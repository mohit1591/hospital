<?php
class Dialysis_admission_print_setting_model extends CI_Model 
{
	var $table = 'hms_dialysis_admission_print_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique($type='0')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dialysis_admission_print_setting.*, hms_dialysis_branch_admission_print_setting.setting_name, hms_dialysis_branch_admission_print_setting.setting_value');
		$this->db->join('hms_dialysis_branch_admission_print_setting', 'hms_dialysis_branch_admission_print_setting.unique_id=hms_dialysis_admission_print_setting.id','left');
		$this->db->from('hms_dialysis_admission_print_setting'); 
    $this->db->where('hms_dialysis_branch_admission_print_setting.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_dialysis_branch_admission_print_setting.type',$type); 
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
      $this->db->where('type','0');
			$this->db->delete('hms_dialysis_branch_admission_print_setting');
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
            	$this->db->insert('hms_dialysis_branch_admission_print_setting',$data);
      
            } //print_r($val['setting_value']); die;
            //echo $this->db->last_query(); die;
		} 
	}
  
  function template_format($data="",$branch_id='')
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_dialysis_branch_admission_print_setting.*');
    $this->db->where($data);
    if(!empty($branch_id))
    {
      $this->db->where('hms_dialysis_branch_admission_print_setting.branch_id = "'.$branch_id.'"');
    }
    else
    {
      $this->db->where('hms_dialysis_branch_admission_print_setting.branch_id = "'.$users_data['parent_id'].'"');
    }
     
    $this->db->from('hms_dialysis_branch_admission_print_setting');
    $result=$this->db->get()->row();
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
    
  function template_format_consent($data="",$branch_id='')
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_ipd_branch_admission_consent_print_form.*');
    $this->db->where($data);
    if(!empty($branch_id))
    {
      $this->db->where('hms_ipd_branch_admission_consent_print_form.branch_id = "'.$branch_id.'"');
    }
    else
    {
      $this->db->where('hms_ipd_branch_admission_consent_print_form.branch_id = "'.$users_data['parent_id'].'"');
    }
     
    $this->db->from('hms_ipd_branch_admission_consent_print_form');
    $result=$this->db->get()->row();
    return $result;

  }
  
  function template_format_consent_new($data="",$branch_id='')
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_ipd_branch_nabh_print_form.*');
    $this->db->where($data);
    if(!empty($branch_id))
    {
      $this->db->where('hms_ipd_branch_nabh_print_form.branch_id = "'.$branch_id.'"');
    }
    else
    {
      $this->db->where('hms_ipd_branch_nabh_print_form.branch_id = "'.$users_data['parent_id'].'"');
    }
     
    $this->db->from('hms_ipd_branch_nabh_print_form');
    $result=$this->db->get()->row();
    return $result;

  }
} 
?>