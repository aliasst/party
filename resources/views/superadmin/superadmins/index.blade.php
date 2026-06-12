@extends('layouts.cabinet')

@section('title', 'Главные админы')

@section('content')
    <section class="cabinet-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="back-log">
                        <div class="back-link"><a class="btn-link btn-backlink" href="{{ route('super.dashboard') }}">Вернуться назад</a></div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="cabinet-title-section cabinet-title-section-flex">
                        <h1>Главные админы</h1>
                        <div class="title-link"><a href="{{ route('super.superadmins.create') }}">Добавить суперадмина</a></div>
                    </div>

                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

                    <table class="requests">
                        <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Дата создания</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($superadmins as $superadmin)
                            <tr>
                                <td aria-label="Имя">{{ $superadmin->name }}</td>
                                <td aria-label="Email">{{ $superadmin->email }}</td>
                                <td aria-label="Дата создания">{{ $superadmin->created_at->format('d.m.Y') }}</td>
                                <td class="req-act" aria-label="Действия">
                                    <a href="{{ route('super.superadmins.edit', $superadmin) }}" class="btn btn-sm btn-primary">✎</a>
                                    @if($superadmin->id !== auth()->id())
                                        <form action="{{ route('super.superadmins.destroy', $superadmin) }}" method="POST" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить суперадмина?')">✖</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Нет суперадминов</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $superadmins->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
