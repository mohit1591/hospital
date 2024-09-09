<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnose extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('diagnose/diagnose_model','simulation');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('12','64');
        $data['page_title'] = 'Diagnose List'; 
        $this->load->view('diagnose/list',$data);
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
            $checkboxs = "";
            if($users_data['parent_id']==$simulation->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$simulation->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            if($simulation->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
         
            $row[] = $simulation->diagnose;  
            $row[] = $status;
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$simulation->branch_id)
            {
              if(in_array('66',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_diagnose('.$simulation->id.');" class="btn-custom" href="javascript:void(0)" style="'.$simulation->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('67',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_diagnose('.$simulation->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
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
        $data['page_title'] = "Add Diagnose";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'diagnose'=>"",
                                  "status"=>1
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
       $this->load->view('diagnose/add',$data);       
    }
    
   
    public function edit($id="")
    {
     unauthorise_permission('12','66');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->simulation->get_by_id($id);  
        $data['page_title'] = "Update Diagnose";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'diagnose'=>$result['diagnose'], 
                                  'status'=>$result['status'],
                                  
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
       $this->load->view('diagnose/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('diagnose', 'diagnose', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'diagnose'=>$post['diagnose'], 
                                        'status'=>$post['status'],
                                        
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
           $response = "Diagnose successfully deleted.";
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
            $response = "Diagnose successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->simulation->get_by_id($id);  
        $data['page_title'] = $data['form_data']['diagnose']." detail";
        $this->load->view('diagnose/view',$data);     
      }
    }  

  public function diagnose_dropdown()
  {
     $simulation_list = $this->simulation->diagnose_list();
     $dropdown = '<option value="">Select Diagnose</option>'; 
     if(!empty($simulation_list))
     {
          foreach($simulation_list as $simulation)
          {
             $dropdown .= '<option value="'.$simulation->id.'">'.$simulation->simulation.'</option>';
          }
     } 
     echo $dropdown; 
  }
  

}
?>