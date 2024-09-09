<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice_board_page extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('notice_board_page/notice_board_page_model','notice_board_page');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(219,1241);  
        $data['page_title'] = 'Notice Board List'; 
        $this->load->view('notice_board_page/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(219,1241);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->notice_board_page->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $notice_board_page) {
         // print_r($notice_board_page);die;
            $no++;
            $row = array();
            if($notice_board_page->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($notice_board_page->state))
            {
                $state = " ( ".ucfirst(strtolower($notice_board_page->state))." )";
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
            if($users_data['parent_id']==$notice_board_page->branch_id){ 
                $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$notice_board_page->id.'">'.$check_script; 
            }else{
                $row[]='';
            }
            $row[] = $notice_board_page->msg;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($notice_board_page->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
             if($users_data['parent_id']==$notice_board_page->branch_id){
                if(in_array('1246',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_notice_board_page('.$notice_board_page->id.');" class="btn-custom" href="javascript:void(0)" style="'.$notice_board_page->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                if(in_array('1245',$users_data['permission']['action'])) 
                {
                   $btndelete = ' <a class="btn-custom" onClick="return delete_notice_board_page('.$notice_board_page->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->notice_board_page->count_all(),
                        "recordsFiltered" => $this->notice_board_page->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function open_popup_page()
    {
        unauthorise_permission(219,1240);
        $data['page_title'] = "Add Notice Board";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'message'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            // $data['form_data'] = $this->_validate();
            // if($this->form_validation->run() == TRUE)
            // {
                $this->notice_board_page->save();
                echo 1;
                return false;
                
            // }
            // else
            // {
            //     $data['form_error'] = validation_errors();  
            // }     
        }
       $this->load->view('notice_board_page/add',$data);       
    }
    
    public function open_popup_page_edit($id="")
    {
      unauthorise_permission(219,1246);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->notice_board_page->get_by_id($id); 
        $this->load->model('general/general_model');
         
        $data['page_title'] = "Update Notice Board";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'message'=>$result['msg'],
 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            // $data['form_data'] = $this->_validate();
            // if($this->form_validation->run() == TRUE)
            // {
                $this->notice_board_page->save();
                echo 1;
                return false;
                
            // }
            // else
            // {
            //     $data['form_error'] = validation_errors();  
            // }     
        }
       $this->load->view('notice_board_page/add',$data);       
      }
    }
     
    // private function _validate()
    // {
    //     $post = $this->input->post();    
    //     $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
    //     $this->form_validation->set_rules('emp_type', 'employee type', 'trim|required'); 
        
    //     if ($this->form_validation->run() == FALSE) 
    //     {  
    //         $reg_no = generate_unique_id(2); 
    //         $data['form_data'] = array(
    //                                     'data_id'=>$post['data_id'],
    //                                     'emp_type'=>$post['emp_type'], 
    //                                     'status'=>$post['status']
    //                                    ); 
    //         return $data['form_data'];
    //     }   
    // }
 
    public function delete($id="")
    {
       unauthorise_permission(219,1245);
       if(!empty($id) && $id>0)
       {
           $result = $this->notice_board_page->delete($id);
           $response = "Notice Board successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(219,1245);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->notice_board_page->deleteall($post['row_id']);
            $response = "Notice Board successfully deleted.";
            echo $response;
        }
    }

     


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(219,1244);
        $data['page_title'] = 'Notice Board Archive List';
        $this->load->helper('url');
        $this->load->view('notice_board_page/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(219,1244);
        $this->load->model('notice_board_page/notice_board_page_archive_model','notice_board_page_archive'); 
        $users_data = $this->session->userdata('auth_users');
        

               $list = $this->notice_board_page_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $notice_board_page) {
         // print_r($notice_board_page);die;
            $no++;
            $row = array();
            if($notice_board_page->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($notice_board_page->state))
            {
                $state = " ( ".ucfirst(strtolower($notice_board_page->state))." )";
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$notice_board_page->id.'">'.$check_script; 
            $row[] = $notice_board_page->msg;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($notice_board_page->created_date)); 
            
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if(in_array('1242',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_notice_board_page('.$notice_board_page->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            if(in_array('1243',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$notice_board_page->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //


      $row[] = $btn_restore.$btn_delete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->notice_board_page_archive->count_all(),
                        "recordsFiltered" => $this->notice_board_page_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(219,1242);
        $this->load->model('notice_board_page/notice_board_page_archive_model','notice_board_page_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->notice_board_page_archive->restore($id);
           $response = "Notice Board successfully restore in Notice Board list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(219,1242);
        $this->load->model('notice_board_page/notice_board_page_archive_model','notice_board_page_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->notice_board_page_archive->restoreall($post['row_id']);
            $response = "Notice Board successfully restore in Notice Board list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(219,1243);
        $this->load->model('notice_board_page/notice_board_page_archive_model','notice_board_page_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->notice_board_page_archive->trash($id);
           $response = "Notice Board successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(219,1243);
        $this->load->model('notice_board_page/notice_board_page_archive_model','notice_board_page_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->notice_board_page_archive->trashall($post['row_id']);
            $response = "Notice Board successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function notice_board_page_dropdown()
  {
      $notice_board_page_list = $this->notice_board_page->notice_board_page_list();
      $dropdown = '<option value="">Select employee type</option>'; 
      if(!empty($notice_board_page_list))
      {
        foreach($notice_board_page_list as $notice_board_page)
        {
           $dropdown .= '<option value="'.$notice_board_page->id.'">'.$notice_board_page->emp_type.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>
