<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_room_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_room_type/Ipd_room_type_model','ipd_room_type');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('106','656');
        $data['page_title'] = 'Room Type List'; 
        $this->load->view('ipd_room_type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('106','656');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->ipd_room_type->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_room_type) {
         // print_r($ipd_room_type);die;
            $no++;
            $row = array();
            if($ipd_room_type->status==1)
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
            if($users_data['parent_id']==$ipd_room_type->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_room_type->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ipd_room_type->room_category;
             
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_room_type->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_room_type->branch_id)
            {
              if(in_array('658',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_room_type('.$ipd_room_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_room_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('659',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_room_type('.$ipd_room_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_room_type->count_all(),
                        "recordsFiltered" => $this->ipd_room_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('106','657');
        $data['page_title'] = "Add Room Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'room_type'=>"",
                                  'status'=>"1",
                                 
                                  
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_room_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_room_type/add',$data);       
    }
    
    public function find_gender()
    {
         $this->load->model('general/general_model'); 
         $ipd_room_type_id = $this->input->post('ipd_room_type_id');
         $data='';
          if(!empty($ipd_room_type_id)){
               $result = $this->general_model->find_gender($ipd_room_type_id);
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
     unauthorise_permission('106','658');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_room_type->get_by_id($id);  
        $data['page_title'] = "Update Room Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'room_type'=>$result['room_category'], 
                                  'status'=>$result['status'],
                                 
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_room_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_room_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('room_type', 'room category', 'trim|required|callback_check_room_type'); 
       
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'room_type'=>$post['room_type'], 
                                        'status'=>$post['status'],
                                       
                                     ); 
            return $data['form_data'];
        }   
    }
    public function check_room_type($str){
 
          $post = $this->input->post();
          if(!empty($post['room_type']))
          {
               $this->load->model('general/general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    return true;
               }
               else
               {
                    $room_data = $this->general->check_room_type($post['room_type']);
                    if(empty($room_data))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('check_room_type', 'The room category already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('check_room_type', 'The  room category field is required.');
               return false; 
          } 
     }
    public function delete($id="")
    {
       unauthorise_permission('106','659');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_room_type->delete($id);
           $response = "Room Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('106','659');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_room_type->deleteall($post['row_id']);
            $response = "Room Types successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_room_type->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_room_type']." detail";
        $this->load->view('ipd_room_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('106','660');
        $data['page_title'] = 'Room Type Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_room_type/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('106','660');
        $this->load->model('ipd_room_type/ipd_room_type_archive_model','ipd_room_type_archive'); 

      
               $list = $this->ipd_room_type_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_room_type) {
         // print_r($ipd_room_type);die;
            $no++;
            $row = array();
            if($ipd_room_type->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_room_type->id.'">'.$check_script; 
            $row[] = $ipd_room_type->room_category; 
            
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_room_type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('662',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_room_type('.$ipd_room_type->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('661',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_room_type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_room_type_archive->count_all(),
                        "recordsFiltered" => $this->ipd_room_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('106','662');
        $this->load->model('ipd_room_type/ipd_room_type_archive_model','ipd_room_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_room_type_archive->restore($id);
           $response = "Room Type successfully restore in Room Type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('106','662');
        $this->load->model('ipd_room_type/ipd_room_type_archive_model','ipd_room_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_room_type_archive->restoreall($post['row_id']);
            $response = "Room Types successfully restore in Room Type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('106','661');
        $this->load->model('ipd_room_type/ipd_room_type_archive_model','ipd_room_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_room_type_archive->trash($id);
           $response = "Room Type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('106','661');
        $this->load->model('ipd_room_type/ipd_room_type_archive_model','ipd_room_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_room_type_archive->trashall($post['row_id']);
            $response = "Room Type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ipd_room_type_dropdown()
  {
     $ipd_room_type_list = $this->ipd_room_type->ipd_room_type_list();
     $dropdown = '<option value="">Select ipd_room_type</option>'; 
     $ipd_room_types_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($ipd_room_type_list))
     {
          foreach($ipd_room_type_list as $ipd_room_type)
          {
               if(in_array($ipd_room_type->ipd_room_type,$ipd_room_types_array)){
                    $selected_ipd_room_type = 'selected="selected"';
               }
               else
               {
                  $selected_ipd_room_type = '';  
               }
               $dropdown .= '<option value="'.$ipd_room_type->id.'" '.$selected_ipd_room_type.' >'.$ipd_room_type->ipd_room_type.'</option>';
          }
     } 
     echo $dropdown; 
  }
  public function get_room_type_charges()
  {
     $result = $this->ipd_room_type->get_room_type_charges();
     $data = array();
     if(!empty($result))
     {
          $data = array(
              'bed_charges'=>$result[0]['bed_charges'],
              'nursing_charges'=>$result[0]['nursing_charges'],
              'rmo_charges'=>$result[0]['rmo_charges'],
              'panel_bed_charges'=>$result[0]['panel_bed_charges'],
              'panel_nursing_charges'=>$result[0]['panel_nursing_charges'],
              'panel_rmo_charges'=>$result[0]['panel_rmo_charges']
          );

     }
     $format_data = json_encode($data);
     return $format_data;
  }
  

}
?>