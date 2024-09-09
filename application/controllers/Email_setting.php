<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        //unauthorise_permission('6','29');
        $this->load->model('sms_setting/sms_setting_model','sms_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(101,639);
        $data['page_title'] = 'Email Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->sms_setting->save_email();
            echo 'Your Email Settings successfully updated.';
            return false;
        }
        $data['sms_setting_list'] = $this->sms_setting->get_master_unique();
        $this->load->view('email_setting/add',$data);
    } 
      
   
}
?>