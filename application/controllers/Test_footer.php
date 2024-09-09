<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_footer extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_master/test_footer_model','test_footer');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission(148,895);
        $data['page_title'] = 'Technician Signature';    
        $this->load->view('test_master/list_footer',$data);
    } 

    public function footer_ajax_list()
    {  
            unauthorise_permission(148,895);
           $users_data = $this->session->userdata('auth_users');
        
            $list = $this->test_footer->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $footer) {
         // print_r($simulation);die;
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
             if($users_data['parent_id']==$footer->branch_id){
                
                        $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$footer->id.'">'.$check_script;
                    
               }else{
                    $row[]='';
               } 
            $sign_img = "";
            if(!empty($footer->sign_img) && file_exists(DIR_UPLOAD_PATH.'technician_signature/'.$footer->sign_img))
            {
                $sign_img = ROOT_UPLOADS_PATH.'technician_signature/'.$footer->sign_img;
                $sign_img = '<img src="'.$sign_img.'" width="100px" />';
            }
            //$row[] = $footer->doctor_name;
            $row[] = $footer->name;  
            $row[] = $footer->department; 
            $row[] = $footer->signature;  
            $row[] = $sign_img;
            //$row[] = date('d-M-Y H:i A',strtotime($footer->created_date)); 
 
        $btnedit='';
        $btndelete='';
         if($users_data['parent_id']==$footer->branch_id ){
             if(in_array('897',$users_data['permission']['action'])){ 
                $btnedit = '<a onClick="return edit_test_footer('.$footer->id.');" class="btn-custom" href="javascript:void(0)" style="'.$footer->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
              if(in_array('898',$users_data['permission']['action'])){ 
                $btndelete = '<a class="btn-custom" onClick="return delete_test_footer('.$footer->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
             }
     }
     $row[] = $btnedit.$btndelete;

        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_footer->count_all(),
                        "recordsFiltered" => $this->test_footer->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function add_footer()
    { 
          unauthorise_permission(148,896);
        $data['page_title'] = 'Add Signature';   
        $this->load->model('general/general_model'); 
        //$data['dept_list'] = $this->general_model->department_list(5); 
        $data['dept_list'] = $this->general_model->active_department_list(5);
        $data['doctor_list'] = $this->test_footer->doctors_list();
        $data['employee_list'] = $this->general_model->branch_user_list();
        $data['form_error'] = [];
        $data['sign_error'] = [];
        $post = $this->input->post();
        $data['form_data'] = array(
                                     'data_id'=>'',
                                     'dept_id'=>'',
                                     //'doctor_id'=>'',
                                    'employee_id'=>'', 
                                     'signature'=>'',
                                     'old_sign_img'=>''
                                  );
        if(isset($post) && !empty($post))
        {   
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
            $this->form_validation->set_rules('dept_id', 'department', 'trim|required'); 
            $this->form_validation->set_rules('employee_id', 'Employee Name', 'trim|required');
            //$this->form_validation->set_rules('doctor_id', 'doctor', 'trim|required'); 
            //$this->form_validation->set_rules('signature', 'signature', 'trim|required');  
            /*if(!isset($_FILES['sign_img']) || empty($_FILES['sign_img']))
            {
               $this->form_validation->set_rules('sign_img', 'sign image', 'trim|required');  
            }*/ 

            if($this->form_validation->run() == TRUE)
            { 
              if(!empty($_FILES['sign_img']['name']))
              { 
                 $config['upload_path']   = DIR_UPLOAD_PATH.'technician_signature/'; 
                 $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                 $config['max_size']      = 1000; 
                 $config['encrypt_name'] = TRUE; 
                 $this->load->library('upload', $config);
                 if($this->upload->do_upload('sign_img')) 
                  {
                    $file_data = $this->upload->data(); 
                    $this->test_footer->footer_save($file_data['file_name']);
                    echo 1;
                    return false;
                  } 
                 else
                  { 
                    $data['sign_error'] = $this->upload->display_errors();
                    $data['form_data'] = array(
                                            'data_id'=>$post['data_id'],
                                            'dept_id'=>$post['dept_id'],
                                            //'doctor_id'=>$post['doctor_id'],
                                            'employee_id'=>$post['employee_id'],  
                                            'signature'=>$post['signature'],
                                            'old_sign_img'=>$post['old_sign_img']
                                           );
                  }  
              }
              else
              {
                $this->test_footer->footer_save();
                echo 1;
                return false;
              }    
            }
            else
            {
                $data['form_data'] = array(
                                            'data_id'=>$post['data_id'],
                                            'dept_id'=>$post['dept_id'],
                                            //'doctor_id'=>$post['doctor_id'], 
                                            'employee_id'=>$post['employee_id'],
                                            'signature'=>$post['signature'],
                                            'old_sign_img'=>$post['old_sign_img']
                                           );
                $data['form_error'] = validation_errors();  
            }     

        }
        $this->load->view('test_master/add_footer',$data);
    } 

    public function edit_footer($id="")
    { 
          unauthorise_permission(148,897);
        if(!empty($id) && $id>0)
        {
            $data['page_title'] = 'Edit Signature';   
            $this->load->model('general/general_model'); 
            //$data['dept_list'] = $this->general_model->department_list(5); 
            $data['dept_list'] = $this->general_model->active_department_list(5);
            $data['doctor_list'] = $this->test_footer->doctors_list($id);
            $sign_data = $this->test_footer->footer_get_by_id($id);
            $data['employee_list'] = $this->general_model->branch_user_list();
            $data['form_error'] = [];
            $data['sign_error'] = [];
            $post = $this->input->post();
            $data['form_data'] = array(
                                         'data_id'=>$sign_data['id'],
                                         'dept_id'=>$sign_data['dept_id'],
                                         //'doctor_id'=>$sign_data['doctor_id'],
                                         'employee_id'=>$sign_data['employee_id'], 
                                         'signature'=>$sign_data['signature'],
                                         'old_sign_img'=>$sign_data['sign_img']
                                      );
            if(isset($post) && !empty($post))
            {   
                $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
                $this->form_validation->set_rules('dept_id', 'department', 'trim|required');
                $this->form_validation->set_rules('employee_id', 'Employee Name', 'trim|required'); 
                //$this->form_validation->set_rules('doctor_id', 'doctor', 'trim|required'); 
                //$this->form_validation->set_rules('signature', 'signature', 'trim|required'); 

                if($this->form_validation->run() == TRUE)
                {
                    if(isset($_FILES['sign_img']['name']) && !empty($_FILES['sign_img']['name']))
                    {   

                     if(!empty($post['old_sign_img']) && file_exists(DIR_UPLOAD_PATH.'technician_signature/'.$post['old_sign_img'])) 
                     {
                        unlink(DIR_UPLOAD_PATH.'technician_signature/'.$post['old_sign_img']);
                     }  
                     $config['upload_path']   = DIR_UPLOAD_PATH.'technician_signature/'; 
                     $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                     $config['max_size']      = 1000; 
                     $config['encrypt_name'] = TRUE; 
                     $this->load->library('upload', $config);
                     if ($this->upload->do_upload('sign_img')) 
                      {
                        $file_data = $this->upload->data(); 
                        $this->test_footer->footer_save($file_data['file_name']);
                        echo 1;
                        return false;
                      } 
                     else
                      { 
                        $data['sign_error'] = $this->upload->display_errors();
                        $data['form_data'] = array(
                        'data_id'=>$post['data_id'],
                        'dept_id'=>$post['dept_id'],
                        //'doctor_id'=>$post['doctor_id'],
                        'employee_id'=>$post['employee_id'],  
                        'signature'=>$post['signature'],
                        'old_sign_img'=>$post['old_sign_img']
                        );
                      } 
                    }
                    else
                    {
                        $this->test_footer->footer_save($post['old_sign_img']);
                        echo 1;
                        return false;
                    }    
                       
                }
                else
                {
                    $data['form_data'] = array(
                                                'data_id'=>$post['data_id'],
                                                'dept_id'=>$post['dept_id'],
                                                'employee_id'=>$post['employee_id'], 
                                                //'doctor_id'=>$post['doctor_id'], 
                                                'signature'=>$post['signature'],
                                                'old_sign_img'=>$post['old_sign_img']
                                               );
                    $data['form_error'] = validation_errors();  
                }     

            }
            $this->load->view('test_master/add_footer',$data);
        }
        
    }

     ///// employee Archive Start  ///////////////
    public function archive_footer()
    {
        unauthorise_permission(148,899);
        $data['page_title'] = 'Technician Signature Archieve List';
        $this->load->helper('url');
        $this->load->view('test_master/archive_footer',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission(148,899);
        $this->load->model('test_master/Test_footer_archieve_model','test_footer_archieve'); 
        $users_data = $this->session->userdata('auth_users');
     

            $list = $this->test_footer_archieve->get_datatables();
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $footer) { 
            $no++;
            $row = array();
            if($footer->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
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
            if($users_data['parent_id']==$footer->branch_id){
              
                     $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$footer->id.'">'.$check_script; 
                
            }else{
               $row[]='';
            }
                 $sign_img = "";
            if(!empty($footer->sign_img) && file_exists(DIR_UPLOAD_PATH.'technician_signature/'.$footer->sign_img))
            {
                $sign_img = ROOT_UPLOADS_PATH.'technician_signature/'.$footer->sign_img;
                $sign_img = '<img src="'.$sign_img.'" width="100px" />';
            }
            //$row[] = $footer->doctor_name;
            $row[] = $footer->name;  
            $row[] = $footer->department; 
            $row[] = $footer->signature;  
            $row[] = $sign_img;
            //$row[] = date('d-M-Y H:i A',strtotime($footer->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if($users_data['parent_id']==$footer->branch_id){
               if(in_array('900',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_test_footer('.$footer->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
               if(in_array('901',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$footer->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_footer_archieve->count_all(),
                        "recordsFiltered" => $this->test_footer_archieve->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission(148,900);
 $this->load->model('test_master/Test_footer_archieve_model','test_footer_archieve'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->test_footer_archieve->restore($id);
           $response = "Technician Signature successfully restore in Technician Signature list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(148,900);
         $this->load->model('test_master/Test_footer_archieve_model','test_footer_archieve'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_footer_archieve->restoreall($post['row_id']);
            $response = "Technician Signature successfully restore in Technician Signature list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(148,901);
        $this->load->model('test_master/Test_footer_archieve_model','test_footer_archieve'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->test_footer_archieve->trash($id);
           $response = "Technician Signature successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
         unauthorise_permission(148,901);
         $this->load->model('test_master/Test_footer_archieve_model','test_footer_archieve'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_footer_archieve->trashall($post['row_id']);
            $response = "Technician Signature successfully deleted parmanently.";
            echo $response;
        }
    }
    /// employee Archive end  ///////////////
    public function delete_sign($id="")
    {
       if(!empty($id) && $id>0)
       {
           $result = $this->test_footer->delete($id);
           $response = "Doctor Signature successfully deleted.";
           echo $response;
       }
    }

    function deleteall_sign()
    {
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_footer->deleteall($post['row_id']);
            $response = "Doctor Signature successfully deleted.";
            echo $response;
        }
    }
  
  
}
?>