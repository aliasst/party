@extends('layouts.cabinet')

@section('title', 'Редактировать суперадмина')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('super.superadmins.index') }}">Вернуться к списку</a></div></div>
                </div>
                <div class="col-xs-12"><div class="cabinet-title-section"><h1 class="text-center">Редактировать суперадмина: {{ $superadmin->name }}</h1></div></div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('super.superadmins.update', $superadmin) }}">
                                @csrf @method('PUT')
                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                @endif

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input"><input type="text" name="name" value="{{ old('name', $superadmin->name) }}" required placeholder="Имя *"></div></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input"><input type="email" name="email" value="{{ old('email', $superadmin->email) }}" required placeholder="Email *"></div></div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12"><div class="form-input"><input type="password" name="password" placeholder="Новый пароль (оставьте пустым, если не менять)"></div></div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-12"><div class="form-input"><input type="password" name="password_confirmation" placeholder="Подтверждение нового пароля"></div></div>
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
