<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreReleaseNoteRequest;
use App\Models\ReleaseNote;
use App\Services\SaasOps\ReleaseManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReleaseNoteController extends Controller
{
    public function __construct(private readonly ReleaseManagementService $releases) {}

    public function index(): View
    {
        return view('saas-ops.release-notes.index', ['releaseNotes' => ReleaseNote::query()->latest()->paginate(20)]);
    }

    public function create(): View
    {
        return view('saas-ops.release-notes.create', ['releaseNote' => null]);
    }

    public function store(StoreReleaseNoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        $releaseNote = ReleaseNote::query()->create($data);

        return redirect()->route('saas-ops.release-notes.show', $releaseNote)->with('success', 'Release note dibuat.');
    }

    public function show(ReleaseNote $releaseNote): View
    {
        return view('saas-ops.release-notes.show', compact('releaseNote'));
    }

    public function edit(ReleaseNote $releaseNote): View
    {
        return view('saas-ops.release-notes.edit', compact('releaseNote'));
    }

    public function update(StoreReleaseNoteRequest $request, ReleaseNote $releaseNote): RedirectResponse
    {
        $releaseNote->update($request->validated());
        if ($releaseNote->status === 'published' && ! $releaseNote->published_at) {
            $this->releases->publish($releaseNote, $request->user());
        }

        return redirect()->route('saas-ops.release-notes.show', $releaseNote)->with('success', 'Release note diperbarui.');
    }

    public function destroy(ReleaseNote $releaseNote): RedirectResponse
    {
        $releaseNote->update(['status' => 'draft']);

        return back()->with('success', 'Release note dikembalikan ke draft.');
    }
}
