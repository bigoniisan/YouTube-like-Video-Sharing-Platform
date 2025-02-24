<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Login Page</h1>

<?php echo form_open('main/login_submit')?>
<form>
	<label>Email</label>
	<input type="email" id="email" name="email" value="<?php if(get_cookie('email')) {
		echo get_cookie('email');
	}?>" required/>
	<br/>
	<label>Password</label>
	<input type="password" id="password" name="password" value="<?php if(get_cookie('password')) {
		echo get_cookie('password');
	}?>" required/>
	<br/>
	<input type="checkbox" id="remember-me" name="remember-me"
		<?php if (get_cookie('email')) { ?> checked="checked" <?php } ?>
	/>
	<label>Remember Me?</label>

	<br/>
	<a href="<?php echo base_url() . 'main/forgot_password';?>">Forgot Password?</a>
	<br>

	<input type="submit" id="submit" name="submit" value="Log In"/>

</form>
<?php echo $this->session->flashdata("error"); ?>
