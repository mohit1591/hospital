<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_settings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('company_settings/Company_setting_model','company_setting');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('11','57');
        $data['page_title'] = 'Company Settings Details'; 
        $this->load->view('company_settings/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('11','57');
        $list = $this->company_setting->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Company_details) {
         // print_r($Vendor);die;
            $no++;
            $row = array();
            // if($Email->status==1)
            // {
            //     $status = '<font color="green">Active</font>';
            // }   
            // else{
            //     $status = '<font color="red">Inactive</font>';
            // } 
            //  if($Email->smtp_ssl==1)
            // {
            //     $ssl_status = '<font color="green">Yes</font>';
            // }   
            // else{
            //     $ssl_status = '<font color="red">No</font>';
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
            $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Company_details->id.'">'.$check_script;
            $row[] = $Company_details->branch_name; 
            $row[] = $Company_details->email;
            $row[] = $Company_details->address; 
            $row[] = $Company_details->country; 
            $row[] = $Company_details->city; 
            $row[] = $Company_details->state;
                 
           
            // $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($Company_details->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
        if($users_data['parent_id']=='110')
        {
            $btnedit = '';
        }
        else
        {
           if(in_array('59',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_company_settings('.$Company_details->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Company_details->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          } 
        }
          
         
         
          
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->company_setting->count_all(),
                        "recordsFiltered" => $this->company_setting->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('11','58');
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
                

                
                
                $this->company_setting->save();
                 echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('company_settings/add',$data);       
    }
     
    public function edit($id="")
    {

     $this->load->model('general/general_model'); 
     $data['country_list'] = $this->general_model->country_list();
     $data['city_list'] = $this->general_model->city_list();
     $data['state_list'] = $this->general_model->state_list();


      // unauthorise_permission('11','59');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->company_setting->get_by_id($id);  
        $data['page_title'] = "Update Company Settings";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                   'comp_id'=>$result['id'],
                                   'company_name'=>$result['branch_name'],
                                   'email'=>$result['email'],
                                   'address' =>$result['address'],
                                   'country_id'=>$result['country_id'],
                                   "old_img"=>$result['photo'],
                                   "photo"=>$result['photo'],
                                   "old_img_banner"=>$result['photo_banner'],
                                   "photo_banner"=>$result['photo_banner'],
                                   "theme"=>$result['theme'],
                                   "logo_url"=>$result['logo_url'],
                                   "punch_line"=>$result['punch_line'],
                                   "auth_code"=>$result['auth_code'],
                                   'city_id'=>$result['city_id'],
                                   'state_id'=>$result['state_id']
                                 
                                  
                              );   
        
        if(isset($post) && !empty($post))
        {  
          
            $valid_response = $this->_validate();

            $data['form_data'] = $valid_response['form_data'];
            $data['photo_error'] = $valid_response['photo_error']; 
            $data['photo_banner_error'] = $valid_response['photo_banner_error']; 
            if($this->form_validation->run() == TRUE)
            {  
                if(!empty($post['old_img']) && !empty($valid_response['photo_name']) && file_exists(DIR_UPLOAD_PATH.'logo/'.$post['old_img']) &&  isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name']))
               {
                   unlink(DIR_UPLOAD_PATH.'logo/'.$post['old_img']);
               }
               if(!empty($post['old_img_banner']) && !empty($valid_response['photo_name_banner']) && file_exists(DIR_UPLOAD_PATH.'patient_login/banner/'.$post['old_img_banner'])  && isset($_FILES['photo_banner']['name']) && !empty($_FILES['photo_banner']['name']))
               {
                   unlink(DIR_UPLOAD_PATH.'patient_login/banner/'.$post['old_img_banner']);
               }
               
               

               $this->company_setting->save($valid_response['photo_name'],$valid_response['photo_name_banner']);
           
               echo 1;

               return false;
           }
            else
            {
              
                $data['form_error'] = validation_errors();  
            }     
        }
       
       $this->load->view('company_settings/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();
        $data['form_data']= [];  
        $data['photo_error']= [];
        $data['photo_name'] = $post['old_img'];
        $data['photo_name_banner'] = $post['old_img_banner'];     
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'valid_email|trim|required');
        $this->form_validation->set_rules('city_id', 'City', 'trim|required'); 
        $this->form_validation->set_rules('state_id', 'State', 'trim|required'); 
        $this->form_validation->set_rules('country_id', 'Country', 'trim|required'); 
        if(isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name']))
        {   
          
           $config['upload_path']   = DIR_UPLOAD_PATH.'logo/'; 
              $config['allowed_types'] = 'jpg|png'; 
              $config['max_size']      = 1000; 
              $config['encrypt_name'] = TRUE; 
              $this->load->library('upload', $config);
               if ($this->upload->do_upload('photo')) 
               {

                $file_data = $this->upload->data();
               

                $data['photo_name']  =  $file_data['file_name']; 
             } 
             else
             {
               $this->form_validation->set_rules('photo', 'photo', 'trim|required'); 
               $data['photo_error'] = $this->upload->display_errors();
             }

        }

        if(isset($_FILES['photo_banner']['name']) && !empty($_FILES['photo_banner']['name']))
        {   
          $config['upload_path']   = DIR_UPLOAD_PATH.'patient_login/banner/'; 
          $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
          $config['max_size']      = 1000; 
          $config['encrypt_name'] = TRUE; 
          $this->load->library('upload', $config);
          $this->upload->initialize($config);

           
            if ($this->upload->do_upload('photo_banner')) 
            {
              $file_data = $this->upload->data();
              $data['photo_name_banner']  =  $file_data['file_name'];
              //print_r($data['photo_name_banner']);die;
            } 
            else
            {
              $this->form_validation->set_rules('photo_banner', 'Banner', 'trim|required'); 
              $data['photo_banner_error'] = $this->upload->display_errors();
            }

        }

        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                  'comp_id'=>$post['comp_id'],
                                  'company_name'=>$post['company_name'],
                                  'email'=>$post['email'],
                                  'address' =>$post['address'],
                                  'country_id'=>$post['country_id'],
                                  "old_img_banner"=>$post['old_img_banner'],
                                  "logo_url"=>$post['logo_url'],
                                  "punch_line"=>$post['punch_line'],
                                  "theme"=>$post['theme'],
                                  "old_img"=>$post['old_img'],
                                  'city_id'=>$post['city_id'],
                                  'auth_code'=>$post['auth_code'],
                                  'state_id'=>$post['state_id'],
                                 
                                  
                              );   
            
        }   
        return $data;
    }
 
    public function delete($id="")
    {
       unauthorise_permission('11','60');
       if(!empty($id) && $id>0)
       {
           $result = $this->company_setting->delete($id);
           $response = "Company Settings  successfully deleted.";
           echo $response;
       }
    }
    public function total_active_settings_count(){
        $result = $this->company_setting->totalcount_active_setting();
        
        if($result){
            return $result;
        }else{
          return false;
        }
    }
    function deleteall()
    {
       unauthorise_permission('11','60');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->company_setting->deleteall($post['row_id']);
            $response = "Company Settings successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->company_setting->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Vendor']." detail";
        $this->load->view('company_settings/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('11','61');
        $data['page_title'] = 'Email Settings list';
        $this->load->helper('url');
        $this->load->view('company_settings/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('15','61');
        $this->load->model('company_settings/Company_setting_archive_model','company_setting_archieve'); 
        $users_data = $this->session->userdata('auth_users');
        $list = $this->company_setting_archieve->get_datatables();  
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
               if(in_array('63',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_Email_Setting('.$Email->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
               if(in_array('62',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$Email->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->company_setting_archieve->count_all(),
                        "recordsFiltered" => $this->company_setting_archieve->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('11','63');
        $this->load->model('company_settings/Company_setting_archive_model','company_setting_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->company_setting_archieve->restore($id);
           $response = "Company Settings successfully restore in Email Template list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('11','63');
       $this->load->model('company_settings/Company_setting_archive_model','company_setting_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->company_setting_archieve->restoreall($post['row_id']);
            $response = "Company Setting successfully restore in Email Template list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('11','62');
       $this->load->model('company_settings/Company_setting_archive_model','company_setting_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->company_setting_archieve->trash($id);
           $response = "Company Setting successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('11','62');
       $this->load->model('company_settings/Company_setting_archive_model','company_setting_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->company_setting_archieve->trashall($post['row_id']);
            $response = "Company Setting successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function State_list_dropdown()
  {

      $State_list = $this->company_setting->state_list();
      $dropdown = '<option value="">Select Element Template</option>'; 
      if(!empty($State_list))
      {
        foreach($State_list as $State)
        {
           $dropdown .= '<option value="'.$State->id.'">'.$State->state.'</option>';
        }
      } 
      echo $dropdown; 
  }
  public function Country_list_dropdown()
  {

      $Country_list = $this->company_setting->country_list();
      $dropdown = '<option value="">Select Element Template</option>'; 
      if(!empty($Country_list))
      {
        foreach($Country_list as $Country)
        {
           $dropdown .= '<option value="'.$Country->id.'">'.$Country->country.'</option>';
        }
      } 
      echo $dropdown; 
  }
  public function City_list_dropdown()
  {

      $City_list = $this->company_setting->city_list();
      $dropdown = '<option value="">Select Element Template</option>'; 
      if(!empty($City_list))
      {
        foreach($City_list as $City)
        {
           $dropdown .= '<option value="'.$City->id.'">'.$City->city.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>