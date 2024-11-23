@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Редактировать альбом</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('albums.update', $album) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Название</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $album->title }}" required>
            </div>
            <div class="form-group">
                <label for="artist">Исполнитель</label>
                <input type="text" name="artist" id="artist" class="form-control" value="{{ $album->artist }}" required>
            </div>
            <div class="form-group">
                <label for="description">Описание</label>
                <textarea name="description" id="description" class="form-control">{{ $album->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="cover_image">Обложка (URL)</label>
                <input type="url" name="cover_image" id="cover_image" class="form-control" value="{{ $album->cover_image }}">
                @if($album->cover_image)
                    <img src="{{ $album->cover_image }}" alt="Обложка альбома" class="img-thumbnail mt-2" style="max-width: 200px;">
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
