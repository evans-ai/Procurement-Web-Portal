<!-- BEGIN: Content-->
<?php
 use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Document Attachments';
 $baseUrl = Yii::$app->request->baseUrl;
 $applicant_no = $session->get('Applicantid');
  ?>
    
<div class="app-content content">



   <?php $this->render('_steps'); ?>


    <div class="content-wrapper">
        <div class="content-body">
            <!-- Form wizard with number tabs section start -->
            <section id="number-tabs">
                <div class="row">
                    <div class="col-12">
                              <div class="card">
                            
                                <div class="card-header">
                                  <h2 class="header-title">Recruitment Assistance.</h2>
                                </div>
                                      <div class="bs-callout-danger callout-transparent callout-bordered mt-1">
                                            <div class="media align-items-stretch">
                                                <div class="media-left media-middle bg-danger position-relative callout-arrow-left p-2">
                                                    <i class="la la-hand-o-right white font-medium-5 mt-1"></i>
                                                </div>
                                                <div class="media-body p-1">
                                                    <strong>Our Contacts.</strong>
                                                    <p>In case of any difficulty(ies) reach us via: </p>
                                                    <address>
                                                      Rahimtulla Tower, 13th Floor, Upper Hill Road, Opp UK High Commission, Nairobi.<br>
                                                      P.O. Box 57733 - 00200, Nairobi Kenya.<br>
                                                      Phone: +254 (20) 2809000, Fax: +254 (20) 2710330.<br>
                                                      Toll Free No: 0800720300 (Safaricom Network)<br>
                                                      Email: info@rba.go.ke<br>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>



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