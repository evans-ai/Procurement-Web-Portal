<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\SupplierdataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplierdata-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'No_') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Address') ?>

    <?= $form->field($model, 'PlotNo_') ?>

    <?php // echo $form->field($model, 'Street_Road') ?>

    <?php // echo $form->field($model, 'PostalAddress') ?>

    <?php // echo $form->field($model, 'NatureOfBusiness') ?>

    <?php // echo $form->field($model, 'LicenseNo_') ?>

    <?php // echo $form->field($model, 'LicenseExpiry') ?>

    <?php // echo $form->field($model, 'MaxBusinessValue') ?>

    <?php // echo $form->field($model, 'Banker') ?>

    <?php // echo $form->field($model, 'Branch') ?>

    <?php // echo $form->field($model, 'SoleProprietor') ?>

    <?php // echo $form->field($model, 'Proprietor') ?>

    <?php // echo $form->field($model, 'ProprietorDOB') ?>

    <?php // echo $form->field($model, 'ProprietorNationality') ?>

    <?php // echo $form->field($model, 'ProprietorOrigin') ?>

    <?php // echo $form->field($model, 'ProprietorCitizedID') ?>

    <?php // echo $form->field($model, 'Partnership') ?>

    <?php // echo $form->field($model, 'Company') ?>

    <?php // echo $form->field($model, 'PublicCompany') ?>

    <?php // echo $form->field($model, 'NorminalCapital') ?>

    <?php // echo $form->field($model, 'IssuedCapital') ?>

    <?php // echo $form->field($model, 'UserID') ?>

    <?php // echo $form->field($model, 'SupplierType') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
