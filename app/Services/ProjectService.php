<?php
namespace App\Services;
use App\Models\Proj_Mem;
use App\Models\Project;
use Illuminate\Http\Request;
use Response;

Class ProjectService{
    //can we do same query in one
            //-getting all projects for the given user_id
            //-getting all the users for the given project_id
    public function getAllProjects($request){
        $user = json_decode($request,true)['member_id'];
        $project = Proj_Mem::query()->with(['project'=> function($query){ //hasmanythrough
        $query->select('id','project_name');
          }])->where('member_id',$user)->get(['project_id']);
         return $project;
    }
    // validation
            // -not empty
            // neg values
            // integer/string
    public function createProject($request){
       $project = new Project;
       $req = json_decode($request,true);
      
       $project->project_name = $req['name'];
       $project->owner = $req['owner'];
       $project->save();

       $proj_Mem = new Proj_Mem;
       $proj_Mem ->project_id  = $project->id; 
       $proj_Mem ->member_id = $project->owner;
       $proj_Mem->save();
       return Response::json(array(
        'success' => true,
        'data'   => $project
      )); 
    }
    
    public function addMemberToProject($request){
        $prj_mem = json_decode($request,true);
        $proj_Mem = new Proj_Mem;
        $proj_Mem ->project_id  = $prj_mem['project_id']; 
        $proj_Mem ->member_id = $prj_mem['member_id'];
        $proj_Mem->save();
        return Response::json(array(
            'success' => true,
          )); 
        //send notification only this newly added user
    }

    public function getAllMembers($request){
        $proj = json_decode($request,true)['project_id'];
        $user = Proj_Mem::query()->with(['member'=> function($query){ //hasmanythrough
        $query->select('id','first_name','last_name','email');
          }])->where('project_id',$proj)->get(['member_id']);
         return $user;
    }
   
}
