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
$session = Yii::$app->session;
$identity = Yii::$app->user->identity;



AppAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="@francnjamb">
    <title>RBA - Recruitment Login</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $baseUrl; ?>/images/rba-logo.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/material-vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/vendors/css/forms/icheck/custom.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/material.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/components.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/material-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/material-colors.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/core/menu/menu-types/material-vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/pages/login-register.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= $baseUrl; ?>/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->






<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu material-vertical-layout material-layout 1-column  bg-full-screen-image blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">



         <!-- BEGIN: Header-->
    <!-- <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-dark navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item"><a class="navbar-brand" href="index.html"><img class="brand-logo" alt="modern admin logo" src="<?= $baseUrl; ?>/images/logo/logo.png">
                            <h3 class="brand-text">Modern Admin</h3>
                        </a></li>
                    <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container">
                <div class="collapse navbar-collapse justify-content-end" id="navbar-mobile">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link mr-2 nav-link-label" href="index.html"><i class="ficon ft-arrow-left"></i></a></li>
                        <li class="dropdown nav-item"><a class="nav-link mr-2 nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-settings"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav> -->
    <!-- END: Header-->



    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-header row">
        </div>
        <div class="content-wrapper">
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                                <div class="card-header border-0">
                                    <div class="card-title text-center">
                                        <img src="<?= $baseUrl; ?>/images/logo/rba-small.png" alt="branding logo">
                                    </div>
                                   
                                </div>
                                <div class="card-content">
                                    
                                    <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1"><span> Use Account Details</span></p>
                                    <div class="card-body">
                                        <?= $content ?>
                                    </div>
                                    <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1"><span>Don't have an Account ?</span></p>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="<?= $baseUrl?>/site/signup" class="btn btn-outline-danger btn-block"><i class="ft-user"></i> Register</a>
                                            </div>

                                             <div class="col-md-6">
                                                <a href="<?= $baseUrl?>/site/request-password-reset" class="btn btn-outline-danger btn-block "><i class="ft-user"></i> Forgot Password</a>
                                            </div>
                                        </div>
                                        


                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="<?= $baseUrl; ?>/vendors/js/material-vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?= $baseUrl; ?>/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
    <script src="<?= $baseUrl; ?>/vendors/js/forms/icheck/icheck.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?= $baseUrl; ?>/js/core/app-menu.js"></script>
    <script src="<?= $baseUrl; ?>/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="<?= $baseUrl; ?>/js/scripts/pages/material-app.js"></script>
    <script src="<?= $baseUrl; ?>/js/scripts/forms/form-login-register.js"></script>
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>
<?php $this->endPage(); ?>