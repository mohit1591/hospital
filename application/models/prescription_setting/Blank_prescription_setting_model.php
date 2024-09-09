<?php
class Blank_prescription_setting_model extends CI_Model 
{
	var $table = 'hms_prescription_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique($type='1')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_prescription_setting.*, hms_branch_prescription_setting.setting_name, hms_branch_prescription_setting.setting_value,hms_branch_prescription_setting.header_content');
		$this->db->join('hms_branch_prescription_setting', 'hms_branch_prescription_setting.unique_id=hms_prescription_setting.id','left');
		$this->db->from('hms_prescription_setting'); 
    $this->db->where('hms_branch_prescription_setting.branch_id',$user_data['parent_id']);  
    $this->db->where('hms_branch_prescription_setting.type',$type);  
		$query = $this->db->get();

		return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
   
		if(!empty($post['data']))
		{    
			$current_date = date('Y-m-d H:i:s');

			$this->db->where('branch_id',$user_data['parent_id']);
      $this->db->where('type',1);
			$this->db->delete('hms_branch_prescription_setting');
			//$message = $post['message'];
            foreach($post['data'] as $key=>$val)
            {
            	$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               'type'=>1,
                               "unique_id"=>$key,
                               "setting_name"=>$val['setting_name'],
                               "setting_value"=>$val['setting_value'],
                               'header_content'=>$val['header_content'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
            	$this->db->insert('hms_branch_prescription_setting',$data);
      
            } //print_r($val['setting_value']); die;
            //echo $this->db->last_query(); die;
		} 
	}
 
    public function rate_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_rate_plan');
        $result = $query->result(); 
        return $result; 
    }

    public function get_hms_prescription_page()
    {
    	$this->db->select('page_data');  
        $query = $this->db->get('hms_prescription_page');
        $result = $query->row();
        $data='';
        if(!empty($result))
        {
        	$data = $result->page_data;
        }
        return $data;
    } 
} 
?>