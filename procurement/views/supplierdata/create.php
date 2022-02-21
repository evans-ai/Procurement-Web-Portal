<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Supplierdata */

$this->title = 'Create Supplier Profile';
//$this->params['breadcrumbs'][] = ['label' => 'Supplier Data', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplierdata-create">

    <h1><?= Html::encode($this->title) ?></h1>  

    <?= $this->render('_form', [
        'model' => $model, 'directors' =>$directors, 'newDirector' => $newDirector, 'newPartner' => $newPartner, 'partners' => $partners,
    ]) ?>

</div>
