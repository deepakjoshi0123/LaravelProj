<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task_Mem extends Model
{
    use HasFactory;
    protected $fillable = ['task_id','member_id'];
   
}
