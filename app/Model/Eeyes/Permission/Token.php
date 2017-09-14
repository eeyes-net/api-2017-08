<?php

namespace App\Model\Eeyes\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Token
 *
 * @package App\Model\Eeyes\Permission
 *
 * @property $id
 * @property $token 令牌
 * @property $name 名称
 * @property $description 说明
 * @property $not_before 生效时间
 * @property $not_after 过期时间
 *
 * @property $all_permission_ids
 * @property $all_permissions
 *
 * @property $is_available 是否有效
 */
class Token extends Model
{
    protected $connection = 'mysql_permission';

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function can($slug)
    {
        if ($_SERVER['REQUEST_TIME'] < strtotime($this->not_before)) {
            return 'Token not available now.';
        }
        if (strtotime($this->not_after) < $_SERVER['REQUEST_TIME']) {
            return 'Token expired.';
        }
        $permission = Permission::where('slug', $slug)->first();
        if (is_null($permission)) {
            return 'Permission not exists.';
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

    public function getIsAvailableAttribute()
    {
        return (strtotime($this->not_before) <= $_SERVER['REQUEST_TIME'])
            && ($_SERVER['REQUEST_TIME'] <= strtotime($this->not_after));
    }
}