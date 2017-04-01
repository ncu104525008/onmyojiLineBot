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
            <div class="col-sm-3 col-sm-offset-2">
                {{ Form::select('stage', $stages, NULL, array('class' => 'form-control', 'id' => 'stage')) }}
            </div>
            <div class="col-sm-5">
                {{ Form::text($name = 'name', $value = '', $attributes = array('class' => 'form-control', 'id' => 'name')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::button('新增', array('class' => 'btn btn-success', 'id' => 'add')) }}
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 stage-info">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="text-align: center" width="20%">編號</th>
                        <th style="text-align: center" width="50%">關卡名稱</th>
                        <th style="text-align: center" width="30%">名稱</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($details as $detail)
                        <tr>
                            <td>{{ $detail->id }}</td>
                            <td>{{ $detail->stageName }}</td>
                            <td>{{ $detail->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#add').click(function () {
            var stageId = $('#stage').val();
            var name = $('#name').val();

            if (name.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "{{ action('AdminController@addStageDetail') }}",
                    data: {'stageId': stageId, 'name': name},
                    success: function (data) {
                    }
                });
            } else {
                alert('請先輸入內容！');
            }
        });
    </script>
@endsection