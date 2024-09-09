<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('crm/department/department_model','department');
        $this->load->library('form_validation');
    }

    public function index()
    {   
        unauthorise_permission(401,2442);
        $data['page_title'] = 'Service Department List'; 
        $this->load->view('crm/department/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(401,2442);
        $users_data = $this->session->userdata('auth_users'); 
        $permission = $this->session->userdata('permission');
        $list = $this->department->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $department) 
        {
         // print_r($department);die;
            $no++;
            $row = array();  
            $row[] = '<input type="checkbox" name="department[]" class="checklist" value="'.$department->id.'">';   
            $row[] = $department->department;  
            $row[] = date('d-M-Y H:i A',strtotime($department->created_date)); 
            $btnedit='';
            $btndelete=''; 
            
            if(in_array('2443',$users_data['permission']['action'])) 
            {
               $btnedit =' <a href="javascript:void(0);" class="btn-custom" onClick="return edit_department('.$department->id.');"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>';
            }
            else
            {
               $btnedit='N/A';
            } 
                
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->department->count_all(),
                        "recordsFiltered" => $this->department->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    { 
        unauthorise_permission(401,2443);
        $data['page_title'] = "Add Service Department";  
        $post = $this->input->post(); 
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'department'=>""
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->department->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('crm/department/add',$data);       
    }

    public function edit($id="")
    { 
     unauthorise_permission(401,2443);   
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->department->get_by_id($id);   
         
        $data['page_title'] = "Update Service Department";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'department'=>$result['department']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->department->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('crm/department/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('department', 'Service Department name', 'trim|required|callback_check_department');  
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'department'=>$post['department']
                                       ); 
            return $data['form_data'];
        }   
    }


    public function check_department($str)
    {
      $post = $this->input->post();
      if(empty($str))
      {
         $this->form_validation->set_message('check_department', 'The Service Department field is required.');
         return false;
      }

        $cluster_data = $this->department->check_department($str,$post['data_id']);
        if(empty($cluster_data))
        {
           return true;
        }
        else
        {
          $this->form_validation->set_message('check_department', 'This Service Department already exists.');
          return false;
        }
    } 
 


    

}
?>