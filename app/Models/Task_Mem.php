<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task_Mem extends Model
{
    use HasFactory;
    public function task(){
        return $this->hasMany('App\Models\Task','id','task_id');
    }
    public function member(){
        return $this->hasMany('App\Models\Member','id','member_id');
    }
}
