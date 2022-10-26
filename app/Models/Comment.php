<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public function getTask(){
        return $this->belongsTo(Task::class);
    }
    public function getMember(){
        return $this->belongsTo(Member::class);
    }
    //belongs to gic=ves error
  // task belongs comment  
 //comment belongs to vs beongs to many
}
//form vali