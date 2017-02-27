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
            <div class="col-sm-8 col-sm-offset-2">
                <input id="name" class="form-control col-sm-10">
            </div>
            <div class="col-sm-2">
                <button id="add" class="btn btn-success">新增</button>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 stage-info">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="text-align: center" width="20%">編號</th>
                        <th style="text-align: center" width="80%">式神名稱</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($monsters as $monster)
                        <tr>
                            <td>{{ $monster->id }}</td>
                            <td>{{ $monster->name }}</td>
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
            var name = $('#name').val();

            if (name.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "{{ action('AdminController@addMonster') }}",
                    data: {'name': name},
                    success: function (data) {
                        window.location.reload();
                    }
                });
            } else {
                alert('請先輸入內容！');
            }
        });
    </script>
@endsection