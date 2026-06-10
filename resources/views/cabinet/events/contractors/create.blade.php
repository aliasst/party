@extends('layouts.cabinet')

@section('title', 'Добавить подрядчика')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.events.contractors.index', $event) }}">Вернуться к подрядчикам</a></div></div>
                </div>
                <div class="col-xs-12"><div class="cabinet-title-section"><h1 class="text-center">Добавить подрядчика для мероприятия: {{ $event->title }}</h1></div></div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.events.contractors.store', $event) }}">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input"><input type="text" name="name" value="{{ old('name') }}" required placeholder="Название подрядчика *"></div></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input">
                                            <select name="stage_id" class="form-select">
                                                <option value="">— Не привязан к этапу —</option>
                                                @foreach($stagesTree as $stageOption)
                                                    <option value="{{ $stageOption->id }}" style="padding-left: {{ $stageOption->level * 20 }}px;" {{ old('stage_id') == $stageOption->id ? 'selected' : '' }}>{{ $stageOption->name }}</option>
                                                @endforeach
                                            </select>
                                        </div></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input"><textarea name="comment" rows="4" placeholder="Комментарий">{{ old('comment') }}</textarea></div></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input"><input type="date" name="start_date" value="{{ old('start_date') }}" placeholder="Дата начала работ"></div></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input"><input type="date" name="end_date" value="{{ old('end_date') }}" placeholder="Дата окончания работ"></div></div>
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
