<?php

namespace App\Http\Controllers\Episode;

use App\Http\Controllers\Controller;
use App\Http\Requests\Episode\StoreEpisodeRequest;
use App\Http\Requests\Episode\UpdateEpisodeRequest;
use App\Http\Resources\Episode\EpisodeResource;
use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Support\Facades\Storage;

class EpisodeController extends Controller
{
    public function index(Podcast $podcast)
    {
        $episodes = $podcast->episodes()->paginate(10);

        return EpisodeResource::collection($episodes);
    }

    public function store(StoreEpisodeRequest $request, Podcast $podcast)
    {
        $path = Storage::putFile('episodes', $request->file('audio_file'));
        $episode = $podcast->episodes()->create([
            'title' => $request->title,
            'description' => $request->description,
            'audio_file' => $path,
        ]);

        return EpisodeResource::make($episode);
    }

    public function show(Episode $episode)
    {
        return EpisodeResource::make($episode);
    }

    public function update(UpdateEpisodeRequest $request, Episode $episode)
    {
        if (! $request->file('audio_file')) {
            $episode = tap($episode)->update($request->validated());

            return EpisodeResource::make($episode);
        }

        Storage::delete($episode->audio_file);
        $path = Storage::putFile('episodes', $request->file('audio_file'));
        $episode = tap($episode)->update([
            'title' => $request->title,
            'description' => $request->description,
            'audio_file' => $path,
        ]);

        return EpisodeResource::make($episode);
    }

    public function destroy(Episode $episode)
    {
        Storage::delete($episode->audio_file);
        
        $episode->delete();

        return response()->noContent();
    }
}
