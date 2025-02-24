<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Signup Page</h1>

<?php echo form_open('main/signup_submit')?>
<form>
	<label>Email</label>
	<input type="email" id="email" name="email" required/>
	<br/>
	<label>Username</label>
	<input type="text" id="username" name="username" minlength="3" required/>
	<br>
	<label>Password</label>
	<input type="password" id="password" name="password" minlength="6" required/>
	<br/>
	<label>Name</label>
	<input type="text" id="name" name="name" required/>
	<br/>
	<label>Birthday</label>
	<input type="date" id="birthday" name="birthday" required/>
	<br/>

	<input type="submit" id="submit" name="submit" value="Sign Up"/>
</form>
<?php echo $this->session->flashdata("email_error"); ?>
<?php echo $this->session->flashdata("username_error"); ?>
<?php echo $this->session->flashdata("password_type_error"); ?>
<?php echo $this->session->flashdata("password_length_error"); ?>
<?php echo $this->session->flashdata("birthday_error"); ?>


