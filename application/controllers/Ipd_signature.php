<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_signature extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_signature/ipd_signature_model','ipd_signature');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission(66,418);
        $data['page_title'] = 'Signature';    
        $this->load->view('ipd_signature/list',$data);
    } 

    public function ajax_list()
    {  
          unauthorise_permission(66,418);
          $users_data = $this->session->userdata('auth_users');
          $list = $this->ipd_signature->get_datatables();  
          $data = array();
          $no = $_POST['start'];
          $i = 1;
          $total_num = count($list);
          foreach ($list as $ipd_signature) {
               // print_r($simulation);die;
               $no++;
               $row = array(); 
               ////////// Check  List /////////////////
               $check_script = "";
               if($i==$total_num){
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
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_signature->id.'">'.$check_script; 
               $sign_img = "";
               if(!empty($ipd_signature->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$ipd_signature->sign_img))
               {
                    $sign_img = ROOT_UPLOADS_PATH.'doctor_signature/'.$ipd_signature->sign_img;
                    $sign_img = '<img src="'.$sign_img.'" width="100px" />';
               }
               $row[] = $ipd_signature->doctor_name; 
               $row[] = $ipd_signature->department; 
               $row[] = $ipd_signature->signature;  
               $row[] = $sign_img;
               $row[] = date('d-M-Y H:i A',strtotime($ipd_signature->created_date)); 
               $btn_edit='';
               $btn_delete='';
               if(in_array('420',$users_data['permission']['action']))
               {
                    $btn_edit= '<a onClick="return edit_ipd_signature('.$ipd_signature->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_signature->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
               if(in_array('421',$users_data['permission']['action']))
               { 
                    $btn_delete = '<a class="btn-custom" onClick="return delete_ipd_signature('.$ipd_signature->id.')" href="javascript:void(0)" title="Delete" data-url="662"><i class="fa fa-trash"></i> Delete</a> '; 
               }  
          $row[] = $btn_edit.$btn_delete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_signature->count_all(),
                        "recordsFiltered" => $this->ipd_signature->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function add_signature()
    { 
        unauthorise_permission(66,419);
        $data['page_title'] = 'Add Signature';   
        $this->load->model('general/general_model'); 
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['doctor_list'] = $this->ipd_signature->doctors_list();
        $data['form_error'] = [];
        $data['sign_error'] = [];
        $post = $this->input->post();
        $data['form_data'] = array(
                                     'data_id'=>'',
                                     'dept_id'=>'',
                                     'doctor_id'=>'',
                                     'signature'=>'',
                                     'old_sign_img'=>''
                                  );

        if(isset($post) && !empty($post))
        {   
             
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
            $this->form_validation->set_rules('dept_id', 'department', 'trim|required'); 
            $this->form_validation->set_rules('doctor_id', 'doctor', 'trim|required'); 
            $this->form_validation->set_rules('signature', 'signature', 'trim|required');  
            if(!isset($_FILES['sign_img']) || empty($_FILES['sign_img']))
            {
               $this->form_validation->set_rules('sign_img', 'sign image', 'trim|required');  
            } 

            if($this->form_validation->run() == TRUE)
            {
                 $config['upload_path']   = DIR_UPLOAD_PATH.'doctor_signature/'; 
                 $config['allowed_types'] = 'jpeg|jpg|png'; 
                 $config['max_size']      = 1000; 
                 $config['encrypt_name'] = TRUE; 
                 $this->load->library('upload', $config);
                 if ($this->upload->do_upload('sign_img')) 
                  {
                    $file_data = $this->upload->data(); 
                    $this->ipd_signature->save($file_data['file_name']);
                    echo 1;
                    return false;
                  } 
                 else
                  { 
                    $data['sign_error'] = $this->upload->display_errors();
                    $data['form_data'] = array(
                                            'data_id'=>$post['data_id'],
                                            'dept_id'=>$post['dept_id'],
                                            'doctor_id'=>$post['doctor_id'], 
                                            'signature'=>$post['signature'],
                                            'old_sign_img'=>$post['old_sign_img']
                                           );
                  }   
            }
            else
            {
                $data['form_data'] = array(
                                            'data_id'=>$post['data_id'],
                                            'dept_id'=>$post['dept_id'],
                                            'doctor_id'=>$post['doctor_id'], 
                                            'signature'=>$post['signature'],
                                            'old_sign_img'=>$post['old_sign_img']
                                           );
                $data['form_error'] = validation_errors();  
            }     

        }
        $this->load->view('ipd_signature/add',$data);
    } 

    public function edit_signature($id="")
    { 

        unauthorise_permission(66,420);
        if(!empty($id) && $id>0)
        {
            $data['page_title'] = 'Edit Signature';   
            $this->load->model('general/general_model'); 
            $data['dept_list'] = $this->general_model->department_list(); 
            $data['doctor_list'] = $this->ipd_signature->doctors_list($id);
            $sign_data = $this->ipd_signature->get_by_id($id);
            $data['form_error'] = [];
            $data['sign_error'] = [];
            $post = $this->input->post();
            $data['form_data'] = array(
                                         'data_id'=>$sign_data['id'],
                                         'dept_id'=>$sign_data['dept_id'],
                                         'doctor_id'=>$sign_data['doctor_id'],
                                         'signature'=>$sign_data['signature'],
                                         'old_sign_img'=>$sign_data['sign_img']
                                      );
            if(isset($post) && !empty($post))
            {   
                $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
                $this->form_validation->set_rules('dept_id', 'department', 'trim|required'); 
                $this->form_validation->set_rules('doctor_id', 'doctor', 'trim|required'); 
                $this->form_validation->set_rules('signature', 'signature', 'trim|required'); 

                if($this->form_validation->run() == TRUE)
                {
                    if(isset($_FILES['sign_img']['name']) && !empty($_FILES['sign_img']['name']))
                    {   

                     if(!empty($post['old_sign_img']) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$post['old_sign_img'])) 
                     {
                        unlink(DIR_UPLOAD_PATH.'doctor_signature/'.$post['old_sign_img']);
                     }  
                     $config['upload_path']   = DIR_UPLOAD_PATH.'doctor_signature/'; 
                     $config['allowed_types'] = 'jpeg|jpg|png'; 
                     $config['max_size']      = 1000; 
                     $config['encrypt_name'] = TRUE; 
                     $this->load->library('upload', $config);
                     if ($this->upload->do_upload('sign_img')) 
                      {
                        $file_data = $this->upload->data(); 
                        $this->ipd_signature->save($file_data['file_name']);
                        echo 1;
                        return false;
                      } 
                     else
                      { 
                        $data['sign_error'] = $this->upload->display_errors();
                        $data['form_data'] = array(
                        'data_id'=>$post['data_id'],
                        'dept_id'=>$post['dept_id'],
                        'doctor_id'=>$post['doctor_id'], 
                        'signature'=>$post['signature'],
                        'old_sign_img'=>$post['old_sign_img']
                        );
                      } 
                    }
                    else
                    {
                        $this->ipd_signature->save($post['old_sign_img']);
                        echo 1;
                        return false;
                    }    
                       
                }
                else
                {
                    $data['form_data'] = array(
                                                'data_id'=>$post['data_id'],
                                                'dept_id'=>$post['dept_id'],
                                                'doctor_id'=>$post['doctor_id'], 
                                                'signature'=>$post['signature'],
                                                'old_sign_img'=>$post['old_sign_img']
                                               );
                    $data['form_error'] = validation_errors();  
                }     

            }
            $this->load->view('ipd_signature/add',$data);
        }
        
    }

 
    public function delete($id="")
    {
        unauthorise_permission(66,421);
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_signature->delete($id);
           $response = "Doctor signature successfully deleted.";
           echo $response;
       }
    }

    function deleteall_sign()
    {
        unauthorise_permission(66,421);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_signature->deleteall($post['row_id']);
            $response = "Doctor signature successfully deleted.";
            echo $response;
        }
    }
     public function archive()
    {
   
        unauthorise_permission(66,422);
        $data['page_title'] = 'Signature Archieve  list';
        $this->load->helper('url');
        $this->load->view('ipd_signature/archive',$data);
    }

    public function archive_ajax_list()
    {

          unauthorise_permission(66,422);
          $users_data = $this->session->userdata('auth_users');
          $this->load->model('ipd_signature/Ipd_signature_archive_model','ipd_signature_archive'); 
          $users_data = $this->session->userdata('auth_users');
          $list = $this->ipd_signature_archive->get_datatables();
          $data = array();
          $no = $_POST['start'];
          $i = 1;
          $total_num = count($list);
          foreach ($list as $ipd_signature_archive) { 
               $no++;
               $row = array();
               if($ipd_signature_archive->status==1){
                    $status = '<font color="green">Active</font>';
               }   
               else{
                    $status = '<font color="red">Inactive</font>';
               } 
               ////////// Check  List /////////////////
               $check_script = "";
               if($i==$total_num){
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
               if($users_data['parent_id']==$ipd_signature_archive->branch_id){
              
                     $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_signature_archive->id.'">'.$check_script; 
                
               }else{
                    $row[]='';
               }
               $sign_img = "";
               if(!empty($ipd_signature_archive->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$ipd_signature_archive->sign_img)){
                    $sign_img = ROOT_UPLOADS_PATH.'doctor_signature/'.$ipd_signature_archive->sign_img;
                    $sign_img = '<img src="'.$sign_img.'" width="100px" />';
               }
               $row[] = $ipd_signature_archive->doctor_name; 
               $row[] = $ipd_signature_archive->department; 
               $row[] = $ipd_signature_archive->signature;  
               $row[] = $sign_img;
               $row[] = date('d-M-Y H:i A',strtotime($ipd_signature_archive->created_date)); 
               $btnrestore='';
               $btndelete='';
               if($users_data['parent_id']==$ipd_signature_archive->branch_id){
                   

                    if(in_array('424',$users_data['permission']['action'])){
                         $btnrestore = ' <a onClick="return restore_ipd_signature('.$ipd_signature_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
                    }
                    if(in_array('423',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_signature_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
                    }
               }
               $row[] = $btnrestore.$btndelete;
               $data[] = $row;

               $i++;
          }
          $output = array(
               "draw" => $_POST['draw'],
               "recordsTotal" => $this->ipd_signature_archive->count_all(),
               "recordsFiltered" => $this->ipd_signature_archive->count_filtered(),
               "data" => $data,
          );
          //output to json format
          echo json_encode($output);
     }

    public function restore($id="")
    {
         unauthorise_permission(66,424);
  $this->load->model('ipd_signature/Ipd_signature_archive_model','ipd_signature_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_signature_archive->restore($id);
           $response = "Signature successfully restore in Signatures list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(66,424);
        $this->load->model('ipd_signature/Ipd_signature_archive_model','ipd_signature_archive');  
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_signature_archive->restoreall($post['row_id']);
            $response = "Signature successfully restore in Signatures list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(66,423);
       $this->load->model('ipd_signature/Ipd_signature_archive_model','ipd_signature_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_signature_archive->trash($id);
           $response = "Signature successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
         unauthorise_permission(66,423);
         $this->load->model('ipd_signature/Ipd_signature_archive_model','ipd_signature_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_signature_archive->trashall($post['row_id']);
            $response = "Signtature successfully deleted parmanently.";
            echo $response;
        }
    }
   
  
  
  
}
?>