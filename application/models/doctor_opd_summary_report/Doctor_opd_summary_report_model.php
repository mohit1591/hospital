<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor_opd_summary_report_model extends CI_Model {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    
    public function get_details($get=array())
    { 
        if(!empty($get))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select("hms_doctors.doctor_name,(select count(hms_opd_booking.id) from hms_opd_booking where hms_opd_booking.attended_doctor=hms_doctors.id AND hms_opd_booking.branch_id='".$get['branch_id']."' AND hms_opd_booking.type=3 AND hms_opd_booking.is_deleted=0 AND hms_opd_booking.booking_date>='".date('Y-m-d',strtotime($get['start_date']))."' AND hms_opd_booking.booking_date<='".date('Y-m-d',strtotime($get['end_date']))."') as total_count");
            $this->db->from('hms_doctors'); 
            $this->db->where('hms_doctors.is_deleted=0');
            /*if(!empty($get['start_date']))
            {
              $this->db->where('hms_doctors.created_date >= "'.date('Y-m-d',strtotime($get['start_date'])).'"');
            }

            if(!empty($get['end_date']))
            {
              $this->db->where('hms_doctors.created_date<= "'.date('Y-m-d',strtotime($get['end_date'])).'"');   
            }*/

            $this->db->where('hms_doctors.branch_id',$get['branch_id']);  
            $this->db->order_by('hms_doctors.doctor_name','DESC');
            $query = $this->db->get();
            //echo $this->db->last_query(); exit;
            //$result = $query->result();
            $result_expense = $query->result(); 

            return $result_expense;  
          
        } 
    }
    
    
    function search_report_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $opd_summary_data = $this->session->userdata('opd_summary_search'); 
        
         $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,hms_opd_booking.booking_date,hms_opd_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode,hms_opd_booking.token_no");

            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');

             //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (1,8)','left');
            //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_ipd_booking.id AND hms_branch_hospital_no.section_id=3','left');   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   

            if(!empty($opd_summary_data['attended_doctor']))
            {
                $this->db->where('hms_opd_booking.attended_doctor',$opd_summary_data['attended_doctor']);
            }

            if(!empty($opd_summary_data['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($opd_summary_data['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($opd_summary_data['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($opd_summary_data['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
         
            $this->db->where('hms_opd_booking.type',2); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id',2);  //billing section id 4
            $this->db->where('hms_payment.debit>0');
            //$this->db->order_by('hms_payment.id','DESC');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            //$result = $this->db->get()->result();  
            //echo $this->db->last_query();die;
            
            $new_self_opd_array['self_opd_coll'] = $this->db->get()->result(); 
           // echo $this->db->last_query(); exit; 

            /* self opd collection payment */

            $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
            //(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
            //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (1,8)','left');

            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($opd_summary_data['attended_doctor']))
            {
                $this->db->where('hms_opd_booking.attended_doctor',$opd_summary_data['attended_doctor']);
            }
            if(!empty($opd_summary_data['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($opd_summary_data['start_date']))." 00:00:00";

               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($opd_summary_data['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($opd_summary_data['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            //$this->db->where('hms_opd_booking.type',3);
            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id',2); 
            $this->db->where('hms_payment.debit>=0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_self_opd_array['self_opd_coll_payment_mode'] = $this->db->get()->result(); 

             /* self opd collection payment */

            return $new_self_opd_array;

        //echo $this->db->last_query();die;
        return $result;
        
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
    
    public function religion_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('religion','ASC'); 
        $query = $this->db->get('path_religion');
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('path_religion.*');
        $this->db->from('path_religion'); 
        $this->db->where('path_religion.id',$id);
        $this->db->where('path_religion.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }
    
    public function save()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();  
        $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'religion'=>$post['religion'],
                    'status'=>$post['status'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
        if(!empty($post['data_id']) && $post['data_id']>0)
        {    
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('path_religion',$data);  
        }
        else{    
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('path_religion',$data);               
        }   
    }

    public function delete($id="")
    {
        if(!empty($id) && $id>0)
        {

            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',1);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$id);
            $this->db->update('path_religion');
            //echo $this->db->last_query();die;
        } 
    }

    public function deleteall($ids=array())
    {
        if(!empty($ids))
        { 

            $id_list = [];
            foreach($ids as $id)
            {
                if(!empty($id) && $id>0)
                {
                  $id_list[]  = $id;
                } 
            }
            $branch_ids = implode(',', $id_list);
            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',1);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id IN ('.$branch_ids.')');
            $this->db->update('path_religion');
            //echo $this->db->last_query();die;
        } 
    }

}
?>