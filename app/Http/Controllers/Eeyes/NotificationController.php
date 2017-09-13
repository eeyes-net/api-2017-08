<?php

namespace App\Http\Controllers\Eeyes;

use App\Library\Eeyes\DingTalkRobot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class NotificationController
{
    public function dingTalk(Request $request)
    {
        $access_token = env('EEYES_DING_TALK_ROBOT_ACCESS_TOKEN', '');
        if (empty($access_token)) {
            throw new Exception('Empty EEYES_DING_TALK_ROBOT_ACCESS_TOKEN.');
        }
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($request->all(), [
            'content' => 'required|max:4096',
        ]);
        if ($validator->fails()) {
            return build_api_return([
                'errors' => $validator->errors(),
            ], 400, 'Validate input data failed.');
        }
        $dingTalkRobot = new DingTalkRobot($access_token);
        $result = $dingTalkRobot->send($request->input('content'));
        if ($result) {
            return build_api_return();
        } else {
            return build_api_return(null, 502, 'Unknown error');
        }
    }
}