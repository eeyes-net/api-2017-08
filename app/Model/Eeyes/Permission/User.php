<?php

namespace App\Model\Eeyes\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package App\Model\Eeyes\Permission
 *
 * @property $id
 * @property $username
 * @property $password
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @property $all_permission_ids
 * @property $all_permissions
 */
class User extends Model
{
    protected $connection = 'mysql_permission';
    protected $hidden = [
        'password',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function can($slug)
    {
        $permission = Permission::where('slug', $slug)->first();
        if (is_null($permission)) {
            return null;
        }
        return $this->hasPermissionById($permission->id);
    }

    public function hasPermissionById($permission_id)
    {
        $roles = $this->roles()->get();
        $except_role_ids = [];
        /** @var Role $role */
        foreach ($roles as $role) {
            $except_role_ids[] = $role->id;
            if ($role->hasPermissionById($permission_id, $except_role_ids)) {
                return true;
            }
        }
        return false;
    }

    public function getAllPermissionIdsAttribute()
    {
        $permission_ids = [];
        $except_role_ids = [];
        $roles = $this->roles()->get();
        /** @var Role $role */
        foreach ($roles as $role) {
            if (in_array($role->id, $except_role_ids)) {
                continue;
            }
            $except_role_ids[] = $role->id;
            $permission_ids = array_merge($permission_ids, $role->getPermissionIdsRecursive($except_role_ids));
        }
        return array_values(array_unique($permission_ids));
    }

    public function getAllPermissionsAttribute()
    {
        return Permission::find($this->all_permission_ids);
    }

}