<?php
class Sms_setting_model extends CI_Model 
{
	var $table = 'hms_sms_setting_name'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_sms_setting_name.*, hms_branch_sms_setting.setting_name,hms_branch_sms_setting.sms_status,hms_branch_sms_setting.email_status');
		$this->db->join('hms_branch_sms_setting', 'hms_branch_sms_setting.unique_id=hms_sms_setting_name.id','left');
		$this->db->from('hms_sms_setting_name');
    $this->db->where('hms_branch_sms_setting.branch_id',$user_data['parent_id']);
		$query = $this->db->get(); 
    //echo $this->db->last_query(); exit;
		return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//echo "<pre>"; print_r($post); exit;
		if(!empty($post['data']))
		{    
			      foreach($post['data'] as $key=>$val)
            {	
            	if(!empty($val['sms_status']) && $val['sms_status']==1)
            	{
            		$sms_status=1;
            	}
            	else
            	{
            		$sms_status=0;
            	}
              
            	$this->db->set('sms_status',$sms_status);
              $this->db->where('branch_id',$user_data['parent_id']);
              $this->db->where('unique_id',$key);
              $this->db->where('setting_name',$val['setting_name']);
              $this->db->update('hms_branch_sms_setting');
             
            }
		} 
	}

  public function save_email()
  {  
    $user_data = $this->session->userdata('auth_users');
    $post = $this->input->post(); 
    //echo "<pre>"; print_r($post); exit;
    if(!empty($post['data']))
    {    
      
            foreach($post['data'] as $key=>$val)
            { 
              
              if(!empty($val['email_status']) && $val['email_status']==1)
              {
                $email_status=1;
              }
              else
              {
                $email_status=0;
              }
              
              $this->db->set('email_status',$email_status);
              $this->db->where('branch_id',$user_data['parent_id']);
              $this->db->where('unique_id',$key);
              $this->db->where('setting_name',$val['setting_name']);
              $this->db->update('hms_branch_sms_setting');

            }
    } 
  }

  function sms_template_format($data="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_sms_branch_template.*');
      $this->db->where($data);
      $this->db->where('hms_sms_branch_template.branch_id = "'.$users_data['parent_id'].'"'); 
      $this->db->from('hms_sms_branch_template');
      $query=$this->db->get()->row();
      //echo $this->db->last_query();
      return $query;

    }

    function sms_url()
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_branch_sms_config.*');
      //$this->db->where($data);
      $this->db->where('hms_branch_sms_config.branch_id = "'.$users_data['parent_id'].'"'); 
      $this->db->from('hms_branch_sms_config');
      $query=$this->db->get()->row();
      //echo $this->db->last_query();
      return $query;

    }
 
} 
?>