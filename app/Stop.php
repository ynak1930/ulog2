<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
        protected $fillable = ['content', 'user_id','task_id'];
        public function stops()
    {
        return $this->belongsToMany(Task::class, 'stops', 'task_id','user_id')->withTimestamps();
    }
}
