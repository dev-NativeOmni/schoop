<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use App\Models\Role;
use App\Models\TeacherProfile;
use App\Models\Student;
use App\Models\LmsCourse;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsLessonResource;
use App\Models\LmsCourseInstructor;
use App\Models\LmsCourseEnrollment;
use App\Models\LmsAssignment;
use App\Models\LmsQuiz;
use App\Models\LmsQuizQuestion;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LmsSampleCourseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get or create school
        $school = School::first();
        if (!$school) {
            $school = School::create([
                'name' => 'Pesantren HafizPlus',
                'unique_code' => 'HP001',
                'is_active' => true,
            ]);
        }

        // Set active tenant context so creating models automatically sets school_id
        app()->instance('resolved_domain_school_id', $school->id);
        session(['active_school_id' => $school->id]);

        // Get roles
        $teacherRole = Role::where('name', 'teacher')->first() ?? Role::create(['name' => 'teacher', 'display_name' => 'Guru']);
        $studentRole = Role::where('name', 'student')->first() ?? Role::create(['name' => 'student', 'display_name' => 'Santri']);

        // 2. Get or create teacher user and profile
        $teacherProfile = TeacherProfile::where('school_id', $school->id)->first();
        if (!$teacherProfile) {
            $teacherUser = User::create([
                'school_id' => $school->id,
                'role_id' => $teacherRole->id,
                'name' => 'Ustadz Ahmad',
                'username' => 'ustadzahmad',
                'email' => 'ahmad@hafizplus.test',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

            $teacherProfile = TeacherProfile::create([
                'user_id' => $teacherUser->id,
                'school_id' => $school->id,
                'employee_number' => 'EMP' . rand(1000, 9999),
                'specialization' => 'Bahasa Arab',
                'is_active' => true,
            ]);
        }

        // 3. Get or create student user and profile
        $student = Student::where('school_id', $school->id)->first();
        if (!$student) {
            $studentUser = User::create([
                'school_id' => $school->id,
                'role_id' => $studentRole->id,
                'name' => 'Ali bin Abi Thalib',
                'username' => 'alibinali',
                'email' => 'ali@hafizplus.test',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

            $student = Student::create([
                'user_id' => $studentUser->id,
                'school_id' => $school->id,
                'student_number' => 'STUD' . rand(1000, 9999),
                'full_name' => 'Ali bin Abi Thalib',
                'is_active' => true,
            ]);
        }

        // 4. Create Course
        $courseTitle = 'Bahasa Arab Dasar (Nahwu & Sharaf)';
        $courseSlug = Str::slug($courseTitle);
        
        // Ensure slug unique
        $existing = LmsCourse::where('school_id', $school->id)->where('slug', $courseSlug)->first();
        if ($existing) {
            $existing->delete();
        }

        $course = LmsCourse::create([
            'school_id' => $school->id,
            'created_by' => $teacherProfile->user_id,
            'updated_by' => $teacherProfile->user_id,
            'title' => $courseTitle,
            'slug' => $courseSlug,
            'course_code' => 'LMS-ARA-01',
            'type' => 'language',
            'description' => 'Materi pembelajaran Bahasa Arab tingkat dasar untuk memahami kaidah Nahwu dan Sharaf.',
            'level' => 'Dasar',
            'visibility' => 'published',
            'enrollment_mode' => 'manual',
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(90)->toDateString(),
            'is_required' => true,
            'sort_order' => 1,
        ]);

        // Assign Instructor
        LmsCourseInstructor::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'teacher_profile_id' => $teacherProfile->id,
            'is_primary' => true,
        ]);

        // 5. Create Module
        $module = LmsCourseModule::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'title' => 'Bab 1: Kalimah (Kata) dalam Bahasa Arab',
            'description' => 'Mempelajari pembagian kata menjadi Isim, Fi\'il, dan Harf.',
            'sort_order' => 1,
            'is_required' => true,
        ]);

        // 6. Create Lessons
        
        // Lesson 1: Text
        $lessonText = LmsLesson::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'created_by' => $teacherProfile->user_id,
            'title' => 'Mengenal Isim, Fi\'il, dan Harf',
            'slug' => Str::slug('Mengenal Isim Fi\'il dan Harf'),
            'lesson_type' => 'text',
            'content' => 'Kalimah (kata) dalam bahasa Arab dibagi menjadi 3 bagian: 1) Isim (Kata benda), 2) Fi\'il (Kata kerja), dan 3) Harf (Kata depan/sambung). Isim ditandai dengan adanya Tanwin atau Alif Lam (Al-). Fi\'il terikat oleh waktu (lampau, sekarang, atau perintah). Harf tidak memiliki tanda khusus melainkan dipahami ketika bersambung dengan kata lainnya.',
            'estimated_minutes' => 15,
            'is_required' => true,
            'visibility' => 'published',
            'sort_order' => 1,
        ]);

        // Lesson Resource
        LmsLessonResource::create([
            'school_id' => $school->id,
            'lesson_id' => $lessonText->id,
            'uploaded_by' => $teacherProfile->user_id,
            'title' => 'Tabel Pembagian Kalimah (PDF)',
            'resource_type' => 'link',
            'external_url' => 'https://example.com/arabic-tabel-kalimah.pdf',
            'sort_order' => 1,
        ]);

        // Lesson 2: Quiz
        $lessonQuiz = LmsLesson::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'created_by' => $teacherProfile->user_id,
            'title' => 'Kuis Pendalaman Bab 1',
            'slug' => Str::slug('Kuis Pendalaman Bab 1'),
            'lesson_type' => 'quiz',
            'estimated_minutes' => 10,
            'is_required' => true,
            'visibility' => 'published',
            'sort_order' => 2,
        ]);

        $quiz = LmsQuiz::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'lesson_id' => $lessonQuiz->id,
            'title' => 'Kuis Pendalaman Bab 1',
            'description' => 'Uji pemahaman Anda tentang Isim, Fi\'il, dan Harf.',
            'time_limit_minutes' => 15,
            'max_attempts' => 3,
            'passing_score' => 75.00,
            'is_randomized' => false,
        ]);

        // Questions
        LmsQuizQuestion::create([
            'school_id' => $school->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'Manakah di bawah ini yang merupakan ciri khas dari Kalimah Isim?',
            'question_type' => 'multiple_choice',
            'options' => [
                'A' => 'Kemasukan Alif Lam (Al-)',
                'B' => 'Terikat dengan waktu lampau',
                'C' => 'Tidak memiliki arti sendiri',
                'D' => 'Diakhiri sukun'
            ],
            'correct_answer' => 'A',
            'score_weight' => 10,
            'sort_order' => 1,
        ]);

        LmsQuizQuestion::create([
            'school_id' => $school->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'Fi\'il adalah kata kerja yang terikat dengan waktu.',
            'question_type' => 'true_false',
            'options' => [
                'true' => 'Benar',
                'false' => 'Salah'
            ],
            'correct_answer' => 'true',
            'score_weight' => 10,
            'sort_order' => 2,
        ]);

        LmsQuizQuestion::create([
            'school_id' => $school->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'Sebutkan pembagian kalimah yang ketiga setelah isim dan fi\'il!',
            'question_type' => 'short_answer',
            'correct_answer' => 'harf',
            'score_weight' => 10,
            'sort_order' => 3,
        ]);

        // Lesson 3: Assignment
        $lessonAssign = LmsLesson::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'created_by' => $teacherProfile->user_id,
            'title' => 'Tugas Praktik Analisis Kalimah',
            'slug' => Str::slug('Tugas Praktik Analisis Kalimah'),
            'lesson_type' => 'assignment',
            'estimated_minutes' => 30,
            'is_required' => true,
            'visibility' => 'published',
            'sort_order' => 3,
        ]);

        LmsAssignment::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'lesson_id' => $lessonAssign->id,
            'title' => 'Analisis Kalimah Surah Al-Fatihah',
            'instructions' => 'Tuliskan dan kelompokkan minimal 5 isim, 3 fi\'il, dan 2 harf yang Anda temukan di dalam Surah Al-Fatihah. Tulis dalam bentuk teks atau unggah file PDF hasil tulisan tangan Anda.',
            'max_score' => 100,
            'passing_score' => 70,
            'due_date' => now()->addDays(7)->toDateTimeString(),
            'allowed_file_types' => 'pdf,doc,docx,jpg,png',
            'max_file_size_kb' => 10240,
        ]);

        // 7. Enroll student
        LmsCourseEnrollment::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'student_id' => $student->id,
            'progress_percentage' => 0.00,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        // Clear active tenant context
        app()->offsetUnset('resolved_domain_school_id');
        session()->forget('active_school_id');
    }
}
