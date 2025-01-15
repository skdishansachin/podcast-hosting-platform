<?php

namespace App\Http\Controllers\Podcast;

use App\Http\Controllers\Controller;
use App\Http\Requests\Podcast\StorePodcastRequest;
use App\Http\Requests\Podcast\UpdatePodcastRequest;
use App\Http\Resources\Podcast\PodcastResource;
use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PodcastController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Podcast::class);
        $podcasts = $request->user()->podcasts();
        return PodcastResource::collection($podcasts);
    }

    public function store(StorePodcastRequest $request)
    {
        Gate::authorize('create', Podcast::class);
        $podcast = $request->user()->podcasts()->create($request);
        return PodcastResource::make($podcast);
    }

    public function show(Podcast $podcast)
    {
        Gate::authorize('view', $podcast);
        return PodcastResource::make($podcast);
    }

    public function update(UpdatePodcastRequest $request, Podcast $podcast)
    {
        Gate::authorize('update', $podcast);
        $podcast = tap($podcast->update($request));
        return PodcastResource::make($podcast);
    }

    public function destroy(Podcast $podcast)
    {
        Gate::authorize('delete', $podcast);
        $podcast->delete();
        return response()->noContent();
    }
}
