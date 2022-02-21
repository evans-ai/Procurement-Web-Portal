<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'RBA Procurement';
$baseUrl = Yii::$app->request->baseUrl;



?>
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-uppercase">Attach the following Documents To Apply</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">

                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                              <div class="alert alert-success alert-dismissable">
                                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                  <h4><i class="icon fa fa-check"></i>Saved!</h4>
                                  <?= Yii::$app->session->getFlash('success') ?>
                              </div>
                        <?php endif; ?>


                        <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                          <div class="alert alert-danger alert-dismissable">
                              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                              <h4><i class="icon fa fa-check"></i>Error!</h4>
                              <?= Yii::$app->session->getFlash('error') ?>
                          </div>
                        <?php endif; ?>

                        <form class="form" action="./submitpreq" method="POST"  enctype="multipart/form-data">
                            <input type="hidden" name="Tender_No" value=<?= sizeof($DataArray)>0?$DataArray[0]['Tender_No']:'' ?>>
                            <div class="repeater-default">
                                <div data-repeater-item>
                                    <div class="form row">
                                        <div class="form-group mb-1 col-sm-12 col-md-2">
                                            #
                                        </div>
                                        <div class="form-group mb-1 col-sm-12 col-md-3">
                                            Description
                                        </div>
                                        <div class="form-group mb-1 col-sm-12 col-md-3">
                                            Document No 
                                        </div>
                                        <div class="form-group mb-1 col-sm-12 col-md-4">
                                            Attachment
                                        </div>                                
                                    </div>

                                </div>
                                <hr>
                                <?php
                                    for($i=0;$i<sizeof($DataArray);$i++)
                                    {
                                      ?>
                                        <div data-repeater-item>
                                            <div class="form row">
                                                <div class="form-group mb-1 col-sm-12 col-md-2">
                                                    <?= $i+1; ?>.
                                                </div>
                                                <div class="form-group mb-1 col-sm-12 col-md-3">
                                                    <?= $DataArray[$i]['Description']; ?>
                                                </div> 
                                                <div class="form-group mb-1 col-sm-12 col-md-3">
                                                    <?php  if($DataArray[$i]['Document_No_Mandatory'])  { ?>
                                                    <input type="text" class="form-control"  name="Document_No[<?= $DataArray[$i]['Requirement_No'] ?>]" required="true">
                                                    <?php  } ?> 
                                                </div> 
                                                <div class="form-group mb-1 col-sm-12 col-md-4">
                                                    <?php  if($DataArray[$i]['Requires_Attachment']) { ?>
                                                    <label id="projectinput8" class="file center-block">
                                                        <input type="file" accept="application/pdf" class="form-control" id="files[]" name="files[<?= $DataArray[$i]['Requirement_No'] ?>]" value= >
                                                        <span class="file-custom"></span>
                                                    </label>
                                                    <?php  } ?> 
                                                </div>                                                       
                                            </div>                               
                                        </div>
                                      <?php
                                    } 
                                ?> 
                                </div> 
                                <div class="form-group overflow-hidden">
                                    <div class="col-12">
                                        <div class="buttons-group float-right">
                                            <button type="submit" class="btn btn-primary">
                                              <i class="la la-check-square-o"></i> Submit
                                            </button>
                                        </div>                                
                                    </div>
                                </div>                                
                        </form>                                                                                
                    </div>
                </div>
            </div>
        </div>                        
    </div>
</section>                
                <!-- Form wizard with vertical tabs section end -->

    <!-- END: Content-->