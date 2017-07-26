<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>

<script src="https://apis.google.com/js/api:client.js"></script>
<meta name="google-signin-client_id" content="87577243966-hv6vn8s6umg3e2po3e4mo76qoe2e8cnn.apps.googleusercontent.com">
 <div class="main-container">
    <div class="page-section page-section-padding-lg">
        <div class="container">
            <div class="login-wrapper">
                <div class="row">
                    <div class="v-grid-container">
                        <div class="v-grid col-sm-6">
                            <div class="col-lg-10 col-sm-12 col-lg-offset-2">
                                <div class="login-form">
                                    <div class="login-header">
                                        <!-- <a href="<?php echo Yii::$app->request->baseUrl; ?>"><img src="<?php echo Yii::$app->request->baseUrl; ?>/img/login-logo.png" alt="Login Logo" /></a> -->
                                    </div>
                                    <div class="form-group  row row-padding-small">
                                        <div class="col-sm-6">
                                            <!-- <button type="button" class="btn btn-facebook btn-block btn-social fblogin"><i class="fa fa-facebook"></i>Signup with Facebook</button> -->
                                        </div>
                                        <div class="col-sm-6">
                                            <!-- <button type="button" id="customBtn" class="btn btn-google-plus btn-block btn-social"><i class="fa fa-google-plus"></i>Signup with Google</button> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
                                    </div>
                                    <?php $form = ActiveForm::begin([
                                              'id'=>'signup-form',
                                              'action'=>Yii::$app->request->baseUrl."/index.php/site/performsignup",
                                              'enableAjaxValidation'=>false,
                                              'enableClientValidation'=>false,
                                          ]); ?>
                                            <div class="form-group">
                                                <input type="text" name="full_name" class="form-control" placeholder="Full Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" name="email_id" class="form-control" placeholder="Email ID">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="mobile_number" class="form-control" placeholder="Contact">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" class="form-control" placeholder="Password">
                                            </div>
                                            <!-- <div class="form-group">
                                                <label class="checkbox-set text-muted">
                                                    <input type="checkbox" name="term"> I agree with the <a href="javascript:;" target="_blank" class="text-blue text-underline">Terms & Conditions</a>.
                                                    <span></span>
                                                </label>
                                                <span for="term" class="help-block"></span>
                                            </div> -->
                                            <div class="form-action">
                                                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign Up</button>
                                            </div>
                                            <div class="text-center res-text"></div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                                <div class="form-meta text-center font-md text-muted">
                                    Already have an account? <a href="<?php echo Yii::$app->request->baseUrl; ?>/index.php/site/login" class="text-secondary text-underline">LOGIN</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Page Section -->                    

</div> <!-- End Main Container -->
<script type="text/javascript">

var form1 = $('#signup-form');
var error1 = $('.alert-danger', form1);
var success1 = $('.alert-success', form1);
form1.validate({
  ignore: [],
    errorElement: 'span', //default input error message container
    errorClass: 'help-block', // default input error message class
    focusInvalid: true, // do not focus the last invalid input
    rules: {
          "full_name": {
              required:true,
              minlength:3,
              maxlength:25,
          },
          "email_id": {
              required:true,
              minlength:5,
              maxlength:30,
              email:true,
              checkemail:true,
          },
          "mobile_number": {
              required:true,
              maxlength:10,
              minlength:10,
              digits:true
          },
          "password": {
              required:true,
              minlength:6,
              maxlength:20,
          },
},
    messages:{
        "full_name": {
              required:"Please enter full name",
             
          },
          "email_id": {
              required:"Please enter email id",
              email:"Please enter valid email id"
          },
          "mobile_number": {
              required:"Enter mobile number",
          },
          "password": {
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

</script>