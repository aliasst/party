<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Act;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActController extends Controller
{
    protected function checkAccess(Request $request, Event $event)
    {
        $user = $request->user();
        $cabinet = $request->attributes->get('cabinet');

        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($event->cabinet_id != $cabinet->id) {
            abort(404);
        }

        $isCabinetAdmin = $cabinet->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();

        if (!$isCabinetAdmin) {
            abort(403, 'У вас нет прав на управление актами.');
        }

        return true;
    }

    public function index(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        $acts = $event->acts()->orderBy('created_at', 'desc')->paginate(10);
        return view('cabinet.events.acts.index', compact('event', 'acts'));
    }

    public function create(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        return view('cabinet.events.acts.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);

        $request->validate([
            'number'   => 'required|string|max:255',
            'status'   => 'required|in:added,needs_signature,signed',
            'file'     => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only(['number', 'status']);
        $data['event_id'] = $event->id;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('acts', 'public');
            $data['file_path'] = $path;
        }

        Act::create($data);

        return redirect()->route('cabinet.events.acts.index', $event)
            ->with('success', 'Акт успешно добавлен.');
    }

    public function edit(Request $request, Event $event, Act $act)
    {
        $this->checkAccess($request, $event);
        if ($act->event_id != $event->id) abort(404);
        return view('cabinet.events.acts.edit', compact('event', 'act'));
    }

    public function update(Request $request, Event $event, Act $act)
    {
        $this->checkAccess($request, $event);
        if ($act->event_id != $event->id) abort(404);

        $request->validate([
            'number'   => 'required|string|max:255',
            'status'   => 'required|in:added,needs_signature,signed',
            'file'     => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only(['number', 'status']);

        if ($request->hasFile('file')) {
            if ($act->file_path && Storage::disk('public')->exists($act->file_path)) {
                Storage::disk('public')->delete($act->file_path);
            }
            $path = $request->file('file')->store('acts', 'public');
            $data['file_path'] = $path;
        }

        $act->update($data);

        return redirect()->route('cabinet.events.acts.index', $event)
            ->with('success', 'Акт обновлён.');
    }

    public function destroy(Request $request, Event $event, Act $act)
    {
        $this->checkAccess($request, $event);
        if ($act->event_id != $event->id) abort(404);

        $act->delete();

        return redirect()->route('cabinet.events.acts.index', $event)
            ->with('success', 'Акт удалён.');
    }
}
