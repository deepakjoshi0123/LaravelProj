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

    public function addTask($req,$project_id){
        $request = json_decode($req['data'],true);
        $data = $request['data'];
        $data['project_id'] = $project_id;
        $task = Task::create($data);
        $this->taskAddOrUpdateHelper($request,$task,$data,$project_id);
        return $task;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
    }

    public function updateTask($req,$project_id,$task_id){
        // return $req;
        $request = json_decode($req['data'],true);
        $data = $request['data'];
       
            $task = Task::find($task_id);
            $task->fill($request['data'])->save();           
            $this->taskAddOrUpdateHelper($request,$task,$data,$task_id);
            $task->edit = true;
           return $task;      
    }
    
    public function taskMembers($request,$project_id,$task_id){
        
       return DB::table('tasks')
            ->join('task__mems','task__mems.task_id','=','tasks.id')
            ->join('members','task__mems.member_id','=','members.id')
            ->where([
                    ['tasks.id',$task_id],
                    ['tasks.project_id',$project_id]
                ])
            ->distinct()
            ->get(['members.id as id','first_name','last_name','email']);   
    }

    public function assignTask($task,$members_id){
        foreach($members_id as $member_id){
            (new Task_Mem())->fill(['task_id'=>$task->id,'member_id'=>$member_id])->save();
        }
    }
    
    public function delTask($request,$project_id,$task_id){
       $task = Task::find($task_id);
       $task->delete();
       return array('status_id' => $task->status_id);
    }
        
    public function taskDetails($request,$project_id,$task_id){

        $task = DB::table('tasks')->join('statuses','statuses.id','=','tasks.status_id')->where('tasks.id',$task_id)->get(['tasks.id as id','title','description','status_id','status']);
        $comment = DB::table('comments')
        ->join('members','members.id','=','comments.member_id')
        ->where('task_id',$task_id)->get(['description','comments.updated_at as updated_at','first_name','last_name']);
        $task_attachments = Task_Attachment::where('task_id',$task_id)->get(['attachment']);
        $task[0]->comments=$comment;
        $task[0]->attachments =$task_attachments;
        return $task;
    }
   
   
    //using this function
    public function projectMembers($request,$project_id){    
            return Project::find($project_id)->members()->distinct()->get(['email','id','first_name']);
    }

    public function getEditAssignees($task_id,$project_id){  
        return DB::table('tasks')
            ->join('task__mems','task__mems.task_id','=','tasks.id')
            ->join('members','task__mems.member_id','=','members.id')
            ->where([
                    ['tasks.id',$task_id],
                    ['tasks.project_id',$project_id]
                ])
            ->distinct()
            ->get(['members.id as id','first_name','last_name','email']);
    }
    
    
    public function filterArray($arr){
        $res = array();
        for($i=0;$i<count($arr);$i++){
            if(!$arr[$i] == null)
            array_push($res,$arr[$i]);
        }
        return $res;
    }


   
    public function getFilterTasksLen($request,$members,$sts,$project_id){
    return DB::table('tasks')
        ->leftjoin('task__mems','task__mems.task_id','=','tasks.id')
        ->leftjoin('members','task__mems.member_id','=','members.id')
        ->where(function($query) use($request,$sts){
            if(array_key_exists('text', $request)){
               return $query->where([
                    ['title','like','%'.$request['text'].'%'],
                ])->orWhere([
                    ['description','like','%'.$request['text'].'%'],
                ]);
            }
        })
        ->where(function ($query) use ($members) {
            if(sizeof($members)!==0){
                return $query->whereIn('members.id', $members);
            }
        })
        ->where([['project_id',$project_id],['status_id',$sts]])
        ->distinct()->count();
    }

    public function getTasks($request,$project_id){
        $members = [];
        $statuses = [];
        if(array_key_exists('filters', $request)){
            $members = $this->filterArray($request['filters']['members']);
            $statuses  = $this->filterArray($request['filters']['status']); 
        } 
 
        $pageSize = 2;
        $status = DB::table('statuses')->where('project_id',$project_id)->get(['id','status']);
        // return gettype($status) ;
        foreach($status as $key => $sts){
            $grpSts = $sts->status;
            $tasks = DB::table('tasks')
            ->leftjoin('task__mems','task__mems.task_id','=','tasks.id')
            ->leftjoin('members','task__mems.member_id','=','members.id')
            ->where(function($query) use($request,$sts){
                if(array_key_exists('status_id', $request)){
                   return $query->where('status_id',$request['status_id']);
                }
            })
            ->where(function ($query) use ($members) {
                if(sizeof($members)!==0){
                    return $query->whereIn('members.id', $members);
                }
            })
            ->where(function ($query) use ($statuses) {
                if(sizeof($statuses)!==0){
                    return $query->whereIn('status_id', $statuses);
                }
            })
            ->where(function($query) use($request,$sts){
                if(array_key_exists('text', $request)){
                   return $query->where([
                        ['title','like','%'.$request['text'].'%'],
                    ])->orWhere([
                        ['description','like','%'.$request['text'].'%'],
                    ]);
                }
            })
            ->where([['project_id',$project_id],['status_id',$sts->id]])
            ->distinct()   
            ->orderBy('id', 'DESC')
            ->skip($request['pageNo']*$pageSize-$request['del']+$request['add'])->take($pageSize)
            ->get(['tasks.id as id','title','description','status_id']);
            
            if(count($tasks) > 0){
                $sts->$grpSts = $tasks;
                $sts->len = $this->getFilterTasksLen($request,$members,$sts->id,$project_id) - $request['pageNo']*$pageSize-$pageSize+$request['del']-$request['add']; // currently showing one tasks so pending tasks are total - 1
                foreach($tasks as $task){
                    $task->members=$this->getEditAssignees($task->id,$project_id);
                }
            }
            else{
                 unset($status[$key]);
            }
        }

        return $status;
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
            (new Comment())->fill(['task_id'=>$task->id,'member_id'=>auth()->guard('api')->user()->id,'description'=>$cmnt])->save();
        }
    }

   
    public function taskAddOrUpdateHelper($request,$task,$data,$project_id){
        $members_id = $request['assignee'];
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
        $task->status = DB::table('statuses')->where('id',$task->status_id)->get('status');
        
        $task->members=$this->getEditAssignees($task->id,$task->project_id);
    }
}

