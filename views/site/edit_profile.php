<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

if($model->isNewRecord)
{
    $a="true";
    //$pageTitle = "Add New User";
}
else
{   
    $a="false";
    $model->password = "";
    if($model->dob == null || $model->dob == ""){
        $model->dob = "";
    }else{
        // $model->dob = date('d/m/Y',$model->dob);
    }
    
}


$path = Yii::$app->request->baseUrl;
$img = '/img/userImg.png';
if(isset(Yii::$app->user->identity->image) && Yii::$app->user->identity->image!=null)
{
  $img = Yii::$app->user->identity->image;
  $path = Yii::$app->request->baseUrl;
}

?>
<!-- Main Container -->
<!-- <div class="main-container"> -->
    <!-- <div class="page-section page-section-padding-lg"> -->
        <!-- <div class="container"> -->
            <!-- <div class="login-wrapper"> -->
                <!-- <div class="row"> -->
                    <!-- <div class="v-grid-container"> -->
                        <!-- <div class="v-grid col-sm-6">
                            <div class="col-lg-10 col-sm-12 col-lg-offset-2"> -->
                            <!-- <div class="col-sm-6"> -->
                                 <?php $form = ActiveForm::begin([
                                    'id'=>'users-form',
                                    'options' => ['enctype'=>'multipart/form-data'],
                                    'action'=>Yii::$app->request->baseUrl."/index.php/site/editprofile",
                                        'enableAjaxValidation'=>false,
                                        'enableClientValidation'=>false,
                                    
                                    // 'options' => ['enctype'=>'multipart/form-data'],
                                    // 'action'=>Yii::$app->request->baseUrl."/site/editprofile",
                                    // 'enableAjaxValidation'=>false,
                                    // 'enableClientValidation'=>false,
                                    ]); ?>
                                    
                                    <!-- <div class="col-lg-10 col-sm-12 col-lg-offset-2"> -->
                                    <!-- <div class="v-grid-container"> -->
                                    <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                        <div class="fileinput clearfix fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 100px; height: 100px; line-height: 100px;">
                                                <img src="<?=$path?>/<?=$img?>"/>
                                            </div>
                                            <div class="form-group">
                                                <span class="font-dinNext btn btn-default btn-file fileinput-new btn-round">
                                                    <span class="fileinput-new">Change Image</span>
                                                    <input type="hidden">
                                                    <input type="file" name="Users[image]" />
                                                </span>

                                                <!-- <a href="javascript:;" style="font-size: 15px;" class="font-dinNext btn btn-link fileinput-exists btn-sm text-uppercase" data-dismiss="fileinput">
                                                    <i class="fa fa-trash text-muted"></i>&nbsp;&nbsp;Remove Image</a> -->
                                            </div>
                                        </div>
                                        </div>
                                        </div>                                       
                                    </div>
                                    <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                        <?php echo  $form->field($model, 'name',['template' => "{input}\n{hint}\n{error}"])->textInput(['maxlength' => true ,'class'=>"form-control input-lg",'placeholder'=>"Full Name"])->label(false) ?>
                                            <span for="users-full_name" class="help-block"></span>                      
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                         <?php echo  $form->field($model,'email',['template' => "{input}\n{hint}\n{error}"])->textInput(['maxlength' => true ,'class'=>"form-control input-lg",'placeholder'=>"Email"])->label(false) ?>
                                            <span for="users-email" class="help-block"></span>
                                    </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                        <?php echo  $form->field($model, 'dob',['template' => "{input}\n{hint}\n{error}"])->textInput(['maxlength' => true ,'class'=>" dob form-control input-lg",'placeholder'=>"Birth Date"])->label(false) ?>
                                                <span for="users-dob" class="help-block"></span>
                                    </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                        <?php echo  $form->field($model, 'mobile',['template' => "{input}\n{hint}\n{error}"])->textInput(['maxlength' => true ,'class'=>"form-control input-lg",'placeholder'=>"Mobile Number"])->label(false) ?>
                                        <span for="users-mobile_no" class="help-block"></span>
                                    </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                        <?php echo  $form->field($model, 'address',['template' => "{input}\n{hint}\n{error}"])->textInput(['maxlength' => true ,'class'=>"form-control input-lg",'placeholder'=>"Address"])->label(false) ?>
                                        <span for="users-address" class="help-block"></span>
                                    </div>
                                        </div>
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-md-1">
                                    <div class="form-action">
                                        <button type="submit" class="btn btn-primary btn-block submit">Submit</button>
                                    </div>
                                    </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Page Section -->                    

                </div> <!-- End Main Container -->
            </div>
        </div>
    </div>
    





<!-- <script type="text/javascript"> 

    userid=0;
    <?php
    if(!$model->isNewRecord)
    {
        echo "userid = ".$model->id."; \n";
    }
    
    ?>
    // var FromEndDate = new Date();
    // $('.dob').datepicker({
    //     autoclose: true,
    //     format: 'dd/mm/yyyy',
    //     endDate: FromEndDate,
    // });
    
    // jQuery.validator.addMethod("imagetype", function(value, element) {
    //         return this.optional(element) || /^.*\.(jpg|png|jpeg)$/i.test(value);
    // }, "Plese Select .jpg .png or .jpeg Image");

    
    /*jQuery.validator.addMethod("checkemail", function (value, element) {
            var result = true;
            path = "<?php echo Yii::$app->request->baseUrl; ?>";
            checkemail = "/admin/users/checkemail";
            $.ajax({
                type:"POST",
                async: false,
                url: path+checkemail, // script to validate in server side
                data: {email: value,_csrf: yii.getCsrfToken(),id:userid},
                success: function(data) {
                    //alert(path+'--'+userid+'--'+data);
                    result = (data == 1) ? true : false;
                }
            });
            return result;
    }, "This email is already taken! Try another.");*/
    
    
    
   function validateEmail(email)
        {
         var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
         if (reg.test(email)){
         return true; }
         else{
         return false;
         }
        }
    
    // var ajax_request;
    //     $("#users-email").on('input propertychange change', function() {
    //         var val1 = $(this).val();
    //         var id1 =  <?php echo $model->id;?> ; //$('#userid').val();
            
    //         path = "<?php echo Yii::$app->request->baseUrl; ?>";
    //         // checkemail = "/admin/users/checkemail";
            
    //         if (validateEmail(val1)) {
    //             if(typeof ajax_request !== 'undefined')
    //                 ajax_request.abort();
    //             ajax_request = $.ajax({
    //                 type:"GET",
    //                 url:"<?=Yii::$app->request->baseUrl?>/admin/users/checkemail",
    //                 data:{email: val1,_csrf: yii.getCsrfToken(),id:userid},    // multiple data sent using ajax
    //                 success: function (result) {
    //                     console.log(result);
    //                     if (result == 1)
    //                     {
    //                         $('.field-users-email').find('.help-block-error').text('');
    //                         $('.submit').attr('disabled',false);
    //                     }
    //                     else
    //                     {
    //                        $('.field-users-email').find('.help-block-error').text('This email is already taken! Try another.');
    //                        $('.submit').attr('disabled',true);
    //                     }
    //                 }
    //             });
    //         }
    //     });
    

    var form1 = $('#users-form');
    var error1 = $('.alert-danger', form1);
    var success1 = $('.alert-success', form1);
    form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            rules: {
                "Users[full_name]": {
                    required: true,
                },
                "Users[email_id]": {
                    required: true,
                    email:true,
                    maxlength:30,
                    //checkemail:true,
                },
                
                "Users[dob]": {
                    required: true,
                },
                "Users[address]": {
                    required: true,
                },
                "Users[mobile_number]": {
                    required:true,
                      maxlength:10,
                      minlength:10,
                      digits:true
                },
                "Users[image]": {
                    required: false,
                   
                   imagetype:true
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
</script> -->
<style type="text/css">
    .form-group .help-block{
        color:red;
    }
    .fileinput-new{
        margin: 10px;
        padding: 10px;
    }
</style>