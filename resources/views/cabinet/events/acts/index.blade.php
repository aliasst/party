@extends('layouts.cabinet')

@section('title', 'Акты мероприятия ' . $event->title)

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.show', $event) }}">Вернуться к мероприятию</a></div></div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Акты мероприятия: {{ $event->title }}</h1>
                        <div class="title-link"><a href="{{ route('cabinet.events.acts.create', $event) }}">Добавить акт</a></div>
                    </div>

                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                    <table class="requests">
                        <thead>
                        <tr><th>Номер</th><th>Статус</th><th>Файл</th><th>Дата создания</th><th>Действия</th></tr>
                        </thead>
                        <tbody>
                        @forelse($acts as $act)
                            <tr>
                                <td aria-label="Номер">{{ $act->number }}</td>
                                <td aria-label="Статус">
                                    @switch($act->status)
                                        @case('added') Добавлен @break
                                        @case('needs_signature') Требуется подпись @break
                                        @case('signed') Подписан сторонами @break
                                    @endswitch
                                </td>
                                <td aria-label="Файл">
                                    @if($act->file_path) <a href="{{ Storage::url($act->file_path) }}" target="_blank" class="btn-link">Скачать</a> @else — @endif
                                </td>
                                <td aria-label="Дата">{{ $act->created_at->format('d.m.Y') }}</td>
                                <td class="req-act">
                                    <a href="{{ route('cabinet.events.acts.edit', [$event, $act]) }}" class="btn btn-sm btn-primary">✎</a>
                                    <form action="{{ route('cabinet.events.acts.destroy', [$event, $act]) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить акт?')">✖</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Нет актов</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $acts->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
