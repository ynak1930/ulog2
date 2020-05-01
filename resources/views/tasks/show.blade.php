@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <div class="mt-4 row">
        <span class="col-sm-6">
                <h1>({{ $tasks->id }}){{$tasks->name}}</h1>
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

        @endif
    </div>
    @endif

@endsection
