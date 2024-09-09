<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daycare_discharge_medicine_print_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('daycare_discharge_medicine_print_setting/discharge_medicine_print_setting_model','discharge_medicine_print');
      $this->load->library('form_validation');
  }

    
  public function index()
  {
      //unauthorise_permission(125,762);
      $data['page_title'] = 'Expense Print List'; 
      $this->load->view('daycare_discharge_medicine_print_setting/list',$data);
  }

 

	public function add()
	{
      //unauthorise_permission(125,762);
      $get_data= $this->discharge_medicine_print->template_format();
      //print_r($get_data);die;
      if(count($get_data)>0)
      {
          $data_id=$get_data['id'];
          $template=$get_data['setting_value'];
        
      }
      else
      {
          $data_id='';
          $template='';
       }
      		$data['page_title'] = "Add Day Care Discharge Medicine Print Settings";
      		$data['form_error'] = [];
      		$post = $this->input->post();
      		$data['form_data'] = array(
                                      "data_id"=>$data_id,
                                      "message"=>$template,
                                  );
      if(isset($post) && !empty($post))
      {   
              $this->discharge_medicine_print->save();
              $this->session->set_flashdata('success','Discharge Medicine template print succefully change.');
              redirect('daycare_discharge_medicine_print_setting/add');
        
          
      }
      
		$this->load->view('daycare_discharge_medicine_print_setting/add',$data);
	}



  public function discharge_medicine_printtemplate_dropdown()
  {
      $value= $this->input->post('value');
      $data_array= array('printer_id'=>$value,'section'=>4,'types'=>1);
      $opd_list = $this->discharge_medicine_print->template_list($data_array);
      $data['template']= $opd_list['template'];
      $data['short_code']= $opd_list['short_code'];
      echo json_encode($data);
  }

 } ?>