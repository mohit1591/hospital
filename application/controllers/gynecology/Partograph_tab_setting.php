<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partograph_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        //unauthorise_permission('345','2075');
        $this->load->model('partograph_tab_setting/partograph_tab_setting_model','partographtabsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
       //unauthorise_permission(345,2075);
        $data['page_title'] = 'Partograph Tab Settings'; 
        $post = $this->input->post();
        //print_r($post);die();
        if(!empty($post))
        { 
            $this->partographtabsetting->save();
            echo 'Your Partograph Tab Settings successfully updated.';;
            return false;
        }
        $data['partograph_tab_setting_list'] = $this->partographtabsetting->get_master_unique();
        $this->load->view('partograph_tab_setting/add',$data);
    } 
      
   
}
?>