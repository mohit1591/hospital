<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vehicle_type_model extends CI_Model 
{

   var $table = 'hms_ambulance_vehicle_types';
	var $column = array('hms_ambulance_vehicle_types.id','hms_ambulance_vehicle_types.type','hms_ambulance_vehicle_types.owner_mobile','hms_ambulance_vehicle_types.owner_name','hms_ambulance_vehicle_types.vehicle_type','hms_ambulance_vehicle_types.gst_no');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
			$users_data = $this->session->userdata('auth_users');
			$search=$this->session->userdata('vehicle_search');
			//	print_r($search);die;
			$sub_branch_details = $this->session->userdata('sub_branches_data');
			$parent_branch_details = $this->session->userdata('parent_branches_data');
			$this->db->select("hms_ambulance_vehicle_types.*"); 
			$this->db->from($this->table); 
			$this->db->where('hms_ambulance_vehicle_types.is_deleted','0');
            $this->db->where('hms_ambulance_vehicle_types.branch_id',$users_data['parent_id']);
      
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
		$user_data = $this->session->userdata('auth_users');
		$this->db->from($this->table);
		$this->db->where('branch_id',$user_data['parent_id']);
		return $this->db->count_all_results();
	}




 public function save()
   {  
	$user_data = $this->session->userdata('auth_users');
	$post = $this->input->post();	


		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 

		$reg_date=date('Y-m-d',strtotime($post['registration_date']));
		$reg_exp_date=date('Y-m-d',strtotime($post['registration_exp_date']));
		$contract_from=date('Y-m-d',strtotime($post['contact_from']));
		$contract_to=date('Y-m-d',strtotime($post['contact_to']));


		$data = array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>$post['type'],
					'local_min_distance'=>$post['local_min_distance'],
					'local_min_amount'=>$post['local_min_amount'],
					'local_per_km_charge'=>$post['local_per_km_charge'],
					'outstation_min_distance'=>$post['outstation_min_distance'],
					'outstation_min_amount'=>$post['outstation_min_amount'],
					'outstation_per_km_charge'=>$post['outstation_per_km_charge'],
					'status'=>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );

		if(!empty($post['data_id']) && $post['data_id']>0)
		{ 

            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->set('modified_by',$user_data['id']);
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ambulance_vehicle_types',$data); 
			//	echo $this->db->last_query();die();	

		}
		else{   
	
		   $data = array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>$post['type'],
					'local_min_distance'=>$post['local_min_distance'],
					'local_min_amount'=>$post['local_min_amount'],
					'local_per_km_charge'=>$post['local_per_km_charge'],
					'outstation_min_distance'=>$post['outstation_min_distance'],
					'outstation_min_amount'=>$post['outstation_min_amount'],
					'outstation_per_km_charge'=>$post['outstation_per_km_charge'],
					'status'=>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR']

		         );
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->set('created_by',$user_data['id']);
			$this->db->insert('hms_ambulance_vehicle_types',$data);   	
	    }

	}
	
	
	
	
	public function get_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ambulance_vehicle_types.*');
		$this->db->from('hms_ambulance_vehicle_types'); 
		$this->db->where('hms_ambulance_vehicle_types.id',$id);
		$this->db->where('hms_ambulance_vehicle_types.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ambulance_vehicle_types.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->where('id',$id);
			$this->db->delete('hms_ambulance_vehicle_types');
			//echo $this->db->last_query();die;
    	} 
    }

    public function deleteall($ids=array())
    {
    	//print_r($ids);die;

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
			$where='id IN ('.$branch_ids.')';
			$this->db->delete('hms_ambulance_vehicle_types',$where);
    	} 
    }




	function search_report_data(){
		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ambulance_vehicle_types.*");
		$this->db->from($this->table); 
		$this->db->where('hms_ambulance_vehicle_types.is_deleted','0');
		$this->db->where('hms_ambulance_vehicle_types.branch_id',$users_data['parent_id']);
               /* 25-04-2020*/
		$query = $this->db->get(); 
		return $query->result();
	}


} 
?>