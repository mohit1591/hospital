<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_leder_model extends CI_Model {
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }
 
   public function get_ledger_report($get=array())
   {    
      //OPD Booking
         if(!empty($get['start_date']))
          {
            $start_date = date('Y-m-d',strtotime($get['start_date']));
          }
         if(!empty($get['end_date']))
          {
            $end_date = date('Y-m-d',strtotime($get['end_date']));
          }
        $this->db->select('hms_opd_booking.booking_date, CONCAT("OPD/",`hms_opd_booking`.booking_code,"/",`hms_patient`.patient_name) as `particular`, hms_opd_booking.paid_amount as credit,0 as debit,hms_payment_mode.payment_mode, hms_opd_booking.remarks');
        $this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id AND hms_opd_booking.is_deleted = 0','LEFT');
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_opd_booking.payment_mode','LEFT');
        $this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'" AND hms_opd_booking.booking_date <= "'.$end_date.'"');
        $this->db->get('hms_opd_booking');
        //echo  $this->db->last_query();die();
        /*  SELECT `hms_opd_booking`.`booking_date` as `booking_date`, 
        concat('OPD/',`hms_opd_booking`.booking_code,'/',`hms_patient`.patient_name) as `part`,
         `hms_opd_booking`.`paid_amount`, 0 as debit, `hms_payment_mode`.`payment_mode` as `payment_mode`, `hms_opd_booking`.`remarks`         
         FROM `hms_opd_booking` LEFT JOIN `hms_patient` ON `hms_patient`.`id`=`hms_opd_booking`.`patient_id`  
         LEFT JOIN `hms_payment_mode` ON `hms_payment_mode`.`id`=`hms_opd_booking`.`payment_mode` WHERE `hms_opd_booking`.`booking_date` >= "2020-03-01 00:00:00" AND `hms_opd_booking`.`booking_date` <= "2020-03-21 23:59:59"*/


   }

    public function get_exp_expenses_details($get=array())
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
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('EXP/',hms_expenses.vouchar_no,'/',hms_medicine_vendors.name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',0);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 

           
            return $result_expense;           
        } 
    }

    // 1 Salary expenses
    public function get_salary_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('EMPSAL/',hms_expenses.vouchar_no,'/',hms_employees.name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_employees','hms_employees.id=hms_expenses.employee_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',1);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 

           
            return $result_expense;           
        } 
    }


// 2 medicine purchase
    public function get_medicine_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('MEDPUR/',hms_expenses.vouchar_no,'/',hms_medicine_vendors.name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
             $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.paid_to_id",'left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',2);
            $this->db->where('hms_expenses.is_deleted!=2');
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
           // echo $this->db->last_query();die;
            $result_expense['expense_list'] = $query->result(); 

           
            return $result_expense;           
        } 
    }


// 3 Medicine Sale refund
    public function get_sale_return_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('MEDRET/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_patient','hms_patient.id=hms_expenses.paid_to_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',3);
            $this->db->where('hms_expenses.is_deleted!=2');
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
           // echo $this->db->last_query();die();
            $result_expense['expense_list'] = $query->result(); 

            return $result_expense;           
        } 
    }

// 4 Purchase stock inventory
    public function get_stock_inventory_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('PUINV/',hms_expenses.vouchar_no,'/',hms_medicine_vendors.name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.paid_to_id",'left');            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',4);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 

           
            return $result_expense;           
        } 
    }

    // 5 vaccine purchase
    public function get_vacc_purchase_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('PUVAC/',hms_expenses.vouchar_no,'/',hms_medicine_vendors.name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.paid_to_id",'left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',5);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 


            return $result_expense;           
        } 
    }
    // 6 vaccine sale refund
    public function get_vacc_sale_return_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('VACRET/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_patient','hms_patient.id=hms_expenses.paid_to_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',6);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            //echo $this->db->last_query();die();
            $result_expense['expense_list'] = $query->result(); 

            return $result_expense;           
        } 
    }
    // 7 opd payment return
    public function get_opd_refund_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('OPDREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_patient','hms_patient.id=hms_expenses.paid_to_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',7);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 

            return $result_expense;           
        } 
    }
    // 8 Pathology payment refund
    public function get_path_refund_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('PATHREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_patient","hms_patient.id=hms_expenses.paid_to_id",'left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',8);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            // echo $this->db->last_query();die();
            $result_expense['expense_list'] = $query->result(); 
            return $result_expense;           
        } 
    }
    // 9 medicine payment refund
    public function get_med_refund_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('MEDPAYREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_patient','hms_patient.id=hms_expenses.paid_to_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',9);
            $this->db->where('hms_expenses.is_deleted!=2');
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
           //echo $this->db->last_query();die();
            $result_expense['expense_list'] = $query->result(); 

            return $result_expense;           
        } 
    }
    // 10 IPD refund
    public function get_ipd_refund_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('IPDREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_patient","hms_patient.id = hms_expenses.paid_to_id",'left');            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',10);
            $this->db->where('hms_expenses.is_deleted!=2');
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
           // echo $this->db->last_query();die();
            $result_expense['expense_list'] = $query->result(); 
 
            return $result_expense;           
        } 
    }

     // 11 ot payment refund
    public function get_ot_refund_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('OTREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses');           
            $this->db->join("hms_patient","hms_patient.id = hms_expenses.paid_to_id",'left');            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',11);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 

             
            return $result_expense;           
        } 
    }
     // 12 blood bank payment refund
    public function get_bb_refund_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('OTREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_patient","hms_patient.id = hms_expenses.paid_to_id",'left');            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',12);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 

            
            return $result_expense;           
        } 
    }
     // 13 Ambulance refund
    public function get_ambulance_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('AMBREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_patient','hms_patient.id=hms_expenses.paid_to_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',13);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 
  
            return $result_expense;           
        } 
    }

     // 14 OPD Bill refund
    public function get_opd_bill_refund_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('BILLREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_patient","hms_patient.id = hms_expenses.paid_to_id",'left');            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',14);
            $this->db->where('hms_expenses.is_deleted!=2');
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
            $result_expense['expense_list'] = $query->result(); 

             
            return $result_expense;           
        } 
    }


    // 15 Day Care refund
    public function get_daycare_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.created_date as booking_date, 
            CONCAT('DCREF/',hms_expenses.vouchar_no,'/',hms_patient.patient_name) as particular,0 as credit, hms_expenses.paid_amount as debit, hms_payment_mode.payment_mode,hms_expenses.remarks as remarks, hms_expenses.payment_mode as pay_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_patient","hms_patient.id=hms_expenses.paid_to_id",'left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.type',15);
            $this->db->where('hms_expenses.is_deleted!=2');
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
           // echo $this->db->last_query();die();
            $result_expense['expense_list'] = $query->result(); 

            return $result_expense;           
        } 
    }


   // doctor_commision indivisual expenses 

    public function get_exp_doctor_commission_exp($get=array())
    {
        
            $users_data = $this->session->userdata('auth_users'); 
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            if(!empty($sub_branch_details))
            {
                $child_ids_arr = array_column($sub_branch_details,'id');
                $child_ids = implode(',',$child_ids_arr);
            } 
             $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('DR COMM','/',hms_doctors.doctor_name) as particular,0 as credit, hms_payment.debit as debit, hms_payment_mode.payment_mode, '' as remarks,(CASE WHEN hms_payment.section_id=2 THEN 'Doctor Commision' ELSE '' END) as type, (CASE WHEN hms_payment.section_id=2 THEN '' ELSE '' END) as vouchar_no, (CASE WHEN hms_payment.section_id=2 THEN 'N/A' ELSE '' END) as exp_category, (CASE WHEN hms_payment.section_id=2 THEN '' ELSE '' END) as remarks, hms_payment.debit as paid_amount, DATE_FORMAT(hms_payment.created_date,'%Y-%m-%d') as expenses_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as payment_mode");
            $this->db->join('hms_doctors','hms_doctors.id=hms_payment.doctor_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
             $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
            if(!empty($get['branch_id']))
            {
              if(is_numeric($get['branch_id']) && $get['branch_id']>0)
              {
                 $this->db->where('hms_payment.branch_id',$get['branch_id']);  
              } 
              else if($get['branch_id']=='all') 
              {
                 $this->db->where('hms_payment.branch_id IN ('.$child_ids.')');  
              }  
            }
             
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
            //user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (2)');
            $this->db->where('hms_payment.parent_id','0');
            $this->db->where('hms_payment.doctor_id > 0');
            $this->db->where('hms_payment.debit>0'); 
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
          
         //  echo $this->db->last_query();die;
            $result_expense['expense_list']= $this->db->get()->result();
            return $result_expense;
    }


/// collections

    public function self_opd_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users');
        $new_self_opd_array=array(); 

        if(!empty($get))
        {  
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('OPD/',hms_opd_booking.booking_code,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_opd_booking.remarks,hms_payment.pay_mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
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
             //user collection
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (2)'); 
            $this->db->where('hms_payment.debit > 0');
            
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            //$this->db->order_by('hms_payment.id','DESC');
            $this->db->from('hms_payment');
            $new_self_opd_array['self_coll'] = $this->db->get()->result(); 
            return $new_self_opd_array;
           
        } 
    }


    public function self_day_care_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users');
        $new_self_day_care_array=array(); 
        if(!empty($get))
        {  
            
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('DC/',hms_day_care_booking.booking_code,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_day_care_booking.remarks,hms_payment.pay_mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
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
             //user collection
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            $this->db->where('hms_day_care_booking.is_deleted',0); 
            $this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (14)'); 
            $this->db->where('hms_payment.debit > 0');
            
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            $new_self_day_care_array['self_coll'] = $this->db->get()->result(); 
            return $new_self_day_care_array;
        } 
    }


    public function self_purchase_collection_list($get="")
    {
        $new_purchase_array=array();
        $users_data = $this->session->userdata('auth_users');
            $branch_id = $users_data['parent_id']; 
            
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('MPR/',hms_medicine_return.return_no,'/',hms_medicine_vendors.name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_medicine_return.remarks,hms_payment.pay_mode");
            $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_payment.vendor_id','left'); 
            $this->db->join('hms_medicine_return','hms_medicine_return.id=hms_payment.parent_id AND hms_medicine_return.is_deleted=0');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_medicine_return.branch_id IN ('.$branch_id.')');
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
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (12)'); 
            $this->db->where('hms_payment.debit > 0');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            $new_purchase_array['self_coll'] = $this->db->get()->result(); 
           // echo $this->db->last_query(); exit; 
          
            return $new_purchase_array;
            
        
    }


    public function self_ambulance_collection_list($get="")
    {

        $users_data = $this->session->userdata('auth_users');
        $new_self_ambulance_array=array(); 
        if(!empty($get))
        {  
           $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('AMB/',hms_ambulance_booking.booking_no,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_ambulance_booking.remark as remarks,hms_payment.pay_mode"); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
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
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (13)');
            $this->db->where('hms_payment.debit>0'); 
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            $new_self_ambulance_array['self_coll']= $this->db->get()->result(); 
         //   echo $this->db->last_query();die;

          
            return $new_self_ambulance_array;
            
        } 
    }
public function self_billing_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_billing= array();
        if(!empty($get))
        { 
            
             $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('OPDBILL/',hms_opd_booking.reciept_code,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_opd_booking.remarks,hms_payment.pay_mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
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
            //user collection
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
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
            $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (4)');  //billing section id 4
            $this->db->where('hms_payment.debit>0');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
           
            $new_self_billing['self_coll'] = $this->db->get()->result();  
           
            return $new_self_billing;
        } 
    }
    
     public function self_medicine_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_medicine_array=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('MDSALE/',hms_medicine_sale.sale_no,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_medicine_sale.remarks,hms_payment.pay_mode");             
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id AND hms_medicine_sale.is_deleted=0');
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
            //user collection
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
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
            $this->db->where('hms_payment.section_id IN (3)'); 
            $this->db->where('hms_medicine_sale.is_deleted','0');
            $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            $new_medicine_array['self_coll'] = $this->db->get()->result();  

            return $new_medicine_array;
        } 
    }


 public function self_ipd_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_ipd_coll=array();
        if(!empty($get))
        { 
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('IPD/',hms_ipd_booking.ipd_no,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_ipd_booking.remarks,hms_payment.pay_mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id','left');
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
            //user collection
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
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
            $this->db->where('hms_payment.section_id IN (5)'); 
            $this->db->where('hms_ipd_booking.is_deleted','0');
            $this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit>0');
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
            $new_self_ipd_coll['self_coll'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); 

            /* payment coll_ipd */
            return $new_self_ipd_coll;
        } 
    } 


 public function pathology_self_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_path_array=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('PATH/',path_test_booking.lab_reg_no,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, path_test_booking.remarks,hms_payment.pay_mode");            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id AND hms_patient.is_deleted=0','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id AND path_test_booking.is_deleted=0');
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
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
           //user collection
           $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
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
            $this->db->where('path_test_booking.is_deleted','0');
            $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit > 0');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            $new_self_path_array['self_coll'] = $this->db->get()->result();  
            //echo $this->db->last_query();die;
            /* path payment coll */
            return  $new_self_path_array;
        } 
    }


    public function self_vaccination_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 

        $new_self_vaccination=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('VACS/',hms_vaccination_sale.sale_no,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_vaccination_sale.remarks,hms_payment.pay_mode"); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id');
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
            //user collection
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
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
            $this->db->where('hms_payment.section_id IN (7)'); 
            $this->db->where('hms_vaccination_sale.is_deleted','0');
            $this->db->where('hms_vaccination_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
           $new_self_vaccination['self_coll'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

            return $new_self_vaccination;
        } 
    }

     public function self_ot_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_ot_array=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('OT/',hms_operation_booking.booking_code,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode, hms_operation_booking.remarks,hms_payment.pay_mode"); 
           
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id');
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
            //user collection
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
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
            $this->db->where('hms_payment.section_id IN (8)'); 
            $this->db->where('hms_operation_booking.is_deleted','0');
            $this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit > 0');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            $new_self_ot_array['self_coll'] = $this->db->get()->result(); 
          
            
            return $new_self_ot_array;
        } 
    }

     public function self_blood_bank_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
       
        $new_self_blood=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.created_date as booking_date, 
            CONCAT('BB/',hms_blood_patient_to_recipient.issue_code,'/',hms_patient.patient_name) as particular,
            hms_payment.debit as credit, 0 as debit, hms_payment_mode.payment_mode,'' as remarks,hms_payment.pay_mode"); 
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id');
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
            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['collection_type']=='1')
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
            $this->db->where('hms_payment.section_id IN (10)'); 
            $this->db->where('hms_blood_patient_to_recipient.is_deleted','0');
            $this->db->where('hms_blood_patient_to_recipient.branch_id',$users_data['parent_id']);   
            $this->db->where('hms_payment.debit>0');
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->from('hms_payment');
            $new_self_blood['self_coll'] = $this->db->get()->result();
          
            return $new_self_blood;
        } 
    }



}
?>