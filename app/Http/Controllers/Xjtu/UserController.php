<?php

namespace App\Http\Controllers\Xjtu;

use App\Http\Controllers\Controller;
use App\Library\Xjtu\XjtuUserInfo;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function getUserInfo(Request $request)
    {
        $xjtuUserInfo = new XjtuUserInfo(config('xjtu.user_info.url'), config('xjtu.user_info.auth'));
        if ($request->has('net_id')) {
            return $xjtuUserInfo->getInfoByNetId($request->input('net_id'));
        } elseif ($request->has('stu_id')) {
            return $xjtuUserInfo->getInfoByStuId($request->input('stu_id'));
        } elseif ($request->has('name')) {
            return $xjtuUserInfo->getInfoByName($request->input('name'));
        } elseif ($request->has('mobile')) {
            return $xjtuUserInfo->getInfoByMobile($request->input('mobile'));
        } else {
            return null;
        }
    }

    function getUserPhoto(Request $request)
    {
        $jpg = config('xjtu.user_photo')($request->get('stu_id'));
        return response($jpg, 200, [
            'Content-Type' => 'image/jpeg',
        ]);
    }
}
