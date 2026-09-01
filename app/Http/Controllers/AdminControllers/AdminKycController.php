<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ClientKycDocument;
use App\Models\GeneralSettings;
use App\Models\SystemAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminKycController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        parent::__construct();
    }

    /**
     * Default KYC Settings Structure
     */
    public static function getDefaultKycSettings(): array
    {
        return [
            'kyc_title' => 'Legal & CPA Due Diligence & Identity Verification (KYC Hub)',
            'kyc_subtitle' => 'Regulatory compliance, client identity verification, and encrypted evidence vault.',
            'general_instructions' => 'Please upload clear color scans or high-resolution photos of the requested documents. All files are encrypted using 256-bit AES cryptographic protocols.',
            'require_address' => 1,
            'require_dob' => 1,
            'require_phone' => 1,
            'document_types' => [
                [
                    'key' => 'passport_id',
                    'name' => 'Government ID / Passport / Driver License',
                    'description' => 'Valid, unexpired government-issued photo identification.',
                    'required' => true,
                    'case_types' => 'All Cases'
                ],
                [
                    'key' => 'proof_of_address',
                    'name' => 'Proof of Physical Residence',
                    'description' => 'Utility bill, bank statement, or official letter dated within the last 90 days.',
                    'required' => true,
                    'case_types' => 'All Cases'
                ],
                [
                    'key' => 'financial_ledger',
                    'name' => 'Financial Statement / Source of Funds Proof',
                    'description' => 'Official banking record or transaction statement demonstrating asset origin.',
                    'required' => false,
                    'case_types' => 'Asset Recovery, Tax Dispute'
                ],
                [
                    'key' => 'tax_clearance',
                    'name' => 'Prior Tax Filing / W-2 / 1099 Transcript',
                    'description' => 'IRS or regional tax authority document for CPA representation.',
                    'required' => false,
                    'case_types' => 'Tax Audit, IRS Representation'
                ],
                [
                    'key' => 'corporate_resolution',
                    'name' => 'Corporate Registration & Authority Resolution',
                    'description' => 'Articles of Incorporation or Certificate of Good Standing for entity clients.',
                    'required' => false,
                    'case_types' => 'Corporate Law, Retainer'
                ],
            ]
        ];
    }

    /**
     * Get active KYC configuration
     */
    public static function getKycSettings(): array
    {
        $defaults = self::getDefaultKycSettings();
        try {
            $general = GeneralSettings::first();
            if ($general && !empty($general->kyc_settings)) {
                $dbKyc = is_array($general->kyc_settings) ? $general->kyc_settings : json_decode($general->kyc_settings, true);
                if (!empty($dbKyc) && is_array($dbKyc)) {
                    return array_merge($defaults, $dbKyc);
                }
            }
        } catch (\Throwable $e) {}

        return $defaults;
    }

    /**
     * Show KYC Form & Fields Configuration page (IFW Replica)
     */
    public function config()
    {
        try {
            $title = __('KYC & Identity Verification Form Configuration');
            $kycSettings = self::getKycSettings();
            return view('backend.pages.kyc.config', compact('title', 'kycSettings'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save KYC Configuration
     */
    public function saveConfig(Request $request)
    {
        $request->validate([
            'kyc_title' => 'required|string|max:255',
            'kyc_subtitle' => 'nullable|string|max:500',
            'general_instructions' => 'nullable|string',
            'doc_keys' => 'nullable|array',
            'doc_names' => 'nullable|array',
            'doc_descriptions' => 'nullable|array',
            'doc_case_types' => 'nullable|array',
            'doc_required' => 'nullable|array',
        ]);

        try {
            $documentTypes = [];
            if ($request->has('doc_names')) {
                foreach ($request->doc_names as $idx => $name) {
                    if (empty($name)) continue;
                    $key = $request->doc_keys[$idx] ?? 'doc_' . uniqid();
                    $documentTypes[] = [
                        'key' => $key,
                        'name' => $name,
                        'description' => $request->doc_descriptions[$idx] ?? '',
                        'required' => isset($request->doc_required[$idx]),
                        'case_types' => $request->doc_case_types[$idx] ?? 'All Cases',
                    ];
                }
            }

            $kycData = [
                'kyc_title' => $request->kyc_title,
                'kyc_subtitle' => $request->kyc_subtitle,
                'general_instructions' => $request->general_instructions,
                'require_address' => $request->has('require_address') ? 1 : 0,
                'require_dob' => $request->has('require_dob') ? 1 : 0,
                'require_phone' => $request->has('require_phone') ? 1 : 0,
                'document_types' => $documentTypes,
            ];

            $general = GeneralSettings::first();
            if (!$general) {
                $general = new GeneralSettings();
            }
            $general->kyc_settings = $kycData;
            $general->save();

            SystemAuditLog::logAction('KYC_CONFIG_UPDATED', 'Admin modified KYC form fields and requested document types.', Auth::id(), 'admin');

            return redirect()->back()->with('success', __('KYC form configuration and document requirements updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * List all submitted Client KYC Documents
     */
    public function submissions(Request $request)
    {
        try {
            $title = __('KYC Verification Submissions & Approvals');
            $status = $request->get('status', 'all');

            $query = ClientKycDocument::with(['client', 'clientCase'])->orderBy('id', 'desc');
            if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'])) {
                $query->where('status', $status);
            }

            $submissions = $query->paginate(25);
            $pendingCount = ClientKycDocument::where('status', 'pending')->count();
            $approvedCount = ClientKycDocument::where('status', 'approved')->count();
            $rejectedCount = ClientKycDocument::where('status', 'rejected')->count();

            return view('backend.pages.kyc.submissions', compact(
                'title', 'submissions', 'status', 'pendingCount', 'approvedCount', 'rejectedCount'
            ));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update KYC Document Status (Approve / Reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        try {
            $doc = ClientKycDocument::with('client')->findOrFail($id);
            $doc->status = $request->status;
            $doc->admin_notes = $request->admin_notes;
            $doc->verified_by = Auth::id();
            $doc->verified_at = now();
            $doc->save();

            SystemAuditLog::logAction('KYC_STATUS_UPDATED', "Updated KYC doc #{$doc->id} ({$doc->document_name}) to status: {$doc->status}", Auth::id(), 'admin');
            ActivityLog::log('KYC Verification Updated', "Marked KYC document {$doc->document_name} for client " . ($doc->client->name ?? 'Client') . " as " . ucfirst($doc->status));

            return redirect()->back()->with('success', __('KYC document status updated to ') . ucfirst($doc->status));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
