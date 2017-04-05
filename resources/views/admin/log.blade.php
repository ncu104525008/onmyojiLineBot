@extends('admin.layouts.main')

@section('style')
    <style>
        .content {
            margin-top: 100px;
        }

        .stage-info {
            margin-top: 20px;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="container content">
        <div class="row">
            <p class="col-sm-12">共 <?=$count ?> 使用者，總次數： {{ $all_count }}</p>
        </div>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 stage-info">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="text-align: center" width="80%">UserId</th>
                        <th style="text-align: center" width="20%">次數</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count = 0; ?>
                    @foreach ($logs as $log)
                        <?php $count++; ?>
                        <tr>
                            <td>{{ $log->userId }}</td>
                            <td>{{ $log->count }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection