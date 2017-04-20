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
            <div class="col-sm-2">
                {{ Form::select('stage', $stages, NULL, array('class' => 'form-control', 'id' => 'stage')) }}
            </div>
            <div class="col-sm-3">
                {{ Form::text($name = 'name', $value = '', $attributes = array('class' => 'form-control', 'id' => 'name')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::select('grade', $grades, NULL, array('class' => 'form-control', 'id' => 'grade')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::select('monster', $monsters, NULL, array('class' => 'form-control', 'id' => 'monster')) }}
            </div>
            <div class="col-sm-1">
                {{ Form::number($name = 'number', $value = '', $attributes = array('class' => 'form-control', 'id' => 'number')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::button('新增', array('class' => 'btn btn-success', 'id' => 'add')) }}
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 stage-info">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="text-align: center" width="20%">關卡</th>
                        <th style="text-align: center" width="20%">名稱</th>
                        <th style="text-align: center" width="20%">難度</th>
                        <th style="text-align: center" width="30%">式神</th>
                        <th style="text-align: center" width="10%">數量</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($details as $detail)
                        <tr>
                            <td>{{ $detail->stageName }}</td>
                            <td>{{ $detail->name }}</td>
                            <td>{{ $detail->grade }}</td>
                            <td>{{ $detail->monsterName }}</td>
                            <td>{{ $detail->number }}</td>
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
            var monsterId = $('#monster').val();
            var grade = $('#grade').val();
            var number = $('#number').val();

            if (name.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "{{ action('AdminController@addStageDetail') }}",
                    data: {'stageId': stageId, 'name': name, 'monsterId': monsterId, 'grade': grade, 'number': number},
                    success: function (data) {
                    }
                });
            } else {
                alert('請先輸入內容！');
            }
        });
    </script>
@endsection