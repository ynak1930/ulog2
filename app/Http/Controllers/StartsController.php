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
  
//      新規作成時　にスタートしてもOK
//      スタート中にスタートすることはないけど別に問題ないのでOK
//      ストップ中にスタートすることはあるのでOK
//      完了しても何かの拍子に動かしたくなるかもしれないのでOK
// 消す             if ($task['status']==0){    //タスクのステータスが0=停止中ならスタート時のコメントを入力
                    return view('tasks.start', [
                        'task' => $task,
                    ]);
//                }
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

        \Auth::user()->start($id,$request['content']);

        }



        return redirect('/');
    }
}
