<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<nav>
	<a href="<?php echo base_url() . 'main/homepage';?>">
		Homepage
	</a>

	<?php echo form_open('main/item_search')?>
	<form>
		<input type="search" id="search" name="search" placeholder="Search">
		<input type="submit" id="submit" name="submit" value="Search"/>
	</form>

	<a href="<?php echo base_url() . 'main/signup';?>">
		Signup
	</a>
	<a href="<?php echo base_url() . 'main/login';?>">
		Login
	</a>
</nav>
