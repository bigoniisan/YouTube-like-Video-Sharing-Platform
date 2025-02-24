<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<h1>Setup Security Questions</h1>

<?php echo form_open('main/setup_security_questions')?>
<form>
	<label>Security Question 1</label>
	<br/>
	<input type="text" id="q1" name="q1" required/>
	<br>
	<label>Security Answer 1</Label>
	<br>
	<input type="text" id="a1" name="a1" required/>
	<br>

	<label>Security Question 2</label>
	<br/>
	<input type="text" id="q2" name="q2" required/>
	<br>
	<label>Security Answer 2</Label>
	<br>
	<input type="text" id="a2" name="a2" required/>
	<br>

	<label>Security Question 3</label>
	<br/>
	<input type="text" id="q3" name="q3" required/>
	<br>
	<label>Security Answer 3</Label>
	<br>
	<input type="text" id="a3" name="a3" required/>
	<br>

	<input type="submit" id="submit" name="submit" value="Continue"/>
</form>
<?php echo $this->session->flashdata("error"); ?>
