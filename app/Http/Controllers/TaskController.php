<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;

use App\Models\Project;
use App\Models\Proj_Mem;
use App\Models\Comment;
use App\Models\Status;
use App\Models\Task_Mem;
use App\Models\Task;
use App\Models\Member;

use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    protected $projectService;
    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;  
    }
    public function getTasks(Request $req){ 
        return response()->json($this->taskService->getTasks($req->all()));
    }
    public function addTask(Request $req){ 
    //    return "hey";
        $validated = Validator::make($req->all(), [ 
            'data.title' => 'required', // min length , max length 
            'data.description' => 'required', // min length , max length
            'comments' => 'required', // min length , max length
            'data.status' => 'required',    // min length , max length 
            'data.project_id' => 'required',     
        ]);
        // return $validated->errors();
        return response()->json($this->taskService->addTask($req));
    }
    public function assignTask(Request $req){ 
        return $this->taskService->assignTask($req->all());
    }
    public function editTask(Request $req){ 
        $validated = $req->validate([ 
            'id' => 'required',
            'title' => 'required', 
            'description' => 'required', 
            'attachment' => 'required',
            'status' => 'required',
            'project_id' => 'required',   
        ]);
        return $this->taskService->editTask($req->all());
    }
    public function delTask(Request $req){ 
        return $this->taskService->delTask($req->all());
    }
    public function members(Request $req){ 
        return $this->taskService->members($req->all());
    }
    public function taskDetails(Request $req){ 
        return response()->json($this->taskService->taskDetails($req->all())) ;
    }
    public function getAssignees(Request $req){ 
        return response()->json($this->taskService->getAssignees($req)) ;
    }
    
}