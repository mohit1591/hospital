<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_rack extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('medicine_rack/medicine_rack_model','medicine_rack');
        $this->load->library('form_validation');
    }

    public function index()
    { 
     
       unauthorise_permission('54','354');
        $data['page_title'] = 'Medicine Rack List'; 
        $this->load->view('medicine_rack/list',$data);
    }

    public function ajax_list()
    { 
       unauthorise_permission('54','354');
        $list = $this->medicine_rack->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine_rack) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($medicine_rack->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine_rack->id.'">'.$check_script; 
            $row[] = $medicine_rack->rack_no;  
            $row[] = $status;
           // $row[] = date('d-M-Y H:i A',strtotime($medicine_rack->created_date)); 
 
       $users_data = $this->session->userdata('auth_users');
       $btnedit='';
       $btndelete='';
      if(in_array('356',$users_data['permission']['action'])){
          $btnedit = '<a onClick="return edit_medicine_rack('.$medicine_rack->id.');" class="btn-custom" href="javascript:void(0)" style="'.$medicine_rack->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
       }
        
        if(in_array('357',$users_data['permission']['action'])){
               $btndelete = '<a class="btn-custom" onClick="return delete_medicine_rack('.$medicine_rack->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
            }
          $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_rack->count_all(),
                        "recordsFiltered" => $this->medicine_rack->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('54','355');
        $data['page_title'] = "Add Rack No";  
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
                $this->medicine_rack->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('medicine_rack/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('54','356');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->medicine_rack->get_by_id($id);  
        $data['page_title'] = "Update Rack No";  
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
                $this->medicine_rack->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('medicine_rack/add',$data);       
      }
    }
     
    private function _validate()
    {
    
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('rack_no', 'rack no', 'trim|required|callback_check_rackno'); 
        
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
 
   public function check_rackno($str)
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
                $companydata = $this->general->check_rackno($str);
                if(empty($companydata))
                {
                   return true;
                }
                else
                {
            $this->form_validation->set_message('check_rackno', 'The rack no already exists.');
            return false;
                }
        }  
    }
    else
    {
      $this->form_validation->set_message('check_rackno', 'The rack no field is required.');
            return false; 
    } 
  }
    public function delete($id="")
    {  
       unauthorise_permission('54','357');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_rack->delete($id);
           $response = "Rack number successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('54','357');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_rack->deleteall($post['row_id']);
            $response = "Rack number successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_rack->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_rack']." detail";
        $this->load->view('medicine_rack/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('54','417');
        $data['page_title'] = 'Rack Archive List';
        $this->load->helper('url');
        $this->load->view('medicine_rack/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('54','417');
        $this->load->model('medicine_rack/medicine_rack_archive_model','medicine_rack_archive'); 

        $list = $this->medicine_rack_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine_rack) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($medicine_rack->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine_rack->id.'">'.$check_script; 
            $row[] = $medicine_rack->rack_no;  
            $row[] = $status;
           // $row[] = date('d-M-Y H:i A',strtotime($medicine_rack->created_date)); 
           $users_data = $this->session->userdata('auth_users');

           $btnrestore='';
           $btndelete='';
         if(in_array('359',$users_data['permission']['action'])){
             $btnrestore = ' <a onClick="return restore_medicine_rack('.$medicine_rack->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';      
         }
         if(in_array('358',$users_data['permission']['action'])){
      $btndelete = '<a onClick="return trash('.$medicine_rack->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
         }
             
          $row[] = $btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_rack_archive->count_all(),
                        "recordsFiltered" => $this->medicine_rack_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('54','359');
        $this->load->model('medicine_rack/medicine_rack_archive_model','medicine_rack_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_rack_archive->restore($id);
           $response = "Rack number successfully restore in rack list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('54','359');
        $this->load->model('medicine_rack/medicine_rack_archive_model','medicine_rack_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_rack_archive->restoreall($post['row_id']);
            $response = "Rack number successfully restore in rack list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('54','358');
        $this->load->model('medicine_rack/medicine_rack_archive_model','medicine_rack_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_rack_archive->trash($id);
           $response = "Rack number successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('54','358');
        $this->load->model('medicine_rack/medicine_rack_archive_model','medicine_rack_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_rack_archive->trashall($post['row_id']);
            $response = "Rack number successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function medicine_rack_dropdown()
  {
      $medicine_rack_list = $this->medicine_rack->medicine_rack_list();
      $dropdown = '<option value="">Select Rack No.</option>'; 
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