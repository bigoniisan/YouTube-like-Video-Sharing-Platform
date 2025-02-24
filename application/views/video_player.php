<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<h1>Video Player</h1>

<?php if (isset($video_data))?>
<video id="video" class="video-js vjs-default-skin" width="1280" height="720" controls>
	<source src="<?php echo $video_data['filepath'];?>">
</video>
<h2><?php echo $video_data['video_name'];?></h2>

<?php echo 'Likes/dislikes: ' . $video_data['video_likes'] . '/' . $video_data['video_dislikes'];?>

<?php echo form_open('main/like_video/' . $video_data['video_id']);?>
<form>
	<input type="submit" name="like-video" value="Like"/>
</form>

<?php echo form_open('main/dislike_video/' . $video_data['video_id']);?>
<form>
	<input type="submit" name="dislike-video" value="Dislike"/>
</form>

<a href="http://www.facebook.com/sharer.php?s=100
<?php echo "&p[summary]=testetsetestset";?>
<?php echo "&p[url]=".base_url()."main/video_player/".$video_data['video_id'];?>
<?php echo "&p[title]=TEST";?>
">
	Share to Facebook
</a>

<h1>Comments</h1>
<?php echo form_open('main/submit_comment/' . $video_data['video_id']);?>
<form>
	<label for="show-name-as">Show my comment as: </label>
	<select name="show-name-as" id="show-name-as" class="form-control" >
		<option value="<?php if(isset($_SESSION['user_id'])) echo $_SESSION['name'];?>"><?php if(isset($_SESSION['user_id'])) echo $_SESSION['name'];?></option>
		<option value="anonymous">Anonymous</option>
	</select>
	<input type="text" name="comment" placeholder="Add a comment" required/>
	<input type="submit" name="submit" value="Comment"/>
</form>


<?php if (isset($comments))
foreach($comments as $comment): ?>
<td>
	<p><?php if($comment['is_anonymous']) {
			echo "Anonymous";
		} else {
			echo $comment['name'];
		}
		echo ' ' . $comment['date'];
	?></p>
	<p><?php echo $comment['comment'];?></p>
</td>

<?php endforeach;?>

<?php if (!isset($video_data)) {
	echo "Error: No video with that ID found.";
}?>

<?php echo $this->session->flashdata('error');?>

