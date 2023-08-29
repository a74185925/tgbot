@extends('cabinet.base')


@section('content')
<h2>Добавить посылку</h2>

@if (isset($result))
   
@else
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

        <div class="col-md-4">
            <label for="district" class="form-label">Район</label>
            <select class="form-select" id="district" name="district" disabled>
                <option>Выберите район</option>
            </select>
            <div class="invalid-feedback">
                Выберите район
            </div>
        </div>

        <div class="col-md-4">
            <label for="product" class="form-label">Товар</label>
            <select class="form-select" id="product" name="product" required disabled>
                <option>Выберите c каким товаром посылка</option>
            </select>
            <div class="invalid-feedback">
                Выберите c каким товаром посылка!!!
            </div>
        </div>

        <div class="col-12">
            <label class="form-label" for="customFile">Фото</label>
            <input type="file" class="form-control" id="foto" name="foto">
        </div>

        <div class="col-12">
            <label for="instruction" class="form-label">Инструкция</label>
            <textarea type="text" class="form-control" id="instruction" name="instruction" placeholder="Инструкция к посылке" required></textarea>
        </div>

        <button class="btn btn-primary btn-lg" type="submit">Добавить</button>

    </div>
    
</form>

@endif

    
@endsection

@section('js')
<div class="d-none" id="sel-prods">

    @foreach ($prod_list as $key => $val)

            <div class="city_{{ $key }}">
                @foreach ($val as $vkey => $vval)
                <option value="{{ $vkey }}">{{ $vval }}</option>
                @endforeach
            </div>

    @endforeach
</div>
<div class="d-none" id="sel-districts">

    @foreach ($districts_list as $key => $val)

            <div class="district_{{ $key }}">
                @foreach ($val as $vkey => $vval)
                <option value="{{ $vkey }}">{{ $vval }}</option>
                @endforeach
            </div>

    @endforeach
</div>
<script>
$( document ).ready(function() {

    $("#city").change(function(){
        if(!$(this).val()) return false;

        let value = $(this).val();
        // PRODUCTS
        let target = $('#product');
        let newOptions = $('#sel-prods .city_'+$(this).val()).children();
        // Очищаем список текущих product
        target.empty();
        // Заполняем нужными данными
        target.append('<option>Выберите c каким товаром посылка</option>');
        newOptions.clone().appendTo(target);
        target.prop('disabled', value === "Выберите c каким товаром посылка")

        // DISTRICTS
        let targetDis = $('#district');
        let newOptionsDis = $('#sel-districts .district_'+$(this).val()).children();
        if (newOptionsDis.length) {
            console.log(newOptionsDis)
            targetDis.empty();
            newOptionsDis.clone().appendTo(targetDis);
            targetDis.prop('disabled', value === "Выберите район")
        } else {
            targetDis.empty();
            targetDis.append('<option>Районы не заданы</option>');
            targetDis.attr('disabled', true);
        }
        
        


        
        
    });

});
</script>
@endsection