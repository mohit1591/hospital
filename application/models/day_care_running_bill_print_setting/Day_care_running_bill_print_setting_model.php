<?php
class Day_care_running_bill_print_setting_model extends CI_Model 
{
	var $table = 'hms_day_care_branch_running_bill_print_setting '; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_day_care_branch_running_bill_print_setting.*');
		
		$this->db->from('hms_day_care_branch_running_bill_print_setting'); 
    $this->db->where('hms_day_care_branch_running_bill_print_setting.branch_id',$user_data['parent_id']); 
   $query = $this->db->get();
    
		return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
   //print_r($post); exit;
		if(!empty($post['data']))
		{    
			$current_date = date('Y-m-d H:i:s');

			$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->delete('hms_day_care_branch_running_bill_print_setting');
			//$message = $post['message'];
            foreach($post['data'] as $key=>$val)
            {
            	$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "setting_name"=>$val['setting_name'],
                               "setting_value"=>$val['setting_value'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
            	$this->db->insert('hms_day_care_branch_running_bill_print_setting',$data);
      
            } //print_r($val['setting_value']); die;
            //echo $this->db->last_query(); die;
		} 
	}
  
  function template_format($data="",$branch_id='')
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_day_care_branch_running_bill_print_setting.*');
    $this->db->where($data);
    if(!empty($branch_id))
    {
      $this->db->where('hms_day_care_branch_running_bill_print_setting.branch_id = "'.$branch_id.'"');
    }
    else
    {
      $this->db->where('hms_day_care_branch_running_bill_print_setting.branch_id = "'.$users_data['parent_id'].'"');
    }
     
    $this->db->from('hms_day_care_branch_running_bill_print_setting');
    $result=$this->db->get()->row();
    return $result;

  }
    

} 
?>