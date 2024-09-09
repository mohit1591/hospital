<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advice extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pediatrician/advice/advice_model','advice');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('329','1950');
        $data['page_title'] = 'Advice List'; 
        $this->load->view('pediatrician/advice/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('329','1950');
        $list = $this->advice->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $advice) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($advice->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////medicine_advice
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$advice->id.'">'.$check_script; 
            $row[] = $advice->medicine_advice;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($advice->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('1952',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_advice('.$advice->id.');" class="btn-custom" href="javascript:void(0)" style="'.$advice->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1953',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_advice('.$advice->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->advice->count_all(),
                        "recordsFiltered" => $this->advice->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('329','1951');
        $data['page_title'] = "Add Advice";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine_advice'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->advice->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/advice/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('329','1952');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->advice->get_by_id($id);  
        $data['page_title'] = "Update Advice";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_advice'=>$result['medicine_advice'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->advice->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/advice/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_advice', 'advice', 'trim|required|callback_medicine_advice'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_advice'=>$post['medicine_advice'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
     /* callbackurl */
    /* check validation laready exit */
   public function medicine_advice($str){
          $post = $this->input->post();
          if(!empty($post['medicine_advice']))
          {
               $this->load->model('pediatrician/general/pediatrician_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->advice->get_by_id($post['data_id']);
                      if($data_cat['medicine_advice']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $medicine_advice = $this->general->check_medicine_advice($str);

                        if(empty($medicine_advice))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('medicine_advice', 'The medicine advice already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_medicine_advice($post['medicine_advice'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('medicine_advice', 'The medicine advice already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('medicine_advice', 'The medicine advice field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('329','1953');
       if(!empty($id) && $id>0)
       {
           $result = $this->advice->delete($id);
           $response = "Advice successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('329','1953');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advice->deleteall($post['row_id']);
            $response = "Advice successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->advice->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_advice']." detail";
        $this->load->view('pediatrician/advice/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('329','1954');
        $data['page_title'] = 'Advice archive list';
        $this->load->helper('url');
        $this->load->view('pediatrician/advice/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('329','1954');
        $this->load->model('pediatrician/advice/advice_archive_model','advice_archive'); 

        $list = $this->advice_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $advice) { 
            $no++;
            $row = array();
            if($advice->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$advice->id.'">'.$check_script; 
            $row[] = $advice->medicine_advice;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($advice->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1956',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_advice('.$advice->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1955',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$advice->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->advice_archive->count_all(),
                        "recordsFiltered" => $this->advice_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
        unauthorise_permission('329','1956');
        $this->load->model('pediatrician/advice/advice_archive_model','advice_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->advice_archive->restore($id);
           $response = "Advice successfully restore in Advice list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('329','1956');
        $this->load->model('pediatrician/advice/advice_archive_model','advice_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advice_archive->restoreall($post['row_id']);
            $response = "Advice successfully restore in Advice list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('329','1955');
        $this->load->model('pediatrician/advice/advice_archive_model','advice_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->advice_archive->trash($id);
           $response = "Advice successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('329','1955');
        $this->load->model('pediatrician/advice/advice_archive_model','advice_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->advice_archive->trashall($post['row_id']);
            $response = "Advice successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function advice_dropdown()
  {

      $advice_list = $this->advice->advice_list();
      $dropdown = '<option value="">Select Advice</option>'; 
      if(!empty($advice_list))
      {
        foreach($advice_list as $advice)
        {
           $dropdown .= '<option value="'.$advice->id.'">'.$advice->medicine_advice.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>