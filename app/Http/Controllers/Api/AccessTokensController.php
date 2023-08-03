<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Egulias\EmailValidator\Parser\IDLeftPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Monolog\Handler\IFTTTHandler;

class AccessTokensController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'device_name' => 'string|max:255',
            'abilities'=>'nullable|array'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $device_name = $request->post('device_name', $request->userAgent());
            $token = $user->createToken($device_name,$request->post('abilities'));

            return Response::json([
                'code' => 100,
                'token' => $token->plainTextToken,
                'user' => $user,
            ], 201);//201 Buc it's create for token , not create
        }

        return Response::json([
            'code'=>0,
            'message'=>'Invalid Credentials'
        ],401);
    }

    public function destroy($token = null)
    {

        // Revoke all tokens
        // $user->tokens()->delete();

        $user = Auth::guard('sanctum')->user();
        if (null == $token){
            $user->currentAccessToken()->delete();
            return;
        }

        $personalAccessToken = PersonalAccessToken::findToken($token);
        if ($user->id == $personalAccessToken->tokenable_id && get_class($user) == $personalAccessToken->tokenable_type){
            $personalAccessToken->delete();
        }
    }
}
