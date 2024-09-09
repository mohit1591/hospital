<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt_no_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        unauthorise_permission('218','1238');
        $this->load->model('receipt_no_setting/receipt_no_setting_model','receipt_no_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['page_title'] = 'Receipt No Setting'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->receipt_no_setting->save();
            echo 'Your Receipt no successfully updated.';;
            return false;
        }
        $data['receipt_list'] = $this->receipt_no_setting->get_master_unique();
        
        $this->load->view('receipt_no_setting/setting',$data);
    } 
      
   
}
?>