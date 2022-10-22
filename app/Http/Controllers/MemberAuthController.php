<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Mail;

class MemberAuthController extends Controller
{
    public function login_view(){
        return view('login');
    }
    public function register_view(){
        return view('register');
    }
    public function login(Request $req){
        $validated = $req->validate([ 
            'email' => 'required|email', 
            'password' => 'required'
        ]);
        $credentials = request(['email', 'password']);
       if (! $token = auth()->attempt($credentials)) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
       return $this->respondWithToken($token);
    }
    public function me()
   {   
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
        // $validated = $request->validate([ 
        //     'email' => 'required|email', 
        //     'password' => 'required',
        //     'first_name' => 'required',
        //     'last_name' => 'required|email', 
        //     'is_verfied' => 'required'
        // ]);
        // return $request->all();
        $memberData = $request->all();
        $member = Member::create($memberData);
        Mail::send('mail.register-mail',$memberData, function ($message) {
            $message->to('deepakjoshi0123@gmail.com','trello clone')
            ->subject('mailtrap test');
        });
        return $member;
    }

    public function verifyMember($token){
        $member = Member::where('email',$token)->first();
        $member->is_verfied = "1";
        $member->save();
        return response()->json($member);
    }

}
