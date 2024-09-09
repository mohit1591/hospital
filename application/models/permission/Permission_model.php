<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_model extends CI_Model {

	var $table = 'hms_permission'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    
    public function section_list()
    {
    	$this->db->select('*');
    	$this->db->where('status','1');
    	$this->db->from('hms_permission_section');
        $this->db->order_by('hms_permission_section.sort_order','asc');
    	$query = $this->db->get();
    	$result = $query->result();
    	return $result; 
    }

    public function action_list($section_id="",$role_id="")
    { 
    	$this->db->select('hms_permission_action.*,  hms_permission_to_role.permission_status,  hms_permission_to_role.attribute_val');
    	$this->db->where('hms_permission_action.status','1');
    	if(!empty($section_id))
    	{
    	   $this->db->where('hms_permission_action.section_id',$section_id); 
    	}

    	if(!empty($role_id) && $role_id > 0)
    	{
    		$this->db->join('hms_permission_to_role','hms_permission_to_role.action_id = hms_permission_action.id AND hms_permission_to_role.users_role = "'.$role_id.'"','left'); 
    	}
    	$this->db->group_by('hms_permission_action.id');
    	$this->db->from('hms_permission_action');
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
    	$result = $query->result();
    	return $result; 
    }

    public function save()
    { 
    	$user_data = $this->session->userdata('auth_users');
    	$post = $this->input->post();
    	if(!empty($post))
    	{
    		$this->db->where('users_role',$post['user_role']);
    		$this->db->delete('hms_permission_to_role');

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
					   'users_role'=>$post['user_role'],
					   'section_id'=>$explode[0],
					   'action_id'=>$explode[1],
					   'attribute_val'=>$attr_val,
					   'permission_status'=>$explode[2], 
					   'created_by'=>$user_data['id'],
					   'created_date'=>date('Y-m-d H:i:s')
					 );
					$this->db->insert('hms_permission_to_role',$data); 
    			} 
    		}
    	}
    }


    

}
?>