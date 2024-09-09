<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_collection_report_model extends CI_Model {

    //var $table = 'hms_operation_booking';
    var $table = 'hms_payment';
    var $column = array('hms_operation_booking.booking_code','hms_operation_booking.operation_date', 'hms_patient.patient_name','hms_operation_booking.referred_by','hms_operation_booking.net_amount','hms_operation_booking.discount_amount','hms_operation_booking.paid_amount','hms_operation_booking.balance_amount','hms_operation_booking.created_by');  
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
        $search_data = $this->session->userdata('ot_collection_resport_search_data');
               
        $this->db->select("hms_operation_booking.id, hms_operation_booking.booking_code,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_operation_booking.operation_date,  (CASE WHEN hms_payment.balance>0 THEN hms_operation_booking.net_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_operation_booking.discount_amount) ELSE '0.00' END) as discount_amount,(CASE WHEN hms_payment.balance > 0 THEN (hms_operation_booking.discount_amount) ELSE '0.00' END) as discount_amount,  
            (CASE WHEN hms_operation_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_operation_booking.referral_doctor=0 THEN concat('Other ',hms_operation_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name
            , hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 8 AND payment.parent_id = hms_operation_booking.id) ELSE '0.00' END) as balance");  
       
        $this->db->join('hms_operation_booking','hms_operation_booking.id = hms_payment.parent_id AND hms_operation_booking.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_operation_booking.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_operation_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_operation_booking.referral_hospital','left');
        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',8);
        $this->db->where('hms_operation_booking.is_deleted',0);


        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_operation_booking.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_operation_booking.branch_id = "'.$users_data['parent_id'].'"');
        }

        if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                //$this->db->where('hms_operation_booking.sale_date >= "'.$start_date.'"');
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                //$this->db->where('hms_operation_booking.sale_date <= "'.$end_date.'"');
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }


            if(!empty($search_data['insurance_type']))
              {
                $this->db->where('hms_patient.insurance_type',$search_data['insurance_type']);
              }

              if(!empty($search_data['insurance_type_id']))
              {
                $this->db->where('hms_patient.insurance_type_id',$search_data['insurance_type_id']);
              }

              if(!empty($search_data['ins_company_id']))
              {
                $this->db->where('hms_patient.ins_company_id',$search_data['ins_company_id']);
              }

            
            if($search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_operation_booking.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif($search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_operation_booking.referral_doctor' ,$search_data['refered_id']);
            }
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }
            
            if(isset($search_data['booking_code']) && !empty($search_data['booking_code'])
                )
            { 
                $this->db->where('hms_operation_booking.booking_code LIKE "'.$search_data["booking_code"].'%"');
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
        $search_data = $this->session->userdata('ot_collection_resport_search_data'); 
        $this->db->select("hms_operation_booking.id, hms_operation_booking.booking_code,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_operation_booking.operation_date,  (CASE WHEN hms_payment.balance>0 THEN hms_operation_booking.net_amount ELSE '0.00' END) total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_operation_booking.discount_amount) ELSE '0.00' END) as discount_amount,(CASE WHEN hms_operation_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_operation_booking.referral_doctor=0 THEN concat('Other ',hms_operation_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name, hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 8 AND payment.parent_id = hms_operation_booking.id) ELSE '0.00' END) as balance"); 
        
        $this->db->join('hms_operation_booking','hms_operation_booking.id = hms_payment.parent_id'); 
        $this->db->join('hms_patient','hms_patient.id = hms_operation_booking.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_operation_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_operation_booking.referral_hospital','left');

        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',8);
        $this->db->where('hms_operation_booking.is_deleted',0);
          
        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_operation_booking.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_operation_booking.branch_id = "'.$users_data['parent_id'].'"');
        }    

        if(isset($search_data) && !empty($search_data))
        {
             if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            
           /* if(isset($search_data['refered_id']) && !empty($search_data['refered_id'])
                )
            { 
                $this->db->where('hms_operation_booking.refered_id = "'.$search_data["refered_id"].'"');
                //$this->db->where('hms_doctors.doctor_type IN (0,2)');
            }*/

            if($search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_operation_booking.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif($search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_operation_booking.referral_doctor' ,$search_data['refered_id']);
            }
            
            
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }


            if(!empty($search_data['insurance_type']))
              {
                $this->db->where('hms_patient.insurance_type',$search_data['insurance_type']);
              }

              if(!empty($search_data['insurance_type_id']))
              {
                $this->db->where('hms_patient.insurance_type_id',$search_data['insurance_type_id']);
              }

              if(!empty($search_data['ins_company_id']))
              {
                $this->db->where('hms_patient.ins_company_id',$search_data['ins_company_id']);
              }
            
            if(isset($search_data['booking_code']) && !empty($search_data['booking_code'])
                )
            { 
                $this->db->where('hms_operation_booking.booking_code LIKE "'.$search_data["booking_code"].'%"');
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
  

    public function get_medicine_collection_list_details($get=array())
    {
        

        if(!empty($get))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            if(!empty($sub_branch_details))
            {
                $child_ids_arr = array_column($sub_branch_details,'id');
                $child_ids = implode(',',$child_ids_arr);
            } 
            $this->db->select("path_expenses.*,path_expenses_category.exp_category"); 
            $this->db->from('path_expenses'); 
            $this->db->join("path_expenses_category","path_expenses.paid_to_id=path_expenses_category.id",'left');
            if(!empty($get['from_date']))
            {
              $this->db->where('path_expenses.expenses_date >= "'.$get['from_date'].'"');
            }

            if(!empty($get['end_date']))
            {
              $this->db->where('path_expenses.expenses_date<= "'.$get['end_date'].'"');   
            }

            if(!empty($get['branch_id']))
            {
              if(is_numeric($get['branch_id']) && $get['branch_id']>0)
              {
                 $this->db->where('path_expenses.branch_id',$get['branch_id']);  
              } 
              else if($get['branch_id']=='all') 
              {
                 $this->db->where('path_expenses.branch_id IN ('.$child_ids.')');  
              }  
            } 
            $query = $this->db->get();
            $result = $query->result();  
            return $result;
        } 
    }

}
?>