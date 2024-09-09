<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preferred_reminder_service extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/preferred_reminder_service/preferred_reminder_service_model','preferred_reminder_service');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('264','1524');
        $data['page_title'] = 'Preferred Reminder Service List'; 
        $this->load->view('blood_bank/preferred_reminder_service/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('264','1524');
        $list = $this->preferred_reminder_service->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $preferred_reminder_service) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($preferred_reminder_service->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////medicine_preferred_reminder_service
            $check_script = "";
            if($i==$total_num)
            {
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$preferred_reminder_service->id.'">'.$check_script; 
            $row[] = $preferred_reminder_service->preferred_reminder_service;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($preferred_reminder_service->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1526',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_preferred_reminder_service('.$preferred_reminder_service->id.');" class="btn-custom" href="javascript:void(0)" style="'.$preferred_reminder_service->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1527',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_preferred_reminder_service('.$preferred_reminder_service->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->preferred_reminder_service->count_all(),
                        "recordsFiltered" => $this->preferred_reminder_service->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('264','1525');
        $data['page_title'] = "Add Preferred Reminder Service";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'preferred_reminder_service'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->preferred_reminder_service->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/preferred_reminder_service/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('264','1526');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->preferred_reminder_service->get_by_id($id);  
        $data['page_title'] = "Update Preferred Reminder Service";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'preferred_reminder_service'=>$result['preferred_reminder_service'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->preferred_reminder_service->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/preferred_reminder_service/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('preferred_reminder_service', 'preferred reminder service', 'trim|required|callback_preferred_reminder_service'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'preferred_reminder_service'=>$post['preferred_reminder_service'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
     /* callbackurl */
    /* check validation laready exit */
   public function preferred_reminder_service($str){
          $post = $this->input->post();
          if(!empty($post['preferred_reminder_service']))
          {
               $this->load->model('blood_bank/general/blood_bank_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->preferred_reminder_service->get_by_id($post['data_id']);
                      if($data_cat['preferred_reminder_service']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $preferred_reminder_service = $this->general->check_preferred_reminder_service($str);

                        if(empty($preferred_reminder_service))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('preferred_reminder_service', 'The preferred reminder service already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $preferred_reminder_service = $this->general->check_preferred_reminder_service($post['preferred_reminder_service'], $post['data_id']);
                    if(empty($preferred_reminder_service))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('preferred_reminder_service', 'The preferred reminder service already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('preferred_reminder_service', 'The preferred reminder service field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
      unauthorise_permission('264','1527');
       if(!empty($id) && $id>0)
       {
           $result = $this->preferred_reminder_service->delete($id);
           $response = "Preferred Reminder Service successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('264','1527');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->preferred_reminder_service->deleteall($post['row_id']);
            $response = "Preferred Reminder Service successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->preferred_reminder_service->get_by_id($id);  
        $data['page_title'] = $data['form_data']['preferred_reminder_service']." detail";
        $this->load->view('blood_bank/preferred_reminder_service/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('264','1528');
        $data['page_title'] = 'Preferred Reminder archive list';
        $this->load->helper('url');
        $this->load->view('blood_bank/preferred_reminder_service/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('264','1528');
        $this->load->model('blood_bank/preferred_reminder_service/preferred_reminder_service_archive_model','preferred_reminder_service_archive'); 

        $list = $this->preferred_reminder_service_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $preferred_reminder_service) { 
            $no++;
            $row = array();
            if($preferred_reminder_service->status==1)
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
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$preferred_reminder_service->id.'">'.$check_script; 
            $row[] = $preferred_reminder_service->preferred_reminder_service;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($preferred_reminder_service->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1531',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_preferred_reminder_service('.$preferred_reminder_service->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1529',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$preferred_reminder_service->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->preferred_reminder_service_archive->count_all(),
                        "recordsFiltered" => $this->preferred_reminder_service_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
        unauthorise_permission('264','1531');
        $this->load->model('blood_bank/preferred_reminder_service/preferred_reminder_service_archive_model','preferred_reminder_service_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->preferred_reminder_service_archive->restore($id);
           $response = "Preferred Reminder Service successfully restore in Preferred Reminder Service list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('264','1531');
        $this->load->model('blood_bank/preferred_reminder_service/preferred_reminder_service_archive_model','preferred_reminder_service_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->preferred_reminder_service_archive->restoreall($post['row_id']);
            $response = "Preferred Reminder Service successfully restore in Preferred Reminder Service list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         unauthorise_permission('264','1529');
       $this->load->model('blood_bank/preferred_reminder_service/preferred_reminder_service_archive_model','preferred_reminder_service_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->preferred_reminder_service_archive->trash($id);
           $response = "Preferred Reminder Service successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('264','1529');
       $this->load->model('blood_bank/preferred_reminder_service/preferred_reminder_service_archive_model','preferred_reminder_service_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->preferred_reminder_service_archive->trashall($post['row_id']);
            $response = "Preferred Reminder Service successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function preferred_reminder_service_dropdown()
  {

      $preferred_reminder_service_list = $this->preferred_reminder_service->preferred_reminder_service_list();
      $dropdown = '<option value="">Select Preferred Reminder Service</option>'; 
      if(!empty($preferred_reminder_service_list))
      {
        foreach($preferred_reminder_service_list as $preferred_reminder_service)
        {
           $dropdown .= '<option value="'.$preferred_reminder_service->id.'">'.$preferred_reminder_service->preferred_reminder_service.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>