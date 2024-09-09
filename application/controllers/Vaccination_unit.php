<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_unit extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('vaccination_unit/vaccination_unit_model','vaccination_unit');
        $this->load->library('form_validation');
    }

    public function index()
    { 
     
       unauthorise_permission('186','1014');
        $data['page_title'] = 'Vaccination unit List'; 
        $this->load->view('vaccination_unit/list',$data);
    }

    public function ajax_list()
    { 
       unauthorise_permission('186','1014');
        $list = $this->vaccination_unit->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_unit) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($vaccination_unit->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vaccination_unit->id.'">'.$check_script; 
            $row[] = $vaccination_unit->vaccination_unit;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($vaccination_unit->created_date)); 
 
       $users_data = $this->session->userdata('auth_users');
       $btnedit='';
       $btndelete='';
      if(in_array('1016',$users_data['permission']['action'])){
          $btnedit = '<a onClick="return edit_vaccination_unit('.$vaccination_unit->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vaccination_unit->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
       }
        
        if(in_array('1017',$users_data['permission']['action'])){
               $btndelete = '<a class="btn-custom" onClick="return delete_vaccination_unit('.$vaccination_unit->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
            }
          $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_unit->count_all(),
                        "recordsFiltered" => $this->vaccination_unit->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('186','1015');
        $data['page_title'] = "Add Vaccination unit";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'vaccination_unit'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vaccination_unit->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('vaccination_unit/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('186','1016');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->vaccination_unit->get_by_id($id);  
        $data['page_title'] = "Update Vaccination unit";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'vaccination_unit'=>$result['vaccination_unit'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vaccination_unit->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('vaccination_unit/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('vaccination_unit', 'vaccination unit', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
        
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'vaccination_unit'=>$post['vaccination_unit'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {  
       unauthorise_permission('186','1017');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_unit->delete($id);
           $response = "Vaccination unit successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('186','1017');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_unit->deleteall($post['row_id']);
            $response = "Vaccination unit successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vaccination_unit->get_by_id($id);  
        $data['page_title'] = $data['form_data']['vaccination_unit']." detail";
        $this->load->view('vaccination_unit/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('186','1020');
        $data['page_title'] = 'Vaccination Unit Archive List';
        $this->load->helper('url');
        $this->load->view('vaccination_unit/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('186','1020');
        $this->load->model('vaccination_unit/vaccination_unit_archive_model','vaccination_unit_archive'); 

        $list = $this->vaccination_unit_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_unit) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($vaccination_unit->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vaccination_unit->id.'">'.$check_script; 
            $row[] = $vaccination_unit->vaccination_unit;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($vaccination_unit->created_date)); 
           $users_data = $this->session->userdata('auth_users');

           $btnrestore='';
           $btndelete='';
         if(in_array('1019',$users_data['permission']['action'])){
             $btnrestore = ' <a onClick="return restore_vaccination_unit('.$vaccination_unit->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';      
         }
         if(in_array('1018',$users_data['permission']['action'])){
      $btndelete = '<a onClick="return trash('.$vaccination_unit->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
         }
             
          $row[] = $btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_unit_archive->count_all(),
                        "recordsFiltered" => $this->vaccination_unit_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('186','1019');
        $this->load->model('vaccination_unit/vaccination_unit_archive_model','vaccination_unit_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_unit_archive->restore($id);
           $response = "Vaccination unit successfully restore in unit list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('186','1019');
        $this->load->model('vaccination_unit/vaccination_unit_archive_model','vaccination_unit_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_unit_archive->restoreall($post['row_id']);
            $response = "Vaccination unit successfully restore in unit list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('186','1018');
        $this->load->model('vaccination_unit/vaccination_unit_archive_model','vaccination_unit_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_unit_archive->trash($id);
           $response = "Vaccination unit successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('186','1018');
        $this->load->model('vaccination_unit/vaccination_unit_archive_model','vaccination_unit_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_unit_archive->trashall($post['row_id']);
            $response = "Vaccination unit successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function vaccination_unit_dropdown()
  {
      $vaccination_unit_list = $this->vaccination_unit->vaccination_unit_list();
      $dropdown = '<option value="">Select Unit</option>'; 
      if(!empty($vaccination_unit_list))
      {
        foreach($vaccination_unit_list as $vaccination_unit)
        {
           $dropdown .= '<option value="'.$vaccination_unit->id.'">'.$vaccination_unit->vaccination_unit.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>