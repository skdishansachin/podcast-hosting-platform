<?php

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;

beforeEach(function () {
    Storage::fake('episodes');
});

test('episodes can be listed for a podcast', function () {
    $user = User::factory()->create();
    $podcast = Podcast::factory()->for($user)->create();
    Episode::factory(15)->for($podcast)->create();

    $response = actingAs($user)->getJson(route('podcasts.episodes.index', $podcast));

    $response->assertOk();
    $response->assertJsonCount(10, 'data');
});

test('authenticated user can create an episode', function () {
    Storage::fake('episodes');

    $user = User::factory()->create();
    $podcast = Podcast::factory()->for($user)->create();

    $audioFile = UploadedFile::fake()->create('episode.mp3', 2048);

    $response = actingAs($user)->postJson(route('podcasts.episodes.store', $podcast), [
        'title' => 'Episode 1',
        'description' => 'Description of Episode 1',
        'audio_file' => $audioFile,
    ]);

    $response->assertCreated();
});

test('validation rules are applied when creating an episode', function () {
    $user = User::factory()->create();
    $podcast = Podcast::factory()->for($user)->create();

    $response = actingAs($user)->postJson(route('podcasts.episodes.store', $podcast), [
        'title' => '',
        'description' => '',
        'audio_file' => null,
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['title', 'description', 'audio_file']);
});

test('episode details can be fetched', function () {
    $user = User::factory()->create();
    $podcast = Podcast::factory()->for($user)->create();
    $episode = Episode::factory()->for($podcast)->create();

    $response = actingAs($user)->getJson(route('episodes.show', $episode));

    $response->assertOk();
    $response->assertJsonFragment([
        'title' => $episode->title,
        'description' => $episode->description,
    ]);
});

test('authenticated user can update an episode without changing audio file', function () {
    $user = User::factory()->create();
    $podcast = Podcast::factory()->for($user)->create();
    $episode = Episode::factory()->for($podcast)->create([
        'title' => 'Old Title',
        'description' => 'Old Description',
    ]);

    $response = actingAs($user)->putJson(route('episodes.update', $episode), [
        'title' => 'New Title',
        'description' => 'New Description',
    ]);

    $response->assertOk();

    assertDatabaseHas(Episode::class, [
        'id' => $episode->id,
        'title' => 'New Title',
        'description' => 'New Description',
    ]);
});

test('authenticated user can update an episode with a new audio file', function () {
    Storage::fake('episodes');
    
    $user = User::factory()->create();
    $podcast = Podcast::factory()->for($user)->create();
    $episode = Episode::factory()->for($podcast)->create();

    $newAudioFile = UploadedFile::fake()->create('new_audio.mp3', 2048);

    $response = actingAs($user)->putJson(route('episodes.update', $episode), [
        'title' => $episode->title,
        'description' => $episode->description,
        'audio_file' => $newAudioFile,
    ]);

    $response->assertOk();
});

test('validation rules are applied when updating an episode', function () {
    $user = User::factory()->create();
    $podcast = Podcast::factory()->for($user)->create();
    $episode = Episode::factory()->for($podcast)->create();

    $response = actingAs($user)->putJson(route('episodes.update', $episode), [
        'title' => '',
        'description' => '',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['title', 'description']);
});

test('authenticated user can delete an episode', function () {
    $user = User::factory()->create();
    $podcast = Podcast::factory()->for($user)->create();
    $episode = Episode::factory()->for($podcast)->create([
        'audio_file' => 'audio_to_delete.mp3',
    ]);

    $response = actingAs($user)->deleteJson(route('episodes.destroy', $episode));

    $response->assertNoContent();

    assertDatabaseMissing(Episode::class, [
        'id' => $episode->id,
    ]);

    Storage::disk('episodes')->assertMissing('audio_to_delete.mp3');
});

test('user cannot update or delete an episode they do not own', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $podcast = Podcast::factory()->for($user1)->create();
    $episode = Episode::factory()->for($podcast)->create();

    $updateResponse = actingAs($user2)->putJson(route('episodes.update', $episode), [
        'title' => 'Title',
        'description' => 'Description',
    ]);

    $updateResponse->assertForbidden();

    $deleteResponse = actingAs($user2)->deleteJson(route('episodes.destroy', $episode));

    $deleteResponse->assertForbidden();

    assertDatabaseHas(Episode::class, [
        'id' => $episode->id,
    ]);
});
