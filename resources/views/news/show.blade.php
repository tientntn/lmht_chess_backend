
@extends('template_webview')

@section('head')
@stop

@section('content')
  <div class="content">
    <div class="content_title">{!! $post->title ? $post->title : $post->content !!}</div>
    <i class="content_time">{{ date('M d, Y H:i:s', strtotime($post->created_at)) }}</i>
    {!! $post ? $post->content : '' !!}
  </div>

@stop

@section('scripts')
  <!-- <script src="https://mapipro2.happyskin.vn/js/jquery-2.1.4.min.js"></script> -->
   <!-- <script src="https://mapipro2.happyskin.vn/js/jquery.lazyload.min.js"></script>  -->
  
 <!--  <script type="text/javascript">
    $(document).ready(function(){


      var images = $('img').map(function(){
          $(this).attr('data-original', $(this).attr('src'));
          $(this).lazyload({ effect : "fadeIn", skip_invisible : false });
      })
       $('img').lazyload({ effect : "fadeIn"});

      var count = $("body img").length;
      $('.content').css('padding-bottom', -50);
    });
  </script> -->
@stop

