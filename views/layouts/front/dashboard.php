<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/img/logo.png" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="page-container-bg-solid page-md">
<section id="container">
<?php $this->beginBody() ?>
    <!--<script src="<?php echo Yii::$app->request->baseUrl; ?>/plugins/jquery-1.10.1.min.js" type="text/javascript"></script>-->
    <?php include_once('header.php');?>
    <?php include_once('header_menu.php');?>
    <div class="page-wrapper-row full-height">
        <div class="page-wrapper-middle">
            <div class="page-container">
                <div class="page-content-wrapper">
                    <div class="page-head">
                        <div class="container">                        
                            <h1><?= Html::encode($this->title) ?></h1>
                        </div>
                    </div>
                    </div>
    <div class="page-content">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>        
            <div class="page-content-inner">
                <div class="mt-content-body">
                    <div class="row">
                    <div class="portlet light ">
                        <?= $content?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('footer.php');?>

<?php $this->endBody() ?>
</section>

<!-- <script src="<?php echo Yii::$app->request->baseUrl.'/plugins/common-scripts.js';?>"></script> -->

<!--<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=true"></script>-->
</body>
</html>
<?php $this->endPage() ?>
