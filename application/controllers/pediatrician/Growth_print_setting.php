<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Growth_print_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();    
      auth_users();  
      $this->load->model('pediatrician/growth_print_setting/growth_print_setting_model','growthsetting');
      $this->load->library('form_validation');
  }

    public function add()
    {
        unauthorise_permission(275,1629);
      $get_data= $this->growthsetting->template_format();
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
            $data['page_title'] = "Add Growth Print Settings";
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
              $this->growthsetting->save();
              $this->session->set_flashdata('success','Growth template print succefully change.');
              redirect('pediatrician/growth_print_setting/add');
        }
        else
        {
              $data['form_error'] = validation_errors();  
        }       
      }
       
        $this->load->view('pediatrician/growth_print_setting/add',$data);
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



  public function growth_printtemplate_dropdown()
  {
      $value= $this->input->post('value');
      $data_array= array('printer_id'=>$value,'section'=>12,'types'=>4);
      $growth_list = $this->growthsetting->template_list($data_array);
      $data['template']= $growth_list['template'];
      $data['short_code']= $growth_list['short_code'];
      echo json_encode($data);
  }

 } ?>