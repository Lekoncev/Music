<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
        // Количество альбомов на странице
        $perPage = 5; // Вы можете изменить это значение по вашему усмотрению

        // Получение альбомов с пагинацией
        $albums = Album::paginate($perPage);

        // Возвращение представления с альбомами
        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        return view('albums.create');
    }

    public function fetchAlbumData(Request $request) {
        $apiKey = '3ce267e43523f166401124c9a9ee1286'; // Замените на ваш реальный API ключ
        $albumName = $request->query('album');
        $artistName = $request->query('artist'); // Получаем имя исполнителя из запроса

        $client = new Client();

        try {
            $url = 'http://ws.audioscrobbler.com/2.0/';
            $response = $client->request('GET', $url, [
                'query' => [
                    'method' => 'album.getinfo',
                    'api_key' => $apiKey,
                    'artist' => $artistName, // Убедитесь, что имя исполнителя передается
                    'album' => $albumName,
                    'format' => 'json'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'description' => 'nullable',
            'cover_image' => 'nullable|url', // Валидация на корректный URL
        ]);

        // Создание нового альбома с заполнением полей
        $album = new Album();
        $album->title = $request->input('title');
        $album->artist = $request->input('artist');
        $album->description = $request->input('description');

        // Сохранение URL обложки, если он указан
        if ($request->filled('cover_image')) {
            $album->cover_image = $request->input('cover_image');
        } else {
            $album->cover_image = null; // Устанавливаем null, если URL не указан
        }

        // Сохранение альбома в базе данных
        $album->save();

        // Логирование изменений
        \Log::info('Album created: ' . $album->title);

        return redirect()->route('albums.index')->with('success', 'Альбом успешно создан!');
    }

    public function edit(Album $album)
    {
        return view('albums.edit', compact('album'));
    }

    public function update(Request $request, $id)
    {
        // Валидация входящих данных
        $request->validate([
            'title' => 'required',
            'artist' => 'required',
            'description' => 'nullable',
            'cover_image' => 'nullable|url', // Валидация на корректный URL
        ]);

        // Поиск существующего альбома
        $album = Album::findOrFail($id);

        // Обновление полей альбома
        $album->title = $request->input('title');
        $album->artist = $request->input('artist');
        $album->description = $request->input('description');

        // Обновление URL обложки, если он указан
        if ($request->filled('cover_image')) {
            $album->cover_image = $request->input('cover_image');
        }

        // Сохранение обновленного альбома в базе данных
        $album->save();

        // Логирование изменений
        \Log::info('Album updated: ' . $album->title);

        return redirect()->route('albums.index')->with('success', 'Альбом успешно обновлен!');
    }

    public function destroy($id)
    {
        $album = Album::findOrFail($id); // Находим альбом по ID
        $album->delete(); // Удаляем альбом

        return redirect()->route('albums.index')->with('success', 'Альбом удален!');
    }

}
