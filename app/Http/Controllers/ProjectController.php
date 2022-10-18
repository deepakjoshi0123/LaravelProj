<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProjectService;

class ProjectController extends Controller
{ 
    protected $projectService;
    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;  
    }
    public function getProjects(Request $req){  //custom req class and pass it the functon arguments for validation
        
        $validated = $req->validate([ 
            'member_id' => 'required', 
        ]);
        return response()->json(($this->projectService->getAllProjects($req->all())));
    }
    public function createProject(Request $req){
        $validated = $req->validate([ 
            'name' => 'required', 
            'owner' => 'required'
        ]);
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
    public function dashboard(){
        return view('dashboard');
    }
}
