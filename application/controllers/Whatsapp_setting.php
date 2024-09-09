<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        //unauthorise_permission('6','29');
        $this->load->model('whatsapp_setting/whatsapp_setting_model','sms_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(101,639);
        $data['page_title'] = 'Whatsapp SMS Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->sms_setting->save();
            echo 'Your Whatsapp Settings successfully updated.';
            return false;
        }
        $data['sms_setting_list'] = $this->sms_setting->get_master_unique();
        //echo "<pre>"; print_r($data['sms_setting_list']);die;
        $this->load->view('whatsapp_setting/add',$data);
    } 
      
   
}
?>