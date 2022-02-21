<!-- BEGIN: Content-->
<?php
 use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Referees';
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
                                <h4 class="card-title">Referees</h4>
                                
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

                                  <form method="post" action="./referee" class="">
                                        
                                        <h6>Referees</h6>
                                        <fieldset>
                                            <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" />
                                            <input type="hidden" class="form-control" name="No" readonly value="<?= Yii::$app->user->identity->ApplicantId ?>" />

                                             <div class="form-group row">
                                                    <div class="col-sm-3">
                                                        <label for="Names">Name <span style="color:red">*</span></label>
                                                        <input type="text" name="Names" class="form-control">
                                                    </div>

                                                    <div class="col-sm-3">
                                                         <label for="Designation">Designation <span style="color:red">*</span></label>
                                                         <input type="text" name="Designation" class="form-control" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                         <label for="Company">Company <span style="color:red">*</span></label>
                                                         <input type="text" name="Company" class="form-control" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                         <label for="Address">Address <span style="color:red">*</span></label>
                                                         <input type="text" name="Address" class="form-control" required="">
                                                    </div>

                                                    
                                            </div>
                                             <div class="form-group row">

                                                    <div class="col-sm-4">
                                                         <label for="Company">Telephone No <span style="color:red">*</span></label>
                                                         <input type="text" name="Telephone_No" class="form-control" required="">
                                                    </div>

                                                     <div class="col-sm-4">
                                                         <label for="E_Mail">E Mail <span style="color:red">*</span></label>
                                                         <input type="text" name="E_Mail" class="form-control" required="">
                                                    </div>

                                                     <div class="col-sm-4">
                                                         <label for="Notes">Notes <span style="color:red">*</span></label>
                                                         <textarea name="Notes" class="form-control" required=""></textarea>
                                                    </div>

                                             </div>

                                      <div class="form-group">
                                        <input type="submit" class="btn btn-success" value="Save">

                                        <a href="<?= $baseUrl ?>./attachments" class="btn btn-primary">Next</a>
                                     </div>


                                       
                                            
                                        
                                    </form>

                                     <hr/><!--Show user qualifications-->

                                  <h4>My Saved Referees</h4>

                                        <?php
                                          $referees = Yii::$app->recruitment->myreferees();

                                          /*print '<pre>';
                                          print_r($referees);*/
                                    ?>


                                    <table class="table table-column">
                                    <thead>
                                        <tr>
                                            <th>Names </th>
                                            <th>Designation</th>
                                            <th>Company</th>
                                            <th>Telephone No</th>
                                            <th>E Mail</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php

                                    if(is_array($referees) && count($referees)){

                                                       foreach($referees as $ref){
                                                       echo '<tr>
                                                            <td>'.$ref->Names.'</td>
                                                            <td>'.$ref->Designation.'</td> 
                                                            <td>'.$ref->Company.'</td>                                                    
                                                            <td>'.$ref->Telephone_No.'</td>                                                           
                                                            <td>'.$ref->E_Mail.'</td>

                                                            <th>'.Html::a('Delete Referee',['deletereferee','serial'=> $ref->Key],[
  'class'=>'btn btn-danger'
  ]).'</th>

                                                      </tr>';
                                                 }

                                     }else{
                                          print '<tr>
                                                <td colspan="5">You have not attached any relevant referees yet.</td>
                                          </tr>';
                                     }
                                         

                                        ?>        
                                            

                                        
                                    </tbody>
                                </table>






                                </div><!--end card body-->
                             </div><!--end card content-->
                         </div><!--end card-->
                     </div><!--end col-12-->
                 </div><!--end row-->
            </section>
        </div><!--end content body-->
    </div><!--end content body-->
</div><!--end app content-->

<!-- <li role="tab" class="first current" aria-disabled="false" aria-selected="true"><a id="steps-uid-0-t-0" href="#steps-uid-0-h-0" aria-controls="steps-uid-0-p-0"><span class="current-info audible">current step: </span><span class="step">1</span> Step 1</a></li> -->