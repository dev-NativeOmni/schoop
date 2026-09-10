<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'user_id',
        'class_room_id',
        'student_number',
        'nisn',
        'full_name',
        'nickname',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'program_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentProfile::class, 'parent_student')
            ->withPivot(['relationship', 'is_primary'])
            ->withTimestamps();
    }

    public function tahfizhTargets(): HasMany
    {
        return $this->hasMany(TahfizhTarget::class);
    }

    public function hafalanRecords(): HasMany
    {
        return $this->hasMany(HafalanRecord::class);
    }

    public function tahfizhDebts(): HasMany
    {
        return $this->hasMany(TahfizhDebt::class);
    }

    public function mutabaahRecords(): HasMany
    {
        return $this->hasMany(MutabaahRecord::class);
    }

    public function attendanceQrToken(): HasOne
    {
        return $this->hasOne(AttendanceQrToken::class)
            ->where('is_active', true);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function tahsinProfile(): HasOne
    {
        return $this->hasOne(TahsinStudentProfile::class);
    }

    public function tahsinAssessments(): HasMany
    {
        return $this->hasMany(TahsinAssessment::class);
    }

    public function financeBills(): HasMany
    {
        return $this->hasMany(StudentBill::class);
    }

    public function financePayments(): HasMany
    {
        return $this->hasMany(StudentPayment::class);
    }

    public function financeLedgerEntries(): HasMany
    {
        return $this->hasMany(FinanceLedgerEntry::class);
    }

    public function cashlessWallet(): HasOne
    {
        return $this->hasOne(CashlessWallet::class);
    }

    public function cashlessTransactions(): HasMany
    {
        return $this->hasMany(CashlessWalletTransaction::class);
    }

    public function boardingAssignments(): HasMany
    {
        return $this->hasMany(BoardingStudentAssignment::class);
    }

    public function activeBoardingAssignment(): HasOne
    {
        return $this->hasOne(BoardingStudentAssignment::class)->where('status', 'active');
    }

    public function boardingLeaveRequests(): HasMany
    {
        return $this->hasMany(BoardingLeaveRequest::class);
    }

    public function boardingHealthLogs(): HasMany
    {
        return $this->hasMany(BoardingHealthLog::class);
    }

    public function boardingDisciplineLogs(): HasMany
    {
        return $this->hasMany(BoardingDisciplineLog::class);
    }
}
