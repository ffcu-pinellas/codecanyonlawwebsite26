<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ClientCase;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|attorney']);
        parent::__construct();
    }

    public function index()
    {
        try {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                $invoices = Invoice::with(['client', 'clientCase'])->orderBy('created_at', 'desc')->get();
            } else {
                $invoices = Invoice::with(['client', 'clientCase'])
                    ->whereHas('clientCase', function ($query) use ($user) {
                        $query->where('attorney_id', $user->id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $title = __('Client Invoices');
            return view('backend.pages.invoices.index', compact('invoices', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $title = __('Generate New Invoice');
            $clients = User::role(['client', 'staff'])->orderBy('name', 'asc')->get();
            
            // Limit cases based on role
            if (Auth::user()->hasRole('admin')) {
                $cases = ClientCase::orderBy('case_number', 'asc')->get();
            } else {
                $cases = ClientCase::where('attorney_id', Auth::id())->orderBy('case_number', 'asc')->get();
            }
            $invoice = null;

            return view('backend.pages.invoices.form', compact('title', 'clients', 'cases', 'invoice'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'case_id' => 'nullable|exists:client_cases,id',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,pending,paid,cancelled',
            'description' => 'nullable|string',
        ]);

        try {
            // Generate unique Invoice Number
            do {
                $invoiceNumber = 'INV-' . rand(100000, 999999);
            } while (Invoice::where('invoice_number', $invoiceNumber)->exists());

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'client_id' => $request->client_id,
                'case_id' => $request->case_id,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            ActivityLog::log('Invoice Created', 'Generated invoice ' . $invoice->invoice_number . ' for client ' . $invoice->client->name);
            $this->sendSlackNotification('🧾 New Invoice Generated: ' . $invoice->invoice_number . ' - $' . number_format($invoice->amount, 2) . ' for ' . $invoice->client->name);

            // Send via email if requested
            if ($request->send_email) {
                $client = $invoice->client;
                $subject = "Invoice Statement: " . $invoice->invoice_number;
                
                $bodyText = "We are pleased to submit the following invoice statement for professional services rendered to your account. Please find the summary below:\n\n"
                    . "Invoice Number : " . $invoice->invoice_number . "\n"
                    . "Amount Due : $" . number_format($invoice->amount, 2) . "\n"
                    . "Due Date : " . $invoice->due_date->format('M d, Y') . "\n"
                    . "Payment Status : " . strtoupper($invoice->status) . "\n";

                if ($invoice->description) {
                    $bodyText .= "\n" . $invoice->description . "\n";
                }

                $bodyText .= "\nTo view the complete itemized invoice, download a PDF statement, or print layout receipts, please log into your dashboard portal at your convenience.\n"
                    . "Dashboard Link : " . route('client.invoices.show', $invoice->id) . "\n\n"
                    . "If you have any questions or require modifications, please contact our billing support office.";

                $pdfPath = $this->generateInvoicePdfFile($invoice);
                $this->sendEmailNotification($client->email, $subject, $bodyText, $pdfPath, 'Invoice_' . $invoice->invoice_number . '.pdf');
                if ($pdfPath && file_exists($pdfPath)) {
                    @unlink($pdfPath);
                }
                ActivityLog::log('Invoice Email Sent', 'Sent invoice statement email with PDF attachment for ' . $invoice->invoice_number . ' to client ' . $client->name);
            }

            return redirect()->route('admin.invoices.index')->with('success', __('Invoice generated successfully. Invoice Number: ') . $invoice->invoice_number);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            // Limit attorney access
            if (!Auth::user()->hasRole('admin')) {
                if ($invoice->clientCase && $invoice->clientCase->attorney_id !== Auth::id()) {
                    abort(403, 'Unauthorized access.');
                }
            }

            $title = __('Edit Invoice #') . $invoice->invoice_number;
            $clients = User::role(['client', 'staff'])->orderBy('name', 'asc')->get();
            
            if (Auth::user()->hasRole('admin')) {
                $cases = ClientCase::orderBy('case_number', 'asc')->get();
            } else {
                $cases = ClientCase::where('attorney_id', Auth::id())->orderBy('case_number', 'asc')->get();
            }

            return view('backend.pages.invoices.form', compact('title', 'clients', 'cases', 'invoice'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!Auth::user()->hasRole('admin')) {
            if ($invoice->clientCase && $invoice->clientCase->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }
        }

        $request->validate([
            'client_id' => 'required|exists:users,id',
            'case_id' => 'nullable|exists:client_cases,id',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,pending,paid,cancelled',
            'description' => 'nullable|string',
        ]);

        try {
            $invoice->update([
                'client_id' => $request->client_id,
                'case_id' => $request->case_id,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            ActivityLog::log('Invoice Updated', 'Updated invoice ' . $invoice->invoice_number);

            // Send via email if requested
            if ($request->send_email) {
                $client = $invoice->client;
                $subject = "Invoice Statement (Updated): " . $invoice->invoice_number;
                
                $bodyText = "We are pleased to submit the following updated invoice statement for professional services rendered to your account. Please find the summary below:\n\n"
                    . "Invoice Number : " . $invoice->invoice_number . "\n"
                    . "Amount Due : $" . number_format($invoice->amount, 2) . "\n"
                    . "Due Date : " . $invoice->due_date->format('M d, Y') . "\n"
                    . "Payment Status : " . strtoupper($invoice->status) . "\n";

                if ($invoice->description) {
                    $bodyText .= "\n" . $invoice->description . "\n";
                }

                $bodyText .= "\nTo view the complete itemized invoice, download a PDF statement, or print layout receipts, please log into your dashboard portal at your convenience.\n"
                    . "Dashboard Link : " . route('client.invoices.show', $invoice->id) . "\n\n"
                    . "If you have any questions or require modifications, please contact our billing support office.";

                $pdfPath = $this->generateInvoicePdfFile($invoice);
                $this->sendEmailNotification($client->email, $subject, $bodyText, $pdfPath, 'Invoice_' . $invoice->invoice_number . '.pdf');
                if ($pdfPath && file_exists($pdfPath)) {
                    @unlink($pdfPath);
                }
                ActivityLog::log('Invoice Email Sent', 'Sent updated invoice statement email with PDF attachment for ' . $invoice->invoice_number . ' to client ' . $client->name);
            }

            return redirect()->route('admin.invoices.index')->with('success', __('Invoice updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }

            ActivityLog::log('Invoice Deleted', 'Deleted invoice ' . $invoice->invoice_number);
            $invoice->delete();

            return redirect()->route('admin.invoices.index')->with('success', __('Invoice deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function markPaid(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!Auth::user()->hasRole('admin')) {
            if ($invoice->clientCase && $invoice->clientCase->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }
        }

        try {
            $newStatus = $invoice->status === 'paid' ? 'unpaid' : 'paid';
            $invoice->update(['status' => $newStatus]);

            ActivityLog::log('Invoice Status Updated', 'Marked invoice ' . $invoice->invoice_number . ' as ' . strtoupper($newStatus));

            // Telegram Notification
            try {
                $telMsg = "✅ *Invoice Status Manually Updated*\n\n"
                        . "🧾 *Invoice:* {$invoice->invoice_number}\n"
                        . "👤 *Client:* {$invoice->client->name}\n"
                        . "💰 *Amount:* $" . number_format($invoice->amount, 2) . "\n"
                        . "📝 *Status:* Marked as " . strtoupper($newStatus) . "\n"
                        . "📅 *Time:* " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            return redirect()->back()->with('success', __('Invoice status updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sendEmail($id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!Auth::user()->hasRole('admin')) {
            if ($invoice->clientCase && $invoice->clientCase->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }
        }

        try {
            $client = $invoice->client;
            $subject = "Invoice Statement: " . $invoice->invoice_number;
            
            $bodyText = "We are pleased to submit the following invoice statement for professional services rendered to your account. Please find the summary below:\n\n"
                . "Invoice Number : " . $invoice->invoice_number . "\n"
                . "Amount Due : $" . number_format($invoice->amount, 2) . "\n"
                . "Due Date : " . $invoice->due_date->format('M d, Y') . "\n"
                . "Payment Status : " . strtoupper($invoice->status) . "\n";

            if ($invoice->description) {
                $bodyText .= "\n" . $invoice->description . "\n";
            }

            $bodyText .= "\nTo view the complete itemized invoice, download a PDF statement, or print layout receipts, please log into your dashboard portal at your convenience.\n"
                . "Dashboard Link : " . route('client.invoices.show', $invoice->id) . "\n\n"
                . "If you have any questions or require modifications, please contact our billing support office.";

            $pdfPath = $this->generateInvoicePdfFile($invoice);
            $this->sendEmailNotification($client->email, $subject, $bodyText, $pdfPath, 'Invoice_' . $invoice->invoice_number . '.pdf');
            if ($pdfPath && file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            ActivityLog::log('Invoice Email Sent', 'Sent invoice statement email with PDF attachment for ' . $invoice->invoice_number . ' to client ' . $client->name);

            return redirect()->back()->with('success', __('Invoice email sent to client successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function generateInvoicePdfFile($invoice)
    {
        try {
            $companyName = config('app.name', 'Your CPA Expert');
            $companyAddress = config('settings.company_address', '582 Professional Way, Financial District, DC');
            $companyPhone = config('settings.company_phone', '(216) 230-1837');
            $companyEmail = config('settings.company_email', 'support@yourcpaexpert.com');
            $title = __('Invoice ') . $invoice->invoice_number;

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.invoice-pdf', compact('title', 'invoice', 'companyName', 'companyAddress', 'companyPhone', 'companyEmail'));
            $tempPdfPath = tempnam(sys_get_temp_dir(), 'invoice_') . '.pdf';
            $pdf->save($tempPdfPath);
            return $tempPdfPath;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function approvePaymentProof(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!Auth::user()->hasRole('admin')) {
            if ($invoice->clientCase && $invoice->clientCase->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }
        }

        try {
            $invoice->update([
                'status' => 'paid',
            ]);

            ActivityLog::log('Payment Proof Approved', 'Approved offline payment proof for invoice ' . $invoice->invoice_number);
            
            // Telegram Notification
            try {
                $telMsg = "✅ *Offline Payment Proof Approved*\n\n"
                        . "🧾 *Invoice:* {$invoice->invoice_number}\n"
                        . "👤 *Client:* {$invoice->client->name}\n"
                        . "💰 *Amount:* $" . number_format($invoice->amount, 2) . "\n"
                        . "💳 *Method:* {$invoice->payment_method}\n"
                        . "🆔 *Reference:* {$invoice->payment_reference}\n"
                        . "📅 *Time:* " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}
            
            $client = $invoice->client;
            $subject = "Payment Confirmed: Invoice " . $invoice->invoice_number;
            $bodyText = "We are pleased to confirm that we have successfully verified your offline payment proof for Invoice " . $invoice->invoice_number . ".\n\n"
                . "Invoice Number : " . $invoice->invoice_number . "\n"
                . "Amount Paid : $" . number_format($invoice->amount, 2) . "\n"
                . "Status : PAID\n\n"
                . "Thank you for choosing our professional services. You can view or download the paid receipt statement by logging into your dashboard portal.";
            
            $pdfPath = $this->generateInvoicePdfFile($invoice);
            $this->sendEmailNotification($client->email, $subject, $bodyText, $pdfPath, 'Invoice_' . $invoice->invoice_number . '_PAID.pdf');
            if ($pdfPath && file_exists($pdfPath)) {
                @unlink($pdfPath);
            }

            return redirect()->back()->with('success', __('Offline payment proof approved successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function rejectPaymentProof(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!Auth::user()->hasRole('admin')) {
            if ($invoice->clientCase && $invoice->clientCase->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }
        }

        try {
            ActivityLog::log('Payment Proof Rejected', 'Rejected offline payment proof for invoice ' . $invoice->invoice_number . '. Reason: ' . ($request->rejection_reason ?: 'No reason provided'));

            $invoice->update([
                'status' => 'unpaid',
                'payment_notes' => ($invoice->payment_notes ? $invoice->payment_notes . "\n" : "") . "[Rejected on " . date('Y-m-d') . "]: " . ($request->rejection_reason ?: 'Invalid payment slip or reference details'),
            ]);

            // Telegram Notification
            try {
                $telMsg = "❌ *Offline Payment Proof Rejected*\n\n"
                        . "🧾 *Invoice:* {$invoice->invoice_number}\n"
                        . "👤 *Client:* {$invoice->client->name}\n"
                        . "💰 *Amount:* $" . number_format($invoice->amount, 2) . "\n"
                        . "⚠️ *Reason:* " . ($request->rejection_reason ?: 'Invalid payment slip or reference details') . "\n"
                        . "📅 *Time:* " . now()->format('Y-m-d H:i:s') . "\n";
                \App\Models\GeneralSettings::sendTelegramNotification($telMsg);
            } catch (\Throwable $e) {}

            $client = $invoice->client;
            $subject = "Payment Proof Rejected: Invoice " . $invoice->invoice_number;
            $bodyText = "Unfortunately, we were unable to verify your offline payment proof for Invoice " . $invoice->invoice_number . ".\n\n"
                . "Invoice Number : " . $invoice->invoice_number . "\n"
                . "Amount Due : $" . number_format($invoice->amount, 2) . "\n"
                . "Rejection Reason : " . ($request->rejection_reason ?: 'The reference number or uploaded slip was invalid.') . "\n\n"
                . "Please review the transaction details or upload a valid bank wire / check deposit confirmation slip via the client dashboard portal.";
            
            $this->sendEmailNotification($client->email, $subject, $bodyText);

            return redirect()->back()->with('success', __('Offline payment proof rejected. Invoice reset to unpaid status.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
