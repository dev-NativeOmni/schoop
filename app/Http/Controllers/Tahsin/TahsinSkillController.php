<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\StoreTahsinSkillRequest;
use App\Http\Requests\Tahsin\UpdateTahsinSkillRequest;
use App\Models\TahsinLevel;
use App\Models\TahsinSkill;
use App\Services\Tahsin\TahsinAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahsinSkillController extends Controller
{
    public function index(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $skills = TahsinSkill::query()
            ->with('level')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(30);

        return view('tahsin.skills.index', compact('skills'));
    }

    public function create(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tahsin.skills.create', compact('levels'));
    }

    public function store(StoreTahsinSkillRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        TahsinSkill::query()->create($data);

        return redirect()
            ->route('tahsin.skills.index')
            ->with('success', 'Skill tahsin berhasil dibuat.');
    }

    public function show(Request $request, TahsinSkill $skill, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $skill->load('level');

        return view('tahsin.skills.show', compact('skill'));
    }

    public function edit(Request $request, TahsinSkill $skill, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tahsin.skills.edit', compact('skill', 'levels'));
    }

    public function update(UpdateTahsinSkillRequest $request, TahsinSkill $skill): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        $skill->update($data);

        return redirect()
            ->route('tahsin.skills.index')
            ->with('success', 'Skill tahsin berhasil diperbarui.');
    }

    public function destroy(Request $request, TahsinSkill $skill, TahsinAccessService $accessService): RedirectResponse
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        $skill->delete();

        return redirect()
            ->route('tahsin.skills.index')
            ->with('success', 'Skill tahsin berhasil dihapus.');
    }
}
