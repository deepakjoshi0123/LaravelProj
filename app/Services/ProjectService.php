<?php
namespace App\Services;
use App\Models\Proj_Mem;
use App\Models\Project;
use Illuminate\Http\Request;
use Response;

Class ProjectService{
    public function getAllProjects($req){
        $user = json_decode($req,true)['member_id'];
        $project = Proj_Mem::query()->with(['project'=> function($query){ //hasmanythrough
        $query->select('id','project_name');
          }])->where('member_id',$user)->get(['project_id']);
         return $project;
    }
    // validation
            // -not empty
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
    
    public function addMemberToProject(){
        
    }
}
