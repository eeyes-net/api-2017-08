<?php

/**
 * 构建标准API返回格式对象
 *
 * @param null $data
 * @param int $code
 * @param string $msg
 *
 * @return array
 */
function build_api_return($data = null, $code = 200, $msg = 'OK')
{
    return [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];
}
