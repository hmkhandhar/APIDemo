<?php

return [
		'apptitle' => 'Demo Project',
		'appName' => 'Demo Project',
		'adminEmail' => 'admin@MHK.com',
		'userimage' => 'img/uploads/users/',
		'appcookiename' => 'DemoProject',
		'productimage' => 'img/uploads/products/',
		'msg_success' =>'<div class="alert alert-dismissable alert-success fade in">
												<button data-dismiss="alert" class="close close-sm" type="button">
													<i class="fa fa-times"></i>
												</button>',
		'msg_error' =>  '<div class="alert alert-dismissable alert-block alert-danger fade in">
							<button data-dismiss="alert" class="close close-sm" type="button">
									<i class="fa fa-times"></i>
										</button>',
		'msg_end' => '</div>',
		'encryption_key'=>'}{(**&%%^$%AHGHSjkgh#$%#&*^$%',
		'response_text'=>array(200=>"Success",400=>'Bad Request',401=>'Unauthorized',403=>'Forbidden',404=>'Not Found',500=>'Internal Server Error',601=>'Data Dupliacation',602=>'Could Not Save',603=>'No data found'),
		'msg_success' =>'<div class="alert alert-dismissable alert-success fade in flash_msg ">
																	<button data-dismiss="alert" class="close close-sm" type="button">
																			<i class="fa fa-times"></i>
																	</button>
																	<strong>Success!</strong> ',
		'msg_error' =>  '<div class="alert alert-dismissable alert-block alert-danger fade in flash_msg">
																	<button data-dismiss="alert" class="close close-sm" type="button">
																			<i class="fa fa-times"></i>
																	</button>
																	<strong>Error!</strong> ',
		'msg_end' => '</div>',
		'successfully_logged_in'=>'Logged in successfully',
		'register_success_message'=>'Register successfully',
		'email_exist_already'=>'Email Exist Already',
		'invalid_login_1'=>'Email Address & Password are wrong',
		'account_deactivated'=>'Account Deactivated',
		'exist_already'=>'Exist Already',
		'error_forgot_password_social'=>'forgot_password',
		'forgot_password_link_sent'=>'Reset password link has been sent to your registered E-mail ID.',
		'error_forgot_password_email_not_found'=>'Forgot Password Email Not Found',
		'success_password_changed'=>'Password Changed Success',
		'error_invalid_old_password'=>'Password Is Wrong',
		'error_user_have_not_access'=>'User Don\'t have Permisstion',
		'error_invalid'=>'Invalid User',
		'social_type_not_found'=>'You try login another social account. Only Login allow in Facebook and Google.',
];
