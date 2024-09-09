<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Mandatory_field_manage extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('mandatory_field_manage/Mandatory_field_manage_model','mandatory_field_manage');
		$this->load->library('form_validation');
	}

	public function index()
	{
	
	}
    public function mandatory_fields(){
   	  $post = $this->input->post();
   	  $mandatory_fields = $this->mandatory_field_manage->get_mandatory_fields();
   	 
      $all_fields = $this->mandatory_field_manage->get_all_fields();
      //$mandatory_sections = array('Doctors','Patients','OPD Booking','OPD Billing','Medicine Entry','IPD Booking','Test Master','Test Booking','Ambulance');
       $mandatory_sections = array('Doctors','Patients','OPD Booking','OPD Billing','Medicine Entry','IPD Booking','Test Master','Test Booking','Ambulance','Medicine purchase','Medicine purchase return','Medicine Sale','Medicine Sale return');
       
   	  $data['page_title'] = 'Manage Mandatory Fields';
   	  $data['mandatory_fields'] = $mandatory_fields;
   	  $data['all_fields'] = $all_fields;
   	  $data['mandatory_sections'] = $mandatory_sections;
      if(isset($post) && !empty($post)){

      }
      $this->load->view('mandatory_field_manage/mandatory_manage',$data);
   }
   public function save_mandatory_fields(){
   	$post = $this->input->post();
      //print_r($post); exit;
      if (array_key_exists("mandatory_fields_ids",$post)){
         $mandatory_fields = $post['mandatory_fields_ids'];
      }
      else{
         $mandatory_fields=array();
      }
   	$this->mandatory_field_manage->save_mandatory_fields($mandatory_fields);
   	
   }
 
}
?>