@extends('layouts.cabinet')

@section('title', 'Заказчики')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.dashboard') }}">Вернуться назад</a></div></div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Заказчики</h1>
                        <div class="title-link"><a href="{{ route('cabinet.clients.create') }}">Добавить заказчика</a></div>
                    </div>

                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                    <table class="requests">
                        <thead>
                        <tr><th>Название</th><th>Юр. лицо</th><th>E-mail</th><th>Телефон</th><th>Действия</th></tr>
                        </thead>
                        <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td aria-label="Название">{{ $client->name }}</td>
                                <td aria-label="Юр. лицо">{{ $client->legal_name ?? '—' }}</td>
                                <td aria-label="E-mail">{{ $client->email ?? '—' }}</td>
                                <td aria-label="Телефон">{{ $client->phone ?? '—' }}</td>
                                <td class="req-act">
                                    <a href="{{ route('cabinet.clients.edit', $client) }}" class="btn btn-sm btn-primary">✎</a>
                                    <form action="{{ route('cabinet.clients.destroy', $client) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить заказчика?')">✖</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Нет заказчиков</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
