<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['page_title'] = 'Address Print Setting'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->address_setting->save();
            echo 'Your address print setting successfully updated.';;
            return false;
        }
        $data['address_list'] = $this->address_setting->get_master_unique();
        
        $this->load->view('address_print_setting/setting',$data);
    } 
      
   
}
?>