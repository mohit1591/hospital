<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collection_center extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('collection_center/collection_center_model','collection_center','Employee_archive_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(157,929);  
        $data['page_title'] = 'Collection Center List'; 
        $this->load->view('collection_center/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(157,929);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->collection_center->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $collection_center) {
         // print_r($collection_center);die;
            $no++;
            $row = array();
            if($collection_center->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($collection_center->state))
            {
                $state = " ( ".ucfirst(strtolower($collection_center->state))." )";
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
            if($users_data['parent_id']==$collection_center->branch_id){ 
                $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$collection_center->id.'">'.$check_script; 
            }else{
                $row[]='';
            }
            $row[] = $collection_center->source;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($collection_center->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
             if($users_data['parent_id']==$collection_center->branch_id){
                if(in_array('929',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_collection_center('.$collection_center->id.');" class="btn-custom" href="javascript:void(0)" style="'.$collection_center->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                if(in_array('929',$users_data['permission']['action'])) 
                {
                   $btndelete = ' <a class="btn-custom" onClick="return delete_emp_type('.$collection_center->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->collection_center->count_all(),
                        "recordsFiltered" => $this->collection_center->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {


        unauthorise_permission(157,929);
        $data['page_title'] = "Add Collection Center";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'collection_center'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->collection_center->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       // print_r( $data['form_error']);die;
       $this->load->view('collection_center/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission(157,929);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->collection_center->get_by_id($id); 
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->collection_center->collection_center_list();  
        $data['page_title'] = "Update Employee";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'collection_center'=>$result['source'],
 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->collection_center->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('collection_center/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('collection_center', 'Collection Center', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  

            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'collection_center'=>$post['collection_center'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(157,929);
       if(!empty($id) && $id>0)
       {
           $result = $this->collection_center->delete($id);
           $response = "Collection Center successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(157,929);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->collection_center->deleteall($post['row_id']);
            $response = "Collection Center successfully deleted.";
            echo $response;
        }
    }

     


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(157,929);
        $data['page_title'] = 'Patient Source Archive List';
        $this->load->helper('url');
        $this->load->view('collection_center/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(157,929);
        $this->load->model('collection_center/collection_center_archive_model','collection_center_archive'); 
        $users_data = $this->session->userdata('auth_users');
        

               $list = $this->collection_center_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $collection_center) {
         // print_r($collection_center);die;
            $no++;
            $row = array();
            if($collection_center->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($collection_center->state))
            {
                $state = " ( ".ucfirst(strtolower($collection_center->state))." )";
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$collection_center->id.'">'.$check_script; 
            $row[] = $collection_center->source;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($collection_center->created_date)); 
            
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if(in_array('929',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_collection_center('.$collection_center->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            if(in_array('929',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$collection_center->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //


      $row[] = $btn_restore.$btn_delete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->collection_center_archive->count_all(),
                        "recordsFiltered" => $this->collection_center_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(157,929);
        $this->load->model('collection_center/collection_center_archive_model','collection_center_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->collection_center_archive->restore($id);
           $response = "Collection center successfully restore in Patient Source list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(157,929);
        $this->load->model('collection_center/collection_center_archive_model','collection_center_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->collection_center_archive->restoreall($post['row_id']);
            $response = "Collection center successfully restore in center list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(157,929);
        $this->load->model('collection_center/collection_center_archive_model','collection_center_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->collection_center_archive->trash($id);
           $response = "Collection center successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(157,929);
        $this->load->model('collection_center/collection_center_archive_model','collection_center_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->collection_center_archive->trashall($post['row_id']);
            $response = "Collection Center successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function collection_center_dropdown()
  {
      $collection_center_list = $this->collection_center->collection_center_list();
      $dropdown = '<option value="">Select Collection Center</option>'; 
      if(!empty($collection_center_list))
      {
        foreach($collection_center_list as $collection_center)
        {
           $dropdown .= '<option value="'.$collection_center->id.'">'.$collection_center->source.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>