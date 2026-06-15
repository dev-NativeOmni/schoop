<?php

namespace App\Http\Controllers\SaasOps;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaasOps\StoreSupportTicketMessageRequest;
use App\Http\Requests\SaasOps\StoreSupportTicketRequest;
use App\Models\School;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\SaasOps\SaasOperationsAccessService;
use App\Services\SaasOps\SupportTicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function __construct(private readonly SaasOperationsAccessService $access, private readonly SupportTicketService $tickets) {}

    public function index(): View
    {
        $query = SupportTicket::query()->with('school')->latest();
        if (! $this->access->canManageSupport(auth()->user())) {
            $query->where('school_id', $this->access->activeSchoolId(auth()->user()));
        }

        return view('saas-ops.support-tickets.index', ['tickets' => $query->paginate(20)]);
    }

    public function create(): View
    {
        return view('saas-ops.support-tickets.create', ['schools' => School::query()->orderBy('name')->get()]);
    }

    public function store(StoreSupportTicketRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['school_id'] = $data['school_id'] ?? $this->access->activeSchoolId($request->user());
        $ticket = $this->tickets->createTicket($data, $request->user());

        return redirect()->route('saas-ops.support-tickets.show', $ticket)->with('success', 'Ticket dibuat.');
    }

    public function show(SupportTicket $supportTicket): View
    {
        if ($supportTicket->school_id) {
            $this->access->assertCanAccessSchool(auth()->user(), (int) $supportTicket->school_id);
        }
        $supportTicket->load(['school', 'messages']);

        return view('saas-ops.support-tickets.show', ['ticket' => $supportTicket, 'users' => User::query()->orderBy('name')->get()]);
    }

    public function storeMessage(StoreSupportTicketMessageRequest $request, SupportTicket $ticket): RedirectResponse
    {
        $this->tickets->addMessage($ticket, $request->user(), $request->message, $request->visibility);

        return back()->with('success', 'Message ditambahkan.');
    }

    public function assign(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate(['user_id' => ['required', 'exists:users,id']]);
        $this->tickets->assign($ticket, User::query()->findOrFail($request->user_id), $request->user());

        return back()->with('success', 'Ticket diassign.');
    }

    public function resolve(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $this->tickets->resolve($ticket, $request->user());

        return back()->with('success', 'Ticket resolved.');
    }

    public function close(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $this->tickets->close($ticket, $request->user());

        return back()->with('success', 'Ticket closed.');
    }
}
