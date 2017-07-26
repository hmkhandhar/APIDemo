<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
// use yii\bootstrap\ActiveForm;
$this->title = Yii::$app->params['apptitle'].' : Login To Your Account';



$cookies = Yii::$app->request->cookies;
// get the cookie value
$email_id = $cookies->getValue(Yii::$app->params['appcookiename'].'email');

//return default value if the cookie is not available
$password = $cookies->getValue(Yii::$app->params['appcookiename'].'password');
$no = $cookies->getValue(Yii::$app->params['appcookiename'].'turns');

for($i=1;$i<=$no;$i++){
	$email_id = base64_decode($email_id);
	$password = base64_decode($password);
}


if($email_id){$model->email = $email_id;}
if($password){$model->password = $password;}
if($email_id){$model->rememberMe = true;}


?>
<!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    <?php
        echo \Yii::$app->getSession()->getFlash('flash_msg');
    ?>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-signin'],
    ]); ?>
      <?= $form->field($model, 'email_id',['inputOptions'=>array('placeholder'=>'Email Id')])->label(false); ?>
      <?= $form->field($model, 'password',['inputOptions'=>array('placeholder'=>'Password')])->passwordInput()->label(false); ?>
      <div class="row">
      <div class="login-box-body">
        <div class="col-xs-8">

          <?php echo $form->field($model, 'rememberMe', ['template' => '<label>{input}</label>'])->checkbox(); ?>
          <!--<div class="checkbox icheck">-->
          <!--  <label>-->
          <!--    <input type="checkbox"> Remember Me-->
          <!--  </label>-->
          <!--</div>-->
        </div>

        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        </div>

        <!-- /.col -->
      </div>
    <?php ActiveForm::end(); ?>

    <a data-toggle="modal" href="#myModal"> Forgot Password?</a> 

    

  </div>
  <!-- /.login-box-body -->


   <!-- Modal -->
          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">Forgot Password ?</h4>
                      </div>
                      <!--form-->
                      <?php $form = ActiveForm::begin([
                            'id' => 'forgot-form',
                            'options' => ['class' => ''],
                            'action' => ['default/forgotpassword'],
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                            'enableAjaxValidation' => true,

                            //'fieldConfig' => [
                            //    //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                            //    //'labelOptions' => ['class' => 'col-lg-1 control-label'],
                            //],
                        ]); ?>

                      <div class="modal-body">
                          <p>Enter your e-mail address below to reset your password.</p>
                          <!--<input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">-->
                            <?= $form->field($user1, 'email_id',['inputOptions'=>array('placeholder'=>'Email ID','autocomplete'=>"off",'class'=>"form-control placeholder-no-fix")])->label(false); ?>
                      </div>
                      <div class="modal-footer">
                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                          <!--<button class="btn btn-success" type="button">Submit</button>-->
                          <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-flat']) ?>
                      </div>
                      <?php ActiveForm::end(); ?>
                      <!--end form-->
                  </div>
              </div>
          </div>
          <!-- modal -->


<script type="text/javascript">
     
    $(".checkbox").addClass("icheck");

    var form1 = $('#forgot-form');
    var error1 = $('.alert-danger', form1);
    var success1 = $('.alert-success', form1);
    form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            rules: {
                "Users[email]": {
                    required: true,
                    email:true
                },
                
            },
            
            onkeyup: false,

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

</script>

   