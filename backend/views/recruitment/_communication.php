<!-- BEGIN: Content-->
<?php
 use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Communication Information';
 $baseUrl = Yii::$app->request->baseUrl;
  ?>
    
<div class="app-content content">


 <?= $this->render('_steps'); ?>



    <div class="content-wrapper">
        <div class="content-body">
            <!-- Form wizard with number tabs section start -->
            <section id="number-tabs">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Communication Information</h4>
                                
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

                                    <form method="post" action="./communication" class="" >
                                        
                                         <h6>Communication</h6>
                                        <fieldset>
                                          <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" />
                                            <input type="text" class="form-control" name="Applicant_No" readonly value="<?= Yii::$app->user->identity->ApplicantId ?>" />
                                        <input name="Key" type="hidden" value="<?= $session->get('Key'); ?>">
                                        <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="code">Home Phone Number <span style="color:red">*</span></label>
                                                       <input type="text" class="form-control" name="Home_Phone_Number">
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <label for="Ext">Ext. <span style="color:red">*</span></label>
                                                       <input type="text" placeholder="e.g +256" class="form-control" name="Ext">
                                                    </div>

                                                    <div class="col-sm-4">
                                                         <label for="Cellular_Phone_Number">Cell Phone Number
                                                         <input  type="text" name="Cellular_Phone_Number" class="form-control">
                                                    </div>
                                            </div>



                                            <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="Postal_Address">Postal Address </label>
                                                        <input type="text" name="Postal_Address" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6">
                                                         <label for="Post_Code">Post Code <span style="color:red">*</span></label>
                                                         <input type="text" name="Post_Code" class="form-control">
                                                    </div>
                                            </div>


                                             <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="Ethnic_Origin">Email Address <span style="color:red">*</span></label>
                                                        <input type="text" name="E_Mail" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6">
                                                         <label for="Residential_Address">Residential Address <span style="color:red">*</span></label>
                                                         <input type="text" name="Residential_Address" class="form-control" required="">
                                                    </div>
                                            </div>

                                     <div class="form-group">
                                        <input type="submit" class="btn btn-success" value="Save and Continue">

                                        <a href="<?= $baseUrl ?>./qualifications" class="btn btn-primary">Next</a>
                                     </div>


                                     </fieldset>
                                        
                                        <h6>Qualifications</h6>
                                        <fieldset></fieldset>

                                        <h6>Experience</h6>
                                        <fieldset></fieldset>

                                        <h6>Referees</h6>
                                        <fieldset></fieldset>

                                        <h6>Attachments</h6>
                                        <fieldset></fieldset>

                                        <h6>Comments</h6>
                                        <fieldset></fieldset>

                                    </form>
                                </div><!--end card body-->
                             </div><!--end card content-->
                         </div><!--end card-->
                     </div><!--end col-12-->
                 </div><!--end row-->
            </section>
        </div><!--end content body-->
    </div><!--end content body-->
</div><!--end app content-->

