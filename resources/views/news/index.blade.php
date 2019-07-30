
@extends('template')

@section('title')
   Tin tức
@stop

@section('css')
   <link rel="stylesheet" type="text/css" href="/css/dataTables.bootstrap.css">
@stop

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="equipment-title">News manage</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <a href="/newss/create" class="btn btn-info pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Thêm mới</a>
            <ol class="breadcrumb">
                <li><a href="/manage">Dashboard</a></li>
                <li class="active">Tin tức</li>
            </ol>
        </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="white-box">
            <h3 class="box-title">Danh sách Tộc</h3>
            @include('errors/error_validation', ['errors' => $errors])
              <div id="datatable-icons_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row table-responsive" >
                  <div class="col-sm-12">
                    <table id="datatable-icons" class="table table-striped dataTable no-footer" role="grid" aria-describedby="datatable-icons_info">
                      <thead>
                        <tr role="row">
                          <th style="width: 5%;">STT</th>
                          <th style="width: 100px;">Ảnh</th>
                          <th>tiêu đề</th>
                          <th style="width: 10%;">Ngôn ngữ</th>
                          <th style="width: 10%;">Ngày tạo</th>
                          <th style="width: 15%;">Thao tác</th>
                        </tr>
                      </thead>
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
        var dataTable = $('#datatable-icons').DataTable( {
            "processing": true,
            "serverSide": true,
            "lengthMenu": [ 50, 100],
            "pageLength": 50,
            "aoColumnDefs": [
              {
                 bSortable: false,
                 aTargets: [  -1, -5 ]
              }
            ],
            "order": [[ 0, "desc" ]],
            "language": {
                  "lengthMenu": "<?php echo trans('template.lengthMenu')?>",
                  "search": "<?php echo trans('template.search')?>",
                  "loadingRecords": "<?php echo trans('template.search')?>",
                  "zeroRecords": "<?php echo trans('template.zeroRecords')?>",
                  "sInfoFiltered": "<?php echo trans('template.sInfoFiltered')?>",
                  "infoEmpty": "<?php echo trans('template.infoEmpty')?>",
                  "info": "<?php echo trans('template.info')?>",
                  "paginate": {
                    "last": "<?php echo trans('template.page_last')?>",
                    "previous": "<?php echo trans('template.page_previous')?>",
                    "next": "<?php echo trans('template.page_next')?>",
                    "first": "<?php echo trans('template.page_first')?>"
                  }
            },
            "ajax":{
                url :"<?php echo url('newss/search')?>", // json datasource
                type: "get",
                data: function ( d ) {
                    d._token = "<?php echo csrf_token(); ?>";
                }, 
                error: function(){
                    $(".datatable-icons-error").html("");
                    $("#datatable-icons").append('<tbody class="employee-grid-error"><tr><th colspan="3">Không tìm thấy dữ liệu</th></tr></tbody>');
                    $("#datatable-icons_processing").css("display","none");
 
                }
            }
        } );
        
        dataTable.on('xhr', function () {
          setTimeout(function(){
            initFunctions();
          }, 1000);
        });

         }); //End document ready

        function initFunctions(){
          $('.delete_news').click(function() {
            var id = $(this).attr('news-id');
            $('#form_model').attr('action', '/newss/'+id+'/destroy');
          });
        }; //End document ready
    </script>  
@stop



