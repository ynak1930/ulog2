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

            //================tasksテーブルの操作==============================
                $task = Task::find($id);

                //=========時間の計算==========================================
                $task->stop_at = now();
                $timestamp = strtotime($task->start_at);// 1つ目の時刻
                $timestamp2 = strtotime($task->stop_at);// 2つ目の時刻
                
                $timestamp3 =  $timestamp2 - $timestamp;// 2つの時刻の差を計算
                
                if ($timestamp3<0){
                    $timestamp3 = $timestamp3 * -1;
                }
                

                
                //$task->timer  //加算前のタイマー値
                //$timestamp3   //加算する実行者の今回のタイマー値
                //===========================================================
                //$contentにタイムスタンプの情報を添加--------------------------------
                $content = "[".date('H:i:s',$task->timer-9*60*60)."]～[".date('H:i:s',$task->timer+$timestamp3-9*60*60)."]".PHP_EOL."[".date('H:i:s',$timestamp3-9*60*60)."]".PHP_EOL.$content;
                //-----------------------------------------------------------------------
                $task->lastcomment = $content;
                $task->status = 2;    //0=新規作成 , 1=開始 , [2=停止], 3=完了
                if ($task->timer>=2147483646)
                {
                    $task->timer=2147483646;
                }else{
                    $task->timer = $task->timer + $timestamp3;//時間を加算する
                }

                
                $task->save();
                
            //======================================================tasksテーブルの操作
            //=======stopsテーブルへのアタッチ(content)================================
                            $this->stops()->attach($id,['content' => $content]);
            //========================================================stopsテーブルへのアタッチ
            return true;

    }
    
    public function counts()
    {
            $this->count();
            
            return $user;
    }
    
}
