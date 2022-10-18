<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proj_Mem extends Model
{
    use HasFactory; 
    protected $fillable = ['project_id','member_id'];
}
