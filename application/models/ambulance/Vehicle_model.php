<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vehicle_model extends CI_Model 
{

		var $table = 'hms_ambulance_vehicle';
	var $column = array('hms_ambulance_vehicle.id','hms_ambulance_vehicle.vehicle_no','hms_ambulance_vehicle.chassis_no','hms_ambulance_vehicle.owner_mobile','hms_ambulance_vehicle.owner_name','hms_ambulance_vehicle.vehicle_type','hms_ambulance_vehicle.gst_no','hms_ambulance_vehicle.registration_date,hms_ambulance_location.location_name');  
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
			$this->db->select("hms_ambulance_vehicle.*,hms_ambulance_location.location_name"); 
			$this->db->join('hms_ambulance_location','hms_ambulance_location.id = hms_ambulance_vehicle.location','left');
		//	$this->db->join('hms_ambulance_vehicle_type','hms_ambulance_vehicle_type.id = hms_ambulance_vehicle.type','left');
			$this->db->from($this->table); 
			$this->db->where('hms_ambulance_vehicle.is_deleted','0');
            $this->db->where('hms_ambulance_vehicle.branch_id',$users_data['parent_id']);
            
               /* 25-04-2020*/
        if(isset($search) && !empty($search))
		{  
            if(!empty($search['start_date']))
            {
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_ambulance_vehicle.created_date >= "'.$start_date.'"');
			}

            if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date']));
				$this->db->where('hms_ambulance_vehicle.created_date <= "'.$end_date.'"');
			}
		
		     
		    if(!empty($search['location']))
			{
				$this->db->where('hms_ambulance_vehicle.location',$search['location']);
			}
			if(!empty($search['reg_date']))
			{
				$reg_date = date('Y-m-d',strtotime($search['reg_date']));
				$this->db->where('hms_ambulance_vehicle.registration_date = "'.$reg_date.'"');
			}
            if(!empty($search['reg_exp']))
			{
				$reg_exp = date('Y-m-d',strtotime($search['reg_exp']));
				$this->db->where('hms_ambulance_vehicle.registration_exp_date = "'.$reg_exp.'"');
			}
			 if(!empty($search['vehicle_no']))
			{
				$this->db->like('hms_ambulance_vehicle.vehicle_no',$search['vehicle_no']);
			}
			  if(!empty($search['chassis_no']))
			{
				$this->db->like('hms_ambulance_vehicle.chassis_no',$search['chassis_no']);
			}
			    if(!empty($search['engine_no']))
			{
				$this->db->like('hms_ambulance_vehicle.engine_no',$search['engine_no']);
			}
			  if(!empty($search['type']))
			{
				$this->db->where('hms_ambulance_vehicle_type.type',$search['type']);
			}
			  if(!empty($search['vehicle_type']))
			{
				$this->db->where('hms_ambulance_vehicle.vehicle_type',$search['vehicle_type']);
			}
		
				  if(!empty($search['owner_name']))
			{
				$this->db->where('hms_ambulance_vehicle.vendor_id',$search['owner_name']);
			}
		}
            /*25-04-2020*/
	
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
		//$user_data = $this->session->userdata('auth_users');
		$this->db->from($this->table);
		//$this->db->where('branch_id',$user_data['parent_id']);
		return $this->db->count_all_results();
	}




 public function save()
   {  
//echo "<pre>"; print_r($_POST);die;
	$user_data = $this->session->userdata('auth_users');
	$post = $this->input->post();		
	$data_vendor = array( 
				
				'branch_id'=>$user_data['parent_id'],
				"name"=>$post['name'],
				"vendor_gst"=>$post['vendor_gst'],
				"address"=>$post['address'],
				"address2"=>$post['address2'],
				"address3"=>$post['address3'],
				"mobile"=>$post['mobile'],
				"email"=>$post['email'],
				"vendor_type"=>5,
				"status"=>1
				
			);
	
	//echo "<pre>"; print_r($data_vendor);die;

		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 

		//$reg_no = generate_unique_id(2); 

		$reg_date=date('Y-m-d',strtotime($post['registration_date']));
		$reg_exp_date=date('Y-m-d',strtotime($post['registration_exp_date']));
		$contract_from=date('Y-m-d',strtotime($post['contact_from']));
		$contract_to=date('Y-m-d',strtotime($post['contact_to']));
		
		if($post['vehicle_type']==2){
            $vendor_id= $post['vendor_id'];			
			}
			else
			{
			$vendor_id=0;
			}

		$data = array(
					
					"vendor_id"=>$vendor_id,
					'branch_id'=>$user_data['parent_id'],
					'vehicle_no'=>$post['vehicle_no'],
					'chassis_no'=>$post['chassis_no'],
					'engine_no'=>$post['engine_no'],
					'registration_date'=>$reg_date,
					'registration_exp_date'=>$reg_exp_date,
					'vehicle_type'=>$post['vehicle_type'],
					'owner_name'=>$post['name'],
				    'owner_mobile'=>$post['mobile'],
				    'owner_email'=>$post['email'],
					'owner_address'=>$post['address'],
					'gst_no'=>$post['vendor_gst'],
                    'location'=>$post['location'],				
					'charge'=>$post['charge'],							
					'charge_type'=>$post['charge_type'],
                    "add_vendor_type"=> $post['add_vendor_type'],					
					'status'=>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );

	//	print_r($data);die;

		if(!empty($post['data_id']) && $post['data_id']>0)

		{    

           if($post['vehicle_type']==2){
            $booking_detail= $this->get_by_id($post['data_id']);
		    $vendor_id_new= $this->get_vendor_by_id($booking_detail['vendor_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['vendor_id']);
			$this->db->update('hms_medicine_vendors',$data_vendor);
            }
           $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->set('modified_by',$user_data['id']);
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ambulance_vehicle',$data);  
			
	      //echo $this->db->last_query();	die;	

		}

		else{   
		
		    $vendor_data= $this->get_vendor_by_id($post['vendor_id']);
			$vendor_code=generate_unique_id(11);
			if(count($vendor_data)>0){
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$post['vendor_id']);
			
			if($post['vehicle_type']==2){
			$this->db->update('hms_medicine_vendors',$data_vendor);
			$vendor_id= $post['vendor_id'];
			}
			}
			else
			{
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('vendor_id',$vendor_code);
			
			if($post['vehicle_type']==2){
			$this->db->insert('hms_medicine_vendors',$data_vendor);
			
            $vendor_id= $this->db->insert_id();			
			}
			else
			{
			$vendor_id=0;
			}
			}
		   $data = array(
					"vendor_id"=>$vendor_id,
					'branch_id'=>$user_data['parent_id'],
					'vehicle_no'=>$post['vehicle_no'],
					'chassis_no'=>$post['chassis_no'],
					'engine_no'=>$post['engine_no'],
					'registration_date'=>$reg_date,
					'registration_exp_date'=>$reg_exp_date,
					'vehicle_type'=>$post['vehicle_type'],
					'owner_name'=>$post['name'],
					'owner_mobile'=>$post['mobile'],
					'owner_email'=>$post['email'],
					'owner_address'=>$post['address'],
					'gst_no'=>$post['vendor_gst'],
					'location'=>$post['location'],
					//'contact_from'=>$contract_from,
					//'contact_to'=>$contract_to,								
					'charge'=>$post['charge'],								
					'charge_type'=>$post['charge_type'],	
                    "add_vendor_type"=> $post['add_vendor_type'],					
					'status'=>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR']

		         );
            $this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ambulance_vehicle',$data);   
//echo $this->db->last_query();die;			
	}

	}
	
	
	
	
	public function get_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ambulance_vehicle.*');
		$this->db->from('hms_ambulance_vehicle'); 
		$this->db->where('hms_ambulance_vehicle.id',$id);
		$this->db->where('hms_ambulance_vehicle.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ambulance_vehicle.is_deleted','0');
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
			$this->db->where('id',$id);
			$this->db->update('hms_ambulance_vehicle');
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
			//$this->db->set('is_deleted',1);
			//$this->db->set('deleted_by',$user_data['id']);
			//$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$where='id IN ('.$branch_ids.')';
			$this->db->delete('hms_ambulance_vehicle',$where);
			//echo $this->db->last_query();die;
    	} 
    }

    	function search_report_data(){
		$users_data = $this->session->userdata('auth_users');
			$search=$this->session->userdata('vehicle_search');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_ambulance_vehicle.*,hms_ambulance_location.location_name"); 
		$this->db->join('hms_ambulance_location','hms_ambulance_location.id = hms_ambulance_vehicle.location','left');
	
		$this->db->from($this->table); 
		$this->db->where('hms_ambulance_vehicle.is_deleted','0');
		$this->db->where('hms_ambulance_vehicle.branch_id',$users_data['parent_id']);
               /* 25-04-2020*/
            if(isset($search) && !empty($search))
		{
			
            if(!empty($search['location']))
			{
				$this->db->like('hms_ambulance_location.location_name',$search['location']);
			}
			if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_ambulance_vehicle.created_date >= "'.$start_date.'"');
			}
            if(!empty($search['end_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_ambulance_vehicle.created_date <= "'.$end_date.'"');
			}
			 if(!empty($search['vehicle_no']))
			{
				$this->db->like('hms_ambulance_vehicle.vehicle_no',$search['vehicle_no']);
			}
		}
            /*25-04-2020*/
		$query = $this->db->get(); 
	//	echo $this->db->last_query();die;
		return $query->result();
	}

	public function save_type()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'type'=>$post['type'],
					'status'=>$post['status'],
					'created_date'=>date('Y-m-d')
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ambulance_vehicle_type',$data);  
		}
		else{    
			
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ambulance_vehicle_type',$data);               
		} 	
	}
	
	
	
	public function get_vendor_by_id($id)
	{

		$this->db->select('hms_medicine_vendors.*');
		$this->db->from('hms_medicine_vendors'); 
		$this->db->where('hms_medicine_vendors.id',$id);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}

	public function vendor_list($vendor_id="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		$this->db->where('branch_id = "'.$users_data['parent_id'].'"');
		if(!empty($vendor_id))
		{
		 $this->db->where('id',$vendor_id);	
		}
		$this->db->where('status',1);	
		$this->db->where('vendor_type',5);
		$query = $this->db->get('hms_medicine_vendors');

		$result = $query->result(); 
		return $result; 
	}
	
	   public function check_unique_value($branch_id, $durat)
    {
    	$this->db->select('hms_ambulance_vehicle.*'); //vehicle_no
		$this->db->from('hms_ambulance_vehicle'); 
		$this->db->where('hms_ambulance_vehicle.branch_id',$branch_id);
		$this->db->where('hms_ambulance_vehicle.vehicle_no',$durat);
		$this->db->where('hms_ambulance_vehicle.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
    }
	
	

} 
?>