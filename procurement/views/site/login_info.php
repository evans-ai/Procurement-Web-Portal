<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

$this->title = 'Login';
$baseUrl = Yii::$app->request->baseUrl;
?>
<!-- END: Main Menu-->
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row mb-1">
        </div>
        <div class="content-body"> 
            <section id="basic-form-layouts">
              <div class="row match-height">                  
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-content collapse show">
                        <div class="card-body">
                          <?= $this->render('openings', 
                            [
                              'tenderProvider'=>$tenderProvider,
                              'preqProvider'=>$preqProvider,
                            ]); 
                          ?>                            
                        </div>
                      </div>
                    </div>
                  </div>                  
            </section>
          </div>            
        </div>
    </div>
</div>
<!-- END: Content-->
