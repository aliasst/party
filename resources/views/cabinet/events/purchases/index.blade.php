@extends('layouts.cabinet')

@section('title', 'Закупки мероприятия ' . $event->title)

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.show', $event) }}">Вернуться к мероприятию</a></div></div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Закупки мероприятия: {{ $event->title }}</h1>
                        <div class="title-link"><a href="{{ route('cabinet.events.purchases.create', $event) }}">Добавить закупку</a></div>
                    </div>

                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                    <table class="requests">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Этап</th>
                            <th>Дата закупки</th>
                            <th>Файл</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td aria-label="Название">{{ $purchase->name }}</td>
                                <td aria-label="Этап">{{ $purchase->stage ? $purchase->stage->name : '—' }}</td>
                                <td aria-label="Дата закупки">{{ $purchase->purchase_date ? $purchase->purchase_date->format('d.m.Y') : '-' }}</td>
                                <td aria-label="Файл">
                                    @if($purchase->file_path)
                                        <a href="{{ Storage::url($purchase->file_path) }}" target="_blank" class="btn-link">Скачать</a>
                                    @else —
                                    @endif
                                </td>
                                <td class="req-act">
                                    <a href="{{ route('cabinet.events.purchases.edit', [$event, $purchase]) }}" class="btn btn-sm btn-primary">✎</a>
                                    <form action="{{ route('cabinet.events.purchases.destroy', [$event, $purchase]) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить закупку?')">✖</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Нет закупок</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $purchases->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
