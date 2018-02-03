<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>LindaWallet | <?=$title;?></title>
    
	<link rel="icon" href="/include/img/linda_icon.png" />
    <link href="/include/css/bootstrap.min.css" rel="stylesheet">
    <link href="/include/css/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="/include/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="/include/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="/include/css/animate.css" rel="stylesheet">
    <link href="/include/css/style.css" rel="stylesheet">
	
    <?=@$include_header;?>

</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> <span>
                        <a href="/">
                            <img alt="image" class="img-circle" style="width: 50px;" src="/include/img/linda_icon.png" />
                            </a>
                             </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="/include/css/font-bold" id="profile_name">
                            <?=Linda::getEmailPrefix($_SESSION['UserID']);?><b class="caret"></b> 
                            </strong>
                            </span> <span class="text-muted text-xs block" id="profile_role"></span></span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="/">Refresh</a></li>
                                <li class="divider"></li>
                                <li><a href="/logout">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            Linda+
                        </div>
                    </li>

                    <li <?=active("default");?>>
                    <a href="/"><i class="fa fa-home"></i> <span class="nav-label">Home</span></a>
                    </li>
                  <?php 
                  
                  /**
                   * Display wallet menus
                   * @var Ambiguous $_ACCOUNT
                   */
                  $_ACCOUNT['Wallets'] = LindaSQL::getWalletInfoTableByAccount($_SESSION['UserID']);
                  foreach($_ACCOUNT['Wallets'] as $wallet)
                  {
                      echo '
                         <li '.active($wallet[3]).' >
                         <a href="/wallet/'.$wallet[3].'"><i class="fa fa-trello"></i> <span class="nav-label">'.$wallet[2].'</span></a>
                         </li>
                        ';          
                  }
                  
                  ?>
                   

<!-- Default Multi-level menu for future usage TODO
                    <li>
                        <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Graphs</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="graph_flot.html">Flot Charts</a></li>
                            <li><a href="graph_morris.html">Morris.js Charts</a></li>
                            <li><a href="graph_rickshaw.html">Rickshaw Charts</a></li>
                            <li><a href="graph_chartjs.html">Chart.js</a></li>
                            <li><a href="graph_chartist.html">Chartist</a></li>
                            <li><a href="c3.html">c3 charts</a></li>
                            <li><a href="graph_peity.html">Peity Charts</a></li>
                            <li><a href="graph_sparkline.html">Sparkline Charts</a></li>
                        </ul>
                    </li>
                  -->

                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="#" style="width: 500px;">
                <div class="form-group" >
                    <input type="text" placeholder="Search Wallet" class="form-control" name="-search" id="search">
                </div>
                <ul class="list-group" id="result">

                </ul>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">LindaCoin Web Wallet</span>
                </li>
              
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary" id="notifications_size">2</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
           <li>
                              <div class="dropdown-messages-box">
                                  <a href="#" class="pull-left">
                                      <img alt="image" class="img-circle" src="/include/img/linda_icon.png">
                                  </a>
                                  <div class="media-body">
                                      <small class="pull-right" style="color: blue;">New</small>
                                      Your wallet has been created!<br /><br />
                                      <small class="text-muted">From Linda Project</small>
                                  </div>
                              </div>
                          </li>
                          <li class="divider"></li>
                      <li>
                              <div class="dropdown-messages-box">
                                  <a href="#" class="pull-left">
                                      <img alt="image" class="img-circle" src="/include/img/linda_icon.png">
                                  </a>
                                  <div class="media-body">
                                      <small class="pull-right" style="color: blue;">New</small>
                                      Linda Wallet - Beta Released!<br /><br />
                                      <small class="text-muted">From Linda Project</small>
                                  </div>
                              </div>
                          </li>
                          <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="#">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="/logout">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
               
            </ul>

        </nav>
        </div>

        <div class="row">
            <div class="col-lg-12">
              <div class="wrapper wrapper-content">
                <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2><img src="/include/img/linda_icon.png" style="height: 25px; width: 25px;" />&nbsp;&nbsp;Linda Wallet - <?=$title;?></h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="/">Home</a>
                        </li>
                        <li class="active">
                            <strong><a><?=$title;?></a></strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>




              <?=$content;?>
              </div>

                <div class="footer">
                    <div class="pull-right">
                     &bull;   <strong>Copyright</strong> Linda Wallet &copy; 2018 &bull;
                    </div>
                </div>
            </div>
        </div>

        </div>

        <div id="right-sidebar">
            <div class="sidebar-container">

                <ul class="nav nav-tabs navs-3">

                    <li class="active"><a data-toggle="tab" href="#tab-1">
                        Notes
                    </a></li>
                    <li><a data-toggle="tab" href="#tab-2">
                        Projects
                    </a></li>
                    <li class=""><a data-toggle="tab" href="#tab-3">
                        <i class="fa fa-gear"></i>
                    </a></li>
                </ul>



            </div>



        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/include/js/sweetalert.min.js" type="text/javascript"></script>
    <script src="/include/js/jquery-2.1.1.js"></script>
    <script src="/include/js/jquery.timeago.js" type="text/javascript"></script>

    <script src="/include/js/bootstrap.min.js"></script>
    <script src="/include/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/include/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="/include/js/plugins/flot/jquery.flot.js"></script>
    <script src="/include/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/include/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="/include/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/include/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="/include/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="/include/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/include/js/inspinia.js"></script>
    <script src="/include/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="/include/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="/include/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="/include/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="/include/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="/include/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="/include/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">
function notify(type, text) {
    setTimeout(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-bottom-right",
            showMethod: 'slideDown',
            timeOut: 7000
    	};
    	switch (type) {
		case 'success':
            toastr.success('Success', text);
			break;
		case 'warning':
            toastr.warning('Warning', text);
			break;
		case 'error':
            toastr.error('Error', text);
			break;
		default:
			break;
		}
    }, 0);
}
</script>
    <?=@$include_footer;?>
    <script>
   <?php 
   if(!isset($_SESSION['showWelcome']))
   {
       echo "$(document).ready(function() {

            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    positionClass: 'toast-bottom-right',
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.warning('The web wallet is undergoing beta testing!', 'Welcome to Linda+');

            }, 0);
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-bottom-right',
                    showMethod: 'slideDown',
                    timeOut: 1500
                };
                toastr.success('Loading Complete', 'Welcome to Linda Web Wallet');

            }, 1500);

        
	



        });";
       
       $_SESSION['showWelcome'] = 1;
   }
   ?>
        
    </script>

    
</body>
</html>
