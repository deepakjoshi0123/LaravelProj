<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['project_id','title','description','attachment','status_id'];
    public function members(){
        return $this->belongsToMany(Member::class,Task_Mem::class);
    }
    public function commentMember(){
        return $this->getComments()->with('getMember');
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}

