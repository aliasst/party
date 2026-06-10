@extends('layouts.cabinet')

@section('title', 'Пользователи кабинета')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.dashboard') }}">Вернуться назад</a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Пользователи кабинета</h1>
                        <div class="title-link"><a href="{{ route('cabinet.admins.create') }}">Добавить пользователя</a></div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <table class="requests">
                        <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Дата создания</th>
                            <th>E-mail</th>
                            <th>Роль</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td aria-label="Имя"><a class="btn-link" href="{{ route('cabinet.admins.edit', $user) }}">{{ $user->name }}</a></td>
                                <td aria-label="Дата создания">{{ $user->created_at->format('d.m.Y') }}</td>
                                <td aria-label="E-mail">{{ $user->email }}</td>
                                <td aria-label="Роль">{{ $user->pivot->role == 'admin' ? 'Администратор' : 'Пользователь' }}</td>
                                <td class="req-act" aria-label="Действия">
                                    <a href="{{ route('cabinet.admins.edit', $user) }}" class="btn btn-sm btn-primary">✎</a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('cabinet.admins.destroy', $user) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пользователя из кабинета?')">✖</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
