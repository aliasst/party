<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
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

        if (!$isCabinetAdmin) abort(403, 'У вас нет прав на управление закупками.');

        return true;
    }

    // Дерево этапов для select
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

    public function index(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        $purchases = $event->purchases()->with('stage')->orderBy('created_at', 'desc')->paginate(10);
        return view('cabinet.events.purchases.index', compact('event', 'purchases'));
    }

    public function create(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        $stagesTree = $this->getStagesTree($event);
        return view('cabinet.events.purchases.create', compact('event', 'stagesTree'));
    }

    public function store(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);

        $request->validate([
            'name'          => 'required|string|max:255',
            'stage_id'      => 'nullable|exists:stages,id',
            'description'   => 'nullable|string',
            'comment'       => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'file'          => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx,xls,xlsx|max:5120',
        ]);

        $data = $request->only(['name', 'stage_id', 'description', 'comment', 'purchase_date']);
        $data['event_id'] = $event->id;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('purchases/' . $event->id, 'public');
            $data['file_path'] = $path;
        }

        Purchase::create($data);

        return redirect()->route('cabinet.events.purchases.index', $event)
            ->with('success', 'Закупка добавлена.');
    }

    public function edit(Request $request, Event $event, Purchase $purchase)
    {
        $this->checkAccess($request, $event);
        if ($purchase->event_id != $event->id) abort(404);
        $stagesTree = $this->getStagesTree($event);
        return view('cabinet.events.purchases.edit', compact('event', 'purchase', 'stagesTree'));
    }

    public function update(Request $request, Event $event, Purchase $purchase)
    {
        $this->checkAccess($request, $event);
        if ($purchase->event_id != $event->id) abort(404);

        $request->validate([
            'name'          => 'required|string|max:255',
            'stage_id'      => 'nullable|exists:stages,id',
            'description'   => 'nullable|string',
            'comment'       => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'file'          => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx,xls,xlsx|max:5120',
        ]);

        $data = $request->only(['name', 'stage_id', 'description', 'comment', 'purchase_date']);

        if ($request->hasFile('file')) {
            if ($purchase->file_path && Storage::disk('public')->exists($purchase->file_path)) {
                Storage::disk('public')->delete($purchase->file_path);
            }
            $path = $request->file('file')->store('purchases/' . $event->id, 'public');
            $data['file_path'] = $path;
        }

        $purchase->update($data);

        return redirect()->route('cabinet.events.purchases.index', $event)
            ->with('success', 'Закупка обновлена.');
    }

    public function destroy(Request $request, Event $event, Purchase $purchase)
    {
        $this->checkAccess($request, $event);
        if ($purchase->event_id != $event->id) abort(404);
        $purchase->delete();
        return redirect()->route('cabinet.events.purchases.index', $event)
            ->with('success', 'Закупка удалена.');
    }
}
