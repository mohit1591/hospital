<?php
class Address_print_setting_model extends CI_Model 
{
	var $table = 'hms_address_print_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_address_print_setting.*');
		
		$this->db->from('hms_address_print_setting');
		$this->db->where('hms_address_print_setting.branch_id',$user_data['parent_id']);  
		$query = $this->db->get(); 
		//echo $this->db->last_query();die();
		return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//print '<pre>'; print_r($post);die;
		if(!empty($post))
		{    
			$current_date = date('Y-m-d H:i:s');

			$this->db->where('branch_id',$user_data['parent_id']);
			
			$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "address1"=>$post['address1'],
                               "address2"=>$post['address2'],
                               "address3"=>$post['address3'],
                               "country"=>$post['country'],
                               "state"=>$post['state'],
                               "city"=>$post['city'],
                               "pincode"=>$post['pincode'],
                               
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
			
           if(!empty($post['data_id']) && isset($post['data_id']))
             {
            	$this->db->update('hms_address_print_setting',$data);
            }
            else
            {
            	$this->db->insert('hms_address_print_setting',$data);
            }
		} 
	}
 
  
} 
?>