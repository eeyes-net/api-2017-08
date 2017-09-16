<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiLog
 *
 * @package App
 *
 * @property $id ID
 * @property $path 路径
 * @property $method HTTP方法
 * @property $ip IP
 * @property $user_agent User Agent
 * @property $query Query string
 * @property $body POST body
 * @property $response_length Response content length
 * @property $response_code 返回代码
 * @property $response_msg 返回提示信息
 */
class ApiLog extends Model
{
    //
}
