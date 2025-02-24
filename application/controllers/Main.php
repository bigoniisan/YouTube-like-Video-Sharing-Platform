<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class main extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('_main');
		$this->load->helper(array(
			'captcha',
			'cookie',
			'form',
			'url'
		));
		$this->load->library(array(
			'encryption',
			'form_validation',
			'session'
		));
	}

	public function index()
	{
		$this->homepage();
	}

	public function homepage()
	{
		$this->load_navbar();
		$video_list = $this->_main->get_videos();
		$this->load->view('homepage', array(
			'video_list' => $video_list
		));
	}

	public function login()
	{
		$this->load_navbar();
		$this->load->view('login');
	}

	public function signup()
	{
		$this->load_navbar();
		$this->load->view('signup');
	}

	public function my_account()
	{
		$this->load_navbar();
		$this->load->view('my_account');
	}

	public function upload()
	{
		$this->load_navbar();
		$this->load->view('upload');
	}

	public function forgot_password()
	{
		$this->load_navbar();
		$this->load->view('forgot_password');
	}

	public function verify_security_questions()
	{
		if ($this->session->userdata('security_questions_set') == 'no') {
			// security questions not set
			$this->session->flashdata('error', "Security questions must be set before changing password");
			$this->my_account();
		} elseif ($this->session->userdata('security_questions_set') == 'yes') {
			// security questions set

			// retrieve security questions then pass as data to security question verification page
			$user_id = $this->session->userdata('user_id');
			$security_questions_array = $this->_main->get_user_security_questions($user_id);
			$data = array(
				'security_questions' => $security_questions_array[0]
			);
			$this->load_navbar();
			$this->load->view('verify_security_questions', $data);
		} else {
			// wtf
			echo 'wtf, something is very wrong';
			$this->my_account();
		}
	}

	public function verify_security_questions_forgot_password()
	{
		$user = $this->_main->get_user($this->session->userdata('email'));
		$user_id = $user[0]['user_id'];
		$security_questions_array = $this->_main->get_user_security_questions($user_id);
		$data = array(
			'security_questions' => $security_questions_array[0]
		);
		$this->load_navbar();
		$this->load->view('verify_security_questions', $data);
	}

	public function profile_image_upload()
	{
		$data['title'] = "Upload Image using Ajax JQuery in CodeIgniter";
		$this->load_navbar();
		$this->load->view('profile_image_upload', $data);
	}

	public function verification_code_input()
	{
		$this->load_navbar();
		$this->load->view('verification_code_input');
	}

	public function verification_code_input_forget_password()
	{
		$this->load_navbar();
		$this->load->view('verification_code_input_forget_password');
	}

	public function video_player($video_id)
	{
		$result_array = $this->_main->get_video_by_id($video_id);
		$video_data = $result_array[0];
		if (!$video_data) {
			// no video with that ID exists
			$this->load_navbar();
			$this->load->view('video_player');
			echo "No video with that ID exists";
		} else {
			$data = array(
				'video_data' => $video_data,
				'comments' => $this->_main->get_all_video_comments($video_id)
			);
			$this->load_navbar();
			$this->load->view('video_player', $data);
		}
	}

	public function security_questions_page()
	{
		$this->load_navbar();
		$this->load->view('setup_security_questions');
	}

	public function load_navbar()
	{
		if ($this->_main->get_user_by_id($this->session->userdata('user_id'))) {
			$this->load->view('templates/navbar_logged_in');
		} else {
			$this->load->view('templates/navbar_logged_out');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('password');
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('birthday');
		$this->session->unset_userdata('is_verified');
		$this->session->unset_userdata('verification_code');
		$this->session->unset_userdata('security_questions_set');

		$this->homepage();
	}

	public function login_submit()
	{
		$data = array(
			'email' => $this->input->post('email'),
			'password' => $this->input->post('password'),
			'remember-me' => $this->input->post('remember-me')
		);

		$user = $this->_main->get_user($data['email']);

		if ($user == FALSE) {
			// user with email does not exist
			$this->session->set_flashdata('error', 'Invalid email');
			$this->load_navbar();
			$this->load->view('login');
		} else {
			// user with email exists
			if (!password_verify($data['password'], $user[0]['password'])) {
				// password does not match hash in db
				$this->session->set_flashdata('error', 'Password incorrect');
				$this->load_navbar();
				$this->load->view('login');
			} else {
				if ($data['remember-me']) {
					set_cookie('email', $data['email'], time() + (60));
					set_cookie('password', $data['password'], time() + (60));
				} else {
					delete_cookie('email');
					delete_cookie('password');
				}

				$is_verified = '';
				if ($user[0]['is_verified']) {
					$is_verified = 'yes';
				} else {
					$is_verified = 'no';
				}

				$security_questions_set = '';
				if ($user[0]['security_questions_set']) {
					$security_questions_set = 'yes';
				} else {
					$security_questions_set = 'no';
				}

				$session_data = array(
					'user_id' => $user[0]['user_id'],
					'email' => $user[0]['email'],
					'username' => $user[0]['username'],
					'password' => $user[0]['password'],
					'name' => $user[0]['name'],
					'birthday' => $user[0]['birthday'],
					'is_verified' => $is_verified,
					'security_questions_set' => $security_questions_set
				);
				$this->session->set_userdata($session_data);

				$this->homepage();
			}
		}
	}

	public function signup_submit()
	{
		$data = array(
			'user_id' => $this->_main->generate_user_id(),
			'email' => $this->input->post('email'),
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password'),
			'name' => $this->input->post('name'),
			'birthday' => $this->input->post('birthday'),
			'is_verified' => FALSE,
			'security_questions_set' => FALSE
		);

		if ($this->_main->user_exists($data['email'])) {
			// check email unique
			$this->session->set_flashdata('email_error', 'Email already exists');
			$this->signup();
		} elseif ($this->_main->user_exists_username($data['username'])) {
			// check username unique
			$this->session->set_flashdata('username_error', 'Username already exists');
			$this->signup();
		} elseif (!preg_match('([a-zA-Z].*[0-9]|[0-9].*[a-zA-Z])', $data['password'])) {
			// if password does not contain both letters and numbers
			$this->session->set_flashdata('password_type_error', 'Password must contain both numbers and letters');
			$this->signup();
		} elseif (strlen($data['password']) < 6 || strlen($data['password'] > 12)) {
			// check password length between 6-12
			$this->session->set_flashdata('password_length_error', 'Password must be 6-12 characters');
			$this->signup();
		} elseif ($data['birthday'] > date('Y-m-d')) {
			// check birthday valid
			$this->session->set_flashdata('birthday_error', 'Birthday is invalid');
			$this->signup();
		} else {
			// signup success
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

			// insert user in db
			$this->_main->insert_user($data);

			// update session data
			$session_data = array(
				'user_id' => $data['user_id'],
				'email' => $data['email'],
				'username' => $data['username'],
				'password' => $data['password'],
				'name' => $data['name'],
				'birthday' => $data['birthday'],
				'is_verified' => 'no',
				'security_questions_set' => 'no'
			);
			$this->session->set_userdata($session_data);

			// setup security questions
			$this->load_navbar();
			$this->load->view('setup_security_questions');
		}
	}

	public function setup_security_questions()
	{
		$data = array(
			'user_id' => $this->session->userdata('user_id'),
			'q1' => $this->input->post('q1'),
			'q2' => $this->input->post('q2'),
			'q3' => $this->input->post('q3'),
			'a1' => $this->input->post('a1'),
			'a2' => $this->input->post('a2'),
			'a3' => $this->input->post('a3')
		);
		$this->_main->insert_security_questions($data);

		// update user in db
		$user_data = array(
			'user_id' => $this->session->userdata('user_id'),
			'security_questions_set' => TRUE
		);;
		$this->_main->update_user($data['user_id'], $user_data);

		// update session data
		$session_data = array(
			'security_questions_set' => 'yes'
		);
		$this->session->set_userdata($session_data);

		// redirect to my_account
		$this->my_account();
	}

	public function item_search()
	{
		$search_name = $this->input->post('search');
		$search_result = $this->_main->search_videos_by_name_contains($search_name);

		if (!$search_result) {
			// no results
			$this->load_navbar();
			$this->load->view('item_search');
		} else {
			$data = array(
				'search_result' => $search_result
			);
			$this->load_navbar();
			$this->load->view('item_search', $data);
		}
	}

	public function send_verification_email_forget_password()
	{
		$email = $this->input->post('email');
		if (!$this->_main->user_exists($email)) {
			$this->session->set_flashdata('error', 'Email does not exist');
			$this->forgot_password();
		} else {
			$verification_code = rand(1000, 9999);
			$session_data = array(
				'email' => $email,
				'verification_code' => (string) $verification_code
			);
			$this->session->set_userdata($session_data);

			// send verification email
			$to_email = $email;
			$email_subject = 'Password Reset Verification Code';
			$email_message = "Please verify your email." . "<br/><br/>" . "Verification Code: " .
				$verification_code . "<br/><br/>" . "Thanks," . "<br/>" . "SupaSexy69";
			$this->send_email($to_email, $email_subject, $email_message);

			// redirect to verification code input page
			$this->verification_code_input_forget_password();
		}
	}

	public function verify_password_change_authentication_code()
	{
		$input_code = $this->input->post('verification-code');
		if ($input_code == $this->session->userdata('verification_code')) {
			// successful verification
//			$this->load_navbar();
//			$this->load->view('change_password_page');
			$this->verify_security_questions_forgot_password();
		} else {
			// unsuccessful verification, redirect to verification code page
			$this->session->set_flashdata('code_verification', 'Incorrect verification code');
			$this->verification_code_input_forget_password();
		}
	}

	public function send_verification_email()
	{
		// set verification code
		$verification_code = rand(1000, 9999);
		$session_data = array(
//			'is_verified' => 'no',
			'verification_code' => (string) $verification_code
		);
		$this->session->set_userdata($session_data);

		// send verification email
		$to_email = $this->session->userdata('email');
		$email_subject = 'Signup Verification Code';
		$email_message = "Please verify your email." . "<br/><br/>" . "Verification Code: " .
			$verification_code . "<br/><br/>" . "Thanks," . "<br/>" . "SupaSexy69";
		$this->send_email($to_email, $email_subject, $email_message);

		// redirect to verification code input page
		$this->verification_code_input();
	}

	public function send_email($email, $subject, $message)
	{
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'mailhub.eait.uq.edu.au',
			'smtp_port' => 25,
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE
		);
		$this->load->library('email', $config);

		$this->email->from('noreply@infs3202-78c24710.uqcloud.net', 'SupaSexy69');
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);

		$this->email->send();
	}

	public function verify_email()
	{
		$input_code = $this->input->post('verification-code');

		if ($input_code == $this->session->userdata('verification_code')) {
			// successful verification

			// update user in db
			$data = array(
				'user_id' => $this->session->userdata('user_id'),
				'is_verified' => TRUE
			);
			$this->_main->update_user($data['user_id'], $data);

			$session_data = array(
				'is_verified' => 'yes'
			);
			$this->session->set_userdata($session_data);

			$this->my_account();
		} else {
			// unsuccessful verification, redirect to verification code page
			$this->session->set_flashdata('email_verification', 'Incorrect verification code');
			$this->verification_code_input();
		}
	}

	public function security_questions_verification()
	{
		$user = $this->_main->get_user($this->session->userdata('email'));
		$user_id = $user[0]['user_id'];
		$data = array(
			'a1' => $this->input->post('a1'),
			'a2' => $this->input->post('a2'),
			'a3' => $this->input->post('a3'),
		);

		if ($this->_main->verify_security_questions($user_id, $data)) {
			$this->load_navbar();
			$this->load->view('change_password_page');
		} else {
			// security answers incorrect
			$security_questions_array = $this->_main->get_user_security_questions($user_id);
			$data = array(
				'security_questions' => $security_questions_array[0]
			);

			$this->session->set_flashdata('error', "Answers to one or more security questions were incorrect");
			$this->load_navbar();
			$this->load->view('verify_security_questions', $data);
		}
	}

	public function password_reset()
	{
		$user = $this->_main->get_user($this->session->userdata('email'));
		$user_id = $user[0]['user_id'];
		$data = array(
			'password' => $this->input->post('new-password')
		);

		if (password_verify($data['password'], $user[0]['password'])) {
			// if input pw matches hashed pw in db
			$this->session->set_flashdata('password_same_error', 'New password must be different from previous password');
			$this->load_navbar();
			$this->load->view('change_password_page');
		} elseif (!preg_match('([a-zA-Z].*[0-9]|[0-9].*[a-zA-Z])', $data['password'])) {
			// if password does not contain both letters and numbers
			$this->session->set_flashdata('password_type_error', 'Password must contain both numbers and letters');
			$this->load_navbar();
			$this->load->view('change_password_page');
		} elseif (strlen($data['password']) < 6 || strlen($data['password'] > 12)) {
			// check password length between 6-12
			$this->session->set_flashdata('password_length_error', 'Password must be 6-12 characters');
			$this->load_navbar();
			$this->load->view('change_password_page');
		} else {
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

			$this->_main->update_user($user_id, $data);

			$this->homepage();
			echo "NOTIFICATION: Password successfully reset";
		}
	}

	public function ajax_upload()
	{
		if(isset($_FILES["image_file"]["name"]))
		{
			$config['upload_path'] = './image_upload/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$this->load->library('upload', $config);
			if(!$this->upload->do_upload('image_file'))
			{
				echo $this->upload->display_errors();
			}
			else
			{
				$data = $this->upload->data();
				echo '<img src="'.base_url().'image_upload/'.$data["file_name"].'" width="300" height="225" class="img-thumbnail" />';
			}
		}
	}

	public function upload_video()
	{
		$data = array();

		$count = count($_FILES['userfile']['name']);
		for ($i = 0; $i < $count; $i++) {
			if (!empty($_FILES['userfile']['name'][$i])) {
				// Define new $_FILES array - $_FILES['file']
				$_FILES['file']['name'] = $_FILES['userfile']['name'][$i];
				$_FILES['file']['type'] = $_FILES['userfile']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['userfile']['tmp_name'][$i];
				$_FILES['file']['error'] = $_FILES['userfile']['error'][$i];
				$_FILES['file']['size'] = $_FILES['userfile']['size'][$i];

				$config['upload_path'] = './uploads';
				$config['allowed_types'] = 'mp4|mov|mpeg4|avi|wmv|mpegps|flv|3gpp|webm|hevc';

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('file')) {
					echo "one or more unsuccessful uploads";
					$this->session->set_flashdata('error', $this->upload->display_errors());
				} else {
					echo "successful upload";
					$upload_data = $this->upload->data();
					$data[$i] = array(
						'video_id' => $this->_main->generate_video_id(),
						'video_name' => $upload_data['file_name'],
						'filepath' => base_url() . 'uploads/' . $upload_data['file_name'],
						'upload_date' => date('Y-m-d H:i:s'),
						'video_likes' => 0,
						'video_dislikes' => 0
					);
					$this->_main->insert_video($data[$i]);
				}
			} else {
				echo "big problem";
				$this->session->set_flashdata('error', 'One or more files do not have a name');
			}
		}
		$this->load_navbar();
		$this->load->view('upload', $data);
	}

//	public function upload_video()
//	{
//		$config['upload_path'] = './uploads';
//		$config['allowed_types'] = 'mp4';
//
//		$this->load->library('upload', $config);
//
//		if (!$this->upload->do_upload()) {
//			$this->session->set_flashdata('error', $this->upload->display_errors());
//			$this->upload();
//		} else {
//			$upload_data = $this->upload->data();
//			$data = array(
//				'video_id' => $this->_main->generate_video_id(),
//				'video_name' => $upload_data['file_name'],
//				'filepath' => base_url() . 'uploads/' . $upload_data['file_name'],
//				'upload_date' => date('Y-m-d H:i:s'),
//				'video_likes' => 0,
//				'video_dislikes' => 0
//			);
//			$this->_main->insert_video($data);
//			$this->upload();
//		}
//	}

	public function like_video($video_id)
	{
		if ($this->session->userdata('email') == '') {
			$this->session->set_flashdata('error', 'You must be logged in to do that');
		} else {
			$query = $this->_main->get_video_likes($video_id);
			$video_likes = $query[0]['video_likes'];
			$data = array(
				'video_likes' => $video_likes + 1
			);
			$this->_main->update_video($video_id, $data);
		}
		$this->video_player($video_id);
	}

	public function dislike_video($video_id)
	{
		if ($this->session->userdata('email') == '') {
			$this->session->set_flashdata('error', 'You must be logged in to do that');
			$this->video_player($video_id);
		} else {
			$query = $this->_main->get_video_dislikes($video_id);
			$video_dislikes = $query[0]['video_dislikes'];
			$data = array(
				'video_dislikes' => $video_dislikes + 1
			);
			$this->_main->update_video($video_id, $data);

			$this->video_player($video_id);
		}
	}

	public function submit_comment($video_id)
	{
		if ($this->session->userdata('email') == '') {
			$this->session->set_flashdata('error', 'You must be logged in to do that');
			$this->video_player($video_id);
		} else {
			$data = array(
				'comment_id' => $this->_main->generate_comment_id(),
				'video_id' => $video_id,
				'user_id' => $this->session->userdata('user_id'),
				'name' => $this->session->userdata('name'),
				'comment' => $this->input->post('comment'),
				'date' => date('Y-m-d H:i:s'),
				'is_anonymous' => FALSE
			);
			$show_name_as = $this->input->post('show-name-as');
			if ($show_name_as == 'anonymous') {
				$data['is_anonymous'] = TRUE;
			}
			$this->_main->insert_comment($data);

			$this->video_player($video_id);
		}
	}

	public function image_upload()
	{
		$config['upload_path'] = './images/';
		$config['allowed_types'] = 'jpg|jpeg|gif|png';
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('profile-image')) {
			$this->session->set_flashdata('image_upload_error', $this->upload->display_errors());
			$this->load_navbar();
			$this->load->view('my_account');
		} else {
			$upload_data = $this->upload->data();
			$data = array(
				'user_id' => $this->session->userdata('user_id'),
				'profile_image_filepath' => base_url() . 'images/' . $upload_data['file_name']
			);
			$this->_main->set_user_profile_image($data);

			$this->load_navbar();
			$this->load->view('my_account', $data);
		}
	}

	public function change_email()
	{
		$data = array(
			'email' => $this->input->post('change-email')
		);

		$user_id = $this->session->userdata('user_id');
		if (!$this->_main->user_exists($data['email'])) {
			$this->session->set_userdata($data);
			$this->_main->update_user($user_id, $data);
			$this->session->set_flashdata("change_email_error","Email changed successfully");
		} else {
			$this->session->set_flashdata("change_email","Error: Email already exists");
		}
		redirect(base_url() . 'main/my_account');
	}

	public function change_username()
	{
		$data = array(
			'username' => $this->input->post('change-username')
		);

		$user_id = $this->session->userdata('user_id');
		if (!$this->_main->user_exists_username($data['username'])) {
			$this->session->set_userdata($data);
			$this->_main->update_user($user_id, $data);
			$this->session->set_flashdata("change_username_error","Username changed successfully");
		} else {
			$this->session->set_flashdata("change_username","Error: Username already exists");
		}
		redirect(base_url() . 'main/my_account');
	}

	public function change_name()
	{
		$data = array(
			'name' => $this->input->post('change-name')
		);
//		if (!preg_match('([a-zA-Z])', $data['name'])) {
//			$this->session->set_flashdata("change_name_error","Invalid characters in name");
//			redirect(base_url() . 'main/my_account');
//		} else {

//		}
		$user_id = $this->session->userdata('user_id');
		$this->_main->update_user($user_id, $data);
		$this->session->set_userdata($data);
		$this->session->set_flashdata("change_name_error","Name changed successfully");
		redirect(base_url() . 'main/my_account');
	}

	public function change_name_ajax()
	{
		$data = array(
			'name' => $this->input->post('change-name-ajax')
		);

		$user_id = $this->session->userdata('user_id');
		$this->_main->update_user($user_id, $data);
		$this->session->set_userdata($data);
		echo $data['name'];
	}

	public function change_birthday()
	{
		$data = array(
			'birthday' => $this->input->post('change-birthday')
		);

		$user_id = $this->session->userdata('user_id');
		if ($data['birthday'] < date('Y-m-d')) {
			$user_id = $this->session->userdata('user_id');
			$this->_main->update_user($user_id, $data);
			$this->session->set_userdata($data);
			$this->session->set_flashdata("change_birthday_error","Birthday changed successfully");
		} else {
			$this->session->set_flashdata("change_birthday","Error: invalid birthday");
		}
		redirect(base_url() . 'main/my_account');
	}

}
