@extends('layouts.cabinet')

@section('title', 'Отчёт: ' . $stage->name)

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.reports.index', $event) }}">Вернуться к списку отчётов</a></div></div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1 class="text-center">Отчёт по этапу: {{ $stage->name }}</h1>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <!-- Поля только для чтения, стилизованы как инпуты (белый шрифт, прозрачный фон) -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" style="color:#fff; margin-bottom:5px;">Название этапа</label>
                                    <div class="form-input readonly">
                                        <input type="text" value="{{ $stage->name }}" readonly disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" style="color:#fff; margin-bottom:5px;">Дата старта</label>
                                    <div class="form-input readonly">
                                        <input type="text" value="{{ $stage->start_date ? $stage->start_date->format('d.m.Y') : '-' }}" readonly disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" style="color:#fff; margin-bottom:5px;">Дата завершения</label>
                                    <div class="form-input readonly">
                                        <input type="text" value="{{ $stage->end_date ? $stage->end_date->format('d.m.Y') : '-' }}" readonly disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" style="color:#fff; margin-bottom:5px;">Статус</label>
                                    <div class="form-input readonly">
                                        <input type="text" value="@switch($stage->status) @case('planned') Планируется @break @case('in_progress') В работе @break @case('completed') Завершён @break @endswitch" readonly disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" style="color:#fff; margin-bottom:5px;">Комментарий</label>
                                    <div class="form-input readonly">
                                        <textarea rows="5" readonly disabled>{{ $stage->comment ?? '—' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" style="color:#fff; margin-bottom:5px;">Прикреплённые файлы</label>
                                    @if($stage->files->count())
                                        <ul style="list-style: none; padding-left: 0; margin-top: 10px;">
                                            @foreach($stage->files as $file)
                                                <li style="margin-bottom: 8px;">
                                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn-link" style="color: #fff; text-decoration: underline;">{{ $file->original_name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="avatar-help-text" style="color: #fff;">Файлы не прикреплены</div>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <a href="{{ route('cabinet.events.reports.index', $event) }}" class="orange-btn orange-btn-small" style="display: inline-block;">Назад к списку</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
