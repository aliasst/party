<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    // Проверка прав доступа (админ кабинета или суперадмин)
    protected function checkAccess(Request $request, Event $event)
    {
        $user = $request->user();
        $cabinet = $request->attributes->get('cabinet');

        if ($user->isSuperAdmin()) {
            return true;
        }

        // Проверяем, что событие принадлежит текущему кабинету
        if ($event->cabinet_id != $cabinet->id) {
            abort(404);
        }

        // Проверяем, является ли пользователь админом кабинета
        $isCabinetAdmin = $cabinet->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();

        if (!$isCabinetAdmin) {
            abort(403, 'У вас нет прав на управление счетами.');
        }

        return true;
    }

    // Список счетов мероприятия
    public function index(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        $invoices = $event->invoices()->orderBy('created_at', 'desc')->paginate(10);
        return view('cabinet.events.invoices.index', compact('event', 'invoices'));
    }

    // Форма создания счёта
    public function create(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);
        return view('cabinet.events.invoices.create', compact('event'));
    }

    // Сохранение нового счёта
    public function store(Request $request, Event $event)
    {
        $this->checkAccess($request, $event);

        $request->validate([
            'number'   => 'required|string|max:255',
            'is_paid'  => 'boolean',
            'file'     => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120', // до 5 МБ
        ]);

        $data = $request->only(['number', 'is_paid']);
        $data['event_id'] = $event->id;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('invoices', 'public');
            $data['file_path'] = $path;
        }

        Invoice::create($data);

        return redirect()->route('cabinet.events.invoices.index', $event)
            ->with('success', 'Счёт успешно добавлен.');
    }

    // Форма редактирования счёта
    public function edit(Request $request, Event $event, Invoice $invoice)
    {
        $this->checkAccess($request, $event);
        // Доп. проверка, что счёт принадлежит мероприятию
        if ($invoice->event_id != $event->id) {
            abort(404);
        }
        return view('cabinet.events.invoices.edit', compact('event', 'invoice'));
    }

    // Обновление счёта
    public function update(Request $request, Event $event, Invoice $invoice)
    {
        $this->checkAccess($request, $event);
        if ($invoice->event_id != $event->id) {
            abort(404);
        }

        $request->validate([
            'number'   => 'required|string|max:255',
            'is_paid'  => 'boolean',
            'file'     => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only(['number', 'is_paid']);

        if ($request->hasFile('file')) {
            // Удаляем старый файл
            if ($invoice->file_path && Storage::disk('public')->exists($invoice->file_path)) {
                Storage::disk('public')->delete($invoice->file_path);
            }
            $path = $request->file('file')->store('invoices', 'public');
            $data['file_path'] = $path;
        }

        $invoice->update($data);

        return redirect()->route('cabinet.events.invoices.index', $event)
            ->with('success', 'Счёт обновлён.');
    }

    // Удаление счёта
    public function destroy(Request $request, Event $event, Invoice $invoice)
    {
        $this->checkAccess($request, $event);
        if ($invoice->event_id != $event->id) {
            abort(404);
        }

        $invoice->delete();

        return redirect()->route('cabinet.events.invoices.index', $event)
            ->with('success', 'Счёт удалён.');
    }
}
