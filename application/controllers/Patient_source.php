<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_source extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('patient_source/patient_source_model','patient_source','Employee_archive_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(152,916);  
        $data['page_title'] = 'Patient Source List'; 
        $this->load->view('patient_source/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(152,916);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->patient_source->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $patient_source) {
         // print_r($patient_source);die;
            $no++;
            $row = array();
            if($patient_source->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($patient_source->state))
            {
                $state = " ( ".ucfirst(strtolower($patient_source->state))." )";
            }
            //////////////////////// 
            
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
            if($users_data['parent_id']==$patient_source->branch_id){ 
                $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$patient_source->id.'">'.$check_script; 
            }else{
                $row[]='';
            }
            $row[] = $patient_source->source;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($patient_source->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
             if($users_data['parent_id']==$patient_source->branch_id){
                if(in_array('921',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_patient_source('.$patient_source->id.');" class="btn-custom" href="javascript:void(0)" style="'.$patient_source->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                if(in_array('920',$users_data['permission']['action'])) 
                {
                   $btndelete = ' <a class="btn-custom" onClick="return delete_emp_type('.$patient_source->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->patient_source->count_all(),
                        "recordsFiltered" => $this->patient_source->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {


        unauthorise_permission(151,921);
        $data['page_title'] = "Add Patient Source";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_source'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->patient_source->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       // print_r( $data['form_error']);die;
       $this->load->view('patient_source/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission(152,921);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->patient_source->get_by_id($id); 
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->patient_source->patient_source_list();  
        $data['page_title'] = "Update Employee";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'patient_source'=>$result['source'],
 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->patient_source->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('patient_source/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('patient_source', 'Patient Source', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  

            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_source'=>$post['patient_source'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(152,920);
       if(!empty($id) && $id>0)
       {
           $result = $this->patient_source->delete($id);
           $response = "Patient Source successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(152,920);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->patient_source->deleteall($post['row_id']);
            $response = "Patient Sources successfully deleted.";
            echo $response;
        }
    }

     


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(152,919);
        $data['page_title'] = 'Patient Source Archive List';
        $this->load->helper('url');
        $this->load->view('patient_source/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(152,919);
        $this->load->model('patient_source/patient_source_archive_model','patient_source_archive'); 
        $users_data = $this->session->userdata('auth_users');
        

               $list = $this->patient_source_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $patient_source) {
         // print_r($patient_source);die;
            $no++;
            $row = array();
            if($patient_source->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($patient_source->state))
            {
                $state = " ( ".ucfirst(strtolower($patient_source->state))." )";
            }
            //////////////////////// 
            
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$patient_source->id.'">'.$check_script; 
            $row[] = $patient_source->source;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($patient_source->created_date)); 
            
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if(in_array('917',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_patient_source('.$patient_source->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            if(in_array('918',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$patient_source->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //


      $row[] = $btn_restore.$btn_delete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->patient_source_archive->count_all(),
                        "recordsFiltered" => $this->patient_source_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(152,920);
        $this->load->model('patient_source/patient_source_archive_model','patient_source_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->patient_source_archive->restore($id);
           $response = "Patient Source successfully restore in Patient Source list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(152,920);
        $this->load->model('patient_source/patient_source_archive_model','patient_source_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->patient_source_archive->restoreall($post['row_id']);
            $response = "Patient Sources successfully restore in Patient Source list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(152,918);
        $this->load->model('patient_source/patient_source_archive_model','patient_source_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->patient_source_archive->trash($id);
           $response = "Patient Source successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(152,918);
        $this->load->model('patient_source/patient_source_archive_model','patient_source_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->patient_source_archive->trashall($post['row_id']);
            $response = "Patient Source successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function patient_source_dropdown()
  {
      $patient_source_list = $this->patient_source->patient_source_list();
      $dropdown = '<option value="">Select Patient Source</option>'; 
      if(!empty($patient_source_list))
      {
        foreach($patient_source_list as $patient_source)
        {
           $dropdown .= '<option value="'.$patient_source->id.'">'.$patient_source->source.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>