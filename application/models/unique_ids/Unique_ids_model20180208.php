<?php
class Unique_ids_model extends CI_Model 
{
	var $table = 'hms_unique_ids'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_unique_ids.*, hms_branch_unique_ids.prefix, hms_branch_unique_ids.start_num,hms_branch_unique_ids.unique_id');
		$this->db->join('hms_branch_unique_ids', 'hms_branch_unique_ids.unique_id=hms_unique_ids.id AND hms_branch_unique_ids.branch_id = "'.$user_data['parent_id'].'"','left');
		$this->db->from('hms_unique_ids');  
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
			$this->db->delete('hms_branch_unique_ids');
			
            foreach($post['data'] as $key=>$val)
            {
            	$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$key,
                               "prefix"=>$val['prefix'],
                               "start_num"=>$val['start_num'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
            	$this->db->insert('hms_branch_unique_ids',$data);
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