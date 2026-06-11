<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\StoreTahsinLevelRequest;
use App\Http\Requests\Tahsin\UpdateTahsinLevelRequest;
use App\Models\TahsinLevel;
use App\Services\Tahsin\TahsinAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TahsinLevelController extends Controller
{
    public function index(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $levels = TahsinLevel::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('tahsin.levels.index', compact('levels'));
    }

    public function create(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        return view('tahsin.levels.create');
    }

    public function store(StoreTahsinLevelRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        TahsinLevel::query()->create($data);

        return redirect()
            ->route('tahsin.levels.index')
            ->with('success', 'Level tahsin berhasil dibuat.');
    }

    public function show(Request $request, TahsinLevel $level, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $level->load('skills');

        return view('tahsin.levels.show', compact('level'));
    }

    public function edit(Request $request, TahsinLevel $level, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        return view('tahsin.levels.edit', compact('level'));
    }

    public function update(UpdateTahsinLevelRequest $request, TahsinLevel $level): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        $level->update($data);

        return redirect()
            ->route('tahsin.levels.index')
            ->with('success', 'Level tahsin berhasil diperbarui.');
    }

    public function destroy(Request $request, TahsinLevel $level, TahsinAccessService $accessService): RedirectResponse
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        $level->delete();

        return redirect()
            ->route('tahsin.levels.index')
            ->with('success', 'Level tahsin berhasil dihapus.');
    }
}
