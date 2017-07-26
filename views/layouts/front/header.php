<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<body class="page-container-bg-solid page-md">
        <div class="page-wrapper">
            <div class="page-wrapper-row">
                <div class="page-wrapper-top">   
                <div class="page-header">
                        <!-- BEGIN HEADER TOP -->
                        <div class="page-header-top">
                            <div class="container">
                              <div class="page-logo">
                              <!-- <?=  Html::a(Yii::t('app', 'Cancel'),['site/index'],['class'=>"btn btn-default"]); ?> -->
                            <a href="index">
                          <img src="/apps/khandhar_training/APIDemo/web/layouts/layout3/img//logo-default.jpg" alt="logo" class="logo-default">
                          </a>
                                </div>
                                <!-- END LOGO -->
                                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                                <a href="javascript:;" class="menu-toggler"></a>
                                <!-- END RESPONSIVE MENU TOGGLER -->
                                <!-- BEGIN TOP NAVIGATION MENU -->
                                <div class="top-menu">
                      <?php if($isLoggedin){ ?>
                        <ul class="nav navbar-nav pull-right">
                          <li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
                        <!-- BEGIN USER LOGIN DROPDOWN -->
                          <li class="dropdown dropdown-user dropdown-dark">
                              <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                  <img alt="" class="img-circle" src="<?=$path?>/<?=$img?>" />
                                  <!-- <img alt="" class="img-circle" src="/web/<?=$img?>"> -->
                                <span class="username username-hide-mobile"><?=$name?></span>
                            </a>
                              <ul class="dropdown-menu dropdown-menu-default">
                            
                                <li>
                                    <?= Html::a('<span> <i class="icon-user"></i>'.Yii::t('app','My Profile').'</span>',["/site/editprofile"]) ?>                                   
                                </li>
                                <li>
                                  <?= Html::a('<span> <i class="icon material-icons"></i>'.Yii::t('app','Change Passsword').'</span>',["/site/changepassword"])?>
                                </li>
                                <!-- <li class="divider"> </li>  -->                          
                                <!-- <li>            
                                    <?= Html::a('<span> <i class="icon-key"></i>'.Yii::t('app','Login').'</span>',["/site/login"]) ?>
                                </li>
                                <li>            
                                    <?= Html::a('<span> <i class="icon-key"></i>'.Yii::t('app','Sign Up').'</span>',["/site/signup"]) ?>
                                </li> -->
                              
                                  <li role="separator" class="divider no-margin"></li>
                                <!-- <?php echo Yii::$app->request->baseUrl."/index.php/site/logout"; ?> -->
                                <li>
                                <!-- <a href="<?php echo Yii::$app->request->baseUrl; ?>/index.php/site/login">Login</a> -->
                                  <a href="<?php echo Yii::$app->request->baseUrl; ?>/index.php/site/logout">Sign out</a>
                                  
                                </li>
                                <!-- Yii::$app->request->baseUrl."/index.php/site/performlogin", -->
                                     </ul>
                                      </li>
                                    </ul>
                                <?php  }else{ ?>

                                  <ul class="nav navbar-nav pull-right">
                                    <li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
                                  <!-- BEGIN USER LOGIN DROPDOWN -->
                                    <li class="dropdown dropdown-user dropdown-dark">
                                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                        <img alt="" class="img-circle" src="/apps/khandhar_training/APIDemo/web/img/userImg.png">
                                          <span class="username username-hide-mobile"></span>
                                      </a>
                                        <ul class="dropdown-menu dropdown-menu-default">
                                      
                                          <li>
                                          <?= Html::a('<span> <i class="icon-key"></i>'.Yii::t('app','Login').'</span>',["/site/login"]) ?>
                                        </li>
                                        </ul>
                                         </ul>
                                      </li>
                                    </ul>
                                <?php } ?>                              
                           
                    </div>
                  </div>
                </div>
<style>
.user-footer .btn{ padding : 6px 9px !important; }
</style>
<script>
$(document).ready(function(){
    $(".flash_msg").fadeOut(7000);
    $(".fade").fadeOut(7000);	
    });
</script>
</header>