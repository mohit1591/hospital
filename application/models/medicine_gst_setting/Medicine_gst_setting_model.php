<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_gst_setting_model extends CI_Model {
 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	} 

	public function get_default_setting()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_medicine_gst_setting.*');
		$this->db->from('hms_medicine_gst_setting'); 
		$this->db->where('hms_medicine_gst_setting.branch_id',$user_data['parent_id']); 
		$query = $this->db->get(); 
		$result = $query->result_array();
		$setting = [];
		if(!empty($result))
		{
			foreach($result as $data)
			{
              $setting[$data['module_id']] = $data['status']; 
			}
		}
		return $setting;
	}
	
	public function save_setting()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->delete('hms_medicine_gst_setting');
		if(!empty($post))
		{
			foreach($post['records'] as $module=>$status)
			{
				$this->db->set('branch_id',$user_data['parent_id']);
				$this->db->set('module_id',$module); 
				$this->db->set('status',$status); 
				$this->db->set('modified_date',date('Y-m-d H:i:s')); 
				$this->db->insert('hms_medicine_gst_setting');  
			}
		} 	
	} 

     
  

}
?>