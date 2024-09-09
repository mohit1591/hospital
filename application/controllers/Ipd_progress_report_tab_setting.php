<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_progress_report_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        unauthorise_permission('125','763');
        $this->load->model('ipd_progress_report_tab_setting/ipd_progress_report_tab_setting_model','ipd_progress_report');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission('125','763');
        $data['page_title'] = 'Progress report Tab Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->ipd_progress_report->save();
            echo 'Your Progress report Tab Settings successfully updated.';;
            return false;
        }
        $data['progress_report_tab_setting_list'] = $this->ipd_progress_report->get_master_unique();
        $this->load->view('ipd_progress_report_tab_setting/add',$data);
    } 
      
   
}
?>