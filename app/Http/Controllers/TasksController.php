<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加
use App\Start;    // 追加
use App\Stop;    // 追加
use App\User;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $key = $request->key;
            $ord = $request->ord;
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            return view('tasks.index', $data);
        }
        

        $count = User::count();
        $taskcount = Task::count();
        $taskmcount = Task::where('status',1)->count();

        return view('welcome',['usercnt' => $count,
                                'taskcnt' => $taskcount,
                                'taskmcnt' => $taskmcount
                                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check()) {

        $this->validate($request, [
            'name' => 'required|max:191',
        ]);
        
        $user = \Auth::user();
        $request->user()->tasks()->create([
        'name' => $request->name,
        'timer' => 0,
        'status' => 0,
        'start_at'=>now(),
        'stop_at'=>now(),
        'lastcomment'=>"",
            ]);


        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);
            $start = Start::where('task_id',$id)->where('user_id',$user['id'])->get();
            $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->get();



        if ($task['user_id']==$user['id']){
        return view('tasks.show', [
            'tasks' => $task,
            'starts' => $start,
            'stops' => $stop,
        ]);
        }

        }
    


        return redirect('/');


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
    public function update(Request $request, $id)//完了処理
    {
        
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);
            $this->validate($request, [
            'content' => 'required|max:191',
        ]);
        if ($task['user_id']==$user['id']){
        
        
        $task->lastcomment = $request['content'];
        $task->status = 3;
        $task->save();
        }

    }
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)//削除する
    {
        
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);
            
        if ($task['user_id']==$user['id']){
        

        $task->delete();
        }

    }
        return redirect('/');
    }

    public function finish($id)//完了ページ入力
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
    
}
