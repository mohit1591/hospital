<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_vaccination_model extends CI_Model 
{
	var $table = 'hms_vaccination_sale';
	var $column = array('hms_vaccination_sale.id','hms_patient.patient_name','hms_vaccination_sale.sale_no','hms_vaccination_sale.id','hms_vaccination_sale.total_amount','hms_vaccination_sale.net_amount','hms_vaccination_sale.paid_amount','hms_vaccination_sale.balance','hms_vaccination_sale.created_date', 'hms_vaccination_sale.modified_date');  

	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('vaccine_sale_search');
		//print_r($search);die;
		$user_data = $this->session->userdata('auth_users');
		
		$this->db->select("hms_vaccination_sale.*, hms_patient.patient_name, (select count(id) from hms_vaccination_sale_to_vaccination where sales_id = hms_vaccination_sale.id) as total_medicine,(CASE WHEN hms_vaccination_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name"); 
		$this->db->join('hms_patient','hms_patient.id = hms_vaccination_sale.patient_id','left'); 
		$this->db->where('hms_vaccination_sale.is_deleted','0'); 
		//$this->db->where('hms_vaccination_sale.branch_id = "'.$user_data['parent_id'].'"'); 
		if($user_data['users_role']==4)
		{
			if(isset($search['branch_id']) && $search['branch_id']!=''){
			$this->db->where('hms_vaccination_sale.patient_id IN ('.$search['branch_id'].')');
			}else{
			$this->db->where('hms_vaccination_sale.patient_id = "'.$user_data['parent_id'].'"');
			}	
		}
		else
		{
			if(isset($search['branch_id']) && $search['branch_id']!=''){
			$this->db->where('hms_vaccination_sale.branch_id IN ('.$search['branch_id'].')');
			}else{
			$this->db->where('hms_vaccination_sale.branch_id = "'.$user_data['parent_id'].'"');
		}
		}
		
		//$this->db->where('hms_patient.is_deleted','0'); 
		//$this->db->where('hms_doctors.is_deleted','0'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_vaccination_sale.refered_id','left');
		$this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale.referral_hospital','left');
		$this->db->from($this->table);
        	if(isset($search) && !empty($search))
		{
			
            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_vaccination_sale.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&  !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_vaccination_sale.sale_date <= "'.$end_date.'"');
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

			if(isset($search['adhar_no']) &&  !empty($search['adhar_no']))
			{
				$this->db->where('hms_patient.adhar_no LIKE "'.$search['adhar_no'].'%"');
			}

			if($search['referred_by']=='1' && !empty($search['referral_hospital']))
			{
				$this->db->where('hms_vaccination_sale.referral_hospital' ,$search['referral_hospital']);
			}
			elseif($search['referred_by']=='0' && !empty($search['refered_id']))
			{
				$this->db->where('hms_vaccination_sale.refered_id' ,$search['refered_id']);
			}


			if(isset($search['sale_no']) &&  !empty($search['sale_no']))
			{
				$this->db->where('hms_vaccination_sale.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['medicine_name']) &&  !empty($search['medicine_name']))
			{
				$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
				$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left');
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_vaccination_entry.manuf_company','left'); 
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) &&  !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$search['medicine_code'].'%"');
			}
				
			if(isset($search['quantity']) && !empty($search['quantity']))
			{
				$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                $this->db->where('hms_vaccination_sale_to_vaccination.qty='.$search['quantity'] );
			}


			/*if(!empty($search['purchase_rate']))
			{
				$this->db->where('hms_medicine_purchase.purchase_rate',$search['purchase_rate']);
			}*/

			if(isset($search['discount']) &&  !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']))
				{
				$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				}
				$this->db->where('hms_vaccination_sale_to_vaccination.discount = "'.$search['discount'].'"');
			}

			if(isset($search['cgst']) &&  !empty($search['cgst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale.cgst', $search['cgst']);
			}
			if(isset($search['igst']) &&  !empty($search['igst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale.igst', $search['igst']);
			}
			if(isset($search['sgst']) &&  !empty($search['sgst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale.sgst', $search['sgst']);
			}




			if(isset($search['batch_no']) &&  !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']))
				{
				$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				}
				$this->db->where('hms_vaccination_sale_to_vaccination.batch_no = "'.$search['batch_no'].'"');
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
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])   && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_vaccination_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) &&  $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])   && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) &&  $search['mrp_to']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) &&  $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst'])&& empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['paid_amount_to']) &&  $search['paid_amount_to']!="")
			{
				$this->db->where('hms_vaccination_sale.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) &&  $search['paid_amount_from']!="")
			{
				$this->db->where('hms_vaccination_sale.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) &&  $search['balance_to']!="")
			{
				$this->db->where('hms_vaccination_sale.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_vaccination_sale.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) &&  $search['total_amount_to']!="")
			{
				$this->db->where('hms_vaccination_sale.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) &&  $search['total_amount_from']!="")
			{
				$this->db->where('hms_vaccination_sale.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_vaccination_sale.bank_name',$search['bank_name']);
			}


		}else{

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
   	$search = $this->session->userdata('vaccine_sale_search');
		//print_r($search);die;
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_vaccination_sale.*, hms_patient.patient_name, (select count(id) from hms_vaccination_sale_to_vaccination where sales_id = hms_vaccination_sale.id) as total_medicine,hms_doctors.doctor_name"); 
		$this->db->join('hms_patient','hms_patient.id = hms_vaccination_sale.patient_id','left'); 
		$this->db->where('hms_vaccination_sale.is_deleted','0'); 
		if($user_data['users_role']==4)
		{
			if(isset($search['branch_id']) && $search['branch_id']!=''){
			$this->db->where('hms_vaccination_sale.patient_id IN ('.$search['branch_id'].')');
			}else{
			$this->db->where('hms_vaccination_sale.patient_id = "'.$user_data['parent_id'].'"');
			}
		}
		else
		{
			if(isset($search['branch_id']) && $search['branch_id']!=''){
			$this->db->where('hms_vaccination_sale.branch_id IN ('.$search['branch_id'].')');
			}else{
			$this->db->where('hms_vaccination_sale.branch_id = "'.$user_data['parent_id'].'"');
			}
		}
		
		//$this->db->where('hms_vaccination_sale.branch_id = "'.$user_data['parent_id'].'"'); 
		
		//$this->db->where('hms_patient.is_deleted','0'); 
		//$this->db->where('hms_doctors.is_deleted','0'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_vaccination_sale.refered_id','left');
		$this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale.referral_hospital','left');
		$this->db->from($this->table);
		if(isset($search) && !empty($search))
		{
			
            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_vaccination_sale.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&  !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_vaccination_sale.sale_date <= "'.$end_date.'"');
			}


			if(isset($search['patient_name']) &&  !empty($search['patient_name']))
			{

				$this->db->where('hms_patient.patient_name',$search['patient_name']);
			}

			if(isset($search['adhar_no']) &&  !empty($search['adhar_no']))
			{

				$this->db->where('hms_patient.adhar_no',$search['adhar_no']);
			}

			if(isset($search['patient_code']) &&  !empty($search['patient_code']))

			{
				
			  $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
			}

			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			//if(isset($search['refered_by']) &&  !empty($search['refered_by']))
			//{
				
				//$this->db->where('hms_vaccination_sale.refered_id' ,$search['refered_by']);
			//}

			if($search['referred_by']=='1' && !empty($search['referral_hospital']))
			{
				$this->db->where('hms_vaccination_sale.referral_hospital' ,$search['referral_hospital']);
			}
			elseif($search['referred_by']=='0' && !empty($search['refered_id']))
			{
				$this->db->where('hms_vaccination_sale.refered_id' ,$search['refered_id']);
			}


			if(isset($search['sale_no']) &&  !empty($search['sale_no']))
			{
				$this->db->where('hms_vaccination_sale.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['medicine_name']) &&  !empty($search['medicine_name']))
			{
				$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
				$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left');
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_vaccination_entry.manuf_company','left'); 
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) &&  !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			/*if(!empty($search['purchase_rate']))
			{
				$this->db->where('hms_medicine_purchase.purchase_rate',$search['purchase_rate']);
			}*/

			if(isset($search['discount']) &&  !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']))
				{
				$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				}
				$this->db->where('hms_vaccination_sale_to_vaccination.discount = "'.$search['discount'].'"');
			}

			if(isset($search['cgst']) &&  !empty($search['cgst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale.cgst', $search['cgst']);
			}
			if(isset($search['igst']) &&  !empty($search['igst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale.igst', $search['igst']);
			}
			if(isset($search['sgst']) &&  !empty($search['sgst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				// }
				$this->db->where('hms_vaccination_sale.sgst', $search['sgst']);
			}

			if(isset($search['batch_no']) &&  !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])   && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']))
				{
				$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
				}
				$this->db->where('hms_vaccination_sale_to_vaccination.batch_no = "'.$search['batch_no'].'"');
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
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_vaccination_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) &&  $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])   && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) &&  $search['mrp_to']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) &&  $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])   && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']))
				{
					$this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale_to_vaccination.sales_id = hms_vaccination_sale.id','left'); 
					$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id','left'); 
                }
				$this->db->where('hms_vaccination_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['paid_amount_to']) &&  $search['paid_amount_to']!="")
			{
				$this->db->where('hms_vaccination_sale.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) &&  $search['paid_amount_from']!="")
			{
				$this->db->where('hms_vaccination_sale.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) &&  $search['balance_to']!="")
			{
				$this->db->where('hms_vaccination_sale.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_vaccination_sale.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) &&  $search['total_amount_to']!="")
			{
				$this->db->where('hms_vaccination_sale.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) &&  $search['total_amount_from']!="")
			{
				$this->db->where('hms_vaccination_sale.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_vaccination_sale.bank_name',$search['bank_name']);
			}

		}else{

		}
		$query = $this->db->get(); 

		$data= $query->result();
		//echo $this->db->last_query();die;
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
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('hms_vaccination_sale.*');
		$this->db->from('hms_vaccination_sale'); 
		 $this->db->where('hms_vaccination_sale.id',$id);
		$this->db->where('hms_vaccination_sale.is_deleted','0');
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
		$this->db->select('hms_vaccination_sale_to_vaccination.*,hms_vaccination_sale.total_amount as t_amount');
		$this->db->from('hms_vaccination_sale_to_vaccination'); 
		if(!empty($id))
		{
          $this->db->where('hms_vaccination_sale_to_vaccination.sales_id',$id);
          $this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_vaccination_sale_to_vaccination.sales_id','left');
		} 
		$query = $this->db->get()->result(); 
		
		$result = [];
		$total_price_medicine_amount='';

		if(!empty($query))
		{
		  foreach($query as $medicine)	
		  {   
		  	  $result[$medicine->vaccine_id.'.'.$medicine->batch_no] = array('vid'=>$medicine->vaccine_id, 'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'batch_no'=>$medicine->batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->actual_amount, 'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
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
			//hms_payment_mode_field_value_acc_section
			$invoice_id = generate_unique_id(12); 
			$purchase_id = generate_unique_id(39); 
			$user_data = $this->session->userdata('auth_users');
			$post = $this->input->post();
			//print '<pre>' ;print_r($post);die;
			$data_patient = array(
								"patient_code"=>$post['patient_reg_code'],
								"patient_name"=>$post['name'],
								'simulation_id'=>$post['simulation_id'],
								'branch_id'=>$user_data['parent_id'],
								'gender'=>$post['gender'],
								'relation_type'=>$post['relation_type'],
								'relation_name'=>$post['relation_name'],
								'relation_simulation_id'=>$post['relation_simulation_id'],
								'adhar_no'=>$post['adhar_no'],
								'mobile_no'=>$post['mobile']
							);
				
		if(!empty($post['data_id']) && $post['data_id']>0)
		{ 

		/*add sales banlk detail*/
		$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>3,'section_id'=>16));
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


				$blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
				$ipd_id='';
				if(isset($post['ipd_id']))
				{
					$ipd_id = $post['ipd_id'];
				}
				$data = array(
				"patient_id"=>$post['patient_id'],
				'branch_id'=>$user_data['parent_id'],
				"ipd_id"=>$ipd_id,
				'sale_no'=>$post['sales_no'],
				'refered_id'=>$post['refered_id'],
				'simulation_id'=>$post['simulation_id'],
				'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
				'total_amount'=>str_replace(',', '', $post['total_amount']),
				'net_amount'=>str_replace(',', '', $post['net_amount']),
				'discount'=>$post['discount_amount'],
				"sgst"=>$post['sgst_amount'],
				"igst"=>$post['igst_amount'],
				"cgst"=>$post['cgst_amount'],
				"discount_percent"=>$post['discount_percent'],
				'payment_mode'=>$post['payment_mode'],
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				'paid_amount'=>$post['pay_amount'],
				'referred_by'=>$post['referred_by'],
                'referral_hospital'=>$post['referral_hospital'],
				); 

			$this->db->where('id',$post['data_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->update('hms_vaccination_sale',$data);
			//echo $this->db->last_query();die;
			$sess_medicine_list= $this->session->userdata('vaccine_id');
			//echo '<pre>';print_r($sess_medicine_list);die;
			$this->db->where('sales_id',$post['data_id']);
			$this->db->delete('hms_vaccination_sale_to_vaccination');
			 

			if(!empty($sess_medicine_list))
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
				foreach($sess_medicine_list as $medicine_list)
				{
					//print '<pre>';print_r($medicine_list['batch_no']);
					//$m_data= explode('.',$medicine_list['vid']);
					$data_purchase_topurchase = array(
					"sales_id"=>$post['data_id'],
					'vaccine_id'=>$medicine_list['vid'],
					'qty'=>$medicine_list['qty'],
					'discount'=>$medicine_list['discount'],
					'sgst'=>$medicine_list['sgst'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'hsn_no'=>$medicine_list['hsn_no'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'batch_no'=>$medicine_list['batch_no'],
					'bar_code'=>$medicine_list['bar_code'],
					'per_pic_price'=>$medicine_list['per_pic_amount'],
					'conversion'=>$medicine_list['conversion'],
					'actual_amount'=>$medicine_list['sale_amount']

					);
					$this->db->insert('hms_vaccination_sale_to_vaccination',$data_purchase_topurchase);
					//echo $this->db->last_query();
					$data_new_stock=array("branch_id"=>$user_data['parent_id'],
					"type"=>3,
					"parent_id"=>$post['data_id'],
					"v_id"=>$medicine_list['vid'],
					"credit"=>$medicine_list['qty'],
					"debit"=>0,
					"batch_no"=>$medicine_list['batch_no'],
					"conversion"=>$medicine_list['conversion'],
					"mrp"=>$medicine_list['sale_amount'],
					"discount"=>$medicine_list['discount'],
					'sgst'=>$medicine_list['sgst'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'hsn_no'=>$medicine_list['hsn_no'],
					'bar_code'=>$medicine_list['bar_code'],
					"total_amount"=>$medicine_list['total_amount'],
					"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'per_pic_rate'=>$medicine_list['per_pic_amount'],
					"created_by"=>$user_data['id'],
					"created_date"=>date('Y-m-d H:i:s'),
					);
					$this->db->insert('hms_vaccination_stock',$data_new_stock);

					
					if(isset($post['ipd_id']) && !empty($post['ipd_id']))
					{
							$medicine_name = get_medicine_name($medicine_list['vid']);
							$patient_medicine_charge = array(
							"branch_id"=>$user_data['parent_id'],
							'ipd_id'=>$post['ipd_id'],
							'parent_id'=>$purchase_id,
							'patient_id'=>$post['patient_id'],
							'type'=>10,
							'quantity'=>$medicine_list['qty'],
							'start_date'=>date('Y-m-d',strtotime($post['sales_date'])),
							'end_date'=>date('Y-m-d',strtotime($post['sales_date'])),
							'particular'=>$medicine_name,
							'price'=>$medicine_list['total_amount'],
							'panel_price'=>$medicine_list['total_amount'],
							'net_price'=>$medicine_list['total_amount'],
							'status'=>1,
							'created_date'=>date('Y-m-d H:i:s')
						);
						$this->db->insert('hms_ipd_patient_to_charge',$patient_medicine_charge);
					}

				}
			//die;
			}

		if(!empty($blance) || $blance==0)
		{
			$blance =1;
		}
			if(isset($post['ipd_id']) && !empty($post['ipd_id']))
			{
				
			}
			else
			{	
						$this->db->where(array('parent_id'=>$post['data_id'],'type'=>3));
						$this->db->delete('hms_vaccination_stock');
						$this->db->where('parent_id',$post['data_id']);
						$this->db->where('section_id','7');
						//$this->db->where('patient_id',$post['patient_id']);
						$this->db->delete('hms_payment');
						$payment_data = array(
						'parent_id'=>$post['data_id'],
						'branch_id'=>$user_data['parent_id'],
						'section_id'=>'7',
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
						'created_date'=>date('Y-m-d H:i:s'),
						'created_by'=>$user_data['id']
					);
					$this->db->insert('hms_payment',$payment_data);
			}
			
			
			/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>14,'section_id'=>4));
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
						'type'=>14,
						'section_id'=>4,
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
		$purchase_id= $post['data_id'];

	}
	else
	{
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
				$this->db->insert('hms_patient',$data_patient); 
				$patient_id= $this->db->insert_id();
		    }
			
            $blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
            $ipd_id ='';
            if(isset($post['ipd_id']))
            {
            	$ipd_id = $post['ipd_id'];	
            }

            
			$data = array(
				"patient_id"=>$patient_id,
				"ipd_id"=>$ipd_id,
				'branch_id'=>$user_data['parent_id'],
				'sale_no'=>$post['sales_no'],
				'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
				'total_amount'=>str_replace(',', '', $post['total_amount']),
				'net_amount'=>str_replace(',', '', $post['net_amount']),
				'discount'=>$post['discount_amount'],
				'refered_id'=>$post['refered_id'],
				'simulation_id'=>$post['simulation_id'],
				"sgst"=>$post['sgst_amount'],
				"igst"=>$post['igst_amount'],
				"cgst"=>$post['cgst_amount'],
				"discount_percent"=>$post['discount_percent'],
				'payment_mode'=>$post['payment_mode'],
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				'paid_amount'=>$post['pay_amount'],
				'referred_by'=>$post['referred_by'],
                'referral_hospital'=>$post['referral_hospital'],
            ); 
			
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_vaccination_sale',$data);
			$purchase_id= $this->db->insert_id();
			/*add sales banlk detail*/
			/* genereate receipt number */
			if(in_array('218',$user_data['permission']['section']))
			{
	           if($post['pay_amount']>0)
	           {
	           	 $hospital_receipt_no= check_hospital_receipt_no();
	           	 $data_receipt_data= array('branch_id'=>$user_data['parent_id'],
												'section_id'=>5,
												'parent_id'=>$purchase_id,
												'reciept_prefix'=>$hospital_receipt_no['prefix'],
												'reciept_suffix'=>$hospital_receipt_no['suffix'],
												'created_by'=>$user_data['id'],
												'created_date'=>date('Y-m-d H:i:s')
												);
		        $this->db->insert('hms_branch_hospital_no',$data_receipt_data);
	           }
	        }
	       
	       //echo $this->db->last_query();die;
              /* generate receipt number */
			
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
							'section_id'=>16,
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

			$this->db->where('section_id','7');
            $this->db->where('parent_id',$purchase_id);
            $this->db->delete('hms_payment'); 

			$sess_medicine_list= $this->session->userdata('vaccine_id');
			//print '<pre>';print_r($sess_medicine_list);die; 
            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{
					//print '<pre>';print_r($medicine_list);
					$m_data= explode('.',$medicine_list['vid']);
					$data_purchase_topurchase = array(
					"sales_id"=>$purchase_id,
					'vaccine_id'=>$medicine_list['vid'],
					'qty'=>$medicine_list['qty'],
					'discount'=>$medicine_list['discount'],
					'sgst'=>$medicine_list['sgst'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
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
					$this->db->insert('hms_vaccination_sale_to_vaccination',$data_purchase_topurchase);
					//echo $this->db->last_query();
					$data_new_stock=array("branch_id"=>$user_data['parent_id'],
				    	"type"=>3,
				    	"parent_id"=>$purchase_id,
				    	"v_id"=>$medicine_list['vid'],
				    	"credit"=>$medicine_list['qty'],
				    	"debit"=>0,
						"batch_no"=>$medicine_list['batch_no'],
						"conversion"=>$medicine_list['conversion'],
						"mrp"=>$medicine_list['sale_amount'],
						"discount"=>$medicine_list['discount'],
						'sgst'=>$medicine_list['sgst'],
						'hsn_no'=>$medicine_list['hsn_no'],
						'igst'=>$medicine_list['igst'],
						'cgst'=>$medicine_list['cgst'],
						'bar_code'=>$medicine_list['bar_code'],
						"total_amount"=>$medicine_list['total_amount'],
						"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
						'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
						'per_pic_rate'=>$medicine_list['per_pic_amount'],
				    	"created_by"=>$user_data['id'],
				    	"created_date"=>date('Y-m-d H:i:s'),
				    	);
					 $this->db->insert('hms_vaccination_stock',$data_new_stock);
					
					if(isset($post['ipd_id']) && !empty($post['ipd_id']))
					{
							$medicine_name = get_medicine_name($medicine_list['vid']);
							$patient_medicine_charge = array(
							"branch_id"=>$user_data['parent_id'],
							'ipd_id'=>$post['ipd_id'],
							'parent_id'=>$purchase_id,
							'patient_id'=>$post['patient_id'],
							'type'=>10,
							'quantity'=>$medicine_list['qty'],
							'start_date'=>date('Y-m-d',strtotime($post['sales_date'])),
							'end_date'=>date('Y-m-d',strtotime($post['sales_date'])),
							'particular'=>$medicine_name,
							'price'=>$medicine_list['total_amount'],
							'panel_price'=>$medicine_list['total_amount'],
							'net_price'=>$medicine_list['total_amount'],
							'status'=>1,
							'created_date'=>date('Y-m-d H:i:s')
						);
						$this->db->insert('hms_ipd_patient_to_charge',$patient_medicine_charge);
					}
				}
				//die;
            } 
             
			if(!empty($blance) || $blance==0)
			{
				$blance =1;
			}
			if(isset($post['ipd_id']) && !empty($post['ipd_id']))
			{
			
				
					
			}
			else
			{
				$payment_data = array(
								'parent_id'=>$purchase_id,
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'7',
								'doctor_id'=>$post['refered_id'],
								'hospital_id'=>$post['referral_hospital'],
								'patient_id'=>$patient_id,
								'total_amount'=>str_replace(',', '', $post['total_amount']),
								'discount_amount'=>$post['discount_amount'],
								'net_amount'=>str_replace(',', '', $post['net_amount']),
								'credit'=>str_replace(',', '', $post['net_amount']),
								'debit'=>$post['pay_amount'],
								'pay_mode'=>$post['payment_mode'],
								'balance'=>$blance,
								'paid_amount'=>$post['pay_amount'],
								'created_date'=>date('Y-m-d H:i:s'),
								'created_by'=>$user_data['id']
            	             );
				$this->db->insert('hms_payment',$payment_data);
			}

             
            
			/*add sales banlk detail*/
			if(!empty($post['field_name']))
			{
					$post_field_value_name= $post['field_name'];	
					for($i=0;$i<$counter_name;$i++) 
					{
					$data_field_value= array(
								'field_value'=>$post['field_name'][$i],
								'field_id'=>$post['field_id'][$i],
								'type'=>14,
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
			$this->db->update('hms_vaccination_sale');
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
			$this->db->update('hms_vaccination_sale');
    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_vaccination_sale');
        $result = $query->result(); 
        return $result; 
    } 

   public function medicine_list($ids="",$batch_no="")
    { 
		$users_data = $this->session->userdata('auth_users'); 
		$medicine_list = $this->session->userdata('vaccine_id');
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
			$arr_mids[] = "'".$medicine_data['vid'].".".$medicine_data['batch_no']."'";
			}
			$keys = implode(',', $arr_mids);
			}
			//$keys = $ids.'.'.$batch_no;
		}
		$this->db->select('hms_vaccination_entry.*, hms_vaccination_stock.bar_code,hms_vaccination_stock.mrp as m_rp,hms_vaccination_stock.hsn_no as hsn,hms_vaccination_stock.conversion as conv,hms_vaccination_unit.vaccination_unit, hms_vaccination_unit_2.vaccination_unit as vaccination_unit_2, hms_vaccination_stock.batch_no,hms_vaccination_stock.expiry_date,hms_vaccination_stock.manuf_date,hms_vaccination_stock.debit as qty');
		$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_stock.v_id'); 
		if(!empty($keys))
		{ 
	     	$this->db->where('CONCAT(hms_vaccination_stock.v_id,".",hms_vaccination_stock.batch_no) IN ('.$keys.') ');
		} 
		/*if(!empty($medicine_list))
        {
          $added_clouse = '';	
          $total_clouse = count($medicine_list);
          $i=1;
          foreach($medicine_list as $medicine_added)
          { 
            $added_clouse .= ' (hms_vaccination_stock.id = "'.$medicine_added['vid'].'" AND hms_vaccination_stock.batch_no = "'.$medicine_added['batch_no'].'")';
            if($i!=$total_clouse)
            {
            	$added_clouse .= ' OR ';
            }
            $i++;
          }
          $this->db->where($added_clouse); 
        }*/
 
		/*if(!empty($batch_no) || !empty($ids))
		{
			$close_batch = '';
			$close_ids = ''; 
			if(!empty($batch_no))
			{
				$close_batch = 'stock.batch_no IN ('.$batch_no.') ';
			}
			if(!empty($ids))
			{
				 $close_ids = ' stock.v_id IN ('.$ids.')';
			}
            $clouse = $close_batch.$close_ids;
			if(!empty($batch_no) && !empty($ids))
			{
				$clouse = $close_batch.' AND '.$close_ids;
			}
			$this->db->where('hms_vaccination_stock.id  IN (select stock.id from hms_vaccination_stock as stock where '.$clouse.')'); 
		}*/

       

		$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
		$this->db->join('hms_vaccination_unit as hms_vaccination_unit_2','hms_vaccination_unit_2.id = hms_vaccination_entry.unit_second_id','left');

		$this->db->where('hms_vaccination_entry.is_deleted','0');  
		$this->db->where('hms_vaccination_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_vaccination_entry.id, hms_vaccination_stock.batch_no');  
		$query = $this->db->get('hms_vaccination_stock');
		$result = $query->result(); 
		
		
		//echo $this->db->last_query();die;
		return $result; 
    }

	 public function get_batch_med_qty($vid="",$batch_no="")
		{
			$user_data = $this->session->userdata('auth_users');
			$this->db->select('(sum(debit)-sum(credit)) as total_qty');
			$this->db->where('branch_id',$user_data['parent_id']); 
			$this->db->where('v_id',$vid);
			$this->db->where('batch_no',$batch_no);
			$query = $this->db->get('hms_vaccination_stock');
			return $query->row_array();
		}

    public function medicine_list_search()
    {

    	$users_data = $this->session->userdata('auth_users'); 
    	$added_medicine_list = $this->session->userdata('vaccine_id');
    	//print_r($added_medicine_list);
    	$added_medicine_ids = [];
    	$added_medicine_batch = [];
    	//print_r($added_medicine_batch);die;
    	/*if(!empty($added_medicine_list) && isset($added_medicine_batch))
    	{
			$added_medicine_ids = array_column($added_medicine_list,'vid');
			$added_medicine_batch = array_column($added_medicine_list,'batch_no');
    	}*/ 

    	$post = $this->input->post();  
		$this->db->select('hms_vaccination_stock.*,hms_vaccination_stock.id as p_id,hms_vaccination_entry.id,hms_vaccination_entry.vaccination_name,hms_vaccination_stock.hsn_no as hsn,hms_vaccination_entry.mrp,hms_vaccination_entry.packing,hms_vaccination_entry.vaccination_code,hms_medicine_company.company_name,hms_vaccination_stock.batch_no,(sum(hms_vaccination_stock.debit)-sum(hms_vaccination_stock.credit)) as qty, hms_vaccination_entry.min_alrt');  
		    //$this->db->join('hms_vaccination_sale','hms_vaccination_sale.id = hms_vaccination_sale_to_vaccination.sales_id');
			$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_stock.v_id');
             

		//	$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id AND hms_vaccination_stock.batch_no = batch_no)>0');

			
			
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
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)<='.$post['qty'].' as qty');
			
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
		if(!empty($post['sgst']))
		{  
			$this->db->where('hms_vaccination_entry.sgst = "'.$post['sgst'].'"');
			
		}
		if(!empty($post['cgst']))
		{  
			$this->db->where('hms_vaccination_entry.cgst = "'.$post['cgst'].'"');
			
		}
		if(!empty($post['hsn_no']))
		{  
			$this->db->where('hms_vaccination_entry.hsn_no = "'.$post['hsn_no'].'"');
			
		}
		if(!empty($post['igst']))
		{  
			$this->db->where('hms_vaccination_entry.igst = "'.$post['igst'].'"');
			
		}
		if(!empty($post['batch_number']))
		{  
			$this->db->where('hms_vaccination_stock.batch_no = "'.$post['batch_number'].'"');
			
		}
		if(!empty($post['bar_code']))
		{  
			$this->db->where('hms_vaccination_stock.bar_code LIKE "'.$post['bar_code'].'%"');
			
		}
		if(!empty($post['stock']))
		{  
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)<='.$post['stock'].'');
			
		}
	
		if(!empty($post['packing']))
		{  
			$this->db->where('hms_vaccination_entry.packing LIKE "'.$post['packing'].'"');
			
		}
		
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_vaccination_entry.manuf_company','left');
		if(!empty($post['medicine_company']))
		{  
			$this->db->where('hms_medicine_company.company_name LIKE"'.$post['medicine_company'].'%"');

			
		}
		/*if(!empty($added_medicine_batch))
		{
			$added_batchs = implode(',', $added_medicine_batch);
			$added_ids = implode(',', $added_medicine_ids); 
			//$this->db->where('(hms_vaccination_stock.batch_no NOT IN ('.$added_batchs.') AND hms_vaccination_entry.id NOT IN ('.$added_ids.'))'); 
            $this->db->where('hms_vaccination_stock.id NOT IN (select stock.id from hms_vaccination_stock as stock where stock.batch_no IN ('.$added_batchs.') AND stock.v_id IN ('.$added_ids.'))'); 
			
		}*/
		/*if(isset($added_medicine_list) && !empty($added_medicine_list))
		{ 
			$keys = implode(',', array_keys($added_medicine_list));
	     	$this->db->where('CONCAT(hms_vaccination_stock.v_id,".",hms_vaccination_stock.batch_no) NOT IN ('.$keys.') ');
		} */

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
           
			$this->db->where('CONCAT(hms_vaccination_stock.v_id,".",hms_vaccination_stock.batch_no) NOT IN ('.$keys.') ');
		} 

		$this->db->where('hms_vaccination_stock.kit_status',0);
		$this->db->where('hms_vaccination_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		//$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		$this->db->group_by('hms_vaccination_stock.v_id');
		$this->db->group_by('hms_vaccination_stock.batch_no');
        $this->db->from('hms_vaccination_stock'); 
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return  $query->result();
        //echo $this->db->last_query();die;
      
    }

    function get_all_detail_print($ids="",$branch_ids=""){
    	$result_sales=array();
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_vaccination_sale.*,hms_patient.*,hms_users.*,(CASE WHEN hms_vaccination_sale.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,hms_simulation.simulation,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_sms.simulation as rel_simulation,hms_gardian_relation.relation"); 
		$this->db->join('hms_patient','hms_patient.id = hms_vaccination_sale.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
		$this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
		$this->db->join('hms_doctors','hms_doctors.id = hms_vaccination_sale.refered_id','left');
		$this->db->join('hms_hospital','hms_hospital.id = hms_vaccination_sale.referral_hospital','left'); 
		$this->db->join('hms_users','hms_users.id = hms_vaccination_sale.created_by');
		$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_vaccination_sale.id AND hms_branch_hospital_no.section_id=5','left'); 
		$this->db->where('hms_vaccination_sale.is_deleted','0'); 
		
		//$this->db->where('hms_vaccination_sale.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->where('hms_vaccination_sale.branch_id = "'.$branch_ids.'"'); 
		$this->db->where('hms_vaccination_sale.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['sales_list']= $this->db->get()->result();
		//print_r($result_sales['sales_list']);die;
		
		$this->db->select('hms_vaccination_sale_to_vaccination.*,hms_vaccination_sale_to_vaccination.discount as m_discount,hms_vaccination_entry.*,hms_vaccination_entry.discount as m_disc,hms_vaccination_sale_to_vaccination.sgst as m_sgst,hms_vaccination_sale_to_vaccination.igst as m_igst,hms_vaccination_sale_to_vaccination.cgst as m_cgst,hms_vaccination_sale_to_vaccination.batch_no as m_batch_no,hms_vaccination_sale_to_vaccination.expiry_date as m_expiry_date,hms_vaccination_sale_to_vaccination.hsn_no as m_hsn_no');
		$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_sale_to_vaccination.vaccine_id'); 
		$this->db->where('hms_vaccination_sale_to_vaccination.sales_id = "'.$ids.'"');
		$this->db->from('hms_vaccination_sale_to_vaccination');
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

    public function check_stock_avability($id="",$batch_no=""){
	  	//if(!empty($batch_no)){
	  	$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_vaccination_stock.*,hms_vaccination_entry.*,(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id and batch_no="'.$batch_no.'" and v_id="'.$id.'") as avl_qty');   
	   $this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_stock.v_id');
		$this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->where('hms_vaccination_stock.batch_no',$batch_no);
		
		$this->db->where('hms_vaccination_stock.v_id',$id);
		 $this->db->group_by('hms_vaccination_stock.batch_no');
		$query = $this->db->get('hms_vaccination_stock');
        $result = $query->row(); 
        //echo $this->db->last_query();die;
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

    function check_bar_code($bar_code="",$vaccine_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');
		if(!empty($bar_code))
		{
		 $this->db->where('bar_code',$bar_code);	
		}
		$this->db->where('branch_id',$users_data['parent_id']);
		$result= $this->db->get('hms_vaccination_stock')->result();
		if(count($result)>0)
		{
			foreach($result as $res)
			{
				$res_new_medicine_array[]= $res->v_id;
			}
				$new_array= array_unique($res_new_medicine_array);
				if(in_array($vaccine_id,$new_array))
				{
					return 1;
				}
				else
				{
					return 2;
				}	
		}
	}

} 
?>