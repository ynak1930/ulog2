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
                        <!--{!! Form::label('timer', 'タイマー初期値(秒):')!!}-->
                        <!--{!! Form::number('timer', null, ['class' => 'form-control', 'min' => 0, 'max' => 2000000000]) !!}-->
                        
                        <p>タイマー初期値<br>
                        <input type="number" name="hour" value="0" min="0" max="33333333" size="8" maxlength="8">
                        :
                        <input type="number" name="minute" value="0" min="0" max="59" size="2" maxlength="2">
                        :
                        <input type="number" name="second" value="0" min="0" max="59" size="2" maxlength="2">
                        </p>
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