@extends('cabinet.base')


@section('content')
@if (isset($city))
<h2>{{ $city->name }}</h2>

<div class="row g-3">
    <div class="col-md-8">
        @if (isset($districts))
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Район</th>
                        <th scope="col">Slug</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($districts as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->slug }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        @else
        <div class="mb-5 text-center">Районов нет</div>
        @endif        
    </div>
    <div class="col-md-4">
        <h3>Добавить Район</h3>
        <form method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="city" value="{{$city->id}}">
            <div class="row g-3">
        
                <div class="col-12">
                    <label for="name" class="form-label">Название</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Название" required>
                </div>
                <div class="col-12">
                    <label for="slug" class="form-label">Сокращение</label>
                    <input type="text" class="form-control" id="slug" name="slug" placeholder="Сокращение" required>
                    <div id="slugHelp" class="form-text">Только латиница и цифры</div>
                </div>
        
                <button class="btn btn-primary btn-lg" type="submit">Добавить</button>
        
            </div>
            
        </form>
    </div>    
</div> 
@else
<div class="mb-5 text-center">Неверный запрос</div>
@endif

    
@endsection