<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosis extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/diagnosis/diagnosis_model','diagnosis');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('227','1279');
        $data['page_title'] = 'Diagnosis List'; 
        $this->load->view('eye/diagnosis/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('227','1279');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->diagnosis->get_datatables();
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $diagnosis) {
         // print_r($diagnosis);die;
            $no++;
            $row = array();
            if($diagnosis->status==1)
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
            $check_script = "";
            }                 
           
            ////////// Check list end ///////////// 
            $checkboxs = "";
            if($users_data['parent_id']==$diagnosis->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$diagnosis->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $diagnosis->diagnosis;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($diagnosis->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$diagnosis->branch_id)
            {
              if(in_array('1281',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_diagnosis('.$diagnosis->id.');" class="btn-custom" href="javascript:void(0)" style="'.$diagnosis->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
              if(in_array('1282',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_diagnosis('.$diagnosis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->diagnosis->count_all(),
                        "recordsFiltered" => $this->diagnosis->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function check_diagnosis($str)
    {

      $post = $this->input->post();

      if(!empty($str))
      {
          $this->load->model('eye/general/eye_general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                $data_cat= $this->diagnosis->get_by_id($post['data_id']);
                if($data_cat['diagnosis']==$str && $post['data_id']==$data_cat['id'])
                {
                return true;  
                }
                else
                {
                $diagnosisdata = $this->general->check_diagnosis($str);

                if(empty($diagnosisdata))
                {
                return true;
                }
                else
                {
                $this->form_validation->set_message('check_diagnosis', 'The diagnosis already exists.');
                return false;
                }
                }
          }
          else
          {
                  $diagnosisdata = $this->general->check_diagnosis($str);
                  if(empty($diagnosisdata))
                  {
                     return true;
                  }
                  else
                  {
              $this->form_validation->set_message('check_diagnosis', 'The diagnosis already exists.');
              return false;
                  }
          }  
      }
      else
      {
        $this->form_validation->set_message('check_diagnosis', 'The diagnosis field is required.');
              return false; 
      } 
    }
    
    
    public function add()
    {
      //echo json_encode("hi");die;
        unauthorise_permission('227','1280');
        $data['page_title'] = "Add Diagnosis Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'diagnosis'=>"",
                                  'status'=>"1"
                                   );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->diagnosis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/diagnosis/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('227','1281');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->diagnosis->get_by_id($id);  
        $data['page_title'] = "Update diagnosis";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'diagnosis'=>$result['diagnosis'], 
                                  'status'=>$result['status']
                                   );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->diagnosis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/diagnosis/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('diagnosis', 'diagnosis', 'trim|required|callback_check_diagnosis'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'diagnosis'=>$post['diagnosis'], 
                                        'status'=>$post['status']
                                         ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('227','1282');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->diagnosis->delete($id);
           $response = "Diagnosis successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('227','1282');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis->deleteall($post['row_id']);
            $response = "Diagnosis successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->diagnosis->get_by_id($id);  
        $data['page_title'] = $data['form_data']['diagnosis']." detail";
        $this->load->view('eye/diagnosis/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('227','1283');
        $data['page_title'] = 'Diagnosis Archive List';
        $this->load->helper('url');
        $this->load->view('eye/diagnosis/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('227','1283');
        $this->load->model('eye/diagnosis/diagnosis_archive_model','diagnosis_archive'); 

      
               $list = $this->diagnosis_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $diagnosis) {
         // print_r($diagnosis);die;
            $no++;
            $row = array();
            if($diagnosis->status==1)
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
            $check_script = "";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$diagnosis->id.'">'.$check_script; 
            $row[] = $diagnosis->diagnosis;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($diagnosis->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1285',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_diagnosis('.$diagnosis->id.');" class="btn-custom" href="javascript:void(0)"  title="diagnosis Type"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1284',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$diagnosis->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->diagnosis_archive->count_all(),
                        "recordsFiltered" => $this->diagnosis_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('227','1285');
        $this->load->model('eye/diagnosis/diagnosis_archive_model','diagnosis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->diagnosis_archive->restore($id);
           $response = "Diagnosis successfully restore in diagnosis list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('227','1285');
        $this->load->model('eye/diagnosis/diagnosis_archive_model','diagnosis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis_archive->restoreall($post['row_id']);
            $response = "Diagnosis successfully restore in diagnosis list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('227','1284');
        $this->load->model('eye/diagnosis/diagnosis_archive_model','diagnosis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->diagnosis_archive->trash($id);
           $response = "Diagnosis successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('227','1284');
        $this->load->model('eye/diagnosis/diagnosis_archive_model','diagnosis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis_archive->trashall($post['row_id']);
            $response = "Diagnosis successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function simulation_dropdown()
  {
     $simulation_list = $this->diagnosis->simulation_list();
     $dropdown = '<option value="">Select Operation Type</option>'; 
     $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $diagnosis)
          {
               if(in_array($diagnosis->diagnosis,$simulations_array)){
                    $selected_simulation = 'selected="selected"';
               }
               else
               {
                  $selected_simulation = '';  
               }
               $dropdown .= '<option value="'.$diagnosis->id.'" '.$selected_simulation.' >'.$diagnosis->diagnosis.'</option>';
          }
     } 
     echo $dropdown; 
  }
  */
   public function diagnosis_dropdown()
  {
      $ot_type_list = $this->diagnosis->diagnosis_list();
      //print_r($ot_type_list);die;
      $dropdown = '<option value="">Select Diagnosis</option>'; 
      if(!empty($ot_type_list))
      {
        foreach($ot_type_list as $ot_type)
        {
           $dropdown .= '<option value="'.$ot_type->id.'">'.$ot_type->diagnosis.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>