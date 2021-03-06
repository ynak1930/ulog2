@extends('layouts.app')

@section('content')
    @if (Auth::check())
        {{ Auth::user()->name }}

    @else
    <div class="center jumbotron">
        <div class="text-center">
            <h1>Welcome to the ULog</h1>
            <h3>{{$usercnt}} Users</h3>
            <h3>{{$taskcnt}} Projects</h3>
            <h3>{{$taskmcnt}} Working</h3>

            {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
        </div>
    </div>
    @endif
@endsection