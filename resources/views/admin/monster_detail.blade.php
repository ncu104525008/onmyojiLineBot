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
                {{ Form::select('stage_detail', $stage_details, NULL, array('class' => 'form-control', 'id' => 'stage_detail')) }}
            </div>
            <div class="col-sm-2">
                {{ Form::select('monster', $monsters, NULL, array('class' => 'form-control', 'id' => 'monster')) }}
            </div>
            <div class="col-sm-3">
                {{ Form::number($name = 'number', $value = '', $attributes = array('class' => 'form-control', 'id' => 'number')) }}
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
                        <th style="text-align: center" width="20%">名稱</th>
                        <th style="text-align: center" width="10%">數量</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($monster_detail_list as $detail)
                        <tr>
                            <td>{{ $detail->id }}</td>
                            <td>{{ $detail->stageDetail }}</td>
                            <td>{{ $detail->monster }}</td>
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
            var stageDetailId = $('#stage_detail').val();
            var monsterId = $('#monster').val();
            var number = $('#number').val();

            if (number > 0) {
                $.ajax({
                    type: "POST",
                    url: "{{ action('AdminController@addMonsterDetail') }}",
                    data: {'stageDetailId': stageDetailId, 'monsterId': monsterId, 'number': number},
                    success: function (data) {
                    }
                });
            } else {
                alert('請先輸入內容！');
            }
        });
    </script>
@endsection