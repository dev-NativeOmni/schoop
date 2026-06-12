<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolOs\UpdateSchoolSettingRequest;
use App\Models\SchoolSetting;
use App\Services\SchoolOs\SchoolOsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolSettingController extends Controller
{
    public function index(Request $request, SchoolOsAccessService $accessService): View
    {
        abort_unless($accessService->canManageSettings($request->user()), 403);

        $settings = SchoolSetting::query()
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        return view('schoolos.settings.index', compact('settings'));
    }

    public function update(UpdateSchoolSettingRequest $request): RedirectResponse
    {
        foreach ($request->validated('settings') as $key => $value) {
            SchoolSetting::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'key' => $key,
                ],
                [
                    'value' => $value,
                    'type' => 'string',
                ]
            );
        }

        return redirect()
            ->route('schoolos.settings.index')
            ->with('success', 'Pengaturan sekolah berhasil diperbarui.');
    }
}
