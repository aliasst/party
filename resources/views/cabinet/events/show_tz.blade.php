@extends('layouts.cabinet')

@section('title', 'ТЗ заказчика: ' . $event->title)

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.index') }}">Вернуться назад</a></div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1 class="text-center">Техническое задание заказчика</h1>
                    </div>
                </div>
                <div class="col-xs-12 col-md-10">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" style="color:#fff;">Мероприятие</label>
                                    <div class="form-input readonly">
                                        <input type="text" value="{{ $event->title }}" readonly disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" style="color:#fff;">Техническое задание (ТЗ)</label>
                                    <div class="form-input readonly">
                                        <textarea rows="15" readonly disabled style="white-space: pre-wrap;">{{ $event->description ?? '—' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a href="{{ route('cabinet.events.show', $event) }}" class="orange-btn orange-btn-small">Назад к мероприятию</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
