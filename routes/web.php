<?php

use App\Http\Controllers\Episode\EpisodeController;
use App\Http\Controllers\Podcast\PodcastController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::middleware(['auth'])->group(function () {
    Route::resource('podcasts', PodcastController::class);
    Route::resource('podcasts.episodes', EpisodeController::class)->shallow();
});

require __DIR__.'/auth.php';
