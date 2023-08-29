@extends('cabinet.base')


@section('headbuttons')
<a href="/loot-add" class="btn btn-sm btn-outline-secondary">
    <span data-feather="calendar" class="align-text-bottom"></span>
    Добавить посылку
</a>
@endsection

@section('content')
<h2>Список посылок</h2>

@if (isset($loots) && $loots->count())
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Продукт</th>
                <th scope="col">Район</th>
                <th scope="col">Статус</th>
                <th scope="col">Курьер</th>
                <th scope="col">Ссылка на фото</th>
                <th scope="col">Описание</th>
                <th scope="col"></th>

            </tr>
        </thead>
        <tbody>
            @foreach ($loots as $item)
            @php
                $district=$districts->find($item->district);
            @endphp
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->product }}</td>
                <td>
                    @if (isset($district))
                        {{ $district->name }}
                    @endif
                </td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->manager }}</td>
                <td>{{ $item->img }}</td>
                <td>{{ $item->text }}</td>
                <td>
                    <form action="{{ route('loot-del') }}" method="post">
                        @csrf
                        <input type="hidden" name="lootForDel" value="{{ $item->id }}">
                        <button class="btn btn-danger btn-sm" type="submit">удалить</button>        
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$loots}}
</div>    
@else
<div class="mb-5 text-center">Активных посылок нет</div>
@endif

    
@endsection