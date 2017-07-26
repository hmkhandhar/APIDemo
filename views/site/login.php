<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;



$session = Yii::$app->session;
$confirmBooking = $session->get('confirmBooking');
$rediect = Yii::$app->request->baseUrl;
if($confirmBooking == 1){

    $rediect = Yii::$app->request->baseUrl."/site/confrimbooking";

}else{

    $rediect = Yii::$app->request->baseUrl;
}

?>

<script src="https://apis.google.com/js/api:client.js"></script>
<meta name="google-signin-client_id" content="87577243966-hv6vn8s6umg3e2po3e4mo76qoe2e8cnn.apps.googleusercontent.com">

<div class="main-container">
                    
    <!-- Page Section -->
    <div class="page-section page-section-padding-lg">
        <div class="container">
            <div class="login-wrapper">
                <div class="row">
                    <div class="v-grid-container">
                        <div class="v-grid col-sm-6">
                            <div class="col-sm-12 col-lg-10">
                                <div class="login-content">
                                    <div class="login-banner">
                                        <img src="<?php echo Yii::$app->request->baseUrl; ?>/img/login-banner.png" alt="Login Banner" />
                                    </div>
                                    <h1 class="heading">Signup</h1>
                                    
                                    <div class="form-action text-left">
                                        <a href="<?php echo Yii::$app->request->baseUrl; ?>/index.php/site/signup" class="btn btn-secondary-outline btn-lg btn-block max-150 btn-round">Sign Up</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="v-grid col-sm-6">
                            <div class="col-lg-10 col-sm-12 col-lg-offset-2">
                                <div class="login-form">
                                    <div class="login-header">
                                        <a href="<?php echo Yii::$app->request->baseUrl; ?>"><img src="<?php echo Yii::$app->request->baseUrl; ?>/img/login-logo.png" alt="Login Logo" /></a>
                                    </div>                                    
                                    <div class="form-group">
                                        <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
                                    </div>
                                    <?php $form = ActiveForm::begin([
                                              'id'=>'login-form',
                                              'action'=>Yii::$app->request->baseUrl."/index.php/site/performlogin",
                                              'enableAjaxValidation'=>false,
                                              'enableClientValidation'=>false,
                                          ]); ?>
                                            <div class="form-group">
                                                <input type="email" name="LoginFormUser[email_id]" class="form-control" placeholder="Email">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="LoginFormUser[password]" class="form-control" placeholder="Password">
                                            </div>
                                            <div class="form-action">
                                                <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
                                            </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Page Section -->                    

</div> <!-- End Main Container -->

<!-- forgot password Modal -->






<script>

/*********************************************************************
                    LOGIN FORM VALIDATION START
**********************************************************************/
var form1 = $('#login-form');
var error1 = $('.alert-danger', form1);
var success1 = $('.alert-success', form1);
form1.validate({
  ignore: [],
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: true, // do not focus the last invalid input
    rules: {
          "LoginFormUser[email_id]": {
              required:true,
              minlength:5,
              maxlength:30,
              email:true,
          },
           "LoginFormUser[password]": {
              required:true,
          },
    },
    messages:{
          "LoginFormUser[email_id]": {
              required:"Please enter email id",
              email:"Please enter valid email id"
          },
           "LoginFormUser[password]": {
              required:"Enter Password",
          },
    },
    invalidHandler: function (event, validator) { //display error alert on form submit
        success1.hide();
        error1.show();
    },
    highlight: function (element) { // hightlight error inputs
        $(element)
            .closest('.form-group').addClass('has-error'); // set error class to the control group
    },
    success: function (label) {
        label
            .closest('.form-group').removeClass('has-error'); // set success class to the control group
    },
    /*submitHandler: function(form) {
        //when form is successfully validate
    },*/
    errorPlacement: function (error, element) { // render error placement for each input type
        if (element.attr("name") == "add_doctor") { // for uniform radio buttons, insert the after the given container
            error.addClass("no-left-padding").insertAfter("#image-error");
        } else {
            error.insertAfter(element); // for other inputs, just perform default behavoir
        }
    },
});
/*********************************************************************
                    LOGIN FORM VALIDATION END
**********************************************************************/
 </script>
