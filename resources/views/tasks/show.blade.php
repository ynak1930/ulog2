@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <h1>({{ $tasks->id }}){{$tasks->name}} の詳細ページ</h1>

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
                    <td><p>{!! nl2br(e($stops[$loop->index]['content'])) !!}</p><span class="text-muted">{!! nl2br(e($stops[$loop->index]['created_at'])) !!}</span></td>
                </tr>
                @endforeach

            </tbody>
        </table>
        </div>
        @endif
    </div>
    @endif

@endsection
