<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Change Password</h1>

<?php echo form_open('main/password_reset'); ?>
<form>
	<label>New Password: </label>
	<input type="password" id="new-password" name="new-password" required/>

	<br>
	<br>

	<input type="submit" id="submit" name="submit" value="Change Password"/>
</form>
<?php //echo $this->session->flashdata('error');?>
<br>
<?php echo $this->session->flashdata('password_same_error');?>
<br>
<?php echo $this->session->flashdata('password_type_error');?>
<br>
<?php echo $this->session->flashdata('password_length_error');?>
