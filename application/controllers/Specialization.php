<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Specialization extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('specialization/specialization_model','specialization');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(9,43);
        $data['page_title'] = 'Specialization List'; 
        $this->load->view('specialization/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(9,43);
        $users_data = $this->session->userdata('auth_users');
       
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
     
            $list = $this->specialization->get_datatables();
         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $specialization) {
         // print_r($employee_type);die;
            $no++;
            $row = array();
            if($specialization->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($specialization->state))
            {
                $state = " ( ".ucfirst(strtolower($specialization->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                 
            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$specialization->branch_id){
                $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$specialization->id.'">'.$check_script;
            }else{
                $row[]='';
            } 
            $row[] = $specialization->specialization;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($specialization->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
        
            if($users_data['parent_id']==$specialization->branch_id){
                if(in_array('45',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_specialization('.$specialization->id.');" class="btn-custom" href="javascript:void(0)" style="'.$specialization->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                if(in_array('46',$users_data['permission']['action'])) 
                {
                   $btndelete = ' <a class="btn-custom" onClick="return delete_specialization('.$specialization->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

             
        
             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->specialization->count_all(),
                        "recordsFiltered" => $this->specialization->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission(9,44);
        $data['page_title'] = "Add Specialization";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'specialization'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->specialization->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('specialization/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission(9,45);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->specialization->get_by_id($id); 
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->specialization->specialization_list();  
        $data['page_title'] = "Update Specialization";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'specialization'=>$result['specialization'],

                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->specialization->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('specialization/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('specialization', 'specialization', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'specialization'=>$post['specialization'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(9,46);
       if(!empty($id) && $id>0)
       {
           $result = $this->specialization->delete($id);
           $response = "Specialization successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(9,46);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->specialization->deleteall($post['row_id']);
            $response = "Specialization successfully deleted.";
            echo $response;
        }
    }

     

    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(9,47);
        $data['page_title'] = 'Specialization Archive List';
        $this->load->helper('url');
        $this->load->view('specialization/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(9,47); 
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('specialization/specialization_archive_model','specialization_archive'); 

       

               $list = $this->specialization_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $specialization) {
         // print_r($employee_type);die;
            $no++;
            $row = array();
            if($specialization->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($specialization->state))
            {
                $state = " ( ".ucfirst(strtolower($specialization->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$specialization->id.'">'.$check_script; 
            $row[] = $specialization->specialization;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($specialization->created_date)); 
 
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";

            if(in_array('49',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_specialization('.$specialization->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 

            if(in_array('48',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$specialization->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //
 
           $row[] = $btn_restore.$btn_delete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->specialization_archive->count_all(),
                        "recordsFiltered" => $this->specialization_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(9,49);
        $this->load->model('specialization/specialization_archive_model','specialization_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->specialization_archive->restore($id);
           $response = "Specialization successfully restore in Specialization list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(9,49);
        $this->load->model('specialization/specialization_archive_model','specialization_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->specialization_archive->restoreall($post['row_id']);
            $response = "Specialization successfully restore in Specialization list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(9,48);
        $this->load->model('specialization/specialization_archive_model','specialization_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->specialization_archive->trash($id);
           $response = "Specialization successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(9,48);
        $this->load->model('specialization/specialization_archive_model','specialization_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->specialization_archive->trashall($post['row_id']);
            $response = "Specialization successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function specialization_dropdown()
  {
      $specialization_list = $this->specialization->specialization_list();
      $dropdown = '<option value="">Select Specialization</option>'; 
      if(!empty($specialization_list))
      {
        foreach($specialization_list as $specialization)
        {
           $dropdown .= '<option value="'.$specialization->id.'">'.$specialization->specialization.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  
      function check_unique_value($special, $id='') 
      {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->specialization->check_unique_value($users_data['parent_id'], $special,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Specialization already exist.');
            $response = false;
        }
        return $response;
      }
 


}
?>