<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Stage;
use Illuminate\Http\Request;

class ReportController extends Controller
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

        if (!$isCabinetAdmin) abort(403, 'У вас нет прав на просмотр отчётов.');

        return true;
    }

    // Список этапов (таблицы)
    public function index(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        $stages = $event->stages; // корневые этапы
        return view('cabinet.events.reports.index', compact('event', 'stages'));
    }

    // Просмотр одного этапа (read-only)
    public function show(Request $request, Event $event, Stage $stage)
    {
        $this->checkAccess($request, $event);
        if ($stage->event_id != $event->id) abort(404);
        return view('cabinet.events.reports.show', compact('event', 'stage'));
    }
}
