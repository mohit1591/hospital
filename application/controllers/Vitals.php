<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitals extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('vitals/vitals_model','vitals_field');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('189','1111');
        $data['page_title'] = 'Vitals'; 
        $this->load->view('vitals/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('189','1111');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->vitals_field->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
       
        $total_num = count($list);
        foreach ($list as $vital_list) {
         // print_r($simulation);die;
            $no++;
            $row = array();
              if($vital_list->status==1)
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
            if($users_data['parent_id']==$vital_list->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vital_list->id.'">'.$check_script;
            }
            else
            {
               $row[]='';
            }
            
            $row[] = $vital_list->vitals_name;
            $row[] = $vital_list->vitals_unit;
            $row[] = $vital_list->short_code;    
            $row[] =  $vital_list->sort_order;
            $row[] = $status;
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$vital_list->branch_id)
            {
              if(in_array('1113',$users_data['permission']['action'])){
              $btnedit =' <a onClick="return edit_vitals('.$vital_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vital_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
            }
            if(in_array('1114',$users_data['permission']['action'])){
                   $btndelete = ' <a class="btn-custom" onClick="return delete_vitals('.$vital_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';  
                   } 
             
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

         $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vitals_field->count_all(),
                        "recordsFiltered" => $this->vitals_field->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {

        unauthorise_permission('189','1112');
        $data['page_title'] = "Add Vital";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"",
                                  'vitals_name'=>"",
                                  'vitals_unit'=>"",
                                  'short_code'=>'',
                                  'status'=>"1",
                                  "sort_order"=>"",
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();

            if($this->form_validation->run() == TRUE)
            {
               $this->vitals_field->save();
                 echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  

                //print_r($data['form_error']);die;
            }     
        }
       $this->load->view('vitals/add',$data);       
    }

    public function edit($id="")
    {
    unauthorise_permission('189','1113');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->vitals_field->get_by_id($id);
        $field_list = $this->vitals_field->get_by_id($id);  
        $data['page_title'] = "Update vital";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'vitals_name'=>$result['vitals_name'],
                                  'vitals_unit'=>$result['vitals_unit'],
                                  'short_code'=>$result['short_code'],
                                  'status'=>$result['status'],
                                  'sort_order'=>$result['sort_order'], 
                                 ); 
       
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $response = $this->vitals_field->save();
                 echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('vitals/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $this->form_validation->set_rules('vitals_name', 'vitals name', 'trim|required');
       
         if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'vitals_name'=>$post['vitals_name'],
                                        'vitals_unit'=>$post['vitals_unit'],
                                        'short_code'=>$post['short_code'],
                                        'sort_order'=>$post['sort_order'],
                                        'status'=>$post['status']
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('189','1114');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->vitals_field->delete($id);
           $response = "vitals successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('189','1114');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vitals_field->deleteall($post['row_id']);
            $response = "Vitals  successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vitals_field->get_by_id($id);  
        $data['page_title'] = $data['form_data']['vitals_name']." detail";
        $this->load->view('vitals/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('189','1115');
        $data['page_title'] = 'Vitals Archive List';
        $this->load->helper('url');
        $this->load->view('vitals/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('189','1115');
        $this->load->model('vitals/vitals_archive_model','vitals_field_archive'); 

      
      $list = $this->vitals_field_archive->get_datatables();
                 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vitals_list) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($vitals_list->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vitals_list->id.'">'.$check_script; 
            
            $row[] = $vitals_list->vitals_name;
            $row[] = $vitals_list->vitals_unit;
            $row[] = $vitals_list->short_code;
            $row[] =  $vitals_list->sort_order;
            $row[] = $status;//date('d-M-Y H:i A',strtotime($vitals_list->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1117',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_vitals('.$vitals_list->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1116',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$vitals_list->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vitals_field_archive->count_all(),
                        "recordsFiltered" => $this->vitals_field_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('189','1117');
        $this->load->model('vitals/vitals_archive_model','vitals_field_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vitals_field_archive->restore($id);
           $response = "Vitals successfully restore in payment mode list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('189','1117');
        $this->load->model('vitals/vitals_archive_model','vitals_field_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vitals_field_archive->restoreall($post['row_id']);
            $response = "Vitals successfully restore in vitals mode list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('189','1116');
        $this->load->model('vitals/vitals_archive_model','vitals_field_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vitals_field_archive->trash($id);
           $response = "vitals successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('189','1116');
        $this->load->model('vitals/vitals_archive_model','vitals_field_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vitals_field_archive->trashall($post['row_id']);
            $response = "Vitals successfully deleted parmanently.";
            echo $response;
        }
    }


}
?>