<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Under_maintenance_page extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('under_maintenance/Under_maintenance_model','under_maintenance');
        $this->load->library('form_validation');
    }

    public function index()
    {  
        $data['page_title'] = 'Under Maintenanace'; 
        $result= $this->under_maintenance->get_all_result();
        $data['result']=$result;
        $this->load->view('under_maintenance/page_m',$data);
    }


    public function open_popup_page()
    {
        // print_r($_POST);die;
        //unauthorise_permission('11','58');
        $data['page_title'] = 'Under Maintenanace';  
        $post = $this->input->post();
        $vendor_code = generate_unique_id(6);
        $data['form_error'] = []; 
        $result= $this->under_maintenance->get_all_result();
         if(!empty($result))
         {
           $data['form_data'] = array(
                                  'data_id'=>$result[0]->id,
                                  'status'=>$result[0]->status,
                                  'message'=>$result[0]->msg
                              );   
         }
         else
         {
           $data['form_data'] = array(
                                  'data_id'=>'',
                                  'status'=>"",
                                  'message'=>''
                              );   
         }
       
         


        if(isset($post) && !empty($post))
        {   
            //$data['form_data'] = $this->_validate();
           
            // if($this->form_validation->run() == TRUE)
            // {
                
                $this->under_maintenance->save();
                 echo 1;
                return false;
                
            // }
            // else
            // {
            //     $data['form_error'] = validation_errors();  
            // }     
        }
        $this->load->view('under_maintenance/page',$data);      
    }
     

}
?>