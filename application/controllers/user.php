<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//this controllar is proceed all the view and pagination
class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->permission->is_logged_in();
		//load model
		$this->load->helper('url');
		//$this->load->model('home_model');
		$this->load->model('user_model');
		$this->load->model('role_model');
		$this->load->model('position_model');
		$this->load->model('department_model');
		$this->load->model('leave_data_model');
		$this->load->model('leave_model');
		if($query = $this->leave_model->leave_type())
		{
			$data['leave_records'] = $query;
		}
		$date = date('Y-m-d h:i:s');
	}

	//user index page
	function index()
	{
		$data = array();
		$data['page'] = 'user_list';
		if($query = $this->user_model->get_role_department_position()->get_all())
		{
			$data['data_records'] = $query;
		}
		$data['main'] = 'user/index';
		$data['js_function'] = array('user');
		$this->load->view('template/template',$data);
	}

	//display error
	function error()
	{
		$data['page'] = 'error';
		$this->load->view('home/error.php',$data);
	}

	//add user page
	function add_user()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('role', 'Role', 'required|trim');
		$this->form_validation->set_rules('position', 'Position', 'required|trim');
		$this->form_validation->set_rules('department', 'Department', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[tb_staff.username]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[password]');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('gender', 'Gender', 'required|trim');
		$this->form_validation->set_rules('mobile_phone', 'Mobile Phone', 'required|trim|min_length[8]|numeric');
		$this->form_validation->set_rules('superior', 'Superior', 'required|trim');

		$this->form_validation->set_message('is_unique', 'The %s already exist.');

		if ($this->form_validation->run() == TRUE)
		{
			$role = $this->input->post('role');
			$position = $this->input->post('position');
			$department = $this->input->post('department');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$password = $this->encode($password);
			$email = $this->input->post('email');
			$gender = $this->input->post('gender');
			$mobile_phone = $this->input->post('mobile_phone');
			$superior = $this->input->post('superior');
			$datetime = date('Y-m-d h:i:s');

			$data = array (
					'role_id'=>$role,
					'position_id'=>$position,
					'department_id'=>$department,
					'username'=>$username,
					'password'=>$password,
					'email_address'=>$email,
					'gender'=>$gender,
					'mobile_phone'=>$mobile_phone,
					'date_created'=>$datetime,
					'superior_id'=>$superior
			);

			if ($this->user_model->insert($data))
			{
				// if role is staff
				if($role == "3" || $role == "2"){
					$staff_id = $this->db->insert_id();
					$current_year = date("Y");
					$leave_data = array (
						'staff_id'=>$staff_id,
						'annual_leave'=>"0",
						'sick_leave'=>"0",
						'annual_leave_balance'=>"0",
						'sick_leave_balance'=>"0",
						'year'=>$current_year
					);
					$this->leave_data_model->insert($leave_data);
				}
				$this->session->set_flashdata('success', 'The User have been successfully added');
				redirect('user/add_user');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect('user/add_user');
			}
		}
		else
		{
			$data = array();
			$data['page'] = 'add_user';
			$data['main'] = 'user/add_user';
			if($query = $this->role_model->get_all())
			{
				$data['data_role_records'] = $query;
			}
			if($query = $this->position_model->get_all())
			{
				$data['data_position_records'] = $query;
			}
			if($query = $this->department_model->sort_data()->get_all())
			{
				$data['data_department_records'] = $query;
			}
			if($query = $this->user_model->get_superior()->get_all())
			{
				$data['data_superior_records'] = $query;
			}
			if($query = $this->leave_model->leave_type())
			{
				$data['leave_records'] = $query;
			}
			$data['js_function'] = array('user');
			$this->load->view('template/template',$data);
		}
	}

	## Delete apps ##
	function ajax_delete_user()
	{
		//$this->permission->superadmin_admin_only();
		$staff_id = $this->input->post('staff_id');

		if($this->user_model->delete($staff_id))
		{
			if($this->leave_data_model->delete($staff_id))
			{
				$msg = "success";
				echo json_encode($msg);
			}else{
				$msg = "error";
				echo json_encode($msg);
			}
		}
		else
		{
			$msg = "error";
			echo json_encode($msg);
		}
	}

	//login page
	function login()
	{
		//$this->is_logged_in();
		$this->load->view('login/login.php');
	}

	## json for checkbox user list ##
	function checkbox_user_delete()
	{
		//$this->permission->superadmin_admin_only();
		$data = array();
		$id = $this->input->post('staff_id');

		foreach($id as $value){

			if($query = $this->user_model->delete($value))
			{
				$this->leave_data_model->delete($value);
				$data['data_records'] = $query;
			}

		}
		$data['page'] = 'checkbox_tab_delete';

		redirect('user/index', $data);
	}

	## edit user ##
	function edit_user()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('role', 'Role', 'required|trim');
		$this->form_validation->set_rules('position', 'Position', 'required|trim');
		$this->form_validation->set_rules('department', 'Department', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		if($this->input->post('password') && $this->input->post('confirm_password')){
			$this->form_validation->set_rules('password', 'Password', 'required|trim');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[password]');
		}
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('gender', 'Gender', 'required|trim');
		$this->form_validation->set_rules('mobile_phone', 'Mobile Phone', 'required|trim|min_length[8]|numeric');
		$this->form_validation->set_rules('superior', 'Superior', 'required|trim');

		$this->form_validation->set_message('is_unique', 'The %s already exist.');

		if ($this->form_validation->run() == TRUE)
		{
			$role = $this->input->post('role');
			$position = $this->input->post('position');
			$department = $this->input->post('department');
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$gender = $this->input->post('gender');
			$mobile_phone = $this->input->post('mobile_phone');
			$staff_id = $this->input->post('staff_id');
			$superior = $this->input->post('superior');
			$hash = md5($staff_id.SECRETTOKEN);
			$datetime = date('Y-m-d h:i:s');

			$data = array (
					'role_id'=>$role,
					'position_id'=>$position,
					'department_id'=>$department,
					'username'=>$username,
					'email_address'=>$email,
					'gender'=>$gender,
					'mobile_phone'=>$mobile_phone,
					'date_created'=>$datetime,
					'superior_id'=>$superior
			);

			if($this->input->post('password'))
			{
				$password = $this->input->post('password');
				$password = $this->encode($password);
				$data = array('password'=>$password) + $data ;
			}

			if ($this->user_model->update($staff_id,$data))
			{

				$this->session->set_flashdata('success', 'The User have been successfully updated');
				redirect("user/edit_user/$staff_id/$hash");
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect("user/edit_user/$staff_id/$hash");
			}
		}
		else
		{
			if($this->uri->segment(3))
	    	{
				$staff_id = $this->uri->segment(3);
			}
			else
			{
				$staff_id = $this->input->post('staff_id');
			}
			$data = array();
			if($query = $this->role_model->get_all())
			{
				$data['data_role_records'] = $query;
			}
			if($query = $this->position_model->get_all())
			{
				$data['data_position_records'] = $query;
			}
			if($query = $this->department_model->sort_data()->get_all())
			{
				$data['data_department_records'] = $query;
			}
			if($query = $this->user_model->get_superior()->get_all())
			{
				$data['data_superior_records'] = $query;
			}
			if($query = $this->user_model->get_role_department_position()->get_by('staff_id',$staff_id))
			{
				$data['data_records'] = $query;
			}
			$data['page'] = 'edit_user';
			$data['main'] = 'user/edit_user';
			$data['js_function'] = array('user');
			$this->load->view('template/template',$data);
		}
	}

	############### USER LEAVE SETTING ###################
	## user Leave list##
	function user_leave_setting()
	{
			$data = array();
			$data['page'] = 'user_leave_setting';
			$data['main'] = 'user/user_leave_setting';
			if($query = $this->leave_data_model->get_staff()->get_all())
			{
				$data['data_records'] = $query;
			}
			$data['js_function'] = array('user');
			$this->load->view('template/template',$data);
	}

	## Edit user Leave  ##
	function edit_user_leave_setting()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('annual_leave', 'Annual Leave', 'required|trim|numeric');
		$this->form_validation->set_rules('sick_leave', 'Sick Leave', 'required|trim|numeric');
		$this->form_validation->set_rules('annual_leave_balance', 'Annual Leave Balance', 'required|trim|numeric');
		$this->form_validation->set_rules('sick_leave_balance', 'Sick Leave Balance', 'required|trim|numeric');
		$this->form_validation->set_rules('year', 'Year', 'required|trim|numeric');
		$this->form_validation->set_message('is_unique', 'The %s already exist.');

		if ($this->form_validation->run() == TRUE){

			$leave_data_id = $this->input->post('leave_data_id');
			$hash = md5($leave_data_id.SECRETTOKEN);
			$username = $this->input->post('username');
			$annual_leave = $this->input->post('annual_leave');
			$sick_leave = $this->input->post('sick_leave');
			$annual_leave_balance = $this->input->post('annual_leave_balance');
			$sick_leave_balance = $this->input->post('sick_leave_balance');
			$year = $this->input->post('year');
			$data = array (
						'annual_leave'=>$annual_leave,
						'sick_leave'=>$sick_leave,
						'annual_leave_balance'=>$annual_leave_balance,
						'sick_leave_balance'=>$sick_leave_balance,
						'year'=>$year
			);
			if ($this->leave_data_model->update($leave_data_id,$data))
			{
				$this->session->set_flashdata('success', 'The User Leave Setting have been successfully updated');
				redirect("user/edit_user_leave_setting/$leave_data_id/$hash");
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect("user/edit_user_leave_setting/$leave_data_id/$hash");
			}

		}else{
		
			if($this->uri->segment(3))
	    	{
				$leave_data_id = $this->uri->segment(3);
			}
			else
			{
				$leave_data_id = $this->input->post('leave_data_id');
			}
			$data = array();
			$data['page'] = 'edit_user_leave_setting';
			$data['main'] = 'user/edit_user_leave_setting';
			if($query = $this->leave_data_model->get_staff()->get_by('leave_data_id',$leave_data_id))
			{
				$data['data_records'] = $query;
			}
			$data['js_function'] = array('user');
			$this->load->view('template/template',$data);
		}
	}
	############### END USER LEAVE SETTING ###################

	
	############### USER DEPARTMENT ###################
	//user index page
	function user_department_list()
	{
		$data = array();
		$data['page'] = 'user_department_list';
		if($query = $this->department_model->sort_data()->get_all())
		{
			$data['data_records'] = $query;
		}
		$data['main'] = 'user/user_department_list';
		$data['js_function'] = array('user_department');
		$this->load->view('template/template',$data);
	}

	## json for checkbox user list ##
	function checkbox_user_department_delete()
	{
		//$this->permission->superadmin_admin_only();
		$data = array();
		$id = $this->input->post('department_id');

		foreach($id as $value){

			if($query = $this->department_model->delete($value))
			{
				$data['data_records'] = $query;
			}

		}
		$data['page'] = 'checkbox_tab_delete';

		redirect('user/user_department_list', $data);
	}

	## Delete user department ##
	function ajax_delete_user_department()
	{
		$department_id = $this->input->post('department_id');

		$query = $this->user_model->view_valid_department()->get_all();

		if($query){

			if($this->department_model->delete($department_id))
			{
				$msg = "success";
				echo json_encode($msg);
			}
			else
			{
				$msg = "error";
				echo json_encode($msg);
			}
		}else{
			$msg = "error, the department you chosen was in use in staff records";
			echo json_encode($msg);
		}
	}

	//add user department page
	function add_user_department()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('department_name', 'Department Name', 'required|trim');
		$this->form_validation->set_message('is_unique', 'The %s already exist.');
		if ($this->form_validation->run() == TRUE)
		{
			$department_name = $this->input->post('department_name');
			$data = array (
				'department_name'=>$department_name
			);

			if ($this->department_model->insert($data))
			{
				$this->session->set_flashdata('success', 'The Department have been successfully added');
				redirect('user/add_user_department');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect('user/add_user_department');
			}
		}
		else
		{
			$data = array();
			$data['page'] = 'add_user_department';
			$data['main'] = 'user/add_user_department';
			$data['js_function'] = array('user_department');
			$this->load->view('template/template',$data);
		}
	}

	## edit user department ##
	function edit_user_department()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('department_name', 'Department Name', 'required|trim');

		$this->form_validation->set_message('is_unique', 'The %s already exist.');

		if ($this->form_validation->run() == TRUE)
		{
			$department_name = $this->input->post('department_name');
			$department_id = $this->input->post('department_id');
			$hash = md5($department_id.SECRETTOKEN);
			$data = array (
				'department_name'=>$department_name
			);

			if ($this->department_model->update($department_id,$data))
			{
				$this->session->set_flashdata('success', 'The Department have been successfully updated');
				redirect("user/edit_user_department/$department_id/$hash");
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect("user/edit_user_department/$department_id/$hash");
			}
		}
		else
		{
			if($this->uri->segment(3))
	    	{
				$department_id = $this->uri->segment(3);
			}
			else
			{
				$department_id = $this->input->post('department_id');
			}
			$data = array();
			if($query = $this->department_model->get_by('department_id',$department_id))
			{
				$data['data_records'] = $query;
			}
			$data['page'] = 'edit_user_department';
			$data['main'] = 'user/edit_user_department';
			$data['js_function'] = array('user_department');
			$this->load->view('template/template',$data);
		}
	}
	############### END USER DEPARTMENT ###################


	############### USER POSITION ###################
	//user position index page
	function user_position_list()
	{
		$data = array();
		$data['page'] = 'user_position_list';
		if($query = $this->position_model->sort_data()->get_all())
		{
			$data['data_records'] = $query;
		}

		$data['main'] = 'user/user_position_list';
		$data['js_function'] = array('user_position');
		$this->load->view('template/template',$data);
	}

	## json for checkbox user list ##
	function checkbox_user_position_delete()
	{
		//$this->permission->superadmin_admin_only();
		$data = array();
		$id = $this->input->post('position_id');

		foreach($id as $value){

			if($query = $this->position_model->delete($value))
			{
				$data['data_records'] = $query;
			}
		}
		$data['page'] = 'checkbox_tab_delete';

		redirect('user/user_position_list', $data);
	}

	## Delete user position ##
	function ajax_delete_user_position()
	{
		$position_id = $this->input->post('position_id');

		//	$query = $this->user_model->view_valid_department()->get_all();

		if($query){

			if($this->position_model->delete($position_id))
			{
				$msg = "success";
				echo json_encode($msg);
			}
			else
			{
				$msg = "error";
				echo json_encode($msg);
			}
		}else{
			$msg = "error, the department you chosen was in use in staff records";
			echo json_encode($msg);
		}
	}

	//add user position page
	function add_user_position()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('position_name', 'Position Name', 'required|trim');
		$this->form_validation->set_message('is_unique', 'The %s already exist.');
		if ($this->form_validation->run() == TRUE)
		{
			$position_name = $this->input->post('position_name');
			$data = array (
				'position_name'=>$position_name
			);

			if ($this->position_model->insert($data))
			{
				$this->session->set_flashdata('success', 'The Position have been successfully added');
				redirect('user/add_user_position');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect('user/add_user_position');
			}
		}
		else
		{
			$data = array();
			$data['page'] = 'add_user_position';
			$data['main'] = 'user/add_user_position';
			$data['js_function'] = array('user_department');
			$this->load->view('template/template',$data);
		}
	}

	## edit user position ##
	function edit_user_position()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('position_name', 'Position Name', 'required|trim');
		$this->form_validation->set_message('is_unique', 'The %s already exist.');
		if ($this->form_validation->run() == TRUE)
		{
			$position_name = $this->input->post('position_name');
			$position_id = $this->input->post('position_id');
			$hash = md5($position_id.SECRETTOKEN);
			$data = array (
				'position_name'=>$position_name
			);

			if ($this->position_model->update($position_id,$data))
			{
				$this->session->set_flashdata('success', 'The Position have been successfully updated');
				redirect("user/edit_user_position/$position_id/$hash");
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect("user/edit_user_position/$position_id/$hash");
			}
		}
		else
		{
			if($this->uri->segment(3))
	    	{
				$position_id = $this->uri->segment(3);
			}
			else
			{
				$position_id = $this->input->post('position_id');
			}
			$data = array();
			if($query = $this->position_model->get_by('position_id',$position_id))
			{
				$data['data_records'] = $query;
			}
			$data['page'] = 'edit_user_position';
			$data['main'] = 'user/edit_user_position';
			$data['js_function'] = array('user_position');
			$this->load->view('template/template',$data);
		}
	}
	############### END USER POSITION ###################

	function ajax_read_contact_file()
	{
		$contact_filename = $this->input->post('contact_filename');

		$contact_file_path = './uploads/'.$contact_filename;

		echo json_encode($contact_file_path);
	}

	## password encode ##
	function encode($text)
	{
		$result = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND));
		return trim(base64_encode($result));
	}

}//end of class
?>