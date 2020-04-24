@extends('layouts.app')

@section('content')

    <h1>{{ $task->name }}を停止する。</h1>

    <div class="row">
        <div class="col-6">
    @if (Auth::check())
            {!! Form::model($task, ['route' => ['stops.update', $task->id] , 'method' => 'put']) !!}


                <div class="form-group">
                    {!! Form::label('content', 'コメント:') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
                </div>
                
                {!! Form::submit('STOP', ['class' => 'btn btn-danger']) !!}
        
            {!! Form::close() !!}
        @endif
        </div>
    </div>
@endsection