<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keratometer extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/keratometer/Keratometer_model','keratometer');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('239','1363');
        $data['page_title'] = 'Keratometer List'; 
        $this->load->view('eye/keratometer/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('239','1363');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->keratometer->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $keratometer) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($keratometer->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$keratometer->id.'">'.$check_script; 
            $row[] = $keratometer->keratometer;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($keratometer->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1365',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_keratometer('.$keratometer->id.');" class="btn-custom" href="javascript:void(0)" style="'.$keratometer->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1366',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_keratometer('.$keratometer->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->keratometer->count_all(),
                        "recordsFiltered" => $this->keratometer->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {

        unauthorise_permission('239','1364');
        $data['page_title'] = "Add Keratometer";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'keratometer'=>"",
                                  'status'=>"1"
                                  ); 


        if(isset($post) && !empty($post))
        {   

            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                $this->keratometer->save();
                echo 1;
                return false;
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/keratometer/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('239','1365');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->keratometer->get_by_id($id);  
        $data['page_title'] = "Update Keratometer";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'keratometer'=>$result['keratometer'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   

            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->keratometer->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/keratometer/add',$data);       
      }
    }
     
    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('keratometer', 'keratometer', 'trim|required|callback_keratometer'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'keratometer'=>$post['keratometer'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function keratometer($str){
 
          $post = $this->input->post();
          if(!empty($post['keratometer']))
          {
               $this->load->model('eye/general/eye_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->keratometer->get_by_id($post['data_id']);
                      if($data_cat['keratometer']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_keratometer = $this->general->check_keratometer($str);

                        if(empty($check_keratometer))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('keratometer', 'The keratometer already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $check_keratometer = $this->general->check_keratometer($post['keratometer'], $post['data_id']);
                    if(empty($check_keratometer))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('keratometer', 'The keratometer already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('keratometer', 'The keratometer field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('239','1366');
       if(!empty($id) && $id>0)
       {
           $result = $this->keratometer->delete($id);
           $response = "Karatometer Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('239','1366');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->keratometer->deleteall($post['row_id']);
            $response = "Keratometer successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->keratometer->get_by_id($id);  
        $data['page_title'] = $data['form_data']['keratometer']." detail";
        $this->load->view('eye/keratometer/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('239','1367');
        $data['page_title'] = 'Keratometer Archive List';
        $this->load->helper('url');
        $this->load->view('eye/keratometer/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('239','1367');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('eye/keratometer/keratometer_archive_model','keratometer_archive'); 

        $list = $this->keratometer_archive->get_datatables(); 

       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $keratometer) { 
            $no++;
            $row = array();
            if($keratometer->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$keratometer->id.'">'.$check_script; 
            $row[] = $keratometer->keratometer;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($keratometer->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1369',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_keratometer('.$keratometer->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1368',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$keratometer->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
         }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->keratometer_archive->count_all(),
                        "recordsFiltered" => $this->keratometer_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('239','1369');
        $this->load->model('eye/keratometer/keratometer_archive_model','keratometer_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->keratometer_archive->restore($id);
           $response = "Keratometer successfully restore in Keratometer list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('239','1369');
         $this->load->model('eye/keratometer/keratometer_archive_model','keratometer_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->keratometer_archive->restoreall($post['row_id']);
            $response = "Keratometer successfully restore in keratometer section list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('239','1368');
         $this->load->model('eye/keratometer/keratometer_archive_model','keratometer_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->keratometer_archive->trash($id);
           $response = "Keratometer successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('239','1368');
        $this->load->model('eye/keratometer/keratometer_archive_model','keratometer_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->keratometer_archive->trashall($post['row_id']);
            $response = "Keratometer successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function keratometer_dropdown()
  {

      $keratometer_list = $this->keratometer->keratometer_list();
      $dropdown = '<option value="">Select keratometer list</option>'; 
      if(!empty($keratometer_list))
      {
        foreach($keratometer_list as $keratometer)
        {
           $dropdown .= '<option value="'.$keratometer->id.'">'.$keratometer->keratometer.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>