@extends('cabinet.base')


@section('content')
<h2>Список пользователей</h2>

@if (isset($castomers))
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">TELEGRAM ID</th>
                <th scope="col">ДАТА РЕГИСТРАЦИИ</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($castomers as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->tg_id }}</td>
                <td>{{ $item->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$castomers}}
</div>    
@else
<div class="mb-5 text-center">Активных посылок нет</div>
@endif

    
@endsection