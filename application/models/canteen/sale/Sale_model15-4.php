<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sale_model extends CI_Model 
{
	var $table = 'hms_canteen_sale';
	 

	var $column = array('hms_canteen_sale.id','hms_customers.customer_name','hms_canteen_sale.sale_no','hms_canteen_sale.patient_id','hms_canteen_sale.id','hms_canteen_sale.total_amount','hms_canteen_sale.net_amount','hms_canteen_sale.paid_amount','hms_canteen_sale.balance','hms_canteen_sale.created_date', 'hms_canteen_sale.modified_date');  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('sale_search_item');

		$this->db->select("hms_canteen_sale.*,hms_customers.customer_name ,hms_customers.customer_code, hms_customers.mobile_no as mobile, hms_patient.patient_name ,hms_patient.patient_code, hms_patient.mobile_no as patient_mobile,

		(select count(id) from hms_canteen_sale_to_sale where sale_id = hms_canteen_sale.id) as total_item"); 
		$this->db->join('hms_customers','hms_customers.id = hms_canteen_sale.customer_id','left'); 
	    $this->db->join('hms_patient','hms_patient.id=hms_canteen_sale.patient_id','left'); 
		$this->db->where('hms_canteen_sale.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_canteen_sale.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_canteen_sale.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_canteen_sale.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->from($this->table); 

		/////// Search query start //////////////
		if(isset($search) && !empty($search))
		{
			
             if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_canteen_sale.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_canteen_sale.sale_date <= "'.$end_date.'"');
			}


			if(isset($search['customer_name']) && !empty($search['customer_name']))
			{
              $this->db->where('hms_customers.customer_name',$search['customer_name']);
			}
			
			if(isset($search['patient_name']) && !empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name',$search['patient_name']);
			}
			
			if(isset($search['patient_code']) && !empty($search['patient_code']))
			{
			  $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
			}

			if(isset($search['customer_code']) && !empty($search['customer_code']))
			{
			  $this->db->where('hms_customers.customer_code LIKE "'.$search['customer_code'].'%"');
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_customers.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['patient_id']) && !empty($search['patient_id']))
			{
				$this->db->where('hms_canteen_sale.patient_id', $search['patient_id']);
			}

			if(isset($search['sale_no']) && !empty($search['sale_no']))
			{
				$this->db->where('hms_canteen_sale.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['item_name']) && !empty($search['item_name']))
			{
				$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
				$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
				$this->db->where('hms_canteen_item.item LIKE "'.$search['item_name'].'%"');
			}

			if(isset($search['company']) && !empty($search['company']) && empty($search['item_name']))
			{
				if(empty($search['item_name'])){
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left');
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
				}
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_canteen_item.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['company'].'%"');
			}

			if(isset($search['item_code']) && !empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
			{
				if(empty($search['company']) && empty($search['item_name']))
				{
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.item_code LIKE "'.$search['item_code'].'%"');
			}
			

			if(isset($search['mrp']) && !empty($search['mrp']))
			{
				$this->db->where('hms_canteen_sale.mrp',$search['mrp']);
			}

			if(isset($search['discount']) && !empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
			{
				if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
				{
                      $this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left');

                      $this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_sale_to_sale.item_id','left');

                      //$this->db->where('hms_canteen_sale_to_sale.unit2 LIKE "%'.$search['unit2'].'%"');
				}  
				$this->db->where('hms_canteen_sale_to_sale.discount = "'.$search['discount'].'"');
			}


			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
				{
				$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 

				$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_sale_to_sale.item_id','left');

				//$this->db->where('hms_canteen_sale_to_sale.unit2 LIKE "%'.$search['unit2'].'%"');
			    }
				$this->db->where('hms_canteen_sale_to_sale.batch_no = "'.$search['batch_no'].'"');
			}

			if(isset($search['unit1']) && $search['unit1']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']))
				{
				//$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 

					$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_sale_to_sale.item_id','left');
					//$this->db->where('hms_canteen_sale_to_sale.unit2 LIKE "%'.$search['unit2'].'%"');

					
				}
				$this->db->where('hms_canteen_sale_to_sale.unit1 LIKE "%'.$search['unit1'].'%"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				 if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']))
				{
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left');

					$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_sale_to_sale.item_id','left'); 
			    

				}

				$this->db->where('hms_canteen_sale_to_sale.unit2 LIKE "%'.$search['unit2'].'%"');

				//$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
				
			}

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']))
				{
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_canteen_item.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']))
				{
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']))
				{
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.mrp <="'.$search['mrp_from'].'"');
			}


			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{	
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
                }
				$this->db->where('hms_canteen_sale_to_sale.cgst', $search['cgst']);
			}


			if(isset($search['igst']) && !empty($search['igst']))
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']))
				{	
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_sale_to_sale.item_id','left'); 
                }
				$this->db->where('hms_canteen_sale_to_sale.igst', $search['igst']);
			}


			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['discount']) && empty($search['item_code']) && empty($search['company']) && empty($search['item_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']) && empty($search['igst']))
				{	
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id =hms_canteen_sale_to_sale.item_id','left'); 
                }
				$this->db->where('hms_canteen_sale_to_sale.sgst', $search['sgst']);
			}



			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_canteen_sale.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
			{
				$this->db->where('hms_canteen_sale.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) && $search['balance_to']!="")
			{
				$this->db->where('hms_canteen_sale.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_canteen_sale.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_canteen_sale.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_canteen_sale.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_canteen_sale.bank_name',$search['bank_name']);
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
			$this->db->where('hms_canteen_sale.created_by IN ('.$emp_ids.')');
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
		$search = $this->session->userdata('sale_search_item');
		$this->db->select("hms_canteen_sale.*,hms_customers.customer_name ,hms_customers.customer_code, hms_customers.mobile_no as mobile, hms_patient.patient_name ,hms_patient.patient_code, hms_patient.mobile_no as patient_mobile,

		(select count(id) from hms_canteen_sale_to_sale where sale_id = hms_canteen_sale.id) as total_item"); 
		$this->db->join('hms_customers','hms_customers.id = hms_canteen_sale.customer_id','left'); 
	    $this->db->join('hms_patient','hms_patient.id=hms_canteen_sale.patient_id','left'); 
		$this->db->where('hms_canteen_sale.is_deleted','0'); 
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_canteen_sale.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_canteen_sale.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_canteen_sale.branch_id = "'.$user_data['parent_id'].'"'); 
		
		$this->db->from($this->table); 

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
               if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_canteen_sale.sale_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_canteen_sale.sale_date <= "'.$end_date.'"');
			}


			if(isset($search['customer_name']) && !empty($search['customer_name']))
			{

				$this->db->where('hms_customers.customer_name',$search['customer_name']);
			}
			
			if(isset($search['patient_name']) && !empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name',$search['patient_name']);
			}
			
			if(isset($search['patient_code']) && !empty($search['patient_code']))
			{
			  $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
			}

			if(isset($search['customer_code']) && !empty($search['customer_code']))
			{
			  $this->db->where('hms_customers.customer_code LIKE "'.$search['customer_code'].'%"');
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_customers.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['patient_id']) && !empty($search['patient_id']))
			{
				$this->db->where('hms_canteen_sale.patient_id', $search['patient_id']);
			}

			if(isset($search['sale_no']) && !empty($search['sale_no']))
			{
				$this->db->where('hms_canteen_sale.sale_no LIKE "'.$search['sale_no'].'%"');
			}

			if(isset($search['item_name']) && !empty($search['item_name']))
			{
				$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
				$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
				$this->db->where('hms_canteen_item.item LIKE "'.$search['item_name'].'%"');
			}

			if(isset($search['company']) && !empty($search['company']))
			{
				if(empty($search['item_name'])){
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left');
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
				}
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_canteen_item.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['company'].'%"');
			}

			if(isset($search['item_code']) && !empty($search['item_code']))
			{
				if(empty($search['company']) && empty($search['item_name']))
				{
					$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
					$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
                }
				$this->db->where('hms_canteen_item.item_code LIKE "'.$search['item_code'].'%"');
			}
			

			if(isset($search['mrp']) && !empty($search['mrp']))
			{
				$this->db->where('hms_canteen_sale.mrp',$search['mrp']);
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name']))
				{
                      $this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left');
				}  
				$this->db->where('hms_canteen_sale_to_sale.discount = "'.$search['discount'].'"');
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				// if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
				// } 
				$this->db->where('hms_canteen_sale.cgst', $search['cgst']);
			}
			if(isset($search['igst']) && !empty($search['igst']))
			{
				// if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
				// } 
				$this->db->where('hms_canteen_sale.igst', $search['igst']);
			}
			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				// if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
				// } 
				$this->db->where('hms_canteen_sale.sgst', $search['sgst']);
			}

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']))
				{
				$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
			    }
				$this->db->where('hms_canteen_sale_to_sale.batch_no = "'.$search['batch_no'].'"');
			}

				if(isset($search['unit1']) && $search['unit1']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']))
					{
					//$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
						$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
						$this->db->where('hms_canteen_sale_to_sale.unit1 LIKE "%'.$search['unit1'].'%"');
					}
				}

				if(isset($search['unit2']) && $search['unit2']!="")
				{
					 if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['unit1']))
					{
						$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
				    $this->db->where('hms_canteen_sale_to_sale.unit2 LIKE "%'.$search['unit2'].'%"');

					}
					//$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
					
				}

				if(isset($search['packing']) && $search['packing']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['unit2']))
					{
						$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
						$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
	                }
					//$this->db->where('packing',$search['packing']);
					$this->db->where('hms_canteen_item.packing LIKE "'.$search['packing'].'%"');
				}

				if(isset($search['conversion']) && $search['conversion']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['unit2']) && empty($search['unit1']))
					{
						$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
						$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
	                }
					$this->db->where('hms_canteen_item.conversion LIKE "'.$search['packing'].'%"');
				}

				if(isset($search['mrp_to']) && $search['mrp_to']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['unit1']) && empty($search['unit2']))
					{
						$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
						$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
	                }
					$this->db->where('hms_canteen_item.mrp >="'.$search['mrp_to'].'"');
				}
				if(isset($search['mrp_from']) && $search['mrp_from']!="")
				{
					if(empty($search['item_code']) && empty($search['company']) && empty($search['item_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['unit2']) && empty($search['unit1']))
					{
						$this->db->join('hms_canteen_sale_to_sale','hms_canteen_sale_to_sale.sale_id = hms_canteen_sale.id','left'); 
						$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id','left'); 
	                }
					$this->db->where('hms_canteen_item.mrp <="'.$search['mrp_from'].'"');
				}

				if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
				{
					$this->db->where('hms_canteen_sale.paid_amount >="'.$search['paid_amount_to'].'"');
				}

				if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
				{
					$this->db->where('hms_canteen_sale.paid_amount <="'.$search['paid_amount_from'].'"');
				}

				if(isset($search['balance_to']) && $search['balance_to']!="")
				{
					$this->db->where('hms_canteen_sale.balance >="'.$search['balance_to'].'"');
				}

				if(isset($search['balance_from']) && $search['balance_from']!="")
				{
					$this->db->where('hms_canteen_sale.balance <="'.$search['balance_from'].'"');
				}

				if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
				{
					$this->db->where('hms_canteen_sale.total_amount >="'.$search['total_amount_to'].'"');
				}
				
				if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
				{
					$this->db->where('hms_canteen_sale.total_amount <="'.$search['total_amount_from'].'"');
				}

				if(isset($search['bank_name']) && $search['bank_name']!="")
				{
					$this->db->where('hms_canteen_sale.bank_name',$search['bank_name']);
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
			$this->db->where('hms_canteen_sale.created_by IN ('.$emp_ids.')');
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
		$this->db->select('hms_canteen_sale.*');
		$this->db->from('hms_canteen_sale'); 
		 $this->db->where('hms_canteen_sale.id',$id);
		$this->db->where('hms_canteen_sale.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function get_by_id_expense($id)
	{
		$this->db->select('hms_expenses.*');
		$this->db->from('hms_expenses'); 
		 $this->db->where('hms_expenses.parent_id',$id);
		$this->db->where('hms_expenses.is_deleted','0');
		$this->db->where('hms_expenses.type','17');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	public function get_customer_by_id($id)
	{

		$this->db->select('hms_customers.*');
		$this->db->from('hms_customers'); 
		$this->db->where('hms_customers.id',$id);
		//$this->db->where('hms_customers.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}
	
   public function get_patient_by_id($id)
  {

    $this->db->select('hms_patient.*');
    $this->db->from('hms_patient'); 
    $this->db->where('hms_patient.id',$id);
    $this->db->where('hms_patient.is_deleted','0');
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    return $query->row_array();
  }

	public function get_item_by_sale_id($id="")
	{
		$this->db->select('hms_canteen_sale_to_sale.*');
		$this->db->from('hms_canteen_sale_to_sale'); 
		if(!empty($id))
		{
          $this->db->where('hms_canteen_sale_to_sale.sale_id',$id);
		} 
		$query = $this->db->get()->result(); 
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $item_list)	
		  {
           //print_r($item_list);
		
				$tot_qty_with_rate= $item_list->mrp*$item_list->qty;
				$perpic_rate= 0;

				$cgstToPay = ($tot_qty_with_rate / 100) * $item_list->cgst;
				$igstToPay = ($tot_qty_with_rate / 100) * $item_list->igst;
				$sgstToPay = ($tot_qty_with_rate / 100) * $item_list->sgst;
            	$totalPrice = $tot_qty_with_rate + $cgstToPay+$igstToPay+$sgstToPay;
				$total_discount = ($item_list->discount/100)*$totalPrice;
				$total_amount= $totalPrice-$total_discount;

               $result[$item_list->item_id] = array('iid'=>$item_list->item_id,'hsn_no'=>$item_list->hsn_no,'perpic_rate'=>$perpic_rate,'batch_no'=>$item_list->batch_no,'manuf_date'=>date('d-m-Y',strtotime($item_list->manuf_date)),'mrp'=>$item_list->mrp,'qty'=>$item_list->qty,'exp_date'=>date('d-m-Y',strtotime($item_list->expiry_date)),'discount'=>$item_list->discount,'cgst'=>$item_list->cgst,'igst'=>$item_list->igst,'sgst'=>$item_list->sgst, 'total_amount'=>$item_list->total_amount,'bar_code'=>$item_list->bar_code); 
		  } 
		} 
		
		return $result;
	}


	public function save()
	{ 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
//echo"<pre>"; print_r($_POST);die;

		$data_customer = array( 
				"customer_name"=>$post['name'],
				'branch_id'=>$user_data['parent_id'],
				"mobile_no"=>$post['mobile'],
				"address"=>$post['address'],
				"address2"=>$post['address2'],
				"address3"=>$post['address3'],
				"customer_email"=>$post['email'],
				"remark"=>$post['remarks'],
				"status"=>1
				
			);
			
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
		"address"=>$post['address'],
		"address2"=>$post['address2'],
		"address3"=>$post['address3'],
		"remark"=>$post['remarks'],
		"patient_email"=>$post['email'],
	  );	
			
		if(!empty($post['data_id']) && $post['data_id']>0)
		{  
		    $created_by_id= $this->get_by_id($post['data_id']);
			
		   $purchase_detail= $this->get_by_id($post['data_id']);
		   $customer_data= $this->get_customer_by_id($post['customer_id']);
		   $customer_id_new= $this->get_customer_by_id($purchase_detail['customer_id']);
		   if(count($customer_data)>0)
            {	    
			  $this->db->set('modified_by',$user_data['id']);
			  $this->db->set('modified_date',date('Y-m-d H:i:s'));
			  $this->db->where('id', $post['customer_id']);
			  $this->db->update('hms_customers',$data_customer);
//echo $this->db->last_query();
		   } 
		  
		 $purchase_detail= $this->get_by_id($post['data_id']);
		 $patient_data= $this->get_patient_by_id($post['patient_id']);
		 $patient_id_new= $this->get_patient_by_id($purchase_detail['patient_id']);
		 if(count($patient_data)>0)
          {
			  $this->db->set('modified_by',$user_data['id']);
			  $this->db->set('modified_date',date('Y-m-d H:i:s'));
			  $this->db->where('id', $post['patient_id']);
			  $this->db->update('hms_patient',$data_patient);
	//echo $this->db->last_query();die;		  
          }


           $blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$data = array(
				"customer_id"=>$post['customer_id'],
				'branch_id'=>$user_data['parent_id'],
				'patient_id'=>$post['patient_id'],
				'sale_no'=>$post['sale_no'],
				'sale_date'=>date('Y-m-d',strtotime($post['sale_date'])),
				'sale_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['sale_time'])),
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
			$this->db->update('hms_canteen_sale',$data);
            
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
			$this->db->where('sale_id',$post['data_id']);
            $this->db->delete('hms_canteen_sale_to_sale');

            $this->db->where('parent_id',$post['data_id']);
            $this->db->delete('hms_expenses');
            
			
			if(!empty($post['patient_id'])) {
	          $customer_id = $post['patient_id'];
			}
			if(!empty($post['customer_id'])){
			  $customer_id = $post['customer_id'];
			}
            $data_expenses= array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>17,
					'vouchar_no'=>generate_unique_id(19),
					'parent_id'=>$post['data_id'],
                    'expenses_date'=>date('Y-m-d',strtotime($post['sale_date'])),
                    'paid_to_id'=>$customer_id,
                    'paid_amount'=>$post['pay_amount'],
                    'payment_mode'=>$post['payment_mode'],
                   // 'cheque_no'=>$cheque_no,
                    //'branch_name'=>$bank_name,
                    //'cheque_date'=>date('Y-m-d',strtotime($post['sale_date'])),
                    //'transaction_no'=>$transaction_no,
                    'modified_date'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$user_data['id'],
                    'created_by'=>$user_data['id'],
                    'created_date'=>date('Y-m-d H:i:s')
					);
				$this->db->insert('hms_expenses',$data_expenses);
//echo $this->db->last_query();die;
              $sale_id= $post['data_id'];
              

            $this->db->where(array('parent_id'=>$post['data_id'],'type'=>1));
            $this->db->delete('hms_canteen_item');

            $this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','15');
            $this->db->where('customer_id',$post['customer_id']);
            $this->db->delete('hms_payment'); 

            if(!empty($item_canteen_list))
            { 
               foreach($item_canteen_list as $item_list)
				{
					
//	echo"<pre>"; print_r($item_list);die;
	
					$qty = $item_list['qty'];
					$qtys = $qty;  
					$data_sale_topurchase = array(
					"sale_id"=>$post['data_id'],
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
					'created_date'=>date('Y-m-d',strtotime($post['sale_date']))
					);
					$this->db->insert('hms_canteen_sale_to_sale',$data_sale_topurchase);
//echo ($this->db->last_query());die;			
                  $data_new_stock=array("branch_id"=>$user_data['parent_id'],
				    	"parent_id"=>$post['data_id'],
				    	"item_id"=>$item_list['iid'],
						'item_name'=>'',
						'product_id'=>$item_list['item_id'],
						'type'=>5,
				    	"credit"=>$qtys,
				    	"debit"=>0,
						'qty'=>$item_list['qty'],
						'item_code'=>'',
						'manuf_company'=>'',
						'unit_id'=>0,
						'cat_type_id'=>0,
						'per_pic_price'=>$item_list['mrp'],
                        "price"=>$item_list['mrp'],
						'total_amount'=>$item_list['total_amount'],
						'status'=>1,
				    	"created_by"=>$created_by_id['created_by'],//$user_data['id'],
				    	"created_date"=>date('Y-m-d',strtotime($post['sale_date'])),
				    	);
					 $this->db->insert('hms_canteen_stock_item',$data_new_stock);
//echo $this->db->last_query();die;
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
					$this->db->set('created_by',$created_by_id['created_by']);
					$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($created_by_id['created_by'])));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

					}
			}

			/*add sales banlk detail*/	

					$payment_data = array(
								'parent_id'=>$post['data_id'],
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'15',
								'customer_id'=>$post['customer_id'],
								'patient_id'=>$post['patient_id'],
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
								'created_date'=>date('Y-m-d',strtotime($post['sale_date'])),
								'created_by'=>$created_by_id['created_by'],//$user_data['id']
            	             );
             $this->db->insert('hms_payment',$payment_data);

             /*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>1,'section_id'=>4));
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
             //echo $this->db->last_query();die;
            
		}
		 else
		{
			$customer_data= $this->get_customer_by_id($post['customer_id']);  
			$customer_code=generate_unique_id(61);
		    $sale_no = generate_unique_id(67);
			$patient_data= $this->get_patient_by_id($post['patient_id']);	
		
			if(count($customer_data)>0){
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$post['customer_id']);
			$this->db->update('hms_customers',$data_customer);
			$customer_id= $post['customer_id'];
//echo ($this->db->last_query());
			}
		     else if(count($patient_data)>0)
            {
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['patient_id']);
            $this->db->update('hms_patient',$data_patient);
            $patient_id= $post['patient_id'];
//echo ($this->db->last_query()); die;
//echo"<pre>"; print_r($patient_data);die;	
			} 
			 else 
			{
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('customer_code',$customer_code);
			$this->db->insert('hms_customers',$data_customer); 
			$customer_id= $this->db->insert_id();
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
            $blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$data = array(
				"customer_id"=>$customer_id,
				'branch_id'=>$user_data['parent_id'],
				'patient_id'=>$patient_id,
				'sale_no'=>$sale_no,
				'sale_date'=>date('Y-m-d',strtotime($post['sale_date'])),
				'sale_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['sale_time'])),
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
			$this->db->insert('hms_canteen_sale',$data);
//echo ($this->db->last_query());die;
			$sale_id= $this->db->insert_id();
			/*add sales banlk detail*/

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
            								'parent_id'=>$sale_id,
            								'ip_address'=>$_SERVER['REMOTE_ADDR']
            								);
            			$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

            }
			/*add sales banlk detail*/


            $item_canteen_list= $this->session->userdata('item_id'); 
//echo "<pre>";print_r($item_canteen_list);die;
            if(!empty($item_canteen_list))
            { 
               foreach($item_canteen_list as $item_list)
				{
                    $qty = $item_list['qty'];
					$qtys = $qty;
					$data_sale_topurchase = array(
					"sale_id"=>$sale_id,
					'item_id'=>$item_list['iid'],
					'qty'=>$qtys,
					'discount'=>$item_list['discount'],
					//'vat'=>$item_list['vat'],
					'sgst'=>$item_list['sgst'],
					'igst'=>$item_list['igst'],
					'cgst'=>$item_list['cgst'],
					'total_amount'=>$item_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($item_list['exp_date'])),
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
					'created_date'=>date('Y-m-d',strtotime($post['sale_date']))
					);
					 // print_r($data_sale_topurchase);die;
					$this->db->insert('hms_canteen_sale_to_sale',$data_sale_topurchase);
	//echo ($this->db->last_query());die;
					
			$data_new_stock=array("branch_id"=>$user_data['parent_id'],
				    	"parent_id"=>$sale_id,
				    	"item_id"=>$item_list['item_id'],
						'item_name'=>'',
						'product_id'=>$item_list['item_id'],
						'type'=>5,
				    	"credit"=>$qtys,
				    	"debit"=>0,
						'qty'=>$item_list['qty'],
						'item_code'=>'',
						'manuf_company'=>'',
						'unit_id'=>0,
						'cat_type_id'=>0,
                        "price"=>$item_list['mrp'],
						'per_pic_price'=>$item_list['mrp'],
						'total_amount'=>$item_list['total_amount'],
						'status'=>1,
				    	"created_by"=>$user_data['id'],
				    	"created_date"=>date('Y-m-d',strtotime($post['sale_date'])),
				    	);

					 $this->db->insert('hms_canteen_stock_item',$data_new_stock);
//echo $this->db->last_query();die;
				}

                //die;
            } 
           
           /* $payment_mode='';
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
			
			if(!empty($post['patient_id'])) {
	          $customer_id = $post['patient_id'];
			}
			if(!empty($post['customer_id'])){
			  $customer_id = $post['customer_id'];
			}

            
            $data_expenses= array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>17,
					'vouchar_no'=>generate_unique_id(19),
					'parent_id'=>$sale_id,
                    'expenses_date'=>date('Y-m-d',strtotime($post['sale_date'])),
                    'paid_to_id'=>$customer_id,
                    'paid_amount'=>$post['pay_amount'],
                    'payment_mode'=>$post['payment_mode'],
                    //'branch_name'=>$bank_name,
                    //'cheque_no'=>$cheque_no,
                    //'cheque_date'=>date('Y-m-d',strtotime($post['sale_date'])),
                    //'transaction_no'=>$transaction_no,
                    'created_date'=>date('Y-m-d H:i:s'),
                    'created_by'=>$user_data['id'],
					);
           //print_r($post);die;
				$this->db->insert('hms_expenses',$data_expenses);
//echo $this->db->last_query();die;
				/*add sales banlk detail*/	
				for($i=0;$i<$counter_name;$i++) 
				{
					$data_field_value= array(
										'field_value'=>$post['field_name'][$i],
										'field_id'=>$post['field_id'][$i],
										'type'=>6,
										'section_id'=>1,
										'p_mode_id'=>$post['payment_mode'],
										'branch_id'=>$user_data['parent_id'],
										'parent_id'=>$sale_id,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
										);
					$this->db->set('created_by',$user_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
				/*add sales banlk detail*/	


				$payment_data = array(
								'parent_id'=>$sale_id,
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'15',
								'customer_id'=>$customer_id,
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
								'created_date'=>date('Y-m-d',strtotime($post['sale_date'])),
								'created_by'=>$user_data['id']
            	             );
            $this->db->insert('hms_payment',$payment_data);
            /*add sales banlk detail*/	
             for($i=0;$i<$counter_name;$i++) 
            {
            	$data_field_value= array(
            								'field_value'=>$post['field_name'][$i],
            								'field_id'=>$post['field_id'][$i],
            								'type'=>2,
            								'section_id'=>4,
            								'p_mode_id'=>$post['payment_mode'],
            								'branch_id'=>$user_data['parent_id'],
            								'parent_id'=>$sale_id,
            								'ip_address'=>$_SERVER['REMOTE_ADDR']
            								);
            			$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

            }
			/*add sales banlk detail*/
			           
		} 
		//echo ($this->db->last_query());die;
		$this->session->unset_userdata('item_id');
		return  $sale_id;	
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
			$this->db->update('hms_canteen_sale');
			
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
			$this->db->update('hms_canteen_sale');
			
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
        $query = $this->db->get('hms_canteen_sale');
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
		$this->db->select("hms_canteen_item.*, hms_canteen_stock_category.category, hms_canteen_manuf_company.company_name, hms_canteen_stock_item_unit.unit, hms_canteen_master_entry.packing, hms_canteen_master_entry.hsn_no, hms_canteen_master_entry.sgst, hms_canteen_master_entry.cgst, hms_canteen_master_entry.igst, hms_canteen_master_entry.discount, hms_canteen_master_entry.expiry_days, hms_canteen_stock_item.price as mrp"); 
        $this->db->join('hms_canteen_stock_category','hms_canteen_stock_category.id=hms_canteen_item.category_id','left');
        $this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id=hms_canteen_item.unit_id','left');
        $this->db->join('hms_canteen_stock_item','hms_canteen_stock_item.parent_id=hms_canteen_item.id','left');
		$this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id=hms_canteen_item.product_id','left');
		
		
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
			$this->db->where('hms_canteen_item.qty LIKE "'.$post['item_code'].'%"');
			
		}

		 $this->db->join('hms_canteen_manuf_company','hms_canteen_manuf_company.id = hms_canteen_item.manuf_company','left');
		
		if(!empty($post['manuf_company']))
		{  
			$this->db->where('hms_canteen_manuf_company.company_name LIKE"'.$post['manuf_company'].'%"');

			
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
		$this->db->select("hms_canteen_sale.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_customers.customer_name as name,hms_customers.email as v_email,hms_customers.mobile_no as mobile,hms_customers.address as v_address,hms_customers.address2 as v_address2,hms_customers.address3 as v_address3,hms_users.*,hms_customers.vendor_gst"); 
		$this->db->join('hms_customers','hms_customers.id = hms_canteen_sale.customer_id','left'); 
		$this->db->join('hms_users','hms_users.id = hms_canteen_sale.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		 
		$this->db->where('hms_canteen_sale.is_deleted','0'); 
		$this->db->where('hms_canteen_sale.branch_id = "'.$branch_id.'"'); 
		$this->db->where('hms_canteen_sale.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['purchase_list']= $this->db->get()->result();

		//echo $this->db->last_query();die;
		
		$this->db->select('hms_canteen_sale_to_sale.*,hms_canteen_item.*,hms_canteen_item.*,hms_canteen_sale_to_sale.sgst as m_sgst,hms_canteen_sale_to_sale.igst as m_igst,hms_canteen_sale_to_sale.cgst as m_cgst,hms_canteen_sale_to_sale.discount as m_disc');
		$this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_sale_to_sale.item_id'); 
		$this->db->where('hms_canteen_sale_to_sale.sale_id = "'.$ids.'"');
		//$this->db->where('hms_canteen_sale_to_sale.branch_id = "'.$branch_id.'"'); 
		$this->db->from('hms_canteen_sale_to_sale');
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

	// }



	public function get_canteen_vendors($item_id)
	{
		 $this->db->select("hms_canteen_sale.customer_id, hms_canteen_sale_to_sale.sale_id,hms_canteen_sale_to_sale.item_id, hms_canteen_item.item, hms_customers.name, hms_canteen_sale_to_sale.mrp, hms_canteen_sale.sale_date"); 

         $this->db->join('hms_canteen_sale_to_sale','hms_canteen_item.id=hms_canteen_sale_to_sale.item_id','left');

          $this->db->join('hms_canteen_sale','hms_canteen_sale.id=hms_canteen_sale_to_sale.sale_id','left'); 
        
          $this->db->join('hms_customers','hms_canteen_sale.customer_id=hms_customers.id','left');

          $this->db->where('hms_canteen_item.id ',$item_id);

          $this->db->from('hms_canteen_item'); 
          $query = $this->db->get(); 
          $result = $query->result_array();       
          return $result;
	}




     public function check_unique_value($branch_id, $patient_id, $id='')
    {
    	$this->db->select('hms_canteen_sale.*');
		$this->db->from('hms_canteen_sale'); 
		$this->db->where('hms_canteen_sale.branch_id',$branch_id);
		$this->db->where('hms_canteen_sale.id',$patient_id);
		if(!empty($id))
		$this->db->where('hms_canteen_sale.id !=',$id);
		$this->db->where('hms_canteen_sale.is_deleted','0');
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