<?php

namespace App\Http\Controllers\Xjtu;

use App\Http\Controllers\Controller;
use App\Library\Xjtu\XjtuUserInfo;
use App\Library\Xjtu\XjtuUserPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function casLogin(Request $request)
    {

    }

    function getUserInfo(Request $request)
    {
        $url = env('XJTU_USER_INFO_URL', '');
        $auth = env('XJTU_USER_INFO_AUTH', '');
        if (empty($url) || empty($auth)) {
            throw new \Exception('Empty XJTU_USER_INFO_URL or XJTU_USER_INFO_AUTH.');
        }
        $xjtuUserInfo = new XjtuUserInfo($url, $auth);
        if ($request->has('net_id')) {
            return build_api_return($xjtuUserInfo->getInfoByNetId($request->input('net_id')));
        } elseif ($request->has('stu_id')) {
            return build_api_return($xjtuUserInfo->getInfoByStuId($request->input('stu_id')));
        } elseif ($request->has('name')) {
            return build_api_return($xjtuUserInfo->getInfoByName($request->input('name')));
        } elseif ($request->has('mobile')) {
            return build_api_return($xjtuUserInfo->getInfoByMobile($request->input('mobile')));
        } else {
            return build_api_return(null, 400, 'Must provide net_id or stu_id or name or mobile.');
        }
    }

    function getUserPhoto(Request $request)
    {
        $url = env('XJTU_USER_PHOTO_URL', '');
        $auth = env('XJTU_USER_PHOTO_AUTH', '');
        if (empty($url) || empty($auth)) {
            throw new \Exception('Empty XJTU_USER_PHOTO_URL or XJTU_USER_PHOTO_AUTH.');
        }

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($request->all(), [
            'stu_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return build_api_return([
                'errors' => $validator->errors(),
            ], 400, 'Validate input data failed.');
        }

        $xjtuUserPhoto = new XjtuUserPhoto($url, $auth);
        $jpg = $xjtuUserPhoto->getPhotoByStuId($request->get('stu_id'));

        if ('base64' === $request->get('format', null)) {
            return build_api_return([
                'data' => base64_encode($jpg),
                'contentType' => 'image/jpeg',
            ]);
        }
        return response($jpg, 200, [
            'Content-Type' => 'image/jpeg',
        ]);
    }
}
