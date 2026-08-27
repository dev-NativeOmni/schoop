<?php

namespace Tests\Feature;

use App\Models\BoardingBed;
use App\Models\BoardingDormitory;
use App\Models\BoardingLeaveRequest;
use App\Models\BoardingRollCallRecord;
use App\Models\BoardingRollCallSession;
use App\Models\BoardingRoom;
use App\Models\BoardingStudentAssignment;
use App\Models\ClassRoom;
use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Student;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSchoolMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoardingRollCallLeaveTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $parentUser;
    private User $studentUser;
    private School $school;
    private Student $student;
    private BoardingDormitory $dormitory;
    private BoardingRoom $room;
    private BoardingBed $bed;
    private BoardingStudentAssignment $assignment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SystemModuleSeeder::class);
        $this->seed(\Database\Seeders\SubscriptionPlanSeeder::class);
        $this->seed(\Database\Seeders\PlanModuleSeeder::class);

        $adminRole = Role::query()->where('name', 'admin')->firstOrFail();
        $parentRole = Role::query()->where('name', 'parent')->firstOrFail();
        $studentRole = Role::query()->where('name', 'student')->firstOrFail();

        $this->school = School::query()->create([
            'name' => 'Pesantren Boarding Test',
            'code' => 'PBT01',
            'is_active' => true,
        ]);

        $plan = SubscriptionPlan::query()->where('code', 'enterprise')->firstOrFail();

        SchoolSubscription::query()->create([
            'school_id' => $this->school->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addYear(),
        ]);

        $this->admin = User::query()->create([
            'role_id' => $adminRole->id,
            'school_id' => $this->school->id,
            'name' => 'Admin Asrama',
            'username' => 'admin_asrama',
            'email' => 'admin_asrama@hafizplus.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $this->admin->id,
            'school_id' => $this->school->id,
            'role_id' => $adminRole->id,
            'membership_status' => 'active',
            'is_default' => true,
        ]);

        $classRoom = ClassRoom::query()->create([
            'school_id' => $this->school->id,
            'name' => 'Kelas 7A Tahfizh',
            'grade' => '7',
            'is_active' => true,
        ]);

        $this->student = Student::query()->create([
            'school_id' => $this->school->id,
            'class_room_id' => $classRoom->id,
            'full_name' => 'Muhammad Zaidan',
            'student_number' => 'NIS-2026-001',
            'gender' => 'male',
            'status' => 'active',
        ]);

        $this->dormitory = BoardingDormitory::query()->create([
            'school_id' => $this->school->id,
            'name' => 'Gedung Umar bin Khattab',
            'gender' => 'male',
            'is_active' => true,
        ]);

        $this->room = BoardingRoom::query()->create([
            'school_id' => $this->school->id,
            'boarding_dormitory_id' => $this->dormitory->id,
            'name' => 'Kamar 101 - Al-Fatih',
            'capacity' => 4,
            'is_active' => true,
        ]);

        $this->bed = BoardingBed::query()->create([
            'school_id' => $this->school->id,
            'boarding_room_id' => $this->room->id,
            'code' => 'R101-A',
            'status' => 'occupied',
        ]);

        $this->assignment = BoardingStudentAssignment::query()->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'boarding_dormitory_id' => $this->dormitory->id,
            'boarding_room_id' => $this->room->id,
            'boarding_bed_id' => $this->bed->id,
            'start_date' => now()->subMonths(2)->format('Y-m-d'),
            'status' => 'active',
        ]);

        // Parent profile and user
        $this->parentUser = User::query()->create([
            'role_id' => $parentRole->id,
            'school_id' => $this->school->id,
            'name' => 'Wali Zaidan',
            'username' => 'wali_zaidan',
            'email' => 'wali@hafizplus.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $parentProfile = ParentProfile::query()->create([
            'school_id' => $this->school->id,
            'user_id' => $this->parentUser->id,
            'is_active' => true,
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $this->parentUser->id,
            'school_id' => $this->school->id,
            'role_id' => $parentRole->id,
            'membership_status' => 'active',
            'is_default' => true,
        ]);

        $parentProfile->students()->attach($this->student->id, [
            'relationship' => 'father',
            'is_primary' => true,
        ]);

        // Student User
        $this->studentUser = User::query()->create([
            'role_id' => $studentRole->id,
            'school_id' => $this->school->id,
            'name' => 'Muhammad Zaidan',
            'username' => 'zaidan_student',
            'email' => 'zaidan@hafizplus.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $this->studentUser->id,
            'school_id' => $this->school->id,
            'role_id' => $studentRole->id,
            'membership_status' => 'active',
            'is_default' => true,
        ]);

        $this->student->update(['user_id' => $this->studentUser->id]);

        app(\App\Services\Billing\ModuleAccessService::class)->flushCache();
    }

    public function test_can_view_boarding_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('boarding.dashboard'));

        $response->assertOk();
        $response->assertViewIs('boarding.dashboard');
        $response->assertSee('Manajemen Asrama Pesantren');
        $response->assertSee('Okupansi Asrama');
        $response->assertSee('Navigasi Operasional Asrama');
    }

    public function test_can_create_roll_call_session_and_populates_active_students(): void
    {
        $response = $this->actingAs($this->admin)->post(route('boarding.roll-calls.store'), [
            'boarding_dormitory_id' => $this->dormitory->id,
            'boarding_room_id' => $this->room->id,
            'session_date' => now()->format('Y-m-d'),
            'session_type' => 'night',
        ]);

        $this->assertDatabaseHas('boarding_roll_call_sessions', [
            'boarding_dormitory_id' => $this->dormitory->id,
            'boarding_room_id' => $this->room->id,
            'session_type' => 'night',
            'status' => 'open',
        ]);

        $session = BoardingRollCallSession::query()->latest('id')->first();
        $response->assertRedirect(route('boarding.roll-calls.show', $session->id));

        // Ensure student record was pre-populated
        $this->assertDatabaseHas('boarding_roll_call_records', [
            'boarding_roll_call_session_id' => $session->id,
            'student_id' => $this->student->id,
            'status' => 'present',
        ]);
    }

    public function test_can_update_roll_call_records_in_bulk(): void
    {
        $session = BoardingRollCallSession::query()->create([
            'boarding_dormitory_id' => $this->dormitory->id,
            'boarding_room_id' => $this->room->id,
            'session_date' => now()->format('Y-m-d'),
            'session_type' => 'night',
            'status' => 'open',
            'created_by' => $this->admin->id,
        ]);

        BoardingRollCallRecord::query()->create([
            'boarding_roll_call_session_id' => $session->id,
            'student_id' => $this->student->id,
            'status' => 'present',
            'recorded_by_user_id' => $this->admin->id,
            'recorded_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post(route('boarding.roll-calls.records.store', $session->id), [
            'records' => [
                [
                    'student_id' => $this->student->id,
                    'status' => 'sick',
                    'note' => 'Dirawat di UKS Pesantren demam',
                ],
            ],
        ]);

        $response->assertRedirect(route('boarding.roll-calls.show', $session->id));
        $this->assertDatabaseHas('boarding_roll_call_records', [
            'boarding_roll_call_session_id' => $session->id,
            'student_id' => $this->student->id,
            'status' => 'sick',
            'note' => 'Dirawat di UKS Pesantren demam',
        ]);
    }

    public function test_can_close_roll_call_session(): void
    {
        $session = BoardingRollCallSession::query()->create([
            'boarding_dormitory_id' => $this->dormitory->id,
            'session_date' => now()->format('Y-m-d'),
            'session_type' => 'night',
            'status' => 'open',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->post(route('boarding.roll-calls.close', $session->id));

        $response->assertRedirect(route('boarding.roll-calls.show', $session->id));
        $this->assertDatabaseHas('boarding_roll_call_sessions', [
            'id' => $session->id,
            'status' => 'closed',
        ]);
    }

    public function test_can_create_and_approve_leave_request_and_mark_returned(): void
    {
        // 1. Submit leave request
        $response = $this->actingAs($this->admin)->post(route('boarding.leave-requests.store'), [
            'student_id' => $this->student->id,
            'type' => 'home_visit',
            'leave_start_at' => now()->format('Y-m-d\TH:i'),
            'leave_end_at' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'destination' => 'Rumah Orang Tua (Bandung)',
            'reason' => 'Menghadiri walimah pernikahan kakak kandung',
        ]);

        $leaveRequest = BoardingLeaveRequest::query()->latest('id')->first();
        $this->assertNotNull($leaveRequest);
        $this->assertEquals('submitted', $leaveRequest->status);

        // 2. View Digital Gate Pass
        $showResponse = $this->actingAs($this->admin)->get(route('boarding.leave-requests.show', $leaveRequest->id));
        $showResponse->assertOk();
        $showResponse->assertSee('SURAT PAS KELUAR ASRAMA');
        $showResponse->assertSee('Rumah Orang Tua (Bandung)');

        // 3. Approve leave request
        $approveResponse = $this->actingAs($this->admin)->post(route('boarding.leave-requests.approve', $leaveRequest->id), [
            'approval_note' => 'Disetujui ustadz, wajib lapor pos satpam',
        ]);

        $leaveRequest->refresh();
        $this->assertEquals('approved', $leaveRequest->status);
        $this->assertEquals($this->admin->id, $leaveRequest->approved_by_user_id);

        // 4. Mark returned (Check-in gate)
        $returnResponse = $this->actingAs($this->admin)->post(route('boarding.leave-requests.mark-returned', $leaveRequest->id));
        $leaveRequest->refresh();
        $this->assertEquals('returned', $leaveRequest->status);
        $this->assertNotNull($leaveRequest->returned_at);
    }

    public function test_parent_and_student_can_view_boarding_portal(): void
    {
        // Parent View
        $parentResponse = $this->actingAs($this->parentUser)->get(route('portal.parent.boarding'));
        $parentResponse->assertOk();
        $parentResponse->assertSee('Gedung Umar bin Khattab');
        $parentResponse->assertSee('Kamar 101 - Al-Fatih');

        // Student View
        $studentResponse = $this->actingAs($this->studentUser)->get(route('portal.student.boarding'));
        $studentResponse->assertOk();
        $studentResponse->assertSee('Gedung Umar bin Khattab');
        $studentResponse->assertSee('Kamar 101 - Al-Fatih');
    }
}
