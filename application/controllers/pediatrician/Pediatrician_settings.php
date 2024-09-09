<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_settings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();

        $this->load->model('pediatrician/pediatrician_settings/Pediatrician_settings_model','pediatrician_settings');
         $this->load->library('form_validation');
    }

    public function index()
    { 

        unauthorise_permission('271','1587');
        $data['page_title'] = 'Pediatrician setting list'; 
        $this->load->view('pediatrician/pediatrician_settings/list',$data);
    }

    public function ajax_list()
    {   
        unauthorise_permission('271','1587');
        $list = $this->pediatrician_settings->get_datatables();
        //print_r($list);
        //die;  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $pediatrician_settings) 
        {
            // print_r($relation);die;
            $no++;
            $row = array();
           
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
            ////////// Check list end ///////////// 
          $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$pediatrician_settings->id.'">'.$check_script; 
          //$row[] = $website_setting->var_title;
          $row[] = $pediatrician_settings->var_name;
          $row[] = $pediatrician_settings->setting_value1;  
          $row[] = $pediatrician_settings->setting_value2;  

          $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          $btnrestore='';
         if(in_array('1589',$users_data['permission']['action']))
          {
          $btnedit = ' <a onClick="return edit_pedic_settings('.$pediatrician_settings->id.');" class="btn-custom" href="javascript:void(0)" style="'.$pediatrician_settings->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
         
          $row[] = $btnedit.$btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->pediatrician_settings->count_all(),
                        "recordsFiltered" => $this->pediatrician_settings->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('271','1588');
        $data['page_title'] = "Add Pediatrician Setting";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'var_title'=>"",
                                  'var_name'=>'',
                                  'setting_value1'=>"",
                                  'setting_value2'=>""
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->pediatrician_settings->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/pediatrician_settings/add',$data);       
    }
    
    public function edit($id="")
    {
       unauthorise_permission('271','1589');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->pediatrician_settings->get_by_id($id);  
        $data['page_title'] = "Update Pediatrician Setting";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'var_title'=>$result['var_title'], 
                                  'var_name'=>$result['var_name'], 
                                  'type'=>$result['type'], 
                                  'setting_value1'=>$result['setting_value1'],
                                   'setting_value2'=>$result['setting_value2'],
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->pediatrician_settings->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        $this->load->view('pediatrician/pediatrician_settings/add',$data);      
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('var_title', 'Var Title', 'trim|required'); 
        $this->form_validation->set_rules('var_name', 'Var Name', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'var_title'=>$post['var_title'],
                                        'var_name'=>$post['var_name'],
                                        'type'=>$post['type'],
                                        'setting_value1'=>$post['setting_value1'],
                                        'setting_value2'=>$post['setting_value2']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('267','1554');
       if(!empty($id) && $id>0)
       {
           $result = $this->pediatrician_settings->delete($id);
           $response = "Pediatrician setting successfully deleted.";
           echo $response;
       }
    }

    public function restore_setting($id="")
    {
      unauthorise_permission('267','1558');
       if(!empty($id) && $id>0)
       {
           $result = $this->pediatrician_settings->restore_setting($id);
           $response = "Pediatrician setting restore successfully.";
           echo $response;
       }
    }

    function deleteall()
    {
      unauthorise_permission('267','1554');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->pediatrician_settings->deleteall($post['row_id']);
            $response = "Pediatrician setting successfully deleted.";
            echo $response;
        }
    }


    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->pediatrician_settings->get_by_id($id);  
        $data['page_title'] = $data['form_data']['blood_bank_settings']." detail";
        $this->load->view('blood_bank/blood_bank_settings/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
         unauthorise_permission('267','1555');
        $data['page_title'] = 'Pediatrician setting archive list';
        $this->load->helper('url');
        $this->load->view('blood_bank/blood_bank_settings/archive',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission('267','1555');
        $this->load->model('blood_bank/blood_bank_settings/blood_bank_settings_archive_model','blood_bank_settings_archive'); 

        $list = $this->blood_bank_settings_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $blood_bank_settings) { 
            $no++;
            $row = array();
            
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$blood_bank_settings->id.'">'.$check_script; 
            $row[] = $blood_bank_settings->var_title;
            $row[] = $blood_bank_settings->var_name;
            $row[] = $blood_bank_settings->setting_value;  
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1558',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_blood_bank_settings('.$blood_bank_settings->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1556',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$blood_bank_settings->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->blood_bank_settings_archive->count_all(),
                        "recordsFiltered" => $this->blood_bank_settings_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    

    public function restore($id="")
    {
         unauthorise_permission('267','1559');
        $this->load->model('blood_bank/blood_bank_settings/blood_bank_settings_archive_model','blood_bank_settings_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->blood_bank_settings_archive->restore($id);
           $response = "Blood Bank setting successfully restore in blood bank setting list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('267','1559');
        $this->load->model('blood_bank/blood_bank_settings/blood_bank_settings_archive_model','blood_bank_settings_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->blood_bank_settings_archive->restoreall($post['row_id']);
            $response = "Blood Bank Setting successfully restore in blood bank setting list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        // unauthorise_permission('92','62');
        $this->load->model('blood_bank/blood_bank_settings/blood_bank_settings_archive_model','blood_bank_settings_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->blood_bank_settings_archive->trash($id);
           $response = "Blood Bank Setting successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        // unauthorise_permission('92','62');
      $this->load->model('blood_bank/blood_bank_settings/blood_bank_settings_archive_model','blood_bank_settings_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->blood_bank_settings_archive->trashall($post['row_id']);
            $response = "Blood Bank Setting successfully successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function website_setting_dropdown()
  {

      $blood_bank_setting_list = $this->blood_bank_settings->blood_bank_setting_list();
      $dropdown = '<option value="">Select Blood Bank Setting</option>'; 
      if(!empty($blood_bank_setting_list))
      {
        foreach($blood_bank_setting_list as $blood_bank_setting)
        {
           $dropdown .= '<option value="'.$blood_bank_setting->id.'">'.$blood_bank_setting->setting_value.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>