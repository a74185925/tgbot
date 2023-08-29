@extends('cabinet.base')


@section('content')
<h2>Добавить посылку</h2>

@if (isset($message))
    <form method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="message_id" value="{{ $message->id }}">
        <h3 class="mb-2">{{ $message->name }}</h3>
        <div class="row g-3">

            <div class="col-12">
                <label for="text" class="form-label">Текст:</label>
                <textarea type="text" class="form-control" id="text" name="text" rows="10" required>{{ $message->text }}</textarea>
            </div>

            @for ($i = 2; $i < 9; $i++)
                @php
                    $text_property_name = 'text'.$i;
                    $message_text = $message->{$text_property_name}
                @endphp
                @if ($message_text)
                <div class="col-12">
                    <label for="text{{ $i }}" class="form-label">Дополнительный текст {{ $i }}:</label>
                    <textarea type="text" class="form-control" id="text{{ $i }}" name="text{{ $i }}" rows="3" required>{{ $message_text }}</textarea>
                </div>                 
                @endif
              
            @endfor

            <button class="btn btn-primary btn-lg" type="submit">Сохранить</button>

        </div>
        
    </form>
@else
<h3 class="mb-3">Выбери какое сообщение редактировать</h3>
<div class="row">
    
    @foreach ($messages as $item)
    <div class="col-md-6 mb-2">
        <a href="?selected_message={{ $item->id }}" class="btn btn-secondary">{{ $item->name }}</a>
    </div>
    @endforeach

</div>

@endif

    
@endsection

