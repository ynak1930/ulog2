@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <div class="mt-4 row">
        <span class="col-sm-6">
                <h1>({{ $tasks->id }}){{$tasks->name}}</h1>
                            @if ($tasks->status!=1)
                            {{sprintf('%02d', floor( $tasks->timer / 3600 ))}}:{{sprintf('%02d',floor( ( $tasks->timer / 60 ) % 60 ))}}:{{sprintf('%02d',$tasks->timer % 60)}}
                            @if (floor($tasks->timer / 3600/24)>0)
                            ({{floor($tasks->timer / 3600/24)}}日)
                            @endif
                            @elseif ($tasks->status==1)
                            <span id="timer_back">
                                <p class="m-4"><span class="badge badge-success">実行中</span></p>
                                <p class="m-4"><span class="alert alert-success"><strong id="timer1"></strong></span></p>
                                <p class="m-4"><span class="alert alert-success">[<strong id="timer2"></strong>]</span></p>
                            </span>
                            @endif
        </span>
        <span class="col-sm-6">
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
        <span class="col-sm-6">
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
    var tmp = "00" + String( sec );
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
