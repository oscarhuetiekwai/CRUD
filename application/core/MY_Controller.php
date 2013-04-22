<?php

class MY_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->permission->is_logged_in();
		$data->the_leave_data = $this->leave_data();
		$this->the_leave_data = $data->the_leave_data;
		// use for view, if remove unable to display data in view
		$this->load->vars($data);
	}

	function leave_data()
	{
		$data = array();
		$this->load->model('leave_data_model');
		$staff_id = $this->session->userdata('staff_id');
		if($query = $this->leave_data_model->get_by('staff_id',$staff_id))
		{
			$data['leave_data_records'] = $query;			
		}
		return $data;
	}

}

?>