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
        $task = Task::create($data);
        $this->taskAddOrUpdateHelper($request,$task,$data);
        return $task;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
    }

    public function updateTask($req){
        // return $req;
        $request = json_decode($req['data'],true);
        $data = $request['data'];
       
            $task = Task::find($data['id']);
            $task->fill($request['data'])->save();           
            $this->taskAddOrUpdateHelper($request,$task,$data);
            $task->edit = true;
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
            ->distinct()
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
       return array('status_id' => $task->status_id);
    }
        
    public function taskDetails($request){

        $task = DB::table('tasks')->join('statuses','statuses.id','=','tasks.status_id')->where('tasks.id',$request['id'])->get(['tasks.id as id','title','description','status_id','status']);
        $comment = DB::table('comments')
        ->join('members','members.id','=','comments.member_id')
        ->where('task_id',$request['id'])->get(['description','comments.updated_at as updated_at','first_name','last_name']);
        $task_attachments = Task_Attachment::where('task_id',$request['id'])->get(['attachment']);
        $task[0]->comments=$comment;
        $task[0]->attachments =$task_attachments;
        return $task;
    }
   
    public function getTasks($request){
        $pageSize = 2;
        $status = DB::table('statuses')->where('project_id',$request['project_id'])->get(['id','status']);
        foreach($status as $key => $sts){
            $grpSts = $sts->status;
            $tasks = DB::table('tasks')->where('status_id',$sts->id)->orderBy('id', 'DESC')->skip(0)->take($pageSize)->get();
            if(count($tasks) > 0){
                $sts->$grpSts = $tasks;
                $sts->len = DB::table('tasks')->where('status_id',$sts->id)->count() - $pageSize ; // currently showing one tasks so pending tasks are total - 1
                foreach($tasks as $task){
                    $task->members=$this->getEditAssignees($task->id,$request['project_id']);
                }
            }
            else{
                unset($status[$key]);
            }
        }
        return $status;
    }

    //using this function
    public function getAddAssignees($request){    
            $id = $request->all()['project_id'];
            return Project::find($id)->members()->distinct()->get(['email','id','first_name']);
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
    
    public function getPendingTasksLength($status_id , $request){
       return DB::table('tasks')
                ->where([
                    ['status_id',$status_id],
                    ['title','like','%'.$request['text'].'%'],
                    ['project_id','=',$request['project_id']]
                ])->orWhere([
                    ['status_id',$status_id],
                    ['description','like','%'.$request['text'].'%'],
                    ['project_id','=',$request['project_id']]
                    ])
                ->count();
    }

    public function searchTask($request){

        $pageSize = 2;
        $status = DB::table('statuses')->where('project_id',$request['project_id'])->get(['id','status']);
        // return $status;
        foreach($status as $key => $sts){
            $grpSts = $sts->status;
            $tasks = DB::table('tasks')
            ->where([
                ['status_id',$sts->id],
                ['project_id',$request['project_id']],
                ['title','like','%'.$request['text'].'%'],
            ])->orWhere([
                ['status_id',$sts->id],
                ['project_id',$request['project_id']],
                ['description','like','%'.$request['text'].'%'],
                ])
            ->orderBy('id', 'DESC')->skip(0)->take($pageSize)->get();
            if(count($tasks) > 0){
                $sts->$grpSts = $tasks;
                $sts->len = $this->getPendingTasksLength($sts->id,$request)- $pageSize ; // currently showing one tasks so pending tasks are total - 1
                foreach($tasks as $task){
                    $task->members=$this->getEditAssignees($task->id,$request['project_id']);
                }
            }
            else{
                unset($status[$key]);
            }
        }
        return $status;
    } 

    public function getNextSearchedTasks($request){
        $pageSize = 2;
        $status_id = 0;
        $tasks = DB::table('tasks')
            ->where([
                ['status_id',$request['status_id']],
                ['project_id',$request['project_id']],
                ['title','like','%'.$request['text'].'%'],
            ])->orWhere([
                ['status_id',$request['status_id']],
                ['project_id',$request['project_id']],
                ['description','like','%'.$request['text'].'%'],
                ])
            ->orderBy('id', 'DESC')
            ->skip($request['pageNo']*$pageSize-$request['del']+$request['add'])->take($pageSize)->get();
            foreach($tasks as $task){
                $status_id = $task->status_id;
                $task->members=$this->getEditAssignees($task->id,$request['project_id']);
            }

            $len = $this->getPendingTasksLength($status_id,$request,$pageSize);
            // return $len;
            return array("tasks"=>$tasks , "len"=>$len-$request['pageNo']*$pageSize- $pageSize +$request['del']-$request['add']);
        }

    public function filterArray($arr){
        $res = array();
        for($i=0;$i<count($arr);$i++){
            if(!$arr[$i] == null)
            array_push($res,$arr[$i]);
        }
        return $res;
    }


    public function getNextFilteredTasks($request){
        $members = $this->filterArray($request['filters']['members']);
        // $statuses  = $this->filterArray($request['filters']['status']);  
        // return $request;
        $pageSize = 2;
        $tasksRes = DB::table('tasks')
        ->leftjoin('task__mems','task__mems.task_id','=','tasks.id')
        ->leftjoin('members','task__mems.member_id','=','members.id')
        ->where(function ($query) use ($members) {
            if(sizeof($members)!==0){
                return $query->whereIn('members.id', $members);
            }
        })
        ->where([['project_id',$request['project_id']],['status_id', $request['status_id']]])
        ->distinct()
        ->orderBy('tasks.id', 'DESC')
        ->skip($request['pageNo']*$pageSize-$request['del']+$request['add'])->take($pageSize)
        ->get(['tasks.id as id','title','description','status_id']);
    foreach($tasksRes as $task){
        $task->members=$this->getEditAssignees($task->id,$request['project_id']);
    }
    // return $tasks;
    $len = $this->getFilterTasksLen($request,$members,$request['status_id']);
    // $len = 5;
    return array("tasks"=>$tasksRes , "len"=>$len-$request['pageNo']*$pageSize-$pageSize+$request['del']-$request['add']);
        // return "hi";
    }

    public function getFilterTasksLen($request,$members,$sts){
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
        ->where([['project_id',$request['project_id']],['status_id',$sts]])
        ->distinct()->count();
    }

    public function filterTask($request){
        $members = [];
        $statuses = [];
        if(array_key_exists('filters', $request)){
            $members = $this->filterArray($request['filters']['members']);
            $statuses  = $this->filterArray($request['filters']['status']); 
        } 
 
        $pageSize = 2;
        $status = DB::table('statuses')->where('project_id',$request['project_id'])->get(['id','status']);
        // return gettype($status) ;
        foreach($status as $key => $sts){
            $grpSts = $sts->status;
            $tasks = DB::table('tasks')
            ->leftjoin('task__mems','task__mems.task_id','=','tasks.id')
            ->leftjoin('members','task__mems.member_id','=','members.id')
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
            ->where([['project_id',$request['project_id']],['status_id',$sts->id]])
            ->distinct()   
            ->orderBy('id', 'DESC')
            ->skip($request['pageNo']*$pageSize-$request['del']+$request['add'])->take($pageSize)
            ->get(['tasks.id as id','title','description','status_id']);
            
            if(count($tasks) > 0){
                $sts->$grpSts = $tasks;
                $sts->len = $this->getFilterTasksLen($request,$members,$sts->id) - $request['pageNo']*$pageSize-$pageSize+$request['del']-$request['add']; // currently showing one tasks so pending tasks are total - 1
                foreach($tasks as $task){
                    $task->members=$this->getEditAssignees($task->id,$request['project_id']);
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
            (new Comment())->fill(['task_id'=>$task->id,'member_id'=>$request['member_id'],'description'=>$cmnt])->save();
        }
    }

    public function getNextTasks($request){
        // return $request;
        $pageSize = 2;
        $tasks = DB::table('tasks')->where([
            ['project_id',$request['project_id']],
            ['status_id',$request['status_id']]
    ])->orderBy('id', 'DESC')->skip($request['pageNo']*$pageSize-$request['del']+$request['add'])->take($pageSize)->get();
    foreach($tasks as $task){
        $task->members=$this->getEditAssignees($task->id,$request['project_id']);
    }
    $len = DB::table('tasks')->where([['project_id',$request['project_id']],['status_id',$request['status_id']]])->count();
    return array("tasks"=>$tasks , "len"=>$len-$request['pageNo']*$pageSize-$pageSize+$request['del']-$request['add']);
    }

    public function taskAddOrUpdateHelper($request,$task,$data){
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
        $task->members=$this->getEditAssignees($task->id,$data['project_id']);
    }
}

