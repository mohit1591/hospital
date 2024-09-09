<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referral_reports_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_appointment_data($get="")
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_ambulance_booking.*,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.gender,hms_patient.patient_code");
        $this->db->join('hms_patient','hms_patient.id=hms_ambulance_booking.patient_id','inner');
        $this->db->where('hms_ambulance_booking.is_deleted','0'); 
        


        $search = $this->session->userdata('appointment_search');
        if(isset($search) && !empty($search))
        {
            $this->db->where('hms_ambulance_booking.branch_id = "'.$search['branch_id'].'"');
        }
        else
        {
            $this->db->where('hms_ambulance_booking.branch_id = "'.$user_data['parent_id'].'"');  
        }
        $this->db->where('hms_ambulance_booking.type=1');
        $this->db->from('hms_ambulance_booking'); 
        
        if(!empty($get['start_date']))
        {
           $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

           $this->db->where('hms_ambulance_booking.booking_date >= "'.$start_date.'"');
        }

        if(!empty($get['end_date']))
        {
            $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
            $this->db->where('hms_ambulance_booking.booking_date <= "'.$end_date.'"');
        }
        /////// Search query end //////////////
        
        $query = $this->db->get('hms_ambulance_booking'); 

        $data= $query->result();
        
        return $data;
    }

    public function ambulance_report_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($get))
        {  
             
            $this->db->select("hms_ambulance_booking.*,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_cities.city, hms_state.state,hms_branch.branch_name,hms_ambulance_booking.created_date as createddate, (CASE WHEN hms_ambulance_booking.reffered_by=0 THEN hms_doctors.doctor_name WHEN hms_ambulance_booking.reffered_by=1 THEN hms_hospital.hospital_name ELSE '' 
          END) as doctor_name"); 
            $this->db->join('hms_patient','hms_patient.id=hms_ambulance_booking.patient_id','inner');
            $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
            $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
            $this->db->join('hms_branch','hms_branch.id=hms_ambulance_booking.branch_id','left');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered','left');
            $this->db->join('hms_hospital','hms_hospital.id=hms_ambulance_booking.referral_hospital','left');
            $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']));

               $this->db->where('hms_ambulance_booking.booking_date >= "'.$start_date.'"');
            }
            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']));
                $this->db->where('hms_ambulance_booking.booking_date <= "'.$end_date.'"');
            }

            if($get['ref_type']=='0' && !empty($get['reffer_by']))
            {
                 $this->db->where('hms_ambulance_booking.reffered',$get['reffer_by']);
            }
            elseif($get["ref_type"]=='1' && !empty($get['reffer_by']))
            {
                 $this->db->where('hms_ambulance_booking.referral_hospital',$get['reffer_by']);
            }

            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            elseif(!empty($get["employee"]))
            {
                $emp_ids=  $get["employee"];
            }
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_ambulance_booking.created_by IN ('.$emp_ids.')');
            }
            $this->db->where('hms_ambulance_booking.reffered_by',$get['ref_type']);
            $this->db->from('hms_ambulance_booking');
            $query = $this->db->get(); 
          //  echo $this->db->last_query(); exit;
            return $query->result_array();
        } 
    }

    public function branch_referral_list($get="",$ids=[])
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            $this->db->select("hms_ambulance_booking.*,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_cities.city, hms_state.state,hms_branch.branch_name,hms_doctors.doctor_name");
            $this->db->join('hms_patient','hms_patient.id=hms_ambulance_booking.patient_id','inner');
            $this->db->join('hms_branch','hms_branch.id=hms_ambulance_booking.branch_id','left');
            $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
            $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered','left');
            $this->db->where('hms_ambulance_booking.branch_id IN ('.$branch_id.')'); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']));

               $this->db->where('hms_ambulance_booking.booking_date >= "'.$start_date.'"');
            }
            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']));
                $this->db->where('hms_ambulance_booking.booking_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_ambulance_booking');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();            
        } 
    }

}
?>