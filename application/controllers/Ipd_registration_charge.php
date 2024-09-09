<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_registration_charge extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_registration_charge/ipd_registration_charge_model','ipd_registration_charge');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        // unauthorise_permission('12','64');
        $data['page_title'] = 'IPD Registration Charge List'; 
        $this->load->view('ipd_registration_charge/list',$data);
    }

    public function ajax_list()
    { 
        // unauthorise_permission('12','64');
         $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
     
            $list = $this->ipd_registration_charge->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_registration_charge) {
         // print_r($ipd_registration_charge);die;
            $no++;
            $row = array();
            // if($ipd_registration_charge->status==1)
            // {
            //     $status = '<font color="green">Active</font>';
            // }   
            // else{
            //     $status = '<font color="red">Inactive</font>';
            // } 
            
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
            if($users_data['parent_id']==$ipd_registration_charge->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_registration_charge->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ipd_registration_charge->registration_charge;  
           
            $row[] = date('d-M-Y H:i A',strtotime($ipd_registration_charge->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_registration_charge->branch_id)
            {
              // if(in_array('66',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_registration_charge('.$ipd_registration_charge->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_registration_charge->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              // }
              // if(in_array('67',$users_data['permission']['action'])){
                    // $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_registration_charge('.$ipd_registration_charge->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               // }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_registration_charge->count_all(),
                        "recordsFiltered" => $this->ipd_registration_charge->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        // unauthorise_permission('12','65');
        $data['page_title'] = "Add ipd_registration_charge";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'registration_charge'=>"",
                                 
                                  
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_registration_charge->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_registration_charge/add',$data);       
    }
     // -> function to find gender according to selected ipd_registration_charge
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_registration_charge_id = $this->input->post('ipd_registration_charge_id');
         $data='';
          if(!empty($ipd_registration_charge_id)){
               $result = $this->general_model->find_gender($ipd_registration_charge_id);
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
     // unauthorise_permission('12','66');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_registration_charge->get_by_id($id);  
        $data['page_title'] = "Update Registration Charge";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'registration_charge'=>$result['registration_charge'], 
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_registration_charge->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_registration_charge/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('registration_charge', 'registration charge', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'registration_charge'=>$post['registration_charge'], 
                                       
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       // unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_registration_charge->delete($id);
           $response = "Registration Charge successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        // unauthorise_permission('12','67');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_registration_charge->deleteall($post['row_id']);
            $response = "Registration Charges successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_registration_charge->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_registration_charge']." detail";
        $this->load->view('registration_charge/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        // unauthorise_permission('12','68');
        $data['page_title'] = 'Registration Charge Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_registration_charge/archive',$data);
    }

    public function archive_ajax_list()
    {
        // unauthorise_permission('12','68');
        $this->load->model('ipd_registration_charge/ipd_registration_charge_archive_model','ipd_registration_charge_archive'); 

      
               $list = $this->ipd_registration_charge_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_registration_charge) {
         // print_r($ipd_registration_charge);die;
            $no++;
            $row = array();
            if($ipd_registration_charge->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_registration_charge->id.'">'.$check_script; 
            $row[] = $ipd_registration_charge->ipd_registration_charge;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_registration_charge->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('70',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_registration_charge('.$ipd_registration_charge->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('69',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_registration_charge->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_registration_charge_archive->count_all(),
                        "recordsFiltered" => $this->ipd_registration_charge_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        // unauthorise_permission('12','70');
        $this->load->model('ipd_registration_charge/ipd_registration_charge_archive_model','ipd_registration_charge_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_registration_charge_archive->restore($id);
           $response = "ipd_registration_charge successfully restore in ipd_registration_charge list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        // unauthorise_permission('12','70');
        $this->load->model('ipd_registration_charge/ipd_registration_charge_archive_model','ipd_registration_charge_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_registration_charge_archive->restoreall($post['row_id']);
            $response = "ipd_registration_charges successfully restore in ipd_registration_charge list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        // unauthorise_permission('12','69');
        $this->load->model('ipd_registration_charge/ipd_registration_charge_archive_model','ipd_registration_charge_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_registration_charge_archive->trash($id);
           $response = "ipd_registration_charge successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        // unauthorise_permission('12','69');
        $this->load->model('ipd_registration_charge/ipd_registration_charge_archive_model','ipd_registration_charge_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_registration_charge_archive->trashall($post['row_id']);
            $response = "ipd_registration_charge successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  // public function ipd_registration_charge_dropdown()
  // {
  //    $ipd_registration_charge_list = $this->ipd_registration_charge->ipd_registration_charge_list();
  //    $dropdown = '<option value="">Select ipd_registration_charge</option>'; 
  //    $ipd_registration_charges_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
  //    if(!empty($ipd_registration_charge_list))
  //    {
  //         foreach($ipd_registration_charge_list as $ipd_registration_charge)
  //         {
  //              if(in_array($ipd_registration_charge->ipd_registration_charge,$ipd_registration_charges_array)){
  //                   $selected_ipd_registration_charge = 'selected="selected"';
  //              }
  //              else
  //              {
  //                 $selected_ipd_registration_charge = '';  
  //              }
  //              $dropdown .= '<option value="'.$ipd_registration_charge->id.'" '.$selected_ipd_registration_charge.' >'.$ipd_registration_charge->ipd_registration_charge.'</option>';
  //         }
  //    } 
  //    echo $dropdown; 
  // }
  

}
?>