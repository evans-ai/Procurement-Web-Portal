<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;


$baseUrl = Yii::$app->request->baseUrl;
$UserName = Yii::$app->user->identity->FirstName.' '.Yii::$app->user->identity->MiddleName;
$CompanyName = Yii::$app->user->identity->CompanyName;
$ProfilePhoto = Yii::$app->user->identity->ProfilePhoto;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
	<meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
	<meta name="author" content="PIXINVENT">
	
	<link rel="apple-touch-icon" href="<?= $baseUrl; ?>/images/FRClogo.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $baseUrl; ?>/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->	
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/material-vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/pickers/daterange/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/datatables/datatables.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/material.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/components.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/material-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/material-colors.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/core/menu/menu-types/material-vertical-menu-modern.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/plugins/forms/wizard.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/plugins/pickers/daterange/daterange.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/assets/css/style.css">
     <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/site.css">
    <!-- END: Custom CSS-->
	<?php $this->registerCsrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>



	<?php $this->head() ?>	
	<style>
		form div.required label.control-label:after {
            content:" * ";
            color:black;
        }
        .hideexternally{
            display: none;
        }		
	</style>	
</head>
<body class="vertical-layout vertical-menu-modern material-vertical-layout material-layout 2-columns   fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
<?php $this->beginBody() ?>
	<!-- BEGIN: Header-->
	<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
		<div class="navbar-wrapper">
			<div class="navbar-header">
				<ul class="nav navbar-nav flex-row">
					<li class="nav-item mobile-menu d-lg-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a>
					</li>
					<li class="nav-item mr-auto">							
						<a class="navbar-brand" href="<?= $baseUrl; ?>/home/">
							<h3 class="brand-text">FRC Supplier Portal</h3>
						</a>

					</li>
					<li class="nav-item d-none d-lg-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
					<li class="nav-item d-lg-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="material-icons mt-1">more_vert</i></a></li>
				</ul>
			</div>
			<div class="navbar-container content">
				<div class="collapse navbar-collapse" id="navbar-mobile">
					<ul class="nav navbar-nav mr-auto float-left">							
						<li class="nav-item d-lg-block d-none"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
						<li class="nav-item d-lg-block d-none"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><?= $CompanyName; ?></a></li>
					</ul>
					<ul class="nav navbar-nav float-right">
						<li class="nav-item d-lg-block d-none">
							<a href="<?= $baseUrl; ?>/site/logout" class="dropdown-item" href="login-with-bg-image.html">
								<i class="material-icons">power_settings_new</i> 
								Logout
							</a>
						</li>
						<li class="nav-item d-lg-block d-none">
							<a class="dropdown-item" href="<?= $baseUrl; ?>/supplierdata/user-profile"><i class="material-icons">person_outline</i> Edit Profile</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<!-- END: Header-->
	<!-- BEGIN: Main Menu-->
	<div class="main-menu material-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
		<div class="user-profile">
			<div class="user-info text-center pb-2"><img class="user-img img-fluid rounded-circle w-50 mt-2" src="<?= $baseUrl; ?>/images/logo/FRClogo.png" alt="" width="100" height="80" />
				<div class="name-wrapper d-block dropdown mt-1"><a class="" id="user-account" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="user-name">
					<a class="white" href="<?= $baseUrl; ?>/supplierdata/profile"><?= $CompanyName; ?></a></span></a>	
				</div>
			</div>
		</div>
		<div class="main-menu-content">
			<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">					
				<li class=" navigation-header"><span data-i18n="nav.category.ecommerce">
					<a href="<?= $baseUrl; ?>/home/dashboard"><i class="material-icons">add_shopping_cart</i><span class="menu-title" data-i18n=""> DASHBOARD</span></a>
				</li>
				<li class=" nav-item">
					<a href="<?= $baseUrl; ?>/supplierdata/profile"><i class="material-icons">add_shopping_cart</i><span class="menu-title" data-i18n="">Business Particulars</span></a>
				</li>			
				<li class=" nav-item">
					<a href="<?= $baseUrl; ?>/home/favourites"><i class="material-icons">add_shopping_cart</i><span class="menu-title" data-i18n="">My Favourites</span></a>
				</li>
				<li class=" nav-item">
					<a href="<?= $baseUrl; ?>/home/applications"><i class="material-icons">add_shopping_cart</i><span class="menu-title" data-i18n="">My Applications</span></a>
				</li>				
				<li class=" nav-item"><a href="#"><i class="material-icons">add_shopping_cart</i><span class="menu-title" data-i18n="nav.menu_levels.main">RFX</span></a>
					<ul class="menu-content">
						<li class="menu-item">
							<a href="/home/list"><i class="material-icons">add_shopping_cart</i><span class="menu-title" data-i18n="">Open RFX</span></a>
						</li>
						
						<li class="menu-item">
							<a href="/home/expired"><i class="material-icons">add_shopping_cart</i><span class="menu-title" data-i18n="">Expired RFX</span></a>
						</li>
					</ul>
				</li>
				<li class=" nav-item">
					<a href="/home/help"><i class="material-icons">help</i><span class="menu-title" data-i18n="">Help</span></a>
				</li>	
			</ul>
		</div>
	</div>
	
	<!-- END: Main Menu-->


	 <!-- BEGIN: Content-->
	<div class="app-content content">
        <div class="content-header row">
            <div class="content-header-dark bg-img col-12">
                <div class="row">
                    <div class="content-header-left col-md-12 col-12 mb-2">
                        <h3 class="content-header-title white">Procurement</h3>
                        <div class="row breadcrumbs-top">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">
                                        <a href="<?= $baseUrl; ?>/home/index" class="content-header-title white">Home</a></span></a>
                                    </li>                                    
                                </ol>
                                
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content-body">

            <!----Body   -->
            		<?= $content ?>
            <!-- end Body -->

            </div>
        </div>
    </div>

	<div class="sidenav-overlay"></div>
	<div class="drag-target"></div>

	<!-- BEGIN: Footer-->
	<footer class="footer footer-static footer-light navbar-border navbar-shadow">
		<p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; <?= date('Y'); ?> <a class="text-bold-800 grey darken-2" href="http://www.frc.go.ke" target="_blank"> Financial Reporting Center(FRC)</a></span><span class="float-md-right d-none d-lg-block">To protect the integrity of our financial system <span id="scroll-top"></span></span></p>
	</footer>
	<!-- END: Footer-->
	
	<!-- BEGIN: Vendor JS-->
	<script src="<?= $baseUrl; ?>/vendors/js/material-vendors.min.js"></script>
	<!-- BEGIN Vendor JS-->

	<!-- BEGIN: Page Vendor JS-->
	<!----Jquery---->
	
	<script src="<?= $baseUrl; ?>/vendors/js/extensions/jquery.steps.min.js"></script>
	<script src="<?= $baseUrl; ?>/vendors/js/pickers/dateTime/moment-with-locales.min.js"></script>
	<script src="<?= $baseUrl; ?>/vendors/js/pickers/daterange/daterangepicker.js"></script>
	<script src="<?= $baseUrl; ?>/vendors/js/forms/validation/jquery.validate.min.js"></script>
	<script src="<?= $baseUrl; ?>/vendors/js/tables/jquery.dataTables.min.js"></script>
	<script src="<?= $baseUrl; ?>/vendors/js/tables/datatable/dataTables.bootstrap4.min.js"></script>
	<!-- END: Page Vendor JS-->

	<!-- BEGIN: Theme JS-->
	<script src="<?= $baseUrl; ?>/js/core/app-menu.js"></script>
	<script src="<?= $baseUrl; ?>/js/core/app.js"></script>
	
	<!-- BEGIN: Page JS-->
	<script src="<?= $baseUrl; ?>/js/scripts/pages/material-app.js"></script>
	<?php $this->registerJsFile($baseUrl.'/datatables/datatables.min.js', ['depends' => [yii\web\JqueryAsset::className()]]); ?>
	<!-- END: Page JS-->
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>