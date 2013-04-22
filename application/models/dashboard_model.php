<?php
class Dashboard_model extends MY_Model {

	protected $_table = 'tb_apps';
	protected $primary_key = 'apps_id';
	
	function sort_date(){
		$this->db->order_by("date_created", "desc"); 
		return $this;
	}
	
	function search_list($searchData=array(), $per_page=null, $current_page=null)
	{
	

		if (isset($per_page) && isset($current_page) ){
			$this->db->limit($per_page, $current_page);
		}

		if (isset($searchData['search_name'])){
			$this->db->where('name', $searchData['search_name']);
		}
		
		if (isset($searchData['search_start_date'])){
			$this->db->where('date(date_created) >=  ', $searchData['search_start_date']);
		}

		if (isset($searchData['search_end_date'])){
			$this->db->where('date(date_created) <=  ', $searchData['search_end_date']);
		}

		$this->db->order_by('date_created','desc');		
		return $this;
	}	

}
?>