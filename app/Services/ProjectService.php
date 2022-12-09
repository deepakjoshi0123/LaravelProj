<?php
namespace App\Services;

use App\Models\Proj_Mem;
use App\Models\Project;
use App\Models\Member;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
Class ProjectService{
    
    public function getAllProjects($req){

        return DB::table('projects')
        ->join('proj__mems','proj__mems.project_id','=','projects.id')
        ->join('members','proj__mems.member_id','=','members.id')
        ->join('members as mem2','projects.owner','=','mem2.id')
        ->where('proj__mems.member_id',$req['member_id'])
        ->get(['mem2.first_name as first_name','mem2.last_name as last_name','project_name','projects.id as id']);
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
      
      $this->addDefaultStatus($project->id);
      
      return array(
        'success' => true,
        'data'   => $project
      ); 
    }

    public function addDefaultStatus($project_id){
        $defStatus = ['OPEN','CLOSED','WIP'];
        foreach($defStatus as $status){
          (new Status())->fill([
            'project_id' => $project_id,
            'status' => $status,
          ])->save() ;
        }
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

    public function createStatus($request){
     
     if (count(Status::where([
      ['project_id',$request['project_id']],
      ['status',strtoupper($request['status'])]
     ])->get(['status']))>0 || strtoupper($request['status']) == 'OPEN' || strtoupper($request['status']) == 'CLOSED' || strtoupper($request['status']) == 'WIP')
     {
      return response()->json(['Duplicate : Choose Another Name'], Response::HTTP_BAD_REQUEST);
     }

      (new Status())->fill([
        'project_id' => $request['project_id'],
        'status' => strtoupper($request['status']),
      ])->save() ;
        return $this->getCustomStatus($request);
    }

   public function getCustomStatus($request){
      return Status::where('project_id',$request['project_id'])->distinct()->get(['id','status']);
   }
}
