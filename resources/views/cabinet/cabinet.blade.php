@extends('cabinet.base')


@section('content')
<h2>Список посылок</h2>

@if (isset($loots))
<div class="table-responsive">
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Продукт</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Курьер</th>
                    <th scope="col">Ссылка на фото</th>
                    <th scope="col">Описание</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loots as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->product }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->manager }}</td>
                    <td>{{ $item->img }}</td>
                    <td>{{ $item->text }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$loots}}
    </div> 
</div>    
@else
<div class="mb-5 text-center">Активных посылок нет</div>
@endif

    
@endsection