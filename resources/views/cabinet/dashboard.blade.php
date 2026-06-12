@extends('layouts.cabinet')

@section('title', 'Личный кабинет')

@section('content')

    <section id="" class="cabinet-section cabinet-section-dashboard cabinet-actions-section-h-center">
        <div class="container">

            @auth
                @if(auth()->user()->isSuperAdmin())
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="back-log">
                                    <div class="back-link">
                                        <a class="btn-link btn-backlink" href="{{ route('superadmin.cabinets.index') }}">Вернуться назад</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif
            @endauth



            <div class="row">
                <div class="col-xs-12">
                    <div class="cabinet-title-section">
                        <H1 class="text-center">Выберите действие</H1>
                    </div>


                    <div class="actions-row">
                        <div class="actions-item">
                            <div class="actions-card">
                                <div class="actions-prew"><img src="{{ asset('img/action-1.png') }}" alt=""></div>
                            </div>
                            <div class="event-title">Заказчики</div>
                            <div class="event-actions">
                                <a href="{{ route('cabinet.clients.index') }}" type="button" class="blue-btn blue-btn-small">Смотреть</a>
                                <a href="{{ route('cabinet.clients.create') }}" type="button" class="blue-btn-rev blue-btn-small">Добавить</a>
                                <a href="{{ route('cabinet.clients.index') }}" type="button" class="blue-btn-rev blue-btn-small">Редактировать</a>
                            </div>


                        </div>


                        <div class="actions-item">
                            <div class="actions-card">
                                <div class="actions-prew"><img src="{{ asset('img/action-2.png') }}" alt=""></div>
                            </div>
                            <div class="event-title">Проекты мероприятий</div>
                            <div class="event-actions">
                                <a href="{{ route('cabinet.events.index') }}" type="button" class="blue-btn blue-btn-small">
                                    Смотреть
                                </a>
                                <a href="{{ route('cabinet.events.create') }}" type="button" class="blue-btn-rev blue-btn-small">
                                    Добавить
                                </a>
                                <a href="{{ route('cabinet.events.index') }}" type="button" class="blue-btn-rev blue-btn-small">
                                    Редактировать
                                </a>
                            </div>
                        </div>



                        <div class="actions-item">
                            <div class="actions-card">
                                <div class="actions-prew"><img src="{{ asset('img/action-3.png') }}" alt=""></div>


                            </div>
                            <div class="event-title">Админы</div>
                            <div class="event-actions">
                                <a type="button" href="{{ route('cabinet.admins.index') }}" class="blue-btn blue-btn-small">Смотреть</a>
                                <a type="button" href="{{ route('cabinet.admins.create') }}" class="blue-btn-rev blue-btn-small">Добавить</a>
                                <a type="button" href="{{ route('cabinet.admins.index') }}" class="blue-btn-rev blue-btn-small">Редактировать</a>



                            </div>


                        </div>









                    </div>



                </div>
            </div>
        </div>
    </section>


@endsection
