<?php
namespace App\Services;
use App\Models\Task_Mem;
use App\Models\Task;
use Illuminate\Http\Request;
use Response;

Class TaskService{
    public function getTasks($project_id){
        $project = Task::where('project_id',$project_id)->get(['id','title','description','status','attachment']);
        $res=array();
        for($i=0;$i<count($project);$i++){
            $res[$project[$i]['status']] =  array();
         }
        for($i=0;$i<count($project);$i++){
           array_push($res[$project[$i]['status']],$project[$i]);
        }
        return $res;
    }
    public function addTask($request){
       $task = new Task;
       $req = json_decode($request,true);
       
       $task->title = $req['title'];
       $task->description = $req['description'];
       $task->attachment = $req['attachment'];
       $task->status = $req['status'];
       $task->project_id = $req['project_id'];
       $task->save();
       return Response::json(array(
        'success' => true,
      )); 
    }
    public function assignTask($request){
        $tsk_mem = json_decode($request,true);
        $task_Mem = new Task_Mem;
        $task_Mem ->task_id  = $tsk_mem['task_id']; 
        $task_Mem ->member_id = $tsk_mem['member_id'];
        $task_Mem->save();
        return Response::json(array(
            'success' => true,
          )); 
        //send notification only this newly added user
    }
    public function editTask($request){
        $tsk = json_decode($request,true);
        $task = Task::find($tsk['task_id']);
        if(is_null($task)){
            return Response::json(array(
                'message' => 'no task exist for the given id',
              )); 
        }
        else{
            $task->title = $tsk['title'];
            $task->description = $tsk['description'];
            $task->attachment = $tsk['attachment'];
            $task->status = $tsk['status'];
            $task->save();

             // notify all users assosiated with that task id; try to di it in async way  

            $user = Task_Mem::query()->with(['member'=> function($query){ //hasmanythrough
                $query->select('id','email');
                  }])->where('task_id',$tsk['task_id'])->get(['member_id']);

            return Response::json(array(
                'success' => true,
                'data' => $user
              )); 
           
        }
    }
    public function delTask($request){
        $id = json_decode($request,true)['task_id'];
        Task::find($id)->delete();
    }
    public function members($request){
        $tsk = json_decode($request,true);
        $user = Task_Mem::query()->with(['member'=> function($query){ //hasmanythrough
        $query->select('id','first_name','last_name','email');
            }])->where('task_id',$tsk['task_id'])->get(['member_id']);
        return $user;
    }
}