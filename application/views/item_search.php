<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Video Search</h1>

<?php //$k=0 ?>
<?php if(isset($search_result))
foreach($search_result as $item):?>
	<td>
		<a href="video_player/<?php echo $item['video_id'];?>"><?php echo $item['video_name'];?></a>
		<br>
		<video id="video" class="video-js vjs-default-skin" width="320" height="180" controls>
			<source src="<?php echo $item['filepath'];?>">
		</video>
		<br>
	</td>
<br>
<br>
<!--	<br/>-->
<!--	--><?php //$k++;
//	if($k%3==0)
//		echo "<tr>";
//	?>
<?php endforeach;?>

<?php if(!isset($search_result)) {
	echo "No videos found";
}?>
