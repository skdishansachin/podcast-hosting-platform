<?php

namespace App\Http\Controllers\Episode;

use App\Http\Controllers\Controller;
use App\Http\Requests\Episode\StoreEpisodeRequest;
use App\Http\Requests\Episode\UpdateEpisodeRequest;
use App\Http\Resources\Episode\EpisodeResource;
use App\Models\Episode;
use App\Models\Podcast;

class EpisodeController extends Controller
{
    public function index(Podcast $podcast)
    {
        $episodes = $podcast->episodes();
        return EpisodeResource::collection($episodes);
    }

    public function store(StoreEpisodeRequest $request, Podcast $podcast)
    {
        $episode = $podcast->episodes()->create($request->validated());
        return EpisodeResource::make($episode);
    }

    public function show(Episode $episode)
    {
        return EpisodeResource::make($episode);
    }

    public function update(UpdateEpisodeRequest $request, Episode $episode)
    {
        $episode = tap($episode)->update($request->validated());
        return EpisodeResource::make($episode);
    }

    public function destroy(Episode $episode)
    {
        $episode->delete();
        return response()->noContent();
    }
}
