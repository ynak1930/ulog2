@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1>{{ $task->name }}を開始する。</h1>

    @if (Auth::check())
            {!! Form::model($task, ['route' => ['starts.update', $task->id] , 'method' => 'put']) !!}


                <div class="form-group">
                    {!! Form::label('content', 'コメント:') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control','maxlength'=>'626']) !!}
                </div>
                
                {!! Form::submit('START', ['class' => 'btn btn-primary']) !!}
        
            {!! Form::close() !!}
        @endif
        </div>
    </div>
@endsection