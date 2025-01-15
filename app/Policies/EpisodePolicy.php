<?php

namespace App\Policies;

use App\Models\Episode;
use App\Models\User;
use Illuminate\Auth\Access\Response;

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

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Episode $episode): bool
    {
        return false;
    }

    public function delete(User $user, Episode $episode): bool
    {
        return false;
    }

    public function restore(User $user, Episode $episode): bool
    {
        return false;
    }

    public function forceDelete(User $user, Episode $episode): bool
    {
        return false;
    }
}
