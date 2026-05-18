<?php

namespace App\Models\Concerns;

use App\Models\AuthGroupUser;
use App\Models\AuthPermissionUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Authorizable
{
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function groups(): HasMany
    {
        /** @var Model $this */

        return $this->hasMany(AuthGroupUser::class);
    }

    public function permissions(): HasMany
    {
        /** @var Model $this */

        return $this->hasMany(AuthPermissionUser::class);
    }

    /*
    |--------------------------------------------------------------------------
    | GROUPS
    |--------------------------------------------------------------------------
    */

    public function getGroups(): array
    {
        return $this->groups()
            ->pluck('group')
            ->toArray();
    }

    public function inGroup(string ...$groups): bool
    {
        return $this->groups()
            ->whereIn('group', $groups)
            ->exists();
    }

    public function addGroup(string ...$groups): static
    {
        $availableGroups = array_keys(
            (array) config('auth_group.groups')
        );

        foreach ($groups as $group) {

            if (! in_array($group, $availableGroups)) {
                continue;
            }

            AuthGroupUser::firstOrCreate([
                'user_id' => $this->getKey(),
                'group' => $group,
            ]);
        }

        return $this;
    }

    public function removeGroup(string ...$groups): static
    {
        $this->groups()
            ->whereIn('group', $groups)
            ->delete();

        return $this;
    }

    public function syncGroups(string ...$groups): static
    {
        $this->groups()->delete();

        $this->addGroup(...$groups);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */

    public function getPermissions(): array
    {
        return $this->permissions()
            ->pluck('permission')
            ->toArray();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()
            ->where('permission', $permission)
            ->exists();
    }

    public function addPermission(string ...$permissions): static
    {
        $availablePermissions = array_keys(
            (array) config('auth_permission.permissions')
        );

        foreach ($permissions as $permission) {

            if (! in_array($permission, $availablePermissions)) {
                continue;
            }

            AuthPermissionUser::firstOrCreate([
                'user_id' => $this->getKey(),
                'permission' => $permission,
            ]);
        }

        return $this;
    }

    public function removePermission(string ...$permissions): static
    {
        $this->permissions()
            ->whereIn('permission', $permissions)
            ->delete();

        return $this;
    }

    public function syncPermissions(string ...$permissions): static
    {
        $this->permissions()->delete();

        $this->addPermission(...$permissions);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    public function canAccess($permissions): bool
    {
        $permissions = is_array($permissions)
            ? $permissions
            : func_get_args();

        /*
        |--------------------------------------------------------------------------
        | DIRECT PERMISSIONS
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $permission) {

            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | GROUP PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $matrix = (array) config('auth_permission.matrix');

        foreach ($this->getGroups() as $group) {

            $groupPermissions = $matrix[$group] ?? [];

            foreach ($permissions as $permission) {

                if (
                    $this->matchesPermission(
                        $permission,
                        $groupPermissions
                    )
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    public function cannotAccess($permissions): bool
    {
        return ! $this->canAccess(...func_get_args());
    }

    /*
    |--------------------------------------------------------------------------
    | WILDCARD MATCHING
    |--------------------------------------------------------------------------
    */

    protected function matchesPermission(
        string $permission,
        array $grantedPermissions
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | FULL ACCESS
        |--------------------------------------------------------------------------
        */

        if (in_array('*', $grantedPermissions)) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | EXACT MATCH
        |--------------------------------------------------------------------------
        */

        if (in_array($permission, $grantedPermissions)) {
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | WILDCARD MATCH
        |--------------------------------------------------------------------------
        */

        foreach ($grantedPermissions as $granted) {

            if (str_ends_with($granted, '.*')) {

                $scope = str_replace('.*', '', $granted);

                if (
                    str_starts_with(
                        $permission,
                        $scope . '.'
                    )
                ) {
                    return true;
                }
            }
        }

        return false;
    }
}