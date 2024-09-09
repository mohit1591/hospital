<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blood_report_setting extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		//auth_users();  
		$this->load->model('blood_bank/blood_bank_report_setting_model','blood_bank_report_setting');
		$this->load->library('form_validation');
  }

  public function add()
	{
    
     
         $get_data=0;
         if(count($get_data)>0)
         {
             $data_id=$get_data['id'];
             $printer_type=$get_data['printer_id'];
             $printer_paper_type = $get_data['printer_paper_type'];
             $template=$get_data['template'];
             $short_code=$get_data['short_code'];
             $sub_section=$get_data['sub_section'];
             $component_id = $get_data['component_id'];
         }
         else
         {
             $data_id='';
             $printer_type='';
             $template='';
             $short_code='';
             $printer_paper_type='';
             $sub_section='';
             $component_id='';
         }
            $data['page_title'] = "Add Blood Bank Report setting";
            $data['form_error'] = [];
            
            $post = $this->input->post();
            $data['form_data'] = array(
                                  "data_id"=>$data_id,
                                  "printer_type"=>$printer_type,
                                  "sub_section"=>$sub_section,
                                  "printer_paper_type"=>$printer_paper_type,
                                  "message"=>$template,
                                  "short_code"=>$short_code,
                                  'component_id'=>$component_id,
                                  );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
              $this->blood_bank_report_setting->save();
              $this->session->set_flashdata('success','Blood Bank Report template succefully change.');
              redirect('blood_bank/blood_report_setting/add');
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']= $this->blood_general_model->get_component_list();
       
		$this->load->view('blood_bank/blood_bank_report/add',$data);
	}
  function get_according_to_section()
  {
        $subsection=$this->input->post('value');
        $get_data= $this->blood_bank_report_setting->template_format($subsection);
    //echo "<pre>"; print_r($get_data); die;
         if(count($get_data)>0){
             $data_id=$get_data['id'];
             $printer_type=$get_data['printer_id'];
             $printer_paper_type = $get_data['printer_paper_type'];
             $template=$get_data['template'];
             $short_code=$get_data['short_code'];
             $sub_section=$get_data['sub_section'];
             $component_id=$get_data['component_id'];
         }else{
             $data_id='';
             $printer_type='';
             $template='';
             $short_code='';
             $printer_paper_type='';
             $sub_section='';
             $component_id='';
         }
      
       $data['form_data'] = array(
                                    "data_id"=>$data_id,
                                    "printer_type"=>$printer_type,
                                    "sub_section"=>$sub_section,
                                    "printer_paper_type"=>$printer_paper_type,
                                    "message"=>$template,
                                    "short_code"=>$short_code,
                                    "component_id"=>$component_id,
                                    );
     $data['sub_section']=$subsection;
     $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']= $this->blood_general_model->get_component_list();
     $this->load->view('blood_bank/blood_bank_report/section_wise',$data);
     
  }

    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('printer_type', 'Printer type', 'trim|required');
        
       
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



  public function medicine_printtemplate_dropdown()
  {
      $value= $this->input->post('value');
      $sub_section=$this->input->post('sub_section');
      $data_array= array('printer_id'=>$value,'section'=>10,'types'=>1,'sub_section'=>$sub_section);
      $medicine_entry_list = $this->blood_bank_report_setting->template_list($data_array);
      $data['template']= $medicine_entry_list['template'];
      $data['short_code']= $medicine_entry_list['short_code'];
      echo json_encode($data);
  }




}