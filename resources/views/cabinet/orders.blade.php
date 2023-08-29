@extends('cabinet.base')


@section('content')
<h2>Список заказов</h2>

@if (isset($orders))
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">ID КЛИЕНТА</th>
                <th scope="col">СТАТУС</th>
                <th scope="col">ГОРОД</th>
                <th scope="col">РАЙОН</th>
                <th scope="col">ПРОДУКТ</th>
                <th scope="col">ЦЕНА</th>
                <th scope="col">СОЗДАН</th>
                <th scope="col">ИЗМЕНЕН</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->customer }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->city }}</td>
                <td>{{ $item->district }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$orders}}
</div>    
@else
<div class="mb-5 text-center">Активных заказов нет</div>
@endif

    
@endsection