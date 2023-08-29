@extends('cabinet.base')


@section('content')
<h2>Список товаров</h2>

@if (isset($products))
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Наименование</th>
                <th scope="col">Цена</th>
                <th scope="col">ГОРОД</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $item)
            @php
                $city = $cities->get($item->city - 1);
            @endphp
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $city->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$products}}
</div>    
@else
<div class="mb-5 text-center">Активных товаров нет</div>
@endif

    
@endsection