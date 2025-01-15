<?php

namespace App\Policies;

use App\Models\Podcast;
use App\Models\User;

class PodcastPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Podcast $podcast): bool
    {
        return $user->id === $podcast->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Podcast $podcast): bool
    {
        return $user->id === $podcast->user_id;
    }

    public function delete(User $user, Podcast $podcast): bool
    {
        return $user->id === $podcast->user_id;
    }
}
