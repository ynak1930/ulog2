<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加
use App\Stop;    // 追加

class StopsController extends Controller
{
    public function create($id)
    {
        
        if (\Auth::check()) {
            $task_id = $id;
            $user = \Auth::user();
            $task = Task::find($id);

   
            if ($task['user_id']==$user['id']){
//status==1で動いてないとストップできないのでここは消さない
                if ($task['status']==1){    //タスクのステータスが1で稼働中なら停止時のコメントを入力
                    return view('tasks.stop', [
                        'task' => $task,
                    ]);
                }
            }else{
            $message = 'Not Found';
             return redirect('/')->with('flash_message', $message);
            }
        }
        return redirect('/');
    }
    



    public function update(Request $request,$id)
    {

        if (\Auth::check()) {

        $this->validate($request, [
            'content' => 'required|max:626',
        ]);

        \Auth::user()->stop($id,$request['content']);

        }



        return redirect('/');
    }
}
