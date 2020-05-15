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
    
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    
    
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
    public function starts()
    {
        return $this->belongsToMany(Task::class, 'starts', 'user_id', 'task_id')->withTimestamps();
    }
    
    public function activities()
    {
        return $this->belongsToMany(Task::class, 'activities', 'user_id', 'task_id')->withTimestamps();
    }

    public function statuses()
    {
        return $this->belongsToMany(Task::class, 'statuses', 'user_id', 'task_id')->withTimestamps();
    }

    public function start($id,$content)
    {
                $this->starts()->attach($id,['content' => $content]);
                $this->activities()->attach($id,['content' => $content,'status'=>1,'timer'=>0]);
                
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
                $user = \Auth::user();
                
                
                $task = Task::find($id);
                if ($task->user_id==$user->id){
                    $category = Category::find($task->category_id);
                    if ($category->user_id==$user->id){
                    $category->updated_at = now();
                    $category->save();
                    }
                }
                
                $start_last = Activity::where('task_id',$id)->where('user_id',$user->id)->where('status',1)->orderBy('created_at', 'desc')->first();

                $timestamp = strtotime($start_last->created_at);// 1つ目の時刻==start_atに代わる物
                $timestamp2 = strtotime(now());
                
            //================tasksテーブルの操作==============================
                $task = Task::find($id);
                //=========時間の計算==========================================
                /*
                $task->stop_at = now();
                $timestamp = strtotime($task->start_at);// 1つ目の時刻
                $timestamp2 = strtotime($task->stop_at);// 2つ目の時刻
                */
                $timestamp3 =  $timestamp2 - $timestamp;// 2つの時刻の差を計算
                
                if ($timestamp3<0){
                    $timestamp3 = $timestamp3 * -1;
                }
                
                $hour_from = sprintf('%02d', floor( $task->timer / 3600 ));//hour
                $min_from = sprintf('%02d',floor( ( $task->timer / 60 ) % 60 ));//minute
                $sec_from = sprintf('%02d',$task->timer % 60);//second

                $hour = sprintf('%02d', floor( $timestamp3 / 3600 ));//hour
                $min = sprintf('%02d',floor( ( $timestamp3 / 60 ) % 60 ));//minute
                $sec = sprintf('%02d',($timestamp3 % 60));//second
                
                $hour_to = sprintf('%02d', floor( ($task->timer+$timestamp3) / 3600 ));//hour
                $min_to = sprintf('%02d',floor( ( ($task->timer+$timestamp3) / 60 ) % 60 ));//minute
                $sec_to = sprintf('%02d',($task->timer+$timestamp3) % 60);//second

                if (floor($task->timer / 3600/24)>0){
                            $day = floor($task->timer / 3600/24);//day
                }
                
                //$task->timer  //加算前のタイマー値
                //$timestamp3   //加算する実行者の今回のタイマー値
                //===========================================================
                //$contentにタイムスタンプの情報を添加--------------------------------
                $content = "[".$hour_from.":".$min_from.":".$sec_from."]～[".$hour_to.":".$min_to.":".$sec_to."]".PHP_EOL."[".$hour.":".$min.":".$sec."]".PHP_EOL.$content;
                //-----------------------------------------------------------------------
                $task->lastcomment = $content;
                $task->status = 2;    //0=新規作成 , 1=開始 , [2=停止], 3=完了
                if ($task->timer>=2147483646)
                {
                    $task->timer=2147483646;
                }else{
                    $task->timer = $task->timer + $timestamp3;//時間を加算する
                }

                $this->activities()->attach($id,['content' => $content,'status'=>2,'timer'=>$task->timer]);
                $task->save();
                
            //======================================================tasksテーブルの操作
            
            
            //=======stopsテーブルへのアタッチ(content)================================
                            $this->stops()->attach($id,['content' => $content]);
            //========================================================stopsテーブルへのアタッチ
            return true;

    }

    public function pause($id)
    {
                $user = \Auth::user();
                
                $task = Task::find($id);
                if ($task->user_id==$user->id){
                    $category = Category::find($task->category_id);
                    if ($category->user_id==$user->id){
                    $category->updated_at = now();
                    $category->save();
                    }
                }

                $start_last = Activity::where('task_id',$id)->where('user_id',$user->id)->where('status',1)->orderBy('created_at', 'desc')->first();

                $timestamp = strtotime($start_last->created_at);// 1つ目の時刻==start_atに代わる物
                $timestamp2 = strtotime(now());

                
            //================tasksテーブルの操作==============================
                $task = Task::find($id);
                $content = '中断';
                //=========時間の計算==========================================
                /*
                $task->stop_at = now();
                $timestamp = strtotime($task->start_at);// 1つ目の時刻
                $timestamp2 = strtotime($task->stop_at);// 2つ目の時刻
                */
                $timestamp3 =  $timestamp2 - $timestamp;// 2つの時刻の差を計算
                if ($timestamp3<0){
                    $timestamp3 = $timestamp3 * -1;
                }
                
                $hour_from = sprintf('%02d', floor( $task->timer / 3600 ));//hour
                $min_from = sprintf('%02d',floor( ( $task->timer / 60 ) % 60 ));//minute
                $sec_from = sprintf('%02d',$task->timer % 60);//second

                $hour = sprintf('%02d', floor( $timestamp3 / 3600 ));//hour
                $min = sprintf('%02d',floor( ( $timestamp3 / 60 ) % 60 ));//minute
                $sec = sprintf('%02d',($timestamp3 % 60));//second
                
                $hour_to = sprintf('%02d', floor( ($task->timer+$timestamp3) / 3600 ));//hour
                $min_to = sprintf('%02d',floor( ( ($task->timer+$timestamp3) / 60 ) % 60 ));//minute
                $sec_to = sprintf('%02d',($task->timer+$timestamp3) % 60);//second

                if (floor($task->timer / 3600/24)>0){
                            $day = floor($task->timer / 3600/24);//day
                }
                
                //$task->timer  //加算前のタイマー値
                //$timestamp3   //加算する実行者の今回のタイマー値
                //===========================================================
                //$contentにタイムスタンプの情報を添加--------------------------------
                //-----------------------------------------------------------------------
                $task->lastcomment = '中断';
                $task->status = 3;    //0=新規作成 , 1=開始 , [2=停止], 3=完了
                if ($task->timer>=2147483646)
                {
                    $task->timer=2147483646;
                }else{
                    $task->timer = $task->timer + $timestamp3;//時間を加算する
                }
                $content = "[".$hour_from.":".$min_from.":".$sec_from."]～[".$hour_to.":".$min_to.":".$sec_to."]".PHP_EOL."[".$hour.":".$min.":".$sec."]".PHP_EOL.$content;

                $this->activities()->attach($id,['content' => $content,'status'=>3,'timer'=>$task->timer]);
                $task->save();
                
            //======================================================tasksテーブルの操作
            
            
            //=======stopsテーブルへのアタッチ(content)================================
                            $this->stops()->attach($id,['content' => $content]);
            //========================================================stopsテーブルへのアタッチ
            return true;

    }

    public function finish($id,$content)
    {
                $user = \Auth::user();

                $task = Task::find($id);
                if ($task->user_id==$user->id){
                    $category = Category::find($task->category_id);
                    if ($category->user_id==$user->id){
                    $category->updated_at = now();
                    $category->save();
                    }
                }                       

                $start_last = Activity::where('task_id',$id)->where('user_id',$user->id)->orderBy('created_at', 'desc')->first();

                $timestamp = strtotime($start_last->created_at);// 1つ目の時刻==start_atに代わる物
                $timestamp2 = strtotime(now());


                $task = Task::find($id);

                if ($start_last->status==1){
                $timestamp3 =  $timestamp2 - $timestamp;// 2つの時刻の差を計算
                
                if ($timestamp3<0){
                    $timestamp3 = $timestamp3 * -1;
                }
                
                $hour_from = sprintf('%02d', floor( $task->timer / 3600 ));//hour
                $min_from = sprintf('%02d',floor( ( $task->timer / 60 ) % 60 ));//minute
                $sec_from = sprintf('%02d',$task->timer % 60);//second

                $hour = sprintf('%02d', floor( $timestamp3 / 3600 ));//hour
                $min = sprintf('%02d',floor( ( $timestamp3 / 60 ) % 60 ));//minute
                $sec = sprintf('%02d',($timestamp3 % 60));//second
                
                $hour_to = sprintf('%02d', floor( ($task->timer+$timestamp3) / 3600 ));//hour
                $min_to = sprintf('%02d',floor( ( ($task->timer+$timestamp3) / 60 ) % 60 ));//minute
                $sec_to = sprintf('%02d',($task->timer+$timestamp3) % 60);//second

                if (floor($task->timer / 3600/24)>0){
                            $day = floor($task->timer / 3600/24);//day
                }
                
                //$task->timer  //加算前のタイマー値
                //$timestamp3   //加算する実行者の今回のタイマー値
                //===========================================================
                //$contentにタイムスタンプの情報を添加--------------------------------
                $content = "[".$hour_from.":".$min_from.":".$sec_from."]～[".$hour_to.":".$min_to.":".$sec_to."]".PHP_EOL."[".$hour.":".$min.":".$sec."]".PHP_EOL.$content;
                //-----------------------------------------------------------------------
                $task->lastcomment = $content;

                if ($task->timer>=2147483646)
                {
                    $task->timer=2147483646;
                }else{
                    $task->timer = $task->timer + $timestamp3;//時間を加算する
                }
                }else{
                    $task->timer=0;
                }
                $task->status = 4;    //0=新規作成 , 1=開始 , [2=停止], 4=完了
                $this->activities()->attach($id,['content' => $content,'status'=>4,'timer'=>$task->timer]);
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
