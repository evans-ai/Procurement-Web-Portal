<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$baseUrl = Yii::$app->request->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <title>FRC Supplier Portal</title>
    <link rel="apple-touch-icon" href="<?= $baseUrl; ?>/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $baseUrl; ?>/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/charts/jquery-jvectormap-2.0.3.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/charts/morris.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/core/colors/palette-gradient.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/site.css?v=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/steps.css">
    <!-- END: Custom CSS-->
    <?php $this->head() ?>
    
    <style>
        form div.required label.control-label:after {
            content:" * ";
            color:red;
        }
        .hideexternally{
            display: none;
        }       
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="horizontal-layout horizontal-menu 2-columns  " data-open="hover" data-menu="horizontal-menu" data-col="2-columns">
<?php $this->beginBody() ?>
    
    <!-- BEGIN: Main Menu-->

    <div class="header-navbar navbar-expand-sm navbar navbar-horizontal navbar-fixed navbar-dark navbar-without-dd-arrow navbar-shadow" role="navigation" data-menu="menu-wrapper">
        <div class="navbar-container main-menu-content" data-menu="menu-container" style="width:100%">
            
			<ul class="nav navbar-nav float-left" id="main-menu-navigation" data-menu="menu-navigation">
				<li>
					<img class="brand-logo" alt="modern admin logo" src="<?= $baseUrl; ?>/images/logo/FRClogo.png">
				</li>
                <li class="nav-item"><a class="nav-link" href="<?= $baseUrl; ?>/home/dashboard">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $baseUrl; ?>/home/help">Help</a></li>                
            </ul>
            <ul class="nav navbar-nav float-right">
                <li class="nav-item">
                    <a href="<?= $baseUrl; ?>/site/logout" class="nav-link">
                        <i class="icon-power icons"></i>
                        Logout
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $baseUrl; ?>/supplierdata/user-profile">
                        <i class="icon-user icons"></i>
                        Edit Profile
                    </a>
                </li>	
            </ul>
        </div>
    </div>
    <div class="content-wrapper">
        <div class="content-body">
            <?= $content ?>
        </div>
    </div>


    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-shadow">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; <?= date('Y') ?> <a class="text-bold-800 grey darken-2" href="http://www.frc.go.ke" target="_blank">Financial Reporting Center(FRC</a></span><span class="float-md-right d-none d-lg-block">Safeguarding Your Retirement Benefits<span id="scroll-top"></span></span></p>
    </footer>
    <!-- END: Footer-->   
    <!-- BEGIN: Vendor JS-->
    <script src="<?= $baseUrl; ?>/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="<?= $baseUrl; ?>/vendors/js/ui/jquery.sticky.js"></script>
    <script src="<?= $baseUrl; ?>/vendors/js/charts/jquery.sparkline.min.js"></script>
    <script src="<?= $baseUrl; ?>/vendors/js/charts/chart.min.js"></script>
    <script src="<?= $baseUrl; ?>/vendors/js/charts/raphael-min.js"></script>
    <script src="<?= $baseUrl; ?>/vendors/js/charts/morris.min.js"></script>
    <script src="<?= $baseUrl; ?>/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js"></script>
    <script src="<?= $baseUrl; ?>/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js"></script>
    <script src="<?= $baseUrl; ?>/data/jvector/visitor-data.js"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="<?= $baseUrl; ?>/js/core/app-menu.js"></script>
    <script src="<?= $baseUrl; ?>/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="<?= $baseUrl; ?>/js/scripts/ui/breadcrumbs-with-stats.js"></script>
    <?php $this->endBody() ?>
</body>
<!-- END: Body-->

</html>
<?php $this->endPage() ?>