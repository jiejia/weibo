<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 当前被操作用户是当前用户时
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     * @version  2020-11-3 9:16
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * 当前用户是管理员，并且被操作用户不是当前用户时
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     * @version  2020-11-3 9:15
     * @author   jiejia <jiejia2009@gmail.com>
     * @license  PHP Version 7.2.9
     */
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->is_admin && ($currentUser->id !== $user->id);
    }

}
