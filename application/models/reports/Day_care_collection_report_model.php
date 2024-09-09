<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Day_care_collection_report_model extends CI_Model {

    var $table = 'hms_payment';
    var $column = array('hms_day_care_booking.booking_code','hms_day_care_booking.booking_date', 'hms_patient.patient_name','docs.doctor_name','hms_doctors.doctor_name','hms_day_care_booking.total_amount','hms_day_care_booking.discount','hms_day_care_booking.net_amount','hms_day_care_booking.paid_amount','hms_day_care_booking.balance','hms_day_care_booking.type');  
    var $order = array('hms_payment.id' => 'desc');
    //,'hms_department.department'
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('day_care_collection_resport_search_data');
        
        $this->db->select("hms_day_care_booking.id, hms_day_care_booking.status, hms_day_care_booking.booking_status, hms_payment.debit as paid_amount, hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.balance as blnce,hms_payment.section_id,hms_payment.id as pay_id,hms_day_care_booking.booking_code, hms_day_care_booking.booking_date,hms_day_care_booking.booking_time, (CASE WHEN hms_payment.balance>0 THEN hms_day_care_booking.total_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_day_care_booking.discount) ELSE '0.00' END) as discount, hms_day_care_booking.type, hms_doctors.doctor_name,(CASE WHEN hms_day_care_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name, hms_patient.patient_name, hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 14 AND payment.parent_id = hms_day_care_booking.id) ELSE '0.00' END) as balance, docs.doctor_name as consultant,(CASE WHEN hms_payment.balance > 0 THEN '1' ELSE '0' END) as print_type,(CASE WHEN hms_payment.balance > 0 THEN '1' ELSE '0' END) as print_type"); 

        $this->db->join('hms_day_care_booking','hms_day_care_booking.id = hms_payment.parent_id AND hms_day_care_booking.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_day_care_booking.patient_id');
        $this->db->join('hms_doctors as docs','docs.id = hms_day_care_booking.attended_doctor','left');
       
        $this->db->join('hms_doctors','hms_doctors.id = hms_day_care_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_day_care_booking.referral_hospital','left');

        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',14);
        $this->db->where('hms_day_care_booking.type',5); 
        $this->db->where('hms_day_care_booking.is_deleted',0);  
            
       
        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_day_care_booking.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
        }
      

        if(isset($search_data) && !empty($search_data))
        {
            
            
            if(isset($search_data['start_date']) && !empty($search_data['start_date'])
                )
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            if(isset($search_data['referred_by']) && $search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_day_care_booking.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif($search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_day_care_booking.referral_doctor' ,$search_data['refered_id']);
            }

            
            if(isset($search_data['attended_doctor']) && !empty($search_data['attended_doctor'])
                )
            { 
                $this->db->where('hms_day_care_booking.attended_doctor = "'.$search_data["attended_doctor"].'"');
            }

            if(!empty($search_data['insurance_type']))
            {
                $this->db->where('hms_day_care_booking.pannel_type',$search_data['insurance_type']);
            }

            if(!empty($search_data['insurance_type_id']))
            {
                $this->db->where('hms_day_care_booking.insurance_type_id',$search_data['insurance_type_id']);
            }

            if(!empty($search_data['ins_company_id']))
            {
                $this->db->where('hms_day_care_booking.ins_company_id',$search_data['ins_company_id']);
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
            
            //search by user
            
            
        }
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
        }
        $this->db->where('hms_day_care_booking.paid_amount>0');
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
            $column[$i] = $item; // set column array variable to order processing
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
        $search_data = $this->session->userdata('day_care_collection_resport_search_data'); 

         $this->db->select("hms_day_care_booking.id, hms_day_care_booking.status, hms_day_care_booking.booking_status, hms_payment.debit as paid_amount, hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.balance as blnce,hms_payment.section_id,hms_payment.id as pay_id,hms_day_care_booking.booking_code, hms_day_care_booking.booking_date,hms_day_care_booking.booking_time, (CASE WHEN hms_payment.balance>0 THEN hms_day_care_booking.total_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_day_care_booking.discount) ELSE '0.00' END) as discount, hms_day_care_booking.type, hms_doctors.doctor_name,(CASE WHEN hms_day_care_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name, hms_patient.patient_name, hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 14 AND payment.parent_id = hms_day_care_booking.id) ELSE '0.00' END) as balance, docs.doctor_name as consultant,(CASE WHEN hms_payment.balance > 0 THEN '1' ELSE '0' END) as print_type,(CASE WHEN hms_payment.balance > 0 THEN '1' ELSE '0' END) as print_type"); 

        $this->db->join('hms_day_care_booking','hms_day_care_booking.id = hms_payment.parent_id AND hms_day_care_booking.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_day_care_booking.patient_id');
        $this->db->join('hms_doctors as docs','docs.id = hms_day_care_booking.attended_doctor','left');
       
        $this->db->join('hms_doctors','hms_doctors.id = hms_day_care_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_day_care_booking.referral_hospital','left');

        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',14);
        $this->db->where('hms_day_care_booking.type',5); 
        $this->db->where('hms_day_care_booking.is_deleted',0);   

        

        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_day_care_booking.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
        }

        if(isset($search_data) && !empty($search_data))
        {
            
            
            if(isset($search_data['start_date']) && !empty($search_data['start_date'])
                )
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            if(isset($search_data['referred_by']) && $search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_day_care_booking.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif($search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_day_care_booking.referral_doctor' ,$search_data['refered_id']);
            }

            
            if(isset($search_data['attended_doctor']) && !empty($search_data['attended_doctor'])
                )
            { 
                $this->db->where('hms_day_care_booking.attended_doctor = "'.$search_data["attended_doctor"].'"');
            }

            if(!empty($search_data['insurance_type']))
            {
                $this->db->where('hms_day_care_booking.pannel_type',$search_data['insurance_type']);
            }

            if(!empty($search_data['insurance_type_id']))
            {
                $this->db->where('hms_day_care_booking.insurance_type_id',$search_data['insurance_type_id']);
            }

            if(!empty($search_data['ins_company_id']))
            {
                $this->db->where('hms_day_care_booking.ins_company_id',$search_data['ins_company_id']);
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
            
            //search by user
            
            
        }
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
        }
        $this->db->where('hms_day_care_booking.paid_amount>0');
        $this->db->order_by('hms_payment.id','desc');
        //$this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
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
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('day_care_collection_resport_search_data');

        $this->db->join('hms_day_care_booking','hms_day_care_booking.id = hms_payment.parent_id AND hms_day_care_booking.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_day_care_booking.patient_id');
        $this->db->join('hms_doctors as docs','docs.id = hms_day_care_booking.attended_doctor','left');
       
        $this->db->join('hms_doctors','hms_doctors.id = hms_day_care_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_day_care_booking.referral_hospital','left');
        $this->db->where('hms_payment.section_id',14);
        $this->db->where('hms_day_care_booking.type',5); 
        $this->db->where('hms_day_care_booking.is_deleted',0);  
            
       
        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_day_care_booking.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
        }
      

        if(isset($search_data) && !empty($search_data))
        {
            
            if(isset($search_data['start_date']) && !empty($search_data['start_date'])
                )
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            if(isset($search_data['referred_by']) && $search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_day_care_booking.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif($search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_day_care_booking.referral_doctor' ,$search_data['refered_id']);
            }

            
            if(isset($search_data['attended_doctor']) && !empty($search_data['attended_doctor'])
                )
            { 
                $this->db->where('hms_day_care_booking.attended_doctor = "'.$search_data["attended_doctor"].'"');
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
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
        }
        $this->db->where('hms_day_care_booking.paid_amount>0');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    

}
?>