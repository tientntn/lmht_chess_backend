
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
            <h4 class="equipment-title">equipments manage</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <a href="/equipments/create" class="btn btn-info pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Thêm mới</a>
            <ol class="breadcrumb">
                <li><a href="/manage">Dashboard</a></li>
                <li><a href="/equipments">equipments</a></li>
                <li class="active">{{ empty($equipment->id) ? 'Thêm mới' : 'Sửa' }}</li>
            </ol>
        </div>
    </div>

          <div class="row">
            <div class="col-md-12">
              <div class="white-box">
                {!! Form::open(['url' => env('ADMIN_URL', '/').'equipments/'.$equipment->_id, 'method' => empty($equipment->_id) ? 'POST' : 'PUT', 'role' => 'form', 'files' => 'true', 'class' => 'form-horizontal group-border-dashed', 'style' => 'border-radius: 0px;', 'id' => 'form_equipment',]) !!}
                  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                
                  <div class="header">
                    <h3>Thông tin equipment</h3>
                  </div>
                    @include('errors/error_validation', ['errors' => $errors])
                      <div class="form-group">
                        <label class="col-sm-3 control-label">Tên trang bị *</label>
                        <div class="col-sm-6">
                           {!! Form::text('title', $equipment->title, array('placeholder' => '', 'class' => 'form-control')) !!} 
                        </div>
                      </div>
                      @if ($equipment->_id)
                         <div class="form-group">
                          <label class="col-sm-3 control-label">Slug</label>
                          <div class="col-sm-6">
                             {!! Form::text('slug', $equipment->slug, array('placeholder' => '', 'class' => 'form-control')) !!} 
                          </div>
                        </div>
                      @endif
                     <!--  <div class="form-group">
                        <label class="col-sm-3 control-label" >Mô tả ngắn</label>
                         <div class="col-sm-9">
                          <textarea name="short_content" id="short_content" class="rich_text">{{ old('short_content') ? old('short_content') : $equipment->short_content }}</textarea>
                        </div>
                      </div> -->
                      <div class="form-group">
                        <label class="col-sm-3 control-label" >Mô tả *</label>
                         <div class="col-sm-9">
                          <textarea name="content" id="content" class="rich_text">{{ old('content') ? old('content') : $equipment->content }}</textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" >Upload Ảnh</label>
                        <div class="col-sm-6" >
                            @if (!empty($equipment->image))
                              <img src="{{ $equipment->urlPath('100x100') }}" id="image_temp" class="image-thumb-upload"/>
                            @else
                              <img src="{{ config('image.image_url_admin').'/images/thumb_default.png' }}" id="image_temp" class="image-thumb-upload"/>
                            @endif
                            <input type="file" name="image_upload" id="image_upload"  class="form-control"/>     
                        </div>
                      </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" >Mô tả ngắn</label>
                             <div class="col-sm-9">
                              <textarea name="short_content" id="short_content" class="form-control">{{ old('short_content') ? old('short_content') : $equipment->short_content }}</textarea>
                            </div>
                        </div>
                          <div class="form-group">
                              <label class="col-sm-3 control-label" >Mảnh đồ 1</label>
                              <div class="col-sm-9">
                                  <select name="piece1" id="" class="">
                                      <option value="">none</option>
                                      @foreach($pieces as $piece)
                                          <option value="{{$piece['id']}}" {{$equipment->piece1 == $piece['id']?"selected=selected":""}}>{{$piece['title']}}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-3 control-label" >Mảnh đồ 2</label>
                              <div class="col-sm-9">
                                  <select name="piece2" id="" class="">
                                      <option value="">none</option>
                                      @foreach($pieces as $piece)
                                          <option value="{{$piece['id']}}" {{$equipment->piece2 == $piece['id']?"selected=selected":""}}>{{$piece['title']}}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>

                      <div class="header">
                          <br/>
                          <h3 class="box-title">Nội dung tiếng Anh</h3>
                          @include('inc/language_fields', ['object' => $equipment, 'fields' => $equipment->languageFields()])
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



