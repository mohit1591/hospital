<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_doctor_wise_model extends CI_Model {
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }
 
   
  public function doctor_commission_details($get=array())
  {
      // OPD BOoking
        $datas=array();
       $users_data = $this->session->userdata('auth_users');
       $this->db->empty_table('hms_temp_doctor_wise_tbl');
      
        if(!empty($get))
        {  
            $this->db->select("hms_patient.patient_name,hms_payment.section_id, hms_patient.patient_code,'OPD' as module, hms_opd_booking.attended_doctor as doctor_id, hms_doctors.doctor_name, hms_opd_booking.booking_code,hms_payment.debit,hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);  
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date'])).' 00:00:00';
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            if(!empty($get['doc_id']))
            {
              $this->db->where('hms_opd_booking.attended_doctor',$get['doc_id']);  
            }

            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (2)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $self_opd_coll= $this->db->get()->result_array(); 
           if(!empty($self_opd_coll))
           {
                foreach ($self_opd_coll as $key => $value) {
                    $datas[] = $value;
                }
           }
    // day care

     $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'DAYCARE' as module, hms_day_care_booking.attended_doctor as doctor_id, hms_doctors.doctor_name,hms_day_care_booking.booking_code,hms_payment.debit,hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_day_care_booking.attended_doctor');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);  
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date'])).' 00:00:00';

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            if(!empty($get['doc_id']))
            {
              $this->db->where('hms_day_care_booking.attended_doctor',$get['doc_id']);  
            }
           
            $this->db->where('hms_day_care_booking.is_deleted',0); 
            $this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (14)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $self_day_care_coll = $this->db->get()->result_array();

          if(!empty($self_day_care_coll))
           {
             foreach ($self_day_care_coll as $key => $value) {
                    $datas[] = $value;
                }
           }
            // ambulance  
            
             $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'AMB' as module, hms_ambulance_booking.reffered as doctor_id, hms_doctors.doctor_name,hms_ambulance_booking.booking_no as booking_code,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_ambulance_booking.is_deleted',0); 
            $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);  
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
             
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:00";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            if(!empty($get['doc_id']))
            {
              $this->db->where('hms_ambulance_booking.reffered',$get['doc_id']);  
            }
            $this->db->where('hms_payment.section_id IN (13)');
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $self_ambulance_coll= $this->db->get()->result_array();
            if(!empty($self_ambulance_coll))
           {
                 foreach ($self_ambulance_coll as $key => $value) {
                    $datas[] = $value;
                }
           }

            // opd billing 


             $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'OPDBILL' as module,hms_opd_booking.attended_doctor as doctor_id, hms_doctors.doctor_name,hms_opd_booking.reciept_code as booking_code, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");

            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);

            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
             if(!empty($get['doc_id']))
            {
              $this->db->where('hms_opd_booking.attended_doctor',$get['doc_id']);  
            }
    
            $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (4)');  //billing section id 4
            $this->db->where('hms_payment.debit>0');
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $self_bill_coll = $this->db->get()->result_array(); 
          if(!empty($self_bill_coll))
           {
            foreach ($self_bill_coll as $key => $value) {
                    $datas[] = $value;
                }
           }
                    // medicine sale

             $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'MEDSALE' as module,hms_medicine_sale.refered_id as doctor_id, hms_doctors.doctor_name,hms_medicine_sale.sale_no as booking_code,hms_payment.debit, hms_payment.created_date,hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id AND hms_medicine_sale.is_deleted=0');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id');
           
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
             if(!empty($get['doc_id']))
            {
              $this->db->where('hms_medicine_sale.refered_id',$get['doc_id']);  
            }
    
            $this->db->where('hms_payment.section_id IN (3)'); 
            $this->db->where('hms_medicine_sale.is_deleted','0');
            $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
     
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $med_coll = $this->db->get()->result_array();   
          if(!empty($med_coll))
           {
            foreach ($med_coll as $key => $value) {
                    $datas[] = $value;
                }
           }

            // ipd booking

            $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'IPD' as module, hms_ipd_booking.referral_doctor as doctor_id, hms_doctors.doctor_name,hms_ipd_booking.ipd_no as booking_code, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");

            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id'); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
                $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:00";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
              if(!empty($get['doc_id']))
            {
              $this->db->where('hms_ipd_booking.attend_doctor_id',$get['doc_id']);  
            }
            $this->db->where('hms_payment.section_id IN (5)'); 
            $this->db->where('hms_ipd_booking.is_deleted','0');
            $this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit>0');
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $ipd_coll = $this->db->get()->result_array();
          if(!empty($ipd_coll))
           {
             foreach ($ipd_coll as $key => $value) {
                    $datas[] = $value;
                }
           }
            // pathology

             $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'PATH' as module, path_test_booking.referral_doctor as doctor_id,hms_doctors.doctor_name,path_test_booking.lab_reg_no as booking_code, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id AND hms_patient.is_deleted=0','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id AND path_test_booking.is_deleted=0');
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);  

            if(!empty($get['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($get['start_date']))." 00:00:00";
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
             if(!empty($get['doc_id']))
            {
              $this->db->where('path_test_booking.referral_doctor',$get['doc_id']);  
            }
            $this->db->where('hms_payment.section_id',1); 
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit > 0');
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $path_coll = $this->db->get()->result_array();  

           if(!empty($path_coll))
           {
            foreach ($path_coll as $key => $value) {
                    $datas[] = $value;
                }
           }

            // vaccine sale

              $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'VACCSALE' as module, hms_vaccination_sale.refered_id as doctor_id, hms_doctors.doctor_name,hms_vaccination_sale.sale_no as booking_code,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_vaccination_sale.refered_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   

            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
             if(!empty($get['doc_id']))
            {
              $this->db->where('hms_vaccination_sale.refered_id',$get['doc_id']);  
            }

            $this->db->where('hms_payment.section_id IN (7)'); 
            $this->db->where('hms_vaccination_sale.is_deleted','0');
            $this->db->where('hms_vaccination_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
           $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
           $vaccine_coll= $this->db->get()->result_array(); 
           if(!empty($vaccine_coll))
           {
             foreach ($vaccine_coll as $key => $value) {
                    $datas[] = $value;
                }
           }
           // ot collection

            $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'OT' as module, hms_operation_booking.referral_doctor as doctor_id, hms_doctors.doctor_name,hms_operation_booking.booking_code, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.referral_doctor');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   

            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            if(!empty($get['doc_id']))
            {
              $this->db->where('hms_operation_booking.referral_doctor',$get['doc_id']);  
            }
            $this->db->where('hms_payment.section_id IN (8)'); 
            $this->db->where('hms_operation_booking.is_deleted','0');
            $this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $self_ot_coll = $this->db->get()->result_array(); 

           if(!empty($self_ot_coll))
           {
            foreach ($self_ot_coll as $key => $value) {
                    $datas[] = $value;
                }
           }
            // bloodbank 

             $this->db->select("hms_patient.patient_name,hms_payment.section_id,hms_patient.patient_code,'BLOODBANK' as module, hms_blood_patient_to_recipient.doctor_id as doctor_id, hms_doctors.doctor_name,hms_blood_patient_to_recipient.issue_code as booking_code, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_blood_patient_to_recipient.doctor_id');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);  
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            if(!empty($get['doc_id']))
            {
              $this->db->where('hms_blood_patient_to_recipient.doctor_id',$get['doc_id']);  
            }
            $this->db->where('hms_payment.section_id IN (10)'); 
            $this->db->where('hms_blood_patient_to_recipient.is_deleted','0');
            $this->db->where('hms_blood_patient_to_recipient.branch_id',$users_data['parent_id']);   
            $this->db->where('hms_payment.debit>0');
            $this->db->order_by('hms_payment.id','ASC');
            $this->db->from('hms_payment');
            $self_blood_bank_collection = $this->db->get()->result_array(); 

           if(!empty($self_blood_bank_collection))
           {
                foreach ($self_blood_bank_collection as $key => $value) {
                    $datas[] = $value;
                }
           }
          $this->db->insert_batch('hms_temp_doctor_wise_tbl',$datas);
           $this->db->select('*');
            if(!empty($get['dept']) && isset($get['dept']))	
           {            	
           $this->db->where('section_id IN ('.$get['dept'].')');	
           }
           $this->db->order_by('created_date','DESC');
          $result['record']= $this->db->get('hms_temp_doctor_wise_tbl')->result();
          $this->db->select('mode,SUM(debit) as paid_total_amount');
           if(!empty($get['dept']) && isset($get['dept']))	
           {            	
           $this->db->where('section_id IN ('.$get['dept'].')');	
           }
           $this->db->group_by('mode');
          $result['pay_mode']= $this->db->get('hms_temp_doctor_wise_tbl')->result();
          return $result;
     }
  }

  public function doctor_list_details($doc_id='',$dept='')
  {
        $this->db->select('doctor_id,doctor_name');
        if(!empty($doc_id))
        {          
        $this->db->where('doctor_id',$doc_id);
        }
        if(!empty($dept) && isset($dept))	
       {            	
       $this->db->where('section_id IN ('.$dept.')');	
       }
        $this->db->order_by('created_date','DESC');
        $this->db->group_by('doctor_id');
        $result = $this->db->get('hms_temp_doctor_wise_tbl')->result();
          return $result;
  }


}
?>