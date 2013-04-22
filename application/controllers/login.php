<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		//$this->permission->is_logged_in();
		//load model
		$this->load->model('user_model');
		$this->load->helper('url');
		$this->load->library('session');

	}

	//load to view
	function index()
	{

		$this->load->view('login/login');
	}

	//login user credentials
	function validate_credentials()
	{
		$this->session->sess_destroy();

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');

		//$username = preg_replace('/\s\s+/','', $username);
		//$username = $this->removeUnwantedString($username);
		$password = preg_replace('/\s\s+/','', $password);
		if ($this->form_validation->run() !== FALSE){

			if($query = $this->user_model->validate_admin($username,$password))
			{
					$id = $query->id;

					$username = $query->username;
					$role_id = $query->role_id;
					$data = array(
								'id' => $id,
								'username' => $query->username,
								'role_id'=>$query->role_id,
								'is_logged_in' => true,
								'is_logged_admin_in' => true,
								'hash' => sha1($id.$query->role_id.HASHTOKENADMIN)
								);

					$this->session->set_userdata($data);

					redirect('dashboard/index');
			}
			else
			{
				$this->session->set_flashdata('error_login', 'Invalid username or password.');

				redirect('login/index');
			}

		}else{
			$this->session->set_flashdata('error_login', 'Invalid username or password.');

			redirect('login/index');
		}
	}

	function forgot_password()
	{
		$email_address = $this->input->post('email_address');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_address', 'Email Address', 'required|trim|valid_email');
		if ($this->form_validation->run() == FALSE){
			$data = array();
			$data['page'] = 'forgot_pass';
			$this->load->view('login/login',$data);
		}else{
			$data = array (
				'email_address'=>$email_address
			);
			//$rand_pass = rand(25,100000000);
			## generate random alpha numeric password ##
			$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$rand_pass = '';
			for ($i = 0; $i < 10; $i++) {
				$rand_pass .= $characters[rand(0, strlen($characters) - 1)];
			}
		
			$password = $this->encode($rand_pass);
			$search_param = array (
				'password'=>$password
			);

				if ($query = $this->user_model->check_email_address($data))
				{
					$this->user_model->update_password($search_param,$email_address);
					$to = $email_address;
					$subject = 'Alphacrossing system request password';
					$message = "Kindly be inform that we setup your temparory password as '".$rand_pass."'. Please use this temparory password for your login, and once successful login remember update your password";
					$headers = 'From: <no-reply>';
					mail($to, $subject, $message, $headers);
					$this->session->set_flashdata('success_login', 'Your temporary password has been send out to your email');
					redirect("login/login");
				}
				else
				{
					$this->session->set_flashdata('error_login', 'Email address was not found in our system.');
					redirect("login/login");
				}
		}

	}
	
	function encode($text)
	{
		$result = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND));
		return trim(base64_encode($result));
	}

	function logout()
	{
		//if($this->session->userdata('role_id')==ADMIN)
		//{
			$this->session->sess_destroy();
			redirect('login/index');
		//}
	}

	public function removeUnwantedString($string){
		$new_string = preg_replace("/[^A-Za-z0-9]/", "", $string);
		return $new_string;
	}

}//end of class
?>