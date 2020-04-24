<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加
use App\Start;    // 追加

class StartsController extends Controller
{

    
    public function create($id)
    {
        
        if (\Auth::check()) {
            $task_id = $id;
            $user = \Auth::user();
            $task = Task::find($id);

            
            if ($task['user_id']==$user['id']){
            
                if ($task['status']==0){    //タスクのステータスが0=停止中ならスタート時のコメントを入力
                    return view('tasks.start', [
                        'task' => $task,
                    ]);
                }
            }
        }
    return redirect('/');
    }
    



    public function update(Request $request,$id)
    {

        if (\Auth::check()) {

        $this->validate($request, [
            'content' => 'required|max:191',
        ]);

        \Auth::user()->start($id,$request['content']);

        }



        return redirect('/');
    }
}
