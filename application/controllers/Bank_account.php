<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_account extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('bank_account/bank_account_model','bank_account','bank_account_archive_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(98,605);  
        $data['page_title'] = 'Bank Account List'; 
        $this->load->view('bank_account/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(98,605); 
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->bank_account->get_datatables();
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $account_type='';
        foreach ($list as $bank_account) {
         // print_r($bank_account);die;
            $no++;
            $row = array();
            if($bank_account->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($bank_account->state))
            {
                $state = " ( ".ucfirst(strtolower($bank_account->state))." )";
            }
            //////////////////////// 
            
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
            if($users_data['parent_id']==$bank_account->branch_id){ 
                $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bank_account->id.'">'.$check_script; 
            }else{
                $row[]='';
            } 
             //print_r($gte_bank_name);
            //$row[]= str_replace('bank', '', $bank_account->bank_name);
            $row[]= str_replace('Bank', '', $bank_account->bank_name);
            $row[] = $bank_account->account_holder; 
          
            $row[] = $bank_account->account_no; 
            if($bank_account->type==1){
             $account_type = 'Saving';  
            }else{
               $account_type = 'Current';
            }
            $row[]= $account_type;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($bank_account->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
             if($users_data['parent_id']==$bank_account->branch_id){
                if(in_array('32',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_bank_account('.$bank_account->id.');" class="btn-custom" href="javascript:void(0)" style="'.$bank_account->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                if(in_array('33',$users_data['permission']['action'])) 
                {
                   $btndelete = ' <a class="btn-custom" onClick="return delete_emp_type('.$bank_account->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bank_account->count_all(),
                        "recordsFiltered" => $this->bank_account->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission(98,606); 
        $data['page_title'] = "Add Bank Account";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                   'account_holder'=>"", 
                                  'bank_name'=>"", 
                                  'ifsc_code'=>'',
                                  'micr_code'=>'',
                                  'account_no'=>"", 
                                  'type'=>"", 
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->bank_account->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('bank_account/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission(98,607); 
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->bank_account->get_by_id($id); 
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->bank_account->bank_account_list();  
        $data['page_title'] = "Update Bank Account";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'account_holder'=>$result['account_holder'], 
                                  'bank_name'=>$result['bank_name'], 
                                  'account_no'=>$result['account_no'], 
                                  'ifsc_code'=>$result['ifsc_code'],
                                  'micr_code'=>$result['micr_code'], 
                                  'type'=>$result['type'], 
 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->bank_account->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

        //print_r($data);die;
       $this->load->view('bank_account/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('account_holder', 'account holder', 'trim|required'); 
        $this->form_validation->set_rules('bank_name', 'bank name', 'trim|required');
        $this->form_validation->set_rules('account_no', 'account number', 'trim|required');
         $this->form_validation->set_rules('type', 'type', 'trim|required');
          $this->form_validation->set_rules('ifsc_code', 'ifsc code', 'trim|required');
         $this->form_validation->set_rules('micr_code', 'micr code', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                      'data_id'=>$post['data_id'],
                                        'account_holder'=>$post['account_holder'], 
                                        'bank_name'=>$post['bank_name'], 
                                        'account_no'=>$post['account_no'], 
                                      'type'=>$post['type'],
                                      'ifsc_code'=>$post['ifsc_code'], 
                                      'micr_code'=>$post['micr_code'], 
                                      'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(98,608); 
       if(!empty($id) && $id>0)
       {
           $result = $this->bank_account->delete($id);
           $response = "Bank Account successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(98,608); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bank_account->deleteall($post['row_id']);
            $response = "Bank Account successfully deleted.";
            echo $response;
        }
    }

     


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(98,609); 
        $data['page_title'] = 'Bank Account Archive List';
        $this->load->helper('url');
        $this->load->view('bank_account/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(98,609); 
        $this->load->model('bank_account/bank_account_archive_model','bank_account_archive'); 
        $users_data = $this->session->userdata('auth_users');
        

               $list = $this->bank_account_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $bank_account) {
         // print_r($bank_account);die;
            $no++;
            $row = array();
            if($bank_account->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($bank_account->state))
            {
                $state = " ( ".ucfirst(strtolower($bank_account->state))." )";
            }
            //////////////////////// 
            
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bank_account->id.'">'.$check_script; 
            $row[] = $bank_account->account_holder; 
            //if(isset($gte_bank_name[0]->bank_name)){
               //$row[] = $gte_bank_name[0]->bank_name; 
            // }else{
              $row[]= $bank_account->bank_name;
           //  }
           
            $row[] = $bank_account->account_no; 
            if($bank_account->type==1){
             $account_type = 'Saving';  
            }else{
               $account_type = 'Current';
            }
            $row[]= $account_type;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($bank_account->created_date)); 
            
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if(in_array('36',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_bank_account('.$bank_account->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            if(in_array('35',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$bank_account->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //


      $row[] = $btn_restore.$btn_delete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bank_account_archive->count_all(),
                        "recordsFiltered" => $this->bank_account_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(98,611); 
        $this->load->model('bank_account/bank_account_archive_model','bank_account_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->bank_account_archive->restore($id);
           $response = "Bank Aaccount successfully restore in Bank Account list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(98,611); 
        $this->load->model('bank_account/bank_account_archive_model','bank_account_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bank_account_archive->restoreall($post['row_id']);
            $response = "Bank Account successfully restore in Bank Account list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(98,610); 
        $this->load->model('bank_account/bank_account_archive_model','bank_account_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->bank_account_archive->trash($id);
           $response = "Bank account successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(98,610); 
        $this->load->model('bank_account/bank_account_archive_model','bank_account_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bank_account_archive->trashall($post['row_id']);
            $response = "Bank Account successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function bank_account_dropdown()
  {
      $bank_account_list = $this->bank_account->bank_account_list();
      $dropdown = '<option value="">Select Bank Account</option>'; 
      if(!empty($bank_account_list))
      {
        foreach($bank_account_list as $bank_account)
        {
           $dropdown .= '<option value="'.$bank_account->id.'">'.$bank_account->emp_type.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>