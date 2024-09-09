<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_profile_model extends CI_Model {

	var $table = 'hms_blood_employee_profile_type';
	var $column = array('hms_blood_employee_profile_type.id','hms_blood_employee_type_list.employee_type_name','hms_blood_employee_profile_type.employee_type',
	 'hms_blood_employee_profile_type.profile_name','hms_blood_employee_profile_type.status','hms_blood_employee_profile_type.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_blood_employee_profile_type.*,hms_blood_employee_type_list.employee_type_name,hms_employees.name"); 
		$this->db->from($this->table); 
		$this->db->join('hms_employees','hms_employees.id=hms_blood_employee_profile_type.profile_name','Left');
		$this->db->join('hms_blood_employee_type_list','hms_blood_employee_type_list.id=hms_blood_employee_profile_type.employee_type','Left');
        $this->db->where('hms_blood_employee_profile_type.is_deleted','0');
        $this->db->where('hms_blood_employee_profile_type.branch_id = "'.$users_data['parent_id'].'"');
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

	 // Function to get Blood camp list
    public function get_blood_employee_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_employee_type_list');
       //$this->db->where('branch_id',$users_data['parent_id']);
        //$this->db->where('status','1');
        //$this->db->where('is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";   
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
    
    public function preferred_reminder_service_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('preferred_reminder_service','ASC'); 
    	$query = $this->db->get('hms_blood_deferral_reason');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_blood_employee_profile_type.*');
		$this->db->from('hms_blood_employee_profile_type'); 
		$this->db->where('hms_blood_employee_profile_type.id',$id);
		$this->db->where('hms_blood_employee_profile_type.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'employee_type'=>$post['employee_type'],
					'profile_name'=>$post['profile_name'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_blood_employee_profile_type',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_blood_employee_profile_type',$data);               
		} 	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_blood_employee_profile_type');
			//echo $this->db->last_query();die;
    	} 
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
			$this->db->update('hms_blood_employee_profile_type');
			//echo $this->db->last_query();die;
    	} 
    }

}
?>