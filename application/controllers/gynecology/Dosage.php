<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosage extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('gynecology/dosage/dosage_model','dosage');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('304','1816');
        $data['page_title'] = 'Dosage List'; 
        $this->load->view('gynecology/dosage/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('304','1816');
        $list = $this->dosage->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $dosage) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($dosage->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dosage->id.'">'.$check_script; 
            $row[] = $dosage->medicine_dosage;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dosage->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('1818',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_dosage('.$dosage->id.');" class="btn-custom" href="javascript:void(0)" style="'.$dosage->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1819',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_dosage('.$dosage->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dosage->count_all(),
                        "recordsFiltered" => $this->dosage->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('304','1817');
        $data['page_title'] = "Add Dosage";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine_dosage'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dosage->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/dosage/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('304','1818');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->dosage->get_by_id($id);  
        $data['page_title'] = "Update Dosage";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_dosage'=>$result['medicine_dosage'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->dosage->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('gynecology/dosage/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_dosage', 'dosage', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_dosage'=>$post['medicine_dosage'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
      unauthorise_permission('304','1819');
       if(!empty($id) && $id>0)
       {
           $result = $this->dosage->delete($id);
           $response = "Dosage successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
      unauthorise_permission('304','1819');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dosage->deleteall($post['row_id']);
            $response = "Dosage successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->dosage->get_by_id($id);  
        $data['page_title'] = $data['form_data']['dosage']." detail";
        $this->load->view('gynecology/dosage/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('304','1820');
        $data['page_title'] = 'Dosage Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/dosage/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('304','1820');
        $this->load->model('gynecology/dosage/dosage_archive_model','dosage_archive'); 

        $list = $this->dosage_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $dosage) { 
            $no++;
            $row = array();
            if($dosage->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dosage->id.'">'.$check_script; 
            $row[] = $dosage->medicine_dosage;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dosage->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1822',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_dosage('.$dosage->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1821',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$dosage->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dosage_archive->count_all(),
                        "recordsFiltered" => $this->dosage_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('304','1822');
        $this->load->model('gynecology/dosage/dosage_archive_model','dosage_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dosage_archive->restore($id);
           $response = "Dosage successfully restore in Dosage list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('304','1822');
        $this->load->model('gynecology/dosage/dosage_archive_model','dosage_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dosage_archive->restoreall($post['row_id']);
            $response = "Dosage successfully restore in Dosage list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('304','1821');
        $this->load->model('gynecology/dosage/dosage_archive_model','dosage_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dosage_archive->trash($id);
           $response = "Dosage successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('304','1821');
        $this->load->model('gynecology/dosage/dosage_archive_model','dosage_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dosage_archive->trashall($post['row_id']);
            $response = "Dosage successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function dosage_dropdown()
  {

      $dosage_list = $this->dosage->dosage_list();
      $dropdown = '<option value="">Select Dosage</option>'; 
      if(!empty($dosage_list))
      {
        foreach($dosage_list as $dosage)
        {
           $dropdown .= '<option value="'.$dosage->id.'">'.$dosage->medicine_dosage.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>