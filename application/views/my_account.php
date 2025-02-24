<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ($this->session->userdata('email') == '') {
	redirect(base_url() . 'main/homepage');
	echo "You must be logged in to access that page";
}
?>

<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>

<h1>My Account</h1>

<?php echo $this->session->flashdata("email_verification"); ?>

<?php echo form_open_multipart('main/image_upload');?>
<form class="dropzone" id="fileupload">
	<label>Profile Image Upload</label><br/>
	<input type="file" id="profile-image" name="profile-image"/>
	<input type="submit" id="submit" name="submit" value="Upload Profile Image"/>
</form>

<!--image manipulation next-->
<div id="profile-image" >
	<h3>Profile Image: </h3>
	<?php if (isset($profile_image_filepath)) ?>
	<img src='<?php echo $profile_image_filepath;?>' width="90" height="60"/>
</div>
<?php echo $this->session->flashdata("image_upload_error"); ?>

<?php echo '<h2>User ID: '.$_SESSION['user_id'].'</h2>';?>

<?php echo '<h3>Email Verified: '.$_SESSION['is_verified'].'</h3>';?>
<?php if ($_SESSION['is_verified'] == 'no') {
	echo '<a href="send_verification_email">Send Verification Email</a>';
}?>

<?php echo '<h3>Security Questions Set: '.$_SESSION['security_questions_set'].'</h3>';?>
<?php if ($_SESSION['security_questions_set'] == 'no') {
	echo '<a href="security_questions_page">Setup Security Questions</a>';
}?>

<?php echo '<h2>Email: '.$_SESSION['email'].'</h2>';?>
<?php echo form_open('main/change_email'); ?>
<form>
	<label>Change Email</label>
	<input type="email" id="change-email" name="change-email"/>
	<input type="submit" name="submit" value="Change Email"/>
</form>
<?php echo $this->session->flashdata("change_email_error"); ?>

<?php echo '<h2>Username: '.$_SESSION['username'].'</h2>';?>
<?php echo form_open('main/change_username'); ?>
	<form>
		<label>Change Username</label>
		<input type="text" id="change-username" name="change-username"/>
		<input type="submit" name="submit" value="Change Username"/>
	</form>
<?php echo $this->session->flashdata("change_username_error"); ?>

<?php //echo '<h2>Name: '.$_SESSION['name'].'</h2>';?>
<?php //echo form_open('main/change_name'); ?>
<!--<form>-->
<!--	<label>Change Name</label>-->
<!--	<input type="text" id="change-name" name="change-name"/>-->
<!--	<input type="submit" name="submit" value="Change Name"/>-->
<!--</form>-->
<?php //echo $this->session->flashdata("change_name_error"); ?>

<div id="ajax-name">
	<?php echo '<h2>Name: '.$_SESSION['name'].'</h2>';?>
</div>
<form method="post" id="change-name-ajax-form" enctype="text/plain">
	<label>Change Name With Ajax</label>
	<input type="text" name="change-name-ajax" id="change-name-ajax" />
	<input type="submit" name="change-name-ajax-submit" id="change-name-ajax-submit" value="Change Name" class="btn btn-info" />
</form>
<script>
	$(document).ready(function(){
		$('#change-name-ajax-form').on('submit', function(e){
			e.preventDefault();
			if($('#change-name-ajax').val() == '') {
				alert("Error: name cannot be empty");
			} else if (!/^[A-Za-z]+$/.test($('#change-name-ajax').val())) {
				alert("Error: name has invalid characters")
			} else {
				$.ajax({
					url:"<?php echo base_url(); ?>main/change_name_ajax",
					method:"POST",
					data:new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success:function(data)
					{
						$('#ajax-name').html('<h2>Name: ' + data + '</h2>');
					}
				});
			}
		});
	});
</script>

<?php echo '<h2>Birthday: '.$_SESSION['birthday'].'</h2>';?>
<?php echo form_open('main/change_birthday'); ?>
<form>
	<label>Change Birthday</label>
	<input type="date" id="change-birthday" name="change-birthday"/>
	<input type="submit" name="submit" value="Change Birthday"/>
</form>
<?php echo $this->session->flashdata("change_birthday_error"); ?>

<a href="<?php echo base_url() . 'main/verify_security_questions';?>">Change Password</a>

<?php //echo $this->session->flashdata('error');

