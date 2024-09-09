<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Birthday_anniversary_model extends CI_Model {
  
	public function __construct()
	{
		parent::__construct();  
	}
    
    public function save_birthday_anniversary()
    {
        $post = $this->input->post(); 
        //print_r($post); exit;
        $user_data = $this->session->userdata('auth_users');
        $data = array(     
                    "branch_id"=>$user_data['parent_id'], 
                    "sms_birthday"=>$post['sms'], 
                    "birthday_sms_template"=>$post['birthday_message'],
                    'sms_anniversary'=>$post['anni_sms'],
                    "anniversary_sms_template"=>$post['anni_message'],
                    "email_bithday"=>$post['birth_email'], 
                    "email_birthday_template"=>$post['birth_email_template'],
                    "email_anniversary"=>$post['anni_email'],
                    "anniversary_email_template"=>$post['anni_email_template'],
                    );
                
                if(!empty($post['data_id']) && $post['data_id']>0)
                {    
                    $this->db->set('modified_by',$user_data['id']);
                    $this->db->set('modified_date',date('Y-m-d H:i:s'));
                    $this->db->where('id',$post['data_id']);
                    $this->db->update('hms_birthday_anniversary',$data);  
                }
                else
                {    
                    $this->db->set('created_by',$user_data['id']);
                    $this->db->set('created_date',date('Y-m-d H:i:s'));
                    $this->db->insert('hms_birthday_anniversary',$data);               
                }   
    }


    

    public function get_birthday_anniversary()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_birthday_anniversary.*');
        $this->db->from('hms_birthday_anniversary'); 
        $this->db->where('hms_birthday_anniversary.branch_id',$user_data['parent_id']);
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function save_birthday_setting()
    {   
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
        //print_r($post); exit;
        $data = array('setting_value'=>$post['type']);
        if(!empty($post['setting_id']) && $post['setting_id']>0)
        {    
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['setting_id']);
            $this->db->update('hms_website_setting',$data);  
        }   
    }

    public function get_setting($setting_name='',$branch_id='')
    {
        $this->db->select('hms_website_setting.*');
        $this->db->from('hms_website_setting'); 
        
        $this->db->where('hms_website_setting.var_title',$setting_name);
        $this->db->where('hms_website_setting.branch_id',$branch_id);
        $query = $this->db->get(); 
        return $query->row_array();
    }
}