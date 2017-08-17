<?php

namespace App\Library\Eeyes;

class EeyesAdmin
{
    /**
     * 判断某CAS用户是否具有某权限
     *
     * @param string $username 用户名(NetId)
     * @param string $permission 权限代号
     *
     * @return array ['can' => mixed, 'msg' => string]
     */
    public static function permission($username, $permission)
    {
        return json_decode(file_get_contents(config('eeyes.admin.url') . '/api/permission/can?' . http_build_query([
                'username' => $username,
                'permission' => $permission,
            ])), true);
    }

    /**
     * 获取token对应的CAS用户名
     *
     * @param string $token 令牌
     *
     * @return array ['username' => string|null, 'msg' => string]
     */
    public static function token($token)
    {
        return json_decode(file_get_contents(config('eeyes.admin.url') . '/api/token?' . http_build_query([
                'token' => $token,
            ])), true);
    }
}