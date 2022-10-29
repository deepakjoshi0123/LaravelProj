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
    // break this service 
    public function addTask($request){
        $data = $request->get('data');
        $member_id = $request->get('assignee');
        if($request->has('data.project_id')){
         
           $task = Task::create($request['data']);
        
            if($member_id){
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
            if($member_id){
                (new Task_Mem())->fill(['task_id'=>$task->id,'member_id'=>$member_id])->save();
            }
            foreach($request->get('comments') as $cmnt){
                (new Comment())->fill(['task_id'=>$task->id,'member_id'=>$request->get('member_id'),'description'=>$cmnt])->save();
            }
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        
        return array(
        'success' => true,
        ); 
    }

    public function members($request){
        return Task::find($request['task_id'])->getMembers()->get(['id','first_name','last_name','email']);
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
        Task::find($request['task_id'])->delete();
        return array('success' => true);
    }
        
    public function taskDetails($request){
        $task = Task::where('id',$request['id'])->get(['id','title','description','attachment','status']);
        $comment = Comment::with('getMember')->where('task_id',$request['id'])->get();
        $task[0]->comments=$comment;
        return $task;
    }
   
    public function getTasks($request){
       
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
}