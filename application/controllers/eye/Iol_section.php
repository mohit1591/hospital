<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iol_section extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/iol_section/Iolsection_model','iol_section');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('238','1356');
        $data['page_title'] = 'IOL Section List'; 
        $this->load->view('eye/iol_section/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('238','1356');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->iol_section->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $iol_section) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($iol_section->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$iol_section->id.'">'.$check_script; 
            $row[] = $iol_section->iol_section;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($iol_section->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1358',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_iol_section('.$iol_section->id.');" class="btn-custom" href="javascript:void(0)" style="'.$iol_section->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1359',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_iol_section('.$iol_section->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->iol_section->count_all(),
                        "recordsFiltered" => $this->iol_section->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {

        unauthorise_permission('238','1357');
        $data['page_title'] = "Add IOL Section";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'iol_section'=>"",
                                  'status'=>"1"
                                  ); 


        if(isset($post) && !empty($post))
        {   

            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                $this->iol_section->save();
                echo 1;
                return false;
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/iol_section/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('238','1358');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->iol_section->get_by_id($id);  
        $data['page_title'] = "Update IOL Section";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'iol_section'=>$result['iol_section'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   

            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->iol_section->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/iol_section/add',$data);       
      }
    }
     
    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('iol_section', 'iol section', 'trim|required|callback_iol_section'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'iol_section'=>$post['iol_section'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function iol_section($str){
 
          $post = $this->input->post();
          if(!empty($post['iol_section']))
          {
               $this->load->model('eye/general/eye_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->iol_section->get_by_id($post['data_id']);
                      if($data_cat['iol_section']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_iol_section = $this->general->check_iol_section($str);

                        if(empty($check_iol_section))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('iol_section', 'The iol section already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $check_iol_section = $this->general->check_iol_section($post['iol_section'], $post['data_id']);
                    if(empty($check_iol_section))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('iol_section', 'The iol section already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('iol_section', 'The iol section field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('238','1359');
       if(!empty($id) && $id>0)
       {
           $result = $this->iol_section->delete($id);
           $response = "IOL section Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('238','1359');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->iol_section->deleteall($post['row_id']);
            $response = "IOL section successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->iol_section->get_by_id($id);  
        $data['page_title'] = $data['form_data']['iol_section']." detail";
        $this->load->view('eye/iol_section/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('238','1360');
        $data['page_title'] = 'IOL Section Archive List';
        $this->load->helper('url');
        $this->load->view('eye/iol_section/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('238','1360');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('eye/iol_section/iolsection_archive_model','iolsection_archive'); 

        $list = $this->iolsection_archive->get_datatables(); 

       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $iol_section) { 
            $no++;
            $row = array();
            if($iol_section->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$iol_section->id.'">'.$check_script; 
            $row[] = $iol_section->iol_section;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($iol_section->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1362',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_iol_section('.$iol_section->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1361',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$iol_section->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
         }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->iolsection_archive->count_all(),
                        "recordsFiltered" => $this->iolsection_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('238','1362');
        $this->load->model('eye/iol_section/iolsection_archive_model','iolsection_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->iolsection_archive->restore($id);
           $response = "IOL section successfully restore in IOL section list.";
           echo $response;
       }
    }

    function restoreall()
    { 
         unauthorise_permission('238','1362');
         $this->load->model('eye/iol_section/iolsection_archive_model','iolsection_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->iolsection_archive->restoreall($post['row_id']);
            $response = "IOL section successfully restore in IOL section list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('238','1361');
         $this->load->model('eye/iol_section/iolsection_archive_model','iolsection_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->iolsection_archive->trash($id);
           $response = "IOL section successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('238','1361');
        $this->load->model('eye/iol_section/iolsection_archive_model','iolsection_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->iolsection_archive->trashall($post['row_id']);
            $response = "IOL section successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function iol_section_dropdown()
  {

      $iol_section_list = $this->iol_section->iolsection_list();
      $dropdown = '<option value="">Select iol section list</option>'; 
      if(!empty($iol_section_list))
      {
        foreach($iol_section_list as $iol_section)
        {
           $dropdown .= '<option value="'.$iol_section->id.'">'.$iol_section->iol_section.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>