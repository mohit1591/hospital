<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Default_doc_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
       // unauthorise_permission('218','1238');
        $this->load->model('default_doct/default_doct_model','default_doct');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['page_title'] = 'Default Doctor Setting'; 
        $this->load->model('general/general_model'); 
        $this->load->model('opd/opd_model','opd');
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->default_doct->save();
            echo 'Your Default Doctor successfully updated.';;
            return false;
        }
        $data['receipt_list'] = $this->default_doct->get_master_unique();

        if(!empty($post['branch_id']))
        {
          $data['specialization_list'] = $this->general_model->specialization_list($post['branch_id']);
        }
        else
        {
          $data['specialization_list'] = $this->general_model->specialization_list();
            
        }        
        $this->load->view('default_doct/setting',$data);
    } 
      
   
}
?>