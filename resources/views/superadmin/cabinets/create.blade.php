@extends('layouts.cabinet')

@section('title', 'Создать кабинет')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link">
                            <a class="btn-link btn-backlink" href="{{ route('superadmin.cabinets.index') }}">Вернуться назад</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1>Создать кабинет</h1>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('superadmin.cabinets.store') }}">
                                @csrf
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                    </div>
                                @endif
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Название кабинета *">
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
