<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Homepage</h1>

<?php echo $this->session->flashdata('error');?>

<?php
if (isset($_SESSION['name'])) {
	echo '<h2>Welcome '.$_SESSION['name'].'</h2>';
}
;?>

<?php $k=0 ?>
<?php if (isset($video_list))
	echo "<ul>";
	echo "<tr>";
	foreach($video_list as $video): ?>
		<td>
			<a href="video_player/<?php echo $video['video_id'];?>"><?php echo $video['video_name'];?></a>
			<p><?php echo $video['upload_date'];?></p>
			<video id="video" class="video-js vjs-default-skin" width="320" height="180" controls>
				<source src="<?php echo $video['filepath'];?>">
			</video>
			<br>
			<br>
		</td>
<!--		--><?php //$k++; ?>
<!--		--><?php //if($k%3==0)
			echo "</tr>";
			echo "<br>";
//		?>
<?php endforeach; echo "</ul>";?>

<!--this variable to display 3 videos per row-->
<?php //if (isset($video_list)) {
//	$k = 0;
//	foreach($video_list as $video) {
//		echo "<tr>";
//		echo "<td>";
//		echo "<a href='video_player/".$video['video_id']."'".">".$video['video_name']."</a>";
//		echo "<br>";
//		echo "<video id='video'" . " " . "class='video-js'" . " " . "vjs-default-skin'" . " " .
//			"width='320'" . " " . "height='180'" . " " . "controls" . ">";
//		echo "<source src='".$video['filepath']."'".">";
//		echo "</video>";
//		echo "<br>";
//		echo "</td>";
//		echo "<br>";
//		echo "<br>";
//
//		echo "</tr>";
//
//		$k++;
//		if ($k % 3 == 0) {
//			echo "<tr>";
//		}
//	}
//};?>
