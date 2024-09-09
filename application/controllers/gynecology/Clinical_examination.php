<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class clinical_examination extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('gynecology/clinical_examination/clinical_examination_model','clinical_examination');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('301','1788');
        $data['page_title'] = 'Clinical Examination List'; 
        $this->load->view('gynecology/clinical_examination/list',$data);
    }

    public function ajax_list()
    { 
       unauthorise_permission('301','1788');
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
          $list = $this->clinical_examination->get_datatables();
         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $clinical_examination) {
         // print_r($clinical_examination);die;
            $no++;
            $row = array();
            if($clinical_examination->status==1)
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
            if($users_data['parent_id']==$clinical_examination->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$clinical_examination->id.'">'.$check_script; 
            }else{
               $row[]='';
            }
            $row[] = $clinical_examination->clinical_examination;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($clinical_examination->created_date)); 
            
          $btnedit='';
          $btndelete='';
          
          if($users_data['parent_id']==$clinical_examination->branch_id){
               if(in_array('1790',$users_data['permission']['action'])){
                    $btnedit = ' <a onClick="return edit_clinical_examination('.$clinical_examination->id.');" class="btn-custom" href="javascript:void(0)" style="'.$clinical_examination->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
                if(in_array('1791',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_clinical_examination('.$clinical_examination->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               }
          }
             
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->clinical_examination->count_all(),
                        "recordsFiltered" => $this->clinical_examination->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
         unauthorise_permission('301','1789');
        $data['page_title'] = "Add Clinical Examination";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'clinical_examination'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->clinical_examination->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/clinical_examination/add',$data);       
    }
    
    public function edit($id="")
    {
       unauthorise_permission('301','1790');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->clinical_examination->get_by_id($id);  
        $data['page_title'] = "Update Clinical Examination";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'clinical_examination'=>$result['clinical_examination'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->clinical_examination->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/clinical_examination/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('clinical_examination', 'Clinical examination', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'clinical_examination'=>$post['clinical_examination'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
        unauthorise_permission('301','1791');
       if(!empty($id) && $id>0)
       {
           $result = $this->clinical_examination->delete($id);
           $response = "Clinical Examination successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('301','1791');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->clinical_examination->deleteall($post['row_id']);
            $response = "Clinical Examinations successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->clinical_examination->get_by_id($id);  
        $data['page_title'] = $data['form_data']['clinical_examination']." detail";
        $this->load->view('gynecology/clinical_examination/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
         unauthorise_permission('301','1792');
        $data['page_title'] = 'Clinical Examination Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/clinical_examination/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('301','1792');
        $this->load->model('gynecology/clinical_examination/clinical_examination_archive_model','clinical_examination_archive'); 

       

               $list = $this->clinical_examination_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $clinical_examination) { 
            $no++;
            $row = array();
            if($clinical_examination->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$clinical_examination->id.'">'.$check_script; 
            $row[] = $clinical_examination->clinical_examination;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($clinical_examination->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1794',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_clinical_examination('.$clinical_examination->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1793',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$clinical_examination->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->clinical_examination_archive->count_all(),
                        "recordsFiltered" => $this->clinical_examination_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission('301','1794');
        $this->load->model('gynecology/clinical_examination/clinical_examination_archive_model','clinical_examination_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->clinical_examination_archive->restore($id);
           $response = "Clinical Examination successfully restore in Clinical Examination list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('301','1794');
        $this->load->model('gynecology/clinical_examination/clinical_examination_archive_model','clinical_examination_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->clinical_examination_archive->restoreall($post['row_id']);
            $response = "Clinical Examinations successfully restore in Clinical Examination list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       unauthorise_permission('301','1793');
        $this->load->model('gynecology/clinical_examination/clinical_examination_archive_model','clinical_examination_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->clinical_examination_archive->trash($id);
           $response = "Clinical Examination successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('301','1793');
        $this->load->model('gynecology/clinical_examination/clinical_examination_archive_model','clinical_examination_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->clinical_examination_archive->trashall($post['row_id']);
            $response = "Clinical Examination successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function clinical_examination_dropdown()
  {

      $clinical_examination_list = $this->clinical_examination->clinical_examination_list();
      $dropdown = '<option value="">Select Clinical Examination</option>'; 
      if(!empty($clinical_examination_list))
      {
        foreach($clinical_examination_list as $clinical_examination)
        {
           $dropdown .= '<option value="'.$clinical_examination->id.'">'.$clinical_examination->clinical_examination.'</option>';
        }
      } 
      echo $dropdown; 
  }
 

}
?>