<?php

namespace App\Policies;

use App\Models\Episode;
use App\Models\Podcast;
use App\Models\User;

class EpisodePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Episode $episode): bool
    {
        return $user->id === $episode->podcast->user_id;
    }

    public function create(User $user, Podcast $podcast): bool
    {
        return $user->id === $podcast->user_id;
    }

    public function update(User $user, Episode $episode): bool
    {
        return $user->id === $episode->podcast->user_id;
    }

    public function delete(User $user, Episode $episode): bool
    {
        return $user->id === $episode->podcast->user_id;
    }
}
