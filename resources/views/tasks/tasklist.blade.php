    @if (count($tasks) > 0)
        <table class="table table-bordered p-1" >
            <thead>
                <tr>
                    <th class="text-center"><span>プロジェクト名</span></th>
                    <th class="text-right m-1 p-1">稼働時間</th>
                </tr>
            </thead>
            <tbody>

        @foreach ($tasks as $task)
            @if ($task->category_id==$catid)

                <tr>


                    @if ($task->status==0)<!--new-->
                        <td class="alert alert-info text-center">
                    @elseif ($task->status==1)<!--start(move)-->
                        <td class="alert alert-success text-center">
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td class="alert alert-danger text-center">
                    @elseif ($task->status==3)<!--pause-->
                        <td class="alert alert-warning text-center">
                    @else<!--status==4 finish(finish)-->
                        <td class="alert alert-secondary text-center">
                    @endif
                            <strong style="word-break: break-all;">
                                {!! link_to_route('tasks.show', $task->name, ['id' => $task->id]) !!}
                            </strong>


                    </td>
                    @if ($task->status==0)<!--new-->
                        <td class="alert alert-info text-right p-1 align-middle">
                            <span class="badge badge-info m-0">新着</span>
                    @elseif ($task->status==1)<!--start(move)-->
                        <td class="alert alert-success text-right p-1 align-middle">

                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td class="alert alert-danger text-right p-1 align-middle">
                            <span class="badge badge-danger m-0">停止中</span>
                    @elseif ($task->status==3)<!--pause-->
                        <td class="alert alert-warning text-right p-1 align-middle">
                            <span class="badge badge-warning m-0">中断</span>
                    @elseif ($task->status==4)<!--finish(finish)-->
                        <td class="alert alert-secondary text-right p-1 align-middle">
                            <span class="badge badge-dark m-0">完了</span>
                    @else
                        <td>
                    @endif
                        
                            @if ($task->status!=1)
                            </p><span>
                            {{sprintf('%02d', floor( $task->timer / 3600 ))}}:{{sprintf('%02d',floor( ( $task->timer / 60 ) % 60 ))}}:{{sprintf('%02d',$task->timer % 60)}}
                            </span></p>
                            @if (floor($task->timer / 3600/24)>0)
                            ({{floor($task->timer / 3600/24)}}日)
                            @endif
                            @elseif ($task->status==1)
                                <span class="badge badge-success">実行中</span><br>
                                <span><strong id="{{$task->id}}"></strong></span><br>
                                <span>[<strong id="{{$task->id}}_cur"></strong>]</span>
                            @endif

                    </td>

                </tr>
                <tr>

                    
                    <td style="word-break: break-all;">
                         {!! nl2br(e($task->lastcomment)) !!}
                    </td>

                    @if ($task->status==0)<!--new-->
                        <td rowspan=2 class="alert alert-info text-right p-1">
                    @elseif ($task->status==1)<!--start(move)-->
                        <td rowspan=2  class="alert alert-success text-right p-1">
                    @elseif ($task->status==2)<!--stop(stop)-->
                        <td rowspan=2  class="alert alert-danger text-right p-1">
                    @elseif ($task->status==3)<!--pause-->
                        <td  rowspan=2 class="alert alert-warning text-right p-1">
                    @elseif ($task->status==4)<!--finish(finish)-->
                        <td rowspan=2  class="alert alert-secondary text-right p-1">
                    @else
                        <td rowspan=2 class="p-0">
                    @endif
                    <div class="btn-group dropleft">
                    <!-- 切替ボタンの設定 -->
                    <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    </button>
                    <!-- ドロップメニューの設定 -->
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                    <p class="m-6 dropdown-item">
   @if ($task->status!=1)
    <a href="{{ route('starts.create', ['id' => $task->id]) }}" class="mr-4"><i class="fas fa-play"></i></a><!--STARTリンク-->
    <i class="fas fa-pause mr-4"></i>
    <i class="fas fa-stop"></i>
   @elseif ($task->status==1)
    <a href="{{ route('pauses.store', ['id' => $task->id ]) }}" class="mr-4"><i class="fas fa-pause"></i></a><!--STOPリンク-->
    <a href="{{ route('stops.create', ['id' => $task->id]) }}"><i class="fas fa-stop"></i></a><!--STOPリンク-->
@endif
</p>

                        <li class="dropdown-divider"></li>
                        <li class="dropdown-item">
                       
                        </li>

  </div><!-- /.dropdown-menu -->
</div><!-- /.dropdown -->
                        @if ($task->status!=4)

                            {!! Form::model($task, ['route' => ['tasks.finish', $task->id], 'method' => 'put']) !!}
                            <button type="sumbit" class="btn btn-primary ml-0 mt-2 mr-0" onclick="return confirm('このプロジェクトを完了しますか？')">
                                <i class="fas fa-check"></i>
                            </button>
                            {!! Form::close() !!}

                        @endif
                        {!! Form::model($task, ['route' => ['tasks.destroy', $task->id], 'method' => 'delete']) !!}
                        <button type="sumbit" class="btn btn-danger ml-0 mt-4 mr-0" onclick="return confirm('このプロジェクトを削除しますか？')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        {!! Form::close() !!} 
                    </td>
                </tr>
                <tr>
                    <td>
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
                <tr>
                    <td class="bg-light p-0" height="10px" colspan="2"></td>
                </tr>
            @endif
        @endforeach
            </tbody>
        </table>
    @endif