<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Proj_Mem;
use App\Models\Comment;
use App\Models\Status;
use App\Models\Task_Mem;
use App\Models\Task;
use App\Models\Member;

class IndexController extends Controller
{
    public function index()
    {
//------------list all projects on left side after login -------------------------------------------------
    // $user = '14';
    // $project =Proj_Mem::query()->with(['project'=> function($query){
    //     $query->select('id','project_name');
    // }])->where('member_id',$user)->get(['project_id']);
    // return $project;

//---------- list of all user for that project----------------------------------------------------------
    //     $proj_id='10';
    //     $project =Proj_Mem::query()->with(['member'=> function($query){
    //     $query->select('id','first_name','last_name','email');
    // }])->where('project_id',$proj_id)->get(['member_id']);
    // return $project;

//------list all task according for a given project_id with respect to all status -----------------------------
        // $proj_id = '10';
        // $project = Task::where('project_id',$proj_id)->get();
        // $res=array();
        //     //intialising ----------> map
        // for($i=0;$i<count($project);$i++){
        //     $res[$project[$i]['status']] =  array();
        //  }
        // for($i=0;$i<count($project);$i++){
        //    array_push($res[$project[$i]['status']],$project[$i]);
        // }
        // return $res;
    
//----------------------------------getting all comments for a particular task_id task ------------------------
        // $task_id = '17';
        // $comnt =Comment::query()->with(['member' => function($query){
        //     $query->select('id','first_name','last_name');
        // }])->where('task_id',$task_id)->get(['description','member_id']);
        // return $comnt;

//------------------------------
    }
}