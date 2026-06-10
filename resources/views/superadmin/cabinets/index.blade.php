@extends('layouts.cabinet')

@section('title', 'Управление кабинетами')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link">
                            <a class="btn-link btn-backlink" href="{{ route('cabinet.dashboard') }}">Вернуться назад</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Все кабинеты</h1>
                        <div class="title-link">
                            <a href="{{ route('superadmin.cabinets.create') }}">Создать кабинет</a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="requests">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название кабинета</th>
                            <th>Администраторов</th>
                            <th>Заказчиков</th>
                            <th>Мероприятий</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cabinets as $cabinet)
                            <tr>
                                <td aria-label="ID">{{ $cabinet->id }}</td>
                                <td aria-label="Название">{{ $cabinet->name }}</td>
                                <td aria-label="Администраторов">{{ $cabinet->users_count ?? 0 }}</td>
                                <td aria-label="Заказчиков">{{ $cabinet->clients_count ?? 0 }}</td>
                                <td aria-label="Мероприятий">{{ $cabinet->events_count ?? 0 }}</td>
                                <td class="req-act" aria-label="Действия">
                                    <a href="{{ route('superadmin.cabinets.show', $cabinet) }}" class="btn btn-sm btn-primary" style="font-size:22px">👁</a>
                                    <a href="{{ route('superadmin.cabinets.edit', $cabinet) }}" class="btn btn-sm btn-warning">✎</a>
                                    <form action="{{ route('superadmin.cabinets.destroy', $cabinet) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить кабинет? Все данные будут удалены.')">✖</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $cabinets->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
