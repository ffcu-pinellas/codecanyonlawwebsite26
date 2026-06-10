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
            $clients = User::role('client')->orderBy('name', 'asc')->get();
            
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
            'status' => 'required|in:unpaid,paid,cancelled',
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
            $clients = User::role('client')->orderBy('name', 'asc')->get();
            
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
            'status' => 'required|in:unpaid,paid,cancelled',
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

            return redirect()->back()->with('success', __('Invoice status updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
