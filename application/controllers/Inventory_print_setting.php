<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_print_setting extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('inventory_print_setting/inventory_print_setting_model','inventory_print_setting');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
       unauthorise_permission(165,952);
        $data['page_title'] = 'Inventory Print list'; 
        $this->load->view('inventory_print_setting/list',$data);
    }

 

	public function add()
	{
    
      unauthorise_permission(165,952);
     // $get_data= $this->medicine_print_setting->template_format();
       //print_r( $get_data);
      $get_data=0;
         if(count($get_data)>0){
             $data_id=$get_data['id'];
             $printer_type=$get_data['printer_id'];
             $printer_paper_type = $get_data['printer_paper_type'];
             $template=$get_data['template'];
             $short_code=$get_data['short_code'];
             $sub_section=$get_data['sub_section'];
         }else{
             $data_id='';
             $printer_type='';
             $template='';
             $short_code='';
             $printer_paper_type='';
             $sub_section='';
         }
            $this->load->model('general/general_model'); 
            $data['page_title'] = "Add inventory print setting";
            $data['form_error'] = [];
            
            $post = $this->input->post();
            $data['form_data'] = array(
                                  "data_id"=>$data_id,
                                  "printer_type"=>$printer_type,
                                  "sub_section"=>$sub_section,
                                  "printer_paper_type"=>$printer_paper_type,
                                  "message"=>$template,
                                  "short_code"=>$short_code
                                  );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->inventory_print_setting->save();
              $this->session->set_flashdata('success','Inventory template print succefully change.');
              redirect('inventory_print_setting/add');
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       
		$this->load->view('inventory_print_setting/add',$data);
	}
  function get_according_to_section()
  {
        $subsection=$this->input->post('value');
        $get_data= $this->inventory_print_setting->template_format($subsection);
     
         if(count($get_data)>0)
         {
             $data_id=$get_data['id'];
             $printer_type=$get_data['printer_id'];
             $printer_paper_type = $get_data['printer_paper_type'];
             $template=$get_data['template'];
             $short_code=$get_data['short_code'];
             $sub_section=$get_data['sub_section'];
         }
         else
         {
             $data_id='';
             $printer_type='';
             $template='';
             $short_code='';
             $printer_paper_type='';
             $sub_section='';
         }
      
              $data['form_data'] = array(
                                    "data_id"=>$data_id,
                                    "printer_type"=>$printer_type,
                                    "sub_section"=>$sub_section,
                                    "printer_paper_type"=>$printer_paper_type,
                                    "message"=>$template,
                                    "short_code"=>$short_code
                                    );
     $data['sub_section']=$subsection;
     $this->load->view('inventory_print_setting/inventory_print_section_wise',$data);
     
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



  public function inventory_printtemplate_dropdown()
  {
      $value= $this->input->post('value');
      $sub_section=$this->input->post('sub_section');
      $data_array= array('printer_id'=>$value,'section'=>9,'types'=>1,'sub_section'=>$sub_section);
      $inventory_entry_list = $this->inventory_print_setting->template_list($data_array);
      $data['template']= $inventory_entry_list['template'];
      $data['short_code']= $inventory_entry_list['short_code'];
      echo json_encode($data);
  }




}
