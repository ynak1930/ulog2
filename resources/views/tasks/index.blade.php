@extends('layouts.app')

@section('content')

<!-- ここにページ毎のコンテンツを書く -->
    @if (Auth::check())

    <h1>{{ Auth::user()->name }}　のプロジェクト一覧 -     {!! link_to_route('tasks.create', '新規プロジェクトの投稿', [], ['class' => 'btn btn-primary']) !!}</h1>

    @if (count($tasks) > 0)
        <table class="table table-striped">
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
                    <td rowspan=2 class="text-center">
                        {!! link_to_route('tasks.show', $task->id, ['id' => $task->id]) !!}
                        {!! Form::model($task, ['route' => ['tasks.destroy', $task->id], 'method' => 'delete']) !!}
                        <!--<i class="fas fa-trash-alt"></i>この画像を使う-->
                        {!! Form::submit('削除', ['class' => 'mt-3 btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td>
                    <td>
                        @if ($task->status==0)
                            <strong>{{ $task->name }}</strong>
                            <span class="text-muted">[{{ $task->created_at}}に作成]</span>
                        @else
                            <div class="alert alert-success" role="alert">
                                {{ $task->name }}
                            <span class="text-muted">[{{ $task->created_at}}に作成]</span>
                            </div>
                        @endif

                    </td>
                    <td>
                        @if ($task->status==0)
                            <!--タイムゾーンの設定で9時間足されちゃうので9時間マイナス・他にいい方法が無いか探す-->
                            {{ date('H:i:s',$task->timer-60*60*9) }}
                        @else
                            <div class="alert alert-success" role="alert">
                                稼働中...({{$task->start_at}}から)
                            </div>
                        @endif
                    </td>
                    <td>
                        @if ($task->status==0)
                        <a href="{{ route('starts.create', ['id' => $task->id]) }}"><i class="fas fa-play"></i></a><!--STARTリンク-->
                        @else
                        <a href="{{ route('stops.create', ['id' => $task->id]) }}"><i class="fas fa-stop"></i></a><!--STOPリンク-->

                        @endif
                    </td>
                    <td>
                        
                    </td>

                </tr>
                <tr>
                    <td colspan=3>
                        @if ($task->status==0)
                         @if ($task->timer>0)
                            <p>{!! nl2br(e($task->lastcomment)) !!}</p><span class="text-muted">{{' - '.$task->stop_at}}に停止</span>
                         @endif
                        @else
                        <p>{!! nl2br(e($task->lastcomment)) !!}</p><span class="text-muted">{{' - '.$task->start_at}}に開始</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @endif
    
    @else
                    <li class="nav-item">{!! link_to_route('signup.get', 'Signup', [], ['class' => 'nav-link']) !!}</li>
                    <li class="nav-item">{!! link_to_route('login', 'Login', [], ['class' => 'nav-link']) !!}</li>
    @endif

@endsection