<?php
namespace App\Services;
use App\Models\Task_Mem;
use App\Models\Task;
use App\Models\Project;
use App\Models\Member;
use App\Models\Comment;
use App\Models\Proj_Mem;
use Illuminate\Http\Request;
use Response;

Class TaskService {
    public function addTask($request){
        // dd($request['comments']);
        $data = $request->get('data');
        // echo $data;
        //token_user_id
        if($request->has('data.project_id')){
           $member_id = $request->get('assignee');
           $task = Task::create($request['data']);
        
            if($member_id){
                (new Task_Mem())->fill(['task_id'=>$task->id,'member_id'=>$member_id])->save();
            }
            // return $request['comments'];
            //
            
            foreach($request->get('comments') as $cmnt){
                (new Comment())->fill(['task_id'=>$task->id,'member_id'=>$request->get('member_id'),'description'=>$cmnt])->save();
                // return $cmnt;
                }
            return $task;
        }
        else{
            return "task";
            $task = Task::find($request['task_id']);
            $task->fill($request)->save();
            $member_id = Member::where('email',$request['assignee'])->get(['id']);
            if($member_id){
                $task_mem = (new Task_Mem())->fill([$task->id,$member_id])->save();
            }
            foreach($request['comments'] as $cmnt){
                (new Comment())->fill([$task->id,$cmnt])->save();
            }
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        
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
        $task = Task::where('id',$request['id'])->get(['id','title','description','attachment','status']);
        $comment = Comment::with('getMember')->where('task_id',$request['id'])->get();
        $task[0]->comments=$comment;
        // dd($task->title);
        return $task;
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

    public function getAssignees($request){    
        if($request->has('project_id')){
            $id = $request->all()['project_id'];
            return Project::find($id)->members()->get(['email','id','first_name']);;
        }
        else{
            $id = $request->get('task_id');//taking out task_id
            $project_id = $request->get('project_id');//taking out project_id
            $memToBeEliminated = Task_Mem::where('task_id',$id)->get(['member_id']);
            $membersRes = Task::find($id)->getMembers()->whereNotIn('member_id',$memToBeEliminated)->get();
                      
            // $mems = Task_Mem::whereNotIn('member_id',$memToBeEliminated)->distinct()->get(['member_id']); 

            // $memToRes = Project::find($project_id)->members()->whereIn('member_id',[$mems])->get();
            // $memToRes = Project::find($project_id)->members()->get();
            return $membersRes;
            //pending ................................
           
        }
    }  
}