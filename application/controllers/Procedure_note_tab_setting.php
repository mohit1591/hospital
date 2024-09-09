<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Procedure_note_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        
        $this->load->model('procedure_note_tab_setting/procedure_note_tab_setting_model','procedure_note');
        $this->load->library('form_validation');
    }

    public function index()
    {
       unauthorise_permission(121,733);
        $data['page_title'] = 'Procedure Note Tab Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->procedure_note->save();
            echo 'Your Procedure Note Tab Settings successfully updated.';;
            return false;
        }
        $data['procedure_note_tab_setting_list'] = $this->procedure_note->get_setting();
        $this->load->view('procedure_note_tab_setting/add',$data);
    } 
      
   
}
?>