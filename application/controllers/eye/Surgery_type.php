<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surgery_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/surgery_type/surgery_type_model','surgery_type');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('243','1391');
        $data['page_title'] = 'Surgery type List'; 
        $this->load->view('eye/surgery_type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('243','1391');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->surgery_type->get_datatables();
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $surgery_type) {
         // print_r($surgery_type);die;
            $no++;
            $row = array();
            if($surgery_type->status==1)
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
            if($users_data['parent_id']==$surgery_type->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$surgery_type->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $surgery_type->surgery_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($surgery_type->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$surgery_type->branch_id)
            {
              if(in_array('1393',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_surgery('.$surgery_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$surgery_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
              if(in_array('1394',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_surgery_type('.$surgery_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->surgery_type->count_all(),
                        "recordsFiltered" => $this->surgery_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function check_surgery_type($str)
    {

      $post = $this->input->post();

      if(!empty($str))
      {
          $this->load->model('eye/general/eye_general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                $data_cat= $this->surgery_type->get_by_id($post['data_id']);
                if($data_cat['surgery_type']==$str && $post['data_id']==$data_cat['id'])
                {
                return true;  
                }
                else
                {
                $surgerydata = $this->general->check_surgery_type($str);

                if(empty($surgerydata))
                {
                return true;
                }
                else
                {
                $this->form_validation->set_message('check_surgery_type', 'The surgery type already exists.');
                return false;
                }
                }
          }
          else
          {
                  $surgerydata = $this->general->check_surgery_type($str);
                  if(empty($surgerydata))
                  {
                     return true;
                  }
                  else
                  {
              $this->form_validation->set_message('check_surgery_type', 'The surgery type already exists.');
              return false;
                  }
          }  
      }
      else
      {
        $this->form_validation->set_message('check_surgery_type', 'The surgery type field is required.');
              return false; 
      } 
    }
    
    
    public function add()
    {
      //echo json_encode("hi");die;
        unauthorise_permission('243','1392');
        $data['page_title'] = "Add Surgery Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'surgery_type'=>"",
                                  'status'=>"1"
                                   );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->surgery_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/surgery_type/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('243','1393');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->surgery_type->get_by_id($id);  
        $data['page_title'] = "Update Surgery Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'surgery_type'=>$result['surgery_type'], 
                                  'status'=>$result['status']
                                   );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->surgery_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/surgery_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('surgery_type', 'surgery type', 'trim|required|callback_check_surgery_type'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'surgery_type'=>$post['surgery_type'], 
                                        'status'=>$post['status']
                                         ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('243','1394');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->surgery_type->delete($id);
           $response = "Surgery Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('243','1394');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->surgery_type->deleteall($post['row_id']);
            $response = "Surgery type successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->surgery_type->get_by_id($id);  
        $data['page_title'] = $data['form_data']['surgery_type']." detail";
        $this->load->view('eye/surgery_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('243','1395');
        $data['page_title'] = 'Surgery Type Archive List';
        $this->load->helper('url');
        $this->load->view('eye/surgery_type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('243','1395');
        $this->load->model('eye/surgery_type/surgery_type_archive_model','surgery_type_archive'); 

      
               $list = $this->surgery_type_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $surgery_type) {
         // print_r($surgery_type);die;
            $no++;
            $row = array();
            if($surgery_type->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$surgery_type->id.'">'.$check_script; 
            $row[] = $surgery_type->surgery_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($surgery_type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1397',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_surgery_type('.$surgery_type->id.');" class="btn-custom" href="javascript:void(0)"  title="Surgery Type"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1396',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$surgery_type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->surgery_type_archive->count_all(),
                        "recordsFiltered" => $this->surgery_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('243','1397');
        $this->load->model('eye/surgery_type/surgery_type_archive_model','surgery_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->surgery_type_archive->restore($id);
           $response = "Surgery Type successfully restore in Surgery Type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('243','1397');
        $this->load->model('eye/surgery_type/surgery_type_archive_model','surgery_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->surgery_type_archive->restoreall($post['row_id']);
            $response = "Surgery type successfully restore in Surgery Type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('243','1396');
        $this->load->model('eye/surgery_type/surgery_type_archive_model','surgery_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->surgery_type_archive->trash($id);
           $response = "Surgery Type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('243','1396');
        $this->load->model('eye/surgery_type/surgery_type_archive_model','surgery_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->surgery_type_archive->trashall($post['row_id']);
            $response = "Surgery Type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function simulation_dropdown()
  {
     $simulation_list = $this->surgery_type->simulation_list();
     $dropdown = '<option value="">Select Operation Type</option>'; 
     $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $surgery_type)
          {
               if(in_array($surgery_type->surgery_type,$simulations_array)){
                    $selected_simulation = 'selected="selected"';
               }
               else
               {
                  $selected_simulation = '';  
               }
               $dropdown .= '<option value="'.$surgery_type->id.'" '.$selected_simulation.' >'.$surgery_type->surgery_type.'</option>';
          }
     } 
     echo $dropdown; 
  }
  */
   public function surgery_type_dropdown()
  {
      $ot_type_list = $this->surgery_type->surgery_type_list();
      //print_r($ot_type_list);die;
      $dropdown = '<option value="">Select Surgery Type</option>'; 
      if(!empty($ot_type_list))
      {
        foreach($ot_type_list as $ot_type)
        {
           $dropdown .= '<option value="'.$ot_type->id.'">'.$ot_type->surgery_type.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>