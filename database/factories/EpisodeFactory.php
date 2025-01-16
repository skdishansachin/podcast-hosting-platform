<?php

namespace Database\Factories;

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EpisodeFactory extends Factory
{
    public function getAudioFileName(): string
    {
        $audioFileName = Str::random();

        return "'$audioFileName'.mp3";
    }

    public function definition(): array
    {
        return [
            'podcast_id' => Podcast::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(nbSentences: 1),
            'audio_url' => $this->getAudioFileName(),
        ];
    }
}
