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
        
        return $this->projectService->getAllProjects($req['member_id']);
    }
    public function createProject(Request $req){
        return $this->projectService->createProject($req->getContent());
    }
    public function addMemberToProject(Request $req){
        return $this->projectService->addMemberToProject($req->getContent());
    }
    public function getMembers(Request $req){ 
        return $this->projectService->getAllMembers($req->getContent());
    }
    public function dashboard(){
        return view('dashboard');
    }
}
