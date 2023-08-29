@extends('cabinet.base')



@section('content')
<h2>Список платежей</h2>

@if (isset($payments) && $payments->count())
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Мерчант</th>
                <th scope="col">ID транзакции</th>
                <th scope="col">Заказ</th>
                <th scope="col">Статус</th>
                <th scope="col">Валюта</th>
                <th scope="col">Сумма</th>
                <th scope="col">Дата</th>
                <th scope="col">Дата обновления</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->merch }}</td>
                <td>{{ $item->merch_transaction }}</td>
                <td>{{ $item->order_id }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->currency }}</td>
                <td>{{ $item->sum }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$payments}}
</div>    
@else
<div class="mb-5 text-center">На данный момент транзакций не создано</div>
@endif

    
@endsection