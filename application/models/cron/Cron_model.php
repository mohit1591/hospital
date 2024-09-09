<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_model extends CI_Model {
  
	public function __construct()
	{
		parent::__construct();  
	}

    public function birthday_list()
    {
        $this->db->select("`hms_doctors`.`branch_id`,`hms_doctors`.`doctor_name` as name,`hms_doctors`.`email` as email,`hms_doctors`.`mobile_no` as mobile"); 
        $this->db->from('hms_doctors');
        $this->db->where('month(dob) = month(curdate()) and day(dob) = day(curdate())');
        $this->db->group_by('hms_doctors.id');  
        $query = $this->db->get();
        //echo $this->db->last_query();die; 
        $sql1 = $this->db->last_query();

        $this->db->select("`hms_patient`.`branch_id`,`hms_patient`.`patient_name` as name,`hms_patient`.`patient_email` as email,`hms_patient`.`mobile_no` as mobile"); 
        $this->db->from('hms_patient');
        $this->db->where('month(hms_patient.dob) = month(curdate()) and day(hms_patient.dob) = day(curdate())');
        $this->db->group_by('hms_patient.id');  
        $query = $this->db->get();
        //echo $this->db->last_query();die; 
        $sql2 = $this->db->last_query();

        $this->db->select("`hms_employees`.`branch_id`,`hms_employees`.`name` as name,`hms_employees`.`email` as email,`hms_employees`.`contact_no` as mobile");
        $this->db->from('hms_employees'); 
        $this->db->where('month(hms_employees.dob) = month(curdate()) and day(hms_employees.dob) = day(curdate())');
        $this->db->group_by('hms_employees.id');  
        $query = $this->db->get();
        //echo $this->db->last_query();die; 
        $sql3 = $this->db->last_query();
        $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.") UNION ALL (".$sql3.")"); 
        //echo $this->db->last_query();die; 
        return $sql->result_array();
        }

    public function anniversary_list()
    {
        $this->db->select("`hms_doctors`.`branch_id`,`hms_doctors`.`doctor_name` as name,`hms_doctors`.`email` as email,`hms_doctors`.`mobile_no` as mobile"); 
        $this->db->from('hms_doctors');
        $this->db->where('month(anniversary) = month(curdate()) and day(anniversary) = day(curdate())');
        $this->db->group_by('hms_doctors.id');  
        $query = $this->db->get();
        //echo $this->db->last_query();die; 
        $sql1 = $this->db->last_query();

        $this->db->select("`hms_patient`.`branch_id`,`hms_patient`.`patient_name` as name,`hms_patient`.`patient_email` as email,`hms_patient`.`mobile_no` as mobile"); 
        $this->db->from('hms_patient');
        $this->db->where('month(hms_patient.anniversary) = month(curdate()) and day(hms_patient.anniversary) = day(curdate())');
        $this->db->group_by('hms_patient.id');  
        $query = $this->db->get();
        //echo $this->db->last_query();die; 
        $sql2 = $this->db->last_query();

        $this->db->select("`hms_employees`.`branch_id`,`hms_employees`.`name` as name,`hms_employees`.`email` as email,`hms_employees`.`contact_no` as mobile");
        $this->db->from('hms_employees'); 
        $this->db->where('month(hms_employees.anniversary) = month(curdate()) and day(hms_employees.anniversary) = day(curdate())');
        $this->db->group_by('hms_employees.id');  
        $query = $this->db->get();
        //echo $this->db->last_query();die; 
        $sql3 = $this->db->last_query();
        $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.") UNION ALL (".$sql3.")"); 
        //echo $this->db->last_query();die; 
        return $sql->result_array();
     }  
     
     
    public function next_appointment_list()
    {
        $this->db->select("hms_next_appointment.*, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_branch_sms_setting.setting_name"); 
        $this->db->where('hms_next_appointment.next_appointment = "'.date('Y-m-d').'"');
        $this->db->from('hms_next_appointment');
        $this->db->join('hms_patient', 'hms_patient.id = hms_next_appointment.patient_id', 'left'); 
        $this->db->join('hms_branch_sms_setting', 'hms_branch_sms_setting.unique_id = hms_next_appointment.booking_type', 'left'); 
        $query = $this->db->get(); 
        //echo $this->db->last_query();die; 
        return $query->result_array();
     }  
     
    }
    ?>