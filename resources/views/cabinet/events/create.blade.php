@extends('layouts.cabinet')

@section('title', 'Создать мероприятие')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link">
                            <a class="btn-link btn-backlink" href="{{ route('cabinet.events.index') }}">Вернуться назад</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1 class="text-center">Создать мероприятие</h1>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.events.store') }}" enctype="multipart/form-data">
                                @csrf

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                    </div>
                                @endif

                                <!-- Название -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Название мероприятия *">
                                        </div>
                                    </div>
                                </div>

                                <!-- Дата начала -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="date" name="start_date" value="{{ old('start_date') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Дата окончания -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="date" name="end_date" value="{{ old('end_date') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- ТЗ (описание) -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <textarea name="description" rows="5" placeholder="Техническое задание (ТЗ)">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Стилизованный выбор обложки с предпросмотром -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="avatar-upload-wrapper">
                                            <div class="avatar-preview">
                                                <div class="avatar-preview-image" id="cover-preview">
                                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f0f0f0;color:#666;">Обложка</div>
                                                </div>
                                                <label for="cover-upload" class="avatar-upload-label orange-btn orange-btn-small">Выбрать обложку</label>
                                                <input type="file" name="cover" id="cover-upload" accept="image/jpeg,image/png,image/jpg" style="display: none;">
                                            </div>
                                            <div class="avatar-help-text">Допустимые форматы: JPG, PNG. Максимум 2 МБ.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="orange-btn orange-btn-small">Создать</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#cover-upload').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('#cover-preview').html('<img src="' + event.target.result + '" alt="preview">');
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Если файл не выбран, возвращаем заглушку
                    $('#cover-preview').html('<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f0f0f0;color:#666;">Обложка</div>');
                }
            });
        });
    </script>
@endpush
