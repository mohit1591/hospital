<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mlc_no_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
       // unauthorise_permission('218','1238');
        $this->load->model('mlc_no_setting/mlc_no_setting_model','mlc_no_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['page_title'] = 'MLC No Setting'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->mlc_no_setting->save();
            echo 'Your MLC no successfully updated.';;
            return false;
        }
        $data['receipt_list'] = $this->mlc_no_setting->get_master_unique();
        
        $this->load->view('mlc_no_setting/setting',$data);
    } 
      
   
}
?>