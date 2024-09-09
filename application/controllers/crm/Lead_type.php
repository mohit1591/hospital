<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('crm/lead_type/lead_type_model','lead_type');
        $this->load->library('form_validation');
    }

    public function index()
    {   
        unauthorise_permission(399,2436);
        $data['page_title'] = 'Lead Type List'; 
        $this->load->view('crm/lead_type/list',$data);
    }

    public function ajax_list()
    {    
        unauthorise_permission(399,2436);
        $users_data = $this->session->userdata('auth_users');  
         $list = $this->lead_type->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $lead_type) 
        {
         // print_r($lead_type);die;
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" name="patient[]" class="checklist" value="'.$lead_type->id.'">';  
            $row[] = $lead_type->lead_type;   
            //$row[] = date('d-M-Y H:i A',strtotime($lead_type->created_date)); 
            $btnedit=''; 

            if(in_array('2438',$users_data['permission']['action'])) 
            {
               $btnedit ='<a href="javascript:void(0);" class="btn-custom" onClick="return edit_lead_type('.$lead_type->id.');"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
            
             
               $btndelete = ' <a class="btn-custom" onClick="return delete_lead_type('.$lead_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               
                
            }
            
            
            $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->lead_type->count_all(),
                        "recordsFiltered" => $this->lead_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {  
        unauthorise_permission(399,2437);
        $data['page_title'] = "Add Lead Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'lead_type'=>""
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->lead_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('crm/lead_type/add',$data);       
    }

    public function edit($id="")
    {  
       unauthorise_permission(399,2438); 
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->lead_type->get_by_id($id);  
        $data['page_title'] = "Update Lead Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'lead_type'=>$result['lead_type']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->lead_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('crm/lead_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('lead_type', 'Lead Type name', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'lead_type'=>$post['lead_type'] 
                                       ); 
            return $data['form_data'];
        }   
    } 

 public function lead_type_dropdown()
  {
     $lead_type_list = $this->lead_type->crm_lead_type_list();
     $dropdown = '<option value="">Select Lead Type</option>';  
     
     if(!empty($lead_type_list))
     {
          foreach($lead_type_list as $lead_type)
          { 
               $dropdown .= '<option value="'.$lead_type->id.'" >'.$lead_type->lead_type.'</option>';
          }
     } 
     echo $dropdown; 
  }
  
  
  public function delete($id="")
    {
       //unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->lead_type->delete($id);
           $response = "Lead Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        //unauthorise_permission('12','67');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->lead_type->deleteall($post['row_id']);
            $response = "Lead Type successfully deleted.";
            echo $response;
        }
    }
}
?>