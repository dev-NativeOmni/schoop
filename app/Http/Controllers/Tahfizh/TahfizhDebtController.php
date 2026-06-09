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
