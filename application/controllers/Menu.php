<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Menu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('menu/menu_model','menu');
		$this->load->library('form_validation');
	}

	public function index()
    {
        
        //unauthorise_permission(92,583);
        $data['page_title'] = 'Menu Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            
            $this->menu->save();
            echo 'Your Menu Settings successfully updated.';;
            return false;
        }

        
        $data['menu_list'] = $this->menu->get_master_menu(0);
        $this->load->view('menu/add',$data);
    }


    

}	