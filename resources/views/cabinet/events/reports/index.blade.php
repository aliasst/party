@extends('layouts.cabinet')

@section('title', 'Отчёты по мероприятию ' . $event->title)

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.show', $event) }}">Вернуться к мероприятию</a></div></div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1>Отчёты по мероприятию: {{ $event->title }}</h1>
                    </div>

                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                    @foreach($stages as $parent)
                        <div class="stage-table-wrap">
                            <table class="stage-table">
                                <thead>
                                <tr><th>Название этапа</th><th>Дата старта</th><th>Дата завершения</th><th>Статус</th><th>Действия</th></tr>
                                </thead>
                                <tbody>
                                <tr class="parent-tr">
                                    <td aria-label="Название этапа"><a class="btn-link" href="{{ route('cabinet.events.reports.show', [$event, $parent]) }}">{{ $parent->name }}</a></td>
                                    <td aria-label="Дата старта">{{ $parent->start_date ? $parent->start_date->format('d.m.Y') : '-' }}</td>
                                    <td aria-label="Дата завершения">{{ $parent->end_date ? $parent->end_date->format('d.m.Y') : '-' }}</td>
                                    <td aria-label="Статус">
                                        @switch($parent->status)
                                            @case('planned') Планируется @break
                                            @case('in_progress') В работе @break
                                            @case('completed') Завершён @break
                                        @endswitch
                                    </td>
                                    <td aria-label="Действия">
                                        <a href="{{ route('cabinet.events.reports.show', [$event, $parent]) }}" class="btn btn-sm btn-primary">👁</a>
                                    </td>
                                </tr>
                                @foreach($parent->children as $child)
                                    <tr class="child-tr">
                                        <td style="padding-left:50px;" aria-label="Название этапа"><a class="btn-link" href="{{ route('cabinet.events.reports.show', [$event, $child]) }}">{{ $child->name }}</a></td>
                                        <td aria-label="Дата старта">{{ $child->start_date ? $child->start_date->format('d.m.Y') : '-' }}</td>
                                        <td aria-label="Дата завершения">{{ $child->end_date ? $child->end_date->format('d.m.Y') : '-' }}</td>
                                        <td aria-label="Статус">
                                            @switch($child->status)
                                                @case('planned') Планируется @break
                                                @case('in_progress') В работе @break
                                                @case('completed') Завершён @break
                                            @endswitch
                                        </td>
                                        <td aria-label="Действия">
                                            <a href="{{ route('cabinet.events.reports.show', [$event, $child]) }}" class="btn btn-sm btn-primary">👁</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
