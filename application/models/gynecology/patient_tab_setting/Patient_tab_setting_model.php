<?php
class Patient_tab_setting_model extends CI_Model 
{
	var $table = 'hms_gynecology_patient_tab_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_gynecology_patient_tab_setting.*, hms_gynecology_branch_patient_tab_setting.setting_name, hms_gynecology_branch_patient_tab_setting.setting_value,hms_gynecology_branch_patient_tab_setting.order_by,hms_gynecology_branch_patient_tab_setting.status,hms_gynecology_branch_patient_tab_setting.print_status');
		$this->db->join('hms_gynecology_branch_patient_tab_setting', 'hms_gynecology_branch_patient_tab_setting.unique_id=hms_gynecology_patient_tab_setting.id AND hms_gynecology_branch_patient_tab_setting.branch_id = "'.$user_data['parent_id'].'"','left');
		$this->db->from('hms_gynecology_patient_tab_setting');
		$this->db->order_by('hms_gynecology_branch_patient_tab_setting.order_by','ASC');  
		$query = $this->db->get(); 
		return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
	//	echo "<pre>"; print_r($post); exit;
		if(!empty($post['data']))
		{    
			$current_date = date('Y-m-d H:i:s');

			$this->db->where('branch_id',$user_data['parent_id']);
		  $this->db->delete('hms_gynecology_branch_patient_tab_setting');
			
            foreach($post['data'] as $key=>$val)
            {	
            	if(!empty($val['status']) && $val['status']==1)
            	{
            		$status=1;
            	}
            	else
            	{
            		$status=0;
            	}
              if(!empty($val['print_status']) && $val['print_status']==1)
              {
                $print_status=1;
              }
              else
              {
                $print_status=0;
              }
            	
            	$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$key,
                               "setting_name"=>$val['setting_name'],
                               "setting_value"=>$val['setting_value'],
                               "status"=>$status,
                               'print_status'=>$print_status,
                               "order_by"=>$val['order_by'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
            	$this->db->insert('hms_gynecology_branch_patient_tab_setting',$data);
            }
		} 
	}
 
    public function rate_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_rate_plan');
        $result = $query->result(); 
        return $result; 
    } 
} 
?>