<?php

namespace App\Http\Traits;

use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait LoginTrait
{
    public function loginUser(Request $request, $isAdmin = false)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if($isAdmin && !$user->is_admin)
            {
                return $this->errorResponse("Unauthorized access", 401);
            }

            if(!$isAdmin && $user->is_admin)
            {
                return $this->errorResponse("Unauthorized access", 401);
            }

            $service = new JwtService();

            $token = $service->issueToken($user);

            return $this->successResponse(['token' => $token->toString()], 200);
        }

        return $this->errorResponse("Unauthorized access", 401);
    }


    public function logoutUser()
    {
        //get user from the bearer token
        $service = new JwtService();

        $bearer = $service->extractBearerToken(request());

        if ($bearer) {
            $user = $service->getUserFromBearer($bearer);

            $user?->jwt_tokens()->delete();
        }


        return $this->successResponse('Logged out successfully', 200);
    }

}