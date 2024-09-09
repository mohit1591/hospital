<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unique_ids extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        unauthorise_permission('6','29');
        $this->load->model('unique_ids/unique_ids_model','unique_ids');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['page_title'] = 'Unique Ids setting'; 
        $post = $this->input->post();
        //echo "<pre>"; print_r($post); exit;
        if(!empty($post))
        { 
            $this->unique_ids->save();
            echo 'Your unique ids successfully updated.';;
            return false;
        }
        $data['unique_list'] = $this->unique_ids->get_master_unique();
        $this->load->view('unique_ids/setting',$data);
    } 
      
   
}
?>