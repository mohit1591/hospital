<?php
class Token_setting_model extends CI_Model 
{
	var $table = 'path_token_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
 
	public function get_master_unique()
	{

		$user_data = $this->session->userdata('auth_users');		
		$this->db->select('path_token_setting.*');
		$this->db->from('path_token_setting');
		$this->db->where('path_token_setting.branch_id',$user_data['parent_id']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$num = $query->num_rows();
		//echo $this->db->last_query(); exit;
		if($num==0)
		{
				$this->db->select('path_token_setting.*');
				$this->db->from('path_token_setting');
				$this->db->where('path_token_setting.branch_id',0);
				$query = $this->db->get(); 
				//echo $this->db->last_query(); exit;
				
		}
		return $query->row();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
	//	echo "<pre>"; print_r($post); exit;
		$current_date = date('Y-m-d H:i:s');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->delete('path_token_setting');
		$data = array(
                           "branch_id"=>$user_data['parent_id'],
                           "type"=>$post['type'],
                           "created_date"=>$current_date
        		         );
        	$this->db->insert('path_token_setting',$data);
             	 
	}
 
} 
?>