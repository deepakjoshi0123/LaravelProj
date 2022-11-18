<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\TaskService;

use App\Models\Project;
use App\Models\Proj_Mem;
use App\Models\Comment;
use App\Models\Status;
use App\Models\Task_Mem;
use App\Models\Task;
use App\Models\Member;

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
              
        $validated = Validator::make(json_decode($req['data'],true), [ 
            'data.title' => 'required|string|min:3|max:20', 
            'data.description' => 'required|string|min:3|max:200', 
            'comments' => 'nullable', 
            'data.status' => 'nullable|string',    
        ]);
        if ($validated->fails()) {    
            return response()->json($validated->messages(), Response::HTTP_BAD_REQUEST);
        }
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
    public function searchTask(Request $req){
        return response()->json(($this->taskService->searchTask($req->all())));
    }
    public function filterTask(Request $req){
        return response()->json(($this->taskService->filterTask($req->all())));
    }
    public function downloadTaskAttachment(Request $req,$file_name){
        // return 'media/'.$file_name;
        return response()->download('media/'.$file_name);
    }
    public function viewTaskAttachment(Request $req,$file_name){
        return response()->file('media/'.$file_name);
    }
}