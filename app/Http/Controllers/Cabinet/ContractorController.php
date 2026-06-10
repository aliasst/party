<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Contractor;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    protected function checkAccess(Request $request, Event $event)
    {
        $user = $request->user();
        $cabinet = $request->attributes->get('cabinet');

        if ($user->isSuperAdmin()) return true;

        if ($event->cabinet_id != $cabinet->id) abort(404);

        $isCabinetAdmin = $cabinet->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();

        if (!$isCabinetAdmin) abort(403, 'У вас нет прав на управление подрядчиками.');

        return true;
    }

    public function index(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        $contractors = $event->contractors()->with('stage')->orderBy('created_at', 'desc')->paginate(10);
        return view('cabinet.events.contractors.index', compact('event', 'contractors'));
    }

    public function create(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        // Для select – получаем дерево этапов
        $stagesTree = $this->getStagesTree($event);
        return view('cabinet.events.contractors.create', compact('event', 'stagesTree'));
    }

    public function store(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);

        $request->validate([
            'name'       => 'required|string|max:255',
            'stage_id'   => 'nullable|exists:stages,id',
            'comment'    => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->only(['name', 'stage_id', 'comment', 'start_date', 'end_date']);
        $data['event_id'] = $event->id;

        Contractor::create($data);

        return redirect()->route('cabinet.events.contractors.index', $event)
            ->with('success', 'Подрядчик добавлен.');
    }

    public function edit(Request $request, Event $event, Contractor $contractor)
    {
        $this->checkAccess($request, $event);
        if ($contractor->event_id != $event->id) abort(404);
        $stagesTree = $this->getStagesTree($event);
        return view('cabinet.events.contractors.edit', compact('event', 'contractor', 'stagesTree'));
    }

    public function update(Request $request, Event $event, Contractor $contractor)
    {
        $this->checkAccess($request, $event);
        if ($contractor->event_id != $event->id) abort(404);

        $request->validate([
            'name'       => 'required|string|max:255',
            'stage_id'   => 'nullable|exists:stages,id',
            'comment'    => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $contractor->update($request->only(['name', 'stage_id', 'comment', 'start_date', 'end_date']));

        return redirect()->route('cabinet.events.contractors.index', $event)
            ->with('success', 'Подрядчик обновлён.');
    }

    public function destroy(Request $request, Event $event, Contractor $contractor)
    {
        $this->checkAccess($request, $event);
        if ($contractor->event_id != $event->id) abort(404);
        $contractor->delete();
        return redirect()->route('cabinet.events.contractors.index', $event)
            ->with('success', 'Подрядчик удалён.');
    }

    // Вспомогательный метод: строит дерево этапов для select (родители + дети с отступом)
    protected function getStagesTree(Event $event)
    {
        $stages = $event->stages()->with('children')->orderBy('sort_order')->get();
        $tree = [];
        foreach ($stages as $stage) {
            $tree[] = (object)['id' => $stage->id, 'name' => $stage->name, 'level' => 0];
            foreach ($stage->children as $child) {
                $tree[] = (object)['id' => $child->id, 'name' => '— ' . $child->name, 'level' => 1];
            }
        }
        return $tree;
    }
}
