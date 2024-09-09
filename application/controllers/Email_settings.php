<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_settings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('email_settings/Email_setting_model','email_setting');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('46','266');
        $data['page_title'] = 'Email Settings List'; 
        $this->load->view('email_settings/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('46','266');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->email_setting->get_datatables();
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Email) {
         // print_r($Vendor);die;
            $no++;
            $row = array();
            
            if($Email->smtp_ssl==1)
            {
                $ssl_status = '<font color="green">Yes</font>';
            }   
            else{
                $ssl_status = '<font color="red">No</font>';
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
            if($users_data['parent_id']==$Email->branch_id){ 
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Email->id.'">'.$check_script;
               }else{
                    $row[]='';
               }
               if($Email->smtp_ssl==1)
               {
                  $smtp_ssl ='Yes';
               }
               else if($Email->smtp_ssl==0)
               {
                  $smtp_ssl ='No';
               }
            $row[] = $Email->smtp_address; 
            $row[] = $Email->network_email_address;
            $row[] = $Email->port; 
            $row[] = $smtp_ssl; 
            $row[] = $Email->cc_email;   
           
          $btnedit='';
          $btndelete='';
           if($users_data['parent_id']==$Email->branch_id){ 
               if(in_array('268',$users_data['permission']['action'])){
                    $btnedit = ' <a onClick="return edit_Email_Settings('.$Email->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Email->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
          }
            $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->email_setting->count_all(),
                        "recordsFiltered" => $this->email_setting->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        // unauthorise_permission('11','58');
        $data['page_title'] = "Add Email Template";  
        $post = $this->input->post();
        $vendor_code = generate_unique_id(6);
        $data['form_error'] = []; 
         $data['form_data'] = array(
                                  'email_set_id'=>"",
                                  'smtp_address'=>'',
                                  'network_email_id' =>'',
                                  'network_email_pass'=>'',
                                  'port'=>'',
                                  'ssl_status'=>'1',
                                  'cc_email'=>'',
                                  'status'=>'1'
                              );    


        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
           
            if($this->form_validation->run() == TRUE)
            {
                

                
                
                $this->email_setting->save();
                 echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('email_settings/add',$data);       
    }
     
    public function edit($id="")
    {
     
     
      unauthorise_permission('46','268');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->email_setting->get_by_id($id);  
        $data['page_title'] = "Update Email Settings";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'email_set_id'=>$result['id'],
                                  'smtp_address'=>$result['smtp_address'],
                                  'network_email_id' =>$result['network_email_address'],
                                  'network_email_pass'=>$result['email_password'],
                                  'port'=>$result['port'],
                                  'ssl_status'=>$result['smtp_ssl'],
                                  'cc_email'=>$result['cc_email'],
                                  
                              );   
        
        if(isset($post) && !empty($post))
        {   
          
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {  
          
                 $this->email_setting->save();
                 echo 1;
                
                return false;
                
            }
            else
            {
              
                $data['form_error'] = validation_errors();  
            }     
        }
       
       $this->load->view('email_settings/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('smtp_address', 'SMTP address', 'trim|required');
        $this->form_validation->set_rules('network_email_id', 'network email', 'valid_email|trim|required');
        $this->form_validation->set_rules('network_email_pass', 'network email password', 'trim|required'); 
        $this->form_validation->set_rules('port', 'port no.', 'max_length[4]|trim|required'); 
        $this->form_validation->set_rules('ssl_status', 'SSL', 'trim|required'); 
        $this->form_validation->set_rules('cc_email', ' email', 'valid_email|trim|required'); 
      
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(6); 
             $data['form_data'] = array(
                                  'email_set_id'=>$post['email_set_id'],
                                  'smtp_address'=>$post['smtp_address'],
                                  'network_email_id' =>$post['network_email_id'],
                                  'network_email_pass'=>$post['network_email_pass'],
                                  'port'=>$post['port'],
                                  'ssl_status'=>$post['ssl_status'],
                                  'cc_email'=>$post['cc_email'],
                             
                              );   
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       // unauthorise_permission('11','60');
       if(!empty($id) && $id>0)
       {
           $result = $this->email_setting->delete($id);
           $response = "Email Settings  successfully deleted.";
           echo $response;
       }
    }
    public function total_active_settings_count(){
        $result = $this->email_setting->totalcount_active_setting();
        
        if($result){
            return $result;
        }else{
          return false;
        }
    }
    function deleteall()
    {
       // unauthorise_permission('11','60');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->email_setting->deleteall($post['row_id']);
            $response = "Email Settings successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->email_template->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Vendor']." detail";
        $this->load->view('email_settings/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        // unauthorise_permission('11','61');
        $data['page_title'] = 'Email Settings Archieve List';
        $this->load->helper('url');
        $this->load->view('email_settings/archive',$data);
    }

    public function archive_ajax_list()
    {
        // unauthorise_permission('15','61');
        $this->load->model('email_settings/Email_setting_archive_model','email_setting_archieve'); 
        $users_data = $this->session->userdata('auth_users');
       
               $list = $this->email_setting_archieve->get_datatables();
              
           
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Email) { 
            $no++;
            $row = array();
            if($Email->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
               if($Email->smtp_ssl==1)
            {
                $ssl_status = '<font color="green">Yes</font>';
            }   
            else{
                $ssl_status = '<font color="red">No</font>';
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
            if($users_data['parent_id']==$Email->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$Email->id.'">'.$check_script; 
            }else{
               $row[]='';
            }
              $row[] = $Email->smtp_address; 
            $row[] = $Email->network_email_address;
            $row[] = $Email->port; 
            $row[] = $ssl_status;
            $row[] = $Email->cc_email;   
           
     
           
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($Email->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if($users_data['parent_id']==$Email->branch_id){
               // if(in_array('63',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_Email_Setting('.$Email->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               // }
               // if(in_array('62',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$Email->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               // }
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->email_setting_archieve->count_all(),
                        "recordsFiltered" => $this->email_setting_archieve->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        // unauthorise_permission('11','63');
        $this->load->model('email_settings/Email_setting_archive_model','email_setting_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->email_setting_archieve->restore($id);
           $response = "Email Settings successfully restore in Email Settings list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        // unauthorise_permission('11','63');
       $this->load->model('email_settings/Email_setting_archive_model','email_setting_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->email_setting_archieve->restoreall($post['row_id']);
            $response = "Email Setting successfully restore in Email Settings list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        // unauthorise_permission('11','62');
       $this->load->model('email_settings/Email_setting_archive_model','email_setting_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->email_setting_archieve->trash($id);
           $response = "Email Setting successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        // unauthorise_permission('11','62');
       $this->load->model('email_settings/Email_setting_archive_model','email_setting_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->email_setting_archieve->trashall($post['row_id']);
            $response = "Email Setting successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function Element_Setting_dropdown()
  {

      $Email_Template_list = $this->email_setting->email_setting_list();
      $dropdown = '<option value="">Select Element Template</option>'; 
      if(!empty($Email_Setting_list))
      {
        foreach($Email_Setting_list as $Email_Setting)
        {
           $dropdown .= '<option value="'.$Email_Setting->id.'">'.$Email_Setting->cc_email.'</option>';
        }
      } 
      echo $dropdown; 
  }
 


}
?>