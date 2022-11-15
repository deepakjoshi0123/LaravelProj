<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Dirape\Token\Token;
use Carbon\Carbon;
use Session;

class MemberAuthController extends Controller
{
    public function login_view(Request $req){
        if($req->session()->get('userid')){
            return redirect('dashboard');
        }
        return view('login');
    }
    public function register_view(Request $req){
        
        if($req->session()->get('userid')){
            return redirect('dashboard');
        }
        return view('register');
    }

    public function register(Request $req){

        $validated = Validator::make($req->all(), [
            'email' => 'required|email', 
            'first_name' => 'required|min:3|max:20',
            'last_name' => 'required||min:3|max:20', 
            'password' => [
                'required','same:cnf-password',
                'min:6',             
            ],
            'cnf-password' => [
                'required',
                'min:6',             
            ],
        ]);
       
        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST);
        }
        
        $email = Member::where('email',$req['email'])->get();
        if($email->count()!==0){
            return response()->json(['email already exists,register with another email'], Response::HTTP_BAD_REQUEST);;
        }

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
        return $member;
    }

   
    public function me()
   {   
       return response()->json(auth()->user());
   }
   public function logout()
   {
    //    Auth::logout();
       Session::flush();
       return redirect('login');
   }

   public function refresh()
   { 
       return auth()->refresh();
   }

   protected function respondWithToken($token)
   {
       return response()->json([
           'access_token' => $token,
           'token_type' => 'bearer',
           'expires_in' => auth()->factory()->getTTL() 
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

    public function Enter_Email_view(){
        return view('enterEmailView');
    }
    // handle exception
    public function change_password_view(Request $req , $key){
        $member = Member::where([
            ['reset_token',$key],
            ['token_expiry','>=',Carbon::now('Asia/Kolkata')]
        ])->first();

        if($member === null){
            return "Link expired OR Invalid Req";
        }

            return view('changePassword');
    }

    public function sendRestLink(Request $req){
        
        $validated = Validator::make($req->all(), [ 
            'email' => 'required|email', 
        ]);
       

        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST);
        }
        $email = Member::where('email',$req['email'])->get();
        if($email->count()===0){
            return response()->json(['email not found in db'], Response::HTTP_BAD_REQUEST);;
        }
        $member = Member::where('email',$req['email'])->first();
        $member->reset_token = str_random(32);
        $member->token_expiry = Carbon::now('Asia/Kolkata')->addMinutes(5);
        $member->save();
        $token = array('token' => $member->reset_token);
        
        Mail::send('mail.password-reset-link',$token, function ($message) {
            $message->to('deepakjoshi0123@gmail.com','trello clone')
            ->subject('password reset link');
        });
    }
       public function changePassword(Request $req){
        
        $validated = Validator::make($req->all(), [ 
            'token' => 'required', 
            'password' => 'required_with:cnf-password|same:cnf-password',
            'cnf-password' => 'required'
            
        ]);

        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST);
        }
       
        $member = Member::where('reset_token',$req->get('token'))->first();

        $member->password = $req['password'];
        $member->save();

        if($member == null){
            return ["Invalid Request OR token expired"];
        }
       
        return response()->json($member);
        }


        public function login(Request $req){
            $validated = Validator::make($req->all(), [ 
                'email' => 'required|email', 
                'password' => 'required'
            ]);
           
            if ($validated->fails()) {    
                return redirect('login')->withErrors($validated->messages());
            }
            
           $credentials = request(['email', 'password']);
           if (Auth::attempt($credentials)) {
                    // dd(Auth::check());
                    $req->session()->put('userid',Auth::id());
                   
                    return redirect('dashboard');
           }
           return redirect('login')->withErrors(['unauthorized' => 'Unauthorized']);
         }
        
         public function getToken(Request $req){
            $validated = Validator::make($req->all(), [ 
                'email' => 'required|email', 
                'password' => 'required'
            ]);
           
            // if ($validated->fails()) {    
            //     return redirect('login')->withErrors($validated->messages());
            // }
            
           $credentials = request(['email', 'password']);
           if ($token = auth()->attempt($credentials)) {
                return $token;
            }
            return response()->json(['unauthorized' => 'Unauthorized']);
         }
    
}

