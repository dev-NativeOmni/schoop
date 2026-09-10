<?php

namespace Tests\Feature;

use App\Models\LmsAssignment;
use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsQuiz;
use App\Models\LmsQuizQuestion;
use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Student;
use App\Models\SubscriptionPlan;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\UserSchoolMembership;
use App\Services\Lms\LmsAccessService;
use App\Services\Lms\LmsAssignmentService;
use App\Services\Lms\LmsProgressService;
use App\Services\Lms\LmsQuizService;
use Database\Seeders\PlanModuleSeeder;
use Database\Seeders\SubscriptionPlanSeeder;
use Database\Seeders\SystemModuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Phase24LmsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;

    private School $schoolB;

    private User $adminA;

    private User $teacherA;

    private User $studentA;

    private User $parentA;

    private TeacherProfile $teacherProfileA;

    private Student $studentProfileA;

    private ParentProfile $parentProfileA;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles are seeded
        if (Role::count() === 0) {
            $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        }

        // Create Schools
        $this->schoolA = School::create(['name' => 'School A', 'code' => 'SCHA', 'is_active' => true]);
        $this->schoolB = School::create(['name' => 'School B', 'code' => 'SCHB', 'is_active' => true]);

        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();
        $parentRole = Role::where('name', 'parent')->first();

        // Create Users
        $this->adminA = User::create([
            'school_id' => $this->schoolA->id,
            'role_id' => $adminRole->id,
            'name' => 'Admin A',
            'username' => 'admin_a_p24',
            'email' => 'admina_p24@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->teacherA = User::create([
            'school_id' => $this->schoolA->id,
            'role_id' => $teacherRole->id,
            'name' => 'Teacher A',
            'username' => 'teacher_a_p24',
            'email' => 'teachera_p24@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->studentA = User::create([
            'school_id' => $this->schoolA->id,
            'role_id' => $studentRole->id,
            'name' => 'Student A',
            'username' => 'student_a_p24',
            'email' => 'studenta_p24@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->parentA = User::create([
            'school_id' => $this->schoolA->id,
            'role_id' => $parentRole->id,
            'name' => 'Parent A',
            'username' => 'parent_a_p24',
            'email' => 'parenta_p24@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Profiles
        $this->teacherProfileA = TeacherProfile::create([
            'user_id' => $this->teacherA->id,
            'school_id' => $this->schoolA->id,
            'employee_number' => 'TEA001',
            'is_active' => true,
        ]);

        $this->studentProfileA = Student::create([
            'user_id' => $this->studentA->id,
            'school_id' => $this->schoolA->id,
            'student_number' => 'STU001',
            'full_name' => 'Student A Name',
            'is_active' => true,
        ]);

        $this->parentProfileA = ParentProfile::create([
            'user_id' => $this->parentA->id,
            'school_id' => $this->schoolA->id,
            'full_name' => 'Parent A Name',
            'is_active' => true,
        ]);

        // Link parent student
        $this->parentProfileA->students()->attach($this->studentProfileA->id);

        // Memberships
        UserSchoolMembership::create(['user_id' => $this->adminA->id, 'school_id' => $this->schoolA->id, 'role_id' => $adminRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->teacherA->id, 'school_id' => $this->schoolA->id, 'role_id' => $teacherRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->studentA->id, 'school_id' => $this->schoolA->id, 'role_id' => $studentRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->parentA->id, 'school_id' => $this->schoolA->id, 'role_id' => $parentRole->id, 'membership_status' => 'active']);

        // Seed and create active school subscription for schoolA to enable 'lms' module
        $this->seed(SystemModuleSeeder::class);
        $this->seed(SubscriptionPlanSeeder::class);
        $this->seed(PlanModuleSeeder::class);

        $enterprisePlan = SubscriptionPlan::where('code', 'enterprise')->firstOrFail();
        SchoolSubscription::create([
            'school_id' => $this->schoolA->id,
            'subscription_plan_id' => $enterprisePlan->id,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'current_period_starts_at' => now()->subDay(),
            'current_period_ends_at' => now()->addMonth(),
        ]);
    }

    public function test_course_creation_by_admin_is_scoped_to_tenant(): void
    {
        $this->actingAs($this->adminA)
            ->withSession(['active_school_id' => $this->schoolA->id]);

        $courseData = [
            'title' => 'Test Course A',
            'course_code' => 'TEST-C-A',
            'type' => 'general',
            'visibility' => 'draft',
            'enrollment_mode' => 'manual',
            'instructor_ids' => [$this->teacherProfileA->id],
        ];

        $response = $this->post(route('lms.courses.store'), $courseData);
        $response->assertRedirect();

        $this->assertDatabaseHas('lms_courses', [
            'school_id' => $this->schoolA->id,
            'title' => 'Test Course A',
        ]);
    }

    public function test_draft_course_is_hidden_from_students(): void
    {
        // 1. Create a course under school A
        session(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        $course = LmsCourse::create([
            'school_id' => $this->schoolA->id,
            'title' => 'Draft Course',
            'slug' => 'draft-course',
            'course_code' => 'DRAFT-C',
            'visibility' => 'draft',
        ]);

        // Enroll student
        LmsCourseEnrollment::create([
            'school_id' => $this->schoolA->id,
            'course_id' => $course->id,
            'student_id' => $this->studentProfileA->id,
            'status' => 'active',
        ]);

        $accessService = app(LmsAccessService::class);
        $this->assertFalse($accessService->canViewCourse($this->studentA, $course));
    }

    public function test_student_progress_recalculation_is_accurate(): void
    {
        session(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        // Create course
        $course = LmsCourse::create([
            'school_id' => $this->schoolA->id,
            'title' => 'Progress Course',
            'slug' => 'progress-course',
            'course_code' => 'PROG-C',
            'visibility' => 'published',
        ]);

        // Modules
        $module = LmsCourseModule::create([
            'school_id' => $this->schoolA->id,
            'course_id' => $course->id,
            'title' => 'Modul 1',
        ]);

        // Lessons: 2 required, 1 optional
        $lesson1 = LmsLesson::create([
            'school_id' => $this->schoolA->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Lesson 1',
            'slug' => 'lesson-1',
            'lesson_type' => 'text',
            'is_required' => true,
            'visibility' => 'published',
            'sort_order' => 1,
        ]);

        $lesson2 = LmsLesson::create([
            'school_id' => $this->schoolA->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Lesson 2',
            'slug' => 'lesson-2',
            'lesson_type' => 'text',
            'is_required' => true,
            'visibility' => 'published',
            'sort_order' => 2,
        ]);

        $lesson3 = LmsLesson::create([
            'school_id' => $this->schoolA->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Lesson 3',
            'slug' => 'lesson-3',
            'lesson_type' => 'text',
            'is_required' => false,
            'visibility' => 'published',
            'sort_order' => 3,
        ]);

        // Enroll student
        LmsCourseEnrollment::create([
            'school_id' => $this->schoolA->id,
            'course_id' => $course->id,
            'student_id' => $this->studentProfileA->id,
            'status' => 'active',
            'progress_percentage' => 0.00,
        ]);

        $progressService = app(LmsProgressService::class);

        // Mark 1 required lesson complete
        $progressService->markAsComplete($this->studentProfileA->id, $lesson1->id);

        $enrollment = LmsCourseEnrollment::where('course_id', $course->id)
            ->where('student_id', $this->studentProfileA->id)
            ->first();

        $this->assertEquals(50.00, (float) $enrollment->progress_percentage);
        $this->assertEquals('active', $enrollment->status);

        // Mark second required lesson complete
        $progressService->markAsComplete($this->studentProfileA->id, $lesson2->id);

        $enrollment->refresh();
        $this->assertEquals(100.00, (float) $enrollment->progress_percentage);
        $this->assertEquals('completed', $enrollment->status);
    }

    public function test_quiz_grading_operates_server_side_and_does_not_leak_keys(): void
    {
        session(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        // Create quiz
        $course = LmsCourse::create(['school_id' => $this->schoolA->id, 'title' => 'Quiz Course', 'slug' => 'q-c', 'course_code' => 'QC', 'visibility' => 'published']);
        $module = LmsCourseModule::create(['school_id' => $this->schoolA->id, 'course_id' => $course->id, 'title' => 'M1']);
        $lesson = LmsLesson::create(['school_id' => $this->schoolA->id, 'course_id' => $course->id, 'module_id' => $module->id, 'title' => 'Q L', 'slug' => 'ql', 'lesson_type' => 'quiz', 'visibility' => 'published']);
        $quiz = LmsQuiz::create(['school_id' => $this->schoolA->id, 'course_id' => $course->id, 'lesson_id' => $lesson->id, 'title' => 'Q1', 'passing_score' => 70]);

        $q1 = LmsQuizQuestion::create([
            'school_id' => $this->schoolA->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'Question 1',
            'question_type' => 'multiple_choice',
            'options' => ['A' => 'Opt A', 'B' => 'Opt B'],
            'correct_answer' => 'A',
            'score_weight' => 10,
        ]);

        $q2 = LmsQuizQuestion::create([
            'school_id' => $this->schoolA->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'Question 2',
            'question_type' => 'short_answer',
            'correct_answer' => 'correct answer text',
            'score_weight' => 10,
        ]);

        // Verify that correct_answer is hidden from JSON serialization
        $serialized = $q1->toArray();
        $this->assertArrayNotHasKey('correct_answer', $serialized);

        // Attempt quiz
        $quizService = app(LmsQuizService::class);
        $attempt = $quizService->startAttempt($quiz->id, $this->studentProfileA->id);

        // Submit answers: Q1 correct (A), Q2 incorrect (wrong text)
        $submittedAnswers = [
            $q1->id => 'A',
            $q2->id => 'wrong text',
        ];

        $attempt = $quizService->submitAttempt($attempt->id, $submittedAnswers);

        // Weight = 10 correct out of 20 total = 50%
        $this->assertEquals(50.00, (float) $attempt->score);
        $this->assertFalse($attempt->is_passed);
        $this->assertEquals('completed', $attempt->status);
    }

    public function test_dangerous_files_are_blocked_from_uploads(): void
    {
        Storage::fake('local');
        session(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        $course = LmsCourse::create(['school_id' => $this->schoolA->id, 'title' => 'C', 'slug' => 'c', 'course_code' => 'C', 'visibility' => 'published']);
        $module = LmsCourseModule::create(['school_id' => $this->schoolA->id, 'course_id' => $course->id, 'title' => 'M']);
        $lesson = LmsLesson::create(['school_id' => $this->schoolA->id, 'course_id' => $course->id, 'module_id' => $module->id, 'title' => 'L', 'slug' => 'l', 'lesson_type' => 'assignment', 'visibility' => 'published']);
        $assignment = LmsAssignment::create(['school_id' => $this->schoolA->id, 'course_id' => $course->id, 'lesson_id' => $lesson->id, 'title' => 'A']);

        // Submit dangerous file
        $dangerousFile = UploadedFile::fake()->create('malicious.php', 10); // php is blocked

        $this->expectException(\InvalidArgumentException::class);
        app(LmsAssignmentService::class)->submitAssignment($assignment->id, $this->studentProfileA->id, [], $dangerousFile);
    }

    public function test_parents_can_only_view_their_own_childrens_progress(): void
    {
        session(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        $accessService = app(LmsAccessService::class);

        // studentProfileA is parentA's child
        $this->assertTrue($accessService->canViewChildProgress($this->parentA, $this->studentProfileA));

        // Create another student
        $studentB = Student::create([
            'school_id' => $this->schoolA->id,
            'student_number' => 'STUB002',
            'full_name' => 'Student B Name',
            'is_active' => true,
        ]);

        // studentB is NOT parentA's child
        $this->assertFalse($accessService->canViewChildProgress($this->parentA, $studentB));
    }

    public function test_lms_console_commands_run_successfully(): void
    {
        $this->artisan('app:lms-recalculate-progress')->assertExitCode(0);
        $this->artisan('app:lms-generate-analytics-snapshot')->assertExitCode(0);
        $this->artisan('app:lms-prune-activity-logs')->assertExitCode(0);
    }
}
