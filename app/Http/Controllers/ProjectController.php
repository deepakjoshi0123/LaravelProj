<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\Proj_Mem;

class ProjectController extends Controller
{ 
    protected $projectService;
    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;  
    }
    public function getProjects(Request $req){  //custom req class and pass it the functon arguments for validation
        
        // $validated = $req->validate([ 
        //     'member_id' => 'required', 
        // ]);
        //all json routes in api
        //all html templetes web
        return response()->json(($this->projectService->getAllProjects($req->all())));
    }
    public function createProject(Request $req){
        $validated = Validator::make($req->all(), [ 
            'name' => 'required|string|min:3|max:20|regex:/^([A-Za-z\d\.-]+)$/',    
        ]);
        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST);
        }
        return response()->json(($this->projectService->createProject($req->all())));
        
    }
   
    public function getMembers(Request $req){ 
        $validated = $req->validate([ 
            'project_id' => 'required'
        ]);
        return response()->json(($this->projectService->getAllMembers($req->all())));
    }
    public function dashboard(Request $req){
      
        if($req->session()->get('userid')){
            return view('dashboard');
        }
        return redirect('login');        
    }

    public function shareProject(Request $req){
        $validated = Validator::make($req->all(), [
            'email' => 'required|email',
        ]);
        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST);
        }
        
        $email = Member::where('email',$req['email'])->get(['id']);
        if($email->count()===0){
            return response()->json(['No email address found in our database'], Response::HTTP_BAD_REQUEST);
        }
        
        $alreadyShared = Proj_Mem::where([
        ['member_id',$email[0]->id],
        ['project_id',$req['project_id']]    
        ])->get();
    
        if($alreadyShared->count()!==0){
            return response()->json(['project already shared to this user'], Response::HTTP_BAD_REQUEST);
        }

        (new Proj_Mem())->fill([
            'project_id' => $req['project_id'],
            'member_id' => $email[0]->id,
          ])->save() ;

    }
}
