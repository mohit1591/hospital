<?php
class Time_print_setting_model extends CI_Model 
{
	var $table = 'hms_module_time_print_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_module_time_print_setting.*');
		
		$this->db->from('hms_module_time_print_setting');
		$this->db->where('hms_module_time_print_setting.branch_id',$user_data['parent_id']);  
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
                               "opd"=>$post['opd'],
                               "opd_billing"=>$post['opd_billing'],
                               "prescription"=>$post['prescription'],
                               "pathology"=>$post['pathology'],
                               "ot"=>$post['ot'],
                               "medicine"=>$post['medicine'],
                               "inventory"=>$post['inventory'],
                               "blood_bank"=>$post['blood_bank'],
                               "ambulance"=>$post['ambulance'],
                               "dialysis"=>$post['dialysis'],
                               "daycare"=>$post['daycare'],
                               'ipd'=>$post['ipd'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
			
           if(!empty($post['data_id']) && isset($post['data_id']))
             {
            	$this->db->update('hms_module_time_print_setting',$data);
            }
            else
            {
            	$this->db->insert('hms_module_time_print_setting',$data);
            }
		} 
	}
 
  
} 
?>