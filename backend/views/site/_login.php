<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="app-content content">

	<div class="content-wrapper">
		<div class="content-body">
			<!-- Form wizard with number tabs section start -->
			<section id="number-tabs">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Login</h4>
								<!-- <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
								<div class="heading-elements">
										<ul class="list-inline mb-0">
											<li><a data-action="collapse"><i class="ft-minus"></i></a></li>
											<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
											<li><a data-action="expand"><i class="ft-maximize"></i></a></li>
											<li><a data-action="close"><i class="ft-x"></i></a></li>
										</ul>
								</div> -->
							</div>
							<div class="card-content collapse show">
								<div class="card-body">
									<div class="row">
										
										<div class="col-md-6">
											<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

												<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

												<?= $form->field($model, 'password')->passwordInput() ?>

												<?= $form->field($model, 'rememberMe')->checkbox() ?>

												<div class="form-group">
													<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

													<?= Html::a('Sign Up',['./site/signup'],['class' => 'btn btn-info', 'name' => 'login-button']) ?>
												</div>

												

											<?php ActiveForm::end(); ?>
										<div>
										
									<div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

