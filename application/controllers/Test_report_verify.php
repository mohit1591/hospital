<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_report_verify extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_report_verify/test_report_verify_model','test_report_verify');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('249','1422');
        $post = $this->input->post();
        $data['page_title'] = 'Test Report Setting';
        $data['selected_dept'] = $this->test_report_verify->branch_verify_dept();
        $report_setting_data = $this->test_report_verify->branch_report_setting();
        $data['report_setting'] = array('report_print'=>'0','report_lock'=>'0','report_break'=>'0');
        if(!empty($report_setting_data))
        {
            $data['report_setting'] = array('report_print'=>$report_setting_data->report_print,'report_lock'=>$report_setting_data->report_lock,'report_break'=>$report_setting_data->report_break);
        }

        if(!empty($post))
        {
          $this->test_report_verify->save($post);
          echo 1;die;
        }
        $this->load->model('general/general_model','general'); 
        $data['dept_list'] = $this->general->active_department_list(5);
        $this->load->view('test_report_verify/verify',$data);
    } 
  
}
?>