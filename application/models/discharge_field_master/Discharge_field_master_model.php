<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discharge_field_master_model extends CI_Model {

	var $table = 'hms_discharge_field_master';
	var $column = array('hms_discharge_field_master.id','hms_discharge_field_master.discharge_lable','hms_discharge_field_master.discharge_short_code', 'hms_discharge_field_master.type','hms_discharge_field_master.sort_order','hms_discharge_field_master.status','hms_discharge_field_master.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_discharge_field_master.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_discharge_field_master.is_deleted','0');
        $this->db->where('hms_discharge_field_master.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function discharge_field_master_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('discharge_lable','ASC'); 
    	$query = $this->db->get('hms_discharge_field_master');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_discharge_field_master.*');
		$this->db->from('hms_discharge_field_master'); 
		$this->db->where('hms_discharge_field_master.id',$id);
		$this->db->where('hms_discharge_field_master.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 

		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'discharge_lable'=>$post['discharge_lable'],
					'discharge_short_code'=>$post['discharge_short_code'], 
                    'type'=>$post['type'], 
                    'sort_order'=>$post['sort_order'], 
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );

		

		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_discharge_field_master',$data);  
		}
		else
		{    
			if(!empty($post['discharge_lable']))
			{
				$field_name = $this->generate_field_name($post['discharge_lable']);
			}
			$this->db->set('field_name',$field_name);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_discharge_field_master',$data);               
		} 	
	}

	public function generate_field_name($discharge_lable='')
	{
		$user_data = $this->session->userdata('auth_users');
		if(!empty($discharge_lable))
		{	
				$string = str_replace(' ', '-', $discharge_lable); // Replaces all spaces with hyphens.
				$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
				$string = preg_replace('/-+/', '_', $string); // Replaces multiple hyphens with single one.
				$fields_name = strtolower($string);
				if(!empty($fields_name))
				{
					$this->db->select('*');
					$this->db->from('hms_discharge_field_master');
					$this->db->where('field_name',$fields_name);
					$this->db->where('branch_id',$user_data['parent_id']);
					$query = $this->db->get(); 
					$total = $query->num_rows();
					if($total>0)
					{
						$fields_name = $fields_name.$total;
					}
					else
					{
						$fields_name = $fields_name;
					}
				}
				
		}
		return $fields_name;

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
			$this->db->update('hms_discharge_field_master');
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
			$this->db->update('hms_discharge_field_master');
			//echo $this->db->last_query();die;
    	} 
    }

}
?>