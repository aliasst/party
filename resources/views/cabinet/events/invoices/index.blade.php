@extends('layouts.cabinet')

@section('title', 'Счета мероприятия ' . $event->title)

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.show', $event) }}">Вернуться к мероприятию</a></div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Счета мероприятия: {{ $event->title }}</h1>
                        <div class="title-link"><a href="{{ route('cabinet.events.invoices.create', $event) }}">Добавить счёт</a></div>
                    </div>

                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

                    <table class="requests">
                        <thead>
                        <tr>
                            <th>Номер счёта</th>
                            <th>Статус</th>
                            <th>Файл</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td aria-label="Номер">{{ $invoice->number }}</td>
                                <td aria-label="Статус">{!! $invoice->is_paid ? '<span style="color: green;">Оплачен</span>' : '<span style="color: red;">Не оплачен</span>' !!}</td>
                                <td aria-label="Файл">
                                    @if($invoice->file_path)
                                        <a href="{{ Storage::url($invoice->file_path) }}" target="_blank" class="btn-link">Скачать</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td aria-label="Дата">{{ $invoice->created_at->format('d.m.Y') }}</td>
                                <td class="req-act" aria-label="Действия">
                                    <a href="{{ route('cabinet.events.invoices.edit', [$event, $invoice]) }}" class="btn btn-sm btn-primary">✎</a>
                                    <form action="{{ route('cabinet.events.invoices.destroy', [$event, $invoice]) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить счёт?')">✖</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Нет счетов</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
