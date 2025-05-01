<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Trip $trip)
    {
        return $user->id === $trip->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Trip $trip)
    {
        return $user->id === $trip->user_id;
    }

    public function delete(User $user, Trip $trip)
    {
        return $user->id === $trip->user_id;
    }
} 