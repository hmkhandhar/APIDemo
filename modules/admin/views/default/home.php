<?php

/* @var $this yii\web\View */
?>
<section class="content-header col-lg-3">
     <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol> -->
    </section>
    <!-- Main content -->
    <section class="content col-lg-11">
    <div class="box box-primary">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?=$allUser?></h3>

              <p>Users</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?= Yii::$app->request->baseUrl.'/index.php/admin/user/' ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>        
        <!-- ./col -->
               
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>3</h3>

              <p>Product</p>
            </div>
            <div class="icon">
              <i class="fa fa-motorcycle"></i>
            </div>
            <a href="<?= Yii::$app->request->baseUrl.'/index.php/admin/product/' ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?=$allAdmin?></h3>

              <p>Admins</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?= Yii::$app->request->baseUrl.'/index.php/admin/user/' ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        
      </div>
      <!-- /.row (main row) -->
      <div class="row">
        
      </div>
      <div class="row">
        
      </div>
      <div class="row">
        
      </div>
      <div class="row">
        
      </div>
      <div class="row">
        
      </div>
      </div>
    </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->