<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加
use App\Start;    // 追加
use App\Stop;    // 追加
use App\User;
use App\Category;

class FinishesControllerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)//完了ページの表示
    {
        if (\Auth::check()) {
            $task_id = $id;
            $user = \Auth::user();
            $task = Task::find($id);

   
            if ($task['user_id']==$user['id']){
                    return view('tasks.finish', [
                        'task' => $task,
                    ]);

            }
        }
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)//完了処理
    {
        $message = '';
        if ($request->content){
            
        }else{
            return redirect('/');
        }
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);
            $this->validate($request, [
            'content' => 'required|max:191',
        ]);
        if ($task['user_id']==$user['id']){
        

            $user->finish($id,$request['content']);

        
        $task->lastcomment = $request['content'];
        $task->status = 4;
        $task->save();
        $message = $task->name.'を完了しました。';
        }else{
        $message = '失敗しました。';
        }

    }
        return redirect('/')->with('flash_message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
