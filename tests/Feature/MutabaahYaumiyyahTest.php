<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\MutabaahActivity;
use App\Models\MutabaahCategory;
use App\Models\MutabaahRecord;
use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Student;
use App\Models\SubscriptionPlan;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\UserSchoolMembership;
use App\Services\Billing\ModuleAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MutabaahYaumiyyahTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $teacherUser;
    private User $parentUser;
    private User $studentUser;
    private School $school;
    private Student $student;
    private Student $student2;
    private ClassRoom $classRoom;
    private MutabaahCategory $catWajib;
    private MutabaahCategory $catSunnah;
    private MutabaahActivity $actSubuh;
    private MutabaahActivity $actDhuha;
    private MutabaahActivity $actTilawah;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\SystemModuleSeeder::class);
        $this->seed(\Database\Seeders\SubscriptionPlanSeeder::class);
        $this->seed(\Database\Seeders\PlanModuleSeeder::class);

        $adminRole = Role::query()->where('name', 'admin')->firstOrFail();
        $teacherRole = Role::query()->where('name', 'teacher')->firstOrFail();
        $parentRole = Role::query()->where('name', 'parent')->firstOrFail();
        $studentRole = Role::query()->where('name', 'student')->firstOrFail();

        $this->school = School::query()->create([
            'name' => 'Pesantren Mutabaah Test',
            'code' => 'PMT01',
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

        // Users
        $this->admin = User::query()->create([
            'role_id' => $adminRole->id,
            'school_id' => $this->school->id,
            'name' => 'Admin Mutabaah',
            'username' => 'admin_mutabaah',
            'email' => 'admin-mutabaah@hafizplus.test',
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

        $this->teacherUser = User::query()->create([
            'role_id' => $teacherRole->id,
            'school_id' => $this->school->id,
            'name' => 'Ustadz Pembina',
            'username' => 'teacher_mutabaah',
            'email' => 'teacher-mutabaah@hafizplus.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        UserSchoolMembership::query()->create([
            'user_id' => $this->teacherUser->id,
            'school_id' => $this->school->id,
            'role_id' => $teacherRole->id,
            'membership_status' => 'active',
            'is_default' => true,
        ]);
        TeacherProfile::query()->create([
            'user_id' => $this->teacherUser->id,
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);

        $this->parentUser = User::query()->create([
            'role_id' => $parentRole->id,
            'school_id' => $this->school->id,
            'name' => 'Wali Santri',
            'username' => 'parent_mutabaah',
            'email' => 'parent-mutabaah@hafizplus.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        UserSchoolMembership::query()->create([
            'user_id' => $this->parentUser->id,
            'school_id' => $this->school->id,
            'role_id' => $parentRole->id,
            'membership_status' => 'active',
            'is_default' => true,
        ]);
        $parentProfile = ParentProfile::query()->create([
            'user_id' => $this->parentUser->id,
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);

        $this->studentUser = User::query()->create([
            'role_id' => $studentRole->id,
            'school_id' => $this->school->id,
            'name' => 'Ahmad Santri',
            'username' => 'student_mutabaah',
            'email' => 'student-mutabaah@hafizplus.test',
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

        $this->classRoom = ClassRoom::query()->create([
            'school_id' => $this->school->id,
            'name' => 'Kelas 10 Tahfizh',
            'grade' => '10',
            'is_active' => true,
        ]);

        $this->student = Student::query()->create([
            'school_id' => $this->school->id,
            'user_id' => $this->studentUser->id,
            'class_room_id' => $this->classRoom->id,
            'student_number' => 'NIS-MUT-001',
            'full_name' => 'Ahmad Santri',
            'gender' => 'male',
            'status' => 'active',
            'is_active' => true,
        ]);

        $this->student2 = Student::query()->create([
            'school_id' => $this->school->id,
            'class_room_id' => $this->classRoom->id,
            'student_number' => 'NIS-MUT-002',
            'full_name' => 'Fathimah Santriwati',
            'gender' => 'female',
            'status' => 'active',
            'is_active' => true,
        ]);

        // Attach students to parent profile
        $parentProfile->students()->attach([
            $this->student->id => ['relationship' => 'father', 'is_primary' => true],
            $this->student2->id => ['relationship' => 'father', 'is_primary' => false],
        ]);

        // Mutabaah Categories
        $this->catWajib = MutabaahCategory::query()->create([
            'school_id' => $this->school->id,
            'name' => 'Ibadah Wajib',
            'slug' => 'ibadah-wajib',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->catSunnah = MutabaahCategory::query()->create([
            'school_id' => $this->school->id,
            'name' => 'Ibadah Sunnah',
            'slug' => 'ibadah-sunnah',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Mutabaah Activities
        $this->actSubuh = MutabaahActivity::query()->create([
            'school_id' => $this->school->id,
            'mutabaah_category_id' => $this->catWajib->id,
            'name' => 'Shalat Subuh Berjamaah',
            'input_type' => 'checklist',
            'is_required' => true,
            'is_active' => true,
            'allow_teacher_input' => true,
            'allow_student_input' => true,
            'sort_order' => 1,
        ]);

        $this->actDhuha = MutabaahActivity::query()->create([
            'school_id' => $this->school->id,
            'mutabaah_category_id' => $this->catSunnah->id,
            'name' => 'Shalat Dhuha',
            'input_type' => 'checklist',
            'is_required' => false,
            'is_active' => true,
            'allow_teacher_input' => true,
            'allow_student_input' => true,
            'sort_order' => 2,
        ]);

        $this->actTilawah = MutabaahActivity::query()->create([
            'school_id' => $this->school->id,
            'mutabaah_category_id' => $this->catSunnah->id,
            'name' => 'Tilawah Mandiri',
            'input_type' => 'count',
            'target_count' => 1,
            'target_unit' => 'Halaman',
            'is_required' => false,
            'is_active' => true,
            'allow_teacher_input' => true,
            'allow_student_input' => true,
            'sort_order' => 3,
        ]);

        app(ModuleAccessService::class)->flushCache();
    }

    public function test_teacher_can_view_mutabaah_daily_index(): void
    {
        $response = $this->actingAs($this->teacherUser)->get(route('mutabaah.daily.index'));

        $response->assertOk();
        $response->assertViewIs('mutabaah.daily.index');
        $response->assertSee('Input Mutabaah');
        $response->assertSee('Ahmad Santri');
        $response->assertSee('Shalat Subuh Berjamaah');
    }

    public function test_teacher_can_save_daily_mutabaah_records_in_bulk(): void
    {
        $date = today()->toDateString();

        $response = $this->actingAs($this->teacherUser)->post(route('mutabaah.daily.store'), [
            'student_id' => $this->student->id,
            'record_date' => $date,
            'records' => [
                [
                    'mutabaah_activity_id' => $this->actSubuh->id,
                    'status' => 'done',
                    'note' => 'Hadir tepat waktu di masjid',
                ],
                [
                    'mutabaah_activity_id' => $this->actDhuha->id,
                    'status' => 'done',
                    'note' => '4 rakaat',
                ],
                [
                    'mutabaah_activity_id' => $this->actTilawah->id,
                    'status' => 'done',
                    'count_value' => 2,
                    'note' => 'Juz 1 hlm 1-2',
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('mutabaah_records', [
            'student_id' => $this->student->id,
            'mutabaah_activity_id' => $this->actSubuh->id,
            'status' => 'done',
            'source' => 'teacher',
        ]);

        $this->assertDatabaseHas('mutabaah_records', [
            'student_id' => $this->student->id,
            'mutabaah_activity_id' => $this->actTilawah->id,
            'count_value' => 2,
            'status' => 'done',
        ]);
    }

    public function test_student_can_view_own_mutabaah_portal_and_see_streak(): void
    {
        // Seed past records for streak
        MutabaahRecord::query()->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'mutabaah_activity_id' => $this->actSubuh->id,
            'record_date' => today()->subDay()->toDateString(),
            'status' => 'done',
            'source' => 'student',
        ]);

        $response = $this->actingAs($this->studentUser)->get(route('portal.student.mutabaah'));

        $response->assertOk();
        $response->assertViewIs('portal.student.mutabaah');
        $response->assertSee('Mutabaah Yaumiyyah');
        $response->assertSee('Ahmad Santri');
        $response->assertSee('Checklist Ibadah Hari Ini');
        $response->assertSee('Shalat Subuh Berjamaah');
    }

    public function test_student_can_submit_own_daily_mutabaah(): void
    {
        $date = today()->toDateString();

        $response = $this->actingAs($this->studentUser)->post(route('portal.student.mutabaah.store'), [
            'record_date' => $date,
            'records' => [
                [
                    'mutabaah_activity_id' => $this->actSubuh->id,
                    'status' => 'done',
                ],
                [
                    'mutabaah_activity_id' => $this->actDhuha->id,
                    'status' => 'not_done',
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('mutabaah_records', [
            'student_id' => $this->student->id,
            'mutabaah_activity_id' => $this->actSubuh->id,
            'status' => 'done',
            'source' => 'student',
        ]);
    }

    public function test_parent_can_view_child_mutabaah_portal_and_switch_children(): void
    {
        // Add mutabaah records for child 1
        MutabaahRecord::query()->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'mutabaah_activity_id' => $this->actSubuh->id,
            'record_date' => today()->toDateString(),
            'status' => 'done',
            'source' => 'teacher',
        ]);

        // Default child visit
        $response = $this->actingAs($this->parentUser)->get(route('portal.parent.mutabaah'));
        $response->assertOk();
        $response->assertViewIs('portal.parent.mutabaah');
        $response->assertSee('Mutabaah Yaumiyyah Ananda');
        $response->assertSee('Ahmad Santri');

        // Specific child 2 visit
        $response2 = $this->actingAs($this->parentUser)->get(route('portal.parent.mutabaah', $this->student2->id));
        $response2->assertOk();
        $response2->assertSee('Fathimah Santriwati');
    }

    public function test_admin_can_view_mutabaah_reports_dashboard(): void
    {
        // Add sample record
        MutabaahRecord::query()->create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'mutabaah_activity_id' => $this->actSubuh->id,
            'record_date' => today()->toDateString(),
            'status' => 'done',
            'source' => 'teacher',
        ]);

        $response = $this->actingAs($this->admin)->get(route('mutabaah.reports.dashboard'));

        $response->assertOk();
        $response->assertViewIs('mutabaah.reports.dashboard');
        $response->assertSee('Laporan Mutabaah Santri');
        $response->assertSee('Rekap Capaian per Santri');
        $response->assertSee('Rekap Capaian per Aktivitas');
    }
}
