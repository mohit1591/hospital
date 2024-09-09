<?php
class Barcode_setting_model extends CI_Model 
{
	var $table = 'hms_branch_barcode'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		
		$this->db->select('hms_branch_barcode.*');
		$this->db->from('hms_branch_barcode');
		$this->db->where('hms_branch_barcode.branch_id',$user_data['parent_id']);
		$query = $this->db->get(); 
		$num = $query->num_rows();
		//echo $this->db->last_query(); exit;
		if($num==0)
		{
				$this->db->select('hms_branch_barcode.*');
				$this->db->from('hms_branch_barcode');
				$this->db->where('hms_branch_barcode.branch_id',0);
				$query = $this->db->get(); 
				//echo $this->db->last_query(); exit;
				
		}
		return $query->row();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		if(isset($post))
		{    
				$current_date = date('Y-m-d H:i:s');
				$this->db->where('branch_id',$user_data['parent_id']);
				$this->db->delete('hms_branch_barcode');
			
				$data = array(
                           "branch_id"=>$user_data['parent_id'],
                           "total_receipt"=>$post['total_receipt'],
                           "type"=>$post['type'],
                           "size"=>$post['size'],
                           "ip_address"=>$_SERVER['REMOTE_ADDR'],
                           "created_by"=>$user_data['id'], 
                           "created_date"=>$current_date
        		         );
        		$this->db->insert('hms_branch_barcode',$data);
             
           
		} 
	}
 
} 
?>