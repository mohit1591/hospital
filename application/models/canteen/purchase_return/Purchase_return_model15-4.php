<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_return_model extends CI_Model 
{
	var $table = 'hms_canteen_return';
    var $column = array('hms_canteen_return.id','hms_canteen_vendors.name','hms_canteen_return.purchase_id','hms_canteen_return.invoice_id','hms_canteen_return.id','hms_canteen_return.total_amount','hms_canteen_return.net_amount','hms_canteen_return.paid_amount','hms_canteen_return.balance','hms_canteen_return.created_date', 'hms_canteen_return.modified_date');  
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
		$this->db->select("hms_canteen_return.*, hms_canteen_vendors.name as vendor_name, (select count(id) from hms_canteen_return_to_return where purchase_return_id = hms_canteen_return.id) as total_medicine"); 
		$this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_return.vendor_id','left');

		$this->db->where('hms_canteen_return.is_deleted','0'); 
		$this->db->where('hms_canteen_vendors.is_deleted','0'); 

		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_canteen_return.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_canteen_return.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		$this->db->from($this->table); 
		if(isset($search) && !empty($search))
		{
			
            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_canteen_return.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_canteen_return.purchase_date <= "'.$end_date.'"');
			}


			if(isset($search['vendor_name']) && !empty($search['vendor_name']))
			{

				$this->db->where('hms_canteen_vendors.name',$search['vendor_name']);
			}

			if(isset($search['vendor_code']) &&  !empty($search['vendor_code']))

			{
				
			  $this->db->where('hms_canteen_vendors.vendor_id LIKE "'.$search['vendor_code'].'%"');
			}

			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
				$this->db->where('hms_canteen_vendors.mobile LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['invoice_id']) && !empty($search['invoice_id']))
			{
				$this->db->where('hms_canteen_return.invoice_id LIKE "'.$search['invoice_id'].'%"');
			}

			if(isset($search['purchase_no']) &&  !empty($search['purchase_no']))
			{
				$this->db->where('hms_canteen_return.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['product_name']) &&  !empty($search['product_name']))
			{
				$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
				$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
				$this->db->where('hms_canteen_master_entry.product_name LIKE "'.$search['product_name'].'%"');
			}

			if(isset($search['product_company']) &&  !empty($search['product_company']))
			{
				if(empty($search['product_name']))
				{

				 $this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
				 $this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
				}
				
				$this->db->join('hms_canteen_manuf_company','hms_canteen_manuf_company.id = hms_canteen_master_entry.manuf_company','left'); 
				$this->db->where('hms_canteen_manuf_company.company_name LIKE"'.$search['product_company'].'%"');
			}

			if(isset($search['product_code']) && !empty($search['product_code']))
			{
				if(empty($search['product_name']) && empty($search['product_company']))
				{
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				$this->db->where('hms_canteen_master_entry.product_code LIKE "'.$search['product_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_canteen_return.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']))
				{
				$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
				 $this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left');

				}
				$this->db->where('hms_canteen_return_to_return.discount = "'.$search['discount'].'"');
			}

			


			if(isset($search['unit1']) && $search['unit1']!="")
			{
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']))
				{
				$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left');

					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id');
					$this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id = hms_canteen_master_entry.unit_id','left');
					$this->db->join('hms_canteen_stock_item_unit as hms_canteen_stock_item_unit_2','hms_canteen_stock_item_unit_2.id = hms_canteen_master_entry.unit_second_id','left'); 
					
					

				}
				
				$this->db->where('hms_canteen_stock_item_unit.id = "'.$search['unit1'].'"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']) && empty($search['unit1']))
				{
						
				$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left');

				$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id');
				$this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id = hms_canteen_master_entry.unit_id','left'); 
				$this->db->join('hms_canteen_stock_item_unit as hms_canteen_stock_item_unit_2','hms_canteen_stock_item_unit_2.id = hms_canteen_master_entry.unit_second_id','left');
				}

				$this->db->where('hms_canteen_stock_item_unit_2.id ="'.$search['unit2'].'"');
			}

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']) && empty($search['unit1']) && empty($search['unit2']) )
				{	
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_canteen_master_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) &&  $search['conversion']!="")
			{
				

				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) )
				{		
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				$this->db->where('hms_canteen_master_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) )
				{	
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				$this->db->where('hms_canteen_master_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) && empty($search['mrp_to']) )
				{	
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				$this->db->where('hms_canteen_master_entry.mrp <="'.$search['mrp_from'].'"');
			}


			if(isset($search['sgst']) &&  !empty($search['sgst']))
			{
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{	
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }

				$this->db->where('hms_canteen_return_to_return.sgst', $search['sgst']);
			}

			if(isset($search['cgst']) &&  !empty($search['cgst']))
			{
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['sgst']))
				{	
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }

				$this->db->where('hms_canteen_return_to_return.cgst', $search['cgst']);
			}
			if(isset($search['igst']) &&  !empty($search['igst']))
			{
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing'])  && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['sgst']) && empty($search['cgst']))
				{	
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }

				$this->db->where('hms_canteen_return_to_return.igst', $search['igst']);
			}



			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_canteen_return.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) &&$search['paid_amount_from']!="")
			{
				$this->db->where('hms_canteen_return.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) &&$search['balance_to']!="")
			{
				$this->db->where('hms_canteen_return.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_canteen_return.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_canteen_return.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_canteen_return.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_canteen_return.bank_name',$search['bank_name']);
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
			$this->db->where('hms_canteen_return.created_by IN ('.$emp_ids.')');
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
					$this->db->group_end(); //close bracket hms_canteen_stock_item
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
		$this->db->select("hms_canteen_return.*, hms_canteen_vendors.name as vendor_name, (select count(id) from hms_canteen_return_to_return where purchase_return_id = hms_canteen_return.id) as total_medicine"); 
		$this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_return.vendor_id','left'); 
		$this->db->where('hms_canteen_return.is_deleted','0'); 
		$this->db->where('hms_canteen_vendors.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_canteen_return.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_canteen_return.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_canteen_return.branch_id = "'.$user_data['parent_id'].'"'); 
		
		$this->db->from($this->table);
		if(isset($search) && !empty($search))
		{
			
           if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_canteen_return.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_canteen_return.purchase_date <= "'.$end_date.'"');
			}


			if(isset($search['vendor_name']) && !empty($search['vendor_name']))
			{

				$this->db->where('hms_canteen_vendors.name',$search['vendor_name']);
			}

			if(isset($search['vendor_code']) &&  !empty($search['vendor_code']))

			{
				
			  $this->db->where('hms_canteen_vendors.vendor_id LIKE "'.$search['vendor_code'].'%"');
			}

			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
				$this->db->where('hms_canteen_vendors.mobile LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['invoice_id']) && !empty($search['invoice_id']))
			{
				$this->db->where('hms_canteen_return.invoice_id LIKE "'.$search['invoice_id'].'%"');
			}

			if(isset($search['purchase_no']) &&  !empty($search['purchase_no']))
			{
				$this->db->where('hms_canteen_return.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['product_name']) &&  !empty($search['product_name']))
			{
				$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
				$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
				$this->db->where('hms_canteen_master_entry.product_name LIKE "'.$search['product_name'].'%"');
			}

			if(isset($search['product_company']) &&  !empty($search['product_company']))
			{
				if(empty($search['product_name']))
				{

				 $this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
				 $this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
				}
				
				$this->db->join('hms_canteen_manuf_company','hms_canteen_manuf_company.id = hms_canteen_master_entry.manuf_company','left'); 
				$this->db->where('hms_canteen_manuf_company.company_name LIKE"'.$search['product_company'].'%"');
			}

			if(isset($search['product_code']) && !empty($search['product_code']))
			{
				if(empty($search['product_name']) && empty($search['product_company']))
				{
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				$this->db->where('hms_canteen_master_entry.product_code LIKE "'.$search['product_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_canteen_return.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']))
				{
				$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
				}
				$this->db->where('hms_canteen_return_to_return.discount = "'.$search['discount'].'"');
			}

			if(isset($search['sgst']) &&  !empty($search['sgst']))
			{
				// if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
			 //     }
				$this->db->where('hms_canteen_return.sgst', $search['sgst']);
			}

			if(isset($search['cgst']) &&  !empty($search['cgst']))
			{
				// if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
			 //     }
				$this->db->where('hms_canteen_return.cgst', $search['cgst']);
			}
			if(isset($search['igst']) &&  !empty($search['igst']))
			{
				// if(empty($search['product_name']) && empty($search['product_company']) && empty($search['product_code']) && empty($search['discount']))
				// {
				// $this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
			 //     }
				$this->db->where('hms_canteen_return.igst', $search['igst']);
			}

			if(isset($search['unit1']) && $search['unit1']!="")
			{
				$this->db->where('hms_canteen_stock_item_unit.id = "'.$search['unit1'].'"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				$this->db->where('hms_canteen_stock_item_unit_2.id ="'.$search['unit2'].'"');
			}

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['product_code']) && empty($search['product_company']) && empty($search['product_name'])  && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']))
				{
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_canteen_master_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) &&  $search['conversion']!="")
			{
				if(empty($search['product_code']) && empty($search['product_company']) && empty($search['product_name'])  && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['packing']))
				{
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				$this->db->where('hms_canteen_master_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['product_code']) && empty($search['product_company']) && empty($search['product_name'])  && empty($search['discount'])  && empty($search['cgst']) && empty($search['igst'])  && empty($search['sgst']) && empty($search['conversion']))
				{
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                } 
				$this->db->where('hms_canteen_master_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['product_code']) && empty($search['product_company']) && empty($search['product_name'])  && empty($search['discount'])  && empty($search['igst'])  && empty($search['sgst']) && empty($search['conversion']) && empty($search['mrp_to']))
				{
					$this->db->join('hms_canteen_return_to_return','hms_canteen_return_to_return.purchase_return_id = hms_canteen_return.id','left'); 
					$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id','left'); 
                }
				$this->db->where('hms_canteen_master_entry.mrp <="'.$search['mrp_from'].'"');
			}

			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_canteen_return.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) &&$search['paid_amount_from']!="")
			{
				$this->db->where('hms_canteen_return.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) &&$search['balance_to']!="")
			{
				$this->db->where('hms_canteen_return.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_canteen_return.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_canteen_return.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_canteen_return.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_canteen_return.bank_name',$search['bank_name']);
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
			$this->db->where('hms_canteen_return.created_by IN ('.$emp_ids.')');
		}
		$query = $this->db->get(); 

		$data= $query->result();
		//echo $this->db->last_query();die;
		return $data;
	}

	public function get_by_id($id)
	{
		$this->db->select('hms_canteen_return.*');
		$this->db->from('hms_canteen_return'); 
		$this->db->where('hms_canteen_return.id',$id);
		$this->db->where('hms_canteen_return.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	public function get_vendor_by_id($id)
	{

		$this->db->select('hms_canteen_vendors.*');
		$this->db->from('hms_canteen_vendors'); 
		$this->db->where('hms_canteen_vendors.id',$id);
		//$this->db->where('hms_canteen_vendors.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}

	public function get_medicine_by_purchase_id($id="")
	{
		$this->db->select('hms_canteen_return_to_return.*');
		$this->db->from('hms_canteen_return_to_return'); 
		if(!empty($id))
		{
          $this->db->where('hms_canteen_return_to_return.purchase_return_id',$id);
		} 
		$query = $this->db->get()->result(); 
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $medicine)	
		  {

			/*$tot_qty_with_rate= $medicine->per_pic_price*1;
			$vatToPay = ($tot_qty_with_rate / 100) * $medicine->vat;
			$totalPrice = $tot_qty_with_rate + $vatToPay;
			$total_discount = ($medicine->discount/100)*$totalPrice;
			$total_amount= $totalPrice-$total_discount;

               $result[$medicine->item_id] = array('mid'=>$medicine->item_id, 'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'discount'=>$medicine->discount,'vat'=>$medicine->vat, 'purchase_amount'=>$medicine->per_pic_price, 'total_amount'=>$medicine->total_amount); */

               $ratewithunit1= $medicine->purchase_rate*$medicine->unit1;
				//$ratewithunit1= $medicine_data[0]->purchase_rate*$post['unit1'];

				$perpic_rate= 0;
				$ratewithunit2=$perpic_rate*$medicine->unit1;
				$tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
				$cgstToPay = ($tot_qty_with_rate / 100) * $medicine->cgst;
				$igstToPay = ($tot_qty_with_rate / 100) * $medicine->igst;
				$sgstToPay = ($tot_qty_with_rate / 100) * $medicine->sgst;
            	$totalPrice = $tot_qty_with_rate + $cgstToPay+$igstToPay+$sgstToPay;
				$total_discount = ($medicine->discount/100)*$totalPrice;
				$total_amount= $totalPrice-$total_discount;



               $result[$medicine->item_id] = array('mid'=>$medicine->item_id,'conversion'=>$medicine->conversion,'unit1'=>$medicine->unit1,'freeunit1'=>$medicine->freeunit1,'unit2'=>$medicine->unit2,'freeunit2'=>$medicine->freeunit2,'perpic_rate'=>$perpic_rate,'mrp'=>$medicine->mrp,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)), 'qty'=>$medicine->qty,'freeqty'=>$medicine->freeqty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'discount'=>$medicine->discount,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'purchase_amount'=>$medicine->purchase_rate, 'total_amount'=>$medicine->total_amount, 'bar_code'=>$medicine->bar_code); 
		  } 
		} 
		
		return $result;
	}


	public function save()
	{ 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
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
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['vendor_id']);
			$this->db->update('hms_canteen_vendors',$data_vendor);
            
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
			}
			*/
            $blance= $post['net_amount']-$post['pay_amount'];
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
				//'vat_percent'=>$post['vat_percent'],
				'mode_payment'=>$post['payment_mode'],
				//'bank_name'=>$bank_name,
				//'card_no'=>$card_no,
				//'cheque_no'=>$cheque_no,
				'payment_date'=>$payment_date,
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				'paid_amount'=>$post['pay_amount']
				//'transaction_no'=>$transaction_no
			); 
			$this->db->where('id',$post['data_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->update('hms_canteen_return',$data);
            
			 /*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>2,'section_id'=>1));
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
				'section_id'=>1,
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

			$sess_medicine_list= $this->session->userdata('item_id');
			//echo '<pre>';print_r($sess_medicine_list);die;
			$this->db->where('purchase_return_id',$post['data_id']);
            $this->db->delete('hms_canteen_return_to_return');

            $this->db->where(array('parent_id'=>$post['data_id'],'type'=>2));
            $this->db->delete('hms_canteen_stock_item');
            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{
					$qty = ($medicine_list['conversion']*$medicine_list['unit1'])+$medicine_list['unit2'];
					$free_qty = ($medicine_list['conversion']*$medicine_list['freeunit1'])+$medicine_list['freeunit2'];
					$qtys = $qty+$free_qty; 
					$data_purchase_topurchase = array(
					"purchase_return_id"=>$post['data_id'],
					'item_id'=>$medicine_list['mid'],
					'qty'=>$qtys,
					'freeqty'=>$medicine_list['freeqty'],
					'discount'=>$medicine_list['discount'],
					'sgst'=>$medicine_list['sgst'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'purchase_rate'=>$medicine_list['purchase_amount'],
					'mrp'=>$medicine_list['mrp'],
					'bar_code'=>$medicine_list['bar_code'],
					'unit1'=>$medicine_list['unit1'],
					'unit2'=>$medicine_list['unit2'],
					'freeunit1'=>$medicine_list['freeunit1'],
					'freeunit2'=>$medicine_list['freeunit2'],
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'conversion'=>$medicine_list['conversion'],
					'per_pic_rate'=>$medicine_list['perpic_rate']
					);
					$this->db->insert('hms_canteen_return_to_return',$data_purchase_topurchase);
                         //echo $this->db->last_query();
					$data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>2,
					    	"parent_id"=>$post['data_id'],
					    	"m_id"=>$medicine_list['mid'],
					    	"credit"=>$qtys,
					    	"debit"=>0,
					    	"conversion"=>$medicine_list['conversion'],
							"purchase_rate"=>$medicine_list['purchase_amount'],
							"mrp"=>$medicine_list['mrp'],
							"discount"=>$medicine_list['discount'],
							'sgst'=>$medicine_list['sgst'],
							'igst'=>$medicine_list['igst'],
							'bar_code'=>$medicine_list['bar_code'],
							'cgst'=>$medicine_list['cgst'],
							"total_amount"=>$medicine_list['total_amount'],
							"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
							'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
							'per_pic_rate'=>($medicine_list['mrp']/$medicine_list['conversion']),
							"created_by"=>$created_by_id['created_by'],//$user_data['id'],
							"created_date"=>date('Y-m-d',strtotime($post['purchase_date'])),
					    	);
						 $this->db->insert('hms_canteen_stock_item',$data_new_stock);
				}
				//die;
            } 

             $payment_data = array(
								'parent_id'=>$post['data_id'],
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'15',
								'vendor_id'=>$post['vendor_id'],
								'type'=>'11',
								'doctor_id'=>'',
								'patient_id'=>'',
								'total_amount'=>str_replace(',', '', $post['total_amount']),
								'discount_amount'=>$post['discount_amount'],
								'net_amount'=>str_replace(',', '', $post['net_amount']),
								'credit'=>str_replace(',', '', $post['net_amount']),
								'debit'=>$post['pay_amount'],
								'pay_mode'=>$post['payment_mode'],
								//'bank_name'=>$bank_name,
								//'card_no'=>$card_no,
								//'cheque_no'=>$cheque_no,
								//'cheque_date'=>$payment_date,
								'balance'=>$blance,
								'paid_amount'=>$post['pay_amount'],
								//'transection_no'=>$transaction_no,
                                'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
                                'created_by'=>$created_by_id['created_by'],//$user_data['id']
            	             );
            // print_r($payment_data);die;
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
            
		}
		else{
			$vendor_data= $this->get_vendor_by_id($post['vendor_id']);
			if(count($vendor_data)>0){
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$post['vendor_id']);
			$this->db->update('hms_canteen_vendors',$data_vendor);
			$vendor_id= $post['vendor_id'];
			
			}else{
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_canteen_vendors',$data_vendor); 
			$vendor_id= $this->db->insert_id();
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
			}
			*/
            $blance= $post['net_amount']-$post['pay_amount'];
			$data = array(
				"vendor_id"=>$vendor_id,
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
				//'vat_percent'=>$post['vat_percent'],
				'mode_payment'=>$post['payment_mode'],
				//'bank_name'=>$bank_name,
				//'card_no'=>$card_no,
				//'cheque_no'=>$cheque_no,
				//'payment_date'=>$payment_date,
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				'paid_amount'=>$post['pay_amount']
				//'transaction_no'=>$transaction_no
			); 
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_canteen_return',$data);

			$purchase_id= $this->db->insert_id();

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
					'type'=>2,
					'section_id'=>1,
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

			$sess_medicine_list= $this->session->userdata('item_id'); 
            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{
					$qty = ($medicine_list['conversion']*$medicine_list['unit1'])+$medicine_list['unit2'];
					$free_qty = ($medicine_list['conversion']*$medicine_list['freeunit1'])+$medicine_list['freeunit2'];
					$qtys = $qty+$free_qty; 
					$data_purchase_topurchase = array(
					"purchase_return_id"=>$purchase_id,
					'item_id'=>$medicine_list['mid'],
					'qty'=>$qtys,
					'freeqty'=>$medicine_list['freeqty'],
					'discount'=>$medicine_list['discount'],
					'sgst'=>$medicine_list['sgst'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'purchase_rate'=>$medicine_list['purchase_amount'],
					'mrp'=>$medicine_list['mrp'],
					'bar_code'=>$medicine_list['bar_code'],
					'unit1'=>$medicine_list['unit1'],
					'unit2'=>$medicine_list['unit2'],
					'freeunit1'=>$medicine_list['freeunit1'],
					'freeunit2'=>$medicine_list['freeunit2'],
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'conversion'=>$medicine_list['conversion'],
					'per_pic_rate'=>$medicine_list['perpic_rate']
					);
					$this->db->insert('hms_canteen_return_to_return',$data_purchase_topurchase);
					 $data_new_stock=array("branch_id"=>$user_data['parent_id'],
					    	"type"=>2,
					    	"parent_id"=>$purchase_id,
					    	"m_id"=>$medicine_list['mid'],
					    	"credit"=>$qtys,
					    	"debit"=>0,
							"conversion"=>$medicine_list['conversion'],
							"purchase_rate"=>$medicine_list['purchase_amount'],
							"mrp"=>$medicine_list['mrp'],
							"discount"=>$medicine_list['discount'],
							'sgst'=>$medicine_list['sgst'],
							'igst'=>$medicine_list['igst'],
							'cgst'=>$medicine_list['cgst'],
							'bar_code'=>$medicine_list['bar_code'],
							"total_amount"=>$medicine_list['total_amount'],
							"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
							'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
							'per_pic_rate'=>($medicine_list['mrp']/$medicine_list['conversion']),
							"created_by"=>$user_data['id'],
							"created_date"=>date('Y-m-d',strtotime($post['purchase_date']))
					    	);
						 $this->db->insert('hms_canteen_stock_item',$data_new_stock);


				}
            } 

             $payment_data = array(
								'parent_id'=>$purchase_id,
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'15',
								'vendor_id'=>$vendor_id,
								'doctor_id'=>'',
								'type'=>'11',
								'patient_id'=>'',
								'total_amount'=>str_replace(',', '', $post['total_amount']),
								'discount_amount'=>$post['discount_amount'],
								'net_amount'=>str_replace(',', '', $post['net_amount']),
								'credit'=>str_replace(',', '', $post['net_amount']),
								'debit'=>$post['pay_amount'],
								'pay_mode'=>$post['payment_mode'],
								//'bank_name'=>$bank_name,
								//'card_no'=>$card_no,
								//'cheque_no'=>$cheque_no,
								//'cheque_date'=>$payment_date,
								'balance'=>$blance,
								'paid_amount'=>$post['pay_amount'],
								//'transection_no'=>$transaction_no,
								'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
								'created_by'=>$user_data['id']
            	             );
           //  print_r($payment_data);die;
            $this->db->insert('hms_payment',$payment_data);
           // print_r($this->db->last_query());die;

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
		$this->session->unset_userdata('item_id'); 	
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
			$this->db->update('hms_canteen_return');
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
			$this->db->update('hms_canteen_return');
    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_canteen_return');
        $result = $query->result(); 
        return $result; 
    } 

   public function medicine_list($ids="")
    {
 
       $users_data = $this->session->userdata('auth_users'); 
       $medicine_list = $this->session->userdata('item_id');

       //print_r($medicine_list);die;
		$keys = '';
		if(!is_array($ids))
		{
			$keys = "'".$ids."'";
		}
		else if(is_array($ids))
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
			//echo $key;
		}
		else //if(is_array($ids))
		{
          //  $keys = implode(',', array_keys($medicine_list));
			//$keys = $ids;

			$arr_mids = [];
			if(!empty($medicine_list))
			{ 
			foreach($medicine_list as $medicine_data)
			{
			$arr_mids[] = "'".$medicine_data['mid']."'";
			}
			$keys = implode(',', $arr_mids);
			}
		}
       //echo '<pre>';print_r($medicine_list);die;
        //echo '<pre>';print_r($medicine_list);die;
		/*$this->db->select('hms_canteen_master_entry.*, hms_canteen_stock_item_unit.unit, hms_canteen_stock_item_unit_2.unit as unit_2,hms_canteen_purchase_to_purchase.purchase_rate as p_rate, hms_canteen_purchase_to_purchase.expiry_date,hms_canteen_purchase_to_purchase.id as p_ids,hms_canteen_purchase_to_purchase.manuf_date,hms_canteen_purchase_to_purchase.qty,hms_canteen_purchase_to_purchase.unit2,hms_canteen_purchase_to_purchase.freeunit2,hms_canteen_purchase_to_purchase.freeunit1,hms_canteen_purchase_to_purchase.unit1,hms_canteen_purchase_to_purchase.total_amount,hms_canteen_purchase_to_purchase.conversion');
		$this->db->join('hms_canteen_purchase_to_purchase','hms_canteen_purchase_to_purchase.item_id = hms_canteen_master_entry.id'); */

		$this->db->select('hms_canteen_master_entry.*, hms_canteen_stock_item.bar_code,hms_canteen_stock_item.mrp as m_rp,IFNULL(hms_canteen_stock_item.purchase_rate, 0) as p_rate, hms_canteen_stock_item.purchase_rate,hms_canteen_stock_item.conversion as conv,hms_canteen_stock_item_unit.unit, hms_canteen_stock_item_unit_2.unit as unit_2, hms_canteen_stock_item.expiry_date,hms_canteen_stock_item.manuf_date,hms_canteen_stock_item.debit as qty, 
            (CASE WHEN hms_canteen_stock_item.id>0 THEN "0" ELSE "0" END) as unit2, 
            (CASE WHEN hms_canteen_stock_item.id>0 THEN "0" ELSE "0" END) as unit1, 
            (CASE WHEN hms_canteen_stock_item.id>0 THEN "0" ELSE "0" END) as freeunit1,
            (CASE WHEN hms_canteen_stock_item.id>0 THEN "0" ELSE "0" END) as freeunit2');


		$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_stock_item.item_id'); 

       if(!empty($keys))
		{ 
	     	$this->db->where('CONCAT(hms_canteen_stock_item.item_id) IN ('.$keys.') ');
		}
/*
		if(!empty($ids))
		{
			$this->db->where('hms_canteen_master_entry.id  IN ('.$ids.')'); 
		}
		*/
		
		$this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id = hms_canteen_master_entry.unit_id','left');
		$this->db->join('hms_canteen_stock_item_unit as hms_canteen_stock_item_unit_2','hms_canteen_stock_item_unit_2.id = hms_canteen_master_entry.unit_second_id','left');

		$this->db->where('hms_canteen_master_entry.is_deleted','0');  
		$this->db->where('hms_canteen_master_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->group_by('hms_canteen_stock_item.item_id');  
		$query = $this->db->get('hms_canteen_stock_item');
		$result = $query->result(); 
		
		//echo $this->db->last_query();
		//print '<pre>'; print_r($result);die;
		return $result;
    }

 

    public function medicine_list_search()
    {

    	$users_data = $this->session->userdata('auth_users'); 
    	/*$added_medicine_list = $this->session->userdata('item_id');
    	$added_item_ids = [];
    	$added_medicine_batch = [];
    	if(!empty($added_medicine_list))
    	{
			$added_item_ids = array_column($added_medicine_list,'mid');
			//$added_medicine_batch = array_column($added_medicine_list,'batch_no');
    	} */
    	$added_medicine_list = $this->session->userdata('item_id');
    	$added_item_ids = [];
    	$added_medicine_batch = [];
    	
    	$post = $this->input->post(); 
		/*$this->db->select('hms_canteen_purchase_to_purchase.*,hms_canteen_purchase_to_purchase.id as p_id,hms_canteen_master_entry.id,hms_canteen_master_entry.product_name,hms_canteen_master_entry.mrp,hms_canteen_master_entry.packing,hms_canteen_master_entry.product_code,hms_canteen_manuf_company.company_name,(SELECT (sum(debit)-sum(credit)) from hms_canteen_stock_item where m_id = hms_canteen_master_entry.id) as stock_quantity, hms_canteen_stock_item_unit.unit,hms_canteen_stock_item_unit_2.unit as unit_2');  
		    //$this->db->join('hms_canteen_sale','hms_canteen_sale.id = hms_canteen_sale_to_medicine.sales_id');
			$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_purchase_to_purchase.item_id');
			$this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id = hms_canteen_master_entry.unit_id','left');
			$this->db->join('hms_canteen_stock_item_unit as hms_canteen_stock_item_unit_2','hms_canteen_stock_item_unit_2.id = hms_canteen_master_entry.unit_second_id','left');

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_canteen_stock_item where m_id = hms_canteen_master_entry.id)>0');*/
			$this->db->select('hms_canteen_stock_item.*,hms_canteen_stock_item.id as p_id,hms_canteen_master_entry.id,hms_canteen_master_entry.product_name, hms_canteen_master_entry.mrp,hms_canteen_master_entry.packing,hms_canteen_master_entry.product_code,hms_canteen_manuf_company.company_name,(sum(hms_canteen_stock_item.debit)-sum(hms_canteen_stock_item.credit)) as qty, hms_canteen_master_entry.min_alrt,hms_canteen_stock_item_unit.unit,hms_canteen_stock_item_unit_2.unit as unit_2');  
			$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_stock_item.item_id');

			$this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id = hms_canteen_master_entry.unit_id','left');
			$this->db->join('hms_canteen_stock_item_unit as hms_canteen_stock_item_unit_2','hms_canteen_stock_item_unit_2.id = hms_canteen_master_entry.unit_second_id','left');
		
		if(!empty($post['product_name']))
		{
			
			$this->db->where('hms_canteen_master_entry.product_name LIKE "'.$post['product_name'].'%"');
			
		}

		if(!empty($post['product_code']))
		{  

			$this->db->where('hms_canteen_master_entry.product_code LIKE "'.$post['product_code'].'%"');
			
		}
		/*if(!empty($post['mfc_date']))
		{  
			$this->db->where('hms_canteen_master_entry.created_date LIKE "'.date('Y-m-d',strtotime($post['mfc_date'])).'%"');
			
		}*/
		/*if(!empty($post['exp_date']))
		{  
			$this->db->where('hms_canteen_master_entry.packing LIKE "'.$post['exp_date'].'%"');
			
		}*/
		if(!empty($post['unit1']))
		{  
			$this->db->where('hms_canteen_stock_item_unit.id ="'.$post['unit1'].'"');

			
		}
		if(!empty($post['unit2']))
		{  
			$this->db->where('hms_canteen_stock_item_unit_2.id ="'.$post['unit2'].'"');
			
		}
		if(!empty($post['mrp']))
		{  
			$this->db->where('hms_canteen_master_entry.mrp ="'.$post['mrp'].'"');
			
		}
		if(!empty($post['p_rate']))
		{  
			$this->db->where('hms_canteen_master_entry.purchase_rate = "'.$post['p_rate'].'"');
			
		}
		if(!empty($post['discount']))
		{  
			//echo 'fsf';die;
			$this->db->where('hms_canteen_master_entry.discount = "'.$post['discount'].'"');
			
		}
		if(!empty($post['sgst']))
		{  
			$this->db->where('hms_canteen_master_entry.sgst = "'.$post['sgst'].'"');
			
		}
		if(!empty($post['cgst']))
		{  
			$this->db->where('hms_canteen_master_entry.cgst = "'.$post['cgst'].'"');
			
		}
		if(!empty($post['igst']))
		{  
			$this->db->where('hms_canteen_master_entry.igst = "'.$post['igst'].'"');
			
		}
		if(!empty($post['conv']))
		{  
			$this->db->where('hms_canteen_master_entry.conversion = "'.$post['conv'].'"');
			
		}
		if(!empty($post['purchase_quantity']))
		{  
             //echo $post['purchase_quantity'];die;
              //echo 'fsfsd';die;
			//$this->db->where($post['purchase_quantity'],'IN (select(sum(debit)-sum(credit)) from hms_canteen_stock_item where m_id = hms_canteen_master_entry.id) as avl_qty');
			//echo $this->db->last_query();die;
		}
		if(!empty($post['stock_quantity']))
		{  

			$this->db->where($post['stock_quantity'],'IN (SELECT (sum(debit)-sum(credit)) from hms_canteen_stock_item where m_id = hms_canteen_master_entry.id) as stock_quantity');
			
		}
		if(!empty($post['conv']))
		{  
			$this->db->where('hms_canteen_master_entry.conversion LIKE "'.$post['conv'].'"');
			
		}
		
		if(!empty($post['packing']))
		{  
			$this->db->where('hms_canteen_master_entry.packing LIKE "'.$post['packing'].'"');
			
		}
		
        $this->db->join('hms_canteen_manuf_company','hms_canteen_manuf_company.id = hms_canteen_master_entry.manuf_company','left');
		if(!empty($post['product_company']))
		{  
			$this->db->where('hms_canteen_manuf_company.company_name LIKE"'.$post['product_company'].'%"');

			
		}

		if(isset($added_medicine_list) && !empty($added_medicine_list))
		{ 
			$arr_mids = [];
			if(!empty($added_medicine_list))
			{ 
			foreach($added_medicine_list as $medicine_data)
			{
			$arr_mids[] = "'".$medicine_data['mid']."'";
			}
            $keys = implode(',', $arr_mids);
			} 
           
			$this->db->where('CONCAT(hms_canteen_stock_item.item_id,".") NOT IN ('.$keys.') ');
		} 
		  
	  /*if(isset($added_medicine_list) && !empty($added_medicine_list))
		{ 
			$keys = implode(',', array_keys($added_medicine_list));
	     	$this->db->where('CONCAT(hms_canteen_stock_item.item_id,".") NOT IN ('.$keys.') ');
		} */


		$this->db->where('hms_canteen_stock_item.kit_status',0);
		$this->db->group_by('hms_canteen_stock_item.item_id');
/* new code is_deleted */
$this->db->where('hms_canteen_master_entry.is_deleted','0');
/* new code is_deleted */
		$this->db->where('hms_canteen_master_entry.branch_id  IN ('.$users_data['parent_id'].')');  
        $this->db->from('hms_canteen_stock_item'); 
        //echo $this->db->last_query();die;
        $querys = $this->db->get(); 
        //echo $this->db->last_query();die;
        $data= $querys->result();
        //print '<pre>';print_r($data);die;
        //echo $this->db->last_query();die;
		return  $querys->result();
    }

     public function get_batch_med_qty($mid="")
		{
			$user_data = $this->session->userdata('auth_users');
			$this->db->select('(sum(debit)-sum(credit)) as total_qty');
			$this->db->where('branch_id',$user_data['parent_id']); 
			$this->db->where('m_id',$mid);
			$query = $this->db->get('hms_canteen_stock_item');
			return $query->row_array();
		}

	  public function check_stock_avability($id=""){
	  	//if(!empty($batch_no)){
	  	$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_canteen_stock_item.*,hms_canteen_master_entry.*,(SELECT (sum(debit)-sum(credit)) from hms_canteen_stock_item where m_id = hms_canteen_master_entry.id and m_id="'.$id.'") as avl_qty');   
	   $this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_stock_item.item_id');
		$this->db->where('hms_canteen_master_entry.branch_id = "'.$user_data['parent_id'].'"');
		
		$this->db->where('hms_canteen_stock_item.item_id',$id);
		$query = $this->db->get('hms_canteen_stock_item');
        $result = $query->row(); 
        //echo $this->db->last_query();die;
         return $result->avl_qty;
      //}
	  }



     public function unit_list($unit_id="")
    {
       $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
         if(!empty($unit_id)){
         	
        	$this->db->where('id',$unit_id);
        }
        $this->db->order_by('unit','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_canteen_stock_item_unit');
        $result = $query->result(); 
      // print '<pre>'; print_r($result);
        return $result; 
    }

    function get_all_detail_print($ids="",$branch_ids="")
    {
		//echo $ids;die;
    	$result_sales=array();
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_canteen_return.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_canteen_vendors.name as name,hms_canteen_vendors.email as v_email,hms_canteen_vendors.mobile as mobile,hms_canteen_vendors.address as v_address,hms_canteen_vendors.address2 as v_address2,hms_canteen_vendors.address3 as v_address3,hms_users.*,hms_canteen_vendors.vendor_gst"); 
		$this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_return.vendor_id','left'); 
		$this->db->join('hms_users','hms_users.id = hms_canteen_return.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		$this->db->where('hms_canteen_return.is_deleted','0'); 
		$this->db->where('hms_canteen_return.branch_id = "'.$branch_ids.'"'); 
		$this->db->where('hms_canteen_return.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['purchase_list']= $this->db->get()->result();
		
		$this->db->select('hms_canteen_return_to_return.*,hms_canteen_return_to_return.purchase_rate as p_r,hms_canteen_master_entry.*,hms_canteen_return_to_return.sgst as m_sgst,hms_canteen_return_to_return.igst as m_igst,hms_canteen_return_to_return.cgst as m_cgst,hms_canteen_return_to_return.discount as m_disc');
		$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_return_to_return.item_id'); 
		$this->db->where('hms_canteen_return_to_return.purchase_return_id = "'.$ids.'"');
		$this->db->from('hms_canteen_return_to_return');
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
        $this->db->select("hms_canteen_purchase.*,hms_canteen_vendors.name as vendor_name,hms_canteen_vendors.email as email,hms_canteen_vendors.vendor_id as v_id,hms_canteen_vendors.mobile, (select count(id) from hms_canteen_purchase_to_purchase where purchase_id = hms_canteen_purchase.id) as total_medicine"); 
		$this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_purchase.vendor_id','left'); 
		$this->db->where('hms_canteen_vendors.is_deleted','0'); 
		$this->db->where('hms_canteen_purchase.is_deleted','0'); 

		$this->db->where('hms_canteen_purchase.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->from('hms_canteen_purchase'); 
		$this->db->where('hms_canteen_purchase.purchase_id LIKE "'.$vals.'%"');
		$query = $this->db->get(); 
		$result = $query->result();
			
	  //echo $this->db->last_query(); exit;
      	$purdate = explode(' ', $result[0]->purchase_date);
        $days=get_setting_value('Set_Return_Medicine_Minimum_Days');
        $expire=date('Y-m-d', strtotime($purdate[0]. ' + '.$days.' days'));
       
        $paymentDate = date('Y-m-d');
		$paymentDate=date('Y-m-d', strtotime($paymentDate));
		$contractDateBegin = date('Y-m-d', strtotime($purdate[0]));
		$contractDateEnd = date('Y-m-d', strtotime($expire));


      if(!empty($result) && ($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
      { 
        $data = array();
        foreach($result as $vals)
        {
            $name = $vals->purchase_id.'|'.$vals->id.'|'.$vals->vendor_id.'|'.$vals->invoice_id.'|'.$vals->total_amount.'|'.$vals->net_amount.'|'.$vals->paid_amount.'|'.$vals->balance.'|'.$vals->remarks.'|'.$vals->discount.'|'.$vals->sgst.'|'.$vals->cgst.'|'.$vals->igst.'|'.$vals->discount_percent.'|'.$vals->vat_percent.'|'.$vals->mode_payment.'|'.$vals->vendor_name.'|'.$vals->v_id.'|'.$vals->mobile.'|'.$vals->email;
            array_push($data, $name);
        }

        echo json_encode($data);
      }
      else{
      	echo json_encode(array('status'=>0, 'message'=>'medicine not return'));
      }
      //return $response; 
  } 
}

} 
?>