<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leads_model extends CI_Model {

	var $table = 'crm_leads';
	var $column = array(
		                 'crm_leads.crm_code',
		                 'hms_department.department',
		                 'crm_lead_type.lead_type',
		                 'crm_source.source', 
		                 'crm_leads.name',
		                 'crm_leads.phone',
		                 'crm_leads.followup_date',
		                 'crm_leads.appointment_date',
                     'crm_lead_to_followup.call_remark',
		                 'hms_users.username',
		                 'crm_call_status.call_status',
		                 'crm_leads.modified_date'
		                );  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		//parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users'); 
        $advance_search = $this->session->userdata('crm_advance_search');  
        //print_r($advance_search);die;
		$this->db->select("crm_leads.*, hms_department.department, crm_lead_type.lead_type, crm_source.source, hms_users.username as uname, crm_call_status.call_status as current_status, crm_lead_to_followup.call_remark as last_remark");
		$this->db->join("hms_department","hms_department.id=crm_leads.department_id","left");

		$this->db->join("crm_lead_to_followup","crm_lead_to_followup.lead_id=crm_leads.id","left");
    $this->db->join("crm_call_status","crm_call_status.id=crm_lead_to_followup.call_status","left");
    $this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
		$this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
		$this->db->join("hms_users","hms_users.id=crm_leads.created_by","left");  
    $this->db->where('crm_lead_to_followup.id IN (SELECT MAX(id) AS id
             FROM crm_lead_to_followup follow 
             WHERE follow.lead_id=crm_lead_to_followup.lead_id) ');
        if($users_data['emp_id']>1)
        {
        	$this->db->where("crm_leads.created_by",$users_data['id']);   
        }
        if(isset($advance_search) && !empty($advance_search))
        { 
          if(!empty($advance_search['start_date']))
          {
          	$this->db->where("crm_leads.created_date >= '".date('Y-m-d',strtotime($advance_search['start_date'])).' 00:00:00'."'");   
          }

          if(!empty($advance_search['end_date']))
          {
          	$this->db->where("crm_leads.created_date <= '".date('Y-m-d',strtotime($advance_search['end_date'])).' 23:59:59'."'");   
          }

          if(!empty($advance_search['from_followup']))
          {
            $this->db->where("crm_lead_to_followup.followup_date >= '".date('Y-m-d',strtotime($advance_search['from_followup']))."'");   
          }

          if(!empty($advance_search['call_status']))
          {
            $this->db->where("crm_lead_to_followup.call_status", $advance_search['call_status']);   
          }

          if(!empty($advance_search['to_followup']))
          {
            $this->db->where("crm_lead_to_followup.followup_date <= '".date('Y-m-d',strtotime($advance_search['to_followup']))."'");   
          }

          if(!empty($advance_search['lead_maturity']))
          {
            if($advance_search['lead_maturity']==1)
            {
              $this->db->where("crm_leads.booking_id!=''");   
            }
            else if($advance_search['lead_maturity']==2)
            {
              $this->db->join("crm_lead_to_followup","crm_lead_to_followup.lead_id=crm_leads.id","left");
              $this->db->where("crm_lead_to_followup.id", "4");   
            }
            else if($advance_search['lead_maturity']==3)
            {
              $this->db->join("crm_lead_to_followup","crm_lead_to_followup.lead_id=crm_leads.id","left");
              $this->db->where("(crm_lead_to_followup.id=4 OR crm_leads.booking_id!='')");   
            }
          }

          if(!empty($advance_search['department_id']))
          {
          	$this->db->where("crm_leads.department_id", $advance_search['department_id']);   
          }

          if(!empty($advance_search['lead_id']))
          {
          	$this->db->where("crm_leads.crm_code", $advance_search['lead_id']);   
          }

          if(!empty($advance_search['lead_source_id']))
          {
          	$this->db->where("crm_leads.lead_source_id", $advance_search['lead_source_id']);   
          }

          if(!empty($advance_search['lead_type_id']))
          {
          	$this->db->where("crm_leads.lead_type_id", $advance_search['lead_type_id']);   
          }

          if(!empty($advance_search['name']))
          {
          	$this->db->where("crm_leads.name '%".$advance_search['name']."%'");  
          }

          if(!empty($advance_search['email']))
          {
          	$this->db->where("crm_leads.email '%".$advance_search['email']."%'");   
          }

          if(!empty($advance_search['phone']))
          {
          	$this->db->where("crm_leads.phone '%".$advance_search['phone']."%'");   
          }
 
        }
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
		return $query->result_array();
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

	public function save_followup()
	{
		$user_data = $this->session->userdata('auth_users');   
    	$post = $this->input->post();

    	$data = array( 
                           'lead_id'=>$post['lead_id'],
                           'call_date'=>date('Y-m-d', strtotime($post['call_date'])),
                           'call_time'=>date('H:i:s', strtotime($post['call_time'])),
                           'followup_date'=>date('Y-m-d', strtotime($post['followup_date'])),
                           'followup_time'=>date('H:i:s', strtotime($post['followup_time'])),
                           'call_status'=>$post['call_status'],
                           'call_remark'=>$post['remark'], 
                           'parent_status'=>0 
                       ); 

		$this->db->set('created_by',$user_data['id']);
		$this->db->set('created_date',date('Y-m-d H:i:s'));
		$this->db->insert('crm_lead_to_followup',$data);
	}
    
    	
	public function save()
    { 
    	 $user_data = $this->session->userdata('auth_users');   
    	 $post = $this->input->post();
    	 $reg_no = generate_unique_id(72);  
         $data = array( 
                                   'branch_id'=>$user_data['parent_id'],
                                   'lead_type_id'=>$post['lead_type_id'],
                                   'lead_source_id'=>$post['lead_source_id'],
                                   'name'=>$post['name'],
                                   'email'=>$post['email'],
                                   'phone'=>$post['phone'],
                                   'age_y'=>$post['age_y'],
                                   'age_m'=>$post['age_m'],
                                   'age_d'=>$post['age_d'],
                                   'gender'=>$post['gender'],
                                   'address'=>$post['address'],
                                   'address2'=>$post['address2'],
                                   'address3'=>$post['address3'],
                                   'city_id'=>$post['city_id'],
                                   'state_id'=>$post['state_id'],
                                   'country_id'=>$post['country_id'],
                                   'total_amount'=>$post['total_amount'],
                                   'home_collection'=>$post['home_collection'],
                                   'call_date'=>date('Y-m-d', strtotime($post['call_date'])),
                                   'call_time'=>date('H:i:s', strtotime($post['call_time'])),
                                   'call_remark'=>$post['call_remark'], 
                                   'call_status'=>$post['call_status'], 
                                   'specialization_id'=>$post['specialization_id'],
                                   'attended_doctor'=>$post['attended_doctor'],
                                   'appointment_date'=>date('Y-m-d', strtotime($post['appointment_date'])),
                                   'appointment_time'=>date('H:i:s', strtotime($post['appointment_time'])),
                                   'followup_date'=>date('Y-m-d', strtotime($post['followup_date'])),
                                   'followup_time'=>date('H:i:s', strtotime($post['followup_time'])),
                                   'opd_service'=>$post['opd_service'],
                                   'billing_service'=>$post['billing_service'],
                                   'ipd_service'=>$post['ipd_service'], 
                                   'department_id'=>$post['department_id'], 
                                   'ot_id'=>$post['ot_id'],
                                   'ot_service'=>$post['ot_service'],
                               ); 

    	 if(!empty($post['data_id']) && $post['data_id']>0)
			{    
	      $this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
	      $this->db->where('id',$post['data_id']);
				$this->db->update('crm_leads',$data);  
        //echo $this->db->last_query();die;
				$lead_id = $post['data_id'];

			}
			else
			{    
				$this->db->set('crm_code',$reg_no);
                $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('crm_leads',$data);
				//echo $this->db->last_query();die;
				$lead_id = $this->db->insert_id();               
			} 

    $this->db->where('lead_id',$lead_id);
    $this->db->where('parent_status','1');
    $this->db->delete('crm_lead_to_followup'); 
		$followup_arr = array(
                                'lead_id'=>$lead_id,
                                'call_date'=>date('Y-m-d', strtotime($post['call_date'])),
                                'call_time'=>date('H:i:s', strtotime($post['call_time'])),
                                'followup_date'=>date('Y-m-d', strtotime($post['followup_date'])),
                                'followup_time'=>date('H:i:s', strtotime($post['followup_time'])), 
                                'call_status'=>$post['call_status'],
                                'call_remark'=>$post['call_remark'],
                                'parent_status'=>'1',
                                'created_by'=>$user_data['id'],
                                'created_date'=>date('Y-m-d H:i:s')
		                     );	
		$this->db->insert('crm_lead_to_followup',$followup_arr);
    //echo $this->db->last_query(); die;
	    if($post['department_id']==8)
	    {
	    	$booked_test = $this->session->userdata('book_test');
            $profile_data = $this->session->userdata('set_profile'); 
            // print_r($profile_data);die;
			$this->db->where('lead_id',$lead_id);
			$this->db->delete('crm_lead_booking_to_test');

			$this->db->where('lead_id',$lead_id);
			$this->db->delete('crm_lead_booking_to_profile');
            if(!empty($booked_test))
            { 
            	$test_ids_arr = array_keys($booked_test);
	            $test_ids = implode(',',$test_ids_arr);
	            $test_list = $this->leads->test_list($test_ids);
	            foreach($test_list as $test)
                {
                	$test_data = array(
                                 'lead_id'=>$lead_id,
                                 'test_id'=>$test->id,
                                 'price'=>$test->rate
			                  );
			        $this->db->insert('crm_lead_booking_to_test',$test_data);
                }
            }

            if(!empty($profile_data))
            {  
	            foreach($profile_data as $profile)
                { 
					$p_data = array(
		                                 'lead_id'=>$lead_id,
		                                 'profile_id'=>$profile['id'],
                                     'price'=>$profile['price']
					                  );
					$this->db->insert('crm_lead_booking_to_profile',$p_data);

                }
            }
	    }		
    } 

    
    public function get_by_id($id='')
	{
		$this->db->select("crm_leads.*, hms_department.department, crm_lead_type.lead_type, crm_source.source,hms_users.username as uname");
		$this->db->join("hms_department","hms_department.id=crm_leads.department_id","left");
		$this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
		$this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
		$this->db->join("hms_users","hms_users.id=crm_leads.created_by","left"); 
		$this->db->where('crm_leads.id', $id);
		$query = $this->db->get('crm_leads');
		//echo $this->db->last_query(); 
		return $query->row_array();
	}

	public function call_status()
	{
	    $user_data = $this->session->userdata('auth_users'); 
		$this->db->select('*'); 
		$this->db->where('crm_call_status.branch_id', $user_data['parent_id']);
		$query = $this->db->get('crm_call_status');
		return $query->result_array();
	}

	public function users_list()
	{
	    $user_data = $this->session->userdata('auth_users'); 
		$this->db->select('*'); 
		$this->db->where('hms_users.parent_id', $user_data['parent_id']);
		$query = $this->db->get('hms_users');
		return $query->result_array();
	}

    public function lead_type_list()
	{
	    $user_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');
		$this->db->where('crm_lead_type.branch_id', $user_data['parent_id']);
		$this->db->where('crm_lead_type.is_deleted', 0);
		
		$query = $this->db->get('crm_lead_type');
		return $query->result();
	}

	public function lead_source_list()
	{
	    $user_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');
		$this->db->where('crm_source.branch_id', $user_data['parent_id']);
		//$this->db->where('crm_source.is_deleted', 0);
		$query = $this->db->get('crm_source');
		return $query->result();
	} 

	public function department_list()
	{
		$this->db->select('*');
		$query = $this->db->get('crm_department');
		return $query->result();
	} 

	public function particulars_list($particular_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        if(!empty($particular_id))
        {
            $this->db->where('id',$particular_id); 
        } 
        $this->db->order_by('particular','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_opd_particular');
        $result = $query->result();
        return $result; 
    }

    public function doctor_specilization_list($specilization_id="")
    {
        $user_data = $this->session->userdata('auth_users'); 
        $this->db->select('*'); 
        if(!empty($specilization_id))
        {
            $this->db->where('specilization_id',$specilization_id); 
        }         
        $this->db->where('status','1'); 
        $this->db->where('branch_id',$user_data['parent_id']); 
        $this->db->where('(doctor_type=1 OR doctor_type=2)');
        $this->db->order_by('doctor_name','ASC'); 
        $this->db->where('is_deleted',0);
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result;
    }

    public function specialization_list($branch_id='')
    {
        $user_data = $this->session->userdata('auth_users'); 
        //$users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->where('branch_id',$user_data['parent_id']); 
        $this->db->order_by('specialization','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$branch_id);
        $this->db->group_by('id'); 
        $query = $this->db->get('hms_specialization');
        $result = $query->result(); 
        return $result; 
    }


    public function lab_department_list($module_id="5")
    { 
        $user_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');  
        if(!empty($module_id))
        {
            $this->db->where('module',$module_id); 
        }
        $this->db->where('(hms_department.branch_id='.$user_data['parent_id'].' OR hms_department.branch_id=0)');   
        $query = $this->db->get('hms_department');
        $result = $query->result(); 
        return $result; 
    }

    public function profile_list($branch_id="")
    {  
        $user_data = $this->session->userdata('auth_users'); 
	    $this->db->select('*');   
	    $this->db->order_by('path_profile.profile_name','ASC');
	    $this->db->where('is_deleted',0);  
	    $this->db->where('branch_id',$user_data['parent_id']); 
	    $this->db->where('status',1); 
	    //$this->db->where('is_deleted',0); 
	    $query = $this->db->get('path_profile');
	    $result = $query->result(); 
	    //echo $this->db->last_query(); 
	    return $result; 
    }

    public function test_head_list($dept_id="", $branch_id="")
    { 
         $user_data = $this->session->userdata('auth_users'); 
    $this->db->select('path_test_heads.*');    
    if(!empty($dept_id))
    {
           $this->db->where('path_test_heads.dept_id',$dept_id);  
    } 
    $this->db->where('path_test_heads.is_deleted',0);  
    $this->db->where('path_test_heads.branch_id',$user_data['parent_id']);  
    $this->db->order_by('path_test_heads.sort_order','ASC');
    $this->db->group_by('path_test_heads.id');  
    $query = $this->db->get('path_test_heads');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function test_list($ids="",$branch_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $booking_set_branch = $this->session->userdata('booking_set_branch');
    $this->db->select('path_test.*, path_test.rate as price'); 
    if(!empty($ids))
    {
      $this->db->where('path_test.id  IN ('.$ids.')'); 
    }
    $this->db->where('path_test.is_deleted',0);  
     if(!empty($branch_id))
        {
          $this->db->where('path_test.branch_id',$branch_id); 
        }
        else
        {
          $this->db->where('path_test.branch_id',$users_data['parent_id']); 
        } 
        $this->db->order_by('path_test.sort_order','ASC');
    $this->db->group_by('path_test.id');  
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function head_test_list($head_id="",$profile_id="",$test_name="",$dept_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $booking_set_branch = $this->session->userdata('booking_set_branch');
    $booking_list = $this->session->userdata('book_test');
    $this->db->select('path_test.*');  
    if(!empty($head_id))
    {
           $this->db->where('path_test.test_head_id',$head_id);  
    } 


    if(!empty($dept_id))
    {
           $this->db->where('path_test.dept_id',$dept_id);  
    } 

    if(!empty($profile_id))
    {
       $this->db->join('path_profile_to_test','path_profile_to_test.test_id = path_test.id');   
           $this->db->where('path_profile_to_test.profile_id',$profile_id);  
    }   

    if(!empty($test_name))
    {
           /*$this->db->where('(path_test.test_name LIKE  "'.$test_name.'%" OR path_test.test_code LIKE  "'.$test_name.'%" OR path_test.rate LIKE  "'.$test_name.'%")'); */ 
           $this->db->where('(path_test.test_name LIKE "%'.trim($test_name).'%" OR path_test.test_code LIKE "%'.trim($test_name).'%")');  
    }  

    if(isset($booking_list) && !empty($booking_list))
    {
      $test_ids_arr = array_keys($booking_list);
      $test_ids = implode(',', $test_ids_arr);
      $this->db->where('path_test.id NOT IN ('.$test_ids.')');
    }
    
    $this->db->where('path_test.is_deleted',0);   
    $this->db->where('path_test.branch_id',$users_data['parent_id']);
    $this->db->order_by('path_test.sort_order','ASC');
    $this->db->group_by('path_test.id'); 
    $this->db->limit(100, 0);
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }

    public function sample_type_list_new($test_id='',$sample_id='')
	  {
	      $users_data = $this->session->userdata('auth_users'); 
	       
	      $this->db->select('path_sample_type.*'); 
	      $this->db->where('status','1'); 
	      $this->db->order_by('sample_type','ASC');
	      $this->db->where('is_deleted',0);
	      $this->db->where('branch_id',$users_data['parent_id']);  
	      $query = $this->db->get('path_sample_type');
	      //echo $this->db->last_query();die();
	      $result = $query->result(); 
	      return $result;
	      
	  }

	public function profile_price($id="",$panel_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($id))
        {   
            $this->db->select("path_profile.*");  
            $this->db->where('path_profile.branch_id',$users_data['parent_id']); 
            $this->db->from('path_profile'); 
            $this->db->where('path_profile.id',$id);
            $this->db->where('path_profile.is_deleted','0');
            $query = $this->db->get(); 
            //echo $this->db->last_query();die;
            return $query->row_array();
        } 
    }


    public function operation_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_ot_management.*');
		$this->db->where('status',1);
		$this->db->where('is_deleted',0);
		$this->db->where('branch_id',$users_data['parent_id']); 
		$this->db->from('hms_ot_management');
		$query=$this->db->get()->result();
		//print_r($query);exit;
		return $query;	
    }  

    public function lead_test_list($lead_id="")
	{
		$this->db->select('crm_lead_booking_to_test.test_id');
		if(!empty($lead_id))
	    {
	           $this->db->where('crm_lead_booking_to_test.lead_id',$lead_id);  
	    } 
		$query = $this->db->get('crm_lead_booking_to_test');
		return $query->result_array();
	}

	public function lead_profile_list($lead_id="")
	{
		$this->db->select('crm_lead_booking_to_profile.profile_id, crm_lead_booking_to_profile.price');
		if(!empty($lead_id))
	    {
	           $this->db->where('crm_lead_booking_to_profile.lead_id',$lead_id);  
	    } 
		$query = $this->db->get('crm_lead_booking_to_profile');
		return $query->result_array();
	}  


	public function report_data()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$advance_search = $this->session->userdata('report_search'); 
		$this->db->select("crm_leads.*, hms_department.department, crm_lead_type.lead_type, crm_source.source,hms_users.username as uname, crm_call_status.call_status as callstatus, crm_lead_to_followup.followup_date followup_date2, crm_lead_to_followup.followup_time followup_time2, crm_lead_to_followup.id followup_id");
		$this->db->join("crm_leads","crm_leads.id=crm_lead_to_followup.lead_id");
		$this->db->join("hms_department","hms_department.id=crm_leads.department_id","left");
		$this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
		$this->db->join("crm_call_status","crm_call_status.id=crm_lead_to_followup.call_status","left");
		$this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
		$this->db->join("hms_users","hms_users.id=crm_leads.created_by","left");  
        if($users_data['id']>1)
        {
        	$this->db->where("crm_leads.created_by",$users_data['id']);   
        }
        if(isset($advance_search) && !empty($advance_search))
        { 
          if(!empty($advance_search['start_date']))
          {
          	$this->db->where("crm_leads.created_date >= '".date('Y-m-d',strtotime($advance_search['start_date'])).' 00:00:00'."'");   
          }

          if(!empty($advance_search['lead_maturity']))
          {
            if($advance_search['lead_maturity']==1)
            {
              $this->db->where("crm_leads.booking_id!=''");   
            }
            else if($advance_search['lead_maturity']==2)
            {
              //$this->db->join("crm_lead_to_followup","crm_lead_to_followup.lead_id=crm_leads.id","left");
              $this->db->where("crm_lead_to_followup.id", "4");   
            }
            else if($advance_search['lead_maturity']==3)
            {
              //$this->db->join("crm_lead_to_followup","crm_lead_to_followup.lead_id=crm_leads.id","left");
              $this->db->where("(crm_lead_to_followup.id=4 OR crm_leads.booking_id!='')");   
            }
          }

          if(!empty($advance_search['end_date']))
          {
          	$this->db->where("crm_leads.created_date <= '".date('Y-m-d',strtotime($advance_search['end_date'])).' 23:59:59'."'");   
          }


          if(!empty($advance_search['from_followup']))
          {
          	$this->db->where("crm_lead_to_followup.followup_date >= '".date('Y-m-d',strtotime($advance_search['from_followup']))."'");   
          }

          if(!empty($advance_search['to_followup']))
          {
          	$this->db->where("crm_lead_to_followup.followup_date <= '".date('Y-m-d',strtotime($advance_search['to_followup']))."'");   
          }

          if(!empty($advance_search['from_call']))
          {
          	$this->db->where("crm_lead_to_followup.call_date >= '".date('Y-m-d',strtotime($advance_search['from_call']))."'");   
          }

          if(!empty($advance_search['to_call']))
          {
          	$this->db->where("crm_lead_to_followup.call_date <= '".date('Y-m-d',strtotime($advance_search['to_call']))."'");   
          }

          if(!empty($advance_search['from_appointment']))
          {
          	$this->db->where("crm_leads.appointment_date >= '".date('Y-m-d',strtotime($advance_search['from_appointment']))."'");   
          }

          if(!empty($advance_search['to_appointment']))
          {
          	$this->db->where("crm_leads.appointment_date <= '".date('Y-m-d',strtotime($advance_search['to_appointment']))."'");   
          }

          if(!empty($advance_search['department_id']))
          {
          	$this->db->where("crm_leads.department_id", $advance_search['department_id']);   
          }


          if(!empty($advance_search['lead_source_id']))
          {
          	$this->db->where("crm_leads.lead_source_id", $advance_search['lead_source_id']);   
          }

          if(!empty($advance_search['lead_type_id']))
          {
          	$this->db->where("crm_leads.lead_type_id", $advance_search['lead_type_id']);   
          } 

          if(!empty($advance_search['call_status']))
          {
          	$this->db->where("crm_lead_to_followup.call_status", $advance_search['call_status']);   
          } 
 
        } 
		$this->db->where('crm_lead_to_followup.id IN (SELECT MAX(id) AS id
             FROM crm_lead_to_followup follow 
             WHERE follow.lead_id=crm_lead_to_followup.lead_id) ');
		$this->db->from('crm_lead_to_followup');
		$this->db->order_by('crm_lead_to_followup.id', 'DESC');
		$this->db->group_by('crm_lead_to_followup.lead_id');
		$query = $this->db->get();  
		//echo $this->db->last_query();die;
		return $query->result_array();
	}


  public function get_home_collection_data()
  {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');
        $this->db->from('hms_test_home_collection');
        $this->db->where('branch_id',$users_data['parent_id']);
        $res=$this->db->get();
        return $res->row_array();
  }


  public function get_sms()
  {
        $users_data=$this->session->userdata('auth_users'); 
        $this->db->select('*');
        $this->db->from('hms_sms_branch_template');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('form_name',35);
        $res=$this->db->get();
        return $res->row_array();
  }

  public function get_email()
  {
        $users_data=$this->session->userdata('auth_users'); 
        $this->db->select('*');
        $this->db->from('hms_email_branch_template');
        $this->db->where('form_name',35);
        $this->db->where('branch_id',$users_data['parent_id']);
        $res=$this->db->get();
        return $res->row_array();
  }
  
  
  function search_crm_data()
  {
    	$users_data = $this->session->userdata('auth_users'); 
        $advance_search = $this->session->userdata('crm_advance_search');  
        //print_r($advance_search);die;
		$this->db->select("crm_leads.*, hms_department.department, crm_lead_type.lead_type, crm_source.source, hms_users.username as uname, crm_call_status.call_status as current_status, crm_lead_to_followup.call_remark as last_remark");
		$this->db->join("hms_department","hms_department.id=crm_leads.department_id","left");

		$this->db->join("crm_lead_to_followup","crm_lead_to_followup.lead_id=crm_leads.id","left");
        $this->db->join("crm_call_status","crm_call_status.id=crm_lead_to_followup.call_status","left");
        $this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
		$this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
		$this->db->join("hms_users","hms_users.id=crm_leads.created_by","left");  
        $this->db->where('crm_lead_to_followup.id IN (SELECT MAX(id) AS id
             FROM crm_lead_to_followup follow 
             WHERE follow.lead_id=crm_lead_to_followup.lead_id) ');
        if($users_data['emp_id']>1)
        {
        	$this->db->where("crm_leads.created_by",$users_data['id']);   
        }
        if(isset($advance_search) && !empty($advance_search))
        { 
          if(!empty($advance_search['start_date']))
          {
          	$this->db->where("crm_leads.created_date >= '".date('Y-m-d',strtotime($advance_search['start_date'])).' 00:00:00'."'");   
          }

          if(!empty($advance_search['end_date']))
          {
          	$this->db->where("crm_leads.created_date <= '".date('Y-m-d',strtotime($advance_search['end_date'])).' 23:59:59'."'");   
          }

          if(!empty($advance_search['from_followup']))
          {
            $this->db->where("crm_lead_to_followup.followup_date >= '".date('Y-m-d',strtotime($advance_search['from_followup']))."'");   
          }

          if(!empty($advance_search['call_status']))
          {
            $this->db->where("crm_lead_to_followup.call_status", $advance_search['call_status']);   
          }

          if(!empty($advance_search['to_followup']))
          {
            $this->db->where("crm_lead_to_followup.followup_date <= '".date('Y-m-d',strtotime($advance_search['to_followup']))."'");   
          }

          if(!empty($advance_search['lead_maturity']))
          {
            if($advance_search['lead_maturity']==1)
            {
              $this->db->where("crm_leads.booking_id!=''");   
            }
            else if($advance_search['lead_maturity']==2)
            {
              $this->db->join("crm_lead_to_followup","crm_lead_to_followup.lead_id=crm_leads.id","left");
              $this->db->where("crm_lead_to_followup.id", "4");   
            }
            else if($advance_search['lead_maturity']==3)
            {
              $this->db->join("crm_lead_to_followup","crm_lead_to_followup.lead_id=crm_leads.id","left");
              $this->db->where("(crm_lead_to_followup.id=4 OR crm_leads.booking_id!='')");   
            }
          }

          if(!empty($advance_search['department_id']))
          {
          	$this->db->where("crm_leads.department_id", $advance_search['department_id']);   
          }

          if(!empty($advance_search['lead_id']))
          {
          	$this->db->where("crm_leads.crm_code", $advance_search['lead_id']);   
          }

          if(!empty($advance_search['lead_source_id']))
          {
          	$this->db->where("crm_leads.lead_source_id", $advance_search['lead_source_id']);   
          }

          if(!empty($advance_search['lead_type_id']))
          {
          	$this->db->where("crm_leads.lead_type_id", $advance_search['lead_type_id']);   
          }

          if(!empty($advance_search['name']))
          {
          	$this->db->where("crm_leads.name '%".$advance_search['name']."%'");  
          }

          if(!empty($advance_search['email']))
          {
          	$this->db->where("crm_leads.email '%".$advance_search['email']."%'");   
          }

          if(!empty($advance_search['phone']))
          {
          	$this->db->where("crm_leads.phone '%".$advance_search['phone']."%'");   
          }
 
        }
		$this->db->from($this->table); 
		$this->db->order_by('crm_leads.id','desc');
	    $query = $this->db->get(); 

		$data= $query->result_array();
		//echo $this->db->last_query();
		return $data;
	}

}
?>