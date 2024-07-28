<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DailyReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DailyReport  $dailyReport
     * @return mixed
     */
    public function view(User $user, DailyReport $dailyReport)
    {
        return $user->type === 'admin' || $user->department_id === $dailyReport->department_id;
    }

    /**
     * Determine whether the user can delete any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->type === 'admin';
    }

    public function create(User $user)
    {
        return $user->type === 'admin' || $user->department_id !== null;
    }
}
