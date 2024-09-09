<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dialysis_type/dialysis_type_model','dialysis_type');
        $this->load->library('form_validation');
    }

    public function index()
    { 
      //echo "hi";die;
        unauthorise_permission('211','1155');
        $data['page_title'] = 'dialysis type List'; 
        $this->load->view('dialysis_type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('211','1155');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->dialysis_type->get_datatables();
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $dialysis_type) {
         // print_r($dialysis_type);die;
            $no++;
            $row = array();
            if($dialysis_type->status==1)
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
            if($users_data['parent_id']==$dialysis_type->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysis_type->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $dialysis_type->dialysis_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dialysis_type->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$dialysis_type->branch_id)
            {
            if(in_array('1157',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_dialysis('.$dialysis_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$dialysis_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
         }
             if(in_array('1158',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_dialysis_type('.$dialysis_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dialysis_type->count_all(),
                        "recordsFiltered" => $this->dialysis_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function check_dialysis_type($str)
    {

      $post = $this->input->post();

      if(!empty($str))
      {
          $this->load->model('general/general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                $data_cat= $this->dialysis_type->get_by_id($post['data_id']);
                if($data_cat['dialysis_type']==$str && $post['data_id']==$data_cat['id'])
                {
                return true;  
                }
                else
                {
                $dialysisdata = $this->general->check_dialysis_type($str);

                if(empty($dialysisdata))
                {
                return true;
                }
                else
                {
                $this->form_validation->set_message('check_dialysis_type', 'The dialysis type already exists.');
                return false;
                }
                }
          }
          else
          {
                  $dialysisdata = $this->general->check_dialysis_type($str);
                  if(empty($dialysisdata))
                  {
                     return true;
                  }
                  else
                  {
              $this->form_validation->set_message('check_dialysis_type', 'The dialysis type already exists.');
              return false;
                  }
          }  
      }
      else
      {
        $this->form_validation->set_message('check_dialysis_type', 'The dialysis type field is required.');
              return false; 
      } 
    }
    
    
    public function add()
    {
      //echo json_encode("hi");die;
        unauthorise_permission('211','1156');
        $data['page_title'] = "Add dialysis Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'dialysis_type'=>"",
                                  'status'=>"1"
                                   );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dialysis_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dialysis_type/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('211','1157');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->dialysis_type->get_by_id($id);  
        $data['page_title'] = "Update dialysis Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'dialysis_type'=>$result['dialysis_type'], 
                                  'status'=>$result['status']
                                   );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dialysis_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dialysis_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('dialysis_type', 'dialysis type', 'trim|required|callback_check_dialysis_type'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'dialysis_type'=>$post['dialysis_type'], 
                                        'status'=>$post['status']
                                         ); 
            return $data['form_data'];
        }   
    }

   
    public function delete($id="")
    {
       unauthorise_permission('211','1158');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->dialysis_type->delete($id);
           $response = "dialysis Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('211','1158');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_type->deleteall($post['row_id']);
            $response = "dialysis type successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->dialysis_type->get_by_id($id);  
        $data['page_title'] = $data['form_data']['dialysis_type']." detail";
        $this->load->view('dialysis_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('211','1159');
        $data['page_title'] = 'dialysis Type Archive List';
        $this->load->helper('url');
        $this->load->view('dialysis_type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('211','1159');
        $this->load->model('dialysis_type/dialysis_type_archive_model','dialysis_type_archive'); 

      
               $list = $this->dialysis_type_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $dialysis_type) {
         // print_r($dialysis_type);die;
            $no++;
            $row = array();
            if($dialysis_type->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dialysis_type->id.'">'.$check_script; 
            $row[] = $dialysis_type->dialysis_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dialysis_type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1161',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_dialysis_type('.$dialysis_type->id.');" class="btn-custom" href="javascript:void(0)"  title="dialysis Type"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1160',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$dialysis_type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dialysis_type_archive->count_all(),
                        "recordsFiltered" => $this->dialysis_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('211','1161');
        $this->load->model('dialysis_type/dialysis_type_archive_model','dialysis_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_type_archive->restore($id);
           $response = "Dialysis Type successfully restore in dialysis Type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('211','1161');
        $this->load->model('dialysis_type/dialysis_type_archive_model','dialysis_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_type_archive->restoreall($post['row_id']);
            $response = "dialysis type successfully restore in dialysis Type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('211','1160');
        $this->load->model('dialysis_type/dialysis_type_archive_model','dialysis_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_type_archive->trash($id);
           $response = "dialysis Type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('211','1160');
        $this->load->model('dialysis_type/dialysis_type_archive_model','dialysis_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_type_archive->trashall($post['row_id']);
            $response = "dialysis Type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function simulation_dropdown()
  {
     $simulation_list = $this->dialysis_type->simulation_list();
     $dropdown = '<option value="">Select dialysis Type</option>'; 
     $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $dialysis_type)
          {
               if(in_array($dialysis_type->dialysis_type,$simulations_array)){
                    $selected_simulation = 'selected="selected"';
               }
               else
               {
                  $selected_simulation = '';  
               }
               $dropdown .= '<option value="'.$dialysis_type->id.'" '.$selected_simulation.' >'.$dialysis_type->dialysis_type.'</option>';
          }
     } 
     echo $dropdown; 
  }
  */
   public function dialysis_type_dropdown()
  {
      $dialysis_type_list = $this->dialysis_type->dialysis_type_list();
      //print_r($dialysis_type_list);die;
      $dropdown = '<option value="">Select dialysis Type</option>'; 
      if(!empty($dialysis_type_list))
      {
        foreach($dialysis_type_list as $dialysis_type)
        {
           $dropdown .= '<option value="'.$dialysis_type->id.'">'.$dialysis_type->dialysis_type.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>