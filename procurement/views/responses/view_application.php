<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Expression Of Interest';
$baseUrl = Yii::$app->request->baseUrl;



?>
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">
    <div class="row match-height">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-y-warning">
                <!-- <div class="card-header">
                    <h5 class="card-tile text-white">HEADER DETAILS</h5>
                </div> -->
                <DIV class="card-content mx-2">
                                    
                </DIV>
            </div>
        </div>
    </div>
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                 <div class="card-header">
                    <h4 class="card-title text-uppercase">RESPONSE NO: <?=  isset($DataArray[0]['Response_No'])?$DataArray[0]['Response_No'] :'Not Set' ?></h4>                    
                </div> 
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form form-horizontal">
                            <div class="form-body">
                                <h4 class="form-section"><i class="ft-layers"></i> Application Attachments</h4>
                                <div class="form-group-row">
                                    <?= GridView::widget([
                                    'dataProvider' => $Provider,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        'Description',                                        
                                        ['class' => 'yii\grid\ActionColumn',
                                        'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                        'contentOptions' => ['style' => 'text-align:center'],
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model)
                                            {
                                                $baseUrl = Yii::$app->request->baseUrl;
                                                return Html::a('<span class="btn btn-primary btn-sm"> View</span>', $baseUrl.'/responses/viewdocs?id='.'&myService=preq', [
                                                            'title' => Yii::t('app', 'View'),
                                                                                          
                                                            ]);

                                                
                                            },
                                        ],
                                    ],
                                    ],  
                                ]); 

                                ?> 

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