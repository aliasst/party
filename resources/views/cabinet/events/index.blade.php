@extends('layouts.cabinet')

@section('title', 'Мероприятия')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.dashboard') }}">Вернуться назад</a></div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Мероприятия</h1>
                        <div class="title-link"><a href="{{ route('cabinet.events.create') }}">Создать мероприятие</a></div>
                    </div>

                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

                    <div class="events-row">

                        @foreach($events as $event)

                        <div class="evant-item">
                            <div class="event-card">
                                <div class="evint-prew">
                                    @php
                                        $coverUrl = $event->cover && Storage::disk('public')->exists($event->cover)
                                            ? Storage::url($event->cover)
                                            : asset('img/default-cover.png');
                                    @endphp

                                    <img src="{{ $coverUrl }}" alt="Обложка мероприятия" class="img-fluid rounded">
                                </div>
                                <div class="event-content">
                                    <div class="event-title event-title-y">{{ $event->title }}</div>
                                    <div class="event-meta">
                                        <div class="event-meta-row">
                                            <div class="event-meta-item event-date">
                                                <span>Дата события:</span>
                                                <span>{{ $event->start_date->format('d.m.Y') }}</span>
                                            </div>
                                            <div class="event-meta-item event-point">
                                                <span>Адрес:</span>
                                                <span>Санкт-Петербург, БЦ "Алые Паруса"</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="event-actions">
                                <a href="{{ route('cabinet.events.edit', $event) }}" type="button" class="blue-btn blue-btn-small">
                                    Подробнее
                                </a>
                                <a href="{{ route('cabinet.events.edit', $event) }}" type="button" class="blue-btn-rev blue-btn-small">
                                    Смотреть ТЗ заказчика
                                </a>
                                <a href="{{ route('cabinet.events.show', $event) }}" type="button" class="blue-btn-rev blue-btn-small">
                                    Смотреть текущее наполнение!
                                </a>
                            </div>
                        </div>


                        @endforeach









                    </div>


{{--                    <table class="requests">--}}
{{--                        <thead>--}}
{{--                        <tr><th>Название</th><th>Дата начала</th><th>Дата окончания</th><th>Статус</th><th>Действия</th></tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($events as $event)--}}
{{--                            <tr>--}}
{{--                                <td aria-label="Название"><a class="btn-link" href="{{ route('cabinet.events.show', $event) }}">{{ $event->title }}</a></td>--}}
{{--                                <td aria-label="Дата начала">{{ $event->start_date->format('d.m.Y') }}</td>--}}
{{--                                <td aria-label="Дата окончания">{{ $event->end_date ? $event->end_date->format('d.m.Y') : '—' }}</td>--}}
{{--                                <td aria-label="Статус">{{ $event->status == 'future' ? 'Будущее' : 'Архив' }}</td>--}}
{{--                                <td class="req-act" aria-label="Действия">--}}
{{--                                    <a href="{{ route('cabinet.events.edit', $event) }}" class="btn btn-sm btn-primary">✎</a>--}}
{{--                                    <form action="{{ route('cabinet.events.destroy', $event) }}" method="POST" style="display:inline-block;">--}}
{{--                                        @csrf @method('DELETE')--}}
{{--                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить мероприятие?')">✖</button>--}}
{{--                                    </form>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
