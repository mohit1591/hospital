<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simulation extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('simulation/simulation_model','simulation');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('12','64');
        $data['page_title'] = 'Simulation List'; 
        $this->load->view('simulation/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('12','64');
         $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
     
            $list = $this->simulation->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $simulation) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($simulation->status==1)
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
            if($users_data['parent_id']==$simulation->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$simulation->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $simulation->simulation;  
            $row[] = $status;
            $row[] = $simulation->sort_order;//date('d-M-Y H:i A',strtotime($simulation->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$simulation->branch_id)
            {
              if(in_array('66',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_simulation('.$simulation->id.');" class="btn-custom" href="javascript:void(0)" style="'.$simulation->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('67',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_simulation('.$simulation->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->simulation->count_all(),
                        "recordsFiltered" => $this->simulation->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('12','65');
        $data['page_title'] = "Add Simulation";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'simulation'=>"",
                                  'status'=>"1",
                                  'gender' =>"1",
                                  'sort_order'=>'',
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->simulation->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('simulation/add',$data);       
    }
     // -> function to find gender according to selected simulation
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $simulation_id = $this->input->post('simulation_id');
         $data='';
          if(!empty($simulation_id)){
               $result = $this->general_model->find_gender($simulation_id);
               if(!empty($result))
               {
                  $male = "";
                  $female = ""; 
                  $others=""; 
                  if($result['gender']==1)
                  {
                     $male = 'checked="checked"';
                  } 
                  else if($result['gender']==0)
                  {
                     $female = 'checked="checked"';
                  } 
                  else if($result['gender']==2){
                    $others = 'checked="checked"';
                  }
                  $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
                          <input type="radio" name="gender" '.$female.' value="0" /> Female &nbsp;
                          <input type="radio" name="gender" '.$others.' value="2" /> Others ';
               }
 
          }
          echo $data;
    }
    // -> end:
    public function edit($id="")
    {
     unauthorise_permission('12','66');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->simulation->get_by_id($id);  
        $data['page_title'] = "Update Simulation";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'simulation'=>$result['simulation'], 
                                  'status'=>$result['status'],
                                  'gender'=>$result['gender'],
                                  'sort_order'=>$result['sort_order'],
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->simulation->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('simulation/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation', 'simulation', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'simulation'=>$post['simulation'], 
                                        'status'=>$post['status'],
                                        'gender'=>$post['gender'],
                                        'sort_order'=>$post['sort_order']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->simulation->delete($id);
           $response = "Simulation successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('12','67');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->simulation->deleteall($post['row_id']);
            $response = "Simulations successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->simulation->get_by_id($id);  
        $data['page_title'] = $data['form_data']['simulation']." detail";
        $this->load->view('simulation/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('12','68');
        $data['page_title'] = 'Simulation Archive List';
        $this->load->helper('url');
        $this->load->view('simulation/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('12','68');
        $this->load->model('simulation/simulation_archive_model','simulation_archive'); 

      
               $list = $this->simulation_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $simulation) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($simulation->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$simulation->id.'">'.$check_script; 
            $row[] = $simulation->simulation;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($simulation->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('70',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_simulation('.$simulation->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('69',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$simulation->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->simulation_archive->count_all(),
                        "recordsFiltered" => $this->simulation_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('12','70');
        $this->load->model('simulation/simulation_archive_model','simulation_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->simulation_archive->restore($id);
           $response = "Simulation successfully restore in Simulation list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('12','70');
        $this->load->model('simulation/simulation_archive_model','simulation_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->simulation_archive->restoreall($post['row_id']);
            $response = "Simulations successfully restore in Simulation list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('12','69');
        $this->load->model('simulation/simulation_archive_model','simulation_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->simulation_archive->trash($id);
           $response = "Simulation successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('12','69');
        $this->load->model('simulation/simulation_archive_model','simulation_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->simulation_archive->trashall($post['row_id']);
            $response = "Simulation successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function simulation_dropdown()
  {
     $simulation_list = $this->simulation->simulation_list();
     $dropdown = '<option value="">Select Simulation</option>'; 
     $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $simulation)
          {
               if(in_array($simulation->simulation,$simulations_array)){
                    $selected_simulation = 'selected="selected"';
               }
               else
               {
                  $selected_simulation = '';  
               }
               $dropdown .= '<option value="'.$simulation->id.'" '.$selected_simulation.' >'.$simulation->simulation.'</option>';
          }
     } 
     echo $dropdown; 
  }
  
  //test for API you can delete
  public function converjsonefromxml() 
  {
      $url = 'https://api.customco.com/scripts/cgiip.exe/ratequote.xml?xmlv=yes&xmluser=Baseb&xmlpass=Racks1!&vozip=60502&vdzip=90001&wpieces[1]=50&wpallets[1]=1&wweight[1]=5000&wlength[1]=148&wheight[1]=48&wwidth[1]=40&vclass[1]=100&LA=Yes&SS=Yes&quotenumber=yes';
        $fileContents= file_get_contents($url);
        
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        
        
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);
        echo "<pre>"; print_r($json); exit;
        //return $json;
    }
  

}
?>