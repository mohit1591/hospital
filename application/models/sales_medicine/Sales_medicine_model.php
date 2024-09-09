<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_medicine_model extends CI_Model 
{
  var $table = 'hms_medicine_sale';
  var $column = array('hms_medicine_sale.id', 'hms_patient.patient_name','hms_medicine_sale.sale_no','hms_medicine_sale.id','hms_medicine_sale.total_amount','hms_medicine_sale.net_amount','hms_medicine_sale.paid_amount','hms_medicine_sale.balance','hms_medicine_sale.created_date', 'hms_medicine_sale.modified_date','hms_patient.patient_code');  

  var $order = array('id' => 'desc'); 

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function diseases_list($branch_id="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_disease.disease','ASC');
		$this->db->where('hms_disease.status',1);
		$this->db->where('hms_disease.is_deleted',0);
		if(!empty($branch_id))
		{
			$this->db->where('hms_disease.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_disease.branch_id',$users_data['parent_id']);	
		}
		  
		$query = $this->db->get('hms_disease');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

  private function _get_datatables_query()
  {
    $search = $this->session->userdata('sale_search');
    // print_r($search); die;
    $user_data = $this->session->userdata('auth_users');
    
    $this->db->select("hms_medicine_sale.*, hms_patient_category.patient_category, hms_patient.address, hms_disease.disease, hms_patient.patient_name, (select count(id) from hms_medicine_sale_to_medicine where sales_id = hms_medicine_sale.id) as total_medicine,

      (CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale.refered_id=0 THEN concat('Other ',hms_medicine_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name
      "); 
    $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
    $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
    $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');
    $this->db->join('hms_patient_category','hms_patient_category.id = hms_medicine_sale.patient_category','left'); 
    $this->db->join('hms_disease','hms_disease.id = hms_medicine_sale.diseases','left'); 
    $this->db->from($this->table);
    $this->db->where('hms_medicine_sale.is_deleted','0'); 
    if(isset($search['disease_id']) && $search['disease_id']!='')
    {
      $this->db->where('hms_medicine_sale.diseases = "'.$search['disease_id'].'"');
    }
    if(isset($search['patient_category']) && $search['patient_category']!='')
    {
      $this->db->where('hms_medicine_sale.patient_category = "'.$search['patient_category'].'"');
    }
    if($user_data['users_role']==4)
    {
      if(isset($search['branch_id']) && $search['branch_id']!=''){
      $this->db->where('hms_medicine_sale.patient_id IN ('.$search['branch_id'].')');
      }else{
      $this->db->where('hms_medicine_sale.patient_id = "'.$user_data['parent_id'].'"');
      } 
    }
    else
    {
      if(isset($search['branch_id']) && $search['branch_id']!='')
      {
        $this->db->where('hms_medicine_sale.branch_id IN ('.$search['branch_id'].')');
      }
      else
      {
        $this->db->where('hms_medicine_sale.branch_id = "'.$user_data['parent_id'].'"');
      }
    }
    if(isset($search) && !empty($search))
    {
      
      if(isset($search['start_date']) && !empty($search['start_date']))
      {
          $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
          $this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
      }

      if(isset($search['end_date']) &&  !empty($search['end_date']))
      {
          $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
          $this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
      }
      if(isset($search['patient_name']) &&  !empty($search['patient_name']))
      {
          $this->db->where('hms_patient.patient_name',$search['patient_name']);
      }
      if(isset($search['patient_code']) &&  !empty($search['patient_code']))
      {
        
        $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
      }
      if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
      {
        $this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
      }

      if($search['insurance_type']!="")
      {
        $this->db->where('hms_patient.insurance_type',$search['insurance_type']);
      }

      if(!empty($search['insurance_type_id']))
      {
        $this->db->where('hms_patient.insurance_type_id',$search['insurance_type_id']);
      }

      if(!empty($search['ins_company_id']))
      {
        $this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
      }

          
      if($search['referred_by']=='1' && !empty($search['referral_hospital']))
      {
        $this->db->where('hms_medicine_sale.referral_hospital' ,$search['referral_hospital']);
      }
      elseif($search['referred_by']=='0' && !empty($search['refered_id']))
      {
        $this->db->where('hms_medicine_sale.refered_id' ,$search['refered_id']);
      }
      if(isset($search['sale_no']) &&  !empty($search['sale_no']))
      {
        $this->db->where('hms_medicine_sale.sale_no LIKE "'.$search['sale_no'].'%"');
      }

      if(isset($search['medicine_name']) &&  !empty($search['medicine_name']))
      {
        $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
      }

      if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
      {
        if(empty($search['medicine_name']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        }
        
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left'); 
        $this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
      }

      if(isset($search['medicine_code']) &&  !empty($search['medicine_code']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
      }
      
      if(isset($search['discount']) &&  !empty($search['discount']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']))
        {
        $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
        }
        $this->db->where('hms_medicine_sale_to_medicine.discount = "'.$search['discount'].'"');
      }
      if(isset($search['batch_no']) &&  !empty($search['batch_no']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
        {
        $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
        }
        $this->db->where('hms_medicine_sale_to_medicine.batch_no = "'.$search['batch_no'].'"');
      }
      
      if(isset($search['packing']) && $search['packing']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])  && empty($search['batch_no']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        }
        $this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
      }

      if(isset($search['conversion']) &&  $search['conversion']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
      }

      if(isset($search['mrp_to']) &&  $search['mrp_to']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
      }
      if(isset($search['mrp_from']) &&  $search['mrp_from']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        }
        $this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
      }

      if(isset($search['cgst']) &&  !empty($search['cgst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        }
          $this->db->where('hms_medicine_sale_to_medicine.cgst', $search['cgst']);
      }
      if(isset($search['igst']) &&  !empty($search['igst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']))
          {
            $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
          }
          $this->db->where('hms_medicine_sale_to_medicine.igst', $search['igst']);
      }
      if(isset($search['sgst']) &&  !empty($search['sgst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']) && empty($search['igst']))
          {
            $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
          }
          $this->db->where('hms_medicine_sale_to_medicine.sgst', $search['sgst']);
      }


      if(isset($search['paid_amount_to']) &&  $search['paid_amount_to']!="")
      {
        $this->db->where('hms_medicine_sale.paid_amount >="'.$search['paid_amount_to'].'"');
      }
      if(isset($search['paid_amount_from']) &&  $search['paid_amount_from']!="")
      {
        $this->db->where('hms_medicine_sale.paid_amount <="'.$search['paid_amount_from'].'"');
      }

      if(isset($search['balance_to']) &&  $search['balance_to']!="")
      {
        $this->db->where('hms_medicine_sale.balance >="'.$search['balance_to'].'"');
      }
      if(isset($search['balance_from']) && $search['balance_from']!="")
      {
        $this->db->where('hms_medicine_sale.balance <="'.$search['balance_from'].'"');
      }

      if(isset($search['total_amount_to']) &&  $search['total_amount_to']!="")
      {
        $this->db->where('hms_medicine_sale.total_amount >="'.$search['total_amount_to'].'"');
      }
      if(isset($search['total_amount_from']) &&  $search['total_amount_from']!="")
      {
        $this->db->where('hms_medicine_sale.total_amount <="'.$search['total_amount_from'].'"');
      }

      if(isset($search['bank_name']) && $search['bank_name']!="")
      {
        $this->db->where('hms_medicine_sale.bank_name',$search['bank_name']);
      }


    }
    else
    {

    }
    
    $emp_ids='';
    if($user_data['emp_id']>0)
    {
      if($user_data['record_access']=='1')
      {
        $emp_ids= $user_data['id'];
      }
    }
    elseif(!empty($search["employee"]) && is_numeric($search['employee']))
    {
      $emp_ids=  $search["employee"];
    }
    if(isset($emp_ids) && !empty($emp_ids))
    { 
      $this->db->where('hms_medicine_sale.created_by IN ('.$emp_ids.')');
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

   function search_report_data(){
    $search = $this->session->userdata('sale_search');
    //print_r($search);die;
    $user_data = $this->session->userdata('auth_users');
    $this->db->select("hms_medicine_sale.*, hms_patient_category.patient_category, hms_patient.address, hms_disease.disease, hms_patient.patient_name, (select count(id) from hms_medicine_sale_to_medicine where sales_id = hms_medicine_sale.id) as total_medicine,(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale.refered_id=0 THEN concat('Other ',hms_medicine_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name"); 
    $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left');
    //(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name 
    
    
    $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
    $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');
    $this->db->join('hms_patient_category','hms_patient_category.id = hms_medicine_sale.patient_category','left'); 
    $this->db->join('hms_disease','hms_disease.id = hms_medicine_sale.diseases','left');
    $this->db->from($this->table);
    $this->db->where('hms_medicine_sale.is_deleted','0'); 
    if($user_data['users_role']==4)
    {
      if(isset($search['branch_id']) && $search['branch_id']!=''){
      $this->db->where('hms_medicine_sale.patient_id IN ('.$search['branch_id'].')');
      }else{
      $this->db->where('hms_medicine_sale.patient_id = "'.$user_data['parent_id'].'"');
      }
    }
    else
    {
      if(isset($search['branch_id']) && $search['branch_id']!=''){
      $this->db->where('hms_medicine_sale.branch_id IN ('.$search['branch_id'].')');
      }else{
      $this->db->where('hms_medicine_sale.branch_id = "'.$user_data['parent_id'].'"');
      }
    }
    if(isset($search) && !empty($search))
    {
      
      if(isset($search['start_date']) && !empty($search['start_date']))
      {
        $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
        $this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
      }

      if(isset($search['end_date']) &&  !empty($search['end_date']))
      {
        $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
        $this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
      }


      if(isset($search['patient_name']) &&  !empty($search['patient_name']))
      {

        $this->db->where('hms_patient.patient_name',$search['patient_name']);
      }

      if(isset($search['patient_code']) &&  !empty($search['patient_code']))

      {
        
        $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
      }

      if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
      {
        $this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
      }
      if($search['referred_by']=='1' && !empty($search['referral_hospital']))
      {
        $this->db->where('hms_medicine_sale.referral_hospital' ,$search['referral_hospital']);
      }
      elseif($search['referred_by']=='0' && !empty($search['refered_id']))
      {
        $this->db->where('hms_medicine_sale.refered_id' ,$search['refered_id']);
      }


      if(isset($search['sale_no']) &&  !empty($search['sale_no']))
      {
        $this->db->where('hms_medicine_sale.sale_no LIKE "'.$search['sale_no'].'%"');
      }

      if(isset($search['medicine_name']) &&  !empty($search['medicine_name']))
      {
        $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
      }

      if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
      {
        if(empty($search['medicine_name']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        }
        
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left'); 
        $this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
      }

      if(isset($search['medicine_code']) &&  !empty($search['medicine_code']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
      }
      
      if(isset($search['discount']) &&  !empty($search['discount']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']))
        {
        $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
        }
        $this->db->where('hms_medicine_sale_to_medicine.discount = "'.$search['discount'].'"');
      }
      if(isset($search['batch_no']) &&  !empty($search['batch_no']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
        {
        $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
        }
        $this->db->where('hms_medicine_sale_to_medicine.batch_no = "'.$search['batch_no'].'"');
      }
      
      if(isset($search['packing']) && $search['packing']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])  && empty($search['batch_no']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        }
        $this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
      }

      if(isset($search['conversion']) &&  $search['conversion']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
      }

      if(isset($search['mrp_to']) &&  $search['mrp_to']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
      }
      if(isset($search['mrp_from']) &&  $search['mrp_from']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        }
        $this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
      }

      if(isset($search['cgst']) &&  !empty($search['cgst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
        {
          $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
        }
          $this->db->where('hms_medicine_sale_to_medicine.cgst', $search['cgst']);
      }
      if(isset($search['igst']) &&  !empty($search['igst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']))
          {
            $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
          }
          $this->db->where('hms_medicine_sale_to_medicine.igst', $search['igst']);
      }
      if(isset($search['sgst']) &&  !empty($search['sgst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']) && empty($search['igst']))
          {
            $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.sales_id = hms_medicine_sale.id','left'); 
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id','left'); 
          }
          $this->db->where('hms_medicine_sale_to_medicine.sgst', $search['sgst']);
      }

      if(isset($search['paid_amount_to']) &&  $search['paid_amount_to']!="")
      {
        $this->db->where('hms_medicine_sale.paid_amount >="'.$search['paid_amount_to'].'"');
      }
      if(isset($search['paid_amount_from']) &&  $search['paid_amount_from']!="")
      {
        $this->db->where('hms_medicine_sale.paid_amount <="'.$search['paid_amount_from'].'"');
      }

      if(isset($search['balance_to']) &&  $search['balance_to']!="")
      {
        $this->db->where('hms_medicine_sale.balance >="'.$search['balance_to'].'"');
      }
      if(isset($search['balance_from']) && $search['balance_from']!="")
      {
        $this->db->where('hms_medicine_sale.balance <="'.$search['balance_from'].'"');
      }

      if(isset($search['total_amount_to']) &&  $search['total_amount_to']!="")
      {
        $this->db->where('hms_medicine_sale.total_amount >="'.$search['total_amount_to'].'"');
      }
      if(isset($search['total_amount_from']) &&  $search['total_amount_from']!="")
      {
        $this->db->where('hms_medicine_sale.total_amount <="'.$search['total_amount_from'].'"');
      }

      if(isset($search['bank_name']) && $search['bank_name']!="")
      {
        $this->db->where('hms_medicine_sale.bank_name',$search['bank_name']);
      }

      if($search['insurance_type']!="")
      {
        $this->db->where('hms_patient.insurance_type',$search['insurance_type']);
      }

      if(!empty($search['insurance_type_id']))
      {
        $this->db->where('hms_patient.insurance_type_id',$search['insurance_type_id']);
      }

      if(!empty($search['ins_company_id']))
      {
        $this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
      }

    }else{

    }
    $emp_ids='';
    if($user_data['emp_id']>0)
    {
      if($user_data['record_access']=='1')
      {
        $emp_ids= $user_data['id'];
      }
    }
    elseif(!empty($search["employee"]) && is_numeric($search['employee']))
    {
      $emp_ids=  $search["employee"];
    }
    if(isset($emp_ids) && !empty($emp_ids))
    { 
      $this->db->where('hms_medicine_sale.created_by IN ('.$emp_ids.')');
    }
    $query = $this->db->get(); 

    $data= $query->result();
    
    return $data;

   }
  function get_datatables()
  {
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
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
    $search = $this->session->userdata('sale_search');
    //print_r($search);die;
    $user_data = $this->session->userdata('auth_users');
    
    $this->db->from($this->table);
     
    if(isset($search) && !empty($search))
    {
      
      if(isset($search['start_date']) && !empty($search['start_date']))
      {
        $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
        $this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
      }

      if(isset($search['end_date']) &&  !empty($search['end_date']))
      {
        $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
        $this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
      }
    }
    $this->db->where('hms_medicine_sale.is_deleted','0'); 
    $this->db->where('hms_medicine_sale.branch_id = "'.$user_data['parent_id'].'"');
    return $this->db->count_all_results();
  }

  public function get_by_id($id)
  {
    $this->db->select('hms_medicine_sale.*, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.adhar_no,hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email, hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.mobile_no');
    $this->db->from('hms_medicine_sale'); 
    $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id'); 
     $this->db->where('hms_medicine_sale.id',$id);
    $this->db->where('hms_medicine_sale.is_deleted','0');
    $query = $this->db->get(); 
    //echo $this->db->last_query(); 
    return $query->row_array();
  }
  public function get_patient_by_id($id)
  {

    $this->db->select('hms_patient.*');
    $this->db->from('hms_patient'); 
    $this->db->where('hms_patient.id',$id);
    //$this->db->where('hms_medicine_vendors.is_deleted','0');
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    return $query->row_array();
  }

  public function get_medicine_by_sales_id($id="",$tamt="")
  { 
    $this->db->select('hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code, hms_medicine_sale_to_medicine.*,hms_medicine_sale.total_amount as t_amount, hms_medicine_entry.packing,hms_medicine_entry.mrp');
    $this->db->from('hms_medicine_sale_to_medicine'); 
    if(!empty($id))
    {
          $this->db->where('hms_medicine_sale_to_medicine.sales_id',$id);
          $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_medicine_sale_to_medicine.sales_id','left');
          $this->db->join('hms_medicine_batch_stock','hms_medicine_batch_stock.medicine_id=hms_medicine_sale_to_medicine.medicine_id AND hms_medicine_batch_stock.batch_no = hms_medicine_sale_to_medicine.batch_no','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_medicine_sale_to_medicine.medicine_id','left');
    }  
    $query1 = $this->db->get();
    //echo $this->db->last_query();die;
    $query = $query1->result(); 
    
    //echo '<pre>'; print_r($query);die; packing mrp 
    $result = []; 
    $total_price_medicine_amount='';

    if(!empty($query))
    {
      foreach($query as $medicine)  
      {   
           if($medicine->manuf_date!='1970-01-01' || $medicine->manuf_date!='0000-00-00')
          {
             $manufdate = date('d-m-Y',strtotime($medicine->manuf_date));
          }
          else
          {
              $manufdate ='';
          }
          if($medicine->expiry_date!='1970-01-01' || $medicine->expiry_date!='0000-00-00')
          {
             $expirydates = date('d-m-Y',strtotime($medicine->expiry_date));
          }
          else
          {
              $expirydates ='';
          }
          
          $result[$medicine->medicine_id.'.'.$medicine->batch_no] = array('medicine_name'=>$medicine->medicine_name, 'medicine_code'=>$medicine->medicine_code, 'packing'=>$medicine->packing, 'mrp'=>$medicine->mrp, 'mid'=>$medicine->medicine_id, 'qty'=>$medicine->qty, 'exp_date'=>$expirydates,'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>$manufdate,'batch_no'=>$medicine->batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->actual_amount, 'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
          $tamt = 0;
      } 
    } 
    //die;
    //print '<pre>';print_r($result);die;
    return $result;
  }
 public function doctor_list()
    {
      $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');   
    $this->db->order_by('hms_doctors.doctor_name','ASC');
    $this->db->where('hms_doctors.is_deleted',0); 
    $this->db->where('hms_doctors.doctor_type IN (0,2)'); 
    $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
    $query = $this->db->get('hms_doctors');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result;
    }

  public function save()
  { 
      $user_data = $this->session->userdata('auth_users');
      $post = $this->input->post();
      //echo "<pre>"; print_r($post); die;
      $data_patient = array(
                //"patient_code"=>$post['patient_reg_code'],
                "patient_name"=>$post['name'],
                'simulation_id'=>$post['simulation_id'],
                'branch_id'=>$user_data['parent_id'],
                'relation_type'=>$post['relation_type'],
                'relation_name'=>$post['relation_name'],
                'relation_simulation_id'=>$post['relation_simulation_id'],
                'gender'=>$post['gender'],
                'mobile_no'=>$post['mobile'],
                'patient_category'=>$post['patient_category'],
              );
        
    if(!empty($post['data_id']) && $post['data_id']>0)
    { 
        $created_by= $this->get_by_id($post['data_id']);
      
      $purchase_detail= $this->get_by_id($post['data_id']);
      $patient_id_new= $this->get_patient_by_id($purchase_detail['patient_id']);
      $this->db->set('modified_by',$user_data['id']);
      $this->db->set('modified_date',date('Y-m-d H:i:s'));
      $this->db->where('id', $post['patient_id']);
      $this->db->update('hms_patient',$data_patient);
/*
            if(!empty($post['discount_percent']))
            {
                $blance= ((str_replace(',', '', $post['net_amount'])-$post['pay_amount']))-$post['discount_amount'];      
            }
            else
            {*/
                $blance= ((str_replace(',', '', $post['net_amount'])-$post['pay_amount']));
            //}
          
          $ipd_id='';
          if(isset($post['ipd_id']))
          {
            $ipd_id = $post['ipd_id'];
          }
          $data = array(
          "patient_id"=>$post['patient_id'],
          'branch_id'=>$user_data['parent_id'],
          "ipd_id"=>$ipd_id, 
          'refered_id'=>$post['refered_id'],
          'simulation_id'=>$post['simulation_id'],
          'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
          'total_amount'=>str_replace(',', '', $post['total_amount']),
          'net_amount'=>str_replace(',', '', $post['net_amount']),
          'discount'=>$post['discount_amount'],
          'medicine_discount'=>$post['medicine_discount'],
          "sgst"=>$post['sgst_amount'],
          "igst"=>$post['igst_amount'],
          "cgst"=>$post['cgst_amount'],
          "discount_percent"=>$post['discount_percent'],
          'payment_mode'=>$post['payment_mode'],
          'balance'=>$blance,
          "remarks"=>$post['remarks'],
          'paid_amount'=>$post['pay_amount'],
          'next_app_date'=>date('Y-m-d', strtotime($post['next_app_date'])),
          'referred_by'=>$post['referred_by'],
          'referral_hospital'=>$post['referral_hospital'],
          'ref_by_other'=>$post['ref_by_other'],
          'patient_category'=>$post['patient_category'],
            'authorize_person'=>$post['authorize_person'],
            'diseases' => $post['diseases'],
          ); 

        $this->db->where('id',$post['data_id']);
        $this->db->set('modified_by',$user_data['id']);
        $this->db->set('modified_date',date('Y-m-d H:i:s'));
        $this->db->update('hms_medicine_sale',$data);
        //echo $this->db->last_query();die;
        //$sess_medicine_list= $this->session->userdata('medicine_id');
        
        $this->db->select('hms_medicine_sale_to_medicine.medicine_id, hms_medicine_sale_to_medicine.batch_no, hms_medicine_sale_to_medicine.qty'); 
        $this->db->where('hms_medicine_sale_to_medicine.sales_id',$post['data_id']); 
        $query = $this->db->get('hms_medicine_sale_to_medicine');
        $sale_to_medicine_data =  $query->result_array();
        $sale_to_medicine_data_arr = [];
        if(!empty($sale_to_medicine_data)) 
        {
            foreach($sale_to_medicine_data as $s_data)
            {
                $sale_to_medicine_data_arr[$s_data['medicine_id'].'.'.$s_data['batch_no']] = array('mid_batch'=>$s_data['medicine_id'].'.'.$s_data['batch_no'], 'medicine_id'=>$s_data['medicine_id'], 'batch_no'=>$s_data['batch_no'], 'qty'=>$s_data['qty']);
            }
        }
        //echo "<pre>"; print_r($sale_to_medicine_data_arr);die;
        $medicine_sel_id = $post['medicine_sel_id'];
        //echo '<pre>';print_r($sess_medicine_list);die;
        $this->db->where('sales_id',$post['data_id']);
        $this->db->delete('hms_medicine_sale_to_medicine');
         
        $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>3));
        $this->db->delete('hms_medicine_stock');
        if(!empty($medicine_sel_id))
        { 
        //echo 'rwer';
          if(isset($post['ipd_id']) && !empty($post['ipd_id']))
          {
            $this->db->where('ipd_id',$post['ipd_id']);
            $this->db->where('parent_id',$post['ipd_id']);
                  $this->db->where('type','8');
                  $this->db->where('patient_id',$post['patient_id']);
                  $this->db->delete('hms_ipd_patient_to_charge');
          }
          
           
              
         
    //print '<pre>'; print_r($sess_medicine_list);die;
    
        $i=0;
       foreach($medicine_sel_id as $medicine_list)
       {
            //print '<pre>';print_r($medicine_list['batch_no']);
            //$m_data= explode('.',$medicine_list['mid']);
            $data_purchase_topurchase = array(
            "sales_id"=>$post['data_id'],
            'medicine_id'=>$medicine_list,
            'qty'=>$post['quantity'][$i],
            'discount'=>$post['discount'][$i],
            'sgst'=>$post['sgst'][$i],
            'igst'=>$post['igst'][$i],
            'cgst'=>$post['cgst'][$i],
            'hsn_no'=>$post['hsn_no'][$i],
            'total_amount'=>$post['row_total_amount'][$i],
            'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
            'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
            'batch_no'=>$post['batch_no'][$i],
            'bar_code'=>$post['bar_code'][$i],
            'per_pic_price'=>$post['per_pic_amount'][$i],
            'conversion'=>$post['conversion'][$i],
            'actual_amount'=>$post['sale_amount'][$i],
            'created_date'=>date('Y-m-d',strtotime($post['sales_date']))

            );
            $this->db->insert('hms_medicine_sale_to_medicine',$data_purchase_topurchase);
            //echo $this->db->last_query();
             
            $data_new_stock=array("branch_id"=>$user_data['parent_id'],
            "type"=>3,
            "parent_id"=>$post['data_id'],
            "m_id"=>$medicine_list,
            "credit"=>$post['quantity'][$i],
            "debit"=>0,
            "batch_no"=>$post['batch_no'][$i],
            "conversion"=>$post['conversion'][$i],
            "mrp"=>$post['sale_amount'][$i],
            "discount"=>$post['discount'][$i],
            'sgst'=>$post['sgst'][$i],
            'igst'=>$post['igst'][$i],
            'cgst'=>$post['cgst'][$i],
            'hsn_no'=>$post['hsn_no'][$i],
            'bar_code'=>$post['bar_code'][$i],
            "total_amount"=>$post['row_total_amount'][$i],
            "expiry_date"=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
            'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
            'per_pic_rate'=>$post['per_pic_amount'][$i],
            "created_by"=>$created_by['created_by'],
            
            "created_date"=>date('Y-m-d',strtotime($post['sales_date']))
            );
            $this->db->insert('hms_medicine_stock',$data_new_stock);
            
            
            $med_qty = $post['quantity'][$i];//$medicine_list['quantity'];
            $last_qty = 0;
            $symbol = '-';
            if(!empty($sale_to_medicine_data_arr))
            {
                $column_arr = array_column($sale_to_medicine_data_arr,'mid_batch');
                //echo "<pre>"; print_r($column_arr); die;
                
                
                if(in_array($medicine_list.'.'.$post['batch_no'][$i], $column_arr))
                {
                    $last_qty = $sale_to_medicine_data_arr[$medicine_list.'.'.$post['batch_no'][$i]]['qty'];
                    if($last_qty>$med_qty)
                    {
                        $med_qty = $last_qty-$med_qty;
                        $symbol = '+';
                    }
                    else if($med_qty>$last_qty)
                    {
                        $med_qty = $med_qty-$last_qty;
                    }
                    else if($med_qty==$last_qty)
                    {
                        $med_qty = 0;
                    }
                    
                }
            }
            /*$med_qty = $medicine_list['quantity'];
            $last_qty = 0;
            $symbol = '-';
            if(!empty($sale_to_medicine_data_arr))
            {
                $column_arr = array_column($sale_to_medicine_data_arr,'mid_batch');
                if(in_array($medicine_list.'.'.$post['batch_no'][$i], $column_arr))
                {
                    $last_qty = $sale_to_medicine_data_arr[$medicine_list.'.'.$post['batch_no'][$i]]['quantity'];
                    if($last_qty>$med_qty)
                    {
                        $med_qty = $last_qty-$med_qty;
                        $symbol = '+';
                    }
                    else if($med_qty>$last_qty)
                    {
                        $med_qty = $med_qty-$last_qty;
                    }
                    else if($med_qty==$last_qty)
                    {
                        $med_qty = 0;
                    }
                    
                }
            }*/
            
            $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` ".$symbol." '".$med_qty."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no'][$i]."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$medicine_list."' ");
            
            //echo $this->db->last_query(); exit;

            
            if(isset($post['ipd_id']) && !empty($post['ipd_id']))
            {
                $medicine_name = get_medicine_name($medicine_list);
                $patient_medicine_charge = array(
                "branch_id"=>$user_data['parent_id'],
                'ipd_id'=>$post['ipd_id'],
                'parent_id'=>$purchase_id,
                'patient_id'=>$post['patient_id'],
                'type'=>8,
                'quantity'=>$post['quantity'][$i],
                'start_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                'end_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                'particular'=>$medicine_name,
                'price'=>$post['total_amount'][$i],
                'panel_price'=>$post['total_amount'][$i],
                'net_price'=>$post['total_amount'][$i],
                'status'=>1,
                'created_date'=>date('Y-m-d',strtotime($post['sales_date']))
              );
              $this->db->insert('hms_ipd_patient_to_charge',$patient_medicine_charge);
            }

          $i++;
           
       }
        //die;
        }

      if(empty($blance) || $blance==0)
      {
        $blance =1;
      }
        if(isset($post['ipd_id']) && !empty($post['ipd_id']))
        {
          
        }
        else
        { 
              $this->db->where('parent_id',$post['data_id']);
              $this->db->where('section_id','3');
              $this->db->where('balance>0');
              $this->db->where('patient_id',$post['patient_id']);
              $query_d_pay = $this->db->get('hms_payment');
              $row_d_pay = $query_d_pay->result();
              //get ids and update the id and created date for balance clearence payment 
              if(!empty($row_d_pay))
              {
                  foreach($row_d_pay as $row_d)
                  {
                      $comission_arr = get_doc_hos_comission($post['refered_id'],$post['referral_hospital'],$post['pay_amount'],4,'',$post['discount_amount']);
                        //print_r($comission_arr);die;
                        $doctor_comission = 0;
                        $hospital_comission=0;
                        $comission_type='';
                        $total_comission=0;
                        if(!empty($comission_arr))
                        {
                            $doctor_comission = $comission_arr['doctor_comission'];
                            $hospital_comission= $comission_arr['hospital_comission'];
                            $comission_type= $comission_arr['comission_type'];
                            $total_comission= $comission_arr['total_comission'];
                        }
                      $payment_data = array(
                        'parent_id'=>$post['data_id'],
                        'branch_id'=>$user_data['parent_id'],
                        'section_id'=>'3',
                        'doctor_id'=>$post['refered_id'],
                        'hospital_id'=>$post['referral_hospital'],
                        'patient_id'=>$post['patient_id'],
                        'total_amount'=>str_replace(',', '', $post['total_amount']),
                        'discount_amount'=>$post['discount_amount'],
                        'net_amount'=>str_replace(',', '', $post['net_amount']),
                        'credit'=>str_replace(',', '', $post['net_amount']),
                        'debit'=>$post['pay_amount'],
                        'pay_mode'=>$post['payment_mode'],
                        'balance'=>$blance,
                        'paid_amount'=>$post['pay_amount'],
                        'doctor_comission'=>$doctor_comission,
						'hospital_comission'=>$hospital_comission,
						'comission_type'=>$comission_type,
						'total_comission'=>$total_comission,
                        'created_date'=>$row_d->created_date,
                        'created_by'=>$row_d->created_by,//$user_data['id']
                      );

                    $this->db->where('id',$row_d->id);
                    $this->db->update('hms_payment',$payment_data);
                      

                  }
              }
              else
              {
                  $comission_arr = get_doc_hos_comission($post['refered_id'],$post['referral_hospital'],$post['pay_amount'],4,'',$post['discount_amount']);
            //print_r($comission_arr);die;
                    $doctor_comission = 0;
                    $hospital_comission=0;
                    $comission_type='';
                    $total_comission=0;
                    if(!empty($comission_arr))
                    {
                        $doctor_comission = $comission_arr['doctor_comission'];
                        $hospital_comission= $comission_arr['hospital_comission'];
                        $comission_type= $comission_arr['comission_type'];
                        $total_comission= $comission_arr['total_comission'];
                    } 
                    
                if(!empty($post['referral_hospital']))
                {
                    $referral_hospital = $post['referral_hospital'];
                }
                else
                {
                    $referral_hospital=0;
                }
                if(!empty($post['refered_id']))
                {
                    $refered_id = $post['refered_id'];
                }
                else
                {
                    $refered_id=0;
                }
                    $payment_data = array(
                            'parent_id'=>$post['data_id'],
                            'branch_id'=>$user_data['parent_id'],
                            'section_id'=>'3',
                            'doctor_id'=>$refered_id,
                            'hospital_id'=>$referral_hospital,
                            'patient_id'=>$post['patient_id'],
                            'total_amount'=>str_replace(',', '', $post['total_amount']),
                            'discount_amount'=>$post['discount_amount'],
                            'net_amount'=>str_replace(',', '', $post['net_amount']),
                            'credit'=>str_replace(',', '', $post['net_amount']),
                            'debit'=>$post['pay_amount'],
                            'pay_mode'=>$post['payment_mode'],
                            'balance'=>$blance,
                            'paid_amount'=>$post['pay_amount'],
                            'doctor_comission'=>$doctor_comission,
            				'hospital_comission'=>$hospital_comission,
            				'comission_type'=>$comission_type,
            				'total_comission'=>$total_comission,
                            'created_date'=>date('Y-m-d H:i:s',strtotime($post['sales_date'].' '.date('H:i:s'))),
                            'created_by'=>$user_data['id']
                                       );
                    $this->db->insert('hms_payment',$payment_data);
                    //echo $this->db->last_query(); exit;
                    $payment_id = $this->db->insert_id();
                              
              
                  if($post['pay_amount']>0)
                   {
                     $hospital_receipt_no= check_hospital_receipt_no();
                     $data_receipt_data= array(
                                        'branch_id'=>$user_data['parent_id'],
                                        'section_id'=>6,
                                        'parent_id'=>$post['data_id'],
                                        'payment_id'=>$payment_id,
                                        'reciept_prefix'=>$hospital_receipt_no['prefix'],
                                        'reciept_suffix'=>$hospital_receipt_no['suffix'],
                                        'created_by'=>$user_data['id'],
                                        'created_date'=>date('Y-m-d H:i:s')
                                        );
                  $this->db->insert('hms_branch_hospital_no',$data_receipt_data);
                   }

              }

        }
        
        
        /*add sales banlk detail*/
        $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>3,'section_id'=>4));
        $this->db->delete('hms_payment_mode_field_value_acc_section');
        if(!empty($post['field_name']))
        {
          $post_field_value_name= $post['field_name'];
          $counter_name= count($post_field_value_name); 
          for($i=0;$i<$counter_name;$i++) 
          {
              $data_field_value= array(
              'field_value'=>$post['field_name'][$i],
              'field_id'=>$post['field_id'][$i],
              'type'=>3,
              'section_id'=>4,
              'p_mode_id'=>$post['payment_mode'],
              'branch_id'=>$user_data['parent_id'],
              'parent_id'=>$post['data_id'],
              'ip_address'=>$_SERVER['REMOTE_ADDR']
              );
              /*$this->db->set('created_by',$user_data['id']);
              $this->db->set('created_date',date('Y-m-d H:i:s'));*/
              $this->db->set('created_by',$created_by['created_by']);
              $this->db->set('created_date',date('Y-m-d H:i:s',strtotime($created_by['created_date'])));
              $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

          }
        }

      /*add sales banlk detail*/  
      $purchase_id= $post['data_id'];

    }
  else
  {
          //echo "<pre>"; print_r($post); die;
          $sale_no = generate_unique_id(16);
          $patient_reg_code=generate_unique_id(4);
          $patient_data= $this->get_patient_by_id($post['patient_id']);
          if(count($patient_data)>0)
          {
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['patient_id']);
            $this->db->update('hms_patient',$data_patient);
            $patient_id= $post['patient_id'];
          }
          else
          {
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->set('patient_code',$patient_reg_code);
            $this->db->insert('hms_patient',$data_patient); 
            $patient_id= $this->db->insert_id();
            
            $data = array(     
              "users_role"=>4,
              "parent_id"=>$patient_id,
              "username"=>'PAT000'.$patient_id,
              "password"=>md5('PASS'.$patient_id),
              //"email"=>$post['patient_email'], 
              "status"=>'1',
              "ip_address"=>$_SERVER['REMOTE_ADDR'],
              "created_by"=>$user_data['id'],
              "created_date" =>date('Y-m-d H:i:s')
                 ); 
              $this->db->insert('hms_users',$data); 
              $users_id = $this->db->insert_id();    
              
            if(in_array('640',$user_data['permission']['action']))
            {
              if(!empty($post['mobile']))
              {
                 
                send_sms('patient_registration',18,$post['name'],$post['mobile'],array('{patient_name}'=>$post['name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id));
              }
            }
          }
      
         
          
          /*if(!empty($post['discount_percent']))
            {
                $blance= ((str_replace(',', '', $post['net_amount'])-$post['pay_amount']))-$post['discount_amount'];      
            }
            else
            {*/
                $blance= ((str_replace(',', '', $post['net_amount'])-$post['pay_amount']));
            //}
          
          $ipd_id ='';
          if(isset($post['ipd_id']))
          {
            $ipd_id = $post['ipd_id'];  
          }

          $opd_id ='0';
          if(isset($post['opd_id']))
          {
            $opd_id = $post['opd_id'];  
          }
          $data = array(
                "patient_id"=>$patient_id,
                "ipd_id"=>$ipd_id,
                'opd_id'=>$opd_id,
                'branch_id'=>$user_data['parent_id'],
                'sale_no'=>$sale_no,
                'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                'total_amount'=>str_replace(',', '', $post['total_amount']),
                'net_amount'=>str_replace(',', '', $post['net_amount']),
                'discount'=>$post['discount_amount'],
                'medicine_discount'=>$post['medicine_discount'],
                'refered_id'=>$post['refered_id'],
                'simulation_id'=>$post['simulation_id'],
                "sgst"=>$post['sgst_amount'],
                "igst"=>$post['igst_amount'],
                "cgst"=>$post['cgst_amount'],
                "discount_percent"=>$post['discount_percent'],
                'payment_mode'=>$post['payment_mode'],
                'next_app_date'=>date('Y-m-d', strtotime($post['next_app_date'])),
                'balance'=>$blance,
                "remarks"=>$post['remarks'],
                'paid_amount'=>$post['pay_amount'],
                'referred_by'=>$post['referred_by'],
                'referral_hospital'=>$post['referral_hospital'],
                'ref_by_other'=>$post['ref_by_other'],
                'patient_category'=>$post['patient_category'],
            'authorize_person'=>$post['authorize_person'],
            'diseases' => $post['diseases'],
              ); 
        
        $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_medicine_sale',$data);
        $purchase_id= $this->db->insert_id();
        //echo $this->db->last_query(); exit;
    
    //$sess_medicine_list= $this->session->userdata('medicine_id');
    $medicine_sel_id = $post['medicine_sel_id'];
    //print '<pre>'; print_r($sess_medicine_list);die;
    if(!empty($medicine_sel_id))
    { 
        $i=0;
       foreach($medicine_sel_id as $medicine_list)
       {
          //print '<pre>';print_r($medicine_list);
          $m_data= explode('.',$medicine_list['mid']);
        $data_purchase_topurchase = array(
          "sales_id"=>$purchase_id,
          'medicine_id'=>$medicine_list,
          'qty'=>$post['quantity'][$i],
          'discount'=>$post['discount'][$i],
          'sgst'=>$post['sgst'][$i],
          'igst'=>$post['igst'][$i],
          'cgst'=>$post['cgst'][$i],
          'hsn_no'=>$post['hsn_no'][$i],
          'bar_code'=>$post['bar_code'][$i],
          'total_amount'=>$post['row_total_amount'][$i],
          'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
          'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
          'batch_no'=>$post['batch_no'][$i],
          'per_pic_price'=>$post['per_pic_amount'][$i],
          'conversion'=>$post['conversion'][$i],
          'actual_amount'=>$post['sale_amount'][$i],
            'created_date'=>date('Y-m-d',strtotime($post['sales_date']))
          );
          $this->db->insert('hms_medicine_sale_to_medicine',$data_purchase_topurchase);
          //echo $this->db->last_query(); exit;
          $data_new_stock=array("branch_id"=>$user_data['parent_id'],
              "type"=>3,
              "parent_id"=>$purchase_id,
              "m_id"=>$medicine_list,
              "credit"=>$post['quantity'][$i],
              "debit"=>0,
            "batch_no"=>$post['batch_no'][$i],
            "conversion"=>$post['conversion'][$i],
            "mrp"=>$post['sale_amount'][$i],
            "discount"=>$post['discount'][$i],
            'sgst'=>$post['sgst'][$i],
            'hsn_no'=>$post['hsn_no'][$i],
            'igst'=>$post['igst'][$i],
            'cgst'=>$post['cgst'][$i],
            'bar_code'=>$post['bar_code'][$i],
            "total_amount"=>$post['row_total_amount'][$i],
            "expiry_date"=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
            'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
            'per_pic_rate'=>$post['per_pic_amount'][$i],
            "created_by"=>$user_data['id'],
            "created_date"=>date('Y-m-d',strtotime($post['sales_date']))
              );
           $this->db->insert('hms_medicine_stock',$data_new_stock);
           
             
            $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity`-'".$post['quantity'][$i]."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no'][$i]."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$post['medicine_sel_id'][$i]."' "); 
          
          if(isset($post['ipd_id']) && !empty($post['ipd_id']))
          {
              $medicine_name = get_medicine_name($medicine_list);
              $patient_medicine_charge = array(
              "branch_id"=>$user_data['parent_id'],
              'ipd_id'=>$post['ipd_id'],
              'parent_id'=>$purchase_id,
              'patient_id'=>$post['patient_id'],
              'type'=>8,
              'quantity'=>$post['quantity'][$i],
              'start_date'=>date('Y-m-d',strtotime($post['sales_date'])),
              'end_date'=>date('Y-m-d',strtotime($post['sales_date'])),
              'particular'=>$medicine_name,
              'price'=>$post['row_total_amount'][$i],
              'panel_price'=>$post['row_total_amount'][$i],
              'net_price'=>$post['row_total_amount'][$i],
              'status'=>1,
              'created_date'=>date('Y-m-d',strtotime($post['sales_date']))
            );
            $this->db->insert('hms_ipd_patient_to_charge',$patient_medicine_charge);
          }
        
           $i++;
       }
        //die;
      } 
             
      if(empty($blance) || $blance==0)
      {
        $blance =1;
      }
      if(isset($post['ipd_id']) && !empty($post['ipd_id']))
      {
      
        
          
      }
      else
      {
        $comission_arr = get_doc_hos_comission($post['refered_id'],$post['referral_hospital'],$post['pay_amount'],4,'',$post['discount_amount']);
            //print_r($comission_arr);die;
        $doctor_comission = 0;
        $hospital_comission=0;
        $comission_type='';
        $total_comission=0;
        if(!empty($comission_arr))
        {
            $doctor_comission = $comission_arr['doctor_comission'];
            $hospital_comission= $comission_arr['hospital_comission'];
            $comission_type= $comission_arr['comission_type'];
            $total_comission= $comission_arr['total_comission'];
        } 
        
        if(!empty($post['referral_hospital']))
        {
            $referral_hospital = $post['referral_hospital'];
        }
        else
        {
            $referral_hospital=0;
        }
        if(!empty($post['refered_id']))
        {
            $refered_id = $post['refered_id'];
        }
        else
        {
            $refered_id=0;
        }
        $payment_data = array(
                'parent_id'=>$purchase_id,
                'branch_id'=>$user_data['parent_id'],
                'section_id'=>'3',
                'doctor_id'=>$refered_id,
                'hospital_id'=>$referral_hospital,
                'patient_id'=>$patient_id,
                'total_amount'=>str_replace(',', '', $post['total_amount']),
                'discount_amount'=>$post['discount_amount'],
                'net_amount'=>str_replace(',', '', $post['net_amount']),
                'credit'=>str_replace(',', '', $post['net_amount']),
                'debit'=>$post['pay_amount'],
                'pay_mode'=>$post['payment_mode'],
                'balance'=>$blance,
                'paid_amount'=>$post['pay_amount'],
                'doctor_comission'=>$doctor_comission,
				'hospital_comission'=>$hospital_comission,
				'comission_type'=>$comission_type,
				'total_comission'=>$total_comission,
                'created_date'=>date('Y-m-d H:i:s',strtotime($post['sales_date'].' '.date('H:i:s'))),
                'created_by'=>$user_data['id']
                           );
        $this->db->insert('hms_payment',$payment_data);
        //echo $this->db->last_query(); exit;
        $payment_id = $this->db->insert_id();

        /* genereate receipt number */
       
           if($post['pay_amount']>0)
           {
             $hospital_receipt_no= check_hospital_receipt_no();
             $data_receipt_data= array(
                                'branch_id'=>$user_data['parent_id'],
                                'section_id'=>6,
                                'parent_id'=>$purchase_id,
                                'payment_id'=>$payment_id,
                                'reciept_prefix'=>$hospital_receipt_no['prefix'],
                                'reciept_suffix'=>$hospital_receipt_no['suffix'],
                                'created_by'=>$user_data['id'],
                                'created_date'=>date('Y-m-d H:i:s')
                                );
          $this->db->insert('hms_branch_hospital_no',$data_receipt_data);
           }
        

      }

             
            
      /*add sales banlk detail*/  
      if(!empty($post['field_name']))
      {
        for($i=0;$i<$counter_name;$i++) 
            {
              $data_field_value= array(
              'field_value'=>$post['field_name'][$i],
              'field_id'=>$post['field_id'][$i],
              'type'=>3,
              'section_id'=>4,
              'p_mode_id'=>$post['payment_mode'],
              'branch_id'=>$user_data['parent_id'],
              'parent_id'=>$purchase_id,
              'ip_address'=>$_SERVER['REMOTE_ADDR']
              );
          $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

            }
      }
      
            
      /*add sales banlk detail*/  
      //echo $this->db->last_query();die;          
    } 
     
      if(!empty($post['next_app_date']) && $post['next_app_date']!='00-00-0000' && $post['next_app_date']!='01-01-1970')
        {  
            $this->db->where('booking_id',$purchase_id);
            $this->db->where('booking_type','34'); 
            $this->db->delete('hms_next_appointment');
            
            $this->db->where('id',$purchase_id); 
            $query_d_pay = $this->db->get('hms_medicine_sale');
            $row_d_pay = $query_d_pay->row_array();
            //print_r($row_d_pay);die;
            $next_appointment_data = array( 
						'branch_id'=>$user_data['parent_id'],
						'booking_id'=>$purchase_id,
						'booking_type'=>34, 
						'booking_code'=>$row_d_pay['sale_no'], 
						'patient_id'=>$row_d_pay['patient_id'],
						'next_appointment'=>date('Y-m-d', strtotime($post['next_app_date'])), 
						'created_date'=>date('Y-m-d H:i:s') 
				);
            $this->db->insert('hms_next_appointment',$next_appointment_data);
            
            //echo $this->db->last_query(); exit;

        }
    $this->session->unset_userdata('medicine_id');
    return $purchase_id;  
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
      $this->db->update('hms_medicine_sale');
      
      // Update batch stock
        $this->db->select('hms_medicine_sale_to_medicine.medicine_id, hms_medicine_sale_to_medicine.batch_no, hms_medicine_sale_to_medicine.qty'); 
        $this->db->where('hms_medicine_sale_to_medicine.sales_id',$id); 
        $query = $this->db->get('hms_medicine_sale_to_medicine');
        $sale_to_medicine_data =  $query->result_array();
        if(!empty($sale_to_medicine_data))
        {
            foreach($sale_to_medicine_data as $sale_to_medicine)
            {
                 $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity`+'".$sale_to_medicine['qty']."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$sale_to_medicine['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$sale_to_medicine['medicine_id']."' "); 
            }
        }
      // End update batch stock
      
      //update status on stock
            $this->db->where('parent_id',$id);
            $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('type','3');
            $query_d_pay = $this->db->get('hms_medicine_stock');

            $row_d_pay = $query_d_pay->result();

            if(!empty($row_d_pay))
            {
                  foreach($row_d_pay as $row_d)
                  {
                    $stock_data = array(
                        'is_deleted'=>1);
                      
                     $this->db->where('id',$row_d->id);
                     $this->db->where('branch_id',$user_data['parent_id']);
                     $this->db->where('parent_id',$id);
                     $this->db->where('type',3);
                    $this->db->update('hms_medicine_stock',$stock_data);
                   // echo $this->db->last_query(); exit;
                }
            }
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
      $this->db->update('hms_medicine_sale');
      
      //update status on stock
            $this->db->where('parent_id',$id);
            $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('type','3');
            $query_d_pay = $this->db->get('hms_medicine_stock');

            $row_d_pay = $query_d_pay->result();

            if(!empty($row_d_pay))
            {
                  foreach($row_d_pay as $row_d)
                  {
                    $stock_data = array(
                        'is_deleted'=>1);
                      
                     $this->db->where('id',$row_d->id);
                     $this->db->where('branch_id',$user_data['parent_id']);
                     $this->db->where('parent_id',$id);
                     $this->db->where('type',3);
                    $this->db->update('hms_medicine_stock',$stock_data);
                   // echo $this->db->last_query(); exit;
                }
            }
      } 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_sale');
        $result = $query->result(); 
        return $result; 
    } 

   public function medicine_list($ids="",$batch_no="")
    { 
    $users_data = $this->session->userdata('auth_users'); 
    $medicine_list = $this->session->userdata('medicine_id');
    //print_r($medicine_list);die;
    $keys = '';
    if(!is_array($ids) || !is_array($batch_no))
    {
      $keys = "'".$ids.'.'.$batch_no."'";
    }
    else if(is_array($ids) || is_array($batch_no))
    { 
      $key_arr = [];
      $total_key = count($ids);
      $i=0;
      foreach($ids as $id)
      {
              $key_arr[] = "'".$id.'.'.$batch_no[$i]."'";
             $i++;
      }
      $keys = implode(',', $key_arr);
      //echo $key;
    }
    else //if(is_array($ids) && is_array($batch_no))
    {
           // $keys = implode(',', array_keys($medicine_list));

      $arr_mids = [];
      if(!empty($medicine_list))
      { 
      foreach($medicine_list as $medicine_data)
      {
      $arr_mids[] = "'".$medicine_data['mid'].".".$medicine_data['batch_no']."'";
      }
      $keys = implode(',', $arr_mids);
      }
      //$keys = $ids.'.'.$batch_no; discount
    }
    $this->db->select('hms_medicine_entry.packing,hms_medicine_entry.id, hms_medicine_entry.id as mid,hms_medicine_entry.medicine_code, hms_medicine_entry.medicine_name, hms_medicine_batch_stock.mrp, hms_medicine_batch_stock.sgst, hms_medicine_batch_stock.cgst,hms_medicine_batch_stock.igst, hms_medicine_entry.discount, hms_medicine_batch_stock.bar_code,hms_medicine_batch_stock.hsn_no as hsn,hms_medicine_batch_stock.mrp as m_rp, hms_medicine_entry.conversion as conv,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, hms_medicine_batch_stock.batch_no,hms_medicine_batch_stock.expiry_date,hms_medicine_batch_stock.manuf_date,hms_medicine_batch_stock.quantity as qty,hms_medicine_entry.sgst as main_sgst,hms_medicine_entry.cgst as main_cgst,hms_medicine_entry.igst as main_igst');
   // $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id'); 
   //12feb2019 3.10 PM
   
   $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id');
    if(!empty($keys))
    { 
        $this->db->where('CONCAT(hms_medicine_batch_stock.medicine_id,".",hms_medicine_batch_stock.batch_no) IN ('.$keys.') ');
    } 
    
    $this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
    $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');

    $this->db->where('hms_medicine_entry.is_deleted','0');  
    $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']); 
    $this->db->group_by('hms_medicine_entry.id, hms_medicine_batch_stock.batch_no'); 
    //$this->db->limit('120');
    $query = $this->db->get('hms_medicine_batch_stock');
    //echo $this->db->last_query();die;
    $result = $query->result(); 
    
    
   // echo $this->db->last_query();die;
    return $result; 
    }

   public function get_batch_med_qty20190613($mid="",$batch_no="")
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('(sum(debit)-sum(credit)) as total_qty');
      $this->db->where('branch_id',$user_data['parent_id']); 
      $this->db->where('m_id',$mid);
      $this->db->where('batch_no',$batch_no);
      $query = $this->db->get('hms_medicine_stock');
      return $query->row_array();
    }
    
    public function get_batch_med_qty($mid="",$batch_no="")
  {
    $user_data = $this->session->userdata('auth_users');
    $search=$this->session->userdata('stock_search');

    /*  code for deleted record */
    $p_ids=array();
    $p_r_ids=array();
    $s_ids=array();
    $s_r_ids=array();
    $new_s_r_ids='';
    $new_s_ids='';
    $new_p_r_ids='';
    $new_p_ids='';
    $where_1='';
    $where_2='';
    $where_3='';
    $where_4='';
    $deleted_purchase_medicine= $this->is_deleted_purchase_medicine();
    $deleted_purchase_return_medicine= $this->is_deleted_purchase_return_medicine();
    $deleted_sale_medicine= $this->is_deleted_sale_medicine();
    $deleted_sale_return_medicine= $this->is_deleted_sale_return_medicine();
    $this->db->select("(sum(debit)-sum(credit)) as total_qty");
    if(isset($search['branch_id']) && $search['branch_id']!=''){
    $this->db->where('branch_id IN ('.$search['branch_id'].')');
    }else{
    $this->db->where('branch_id',$user_data['parent_id']);
    }
    if(!empty($deleted_purchase_medicine))
    {
     foreach($deleted_purchase_medicine as $purchase_ids)
     {
      $p_ids[]=$purchase_ids['id'];
     }
     $new_p_ids=implode(',',$p_ids);
     $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_p_ids.")"); 
    }
     
    
    if(!empty($deleted_purchase_return_medicine))
    {
      foreach($deleted_purchase_return_medicine as $purchase_r_ids)
      {
        $p_r_ids[]=$purchase_r_ids['id'];
      }
        $new_p_r_ids=implode(',',$p_r_ids);
        $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_p_r_ids.")");
    }
    
  
    if(!empty($deleted_sale_medicine))
    {
      foreach($deleted_sale_medicine as $sale_ids)
      {
        $s_ids[]=$sale_ids['id'];
      }
      $new_s_ids=implode(',',$s_ids);
      $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_s_ids.")");
    }
    
  
    if(!empty($deleted_sale_return_medicine))
    {
      foreach($deleted_sale_return_medicine as $sale_r_ids)
      {
        $s_r_ids[]=$sale_r_ids['id'];
      }
        $new_s_r_ids=implode(',',$s_r_ids);
        $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_s_r_ids.")");
    }
    /*  code for deleted record */
    
    $this->db->where('m_id',$mid);
    $this->db->where('batch_no',$batch_no); 
    /*if(!empty($batch_no))
    {
      
    }*/
    //$this->db->where('batch_no='.$batch_no.' OR  batch_no=0 OR batch_no=''');
    
    $query = $this->db->get('hms_medicine_stock');
    //echo $this->db->last_query(); exit;
    return $query->row_array();

  }
    
    
    function is_deleted_purchase_medicine()
    {
         $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_medicine_purchase.id');
    	$this->db->where('hms_medicine_purchase.is_deleted=2');
    	$this->db->where('hms_medicine_purchase.branch_id',$user_data['parent_id']);
    	$query= $this->db->get('hms_medicine_purchase')->result_array();
    	return $query;
    }
    function is_deleted_purchase_return_medicine()
    {
         $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_medicine_return.id');
    	$this->db->where('hms_medicine_return.is_deleted=2');
    	$this->db->where('hms_medicine_return.branch_id',$user_data['parent_id']);
    	$query=$this->db->get('hms_medicine_return')->result_array();
    	//print_r($query);die;
    	return $query;
    }
    function is_deleted_sale_medicine()
    {
         $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_medicine_sale.id');
    	$this->db->where('hms_medicine_sale.is_deleted=2');
    	$this->db->where('hms_medicine_sale.branch_id',$user_data['parent_id']);
    	$query=$this->db->get('hms_medicine_sale')->result_array();
    	return $query;
    }
    function is_deleted_sale_return_medicine()
    {
         $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_medicine_sale_return.id');
    	$this->db->where('hms_medicine_sale_return.is_deleted=2');
    	$this->db->where('hms_medicine_sale_return.branch_id',$user_data['parent_id']);
    	$query=$this->db->get('hms_medicine_sale_return')->result_array();
    	return $query;
    }
     public function medicine_list_search($medicine_order_setting='0')
    {

      $users_data = $this->session->userdata('auth_users'); 
      $added_medicine_list = $this->session->userdata('medicine_id');
      //print_r($added_medicine_list);
      $added_medicine_ids = array();
      $added_medicine_batch = array();
      $post = $this->input->post();  

      $this->db->select('hms_medicine_batch_stock.*,hms_medicine_batch_stock.id as p_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_batch_stock.hsn_no as hsn,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,hms_medicine_batch_stock.batch_no, hms_medicine_batch_stock.quantity as qty, hms_medicine_entry.min_alrt, hms_medicine_entry.discount, hms_medicine_entry.conversion');  
      $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id');
             
    if(!empty($post['medicine_name']))
    {
      $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$post['medicine_name'].'%"');
    }
    if(!empty($post['medicine_code']))
    {  
        $this->db->where('hms_medicine_entry.medicine_code LIKE "'.$post['medicine_code'].'%"');
    }
    if(!empty($post['qty']))
    {  
      $this->db->where('hms_medicine_batch_stock.quantity <='.$post['qty'].' as qty');
    }
    if(!empty($post['rate']))
    {  
      $this->db->where('hms_medicine_entry.mrp = "'.$post['rate'].'"');
    }
    if(!empty($post['discount']))
    {  
     $this->db->where('hms_medicine_entry.discount = "'.$post['discount'].'"');
    }
    if(!empty($post['sgst']))
    {  
      $this->db->where('hms_medicine_entry.sgst = "'.$post['sgst'].'"');
    }
    if(!empty($post['cgst']))
    {  
      $this->db->where('hms_medicine_entry.cgst = "'.$post['cgst'].'"');
      
    }
    if(!empty($post['hsn_no']))
    {  
      $this->db->where('hms_medicine_entry.hsn_no = "'.$post['hsn_no'].'"');
      
    }
    if(!empty($post['igst']))
    {  
      $this->db->where('hms_medicine_entry.igst = "'.$post['igst'].'"');
      
    }
    if(!empty($post['batch_number']))
    {  
      $this->db->where('hms_medicine_batch_stock.batch_no = "'.$post['batch_number'].'"');
      
    }
    if(!empty($post['bar_code']))
    {  
      $this->db->where('hms_medicine_batch_stock.bar_code LIKE "'.$post['bar_code'].'%"');
      
    }
    if(!empty($post['till_expiry']))
    {  
      $this->db->where('hms_medicine_batch_stock.expiry_date >= "'.date('Y-m-d').'"');
      $this->db->where('hms_medicine_batch_stock.expiry_date <= "'.date('Y-m-d', strtotime($post['till_expiry'])).'"');
    }
    if(!empty($post['stock']))
    {  
      $this->db->where('hms_medicine_batch_stock.quantity <='.$post['stock'].'');
      
    }
  
    if(!empty($post['packing']))
    {  
      $this->db->where('hms_medicine_entry.packing LIKE "'.$post['packing'].'"');
      
    }
    
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
        
       // $this->db->join('hms_medicine_batch_stock','hms_medicine_batch_stock.medicine_id = hms_medicine_entry.id','left');
    if(!empty($post['medicine_company']))
    {  
      $this->db->where('hms_medicine_company.company_name LIKE"'.$post['medicine_company'].'%"');

      
    }
    if(isset($added_medicine_list) && !empty($added_medicine_list))
    { 
      $arr_mids = [];
      if(!empty($added_medicine_list))
      { 
      foreach($added_medicine_list as $medicine_data)
      {
      $arr_mids[] = "'".$medicine_data['mid'].".".$medicine_data['batch_no']."'";
      }
            $keys = implode(',', $arr_mids);
      } 
           
      $this->db->where('CONCAT(hms_medicine_batch_stock.medicine_id,".",hms_medicine_batch_stock.batch_no) NOT IN ('.$keys.') ');
    } 

    //$this->db->where('hms_medicine_stock.kit_status',0);
    $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']); 
    $this->db->where('hms_medicine_entry.is_deleted','0'); /* previous comment in it */
    $this->db->group_by('hms_medicine_batch_stock.medicine_id');
    $this->db->group_by('hms_medicine_batch_stock.batch_no');
    
    if($medicine_order_setting=='1')
    {
      $this->db->order_by('hms_medicine_batch_stock.expiry_date', 'ASC');  
    }
    else if($medicine_order_setting==2)
    {
        $this->db->order_by('hms_medicine_batch_stock.mrp', 'DESC');
    }
    
    $this->db->limit('120');
        $this->db->from('hms_medicine_batch_stock'); 
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return  $query->result();
        //echo $this->db->last_query();die;
      
    }
    public function medicine_list_search20220212($medicine_order_setting='0')
    {

      $users_data = $this->session->userdata('auth_users'); 
      $added_medicine_list = $this->session->userdata('medicine_id');
      //print_r($added_medicine_list);
      $added_medicine_ids = array();
      $added_medicine_batch = array();
      $post = $this->input->post();  

      $this->db->select('hms_medicine_batch_stock.*,hms_medicine_batch_stock.id as p_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_batch_stock.hsn_no as hsn,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,hms_medicine_batch_stock.batch_no, hms_medicine_batch_stock.quantity as qty, hms_medicine_entry.min_alrt, hms_medicine_entry.discount, hms_medicine_entry.conversion');  
      $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id');
             
    if(!empty($post['medicine_name']))
    {
      $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$post['medicine_name'].'%"');
    }
    if(!empty($post['medicine_code']))
    {  
        $this->db->where('hms_medicine_entry.medicine_code LIKE "'.$post['medicine_code'].'%"');
    }
    if(!empty($post['qty']))
    {  
      $this->db->where('hms_medicine_batch_stock.quantity <='.$post['qty'].' as qty');
    }
    if(!empty($post['rate']))
    {  
      $this->db->where('hms_medicine_entry.mrp = "'.$post['rate'].'"');
    }
    if(!empty($post['discount']))
    {  
     $this->db->where('hms_medicine_entry.discount = "'.$post['discount'].'"');
    }
    if(!empty($post['sgst']))
    {  
      $this->db->where('hms_medicine_entry.sgst = "'.$post['sgst'].'"');
    }
    if(!empty($post['cgst']))
    {  
      $this->db->where('hms_medicine_entry.cgst = "'.$post['cgst'].'"');
      
    }
    if(!empty($post['hsn_no']))
    {  
      $this->db->where('hms_medicine_entry.hsn_no = "'.$post['hsn_no'].'"');
      
    }
    if(!empty($post['igst']))
    {  
      $this->db->where('hms_medicine_entry.igst = "'.$post['igst'].'"');
      
    }
    if(!empty($post['batch_number']))
    {  
      $this->db->where('hms_medicine_batch_stock.batch_no = "'.$post['batch_number'].'"');
      
    }
    if(!empty($post['bar_code']))
    {  
      $this->db->where('hms_medicine_batch_stock.bar_code LIKE "'.$post['bar_code'].'%"');
      
    }
    if(!empty($post['till_expiry']))
    {  
      $this->db->where('hms_medicine_batch_stock.expiry_date >= "'.date('Y-m-d').'"');
      $this->db->where('hms_medicine_batch_stock.expiry_date <= "'.date('Y-m-d', strtotime($post['till_expiry'])).'"');
    }
    if(!empty($post['stock']))
    {  
      $this->db->where('hms_medicine_batch_stock.quantity <='.$post['stock'].'');
      
    }
  
    if(!empty($post['packing']))
    {  
      $this->db->where('hms_medicine_entry.packing LIKE "'.$post['packing'].'"');
      
    }
    
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
        
       // $this->db->join('hms_medicine_batch_stock','hms_medicine_batch_stock.medicine_id = hms_medicine_entry.id','left');
    if(!empty($post['medicine_company']))
    {  
      $this->db->where('hms_medicine_company.company_name LIKE"'.$post['medicine_company'].'%"');

      
    }
    if(isset($added_medicine_list) && !empty($added_medicine_list))
    { 
      $arr_mids = [];
      if(!empty($added_medicine_list))
      { 
      foreach($added_medicine_list as $medicine_data)
      {
      $arr_mids[] = "'".$medicine_data['mid'].".".$medicine_data['batch_no']."'";
      }
            $keys = implode(',', $arr_mids);
      } 
           
      $this->db->where('CONCAT(hms_medicine_batch_stock.medicine_id,".",hms_medicine_batch_stock.batch_no) NOT IN ('.$keys.') ');
    } 

    //$this->db->where('hms_medicine_stock.kit_status',0);
    $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']); 
    $this->db->where('hms_medicine_entry.is_deleted','0'); /* previous comment in it */
    $this->db->where('hms_medicine_batch_stock.expiry_date >',date('Y-m-d'));
    $this->db->group_by('hms_medicine_batch_stock.medicine_id');
    $this->db->group_by('hms_medicine_batch_stock.batch_no');
    if($medicine_order_setting=='1')
    {
      $this->db->order_by('hms_medicine_batch_stock.expiry_date', 'ASC');  
    }
    else if($medicine_order_setting==2)
    {
        $this->db->order_by('hms_medicine_batch_stock.mrp', 'DESC');
    }
    
    
    $this->db->limit('120');
        $this->db->from('hms_medicine_batch_stock'); 
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return  $query->result();
        //echo $this->db->last_query();die;
      
    }

    function get_all_detail_print($ids="",$branch_ids="")
    {
        //(CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
        $result_sales=array();
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_medicine_sale.*,hms_patient.*,hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,
          (CASE WHEN hms_medicine_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale.refered_id=0 THEN concat('Other ',hms_medicine_sale.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name
          ,hms_simulation.simulation,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_religion.religion,hms_countries.country,hms_state.state,hms_cities.city,hms_relation.relation,hms_insurance_company.insurance_company,hms_sms.simulation as rel_simulation,hms_gardian_relation.relation,hms_patient.relation_simulation_id,hms_patient.relation_type,hms_patient.relation_name,hms_patient_category.patient_category as patient_category_name,hms_authorize_person.authorize_person as authorize_person_name"); 
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
        $this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
        $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital','left');
        $this->db->join('hms_patient_category','hms_patient_category.id = hms_medicine_sale.patient_category','left'); 
         $this->db->join('hms_authorize_person','hms_authorize_person.id = hms_medicine_sale.authorize_person','left');
        $this->db->join('hms_religion','hms_religion.id = hms_patient.religion_id','left');
        $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left');
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left');
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); 
        $this->db->join('hms_relation','hms_relation.id = hms_patient.relation_id','left'); 
        $this->db->join('hms_insurance_company','hms_insurance_company.id = hms_patient.ins_company_id','left'); 
            
        $this->db->join('hms_users','hms_users.id = hms_medicine_sale.created_by'); 
         $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
         $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
        $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_medicine_sale.id AND hms_branch_hospital_no.section_id=6','left');
        $this->db->where('hms_medicine_sale.is_deleted','0'); 

        //$this->db->where('hms_medicine_sale.branch_id = "'.$user_data['parent_id'].'"'); 
        $this->db->where('hms_medicine_sale.branch_id = "'.$branch_ids.'"'); 
        $this->db->where('hms_medicine_sale.id = "'.$ids.'"');
        $this->db->from($this->table);
        $result_sales['sales_list']= $this->db->get()->result();

        $this->db->select('hms_medicine_sale_to_medicine.*,hms_medicine_sale_to_medicine.discount as m_discount,hms_medicine_entry.*,hms_medicine_entry.discount as m_disc,hms_medicine_sale_to_medicine.sgst as m_sgst,hms_medicine_sale_to_medicine.igst as m_igst,hms_medicine_sale_to_medicine.cgst as m_cgst,hms_medicine_sale_to_medicine.batch_no as m_batch_no,hms_medicine_sale_to_medicine.expiry_date as m_expiry_date,hms_medicine_sale_to_medicine.hsn_no as m_hsn_no');
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id'); 
        $this->db->where('hms_medicine_sale_to_medicine.sales_id = "'.$ids.'"');
        $this->db->from('hms_medicine_sale_to_medicine');
        $result_sales['sales_list']['medicine_list']=$this->db->get()->result();
        return $result_sales;
    
    }

    function template_format($data=""){
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_print_branch_template.*');
      $this->db->where($data);
      //$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
      $this->db->from('hms_print_branch_template');
      $query=$this->db->get()->row();
      //print_r($query);exit;
      return $query;

    }

    public function check_stock_avability($id="",$batch_no="")
    {
    
    $user_data = $this->session->userdata('auth_users');
    $search=$this->session->userdata('stock_search');

    /*  code for deleted record */
    $p_ids=array();
    $p_r_ids=array();
    $s_ids=array();
    $s_r_ids=array();
    $new_s_r_ids='';
    $new_s_ids='';
    $new_p_r_ids='';
    $new_p_ids='';
    $where_1='';
    $where_2='';
    $where_3='';
    $where_4='';
    $deleted_purchase_medicine= $this->is_deleted_purchase_medicine();
    $deleted_purchase_return_medicine= $this->is_deleted_purchase_return_medicine();
    $deleted_sale_medicine= $this->is_deleted_sale_medicine();
    $deleted_sale_return_medicine= $this->is_deleted_sale_return_medicine();
    $this->db->select("(sum(debit)-sum(credit)) as avl_qty");
    
    $this->db->where('branch_id',$user_data['parent_id']);
    
    if(!empty($deleted_purchase_medicine))
    {
     foreach($deleted_purchase_medicine as $purchase_ids)
     {
      $p_ids[]=$purchase_ids['id'];
     }
     $new_p_ids=implode(',',$p_ids);
     $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_p_ids.")"); 
    }
     
    
    if(!empty($deleted_purchase_return_medicine))
    {
      foreach($deleted_purchase_return_medicine as $purchase_r_ids)
      {
        $p_r_ids[]=$purchase_r_ids['id'];
      }
        $new_p_r_ids=implode(',',$p_r_ids);
        $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_p_r_ids.")");
    }
    
  
    if(!empty($deleted_sale_medicine))
    {
      foreach($deleted_sale_medicine as $sale_ids)
      {
        $s_ids[]=$sale_ids['id'];
      }
      $new_s_ids=implode(',',$s_ids);
      $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_s_ids.")");
    }
    
  
    if(!empty($deleted_sale_return_medicine))
    {
      foreach($deleted_sale_return_medicine as $sale_r_ids)
      {
        $s_r_ids[]=$sale_r_ids['id'];
      }
        $new_s_r_ids=implode(',',$s_r_ids);
        $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_s_r_ids.")");
    }
    /*  code for deleted record */
    
    //$this->db->where('m_id',$mid);
    $this->db->where('hms_medicine_stock.m_id',$id);
    
    //$this->db->where('batch_no='.$batch_no.' OR  batch_no=0 OR batch_no=''');
    if(!empty($batch_no))
    {
      $this->db->where('hms_medicine_stock.batch_no',$batch_no);  
    }
    elseif($batch_no==0)
    {
       $this->db->where('hms_medicine_stock.batch_no','0');   
    }
    
    $this->db->where('hms_medicine_stock.is_deleted','0');  //on 6 Dec 2021
    $query = $this->db->get('hms_medicine_stock');
    //echo $this->db->last_query(); exit;
    //return $query->row_array();
    $result = $query->row(); 
     return $result->avl_qty;
     // }
    }


    function sms_template_format($data="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_sms_branch_template.*');
      $this->db->where($data);
      $this->db->where('hms_sms_branch_template.branch_id = "'.$users_data['parent_id'].'"'); 
      $this->db->from('hms_sms_branch_template');
      $query=$this->db->get()->row();
      //echo $this->db->last_query();
      return $query;

    }

    function sms_url()
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_branch_sms_config.*');
      //$this->db->where($data);
      $this->db->where('hms_branch_sms_config.branch_id = "'.$users_data['parent_id'].'"'); 
      $this->db->from('hms_branch_sms_config');
      $query=$this->db->get()->row();
      //echo $this->db->last_query();
      return $query;

    }

    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
    {
      $users_data = $this->session->userdata('auth_users'); 
  
      $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
    $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
      $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
      $this->db->where('hms_payment_mode_field_value_acc_section.type',3);
      $this->db->where('hms_payment_mode_field_value_acc_section.section_id',1);
      $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
      return $query;
    }

    function check_bar_code($bar_code="",$medicine_id="")
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');
    if(!empty($bar_code))
    {
     $this->db->where('bar_code',$bar_code);  
    }
    $this->db->where('branch_id',$users_data['parent_id']);
    $result= $this->db->get('hms_medicine_stock')->result();
    if(count($result)>0)
    {
      foreach($result as $res)
      {
        $res_new_medicine_array[]= $res->m_id;
      }
        $new_array= array_unique($res_new_medicine_array);
        if(in_array($medicine_id,$new_array))
        {
          return 1;
        }
        else
        {
          return 2;
        } 
    }
  }
  
  public function opd_patient($vals="")
  {
      $response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_opd_prescription.*,hms_patient.patient_code as p_code,hms_patient.patient_name as p_name,hms_patient.simulation_id,hms_patient.mobile_no as mobile,hms_patient.gender,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_opd_booking.referred_by,hms_opd_booking.referral_doctor,hms_opd_booking.ref_by_other,hms_opd_booking.referral_hospital,hms_opd_booking.remarks,hms_opd_prescription.id as pres_id,hms_patient.patient_email,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.age_h,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.city_id,hms_patient.state_id,hms_patient.id as pa_id"); 
          $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_opd_prescription.booking_id','left');
          $this->db->join('hms_patient','hms_patient.id=hms_opd_prescription.patient_id','left');
          
          $this->db->where('hms_opd_prescription.is_deleted','0'); 
          $this->db->where('hms_opd_prescription.branch_id = "'.$user_data['parent_id'].'"');
          //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
          $this->db->where('hms_opd_booking.booking_code LIKE "'.$vals.'%"');
          $this->db->from('hms_opd_prescription'); 
          //$this->db->group_by('hms_patient.id');
          $query = $this->db->get(); 
          $result = $query->result(); 
          //echo $this->db->last_query(); exit;
          //echo "<pre>"; print_r($result); exit;
          if(!empty($result))
          { 
            $data = array();
            foreach($result as $vals)
            {
                $name = $vals->p_name.' ('.$vals->p_code.')'.'|'.$vals->p_name.'|'.$vals->simulation_id.'|'.$vals->p_code.'|'.$vals->mobile.'|'.$vals->gender.'|'.$vals->relation_type.'|'.$vals->relation_simulation_id.'|'.$vals->relation_name.'|'.$vals->referred_by.'|'.$vals->referral_doctor.'|'.$vals->ref_by_other.'|'.$vals->referral_hospital.'|'.$vals->remarks.'|'.$vals->pres_id.'|'.$vals->patient_email.'|'.$vals->age_y.'|'.$vals->age_m.'|'.$vals->age_d.'|'.$vals->age_h.'|'.$vals->address.'|'.$vals->address2.'|'.$vals->address3.'|'.$vals->city_id.'|'.$vals->state_id.'|'.$vals->pa_id.'|'.$vals->booking_id;
                array_push($data, $name);
            }

            echo json_encode($data);
          }
          //return $response; 
      } 
    }


    public function prescription_medicine_list($medicine_id="")
    { 
        //print_r($medicine_id); exit;
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_medicine_entry.*');
        $this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
        $this->db->where('hms_medicine_entry.id',$medicine_id);
        $this->db->where('hms_medicine_entry.is_deleted','0');  
        $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->group_by('hms_medicine_entry.id');  
        $query = $this->db->get('hms_medicine_entry');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    public function prescription_medicine_array_list($ids="")
    { 
        $users_data = $this->session->userdata('auth_users'); 
        $medicine_list = $this->session->userdata('prescription_medicine_id');
        //print_r($ids);die;
        $keys = '';
        if(is_array($ids))
        { 
          $key_arr = [];
          $total_key = count($ids);
          $i=0;
          foreach($ids as $id)
          {
              $key_arr[] = "'".$id."'";
              $i++;
          }
          $keys = implode(',', $key_arr);
          
        }
        else 
        {
          $arr_mids = [];
          if(!empty($medicine_list))
          { 
          foreach($medicine_list as $medicine_data)
          {
            $arr_mids[] = "'".$medicine_data['mid']."'";
          }
            $keys = implode(',', $arr_mids);
          }
          //$keys = $ids.'.'.$batch_no;
        }
        //print_r($keys);die;
        $this->db->select('hms_medicine_entry.*');
        //$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id'); 
        if(!empty($keys))
        { 
            $this->db->where('hms_medicine_entry.id IN ('.$keys.') ');
        } 
        $this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
        $this->db->where('hms_medicine_entry.is_deleted','0');  
        $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->group_by('hms_medicine_entry.id');  
        $query = $this->db->get('hms_medicine_entry');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }
    
    
/* Estimate data */

    public function estimate_medicine($vals)
  {
    $response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
              $this->db->select('hms_estimate_sale.*,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender');
              $this->db->join('hms_patient','hms_patient.id=hms_estimate_sale.patient_id');
    $this->db->from('hms_estimate_sale'); 

     $this->db->where('hms_estimate_sale.sale_no LIKE "'.$vals.'%"');
    //$this->db->where('hms_estimate_sale.is_deleted','0');
    $query = $this->db->get(); 
 // print_r($this->db->last_query());die;
  $result= $query->row_array();
   //        $this->db->select("hms_estimate_sale.*,hms_estimate_sale_to_estimate.medicine_id,hms_estimate_sale_to_estimate.purchase_rate,hms_estimate_sale_to_estimate.freeunit1,hms_estimate_sale_to_estimate.freeunit2,hms_estimate_sale_to_estimate.unit1,hms_estimate_sale_to_estimate.unit2,hms_estimate_sale_to_estimate.bar_code,hms_estimate_sale_to_estimate.hsn_no,hms_estimate_sale_to_estimate.mrp,hms_estimate_sale_to_estimate.manuf_date,hms_estimate_sale_to_estimate.expiry_date,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code,hms_medicine_entry.hsn_no,hms_medicine_entry.packing,hms_medicine_entry.conversion"); 
   //       $this->db->join('hms_estimate_sale_to_estimate','hms_estimate_sale_to_estimate.purchase_id=hms_estimate_sale.id','left');
   //        $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_estimate_sale_to_estimate.medicine_id','left'); 
   //        //$this->db->where('hms_estimate_sale.id',$estimate_id);
     // //$this->db->where('hms_medicine_entry.id',$medicine_id);
   //        //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
   //        $this->db->where('hms_estimate_sale.purchase_id LIKE "'.$vals.'%"');
   //        $this->db->from('hms_estimate_sale'); 
   //       // $this->db->group_by('hms_estimate_sale.id');
   //        $query = $this->db->get(); 
   //        $result = $query->result(); 
   //        echo $this->db->last_query(); exit;
         // echo "<pre>"; print_r($result); exit;
          if(!empty($result))
          { 
            $data = array($result['sale_no'].'|'.$result['patient_name'].'|'.$result['mobile_no'].'|'.$result['gender']);
            // foreach($result as $vals)
            // {
            //     $name = $vals->purchase_id.'|'.$vals->medicine_code;
            //     array_push($data, $name);
            // }

            echo json_encode($data);
          }
          //return $response; 
      }
  }


  public function get_estimate_medicine_by_id($sales_id)
  {
    $response = '';
      if(!empty($sales_id))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_estimate_sale.id,hms_estimate_sale.total_amount as grand_total,hms_estimate_sale.discount as total_discount,hms_estimate_sale.sale_no,hms_estimate_sale_to_estimate.medicine_id,hms_estimate_sale_to_estimate.qty,hms_estimate_sale_to_estimate.per_pic_price,hms_estimate_sale_to_estimate.discount ,hms_estimate_sale_to_estimate.bar_code,hms_estimate_sale_to_estimate.hsn_no,hms_estimate_sale_to_estimate.total_amount ,hms_estimate_sale_to_estimate.manuf_date,hms_estimate_sale_to_estimate.expiry_date,hms_estimate_sale_to_estimate.sgst sgst,hms_estimate_sale_to_estimate.cgst a,hms_estimate_sale_to_estimate.igst ,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code,hms_medicine_entry.hsn_no,hms_medicine_entry.packing,hms_medicine_entry.conversion"); 
         $this->db->join('hms_estimate_sale_to_estimate','hms_estimate_sale_to_estimate.sales_id=hms_estimate_sale.id','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_estimate_sale_to_estimate.medicine_id','left'); 
          //$this->db->where('hms_estimate_sale.id',$estimate_id);
     //$this->db->where('hms_medicine_entry.id',$medicine_id);
          //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
          $this->db->where('hms_estimate_sale.sale_no ',$sales_id);
          $this->db->from('hms_estimate_sale'); 
         // $this->db->group_by('hms_estimate_sale.id');
          $query = $this->db->get(); 
          // echo $this->db->last_query(); exit;
          $result = $query->result_array();

          //return $result; 
         
         // echo "<pre>"; print_r($result); exit; 
          // if(!empty($result))
          // { 
            $post_mid_arr = array();
            foreach($result as $vals)
           {

        
               $post_mid_arr[$vals['medicine_id'].'.'.'0'] = array('mid'=>$vals['medicine_id'],'id'=>$vals['medicine_id'], 'batch_no'=>'', 'qty'=>$vals['qty'], 'exp_date'=>$vals['expiry_date'],'manuf_date'=>$vals['manuf_date'],'discount'=>$vals['discount'],'bar_code'=>$vals['bar_code'],'conversion'=>$vals['conversion'],'hsn_no'=>$vals['hsn_no'],'cgst'=>$vals['cgst'],'sgst'=>$vals['sgst'],'igst'=>$vals['igst'], 'per_pic_amount'=>$vals['per_pic_rate'],'mrp'=>$vals['mrp'],'total_amount'=>$vals['total_amount'],'total_pricewith_medicine'=>$vals['total_amount'],'medicine_name'=>$vals['medicine_name']); 
            }
            return $post_mid_arr;

          //   echo json_encode($data);
          // }
          //return $response; 
      }
  }
  
  /* Estimate data */
  
  function letterhead_template_format(){
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_print_letter_head_template_setting.*');
      $this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
      $this->db->where('unique_id',5); 
      $this->db->from('hms_print_letter_head_template_setting');
      $query=$this->db->get()->row();
      //print_r($query);exit;
      return $query;

    }



    public function payment_list($booking_id="",$branch_id="")
    {
      //echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_payment.*, hms_payment_mode.payment_mode"); 
     $this->db->from('hms_payment');
     $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
     $this->db->where('hms_payment.branch_id',$branch_id);
     $this->db->where('hms_payment.parent_id',$booking_id);
     $this->db->where('hms_payment.section_id','3');
     $this->db->order_by('hms_payment.id', 'DESC');
     $query = $this->db->get(); 
     return $query->result_array();
    }


    public function total_payment($booking_id="",$branch_id="",$section_id="")
    {
      //echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("
                         sum(hms_payment.credit) total_credit, 
                         sum(hms_payment.debit) total_debit, 
                         (sum(hms_payment.credit)-sum(hms_payment.debit)) as total_balance
                         "); 
     $this->db->from('hms_payment');
     $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
     $this->db->where('hms_payment.branch_id',$branch_id);
     $this->db->where('hms_payment.parent_id',$booking_id);
     $this->db->where('hms_payment.section_id',$section_id);
     $query = $this->db->get(); 
     //echo $this->db->last_query(); 
     return $query->row_array();
    }
    
    
    public function ipd_patient($vals="")
    {
      $response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_ipd_patient_prescription.*,hms_patient.patient_code as p_code,hms_patient.patient_name as p_name,hms_patient.simulation_id,hms_patient.mobile_no as mobile,hms_patient.gender,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_ipd_booking.referred_by,hms_ipd_booking.referral_doctor,hms_ipd_booking.ref_by_other,hms_ipd_booking.referral_hospital,hms_ipd_booking.remarks,hms_ipd_patient_prescription.id as pres_id,hms_patient.patient_email,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.age_h,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.city_id,hms_patient.state_id,hms_patient.id as pa_id"); 
          $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_prescription.booking_id','left');
          $this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_prescription.patient_id','left');
          
          $this->db->where('hms_ipd_patient_prescription.is_deleted','0'); 
          $this->db->where('hms_ipd_patient_prescription.branch_id = "'.$user_data['parent_id'].'"');
          //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
          $this->db->where('hms_ipd_booking.ipd_no LIKE "'.$vals.'%"');
          $this->db->from('hms_ipd_patient_prescription'); 
          $this->db->group_by('hms_patient.id');
          $query = $this->db->get(); 
          // echo $this->db->last_query(); exit;
          $result = $query->result(); 
         
          //echo "<pre>"; print_r($result); exit;
          if(!empty($result))
          { 
            $data = array();
            foreach($result as $vals)
            {
                $name = $vals->p_name.' ('.$vals->p_code.')'.'|'.$vals->p_name.'|'.$vals->simulation_id.'|'.$vals->p_code.'|'.$vals->mobile.'|'.$vals->gender.'|'.$vals->relation_type.'|'.$vals->relation_simulation_id.'|'.$vals->relation_name.'|'.$vals->referred_by.'|'.$vals->referral_doctor.'|'.$vals->ref_by_other.'|'.$vals->referral_hospital.'|'.$vals->remarks.'|'.$vals->pres_id.'|'.$vals->patient_email.'|'.$vals->age_y.'|'.$vals->age_m.'|'.$vals->age_d.'|'.$vals->age_h.'|'.$vals->address.'|'.$vals->address2.'|'.$vals->address3.'|'.$vals->city_id.'|'.$vals->state_id.'|'.$vals->pa_id.'|'.$vals->booking_id;
                array_push($data, $name);
            }

            echo json_encode($data);
          }
          //return $response; 
      } 
    }
    
    
    
    public function set_sales_estimate_medicine($sales_id)
  {
    $response = '';
      if(!empty($sales_id))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_estimate_sale.id,hms_estimate_sale.total_amount as grand_total,hms_estimate_sale.discount as total_discount,hms_estimate_sale.sale_no,hms_estimate_sale_to_estimate.medicine_id,hms_estimate_sale_to_estimate.qty,hms_estimate_sale_to_estimate.per_pic_price,hms_estimate_sale_to_estimate.discount ,hms_estimate_sale_to_estimate.bar_code,hms_estimate_sale_to_estimate.hsn_no,hms_estimate_sale_to_estimate.total_amount ,hms_estimate_sale_to_estimate.manuf_date,hms_estimate_sale_to_estimate.expiry_date,hms_estimate_sale_to_estimate.sgst sgst,hms_estimate_sale_to_estimate.cgst a,hms_estimate_sale_to_estimate.igst ,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code,hms_medicine_entry.hsn_no,hms_medicine_entry.packing,hms_medicine_entry.conversion"); 
         $this->db->join('hms_estimate_sale_to_estimate','hms_estimate_sale_to_estimate.sales_id=hms_estimate_sale.id','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_estimate_sale_to_estimate.medicine_id','left'); 
          //$this->db->where('hms_estimate_sale.id',$estimate_id);
     //$this->db->where('hms_medicine_entry.id',$medicine_id);
          //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
          $this->db->where('hms_estimate_sale.sale_no ',$sales_id);
          $this->db->from('hms_estimate_sale'); 
         // $this->db->group_by('hms_estimate_sale.id');
          $query = $this->db->get(); 
          // echo $this->db->last_query(); exit;
          $result = $query->result_array();

          //return $result; 
         
         // echo "<pre>"; print_r($result); exit; 
          // if(!empty($result))
          // { 
            $post_mid_arr = array();
            foreach($result as $vals)
           {

        
               $post_mid_arr[$vals['medicine_id'].'.'.'0'] = array('mid'=>$vals['medicine_id'],'id'=>$vals['medicine_id'], 'batch_no'=>'', 'qty'=>$vals['qty'], 'exp_date'=>$vals['expiry_date'],'manuf_date'=>$vals['manuf_date'],'discount'=>$vals['discount'],'bar_code'=>$vals['bar_code'],'conversion'=>$vals['conversion'],'hsn_no'=>$vals['hsn_no'],'cgst'=>$vals['cgst'],'sgst'=>$vals['sgst'],'igst'=>$vals['igst'], 'per_pic_amount'=>$vals['per_pic_rate'],'mrp'=>$vals['mrp'],'total_amount'=>$vals['total_amount'],'total_pricewith_medicine'=>$vals['total_amount'],'medicine_name'=>$vals['medicine_name']); 
            }
            return $post_mid_arr;

          //   echo json_encode($data);
          // }
          //return $response; 
      }
  }
  
  public function dialysis_patient($vals="")
  {
      $response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_dialysis_prescription.*,hms_patient.patient_code as p_code,hms_patient.patient_name as p_name,hms_patient.simulation_id,hms_patient.mobile_no as mobile,hms_patient.gender,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_dialysis_booking.referred_by,hms_dialysis_booking.referral_doctor,hms_dialysis_booking.ref_by_other,hms_dialysis_booking.referral_hospital,hms_dialysis_booking.remarks,hms_dialysis_prescription.id as pres_id,hms_patient.patient_email,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.age_h,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.city_id,hms_patient.state_id,hms_patient.id as pa_id"); 
          $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_prescription.booking_id','left');
          $this->db->join('hms_patient','hms_patient.id=hms_dialysis_prescription.patient_id','left');
          
          $this->db->where('hms_dialysis_prescription.is_deleted','0'); 
          $this->db->where('hms_dialysis_prescription.branch_id = "'.$user_data['parent_id'].'"');
          //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
          $this->db->where('hms_dialysis_booking.booking_code LIKE "%'.$vals.'%"');
          $this->db->from('hms_dialysis_prescription'); 
          $this->db->group_by('hms_patient.id');
          $query = $this->db->get(); 
          //echo $this->db->last_query(); exit;
          $result = $query->result(); 
         
          //echo "<pre>"; print_r($result); exit;
          if(!empty($result))
          { 
            $data = array();
            foreach($result as $vals)
            {
                $name = $vals->p_name.' ('.$vals->p_code.')'.'|'.$vals->p_name.'|'.$vals->simulation_id.'|'.$vals->p_code.'|'.$vals->mobile.'|'.$vals->gender.'|'.$vals->relation_type.'|'.$vals->relation_simulation_id.'|'.$vals->relation_name.'|'.$vals->referred_by.'|'.$vals->referral_doctor.'|'.$vals->ref_by_other.'|'.$vals->referral_hospital.'|'.$vals->remarks.'|'.$vals->pres_id.'|'.$vals->patient_email.'|'.$vals->age_y.'|'.$vals->age_m.'|'.$vals->age_d.'|'.$vals->age_h.'|'.$vals->address.'|'.$vals->address2.'|'.$vals->address3.'|'.$vals->city_id.'|'.$vals->state_id.'|'.$vals->pa_id.'|'.$vals->booking_id;
                array_push($data, $name);
            }

            echo json_encode($data);
          }
          //return $response; 
      } 
    }
    
  public function get_dialysis_patient_details($vals="")
  {
      $response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_dialysis_prescription.*,hms_patient.patient_code as p_code,hms_patient.patient_name as p_name,hms_patient.simulation_id,hms_patient.mobile_no as mobile,hms_patient.gender,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_dialysis_booking.referred_by,hms_dialysis_booking.referral_doctor,hms_dialysis_booking.ref_by_other,hms_dialysis_booking.referral_hospital,hms_dialysis_booking.remarks,hms_dialysis_prescription.id as pres_id,hms_patient.patient_email,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.age_h,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.city_id,hms_patient.state_id,hms_patient.id as pa_id"); 
          $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_prescription.booking_id','left');
          $this->db->join('hms_patient','hms_patient.id=hms_dialysis_prescription.patient_id','left');
          
          $this->db->where('hms_dialysis_prescription.is_deleted','0'); 
          $this->db->where('hms_dialysis_prescription.branch_id = "'.$user_data['parent_id'].'"');
          $this->db->where('hms_dialysis_prescription.id',$vals);
          $this->db->from('hms_dialysis_prescription'); 
          $this->db->group_by('hms_patient.id');
          $query = $this->db->get(); 
          //echo $this->db->last_query(); exit;
          $result = $query->result_array(); 
          return $result;
          //echo "<pre>"; print_r($result); exit;
          /*if(!empty($result))
          { 
            $data = array();
            foreach($result as $vals)
            {
                $name = $vals->p_name.' ('.$vals->p_code.')'.'|'.$vals->p_name.'|'.$vals->simulation_id.'|'.$vals->p_code.'|'.$vals->mobile.'|'.$vals->gender.'|'.$vals->relation_type.'|'.$vals->relation_simulation_id.'|'.$vals->relation_name.'|'.$vals->referred_by.'|'.$vals->referral_doctor.'|'.$vals->ref_by_other.'|'.$vals->referral_hospital.'|'.$vals->remarks.'|'.$vals->pres_id.'|'.$vals->patient_email.'|'.$vals->age_y.'|'.$vals->age_m.'|'.$vals->age_d.'|'.$vals->age_h.'|'.$vals->address.'|'.$vals->address2.'|'.$vals->address3.'|'.$vals->city_id.'|'.$vals->state_id.'|'.$vals->pa_id.'|'.$vals->booking_id;
                array_push($data, $name);
            }

            echo json_encode($data);
          }*/
          //return $response; 
      } 
    }
    
    public function get_medicine_estimate_by_sales_id($id="",$tamt="")
  { 
    $this->db->select('hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code, hms_estimate_sale_to_estimate.*,hms_medicine_sale.total_amount as t_amount, hms_medicine_entry.packing,hms_medicine_entry.mrp');
    $this->db->from('hms_estimate_sale_to_estimate'); 
    if(!empty($id))
    {
          $this->db->where('hms_estimate_sale_to_estimate.sales_id',$id);
          $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_estimate_sale_to_estimate.sales_id','left');
          $this->db->join('hms_medicine_batch_stock','hms_medicine_batch_stock.medicine_id=hms_estimate_sale_to_estimate.medicine_id AND hms_medicine_batch_stock.batch_no = hms_estimate_sale_to_estimate.batch_no','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_estimate_sale_to_estimate.medicine_id','left');
    }  
    $query1 = $this->db->get();
    //echo $this->db->last_query();die;
    $query = $query1->result(); 
    
    //echo '<pre>'; print_r($query);die; packing mrp 
    $result = []; 
    $total_price_medicine_amount='';

    if(!empty($query))
    {
      foreach($query as $medicine)  
      {   
          $result[$medicine->medicine_id.'.'.$medicine->batch_no] = array('medicine_name'=>$medicine->medicine_name, 'medicine_code'=>$medicine->medicine_code, 'packing'=>$medicine->packing, 'mrp'=>$medicine->mrp, 'mid'=>$medicine->medicine_id, 'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'batch_no'=>$medicine->batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->actual_amount, 'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
          $tamt = 0;
      } 
    } 
    //die;
    //print '<pre>';print_r($result);die;
    return $result;
  }
  
  
  public function get_opd_medicines($id="",$opd_type="0")
  {
      
        $this->load->model('medicine_order_setting/medicine_order_setting_model','medicine_order');
        $medicine_order = $this->medicine_order->get_default_setting();
        $medicine_order_setting = $medicine_order[1];
      
      if($opd_type==4)  //gynic
      {
          
          $users_data=$this->session->userdata('auth_users');
            $this->db->select("hms_medicine_entry.*"); 
            $this->db->from('hms_gynecology_prescription_medicine_booking');
            
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_gynecology_prescription_medicine_booking.medicine_id','left');
            
            if(!empty($prescription_id))
            {
              $this->db->where('hms_gynecology_prescription_medicine_booking.medicine_template_id',$prescription_id);  
            }
            
            $this->db->where('hms_gynecology_prescription_medicine_booking.booking_id',$id);
            $this->db->where('hms_gynecology_prescription_medicine_booking.branch_id',$users_data['parent_id']);
            $query = $this->db->get(); 
           // echo $this->db->last_query();die;
            $result_row = $query->result();
            $result_all = []; 
            if(!empty($result_row))
            {
              foreach($result_row as $medicine)  
              { 
             $this->db->select('hms_medicine_batch_stock.*,hms_medicine_batch_stock.id as p_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_batch_stock.hsn_no as hsn,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,hms_medicine_batch_stock.batch_no, hms_medicine_batch_stock.quantity as qty, hms_medicine_entry.min_alrt, hms_medicine_entry.discount, hms_medicine_entry.conversion');  
              $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id');
              $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
              if(!empty($medicine->id))
              {  
                $this->db->where('hms_medicine_batch_stock.medicine_id',$medicine->id);
              }
              $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']); 
              $this->db->where('hms_medicine_entry.is_deleted','0'); 
              $this->db->where('hms_medicine_batch_stock.expiry_date >',date('Y-m-d'));
              $this->db->where('hms_medicine_batch_stock.quantity >0');
              $this->db->group_by('hms_medicine_batch_stock.medicine_id');
              $this->db->group_by('hms_medicine_batch_stock.batch_no');
              $this->db->order_by('hms_medicine_batch_stock.expiry_date', 'ASC');  
              $this->db->limit('1');
              $this->db->from('hms_medicine_batch_stock'); 
              $query = $this->db->get(); 
              //echo $this->db->last_query();
              $result_row = $query->row_array();
              //echo "<pre>"; print_r($result_row); die;
              
            if(!empty($result_row))
            {
                
                
                if($result_row['mrp']>0)
                {
                    $per_pic_amount= $result_row['mrp']/$result_row['conversion'];
                    $tot_qty_with_rate= $per_pic_amount*1;
                    if($medicine_order_setting==1)
                    {
                        $total_discount = $result_row['discount'];
                    }
                    else
                    {
                        $total_discount = ($result_row['discount']/100)*$tot_qty_with_rate;
                    }
                    
                    $total_amount= $tot_qty_with_rate-$total_discount;
                    $cgstToPay = ($total_amount / 100) * $result_row['cgst'];
                    $igstToPay = ($total_amount / 100) * $result_row['igst'];
                    $sgstToPay = ($total_amount / 100) * $result_row['sgst'];
                    $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
                    $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount;
                }
                else
                {
                     $per_pic_amount= '';
                    $total_discount ='';
                    $total_amount= '';
                    $cgstToPay = '';
                    $igstToPay = '';
                    $sgstToPay = '';
                    $total_tax='';
                    $total_price_medicine_amount='';
                }
                if(!empty($result_row['batch_no']))
                {
                  $batch_no = $result_row['batch_no'];
                }
                else
                {
                  $batch_no = 0;
                }
                $manuf_date='';
                if(!empty($result_row['expiry_date']))
                {
                    $exp_date=date('d-m-Y',strtotime($result_row['expiry_date']));
                    
                }
                else
                {
                    $exp_date='';
                    
                }
                
                $result_all[] = array('mid'=>$result_row['medicine_id'],'medicine_name'=>$result_row['medicine_name'], 'medicine_code'=>$result_row['medicine_code'], 'packing'=>$result_row['packing'], 'mrp'=>$result_row['mrp'], 'qty'=>0, 'exp_date'=>$exp_date,'hsn_no'=>$result_row['hsn_no'],'discount'=>$result_row['discount'],'conversion'=>$result_row['conversion'],'manuf_date'=>$manuf_date,'batch_no'=>$batch_no,'cgst'=>$result_row['cgst'],'igst'=>$result_row['igst'],'sgst'=>$result_row['sgst'], 'per_pic_amount'=>$result_row['mrp'],'sale_amount'=>$result_row['actual_amount'], 'total_amount'=>0,'bar_code'=>'','total_pricewith_medicine'=>$tamt); 
                  $tamt = 0;
               
            }
              
      }
    }
            
            
        $result=$result_all;
         //echo "<pre>"; print_r($result); die;   
             
    }
    elseif($opd_type==2)  //pedic
    {
                
                
          
          $users_data=$this->session->userdata('auth_users');
            $this->db->select("hms_medicine_entry.*"); 
            $this->db->from('hms_pediatrician_prescription_patient_pres');
            
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_pediatrician_prescription_patient_pres.medicine_id','left');
            
            if(!empty($prescription_id))
            {
              $this->db->where('hms_pediatrician_prescription_patient_pres.medicine_template_id',$prescription_id);  
            }
            
            $this->db->where('hms_pediatrician_prescription_patient_pres.prescription_id',$id);
            //$this->db->where('hms_pediatrician_prescription_patient_pres.branch_id',$users_data['parent_id']);
            $query = $this->db->get(); 
            //echo $this->db->last_query();die;
            $result_row = $query->result(); 
            //echo '<pre>'; print_r($result_row);die;
            $result = []; 
            $total_price_medicine_amount='';
        
            if(!empty($result_row))
            {
              foreach($result_row as $medicine)  
              { 
                 
                if($medicine->mrp>0)
                {
                    $per_pic_amount= $medicine->mrp/$medicine->conversion;
                    $tot_qty_with_rate= $per_pic_amount*1;
                    if($medicine_order_setting==1)
                    {
                        $total_discount = $medicine->discount;
                    }
                    else
                    {
                        $total_discount = ($medicine->discount/100)*$tot_qty_with_rate;
                    }
                    
                    $total_amount= $tot_qty_with_rate-$total_discount;
                    $cgstToPay = ($total_amount / 100) * $medicine->cgst;
                    $igstToPay = ($total_amount / 100) * $medicine->igst;
                    $sgstToPay = ($total_amount / 100) * $medicine->sgst;
                    $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
                    $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount;
                }
                else
                {
                     $per_pic_amount= '';
                    $total_discount ='';
                    $total_amount= '';
                    $cgstToPay = '';
                    $igstToPay = '';
                    $sgstToPay = '';
                    $total_tax='';
                    $total_price_medicine_amount='';
                }
                if(!empty($medicine->batch_no))
                {
                  $batch_no = $medicine->batch_no;
                }
                else
                {
                  $batch_no = 0;
                }
                $manuf_date='';
                $exp_date='';
                  
                  
                  $result[] = array('mid'=>$medicine->id,'medicine_name'=>$medicine->medicine_name, 'medicine_code'=>$medicine->medicine_code, 'packing'=>$medicine->packing, 'mrp'=>$medicine->mrp, 'qty'=>0, 'exp_date'=>$exp_date,'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>$manuf_date,'batch_no'=>$batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->mrp,'sale_amount'=>$medicine->actual_amount, 'total_amount'=>0,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
                  $tamt = 0;
              } 
            } 
            
                
            }
            elseif($opd_type==3)  //Dental
            {
                
                
          
          $users_data=$this->session->userdata('auth_users');
            $this->db->select("hms_medicine_entry.*"); 
            $this->db->from('hms_dental_prescription_medicine_booking');
            
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_dental_prescription_medicine_booking.medicine_id','left');
            
            if(!empty($prescription_id))
            {
              $this->db->where('hms_dental_prescription_medicine_booking.medicine_template_id',$prescription_id);  
            }
            
            $this->db->where('hms_dental_prescription_medicine_booking.booking_id',$id);
            //$this->db->where('hms_pediatrician_prescription_patient_pres.branch_id',$users_data['parent_id']);
            $query = $this->db->get(); 
            //echo $this->db->last_query();die;
            $result_row = $query->result(); 
            //echo '<pre>'; print_r($result_row);die;
            $result = []; 
            $total_price_medicine_amount='';
        
            if(!empty($result_row))
            {
              foreach($result_row as $medicine)  
              { 
                 
                if($medicine->mrp>0)
                {
                    $per_pic_amount= $medicine->mrp/$medicine->conversion;
                    $tot_qty_with_rate= $per_pic_amount*1;
                    if($medicine_order_setting==1)
                    {
                        $total_discount = $medicine->discount;
                    }
                    else
                    {
                        $total_discount = ($medicine->discount/100)*$tot_qty_with_rate;
                    }
                    
                    $total_amount= $tot_qty_with_rate-$total_discount;
                    $cgstToPay = ($total_amount / 100) * $medicine->cgst;
                    $igstToPay = ($total_amount / 100) * $medicine->igst;
                    $sgstToPay = ($total_amount / 100) * $medicine->sgst;
                    $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
                    $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount;
                }
                else
                {
                     $per_pic_amount= '';
                    $total_discount ='';
                    $total_amount= '';
                    $cgstToPay = '';
                    $igstToPay = '';
                    $sgstToPay = '';
                    $total_tax='';
                    $total_price_medicine_amount='';
                }
                if(!empty($medicine->batch_no))
                {
                  $batch_no = $medicine->batch_no;
                }
                else
                {
                  $batch_no = 0;
                }
                $manuf_date='';
                $exp_date='';
                  
                  
                  $result[] = array('mid'=>$medicine->id,'medicine_name'=>$medicine->medicine_name, 'medicine_code'=>$medicine->medicine_code, 'packing'=>$medicine->packing, 'mrp'=>$medicine->mrp, 'qty'=>0, 'exp_date'=>$exp_date,'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>$manuf_date,'batch_no'=>$batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->mrp,'sale_amount'=>$medicine->actual_amount, 'total_amount'=>0,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
                  $tamt = 0;
              } 
            } 
            
                
            }
            elseif($opd_type==0)  //Dental
            {
                
                
          
          $users_data=$this->session->userdata('auth_users');
            $this->db->select("hms_medicine_entry.*"); 
            $this->db->from('hms_opd_prescription_patient_pres');
            
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_opd_prescription_patient_pres.medicine_id','left');
            
            if(!empty($prescription_id))
            {
              $this->db->where('hms_opd_prescription_patient_pres.medicine_template_id',$prescription_id);  
            }
            
            $this->db->where('hms_opd_prescription_patient_pres.prescription_id',$id);
            //$this->db->where('hms_pediatrician_prescription_patient_pres.branch_id',$users_data['parent_id']);
            $query = $this->db->get(); 
            //echo $this->db->last_query();die;
            $result_row = $query->result(); 
            //echo '<pre>'; print_r($result_row);die;
            $result = []; 
            $total_price_medicine_amount='';
        
            if(!empty($result_row))
            {
              foreach($result_row as $medicine)  
              { 
                 
                if($medicine->mrp>0)
                {
                    $per_pic_amount= $medicine->mrp/$medicine->conversion;
                    $tot_qty_with_rate= $per_pic_amount*1;
                    if($medicine_order_setting==1)
                    {
                        $total_discount = $medicine->discount;
                    }
                    else
                    {
                        $total_discount = ($medicine->discount/100)*$tot_qty_with_rate;
                    }
                    
                    $total_amount= $tot_qty_with_rate-$total_discount;
                    $cgstToPay = ($total_amount / 100) * $medicine->cgst;
                    $igstToPay = ($total_amount / 100) * $medicine->igst;
                    $sgstToPay = ($total_amount / 100) * $medicine->sgst;
                    $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
                    $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount;
                }
                else
                {
                     $per_pic_amount= '';
                    $total_discount ='';
                    $total_amount= '';
                    $cgstToPay = '';
                    $igstToPay = '';
                    $sgstToPay = '';
                    $total_tax='';
                    $total_price_medicine_amount='';
                }
                if(!empty($medicine->batch_no))
                {
                  $batch_no = $medicine->batch_no;
                }
                else
                {
                  $batch_no = 0;
                }
                $manuf_date='';
                $exp_date='';
                  
                  
                  $result[] = array('mid'=>$medicine->id,'medicine_name'=>$medicine->medicine_name, 'medicine_code'=>$medicine->medicine_code, 'packing'=>$medicine->packing, 'mrp'=>$medicine->mrp, 'qty'=>0, 'exp_date'=>$exp_date,'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>$manuf_date,'batch_no'=>$batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->mrp,'sale_amount'=>$medicine->actual_amount, 'total_amount'=>0,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
                  $tamt = 0;
              } 
            } 
            
                
            }
            
         
            return $result;
          }
    

} 
?>