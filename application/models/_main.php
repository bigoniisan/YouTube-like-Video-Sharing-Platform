<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class _main extends CI_Model
{

	public function verify_user($email, $password)
	{
		$this->db->where('email', $email);
		$this->db->where('password', $password);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_user($email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_user_by_id($user_id)
	{
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function user_exists($email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function user_exists_username($username)
	{
		$this->db->where('username', $username);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function update_user($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);
	}

	public function insert_user($data)
	{
		$this->db->insert('users', $data);
	}

	public function get_all_emails()
	{
		$this->db->select('email');
		$query = $this->db->get('users');
		return $query->result_array();
	}

	public function get_user_details($data=array())
	{
		$this->db->where('email', $data['email']);
		$query = $this->db->get('users');
		return $query->result_array();
	}

	public function check_password_same($user_id, $password)
	{
		$this->db->where('user_id', $user_id);
		$sql = "SELECT password FROM users 
			WHERE users.user_id = '$user_id'
			AND users.password = '$password'";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function users_count()
	{
		$query = $this->db->get('users');
		return $query->num_rows();
	}

	public function generate_user_id()
	{
		return $this->users_count() + 1;
	}

	public function insert_video($data)
	{
		$this->db->insert('videos', $data);
	}

	public function videos_count()
	{
		$query = $this->db->get('videos');
		return $query->num_rows();
	}

	public function generate_video_id()
	{
		return $this->videos_count() + 1;
	}

	public function get_videos()
	{
		$query = $this->db->get('videos');
		return $query->result_array();
	}

	public function set_user_profile_image($data)
	{
		$this->db->where('user_id', $data['user_id']);
		$this->db->update('users', $data);
	}

	public function search_videos_by_name($search_name)
	{
		$this->db->where('video_name', $search_name);
		$query = $this->db->get('videos');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_video_by_id($video_id)
	{
		$this->db->where('video_id', $video_id);
		$query = $this->db->get('videos');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function search_videos_by_name_contains($search_name)
	{
		// split search terms into array
		$search_terms = explode(' ', $search_name);
		foreach($search_terms as $search_term) {
			$this->db->or_like('video_name', $search_term);
		}
		$query = $this->db->get('videos');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_video_likes($video_id)
	{
		$this->db->where('video_id', $video_id);
		$this->db->select('video_likes');
		$query = $this->db->get('videos');
		return $query->result_array();
	}

	public function get_video_dislikes($video_id)
	{
		$this->db->where('video_id', $video_id);
		$this->db->select('video_dislikes');
		$query = $this->db->get('videos');
		return $query->result_array();
	}

	public function update_video($video_id, $data)
	{
		$this->db->where('video_id', $video_id);
		$this->db->update('videos', $data);
	}

	public function insert_security_questions($data)
	{
		$this->db->insert('security_questions', $data);
	}

	public function get_user_security_questions($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->select('q1');
		$this->db->select('q2');
		$this->db->select('q3');
		$query = $this->db->get('security_questions');
		return $query->result_array();
	}

	public function verify_security_questions($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('a1', $data['a1']);
		$this->db->where('a2', $data['a2']);
		$this->db->where('a3', $data['a3']);
		$query = $this->db->get('security_questions');
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function comments_count()
	{
		$query = $this->db->get('comments');
		return $query->num_rows();
	}

	public function generate_comment_id()
	{
		return $this->comments_count() + 1;
	}

	public function insert_comment($data)
	{
		$this->db->insert('comments', $data);
	}

	public function update_comment($comment_id, $data)
	{
		$this->db->where('comment_id', $comment_id);
		$this->db->update('comments', $data);
	}

	public function get_all_video_comments($video_id)
	{
		$this->db->where('video_id', $video_id);
		$query = $this->db->get('comments');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}
}
