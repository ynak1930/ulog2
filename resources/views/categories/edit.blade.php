@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <h1>カテゴリー削除</h1>
    @if (Auth::check())


            {!!Form::open(['route' => 'categories.destroy', 'method'=>'delete'])!!}
                <div class="form-group">

                    <select name="category">
                        <option value="0">未分類</option>
                        @if (count($categories) > 0)
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->category}}</option>
                        @endforeach
                        @endif
                    </select>


                {!! Form::submit('削除する', ['class' => 'btn btn-danger']) !!}
                </div>
        
            {!! Form::close() !!}
        @endif
        </div>
    </div>
@endsection