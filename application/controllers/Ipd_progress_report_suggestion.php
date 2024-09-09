<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ipd_progress_report_suggestion extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_progress_report_suggestion/ipd_progress_report_suggestion_model','ipd_progress_report_suggestion');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('115','706');
        $data['page_title'] = 'Suggestion List'; 
        $this->load->view('ipd_progress_report_suggestion/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('115','706');
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');

        $list = $this->ipd_progress_report_suggestion->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_progress_report_suggestion) {
         // print_r($ipd_progress_report_suggestion);die;
            $no++;
            $row = array();
            if($ipd_progress_report_suggestion->status==1)
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
            if($users_data['parent_id']==$ipd_progress_report_suggestion->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_progress_report_suggestion->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ipd_progress_report_suggestion->suggestion;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_progress_report_suggestion->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_progress_report_suggestion->branch_id)
            {
              if(in_array('708',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_progress_report_suggestion('.$ipd_progress_report_suggestion->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_progress_report_suggestion->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('709',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_progress_report_suggestion('.$ipd_progress_report_suggestion->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report_suggestion->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_suggestion->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('115','707');
        $data['page_title'] = "Add Suggestion";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'suggestion'=>"",
                                  'status'=>"1",
                                 
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_progress_report_suggestion->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_progress_report_suggestion/add',$data);       
    }
     // -> function to find gender according to selected ipd_progress_report_suggestion
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_progress_report_suggestion_id = $this->input->post('ipd_progress_report_suggestion_id');
         $data='';
          if(!empty($ipd_progress_report_suggestion_id)){
               $result = $this->general_model->find_gender($ipd_progress_report_suggestion_id);
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
     unauthorise_permission('115','708');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_progress_report_suggestion->get_by_id($id);  
        $data['page_title'] = "Update Suggestion";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'suggestion'=>$result['suggestion'], 
                                  'status'=>$result['status'],
                                 
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_progress_report_suggestion->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_progress_report_suggestion/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('suggestion', 'suggestion', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'suggestion'=>$post['suggestion'], 
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('115','709');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_progress_report_suggestion->delete($id);
           $response = "Suggestion successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('115','709');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_suggestion->deleteall($post['row_id']);
            $response = "Suggestions successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_progress_report_suggestion->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_progress_report_suggestion']." detail";
        $this->load->view('ipd_progress_report_suggestion/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('115','710');
        $data['page_title'] = 'Suggestion Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_progress_report_suggestion/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('115','710');
        $this->load->model('ipd_progress_report_suggestion/ipd_progress_report_suggestion_archive_model','ipd_progress_report_suggestion_archive'); 

      
               $list = $this->ipd_progress_report_suggestion_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_progress_report_suggestion) {
         // print_r($ipd_progress_report_suggestion);die;
            $no++;
            $row = array();
            if($ipd_progress_report_suggestion->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_progress_report_suggestion->id.'">'.$check_script; 
            $row[] = $ipd_progress_report_suggestion->suggestion;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_progress_report_suggestion->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('712',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_progress_report_suggestion('.$ipd_progress_report_suggestion->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('711',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_progress_report_suggestion->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report_suggestion_archive->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_suggestion_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('115','712');
        $this->load->model('ipd_progress_report_suggestion/ipd_progress_report_suggestion_archive_model','ipd_progress_report_suggestion_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_suggestion_archive->restore($id);
           $response = "Suggestion successfully restore in Suggestion list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('115','712');
        $this->load->model('ipd_progress_report_suggestion/ipd_progress_report_suggestion_archive_model','ipd_progress_report_suggestion_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_suggestion_archive->restoreall($post['row_id']);
            $response = "Suggestions successfully restore in Suggestions list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('115','711');
        $this->load->model('ipd_progress_report_suggestion/ipd_progress_report_suggestion_archive_model','ipd_progress_report_suggestion_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_suggestion_archive->trash($id);
           $response = "Suggestion successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('115','711');
        $this->load->model('ipd_progress_report_suggestion/ipd_progress_report_suggestion_archive_model','ipd_progress_report_suggestion_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_suggestion_archive->trashall($post['row_id']);
            $response = "Suggestions successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  // public function ipd_progress_report_suggestion_dropdown()
  // {
  //    $ipd_progress_report_suggestion_list = $this->ipd_progress_report_suggestion->ipd_progress_report_suggestion_list();
  //    $dropdown = '<option value="">Select ipd_progress_report_suggestion</option>'; 
  //    $ipd_progress_report_suggestions_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
  //    if(!empty($ipd_progress_report_suggestion_list))
  //    {
  //         foreach($ipd_progress_report_suggestion_list as $ipd_progress_report_suggestion)
  //         {
  //              if(in_array($ipd_progress_report_suggestion->ipd_progress_report_suggestion,$ipd_progress_report_suggestions_array)){
  //                   $selected_ipd_progress_report_suggestion = 'selected="selected"';
  //              }
  //              else
  //              {
  //                 $selected_ipd_progress_report_suggestion = '';  
  //              }
  //              $dropdown .= '<option value="'.$ipd_progress_report_suggestion->id.'" '.$selected_ipd_progress_report_suggestion.' >'.$ipd_progress_report_suggestion->ipd_progress_report_suggestion.'</option>';
  //         }
  //    } 
  //    echo $dropdown; 
  // }
  

}
?>