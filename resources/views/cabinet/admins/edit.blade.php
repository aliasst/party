@extends('layouts.cabinet')

@section('title', 'Редактировать пользователя')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.admins.index') }}">Вернуться назад</a></div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1>Редактировать: {{ $admin->name }}</h1>
                    </div>
                </div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.admins.update', $admin) }}">
                                @csrf
                                @method('PUT')
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                    </div>
                                @endif
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required placeholder="Имя *">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required placeholder="Email *">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <select name="cabinet_role" class="form-select" required>
                                                <option value="admin" {{ ($admin->pivot->role ?? '') == 'admin' ? 'selected' : '' }}>Администратор кабинета</option>
                                                <option value="user" {{ ($admin->pivot->role ?? '') == 'user' ? 'selected' : '' }}>Пользователь</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="password" name="password" placeholder="Новый пароль (оставьте пустым, если не менять)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="password" name="password_confirmation" placeholder="Подтверждение нового пароля">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="orange-btn orange-btn-small">Сохранить</button>
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
