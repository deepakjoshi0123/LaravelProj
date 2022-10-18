<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MemberAuthController extends Controller
{
    public function login(){
        $credentials = request(['email', 'password']);
       if (! $token = auth()->attempt($credentials)) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       return $this->respondWithToken($token);
    }
    public function me()
   {   echo "hey";
       return response()->json(auth()->user());
   }
   public function logout()
   {
       auth()->logout();
 
       return response()->json(['message' => 'Successfully logged out']);
   }

   public function refresh()
   {
       return $this->respondWithToken(auth()->refresh());
   }
   protected function respondWithToken($token)
   {
       return response()->json([
           'access_token' => $token,
           'token_type' => 'bearer',
           'expires_in' => auth()->factory()->getTTL() * 60
       ]);
   }
    public function register(Request $request){
        $memberData = $request->all();
        $member = Member::create($memberData);
        return $member;
    }

}
