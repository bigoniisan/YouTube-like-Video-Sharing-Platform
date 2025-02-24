<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ($this->session->userdata('email') == '') {
	redirect(base_url() . 'main/homepage');
	echo "You must be logged in to access that page";
}
?>

<head>
	<script type="text/javascript" src="../../resources/dropzone.js"></script>
	<link rel="stylesheet" href="../../resources/dropzone.js">
</head>

<h1>Upload Videos</h1>

<?php echo form_open_multipart('main/upload_video'); ?>
<form class="dropzone" id="fileupload">
	<label>Upload Videos (Supported file formats: mov, mpeg4, mp4, avi, wmv, mpegps, flv, 3gpp, webm, hevc)</label><br>
	<input type="file" id="userfile[]" name="userfile[]" multiple required /><br><br>
<!--	<label>Video Title (Required)</label><br>-->
<!--	<input type="text" id="filename" name="filename" required />-->
	<br><br>
	<input type="submit" id="submit" name="submit" value="Upload Videos"/>
</form>
<?php echo $this->session->flashdata("error"); ?>
