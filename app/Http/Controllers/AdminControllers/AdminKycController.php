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
     * Default IFW Dynamic KYC Fields
     */
    public static function getDefaultFields(): array
    {
        return [
            [
                'order' => 1,
                'label' => 'Full Legal Name',
                'db_name' => 'full_name',
                'type' => 'TEXT',
                'options' => '',
                'required' => true,
            ],
            [
                'order' => 2,
                'label' => 'Date of Birth',
                'db_name' => 'dob',
                'type' => 'DATE',
                'options' => '',
                'required' => true,
            ],
            [
                'order' => 3,
                'label' => 'ID Type (Passport/License)',
                'db_name' => 'id_type',
                'type' => 'TEXT',
                'options' => '',
                'required' => true,
            ],
            [
                'order' => 4,
                'label' => 'ID Number',
                'db_name' => 'id_number',
                'type' => 'TEXT',
                'options' => '',
                'required' => true,
            ],
            [
                'order' => 5,
                'label' => 'ID Front Scan (Upload)',
                'db_name' => 'id_front',
                'type' => 'FILE',
                'options' => '',
                'required' => true,
            ],
            [
                'order' => 6,
                'label' => 'ID Back Scan (Upload)',
                'db_name' => 'id_back',
                'type' => 'FILE',
                'options' => '',
                'required' => true,
            ],
            [
                'order' => 7,
                'label' => 'Social Security Number',
                'db_name' => 'social_security_number',
                'type' => 'NUMBER',
                'options' => '',
                'required' => true,
            ],
        ];
    }

    /**
     * Get active configured KYC fields from database
     */
    public static function getFields(): array
    {
        try {
            $general = GeneralSettings::first();
            if ($general && !empty($general->kyc_settings)) {
                $settings = is_array($general->kyc_settings) ? $general->kyc_settings : json_decode($general->kyc_settings, true);
                if (!empty($settings['fields']) && is_array($settings['fields'])) {
                    // Sort by order
                    usort($settings['fields'], function($a, $b) {
                        return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
                    });
                    return $settings['fields'];
                }
            }
        } catch (\Throwable $e) {}

        return self::getDefaultFields();
    }

    /**
     * Save all fields array to Database
     */
    public static function saveFields(array $fields): void
    {
        $general = GeneralSettings::first();
        if (!$general) {
            $general = new GeneralSettings();
        }
        $settings = is_array($general->kyc_settings) ? $general->kyc_settings : (json_decode($general->kyc_settings, true) ?: []);
        $settings['fields'] = $fields;
        $general->kyc_settings = $settings;
        $general->save();
    }

    /**
     * Main KYC Verification & Review Page (Exact IFW Replica)
     */
    public function index(Request $request)
    {
        try {
            $title = __('KYC Verification & Client Due Diligence');
            $fields = self::getFields();

            $submissions = ClientKycDocument::with(['client', 'clientCase'])
                ->orderBy('id', 'desc')
                ->paginate(20);

            return view('backend.pages.kyc.index', compact('title', 'fields', 'submissions'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Add a new dynamic KYC field
     */
    public function addField(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:150',
            'db_name' => 'required|string|max:100',
            'type' => 'required|string|in:TEXT,FILE,DATE,NUMBER,TEXTAREA,SELECT',
            'order' => 'nullable|integer',
        ]);

        try {
            $fields = self::getFields();
            $dbName = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(trim($request->db_name)));

            // Check if already exists
            foreach ($fields as $f) {
                if (($f['db_name'] ?? '') === $dbName) {
                    return redirect()->back()->with('error', __('A field with DB Name ":name" already exists.', ['name' => $dbName]));
                }
            }

            $fields[] = [
                'order' => $request->order ?: count($fields) + 1,
                'label' => trim($request->label),
                'db_name' => $dbName,
                'type' => strtoupper($request->type),
                'options' => $request->options ?? '',
                'required' => $request->has('required'),
            ];

            self::saveFields($fields);
            SystemAuditLog::logAction('KYC_FIELD_ADDED', "Added new KYC field: {$request->label} ({$dbName})", Auth::id(), 'admin');

            return redirect()->back()->with('success', __('KYC field added successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Edit/Update an existing dynamic KYC field
     */
    public function updateField(Request $request)
    {
        $request->validate([
            'original_db_name' => 'required|string',
            'label' => 'required|string|max:150',
            'db_name' => 'required|string|max:100',
            'type' => 'required|string|in:TEXT,FILE,DATE,NUMBER,TEXTAREA,SELECT',
            'order' => 'nullable|integer',
        ]);

        try {
            $fields = self::getFields();
            $origName = $request->original_db_name;
            $newDbName = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(trim($request->db_name)));

            $updated = false;
            foreach ($fields as &$f) {
                if (($f['db_name'] ?? '') === $origName) {
                    $f['label'] = trim($request->label);
                    $f['db_name'] = $newDbName;
                    $f['type'] = strtoupper($request->type);
                    $f['order'] = $request->order ?: $f['order'];
                    $f['required'] = $request->has('required');
                    $updated = true;
                    break;
                }
            }

            if ($updated) {
                self::saveFields($fields);
                SystemAuditLog::logAction('KYC_FIELD_UPDATED', "Updated KYC field: {$request->label} ({$newDbName})", Auth::id(), 'admin');
                return redirect()->back()->with('success', __('KYC field updated successfully.'));
            }

            return redirect()->back()->with('error', __('Field not found.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete a dynamic KYC field
     */
    public function deleteField(Request $request, $dbName)
    {
        try {
            $fields = self::getFields();
            $newFields = [];
            foreach ($fields as $f) {
                if (($f['db_name'] ?? '') !== $dbName) {
                    $newFields[] = $f;
                }
            }

            self::saveFields($newFields);
            SystemAuditLog::logAction('KYC_FIELD_DELETED', "Deleted KYC field: {$dbName}", Auth::id(), 'admin');

            return redirect()->back()->with('success', __('KYC field removed successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update Submission Status (Approve / Reject)
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
            $doc->reviewed_by = Auth::id();
            $doc->reviewed_at = now();
            $doc->save();

            SystemAuditLog::logAction('KYC_STATUS_UPDATED', "KYC doc #{$doc->id} for client " . ($doc->client->name ?? 'Client') . " status set to: {$doc->status}", Auth::id(), 'admin');
            ActivityLog::log('KYC Verification Updated', "Marked KYC document #{$doc->id} as " . ucfirst($doc->status));

            return redirect()->back()->with('success', __('KYC document status updated to ') . ucfirst($doc->status));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
