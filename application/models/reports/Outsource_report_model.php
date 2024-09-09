<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outsource_report_model extends CI_Model {

    var $table = 'path_test_booking';
    var $column = array('path_test_booking.lab_reg_no','path_test_booking.booking_date', 'hms_patient.patient_name','hms_doctors.doctor_name','hms_department.department','path_test_booking.total_amount','path_test_booking.discount','path_test_booking.net_amount','path_test_booking.paid_amount','path_test_booking.balance');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('outsource_search_data'); 
        $this->db->select("path_test_booking.id, path_test_booking.lab_reg_no, path_test_booking.booking_date, hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.patient_code,
            (CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,path_test.test_name,path_test_booking_to_test.amount");
        $this->db->from('path_test_booking');
        
        $this->db->join('path_test_booking_to_profile','path_test_booking_to_profile.test_booking_id = path_test_booking.id','left');
        $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id'); 
        $this->db->join('hms_department','hms_department.id = path_test_booking.dept_id','left'); 
        $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');

        $this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id','left');
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
        $this->db->where('path_test.is_outsource','1');
        $this->db->group_by('path_test_booking.id');
        if(isset($search_data) && !empty($search_data))
        {
          if(isset($search_data['start_date']) && !empty($search_data['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('path_test_booking.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date']))." 23:59:59";
                $this->db->where('path_test_booking.created_date <= "'.$end_date.'"');
            }

           
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }
            
            if(isset($search_data['uhid_no']) && !empty($search_data['uhid_no'])
                )
            { 
                $this->db->where('hms_patient.patient_code LIKE "'.$search_data["uhid_no"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }
            if(isset($search_data['lab_ref_no']) && !empty($search_data['lab_ref_no'])
                )
            { 
                $this->db->where('path_test_booking.lab_reg_no LIKE "'.$search_data["lab_ref_no"].'%"');
            }
        }
      
        $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
        $i = 0;
    
        foreach ($this->column as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column) - 1 == $i) //last loop+
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item;  // set column array variable to order processing
            $i++;
        }
        
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($branch_id='')
    {
        $this->_get_datatables_query($branch_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }

    function search_report_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('outsource_search_data'); 
        $this->db->select("path_test_booking.id, path_test_booking.lab_reg_no, path_test_booking.booking_date, hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.patient_code,
            (CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,path_test.test_name,path_test_booking_to_test.amount");
        $this->db->from('path_test_booking');
        
        $this->db->join('path_test_booking_to_profile','path_test_booking_to_profile.test_booking_id = path_test_booking.id','left');
        $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id'); 
        $this->db->join('hms_department','hms_department.id = path_test_booking.dept_id','left'); 
        $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');

        $this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id','left');
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
        $this->db->where('path_test.is_outsource','1');

        if(isset($search_data) && !empty($search_data))
        {
          if(isset($search_data['start_date']) && !empty($search_data['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('path_test_booking.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date']))." 23:59:59";
                $this->db->where('path_test_booking.created_date <= "'.$end_date.'"');
            }

           
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }
            
            if(isset($search_data['patient_code']) && !empty($search_data['patient_code'])
                )
            { 
                $this->db->where('hms_patient.patient_code LIKE "'.$search_data["patient_code"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }
        }
        $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
        $query = $this->db->get(); 
       // echo $this->db->last_query();die;
        return $query->result();
    
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}
?>