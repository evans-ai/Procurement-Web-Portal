<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$baseUrl = Yii::$app->request->baseUrl;

?>
   <!-- BEGIN: Content-->
	<div class="app-content content">
        <div class="content-header row">
            <div class="content-header-dark bg-img col-12">
                <div class="row">
                    <div class="content-header-left col-md-9 col-12 mb-2">
                        <h3 class="content-header-title white">Procurement</h3>
                        <div class="row breadcrumbs-top">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active">Home
                                    </li>                                    
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right col-md-3 col-12">
                        <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
                            <button class="btn btn-primary round dropdown-toggle dropdown-menu-right box-shadow-2 px-2" id="btnGroupDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            <div class="dropdown-menu"><a class="dropdown-item" href="component-alerts.html"> Alerts</a><a class="dropdown-item" href="material-component-cards.html"> Cards</a><a class="dropdown-item" href="component-progress.html"> Progress</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="register-with-bg-image.html"> Register</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content-body">
                <!-- Form wizard with number tabs section start -->
                <!-- Custom Listgroups start -->
                <section id="custom-listgroup">                    
                    <div class="row match-height">
                        <div class="col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-uppercase">Profile Checklist</h4>                              
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">                                        
                                        <div class="list-group">
                                            <a href="<?= $baseUrl; ?>/supplierdata/create" class="list-group-item list-group-item-success">Business Information</a>
                                            <a href="#" class="list-group-item list-group-item-action list-group-item-info">Company Personnel</a>
                                            <a href="#" class="list-group-item list-group-item-action list-group-item-light">Business Financials</a>
                                            <a href="#" class="list-group-item list-group-item-action list-group-item-danger">Experience</a>
                                            <a href="#" class="list-group-item list-group-item-action list-group-item-light">Litigation History</a>
                                            <a href="#" class="list-group-item list-group-item-action list-group-item-success">Documentation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </section>                
                <!-- Form wizard with vertical tabs section end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->