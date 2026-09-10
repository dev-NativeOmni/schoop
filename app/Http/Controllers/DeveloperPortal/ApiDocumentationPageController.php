<?php

namespace App\Http\Controllers\DeveloperPortal;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeveloperPortal\StoreApiDocumentationPageRequest;
use App\Models\ApiDocumentationPage;
use App\Services\DeveloperPortal\ApiAccessService;

class ApiDocumentationPageController extends Controller
{
    public function __construct(private readonly ApiAccessService $access) {}

    public function index()
    {
        $this->access->assertDeveloperPortalAccess(request()->user());

        return view('developer-portal.docs.index', [
            'pages' => ApiDocumentationPage::query()->orderBy('sort_order')->orderBy('title')->paginate(30),
        ]);
    }

    public function create()
    {
        $this->access->assertDeveloperPortalAccess(request()->user());
        $this->access->canManageDocs(request()->user()) ?: abort(403);

        return view('developer-portal.docs.create', ['page' => null]);
    }

    public function store(StoreApiDocumentationPageRequest $request)
    {
        abort_unless($this->access->canManageDocs($request->user()), 403);
        $payload = $request->validated();
        $payload['created_by'] = $request->user()->id;
        $payload['updated_by'] = $request->user()->id;
        $payload['published_at'] = $payload['status'] === 'published' ? now() : null;
        $page = ApiDocumentationPage::query()->create($payload);

        return redirect()->route('developer-portal.docs.show', $page)->with('success', 'Dokumentasi API dibuat.');
    }

    public function show(ApiDocumentationPage $doc)
    {
        $this->access->assertDeveloperPortalAccess(request()->user());

        return view('developer-portal.docs.show', [
            'page' => $doc,
        ]);
    }

    public function edit(ApiDocumentationPage $doc)
    {
        abort_unless($this->access->canManageDocs(request()->user()), 403);

        return view('developer-portal.docs.edit', [
            'page' => $doc,
        ]);
    }

    public function update(StoreApiDocumentationPageRequest $request, ApiDocumentationPage $doc)
    {
        abort_unless($this->access->canManageDocs($request->user()), 403);
        $payload = $request->validated();
        $payload['updated_by'] = $request->user()->id;
        $payload['published_at'] = $payload['status'] === 'published'
            ? ($doc->published_at ?: now())
            : null;
        $doc->update($payload);

        return redirect()->route('developer-portal.docs.show', $doc)->with('success', 'Dokumentasi API diperbarui.');
    }

    public function destroy(ApiDocumentationPage $doc)
    {
        abort_unless($this->access->canManageDocs(request()->user()), 403);
        $doc->update(['status' => 'archived']);

        return redirect()->route('developer-portal.docs.index')->with('success', 'Dokumentasi API diarsipkan.');
    }
}
