<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_rack extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('vaccination_rack/vaccination_rack_model','vaccination_rack');
        $this->load->library('form_validation');
    }

    public function index()
    { 
     
       unauthorise_permission('185','1021');
        $data['page_title'] = 'VaccinatiOn rack List'; 
        $this->load->view('vaccination_rack/list',$data);
    }

    public function ajax_list()
    { 
       unauthorise_permission('185','1021');
        $list = $this->vaccination_rack->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_rack) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($vaccination_rack->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vaccination_rack->id.'">'.$check_script; 
            $row[] = $vaccination_rack->rack_no;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($vaccination_rack->created_date)); 
 
       $users_data = $this->session->userdata('auth_users');
       $btnedit='';
       $btndelete='';
      if(in_array('1023',$users_data['permission']['action'])){
          $btnedit = '<a onClick="return edit_vaccination_rack('.$vaccination_rack->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vaccination_rack->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
       }
        
        if(in_array('1024',$users_data['permission']['action'])){
               $btndelete = '<a class="btn-custom" onClick="return delete_vaccination_rack('.$vaccination_rack->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
            }
          $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_rack->count_all(),
                        "recordsFiltered" => $this->vaccination_rack->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('185','1022');
        $data['page_title'] = "Add Vaccination Rack No";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'rack_no'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vaccination_rack->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('vaccination_rack/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('185','1023');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->vaccination_rack->get_by_id($id);  
        $data['page_title'] = "Update Vaccination Rack No";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'rack_no'=>$result['rack_no'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vaccination_rack->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('vaccination_rack/add',$data);       
      }
    }
     
    private function _validate()
    {
    
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('rack_no', 'rack no', 'trim|required|callback_check_vaccinationrackno'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'rack_no'=>$post['rack_no'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
   public function check_vaccinationrackno($str)
  {
    $post = $this->input->post();
    if(!empty($str ))
    {
        $this->load->model('general/general_model','general'); 
        if(!empty($post['data_id']) && $post['data_id']>0)
        {
                return true;
        }
        else
        {
                $companydata = $this->general->check_vaccinationrackno($str);
                if(empty($companydata))
                {
                   return true;
                }
                else
                {
            $this->form_validation->set_message('check_vaccinationrackno', 'The rack no already exists.');
            return false;
                }
        }  
    }
    else
    {
      $this->form_validation->set_message('check_vaccinationrackno', 'The rack no field is required.');
            return false; 
    } 
  }
    public function delete($id="")
    {  
       unauthorise_permission('185','1024');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_rack->delete($id);
           $response = "Vaccination Rack number successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('185','1024');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_rack->deleteall($post['row_id']);
            $response = "Vaccination Rack number successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vaccination_rack->get_by_id($id);  
        $data['page_title'] = $data['form_data']['vaccination_rack']." detail";
        $this->load->view('vaccination_rack/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('185','1027');
        $data['page_title'] = 'Rack Archive List';
        $this->load->helper('url');
        $this->load->view('vaccination_rack/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('185','1027');
        $this->load->model('vaccination_rack/vaccination_rack_archive_model','vaccination_rack_archive'); 

        $list = $this->vaccination_rack_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_rack) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($vaccination_rack->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vaccination_rack->id.'">'.$check_script; 
            $row[] = $vaccination_rack->rack_no;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($vaccination_rack->created_date)); 
           $users_data = $this->session->userdata('auth_users');

           $btnrestore='';
           $btndelete='';
         if(in_array('1026',$users_data['permission']['action'])){
             $btnrestore = ' <a onClick="return restore_vaccination_rack('.$vaccination_rack->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';      
         }
         if(in_array('1025',$users_data['permission']['action'])){
      $btndelete = '<a onClick="return trash('.$vaccination_rack->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
         }
             
          $row[] = $btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_rack_archive->count_all(),
                        "recordsFiltered" => $this->vaccination_rack_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('185','1026');
        $this->load->model('vaccination_rack/vaccination_rack_archive_model','vaccination_rack_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_rack_archive->restore($id);
           $response = "Vaccination Rack number successfully restore in vaccination rack list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('185','1026');
        $this->load->model('vaccination_rack/vaccination_rack_archive_model','vaccination_rack_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_rack_archive->restoreall($post['row_id']);
            $response = "Vaccination Rack number successfully restore in vaccination rack list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('185','1025');
        $this->load->model('vaccination_rack/vaccination_rack_archive_model','vaccination_rack_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_rack_archive->trash($id);
           $response = "Vaccination Rack number successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('185','1025');
        $this->load->model('vaccination_rack/vaccination_rack_archive_model','vaccination_rack_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_rack_archive->trashall($post['row_id']);
            $response = "Vaccination Rack number successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function vaccination_rack_dropdown()
  {
      $medicine_rack_list = $this->vaccination_rack->medicine_rack_list();
      $dropdown = '<option value="">Select Vaccination Rack No.</option>'; 
      if(!empty($medicine_rack_list))
      {
        foreach($medicine_rack_list as $rack_list)
        {
           $dropdown .= '<option value="'.$rack_list->id.'">'.$rack_list->rack_no.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>