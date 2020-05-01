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
            
            
            if ($cur_category>0){
                $category = Category::find($cur_category);
                if ($category){
                if ($category->user_id == $user->id){
                    $category_flg = TRUE;
                }
                }
            }else{
                $category_flg = FALSE;
            }
            
            switch ($request->sortby) {
                case 0:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->orderBy('timer', 'asc')->paginate(10);
                    }else{
                    $tasks = $user->tasks()->orderBy('timer', 'asc')->paginate(10);//稼働時間が短い
                    }
                    break;
                case 1:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->orderBy('timer', 'desc')->paginate(10);//稼働時間が長い
                    }else{
                    $tasks = $user->tasks()->orderBy('timer', 'desc')->paginate(10);//稼働時間が長い
                    }
                    break;
                case 2:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->orderBy('stop_at', 'asc')->paginate(10);//最後に停止した時間が古い
                    }else{
                    $tasks = $user->tasks()->orderBy('stop_at', 'asc')->paginate(10);//最後に停止した時間が古い
                    }
                    break;
                case 3:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->orderBy('stop_at', 'desc')->paginate(10);//最後に停止した時間が新しい
                    }else{
                    $tasks = $user->tasks()->orderBy('stop_at', 'desc')->paginate(10);//最後に停止した時間が新しい
                    }
                    break;
                case 4:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->orderBy('start_at', 'asc')->paginate(10);//最後に開始した時間が古い
                    }else{
                    $tasks = $user->tasks()->orderBy('start_at', 'asc')->paginate(10);//最後に開始した時間が古い
                    }
                    break;
                case 5:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->orderBy('start_at', 'desc')->paginate(10);//最後に開始した時間が新しい
                    }else{
                    $tasks = $user->tasks()->orderBy('start_at', 'desc')->paginate(10);//最後に開始した時間が新しい
                    }
                    break;
                case 6:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->where('status',0)->orderBy('created_at', 'desc')->paginate(10);//新規プロジェクト
                    }else{
                    $tasks = $user->tasks()->where('status',0)->orderBy('created_at', 'desc')->paginate(10);//新規プロジェクト
                    }
                    break;
                case 7:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->where('status',1)->orderBy('created_at', 'desc')->paginate(10);//実行中プロジェクト
                    }else{
                    $tasks = $user->tasks()->where('status',1)->orderBy('created_at', 'desc')->paginate(10);//実行中プロジェクト
                    }
                    break;
                case 8:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->where('status',2)->orderBy('created_at', 'desc')->paginate(10);//停止中プロジェクト
                    }else{
                    $tasks = $user->tasks()->where('status',2)->orderBy('created_at', 'desc')->paginate(10);//停止中プロジェクト
                    }
                    break;
                case 9:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->where('status',3)->orderBy('created_at', 'desc')->paginate(10);//完了したプロジェクト
                    }else{
                    $tasks = $user->tasks()->where('status',3)->orderBy('created_at', 'desc')->paginate(10);//完了したプロジェクト
                    }
                    break;
                case 10:
                     if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->orderBy('created_at', 'asc')->paginate(10);//作成日が古い
                    }else{
                    $tasks = $user->tasks()->orderBy('created_at', 'asc')->paginate(10);//作成日が古い
                    }
                    break;
                default:
                    if ($category_flg){
                    $tasks = $user->tasks()->where('category_id',$cur_category)->orderBy('created_at', 'desc')->paginate(10);//作成日が新しい
                    }else{
                    $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);//作成日が新しい
                    }
                    break;
            }



            $categories = $user->categories()->get();



            $data = [
                'user' => $user,
                'tasks' => $tasks,
                'categories' => $categories,
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
        ]);

        $message = '';
            $cur_cat = $request->category;

        $user = \Auth::user();
        $request->user()->tasks()->create([
        'name' => $request->name,
        'timer' => 0,
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
    public function show($id)
    {
    
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);
            $start = Start::where('task_id',$id)->where('user_id',$user['id'])->get();
            $stop = Stop::where('task_id',$id)->where('user_id',$user['id'])->get();
            $categories = $user->categories()->get();


        if ($task['user_id']==$user['id']){
        return view('tasks.show', [
            'tasks' => $task,
            'starts' => $start,
            'stops' => $stop,
            'categories' => $categories,
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
    public function edit(Request $request, $id)
    {
        $message = '';
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);

        if ($task['user_id']==$user['id']){

        $task->category_id = $request['category'];
        $task->save();
        $message = 'カテゴリーを変更しました。';
        }
    }else{
        $message = '失敗しました。';
    }
        
        return redirect('/')->with('flash_message', $message);
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
        $message = '';
        if (\Auth::check()) {
            $user = \Auth::user();
            $task = Task::find($id);
            $this->validate($request, [
            'content' => 'required|max:191',
        ]);
        if ($task['user_id']==$user['id']){
        
        if ($task->status==1){//タスクが動いてたら停止処理
            \Auth::user()->stop($id,$request['content']);
        }else{//その他開始コメ入れて、停止コメ入れる
            \Auth::user()->start($id,$request['content']);
            \Auth::user()->stop($id,$request['content']);
        }
        
        $task->lastcomment = $request['content'];
        $task->status = 3;
        $task->save();
        $message = $task->name.'を完了しました。';
        }else{
        $message = '失敗しました。';
        }

    }
        return redirect('/')->with('flash_message', $message);
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
