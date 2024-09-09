<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {

  var $table = 'hms_opd_booking';
  var $column = array('hms_payment.credit','hms_payment.debit', 'hms_doctors.doctor_name');  
  var $order = array('id' => 'desc');

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function branch_doctor_payment($search_data="")
  {
      $users_data = $this->session->userdata('auth_users');  
      $company_data = $this->session->userdata('company_data');  
      $branch_id = $company_data['id']; 
    if(!empty($search_data['branch_id']) && empty($search_data['doctor_id']))
    { 
       $start_date = '';
       $end_date = '';
       if(!empty($search_data['start_date']))
       {
         $s_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
         $start_date = ' AND created_date >="'.$s_date.'"';
       }

       if(!empty($search_data['end_date']))
       {
         $e_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
         $end_date = ' AND created_date <="'.$e_date.'"';
       } 
           $this->db->select("sum(hms_payment.test_base_rate) as credit_payment, (select sum(debit) from hms_payment where branch_id = ".$search_data['branch_id']." AND parent_id = 0 AND doctor_id = 0 AND patient_id = 0 ".$start_date.$end_date.") as debit_payment, hms_branch.branch_name as name");  
           $this->db->join('hms_branch','hms_branch.id = hms_payment.branch_id');  
       $this->db->where('hms_branch.id',$search_data['branch_id']); 
       if(!empty($search_data['start_date']))
       {
         $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00"');
       }
       
       if(!empty($search_data['end_date']))
       {
         $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59"');
       }
        
    }

     else if(!empty($search_data['doctor_id']) && empty($search_data['branch_id']))
    { 
       $start_date = '';
       $end_date = '';
       if(!empty($search_data['start_date']))
       {
         $s_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
         $start_date = ' AND created_date >="'.$s_date.'"';
       }

       if(!empty($search_data['end_date']))
       {
         $e_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
         $end_date = ' AND created_date <="'.$e_date.'"';
       }  
       $this->db->select("sum(hms_payment.total_base_amount) as credit_payment, (select sum(debit) from hms_payment where doctor_id = ".$search_data['doctor_id']." AND branch_id = ".$branch_id." AND parent_id=0 ".$start_date.$end_date.") as debit_payment, hms_doctors.doctor_name as name");
       $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id');  
           $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
           //$this->db->join('path_rate_plan','path_rate_plan.id = hms_doctors.rate_plan_id','left');
       $this->db->where('hms_doctors.doctor_pay_type',2);
       $this->db->where('path_test_booking.referral_doctor',$search_data['doctor_id']);
       $this->db->where('hms_payment.doctor_id IN (0,'.$search_data['doctor_id'].')');
       if(!empty($search_data['start_date']))
       {
         $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00"');
       }
       
       if(!empty($search_data['end_date']))
       {
         $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59"');
       }
       
       $this->db->where('path_test_booking.branch_id',$branch_id);
    } 
    else
    {  
      $this->db->select("sum(hms_payment.credit) as credit_payment, sum(hms_payment.debit) as debit_payment, hms_doctors.doctor_name as name");
      $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id');  
      $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
      if(!empty($search_data['start_date']))
       {
         $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00"');
       }
       
       if(!empty($search_data['end_date']))
       {
         $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59"');
       }
      $this->db->where('hms_payment.id',0); 
    } 
        $this->db->where('hms_payment.section_id',1); 
    $this->db->from('hms_payment');
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    return $query->result();
  }
    
  public function branch_doctor_payment_old($search_data="")
  {
      //doctor_name
      $users_data = $this->session->userdata('auth_users'); 
    if(!empty($search_data['branch_id']) && empty($search_data['doctor_id']))
    { 
           $start_date = '';
       $end_date = '';
       if(!empty($search_data['start_date']))
       {
         $s_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
         $start_date = ' AND created_date >="'.$s_date.'"';
       }

       if(!empty($search_data['end_date']))
       {
         $e_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
         $end_date = ' AND created_date <="'.$e_date.'"';
       }

           $this->db->select("sum(hms_payment.net_amount) as credit_payment, (select sum(debit) from hms_payment where branch_id = ".$search_data['branch_id']." AND parent_id = 0 AND doctor_id = 0 AND patient_id = 0 ".$start_date.$end_date." ) as debit_payment, hms_branch.branch_name as name");  
           $this->db->join('hms_branch','hms_branch.id = hms_payment.branch_id');  
       $this->db->where('hms_branch.id',$search_data['branch_id']); 
       if(!empty($search_data['start_date']) && $search_data['start_date']!=date('d-m-Y'))
       {
         $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($search_data['start_date'])).' 00.00.00'.'"');
       }
       
       if(!empty($search_data['end_date']) && $search_data['end_date']!=date('d-m-Y'))
       {
         $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($search_data['end_date'])).' 23.59.59'.'"');
       }
        
    }

     else if(!empty($search_data['doctor_id']) && empty($search_data['branch_id']))
    { 
       $start_date = '';
       $end_date = '';
       if(!empty($search_data['start_date']))
       {
         $s_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
         $start_date = ' AND created_date >="'.$s_date.'"';
       }

       if(!empty($search_data['end_date']))
       {
         $e_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
         $end_date = ' AND created_date <="'.$e_date.'"';
       }
       $this->db->select("sum(hms_payment.net_amount) as credit_payment, (select sum(debit) from hms_payment where doctor_id = ".$search_data['doctor_id']." AND branch_id = ".$users_data['parent_id']." AND parent_id=0 ".$start_date.$end_date.") as debit_payment, hms_doctors.doctor_name as name");
       $this->db->join('hms_opd_booking','hms_opd_booking.id = hms_payment.parent_id');  
           $this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
           $this->db->where('hms_doctors.doctor_pay_type',2);
       $this->db->where('hms_opd_booking.referral_doctor',$search_data['doctor_id']);
       $this->db->where('hms_payment.doctor_id IN (0,'.$search_data['doctor_id'].')');
       if(!empty($search_data['start_date']) && $search_data['start_date']!=date('d-m-Y'))
       {
         $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($search_data['start_date'])).' 00.00.00'.'"');
       }
       
       if(!empty($search_data['end_date']) && $search_data['end_date']!=date('d-m-Y'))
       {
         $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($search_data['end_date'])).' 23.59.59'.'"');
       }
       
       $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
    } 
    else
    {  
      $this->db->select("sum(hms_payment.credit) as credit_payment, sum(hms_payment.debit) as debit_payment, hms_doctors.doctor_name as name");
      $this->db->join('hms_opd_booking','hms_opd_booking.id = hms_payment.parent_id');  
      $this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
      if(!empty($search_data['start_date']))
       {
         $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00"');
       }
       
       if(!empty($search_data['end_date']))
       {
         $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59"');
       }
      $this->db->where('hms_payment.id',0); 
    } 
        $this->db->where('hms_payment.section_id',2); 
    $this->db->from('hms_payment');
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    return $query->result();
  }
   
  public function db_comission_list($get=array())
  {
     
     $users_data = $this->session->userdata('auth_users'); 
     $company_data = $this->session->userdata('company_data');
     $branch_id = $company_data['id']; 
     if(!empty($get))
     {
        if($get['type']==1)
      {
      $this->db->select("hms_patient.patient_name, path_test_booking.lab_reg_no, hms_payment.net_amount as total_amount, hms_payment.test_base_rate as rate, path_test_booking.payment_mode,hms_payment_mode.payment_mode as p_mode");
      $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id');  
      $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
      $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
      $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode');
      $this->db->where('hms_doctors.doctor_pay_type',2);
      $this->db->where('path_test_booking.referral_doctor',$get['ids']);
      $this->db->where('hms_payment.doctor_id IN (0,'.$get['ids'].')');
      $this->db->where('path_test_booking.branch_id',$branch_id);
      if(!empty($get['start_date']))
      {
      $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($get['start_date'])).' 00:00:00"');
      }
      
      if(!empty($get['end_date']))
      {
      $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($get['end_date'])).' 23:59:59"');
      }
      $this->db->where('hms_payment.section_id',1); 
      $this->db->order_by('hms_payment.id','DESC');
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
      }
      else if($get['type']==0)
      { 
            $this->db->select("hms_patient.patient_name, path_test_booking.lab_reg_no, path_test_booking.net_amount as total_amount, hms_payment.test_base_rate as rate, hms_doctors.doctor_name , path_test_booking.payment_mode,hms_payment_mode.payment_mode as p_mode");
      $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id');  
      $this->db->join('hms_branch','hms_branch.id = hms_payment.branch_id','left');
      $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
      $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode');
      $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');   
      $this->db->where('hms_payment.branch_id',$get['ids']);
      if(!empty($get['start_date']))
      {
      $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($get['start_date'])).' 00:00:00"');
      }
      
      if(!empty($get['end_date']))
      {
      $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($get['end_date'])).' 23:59:59"');
      }
      $this->db->where('hms_payment.section_id',1); 
      $this->db->order_by('hms_payment.id','DESC');
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
      } 
     }

     /*$users_data = $this->session->userdata('auth_users'); 
     if(!empty($get))
     {
        if($get['type']==1)
      {
      $this->db->select("hms_patient.patient_name, hms_opd_booking.booking_code, hms_payment.debit as total_amount, hms_payment.credit as rate, hms_payment.pay_mode");
      $this->db->join('hms_opd_booking','hms_opd_booking.id = hms_payment.parent_id');  
      $this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
      $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id','left');
      $this->db->where('hms_doctors.doctor_pay_type',2);
      $this->db->where('hms_opd_booking.referral_doctor',$get['ids']);
      $this->db->where('hms_payment.doctor_id IN (0,'.$get['ids'].')');
      if($users_data['users_role']==3)
        {
        }
        else
        {
          $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']); 
        }
      
      if(!empty($get['start_date']) && $get['start_date']!=date('d-m-Y'))
      {
      $this->db->where('hms_payment.created_date >= "'.$get['start_date'].'"');
      }
      
      if(!empty($get['end_date']) && $get['end_date']!=date('d-m-Y'))
      {
      $this->db->where('hms_payment.created_date <= "'.$get['start_date'].'"');
      }
      $this->db->where('hms_payment.section_id',2); 
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
      }
      else if($get['type']==0)
      { 
            $this->db->select("hms_patient.patient_name, hms_opd_booking.booking_code, hms_opd_booking.net_amount as total_amount, hms_payment.net_amount as rate");
      $this->db->join('hms_opd_booking','hms_opd_booking.id = hms_payment.parent_id');  
      $this->db->join('hms_branch','hms_branch.id = hms_payment.branch_id','left');
      $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id','left');   
      $this->db->where('hms_payment.branch_id',$get['ids']);
      if(!empty($get['start_date']) && $get['start_date']!=date('d-m-Y'))
      {
      $this->db->where('hms_payment.created_date >= "'.$get['start_date'].'"');
      }
      
      if(!empty($get['end_date']) && $get['end_date']!=date('d-m-Y'))
      {
      $this->db->where('hms_payment.created_date <= "'.$get['start_date'].'"');
      }
      $this->db->where('hms_payment.section_id',2); 
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
      } 
     }*/
  }

  public function doctor_paid_comission_list($get=array())
  {
     $users_data = $this->session->userdata('auth_users'); 
     if(!empty($get))
     {
        $this->db->select("hms_payment.debit, hms_payment.pay_mode, hms_payment.created_date,hms_payment_mode.payment_mode");
          if(isset($get['doctor_id']) && !empty($get['doctor_id']))
          {
            $this->db->where('hms_payment.doctor_id',$get['doctor_id']); 
          } 

          if(isset($get['branch_id']) && !empty($get['branch_id']))
          {
            $this->db->where('hms_payment.branch_id',$get['branch_id']); 
          }
          else
          {
                $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
          } 
      $this->db->where('hms_payment.parent_id',0);  
      if(!empty($get['start_date']))
      {
      $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($get['start_date'])).' 00:00:00"');
      }
      
      if(!empty($get['end_date']))
      {
      $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($get['start_date'])).' 23:59:59"');
      } 
      $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode');
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
     }
  }
  
  public function db_detail_list_old($get=array())
  {
     $users_data = $this->session->userdata('auth_users'); 
     if(!empty($get))
     {
        if($get['type']==1)
      {
      $this->db->select(" hms_payment.debit, hms_payment.pay_mode, hms_payment.created_date, hms_payment.pay_mode");
      $this->db->where('hms_payment.doctor_id',$get['ids']); 
      $this->db->where('hms_payment.parent_id',0); 
      $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
      if(!empty($get['start_date']) && $get['start_date']!=date('d-m-Y'))
      {
      $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($get['start_date'])).'"');
      }
      
      if(!empty($get['end_date']) && $get['end_date']!=date('d-m-Y'))
      {
      $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($get['end_date'])).'"');
      }
      $this->db->where('hms_payment.section_id',2); 
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
      } 
      else if($get['type']==0)
      {
      $this->db->select(" hms_payment.debit, hms_payment.created_date");
      $this->db->where('hms_payment.doctor_id',0); 
      $this->db->where('hms_payment.branch_id',$get['ids']); 
      $this->db->where('hms_payment.patient_id',0); 
      $this->db->where('hms_payment.parent_id',0); 
      if(!empty($get['start_date']) && $get['start_date']!=date('d-m-Y'))
      {
      $this->db->where('hms_payment.created_date >= "'.$get['start_date'].'"');
      }
      
      if(!empty($get['end_date']) && $get['end_date']!=date('d-m-Y'))
      {
      $this->db->where('hms_payment.created_date <= "'.$get['start_date'].'"');
      }
      $this->db->where('hms_payment.section_id',2); 
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
      } 
     }
  }


  public function db_detail_list($get=array())
  {
     $users_data = $this->session->userdata('auth_users'); 
     $company_data = $this->session->userdata('company_data');
     $branch_id = $company_data['id']; 
     if(!empty($get))
     {
        if($get['type']==1)
      {
      $this->db->select("hms_payment.debit, hms_payment.pay_mode, hms_payment.created_date,hms_payment_mode.payment_mode as p_mode");
      $this->db->where('hms_payment.doctor_id',$get['ids']); 
      $this->db->where('hms_payment.parent_id',0); 
      $this->db->where('hms_payment.branch_id',$branch_id);
      $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode');
      if(!empty($get['start_date']))
      {
      $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($get['start_date'])).' 00:00:00"');
      }
      
      if(!empty($get['end_date']))
      {
      $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($get['end_date'])).' 23:59:59"');
      }
      $this->db->where('hms_payment.section_id',1); 
      $this->db->order_by('hms_payment.id','DESC');
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
      } 
      else if($get['type']==0)
      {
      $this->db->select("hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as p_mode");
      $this->db->where('hms_payment.doctor_id',0); 
      $this->db->where('hms_payment.branch_id',$get['ids']); 
      $this->db->where('hms_payment.patient_id',0); 
      $this->db->where('hms_payment.parent_id',0); 
      $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode');
      if(!empty($get['start_date']))
      {
      $this->db->where('hms_payment.created_date >= "'.date('Y-m-d', strtotime($get['start_date'])).' 00:00:00"');
      }
      
      if(!empty($get['end_date']))
      {
      $this->db->where('hms_payment.created_date <= "'.date('Y-m-d', strtotime($get['end_date'])).' 23:59:59"');
      }
      $this->db->where('hms_payment.section_id',1); 
      $this->db->order_by('hms_payment.id','DESC');
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
      } 
     }
  }
    

    
    public function get_transection_doctor()
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('*');
      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->where('status',1);
      $this->db->where('doctor_pay_type',2); 
      $this->db->where('is_deleted',0); 
      $this->db->order_by('doctor_name','ASC'); 
      $query = $this->db->get('hms_doctors');
    return $query->result();
    }
 
  public function doctor_branch_pay()
  {
      $users_data = $this->session->userdata('auth_users');
      $post = $this->input->post();
    if(!empty($post))
    {
      // $bank_name = '';
      // $cheque_no = '';
      // $cheque_date = '';
      // $transection_no = '';
      // if(isset($post['bank_name']))
      // {
      //  $bank_name = $post['bank_name'];
      // }
      // if(isset($post['cheque_no']))
      // {
      //  $cheque_no = $post['cheque_no'];
      // }
      // if(isset($post['cheque_date']))
      // {
      //  $cheque_date = date('Y-m-d', strtotime($post['cheque_date']));
      // }
      // if(isset($post['transection_no']))
      // {
      //  $transection_no = $post['transection_no'];
      // }
       
       if(isset($post['doctor_id']) && !empty($post['doctor_id']))
       { 
          $data = array(
                        'branch_id'=>$users_data['parent_id'],
                'section_id'=>'1', 
                'branch_id'=>$users_data['parent_id'],
                'doctor_id'=>$post['doctor_id'],
                'pay_mode'=>$post['pay_mode'],
                //'bank_name'=>$bank_name,
                //'cheque_no'=>$cheque_no,
                //'cheque_date'=>$cheque_date,
                //'transection_no'=>$transection_no,
                'debit'=>$post['pay_amount'],
                'status'=>1,
                'created_by'=>$users_data['id'],
                'created_date'=>date('Y-m-d H:i:s')   
                );
      $this->db->insert('hms_payment',$data); 
            $last_id= $this->db->insert_id();
      /*add sales banlk detail*/
        if(!empty($post['field_name']))
        {
        $post_field_value_name= $post['field_name'];
        $counter_name= count($post_field_value_name); 
          for($i=0;$i<$counter_name;$i++) 
          {
              $data_field_value= array(
              'field_value'=>$post['field_name'][$i],
              'field_id'=>$post['field_id'][$i],
              'type'=>5,
              'section_id'=>13,
              'p_mode_id'=>$post['payment_mode'],
              'branch_id'=>$users_data['parent_id'],
              'parent_id'=>$last_id,
              'ip_address'=>$_SERVER['REMOTE_ADDR']
              );
              $this->db->set('created_by',$users_data['id']);
              $this->db->set('created_date',date('Y-m-d H:i:s'));
              $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

          }
        }

        /*add sales banlk detail*/        
       }
       else if(isset($post['branch_id']) && !empty($post['branch_id']))
       { 
          $data = array(
                        'branch_id'=>$post['branch_id'],
                'section_id'=>'1',   
                'pay_mode'=>$post['pay_mode'],
               // 'bank_name'=>$bank_name,
               // 'cheque_no'=>$cheque_no,
                //'cheque_date'=>$cheque_date,
                //'transection_no'=>$transection_no,
                'debit'=>$post['pay_amount'],
                'status'=>1,
                'created_by'=>$users_data['id'],
                'created_date'=>date('Y-m-d H:i:s')   
                );
         $this->db->insert('hms_payment',$data); 
         $last_id= $this->db->insert_id();
      /*add sales banlk detail*/
        if(!empty($post['field_name']))
        {
        $post_field_value_name= $post['field_name'];
        $counter_name= count($post_field_value_name); 
          for($i=0;$i<$counter_name;$i++) 
          {
              $data_field_value= array(
              'field_value'=>$post['field_name'][$i],
              'field_id'=>$post['field_id'][$i],
              'type'=>5,
              'section_id'=>13,
              'p_mode_id'=>$post['payment_mode'],
              'branch_id'=>$users_data['parent_id'],
              'parent_id'=>$last_id,
              'ip_address'=>$_SERVER['REMOTE_ADDR']
              );
              $this->db->set('created_by',$users_data['id']);
              $this->db->set('created_date',date('Y-m-d H:i:s'));
              $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

          }
        }

        /*add sales banlk detail*/          
       }
    }
  }

  public function commission_doctor_list($branch_id="")
  {
    $this->db->select('hms_doctors.*');
    $this->db->where('branch_id',$branch_id); 
    $this->db->where('doctor_pay_type',1); 
    $this->db->where('is_deleted',0); 
    $this->db->order_by('doctor_name','ASC'); 
    $query = $this->db->get('hms_doctors');
    return $query->result();
  }

  public function total_doctor_comission()
  {

    $post = $this->input->post();
    if(!empty($post))
    {
      $debit_condition = "";
      $credit_condition = "";
      if(!empty($post['start_date']))
      {
              $debit_condition .=  ' AND hms_payment.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
              $credit_condition .=  ' AND hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"';
      }
      if(!empty($post['end_date']))
      {
              $debit_condition .=  ' AND hms_payment.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
              $credit_condition .=  ' AND hms_opd_booking.booking_date <= "'.date('Y-m-d', strtotime($post['end_date'])).'"';
      }
      //medicine
      $medi_debit_condition = "";
      $medi_credit_condition = "";
      if(!empty($post['start_date']))
      {
              $medi_debit_condition .=  ' AND hms_medicine_sale.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
              $medi_credit_condition .=  ' AND hms_medicine_sale.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
      }
      if(!empty($post['end_date']))
      {
              $medi_debit_condition .=  ' AND hms_medicine_sale.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
              $medi_credit_condition .=  ' AND hms_medicine_sale.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
      }
      //medicine medi_debit_condition

      $ipd_debit_condition = "";
      $ipd_credit_condition = "";
      if(!empty($post['start_date']))
      {
              $ipd_debit_condition .=  ' AND ipd_book1.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
              $ipd_credit_condition .=  ' AND ipd_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
      }
      if(!empty($post['end_date']))
      {
              $ipd_debit_condition .=  ' AND ipd_book1.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
              $ipd_credit_condition .=  ' AND ipd_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
      }


      //OT Booking //
      $ot_debit_condition = "";
      $ot_credit_condition = "";
      if(!empty($post['start_date']))
      {
              $ot_debit_condition .=  ' AND hms_operation_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
              $ot_credit_condition .=  ' AND hms_operation_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
      }
      if(!empty($post['end_date']))
      {
              $ot_debit_condition .=  ' AND hms_operation_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
              $ot_credit_condition .=  ' AND hms_operation_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
      }
      //OT Booking //


      /* Doctor connision */    

    $this->db->select("`hms_doctors`.`doctor_name`, (select sum(debit) from hms_payment where doctor_id = ".$post['doctor_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$debit_condition.") as total_debit, (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END) as total_credit from hms_opd_booking LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_opd_booking`.`referral_doctor` LEFT JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_opd_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 2 LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id` "); 
          
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_opd_booking.is_deleted','0');
       $this->db->where('hms_opd_booking.type',2);  
       
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_opd_booking.referral_doctor',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_doctors.id');  
      $query = $this->db->get();
      //echo $this->db->last_query();die; 
      $sql1 = $this->db->last_query();


      $this->db->select("`hms_doctors`.`doctor_name`, (select sum(debit) from hms_payment where doctor_id = ".$post['doctor_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$debit_condition.") as total_debit, 
      (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END) 

      as total_credit from hms_opd_booking 
              LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_opd_booking`.`referral_doctor`  
      LEFT JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_opd_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 3 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id` "); 
          
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_opd_booking.booking_status',1);
       $this->db->where('hms_opd_booking.is_deleted','0');
       $this->db->where('hms_opd_booking.type',3);  
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_opd_booking.referral_doctor',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_doctors.id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql2 = $this->db->last_query();

            // Medicine Comission /////

            $this->db->select("`hms_doctors`.`doctor_name`, (select sum(debit) from hms_payment where doctor_id = ".$post['doctor_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$medi_debit_condition.") as total_debit, 
      (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_medicine_sale.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END)  as total_credit from hms_medicine_sale 
              LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_medicine_sale`.`refered_id`  
      LEFT JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_medicine_sale`.`refered_id` AND `hms_doctors_to_comission`.`dept_id` = 4 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_medicine_sale`.`patient_id` "); 
          
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_medicine_sale.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_medicine_sale.refered_id',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->where('hms_medicine_sale.is_deleted','0');
      $this->db->group_by('hms_doctors.id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql3 = $this->db->last_query();

      //ipd
      $this->db->select("`hms_doctors`.`doctor_name`, (select sum(debit) from hms_payment join hms_ipd_booking as ipd_book1 on ipd_book1.id = hms_payment.parent_id where ipd_book1.discharge_status = 1 AND ipd_book1.is_deleted='0' AND hms_payment.doctor_id = ".$post['doctor_id']." AND hms_payment.branch_id = ".$post['branch_id']." AND hms_payment.section_id = 5  ".$ipd_debit_condition.") as total_debit,(CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN (((select sum(credit) from hms_payment as sub_pay join hms_ipd_booking as ipd_book on ipd_book.id = sub_pay.parent_id where ipd_book.is_deleted='0' AND ipd_book.discharge_status = 1 AND sub_pay.section_id = 5 AND sub_pay.doctor_id = ".$post['doctor_id']." AND sub_pay.branch_id = ".$post['branch_id']."  ".$ipd_credit_condition. ")/100)*hms_doctors_to_comission.rate) ELSE 0 END) as total_credit from hms_payment  LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_payment`.`doctor_id`  
      LEFT JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_payment`.`doctor_id` AND `hms_doctors_to_comission`.`dept_id` = 5 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_payment`.`patient_id` LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `hms_payment`.`parent_id`"); 
          
       $this->db->where('hms_doctors.doctor_pay_type',1);
       $this->db->where('hms_payment.section_id',5);
       $this->db->where('hms_payment.type!=4');
       $this->db->where('hms_ipd_booking.discharge_status','1');  
       $this->db->where('hms_ipd_booking.is_deleted','0');
        if(!empty($post['branch_id']))
      {
              $this->db->where('hms_payment.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_payment.doctor_id',$post['doctor_id']); 
      }
      if(!empty($post['start_date']) && $post['start_date']!=date('d-m-Y'))
      {
              $this->db->where('hms_ipd_booking.admission_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']) && $post['end_date']!=date('d-m-Y'))
      {
              $this->db->where('hms_ipd_booking.admission_date <="'.date('Y-m-d h:i:s', strtotime($post['end_date'])).'"'); 
      } 
      
      $this->db->group_by('hms_doctors.id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql4 = $this->db->last_query();
      //ipd

      //Pathology
        $path_debit_condition = "";
        $path_credit_condition = "";
        $profile_credit_condition = "";
        $mult_profile_credit_condition = "";
        if(!empty($post['start_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $path_credit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';

                $profile_credit_condition .=  ' AND test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $mult_profile_credit_condition .=  ' AND p_test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
        }
        if(!empty($post['end_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $path_credit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $profile_credit_condition .=  ' AND test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $mult_profile_credit_condition .=  ' AND p_test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
        }  
        $this->db->select("hms_doctors.doctor_name, (select sum(debit) from hms_payment where doctor_id = ".$post['doctor_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$path_debit_condition.") as total_debit, 
          (select 
          (CASE WHEN SUM(CASE 
          WHEN hms_doctors_to_comission.rate_type=0 THEN hms_doctors_to_comission.rate 
          WHEN hms_doctors_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_doctors_to_comission.rate ELSE 0 
          END)>0 THEN SUM(CASE 
          WHEN hms_doctors_to_comission.rate_type=0 THEN hms_doctors_to_comission.rate 
          WHEN hms_doctors_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_doctors_to_comission.rate ELSE 0 
          END) ELSE 0 END) as amount  from path_test_booking_to_test join path_test on path_test.id = path_test_booking_to_test.test_id JOIN hms_department on hms_department.id = path_test.dept_id  join hms_doctors_to_comission on hms_doctors_to_comission.dept_id = hms_department.id AND hms_doctors_to_comission.doctor_id = '".$post['doctor_id']."' where  path_test_booking_to_test.booking_id = path_test_booking.id ".$path_credit_condition." AND path_test_booking.is_deleted !=2 AND path_test_booking_to_test.test_type=0 AND path_test_booking_to_test.parent_status=1)
              +
              (select (CASE WHEN test_book.profile_id>0 THEN SUM(CASE WHEN  doc_comission.rate_type=0 THEN doc_comission.rate WHEN doc_comission.rate_type=1 THEN (test_book.profile_amount/100)*doc_comission.rate ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking as test_book join hms_doctors_to_comission as doc_comission on doc_comission.dept_id = '8' AND doc_comission.doctor_id = '".$post['doctor_id']."' where test_book.is_deleted !=2 AND test_book.id = path_test_booking.id ".$profile_credit_condition.")
              +
              (select (CASE WHEN path_test_booking_to_profile.test_booking_id>0 THEN SUM(CASE WHEN  p_doc_comission.rate_type=0 THEN p_doc_comission.rate WHEN p_doc_comission.rate_type=1 THEN (path_test_booking_to_profile.net_amount/100)*p_doc_comission.rate ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking_to_profile 
              join hms_doctors_to_comission as p_doc_comission on p_doc_comission.dept_id = '8'  AND p_doc_comission.doctor_id = '".$post['doctor_id']."' 
              left join path_test_booking as p_test_book on p_test_book.id = path_test_booking_to_profile.test_booking_id 
              where p_test_book.is_deleted !=2 AND path_test_booking_to_profile.test_booking_id = path_test_booking.id ".$mult_profile_credit_condition.")

               as total_credit"); 
             $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
             $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left'); 
         $this->db->where('hms_doctors.doctor_pay_type',1);   
        if(!empty($post['branch_id']))
        {
                $this->db->where('path_test_booking.branch_id',$post['branch_id']); 
        }
        if(!empty($post['doctor_id']))
        {
                $this->db->where('path_test_booking.referral_doctor',$post['doctor_id']); 
        }
        if(!empty($post['start_date']))
        {
                $this->db->where('path_test_booking.created_date >= "'.date('Y-m-d',strtotime($post['start_date'])).'  00:00:00"'); 
        }
        if(!empty($post['end_date']))
        {
                $this->db->where('path_test_booking.created_date <="'.date('Y-m-d',strtotime($post['end_date'])).' 23:59:59"'); 
        } 
         $this->db->order_by('path_test_booking.id','DESC');
        $query = $this->db->get('path_test_booking');
        $sql5 = $this->db->last_query();
        //echo $this->db->last_query();die;
      //Pathology
      //OT
        $this->db->select("`hms_doctors`.`doctor_name`, (select sum(debit) from hms_payment where doctor_id = ".$post['doctor_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$ot_debit_condition.") as total_debit, 
          (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_operation_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END)  as total_credit from hms_operation_booking 
          LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_operation_booking`.`referral_doctor`  
          LEFT JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_operation_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 6 
          LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_operation_booking`.`patient_id` "); 

          $this->db->where('hms_doctors.doctor_pay_type',1); 

          if(!empty($post['branch_id']))
          {
          $this->db->where('hms_operation_booking.branch_id',$post['branch_id']); 
          }
          if(!empty($post['doctor_id']))
          {
          $this->db->where('hms_operation_booking.referral_doctor',$post['doctor_id']); 
          }
          if(!empty($post['start_date']))
          {
          $this->db->where('hms_operation_booking.operation_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
          }
          if(!empty($post['end_date']))
          {
          $this->db->where('hms_operation_booking.operation_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
          } 
          $this->db->where('hms_operation_booking.is_deleted','0');
          $this->db->group_by('hms_doctors.id');  
          $query = $this->db->get();
          //echo $this->db->last_query(); exit;
          $sql6 = $this->db->last_query();
        //OT comission //


 



      $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.") UNION ALL (".$sql3.") UNION ALL (".$sql4.") UNION ALL (".$sql5.") UNION ALL (".$sql6.") "); 
      //echo $this->db->last_query();die; 

      return $sql->result_array();
    } 
  
  
  }

  public function pay_doctor_commission()
  {
      
      $users_data = $this->session->userdata('auth_users');
      $post = $this->input->post();
    //echo "<pre>"; print_r($post); exit;
    if(!empty($post))
    {
      //$bank_name = '';
      //$cheque_no = '';
      //$cheque_date = '';
      //$transection_no = '';
      // if(isset($post['bank_name']))
      // {
      //  $bank_name = $post['bank_name'];
      // }
      // if(isset($post['cheque_no']))
      // {
      //  $cheque_no = $post['cheque_no'];
      // }

      // if(isset($post['cheque_date']) && $post['cheque_date']!='00-00-0000')
      // {
      //  $cheque_date = $post['cheque_date'];
      // }
      
      // if(isset($post['transection_no']))
      // {
      //  $transection_no = $post['transection_no'];
      // }
        
          $data = array(
                        'branch_id'=>$post['branch_id'],
                'section_id'=>'2',  
                'doctor_id'=>$post['doctor_id'],
                'pay_mode'=>$post['pay_mode'],
                //'bank_name'=>$bank_name,
                //'cheque_no'=>$cheque_no,
                //'cheque_date'=>date('Y-m-d', strtotime($cheque_date)),
                //'transection_no'=>$transection_no,
                'debit'=>$post['pay_amount'],
                'status'=>1,
                'created_by'=>$users_data['id'],
                'created_date'=>date('Y-m-d H:i:s')   
                );
          $this->db->insert('hms_payment',$data);
                $last_id= $this->db->insert_id();
        /*add sales banlk detail*/
        if(!empty($post['field_name']))
        {
        $post_field_value_name= $post['field_name'];
        $counter_name= count($post_field_value_name); 
        for($i=0;$i<$counter_name;$i++) 
        {
            $data_field_value= array(
            'field_value'=>$post['field_name'][$i],
            'field_id'=>$post['field_id'][$i],
            'type'=>5,
            'section_id'=>12,
            'p_mode_id'=>$post['payment_mode'],
            'branch_id'=>$users_data['parent_id'],
            'parent_id'=>$last_id,
            'ip_address'=>$_SERVER['REMOTE_ADDR']
            );
            $this->db->set('created_by',$users_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

        }
        }

        /*add sales banlk detail*/     
    }
  }

  public function doctor_commission_details($post=array())
  {
     
        
      
    if(!empty($post))
    { 
      $credit_condition = "";
      if(!empty($post['start_date']))
      { 
              $credit_condition .=  ' AND hms_payment.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
      }
      if(!empty($post['end_date']))
      { 
              $credit_condition .=  ' AND hms_payment.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
      } 


      // OPD BOoking
      $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'OPD' ELSE ' ' END) as commission_type, `hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_opd_booking`.`booking_date`, (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END) as total_credit from hms_opd_booking LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_opd_booking`.`referral_doctor` LEFT JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_opd_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 2 LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id`"); 
           $this->db->where('hms_opd_booking.is_deleted',0);
       $this->db->where('hms_doctors.doctor_pay_type',1);  
       $this->db->where('hms_opd_booking.type',2);  
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_opd_booking.referral_doctor',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->order_by('hms_opd_booking.booking_date','DESC');
      $this->db->group_by('hms_opd_booking.patient_id');  
      $query = $this->db->get();
      //echo $this->db->last_query();die; 
      $sql1 = $this->db->last_query();

            // OPD Billing
      $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'OPD Billing' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_opd_booking`.`booking_date`, 
      (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END) 

      as total_credit from hms_opd_booking 
              LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_opd_booking`.`referral_doctor`  
      JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_opd_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 3 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id` "); 
          
       $this->db->where('hms_opd_booking.is_deleted','0'); 
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_opd_booking.type',3);  
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_opd_booking.referral_doctor',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'" '); 
      } 
      $this->db->group_by('hms_opd_booking.patient_id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql2 = $this->db->last_query();

            // Medicine Comission /////

            $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'Medicine' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_medicine_sale`.`sale_date` as `booking_date`,  
      (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_medicine_sale.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END)  as total_credit from hms_medicine_sale 
              LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_medicine_sale`.`refered_id`  
      JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_medicine_sale`.`refered_id` AND `hms_doctors_to_comission`.`dept_id` = 4 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_medicine_sale`.`patient_id`"); 
          
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_medicine_sale.is_deleted','0'); 
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_medicine_sale.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_medicine_sale.refered_id',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_medicine_sale.patient_id');  //hms_doctors.id
      $query = $this->db->get();
      $sql3 = $this->db->last_query();

      //ipd commission

    $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'IPD' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_ipd_booking`.`admission_date` as `booking_date`, 
    (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN (((select sum(credit) from hms_payment as sub_pay join hms_ipd_booking as ipd_book on ipd_book.id = sub_pay.parent_id where ipd_book.is_deleted='0' AND ipd_book.discharge_status = 1 AND sub_pay.section_id = 5 AND sub_pay.parent_id = hms_payment.parent_id )/100)*hms_doctors_to_comission.rate) ELSE 0 END) as total_credit from hms_payment
      LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_payment`.`doctor_id` 
      JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `hms_payment`.`parent_id`  
       JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_payment`.`doctor_id` AND `hms_doctors_to_comission`.`dept_id` = 5 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_payment`.`patient_id` "); 
          
       $this->db->where('hms_payment.section_id','5'); 
       $this->db->where('hms_payment.type!=4');
       $this->db->where('hms_doctors.is_deleted','0'); 
       $this->db->where('hms_doctors.doctor_pay_type',1);  
           $this->db->where('hms_ipd_booking.is_deleted','0'); 
           $this->db->where('hms_ipd_booking.discharge_status','1'); 
       // $this->db->group_by('hms_ipd_booking.id');  
      
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_payment.branch_id',$post['branch_id']); 
      }
      else
      {
        $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_payment.doctor_id',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_ipd_booking.discharge_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_ipd_booking.discharge_date <="'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"'); 
      } 
      $this->db->group_by('hms_payment.parent_id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql4 = $this->db->last_query();
      //ipd commision

      //pathology
        $path_credit_condition = "";
        $profile_credit_condition = "";
        $multi_profile_credit_condition = "";
        $path_debit_condition = '';
        $ot_debit_condition = '';
        $ot_credit_condition = '';
        if(!empty($post['start_date']))
        { 
                $path_credit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $profile_credit_condition .=  ' AND test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $multi_profile_credit_condition .=  ' AND p_test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
        }
        if(!empty($post['end_date']))
        { 
                $path_credit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $profile_credit_condition .=  ' AND test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $multi_profile_credit_condition .=  ' AND p_test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
        } 

        if(!empty($post['start_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
        }
        if(!empty($post['end_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
        }
                 
        $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'Pathology' ELSE ' ' END) as commission_type, hms_patient.patient_code, hms_patient.patient_name,  path_test_booking.booking_date, 
          (select (CASE WHEN SUM(CASE WHEN hms_doctors_to_comission.rate_type=0 THEN hms_doctors_to_comission.rate 
          WHEN hms_doctors_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_doctors_to_comission.rate ELSE 0 END)>0 THEN SUM(CASE WHEN hms_doctors_to_comission.rate_type=0 THEN hms_doctors_to_comission.rate 
          WHEN hms_doctors_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_doctors_to_comission.rate ELSE 0 END) ELSE 0 END) as total_credit  from path_test_booking_to_test join path_test on path_test.id = path_test_booking_to_test.test_id Join hms_department on hms_department.id = path_test.dept_id join hms_doctors_to_comission on hms_doctors_to_comission.dept_id = hms_department.id AND hms_doctors_to_comission.doctor_id = '".$post['doctor_id']."' where path_test_booking.is_deleted ='0' AND path_test_booking_to_test.parent_status=1 AND path_test_booking_to_test.test_type= 0 AND  path_test_booking_to_test.booking_id = path_test_booking.id ".$path_credit_condition.")
          +
          (select (CASE WHEN test_book.profile_id>0 THEN SUM(CASE WHEN  doc_comission.rate_type=0 THEN doc_comission.rate WHEN doc_comission.rate_type=1 THEN (test_book.net_amount/100)*doc_comission.rate ELSE 0 END) ELSE 0 END) AS total_credit  from path_test_booking as test_book join hms_doctors_to_comission as doc_comission on doc_comission.dept_id = '1' AND doc_comission.doctor_id = '".$post['doctor_id']."' where path_test_booking.is_deleted =0 AND test_book.id = path_test_booking.id ".$profile_credit_condition.")
          +
          (select (CASE WHEN path_test_booking_to_profile.test_booking_id>0 THEN SUM(CASE WHEN  p_doc_comission.rate_type=0 THEN p_doc_comission.rate WHEN p_doc_comission.rate_type=1 THEN (path_test_booking_to_profile.net_amount/100)*p_doc_comission.rate ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking_to_profile 
              join hms_doctors_to_comission as p_doc_comission on p_doc_comission.dept_id = '8'  AND p_doc_comission.doctor_id = '".$post['doctor_id']."' 
              left join path_test_booking as p_test_book on p_test_book.id = path_test_booking_to_profile.test_booking_id 
              where p_test_book.is_deleted ='0' AND path_test_booking_to_profile.test_booking_id = path_test_booking.id ".$multi_profile_credit_condition.")
           as total_credit"); 
             $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
             $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left'); 
         $this->db->where('hms_doctors.doctor_pay_type',1);   
         $this->db->where('path_test_booking.is_deleted','0'); 
        if(!empty($post['branch_id']))
        {
                $this->db->where('path_test_booking.branch_id',$post['branch_id']); 
        }
        if(!empty($post['doctor_id']))
        {
                $this->db->where('path_test_booking.referral_doctor',$post['doctor_id']); 
        }
        if(!empty($post['start_date']))
        {
                $this->db->where('path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"'); 
        }
        if(!empty($post['end_date']))
        {
                $this->db->where('path_test_booking.created_date <="'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"'); 
        } 
        $this->db->order_by('path_test_booking.id','DESC'); 
        $this->db->group_by('path_test_booking.id'); 
        $query = $this->db->get('path_test_booking');

        //echo $this->db->last_query(); exit;
        $sql5 = $this->db->last_query();


        //OT COMISSION //
         $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'OT' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_operation_booking`.`operation_date` as `booking_date`,  
      (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_operation_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END)  as total_credit from hms_operation_booking 
              LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_operation_booking`.`referral_doctor`  
      JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_operation_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 6
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_operation_booking`.`patient_id`"); 
          
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_operation_booking.ipd_id','0');
       $this->db->where('hms_operation_booking.is_deleted','0'); 
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_operation_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_operation_booking.referral_doctor',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_operation_booking.operation_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_operation_booking.operation_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_operation_booking.patient_id');  //hms_doctors.id
      $query = $this->db->get();
      $sql6 = $this->db->last_query();
        //OT COMISSION//



       
        
      //pathology
      $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.")  UNION ALL (".$sql3.") UNION ALL (".$sql4.") UNION ALL (".$sql5.") UNION ALL (".$sql6.")" ); 
      //echo $this->db->last_query(); exit;
      //hms_test.dept_id
      //echo $this->db->last_query();die;
      return $sql->result();
    }  
  
  }

  public function patient_to_balclearlist($id='')
  {
    $post = $this->input->post();
    $users_data = $this->session->userdata('auth_users');
    $result= array();
    $this->db->select('hms_patient.*,(sum(hms_payment.credit)-sum(hms_payment.debit)) as balance');
    //$this->db->where('(select sum(credit)-sum(debit) as total from hms_payment where patient_id=hms_payment.patient_id AND branch_id=hms_payment.branch_id ) > 0'); 
    $this->db->from('hms_patient');
    $this->db->join('hms_payment','hms_payment.patient_id=hms_patient.id');
    if(isset($post) || !empty($post))
    {
      if(!empty($post['patient_name']))
      {
        $this->db->like('hms_patient.patient_name',$post['patient_name']);
      }
      if(!empty($post['mobile_no']))
      {
        $this->db->like('hms_patient.mobile_no',$post['mobile_no']);
      }
      if(!empty($post['sub_branch_id']))
      {
        $this->db->where('(hms_patient.branch_id='.$post['sub_branch_id'].' and hms_payment.branch_id='.$post['sub_branch_id'].')');
        
      }
      else
      {
        $this->db->where('(hms_patient.branch_id='.$users_data['parent_id'].' and hms_payment.branch_id='.$users_data['parent_id'].')');
         
      }
      if(!empty($id))
      {
        $this->db->where('hms_payment.patient_id',$id);
      }
       
    }
    else
    {
      $this->db->where('(hms_patient.branch_id='.$users_data['parent_id'].' and hms_payment.branch_id='.$users_data['parent_id'].')');
    }

    $this->db->group_by('hms_patient.id'); 
    $this->db->order_by('hms_payment.id','DESC'); 
    $query = $this->db->get(); 
    $result = $query->result_array(); 
    //echo $this->db->last_query();die;
    return $result;

  }
  public function payment_to_branch($branch_id="")
  {
    $post = $this->input->post();
    $users_data = $this->session->userdata('auth_users');
    if(!empty($branch_id))
    {
           $branch_id = $branch_id;
    }
    else
    {
          $branch_id = $users_data['parent_id'];
    }
    if(isset($post) && !empty($post))
    {
      $data = array(
        'branch_id'=>$branch_id,
        'patient_id'=>$post['data_id'],
        'total_amount'=>$post['balance'],
        'net_amount'=>$post['balance'],
        'debit'=>$post['balance'],
        'pay_mode'=>$post['payment_mode'],
        'bank_name'=>$post['bank_name'],
        'cheque_no'=>$post['cheque_no'],
        'cheque_date'=>date('Y-m-d', strtotime($post['cheque_date'])),
        'transection_no'=>$post['transection_no'],
        'card_no'=>$post['card_no'],
        'created_by'=>$users_data['id'],
        'created_date'=>date('Y-m-d H:i:s'),
      );
       
      
      $this->db->insert('hms_payment',$data);
      $payment_id = $this->db->insert_id(); 
      return $payment_id;
    }

  }

  public function patient_balance_details($branch_id="",$patient_id="")
  {
       $result = [];
       if(!empty($branch_id) && !empty($patient_id))
       {
      $this->db->select('hms_payment.*');
      $this->db->where('hms_payment.parent_id','0'); 
      $this->db->where('hms_payment.branch_id',$branch_id); 
      $this->db->where('hms_payment.patient_id',$patient_id);  
      $this->db->order_by('hms_payment.id','DESC'); 
      $query = $this->db->get('hms_payment');
      //echo $this->db->last_query();
      return $query->result();
       }
  }

  public function patient_balance_receipt_data($id="")
  {
    if(!empty($id))
    { 
            $result_booking=array();
        $user_data = $this->session->userdata('auth_users');
      $this->db->select("hms_opd_booking.booking_code, hms_opd_booking.booking_date, hms_patient.*,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount"); 
      $this->db->join('hms_patient','hms_patient.id = hms_payment.patient_id','left');
      $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
      $this->db->join('hms_opd_booking','hms_opd_booking.patient_id = hms_patient.id','left');     
      $this->db->where('hms_payment.doctor_id','0'); 
      $this->db->where('hms_payment.parent_id','0'); 
      $this->db->where('hms_payment.id = "'.$id.'"'); 
      $this->db->order_by('hms_opd_booking.id','DESC');  
      $this->db->from('hms_payment');
      $result_booking['booking_list'] = $this->db->get()->result();
      $billing_particuler_arr = array('test_id'=>'','test_name'=>'Balance Clearance', 'amount'=>$result_booking['booking_list'][0]->debit);
      $object = (object) $billing_particuler_arr; 
      $result_booking['booking_list']['test_booking_list'][0] = $object;
      return $result_booking;
    } 
  }

  public function pathology_patient_balance_receipt_data($id="")
  {
    if(!empty($id))
    { 
            $result_booking=array();
        $user_data = $this->session->userdata('auth_users');
      $this->db->select("path_test_booking.lab_reg_no, path_test_booking.booking_date,  path_test_booking.attended_doctor,  path_test_booking.referral_doctor,  hms_patient.*,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.parent_id = hms_payment.parent_id) as balance, 
        (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount, hms_payment.pay_mode as payment_mode"); 
      $this->db->join('hms_patient','hms_patient.id = hms_payment.patient_id','left');
      $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
      $this->db->join('path_test_booking','path_test_booking.patient_id = hms_patient.id','left');     
      //$this->db->where('path_payment.doctor_id','0'); 
      //$this->db->where('path_payment.parent_id','0'); 
      $this->db->where('hms_payment.id = "'.$id.'"'); 
      $this->db->order_by('path_test_booking.id','DESC');  
      $this->db->from('hms_payment');
      $result_booking['booking_list'] = $this->db->get()->result();
      $billing_particuler_arr = array('test_id'=>'','test_name'=>'Balance Clearance', 'amount'=>$result_booking['booking_list'][0]->debit);
      $object = (object) $billing_particuler_arr; 
      $result_booking['booking_list']['test_booking_list'][0] = $object;
      return $result_booking;
    } 
  }


  public function commission_hospital_list($branch_id="")
  {
      $this->db->select('hms_hospital.*');
      $this->db->where('branch_id',$branch_id); 
      $this->db->where('is_deleted',0); 
      $this->db->order_by('hospital_name','ASC'); 
      $query = $this->db->get('hms_hospital');
      return $query->result();
  }



  public function total_hospital_comission()
  {

    $post = $this->input->post();
    if(!empty($post))
    {
      $debit_condition = "";
      $credit_condition = "";
      if(!empty($post['start_date']))
      {
              $debit_condition .=  ' AND hms_opd_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
              $credit_condition .=  ' AND hms_opd_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
      }
      if(!empty($post['end_date']))
      {
              $debit_condition .=  ' AND hms_opd_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
              $credit_condition .=  ' AND hms_opd_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
      }
      //medicine
      $medi_debit_condition = "";
      $medi_credit_condition = "";
      if(!empty($post['start_date']))
      {
              $medi_debit_condition .=  ' AND hms_medicine_sale.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
              $medi_credit_condition .=  ' AND hms_medicine_sale.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
      }
      if(!empty($post['end_date']))
      {
              $medi_debit_condition .=  ' AND hms_medicine_sale.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
              $medi_credit_condition .=  ' AND hms_medicine_sale.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
      }
      //medicine medi_debit_condition

      $ipd_debit_condition = "";
      $ipd_credit_condition = "";
      if(!empty($post['start_date']))
      {
              $ipd_debit_condition .=  ' AND ipd_book1.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
              $ipd_credit_condition .=  ' AND ipd_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
      }
      if(!empty($post['end_date']))
      {
              $ipd_debit_condition .=  ' AND ipd_book1.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
              $ipd_credit_condition .=  ' AND ipd_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
      }

      //OT COMISSION//
      $ot_debit_condition = "";
      $ot_credit_condition = "";
      if(!empty($post['start_date']))
      {
              $ot_debit_condition .=  ' AND hms_operation_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
              $ot_credit_condition .=  ' AND hms_operation_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"';
      }
      if(!empty($post['end_date']))
      {
              $ot_debit_condition .=  ' AND hms_operation_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
              $ot_credit_condition .=  ' AND hms_operation_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"';
      }
      //OT COMISSION //

 

      /* hospital connision */    

    $this->db->select("`hms_hospital`.`hospital_name`, (select sum(debit) from hms_payment where hospital_id = ".$post['hospital_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$debit_condition.") as total_debit, (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 END) as total_credit from hms_opd_booking LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_opd_booking`.`referral_hospital` LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_opd_booking`.`referral_hospital` AND `hms_hospital_to_comission`.`dept_id` = 2 LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id` "); 
          
       //$this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_opd_booking.booking_status',1);
       $this->db->where('hms_opd_booking.is_deleted','0');
       $this->db->where('hms_opd_booking.type',2);  
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_opd_booking.referral_hospital',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_hospital.id');  
      $query = $this->db->get();
      //echo $this->db->last_query();die; 
      $sql1 = $this->db->last_query();


      $this->db->select("`hms_hospital`.`hospital_name`, (select sum(debit) from hms_payment where hospital_id = ".$post['hospital_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$debit_condition.") as total_debit, 
      (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 END) 

      as total_credit from hms_opd_booking 
              LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_opd_booking`.`referral_hospital`  
      LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_opd_booking`.`referral_hospital` AND `hms_hospital_to_comission`.`dept_id` = 3 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id` "); 
          
       //$this->db->where('hms_hospital.doctor_pay_type',1); 
       $this->db->where('hms_opd_booking.booking_status',1);
       $this->db->where('hms_opd_booking.type',3);  
       $this->db->where('hms_opd_booking.is_deleted','0');
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_opd_booking.referral_hospital',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_hospital.id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql2 = $this->db->last_query();

            // Medicine Comission /////

            $this->db->select("`hms_hospital`.`hospital_name`, (select sum(debit) from hms_payment where hospital_id = ".$post['hospital_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$medi_debit_condition.") as total_debit, 
      (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN sum((hms_medicine_sale.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 END)  as total_credit from hms_medicine_sale 
              LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_medicine_sale`.`refered_id`  
      LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_medicine_sale`.`refered_id` AND `hms_hospital_to_comission`.`dept_id` = 4 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_medicine_sale`.`patient_id` "); 
          
       //$this->db->where('hms_hospital.doctor_pay_type',1); 
       $this->db->where('hms_medicine_sale.is_deleted','0');
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_medicine_sale.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_medicine_sale.refered_id',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_hospital.id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql3 = $this->db->last_query();

      //ipd
      $this->db->select("`hms_hospital`.`hospital_name`, (select sum(debit) from hms_payment join hms_ipd_booking as ipd_book1 on ipd_book1.id = hms_payment.parent_id where ipd_book1.discharge_status = 1 AND ipd_book1.is_deleted='0' AND hms_payment.hospital_id = ".$post['hospital_id']." AND hms_payment.branch_id = ".$post['branch_id']." AND hms_payment.section_id = 5  ".$ipd_debit_condition.") as total_debit,(CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN (((select sum(credit) from hms_payment as sub_pay join hms_ipd_booking as ipd_book on ipd_book.id = sub_pay.parent_id where ipd_book.is_deleted='0' AND ipd_book.discharge_status = 1 AND sub_pay.section_id = 5 AND sub_pay.hospital_id = ".$post['hospital_id']." AND sub_pay.branch_id = ".$post['branch_id']."  ".$ipd_credit_condition. ")/100)*hms_hospital_to_comission.rate) ELSE 0 END) as total_credit from hms_payment  LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_payment`.`hospital_id`  
      LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_payment`.`hospital_id` AND `hms_hospital_to_comission`.`dept_id` = 5 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_payment`.`patient_id` LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `hms_payment`.`parent_id`"); 
          
       //$this->db->where('hms_doctors.doctor_pay_type',1);  
       $this->db->where('hms_payment.section_id','5');
       $this->db->where('hms_payment.type!=4');
       $this->db->where('hms_ipd_booking.discharge_status','1');  
       $this->db->where('hms_ipd_booking.is_deleted','0'); 
        if(!empty($post['branch_id']))
      {
              $this->db->where('hms_payment.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_payment.hospital_id',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_ipd_booking.admission_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_ipd_booking.admission_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      //$this->db->where('hms_payment.section_id',5);
      $this->db->group_by('hms_hospital.id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql4 = $this->db->last_query();
      //ipd

      //Pathology
        /*$path_debit_condition = "";
        $path_credit_condition = "";
        $profile_credit_condition = "";
        $multi_profile_credit_condition = "";
        if(!empty($post['start_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $path_credit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';

                $profile_credit_condition .=  ' AND test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $multi_profile_credit_condition .=  ' AND p_test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
        }
        if(!empty($post['end_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $path_credit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $profile_credit_condition .=  ' AND test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $multi_profile_credit_condition .=  ' AND p_test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
        }  
        $this->db->select("hms_hospital.hospital_name, (select sum(debit) from hms_payment where hospital_id = ".$post['hospital_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$path_debit_condition.") as total_debit ,
          (select 
          (CASE 
          WHEN hms_hospital_to_comission.rate_type=0 THEN hms_hospital_to_comission.rate 
          WHEN hms_hospital_to_comission.rate_type=1 THEN sum((path_test_booking_to_test.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 
          END) as amount  from path_test_booking_to_test join path_test on path_test.id = path_test_booking_to_test.test_id JOIN hms_department on hms_department.id = path_test.dept_id left join hms_hospital_to_comission on hms_hospital_to_comission.dept_id = hms_department.id AND hms_hospital_to_comission.hospital_id = '".$post['hospital_id']."' where path_test_booking.is_deleted ='0' AND   path_test_booking_to_test.booking_id = path_test_booking.id ".$path_credit_condition."  AND path_test_booking_to_test.test_type=0 AND path_test_booking_to_test.parent_status=1)
              +
              (select (CASE WHEN test_book.profile_id>0 THEN (CASE WHEN  doc_comission.rate_type=0 THEN SUM(doc_comission.rate) WHEN doc_comission.rate_type=1 THEN sum((test_book.profile_amount/100)*doc_comission.rate) ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking as test_book join hms_hospital_to_comission as doc_comission on doc_comission.dept_id = '8' AND doc_comission.hospital_id = '".$post['hospital_id']."' where path_test_booking.is_deleted ='0' AND test_book.id = path_test_booking.id ".$profile_credit_condition.")
              +
              (select (CASE WHEN path_test_booking_to_profile.test_booking_id>0 THEN (CASE WHEN  p_doc_comission.rate_type=0 THEN SUM(p_doc_comission.rate) WHEN p_doc_comission.rate_type=1 THEN sum((path_test_booking_to_profile.master_price/100)*p_doc_comission.rate) ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking_to_profile 
              join hms_hospital_to_comission as p_doc_comission on p_doc_comission.dept_id = '8'  AND p_doc_comission.hospital_id = '".$post['hospital_id']."' 
              left join path_test_booking as p_test_book on p_test_book.id = path_test_booking_to_profile.test_booking_id 
              where p_test_book.is_deleted ='0' AND path_test_booking_to_profile.test_booking_id = path_test_booking.id ".$multi_profile_credit_condition.")

               as total_credit"); 
             $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
             $this->db->where('path_test_booking.is_deleted','0'); 
         //$this->db->where('hms_hospital.hospital_pay_type',1);   
        if(!empty($post['branch_id']))
        {
                $this->db->where('path_test_booking.branch_id',$post['branch_id']); 
        }
        if(!empty($post['hospital_id']))
        {
                $this->db->where('path_test_booking.referral_hospital',$post['hospital_id']); 
        }
        if(!empty($post['start_date']))
        {
                $this->db->where('path_test_booking.created_date >= "'.date('Y-m-d',strtotime($post['start_date'])).'  00:00:00"'); 
        }
        if(!empty($post['end_date']))
        {
                $this->db->where('path_test_booking.created_date <="'.date('Y-m-d',strtotime($post['end_date'])).' 23:59:59"'); 
        } 
         $this->db->order_by('path_test_booking.id','DESC');
         $this->db->group_by('path_test_booking.id'); 
        $query = $this->db->get('path_test_booking');
        $sql5 = $this->db->last_query();
        echo $this->db->last_query();die;*/

        $path_debit_condition = "";
        $path_credit_condition = "";
        $profile_credit_condition = "";
        $mult_profile_credit_condition = "";
        if(!empty($post['start_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $path_credit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';

                $profile_credit_condition .=  ' AND test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $mult_profile_credit_condition .=  ' AND p_test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
        }
        if(!empty($post['end_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $path_credit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $profile_credit_condition .=  ' AND test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $mult_profile_credit_condition .=  ' AND p_test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
        }  
        $this->db->select("hms_hospital.hospital_name, (select sum(debit) from hms_payment where hospital_id = ".$post['hospital_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$path_debit_condition.") as total_debit, 
          (select 
          (CASE WHEN SUM(CASE 
          WHEN hms_hospital_to_comission.rate_type=0 THEN hms_hospital_to_comission.rate 
          WHEN hms_hospital_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_hospital_to_comission.rate ELSE 0 
          END)>0 THEN SUM(CASE 
          WHEN hms_hospital_to_comission.rate_type=0 THEN hms_hospital_to_comission.rate 
          WHEN hms_hospital_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_hospital_to_comission.rate ELSE 0 
          END) ELSE 0 END) as amount  from path_test_booking_to_test join path_test on path_test.id = path_test_booking_to_test.test_id JOIN hms_department on hms_department.id = path_test.dept_id  join hms_hospital_to_comission on hms_hospital_to_comission.dept_id = hms_department.id AND hms_hospital_to_comission.hospital_id = '".$post['hospital_id']."' where  path_test_booking_to_test.booking_id = path_test_booking.id ".$path_credit_condition." AND path_test_booking.is_deleted ='0' AND path_test_booking_to_test.test_type=0 AND path_test_booking_to_test.parent_status=1)
              +
              (select (CASE WHEN test_book.profile_id>0 THEN SUM(CASE WHEN  doc_comission.rate_type=0 THEN doc_comission.rate WHEN doc_comission.rate_type=1 THEN (test_book.profile_amount/100)*doc_comission.rate ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking as test_book join hms_hospital_to_comission as doc_comission on doc_comission.dept_id = '8' AND doc_comission.hospital_id = '".$post['hospital_id']."' where test_book.is_deleted ='0' AND test_book.id = path_test_booking.id ".$profile_credit_condition.")
              +
              (select (CASE WHEN path_test_booking_to_profile.test_booking_id>0 THEN SUM(CASE WHEN  p_doc_comission.rate_type=0 THEN p_doc_comission.rate WHEN p_doc_comission.rate_type=1 THEN (path_test_booking_to_profile.net_amount/100)*p_doc_comission.rate ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking_to_profile 
              join hms_hospital_to_comission as p_doc_comission on p_doc_comission.dept_id = '8'  AND p_doc_comission.hospital_id = '".$post['hospital_id']."' 
              left join path_test_booking as p_test_book on p_test_book.id = path_test_booking_to_profile.test_booking_id 
              where p_test_book.is_deleted ='0' AND path_test_booking_to_profile.test_booking_id = path_test_booking.id ".$mult_profile_credit_condition.")

               as total_credit"); 
             $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');    
        if(!empty($post['branch_id']))
        {
                $this->db->where('path_test_booking.branch_id',$post['branch_id']); 
        }
        if(!empty($post['hospital_id']))
        {
                $this->db->where('path_test_booking.referral_hospital',$post['hospital_id']); 
        }
        if(!empty($post['start_date']))
        {
                $this->db->where('path_test_booking.created_date >= "'.date('Y-m-d',strtotime($post['start_date'])).'  00:00:00"'); 
        }
        if(!empty($post['end_date']))
        {
                $this->db->where('path_test_booking.created_date <="'.date('Y-m-d',strtotime($post['end_date'])).' 23:59:59"'); 
        } 
         $this->db->order_by('path_test_booking.id','DESC');
        $query = $this->db->get('path_test_booking');
        $sql5 = $this->db->last_query();
        //echo $this->db->last_query();die;
      //Pathology

        //OT COMISSION//

              $this->db->select("`hms_hospital`.`hospital_name`, (select sum(debit) from hms_payment where hospital_id = ".$post['hospital_id']." AND branch_id = ".$post['branch_id']." AND parent_id = 0 ".$ot_debit_condition.") as total_debit, 
        (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN sum((hms_operation_booking.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 END)  as total_credit from hms_operation_booking 
                LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_operation_booking`.`referral_hospital`  
        LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_operation_booking`.`referral_hospital` AND `hms_hospital_to_comission`.`dept_id` = 6
        LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_operation_booking`.`patient_id` "); 
            
         //$this->db->where('hms_hospital.doctor_pay_type',1); 
         
        if(!empty($post['branch_id']))
        {
                $this->db->where('hms_operation_booking.branch_id',$post['branch_id']); 
        }
        if(!empty($post['hospital_id']))
        {
                $this->db->where('hms_operation_booking.referral_hospital',$post['hospital_id']); 
        }
        if(!empty($post['start_date']))
        {
                $this->db->where('hms_operation_booking.operation_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
        }
        if(!empty($post['end_date']))
        {
                $this->db->where('hms_operation_booking.operation_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
        } 
        $this->db->group_by('hms_hospital.id');  
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        $sql6 = $this->db->last_query();
        //OT COMISSION //


         
         $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.") UNION ALL (".$sql3.") UNION ALL (".$sql4.") UNION ALL (".$sql5.") UNION ALL (".$sql6.")"); 
         //echo $this->db->last_query();die; 

      return $sql->result_array();
    } 
  
  
  }


  public function hospital_commission_details($post=array())
  {
       
    $users_data = $this->session->userdata('auth_users'); 
    if(!empty($post))
    { 
      $credit_condition = "";
      if(!empty($post['start_date']))
      { 
              $credit_condition .=  ' AND hms_payment.created_date >= "'.date('Y-m-d h:i:s', strtotime($post['start_date'])).'"';
      }
      if(!empty($post['end_date']))
      { 
              $credit_condition .=  ' AND hms_payment.created_date >= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
      } 
      
      $this->db->select("(CASE WHEN `hms_hospital`.`id`>0 THEN 'OPD' ELSE ' ' END) as commission_type, `hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_opd_booking`.`booking_date`,  (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 END) as total_credit from hms_opd_booking LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_opd_booking`.`referral_hospital` LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_opd_booking`.`referral_hospital` AND `hms_hospital_to_comission`.`dept_id` = 2 LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id`"); 
           $this->db->where('hms_hospital.is_deleted',0);  
       $this->db->where('hms_opd_booking.type',2);  
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_opd_booking.referral_hospital',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_opd_booking.patient_id');  
      $query = $this->db->get();
      //echo $this->db->last_query();die; 
      $sql1 = $this->db->last_query();


      $this->db->select("(CASE WHEN `hms_hospital`.`id`>0 THEN 'OPD Billing' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_opd_booking`.`booking_date`,  
      (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 END) 
      as total_credit from hms_opd_booking 
              LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_opd_booking`.`referral_hospital`  
      LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_opd_booking`.`referral_hospital` AND `hms_hospital_to_comission`.`dept_id` = 3 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id` "); 
          
       $this->db->where('hms_hospital.is_deleted',0); 
       //$this->db->where('hms_hospital.doctor_pay_type',1); 
       $this->db->where('hms_opd_booking.type',3);  
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_opd_booking.referral_hospital',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_opd_booking.patient_id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql2 = $this->db->last_query();

            // Medicine Comission /////

            $this->db->select("(CASE WHEN `hms_hospital`.`id`>0 THEN 'Medicine' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_medicine_sale`.`sale_date` as `booking_date`,  
      (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN sum((hms_medicine_sale.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 END)  as total_credit from hms_medicine_sale 
              LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_medicine_sale`.`refered_id`  
      LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_medicine_sale`.`refered_id` AND `hms_hospital_to_comission`.`dept_id` = 4 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_medicine_sale`.`patient_id`"); 
          
       //$this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_medicine_sale.is_deleted','0'); 
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_medicine_sale.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_medicine_sale.refered_id',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_medicine_sale.patient_id');  //hms_doctors.id
      $query = $this->db->get();
      $sql3 = $this->db->last_query();

      //ipd commission

    $this->db->select("(CASE WHEN `hms_hospital`.`id`>0 THEN 'IPD' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_ipd_booking`.`admission_date` as `booking_date`, 
    (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN (((select sum(credit) from hms_payment as sub_pay join hms_ipd_booking as ipd_book on ipd_book.id = sub_pay.parent_id where ipd_book.is_deleted='0' AND ipd_book.discharge_status = 1 AND sub_pay.section_id = 5 AND sub_pay.parent_id = hms_payment.parent_id )/100)*hms_hospital_to_comission.rate) ELSE 0 END) as total_credit from hms_payment
      LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_payment`.`hospital_id` 
      JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `hms_payment`.`parent_id`  
      LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_payment`.`hospital_id` AND `hms_hospital_to_comission`.`dept_id` = 5 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_payment`.`patient_id` "); 
          
       $this->db->where('hms_hospital.is_deleted',0); 
       $this->db->where('hms_payment.section_id','5');
       $this->db->where('hms_payment.type!=4');    
           $this->db->where('hms_ipd_booking.is_deleted','0'); 
           $this->db->where('hms_ipd_booking.discharge_status','1'); 
       //$this->db->where('hms_doctors.doctor_pay_type',1);  

       // $this->db->group_by('hms_ipd_booking.id');  
      
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_payment.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_payment.hospital_id',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_ipd_booking.admission_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_ipd_booking.admission_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      //$this->db->where('hms_payment.section_id',5);
      $this->db->group_by('hms_payment.parent_id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql4 = $this->db->last_query();
      //ipd commision

      //pathology
        $path_credit_condition = "";
        $profile_credit_condition = "";
        $multi_profile_credit_condition = "";
        if(!empty($post['start_date']))
        { 
                $path_credit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d H:i:s', strtotime($post['start_date'])).'"';
                $profile_credit_condition .=  ' AND test_book.created_date >= "'.date('Y-m-d H:i:s', strtotime($post['start_date'])).'"';
                $multi_profile_credit_condition .=  ' AND p_test_book.created_date >= "'.date('Y-m-d H:i:s', strtotime($post['start_date'])).'"';
        }
        if(!empty($post['end_date']))
        { 
                $path_credit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d H:i:s', strtotime($post['end_date'])).'"';
                $profile_credit_condition .=  ' AND test_book.created_date <= "'.date('Y-m-d H:i:s', strtotime($post['end_date'])).'"';
                $multi_profile_credit_condition .=  ' AND p_test_book.created_date <= "'.date('Y-m-d H:i:s', strtotime($post['end_date'])).'"';
        } 
        $this->db->select("(CASE WHEN `hms_hospital`.`id`>0 THEN 'Pathology' ELSE ' ' END) as commission_type, hms_patient.patient_code, hms_patient.patient_name,  path_test_booking.booking_date, 
          (select 
           (CASE WHEN SUM(CASE WHEN hms_hospital_to_comission.rate_type=0 THEN hms_hospital_to_comission.rate 
          WHEN hms_hospital_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_hospital_to_comission.rate ELSE 0 END)>0 THEN SUM(CASE WHEN hms_hospital_to_comission.rate_type=0 THEN hms_hospital_to_comission.rate 
          WHEN hms_hospital_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_hospital_to_comission.rate ELSE 0 END) ELSE 0 END) 
           as total_credit  from path_test_booking_to_test join path_test on path_test.id = path_test_booking_to_test.test_id Join hms_department on hms_department.id = path_test.dept_id join hms_hospital_to_comission on hms_hospital_to_comission.dept_id = hms_department.id AND hms_hospital_to_comission.hospital_id = '".$post['hospital_id']."' where path_test_booking.is_deleted ='0' AND path_test_booking_to_test.parent_status=1 AND path_test_booking_to_test.test_type= 0 AND  path_test_booking_to_test.booking_id = path_test_booking.id ".$path_credit_condition.")
          +
          (select (CASE WHEN test_book.profile_id>0 THEN SUM(CASE WHEN  doc_comission.rate_type=0 THEN doc_comission.rate WHEN doc_comission.rate_type=1 THEN (test_book.profile_amount/100)*doc_comission.rate ELSE 0 END) ELSE 0 END) AS total_credit  from path_test_booking as test_book join hms_hospital_to_comission as doc_comission on doc_comission.dept_id = '1' AND doc_comission.hospital_id = '".$post['hospital_id']."' where path_test_booking.is_deleted =0 AND test_book.id = path_test_booking.id ".$profile_credit_condition.")
          +
          (select (CASE WHEN path_test_booking_to_profile.test_booking_id>0 THEN SUM(CASE WHEN  p_doc_comission.rate_type=0 THEN p_doc_comission.rate WHEN p_doc_comission.rate_type=1 THEN (path_test_booking_to_profile.net_amount/100)*p_doc_comission.rate ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking_to_profile 
              join hms_hospital_to_comission as p_doc_comission on p_doc_comission.dept_id = '8'  AND p_doc_comission.hospital_id = '".$post['hospital_id']."' 
              left join path_test_booking as p_test_book on p_test_book.id = path_test_booking_to_profile.test_booking_id 
              where p_test_book.is_deleted ='0' AND path_test_booking_to_profile.test_booking_id = path_test_booking.id ".$multi_profile_credit_condition.")
           as total_credit");
             $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left');
             $this->db->where('path_test_booking.is_deleted','0');  
         //$this->db->where('hms_hospital.hospital_pay_type',1);   
        if(!empty($post['branch_id']))
        {
                $this->db->where('path_test_booking.branch_id',$post['branch_id']); 
        }
        if(!empty($post['hospital_id']))
        {
                $this->db->where('path_test_booking.referral_hospital',$post['hospital_id']); 
        }
        if(!empty($post['start_date']))
        {
                $this->db->where('path_test_booking.created_date >= "'.date('Y-m-d H:i:s', strtotime($post['start_date'])).'"'); 
        }
        if(!empty($post['end_date']))
        {
                $this->db->where('path_test_booking.created_date <="'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"'); 
        } 

        $this->db->order_by('path_test_booking.id','DESC'); 
        $this->db->group_by('path_test_booking.id'); 
        $query = $this->db->get('path_test_booking');

        //echo $this->db->last_query(); exit;
        $sql5 = $this->db->last_query();


        //OT HOSPITAL COMISSION//
         $this->db->select("(CASE WHEN `hms_hospital`.`id`>0 THEN 'OT' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_operation_booking`.`operation_date` as `booking_date`,  
      (CASE WHEN hms_hospital_to_comission.rate_type=0 THEN sum(hms_hospital_to_comission.rate) WHEN hms_hospital_to_comission.rate_type=1 THEN sum((hms_operation_booking.net_amount/100)*hms_hospital_to_comission.rate) ELSE 0 END)  as total_credit from hms_operation_booking 
              LEFT JOIN `hms_hospital` ON `hms_hospital`.`id` = `hms_operation_booking`.`referral_hospital`  
      LEFT JOIN `hms_hospital_to_comission` ON `hms_hospital_to_comission`.`hospital_id` = `hms_operation_booking`.`referral_hospital` AND `hms_hospital_to_comission`.`dept_id` = 6 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_operation_booking`.`patient_id`"); 
           $this->db->where('hms_operation_booking.ipd_id','0');
       $this->db->where('hms_operation_booking.is_deleted','0');
       //$this->db->where('hms_doctors.doctor_pay_type',1); 
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_operation_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['hospital_id']))
      {
              $this->db->where('hms_operation_booking.referral_hospital',$post['hospital_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_operation_booking.operation_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_operation_booking.operation_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_operation_booking.patient_id');  //hms_doctors.id
      $query = $this->db->get();
      $sql6 = $this->db->last_query();
      //echo $this->db->last_query();die;
        //OT HOSPITAL COMISSION//  
        
      //pathology
      $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.")  UNION ALL (".$sql3.") UNION ALL (".$sql4.") UNION ALL (".$sql5.") UNION ALL (".$sql6.")  "); 


      


      
       
      
      //hms_test.dept_id
      //echo $this->db->last_query();die;
      return $sql->result();
    }  
  
  }


  public function pay_hospital_commission()
  {
      
      $users_data = $this->session->userdata('auth_users');
      $post = $this->input->post();
    //echo "<pre>"; print_r($post); exit;
    if(!empty($post))
    {
      
        
          $data = array(
                        'branch_id'=>$post['branch_id'],
                'section_id'=>'2',  
                'hospital_id'=>$post['hospital_id'],
                'pay_mode'=>$post['pay_mode'],
                'debit'=>$post['pay_amount'],
                'status'=>1,
                'created_by'=>$users_data['id'],
                'created_date'=>date('Y-m-d H:i:s')   
                );
          $this->db->insert('hms_payment',$data);
                $last_id= $this->db->insert_id();
        /*add sales banlk detail*/
        if(!empty($post['field_name']))
        {
        $post_field_value_name= $post['field_name'];
        $counter_name= count($post_field_value_name); 
        for($i=0;$i<$counter_name;$i++) 
        {
            $data_field_value= array(
            'field_value'=>$post['field_name'][$i],
            'field_id'=>$post['field_id'][$i],
            'type'=>5,
            'section_id'=>15,
            'p_mode_id'=>$post['payment_mode'],
            'branch_id'=>$users_data['parent_id'],
            'parent_id'=>$last_id,
            'ip_address'=>$_SERVER['REMOTE_ADDR']
            );
            $this->db->set('created_by',$users_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

        }
        }

        /*add sales banlk detail*/     
    }
  }

  public function hospital_paid_comission_list($get=array())
  {
     $users_data = $this->session->userdata('auth_users'); 
     if(!empty($get))
     {
        $this->db->select("hms_payment.debit, hms_payment.pay_mode, hms_payment.created_date,hms_payment_mode.payment_mode");
          if(isset($get['hospital_id']) && !empty($get['hospital_id']))
          {
            $this->db->where('hms_payment.hospital_id',$get['hospital_id']); 
          } 

          if(isset($get['branch_id']) && !empty($get['branch_id']))
          {
            $this->db->where('hms_payment.branch_id',$get['branch_id']); 
          }
          else
          {
                $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
          } 
      $this->db->where('hms_payment.parent_id',0);  
      if(!empty($get['start_date']))
      {
      $this->db->where('hms_payment.created_date >= "'.date('Y-m-d', strtotime($get['start_date'])).' 00:00:00"');
      }
      
      if(!empty($get['end_date']))
      {
      $this->db->where('hms_payment.created_date <= "'.date('Y-m-d', strtotime($get['end_date'])).' 23:59:59"');
      }  
      $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode');
      $this->db->from('hms_payment');
      $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
     }
  }
  
  public function total_hospital_debit()
	{ 
		$post = $this->input->post();
		$this->db->select('sum(hms_payment.debit) as total_debit');
		$this->db->where('hms_payment.hospital_id',$post['hospital_id']);
		if(!empty($post['start_date']))
		{
		  $this->db->where('hms_payment.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"');	 
		}
		if(!empty($post['end_date']))
		{
		  $this->db->where('hms_payment.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"');
		}
		$this->db->where('hms_payment.parent_id','0');
		$this->db->where('hms_payment.branch_id',$post['branch_id']);
		$query = $this->db->get('hms_payment'); 
		//echo $this->db->last_query();die;
		return $query->result();

	}
	
	public function total_doctor_debit()
	{ 
		$post = $this->input->post();
		$this->db->select('sum(hms_payment.debit) as total_debit');
		$this->db->where('hms_payment.doctor_id',$post['doctor_id']);
		if(!empty($post['start_date']))
		{
		  $this->db->where('hms_payment.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00'.'"');	 
		}
		if(!empty($post['end_date']))
		{
		  $this->db->where('hms_payment.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59'.'"');
		}
		$this->db->where('hms_payment.parent_id','0');
		$this->db->where('hms_payment.branch_id',$post['branch_id']);
		$query = $this->db->get('hms_payment'); 
		//echo $this->db->last_query();die;
		return $query->result();

	}


  public function doctor_commission($post=array())
  {

      
    if(!empty($post))
    { 
      $credit_condition = "";
      if(!empty($post['start_date']))
      { 
              $credit_condition .=  ' AND hms_payment.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
      }
      if(!empty($post['end_date']))
      { 
              $credit_condition .=  ' AND hms_payment.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
      } 


      // OPD BOoking
      $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'OPD' ELSE ' ' END) as commission_type, `hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_opd_booking`.`booking_date`, (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END) as total_credit from hms_opd_booking LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_opd_booking`.`referral_doctor` LEFT JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_opd_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 2 LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id`"); 
           $this->db->where('hms_opd_booking.is_deleted',0);
       $this->db->where('hms_doctors.doctor_pay_type',1);  
       $this->db->where('hms_opd_booking.type',2);  
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_opd_booking.referral_doctor',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->order_by('hms_opd_booking.booking_date','DESC');
      $this->db->group_by('hms_opd_booking.patient_id');  
      $query = $this->db->get();
      //echo $this->db->last_query();die; 
      $sql1 = $this->db->last_query();

            // OPD Billing
      $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'OPD Billing' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_opd_booking`.`booking_date`, 
      (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_opd_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END) 

      as total_credit from hms_opd_booking 
              LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_opd_booking`.`referral_doctor`  
      JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_opd_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 3 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_opd_booking`.`patient_id` "); 
          
       $this->db->where('hms_opd_booking.is_deleted','0'); 
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_opd_booking.type',3);  
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_opd_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_opd_booking.referral_doctor',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_opd_booking.booking_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_opd_booking.booking_date <="'.date('Y-m-d', strtotime($post['end_date'])).'" '); 
      } 
      $this->db->group_by('hms_opd_booking.patient_id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql2 = $this->db->last_query();

            // Medicine Comission /////

            $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'Medicine' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_medicine_sale`.`sale_date` as `booking_date`,  
      (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_medicine_sale.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END)  as total_credit from hms_medicine_sale 
              LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_medicine_sale`.`refered_id`  
      JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_medicine_sale`.`refered_id` AND `hms_doctors_to_comission`.`dept_id` = 4 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_medicine_sale`.`patient_id`"); 
          
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_medicine_sale.is_deleted','0'); 
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_medicine_sale.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_medicine_sale.refered_id',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_medicine_sale.sale_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_medicine_sale.patient_id');  //hms_doctors.id
      $query = $this->db->get();
      $sql3 = $this->db->last_query();

      //ipd commission

    $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'IPD' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_ipd_booking`.`admission_date` as `booking_date`, 
    (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN (((select sum(credit) from hms_payment as sub_pay join hms_ipd_booking as ipd_book on ipd_book.id = sub_pay.parent_id where ipd_book.is_deleted='0' AND ipd_book.discharge_status = 1 AND sub_pay.section_id = 5 AND sub_pay.parent_id = hms_payment.parent_id )/100)*hms_doctors_to_comission.rate) ELSE 0 END) as total_credit from hms_payment
      LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_payment`.`doctor_id` 
      JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `hms_payment`.`parent_id`  
       JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_payment`.`doctor_id` AND `hms_doctors_to_comission`.`dept_id` = 5 
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_payment`.`patient_id` "); 
          
       $this->db->where('hms_payment.section_id','5'); 
       $this->db->where('hms_payment.type!=4');
       $this->db->where('hms_doctors.is_deleted','0'); 
       $this->db->where('hms_doctors.doctor_pay_type',1);  
           $this->db->where('hms_ipd_booking.is_deleted','0'); 
           $this->db->where('hms_ipd_booking.discharge_status','1'); 
       // $this->db->group_by('hms_ipd_booking.id');  
      
       
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_payment.branch_id',$post['branch_id']); 
      }
      else
      {
        $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_payment.doctor_id',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_ipd_booking.discharge_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_ipd_booking.discharge_date <="'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"'); 
      } 
      $this->db->group_by('hms_payment.parent_id');  
      $query = $this->db->get();
      //echo $this->db->last_query(); exit;
      $sql4 = $this->db->last_query();
      //ipd commision

      //pathology
        $path_credit_condition = "";
        $profile_credit_condition = "";
        $multi_profile_credit_condition = "";
        $path_debit_condition = '';
        $ot_debit_condition = '';
        $ot_credit_condition = '';
        if(!empty($post['start_date']))
        { 
                $path_credit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $profile_credit_condition .=  ' AND test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
                $multi_profile_credit_condition .=  ' AND p_test_book.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
        }
        if(!empty($post['end_date']))
        { 
                $path_credit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $profile_credit_condition .=  ' AND test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
                $multi_profile_credit_condition .=  ' AND p_test_book.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
        } 

        if(!empty($post['start_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"';
        }
        if(!empty($post['end_date']))
        {
                $path_debit_condition .=  ' AND path_test_booking.created_date <= "'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"';
        }
                 
        $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'Pathology' ELSE ' ' END) as commission_type, hms_patient.patient_code, hms_patient.patient_name,  path_test_booking.booking_date, 
          (select (CASE WHEN SUM(CASE WHEN hms_doctors_to_comission.rate_type=0 THEN hms_doctors_to_comission.rate 
          WHEN hms_doctors_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_doctors_to_comission.rate ELSE 0 END)>0 THEN SUM(CASE WHEN hms_doctors_to_comission.rate_type=0 THEN hms_doctors_to_comission.rate 
          WHEN hms_doctors_to_comission.rate_type=1 THEN (path_test_booking_to_test.net_amount/100)*hms_doctors_to_comission.rate ELSE 0 END) ELSE 0 END) as total_credit  from path_test_booking_to_test join path_test on path_test.id = path_test_booking_to_test.test_id Join hms_department on hms_department.id = path_test.dept_id join hms_doctors_to_comission on hms_doctors_to_comission.dept_id = hms_department.id AND hms_doctors_to_comission.doctor_id = '".$post['doctor_id']."' where path_test_booking.is_deleted ='0' AND path_test_booking_to_test.parent_status=1 AND path_test_booking_to_test.test_type= 0 AND  path_test_booking_to_test.booking_id = path_test_booking.id ".$path_credit_condition.")
          +
          (select (CASE WHEN test_book.profile_id>0 THEN SUM(CASE WHEN  doc_comission.rate_type=0 THEN doc_comission.rate WHEN doc_comission.rate_type=1 THEN (test_book.net_amount/100)*doc_comission.rate ELSE 0 END) ELSE 0 END) AS total_credit  from path_test_booking as test_book join hms_doctors_to_comission as doc_comission on doc_comission.dept_id = '1' AND doc_comission.doctor_id = '".$post['doctor_id']."' where path_test_booking.is_deleted =0 AND test_book.id = path_test_booking.id ".$profile_credit_condition.")
          +
          (select (CASE WHEN path_test_booking_to_profile.test_booking_id>0 THEN SUM(CASE WHEN  p_doc_comission.rate_type=0 THEN p_doc_comission.rate WHEN p_doc_comission.rate_type=1 THEN (path_test_booking_to_profile.net_amount/100)*p_doc_comission.rate ELSE 0 END) ELSE 0 END) AS amount  from path_test_booking_to_profile 
              join hms_doctors_to_comission as p_doc_comission on p_doc_comission.dept_id = '8'  AND p_doc_comission.doctor_id = '".$post['doctor_id']."' 
              left join path_test_booking as p_test_book on p_test_book.id = path_test_booking_to_profile.test_booking_id 
              where p_test_book.is_deleted ='0' AND path_test_booking_to_profile.test_booking_id = path_test_booking.id ".$multi_profile_credit_condition.")
           as total_credit"); 
             $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
             $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id','left'); 
         $this->db->where('hms_doctors.doctor_pay_type',1);   
         $this->db->where('path_test_booking.is_deleted','0'); 
        if(!empty($post['branch_id']))
        {
                $this->db->where('path_test_booking.branch_id',$post['branch_id']); 
        }
        if(!empty($post['doctor_id']))
        {
                $this->db->where('path_test_booking.referral_doctor',$post['doctor_id']); 
        }
        if(!empty($post['start_date']))
        {
                $this->db->where('path_test_booking.created_date >= "'.date('Y-m-d', strtotime($post['start_date'])).' 00:00:00"'); 
        }
        if(!empty($post['end_date']))
        {
                $this->db->where('path_test_booking.created_date <="'.date('Y-m-d', strtotime($post['end_date'])).' 23:59:59"'); 
        } 
        $this->db->order_by('path_test_booking.id','DESC'); 
        $this->db->group_by('path_test_booking.id'); 
        $query = $this->db->get('path_test_booking');

        //echo $this->db->last_query(); exit;
        $sql5 = $this->db->last_query();


        //OT COMISSION //
         $this->db->select("(CASE WHEN `hms_doctors`.`id`>0 THEN 'OT' ELSE '' END) as commission_type, hms_patient`.`patient_code`, `hms_patient`.`patient_name`,`hms_operation_booking`.`operation_date` as `booking_date`,  
      (CASE WHEN hms_doctors_to_comission.rate_type=0 THEN sum(hms_doctors_to_comission.rate) WHEN hms_doctors_to_comission.rate_type=1 THEN sum((hms_operation_booking.net_amount/100)*hms_doctors_to_comission.rate) ELSE 0 END)  as total_credit from hms_operation_booking 
              LEFT JOIN `hms_doctors` ON `hms_doctors`.`id` = `hms_operation_booking`.`referral_doctor`  
      JOIN `hms_doctors_to_comission` ON `hms_doctors_to_comission`.`doctor_id` = `hms_operation_booking`.`referral_doctor` AND `hms_doctors_to_comission`.`dept_id` = 6
      LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_operation_booking`.`patient_id`"); 
          
       $this->db->where('hms_doctors.doctor_pay_type',1); 
       $this->db->where('hms_operation_booking.ipd_id','0');
       $this->db->where('hms_operation_booking.is_deleted','0'); 
      if(!empty($post['branch_id']))
      {
              $this->db->where('hms_operation_booking.branch_id',$post['branch_id']); 
      }
      if(!empty($post['doctor_id']))
      {
              $this->db->where('hms_operation_booking.referral_doctor',$post['doctor_id']); 
      }
      if(!empty($post['start_date']))
      {
              $this->db->where('hms_operation_booking.operation_date >= "'.date('Y-m-d', strtotime($post['start_date'])).'"'); 
      }
      if(!empty($post['end_date']))
      {
              $this->db->where('hms_operation_booking.operation_date <="'.date('Y-m-d', strtotime($post['end_date'])).'"'); 
      } 
      $this->db->group_by('hms_operation_booking.patient_id');  //hms_doctors.id
      $query = $this->db->get();
      $sql6 = $this->db->last_query();
        //OT COMISSION//



       
        
      //pathology
      $sql =  $this->db->query("(".$sql1.") UNION ALL (".$sql2.")  UNION ALL (".$sql3.") UNION ALL (".$sql4.") UNION ALL (".$sql5.") UNION ALL (".$sql6.")" ); 
      //echo $this->db->last_query(); exit;
      //hms_test.dept_id
      //echo $this->db->last_query();die;
      return $sql->result();
    }  
  
  
  }

  /* Letter head Discharge Bill */
     function letterhead_template_format($branch_id){
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_billing_doctor_commission_letterhead_setting.*');
     
      $this->db->where('hms_billing_doctor_commission_letterhead_setting.branch_id',$branch_id); 
      $this->db->from('hms_billing_doctor_commission_letterhead_setting');
      $query=$this->db->get()->row();
      //print_r($query);exit;
      return $query;

    }
  /* letetr head dischareg bill */
  

}
?>