<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\StaffDetail;
use App\Models\StaffLoginLog;
use App\Models\StaffMessage;
use App\Models\StaffTimeLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
        parent::__construct();
    }

    /**
     * Display list of all staff
     */
    public function index()
    {
        try {
            $staffUsers = User::role('staff')->with('staffDetail')->get();
            $title = __('Staff Directory');

            return view('backend.pages.staff.index', compact('staffUsers', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show form to create staff
     */
    public function create()
    {
        try {
            $title = __('Add Staff Member');
            $officers = User::role('admin')->get(); // Admins act as assigned officers
            $staff = null;

            return view('backend.pages.staff.form', compact('title', 'officers', 'staff'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store new staff
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'hired_at' => 'required|date',
            'next_pay_date' => 'nullable|date',
            'assigned_officer_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
            'bonus' => 'nullable|numeric|min:0',
            'debt' => 'nullable|numeric|min:0',
            'reimbursement' => 'nullable|numeric|min:0',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone ?: '',
                'address' => $request->address ?: '',
            ]);

            // Assign role
            $user->assignRole('staff');

            // Generate unique Staff ID
            $staffId = 'STF-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

            // Create staff details
            StaffDetail::create([
                'user_id' => $user->id,
                'staff_id' => $staffId,
                'position' => $request->position,
                'hourly_rate' => $request->hourly_rate,
                'hired_at' => $request->hired_at,
                'next_pay_date' => $request->next_pay_date,
                'assigned_officer_id' => $request->assigned_officer_id,
                'is_active' => $request->is_active,
                'bonus' => $request->bonus ?: 0.00,
                'debt' => $request->debt ?: 0.00,
                'reimbursement' => $request->reimbursement ?: 0.00,
            ]);

            return redirect()->route('admin.staff.index')->with('success', __('Staff member registered successfully. ID: ' . $staffId));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show form to edit staff
     */
    public function edit($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            
            // Ensure details exist
            if (!$staff->staffDetail) {
                StaffDetail::create([
                    'user_id' => $staff->id,
                    'staff_id' => 'STF-' . str_pad($staff->id, 5, '0', STR_PAD_LEFT),
                    'hired_at' => now(),
                    'is_active' => true,
                ]);
                $staff->load('staffDetail');
            }

            $title = __('Edit Staff Member');
            $officers = User::role('admin')->get();

            return view('backend.pages.staff.form', compact('title', 'officers', 'staff'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update staff details
     */
    public function update(Request $request, $id)
    {
        $staff = User::role('staff')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'hired_at' => 'required|date',
            'next_pay_date' => 'nullable|date',
            'assigned_officer_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
            'bonus' => 'nullable|numeric|min:0',
            'debt' => 'nullable|numeric|min:0',
            'reimbursement' => 'nullable|numeric|min:0',
        ]);

        try {
            // Update User
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone ?: '',
                'address' => $request->address ?: '',
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $staff->update($userData);

            // Update Staff Detail
            $staff->staffDetail->update([
                'position' => $request->position,
                'hourly_rate' => $request->hourly_rate,
                'hired_at' => $request->hired_at,
                'next_pay_date' => $request->next_pay_date,
                'assigned_officer_id' => $request->assigned_officer_id,
                'is_active' => $request->is_active,
                'bonus' => $request->bonus ?: 0.00,
                'debt' => $request->debt ?: 0.00,
                'reimbursement' => $request->reimbursement ?: 0.00,
            ]);

            return redirect()->route('admin.staff.index')->with('success', __('Staff member updated successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove staff
     */
    public function destroy($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $staff->delete(); // Cascades deletes to details, login logs, time logs, and messages due to foreign key constraints

            return redirect()->route('admin.staff.index')->with('success', __('Staff member deleted successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display staff check-in history
     */
    public function timeLogs($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $timeLogs = $staff->staffTimeLogs()->orderBy('clocked_in_at', 'desc')->get();
            $title = __('Time & Wage Logs for ') . $staff->name;

            return view('backend.pages.staff.time-logs', compact('staff', 'timeLogs', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display staff login audit trail
     */
    public function loginLogs($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $loginLogs = $staff->staffLoginLogs()->orderBy('logged_in_at', 'desc')->get();
            $title = __('Login Logs for ') . $staff->name;

            return view('backend.pages.staff.login-logs', compact('staff', 'loginLogs', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * View messages / Chat panel with staff
     */
    public function messages($id)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $adminUser = Auth::user();

            $messages = StaffMessage::where('staff_user_id', $staff->id)
                ->where('officer_user_id', $adminUser->id)
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark incoming messages as read
            StaffMessage::where('staff_user_id', $staff->id)
                ->where('officer_user_id', $adminUser->id)
                ->where('sender_id', $staff->id)
                ->where('read', false)
                ->update(['read' => true]);

            $title = __('Chat with ') . $staff->name;

            return view('backend.pages.staff.messages', compact('staff', 'messages', 'title'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Send message to staff
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $staff = User::role('staff')->findOrFail($id);
            $adminUser = Auth::user();

            StaffMessage::create([
                'staff_user_id' => $staff->id,
                'officer_user_id' => $adminUser->id,
                'sender_id' => $adminUser->id,
                'message' => $request->message,
                'read' => false,
            ]);

            return $this->backWithSuccess(__('Message sent successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download payment forms uploaded by staff
     */
    public function downloadPaymentForm($id, $type)
    {
        try {
            $staff = User::role('staff')->findOrFail($id);
            $detail = $staff->staffDetail;

            if (!$detail) {
                abort(404, 'Staff details not found.');
            }

            $filePath = '';
            if ($type === 'void_check') {
                $filePath = public_path($detail->void_check_path);
            } elseif ($type === 'direct_deposit') {
                $filePath = public_path($detail->direct_deposit_form_path);
            } else {
                abort(404, 'Invalid document type.');
            }

            if (empty($filePath) || !file_exists($filePath)) {
                return $this->backWithError(__('The requested document was not found or has not been uploaded.'));
            }

            return response()->download($filePath);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Verify payment details
     */
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'payment_verified' => 'required|boolean',
        ]);

        try {
            $staff = User::role('staff')->findOrFail($id);
            $detail = $staff->staffDetail;

            if (!$detail) {
                abort(404, 'Staff details not found.');
            }

            $detail->update([
                'payment_verified' => $request->payment_verified,
            ]);

            $statusStr = $request->payment_verified ? __('verified') : __('unverified');
            return $this->backWithSuccess(__('Staff payment details marked as ') . $statusStr);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
