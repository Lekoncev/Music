@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Список альбомов</h1>
        <a href="{{ route('albums.create') }}" class="btn btn-primary">Добавить альбом</a>
        <table class="table">
            <thead>
            <tr>
                <th>Название</th>
                <th>Исполнитель</th>
                <th>Описание</th>
                <th>Обложка</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($albums as $album)
                <tr>
                    <td>{{ $album->title }}</td>
                    <td>{{ $album->artist }}</td>
                    <td>{{ $album->description }}</td>
                    <td>
                        <img src="{{ $album->cover_image }}" alt="{{ $album->title }} обложка" style="max-width: 200px; height: auto;">
                    </td>
                    <td>
                        <a href="{{ route('albums.edit', $album) }}" class="btn btn-warning">Редактировать</a>
                        <form action="{{ route('albums.destroy', $album->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот альбом?');">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Пагинация --}}
        <div class="d-flex justify-content-center">
            {{ $albums->links() }}
        </div>
    </div>
@endsection
