<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolOs\SchoolOsSearchRequest;
use App\Services\SchoolOs\SchoolOsAccessService;
use App\Services\SchoolOs\SchoolOsSearchService;
use Illuminate\View\View;

class SchoolOsSearchController extends Controller
{
    public function __invoke(
        SchoolOsSearchRequest $request,
        SchoolOsAccessService $accessService,
        SchoolOsSearchService $searchService
    ): View {
        $results = $searchService->search(
            $request->validated('q'),
            $request->user(),
            $accessService
        );

        return view('schoolos.search-results', [
            'keyword' => $request->validated('q'),
            'results' => $results,
        ]);
    }
}
