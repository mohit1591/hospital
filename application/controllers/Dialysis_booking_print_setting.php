<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_booking_print_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('dialysis_booking_print_setting/dialysis_booking_print_setting_model','dialysis_booking_print');
      $this->load->library('form_validation');
  }

    
  public function index()
  {
      unauthorise_permission(208,1204);
      $data['page_title'] = 'Dialysis Booking Print List'; 
      $this->load->view('dialysis_booking_print_setting/list',$data);
  }

 

	public function add()
	{
      unauthorise_permission(208,1204);
      $get_data= $this->dialysis_booking_print->template_format();
      if(count($get_data)>0)
      {
          $data_id=$get_data['id'];
          $printer_type=$get_data['printer_id'];
          $template=$get_data['template'];
          $printer_paper_type = $get_data['printer_paper_type'];
          $short_code=$get_data['short_code'];
      }
      else
      {
          $data_id='';
          $printer_type='';
          $template='';
          $short_code='';
          $printer_paper_type='';
      }
      		$data['page_title'] = "Dialysis Booking Print Settings";
      		$data['form_error'] = [];
      		$post = $this->input->post();
      		$data['form_data'] = array(
                                      "data_id"=>$data_id,
                                      "printer_type"=>$printer_type,
                                      "printer_paper_type"=>$printer_paper_type,
                                      "message"=>$template,
                                      "short_code"=>$short_code
      			                      );
      if(isset($post) && !empty($post))
      {   
        $data['form_data'] = $this->_validate();
        if($this->form_validation->run() == TRUE)
        {
              $this->dialysis_booking_print->save();
              $this->session->set_flashdata('success','Dialysis Booking template print succefully change.');
              redirect('dialysis_booking_print_setting/add');
        }
        else
        {
              $data['form_error'] = validation_errors();  
        }       
      }
       
		$this->load->view('dialysis_booking_print_setting/add',$data);
	}


    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('printer_type', 'printer type', 'trim|required');
        
       
        if ($this->form_validation->run() == FALSE) 
        {  
           
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'],
                                    "printer_type"=>$_POST['printer_type'],
                                    "message"=>$_POST['message'],
                                    "short_code"=>$_POST['short_code']
                                  );  
            return $data['form_data'];
        }   
    }



  public function dialysis_booking_printtemplate_dropdown()
  {
      $value= $this->input->post('value');
      $data_array= array('printer_id'=>$value,'section'=>8,'types'=>1);
      $opd_list = $this->dialysis_booking_print->template_list($data_array);
      $data['template']= $opd_list['template'];
      $data['short_code']= $opd_list['short_code'];
      echo json_encode($data);
  }

 } ?>