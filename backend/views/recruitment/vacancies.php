<!-- BEGIN: Content-->
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Job Vacancies';
$baseUrl = Yii::$app->request->baseUrl;
$applicant_no = $session->get('Applicantid');
?>

<style>
    .headline {
        font-weight: bold;
    }
</style>

<div class="app-content content">

    <?php if (!Yii::$app->user->isGuest):
        echo $this->render('_steps');
    endif;
    ?>


    <div class="content-wrapper">
        <div class="content-body">
            <!-- Form wizard with number tabs section start -->
            <section id="number-tabs">
                <div class="row">
                    <div class="col-12">
                        <div class="card">


                            <?php if (Yii::$app->session->hasFlash('success')): ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×
                                    </button>
                                    <h4><i class="icon fa fa-check"></i>Saved!</h4>
                                    <?= Yii::$app->session->getFlash('success') ?>
                                </div>
                            <?php endif; ?>


                            <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×
                                    </button>
                                    <h4><i class="icon fa fa-check"></i>Error!</h4>
                                    <?= Yii::$app->session->getFlash('error') ?>
                                </div>
                            <?php endif; ?>


                            <div class="card-header">


                                <h3 class="headline" style="color:#CE352C">You want to make a difference…Exciting
                                    Opportunities</h3>

                                <p>The Financial Reporting Center(FRC is a Public Sector Organization tasked with the
                                    role of Regulating the Retirement Benefits Schemes in Kenya. The Authority is
                                    seeking qualified and experienced talent to join its dynamic management team in
                                    various positions.</p>
                                <p>
                                    Candidates short listed for interview will be required to produce a Certificate of
                                    Good Conduct from the Directorate of Criminal Investigations, Clearance or
                                    Compliance Certificate from the Higher Education Loans Board (HELB), Tax Compliance
                                    Certificates by Kenya Revenue Authority, a clean report from an approved Credit
                                    Reference Bureau and a duly attested self – declaration form by the Ethics and Anti
                                    – Corruption Commission.</p>

                                <p>Please Note:</p>

                                <ul>
                                    <li>Canvassing will lead to automatic disqualification.</li>
                                    <li>Persons with disability, women and from marginalized areas are encouraged to
                                        apply.
                                        Financial Reporting Center(FRC is an Equal Opportunity Employer.
                                    </li>
                                </ul>


                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 style="color:#CE352C" class="card-title">Job Vacancies</h4>
                                    </div>
                                    <?php if (Yii::$app->recruitment->internshipspresent()): ?>
                                        <div class="col-md-4">
                                            <a href="<?= $baseUrl; ?>/recruitment/internships" class="btn btn-primary">View
                                                Internship Positions</a>
                                        </div>
                                    <?php endif; ?>

                                </div>


                                <hr/>

                            </div>


                            <div class="card-content collapse show">


                                <div class="col-md-12">

                                    <a class="btn  <?= Yii::$app->request->get('closed') != 1 ? "btn-primary" : "btn-default" ?>"

                                       href="<?= $baseUrl; ?>/recruitment/vacancies?closed=0">Open</a>

                                    <a class="btn <?= Yii::$app->request->get('closed') == 1 ? "btn-primary" : "btn-default" ?>""
                                    href="<?= $baseUrl; ?>/recruitment/vacancies?closed=1">Closed</a>

                                </div>
                                <div class="card-body">


                                    <?= GridView::widget([
                                        'dataProvider' => $provider,
                                        'showOnEmpty' => false,
                                        'columns' => [
                                            [
                                                'class' => 'yii\grid\SerialColumn',
                                                'headerOptions' => ['width' => '5%'],

                                            ],
                                            [
                                                'attribute' => 'No',
                                                'headerOptions' => ['width' => '5%'],
                                            ],

                                            [
                                                'attribute' => 'Description',
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    return Html::a($model['Description'], ['recruitment/jobcard', 'id' => $model['No']], ['target' => '_blank']);
                                                },
                                                //'contentOptions'=> ['style'=>'width: 10%']
                                            ],
                                            [
                                                'attribute' => 'Type',
                                                'headerOptions' => ['width' => '15%'],
                                            ],
                                            [
                                                'label' => 'Deadline',
                                                'headerOptions' => ['width' => '10%'],
                                                'attribute' => 'Application Deadline'],


                                            ['class' => 'yii\grid\ActionColumn',
                                                'template' => '{download} {view} {update} {delete}',
                                                'headerOptions' => ['width' => '5%'],
                                                'buttons' => [
                                                    'download' => function ($url, $model) {
                                                        return Html::a(
                                                            '<span class="btn btn-sm"></i> View</span>',
                                                            ['recruitment/jobcard', 'id' => $model['No']],
                                                            [
                                                                'title' => 'Download',
                                                                'data-pjax' => '0',
                                                            ]
                                                        );
                                                    },
                                                ],
                                            ],

                                        ],
                                        'tableOptions' => [
                                            'class' => 'table table-striped table-bordered responsive',
                                            'data-role' => 'datatable',
                                            'data-searching' => 'true',
                                            'data-paging' => 'true',
                                            'data-ordering' => 'false',
                                            'data-info' => 'false'
                                        ],
                                    ]);
                                    ?>

                                </div><!--end card body-->
                            </div><!--end card content-->
                        </div><!--end card-->
                    </div><!--end col-12-->
                </div><!--end row-->
            </section>
        </div><!--end content body-->
    </div><!--end content body-->
</div><!--end app content-->

<!-- <li role="tab" class="first current" aria-disabled="false" aria-selected="true"><a id="steps-uid-0-t-0" href="#steps-uid-0-h-0" aria-controls="steps-uid-0-p-0"><span class="current-info audible">current step: </span><span class="step">1</span> Step 1</a></li> -->