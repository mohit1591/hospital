<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_master extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('bank_master/bank_master_model','bank_master');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('94','598');
        $data['page_title'] = 'Banks List'; 
        $this->load->view('bank_master/list',$data);
    }

    public function ajax_list()
    { 
         unauthorise_permission('94','598');
         $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
     
            $list = $this->bank_master->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $bank_master) {
         // print_r($bank_master);die;
            $no++;
            $row = array();
            if($bank_master->status==1)
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
            if($users_data['parent_id']==$bank_master->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bank_master->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $bank_master->bank_name;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($bank_master->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$bank_master->branch_id)
            {
              // if(in_array('66',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_bank_master('.$bank_master->id.');" class="btn-custom" href="javascript:void(0)" style="'.$bank_master->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              // }
              // if(in_array('67',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_bank_master('.$bank_master->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               // }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bank_master->count_all(),
                        "recordsFiltered" => $this->bank_master->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
         unauthorise_permission('94','599');
        $data['page_title'] = "Add bank master";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'bank_name'=>"",
                                  'status'=>"1",
                                 
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->bank_master->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('bank_master/add',$data);       
    }
     // -> function to find gender according to selected bank_master
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    // public function find_gender(){
    //      $this->load->model('general/general_model'); 
    //      $bank_master_id = $this->input->post('bank_master_id');
    //      $data='';
    //       if(!empty($bank_master_id)){
    //            $result = $this->general_model->find_gender($bank_master_id);
    //            if(!empty($result))
    //            {
    //               $male = "";
    //               $female = ""; 
    //               $others=""; 
    //               if($result['gender']==1)
    //               {
    //                  $male = 'checked="checked"';
    //               } 
    //               else if($result['gender']==0)
    //               {
    //                  $female = 'checked="checked"';
    //               } 
    //               else if($result['gender']==2){
    //                 $others = 'checked="checked"';
    //               }
    //               $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
    //                       <input type="radio" name="gender" '.$female.' value="0" /> Female &nbsp;
    //                       <input type="radio" name="gender" '.$others.' value="2" /> Others ';
    //            }
 
    //       }
    //       echo $data;
    // }
    // -> end:
    public function edit($id="")
    {
      unauthorise_permission('94','600');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->bank_master->get_by_id($id);  
        $data['page_title'] = "Update Bank";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'bank_name'=>$result['bank_name'], 
                                  'status'=>$result['status'],
                                  
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->bank_master->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('bank_master/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('bank_name', 'bank name', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'bank_name'=>$post['bank_name'], 
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
        unauthorise_permission('94','601');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->bank_master->delete($id);
           $response = "Bank successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
         unauthorise_permission('94','601');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bank_master->deleteall($post['row_id']);
            $response = "Bank successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->bank_master->get_by_id($id);  
        $data['page_title'] = $data['form_data']['bank_master']." detail";
        $this->load->view('bank_master/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
         unauthorise_permission('94','602');
        $data['page_title'] = 'Banks Archive List';
        $this->load->helper('url');
        $this->load->view('bank_master/archive',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission('94','602');
        $this->load->model('bank_master/bank_master_archive_model','bank_master_archive'); 

      
               $list = $this->bank_master_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $bank_master) {
         // print_r($bank_master);die;
            $no++;
            $row = array();
            if($bank_master->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bank_master->id.'">'.$check_script; 
            $row[] = $bank_master->bank_name;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($bank_master->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            // if(in_array('70',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_bank_master('.$bank_master->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              // }
              // if(in_array('69',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$bank_master->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               // }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bank_master_archive->count_all(),
                        "recordsFiltered" => $this->bank_master_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission('94','604');
        $this->load->model('bank_master/bank_master_archive_model','bank_master_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->bank_master_archive->restore($id);
           $response = "bank successfully restore in bank list.";
           echo $response;
       }
    }

    function restoreall()
    { 
         unauthorise_permission('94','604');
        $this->load->model('bank_master/bank_master_archive_model','bank_master_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bank_master_archive->restoreall($post['row_id']);
            $response = "Bank successfully restore in bank list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         unauthorise_permission('94','603');
        $this->load->model('bank_master/bank_master_archive_model','bank_master_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->bank_master_archive->trash($id);
           $response = "Bank successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('94','603');
        $this->load->model('bank_master/bank_master_archive_model','bank_master_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bank_master_archive->trashall($post['row_id']);
            $response = "Bank successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  // public function bank_master_dropdown()
  // {
  //    $bank_master_list = $this->bank_master->bank_master_list();
  //    $dropdown = '<option value="">Select bank_master</option>'; 
  //    $bank_masters_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
  //    if(!empty($bank_master_list))
  //    {
  //         foreach($bank_master_list as $bank_master)
  //         {
  //              if(in_array($bank_master->bank_master,$bank_masters_array)){
  //                   $selected_bank_master = 'selected="selected"';
  //              }
  //              else
  //              {
  //                 $selected_bank_master = '';  
  //              }
  //              $dropdown .= '<option value="'.$bank_master->id.'" '.$selected_bank_master.' >'.$bank_master->bank_master.'</option>';
  //         }
  //    } 
  //    echo $dropdown; 
  // }
  

}
?>