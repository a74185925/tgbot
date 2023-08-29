@extends('cabinet.base')

@section('content')
<h2>Добавить продукт</h2>


<form method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">

        @if (isset($cities))
        <div class="col-md-4">
            <label for="city" class="form-label">Город</label>
            <select class="form-select" id="city" name="city" required>
                <option value="false">Выберите город...</option>
                @foreach ($cities as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                Выберите город!!!
            </div>
        </div>
        @endif
        <div class="col-12">
            <label for="name" class="form-label">Название товара</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Название товара" required>
        </div>
        <div class="col-12">
            <label for="price" class="form-label">Цена товара</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="Цена товара" required>
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" id="" cols="30" rows="6"></textarea>
            <div id="descriptionHelp" class="form-text">Необязательное поле. Выводится при покувке товара. после его выбора</div>

        </div>

        <button class="btn btn-primary btn-lg" type="submit">Добавить</button>

    </div>
    
</form>

    
@endsection

