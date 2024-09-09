<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_return_medicine_model extends CI_Model 
{
	var $table = 'hms_medicine_sale_return';
	var $column = array('hms_medicine_sale_return.id','hms_patient.patient_name','hms_medicine_sale_return.sale_no','hms_medicine_sale_return.id','hms_medicine_sale_return.total_amount','hms_medicine_sale_return.net_amount','hms_medicine_sale_return.paid_amount','hms_medicine_sale_return.balance','hms_medicine_sale_return.created_date', 'hms_medicine_sale_return.modified_date');  
		var $order = array('id' => 'desc'); 
 
 public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('sale_search');
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_sale_return.*, hms_patient.patient_name, (select count(id) from hms_medicine_sale_return_medicine where sales_return_id = hms_medicine_sale_return.id) as total_medicine,(CASE WHEN hms_medicine_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale_return.refered_id=0 THEN concat('Other ',hms_medicine_sale_return.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name");
		//(CASE WHEN hms_medicine_sale_return.referred_by=1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name 
		$this->db->join('hms_patient','hms_patient.id = hms_medicine_sale_return.patient_id','left'); 
		
		$this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale_return.referral_hospital','left');
		$this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale_return.refered_id','left');
		$this->db->from($this->table);
		$this->db->where('hms_medicine_sale_return.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_medicine_sale_return.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_medicine_sale_return.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		if(isset($search) && !empty($search))
		{
			if(isset($search['start_date'])&& !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_medicine_sale_return.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date']));
				$this->db->where('hms_medicine_sale_return.sale_date <= "'.$end_date.'"');
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
				$this->db->where('hms_medicine_sale_return.referral_hospital' ,$search['referral_hospital']);
			}
			elseif($search['referred_by']=='0' && !empty($search['refered_id']))
			{
				$this->db->where('hms_medicine_sale_return.refered_id' ,$search['refered_id']);
			}
			

			if(isset($search['sale_no']) && !empty($search['sale_no']))
			{
				$this->db->where('hms_medicine_sale_return.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left');	

				}
				$this->db->where('hms_medicine_sale_return_medicine.discount = "'.$search['discount'].'"');
			}

			
			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) )
				{
				$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left');


			}
				$this->db->where('hms_medicine_sale_return_medicine.batch_no = "'.$search['batch_no'].'"');
			}
			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']))
				{	
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']))
				{	
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) )
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['igst']) && !empty($search['igst']))
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.igst', $search['igst']);
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']))
				{	
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.cgst', $search['cgst']);
			}

			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']) && empty($search['cgst']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.sgst', $search['sgst']);
			}

			


			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_medicine_sale_return.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
			{
				$this->db->where('hms_medicine_sale_return.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) && $search['balance_to']!="")
			{
				$this->db->where('hms_medicine_sale_return.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_medicine_sale_return.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_medicine_sale_return.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_medicine_sale_return.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_medicine_sale_return.bank_name',$search['bank_name']);
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
			$this->db->where('hms_medicine_sale_return.created_by IN ('.$emp_ids.')');
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
		$search = $this->session->userdata('sale_search');
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_sale_return.*, hms_patient.patient_name, (select count(id) from hms_medicine_sale_return_medicine where sales_return_id = hms_medicine_sale_return.id) as total_medicine,(CASE WHEN hms_medicine_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_medicine_sale_return.refered_id=0 THEN concat('Other ',hms_medicine_sale_return.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name"); 

		//(CASE WHEN hms_medicine_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
		$this->db->join('hms_patient','hms_patient.id = hms_medicine_sale_return.patient_id','left'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale_return.refered_id','left');
		$this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale_return.referral_hospital','left');
		
		$this->db->from($this->table);
		$this->db->where('hms_medicine_sale_return.is_deleted','0'); 
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_medicine_sale_return.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_medicine_sale_return.branch_id = "'.$user_data['parent_id'].'"');
		}
        if(isset($search) && !empty($search))
		{
			if(isset($search['start_date'])&& !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_medicine_sale_return.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_medicine_sale_return.sale_date <= "'.$end_date.'"');
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
				$this->db->where('hms_medicine_sale_return.referral_hospital' ,$search['referral_hospital']);
			}
			elseif($search['referred_by']=='0' && !empty($search['refered_id']))
			{
				$this->db->where('hms_medicine_sale_return.refered_id' ,$search['refered_id']);
			}
			
			

			if(isset($search['sale_no']) && !empty($search['sale_no']))
			{
				$this->db->where('hms_medicine_sale_return.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			/*if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left');	

				}
				$this->db->where('hms_medicine_sale_return.discount = "'.$search['discount'].'"');
			}
			
			

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']) && empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']))
				{
				$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 

				}
				$this->db->where('hms_medicine_sale_return_medicine.batch_no = "'.$search['batch_no'].'"');
			}
			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']) && empty($search['batch_no']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']) && empty($search['batch_no'])&& empty($search['packing']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])&& empty($search['igst'])&& empty($search['cgst'])&& empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion'])&& empty($search['mrp_to']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}



			if(isset($search['igst']) && !empty($search['igst']))
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount']) && empty($search['cgst'])&& empty($search['sgst'])&& empty($search['igst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion'])&& empty($search['mrp_to']) && empty($search['mrp_from']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.igst', $search['igst']);
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])  && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion'])&& empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.cgst', $search['cgst']);
			}

			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code'])&& empty($search['discount'])  && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion'])&& empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']) && empty($search['cgst']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.sgst', $search['sgst']);
			}*/

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left');	

				}
				$this->db->where('hms_medicine_sale_return_medicine.discount = "'.$search['discount'].'"');
			}

			
			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) )
				{
				$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left');


			}
				$this->db->where('hms_medicine_sale_return_medicine.batch_no = "'.$search['batch_no'].'"');
			}
			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']))
				{	
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']))
				{	
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) )
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['igst']) && !empty($search['igst']))
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.igst', $search['igst']);
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']))
				{	
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.cgst', $search['cgst']);
			}

			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['igst']) && empty($search['cgst']))
				{
					$this->db->join('hms_medicine_sale_return_medicine','hms_medicine_sale_return_medicine.sales_return_id = hms_medicine_sale_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_sale_return_medicine.sgst', $search['sgst']);
			}


			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_medicine_sale_return.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
			{
				$this->db->where('hms_medicine_sale_return.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) && $search['balance_to']!="")
			{
				$this->db->where('hms_medicine_sale_return.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_medicine_sale_return.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_medicine_sale_return.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_medicine_sale_return.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_medicine_sale_return.bank_name',$search['bank_name']);
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
			$this->db->where('hms_medicine_sale_return.created_by IN ('.$emp_ids.')');
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
		$this->db->select('hms_medicine_sale_return.*');
		$this->db->from('hms_medicine_sale_return'); 
		 $this->db->where('hms_medicine_sale_return.id',$id);
		$this->db->where('hms_medicine_sale_return.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function get_by_id_expense($id)
	{
		$this->db->select('hms_expenses.*');
		$this->db->from('hms_expenses'); 
		 $this->db->where('hms_expenses.parent_id',$id);
		$this->db->where('hms_expenses.is_deleted','0');
		$this->db->where('hms_expenses.type','3');
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
    $this->db->select('hms_medicine_batch_stock.medicine_id, hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code, hms_medicine_sale_return_medicine.*,hms_medicine_sale_return.total_amount as t_amount, hms_medicine_entry.packing, hms_medicine_sale_return_medicine.per_pic_price as mrp');
    $this->db->from('hms_medicine_sale_return_medicine'); 
    if(!empty($id))
    {
          $this->db->where('hms_medicine_sale_return_medicine.sales_return_id',$id);
          $this->db->join('hms_medicine_sale_return','hms_medicine_sale_return.id=hms_medicine_sale_return_medicine.sales_return_id','left');
          $this->db->join('hms_medicine_batch_stock','hms_medicine_batch_stock.medicine_id=hms_medicine_sale_return_medicine.medicine_id AND hms_medicine_batch_stock.batch_no = hms_medicine_sale_return_medicine.batch_no','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_medicine_sale_return_medicine.medicine_id','left');
    } 
    //$query = $this->db->get()->result(); sales_id
    $query = $this->db->get();
    //echo $this->db->last_query();die;
    $data = $query->result(); 
    //echo $this->db->last_query();die;
    //echo '<pre>'; print_r($data);die; 
    $result = []; 
    $total_price_medicine_amount='';

    if(!empty($data))
    {
      foreach($data as $medicine)  
      {   
          $result[$medicine->medicine_id.'.'.$medicine->batch_no] = array('medicine_name'=>$medicine->medicine_name, 'medicine_code'=>$medicine->medicine_code, 'packing'=>$medicine->packing, 'mrp'=>$medicine->per_pic_amount, 'mid'=>$medicine->medicine_id, 'qty'=>$medicine->qty, 'expiry_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'batch_no'=>$medicine->batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->actual_amount, 'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
          $tamt = 0;
      } 
    } 
    //die;
    //print '<pre>';print_r($result);die;
    return $result;
  }


	public function save()
	{ 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//echo "<pre>";print_r($post);die;
		$data_patient = array(
				//"patient_code"=>$post['patient_reg_code'],
				"patient_name"=>$post['name'],
				'branch_id'=>$user_data['parent_id'],
				'simulation_id'=>$post['simulation_id'],
				'relation_type'=>$post['relation_type'],
                'relation_name'=>$post['relation_name'],
                'relation_simulation_id'=>$post['relation_simulation_id'],
				'gender'=>$post['gender'],
				'mobile_no'=>$post['mobile']
			 );
				
		if(!empty($post['data_id']) && $post['data_id']>0)
		{  

            $created_by_id= $this->get_by_id($post['data_id']);
			/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>4,'section_id'=>1));
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
						'section_id'=>1,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
				/*$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));*/
				$this->db->set('created_by',$created_by_id['created_by']);
				$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($created_by_id['created_date'])));
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

          
            //$blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount']);//-$post['discount_amount'];
            
            $blance= $post['net_amount']-$post['pay_amount'];
            
            $refered_id=0;
            if(!empty($post['refered_id']))
            {
                $refered_id = $post['refered_id'];
            }
            $referral_hospital=0;
            if(!empty($post['referral_hospital']))
            {
                $referral_hospital = $post['referral_hospital'];
            }
			$data = array(
				"patient_id"=>$post['patient_id'],
				'branch_id'=>$user_data['parent_id'],
				'sale_no'=>$post['sales_no'], 
				'refered_id'=>$refered_id,
				'simulation_id'=>$post['simulation_id'],
				'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
				'total_amount'=>str_replace(',', '', $post['total_amount']),
				'net_amount'=>str_replace(',', '', $post['net_amount']),
				'discount'=>$post['discount_amount'],
				'igst'=>$post['igst_amount'],
				'cgst'=>$post['cgst_amount'],
				'sgst'=>$post['sgst_amount'],
				'payment_mode'=>$post['payment_mode'],
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				"discount_percent"=>$post['discount_percent'],
				//"vat_percent"=>$post['vat_percent'],
				'paid_amount'=>$post['pay_amount'],
				'referred_by'=>$post['referred_by'],
                'referral_hospital'=>$referral_hospital,
                'ref_by_other'=>$post['ref_by_other'],
				//'transaction_no'=>$transaction_no
			); 

            $created_by= $this->get_by_id_expense($post['data_id']);

			$this->db->where('id',$post['data_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->update('hms_medicine_sale_return',$data);
            //echo $this->db->last_query(); exit;
           /* $this->db->where('parent_id',$post['data_id']);
             $this->db->where('type',3);
            $this->db->delete('hms_expenses');
            
            */
            
            $this->db->select('hms_refund_payment.id');  
            $this->db->where('patient_id',$post['patient_id']);
            $this->db->where('section_id',8);
            $this->db->where('parent_id',$post['data_id']);
            $this->db->where('type',5);
            $query = $this->db->get('hms_refund_payment');
            $result = $query->result(); 
            
            //echo "<pre>"; print_r($result); die;
             $refund_id = $result[0]->id;  
             if(!empty($refund_id))
             {
                 $this->db->where('patient_id',$post['patient_id']);
                 $this->db->where('section_id',8);
                 $this->db->where('parent_id',$post['data_id']);
                 $this->db->where('type',5);
                 $this->db->delete('hms_refund_payment');
                 
                 $this->db->where('parent_id',$refund_id);
                 $this->db->where('type',3);
                 $this->db->delete('hms_expenses');
             }
             
             
            
            
            
            //insert in refund 
            
            $insert_array=array(
              'patient_id'=>$post['patient_id'],
              'section_id'=>8,
              'parent_id'=>$post['data_id'],
              'branch_id'=>$user_data['parent_id'],
              'doctor_id'=>"",
              'refund_amount'=>$post['pay_amount'],
              'refund_date'=> date("Y-m-d", strtotime($post['sales_date']) ),
              'created_date'=>date('Y-m-d H:i:s'),
              'status'=>"1",
              'type'=>5,
              'created_by'=>$user_data['id']);
            
            $this->db->insert('hms_refund_payment',$insert_array);
            $ref_id= $this->db->insert_id();
            
            $data_expenses= array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>3,
					'vouchar_no'=>generate_unique_id(19),
					'parent_id'=>$ref_id,//$post['data_id'],
                    'expenses_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                    'paid_to_id'=>$post['patient_id'],
                    'paid_amount'=>$post['pay_amount'],
                    'payment_mode'=>$post['payment_mode'],
                    //'cheque_no'=>$cheque_no,
                    'branch_name'=>$bank_name,
                    //'cheque_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                    //'transaction_no'=>$transaction_no,
                    'modified_date'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$user_data['id'],
                    'created_by'=>$user_data['id'],
                    'created_date'=>date('Y-m-d H:i:s',strtotime($post['sales_date']))
					);
				$this->db->insert('hms_expenses',$data_expenses);
                $sales_id= $post['data_id'];

		//	$sess_medicine_list= $this->session->userdata('medicine_id');
			//echo '<pre>';print_r($sess_medicine_list);die;
			
			
			$this->db->select('hms_medicine_sale_return_medicine.medicine_id, hms_medicine_sale_return_medicine.batch_no, hms_medicine_sale_return_medicine.qty'); 
            $this->db->where('hms_medicine_sale_return_medicine.sales_return_id',$post['data_id']); 
            $query = $this->db->get('hms_medicine_sale_return_medicine');
            $sale_to_medicine_data =  $query->result_array();
            $sale_to_medicine_data_arr = [];
            if(!empty($sale_to_medicine_data)) 
            {
                foreach($sale_to_medicine_data as $s_data)
                {
                    $sale_to_medicine_data_arr[$s_data['medicine_id'].'.'.$s_data['batch_no']] = array('mid_batch'=>$s_data['medicine_id'].'.'.$s_data['batch_no'], 'medicine_id'=>$s_data['medicine_id'], 'batch_no'=>$s_data['batch_no'], 'qty'=>$s_data['qty']);
                }
            }
			
			
			$this->db->where('sales_return_id',$post['data_id']);
            $this->db->delete('hms_medicine_sale_return_medicine');

             $this->db->where(array('parent_id'=>$post['data_id'],'type'=>4));
            $this->db->delete('hms_medicine_stock');
            /*if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{*/
				$medicine_sel_id = $post['medicine_sel_id'];
			if(!empty($medicine_sel_id))
            { 
                $i=0;
               foreach($medicine_sel_id as $medicine_list)
               {
					$data_purchase_topurchase = array(
					"sales_return_id"=>$post['data_id'],
					'medicine_id'=>$medicine_list,
					'qty'=>$post['quantity'][$i],
					'discount'=>$post['discount'][$i],
					'igst'=>$post['igst'][$i],
					'cgst'=>$post['cgst'][$i],
					'sgst'=>$post['sgst'][$i],
					'hsn_no'=>$post['hsn_no'][$i],
					'bar_code'=>$post['bar_code'][$i],
					'total_amount'=>$post['row_total_amount'][$i],
					'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
					'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
					'batch_no'=>$post['batch_no'][$i],
					'per_pic_price'=>$post['per_pic_amount'][$i], //row_total_amount
					'conversion'=>$post['conversion'][$i],
					'actual_amount'=>$post['sale_amount'][$i]
					);
					$this->db->insert('hms_medicine_sale_return_medicine',$data_purchase_topurchase);
					//echo $this->db->last_query(); exit;
					$original_price = get_medicine_price($medicine_list);
				    
			     if(!empty($original_price))
				 {
				 	$med_original_price = $original_price;
				 }
				 else
				 {
				 	$med_original_price = $medicine_list['sale_amount'];
				 }
				 
				  $data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>4,
					    	"parent_id"=>$post['data_id'],
					    	"m_id"=>$medicine_list,
					    	"credit"=>0,
					    	"debit"=>$post['quantity'][$i],
					    	"created_by"=>$user_data['id'],
							"batch_no"=>$post['batch_no'][$i],
							"conversion"=>$post['conversion'][$i],
							"mrp"=>$med_original_price,//$medicine_list['sale_amount'],
							"discount"=>$post['discount'][$i],
							'igst'=>$post['igst'][$i],
							'cgst'=>$post['cgst'][$i],
							'sgst'=>$post['sgst'][$i],
							'hsn_no'=>$post['hsn_no'][$i],
							'bar_code'=>$post['bar_code'][$i],
							"total_amount"=>$post['row_total_amount'][$i],
							"expiry_date"=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
							'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
							'per_pic_rate'=>$post['per_pic_amount'][$i],
					    	"created_date"=>date('Y-m-d',strtotime($post['sales_date']))
					    	);
						 $this->db->insert('hms_medicine_stock',$data_new_stock);
						 
						 
						$med_qty = $post['quantity'][$i];
                        $last_qty = 0;
                        $symbol = '+';
                        if(!empty($sale_to_medicine_data_arr))
                        {
                            $column_arr = array_column($sale_to_medicine_data_arr,'mid_batch');
                            if(in_array($medicine_list.'.'.$post['batch_no'][$i], $column_arr))
                            {
                                $last_qty = $sale_to_medicine_data_arr[$medicine_list.'.'.$post['batch_no'][$i]]['qty'];
                                if($last_qty>$med_qty)
                                {
                                    $med_qty = $last_qty-$med_qty;
                                    $symbol = '-';
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
                        
                        $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` ".$symbol." '".$med_qty."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no'][$i]."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$medicine_list."' ");

				$i++;
                   
               }
            } 

            

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
						'section_id'=>1,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
				/*$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));*/
				$this->db->set('created_by',$created_by_id['created_by']);
				$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($created_by_id['created_date'])));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
			}
			
			
			$sales_id= $post['data_id'];

			/*add sales banlk detail*/	
            
		}
		else
		{
			$sale_no = generate_unique_id(17);
		    $patient_reg_code=generate_unique_id(4);
			$vendor_data= $this->get_patient_by_id($post['patient_id']);
			if(count($vendor_data)>0){
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$post['patient_id']);
			$this->db->update('hms_patient',$data_patient);
			$patient_id= $post['patient_id'];
			}else{
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
              
            
                ////////// Send SMS /////////////////////
                if(in_array('640',$user_data['permission']['action']))
                {
                  if(!empty($post['mobile']))
                  {
                     
                    send_sms('patient_registration',18,$post['name'],$post['mobile'],array('{patient_name}'=>$post['name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id));
                  }
                }
		     }
			
            $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount']);//-$post['discount_amount'];
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
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				'paid_amount'=>$post['pay_amount'],
				'referred_by'=>$post['referred_by'],
                'referral_hospital'=>$post['referral_hospital'],
                'ref_by_other'=>$post['ref_by_other'],
				
			); 

			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_medicine_sale_return',$data);
     
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
					'section_id'=>1,
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
			
            $medicine_sel_id = $post['medicine_sel_id'];
		    if(!empty($medicine_sel_id))
            { 
                $i=0;
               foreach($medicine_sel_id as $medicine_list)
               {
					$data_purchase_topurchase = array(
					"sales_return_id"=>$sales_id,
					'medicine_id'=>$medicine_list,
					'qty'=>$post['quantity'][$i],
					'discount'=>$post['discount'][$i],
					'igst'=>$post['igst'][$i],
					'cgst'=>$post['cgst'][$i],
					'sgst'=>$post['sgst'][$i],
					'hsn_no'=>$post['hsn_no'][$i],
					'bar_code'=>$post['bar_code'][$i],
					'total_amount'=>$post['row_total_amount'][$i],
					'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
					'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
					'batch_no'=>$post['batch_no'][$i],
					'per_pic_price'=>$post['per_pic_amount'][$i],
					'conversion'=>$post['conversion'][$i],
					'actual_amount'=>$post['sale_amount'][$i]
					);
					$this->db->insert('hms_medicine_sale_return_medicine',$data_purchase_topurchase);
				
				$original_price = get_medicine_price($medicine_list); 
				if(!empty($original_price))
				 {
				 	$med_original_price = $original_price;
				 }
				 else
				 {
				 	$med_original_price = $post['sale_amount'][$i];
				 }
                  $data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>4,
					    	"parent_id"=>$sales_id,
					    	"m_id"=>$medicine_list,
					    	"credit"=>0,
					    	"debit"=>$post['quantity'][$i],
					    	"created_by"=>$user_data['id'],
							"batch_no"=>$post['batch_no'][$i],
							"conversion"=>$post['conversion'][$i],
							"mrp"=>$med_original_price,//$medicine_list['sale_amount'],
							"discount"=>$post['discount'][$i],
							'igst'=>$post['igst'][$i],
							'cgst'=>$post['cgst'][$i],
							'sgst'=>$post['sgst'][$i],
							'hsn_no'=>$post['hsn_no'][$i],
							'bar_code'=>$post['bar_code'][$i],
							"total_amount"=>$post['row_total_amount'][$i],
							"expiry_date"=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
							'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
							'per_pic_rate'=>$post['per_pic_amount'][$i],
					    	"created_date"=>date('Y-m-d',strtotime($post['sales_date']))
					    	);
						 $this->db->insert('hms_medicine_stock',$data_new_stock);
						 
						 
						 $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity`+'".$post['quantity'][$i]."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no'][$i]."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$medicine_list."' "); 

				$i++;
                   
               }
            } 

           
           //
           
           
          
           $insert_array=array(
              'patient_id'=>$patient_id,
              'section_id'=>8,
              'parent_id'=>$sales_id,
              'branch_id'=>$user_data['parent_id'],
              'doctor_id'=>"0",
              'refund_amount'=>$post['pay_amount'],
              'refund_date'=> date("Y-m-d", strtotime($post['sales_date']) ),
              'created_date'=>date('Y-m-d H:i:s'),
              'status'=>"1",
              'type'=>5,
              'created_by'=>$user_data['id']

                                  
            );
          $this->db->insert('hms_refund_payment',$insert_array);
         $ref_id= $this->db->insert_id();
         //echo $this->db->last_query(); exit;
            
            $data_expenses= array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>3,
					'vouchar_no'=>generate_unique_id(19),
					'parent_id'=>$ref_id,//$sales_id,
                    'expenses_date'=>date('Y-m-d',strtotime($post['sales_date'])),
                    'paid_to_id'=>$post['patient_id'],
                    'paid_amount'=>$post['pay_amount'],
                    'payment_mode'=>$post['payment_mode'],
                    'branch_name'=>$bank_name,
                    'modified_date'=>date('Y-m-d H:i:s'),
                    'created_date'=>date("Y-m-d", strtotime($post['sales_date']) ),
                    'modified_by'=>$user_data['id'],
                    'created_by'=>$user_data['id'],
					);
           
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
												'section_id'=>1,
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
		//$this->session->unset_userdata('medicine_id');
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
			$this->db->update('hms_medicine_sale_return');
			
			// Update batch stock
            $this->db->select('hms_medicine_sale_return_medicine.medicine_id, hms_medicine_sale_return_medicine.batch_no, hms_medicine_sale_return_medicine.qty'); 
            $this->db->where('hms_medicine_sale_return_medicine.sales_id',$id); 
            $query = $this->db->get('hms_medicine_sale_return_medicine');
            $sale_to_medicine_data =  $query->result_array();
            if(!empty($sale_to_medicine_data))
            {
                foreach($sale_to_medicine_data as $sale_to_medicine)
                {
                     $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity`-'".$sale_to_medicine['qty']."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$sale_to_medicine['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$sale_to_medicine['medicine_id']."' "); 
                }
            }
          // End update batch stock
			
			//update status on stock
		      $this->db->where('parent_id IN('.$branch_ids.')');
		      $this->db->where('branch_id',$user_data['parent_id']);
		            $this->db->where('type','4');
		            $query_d_pay = $this->db->get('hms_medicine_stock');
		            $row_d_pay = $query_d_pay->result();

		            //echo "<pre>"; print_r($row_d_pay); exit;
		            if(!empty($row_d_pay))
		            {
		                  foreach($row_d_pay as $row_d)
		                  {
		                    $stock_data = array(
		                        'is_deleted'=>1);
		                      
		                     $this->db->where('id',$row_d->id);
		                     $this->db->where('branch_id',$user_data['parent_id']);
		                     $this->db->where('parent_id IN('.$branch_ids.')');
		                     $this->db->where('type',4);
		                    $this->db->update('hms_medicine_stock',$stock_data);
		                    //echo $this->db->last_query(); 
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
			$this->db->update('hms_medicine_sale_return');
			
			//update status on stock
		      $this->db->where('parent_id IN('.$branch_ids.')');
		      $this->db->where('branch_id',$user_data['parent_id']);
		            $this->db->where('type','4');
		            $query_d_pay = $this->db->get('hms_medicine_stock');
		            $row_d_pay = $query_d_pay->result();

		            //echo "<pre>"; print_r($row_d_pay); exit;
		            if(!empty($row_d_pay))
		            {
		                  foreach($row_d_pay as $row_d)
		                  {
		                    $stock_data = array(
		                        'is_deleted'=>1);
		                      
		                     $this->db->where('id',$row_d->id);
		                     $this->db->where('branch_id',$user_data['parent_id']);
		                     $this->db->where('parent_id IN('.$branch_ids.')');
		                     $this->db->where('type',4);
		                    $this->db->update('hms_medicine_stock',$stock_data);
		                    //echo $this->db->last_query(); 
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
   
   public function medicine_list_check($ids="",$batch_no="",$type="")
   {
        $users_data = $this->session->userdata('auth_users'); 
        $medicine_list = $this->session->userdata('medicine_id');
        $this->db->select('hms_medicine_batch_stock.medicine_id, hms_medicine_batch_stock.medicine_id as mid,hms_medicine_batch_stock.batch_no');
        
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id');
        if(!empty($ids))
		{ 
		    if(is_array($ids))
		    {
		        $ids = implode(',',$ids);
		    }
			$this->db->where('hms_medicine_batch_stock.medicine_id  IN ('.$ids.')'); 
		}
		if(!empty($batch_no))
		{
		    if(is_array($batch_no))
		    {
		        $batch_no = implode(',',$batch_no);
		    }
			if(!empty($type))
			{
              $this->db->where('hms_medicine_batch_stock.batch_no IN ('.$batch_no.')'); 
			}
			else
			{
              $this->db->where('hms_medicine_batch_stock.batch_no IN ("'.$batch_no.'")'); 
			} 
		}
		$this->db->where('hms_medicine_entry.is_deleted','0');  
		$this->db->where('hms_medicine_batch_stock.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_medicine_batch_stock.batch_no');  
		$query = $this->db->get('hms_medicine_batch_stock');
		//echo $this->db->last_query();die;
		$result = $query->result();
		
		
	    return $result;
   }
   public function medicine_list($ids="",$batch_no="",$type="")
    {
    	 //cgst per_pic_amount discount
    	$users_data = $this->session->userdata('auth_users'); 
    	//$medicine_list = $this->session->userdata('medicine_id');
		$this->db->select('hms_medicine_entry.packing,hms_medicine_entry.id, hms_medicine_entry.id as mid,  hms_medicine_entry.medicine_code, hms_medicine_entry.medicine_name, hms_medicine_batch_stock.mrp as m_rp, hms_medicine_batch_stock.mrp,hms_medicine_sale_to_medicine.bar_code,hms_medicine_sale_to_medicine.hsn_no as hsn,hms_medicine_sale_to_medicine.per_pic_price as per_price,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, hms_medicine_sale_to_medicine.batch_no,hms_medicine_sale_to_medicine.expiry_date,hms_medicine_sale_to_medicine.manuf_date,hms_medicine_sale_to_medicine.qty, hms_medicine_entry.conversion, hms_medicine_entry.conversion as conv, hms_medicine_entry.discount, hms_medicine_batch_stock.sgst, hms_medicine_batch_stock.cgst,hms_medicine_batch_stock.igst');
		$this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.medicine_id = hms_medicine_entry.id'); 
		
		$this->db->join('hms_medicine_batch_stock','hms_medicine_batch_stock.medicine_id = hms_medicine_sale_to_medicine.medicine_id AND hms_medicine_batch_stock.batch_no = hms_medicine_sale_to_medicine.batch_no');
        if(!empty($ids))
		{ 
		    if(is_array($ids))
		    {
		        $ids = implode(',',$ids);
		    }
			$this->db->where('hms_medicine_entry.id  IN ('.$ids.')'); 
		}
		if(!empty($batch_no))
		{
		    if(is_array($batch_no))
		    {
		        $batch_no = implode(',',$batch_no);
		    }
			if(!empty($type))
			{
              $this->db->where('hms_medicine_sale_to_medicine.batch_no IN ('.$batch_no.')'); 
			}
			else
			{
              $this->db->where('hms_medicine_sale_to_medicine.batch_no IN ("'.$batch_no.'")'); 
			} 
		}


		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');

		$this->db->where('hms_medicine_entry.is_deleted','0');  
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_medicine_sale_to_medicine.batch_no');  
		$query = $this->db->get('hms_medicine_entry');
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
        $added_medicine_list = $this->session->userdata('medicine_id');
    	$added_medicine_ids = [];
    	$added_medicine_batch = [];
		//mrp discount
		$this->db->select('hms_medicine_sale_to_medicine.*,hms_medicine_sale_to_medicine.id as s_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_batch_stock.mrp,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,hms_medicine_entry.min_alrt,hms_medicine_batch_stock.quantity as stock_quantity,hms_medicine_batch_stock.quantity as qty, hms_medicine_sale_to_medicine.batch_no, hms_medicine_entry.discount');  
		
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id');
		$this->db->join('hms_medicine_batch_stock','hms_medicine_batch_stock.medicine_id = hms_medicine_sale_to_medicine.medicine_id AND hms_medicine_batch_stock.batch_no = hms_medicine_sale_to_medicine.batch_no');
        if(!empty($post['medicine_name']))
		{
			
			//echo 'fsf';die;
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$post['medicine_name'].'%"');
			
		}

		if(!empty($post['medicine_code']))
		{  

			$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$post['medicine_code'].'%"');
			
		}
	
		
		if(!empty($post['qty']))
		{  
			$this->db->where('hms_medicine_batch_stock.quantity<='.$post['qty'].' as sqty');
			
		}
		if(!empty($post['rate']))
		{  
			$this->db->where('hms_medicine_entry.mrp = "'.$post['rate'].'"');
			
		}
		if(!empty($post['discount']))
		{  
			//echo 'fsf';die;
			$this->db->where('hms_medicine_entry.discount = "'.$post['discount'].'"');
			
		}
		if(!empty($post['cgst']))
		{  
			$this->db->where('hms_medicine_entry.cgst = "'.$post['cgst'].'"');
			
		}
		if(!empty($post['sgst']))
		{  
			$this->db->where('hms_medicine_entry.sgst = "'.$post['sgst'].'"');
			
		}
		if(!empty($post['igst']))
		{  
			$this->db->where('hms_medicine_entry.igst = "'.$post['igst'].'"');
			
		}
		if(!empty($post['hsn_no']))
		{  
			$this->db->where('hms_medicine_entry.hsn_no = "'.$post['hsn_no'].'"');
			
		}
		if(!empty($post['batch_number']))
		{  
			$this->db->where('hms_medicine_sale_to_medicine.batch_no = "'.$post['batch_number'].'"');
			
		}
		if(!empty($post['bar_code']))
		{  
			$this->db->where('hms_medicine_sale_to_medicine.bar_code LIKE "'.$post['bar_code'].'%"');
			
		}
		if(!empty($post['stock']))
		{  
			$this->db->where('hms_medicine_batch_stock.quantity<='.$post['stock'].'');
			
		}
	
		if(!empty($post['packing']))
		{  
			$this->db->where('hms_medicine_entry.packing LIKE "'.$post['packing'].'"');
			
		}
		
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
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
           
			$this->db->where('CONCAT(hms_medicine_sale_to_medicine.medicine_id,".",hms_medicine_sale_to_medicine.batch_no) NOT IN ('.$keys.') ');
		} 
		  
		$this->db->where('hms_medicine_entry.is_deleted','0');  
        $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_medicine_sale_to_medicine.medicine_id');
		$this->db->group_by('hms_medicine_sale_to_medicine.batch_no');
        $this->db->from('hms_medicine_sale_to_medicine');
       
        $query = $this->db->get(); 
        //print'<pre>';print_r($query->result());die;
        //echo $this->db->last_query();die;
        return $query->result();
        // $data= $query->result();
       // print'<pre>';print_r($data);die;
	
    }
function get_all_detail_print($ids="",$branch_ids="")
{
    	$result_sales=array();
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_sale_return.*,hms_patient.*,hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,(CASE WHEN hms_medicine_sale_return.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name,hms_simulation.simulation,hms_religion.religion,hms_countries.country,hms_state.state,hms_cities.city,hms_relation.relation,hms_insurance_company.insurance_company,hms_sms.simulation as rel_simulation,hms_gardian_relation.relation,hms_patient.relation_simulation_id,hms_patient.relation_type,hms_patient.relation_name"); 
		$this->db->join('hms_patient','hms_patient.id = hms_medicine_sale_return.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
        $this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
		$this->db->join('hms_users','hms_users.id = hms_medicine_sale_return.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');

		$this->db->where('hms_medicine_sale_return.is_deleted','0'); 
		
		$this->db->where('hms_medicine_sale_return.branch_id = "'.$branch_ids.'"');
		//$this->db->where('hms_medicine_sale_return.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale_return.refered_id','left');
		$this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale_return.referral_hospital','left');

		$this->db->join('hms_religion','hms_religion.id = hms_patient.religion_id','left');
        $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left');
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left');
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); 
        $this->db->join('hms_relation','hms_relation.id = hms_patient.relation_id','left'); 
        $this->db->join('hms_insurance_company','hms_insurance_company.id = hms_patient.ins_company_id','left');


		$this->db->where('hms_medicine_sale_return.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['sales_list']= $this->db->get()->result();
		//echo $this->db->last_query(); exit;
		$this->db->select('hms_medicine_sale_return_medicine.*,hms_medicine_sale_return_medicine.discount as m_discount,hms_medicine_entry.*,hms_medicine_sale_return_medicine.sgst as m_sgst,hms_medicine_sale_return_medicine.igst as m_igst,hms_medicine_sale_return_medicine.cgst as m_cgst,hms_medicine_sale_return_medicine.batch_no as m_batch_no,hms_medicine_sale_return_medicine.expiry_date as m_expiry_date,hms_medicine_sale_return_medicine.hsn_no as m_hsn_no');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_return_medicine.medicine_id'); 
		$this->db->where('hms_medicine_sale_return_medicine.sales_return_id = "'.$ids.'"');
		$this->db->from('hms_medicine_sale_return_medicine');
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
    
    public function search_sales($vals="")
    {
      $response = '';
      if(!empty($vals))
      {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_medicine_sale.*,hms_patient.patient_name,hms_patient.patient_email as email,hms_patient.id as patient_id,hms_patient.mobile_no,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_patient.gender"); 
		$this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
		$this->db->where('hms_patient.is_deleted','0'); 
		$this->db->where('hms_medicine_sale.is_deleted','0'); 

		$this->db->where('hms_medicine_sale.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->from('hms_medicine_sale'); 
		$this->db->where('hms_medicine_sale.sale_no LIKE "'.$vals.'%"');
		$query = $this->db->get(); 
		$result = $query->result();
			
	  //echo $this->db->last_query(); exit;
      //echo "<pre>"; print_r($result); exit;
      if(!empty($result))
      { 
        $data = array();
        foreach($result as $vals)
        {
            $name = $vals->sale_no.'|'.$vals->id.'|'.$vals->referred_by.'|'.$vals->referral_hospital.'|'.$vals->ref_by_other_doctor.'|'.$vals->ref_by_other_hospital.'|'.$vals->refered_id.'|'.$vals->ref_by_other.'|'.$vals->total_amount.'|'.$vals->net_amount.'|'.$vals->paid_amount.'|'.$vals->balance.'|'.$vals->remarks.'|'.$vals->discount.'|'.$vals->sgst.'|'.$vals->cgst.'|'.$vals->igst.'|'.$vals->discount_percent.'|'.$vals->vat_percent.'|'.$vals->payment_mode.'|'.$vals->simulation_id.'|'.$vals->patient_name.'|'.$vals->patient_id.'|'.$vals->mobile_no.'|'.$vals->email.'|'.$vals->relation_type.'|'.$vals->relation_simulation_id.'|'.$vals->relation_name.'|'.$vals->gender;
            array_push($data, $name);
        }

        echo json_encode($data);
      }
      //return $response; 
  } 
}

} 
?>