<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\ClientCase;
use App\Models\CaseDocument;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminCaseController extends Controller
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
                $cases = ClientCase::with(['client', 'attorney'])->orderBy('created_at', 'desc')->get();
            } else {
                $cases = ClientCase::with(['client', 'attorney'])
                    ->where('attorney_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $title = __('Case Directory');
            return view('backend.pages.cases.index', compact('cases', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $title = __('Create New Case');
            $clients = User::role('client')->orderBy('name', 'asc')->get();
            $attorneys = User::role(['admin', 'attorney'])->orderBy('name', 'asc')->get();
            $case = null;

            return view('backend.pages.cases.form', compact('title', 'clients', 'attorneys', 'case'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:users,id',
            'attorney_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,active,suspended,resolved',
            'court_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            // Generate unique Case Number
            do {
                $caseNumber = 'CS-' . rand(100000, 999999);
            } while (ClientCase::where('case_number', $caseNumber)->exists());

            $case = ClientCase::create([
                'case_number' => $caseNumber,
                'title' => $request->title,
                'description' => $request->description,
                'client_id' => $request->client_id,
                'attorney_id' => $request->attorney_id,
                'status' => $request->status,
                'court_date' => $request->court_date,
            ]);

            ActivityLog::log('Case Created', 'Created case ' . $case->case_number . ': "' . $case->title . '"');
            $this->sendSlackNotification('📂 New Legal Case Created: ' . $case->case_number . ' - "' . $case->title . '"');

            return redirect()->route('admin.cases.index')->with('success', __('Case created successfully. Case Number: ') . $case->case_number);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $case = ClientCase::findOrFail($id);
            
            // Limit attorney access
            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $title = __('Edit Case #') . $case->case_number;
            $clients = User::role('client')->orderBy('name', 'asc')->get();
            $attorneys = User::role(['admin', 'attorney'])->orderBy('name', 'asc')->get();

            return view('backend.pages.cases.form', compact('title', 'clients', 'attorneys', 'case'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $case = ClientCase::findOrFail($id);

        if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:users,id',
            'attorney_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,active,suspended,resolved',
            'court_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        try {
            $case->update([
                'title' => $request->title,
                'description' => $request->description,
                'client_id' => $request->client_id,
                'attorney_id' => $request->attorney_id,
                'status' => $request->status,
                'court_date' => $request->court_date,
            ]);

            ActivityLog::log('Case Updated', 'Updated case ' . $case->case_number);

            return redirect()->route('admin.cases.index')->with('success', __('Case details updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $case = ClientCase::findOrFail($id);

            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }

            ActivityLog::log('Case Deleted', 'Deleted case ' . $case->case_number);
            $case->delete();

            return redirect()->route('admin.cases.index')->with('success', __('Case deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,png,jpg,jpeg,doc,docx,xlsx|max:20480',
        ]);

        try {
            $case = ClientCase::findOrFail($id);

            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $fileName = time() . '_' . uniqid() . '.' . $request->file->getClientOriginalExtension();
            
            // Ensure target directory exists
            $uploadPath = public_path('upload/case-documents');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $request->file->move($uploadPath, $fileName);

            $document = CaseDocument::create([
                'case_id' => $case->id,
                'user_id' => Auth::id(),
                'title' => $request->title,
                'file_path' => '/upload/case-documents/' . $fileName,
                'file_type' => $request->file->getClientOriginalExtension(),
                'file_size' => $request->file->getSize(),
                'is_client_uploaded' => false,
            ]);

            ActivityLog::log('Document Uploaded', 'Uploaded document "' . $document->title . '" for case ' . $case->case_number);

            return redirect()->back()->with('success', __('Document uploaded successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyDocument($doc_id)
    {
        try {
            $document = CaseDocument::findOrFail($doc_id);
            $case = $document->clientCase;

            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            // Remove file from disk
            $filePath = public_path($document->file_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            ActivityLog::log('Document Deleted', 'Deleted document "' . $document->title . '" from case ' . $case->case_number);
            $document->delete();

            return redirect()->back()->with('success', __('Document deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function previewDocument($doc_id)
    {
        try {
            $document = CaseDocument::findOrFail($doc_id);
            $case = $document->clientCase;

            // Restrict access
            if (!Auth::user()->hasRole('admin') && $case->attorney_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $filePath = public_path($document->file_path);
            if (!File::exists($filePath)) {
                abort(404, 'File not found on server.');
            }

            return response()->file($filePath);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function activityLogs()
    {
        try {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Unauthorized access.');
            }

            $title = __('System Activity Logs');
            $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(50);

            return view('backend.pages.cases.logs', compact('title', 'logs'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function documentGenerator()
    {
        try {
            $title = __('Legal Document Builder');
            $clients = User::role('client')->orderBy('name', 'asc')->get();

            return view('backend.pages.cases.doc-generator', compact('title', 'clients'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function generateDocument(Request $request)
    {
        $request->validate([
            'template_type' => 'required|in:retainer,power_of_attorney,cpa_auth',
            'client_id' => 'required|exists:users,id',
        ]);

        try {
            $client = User::findOrFail($request->client_id);
            $title = '';
            $content = '';
            $dateStr = date('F d, Y');

            if ($request->template_type === 'retainer') {
                $title = __('Legal Representation Retainer Agreement');
                $content = "
                    <h3 style='text-align: center; color: #1e3c72;'>" . __('RETAINER AGREEMENT') . "</h3>
                    <p><strong>" . __('Date:') . "</strong> {$dateStr}</p>
                    <p>This Retainer Agreement is entered into by and between <strong>" . config('app.name', 'Your CPA Expert') . "</strong> (hereafter 'Firm') and <strong>{$client->name}</strong> (hereafter 'Client') residing at <strong>{$client->address}</strong>.</p>
                    <p><strong>1. Scope of Services:</strong> Firm agrees to provide legal/accounting representation and consultation services to Client. Services include case evaluation, document preparation, and filings.</p>
                    <p><strong>2. Fees & Compensation:</strong> Client agrees to pay the agreed hourly rates or flat-fee representation schedules. Invoice statements will be generated cycle-by-cycle.</p>
                    <p><strong>3. Termination:</strong> Either party may terminate representation upon written notice, subject to professional code constraints.</p>
                ";
            } elseif ($request->template_type === 'power_of_attorney') {
                $title = __('General Power of Attorney (POA)');
                $content = "
                    <h3 style='text-align: center; color: #1e3c72;'>" . __('GENERAL POWER OF ATTORNEY') . "</h3>
                    <p><strong>" . __('Date:') . "</strong> {$dateStr}</p>
                    <p>I, <strong>{$client->name}</strong>, residing at <strong>{$client->address}</strong>, hereby appoint <strong>" . config('app.name', 'Your CPA Expert') . "</strong> as my attorney-in-fact to act in my name, place, and stead in any way which I myself could do, if I were personally present, with respect to legal, financial, and tax matters.</p>
                    <p>This Power of Attorney is durable and shall not be affected by subsequent disability or incapacity of the principal.</p>
                ";
            } else {
                $title = __('IRS Form CPA Representation Authorization');
                $content = "
                    <h3 style='text-align: center; color: #1e3c72;'>" . __('IRS TAX REPRESENTATION AUTHORIZATION') . "</h3>
                    <p><strong>" . __('Date:') . "</strong> {$dateStr}</p>
                    <p>The undersigned taxpayer, <strong>{$client->name}</strong>, residing at <strong>{$client->address}</strong>, hereby authorizes <strong>" . config('app.name', 'Your CPA Expert') . "</strong> to represent the taxpayer before the Internal Revenue Service and state departments of revenue regarding tax audits, compliance filings, and balance resolutions.</p>
                ";
            }

            $companySettings = \App\Models\GeneralSettings::first();
            $companyName = $companySettings && $companySettings->site_name ? $companySettings->site_name : config('app.name', 'Your CPA Expert');

            ActivityLog::log('Document Generated', 'Generated ' . $request->template_type . ' template for client ' . $client->name);

            return view('backend.pages.cases.doc-print', compact('title', 'content', 'client', 'companyName', 'dateStr'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
