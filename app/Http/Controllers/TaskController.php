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

class TaskController extends Controller
{
    protected $projectService;
    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;  
    }
    public function getTasks(Request $req){ 
        return $this->taskService->getTasks($req->getContent());
    }
    public function addTask(Request $req){ 
        return $this->taskService->addTask($req->getContent());
    }
    public function assignTask(Request $req){ 
        return $this->taskService->assignTask($req->getContent());
    }
    public function editTask(Request $req){ 
        return $this->taskService->editTask($req->getContent());
    }
    public function delTask(Request $req){ 
        return $this->taskService->delTask($req->getContent());
    }
    public function members(Request $req){ 
        return $this->taskService->members($req->getContent());
    }
}