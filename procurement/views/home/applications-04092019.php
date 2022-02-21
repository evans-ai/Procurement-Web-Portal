
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = "My Applications";
$baseUrl = Yii::$app->request->baseUrl;
?>
  
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-uppercase">MY APPLICATIONS</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-header">
                        <div class="buttons-group float-right">
                            <?= Html::a('Home',['./supplierdata/profile'],['class' => 'btn btn-danger-rba mr-1', 'name' => 'applications']) ?>                                            
                        </div>
                        <h4 class="card-title text-uppercase">DASHBOARD</h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal">
                              <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" name="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-body">

                                <h4 class="form-section"><i class="ft-user"></i> PREQUALIFICATIONS</h4>
                                <div class="form-group row">
                                    <div class="card-body">
                                         <table class="table table-striped table-bordered zero-configuration">
                                                   <!--    <tbody>
                                                    <tr>
                                                      <th>Document Number</th>
                                                      <th>Category Name</th>
                                                      <th>Start Date</th>
                                                      <th>End Date</th>
                                                      <th>Status</th>
                                                    </tr>
                                                    <?php
                                                     //print('<pre>');
                                            //print_r($preqProvider); exit;
                                                foreach ($preqProvider->allModels as $key => $value) {
                                                // print_r($value['No']); exit;
                                                    echo '<tr><td>'.$value['Document_No'].'</td>';
                                                    echo '<td>'.$value['Category_Name'].'</td>';
                                                    echo '<td>'.$value ['StartDate'].'</td>';
                                                    echo '<td>'.$value ['EndDate'].'</td></tr>';
                                                    echo '<td>'.$value ['Status'].'</td></tr>';

                                                }?>
                                                </tbody>
                                                 </table>  
                                                         -->




                                        <?= GridView::widget([
                                            'dataProvider' => $preqProvider,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                'Document_No',
                                                'Category_Name',
                                                ['attribute'=>'StartDate',
                                                         'contentOptions' => ['width'=>'12%'],
                                                         'format'=>['date', 'php:d/m/Y'], 
                                                ],
                                                ['attribute'=>'EndDate',
                                                 'contentOptions' => ['width'=>'12%'],
                                                 'format'=>['date', 'php:d/m/Y'], 
                                                ],
                                                'Status',
                                               ['class' => 'yii\grid\ActionColumn',
                                                'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                                'contentOptions' => ['style' => 'text-align:center'],
                                                'template' => '{view}',
                                                'buttons' => [
                                                    'view' => function ($url, $model)
                                                    {
                                                        $baseUrl = Yii::$app->request->baseUrl;
                                                         return Html::a('<span class="btn btn-primary"><i class="ft-eye"></i> View</span>', $baseUrl.'/responses/viewapplicationitems?id='.$model['Document_No'].'&myService=preq', [
                                                                    'title' => Yii::t('app', 'View'),                         
                                                                     ]);

                                                        return Html::a('<span class="btn btn-primary btn-sm">View</span>', $baseUrl.'/responses/viewapplicationitems?id='.$model['Document_No'].'&myService=preq', [
                                                                                            'title' => Yii::t('app', 'View'),
                                                                                            'class'=>' btn-primary btn-xs',                                  
                                                                                            ]);
                                                    },
                                                ],
                                            ],
                                            ],
                                        ]); 

                                        ?> 
                                    </div>
                                </div>

                                <h4 class="form-section"><i class="ft-user"></i> APPLIED TENDERS</h4>
                                <div class="form-group row">
                                    <div class="card-body">

                        <table class="table table-striped table-bordered zero-configuration">
                           <tbody>
                                <tr>
                                  <th>Document Number</th>
                                  <th>Title</th>
                                  <th>Start Date</th>
                                  <th>End Date</th>
                                </tr>



                                <?php
                                 //print('<pre>');
                        // print_r($tenderProvider); exit;

                            foreach ($tenderProvider->allModels as $key => $value) {
                            // print_r($value['No']); exit;
                                echo '<tr><td>'.$value['No'].'</td>';
                                echo '<td>'.$value['Title'].'</td>';
                                echo '<td>'.$value ['StartDate'].'</td>';
                                echo '<td>'.$value ['EndDate'].'</td></tr>';
                            }
                          
                                
                                ?>
                            </tbody>
                             </table>  
                                                    



                           




                                        <?php GridView::widget([
                                            'dataProvider' => $tenderProvider,
                                            'filterModel' => $tenderProvider,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                'No',
                                                'Title',
                                                'Date_Openned',
                                                ['attribute'=>'StartDate',
                                                         'contentOptions' => ['width'=>'12%'],
                                                         'format'=>['date', 'php:d/m/Y'], 
                                                        ],
                                                ['attribute'=>'EndDate',
                                                 'contentOptions' => ['width'=>'12%'],
                                                 'format'=>['date', 'php:d/m/Y'], 
                                                ],
                                                ['class' => 'yii\grid\ActionColumn',
                                                'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                                'contentOptions' => ['style' => 'text-align:center'],
                                                'template' => '{view}',
                                                'buttons' => [
                                                    'view' => function ($url, $model)
                                                    {
                                                        $baseUrl = Yii::$app->request->baseUrl;
                                                        return Html::a('<span class="btn btn-primary btn-sm"></i> View</span>', $baseUrl.'/home/view?id='.$model['No'].'&myService=tender', [
                                                                    'title' => Yii::t('app', 'View'),
                                                                    'class'=>'gridbtn btn-primary btn-xs',                                  
                                                                    ]);


                                                    },
                                                ],
                                            ],
                                            ],
                                            ]); ?>
                                    </div>
                                </div>
  
                                <h4 class="form-section"><i class="ft-user"></i> DIRECT PROCUREMENT</h4>
                                <div class="form-group row">
                                    <div class="card-body">
                                        <?= GridView::widget([
                                            'dataProvider' => $dpProvider,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                'No',
                                                'Title',
                                                'Requisition_No',
                                                'Procurement_Plan_No',
                                                ['attribute'=>'StartDate',
                                                         'contentOptions' => ['width'=>'12%'],
                                                         'format'=>['date', 'php:d/m/Y'], 
                                                ],
                                                ['attribute'=>'EndDate',
                                                 'contentOptions' => ['width'=>'12%'],
                                                 'format'=>['date', 'php:d/m/Y'], 
                                                ],
                                                ['class' => 'yii\grid\ActionColumn',
                                                    'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                                    'contentOptions' => ['style' => 'text-align:center'],
                                                    'template' => '{view}',
                                                    'buttons' => [
                                                        'view' => function ($url, $model)
                                                        {
                                                            $baseUrl = Yii::$app->request->baseUrl;
                                                            return Html::a('<span class="btn btn-primary btn-sm">View</span>', $baseUrl.'/home/view?id='.$model['No'].'&myService=dp', [
                                                                        'title' => Yii::t('app', 'View'),
                                                                        'class'=>'gridbtn btn-primary btn-xs',                                  
                                                                        ]);
                                                        },
                                                    ],
                                                ],
                                            ],
                                        ]); 

                                        ?> 
                                    </div>
                                </div>
                            
                                <h4 class="form-section"><i class="ft-user"></i> REQUEST FOR PREQUALIFICATIONS</h4>
                                <div class="form-group row">
                                    <div class="card-body">
                                    <?= GridView::widget([
                                        'dataProvider' => $rfqProvider,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],

                                            ['class' => 'yii\grid\ActionColumn',
                                            'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                            'contentOptions' => ['style' => 'text-align:center'],
                                            'template' => '{view}',
                                            'buttons' => [
                                                'view' => function ($url, $model)
                                                {
                                                    $baseUrl = Yii::$app->request->baseUrl;
                                                    return Html::a('<span class="btn btn-primary btn-sm">View</span>', $baseUrl.'/home/view?id='.$model['No'].'&myService=rfq', [
                                                                'title' => Yii::t('app', 'View'),
                                                                'class'=>'gridbtn btn-primary btn-xs',                                  
                                                                ]);
                                                },
                                            ],
                                        ],
                                        ],
                                        ]); ?>
                                    </div>
                                </div>                            
                                <h4 class="form-section"><i class="ft-user"></i> REQUEST FOR PROPOSAL</h4>
                                <div class="form-group row">
                                    <div class="card-body">
                                        <?= GridView::widget([
                                            'dataProvider' => $rfpProvider,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                'No',
                                                'Title',
                                                'Requisition_No',
                                                'Procurement_Plan_No',
                                                ['attribute'=>'StartDate',
                                                         'contentOptions' => ['width'=>'12%'],
                                                         'format'=>['date', 'php:d/m/Y'], 
                                                ],
                                                ['attribute'=>'EndDate',
                                                 'contentOptions' => ['width'=>'12%'],
                                                 'format'=>['date', 'php:d/m/Y'], 
                                                ],
                                                ['class' => 'yii\grid\ActionColumn',
                                                'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                                'contentOptions' => ['style' => 'text-align:center'],
                                                'template' => '{view}',
                                                'buttons' => [
                                                    'view' => function ($url, $model)
                                                    {
                                                        $baseUrl = Yii::$app->request->baseUrl;
                                                        return Html::a('<span class="btn btn-primary btn-sm">View</span>', $baseUrl.'/home/view?id='.$model['No'].'&myService=rfp', [
                                                                    'title' => Yii::t('app', 'View'),
                                                                    'class'=>'gridbtn btn-primary btn-xs',                                  
                                                                    ]);
                                                    },
                                                ],
                                            ],
                                            ],
                                        ]); ?>
                                    </div>
                                </div>

                                <h4 class="form-section"><i class="ft-user"></i> EXPRESSION OF INTEREST</h4>
                                <div class="form-group row">
                                    <div class="card-body">
                                    <?= GridView::widget([
                                        'dataProvider' => $EoiProvider,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
                                            'No',
                                            'Title',
                                            'Requisition_No',
                                            'Procurement_Plan_No',
                                            ['attribute'=>'StartDate',
                                                     'contentOptions' => ['width'=>'12%'],
                                                     'format'=>['date', 'php:d/m/Y'], 
                                            ],
                                            ['attribute'=>'EndDate',
                                             'contentOptions' => ['width'=>'12%'],
                                             'format'=>['date', 'php:d/m/Y'], 
                                            ],
                                            ['class' => 'yii\grid\ActionColumn',
                                            'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                            'contentOptions' => ['style' => 'text-align:center'],
                                            'template' => '{view}',
                                            'buttons' => 
                                            [
                                                'view' => function ($url, $model)
                                                {
                                                    $baseUrl = Yii::$app->request->baseUrl;
                                                    return Html::a('<span class="btn btn-primary btn-sm">View</span>', $baseUrl.'/home/view?id='.$model['No'].'&myService=eoi', [
                                                                'title' => Yii::t('app', 'View'),
                                                                'class'=>'gridbtn btn-primary btn-xs',                                  
                                                                ]);
                                                },
                                            ],
                                        ],                                                    
                                        ],
                                        ]); 
                                    ?>
                                    </div>
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