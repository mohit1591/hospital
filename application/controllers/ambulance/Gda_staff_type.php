<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gda_staff_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ambulance/gda_staff_type/gda_staff_type_model','gda_staff_type','gda_staff_type_archive_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(7,30);   
        $data['page_title'] = 'Gda staff type department List';   
        $this->load->view('ambulance/gda_staff_type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(7,30);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->gda_staff_type->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $gda_staff_type) {
         // print_r($gda_staff_type);die;
            $no++;
            $row = array();
            if($gda_staff_type->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($gda_staff_type->state))
            {
                $state = " ( ".ucfirst(strtolower($gda_staff_type->state))." )";
            }
            //////////////////////// 
            
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
            if($users_data['parent_id']==$gda_staff_type->branch_id){ 
                $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$gda_staff_type->id.'">'.$check_script; 
            }else{
                $row[]='';
            }
            $row[] = $gda_staff_type->department;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($gda_staff_type->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
             if($users_data['parent_id']==$gda_staff_type->branch_id){
                if(in_array('32',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_gda_staff_type('.$gda_staff_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$gda_staff_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                if(in_array('33',$users_data['permission']['action'])) 
                {
                   $btndelete = ' <a class="btn-custom" onClick="return delete_emp_type('.$gda_staff_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->gda_staff_type->count_all(),
                        "recordsFiltered" => $this->gda_staff_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission(7,31);
        $data['page_title'] = "Add GDA staff type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'department'=>"",
                                  'depart_short_name'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->gda_staff_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/gda_staff_type/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission(7,32);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->gda_staff_type->get_by_id($id); 
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->gda_staff_type->gda_staff_type_list();  
        $data['page_title'] = "Update Gda staff type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'department'=>$result['department'],
                                  'depart_short_name'=>$result['depart_short_name'],
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->gda_staff_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/gda_staff_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('department', 'GDA Staff type', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'department'=>$post['department'], 
                                        'depart_short_name'=>$post['depart_short_name'],
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(7,33);
       if(!empty($id) && $id>0)
       {
           $result = $this->gda_staff_type->delete($id);
           $response = "GDA Staff type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(7,33);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->gda_staff_type->deleteall($post['row_id']);
            $response = "GDA Staff types successfully deleted.";
            echo $response;
        }
    }

     


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(7,34);
        $data['page_title'] = 'GDA Staff type Archive List';
        $this->load->helper('url');
        $this->load->view('ambulance/gda_staff_type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(7,34);
        $this->load->model('ambulance/gda_staff_type/gda_staff_type_archive_model','gda_staff_type_archive'); 
        $users_data = $this->session->userdata('auth_users');
        

               $list = $this->gda_staff_type_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $gda_staff_type) {
         // print_r($gda_staff_type);die;
            $no++;
            $row = array();
            if($gda_staff_type->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($gda_staff_type->state))
            {
                $state = " ( ".ucfirst(strtolower($gda_staff_type->state))." )";
            }
            //////////////////////// 
            
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$gda_staff_type->id.'">'.$check_script; 
            $row[] = $gda_staff_type->department;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($gda_staff_type->created_date)); 
            
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if(in_array('36',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_gda_staff_type('.$gda_staff_type->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            if(in_array('35',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$gda_staff_type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //


      $row[] = $btn_restore.$btn_delete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->gda_staff_type_archive->count_all(),
                        "recordsFiltered" => $this->gda_staff_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(7,36);
        $this->load->model('ambulance/gda_staff_type/gda_staff_type_archive_model','gda_staff_type_archive_model');
       if(!empty($id) && $id>0)
       {
           $result = $this->gda_staff_type_archive_model->restore($id);
           $response = "GDA Staff type successfully restore in GDA Staff type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(7,36);
        $this->load->model('ambulance/gda_staff_type/gda_staff_type_archive_model','gda_staff_type_archive_model');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->gda_staff_type_archive_model->restoreall($post['row_id']);
            $response = "GDA Staff types successfully restore in GDA Staff type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(7,35);
        $this->load->model('ambulance/gda_staff_type/gda_staff_type_archive_model','gda_staff_type_archive_model');
       if(!empty($id) && $id>0)
       {
           $result = $this->gda_staff_type_archive_model->trash($id);
           $response = "GDA Staff type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(7,35);
        $this->load->model('ambulance/gda_staff_type/gda_staff_type_archive_model','gda_staff_type_archive_model');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->gda_staff_type_archive_model->trashall($post['row_id']);
            $response = "GDA Staff type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function gda_staff_type_dropdown()
  {
      $gda_staff_type_list = $this->gda_staff_type->gda_staff_type_list();
      $dropdown = '<option value="">Select GDA Staff type</option>'; 
      if(!empty($gda_staff_type_list))
      {
        foreach($gda_staff_type_list as $gda_staff_type)
        {
           $dropdown .= '<option value="'.$gda_staff_type->id.'">'.$gda_staff_type->department.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>