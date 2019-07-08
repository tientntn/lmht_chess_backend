<?php use Illuminate\Support\Str as Str; 
    use Illuminate\Support\Facades\Session as Session;
 ?>
@extends('template')

@section('title')
   User
@stop

@section('css')
  
@stop

@section('content')
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">{{ trans('template.user_system')}}</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="/">{{ trans('template.dashboard')}}</a></li>
                <li><a href="{{ url('/users') }}">{{ trans('template.user_list')}}</a></li>
                <li class="active">{{ empty($user->id) ? trans('template.add') : trans('template.update') }}</li>
            </ol>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="white-box">
            {!! Form::open(['url' => url('/users/'.$user->id), 'method' => empty($user->id) ? 'POST' : 'PUT', 'role' => 'form', 'files' => 'true', 'class' => 'form-horizontal group-border-dashed', 'style' => 'border-radius: 0px;', 'id' => 'form_user']) !!} 
              <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
              <input type="hidden" name="auth_role" value="<?php echo $auth->role_id ?>" id="auth_role">
              <div class="block-flat">
                <div class="header">
                  <h3>{{ trans('template.information') }}</h3>
                </div>
                <div class="content">
                  @include('errors/error_validation', ['errors' => $errors])

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.username') }} *</label>
                    <div class="col-sm-6">
                    @if (empty($user->id) )
                      {!! Form::text('username', $user->username, array('placeholder' => trans('template.username'), 'class' => 'form-control')) !!}
                    @else
                      <strong>{{ $user->username }}</strong>
                    @endif
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.password') }}</label>
                    <div class="col-sm-6">
                      {!! Form::password('password', array('class' => 'form-control', 'id' => 'password')) !!}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.password_confirm') }}</label>
                    <div class="col-sm-6">
                      {!! Form::password('password_confirmation', array('class' => 'form-control', 'id' => 'password_confirm')) !!}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.email') }}</label>
                    <div class="col-sm-6">
                      {!! Form::text('email', $user->email, array('placeholder' => 'Email', 'class' => 'form-control')) !!}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.phone') }}</label>
                    <div class="col-sm-6">
                      {!! Form::text('telephone', $user->telephone, array('placeholder' => trans('template.phone'), 'class' => 'form-control')) !!}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.full_name') }}</label>
                    <div class="col-sm-6">
                      {!! Form::text('full_name', $user->full_name, array('placeholder' => trans('template.full_name'), 'class' => 'form-control')) !!}
                    </div>
                  </div>


                </div>
              </div>
              <div class="row block-flat">
                <br/>
                <div class="col-sm-offset-2 col-sm-10">
                    <a href="/users" class="btn btn-default">Trở lại</a>
                    <button id="form_submit" type="submit" class="btn btn-primary wizard-next">Lưu thông tin</button>
                </div>
              </div>
              </form>
            </div>
          </div>
      </div>

@stop

@section('script')
  <script type="text/javascript" src="/js/bootstrap-multiselect.js"></script>
  
  
  
@stop

@section('scriptend')
    <script type="text/javascript">
      $(document).ready(function(){
       
       

      });
    </script>
@stop



