<?php
class Hospital_model extends CI_Model 
{
	var $table = 'hms_hospital';
	var $column = array('hms_hospital.id','hms_hospital.hospital_code','hms_hospital.hospital_name', 'hms_hospital.mobile_no','hms_cities.city', 'hms_hospital.status', 'hms_hospital.created_date', 'hms_hospital.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('hospital_search'); 
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_hospital.*, hms_cities.city, hms_state.state"); 
		$this->db->join('hms_cities','hms_cities.id=hms_hospital.city_id','left');
		$this->db->join('hms_state','hms_state.id=hms_hospital.state_id','left');
		$this->db->where('hms_hospital.is_deleted','0'); 

		//$this->db->where('hms_hospital.branch_id',$users_data['parent_id']);
        if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_hospital.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_hospital.branch_id',$users_data['parent_id']);
		}
        if(isset($search) && !empty($search))
        {
        	if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d H:i:s',strtotime($search['start_date']));
				$this->db->where('hms_hospital.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_hospital.created_date <= "'.$end_date.'"');
			}

			
			if(isset($search['hospital_name']) && !empty($search['hospital_name']))
			{
				$this->db->where('hms_hospital.hospital_name LIKE "%'.$search['hospital_name'].'%"');
			}

			if(isset($search['hospital_code']) && !empty($search['hospital_code']))
			{
				$this->db->where('hms_hospital.hospital_code LIKE "%'.$search['hospital_code'].'%"');
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_hospital.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}
        }
		$i = 0;
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
		$this->db->select("hms_hospital.*, hms_cities.city, hms_state.state, hms_countries.country, path_rate_plan.title as rate_plan, hms_employees.name as marketing_person,hms_users.username"); 
		$this->db->from('hms_hospital'); 
		$this->db->where('hms_hospital.id',$id);
		$this->db->where('hms_hospital.is_deleted','0'); 
		$this->db->join('hms_users','hms_users.parent_id=hms_hospital.id AND hms_users.users_role=3','left');
		
		$this->db->join('hms_employees','hms_employees.id=hms_hospital.marketing_person_id','left');
		$this->db->join('hms_countries','hms_countries.id=hms_hospital.country_id','left');
		$this->db->join('hms_cities','hms_cities.id=hms_hospital.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_hospital.state_id','left');
        $this->db->join('path_rate_plan','path_rate_plan.id=hms_hospital.rate_plan_id','left');
		$query = $this->db->get(); 

		return $query->row_array();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$reg_no = generate_unique_id(29); 
		$data = array(  
							"hospital_name"=>$post['hospital_name'],
							"mobile_no"=>$post['mobile_no'],
							"address"=>$post['address'],
							"city_id"=>$post['city_id'],
							"state_id"=>$post['state_id'],
							"country_id"=>$post['country_id'],
							"pincode"=>$post['pincode'],
							"email"=>$post['email'],
							"alt_mobile_no"=>$post['alt_mobile_no'],
							"landline_no"=>$post['landline_no'],
							"status"=>$post['status'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'], 
						); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{     
			
				 $comission_list = $this->session->userdata('hospital_comission_data');
				 if(!empty($comission_list))
				 { 
				 	$this->db->where('hospital_id',$post['data_id']);
				 	$this->db->delete('hms_hospital_to_comission');
				 	foreach($comission_list['data'] as $key=>$val)
				 	{
				 		if(!empty($val['numb']))
				 		{
							$dept_com_arr = array(
							'dept_id'=>$key,
							'rate_type'=>$val['type'],
							'hospital_id'=>$post['data_id'],
							'rate'=>$val['numb'],
							'created_by'=>$user_data['id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							); 
							$this->db->insert('hms_hospital_to_comission',$dept_com_arr);
				 		} 
				 	}
				 }
			
			
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_hospital',$data); 
			$hospital_id = $post['data_id'];
			
		}
		else
		{    
			//"doctor_code"=>$post['doctor_code'],
			$this->db->set('hospital_code',$reg_no);
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('branch_id',$user_data['parent_id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_hospital',$data); 
			$data_id = $this->db->insert_id();
			
			$hospital_id = $data_id;

			

			
				 $comission_list = $this->session->userdata('hospital_comission_data');
				 if(!empty($comission_list))
				 {
				 	$this->db->where('hospital_id',$data_id);
				 	$this->db->delete('hms_hospital_to_comission');
				 	
				 	foreach($comission_list['data'] as $key=>$val)
				 	{
				 		if(!empty($val['numb']))
				 		{
							$dept_com_arr = array(
							'dept_id'=>$key,
							'rate_type'=>$val['type'],
							'hospital_id'=>$data_id,
							'rate'=>$val['numb'],
							'created_by'=>$user_data['id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_hospital_to_comission',$dept_com_arr);
				 		} 
				 	}
				 }
			

			
		}
		return $hospital_id;	 	
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
			$this->db->update('hms_hospital');
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
			$this->db->update('hms_hospital');
    	} 
    }

    public function hospital_list()
    {
    	 $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('is_deleted','0');
        $this->db->where('hms_hospital.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_hospital');
        $result = $query->result(); 
        return $result; 
    }

    

    

    
    public function hospital_comission_data($doc_id="")
    {
    	if(!empty($doc_id) && $doc_id>0)
    	{
    		$comission_data = [];

			$this->db->select('*');  
			$this->db->where('hospital_id',$doc_id);
			$query = $this->db->get('hms_hospital_to_comission');
			$result = $query->result(); 

			if(!empty($result))
			{ 
				foreach($result as $comission)
				{
                   $comission_data['data'][$comission->dept_id]['numb'] = $comission->rate;
                   $comission_data['data'][$comission->dept_id]['type'] = $comission->rate_type; 
				}
			}
			return $comission_data;
    	}
    }


    function search_hospital_data()
    {
    	
		$users_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('hospital_search');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_hospital.*, hms_cities.city, hms_state.state"); 
		$this->db->join('hms_cities','hms_cities.id=hms_hospital.city_id','left');
		$this->db->join('hms_state','hms_state.id=hms_hospital.state_id','left');
		$this->db->where('hms_hospital.is_deleted','0');
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_hospital.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_hospital.branch_id',$users_data['parent_id']);
		} 
		//$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d h:i:s',strtotime($search['start_date']));
				$this->db->where('hms_hospital.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d h:i:s',strtotime($search['end_date']));
				$this->db->where('hms_hospital.created_date >= "'.$end_date.'"');
			}

			// if(!empty($search['simulation_id']))
			// {
			// 	$this->db->where('simulation_id',$search['simulation_id']);
			// }

			if(!empty($search['hospital_name']))
			{
				$this->db->where('hms_hospital.hospital_name LIKE "'.$search['hospital_name'].'%"');
			}

			if(!empty($search['hospital_code']))
			{
				$this->db->where('hms_hospital.hospital_code',$search['hospital_code']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_hospital.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			// if(isset($search['gender']) && $search['gender']!="")
			// {
			// 	$this->db->where('gender',$search['gender']);
			// }

			if(!empty($search['address']))
			{
				$this->db->where('hms_hospital.address LIKE "'.$search['address'].'%"');
			}

			if(!empty($search['country_id']))
			{
				$this->db->where('hms_hospital.country_id',$search['country_id']);
			}

			if(!empty($search['state_id']))
			{
				$this->db->where('hms_hospital.state_id',$search['state_id']);
			}

			if(!empty($search['city_id']))
			{
				$this->db->where('hms_hospital.city_id',$search['city_id']);
			}

			if(!empty($search['pincode']))
			{
				$this->db->where('hms_hospital.pincode LIKE "'.$search['pincode'].'%"');
			}
			if(!empty($search['hospital_email']))
			{
				$this->db->where('hms_hospital.email LIKE "'.$search['hospital_email'].'%"');
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_hospital.status',$search['status']);
			}


		}
	    $query = $this->db->get(); 

		$data= $query->result();
		
		return $data;
	}
	
	 public function check_unique_value($branch_id, $name, $id='')
    {
    	$this->db->select('hms_hospital.*');
		$this->db->from('hms_hospital'); 
		$this->db->where('hms_hospital.branch_id',$branch_id);
		$this->db->where('hms_hospital.hospital_name',$name);
		if(!empty($id))
		$this->db->where('hms_hospital.id !=',$id);
		$this->db->where('hms_hospital.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$result=$query->row_array();
		if(!empty($result))
		{
			return 1;
		}
		else{
			return 0;
		}
    }
} 
?>