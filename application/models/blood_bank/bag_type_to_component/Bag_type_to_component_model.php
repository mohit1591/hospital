<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bag_type_to_component_model extends CI_Model {

	var $table = 'hms_blood_bag_type_to_component';
	var $column = array('hms_blood_bag_type_to_component.id','hms_blood_bag_type_to_component.bag_type_id', 'hms_blood_bag_type_to_component.status','hms_blood_bag_type.bag_type','hms_blood_bag_type_to_component.component_id','hms_blood_bag_type_to_component.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_blood_bag_type_to_component.*,hms_blood_bag_type.bag_type,hms_blood_component_master.component"); 
		$this->db->from($this->table);
		$this->db->group_by('bag_type_id'); 
		$this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_bag_type_to_component.bag_type_id','left');
		$this->db->join('hms_blood_component_master','hms_blood_component_master.id=hms_blood_bag_type_to_component.component_id','left');
        $this->db->where('hms_blood_bag_type_to_component.is_deleted','0');
        $this->db->where('hms_blood_bag_type_to_component.branch_id = "'.$users_data['parent_id'].'"');
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

	  public function get_users_name($id='')
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->from('hms_employees'); 

    	$this->db->where('id',$id);
        $this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	// $this->db->order_by('preferred_reminder_service','ASC'); 
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
		return $query->row_array();
		
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


	public function get_by_bag_type_id($bag_type_id)
	{
		$this->db->select('hms_blood_bag_type_to_component.*, hms_blood_bag_type.bag_type');
		$this->db->from('hms_blood_bag_type_to_component'); 
		$this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_bag_type_to_component.bag_type_id');
		$this->db->where('hms_blood_bag_type_to_component.bag_type_id',$bag_type_id);
		$this->db->where('hms_blood_bag_type_to_component.is_deleted','0');
		$query = $this->db->get(); 
		return $query->result();
	}


	public function get_by_id($bag_type_id)
	{
		$this->db->select('hms_blood_bag_type_to_component.*');
		$this->db->from('hms_blood_bag_type_to_component'); 
		$this->db->where('hms_blood_bag_type_to_component.bag_type_id',$bag_type_id);
		$this->db->where('hms_blood_bag_type_to_component.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}


	

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			//$this->db->where('id',$id);
			$this->db->where('bag_type_id',$id);
			$this->db->update('hms_blood_bag_type_to_component`');
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
			$this->db->where('bag_type_id IN ('.$branch_ids.')');
			$this->db->update('hms_blood_bag_type_to_component');
			//echo $this->db->last_query();die;
    	} 
    }

    public function component_list()
    {
    	$user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_component_master.*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('hms_blood_component_master.component','ASC'); 
    	$query = $this->db->get('hms_blood_component_master');
    	//echo $this->db->last_query();die;
		return $query->result();
    }

    public function bag_type_with_component_list($id='')
    {
    	$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_blood_bag_type_to_component.*,hms_blood_bag_type.bag_type,hms_blood_component_master.component"); 
		//$this->db->from($this->table); 
		$this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_bag_type_to_component.bag_type_id','left');
		$this->db->join('hms_blood_component_master','hms_blood_component_master.id=hms_blood_bag_type_to_component.component_id','left');
		$this->db->from($this->table);

		$this->db->where('hms_blood_bag_type_to_component.bag_type_id',$id);
        $this->db->where('hms_blood_bag_type_to_component.is_deleted','0');
        $this->db->where('hms_blood_bag_type_to_component.branch_id = "'.$users_data['parent_id'].'"');	
        $query = $this->db->get();
       // echo $this->db->last_query();die;
        return $query->result();
		
    }

    

    public function bag_type_list()
    {
    	$user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_bag_type.*');

    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->where('id not in (select bag_type_id from hms_blood_bag_type_to_component where is_deleted!=2 group by bag_type_id ) ');
    	$this->db->order_by('hms_blood_bag_type.bag_type','ASC'); 
    	$query = $this->db->get('hms_blood_bag_type');
    	//echo $this->db->last_query();die;
		return $query->result();
    }

}
?>