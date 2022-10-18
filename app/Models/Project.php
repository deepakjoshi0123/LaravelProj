<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['project_name','owner'];
    protected $hidden = ['pivot'];
    public function members(){
        return $this->belongsToMany(Member::class,Proj_Mem::class);
    }
}
