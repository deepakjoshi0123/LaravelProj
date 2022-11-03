<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
            'name' => 'required|string|min:3|max:20',    
        ]);
        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST);
        }
        return response()->json(($this->projectService->createProject($req->all())));
        
    }
    public function addMemberToProject(Request $req){
        $validated = $req->validate([ 
            'project_id' => 'required', 
            'member_id' => 'required'
        ]);
        return response()->json(($this->projectService->addMemberToProject($req->all())));
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
}
