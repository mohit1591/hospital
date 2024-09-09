<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leads_history_model extends CI_Model {

	var $table = 'crm_lead_to_followup';
	var $column = array(
		                 'crm_lead_to_followup.id',
		                 'crm_lead_to_followup.call_date',
		                 'crm_lead_to_followup.call_time',
		                 'crm_lead_to_followup.followup_date', 
		                 'crm_lead_to_followup.followup_time',
		                 'crm_call_status.call_status',
		                 'crm_lead_to_followup.call_remark', 
		                 'crm_lead_to_followup.created_date' 
		                );  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		//parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($lead_id='')
	{
        $users_data = $this->session->userdata('auth_users'); 
        $advance_search = $this->session->userdata('advance_search'); 
    		$this->db->select("crm_lead_to_followup.*, crm_call_status.call_status as callstatus");
    		$this->db->join("crm_call_status", "crm_call_status.id=crm_lead_to_followup.call_status", "left");   
        if(!empty($lead_id))
        {
          $this->db->where("crm_lead_to_followup.lead_id",$lead_id);   
        } 
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

	function get_datatables($lead_id="")
	{
		$this->_get_datatables_query($lead_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result_array();
	}

	function count_filtered($lead_id="")
	{
		$this->_get_datatables_query($lead_id="");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($lead_id="")
	{
    if(!empty($lead_id))
    {
      $this->db->where("crm_lead_to_followup.lead_id",$lead_id);   
    } 
		$this->db->from($this->table);
		return $this->db->count_all_results();
	} 

}
?>