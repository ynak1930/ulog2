@extends('layouts.app')

@section('content')


    @if (Auth::check())
    <div>
        <h1>{{ Auth::user()->name }} - {!! link_to_route('tasks.create', '新規プロジェクトの投稿', [], ['class' => 'btn btn-primary']) !!}</h1>
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
                    <th class="text-left">プロジェクト名</th>
                    <th class="text-left" colspan=3>稼働時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>

                    @if ($task->status==0)<!--new-->
                        <td class="text-center alert alert-info">
                            <span class="badge badge-info">新着</span>
                    @elseif ($task->status==1)<!--start(move)-->
                        <td class="alert alert-success text-center">
                            <span class="badge badge-success">実行中</span>
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td class="alert alert-danger text-center">
                            <span class="badge badge-warning">停止中</span>
                    @elseif ($task->status==3)<!--finish(finish)-->
                        <td class="alert alert-secondary text-center">
                            <span class="badge badge-dark">完了</span>
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
                    @else<!--status==3 finish(finish)-->
                        <td class="alert alert-secondary text-center">
                    @endif
                            <strong>{{ $task->name }}</strong>

                    </td>
                    @if ($task->status==0)<!--new-->
                        <td class="text-left alert alert-info">
                            <span class="badge badge-info">新着</span>
                    @elseif ($task->status==1)<!--start(move)-->
                        <td class="alert alert-success text-left">

                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td class="alert alert-danger text-left">
                            <span class="badge badge-warning">停止中</span>
                    @elseif ($task->status==3)<!--finish(finish)-->
                        <td class="alert alert-secondary text-left">
                            <span class="badge badge-dark">完了</span>
                    @else
                        <td>
                    @endif
                        
                            @if ($task->status!=1)
                            <!--タイムゾーンの設定で9時間足されちゃうので9時間マイナス・他にいい方法が無いか探す-->
                            {{ date('H:i:s',$task->timer-60*60*9) }}

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
                    @elseif ($task->status==3)<!--finish(finish)-->
                        <td class="alert alert-secondary text-center">
                    @else
                        <td>
                    @endif
                    
                        @if ($task->status!=1)<!--status!=1　新規=0、2=stop、 3=finishならスタートボタンを表示する-->
                        <a href="{{ route('starts.create', ['id' => $task->id]) }}"><i class="fas fa-play"></i></a><!--STARTリンク-->
                        @elseif ($task->status==1)<!--status==1 status==1==動いてるなら　ストップボタンを表示する-->
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
                    @elseif ($task->status==3)<!--finish(finish)-->
                        <td  rowspan=2  class="alert alert-secondary text-center">
                    @else
                        <td  rowspan=2 >
                    @endif
                        
                        @if ($task->status!=3)
                        {!! Form::model($task, ['route' => ['tasks.finish', $task->id], 'method' => 'put']) !!}
                        <!--<i class="fas fa-trash-alt"></i>この画像を使う-->
                        {!! Form::submit('完了', ['class' => 'mt-3 btn-secondary']) !!}
                        {!! Form::close() !!}
                        @endif
                        
                    </td>
                    
                    <td colspan=2>
                         {!! nl2br(e($task->lastcomment)) !!}
                    </td>

                    @if ($task->status==0)<!--new-->
                        <td rowspan=2 class="text-center alert alert-info">
                    @elseif ($task->status==1)<!--start(move)-->
                        <td rowspan=2  class="alert alert-success text-center">
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td rowspan=2  class="alert alert-danger text-center">
                    @elseif ($task->status==3)<!--finish(finish)-->
                        <td rowspan=2  class="alert alert-secondary text-center">
                    @else
                        <td rowspan=2 >
                    @endif
                        {!! Form::model($task, ['route' => ['tasks.destroy', $task->id], 'method' => 'delete']) !!}
                        <!--<i class="fas fa-trash-alt"></i>この画像を使う-->
                        {!! Form::submit('削除', ['class' => 'mt-3 btn btn-danger', 'onclick'=> 'return confirm("このプロジェクトを削除しますか？")']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                    @if ($task->status==0)<!--new-->
                        <span class="text-muted">{{' - '.$task->created_at}}に作成</span>
                    @elseif ($task->status==1)<!--start(move)-->
                        <span class="text-muted">{{' - '.$task->start_at}}に開始</span>
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <span class="text-muted">{{' - '.$task->stop_at}}に停止</span>
                    @elseif ($task->status==3)<!--finish(finish)-->
                        <span class="text-muted">{{' - '.$task->stop_at}}に完了</span>
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
                var ms   = new Date(now.getTime() - from.getTime()+timer[i]-60*60*9*1000);// 稼働時間＋今のタイマー
                var ms2  = new Date(now.getTime() - from.getTime()-60*60*9*1000);// 今のタイマー
                document.getElementById(id[i]).innerHTML = ms.toLocaleTimeString();
                document.getElementById(id[i]+"_cur").innerHTML = ms2.toLocaleTimeString();
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


    </script>
        
        
    @endif
    
    @else
                    <li class="nav-item">{!! link_to_route('signup.get', 'Signup', [], ['class' => 'nav-link']) !!}</li>
                    <li class="nav-item">{!! link_to_route('login', 'Login', [], ['class' => 'nav-link']) !!}</li>
    @endif

@endsection