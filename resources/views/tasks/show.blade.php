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
                <p class="mt-4">
                {!! Form::model($tasks, ['route' => ['tasks.edit', $tasks->id] , 'method' => 'put']) !!}
                {!! Form::label('category', '変更:') !!}
                <select name="category">
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

                </h2>
        </span>
    </div>
    <div class="mt-4 row">
        @if (count($starts) > 0)
        <div class="col-sm-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>開始コメント</th><th>停止コメント</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($starts as $start)
                <tr> 
                    <td><p>{!! nl2br(e($start->content)) !!}</p><span class="text-muted">{{ $start->created_at}}</span></td>
                    @if (isset($stops[$loop->index]['id']))
                    <td><p>{!! nl2br(e($stops[$loop->index]['content'])) !!}</p><span class="text-muted">{!! nl2br(e($stops[$loop->index]['created_at'])) !!}</span></td>
                    @endif
                </tr>
                @endforeach

            </tbody>
        </table>
        </div>
        @endif
    </div>
    @endif

@endsection
