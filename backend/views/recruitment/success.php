<?php
 use yii\helpers\Html;
$this->title = 'Applicantion - Success';
$baseUrl = Yii::$app->request->baseUrl;
/*print '<pre>';
print_r($profile); exit;*/
?>

<div class="app-content content">
	<div class="content-wrapper">
        <div class="content-body">
        	<section id="ticket-detail">
			 	 <div class="row">
			 	 	<div class="col-lg-12 col-md-12">
			 	 		 <div class="card activated">
			                                <div class="card-header">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8">

                                                             <h2 class="card-title" style="margin-top: 10px !important">
                                                                    <i class="ft-lock"></i> Application Success 
                                                             </h2>

                                                    </div>

                                                    <div class="col-lg-4 col-md-4">
                                                            <?php
                                                               // if(Yii::$app->request->get('action')){
                                                                   echo Html::a('<i class="ft-file"></i> Done',['applications'],['class'=>'btn btn-primary pull-right']); 
                                                               // }
                                                 
                                                                ?>
                                                    </div>
                                                </div>
			                                   

                                                
			                                </div>			                                
                        </div>
                        <div class="card">
                        	<div class="card-body">
                        		
	                        	<div class="bs-callout-success mt-1">
                                                        <div class="media align-items-stretch">
                                                            <div class="media-left media-middle bg-success p-2 pt-3">
                                                                <i class="ft-eye white"></i>
                                                            </div>
                                                            <div class="media-body p-1">
                                                                <strong>Congratulations!</strong>
                                                                <p>Your application has been submitted successfully.</p>
                                                            </div>
                                                        </div>
                                </div>

                			</div>
                        </div><!--end card2 -->

                       




                        

                        








			 	 	</div>

			 	 	

			 	 </div>
			 </section>
        </div>
    </div>
</div>
 