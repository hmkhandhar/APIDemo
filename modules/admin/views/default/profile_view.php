<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Service */
/* @var $form yii\widgets\ActiveForm */

?>

<!-- <div class="content-wrapper"> -->
    <section class="content-header">
    <h1>
      Profile </h1>
    <!-- <ol class="breadcrumb">
      <li><?=Html::a('<i class="fa fa-dashboard"></i> '.Yii::t('app', 'Home'), ['default/index']) ?></li>
      <li class ="active"><?=Html::a('Profile', ['']) ?></li>
    </ol> -->
  </section>
    <!-- Main content -->
	<section class="content col-lg-11">
    <div class="box box-primary">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <!--<div class="box-header with-border">
          <h3 class="box-title"></h3>

        </div>-->
        <div class="box-body">
        	<?php echo \Yii::$app->getSession()->getFlash('flash_msg');  ?>
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h3 class="panel-title"></h3>
				</div>
				<div class="panel-body">
					
					<div class="row">
						<div class="col-md-8 col-lg-6">
							<div class="form-group field-users-full_name">
								<label class="control-label col-sm-4" for="users-full_name">Name</label>
								<div class="col-sm-8">
								<label> <?php echo $model->full_name ; ?></label>
								<div class="help-block help-block-error "></div>
								</div>
							</div>
							<div class="form-group field-users-email">
								<label class="control-label col-sm-4" for="users-email">Email</label>
								<div class="col-sm-8">
								<label> <?php echo $model->email_id ; ?></label>
								<div class="help-block help-block-error "></div>
								</div>
							</div>	
							<div class="form-group field-users-mobile_number">
								<label class="control-label col-sm-4" for="users-mobile_number">Mobile Number</label>
								<div class="col-sm-8">
								<label> <?php echo $model->mobile_number ; ?></label>				
								<div class="help-block help-block-error "></div>
								</div>
							</div>
					</div>
					</div>
					<div class="col-md-8 col-lg-6">
							<div class="row">
								<div class="col-sm-8 col-sm-offset-4">
									
									<?=  Html::a(Yii::t('app', 'Edit'),['default/editprofile'],['class'=>"btn btn-primary"]); ?>
									<?=  Html::a(Yii::t('app', 'Cancel'),['default/index'],['class'=>"btn btn-default"]); ?>
									
								</div>
							</div>
					</div>
					
					
		</div>
		  </div>
        </div>
        <!-- /.box-body -->
        <!--<div class="box-footer">
          <h5>Change Password</h5>
        </div>-->
        <!-- /.box-footer-->
      </div>
</section>
</div>

