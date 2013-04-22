<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//this controllar is proceed all the view and pagination
class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->permission->is_logged_in();
		//load model
		$this->load->helper('url');
		$this->load->model('dashboard_model');

		//$this->load->model('leave_model');

	}

	function index()
	{
		$data = array();
		$data['page'] = 'dashboard';

		// start pagination
		$config = $this->email_pagination_config();

		$config['base_url'] = base_url().'dashboard/index';

		$config['total_rows'] = count($this->dashboard_model->search_list()->get_all());

		$this->pagination->initialize($config);
		// end pagination

		if($query = $this->dashboard_model->search_list(null, $config['per_page'],$this->uri->segment(3))->get_all())
		{
			$data['data_records'] = $query;
		}

		$data['main'] = 'dashboard/index';
		$data['js_function'] = array('home');
		$this->load->view('template/template',$data);
	}

	function another_table()
	{
		$data = array();
		$data['page'] = 'anothertable';
		if($query = $this->dashboard_model->sort_date()->get_all())
		{
			$data['data_record'] = $query;
		}
		$data['main'] = 'dashboard/another_table';
		$data['js_function'] = array('home');
		$this->load->view('template/template',$data);
	}



	public function search_dashboard()
	{
		//set the search data
		$this->load->library('searchdata');

		$this->searchdata->_set();

		$content_data = array();

		//Pagination

		$config = $this->email_pagination_config();

		$config['base_url'] = base_url().'dashboard/search_dashboard';

		$search_param = array();

		foreach($this->session->userdata('search') as $key => $value)
		{

			$search_param = $search_param + array($key=>$value);

		}

		$config['total_rows'] = count($this->dashboard_model->search_list($search_param, null, null)->get_all());

		$this->pagination->initialize($config);
			// end Pagination

		if($query = $this->dashboard_model->search_list($search_param, $config['per_page'], $this->uri->segment(3))->get_all())
		{
			$content_data['data_records'] = $query;
		}
		$content_data['page'] = 'dashboard';
		$content_data['main'] = 'dashboard/index';
		$content_data['js_function'] = array('home');
		$this->load->view('template/template', $content_data);
	}

	function search_email_excel()
	{
		$this->load->library('searchdata');

		//set the search data

		$this->searchdata->_set();

		$data = array();

		$search_param = array();

		foreach($this->session->userdata('search') as $key => $value)
		{
			$search_param = $search_param + array($key=>$value);
		}

		if($query = $this->dashboard_model->search_list($search_param, null, null)->get_all())
		{
			$data['email_list'] = $query;
		}
		else
		{
			$data['email_list'] = array();
		}

		$this->load->view('dashboard/email_list_excel',$data);
	}

	function add_row()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('description', 'Description', 'required|trim');
		//$this->form_validation->set_rules('start_date', 'News Date', 'required|trim');

		$this->form_validation->set_message('is_unique', 'The %s already exist.');

		if ($this->form_validation->run() === TRUE)
		{
			$name = $this->input->post('name');
			$description = $this->input->post('description');

			$datetime = date('Y-m-d h:i:s');
			$data = array (

					'name'=>$name,
					'description'=>$description,
					'date_created'=>$datetime,
			);

			if ($this->dashboard_model->insert($data))
			{
				$this->session->set_flashdata('success', 'The Apps have been successfully added');
				redirect('dashboard/add_row');
			}
			else
			{
				$this->session->set_flashdata('error', 'Error. Please try again.');
				redirect('dashboard/add_row');
			}
		}
		else
		{

			$data = array();
			$data['page'] = 'add_row';
			$data['main'] = 'dashboard/add_row';
			$data['js_function'] = array('home');
			$this->load->view('template/template',$data);

		}
	}

	## Delete notification ##
	function ajax_delete_row()
	{
			//$this->permission->superadmin_admin_only();
		$apps_id = $this->input->post('apps_id');

		if($this->dashboard_model->delete($apps_id))
		{
			$msg = "success";
			echo json_encode($msg);
		}
		else
		{
			$msg = "error";
			echo json_encode($msg);
		}
	}


	## checkbox for apps list under view ##
	function checkbox_news_delete()
	{
		//$this->permission->superadmin_admin_only();
		$data = array();
		$id = $this->input->post('news_id');

		foreach($id as $value){

			if($query = $this->dashboard_model->delete($value))
			{
				$data['data_records'] = $query;
			}

		}
		$data['page'] = 'checkbox_tab_delete';

		redirect('home/index', $data);
	}



	## edit_row ##
	function edit_row()
	{

		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Title', 'required|trim');
		$this->form_validation->set_rules('description', 'Description', 'required|trim');
		$this->form_validation->set_message('is_unique', 'The %s already exist.');

			if ($this->form_validation->run() === TRUE)
			{
				$apps_id = $this->input->post('apps_id');
				$hash = md5($apps_id.SECRETTOKEN);
				$title = $this->input->post('name');
				$description = $this->input->post('description');
				$activity = $this->input->post('activity');
				$datetime = date('Y-m-d h:i:s');
				$data = array (
					'name'=>$title,
					'description'=>$description,
					'activity'=>$activity,
				);

				if ($this->dashboard_model->update($apps_id,$data))
				{
					$this->session->set_flashdata('success', 'Your Apps have been successfully updated');
					redirect("dashboard/edit_row/$apps_id/$hash");
				}
				else
				{
					$this->session->set_flashdata('error', 'Error. Please try again.');
					redirect("dashboard/edit_row/$apps_id/$hash");
				}
			}
			else
			{
				$apps_id = $this->uri->segment(3);

				$data = array();
				if($query = $this->dashboard_model->get_by('apps_id',$apps_id))
				{
					$data['data_records'] = $query;
				}
				$data['js_function'] = array('home');
				$data['page'] = 'edit_row';
				$data['main'] = 'dashboard/edit_row';
				$this->load->view('template/template', $data);
			}

	}

	function ajax_read_contact_file()
	{
		$contact_filename = $this->input->post('contact_filename');

		$contact_file_path = './uploads/'.$contact_filename;

		echo json_encode($contact_file_path);
	}

	function email_pagination_config()
	{
		$this->load->library('pagination');

		$config['per_page'] =5;
		$config['num_links'] = 2;
		$config['first_link'] = false;
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Previous';
		$config['last_link'] = false;
		$config['full_tag_open'] = '<div class="pagination pagination-centered"><ul>';
		$config['full_tag_close'] = '</ul></div>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>;';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		return $config;

	}

}//end of class
?>