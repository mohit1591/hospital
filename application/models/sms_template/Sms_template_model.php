<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms_template_model extends CI_Model 
{
	var $table = 'hms_sms_branch_template';
	var $column = array('hms_sms_branch_template.id','hms_sms_branch_template.form_name', 'hms_sms_branch_template.template','hms_sms_branch_template.short_code');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_sms_branch_template.*,hms_sms_setting_name.var_title as module_name");
		$this->db->join('hms_sms_setting_name','hms_sms_setting_name.id=hms_sms_branch_template.form_name','inner'); 
		$this->db->from($this->table); 
		$this->db->where('hms_sms_branch_template.is_deleted','0');
		$this->db->where('hms_sms_branch_template.branch_id',$users_data['parent_id']);
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

 	public function get_by_id($id)
	{
		$this->db->select("hms_sms_branch_template.*"); 
		$this->db->from('hms_sms_branch_template'); 
		$this->db->where('hms_sms_branch_template.id',$id);
		
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//echo "<pre>";print_r($post); exit;  
				$data = array(
				'branch_id'=>$user_data['parent_id'],
				"form_name"=>$post['form_name'],
				"template"=>$post['message'],
				"short_code"=>$post['short_code']
			);

		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_sms_branch_template',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_sms_branch_template',$data);               
		}	 
        //if already exist for a branch then delete it and insert
		
		/*$this->db->select('*');
		$this->db->where('form_name',$post['form_name']);
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->from('hms_sms_branch_template');
		$query = $this->db->get();
		$result = $query->result();	
		//echo count($result); exit;
		if(count($result)>0)
		{
			$this->db->where('form_name',$post['form_name']);
			$this->db->where('branch_id',$user_data['parent_id']);
			$this->db->delete('hms_sms_branch_template');

		}		
		    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_sms_branch_template',$data); */              
		 	
	}


    public function deleteall($ids=array())
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
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_print_template');
    	} 
    }
	public function template_list($data="")
	{
		$this->db->select("hms_sms_default_template.*"); 
		$this->db->from('hms_sms_default_template'); 
		$this->db->where($data);
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function template_format()
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_sms_branch_template.*"); 
		$this->db->from('hms_sms_branch_template'); 
		$this->db->where('branch_id',$users_data['parent_id']); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
    
} 
?>