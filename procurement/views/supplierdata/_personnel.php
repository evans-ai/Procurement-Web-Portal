<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Directors List';
$baseUrl = Yii::$app->params['baseUrl'];

// print '<pre>';
// print_r($businesstype); exit;

?>
   
<section id="basic-form-layouts" class="container">
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: #fff">              
                    <h1 class="card-title">Business Profile</h1>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <?=$this->render('steps', ['active' => 'personnel']) ?>
                        <br><br>
                        <br>
                        <hr/>
                        <br>
                        <?php $form = ActiveForm::begin([]); ?>
                        <?= $form->field($model, 'Key')->hiddenInput()->label(false); ?>
                        <input type="hidden" id="totalShares" value="<?= $totalShares; ?>" name="totalShares"/>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'DirectorName')->textInput()->label('Full Name'); ?>
                            </div>
                            <div class="col-md-4">                            
                                <?= $form->field($model, 'Relationship')->dropDownList($OptionsArray); ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'Nationality')->dropDownList(ArrayHelper::map($countries,'Code','Description'), ['prompt'=>'Select Country']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">                        
                                <?= $form->field($model, 'Citizenship')->dropDownList(array('By Birth' => 'By Birth', 'By Registration' => 'By Registration', 'N/A' => 'N/A'))->label('Citizenship Details'); ?>                        
                            </div>                      
                            <?php if($businesstype != 'Sole_Proprietorship'){ ?>
                            <div class="col-md-3">
                                <?= $form->field($model, 'Shares')->textInput(['type'=>'number','max'=> $max, 'min' => 0])
                                    ->label('Shares %'); ?>
                            </div>
                            <?php } else{
                                echo $form->field($model, 'Shares')->hiddenInput()->label(false);
                            } ?>
                            <div class="col-md-3">
                                <?= $form->field($model, 'ID_Passport')->textInput(['required'=>'required'])->label('ID/Passport'); ?>
                            </div> 
                            <div class="col-md-3">
                                <?= $form->field($model,'KRA_PIN')->textInput()->label('KRA/PIN'); ?>
                            </div>                    
                        </div>
                        <div class="form-actions">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                            <?php  
                                if($totalShares == 0){
                                    echo Html::a('Next', 'categories', ['class' => 'btn btn-danger']);
                                } 
                            ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                        <hr />
                        <h4 class="form-section"><i class="ft-layers"></i> Business Management Team</h4>
                        <div class="form-group-row">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'tableOptions' => ['class' => 'table table-bordered table-striped table-xs'],
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'DirectorName',
                                    'Nationality',
                                    'Relationship',
                                    [
                                        'attribute'=>'Shares',
                                        'label'=>'Share (%)'
                                    ],
                                    [
                                        'attribute'=>'ID_Passport',
                                        'label'=>'ID/Passport No.'
                                    ],
                                    [
                                        'attribute'=>'KRA_PIN',
                                        'label'=>'KRA PIN.'
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                        'contentOptions' => ['style' => 'text-align:center'],
                                        'template' => '{edit}{delete}',
                                        'buttons' => [
                                            'edit' => function ($url, $model){
                                                $baseUrl = Yii::$app->request->baseUrl;
                                                return Html::a('<span class="btn btn-sm btn-secondary"><i class="ft-danger"></i> Edit</span>', 'personnel?ref='.$model['Line_No'].'', [
                                                    'title' => Yii::t('app', 'View'),
                                                    'class'=>'gridbtn btn-primary btn-xs',                                  
                                                ]);                                                
                                            },
                                            'delete' => function ($url, $model){
                                                $baseUrl = Yii::$app->request->baseUrl;
                                                return Html::a('<span class="btn btn-sm btn-danger"><i class="ft-danger"></i> Delete</span>','delete-personnel?key='.$model['Key'].'', [
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
            </div>
        </div>
    </div>
</section>                              
<!-- Form wizard with vertical tabs section end -->
