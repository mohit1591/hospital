<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Default_path_doc_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('default_doct/default_path_doct_model','default_doct');
        $this->load->model('general/general_model'); 
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['page_title'] = 'Pathology Default Doctor Setting'; 
      
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
        $this->load->view('default_doct/path_setting',$data);
    } 
      
   
}
?>