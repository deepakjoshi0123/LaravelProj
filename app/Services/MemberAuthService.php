<?php
namespace App\Services;
use App\Models\Comment;
use Illuminate\Http\Request;
use Response;
use Carbon\Carbon;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
Class MemberAuthService{
    public function register($req){
        $memberData = $req->all();
        $member = Member::create($memberData);
        $member = Member::where('email',$req['email'])->first();
        $member->verification_token = str_random(32);
        $member->save();

        // $memberData->save();
        Mail::send('mail.register-mail',$member->toArray(), function ($message) {
            $message->to('deepakjoshi0123@gmail.com','trello clone')
            ->subject('mailtrap test');
        });
        return ([
            'status' => 'OK',
            'message' => 'Member added Successfully'
        ]);

    }
    public function verifyMember($token){
        $member = Member::where([['verification_token',$token],["is_verfied","0"]])->first();
        if($member == null){
            return "Invalid request OR invalid token";
        }
        $member->is_verfied = "1";
        $member->save();
        return "verified successfully";
    }

    public function change_password_view($req,$key){
        $member = Member::where([
            ['reset_token',$key],
            ['token_expiry','>=',Carbon::now('Asia/Kolkata')]
        ])->first();
        if($member === null){
            return "Link expired OR Invalid Req";
        }
            return view('changePassword');
    }
    public function sendResetLink($req){
       
        $member = Member::where('email',$req['email'])->first();
        $member->reset_token = str_random(32);
        $member->token_expiry = Carbon::now('Asia/Kolkata')->addMinutes(5);
        $member->save();
        $token = array('token' => $member->reset_token);
        
        Mail::send('mail.password-reset-link',$token, function ($message) {
            $message->to('deepakjoshi0123@gmail.com','trello clone')
            ->subject('password reset link');
        });
        return ([
            'status' => 'OK',
        ]);
    }

    public function changePassword($req){
        $member = Member::where('reset_token',$req->get('token'))->first();

        $member->password = $req['password'];
        $member->save();

        if($member == null){
            return ["Invalid Request OR token expired"];
        }
        return response()->json($member);
    }
    public function login($req){
        // return 'hi login';
        $credentials = request(['email', 'password']);
        if (Auth::attempt($credentials)) {
                 // dd(Auth::check());
                 $req->session()->put('userid',Auth::id());
                 // Auth::logoutOtherDevices(request('password'));
                 return redirect('dashboard');
        }
        return redirect('login')->withErrors(['unauthorized' => 'Unauthorized']);
    }
    // public function getToken($req){
       
    // }
}