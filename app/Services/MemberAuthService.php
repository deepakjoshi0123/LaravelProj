<?php
namespace App\Services;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Services\MailService;
use Response;
use Carbon\Carbon;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

Class MemberAuthService{

    public function register($req){
        $memberData = $req->all();
        $member = Member::create($memberData);
        $member->verification_token = str_random(32);
        $member->save();

        MailService::sendMail('mail.register-mail',$member->toArray(),$member->email,'Registration verification');
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
            return redirect('register');
        }
            return view('changePassword');
    }
    public function sendResetLink($req){
       
        $member = Member::where('email',$req['email'])->first();
        $member->reset_token = str_random(32);
        $member->token_expiry = Carbon::now('Asia/Kolkata')->addMinutes(5);
        $member->save();
        $token = array('token' => $member->reset_token);
        
        MailService::sendMail('mail.password-reset-link',$token,$member->email,'Password-reset');
        return ([
            'status' => 'OK',
        ]);
    }

    public function changePassword($req){
        $member = Member::where('reset_token',$req->get('token'))->first();

        $member->password = $req['password'];
        $member->save();
        //Extra Check
        if($member == null){
            return redirect('register');
        }
        return response()->json($member);
    }

    public function login($req){
        $credentials = request(['email', 'password']);
        if (Auth::attempt($credentials)) {
                 $req->session()->put('userid',Auth::id());
                 // Auth::logoutOtherDevices(request('password'));
                 return redirect('dashboard')->withCookie('jwt-token',auth()->attempt($credentials),60,"/", null, false, false);
                 //last 3 flags are for path , secure and http only for cookie
        }
        // ajax - automatic sending 
        return redirect('login')->withErrors(['unauthorized' => 'Unauthorized']);
    }
}