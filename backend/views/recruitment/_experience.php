<!-- BEGIN: Content-->
<?php
 use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Relevant Experience';
 $baseUrl = Yii::$app->request->baseUrl;
 $applicant_no = $session->get('Applicantid');
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
                                <h4 class="card-title">Experience</h4>
                                
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

                                  <form method="post" action="./experience" class="">
                                         <h6>Experience</h6>
                                         <fieldset>
                                           <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" />
                                            <input type="hidden" class="form-control" name="Applicant_No" readonly value="<?= Yii::$app->user->identity->ApplicantId ?>" />

                                             <div class="form-group row">
                                                    <div class="col-sm-2">
                                                        <label for="From_Date">From Date <span style="color:red">*</span></label>
                                                        <input type="date" name="From_Date" class="form-control">
                                                    </div>

                                                    <div class="col-sm-2">
                                                         <label for="To_Date">To Date <span style="color:red">*</span></label>
                                                         <input type="date" name="To_Date" class="form-control" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                         <label for="Responsibility">Job Title <span style="color:red">*</span></label>
                                                         <input type="text" name="Responsibility" class="form-control" required="">
                                                    </div>

                                                    <div class="col-sm-2">
                                                         <label for="Institution_Company">Institution_Company <span style="color:red">*</span></label>
                                                         <input type="text" name="Institution_Company" class="form-control" required="">
                                                    </div>

                                                    <div class="col-sm-3">
                                                         <label for="Salary">Salary <span style="color:red">*</span></label>
                                                         <input type="number" name="Salary" class="form-control" required="">
                                                    </div>
                                            </div>

                                     <div class="form-group">
                                        <input type="submit" class="btn btn-success" value="Save">

                                        <a href="<?= $baseUrl ?>./referee" class="btn btn-primary">Next</a>
                                     </div>


                                         </fieldset>
                                         

                                            

                                    </form>
                                  <hr/><!--Show user qualifications-->

                                  <h4>My Saved Experiences</h4>

                                  <?php
                                          $experience = Yii::$app->recruitment->myexperinces();

                                         /* print '<pre>';
                                          print_r($experience);*/
                                    ?>

                                  <table class="table table-column">
                              <thead>
                                <tr>
                                  <th>Institution Company </th>
                                  <th>Responsibility</th>
                                  <th>To Date</th>
                                  <th>From Date</th>
                                  <th>Salary</th>
                                  <th>Delete Action</th>
                                  
                                </tr>
                              </thead>
                              <tbody>
                                <?php

                        if(is_array($experience) && count($experience)){
                                                foreach($experience as $x){
                                                      print '<tr>
                                                            <td>'.$x->Institution_Company.'</td>
                                                            <td>'.$x->Responsibility.'</td> 
                                                            <td>'.$x->To_Date.'</td>                                                
                                                                  <td>'.$x->From_Date.'</td>                                                          
                                                            <td>'.$x->Salary.'</td>
                                                            <th>'.Html::a('Delete Experience',['deleteexperience','serial'=> $x->Key],[
  'class'=>'btn btn-danger'
  ]).'</th>
                                                      </tr>';
                                                 }
                                                }else{
                                          print '<tr>
                                                <td colspan="5">You have not added any relevant experience yet.</td>
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