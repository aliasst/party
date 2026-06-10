@extends('layouts.cabinet')

@section('title', 'Подрядчики мероприятия ' . $event->title)

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.show', $event) }}">Вернуться к мероприятию</a></div></div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Подрядчики мероприятия: {{ $event->title }}</h1>
                        <div class="title-link"><a href="{{ route('cabinet.events.contractors.create', $event) }}">Добавить подрядчика</a></div>
                    </div>

                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                    <table class="requests">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Этап</th>
                            <th>Дата начала</th>
                            <th>Дата окончания</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($contractors as $contractor)
                            <tr>
                                <td aria-label="Название">{{ $contractor->name }}</td>
                                <td aria-label="Этап">{{ $contractor->stage ? $contractor->stage->name : '—' }}</td>
                                <td aria-label="Дата начала">{{ $contractor->start_date ? $contractor->start_date->format('d.m.Y') : '-' }}</td>
                                <td aria-label="Дата окончания">{{ $contractor->end_date ? $contractor->end_date->format('d.m.Y') : '-' }}</td>
                                <td class="req-act">
                                    <a href="{{ route('cabinet.events.contractors.edit', [$event, $contractor]) }}" class="btn btn-sm btn-primary">✎</a>
                                    <form action="{{ route('cabinet.events.contractors.destroy', [$event, $contractor]) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить подрядчика?')">✖</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Нет подрядчиков</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $contractors->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
