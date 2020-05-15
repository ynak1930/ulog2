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
                        
    </div>
    @if (count($categories) > 0)
    
<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="card">
    <div class="card-header" role="tab" id="headingOne">
      <h5 class="mb-0">
        <a class="text-body d-block p-3 m-n3" data-toggle="collapse" href="#collapseOne" role="button" aria-expanded="true" aria-controls="collapseOne">
          未分類
        </a>
      </h5>
    </div><!-- /.card-header -->
    <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
      <div class="card-body p-0">
        @if (count($tasks) > 0)
            @include('tasks.tasklist', ['tasks' => $tasks,'catid' => 0])
        @endif
      </div><!-- /.card-body -->
    </div><!-- /.collapse -->
  </div><!-- /.card -->
    @foreach ($categories as $category)
   <div class="card">
    <div class="card-header" role="tab" id="heading{{$category->id}}">
      <h5 class="mb-0">
        <a class="collapsed text-body d-block p-3 m-n3" data-toggle="collapse" href="#collapse{{$category->id}}" role="button" aria-expanded="false" aria-controls="collapse{{$category->id}}">
          {{ $category->category }}
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