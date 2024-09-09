<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Age_vaccination_archive_model extends CI_Model {

		var $table = 'hms_pediatrician_age_vaccination';
		var $column = array('hms_pediatrician_age_vaccination.id','hms_vaccination_entry.vaccination_name','hms_pediatrician_age_vaccination.status','hms_pediatrician_age_vaccination.created_date');  
		var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_pediatrician_age_vaccination.*,hms_vaccination_entry.vaccination_name"); 
		$this->db->from($this->table); 
        $this->db->where('hms_pediatrician_age_vaccination.is_deleted','1');
        $this->db->where('hms_pediatrician_age_vaccination.branch_id = "'.$users_data['parent_id'].'"');
        $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id= hms_pediatrician_age_vaccination.vaccination','left');
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

	function get_age_list_according_vaccine($id='')
	{
		$users_data = $this->session->userdata('auth_users');
		$age_list_arr_recommended=array();
		$age_recomended_ids=array();
		$this->db->select('hms_pediatrician_age_vaccination_to_age.*,hms_pedic_age_vaccine_master.title');
		$this->db->from('hms_pediatrician_age_vaccination_to_age');
		$this->db->join('hms_pedic_age_vaccine_master','hms_pedic_age_vaccine_master.id=hms_pediatrician_age_vaccination_to_age.age_id','left');
		$this->db->where('hms_pediatrician_age_vaccination_to_age.age_vaccination_id',$id);
		$this->db->where('hms_pediatrician_age_vaccination_to_age.type',1);
		$this->db->where('hms_pedic_age_vaccine_master.branch_id',$users_data['parent_id']);
	
		$age_list_recommended= $this->db->get()->result();

		//echo $this->db->last_query();die;
		if(!empty($age_list_recommended))
		{
			foreach($age_list_recommended as $ages)
			{
				$age_list_arr_recommended[]=$ages->title;
			}
			$age_recomended_ids[]= '<div style="width: 100%;height: 100%;background-color: #FFB84D; border-radius: 100px; text-align: center;margin-bottom: 2px;background: #FDF585 none repeat scroll 0% 0%;">'.implode(',',$age_list_arr_recommended).'</div>';
			
		}

		/* catchup age */
		$age_list_arr_catchup=array();
		$age_catchup_ids=array();
		$this->db->select('hms_pediatrician_age_vaccination_to_age.*,hms_pedic_age_vaccine_master.title');
		$this->db->from('hms_pediatrician_age_vaccination_to_age');
		$this->db->join('hms_pedic_age_vaccine_master','hms_pedic_age_vaccine_master.id=hms_pediatrician_age_vaccination_to_age.age_id','left');
		$this->db->where('hms_pediatrician_age_vaccination_to_age.age_vaccination_id',$id);
		$this->db->where('hms_pediatrician_age_vaccination_to_age.type',2);
		$this->db->where('hms_pedic_age_vaccine_master.branch_id',$users_data['parent_id']);
	
		$age_list_catchup= $this->db->get()->result();
		if(!empty($age_list_catchup))
		{
			foreach($age_list_catchup as $ages)
			{
				$age_list_arr_catchup[]=$ages->title;
			}
			$age_catchup_ids[]= '<div style="width: 100%;height: 100%;background-color: #FFB84D; border-radius: 100px; text-align: center;margin-bottom: 2px;background: #D1E48A none repeat scroll 0% 0%;">'.implode(',',$age_list_arr_catchup).'</div>';
		 }

		/* catchup age */

		/* risk age */
		$age_list_arr_risk=array();
		$age_risk_ids=array();
		$this->db->select('hms_pediatrician_age_vaccination_to_age.*,hms_pedic_age_vaccine_master.title');
		$this->db->from('hms_pediatrician_age_vaccination_to_age');
		$this->db->join('hms_pedic_age_vaccine_master','hms_pedic_age_vaccine_master.id=hms_pediatrician_age_vaccination_to_age.age_id','left');
		$this->db->where('hms_pediatrician_age_vaccination_to_age.age_vaccination_id',$id);
		$this->db->where('hms_pediatrician_age_vaccination_to_age.type',3);
		$this->db->where('hms_pedic_age_vaccine_master.branch_id',$users_data['parent_id']);
	
		$age_list_risk= $this->db->get()->result();

		//echo $this->db->last_query();die;
		if(!empty($age_list_risk))
		{
			foreach($age_list_risk as $ages)
			{
				$age_list_arr_risk[]=$ages->title;
			}
			$age_risk_ids[]= '<div style="width: 100%;height: 100%;background-color: #FFB84D; border-radius: 100px; text-align: center;margin-bottom: 2px;background: #3595DB none repeat scroll 0% 0%;">'.implode(',',$age_list_arr_risk).'</div>';
			
			
		}
		$data_style= array_merge($age_risk_ids,$age_catchup_ids,$age_recomended_ids);
		return $data_style;
		/* risk age */
	
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
			$this->db->update('hms_pediatrician_age_vaccination');
	
    	}
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('age_vaccination_id',$id);
			$this->db->update('hms_pediatrician_age_vaccination_to_age');
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
			$this->db->update('hms_pediatrician_age_vaccination');
    	} 
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
			$this->db->where('age_vaccination_id IN ('.$emp_ids.')');
			$this->db->update('hms_pediatrician_age_vaccination_to_age');
    	} 


    }

    public function trash($id="")
    {
    	if(!empty($id) && $id>0)
    	{  
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_pediatrician_age_vaccination');

    	} 
    	if(!empty($id) && $id>0)
    	{  
			
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('age_vaccination_id',$id);
			$this->db->update('hms_pediatrician_age_vaccination_to_age');

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
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_pediatrician_age_vaccination');
    	} 

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
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('age_vaccination_id IN ('.$branch_ids.')');
			$this->db->update('hms_pediatrician_age_vaccination_to_age');
    	}
    }
 

}
?>