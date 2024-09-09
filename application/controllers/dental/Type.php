<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/type/type_model','typemodel');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('292','1731');
        $data['page_title'] = 'Medicine Unit List'; 
        $this->load->view('dental/type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('292','1731');
        $list = $this->typemodel->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $type) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($type->status==1)
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
            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$type->id.'">'.$check_script; 
            $row[] = $type->medicine_unit;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('1733',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_type('.$type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
         }
           if(in_array('1734',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_type('.$type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->typemodel->count_all(),
                        "recordsFiltered" => $this->typemodel->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('292','1732');
        $data['page_title'] = "Add Medicine Unit";  
        $post = $this->input->post();
        //print_r($_POST); exit;
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine_type'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                $this->typemodel->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/type/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('292','1733');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->typemodel->get_by_id($id);  
        $data['page_title'] = "Update Medicine Unit";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_type'=>$result['medicine_unit'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->typemodel->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dental/type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_type', 'medicine unit', 'trim|required|callback_medicine_type'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_type'=>$post['medicine_type'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }

   /* check medicine type exit */
   public function medicine_type($str){
          $post = $this->input->post();
          if(!empty($post['medicine_type']))
          {
               $this->load->model('dental/general/dental_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->typemodel->get_by_id($post['data_id']);
                      if($data_cat['medicine_unit']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $medicine_type = $this->general->check_medicine_unit($str);

                        if(empty($medicine_type))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('medicine_type', 'The Medicine Unit already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $medicine_type = $this->general->check_medicine_unit($post['medicine_type'], $post['data_id']);
                    if(empty($medicine_type))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('medicine_type', 'The Medicine Unit already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('medicine_type', 'The Medicine Unit field is required.');
               return false; 
          } 
     }
     /* check medicine type exit */
     
 
    public function delete($id="")
    {
       unauthorise_permission('292','1734');
       if(!empty($id) && $id>0)
       {
           $result = $this->typemodel->delete($id);
           $response = "Medicine Unit successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('292','1734');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->typemodel->deleteall($post['row_id']);
            $response = "Medicine Unit successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->typemodel->get_by_id($id);  
        $data['page_title'] = $data['form_data']['type']." detail";
        $this->load->view('eye/type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('292','1735');
        $data['page_title'] = 'Medicine Unit Archive List';
        $this->load->helper('url');
        $this->load->view('dental/type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('292','1735');
        $this->load->model('dental/type/type_archive_model','type_archive'); 

        $list = $this->type_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $type) { 
            $no++;
            $row = array();
            if($type->status==1)
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
            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$type->id.'">'.$check_script; 
            $row[] = $type->medicine_unit;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1737',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_type('.$type->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1736',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->type_archive->count_all(),
                        "recordsFiltered" => $this->type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('292','1737');
        $this->load->model('dental/type/type_archive_model','type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->type_archive->restore($id);
           $response = "Medicine Unit successfully restore in Medicine unit list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('292','1737');
        $this->load->model('dental/type/type_archive_model','type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->type_archive->restoreall($post['row_id']);
            $response = "Medicine Unit successfully restore in Medicine unit list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('292','1736');
        $this->load->model('dental/type/type_archive_model','type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->type_archive->trash($id);
           $response = "Medicine Unit successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('292','1736');
        $this->load->model('dental/type/type_archive_model','type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->type_archive->trashall($post['row_id']);
            $response = "Medicine Unit successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function type_dropdown()
  {

      $type_list = $this->typemodel->type_list();
      $dropdown = '<option value="">Select Type</option>'; 
      if(!empty($type_list))
      {
        foreach($type_list as $type)
        {
           $dropdown .= '<option value="'.$type->id.'">'.$type->type.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>