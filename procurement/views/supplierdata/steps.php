<?php if (Yii::$app->session->hasFlash('success')){ ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Saved!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php } ?>

<?php if (Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Error!</h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php } ?>
<div class="text-center">
    <ul class="progressbar">
        <li style="width: 20%" <?=$active=='particulars'? 'class="active"' : ''?>>Business Particulars</li>
        <li style="width: 20%" <?=$active=='personnel'? 'class="active"' : ''?>>Management Team</li>
        <li style="width: 20%" <?=$active=='categories'? 'class="active"' : ''?>>Prequalification Categories</li>
        <li style="width: 20%" <?=$active=='docs'? 'class="active"' : ''?>>Documents</li>
        <li style="width: 20%" <?=$active=='submit'? 'class="active"' : ''?>>Submit</li>
    </ul>
</div>

