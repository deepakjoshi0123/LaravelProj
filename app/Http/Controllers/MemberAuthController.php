<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Services\MemberAuthService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Encryption\Encrypter;
use Config;
use Illuminate\Support\Facades\Crypt;
use Dirape\Token\Token;
use Carbon\Carbon;
use Session;

class MemberAuthController extends Controller
{
    protected $memberAuthService;
    public function __construct(MemberAuthService $memberAuthService) {
        $this->memberAuthService = $memberAuthService;  
    }
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
            'email' => 'required|unique:members,email|regex:/^([A-Za-z\d\.-]+)@([A-Za-z\d-]+)\.([A-Za-z]{2,8})(\.[A-Za-z]{2,8})?$/', 
            'first_name' => 'required|min:3|max:20|regex:/^([A-Za-z\d\.-]+)$/',
            'last_name' => 'required||min:3|max:20|regex:/^([A-Za-z\d\.-]+)$/', 
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
        return response()->json($this->memberAuthService->register($req));
    }
   
    public function me(Request $req)
    {   
        return response()->json(["id"=>$req['user']['id'],"first_name"=>$req['user']['first_name'],"last_name"=>$req['user']['last_name']]);
    }
   
   public function logout()
     {    
       Session::flush();
       return redirect('login');
     }

   public function refresh()
   { 
       return response()->json(['status'=>true])->withCookie('jwt-token',auth()->refresh(),60,"/", null, false, false);
   }

    public function verifyMember($token){
        return response()->json($this->memberAuthService->verifyMember($token));
    }

    public function Enter_Email_view(){
        return view('enterEmailView');
    }
    // handle exception
    public function change_password_view(Request $req , $key){
        return $this->memberAuthService->change_password_view($req,$key);
    }

    public function sendRestLink(Request $req){
        $validated = Validator::make($req->all(), [ 
            'email' => 'required|email|regex:/^([A-Za-z\d\.-]+)@([A-Za-z\d-]+)\.([A-Za-z]{2,8})(\.[A-Za-z]{2,8})?$/', 
        ]);
       
        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST) ;
        }
        $email = Member::where('email',$req['email'])->get();
        if($email->count()===0){
            return response()->json(['Email not found in db'], Response::HTTP_BAD_REQUEST);
        }
        return $this->memberAuthService->sendResetLink($req);  
    }
       
    public function changePassword(Request $req){ 
        $validated = Validator::make($req->all(), [ 
            'token' => 'required', 
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
            return $this->memberAuthService->changePassword($req);  
        }

        public function login(Request $req){
            $validated = Validator::make($req->all(), [ 
                'email' => 'required|regex:/^([A-Za-z\d\.-]+)@([A-Za-z\d-]+)\.([A-Za-z]{2,8})(\.[A-Za-z]{2,8})?$/', 
                'password' => 'required'
            ]);

            if ($validated->fails()) {    
                return redirect('login')->withErrors($validated->messages());
            }
            return $this->memberAuthService->login($req);  
         }
}

