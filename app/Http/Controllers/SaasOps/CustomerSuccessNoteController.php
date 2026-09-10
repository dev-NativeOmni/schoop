<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreCustomerSuccessNoteRequest;
use App\Models\CustomerSuccessNote;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerSuccessNoteController extends Controller
{
    public function index(): View
    {
        return view('saas-ops.customer-success-notes.index', ['notes' => CustomerSuccessNote::query()->with('school')->latest()->paginate(20)]);
    }

    public function create(): View
    {
        return view('saas-ops.customer-success-notes.create', ['schools' => School::query()->orderBy('name')->get()]);
    }

    public function store(StoreCustomerSuccessNoteRequest $request): RedirectResponse
    {
        $note = CustomerSuccessNote::query()->create(array_merge($request->validated(), ['user_id' => $request->user()->id]));

        return redirect()->route('saas-ops.customer-success-notes.show', $note)->with('success', 'Customer success note dibuat.');
    }

    public function show(CustomerSuccessNote $customerSuccessNote): View
    {
        return view('saas-ops.customer-success-notes.show', ['note' => $customerSuccessNote->load('school')]);
    }
}
