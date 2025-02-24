<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Password Change Authentication Code</h1>

<?php echo form_open('main/verify_password_change_authentication_code')?>
<form>
	<label>Please enter your verification code:</label>
	<br/>
	<input type="text" id="verification-code" name="verification-code" required/>
	<br>
	<input type="submit" id="submit" name="submit" value="Submit Code"/>
</form>
<?php echo $this->session->flashdata("code_verification"); ?>

<br>
<a href="forgot_password">Resend verification code</a>
