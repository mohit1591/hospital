<?php
class Opd_validity_setting_model extends CI_Model 
{
	var $table = 'hms_opd_validity_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
 
	public function get_master_unique()
	{

		$user_data = $this->session->userdata('auth_users');
		
		$this->db->select('hms_opd_validity_setting.*');
		$this->db->from('hms_opd_validity_setting');
		$this->db->where('hms_opd_validity_setting.branch_id',$user_data['parent_id']);
		$query = $this->db->get(); 
		$num = $query->num_rows();
		//echo $this->db->last_query(); exit;
		if($num==0)
		{
				$this->db->select('hms_opd_validity_setting.*');
				$this->db->from('hms_opd_validity_setting');
				$this->db->where('hms_opd_validity_setting.branch_id',0);
				$query = $this->db->get(); 
				//echo $this->db->last_query(); exit;
				
		}
		return $query->row();
	}
	
	public function save()
	{  
		//echo "hi";die;
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//echo "<pre>"; print_r($post); exit;
		$current_date = date('Y-m-d H:i:s');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->delete('hms_opd_validity_setting');
		$data = array(
                           "branch_id"=>$user_data['parent_id'],
                           "days"=>$post['days'],
                         //  "ip_address"=>$_SERVER['REMOTE_ADDR'],
                         //  "created_by"=>$user_data['id'], 
                           "created_date"=>$current_date
        		         );
		//print_r($data);die;
        	$this->db->insert('hms_opd_validity_setting',$data);
             	 
	}
 
} 
?>