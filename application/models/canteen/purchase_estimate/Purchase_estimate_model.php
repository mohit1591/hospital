<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_estimate_model extends CI_Model 
{
	var $table = 'hms_canteen_estimate_purchase';
	 

	var $column = array('hms_canteen_estimate_purchase.id','hms_canteen_vendors.name','hms_canteen_estimate_purchase.purchase_id','hms_canteen_estimate_purchase.invoice_id','hms_canteen_estimate_purchase.id','hms_canteen_estimate_purchase.total_amount','hms_canteen_estimate_purchase.net_amount','hms_canteen_estimate_purchase.paid_amount','hms_canteen_estimate_purchase.balance','hms_canteen_estimate_purchase.created_date', 'hms_canteen_estimate_purchase.modified_date');  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('purchase_estimate_search_item');

		$this->db->select("hms_canteen_estimate_purchase.*,hms_canteen_vendors.name as vendor_name ,hms_canteen_vendors.vendor_id as v_id, hms_canteen_vendors.mobile, (select count(id) from hms_canteen_estimate_purchase_to_purchase where purchase_id = hms_canteen_estimate_purchase.id) as total_item"); 
		$this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_estimate_purchase.vendor_id','left'); 
		$this->db->where('hms_canteen_vendors.is_deleted','0'); 
		$this->db->where('hms_canteen_estimate_purchase.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_canteen_estimate_purchase.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_canteen_estimate_purchase.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_canteen_estimate_purchase.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->from($this->table); 

		/////// Search query start //////////////
		if(isset($search) && !empty($search))
		{
			
             if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_canteen_estimate_purchase.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_canteen_estimate_purchase.purchase_date <= "'.$end_date.'"');
			}


			if(isset($search['vendor_name']) && !empty($search['vendor_name']))
			{

				$this->db->where('hms_canteen_vendors.name',$search['vendor_name']);
			}

			if(isset($search['vendor_code']) && !empty($search['vendor_code']))

			{
				
			  $this->db->where('hms_canteen_vendors.vendor_id LIKE "'.$search['vendor_code'].'%"');
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_canteen_vendors.mobile LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['invoice_id']) && !empty($search['invoice_id']))
			{
				$this->db->where('hms_canteen_estimate_purchase.invoice_id', $search['invoice_id']);
			}

			if(isset($search['purchase_no']) && !empty($search['purchase_no']))
			{
				$this->db->where('hms_canteen_estimate_purchase.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['item_name']) && !empty($search['item_name']))
			{
				$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
				$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
				$this->db->where('hms_canteen_item.item LIKE "'.$search['item_name'].'%"');
			}

			if(isset($search['company']) && !empty($search['company']) && empty($search['item_name']))
			{
				if(empty($search['item_name'])){
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left');
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
				}
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_canteen_item.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['company'].'%"');
			}

			if(isset($search['item_code']) && !empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
			{
				if(empty($search['company']) && empty($search['item_name']))
				{
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.item_code LIKE "'.$search['item_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_canteen_estimate_purchase.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
			{
				if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
				{
                      $this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left');

                      $this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_estimate_purchase_to_purchase.item_id','left');

                      //$this->db->where('hms_canteen_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');
				}  
				$this->db->where('hms_canteen_estimate_purchase_to_purchase.discount = "'.$search['discount'].'"');
			}


			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
				{
				$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 

				$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_estimate_purchase_to_purchase.item_id','left');

				//$this->db->where('hms_canteen_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');
			    }
				$this->db->where('hms_canteen_estimate_purchase_to_purchase.batch_no = "'.$search['batch_no'].'"');
			}

			if(isset($search['unit1']) && $search['unit1']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']))
				{
				//$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 

					$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_estimate_purchase_to_purchase.item_id','left');
					//$this->db->where('hms_canteen_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

					
				}
				$this->db->where('hms_canteen_estimate_purchase_to_purchase.unit1 LIKE "%'.$search['unit1'].'%"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				 if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']))
				{
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left');

					$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
			    

				}

				$this->db->where('hms_canteen_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

				//$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
				
			}

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']))
				{
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_canteen_item.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']))
				{
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']))
				{
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.mrp <="'.$search['mrp_from'].'"');
			}


			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{	
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				$this->db->where('hms_canteen_estimate_purchase_to_purchase.cgst', $search['cgst']);
			}


			if(isset($search['igst']) && !empty($search['igst']))
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']))
				{	
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				$this->db->where('hms_canteen_estimate_purchase_to_purchase.igst', $search['igst']);
			}


			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']) && empty($search['igst']))
				{	
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				$this->db->where('hms_canteen_estimate_purchase_to_purchase.sgst', $search['sgst']);
			}



			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_canteen_estimate_purchase.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
			{
				$this->db->where('hms_canteen_estimate_purchase.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) && $search['balance_to']!="")
			{
				$this->db->where('hms_canteen_estimate_purchase.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_canteen_estimate_purchase.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_canteen_estimate_purchase.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_canteen_estimate_purchase.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_canteen_estimate_purchase.bank_name',$search['bank_name']);
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
			$this->db->where('hms_canteen_estimate_purchase.created_by IN ('.$emp_ids.')');
		}

		/////// Search query end //////////////
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

		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('purchase_estimate_search_item');
		$this->db->select("hms_canteen_estimate_purchase.*,hms_canteen_vendors.name as vendor_name,hms_canteen_vendors.vendor_id as v_id,hms_canteen_vendors.mobile, (select count(id) from hms_canteen_estimate_purchase_to_purchase where purchase_id = hms_canteen_estimate_purchase.id) as total_item"); 
		
		$this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_estimate_purchase.vendor_id','left'); 

		
		 //$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_canteen_item.manuf_company');
		$this->db->where('hms_canteen_estimate_purchase.is_deleted','0'); 
		$this->db->where('hms_canteen_vendors.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_canteen_estimate_purchase.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_canteen_estimate_purchase.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_canteen_estimate_purchase.branch_id = "'.$user_data['parent_id'].'"'); 
		
		$this->db->from($this->table); 

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
               if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_canteen_estimate_purchase.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_canteen_estimate_purchase.purchase_date <= "'.$end_date.'"');
			}


			if(isset($search['vendor_name']) && !empty($search['vendor_name']))
			{

				$this->db->where('hms_canteen_vendors.name',$search['vendor_name']);
			}

			if(isset($search['vendor_code']) && !empty($search['vendor_code']))

			{
				
			  $this->db->where('hms_canteen_vendors.vendor_id LIKE "'.$search['vendor_code'].'%"');
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_canteen_vendors.mobile LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['invoice_id']) && !empty($search['invoice_id']))
			{
				$this->db->where('hms_canteen_estimate_purchase.invoice_id', $search['invoice_id']);
			}

			if(isset($search['purchase_no']) && !empty($search['purchase_no']))
			{
				$this->db->where('hms_canteen_estimate_purchase.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['item_name']) && !empty($search['item_name']))
			{
				$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
				$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
				$this->db->where('hms_canteen_item.item LIKE "'.$search['item_name'].'%"');
			}

			if(isset($search['company']) && !empty($search['company']))
			{
				if(empty($search['item_name'])){
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left');
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
				}
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_canteen_item.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['company'].'%"');
			}

			if(isset($search['item_code']) && !empty($search['item_code']))
			{
				if(empty($search['company']) && empty($search['item_name']))
				{
					$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.item_code LIKE "'.$search['item_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_canteen_estimate_purchase.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
				{
                      $this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left');
				}  
				$this->db->where('hms_canteen_estimate_purchase_to_purchase.discount = "'.$search['discount'].'"');
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				// if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
				// } 
				$this->db->where('hms_canteen_estimate_purchase.cgst', $search['cgst']);
			}
			if(isset($search['igst']) && !empty($search['igst']))
			{
				// if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
				// } 
				$this->db->where('hms_canteen_estimate_purchase.igst', $search['igst']);
			}
			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				// if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
				// } 
				$this->db->where('hms_canteen_estimate_purchase.sgst', $search['sgst']);
			}

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']))
				{
				$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
			    }
				$this->db->where('hms_canteen_estimate_purchase_to_purchase.batch_no = "'.$search['batch_no'].'"');
			}

				if(isset($search['unit1']) && $search['unit1']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']))
					{
					//$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
						$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
						$this->db->where('hms_canteen_estimate_purchase_to_purchase.unit1 LIKE "%'.$search['unit1'].'%"');
					}
				}

				if(isset($search['unit2']) && $search['unit2']!="")
				{
					 if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['unit1']))
					{
						$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
				    $this->db->where('hms_canteen_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

					}
					//$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
					
				}

				if(isset($search['packing']) && $search['packing']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['unit2']))
					{
						$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
						$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
	                }
					//$this->db->where('packing',$search['packing']);
					$this->db->where('hms_canteen_item.packing LIKE "'.$search['packing'].'%"');
				}

				if(isset($search['conversion']) && $search['conversion']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['unit2']) && empty($search['unit1']))
					{
						$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
						$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
	                }
					$this->db->where('hms_canteen_item.conversion LIKE "'.$search['packing'].'%"');
				}

				if(isset($search['mrp_to']) && $search['mrp_to']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['unit1']) && empty($search['unit2']))
					{
						$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
						$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
	                }
					$this->db->where('hms_canteen_item.mrp >="'.$search['mrp_to'].'"');
				}
				if(isset($search['mrp_from']) && $search['mrp_from']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['unit2']) && empty($search['unit1']))
					{
						$this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_estimate_purchase_to_purchase.purchase_id = hms_canteen_estimate_purchase.id','left'); 
						$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id','left'); 
	                }
					$this->db->where('hms_canteen_item.mrp <="'.$search['mrp_from'].'"');
				}

				if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
				{
					$this->db->where('hms_canteen_estimate_purchase.paid_amount >="'.$search['paid_amount_to'].'"');
				}

				if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
				{
					$this->db->where('hms_canteen_estimate_purchase.paid_amount <="'.$search['paid_amount_from'].'"');
				}

				if(isset($search['balance_to']) && $search['balance_to']!="")
				{
					$this->db->where('hms_canteen_estimate_purchase.balance >="'.$search['balance_to'].'"');
				}

				if(isset($search['balance_from']) && $search['balance_from']!="")
				{
					$this->db->where('hms_canteen_estimate_purchase.balance <="'.$search['balance_from'].'"');
				}

				if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
				{
					$this->db->where('hms_canteen_estimate_purchase.total_amount >="'.$search['total_amount_to'].'"');
				}
				
				if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
				{
					$this->db->where('hms_canteen_estimate_purchase.total_amount <="'.$search['total_amount_from'].'"');
				}

				if(isset($search['bank_name']) && $search['bank_name']!="")
				{
					$this->db->where('hms_canteen_estimate_purchase.bank_name',$search['bank_name']);
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
			$this->db->where('hms_canteen_estimate_purchase.created_by IN ('.$emp_ids.')');
		}
		$query = $this->db->get(); 

		$data= $query->result();
		//echo $this->db->last_query();die;
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
		$this->db->select('hms_canteen_estimate_purchase.*');
		$this->db->from('hms_canteen_estimate_purchase'); 
		 $this->db->where('hms_canteen_estimate_purchase.id',$id);
		$this->db->where('hms_canteen_estimate_purchase.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function get_by_id_expense($id)
	{
		$this->db->select('hms_expenses.*');
		$this->db->from('hms_expenses'); 
		 $this->db->where('hms_expenses.parent_id',$id);
		$this->db->where('hms_expenses.is_deleted','0');
		$this->db->where('hms_expenses.type','16');
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

	public function get_item_by_purchase_id($id="")
	{
		$this->db->select('hms_canteen_estimate_purchase_to_purchase.*');
		$this->db->from('hms_canteen_estimate_purchase_to_purchase'); 
		if(!empty($id))
		{
          $this->db->where('hms_canteen_estimate_purchase_to_purchase.purchase_id',$id);
		} 
		$query = $this->db->get()->result(); 
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $item_list)	
		  {
           //print_r($item_list);
		
				$tot_qty_with_rate= $item_list->purchase_rate*$item_list->qty;
				$perpic_rate= 0;

				$cgstToPay = ($tot_qty_with_rate / 100) * $item_list->cgst;
				$igstToPay = ($tot_qty_with_rate / 100) * $item_list->igst;
				$sgstToPay = ($tot_qty_with_rate / 100) * $item_list->sgst;
            	$totalPrice = $tot_qty_with_rate + $cgstToPay+$igstToPay+$sgstToPay;
				$total_discount = ($item_list->discount/100)*$totalPrice;
				$total_amount= $totalPrice-$total_discount;

               $result[$item_list->item_id] = array('iid'=>$item_list->item_id,'hsn_no'=>$item_list->hsn_no,'perpic_rate'=>$perpic_rate,'batch_no'=>$item_list->batch_no,'manuf_date'=>date('d-m-Y',strtotime($item_list->manuf_date)),'mrp'=>$item_list->mrp,'qty'=>$item_list->qty,'exp_date'=>date('d-m-Y',strtotime($item_list->expiry_date)),'discount'=>$item_list->discount,'cgst'=>$item_list->cgst,'igst'=>$item_list->igst,'sgst'=>$item_list->sgst, 'purchase_amount'=>$item_list->purchase_rate, 'total_amount'=>$item_list->total_amount,'bar_code'=>$item_list->bar_code); 
		  } 
		} 
		
		return $result;
	}


	public function save()
	{ 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//echo"<pre>"; print_r($post);die;
		$data_vendor = array( 
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
		    $created_by_id= $this->get_by_id($post['data_id']);
		    
           $purchase_detail= $this->get_by_id($post['data_id']);
		   $vendor_id_new= $this->get_vendor_by_id($purchase_detail['vendor_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['vendor_id']);
			$this->db->update('hms_canteen_vendors',$data_vendor);

          


           $blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$data = array(
				"vendor_id"=>$post['vendor_id'],
				'branch_id'=>$user_data['parent_id'],
				'invoice_id'=>$post['invoice_id'],
				'purchase_id'=>$post['purchase_no'],
				'purchase_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
				'purchase_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['purchase_time'])),
				'total_amount'=>$post['total_amount'],
				'net_amount'=>$post['net_amount'],
				'discount'=>$post['discount_amount'],
				'discount_percent'=>$post['discount_percent'],
				//'vat'=>$post['vat_amount'],
				"sgst"=>$post['sgst_amount'],
				"igst"=>$post['igst_amount'],
				"cgst"=>$post['cgst_amount'],
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
			$this->db->where('id',$post['data_id']);
			/*$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));*/
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->update('hms_canteen_estimate_purchase',$data);
            
            /*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>1,'section_id'=>1));
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
				'type'=>1,
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

			$item_canteen_list= $this->session->userdata('item_id');

			$created_by= $this->get_by_id_expense($post['data_id']);
			$this->db->where('purchase_id',$post['data_id']);
            $this->db->delete('hms_canteen_estimate_purchase_to_purchase');
			
            $purchase_id= $post['data_id'];

            if(!empty($item_canteen_list))
            { 
               foreach($item_canteen_list as $item_list)
				{
					
//	echo"<pre>"; print_r($item_list);die;
	
					$qty = $item_list['qty'];
					$qtys = $qty;  
					$data_purchase_topurchase = array(
					"purchase_id"=>$post['data_id'],
					'item_id'=>$item_list['iid'],
					'qty'=>$qtys,
					'freeqty'=>$item_list['freeqty'],
					'discount'=>$item_list['discount'],
					//'vat'=>$item_list['vat'],
					'sgst'=>$item_list['sgst'],
					'igst'=>$item_list['igst'],
					'cgst'=>$item_list['cgst'],
					'hsn_no'=>$item_list['hsn_no'],
					'total_amount'=>$item_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($item_list['exp_date'])),
					'purchase_rate'=>$item_list['purchase_amount'],
					'mrp'=>$item_list['mrp'],
					'batch_no'=>$item_list['batch_no'],
					'bar_code'=>$item_list['bar_code'],
					'unit1'=>$item_list['unit1'],
					'unit2'=>$item_list['unit2'],
					'freeunit1'=>$item_list['freeunit1'],
					'freeunit2'=>$item_list['freeunit2'],
					'manuf_date'=>date('Y-m-d',strtotime($item_list['manuf_date'])),
					'conversion'=>$item_list['conversion'],
					'per_pic_rate'=>$item_list['perpic_rate'],
					'created_date'=>date('Y-m-d',strtotime($post['purchase_date']))
					);
					$this->db->insert('hms_canteen_estimate_purchase_to_purchase',$data_purchase_topurchase);
//echo ($this->db->last_query());die;			
				}
			

            } 
                
            
		}
		else{
			
			$vendor_data= $this->get_vendor_by_id($post['vendor_id']);
			$vendor_code=generate_unique_id(63);
		    $purchase_no = generate_unique_id(66);
			if(count($vendor_data)>0){
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$post['vendor_id']);
			$this->db->update('hms_canteen_vendors',$data_vendor);
			$vendor_id= $post['vendor_id'];
			}else{
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('vendor_id',$vendor_code);
			$this->db->set('vendor_type',1);
			$this->db->insert('hms_canteen_vendors',$data_vendor); 
			$vendor_id= $this->db->insert_id();
		     }
			
            $blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$data = array(
				"vendor_id"=>$vendor_id,
				'branch_id'=>$user_data['parent_id'],
				'invoice_id'=>$post['invoice_id'],
				'purchase_id'=>$purchase_no,
				'purchase_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
				'purchase_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['purchase_time'])),
				'total_amount'=>$post['total_amount'],
				'net_amount'=>$post['net_amount'],
				'discount'=>$post['discount_amount'],
				//'vat'=>$post['vat_amount'],
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
			$this->db->insert('hms_canteen_estimate_purchase',$data);

			$purchase_id= $this->db->insert_id();
		
            $item_canteen_list= $this->session->userdata('item_id'); 
//echo "<pre>";print_r($item_canteen_list);die;
            if(!empty($item_canteen_list))
            { 
               foreach($item_canteen_list as $item_list)
				{
                    $qty = $item_list['qty'];
					$qtys = $qty;
					$data_purchase_topurchase = array(
					"purchase_id"=>$purchase_id,
					'item_id'=>$item_list['iid'],
					'qty'=>$qtys,
					'discount'=>$item_list['discount'],
					//'vat'=>$item_list['vat'],
					'sgst'=>$item_list['sgst'],
					'igst'=>$item_list['igst'],
					'cgst'=>$item_list['cgst'],
					'total_amount'=>$item_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($item_list['exp_date'])),
					'purchase_rate'=>$item_list['purchase_amount'],
					'mrp'=>$item_list['mrp'],
					'batch_no'=>$item_list['batch_no'],
					'bar_code'=>$item_list['bar_code'],
					'hsn_no'=>$item_list['hsn_no'],
					'unit1'=>$item_list['unit1'],
					'unit2'=>$item_list['unit2'],
					'freeunit1'=>$item_list['freeunit1'],
					'freeunit2'=>$item_list['freeunit2'],
					'manuf_date'=>date('Y-m-d',strtotime($item_list['manuf_date'])),
					'conversion'=>$item_list['conversion'],
					'per_pic_rate'=>$item_list['perpic_rate'],
					'created_date'=>date('Y-m-d',strtotime($post['purchase_date']))
					);
					 // print_r($data_purchase_topurchase);die;
					$this->db->insert('hms_canteen_estimate_purchase_to_purchase',$data_purchase_topurchase);
	//echo ($this->db->last_query());die;
					
		
				}

                //die;
            } 
   
			           
		} 
		//echo ($this->db->last_query());die;
		$this->session->unset_userdata('item_id');
		return  $purchase_id;	
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
			$this->db->update('hms_canteen_estimate_purchase');
			
			//update status on stock
			$this->db->where('parent_id',$id);
			$this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('type','1');
            $query_d_pay = $this->db->get('hms_canteen_stock_item');

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
                  	 $this->db->where('type',1);
                    $this->db->update('hms_canteen_stock_item',$stock_data);
                   //echo $this->db->last_query(); exit;
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
			$this->db->update('hms_canteen_estimate_purchase');
			
			//update status on stock
			$this->db->where('parent_id IN('.$branch_ids.')');
			$this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('type','1');
            $query_d_pay = $this->db->get('hms_canteen_item');
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
                  	 $this->db->where('type',1);
                    $this->db->update('hms_canteen_item',$stock_data);
                    //echo $this->db->last_query(); 
                }
            }

    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_canteen_estimate_purchase');
        $result = $query->result(); 
        return $result; 
    } 

   	public function item_list($ids="")
    {

    	$users_data = $this->session->userdata('auth_users');
		
		$this->db->select("hms_canteen_item.*, hms_canteen_stock_category.category, hms_canteen_stock_item_unit.unit, hms_canteen_master_entry.packing, hms_canteen_master_entry.hsn_no, hms_canteen_master_entry.sgst, hms_canteen_master_entry.cgst, hms_canteen_master_entry.igst, hms_canteen_master_entry.discount, hms_canteen_master_entry.expiry_days, hms_canteen_stock_item.price as mrp");  
		if(!empty($ids))
		{
			$this->db->where('hms_canteen_item.id  IN ('.$ids.')'); 
		}
		$this->db->where('hms_canteen_item.is_deleted','0');
        $this->db->join('hms_canteen_stock_category','hms_canteen_stock_category.id=hms_canteen_item.category_id','left');
        $this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id=hms_canteen_item.unit_id','left');
        $this->db->join('hms_canteen_stock_item','hms_canteen_stock_item.parent_id=hms_canteen_item.id','left');
		$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id=hms_canteen_item.product_id','left');
	
		$this->db->group_by('hms_canteen_item.id');  
		$query = $this->db->get('hms_canteen_item');
		$result = $query->result(); 
	//echo $this->db->last_query();die;
		return $result; 
    }

 

    public function item_list_search()
    {

    	$users_data = $this->session->userdata('auth_users'); 
    	$post = $this->input->post();  
		$this->db->select("hms_canteen_item.*, hms_canteen_stock_category.category, hms_canteen_manuf_company.company_name, hms_canteen_stock_item_unit.unit, hms_canteen_master_entry.packing, hms_canteen_master_entry.hsn_no, hms_canteen_master_entry.sgst, hms_canteen_master_entry.cgst, hms_canteen_master_entry.igst, hms_canteen_master_entry.discount, hms_canteen_master_entry.expiry_days, hms_canteen_stock_item.price as mrp,hms_canteen_master_entry.purchase_rate, (sum(hms_canteen_stock_item.debit)-sum(hms_canteen_stock_item.credit)) as stock_qty"); 
        $this->db->join('hms_canteen_stock_category','hms_canteen_stock_category.id=hms_canteen_item.category_id','left');
        $this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id=hms_canteen_item.unit_id','left');
        $this->db->join('hms_canteen_stock_item','hms_canteen_stock_item.parent_id=hms_canteen_item.id','left');
		$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id=hms_canteen_item.product_id','left');
	   // $this->db->select('(sum(debit)-sum(credit)) as stock_qty;
	
		if(!empty($post['item']))
		{
			
			$this->db->where('hms_canteen_item.item LIKE "'.$post['item'].'%"');
			
		}

		if(!empty($post['item_code']))
		{  

			$this->db->where('hms_canteen_item.item_code LIKE "'.$post['item_code'].'%"');
			
		}
		if(!empty($post['qty']))
		{  
			$this->db->where('hms_canteen_item.qty LIKE "'.$post['qty'].'%"');
			
		}

		 $this->db->join('hms_canteen_manuf_company','hms_canteen_manuf_company.id = hms_canteen_item.manuf_company','left');
		
		if(!empty($post['manuf_company']))
		{  
			$this->db->where('hms_canteen_manuf_company.company_name LIKE"'.$post['manuf_company'].'%"');

			
		}
	
		if(!empty($post['p_rate']))
		{  
			$this->db->where('hms_canteen_item.price = "'.$post['p_rate'].'"');
			
		}
		if(!empty($post['discount']))
		{  
			//echo 'fsf';die;
			$this->db->where('hms_canteen_item.discount = "'.$post['discount'].'"');
			
		}
		if(!empty($post['hsn_no']))
		{  
			//echo 'fsf';die;
			$this->db->where('hms_canteen_item.hsn_no = "'.$post['hsn_no'].'"');
			
		}
	
		if(!empty($post['sgst']))
		{  
			$this->db->where('hms_canteen_item.sgst = "'.$post['sgst'].'"');
			
		}
		if(!empty($post['cgst']))
		{  
			$this->db->where('hms_canteen_item.cgst = "'.$post['cgst'].'"');
			
		}
		if(!empty($post['igst']))
		{  
			$this->db->where('hms_canteen_item.igst = "'.$post['igst'].'"');
			
		}
	
		if(!empty($post['packing']))
		{  
			$this->db->where('hms_canteen_item.packing LIKE "'.$post['packing'].'%"');
			
		}

		if(!empty($post['bar_code']))
		{  
			
			$this->db->where('hms_canteen_item.bar_code LIKE "'.$post['bar_code'].'%"');
			
		}
		
		$this->db->where('hms_canteen_item.branch_id  IN ('.$users_data['parent_id'].')');
		$this->db->where('hms_canteen_item.is_deleted','0'); 
		$this->db->group_by('hms_canteen_item.id');
		$this->db->from('hms_canteen_item');
        $query = $this->db->get(); 
        $data= $query->result();
      //print_r($data);die;
// echo $this->db->last_query();die;
       return  $query->result();
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

	function get_all_detail_print($ids="",$branch_id="")
	{
		$result_sales=array();
    	//$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_canteen_estimate_purchase.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_canteen_vendors.name as name,hms_canteen_vendors.email as v_email,hms_canteen_vendors.mobile as mobile,hms_canteen_vendors.address as v_address,hms_canteen_vendors.address2 as v_address2,hms_canteen_vendors.address3 as v_address3,hms_users.*,hms_canteen_vendors.vendor_gst"); 
		$this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_estimate_purchase.vendor_id','left'); 
		$this->db->join('hms_users','hms_users.id = hms_canteen_estimate_purchase.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		 
		$this->db->where('hms_canteen_estimate_purchase.is_deleted','0'); 
		$this->db->where('hms_canteen_estimate_purchase.branch_id = "'.$branch_id.'"'); 
		$this->db->where('hms_canteen_estimate_purchase.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['purchase_list']= $this->db->get()->result();

		//echo $this->db->last_query();die;
		
		$this->db->select('hms_canteen_estimate_purchase_to_purchase.*,hms_canteen_estimate_purchase_to_purchase.purchase_rate as p_r,hms_canteen_item.*,hms_canteen_item.*,hms_canteen_estimate_purchase_to_purchase.sgst as m_sgst,hms_canteen_estimate_purchase_to_purchase.igst as m_igst,hms_canteen_estimate_purchase_to_purchase.cgst as m_cgst,hms_canteen_estimate_purchase_to_purchase.discount as m_disc');
		$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_estimate_purchase_to_purchase.item_id'); 
		$this->db->where('hms_canteen_estimate_purchase_to_purchase.purchase_id = "'.$ids.'"');
		//$this->db->where('hms_canteen_estimate_purchase_to_purchase.branch_id = "'.$branch_id.'"'); 
		$this->db->from('hms_canteen_estimate_purchase_to_purchase');
		$result_sales['purchase_list']['item_list']=$this->db->get()->result();
		//echo $this->db->last_query();die;
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
	function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
	{
	$users_data = $this->session->userdata('auth_users'); 
	$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
	$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
	$this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
	$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
	$this->db->where('hms_payment_mode_field_value_acc_section.type',1);
	$this->db->where('hms_payment_mode_field_value_acc_section.section_id',1);
	$this->db->where('hms_payment_mode_field_value_acc_section.branch_id = "'.$users_data['parent_id'].'"');
	$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
	return $query;
	}

	function check_bar_code($bar_code="",$item_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');
		if(!empty($bar_code))
		{
		 $this->db->where('bar_code',$bar_code);	
		}
		$this->db->where('branch_id',$users_data['parent_id']);
		$result= $this->db->get('hms_canteen_item')->result();
        if(!empty($result))
        {
            foreach($result as $res)
            		{
                     $res_new_medicine_array[]= $res->i_id;
            		}
                     $new_array= array_unique($res_new_medicine_array);
                     if(in_array($item_id,$new_array))
                     {
                     	return 1;
                     }
                     else
                     {
                     	return 2;
                     }
        }
        else
        {
        	return 1;
        }

		

  
	}

	/* Estimate data */


	// }


	public function estimate_item($vals)
	{
		$response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
          		$this->db->select('hms_estimate_purchase.*,hms_canteen_vendors.name,hms_canteen_vendors.mobile,hms_canteen_vendors.vendor_gst,hms_canteen_vendors.address,hms_canteen_vendors.email');
          		$this->db->join('hms_canteen_vendors','hms_canteen_vendors.id=hms_estimate_purchase.vendor_id');
		$this->db->from('hms_estimate_purchase'); 

		 $this->db->where('hms_estimate_purchase.purchase_id LIKE "'.$vals.'%"');
		//$this->db->where('hms_estimate_purchase.is_deleted','0');
		$query = $this->db->get(); 

	$result= $query->row_array();

          if(!empty($result))
          { 
            $data = array($result['purchase_id'].'|'.$result['item_code'].'|'.$result['name'].'|'.$result['vendor_gst'].'|'.$result['email'].'|'.$result['mobile'].'|'.$result['address']);
            // foreach($result as $vals)
            // {
            //     $name = $vals->purchase_id.'|'.$vals->item_code;
            //     array_push($data, $name);
            // }

            echo json_encode($data);
          }
          //return $response; 
      }
	}


	public function get_estimate_medicine_by_id($purchase_id)
	{
		$response = '';
      if(!empty($purchase_id))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_estimate_purchase.id,hms_estimate_purchase.total_amount as grand_total,hms_estimate_purchase.discount as total_discount,hms_estimate_purchase.purchase_id,hms_estimate_purchase_to_purchase.item_id,hms_estimate_purchase_to_purchase.qty,hms_estimate_purchase_to_purchase.purchase_rate,hms_estimate_purchase_to_purchase.discount ,hms_estimate_purchase_to_purchase.freeunit1,hms_estimate_purchase_to_purchase.freeunit2,hms_estimate_purchase_to_purchase.unit1,hms_estimate_purchase_to_purchase.unit2,hms_estimate_purchase_to_purchase.bar_code,hms_estimate_purchase_to_purchase.hsn_no,hms_estimate_purchase_to_purchase.mrp,hms_estimate_purchase_to_purchase.total_amount ,hms_estimate_purchase_to_purchase.per_pic_rate,hms_estimate_purchase_to_purchase.manuf_date,hms_estimate_purchase_to_purchase.expiry_date,hms_estimate_purchase_to_purchase.sgst sgst,hms_estimate_purchase_to_purchase.cgst ,hms_estimate_purchase_to_purchase.igst ,hms_canteen_item.item,hms_canteen_item.item_code,hms_canteen_item.hsn_no,hms_canteen_item.packing,hms_canteen_item.conversion"); 
         $this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id=hms_estimate_purchase.id','left');
          $this->db->join('hms_canteen_item','hms_canteen_item.id=hms_estimate_purchase_to_purchase.item_id','left'); 
          //$this->db->where('hms_estimate_purchase.id',$estimate_id);
		 //$this->db->where('hms_canteen_item.id',$item_id);
          //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
          $this->db->where('hms_estimate_purchase.purchase_id ',$purchase_id);
          $this->db->from('hms_estimate_purchase'); 
         // $this->db->group_by('hms_estimate_purchase.id');
          $query = $this->db->get(); 
          // echo $this->db->last_query(); exit;
          $result = $query->result_array();
        
          //   $data = array();
         $post_iid_arr = array();
      //  echo "<pre>"; print_r($result);die;
            foreach($result as $vals)
           {

        

           	$post_iid_arr[$vals['item_id']] = array('iid'=>$vals['item_id'],'id'=>$vals['item_id'],'unit1'=>$vals['unit1'],'unit2'=>$vals['unit2'],'conversion'=>$vals['conversion'],'perpic_rate'=>$vals['per_pic_rate'],'manuf_date'=>$vals['manuf_date'],'batch_no'=>'','freeunit1'=>$vals['freeunit1'],'freeunit2'=>$vals['freeunit2'],'hsn_no'=>$vals['hsn_no'],'qty'=>$vals['qty'],'freeqty'=>'1', 'exp_date'=>$vals['expiry_date'],'discount'=>$vals['discount'],'mrp'=>$vals['mrp'],'cgst'=>$vals['cgst'],'igst'=>$vals['igst'],'sgst'=>$vals['sgst'],'purchase_amount'=>$vals['purchase_rate'], 'total_amount'=>$vals['total_amount'],'bar_code'=>$vals['bar_code'],'item_name'=>$vals['item_name']); 
            }
            return $post_iid_arr;
          
      } 
	}
	

	public function get_canteen_vendors($item_id)
	{
		 $this->db->select("hms_canteen_estimate_purchase.vendor_id, hms_canteen_estimate_purchase_to_purchase.purchase_id,hms_canteen_estimate_purchase_to_purchase.item_id, hms_canteen_item.item, hms_canteen_vendors.name, hms_canteen_estimate_purchase_to_purchase.purchase_rate, hms_canteen_estimate_purchase.purchase_date"); 

         $this->db->join('hms_canteen_estimate_purchase_to_purchase','hms_canteen_item.id=hms_canteen_estimate_purchase_to_purchase.item_id','left');

          $this->db->join('hms_canteen_estimate_purchase','hms_canteen_estimate_purchase.id=hms_canteen_estimate_purchase_to_purchase.purchase_id','left'); 
        
          $this->db->join('hms_canteen_vendors','hms_canteen_estimate_purchase.vendor_id=hms_canteen_vendors.id','left');

          $this->db->where('hms_canteen_item.id ',$item_id);

          $this->db->from('hms_canteen_item'); 
          $query = $this->db->get(); 
          $result = $query->result_array();       
          return $result;
	}




     public function check_unique_value($branch_id, $invoice_id, $id='')
    {
    	$this->db->select('hms_canteen_estimate_purchase.*');
		$this->db->from('hms_canteen_estimate_purchase'); 
		$this->db->where('hms_canteen_estimate_purchase.branch_id',$branch_id);
		$this->db->where('hms_canteen_estimate_purchase.id',$invoice_id);
		if(!empty($id))
		$this->db->where('hms_canteen_estimate_purchase.id !=',$id);
		$this->db->where('hms_canteen_estimate_purchase.is_deleted','0');
		$query = $this->db->get(); 
		$result=$query->row_array();
		if(!empty($result))
		{
			return 1;
		}
		else{
			return 0;
		}
    }

	/* Estimate data */

} 
?>