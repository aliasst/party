@extends('layouts.cabinet')

@section('title', 'Панель управления')

@section('content')
    <section class="cabinet-section cabinet-section-dashboard cabinet-actions-section-h-center">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <h1 class="text-center">Выберите действие</h1>
                    </div>
                    <div class="actions-row">
                        <!-- Карточка "Кабинеты" -->
                        <div class="actions-item">
                            <div class="actions-card">
                                <div class="actions-prew"><img src="{{ asset('img/action-1.png') }}" alt="Кабинеты"></div>
                            </div>
                            <div class="event-title">Кабинеты</div>
                            <div class="event-actions">
                                <a type="button" href="{{ route('superadmin.cabinets.index') }}" class="blue-btn blue-btn-small">Смотреть</a>
                                <a type="button" href="{{ route('superadmin.cabinets.create') }}" class="blue-btn-rev blue-btn-small">Добавить</a>
                                <a type="button" href="{{ route('superadmin.cabinets.index') }}" class="blue-btn-rev blue-btn-small">Редактировать</a>
                            </div>
                        </div>
                        <!-- Карточка "Главные админы" -->
                        <div class="actions-item">
                            <div class="actions-card">
                                <div class="actions-prew"><img src="{{ asset('img/action-2.png') }}" alt="Главные админы"></div>
                            </div>
                            <div class="event-title">Главные админы</div>
                            <div class="event-actions">
                                <a type="button" href="{{ route('super.superadmins.index') }}" class="blue-btn blue-btn-small">Смотреть</a>
                                <a type="button" href="{{ route('super.superadmins.create') }}" class="blue-btn-rev blue-btn-small">Добавить</a>
                                <a type="button" href="{{ route('super.superadmins.index') }}" class="blue-btn-rev blue-btn-small">Редактировать</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
