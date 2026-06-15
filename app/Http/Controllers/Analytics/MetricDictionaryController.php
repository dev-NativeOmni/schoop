<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Http\Requests\Analytics\StoreMetricDefinitionRequest;
use App\Http\Requests\Analytics\UpdateMetricDefinitionRequest;
use App\Models\AnalyticsMetricDefinition;
use App\Services\Analytics\AnalyticsAccessService;
use App\Services\Analytics\MetricDictionaryService;
use Illuminate\Http\Request;

class MetricDictionaryController extends Controller
{
    public function __construct(
        private readonly AnalyticsAccessService $access,
        private readonly MetricDictionaryService $service
    ) {}

    public function index(Request $request)
    {
        $this->access->canViewInternalExecutiveAnalytics($request->user()) or abort(403);

        return view('analytics.metric-dictionary.index', [
            'categories' => $this->service->listAll(),
            'canManage' => $this->access->canManageMetricDictionary($request->user()),
        ]);
    }

    public function create(Request $request)
    {
        $this->access->canManageMetricDictionary($request->user()) or abort(403);

        return view('analytics.metric-dictionary.create');
    }

    public function store(StoreMetricDefinitionRequest $request)
    {
        $this->access->canManageMetricDictionary($request->user()) or abort(403);

        $metric = AnalyticsMetricDefinition::query()->create($request->validated());

        return redirect()->route('analytics.metric-dictionary.index')->with('success', 'Metrik baru ditambahkan ke Kamus.');
    }

    public function edit(Request $request, AnalyticsMetricDefinition $metricDictionary)
    {
        $this->access->canManageMetricDictionary($request->user()) or abort(403);

        return view('analytics.metric-dictionary.edit', [
            'metric' => $metricDictionary,
        ]);
    }

    public function update(UpdateMetricDefinitionRequest $request, AnalyticsMetricDefinition $metricDictionary)
    {
        $this->access->canManageMetricDictionary($request->user()) or abort(403);

        $metricDictionary->update($request->validated());

        return redirect()->route('analytics.metric-dictionary.index')->with('success', 'Metrik diperbarui.');
    }

    public function destroy(Request $request, AnalyticsMetricDefinition $metricDictionary)
    {
        $this->access->canManageMetricDictionary($request->user()) or abort(403);

        $metricDictionary->delete();

        return redirect()->route('analytics.metric-dictionary.index')->with('success', 'Metrik dihapus.');
    }
}
