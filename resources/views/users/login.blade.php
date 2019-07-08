
@extends('none_template')


@section('title')
  Đăng nhập | Quản trị hệ thống
@stop

@section('content')
    
    <div class="new-login-box">
                <div class="white-box">
                    <h3 class="box-title m-b-0">{{ trans('template.login_system') }}</h3>
                    <small>{{ trans('template.input_info_bellow') }}</small>

                  {!! Form::open(['url' => url('/login'), 'method' => 'post', 'class' => 'form-horizontal', 'style' => 'margin-bottom: 0px !important;']) !!}
                    @include('errors/error_validation')
                    <input type="hidden" name="captcha" value="" id="captcha">
                    @if (!empty($error))
                      <br/>
                      <div class="alert alert-danger alert-white rounded">
                          <button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button>
                          <div>  {{ $error }} </div>
                      </div>
                    @endif

                    <div class="form-group  m-t-20">
                      <div class="col-xs-12">
                        <label>{{ trans('template.username') }}</label>
                        {!! Form::text('username', Input::old('username',''), array('placeholder' => 'Tên đăng nhập', 'class' => 'form-control', 'id' => 'username', 'required' => '""')) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-xs-12">
                        <label>{{ trans('template.password') }}</label>
                        {!! Form::password('password', array('placeholder' => 'Mật khẩu', 'class' => 'form-control', 'id' => 'password', 'required' => '""' )) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <div class="checkbox checkbox-info pull-left p-t-0">
                          <input type="checkbox" name="remember" id="checkbox-signup" value="1"> 
                          <label for="checkbox-signup"> {{ trans('template.remember_me') }} </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                      <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">{{ trans('template.login') }}</button>
                      </div>
                    </div>
                  </form>
                </div>
      </div>    

@stop

@section('script')
  <script src="https://www.google.com/recaptcha/api.js?render=6LefqZ4UAAAAALEnsUbkmDg2NMzQ-4Y-ZkmRuHcH"></script>

  <script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LefqZ4UAAAAALEnsUbkmDg2NMzQ-4Y-ZkmRuHcH', {action: 'homepage'}).then(function(token) {
           $('#captcha').val(token);
        });
    });
  </script>
@stop

