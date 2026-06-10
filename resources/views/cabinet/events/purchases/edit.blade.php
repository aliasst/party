@extends('layouts.cabinet')

@section('title', 'Редактировать закупку')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.purchases.index', $event) }}">Вернуться к закупкам</a></div></div>
                </div>
                <div class="col-xs-12"><div class="cabinet-title-section"><h1 class="text-center">Редактировать закупку: {{ $purchase->name }}</h1></div></div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.events.purchases.update', [$event, $purchase]) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                @endif

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="text" name="name" value="{{ old('name', $purchase->name) }}" required placeholder="Название закупки *"></div></div></div>

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><textarea name="description" rows="3" placeholder="Описание">{{ old('description', $purchase->description) }}</textarea></div></div></div>

                                <div class="row mb-3"><div class="col-12"><div class="form-input">
                                            <select name="stage_id" class="form-select">
                                                <option value="">— Не привязан к этапу —</option>
                                                @foreach($stagesTree as $stageOption)
                                                    <option value="{{ $stageOption->id }}" style="padding-left: {{ $stageOption->level * 20 }}px;" {{ old('stage_id', $purchase->stage_id) == $stageOption->id ? 'selected' : '' }}>{{ $stageOption->name }}</option>
                                                @endforeach
                                            </select>
                                        </div></div></div>

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><textarea name="comment" rows="3" placeholder="Комментарий">{{ old('comment', $purchase->comment) }}</textarea></div></div></div>

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="date" name="purchase_date" value="{{ old('purchase_date', $purchase->purchase_date ? $purchase->purchase_date->format('Y-m-d') : '') }}" placeholder="Дата закупки"></div></div></div>

                                @if($purchase->file_path)
                                    <div class="row mb-2">
                                        <div class="col-12 text-center">
                                            <a href="{{ Storage::url($purchase->file_path) }}" target="_blank" class="btn-link" style="color: #fff;">Скачать текущий файл ({{ basename($purchase->file_path) }})</a>
                                        </div>
                                    </div>
                                @endif

                                <!-- Выбор нового файла -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="avatar-upload-label orange-btn orange-btn-small" style="display: inline-block; cursor: pointer;">
                                            Выбрать новый файл
                                            <input type="file" name="file" id="purchase-file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" style="display: none;">
                                        </label>
                                        <div id="file-name" class="avatar-help-text" style="margin-top: 8px; color: #fff; text-align: center;">
                                            @if($purchase->file_path) Текущий: {{ basename($purchase->file_path) }} @else Файл не выбран @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row"><div class="col-12"><button type="submit" class="orange-btn orange-btn-small">Сохранить</button></div></div>
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
        document.getElementById('purchase-file').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'Файл не выбран';
            document.getElementById('file-name').innerText = fileName;
        });
    </script>
@endpush
