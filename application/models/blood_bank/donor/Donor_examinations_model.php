<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donor_examinations_model extends CI_Model 
{
  	var $table = 'hms_blood_examination';
    var $column = array('hms_blood_donor.id','hms_blood_donor.donor_name','hms_blood_donor.donor_email','hms_blood_donor.status','hms_blood_donor.created_date','hms_blood_donor.modified_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function examination_data($id="",$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        //$this->db->select('ex.*, (CASE WHEN usr.emp_id > 0 THEN emp.name ELSE usr.username END) as examiner_name');
    	$this->db->select('ex.*, emp.name as examiner_name');
        //emp.name
    	$this->db->from('hms_blood_examination as ex');
        //$this->db->join('hms_users usr','usr.id=ex.examiner_id','left');
        $this->db->join('hms_employees emp','emp.id=ex.examiner_id','left');
        //$this->db->join('hms_employees emp','emp.id=usr.emp_id','left');

        //ex.examiner_id
    	if($donor_id!="")
    		$this->db->where('ex.donor_id',$donor_id);
    	if($id!="")
    		$this->db->where('ex.id',$id);

        $this->db->where('ex.branch_id',$branch_id);
    	$res=$this->db->get();
        if($res->num_rows() > 0)
    		return $res->row_array();
    	else
    		return "empty";
    }

    public function get_by_examiner_id($employee_type)
    {
          $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_employee_profile_type.*,hms_employees.name, hms_employees.id as emp_id');
        $this->db->join('hms_employees','hms_employees.id=hms_blood_employee_profile_type.profile_name','Left');
        $this->db->from('hms_blood_employee_profile_type'); 
        $this->db->where('hms_blood_employee_profile_type.employee_type',$employee_type);
        $this->db->where('hms_blood_employee_profile_type.is_deleted','0');
        $this->db->where('hms_blood_employee_profile_type.branch_id',$branch_id);
         $this->db->where('hms_blood_employee_profile_type.is_deleted','0');
         $this->db->where('hms_blood_employee_profile_type.status','1');
        $res=$this->db->get();
        //echo $this->db->last_query();die;
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }

    // Function to get blood details
    public function blood_details_data($id="",$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];    
        //$this->db->select('hms_blood_details.*, (CASE WHEN usr.emp_id > 0 THEN emp.name ELSE usr.username END) as phlebotomist_name, bbt.bag_type');
        $this->db->select('hms_blood_details.*, emp.name as phlebotomist_name,bbt.bag_type,hms_blood_post_complication.post_name');
        $this->db->from('hms_blood_details');
        $this->db->join('hms_blood_post_complication','hms_blood_post_complication.id=hms_blood_details.post_compilations','left');
        //$this->db->join('hms_users as usr','usr.id=hms_blood_details.phlebotomist','left');
       // $this->db->join('hms_employees as emp','emp.id=usr.emp_id','left');
        $this->db->join('hms_employees as emp','emp.id=hms_blood_details.phlebotomist','left');
        $this->db->join('hms_blood_bag_type bbt','bbt.id=hms_blood_details.blood_bag_type_id');
        if($donor_id!="")
            $this->db->where('hms_blood_details.donor_id',$donor_id);
        if($id!="")
            $this->db->where('hms_blood_details.id',$id);

        $this->db->where('hms_blood_details.branch_id',$branch_id);
        $res=$this->db->get();
        //echo $this->db->last_query();die;
        if($res->num_rows() > 0)
            return $res->row_array();
        else
            return "empty";
    }
    // Function to get blood details


    // Function to get Blood bag Qc Details
    public function blood_qc_data($id="",$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];        
       
         $this->db->select('hms_blood_qc_examination.*, emp.name as technician_name');
         $this->db->from('hms_blood_qc_examination');
         $this->db->join('hms_employees as emp','emp.id=hms_blood_qc_examination.technician_id','left');
       
        if($donor_id!="")
            $this->db->where('hms_blood_qc_examination.donor_id',$donor_id);
        if($id!="")
            $this->db->where('hms_blood_qc_examination.id',$id);

        $this->db->where('hms_blood_qc_examination.branch_id',$branch_id);
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->row_array();
        else
            return "empty";
    }
    // Function to get Blood bag QC details

    // Function to get blood data examination fields
    public function blood_qc_data_fields($qc_exm_id,$donor_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id']; 
        $this->db->select('hms_blood_qc_examination_to_fields.*, hms_blood_qc_fields.qc_field');
        $this->db->from('hms_blood_qc_examination_to_fields');
        $this->db->join('hms_blood_qc_fields','hms_blood_qc_fields.id=hms_blood_qc_examination_to_fields.qc_field_id','left');
         
        if($donor_id!="")
            $this->db->where('hms_blood_qc_examination_to_fields.donor_id',$donor_id);
        if($qc_exm_id!="")
            $this->db->where('hms_blood_qc_examination_to_fields.qc_exm_id',$qc_exm_id);
            //$this->db->where('hms_blood_qc_fields.qc_exm_id',$qc_exm_id);
        $this->db->where('hms_blood_qc_examination_to_fields.branch_id',$branch_id);

        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";   
    }
    
    // Function to get blood data examination fields
public function blood_get_components($id="",$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];        
         $this->db->select('hms_blood_extract_component.*');
         $this->db->from('hms_blood_extract_component');
        if($donor_id!="")
            $this->db->where('hms_blood_extract_component.donor_id',$donor_id);
        if($id!="")
            $this->db->where('hms_blood_extract_component.id',$id);

        $this->db->where('hms_blood_extract_component.branch_id',$branch_id);
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->row_array();
        else
            return "empty";
    }
    // Function to get components for Bag
    public function get_components_for_bag($bag_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('btc.*, cmp.component, cmp.unit_price');
        $this->db->from('hms_blood_bag_type_to_component btc');
        $this->db->join('hms_blood_component_master cmp','cmp.id=btc.component_id');    
        $this->db->where('btc.bag_type_id',$bag_id);
        $this->db->where('btc.is_deleted!=2');
        $this->db->where('btc.branch_id',$branch_id);
        $res=$this->db->get();
        //echo $this->db->last_query(); exit;
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }
    // Function to get Components for bag



    // function to get donor components extracted
    public function blood_components($rec_id="",$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('ec.*, cmp.component, cmp.unit_price,hms_blood_extract_component_bar_code.bar_code as b_code');
        $this->db->from('hms_blood_extract_component ec');
        $this->db->join('hms_blood_extract_component_bar_code','hms_blood_extract_component_bar_code.extraction_id=ec.id','left');
        $this->db->join('hms_blood_component_master cmp','cmp.id=ec.component_id');    
        $this->db->where('ec.donor_id',$donor_id);
        //$this->db->where('ec.is_deleted!=2');
        $this->db->where('ec.branch_id',$branch_id);
        $res=$this->db->get();
       // echo $this->db->last_query();die;
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }
    public function get_all_bar_code($component_id="",$extraction_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_extract_component_bar_code.*');
        $this->db->from('hms_blood_extract_component_bar_code');
        $this->db->where('hms_blood_extract_component_bar_code.extraction_id',$extraction_id);
        $this->db->where('hms_blood_extract_component_bar_code.component_id',$component_id);
        $this->db->where('hms_blood_extract_component_bar_code.branch_id',$branch_id);
        $res=$this->db->get();
        // echo $this->db->last_query();die;
        if($res->num_rows() > 0)
        return $res->result();
        else
        return "empty";
    }
   
    public function get_blood_grp_detail($donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_donor.*');
        $this->db->from('hms_blood_donor');
        $this->db->where('hms_blood_donor.id',$donor_id);
        $res=$this->db->get();
        // echo $this->db->last_query();die;
        if($res->num_rows() > 0)
        return $res->result();
        else
        return "empty";
    }

     public function get_doctor_id()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.doctor_type IN (1,2)'); 
        $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result; 
    } 
    // Function to get donor components extracted

// Please write code above    
}