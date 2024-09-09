<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('permission/permission_model','permission');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(8,42);
        $data['page_title'] = 'Permission Assignment'; 
        $post = $this->input->post();
        $data['form_error'] = [];
        if(!empty($post) && !empty($post))
        {
             $this->permission->save(); 
             echo 'User role permission successfully assigned';
             return false;               
        }
        $this->load->view('permission/permission',$data);
    }

    public function permission_section($user_role="")
    {
       if(!empty($user_role))
       {
            $data['section_list'] = $this->permission->section_list();
            $data['user_role'] = $user_role;
            $this->load->view('permission/permission_section',$data);
       } 
    }

}    