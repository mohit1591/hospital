<?php
class Menu_model extends CI_Model 
{
	var $table = 'hms_prescription_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_menu($parent_id='0')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_branch_menu.*');
		$this->db->from('hms_branch_menu'); 
    $this->db->where('hms_branch_menu.parent_id',$parent_id);
    $this->db->where('hms_branch_menu.branch_id',$user_data['parent_id']); 
    $this->db->order_by('sort_order','ASC'); 
    $query = $this->db->get();
    //echo $this->db->last_query();
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
            foreach($post['data'] as $key=>$val)
            {
            	$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "parent_id"=>$val['parent_id'],
                               "name"=>$val['name'],
                               "status"=>1,
                               'sort_order'=>$val['sort_order'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
              $this->db->where('id',$val['id']);
              $this->db->where('branch_id',$user_data['parent_id']);
            	$this->db->update('hms_branch_menu',$data);
      
            } //print_r($val['setting_value']); die;
            //echo $this->db->last_query(); die;
		} 
	}
 
  public function get_sub_master_menu($parent_id='0')
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_branch_menu.*');
    $this->db->from('hms_branch_menu'); 
    $this->db->where('hms_branch_menu.parent_id',$parent_id);
    $this->db->where('hms_branch_menu.branch_id',$user_data['parent_id']);
    $this->db->where('hms_branch_menu.status','1'); 
    $this->db->order_by('hms_branch_menu.sort_order','ASC'); 
    $query = $this->db->get();
    //echo $this->db->last_query();
    return $query->result();
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