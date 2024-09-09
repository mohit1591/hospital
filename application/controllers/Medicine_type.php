<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('medicine_type/medicine_type_model','medicine_type');
        $this->load->library('form_validation');
    }

    public function index()
    { 
     
       unauthorise_permission('53','347');
        $data['page_title'] = 'Medicine Type'; 
        $this->load->view('medicine_type/list',$data);
    }

    public function ajax_list()
    { 
       unauthorise_permission('53','347');
        $list = $this->medicine_type->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine_unit) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($medicine_unit->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine_unit->id.'">'.$check_script; 
            $row[] = $medicine_unit->medicine_type_name;  
            $row[] = $status;
           // $row[] = date('d-M-Y H:i A',strtotime($medicine_unit->created_date)); 
 
       $users_data = $this->session->userdata('auth_users');
       $btnedit='';
       $btndelete='';
      if(in_array('349',$users_data['permission']['action'])){
          $btnedit = '<a onClick="return edit_medicine_type('.$medicine_unit->id.');" class="btn-custom" href="javascript:void(0)" style="'.$medicine_unit->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
       }
        
        if(in_array('350',$users_data['permission']['action'])){
               $btndelete = '<a class="btn-custom" onClick="return delete_medicine_type('.$medicine_unit->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
            }
          $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_type->count_all(),
                        "recordsFiltered" => $this->medicine_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('53','348');
        $data['page_title'] = "Add Medicine Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine_type_name'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('medicine_type/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('53','349');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->medicine_type->get_by_id($id);  
        $data['page_title'] = "Update Medicine Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_type_name'=>$result['medicine_type_name'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('medicine_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_type_name', 'medicine type', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
        
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_type_name'=>$post['medicine_type_name'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {  
       unauthorise_permission('53','350');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_type->delete($id);
           $response = "Medicine type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('53','350');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_type->deleteall($post['row_id']);
            $response = "Medicine type successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_type->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_type_name']." detail";
        $this->load->view('medicine_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('53','416');
        $data['page_title'] = 'Unit Archive List';
        $this->load->helper('url');
        $this->load->view('medicine_type/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('53','416');
        $this->load->model('medicine_type/medicine_type_archive_model','medicine_type_archive'); 

        $list = $this->medicine_type_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine_unit) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($medicine_unit->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine_unit->id.'">'.$check_script; 
            $row[] = $medicine_unit->medicine_type_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($medicine_unit->created_date)); 
           $users_data = $this->session->userdata('auth_users');

           $btnrestore='';
           $btndelete='';
         if(in_array('353',$users_data['permission']['action'])){
             $btnrestore = ' <a onClick="return restore_medicine_type('.$medicine_unit->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';      
         }
         if(in_array('352',$users_data['permission']['action'])){
      $btndelete = '<a onClick="return trash('.$medicine_unit->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
         }
             
          $row[] = $btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_type_archive->count_all(),
                        "recordsFiltered" => $this->medicine_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('53','353');
        $this->load->model('medicine_type/medicine_type_archive_model','medicine_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_type_archive->restore($id);
           $response = "Medicine unit successfully restore in unit list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('53','353');
        $this->load->model('medicine_type/medicine_type_archive_model','medicine_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_type_archive->restoreall($post['row_id']);
            $response = "Medicine type successfully restore in unit list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('53','352');
        $this->load->model('medicine_type/medicine_type_archive_model','medicine_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_type_archive->trash($id);
           $response = "Medicine type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('53','352');
        $this->load->model('medicine_type/medicine_type_archive_model','medicine_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_type_archive->trashall($post['row_id']);
            $response = "Medicine type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function medicine_type_dropdown()
  {
      $medicine_unit_list = $this->medicine_type->medicine_type_list();
      $dropdown = '<option value="">Select Type</option>'; 
      $dropdown .= '<option selected value="0">Normal</option>'; 
      if(!empty($medicine_unit_list))
      {
        foreach($medicine_unit_list as $medicine_unit)
        {
           $dropdown .= '<option value="'.$medicine_unit->id.'">'.$medicine_unit->medicine_type_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>