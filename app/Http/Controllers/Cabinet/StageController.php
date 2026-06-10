<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Stage;
use App\Models\StageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StageController extends Controller
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

        if (!$isCabinetAdmin) abort(403, 'У вас нет прав на управление этапами.');

        return true;
    }

    // Отображение дерева этапов
    public function index(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        $stages = $event->stages; // корневые этапы, уже с детьми через relation children
        return view('cabinet.events.stages.index', compact('event', 'stages'));
    }

    // Форма создания этапа (опционально parent_id)
    public function create(Request $request, Event $event, $parentId = null)
    {
        $this->checkAccess($request, $event);
        $parent = null;
        if ($parentId) {
            $parent = Stage::where('event_id', $event->id)->findOrFail($parentId);
        }
        return view('cabinet.events.stages.create', compact('event', 'parent'));
    }

    // Сохранение этапа (и родительского, и дочернего)
    public function store(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);

        $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:stages,id',
            'start_date'=> 'nullable|date',
            'end_date'  => 'nullable|date|after_or_equal:start_date',
            'status'    => 'required|in:planned,in_progress,completed',
            'comment'   => 'nullable|string',
        ]);

        $data = $request->only(['name', 'parent_id', 'start_date', 'end_date', 'status', 'comment']);
        $data['event_id'] = $event->id;

        // Определяем sort_order
        if ($data['parent_id']) {
            $parent = Stage::find($data['parent_id']);
            $maxSort = $parent->children()->max('sort_order') ?? 0;
            $data['sort_order'] = $maxSort + 1;
        } else {
            $maxSort = $event->stages()->max('sort_order') ?? 0;
            $data['sort_order'] = $maxSort + 1;
        }

        $stage = Stage::create($data);

        // Обработка файлов
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('stages/' . $event->id . '/' . $stage->id, 'public');
                StageFile::create([
                    'stage_id'      => $stage->id,
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        $redirectRoute = $stage->parent_id ? route('cabinet.events.stages.index', $event) : route('cabinet.events.stages.index', $event);
        return redirect($redirectRoute)->with('success', 'Этап создан');
    }

    // Форма редактирования
    public function edit(Request $request, Event $event, Stage $stage)
    {
        $this->checkAccess($request, $event);
        if ($stage->event_id != $event->id) abort(404);
        return view('cabinet.events.stages.edit', compact('event', 'stage'));
    }

    // Обновление этапа
    public function update(Request $request, Event $event, Stage $stage)
    {
        $this->checkAccess($request, $event);
        if ($stage->event_id != $event->id) abort(404);

        $request->validate([
            'name'      => 'required|string|max:255',
            'start_date'=> 'nullable|date',
            'end_date'  => 'nullable|date|after_or_equal:start_date',
            'status'    => 'required|in:planned,in_progress,completed',
            'comment'   => 'nullable|string',
        ]);

        $stage->update($request->only(['name', 'start_date', 'end_date', 'status', 'comment']));

        // Добавление новых файлов (без удаления старых)
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('stages/' . $event->id . '/' . $stage->id, 'public');
                StageFile::create([
                    'stage_id'      => $stage->id,
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('cabinet.events.stages.index', $event)->with('success', 'Этап обновлён');
    }

    // Удаление этапа (каскадно удалит подэтапы и файлы через внешние ключи)
    public function destroy(Request $request, Event $event, Stage $stage)
    {
        $this->checkAccess($request, $event);
        if ($stage->event_id != $event->id) abort(404);
        $stage->delete();
        return redirect()->route('cabinet.events.stages.index', $event)->with('success', 'Этап удалён');
    }

    // Добавление файлов к существующему этапу (отдельный маршрут)
    public function attachFiles(Request $request, Event $event, Stage $stage)
    {
        $this->checkAccess($request, $event);
        if ($stage->event_id != $event->id) abort(404);

        $request->validate([
            'files.*' => 'file|max:5120|mimes:pdf,jpeg,png,jpg,doc,docx,xls,xlsx',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('stages/' . $event->id . '/' . $stage->id, 'public');
                StageFile::create([
                    'stage_id'      => $stage->id,
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('success', 'Файлы добавлены');
    }

    // Удаление отдельного файла
    public function deleteFile(Request $request, Event $event, Stage $stage, StageFile $file)
    {
        $this->checkAccess($request, $event);
        if ($stage->event_id != $event->id || $file->stage_id != $stage->id) abort(404);
        $file->delete();
        return back()->with('success', 'Файл удалён');
    }
}
