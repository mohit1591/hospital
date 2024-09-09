<?php
class Doctors_model extends CI_Model 
{
	var $table = 'hms_doctors';
	var $column = array('hms_doctors.id','hms_doctors.doctor_code','hms_doctors.doctor_name','hms_specialization.specialization', 'hms_doctors.mobile_no', 'hms_doctors.doctor_type','hms_doctors.doctor_pay_type', 'hms_employees.name',  'hms_doctors.consultant_charge', 'hms_doctors.emergency_charge', 'hms_doctors.dob', 'hms_doctors.anniversary', 'hms_doctors.doctor_panel_type', 'hms_doctors.schedule_type', 'hms_doctors.address', 'hms_doctors.email', 'hms_doctors.alt_mobile_no', 'hms_doctors.landline_no', 'hms_doctors.pan_no', 'hms_doctors.doc_reg_no', 'hms_doctors.per_patient_timing' , 'hms_doctors.status' );  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('doctor_search'); 
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_doctors.*, hms_cities.city, hms_state.state, hms_specialization.specialization, (CASE WHEN hms_doctors.doctor_pay_type=1 THEN 'Commission' ELSE 'Transaction' END ) as sharing_pattern, hms_employees.name as marketing_person, (CASE WHEN hms_doctors.doctor_panel_type=1 THEN 'Normal' WHEN hms_doctors.doctor_panel_type=2 THEN 'Panel' ELSE '' END ) as panel_type, (CASE WHEN hms_doctors.schedule_type=1 THEN 'Yes' ELSE 'No' END ) as doc_schedule_type, 
			concat_ws(', ',hms_doctors.address,hms_cities.city, hms_state.state) as doc_address
		 " ); 
		$this->db->join('hms_cities','hms_cities.id=hms_doctors.city_id','left');
		$this->db->join('hms_state','hms_state.id=hms_doctors.state_id','left');

		/** coded on 26-feb-2018 **/
		$this->db->join('hms_specialization', 'hms_specialization.id=hms_doctors.specilization_id', 'left');
		$this->db->join('hms_employees','hms_employees.id=hms_doctors.marketing_person_id','left');
		/** Coded on 26-feb-2018  **/


		$this->db->where('hms_doctors.is_deleted','0'); 
		//$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_doctors.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		}
		
        if(isset($search) && !empty($search))
        {
        	

            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_doctors.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&  !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_doctors.created_date <= "'.$end_date.'"');
			}

			if(isset($search['doctor_type']) && $search['doctor_type']!="")
			{
				$this->db->where('hms_doctors.doctor_type',$search['doctor_type']);
			}

			if(isset($search['specialization_id']) && !empty($search['specialization_id']))
			{
				$this->db->where('hms_doctors.specilization_id',$search['specialization_id']);
			}

			if(isset($search['doctor_name']) && !empty($search['doctor_name']))
			{
				$this->db->where('hms_doctors.doctor_name LIKE "%'.$search['doctor_name'].'%"');
			}

			if(isset($search['doctor_code']) && !empty($search['doctor_code']))
			{
				$this->db->where('hms_doctors.doctor_code LIKE "%'.$search['doctor_code'].'%"');
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_doctors.mobile_no LIKE "'.$search['mobile_no'].'%"');
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
	    $users_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('doctor_search'); 
		$this->db->from($this->table);
		
		$this->db->where('hms_doctors.is_deleted','0'); 
		//$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_doctors.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		}
		
        if(isset($search) && !empty($search))
        {
        	

            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_doctors.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&  !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_doctors.created_date <= "'.$end_date.'"');
			}
        }
		
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("hms_doctors.*, hms_cities.city, hms_state.state, hms_specialization.specialization, hms_countries.country, path_rate_plan.title as rate_plan, hms_employees.name as marketing_person,hms_users.username"); 
		$this->db->from('hms_doctors'); 
		$this->db->where('hms_doctors.id',$id);
		$this->db->where('hms_doctors.is_deleted','0'); 
		$this->db->join('hms_users','hms_users.parent_id=hms_doctors.id AND hms_users.users_role=3','left');
		$this->db->join('hms_specialization','hms_specialization.id=hms_doctors.specilization_id','left');
		$this->db->join('hms_employees','hms_employees.id=hms_doctors.marketing_person_id','left');
		$this->db->join('hms_countries','hms_countries.id=hms_doctors.country_id','left');
		$this->db->join('hms_cities','hms_cities.id=hms_doctors.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_doctors.state_id','left');
        $this->db->join('path_rate_plan','path_rate_plan.id=hms_doctors.rate_plan_id','left');
		$query = $this->db->get(); 
		$result = $query->row_array();
		
		$this->db->select('hms_doctors_schedule.*,hms_days.day_name');
		$this->db->join('hms_doctors','hms_doctors.id = hms_doctors_schedule.doctor_id');
		$this->db->join('hms_days','hms_days.id = hms_doctors_schedule.available_day'); 
		$this->db->where('hms_doctors_schedule.doctor_id = "'.$id.'"');
		$this->db->from('hms_doctors_schedule');
		$result['doctor_availablity']=$this->db->get()->result();

		return $result;
	}
	
	public function save($filename="")
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		if(isset($post['schedule_type']))
		{
			$schedule_type = $post['schedule_type'];
		}
		else
		{
			$schedule_type =0;	
		}
		$doctor_panel_type='';
		if(!empty($post['doctor_panel_type']))
		{
			$doctor_panel_type = $post['doctor_panel_type'];
		}
		$doctor_pay_type='';
		if(!empty($post['doctor_pay_type']))
		{
			$doctor_pay_type = $post['doctor_pay_type'];
		}
		 
		//$reg_no = generate_unique_id(3); 
		$data = array(  
							
							"doctor_type"=>$post['doctor_type'],
							"doctor_name"=>$post['doctor_name'],
							'dob'=>date('Y-m-d', strtotime($post['dob'])),
							'anniversary'=>date('Y-m-d', strtotime($post['anniversary'])),
							"specilization_id"=>$post['specilization_id'],
							"mobile_no"=>$post['mobile_no'],
							"address"=>$post['address'],
							"city_id"=>$post['city_id'],
							"state_id"=>$post['state_id'],
							"country_id"=>$post['country_id'],
							"pincode"=>$post['pincode'],
							"email"=>$post['email'],
							"alt_mobile_no"=>$post['alt_mobile_no'],
							"landline_no"=>$post['landline_no'],
							"doc_reg_no"=>$post['doc_reg_no'],
							"pan_no"=>$post['pan_no'],
							"marketing_person_id"=>$post['marketing_person_id'],
							"doctor_pay_type"=>$doctor_pay_type,
							"doctor_panel_type"=>$doctor_panel_type,
							"consultant_charge"=>$post['consultant_charge'],
							"emergency_charge"=>$post['emergency_charge'],
							"status"=>$post['status'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'], 
							'per_patient_timing'=>$post['per_patient_timing'], 
							'schedule_type'=>$schedule_type,
							'qualification'=>$post['qualification'],
							'seprate_header'=>$post['seprate_header'],
                             'header_content'=>$post['header_content'],
                             'opd_header'=>$post['opd_header'],
                            'billing_header'=>$post['billing_header'],
                            'prescription_header'=>$post['prescription_header'],
							/*'schedule_type'=>$post['schedule_type'],
							'days'=>$post['days'],
							'timings'=>$post['timings']*/
				         ); 
         
		if(!empty($post['data_id']) && $post['data_id']>0)
		{     
			if($post['doctor_pay_type']==1)
			{
				 $comission_list = $this->session->userdata('comission_data');
				 if(!empty($comission_list))
				 { 
				 	$this->db->where('doctor_id',$post['data_id']);
				 	$this->db->delete('hms_doctors_to_comission');
				 	foreach($comission_list['data'] as $key=>$val)
				 	{
				 		if(!empty($val['numb']))
				 		{
							$dept_com_arr = array(
							'dept_id'=>$key,
							'rate_type'=>$val['type'],
							'doctor_id'=>$post['data_id'],
							'rate'=>$val['numb'],
							'created_by'=>$user_data['id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							); 
							$this->db->insert('hms_doctors_to_comission',$dept_com_arr);
				 		} 
				 	}
				 }
			}

			else if($post['doctor_pay_type']==2)
			{
				$this->db->set('rate_plan_id',$post['rate_plan_id']);
			}
			if(!empty($filename))
			{
				$this->db->set('signature',$filename);
			}
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_doctors',$data); 
			$doctor_id = $post['data_id'];
			if(!empty($post['data_id']) && !empty($post['password']))
			{
				$this->db->set('password',md5($post['password']));
				$this->db->where('users_role',3);
				$this->db->where('parent_id',$post['data_id']);
				$this->db->update('hms_users');
			}
            

            if(!empty($post['data_id']))
			{
				$this->db->where('doctor_id',$post['data_id']);
				$this->db->where("branch_id",$user_data['parent_id']);
		        $this->db->delete('hms_doctors_schedule');
		        //echo "dsd"; exit;
		    }
		    //echo count($post['time']);    
			if(!empty($post['time']))
			{
				
				//echo $this->db->last_query(); exit;
				$n=0;
				foreach($post['time'] as $key=>$val)
	            {
	            	//echo '<pre>'; print_r($val); exit; 
	            	//$to = $val['to'][$n]; 
					//$from = $val['from'][$n];
						$k=0;
	            		foreach ($val['from'] as $value) 
	            		{
	            			$to = $val['to'][$k]; 
							$from = $value;
	            			$data = array(
	                               "branch_id"=>$user_data['parent_id'],
	                               "doctor_id"=>$post['data_id'],
	                               "available_day"=>$key,
	                               "from_time"=>date('H:i:s', strtotime(date('d-m-Y').' '.$from)),
	                               "to_time"=>date('H:i:s', strtotime(date('d-m-Y').' '.$to))
	                             );
	            			$this->db->insert('hms_doctors_schedule',$data);	
	            			$k++;
	            		}	
	            	
		            
	            	$n++;
	            	//echo $this->db->last_query(); 
	            }
	        }


		}
		else
		{    
			//"doctor_code"=>$post['doctor_code'],
			if($post['doctor_pay_type']==2)
			{
				$this->db->set('rate_plan_id',$post['rate_plan_id']);
			}
			if(!empty($filename))
			{
				$this->db->set('signature',$filename);
			}
			$reg_no = generate_unique_id(3);
			$this->db->set('doctor_code',$reg_no);
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('branch_id',$user_data['parent_id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_doctors',$data); 
			$data_id = $this->db->insert_id();
			$doctor_id = $data_id;

			$data = array(     
					"users_role"=>3,
					"parent_id"=>$data_id,
					"username"=>'DOC000'.$data_id,
					"password"=>md5('PASS'.$data_id),
					"email"=>$post['email'], 
					"status"=>'1',
					"ip_address"=>$_SERVER['REMOTE_ADDR'],
					"created_by"=>$user_data['id'],
					"created_date" =>date('Y-m-d H:i:s')
				         ); 
			$this->db->insert('hms_users',$data);	
            $users_id = $this->db->insert_id();    
		/*	
			$this->db->select('*');
			$this->db->where('users_role','3');
			$query = $this->db->get('hms_permission_to_role');		 
			$permission_list = $query->result();
			if(!empty($permission_list))
			{
				foreach($permission_list as $permission)
				{
					$data = array(
					        'users_role' =>3,
					        'users_id' => $users_id,
					        'master_id' => $doctor_id,
					        'section_id' => $permission->section_id,
					        'action_id' => $permission->action_id, 
					        'permission_status' => '1',
					        'ip_address' => $_SERVER['REMOTE_ADDR'],
					        'created_by' =>$user_data['id'],
					        'created_date' =>date('Y-m-d H:i:s'),
					     );
					$this->db->insert('hms_permission_to_users',$data);
				}
			}*/


			if($post['doctor_pay_type']==1)
			{
				 $comission_list = $this->session->userdata('comission_data');
				 if(!empty($comission_list))
				 {
				 	$this->db->where('doctor_id',$data_id);
				 	$this->db->delete('hms_doctors_to_comission');
				 	
				 	foreach($comission_list['data'] as $key=>$val)
				 	{
				 		if(!empty($val['numb']))
				 		{
							$dept_com_arr = array(
							'dept_id'=>$key,
							'rate_type'=>$val['type'],
							'doctor_id'=>$data_id,
							'rate'=>$val['numb'],
							'created_by'=>$user_data['id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_doctors_to_comission',$dept_com_arr);
				 		} 
				 	}
				 }
			}

		

			if(!empty($post['time']))
			{
				
				//echo $this->db->last_query(); exit;
				$n=0;
				foreach($post['time'] as $key=>$val)
	            {
	            		$k=0;
	            		foreach ($val['from'] as $value) 
	            		{
	            			$to = $val['to'][$k]; 
							$from = $value;
	            			$data = array(
	                               "branch_id"=>$user_data['parent_id'],
	                               "doctor_id"=>$doctor_id,
	                               "available_day"=>$key,
	                               "from_time"=>date('H:i:s', strtotime(date('d-m-Y').' '.$from)),
	                               "to_time"=>date('H:i:s', strtotime(date('d-m-Y').' '.$to))
	                             );
	            			$this->db->insert('hms_doctors_schedule',$data);	
	            		}	
	            	
		            
	            	$n++;
	            	//echo $this->db->last_query(); 
	            }
	        } 

		}
		return $doctor_id;	 	
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
			$this->db->update('hms_doctors');
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
			$this->db->update('hms_doctors');
    	} 
    }

    public function doctors_list()
    {
    	 $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('is_deleted','0');
        $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        return $result; 
    }

    public function attended_doctors_list($specilization_id="",$branch_id="")
    {
    	$users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('is_deleted','0');
        if(isset($branch_id) && !empty($branch_id))
        {
            $this->db->where('branch_id',$branch_id);
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']);    
        }
        //$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        if(!empty($specilization_id))
        {
            $this->db->where('specilization_id',$specilization_id); 
        }
        $this->db->where('(doctor_type=1 OR doctor_type=2)');
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        return $result; 
    }


    public function referral_doctors_list($specilization_id="",$branch_id="")
    {
    	$users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('is_deleted','0');
        if(isset($branch_id) && !empty($branch_id))
        {
            $this->db->where('branch_id',$branch_id);
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']);    
        }
        //$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        if(!empty($specilization_id))
        {
            $this->db->where('specilization_id',$specilization_id); 
        }
        $this->db->where('(doctor_type="0" OR doctor_type="2")');
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        return $result; 
    }
    

    public function doctors_list_specilization($specilization_id)
    {
    	$users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('is_deleted','0');
        $this->db->where('specilization_id',$specilization_id);
        $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        return $result; 
    } 

    public function doc_comission_data($doc_id="")
    {
    	if(!empty($doc_id) && $doc_id>0)
    	{
    		$comission_data = [];

			$this->db->select('*');  
			$this->db->where('doctor_id',$doc_id);
			$query = $this->db->get('hms_doctors_to_comission');
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


    function search_doctor_data()
    {
    	
		$users_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('doctor_search');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_doctors.*, hms_cities.city, hms_state.state"); 
		$this->db->join('hms_cities','hms_cities.id=hms_doctors.city_id','left');
		$this->db->join('hms_state','hms_state.id=hms_doctors.state_id','left');
		$this->db->where('hms_doctors.is_deleted','0');
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_doctors.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		} 
		//$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
			
           
            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_doctors.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&  !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_doctors.created_date <= "'.$end_date.'"');
			}

			if(!empty($search['doctor_name']))
			{
				$this->db->where('hms_doctors.doctor_name LIKE "'.$search['doctor_name'].'%"');
			}

			if(!empty($search['doctor_code']))
			{
				$this->db->where('hms_doctors.doctor_code',$search['doctor_code']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_doctors.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			// if(isset($search['gender']) && $search['gender']!="")
			// {
			// 	$this->db->where('gender',$search['gender']);
			// }

			if(!empty($search['address']))
			{
				$this->db->where('hms_doctors.address LIKE "'.$search['address'].'%"');
			}

			if(!empty($search['country_id']))
			{
				$this->db->where('hms_doctors.country_id',$search['country_id']);
			}

			if(!empty($search['state_id']))
			{
				$this->db->where('hms_doctors.state_id',$search['state_id']);
			}

			if(!empty($search['city_id']))
			{
				$this->db->where('hms_doctors.city_id',$search['city_id']);
			}

			if(!empty($search['pincode']))
			{
				$this->db->where('hms_doctors.pincode LIKE "'.$search['pincode'].'%"');
			}
			if(!empty($search['doctor_email']))
			{
				$this->db->where('hms_doctors.doctor_email LIKE "'.$search['doctor_email'].'%"');
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_doctors.status',$search['status']);
			}


		}
            $this->db->order_by('hms_doctors.id','DESC');
	    $query = $this->db->get(); 

		$data= $query->result();
		
		return $data;
	}

	public function get_panel_list($doctor_id="",$panel_id='')
	{	
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_insurance_company.*,(CASE WHEN hms_doctor_panel_charge.charge > 0 THEN hms_doctor_panel_charge.charge ELSE  (select doctor_charge.consultant_charge from hms_doctors as doctor_charge where doctor_charge.id ="'.$doctor_id.'" AND doctor_charge.is_deleted=0 AND doctor_charge.branch_id ="'.$user_data['parent_id'].'")  END) as charge,(CASE WHEN hms_doctor_panel_charge.emergency_charge > 0 THEN hms_doctor_panel_charge.emergency_charge ELSE (select doctor_emergency_charge.emergency_charge from hms_doctors as doctor_emergency_charge where doctor_emergency_charge.id ="'.$doctor_id.'" AND doctor_emergency_charge.is_deleted=0 AND doctor_emergency_charge.branch_id ="'.$user_data['parent_id'].'") END) as charge_emergency,hms_doctor_panel_charge.id as charge_id');
		$this->db->from('hms_insurance_company');
		$this->db->join('hms_doctor_panel_charge','hms_doctor_panel_charge.panel_id=hms_insurance_company.id and hms_doctor_panel_charge.doctor_id = "'.$doctor_id.'"','left');
		$this->db->join('hms_doctors','hms_doctors.id=hms_doctor_panel_charge.doctor_id AND hms_doctors.id = "'.$doctor_id.'"','left'); 
		//$this->db->where('hms_doctors.id',$doctor_id);
		$this->db->where('hms_insurance_company.is_deleted','0');
		$this->db->where('hms_insurance_company.status',1);
		$this->db->where('hms_insurance_company.branch_id',$user_data['parent_id']); 
		$this->db->order_by('hms_insurance_company.insurance_company','ASC');
		$query = $this->db->get(); 
		//echo $this->db->last_query(); exit;
		$result = $query->result_array();
		
		return $result;
	}

	public function save_panel_charge()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		if(!empty($post['doctor_id']))
		{ 
			$this->db->where('doctor_id',$post['doctor_id']);
			$this->db->delete('hms_doctor_panel_charge');
			//print_r($post['charge']); exit;
			foreach($post['charge'] as $key=>$val)
			{
				$doctor_panel_charge  = array(
												"branch_id"=>$user_data['parent_id'],
												'doctor_id'=>$post['doctor_id'],
												'panel_id'=>$key,
												'charge'=>$val,
												'emergency_charge'=>$post['emergency_charge'][$key],
										     ); 
				
				$this->db->insert('hms_doctor_panel_charge',$doctor_panel_charge);
				
			}
		}

	}
	
	public function save_all_doctors($doctors_all_data = array())
	{
		$users_data = $this->session->userdata('auth_users');
        if(!empty($doctors_all_data))
        {
            foreach($doctors_all_data as $doctor_data)
            {
            	//print_r($doctor_data);
            	if(!empty($doctor_data['doctor_name']))
            	{
            		

				//country start
					$country='';
            		if(!empty($doctor_data['country']))
            		{
            			//echo "hello"; print_r($doctor_data['country']);
		            	$this->db->select("hms_countries.*");
					    $this->db->from('hms_countries'); 
		                $this->db->where('LOWER(hms_countries.country)',strtolower($doctor_data['country'])); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $country_data = $query->result_array();

					    if(!empty($country_data))
					    {
						    $country = $country_data[0]['id'];
					    }
					    else
					    {
						 	$country_insert_data = array(
							'code'=>'',
							'country'=>$doctor_data['country'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_countries',$country_insert_data);
							$country = $this->db->insert_id();
					    }
					}
					//country end
					//state start
					$state='';
            		if(!empty($doctor_data['state']) && !empty($country))
            		{
		            	$this->db->select("hms_state.*");
					    $this->db->from('hms_state'); 
		                $this->db->where('LOWER(hms_state.state)',strtolower($doctor_data['state']));
		                $this->db->where('country_id',$country); 
					      
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $state_data = $query->result_array();

					    if(!empty($state_data))
					    {
						    $state = $state_data[0]['id'];
					    }
					    else
					    {
						 	$state_insert_data = array(
							'country_id'=>$country,
							'state'=>$doctor_data['state'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'modified_date'=>date('Y-m-d H:i:s'),
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_state',$state_insert_data);
							$state = $this->db->insert_id();
					    }
					}

					//state end

					//city start
					$city='';
            		if(!empty($doctor_data['city']) && !empty($country) && !empty($state))
            		{
		            	$this->db->select("hms_cities.*");
					    $this->db->from('hms_cities'); 
		                $this->db->where('LOWER(hms_cities.city)',strtolower($doctor_data['city']));
		                $this->db->where('country_id',$country);
		                $this->db->where('state_id',$state); 
					      
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $city_data = $query->result_array();

					    if(!empty($city_data))
					    {
						    $city = $city_data[0]['id'];
					    }
					    else
					    {
						 	$city_insert_data = array(
							'country_id'=>$country,
							'state_id'=>$state,
							'city'=>$doctor_data['city'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'modified_date'=>date('Y-m-d H:i:s'),
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_cities',$city_insert_data);
							$city = $this->db->insert_id();
					    }
					}
                   
                   //specilization start
					$specilization='';
					if(!empty($doctor_data['specialization']))
            		{
            			
		            	$this->db->select("hms_specialization.*");
					    $this->db->from('hms_specialization'); 
		                $this->db->where('LOWER(hms_specialization.specialization)',strtolower($doctor_data['specialization'])); 
		                $this->db->where('hms_specialization.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $specialization_data = $query->result_array();

					    if(!empty($specialization_data))
					    {
						    $specialization = $specialization_data[0]['id'];
						   
					    }
					    else
					    {
						 	$specialization_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'specialization'=>$doctor_data['specialization'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_specialization',$specialization_insert_data);
							$specialization = $this->db->insert_id();
					    }
					}


                   //specilization end
				   //doctor_type start
					$doctor_type='';
					if(!empty($doctor_data['doctor_type']))
            		{
            			//echo'hello';print_r($doctor_data['doctor_type']);die;
            			if(($doctor_data['doctor_type']=='Referral') ||($doctor_data['doctor_type']=='referral'))
            			{
            				$doctor_type=0;
            			}
            			else if(($doctor_data['doctor_type']=='Attended') ||($doctor_data['doctor_type']=='attended'))
            			{
            				$doctor_type=1;
            			}
            			else if(($doctor_data['doctor_type']=='Both') ||($doctor_data['doctor_type']=='both'))
            			{
            				$doctor_type=2;
            			}
            			else
            			{
            				$doctor_type=0;
            			}

		            	
					}


                        //doctor_type end
					  //specilization start
					$marketing_person_id='';
					if(!empty($doctor_data['marketing_person_id']))
            		{
            			//echo "hello"; print_r($doctor_data['specialization']);
		            	$this->db->select("hms_employees.*");
					    $this->db->from('hms_employees'); 
		                $this->db->where('LOWER(hms_employees.name)',strtolower($doctor_data['marketing_person_id'])); 
		                $this->db->where('hms_employees.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $employee_data = $query->result_array();

					    if(!empty($employee_data))
					    {
						    $marketing_person_id = $employee_data[0]['id'];
					    }
					    else
					    {
						 	$employee_insert_data = array(
							'name'=>$doctor_data['marketing_person_id'],
							'status'=>1,
							'branch_id'=>$users_data['parent_id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_employees',$employee_insert_data);
							$marketing_person_id = $this->db->insert_id();
					    }
					}

					$doctor_panel_type='';
					if(!empty($doctor_data['doctor_panel_type']))
            		{
            			if(($doctor_data['doctor_panel_type']=='Normal')||($doctor_data['doctor_panel_type']=='normal'))
            			{
            				$doctor_panel_type='1';
            			}
            			else if(($doctor_data['doctor_panel_type']=='Panel')||($doctor_data['doctor_panel_type']=='panel'))
            			{
                         $doctor_panel_type='2';
            			}
            			else{
            				  $doctor_panel_type='1';
            			}
            		}

            		$dob='';
					if(!empty($doctor_data['dob']))
            		{
            			if(!empty($doctor_data['dob']))
            			{
            				$dob=date('Y-m-d', strtotime($doctor_data['dob']));
            			}
            			else
            			{
            				$dob='0000-00-00';
            			}
                      


            		}

            		$anniversary='';
					if(!empty($doctor_data['anniversary']))
            		{
            			if(!empty($doctor_data['anniversary']))
            			{
            				$anniversary=date('Y-m-d', strtotime($doctor_data['anniversary']));
            			}
            			else
            			{
            				$anniversary='0000-00-00';
            			}
                      


            		}

					//insurance company
$doctor_code = generate_unique_id(3);
//print_r($doctor_code);
				$doctors_data_array = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'doctor_code'=>$doctor_code,
	                    'doctor_name'=>$doctor_data['doctor_name'],
	                    'specilization_id'=>$specialization,
	                    'mobile_no'=>$doctor_data['mobile_no'],
	                    "doctor_type"=>$doctor_type,
						"marketing_person_id"=>$marketing_person_id,
						"consultant_charge"=>$doctor_data['consultant_charge'],
						"emergency_charge"=>$doctor_data['emergency_charge'],
						'dob'=>$dob,
						'anniversary'=>$anniversary,
						'doctor_panel_type'=>$doctor_panel_type,
						'address'=>$doctor_data['address'],
						'country_id'=>$country,
						'state_id'=>$state,
						'city_id'=>$city,
						'status'=>1,
						'pincode'=>$doctor_data['pincode'],	
						'email'=>$doctor_data['email'],	
						'alt_mobile_no'=>$doctor_data['alt_mobile_no'],	
						'landline_no'=>$doctor_data['landline_no'],	
						'pan_no'=>$doctor_data['pan_no'],	
						'doc_reg_no'=>$doctor_data['doc_reg_no'],				
	                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_date'=>date('Y-m-d H:i:s'),
				        'modified_date'=>date('Y-m-d H:i:s'),
				        'created_by'=>$users_data['parent_id']
				        
				    );

               // print_r($doctors_data_array);
				    $this->db->insert('hms_doctors',$doctors_data_array);
	                $data_id = $this->db->insert_id();
			        $doctor_id = $data_id;
			        //echo $this->db->last_query(); exit;
            if(!empty($doctor_id))
            {
                
            
			/*$data = array(     
					"users_role"=>3,
					"parent_id"=>$data_id,
					"username"=>'DOC000'.$data_id,
					"password"=>md5('PASS'.$data_id),
					"email"=>$doctor_data['email'], 
					"status"=>'1',
					"ip_address"=>$_SERVER['REMOTE_ADDR'],
					"created_by"=>$users_data['id'],
					"created_date" =>date('Y-m-d H:i:s')
				         ); 
			$this->db->insert('hms_users',$data);	
            $users_id = $this->db->insert_id();    
			
			$this->db->select('*');
			$this->db->where('users_role','3');
			$query = $this->db->get('hms_permission_to_role');		 
			$permission_list = $query->result();
    			if(!empty($permission_list))
    			{
    				foreach($permission_list as $permission)
    				{
    					$data = array(
    					        'users_role' =>3,
    					        'users_id' => $users_id,
    					        'master_id' => $doctor_id,
    					        'section_id' => $permission->section_id,
    					        'action_id' => $permission->action_id, 
    					        'permission_status' => '1',
    					        'ip_address' => $_SERVER['REMOTE_ADDR'],
    					        'created_by' =>$users_data['id'],
    					        'created_date' =>date('Y-m-d H:i:s'),
    					     );
    					$this->db->insert('hms_permission_to_users',$data);
    				}
    			}*/
			
            }

	            }
            }   	
        }
	}
	
	//Histroy Modals
	
	public function opd_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id AND hms_opd_booking.is_deleted=0');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		      
		    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
		    
		    if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
		    
		    //$this->db->where('hms_opd_booking.type',3);
		    $this->db->where('hms_payment.section_id IN (2)');
		    
		    $this->db->order_by('hms_payment.created_date','DESC');
		    $this->db->from('hms_payment');
		    $query = $this->db->get(); 
		    //echo $this->db->last_query(); exit;
		    return $query->result();
		} 
	}
	
	public function billing_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		     $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
		    //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
		    
		    if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
		    $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
		    $this->db->where('hms_opd_booking.is_deleted',0);
		    $this->db->where('hms_payment.section_id=4');  //billing section id 4
		    $this->db->order_by('hms_payment.created_date','DESC');
		    $this->db->from('hms_payment');
		    $query = $this->db->get(); 
		    //echo $this->db->last_query(); exit;
		    return $query->result();
		} 
	}
	
	public function medicine_history_list($get="")
	    {
	        $users_data = $this->session->userdata('auth_users'); 
	        if(!empty($get))
	        {  
	             $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
	            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
	            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id');
	            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');   
	            //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
	            if(!empty($get['branch_id']))
			    {
			    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
			    }
			    else
			    {
			    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
			    }
	            $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
	            if(!empty($get['start_date']))
                {
                   $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";
    
                   $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
                }
    
                if(!empty($get['end_date']))
                {
                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
                }
	            $this->db->where('hms_payment.section_id IN (3)'); 
	            $this->db->order_by('hms_payment.created_date','DESC');
	            $this->db->from('hms_payment');
	            $query = $this->db->get();  
	             //echo $this->db->last_query();die;
	            return $query->result();
	        } 
	    }
	    
	    public function ipd_history_list($get="")
		{
			$users_data = $this->session->userdata('auth_users'); 
			if(!empty($get))
			{  
			     $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
			    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
			    $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id');
			    $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id','left');   
			    //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
			    if(!empty($get['branch_id']))
			    {
			    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
			    }
			    else
			    {
			    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
			    }
			    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
			    if(!empty($get['start_date']))
                {
                   $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";
    
                   $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
                }
    
                if(!empty($get['end_date']))
                {
                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
                }
			    $this->db->where('hms_payment.section_id IN (5)');  //billing section id 4
			    $this->db->order_by('hms_payment.created_date','DESC');
			    $this->db->from('hms_payment');
			    $query = $this->db->get();  
			    return $query->result();
			} 
		}
		
		public function pathology_history_list($get="")
		{
			$users_data = $this->session->userdata('auth_users'); 
			if(!empty($get))
			{  
			     $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
			    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
			    $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id');
			    $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.attended_doctor','left');   
			    //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
			    if(!empty($get['branch_id']))
			    {
			    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
			    }
			    else
			    {
			    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
			    }
			    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
			    if(!empty($get['start_date']))
                {
                   $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";
    
                   $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
                }
    
                if(!empty($get['end_date']))
                {
                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
                }
			    $this->db->where('hms_payment.section_id IN (1)');  //billing section id 4
			    $this->db->order_by('hms_payment.created_date','DESC');
			    $this->db->from('hms_payment');
			    $query = $this->db->get();  
			    return $query->result();
			} 
		}
		
		
    	public function ot_history_list($get="")
    	{
    		$users_data = $this->session->userdata('auth_users'); 
    		$this->db->select('hms_patient.patient_name,hms_patient.patient_code,hms_operation_booking.created_date,hms_operation_booking.paid_amount');  
    	        $this->db->join('hms_patient','hms_patient.id=hms_operation_booking.patient_id','left');
    	        $this->db->from('hms_operation_booking');
    	        if(!empty($get['branch_id']))
    		    {
    		    	$this->db->where('hms_operation_booking.branch_id',$get['branch_id']); 
    		    }
    		    else
    		    {
    		    	$this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']); 
    		    }	        
    	        $this->db->where('hms_operation_booking.doctor_id',$get['doctor_id']);
    	       
    	        $this->db->order_by('hms_operation_booking.id','desc');
    	        
    	        $this->db->where('hms_operation_booking.is_deleted',0);
    			$query = $this->db->get(); 
    		   //echo $this->db->last_query();die(); 
    		    return $query->result();
    	}
    	
    	public function dialysis_history_list($get="")
    	{
    		$users_data = $this->session->userdata('auth_users'); 
    		$this->db->select('hms_patient.patient_name,hms_patient.patient_code,hms_dialysis_booking.id,hms_dialysis_booking.branch_id,hms_dialysis_booking.booking_code as number, hms_dialysis_booking.created_date');  
    	        $this->db->join('hms_patient','hms_patient.id=hms_dialysis_booking.patient_id','left');
    	        $this->db->from('hms_dialysis_booking');  
    	        if(!empty($get['branch_id']))
    		    {
    		    	$this->db->where('hms_dialysis_booking.branch_id',$get['branch_id']); 
    		    }
    		    else
    		    {
    		    	$this->db->where('hms_dialysis_booking.branch_id',$users_data['parent_id']); 
    		    }
    	        $this->db->where('hms_dialysis_booking.doctor_id',$get['doctor_id']);
    	        
    	        $this->db->order_by('hms_dialysis_booking.id','desc');
    	        
    	        $this->db->where('hms_dialysis_booking.is_deleted',0);
    			$query = $this->db->get(); 
    		   
    		    return $query->result();
    
    	}
    	
    	public function inventory_history_list($get="")
    	{
    		$users_data = $this->session->userdata('auth_users'); 
    			$this->db->select('hms_patient.patient_name,hms_patient.patient_code, hms_stock_issue_allotment.created_date,hms_stock_issue_allotment.paid_amount');  
    			$this->db->join('hms_patient','hms_patient.id=hms_stock_issue_allotment.user_type_id','left');
    	        $this->db->from('hms_stock_issue_allotment');  
    
    	        if(!empty($get['branch_id']))
    		    {
    		    	$this->db->where('hms_stock_issue_allotment.branch_id',$get['branch_id']); 
    		    }
    		    else
    		    {
    		    	$this->db->where('hms_stock_issue_allotment.branch_id',$users_data['parent_id']); 
    		    }
    	        $this->db->where('hms_stock_issue_allotment.user_type_id',$get['doctor_id']);
    	       
    	        $this->db->order_by('hms_stock_issue_allotment.id','desc');
    	        
    	        $this->db->where('hms_stock_issue_allotment.is_deleted',0);
    			$query = $this->db->get(); 
    		  // echo $this->db->last_query();die(); 
    		    return $query->result();
    
    	}
    	
    
    	
    	public function eye_history_list($get="")
    	{
    		$users_data = $this->session->userdata('auth_users'); 
    		if(!empty($get))
    		{  
    		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
    		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
    		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
    		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
    		    if(!empty($get['branch_id']))
    		    {
    		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
    		    }
    		    else
    		    {
    		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
    		    }
    		      
    		    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
    		    $this->db->where('hms_opd_booking.booking_type',1);
    		    $this->db->where('hms_opd_booking.type',2);
    		    $this->db->where('hms_payment.section_id IN (2)'); 
    		    $this->db->from('hms_payment');
    		    $query = $this->db->get(); 
    		   
    		    return $query->result();
    		} 
    	}
    	
    	public function pedit_history_list($get="")
    	{
    		$users_data = $this->session->userdata('auth_users'); 
    		if(!empty($get))
    		{  
    		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
    		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
    		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
    		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
    		    if(!empty($get['branch_id']))
    		    {
    		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
    		    }
    		    else
    		    {
    		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
    		    }
    		      
    		    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
    		    $this->db->where('hms_opd_booking.booking_type',2);
    		    $this->db->where('hms_opd_booking.type',2);
    		    $this->db->where('hms_payment.section_id IN (2)'); 
    		    $this->db->from('hms_payment');
    		    $query = $this->db->get(); 
    		   
    		    return $query->result();
    		} 
    	}
    	
    	public function dental_history_list($get="")
    	{
    		$users_data = $this->session->userdata('auth_users'); 
    		if(!empty($get))
    		{  
    		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
    		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
    		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
    		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
    		    if(!empty($get['branch_id']))
    		    {
    		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
    		    }
    		    else
    		    {
    		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
    		    }
    		      
    		    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
    		    $this->db->where('hms_opd_booking.booking_type',3);
    		    $this->db->where('hms_opd_booking.type',2);
    		    $this->db->where('hms_payment.section_id IN (2)'); 
    		    $this->db->from('hms_payment');
    		    $query = $this->db->get(); 
    		   
    		    return $query->result();
    		} 
    	}
    	
    	public function gyni_history_list($get="")
    	{
    		$users_data = $this->session->userdata('auth_users'); 
    		if(!empty($get))
    		{  
    		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
    		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
    		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
    		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
    		    if(!empty($get['branch_id']))
    		    {
    		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
    		    }
    		    else
    		    {
    		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
    		    }
    		      
    		    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
    		    $this->db->where('hms_opd_booking.booking_type',4);
    		    $this->db->where('hms_opd_booking.type',2);
    		    $this->db->where('hms_payment.section_id IN (2)'); 
    		    $this->db->from('hms_payment');
    		    $query = $this->db->get(); 
    		   
    		    return $query->result();
    		} 
    	}
    	
    	public function day_care_history_list($get="")
    	{
    		$users_data = $this->session->userdata('auth_users'); 
    		if(!empty($get))
    		{  
    		     $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
    		    $this->db->join('hms_patient','hms_patient.id=hms_payment.doctor_id','left'); 
    		    $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
    		    $this->db->join('hms_doctors','hms_doctors.id=hms_day_care_booking.consultant','left');   
    		       
    		    if(!empty($get['branch_id']))
    		    {
    		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
    		    }
    		    else
    		    {
    		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
    		    }
    		    $this->db->where('hms_payment.doctor_id',$get['doctor_id']);
    		    if(!empty($get['start_date']))
                {
                   $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";
    
                   $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
                }
    
                if(!empty($get['end_date']))
                {
                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
                }
    		    $this->db->where('hms_payment.section_id IN (14)');  
    		    $this->db->from('hms_payment');
    		    $query = $this->db->get();  
    		    //echo $this->db->last_query(); exit;
    		    return $query->result();
    		} 
    	}
    	
    function get_doctors($branch_id)
	{
    	if(!empty($branch_id) && $branch_id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->where('branch_id',$branch_id);
			$this->db->where('status',1);
			$this->db->where('is_deleted',0); 
			$this->db->where('(doctor_type=0 OR doctor_type=2)'); 
			
			$result= $this->db->get('hms_doctors')->result();
			return $result;
    	} 
    }
	
	
} 
?>