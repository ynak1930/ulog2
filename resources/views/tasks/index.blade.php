@extends('layouts.app')

@section('content')

<!-- ここにページ毎のコンテンツを書く -->
    @if (Auth::check())

    <h1>{{ Auth::user()->name }}　のプロジェクト一覧 -     {!! link_to_route('tasks.create', '新規プロジェクトの投稿', [], ['class' => 'btn btn-primary']) !!}</h1>

    @if (count($tasks) > 0)
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
            <!--
            現状はstatus
            0=新規作成時、ストップ時
            1=スタート時
            
            これを
            0=新規作成
            1=スタート時
            2=ストップ時
            3=完了時
            に変更する。
            -->
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
                                <span class="badge badge-success">実行中</span><strong id="time"></strong>

                                <script type="text/javascript">
                                    time();
                                    function time(){
                                        var start_at = <?php echo json_encode($task->start_at); ?>;
                                        var timer = <?php echo json_encode($task->timer); ?>;
                                        var timer = timer * 1000;
                                        var from = new Date(start_at);
                                        //var from = new Date("2016/3/1 23:44:59");
                                        var now = new Date();
                                        
                                        // 経過時間をミリ秒で取得
                                        var ms = new Date(now.getTime() - from.getTime()+timer-60*60*9*1000);
                                        // ミリ秒を日付に変換(端数切捨て)
                                        var days = Math.floor(ms / (1000*60*60*24));
                                        
                                        //document.getElementById("time").innerHTML = days.toLocaleTimeString();
                                        document.getElementById("time").innerHTML = ms.toLocaleTimeString();
                                    }
                                    setInterval('time()',1000);
                                </script>
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
                        {!! Form::submit('削除', ['class' => 'mt-3 btn btn-danger']) !!}
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
    @endif
    
    @else
                    <li class="nav-item">{!! link_to_route('signup.get', 'Signup', [], ['class' => 'nav-link']) !!}</li>
                    <li class="nav-item">{!! link_to_route('login', 'Login', [], ['class' => 'nav-link']) !!}</li>
    @endif

@endsection