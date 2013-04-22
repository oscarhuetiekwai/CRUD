<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permission {

	private $_ci;

	function __construct()
	{
		$this->_ci = &get_instance();
	}

	public function is_logged_in_admin()
	{
		$is_logged_in = $this->_ci->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			redirect('login/admin');
		}
	}

	public function is_logged_in()
	{
		$is_logged_in = $this->_ci->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			redirect('login/index');
		}
	}

	public function allowed_function($description)
	{
		$this->_ci->load->model('role_model');

		$role_id = $this->_ci->session->userdata('role_id');

		$search_param = array('role'=>$role_id, 'description'=> $description );

		if($this->_ci->role_model->search_user_role($search_param))
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	public function superadmin_admin_only()
	{
		if(($this->_ci->session->userdata('role_id')!=SUPER_ADMIN)&&($this->_ci->session->userdata('role_id')!=ADMIN_ROLE))
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/error');
		}
	}


	public function superadmin_only()
	{
		if($this->_ci->session->userdata('role_id')!=SUPER_ADMIN)
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/error');
		}
	}

	public function admin_only()
	{
		if($this->_ci->session->userdata('role_id')!=ADMIN_ROLE)
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/error');
		}
	}

	public function user_only()
	{
		if($this->_ci->session->userdata('role_id')!=USER_ROLE)
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/error');
		}
	}


}

// END Reply Class

/* End of file Reply.php */
/* Location: ./application/libraries/Reply.php */