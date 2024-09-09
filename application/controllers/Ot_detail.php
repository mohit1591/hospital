<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_detail extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_detail/ot_detail_model','ot_detail');
        $this->load->library('form_validation');
    }


    
    public function add($id='',$ot_id='',$ot_amount='')
    {
      
       
        $data['page_title'] = "OT Detail";  
        $result_ot_details = $this->ot_detail->get_by_id_ot_details($id,$ot_id);
        $post = $this->input->post();
     // print_r($result_ot_details);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>$id, 
                                  'ot_mgt_id'=>$ot_id,
                                  'ot_amount'=>$ot_amount,
                                  'ot_details'=>$result_ot_details,
                                  );
        //print_r($data['form_data']);die;

        if(isset($post) && !empty($post))
        {   
            
            //if($this->form_validation->run() == TRUE)
            //{
              //echo "hi";die;
               $this->ot_detail->save();
                echo 1;
                return false;
                
           // }
            //else
            //{

                //$data['form_error'] = validation_errors();  
           // }     
        }
       $this->load->view('ot_detail/add',$data);       
    }



 
}
?>