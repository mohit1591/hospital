<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_return_vaccination_model extends CI_Model 
{
	var $table = 'hms_vaccination_sale_return';
	var $column = array('hms_vaccination_sale_return.id','hms_patient.patient_name','hms_vaccination_sale_return.sale_no','hms_vaccination_sale_return.id','hms_vaccination_sale_return.total_amount','hms_vaccination_sale_return.net_amount','hms_vaccination_sale_return.paid_amount','hms_vaccination_sale_return.balance','hms_vaccination_sale_return.created_date', 'hms_vaccination_sale_return.modified_date');  
		var $order = array('id' => 'desc'); 
 
 public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('vaccine_return_sale_search');
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_vaccination_sale_return.*, hms_patient.patient_name, (select count(id) from hms_vaccination_sale_return_vaccination where sales_return_id = hms_vaccination_sale_return.id) as total_medicine,



			(CASE WHEN hms_vaccination_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_vaccination_sale_return.refered_id=0 THEN concat('Other ',hms_vaccination_sale_return.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name"); 

		//(CASE WHEN hms_vaccination_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,
		$this->db->join('hms_patient','hms_patient.id = hms_vaccination_sale_return.patient_id','left'); 
		
		$this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale_return.referral_hospital','left');
		$this->db->join('hms_doctors','hms_doctors.id = hms_vaccination_sale_return.refered_id','left');
		$this->db->where('hms_vaccination_sale_return.is_deleted','0'); 
		//$this->db->where('hms_patient.is_deleted','0'); 
		//$this->db->where('hms_doctors.is_deleted','0'); 
		
		//$this->db->where('hms_vaccination_sale_return.branch_id = "'.$user_data['parent_id'].'"'); 
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_vaccination_sale_return.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_vaccination_sale_return.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		$this->db->from($this->table);
        	if(isset($search) && !empty($search))
		{
			
            if(isset($search['start_date'])&& !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_vaccination_sale_return.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_vaccination_sale_return.sale_date <= "'.$end_date.'"');
			}


			if(isset($search['patient_name']) && !empty($search['patient_name']))
			{

				$this->db->where('hms_patient.patient_name',$search['patient_name']);
			}

			if(isset($search['patient_code']) && !empty($search['patient_code']))

			{
				
			  $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
			}

			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['adhar_no']) &&  !empty($search['adhar_no']))
			{
				$this->db->where('hms_patient.adhar_no LIKE "'.$search['adhar_no'].'%"');
			}
			
			if($search['referred_by']=='1' && !empty($search['referral_hospital']))
			{
				$this->db->where('hms_vaccination_sale_return.referral_hospital' ,$search['referral_hospital']);
			}
			elseif($search['referred_by']=='0' && !empty($search['refered_id']))
			{
				$this->db->where('hms_vaccination_sale_return.refered_id' ,$search['refered_id']);
			}

			if(isset($search['sale_no']) && !empty($search['sale_no']))
			{
				$this->db->where('hms_vaccination_sale_return.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['vaccination_name']) && !empty($search['vaccination_name']))
			{
				$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
				$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
				$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['vaccination_name'].'%"');
			}

			if(isset($search['vaccination_company']) &&  !empty($search['vaccination_company']))
			{
				if(empty($search['vaccination_name'])){
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left');
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
				}
				
				$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
				$this->db->where('hms_vaccination_company.company_name LIKE"'.$search['vaccination_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_medicine_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			/*if(!empty($search['purchase_rate']))
			{
				$this->db->where('hms_medicine_purchase.purchase_rate',$search['purchase_rate']);
			}*/

				if(isset($search['discount']) &&!empty($search['discount']))
				{
					if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']))
					{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_medicine_sale.id','left'); 

					}
					$this->db->where('hms_vaccination_sale_return_vaccination.discount = "'.$search['discount'].'"');
				}

				if(isset($search['igst']) && !empty($search['igst']))
				{
				// if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale_return.igst', $search['igst']);
				}

				if(isset($search['cgst']) && !empty($search['cgst']))
				{
				// if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale_return.cgst', $search['cgst']);
				}

				if(isset($search['sgst']) && !empty($search['sgst']))
				{
				// if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale_return.sgst', $search['sgst']);
				}

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']) && empty($search['cgst'])&& empty($search['sgst'])&& empty($search['igst']))
				{
				$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 

				}
				$this->db->where('hms_vaccination_sale_return_vaccination.batch_no = "'.$search['batch_no'].'"');
			}

			/*if(isset($search['unit1']) && $search['unit1']!="")
			{
				$this->db->where('hms_vaccination_unit.id = "'.$search['unit1'].'"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				$this->db->where('hms_vaccination_unit_2.id ="'.$search['unit2'].'"');
			}*/

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['cgst'])&& empty($search['sgst'])&& empty($search['igst']) && empty($search['batch_no']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_vaccination_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']) && empty($search['cgst'])&& empty($search['sgst'])&& empty($search['igst']) && empty($search['batch_no'])&& empty($search['packing']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']) && empty($search['cgst'])&& empty($search['sgst'])&& empty($search['igst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']) && empty($search['cgst'])&& empty($search['sgst'])&& empty($search['igst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion'])&& empty($search['mrp_to']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_vaccination_sale_return.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
			{
				$this->db->where('hms_vaccination_sale_return.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) && $search['balance_to']!="")
			{
				$this->db->where('hms_vaccination_sale_return.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_vaccination_sale_return.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_vaccination_sale_return.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_vaccination_sale_return.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_vaccination_sale_return.bank_name',$search['bank_name']);
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
			$this->db->where('hms_vaccination_sale_return.created_by IN ('.$emp_ids.')');
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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	function search_report_data(){
		$search = $this->session->userdata('vaccine_return_sale_search');
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_vaccination_sale_return.*, hms_patient.patient_name, (select count(id) from hms_vaccination_sale_return_vaccination where sales_return_id = hms_vaccination_sale_return.id) as total_medicine,

			(CASE WHEN hms_vaccination_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_vaccination_sale_return.refered_id=0 THEN concat('Other ',hms_vaccination_sale_return.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name"); 

		//(CASE WHEN hms_vaccination_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
		$this->db->join('hms_patient','hms_patient.id = hms_vaccination_sale_return.patient_id','left'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_vaccination_sale_return.refered_id','left');
		$this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale_return.referral_hospital','left');
		$this->db->where('hms_vaccination_sale_return.is_deleted','0'); 
		//$this->db->where('hms_patient.is_deleted','0'); 
		//$this->db->where('hms_doctors.is_deleted','0');
		//$this->db->where('hms_hospital.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_vaccination_sale_return.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_vaccination_sale_return.branch_id = "'.$user_data['parent_id'].'"');
		}

		//$this->db->where('hms_vaccination_sale_return.branch_id = "'.$user_data['parent_id'].'"'); 
		
		
		$this->db->from($this->table);
        	if(isset($search) && !empty($search))
		{
			
            if(isset($search['start_date'])&& !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_vaccination_sale_return.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_vaccination_sale_return.sale_date <= "'.$end_date.'"');
			}

			if(isset($search['adhar_no']) &&  !empty($search['adhar_no']))
			{
				$this->db->where('hms_patient.adhar_no LIKE "'.$search['adhar_no'].'%"');
			}
			

			if(isset($search['patient_name']) && !empty($search['patient_name']))
			{

				$this->db->where('hms_patient.patient_name',$search['patient_name']);
			}

			if(isset($search['patient_code']) && !empty($search['patient_code']))

			{
				
			  $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
			}

			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}
			if($search['referred_by']=='1' && !empty($search['referral_hospital']))
			{
				$this->db->where('hms_vaccination_sale_return.referral_hospital' ,$search['referral_hospital']);
			}
			elseif($search['referred_by']=='0' && !empty($search['refered_id']))
			{
				$this->db->where('hms_vaccination_sale_return.refered_id' ,$search['refered_id']);
			}
			

			if(isset($search['sale_no']) && !empty($search['sale_no']))
			{
				$this->db->where('hms_vaccination_sale_return.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
				$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
				$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left');
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
				}
				
				$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
				$this->db->where('hms_vaccination_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_medicine_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			/*if(!empty($search['purchase_rate']))
			{
				$this->db->where('hms_medicine_purchase.purchase_rate',$search['purchase_rate']);
			}*/

			if(isset($search['discount']) &&!empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']))
				{
				$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_medicine_sale.id','left'); 

				}
				$this->db->where('hms_vaccination_sale_return_vaccination.discount = "'.$search['discount'].'"');
			}

			if(isset($search['igst']) && !empty($search['igst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']))
				{
				$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
			   }
				$this->db->where('hms_vaccination_sale_return_vaccination.igst', $search['igst']);
			  }
			  if(isset($search['cgst']) && !empty($search['cgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']))
				{
				$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
			   }
				$this->db->where('hms_vaccination_sale_return_vaccination.cgst', $search['cgst']);
			  }
			  if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']))
				{
				$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
			   }
				$this->db->where('hms_vaccination_sale_return_vaccination.sgst', $search['sgst']);
			  }

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']) && empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']))
				{
				$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 

			}
				$this->db->where('hms_vaccination_sale_return_vaccination.batch_no = "'.$search['batch_no'].'"');
			}

			/*if(isset($search['unit1']) && $search['unit1']!="")
			{
				$this->db->where('hms_vaccination_unit.id = "'.$search['unit1'].'"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				$this->db->where('hms_vaccination_unit_2.id ="'.$search['unit2'].'"');
			}*/

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']) && empty($search['batch_no']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_vaccination_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']) && empty($search['batch_no'])&& empty($search['packing']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion'])&& empty($search['mrp_to']))
				{
					$this->db->join('hms_vaccination_sale_return_vaccination','hms_vaccination_sale_return_vaccination.sales_return_id = hms_vaccination_sale_return.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_vaccination_sale_return.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
			{
				$this->db->where('hms_vaccination_sale_return.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) && $search['balance_to']!="")
			{
				$this->db->where('hms_vaccination_sale_return.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_vaccination_sale_return.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_vaccination_sale_return.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_vaccination_sale_return.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_vaccination_sale_return.bank_name',$search['bank_name']);
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
			$this->db->where('hms_vaccination_sale_return.created_by IN ('.$emp_ids.')');
		}
		$query = $this->db->get(); 

		$data= $query->result();
		return $data;
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

	public function get_by_id($id)
	{
		$this->db->select('hms_vaccination_sale_return.*');
		$this->db->from('hms_vaccination_sale_return'); 
		 $this->db->where('hms_vaccination_sale_return.id',$id);
		$this->db->where('hms_vaccination_sale_return.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function get_by_id_expense($id)
	{
		$this->db->select('hms_expenses.*');
		$this->db->from('hms_expenses'); 
		 $this->db->where('hms_expenses.parent_id',$id);
		$this->db->where('hms_expenses.is_deleted','0');
		$this->db->where('hms_expenses.type','6');
		$query = $this->db->get(); 
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
		$this->db->select('hms_vaccination_sale_return_vaccination.*');
		$this->db->from('hms_vaccination_sale_return_vaccination'); 
		if(!empty($id))
		{
          $this->db->where('hms_vaccination_sale_return_vaccination.sales_return_id',$id);
		} 
		$query = $this->db->get()->result(); 
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $medicine)	
		  { 
                 $result[$medicine->vaccine_id.'.'.$medicine->batch_no] = array('vid'=>$medicine->vaccine_id, 'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'batch_no'=>$medicine->batch_no,'igst'=>$medicine->igst,'hsn_no'=>$medicine->hsn_no,'cgst'=>$medicine->cgst,'sgst'=>$medicine->sgst,'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->actual_amount,'bar_code'=>$medicine->bar_code,'total_amount'=>$medicine->total_amount,'total_pricewith_medicine'=>$tamt); 
		  } 
		} 
		
		return $result;
	}


	public function save()
	{
       //print_r($_POST);die;
		
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//print_r($post);die;
		$data_patient = array(
				//"patient_code"=>$post['patient_reg_code'],
				"patient_name"=>$post['name'],
				'branch_id'=>$user_data['parent_id'],
				'simulation_id'=>$post['simulation_id'],
				'gender'=>$post['gender'],
				'adhar_no'=>$post['adhar_no'],
				'relation_type'=>$post['relation_type'],
				'relation_name'=>$post['relation_name'],
				'relation_simulation_id'=>$post['relation_simulation_id'],
				'mobile_no'=>$post['mobile']
			 );
				
		if(!empty($post['data_id']) && $post['data_id']>0)
		{  


			/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>4,'section_id'=>16));
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
						'type'=>4,
						'section_id'=>16,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
			}

			/*add sales banlk detail*/	

            $purchase_detail= $this->get_by_id($post['data_id']);
		    $patient_id_new= $this->get_patient_by_id($purchase_detail['patient_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['patient_id']);
			$this->db->update('hms_patient',$data_patient);

          /*if(isset($post['bank_name'])){

			$bank_name=$post['bank_name'];
			}else{
			$bank_name='';
			}
			if(isset($post['card_no'])){

			$card_no=$post['card_no'];
			}else{
			$card_no='';
			}
			if(isset($post['cheque_no'])){

			$cheque_no=$post['cheque_no'];
			}else{
			$cheque_no='';
			}
			if(isset($post['payment_date'])){

			$payment_date=date('Y-m-d',strtotime($post['payment_date']));

			}else{
			$payment_date='';
			}
			if(isset($post['transaction_no'])){

			$transaction_no=$post['transaction_no'];
			}else{
			$transaction_no='';
			}*/
            $blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$data = array(
				"patient_id"=>$post['patient_id'],
				'branch_id'=>$user_data['parent_id'],
				'sale_no'=>$post['sales_no'],
				'refered_id'=>$post['refered_id'],
				'simulation_id'=>$post['simulation_id'],
				'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
				'total_amount'=>str_replace(',', '', $post['total_amount']),
				'net_amount'=>str_replace(',', '', $post['net_amount']),
				'discount'=>$post['discount_amount'],
				'igst'=>$post['igst_amount'],
				'cgst'=>$post['cgst_amount'],
				'sgst'=>$post['sgst_amount'],
				'payment_mode'=>$post['payment_mode'],
				//'bank_name'=>$bank_name,
				//'card_no'=>$card_no,
				//'cheque_no'=>$cheque_no,
				//'payment_date'=>$payment_date,
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				"discount_percent"=>$post['discount_percent'],
				//"vat_percent"=>$post['vat_percent'],
				'paid_amount'=>$post['pay_amount'],
				'referred_by'=>$post['referred_by'],
                'referral_hospital'=>$post['referral_hospital'],
                'ref_by_other'=>$post['ref_by_other'],
				//'transaction_no'=>$transaction_no
			); 

			$this->db->where('id',$post['data_id']);
			//$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->update('hms_vaccination_sale_return',$data);
            
            $created_by= $this->get_by_id_expense($post['data_id']);
            
            $this->db->where('parent_id',$post['data_id']);
            $this->db->delete('hms_expenses');
            
            $data_expenses= array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>6,
					'vouchar_no'=>generate_unique_id(19),
					'parent_id'=>$post['data_id'],
                    'expenses_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                    'paid_to_id'=>'',
                    'paid_amount'=>$post['pay_amount'],
                    'payment_mode'=>$post['payment_mode'],
                    //'cheque_no'=>$cheque_no,
                    //'branch_name'=>$bank_name,
                    //'cheque_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                    //'transaction_no'=>$transaction_no,
                    'created_by'=>$created_by['created_by'],
                    'created_date'=>date('Y-m-d H:i:s',strtotime($created_by['created_date']))
					);
				$this->db->insert('hms_expenses',$data_expenses);
                $sales_id= $post['data_id'];
                

			$sess_medicine_list= $this->session->userdata('vaccine_id');
			//echo '<pre>';print_r($sess_medicine_list);die;
			$this->db->where('sales_return_id',$post['data_id']);
            $this->db->delete('hms_vaccination_sale_return_vaccination');

             $this->db->where(array('parent_id'=>$post['data_id'],'type'=>4));
            $this->db->delete('hms_vaccination_stock');
            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{
					$data_purchase_topurchase = array(
					"sales_return_id"=>$post['data_id'],
					'vaccine_id'=>$medicine_list['vid'],
					'qty'=>$medicine_list['qty'],
					'discount'=>$medicine_list['discount'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'sgst'=>$medicine_list['sgst'],
					'hsn_no'=>$medicine_list['hsn_no'],
					'bar_code'=>$medicine_list['bar_code'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'batch_no'=>$medicine_list['batch_no'],
					'per_pic_price'=>$medicine_list['per_pic_amount'],
					'conversion'=>$medicine_list['conversion'],
					'actual_amount'=>$medicine_list['sale_amount']
					);
					$this->db->insert('hms_vaccination_sale_return_vaccination',$data_purchase_topurchase);
				 
				  $data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>4,
					    	"parent_id"=>$post['data_id'],
					    	"v_id"=>$medicine_list['vid'],
					    	"credit"=>0,
					    	"debit"=>$medicine_list['qty'],
					    	"created_by"=>$user_data['id'],
							"batch_no"=>$medicine_list['batch_no'],
							"conversion"=>$medicine_list['conversion'],
							"mrp"=>$medicine_list['sale_amount'],
							"discount"=>$medicine_list['discount'],
							'igst'=>$medicine_list['igst'],
							'cgst'=>$medicine_list['cgst'],
							'sgst'=>$medicine_list['sgst'],
							'hsn_no'=>$medicine_list['hsn_no'],
							'bar_code'=>$medicine_list['bar_code'],
							"total_amount"=>$medicine_list['total_amount'],
							"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
							'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
							'per_pic_rate'=>$medicine_list['per_pic_amount'],
					    	"created_date"=>date('Y-m-d H:i:s'),
					    	);
						 $this->db->insert('hms_vaccination_stock',$data_new_stock);

				}
            } 

               /*$payment_mode='';
				if($post['payment_mode']==1){
				$payment_mode='cash';
				}
				if($post['payment_mode']==2){
				$payment_mode='card';
				}
				if($post['payment_mode']==3){
				$payment_mode='cheque';
				}
				if($post['payment_mode']==4){
				$payment_mode='neft';
				}*/
				

                /*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>6,'section_id'=>1));
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
						'type'=>6,
						'section_id'=>16,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
			}

			/*add sales banlk detail*/	
            
		}
		else{
			$sale_no = generate_unique_id(40);
			$patient_reg_code=generate_unique_id(4);
			$vendor_data= $this->get_patient_by_id($post['patient_id']);
			if(count($vendor_data)>0){
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
              $this->db->select('*');
              $this->db->where('users_role','4');
              $query = $this->db->get('hms_permission_to_role');     
               $permission_list = $query->result();
		        if(!empty($permission_list))
		        {
		          foreach($permission_list as $permission)
		          {
		            $data = array(
		                    'users_role' =>4,
		                    'users_id' => $users_id,
		                    'master_id' => $patient_id,
		                    'section_id' => $permission->section_id,
		                    'action_id' => $permission->action_id, 
		                    'permission_status' => '1',
		                    'ip_address' => $_SERVER['REMOTE_ADDR'],
		                    'created_by' =>$user_data['id'],
		                    'created_date' =>date('Y-m-d H:i:s'),
		                 );
		            $this->db->insert('hms_permission_to_users',$data);
		          }
		        }

		        ////////// Send SMS /////////////////////
		        if(in_array('640',$user_data['permission']['action']))
		        {
		          if(!empty($post['mobile']))
		          {
		          	 
		          	send_sms('patient_registration',18,$post['name'],$post['mobile'],array('{patient_name}'=>$post['name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id));
		          }
		        }	
			
			
		}
			/*if(isset($post['bank_name'])){

			$bank_name=$post['bank_name'];
			}else{
			$bank_name='';
			}
			if(isset($post['card_no'])){

			$card_no=$post['card_no'];
			}else{
			$card_no='';
			}
			if(isset($post['cheque_no'])){

			$cheque_no=$post['cheque_no'];
			}else{
			$cheque_no='';
			}
			if(isset($post['payment_date'])){

			$payment_date=date('Y-m-d',strtotime($post['payment_date']));

			}else{
			$payment_date='';
			}
			if(isset($post['transaction_no'])){

			$transaction_no=$post['transaction_no'];
			}else{
			$transaction_no='';
			}*/
            $blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$data = array(
				"patient_id"=>$patient_id,
				'branch_id'=>$user_data['parent_id'],
				'sale_no'=>$post['sales_no'],
				'return_no'=>$sale_no,
				'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
				'total_amount'=>str_replace(',', '', $post['total_amount']),
				'net_amount'=>str_replace(',', '', $post['net_amount']),
				'discount'=>$post['discount_amount'],
				'refered_id'=>$post['refered_id'],
				'simulation_id'=>$post['simulation_id'],
				'igst'=>$post['igst_amount'],
				'cgst'=>$post['cgst_amount'],
				'sgst'=>$post['sgst_amount'],
				'payment_mode'=>$post['payment_mode'],
				"discount_percent"=>$post['discount_percent'],
				//"vat_percent"=>$post['vat_percent'],
				//'bank_name'=>$bank_name,
				//'card_no'=>$card_no,
				//'cheque_no'=>$cheque_no,
				//'payment_date'=>$payment_date,
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				'paid_amount'=>$post['pay_amount'],
				'referred_by'=>$post['referred_by'],
                'referral_hospital'=>$post['referral_hospital'],
                'ref_by_other'=>$post['ref_by_other'],
				//'transaction_no'=>$transaction_no
			); 

			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_vaccination_sale_return',$data);
     
			$sales_id= $this->db->insert_id();
			if(!empty( $post['field_name']))
			{
			$post_field_value_name= $post['field_name'];
				$counter_name= count($post_field_value_name); 
				for($i=0;$i<$counter_name;$i++) 
				{
				$data_field_value= array(
					'field_value'=>$post['field_name'][$i],
					'field_id'=>$post['field_id'][$i],
					'type'=>4,
					'section_id'=>16,
					'p_mode_id'=>$post['payment_mode'],
					'branch_id'=>$user_data['parent_id'],
					'parent_id'=>$sales_id,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
					);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}	
			}
			

			$sess_medicine_list= $this->session->userdata('vaccine_id'); 
            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{
					$data_purchase_topurchase = array(
					"sales_return_id"=>$sales_id,
					'vaccine_id'=>$medicine_list['vid'],
					'qty'=>$medicine_list['qty'],
					'discount'=>$medicine_list['discount'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'sgst'=>$medicine_list['sgst'],
					'hsn_no'=>$medicine_list['hsn_no'],
					'bar_code'=>$medicine_list['bar_code'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'batch_no'=>$medicine_list['batch_no'],
					'per_pic_price'=>$medicine_list['per_pic_amount'],
					'conversion'=>$medicine_list['conversion'],
					'actual_amount'=>$medicine_list['sale_amount']
					);
					$this->db->insert('hms_vaccination_sale_return_vaccination',$data_purchase_topurchase);
				
                  $data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>4,
					    	"parent_id"=>$sales_id,
					    	"v_id"=>$medicine_list['vid'],
					    	"credit"=>0,
					    	"debit"=>$medicine_list['qty'],
					    	"created_by"=>$user_data['id'],
							"batch_no"=>$medicine_list['batch_no'],
							"conversion"=>$medicine_list['conversion'],
							"mrp"=>$medicine_list['sale_amount'],
							"discount"=>$medicine_list['discount'],
							'igst'=>$medicine_list['igst'],
							'cgst'=>$medicine_list['cgst'],
							'sgst'=>$medicine_list['sgst'],
							'hsn_no'=>$medicine_list['hsn_no'],
							'bar_code'=>$medicine_list['bar_code'],
							"total_amount"=>$medicine_list['total_amount'],
							"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
							'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
							'per_pic_rate'=>$medicine_list['per_pic_amount'],
					    	"created_date"=>date('Y-m-d H:i:s'),
					    	);
						 $this->db->insert('hms_vaccination_stock',$data_new_stock);

				}
            } 

            /*$payment_mode='';
            if($post['payment_mode']==1){
            	$payment_mode='cash';
            }
            if($post['payment_mode']==2){
            	$payment_mode='card';
            }
            if($post['payment_mode']==3){
            	$payment_mode='cheque';
            }
            if($post['payment_mode']==4){
            	$payment_mode='neft';
            }*/
            
            $data_expenses= array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>6,
					'vouchar_no'=>generate_unique_id(19),
					'parent_id'=>$sales_id,
                    'expenses_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                    'paid_to_id'=>'',
                    'paid_amount'=>$post['pay_amount'],
                    'payment_mode'=>$post['payment_mode'],
                   // 'branch_name'=>$bank_name,
                   // 'cheque_no'=>$cheque_no,
                   // 'cheque_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                    //'transaction_no'=>$transaction_no,
                    'created_date'=>date('Y-m-d H:i:s'),
                    'created_by'=>$user_data['id'],
					);
           //print_r($post);die;
				$this->db->insert('hms_expenses',$data_expenses);
				if(!empty($post['field_name']))
				{
						$post_field_value_name= $post['field_name'];
						$counter_name= count($post_field_value_name); 
						for($i=0;$i<$counter_name;$i++) 
						{
						$data_field_value= array(
												'field_value'=>$post['field_name'][$i],
												'field_id'=>$post['field_id'][$i],
												'type'=>6,
												'section_id'=>16,
												'p_mode_id'=>$post['payment_mode'],
												'branch_id'=>$user_data['parent_id'],
												'parent_id'=>$sales_id,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
												);
						$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

						}

				}
				
			           
		}
		return $sales_id;	 	
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
			$this->db->update('hms_vaccination_sale_return');
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
			$this->db->update('hms_vaccination_sale_return');
    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_sale');
        $result = $query->result(); 
        return $result; 
    } 

   public function medicine_list($ids="",$batch_no="",$type="")
    {
    	// echo 'rew';die;
    	$users_data = $this->session->userdata('auth_users'); 
    	$medicine_list = $this->session->userdata('vaccine_id');
		$this->db->select('hms_vaccination_entry.*,hms_vaccination_sale_to_vaccination.bar_code,hms_vaccination_sale_to_vaccination.hsn_no as hsn,,hms_vaccination_sale_to_vaccination.per_pic_price as per_price,hms_vaccination_unit.vaccination_unit, hms_vaccination_unit_2.vaccination_unit as vaccination_unit_2, hms_vaccination_sale_to_vaccination.batch_no,hms_vaccination_sale_to_vaccination.expiry_date,hms_vaccination_sale_to_vaccination.manuf_date,hms_vaccination_sale_to_vaccination.qty');
		$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.vaccine_id = hms_vaccination_entry.id'); 
		
		/*if(!empty($ids))
		{
			$this->db->where('hms_vaccination_entry.id  IN ('.$ids.')'); 
		}
		if(!empty($batch_no))
		{
			$this->db->where('hms_vaccination_sale_to_vaccination.batch_no  IN ('.$batch_no.')'); 
		}*/


		/*if(!empty($medicine_list))
        {
          $added_clouse = '';	
          $total_clouse = count($medicine_list);
          $i=1;
          foreach($medicine_list as $medicine_added)
          { 
            $added_clouse .= ' (hms_vaccination_entry.id = "'.$medicine_added['vid'].'" AND hms_vaccination_sale_to_vaccination.batch_no = "'.$medicine_added['batch_no'].'")';
            if($i!=$total_clouse)
            {
            	$added_clouse .= ' OR ';
            }
            $i++;
          }
          $this->db->where($added_clouse); 
        }*/

        if(!empty($ids))
		{
			$this->db->where('hms_vaccination_entry.id  IN ('.$ids.')'); 
		}
		if(!empty($batch_no))
		{
			if(!empty($type))
			{
              $this->db->where('hms_vaccination_sale_to_vaccination.batch_no IN ('.$batch_no.')'); 
			}
			else
			{
              $this->db->where('hms_vaccination_sale_to_vaccination.batch_no IN ("'.$batch_no.'")'); 
			} 
		}


		$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
		$this->db->join('hms_vaccination_unit as hms_vaccination_unit_2','hms_vaccination_unit_2.id = hms_vaccination_entry.unit_second_id','left');

		$this->db->where('hms_vaccination_entry.is_deleted','0');  
		$this->db->where('hms_vaccination_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_vaccination_sale_to_vaccination.batch_no');  
		$query = $this->db->get('hms_vaccination_entry');
		$result = $query->result();
		
	   //echo $this->db->last_query();die;
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
 

    public function medicine_list_search()
    {
    	$post = $this->input->post();  
        $users_data = $this->session->userdata('auth_users'); 
        $added_medicine_list = $this->session->userdata('vaccine_id');
    	$added_medicine_ids = [];
    	$added_medicine_batch = [];
		/*$this->db->select('hms_vaccination_entry.*,hms_medicine_sale.id as s_id,hms_vaccination_sale_to_vaccination.id as s_to_sid,hms_vaccination_entry.created_date as create, hms_vaccination_company.company_name,hms_vaccination_unit.vaccination_unit, hms_vaccination_unit_2.vaccination_unit as vaccination_unit_2,(SELECT (sum(credit)-sum(debit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id and type=3) as avl_qty,(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id) as stock_quantity');  
		$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
		$this->db->join('hms_vaccination_unit as hms_vaccination_unit_2','hms_vaccination_unit_2.id = hms_vaccination_entry.unit_second_id','left');
		$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.vaccine_id = hms_vaccination_entry.id');
		$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_vaccination_sale_to_vaccination.sales_id');
		$this->db->group_by('hms_vaccination_entry.id');*/

		$this->db->select('hms_vaccination_sale_to_vaccination.*,hms_vaccination_sale_to_vaccination.id as s_id,,hms_vaccination_entry.id,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.mrp,hms_vaccination_entry.packing,hms_vaccination_entry.vaccination_code,hms_vaccination_company.company_name,hms_vaccination_entry.min_alrt,(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id AND hms_vaccination_sale_to_vaccination.batch_no = batch_no) as stock_quantity,(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id AND hms_vaccination_sale_to_vaccination.batch_no = batch_no) as qty, hms_vaccination_sale_to_vaccination.batch_no');  
		//$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_vaccination_sale_to_vaccination.sales_id');
		$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id');


		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id AND hms_vaccination_sale_to_vaccination.batch_no = batch_no)>0');
		


		if(!empty($post['medicine_name']))
		{
			
			//echo 'fsf';die;
			$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$post['medicine_name'].'%"');
			
		}

		if(!empty($post['medicine_code']))
		{  

			$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$post['medicine_code'].'%"');
			
		}
	
		
		if(!empty($post['qty']))
		{  
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)<='.$post['qty'].' as sqty');
			
		}
		if(!empty($post['rate']))
		{  
			$this->db->where('hms_vaccination_entry.mrp = "'.$post['rate'].'"');
			
		}
		if(!empty($post['discount']))
		{  
			//echo 'fsf';die;
			$this->db->where('hms_vaccination_entry.discount = "'.$post['discount'].'"');
			
		}
		if(!empty($post['cgst']))
		{  
			$this->db->where('hms_vaccination_entry.cgst = "'.$post['cgst'].'"');
			
		}
		if(!empty($post['sgst']))
		{  
			$this->db->where('hms_vaccination_entry.sgst = "'.$post['sgst'].'"');
			
		}
		if(!empty($post['igst']))
		{  
			$this->db->where('hms_vaccination_entry.igst = "'.$post['igst'].'"');
			
		}
		if(!empty($post['hsn_no']))
		{  
			$this->db->where('hms_vaccination_entry.hsn_no = "'.$post['hsn_no'].'"');
			
		}
		if(!empty($post['batch_number']))
		{  
			$this->db->where('hms_vaccination_sale_to_vaccination.batch_no = "'.$post['batch_number'].'"');
			
		}
		if(!empty($post['bar_code']))
		{  
			$this->db->where('hms_vaccination_sale_to_vaccination.bar_code LIKE "'.$post['bar_code'].'%"');
			
		}
		if(!empty($post['stock']))
		{  
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)<='.$post['stock'].'');
			
		}
	
		if(!empty($post['packing']))
		{  
			$this->db->where('hms_vaccination_entry.packing LIKE "'.$post['packing'].'"');
			
		}
		
        $this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
		if(!empty($post['medicine_company']))
		{  
			$this->db->where('hms_vaccination_company.company_name LIKE"'.$post['medicine_company'].'%"');

			
		}

		if(isset($added_medicine_list) && !empty($added_medicine_list))
		{ 
			$arr_mids = [];
			if(!empty($added_medicine_list))
			{ 
			foreach($added_medicine_list as $medicine_data)
			{
			$arr_mids[] = "'".$medicine_data['vid'].".".$medicine_data['batch_no']."'";
			}
            $keys = implode(',', $arr_mids);
			} 
           
			$this->db->where('CONCAT(hms_vaccination_sale_to_vaccination.vaccine_id,".",hms_vaccination_sale_to_vaccination.batch_no) NOT IN ('.$keys.') ');
		} 
		  
		$this->db->where('hms_vaccination_entry.is_deleted','0');  
        $this->db->where('hms_vaccination_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_vaccination_sale_to_vaccination.vaccine_id');
		$this->db->group_by('hms_vaccination_sale_to_vaccination.batch_no');
        $this->db->from('hms_vaccination_sale_to_vaccination');
       
        $query = $this->db->get(); 
        //print'<pre>';print_r($query->result());die;
        //echo $this->db->last_query();die;
        return $query->result();
        // $data= $query->result();
       // print'<pre>';print_r($data);die;
	
    }
function get_all_detail_print($ids="",$branch_ids=""){
    	$result_sales=array();
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_vaccination_sale_return.*,hms_patient.*,hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,(CASE WHEN hms_vaccination_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,hms_simulation.simulation,hms_sms.simulation as rel_simulation,hms_gardian_relation.relation,hms_patient.relation_simulation_id,hms_patient.relation_type,hms_patient.relation_name"); 
		$this->db->join('hms_patient','hms_patient.id = hms_vaccination_sale_return.patient_id','left');
		$this->db->join('hms_users','hms_users.id = hms_vaccination_sale_return.created_by'); 
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');

		$this->db->where('hms_vaccination_sale_return.is_deleted','0'); 
		
		$this->db->where('hms_vaccination_sale_return.branch_id = "'.$branch_ids.'"');
		//$this->db->where('hms_vaccination_sale_return.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_vaccination_sale_return.refered_id','left');
		$this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale_return.referral_hospital','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
		$this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
		$this->db->where('hms_vaccination_sale_return.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['sales_list']= $this->db->get()->result();
		//echo $this->db->last_query(); exit;
		$this->db->select('hms_vaccination_sale_return_vaccination.*,hms_vaccination_sale_return_vaccination.discount as m_discount,hms_vaccination_entry.*,hms_vaccination_sale_return_vaccination.sgst as m_sgst,hms_vaccination_sale_return_vaccination.igst as m_igst,hms_vaccination_sale_return_vaccination.cgst as m_cgst,hms_vaccination_sale_return_vaccination.batch_no as m_batch_no,hms_vaccination_sale_return_vaccination.expiry_date as m_expiry_date,hms_vaccination_sale_return_vaccination.hsn_no as m_hsn_no');
		$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_return_vaccination.vaccine_id'); 
		$this->db->where('hms_vaccination_sale_return_vaccination.sales_return_id = "'.$ids.'"');
		$this->db->from('hms_vaccination_sale_return_vaccination');
		$result_sales['sales_list']['medicine_list']=$this->db->get()->result();
		//print '<pre>';print_r($result_sales);die;
		return $result_sales;
		
    }

    function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	//$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	return $query;

    }

    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
    {
    	 $users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
	
    	$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
    	$this->db->where('hms_payment_mode_field_value_acc_section.type',4);
    	$this->db->where('hms_payment_mode_field_value_acc_section.section_id',1);
    	$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
    	return $query;
    }

} 
?>