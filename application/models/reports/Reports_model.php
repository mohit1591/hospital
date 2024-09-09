<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

  var $table = 'hms_opd_booking';
  var $column = array('hms_opd_booking.lab_reg_no','hms_opd_booking.booking_date', 'hms_patient.patient_name','hms_doctors.doctor_name','hms_opd_booking.total_amount','hms_opd_booking.discount','hms_opd_booking.net_amount','hms_opd_booking.paid_amount','hms_opd_booking.balance');  
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
    $this->db->select("hms_opd_booking.*, hms_doctors.doctor_name, hms_patient.patient_name"); 
    $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id');
    //$this->db->join('hms_department','hms_department.id = hms_opd_booking.dept_id');
    $this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.attended_doctor','left');

    $this->db->from($this->table);  
        $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
        if(isset($search_data) && !empty($search_data))
        {
          if(isset($search_data['start_date']) && !empty($search_data['start_date'])
            )
          {
            $start_date = date('Y-m-d',strtotime($search_data['start_date']));
            $this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
          }

          if(isset($search_data['end_date']) && !empty($search_data['end_date'])
            )
          {
            $end_date = date('Y-m-d',strtotime($search_data['end_date']));
            $this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
          }

          /*if(isset($search_data['dept_id']) && !empty($search_data['dept_id'])
            )
          { 
            $this->db->where('hms_opd_booking.booking_date = "'.$search_data["dept_id"].'"');
          }
*/
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
            $this->db->where('hms_opd_booking.profile_id = "'.$search_data["profile_id"].'"');
          }
      
      if(isset($search_data['sample_collected_by']) && !empty($search_data['sample_collected_by'])
            )
          { 
            $this->db->where('hms_opd_booking.sample_collected_by = "'.$search_data["sample_collected_by"].'"');
          }
      
      if(isset($search_data['staff_refrenace_id']) && !empty($search_data['staff_refrenace_id'])
            )
          { 
            $this->db->where('hms_opd_booking.staff_refrenace_id = "'.$search_data["staff_refrenace_id"].'"');
          }
      
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
    $search_data = $this->session->userdata('search_data'); 
    $this->db->select("hms_opd_booking.*, hms_doctors.doctor_name, hms_patient.patient_name"); 
    $this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id');
    //$this->db->join('hms_department','hms_department.id = hms_opd_booking.dept_id');
    $this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.attended_doctor','left');

    $this->db->from($this->table);  
        $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
        if(isset($search_data) && !empty($search_data))
        {
          if(isset($search_data['start_date']) && !empty($search_data['start_date'])
            )
          {
            $start_date = date('Y-m-d',strtotime($search_data['start_date']));
            $this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
          }

          if(isset($search_data['end_date']) && !empty($search_data['end_date'])
            )
          {
            $end_date = date('Y-m-d',strtotime($search_data['end_date']));
            $this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
          }

          /*if(isset($search_data['dept_id']) && !empty($search_data['dept_id'])
            )
          { 
            $this->db->where('hms_opd_booking.booking_date = "'.$search_data["dept_id"].'"');
          }*/

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
    
    public function religion_list()
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('*');
      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->where('status',1); 
      $this->db->where('is_deleted',0); 
      $this->db->order_by('religion','ASC'); 
      $query = $this->db->get('hms_religion');
    return $query->result();
    }

  public function get_by_id($id)
  {
    $this->db->select('hms_religion.*');
    $this->db->from('hms_religion'); 
    $this->db->where('hms_religion.id',$id);
    $this->db->where('hms_religion.is_deleted','0');
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
      $this->db->update('hms_religion',$data);  
    }
    else{    
      $this->db->set('created_by',$user_data['id']);
      $this->db->set('created_date',date('Y-m-d H:i:s'));
      $this->db->insert('hms_religion',$data);               
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
      $this->db->update('hms_religion');
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
      $this->db->update('hms_religion');
      //echo $this->db->last_query();die;
      } 
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
            
            $this->db->select("hms_expenses.type as new_type,(CASE WHEN hms_expenses.type=0 THEN 'Expenses'  WHEN hms_expenses.type=4 THEN 'Inventory Purchase' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Vendor Payment' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=12 THEN 'Blood Bank Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' WHEN hms_expenses.type=14 THEN 'OPD Billing' WHEN hms_expenses.type=15 THEN 'Day Care Refund' END) as expenses_type,(CASE WHEN hms_expenses.type=0 THEN hms_expenses.vouchar_no WHEN hms_expenses.type=2 THEN (select purchase_id from  hms_medicine_purchase where id=hms_expenses.parent_id) ELSE 'N/A' END) as vouchar_no, (CASE  WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Medicine Sale Return' WHEN hms_expenses.type=5 THEN  'Vaccine Purchase' WHEN hms_expenses.type=6 THEN  'Vaccine Billing Return' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=11 THEN 'OT Refund' WHEN hms_expenses.type=12 THEN 'Blood Bank Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' WHEN hms_expenses.type=14 THEN 'OPD Billing' WHEN hms_expenses.type=15 THEN 'Day Care Refund' ELSE 'N/A' END) as type, hms_expenses.paid_amount, (CASE WHEN hms_expenses.type=0 THEN hms_expenses_category.exp_category ELSE 'N/A' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(CASE WHEN hms_expenses.type=0 THEN 'Expenses' 

WHEN hms_expenses.type=7 THEN (SELECT `hms_opd_booking`.`booking_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id` AND section_id=2 WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 7) 

WHEN hms_expenses.type=8 THEN (SELECT `path_test_booking`.`lab_reg_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 8) 
		
WHEN hms_expenses.type=9 THEN  (SELECT `hms_medicine_sale`.`sale_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 9)

WHEN hms_expenses.type=10 THEN (SELECT `hms_ipd_booking`.`ipd_no` FROM `hms_expenses` as exp LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 10)


WHEN hms_expenses.type=11 THEN (SELECT `hms_ot_booking_ot_details`.`code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ot_booking_ot_details` ON `hms_ot_booking_ot_details`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 11)

WHEN hms_expenses.type=13 THEN (SELECT `hms_ambulance_booking`.`booking_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ambulance_booking` ON `hms_ambulance_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 13)

WHEN hms_expenses.type=14 THEN (SELECT `hms_opd_booking`.`reciept_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 14)

WHEN hms_expenses.type=2 THEN (SELECT `hms_medicine_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_medicine_purchase` ON `hms_medicine_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 2)

WHEN hms_expenses.type=5 THEN (SELECT `hms_vaccination_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_vaccination_purchase` ON `hms_vaccination_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 5)


		END) as book_code,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,hms_employees.name as emp_names,hms_medicine_vendors.name as paid_to_name");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->join('hms_employees','hms_employees.id=hms_expenses.employee_id','left');
            $this->db->where('hms_expenses.paid_amount>0');
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
            $this->db->order_by('hms_expenses.expenses_date','DESC');
            $query = $this->db->get();
            //echo $this->db->last_query(); exit;
            //$result = $query->result();
            $result_expense['expense_list'] = $query->result(); 

            $this->db->select("(CASE WHEN hms_expenses.type=0 THEN hms_expenses.vouchar_no WHEN hms_expenses.type=2 THEN (select purchase_id from  hms_medicine_purchase where id=hms_expenses.parent_id) ELSE 'N/A' END) as vouchar_no, (CASE WHEN hms_expenses.type=0 THEN 'expenses' WHEN hms_expenses.type=3 THEN 'Sale Return' WHEN hms_expenses.type=1 THEN 'Emp. Salary' WHEN hms_expenses.type=2 THEN 'Purchase Medicine' ELSE 'N/A' END) as type, hms_expenses.paid_amount, (CASE WHEN hms_expenses.type=0 THEN hms_expenses_category.exp_category ELSE 'N/A' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount"); 
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
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
    public function get_expenses_details_old($get=array())
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
           // $this->db->select("(CASE WHEN hms_expenses.type=0 THEN hms_expenses.vouchar_no WHEN hms_expenses.type=2 THEN (select purchase_id from  hms_medicine_purchase where id=hms_expenses.parent_id) ELSE '' END) as vouchar_no, (CASE WHEN hms_expenses.type=0 THEN 'expenses' WHEN hms_expenses.type=3 THEN 'Sale Return' WHEN hms_expenses.type=1 THEN 'Emp. Salary' WHEN hms_expenses.type=2 THEN 'Purchase Medicine' ELSE '' END) as type, hms_expenses.paid_amount,hms_expenses_category.exp_category, hms_expenses.created_date,hms_expenses.expenses_date"); 
            $this->db->select("(CASE WHEN hms_expenses.type=0 THEN hms_expenses.vouchar_no WHEN hms_expenses.type=2 THEN (select purchase_id from  hms_medicine_purchase where id=hms_expenses.parent_id) ELSE 'N/A' END) as vouchar_no, (CASE WHEN hms_expenses.type=0 THEN 'expenses' WHEN hms_expenses.type=3 THEN 'Sale Return' WHEN hms_expenses.type=1 THEN 'Emp. Salary' WHEN hms_expenses.type=2 THEN 'Purchase Medicine' ELSE 'N/A' END) as type, hms_expenses.paid_amount, (CASE WHEN hms_expenses.type=0 THEN hms_expenses_category.exp_category ELSE 'N/A' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
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
            $query = $this->db->get();
            //echo $this->db->last_query(); exit;
            $result = $query->result();  
            return $result;
        } 
    }

    public function branch_opd_collection_list($get="",$ids=[])
    {
        $new_payment_mode=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_opd_booking.booking_date,hms_opd_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");  

            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (1,8)','left');
*/
           
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
             
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
            $new_payment_mode['opd_collection_list']= $this->db->get()->result(); 

            /* code from payment_mode by */

             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name, sum(hms_payment.debit) as to_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (1,8)','left');*/
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
             
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

            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (2)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->group_by('hms_payment.pay_mode'); 
            $this->db->from('hms_payment');
            $new_payment_mode['payment_mode_list']= $this->db->get()->result(); 
            /* code from payment_mode by */

           //print '<pre>'; print_r($new_payment_mode);die;
           //echo $this->db->last_query(); exit; 
            return $new_payment_mode;
            
        } 
    }

    public function branch_billing_collection_list($get="",$ids=[])
    {
        $new_billing_array=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_payment.id as p_id,hms_branch.branch_name,hms_branch.id as branch_id,hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_opd_booking.booking_date,hms_opd_booking.reciept_code as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");

            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');

             /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (2,12)','left');*/
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
            $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($get['start_date'])).'"');
            }

            if(!empty($get['end_date']))
            {
            $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($get['end_date'])).'"');
            }
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (4)');
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
            $new_billing_array['billing_array'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

            /* billing payment mode array */

             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_payment.id as p_id,hms_branch.branch_name,hms_branch.id as branch_id,hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');

            /* $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (2,12)','left');*/
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
            $this->db->where('hms_payment.created_date >= "'.date('Y-m-d',strtotime($get['start_date'])).'"');
            }

            if(!empty($get['end_date']))
            {
            $this->db->where('hms_payment.created_date <= "'.date('Y-m-d',strtotime($get['end_date'])).'"');
            }
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (4)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->group_by('hms_payment.pay_mode'); 
            $this->db->from('hms_payment');
            $new_billing_array['billing_payment_mode_array'] = $this->db->get()->result(); 

            /* billing payment mode array */
           // print '<pre>'; print_r($new_billing_array);die;

            return $new_billing_array;
            
        } 
    }

    public function branch_medicine_collection_list($get="",$ids=[])
    {
        $new_medicine_array=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id,hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_medicine_sale.sale_date as booking_date,hms_medicine_sale.sale_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale.refered_id=0 THEN concat('Other ',hms_medicine_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");

            
            
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id AND hms_medicine_sale.is_deleted=0');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');
                /* $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (6,10)','left');*/

            // $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_medicine_sale.id AND hms_branch_hospital_no.section_id IN(6,10)','left');

            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');

            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_medicine_sale.branch_id','0'); 
            $this->db->where('hms_medicine_sale.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
            $this->db->where('hms_payment.section_id IN (3)'); 
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
            $new_medicine_array['medicine_collection_list'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 


            /* medicine sale collection list */

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id,hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale.refered_id=0 THEN concat('Other ',hms_medicine_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            //(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');
                 /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (6,10)','left');
*/
            // $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_medicine_sale.id AND hms_branch_hospital_no.section_id IN(6,10)','left');

            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');

            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_medicine_sale.branch_id','0'); 
            $this->db->where('hms_medicine_sale.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
            $this->db->where('hms_payment.section_id IN (3)'); 
            $this->db->where('hms_payment.debit>0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_medicine_array['medicine_payment_mode_array'] = $this->db->get()->result(); 

            /* medicine sale collection list */
            return $new_medicine_array;
            
        } 
    }

    //ot branch collection
    public function branch_ot_collection_list($get="",$ids=[])
    {
         $new_array_ot=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id,hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_operation_booking.operation_date as booking_date,hms_operation_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_operation_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_operation_booking.referral_doctor=0 THEN concat('Other ',hms_operation_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");

            
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_operation_booking.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (16,15)','left');*/

         


            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            $this->db->where('hms_operation_booking.branch_id','0');
            $this->db->where('hms_operation_booking.branch_id IN ('.$branch_id.')');
            //$this->db->where('hms_payment.parent_id',0); 
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
            $this->db->where('hms_payment.section_id',8); 
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
            $new_array_ot['ot_collection'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

            /* payment mode ot */

              $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id,hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_operation_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_operation_booking.referral_doctor=0 THEN concat('Other ',hms_operation_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_operation_booking.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (16,15)','left');*/
            
            $this->db->where('hms_operation_booking.branch_id','0');
            $this->db->where('hms_operation_booking.branch_id IN ('.$branch_id.')');

             $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id',8); 
            $this->db->where('hms_payment.debit>0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_array_ot['ot_collection_payment_mode'] = $this->db->get()->result(); 


            /* payment mode ot */
            return $new_array_ot;
            
        } 
    }
    //ot branch collection

    public function branch_vaccination_collection_list($get="",$ids=[])
    {

         $new_vaccination_array=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
           
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_vaccination_sale.sale_date as booking_date,hms_vaccination_sale.sale_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_vaccination_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_vaccination_sale.refered_id=0 THEN concat('Other ',hms_vaccination_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");

            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left'); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_vaccination_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');

            /* $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (5,13)','left'); */
            $this->db->where('hms_vaccination_sale.is_deleted','0');
            $this->db->where('hms_vaccination_sale.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
            $this->db->where('hms_payment.section_id IN (7)');
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
            $new_vaccination_array['vaccination_collection'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

            /* new vaccination array  */

             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_vaccination_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_vaccination_sale.refered_id=0 THEN concat('Other ',hms_vaccination_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left'); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id');
            

            $this->db->join('hms_doctors','hms_doctors.id=hms_vaccination_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');

            /* $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (5,13)','left'); */
            $this->db->where('hms_vaccination_sale.is_deleted','0');
            $this->db->where('hms_vaccination_sale.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
            $this->db->where('hms_payment.section_id IN (7)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->from('hms_payment');
            $new_vaccination_array['vaccination_payment_mode_collection'] = $this->db->get()->result(); 

            /* new vaccination array */
            return $new_vaccination_array;
            
        } 
    }

      public function branch_medicine_return_collection_list($get="",$ids=[])
    {
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
           $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name, hms_payment.type,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
              $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
             $this->db->join('hms_medicine_return','hms_medicine_return.id=hms_payment.parent_id');
             $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left'); 
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_payment.type','3') ;
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
            $this->db->where('hms_medicine_return.is_deleted','0');
            $this->db->where('hms_medicine_return.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.section_id IN (3)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->from('hms_payment');
            $query = $this->db->get()->result(); 
            //print_r($query);die;
            //echo $this->db->last_query(); exit; 
            return $query;
            
        } 
    }

    public function medicine_branch_collection_list($get="",$ids=[])
    {
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_branch.branch_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode, hms_patient.patient_name,hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            //$this->db->where('hms_payment.patient_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
            $this->db->where('hms_payment.section_id IN (3)'); 
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
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_doctors','hms_doctors.id=hms_payment.doctor_id'); 
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_doctors.doctor_pay_type',2); 
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.patient_id',0); 
            $this->db->where('hms_payment.parent_id',0); 
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
            $this->db->where('hms_payment.section_id IN (2,3)'); 
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
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');  
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
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_opd_booking.is_deleted','0');
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.section_id IN (2,3,4)'); 
            $this->db->from('hms_payment');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
        } 
    }

    public function self_opd_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users');
        $new_self_opd_array=array(); 
        if(!empty($get))
        {  
            /*$this->db->select("hms_patient.patient_name, hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_payment_mode.payment_mode as mode");*/
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_opd_booking.booking_date,hms_opd_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit,hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,   hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
            //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (1,8)','left');

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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            //$this->db->where('hms_opd_booking.type',3);
            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (2)'); 
            $this->db->where('hms_payment.debit>=0');
            
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
            $new_self_opd_array['self_opd_coll'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

            /* self opd collection payment */

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            //(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
            //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (1,8)','left');

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
               /* if($users_data['record_access']=='1')
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
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }
            //$this->db->where('hms_opd_booking.type',3);
            $this->db->where('hms_opd_booking.is_deleted',0); 
            $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (2)'); 
            $this->db->where('hms_payment.debit>=0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_self_opd_array['self_opd_coll_payment_mode'] = $this->db->get()->result(); 

             /* self opd collection payment */

            return $new_self_opd_array;
        } 
    }



    public function self_billing_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_billing= array();
        if(!empty($get))
        {  
            
            
             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_opd_booking.booking_date,hms_opd_booking.reciept_code as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");

            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');

             /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (2,12)','left');*/
            
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
               /* if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            $new_self_billing['self_bill_coll'] = $this->db->get()->result();  
            //echo $this->db->last_query();die;

            /* self bill coll payment mode  */

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');

            /* $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (2,12)','left');*/
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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_self_billing['self_bill_coll_payment_mode'] = $this->db->get()->result();  


              /* self bill coll payment mode  */

            return $new_self_billing;
        } 
    }
    
    public function self_medicine_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_medicine_array=array();
        if(!empty($get))
        {  

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_medicine_sale.sale_date as booking_date,hms_medicine_sale.sale_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale.refered_id=0 THEN concat('Other ',hms_medicine_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id AND hms_medicine_sale.is_deleted=0');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');

           /* $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (6,10)','left');*/
   
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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            $new_medicine_array['med_coll'] = $this->db->get()->result();  

            /* med coll payment mode */
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale.refered_id=0 THEN concat('Other ',hms_medicine_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            //,(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id AND hms_medicine_sale.is_deleted=0');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');

            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (6,10)','left');*/
   
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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_medicine_array['med_coll_pay_mode'] = $this->db->get()->result();  
            /* med coll payment mode */


            

            return $new_medicine_array;
        } 
    }

    //ot self collection
    public function self_ot_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_ot_array=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN 'Yes' ELSE 'No' END ) as panel_type,hms_operation_booking.operation_date as booking_date,hms_operation_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_operation_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_operation_booking.referral_doctor=0 THEN concat('Other ',hms_operation_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
           
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_operation_booking.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (16,15)','left');*/
   
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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            $this->db->where('hms_payment.section_id',8); 
            $this->db->where('hms_operation_booking.is_deleted','0');
            $this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']); 
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
            //$this->db->group_by('hms_payment.id');
            $new_self_ot_array['self_ot_coll'] = $this->db->get()->result(); 
            //echo  $this->db->last_query();die; 

            /* self ot collection payment_mode*/

             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_operation_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_operation_booking.referral_doctor=0 THEN concat('Other ',hms_operation_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
           
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_operation_booking.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (16,15)','left');*/
   
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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            $this->db->where('hms_payment.section_id',8); 
            $this->db->where('hms_operation_booking.is_deleted','0');
            $this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit>0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            //$this->db->group_by('hms_payment.id');
            $new_self_ot_array['self_ot_coll_payment_mode'] = $this->db->get()->result(); 

             /* self ot collection payment_mode*/



            return $new_self_ot_array;
        } 
    }

    //ot self collection


    public function self_vaccination_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 

        $new_self_vaccination=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_vaccination_sale.sale_date as booking_date,hms_vaccination_sale.sale_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_vaccination_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_vaccination_sale.refered_id=0 THEN concat('Other ',hms_vaccination_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 


            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_vaccination_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale.referral_hospital','left');

             /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (5,13)','left');*/
   
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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            //$this->db->order_by('hms_payment.id','DESC');
            $this->db->from('hms_payment');
           $new_self_vaccination['vaccine_coll'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

           /* self payment mode */

           $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_vaccination_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_vaccination_sale.refered_id=0 THEN concat('Other ',hms_vaccination_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_vaccination_sale.refered_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale.referral_hospital','left');

            /* $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (5,13)','left');*/
   
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
            $this->db->where('hms_payment.section_id IN (7)'); 
            $this->db->where('hms_vaccination_sale.is_deleted','0');
            $this->db->where('hms_vaccination_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.debit>0');
             $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
             $new_self_vaccination['vaccine_coll_payment_mode'] = $this->db->get()->result();

           /* self vaccination payment mode */
            return $new_self_vaccination;
        } 
    }

    public function self_medicine_return_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($get))
        {  
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_payment.type,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
              $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
             $this->db->join('hms_medicine_return','hms_medicine_return.id=hms_payment.parent_id');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left'); 
            $this->db->where('hms_payment.type','3') ;
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
/*                if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            $this->db->where('hms_medicine_return.is_deleted != 2');
            $this->db->where('hms_payment.section_id IN (3)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->from('hms_payment');
            $query = $this->db->get()->result();  
          // print_r($query);die;
            return $query;
        } 
    }
    

    public function employee_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_employees.name','ASC');
        $this->db->where('is_deleted',0);  
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_employees');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result; 
    }

    public function next_appotment_list($get=""){
          
            $this->db->select("hms_opd_booking.*,hms_patient.*,hms_doctors.doctor_name,hms_cities.city,hms_disease.disease as dise"); 
            $this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left');
             $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.referral_doctor','left'); 
             $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
              $this->db->join('hms_disease','hms_disease.id=hms_opd_booking.diseases','left');
             
            $this->db->where('hms_opd_booking.branch_id',$get['branch_id']); 
            // $this->db->where('hms_opd_booking.next_app_date!= ','0000-00-00',FALSE);
$this->db->where('hms_opd_booking.next_app_date!= ','0000-00-00');
             $this->db->where('hms_opd_booking.next_app_date!= ','1970-01-01');
           
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_opd_booking.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_opd_booking.created_date <= "'.$end_date.'"');
            }
            $this->db->where('hms_opd_booking.is_deleted',0); //billing type in hms_opd_booking 3
            $this->db->where('hms_opd_booking.status',0);  //billing section id 4
            $this->db->where('hms_opd_booking.type',2);  //billing section id 4
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
            elseif(!empty($get["employee"]))
            {
                $emp_ids=  $get["employee"];
            }
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_opd_booking.created_by IN ('.$emp_ids.')');
            }
            $this->db->from('hms_opd_booking');
            $query = $this->db->get();  
            //echo $this->db->last_query();die;
            return $query->result();
    }



    

    public function branch_source_name_list($get="",$ids=[])
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            $this->db->select("hms_patient_source.id as p_id,hms_patient_source.source,hms_branch.branch_name,(select count(id) from hms_opd_booking as total_opd where total_opd.source_from = hms_patient_source.id  AND total_opd.branch_id IN ('".$branch_id."')) as total,(select count(id) from hms_opd_booking as opd_appointment where opd_appointment.source_from = hms_patient_source.id AND opd_appointment.type=1 AND opd_appointment.branch_id IN ('".$branch_id."')) as total_enquiry, 
                   (select count(id) from hms_opd_booking as opd_booking where opd_booking.source_from = hms_patient_source.id AND opd_booking.type=2 AND opd_booking.branch_id IN ('".$branch_id."')) as total_booking, 
                   (select count(id) from hms_opd_booking as opd_billing where opd_billing.source_from = hms_patient_source.id AND opd_billing.type=3 AND opd_billing.branch_id IN ('".$branch_id."')) as total_billing");
            $this->db->join('hms_opd_booking','hms_patient_source.id=hms_opd_booking.source_from');
            $this->db->join('hms_branch','hms_branch.id=hms_patient_source.branch_id','left');
            $this->db->where('hms_patient_source.branch_id IN ('.$branch_id.')'); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_opd_booking.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_opd_booking.created_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_patient_source');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
            
        } 
    }

    public function all_child_branch_source_report($get="",$ids=[])
    {
       $users_data = $this->session->userdata('auth_users'); 
        if(!empty($ids))
        {  
             $branch_id = implode(',', $ids);
            $this->db->select("hms_patient_source.source, 
                   (select count(id) from hms_opd_booking as total_opd where total_opd.source_from = hms_patient_source.id  AND total_opd.branch_id IN ('".$branch_id."')) as total,(select count(id) from hms_opd_booking as opd_appointment where opd_appointment.source_from = hms_patient_source.id AND opd_appointment.type=1 AND opd_appointment.branch_id IN ('".$branch_id."')) as total_enquiry, 
                   (select count(id) from hms_opd_booking as opd_booking where opd_booking.source_from = hms_patient_source.id AND opd_booking.type=2 AND opd_booking.branch_id IN ('".$branch_id."')) as total_booking, 
                   (select count(id) from hms_opd_booking as opd_billing where opd_billing.source_from = hms_patient_source.id AND opd_billing.type=3 AND opd_billing.branch_id IN ('".$branch_id."')) as total_billing"); 
            $this->db->join('hms_opd_booking','hms_patient_source.id=hms_opd_booking.source_from');
           // $this->db->where_in('hms_opd_booking.branch_id',$branch_id);
           $this->db->where('hms_patient_source.branch_id IN ('.$branch_id.')');    
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_opd_booking.created_date >= "'.$start_date.'"');
            }
            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_opd_booking.created_date <= "'.$end_date.'"');
            }
            $this->db->group_by('hms_patient_source.id');
            $this->db->from('hms_patient_source');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit;
            return $query->result();
        }  
    }


   public function all_branch_users()
   {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select("hms_users.*, hms_users.id as user_id, hms_employees.name"); 
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->where('hms_users.parent_id',$users_data['parent_id']); 
        $this->db->where('hms_users.emp_id >','0');
        $this->db->from('hms_users');
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        return $query->result();   
   }

   public function all_user_source_report($get="",$user_id='',$source_id='')
    {
       $users_data = $this->session->userdata('auth_users'); 
        if(!empty($user_id))
        {  
            $this->db->select("(select count(id) from hms_opd_booking as opd_appointment where opd_appointment.source_from = hms_patient_source.id AND opd_appointment.type=1 AND opd_appointment.appointment=1 AND opd_appointment.created_by='".$user_id."') as total_enquiry, 
                (select count(id) from hms_opd_booking as opd_booking where opd_booking.source_from = hms_patient_source.id AND opd_booking.type=2 AND opd_booking.appointment=1 AND opd_booking.created_by='".$user_id."') as total_booking"); 
            $this->db->join('hms_opd_booking','hms_patient_source.id=hms_opd_booking.source_from');
           // $this->db->where_in('hms_opd_booking.branch_id',$branch_id);
           $this->db->where('hms_patient_source.branch_id='.$users_data['parent_id']);
           $this->db->where('hms_opd_booking.source_from='.$source_id);    
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_opd_booking.created_date >= "'.$start_date.'"');
            }
            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_opd_booking.created_date <= "'.$end_date.'"');
            }
            $this->db->group_by('hms_patient_source.id');
            $this->db->from('hms_patient_source');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); 
            return $query->result();
        }  
    }

    public function source_from_report_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($get))
        {  
             
        //$this->db->select("hms_patient_source.id as source_id,hms_patient_source.source, (select count(id) from hms_opd_booking as total_opd where total_opd.source_from = hms_patient_source.id  AND total_opd.branch_id = '".$users_data['parent_id']."' AND total_opd.appointment=1 ) as total,(select count(id) from hms_opd_booking as opd_appointment where opd_appointment.source_from = hms_patient_source.id AND opd_appointment.type=1 AND opd_appointment.appointment=1  AND opd_appointment.branch_id = '".$users_data['parent_id']."') as total_enquiry,  (select count(id) from hms_opd_booking as opd_booking where opd_booking.source_from = hms_patient_source.id AND opd_booking.type=2  AND opd_booking.appointment=1  AND opd_booking.branch_id = '".$users_data['parent_id']."') as total_booking");
        $this->db->select("hms_patient_source.id as source_id,hms_patient_source.source, 
               (select count(id) from hms_opd_booking as total_opd where total_opd.source_from = hms_patient_source.id  AND total_opd.branch_id = '".$users_data['parent_id']."' AND total_opd.is_deleted!='2') as total,(select count(id) from hms_opd_booking as opd_appointment where opd_appointment.source_from = hms_patient_source.id AND opd_appointment.type=1  AND opd_appointment.branch_id = '".$users_data['parent_id']."' AND opd_appointment.is_deleted!='2') as total_enquiry, 
                  (select count(id) from hms_opd_booking as opd_booking where opd_booking.source_from = hms_patient_source.id AND opd_booking.type=2 AND opd_booking.branch_id = '".$users_data['parent_id']."' AND opd_booking.is_deleted!='2') as total_booking
                  , 
                  (select count(id) from hms_opd_booking as opd_billing where opd_billing.source_from = hms_patient_source.id AND opd_billing.type=3 AND opd_billing.branch_id = '".$users_data['parent_id']."' AND opd_billing.is_deleted!='2') as total_billing");
        
        $this->db->join('hms_opd_booking','hms_patient_source.id=hms_opd_booking.source_from');
        $this->db->join('hms_users','hms_users.id=hms_opd_booking.created_by','left');           
        $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);   
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_opd_booking.created_date >= "'.$start_date.'"');
            }
            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_opd_booking.created_date <= "'.$end_date.'"');
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
            elseif(!empty($get["employee"]))
            {
                $emp_ids=  $get["employee"];
            }
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_opd_booking.created_by IN ('.$emp_ids.')');
            }
            $this->db->group_by('hms_patient_source.id');
            $this->db->from('hms_patient_source');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit;
            return $query->result();
        } 
    }

    //IPD Billing

    public function self_ipd_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_ipd_coll=array();
        if(!empty($get))
        {  
           
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_ipd_booking.discharge_date as booking_date,hms_ipd_booking.ipd_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ipd_booking.   referral_doctor=0 THEN concat('Other ',hms_ipd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");

            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ipd_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (3,7,9)','left');*/
   
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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            //$this->db->order_by('hms_payment.id','DESC');
            $this->db->group_by('hms_payment.id');
            $this->db->from('hms_payment');
            $new_self_ipd_coll['ipd_coll'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit;

            /* payment coll_ipd */

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ipd_booking.   referral_doctor=0 THEN concat('Other ',hms_ipd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            //(CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id','left');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ipd_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (3,7,9)','left');*/
   
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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
                
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
            //$this->db->group_by('hms_payment.id');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_self_ipd_coll['ipd_coll_payment_mode'] = $this->db->get()->result(); 

            /* payment coll_ipd */
            return $new_self_ipd_coll;
        } 
    } 
    //ipd branch collection
    public function branch_ipd_collection_list($get="",$ids=[])
    {
         $new_ipd_array=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_payment.id as p_id,hms_branch.branch_name,hms_branch.id as branch_id,hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_ipd_booking.discharge_date as booking_date,hms_ipd_booking.ipd_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ipd_booking.   referral_doctor=0 THEN concat('Other ',hms_ipd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");

            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id','left');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ipd_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (3,7,9)','left');*/

            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
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
            $this->db->where('hms_payment.section_id IN (5)');
            $this->db->where('hms_payment.debit>0'); 
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
            $new_ipd_array['ipd_collection_list'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 


            /* ipd payment mode */

              $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_payment.id as p_id,hms_branch.branch_name,hms_branch.id as branch_id,hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit)as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ipd_booking.   referral_doctor=0 THEN concat('Other ',hms_ipd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");
            //,(CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id','left');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ipd_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (3,7,9)','left');*/

            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
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
            $this->db->where('hms_payment.section_id IN (5)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->group_by('hms_payment.pay_mode'); 
            $this->db->from('hms_payment');
            $new_ipd_array['ipd_payment_mode_list'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 
             return $new_ipd_array;
            /* ipd payment mode */
        
            
        } 
    }



    public function pathology_branch_collection_list($get="",$ids=[])
    {
         $new_path_array= array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,path_test_booking.booking_date,path_test_booking.lab_reg_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");
            
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');  
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (4,11)','left');*/


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
            //user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id',1); 
            $this->db->where('path_test_booking.is_deleted != 2');
            $this->db->where('hms_payment.debit>0');
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
            $new_path_array['pathalogy_collection'] = $this->db->get()->result(); 

            /* pathalogy payment mode list */
             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');  
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (4,11)','left');*/


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
            //user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id',1); 
            $this->db->where('path_test_booking.is_deleted != 2');
            $this->db->where('hms_payment.debit>0');
            $this->db->order_by('hms_payment.id','DESC');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_path_array['pathalogy_collection_payment_mode'] = $this->db->get()->result(); 
            /* pathalogy payment mode list */

            

            return $new_path_array;
        } 
    }

    public function pathology_doctor_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($get))
        {  
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_name,hms_payment_mode.payment_mode as mode");
            //hms_doctors.doctor_name
            //$this->db->join('hms_doctors','hms_doctors.id=hms_payment.doctor_id');
            $this->db->join('path_test_booking','path_test_booking.id = hms_payment.parent_id AND is_deleted=0','left');

            $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
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
            $this->db->where('hms_payment.section_id',1); 
            $this->db->order_by('hms_payment.id','DESC');
            $this->db->from('hms_payment');
            $query = $this->db->get();  
            return $query->result();
        } 
    }

    public function pathology_self_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_path_array=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,path_test_booking.booking_date,path_test_booking.lab_reg_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id AND hms_patient.is_deleted=0','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id AND path_test_booking.is_deleted=0');
            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (4,11)','left');*/

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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            if($users_data['parent_id']=='66' || $users_data['parent_id']=='171' || $users_data['parent_id']=='129')
            {
                $this->db->where('hms_payment.debit>=0');
            }
            else
            {
                $this->db->where('hms_payment.debit>0');   
            }
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
            $new_self_path_array['path_coll'] = $this->db->get()->result();  
            //echo $this->db->last_query();die;

            /* path payment coll */
             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id');
           

            $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (4,11)','left');*/

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
                /*if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }*/
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
            
            if($users_data['parent_id']=='66' || $users_data['parent_id']=='171' || $users_data['parent_id']=='129')
            {
                $this->db->where('hms_payment.debit>=0');
            }
            else
            {
                $this->db->where('hms_payment.debit>0');   
            }
            
            $this->db->order_by('hms_payment.id','DESC');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_self_path_array['path_coll_pay_mode'] = $this->db->get()->result();  
            /* path payment coll */


            return  $new_self_path_array;
        } 
    }

    /* hospital collection report start */ 

    public function self_hospital_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_array=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.discount_amount,

                (CASE WHEN hms_payment.section_id=1 THEN 'Pathology' WHEN hms_payment.section_id=2 THEN 'OPD' WHEN hms_payment.section_id=3 THEN 'Sale Medicine' WHEN hms_payment.section_id=4 THEN 'OPD Billing'  WHEN hms_payment.section_id=5 THEN 'IPD' WHEN hms_payment.section_id=7 THEN 'Vaccination' WHEN hms_payment.section_id=8 THEN 'OT' WHEN hms_payment.section_id=10 THEN 'Blood Bank'  ELSE '' END) as section_name,

                (CASE WHEN hms_payment.section_id=1 THEN path_booking.lab_reg_no WHEN hms_payment.section_id=2 THEN opd_booking.booking_code WHEN hms_payment.section_id=3 THEN sell_medicne.sale_no WHEN hms_payment.section_id=4 THEN opd_billing.reciept_code  WHEN hms_payment.section_id=5 THEN ipd_booking.ipd_no WHEN hms_payment.section_id=7 THEN vaccination_booking.sale_no WHEN hms_payment.section_id=8 THEN operation_booking.booking_code WHEN hms_payment.section_id=10 THEN recipient_booking.issue_code ELSE '' END) as booking_code,

                (CASE WHEN hms_payment.section_id=1 THEN path_booking.booking_date WHEN hms_payment.section_id=2 THEN opd_booking.booking_date WHEN hms_payment.section_id=3 THEN sell_medicne.sale_date WHEN hms_payment.section_id=4 THEN opd_billing.booking_date  WHEN hms_payment.section_id=5 THEN ipd_booking.admission_date WHEN hms_payment.section_id=7 THEN vaccination_booking.sale_date WHEN hms_payment.section_id=8 THEN operation_booking.operation_date WHEN hms_payment.section_id=10 THEN recipient_booking.requirement_date ELSE '' END) as booking_date,

                (CASE WHEN hms_payment.section_id=1 THEN path_attended_doc.doctor_name WHEN hms_payment.section_id=2 THEN opd_attended_doc.doctor_name WHEN hms_payment.section_id=3 THEN '' WHEN hms_payment.section_id=4 THEN billing_attended_doc.doctor_name  WHEN hms_payment.section_id=5 THEN ipd_attended_doc.doctor_name WHEN hms_payment.section_id=7 THEN vaccination_doctor.doctor_name WHEN hms_payment.section_id=8 THEN ot_attended_doc.doctor_name WHEN hms_payment.section_id=10 THEN recipient_booking_doctor.doctor_name  ELSE '' END) as doctor_name,

                (CASE 
                    
                    WHEN hms_payment.section_id=1 THEN (CASE WHEN path_booking.referred_by =1 THEN concat(path_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',path_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=2 THEN (CASE WHEN opd_booking.referred_by =1 THEN 
                    concat(opd_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',opd_doctor.doctor_name) END)
                    
                    WHEN hms_payment.section_id=3 THEN (CASE WHEN sell_medicne.referred_by =1 THEN 

                    concat(medicine_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',medicine_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=4 THEN (CASE WHEN opd_billing.referred_by =1 THEN 
                    concat(billing_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',billing_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=5 THEN (CASE WHEN ipd_booking.referred_by =1 THEN

                    concat(ipd_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',ipd_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=7 THEN (CASE WHEN vaccination_booking.referred_by =1 THEN concat(vaccination_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',vaccination_doctor.doctor_name) END)
                    
                     WHEN hms_payment.section_id=8 THEN (CASE WHEN operation_booking.referred_by =1 THEN concat(operation_booking_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',operation_booking_doctor.doctor_name) END)

                      WHEN hms_payment.section_id=10 THEN (CASE WHEN recipient_booking.referred_by =1 THEN concat(recipient_booking_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',recipient_booking_doctor.doctor_name) END)

                       
                ELSE 'N/A'
                END
                ) as doctor_hospital_name,

                (CASE 
                    WHEN hms_payment.section_id=1 THEN path_hospital_no.reciept_prefix 
                    WHEN hms_payment.section_id=2 THEN opd_hospital_no.reciept_prefix 
                    WHEN hms_payment.section_id=3 THEN medicine_reciept_no.reciept_prefix
                    WHEN hms_payment.section_id=4 THEN billing_reciept_no.reciept_prefix 
                    WHEN hms_payment.section_id=5 THEN ipd_reciept_no.reciept_prefix
                    WHEN hms_payment.section_id=7 THEN vaccination_reciept_no.reciept_prefix  
                    WHEN hms_payment.section_id=8 THEN operation_booking_reciept_no.reciept_prefix  

                    WHEN hms_payment.section_id=10 THEN receipent_booking_reciept_no.reciept_prefix  

                    ELSE '' END) as reciept_prefix,

                 (CASE 
                    WHEN hms_payment.section_id=1 THEN path_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=2 THEN opd_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=3 THEN medicine_payment_mode.payment_mode
                    WHEN hms_payment.section_id=4 THEN billing_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=5 THEN ipd_payment_mode.payment_mode
                    WHEN hms_payment.section_id=7 THEN vaccination_payment_mode.payment_mode  
                     WHEN hms_payment.section_id=8 THEN operation_payment_mode.payment_mode

                      WHEN hms_payment.section_id=10 THEN recepient_payment_mode.payment_mode  


                    ELSE '' END) as mode_name,


                (CASE 

                    WHEN hms_payment.section_id=1 THEN path_hospital_no.reciept_suffix 
                    WHEN hms_payment.section_id=2 THEN opd_hospital_no.reciept_suffix 
                    WHEN hms_payment.section_id=3 THEN medicine_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=4 THEN billing_reciept_no.reciept_suffix 
                    WHEN hms_payment.section_id=5 THEN ipd_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=7 THEN vaccination_reciept_no.reciept_suffix
                    
                    WHEN hms_payment.section_id=8 THEN  operation_booking_reciept_no.reciept_suffix

                     WHEN hms_payment.section_id=10 THEN receipent_booking_reciept_no.reciept_suffix
                    
                    ELSE '' END) as reciept_suffix
                    
                "
                );

            //common 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id');
            //OPD
            $this->db->join('hms_opd_booking as opd_booking','opd_booking.id=hms_payment.parent_id AND opd_booking.is_deleted=0 AND opd_booking.type = 2 AND opd_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_doctors as opd_doctor','opd_doctor.id=opd_booking.referral_doctor','left');
            $this->db->join('hms_hospital as opd_hospital','opd_hospital.id = opd_booking.referral_hospital','left');
             /* get payment mode */
               //opd_payment_mode
             $this->db->join('hms_payment_mode as opd_payment_mode','opd_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            $this->db->join('hms_branch_hospital_no as opd_hospital_no','opd_hospital_no.payment_id = hms_payment.id AND opd_hospital_no.section_id IN (1,8)','left'); //8
            $this->db->join('hms_doctors as opd_attended_doc','opd_attended_doc.id = opd_booking.attended_doctor','left');
            //OPD

            //Billing
            $this->db->join('hms_opd_booking as opd_billing','opd_billing.id=hms_payment.parent_id AND opd_billing.is_deleted=0 AND opd_booking.type = 3 AND opd_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_doctors billing_doctor','billing_doctor.id=opd_billing.referral_doctor','left');
            $this->db->join('hms_hospital billing_hospital','billing_hospital.id = opd_billing.referral_hospital','left');
             /* get payment mode */
               //billing_payment_mode
             $this->db->join('hms_payment_mode as billing_payment_mode','billing_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            //1 OPD , 2 OPD Billing,6 medicine sell
            $this->db->join('hms_branch_hospital_no as billing_reciept_no','billing_reciept_no.payment_id = hms_payment.id AND billing_reciept_no.section_id IN (2,12)','left'); //12

            $this->db->join('hms_doctors as billing_attended_doc','billing_attended_doc.id = opd_billing.attended_doctor','left');
            //Billing

            
            //medicine
            $this->db->join('hms_medicine_sale as sell_medicne','sell_medicne.id=hms_payment.parent_id AND sell_medicne.is_deleted=0  AND sell_medicne.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as medicine_hospital','medicine_hospital.id = sell_medicne.referral_hospital','left');
            $this->db->join('hms_doctors as medicine_doctor','medicine_doctor.id=sell_medicne.refered_id','left');
             /* get payment mode */
               //medicine_payment_mode
             $this->db->join('hms_payment_mode as medicine_payment_mode','medicine_payment_mode.id = hms_payment.pay_mode','left');
             /* ipd_payment_mode */
            $this->db->join('hms_branch_hospital_no as medicine_reciept_no','medicine_reciept_no.payment_id = hms_payment.id AND medicine_reciept_no.section_id IN (6,10)','left'); // 10
            //medicine


            //ipd
            $this->db->join('hms_ipd_booking as ipd_booking','ipd_booking.id=hms_payment.parent_id AND ipd_booking.is_deleted=0 AND ipd_booking.branch_id="'.$users_data['parent_id'].'"','left');            
            $this->db->join('hms_hospital as ipd_hospital','ipd_hospital.id = ipd_booking.referral_hospital','left');
            /* get payment mode */
               //ipd_payment_mode
             $this->db->join('hms_payment_mode as ipd_payment_mode','ipd_payment_mode.id = hms_payment.pay_mode','left');
             /* ipd_payment_mode */
            $this->db->join('hms_doctors as ipd_doctor','ipd_doctor.id=ipd_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as ipd_reciept_no','ipd_reciept_no.payment_id = hms_payment.id AND ipd_reciept_no.section_id IN(3,9,7)','left'); 

            $this->db->join('hms_doctors as ipd_attended_doc','ipd_attended_doc.id = ipd_booking.attend_doctor_id','left');
            //ipd

            //pathology
            $this->db->join('path_test_booking as path_booking','path_booking.id=hms_payment.parent_id AND path_booking.is_deleted=0  AND path_booking.branch_id="'.$users_data['parent_id'].'"','left');

            $this->db->join('hms_hospital as path_hospital','path_hospital.id = path_booking.referral_hospital','left');
            $this->db->join('hms_doctors as path_doctor','path_doctor.id=path_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as path_hospital_no','path_hospital_no.payment_id = hms_payment.id AND path_hospital_no.section_id IN (4,11)','left'); //11

               /* get payment mode */
               //path_payment_mode
             $this->db->join('hms_payment_mode as path_payment_mode','path_payment_mode.id = hms_payment.pay_mode','left');
             
             $this->db->join('hms_doctors as path_attended_doc','path_attended_doc.id = path_booking.attended_doctor','left');
             /* get payment mode */
            //pathology

            //vaccination_booking
           

            $this->db->join('hms_vaccination_sale as vaccination_booking','vaccination_booking.id=hms_payment.parent_id AND vaccination_booking.is_deleted=0   AND vaccination_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as vaccination_hospital','vaccination_hospital.id = vaccination_booking.referral_hospital','left');
            $this->db->join('hms_doctors as vaccination_doctor','vaccination_doctor.id=vaccination_booking.refered_id','left');
            $this->db->join('hms_branch_hospital_no as vaccination_reciept_no','vaccination_reciept_no.payment_id = hms_payment.id AND vaccination_reciept_no.section_id IN (5,13)','left'); //13
                /* get payment mode */
                //vaccination_payment_mode
             $this->db->join('hms_payment_mode as vaccination_payment_mode','vaccination_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            //vaccination_booking
            
             /* operation booking */
            $this->db->join('hms_operation_booking as operation_booking','operation_booking.id=hms_payment.parent_id AND operation_booking.is_deleted=0   AND operation_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as operation_booking_hospital','operation_booking_hospital.id = operation_booking.referral_hospital','left');
            $this->db->join('hms_doctors as operation_booking_doctor','operation_booking_doctor.id=operation_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as operation_booking_reciept_no','operation_booking_reciept_no.payment_id = hms_payment.id AND operation_booking_reciept_no.section_id IN (15,16)','left'); //13
                /* get payment mode */
             $this->db->join('hms_payment_mode as operation_payment_mode','operation_payment_mode.id = hms_payment.pay_mode','left');
             $this->db->join('hms_doctors as ot_attended_doc','ot_attended_doc.id=operation_booking.doctor_id','left');
             /* operation booking */


              /* blood bank booking */
            $this->db->join('hms_blood_patient_to_recipient as recipient_booking','recipient_booking.id=hms_payment.parent_id AND recipient_booking.is_deleted=0   AND recipient_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as recipient_booking_hospital','recipient_booking_hospital.id = recipient_booking.hospital_id','left');
            $this->db->join('hms_doctors as recipient_booking_doctor','recipient_booking_doctor.id=recipient_booking.doctor_id','left');
            $this->db->join('hms_branch_hospital_no as receipent_booking_reciept_no','receipent_booking_reciept_no.payment_id = hms_payment.id AND receipent_booking_reciept_no.section_id IN (17,18)','left'); //13
                /* get payment mode */
             $this->db->join('hms_payment_mode as recepient_payment_mode','recepient_payment_mode.id = hms_payment.pay_mode','left');

             /* blood bank booking */

            
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
            $this->db->where('hms_payment.section_id IN (1,2,3,4,5,7,8,10)'); 
            //2 OPD,4 OPD Billing ,3 medicne sell,
            $this->db->where('hms_payment.debit>0');

            //$this->db->order_by('hms_payment.created_date','DESC');
            //$this->db->order_by('vaccination_reciept_no.created_date,path_hospital_no.created_date,medicine_reciept_no.created_date,billing_reciept_no.created_date,opd_hospital_no.created_date','DESC');
            $this->db->from('hms_payment');
            $new_array['over_all_collection'] = $this->db->get()->result_array(); 
            //echo $this->db->last_query(); exit; 

           /////////////////////// /* code for payment mode */////////////////////////////

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,

                (CASE WHEN hms_payment.section_id=1 THEN 'Pathology' WHEN hms_payment.section_id=2 THEN 'OPD' WHEN hms_payment.section_id=3 THEN 'Sale Medicine' WHEN hms_payment.section_id=4 THEN 'OPD Billing'  WHEN hms_payment.section_id=5 THEN 'IPD' WHEN hms_payment.section_id=7 THEN 'Vaccination' WHEN hms_payment.section_id=8 THEN 'OT' WHEN hms_payment.section_id=10 THEN 'Blood Bank'  ELSE '' END) as section_name,
                (CASE 
                    
                    WHEN hms_payment.section_id=1 THEN (CASE WHEN path_booking.referred_by =1 THEN concat(path_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',path_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=2 THEN (CASE WHEN opd_booking.referred_by =1 THEN 
                    concat(opd_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',opd_doctor.doctor_name) END)
                    
                    WHEN hms_payment.section_id=3 THEN (CASE WHEN sell_medicne.referred_by =1 THEN 

                    concat(medicine_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',medicine_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=4 THEN (CASE WHEN opd_billing.referred_by =1 THEN 
                    concat(billing_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',billing_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=5 THEN (CASE WHEN ipd_booking.referred_by =1 THEN

                    concat(ipd_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',ipd_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=7 THEN (CASE WHEN vaccination_booking.referred_by =1 THEN concat(vaccination_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',vaccination_doctor.doctor_name) END)
                    
                     WHEN hms_payment.section_id=8 THEN (CASE WHEN operation_booking.referred_by =1 THEN concat(operation_booking_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',operation_booking_doctor.doctor_name) END)

                      WHEN hms_payment.section_id=10 THEN (CASE WHEN recipient_booking.referred_by =1 THEN concat(recipient_booking_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',recipient_booking_doctor.doctor_name) END)

                       
                ELSE 'N/A'
                END
                ) as doctor_hospital_name,

                (CASE 
                    WHEN hms_payment.section_id=1 THEN path_hospital_no.reciept_prefix 
                    WHEN hms_payment.section_id=2 THEN opd_hospital_no.reciept_prefix 
                    WHEN hms_payment.section_id=3 THEN medicine_reciept_no.reciept_prefix
                    WHEN hms_payment.section_id=4 THEN billing_reciept_no.reciept_prefix 
                    WHEN hms_payment.section_id=5 THEN ipd_reciept_no.reciept_prefix
                    WHEN hms_payment.section_id=7 THEN vaccination_reciept_no.reciept_prefix  
                    WHEN hms_payment.section_id=8 THEN operation_booking_reciept_no.reciept_prefix 

                    WHEN hms_payment.section_id=10 THEN receipent_booking_reciept_no.reciept_prefix  


                    ELSE '' END) as reciept_prefix,

                 (CASE 
                    WHEN hms_payment.section_id=1 THEN path_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=2 THEN opd_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=3 THEN medicine_payment_mode.payment_mode
                    WHEN hms_payment.section_id=4 THEN billing_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=5 THEN ipd_payment_mode.payment_mode
                    WHEN hms_payment.section_id=7 THEN vaccination_payment_mode.payment_mode  
                    WHEN hms_payment.section_id=8 THEN operation_payment_mode.payment_mode

                     WHEN hms_payment.section_id=10 THEN recepient_payment_mode.payment_mode 

                    ELSE '' END) as mode_name,


                (CASE 

                    WHEN hms_payment.section_id=1 THEN path_hospital_no.reciept_suffix 
                    WHEN hms_payment.section_id=2 THEN opd_hospital_no.reciept_suffix 
                    WHEN hms_payment.section_id=3 THEN medicine_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=4 THEN billing_reciept_no.reciept_suffix 
                    WHEN hms_payment.section_id=5 THEN ipd_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=7 THEN vaccination_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=8 THEN  operation_booking_reciept_no.reciept_suffix

                    WHEN hms_payment.section_id=10 THEN receipent_booking_reciept_no.reciept_suffix
                    ELSE '' END) as reciept_suffix
                "
                );

            //common 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id');
            //OPD
            $this->db->join('hms_opd_booking as opd_booking','opd_booking.id=hms_payment.parent_id AND opd_booking.is_deleted="0"    AND opd_booking.branch_id="'.$users_data['parent_id'].'" AND opd_booking.type = 2','left');
            $this->db->join('hms_doctors as opd_doctor','opd_doctor.id=opd_booking.referral_doctor','left');
            $this->db->join('hms_hospital as opd_hospital','opd_hospital.id = opd_booking.referral_hospital','left');
             /* get payment mode */
               //opd_payment_mode
             $this->db->join('hms_payment_mode as opd_payment_mode','opd_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            $this->db->join('hms_branch_hospital_no as opd_hospital_no','opd_hospital_no.payment_id = hms_payment.id AND opd_hospital_no.section_id IN (1,8)','left'); //8
            //OPD

            //Billing
            $this->db->join('hms_opd_booking as opd_billing','opd_billing.id=hms_payment.parent_id AND opd_billing.is_deleted="0" AND opd_booking.branch_id="'.$users_data['parent_id'].'" AND opd_booking.type = 3','left');
            $this->db->join('hms_doctors billing_doctor','billing_doctor.id=opd_billing.referral_doctor','left');
            $this->db->join('hms_hospital billing_hospital','billing_hospital.id = opd_billing.referral_hospital','left');
             /* get payment mode */
               //billing_payment_mode
             $this->db->join('hms_payment_mode as billing_payment_mode','billing_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            //1 OPD , 2 OPD Billing,6 medicine sell
            $this->db->join('hms_branch_hospital_no as billing_reciept_no','billing_reciept_no.payment_id = hms_payment.id AND billing_reciept_no.section_id IN (2,12)','left'); //12
            //Billing

            
            //medicine
            $this->db->join('hms_medicine_sale as sell_medicne','sell_medicne.id=hms_payment.parent_id AND sell_medicne.is_deleted="0"    AND sell_medicne.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as medicine_hospital','medicine_hospital.id = sell_medicne.referral_hospital','left');
            $this->db->join('hms_doctors as medicine_doctor','medicine_doctor.id=sell_medicne.refered_id','left');
             /* get payment mode */
               //medicine_payment_mode
             $this->db->join('hms_payment_mode as medicine_payment_mode','medicine_payment_mode.id = hms_payment.pay_mode','left');
             /* ipd_payment_mode */
            $this->db->join('hms_branch_hospital_no as medicine_reciept_no','medicine_reciept_no.payment_id = hms_payment.id AND medicine_reciept_no.section_id IN (6,10)','left'); // 10
            //medicine


            //ipd
            $this->db->join('hms_ipd_booking as ipd_booking','ipd_booking.id=hms_payment.parent_id AND ipd_booking.is_deleted="0"  AND ipd_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as ipd_hospital','ipd_hospital.id = ipd_booking.referral_hospital','left');
            /* get payment mode */
               //ipd_payment_mode
             $this->db->join('hms_payment_mode as ipd_payment_mode','ipd_payment_mode.id = hms_payment.pay_mode','left');
             /* ipd_payment_mode */
            $this->db->join('hms_doctors as ipd_doctor','ipd_doctor.id=ipd_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as ipd_reciept_no','ipd_reciept_no.payment_id = hms_payment.id AND ipd_reciept_no.section_id IN(3,9,7)','left'); // 9
            //ipd

            //pathology
            $this->db->join('path_test_booking as path_booking','path_booking.id=hms_payment.parent_id AND path_booking.is_deleted="0" AND ipd_booking.branch_id="'.$users_data['parent_id'].'"','left');

            $this->db->join('hms_hospital as path_hospital','path_hospital.id = path_booking.referral_hospital','left');
            $this->db->join('hms_doctors as path_doctor','path_doctor.id=path_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as path_hospital_no','path_hospital_no.payment_id = hms_payment.id AND path_hospital_no.section_id IN (4,11)','left'); //11

               /* get payment mode */
               //path_payment_mode
             $this->db->join('hms_payment_mode as path_payment_mode','path_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            //pathology

            //vaccination_booking
           

            $this->db->join('hms_vaccination_sale as vaccination_booking','vaccination_booking.id=hms_payment.parent_id AND vaccination_booking.is_deleted=0  AND vaccination_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as vaccination_hospital','vaccination_hospital.id = vaccination_booking.referral_hospital','left');
            $this->db->join('hms_doctors as vaccination_doctor','vaccination_doctor.id=vaccination_booking.refered_id','left');
            $this->db->join('hms_branch_hospital_no as vaccination_reciept_no','vaccination_reciept_no.payment_id = hms_payment.id AND vaccination_reciept_no.section_id IN (5,13)','left'); //13
                /* get payment mode */
                //vaccination_payment_mode
             $this->db->join('hms_payment_mode as vaccination_payment_mode','vaccination_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            //vaccination_booking
            
            
               /* operation booking */
            $this->db->join('hms_operation_booking as operation_booking','operation_booking.id=hms_payment.parent_id AND operation_booking.is_deleted=0  AND vaccination_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as operation_booking_hospital','operation_booking_hospital.id = operation_booking.referral_hospital','left');
            $this->db->join('hms_doctors as operation_booking_doctor','operation_booking_doctor.id=operation_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as operation_booking_reciept_no','operation_booking_reciept_no.payment_id = hms_payment.id AND operation_booking_reciept_no.section_id IN (15,16)','left'); //13
                /* get payment mode */
             $this->db->join('hms_payment_mode as operation_payment_mode','operation_payment_mode.id = hms_payment.pay_mode','left');

             /* operation booking */

             /* blood bank booking */
            $this->db->join('hms_blood_patient_to_recipient as recipient_booking','recipient_booking.id=hms_payment.parent_id AND recipient_booking.is_deleted=0  AND recipient_booking.branch_id="'.$users_data['parent_id'].'"','left');
            $this->db->join('hms_hospital as recipient_booking_hospital','recipient_booking_hospital.id = recipient_booking.hospital_id','left');
            $this->db->join('hms_doctors as recipient_booking_doctor','recipient_booking_doctor.id=recipient_booking.doctor_id','left');
            $this->db->join('hms_branch_hospital_no as receipent_booking_reciept_no','receipent_booking_reciept_no.payment_id = hms_payment.id AND receipent_booking_reciept_no.section_id IN (17,18)','left'); //13
                /* get payment mode */
             $this->db->join('hms_payment_mode as recepient_payment_mode','recepient_payment_mode.id = hms_payment.pay_mode','left');

             /* blood bank booking */
             
             
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
            $this->db->where('hms_payment.section_id IN (1,2,3,4,5,7,8,10)'); 
            //2 OPD,4 OPD Billing ,3 medicne sell,
            $this->db->where('hms_payment.debit>0');
            
            if(!empty($get["order_by"]) && $get['order_by']=='ASC')
            {
                $this->db->order_by('hms_payment.id','ASC');
            }
            else
            {
                $this->db->order_by('hms_payment.id','DESC');
            }
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_array['over_all_collection_payment_mode'] = $this->db->get()->result_array(); 


             /////////////////////// /* code for payment mode */////////////////////////////

            return $new_array;
            // print '<pre>'; print_r($data);die;
        } 
    }

    /* hospital collection report end  */

    /* hospital sub branch report start */
    public function branch_hospital_collection_list($get="",$ids=[])
    {
        $new_branch_overall_array=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            $users_data = $this->session->userdata('auth_users'); 
         

            //(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
            //hms_branch.branch_name,hms_patient.patient_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_branch.branch_name,hms_patient.patient_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.discount_amount,

                (CASE WHEN hms_payment.section_id=1 THEN 'Pathology' WHEN hms_payment.section_id=2 THEN 'OPD' WHEN hms_payment.section_id=3 THEN 'Sale Medicine' WHEN hms_payment.section_id=4 THEN 'OPD Billing'  WHEN hms_payment.section_id=5 THEN 'IPD' WHEN hms_payment.section_id=7 THEN 'Vaccination' WHEN hms_payment.section_id=8 THEN 'OT' WHEN hms_payment.section_id=10 THEN 'Blood Bank'  ELSE '' END) as section_name,


                (CASE WHEN hms_payment.section_id=1 THEN path_booking.lab_reg_no WHEN hms_payment.section_id=2 THEN opd_booking.booking_code WHEN hms_payment.section_id=3 THEN sell_medicne.sale_no WHEN hms_payment.section_id=4 THEN opd_billing.reciept_code  WHEN hms_payment.section_id=5 THEN ipd_booking.ipd_no WHEN hms_payment.section_id=7 THEN vaccination_booking.sale_no WHEN hms_payment.section_id=8 THEN operation_booking.booking_code WHEN hms_payment.section_id=10 THEN recipient_booking.issue_code  ELSE '' END) as booking_code,

                (CASE WHEN hms_payment.section_id=1 THEN path_booking.booking_date WHEN hms_payment.section_id=2 THEN opd_booking.booking_date WHEN hms_payment.section_id=3 THEN sell_medicne.sale_date WHEN hms_payment.section_id=4 THEN opd_billing.booking_date  WHEN hms_payment.section_id=5 THEN ipd_booking.admission_date WHEN hms_payment.section_id=7 THEN vaccination_booking.sale_date WHEN hms_payment.section_id=8 THEN operation_booking.operation_date WHEN hms_payment.section_id=10 THEN recipient_booking.requirement_date  ELSE '' END) as booking_date,

                (CASE WHEN hms_payment.section_id=1 THEN path_attended_doc.doctor_name WHEN hms_payment.section_id=2 THEN opd_attended_doc.doctor_name WHEN hms_payment.section_id=3 THEN '' WHEN hms_payment.section_id=4 THEN billing_attended_doc.doctor_name  WHEN hms_payment.section_id=5 THEN ipd_attended_doc.doctor_name WHEN hms_payment.section_id=7 THEN vaccination_doctor.doctor_name WHEN hms_payment.section_id=8 THEN ot_attended_doc.doctor_name WHEN hms_payment.section_id=10 THEN recipient_booking_doctor.doctor_name  ELSE '' END) as doctor_name,
                (CASE 
                    
                    WHEN hms_payment.section_id=1 THEN (CASE WHEN path_booking.referred_by =1 THEN concat(path_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',path_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=2 THEN (CASE WHEN opd_booking.referred_by =1 THEN 
                    concat(opd_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',opd_doctor.doctor_name) END)
                    
                    WHEN hms_payment.section_id=3 THEN (CASE WHEN sell_medicne.referred_by =1 THEN 

                    concat(medicine_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',medicine_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=4 THEN (CASE WHEN opd_billing.referred_by =1 THEN 
                    concat(billing_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',billing_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=5 THEN (CASE WHEN ipd_booking.referred_by =1 THEN

                    concat(ipd_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',ipd_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=7 THEN (CASE WHEN vaccination_booking.referred_by =1 THEN concat(vaccination_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',vaccination_doctor.doctor_name) END)

                      WHEN hms_payment.section_id=8 THEN (CASE WHEN operation_booking.referred_by =1 THEN concat(operation_booking_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',operation_booking_doctor.doctor_name) END)

                      WHEN hms_payment.section_id=10 THEN (CASE WHEN recipient_booking.referred_by =1 THEN concat(recipient_booking_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',recipient_booking_doctor.doctor_name) END)

                       
                ELSE 'N/A'
                END
                ) as doctor_hospital_name,

                (CASE 
                    WHEN hms_payment.section_id=1 THEN path_hospital_no.reciept_prefix 
                    WHEN hms_payment.section_id=2 THEN opd_hospital_no.reciept_prefix 
                    WHEN hms_payment.section_id=3 THEN medicine_reciept_no.reciept_prefix
                    WHEN hms_payment.section_id=4 THEN billing_reciept_no.reciept_prefix 
                    WHEN hms_payment.section_id=5 THEN ipd_reciept_no.reciept_prefix
                    WHEN hms_payment.section_id=7 THEN vaccination_reciept_no.reciept_prefix  
                    WHEN hms_payment.section_id=8 THEN operation_booking_reciept_no.reciept_prefix  
                    WHEN hms_payment.section_id=10 THEN receipent_booking_reciept_no.reciept_prefix   

                    ELSE '' END) as reciept_prefix,
                  (CASE 
                    WHEN hms_payment.section_id=1 THEN path_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=2 THEN opd_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=3 THEN medicine_payment_mode.payment_mode
                    WHEN hms_payment.section_id=4 THEN billing_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=5 THEN ipd_payment_mode.payment_mode
                    WHEN hms_payment.section_id=7 THEN vaccination_payment_mode.payment_mode  
                    WHEN hms_payment.section_id=8 THEN operation_payment_mode.payment_mode  
                    WHEN hms_payment.section_id=10 THEN recepient_payment_mode.payment_mode  

                    ELSE '' END) as mode_name,

                (CASE 

                    WHEN hms_payment.section_id=1 THEN path_hospital_no.reciept_suffix 
                    WHEN hms_payment.section_id=2 THEN opd_hospital_no.reciept_suffix 
                    WHEN hms_payment.section_id=3 THEN medicine_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=4 THEN billing_reciept_no.reciept_suffix 
                    WHEN hms_payment.section_id=5 THEN ipd_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=7 THEN vaccination_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=8 THEN  operation_booking_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=10 THEN receipent_booking_reciept_no.reciept_suffix
                    ELSE '' END) as reciept_suffix
                "
                );

            //common doctor_name
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id');
            //OPD
            $this->db->join('hms_opd_booking as opd_booking','opd_booking.id=hms_payment.parent_id and opd_booking.is_deleted=0','left');
            /* get payment mode */
               //opd_payment_mode
             $this->db->join('hms_payment_mode as opd_payment_mode','opd_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            $this->db->join('hms_doctors as opd_doctor','opd_doctor.id=opd_booking.referral_doctor','left');
            $this->db->join('hms_hospital as opd_hospital','opd_hospital.id = opd_booking.referral_hospital','left');
            $this->db->join('hms_branch_hospital_no as opd_hospital_no','opd_hospital_no.payment_id = hms_payment.id AND opd_hospital_no.section_id IN(1,8)','left');

            $this->db->join('hms_doctors as opd_attended_doc','opd_attended_doc.id = opd_booking.attended_doctor','left');
            //OPD

            //Billing
            $this->db->join('hms_opd_booking as opd_billing','opd_billing.id=hms_payment.parent_id and opd_billing.is_deleted=0','left');
            $this->db->join('hms_doctors billing_doctor','billing_doctor.id=opd_billing.referral_doctor','left');
            $this->db->join('hms_hospital billing_hospital','billing_hospital.id = opd_billing.referral_hospital','left');
              //billing_payment_mode
             $this->db->join('hms_payment_mode as billing_payment_mode','billing_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            //1 OPD , 2 OPD Billing,6 medicine sell
            $this->db->join('hms_branch_hospital_no as billing_reciept_no','billing_reciept_no.payment_id = hms_payment.id AND billing_reciept_no.section_id IN(2,12)','left');

            $this->db->join('hms_doctors as billing_attended_doc','billing_attended_doc.id = opd_billing.attended_doctor','left');
            //Billing

            
            //medicine
            $this->db->join('hms_medicine_sale as sell_medicne','sell_medicne.id=hms_payment.parent_id AND sell_medicne.is_deleted=0','left');
             /* get payment mode */
               //medicine_payment_mode
             $this->db->join('hms_payment_mode as medicine_payment_mode','medicine_payment_mode.id = hms_payment.pay_mode','left');
             /* ipd_payment_mode */

            $this->db->join('hms_hospital as medicine_hospital','medicine_hospital.id = sell_medicne.referral_hospital','left');
            $this->db->join('hms_doctors as medicine_doctor','medicine_doctor.id=sell_medicne.refered_id','left');
            $this->db->join('hms_branch_hospital_no as medicine_reciept_no','medicine_reciept_no.payment_id = hms_payment.id AND medicine_reciept_no.section_id IN(6,10)','left');
            //medicine


            //ipd
            $this->db->join('hms_ipd_booking as ipd_booking','ipd_booking.id=hms_payment.parent_id AND ipd_booking.is_deleted=0','left');

            $this->db->join('hms_hospital as ipd_hospital','ipd_hospital.id = ipd_booking.referral_hospital','left');
             /* get payment mode */
               //ipd_payment_mode
             $this->db->join('hms_payment_mode as ipd_payment_mode','ipd_payment_mode.id = hms_payment.pay_mode','left');
             /* ipd_payment_mode */
            $this->db->join('hms_doctors as ipd_doctor','ipd_doctor.id=ipd_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as ipd_reciept_no','ipd_reciept_no.payment_id = hms_payment.id AND ipd_reciept_no.section_id IN(3,9)','left');

            $this->db->join('hms_doctors as ipd_attended_doc','ipd_attended_doc.id = ipd_booking.attend_doctor_id','left');
            //ipd

            //pathology
            $this->db->join('path_test_booking as path_booking','path_booking.id=hms_payment.parent_id AND path_booking.is_deleted=0','left');

            $this->db->join('hms_hospital as path_hospital','path_hospital.id = path_booking.referral_hospital','left');
            $this->db->join('hms_doctors as path_doctor','path_doctor.id=path_booking.referral_doctor','left');
                 /* get payment mode */
               //path_payment_mode
             $this->db->join('hms_payment_mode as path_payment_mode','path_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            $this->db->join('hms_branch_hospital_no as path_hospital_no','path_hospital_no.payment_id = hms_payment.id AND path_hospital_no.section_id IN(4,11)','left');

            $this->db->join('hms_doctors as path_attended_doc','path_attended_doc.id = path_booking.attended_doctor','left');
            //pathology

            //vaccination_booking

                /* get payment mode */
                //vaccination_payment_mode
                $this->db->join('hms_payment_mode as vaccination_payment_mode','vaccination_payment_mode.id = hms_payment.pay_mode','left');
                /* get payment mode */

            $this->db->join('hms_vaccination_sale as vaccination_booking','vaccination_booking.id=hms_payment.parent_id AND vaccination_booking.is_deleted=0','left');
            $this->db->join('hms_hospital as vaccination_hospital','vaccination_hospital.id = vaccination_booking.referral_hospital','left');
            $this->db->join('hms_doctors as vaccination_doctor','vaccination_doctor.id=vaccination_booking.refered_id','left');
            $this->db->join('hms_branch_hospital_no as vaccination_reciept_no','vaccination_reciept_no.payment_id = hms_payment.id AND vaccination_reciept_no.section_id IN(5,13)','left');
            //vaccination_booking


             /* operation booking */
            $this->db->join('hms_operation_booking as operation_booking','operation_booking.id=hms_payment.parent_id AND operation_booking.is_deleted=0','left');
            $this->db->join('hms_hospital as operation_booking_hospital','operation_booking_hospital.id = operation_booking.referral_hospital','left');
            $this->db->join('hms_doctors as operation_booking_doctor','operation_booking_doctor.id=operation_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as operation_booking_reciept_no','operation_booking_reciept_no.payment_id = hms_payment.id AND operation_booking_reciept_no.section_id IN (15,16)','left'); //13
                /* get payment mode */
                //vaccination_payment_mode
             $this->db->join('hms_payment_mode as operation_payment_mode','operation_payment_mode.id = hms_payment.pay_mode','left');
             $this->db->join('hms_doctors as ot_attended_doc','ot_attended_doc.id=operation_booking.doctor_id','left');
             /* operation booking */


                /* blood bank booking */
            $this->db->join('hms_blood_patient_to_recipient as recipient_booking','recipient_booking.id=hms_payment.parent_id AND recipient_booking.is_deleted=0','left');
            $this->db->join('hms_hospital as recipient_booking_hospital','recipient_booking_hospital.id = recipient_booking.hospital_id','left');
            $this->db->join('hms_doctors as recipient_booking_doctor','recipient_booking_doctor.id=recipient_booking.doctor_id','left');
            $this->db->join('hms_branch_hospital_no as receipent_booking_reciept_no','receipent_booking_reciept_no.payment_id = hms_payment.id AND receipent_booking_reciept_no.section_id IN (17,18)','left'); //13
                /* get payment mode */
             $this->db->join('hms_payment_mode as recepient_payment_mode','recepient_payment_mode.id = hms_payment.pay_mode','left');

             /* blood bank booking */





            //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
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
            $this->db->where('hms_payment.section_id IN (1,2,3,4,5,7,8,10)'); 
            //2 OPD,4 OPD Billing ,3 medicne sell,
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
            $new_branch_overall_array['over_all_branch_data'] = $this->db->get()->result_array(); 
            //echo $this->db->last_query(); exit; 

            /////////////////////// /* code for payment mode */////////////////////////////

            $users_data = $this->session->userdata('auth_users'); 
         

            //(CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name

            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_branch.branch_name,hms_patient.patient_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,

                (CASE WHEN hms_payment.section_id=1 THEN 'Pathology' WHEN hms_payment.section_id=2 THEN 'OPD' WHEN hms_payment.section_id=3 THEN 'Sale Medicine' WHEN hms_payment.section_id=4 THEN 'OPD Billing'  WHEN hms_payment.section_id=5 THEN 'IPD' WHEN hms_payment.section_id=7 THEN 'Vaccination' WHEN hms_payment.section_id=8 THEN 'OT' WHEN hms_payment.section_id=10 THEN 'Blood Bank' ELSE '' END) as section_name,
                (CASE 
                    
                    WHEN hms_payment.section_id=1 THEN (CASE WHEN path_booking.referred_by =1 THEN concat(path_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',path_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=2 THEN (CASE WHEN opd_booking.referred_by =1 THEN 
                    concat(opd_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',opd_doctor.doctor_name) END)
                    
                    WHEN hms_payment.section_id=3 THEN (CASE WHEN sell_medicne.referred_by =1 THEN 

                    concat(medicine_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',medicine_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=4 THEN (CASE WHEN opd_billing.referred_by =1 THEN 
                    concat(billing_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',billing_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=5 THEN (CASE WHEN ipd_booking.referred_by =1 THEN

                    concat(ipd_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',ipd_doctor.doctor_name) END)

                    WHEN hms_payment.section_id=7 THEN (CASE WHEN vaccination_booking.referred_by =1 THEN concat(vaccination_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',vaccination_doctor.doctor_name) END)

                     WHEN hms_payment.section_id=8 THEN (CASE WHEN operation_booking.referred_by =1 THEN concat(operation_booking_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',operation_booking_doctor.doctor_name) END)

                      WHEN hms_payment.section_id=10 THEN (CASE WHEN recipient_booking.referred_by =1 THEN concat(recipient_booking_hospital.hospital_name,' (Hospital)') ELSE  concat('Dr. ',recipient_booking_doctor.doctor_name) END)

                       
                ELSE 'N/A'
                END
                ) as doctor_hospital_name,

                (CASE 
                    WHEN hms_payment.section_id=1 THEN path_hospital_no.reciept_prefix 
                    WHEN hms_payment.section_id=2 THEN opd_hospital_no.reciept_prefix 
                    WHEN hms_payment.section_id=3 THEN medicine_reciept_no.reciept_prefix
                    WHEN hms_payment.section_id=4 THEN billing_reciept_no.reciept_prefix 
                    WHEN hms_payment.section_id=5 THEN ipd_reciept_no.reciept_prefix
                    WHEN hms_payment.section_id=7 THEN vaccination_reciept_no.reciept_prefix  
                    WHEN hms_payment.section_id=8 THEN operation_booking_reciept_no.reciept_prefix  
                    WHEN hms_payment.section_id=10 THEN receipent_booking_reciept_no.reciept_prefix     

                    ELSE '' END) as reciept_prefix,
                  (CASE 
                    WHEN hms_payment.section_id=1 THEN path_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=2 THEN opd_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=3 THEN medicine_payment_mode.payment_mode
                    WHEN hms_payment.section_id=4 THEN billing_payment_mode.payment_mode 
                    WHEN hms_payment.section_id=5 THEN ipd_payment_mode.payment_mode
                    WHEN hms_payment.section_id=7 THEN vaccination_payment_mode.payment_mode  
                     WHEN hms_payment.section_id=8 THEN operation_payment_mode.payment_mode  
                    WHEN hms_payment.section_id=10 THEN recepient_payment_mode.payment_mode 

                    ELSE '' END) as mode_name,

                (CASE 

                    WHEN hms_payment.section_id=1 THEN path_hospital_no.reciept_suffix 
                    WHEN hms_payment.section_id=2 THEN opd_hospital_no.reciept_suffix 
                    WHEN hms_payment.section_id=3 THEN medicine_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=4 THEN billing_reciept_no.reciept_suffix 
                    WHEN hms_payment.section_id=5 THEN ipd_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=7 THEN vaccination_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=8 THEN  operation_booking_reciept_no.reciept_suffix
                    WHEN hms_payment.section_id=10 THEN receipent_booking_reciept_no.reciept_suffix
                    ELSE '' END) as reciept_suffix
                "
                );

            //common doctor_name
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id');
            //OPD
            $this->db->join('hms_opd_booking as opd_booking','opd_booking.id=hms_payment.parent_id and opd_booking.is_deleted=0','left');
            /* get payment mode */
               //opd_payment_mode
             $this->db->join('hms_payment_mode as opd_payment_mode','opd_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            $this->db->join('hms_doctors as opd_doctor','opd_doctor.id=opd_booking.referral_doctor','left');
            $this->db->join('hms_hospital as opd_hospital','opd_hospital.id = opd_booking.referral_hospital','left');
            $this->db->join('hms_branch_hospital_no as opd_hospital_no','opd_hospital_no.payment_id = hms_payment.id AND opd_hospital_no.section_id IN(1,8)','left');
            //OPD

            //Billing
            $this->db->join('hms_opd_booking as opd_billing','opd_billing.id=hms_payment.parent_id and opd_billing.is_deleted=0','left');
            $this->db->join('hms_doctors billing_doctor','billing_doctor.id=opd_billing.referral_doctor','left');
            $this->db->join('hms_hospital billing_hospital','billing_hospital.id = opd_billing.referral_hospital','left');
              //billing_payment_mode
             $this->db->join('hms_payment_mode as billing_payment_mode','billing_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            //1 OPD , 2 OPD Billing,6 medicine sell
            $this->db->join('hms_branch_hospital_no as billing_reciept_no','billing_reciept_no.payment_id = hms_payment.id AND billing_reciept_no.section_id IN(2,12)','left');
            //Billing

            
            //medicine
            $this->db->join('hms_medicine_sale as sell_medicne','sell_medicne.id=hms_payment.parent_id AND sell_medicne.is_deleted=0','left');
             /* get payment mode */
               //medicine_payment_mode
             $this->db->join('hms_payment_mode as medicine_payment_mode','medicine_payment_mode.id = hms_payment.pay_mode','left');
             /* ipd_payment_mode */

            $this->db->join('hms_hospital as medicine_hospital','medicine_hospital.id = sell_medicne.referral_hospital','left');
            $this->db->join('hms_doctors as medicine_doctor','medicine_doctor.id=sell_medicne.refered_id','left');
            $this->db->join('hms_branch_hospital_no as medicine_reciept_no','medicine_reciept_no.payment_id = hms_payment.id AND medicine_reciept_no.section_id IN(6,10)','left');
            //medicine


            //ipd
            $this->db->join('hms_ipd_booking as ipd_booking','ipd_booking.id=hms_payment.parent_id AND ipd_booking.is_deleted=0','left');

            $this->db->join('hms_hospital as ipd_hospital','ipd_hospital.id = ipd_booking.referral_hospital','left');
             /* get payment mode */
               //ipd_payment_mode
             $this->db->join('hms_payment_mode as ipd_payment_mode','ipd_payment_mode.id = hms_payment.pay_mode','left');
             /* ipd_payment_mode */
            $this->db->join('hms_doctors as ipd_doctor','ipd_doctor.id=ipd_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as ipd_reciept_no','ipd_reciept_no.payment_id = hms_payment.id AND ipd_reciept_no.section_id IN(3,9)','left');
            //ipd

            //pathology
            $this->db->join('path_test_booking as path_booking','path_booking.id=hms_payment.parent_id AND path_booking.is_deleted=0','left');

            $this->db->join('hms_hospital as path_hospital','path_hospital.id = path_booking.referral_hospital','left');
            $this->db->join('hms_doctors as path_doctor','path_doctor.id=path_booking.referral_doctor','left');
                 /* get payment mode */
               //path_payment_mode
             $this->db->join('hms_payment_mode as path_payment_mode','path_payment_mode.id = hms_payment.pay_mode','left');
             /* get payment mode */
            $this->db->join('hms_branch_hospital_no as path_hospital_no','path_hospital_no.payment_id = hms_payment.id AND path_hospital_no.section_id IN(4,11)','left');
            //pathology

            //vaccination_booking

                /* get payment mode */
                //vaccination_payment_mode
                $this->db->join('hms_payment_mode as vaccination_payment_mode','vaccination_payment_mode.id = hms_payment.pay_mode','left');
                /* get payment mode */

            $this->db->join('hms_vaccination_sale as vaccination_booking','vaccination_booking.id=hms_payment.parent_id AND vaccination_booking.is_deleted=0','left');
            $this->db->join('hms_hospital as vaccination_hospital','vaccination_hospital.id = vaccination_booking.referral_hospital','left');
            $this->db->join('hms_doctors as vaccination_doctor','vaccination_doctor.id=vaccination_booking.refered_id','left');
            $this->db->join('hms_branch_hospital_no as vaccination_reciept_no','vaccination_reciept_no.payment_id = hms_payment.id AND vaccination_reciept_no.section_id IN(5,13)','left');
            //vaccination_booking


               /* operation booking */
            $this->db->join('hms_operation_booking as operation_booking','operation_booking.id=hms_payment.parent_id AND operation_booking.is_deleted=0','left');
            $this->db->join('hms_hospital as operation_booking_hospital','operation_booking_hospital.id = operation_booking.referral_hospital','left');
            $this->db->join('hms_doctors as operation_booking_doctor','operation_booking_doctor.id=operation_booking.referral_doctor','left');
            $this->db->join('hms_branch_hospital_no as operation_booking_reciept_no','operation_booking_reciept_no.payment_id = hms_payment.id AND operation_booking_reciept_no.section_id IN (15,16)','left'); //13
                /* get payment mode */
                //vaccination_payment_mode
             $this->db->join('hms_payment_mode as operation_payment_mode','operation_payment_mode.id = hms_payment.pay_mode','left');

             /* operation booking */


                /* blood bank booking */
            $this->db->join('hms_blood_patient_to_recipient as recipient_booking','recipient_booking.id=hms_payment.parent_id AND recipient_booking.is_deleted=0','left');
            $this->db->join('hms_hospital as recipient_booking_hospital','recipient_booking_hospital.id = recipient_booking.hospital_id','left');
            $this->db->join('hms_doctors as recipient_booking_doctor','recipient_booking_doctor.id=recipient_booking.doctor_id','left');
            $this->db->join('hms_branch_hospital_no as receipent_booking_reciept_no','receipent_booking_reciept_no.payment_id = hms_payment.id AND receipent_booking_reciept_no.section_id IN (17,18)','left'); //13
                /* get payment mode */
             $this->db->join('hms_payment_mode as recepient_payment_mode','recepient_payment_mode.id = hms_payment.pay_mode','left');

             /* blood bank booking */

            //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
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
            $this->db->where('hms_payment.section_id IN (1,2,3,4,5,7,8,10)'); 
            //2 OPD,4 OPD Billing ,3 medicne sell,
            $this->db->where('hms_payment.debit>0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_branch_overall_array['over_all_branch_data_payment_mode'] = $this->db->get()->result_array(); 

            /////////////////////// /* code for payment mode */////////////////////////////


            return $new_branch_overall_array;

            /* ends */
        } 
    }

    /* hospital sub branch report end */


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


    /* self_blood_bank_collection_list*/

    public function self_blood_bank_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
       
        $new_self_blood=array();
        if(!empty($get))
        {  
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_blood_patient_to_recipient.requirement_date as booking_date,hms_blood_patient_to_recipient.issue_code as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_blood_patient_to_recipient.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)') ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_blood_patient_to_recipient.doctor_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_blood_patient_to_recipient.hospital_id','left');

             /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (17,18)','left');*/
   
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
            /*if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }*/
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
            $new_self_blood['self_blood_bank_collection'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

           /* self payment mode */

           $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_blood_patient_to_recipient.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)') ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            

            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_doctors','hms_doctors.id=hms_blood_patient_to_recipient.doctor_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_blood_patient_to_recipient.hospital_id','left');

             /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (17,18)','left');*/
   
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
           /* if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }*/

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
            $this->db->from('hms_payment');
            $this->db->group_by('hms_payment.pay_mode');
            $new_self_blood['self_blood_bank_coll_payment_mode'] = $this->db->get()->result();
            //echo $this->db->last_query();die;

           /* self vaccination payment mode */
            return $new_self_blood;
        } 
    }


    /* self_blood_bank_collection_list */

     //blood bank branch collection
    public function blood_bank_branch_collection_list($get="",$ids=[])
    {
         $new_array_blood_bank=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id,hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_blood_patient_to_recipient.requirement_date as booking_date,hms_blood_patient_to_recipient.issue_code as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_blood_patient_to_recipient.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)') ELSE concat('Dr. ',hms_doctors.doctor_name)END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_blood_patient_to_recipient.doctor_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_blood_patient_to_recipient.hospital_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (17,18)','left');*/
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            $this->db->where('hms_blood_patient_to_recipient.is_deleted','0');
            $this->db->where('hms_blood_patient_to_recipient.branch_id IN ('.$branch_id.')'); 
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
            $this->db->where('hms_payment.section_id IN (10)'); 
            $this->db->where('hms_payment.debit>0');
            $this->db->from('hms_payment');
            $new_array_blood_bank['blood_bank_collection'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

            /* payment mode ot */
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id,hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_blood_patient_to_recipient.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)') ELSE concat('Dr. ',hms_doctors.doctor_name)END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_blood_patient_to_recipient.doctor_id','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_blood_patient_to_recipient.hospital_id','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (17,18)','left');*/

            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            $this->db->where('hms_blood_patient_to_recipient.is_deleted','0');
            $this->db->where('hms_blood_patient_to_recipient.branch_id IN ('.$branch_id.')'); 
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
            $this->db->where('hms_payment.section_id IN (10)'); 
            $this->db->where('hms_payment.debit>0');
             $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_array_blood_bank['blood_bank_coll_payment_mode'] = $this->db->get()->result(); 
             /* payment mode ot */
            return $new_array_blood_bank;
            
        } 
    }
    //blood bank branch collection
    
    
    public function self_ambulance_collection_list($get="")
    {

        $users_data = $this->session->userdata('auth_users');
        $new_self_ambulance_array=array(); 
        if(!empty($get))
        {  
           $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_ambulance_booking.booking_date,hms_ambulance_booking.booking_no,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ambulance_booking.reffered_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ambulance_booking.reffered=0 THEN  concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");  

            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id','left');*/

           
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
            $new_self_ambulance_array['self_ambulance_coll']= $this->db->get()->result(); 
          // echo $this->db->last_query();die;

            /* code from payment_mode by */

             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name, sum(hms_payment.debit) as to_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ambulance_booking.reffered_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ambulance_booking.reffered=0 THEN concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (1,8)','left');*/
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

            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (13)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->group_by('hms_payment.pay_mode'); 
            $this->db->from('hms_payment');
            $new_self_ambulance_array['self_ambulance_coll_payment_mode']= $this->db->get()->result(); 
           // echo $this->db->last_query();die;
            /* code from payment_mode by */

           //print '<pre>'; print_r($new_payment_mode);die;
           //echo $this->db->last_query(); exit; 
            return $new_self_ambulance_array;
            
        } 
    }
    
    
    public function branch_ambulance_collection_list($get="",$ids=[])
    {
        $new_payment_mode=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            
           
            $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_ambulance_booking.booking_date,hms_ambulance_booking.booking_no,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ambulance_booking.reffered_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ambulance_booking.reffered=0 THEN  concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");  

            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id','left');*/

           
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_ambulance_booking.is_deleted',0); 
            $this->db->where('hms_ambulance_booking.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
             
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
            $new_payment_mode['ambulance_collection_list']= $this->db->get()->result(); 
            //echo $this->db->last_query();die;

            /* code from payment_mode by */

             $this->db->select("hms_payment.reciept_prefix,hms_payment.reciept_suffix, hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name, sum(hms_payment.debit) as to_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_ambulance_booking.reffered_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_ambulance_booking.reffered=0 THEN  concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_ambulance_booking.reffered','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
            /*$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (1,8)','left');*/
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_ambulance_booking.is_deleted',0); 
            $this->db->where('hms_ambulance_booking.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
             
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

            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (13)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->group_by('hms_payment.pay_mode'); 
            $this->db->from('hms_payment');
            $new_payment_mode['payment_mode_list']= $this->db->get()->result(); 
            /* code from payment_mode by */

           //print '<pre>'; print_r($new_payment_mode);die;
           //echo $this->db->last_query(); exit; 
            return $new_payment_mode;
            
        } 
    }
    
    
    // 0 Expenses
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
            $this->db->select("hms_expenses.vouchar_no, hms_medicine_vendors.name as type, hms_expenses.paid_amount,hms_expenses_category.exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name");
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('hms_expenses.type',0);
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
              $this->db->select("(CASE WHEN hms_payment.section_id=2 THEN '' ELSE '' END) as vouchar_no, (CASE WHEN hms_payment.section_id=2 THEN 'Doctor Commision' ELSE '' END) as type, hms_payment.debit as paid_amount, (CASE WHEN hms_payment.section_id=2 THEN 'N/A' ELSE '' END) as exp_category, hms_payment.created_date, DATE_FORMAT(hms_payment.created_date,'%Y-%m-%d') as expenses_date, hms_payment_mode.payment_mode as payment_mode, (CASE WHEN hms_payment.section_id=2 THEN '' ELSE '' END) as remarks");
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
            $result_expense['doctor_commission']= $this->db->get()->result();

             $this->db->select("sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
        
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
            $this->db->where('hms_payment.section_id IN (2)');
            $this->db->where('hms_payment.parent_id','0');
            $this->db->where('hms_payment.doctor_id > 0');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $result_expense['doc_payment_mode'] = $this->db->get()->result(); 
            return $result_expense;
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
       
            $this->db->select("hms_expenses.vouchar_no,(CASE WHEN hms_expenses.type=1 THEN 'Salary' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=1 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks");
            $this->db->from('hms_expenses'); 
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('hms_expenses.type',1);
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("(CASE WHEN hms_expenses.type=2 THEN (select purchase_id from hms_medicine_purchase where id=hms_expenses.parent_id) ELSE hms_expenses.vouchar_no END) as vouchar_no,(CASE WHEN hms_expenses.type=2 THEN 'Purchase Medicine' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=2 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_medicine_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_medicine_purchase` ON `hms_medicine_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 2) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=2 OR hms_expenses.department_type=2)');
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=2 OR hms_expenses.department_type=2)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=3 THEN 'Sale Return' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=3 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=3 OR hms_expenses.department_type=3)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
           $this->db->where('(hms_expenses.type=3 OR hms_expenses.department_type=3)'); 
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=4 THEN 'Purchase Stcok Inventroy' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=4 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=4 OR hms_expenses.department_type=4)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=4 OR hms_expenses.department_type=4)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=5 THEN 'Vaccine Purchase' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=5 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_vaccination_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_vaccination_purchase` ON `hms_vaccination_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 5) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
           $this->db->where('(hms_expenses.type=5 OR hms_expenses.department_type=5)'); 
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=5 OR hms_expenses.department_type=5)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=6 THEN 'Vaccine Sale Return' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=6 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=6 OR hms_expenses.department_type=6)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=6 OR hms_expenses.department_type=6)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=7 THEN 'OPD Payment Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=7 THEN 'N/A' ELSE '' END) as exp_category,hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_opd_booking`.`booking_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id` AND section_id=2 WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 7) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=7 OR hms_expenses.department_type=7)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=7 OR hms_expenses.department_type=7)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=8 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `path_test_booking`.`lab_reg_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 8) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=8 OR hms_expenses.department_type=8)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=8 OR hms_expenses.department_type=8)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=9 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_medicine_sale`.`sale_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 9)
 as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=9 OR hms_expenses.department_type=9)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=9 OR hms_expenses.department_type=9)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=10 THEN 'IPD Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=10 THEN 'N/A' ELSE '' END) as exp_category,hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_ipd_booking`.`ipd_no` FROM `hms_expenses` as exp LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `exp`.`refund_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 10) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=10 OR hms_expenses.department_type=10)');  
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
            //echo $this->db->last_query(); exit;
            $result_expense['expense_list'] = $query->result(); 

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=10 OR hms_expenses.department_type=10)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no,(CASE WHEN hms_expenses.type=11 THEN 'OT Payment Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=11 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_ot_booking_ot_details`.`code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ot_booking_ot_details` ON `hms_ot_booking_ot_details`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 11) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=11 OR hms_expenses.department_type=11)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
           $this->db->where('(hms_expenses.type=11 OR hms_expenses.department_type=11)'); 
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no,(CASE WHEN hms_expenses.type=12 THEN 'Blood Bank Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=12 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=12 OR hms_expenses.department_type=12)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=12 OR hms_expenses.department_type=12)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=13 THEN 'Ambulance Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=13 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_ambulance_booking`.`booking_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ambulance_booking` ON `hms_ambulance_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 13) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=13 OR hms_expenses.department_type=13)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=13 OR hms_expenses.department_type=13)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=14 THEN 'OPD Billing Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=14 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_opd_booking`.`reciept_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 14) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
           $this->db->where('(hms_expenses.type=14 OR hms_expenses.department_type=14)'); 
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=14 OR hms_expenses.department_type=14)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=15 THEN 'Day Care Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=15 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name");
            $this->db->from('hms_expenses'); 
            $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
            $this->db->join("hms_medicine_vendors","hms_medicine_vendors.id = hms_expenses.vendor_id",'left');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=15 OR hms_expenses.department_type=15)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
           $this->db->where('(hms_expenses.type=15 OR hms_expenses.department_type=15)'); 
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
            return $result_expense;           
        } 
    }
    
    
    // indivisual expenses 

    public function get_exp_doctor_commission($get=array())
    {
            $users_data = $this->session->userdata('auth_users'); 
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            if(!empty($sub_branch_details))
            {
                $child_ids_arr = array_column($sub_branch_details,'id');
                $child_ids = implode(',',$child_ids_arr);
            } 
             $this->db->select("hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode");
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
            $result_expense['doctor_commission']= $this->db->get()->result();

             $this->db->select("sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
        
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
            $this->db->where('hms_payment.section_id IN (2)');
            $this->db->where('hms_payment.parent_id','0');
            $this->db->where('hms_payment.doctor_id > 0');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $result_expense['doc_payment_mode'] = $this->db->get()->result(); 
            return $result_expense;
    }
    
    
    public function self_day_care_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users');
        $new_self_day_care_array=array(); 
        if(!empty($get))
        {  
            
            $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_day_care_booking.booking_date,hms_day_care_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit,hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_day_care_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_day_care_booking.referral_doctor=0 THEN concat('Other ',hms_day_care_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_day_care_booking.attended_doctor','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_hospital','hms_hospital.id = hms_day_care_booking.referral_hospital','left');
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (23)','left');

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
            //$this->db->where('hms_opd_booking.type',3);
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
            //$this->db->order_by('hms_payment.id','DESC');
            $this->db->from('hms_payment');
            $new_self_day_care_array['self_day_care_coll'] = $this->db->get()->result(); 

   // echo $this->db->last_query(); exit; 

            /* self day_care collection payment */

            $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_day_care_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_day_care_booking.referral_doctor=0 THEN concat('Other ',hms_day_care_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_day_care_booking.attended_doctor','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_hospital','hms_hospital.id = hms_day_care_booking.referral_hospital','left');
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (23)','left');

            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
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
            $this->db->where('hms_day_care_booking.type',5);
            $this->db->where('hms_day_care_booking.is_deleted',0); 
            $this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
            $this->db->where('hms_payment.section_id IN (14)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_self_day_care_array['self_day_care_coll_payment_mode'] = $this->db->get()->result(); 

             /* self day_care collection payment */

            return $new_self_day_care_array;
        } 
    }

    public function branch_day_care_collection_list($get="",$ids=[])
    {
        $new_payment_mode=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
            

            $this->db->select("hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_day_care_booking.booking_date,hms_day_care_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount,hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_day_care_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_day_care_booking.referral_doctor=0 THEN concat('Other ',hms_day_care_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_payment_mode.payment_mode as mode");  

            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_day_care_booking.attended_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_day_care_booking.referral_hospital','left');
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (23)','left');

           
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_day_care_booking.is_deleted',0); 
            $this->db->where('hms_day_care_booking.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
             
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
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (14)');
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
            $new_payment_mode['day_care_collection_list']= $this->db->get()->result(); 

            /* code from payment_mode by */

             $this->db->select("hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id, hms_doctors.doctor_name, sum(hms_payment.debit) as to_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_day_care_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_day_care_booking.referral_doctor=0 THEN concat('Other ',hms_day_care_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
            $this->db->join('hms_doctors','hms_doctors.id=hms_day_care_booking.attended_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_day_care_booking.referral_hospital','left');
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (23)','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_day_care_booking.is_deleted',0); 
            $this->db->where('hms_day_care_booking.branch_id IN ('.$branch_id.')');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
             
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

            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (14)');
            $this->db->where('hms_payment.debit>0'); 
            $this->db->group_by('hms_payment.pay_mode'); 
            $this->db->from('hms_payment');
            $new_payment_mode['payment_mode_list']= $this->db->get()->result(); 
            /* code from payment_mode by */

           //print '<pre>'; print_r($new_payment_mode);die;
   //echo $this->db->last_query(); exit; 
            return $new_payment_mode;
            
        } 
    }
    
    
    public function self_purchase_collection_list($get="")
    {
        $new_purchase_array=array();
      $users_data = $this->session->userdata('auth_users');
            $branch_id = $users_data['parent_id']; 
            
            $this->db->select("hms_medicine_vendors.name as patient_name, hms_medicine_vendors.vendor_id as patient_code, hms_medicine_vendors.mobile as mobile_no,hms_medicine_return.purchase_date as booking_date,hms_medicine_return.return_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,,hms_payment_mode.payment_mode as mode");
 
            
           // $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_payment.vendor_id','left'); 
            $this->db->join('hms_medicine_return','hms_medicine_return.id=hms_payment.parent_id AND hms_medicine_return.is_deleted=0');
          

            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');

            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
           // $this->db->where('hms_medicine_return.branch_id','0'); 
            $this->db->where('hms_medicine_return.branch_id IN ('.$branch_id.')');
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
            $new_purchase_array['self_purchase_coll'] = $this->db->get()->result(); 
           // echo $this->db->last_query(); exit; 


            /* medicine sale collection list */

            $this->db->select("hms_medicine_vendors.name as patient_name,hms_branch.branch_name,hms_branch.id as branch_id,sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
            //(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_payment.vendor_id','left'); 
            $this->db->join('hms_medicine_return','hms_medicine_return.id=hms_payment.parent_id');
            
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');

            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
          //  $this->db->where('hms_medicine_return.branch_id','0'); 
            $this->db->where('hms_medicine_return.branch_id IN ('.$branch_id.')');
           // $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (12)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_purchase_array['purchase_payment_mode_array'] = $this->db->get()->result(); 
//echo $this->db->last_query(); exit();
            /* medicine sale collection list */
            return $new_purchase_array;
            
        
    }
    
    
    
    public function self_dialysis_collection_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $new_self_ot_array=array();
        if(!empty($get))
        {  
            $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN 'Yes' ELSE 'No' END ) as panel_type,hms_dialysis_booking.dialysis_date as booking_date,hms_dialysis_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,(CASE WHEN hms_dialysis_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_dialysis_booking.referral_doctor=0 THEN concat('Other ',hms_dialysis_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
           
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_dialysis_booking.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (24)','left');
   
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
            $this->db->where('hms_payment.section_id IN (15)'); 
            $this->db->where('hms_dialysis_booking.is_deleted','0');
            $this->db->where('hms_dialysis_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit > 0');
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
            //$this->db->group_by('hms_payment.id');
            $new_self_ot_array['self_dialysis_coll'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit;
          
             $this->db->select("hms_patient.patient_name, hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_dialysis_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_dialysis_booking.referral_doctor=0 THEN concat('Other ',hms_dialysis_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_dialysis_booking.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left'); 
           // $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (24)','left');
   
            $this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
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
            $this->db->where('hms_payment.section_id IN (15)'); 
            $this->db->where('hms_dialysis_booking.is_deleted','0');
            $this->db->where('hms_dialysis_booking.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            //$this->db->group_by('hms_payment.id');
           // echo $this->db->last_query();die;
            $new_self_ot_array['self_dialysis_coll_payment_mode'] = $this->db->get()->result(); 

             /* self ot collection payment_mode*/



            return $new_self_ot_array;
        } 
    }



public function branch_dialysis_collection_list($get="",$ids=[])
    {
         $new_array_ot=array();
        if(!empty($ids))
        { 
            $branch_id = implode(',', $ids); 
         
            $this->db->select("hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id,hms_doctors.doctor_name,hms_patient.mobile_no,hms_patient.patient_code,(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type,hms_dialysis_booking.dialysis_date as booking_date,hms_dialysis_booking.booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,(CASE WHEN hms_dialysis_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_dialysis_booking.referral_doctor=0 THEN concat('Other ',hms_dialysis_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode");

            
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_dialysis_booking.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (24)','left');

            // $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_dialysis_booking.id AND hms_branch_hospital_no.section_id IN(16,15)','left');



            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            $this->db->where('hms_dialysis_booking.branch_id','0');
            $this->db->where('hms_dialysis_booking.branch_id IN ('.$branch_id.')');
            //$this->db->where('hms_payment.parent_id',0); 
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
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (15)'); 
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
            $new_array_ot['dialysis_collection'] = $this->db->get()->result(); 
            //echo $this->db->last_query(); exit; 

            /* payment mode ot */

              $this->db->select("hms_patient.patient_name,hms_branch.branch_name,hms_branch.id as branch_id,hms_doctors.doctor_name, sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,(CASE WHEN hms_dialysis_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_dialysis_booking.referral_doctor=0 THEN concat('Other ',hms_dialysis_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,hms_payment_mode.payment_mode as mode"); 
            //(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
            $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_payment.parent_id');
            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');

            $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.referral_doctor','left');
            $this->db->join('hms_hospital','hms_hospital.id = hms_dialysis_booking.referral_hospital','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            
            //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id IN (24)','left');
            
            $this->db->where('hms_dialysis_booking.branch_id','0');
            $this->db->where('hms_dialysis_booking.branch_id IN ('.$branch_id.')');

             $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
            $this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
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
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (15)'); 
            $this->db->where('hms_payment.debit>0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_array_ot['dialysis_collection_payment_mode'] = $this->db->get()->result(); 


            /* payment mode ot */
            return $new_array_ot;
            
        } 
    }


     public function self_inven_pur_ret_collection_list($get="")
    {
        $new_purchase_array=array();
        $users_data = $this->session->userdata('auth_users');
            $branch_id = $users_data['parent_id']; 
            
            $this->db->select("hms_medicine_vendors.name as patient_name, hms_medicine_vendors.vendor_id as patient_code, hms_medicine_vendors.mobile as mobile_no,path_purchase_return_item.purchase_date as booking_date,path_purchase_return_item.return_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,,hms_payment_mode.payment_mode as mode");

            $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_payment.vendor_id','left'); 
            $this->db->join('path_purchase_return_item','path_purchase_return_item.id=hms_payment.parent_id AND path_purchase_return_item.is_deleted=0');
          

            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');

            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
           // $this->db->where('path_purchase_return_item.branch_id','0'); 
            $this->db->where('path_purchase_return_item.branch_id IN ('.$branch_id.')');
         
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
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (6)'); 
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
            $new_purchase_array['invent_pur_ret_coll'] = $this->db->get()->result(); 
           // echo $this->db->last_query(); exit; 


            /* medicine sale collection list */
            $this->db->select("hms_medicine_vendors.name as patient_name,hms_branch.branch_name,hms_branch.id as branch_id,sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_payment.vendor_id','left'); 
            $this->db->join('path_purchase_return_item','path_purchase_return_item.id=hms_payment.parent_id');
         
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')');  
            $this->db->where('path_purchase_return_item.branch_id IN ('.$branch_id.')');
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
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (6)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_purchase_array['invent_pur_ret_pay_mode'] = $this->db->get()->result(); 
            return $new_purchase_array;         
        
    }

    public function self_vaccination_pur_ret_collection_list($get="")
    {
        $new_purchase_array=array();
        $users_data = $this->session->userdata('auth_users');
            $branch_id = $users_data['parent_id'];             
            $this->db->select("hms_medicine_vendors.name as patient_name, hms_medicine_vendors.vendor_id as patient_code, hms_medicine_vendors.mobile as mobile_no,hms_vaccination_purchase_return.purchase_date as booking_date,hms_vaccination_purchase_return.return_no as booking_code,hms_payment.discount_amount,hms_payment.net_amount,hms_payment.balance,hms_payment.total_amount, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode,,hms_payment_mode.payment_mode as mode");
            $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_payment.vendor_id','left'); 
            $this->db->join('hms_vaccination_purchase_return','hms_vaccination_purchase_return.id=hms_payment.parent_id AND hms_vaccination_purchase_return.is_deleted=0');         

            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');

            //$this->db->where('hms_payment.doctor_id',0); 
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')'); 
           // $this->db->where('hms_vaccination_purchase_return.branch_id','0'); 
            $this->db->where('hms_vaccination_purchase_return.branch_id IN ('.$branch_id.')');
         
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
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (15)'); 
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
            $new_purchase_array['invent_pur_ret_coll'] = $this->db->get()->result(); 
           // echo $this->db->last_query(); exit; 
            /* medicine sale collection list */
            $this->db->select("hms_medicine_vendors.name as patient_name,hms_branch.branch_name,hms_branch.id as branch_id,sum(hms_payment.debit) as tot_debit, hms_payment.created_date, hms_payment.pay_mode,hms_payment_mode.payment_mode as mode"); 
            $this->db->join('hms_branch','hms_branch.id=hms_payment.branch_id','left');
            $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_payment.vendor_id','left'); 
            $this->db->join('hms_vaccination_purchase_return','hms_vaccination_purchase_return.id=hms_payment.parent_id');
         
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
            $this->db->where('hms_payment.branch_id IN ('.$branch_id.')');  
            $this->db->where('hms_vaccination_purchase_return.branch_id IN ('.$branch_id.')');
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
//user collection
            if(!empty($get['employee']))
            {
                $this->db->where('hms_payment.created_by = "'.$get['employee'].'"');
            }
            $this->db->where('hms_payment.section_id IN (15)'); 
            $this->db->where('hms_payment.debit > 0');
            $this->db->group_by('hms_payment.pay_mode');
            $this->db->from('hms_payment');
            $new_purchase_array['invent_pur_ret_pay_mode'] = $this->db->get()->result(); 
            return $new_purchase_array;         
        
    }
    
    //////////
    public function get_dialysis_expenses_details($get=array())
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
       
            $this->db->select("hms_expenses.vouchar_no, (CASE WHEN hms_expenses.type=13 THEN 'Ambulance Refund' ELSE '' END) as type, hms_expenses.paid_amount,(CASE WHEN hms_expenses.type=13 THEN 'N/A' ELSE '' END) as exp_category, hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode, hms_expenses.remarks,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,(SELECT `hms_ambulance_booking`.`booking_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ambulance_booking` ON `hms_ambulance_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 13) as book_code");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('(hms_expenses.type=18 OR hms_expenses.department_type=18)');  
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

            $this->db->select("hms_expenses.created_date,hms_expenses.expenses_date,hms_payment_mode.payment_mode,sum(hms_expenses.paid_amount) as paid_total_amount");
            $this->db->from('hms_expenses'); 
            $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode','left');
            $this->db->where('hms_expenses.paid_amount>0');
            $this->db->where('hms_expenses.is_deleted!=2');
            $this->db->where('(hms_expenses.type=18 OR hms_expenses.department_type=18)');  
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
            $this->db->group_by('hms_expenses.payment_mode');
            $result_expense['expense_payment_mode'] = $this->db->get()->result();  
            return $result_expense;           
        } 
    }
    




}
?>