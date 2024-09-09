<?php
class Whatsapp_sms_config_model extends CI_Model 
{
	var $table = 'hms_branch_whatsapp_sms_config'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		
		$this->db->select('hms_branch_whatsapp_sms_config.*');
		$this->db->from('hms_branch_whatsapp_sms_config');
		$this->db->where('hms_branch_whatsapp_sms_config.branch_id',$user_data['parent_id']);
		$query = $this->db->get(); 
		$num = $query->num_rows();
		//echo $this->db->last_query(); exit;
		if($num==0)
		{
				$this->db->select('hms_branch_whatsapp_sms_config.*');
				$this->db->from('hms_branch_whatsapp_sms_config');
				$this->db->where('hms_branch_whatsapp_sms_config.branch_id',0);
				$query = $this->db->get(); 
				//echo $this->db->last_query(); exit;
				
		}
		return $query->row();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//echo "<pre>"; print_r($post); exit;
		if(!empty($post['url']))
		{    
			$current_date = date('Y-m-d H:i:s');
			$this->db->where('branch_id',$user_data['parent_id']);
			$this->db->delete('hms_branch_whatsapp_sms_config');
			
			$data = array(
                           "branch_id"=>$user_data['parent_id'],
                           "whatsapp_username"=>$post['whatsapp_username'],
                           "whatsapp_password"=>$post['whatsapp_password'],
                           "ip_address"=>$_SERVER['REMOTE_ADDR'],
                           "created_by"=>$user_data['id'], 
                           "created_date"=>$current_date
        		         );
        	$this->db->insert('hms_branch_whatsapp_sms_config',$data);
             
           
		} 
	}
 
} 
?>