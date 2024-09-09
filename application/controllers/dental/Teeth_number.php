<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teeth_number extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/teeth_number/Teeth_number_model','teeth_number');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        
        unauthorise_permission('286','1689');
        $data['page_title'] = 'Teeth Numbers List'; 
        $this->load->view('dental/teeth_number/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('286','1689');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->teeth_number->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $teeth_number) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($teeth_number->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$teeth_number->id.'">'.$check_script; 
            $row[] = $teeth_number->number;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($teeth_number->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1691',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_teeth_number('.$teeth_number->id.');" class="btn-custom" href="javascript:void(0)" style="'.$teeth_number->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1692',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_teeth_number('.$teeth_number->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->teeth_number->count_all(),
                        "recordsFiltered" => $this->teeth_number->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
          unauthorise_permission('286','1690');
        $data['page_title'] = "Add Teeth Numbers";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'number'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->teeth_number->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/teeth_number/add',$data);       
    }
    
    
    public function edit($id="")
    {
    unauthorise_permission('286','1691');

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->teeth_number->get_by_id($id);
       
        $data['page_title'] = "Update Teeth Number";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'number'=>$result['number'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->teeth_number->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/teeth_number/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('number', 'teeth number', 'trim|required|callback_number'); 
        //callback_chief_complaints
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'number'=>$post['number'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function number($str){
 
          $post = $this->input->post();
          //print_r($post);
          //die;
          if(!empty($post['number']))
          {
               $this->load->model('dental/general/dental_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->teeth_number->get_by_id($post['data_id']);
                      if($data_cat['number']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $teeth_number = $this->general->teeth_numbers($str);

                        if(empty($teeth_number))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('number', 'The teeth number already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $teeth_number = $this->general->teeth_numbers($post['number'], $post['data_id']);
                    if(empty($teeth_number))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('number', 'The teeth number already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('number', 'teeth number field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
      unauthorise_permission('286','1692');
       if(!empty($id) && $id>0)
       {
           $result = $this->teeth_number->delete($id);
           $response = "teeth number Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
      unauthorise_permission('286','1692');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->teeth_number->deleteall($post['row_id']);
            $response = "teeth number successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->chief_complaints->get_by_id($id);  
        $data['page_title'] = $data['form_data']['chief_complaints']." detail";
        $this->load->view('dental/teeth_number/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
      unauthorise_permission('286','1693');
        $data['page_title'] = 'Teeth Numbers Archive List';
        $this->load->helper('url');
        $this->load->view('dental/teeth_number/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('286','1693');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('dental/teeth_number/Teeth_number_archive_model','teeth_number_archive'); 

        $list = $this->teeth_number_archive->get_datatables(); 
       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $teeth_number) { 
            $no++;
            $row = array();
            if($teeth_number->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$teeth_number->id.'">'.$check_script; 
            $row[] = $teeth_number->number;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($teeth_number->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1695',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_teeth_number('.$teeth_number->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1694',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$teeth_number->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
           }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->teeth_number_archive->count_all(),
                        "recordsFiltered" => $this->teeth_number_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission('286','1695');
        $this->load->model('dental/teeth_number/Teeth_number_archive_model','teeth_number_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->teeth_number_archive->restore($id);
           $response = "Teeth number successfully restore in Teeth number list.";
           echo $response;
       }
    }

    function restoreall()
    { 
      unauthorise_permission('286','1695');
          $this->load->model('dental/teeth_number/Teeth_number_archive_model','teeth_number_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->teeth_number_archive->restoreall($post['row_id']);
            $response = "Teeth number successfully restore in Teeth number list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
      unauthorise_permission('286','1696');
          $this->load->model('dental/teeth_number/Teeth_number_archive_model','teeth_number_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->teeth_number_archive->trash($id);
           $response = "Teeth number successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('286','1696');
       $this->load->model('dental/teeth_number/Teeth_number_archive_model','teeth_number_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->teeth_number_archive->trashall($post['row_id']);
            $response = "Teeth number successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function teeth_number_dropdown()
  {

      $teeth_number = $this->teeth_number->teeth_number_list();
      $dropdown = '<option value="">Select teeth number</option>'; 
      if(!empty($teeth_number))
      {
        foreach($teeth_number as $teeth_number_list)
        {
           $dropdown .= '<option value="'.$teeth_number_list->id.'">'.$teeth_number_list->number.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>