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

    public function addTask($req){
        $request = json_decode($req['data'],true);
        $data = $request['data'];
        $members_id = $request['assignee'];
           $task = Task::create($data);
        
            if(count($members_id)>0){
                $this->assignTask($task,$members_id);
            }
            //replace member_id with member_if fetched from token
            if(count($request['comments'])>0){
                $this->addComments($request,$task);
            }
            if(isset($_FILES['files'])){
                $this->addAttachment($request,$task);
              }
            return $task;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
    }

    public function updateTask($req){
        $request = json_decode($req['data'],true);
        $data = $request['data'];
        $members_id = $request['assignee'];
            $task = Task::find($data['id']);
            $task->fill($request['data'])->save();           
            if(count($members_id)>0){
                $this->assignTask($task,$members_id);
            }
            if(count($request['comments'])>0){
                $this->addComments($request,$task);
            }
            $task['edit'] = true;
            if(isset($_FILES['files'])){
                $this->addAttachment($request,$task);
              }
           return $task;      
    }
    
    

    public function members($request){
        
       return DB::table('tasks')
            ->join('task__mems','task__mems.task_id','=','tasks.id')
            ->join('members','task__mems.member_id','=','members.id')
            ->where([
                    ['tasks.id',$request['task_id']],
                    ['tasks.project_id',$request['project_id']]
                ])
            ->get(['members.id as id','first_name','last_name','email']);
        
    }

    public function assignTask($task,$members_id){
        foreach($members_id as $member_id){
            (new Task_Mem())->fill(['task_id'=>$task->id,'member_id'=>$member_id])->save();
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
        $task_attachments = Task_Attachment::where('task_id',$request['id'])->get(['attachment']);
        $task[0]->comments=$comment;
        $task[0]->attachments =$task_attachments;
        return $task;
    }
   
    public function getTasks($request){

        // return 1/0;
        // abort(404,'user not found');
       
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

    public function getAddAssignees($request){    
            $id = $request->all()['project_id'];
            return Project::find($id)->members()->distinct()->get(['email','id','first_name']);
    }

    public function getEditAssignees($request){  
        $id = $request->get('task_id');//taking out task_id
            $project_id = $request->get('project_id');//taking out project_id
            $memToBeEliminated = Task_Mem::where('task_id',$id)->distinct()->get(['member_id']);
            
            $membersRes = DB::table('proj__mems')
            ->join('members','proj__mems.member_id','=','members.id')
            ->where('project_id',$project_id)
            ->whereNotIn('member_id',$memToBeEliminated)
            ->distinct()
            ->get(['email','first_name','last_name','members.id as id']);
            return $membersRes; 
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
        
        $tasks = DB::table('tasks')
        ->leftjoin('task__mems','task__mems.task_id','=','tasks.id')
        ->leftjoin('members','task__mems.member_id','=','members.id')
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
        ->distinct()
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

    public function addAttachment($request,$task){
        $no_of_files = count($_FILES['files']['name']);  
        for($i = 0 ;$i < $no_of_files ; $i++){  
            $file_name = preg_replace('/\s+/', '', $_FILES['files']['name'][$i]);
            // return $_FILES['files']['name'][$i];//remove spaces before adding the file
            $location = 'media/'.time().$file_name;
            move_uploaded_file($_FILES['files']['tmp_name'][$i],$location);    
            Task_Attachment::create(['task_id' => $task->id,'attachment' => time().$file_name ]);
        }
    }
    public function addComments($request,$task){
        foreach($request['comments'] as $cmnt){
            (new Comment())->fill(['task_id'=>$task->id,'member_id'=>'1','description'=>$cmnt])->save();
        }
    }
}

