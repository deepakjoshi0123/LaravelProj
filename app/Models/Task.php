<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    public function getMembers(){
        return $this->belongsToMany(Member::class,Task_Mem::class);
    }
    public function commentMember(){
        return $this->getComments()->with('getMember');
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}

