<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Start extends Model
{
        protected $fillable = ['content', 'user_id','task_id'];
        public function starts()
    {
        return $this->belongsToMany(Start::class, 'starts', 'task_id','user_id', 'content')->withTimestamps();
    }
}
