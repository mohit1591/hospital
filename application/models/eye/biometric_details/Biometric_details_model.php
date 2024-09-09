<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biometric_details_model extends CI_Model 
{
	// function to get keratometer masters list
	public function get_keratometer_list()
	{
		$users_data = $this->session->userdata('auth_users');
		$branch_id=$users_data['parent_id'];
		$this->db->select('id,keratometer');
		$this->db->from('hms_eye_keratometer');
		$this->db->where('status',1);
		$this->db->where('is_deleted!=',2);
		$this->db->where('branch_id',$branch_id);
		$res=$this->db->get();
		if($res->num_rows() > 0)
			return $res->result();
		else
			return "empty";
	}
	// function to get keratometer masters list

	// function to get iol masters list
	public function get_iol_section_list()
	{
		$users_data = $this->session->userdata('auth_users');
		$branch_id=$users_data['parent_id'];
		$this->db->select('id,iol_section');
		$this->db->from('hms_eye_iol_section');
		$this->db->where('is_deleted!=',2);
		$this->db->where('status',1);
		$this->db->where('branch_id',$branch_id);
		$res=$this->db->get();
		if($res->num_rows() > 0)
			return $res->result();
		else
			return "empty";
	}

	public function common_insert($tablename,$dataarray)
	{
		$this->db->insert($tablename,$dataarray);
		return "200";
	}
	public function common_pre_insert($tablename,$parameter)
	{
		$users_data = $this->session->userdata('auth_users');
		$branch_id=$users_data['parent_id'];
		if($parameter==1)
		{	$this->db->where('hms_eye_biometric_details_ucva_bcva.branch_id',$branch_id);
			$this->db->delete('hms_eye_biometric_details_ucva_bcva');
		}
		if($parameter==2)
		{
			$this->db->where('hms_eye_biometric_details_keratometer.branch_id',$branch_id);
			$this->db->delete('hms_eye_biometric_details_keratometer');
		}
		if($parameter==3)	
		{	
			$this->db->where('hms_eye_biometric_details_iol.branch_id',$branch_id);
			$this->db->delete('hms_eye_biometric_details_iol');
		}	
		//$this->db->insert($tablename,$dataarray);
		//echo $this->db->last_query();die;
		return "200";
	}
	// function to get iol masters list


	// function to get patient booking ucva and bcva data 
	public function get_biometric_details_ucva_bcva($opd_booking_id, $patient_id)
	{
		$users_data = $this->session->userdata('auth_users');
		$branch_id=$users_data['parent_id'];
		$this->db->select('*');
		$this->db->from('hms_eye_biometric_details_ucva_bcva');
		$this->db->where('branch_id',$branch_id);
		$this->db->where('opd_booking_id',$opd_booking_id);
		$this->db->where('patient_id',$patient_id);
		$res=$this->db->get();
		if($res->num_rows()  > 0)
			return $res->result();
		else
			return "empty";
	}
	// function to get patient booking ucva and bcva data

	// function to get patient keratometer details
	public function get_biometric_details_keratometer($opd_booking_id,$patient_id)
	{
		$users_data = $this->session->userdata('auth_users');
		$branch_id=$users_data['parent_id'];
		$this->db->select('bdk.*, ek.keratometer');
		$this->db->from('hms_eye_biometric_details_keratometer as bdk');
		$this->db->join('hms_eye_keratometer ek', "ek.id=bdk.kera_id");
		$this->db->where('bdk.branch_id',$branch_id);
		$this->db->where('bdk.opd_booking_id',$opd_booking_id);
		$this->db->where('bdk.patient_id',$patient_id);
		$res=$this->db->get();
		if($res->num_rows()  > 0)
			return $res->result();
		else
			return "empty";	
	}
	// fucntion to get patient keratometer details
	
	// function to get patient iol details
	public function get_biometric_details_iol($opd_booking_id, $patient_id)
	{
		$users_data = $this->session->userdata('auth_users');
		$branch_id=$users_data['parent_id'];
		$this->db->select('bdi.*, iol.iol_section');
		$this->db->from('hms_eye_biometric_details_iol as bdi');
		$this->db->join('hms_eye_iol_section iol', "iol.id=bdi.iol_id");
		$this->db->where('bdi.branch_id',$branch_id);
		$this->db->where('bdi.opd_booking_id',$opd_booking_id);
		$this->db->where('bdi.patient_id',$patient_id);
		$res=$this->db->get();
		if($res->num_rows() > 0)
			return $res->result();
		else
			return "empty";	
	}
	// function to get patient iol details	

	// function to update biometric ucva and bcva details
	public function update_biometric_details_ucva_bcva($opd_booking_id,$patient_id,$branch_id,$data_array)
	{	
		$this->db->where('opd_booking_id',$opd_booking_id);
		$this->db->where('patient_id',$patient_id);
		$this->db->where('branch_id',$branch_id);
		$this->db->update('hms_eye_biometric_details_ucva_bcva',$data_array);
		return "200";
	}
	// function to update biometric ucva and bcva details

	// Function to update keratometer details
	public function update_keratometer_details($opd_booking_id,$patient_id,$key,$branch_id,$kera_array)
	{
		$this->db->where('branch_id',$branch_id);
		$this->db->where('opd_booking_id',$opd_booking_id);
		$this->db->where('patient_id',$patient_id);
		$this->db->where('kera_id',$key);
		$this->db->update('hms_eye_biometric_details_keratometer',$kera_array);
	}		
	// function to update keratometer details

	// Function to update iol section details
	public function update_iol_details($opd_booking_id,$patient_id,$key,$branch_id,$iol_array)
	{
		$this->db->where('branch_id',$branch_id);
		$this->db->where('opd_booking_id',$opd_booking_id);
		$this->db->where('patient_id',$patient_id);
		$this->db->where('iol_id',$key);
		$this->db->update('hms_eye_biometric_details_iol',$iol_array);
	}		
	// function to update iol section details



// Please code above this	
}
?>