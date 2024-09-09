<?php
class Default_doct_model extends CI_Model 
{
	var $table = 'hms_default_doct_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_default_doct_setting.*');		
		$this->db->from('hms_default_doct_setting');
		$this->db->where('hms_default_doct_setting.branch_id',$user_data['parent_id']);  
		$query = $this->db->get(); 
		return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		if(!empty($post))
		{    
			$current_date = date('Y-m-d H:i:s');

			$this->db->where('branch_id',$user_data['parent_id']);
			
			$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "specialize_id"=>$post['specialization'],
                               "doctor_id"=>$post['attended_doctor'],
                               "doc_status"=>1,
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
			
           if(!empty($post['data_id']) && isset($post['data_id']))
             {
            	$this->db->update('hms_default_doct_setting',$data);
            }
            else
            {
            	$this->db->insert('hms_default_doct_setting',$data);
            }
           // echo $this->db->last_query();die();
		} 
	}
 
  
} 
?>