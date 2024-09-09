<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_complication extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/post_complication/post_complication_model','complication');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('297','1765');
        $data['page_title'] = 'Post Complication List'; 
        $this->load->view('blood_bank/post_complication/list',$data);
    }

    public function ajax_list()
    { 
         unauthorise_permission('297','1765');
      
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->complication->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
       
        $total_num = count($list);
        foreach ($list as $complication_list) {
         // print_r($simulation);die;
            $no++;
            $row = array();
              if($complication_list->status==1)
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
            $checkboxs = "";
            if($users_data['parent_id']==$complication_list->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$complication_list->id.'">'.$check_script;
            }
            else
            {
               $row[]='';
            }
            
            $row[] = $complication_list->post_name;
            $row[] = $status;
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$complication_list->branch_id)
            {
             if(in_array('1763',$users_data['permission']['action'])){
              $btnedit =' <a onClick="return edit_post('.$complication_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$complication_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
            }
            if(in_array('1762',$users_data['permission']['action'])){
                   $btndelete = ' <a class="btn-custom" onClick="return delete_post('.$complication_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';  
                  } 
             
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

         $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->complication->count_all(),
                        "recordsFiltered" => $this->complication->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {

       unauthorise_permission('297','1764');
        $data['page_title'] = "Add Post Complication";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"",
                                  'post_name'=>"",
                                  'status'=>"1",
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();

            if($this->form_validation->run() == TRUE)
            {
               $this->complication->save();
                 echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  

                //print_r($data['form_error']);die;
            }     
        }
       $this->load->view('blood_bank/post_complication/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('297','1763');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->complication->get_by_id($id);
        $field_list = $this->complication->get_by_id($id);  
        $data['page_title'] = "Update Post Complication";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'post_name'=>$result['post_name'],
                                  'status'=>$result['status'], 
                                 ); 
       
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $response = $this->complication->save();
                 echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/post_complication/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('post_name', 'post name', 'trim|required');
       
         if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'post_name'=>$post['post_name'],
                                        'status'=>$post['status']
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('297','1762');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->complication->delete($id);
           $response = "Post complication successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('297','1762');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->complication->deleteall($post['row_id']);
            $response = "Post complication  successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->complication->get_by_id($id);  
        $data['page_title'] = $data['form_data']['vitals_name']." detail";
        $this->load->view('blood_bank/post_complication/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('297','1761');
        $data['page_title'] = 'Post Complication Archive List';
        $this->load->helper('url');
        $this->load->view('blood_bank/post_complication/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('297','1761');
        $this->load->model('blood_bank/post_complication/Post_complication_archive_model','complication_archive_model'); 

      
      $list = $this->complication_archive_model->get_datatables();
                 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $complication_list) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($complication_list->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$complication_list->id.'">'.$check_script; 
            
            $row[] = $complication_list->post_name;
            $row[] = $status;//date('d-M-Y H:i A',strtotime($vitals_list->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
           if(in_array('1758',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_post('.$complication_list->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
             if(in_array('1760',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$complication_list->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->complication_archive_model->count_all(),
                        "recordsFiltered" => $this->complication_archive_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission('297','1758');
       $this->load->model('blood_bank/post_complication/Post_complication_archive_model','complication_archive_model');
       if(!empty($id) && $id>0)
       {
           $result = $this->complication_archive_model->restore($id);
           $response = "Post Complication successfully restore in Post Complication list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('297','1758');
         $this->load->model('blood_bank/post_complication/Post_complication_archive_model','complication_archive_model');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->complication_archive_model->restoreall($post['row_id']);
            $response = "Post Complication successfully restore in Post Complication list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       unauthorise_permission('297','1760');
         $this->load->model('blood_bank/post_complication/Post_complication_archive_model','complication_archive_model');
       if(!empty($id) && $id>0)
       {
           $result = $this->complication_archive_model->trash($id);
           $response = "Post Complication successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('297','1760');
       $this->load->model('blood_bank/post_complication/Post_complication_archive_model','complication_archive_model');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->complication_archive_model->trashall($post['row_id']);
            $response = "Post Complication successfully deleted parmanently.";
            echo $response;
        }
    }


}
?>