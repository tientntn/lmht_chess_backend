
@extends('template')

@section('title')
   Trang bị
@stop

@section('css')
   <link rel="stylesheet" type="text/css" href="/css/dataTables.bootstrap.css">
@stop

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="equipment-title">equipments manage</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <a href="/equipments/create" class="btn btn-info pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Thêm mới</a>
            <ol class="breadcrumb">
                <li><a href="/manage">Dashboard</a></li>
                <li class="active">equipments</li>
            </ol>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="white-box">
            <h3 class="box-title">Danh sách equipments</h3>
            @include('errors/error_validation', ['errors' => $errors])
              <div id="datatable-icons_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row table-responsive" >
                  <div class="col-sm-12">
                    <table id="datatable-icons" class="table table-striped dataTable no-footer" role="grid" aria-describedby="datatable-icons_info">
                      <thead>
                        <tr role="row">
                          <th style="width: 5%;">STT</th>
                          <th style="width: 15%;">Ảnh</th>
                          <th>Tiêu đề</th>
                          <th style="width: 10%;">Trạng thái</th>
                          <th style="width: 15%;">Thao tác</th>
                        </tr>
                      </thead>
                      <tbody>
                      @if ($equipments)
                        <?php $i = 1;?>
                        @foreach($equipments as $equipment)
                          <tr>
                              <td>{{ $i }}</td>
                              <td>
                                <img style="width: 100px;" src="{{ $equipment->urlPath('100x100') }}" /><br/>
                              </td>
                              <td>
                                {{ $equipment->title }}
                                <br/>
                                <a href="/equipments/{{ $equipment->slug }}" target="_blank">{{ $equipment->slug }}</a>
                              </td>
                              <td>{{ $equipment->displayStatus() }}</td>
                              <td><a class="btn btn-info btn-margin" href="/equipments/{{$equipment->id}}/edit" ><i class="fa fa-pencil"></i></a>
                                  <button data-toggle="modal" data-target="#mod-error" class="delete_equipment btn btn-danger btn-margin" equipments-id="{{ $equipment->_id }}" ><i class="fa fa-times"></i></button>
                               </td>
                          </tr>
                          <?php $i++?>
                        @endforeach
                      @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        </div>
      </div>
    </div>
          

        <div id="mod-error" class="modal fade in form-delete" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="text-center">                
                        <p>Bạn có chắn chắn muốn xóa không?</p>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <form method="POST" role="form"  id="form_model" class="text-center">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Không</button>
                        <button type="submit" class="btn btn-danger" id="model_submit">Có</button>
                      </form>
                  </div>
              </div>
              <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
      </div>
@stop

@section('script')
    <script src="/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
@stop

@section('scriptend')
    <script type="text/javascript">
      $(document).ready(function(){
        $('#datatable-icons').dataTable({
           "lengthMenu": [ 20, 50, 100],
           "equipmentLength": 20,
           "aoColumnDefs": [
              {
               bSortable: false,
               aTargets: [ -1 ]
              }
            ],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi",
                "search": "Tìm kiếm",
                "loadingRecords": "Xin vui lòng đợi - đang xử lý...",
                "zeroRecords": "Không tìm thấy bản ghi nào",
                "sInfoFiltered": " ( tìm kiếm từ _MAX_ bản ghi )",
                "infoEmpty": "Không tìm thấy dữ liệu để hiển thị",
                "info": "Hiển thị _START_ tới _END_ của _TOTAL_ bản ghi",                 
                "paginate": {
                  "last": "Trang cuối",
                  "previous": "Trang trước",
                  "next": "Trang sau",
                  "first": "Trang đầu"
                }
            }
        });
        $('.delete_equipment').click(function() {
          var id = $(this).attr('equipments-id');
          $('#form_model').attr('action', '/equipments/'+id+'/destroy');
        });


    }); //End document ready
    </script>  
@stop



