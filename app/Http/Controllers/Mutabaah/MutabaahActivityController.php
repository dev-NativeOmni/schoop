<?php

namespace App\Http\Controllers\Mutabaah;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutabaah\StoreMutabaahActivityRequest;
use App\Http\Requests\Mutabaah\UpdateMutabaahActivityRequest;
use App\Models\MutabaahActivity;
use App\Models\MutabaahCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MutabaahActivityController extends Controller
{
    public function index(Request $request): View
    {
        $activities = MutabaahActivity::query()
            ->with('category')
            ->when($request->filled('category_id'), fn ($q) => $q->where('mutabaah_category_id', $request->integer('category_id')))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $categories = MutabaahCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('mutabaah.activities.index', compact('activities', 'categories'));
    }

    public function create(): View
    {
        $categories = MutabaahCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('mutabaah.activities.create', compact('categories'));
    }

    public function store(StoreMutabaahActivityRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['is_required'] = $request->boolean('is_required');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['allow_teacher_input'] = $request->boolean('allow_teacher_input', true);
        $data['allow_parent_input'] = $request->boolean('allow_parent_input');
        $data['allow_student_input'] = $request->boolean('allow_student_input');

        MutabaahActivity::create($data);

        return redirect()
            ->route('mutabaah.activities.index')
            ->with('success', 'Aktivitas mutabaah berhasil dibuat.');
    }

    public function show(MutabaahActivity $activity): View
    {
        $activity->load('category');

        return view('mutabaah.activities.show', compact('activity'));
    }

    public function edit(MutabaahActivity $activity): View
    {
        $categories = MutabaahCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('mutabaah.activities.edit', compact('activity', 'categories'));
    }

    public function update(UpdateMutabaahActivityRequest $request, MutabaahActivity $activity): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['is_required'] = $request->boolean('is_required');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['allow_teacher_input'] = $request->boolean('allow_teacher_input', true);
        $data['allow_parent_input'] = $request->boolean('allow_parent_input');
        $data['allow_student_input'] = $request->boolean('allow_student_input');

        $activity->update($data);

        return redirect()
            ->route('mutabaah.activities.index')
            ->with('success', 'Aktivitas mutabaah berhasil diperbarui.');
    }

    public function destroy(MutabaahActivity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()
            ->route('mutabaah.activities.index')
            ->with('success', 'Aktivitas mutabaah berhasil dihapus.');
    }
}
