<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gynecology_medicine_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        unauthorise_permission('308','1844');
        $this->load->model('gynecology/gynecology_medicine_tab_setting/Gynecology_medicine_tab_setting_model','tab_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission('308','1844');
        $data['page_title'] = 'Gynecology Medicine Tab Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->tab_setting->save();
            echo 'Your Gynecology Medicine Settings successfully updated.';;
            return false;
        }
       $data['gynecology_tab_setting_list'] = $this->tab_setting->get_master_unique();
        $this->load->view('gynecology/gynecology_medicine_tab_setting/add',$data);
    } 
      
   
}
?>