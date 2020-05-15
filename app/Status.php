<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
        protected $fillable = ['lastcomment','status', 'user_id','task_id','start_at','stop_at'];
        public function statuses()
    {
        return $this->belongsToMany(Status::class, 'statuses', 'task_id','user_id', 'status','lastcomment')->withTimestamps();
    }

}
