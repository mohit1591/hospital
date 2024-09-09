<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examination extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pediatrician/examination/examination_model','examination');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('338','1971');
        $data['page_title'] = 'Examination List'; 
        $this->load->view('pediatrician/examination/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('338','1971');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->examination->get_datatables();
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $examination) {
         // print_r($examination);die;
            $no++;
            $row = array();
            if($examination->status==1)
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
            if($users_data['parent_id']==$examination->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$examination->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $examination->examination;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($examination->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$examination->branch_id)
            {
              if(in_array('1973',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_examination('.$examination->id.');" class="btn-custom" href="javascript:void(0)" style="'.$examination->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
              if(in_array('1974',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_examination('.$examination->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->examination->count_all(),
                        "recordsFiltered" => $this->examination->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function check_examination($str)
    {

      $post = $this->input->post();

      if(!empty($str))
      {
          $this->load->model('pediatrician/general/pediatrician_general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                $data_cat= $this->examination->get_by_id($post['data_id']);
                if($data_cat['examination']==$str && $post['data_id']==$data_cat['id'])
                {
                return true;  
                }
                else
                {
                $examinationdata = $this->general->check_examination($str);

                if(empty($examinationdata))
                {
                return true;
                }
                else
                {
                $this->form_validation->set_message('check_examination', 'The examination type already exists.');
                return false;
                }
                }
          }
          else
          {
                  $examinationdata = $this->general->check_examination($str);
                  if(empty($examinationdata))
                  {
                     return true;
                  }
                  else
                  {
              $this->form_validation->set_message('check_examination', 'The examination type already exists.');
              return false;
                  }
          }  
      }
      else
      {
        $this->form_validation->set_message('check_examination', 'The examination type field is required.');
              return false; 
      } 
    }
    
    
    public function add()
    {
      //echo json_encode("hi");die;
        unauthorise_permission('338','1972');
        $data['page_title'] = "Add Examination";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'examination'=>"",
                                  'status'=>"1"
                                   );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->examination->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/examination/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('338','1973');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->examination->get_by_id($id);  
        $data['page_title'] = "Update Examination";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'examination'=>$result['examination'], 
                                  'status'=>$result['status']
                                   );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->examination->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/examination/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('examination', 'examination type', 'trim|required|callback_check_examination'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'examination'=>$post['examination'], 
                                        'status'=>$post['status']
                                         ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('338','1974');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->examination->delete($id);
           $response = "Examination successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('338','1974');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->examination->deleteall($post['row_id']);
            $response = "Examination successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->examination->get_by_id($id);  
        $data['page_title'] = $data['form_data']['examination']." detail";
        $this->load->view('pediatrician/examination/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('338','1975');
        $data['page_title'] = 'Examination Archive List';
        $this->load->helper('url');
        $this->load->view('pediatrician/examination/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('338','1975');
        $this->load->model('pediatrician/examination/examination_archive_model','examination_archive'); 

      
               $list = $this->examination_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $examination) {
         // print_r($examination);die;
            $no++;
            $row = array();
            if($examination->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$examination->id.'">'.$check_script; 
            $row[] = $examination->examination;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($examination->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1977',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_examination('.$examination->id.');" class="btn-custom" href="javascript:void(0)"  title="Examination"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1976',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$examination->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->examination_archive->count_all(),
                        "recordsFiltered" => $this->examination_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('338','1977');
        $this->load->model('pediatrician/examination/examination_archive_model','examination_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->examination_archive->restore($id);
           $response = "Examination successfully restore in examination list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('338','1977');
        $this->load->model('pediatrician/examination/examination_archive_model','examination_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->examination_archive->restoreall($post['row_id']);
            $response = "Examination successfully restore in examination list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('338','1976');
        $this->load->model('pediatrician/examination/examination_archive_model','examination_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->examination_archive->trash($id);
           $response = "Examination  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('338','1976');
        $this->load->model('pediatrician/examination/examination_archive_model','examination_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->examination_archive->trashall($post['row_id']);
            $response = "Examination successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function simulation_dropdown()
  {
     $simulation_list = $this->examination->simulation_list();
     $dropdown = '<option value="">Select Operation Type</option>'; 
     $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $examination)
          {
               if(in_array($examination->examination,$simulations_array)){
                    $selected_simulation = 'selected="selected"';
               }
               else
               {
                  $selected_simulation = '';  
               }
               $dropdown .= '<option value="'.$examination->id.'" '.$selected_simulation.' >'.$examination->examination.'</option>';
          }
     } 
     echo $dropdown; 
  }
  */
   public function examination_dropdown()
  {
      $ot_type_list = $this->examination->examination_list();
      //print_r($ot_type_list);die;
      $dropdown = '<option value="">Select Examination Type</option>'; 
      if(!empty($ot_type_list))
      {
        foreach($ot_type_list as $ot_type)
        {
           $dropdown .= '<option value="'.$ot_type->id.'">'.$ot_type->examination.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>