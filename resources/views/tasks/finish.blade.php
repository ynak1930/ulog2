@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1>{{ $task->name }}を完了する。</h1>


    @if (Auth::check())
            {!! Form::model($task, ['route' => ['tasks.update', $task->id] , 'method' => 'put']) !!}

        <div class="form-group">

                    {!! Form::label('content', 'コメント:') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
                
                {!! Form::submit('完了', ['class' => 'btn btn-dark']) !!}
        
            {!! Form::close() !!}

        </div>
    @endif
    </div>
@endsection