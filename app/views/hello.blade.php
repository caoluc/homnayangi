@extends('layouts.default')

@section('css')

@stop

@section('main')

<div class="page jumbotron text-center">
    <h1 class="text-large text-primary">Hôm nay ăn gì ?</h1>
    <div class="row">
        <div class="col-md-3 col-md-offset-1 today-random">
            <h2 class="text-danger">Random</h2>
            @if (count($randomLogs))
                @foreach ($randomLogs as $index => $randomLog)
                    <h4 class="text-success">{{ $index + 1 }}. {{ $randomLog->meal->name }}</h4>
                @endforeach
            @else
                <h4 class="text-danger">Hiện chưa có kết quả Random. Come back later ^ ^</h4>
            @endif
            <hr>
            <div class="votes">
                <h2 class="text-danger">Ai đã vote gì ?</h2>
                @foreach($votes as $vote)
                    <div class="row vote-row">
                        <img src="{{ $vote->user->avatar_image_url }}" alt="{{ $vote->user->name }}" class="img-responsive img-circle pull-left avatar">
                        <div class="vote-info pull-left">
                            <span class="text-success">{{ $vote->user->name }}</span>: <span class="text-info">{{ $vote->meal->name }}</span><br/>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-8" id="point-chart"></div>
    </div>
</div>

<hr>

<div class="page jumbotron text-center">
    <h1 class="text-large text-primary">Gần đây ăn gì ?</h1>
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            @if (count($mealLogs))
                @foreach ($mealLogs as $index => $mealLog)
                    <h4 class="text-success"><span class='text-info'>{{ to_date_string($mealLog->date) }}</span>.
                    {{ $mealLog->meal->name }}</h4>
                @endforeach
            @else
                <h2 class="text-danger">Đã ăn gì đâu mà có lịch sử.</h2>
            @endif
        </div>
        <div class="col-md-7" id="count-chart"></div>
        <div class="col-md-1"></div>
    </div>
</div>

<div class="page jumbotron text-center">
    <h1 class="text-large text-primary">Danh sách các món ăn</h1>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="text-warning">
                        <th class="text-center">#</th>
                        <th class="text-center">Tên món ăn</th>
                        <th class="text-center">Điểm khởi đầu</th>
                        <th class="text-center">Điểm gia tăng</th>
                        <th class="text-center">Điểm hiện tại</th>
                        <th class="text-center">Đã ăn trong tuần này hay chưa ?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($meals as $index => $meal)
                        <tr class="{{ $meal->hasBeenChosenThisWeek() ? 'success' : '' }}">
                            <td> {{ $index + 1 }}</td>
                            <td> {{ $meal->name }} </td>
                            <td> {{ $meal->start_point }} </td>
                            <td> {{ $meal->step_point }} </td>
                            <td> {{ $meal->getCurrentPoint() }}</td>
                            <td> {{ $meal->hasBeenChosenThisWeek() ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@section('script')
    <script>
        var mealPointsData = {{ json_encode($mealPointsData) }};
        var mealCountData = {{ json_encode($mealCountData) }};
    </script>
@stop