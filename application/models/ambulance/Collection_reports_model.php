<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collection_reports_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function ambulance_collection_report($get="")
    {
        $users_data = $this->session->userdata('auth_users');
        $new_self_ambulance_array=array(); 
        if(!empty($get))
        {  
           $this->db->select("hms_ambulance_booking.booking_no as booking_code,hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_ambulance_booking.booking_date,hms_ambulance_booking.booking_no,hms_payment.discount_amount,
           hms_payment.net_amount as net_amount,hms_ambulance_booking.refund_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ambulance_booking.reffered_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ambulance_booking.reffered=0 THEN  concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,
           hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_payment_mode.payment_mode as mode");  

            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
             $this->db->join('hms_refund_payment','hms_refund_payment.parent_id=hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id','left');           
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
           
            $this->db->where('hms_ambulance_booking.is_deleted',0); 
            $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);  
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 

             if(!empty($get['start_time']))
            {
              $start_time=date('H:i:s',strtotime($get['start_time']));
            }
            else
            {
              $start_time=" 00:00:00";
            }
            if(!empty($get['end_time']))
            {
              $end_time=date('H:i:s',strtotime($get['end_time']));
            }
            else
            {
              $end_time=" 23:59:00";
            }
             
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d H:i:s',strtotime($get['start_date'].$start_time));
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d H:i:s',strtotime($get['end_date'].$end_time));
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
             if($get['ref_type']=='0' && !empty($get['reffer_by']))
            {
                 $this->db->where('hms_ambulance_booking.reffered',$get['reffer_by']);
            }
            elseif($get["ref_type"]=='1' && !empty($get['reffer_by']))
            {
                 $this->db->where('hms_ambulance_booking.referral_hospital',$get['reffer_by']);
            }

            if(!empty($get['staff_id']))
            {
                 $this->db->where('hms_ambulance_booking.staff_id',$get['staff_id']);
            }

             if(!empty($get['vehicle_id']))
            {
                 $this->db->where('hms_ambulance_booking.vehicle_no',$get['vehicle_id']);
            }

             if(!empty($get['ven_id']) && $get['ven_id']!='null')
            {
                 $this->db->where('hms_ambulance_booking.vendor_id',$get['ven_id']);
            }
            if(!empty($get['parti_id']))
            {
                 $this->db->join('hms_ambulance_booking_to_particulars','hms_ambulance_booking_to_particulars.booking_id=hms_payment.parent_id','left');
                 $this->db->where('hms_ambulance_booking_to_particulars.particular',$get['parti_id']);
            }
            
            if(!empty($get['driver_id']))
            {
                 $this->db->where('hms_ambulance_booking.driver_id',$get['driver_id']);
            }
            if(!empty($get['location']))
            {
                 $this->db->where('hms_ambulance_booking.location',$get['location']);
            }
            
             if(!empty($get['pickup']))
            {
                 $this->db->where('hms_ambulance_booking.source LIKE "%'.$get['pickup'].'%"');
            }
            if(!empty($get['drop']))
            {
                 $this->db->where('hms_ambulance_booking.destination LIKE "%'.$get['drop'].'%"');
            }
            if(!empty($get['remark']))
            {
                 $this->db->where('hms_ambulance_booking.remark LIKE "%'.$get['remark'].'%"');
            }
             if(!empty($get['payment_mode']))
            {
                 //$this->db->where('hms_ambulance_booking.payment_mode',$get['payment_mode']);
                 $this->db->where('hms_payment.pay_mode',$get['payment_mode']);
                 
            }
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            
            $this->db->where('hms_payment.section_id IN (13)');
             $this->db->where('hms_payment.debit>0'); 
             $this->db->where('hms_payment.type!=12'); 
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->group_by('hms_payment.id');
            $this->db->from('hms_payment');
            $new_self_ambulance_array['self_ambulance_coll']= $this->db->get()->result(); 
            //echo $this->db->last_query();die;
            /* code from payment_mode by */
             $this->db->select("SUM(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,
             hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
             $this->db->join('hms_refund_payment','hms_refund_payment.parent_id=hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id','left');           
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            /*$this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');*/
            
            $this->db->where('hms_ambulance_booking.is_deleted',0); 
           $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);  
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   

             
            if(!empty($get['start_time']))
            {
              $start_time=date('H:i:s',strtotime($get['start_time']));
            }
            else
            {
              $start_time=" 00:00:00";
            }
            if(!empty($get['end_time']))
            {
              $end_time=date('H:i:s',strtotime($get['end_time']));
            }
            else
            {
              $end_time=" 23:59:00";
            }
             
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d H:i:s',strtotime($get['start_date'].$start_time));
               $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d H:i:s',strtotime($get['end_date'].$end_time));
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
             if($get['ref_type']=='0' && !empty($get['reffer_by']))
            {
                 $this->db->where('hms_ambulance_booking.reffered',$get['reffer_by']);
            }
            elseif($get["ref_type"]=='1' && !empty($get['reffer_by']))
            {
                 $this->db->where('hms_ambulance_booking.referral_hospital',$get['reffer_by']);
            }

            if(!empty($get['staff_id']))
            {
                 $this->db->where('hms_ambulance_booking.staff_id',$get['staff_id']);
            }

             if(!empty($get['vehicle_id']))
            {
                 $this->db->where('hms_ambulance_booking.vehicle_no',$get['vehicle_id']);
            }

             if(!empty($get['ven_id']) && $get['ven_id']!='null')
            {
                 $this->db->where('hms_ambulance_booking.vendor_id',$get['ven_id']);
            }
            if(!empty($get['parti_id']))
            {
                 $this->db->join('hms_ambulance_booking_to_particulars','hms_ambulance_booking_to_particulars.booking_id=hms_payment.parent_id','left');
                 $this->db->where('hms_ambulance_booking_to_particulars.particular',$get['parti_id']);
            }
            
            if(!empty($get['driver_id']))
            {
                 $this->db->where('hms_ambulance_booking.driver_id',$get['driver_id']);
            }
            if(!empty($get['location']))
            {
                 $this->db->where('hms_ambulance_booking.location',$get['location']);
            }
            
             if(!empty($get['pickup']))
            {
                 $this->db->where('hms_ambulance_booking.source LIKE "%'.$get['pickup'].'%"');
            }
            if(!empty($get['drop']))
            {
                 $this->db->where('hms_ambulance_booking.destination LIKE "%'.$get['drop'].'%"');
            }
            if(!empty($get['remark']))
            {
                 $this->db->where('hms_ambulance_booking.remark LIKE "%'.$get['remark'].'%"');
            }
             if(!empty($get['payment_mode']))
            {
                 //$this->db->where('hms_ambulance_booking.payment_mode',$get['payment_mode']);
                 $this->db->where('hms_payment.pay_mode',$get['payment_mode']);
            }
//user collection payment_mode
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (13)');
            $this->db->where('hms_payment.debit>0'); 
             $this->db->where('hms_payment.type!=12'); 
            // $this->db->group_by('hms_payment.id');
            $this->db->group_by('hms_payment.pay_mode'); 
            $this->db->from('hms_payment');
            $new_self_ambulance_array['self_ambulance_coll_payment_mode']= $this->db->get()->result(); 
            //echo $this->db->last_query(); exit;
            return $new_self_ambulance_array;           
        } 
    
    }
    
      function get_collection_tab_setting($branch_id="",$order_by='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_branch_collection_tab_setting.*');   
        if(!empty($branch_id))
        {
            $this->db->where('hms_branch_collection_tab_setting.branch_id',$branch_id);
        }
        else
        {
            $this->db->where('hms_branch_collection_tab_setting.branch_id',$users_data['parent_id']); 
        }
        
        if(!empty($order_by))
        {
           $this->db->order_by('hms_branch_collection_tab_setting.order_by',$order_by); 
        }
        else
        {
            $this->db->order_by('hms_branch_collection_tab_setting.order_by','ASC');     
        }
        $this->db->where('hms_branch_collection_tab_setting.print_status',1);
        $query = $this->db->get('hms_branch_collection_tab_setting');
        $result = $query->result(); 
        //echo $this->db->last_query();exit;
        return $result; 
                
    }
    
     public function get_expenses_details($get=array())
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
            /* (CASE WHEN hms_expenses.department_type=0 THEN 'Office Expense'  WHEN hms_expenses.department_type=1 THEN 'Emp. Salary' WHEN hms_expenses.department_type=2 THEN 'Purchase Medicine' WHEN hms_expenses.department_type=3 THEN 'Sale Return' WHEN hms_expenses.department_type=4 THEN 'Purchase Stcok Inventroy' WHEN hms_expenses.department_type=5 THEN 'Vaccine Purchase' WHEN hms_expenses.department_type=6 THEN 'Vaccine Sale Return' WHEN hms_expenses.department_type=7 THEN 'OPD Payment Refund' WHEN hms_expenses.department_type=8 THEN 'Pathology payment Refund' WHEN hms_expenses.department_type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.department_type=10 THEN 'IPD Refund' WHEN hms_expenses.department_type=11 THEN 'OT Payment Refund' WHEN hms_expenses.department_type=12 THEN 'Blood Bank Refund' WHEN hms_expenses.department_type=13 THEN 'Ambulance Refund' WHEN hms_expenses.department_type=14 THEN 'OPD Billing Refund' WHEN hms_expenses.department_type=15 THEN 'Day Care' WHEN hms_expenses.department_type=15 THEN 'Doctor Commision' END)*/

            $this->db->select("(CASE WHEN hms_expenses.type=0 THEN hms_expenses.vouchar_no WHEN hms_expenses.type=2 THEN (select purchase_id from hms_medicine_purchase where id=hms_expenses.parent_id) ELSE hms_expenses.vouchar_no END) as vouchar_no, (CASE WHEN hms_expenses.type=0 THEN 'Office Expense' WHEN hms_expenses.type=3 THEN 'Sale Return' WHEN hms_expenses.type=1 THEN 'Emp. Salary' WHEN hms_expenses.type=0 THEN 'Expenses' WHEN hms_expenses.type=1 THEN 'Salary' WHEN hms_expenses.type=2 THEN 'Purchase Medicine' WHEN hms_expenses.type=3 THEN 'Sale Return' WHEN hms_expenses.type=4 THEN 'Purchase Stcok Inventroy' WHEN hms_expenses.type=5 THEN 'Vaccine Purchase' WHEN hms_expenses.type=6 THEN 'Vaccine Sale Return' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund' WHEN hms_expenses.type=8 THEN 'Pathology payment Refund' WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=11 THEN 'OT Payment Refund' WHEN hms_expenses.type=12 THEN 'Blood Bank Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' WHEN hms_expenses.type=14 THEN 'OPD Billing Refund' WHEN hms_expenses.type=15 THEN 'Day Care' WHEN hms_expenses.type=16 THEN 'Vendor Payment' ELSE 'N/A' END) as type, hms_expenses.paid_amount, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(CASE WHEN hms_expenses.type=0 THEN 'Office Expense'  WHEN hms_expenses.type=4 THEN 'Inventory Purchase' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Medicine Sale Return' WHEN hms_expenses.type=14 THEN 'OPD Billing' WHEN hms_expenses.type=5 THEN  'Vaccine Purchase' WHEN hms_expenses.type=6 THEN  'Vaccine Billing Return' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=11 THEN 'OT Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' WHEN hms_expenses.type=15 THEN 'Day Care Refund' WHEN hms_expenses.type=17 THEN 'Dialysis Refund' WHEN hms_expenses.type=16 THEN 'Vendor Payment' END) as expenses_type, (CASE WHEN hms_expenses.type=0 THEN hms_expenses_category.exp_category ELSE (CASE WHEN hms_expenses.type=0 THEN 'Office Expense'  WHEN hms_expenses.type=4 THEN 'Inventory Purchase' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Medicine Sale Return' WHEN hms_expenses.type=14 THEN 'OPD Billing' WHEN hms_expenses.type=5 THEN  'Vaccine Purchase' WHEN hms_expenses.type=6 THEN  'Vaccine Billing Return' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=11 THEN 'OT Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' WHEN hms_expenses.type=15 THEN 'Day Care Refund' WHEN hms_expenses.type=17 THEN 'Dialysis Refund' WHEN hms_expenses.type=16 THEN 'Vendor Payment' END) END) as exp_category,hms_expenses_category.exp_category as excategory");

            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id AND hms_expenses_category.branch_id=".$users_data['parent_id'],'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            
             if(!empty($get['cat_id']))	
            {	
              $this->db->where('hms_expenses.paid_to_id',$get['cat_id']);   	
            }	
            if(!empty($get['dept_type']))	
            {	
              $this->db->where('(hms_expenses.department_type='.$get['dept_type'].') OR (hms_expenses.type='.$get['dept_type'].')');   	
            }
            
            if(!empty($get['start_date']))
            {
              $this->db->where('hms_expenses.expenses_date >= "'.date('Y-m-d',strtotime($get['start_date'])).'"');
            }

            if(!empty($get['end_date']))
            {
              $this->db->where('hms_expenses.expenses_date<= "'.date('Y-m-d',strtotime($get['end_date'])).'"');   
            }

            if(!empty($get['branch_id']))
            {
              if(is_numeric($get['branch_id']) && $get['branch_id']>0)
              {
                 $this->db->where('hms_expenses.branch_id',$get['branch_id']);  
              } 
              else if($get['branch_id']=='all') 
              {
                 $this->db->where('hms_expenses.branch_id IN ('.$child_ids.')');  
              }  
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                 if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            elseif(!empty($get["employee"]) && is_numeric($get['employee']))
            {
                $emp_ids=  $get["employee"];
            }
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_expenses.created_by IN ('.$emp_ids.')');
            }

            $query = $this->db->get();
          //  echo $this->db->last_query();die;
           
            $result_expense['expense_list'] = $query->result(); 




            $this->db->select("(CASE WHEN hms_expenses.type=0 THEN hms_expenses.vouchar_no WHEN hms_expenses.type=2 THEN (select purchase_id from  hms_medicine_purchase where id=hms_expenses.parent_id) ELSE 'N/A' END) as vouchar_no, (CASE WHEN hms_expenses.type=0 THEN 'expenses' WHEN hms_expenses.type=3 THEN 'Sale Return' WHEN hms_expenses.type=1 THEN 'Emp. Salary' WHEN hms_expenses.type=2 THEN 'Purchase Medicine' WHEN hms_expenses.type=13 THEN 'Ambulance' ELSE 'N/A' END) as type, hms_expenses.paid_amount, (CASE WHEN hms_expenses.type=0 THEN hms_expenses_category.exp_category ELSE 'N/A' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");

            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            if(!empty($get['cat_id']))	
            {	
              $this->db->where('hms_expenses.paid_to_id',$get['cat_id']);   	
            }	
            if(!empty($get['dept_type']))	
            {	
              $this->db->where('hms_expenses.type='.$get['dept_type']);   	
            }
            
            if(!empty($get['start_date']))
            {
              $this->db->where('hms_expenses.expenses_date >= "'.date('Y-m-d',strtotime($get['start_date'])).'"');
            }

            if(!empty($get['end_date']))
            {
              $this->db->where('hms_expenses.expenses_date<= "'.date('Y-m-d',strtotime($get['end_date'])).'"');   
            }

            if(!empty($get['branch_id']))
            {
              if(is_numeric($get['branch_id']) && $get['branch_id']>0)
              {
                 $this->db->where('hms_expenses.branch_id',$get['branch_id']);  
              } 
              else if($get['branch_id']=='all') 
              {
                 $this->db->where('hms_expenses.branch_id IN ('.$child_ids.')');  
              }  
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
                if($users_data['collection_type']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            elseif(!empty($get["employee"]) && is_numeric($get['employee']))
            {
                $emp_ids=  $get["employee"];
            }
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_expenses.created_by IN ('.$emp_ids.')');
            }

            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
            return $result_expense;  
          
        } 
    }
    
}
?>