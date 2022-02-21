<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\SupplierdataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supplier Data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplierdata-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Supplierdata', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
