<?php
class Nabh_form_print_setting_model extends CI_Model 
{
    var $table = 'hms_ipd_branch_nabh_print_form'; 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    public function get_master_unique($type='0',$form_type='1',$form_name='IPD_PRINT_CONSENT')
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_ipd_branch_nabh_print_form.*');
        $this->db->from('hms_ipd_branch_nabh_print_form'); 
    $this->db->where('hms_ipd_branch_nabh_print_form.branch_id',$user_data['parent_id']); 
    $this->db->where('hms_ipd_branch_nabh_print_form.form_type',$form_type);
        $query = $this->db->get(); 
    return $query->result();
    }
    
    public function save()
    {  
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
      
    if(!empty($post['data_id']))
    {
        $this->db->set('setting_value',$post['message']);
        $this->db->where('id',$post['data_id']);
        $this->db->where('form_type',$post['template_name']);
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->update('hms_ipd_branch_nabh_print_form',$data);
        //echo $this->db->last_query();die();
    }

   } 

    public function get_hms_prescription_page()
    {
        $this->db->select('page_data');  
        $query = $this->db->get('hms_ipd_branch_nabh_print_form');
        $result = $query->row();
        $data='';
        if(!empty($result))
        {
            $data = $result->page_data;
        }
        return $data;
    }

    function template_format($data="",$branch_id='')
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

    public function second_template_format($temp_type="")
    {

        $users_data = $this->session->userdata('auth_users');
        $data=array('form_type'=>$temp_type);
        $this->db->select("hms_ipd_branch_nabh_print_form.*"); 
        $this->db->from('hms_ipd_branch_nabh_print_form'); 
        $this->db->where($data);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get();
       // echo  $this->db->last_query();die(); 
        return $query->row_array();
  }


  

} 
?>