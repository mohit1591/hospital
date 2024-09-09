<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_return_model extends CI_Model 
{
	var $table = 'hms_medicine_return';
    var $column = array('hms_medicine_return.id','hms_medicine_vendors.name','hms_medicine_return.purchase_id','hms_medicine_return.invoice_id','hms_medicine_return.id','hms_medicine_return.total_amount','hms_medicine_return.net_amount','hms_medicine_return.paid_amount','hms_medicine_return.balance','hms_medicine_return.created_date', 'hms_medicine_return.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{

		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('purchase_search');
		//echo "<pre>"; print_r($search); exit;
		$this->db->select("hms_medicine_return.*, hms_medicine_vendors.name as vendor_name, (select count(id) from hms_medicine_return_to_return where purchase_return_id = hms_medicine_return.id) as total_medicine"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_return.vendor_id','left');

		$this->db->where('hms_medicine_return.is_deleted','0'); 
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 

		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_medicine_return.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_medicine_return.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		$this->db->from($this->table); 
		if(isset($search) && !empty($search))
		{
			
            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_medicine_return.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_medicine_return.purchase_date <= "'.$end_date.'"');
			}


			if(isset($search['vendor_name']) && !empty($search['vendor_name']))
			{

				$this->db->where('hms_medicine_vendors.name LIKE "'.$search['vendor_name'].'%"');
			}

			if(isset($search['vendor_code']) &&  !empty($search['vendor_code']))

			{
				
			  $this->db->where('hms_medicine_vendors.vendor_id LIKE "'.$search['vendor_code'].'%"');
			}

			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
				$this->db->where('hms_medicine_vendors.mobile LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['invoice_id']) && !empty($search['invoice_id']))
			{
				$this->db->where('hms_medicine_return.invoice_id LIKE "'.$search['invoice_id'].'%"');
			}

			if(isset($search['purchase_no']) &&  !empty($search['purchase_no']))
			{
				$this->db->where('hms_medicine_return.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['medicine_name']) &&  !empty($search['medicine_name']))
			{
				$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name']))
				{

				 $this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
				 $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left'); 
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_medicine_return.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']))
				{
				$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
				 $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left');

				}
				$this->db->where('hms_medicine_return_to_return.discount = "'.$search['discount'].'"');
			}

			

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']))
				{

				$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
				}
				$this->db->where('hms_medicine_return_to_return.batch_no = "'.$search['batch_no'].'"');
			}

			if(isset($search['unit1']) && $search['unit1']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) )
				{
				$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left');

					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id');
					$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
					$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left'); 
					
					

				}
				
				$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['unit1']))
				{
						
				$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left');

				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id');
				$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left'); 
				$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
				}

				$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
			}

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) )
				{	
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) &&  $search['conversion']!="")
			{
				

				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) )
				{		
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) )
				{	
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) && empty($search['mrp_to']) )
				{	
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}


			if(isset($search['sgst']) &&  !empty($search['sgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{	
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }

				$this->db->where('hms_medicine_return_to_return.sgst', $search['sgst']);
			}

			if(isset($search['cgst']) &&  !empty($search['cgst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['sgst']))
				{	
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }

				$this->db->where('hms_medicine_return_to_return.cgst', $search['cgst']);
			}
			if(isset($search['igst']) &&  !empty($search['igst']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['sgst']) && empty($search['cgst']))
				{	
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }

				$this->db->where('hms_medicine_return_to_return.igst', $search['igst']);
			}



			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_medicine_return.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) &&$search['paid_amount_from']!="")
			{
				$this->db->where('hms_medicine_return.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) &&$search['balance_to']!="")
			{
				$this->db->where('hms_medicine_return.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_medicine_return.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_medicine_return.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_medicine_return.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_medicine_return.bank_name',$search['bank_name']);
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
			$this->db->where('hms_medicine_return.created_by IN ('.$emp_ids.')');
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
					$this->db->group_end(); //close bracket hms_medicine_stock
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

	function search_report_data(){
		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('purchase_search');
		$this->db->select("hms_medicine_return.*, hms_medicine_vendors.name as vendor_name, (select count(id) from hms_medicine_return_to_return where purchase_return_id = hms_medicine_return.id) as total_medicine"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_return.vendor_id','left'); 
		$this->db->where('hms_medicine_return.is_deleted','0'); 
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_medicine_return.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_medicine_return.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_medicine_return.branch_id = "'.$user_data['parent_id'].'"'); 
		
		$this->db->from($this->table);
		if(isset($search) && !empty($search))
		{
			
           if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_medicine_return.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_medicine_return.purchase_date <= "'.$end_date.'"');
			}


			if(isset($search['vendor_name']) && !empty($search['vendor_name']))
			{

				$this->db->where('hms_medicine_vendors.name',$search['vendor_name']);
			}

			if(isset($search['vendor_code']) &&  !empty($search['vendor_code']))

			{
				
			  $this->db->where('hms_medicine_vendors.vendor_id LIKE "'.$search['vendor_code'].'%"');
			}

			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
				$this->db->where('hms_medicine_vendors.mobile LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['invoice_id']) && !empty($search['invoice_id']))
			{
				$this->db->where('hms_medicine_return.invoice_id LIKE "'.$search['invoice_id'].'%"');
			}

			if(isset($search['purchase_no']) &&  !empty($search['purchase_no']))
			{
				$this->db->where('hms_medicine_return.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['medicine_name']) &&  !empty($search['medicine_name']))
			{
				$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name']))
				{

				 $this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
				 $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
				}
				
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left'); 
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']))
				{
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_medicine_return.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']))
				{
				$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
				}
				$this->db->where('hms_medicine_return_to_return.discount = "'.$search['discount'].'"');
			}

			if(isset($search['sgst']) &&  !empty($search['sgst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
			 //     }
				$this->db->where('hms_medicine_return.sgst', $search['sgst']);
			}

			if(isset($search['cgst']) &&  !empty($search['cgst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
			 //     }
				$this->db->where('hms_medicine_return.cgst', $search['cgst']);
			}
			if(isset($search['igst']) &&  !empty($search['igst']))
			{
				// if(empty($search['medicine_name']) && empty($search['medicine_company']) && empty($search['medicine_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
			 //     }
				$this->db->where('hms_medicine_return.igst', $search['igst']);
			}

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']))
				{
				$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
				}
				$this->db->where('hms_medicine_return_to_return.batch_no = "'.$search['batch_no'].'"');
			}

			if(isset($search['unit1']) && $search['unit1']!="")
			{
				$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
			}

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']))
				{
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) &&  $search['conversion']!="")
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']))
				{
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']) && empty($search['conversion']))
				{
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['igst'])  && empty($search['sgst']) && empty($search['batch_no']) && empty($search['conversion']) && empty($search['mrp_to']))
				{
					$this->db->join('hms_medicine_return_to_return','hms_medicine_return_to_return.purchase_return_id = hms_medicine_return.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_medicine_return.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) &&$search['paid_amount_from']!="")
			{
				$this->db->where('hms_medicine_return.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) &&$search['balance_to']!="")
			{
				$this->db->where('hms_medicine_return.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_medicine_return.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_medicine_return.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_medicine_return.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_medicine_return.bank_name',$search['bank_name']);
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
			$this->db->where('hms_medicine_return.created_by IN ('.$emp_ids.')');
		}
		$query = $this->db->get(); 

		$data= $query->result();
		//echo $this->db->last_query();die;
		return $data;
	}

	public function get_by_id($id)
	{
		$this->db->select('hms_medicine_return.*');
		$this->db->from('hms_medicine_return'); 
		$this->db->where('hms_medicine_return.id',$id);
		$this->db->where('hms_medicine_return.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	public function get_vendor_by_id($id)
	{

		$this->db->select('hms_medicine_vendors.*');
		$this->db->from('hms_medicine_vendors'); 
		$this->db->where('hms_medicine_vendors.id',$id);
		//$this->db->where('hms_medicine_vendors.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}

	public function get_medicine_by_purchase_id($id="")
	{
		$this->db->select('hms_medicine_return_to_return.*, hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, hms_medicine_entry.conversion, hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code, hms_medicine_entry.min_alrt, hms_medicine_entry.packing, hms_medicine_entry.rack_no, hms_medicine_entry.salt');
		$this->db->from('hms_medicine_return_to_return'); 
			$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id','left');
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		if(!empty($id))
		{
          $this->db->where('hms_medicine_return_to_return.purchase_return_id',$id);
		} 
		$query = $this->db->get()->result(); 
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $medicine)	
		  {
                $ratewithunit1= $medicine->purchase_rate*$medicine->unit1;
			    $perpic_rate= 0;
				$ratewithunit2=$perpic_rate*$medicine->unit1;
				$tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
				$cgstToPay = ($tot_qty_with_rate / 100) * $medicine->cgst;
				$igstToPay = ($tot_qty_with_rate / 100) * $medicine->igst;
				$sgstToPay = ($tot_qty_with_rate / 100) * $medicine->sgst;
            	$totalPrice = $tot_qty_with_rate + $cgstToPay+$igstToPay+$sgstToPay;
				$total_discount = ($medicine->discount/100)*$totalPrice;
				$total_amount= $totalPrice-$total_discount;

                if($medicine->expiry_date!='1970-01-01')
                {
                   $exp_date=date('d-m-Y',strtotime($medicine->expiry_date)); 
                }
                else
                {
                    $exp_date='';
                }
                if($medicine->manuf_date!='1970-01-01')
                {
                   $manuf_date=date('d-m-Y',strtotime($medicine->manuf_date)); 
                }
                else
                {
                    $manuf_date='';
                }

               $result[$medicine->medicine_id.'.'.$medicine->batch_no] = array('mid'=>$medicine->medicine_id,'medicine_name'=>$medicine->medicine_name,'medicine_code'=>$medicine->medicine_code,'conversion'=>$medicine->conversion,'unit1'=>$medicine->unit1,'freeunit1'=>$medicine->freeunit1,'hsn_no'=>$medicine->hsn_no,'unit2'=>$medicine->unit2,'freeunit2'=>$medicine->freeunit2,'perpic_rate'=>$perpic_rate,'batch_no'=>$medicine->batch_no,'mrp'=>$medicine->mrp,'manuf_date'=>$manuf_date, 'qty'=>$medicine->qty,'freeqty'=>$medicine->freeqty, 'expiry_date'=>$exp_date,'discount'=>$medicine->discount,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'purchase_amount'=>$medicine->purchase_rate, 'total_amount'=>$medicine->total_amount, 'bar_code'=>$medicine->bar_code); 
		  } 
		} 
		
		return $result;
	}


	public function save()
	{ 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//echo "<pre>"; print_r($post); exit;
		$created_by_id=$this->get_by_id($post['data_id']);
		// print_r($post);die;
		$data_vendor = array(
				"vendor_id"=>$post['vendor_code'],
				"name"=>$post['name'],
				'vendor_gst'=>$post['vendor_gst'],
				'branch_id'=>$user_data['parent_id'],
				"mobile"=>$post['mobile'],
				"address"=>$post['address'],
				"address2"=>$post['address2'],
			    "address3"=>$post['address3'],
				"email"=>$post['email'],
				"status"=>1
				
			);
				
		if(!empty($post['data_id']) && $post['data_id']>0)
		{  
           $purchase_detail= $this->get_by_id($post['data_id']);
		   $vendor_id_new= $this->get_vendor_by_id($purchase_detail['vendor_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('vendor_type',1);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['vendor_id']);
			$this->db->update('hms_medicine_vendors',$data_vendor);
            
            
            $this->db->select('hms_medicine_return_to_return.medicine_id, hms_medicine_return_to_return.batch_no, hms_medicine_return_to_return.qty'); 
            $this->db->where('hms_medicine_return_to_return.purchase_return_id',$post['data_id']); 
            $query = $this->db->get('hms_medicine_return_to_return');
            $sale_to_medicine_data =  $query->result_array();
            $sale_to_medicine_data_arr = [];
            if(!empty($sale_to_medicine_data)) 
            {
                foreach($sale_to_medicine_data as $s_data)
                {
                    $sale_to_medicine_data_arr[$s_data['medicine_id'].'.'.$s_data['batch_no']] = array('mid_batch'=>$s_data['medicine_id'].'.'.$s_data['batch_no'], 'medicine_id'=>$s_data['medicine_id'], 'batch_no'=>$s_data['batch_no'], 'qty'=>$s_data['qty']);
                }
            }
          
           // $blance= $post['net_amount']-$post['pay_amount'];
             $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount'])-$post['discount_amount'];
             
			$data = array(
				"vendor_id"=>$post['vendor_id'],
				'branch_id'=>$user_data['parent_id'],
				'invoice_id'=>$post['invoice_id'],
				'purchase_id'=>$post['purchase_no'],
				'return_no'=>$post['return_no'],
				'purchase_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
				'purchase_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['purchase_time'])),
				'total_amount'=>$post['total_amount'],
				'net_amount'=>$post['net_amount'],
				'discount'=>$post['discount_amount'],
				"sgst"=>$post['sgst_amount'],
				"igst"=>$post['igst_amount'],
				"cgst"=>$post['cgst_amount'],
				'discount_percent'=>$post['discount_percent'],
				'mode_payment'=>$post['payment_mode'],
				'payment_date'=>$payment_date,
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				'paid_amount'=>$post['pay_amount']
				
			); 
			$this->db->where('id',$post['data_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->update('hms_medicine_return',$data);
            
			$sess_medicine_list= $post['medicine_sel_id'];
			//echo '<pre>';print_r($sess_medicine_list);die;
			$this->db->where('purchase_return_id',$post['data_id']);
            $this->db->delete('hms_medicine_return_to_return');

            $this->db->where(array('parent_id'=>$post['data_id'],'type'=>2));
            $this->db->delete('hms_medicine_stock');
            if(!empty($sess_medicine_list))
            { 
                $i=0;
               foreach($sess_medicine_list as $medicine_list)
				{
				    if(!empty($post['medicine_name_data'][$i]))
					{
						$medicine_name = $post['medicine_name_data'][$i];
					}
					
					if(!empty($post['conversion'][$i]))
					{
						$conversion = $post['conversion'][$i];
					}
					if(!empty($post['unit1'][$i]))
					{
						$unit1 = $post['unit1'][$i];
					}
					if(!empty($post['unit2'][$i]))
					{
						$unit2 = $post['unit2'][$i];
					}
					if(!empty($post['freeunit1'][$i]))
					{
						$freeunit1 = $post['freeunit1'][$i];
					}
					if(!empty($post['freeunit2'][$i]))
					{
						$freeunit2 = $post['freeunit2'][$i];
					}
					
                    
                    $qty = ($conversion*$unit1)+$unit2;
					$free_qty = ($conversion*$freeunit1)+$freeunit2;
					$qtys = $qty+$free_qty; 
					
					if(!empty($post['sgst'][$i]))
					{
					   $sgst =  $post['sgst'][$i];
					}
					else
					{
					    $sgst ='0.00';
					}
						if(!empty($post['igst'][$i]))
					{
					   $igst =  $post['igst'][$i];
					}
					else
					{
					    $igst ='0.00';
					}
					
						if(!empty($post['cgst'][$i]))
					{
					   $cgst =  $post['cgst'][$i];
					}
					else
					{
					    $cgst ='0.00';
					}
					//echo $post['manuf_date'][$i]; die;
					if($post['manuf_date'][$i]!='00-00-0000')
					{
					    $manuf_date = date('Y-m-d',strtotime($post['manuf_date'][$i]));
					}
					else
					{
					    $manuf_date ='1970-01-01';
					}
					
			$data_purchase_topurchase = array(
					"purchase_return_id"=>$post['data_id'],
					'medicine_id'=>$medicine_list,
					'qty'=>$qtys,
					'discount'=>$post['discount'][$i],
					'sgst'=>$sgst,
					'igst'=>$igst,
					'cgst'=>$cgst,
					'total_amount'=>$post['row_total_amount'][$i],
					'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
					'purchase_rate'=>$post['purchase_rate'][$i],
					'mrp'=>$post['mrp'][$i],
					'batch_no'=>$post['batch_no'][$i],
					'bar_code'=>$post['bar_code'][$i],
					'hsn_no'=>$post['hsn_no'][$i],
					'unit1'=>$post['unit1'][$i],
					'unit2'=>$post['unit2'][$i],
					'freeunit1'=>$post['freeunit1'][$i],
					'freeunit2'=>$post['freeunit2'][$i],
					'manuf_date'=>$manuf_date,
					'conversion'=>$post['conversion'][$i],
					'per_pic_rate'=>$post['perpic_rate'][$i],
					'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
					);
				$this->db->insert('hms_medicine_return_to_return',$data_purchase_topurchase);
                //echo $this->db->last_query(); exit;
				$data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>2,
					    	"parent_id"=>$post['data_id'],
					    	"m_id"=>$medicine_list,
					    	"credit"=>$qtys,
					    	"debit"=>0,
					    	"batch_no"=>$post['batch_no'][$i],
    				    	"conversion"=>$post['conversion'][$i],
    				    	"purchase_rate"=>$post['purchase_rate'][$i],
    				    	"mrp"=>$post['mrp'][$i],
    				    	"discount"=>$post['discount'][$i],
    				    	'hsn_no'=>$post['hsn_no'][$i],
    				    	'bar_code'=>$post['bar_code'][$i],
    						'sgst'=>$sgst,
    						'igst'=>$igst,
    						'cgst'=>$cgst,
    				    	"total_amount"=>$post['row_total_amount'][$i],
    				    	"expiry_date"=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
    				    	'manuf_date'=>$manuf_date,
    				    	'per_pic_rate'=>($post['mrp'][$i]/$post['conversion'][$i]),
							"created_by"=>$created_by_id['created_by'],//$user_data['id'],
							"created_date"=>date('Y-m-d',strtotime($post['purchase_date'])),
					    	);
					$this->db->insert('hms_medicine_stock',$data_new_stock);
						 
						 
						$med_qty = $qtys;
                        $last_qty = 0;
                        $symbol = '-';
                        if(!empty($sale_to_medicine_data_arr))
                        {
                            $column_arr = array_column($sale_to_medicine_data_arr,'mid_batch');
                            if(in_array($medicine_list['mid'].'.'.$medicine_list['batch_no'], $column_arr))
                            {
                                $last_qty = $sale_to_medicine_data_arr[$medicine_list['mid'].'.'.$medicine_list['batch_no']]['qty'];
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
                        
                        $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` ".$symbol." '".$med_qty."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no'][$i]."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$medicine_list."' ");
				
				    $i++;
				}
				//die;
            } 
            
            $this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','3');
            $this->db->where('type','3');
            $this->db->where('patient_id=""');
            $this->db->where('vendor_id',$post['vendor_id']);
            $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->delete('hms_payment');
            ////////
            $credit = str_replace(',', '', ($post['net_amount']-$post['discount_amount']));
            
             $payment_data = array(
								'parent_id'=>$post['data_id'],
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'3',
								'type'=>'3',
								'doctor_id'=>'',
								'vendor_id'=>$post['vendor_id'],
								'patient_id'=>'',
								'total_amount'=>str_replace(',', '', $post['total_amount']),
								'discount_amount'=>$post['discount_amount'],
								'net_amount'=>str_replace(',', '', $post['net_amount']),
								'credit'=>$credit,
								'debit'=>$post['pay_amount'],
								'pay_mode'=>$post['payment_mode'],
								'balance'=>$blance,
								'paid_amount'=>$post['pay_amount'],
								//'transection_no'=>$transaction_no,
                                'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
                                'created_by'=>$created_by_id['created_by'],//$user_data['id']
            	             );
            $this->db->insert('hms_payment',$payment_data);
            $purchase_id=$post['data_id'];
            /*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>2,'section_id'=>4));
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
						'type'=>2,
						'section_id'=>4,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					$this->db->set('created_by',$created_by_id['created_by']);
					$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($created_by_id['created_date'])));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

					}
			}

			/*add sales banlk detail*/ 
        $purchase_id=$post['data_id'];
            
		}
		else
		{
			$vendor_data= $this->get_vendor_by_id($post['vendor_id']);
			if(count($vendor_data)>0)
			{
    			$this->db->set('modified_by',$user_data['id']);
    			$this->db->set('modified_date',date('Y-m-d H:i:s'));
    			$this->db->where('id',$post['vendor_id']);
    			$this->db->update('hms_medicine_vendors',$data_vendor);
    			$vendor_id= $post['vendor_id'];
    			
			}
			else
			{
    			$this->db->set('created_by',$user_data['id']);
    			$this->db->set('created_date',date('Y-m-d H:i:s'));
    			$this->db->set('vendor_type',1);
    			$this->db->insert('hms_medicine_vendors',$data_vendor); 
    			$vendor_id= $this->db->insert_id();
    		}
			
            $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount'])-$post['discount_amount'];
			$data = array("vendor_id"=>$vendor_id,
            				'branch_id'=>$user_data['parent_id'],
            				'invoice_id'=>$post['invoice_id'],
            				'purchase_id'=>$post['purchase_no'],
            				'return_no'=>$post['return_no'],
            				'purchase_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
            				'purchase_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['purchase_time'])),
            				'total_amount'=>$post['total_amount'],
            				'net_amount'=>$post['net_amount'],
            				'discount'=>$post['discount_amount'],
            				"sgst"=>$post['sgst_amount'],
            				"igst"=>$post['igst_amount'],
            				"cgst"=>$post['cgst_amount'],
            				'discount_percent'=>$post['discount_percent'],
            				'mode_payment'=>$post['payment_mode'],
            				'balance'=>$blance,
            				"remarks"=>$post['remarks'],
            				'paid_amount'=>$post['pay_amount']
            			); 
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_medicine_return',$data);
            $purchase_id= $this->db->insert_id();

			$sess_medicine_list=  $post['medicine_sel_id'];
			
            if(!empty($sess_medicine_list))
            { 
                $i=0;
               foreach($sess_medicine_list as $medicine_list)
				{
					if(!empty($post['medicine_name_data'][$i]))
					{
						$medicine_name = $post['medicine_name_data'][$i];
					}
					
					if(!empty($post['conversion'][$i]))
					{
						$conversion = $post['conversion'][$i];
					}
					if(!empty($post['unit1'][$i]))
					{
						$unit1 = $post['unit1'][$i];
					}
					if(!empty($post['unit2'][$i]))
					{
						$unit2 = $post['unit2'][$i];
					}
					if(!empty($post['freeunit1'][$i]))
					{
						$freeunit1 = $post['freeunit1'][$i];
					}
					if(!empty($post['freeunit2'][$i]))
					{
						$freeunit2 = $post['freeunit2'][$i];
					}
					
                    
                    $qty = ($conversion*$unit1)+$unit2;
					$free_qty = ($conversion*$freeunit1)+$freeunit2;
					$qtys = $qty+$free_qty; 
					
					if(!empty($post['sgst'][$i]))
					{
					   $sgst =  $post['sgst'][$i];
					}
					else
					{
					    $sgst ='0.00';
					}
						if(!empty($post['igst'][$i]))
					{
					   $igst =  $post['igst'][$i];
					}
					else
					{
					    $igst ='0.00';
					}
					
						if(!empty($post['cgst'][$i]))
					{
					   $cgst =  $post['cgst'][$i];
					}
					else
					{
					    $cgst ='0.00';
					}
					//echo $post['manuf_date'][$i]; die;
					if($post['manuf_date'][$i]!='00-00-0000')
					{
					    $manuf_date = date('Y-m-d',strtotime($post['manuf_date'][$i]));
					}
					else
					{
					    $manuf_date ='1970-01-01';
					}
					
			$data_purchase_topurchase = array(
        					"purchase_return_id"=>$purchase_id,
        					'medicine_id'=>$medicine_list,
        					'qty'=>$qtys,
        					'discount'=>$post['discount'][$i],
        					'sgst'=>$sgst,
        					'igst'=>$igst,
        					'cgst'=>$cgst,
        					'total_amount'=>$post['row_total_amount'][$i],
        					'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
        					'purchase_rate'=>$post['purchase_rate'][$i],
        					'mrp'=>$post['mrp'][$i],
        					'batch_no'=>$post['batch_no'][$i],
        					'bar_code'=>$post['bar_code'][$i],
        					'hsn_no'=>$post['hsn_no'][$i],
        					'unit1'=>$post['unit1'][$i],
        					'unit2'=>$post['unit2'][$i],
        					'freeunit1'=>$post['freeunit1'][$i],
        					'freeunit2'=>$post['freeunit2'][$i],
        					'manuf_date'=>$manuf_date,
        					'conversion'=>$post['conversion'][$i],
        					'per_pic_rate'=>$post['perpic_rate'][$i],
        					'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
					);
					
		    $this->db->insert('hms_medicine_return_to_return',$data_purchase_topurchase);
		    //echo $this->db->last_query(); exit;
		    $data_new_stock=array(
		                    "branch_id"=>$user_data['parent_id'],
					    	"type"=>2,
					    	"parent_id"=>$purchase_id,
					    	"m_id"=>$medicine_list,
					    	"credit"=>$qtys,
					    	"debit"=>0,
							"batch_no"=>$post['batch_no'][$i],
    				    	"conversion"=>$post['conversion'][$i],
    				    	"purchase_rate"=>$post['purchase_rate'][$i],
    				    	"mrp"=>$post['mrp'][$i],
    				    	"discount"=>$post['discount'][$i],
    				    	'hsn_no'=>$post['hsn_no'][$i],
    				    	'bar_code'=>$post['bar_code'][$i],
    						'sgst'=>$sgst,
    						'igst'=>$igst,
    						'cgst'=>$cgst,
    				    	"total_amount"=>$post['row_total_amount'][$i],
    				    	"expiry_date"=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
    				    	'manuf_date'=>$manuf_date,
    				    	'per_pic_rate'=>($post['mrp'][$i]/$post['conversion'][$i]),
							"created_by"=>$user_data['id'],
							"created_date"=>date('Y-m-d',strtotime($post['purchase_date']))
					    	);
					$this->db->insert('hms_medicine_stock',$data_new_stock);
						 
						 
					$this->db->select('hms_medicine_batch_stock.id');
                    $this->db->where('hms_medicine_batch_stock.medicine_id',$medicine_list);
                    $this->db->where('hms_medicine_batch_stock.batch_no',$post['batch_no'][$i]);
                    $query_batch_stock = $this->db->get('hms_medicine_batch_stock'); 
                    $row_batch_stock = $query_batch_stock->result();
					 
					 if(!empty($row_batch_stock))
					 {
					     $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` - '".$qtys."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no'][$i]."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$medicine_list."' ");
					 }
					 else
					 {
	                     $batch_stock_data = array(
                                   'branch_id'=>$user_data['parent_id'],
                                   'medicine_id'=>$medicine_list,
                                   'batch_no'=>$post['batch_no'][$i],
                                   'hsn_no'=>$post['hsn_no'][$i],
                                   'quantity'=>$qtys,
                                   'purchase_rate'=>$post['purchase_rate'][$i],
                                   'mrp'=>$post['mrp'][$i],
                                   'discount'=>$row_batch_stock[0]->discount,
                                   'vat'=>0,
                                   'sgst'=>$sgst,
                                   'cgst'=>$cgst,
                                   'igst'=>$igst,
                                   'bar_code'=>$post['bar_code'][$i],
                                   'conversion'=>$post['conversion'][$i],
                                   'total_amount'=>$post['row_total_amount'][$i],
                                   'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
                                   'manuf_date'=>$manuf_date,
                                   'per_pic_rate'=>($post['mrp'][$i]/$post['conversion'][$i]),
                                   'created_date'=>date('Y-m-d',strtotime($post['purchase_date']))
        					     ); 
					   $this->db->insert("hms_medicine_batch_stock", $batch_stock_data);
                    }
            
				    $i++;
				} 

                 $payment_data = array(
    								'parent_id'=>$purchase_id,
    								'branch_id'=>$user_data['parent_id'],
    								'section_id'=>'3',
    								'doctor_id'=>'',
    								'vendor_id'=>$vendor_id,
    								'type'=>'3',
    								'patient_id'=>'',
    								'total_amount'=>str_replace(',', '', $post['total_amount']),
    								'discount_amount'=>$post['discount_amount'],
    								'net_amount'=>str_replace(',', '', $post['net_amount']),
    								'credit'=>str_replace(',', '', $post['net_amount']),
    								'debit'=>$post['pay_amount'],
    								'pay_mode'=>$post['payment_mode'],
    								'bank_name'=>$bank_name,
    								'card_no'=>$card_no,
    								'cheque_no'=>$cheque_no,
    								'cheque_date'=>$payment_date,
    								'balance'=>$blance,
    								'paid_amount'=>$post['pay_amount'],
    								'transection_no'=>$transaction_no,
    								'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
    								'created_by'=>$user_data['id']
                	             );
                $this->db->insert('hms_payment',$payment_data);

            /*add sales banlk detail*/
         if(!empty($post['field_name']))
         {	
             for($i=0;$i<$counter_name;$i++) 
            {
            	$data_field_value= array(
            								'field_value'=>$post['field_name'][$i],
            								'field_id'=>$post['field_id'][$i],
            								'type'=>2,
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
			           
		} 
		
		//$this->session->unset_userdata('medicine_id'); 	
		
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
			$this->db->update('hms_medicine_return');
			
			
			 // Update batch stock
            $this->db->select('hms_medicine_return_to_return.medicine_id, hms_medicine_return_to_return.batch_no, hms_medicine_return_to_return.qty'); 
            $this->db->where('hms_medicine_return_to_return.purchase_return_id',$id); 
            $query = $this->db->get('hms_medicine_return_to_return');
            $sale_to_medicine_data =  $query->result_array();
            if(!empty($sale_to_medicine_data))
            {
                foreach($sale_to_medicine_data as $sale_to_medicine)
                {
                     $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity`+'".$sale_to_medicine['qty']."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$sale_to_medicine['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$sale_to_medicine['medicine_id']."' "); 
                }
            }
          // End update batch stock
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
                  
                  // Update batch stock
                    $this->db->select('hms_medicine_return_to_return.medicine_id, hms_medicine_return_to_return.batch_no, hms_medicine_return_to_return.qty'); 
                    $this->db->where('hms_medicine_return_to_return.purchase_return_id',$id); 
                    $query = $this->db->get('hms_medicine_return_to_return');
                    $sale_to_medicine_data =  $query->result_array();
                    if(!empty($sale_to_medicine_data))
                    {
                        foreach($sale_to_medicine_data as $sale_to_medicine)
                        {
                             $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity`+'".$sale_to_medicine['qty']."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$sale_to_medicine['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$sale_to_medicine['medicine_id']."' "); 
                        }
                    }
                  // End update batch stock
    			} 
    		}
    		$branch_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_medicine_return');
    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_return');
        $result = $query->result(); 
        return $result; 
    } 

   public function medicine_list($ids="",$batch_no="")
    {
 
       $users_data = $this->session->userdata('auth_users'); 
       //print_r($batch_no); die;
		$keys = '';
		if(!is_array($ids) || !is_array($batch_no))
		{
		    if(!empty($batch_no))
		    {
		        $keys = "'".$ids.'.'.$batch_no."'";
		    }
		    else
		    {
		        $keys = "'".$ids."'";
		    }
			
			//echo $keys; die;
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

     
		$this->db->select('hms_medicine_entry.*, hms_medicine_stock.bar_code,hms_medicine_stock.mrp as m_rp,IFNULL(hms_medicine_stock.purchase_rate, 0) as p_rate, hms_medicine_stock.hsn_no as hsn, hms_medicine_stock.purchase_rate,hms_medicine_stock.conversion as conv,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, hms_medicine_stock.batch_no,hms_medicine_stock.expiry_date,hms_medicine_stock.manuf_date,hms_medicine_stock.debit as qty, 
            (CASE WHEN hms_medicine_stock.id>0 THEN "0" ELSE "0" END) as unit2, 
            (CASE WHEN hms_medicine_stock.id>0 THEN "0" ELSE "0" END) as unit1, 
            (CASE WHEN hms_medicine_stock.id>0 THEN "0" ELSE "0" END) as freeunit1,
            (CASE WHEN hms_medicine_stock.id>0 THEN "0" ELSE "0" END) as freeunit2,hms_medicine_entry.purchase_rate as initial_p_rate');


		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id'); 

       if(!empty($keys))
		{ 
	     	$this->db->where('CONCAT(hms_medicine_stock.m_id,".",hms_medicine_stock.batch_no) IN ('.$keys.') ');
		}

		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');

		$this->db->where('hms_medicine_entry.is_deleted','0');  
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_medicine_stock.m_id, hms_medicine_stock.batch_no');  
		$query = $this->db->get('hms_medicine_stock');
		///echo $this->db->last_query(); exit;
		$result = $query->result(); 
		
		//echo $this->db->last_query();
		//print '<pre>'; print_r($result);die;
		return $result;
    }

 

    public function medicine_list_search()
    {

    	$users_data = $this->session->userdata('auth_users'); 
    	/*$added_medicine_list = $this->session->userdata('medicine_id');
    	$added_medicine_ids = [];
    	$added_medicine_batch = [];
    	if(!empty($added_medicine_list))
    	{
			$added_medicine_ids = array_column($added_medicine_list,'mid');
			$added_medicine_batch = array_column($added_medicine_list,'batch_no');
    	} */
    	$added_medicine_list = $this->session->userdata('medicine_id');
    	$added_medicine_ids = [];
    	$added_medicine_batch = [];
    	
    	$post = $this->input->post(); 
		/*$this->db->select('hms_medicine_purchase_to_purchase.*,hms_medicine_purchase_to_purchase.id as p_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_entry.mrp,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_purchase_to_purchase.batch_no = batch_no) as stock_quantity, hms_medicine_purchase_to_purchase.batch_no,hms_medicine_unit.medicine_unit,hms_medicine_unit_2.medicine_unit as medicine_unit_2');  
		    //$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id');
			$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id');
			$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
			$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_purchase_to_purchase.batch_no = batch_no)>0');*/
			$this->db->select('hms_medicine_stock.*,hms_medicine_stock.id as p_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_stock.hsn_no as hsn,hms_medicine_entry.mrp,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,hms_medicine_stock.batch_no,(sum(hms_medicine_stock.debit)-sum(hms_medicine_stock.credit)) as qty, hms_medicine_entry.min_alrt,hms_medicine_unit.medicine_unit,hms_medicine_unit_2.medicine_unit as medicine_unit_2');  
			$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id');

			$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
			$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		
		if(!empty($post['medicine_name']))
		{
			
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$post['medicine_name'].'%"');
			
		}

		if(!empty($post['medicine_code']))
		{  

			$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$post['medicine_code'].'%"');
			
		}
		/*if(!empty($post['mfc_date']))
		{  
			$this->db->where('hms_medicine_entry.created_date LIKE "'.date('Y-m-d',strtotime($post['mfc_date'])).'%"');
			
		}*/
		/*if(!empty($post['exp_date']))
		{  
			$this->db->where('hms_medicine_entry.packing LIKE "'.$post['exp_date'].'%"');
			
		}*/
		if(!empty($post['unit1']))
		{  
			$this->db->where('hms_medicine_unit.id ="'.$post['unit1'].'"');

			
		}
		if(!empty($post['unit2']))
		{  
			$this->db->where('hms_medicine_unit_2.id ="'.$post['unit2'].'"');
			
		}
		if(!empty($post['mrp']))
		{  
			$this->db->where('hms_medicine_entry.mrp ="'.$post['mrp'].'"');
			
		}
		if(!empty($post['p_rate']))
		{  
			$this->db->where('hms_medicine_entry.purchase_rate = "'.$post['p_rate'].'"');
			
		}
		if(!empty($post['discount']))
		{  
			//echo 'fsf';die;
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
		if(!empty($post['igst']))
		{  
			$this->db->where('hms_medicine_entry.igst = "'.$post['igst'].'"');
			
		}
		if(!empty($post['conv']))
		{  
			$this->db->where('hms_medicine_entry.conversion = "'.$post['conv'].'"');
			
		}
		if(!empty($post['purchase_quantity']))
		{  
             //echo $post['purchase_quantity'];die;
              //echo 'fsfsd';die;
			//$this->db->where($post['purchase_quantity'],'IN (select(sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id) as avl_qty');
			//echo $this->db->last_query();die;
		}
		if(!empty($post['stock_quantity']))
		{  

			$this->db->where($post['stock_quantity'],'IN (SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id) as stock_quantity');
			
		}
		if(!empty($post['conv']))
		{  
			$this->db->where('hms_medicine_entry.conversion LIKE "'.$post['conv'].'"');
			
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
		if(!empty($post['batch_number']))
		{  
			$this->db->where('hms_medicine_stock.batch_no = "'.$post['batch_number'].'"');
			
		}

		if(!empty($post['bar_code']))
		{  
			$this->db->where('hms_medicine_stock.bar_code LIKE"'.$post['bar_code'].'%"');
			
		}

		

		if(!empty($post['hsn_no']))
		{  
			$this->db->where('hms_medicine_entry.hsn_no = "'.$post['hsn_no'].'"');
			
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
           
			$this->db->where('CONCAT(hms_medicine_stock.m_id,".",hms_medicine_stock.batch_no) NOT IN ('.$keys.') ');
		} 
		  
	  /*if(isset($added_medicine_list) && !empty($added_medicine_list))
		{ 
			$keys = implode(',', array_keys($added_medicine_list));
	     	$this->db->where('CONCAT(hms_medicine_stock.m_id,".",hms_medicine_stock.batch_no) NOT IN ('.$keys.') ');
		} */


		$this->db->where('hms_medicine_stock.kit_status',0);
		$this->db->group_by('hms_medicine_stock.m_id');
/* new code is_deleted */
$this->db->where('hms_medicine_entry.is_deleted','0');
/* new code is_deleted */
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')');  
	    $this->db->group_by('hms_medicine_stock.batch_no');
        $this->db->from('hms_medicine_stock'); 
        //echo $this->db->last_query();die;
        $querys = $this->db->get(); 
        //echo $this->db->last_query();die;
        $data= $querys->result();
        //print '<pre>';print_r($data);die;
        //echo $this->db->last_query();die;
		return  $querys->result();
    }

     public function get_batch_med_qty($mid="",$batch_no="")
		{
			$user_data = $this->session->userdata('auth_users');
			$this->db->select('(sum(debit)-sum(credit)) as total_qty');
			$this->db->where('branch_id',$user_data['parent_id']); 
			$this->db->where('m_id',$mid);
			$this->db->where('batch_no',$batch_no);
			$query = $this->db->get('hms_medicine_stock');
			return $query->row_array();
		}

	  public function check_stock_avability($id="",$batch_no="0"){
	  	
	  	$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_medicine_stock.*,hms_medicine_entry.*,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id and batch_no="'.$batch_no.'" and m_id="'.$id.'") as avl_qty');   
	   $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id');
		$this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->where('hms_medicine_stock.batch_no',$batch_no);
		
		$this->db->where('hms_medicine_stock.m_id',$id);
		 $this->db->group_by('hms_medicine_stock.batch_no');
		$query = $this->db->get('hms_medicine_stock');
		// echo $this->db->last_query();die;
        $result = $query->row(); 
       
         return $result->avl_qty;
      
	  }



     public function unit_list($unit_id="")
    {
       $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
         if(!empty($unit_id)){
         	
        	$this->db->where('id',$unit_id);
        }
        $this->db->order_by('medicine_unit','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_medicine_unit');
        $result = $query->result(); 
      // print '<pre>'; print_r($result);
        return $result; 
    }

    function get_all_detail_print($ids="",$branch_ids="")
    {
		//echo $ids;die;
    	$result_sales=array();
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_return.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_medicine_vendors.name as name,hms_medicine_vendors.email as v_email,hms_medicine_vendors.mobile as mobile,hms_medicine_vendors.address as v_address,hms_medicine_vendors.address2 as v_address2,hms_medicine_vendors.address3 as v_address3,hms_users.*,hms_medicine_vendors.vendor_gst"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_return.vendor_id','left'); 
		$this->db->join('hms_users','hms_users.id = hms_medicine_return.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		$this->db->where('hms_medicine_return.is_deleted','0'); 
		$this->db->where('hms_medicine_return.branch_id = "'.$branch_ids.'"'); 
		$this->db->where('hms_medicine_return.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['purchase_list']= $this->db->get()->result();
		
		$this->db->select('hms_medicine_return_to_return.*,hms_medicine_return_to_return.purchase_rate as p_r,hms_medicine_entry.*,hms_medicine_return_to_return.sgst as m_sgst,hms_medicine_return_to_return.igst as m_igst,hms_medicine_return_to_return.cgst as m_cgst,hms_medicine_return_to_return.discount as m_disc');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_return_to_return.medicine_id'); 
		$this->db->where('hms_medicine_return_to_return.purchase_return_id = "'.$ids.'"');
		$this->db->from('hms_medicine_return_to_return');
		$result_sales['purchase_list']['medicine_list']=$this->db->get()->result();
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

    function  get_user_detail(){
    	$post = $this->input->post();
    	if(isset($post) && !empty($post))
    	{
    		$this->db->select('*');
    		$this->db->where('username',$post['username']);
    		$this->db->where('password',md5($post['password']));
    		$this->db->where('status','1'); 
    		$query = $this->db->get('hms_users');
    		$result = $query->row_array(); 

        // Permission gethering /
        $this->db->select('hms_permission_to_users.section_id, hms_permission_to_users.action_id');
        $this->db->where('users_id',$result['id']);   
        $query = $this->db->get('hms_permission_to_users');
        $permission_list = $query->result(); 
        $permission = [];
        if(!empty($permission_list))
        {
          $section = [];
          $action = [];
          foreach($permission_list as $permission)
          {
             if(!in_array($permission->section_id,$section))
             {
                $section[] = $permission->section_id;
             }

             $action[] = $permission->action_id;
          }
          $permission = array('section'=>$section, 'action'=>$action);

          if(!empty($result))
          {
            $result['permission'] = $permission;
          }
        }
        ////////////////////////

    		return $result;
    	}
    }
    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
		$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

		$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.type',2);
		$this->db->where('hms_payment_mode_field_value_acc_section.section_id',1);
		$this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.branch_id = "'.$users_data['parent_id'].'"');
		$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
		return $query;
	}
	
	public function search_purchase($vals="")
    {
      $response = '';
      if(!empty($vals))
      {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_medicine_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.email as email,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_medicine_purchase_to_purchase where purchase_id = hms_medicine_purchase.id) as total_medicine"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		$this->db->where('hms_medicine_purchase.is_deleted','0'); 

		$this->db->where('hms_medicine_purchase.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->from('hms_medicine_purchase'); 
		$this->db->where('hms_medicine_purchase.purchase_id LIKE "'.$vals.'%"');
		$query = $this->db->get(); 
		$result = $query->result();
			
	  //echo $this->db->last_query(); exit;
      //echo "<pre>"; print_r($result); exit;
      if(!empty($result))
      { 
        $data = array();
        foreach($result as $vals)
        {
            $name = $vals->purchase_id.'|'.$vals->id.'|'.$vals->vendor_id.'|'.$vals->invoice_id.'|'.$vals->total_amount.'|'.$vals->net_amount.'|'.$vals->paid_amount.'|'.$vals->balance.'|'.$vals->remarks.'|'.$vals->discount.'|'.$vals->sgst.'|'.$vals->cgst.'|'.$vals->igst.'|'.$vals->discount_percent.'|'.$vals->vat_percent.'|'.$vals->mode_payment.'|'.$vals->vendor_name.'|'.$vals->v_id.'|'.$vals->mobile.'|'.$vals->email;
            array_push($data, $name);
        }

        echo json_encode($data);
      }
      //return $response; 
  } 
}

public function check_row_stock_avability($id="",$batch_no="0",$row_qty='')
{
	  	
  	$user_data = $this->session->userdata('auth_users');
	$this->db->select('hms_medicine_stock.*,hms_medicine_entry.*,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id and batch_no="'.$batch_no.'" and m_id="'.$id.'") as avl_qty');   
   $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id');
	$this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
	$this->db->where('hms_medicine_stock.batch_no',$batch_no);
	
	$this->db->where('hms_medicine_stock.m_id',$id);
	 $this->db->group_by('hms_medicine_stock.batch_no');
	$query = $this->db->get('hms_medicine_stock');
	// echo $this->db->last_query();die;
    $result = $query->row(); 
   
     return $result->avl_qty;
  
  }
  
  
  

} 
?>