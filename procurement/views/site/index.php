<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
$baseUrl = Yii::$app->request->baseUrl;

?>
   <!-- BEGIN: Content-->
	<div class="app-content content">
        <div class="content-header row">
            <div class="content-header-dark col-12">
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
                   <div class="row justify-content-md-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-body"> 

                                        <div class="card-body card-dashboard dataTables_wrapper dt-bootstrap">
                                            <p class="card-text">We request for the EOI as listed Below
                                            </p>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered sourced-data">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Title</th>
                                                            <th>Requisition_No</th>
                                                            <th>Procurement_Plan_No</th>
                                                            <th>Procurement_Method</th> 
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>No</td>
                                                            <td>Title</td>
                                                            <td>Requisition_No</td>
                                                            <td>Procurement_Plan_No</td>
                                                            <td>Procurement_Method</td> 
                                                        </tr>
                                                    </tbody>                                                    
                                                    <tfoot>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Title</th>
                                                            <th>Requisition_No</th>
                                                            <th>Procurement_Plan_No</th>
                                                            <th>Procurement_Method</th> 
                                                        </tr>
                                                    </tfoot>
                                                </table>
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