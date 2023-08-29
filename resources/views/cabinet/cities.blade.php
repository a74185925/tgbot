@extends('cabinet.base')


@section('content')
<h2>Список товаров</h2>

@if (isset($cities))
<div class="row g-3">
    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Город</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Районы</th>
                        <th scope="col">Добавить район</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cities as $item)
                    @php
                        $distrs = $districts->where('city', $item->id);
                    @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->slug }}</td>
                        <td>
                            @if ($distrs->count())
                            <ul>
                            @foreach ($distrs as $distr)
                                <li>
                                    {{ $distr->name }}
                                </li>
                            @endforeach
                            </ul>
                            @endif
                        </td>
                        <td>
                            <a href="/district-add?city={{$item->id}}&add=district" class="btn btn-success btn-sm">Добавить</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{$cities}}
        </div>
    </div>
    <div class="col-md-4">
        <h3>Добавить город</h3>
        <form method="POST" enctype="multipart/form-data">
            @csrf
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
<div class="mb-5 text-center">Городов еще не добавлено</div>
@endif

    
@endsection