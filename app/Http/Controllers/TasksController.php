<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加
use App\Start;    // 追加
use App\Stop;    // 追加
use App\User;
use App\Category;


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
            $cur_category = $request->categories;
            $category_flg = FALSE;


            $timersums=Task::where('user_id',$user->id)->selectRaw('category_id, sum(timer) as timersum')->groupBy('category_id')->orderBy('timersum', 'desc')->get();
   
            $category=Category::where('user_id',$user->id)->get();

            foreach ($category as $cat){
                $cat->timersum = Task::where('user_id',$user->id)->where('category_id',$cat->id)->sum('timer');
                $cat->save();
            }
            

            switch ($request->sortby) {
                case 0:
                    $categories = $user->categories()->orderBy('timersum', 'asc')->get();
                    $tasks = $user->tasks()->orderBy('timer', 'asc')->get();//稼働時間が短い
                    break;
                case 1:
                    $categories = $user->categories()->orderBy('timersum', 'desc')->get();
                    $tasks = $user->tasks()->orderBy('timer', 'desc')->get();//稼働時間が長い
                    break;
                case 2:
                    $categories = $user->categories()->orderBy('updated_at', 'asc')->get();
                    $tasks = $user->tasks()->orderBy('updated_at', 'asc')->get();//最後に停止した時間が古い→実行したのが古い
                    break;
                case 3:
                    $categories = $user->categories()->orderBy('updated_at', 'desc')->get();
                    $tasks = $user->tasks()->orderBy('updated_at', 'desc')->get();//最後に停止した時間が新しい→実行したのが新しい
                    break;
                case 4:
                    $categories = $user->categories()->orderBy('updated_at', 'desc')->get();
                    $tasks = $user->tasks()->orderBy('start_at', 'asc')->get();//最後に開始した時間が古い(消す)
                    break;
                case 5:
                    $categories = $user->categories()->orderBy('updated_at', 'desc')->get();
                    $tasks = $user->tasks()->orderBy('start_at', 'desc')->get();//最後に開始した時間が新しい(消す)
                    break;
                case 6:
                    $categories = $user->categories()->orderBy('timersum', 'desc')->get();
                    $tasks = $user->tasks()->where('status',0)->orderBy('created_at', 'desc')->get();//新規プロジェクト
                    break;
                case 7:
                    $categories = $user->categories()->orderBy('timersum', 'desc')->get();
                    $tasks = $user->tasks()->where('status',1)->orderBy('updated_at', 'desc')->get();//実行中プロジェクト
                    break;
                case 8:
                    $categories = $user->categories()->orderBy('timersum', 'desc')->get();
                    $tasks = $user->tasks()->where('status',2)->orderBy('updated_at', 'desc')->get();//停止中プロジェクト
                    break;
                case 9:
                    $categories = $user->categories()->orderBy('timersum', 'desc')->get();
                    $tasks = $user->tasks()->where('status',4)->orderBy('updated_at', 'desc')->get();//完了したプロジェクト
                    break;
                case 10:
                    $categories = $user->categories()->orderBy('created_at', 'asc')->get();
                    $tasks = $user->tasks()->orderBy('created_at', 'asc')->get();//作成日が古い
                    break;
                case 11:
                    $categories = $user->categories()->orderBy('created_at', 'desc')->get();
                    $tasks = $user->tasks()->orderBy('created_at', 'desc')->get();//作成日が新しい
                    break;
                case 12:
                    $categories = $user->categories()->orderBy('timersum', 'desc')->get();
                    $tasks = $user->tasks()->where('status',3)->orderBy('updated_at', 'desc')->get();//中断中プロジェクト
                    break;
                default:
                    $categories = $user->categories()->orderBy('updated_at', 'desc')->get();
                    $tasks = $user->tasks()->orderBy('updated_at', 'desc')->get();//すべて
                    break;
            }

            $alltimersum = Category::where('user_id',$user->id)->sum('timersum');

            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
                'categories' => $categories,
                'timersums' => $timersums,
                'alltimersum' => $alltimersum,
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
        if (\Auth::check()) {
        $user = \Auth::user();
        $task = new Task;
        $categories = $user->categories()->orderBy('created_at', 'desc')->get();

        return view('tasks.create', [
            'task' => $task,
            'categories' => $categories,
        ]);
        }
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
            //'timer' => 'nullable|integer|min:0|max:2147483646',
            'hour' => 'nullable|integer|min:0|max:33333333',
            'minute' => 'nullable|integer|min:0|max:59',
            'second' => 'nullable|integer|min:0|max:59'
        ]);

        $timer = 0;

        if ($request->hour){
            if ($request->hour>0){
                if ($request->hour<33333333){
                    $timer = $request->hour*3600;
                }
            }
        }

        if ($request->minute){
            if ($request->minute>0){
                if ($request->minute<60){
                    $timer = $timer + $request->minute*60;
                }
            }
        }

        if ($request->second){
            if ($request->second>0){
                if ($request->second<60){
                    $timer = $timer + $request->second;
                }
            }
        }

        /*if ($request->timer){
            $timer = $request->timer;
        }else{
            $timer = 0;
        }*/

        $message = '';
            $cur_cat = $request->category;

        $user = \Auth::user();
        $request->user()->tasks()->create([
        'name' => $request->name,
        'timer' => $timer,
        'status' => 0,
        'start_at'=>now(),
        'stop_at'=>now(),
        'lastcomment'=>"",
        'category_id'=> $cur_cat,
            ]);
            

        $message = 'プロジェクト'.$request->name.'を追加しました。';

        }else{
        $message = '失敗しました。';
        }
        return redirect('/')->with('flash_message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $message = '';
        $sort = $request->sort;

        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);
            if($task){
                
            if ($sort){
                switch ($sort) {
                    case 1://新しい順
                        $start = Start::where('task_id',$id)->where('user_id',$user['id'])->orderBy('created_at', 'desc')->get();
                        $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->orderBy('created_at', 'desc')->get();
                    break;
                    case 2://古い順
                        $start = Start::where('task_id',$id)->where('user_id',$user['id'])->orderBy('created_at', 'asc')->get();
                        $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->orderBy('created_at', 'asc')->get();

                    break;
                    case 3://今日のみ
                        $now = date('Y-m-d');

                        $start = Start::where('task_id',$id)->where('user_id',$user['id'])->whereDate('created_at', '=', $now)->get();
                        $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->whereDate('created_at', '=', $now)->get();
                    break;
                    case 4://今週のみ
                    $day7 = date("Y-m-d",strtotime("-7 day"));

                        $now = date('Y-m-');

                        $start = Start::where('task_id',$id)->where('user_id',$user['id'])->whereDate('created_at', '>=', $day7)->get();
                        $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->whereDate('created_at', '>=', $day7)->get();

                    break;
                    case 5://今月のみ
                        $year = date('Y');
                        $month = date('m');


                        
                        $start = Start::where('task_id',$id)->where('user_id',$user['id'])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->get();
                        $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->get();
                    break;
                    default:
                        $start = Start::where('task_id',$id)->where('user_id',$user['id'])->orderBy('created_at', 'desc')->get();
                        $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->orderBy('created_at', 'desc')->get();
                    break;
                }
            }else{
                $start = Start::where('task_id',$id)->where('user_id',$user['id'])->orderBy('created_at', 'desc')->get();
                $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->orderBy('created_at', 'desc')->get();
            }

        
            $categories = $user->categories()->get();
            }else{
            $message = 'Not Found';
             return redirect('/')->with('flash_message', $message);
            }



        if ($task['user_id']==$user['id']){

        return view('tasks.show', [
            'tasks' => $task,
            'starts' => $start,
            'stops' => $stop,
            'categories' => $categories,
        ])->with('flash_message', $message);
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
    public function edit(Request $request, $id)
    {
        $message = '';
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);

        $this->validate($request, [
            'category' => 'nullable|integer',
            'hour' => 'nullable|integer|min:0|max:33333333',
            'minute' => 'nullable|integer|min:0|max:59',
            'second' => 'nullable|integer|min:0|max:59',
        ]);

        if ($task['user_id']==$user['id']){

            if ($request['category']!=null){
                
                $task->category_id = $request['category'];
                $task->save();


                $message = 'カテゴリーを変更しました。'.PHP_EOL;
            }else{

                $timer = 0;

                if ($request->hour){
                    if ($request->hour>0){
                        if ($request->hour<33333333){
                            $timer = $request->hour*3600;
                        }
                    }
                }

                if ($request->minute){
                    if ($request->minute>0){
                        if ($request->minute<60){
                            $timer = $timer + $request->minute*60;
                        }
                    }
                }

                if ($request->second){
                    if ($request->second>0){
                        if ($request->second<60){
                            $timer = $timer + $request->second;
                        }
                    }
                }
                $task->timer = $timer;
                $task->save();
                $message = $message.'タイマー値を変更しました。';
            }
        }
    }else{
        $message = '失敗しました。';
    }
        
        return back()->with('flash_message', $message);
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
    //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)//削除する
    {
        $message = '';
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);
            
        if ($task['user_id']==$user['id']){
        
            $message = $task->name.'を削除しました。';
        $task->delete();
        }else{
            $message = '失敗しました。';
        }

    }
        return redirect('/')->with('flash_message', $message);
    }


}
