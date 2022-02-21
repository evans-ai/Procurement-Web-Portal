 <?php 
 $action = Yii::$app->controller->action->id;
 $baseUrl = Yii::$app->request->baseUrl;
//print $action;
   ?>
 <style type="text/css">
   a.active{
    color: #fff !important;
    font-style: bold;
  }
</style>
 <div class="content-header row">
            <div class="content-header-dark bg-img col-12">
                <div class="row">
                    <div class="content-header-left col-md-12 col-12 mb-2">
                        <h3 class="content-header-title white">Applicant Profile</h3>
                        <div class="row breadcrumbs-top">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a class="<?= ($action == 'index')?'active':''?>" href="<?= $baseUrl ?>./?create=1">General</a>
                                    </li>
                                    
                                    <?php if(Yii::$app->recruitment->hasprofile()): ?>

                                    <li class="breadcrumb-item "><a class="<?= ($action == 'qualifications')?'active':''?>" href="<?= $baseUrl ?>./qualifications">Qualifications</a>
                                    </li>
                                    <li class="breadcrumb-item "><a class="<?= ($action == 'experience')?'active':''?>" href="<?= $baseUrl ?>./experience">Experience</a>
                                    </li>
                                    <li class="breadcrumb-item "><a class="<?= ($action == 'referee')?'active':''?>" href="<?= $baseUrl ?>./referee">Referees</a>
                                    </li>
                                    <li class="breadcrumb-item "><a class="<?= ($action == 'attachments')?'active':''?>"  href="<?= $baseUrl ?>./attachments">Attachments</a>
                                    </li>
                                    <li class="breadcrumb-item"><a class="<?= ($action == 'comments')?'active':''?>" href="<?= $baseUrl ?>./comments">Comments</a>
                                    </li>


                                <?php endif; ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>