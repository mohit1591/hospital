<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_procedure extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_procedure/ot_procedure_model','ot_procedure');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('250','1423');
        $data['page_title'] = 'OT Procedure List'; 
        $this->load->view('ot_procedure/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('250','1423');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->ot_procedure->get_datatables();
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot_procedure) {
         // print_r($ot_procedure);die;
            $no++;
            $row = array();
            if($ot_procedure->status==1)
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
            if($users_data['parent_id']==$ot_procedure->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot_procedure->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ot_procedure->ot_procedure;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ot_procedure->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ot_procedure->branch_id)
            {
              if(in_array('1425',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ot_procedure('.$ot_procedure->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ot_procedure->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
              if(in_array('1426',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ot_procedure('.$ot_procedure->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_procedure->count_all(),
                        "recordsFiltered" => $this->ot_procedure->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function check_ot_procedure($str)
    {

      $post = $this->input->post();

      if(!empty($str))
      {
          $this->load->model('eye/general/eye_general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                $data_cat= $this->ot_procedure->get_by_id($post['data_id']);
                if($data_cat['ot_procedure']==$str && $post['data_id']==$data_cat['id'])
                {
                return true;  
                }
                else
                {
                $ot_proceduredata = $this->general->check_ot_procedure($str);

                if(empty($ot_proceduredata))
                {
                return true;
                }
                else
                {
                $this->form_validation->set_message('check_ot_procedure', 'The ot_procedure already exists.');
                return false;
                }
                }
          }
          else
          {
                  $ot_proceduredata = $this->general->check_ot_procedure($str);
                  if(empty($ot_proceduredata))
                  {
                     return true;
                  }
                  else
                  {
              $this->form_validation->set_message('check_ot_procedure', 'The ot_procedure already exists.');
              return false;
                  }
          }  
      }
      else
      {
        $this->form_validation->set_message('check_ot_procedure', 'The ot_procedure field is required.');
              return false; 
      } 
    }
    
    
    public function add()
    {
      //echo json_encode("hi");die;
        unauthorise_permission('250','1424');
        $data['page_title'] = "Add OT Procedure";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'ot_procedure'=>"",
                                  'status'=>"1"
                                   );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ot_procedure->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ot_procedure/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('250','1425');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ot_procedure->get_by_id($id);  
        $data['page_title'] = "Update OT Procedure";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'ot_procedure'=>$result['ot_procedure'], 
                                  'status'=>$result['status']
                                   );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ot_procedure->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ot_procedure/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('ot_procedure', 'ot_procedure', 'trim|required|callback_check_ot_procedure'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'ot_procedure'=>$post['ot_procedure'], 
                                        'status'=>$post['status']
                                         ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('250','1426');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ot_procedure->delete($id);
           $response = "OT Procedure successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('250','1426');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_procedure->deleteall($post['row_id']);
            $response = "OT Procedure successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ot_procedure->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ot_procedure']." detail";
        $this->load->view('ot_procedure/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('250','1427');
        $data['page_title'] = 'OT Procedure Archive List';
        $this->load->helper('url');
        $this->load->view('ot_procedure/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('250','1427');
        $this->load->model('ot_procedure/ot_procedure_archive_model','ot_procedure_archive'); 

      
               $list = $this->ot_procedure_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot_procedure) {
         // print_r($ot_procedure);die;
            $no++;
            $row = array();
            if($ot_procedure->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot_procedure->id.'">'.$check_script; 
            $row[] = $ot_procedure->ot_procedure;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ot_procedure->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1429',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ot_procedure('.$ot_procedure->id.');" class="btn-custom" href="javascript:void(0)"  title="ot_procedure Type"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1426',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ot_procedure->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_procedure_archive->count_all(),
                        "recordsFiltered" => $this->ot_procedure_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('250','1429');
        $this->load->model('ot_procedure/ot_procedure_archive_model','ot_procedure_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_procedure_archive->restore($id);
           $response = "OT Procedure successfully restore in list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('250','1429');
        $this->load->model('ot_procedure/ot_procedure_archive_model','ot_procedure_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_procedure_archive->restoreall($post['row_id']);
            $response = "OT Procedure successfully restore in list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('250','1428');
        $this->load->model('ot_procedure/ot_procedure_archive_model','ot_procedure_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_procedure_archive->trash($id);
           $response = "OT Procedure successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('250','1428');
        $this->load->model('ot_procedure/ot_procedure_archive_model','ot_procedure_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_procedure_archive->trashall($post['row_id']);
            $response = "OT Procedure successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function simulation_dropdown()
  {
     $simulation_list = $this->ot_procedure->simulation_list();
     $dropdown = '<option value="">Select Operation Type</option>'; 
     $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $ot_procedure)
          {
               if(in_array($ot_procedure->ot_procedure,$simulations_array)){
                    $selected_simulation = 'selected="selected"';
               }
               else
               {
                  $selected_simulation = '';  
               }
               $dropdown .= '<option value="'.$ot_procedure->id.'" '.$selected_simulation.' >'.$ot_procedure->ot_procedure.'</option>';
          }
     } 
     echo $dropdown; 
  }
  */
   public function ot_procedure_dropdown()
  {
      $ot_type_list = $this->ot_procedure->ot_procedure_list();
      //print_r($ot_type_list);die;
      $dropdown = '<option value="">Select ot_procedure</option>'; 
      if(!empty($ot_type_list))
      {
        foreach($ot_type_list as $ot_type)
        {
           $dropdown .= '<option value="'.$ot_type->id.'">'.$ot_type->ot_procedure.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  
  

}
?>