<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_estimate_model extends CI_Model 
{
	var $table = 'hms_estimate_purchase';
	 

	var $column = array('hms_estimate_purchase.id','hms_medicine_vendors.name','hms_estimate_purchase.purchase_id','hms_estimate_purchase.invoice_id','hms_estimate_purchase.id','hms_estimate_purchase.total_amount','hms_estimate_purchase.net_amount','hms_estimate_purchase.paid_amount','hms_estimate_purchase.balance','hms_estimate_purchase.created_date', 'hms_estimate_purchase.modified_date');  
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

		$this->db->select("hms_estimate_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.id as ven_id,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_estimate_purchase_to_purchase where purchase_id = hms_estimate_purchase.id) as total_medicine"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_estimate_purchase.vendor_id','left'); 
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		$this->db->where('hms_estimate_purchase.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_estimate_purchase.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_estimate_purchase.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_estimate_purchase.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->from($this->table); 

		/////// Search query start //////////////
		if(isset($search) && !empty($search))
		{
			
             if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_estimate_purchase.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_estimate_purchase.purchase_date <= "'.$end_date.'"');
			}


			if(isset($search['vendor_name']) && !empty($search['vendor_name']))
			{

				$this->db->where('hms_medicine_vendors.name',$search['vendor_name']);
			}

			if(isset($search['vendor_code']) && !empty($search['vendor_code']))

			{
				
			  $this->db->where('hms_medicine_vendors.vendor_id LIKE "'.$search['vendor_code'].'%"');
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_medicine_vendors.mobile LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['invoice_id']) && !empty($search['invoice_id']))
			{
				$this->db->where('hms_estimate_purchase.invoice_id', $search['invoice_id']);
			}

			if(isset($search['purchase_no']) && !empty($search['purchase_no']))
			{
				$this->db->where('hms_estimate_purchase.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) && !empty($search['medicine_company']) && empty($search['medicine_name']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
				}
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
			{
				if(empty($search['medicine_company']) && empty($search['medicine_name']))
				{
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_estimate_purchase.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
				{
                      $this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left');

                      $this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_estimate_purchase_to_purchase.medicine_id','left');

                      //$this->db->where('hms_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');
				}  
				$this->db->where('hms_estimate_purchase_to_purchase.discount = "'.$search['discount'].'"');
			}





			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
				{
				$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 

				$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_estimate_purchase_to_purchase.medicine_id','left');

				//$this->db->where('hms_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');
			    }
				$this->db->where('hms_estimate_purchase_to_purchase.batch_no = "'.$search['batch_no'].'"');
			}

			if(isset($search['unit1']) && $search['unit1']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']))
				{
				//$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 

					$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_estimate_purchase_to_purchase.medicine_id','left');
					//$this->db->where('hms_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

					
				}
				$this->db->where('hms_estimate_purchase_to_purchase.unit1 LIKE "%'.$search['unit1'].'%"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				 if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']))
				{
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left');

					$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_estimate_purchase_to_purchase.medicine_id','left'); 
			    

				}

				$this->db->where('hms_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

				//$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
				
			}

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']))
				{
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']))
				{
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']))
				{
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}


			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{	
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_estimate_purchase_to_purchase.cgst', $search['cgst']);
			}


			if(isset($search['igst']) && !empty($search['igst']))
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']))
				{	
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_estimate_purchase_to_purchase.igst', $search['igst']);
			}


			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']) && empty($search['igst']))
				{	
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_estimate_purchase_to_purchase.sgst', $search['sgst']);
			}



			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_estimate_purchase.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
			{
				$this->db->where('hms_estimate_purchase.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) && $search['balance_to']!="")
			{
				$this->db->where('hms_estimate_purchase.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_estimate_purchase.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_estimate_purchase.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_estimate_purchase.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_estimate_purchase.bank_name',$search['bank_name']);
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
			$this->db->where('hms_estimate_purchase.created_by IN ('.$emp_ids.')');
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
		$search = $this->session->userdata('purchase_search');
		$this->db->select("hms_estimate_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_estimate_purchase_to_purchase where purchase_id = hms_estimate_purchase.id) as total_medicine"); 
		
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_estimate_purchase.vendor_id','left'); 

		
		 //$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company');
		$this->db->where('hms_estimate_purchase.is_deleted','0'); 
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_estimate_purchase.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_estimate_purchase.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_estimate_purchase.branch_id = "'.$user_data['parent_id'].'"'); 
		
		$this->db->from($this->table); 

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
               if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_estimate_purchase.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_estimate_purchase.purchase_date <= "'.$end_date.'"');
			}


			if(isset($search['vendor_name']) && !empty($search['vendor_name']))
			{

				$this->db->where('hms_medicine_vendors.name',$search['vendor_name']);
			}

			if(isset($search['vendor_code']) && !empty($search['vendor_code']))

			{
				
			  $this->db->where('hms_medicine_vendors.vendor_id LIKE "'.$search['vendor_code'].'%"');
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_medicine_vendors.mobile LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['invoice_id']) && !empty($search['invoice_id']))
			{
				$this->db->where('hms_estimate_purchase.invoice_id', $search['invoice_id']);
			}

			if(isset($search['purchase_no']) && !empty($search['purchase_no']))
			{
				$this->db->where('hms_estimate_purchase.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) && !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
				}
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_company']) && empty($search['medicine_name']))
				{
					$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_estimate_purchase.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
				{
                      $this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left');
				}  
				$this->db->where('hms_estimate_purchase_to_purchase.discount = "'.$search['discount'].'"');
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				// if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
				// } 
				$this->db->where('hms_estimate_purchase.cgst', $search['cgst']);
			}
			if(isset($search['igst']) && !empty($search['igst']))
			{
				// if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
				// } 
				$this->db->where('hms_estimate_purchase.igst', $search['igst']);
			}
			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				// if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
				// } 
				$this->db->where('hms_estimate_purchase.sgst', $search['sgst']);
			}

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']))
				{
				$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
			    }
				$this->db->where('hms_estimate_purchase_to_purchase.batch_no = "'.$search['batch_no'].'"');
			}

				if(isset($search['unit1']) && $search['unit1']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']))
					{
					//$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
						$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
						$this->db->where('hms_estimate_purchase_to_purchase.unit1 LIKE "%'.$search['unit1'].'%"');
					}
				}

				if(isset($search['unit2']) && $search['unit2']!="")
				{
					 if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['unit1']))
					{
						$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
				    $this->db->where('hms_estimate_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

					}
					//$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
					
				}

				if(isset($search['packing']) && $search['packing']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['unit2']))
					{
						$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
						$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
	                }
					//$this->db->where('packing',$search['packing']);
					$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
				}

				if(isset($search['conversion']) && $search['conversion']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['unit2']) && empty($search['unit1']))
					{
						$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
						$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
	                }
					$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
				}

				if(isset($search['mrp_to']) && $search['mrp_to']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['unit1']) && empty($search['unit2']))
					{
						$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
						$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
	                }
					$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
				}
				if(isset($search['mrp_from']) && $search['mrp_from']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['unit2']) && empty($search['unit1']))
					{
						$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id = hms_estimate_purchase.id','left'); 
						$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left'); 
	                }
					$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
				}

				if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
				{
					$this->db->where('hms_estimate_purchase.paid_amount >="'.$search['paid_amount_to'].'"');
				}

				if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
				{
					$this->db->where('hms_estimate_purchase.paid_amount <="'.$search['paid_amount_from'].'"');
				}

				if(isset($search['balance_to']) && $search['balance_to']!="")
				{
					$this->db->where('hms_estimate_purchase.balance >="'.$search['balance_to'].'"');
				}

				if(isset($search['balance_from']) && $search['balance_from']!="")
				{
					$this->db->where('hms_estimate_purchase.balance <="'.$search['balance_from'].'"');
				}

				if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
				{
					$this->db->where('hms_estimate_purchase.total_amount >="'.$search['total_amount_to'].'"');
				}
				
				if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
				{
					$this->db->where('hms_estimate_purchase.total_amount <="'.$search['total_amount_from'].'"');
				}

				if(isset($search['bank_name']) && $search['bank_name']!="")
				{
					$this->db->where('hms_estimate_purchase.bank_name',$search['bank_name']);
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
			$this->db->where('hms_estimate_purchase.created_by IN ('.$emp_ids.')');
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
		$this->db->select('hms_estimate_purchase.*');
		$this->db->from('hms_estimate_purchase'); 
		 $this->db->where('hms_estimate_purchase.id',$id);
		$this->db->where('hms_estimate_purchase.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function get_by_id_expense($id)
	{
		$this->db->select('hms_expenses.*');
		$this->db->from('hms_expenses'); 
		 $this->db->where('hms_expenses.parent_id',$id);
		$this->db->where('hms_expenses.is_deleted','0');
		$this->db->where('hms_expenses.type','2');
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
		$this->db->select('hms_estimate_purchase_to_purchase.*');
		$this->db->from('hms_estimate_purchase_to_purchase'); 
		if(!empty($id))
		{
          $this->db->where('hms_estimate_purchase_to_purchase.purchase_id',$id);
		} 
		$query = $this->db->get()->result(); 
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $medicine)	
		  {
           //print_r($medicine);
			/*$tot_qty_with_rate= $medicine->per_pic_price*1;
			$vatToPay = ($tot_qty_with_rate / 100) * $medicine->vat;
			$totalPrice = $tot_qty_with_rate + $vatToPay;
			$total_discount = ($medicine->discount/100)*$totalPrice;
			$total_amount= $totalPrice-$total_discount;*/

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



               $result[$medicine->medicine_id] = array('mid'=>$medicine->medicine_id,'conversion'=>$medicine->conversion,'freeunit1'=>$medicine->freeunit1,'freeunit2'=>$medicine->freeunit2,'unit1'=>$medicine->unit1,'unit2'=>$medicine->unit2,'hsn_no'=>$medicine->hsn_no,'perpic_rate'=>$perpic_rate,'batch_no'=>$medicine->batch_no,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'mrp'=>$medicine->mrp,'qty'=>$medicine->qty,'freeqty'=>$medicine->freeqty,'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'discount'=>$medicine->discount,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'purchase_amount'=>$medicine->purchase_rate, 'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code); 
		  } 
		} 
		
		return $result;
	}


	public function save()
	{ 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		// print_r($post);die;
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
			$this->db->set('vendor_type',1);
			$this->db->where('id', $post['vendor_id']);
			$this->db->update('hms_medicine_vendors',$data_vendor);

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


           $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount'])-$post['discount_amount'];
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
			$this->db->update('hms_estimate_purchase',$data);
            
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

			$sess_medicine_list= $this->session->userdata('medicine_id');
			$created_by= $this->get_by_id_expense($post['data_id']);
			$this->db->where('purchase_id',$post['data_id']);
            $this->db->delete('hms_estimate_purchase_to_purchase');

    //         $this->db->where('parent_id',$post['data_id']);
    //         $this->db->delete('hms_expenses');
            
            
    //         $data_expenses= array(
				// 	'branch_id'=>$user_data['parent_id'],
				// 	'type'=>2,
				// 	'vouchar_no'=>generate_unique_id(19),
				// 	'parent_id'=>$post['data_id'],
    //                 'expenses_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
    //                 'paid_to_id'=>$post['vendor_id'],
    //                 'paid_amount'=>$post['pay_amount'],
    //                 'payment_mode'=>$post['payment_mode'],
    //                // 'cheque_no'=>$cheque_no,
    //                 //'branch_name'=>$bank_name,
    //                 //'cheque_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
    //                 //'transaction_no'=>$transaction_no,
    //                 'modified_date'=>date('Y-m-d H:i:s'),
    //                 'modified_by'=>$user_data['id'],
    //                 'created_by'=>$created_by['created_by'],
    //                 'created_date'=>date('Y-m-d H:i:s')
				// 	);
				// $this->db->insert('hms_expenses',$data_expenses);

              $purchase_id= $post['data_id'];
              

            $this->db->where(array('parent_id'=>$post['data_id'],'type'=>1));
            $this->db->delete('hms_medicine_stock');

            $this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','3');
            $this->db->where('vendor_id',$post['vendor_id']);
            $this->db->delete('hms_payment'); 

            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{
					$qty = ($medicine_list['conversion']*$medicine_list['unit1'])+$medicine_list['unit2'];
					$free_qty = ($medicine_list['conversion']*$medicine_list['freeunit1'])+$medicine_list['freeunit2'];
					$qtys = $qty+$free_qty; 
					$data_purchase_topurchase = array(
					"purchase_id"=>$post['data_id'],
					'medicine_id'=>$medicine_list['mid'],
					'qty'=>$qtys,
					'freeqty'=>$medicine_list['freeqty'],
					'discount'=>$medicine_list['discount'],
					//'vat'=>$medicine_list['vat'],
					'sgst'=>$medicine_list['sgst'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'hsn_no'=>$medicine_list['hsn_no'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'purchase_rate'=>$medicine_list['purchase_amount'],
					'mrp'=>$medicine_list['mrp'],
					'batch_no'=>$medicine_list['batch_no'],
					'bar_code'=>$medicine_list['bar_code'],
					'unit1'=>$medicine_list['unit1'],
					'unit2'=>$medicine_list['unit2'],
					'freeunit1'=>$medicine_list['freeunit1'],
					'freeunit2'=>$medicine_list['freeunit2'],
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'conversion'=>$medicine_list['conversion'],
					'per_pic_rate'=>$medicine_list['perpic_rate']
					);
					$this->db->insert('hms_estimate_purchase_to_purchase',$data_purchase_topurchase);
				//echo $this->db->last_query();
      //             $data_new_stock=array("branch_id"=>$user_data['parent_id'],
				  //   	"type"=>1,
				  //   	"parent_id"=>$post['data_id'],
				  //   	"m_id"=>$medicine_list['mid'],
				  //   	"credit"=>0,
				  //   	"debit"=>$qtys,
				  //   	"conversion"=>$medicine_list['conversion'],
				  //   	"batch_no"=>$medicine_list['batch_no'],
				  //   	"purchase_rate"=>$medicine_list['purchase_amount'],
				  //   	"mrp"=>$medicine_list['mrp'],
				  //   	"discount"=>$medicine_list['discount'],
				  //   	//"vat"=>$medicine_list['vat'],
						// 'sgst'=>$medicine_list['sgst'],
						// 'hsn_no'=>$medicine_list['hsn_no'],
						// 'bar_code'=>$medicine_list['bar_code'],
						// 'igst'=>$medicine_list['igst'],
						// 'cgst'=>$medicine_list['cgst'],
				  //   	"total_amount"=>$medicine_list['total_amount'],
				  //   	"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
				  //   	'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
				  //   	'per_pic_rate'=>($medicine_list['mrp']/$medicine_list['conversion']),
				  //   	"created_by"=>$created_by_id['created_by'],//$user_data['id'],
				  //   	"created_date"=>date('Y-m-d',strtotime($post['purchase_date'])),
				  //   	);
					 // $this->db->insert('hms_medicine_stock',$data_new_stock);
                     //echo $this->db->last_query();
				}
				//die;

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

					// $payment_data = array(
					// 			'parent_id'=>$post['data_id'],
					// 			'branch_id'=>$user_data['parent_id'],
					// 			'section_id'=>'3',
					// 			'vendor_id'=>$post['vendor_id'],
					// 			'patient_id'=>0,
					// 			'total_amount'=>str_replace(',', '', $post['total_amount']),
					// 			'discount_amount'=>$post['discount_amount'],
					// 			'net_amount'=>str_replace(',', '', $post['net_amount']),
					// 			'credit'=>str_replace(',', '', $post['net_amount']),
					// 			'debit'=>$post['pay_amount'],
					// 			'pay_mode'=>$post['payment_mode'],
					// 			//'bank_name'=>$bank_name,
					// 		//'card_no'=>$card_no,
					// 			//'cheque_no'=>$cheque_no,
					// 			//'cheque_date'=>$payment_date,
					// 			'balance'=>$blance,
					// 			'paid_amount'=>$post['pay_amount'],
					// 			//'transection_no'=>$transaction_no,
					// 			'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
					// 			'created_by'=>$created_by_id['created_by'],//$user_data['id']
     //        	             );
     //         $this->db->insert('hms_payment',$payment_data);

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
		else{
			
			$vendor_data= $this->get_vendor_by_id($post['vendor_id']);
			$vendor_code=generate_unique_id(11);
		    $purchase_no = generate_unique_id(13);
			if(count($vendor_data)>0){
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->set('vendor_type',1);
			$this->db->where('id',$post['vendor_id']);
			$this->db->update('hms_medicine_vendors',$data_vendor);
			$vendor_id= $post['vendor_id'];
			}else{
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('vendor_id',$vendor_code);
			$this->db->set('vendor_type',1);
			$this->db->insert('hms_medicine_vendors',$data_vendor); 
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
            $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount'])-$post['discount_amount'];
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
			$this->db->insert('hms_estimate_purchase',$data);

			$purchase_id= $this->db->insert_id();
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
            								'parent_id'=>$purchase_id,
            								'ip_address'=>$_SERVER['REMOTE_ADDR']
            								);
            			$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

            }
			/*add sales banlk detail*/


            $sess_medicine_list= $this->session->userdata('medicine_id'); 
            if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{

                    $qty = ($medicine_list['conversion']*$medicine_list['unit1'])+$medicine_list['unit2'];
					$free_qty = ($medicine_list['conversion']*$medicine_list['freeunit1'])+$medicine_list['freeunit2'];
					$qtys = $qty+$free_qty; 
					
					$data_purchase_topurchase = array(
					"purchase_id"=>$purchase_id,
					'medicine_id'=>$medicine_list['mid'],
					'qty'=>$qtys,
					'discount'=>$medicine_list['discount'],
					//'vat'=>$medicine_list['vat'],
					'sgst'=>$medicine_list['sgst'],
					'igst'=>$medicine_list['igst'],
					'cgst'=>$medicine_list['cgst'],
					'total_amount'=>$medicine_list['total_amount'],
					'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
					'purchase_rate'=>$medicine_list['purchase_amount'],
					'mrp'=>$medicine_list['mrp'],
					'batch_no'=>$medicine_list['batch_no'],
					'bar_code'=>$medicine_list['bar_code'],
					'hsn_no'=>$medicine_list['hsn_no'],
					'unit1'=>$medicine_list['unit1'],
					'unit2'=>$medicine_list['unit2'],
					'freeunit1'=>$medicine_list['freeunit1'],
					'freeunit2'=>$medicine_list['freeunit2'],
					'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
					'conversion'=>$medicine_list['conversion'],
					'per_pic_rate'=>$medicine_list['perpic_rate']
					);
					$this->db->insert('hms_estimate_purchase_to_purchase',$data_purchase_topurchase);
					
				  //   $data_new_stock=array("branch_id"=>$user_data['parent_id'],
				  //   	"type"=>1,
				  //   	"parent_id"=>$purchase_id,
				  //   	"m_id"=>$medicine_list['mid'],
				  //   	"credit"=>0,
				  //   	"debit"=>$qtys,
				  //   	"batch_no"=>$medicine_list['batch_no'],
				  //   	"conversion"=>$medicine_list['conversion'],
				  //   	"purchase_rate"=>$medicine_list['purchase_amount'],
				  //   	"mrp"=>$medicine_list['mrp'],
				  //   	"discount"=>$medicine_list['discount'],
				  //   	//"vat"=>$medicine_list['vat'],
				  //   	'hsn_no'=>$medicine_list['hsn_no'],
				  //   	'bar_code'=>$medicine_list['bar_code'],
						// 'sgst'=>$medicine_list['sgst'],
						// 'igst'=>$medicine_list['igst'],
						// 'cgst'=>$medicine_list['cgst'],
				  //   	"total_amount"=>$medicine_list['total_amount'],
				  //   	"expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
				  //   	'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
				  //   	'per_pic_rate'=>($medicine_list['mrp']/$medicine_list['conversion']),
				  //   	"created_by"=>$user_data['id'],
				  //   	"created_date"=>date('Y-m-d',strtotime($post['purchase_date'])),
				  //   	);
					 // $this->db->insert('hms_medicine_stock',$data_new_stock);
					//echo $this->db->last_query();
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
            
    //         $data_expenses= array(
				// 	'branch_id'=>$user_data['parent_id'],
				// 	'type'=>2,
				// 	'vouchar_no'=>generate_unique_id(19),
				// 	'parent_id'=>$purchase_id,
    //                 'expenses_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
    //                 'paid_to_id'=>$vendor_id,
    //                 'paid_amount'=>$post['pay_amount'],
    //                 'payment_mode'=>$post['payment_mode'],
    //                 //'branch_name'=>$bank_name,
    //                 //'cheque_no'=>$cheque_no,
    //                 //'cheque_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
    //                 //'transaction_no'=>$transaction_no,
    //                 'created_date'=>date('Y-m-d H:i:s'),
    //                 'created_by'=>$user_data['id'],
				// 	);
    //        //print_r($post);die;
				// $this->db->insert('hms_expenses',$data_expenses);
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
										'parent_id'=>$purchase_id,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
										);
					$this->db->set('created_by',$user_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
				/*add sales banlk detail*/	


				// $payment_data = array(
				// 				'parent_id'=>$purchase_id,
				// 				'branch_id'=>$user_data['parent_id'],
				// 				'section_id'=>'3',
				// 				'vendor_id'=>$vendor_id,
				// 				'total_amount'=>str_replace(',', '', $post['total_amount']),
				// 				'discount_amount'=>$post['discount_amount'],
				// 				'net_amount'=>str_replace(',', '', $post['net_amount']),
				// 				'credit'=>str_replace(',', '', $post['net_amount']),
				// 				'debit'=>$post['pay_amount'],
				// 				'pay_mode'=>$post['payment_mode'],
				// 				//'bank_name'=>$bank_name,
				// 				//'card_no'=>$card_no,
				// 				//'cheque_no'=>$cheque_no,
				// 				//'cheque_date'=>$payment_date,
				// 				'balance'=>$blance,
				// 				'paid_amount'=>$post['pay_amount'],
				// 				//'transection_no'=>$transaction_no,
				// 				'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
				// 				'created_by'=>$user_data['id']
    //         	             );
    //         $this->db->insert('hms_payment',$payment_data);
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
            								'parent_id'=>$purchase_id,
            								'ip_address'=>$_SERVER['REMOTE_ADDR']
            								);
            			$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

            }
			/*add sales banlk detail*/
			           
		} 
		$this->session->unset_userdata('medicine_id');
		return  $purchase_id;	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			// $user_data = $this->session->userdata('auth_users');
			// $this->db->set('is_deleted',1);
			// $this->db->set('deleted_by',$user_data['id']);
			// $this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->delete('hms_estimate_purchase');
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
			// $user_data = $this->session->userdata('auth_users');
			// $this->db->set('is_deleted',1);
			// $this->db->set('deleted_by',$user_data['id']);
			// $this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->delete('hms_estimate_purchase');
			
			//update status on stock
			// $this->db->where('parent_id IN('.$branch_ids.')');
			// $this->db->where('branch_id',$user_data['parent_id']);
   //          $this->db->where('type','1');
   //          $query_d_pay = $this->db->get('hms_medicine_stock');
   //          $row_d_pay = $query_d_pay->result();

   //          //echo "<pre>"; print_r($row_d_pay); exit;
   //          if(!empty($row_d_pay))
   //          {
   //                foreach($row_d_pay as $row_d)
   //                {
   //                	$stock_data = array(
   //                      'is_deleted'=>1);
                      
   //                	 $this->db->where('id',$row_d->id);
   //                	 $this->db->where('branch_id',$user_data['parent_id']);
   //                	 $this->db->where('parent_id IN('.$branch_ids.')');
   //                	 $this->db->where('type',1);
   //                  $this->db->update('hms_medicine_stock',$stock_data);
   //                  //echo $this->db->last_query(); 
   //              }
   //          }

    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_estimate_purchase');
        $result = $query->result(); 
        return $result; 
    } 

   public function medicine_list($ids="")
    {

    	$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_medicine_entry.*,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2'); 
		if(!empty($ids))
		{
			$this->db->where('hms_medicine_entry.id  IN ('.$ids.')'); 
		}
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		//	$this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.medicine_id = hms_medicine_entry.id','left');
		$this->db->where('hms_medicine_entry.is_deleted',0);  
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
	
		$this->db->group_by('hms_medicine_entry.id');  
		$query = $this->db->get('hms_medicine_entry');
		$result = $query->result(); 
		//echo $this->db->last_query();die;
		return $result; 
    }

 

    public function medicine_list_search()
    {

    	$users_data = $this->session->userdata('auth_users'); 
    	$post = $this->input->post();  
		$this->db->select('hms_medicine_entry.*,hms_medicine_stock.bar_code,hms_medicine_company.id as cm_id,hms_medicine_company.company_name,hms_medicine_entry.created_date as create,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2');  
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
        $this->db->join('hms_medicine_stock','hms_medicine_stock.m_id = hms_medicine_entry.id','left');
		if(!empty($post['medicine_name']))
		{
			
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$post['medicine_name'].'%"');
			
		}

		if(!empty($post['medicine_code']))
		{  

			$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$post['medicine_code'].'%"');
			
		}
		if(!empty($post['mfc_date']))
		{  
			$this->db->where('hms_medicine_entry.created_date LIKE "'.date('Y-m-d',strtotime($post['mfc_date'])).'%"');
			
		}
		/*if(!empty($post['exp_date']))
		{  
			$this->db->where('hms_medicine_entry.packing LIKE "'.$post['exp_date'].'%"');
			
		}*/
		if(!empty($post['unit1']))
		{  
			$this->db->where('hms_medicine_unit.id = "'.$post['unit1'].'"');

			
		}
		 $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
		
		if(!empty($post['medicine_company']))
		{  
			$this->db->where('hms_medicine_company.company_name LIKE"'.$post['medicine_company'].'%"');

			
		}
		if(!empty($post['unit2']))
		{  
			$this->db->where('hms_medicine_unit_2.id ="'.$post['unit2'].'"');
			
		}
		if(!empty($post['mrp']))
		{  
			$this->db->where('hms_medicine_entry.mrp = "'.$post['mrp'].'"');
			
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
		if(!empty($post['hsn_no']))
		{  
			//echo 'fsf';die;
			$this->db->where('hms_medicine_entry.hsn_no = "'.$post['hsn_no'].'"');
			
		}
		/*if(!empty($post['vat']))
		{  
			$this->db->where('hms_medicine_entry.vat = "'.$post['vat'].'"');
			
		}*/
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
		if(!empty($post['packing']))
		{  
			$this->db->where('hms_medicine_entry.packing LIKE "'.$post['packing'].'%"');
			
		}

		if(!empty($post['bar_code']))
		{  
			
			$this->db->where('hms_medicine_stock.bar_code LIKE "'.$post['bar_code'].'%"');
			
		}
		
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')');
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		$this->db->group_by('hms_medicine_entry.id');
		$this->db->from('hms_medicine_entry');
        $query = $this->db->get(); 
        $data= $query->result();
      //  print_r($data);die;
      //echo $this->db->last_query();die;
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
		$this->db->select("hms_estimate_purchase.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_medicine_vendors.name as name,hms_medicine_vendors.email as v_email,hms_medicine_vendors.mobile as mobile,hms_medicine_vendors.address as v_address,hms_medicine_vendors.address2 as v_address2,hms_medicine_vendors.address3 as v_address3,hms_users.*,hms_medicine_vendors.vendor_gst"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_estimate_purchase.vendor_id','left'); 
		$this->db->join('hms_users','hms_users.id = hms_estimate_purchase.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		 
		$this->db->where('hms_estimate_purchase.is_deleted','0'); 
		$this->db->where('hms_estimate_purchase.branch_id = "'.$branch_id.'"'); 
		$this->db->where('hms_estimate_purchase.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['purchase_list']= $this->db->get()->result();

		//echo $this->db->last_query();die;
		
		$this->db->select('hms_estimate_purchase_to_purchase.*,hms_estimate_purchase_to_purchase.purchase_rate as p_r,hms_medicine_entry.*,hms_medicine_entry.*,hms_estimate_purchase_to_purchase.sgst as m_sgst,hms_estimate_purchase_to_purchase.igst as m_igst,hms_estimate_purchase_to_purchase.cgst as m_cgst,hms_estimate_purchase_to_purchase.discount as m_disc');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id'); 
		$this->db->where('hms_estimate_purchase_to_purchase.purchase_id = "'.$ids.'"');
		//$this->db->where('hms_estimate_purchase_to_purchase.branch_id = "'.$branch_id.'"'); 
		$this->db->from('hms_estimate_purchase_to_purchase');
		$result_sales['purchase_list']['medicine_list']=$this->db->get()->result();
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
        if(!empty($result))
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
        else
        {
        	return 1;
        }

		

  
	}

} 
?>