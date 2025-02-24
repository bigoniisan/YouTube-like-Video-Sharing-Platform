<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Verify Security Questions</h1>

<?php echo form_open('main/security_questions_verification'); ?>
<form>
	<br>

	<label><?php echo $security_questions['q1'];?></label>
	<input type="text" id="a1" name="a1" required/>
	<br>
	<br>

	<label><?php echo $security_questions['q2'];?></label>
	<input type="text" id="a2" name="a2" required/>
	<br>
	<br>

	<label><?php echo $security_questions['q3'];?></label>
	<input type="text" id="a3" name="a3" required/>
	<br>
	<br>

	<input type="submit" id="submit" name="submit" value="Continue"/>
</form>
<?php echo $this->session->flashdata('error');?>

