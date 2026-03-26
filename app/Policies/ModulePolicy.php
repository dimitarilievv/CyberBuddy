<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Module;

class ModulePolicy
{
    /**
     * Determine if the given module can be updated by the user.
     */
    public function update(User $user, Module $module): bool
    {
        return $user->id === $module->author_id;
    }

    /**
     * Determine if the given module can be deleted by the user.
     */
    public function delete(User $user, Module $module): bool
    {
        return $user->id === $module->author_id;
    }

    /**
     * Determine if the given module can be published by the user.
     */
    public function publish(User $user, Module $module): bool
    {
        return $user->id === $module->author_id;
    }
}

