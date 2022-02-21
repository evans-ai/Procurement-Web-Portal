<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<br>
<div class="site-error container">
  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title"><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="card-content" aria-expanded="true">
                <div class="card-body">
                    <div class="alert alert-danger">
                        <?= nl2br(Html::encode($message)) ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p  class="text-muted">The above error occurred while the Web server was processing your request.</p>
                <p>Please contact us if you think this is a server error. Thank you.</p>
            </div>
        </div>
    </div>
  </div>
</div>
