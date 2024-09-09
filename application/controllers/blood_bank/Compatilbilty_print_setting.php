<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compatilbilty_print_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('blood_bank/compatilbilty_print_setting/compatilbilty_print_setting_model','expense_print');
      $this->load->library('form_validation');
  }

    
  public function index()
  {
      
      $data['page_title'] = 'Compatilbilty Print List'; 
      $this->load->view('blood_bank/compatilbilty_print_setting/list',$data);
  }

 

	public function add()
	{
      
      $get_data= $this->expense_print->template_format();
      //print_r($get_data);die;
      if(count($get_data)>0)
      {
          $data_id=$get_data['id'];
          $template=$get_data['template'];
        
      }
      else
      {
          $data_id='';
          $template='';
       }
      		$data['page_title'] = "Add Compatilbilty Print Settings";
      		$data['form_error'] = [];
      		$post = $this->input->post();
      		$data['form_data'] = array(
                                      "data_id"=>$data_id,
                                      "message"=>$template,
                                  );
      if(isset($post) && !empty($post))
      {   
         
              $this->expense_print->save();
              $this->session->set_flashdata('success','Compatilbilty template print succefully change.');
              redirect('blood_bank/compatilbilty_print_setting/add');
        
          
      }
      
		$this->load->view('blood_bank/compatilbilty_print_setting/add',$data);
	}


  


  public function expense_printtemplate_dropdown()
  {
      $value= $this->input->post('value');
      $data_array= array('printer_id'=>$value,'section'=>4,'types'=>1);
      $opd_list = $this->expense_print->template_list($data_array);
      $data['template']= $opd_list['template'];
      $data['short_code']= $opd_list['short_code'];
      echo json_encode($data);
  }

 } ?>