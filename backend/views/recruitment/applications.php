<!-- BEGIN: Content-->
<?php
 use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - My Applications';
 $baseUrl = Yii::$app->request->baseUrl;


/*print '<pre>';
print_r($applications);

exit();*/


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

                          
                            <div class="card-header">
                                <h4 class="card-title">My Job Applications</h4>
                                
                            </div>


                            <div class="card-content collapse show">
                                <div class="card-body">
                                	

                                 <?= GridView::widget([
                'dataProvider' => $provider,
                'showOnEmpty' => false, 
                'columns' => [
                    [
                      'class' => 'yii\grid\SerialColumn',
                      'headerOptions' => ['width'=>'5%'],
                  ],            
                  [
                    'attribute'=>'ApplicationNo',
                    'headerOptions' => ['width'=>'5%'],

                  ],          
                    //'ApplicantID',
                    [ 'attribute' => 'JobDescription', 'format' => 'raw', 'value' => function ($model) { return Html::a($model['JobDescription'], [ 'recruitment/jobapplication', 'jobapplicantid' => $model['ApplicationNo'] ], ['target' => '_blank']); }, ],
           
            

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{download} {view} {update} {delete}',
                'headerOptions' => ['width'=>'5%'],
                'buttons' => [
                    'view' => function ($url,$model) {
                        return Html::a(
                            '<span class="btn btn-primary"><i class="ft-eye">&nbsp;</i>View</span>',
                            ['recruitment/jobapplication', 'jobapplicantid' => $model['ApplicationNo']], 
                            [
                                'title' => 'Download',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
          ],

        ],
         'tableOptions' => [
            'class' => 'table table-striped table-bordered responsive',
            'data-role' => 'datatable',
            'data-searching' => 'true',
            'data-paging' => 'true',
            'data-ordering' => 'false',
            'data-info' => 'false'
        ],
    ]); ?>

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