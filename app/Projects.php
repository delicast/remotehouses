<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    //
    protected $table = 'project';

    public function grids (){
        return $this->belongsToMany('App\Grids','grid_project','project_id','grid_id')->withTimestamps();
    }

    public function users (){
        return $this->belongsToMany('App\User','project_user','project_id','user_id')->withPivot('level_id')->withTimestamps();
    }
    public function pointtypes (){
        return $this->belongsToMany('App\Pointtypes','project_pointtype','project_id','pointtype_id')->withTimestamps();
    }


}
