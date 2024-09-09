<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_age_chart_model extends CI_Model {

	var $table = 'hms_pediatrician_age_vaccination_to_age';
	

	var $column = array('hms_pediatrician_age_vaccination_to_age.id','hms_pediatrician_age_vaccination_to_age.age_vaccination_id','hms_pediatrician_age_vaccination_to_age.type','hms_pediatrician_age_vaccination_to_age.age_id','hms_pediatrician_age_vaccination_to_age.status'); 
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_pediatrician_age_vaccination_to_age.*"); 
		$this->db->from($this->table);
        $this->db->where('hms_pediatrician_age_vaccination_to_age.is_deleted','0');
        $this->db->where('hms_pediatrician_age_vaccination_to_age.branch_id = "'.$users_data['parent_id'].'"');
		$i = 0;
		  if(isset($_POST['order'])) // here order processing
			{
				$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}
			else if(isset($this->order))
			{
				$order = $this->order;
				$this->db->order_by(key($order), $order[key($order)]);
			}
	}


	function get_datatables()
	{
		$this->_get_datatables_query();
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
    
    

	public function get_by_id($id)
	{
		$this->db->select('hms_pediatrician_setting.*');
		$this->db->from('hms_pediatrician_setting'); 
		$this->db->where('hms_pediatrician_setting.id',$id);
		$this->db->where('hms_pediatrician_setting.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function vaccination_list()
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_vaccination_entry.id,hms_vaccination_entry.vaccination_name');
		$this->db->from('hms_vaccination_entry');
		$this->db->where('hms_vaccination_entry.is_deleted=0');
		$this->db->where('hms_vaccination_entry.status','1');
		$this->db->where('hms_vaccination_entry.branch_id',$users_data['parent_id']);

		return $this->db->get()->result();
	}
	public function age_list()
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_pedic_age_vaccine_master.id,hms_pedic_age_vaccine_master.title,hms_pedic_age_vaccine_master.start_age,hms_pedic_age_vaccine_master.end_age,hms_pedic_age_vaccine_master.start_age_type,hms_pedic_age_vaccine_master.end_age_type');
		$this->db->from('hms_pedic_age_vaccine_master');
		$this->db->where('hms_pedic_age_vaccine_master.is_deleted=0');
		$this->db->where('hms_pedic_age_vaccine_master.status','1');
		$this->db->where('hms_pedic_age_vaccine_master.branch_id',$users_data['parent_id']);
		//$this->db->order_by('hms_pedic_age_vaccine_master.start_age','ASC');
		//$this->db->order_by('CAST(start_age as INT)','ASC');
		//$this->db->order_by('CAST(end_age as INT)','ASC');
		$this->db->order_by('start_age_type','ASC');
		//$this->db->order_by('CAST(start_age as INT)','ASC');
		$this->db->order_by('end_age_type','ASC');
	//	$this->db->order_by('CAST(end_age as INT)','ASC');
		return $this->db->get()->result();
		
	}

	
	

   

  

   

}
?>