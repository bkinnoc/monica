<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Support\Str;
use Nitm\Content\Models\Role;
use Nitm\Content\Traits\Model;
use Nitm\Content\Traits\CustomWith;
use Nitm\Content\Models\Team as BaseTeam;

/**
 * @inheritDoc
 */
class Team extends BaseTeam
{
    use Model, CustomWith;

    /**
     * @param role The role you want to translate.
     *
     * @return The role name is being returned.
     */
    public function translateRole($role)
    {
        $role = strtolower($role);
        if ($role == 'mentor') {
            return $this->user_role_name;
        } elseif ($role == 'student') {
            return $this->student_role_name;
        } elseif ($role == 'organization-admin') {
            $role = 'Organization Admin';
        }
        return Str::title($role);
    }

    /**
     * Resolve Role to a supported role
     *
     * @param  mixed $role
     * @return string
     */
    public function resolveRole(string $role): string
    {
        $roles = \Cache::rememberForever(
            'user-roles',
            function () {
                return Role::where("name", "!=", "Super Admin")->get();
            }
        );
        $role = Str::slug($role);
        return in_array($role, collect($roles->toArray())->pluck('id')->all()) ? $role : 'student';
    }

    /**
     * Detach all of the users from the team and delete the team.
     *
     * @return void
     */
    public function detachUsersAndDestroy()
    {
        parent::detachUsersAndDestroy();
        $this->forceDelete();
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function isAdmin(User $user)
    {
        return $this->isOrganizationAdmin($user);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function isOrganizationAdmin(User $user)
    {
        return $user->isAdminOn($this);
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($value instanceof Team) {
            return $value;
        }

        $value = is_array($value) ? $value['id'] : $value;
        $key = is_numeric($value) ? 'id' : 'slug';
        return $this->where($key, $value)->setEagerLoads([])->first() ?? abort(404);
    }

    /**
     * @inheritDoc
     */
    public static function getFilterableRelations($class = null): array
    {
        return [
            'owner' => true,
        ];
    }
}