<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ($this->session->userdata('email') == '') {
	redirect(base_url() . 'main/homepage');
	echo "You must be logged in to access that page";
}
?>

<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
<div class="container">
	<form method="post" id="upload_form" align="center" enctype="multipart/form-data">
		<input type="file" name="image_file" id="image_file" />
		<input type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" />
	</form>
	<div id="uploaded_image">

	</div>
</div>
</body>
</html>
<script>
	$(document).ready(function(){
		$('#upload_form').on('submit', function(e){
			e.preventDefault();
			if($('#image_file').val() == '')
			{
				alert("Please Select the File");
			}
			else
			{
				$.ajax({
					url:"<?php echo base_url(); ?>main/ajax_upload",
					method:"POST",
					data:new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					success:function(data)
					{
						$('#uploaded_image').html(data);
					}
				});
			}
		});
	});
</script>
