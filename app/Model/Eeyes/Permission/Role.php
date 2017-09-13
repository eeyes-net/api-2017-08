<?php

namespace App\Model\Eeyes\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @package App\Model\Eeyes\Permission
 *
 * @property $id
 * @property $slug
 * @property $name
 * @property $created_at
 * @property $updated_at
 */
class Role extends Model
{
    protected $connection = 'mysql_permission';

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function extend_roles()
    {
        return $this->belongsToMany(Role::class, null, 'role_id', 'extend_id');
    }

    public function hasPermissionById($permission_id, &$except_role_ids = [])
    {
        if (in_array($permission_id, $this->permissions()->pluck('id')->toArray())) {
            return true;
        }
        $roles = $this->extend_roles()->get();
        /** @var Role $role */
        foreach ($roles as $role) {
            if (in_array($role->id, $except_role_ids)) {
                continue;
            }
            $except_role_ids[] = $role->id;
            if ($role->hasPermissionById($permission_id, $except_role_ids)) {
                return true;
            }
        }
        return false;
    }

    public function getPermissionIdsRecursive(&$except_role_ids = [])
    {
        $permission_ids = $this->permissions()->pluck('id')->toArray();
        $roles = $this->extend_roles()->get();
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
}