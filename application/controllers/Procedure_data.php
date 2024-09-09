<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Procedure_data extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('procedure_data/procedure_data_model','procedure_data');
        $this->load->library('form_validation');
    }

    public function index()
    {  
        unauthorise_permission(10,50);
        $data['page_title'] = 'Procedure Data List'; 
        $this->load->view('procedure_data/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(10,50);
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
    
       
            $list = $this->procedure_data->get_datatables();
     
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $religion) {
         // print_r($religion);die;
            $no++;
            $row = array();
            if($religion->status==1)
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
            if($users_data['parent_id']==$religion->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$religion->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $religion->name;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($religion->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
            if($users_data['parent_id']==$religion->branch_id){
              
                 if(in_array('52',$users_data['permission']['action'])) 
                 {
                    
                    $btnedit = ' <a onClick="return edit_religion('.$religion->id.');" class="btn-custom" href="javascript:void(0)" style="'.$religion->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                 }
                 if(in_array('53',$users_data['permission']['action'])) 
                 {
                    $btndelete = ' <a class="btn-custom" onClick="return delete_religion('.$religion->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                 } 
            }

            // End Action Button //
            
    
             $row[] = $btnedit.$btndelete;
             
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->procedure_data->count_all(),
                        "recordsFiltered" => $this->procedure_data->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission(10,51); 
        $data['page_title'] = "Add Procedure Data";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'name'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->procedure_data->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('procedure_data/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission(10,52); 
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->procedure_data->get_by_id($id);  
        $data['page_title'] = "Update Procedure Data";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'religion'=>$result['religion'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->procedure_data->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('procedure_data/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'religion'=>$post['religion'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(10,53); 
       if(!empty($id) && $id>0)
       {
           $result = $this->procedure_data->delete($id);
           $response = "Procedure Data successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(10,53); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->procedure_data->deleteall($post['row_id']);
            $response = "Procedure Datas successfully deleted.";
            echo $response;
        }
    }
 


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(10,54); 
        $data['page_title'] = 'Procedure Data Archive List';
        $this->load->helper('url');
        $this->load->view('procedure_data/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(10,54);
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('procedure_data/procedure_data_archive_model','procedure_data_archive'); 

        

               $list = $this->procedure_data_archive->get_datatables();
              
            
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $religion) {
         // print_r($religion);die;
            $no++;
            $row = array();
            if($religion->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$religion->id.'">'.$check_script; 
            $row[] = $religion->religion;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($religion->created_date)); 
 
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";

            if(in_array('56',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_religion('.$religion->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            
            if(in_array('55',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$religion->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //
 
           $row[] = $btn_restore.$btn_delete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->procedure_data_archive->count_all(),
                        "recordsFiltered" => $this->procedure_data_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        $this->load->model('procedure_data/procedure_data_archive_model','procedure_data_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->procedure_data_archive->restore($id);
           $response = "Procedure Data successfully restore in Procedure Data list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        $this->load->model('procedure_data/procedure_data_archive_model','procedure_data_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->procedure_data_archive->restoreall($post['row_id']);
            $response = "Procedure Datas successfully restore in Procedure Data list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        $this->load->model('procedure_data/procedure_data_archive_model','procedure_data_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->procedure_data_archive->trash($id);
           $response = "Procedure Data successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        $this->load->model('procedure_data/procedure_data_archive_model','procedure_data_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->procedure_data_archive->trashall($post['row_id']);
            $response = "Procedure Data successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function procedure_data_dropdown()
  {
      $religion_list = $this->procedure_data->procedure_data_list();
      $dropdown = '<option value="">Select Procedure Data</option>'; 
      if(!empty($religion_list))
      {
        foreach($religion_list as $religion)
        {
           $dropdown .= '<option value="'.$religion->id.'">'.$religion->name.'</option>';
        }
      } 
      echo $dropdown; 
  }
 
  function check_unique_value($religion, $id='') 
      {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->procedure_data->check_unique_value($users_data['parent_id'], $religion,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Procedure Data already exist.');
            $response = false;
        }
        return $response;
      }
   

}
?>