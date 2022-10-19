<?php
namespace App\Services;
use App\Models\Task_Mem;
use App\Models\Task;
use App\Models\Member;
use Illuminate\Http\Request;
use Response;

Class TaskService{
    public function addTask($request){
        (new Task())->fill($request)->save();
        return array(
        'success' => true,
            ); 
    }
    //why vaidation failed are redirecting to this function
    public function members($request){
        return Task::find($request['task_id'])->getMembers()->get(['id','first_name','last_name','email']);
    }

    public function assignTask($request){
        (new Task_Mem())->fill($request)->save();
        return Response::json(array(
            'success' => true,
        )); 
        // Task::find($request['task_id'])->members()->get(['id','first_name','last_name','email']);
        //send notification only this newly added user query using new update in task model for geeting user email
    }
    public function editTask($request){
        $task = Task::find($request['id']);
        if(is_null($task)){
            return Response::json(array(
                'message' => 'no task exist for the given id',
              )); 
        }
        else{
            $task = Task::find($request['id']);
            $task->fill($request)->save();
             // Task::find($request['task_id'])->members()->get(['id','first_name','last_name','email']);
             // notify all users assosiated with that task id; try to di it in async way  just like 
            return array(
                'success' => true
              ); 
        }
    }

    public function delTask($request){
        Task::find($request['task_id'])->delete();
        return array('success' => true);
    }
        //
    public function taskDetails($request){
        return Task::with('comments')->where('id',$request['id'])->get(['id','title','description','attachment','status']);
    }
   
    public function getTasks($request){
        // return $request;
        $project = Task::where('project_id',$request['project_id'])->get(['id','title','description','status','attachment']);//get(['id',etc..]) was giving error when
        $res=array();
        for($i=0;$i<count($project);$i++){
            $res[$project[$i]['status']] =  array();
         }
        for($i=0;$i<count($project);$i++){
           array_push($res[$project[$i]['status']],$project[$i]);
        }
        return $res;
    }
}