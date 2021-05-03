<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Grana LMCC | </title>

    <!-- Bootstrap -->
    <link href="public/lmcc/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="public/lmcc/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="public/lmcc/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="public/lmcc/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="public/lmcc/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="public/lmcc/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="public/lmcc/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="public/lmcc/build/css/custom.min.css" rel="stylesheet">
     
    <style>
        .brand-name{
            color: #73879C !important;
        }
        
        html, body{
            height: 100%;
        }
        
        .carousel-control.left{
            background: none;
            color: #000080;
        }
        
        .carousel-control.right{
            background: none;
            color: #000080;
        }
        
        .page{
            /*visibility: hidden;*/
        }
    </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-university"></i> <span>LMCC</span></a>
            </div> 

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <?php if($logo != null || $logo != ''){ ?>
                  <img src="<?php echo $logo ?>" alt="..." class="img-circle profile_img">
                <?php } else { ?>
                <img src="public/lmcc/images/UDG.png" alt="..." class="img-circle profile_img">
                <?php } ?>
                
              </div>
              <div class="profile_info">
                <span>Bienvenido,</span>
                <?php if($nombre != null || $nombre != ''){ ?>
                <h2><?php echo $nombre ?></h2>
                <?php } else { ?>
                <h2>Universidad de Guadalajara</h2>
                <?php } ?>
                
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>Menu</h3>
                <ul class="nav side-menu">
                  <li><a href="#inicio" class="spa_link"><i class="fa fa-home"></i> Inicio </a>
<!--                    <ul class="nav child_menu">
                      <li><a href="index.html">Dashboard</a></li>
                      <li><a href="index2.html">Dashboard2</a></li>
                      <li><a href="index3.html">Dashboard3</a></li>
                    </ul>-->
                  </li>
                  <!--<li><a href="#avances" class="spa_link"><i class="fa fa-check"></i> Avances</a>-->
<!--                    <ul class="nav child_menu">
                      <li><a href="form.html">General Form</a></li>
                      <li><a href="form_advanced.html">Advanced Components</a></li>
                      <li><a href="form_validation.html">Form Validation</a></li>
                      <li><a href="form_wizards.html">Form Wizard</a></li>
                      <li><a href="form_upload.html">Form Upload</a></li>
                      <li><a href="form_buttons.html">Form Buttons</a></li>
                    </ul>-->
                  </li>
                  <li><a href="#grafindicametros" class="spa_link"><i class="fa fa-pie-chart"></i> Grafindicámetros</a>
<!--                    <ul class="nav child_menu">
                      <li><a href="general_elements.html">General Elements</a></li>
                      <li><a href="media_gallery.html">Media Gallery</a></li>
                      <li><a href="typography.html">Typography</a></li>
                      <li><a href="icons.html">Icons</a></li>
                      <li><a href="glyphicons.html">Glyphicons</a></li>
                      <li><a href="widgets.html">Widgets</a></li>
                      <li><a href="invoice.html">Invoice</a></li>
                      <li><a href="inbox.html">Inbox</a></li>
                      <li><a href="calendar.html">Calendar</a></li>
                    </ul>-->
                  </li>
                  <li><a href="#estadisticas" class="spa_link"><i class="fa fa-line-chart"></i> Estadísticas</a>
<!--                    <ul class="nav child_menu">
                      <li><a href="tables.html">Tables</a></li>
                      <li><a href="tables_dynamic.html">Table Dynamic</a></li>
                    </ul>-->
                  </li>
                  <li><a href="#promedios" class="spa_link"><i class="fa fa-star"></i> Promedios</a>
<!--                    <ul class="nav child_menu">
                      <li><a href="tables.html">Tables</a></li>
                      <li><a href="tables_dynamic.html">Table Dynamic</a></li>
                    </ul>-->
                  </li>
                  <!--<li><a href="#rankings" class="spa_link"><i class="fa fa-star"></i> Rankings</a>-->
<!--                    <ul class="nav child_menu">
                      <li><a href="chartjs.html">Chart JS</a></li>
                      <li><a href="chartjs2.html">Chart JS2</a></li>
                      <li><a href="morisjs.html">Moris JS</a></li>
                      <li><a href="echarts.html">ECharts</a></li>
                      <li><a href="other_charts.html">Other Charts</a></li>
                    </ul>-->
                  </li>
                  <li><a href="#cambiarunidad" class="spa_link"><i class="fa fa-cog"></i>Cambiar unidad académica</a>
<!--                    <ul class="nav child_menu">
                      <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
                      <li><a href="fixed_footer.html">Fixed Footer</a></li>
                    </ul>-->
                  </li>
                  <li><a href="index.php?mod=auth&controlador=usuarios&accion=logout"><i class="fa fa-sign-out"></i>Salir</a>
<!--                    <ul class="nav child_menu">
                      <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
                      <li><a href="fixed_footer.html">Fixed Footer</a></li>
                    </ul>-->
                  </li>
                </ul>
              </div>


            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
<!--            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>-->
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
               <a class="navbar-brand brand-name" href="#">LMCC</a>  
              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      <?php if($abr != null || $abr != ''){ ?>
                      <img src="<?php echo $logo ?>" alt=""><?php echo $abr ?>
                      <?php } else { ?>
                      <img src="public/lmcc/images/UDG.png" alt="">UDG
                      <?php } ?>
                      
                      
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
<!--                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>-->
                    <!--<li><a href="#" class="cambiarunidad"><i class="fa fa-cog pull-right"></i> Cambiar unidad académica</a></li>-->
                    <li><a href="index.php?mod=auth&controlador=usuarios&accion=logout"><i class="fa fa-sign-out pull-right"></i> Salir</a></li>
                  </ul>
                </li>

              
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        
        <!-- page content -->
        
        <div class="right_col" role="main">
        <?php echo View::content() ?>    
   

        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Laboratorio de Monitoreo Continuo de la Calidad por <a href="http://certification-grana.org">Grana</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="public/lmcc/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="public/lmcc/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <script src="public/lmcc/js/pagemanager/pagemanager.js"></script>
    
    <!-- FastClick -->
    <script src="public/lmcc/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="public/lmcc/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="public/lmcc/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="public/lmcc/vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="public/lmcc/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="public/lmcc/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="public/lmcc/vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="public/lmcc/vendors/Flot/jquery.flot.js"></script>
    <script src="public/lmcc/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="public/lmcc/vendors/Flot/jquery.flot.time.js"></script>
    <script src="public/lmcc/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="public/lmcc/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="public/lmcc/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="public/lmcc/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="public/lmcc/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="public/lmcc/vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="public/lmcc/vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="public/lmcc/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="public/lmcc/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="public/lmcc/vendors/moment/min/moment.min.js"></script>
    <script src="public/lmcc/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- Highcharts -->
    <script src="public/lmcc/vendors/Highcharts-5.0.10/code/highcharts.js"></script>
    <script src="public/lmcc/vendors/Highcharts-5.0.10/code/highcharts-more.js"></script>
    <script src="public/lmcc/vendors/Highcharts-5.0.10/modules/exporting.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="public/lmcc/build/js/custom.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js" ></script>
    
    <?php  echo View::print_js(); ?>
    
    <script src="public/lmcc/js/main.js"></script>
	
  </body>
</html>
