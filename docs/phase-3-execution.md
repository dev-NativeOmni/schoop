# Phase 3 Execution Guide — Tahfizh Core Database Foundation

## 0. Identitas Phase

Dokumen ini adalah instruksi eksekusi untuk AI coding agent di code editor.

Project:

```text
HafizPlus School Platform
```

Produk pertama:

```text
Tahfizh Monitoring App
```

Framework:

```text
Laravel 12
```

Database:

```text
MySQL
```

Project folder:

```text
C:\xampp\htdocs\hafizplus-school-platform
```

Database local:

```text
hafizplus_school_platform
```

Status:

```text
Proyek mandiri, bukan bagian dari HafizPlus 2.0 atau HafizPlus 3.0.
```

---

# 1. Tujuan Phase 3

Phase 3 bertujuan membangun fondasi database dan domain logic awal untuk fitur tahfizh.

Phase ini membuat struktur inti untuk:

1. Data juz Al-Qur’an.
2. Data surah Al-Qur’an.
3. Data mushaf pojok 604 halaman.
4. Aturan dasar 1 halaman = 15 baris.
5. Target tahfizh.
6. Catatan setoran hafalan.
7. Service hitung total baris setoran.
8. Validasi dasar range halaman dan baris.
9. Seeder data Qur’an awal.
10. Dokumentasi Phase 3.

---

# 2. Batasan Phase 3

AI agent tidak boleh membuat fitur berikut pada Phase 3:

1. UI quick input setoran.
2. Bulk input setoran.
3. Dashboard statistik tahfizh.
4. Report bulanan.
5. Report triwulan.
6. Parent progress detail.
7. Student progress detail.
8. Notification real.
9. Export PDF.
10. Export Excel.
11. Grafik progres.
12. API mobile.

Phase 3 hanya membuat:

1. Migration.
2. Model.
3. Seeder.
4. Service dasar.
5. Dokumentasi.
6. Validasi teknis via migrate/seed/tinker.

---

# 3. Target Output Phase 3

Setelah Phase 3 selesai, project harus memiliki tabel:

1. `quran_juzs`
2. `quran_surahs`
3. `mushaf_pages`
4. `tahfizh_targets`
5. `hafalan_records`

Project juga harus memiliki model:

1. `QuranJuz`
2. `QuranSurah`
3. `MushafPage`
4. `TahfizhTarget`
5. `HafalanRecord`

Project juga harus memiliki seeder:

1. `QuranJuzSeeder`
2. `QuranSurahSeeder`
3. `MushafPageSeeder`

Project juga harus memiliki service:

1. `App\Services\Tahfizh\LineRangeCalculator`

---

# 4. Validasi Awal

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform
php artisan --version
php artisan migrate:status
php artisan route:list
git status
```

Target:

1. Laravel 12 berjalan.
2. Phase 1 sudah selesai.
3. Phase 2 sudah selesai.
4. Tabel `roles`, `schools`, `class_rooms`, `teacher_profiles`, `parent_profiles`, `students`, dan `parent_student` sudah ada.
5. Working tree bersih atau semua perubahan sudah diketahui.

Jika Phase 2 belum selesai, hentikan eksekusi.

---

# 5. Buat Branch Git Phase 3

Jalankan:

```powershell
git checkout -b phase-3-tahfizh-core-database
```

Jika branch sudah ada:

```powershell
git checkout phase-3-tahfizh-core-database
```

---

# 6. Buat Model dan Migration

Jalankan:

```powershell
php artisan make:model QuranJuz -m
php artisan make:model QuranSurah -m
php artisan make:model MushafPage -m
php artisan make:model TahfizhTarget -m
php artisan make:model HafalanRecord -m
```

---

# 7. Buat Seeder

Jalankan:

```powershell
php artisan make:seeder QuranJuzSeeder
php artisan make:seeder QuranSurahSeeder
php artisan make:seeder MushafPageSeeder
```

---

# 8. Buat Folder Service Tahfizh

Jalankan:

```powershell
mkdir app\Services
mkdir app\Services\Tahfizh
```

Jika folder sudah ada, lanjut saja.

Buat file:

```text
app/Services/Tahfizh/LineRangeCalculator.php
```

---

# 9. Migration `create_quran_juzs_table`

Cari migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_quran_juzs_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quran_juzs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('number')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_juzs');
    }
};
```

---

# 10. Migration `create_quran_surahs_table`

Cari migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_quran_surahs_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quran_surahs', function (Blueprint $table): void {
            $table->id();

            $table->unsignedTinyInteger('number')->unique();
            $table->string('name_latin');
            $table->string('name_arabic')->nullable();
            $table->string('meaning')->nullable();
            $table->unsignedSmallInteger('total_ayah');
            $table->string('revelation_place')->nullable();

            $table->foreignId('start_juz_id')
                ->nullable()
                ->constrained('quran_juzs')
                ->nullOnDelete();

            $table->foreignId('end_juz_id')
                ->nullable()
                ->constrained('quran_juzs')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('number');
            $table->index('name_latin');
            $table->index('start_juz_id');
            $table->index('end_juz_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_surahs');
    }
};
```

---

# 11. Migration `create_mushaf_pages_table`

Cari migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_mushaf_pages_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mushaf_pages', function (Blueprint $table): void {
            $table->id();

            $table->unsignedSmallInteger('page_number')->unique();
            $table->unsignedTinyInteger('total_lines')->default(15);

            $table->foreignId('start_surah_id')
                ->nullable()
                ->constrained('quran_surahs')
                ->nullOnDelete();

            $table->unsignedSmallInteger('start_ayah')->nullable();

            $table->foreignId('end_surah_id')
                ->nullable()
                ->constrained('quran_surahs')
                ->nullOnDelete();

            $table->unsignedSmallInteger('end_ayah')->nullable();

            $table->foreignId('juz_id')
                ->nullable()
                ->constrained('quran_juzs')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('page_number');
            $table->index('juz_id');
            $table->index('start_surah_id');
            $table->index('end_surah_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mushaf_pages');
    }
};
```

Catatan:

Untuk Phase 3, `mushaf_pages` minimal menyimpan 604 halaman dan 15 baris per halaman. Mapping surah/ayah per halaman boleh dilengkapi pada fase data enrichment berikutnya.

---

# 12. Migration `create_tahfizh_targets_table`

Cari migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tahfizh_targets_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tahfizh_targets', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('class_room_id')
                ->nullable()
                ->constrained('class_rooms')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->nullOnDelete();

            $table->string('name');
            $table->string('program_type')->nullable();

            $table->unsignedSmallInteger('daily_target_lines')->default(0);
            $table->unsignedSmallInteger('weekly_target_lines')->default(0);
            $table->unsignedSmallInteger('monthly_target_lines')->default(0);

            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('school_id');
            $table->index('class_room_id');
            $table->index('student_id');
            $table->index('program_type');
            $table->index('is_active');
            $table->index(['effective_from', 'effective_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahfizh_targets');
    }
};
```

---

# 13. Migration `create_hafalan_records_table`

Cari migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_hafalan_records_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hafalan_records', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('tahfizh_target_id')
                ->nullable()
                ->constrained('tahfizh_targets')
                ->nullOnDelete();

            $table->date('record_date');

            $table->foreignId('start_surah_id')
                ->nullable()
                ->constrained('quran_surahs')
                ->nullOnDelete();

            $table->unsignedSmallInteger('start_ayah')->nullable();

            $table->foreignId('end_surah_id')
                ->nullable()
                ->constrained('quran_surahs')
                ->nullOnDelete();

            $table->unsignedSmallInteger('end_ayah')->nullable();

            $table->unsignedSmallInteger('start_page');
            $table->unsignedTinyInteger('start_line');
            $table->unsignedSmallInteger('end_page');
            $table->unsignedTinyInteger('end_line');

            $table->unsignedSmallInteger('total_lines')->default(0);

            $table->string('status')->default('kurang');
            $table->unsignedTinyInteger('quality_score')->nullable();
            $table->text('notes')->nullable();

            $table->boolean('is_sequence_valid')->default(false);
            $table->text('sequence_note')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('school_id');
            $table->index('student_id');
            $table->index('teacher_id');
            $table->index('record_date');
            $table->index('status');
            $table->index(['student_id', 'record_date']);
            $table->index(['start_page', 'start_line']);
            $table->index(['end_page', 'end_line']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hafalan_records');
    }
};
```

Status awal yang dipakai:

```text
lunas
kurang
lebih
tidak_hadir
izin
sakit
```

Jangan pakai database enum agar status lebih fleksibel.

---

# 14. Model `QuranJuz`

Buka:

```text
app/Models/QuranJuz.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuranJuz extends Model
{
    protected $fillable = [
        'number',
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
        ];
    }

    public function startingSurahs(): HasMany
    {
        return $this->hasMany(QuranSurah::class, 'start_juz_id');
    }

    public function endingSurahs(): HasMany
    {
        return $this->hasMany(QuranSurah::class, 'end_juz_id');
    }

    public function mushafPages(): HasMany
    {
        return $this->hasMany(MushafPage::class, 'juz_id');
    }
}
```

---

# 15. Model `QuranSurah`

Buka:

```text
app/Models/QuranSurah.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuranSurah extends Model
{
    protected $fillable = [
        'number',
        'name_latin',
        'name_arabic',
        'meaning',
        'total_ayah',
        'revelation_place',
        'start_juz_id',
        'end_juz_id',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'total_ayah' => 'integer',
        ];
    }

    public function startJuz(): BelongsTo
    {
        return $this->belongsTo(QuranJuz::class, 'start_juz_id');
    }

    public function endJuz(): BelongsTo
    {
        return $this->belongsTo(QuranJuz::class, 'end_juz_id');
    }

    public function startingPages(): HasMany
    {
        return $this->hasMany(MushafPage::class, 'start_surah_id');
    }

    public function endingPages(): HasMany
    {
        return $this->hasMany(MushafPage::class, 'end_surah_id');
    }

    public function hafalanRecordsStartingHere(): HasMany
    {
        return $this->hasMany(HafalanRecord::class, 'start_surah_id');
    }

    public function hafalanRecordsEndingHere(): HasMany
    {
        return $this->hasMany(HafalanRecord::class, 'end_surah_id');
    }
}
```

---

# 16. Model `MushafPage`

Buka:

```text
app/Models/MushafPage.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MushafPage extends Model
{
    public const DEFAULT_TOTAL_LINES = 15;

    protected $fillable = [
        'page_number',
        'total_lines',
        'start_surah_id',
        'start_ayah',
        'end_surah_id',
        'end_ayah',
        'juz_id',
    ];

    protected function casts(): array
    {
        return [
            'page_number' => 'integer',
            'total_lines' => 'integer',
            'start_ayah' => 'integer',
            'end_ayah' => 'integer',
        ];
    }

    public function startSurah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'start_surah_id');
    }

    public function endSurah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'end_surah_id');
    }

    public function juz(): BelongsTo
    {
        return $this->belongsTo(QuranJuz::class, 'juz_id');
    }
}
```

---

# 17. Model `TahfizhTarget`

Buka:

```text
app/Models/TahfizhTarget.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahfizhTarget extends Model
{
    protected $fillable = [
        'school_id',
        'class_room_id',
        'student_id',
        'name',
        'program_type',
        'daily_target_lines',
        'weekly_target_lines',
        'monthly_target_lines',
        'effective_from',
        'effective_until',
        'created_by',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'daily_target_lines' => 'integer',
            'weekly_target_lines' => 'integer',
            'monthly_target_lines' => 'integer',
            'effective_from' => 'date',
            'effective_until' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hafalanRecords(): HasMany
    {
        return $this->hasMany(HafalanRecord::class);
    }
}
```

---

# 18. Model `HafalanRecord`

Buka:

```text
app/Models/HafalanRecord.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HafalanRecord extends Model
{
    use SoftDeletes;

    public const STATUS_LUNAS = 'lunas';
    public const STATUS_KURANG = 'kurang';
    public const STATUS_LEBIH = 'lebih';
    public const STATUS_TIDAK_HADIR = 'tidak_hadir';
    public const STATUS_IZIN = 'izin';
    public const STATUS_SAKIT = 'sakit';

    protected $fillable = [
        'school_id',
        'student_id',
        'teacher_id',
        'tahfizh_target_id',
        'record_date',
        'start_surah_id',
        'start_ayah',
        'end_surah_id',
        'end_ayah',
        'start_page',
        'start_line',
        'end_page',
        'end_line',
        'total_lines',
        'status',
        'quality_score',
        'notes',
        'is_sequence_valid',
        'sequence_note',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'start_ayah' => 'integer',
            'end_ayah' => 'integer',
            'start_page' => 'integer',
            'start_line' => 'integer',
            'end_page' => 'integer',
            'end_line' => 'integer',
            'total_lines' => 'integer',
            'quality_score' => 'integer',
            'is_sequence_valid' => 'boolean',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_LUNAS,
            self::STATUS_KURANG,
            self::STATUS_LEBIH,
            self::STATUS_TIDAK_HADIR,
            self::STATUS_IZIN,
            self::STATUS_SAKIT,
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function tahfizhTarget(): BelongsTo
    {
        return $this->belongsTo(TahfizhTarget::class);
    }

    public function startSurah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'start_surah_id');
    }

    public function endSurah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'end_surah_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
```

---

# 19. Update Model `School`

Buka:

```text
app/Models/School.php
```

Tambahkan import jika belum ada:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
```

Tambahkan method:

```php
public function tahfizhTargets(): HasMany
{
    return $this->hasMany(TahfizhTarget::class);
}

public function hafalanRecords(): HasMany
{
    return $this->hasMany(HafalanRecord::class);
}
```

---

# 20. Update Model `Student`

Buka:

```text
app/Models/Student.php
```

Pastikan ada import:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
```

Tambahkan method:

```php
public function tahfizhTargets(): HasMany
{
    return $this->hasMany(TahfizhTarget::class);
}

public function hafalanRecords(): HasMany
{
    return $this->hasMany(HafalanRecord::class);
}
```

---

# 21. Update Model `ClassRoom`

Buka:

```text
app/Models/ClassRoom.php
```

Tambahkan method:

```php
public function tahfizhTargets(): HasMany
{
    return $this->hasMany(TahfizhTarget::class);
}
```

---

# 22. Service `LineRangeCalculator`

Buka:

```text
app/Services/Tahfizh/LineRangeCalculator.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Tahfizh;

use InvalidArgumentException;

class LineRangeCalculator
{
    public const MIN_PAGE = 1;
    public const MAX_PAGE = 604;
    public const MIN_LINE = 1;
    public const MAX_LINE = 15;

    public function calculate(
        int $startPage,
        int $startLine,
        int $endPage,
        int $endLine
    ): int {
        $this->validateRange($startPage, $startLine, $endPage, $endLine);

        if ($startPage === $endPage) {
            return ($endLine - $startLine) + 1;
        }

        $firstPageLines = self::MAX_LINE - $startLine + 1;
        $middlePages = max(0, $endPage - $startPage - 1);
        $middleLines = $middlePages * self::MAX_LINE;
        $lastPageLines = $endLine;

        return $firstPageLines + $middleLines + $lastPageLines;
    }

    public function validateRange(
        int $startPage,
        int $startLine,
        int $endPage,
        int $endLine
    ): void {
        if ($startPage < self::MIN_PAGE || $startPage > self::MAX_PAGE) {
            throw new InvalidArgumentException('Halaman awal harus antara 1 sampai 604.');
        }

        if ($endPage < self::MIN_PAGE || $endPage > self::MAX_PAGE) {
            throw new InvalidArgumentException('Halaman akhir harus antara 1 sampai 604.');
        }

        if ($startLine < self::MIN_LINE || $startLine > self::MAX_LINE) {
            throw new InvalidArgumentException('Baris awal harus antara 1 sampai 15.');
        }

        if ($endLine < self::MIN_LINE || $endLine > self::MAX_LINE) {
            throw new InvalidArgumentException('Baris akhir harus antara 1 sampai 15.');
        }

        if ($endPage < $startPage) {
            throw new InvalidArgumentException('Halaman akhir tidak boleh lebih kecil dari halaman awal.');
        }

        if ($startPage === $endPage && $endLine < $startLine) {
            throw new InvalidArgumentException('Baris akhir tidak boleh lebih kecil dari baris awal pada halaman yang sama.');
        }
    }
}
```

---

# 23. Seeder `QuranJuzSeeder`

Buka:

```text
database/seeders/QuranJuzSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\QuranJuz;
use Illuminate\Database\Seeder;

class QuranJuzSeeder extends Seeder
{
    public function run(): void
    {
        for ($number = 1; $number <= 30; $number++) {
            QuranJuz::query()->updateOrCreate(
                ['number' => $number],
                [
                    'name' => 'Juz '.$number,
                    'description' => null,
                ]
            );
        }
    }
}
```

---

# 24. Seeder `QuranSurahSeeder`

Buka:

```text
database/seeders/QuranSurahSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\QuranJuz;
use App\Models\QuranSurah;
use Illuminate\Database\Seeder;

class QuranSurahSeeder extends Seeder
{
    public function run(): void
    {
        $surahs = [
            [1, 'Al-Fatihah', 'الفاتحة', 'Pembukaan', 7, 'Makkiyah', 1, 1],
            [2, 'Al-Baqarah', 'البقرة', 'Sapi Betina', 286, 'Madaniyah', 1, 3],
            [3, 'Ali Imran', 'آل عمران', 'Keluarga Imran', 200, 'Madaniyah', 3, 4],
            [4, 'An-Nisa', 'النساء', 'Wanita', 176, 'Madaniyah', 4, 6],
            [5, 'Al-Maidah', 'المائدة', 'Hidangan', 120, 'Madaniyah', 6, 7],
            [6, 'Al-Anam', 'الأنعام', 'Binatang Ternak', 165, 'Makkiyah', 7, 8],
            [7, 'Al-Araf', 'الأعراف', 'Tempat Tertinggi', 206, 'Makkiyah', 8, 9],
            [8, 'Al-Anfal', 'الأنفال', 'Rampasan Perang', 75, 'Madaniyah', 9, 10],
            [9, 'At-Taubah', 'التوبة', 'Pengampunan', 129, 'Madaniyah', 10, 11],
            [10, 'Yunus', 'يونس', 'Nabi Yunus', 109, 'Makkiyah', 11, 11],
            [11, 'Hud', 'هود', 'Nabi Hud', 123, 'Makkiyah', 11, 12],
            [12, 'Yusuf', 'يوسف', 'Nabi Yusuf', 111, 'Makkiyah', 12, 13],
            [13, 'Ar-Rad', 'الرعد', 'Guruh', 43, 'Madaniyah', 13, 13],
            [14, 'Ibrahim', 'ابراهيم', 'Nabi Ibrahim', 52, 'Makkiyah', 13, 13],
            [15, 'Al-Hijr', 'الحجر', 'Gunung Al-Hijr', 99, 'Makkiyah', 14, 14],
            [16, 'An-Nahl', 'النحل', 'Lebah', 128, 'Makkiyah', 14, 14],
            [17, 'Al-Isra', 'الإسراء', 'Perjalanan Malam', 111, 'Makkiyah', 15, 15],
            [18, 'Al-Kahf', 'الكهف', 'Gua', 110, 'Makkiyah', 15, 16],
            [19, 'Maryam', 'مريم', 'Maryam', 98, 'Makkiyah', 16, 16],
            [20, 'Taha', 'طه', 'Taha', 135, 'Makkiyah', 16, 16],
            [21, 'Al-Anbiya', 'الأنبياء', 'Para Nabi', 112, 'Makkiyah', 17, 17],
            [22, 'Al-Hajj', 'الحج', 'Haji', 78, 'Madaniyah', 17, 17],
            [23, 'Al-Muminun', 'المؤمنون', 'Orang-Orang Mukmin', 118, 'Makkiyah', 18, 18],
            [24, 'An-Nur', 'النور', 'Cahaya', 64, 'Madaniyah', 18, 18],
            [25, 'Al-Furqan', 'الفرقان', 'Pembeda', 77, 'Makkiyah', 18, 19],
            [26, 'Asy-Syuara', 'الشعراء', 'Para Penyair', 227, 'Makkiyah', 19, 19],
            [27, 'An-Naml', 'النمل', 'Semut', 93, 'Makkiyah', 19, 20],
            [28, 'Al-Qasas', 'القصص', 'Kisah-Kisah', 88, 'Makkiyah', 20, 20],
            [29, 'Al-Ankabut', 'العنكبوت', 'Laba-Laba', 69, 'Makkiyah', 20, 21],
            [30, 'Ar-Rum', 'الروم', 'Bangsa Romawi', 60, 'Makkiyah', 21, 21],
            [31, 'Luqman', 'لقمان', 'Luqman', 34, 'Makkiyah', 21, 21],
            [32, 'As-Sajdah', 'السجدة', 'Sujud', 30, 'Makkiyah', 21, 21],
            [33, 'Al-Ahzab', 'الأحزاب', 'Golongan Yang Bersekutu', 73, 'Madaniyah', 21, 22],
            [34, 'Saba', 'سبإ', 'Kaum Saba', 54, 'Makkiyah', 22, 22],
            [35, 'Fatir', 'فاطر', 'Pencipta', 45, 'Makkiyah', 22, 22],
            [36, 'Yasin', 'يس', 'Yasin', 83, 'Makkiyah', 22, 23],
            [37, 'As-Saffat', 'الصافات', 'Barisan-Barisan', 182, 'Makkiyah', 23, 23],
            [38, 'Sad', 'ص', 'Sad', 88, 'Makkiyah', 23, 23],
            [39, 'Az-Zumar', 'الزمر', 'Rombongan-Rombongan', 75, 'Makkiyah', 23, 24],
            [40, 'Ghafir', 'غافر', 'Yang Mengampuni', 85, 'Makkiyah', 24, 24],
            [41, 'Fussilat', 'فصلت', 'Yang Dijelaskan', 54, 'Makkiyah', 24, 25],
            [42, 'Asy-Syura', 'الشورى', 'Musyawarah', 53, 'Makkiyah', 25, 25],
            [43, 'Az-Zukhruf', 'الزخرف', 'Perhiasan', 89, 'Makkiyah', 25, 25],
            [44, 'Ad-Dukhan', 'الدخان', 'Kabut', 59, 'Makkiyah', 25, 25],
            [45, 'Al-Jasiyah', 'الجاثية', 'Yang Berlutut', 37, 'Makkiyah', 25, 25],
            [46, 'Al-Ahqaf', 'الأحقاف', 'Bukit Pasir', 35, 'Makkiyah', 26, 26],
            [47, 'Muhammad', 'محمد', 'Nabi Muhammad', 38, 'Madaniyah', 26, 26],
            [48, 'Al-Fath', 'الفتح', 'Kemenangan', 29, 'Madaniyah', 26, 26],
            [49, 'Al-Hujurat', 'الحجرات', 'Kamar-Kamar', 18, 'Madaniyah', 26, 26],
            [50, 'Qaf', 'ق', 'Qaf', 45, 'Makkiyah', 26, 26],
            [51, 'Az-Zariyat', 'الذاريات', 'Angin yang Menerbangkan', 60, 'Makkiyah', 26, 27],
            [52, 'At-Tur', 'الطور', 'Bukit Tur', 49, 'Makkiyah', 27, 27],
            [53, 'An-Najm', 'النجم', 'Bintang', 62, 'Makkiyah', 27, 27],
            [54, 'Al-Qamar', 'القمر', 'Bulan', 55, 'Makkiyah', 27, 27],
            [55, 'Ar-Rahman', 'الرحمن', 'Yang Maha Pemurah', 78, 'Madaniyah', 27, 27],
            [56, 'Al-Waqiah', 'الواقعة', 'Hari Kiamat', 96, 'Makkiyah', 27, 27],
            [57, 'Al-Hadid', 'الحديد', 'Besi', 29, 'Madaniyah', 27, 27],
            [58, 'Al-Mujadilah', 'المجادلة', 'Wanita yang Mengajukan Gugatan', 22, 'Madaniyah', 28, 28],
            [59, 'Al-Hasyr', 'الحشر', 'Pengusiran', 24, 'Madaniyah', 28, 28],
            [60, 'Al-Mumtahanah', 'الممتحنة', 'Wanita yang Diuji', 13, 'Madaniyah', 28, 28],
            [61, 'As-Saff', 'الصف', 'Barisan', 14, 'Madaniyah', 28, 28],
            [62, 'Al-Jumuah', 'الجمعة', 'Hari Jumat', 11, 'Madaniyah', 28, 28],
            [63, 'Al-Munafiqun', 'المنافقون', 'Orang-Orang Munafik', 11, 'Madaniyah', 28, 28],
            [64, 'At-Taghabun', 'التغابن', 'Hari Ditampakkan Kesalahan', 18, 'Madaniyah', 28, 28],
            [65, 'At-Talaq', 'الطلاق', 'Talak', 12, 'Madaniyah', 28, 28],
            [66, 'At-Tahrim', 'التحريم', 'Pengharaman', 12, 'Madaniyah', 28, 28],
            [67, 'Al-Mulk', 'الملك', 'Kerajaan', 30, 'Makkiyah', 29, 29],
            [68, 'Al-Qalam', 'القلم', 'Pena', 52, 'Makkiyah', 29, 29],
            [69, 'Al-Haqqah', 'الحاقة', 'Hari Kiamat', 52, 'Makkiyah', 29, 29],
            [70, 'Al-Maarij', 'المعارج', 'Tempat Naik', 44, 'Makkiyah', 29, 29],
            [71, 'Nuh', 'نوح', 'Nabi Nuh', 28, 'Makkiyah', 29, 29],
            [72, 'Al-Jinn', 'الجن', 'Jin', 28, 'Makkiyah', 29, 29],
            [73, 'Al-Muzzammil', 'المزمل', 'Orang yang Berselimut', 20, 'Makkiyah', 29, 29],
            [74, 'Al-Muddassir', 'المدثر', 'Orang yang Berselimut', 56, 'Makkiyah', 29, 29],
            [75, 'Al-Qiyamah', 'القيامة', 'Hari Kiamat', 40, 'Makkiyah', 29, 29],
            [76, 'Al-Insan', 'الانسان', 'Manusia', 31, 'Madaniyah', 29, 29],
            [77, 'Al-Mursalat', 'المرسلات', 'Malaikat-Malaikat yang Diutus', 50, 'Makkiyah', 29, 29],
            [78, 'An-Naba', 'النبإ', 'Berita Besar', 40, 'Makkiyah', 30, 30],
            [79, 'An-Naziat', 'النازعات', 'Malaikat yang Mencabut', 46, 'Makkiyah', 30, 30],
            [80, 'Abasa', 'عبس', 'Ia Bermuka Masam', 42, 'Makkiyah', 30, 30],
            [81, 'At-Takwir', 'التكوير', 'Menggulung', 29, 'Makkiyah', 30, 30],
            [82, 'Al-Infitar', 'الإنفطار', 'Terbelah', 19, 'Makkiyah', 30, 30],
            [83, 'Al-Mutaffifin', 'المطففين', 'Orang-Orang Curang', 36, 'Makkiyah', 30, 30],
            [84, 'Al-Insyiqaq', 'الإنشقاق', 'Terbelah', 25, 'Makkiyah', 30, 30],
            [85, 'Al-Buruj', 'البروج', 'Gugusan Bintang', 22, 'Makkiyah', 30, 30],
            [86, 'At-Tariq', 'الطارق', 'Yang Datang di Malam Hari', 17, 'Makkiyah', 30, 30],
            [87, 'Al-Ala', 'الأعلى', 'Yang Paling Tinggi', 19, 'Makkiyah', 30, 30],
            [88, 'Al-Ghasyiyah', 'الغاشية', 'Hari Pembalasan', 26, 'Makkiyah', 30, 30],
            [89, 'Al-Fajr', 'الفجر', 'Fajar', 30, 'Makkiyah', 30, 30],
            [90, 'Al-Balad', 'البلد', 'Negeri', 20, 'Makkiyah', 30, 30],
            [91, 'Asy-Syams', 'الشمس', 'Matahari', 15, 'Makkiyah', 30, 30],
            [92, 'Al-Lail', 'الليل', 'Malam', 21, 'Makkiyah', 30, 30],
            [93, 'Ad-Duha', 'الضحى', 'Waktu Duha', 11, 'Makkiyah', 30, 30],
            [94, 'Asy-Syarh', 'الشرح', 'Lapang', 8, 'Makkiyah', 30, 30],
            [95, 'At-Tin', 'التين', 'Buah Tin', 8, 'Makkiyah', 30, 30],
            [96, 'Al-Alaq', 'العلق', 'Segumpal Darah', 19, 'Makkiyah', 30, 30],
            [97, 'Al-Qadr', 'القدر', 'Kemuliaan', 5, 'Makkiyah', 30, 30],
            [98, 'Al-Bayyinah', 'البينة', 'Bukti Nyata', 8, 'Madaniyah', 30, 30],
            [99, 'Az-Zalzalah', 'الزلزلة', 'Guncangan', 8, 'Madaniyah', 30, 30],
            [100, 'Al-Adiyat', 'العاديات', 'Kuda yang Berlari Kencang', 11, 'Makkiyah', 30, 30],
            [101, 'Al-Qariah', 'القارعة', 'Hari Kiamat', 11, 'Makkiyah', 30, 30],
            [102, 'At-Takatsur', 'التكاثر', 'Bermegah-Megahan', 8, 'Makkiyah', 30, 30],
            [103, 'Al-Asr', 'العصر', 'Masa', 3, 'Makkiyah', 30, 30],
            [104, 'Al-Humazah', 'الهمزة', 'Pengumpat', 9, 'Makkiyah', 30, 30],
            [105, 'Al-Fil', 'الفيل', 'Gajah', 5, 'Makkiyah', 30, 30],
            [106, 'Quraisy', 'قريش', 'Suku Quraisy', 4, 'Makkiyah', 30, 30],
            [107, 'Al-Maun', 'الماعون', 'Barang Berguna', 7, 'Makkiyah', 30, 30],
            [108, 'Al-Kausar', 'الكوثر', 'Nikmat yang Banyak', 3, 'Makkiyah', 30, 30],
            [109, 'Al-Kafirun', 'الكافرون', 'Orang-Orang Kafir', 6, 'Makkiyah', 30, 30],
            [110, 'An-Nasr', 'النصر', 'Pertolongan', 3, 'Madaniyah', 30, 30],
            [111, 'Al-Lahab', 'اللهب', 'Gejolak Api', 5, 'Makkiyah', 30, 30],
            [112, 'Al-Ikhlas', 'الإخلاص', 'Ikhlas', 4, 'Makkiyah', 30, 30],
            [113, 'Al-Falaq', 'الفلق', 'Subuh', 5, 'Makkiyah', 30, 30],
            [114, 'An-Nas', 'الناس', 'Manusia', 6, 'Makkiyah', 30, 30],
        ];

        foreach ($surahs as [$number, $latin, $arabic, $meaning, $totalAyah, $place, $startJuz, $endJuz]) {
            QuranSurah::query()->updateOrCreate(
                ['number' => $number],
                [
                    'name_latin' => $latin,
                    'name_arabic' => $arabic,
                    'meaning' => $meaning,
                    'total_ayah' => $totalAyah,
                    'revelation_place' => $place,
                    'start_juz_id' => QuranJuz::query()->where('number', $startJuz)->value('id'),
                    'end_juz_id' => QuranJuz::query()->where('number', $endJuz)->value('id'),
                ]
            );
        }
    }
}
```

---

# 25. Seeder `MushafPageSeeder`

Buka:

```text
database/seeders/MushafPageSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\MushafPage;
use Illuminate\Database\Seeder;

class MushafPageSeeder extends Seeder
{
    public function run(): void
    {
        for ($page = 1; $page <= 604; $page++) {
            MushafPage::query()->updateOrCreate(
                ['page_number' => $page],
                [
                    'total_lines' => 15,
                    'start_surah_id' => null,
                    'start_ayah' => null,
                    'end_surah_id' => null,
                    'end_ayah' => null,
                    'juz_id' => null,
                ]
            );
        }
    }
}
```

Catatan:

Mushaf pojok dasar dibuat dulu dengan 604 halaman dan 15 baris per halaman. Mapping detail surah/ayah per halaman dapat dilengkapi pada fase enrichment data, bukan Phase 3.

---

# 26. Update `DatabaseSeeder`

Buka:

```text
database/seeders/DatabaseSeeder.php
```

Pastikan memanggil seeder Qur’an setelah seeder Phase 1.

Isi lengkap yang direkomendasikan:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SchoolSeeder::class,
            InitialUserSeeder::class,
            QuranJuzSeeder::class,
            QuranSurahSeeder::class,
            MushafPageSeeder::class,
        ]);
    }
}
```

---

# 27. Jalankan Migration

Jika project masih development dan data belum penting, boleh jalankan:

```powershell
php artisan migrate
```

Jika butuh reset total development database:

```powershell
php artisan migrate:fresh --seed
```

Untuk Phase 3, jika data master hasil input Phase 2 belum penting, `migrate:fresh --seed` boleh digunakan.

Jika data Phase 2 sudah diinput manual dan ingin dipertahankan, gunakan:

```powershell
php artisan migrate
php artisan db:seed --class=QuranJuzSeeder
php artisan db:seed --class=QuranSurahSeeder
php artisan db:seed --class=MushafPageSeeder
```

---

# 28. Validasi Seeder

Jalankan:

```powershell
php artisan tinker
```

Lalu cek:

```php
App\Models\QuranJuz::count();
App\Models\QuranSurah::count();
App\Models\MushafPage::count();
```

Target:

```text
30
114
604
```

Keluar dari tinker:

```php
exit
```

---

# 29. Validasi Service LineRangeCalculator

Jalankan:

```powershell
php artisan tinker
```

Cek:

```php
$calculator = app(App\Services\Tahfizh\LineRangeCalculator::class);

$calculator->calculate(1, 1, 1, 15);
$calculator->calculate(1, 10, 2, 5);
$calculator->calculate(10, 1, 12, 15);
```

Target:

```text
15
11
45
```

Penjelasan:

1. Halaman 1 baris 1–15 = 15 baris.
2. Halaman 1 baris 10 sampai halaman 2 baris 5 = 6 + 5 = 11 baris.
3. Halaman 10 baris 1 sampai halaman 12 baris 15 = 15 + 15 + 15 = 45 baris.

Keluar:

```php
exit
```

---

# 30. Buat Sample Target via Tinker

Jalankan:

```powershell
php artisan tinker
```

Contoh:

```php
$school = App\Models\School::first();
$admin = App\Models\User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->first();

App\Models\TahfizhTarget::create([
    'school_id' => $school->id,
    'class_room_id' => null,
    'student_id' => null,
    'name' => 'Target Tahfizh Reguler',
    'program_type' => 'reguler',
    'daily_target_lines' => 0,
    'weekly_target_lines' => 5,
    'monthly_target_lines' => 20,
    'effective_from' => now()->toDateString(),
    'effective_until' => null,
    'created_by' => $admin?->id,
    'is_active' => true,
]);
```

Cek:

```php
App\Models\TahfizhTarget::count();
```

Keluar:

```php
exit
```

---

# 31. Buat Sample Hafalan Record via Tinker

Jalankan:

```powershell
php artisan tinker
```

Contoh:

```php
$student = App\Models\Student::first();
$teacher = App\Models\User::whereHas('role', fn ($q) => $q->where('name', 'teacher'))->first();
$school = $student->school;
$calculator = app(App\Services\Tahfizh\LineRangeCalculator::class);

$totalLines = $calculator->calculate(1, 1, 1, 5);

App\Models\HafalanRecord::create([
    'school_id' => $school->id,
    'student_id' => $student->id,
    'teacher_id' => $teacher?->id,
    'tahfizh_target_id' => null,
    'record_date' => now()->toDateString(),
    'start_surah_id' => 1,
    'start_ayah' => 1,
    'end_surah_id' => 1,
    'end_ayah' => 7,
    'start_page' => 1,
    'start_line' => 1,
    'end_page' => 1,
    'end_line' => 5,
    'total_lines' => $totalLines,
    'status' => App\Models\HafalanRecord::STATUS_KURANG,
    'quality_score' => null,
    'notes' => 'Sample setoran Phase 3.',
    'is_sequence_valid' => false,
    'sequence_note' => 'Sequential validation detail masuk fase berikutnya.',
    'created_by' => $teacher?->id,
    'updated_by' => null,
]);
```

Cek:

```php
App\Models\HafalanRecord::count();
App\Models\HafalanRecord::first()->total_lines;
```

Target:

```text
1
5
```

Keluar:

```php
exit
```

---

# 32. Dokumentasi Phase 3

Buat file:

```text
docs/phase-3-tahfizh-core-database.md
```

Isi lengkap:

````md
# Phase 3 — Tahfizh Core Database Foundation

## Status

Phase 3 membangun fondasi database dan domain logic awal untuk fitur tahfizh di HafizPlus School Platform.

## Output

Tabel yang dibuat:

1. `quran_juzs`
2. `quran_surahs`
3. `mushaf_pages`
4. `tahfizh_targets`
5. `hafalan_records`

Model yang dibuat:

1. `QuranJuz`
2. `QuranSurah`
3. `MushafPage`
4. `TahfizhTarget`
5. `HafalanRecord`

Seeder yang dibuat:

1. `QuranJuzSeeder`
2. `QuranSurahSeeder`
3. `MushafPageSeeder`

Service yang dibuat:

1. `App\Services\Tahfizh\LineRangeCalculator`

## Prinsip Mushaf

Sistem menggunakan prinsip mushaf pojok:

```text
1 halaman = 15 baris
Total halaman = 604 halaman
````

Pada Phase 3, mapping halaman ke surah/ayah masih minimal. Data yang wajib tersedia adalah 604 halaman dan 15 baris per halaman.

## Status Setoran

Status setoran awal:

1. `lunas`
2. `kurang`
3. `lebih`
4. `tidak_hadir`
5. `izin`
6. `sakit`

Status disimpan sebagai string agar fleksibel.

## Belum Dibuat

Phase 3 belum membuat:

1. UI input setoran.
2. Quick input guru.
3. Bulk input.
4. Sequential validation penuh.
5. Hitung hutang otomatis.
6. Report bulanan.
7. Report triwulan.
8. Dashboard statistik.
9. Parent progress detail.
10. Student progress detail.
11. Notifikasi real.
12. Export PDF/Excel.

## Validasi

Phase 3 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. `QuranJuz::count()` menghasilkan `30`.
4. `QuranSurah::count()` menghasilkan `114`.
5. `MushafPage::count()` menghasilkan `604`.
6. `LineRangeCalculator` bisa menghitung total baris.
7. Sample `TahfizhTarget` bisa dibuat.
8. Sample `HafalanRecord` bisa dibuat.
9. Tidak ada fitur UI input setoran yang dibuat pada Phase 3.

## Catatan Strategis

Phase 3 adalah fondasi domain tahfizh.

Jangan melompat ke dashboard atau report sebelum struktur target, setoran, dan kalkulasi baris benar.

````

---

# 33. Update Checklist Phase

Boleh update file:

```text
docs/phase-0-checklist.md
````

Atau buat file baru:

```text
docs/project-progress.md
```

Isi minimal:

```md
# Project Progress — HafizPlus School Platform

| Phase | Nama | Status |
|---:|---|---|
| 0 | Product Foundation | Done |
| 1 | Auth, Role, and Initial Database Foundation | Done |
| 2 | Master Data Foundation | Done |
| 3 | Tahfizh Core Database Foundation | Done |
| 4 | Tahfizh Input Foundation | Pending |
| 5 | Target and Debt Calculation | Pending |
| 6 | Dashboard and Reports | Pending |
```

---

# 34. Jalankan Validasi Akhir

Jalankan:

```powershell
php artisan migrate:status
php artisan tinker
```

Di tinker:

```php
App\Models\QuranJuz::count();
App\Models\QuranSurah::count();
App\Models\MushafPage::count();
```

Target:

```text
30
114
604
```

Lalu:

```php
app(App\Services\Tahfizh\LineRangeCalculator::class)->calculate(1, 1, 1, 15);
```

Target:

```text
15
```

Keluar:

```php
exit
```

---

# 35. Build Frontend

Meskipun Phase 3 tidak mengubah UI besar, tetap jalankan:

```powershell
npm run build
```

---

# 36. Commit Phase 3

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add tahfizh core database foundation"
```

Jika remote sudah tersedia:

```powershell
git push origin phase-3-tahfizh-core-database
```

---

# 37. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 3 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Tabel dibuat:
- quran_juzs
- quran_surahs
- mushaf_pages
- tahfizh_targets
- hafalan_records

Model dibuat:
- QuranJuz
- QuranSurah
- MushafPage
- TahfizhTarget
- HafalanRecord

Seeder dibuat:
- QuranJuzSeeder
- QuranSurahSeeder
- MushafPageSeeder

Service dibuat:
- App\Services\Tahfizh\LineRangeCalculator

Validasi:
- Juz: 30
- Surah: 114
- Mushaf pages: 604
- Line range calculator: berhasil

Belum dibuat:
- UI input setoran
- Quick input
- Sequential validation penuh
- Hutang otomatis
- Report
- Dashboard statistik
- Parent progress detail
- Student progress detail

Status:
- Siap lanjut Phase 4 setelah validasi manual.
```

---

# 38. Larangan Setelah Phase 3

Agent harus berhenti setelah Phase 3 selesai.

Jangan lanjut membuat:

1. UI input setoran.
2. Quick input guru.
3. Bulk input.
4. Dashboard statistik.
5. Report bulanan.
6. Report triwulan.
7. Parent portal detail.
8. Student portal detail.
9. Notification center.
10. Export PDF/Excel.

Semua itu masuk Phase berikutnya.
