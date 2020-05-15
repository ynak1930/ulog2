<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
        protected $fillable = ['content','status','timer', 'user_id','task_id'];
        public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activities', 'task_id','user_id', 'content')->withTimestamps();
    }

}