<?php
class Unique_ids_model extends CI_Model 
{
	var $table = 'hms_unique_ids'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_unique_ids.*,hms_branch_unique_ids.id as p_id,hms_branch_unique_ids.branch_id,hms_branch_unique_ids.prefix,hms_branch_unique_ids.start_num,hms_branch_unique_ids.unique_id');
		$this->db->join('hms_branch_unique_ids', 'hms_branch_unique_ids.unique_id=hms_unique_ids.id AND hms_branch_unique_ids.branch_id = "'.$user_data['parent_id'].'"','left');
		$this->db->from('hms_unique_ids');  
		$query = $this->db->get(); 
		return $query->result();
	}
	
	
	public function get_unique_key($unique_id="",$branch_id="", $prefix="", $start_num="")
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_branch_unique_ids.*');
		if(!empty($unique_id))
		{
		    $this->db->where('hms_branch_unique_ids.unique_id', $unique_id);
		}
		if(!empty($branch_id))
		{
		    $this->db->where('hms_branch_unique_ids.branch_id', $branch_id);
		}
		if(!empty($prefix))
		{
		    $this->db->where('hms_branch_unique_ids.prefix', $prefix);
		}
		if(!empty($start_num))
		{
		    $this->db->where('hms_branch_unique_ids.start_num', $start_num);
		}
		$this->db->from('hms_branch_unique_ids');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//echo "<pre>"; print_r($post); exit;
	        if(!empty($post['data']))
		{    
			$branch_id  = $post['branch_id'];
			$p_id  = $post['p_id'];
			$current_date = date('Y-m-d H:i:s');
                        $user_data = $this->session->userdata('auth_users');

			foreach($post['data'] as $key=>$val)
            {
                $check_unique_key = $this->get_unique_key($key,$branch_id,$val['prefix'],$val['start_num']);  
                //echo '<pre>'; print_r($check_unique_key);die;
                if(empty($check_unique_key))
                {
                    $data = array(
                               "branch_id"=>$branch_id,
                               "unique_id"=>$key,
                               "prefix"=>$val['prefix'],
                               "start_num"=>$val['start_num'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         ); 
                	$this->db->where('id',$p_id);
                    $this->db->where('branch_id',$user_data['parent_id']); 
                	$this->db->update('hms_branch_unique_ids',$data);
                }
            	
            	//echo $this->db->last_query(); exit;
            }
			//$this->db->where('branch_id',$user_data['parent_id']);
			//$this->db->delete('hms_branch_unique_ids');
			/*foreach($post['data'] as $key=>$val)
            {
            	$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$key,
                               "prefix"=>$val['prefix'],
                               "start_num"=>$val['start_num'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
            	$this->db->insert('hms_branch_unique_ids',$data);
            }*/
		} 
	}
 
    
} 
?>