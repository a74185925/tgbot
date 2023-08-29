@extends('cabinet.base')

@section('content')
<h2>Удаление пользователя</h2>


<form method="POST" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="forDel" value="{{ $forDel->id }}">
    <div class="row g-3">

        <div class="col-md-4">
            <div class="input-group mb-3">
                <p>ВЫ уверены что хотите удалить сотрудника <b>{{ $forDel->name }}</b> </p>
            </div>

        </div>

        <button class="btn btn-primary btn-lg" type="submit">Удалить</button>

    </div>
    
</form>

    
@endsection

