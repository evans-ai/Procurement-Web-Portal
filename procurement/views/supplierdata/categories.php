<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = 'Directors List';
$baseUrl = Yii::$app->params['baseUrl'];

// print '<pre>';
// print_r($businesstype); exit;

?>
   
<section id="basic-form-layouts" class="container ">
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: #fff">              
                    <h1 class="card-title">Business Profile</h1>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <?=$this->render('steps', ['active' => 'categories']) ?>
                        <br><br>
                        <br>
                        <hr/>
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group" style="column-count: 3">
                            
                            <?php /*** Select2::widget([
                                'name' => 'categories',
                                'data' => $CategoryOptions,
                                'options' => [
                                    'placeholder' => 'Select Categories',
                                    'multiple' => true,
                                    'class' => 'form-control',
                                    'required' => true,
                                ],
                            ]) /*******/?>
                            <?=Html::checkboxList('categories[]', $Selected, $CategoryOptions, ['class' => 'checkbox', 'required' => true]); ?>
                        </div>
                            <?=Html::a('Previous', 'personnel', ['class' => 'btn btn-danger']) ?>
                            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>                        
                            <?php if($Selected){
                                echo Html::a('Next', 'documents', ['class' => 'btn btn-success']);
                            }  ?>                        
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>                              
<!-- Form wizard with vertical tabs section end -->
