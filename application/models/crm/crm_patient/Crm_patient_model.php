<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Crm_patient_model extends CI_Model 
{

	var $table = 'crm_leads';
	var $column = array(
		                 'crm_leads.crm_code',
		                 'hms_department.department',
		                 'crm_lead_type.lead_type',
		                 'crm_source.source', 
		                 'crm_leads.name',
		                 'crm_leads.phone',
		                 'crm_leads.followup_date',
		                 'crm_leads.appointment_date',
		                 'hms_users.username',
		                 'crm_leads.created_date',
		                 'crm_leads.modified_date'
		                );  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		//parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users'); 
        $advance_search = $this->session->userdata('advance_search'); 
		$this->db->select("crm_leads.*, hms_department.department, crm_lead_type.lead_type, crm_source.source, hms_users.username as uname");
		$this->db->join("hms_department","hms_department.id=crm_leads.department_id","left");
		$this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
		$this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
		$this->db->join("hms_users","hms_users.id=crm_leads.created_by","left"); 
		$this->db->where("crm_leads.booking_id=''"); 
		if($users_data['parent_id']==184)
		{
			$this->db->where("crm_leads.lead_type_id",4);
		}
		else if($users_data['parent_id']==185)
		{
			$this->db->where("crm_leads.lead_type_id",5);
		}
		else
		{
			$this->db->where("crm_leads.lead_type_id NOT IN (5,4)");
		}
           
        if(isset($advance_search) && !empty($advance_search))
        {/* 
          if(!empty($advance_search['start_date']))
          {
          	$this->db->where("crm_leads.created_date >= '".date('Y-m-d',strtotime($advance_search['start_date'])).' 00:00:00'."'");   
          }

          if(!empty($advance_search['end_date']))
          {
          	$this->db->where("crm_leads.created_date <= '".date('Y-m-d',strtotime($advance_search['end_date'])).' 23:59:59'."'");   
          }

          if(!empty($advance_search['department_id']))
          {
          	$this->db->where("crm_leads.department_id", $advance_search['department_id']);   
          }

          if(!empty($advance_search['lead_id']))
          {
          	$this->db->where("crm_leads.crm_code", $advance_search['lead_id']);   
          }

          if(!empty($advance_search['lead_source_id']))
          {
          	$this->db->where("crm_leads.lead_source_id", $advance_search['lead_source_id']);   
          }

          if(!empty($advance_search['lead_type_id']))
          {
          	$this->db->where("crm_leads.lead_type_id", $advance_search['lead_type_id']);   
          }

          if(!empty($advance_search['name']))
          {
          	$this->db->where("crm_leads.name '%".$advance_search['name']."%'");  
          }

          if(!empty($advance_search['email']))
          {
          	$this->db->where("crm_leads.email '%".$advance_search['email']."%'");   
          }

          if(!empty($advance_search['phone']))
          {
          	$this->db->where("crm_leads.phone '%".$advance_search['phone']."%'");   
          }
 
        */}
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
		//echo $this->db->last_query();die;
		return $query->result_array();
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
	
	
  function search_crm_data()
  {
    	$users_data = $this->session->userdata('auth_users'); 
        $advance_search = $this->session->userdata('advance_search'); 
		$this->db->select("crm_leads.*, hms_department.department, crm_lead_type.lead_type, crm_source.source, hms_users.username as uname");
		$this->db->join("hms_department","hms_department.id=crm_leads.department_id","left");
		$this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
		$this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
		$this->db->join("hms_users","hms_users.id=crm_leads.created_by","left"); 
		$this->db->where("crm_leads.booking_id=''"); 
		if($users_data['parent_id']==184)
		{
			$this->db->where("crm_leads.lead_type_id",4);
		}
		else if($users_data['parent_id']==185)
		{
			$this->db->where("crm_leads.lead_type_id",5);
		}
		else
		{
			$this->db->where("crm_leads.lead_type_id NOT IN (5,4)");
		}
           
        if(isset($advance_search) && !empty($advance_search))
        { }
		$this->db->from($this->table);  
        $query = $this->db->get();
		$data= $query->result_array();
		//echo $this->db->last_query();
		return $data;
	}
 

} 
?>