<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_card_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('reg_card_setting/reg_card_setting_model','reg_card_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        //unauthorise_permission(125,766);
        $data['page_title'] = 'Reg. Card Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->reg_card_setting->save();
            echo 'Your Reg. card Settings successfully updated.';
            return false;
        }

        
        $data['reg_card_setting_list'] = $this->reg_card_setting->get_master_unique();
       
        $this->load->view('reg_card_setting/add',$data);
    }


}
?>