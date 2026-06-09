<?php

namespace Tests\Feature;

use App\Models\StaffDetail;
use App\Models\StaffLoginLog;
use App\Models\StaffMessage;
use App\Models\StaffTimeLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register Spatie Roles
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
    }

    /**
     * Test staff creation and details model
     */
    public function test_staff_creation_and_attributes()
    {
        $user = User::create([
            'name' => 'John Staff',
            'email' => 'john@company.com',
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('staff');

        $detail = StaffDetail::create([
            'user_id' => $user->id,
            'staff_id' => 'STF-00001',
            'position' => 'Junior Accountant',
            'hourly_rate' => 25.50,
            'hired_at' => now(),
            'is_active' => true,
        ]);

        $this->assertEquals('STF-00001', $detail->staff_id);
        $this->assertEquals('Junior Accountant', $detail->position);
        $this->assertEquals(25.50, $detail->hourly_rate);
        $this->assertTrue($detail->is_active);
        $this->assertTrue($user->hasRole('staff'));
    }

    /**
     * Test active staff can login, but inactive cannot
     */
    public function test_active_and_inactive_staff_login()
    {
        $activeUser = User::create([
            'name' => 'Active Staff',
            'email' => 'active@company.com',
            'password' => Hash::make('password123'),
        ]);
        $activeUser->assignRole('staff');
        StaffDetail::create([
            'user_id' => $activeUser->id,
            'staff_id' => 'STF-00002',
            'is_active' => true,
            'hired_at' => now(),
        ]);

        $inactiveUser = User::create([
            'name' => 'Inactive Staff',
            'email' => 'inactive@company.com',
            'password' => Hash::make('password123'),
        ]);
        $inactiveUser->assignRole('staff');
        StaffDetail::create([
            'user_id' => $inactiveUser->id,
            'staff_id' => 'STF-00003',
            'is_active' => false, // Inactive employee status
            'hired_at' => now(),
        ]);

        // Attempt logging in active staff
        $response = $this->post('/staff/login', [
            'email' => 'active@company.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('staff.dashboard'));
        $this->assertAuthenticatedAs($activeUser);

        // Logout
        $this->post('/logout');

        // Attempt logging in inactive staff
        $response = $this->post('/staff/login', [
            'email' => 'inactive@company.com',
            'password' => 'password123',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test clock in and clock out flow
     */
    public function test_clock_in_and_clock_out_recording()
    {
        $user = User::create([
            'name' => 'Attendance Staff',
            'email' => 'attendance@company.com',
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('staff');
        StaffDetail::create([
            'user_id' => $user->id,
            'staff_id' => 'STF-00004',
            'hourly_rate' => 30.00,
            'is_active' => true,
            'hired_at' => now(),
        ]);

        $this->actingAs($user);

        // Clock In
        $response = $this->post('/staff/clock-in');
        $response->assertStatus(302);
        
        $activeLog = StaffTimeLog::where('user_id', $user->id)->whereNull('clocked_out_at')->first();
        $this->assertNotNull($activeLog);
        $this->assertEquals(30.00, $activeLog->hourly_rate_at_time);

        // Clock Out
        $response = $this->post('/staff/clock-out');
        $response->assertStatus(302);

        $closedLog = StaffTimeLog::where('user_id', $user->id)->whereNotNull('clocked_out_at')->first();
        $this->assertNotNull($closedLog);
        $this->assertTrue($closedLog->duration_seconds >= 0);
    }

    /**
     * Test staff chat with assigned officer
     */
    public function test_staff_officer_chat()
    {
        $admin = User::create([
            'name' => 'Admin Officer',
            'email' => 'admin@company.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole('admin');

        $staffUser = User::create([
            'name' => 'Staff Chat',
            'email' => 'staffchat@company.com',
            'password' => Hash::make('password123'),
        ]);
        $staffUser->assignRole('staff');
        StaffDetail::create([
            'user_id' => $staffUser->id,
            'staff_id' => 'STF-00005',
            'assigned_officer_id' => $admin->id,
            'is_active' => true,
            'hired_at' => now(),
        ]);

        // Staff send message
        $this->actingAs($staffUser);
        $response = $this->post('/staff/messages', [
            'message' => 'Hello Officer, please review my ledger.',
        ]);
        $response->assertStatus(302);

        $msg = StaffMessage::where('staff_user_id', $staffUser->id)->first();
        $this->assertNotNull($msg);
        $this->assertEquals('Hello Officer, please review my ledger.', $msg->message);
        $this->assertEquals($staffUser->id, $msg->sender_id);

        // Admin reply
        $this->actingAs($admin);
        $response = $this->post(route('admin.staff.send-message', $staffUser->id), [
            'message' => 'I have reviewed it. It is approved.',
        ]);
        $response->assertStatus(302);

        $replies = StaffMessage::where('staff_user_id', $staffUser->id)->orderBy('created_at', 'desc')->first();
        $this->assertEquals('I have reviewed it. It is approved.', $replies->message);
        $this->assertEquals($admin->id, $replies->sender_id);
    }
}
