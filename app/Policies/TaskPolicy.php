<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{

    /**
     * Determine whether the user can create models.
     */
    public function createTask(User $user,Task $task): bool
    {
        return $user->type=='admin';
    }
    public function editTask(User $user,Task $task): bool
    {
        return $user->type=='admin' ||$user->id==$task->user_id;
    }
    public function deleteTask(User $user,Task $task)
    {
        return $user->type=='admin' ||$user->id==$task->user_id;
    }
    public function restoreTask(User $user,Task $task)
    {
        return $user->type=='admin' ||$user->id==$task->user_id;
    }

}
