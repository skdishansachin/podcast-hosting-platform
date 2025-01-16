<?php

use App\Models\Podcast;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

test('user must be authenticated to create a podcast', function () {
    $title = fake()->sentence;
    $description = fake()->paragraph;

    $response = postJson(route('podcasts.store'), [
        'title' => $title,
        'description' => $description,
    ]);

    $response->assertUnauthorized();

    assertDatabaseMissing(Podcast::class, [
        'title' => $title,
        'description' => $description,
    ]);
});

test('authenticated user can create a podcast', function () {
    $user = User::factory()->create();

    $title = fake()->sentence;
    $description = fake()->paragraph;

    $response = actingAs($user)->postJson(route('podcasts.store'), [
        'title' => $title,
        'description' => $description,
    ]);

    $response->assertCreated();

    assertDatabaseHas(Podcast::class, [
        'title' => $title,
        'description' => $description,
        'user_id' => $user->id,
    ]);
});

test('validation rules are applied when creating a podcast', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->postJson(route('podcasts.store'), [
        'title' => '',
        'description' => '',
    ]);

    $response->assertUnprocessable();

    $response->assertJsonValidationErrors(['title', 'description']);
});

test('authenticated user can update their podcast', function () {
    $user = User::factory()->create();

    $oldTitle = fake()->sentence;
    $oldDescription = fake()->paragraph;

    $newTitle = fake()->sentence;
    $newDescription = fake()->paragraph;

    $podcast = $user->podcasts()->create([
        'title' => $oldTitle,
        'description' => $oldDescription,
    ]);

    $response = actingAs($user)->putJson(route('podcasts.update', $podcast), [
        'title' => $newTitle,
        'description' => $newDescription,
    ]);

    $response->assertOk();

    assertDatabaseHas(Podcast::class, [
        'id' => $podcast->id,
        'title' => $newTitle,
        'description' => $newDescription,
    ]);
});

test('validation rules are applied when updating a podcast', function () {
    $user = User::factory()->create();

    $podcast = $user->podcasts()->create([
        'title' => fake()->sentence,
        'description' => fake()->paragraph,
    ]);

    $response = actingAs($user)->putJson(route('podcasts.update', $podcast), [
        'title' => '',
        'description' => '',
    ]);

    $response->assertUnprocessable();

    $response->assertJsonValidationErrors(['title', 'description']);
});

test('authenticated user can delete their podcast', function () {
    $user = User::factory()->create();

    $podcast = $user->podcasts()->create([
        'title' => fake()->sentence,
        'description' => fake()->paragraph,
    ]);

    $response = actingAs($user)->deleteJson(route('podcasts.destroy', $podcast));

    $response->assertNoContent();

    assertDatabaseMissing(Podcast::class, [
        'id' => $podcast->id,
    ]);
});

test('user cannot update or delete another user\'s podcast', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $podcast = $user1->podcasts()->create([
        'title' => fake()->sentence,
        'description' => fake()->paragraph,
    ]);

    $updateResponse = actingAs($user2)->putJson(route('podcasts.update', $podcast), [
        'title' => fake()->sentence,
        'description' => fake()->paragraph,
    ]);

    $updateResponse->assertForbidden();

    $deleteResponse = actingAs($user2)->deleteJson(route('podcasts.destroy', $podcast));

    $deleteResponse->assertForbidden();

    assertDatabaseHas(Podcast::class, [
        'id' => $podcast->id,
    ]);
});
