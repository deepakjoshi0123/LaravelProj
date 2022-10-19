<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public function task(){
        // return $this->belongsTo('App\Models\Task','id','task_id');
    }
    public function commentMem(){
        return $this->belongsTo(Member::class,Comment::class);
    }
    //belongs to gic=ves error
  // task belongs comment  
 //comment belongs to vs beongs to many
}
//form vali