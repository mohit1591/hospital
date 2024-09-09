<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_pack_detail extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_pack_detail/ot_pack_detail_model','ot_pack_detail');
        $this->load->library('form_validation');
    }


    
    public function add($id='',$pack_id='',$package_amount='')
    {
      
        //echo 'fsf';die;
        $data['page_title'] = "OT Detail";  
        $result_ot_details = $this->ot_pack_detail->get_by_id_ot_details($id,$pack_id);
        $post = $this->input->post();
     //  print_r($post);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>$id, 
                                  'package_id'=>$pack_id,
                                  'package_amount'=>$package_amount,
                                  'ot_details'=>$result_ot_details,
                                  );
        //print_r($data['form_data']);die;

        if(isset($post) && !empty($post))
        {   
            
            //if($this->form_validation->run() == TRUE)
            //{
              //echo "hi";die;
               $this->ot_pack_detail->save();
                echo 1;
                return false;
                
           // }
            //else
            //{

                //$data['form_error'] = validation_errors();  
           // }     
        }
       $this->load->view('ot_pack_detail/add',$data);       
    }



 
}
?>