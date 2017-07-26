<?php
use yii\helpers\Html;
// use app\models\Users;
$isLoggedin = FALSE;
if(isset(Yii::$app->user->identity->id) && Yii::$app->user->identity->id!=null)
{
    $path = Yii::$app->request->baseUrl;
    $name='';
    $email = "";
    $img = '/img/userImg.png';

    if(isset(Yii::$app->user->identity->name) && Yii::$app->user->identity->name!=null)
    {
      $name=Yii::$app->user->identity->name;
    }
    
    if(isset(Yii::$app->user->identity->image) && Yii::$app->user->identity->image!=null)
    {
      $img = Yii::$app->user->identity->image;      
    }

    if(isset(Yii::$app->user->identity->email_id) && Yii::$app->user->identity->email_id!=null)
    {
      $email=Yii::$app->user->identity->email_id;
    }
    $isLoggedin = TRUE;
  
}else{
  $isLoggedin = FALSE;
}

?>
<header class="main-header">
    <!-- Logo -->
    <a href="javascript:void(0);" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b></span>
      <!-- logo for regular state and mobile devices -->
      
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <?php if($isLoggedin){ ?>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=$path?>/<?=$img?>" class="user-image" alt="">
              <span class="hidden-xs">
                <?php
                  if(isset(Yii::$app->user->identity->name) && Yii::$app->user->identity->name!=null)
                  {
                    echo Yii::$app->user->identity->name;
                  }
                  else {
                    echo "Admin";
                  }
                ?>
              </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?=$path?>/<?=$img?>" class="img-circle" alt="User Image">
                <p>
                  <?php
                    if(isset(Yii::$app->user->identity->name) && Yii::$app->user->identity->name!=null)
                    {
                      echo  Yii::$app->user->identity->name;
                    }
                    else {
                      echo "Admin";
                    }
                  ?>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <?= Html::a('<span>Profile</span>',["/admin/default/profileview"],['class'=>'btn btn-default btn-flat']) ?>
                  <?= Html::a('<span>Change Password</span>',["/admin/default/changepassword"],['class'=>'btn btn-default btn-flat']) ?>
                  <!--<a href="#" class="btn btn-default btn-flat">Profile</a>-->
                </div>

                <div class="pull-right">
                   <?= Html::a('<span>Sign out</span>',["/admin/default/logout"],['class'=>'btn btn-default btn-flat']) ?>
                  <!--<a href="#" class="btn btn-default btn-flat">Sign out</a>-->
                </div>

              </li>
            </ul>
          </li>
          <?php  }else{ ?>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo Yii::$app->request->baseUrl; ?>/img/logo.png" class="user-image" alt="">
              <span class="hidden-xs">
                <?php
                  if(isset(Yii::$app->user->identity->name) && Yii::$app->user->identity->name!=null)
                  {
                    echo Yii::$app->user->identity->name;
                  }
                  else {
                    echo "Admin";
                  }
                ?>
              </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo Yii::$app->request->baseUrl; ?>/img/logo.png" class="img-circle" alt="User Image">
                <p>
                  <?php
                    if(isset(Yii::$app->user->identity->name) && Yii::$app->user->identity->name!=null)
                    {
                      echo  Yii::$app->user->identity->name;
                    }
                    else {
                      echo "Admin";
                    }
                  ?>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <?= Html::a('<span>Login</span>',["/admin/default/login"],['class'=>'btn btn-default btn-flat']) ?>                  
                </div>
              </li>
            </ul>
          </li>
          <?php } ?>
        </ul>
      </div>
    </nav>
</header>

<style>
.user-footer .btn{ padding : 6px 9px !important; }
</style>
