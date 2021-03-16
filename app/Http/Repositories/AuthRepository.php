<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Traits\ApiResponse;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Hash;
use App\Rules\MatchOldPassword;

class AuthRepository implements AuthInterface
{
    use ApiResponse;

    public function login($request)
    {
        $req = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($req->fails()) {
            return $this->apiResponse(422, 'ValidatorErrors', $req->errors() );
        }

        $credentials = $request->only('email', 'password');

        if ($token = JWTAuth::attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return $this->apiResponse(401,'Unauthorized');


    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = auth()->user();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role_id' => $user->role_id,
            'role_name' => $user->role->name,
            'token' => $token,
        ];
        return $this->apiResponse(200, 'User data', null, $data);

        /*return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);*/
    }


    public function updatePassword($request)
    {
        $validation = Validator::make($request->all(), [
            'old_password' => ['required', new MatchOldPassword],
            'new_password' => 'required|min:6',
        ]);
        if($validation->fails()){
            return $this->apiResponse(422, 'Error', $validation->errors());
        }
        $user = auth()->user();
        /*if( !Hash::check($request->old_password, $user->password)){
            return $this->apiResponse(422, 'Wrong old password');
        }*/
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        return $this->apiResponse(200, 'Password updated successfully');

    }

}
