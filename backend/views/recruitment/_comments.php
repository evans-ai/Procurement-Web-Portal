<!-- BEGIN: Content-->
<?php
 use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Self Brief';
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
                                <h4 class="card-title">Brief Comments About Yourself</h4>
                                
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

                                  <form method="post" action="./comments" class="">
                                        
                                           <h6>Comments (About Yourself)</h6>
                                           <fieldset>
                                             <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" />
                                            <input type="hidden" class="form-control" name="Applicant_No" readonly value="<?= Yii::$app->user->identity->ApplicantId ?>" />
                                            <input type="hidden" name="Date" value="<?= date('Y-m-d') ?>">

                                             <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <label for="From_Date">Brief Comments (250 characters max.) <span style="color:red">*</span></label>
                                                        <textarea maxlength="250" cols="5" rows="4" class="form-control" name="Views_Comments"></textarea>
                                                    </div>
                                                    
                                            </div>

                                     <div class="form-group">
                                        <input type="submit" class="btn btn-success" value="Save">

                                        <!--<a href="<?= $baseUrl ?>./attachments" class="btn btn-primary">Next</a>-->
                                     </div>

                                           </fieldset>
                                            


                                    </form>

                                      <?php
                                          $comments = Yii::$app->recruitment->mycomments();

                                         /* print '<pre>';
                                          print_r($comments);*/
                                    ?>

                                      <table class="table table-column">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Comment </th>
                                  <th>Date</th> 
                                  <th>Delete Action</th>                                                              
                                </tr>
                              </thead>
                              <tbody>
                                <?php $counter = 0;
                                                if(is_array($comments) && count($comments)){
                                                      foreach($comments as $c){
                                                       ++$counter;

                                                       echo '<tr>
                                                            <td>'.$counter.'</td>
                                                            <td>'.$c->Views_Comments.'</td>
                                                            <td>'.$c->Date.'</td>
                                                            <th>'.Html::a('Delete Comments',['deletecomment','serial'=> $c->Key],[
  'class'=>'btn btn-danger','title'=>'Remove Comment'
  ]).'</th>
                                                            
                                                      </tr>';

                                                 }
                                                }else{
                                                      print '<tr>
                                                            <td colspan="3">You have not given a brief comment about yourself.</td>
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