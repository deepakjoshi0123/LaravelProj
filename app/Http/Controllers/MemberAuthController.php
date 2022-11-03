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
            'is_verfied' => 'required',
            'password' => 'required_with:cnf-password|same:cnf-password',
            'cnf-password' => 'required'
        ]);
       
        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST);
        }
        
        $memberData = $req->all();
        $member = Member::create($memberData);
        Mail::send('mail.register-mail',$memberData, function ($message) {
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
       return response()->json(['message' => 'Successfully logged out']);
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
        $member = Member::where('email',$token)->first();
        $member->is_verfied = "1";
        $member->save();
        return response()->json($member);
    }

    public function Enter_Email_view(){
        return view('enterEmailView');
    }
    public function change_password_view(){
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
        Mail::send('mail.password-reset-link',$req->all(), function ($message) {
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
        $member = Member::where('email',$req['token'])->first();
        $member->password = $req['password'];
        $member->save();
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
           
            if ($validated->fails()) {    
                return redirect('login')->withErrors($validated->messages());
            }
            
           $credentials = request(['email', 'password']);
           if ($token = auth()->attempt($credentials)) {
                return $token;
            }
            return response()->json(['unauthorized' => 'Unauthorized']);
         }
    
}

