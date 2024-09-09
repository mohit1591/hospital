<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class camp_details_archive_model extends CI_Model {

	var $table = 'hms_blood_camp_details';
	var $column = array('hms_blood_camp_details.id','hms_blood_camp_details.camp_name', 'hms_blood_camp_details.status','hms_blood_camp_details.camp_address','hms_blood_camp_details.camp_involved','hms_blood_camp_details.camp_date','hms_blood_camp_details.created_date'); 
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata("auth_users");
		$this->db->select("hms_blood_camp_details.*"); 
		$this->db->from($this->table);
        $this->db->where('hms_blood_camp_details.is_deleted','1');
        $this->db->where('hms_blood_camp_details.branch_id = "'.$users_data['parent_id'].'"');
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

	public function restore($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_blood_camp_details');
    	} 
    }

    public function restoreall($ids=array())
    {
    	if(!empty($ids))
    	{
    		$id_list = [];
    		foreach($ids as $id)
    		{
    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$emp_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$emp_ids.')');
			$this->db->update('hms_blood_camp_details');
    	} 
    }

    public function trash($id="")
    {
    	if(!empty($id) && $id>0)
    	{  
			//$this->db->where('id',$id);
			//$this->db->delete('hms_blood_deferral_reason');
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_blood_camp_details');
    	} 
    }

    public function trashall($ids=array())
    {
    	if(!empty($ids))
    	{
    		$id_list = [];
    		foreach($ids as $id)
    		{
    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$branch_ids = implode(',', $id_list); 
			//$this->db->where('id IN ('.$branch_ids.')');
			//$this->db->delete('hms_blood_deferral_reason');

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_blood_camp_details');
    	} 
    }
      public function get_inuser_name($id='')
    {
    	$emp_name=array();
    	$new_emp_name='';
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->from('hms_employees'); 

    	$this->db->where('id IN('.$id.')');
        $this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$query = $this->db->get();

    	//echo $this->db->last_query();die;
		$data= $query->result_array();
		if(!empty($data))
		{
			foreach($data as $data_arr)
			{
				$emp_name[]= $data_arr['name'];
			}
			$new_emp_name= implode(',',$emp_name);
		}
		return $new_emp_name;
		
    }

 

}
?>