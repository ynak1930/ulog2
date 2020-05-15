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
        <h1 class="m-4">
            {{ Auth::user()->name }} - {!! link_to_route('categories.create', 'カテゴリを追加', [], ['class' => 'btn btn-primary']) !!} - {!! link_to_route('tasks.create', '新規プロジェクトの投稿', [], ['class' => 'btn btn-primary']) !!}
        </h1>
                        <span class="mt-6">
                            <script src="{{ asset('/js/sort.js') }}"></script>
                            <form name="sort_form" style="display: inline">
                            <select name="sort" onchange="dropsort()">
                                <option value="">フィルタ/並び替え</option>
                                <option value="{{ route('tasks.index', ['sortby' => 13]) }}">すべてを表示</option>  
                                <option value="{{ route('tasks.index', ['sortby' => 6]) }}">新規プロジェクトのみ表示</option>
                                <option value="{{ route('tasks.index', ['sortby' => 7]) }}">実行中プロジェクトのみ表示</option>
                                <option value="{{ route('tasks.index', ['sortby' => 12]) }}">中断したプロジェクトのみ表示</option>    
                                <option value="{{ route('tasks.index', ['sortby' => 8]) }}">停止中プロジェクトのみ表示</option>
                                <option value="{{ route('tasks.index', ['sortby' => 9]) }}">完了したプロジェクトのみ表示</option>
                                <option value="">---</option>
                                <option value="{{ route('tasks.index', ['sortby' => 3]) }}">操作したのが最近</option>
                                <option value="{{ route('tasks.index', ['sortby' => 2]) }}">操作したのが古い</option>

                                <option value="{{ route('tasks.index', ['sortby' => 11]) }}">作成日が新しい</option>
                                <option value="{{ route('tasks.index', ['sortby' => 10]) }}">作成日が古い</option>
                                <option value="{{ route('tasks.index', ['sortby' => 1]) }}">稼働時間が長い</option>
                                <option value="{{ route('tasks.index', ['sortby' => 0]) }}">稼働時間が短い</option>
                            </select>
                        </form>
                        
                        </span>
                        
    </div>
    @if (count($categories) > 0)
    
<div class="accordion mt-2" id="accordion" role="tablist" aria-multiselectable="true">
    @foreach ($categories as $category)
   <div class="card">
    <div class="card-header" role="tab" id="heading{{$category->id}}">
      <h5 class="mb-0">
        <a class="collapsed text-body d-block p-3 m-n3" data-toggle="collapse" href="#collapse{{$category->id}}" role="button" aria-expanded="false" aria-controls="collapse{{$category->id}}">
            <div class="row">
            @php
                $timers = 0;
                $timersum = 0;
                $taskcnt = 0;
            @endphp
              @foreach ($tasks as $task)
                @if ($category->id==$task->category_id)
                    @php
                    $taskcnt = $taskcnt+1;
                    @endphp
                    @if ($task->status==1)
                        @php
                            $timers = $timers+1;
                        @endphp
                    @endif
                @endif
            @endforeach
          <span class='col-sm-9'>
              {{ $category->category .'('.$taskcnt.')'}} - 
              {{sprintf('%02d', floor( $category->timersum / 3600 ))}}:{{sprintf('%02d',floor( ( $category->timersum / 60 ) % 60 ))}}:{{sprintf('%02d',$category->timersum % 60)}}
          </span>
          @if ($timers>0)
            @if ($timers==1)
                <span class='alert alert-success col-sm-3 m-0'><strong>{{$timers}} timer</strong></span>
            @elseif ($timers>1)
                <span class='alert alert-success col-sm-3 m-0'><strong>{{$timers}} timers</strong></span>
            @endif
          @endif
          @php
                $timers = 0;
                $taskcnt = 0;
          @endphp
          </div>
        </a>

      </h5>
    </div><!-- /.card-header -->
    <div id="collapse{{$category->id}}" class="collapse" role="tabpanel" aria-labelledby="heading{{$category->id}}">
      <div class="card-body p-0">
        @if (count($tasks) > 0)
            @include('tasks.tasklist', ['tasks' => $tasks,'catid' => $category->id])
        @endif
      </div><!-- /.card-body -->
    </div><!-- /.collapse -->
  </div><!-- /.card -->
    @endforeach
  <div class="card">
    <div class="card-header" role="tab" id="headingOne">
      <h5 class="mb-0">
        <a class="text-body d-block p-3 m-n3" data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="true" aria-controls="collapseOne">
            <div class="row">
            @php
                $timers = 0;
                $taskcnt = 0;
                $timersum = 0;
            @endphp
            @foreach ($tasks as $task)
                @if ($task->category_id==0)
                    @php
                    $taskcnt = $taskcnt+1;
                    $timersum = $timersum+$task->timer;
                    @endphp
                    @if ($task->status==1)
                        @php
                            $timers = $timers+1;
                        @endphp
                    @endif
                @endif
            @endforeach
          <span class='col-sm-9'>
                未分類({{$taskcnt}})
          </span>
          @if ($timers>0)
            @if ($timers==1)
                <span class='alert alert-success col-sm-3 m-0'><strong>{{$timers}} timer</strong></span>
            @elseif ($timers>1)
                <span class='alert alert-success col-sm-3 m-0'><strong>{{$timers}} timers</strong></span>
            @endif
          @endif
          @php
                $timers = 0;
                $taskcnt = 0;
                $timersum = 0;
          @endphp
          </div>
        </a>
      </h5>
    </div><!-- /.card-header -->
    <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="card-body p-0">
        @if (count($tasks) > 0)
            @include('tasks.tasklist', ['tasks' => $tasks,'catid' => 0])
        @endif
      </div><!-- /.card-body -->
    </div><!-- /.collapse -->
  </div><!-- /.card -->
</div><!-- /#accordion -->
    


    @endif    


    @if (count($tasks) > 0)

        <div class="m-3">
            <p>
            <span id="timers_base" class="text-center"><strong id="timers"></strong></span>
            </p>
        </div>

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
                elem.innerHTML = "<span id='timers_base' class='alert alert-warning text-center'><strong id='timers'>"+cnt+" timers</strong></span>";
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