<?php

namespace App\Model\Eeyes\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @package App\Model\Eeyes\Permission
 *
 * @property $id
 * @property $slug
 * @property $name
 * @property $created_at
 * @property $updated_at
 */
class Permission extends Model
{
    protected $connection = 'mysql_permission';
}