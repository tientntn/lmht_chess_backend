<?php use Illuminate\Support\Str as Str; 
    use Illuminate\Support\Facades\Session as Session;
 ?>
@extends('template')

@section('title')
   Change password
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
                <li><a href="{{ url('/users') }}">{{ trans('template.changepassword')}}</a></li>
                <li class="active">{{ empty($user->id) ? trans('template.add') : trans('template.update') }}</li>
            </ol>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="white-box">
            {!! Form::open(['url' => url('/users/change-password'), 'method' => 'POST', 'role' => 'form', 'class' => 'form-horizontal group-border-dashed', 'style' => 'border-radius: 0px;', 'id' => 'form_user']) !!} 
              <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div class="header">
                  <h3>Thông tin</h3>
                </div>
                <div class="content">
                  @include('errors/error_validation', ['errors' => $errors])

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.password_current') }}</label>
                    <div class="col-sm-6">
                      {!! Form::password('password_old', array('class' => 'form-control', 'id' => 'password_current')) !!}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.password_new') }}</label>
                    <div class="col-sm-6">
                      {!! Form::password('password', array('class' => 'form-control', 'id' => 'password')) !!}
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ trans('template.password_confirm') }}</label>
                    <div class="col-sm-6">
                      {!! Form::password('password_confirm', array('class' => 'form-control', 'id' => 'password_confirm')) !!}
                    </div>
                  </div>

                </div>
              <div class="row block-flat">
                <br/>
                <div class="col-sm-offset-2 col-sm-10">
                    <a href="/users" class="btn btn-default">{{ trans('template.back') }}</a>
                    <button id="form_submit" type="submit" class="btn btn-primary wizard-next">{{ trans('template.save') }}</button>
                </div>
              </div>
              </form>
            </div>
          </div>
      </div>
@stop

@section('script')
  
@stop

@section('scriptend')
@stop



