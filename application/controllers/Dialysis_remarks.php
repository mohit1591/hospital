<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_remarks extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dialysis_remarks/dialysis_remarks_model','dialysis_remarks');
        $this->load->library('form_validation');
    }

    public function index()
    { 
         unauthorise_permission('205','1162');
        $data['page_title'] = 'Dialysis Remarks List'; 
        $this->load->view('dialysis_remarks/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('205','1162');
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');

        $list = $this->dialysis_remarks->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $tdialysisal_num = count($list);
        foreach ($list as $dialysisremarks) {
         // print_r($ipd_progress_report_remarks);die;
            $no++;
            $row = array();
            if($dialysisremarks->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$tdialysisal_num)
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
            if($users_data['parent_id']==$dialysisremarks->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysisremarks->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $dialysisremarks->remarks;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dialysisremarks->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$dialysisremarks->branch_id)
            {
              if(in_array('1164',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_dialysis_remarks('.$dialysisremarks->id.');" class="btn-custom" href="javascript:void(0)" style="'.$dialysisremarks->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('1165',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_dialysis_remarks('.$dialysisremarks->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTdialysisal" => $this->dialysis_remarks->count_all(),
                        "recordsFiltered" => $this->dialysis_remarks->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
         unauthorise_permission('205','1163');
        $data['page_title'] = "Add Remarks";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'remarks'=>"",
                                  'status'=>"1",
                                 
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dialysis_remarks->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dialysis_remarks/add',$data);       
    }
    
    // -> end:
    public function edit($id="")
    {
      unauthorise_permission('205','1164');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->dialysis_remarks->get_by_id($id);  
        $data['page_title'] = "Update Remarks";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'remarks'=>$result['remarks'], 
                                  'status'=>$result['status'],
                                 
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dialysis_remarks->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dialysis_remarks/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('remarks', 'remarks', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'remarks'=>$post['remarks'], 
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('205','1165');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->dialysis_remarks->delete($id);
           $response = "Remarks successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('205','1165');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_remarks->deleteall($post['row_id']);
            $response = "Remarks successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->dialysis_remarks->get_by_id($id);  
        $data['page_title'] = $data['form_data']['remarks']." detail";
        $this->load->view('dialysis_remarks/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
         unauthorise_permission('205','1166');
        $data['page_title'] = 'dialysis Remark Archive List';
        $this->load->helper('url');
        $this->load->view('dialysis_remarks/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('205','1166');
        $this->load->model('dialysis_remarks/dialysis_remarks_archive_model','dialysis_remarks_archive'); 
        $list = $this->dialysis_remarks_archive->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $tdialysisal_num = count($list);
        foreach ($list as $dialysis_remarksarchive) {
         // print_r($ipd_progress_report_remarks);die;
            $no++;
            $row = array();
            if($dialysis_remarksarchive->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$tdialysisal_num)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysis_remarksarchive->id.'">'.$check_script; 
            $row[] = $dialysis_remarksarchive->remarks;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dialysis_remarksarchive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1168',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_dialysis_remarks('.$dialysis_remarksarchive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1167',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$dialysis_remarksarchive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTdialysisal" => $this->dialysis_remarks_archive->count_all(),
                        "recordsFiltered" => $this->dialysis_remarks_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('205','1168');
        $this->load->model('dialysis_remarks/dialysis_remarks_archive_model','dialysis_remarksarchive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_remarksarchive->restore($id);
           $response = "Remarks successfully restore in Remarks list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('205','1168');
        $this->load->model('dialysis_remarks/dialysis_remarks_archive_model','dialysis_remarksarchive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_remarksarchive->restoreall($post['row_id']);
            $response = "Remarks successfully restore in Suggestions list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         unauthorise_permission('205','1167');
        $this->load->model('dialysis_remarks/dialysis_remarks_archive_model','dialysis_remarksarchive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_remarksarchive->trash($id);
           $response = "Remarks successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('205','1167');
        $this->load->model('dialysis_remarks/dialysis_remarks_archive_model','dialysis_remarksarchive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_remarksarchive->trashall($post['row_id']);
            $response = "Remarks successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////


}
?>