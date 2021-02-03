<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @Endpoint(name="Login", description="Logs a user in")
     * @BodyParam(name="email", type="string", status="required", description="The users email")
     * @BodyParam(name="password", type="string", status="required", description="Users password")
     * @ResponseExample(status=200, file="responses/user/user.login-200.json")
     * @ResponseExample(status=401, file="responses/user/user.login-401.json")
     */
    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($login))
        {
            return response()->json(['error' => 'Invalid login credentials.'],401);
        }

        $user = Auth::User();
        $accessToken = $user->createToken("auth_token")->plainTextToken;

        return response()->json(["access_token" => $accessToken]);
    }

    /**
     * @Endpoint(name="Register", description="Registers a user")
     * @BodyParam(name="email", type="string", status="required", description="The users email")
     * @BodyParam(name="password", type="string", status="required", description="Users password")
     * @ResponseExample(status=200, file="responses/user/user.register-200.json")
     */
    public function Register(Request $request)
    {
        $register = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            "name" => "required|string",
        ]);

        $user = User::create([
            'email' => $register['email'],
            'password' =>  Hash::make($register['password']),
            'name' => $register["name"],
        ]);

        $accessToken = $user->createToken("auth_token")->plainTextToken;

        return response()->json(["access_token" => $accessToken]);

    }
}
