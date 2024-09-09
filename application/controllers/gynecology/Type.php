<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('gynecology/type/type_model','typemodel');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('313','1867');
        $data['page_title'] = 'Type List'; 
        $this->load->view('gynecology/type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('313','1867');
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
            $row[] = $type->medicine_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('1869',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_type('.$type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1870',$users_data['permission']['action'])){
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
        unauthorise_permission('313','1868');
        $data['page_title'] = "Add Type";  
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
       $this->load->view('gynecology/type/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('313','1869');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->typemodel->get_by_id($id);  
        $data['page_title'] = "Update Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_type'=>$result['medicine_type'], 
                                  'status'=>$result['status'],
                                  
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
       $this->load->view('gynecology/type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_type', 'medicine unit', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_type'=>$post['medicine_type'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
        unauthorise_permission('313','1870');
       if(!empty($id) && $id>0)
       {
           $result = $this->typemodel->delete($id);
           $response = "Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('313','1870');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->typemodel->deleteall($post['row_id']);
            $response = "Type successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->typemodel->get_by_id($id);  
        $data['page_title'] = $data['form_data']['type']." detail";
        $this->load->view('gynecology/type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('313','1871');
        $data['page_title'] = 'Type Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('313','1871');
        $this->load->model('gynecology/type/type_archive_model','type_archive'); 

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
            $row[] = $type->medicine_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1873',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_type('.$type->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1872',$users_data['permission']['action'])){
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
      unauthorise_permission('313','1873');
        $this->load->model('gynecology/type/type_archive_model','type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->type_archive->restore($id);
           $response = "Type successfully restore in Type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
      unauthorise_permission('313','1873');
        $this->load->model('gynecology/type/type_archive_model','type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->type_archive->restoreall($post['row_id']);
            $response = "Type successfully restore in Type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('313','1872');
        $this->load->model('gynecology/type/type_archive_model','type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->type_archive->trash($id);
           $response = "Type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('313','1872');
        $this->load->model('gynecology/type/type_archive_model','type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->type_archive->trashall($post['row_id']);
            $response = "Type successfully deleted parmanently.";
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