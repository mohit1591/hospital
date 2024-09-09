<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Source extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('crm/source/source_model','source');
        $this->load->library('form_validation');
    }

    public function index()
    {   
        unauthorise_permission(402,2444);
        $data['page_title'] = 'Source List'; 
        $this->load->view('crm/source/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(402,2444);
        $users_data = $this->session->userdata('auth_users'); 
        $permission = $this->session->userdata('permission');
        $list = $this->source->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $source) 
        {
         // print_r($source);die;
            $no++;
            $row = array();  
            $row[] = '<input type="checkbox" name="call_status[]" class="checklist" value="'.$source->id.'">';   
            $row[] = $source->source;  
            //$row[] = date('d-M-Y H:i A',strtotime($source->created_date)); 
            $btnedit='';
            $btndelete=''; 
            if(in_array('2446',$users_data['permission']['action'])) 
            {
               $btnedit =' <a href="javascript:void(0);" class="btn-custom" onClick="return edit_source('.$source->id.');"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>'; 
               
                $btndelete = ' <a class="btn-custom" onClick="return delete_source('.$source->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';  
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
                        "recordsTotal" => $this->source->count_all(),
                        "recordsFiltered" => $this->source->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    { 
        unauthorise_permission(402,2445);
        $data['page_title'] = "Add Source";  
        $post = $this->input->post(); 
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'source'=>""
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->source->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('crm/source/add',$data);       
    }

    public function edit($id="")
    { 
        unauthorise_permission(402,2446);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->source->get_by_id($id);   
         
        $data['page_title'] = "Update Source";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'source'=>$result['source']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->source->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('crm/source/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('source', 'source name', 'trim|required|callback_check_source');  
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'source'=>$post['source']
                                       ); 
            return $data['form_data'];
        }   
    }


    public function check_source($str)
    {
      $post = $this->input->post();
      if(empty($str))
      {
         $this->form_validation->set_message('check_source', 'The source field is required.');
         return false;
      }

        $cluster_data = $this->source->check_source($str,$post['data_id']);
        if(empty($cluster_data))
        {
           return true;
        }
        else
        {
          $this->form_validation->set_message('check_source', 'This source already exists.');
          return false;
        }
    } 


  public function source_dropdown()
  {
     $source_list = $this->source->crm_source_list();
     $dropdown = '<option value="">Select Source</option>';  
     
     if(!empty($source_list))
     {
          foreach($source_list as $source)
          { 
               $dropdown .= '<option value="'.$source->id.'" >'.$source->source.'</option>';
          }
     } 
     echo $dropdown; 
  }
 
    public function delete($id="")
    {
       //unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->source->delete($id);
           $response = "sSurce successfully deleted.";
           echo $response;
       }
    }

    

}
?>