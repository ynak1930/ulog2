@extends('layouts.app')

@section('content')
    @if (Auth::check())
            @if (session('flash_message'))
            <div class="flash_message alert alert-info">
                {{ session('flash_message') }}
            </div>
        @endif
    <div class="mt-4 row">
        <span class="col-sm-6 border bg-light">
                <h1>({{ $tasks->id }}){{$tasks->name}}</h1>
        </span>
        <span class="col-sm-6 border bg-light">
                <h2>
                    @if (isset($categories[$tasks->category_id-1]['category']))
                        {{$categories[$tasks->category_id-1]['category']}}
                    @else
                    未分類
                    @endif
                </h2>
                <p class="mt-4">
                    {!! Form::model($tasks, ['route' => ['tasks.edit', $tasks->id] , 'method' => 'put']) !!}
                    {!! Form::label('category', 'カテゴリー:') !!}
                    <select name="category" style="width:100%;">
                        <option value="0">未分類</option>
                        @if (count($categories) > 0)
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->category}}</option>
                        @endforeach
                        @endif
                    </select>
                    {!! Form::submit('変更', ['class' => 'btn btn-dark']) !!}
                    {!! Form::close() !!}
                </p>
        </span>
        <span class="col-sm-6 border bg-light">
                @if ($tasks->status==0)
                <span id="timer_back">
                    <p class="m-4">
                        <span class="alert alert-info">
                            <span class="badge badge-info">新着</span>
                            {{sprintf('%02d', floor( $tasks->timer / 3600 ))}}:{{sprintf('%02d',floor( ( $tasks->timer / 60 ) % 60 ))}}:{{sprintf('%02d',$tasks->timer % 60)}}
                            @if (floor($tasks->timer / 3600/24)>0)
                            ({{floor($tasks->timer / 3600/24)}}日)
                            @endif
                        </span>
                    </p>
                </span>
                @endif

                @if ($tasks->status==2)
                <span id="timer_back">
                    <p class="m-4">
                        <span class="alert alert-danger">
                            <span class="badge badge-danger">停止中</span>
                            {{sprintf('%02d', floor( $tasks->timer / 3600 ))}}:{{sprintf('%02d',floor( ( $tasks->timer / 60 ) % 60 ))}}:{{sprintf('%02d',$tasks->timer % 60)}}
                            @if (floor($tasks->timer / 3600/24)>0)
                            ({{floor($tasks->timer / 3600/24)}}日)
                            @endif
                        </span>
                    </p>
                </span>
                @endif

                @if ($tasks->status==3)
                <span id="timer_back">
                    <p class="m-4">
                        <span class="alert alert-warning">
                            <span class="badge badge-warning">停止中</span>
                            {{sprintf('%02d', floor( $tasks->timer / 3600 ))}}:{{sprintf('%02d',floor( ( $tasks->timer / 60 ) % 60 ))}}:{{sprintf('%02d',$tasks->timer % 60)}}
                            @if (floor($tasks->timer / 3600/24)>0)
                            ({{floor($tasks->timer / 3600/24)}}日)
                            @endif
                        </span>
                    </p>
                </span>
                @endif

                @if ($tasks->status==4)
                <span id="timer_back">
                    <p class="m-4">
                        <span class="alert alert-dark">
                            <span class="badge badge-dark">完了</span>
                            {{sprintf('%02d', floor( $tasks->timer / 3600 ))}}:{{sprintf('%02d',floor( ( $tasks->timer / 60 ) % 60 ))}}:{{sprintf('%02d',$tasks->timer % 60)}}
                            @if (floor($tasks->timer / 3600/24)>0)
                            ({{floor($tasks->timer / 3600/24)}}日)
                            @endif
                        </span>
                    </p>
                </span>
                @endif

                @if ($tasks->status==1)
                <span id="timer_back">
                    <p class="m-4"><span class="alert alert-success"><span class="badge badge-success">実行中</span><strong id="timer1"></strong>[<strong id="timer2"></strong>]</span></p>
                    <p class="m-4">
                        {!! Form::model($tasks, ['route' => ['tasks.finish', $tasks->id], 'method' => 'put']) !!}
                        <span class="alert alert-dark">
                            <i class="fas fa-play mr-4"></i>
                            <a href="{{ route('pauses.store', ['id' => $tasks->id ]) }}" class="mr-4"><i class="fas fa-pause"></i></a><!--STOPリンク-->
                            <a href="{{ route('stops.create', ['id' => $tasks->id]) }}" class="mr-6"><i class="fas fa-stop"></i></a><!--STOPリンク-->
                        </span>
                        <span class="ml-4">
                            <button type="sumbit" class="btn btn-primary" onclick="return confirm('このプロジェクトを完了しますか？')">
                                <i class="fas fa-check"></i>
                            </button>
                        </span>
                        {!! Form::close() !!}
                    </p>
                @endif
                @if ($tasks->status!=1)
                    <p class="m-4">
                        {!! Form::model($tasks, ['route' => ['tasks.finish', $tasks->id], 'method' => 'put']) !!}
                        <span class="alert alert-dark">
                            <a href="{{ route('starts.create', ['id' => $tasks->id]) }}" class="mr-4"><i class="fas fa-play"></i></a><!--STARTリンク-->
                            <i class="fas fa-pause mr-4"></i><!--pauseダミー-->
                            <i class="fas fa-stop mr-6"></i><!--STOPダミー-->
                        </span>
                        <span class="ml-4">
                            <button type="sumbit" class="btn btn-primary" onclick="return confirm('このプロジェクトを完了しますか？')">
                                <i class="fas fa-check"></i>
                            </button>
                        </span>
                        {!! Form::close() !!}

                    </p>
                @endif
                </span>

        </span>

        <span class="col-sm-6 border bg-light">
                {!! Form::model($tasks, ['route' => ['tasks.edit', $tasks->id] , 'method' => 'put']) !!}
                <p>タイマー値変更<br>
                <input type="number" name="hour" value="0" min="0" max="33333333" size="8" maxlength="8">
                :
                <input type="number" name="minute" value="0" min="0" max="59" size="2" maxlength="2">
                :
                <input type="number" name="second" value="0" min="0" max="59" size="2" maxlength="2">
                </p>
                {!! Form::submit('変更', ['class' => 'btn btn-dark']) !!}
                {!! Form::close() !!}
        </span>



        @if (count($starts) > 0)

        <table class="table table-striped m-4" style="width:100%">
            <tbody>
                <tr class="row">
                    <th colspan="2" class="col-sm-12 text-right">
                        <span class="mr-4">
                            <script src="{{ asset('/js/sort.js') }}"></script>
                            <form name="sort_form" style="display: inline">
                            <select name="sort" onchange="dropsort()">
                                <option value="">並べ替え</option>
                                <option value="{{ route('tasks.show', ['id' => $tasks->id, 'sort' => 1]) }}">新しい順</option>
                                <option value="{{ route('tasks.show', ['id' => $tasks->id, 'sort' => 2]) }}">古い順</option>
                                <option value="{{ route('tasks.show', ['id' => $tasks->id, 'sort' => 3]) }}">今日のみ</option>
                                <option value="{{ route('tasks.show', ['id' => $tasks->id, 'sort' => 4]) }}">今週のみ</option>
                                <option value="{{ route('tasks.show', ['id' => $tasks->id, 'sort' => 5]) }}">今月のみ</option>
                            </select>
                        </form>
                        
                        </span>
                    </th>
                </tr>
                @foreach ($starts as $start)
                <tr class="row"> 
                    <td class="col-sm-6" style="word-break: break-all;">
                        <p>{!! nl2br(e($start->content)) !!}</p>
                        <span class="text-muted">{{ $start->created_at}}に開始</span>
                    </td>
                    @if (isset($stops[$loop->index]['id']))
                    <td class="col-sm-6" style="word-break: break-all;">
                        <p>{!! nl2br(e($stops[$loop->index]['content'])) !!}</p>
                        <span class="text-muted">{{($stops[$loop->index]['created_at'])}}に停止</span>
                    </td>
                    @endif
                </tr>
                @endforeach

            </tbody>
        </table>
@if ($tasks->status==1)
        <script type="text/javascript">
        var start_at = 0;
        var mytimer = 0;
        var id = 0;
        var cnt = 0;
        

                start_at = "<?php echo htmlspecialchars($tasks->start_at, ENT_QUOTES, 'UTF-8');?>";
                mytimer    = "<?php echo htmlspecialchars($tasks->timer, ENT_QUOTES, 'UTF-8');?>";
                mytimer = mytimer * 1000;
                id    = "<?php echo htmlspecialchars($tasks->id, ENT_QUOTES, 'UTF-8');?>";
            

        function time(){
        
                 var now  = new Date();
                var from = new Date(start_at);

                document.getElementById('timer1').innerHTML = mytime(now.getTime() - from.getTime()+mytimer);
                document.getElementById('timer2').innerHTML = mytime(now.getTime() - from.getTime());

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
        @endif
    </div>
    @endif

@endsection
