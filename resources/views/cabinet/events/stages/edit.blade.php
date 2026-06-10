@extends('layouts.cabinet')

@section('title', 'Редактировать этап')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.stages.index', $event) }}">Вернуться к этапам</a></div></div>
                </div>
                <div class="col-xs-12"><div class="cabinet-title-section"><h1 class="text-center">Редактировать этап: {{ $stage->name }}</h1></div></div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.events.stages.update', [$event, $stage]) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                @endif

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="text" name="name" value="{{ old('name', $stage->name) }}" required placeholder="Название этапа *"></div></div></div>

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="date" name="start_date" value="{{ old('start_date', $stage->start_date ? $stage->start_date->format('Y-m-d') : '') }}"></div></div></div>

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="date" name="end_date" value="{{ old('end_date', $stage->end_date ? $stage->end_date->format('Y-m-d') : '') }}"></div></div></div>

                                <div class="row mb-3"><div class="col-12"><div class="form-input">
                                            <select name="status" class="form-select">
                                                <option value="planned" {{ old('status', $stage->status) == 'planned' ? 'selected' : '' }}>Планируется</option>
                                                <option value="in_progress" {{ old('status', $stage->status) == 'in_progress' ? 'selected' : '' }}>В работе</option>
                                                <option value="completed" {{ old('status', $stage->status) == 'completed' ? 'selected' : '' }}>Завершён</option>
                                            </select>
                                        </div></div></div>

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><textarea name="comment" rows="4" placeholder="Комментарий">{{ old('comment', $stage->comment) }}</textarea></div></div></div>

                                @if($stage->files->count())
                                    <div class="row mb-2">
                                        <div class="col-12"><strong>Прикреплённые файлы:</strong>
                                            @foreach($stage->files as $file)
                                                <div><a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ $file->original_name }}</a>
                                                    <form action="{{ route('cabinet.events.stages.deleteFile', [$event, $stage, $file]) }}" method="POST" style="display:inline-block;">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn-link" onclick="return confirm('Удалить файл?')">[x]</button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="row mb-3"><div class="col-12">
                                        <label class="avatar-upload-label orange-btn orange-btn-small" style="display: inline-block; cursor: pointer;">Добавить файлы
                                            <input type="file" name="files[]" multiple style="display: none;" onchange="updateFileNames(this)">
                                        </label>
                                        <div id="file-list" class="avatar-help-text" style="margin-top: 8px; color: #fff;text-align: center">Файлы не выбраны</div>
                                    </div></div>

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
        function updateFileNames(input) {
            let fileList = '';
            for (let i = 0; i < input.files.length; i++) {
                fileList += (i > 0 ? ', ' : '') + input.files[i].name;
            }
            document.getElementById('file-list').innerText = fileList || 'Файлы не выбраны';
        }
    </script>
@endpush
