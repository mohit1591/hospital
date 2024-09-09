<?php
class Collection_tab_setting_model extends CI_Model 
{
	var $table = 'hms_collection_tab_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('hms_branch_collection_tab_setting.*');
      $this->db->where('hms_branch_collection_tab_setting.branch_id',$user_data['parent_id']);
      $this->db->from('hms_branch_collection_tab_setting');
      $this->db->order_by('hms_branch_collection_tab_setting.order_by','ASC');  
      $query = $this->db->get(); 
      return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//echo "<pre>"; print_r($post); exit;
		if(!empty($post['data']))
		{    
			$current_date = date('Y-m-d H:i:s');
      $this->db->where('branch_id',$user_data['parent_id']);
		  $this->db->delete('hms_branch_collection_tab_setting');
			
            foreach($post['data'] as $key=>$val)
            {	
            	
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
                               "var_title"=>$val['var_title'],
                               "setting_name"=>$val['setting_name'],
                               "setting_value"=>$val['setting_value'],
                               'print_status'=>$print_status,
                               "order_by"=>$val['order_by'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
            	$this->db->insert('hms_branch_collection_tab_setting',$data);
            }
		} 
	}
 
   
} 
?>