<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_room_transfer extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_room_transfer/ipd_room_transfer_model','room_transfer');
        $this->load->library('form_validation');
    }

   
    public function add($ipd_id="",$patient_id="")
    {
       unauthorise_permission(122,740);
       $this->load->model('general/general_model');
       $data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
       $data['room_category']= $this->general_model->check_room_type();

       $data['page_title']="Room Transfer";
       $data['form_error'] = []; 
       $post= $this->input->post();
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'ipd_id'=>$ipd_id,
                                  "patient_id"=>$patient_id,
                                  "room_id"=>'',
                                  "room_no_id"=>'',
                                  'bed_no_id'=>'',
                                  //'card_no'=>'',
                                  'transfer_date'=>date('d-m-Y'),
                                  "transfer_time"=>date('H:i:s')
                                  );    

        if(isset($post) && !empty($post))
        { 
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->room_transfer->save();
                //$this->session->set_userdata('ipd_room_transfer_id',$ipd_room_transfer_id);
                $this->session->set_flashdata('success','Room has been successfully transfer.');
               
                redirect(base_url('ipd_room_transfer/add/'.$ipd_id.'/'.$patient_id)); // /?status=print
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }  
         }
       //print_r( $data['form_error']);die;
       $this->load->view('ipd_room_transfer/add',$data); 
    }
    
    
     
    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('room_id', 'room type', 'trim|required'); 
        $this->form_validation->set_rules('room_no_id', 'room no', 'trim|required'); 
        $this->form_validation->set_rules('bed_no_id', 'bed no', 'trim|required'); 
        $this->form_validation->set_rules('transfer_date', 'transfer date', 'trim|required'); 
        $this->form_validation->set_rules('transfer_time', 'transfer time', 'trim|required'); 
      
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                      'data_id'=>"", 
                                      'ipd_id'=>$post['ipd_id'],
                                      "patient_id"=>$post['patient_id'],
                                      "room_id"=>$post['room_id'],
                                      "room_no_id"=>$post['room_no_id'],
                                      'bed_no_id'=>$post['bed_no_id'],
                                      //'card_no'=>'',
                                      'transfer_date'=>date('d-m-Y',strtotime($post['transfer_date'])),
                                      "transfer_time"=>date('H:i:s',strtotime($post['transfer_time']))
                                      ); 
            return $data['form_data'];
        }   
    }


}
?>