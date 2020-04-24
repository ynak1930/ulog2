<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
    public function starts()
    {
        return $this->belongsToMany(Task::class, 'starts', 'user_id', 'task_id')->withTimestamps();
    }
    
    public function start($id,$content)
    {
                $this->starts()->attach($id,['content' => $content]);
                
                $task = Task::find($id);
                $task->lastcomment = $content;
                $task->start_at = now();
                $task->status = 1;    // 追加
                $task->save();                

            return true;

    }
    
    public function stops()
    {
        return $this->belongsToMany(Task::class, 'stops', 'user_id', 'task_id')->withTimestamps();
    }
    
    public function stop($id,$content)
    {
                $this->stops()->attach($id,['content' => $content]);
                
                $task = Task::find($id);

                $task->lastcomment = $content;
                $task->stop_at = now();
                $task->status = 0;    // 追加                

                // 1つ目の時刻
                $timestamp = strtotime($task->start_at);
                // 2つ目の時刻
                $timestamp2 = strtotime($task->stop_at);
                
                $timestamp3 =  $timestamp2 - $timestamp;// 2つの時刻の差を計算
                
                if ($timestamp3<0){
                    $timestamp3 = $timestamp3 * -1;
                }    
                
                $task->timer = $task->timer + $timestamp3;//時間を加算する

                $task->save();                

            return true;

    }
    
}
