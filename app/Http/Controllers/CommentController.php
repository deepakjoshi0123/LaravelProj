<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;  
    }
    public function getComments(Request $req){
        return $this->commentService->getComments($req->getContent());
    }
    public function addComment(Request $req){
        return $this->commentService->addComment($req->getContent());
    }
}
