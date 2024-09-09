<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfusion_print_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('blood_bank/transfusion_print_setting/transfusion_print_setting_model','expense_print');
      $this->load->library('form_validation');
  }

    
  public function index()
  {
      
      $data['page_title'] = 'Transfusion Print List'; 
      $this->load->view('blood_bank/transfusion_print_setting/list',$data);
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
      		$data['page_title'] = "Add Transfusion Print Settings";
      		$data['form_error'] = [];
      		$post = $this->input->post();
      		$data['form_data'] = array(
                                      "data_id"=>$data_id,
                                      "message"=>$template,
                                  );
      if(isset($post) && !empty($post))
      {   
              $this->expense_print->save();
              $this->session->set_flashdata('success','Transfusion template print succefully change.');
              redirect('blood_bank/transfusion_print_setting/add');
        
          
      }
      
		$this->load->view('blood_bank/transfusion_print_setting/add',$data);
	}


  /*  private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('printer_type', 'printer type', 'trim|required');
        
       
        if ($this->form_validation->run() == FALSE) 
        {  
           
            $data['form_data'] = array(
                                    "message"=>$_POST['message'],
                                   );  
            return $data['form_data'];
        }   
    }*/



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