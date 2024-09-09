<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_settings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('sms_settings/sms_setting_model','sms_setting');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('49','280');
        $data['page_title'] = 'Sms Settings List'; 
        $this->load->view('sms_settings/list',$data);
    }

    public function ajax_list()
    { 
      unauthorise_permission('49','280');
      $users_data = $this->session->userdata('auth_users');
      $sub_branch_details = $this->session->userdata('sub_branches_data');
      $parent_branch_details = $this->session->userdata('parent_branches_data');
      $list = $this->sms_setting->get_datatables();
         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $SMS) {
         // print_r($Vendor);die;
            $no++;
            $row = array();
            
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
            if($users_data['parent_id']==$SMS->branch_id){
                $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$SMS->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $SMS->sms_url; 
            $row[] = $SMS->username;
            $row[] = $SMS->sender_id; 
           
             
           
            // $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($SMS->created_date)); 
          
          $btnedit='';
          $btndelete='';
        
           if($users_data['parent_id']==$SMS->branch_id){
               if(in_array('282',$users_data['permission']['action'])){
                    $btnedit = ' <a onClick="return edit_Sms_Settings('.$SMS->id.');" class="btn-custom" href="javascript:void(0)" style="'.$SMS->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
          }
         
         
          
         
             $row[] = $btnedit.$btndelete;           
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->sms_setting->count_all(),
                        "recordsFiltered" => $this->sms_setting->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        // unauthorise_permission('11','58');
        $data['page_title'] = "Add SMS Settings";  
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
                

                
                
                $this->sms_setting->save();
                 echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('sms_settings/add',$data);       
    }
     
    public function edit($id="")
    {
     
     
      unauthorise_permission('49','282');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->sms_setting->get_by_id($id);  
        $data['page_title'] = "Update Sms Settings";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'sms_set_id'=>$result['id'],
                                 
                                  'url_of_sms' =>$result['sms_url'],
                                  'username'=>$result['username'],
                                  'password'=>'',
                                  'sender_id'=>$result['sender_id'],
                              
                                  
                              );   
        
        if(isset($post) && !empty($post))
        {   
          
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {  
          
                 $this->sms_setting->save();
                 echo 1;
                
                return false;
                
            }
            else
            {
              
                $data['form_error'] = validation_errors();  
            }     
        }
       
       $this->load->view('sms_settings/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('url_of_sms', 'URL of SMS', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required'); 
        $this->form_validation->set_rules('sender_id', 'Sender ID', 'trim|required'); 
 
      
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(6); 
             $data['form_data'] = array(
                                  'sms_set_id'=>$post['sms_set_id'],
                                 
                                  'url_of_sms' =>$post['url_of_sms'],
                                  'username'=>$post['username'],
                                  'password'=>'',
                                  'sender_id'=>$post['sender_id'],
                              
                                  
                              );   
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('11','60');
       if(!empty($id) && $id>0)
       {
           $result = $this->sms_setting->delete($id);
           $response = "Sms Settings  successfully deleted.";
           echo $response;
       }
    }
    public function total_active_settings_count(){
        $result = $this->sms_setting->totalcount_active_setting();
        
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
            $result = $this->sms_setting->deleteall($post['row_id']);
            $response = "Sms Settings successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->email_template->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Vendor']." detail";
        $this->load->view('sms_settings/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        // unauthorise_permission('11','61');
        $data['page_title'] = 'Sms Settings list';
        $this->load->helper('url');
        $this->load->view('sms_settings/archive',$data);
    }

    public function archive_ajax_list()
    {
        // unauthorise_permission('15','61');
        $this->load->model('sms_settings/Sms_setting_archive_model','sms_setting_archieve'); 
        $users_data = $this->session->userdata('auth_users');
      
               $list = $this->sms_setting_archieve->get_datatables();
              
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $SMS) { 
            $no++;
            $row = array();
            if($SMS->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
               if($SMS->smtp_ssl==1)
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
            if($users_data['parent_id']==$SMS->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$SMS->id.'">'.$check_script; 
            }else{
               $row[]='';
            }
              $row[] = $SMS->smtp_address; 
            $row[] = $SMS->network_email_address;
            $row[] = $SMS->port; 
            $row[] = $ssl_status;
            $row[] = $SMS->cc_email;   
           
     
           
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($SMS->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if($users_data['parent_id']==$SMS->branch_id){
               if(in_array('63',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_Email_Setting('.$SMS->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
               if(in_array('62',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$SMS->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->sms_setting_archieve->count_all(),
                        "recordsFiltered" => $this->sms_setting_archieve->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        // unauthorise_permission('11','63');
        $this->load->model('sms_settings/Sms_setting_archive_model','sms_setting_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->sms_setting_archieve->restore($id);
           $response = "Sms Settings successfully restore in Email Template list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        // unauthorise_permission('11','63');
       $this->load->model('sms_settings/Sms_setting_archive_model','sms_setting_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sms_setting_archieve->restoreall($post['row_id']);
            $response = "Sms Setting successfully restore in Email Template list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        // unauthorise_permission('11','62');
       $this->load->model('sms_settings/Sms_setting_archive_model','sms_setting_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->sms_setting_archieve->trash($id);
           $response = "Sms Setting successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        // unauthorise_permission('11','62');
       $this->load->model('sms_settings/Sms_setting_archive_model','sms_setting_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sms_setting_archieve->trashall($post['row_id']);
            $response = "Sms Setting successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function Sms_Setting_dropdown()
  {

      $Email_Template_list = $this->sms_setting->email_setting_list();
      $dropdown = '<option value="">Select Element Template</option>'; 
      if(!empty($Sms_Setting_list))
      {
        foreach($Sms_Setting_list as $Sms_Setting)
        {
           $dropdown .= '<option value="'.$Sms_Setting->id.'">'.$Sms_Setting->cc_email.'</option>';
        }
      } 
      echo $dropdown; 
  }

    
  

}
?>