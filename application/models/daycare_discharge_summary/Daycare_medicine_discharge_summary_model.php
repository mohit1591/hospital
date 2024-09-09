<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daycare_medicine_discharge_summary_model extends CI_Model {

	var $table = 'hms_daycare_discharge_summery_medicine';
	var $column = array('hms_daycare_discharge_summery_medicine.medicine_name');   
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_daycare_discharge_summery_medicine.*,hms_day_care_booking.daycare_no,hms_patient.patient_name,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.patient_code, hms_patient.age_y,hms_patient.gender,hms_patient.mobile_no,hms_patient.address");

		$this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_daycare_discharge_summery_medicine.daycare_id','left');
		$this->db->join('hms_patient','hms_patient.id=hms_daycare_discharge_summery_medicine.patient_id','left');
		$this->db->from($this->table); 
		
		$i = 0;
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop+
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		
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
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		// echo $this->db->last_query();die;
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
    
    public function simulation_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('simulation','ASC'); 
    	$query = $this->db->get('hms_discharge_summery');
		return $query->result();
    }

	
	

    public function get_medicine_by_id($id)
	{
		$this->db->select('hms_daycare_discharge_summery_medicine.*');
		$this->db->from('hms_daycare_discharge_summery_medicine'); 
		$this->db->where('hms_daycare_discharge_summery_medicine.discharge_summary_id',$id);
		$query = $this->db->get(); 
		return $query->result_array();
	}

	
  

}
?>