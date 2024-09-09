<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('unit/unit_model','unit');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('142','848');
        $data['page_title'] = 'Unit list'; 
        $this->load->view('unit/list',$data);
    }

    public function ajax_list()
    { 
    $users_data = $this->session->userdata('auth_users');
    unauthorise_permission('142','848');

    $sub_branch_details = $this->session->userdata('sub_branches_data');
    $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->unit->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $unit) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($unit->status==1)
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
            if($users_data['parent_id']==$unit->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$unit->id.'">'.$check_script;
            } 
            else{
               $row[]='';
            }
            $row[] = $unit->unit;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($unit->created_date)); 
 
       
       $btnedit='';
       $btndelete='';
       
       if($users_data['parent_id']==$unit->branch_id){
          if(in_array('850',$users_data['permission']['action'])){
               $btnedit = '<a onClick="return edit_unit('.$unit->id.');" class="btn-custom" href="javascript:void(0)" style="'.$unit->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('851',$users_data['permission']['action'])){
               $btndelete = '<a class="btn-custom" onClick="return delete_unit('.$unit->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
          }
      }
      
             $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->unit->count_all(),
                        "recordsFiltered" => $this->unit->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('142','849');
        $data['page_title'] = "Add unit";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'unit'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->unit->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('unit/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('142','850');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->unit->get_by_id($id);  
        $data['page_title'] = "Update unit";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'unit'=>$result['unit'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->unit->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('unit/add',$data);       
      }
    }
     
    private function _validate()
    {
    
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('unit', 'unit', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'unit'=>$post['unit'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {  
       unauthorise_permission('142','851');
       if(!empty($id) && $id>0)
       {
           $result = $this->unit->delete($id);
           $response = "Unit successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('142','851');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->unit->deleteall($post['row_id']);
            $response = "Units successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->unit->get_by_id($id);  
        $data['page_title'] = $data['form_data']['unit']." detail";
        $this->load->view('unit/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('142','852');
        $data['page_title'] = 'unit archive list';
        $this->load->helper('url');
        $this->load->view('unit/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('142','852');
        $this->load->model('unit/unit_archive_model','unit_archive'); 
        $list = $this->unit_archive->get_datatables();
              
                  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $unit) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($unit->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$unit->id.'">'.$check_script; 
            $row[] = $unit->unit;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($unit->created_date)); 
           $users_data = $this->session->userdata('auth_users');

           $btnrestore='';
           $btndelete='';
          if(in_array('854',$users_data['permission']['action'])){
             $btnrestore = ' <a onClick="return restore_unit('.$unit->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';      
          }
          if(in_array('853',$users_data['permission']['action'])){
      $btndelete = '<a onClick="return trash('.$unit->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
             
          $row[] = $btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->unit_archive->count_all(),
                        "recordsFiltered" => $this->unit_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('142','854');
        $this->load->model('unit/unit_archive_model','unit_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->unit_archive->restore($id);
           $response = "unit successfully restore in unit list.";
           echo $response;
       }
    }

    function restoreall()
    { 
     unauthorise_permission('142','854');
        $this->load->model('unit/unit_archive_model','unit_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->unit_archive->restoreall($post['row_id']);
            $response = "Units successfully restore in unit list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('142','853');
        $this->load->model('unit/unit_archive_model','unit_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->unit_archive->trash($id);
           $response = "Unit successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('142','853');
        $this->load->model('unit/unit_archive_model','unit_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->unit_archive->trashall($post['row_id']);
            $response = "Units successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function unit_dropdown()
  {
      $unit_list = $this->unit->unit_list();
      $dropdown = '<option value="">Select Unit</option>'; 
      if(!empty($unit_list))
      {
        foreach($unit_list as $unit)
        {
           $dropdown .= '<option value="'.$unit->id.'">'.$unit->unit.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  

}
?>