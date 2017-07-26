<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Change Passowrd';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-container">
                    
    <!-- Page Section -->
    <div class="page-section page-section-padding-lg">
        <div class="container">
            <div class="login-wrapper">
                <div class="row">
                    <div class="v-grid-container">
                        
                        <div class="v-grid col-sm-6">
                            <div class="col-lg-8 col-sm-12 col-lg-offset-2">
                                <div class="login-form">
                                    <div class="login-header">
                                        <a href="<?php echo Yii::$app->request->baseUrl; ?>"><img src="<?php echo Yii::$app->request->baseUrl; ?>/img/login-logo.png" alt="Login Logo" /></a>
                                    </div>
                                    <h4><p class="text-center">Change Password</p></h4>
                                    <div class="form-group">
                                        <?php echo \Yii::$app->getSession()->getFlash('flash_msg'); ?>
                                    </div>
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'reset-form',
                                        'options' => ['class' => 'form-signin'],
                                    ]); ?>

                                    <?= $form->field($model, 'password',['inputOptions'=>array('placeholder'=>'Old password','required'=>'required')])->passwordInput()->label(false); ?>
                                    <?= $form->field($model, 'new_password',['inputOptions'=>array('placeholder'=>'New password','required'=>'required')])->passwordInput()->label(false); ?>
                                    <?= $form->field($model, 'PasswordConfirm',['inputOptions'=>array('placeholder'=>'Confirm new password','required'=>'required')])->passwordInput()->label(false); ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-block btn-flat submit', 'name' => 'login-button ']) ?>
                                        </div>
                                        <!--<button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>-->
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


<script>
    
        
            
        
    $('.submit').attr('disabled',true);
    var ajax_request;
        $("#users-password").on('input propertychange change', function() {
            var val1 = $(this).val();
           // var  id1 =  <?php echo $model->id;?> ; //$('#userid').val();
            
            path = "<?php echo Yii::$app->request->baseUrl; ?>";
            pass_check = "/index.php/site/oldpasswordcheck";
            
            //if (validateEmail(val1)) {
                if(typeof ajax_request !== 'undefined')
                    ajax_request.abort();
                ajax_request = $.ajax({
                    type:"POST",
                    url:path+pass_check,
                    data:{pass: val1},    // multiple data sent using ajax
                    success: function (result) {
                        console.log(result);
                        if (result == 1)
                        {
                            $('.field-users-password').find('.help-block-error').text('');
                            $('.submit').attr('disabled',false);
                        }
                        else
                        {
                           $('.field-users-password').find('.help-block-error').text('Please enter Correct Old Passward!');
                           $('.submit').attr('disabled',true);
                        }
                    }
                });
           // }
        });
    
    
    
$( document ).ready(function() {
var form1 = $('#reset-form');
var error1 = $('.alert-danger', form1);
var success1 = $('.alert-success', form1);
form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block', // default input error message class
        focusInvalid: true, // do not focus the last invalid input
        rules: {

            "Users[password]": {
                required: true,
                
                maxlength:15,
            },
            "Users[new_password]": {
                required:true,
                minlength:6,
            },
            "Users[PasswordConfirm]": {
                required:true,
                equalTo: "#users-new_password",
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
        errorPlacement: function (error, element) { // render error placement for each input type
            if (element.attr("name") == "User[image]") { // for uniform radio buttons, insert the after the given container
                error.addClass("no-left-padding").insertAfter("#image-error");
            }
            if (element.attr("name") == "User[coverimage]") { // for uniform radio buttons, insert the after the given container
                error.addClass("no-left-padding").insertAfter("#coverimage-error");
            }else {
                error.insertAfter(element); // for other inputs, just perform default behavoir
            }
        },

    });
});
</script>

<style type="text/css">
    .help-block-error{
        color: red !important;
    }
</style>
