<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_progress_report_remarks extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_progress_report_remarks/ipd_progress_report_remarks_model','ipd_progress_report_remarks');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('116','713');
        $data['page_title'] = 'Remarks List'; 
        $this->load->view('ipd_progress_report_remarks/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('116','713');
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');

        $list = $this->ipd_progress_report_remarks->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_progress_report_remarks) {
         // print_r($ipd_progress_report_remarks);die;
            $no++;
            $row = array();
            if($ipd_progress_report_remarks->status==1)
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
            if($users_data['parent_id']==$ipd_progress_report_remarks->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_progress_report_remarks->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ipd_progress_report_remarks->remarks;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_progress_report_remarks->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_progress_report_remarks->branch_id)
            {
              if(in_array('715',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_progress_report_remarks('.$ipd_progress_report_remarks->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_progress_report_remarks->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('716',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_progress_report_remarks('.$ipd_progress_report_remarks->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report_remarks->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_remarks->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('116','714');
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
                $this->ipd_progress_report_remarks->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_progress_report_remarks/add',$data);       
    }
     // -> function to find gender according to selected ipd_progress_report_remarks
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_progress_report_remarks_id = $this->input->post('ipd_progress_report_remarks_id');
         $data='';
          if(!empty($ipd_progress_report_remarks_id)){
               $result = $this->general_model->find_gender($ipd_progress_report_remarks_id);
               if(!empty($result))
               {
                  $male = "";
                  $female = ""; 
                  $others=""; 
                  if($result['gender']==1)
                  {
                     $male = 'checked="checked"';
                  } 
                  else if($result['gender']==0)
                  {
                     $female = 'checked="checked"';
                  } 
                  else if($result['gender']==2){
                    $others = 'checked="checked"';
                  }
                  $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
                          <input type="radio" name="gender" '.$female.' value="0" /> Female &nbsp;
                          <input type="radio" name="gender" '.$others.' value="2" /> Others ';
               }
 
          }
          echo $data;
    }
    // -> end:
    public function edit($id="")
    {
     unauthorise_permission('116','715');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_progress_report_remarks->get_by_id($id);  
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
                $this->ipd_progress_report_remarks->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_progress_report_remarks/add',$data);       
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
       unauthorise_permission('116','716');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_progress_report_remarks->delete($id);
           $response = "Remark successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('116','716');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_remarks->deleteall($post['row_id']);
            $response = "Remarks successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_progress_report_remarks->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_progress_report_remarks']." detail";
        $this->load->view('ipd_progress_report_remarks/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('116','717');
        $data['page_title'] = 'Remark Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_progress_report_remarks/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('116','717');
        $this->load->model('ipd_progress_report_remarks/ipd_progress_report_remarks_archive_model','ipd_progress_report_remarks_archive'); 

      
               $list = $this->ipd_progress_report_remarks_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_progress_report_remarks) {
         // print_r($ipd_progress_report_remarks);die;
            $no++;
            $row = array();
            if($ipd_progress_report_remarks->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_progress_report_remarks->id.'">'.$check_script; 
            $row[] = $ipd_progress_report_remarks->remarks;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_progress_report_remarks->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('719',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_progress_report_remarks('.$ipd_progress_report_remarks->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('718',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_progress_report_remarks->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report_remarks_archive->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_remarks_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('116','719');
        $this->load->model('ipd_progress_report_remarks/ipd_progress_report_remarks_archive_model','ipd_progress_report_remarks_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_remarks_archive->restore($id);
           $response = "Remark successfully restore in Remarks list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('116','719');
        $this->load->model('ipd_progress_report_remarks/ipd_progress_report_remarks_archive_model','ipd_progress_report_remarks_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_remarks_archive->restoreall($post['row_id']);
            $response = "Remarks successfully restore in Suggestions list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('116','718');
        $this->load->model('ipd_progress_report_remarks/ipd_progress_report_remarks_archive_model','ipd_progress_report_remarks_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_remarks_archive->trash($id);
           $response = "Remark successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('116','718');
        $this->load->model('ipd_progress_report_remarks/ipd_progress_report_remarks_archive_model','ipd_progress_report_remarks_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_remarks_archive->trashall($post['row_id']);
            $response = "Remarks successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  // public function ipd_progress_report_remarks_dropdown()
  // {
  //    $ipd_progress_report_remarks_list = $this->ipd_progress_report_remarks->ipd_progress_report_remarks_list();
  //    $dropdown = '<option value="">Select ipd_progress_report_remarks</option>'; 
  //    $ipd_progress_report_remarkss_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
  //    if(!empty($ipd_progress_report_remarks_list))
  //    {
  //         foreach($ipd_progress_report_remarks_list as $ipd_progress_report_remarks)
  //         {
  //              if(in_array($ipd_progress_report_remarks->ipd_progress_report_remarks,$ipd_progress_report_remarkss_array)){
  //                   $selected_ipd_progress_report_remarks = 'selected="selected"';
  //              }
  //              else
  //              {
  //                 $selected_ipd_progress_report_remarks = '';  
  //              }
  //              $dropdown .= '<option value="'.$ipd_progress_report_remarks->id.'" '.$selected_ipd_progress_report_remarks.' >'.$ipd_progress_report_remarks->ipd_progress_report_remarks.'</option>';
  //         }
  //    } 
  //    echo $dropdown; 
  // }
  

}
?>