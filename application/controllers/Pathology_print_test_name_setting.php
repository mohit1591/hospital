<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_print_test_name_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pathology_print_test_name_setting/pathology_print_test_name_setting_model','pathology_print_test_setting');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('144','864');
        $data['page_title'] = 'Profile Name Setting'; 
        $this->load->view('pathology_print_test_name_setting/add',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('144','864');
        $users_data = $this->session->userdata('auth_users');
        $pathology_list = $this->pathology_print_test_setting->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($pathology_list);
        foreach ($pathology_list as $pathology_setting) 
        {
              // print_r($chief_complaints);die;
              $no++;
              $row = array();
              $row[] = $pathology_setting->module_name;  
              $profile_active_checked='';
              $profile_inactive_checked='';
              if($pathology_setting->profile_status==1)
              {
                $profile_active_checked = 'checked="checked"';
              }
              else
              {
                $profile_inactive_checked = 'checked="checked"';
              }
              $profile_module = "'".$pathology_setting->module."'";
              $row[] = '<input type="radio" value="1" '.$profile_active_checked.' name="profile_status_'.$i.'" onClick="update_profile_status('.$pathology_setting->branch_id.','.$profile_module.',1)"/> Active
                <input type="radio" value="0" name="profile_status_'.$i.'" '.$profile_inactive_checked.' onClick="update_profile_status('.$pathology_setting->branch_id.','.$profile_module.',0)"/> Inactive';
              $active_checked='';
              $inactive_checked='';
              if($pathology_setting->print_status==1)
              {
                $active_checked = 'checked="checked"';
              }
              else
              {
                $inactive_checked = 'checked="checked"';
              }
              $module = "'".$pathology_setting->module."'";
              $row[] = '<input type="radio" value="1" '.$active_checked.' name="print_status_'.$i.'" onClick="update_print_status('.$pathology_setting->branch_id.','.$module.',1)"/> Active
              <input type="radio" value="0" name="print_status_'.$i.'" '.$inactive_checked.' onClick="update_print_status('.$pathology_setting->branch_id.','.$module.',0)"/> Inactive';
              
              $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->pathology_print_test_setting->count_all(),
                        "recordsFiltered" => $this->pathology_print_test_setting->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

  public function update_print_status()
  {
     $post = $this->input->post();
     //echo "<pre>"; print_r($post); exit;
     $module = $post['module'];
     $branch_id = $post['branch_id'];
     $status = $post['status'];
     if(!empty($module))
     {
        $result = $this->pathology_print_test_setting->update_print_status($branch_id,$module,$status);
        echo $result;
        die;
     }
  } 

  public function update_profile_status()
  {
     $post = $this->input->post();
     //echo "<pre>"; print_r($post); exit;
     $module = $post['module'];
     $branch_id = $post['branch_id'];
     $status = $post['status'];
     if(!empty($module))
     {
       $result = $this->pathology_print_test_setting->update_profile_status($branch_id,$module,$status);
        echo $result;
        die;
     }
  } 
  

 } 
 ?>