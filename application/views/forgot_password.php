<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Change Password</h1>

<?php echo form_open('main/send_verification_email_forget_password'); ?>
<form>
	<label>Email: </label>
	<input type="email" id="email" name="email" required/>

	<br>
	<br>

	<input type="submit" id="submit" name="submit" value="Send password change verification code"/>
</form>
<?php echo $this->session->flashdata('error');?>
