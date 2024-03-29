
@extends('template')

@section('title')
    Trang bị
@stop

@section('css')
  <link href="/css/editor/redactor.css?v=1.1" rel="stylesheet">
@stop

@section('content')

    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="equipment-title">category manage</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <a href="/equipments/create" class="btn btn-info pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Thêm mới</a>
            <ol class="breadcrumb">
                <li><a href="/manage">Dashboard</a></li>
                <li><a href="/equipments">categoryies</a></li>
                <li class="active">{{ empty($category->id) ? 'Thêm mới' : 'Sửa' }}</li>
            </ol>
        </div>
    </div>

          <div class="row">
            <div class="col-md-12">
              <div class="white-box">
                {!! Form::open(['url' => env('ADMIN_URL', '/').'categories/'.$category->_id, 'method' => empty($category->_id) ? 'POST' : 'PUT', 'role' => 'form', 'files' => 'true', 'class' => 'form-horizontal group-border-dashed', 'style' => 'border-radius: 0px;', 'id' => 'form_equipment',]) !!}
                  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                
                  <div class="header">
                    <h3>Thông tin equipment</h3>
                  </div>
                    @include('errors/error_validation', ['errors' => $errors])
                      <div class="form-group">
                        <label class="col-sm-3 control-label">Tên bộ tộc *</label>
                        <div class="col-sm-6">
                           {!! Form::text('title', $category->title, array('placeholder' => '', 'class' => 'form-control')) !!} 
                        </div>
                      </div>
                      @if ($category->_id)
                         <div class="form-group">
                          <label class="col-sm-3 control-label">Slug</label>
                          <div class="col-sm-6">
                             {!! Form::text('slug', $category->slug, array('placeholder' => '', 'class' => 'form-control')) !!} 
                          </div>
                        </div>
                      @endif
                      <div class="form-group">
                        <label class="col-sm-3 control-label" >Mô tả</label>
                         <div class="col-sm-9">
                          <textarea name="content" id="content" class="form-control">{{ old('content') ? old('content') : $category->content }}</textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" >Kích hoạt</label>
                         <div class="col-sm-9">
                          <textarea name="power" id="power" class="form-control">{{ old('power') ? old('power') : $category->power_text }}</textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-3 control-label" >Upload Ảnh</label>
                        <div class="col-sm-4" >
                          <label>Active</label>
                            @if (!empty($category->image_active))
                              <img src="{{ $category->urlPath('100x100', 'image_active') }}" class="image-thumb-upload"/>
                            @endif
                            <input type="file" name="image_upload" id="image_upload"  class="form-control"/>
                        </div>
                        <div class="col-sm-4" >
                          <label>Inactive</label>
                            @if (!empty($category->image_inactive))
                              <img src="{{ $category->urlPath('100x100', 'image_inactive') }}" class="image-thumb-upload"/>
                            @endif
                            <input type="file" name="image_upload2" id="image_upload"  class="form-control"/>
                        </div>
                      </div>

                    <div class="header">
                    <br/>
                      <h3 class="box-title">Nội dung tiếng Anh</h3>
                      @include('inc/language_fields', ['object' => $category, 'fields' => $category->languageFields()])
                  </div>
                  <div class="row">
                    <div class="col-sm-offset-4 col-sm-8">
                        <a href="/equipments" class="btn btn-default">Trở lại</a>
                        <button id="form_submit" type="submit" class="btn btn-primary wizard-next">Lưu thông tin</button>
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>

    </div>
@stop

@section('script')
<script type="text/javascript" src="/js/editor/redactor.js?v=1.1"></script>
<script type="text/javascript" src="/js/editor/vi.js?v=1.1"></script>
@stop

@section('scriptend')
     <script type="text/javascript">
      $(document).ready(function(){
        $( ".rich_text" ).each(function(){
          $(this).redactor(
              {
                imageUpload: '/ajax/uploadImage?_token='+$('meta[name="csrf-token"]').attr('content'),
                lang: 'vi',
                imageUploadCallback: true
              }
            );
        });

        $('#image_upload').change(function(){
          var size = event.target.files[0].size;
          if (size > 2048000) {
            alert('Dung lượng file vượt quá 2Mb');
            $('#img_upload').val('');
            $('#image_temp').attr('src', '');
          } else {
            $('#image_temp').attr('src',URL.createObjectURL(event.target.files[0]));
          }
        });


      });//document

     
    </script>
@stop



