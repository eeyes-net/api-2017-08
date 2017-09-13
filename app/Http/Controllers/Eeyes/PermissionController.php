<?php

namespace App\Http\Controllers\Eeyes;

use App\Http\Controllers\Controller;
use App\Model\Eeyes\Permission\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function checkByUsername(Request $request)
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:190',
            'permission' => 'required|max:190',
        ]);
        if ($validator->fails()) {
            return build_api_return([
                'errors' => $validator->errors(),
            ], 400, 'Validate input data failed.');
        }
        $user = User::where('username', $request->get('username'))->first();
        if (!$user) {
            return build_api_return([
                'can' => false,
                'msg' => 'User not exists.',
            ]);
        }
        $result = $user->can($request->get('permission'));
        if (is_null($result)) {
            return build_api_return([
                'can' => false,
                'msg' => 'Permission not exists.',
            ]);
        } elseif (false === $result) {
            return build_api_return([
                'can' => false,
                'msg' => 'Forbidden',
            ]);
        } else {
            return build_api_return([
                'can' => true,
                'msg' => 'OK',
            ]);
        }
    }

    public function checkByToken(Request $request)
    {
        // TODO
        return build_api_return(null, 404, 'TODO');
    }
}