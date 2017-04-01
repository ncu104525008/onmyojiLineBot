@extends('admin.layouts.main')

@section('style')
    <style>
        .content {
            margin-top: 100px;
        }

        .row {
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container content">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                {{ Form::label('account', '帳號') }}
                {{ Form::text($name = 'account', $value = '', $attributes = array('class' => 'form-control', 'id' => 'account')) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                {{ Form::label('password', '密碼') }}
                {{ Form::password($name = 'password', $attributes = array('class' => 'form-control', 'id' => 'password')) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 col-sm-offset-9">
                {{ Form::button('登入', array('class' => 'btn btn-success col-sm-12', 'id' => 'login')) }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#login').click(function () {
            var account = $('#account').val();
            var password = $('#password').val();
            $.ajax({
                type: "POST",
                url: "{{ action('UsersController@login') }}",
                data: {'account': account, 'password': password},
                success: function (data) {
                    if (data == 'success')
                    {
                        swal({
                            title: '登入成功',
                            text: '',
                            type: 'success'
                        },
                        function () {
                            javascript:window.location.reload();
                        });
                    }
                    else
                    {
                        swal("登入失敗", "", "error");
                    }
                }
            });
        });
    </script>
@endsection