<?php
use yii\helpers\Html;
use yii\grid\GridView;

?>


<section id="card-bordered-options">
    
    <div class="row">
        <div class="col-12 mt-0">
            <h1 class="text-center"><b>FRC Suppliers Portal</b></h1>
			<hr>
            <p>Below is a listing of the available openings that you may be interested in. You will need to create an acount in order to apply or download documents.</p>
        </div>
    </div>
    <div class="card border-danger">
        <div class="card-header">
            <h4 class="card-title">Open Tenders</h4>
        </div>
        <div class="card-content collapse show">
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $tenderProvider,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'contentOptions' => ['width'=>'5%'],
                        ],
                        [
                            'attribute'=>'No',
                            'contentOptions' => ['width'=>'10%'],
                        ],
                        [
                            'attribute'=>'Title'
                        ],
                        [
                            'attribute'=>'StartDate',
                            'contentOptions' => ['width'=>'12%'],
                            'format'=>['date', 'php:d/m/Y'], 
                        ],
                        [
                            'attribute'=>'EndDate',
                            'contentOptions' => ['width'=>'12%'],
                            'format'=>['date', 'php:d/m/Y'], 
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'template' => '{view}',
                            'buttons' => 
                            [
                                'view' => function ($url, $model){
                                    $baseUrl = Yii::$app->request->baseUrl;
                                    return Html::a('<span class="btn btn-secondary btn-sm"> View</span>', $baseUrl.'/site/view?id='.$model['No'].'&myService=tender', [
                                        'title' => Yii::t('app', 'View'),
                                        'class'=>'gridbtn btn-primary btn-xs',                                  
                                    ]);
                                    },
                            ],
                        ],
                    ],
                ]);?>  
            </div>
        </div>
    </div>
</section>



                                                            
                                                       