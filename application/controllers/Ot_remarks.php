<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_remarks extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_remarks/ot_remarks_model','ot_remarks');
        $this->load->library('form_validation');
    }

    public function index()
    { 
         unauthorise_permission('132','793');
        $data['page_title'] = 'Remarks List'; 
        $this->load->view('ot_remarks/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('132','793');
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');

        $list = $this->ot_remarks->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $otremarks) {
         // print_r($ipd_progress_report_remarks);die;
            $no++;
            $row = array();
            if($otremarks->status==1)
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
            if($users_data['parent_id']==$otremarks->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$otremarks->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $otremarks->remarks;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($otremarks->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$otremarks->branch_id)
            {
              if(in_array('795',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ot_remarks('.$otremarks->id.');" class="btn-custom" href="javascript:void(0)" style="'.$otremarks->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('796',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ot_remarks('.$otremarks->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_remarks->count_all(),
                        "recordsFiltered" => $this->ot_remarks->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
         unauthorise_permission('132','794');
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
                $this->ot_remarks->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ot_remarks/add',$data);       
    }
    
    // -> end:
    public function edit($id="")
    {
      unauthorise_permission('132','795');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ot_remarks->get_by_id($id);  
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
                $this->ot_remarks->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ot_remarks/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('remarks', 'remark', 'trim|required'); 
        
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
        unauthorise_permission('132','796');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ot_remarks->delete($id);
           $response = "Remark successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
         unauthorise_permission('132','796');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_remarks->deleteall($post['row_id']);
            $response = "Remarks successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ot_remarks->get_by_id($id);  
        $data['page_title'] = $data['form_data']['remarks']." detail";
        $this->load->view('ot_remarks/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
         unauthorise_permission('132','797');
        $data['page_title'] = 'OT Remark Archive List';
        $this->load->helper('url');
        $this->load->view('ot_remarks/archive',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission('132','797');
        $this->load->model('ot_remarks/ot_remarks_archive_model','ot_remarks_archive'); 
        $list = $this->ot_remarks_archive->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot_remarksarchive) {
         // print_r($ipd_progress_report_remarks);die;
            $no++;
            $row = array();
            if($ot_remarksarchive->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot_remarksarchive->id.'">'.$check_script; 
            $row[] = $ot_remarksarchive->remarks;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ot_remarksarchive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('799',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ot_remarks('.$ot_remarksarchive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('798',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ot_remarksarchive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_remarks_archive->count_all(),
                        "recordsFiltered" => $this->ot_remarks_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission('132','799');
        $this->load->model('ot_remarks/ot_remarks_archive_model','ot_remarksarchive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_remarksarchive->restore($id);
           $response = "Remark successfully restore in Remarks list.";
           echo $response;
       }
    }

    function restoreall()
    { 
         unauthorise_permission('132','799');
        $this->load->model('ot_remarks/ot_remarks_archive_model','ot_remarksarchive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_remarksarchive->restoreall($post['row_id']);
            $response = "Remarks successfully restore in Suggestions list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         unauthorise_permission('132','798');
        $this->load->model('ot_remarks/ot_remarks_archive_model','ot_remarksarchive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_remarksarchive->trash($id);
           $response = "Remark successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
         unauthorise_permission('132','798');
        $this->load->model('ot_remarks/ot_remarks_archive_model','ot_remarksarchive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_remarksarchive->trashall($post['row_id']);
            $response = "Remarks successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////


}
?>