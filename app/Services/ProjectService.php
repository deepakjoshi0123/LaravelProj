<?php
namespace App\Services;
use App\Models\Proj_Mem;
use App\Models\Project;
use App\Models\Member;
use Illuminate\Http\Request;
use Response;

Class ProjectService{
    
    public function getAllProjects($req){
        return Member::find($req['member_id'])->projects()->get(['project_name','id']);
    }

    public function createProject($request){
      $project = new Project();
      $project->fill([
        'project_name' => $request['name'],
        'owner' => $request['owner'],
      ])->save() ;

       (new Proj_Mem())->fill([
        'project_id' => $project->id,
        'member_id' => $project->owner,
      ])->save() ;

       return array(
        'success' => true,
        'data'   => $project
      ); 
    }
    
    public function addMemberToProject($request){
      (new Proj_Mem())->fill([
        'project_id' => $request['project_id'],
        'member_id' => $request['member_id'],
      ])->save() ;
        return array(
            'success' => true,
          ); 
        //send notification only this newly added user
    }

    public function getAllMembers($request){
      return Project::find($request['project_id'])->members()->get(['id','first_name','last_name','email']);
    }
   
}
