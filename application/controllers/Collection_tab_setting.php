<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collection_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //auth_users();
        //unauthorise_permission('6','29');
        $this->load->model('collection_tab_setting/collection_tab_setting_model','collection');
        $this->load->library('form_validation');
    }

    public function index()
    {
        //unauthorise_permission('42','245');
        $data['page_title'] = 'Collection Tab Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->collection->save();
            echo 'Your collection Tab Settings successfully updated.';;
            return false;
        }
        $data['collection_tab_setting_list'] = $this->collection->get_master_unique();
        $this->load->view('collection_tab_setting/add',$data);
    } 
      
   
}
?>