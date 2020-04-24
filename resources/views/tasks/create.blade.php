@extends('layouts.app')

@section('content')

    <h1>プロジェクト追加</h1>

    <div class="row">
        <div class="col-6">
    @if (Auth::check())
            {!! Form::model($task, ['route' => 'tasks.store']) !!}


                <div class="form-group">
                    {!! Form::label('name', 'プロジェクト名:') !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                </div>
        
                {!! Form::submit('追加する', ['class' => 'btn btn-primary']) !!}
        
            {!! Form::close() !!}
        @endif
        </div>
    </div>
@endsection