<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiTeacherReviewQueue;
use App\Services\Ai\AiAccessService;
use App\Services\Ai\AiTeacherReviewService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiTeacherReviewQueueController extends Controller
{
    protected AiTeacherReviewService $reviewService;

    protected AiAccessService $accessService;

    protected TenantContextService $tenantContext;

    public function __construct(
        AiTeacherReviewService $reviewService,
        AiAccessService $accessService,
        TenantContextService $tenantContext
    ) {
        $this->reviewService = $reviewService;
        $this->accessService = $accessService;
        $this->tenantContext = $tenantContext;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();

        $queueItems = AiTeacherReviewQueue::query()
            ->where('school_id', $schoolId)
            ->with(['student.user', 'output'])
            ->latest()
            ->paginate(15);

        return view('ai.review-queue.index', compact('queueItems'));
    }

    public function show(AiTeacherReviewQueue $reviewItem): View
    {
        if (! $this->accessService->canReviewAiOutput(Auth::user(), $reviewItem->student)) {
            abort(403, 'Unauthorized access to review queue.');
        }

        $reviewItem->load(['student.user', 'output']);

        return view('ai.review-queue.show', [
            'item' => $reviewItem,
        ]);
    }

    public function approve(Request $request, AiTeacherReviewQueue $reviewItem): RedirectResponse
    {
        if (! $this->accessService->canReviewAiOutput(Auth::user(), $reviewItem->student)) {
            abort(403, 'Unauthorized to approve review queue item.');
        }

        $note = $request->input('review_note');
        $this->reviewService->approve($reviewItem, $note);

        return redirect()->route('ai-learning.review-queue.index')->with('success', 'Umpan balik berhasil disetujui.');
    }

    public function reject(Request $request, AiTeacherReviewQueue $reviewItem): RedirectResponse
    {
        if (! $this->accessService->canReviewAiOutput(Auth::user(), $reviewItem->student)) {
            abort(403, 'Unauthorized to reject review queue item.');
        }

        $note = $request->input('review_note');
        $this->reviewService->reject($reviewItem, $note);

        return redirect()->route('ai-learning.review-queue.index')->with('success', 'Umpan balik berhasil ditolak.');
    }

    public function publish(Request $request, AiTeacherReviewQueue $reviewItem): RedirectResponse
    {
        if (! $this->accessService->canReviewAiOutput(Auth::user(), $reviewItem->student)) {
            abort(403, 'Unauthorized to publish review queue item.');
        }

        $this->reviewService->publish($reviewItem);

        return redirect()->route('ai-learning.review-queue.index')->with('success', 'Umpan balik berhasil dipublikasikan.');
    }
}
