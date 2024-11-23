@extends('layouts.app')

@section('content')
    <form action="{{ route('albums.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="album_name">Название альбома:</label>
            <input type="text" id="album_name" name="title" class="form-control" required>

            <label for="artist_name">Исполнитель:</label>
            <input type="text" id="artist_name" name="artist" class="form-control" required>

            <label for="description">Описание:</label>
            <textarea id="description" name="description" class="form-control" rows="4"></textarea>

            <label for="cover">Обложка:</label>
            <input type="url" id="cover" name="cover_image" class="form-control">

            <button type="button" id="fetch-album-data" class="btn btn-primary mt-2">Получить данные альбома</button>
            <button type="submit" class="btn btn-success mt-2">Создать альбом</button>
        </div>
    </form>

    <div id="album-info" class="mt-3"></div>

    <script>
        document.getElementById('fetch-album-data').addEventListener('click', function() {
            const albumName = document.getElementById('album_name').value;
            const artistName = document.getElementById('artist_name').value;

            if (albumName && artistName) {
                fetch(`/fetch-album-data?album=${encodeURIComponent(albumName)}&artist=${encodeURIComponent(artistName)}`)
                    .then(response => response.json())
                    .then(data => {
                        let albumInfoDiv = document.getElementById('album-info');
                        if (data.error) {
                            albumInfoDiv.innerHTML = `<p style="color: red;">Ошибка: ${data.error}</p>`;
                        } else {
                            document.getElementById('artist_name').value = data.album.artist;

                            // Функция для удаления ссылок из описания
                            const cleanDescription = (description) => {
                                return description.replace(/<a href="[^"]+"[^>]*>(.*?)<\/a>/g, '\$1'); // Удаляем ссылки
                            };

                            document.getElementById('description').value = data.album.wiki ? cleanDescription(data.album.wiki.summary) : 'Нет описания';

                            // Проверка длины массива изображений
                            const images = data.album.image;
                            document.getElementById('cover').value = (images.length > 3) ? images[3]['#text'] : '';

                            albumInfoDiv.innerHTML = `
                                <h3>Информация об альбоме:</h3>
                                <p>Название: ${data.album.name}</p>
                                <p>Исполнитель: ${data.album.artist}</p>
                                <img src="${images[3]['#text'] || ''}" alt="${data.album.title} обложка">
                                <h4>Описание:</h4>
                                <p>${data.album.wiki ? cleanDescription(data.album.wiki.summary) : 'Нет описания'}</p>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка:', error);
                    });
            } else {
                alert('Пожалуйста, введите название альбома и исполнителя.');
            }
        });
    </script>
@endsection
