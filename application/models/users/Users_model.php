<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

	var $table = 'hms_users';
	var $column = array('hms_users.id','hms_employees.reg_no', 'hms_emp_type.emp_type','hms_employees.name', 'hms_users.email','hms_users.status','hms_users.created_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select("hms_users.*, hms_employees.reg_no, hms_employees.name, hms_employees.email, hms_emp_type.emp_type");
		$this->db->where('hms_users.emp_id > 0');		
		$this->db->join('hms_employees','hms_employees.id = hms_users.emp_id');
		$this->db->join('hms_emp_type','hms_emp_type.id = hms_employees.emp_type_id','left');
	    $this->db->where('hms_employees.is_deleted',0);
		$this->db->where('hms_users.is_deleted',0);
		
            $this->db->where('hms_users.parent_id',$users_data['parent_id']);
        
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
		$this->db->select('hms_users.*, hms_employees.emp_type_id');
		$this->db->from('hms_users');   
		$this->db->join('hms_employees','hms_employees.id = hms_users.emp_id');   
		$this->db->where('hms_users.id',$id); 
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->row_array();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		//echo "<pre>"; print_r($post); exit;
		if(!empty($post['users_role']))
		{
		  $usersrole = $post['users_role'];  
		}
		else
		{
		   $usersrole =  $user_data['users_role'];
		}
		$data = array(
					'users_role'=>$usersrole,
					'users_type'=>'1',
					'parent_id'=>$user_data['parent_id'],
					'emp_id'=>$post['emp_id'], 
					'username'=>$post['username'],
					
					'email'=>$post['email'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					'record_access'=>$post['record_access'],
					'collection_type'=>$post['collection_type']
		         );
		         //'password'=>md5($post['password']),
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(!empty($post['password']) && !empty($post['cpassword']))
			{
               $this->db->set('password',md5($post['password']));
			} 
            $this->db->set('modified_by',$user_data['id']);
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_users',$data);  
			
			if($this->input->post('doctor_name')!="" && !empty($this->input->post('doctor_name')))
          {
            $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('users_id',$post['data_id']);
            $this->db->delete('hms_users_to_doctor');
            foreach($this->input->post('doctor_name') as $list)
            {
              $data_arr=array( 
                                'branch_id' => $user_data['parent_id'],
                                'users_id'=>$post['data_id'],
                                'doctor_id'=>$list,
                              );
              $this->db->insert('hms_users_to_doctor',$data_arr); 
            }
            
          }
		}
		else
		{   
		    $this->db->set('password',md5($post['password']));	
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_users',$data);  
			//echo $this->db->last_query();exit;
			$last_user_id= $this->db->insert_id();
      
          if($this->input->post('doctor_name')!="" && !empty($this->input->post('doctor_name')))
            {
              foreach($this->input->post('doctor_name') as $list)
              {
                $data_arr=array('branch_id' => $users_data['parent_id'],
                                  'doctor_id'=>$list,
                                  'users_id'=>$last_user_id,
                                );
                $this->db->insert('hms_users_to_doctor',$data_arr); 
                //echo $this->db->last_query();die;
              }
              
            }
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
			$this->db->update('hms_users');
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
    		$emp_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$emp_ids.')');
			$this->db->update('hms_users');
			//echo $this->db->last_query();die;
    	} 
    }

    public function employee_type_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('emp_type','ASC'); 
    	$query = $this->db->get('hms_emp_type');
		return $query->result();
    }

    public function type_to_employee($type_id="",$data_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_employees.*'); 
    	$this->db->where('hms_employees.branch_id',$user_data['parent_id']);
    	$this->db->where('hms_employees.emp_type_id',$type_id); 
    	$this->db->where('hms_employees.status',1); 
    	$this->db->where('hms_employees.is_deleted',0); 
    	if(!empty($data_id))
    	{
           $this->db->where('hms_employees.id NOT IN(select emp_id from hms_users where id != "'.$data_id.'" )'); 
    	}
    	else
    	{
    	//	$this->db->where('hms_employees.id NOT IN(select emp_id from hms_users)'); 
    	}
    	
    	$this->db->order_by('hms_employees.name','ASC'); 
    	$query = $this->db->get('hms_employees');
        //echo $this->db->last_query();die;
		return $query->result();
    }
   
    public function auth_branch_users($user_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('parent_id',$user_data['parent_id']);
    	$this->db->where('id',$user_id);
    	$this->db->from('hms_users');
    	$query = $this->db->get();
    	$result = $query->result();
    	return $result; 
    }

    public function permission_section_list()
    { 

    	$user_data = $this->session->userdata('auth_users'); 
    	//print_r($user_data);
    	$this->db->select('hms_permission_section.*');
    	
    	if($user_data['users_role']==1)
    	{
            $this->db->join('hms_permission_section','hms_permission_section.id = hms_permission_to_role.section_id');
			$this->db->where('hms_permission_section.status','1');
			$this->db->group_by('hms_permission_section.id');
			$this->db->from('hms_permission_to_role'); 
    	}
    	else if($user_data['users_role']==2)
    	{
    		$this->db->join('hms_permission_section','hms_permission_section.id = hms_permission_to_users.section_id');
    		if($user_data['parent_id']>0)
	    	{
	           $this->db->where('hms_permission_to_users.users_id',$user_data['id']);
	    	}
	    	else
	    	{ 
	           $this->db->where('hms_permission_to_users.master_id',$user_data['parent_id']);
	    	}
			$this->db->where('hms_permission_section.status','1');
			$this->db->group_by('hms_permission_section.id');
			$this->db->from('hms_permission_to_users'); 
    	}
    	  
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
    	$result = $query->result();
    	return $result; 
    }

      public function user_permission_action_list($section_id="",$users_id="")
    {
    	/*
        $this->db->select('hms_permission_action.*,user_self_permission.permission_status as user_permission_status, hms_permission_to_users.attribute_val');
    	$this->db->where('hms_permission_action.status','1');
    	if(!empty($section_id))
    	{
    	   $this->db->where('hms_permission_to_users.section_id',$section_id); 
    	} 

    	if($user_data['parent_id']>0)
    	{
           $this->db->where('hms_permission_to_users.users_id',$user_data['parent_id']);
    	}
    	else
    	{
           $this->db->where('hms_permission_to_users.users_id',$user_data['id']);
    	}
    	$this->db->join('hms_permission_action','hms_permission_action.id = hms_permission_to_users.action_id');
    	$this->db->join('hms_permission_to_users as user_self_permission','user_self_permission.action_id = hms_permission_action.id AND user_self_permission.users_id = "'.$users_id.'"','left');
    	$this->db->group_by('hms_permission_action.id');
    	$this->db->from('hms_permission_to_users'); 
    	*/
    	$user_data = $this->session->userdata('auth_users');
        if($user_data['users_role']==1)
    	{
           $this->db->select('hms_permission_action.*,(select permission_status from hms_permission_to_users as user_perm where user_perm.users_id = '.$users_id.' AND user_perm.section_id=hms_permission_to_users.section_id AND user_perm.action_id = hms_permission_to_users.action_id) as user_permission_status, (select attribute_val from hms_permission_to_users as user_perm where user_perm.users_id = '.$users_id.' AND user_perm.section_id=hms_permission_to_users.section_id AND user_perm.action_id = hms_permission_to_users.action_id) as attribute_val');

           $this->db->join('hms_permission_action','hms_permission_action.id = hms_permission_to_role.action_id');

           $this->db->join('hms_permission_to_users','hms_permission_to_users.action_id = hms_permission_to_role.action_id   AND hms_permission_to_users.users_id = "'.$users_id.'"','left');

           if(!empty($section_id))
	    	{
	    	   $this->db->where('hms_permission_action.section_id',$section_id); 
	    	} 
 
	    	$this->db->where('hms_permission_action.status','1'); 
	    	$this->db->group_by('hms_permission_action.id');
	    	$this->db->from('hms_permission_to_role'); 
    	}
    	else if($user_data['users_role']==2)
    	{
    		$this->db->select('hms_permission_action.*,(select permission_status from hms_permission_to_users as user_perm where user_perm.users_id = '.$users_id.' AND user_perm.section_id=hms_permission_to_users.section_id AND user_perm.action_id = hms_permission_to_users.action_id) as user_permission_status, (select attribute_val from hms_permission_to_users as user_perm where user_perm.users_id = '.$users_id.' AND user_perm.section_id=hms_permission_to_users.section_id AND user_perm.action_id = hms_permission_to_users.action_id) as attribute_val');

           $this->db->join('hms_permission_action','hms_permission_action.id = hms_permission_to_users.action_id'); 

           if($user_data['parent_id']>0)
	    	{
	           $this->db->where('hms_permission_to_users.users_id',$user_data['id']);
	    	}
	    	else
	    	{
	           $this->db->where('hms_permission_to_users.master_id',$user_data['parent_id']);
	    	}

           if(!empty($section_id))
	    	{
	    	   $this->db->where('hms_permission_action.section_id',$section_id); 
	    	} 
 
	    	$this->db->where('hms_permission_action.status','1'); 
	    	$this->db->group_by('hms_permission_action.id');
	    	$this->db->from('hms_permission_to_users');  
    	}
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
    	$result = $query->result();
    	return $result; 
 
    }

    public function save_users_permission($users_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	$post = $this->input->post();
    	if(!empty($post))
    	{
    		$this->db->where('users_id',$post['uid']);
    		$this->db->delete('hms_permission_to_users');

    		foreach($post['active'] as $active)
    		{
    			$explode = explode('-', $active);
    			$attr_val = "";
    			if(isset($post['attribute_val-'.$explode[1]]))
    			{
    				$attr_val = $post['attribute_val-'.$explode[1]];
    			}

    			if(isset($explode[2]) && !empty($explode[2]) && $explode[2]==1)
    			{
					$data = array(
					   'users_role'=>$user_data['users_role'],
					   'users_id'=>$post['uid'],
					   'master_id'=>$user_data['parent_id'],
					   'section_id'=>$explode[0],
					   'action_id'=>$explode[1],
					   'attribute_val'=>$attr_val,
					   'permission_status'=>1, 
					   'created_by'=>$user_data['id'],
					   'created_date'=>date('Y-m-d H:i:s')
					 );
					$this->db->insert('hms_permission_to_users',$data); 
    			} 
    		}
    	}
    }
    
    public function user_role_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('status',1);
    	$this->db->order_by('id','ASC'); 
    	$query = $this->db->get('hms_users_role');
    	//echo $this->db->last_query();die;
		return $query->result();
    }
    
    public function referal_doctor_list($branch_id="",$pay_type="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.status',1); 
        $this->db->where('hms_doctors.doctor_type IN (0,2)');
        $this->db->where('hms_doctors.branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result; 
    }
    
    public function users_to_doctors($users_id="")
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_users_to_doctor.*'); 
        $this->db->where('hms_users_to_doctor.branch_id',$user_data['parent_id']);
        $this->db->where('hms_users_to_doctor.users_id',$users_id); 
        $query = $this->db->get('hms_users_to_doctor');
        //echo $this->db->last_query();die;
        return $query->result();
    }


}
?>