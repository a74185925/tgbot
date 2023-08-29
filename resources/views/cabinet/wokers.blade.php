@extends('cabinet.base')



@section('content')
<h2>Список сотрудников</h2>

@if (isset($wokers) && $wokers->count())
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Имя</th>
                <th scope="col">Должность</th>
                <th scope="col">Почта</th>
                <th scope="col">Дата добавления</th>
                <th scope="col"></th>

            </tr>
        </thead>
        <tbody>
            @foreach ($wokers as $item)
            <tr>
                @php
                    if ($item->is_admin) {
                        $role = 'admin';
                    } elseif ($item->is_manager) {
                        $role = 'Менеджер';
                    } elseif ($item->is_courier) {
                        $role = 'Курьер';
                    } else {
                        $role = 'Какой-то левый ноунейм!';
                    }
                    
                @endphp
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $role }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->created_at }}</td>
                <td><a href="delete-woker?del={{$item->id}}">DEL</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$wokers}}
</div>    
@else
<div class="mb-5 text-center">Нет сотрудников</div>
@endif

    
@endsection