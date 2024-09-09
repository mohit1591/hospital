<?php
class Login_model extends CI_Model 
{
    public function auth_users()
    {
    	$post = $this->input->post();
    	if(isset($post) && !empty($post))
    	{
    		$this->db->select('hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,branch_new.status as branch_current_status');
        $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
        $this->db->join('hms_branch as branch_new','branch_new.id = hms_users.parent_id','left');
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
    		$this->db->where('hms_users.username',$post['username']);
    		$this->db->where('hms_users.password',md5($post['password']));
    		$this->db->where('hms_users.status','1');
    		$this->db->where('hms_users.is_deleted','0');
    		$query = $this->db->get('hms_users');
    		$result = $query->row_array(); 
        //echo $this->db->last_query();die;

        // Permission gethering /
        $this->db->select('hms_permission_to_users.section_id, hms_permission_to_users.action_id');
        $this->db->where('users_id',$result['id']);   
        $query = $this->db->get('hms_permission_to_users');
        $permission_list = $query->result(); 

        $permission = [];
        if(!empty($permission_list))
        {
          $section = [];
          $action = [];
          foreach($permission_list as $permission)
          {
             if(!in_array($permission->section_id,$section))
             {
                $section[] = $permission->section_id;
             }

             $action[] = $permission->action_id;
          }
          $permission = array('section'=>$section, 'action'=>$action);

          if(!empty($result))
          {
            $result['permission'] = $permission;
          }
        }
        ////////////////////////

    		return $result;
    	}
    }
    
    
    public function bauth_users($parent_id="")
    {
    	 
    		$this->db->select('hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,branch_new.status as branch_current_status');
        $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
        $this->db->join('hms_branch as branch_new','branch_new.id = hms_users.parent_id','left');
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
    		$this->db->where('hms_users.parent_id',$parent_id); 
    		$this->db->where('hms_users.status','1');
    		$this->db->where('hms_users.is_deleted','0');
    		$query = $this->db->get('hms_users');
    		$result = $query->row_array(); 
        //echo $this->db->last_query();die;

        // Permission gethering /
        $this->db->select('hms_permission_to_users.section_id, hms_permission_to_users.action_id');
        $this->db->where('users_id',$result['id']);   
        $query = $this->db->get('hms_permission_to_users');
        $permission_list = $query->result(); 

        $permission = [];
        if(!empty($permission_list))
        {
          $section = [];
          $action = [];
          foreach($permission_list as $permission)
          {
             if(!in_array($permission->section_id,$section))
             {
                $section[] = $permission->section_id;
             }

             $action[] = $permission->action_id;
          }
          $permission = array('section'=>$section, 'action'=>$action);

          if(!empty($result))
          {
            $result['permission'] = $permission;
          }
        }
        ////////////////////////

    		return $result; 
    }

    public function get_users($email="",$token="")
    {
		$this->db->select('*');
		if(!empty($email))
		{
          $this->db->where('email',$email); 
		} 
		if(!empty($token))
		{
          $this->db->where('token',$token); 
		} 
		$this->db->where('status','1'); 
		$query = $this->db->get('hms_users');
		$result = $query->result(); 
		return $result;
    }
    
    public function get_branch_status($branch_id="")
    {
		$this->db->select('status');
		if(!empty($branch_id))
		{
          $this->db->where('id',$branch_id); 
		} 
	    $query = $this->db->get('hms_branch');
		//$result = $query->result();
		
		 $result = $query->row(); 
            //echo "<pre>"; print_r($result); exit;
            if(!empty($$result))
            {
                $status = $result->status;      
            }
            else
            {
                $status=0;
            }
             
        return $status;
    }

    public function set_password_token($id="",$token="",$token_time="")
    {
        if(!empty($id) && !empty($token) && !empty($token_time))
        {
        	$this->db->set('token',$token);
        	$this->db->set('token_expire',$token_time);
        	$this->db->where('id',$id);
            $this->db->update('hms_users');
        }
    }

    public function reset_password($token="")
    {
        $post = $this->input->post();
        if(!empty($post) && !empty($token))
        {
            $this->db->set('token','');
            $this->db->set('password',md5($post['password'])); 
            $this->db->where('token',$token);
            $this->db->update('hms_users');
        }
    }

    public function check_old_password($password="")
    {
       $post = $this->input->post();
       if(!empty($password) && !empty($password))
       {
            $user_data = $this->session->userdata('auth_users');

            $this->db->select('*'); 
            $this->db->where('password',md5($password));  
            $this->db->where('id',$user_data['id']);  
            $query = $this->db->get('hms_users');
            //echo $this->db->last_query();
            $result = $query->result(); 
            return $result;
       }
    }

    public function change_password()
    {
       $post = $this->input->post();
       if(!empty($post) && !empty($post))
       {
          $user_data = $this->session->userdata('auth_users');
          $data = array('password'=>md5($post['password']), 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'modified_date'=>date('Y-m-d H:i:s'));
          $this->db->where('id',$user_data['id']);
          $this->db->update('hms_users',$data);
       }
    }

     public function get_by_user_id($id="")
    {
       $post = $this->input->post();
       if(!empty($post) && !empty($post))
       {
          $user_data = $this->session->userdata('auth_users');
          $this->db->where('users_id',$id);
          return $this->db->get('hms_branch')->result();
       }
    }
    
    public function update_login_activityold($id)
      {
      $this->db->set('last_login_ip',$_SERVER['REMOTE_ADDR']);
      $this->db->set('last_login_time',date('Y-m-d H:i:s'));
      $this->db->where('id',$id);
      $this->db->update('hms_users');
      }
      
     public function update_login_activity($id='',$branch_id='')
     {
        $this->db->set('last_login_ip',$_SERVER['REMOTE_ADDR']);
        $this->db->set('last_login_time',date('Y-m-d H:i:s'));
        $this->db->where('id',$id);
        $this->db->update('hms_users');
        
        $this->db->set('login_time',date('Y-m-d H:i:s'));
        $this->db->set('last_login_by',$id);
        $this->db->where('id',$branch_id);
        $this->db->update('hms_branch');

      }

    

 
} 
?>