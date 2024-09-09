<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosage extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dental/dosage/dosage_model','dosage');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('289','1710');
        $data['page_title'] = 'Dosage List'; 
        $this->load->view('dental/dosage/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('289','1710');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->dosage->get_datatables();
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $dosage) {
         // print_r($dosage);die;
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
            $check_script = "";
            }                 
           
            ////////// Check list end ///////////// 
            $checkboxs = "";
            if($users_data['parent_id']==$dosage->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dosage->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $dosage->dosage;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dosage->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$dosage->branch_id)
            {
              if(in_array('1712',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_dosage('.$dosage->id.');" class="btn-custom" href="javascript:void(0)" style="'.$dosage->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
              if(in_array('1713',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_dosage('.$dosage->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              }
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

    public function check_dosage($str)
    {

      $post = $this->input->post();

      if(!empty($str))
      {
          $this->load->model('dental/general/dental_general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                $data_cat= $this->dosage->get_by_id($post['data_id']);
                if($data_cat['dosage']==$str && $post['data_id']==$data_cat['id'])
                {
                return true;  
                }
                else
                {
                $dosagedata = $this->general->check_dosage($str);

                if(empty($dosagedata))
                {
                return true;
                }
                else
                {
                $this->form_validation->set_message('check_dosage', 'The dosage already exists.');
                return false;
                }
                }
          }
          else
          {
                  $dosagedata = $this->general->check_dosage($str);
                  if(empty($dosagedata))
                  {
                     return true;
                  }
                  else
                  {
              $this->form_validation->set_message('check_dosage', 'The dosage already exists.');
              return false;
                  }
          }  
      }
      else
      {
        $this->form_validation->set_message('check_dosage', 'The dosage field is required.');
              return false; 
      } 
    }
    
    
    public function add()
    {
      //echo json_encode("hi");die;
        unauthorise_permission('289','1711');
        $data['page_title'] = "Add Dosage";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'dosage'=>"",
                                  'status'=>"1"
                                   ); 

       //print_r($post);die;
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
       $this->load->view('dental/dosage/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('289','1712');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->dosage->get_by_id($id);  
        $data['page_title'] = "Update Dosage";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'dosage'=>$result['dosage'], 
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
       $this->load->view('dental/dosage/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('dosage', 'dosage', 'trim|required|callback_check_dosage'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'dosage'=>$post['dosage'], 
                                        'status'=>$post['status']
                                         ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('289','1713');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->dosage->delete($id);
           $response = "Dosage successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('289','1713');
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
        $this->load->view('eye/dosage/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('289','1714');
        $data['page_title'] = 'Dosage Archive List';
        $this->load->helper('url');
        $this->load->view('dental/dosage/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('289','1714');
        $this->load->model('dental/dosage/dosage_archive_model','dosage_archive'); 
        $list = $this->dosage_archive->get_datatables();
       $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $dosage) {
         // print_r($dosage);die;
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
            $check_script = "";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$dosage->id.'">'.$check_script; 
            $row[] = $dosage->dosage;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($dosage->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1716',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_dosage('.$dosage->id.');" class="btn-custom" href="javascript:void(0)"  title="Dosage"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1715',$users_data['permission']['action'])){
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
        unauthorise_permission('289','1716');
        $this->load->model('dental/dosage/dosage_archive_model','dosage_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dosage_archive->restore($id);
           $response = "Dosage successfully restore in dosage list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('289','1716');
        $this->load->model('dental/dosage/dosage_archive_model','dosage_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dosage_archive->restoreall($post['row_id']);
            $response = "Dosage successfully restore in dosage list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('289','1715');
        $this->load->model('dental/dosage/dosage_archive_model','dosage_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dosage_archive->trash($id);
           $response = "Dosage successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('289','1715');
        $this->load->model('dental/dosage/dosage_archive_model','dosage_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dosage_archive->trashall($post['row_id']);
            $response = "Dosage successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function simulation_dropdown()
  {
     $simulation_list = $this->dosage->simulation_list();
     $dropdown = '<option value="">Select Operation Type</option>'; 
     $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $dosage)
          {
               if(in_array($dosage->dosage,$simulations_array)){
                    $selected_simulation = 'selected="selected"';
               }
               else
               {
                  $selected_simulation = '';  
               }
               $dropdown .= '<option value="'.$dosage->id.'" '.$selected_simulation.' >'.$dosage->dosage.'</option>';
          }
     } 
     echo $dropdown; 
  }
  */
   public function dosage_dropdown()
  {
      $ot_type_list = $this->dosage->dosage_list();
      //print_r($ot_type_list);die;
      $dropdown = '<option value="">Select Dosage</option>'; 
      if(!empty($ot_type_list))
      {
        foreach($ot_type_list as $ot_type)
        {
           $dropdown .= '<option value="'.$ot_type->id.'">'.$ot_type->dosage.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>