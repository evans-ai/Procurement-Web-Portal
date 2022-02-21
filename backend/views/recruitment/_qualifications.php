<!-- BEGIN: Content-->
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Qualifications';
 $baseUrl = Yii::$app->request->baseUrl;
 $applicant_no = $session->get('Applicantid');

 /*print '<pre>';
 print_r($grades); exit;*/

 /*foreach($grades as $c){
  if(!isset($c->$GradeID)){
  print $c->CertificateID.'<br>';
}
 }
 exit;*/
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
                                <h4 class="card-title">Qualifications</h4>
                                
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


                                  <!-- <form method="post" action="./qualifications" class="icons-tab-steps wizard-circle"> -->

                                    
                                      <?php $form = ActiveForm::begin([
                                        'id' => 'login-form',
                                        'action' => './qualifications',
                                         'options' => ['class'=>'']
                                      ]); ?>

                                      <h6>Qualifications</h6>
                                    <fieldset>
                                      <div class="form-group row">                                                   
                                                        
                                                    <!-- <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" /> -->

                                                    <?= $form->field($modelqualification, 'Applicant_No')->textInput([
                                                        'type'=>'hidden',
                                                        'class'=> 'ApplicantId form-control',
                                                        'value'=> Yii::$app->user->identity->ApplicantId
                                                    ])->label(false) ?>







                                                      <div class="row col-md-12">
                                                         
                                                <div class="col-md-4">
                                                      <?= $form->field($modelqualification, 'Education_Level_Id')->dropDownList(ArrayHelper::map($levels,'EducationLevelID','EducationLevelName'),
                                                           [
                                                            'prompt'=>'Select Level',
                                                            'onChange'=>'
                                                                $.post("../recruitment/academiccerts/?educationlevelid="+$(this).val(),function(msg)
                                                                {
                                                                  $("select#qualification-course_id").html(msg);
                                                                })
                                                            '
                                                         ]);
                                                        ?>

                                                    </div>

                                                    <div class="col-md-4">
                                                        

                                                       <?= $form->field($modelqualification, 'Course_Id')->dropDownList(ArrayHelper::map($certs,'CertificateID','CertificateName'), 
                                                       [
                                                        'prompt'=>'Select Level',
                                                        'onChange'=>'
                                                                $.post("../recruitment/academicgrades/?certificateid="+$(this).val(),function(msg){
                                                                    $("select#qualification-grade_id").html(msg);
                                                                  })
                                                            '
                                                     ]); ?>


                                                    </div>

                                                    <div class="col-md-4">
                                                        


                                                       <?= $form->field($modelqualification, 'Grade_Id')->dropDownList(ArrayHelper::map($grades,'GradeID','GradeName'), ['prompt'=>'Select Grade']); ?>

                                                    </div>




                                                          
                                                        
                                                      </div>

                                                     <div class="row col-md-12">
                                                          <div class="col-md-4">
                                                       

                                                         <?= $form->field($modelqualification, 'From_Date')->textInput(['type'=>'date']) ?>

                                                    </div>

                                                    <div class="col-md-4">
                                                        

                                                        <?= $form->field($modelqualification, 'To_Date')->textInput(['type'=>'date']) ?>

                                                    </div>


                                                    
                                            



                                            <div class="col-md-4">
                                                    

                                                    <?= $form->field($modelqualification, 'Institution_Company')->textInput(['type'=>'text'])->label('Institution') ?>
                                                    
                                            </div>
                                                      </div>
                                                    
                                                    


                                            <div class="form-group">
                          <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'name' => 'submit-qualification']) ?>

                          <?= Html::a('Next',['./recruitment/experience'],['class' => 'btn btn-primary', 'name' => 'next-button']) ?>
                        </div>

                                    



                                    </fieldset>
                               
                                       
                                                
                                    <?php ActiveForm::end(); ?>

                                      <hr/><!--Show user qualifications-->

                                      <h4>My Saved Qualifications</h4>
                                      <?php
                                          $qualifications = Yii::$app->recruitment->myqualifications();

                                          /*print '<pre>';
                                          print_r($qualifications);*/
                                      ?>



                                      <!---user qualification display------>

                            <table class="table table-column table-responsive">
                              <thead>
                                <tr>
                                  <th>Education Level </th>
                                  <th>Course Name</th>
                                  <th>Grade Name</th>
                                  <th>From Date</th>
                                  <th>To Date</th>
                                  <th>Institution Company</th>
                                  <th>Delete Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                             if(is_array($qualifications) && count($qualifications)){

                                                
                                  foreach($qualifications as $q){

                                                       echo '<tr>
                                                            <td>'.$q->Education_Level_Name.'</td>
                                                            <td>'.$q->Course_Name.'</td>
                                                            <td>'.$q->Grade_Name.'</td>
                                                            <td>'.$q->From_Date.'</td>
                                                            <td>'.$q->To_Date.'</td>
                                                            <td>'.$q->Institution_Company.'</td>
                                                            <td>'.Html::a('Delete',['deletequalification','serial'=> $q->Key],['class'=>'btn btn-danger']).'</td>
                                                      </tr>';
                                                      

                                                 }

                                }else{
                                          print '<tr>
                                                <td colspan="6">You have not added any Qualifications yet.</td>
                                          </tr>';
                                     }  
                                ?>
                              </tbody>
                            </table>

                                      <!--end display of user qualifications ---->




                                </div><!--end card body-->
                             </div><!--end card content-->
                         </div><!--end card-->
                     </div><!--end col-12-->
                 </div><!--end row-->
            </section>
        </div><!--end content body-->
    </div><!--end content body-->
</div><!--end app content-->

