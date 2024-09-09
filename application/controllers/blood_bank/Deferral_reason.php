<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deferral_reason extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/deferral_reason/deferral_reason_model','deferral_reason');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('263','1515');
        $data['page_title'] = 'Deferral Reason List'; 
        $this->load->view('blood_bank/deferral_reason/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('263','1515');
        $list = $this->deferral_reason->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $deferral_reason) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($deferral_reason->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$deferral_reason->id.'">'.$check_script; 
            $row[] = $deferral_reason->deferral_reason;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($deferral_reason->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1517',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_deferral_reason('.$deferral_reason->id.');" class="btn-custom" href="javascript:void(0)" style="'.$deferral_reason->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1518',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_deferral_reason('.$deferral_reason->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->deferral_reason->count_all(),
                        "recordsFiltered" => $this->deferral_reason->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('263','1516');
        $data['page_title'] = "Add Deferral Reason";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'deferral_reason'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->deferral_reason->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/deferral_reason/add',$data);       
    }
    
    
    public function edit($id="")
    {
      unauthorise_permission('263','1517');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->deferral_reason->get_by_id($id);  
        $data['page_title'] = "Update Deferral Reason";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'deferral_reason'=>$result['deferral_reason'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->deferral_reason->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/deferral_reason/add',$data);       
      }
    }
     
    private function _validate()
    {
          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('deferral_reason', 'deferral reason', 'trim|required'); 
          //callback_deferral_reason
          if ($this->form_validation->run() == FALSE) 
          {  
          //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'deferral_reason'=>$post['deferral_reason'], 
                                        'status'=>$post['status']
            ); 
          return $data['form_data'];
          }   
    }
     /* callbackurl */
    /* check validation laready exit */
   public function deferral_reason($str){
          $post = $this->input->post();
          if(!empty($post['deferral_reason']))
          {
               $this->load->model('blood_bank/general/blood_bank_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->deferral_reason->get_by_id($post['data_id']);
                      if($data_cat['deferral_reason']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $deferral_reason = $this->general->check_deferral_reason($str);

                        if(empty($deferral_reason))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('deferral_reason', 'The Deferral reason already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $deferral_reason = $this->general->check_deferral_reason($post['deferral_reason'], $post['data_id']);
                    if(empty($deferral_reason))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('deferral_reason', 'The Deferral reason already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('deferral_reason', 'The Deferral reason field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
      unauthorise_permission('263','1518');
       if(!empty($id) && $id>0)
       {
           $result = $this->deferral_reason->delete($id);
           $response = "Deferral Reason successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('263','1518');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->deferral_reason->deleteall($post['row_id']);
            $response = "Deferral Reason successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->deferral_reason->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Deferral']." detail";
        $this->load->view('blood_bank/deferral_reason/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('263','1519');
        $data['page_title'] = 'Deferral Reason archive list';
        $this->load->helper('url');
        $this->load->view('blood_bank/deferral_reason/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('263','1519');
        $this->load->model('blood_bank/deferral_reason/deferral_reason_archive_model','deferral_reason_archive'); 

        $list = $this->deferral_reason_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $deferral_reason_archive) { 
            $no++;
            $row = array();
            if($deferral_reason_archive->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$deferral_reason_archive->id.'">'.$check_script; 
            $row[] = $deferral_reason_archive->deferral_reason;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($deferral_reason_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1522',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_deferral_reason('.$deferral_reason_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1520',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$deferral_reason_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->deferral_reason_archive->count_all(),
                        "recordsFiltered" => $this->deferral_reason_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
         unauthorise_permission('263','1522');
        $this->load->model('blood_bank/deferral_reason/deferral_reason_archive_model','deferral_reason_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->deferral_reason_archive->restore($id);
           $response = "Deferral Reason successfully restore in Deferral Reason list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('263','1522');
        $this->load->model('blood_bank/deferral_reason/deferral_reason_archive_model','deferral_reason_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->deferral_reason_archive->restoreall($post['row_id']);
            $response = "Deferral Reason successfully restore in Deferral Reason list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         unauthorise_permission('263','1520');
        $this->load->model('blood_bank/deferral_reason/deferral_reason_archive_model','deferral_reason_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->deferral_reason_archive->trash($id);
           $response = "Deferral Reason successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
                unauthorise_permission('263','1520');
        $this->load->model('blood_bank/deferral_reason/deferral_reason_archive_model','deferral_reason_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->deferral_reason_archive->trashall($post['row_id']);
            $response = "Deferral Reason successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function deferral_reason_archive_dropdown()
  {

      $deferral_reason_archive_list = $this->deferral_reason_archive->deferral_reason_archive_list();
      $dropdown = '<option value="">Select Deferral Reason</option>'; 
      if(!empty($deferral_reason_archive_list))
      {
        foreach($deferral_reason_archive_list as $deferral_reason_archive)
        {
           $dropdown .= '<option value="'.$deferral_reason_archive->id.'">'.$deferral_reason_archive->deferral_reason_archive.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>