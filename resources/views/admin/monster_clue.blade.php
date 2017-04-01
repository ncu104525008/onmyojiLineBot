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
            <div class="col-sm-3 col-sm-offset-1">
                {{ Form::select('monster', $monsters, NULL, array('class' => 'form-control', 'id' => 'monster')) }}
            </div>
            <div class="col-sm-4">
                {{ Form::text($name = 'clue', $value = '', $attributes = array('class' => 'form-control', 'id' => 'clue')) }}
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
                        <th style="text-align: center" width="50%">名稱</th>
                        <th style="text-align: center" width="50%">線索</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($monster_clue_list as $item)
                        <tr>
                            <td>{{ $item->monsterName }}</td>
                            <td>{{ $item->clue }}</td>
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
            var monsterId = $('#monster').val();
            var clue = $('#clue').val();

            if (clue.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "{{ action('AdminController@addMonsterClue') }}",
                    data: {'monsterId': monsterId, 'clue': clue},
                    success: function (data) {
                        $('#clue').val('');
                        swal('新增成功', '', 'success');
                    }
                });
            } else {
                alert('請先輸入內容！');
            }
        });
    </script>
@endsection