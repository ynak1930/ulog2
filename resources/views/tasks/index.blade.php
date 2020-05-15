@extends('layouts.app')

@section('content')


    @if (Auth::check())
        <!-- フラッシュメッセージ -->
        @if (session('flash_message'))
            <div class="flash_message alert alert-info">
                {{ session('flash_message') }}
            </div>
        @endif
        

    <div>
        <h1>{{ Auth::user()->name }} - {!! link_to_route('tasks.create', '新規プロジェクトの投稿', [], ['class' => 'btn btn-primary']) !!}
            </h1>
                        <span class="mr-4">
                            <script src="{{ asset('/js/sort.js') }}"></script>
                            <form name="sort_form" style="display: inline">
                            <select name="sort" onchange="dropsort()">
                                <option value="">並べ替え</option>
                                <option value="{{ route('tasks.index', ['sortby' => 6]) }}">新規プロジェクトのみ</option>
                                <option value="{{ route('tasks.index', ['sortby' => 7]) }}">実行中プロジェクトのみ</option>
                                <option value="{{ route('tasks.index', ['sortby' => 8]) }}">停止中プロジェクトのみ</option>
                                <option value="{{ route('tasks.index', ['sortby' => 9]) }}">完了したプロジェクトのみ</option>
                                <option value="{{ route('tasks.index', ['sortby' => 11]) }}">作成日が新しい</option>
                                <option value="{{ route('tasks.index', ['sortby' => 1]) }}">稼働時間が長い</option>
                                <option value="{{ route('tasks.index', ['sortby' => 3]) }}">停止した時間が新しい</option>
                                <option value="{{ route('tasks.index', ['sortby' => 5]) }}">開始した時間が新しい</option>
                                <option value="{{ route('tasks.index', ['sortby' => 10]) }}">作成日が古い</option>
                                <option value="{{ route('tasks.index', ['sortby' => 0]) }}">稼働時間が短い</option>
                                <option value="{{ route('tasks.index', ['sortby' => 2]) }}">停止した時間が古い</option>
                                <option value="{{ route('tasks.index', ['sortby' => 4]) }}">開始した時間が古い</option>
                            </select>
                        </form>
                        
                        </span>
                        
                        <span>
                            <script src="{{ asset('/js/sort2.js') }}"></script>
                            <form name="sort_form2" style="display: inline">
                            <select name="sort2" onchange="dropsort2()">
                                <option value="">カテゴリー</option>
                                <option value="{{ route('tasks.index', ['categories' => 0]) }}">すべて</option>
                        @if (count($categories) > 0)
                           @foreach ($categories as $category)
                               <option value="{{ route('tasks.index', ['categories' => $category->id]) }}">{{$category->category}}</option>
                                
                            @endforeach
                        @endif
                            </select>
                        </form>
                        を表示
                        </span>
                        
    </div>


    @if (count($tasks) > 0)


        <div class="m-3">
            <p>
            <span id="timers_base" class="text-center"><strong id="timers"></strong></span>
            </p>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">id</th>
                    <th class="text-left"><span>プロジェクト名</span></th>
                    <th class="text-left" colspan=3>稼働時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>

                    @if ($task->status==0)<!--new-->
                        <td class="text-center alert alert-info">
                    @elseif ($task->status==1)<!--start(move)-->
                        <td class="alert alert-success text-center">
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td class="alert alert-danger text-center">
                    @elseif ($task->status==3)<!--pause-->
                        <td class="alert alert-warning text-center">
                    @elseif ($task->status==4)<!--finish(finish)-->
                        <td class="alert alert-dark text-center">
                    @else
                        <td>
                    @endif
                        {!! link_to_route('tasks.show', $task->id, ['id' => $task->id]) !!}

                    </td>
                    
                    @if ($task->status==0)<!--new-->
                        <td class="text-center alert alert-info">
                    @elseif ($task->status==1)<!--start(move)-->
                        <td class="alert alert-success text-center">
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td class="alert alert-danger text-center">
                    @elseif ($task->status==3)<!--pause-->
                        <td class="alert alert-warning text-center">
                    @else<!--status==4 finish(finish)-->
                        <td class="alert alert-secondary text-center">
                    @endif
                            <strong style="word-break: break-all;">{{ $task->name }}</strong>
                            <div>
                            @if ($task->category_id==0)
                                未分類
                            @else

                                @foreach ($categories as $category)
                                    @if ($task->category_id==$category->id)
                                        {{$category->category}}
                                    @endif
                                @endforeach

                            @endif
                            </div>

                    </td>
                    @if ($task->status==0)<!--new-->
                        <td class="text-left alert alert-info">
                            <span class="badge badge-info">新着</span>
                    @elseif ($task->status==1)<!--start(move)-->
                        <td class="alert alert-success text-left">

                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td class="alert alert-danger text-left">
                            <span class="badge badge-danger">停止中</span>
                    @elseif ($task->status==3)<!--pause-->
                        <td class="alert alert-warning text-left">
                            <span class="badge badge-warning">一時停止中</span>
                    @elseif ($task->status==4)<!--finish(finish)-->
                        <td class="alert alert-secondary text-left">
                            <span class="badge badge-dark">完了</span>
                    @else
                        <td>
                    @endif
                        
                            @if ($task->status!=1)
                            <!--タイムゾーンの設定で9時間足されちゃうので9時間マイナス・他にいい方法が無いか探す-->
                            {{sprintf('%02d', floor( $task->timer / 3600 ))}}:{{sprintf('%02d',floor( ( $task->timer / 60 ) % 60 ))}}:{{sprintf('%02d',$task->timer % 60)}}
                            @if (floor($task->timer / 3600/24)>0)
                            ({{floor($task->timer / 3600/24)}}日)
                            @endif
                            @elseif ($task->status==1)
                                <span class="badge badge-success">実行中</span>
                                <span><strong id="{{$task->id}}"></strong></span>
                                <span>[<strong id="{{$task->id}}_cur"></strong>]</span>
                            @endif

                    </td>
                    @if ($task->status==0)<!--new-->
                        <td class="text-center alert alert-info">
                    @elseif ($task->status==1)<!--start(move)-->
                        <td class="alert alert-success text-center">
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td class="alert alert-danger text-center">
                    @elseif ($task->status==3)<!--pause-->
                        <td class="alert alert-warning text-center">
                    @elseif ($task->status==4)<!--finish(finish)-->
                        <td class="alert alert-secondary text-center">
                    @else
                        <td>
                    @endif
                    
                        @if ($task->status!=1)<!--status!=1　新規=0、2=stop、 3=finishならスタートボタンを表示する-->
                        <a href="{{ route('starts.create', ['id' => $task->id]) }}"><i class="fas fa-play"></i></a><!--STARTリンク-->
                        @elseif ($task->status==1)<!--status==1 status==1==動いてるなら　ストップボタンを表示する-->
                        <a href="{{ route('pauses.store', ['id' => $task->id ]) }}" class="mr-4"><i class="fas fa-pause"></i></a><!--STOPリンク-->
                        <a href="{{ route('stops.create', ['id' => $task->id]) }}"><i class="fas fa-stop"></i></a><!--STOPリンク-->
                        @endif
                    </td>
                </tr>
                <tr>
                    @if ($task->status==0)<!--new-->
                        <td  rowspan=2  class="text-center alert alert-info">
                    @elseif ($task->status==1)<!--start(move)-->
                        <td  rowspan=2  class="alert alert-success text-center">
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td  rowspan=2  class="alert alert-danger text-center">
                    @elseif ($task->status==3)<!--pause-->
                        <td  rowspan=2 class="alert alert-warning text-center">
                    @elseif ($task->status==4)<!--finish(finish)-->
                        <td  rowspan=2  class="alert alert-secondary text-center">
                    @else
                        <td  rowspan=2 >
                    @endif
                        
                        @if ($task->status!=4)



                        {!! Form::model($task, ['route' => ['tasks.finish', $task->id], 'method' => 'put']) !!}
                        <!--<i class="fas fa-check"></i>この画像を使う-->
                        <button type="sumbit" class="btn btn-primary" onclick="return confirm('このプロジェクトを完了しますか？')">
                            <i class="fas fa-check"></i>
                        </button>
                        {!! Form::close() !!}
                        @endif
                        
                    </td>
                    
                    <td colspan=2 style="word-break: break-all;">
                         {!! nl2br(e($task->lastcomment)) !!}
                    </td>

                    @if ($task->status==0)<!--new-->
                        <td rowspan=2 class="text-center alert alert-info">
                    @elseif ($task->status==1)<!--start(move)-->
                        <td rowspan=2  class="alert alert-success text-center">
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td rowspan=2  class="alert alert-danger text-center">
                    @elseif ($task->status==3)<!--pause-->
                        <td  rowspan=2 class="alert alert-warning text-center">
                    @elseif ($task->status==4)<!--finish(finish)-->
                        <td rowspan=2  class="alert alert-secondary text-center">
                    @else
                        <td rowspan=2 >
                    @endif
                    
                        {!! Form::model($task, ['route' => ['tasks.destroy', $task->id], 'method' => 'delete']) !!}
                        <div class="text-center">
                        <button type="sumbit" class="btn btn-danger" onclick="return confirm('このプロジェクトを削除しますか？')">
                            <i class="fas fa-trash-alt"></i>
                        </button
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                    @if ($task->status==0)<!--new-->
                        <span class="text-muted">{{$task->created_at}}に作成</span>
                    @elseif ($task->status==1)<!--start(move)-->
                        <span class="text-muted">{{$task->start_at}}に開始</span>
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <span class="text-muted">{{$task->stop_at}}に停止</span>
                    @elseif ($task->status==3)<!--pause-->
                        <span class="text-muted">{{$task->stop_at}}に中断</span>
                    @elseif ($task->status==4)<!--finish(finish)-->
                        <span class="text-muted">{{$task->stop_at}}に完了</span>
                    @else
                    @endif
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $tasks->links() }}
        <script type="text/javascript">
        var start_at = new Array();
        var timer = new Array();
        var id = new Array();
        var cnt = 0;
        
        @foreach ($tasks as $task)
            @if ($task->status==1)
                start_at[cnt] = "<?php echo htmlspecialchars($task->start_at, ENT_QUOTES, 'UTF-8');?>";
                timer[cnt]    = "<?php echo htmlspecialchars($task->timer, ENT_QUOTES, 'UTF-8');?>";
                timer[cnt] = timer[cnt] * 1000;
                id[cnt]       = "<?php echo htmlspecialchars($task->id, ENT_QUOTES, 'UTF-8');?>";
                cnt++;
            @endif
        @endforeach

        function time(){
        
        for (  var i = 0;  i < cnt;  i++  ) {
                var now  = new Date();
                var from = new Date(start_at[i]);

                document.getElementById(id[i]).innerHTML = mytime(now.getTime() - from.getTime()+timer[i]);
                document.getElementById(id[i]+"_cur").innerHTML = mytime(now.getTime() - from.getTime());
            }

        }
        
        if (cnt==1){
                var elem = document.getElementById("timers_base");
                elem.innerHTML = "<span id='timers_base' class='alert alert-success  text-center'><strong id='timers'>"+cnt+" timer</strong></span>";
        }else{
            if(cnt>1){
                var elem = document.getElementById("timers_base");
                elem.innerHTML = "<span id='timers_base' class='alert alert-warning text-center'><strong id='timers'>"+cnt+" timers - multi</strong></span>";
            }
        }
        setInterval('time()',1000);


function mytime(timer) {

    timer = timer/1000;

    var day =0;
    var hou =0;
    var min =0;
    var sec =0;
    
    var timetext = '';

    day = Math.floor(timer/3600/24);
    hou = Math.floor(timer/3600);
    min = Math.floor((timer/60)%60);
    sec = Math.floor(timer % 60);
    var tmp = "00" + String( hou );
    hou = tmp.substr(tmp.length - 2);

    tmp = "00" + String( min );
    min = tmp.substr(tmp.length - 2);

    tmp = "00" + String( sec );
    sec = tmp.substr(tmp.length - 2);

    if(day>0){
        timetext = day+"日 "+hou+":"+min+":"+sec;
    }else{
        timetext = hou+":"+min+":"+sec;
    }
    
    return timetext;
}


    </script>
        
        
    @endif
    
    @else
                    <li class="nav-item">{!! link_to_route('signup.get', 'Signup', [], ['class' => 'nav-link']) !!}</li>
                    <li class="nav-item">{!! link_to_route('login', 'Login', [], ['class' => 'nav-link']) !!}</li>
    @endif

@endsection