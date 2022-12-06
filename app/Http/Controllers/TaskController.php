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
            // return $req['file'];
            $fls=array();
            $extensionFlag = false;
            $sizeFlag = false;
            $allowedExtension = array("jpg", "jpeg", "png");
            if($req['files']){
                foreach($req['files'] as $file){
                    $extension = $file->getClientOriginalExtension();
                    if($file->getSize() >150000) {
                        // return $file->getSize() ;
                        $sizeFlag = true;
                    }
                    if(!in_array($extension, $allowedExtension)) {
                        $extensionFlag = true;
                    }
                }
            }
            
            $validated = Validator::make(json_decode($req['data'],true), [ 
                'data.title' => 'required|string|min:3|max:40|', 
                'data.description' => 'required|string|min:3|max:200', 
                'comments' => 'nullable', 
                'data.status' => 'nullable|string',    
            ]);
           
            if ($validated->fails()) {  
                return response()->json([$validated->messages(),"sizeFlag"=>$sizeFlag,"extensionFlag"=>$extensionFlag],Response::HTTP_BAD_REQUEST);
            }
            if($sizeFlag === true || $extensionFlag === true){
                return response()->json([$validated->messages(),"sizeFlag"=>$sizeFlag,"extensionFlag"=>$extensionFlag],Response::HTTP_BAD_REQUEST);
            }
        return response()->json($this->taskService->addTask($req));
    }

    public function updateTask(Request $req){

       
        $fls=array();
        $extensionFlag = false;
        $sizeFlag = false;
        $allowedExtension = array("jpg", "jpeg", "png");
        if($req['files']){
            foreach($req['files'] as $file){
                $extension = $file->getClientOriginalExtension();
                if($file->getSize() >150000) {
                    // return $file->getSize() ;
                    $sizeFlag = true;
                }
                if(!in_array($extension, $allowedExtension)) {
                    $extensionFlag = true;
                }
            }
        }
        
        $validated = Validator::make(json_decode($req['data'],true), [ 
            'data.title' => 'required|string|min:3|max:40|', 
            'data.description' => 'required|string|min:3|max:200', 
            'comments' => 'nullable', 
            'data.status' => 'nullable|string',    
        ]);
       
        if ($validated->fails()) {  
            return response()->json([$validated->messages(),"sizeFlag"=>$sizeFlag,"extensionFlag"=>$extensionFlag],Response::HTTP_BAD_REQUEST);
        }
        if($sizeFlag === true || $extensionFlag === true){
            return response()->json([$validated->messages(),"sizeFlag"=>$sizeFlag,"extensionFlag"=>$extensionFlag],Response::HTTP_BAD_REQUEST);
        }
        return response()->json($this->taskService->updateTask($req));
    }

    public function assignTask(Request $req){ 
        return $this->taskService->assignTask($req->all());
    }
    // public function editTask(Request $req){ 
    //     $validated = $req->validate([ 
    //         'id' => 'required',
    //         'title' => 'required', 
    //         'description' => 'required', 
    //         'status' => 'required',
    //         'project_id' => 'required',   
    //     ]);
    //     return $this->taskService->editTask($req->all());
    // }
    public function delTask(Request $req){ 
        return $this->taskService->delTask($req->all());
    }
    public function members(Request $req){ 
        return $this->taskService->members($req->all());
    }
    public function taskDetails(Request $req){ 
        return response()->json($this->taskService->taskDetails($req->all())) ;
    }
    public function getAddAssignees(Request $req){ 
        return response()->json($this->taskService->getAddAssignees($req)) ;
    }
    public function getEditAssignees(Request $req){ 
        return response()->json($this->taskService->getEditAssignees($req)) ;
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