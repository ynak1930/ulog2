@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <h1>({{ $tasks->id }}){{$tasks->name}} の詳細ページ</h1>

    <div class="mt-4 row">
    @if (count($starts) > 0)
        <div class="col-sm-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-sm-3">開始コメント</th>
                    <th class="col-sm-3">日時</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($starts as $start)
                <tr>
                    <td>{{ $start->content }}</td>
                    <td>{{ $start->created_at}}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
        </div>
    @endif
    @if (count($stops) > 0)
        <div class="col-sm-6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="col-sm-3">停止コメント</th>
                    <th class="col-sm-3">日時</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stops as $stop)
                <tr>
                    <td>{{ $stop->content }}</td>
                    <td>{{ $stop->created_at}}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
        </div>
    @endif
    </div>
        @endif

@endsection
