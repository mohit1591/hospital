<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Driver_model extends CI_Model 
{

		var $table = 'hms_ambulance_driver';
	var $column = array('hms_ambulance_driver.id','hms_ambulance_driver.driver_name','hms_ambulance_driver.licence_no','hms_ambulance_driver.mobile_no','hms_ambulance_driver.address', 'hms_ambulance_driver.status','hms_ambulance_driver.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
			$users_data = $this->session->userdata('auth_users');
			$sub_branch_details = $this->session->userdata('sub_branches_data');
			$parent_branch_details = $this->session->userdata('parent_branches_data');
			$this->db->select("hms_ambulance_driver.*"); 
			$this->db->from($this->table); 
			$this->db->where('hms_ambulance_driver.is_deleted','0');
            $this->db->where('hms_ambulance_driver.branch_id',$users_data['parent_id']);
	
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
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//$reg_no = generate_unique_id(2); 
		$dob=date('Y-m-d',strtotime($post['dob']));

		$employee_data = array(
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['driver_name'],
					'contact_no'=>$post['mobile_no'],
					'dob'=>$dob,
					'email'=>$post['email'],
					'address'=>$post['address'],
					'city_id'=>$post['city_id'],
					'state_id'=>$post['state_id'],
					'country_id'=>$post['country_id'],
					'postal_code'=>$post['pincode'], 
					'status'=>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					"type"=>2,
		         );
		        $data = array(
            					'branch_id'=>$user_data['parent_id'],
            					'driver_name'=>$post['driver_name'],
                                'licence_no'=>$post['licence_no'],
                                'dl_expiry_date'=>date('Y-m-d',strtotime($post['dl_expiry_date'])),
                                'mobile_no'=>$post['mobile_no'],
								'dob'=>$dob,
								'email'=>$post['email'],
								'guardian_name'=>$post['guardian_name'],
			                    'gaurdian_mob'=>$post['gaurdian_mob'],
			                    'relation'=>$post['relation'],
								'address'=>$post['address'],
								'city'=>$post['city_id'],
								'state'=>$post['state_id'],
								'country'=>$post['country_id'],
								'pincode'=>$post['pincode'], 
								'status'=>1,
								'ip_address'=>$_SERVER['REMOTE_ADDR']
		                    );
		//print_r($data);die;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            
            $this->db->set('modified_by',$user_data['id']);
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ambulance_driver',$data);  
			//echo $this->db->last_query(); exit;
			
			$this->db->set('modified_by',$user_data['id']);
            $this->db->where('staff_id',$post['data_id']);
			$this->db->update('hms_employees',$employee_data); 
			//echo $this->db->last_query(); 
			
			$this->db->select('*');
	    	$this->db->where('branch_id',$user_data['parent_id']);
	    	$this->db->where('staff_id',$post['data_id']);
	    	$this->db->where('status',1); 
	    	$this->db->where('is_deleted',0); 
	    	$query = $this->db->get('hms_employees');
	    	 $employee= $query->row_array();
	    	 //echo $this->db->last_query(); exit;

			/*if(in_array('365',$user_data['permission']['section'])) 
            {
    			$this->db->select('*');
    	    	$this->db->where('branch_id',$user_data['parent_id']);
    	    	$this->db->where('employee_id',$employee['id']);
    	    	$this->db->where('status',1); 
    	    	$this->db->where('is_deleted',0); 
    	    	$leave_query = $this->db->get('prl_leave_details');
    	    	if($leave_query->num_rows() > 0)
    	    	{
    
    	    	}
	    	else{
	    		$this->db->delete('prl_leave_details',array('employee_id'=>$post['data_id']));
    	    	$this->db->select('*');
    	    	$this->db->where('branch_id',$user_data['parent_id']);
    	    	$this->db->where('status',1); 
    	    	$this->db->where('is_deleted',0); 
    	    	$this->db->order_by('leave_type_id','ASC'); 
    	    	$query = $this->db->get('prl_leave_master');
    	    	$leavelist = $query->result();
	    	if(!empty($leavelist))
			{
				$this->db->select('*');
		    	$this->db->where('branch_id',$user_data['parent_id']);
		    	$this->db->where('employee_id',$employee['id']);
		    	$query = $this->db->get('prl_leave_details');
		    	if($query->num_rows() > 0)
		    	{
				}
				else{
				foreach($leavelist as $leave)
				{
			       $employee_data = array(     
					'branch_id'=>$user_data['parent_id'],
					"employee_id"=>$employee['id'],
					"applicable_date"=>date('Y-m-d'),
					'leave_type'=>$leave->leave_type_id,
					'no_of_leave'=>$leave->leave_type_day,
					'duration'=>$leave->leave_month,
	           		'created_by'=>$user_data['id'],
	           		'created_date'=>date('Y-m-d H:i:s'),
	           		'ip_address' => $_SERVER['REMOTE_ADDR']
				        ); 

				$this->db->insert('prl_leave_details',$employee_data);

				}
			  }
			 }
			}
	     }*/
			
			
		}
		else{   
			
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ambulance_driver',$data); 
			$id=$this->db->insert_id();
			
			$reg_no = generate_unique_id(2); 
			$this->db->set('reg_no',$reg_no);
			$this->db->set('staff_id',$id);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_employees',$employee_data); 
			$emp_id=$this->db->insert_id();  
			
			/*if(in_array('365',$user_data['permission']['section'])) 
              {
				
			$this->db->select('*');
	    	$this->db->where('branch_id',$user_data['parent_id']);
	    	$this->db->where('status',1); 
	    	$this->db->where('is_deleted',0); 
	    	$this->db->order_by('leave_type_id','ASC'); 
	    	$query = $this->db->get('prl_leave_master');
	    	$leavelist = $query->result();
	    	if(!empty($leavelist))
			{
				foreach($leavelist as $leave)
				{
			       $employee_data = array(     
					'branch_id'=>$user_data['parent_id'],
					"employee_id"=>$emp_id,
					"applicable_date"=>date('Y-m-d',strtotime($post['joining_date'])),
					'leave_type'=>$leave->leave_type_id,
					'no_of_leave'=>$leave->leave_type_day,
					'duration'=>$leave->leave_month,
	           		'created_by'=>$user_data['id'],
	           		'created_date'=>date('Y-m-d H:i:s'),
	           		'ip_address' => $_SERVER['REMOTE_ADDR']
				         ); 

				$this->db->insert('prl_leave_details',$employee_data);
				}
			}
				
			
		  }*/   
		
		} 	
		//echo $this->db->last_query();die;
	}

	
	public function get_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ambulance_driver.*');
		$this->db->from('hms_ambulance_driver'); 
		$this->db->where('hms_ambulance_driver.id',$id);
		$this->db->where('hms_ambulance_driver.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ambulance_driver.is_deleted','0');
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
			$this->db->update('hms_ambulance_driver');
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
    		//print_r($branch_ids);die;
			$user_data = $this->session->userdata('auth_users');
		
			//$this->db->set('deleted_by',$user_data['id']);
			//$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$where='id IN ('.$branch_ids.')';
		//	$this->db->where('id IN ('.$branch_ids.')');
			$this->db->delete('hms_ambulance_driver',$where);
			//echo $this->db->last_query();die;
    	} 
    }

    	function search_report_data(){
		$users_data = $this->session->userdata('auth_users');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_ambulance_driver.*"); 
		$this->db->from($this->table); 
		$this->db->where('hms_ambulance_driver.is_deleted','0');
		//$this->db->where('hms_ambulance_driver.type','0');
		$this->db->where('hms_ambulance_driver.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		return $query->result();
	}

  
} 
?>