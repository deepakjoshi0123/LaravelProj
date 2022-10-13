<?php
namespace App\Services;
use App\Models\Comment;
use Illuminate\Http\Request;
use Response;

class CommentService{
    public function getComments($request){
        $task_id = json_decode($request,true)['task_id'];
        $comnt =Comment::query()->with(['member' => function($query){
            $query->select('id','first_name','last_name');
        }])->where('task_id',$task_id)->get(['description','member_id']);
        return $comnt;
    }
    public function addComment($request){
        $comment = json_decode($request,true);
        $cmnt = new Comment;
        $cmnt ->description  = $comment['description']; 
        $cmnt ->member_id = $comment['member_id'];
        $cmnt ->task_id = $comment['task_id'];
        $cmnt->save();
        return Response::json(array(
            'success' => true,
          )); 
    }

}