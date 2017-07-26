<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
$dash = $user = $admin = '';

$controller = strtolower(Yii::$app->controller->id);
$action = strtolower(Yii::$app->controller->action->id);

function openness($controller)
{
    if(strtolower(Yii::$app->controller->id) == $controller)
    {
        return 'open active';
    }
}

?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>

        <li class="<?php echo openness('default'); ?>">
          <?= Html::a('<i class="fa fa-home"></i><span>Dashboard</span>',["/admin/default"]) ?>
        </li>

        <!-- <li class="<?php echo openness('product'); ?>">
          <?= Html::a('<i class="fa fa-bookmark-o"></i><span>Product</span>',["/admin/product"]) ?>
        </li> -->

        <li class="<?php echo openness('product'); ?>">
          <?= Html::a('<i class="fa fa-list"></i><span>Product</span>',["/admin/product"]) ?>
        </li>
        
        <li class="<?php echo openness('cms'); ?>">
          <?= Html::a('<i class="fa fa-list"></i><span>CMS</span>',["/admin/cms"]) ?>
        </li>

        <li class="<?php echo openness('user'); ?>">
          <?= Html::a('<i class="fa fa-user"></i><span>User</span>',["/admin/user"]) ?>
        </li> 

        <!-- <li class="<?php echo openness('newsletter'); ?>">
          <?= Html::a('<i class="fa fa-crop"></i><span>Newsletter</span>',["/admin/newsletter"]) ?>
        </li> -->
        
        <!-- <li class="<?php echo openness('bookbike'); ?>">
          <?= Html::a('<i class="fa fa fa-list"></i><span>Bookings</span>',["/admin/bookbike"]) ?>
        </li>  -->
        
        

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
