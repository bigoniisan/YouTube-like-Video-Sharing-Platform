<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Verify Email</h1>

<?php echo form_open('main/verify_email')?>
<form>
	<label>Please enter your verification code:</label>
	<br/>
	<input type="text" id="verification-code" name="verification-code" required/>
	<br>
	<input type="submit" id="submit" name="submit" value="Submit Code"/>
</form>
<?php echo $this->session->flashdata("email_verification"); ?>
