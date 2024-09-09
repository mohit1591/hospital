<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operation_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('operation_type/operation_type_model','operation_type');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('193','1140');
        $data['page_title'] = 'Operation type List'; 
        $this->load->view('operation_type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('193','1140');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->operation_type->get_datatables();
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $operation_type) {
         // print_r($operation_type);die;
            $no++;
            $row = array();
            if($operation_type->status==1)
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
            $checkboxs = "";
            if($users_data['parent_id']==$operation_type->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$operation_type->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $operation_type->operation_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($operation_type->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$operation_type->branch_id)
            {
              if(in_array('1138',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_operation('.$operation_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$operation_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
              if(in_array('1137',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_operation_type('.$operation_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->operation_type->count_all(),
                        "recordsFiltered" => $this->operation_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function check_operation_type($str)
    {

      $post = $this->input->post();

      if(!empty($str))
      {
          $this->load->model('general/general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                $data_cat= $this->operation_type->get_by_id($post['data_id']);
                if($data_cat['operation_type']==$str && $post['data_id']==$data_cat['id'])
                {
                return true;  
                }
                else
                {
                $operationdata = $this->general->check_operation_type($str);

                if(empty($operationdata))
                {
                return true;
                }
                else
                {
                $this->form_validation->set_message('check_operation_type', 'The operation type already exists.');
                return false;
                }
                }
          }
          else
          {
                  $operationdata = $this->general->check_operation_type($str);
                  if(empty($operationdata))
                  {
                     return true;
                  }
                  else
                  {
              $this->form_validation->set_message('check_operation_type', 'The operation type already exists.');
              return false;
                  }
          }  
      }
      else
      {
        $this->form_validation->set_message('check_operation_type', 'The operation type field is required.');
              return false; 
      } 
    }
    
    
    public function add()
    {
      //echo json_encode("hi");die;
        unauthorise_permission('193','1139');
        $data['page_title'] = "Add Operation Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'operation_type'=>"",
                                  'status'=>"1"
                                   );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->operation_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('operation_type/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('193','1138');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->operation_type->get_by_id($id);  
        $data['page_title'] = "Update Operation Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'operation_type'=>$result['operation_type'], 
                                  'status'=>$result['status']
                                   );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->operation_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('operation_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('operation_type', 'operation type', 'trim|required|callback_check_operation_type'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'operation_type'=>$post['operation_type'], 
                                        'status'=>$post['status']
                                         ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('193','1137');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->operation_type->delete($id);
           $response = "Operation Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('193','1137');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->operation_type->deleteall($post['row_id']);
            $response = "Operation type successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->operation_type->get_by_id($id);  
        $data['page_title'] = $data['form_data']['operation_type']." detail";
        $this->load->view('operation_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('193','1136');
        $data['page_title'] = 'Operation Type Archive List';
        $this->load->helper('url');
        $this->load->view('operation_type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('193','1136');
        $this->load->model('operation_type/operation_type_archive_model','operation_type_archive'); 

      
               $list = $this->operation_type_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $operation_type) {
         // print_r($operation_type);die;
            $no++;
            $row = array();
            if($operation_type->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$operation_type->id.'">'.$check_script; 
            $row[] = $operation_type->operation_type;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($operation_type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('70',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_operation_type('.$operation_type->id.');" class="btn-custom" href="javascript:void(0)"  title="Operation Type"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('69',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$operation_type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->operation_type_archive->count_all(),
                        "recordsFiltered" => $this->operation_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('193','1134');
        $this->load->model('operation_type/operation_type_archive_model','operation_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->operation_type_archive->restore($id);
           $response = "Operation Type successfully restore in Operation Type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('193','1134');
        $this->load->model('operation_type/operation_type_archive_model','operation_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->operation_type_archive->restoreall($post['row_id']);
            $response = "Operation type successfully restore in Operation Type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('193','1135');
        $this->load->model('operation_type/operation_type_archive_model','operation_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->operation_type_archive->trash($id);
           $response = "Operation Type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('193','1135');
        $this->load->model('operation_type/operation_type_archive_model','operation_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->operation_type_archive->trashall($post['row_id']);
            $response = "Operation Type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function simulation_dropdown()
  {
     $simulation_list = $this->operation_type->simulation_list();
     $dropdown = '<option value="">Select Operation Type</option>'; 
     $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $operation_type)
          {
               if(in_array($operation_type->operation_type,$simulations_array)){
                    $selected_simulation = 'selected="selected"';
               }
               else
               {
                  $selected_simulation = '';  
               }
               $dropdown .= '<option value="'.$operation_type->id.'" '.$selected_simulation.' >'.$operation_type->operation_type.'</option>';
          }
     } 
     echo $dropdown; 
  }
  */
   public function operation_type_dropdown()
  {
      $ot_type_list = $this->operation_type->operation_type_list();
      //print_r($ot_type_list);die;
      $dropdown = '<option value="">Select Operation Type</option>'; 
      if(!empty($ot_type_list))
      {
        foreach($ot_type_list as $ot_type)
        {
           $dropdown .= '<option value="'.$ot_type->id.'">'.$ot_type->operation_type.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>