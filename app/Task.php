<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name','user_id', 'timer','status','start_at','stop_at','lastcomment','category_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsToMany('activities', 'user_id','task_id')->withTimestamps();//???
    }

    public function status()
    {
        return $this->belongsToMany('statuses', 'user_id','task_id')->withTimestamps();//???
    }

    public function taskstarts()
    {
        return $this->belongsToMany('starts', 'user_id','task_id')->withTimestamps();//???
    }
    
    public function taskstops()
    {
        return $this->belongsToMany('stops', 'user_id','task_id')->withTimestamps();//???
    }
}
