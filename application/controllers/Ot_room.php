<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_room extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_room/ot_room_model','ot_room');
        $this->load->library('form_validation');
    }

    public function index()
    { 
      // echo "hi";die;
        unauthorise_permission('191','1126');
        $data['page_title'] = 'Operation Room List'; 
        $this->load->view('ot_room/list',$data);
    }

    public function ajax_list()
    { 
     
         unauthorise_permission('191','1126');
         $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
     
            $list = $this->ot_room->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot_room) {
         // print_r($bank_master);die;
            $no++;
            $row = array();
            if($ot_room->status==1)
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
            if($users_data['parent_id']==$ot_room->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot_room->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ot_room->room_no;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ot_room->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ot_room->branch_id)
            {
             if(in_array('1124',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ot_room('.$ot_room->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ot_room->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('1123',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ot_room('.$ot_room->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_room->count_all(),
                        "recordsFiltered" => $this->ot_room->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('191','1125');
        $data['page_title'] = "Add Operation Room";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'room_no'=>"",
                                  'status'=>"1",
                                 
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ot_room->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ot_room/add',$data);       
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
      unauthorise_permission('191','1124');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ot_room->get_by_id($id);  
        $data['page_title'] = "Update Room No";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'room_no'=>$result['room_no'], 
                                  'status'=>$result['status'],
                                  
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ot_room->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ot_room/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('room_no', 'Room No', 'trim|required|callback_check_ot_room_no'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'room_no'=>$post['room_no'], 
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }

    public function check_ot_room_no($str)
  {

    $post = $this->input->post();

    if(!empty($str))
    {
        $this->load->model('general/general_model','general'); 
        if(!empty($post['data_id']) && $post['data_id']>0)
        {
              $data_cat= $this->ot_room->get_by_id($post['data_id']);
              if($data_cat['room_no']==$str && $post['data_id']==$data_cat['id'])
              {
              return true;  
              }
              else
              {
              $stockunitdata = $this->general->check_ot_room_no($str);

              if(empty($stockunitdata))
              {
              return true;
              }
              else
              {
              $this->form_validation->set_message('check_ot_room_no', 'The Room No already exists.');
              return false;
              }
              }
        }
        else
        {
                $stockunitdata = $this->general->check_ot_room_no($str);
                if(empty($stockunitdata))
                {
                   return true;
                }
                else
                {
            $this->form_validation->set_message('check_ot_room_no', 'The Room No already exists.');
            return false;
                }
        }  
    }
    else
    {
      $this->form_validation->set_message('check_ot_room_no', 'The Room No field is required.');
            return false; 
    } 
  }
 
    public function delete($id="")
    {
       unauthorise_permission('191','1123');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ot_room->delete($id);
           $response = "Room successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('191','1123');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_room->deleteall($post['row_id']);
            $response = "OT Room successfully deleted.";
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
         unauthorise_permission('191','1122');
        $data['page_title'] = 'OT Room Archive List';
        $this->load->helper('url');
        $this->load->view('ot_room/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('191','1122');
        $this->load->model('ot_room/ot_room_archive_model','ot_room_archive'); 

      
               $list = $this->ot_room_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot_room) {
         // print_r($bank_master);die;
            $no++;
            $row = array();
            if($ot_room->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot_room->id.'">'.$check_script; 
            $row[] = $ot_room->room_no;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ot_room->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
           if(in_array('1120',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ot_room('.$ot_room->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
              if(in_array('1121',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ot_room->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
              }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_room_archive->count_all(),
                        "recordsFiltered" => $this->ot_room_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('191','1120');
        $this->load->model('ot_room/ot_room_archive_model','ot_room_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_room_archive->restore($id);
           $response = "OT Room successfully restore in Room list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('191','1120');
          $this->load->model('ot_room/ot_room_archive_model','ot_room_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_room_archive->restoreall($post['row_id']);
            $response = "OT Room successfully restore in Room list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('191','1121');
        $this->load->model('ot_room/ot_room_archive_model','ot_room_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_room_archive->trash($id);
           $response = "OT Room successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('191','1121');
          $this->load->model('ot_room/ot_room_archive_model','ot_room_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_room_archive->trashall($post['row_id']);
            $response = "Ot Room deleted parmanently.";
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
  
 public function ot_name_dropdown()
  {
    $ot_name_list = $this->ot_room->ot_room_list();
    $dropdown = '<option value="">Select OT Room</option>'; 
    if(!empty($ot_name_list))
    {
    foreach($ot_name_list as $otname)
    {
    $dropdown .= '<option value="'.$otname->id.'">'.$otname->room_no.'</option>';
    }
    } 
    echo $dropdown; 
  }
}
?>