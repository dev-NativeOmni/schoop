<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\QuranSurah;
use App\Models\School;
use App\Models\Student;
use App\Models\TahfizhTarget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::query()->where('code', 'ALAZHAR7')->firstOrFail();

        // =====================
        // 1. CREATE CLASS ROOMS
        // =====================
        $class10A = ClassRoom::query()->updateOrCreate(
            ['name' => 'Kelas X-A', 'school_id' => $school->id],
            [
                'level' => '10',
                'academic_year' => '2025/2026',
                'is_active' => true,
            ]
        );

        $class10B = ClassRoom::query()->updateOrCreate(
            ['name' => 'Kelas X-B', 'school_id' => $school->id],
            [
                'level' => '10',
                'academic_year' => '2025/2026',
                'is_active' => true,
            ]
        );

        $this->command->info("ClassRooms: X-A (ID:{$class10A->id}), X-B (ID:{$class10B->id})");

        // =====================
        // 2. LINK STUDENTS TO CLASSES & FIX NIS
        // =====================
        $santri1 = Student::query()
            ->whereHas('user', fn ($q) => $q->where('email', 'santri@hafizplus.test'))
            ->first();

        $santri2 = Student::query()
            ->whereHas('user', fn ($q) => $q->where('email', 'santri2@hafizplus.test'))
            ->first();

        $santri3 = Student::query()
            ->whereHas('user', fn ($q) => $q->where('email', 'santri3@hafizplus.test'))
            ->first();

        if ($santri1) {
            $santri1->update(['class_room_id' => $class10A->id, 'student_number' => '2025001', 'nisn' => '0011223344']);
        }
        if ($santri2) {
            $santri2->update(['class_room_id' => $class10A->id, 'student_number' => '2025002', 'nisn' => '0011223345']);
        }
        if ($santri3) {
            $santri3->update(['class_room_id' => $class10B->id, 'student_number' => '2025003', 'nisn' => '0011223346']);
        }

        $this->command->info('Students linked to classes.');

        // =====================
        // 3. CREATE TAHFIZH TARGETS
        // =====================
        $teacherUser = User::where('email', 'guru@hafizplus.test')->first();
        $teacherUser2 = User::where('email', 'guru2@hafizplus.test')->first();
        $today = Carbon::today();

        if ($santri1) {
            $target1 = TahfizhTarget::query()->updateOrCreate(
                ['student_id' => $santri1->id, 'name' => 'Target Semester 1 TA 2025/2026'],
                [
                    'school_id' => $school->id,
                    'class_room_id' => $class10A->id,
                    'program_type' => 'tahfizh',
                    'daily_target_lines' => 10,
                    'weekly_target_lines' => 50,
                    'monthly_target_lines' => 200,
                    'effective_from' => '2025-07-01',
                    'effective_until' => '2025-12-31',
                    'created_by' => $teacherUser?->id,
                    'is_active' => true,
                ]
            );
        }

        if ($santri2) {
            $target2 = TahfizhTarget::query()->updateOrCreate(
                ['student_id' => $santri2->id, 'name' => 'Target Semester 1 TA 2025/2026'],
                [
                    'school_id' => $school->id,
                    'class_room_id' => $class10A->id,
                    'program_type' => 'tahfizh',
                    'daily_target_lines' => 8,
                    'weekly_target_lines' => 40,
                    'monthly_target_lines' => 160,
                    'effective_from' => '2025-07-01',
                    'effective_until' => '2025-12-31',
                    'created_by' => $teacherUser?->id,
                    'is_active' => true,
                ]
            );
        }

        if ($santri3) {
            $target3 = TahfizhTarget::query()->updateOrCreate(
                ['student_id' => $santri3->id, 'name' => 'Target Semester 1 TA 2025/2026'],
                [
                    'school_id' => $school->id,
                    'class_room_id' => $class10B->id,
                    'program_type' => 'tahfizh',
                    'daily_target_lines' => 12,
                    'weekly_target_lines' => 60,
                    'monthly_target_lines' => 240,
                    'effective_from' => '2025-07-01',
                    'effective_until' => '2025-12-31',
                    'created_by' => $teacherUser2?->id,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('TahfizhTargets created.');

        // =====================
        // 4. CREATE HAFALAN RECORDS
        // =====================
        if (! $teacherUser || ! $santri1 || ! $santri2) {
            $this->command->warn('Skipping hafalan records - missing teacher or students');

            return;
        }

        $surahAlFatiha = QuranSurah::where('number', 1)->first();
        $surahAlBaqarah = QuranSurah::where('number', 2)->first();

        if (! $surahAlFatiha || ! $surahAlBaqarah) {
            $this->command->warn('No Quran surah data — skipping hafalan records. Run QuranSurahSeeder first.');

            return;
        }

        $target1Id = TahfizhTarget::where('student_id', $santri1->id)->value('id');
        $target2Id = TahfizhTarget::where('student_id', $santri2->id)->value('id');
        $target3Id = $santri3 ? TahfizhTarget::where('student_id', $santri3->id)->value('id') : null;

        // Santri 1 - 3 setoran sequential (pages 1 → 3 → 4)
        if (HafalanRecord::where('student_id', $santri1->id)->doesntExist()) {
            HafalanRecord::create([
                'school_id' => $school->id,
                'student_id' => $santri1->id,
                'teacher_id' => $teacherUser->id,
                'tahfizh_target_id' => $target1Id,
                'record_date' => $today->copy()->subDays(14)->toDateString(),
                'start_surah_id' => $surahAlFatiha->id,
                'start_ayah' => 1,
                'end_surah_id' => $surahAlFatiha->id,
                'end_ayah' => 7,
                'start_page' => 1,
                'start_line' => 1,
                'end_page' => 1,
                'end_line' => 15,
                'total_lines' => 15,
                'status' => HafalanRecord::STATUS_LUNAS,
                'quality_score' => 90,
                'notes' => 'Al-Fatihah 1-7 lancar dan fasih',
                'is_sequence_valid' => true,
                'sequence_note' => 'Setoran pertama, tidak ada rekaman sebelumnya.',
                'created_by' => $teacherUser->id,
            ]);

            HafalanRecord::create([
                'school_id' => $school->id,
                'student_id' => $santri1->id,
                'teacher_id' => $teacherUser->id,
                'tahfizh_target_id' => $target1Id,
                'record_date' => $today->copy()->subDays(7)->toDateString(),
                'start_surah_id' => $surahAlBaqarah->id,
                'start_ayah' => 1,
                'end_surah_id' => $surahAlBaqarah->id,
                'end_ayah' => 20,
                'start_page' => 2,
                'start_line' => 1,
                'end_page' => 3,
                'end_line' => 10,
                'total_lines' => 25,
                'status' => HafalanRecord::STATUS_LUNAS,
                'quality_score' => 85,
                'notes' => 'Al-Baqarah 1-20, lancar',
                'is_sequence_valid' => true,
                'sequence_note' => 'Melanjutkan dari halaman sebelumnya. Urutan valid.',
                'created_by' => $teacherUser->id,
            ]);

            HafalanRecord::create([
                'school_id' => $school->id,
                'student_id' => $santri1->id,
                'teacher_id' => $teacherUser->id,
                'tahfizh_target_id' => $target1Id,
                'record_date' => $today->copy()->subDays(2)->toDateString(),
                'start_surah_id' => $surahAlBaqarah->id,
                'start_ayah' => 21,
                'end_surah_id' => $surahAlBaqarah->id,
                'end_ayah' => 40,
                'start_page' => 4,
                'start_line' => 1,
                'end_page' => 5,
                'end_line' => 8,
                'total_lines' => 23,
                'status' => HafalanRecord::STATUS_KURANG,
                'quality_score' => 70,
                'notes' => 'Perlu perbaikan di ayat 35-37, ada bacaan yang salah',
                'is_sequence_valid' => true,
                'sequence_note' => 'Melanjutkan dari halaman 4. Urutan valid.',
                'created_by' => $teacherUser->id,
            ]);

            $this->command->info('Santri 1: 3 hafalan records created.');
        } else {
            $this->command->info('Santri 1: hafalan records already exist, skipping.');
        }

        // Santri 2 - 2 setoran sequential
        if (HafalanRecord::where('student_id', $santri2->id)->doesntExist()) {
            HafalanRecord::create([
                'school_id' => $school->id,
                'student_id' => $santri2->id,
                'teacher_id' => $teacherUser->id,
                'tahfizh_target_id' => $target2Id,
                'record_date' => $today->copy()->subDays(10)->toDateString(),
                'start_surah_id' => $surahAlFatiha->id,
                'start_ayah' => 1,
                'end_surah_id' => $surahAlFatiha->id,
                'end_ayah' => 7,
                'start_page' => 1,
                'start_line' => 1,
                'end_page' => 1,
                'end_line' => 15,
                'total_lines' => 15,
                'status' => HafalanRecord::STATUS_LUNAS,
                'quality_score' => 95,
                'notes' => 'Sangat baik dan fasih',
                'is_sequence_valid' => true,
                'sequence_note' => 'Setoran pertama.',
                'created_by' => $teacherUser->id,
            ]);

            HafalanRecord::create([
                'school_id' => $school->id,
                'student_id' => $santri2->id,
                'teacher_id' => $teacherUser->id,
                'tahfizh_target_id' => $target2Id,
                'record_date' => $today->copy()->subDays(3)->toDateString(),
                'start_surah_id' => $surahAlBaqarah->id,
                'start_ayah' => 1,
                'end_surah_id' => $surahAlBaqarah->id,
                'end_ayah' => 15,
                'start_page' => 2,
                'start_line' => 1,
                'end_page' => 2,
                'end_line' => 20,
                'total_lines' => 20,
                'status' => HafalanRecord::STATUS_LUNAS,
                'quality_score' => 88,
                'notes' => 'Baik sekali',
                'is_sequence_valid' => true,
                'sequence_note' => 'Melanjutkan dari halaman 2. Urutan valid.',
                'created_by' => $teacherUser->id,
            ]);

            $this->command->info('Santri 2: 2 hafalan records created.');
        } else {
            $this->command->info('Santri 2: hafalan records already exist, skipping.');
        }

        // Santri 3 - 1 setoran (guru2)
        if ($santri3 && $teacherUser2 && HafalanRecord::where('student_id', $santri3->id)->doesntExist()) {
            HafalanRecord::create([
                'school_id' => $school->id,
                'student_id' => $santri3->id,
                'teacher_id' => $teacherUser2->id,
                'tahfizh_target_id' => $target3Id,
                'record_date' => $today->copy()->subDays(5)->toDateString(),
                'start_surah_id' => $surahAlFatiha->id,
                'start_ayah' => 1,
                'end_surah_id' => $surahAlFatiha->id,
                'end_ayah' => 7,
                'start_page' => 1,
                'start_line' => 1,
                'end_page' => 1,
                'end_line' => 15,
                'total_lines' => 15,
                'status' => HafalanRecord::STATUS_LUNAS,
                'quality_score' => 80,
                'notes' => 'Cukup baik',
                'is_sequence_valid' => true,
                'sequence_note' => 'Setoran pertama.',
                'created_by' => $teacherUser2->id,
            ]);

            $this->command->info('Santri 3: 1 hafalan record created (by Guru 2).');
        } else {
            $this->command->info('Santri 3: hafalan records already exist or missing teacher, skipping.');
        }

        $this->command->info('');
        $this->command->info('=== TestDataSeeder Summary ===');
        $this->command->info('ClassRooms  : '.ClassRoom::count());
        $this->command->info('Students    : '.Student::count());
        $this->command->info('Targets     : '.TahfizhTarget::count());
        $this->command->info('HafalanRecs : '.HafalanRecord::count());
    }
}
