<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Biometric_details extends CI_Controller 
{
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->library('form_validation');
    $this->load->model('eye/biometric_details/biometric_details_model','biometric_model');
  }

  public function add($opd_booking_id="")
  {
      $this->load->model('opd/opd_model','opd');
      $data['page_title']="Biometric Details";
      $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
      if($data['booking_data']!="")
      {
        $patient_id=$data['booking_data']['patient_id'];
        $data['ucva_bcva_data']=$this->biometric_model->get_biometric_details_ucva_bcva($opd_booking_id,$patient_id);
        $data['keratometer_details']=$this->biometric_model->get_biometric_details_keratometer($opd_booking_id,$patient_id);
        $data['iol_details']=$this->biometric_model->get_biometric_details_iol($opd_booking_id,$patient_id);
        $data['keratometer_data']=$this->biometric_model->get_keratometer_list();
        $data['iol_data']=$this->biometric_model->get_iol_section_list();

        //print_r($data['iol_details']);die;
        $this->load->view('eye/biometric_details/add',$data);  
      }
      else
      {
        redirect(base_url()."dashboard");
      }



      
  }

  public function save()
  {
    $users_data = $this->session->userdata('auth_users');
    $data_array=array("ucva_nva_left"=>$this->input->post('ucva_nva_left'),
                      "ucva_nva_right"=>$this->input->post('ucva_nva_right'),
                      "ucva_dva_left"=>$this->input->post('ucva_dva_left'),
                      "ucva_dva_right"=>$this->input->post('ucva_dva_right'),
                      "bcva_sph_left"=>$this->input->post('bcva_sph_left'),
                      "bcva_sph_right"=>$this->input->post('bcva_sph_right'),
                      "bcva_cyl_left"=>$this->input->post('bcva_cyl_left'),
                      "bcva_cyl_right"=>$this->input->post('bcva_cyl_right'),
                      "bcva_axis_left"=>$this->input->post('bcva_axis_left'),
                      "bcva_axis_right"=>$this->input->post('bcva_axis_right'),
                      "bcva_add_left"=>$this->input->post('bcva_add_left'),
                      "bcva_add_right"=>$this->input->post('bcva_add_right'),
                      "bcva_dva_left"=>$this->input->post('bcva_dva_left'),
                      "bcva_dva_right"=>$this->input->post('bcva_dva_right'),
                      "bcva_nva_left"=>$this->input->post('bcva_nva_left'),
                      "bcva_nva_right"=>$this->input->post('bcva_nva_right'),
                      "branch_id"=>$this->input->post('branch_id'),
                      "opd_booking_id"=>$this->input->post('opd_booking_id'),
                      "patient_id"=>$this->input->post('patient_id'),
                      "created_by"=>$users_data['id'],
                      "ip_address"=>$_SERVER['REMOTE_ADDR'],
                      );
    $this->biometric_model->common_insert('hms_eye_biometric_details_ucva_bcva',$data_array);
    $keratometer_left=$this->input->post('kera_le');
    $keratometer_right=$this->input->post('kera_re');
    $kara_main_array=array();
    foreach($keratometer_left as $key=>$val)
    {
      $kera_array=array(
                            "kera_id"=>$key,
                            "right_eye"=>$keratometer_right[$key],
                            "left_eye"=>$keratometer_left[$key],
                            "branch_id"=>$this->input->post('branch_id'),
                            "opd_booking_id"=>$this->input->post('opd_booking_id'),
                            "patient_id"=>$this->input->post('patient_id'),
                            "created_by"=>$users_data['id'],
                            "ip_address"=>$_SERVER['REMOTE_ADDR'],
                        );
      $this->biometric_model->common_insert('hms_eye_biometric_details_keratometer',$kera_array);
    }
    $iol_section_left=$this->input->post('iol_le');
    $iol_section_right=$this->input->post('iol_re');
    $kara_main_array=array();
    foreach($iol_section_left as $key=>$val)
    {
      $iol_array=array(
                            "iol_id"=>$key,
                            "right_eye"=>$iol_section_right[$key],
                            "left_eye"=>$iol_section_left[$key],
                            "branch_id"=>$this->input->post('branch_id'),
                            "opd_booking_id"=>$this->input->post('opd_booking_id'),
                            "patient_id"=>$this->input->post('patient_id'),
                            "created_by"=>$users_data['id'],
                            "ip_address"=>$_SERVER['REMOTE_ADDR'],
                        );
      $this->biometric_model->common_insert('hms_eye_biometric_details_iol',$iol_array);
    }  

  }

  // Function to update biometric details
  public function update()
  {
      $branch_id=$this->input->post('branch_id');
      $opd_booking_id=$this->input->post('opd_booking_id');
      $patient_id=$this->input->post('patient_id');
      $data_array=array("ucva_nva_left"=>$this->input->post('ucva_nva_left'),
                      "ucva_nva_right"=>$this->input->post('ucva_nva_right'),
                      "ucva_dva_left"=>$this->input->post('ucva_dva_left'),
                      "ucva_dva_right"=>$this->input->post('ucva_dva_right'),
                      "bcva_sph_left"=>$this->input->post('bcva_sph_left'),
                      "bcva_sph_right"=>$this->input->post('bcva_sph_right'),
                      "bcva_cyl_left"=>$this->input->post('bcva_cyl_left'),
                      "bcva_cyl_right"=>$this->input->post('bcva_cyl_right'),
                      "bcva_axis_left"=>$this->input->post('bcva_axis_left'),
                      "bcva_axis_right"=>$this->input->post('bcva_axis_right'),
                      "bcva_add_left"=>$this->input->post('bcva_add_left'),
                      "bcva_add_right"=>$this->input->post('bcva_add_right'),
                      "bcva_dva_left"=>$this->input->post('bcva_dva_left'),
                      "bcva_dva_right"=>$this->input->post('bcva_dva_right'),
                      "bcva_nva_left"=>$this->input->post('bcva_nva_left'),
                      "bcva_nva_right"=>$this->input->post('bcva_nva_right'),
                      "modified_by"=>$users_data['id'],
                      "ip_address"=>$_SERVER['REMOTE_ADDR'],
                      "modified_date"=>date('Y-m-d H:i:s'),
                      );
    $this->biometric_model->update_biometric_details_ucva_bcva($opd_booking_id,$patient_id,$branch_id,$data_array);

    $keratometer_left=$this->input->post('kera_le');
    $keratometer_right=$this->input->post('kera_re');
    $kara_main_array=array();
    foreach($keratometer_left as $key=>$val)
    {
      $kera_array=array(
                            "right_eye"=>$keratometer_right[$key],
                            "left_eye"=>$keratometer_left[$key],
                            "modified_by"=>$users_data['id'],
                            "ip_address"=>$_SERVER['REMOTE_ADDR'],
                            "modified_date"=>date('Y-m-d H:i:s'),
                        );
      $this->biometric_model->update_keratometer_details($opd_booking_id,$patient_id,$key,$branch_id, $kera_array);
    }

    $iol_section_left=$this->input->post('iol_le');
    $iol_section_right=$this->input->post('iol_re');
    $kara_main_array=array();
    foreach($iol_section_left as $key=>$val)
    {
      $iol_array=array(
                            "right_eye"=>$iol_section_right[$key],
                            "left_eye"=>$iol_section_left[$key],
                            "modified_by"=>$users_data['id'],
                            "ip_address"=>$_SERVER['REMOTE_ADDR'],
                            "modified_date"=>date('Y-m-d H:i:s'),
                        );
      $this->biometric_model->update_iol_details($opd_booking_id,$patient_id,$key,$branch_id, $iol_array);
    }  
  }
  // Function to update biometric details

  public function biometric_details_print($opd_booking_id="")
  {    
    $this->load->model('opd/opd_model','opd');
    $data['print_status']="1";
    $data['page_title']="print Biometric Details";
    $data['booking_data']=$this->opd->get_by_id($opd_booking_id);
    if($data['booking_data']!="")
    {
      $patient_id=$data['booking_data']['patient_id'];
      $data['ucva_bcva_data']=$this->biometric_model->get_biometric_details_ucva_bcva($opd_booking_id,$patient_id);
      $data['keratometer_details']=$this->biometric_model->get_biometric_details_keratometer($opd_booking_id,$patient_id);
      $data['iol_details']=$this->biometric_model->get_biometric_details_iol($opd_booking_id,$patient_id);
      $data['keratometer_data']=$this->biometric_model->get_keratometer_list();
      $data['iol_data']=$this->biometric_model->get_iol_section_list();
      $this->load->view('eye/biometric_details/biometric_details_print',$data);  
    }
    else
    {
      redirect(base_url()."dashboard");
    } 
  }
    


// Please write code above this
}
?>
