
<form class="form form-horizontal">
    <div class="form-body">
        <h4 class="form-section"><i class="ft-user"></i> Personal Info</h4>
        <?= GridView::widget([
            'dataProvider' => $preqProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'Fiscal_Year',
                'Category',
                //'Category_Name',
                'Category_Type',
                ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                'contentOptions' => ['style' => 'text-align:center'],
                'template' => '{view}',
                'buttons' => 
                [
                    'view' => function ($url, $model)
                    {
                        $baseUrl = Yii::$app->request->baseUrl;
                        return Html::a('<span class="fa fa-eye"></span> View', $baseUrl.'/home/view?id='.$model['Key'].'&myService=dp', [
                                    'title' => Yii::t('app', 'View'),
                                    'class'=>'gridbtn btn-primary btn-xs',                                  
                                    ]);
                    },
                ],
            ],
            ],
        ]);
        ?>

        <h4 class="form-section"><i class="ft-user"></i> Open Tenders</h4>
        <?= GridView::widget([
            'dataProvider' => $preqProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'Fiscal_Year',
                'Category',
                //'Category_Name',
                'Category_Type',
                ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                'contentOptions' => ['style' => 'text-align:center'],
                'template' => '{view}',
                'buttons' => 
                [
                    'view' => function ($url, $model)
                    {
                        $baseUrl = Yii::$app->request->baseUrl;
                        return Html::a('<span class="fa fa-eye"></span> View', $baseUrl.'/home/view?id='.$model['Key'].'&myService=dp', [
                                    'title' => Yii::t('app', 'View'),
                                    'class'=>'gridbtn btn-primary btn-xs',                                  
                                    ]);
                    },
                ],
            ],
            ],
        ]);
        ?> 

        <h4 class="form-section"><i class="ft-user"></i> Expression of Interest</h4>
        <?= GridView::widget([
            'dataProvider' => $EoiProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'Fiscal_Year',
                'Category',
                //'Category_Name',
                'Category_Type',
                ['class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                'contentOptions' => ['style' => 'text-align:center'],
                'template' => '{view}',
                'buttons' => 
                [
                    'view' => function ($url, $model)
                    {
                        $baseUrl = Yii::$app->request->baseUrl;
                        return Html::a('<span class="fa fa-eye"></span> View', $baseUrl.'/home/view?id='.$model['Key'].'&myService=dp', [
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
</form>
                                                            
                                                       