@extends('layouts.cabinet')

@section('title', 'Редактировать заказчика')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12">
                    <div class="back-log"><div class="back-link"><a class="btn-link btn-backlink" href="{{ route('cabinet.clients.index') }}">Вернуться к заказчикам</a></div></div>
                </div>
                <div class="col-xs-12"><div class="cabinet-title-section"><h1 class="text-center">Редактировать заказчика: {{ $client->name }}</h1></div></div>
                <div class="col-xs-12 col-md-8">
                    <div class="request-card card auth-card">
                        <div class="card-content">
                            <form method="POST" action="{{ route('cabinet.clients.update', $client) }}">
                                @csrf @method('PUT')
                                @if ($errors->any())
                                    <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                @endif

                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="text" name="name" value="{{ old('name', $client->name) }}" required placeholder="Название *"></div></div></div>
                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="text" name="legal_name" value="{{ old('legal_name', $client->legal_name) }}" placeholder="Юридическое лицо"></div></div></div>
                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="email" name="email" value="{{ old('email', $client->email) }}" placeholder="E-mail"></div></div></div>
                                <div class="row mb-3"><div class="col-12"><div class="form-input"><input type="text" name="phone" value="{{ old('phone', $client->phone) }}" placeholder="Телефон"></div></div></div>
                                <div class="row mb-3"><div class="col-12"><div class="form-input"><textarea name="requisites" rows="5" placeholder="Реквизиты">{{ old('requisites', $client->requisites) }}</textarea></div></div></div>

                                <div class="row"><div class="col-12"><button type="submit" class="orange-btn orange-btn-small">Сохранить</button></div></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
