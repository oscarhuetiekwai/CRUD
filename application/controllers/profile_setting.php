<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//This controllar is proceed profile setting
class Profile_setting extends MY_Controller {

	public function __construct()
    {
        parent::__construct();
      	//$this->permission->is_logged_in();
		//$this->permission->superadmin_admin_only();
		$this->load->model('user_model');

    }


	function index()
	{
		$id = $this->session->userdata('id');

		$data = array();

		if($query = $this->user_model->view_admin($id))
		{
			$data['admin_records'] = $query;
		}
		$data['page'] = 'profile_setting';

		$data['main'] = 'profile/index';
		$this->load->view('template/template',$data);

	}

	function check_admin_exist()
	{
			$id = ($this->input->post('admin_id'));
			$admin_username = trim($this->input->post('admin_username'));

			if(!$this->user_model->check_admin_exist($admin_username,$id))
			{
				$this->form_validation->set_message('check_admin_exist', 'The %s already exist.');
				return false;
			}

			return true;
	}

		## profile Setting
	function update_profile()
	{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Username', 'required|trim');
			$this->form_validation->set_rules('email_address', 'Email Address', 'required|trim');
			if($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', 'Password', 'required|trim');
				$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[password]');
			}
			$id = $this->session->userdata('staff_id');
			//$id = "1";
			$hash = md5($id.SECRETTOKEN);
			if ($this->form_validation->run() == FALSE)
			{
				$data = array();
				$data['page'] = 'profile_setting';
				if($query = $this->user_model->view_admin($id))
				{
					$data['admin_records'] = $query;
				}

				//$data['page'] = 'admin_profile';

				$data['main'] = 'profile/index';
				$this->load->view('template/template',$data);

			}
			else
			{
				$email_address = $this->input->post('email_address');
				$data = array('email_address'=>$email_address);

				if($this->input->post('password'))
				{
					$password = $this->input->post('password');
					$password = $this->encode($password);
					$data = array('password'=>$password) + $data ;
				}
					if ($this->user_model->edit_admin($data,$id))
					{
						$this->session->set_flashdata('success', 'Your profile setting have been successfully updated');
						redirect("profile_setting/index/$id/$hash");
					}
					else
					{
						$this->session->set_flashdata('error', 'Error. Please try again.');
						redirect("profile_setting/index/$id/$hash");
					}
			}
	}


	function encode($text)
	{
		$result = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND));
		return trim(base64_encode($result));
	}


}//end of class
?>