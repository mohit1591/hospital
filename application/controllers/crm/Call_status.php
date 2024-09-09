<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Call_status extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('crm/call_status/call_status_model','call_status');
        $this->load->library('form_validation');
    }

    public function index()
    {   
        unauthorise_permission(400,2439);
        $data['page_title'] = 'Call Status List'; 
        $this->load->view('crm/call_status/list',$data);
    }

    public function ajax_list()
    {    
        unauthorise_permission(400,2439);
        $users_data = $this->session->userdata('auth_users');  
         $list = $this->call_status->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $call_status) 
        {
         // print_r($call_status);die;
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" name="call_status[]" class="checklist" value="'.$call_status->id.'">';   
            $row[] = $call_status->call_status;   
            //$row[] = date('d-M-Y H:i A',strtotime($call_status->created_date)); 
            $btnedit=''; 

            if(in_array('2441',$users_data['permission']['action'])) 
            {
               $btnedit ='<a href="javascript:void(0);" class="btn-custom" onClick="return edit_call_status('.$call_status->id.');"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               
                $btndelete = ' <a class="btn-custom" onClick="return delete_call_status('.$call_status->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';  
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
                        "recordsTotal" => $this->call_status->count_all(),
                        "recordsFiltered" => $this->call_status->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {  
        unauthorise_permission(400,2440);
        $data['page_title'] = "Add Call Status";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'call_status'=>""
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->call_status->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('crm/call_status/add',$data);       
    }

    public function edit($id="")
    {  
        unauthorise_permission(400,2441);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->call_status->get_by_id($id);  
        $data['page_title'] = "Update Call Status";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'call_status'=>$result['call_status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->call_status->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('crm/call_status/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('call_status', 'Call Status name', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'call_status'=>$post['call_status'] 
                                       ); 
            return $data['form_data'];
        }   
    } 


    public function call_status_dropdown()
      {
         $lead_type_list = $this->call_status->crm_call_status_list();
         $dropdown = '<option value="">Select Call Status</option>';  
         
         if(!empty($lead_type_list))
         {
              foreach($lead_type_list as $lead_type)
              { 
                   $dropdown .= '<option value="'.$lead_type->id.'" >'.$lead_type->call_status.'</option>';
              }
         } 
         echo $dropdown; 
      }
      
      public function delete($id="")
    {
       //unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->call_status->delete($id);
           $response = "Call status successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        //unauthorise_permission('12','67');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->call_status->deleteall($post['row_id']);
            $response = "Call status successfully deleted.";
            echo $response;
        }
    }
}
?>