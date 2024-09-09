<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operation_summary_model extends CI_Model 
{

    // Functions for operation summary
    public function common_insert($table_name,$data_array)
    {
    	$id=$this->db->insert($table_name,$data_array);
    	return $this->db->insert_id();
    }

    public function get_data_by_id($ot_booking_id,$patient_id,$branch_id)
    {
    	$this->db->select('hms_operation_summary.*,hms_eye_ot_procedure.ot_procedure, hms_eye_ot_post_observations.post_observations');
    	$this->db->from('hms_operation_summary');
    	$this->db->where('hms_operation_summary.branch_id',$branch_id);
    	$this->db->where('hms_operation_summary.patient_id',$patient_id);
    	$this->db->where('hms_operation_summary.ot_booking_id',$ot_booking_id);
    	$this->db->join('hms_eye_ot_procedure', 'hms_eye_ot_procedure.id=hms_operation_summary.ot_procedure_id','left');
    	$this->db->join('hms_eye_ot_post_observations', 'hms_eye_ot_post_observations.id=hms_operation_summary.post_observation_id','left');
    	$res=$this->db->get();
    	return $res->row_array();
    }

    public function get_procedure_data_by_id($ot_booking_id,$id,$patient_id,$branch_id)
    {
    	$this->db->select('hms_operation_procedure_note_summary.*,hms_eye_ot_procedure.ot_procedure, hms_eye_ot_post_observations.post_observations');
    	$this->db->from('hms_operation_procedure_note_summary');
    	$this->db->where('hms_operation_procedure_note_summary.branch_id',$branch_id);
    	$this->db->where('hms_operation_procedure_note_summary.patient_id',$patient_id);
    	$this->db->where('hms_operation_procedure_note_summary.ot_booking_id',$ot_booking_id);
        $this->db->where('hms_operation_procedure_note_summary.id',$id);
    	$this->db->join('hms_eye_ot_procedure', 'hms_eye_ot_procedure.id=hms_operation_procedure_note_summary.ot_procedure_id','left');
    	$this->db->join('hms_eye_ot_post_observations', 'hms_eye_ot_post_observations.id=hms_operation_procedure_note_summary.post_observation_id','left');
    	$res=$this->db->get();
    	return $res->row_array();
    }

    // Function to get medicine data of summary
    public function get_summary_medicine_data($ot_booking_id,$patient_id,$branch_id,$summary_data_id)
    {
    	$this->db->select('hms_operation_summary_to_medicine.*');
    	$this->db->from('hms_operation_summary_to_medicine');
    	$this->db->where('hms_operation_summary_to_medicine.branch_id',$branch_id);
    	$this->db->where('hms_operation_summary_to_medicine.summary_id',$summary_data_id);
    	$res=$this->db->get();
    	if($res->num_rows() > 0)
    		return $res->result();
    	else
    		return "empty";
    }

    public function get_procedure_summary_medicine_data($ot_booking_id,$patient_id,$branch_id,$summary_data_id)
    {
    	$this->db->select('hms_operation_procedure_note_summary_to_medicine.*');
    	$this->db->from('hms_operation_procedure_note_summary_to_medicine');
    	$this->db->where('hms_operation_procedure_note_summary_to_medicine.branch_id',$branch_id);
    	$this->db->where('hms_operation_procedure_note_summary_to_medicine.summary_id',$summary_data_id);
    	$res=$this->db->get();
    	if($res->num_rows() > 0)
    		return $res->result();
    	else
    		return "empty";
    }
    // Function to get medicien data of summary


    // Function to update data by id only

    // Function to update data by id only
    public function common_update($table_name,$data_array,$rec_id,$branch_id="")
    {
    	$this->db->where('id',$rec_id);	
    	if($branch_id!="")
    		$this->db->where('branch_id',$branch_id);
    	$this->db->update($table_name,$data_array);
    	//echo $this->db->last_query();die;
    	return "200";
    }
    // Function for operation summary

    // funtion to delete medicined of operation summasry
    public function delete_operation_summary_medicines($ot_summary_id,$branch_id)
    {
    	$this->db->where('summary_id',$ot_summary_id);
    	$this->db->where('branch_id',$branch_id);
    	$this->db->delete('hms_operation_summary_to_medicine');
    }
    // Function to delete medicines of operation summary

    // Function to get ot summary print template
    public function get_ot_summary_template($branch_id)
    {
    	$this->db->select('hms_ot_summary_print_setting.*');
    	$this->db->from('hms_ot_summary_print_setting');
    	$this->db->where('branch_id',$branch_id);
    	$res=$this->db->get();
    	return $res->result();	
    }

    public function get_procedure_note_summary_template($branch_id)
    {
    	$this->db->select('hms_procedure_note_summary_print_setting.*');
    	$this->db->from('hms_procedure_note_summary_print_setting');
    	$this->db->where('branch_id',$branch_id);
    	$res=$this->db->get();
    	return $res->result();	
    }
    // Function to get ot summary print template

  public function get_template($template_id="")
   {
        // $users_data = $this->session->userdata('auth_users'); 
        // $this->db->select('hms_operation_summary.*');  
        // $this->db->where('hms_operation_summary.id',$template_id);  
        // // $this->db->where('hms_operation_summary.is_deleted',0); 
        // $this->db->where('hms_operation_summary.branch_id',$users_data['parent_id']); 
        // $query = $this->db->get('hms_operation_summary');
        // dd($this->db->last_query());
        // $result = $query->row();  
        // return json_encode($result);
        
        $this->db->select('hms_operation_summery.*');
		$this->db->from('hms_operation_summery'); 
		$this->db->where('hms_operation_summery.id',$template_id);
		$this->db->where('hms_operation_summery.is_deleted','0');
		$query = $this->db->get(); 
		$result = $query->row_array();
		return json_encode($result);
    }

    public function get_procedure_template($template_id="")
   {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_procedure_note_summery.*');  
        $this->db->where('hms_procedure_note_summery.id',$template_id);  
        $this->db->where('hms_procedure_note_summery.is_deleted',0); 
        $this->db->where('hms_procedure_note_summery.branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_procedure_note_summery');
        // dd($this->db->last_query());
        $result = $query->row();  
        return json_encode($result);
    }
    
     function doctor_list_by_otids($id){
        $this->db->select('hms_operation_summary_to_doctors.*');
        $this->db->from('hms_operation_summary_to_doctors'); 
        $this->db->where('hms_operation_summary_to_doctors.summary_id',$id);
        
        $query = $this->db->get()->result();
        $data=array(); 
        foreach($query as $res){
            $data[$res->doctor_id][]=$res->doctor_name;
        }
        return $data;
    
    }

    function procedure_doctor_list_by_otids($id){
        $this->db->select('hms_operation_summary_procedure_note_to_doctors.*');
        $this->db->from('hms_operation_summary_procedure_note_to_doctors'); 
        $this->db->where('hms_operation_summary_procedure_note_to_doctors.summary_id',$id);
        
        $query = $this->db->get()->result();
        $data=array(); 
        foreach($query as $res){
            $data[$res->doctor_id][]=$res->doctor_name;
        }
        return $data;
    
    }
    
   public function get_template_medicine($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_operation_summery_to_medicine.*');  
		$this->db->where('hms_operation_summery_to_medicine.summary_id',$template_id);  
		$query = $this->db->get('hms_operation_summery_to_medicine');
		$result = $query->result(); 
		//echo $this->db->last_query();die;
		return json_encode($result);
		 
    }
    
   public function get_doctor_digital_sign($doctor_name="")
   {
        $users_data = $this->session->userdata('auth_users');
        
        $this->db->select('signature,doctor_name,doc_reg_no,qualification'); 
        $this->db->where('doctor_name',$doctor_name);  
        $this->db->where('is_deleted',0);  
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->group_by('id');  
        $query = $this->db->get('hms_doctors');
        $result = $query->row(); 
        return $result; 
    }

// Please write code above this
}
?>