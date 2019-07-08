@extends('template')

@section('title')
   User
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="/css/dataTables.bootstrap.css">
@stop

@section('content')
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">{{ trans('template.user_list')}}</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <a href="/users/create" class="btn btn-info pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">{{ trans('template.add')}}</a>
            <ol class="breadcrumb">
                <li><a href="/">{{ trans('template.dashboard')}}</a></li>
                <li class="active">{{ trans('template.user')}}</li>
            </ol>
        </div>
    </div>

    <div class="cl-mcont">
        
      <div class="row">
        <div class="col-md-12">
          <div class="white-box">
              <br/><br/>
              @include('errors/error_validation', ['errors' => $errors])
              <div>
                <div id="datatable-icons_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row table-responsive" >
                    <div class="col-sm-12">
                      <table id="datatable-icons"  cellpadding="0" cellspacing="0" border="0" class="display table table-hover" width="100%">
                        <thead>
                            <tr role="row">
                              <th style="width: 5%;">STT
                                <input type="checkbox" name="check_all" id="check_all"/>
                              </th>
                              <th style="width: 30%;">{{ trans('template.user')}}</th>
                              <th style="width: 12%;" >{{ trans('template.role')}}</th>
                              <th style="width: 10%;">{{ trans('template.created_at')}}</th>
                              <th style="width: 15%;">{{ trans('template.action')}}</th>
                            </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>

      

    </div>

        <div id="mod-error" class="modal fade in form-delete" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
              </div>
              <div class="modal-body">
                <div class="text-center">
                  <p id="model_content_confirm">{{ trans('template.delete_confirm') }}</p>
                </div>
              </div>
              <div class="modal-footer">
              <form method="POST" role="form" id="form_model" class="text-center">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <button type="button" data-dismiss="modal" class="btn btn-default">{{ trans('template.no') }}</button>
                <button type="submit" class="btn btn-danger" id="model_submit">{{ trans('template.yes') }}</button>
              </form>
              </div>
            </div>
            <!-- /.modal-content-->
          </div>
          <!-- /.modal-dialog-->
        </div>
        <div id="mod-error2" class="modal fade in form-delete" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
              </div>
              <div class="modal-body">
                <div class="text-center">
                  <p>Bạn có chắn chắn muốn xóa các mục đã chọn không?</p>
                </div>
              </div>
              <div class="modal-footer">
              <form method="POST" role="form"  id="form_model2" action="/user/deleteMulti" class="text-center">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                <div id="form_delete_multi_user">

                </div>
                <button type="button" data-dismiss="modal" class="btn btn-default">{{ trans('template.no') }}</button>
                <button type="submit" class="btn btn-danger" id="model_submit">{{ trans('template.yes') }}</button>
              </form>
              </div>
            </div>
            <!-- /.modal-content-->
          </div>
          <!-- /.modal-dialog-->
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
                url :"<?php echo url('users/search')?>", // json datasource
                type: "post",
                // data: {
                //   "_token": "<?php echo csrf_token(); ?>",
                //   "role_id": "<?php echo $role_id; ?>",
                //   "status": "<?php echo $status ?>",
                //   "city": $('#city').val(),
                //   "district": $('#district').val(),
                //   "commune": $('#commune').val()
                // },
                data: function ( d ) {
                    d._token = "<?php echo csrf_token(); ?>";
                    d.role = $('#role').val();
                    d.status = "<?php echo $status ?>";
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
        $('.lock_user').click(function() {
          var id = $(this).attr('user-id');
          var status = $(this).attr('user-status');
          if (status == '0') {
            $('#model_content_confirm').html('Bạn chắc chắn muốn kích hoạt người dùng này ?');
          } else {
            $('#model_content_confirm').html('Bạn chắc chắn muốn khóa người dùng này ?');
          }
          $('#form_model').attr('action', '/users/'+id+'/updateStatus');
        });

        $('button.delete_user').click(function() {
          var id = $(this).attr('user-id');
          $('#form_model').attr('action', '/users/'+id+'/destroy');
        });

        $('#export_excel').click(function(){
          var token = "{{ csrf_token() }}";
          var type = $('#type_export').val();
          $('#export_excel').attr('disabled', 'disabled');
          requestExport(token, type, 0);
        });

        $('#check_all').click(function(){
          var checked = $(this).prop('checked');
          $('.checkbox_user').prop('checked', checked);
        });
        $('#delete_multi_user').unbind().click(function(){
          var checkboxs = $('.checkbox_user:checked');
          console.log(checkboxs);
          if (checkboxs.length == 0) {
            alert('Xin hãy chọn tài khoản cần xóa!');
          } else {
            // $('#comment_ids').val(checkboxs);
            // $('#box_form_merge').css('display','block');
            //$('#form_merge_product_ids').html('');
            for(var i=0;i< checkboxs.length;i++)
            {
              var id = checkboxs[i].id;
              $('#user_ids').val(id);
              $('#form_delete_multi_user').append('<input type="hidden" name="user_ids[]" value="'+id+'">');
              $('#mod-error2').modal('show');  
            }    
          }
        });
      }

      function requestExport(token, type, page) {
      $.ajax({
          type: "GET",
          data : {
            _token : token,
            type : type,
            page: page
          },
          url: '/user/exportExcel'
        }).done(function( rels ) {
          if (rels.status == 200) {
            $('#progress_form').css('display', 'none');
            $('#progess_content').css('width', '0%');
            $('#progess_content').html('0% complate');
            $('#export_excel').removeAttr('disabled');
            $('#form-download-excel').attr('action', '/users/downloadExcel/'+rels.filename);
            $('#form-download-excel').submit();
          } else {
            $('#progress_form').css('display', 'block');
            $('#progess_content').css('width', rels.progress+'%');
            $('#progess_content').html(rels.progress+'% complate');
            page = page + 1;
            requestExport(token, type, page);
          }
        });
    }
    </script>

        <script type="text/javascript">
        $(document).ready(function(){
          $('#city').unbind().change(function(){
            var city = $(this).val();
            getDistricts(city);
            $('#datatable-icons').dataTable().fnDraw();
          });
          $('#district').unbind().change(function(){
            var district = $(this).val();
            getCommunes(district);
            $('#datatable-icons').dataTable().fnDraw();
          });

          $('#commune').unbind().change(function (e) {
            $('#datatable-icons').dataTable().fnDraw();
            //$('#datatable-icons').DataTable().ajax.reload();
          });
          $('#role').unbind().change(function(){
            $('#datatable-icons').dataTable().fnDraw();
          });
        });

        function getDistricts(city)
        {
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: "GET",
                data : {
                    _token : token
                },
                url: '/cities/'+city+'/districts'
            }).done(function( rels ) {
                $('#commune').empty();
                $('#commune').append($("<option></option>")
                    .attr("value",'')
                    .text(""));
                var selectbox = $('#district');
                selectbox.empty();
                selectbox.append($("<option></option>")
                    .attr("value",'')
                    .text(""));
                $.each(rels.data, function(key, object) {
                    selectbox.append($("<option></option>")
                        .attr("value",object.slug)
                        .text(object.name));
                });
            });
        }

        function getCommunes(district) {
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: "GET",
                data : {
                    _token : token
                },
                url: '/districts/'+district+'/communes'
            }).done(function( rels ) {
                var selectbox = $('#commune');
                selectbox.empty();
                selectbox.append($("<option></option>")
                    .attr("value",'')
                    .text(""));
                $.each(rels.data, function(key, object) {
                    selectbox.append($("<option></option>")
                        .attr("value",object.slug)
                        .text(object.name));
                });
            });
        }

        $('#export_csv').click(function(){
          var city = $('#city').val();
          var district = $('#district').val();
          var commune = $('#commune').val();
          var role = $('#role').val();
          window.open('/users/export-csv?city='+city+'&district='+district+'&commune='+commune+'&role='+role,'_blank');
        });

      </script>
@stop



