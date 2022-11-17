<?php
namespace App\Services;
use App\Models\Task_Mem;
use App\Models\Task;
use App\Models\Project;
use App\Models\Member;
use App\Models\Comment;
use App\Models\Proj_Mem;
use App\Models\Task_Attachment;
use Illuminate\Http\Request;
use Response;
use DB;

Class TaskService {
    // break this service 
    public function addTask($request){
        $data = $request->get('data');
        $member_id = $request->get('assignee');

        // return $request->get('attachment');

        if($request->has('data.project_id')){ 
           $task = Task::create($request['data']);
        
            if($member_id != 'unassigned'){
                (new Task_Mem())->fill(['task_id'=>$task->id,'member_id'=>$member_id])->save();
            }
                        
            foreach($request->get('comments') as $cmnt){
                (new Comment())->fill(['task_id'=>$task->id,'member_id'=>$request->get('member_id'),'description'=>$cmnt])->save();
            }
            return $task;
        }
        else{
            $task = Task::find($request['data.id']);
            $task->fill($request['data'])->save();           
            if($member_id != 'unassigned'){
                (new Task_Mem())->fill(['task_id'=>$task->id,'member_id'=>$member_id])->save();
            }
            foreach($request->get('comments') as $cmnt){
                (new Comment())->fill(['task_id'=>$task->id,'member_id'=>$request->get('member_id'),'description'=>$cmnt])->save();
            }
            //send edit flag from here 
            //check for edit flag in UI if there exists edit flag  then
            //remove it from task-listing and append it to top of that status
            $task['edit'] = true;
           return $task;
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        
    }

    public function members($request){
        return Task::find($request['task_id'])->members()->get(['id','first_name','last_name','email']);
    }

    public function assignTask($request){
        (new Task_Mem())->fill($request)->save();
        return Response::json(array(
            'success' => true,
        )); 
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
             // notify all users assosiated with that task id; try to di it in async way  just like 
            return array(
                'success' => true
              ); 
        }
    }

    public function delTask($request){
       $task = Task::find($request['task_id']);
       $task->delete();
        return array('status' => $task->status);
    }
        
    public function taskDetails($request){
        $task = Task::where('id',$request['id'])->get(['id','title','description','status']);
        $comment = Comment::with('getMember')->where('task_id',$request['id'])->get();
        $task[0]->comments=$comment;
        return $task;
    }
   
    public function getTasks($request){
       
        $project = Task::where('project_id',$request['project_id'])->get(['id','title','description','status']);//get(['id',etc..]) was giving error when
        $res=array();
        for($i=0;$i<count($project);$i++){
            $res[$project[$i]['status']] =  array();
         }
        for($i=0;$i<count($project);$i++){
           array_push($res[$project[$i]['status']],$project[$i]);
        }
        return $res;
    }

    public function getAssignees($request){    
        if(!$request->has('task_id')){
            $id = $request->all()['project_id'];
            return Project::find($id)->members()->get(['email','id','first_name']);;
        }
        else{
            // need to find those member who are included in this project but included in the task
            $id = $request->get('task_id');//taking out task_id
            $project_id = $request->get('project_id');//taking out project_id
            $memToBeEliminated = Task_Mem::where('task_id',$id)->get(['member_id']);
            $membersRes = DB::table('proj__mems')
            ->join('members','proj__mems.member_id','=','members.id')
            ->where('project_id',$project_id)
            ->whereNotIn('member_id',$memToBeEliminated)
            ->get(['email','first_name','last_name','members.id as id']);
            return $membersRes; 
        }

    } 
    public function searchTask($request){
        $project = Task::where([
            ['title','like','%'.$request['text'].'%'],
            ['project_id','=',$request['project_id']]
        ])->orWhere([
            ['description','like','%'.$request['text'].'%'],
            ['project_id','=',$request['project_id']]
            ])
        ->get(['title','description','id','status']);
        $res=array();
        for($i=0;$i<count($project);$i++){
            $res[$project[$i]['status']] =  array();
         }
        for($i=0;$i<count($project);$i++){
           array_push($res[$project[$i]['status']],$project[$i]);
        }
        return $res;
    } 

    public function filterArray($arr){
        $res = array();

        for($i=0;$i<count($arr);$i++){
            if(!$arr[$i] == null)
            array_push($res,$arr[$i]);
        }
        
        return $res;
    }

    public function filterTask($request){


        $members = $this->filterArray($request['filters']['members']);
        $status  = $this->filterArray($request['filters']['status']);
        
        $tasks = DB::table('task__mems')
        ->join('members','task__mems.member_id','=','members.id')
        ->join('tasks','task__mems.task_id','=','tasks.id')
        ->where(function ($query) use ($members) {
            if(sizeof($members)!==0){
                return $query->whereIn('members.id', $members);
            }
        })
        ->where(function ($query) use ($status) {
            if(sizeof($status)!==0){
                return $query->whereIn('status', $status);
            }
        })
        ->where('project_id',$request['project_id'])
        ->get(['tasks.id','title','description','status']);
       
        $resToSend=array();
        for($i=0;$i<count($tasks);$i++){
            $resToSend[$tasks[$i]->status] =  array();
         }
        for($i=0;$i<count($tasks);$i++){
           array_push($resToSend[$tasks[$i]->status],$tasks[$i]);
        }
        return $resToSend;
    }

}