@extends('layouts.cabinet')

@section('title', 'Добавить админа')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.admins.index') }}">Вернуться назад</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1 class="text-center">Добавить администратора</h1>
                    </div>
                </div>

                <div class="col-xs-12 col-md-8">

                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.admins.store') }}">
                                @csrf

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                    </div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Имя *">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email *">
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
                                            <input type="password" name="password" required placeholder="Пароль *">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="password" name="password_confirmation" required placeholder="Подтверждение пароля *">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="orange-btn orange-btn-small">Добавить</button>
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
