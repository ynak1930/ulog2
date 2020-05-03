@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <h1>プロジェクト追加</h1>
    @if (Auth::check())
            {!! Form::model($task, ['route' => 'tasks.store']) !!}


                <div class="form-group">

                        {!! Form::label('name', 'プロジェクト名:') !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                        {!! Form::label('timer', 'タイマー初期値(秒):')!!}
                        {!! Form::number('timer', null, ['class' => 'form-control', 'min' => 0, 'max' => 2000000000]) !!}
                        <p class="mt-4">
                        {!! Form::label('category', 'カテゴリー:') !!}
                        <select name="category">
                                <option value="0">未分類</option>
                        @if (count($categories) > 0)
                           @foreach ($categories as $category)
                               <option value="{{$category->id}}">{{$category->category}}</option>
                            @endforeach
                        @endif
                            </select></p>


                {!! Form::submit('追加する', ['class' => 'btn btn-primary']) !!}
                </div>
        
            {!! Form::close() !!}
        @endif
        </div>
    </div>
@endsection