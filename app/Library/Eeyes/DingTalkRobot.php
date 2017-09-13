<?php
/**
 * Created by PhpStorm.
 * User: ganlv
 * Date: 17-9-13
 * Time: 下午1:10
 */

namespace App\Library\Eeyes;


use Illuminate\Support\Facades\Log;

class DingTalkRobot
{
    protected $access_token = '';

    public function __construct($access_token)
    {
        $this->access_token = $access_token;
    }

    public function send($content)
    {
        $url = 'https://oapi.dingtalk.com/robot/send?' . http_build_query([
                'access_token' => $this->access_token,
            ]);
        $data = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content,
            ],
        ];
        $post_fields = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (env('APP_DEBUG', false)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response_content = curl_exec($ch);
        curl_close($ch);

        if (false === $response_content) {
            Log::error('CURL return false. ' . curl_getinfo($ch));
            return false;
        }
        return true;
    }
}