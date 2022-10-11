<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proj_Mem extends Model
{
    use HasFactory; 
    public function project(){
        return $this->hasMany('App\Models\Project','id','project_id');
    }
    public function member(){
        return $this->hasMany('App\Models\Member','id','member_id');
    }
}
