
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon.png">
    <title>@yield('title') | Quản trị hệ thống</title>



    <link href="/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="/plugins/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">

    <!-- toast CSS -->
    <!-- <link href="/back/plugins/toast-master/css/jquery.toast.css" rel="stylesheet"> -->
    <!-- morris CSS -->
    <!-- <link href="/back/plugins/morrisjs/morris.css" rel="stylesheet"> -->
    <!-- chartist CSS -->
    <link href="/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!-- Calendar CSS -->
    <!-- <link href="/back/plugins/calendar/dist/fullcalendar.css" rel="stylesheet" /> -->
    <!-- animation CSS -->
    <script src="/plugins/jquery/dist/jquery.min.js"></script>

    <link href="/css/animate.css" rel="stylesheet">

    @yield('css')
    <!-- Custom CSS -->
    <link href="/css/style.css?v=1.1" rel="stylesheet">
    <!-- color CSS -->
    <link href="/css/colors/default.css" id="theme" rel="stylesheet">
    <link href="/css/extra.css?v=1.6" rel="stylesheet">


</head>

<body class="fix-header">
    <!-- Preloader -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <a class="logo" href="/">
                         <b>
                            <img src="/images/favicon.png" alt="home" class="dark-logo" style="width: 20px;"/>
                            <!-- <img src="/images/admin-logo-dark.png" alt="home" class="light-logo" /> -->
                            <!-- <img src="/images/favicon.png" alt="home" class="light-logo" style="width: 30px;"/> -->
                            <img src="/images/favicon.png" alt="home" class="light-logo" style="width: 20px;"/>
                         </b>
                            <span class="hidden-xs">
                            <!-- <img src="/images/favicon.png" alt="home" class="dark-logo"  style="width: 30px;"/> -->
                            <span style="color: #000;"><strong>TECHUP</strong> GAME</span>
                            <!-- <img src="/images/admin-text-dark.png" alt="home" class="light-logo" /> -->
                         </span> </a>
                </div>
                <ul class="nav navbar-top-links navbar-left">
                    <li><a href="javascript:void(0)" class="open-close waves-effect waves-light"><i class="ti-close ti-menu"></i></a></li>
                 
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <form role="search" class="app-search hidden-sm hidden-xs m-r-10" action="#">
                            <input name="keyword" type="text" placeholder="{{ trans('template.search') }}..." class="form-control"> <a href=""><i class="fa fa-search"></i></a>
                        </form>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                            <!-- <img src="/images/users/varun.jpg" alt="user-img" width="36" class="img-circle"> -->
                            <b class="hidden-xs">{{ $auth->username }}</b>
                            <span class="caret"></span> 
                        </a>
                        <ul class="dropdown-menu dropdown-user animated">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="/images/avatar_default.png" alt="user" /></div>
                                    <div class="u-text"><h4>{{ $auth->full_name }}</h4><p class="text-muted">{{ $auth->email }}</p>
                                        <a href="/users/{{ $auth->_id }}/edit" class="btn btn-rounded btn-danger btn-sm">{{ trans('template.profile') }}</a>
                                    </div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/users/change-password"><i class="ti-lock"></i> {{ trans('template.changepassword') }}</a></li>
                            <li><a href="#" class="right-side-toggle"><i class="ti-settings"></i> {{ trans('template.theme') }}</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/logout"><i class="fa fa-power-off"></i> {{ trans('template.logout') }}</a></li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
        </nav>

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <?php $segment = Request::segment(1);?>
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">MENU</span></h3>
                </div>
                <ul class="nav" id="side-menu" style="padding-top: 60px;">
                    <li><a href="/manage" class="waves-effect {{ $segment == '' ? 'active' : '' }}"><i class="linea-icon fa-fw ti-bar-chart-alt"></i><span class="hide-menu">{{ trans('template.dashboard') }}</span></a> </li>
                    <li><a href="/equipments" class="waves-effect {{ $segment == '' ? 'active' : '' }}"><i class="linea-icon fa-fw ti-bar-chart-alt"></i><span class="hide-menu">Trang bị</span></a> </li>
                    <li><a href="/pieces" class="waves-effect {{ $segment == '' ? 'active' : '' }}"><i class="linea-icon fa-fw ti-bar-chart-alt"></i><span class="hide-menu">Mảnh ghép</span></a> </li>
                    <li><a href="/heroes" class="waves-effect {{ $segment == '' ? 'active' : '' }}"><i class="linea-icon fa-fw ti-bar-chart-alt"></i><span class="hide-menu">Tướng</span></a> </li>
                    <li><a href="/categories" class="waves-effect {{ $segment == '' ? 'active' : '' }}"><i class="linea-icon fa-fw ti-bar-chart-alt"></i><span class="hide-menu">Tộc</span></a> </li>

                    <li class="devider"></li>
                    <li> <a href="/users" class="waves-effect {{ $segment == 'users' ? 'active' : '' }}"><i data-icon="/" class="linea-icon fa-fw fa ti-user"></i> Tài khoản hệ thống</a></li>
                    <li><a href="/users/change-password" class="waves-effect {{ $segment == 'patient-duplicates' ? 'active' : '' }} "><i data-icon="7" class="linea-icon fa-fw ti-unlock"></i><span>{{ trans('template.changepassword') }}</span></a> </li>
                    <li><a href="/logout" class="waves-effect"><i data-icon="7" class="linea-icon fa-fw ti-power-off"></i><span>{{ trans('template.logout') }}</span></a> </li>
                </ul>
            </div>
        </div>
        <!-- Left navbar-header end -->
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                @yield('content')
                
                <!-- .row -->
                <!-- .right-sidebar -->
               <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> {{ trans('template.change_theme') }} <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>{{ trans('template.silebar_light') }}</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme working">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="gray" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                <li class="full-width"><b>{{ trans('template.silebar_dark') }}</b></li>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="gray-dark" class="yellow-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme">12</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.right-sidebar -->
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center" style="padding: 0px 30px !important;"> 2017 © Power by TechUp team.
                <div class="col-md-12 row-footer" style="display: flex;justify-content: center;margin-top: 10px;">
                    <a class="main-logo-chai" href="https://vi-vn.facebook.com/CTCLQG/" target="_blank">
                        <img src="/images/lg1.jpg" class="image-logo-chai" style=" width: 50px;height: auto;vertical-align: middle;padding: 5px;">
                    </a>
                    <a class="main-logo-chai" href="http://vatld.org.vn/" target="_blank">
                        <img src="/images/lg2.jpg" class="image-logo-chai" style=" width: 50px;height: auto;vertical-align: middle;padding: 5px;">
                    </a>
                    <a class="main-logo-chai" href="http://hytcc.org.vn/" target="_blank">
                        <img src="/images/lg3.jpg" class="image-logo-chai" style=" width: 50px;height: auto;vertical-align: middle;padding: 5px;">
                    </a>
                    <a class="main-logo-chai" href="http://www.tbhelp.org/" target="_blank">
                        <img src="/images/lg4.jpg?v=1" class="image-logo-chai" style=" width: 50px;height: auto;vertical-align: middle;padding: 5px;">
                    </a>
                    <a class="main-logo-chai" href="https://clintonhealthaccess.org/vietnamese-stories-hard-work-moved-find-way-help/" target="_blank">
                        <img src="/images/lg5.jpg" class="image-logo-chai" style=" width: 50px;height: auto;vertical-align: middle;padding: 5px;">
                    </a>
                </div>
            </footer>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->

    
    <!-- Bootstrap Core JavaScript -->
    <script src="/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="/plugins/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="/js/waves.js"></script>
    <!--Counter js -->
    <!-- <script src="/plugins/waypoints/lib/jquery.waypoints.js"></script>
    <script src="/plugins/counterup/jquery.counterup.min.js"></script> -->
    <!--Morris JavaScript -->
    <!-- <script src="/plugins/raphael/raphael-min.js"></script> -->
    <!-- Thu vien cho chart -->
    <!-- <script src="/plugins/morrisjs/morris.js"></script> -->

    <!-- chartist chart -->
<!--     <script src="/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script> -->
    <script src="/plugins/flot/jquery.flot.js" type="text/javascript"></script>
    <script src="/plugins/flot/jquery.flot.pie.js" type="text/javascript"></script>
    <!-- Calendar JavaScript -->
    <!-- <script src="/plugins/moment/moment.js"></script>
    <script src='/plugins/calendar/dist/fullcalendar.min.js'></script>
    <script src="/plugins/calendar/dist/cal-init.js"></script> -->
    <!-- Custom Theme JavaScript -->
    <script src="/js/custom.js"></script>

    <!-- <script src="/js/dashboard1.js"></script> -->
    <!-- Custom tab JavaScript -->
    <script src="/js/cbpFWTabs.js"></script>
    <!-- <script type="text/javascript">
    (function() {
        [].slice.call(document.querySelectorAll('.sttabs')).forEach(function(el) {
            new CBPFWTabs(el);
        });
    })();
    </script> -->
    <!--Style Toast -->
    <!-- <script src="/plugins/toast-master/js/jquery.toast.js"></script> -->
    <!--Style Switcher -->
    <!-- Phần này để set thay đổi theme. Project mới chú ý thay đổi đường dẫn theme file css/color trong file này. và có thể set default theme -->
    <script src="/plugins/styleswitcher/jQuery.style.switcher.js"></script>

    @yield('script')
    @yield('scriptend')
</body>

</html>