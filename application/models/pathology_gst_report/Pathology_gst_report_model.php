<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_gst_report_model extends CI_Model {

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
        $search_data = $this->session->userdata('search_data'); 
          $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,path_test_booking.booking_date,path_test_booking.lab_reg_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode,hms_disease.disease,hms_patient.id as patientid,path_test_booking.id as book_id,path_test_booking.gst_amount,path_test_booking.total_amount,path_test_booking.lab_reg_no");
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id AND hms_patient.is_deleted=0','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id AND path_test_booking.is_deleted=0');
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_disease','hms_disease.id = path_test_booking.diseases','left');

            $this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id','left');
            $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
            
            
            $this->db->where('hms_payment.section_id',1); 
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
            $this->db->where('path_test_booking.gst_amount>0');   
            
            $this->db->from('hms_payment');
            
        if(isset($search_data) && !empty($search_data))
        {
            if(isset($search_data['branch_id']) && !empty($search_data['branch_id'])
                )
            {
                //echo $search_data['branch_id'];
                $sub_branch_details = $this->session->userdata('sub_branches_data');
                $sub_branch_id = array_column($sub_branch_details, 'id'); 
                if($search_data['branch_id']=='all')
                {
                    $sub_ids = implode(',', $sub_branch_id);
                    if(!empty($sub_ids))
                    {
                       $sub_ids = $users_data['parent_id'].','.$sub_ids;   
                    }
                    else
                    {
                        $sub_ids = $users_data['parent_id'];
                    }
                    
                    $this->db->where('hms_payment.branch_id IN ('.$sub_ids.')');
                }
                else
                {
                    $this->db->where('hms_payment.branch_id',$search_data['branch_id']);
                }

            }
            else
            {
                $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
            }
            if(isset($search_data['start_date']) && !empty($search_data['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('path_test_booking.booking_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date']))." 23:59:59";
                $this->db->where('path_test_booking.booking_date  <= "'.$end_date.'"');
            }

           
           
           
            
            
        }
        else
        {
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
        }
       
        $this->db->group_by('hms_payment.id');

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
        $search_data = $this->session->userdata('search_data'); 

       
        
          $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,path_test_booking.booking_date,path_test_booking.lab_reg_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode,hms_disease.disease,hms_patient.id as patientid,path_test_booking.id as book_id,path_test_booking.gst_amount,path_test_booking.total_amount,path_test_booking.lab_reg_no");
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id AND hms_patient.is_deleted=0','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id AND path_test_booking.is_deleted=0');
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            
            $this->db->join('hms_disease','hms_disease.id = path_test_booking.diseases','left');

            $this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id','left');
            $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
            
            $this->db->where('hms_payment.section_id',1); 
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
            $this->db->where('path_test_booking.gst_amount>0');  
            
            $this->db->from('hms_payment');
            
        if(isset($search_data) && !empty($search_data))
        {
            if(isset($search_data['branch_id']) && !empty($search_data['branch_id'])
                )
            {
                //echo $search_data['branch_id'];
                $sub_branch_details = $this->session->userdata('sub_branches_data');
                $sub_branch_id = array_column($sub_branch_details, 'id'); 
                if($search_data['branch_id']=='all')
                {
                    $sub_ids = implode(',', $sub_branch_id);
                    if(!empty($sub_ids))
                    {
                       $sub_ids = $users_data['parent_id'].','.$sub_ids;   
                    }
                    else
                    {
                        $sub_ids = $users_data['parent_id'];
                    }
                    
                    $this->db->where('hms_payment.branch_id IN ('.$sub_ids.')');
                }
                else
                {
                    $this->db->where('hms_payment.branch_id',$search_data['branch_id']);
                }

            }
            else
            {
                $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
            }
            if(isset($search_data['start_date']) && !empty($search_data['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('path_test_booking.booking_date  >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date']))." 23:59:59";
                $this->db->where('path_test_booking.booking_date  <= "'.$end_date.'"');
            }

           
           
            
            
        }
        else
        {
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
        }

        
        $this->db->group_by('hms_payment.id');
      
        $new_self_path_array['path_coll'] = $this->db->get()->result();  
            //echo $this->db->last_query();die;

            /* path payment coll */
             $this->db->select("hms_patient.patient_name,hms_patient.patient_code,path_test_booking.lab_reg_no as booking_code,hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
    
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            

            
        if(isset($search_data) && !empty($search_data))
        {
            if(isset($search_data['branch_id']) && !empty($search_data['branch_id'])
                )
            {
                //echo $search_data['branch_id'];
                $sub_branch_details = $this->session->userdata('sub_branches_data');
                $sub_branch_id = array_column($sub_branch_details, 'id'); 
                if($search_data['branch_id']=='all')
                {
                    $sub_ids = implode(',', $sub_branch_id);
                    if(!empty($sub_ids))
                    {
                       $sub_ids = $users_data['parent_id'].','.$sub_ids;   
                    }
                    else
                    {
                        $sub_ids = $users_data['parent_id'];
                    }
                    
                    $this->db->where('hms_payment.branch_id IN ('.$sub_ids.')');
                }
                else
                {
                    $this->db->where('hms_payment.branch_id',$search_data['branch_id']);
                }

            }
            else
            {
                $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
            }
            if(isset($search_data['start_date']) && !empty($search_data['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('path_test_booking.reminder_date  >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date']))." 23:59:59";
                $this->db->where('path_test_booking.reminder_date  <= "'.$end_date.'"');
            }

           
             if(isset($search_data['referral_hospital']) && $search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('path_test_booking.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif(isset($search_data['refered_id']) && $search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('path_test_booking.referral_doctor' ,$search_data['refered_id']);
            }
            /* refered by code */

            if(isset($search_data['referral_doctor']) && !empty($search_data['referral_doctor'])
                )
            { 
                $this->db->where('hms_doctors.id = "'.$search_data["referral_doctor"].'"');
                $this->db->where('hms_doctors.doctor_type IN (0,2)');
            }
            
            if(isset($search_data['attended_doctor']) && !empty($search_data['attended_doctor'])
                )
            { 
                $this->db->where('hms_doctors.id = "'.$search_data["attended_doctor"].'"');
                $this->db->where('hms_doctors.doctor_type IN (1,2)');
            }
            
            if(isset($search_data['diseases']) && !empty($search_data['diseases'])
                )
            { 
               $this->db->where('path_test_booking.diseases = "'.$search_data["diseases"].'"');
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
            
            if(isset($search_data['profile_id']) && !empty($search_data['profile_id'])
                )
            { 
                $this->db->where('path_test_booking.profile_id = "'.$search_data["profile_id"].'"');
            }
            
            if(isset($search_data['sample_collected_by']) && !empty($search_data['sample_collected_by'])
                )
            { 
                $this->db->where('path_test_booking.sample_collected_by = "'.$search_data["sample_collected_by"].'"');
            }
            
            if(isset($search_data['staff_refrenace_id']) && !empty($search_data['staff_refrenace_id'])
                )
            { 
                $this->db->where('path_test_booking.staff_refrenace_id = "'.$search_data["staff_refrenace_id"].'"');
            }

            
            
        }
        else
        {
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
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
            
            $this->db->where('hms_payment.section_id',1); 
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
            $this->db->order_by('hms_payment.id','DESC');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_self_path_array['path_coll_pay_mode'] = $this->db->get()->result();  
            /* path payment coll */


            return  $new_self_path_array;
        
        //return $query->result();
    
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
            $this->db->select("(CASE WHEN path_expenses.type=0 THEN path_expenses.vouchar_no ELSE '' END) as vouchar_no, (CASE WHEN path_expenses.type=0 THEN 'Expenses' WHEN path_expenses.type=1 THEN 'Emp. Salary' WHEN path_expenses.type=2 THEN 'Item Purchase'   ELSE '' END) as type, path_expenses.paid_amount, path_expenses.payment_mode, path_expenses.payment_mode, path_expenses_category.exp_category, path_expenses.created_date, (CASE WHEN path_expenses.vendor_id>0 THEN CONCAT('(',path_stock_vendors.name,')') WHEN path_expenses.employee_id>0 THEN CONCAT('(',hms_employees.name,')') ELSE '' END) as paid_to_name"); 
            $this->db->from('path_expenses'); 
            $this->db->join("path_expenses_category","path_expenses.paid_to_id=path_expenses_category.id",'left');
            $this->db->join("path_stock_vendors","path_stock_vendors.id=path_expenses.vendor_id",'left'); 
        $this->db->join("hms_employees","hms_employees.id=path_expenses.employee_id",'left'); 
            $this->db->where('path_expenses.paid_amount>0');
            if(!empty($get['start_date']))
            {
              $this->db->where('path_expenses.expenses_date >= "'.date('Y-m-d',strtotime($get['start_date'])).'"');
            }

            if(!empty($get['end_date']))
            {
              $this->db->where('path_expenses.expenses_date<= "'.date('Y-m-d',strtotime($get['end_date'])).'"');   
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
            $this->db->order_by('path_expenses.id','desc');
            $query = $this->db->get();
            //echo $this->db->last_query(); exit;
            $result = $query->result();  
            return $result;
        } 
       
    }

    public function branch_collection_list($get="",$ids=[])
    {
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            
            $this->db->select("hms_branch.branch_name, hms_patient.patient_name, hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode");
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');  
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');   
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')');   
            if(!empty($get['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($get['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($get['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            /*$emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }*/

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
            $this->db->where('hms_payment.section_id',1); 
            $this->db->order_by('hms_payment.id','DESC');
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            return $query->result();
        } 
    }

    public function doctor_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($get))
        {  
            $this->db->select("hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode");
            $this->db->join('hms_doctors','hms_doctors.id=hms_payment.doctor_id'); 
            $this->db->where('hms_doctors.doctor_pay_type',2); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.patient_id',0); 
            $this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($get['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($get['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            $emp_ids='';
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->where('hms_payment.section_id',1); 
            $this->db->order_by('hms_payment.id','DESC');
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            return $query->result();
        } 
    }

    public function self_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($get))
        {  
            $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($get['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($get['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            $emp_ids='';
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->where('hms_payment.section_id',1); 
            $this->db->order_by('hms_payment.id','DESC');
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            return $query->result();
        } 
    }



    ///////////////// Advance pathology function ///////////////////

    public function summerize_dept_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users'); 
          
        if(!empty($post['dept_list']))
        {
            $imp_users = implode(',', $post['users_id']);
            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit) as total_amount, sum(hms_payment.discount_amount) as total_discount, count(DISTINCT(hms_department.department)) as total_department');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id','left');
            $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
            $this->db->join('hms_department','hms_department.id = path_test.dept_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_department.id IN ('.$imp_users.')');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            //$this->db->group_by('path_test_booking_to_test.booking_id');
            //$this->db->group_by('hms_department.id');
            
            $emp_ids='';
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }

            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            return $query->result_array();
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }

    public function summerize_test_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');    
        if(!empty($post['dept_list']))
        {
            $imp_users = implode(',', $post['users_id']);
            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit) as total_amount, sum(hms_payment.discount_amount) as total_discount, count(DISTINCT(hms_department.department)) as total_department');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id','left');
            $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left');
            $this->db->join('hms_department','hms_department.id = path_test.dept_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_department.id IN ('.$imp_users.')');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            //$this->db->group_by('path_test_booking_to_test.booking_id');
            //$this->db->group_by('hms_department.id');
            $emp_ids='';
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            return $query->result_array();
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }

    /*public function summerize_test_booking_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $post = $this->input->post();    
        if(!empty($post))
        {
            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit) as total_amount, sum(hms_payment.discount_amount) as total_discount, sum((select count(id) from path_test_booking_to_test where booking_id = hms_payment.parent_id AND hms_payment.balance>0)) as total_test');
            // count(path_test_booking_to_test.test_id) as total_test
            //$this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id=hms_payment.parent_id');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            //$this->db->group_by('path_test_booking_to_test.booking_id');
            //$this->db->group_by('hms_department.id');
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            return $query->result_array();
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }*/

    public function summerize_users_booking_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');  
        if(!empty($post['users_id']))
        {
            $imp_users = implode(',', $post['users_id']);
            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, count(DISTINCT(hms_payment.created_by)) as total_users');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_payment.created_by IN ('.$imp_users.')');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            $emp_ids='';
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }

    public function summerize_att_doc_booking_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');  
        if(!empty($post['attended_doctor']))
        {
            $imp_users = implode(',', $post['attended_doctor']);
            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, count(DISTINCT(hms_doctors.doctor_name)) as total_doctors');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.attended_doctor','left');
            $this->db->where('hms_doctors.doctor_type IN (1,2)');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_doctors.id IN ('.$imp_users.')');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            $emp_ids='';
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }

            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }

    public function summerize_ref_doc_booking_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');  
        if(!empty($post['referral_doctor']))
        {
            $imp_users = implode(',', $post['referral_doctor']);
            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, count(DISTINCT(hms_doctors.doctor_name)) as total_doctors');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
            $this->db->where('hms_doctors.doctor_type IN (0,2)');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_doctors.id IN ('.$imp_users.')');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            $emp_ids='';
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }

    public function summerize_patient_booking_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');  
        if(!empty($post))
        {
            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, count(DISTINCT(hms_patient.patient_name)) as total_patient');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1'); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            $emp_ids='';
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }


    public function summerize_test_booking_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');  
        if(!empty($post))
        {
            $emp_ids='';
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
            $where = '';
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $where = " AND path_test_booking.branch_id = ".$users_data['parent_id']." AND path_test_booking.created_by IN (".$emp_ids.")";//$this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
                
            }



            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit) as total_amount, sum(hms_payment.discount_amount) as total_discount');

            //$this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit) as total_amount, sum(hms_payment.discount_amount) as total_discount');

            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id '.$where);
            //$this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1'); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            
            
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
                //$this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
                
            }
            //$this->db->group_by('hms_payment.id');
            $this->db->from('hms_payment');
            //$this->db->group_by('hms_payment.id');
            $query = $this->db->get();  
           //echo $this->db->last_query(); die;
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }



    public function summerize_pay_mode_booking_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');   
        if(!empty($post))
        {
            $this->db->select('sum(hms_payment.debit) as total_collection, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, count(DISTINCT(hms_payment_mode.payment_mode)) as total_mode');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
            $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1'); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
        }
    }

    public function details_dept_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');   

        if(!empty($post['dept_id']))
        {
            $payment_string = "";
            if(!empty($post['start_date']))
            {
              $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
              $payment_string .= ' AND payment.created_date >= "'.$start_date.'"';
            }

            if(!empty($post['end_date']))
            {
              $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
              $payment_string .=' AND payment.created_date <= "'.$end_date.'"';
            }
            $imp_users = implode(',', $post['dept_id']);
            $this->db->select('hms_department.id as dept_id, hms_department.department, sum(path_test_booking_to_test.amount)
                as total_amount,
                (path_test_booking.discount/
                (select count(DISTINCT(department.id))
                + 
                (select (CASE WHEN count(path_test_booking_to_profile.id)>0 THEN 1 ELSE 0 END) from path_test_booking_to_profile where path_test_booking_to_profile.test_booking_id = path_test_booking.id) 
                from hms_department department join path_test test on test.dept_id = department.id join path_test_booking_to_test booking_to_test on booking_to_test.test_id = test.id AND booking_to_test.test_type = "0" AND booking_to_test.parent_status=1 where booking_to_test.booking_id = path_test_booking.id)
                ) as total_discount, 
                    (
                    sum(hms_payment.debit)
                    /(select count(DISTINCT(booked_test.test_id))
                     + 
                (select (CASE WHEN count(path_test_booking_to_profile.id)>0 THEN 1 ELSE 0 END) from path_test_booking_to_profile where path_test_booking_to_profile.test_booking_id = path_test_booking.id) 
                     from path_test_booking_to_test booked_test left join path_test_booking as booking on booking.id = booked_test.booking_id  where booked_test.booking_id = path_test_booking.id AND booked_test.test_type  = "0" AND booked_test.parent_status=1))
                     as total_paid_amount');

            $this->db->join('path_test','path_test.dept_id = hms_department.id');

            $this->db->join('path_test_booking_to_test','path_test_booking_to_test.test_id = path_test.id AND path_test_booking_to_test.test_type  = "0" AND path_test_booking_to_test.parent_status=1');

            $this->db->join('path_test_booking','path_test_booking.id = path_test_booking_to_test.booking_id','left');

            $this->db->join('hms_payment','hms_payment.parent_id = path_test_booking.id AND hms_payment.section_id=1','left');
 
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);   
            $this->db->where('hms_department.id IN ('.$imp_users.')');
            $this->db->where('path_test.dept_id IN ('.$imp_users.')');
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('path_test_booking.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('path_test_booking.created_date <= "'.$end_date.'"');
            }
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                $this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
            }

            $this->db->group_by('hms_department.id'); 
            $this->db->group_by('path_test_booking.id');  

            $this->db->order_by('hms_department.id','ASC');             
            $this->db->from('hms_department');
            $query = $this->db->get();  
            $sql1 = $this->db->last_query();
            //echo $sql1;die;


            // Profile Data
            $this->db->select('(CASE WHEN count(path_test_booking_to_profile.id)>0 THEN "1" ELSE " " END) as dept_id, (CASE WHEN count(path_test_booking_to_profile.id)>0 THEN "PROFILE" ELSE " " END) as department, (sum(path_test_booking_to_profile.master_price))
                as total_amount,
                sum(path_test_booking.discount/
                 (select count(DISTINCT(hms_department.id))+1 from hms_department left join path_test on path_test.dept_id=hms_department.id left join path_test_booking_to_test on path_test_booking_to_test.test_id = path_test.id AND path_test_booking_to_test.test_type="0" AND path_test_booking_to_test.parent_status="1" where path_test_booking_to_test.booking_id=path_test_booking.id))
                 as total_discount,
                     
                       sum(hms_payment.debit/(select count(DISTINCT(path_test_booking_to_test.test_id))+1 from hms_department left join path_test on path_test.dept_id=hms_department.id left join path_test_booking_to_test on path_test_booking_to_test.test_id = path_test.id AND path_test_booking_to_test.test_type="0" AND path_test_booking_to_test.parent_status="1" where path_test_booking_to_test.booking_id=path_test_booking.id))
                        
                     as total_paid_amount');
  
            $this->db->join('path_test_booking_to_profile','path_test_booking_to_profile.test_booking_id = path_test_booking.id');


            /*$this->db->join('path_test_booking_to_test','path_test_booking_to_test.booking_id = path_test_booking.id AND path_test_booking_to_test.test_type  IN (1,2)');
            $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id');
            $this->db->join('hms_department','hms_department.id = path_test.dept_id');*/
            
            $this->db->join('hms_payment','hms_payment.parent_id = path_test_booking.id AND hms_payment.section_id=1','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']); 
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('path_test_booking.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('path_test_booking.created_date <= "'.$end_date.'"');
            }
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                $this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
            }                
            $this->db->from('path_test_booking');
            $query = $this->db->get();  
            //echo $this->db->last_query();die;
            $sql2 = $this->db->last_query();
            //echo $sql2;die;
            ///////////////////////// 
            $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.")");  
            //echo $this->db->last_query();die;
            $result = $sql->result_array();
            //echo "<pre>"; print_r($result);die;
            if(!empty($result))
            {
                $data_result = [];
                $ii=1; 
                $key_search = '';
                foreach($result as $res_key=>$resul)
                { 
                    if(!empty($data_result))
                    {
                        foreach($data_result as $d_key=>$data_res)
                        {
                            if ($resul['department'] == $data_res['department']) 
                            {
                                $key_search = $d_key; 
                                break;
                            }
                        }

                    } 
                    //echo "<pre>"; print_r($result);die;
                    if(!empty($resul['dept_id']))
                    {
                      if(!empty($key_search))
                        {  
                             $data_result[$resul['dept_id']] = array(
                            'dept_id'=>$resul['dept_id'],
                            'department'=>$resul['department'],
                            'total_amount'=>$data_result[$resul['dept_id']]['total_amount']+$resul['total_amount'],
                            'total_discount'=>$data_result[$resul['dept_id']]['total_discount']+$resul['total_discount'],
                            'total_paid_amount'=>$data_result[$resul['dept_id']]['total_paid_amount']+$resul['total_paid_amount']
                            );  
                            
                        }
                        else
                        {
                           $data_result[$resul['dept_id']] = $resul; 
                        }   
                    }  

                     

                    if(isset($result[$res_key]))
                        {
                            unset($result[$res_key]);
                        } 
                    
                  $ii++;
                }

                //echo "<pre>"; print_r($data_result);die;
                //echo "<pre>";print_r($result);die;
                $result = $data_result; 
               /* $lab_key = array_search('LAB', array_column($result, 'department'));
                $profile_key = array_search('PROFILE', array_column($result, 'department'));
                //echo $lab_key;die;
                if(is_numeric($profile_key) && is_numeric($lab_key))
                {  
                   $result[$lab_key] = array(
                                              'department'=>$result[$lab_key]['department'],
                                              'total_amount'=>$result[$lab_key]['total_amount']+$result[$profile_key]['total_amount'],
                                              'total_discount'=>$result[$lab_key]['total_discount']+$result[$profile_key]['total_discount'],
                                              'total_paid_amount'=>$result[$lab_key]['total_paid_amount']+$result[$profile_key]['total_paid_amount'] 
                                            ); 
                   unset($result[$profile_key]);
                }*/
                 
            } 
            //echo "<pre>"; print_r($result);die;
            return $result;
        }
    }


    public function details_test_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');     
        if(!empty($post['test_id']))
        { 
            $this->db->select('path_test.test_name, count(path_test_booking_to_test.test_id) as total_test, sum(path_test_booking_to_test.amount) as total_amount');
            $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id');
            $this->db->join('path_test_booking','path_test_booking.id = path_test_booking_to_test.booking_id AND path_test_booking_to_test.test_type IN (0,2) AND path_test_booking_to_test.parent_status=1','left');
 
            $this->db->join('hms_payment','hms_payment.parent_id = path_test_booking.id AND hms_payment.section_id=1','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);   
            //$this->db->where('path_test_booking_to_test.test_id IN ('.$imp_users.')');
            //$this->db->where('path_test.id IN ('.$imp_users.')');
            if(!empty($post['start_date']))
            {
              $start_date = date('Y-m-d',strtotime($post['start_date']));
              $this->db->where('path_test_booking.booking_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
               $end_date = date('Y-m-d',strtotime($post['end_date']));
               $this->db->where('path_test_booking.booking_date <= "'.$end_date.'"');
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                //$this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
            }

            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->group_by('path_test_booking_to_test.test_id');
            $this->db->order_by('path_test_booking.id','desc');            
            $this->db->from('path_test_booking_to_test');
            $query = $this->db->get();  
            $sql1 = $this->db->last_query();
            //echo $sql1;die;


            /////////// Profile data ////////
            $this->db->select('path_profile.profile_name as test_name, count(path_test_booking_to_profile.profile_id) as total_test, sum(path_test_booking_to_profile.master_price) as total_amount');
            $this->db->join('path_profile','path_profile.id = path_test_booking_to_profile.profile_id');  
            
            $this->db->join('path_test_booking','path_test_booking.id = path_test_booking_to_profile.test_booking_id','left');

            $this->db->join('hms_payment','hms_payment.parent_id = path_test_booking.id AND hms_payment.section_id=1','left');

            

            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);    
            if(!empty($post['start_date']))
            {
              $start_date = date('Y-m-d',strtotime($post['start_date']));
              $this->db->where('path_test_booking.booking_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
               $end_date = date('Y-m-d',strtotime($post['end_date']));
               $this->db->where('path_test_booking.booking_date <= "'.$end_date.'"');
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                //$this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
            }

            $this->db->group_by('path_test_booking_to_profile.profile_id');
            $this->db->order_by('path_test_booking.id','desc');            
            $this->db->from('path_test_booking_to_profile');
            $query = $this->db->get();  
            $sql2 = $this->db->last_query();
            //echo $sql2;die;
            /////////////////////////////////

            $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.")");  
            return $sql->result_array();
            //echo $this->db->last_query();die;
            //
            //echo "<pre>"; print_r($sql->result_array());die;
            //return 
        }
    }

    public function details_test_total_entities($post=array())
    {
        $users_data = $this->session->userdata('auth_users'); 
        $sub_string = "";
        if(!empty($post['start_date']))
            {
              $start_date = date('Y-m-d',strtotime($post['start_date']))." 00:00:00";
              $sub_string .= " AND hms_payment.created_date >= '".$start_date."'";
            }

            if(!empty($post['end_date']))
            {
               $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $sub_string .= " AND hms_payment.created_date <= '".$end_date."'";
            }
            //
            $this->db->select('sum(path_test_booking.discount) as total_discount, (select sum(hms_payment.debit) from hms_payment join path_test_booking on path_test_booking.id = hms_payment.parent_id where hms_payment.section_id=1 AND path_test_booking.is_deleted=0 AND hms_payment.branch_id="'.$users_data['parent_id'].'" '.$sub_string.' ) as total_paid_amount');    
            //$this->db->join('hms_payment','hms_payment.parent_id = path_test_booking.id AND hms_payment.section_id=1','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);    
            if(!empty($post['start_date']))
            {
              $start_date = date('Y-m-d',strtotime($post['start_date']));
              $this->db->where('path_test_booking.booking_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
               $end_date = date('Y-m-d',strtotime($post['end_date']));
               $this->db->where('path_test_booking.booking_date <= "'.$end_date.'"');
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                //$this->db->where('path_test_booking.created_by IN ('.$emp_ids.')');
            }

            //$this->db->group_by('path_test_booking.id'); 
            $this->db->order_by('path_test_booking.id','desc');            
            $this->db->from('path_test_booking');
            $query = $this->db->get(); 
            //echo $this->db->last_query();die;
            return $query->result(); 

    }

    public function details_users_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');     
        if(!empty($post['users_id']))
        {
            $imp_users = implode(',', $post['users_id']);
            $this->db->select('path_test_booking.id, path_test_booking.lab_reg_no, sum(hms_payment.debit) as total_paid_amount, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, (CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE "Admin" END) as emp_name, hms_payment.created_date');
            $this->db->join('hms_users','hms_users.id = hms_payment.created_by');
            $this->db->join('hms_employees','hms_employees.id = hms_users.emp_id','left');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_payment.created_by IN ('.$imp_users.')');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }

            $this->db->group_by('hms_payment.parent_id');
            $this->db->from('hms_payment');
            $this->db->order_by('path_test_booking.id','desc');   
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }

    public function details_att_doc_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');  
        if(!empty($post['attended_doctor']))
        {
            $imp_users = implode(',', $post['attended_doctor']);
            $this->db->select('path_test_booking.id, path_test_booking.lab_reg_no, sum(hms_payment.debit) as total_paid_amount, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, hms_doctors.doctor_name, hms_payment.created_date');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.attended_doctor','left');
            $this->db->where('hms_doctors.doctor_type IN (1,2)');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_doctors.id IN ('.$imp_users.')');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);  
            $this->db->group_by('hms_payment.parent_id');
            $this->db->order_by('path_test_booking.id','desc');   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }

    public function details_ref_doc_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');  
        if(!empty($post['referral_doctor']))
        {
            $imp_users = implode(',', $post['referral_doctor']);
            $this->db->select('path_test_booking.id, path_test_booking.lab_reg_no, sum(hms_payment.debit) as total_paid_amount, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, hms_doctors.doctor_name, hms_payment.created_date');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
            $this->db->where('hms_doctors.doctor_type IN (0,2)');
            $this->db->where('hms_doctors.doctor_pay_type','1');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1');
            $this->db->where('hms_doctors.id IN ('.$imp_users.')');
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);  
            $this->db->group_by('hms_payment.parent_id');
            $this->db->order_by('path_test_booking.id','desc');   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }


    public function details_pay_mode_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');  
        if(!empty($post['payment_mode']))
        {
            $imp_users = implode(',', $post['payment_mode']);
            $this->db->select('path_test_booking.id, path_test_booking.lab_reg_no, sum(hms_payment.debit) as total_paid_amount, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, hms_payment_mode.payment_mode, hms_payment.created_date');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
            $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1'); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);  
            $this->db->where('hms_payment_mode.id IN ('.$imp_users.')'); 
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->group_by('hms_payment.parent_id');
            $this->db->order_by('path_test_booking.id','desc'); 
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
        }
    }

    public function date_wise_list($post=array())
    {
        $users_data = $this->session->userdata('auth_users');     
        if(!empty($post))
        { 
            $this->db->select('path_test_booking.id, sum(hms_payment.debit) as total_paid_amount, sum(hms_payment.credit)+sum(hms_payment.discount_amount) as total_amount, sum(hms_payment.discount_amount) as total_discount, hms_payment.created_date');  
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id','left');
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('hms_payment.section_id','1'); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            if(!empty($post['start_date']))
            {
            $start_date = date('Y-m-d',strtotime($post['start_date'])).' 00:00:00';
             $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(!empty($post['end_date']))
            {
            $end_date = date('Y-m-d',strtotime($post['end_date'])).' 23:59:59';
               $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }

            $this->db->group_by('CAST(hms_payment.created_date AS DATE)');
            $this->db->order_by('hms_payment.created_date','ASC');
            $this->db->from('hms_payment');
            $this->db->order_by('path_test_booking.booking_date','desc');   
            $query = $this->db->get();  
            //echo $this->db->last_query();
            $result = $query->result_array();
            return $result;
            //echo "<pre>"; print_r($result);die;
            //return 
        }
    }

    public function branch_report_data()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('branch_id',$user_data['parent_id']);  
        $query = $this->db->get('path_report_default');
        $result = $query->result_array(); 
        return $result;
    }

}
?>