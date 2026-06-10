@extends('layouts.cabinet')

@section('title', 'Редактирование профиля')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.dashboard') }}">Вернуться назад</a>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1 class="text-center">Редактировать профиль</h1>
                    </div>
                </div>

                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.profile.update') }}" enctype="multipart/form-data" id="profile-form">
                                @csrf
                                @method('PUT')

                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                    </div>
                                @endif

                                <!-- Блок аватарки с предпросмотром -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="avatar-upload-wrapper">
                                            <div class="avatar-preview">
                                                <div class="avatar-preview-image" id="avatar-preview">
                                                    @if(Auth::user()->avatar)
                                                        <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="avatar">
                                                    @else
                                                        <img src="{{ asset('img/avatar.svg') }}" alt="avatar">
                                                    @endif
                                                </div>
                                                <label for="avatar-upload" class="avatar-upload-label orange-btn orange-btn-small">Выбрать фото</label>
                                                <input type="file" name="avatar" id="avatar-upload" accept="image/jpeg,image/png,image/jpg,image/gif" style="display: none;">
                                            </div>
                                            <div class="avatar-help-text">Допустимые форматы: JPG, PNG, GIF. Максимум 2 МБ.</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Имя -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="Имя *">
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-input">
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="Email *">
                                        </div>
                                    </div>
                                </div>

                                <!-- Новый пароль -->
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
                                        <button type="submit" class="orange-btn orange-btn-small">Сохранить профиль</button>
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
            $('#avatar-upload').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('#avatar-preview img').attr('src', event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
