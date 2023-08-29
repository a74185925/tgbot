@extends('cabinet.base')

@section('content')
<h2>Добавить сотрудника</h2>


<form method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">

        <div class="col-md-4">
            <div class="input-group mb-3">
                <select class="form-select" id="role" name="role" required>
                    <option value="false">Выберите роль...</option>
                    <option value="manager">Менеджер</option>
                    <option value="courier">Курьер</option>
                </select>
                <div class="invalid-feedback">
                    Выберите роль!!!
                </div>
            </div>

            <div class="input-group mb-3">
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input name="password" type="password" class="form-control" id="password">
                </div>
            </div>

        </div>

        <div class="col-md-8">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">@</span>
                <input type="text" name="name" class="form-control" placeholder="Имя пользователя" aria-label="Имя пользователя">
            </div>
            
            <div class="input-group mb-3">
                <input type="text" name="email" class="form-control" placeholder="Email пользователя" aria-label="Имя пользователя получателя">
                <span class="input-group-text" id="basic-addon2">@example.com</span>
            </div>
          
        </div>
        <button class="btn btn-primary btn-lg" type="submit">Добавить</button>

    </div>
    
</form>

    
@endsection

