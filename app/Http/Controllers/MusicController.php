<?php

namespace App\Http\Controllers;

use App\Http\Requests\Music\StorePostRequest;
use App\Models\Music;
use Illuminate\Http\Request;
use App\Traits\MusicTrait;
use Error;
use Exception;
use Illuminate\Support\Facades\Auth;

class MusicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $musics = Music::query();

        if ($request->has('search')) {
            $musics->where('title', 'LIKE', '%' . $request->get('search') . '%');
        }

        if ($request->has('filter')) {
            $filters = explode(';', $request->get('filter'));

            foreach ($filters as $filter) {
                $filterData = explode(':', $filter);
                $musics->where($filterData[0], $filterData[1], $filterData[2]);
            }
        }

        if ($request->has('order')) {
            $order = explode(',', $request->get('order'));
            $musics->orderBy($order[0], !empty($order[1]) ? $order[1] : 'asc');
        }

        if ($request->has('properties')) {
            $musics->selectRaw('id,' . $request->get('properties'));
        }

        $musics = $musics->paginate(5);

        return $musics;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $body = $request->only('url');

        try {
            // Extract the video ID
            $videoId = MusicTrait::extractVideoId($body['url']);
            if (!$videoId) {
                throw new Error('URL do Youtube inválida!');
            }

            // Search video information
            $body = MusicTrait::getVideoInfo($videoId);

            // Get the ID of the user who suggested the video
            $body['user_id'] = Auth::user()->id;

            // Saved to database
            $music = Music::create($body);

            return $music;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $music = Music::where('id', $id)->with('user')->first();

        if (!$music) {
            return response()->json(['message' => 'Música não encontrada!'], 404);
        }

        return $music;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
