<?php

namespace App\Http\Middleware;

use App\Models\Cabinet;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCabinetContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, $next)
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            // Суперадмин может переключать кабинеты; ожидаем параметр cabinet_id в запросе или сессии
            $cabinetId = $request->route('cabinet_id') ?? session('cabinet_id');
            if ($cabinetId) {
                $cabinet = Cabinet::findOrFail($cabinetId);
                session(['cabinet_id' => $cabinet->id]);
                $request->attributes->set('cabinet', $cabinet);
            } else {
                // Если суперадмин не выбрал кабинет – редирект на список кабинетов
                return redirect()->route('superadmin.cabinets.index');
            }
        } else {
            // Обычный пользователь: определяем его кабинет (предполагаем, что он привязан только к одному)
            $cabinet = $user->cabinets()->first();
            if (!$cabinet) {
                abort(403, 'У вас нет доступа ни к одному кабинету.');
            }
            session(['cabinet_id' => $cabinet->id]);
            $request->attributes->set('cabinet', $cabinet);
        }
        return $next($request);
    }
}
