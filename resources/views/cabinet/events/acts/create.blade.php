@extends('layouts.cabinet')

@section('title', 'Добавить акт')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.acts.index', $event) }}">Вернуться к актам</a></div></div>
                </div>
                <div class="col-xs-12"><div class="cabinet-title-section"><h1 class="text-center">Добавить акт для мероприятия: {{ $event->title }}</h1></div></div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.events.acts.store', $event) }}" enctype="multipart/form-data">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input"><input type="text" name="number" value="{{ old('number') }}" required placeholder="Номер акта *"></div></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input">
                                            <select name="status" class="form-select">
                                                <option value="added" {{ old('status') == 'added' ? 'selected' : '' }}>Добавлен</option>
                                                <option value="needs_signature" {{ old('status') == 'needs_signature' ? 'selected' : '' }}>Требуется подпись</option>
                                                <option value="signed" {{ old('status') == 'signed' ? 'selected' : '' }}>Подписан сторонами</option>
                                            </select>
                                        </div></div>
                                </div>

                                <!-- Блок выбора файла -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="avatar-upload-label orange-btn orange-btn-small" style="display: inline-block; cursor: pointer;">
                                            Выбрать файл
                                            <input type="file" name="file" id="act-file" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                                        </label>
                                        <div id="file-name" class="avatar-help-text" style="margin-top: 8px; color: #fff; text-align: center;">Файл не выбран</div>
                                    </div>
                                </div>

                                <div class="row"><div class="col-12"><button type="submit" class="orange-btn orange-btn-small">Добавить</button></div></div>
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
        document.getElementById('act-file').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'Файл не выбран';
            document.getElementById('file-name').innerText = fileName;
        });
    </script>
@endpush
