<?php
class Partograph_setting_model extends CI_Model 
{
	var $table = 'hms_partograph_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique($type='0')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_partograph_setting.*, hms_branch_partograph_setting.setting_name, hms_branch_partograph_setting.setting_value');
		$this->db->join('hms_branch_partograph_setting', 'hms_branch_partograph_setting.unique_id=hms_partograph_setting.id','left');
		$this->db->from('hms_partograph_setting'); 
    $this->db->where('hms_branch_partograph_setting.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_branch_partograph_setting.type',$type); 
		$query = $this->db->get();
   // echo $this->db->last_query();die();
		return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
  // print_r($post); exit;
		if(!empty($post['data']))
		{    
			$current_date = date('Y-m-d H:i:s');

			$this->db->where('branch_id',$user_data['parent_id']);
      $this->db->where('type','0');
			$this->db->delete('hms_branch_partograph_setting');
			//$message = $post['message'];
            foreach($post['data'] as $key=>$val)
            {
            	$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               'type'=>0,
                               "unique_id"=>$key,
                               "setting_name"=>$val['setting_name'],
                               "setting_value"=>$val['setting_value'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
            	$this->db->insert('hms_branch_partograph_setting',$data);
      
            } //print_r($val['setting_value']); die;
            //echo $this->db->last_query(); die;
		} 
	}
 
    

    public function get_hms_partograph_page()
    {
    	$this->db->select('page_data');  
        $query = $this->db->get('hms_partograph_page');
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