# Phase 5 Execution Guide — Target and Debt Calculation

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

# 1. Tujuan Phase 5

Phase 5 bertujuan membuat sistem mampu menghitung:

1. Target hafalan santri.
2. Total capaian hafalan.
3. Kekurangan hafalan.
4. Kelebihan hafalan.
5. Hutang hafalan harian.
6. Akumulasi hutang hafalan.
7. Status capaian santri:

   * belum ada target
   * tercapai
   * kurang
   * lebih

Phase 5 membuat modul **Target & Hutang Hafalan**.

Phase ini belum membuat:

1. Report bulanan final.
2. Report triwulan final.
3. Dashboard grafik besar.
4. Export PDF.
5. Export Excel.
6. Parent portal detail.
7. Student portal detail.
8. Notifikasi otomatis.

---

# 2. Batasan Phase 5

AI agent tidak boleh membuat fitur berikut pada Phase 5:

1. Dashboard statistik lengkap.
2. Grafik Chart.js.
3. Recharts.
4. Export PDF.
5. Export Excel.
6. Parent portal detail.
7. Student portal detail.
8. Notification center.
9. WhatsApp gateway.
10. API mobile.
11. Attendance.
12. Mutabaah.
13. Tahsin.
14. Finance.
15. Cashless.
16. Multi-tenant kompleks.

Phase 5 hanya membuat:

1. CRUD target tahfizh.
2. Perhitungan hutang hafalan.
3. Tabel hasil hutang hafalan.
4. Service resolver target.
5. Service kalkulasi hutang.
6. Halaman ringkas target dan hutang.
7. Dokumentasi Phase 5.

---

# 3. Target Output Phase 5

Setelah Phase 5 selesai, aplikasi harus punya:

1. Menu **Target Tahfizh**.
2. Menu **Hutang Hafalan**.
3. CRUD target tahfizh.
4. Tabel `tahfizh_debts`.
5. Model `TahfizhDebt`.
6. Service:

   * `ActiveTahfizhTargetResolver`
   * `TahfizhDebtCalculator`
7. Controller:

   * `TahfizhTargetController`
   * `TahfizhDebtController`
8. Form Request:

   * `StoreTahfizhTargetRequest`
   * `UpdateTahfizhTargetRequest`
   * `CalculateTahfizhDebtRequest`
9. View:

   * target index/create/edit/show
   * debt index/show
10. Role access:

* Super Admin: manage target dan hutang
* Admin Sekolah: manage target dan hutang
* Kepala Sekolah: lihat target dan hutang
* Guru Tahfidz: lihat target dan hutang
* Orang Tua: belum akses
* Santri: belum akses

---

# 4. Konsep Perhitungan

## 4.1 Target

Target bisa berlaku untuk:

1. Satu sekolah.
2. Satu kelas.
3. Satu santri.
4. Program tertentu.

Prioritas target:

| Prioritas | Target               |
| --------: | -------------------- |
|         1 | Target khusus santri |
|         2 | Target kelas         |
|         3 | Target program       |
|         4 | Target umum sekolah  |

Jika santri punya target khusus, pakai target santri.

Jika tidak ada, pakai target kelas.

Jika tidak ada, pakai target program.

Jika tidak ada, pakai target umum sekolah.

---

## 4.2 Capaian

Capaian dihitung dari tabel:

```text
hafalan_records
```

Kolom utama:

```text
total_lines
```

Status yang dihitung sebagai capaian:

```text
lunas
kurang
lebih
```

Status yang tidak dihitung pada Phase 5:

```text
tidak_hadir
izin
sakit
```

Alasan:

Status tidak hadir, izin, dan sakit perlu desain absensi tahfizh terpisah. Pada Phase 5, hutang dihitung dari target dan capaian setoran saja.

---

## 4.3 Hutang

Rumus dasar:

```text
hutang = max(0, target_lines - actual_lines)
```

Rumus lebih:

```text
lebih = max(0, actual_lines - target_lines)
```

Rumus akumulasi hutang:

```text
akumulasi_hutang = max(0, hutang_sebelumnya + hutang_hari_ini - lebih_hari_ini)
```

Contoh:

```text
Target hari ini: 5 baris
Capaian hari ini: 3 baris
Hutang hari ini: 2 baris
```

Contoh lebih:

```text
Target hari ini: 5 baris
Capaian hari ini: 8 baris
Lebih hari ini: 3 baris
```

Jika sebelumnya punya hutang 4 baris, maka:

```text
Akumulasi hutang = max(0, 4 - 3) = 1 baris
```

---

# 5. Validasi Awal Sebelum Eksekusi

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
2. Phase 1 selesai.
3. Phase 2 selesai.
4. Phase 3 selesai.
5. Phase 4 selesai.
6. Tabel berikut sudah ada:

   * `schools`
   * `roles`
   * `users`
   * `class_rooms`
   * `students`
   * `tahfizh_targets`
   * `hafalan_records`
7. Route setoran tahfizh sudah tersedia.
8. Working tree bersih atau semua perubahan sudah diketahui.

Jika Phase 4 belum selesai, hentikan eksekusi.

---

# 6. Buat Branch Git Phase 5

Jalankan:

```powershell
git checkout -b phase-5-target-debt-calculation
```

Jika branch sudah ada:

```powershell
git checkout phase-5-target-debt-calculation
```

---

# 7. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Tahfizh/
│   │       ├── TahfizhTargetController.php
│   │       └── TahfizhDebtController.php
│   └── Requests/
│       └── Tahfizh/
│           ├── StoreTahfizhTargetRequest.php
│           ├── UpdateTahfizhTargetRequest.php
│           └── CalculateTahfizhDebtRequest.php
├── Models/
│   └── TahfizhDebt.php
├── Services/
│   └── Tahfizh/
│       ├── ActiveTahfizhTargetResolver.php
│       └── TahfizhDebtCalculator.php

database/
└── migrations/
    └── xxxx_xx_xx_xxxxxx_create_tahfizh_debts_table.php

resources/
└── views/
    └── tahfizh/
        ├── targets/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   ├── edit.blade.php
        │   └── show.blade.php
        └── debts/
            ├── index.blade.php
            └── show.blade.php

routes/
└── web.php

docs/
└── phase-5-target-debt-calculation.md
```

---

# 8. Buat Model, Migration, Controller, dan Request

Jalankan:

```powershell
php artisan make:model TahfizhDebt -m

php artisan make:controller Tahfizh/TahfizhTargetController --resource
php artisan make:controller Tahfizh/TahfizhDebtController

php artisan make:request Tahfizh/StoreTahfizhTargetRequest
php artisan make:request Tahfizh/UpdateTahfizhTargetRequest
php artisan make:request Tahfizh/CalculateTahfizhDebtRequest
```

Buat service:

```powershell
New-Item app\Services\Tahfizh\ActiveTahfizhTargetResolver.php
New-Item app\Services\Tahfizh\TahfizhDebtCalculator.php
```

Jika folder belum ada:

```powershell
mkdir app\Services
mkdir app\Services\Tahfizh
```

---

# 9. Migration `create_tahfizh_debts_table`

Cari file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tahfizh_debts_table.php
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
        Schema::create('tahfizh_debts', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('class_room_id')
                ->nullable()
                ->constrained('class_rooms')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('tahfizh_target_id')
                ->nullable()
                ->constrained('tahfizh_targets')
                ->nullOnDelete();

            $table->string('period_type')->default('daily');
            $table->date('calculation_date');
            $table->date('period_start');
            $table->date('period_end');

            $table->unsignedInteger('target_lines')->default(0);
            $table->unsignedInteger('actual_lines')->default(0);
            $table->unsignedInteger('debt_lines')->default(0);
            $table->unsignedInteger('surplus_lines')->default(0);
            $table->unsignedInteger('cumulative_debt_lines')->default(0);

            $table->string('status')->default('no_target');

            $table->foreignId('calculated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['student_id', 'period_type', 'period_start', 'period_end'],
                'tahfizh_debt_unique_period'
            );

            $table->index('school_id');
            $table->index('class_room_id');
            $table->index('student_id');
            $table->index('tahfizh_target_id');
            $table->index('period_type');
            $table->index('calculation_date');
            $table->index(['period_start', 'period_end']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahfizh_debts');
    }
};
```

---

# 10. Model `TahfizhDebt`

Buka:

```text
app/Models/TahfizhDebt.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfizhDebt extends Model
{
    public const PERIOD_DAILY = 'daily';
    public const PERIOD_WEEKLY = 'weekly';
    public const PERIOD_MONTHLY = 'monthly';

    public const STATUS_NO_TARGET = 'no_target';
    public const STATUS_MET = 'met';
    public const STATUS_BEHIND = 'behind';
    public const STATUS_AHEAD = 'ahead';

    protected $fillable = [
        'school_id',
        'class_room_id',
        'student_id',
        'tahfizh_target_id',
        'period_type',
        'calculation_date',
        'period_start',
        'period_end',
        'target_lines',
        'actual_lines',
        'debt_lines',
        'surplus_lines',
        'cumulative_debt_lines',
        'status',
        'calculated_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'calculation_date' => 'date',
            'period_start' => 'date',
            'period_end' => 'date',
            'target_lines' => 'integer',
            'actual_lines' => 'integer',
            'debt_lines' => 'integer',
            'surplus_lines' => 'integer',
            'cumulative_debt_lines' => 'integer',
        ];
    }

    public static function periodTypes(): array
    {
        return [
            self::PERIOD_DAILY,
            self::PERIOD_WEEKLY,
            self::PERIOD_MONTHLY,
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_NO_TARGET,
            self::STATUS_MET,
            self::STATUS_BEHIND,
            self::STATUS_AHEAD,
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

    public function tahfizhTarget(): BelongsTo
    {
        return $this->belongsTo(TahfizhTarget::class);
    }

    public function calculator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'calculated_by');
    }
}
```

---

# 11. Update Model `TahfizhTarget`

Buka:

```text
app/Models/TahfizhTarget.php
```

Pastikan method berikut ada:

```php
public function debts(): HasMany
{
    return $this->hasMany(TahfizhDebt::class);
}
```

Pastikan import ini ada:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
```

---

# 12. Update Model `Student`

Buka:

```text
app/Models/Student.php
```

Tambahkan method:

```php
public function tahfizhDebts(): HasMany
{
    return $this->hasMany(TahfizhDebt::class);
}
```

Pastikan import ini ada:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
```

---

# 13. Update Model `School`

Buka:

```text
app/Models/School.php
```

Tambahkan method:

```php
public function tahfizhDebts(): HasMany
{
    return $this->hasMany(TahfizhDebt::class);
}
```

---

# 14. Update Model `ClassRoom`

Buka:

```text
app/Models/ClassRoom.php
```

Tambahkan method:

```php
public function tahfizhDebts(): HasMany
{
    return $this->hasMany(TahfizhDebt::class);
}
```

---

# 15. Service `ActiveTahfizhTargetResolver`

Buka:

```text
app/Services/Tahfizh/ActiveTahfizhTargetResolver.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Tahfizh;

use App\Models\Student;
use App\Models\TahfizhTarget;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class ActiveTahfizhTargetResolver
{
    public function resolveForStudent(Student $student, CarbonInterface|string $date): ?TahfizhTarget
    {
        $date = $date instanceof CarbonInterface
            ? Carbon::instance($date->toDateTime())
            : Carbon::parse($date);

        return TahfizhTarget::query()
            ->where('school_id', $student->school_id)
            ->where('is_active', true)
            ->where(function ($query) use ($date): void {
                $query
                    ->whereNull('effective_from')
                    ->orWhereDate('effective_from', '<=', $date);
            })
            ->where(function ($query) use ($date): void {
                $query
                    ->whereNull('effective_until')
                    ->orWhereDate('effective_until', '>=', $date);
            })
            ->where(function ($query) use ($student): void {
                $query
                    ->where('student_id', $student->id)
                    ->orWhere('class_room_id', $student->class_room_id)
                    ->orWhere('program_type', $student->program_type)
                    ->orWhere(function ($fallbackQuery): void {
                        $fallbackQuery
                            ->whereNull('student_id')
                            ->whereNull('class_room_id')
                            ->whereNull('program_type');
                    });
            })
            ->orderByRaw(
                "
                CASE
                    WHEN student_id = ? THEN 1
                    WHEN class_room_id = ? THEN 2
                    WHEN program_type = ? THEN 3
                    ELSE 4
                END
                ",
                [
                    $student->id,
                    $student->class_room_id,
                    $student->program_type,
                ]
            )
            ->latest('id')
            ->first();
    }
}
```

---

# 16. Service `TahfizhDebtCalculator`

Buka:

```text
app/Services/Tahfizh/TahfizhDebtCalculator.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Tahfizh;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\TahfizhTarget;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class TahfizhDebtCalculator
{
    public function __construct(
        private readonly ActiveTahfizhTargetResolver $targetResolver,
    ) {
        //
    }

    public function calculateForStudent(
        Student $student,
        CarbonInterface|string $date,
        string $periodType = TahfizhDebt::PERIOD_DAILY,
        ?User $calculatedBy = null
    ): TahfizhDebt {
        $date = $date instanceof CarbonInterface
            ? Carbon::instance($date->toDateTime())
            : Carbon::parse($date);

        [$periodStart, $periodEnd] = $this->periodRange($date, $periodType);

        $target = $this->targetResolver->resolveForStudent($student, $date);

        $targetLines = $target
            ? $this->targetLines($target, $periodType)
            : 0;

        $actualLines = $this->actualLines(
            student: $student,
            periodStart: $periodStart,
            periodEnd: $periodEnd
        );

        $debtLines = max(0, $targetLines - $actualLines);
        $surplusLines = max(0, $actualLines - $targetLines);

        $previousDebt = TahfizhDebt::query()
            ->where('student_id', $student->id)
            ->where('period_type', $periodType)
            ->whereDate('period_end', '<', $periodStart)
            ->orderByDesc('period_end')
            ->orderByDesc('id')
            ->first();

        $previousCumulativeDebt = $previousDebt?->cumulative_debt_lines ?? 0;

        $cumulativeDebtLines = max(
            0,
            $previousCumulativeDebt + $debtLines - $surplusLines
        );

        $status = $this->status(
            targetLines: $targetLines,
            actualLines: $actualLines,
            debtLines: $debtLines,
            surplusLines: $surplusLines,
        );

        return TahfizhDebt::query()->updateOrCreate(
            [
                'student_id' => $student->id,
                'period_type' => $periodType,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
            ],
            [
                'school_id' => $student->school_id,
                'class_room_id' => $student->class_room_id,
                'tahfizh_target_id' => $target?->id,
                'calculation_date' => $date->toDateString(),
                'target_lines' => $targetLines,
                'actual_lines' => $actualLines,
                'debt_lines' => $debtLines,
                'surplus_lines' => $surplusLines,
                'cumulative_debt_lines' => $cumulativeDebtLines,
                'status' => $status,
                'calculated_by' => $calculatedBy?->id,
                'notes' => $this->notes($target, $targetLines, $actualLines, $debtLines, $surplusLines),
            ]
        );
    }

    public function calculateForStudents(
        iterable $students,
        CarbonInterface|string $date,
        string $periodType = TahfizhDebt::PERIOD_DAILY,
        ?User $calculatedBy = null
    ): array {
        $results = [];

        foreach ($students as $student) {
            $results[] = $this->calculateForStudent(
                student: $student,
                date: $date,
                periodType: $periodType,
                calculatedBy: $calculatedBy
            );
        }

        return $results;
    }

    private function actualLines(Student $student, Carbon $periodStart, Carbon $periodEnd): int
    {
        return (int) HafalanRecord::query()
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->whereIn('status', [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ])
            ->sum('total_lines');
    }

    private function periodRange(Carbon $date, string $periodType): array
    {
        return match ($periodType) {
            TahfizhDebt::PERIOD_DAILY => [
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
            ],
            TahfizhDebt::PERIOD_WEEKLY => [
                $date->copy()->startOfWeek(),
                $date->copy()->endOfWeek(),
            ],
            TahfizhDebt::PERIOD_MONTHLY => [
                $date->copy()->startOfMonth(),
                $date->copy()->endOfMonth(),
            ],
            default => throw new InvalidArgumentException('Jenis periode tidak valid.'),
        };
    }

    private function targetLines(TahfizhTarget $target, string $periodType): int
    {
        return match ($periodType) {
            TahfizhDebt::PERIOD_DAILY => (int) $target->daily_target_lines,
            TahfizhDebt::PERIOD_WEEKLY => (int) $target->weekly_target_lines,
            TahfizhDebt::PERIOD_MONTHLY => (int) $target->monthly_target_lines,
            default => 0,
        };
    }

    private function status(
        int $targetLines,
        int $actualLines,
        int $debtLines,
        int $surplusLines
    ): string {
        if ($targetLines <= 0) {
            return TahfizhDebt::STATUS_NO_TARGET;
        }

        if ($debtLines > 0) {
            return TahfizhDebt::STATUS_BEHIND;
        }

        if ($surplusLines > 0) {
            return TahfizhDebt::STATUS_AHEAD;
        }

        return TahfizhDebt::STATUS_MET;
    }

    private function notes(
        ?TahfizhTarget $target,
        int $targetLines,
        int $actualLines,
        int $debtLines,
        int $surplusLines
    ): string {
        if (! $target || $targetLines <= 0) {
            return 'Belum ada target aktif untuk periode ini.';
        }

        if ($debtLines > 0) {
            return "Kurang {$debtLines} baris dari target {$targetLines} baris.";
        }

        if ($surplusLines > 0) {
            return "Lebih {$surplusLines} baris dari target {$targetLines} baris.";
        }

        return "Target tercapai tepat {$actualLines} baris.";
    }
}
```

---

# 17. Form Request `StoreTahfizhTargetRequest`

Buka:

```text
app/Http/Requests/Tahfizh/StoreTahfizhTargetRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahfizh;

use Illuminate\Foundation\Http\FormRequest;

class StoreTahfizhTargetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'name' => ['required', 'string', 'max:255'],
            'program_type' => ['nullable', 'string', 'max:100'],
            'daily_target_lines' => ['required', 'integer', 'min:0', 'max:300'],
            'weekly_target_lines' => ['required', 'integer', 'min:0', 'max:1500'],
            'monthly_target_lines' => ['required', 'integer', 'min:0', 'max:6000'],
            'effective_from' => ['nullable', 'date'],
            'effective_until' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'school_id' => 'sekolah',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'name' => 'nama target',
            'program_type' => 'jenis program',
            'daily_target_lines' => 'target harian',
            'weekly_target_lines' => 'target mingguan',
            'monthly_target_lines' => 'target bulanan',
            'effective_from' => 'tanggal mulai berlaku',
            'effective_until' => 'tanggal akhir berlaku',
            'is_active' => 'status aktif',
        ];
    }
}
```

---

# 18. Form Request `UpdateTahfizhTargetRequest`

Buka:

```text
app/Http/Requests/Tahfizh/UpdateTahfizhTargetRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahfizh;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTahfizhTargetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'name' => ['required', 'string', 'max:255'],
            'program_type' => ['nullable', 'string', 'max:100'],
            'daily_target_lines' => ['required', 'integer', 'min:0', 'max:300'],
            'weekly_target_lines' => ['required', 'integer', 'min:0', 'max:1500'],
            'monthly_target_lines' => ['required', 'integer', 'min:0', 'max:6000'],
            'effective_from' => ['nullable', 'date'],
            'effective_until' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'school_id' => 'sekolah',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'name' => 'nama target',
            'program_type' => 'jenis program',
            'daily_target_lines' => 'target harian',
            'weekly_target_lines' => 'target mingguan',
            'monthly_target_lines' => 'target bulanan',
            'effective_from' => 'tanggal mulai berlaku',
            'effective_until' => 'tanggal akhir berlaku',
            'is_active' => 'status aktif',
        ];
    }
}
```

---

# 19. Form Request `CalculateTahfizhDebtRequest`

Buka:

```text
app/Http/Requests/Tahfizh/CalculateTahfizhDebtRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahfizh;

use App\Models\TahfizhDebt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CalculateTahfizhDebtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'calculation_date' => ['required', 'date'],
            'period_type' => [
                'required',
                'string',
                Rule::in(TahfizhDebt::periodTypes()),
            ],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'calculation_date' => 'tanggal perhitungan',
            'period_type' => 'jenis periode',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
        ];
    }
}
```

---

# 20. Controller `TahfizhTargetController`

Buka:

```text
app/Http/Controllers/Tahfizh/TahfizhTargetController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Tahfizh;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahfizh\StoreTahfizhTargetRequest;
use App\Http\Requests\Tahfizh\UpdateTahfizhTargetRequest;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Models\TahfizhTarget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahfizhTargetController extends Controller
{
    public function index(Request $request): View
    {
        $targets = TahfizhTarget::query()
            ->with(['school', 'classRoom', 'student', 'creator'])
            ->when($request->filled('school_id'), function ($query) use ($request): void {
                $query->where('school_id', $request->integer('school_id'));
            })
            ->when($request->filled('class_room_id'), function ($query) use ($request): void {
                $query->where('class_room_id', $request->integer('class_room_id'));
            })
            ->when($request->filled('student_id'), function ($query) use ($request): void {
                $query->where('student_id', $request->integer('student_id'));
            })
            ->when($request->filled('program_type'), function ($query) use ($request): void {
                $query->where('program_type', $request->input('program_type'));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('tahfizh.targets.index', [
            'targets' => $targets,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('tahfizh.targets.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
        ]);
    }

    public function store(StoreTahfizhTargetRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['created_by'] = $request->user()->id;
        $data['is_active'] = $request->boolean('is_active');

        TahfizhTarget::query()->create($data);

        return redirect()
            ->route('tahfizh.targets.index')
            ->with('success', 'Target tahfizh berhasil dibuat.');
    }

    public function show(TahfizhTarget $target): View
    {
        $target->load(['school', 'classRoom', 'student', 'creator']);

        return view('tahfizh.targets.show', compact('target'));
    }

    public function edit(TahfizhTarget $target): View
    {
        return view('tahfizh.targets.edit', [
            'target' => $target,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
        ]);
    }

    public function update(UpdateTahfizhTargetRequest $request, TahfizhTarget $target): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $target->update($data);

        return redirect()
            ->route('tahfizh.targets.index')
            ->with('success', 'Target tahfizh berhasil diperbarui.');
    }

    public function destroy(TahfizhTarget $target): RedirectResponse
    {
        $target->delete();

        return redirect()
            ->route('tahfizh.targets.index')
            ->with('success', 'Target tahfizh berhasil dihapus.');
    }
}
```

---

# 21. Controller `TahfizhDebtController`

Buka:

```text
app/Http/Controllers/Tahfizh/TahfizhDebtController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Tahfizh;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahfizh\CalculateTahfizhDebtRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Services\Tahfizh\TahfizhDebtCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahfizhDebtController extends Controller
{
    public function __construct(
        private readonly TahfizhDebtCalculator $debtCalculator,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $debts = TahfizhDebt::query()
            ->with(['school', 'classRoom', 'student', 'tahfizhTarget', 'calculator'])
            ->when($request->filled('period_type'), function ($query) use ($request): void {
                $query->where('period_type', $request->input('period_type'));
            })
            ->when($request->filled('class_room_id'), function ($query) use ($request): void {
                $query->where('class_room_id', $request->integer('class_room_id'));
            })
            ->when($request->filled('student_id'), function ($query) use ($request): void {
                $query->where('student_id', $request->integer('student_id'));
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->input('status'));
            })
            ->when($request->filled('date_from'), function ($query) use ($request): void {
                $query->whereDate('period_start', '>=', $request->date('date_from'));
            })
            ->when($request->filled('date_until'), function ($query) use ($request): void {
                $query->whereDate('period_end', '<=', $request->date('date_until'));
            })
            ->latest('period_start')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('tahfizh.debts.index', [
            'debts' => $debts,
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
            'periodTypes' => TahfizhDebt::periodTypes(),
            'statuses' => TahfizhDebt::statuses(),
        ]);
    }

    public function calculate(CalculateTahfizhDebtRequest $request): RedirectResponse
    {
        $students = Student::query()
            ->where('is_active', true)
            ->when($request->filled('class_room_id'), function ($query) use ($request): void {
                $query->where('class_room_id', $request->integer('class_room_id'));
            })
            ->when($request->filled('student_id'), function ($query) use ($request): void {
                $query->where('id', $request->integer('student_id'));
            })
            ->orderBy('full_name')
            ->get();

        $this->debtCalculator->calculateForStudents(
            students: $students,
            date: $request->input('calculation_date'),
            periodType: $request->input('period_type'),
            calculatedBy: $request->user()
        );

        return redirect()
            ->route('tahfizh.debts.index', [
                'period_type' => $request->input('period_type'),
                'class_room_id' => $request->input('class_room_id'),
                'student_id' => $request->input('student_id'),
            ])
            ->with('success', 'Perhitungan hutang hafalan berhasil dijalankan untuk '.$students->count().' santri.');
    }

    public function show(TahfizhDebt $debt): View
    {
        $debt->load([
            'school',
            'classRoom',
            'student',
            'tahfizhTarget',
            'calculator',
        ]);

        return view('tahfizh.debts.show', compact('debt'));
    }
}
```

---

# 22. Update Routes

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Tahfizh\TahfizhDebtController;
use App\Http\Controllers\Tahfizh\TahfizhTargetController;
```

Di dalam group `Route::middleware('auth')->group(...)`, tambahkan:

```php
Route::middleware('role:super_admin,admin,principal,teacher')
    ->prefix('tahfizh')
    ->name('tahfizh.')
    ->group(function (): void {
        Route::get('targets', [TahfizhTargetController::class, 'index'])
            ->name('targets.index');

        Route::get('targets/{target}', [TahfizhTargetController::class, 'show'])
            ->name('targets.show');

        Route::get('debts', [TahfizhDebtController::class, 'index'])
            ->name('debts.index');

        Route::get('debts/{debt}', [TahfizhDebtController::class, 'show'])
            ->name('debts.show');
    });

Route::middleware('role:super_admin,admin')
    ->prefix('tahfizh')
    ->name('tahfizh.')
    ->group(function (): void {
        Route::get('targets/create', [TahfizhTargetController::class, 'create'])
            ->name('targets.create');

        Route::post('targets', [TahfizhTargetController::class, 'store'])
            ->name('targets.store');

        Route::get('targets/{target}/edit', [TahfizhTargetController::class, 'edit'])
            ->name('targets.edit');

        Route::put('targets/{target}', [TahfizhTargetController::class, 'update'])
            ->name('targets.update');

        Route::delete('targets/{target}', [TahfizhTargetController::class, 'destroy'])
            ->name('targets.destroy');

        Route::post('debts/calculate', [TahfizhDebtController::class, 'calculate'])
            ->name('debts.calculate');
    });
```

Catatan:

1. Kepala Sekolah hanya lihat target dan hutang.
2. Guru hanya lihat target dan hutang.
3. Admin dan Super Admin boleh mengelola target dan menjalankan kalkulasi hutang.
4. Orang Tua dan Santri belum dapat akses Phase 5.

---

# 23. Update Navigasi Layout

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan menu:

```blade
@if (auth()->user()->hasRole(['super_admin', 'admin', 'teacher', 'principal']))
    <a href="{{ route('tahfizh.targets.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Target Tahfizh
    </a>

    <a href="{{ route('tahfizh.debts.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Hutang Hafalan
    </a>
@endif
```

---

# 24. Buat Folder View

Jalankan:

```powershell
mkdir resources\views\tahfizh\targets
mkdir resources\views\tahfizh\debts
```

---

# 25. View Target Tahfizh

Agent harus membuat view berikut:

```text
resources/views/tahfizh/targets/index.blade.php
resources/views/tahfizh/targets/create.blade.php
resources/views/tahfizh/targets/edit.blade.php
resources/views/tahfizh/targets/show.blade.php
```

Field wajib pada form target:

```text
school_id
class_room_id
student_id
name
program_type
daily_target_lines
weekly_target_lines
monthly_target_lines
effective_from
effective_until
is_active
```

Aturan tampilan:

1. Gunakan layout `layouts.app`.
2. Gunakan styling yang konsisten dengan Phase 4.
3. Super Admin dan Admin melihat tombol tambah/edit/hapus.
4. Kepala Sekolah dan Guru hanya melihat daftar dan detail.
5. Gunakan validasi error `@error`.
6. Gunakan `old()` untuk menjaga input saat gagal validasi.
7. Field `is_active` pakai checkbox.

---

# 26. View Hutang Hafalan

Agent harus membuat view berikut:

```text
resources/views/tahfizh/debts/index.blade.php
resources/views/tahfizh/debts/show.blade.php
```

## 26.1 Isi Utama `debts/index.blade.php`

Halaman hutang harus memiliki:

1. Judul: `Hutang Hafalan`.
2. Form kalkulasi untuk Super Admin dan Admin:

   * tanggal perhitungan
   * jenis periode
   * filter kelas
   * filter santri
   * tombol `Hitung Hutang`
3. Filter tabel:

   * periode
   * kelas
   * santri
   * status
   * tanggal mulai
   * tanggal akhir
4. Tabel:

   * periode
   * santri
   * kelas
   * target
   * capaian
   * hutang
   * lebih
   * akumulasi hutang
   * status
   * aksi lihat
5. Pagination.

## 26.2 Status Label

Tampilkan status dengan label:

| Status      | Label            |
| ----------- | ---------------- |
| `no_target` | Belum Ada Target |
| `met`       | Tercapai         |
| `behind`    | Kurang           |
| `ahead`     | Lebih            |

---

# 27. Jalankan Migration

Jalankan:

```powershell
php artisan migrate
```

Pastikan tabel berikut muncul:

```text
tahfizh_debts
```

---

# 28. Validasi Route

Jalankan:

```powershell
php artisan route:list
```

Pastikan route berikut ada:

```text
tahfizh.targets.index
tahfizh.targets.create
tahfizh.targets.store
tahfizh.targets.show
tahfizh.targets.edit
tahfizh.targets.update
tahfizh.targets.destroy

tahfizh.debts.index
tahfizh.debts.show
tahfizh.debts.calculate
```

---

# 29. Test Manual Target

## 29.1 Buat Target Umum Sekolah

Login sebagai Super Admin atau Admin.

Buat target:

```text
Nama: Target Reguler Harian
Sekolah: sekolah aktif
Kelas: kosong
Santri: kosong
Program Type: reguler
Target Harian: 5
Target Mingguan: 25
Target Bulanan: 100
Tanggal Mulai: hari ini
Tanggal Akhir: kosong
Aktif: ya
```

Target:

```text
Target berhasil disimpan.
```

---

## 29.2 Buat Target Kelas

Buat target:

```text
Nama: Target Kelas Tahfizh
Sekolah: sekolah aktif
Kelas: kelas aktif
Santri: kosong
Program Type: kosong
Target Harian: 7
Target Mingguan: 35
Target Bulanan: 140
Tanggal Mulai: hari ini
Tanggal Akhir: kosong
Aktif: ya
```

Target:

```text
Santri di kelas tersebut memakai target kelas jika tidak punya target khusus.
```

---

## 29.3 Buat Target Khusus Santri

Buat target:

```text
Nama: Target Khusus Ahmad
Sekolah: sekolah aktif
Kelas: kosong
Santri: Ahmad
Program Type: kosong
Target Harian: 10
Target Mingguan: 50
Target Bulanan: 200
Tanggal Mulai: hari ini
Tanggal Akhir: kosong
Aktif: ya
```

Target:

```text
Ahmad memakai target khusus santri.
```

---

# 30. Test Manual Hutang Harian

Pastikan ada setoran dari Phase 4.

Contoh:

```text
Target Harian: 5 baris
Setoran Hari Ini: 3 baris
```

Jalankan kalkulasi:

```text
Tanggal: hari ini
Periode: daily
Santri: santri terkait
```

Target hasil:

```text
target_lines = 5
actual_lines = 3
debt_lines = 2
surplus_lines = 0
status = behind
```

---

# 31. Test Manual Lebih

Contoh:

```text
Target Harian: 5 baris
Setoran Hari Ini: 8 baris
```

Target hasil:

```text
target_lines = 5
actual_lines = 8
debt_lines = 0
surplus_lines = 3
status = ahead
```

---

# 32. Test Manual Tercapai

Contoh:

```text
Target Harian: 5 baris
Setoran Hari Ini: 5 baris
```

Target hasil:

```text
target_lines = 5
actual_lines = 5
debt_lines = 0
surplus_lines = 0
status = met
```

---

# 33. Test Manual Tanpa Target

Contoh:

```text
Santri tidak punya target aktif.
Setoran Hari Ini: 5 baris
```

Target hasil:

```text
target_lines = 0
actual_lines = 5
debt_lines = 0
surplus_lines = 5
status = no_target
notes = Belum ada target aktif untuk periode ini.
```

---

# 34. Test Manual Akumulasi Hutang

Hari 1:

```text
Target: 5
Capaian: 3
Hutang: 2
Akumulasi: 2
```

Hari 2:

```text
Target: 5
Capaian: 4
Hutang: 1
Akumulasi: 3
```

Hari 3:

```text
Target: 5
Capaian: 8
Lebih: 3
Akumulasi: 0
```

Target:

```text
lebih pada hari berikutnya mengurangi akumulasi hutang.
```

---

# 35. Dokumentasi Phase 5

Buat file:

```text
docs/phase-5-target-debt-calculation.md
```

Isi lengkap:

````md
# Phase 5 — Target and Debt Calculation

## Status

Phase 5 membangun modul target dan hutang hafalan untuk HafizPlus School Platform.

## Output

1. CRUD target tahfizh.
2. Tabel hutang hafalan.
3. Kalkulasi target harian, mingguan, dan bulanan.
4. Kalkulasi capaian dari setoran hafalan.
5. Kalkulasi hutang hafalan.
6. Kalkulasi lebih hafalan.
7. Kalkulasi akumulasi hutang.
8. Status capaian santri.
9. Role-based access.

## Tabel Baru

```text
tahfizh_debts
````

## Service Baru

```text
App\Services\Tahfizh\ActiveTahfizhTargetResolver
App\Services\Tahfizh\TahfizhDebtCalculator
```

## Controller Baru

```text
App\Http\Controllers\Tahfizh\TahfizhTargetController
App\Http\Controllers\Tahfizh\TahfizhDebtController
```

## Route Baru

```text
tahfizh.targets.index
tahfizh.targets.create
tahfizh.targets.store
tahfizh.targets.show
tahfizh.targets.edit
tahfizh.targets.update
tahfizh.targets.destroy

tahfizh.debts.index
tahfizh.debts.show
tahfizh.debts.calculate
```

## Prioritas Target

Target dipilih berdasarkan prioritas berikut:

1. Target khusus santri.
2. Target kelas.
3. Target program.
4. Target umum sekolah.

## Rumus

```text
hutang = max(0, target_lines - actual_lines)
lebih = max(0, actual_lines - target_lines)
akumulasi_hutang = max(0, hutang_sebelumnya + hutang_hari_ini - lebih_hari_ini)
```

## Status

| Status      | Arti                   |
| ----------- | ---------------------- |
| `no_target` | Belum ada target aktif |
| `met`       | Target tercapai        |
| `behind`    | Kurang dari target     |
| `ahead`     | Lebih dari target      |

## Role Access

| Role           | Target      | Hutang           |
| -------------- | ----------- | ---------------- |
| Super Admin    | CRUD        | Lihat dan hitung |
| Admin Sekolah  | CRUD        | Lihat dan hitung |
| Kepala Sekolah | Lihat       | Lihat            |
| Guru Tahfidz   | Lihat       | Lihat            |
| Orang Tua      | Belum akses | Belum akses      |
| Santri         | Belum akses | Belum akses      |

## Belum Dibuat

Phase 5 belum membuat:

1. Report bulanan final.
2. Report triwulan final.
3. Dashboard grafik.
4. Export PDF.
5. Export Excel.
6. Parent progress detail.
7. Student progress detail.
8. Notification center.
9. API mobile.

## Definition of Done

Phase 5 selesai jika:

1. Target tahfizh bisa dibuat.
2. Target tahfizh bisa diedit.
3. Target tahfizh bisa dihapus.
4. Target bisa berlaku untuk sekolah, kelas, program, atau santri.
5. Sistem bisa memilih target aktif berdasarkan prioritas.
6. Hutang harian bisa dihitung.
7. Hutang mingguan bisa dihitung.
8. Hutang bulanan bisa dihitung.
9. Akumulasi hutang berjalan.
10. Status capaian benar.
11. Kepala Sekolah dan Guru bisa melihat hasil hutang.
12. Orang Tua dan Santri belum bisa mengakses halaman ini.
13. Dokumentasi Phase 5 dibuat.

````

---

# 36. Update Project Progress

Buka:

```text
docs/project-progress.md
````

Update menjadi:

```md
# Project Progress — HafizPlus School Platform

| Phase | Nama | Status |
|---:|---|---|
| 0 | Product Foundation | Done |
| 1 | Auth, Role, and Initial Database Foundation | Done |
| 2 | Master Data Foundation | Done |
| 3 | Tahfizh Core Database Foundation | Done |
| 4 | Tahfizh Input Foundation | Done |
| 5 | Target and Debt Calculation | Done |
| 6 | Dashboard and Reports | Pending |
| 7 | Parent and Student Progress Portal | Pending |
| 8 | Notification Center | Pending |
```

---

# 37. Build Frontend

Jalankan:

```powershell
npm run build
```

---

# 38. Validasi Akhir

Jalankan:

```powershell
php artisan migrate:status
php artisan route:list
npm run build
```

Lalu jalankan:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

Tes halaman:

```text
/tahfizh/targets
/tahfizh/debts
```

---

# 39. Commit Phase 5

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add tahfizh target and debt calculation"
```

Jika remote sudah tersedia:

```powershell
git push origin phase-5-target-debt-calculation
```

---

# 40. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 5 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- CRUD Target Tahfizh
- Tabel Hutang Hafalan
- Perhitungan target harian
- Perhitungan target mingguan
- Perhitungan target bulanan
- Perhitungan capaian dari setoran
- Perhitungan hutang hafalan
- Perhitungan lebih hafalan
- Perhitungan akumulasi hutang
- Status capaian santri

Service dibuat:
- ActiveTahfizhTargetResolver
- TahfizhDebtCalculator

Route dibuat:
- tahfizh.targets.index
- tahfizh.targets.create
- tahfizh.targets.store
- tahfizh.targets.show
- tahfizh.targets.edit
- tahfizh.targets.update
- tahfizh.targets.destroy
- tahfizh.debts.index
- tahfizh.debts.show
- tahfizh.debts.calculate

Role access:
- Super Admin: CRUD target dan hitung hutang
- Admin Sekolah: CRUD target dan hitung hutang
- Kepala Sekolah: lihat target dan hutang
- Guru Tahfidz: lihat target dan hutang
- Orang Tua: belum akses
- Santri: belum akses

Belum dibuat:
- Dashboard statistik besar
- Report bulanan final
- Report triwulan final
- Export PDF
- Export Excel
- Parent portal detail
- Student portal detail
- Notification center

Status:
- Siap lanjut Phase 6 setelah validasi manual.
```

---

# 41. Larangan Setelah Phase 5

Agent harus berhenti setelah Phase 5 selesai.

Jangan lanjut membuat:

1. Dashboard statistik besar.
2. Grafik.
3. Report bulanan final.
4. Report triwulan final.
5. Export PDF.
6. Export Excel.
7. Parent portal detail.
8. Student portal detail.
9. Notification center.
10. WhatsApp gateway.
11. API mobile.

Semua itu masuk fase berikutnya.
