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
        <div class="col-lg-12 col-md-12">
            <div class="card">
                
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="card-header">
                            
                            <div class="card-title">
        
                            </div>
                        </div>                        
                        <form class="form form-horizontal">
                            <div class="form-body">
                                <h4 class="form-section"><i class="ft-layers"></i>RESPONSES</h4>
                                <div class="form-group-row">
                                    <?= GridView::widget([
                                    'dataProvider' => $Provider,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn',
                                        'contentOptions' => ['width'=>'5%'],
                                        ],
                                        ['attribute'=>'Status',
                                        'contentOptions' => ['width'=>'25%'],
                                        ],     
                                        ['attribute'=>'Response_Id',
                                        'contentOptions' => ['width'=>'30%'],
                                        ],  
                                         ['attribute'=>'Date_Submitted',
                                        'contentOptions' => ['width'=>'20%'],
                                        'format'=>['date', 'php:d/m/Y'], 
                                        ],                                   
                                        ['class' => 'yii\grid\ActionColumn',
                                        'headerOptions' => ['width' => '10%', 'style'=>'color:black; text-align:center'],
                                        'contentOptions' => ['style' => 'text-align:center'],
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model)
                                            {
                                                 //$baseUrl = Yii::$app->request->baseUrl;
                                                    return Html::a(
                                                    '<span></i> View</span>',
                                                  ['responses/viewdoc' ], 
                                                  [
                                                      'title' => 'View',
                                                      'class'=>'btn btn-info',
                                                                                          
                                            ]);
                                                
                                            },3
                                        ],
                                    ],
                                    ],
                                ]); 

                                ?> 
                                  <div data-repeater-item>
                                                <div class="form-group mb-1 col-sm-12 col-md-4">
                                                    
                                                </div>
                                                <div class="form-group mb-1 col-sm-12 col-md-2">
                                                                       
                                                </div>
                                                <div class="form-group mb-1 col-sm-12 col-md-2">
                                                                                 
                                                </div>  
                                                <div class="form-group mb-1 col-sm-12 col-md-3">
                                                    
                                                    <label id="projectinput8" class="file center-block">
                                                            
                                                            <span class="file-custom"></span>
                                                    </label>

                                                                                         
                                                </div>                                                       
                                            </div>                               
                                        </div>
                                                                                   
                                </div>    
                            </div>
                        </form>                       
                    </div>
                    <div class="card-body">
                    
                        <form class="form form-horizontal">
                            <div class="form-body">
                                <h4 class="form-section"><i class="ft-layers"></i> FINANCIAL RESPONSE</h4>
                                <div class="form-group-row">
                                    <?= GridView::widget([
                                    'dataProvider' => $Provider,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn',
                                        'contentOptions' => ['width'=>'5%'],
                                        ],
                                        ['attribute'=>'',
                                        'contentOptions' => ['width'=>'25%'],
                                        ],     
                                        ['attribute'=>'Response_Id',
                                        'contentOptions' => ['width'=>'30%'],
                                        ],  
                                         ['attribute'=>'Date_Submitted',
                                        'contentOptions' => ['width'=>'20%'],
                                        'format'=>['date', 'php:d/m/Y'], 
                                        ],                                   
                                        ['class' => 'yii\grid\ActionColumn',
                                        'headerOptions' => ['width' => '10%', 'style'=>'color:black; text-align:center'],
                                        'contentOptions' => ['style' => 'text-align:center'],
                                        'template' => '{view}',
                                        'buttons' => [
                                            'view' => function ($url, $model)
                                            {
                                                 //$baseUrl = Yii::$app->request->baseUrl;
                                                    return Html::a(
                                                    '<span></i> View</span>',
                                                  ['responses/viewdoc' ], 
                                                  [
                                                      'title' => 'View',
                                                      'class'=>'btn btn-info',
                                                                                          
                                            ]);
                                                
                                            },3
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