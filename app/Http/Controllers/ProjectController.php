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
    public function getProjects(Request $req){  //custom req class and pass it the functon arguments 
        return $this->projectService->getAllProjects($req->getContent());
    }
    public function createProject(Request $req){
        return $this->projectService->createProject($req->getContent());
    }
}
