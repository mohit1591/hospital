<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Time_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('time_print_setting/time_print_setting_model','time_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['page_title'] = 'Time Print Setting'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->time_setting->save();
            echo 'Your Time print setting successfully updated.';;
            return false;
        }
        $data['address_list'] = $this->time_setting->get_master_unique();
        
        $this->load->view('time_print_setting/setting',$data);
    } 
      
   
}
?>