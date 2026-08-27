<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Student;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSchoolMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TalaqqiFastInputTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private School $school;
    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SystemModuleSeeder::class);
        $this->seed(\Database\Seeders\SubscriptionPlanSeeder::class);
        $this->seed(\Database\Seeders\PlanModuleSeeder::class);
        $this->seed(\Database\Seeders\QuranJuzSeeder::class);
        $this->seed(\Database\Seeders\QuranSurahSeeder::class);

        $teacherRole = Role::query()->where('name', 'teacher')->firstOrFail();

        $this->school = School::query()->create([
            'name' => 'Pesantren Tahfizh Test',
            'code' => 'PTT01',
            'is_active' => true,
        ]);

        $plan = SubscriptionPlan::query()->where('code', 'pro')->firstOrFail();

        SchoolSubscription::query()->create([
            'school_id' => $this->school->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addYear(),
        ]);

        $this->teacher = User::query()->create([
            'role_id' => $teacherRole->id,
            'school_id' => $this->school->id,
            'name' => 'Ustadz Ahmad',
            'username' => 'ustadz_ahmad',
            'email' => 'ahmad@hafizplus.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $this->teacher->id,
            'school_id' => $this->school->id,
            'role_id' => $teacherRole->id,
            'membership_status' => 'active',
            'is_default' => true,
        ]);

        $classRoom = ClassRoom::query()->create([
            'school_id' => $this->school->id,
            'name' => 'Halaqah Imam Nafi',
            'is_active' => true,
        ]);

        $this->student = Student::query()->create([
            'school_id' => $this->school->id,
            'class_room_id' => $classRoom->id,
            'full_name' => 'Muhammad Bilal',
            'nis' => '1001',
            'is_active' => true,
        ]);
    }

    public function test_talaqqi_create_view_loads_with_student_metadata(): void
    {
        $this->actingAs($this->teacher);

        $response = $this->get(route('tahfizh.hafalan-records.create'));

        $response->assertStatus(200);
        $response->assertViewHas('studentsData');
        $response->assertViewHas('classRooms');

        $studentsData = $response->viewData('studentsData');
        $this->assertArrayHasKey($this->student->id, $studentsData);
        $this->assertEquals('Muhammad Bilal', $studentsData[$this->student->id]['name']);
        $this->assertEquals(1, $studentsData[$this->student->id]['expected_page']);
        $this->assertEquals(1, $studentsData[$this->student->id]['expected_line']);
    }

    public function test_talaqqi_prefills_expected_next_line_from_previous_setoran(): void
    {
        $this->actingAs($this->teacher);

        // Create first setoran for student: Page 1 Line 1 -> Page 1 Line 10
        HafalanRecord::query()->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'record_date' => now()->subDay()->toDateString(),
            'start_page' => 1,
            'start_line' => 1,
            'end_page' => 1,
            'end_line' => 10,
            'total_lines' => 10,
            'status' => HafalanRecord::STATUS_KURANG,
            'quality_score' => 90,
            'is_sequence_valid' => true,
            'created_by' => $this->teacher->id,
        ]);

        $response = $this->get(route('tahfizh.hafalan-records.create'));

        $response->assertStatus(200);
        $studentsData = $response->viewData('studentsData');

        $this->assertTrue($studentsData[$this->student->id]['has_previous']);
        $this->assertEquals(1, $studentsData[$this->student->id]['last_end_page']);
        $this->assertEquals(10, $studentsData[$this->student->id]['last_end_line']);
        // Next sequential line is Page 1 Line 11!
        $this->assertEquals(1, $studentsData[$this->student->id]['expected_page']);
        $this->assertEquals(11, $studentsData[$this->student->id]['expected_line']);
    }

    public function test_teacher_can_store_hafalan_record_with_automatic_line_calculation(): void
    {
        $this->actingAs($this->teacher);

        $response = $this->post(route('tahfizh.hafalan-records.store'), [
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'record_date' => now()->toDateString(),
            'start_page' => 1,
            'start_line' => 1,
            'end_page' => 1,
            'end_line' => 15,
            'status' => HafalanRecord::STATUS_LUNAS,
            'quality_score' => 95,
            'notes' => 'Alhamdulillah lancar dan tartil.',
        ]);

        $response->assertRedirect(route('tahfizh.hafalan-records.index'));
        $this->assertDatabaseHas('hafalan_records', [
            'student_id' => $this->student->id,
            'total_lines' => 15,
            'start_page' => 1,
            'end_page' => 1,
            'status' => 'lunas',
        ]);
    }

    public function test_talaqqi_edit_view_loads_successfully(): void
    {
        $this->actingAs($this->teacher);

        $record = HafalanRecord::query()->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'teacher_id' => $this->teacher->id,
            'record_date' => now()->toDateString(),
            'start_page' => 1,
            'start_line' => 1,
            'end_page' => 1,
            'end_line' => 15,
            'total_lines' => 15,
            'status' => HafalanRecord::STATUS_LUNAS,
            'quality_score' => 95,
            'is_sequence_valid' => true,
            'created_by' => $this->teacher->id,
        ]);

        $response = $this->get(route('tahfizh.hafalan-records.edit', $record));

        $response->assertStatus(200);
        $response->assertViewHas('studentsData');
        $response->assertViewHas('classRooms');
        $response->assertViewHas('record');
    }
}
