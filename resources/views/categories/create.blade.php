@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <h1>カテゴリー追加</h1>
    @if (Auth::check())
            {!! Form::model($category, ['route' => 'categories.store']) !!}


                <div class="form-group">

                        {!! Form::label('name', 'カテゴリー名:') !!}
                        {!! Form::text('name', null, ['class' => 'form-control']) !!}

                {!! Form::submit('追加する', ['class' => 'btn btn-primary']) !!}
                </div>
        
            {!! Form::close() !!}
        @endif
        </div>
    </div>
@endsection