<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreKnowledgeBaseArticleRequest;
use App\Models\KnowledgeBaseArticle;
use App\Services\SaasOps\KnowledgeBaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KnowledgeBaseArticleController extends Controller
{
    public function __construct(private readonly KnowledgeBaseService $knowledgeBase) {}

    public function index(): View
    {
        return view('saas-ops.knowledge-base.index', ['articles' => KnowledgeBaseArticle::query()->latest()->paginate(20)]);
    }

    public function create(): View
    {
        return view('saas-ops.knowledge-base.create', ['article' => null]);
    }

    public function store(StoreKnowledgeBaseArticleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->knowledgeBase->slug($data['title']);
        $data['created_by'] = $request->user()->id;
        $data['published_at'] = $data['status'] === 'published' ? now() : null;
        $article = KnowledgeBaseArticle::query()->create($data);

        return redirect()->route('saas-ops.knowledge-base.show', $article)->with('success', 'Artikel dibuat.');
    }

    public function show(KnowledgeBaseArticle $knowledgeBase): View
    {
        return view('saas-ops.knowledge-base.show', ['article' => $knowledgeBase]);
    }

    public function edit(KnowledgeBaseArticle $knowledgeBase): View
    {
        return view('saas-ops.knowledge-base.edit', ['article' => $knowledgeBase]);
    }

    public function update(StoreKnowledgeBaseArticleRequest $request, KnowledgeBaseArticle $knowledgeBase): RedirectResponse
    {
        $data = $request->validated();
        $data['published_at'] = $data['status'] === 'published' ? ($knowledgeBase->published_at ?: now()) : null;
        $knowledgeBase->update($data);

        return redirect()->route('saas-ops.knowledge-base.show', $knowledgeBase)->with('success', 'Artikel diperbarui.');
    }

    public function destroy(KnowledgeBaseArticle $knowledgeBase): RedirectResponse
    {
        $knowledgeBase->update(['status' => 'draft']);

        return back()->with('success', 'Artikel dikembalikan ke draft.');
    }
}
