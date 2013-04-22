<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permission {

	private $_ci;

	function __construct()
	{
		$this->_ci = &get_instance();
	}

	public function is_logged_in()
	{


	}

	public function is_logged_in()
	{
		$is_logged_in = $this->_ci->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			redirect('login/index');
		}
	}

	public function superadmin_admin_only()
	{
		if(($this->_ci->session->userdata('role_id')!=SUPER_ADMIN)&&($this->_ci->session->userdata('role_id')!=ADMIN_ROLE))
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/index');
		}
	}

	public function superadmin_only()
	{
		if($this->_ci->session->userdata('role_id')!=SUPER_ADMIN)
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/index');
		}
	}

	public function admin_only()
	{
		if($this->_ci->session->userdata('role_id')!=ADMIN_ROLE)
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/index');
		}
	}

	public function dealer_only()
	{
		if($this->_ci->session->userdata('role_id')!=DEALER_ROLE)
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/index');
		}
	}

	public function user_only()
	{
		if($this->_ci->session->userdata('role_id')!=USER_ROLE)
		{
			$this->_ci->session->set_flashdata('error', 'Error. You dont have permission to access that page.');
			redirect('home/index');
		}
	}


}

// END Reply Class

/* End of file Reply.php */
/* Location: ./application/libraries/Reply.php */