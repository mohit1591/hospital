<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase_model extends CI_Model 
{
	var $table = 'hms_medicine_purchase';
	 

	var $column = array('hms_medicine_purchase.id','hms_medicine_vendors.name','hms_medicine_purchase.purchase_id','hms_medicine_purchase.invoice_id','hms_medicine_purchase.id','hms_medicine_purchase.total_amount','hms_medicine_purchase.net_amount','hms_medicine_purchase.paid_amount','hms_medicine_purchase.balance','hms_medicine_purchase.created_date', 'hms_medicine_purchase.modified_date');  
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

		$this->db->select("hms_medicine_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_medicine_purchase_to_purchase where purchase_id = hms_medicine_purchase.id) as total_medicine"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		$this->db->where('hms_medicine_purchase.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_medicine_purchase.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_medicine_purchase.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_medicine_purchase.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->from($this->table); 

		/////// Search query start //////////////
		if(isset($search) && !empty($search))
		{
			
             if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_medicine_purchase.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_medicine_purchase.purchase_date <= "'.$end_date.'"');
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
				$this->db->where('hms_medicine_purchase.invoice_id', $search['invoice_id']);
			}

			if(isset($search['purchase_no']) && !empty($search['purchase_no']))
			{
				$this->db->where('hms_medicine_purchase.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) && !empty($search['medicine_company']) && empty($search['medicine_name']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
				}
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
			{
				if(empty($search['medicine_company']) && empty($search['medicine_name']))
				{
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_medicine_purchase.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
				{
                      $this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left');

                      $this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_medicine_purchase_to_purchase.medicine_id','left');

                      //$this->db->where('hms_medicine_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');
				}  
				$this->db->where('hms_medicine_purchase_to_purchase.discount = "'.$search['discount'].'"');
			}





			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
				{
				$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 

				$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_medicine_purchase_to_purchase.medicine_id','left');

				//$this->db->where('hms_medicine_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');
			    }
				$this->db->where('hms_medicine_purchase_to_purchase.batch_no = "'.$search['batch_no'].'"');
			}

			if(isset($search['unit1']) && $search['unit1']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']))
				{
				//$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 

					$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_medicine_purchase_to_purchase.medicine_id','left');
					//$this->db->where('hms_medicine_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

					
				}
				$this->db->where('hms_medicine_purchase_to_purchase.unit1 LIKE "%'.$search['unit1'].'%"');
			}

			if(isset($search['unit2']) && $search['unit2']!="")
			{
				 if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']))
				{
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left');

					$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_medicine_purchase_to_purchase.medicine_id','left'); 
			    

				}

				$this->db->where('hms_medicine_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

				//$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
				
			}

			if(isset($search['packing']) && $search['packing']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']))
				{
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				//$this->db->where('packing',$search['packing']);
				$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['conversion']) && $search['conversion']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']))
				{
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
			}

			if(isset($search['mrp_to']) && $search['mrp_to']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']))
				{
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
			}
			if(isset($search['mrp_from']) && $search['mrp_from']!="")
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']))
				{
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
			}


			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
				{	
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_purchase_to_purchase.cgst', $search['cgst']);
			}


			if(isset($search['igst']) && !empty($search['igst']))
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']))
				{	
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_purchase_to_purchase.igst', $search['igst']);
			}


			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				if(empty($search['discount']) && empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']) && empty($search['batch_no']) && empty($search['unit1']) && empty($search['unit2']) && empty($search['packing']) && empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']) && empty($search['igst']))
				{	
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id =hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_purchase_to_purchase.sgst', $search['sgst']);
			}



			if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
			{
				$this->db->where('hms_medicine_purchase.paid_amount >="'.$search['paid_amount_to'].'"');
			}
			if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
			{
				$this->db->where('hms_medicine_purchase.paid_amount <="'.$search['paid_amount_from'].'"');
			}

			if(isset($search['balance_to']) && $search['balance_to']!="")
			{
				$this->db->where('hms_medicine_purchase.balance >="'.$search['balance_to'].'"');
			}
			if(isset($search['balance_from']) && $search['balance_from']!="")
			{
				$this->db->where('hms_medicine_purchase.balance <="'.$search['balance_from'].'"');
			}

			if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
			{
				$this->db->where('hms_medicine_purchase.total_amount >="'.$search['total_amount_to'].'"');
			}
			if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
			{
				$this->db->where('hms_medicine_purchase.total_amount <="'.$search['total_amount_from'].'"');
			}

			if(isset($search['bank_name']) && $search['bank_name']!="")
			{
				$this->db->where('hms_medicine_purchase.bank_name',$search['bank_name']);
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
			$this->db->where('hms_medicine_purchase.created_by IN ('.$emp_ids.')');
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
		/*$user_data = $this->session->userdata('auth_users');
		if($user_data['parent_id']=='64')
		{
		    echo $this->db->last_query();die;
		}*/
		//echo $this->db->last_query();die;
		return $query->result();
	}


	function search_report_data(){

		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('purchase_search');
		$this->db->select("hms_medicine_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_medicine_purchase_to_purchase where purchase_id = hms_medicine_purchase.id) as total_medicine"); 
		
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 

		
		 //$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company');
		$this->db->where('hms_medicine_purchase.is_deleted','0'); 
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_medicine_purchase.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_medicine_purchase.branch_id = "'.$user_data['parent_id'].'"');
		}
		//$this->db->where('hms_medicine_purchase.branch_id = "'.$user_data['parent_id'].'"'); 
		
		$this->db->from($this->table); 

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
               if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_medicine_purchase.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_medicine_purchase.purchase_date <= "'.$end_date.'"');
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
				$this->db->where('hms_medicine_purchase.invoice_id', $search['invoice_id']);
			}

			if(isset($search['purchase_no']) && !empty($search['purchase_no']))
			{
				$this->db->where('hms_medicine_purchase.purchase_id LIKE "'.$search['purchase_no'].'%"');
			}

			if(isset($search['medicine_name']) && !empty($search['medicine_name']))
			{
				$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
				$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
				$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
			}

			if(isset($search['medicine_company']) && !empty($search['medicine_company']))
			{
				if(empty($search['medicine_name'])){
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left');
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
				}
				$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
				$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			}

			if(isset($search['medicine_code']) && !empty($search['medicine_code']))
			{
				if(empty($search['medicine_company']) && empty($search['medicine_name']))
				{
					$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
					$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
                }
				$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
			}
			

			if(isset($search['purchase_rate']) && !empty($search['purchase_rate']))
			{
				$this->db->where('hms_medicine_purchase.purchase_rate',$search['purchase_rate']);
			}

			if(isset($search['discount']) && !empty($search['discount']))
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name']))
				{
                      $this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left');
				}  
				$this->db->where('hms_medicine_purchase_to_purchase.discount = "'.$search['discount'].'"');
			}

			if(isset($search['cgst']) && !empty($search['cgst']))
			{
				// if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
				// } 
				$this->db->where('hms_medicine_purchase.cgst', $search['cgst']);
			}
			if(isset($search['igst']) && !empty($search['igst']))
			{
				// if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
				// } 
				$this->db->where('hms_medicine_purchase.igst', $search['igst']);
			}
			if(isset($search['sgst']) && !empty($search['sgst']))
			{
				// if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount']))
				// {
    //                $this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
				// } 
				$this->db->where('hms_medicine_purchase.sgst', $search['sgst']);
			}

			if(isset($search['batch_no']) && !empty($search['batch_no']))
			{
				if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']))
				{
				$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
			    }
				$this->db->where('hms_medicine_purchase_to_purchase.batch_no = "'.$search['batch_no'].'"');
			}

				if(isset($search['unit1']) && $search['unit1']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']))
					{
					//$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
						$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
						$this->db->where('hms_medicine_purchase_to_purchase.unit1 LIKE "%'.$search['unit1'].'%"');
					}
				}

				if(isset($search['unit2']) && $search['unit2']!="")
				{
					 if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['unit1']))
					{
						$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
				    $this->db->where('hms_medicine_purchase_to_purchase.unit2 LIKE "%'.$search['unit2'].'%"');

					}
					//$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
					
				}

				if(isset($search['packing']) && $search['packing']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['unit2']))
					{
						$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
						$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
	                }
					//$this->db->where('packing',$search['packing']);
					$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
				}

				if(isset($search['conversion']) && $search['conversion']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing']) && empty($search['unit2']) && empty($search['unit1']))
					{
						$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
						$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
	                }
					$this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
				}

				if(isset($search['mrp_to']) && $search['mrp_to']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['unit1']) && empty($search['unit2']))
					{
						$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
						$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
	                }
					$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
				}
				if(isset($search['mrp_from']) && $search['mrp_from']!="")
				{
					if(empty($search['medicine_code']) && empty($search['medicine_company']) && empty($search['medicine_name'])  && empty($search['discount'])  && empty($search['cgst'])  && empty($search['igst']) && empty($search['sgst']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['unit2']) && empty($search['unit1']))
					{
						$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.purchase_id = hms_medicine_purchase.id','left'); 
						$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 
	                }
					$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
				}

				if(isset($search['paid_amount_to']) && $search['paid_amount_to']!="")
				{
					$this->db->where('hms_medicine_purchase.paid_amount >="'.$search['paid_amount_to'].'"');
				}

				if(isset($search['paid_amount_from']) && $search['paid_amount_from']!="")
				{
					$this->db->where('hms_medicine_purchase.paid_amount <="'.$search['paid_amount_from'].'"');
				}

				if(isset($search['balance_to']) && $search['balance_to']!="")
				{
					$this->db->where('hms_medicine_purchase.balance >="'.$search['balance_to'].'"');
				}

				if(isset($search['balance_from']) && $search['balance_from']!="")
				{
					$this->db->where('hms_medicine_purchase.balance <="'.$search['balance_from'].'"');
				}

				if(isset($search['total_amount_to']) && $search['total_amount_to']!="")
				{
					$this->db->where('hms_medicine_purchase.total_amount >="'.$search['total_amount_to'].'"');
				}
				
				if(isset($search['total_amount_from']) && $search['total_amount_from']!="")
				{
					$this->db->where('hms_medicine_purchase.total_amount <="'.$search['total_amount_from'].'"');
				}

				if(isset($search['bank_name']) && $search['bank_name']!="")
				{
					$this->db->where('hms_medicine_purchase.bank_name',$search['bank_name']);
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
			$this->db->where('hms_medicine_purchase.created_by IN ('.$emp_ids.')');
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
	    $user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('purchase_search');
		$this->db->from($this->table);
		$this->db->where('hms_medicine_purchase.is_deleted','0'); 
		//$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		if(isset($search) && !empty($search))
		{
			
               if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_medicine_purchase.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_medicine_purchase.purchase_date <= "'.$end_date.'"');
			}
		}
		$this->db->where('hms_medicine_purchase.branch_id = "'.$user_data['parent_id'].'"');
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select('hms_medicine_purchase.*');
		$this->db->from('hms_medicine_purchase'); 
		 $this->db->where('hms_medicine_purchase.id',$id);
		$this->db->where('hms_medicine_purchase.is_deleted','0');
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
		$this->db->select('hms_medicine_purchase_to_purchase.*, hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, hms_medicine_entry.conversion, hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code, hms_medicine_entry.min_alrt, hms_medicine_entry.packing, hms_medicine_entry.rack_no, hms_medicine_entry.salt');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left');
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		$this->db->from('hms_medicine_purchase_to_purchase'); 
		if(!empty($id))
		{
          $this->db->where('hms_medicine_purchase_to_purchase.purchase_id',$id);
		} 
		$query = $this->db->get()->result(); 
		//echo "<pre>";print_r($query);die;
		$result = [];
		if(!empty($query))
		{
		  $i=1;  
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

                //echo $medicine->manuf_date;
                if($medicine->expiry_date!='1970-01-01')
                {
                   $exp_date=date('d-m-Y',strtotime($medicine->expiry_date)); 
                }
                else
                {
                    $exp_date='';
                }
                if($medicine->manuf_date!='0000-00-00')
                {
                   $manuf_date=date('d-m-Y',strtotime($medicine->manuf_date)); 
                }
                else
                {
                    $manuf_date='';
                }
                //echo $manuf_date;
                $timestamps = time()-$i;
               $result[$medicine->medicine_id.'_'.$timestamps] = array('mid'=>$medicine->medicine_id,'medicine_name'=>$medicine->medicine_name,'medicine_code'=>$medicine->medicine_code,'conversion'=>$medicine->conversion,'freeunit1'=>$medicine->freeunit1,'freeunit2'=>$medicine->freeunit2,'unit1'=>$medicine->unit1,'unit2'=>$medicine->unit2,'hsn_no'=>$medicine->hsn_no,'perpic_rate'=>$perpic_rate,'batch_no'=>$medicine->batch_no,'manuf_date'=>$manuf_date,'mrp'=>$medicine->mrp,'qty'=>$medicine->qty,'freeqty'=>$medicine->freeqty,'exp_date'=>$exp_date,'discount'=>$medicine->discount,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'purchase_amount'=>$medicine->purchase_rate, 'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code);  //'other_data'=>$medicine
               $i++;
		  } 
		} 
		//echo "<pre>";print_r($result);die;
		return $result;
	}


	public function save()
	{   
	    //$sess_medicine_list= $this->session->userdata('medicine_id');
		
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		$sess_medicine_list = $post['medicine_sel_id'];
		//echo "<pre>"; print_r($post);die;
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
		    $purchaseids = $post['data_id'];
		    //echo "<pre>"; print_r($post); exit;
		    
		    $created_by_id= $this->get_by_id($post['data_id']);
		    
           $purchase_detail= $this->get_by_id($post['data_id']);
		   $vendor_id_new= $this->get_vendor_by_id($purchase_detail['vendor_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['vendor_id']);
			$this->db->update('hms_medicine_vendors',$data_vendor);

          

            if(!empty($post['discount_percent']))
            {
                $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount'])-$post['discount_amount'];
            }
            else
            {
                $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount']); 
            }
           
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
				"sgst"=>$post['sgst_amount'],
				"igst"=>$post['igst_amount'],
				"cgst"=>$post['cgst_amount'],
				'mode_payment'=>$post['payment_mode'],
				'balance'=>$blance,
				"remarks"=>$post['remarks'],
				'paid_amount'=>$post['pay_amount']
			); 
			$this->db->where('id',$post['data_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->update('hms_medicine_purchase',$data);
            //echo $this->db->last_query(); exit;
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
				
				$this->db->set('created_by',$created_by_id['created_by']);
				$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($created_by_id['created_date'])));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
			}
			/*add sales banlk detail*/	
            $this->db->select('hms_medicine_purchase_to_purchase.medicine_id, hms_medicine_purchase_to_purchase.batch_no, hms_medicine_purchase_to_purchase.qty'); 
            $this->db->where('hms_medicine_purchase_to_purchase.purchase_id',$post['data_id']); 
            $query = $this->db->get('hms_medicine_purchase_to_purchase');
            $sale_to_medicine_data =  $query->result_array();
            $sale_to_medicine_data_arr = [];
            if(!empty($sale_to_medicine_data)) 
            {
                foreach($sale_to_medicine_data as $s_data)
                {
                    $sale_to_medicine_data_arr[$s_data['medicine_id'].'.'.$s_data['batch_no']] = array('mid_batch'=>$s_data['medicine_id'].'.'.$s_data['batch_no'], 'medicine_id'=>$s_data['medicine_id'], 'batch_no'=>$s_data['batch_no'], 'qty'=>$s_data['qty']);
                }
            }
            
            
			
			$created_by= $this->get_by_id_expense($post['data_id']);
			$this->db->where('purchase_id',$post['data_id']);
            $this->db->delete('hms_medicine_purchase_to_purchase');

            $this->db->where('parent_id',$post['data_id']);
            $this->db->delete('hms_expenses');
            
            
            $data_expenses= array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>2,
					'vouchar_no'=>generate_unique_id(19),
					'parent_id'=>$post['data_id'],
                    'expenses_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
                    'paid_to_id'=>$post['vendor_id'],
                    'vendor_id'=>$post['vendor_id'],
                    'paid_amount'=>$post['pay_amount'],
                    'payment_mode'=>$post['payment_mode'],
                    'modified_date'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$user_data['id'],
                    'created_by'=>$created_by['created_by'],
                    'created_date'=>date('Y-m-d H:i:s')
					);
				$this->db->insert('hms_expenses',$data_expenses);
                $purchase_id= $post['data_id'];
              

            $this->db->where(array('parent_id'=>$post['data_id'],'type'=>1));
            $this->db->delete('hms_medicine_stock');

            $this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','3');
            $this->db->where('vendor_id',$post['vendor_id']);
            $this->db->delete('hms_payment'); 

          /*  if(!empty($sess_medicine_list))
            { 
               foreach($sess_medicine_list as $medicine_list)
				{*/
			$sess_medicine_list = $post['medicine_sel_id'];
			
			 if(!empty($sess_medicine_list))
            { 
                $i=0;
               foreach($sess_medicine_list as $medicine_list)
			   {	
					$qtys=0;
					$qty = ($post['conversion'][$i]*$post['unit1'][$i])+$post['unit2'][$i];
					$free_qty = ($post['conversion'][$i]*$post['freeunit1'][$i])+$post['freeunit2'][$i];
					$qtys = $qty+$free_qty; 
	        $data_purchase_topurchase = array(
					"purchase_id"=>$post['data_id'],
					'medicine_id'=>$medicine_list,
					'qty'=>$qtys,
					'freeqty'=>$post['freeqty'][$i],
					'discount'=>$post['discount'][$i],
					'sgst'=>$post['sgst'][$i],
					'igst'=>$post['igst'][$i],
					'cgst'=>$post['cgst'][$i],
					'hsn_no'=>$post['hsn_no'][$i],
					'total_amount'=>$post['row_total_amount'][$i],
					'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
					'purchase_rate'=>$post['purchase_rate'][$i],
					'mrp'=>$post['mrp'][$i],
					'batch_no'=>$post['batch_no'][$i],
					'bar_code'=>$post['bar_code'][$i],
					'unit1'=>$post['unit1'][$i],
					'unit2'=>$post['unit2'][$i],
					'freeunit1'=>$post['freeunit1'][$i],
					'freeunit2'=>$post['freeunit2'][$i],
					'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
					'conversion'=>$post['conversion'][$i],
					'per_pic_rate'=>$post['perpic_rate'][$i],
					'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
					);
					$this->db->insert('hms_medicine_purchase_to_purchase',$data_purchase_topurchase);
				//echo $this->db->last_query(); die;
			
                $data_new_stock=array("branch_id"=>$user_data['parent_id'],
				    	"type"=>1,
				    	"parent_id"=>$post['data_id'],
				    	"m_id"=>$medicine_list,
				    	"credit"=>0,
				    	"debit"=>$qtys,
				    	"conversion"=>$post['conversion'][$i],
				    	"batch_no"=>$post['batch_no'][$i],
				    	"purchase_rate"=>$post['purchase_rate'][$i],
				    	"mrp"=>$post['mrp'][$i],
				    	"discount"=>$post['discount'][$i],
				    	//"vat"=>$medicine_list['vat'],
						'sgst'=>$post['sgst'][$i],
						'hsn_no'=>$post['hsn_no'][$i],
						'bar_code'=>$post['bar_code'][$i],
						'igst'=>$post['igst'][$i],
						'cgst'=>$post['cgst'][$i],
				    	"total_amount"=>$post['row_total_amount'][$i],
				    	"expiry_date"=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
				    	'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
				    	'per_pic_rate'=>($post['mrp'][$i]/$post['conversion'][$i]),
				    	"created_by"=>$created_by_id['created_by'],//$user_data['id'],
				    	"created_date"=>date('Y-m-d',strtotime($post['purchase_date'])),
				    	);
					 $this->db->insert('hms_medicine_stock',$data_new_stock);
					 
					 
					$med_qty = $qtys;
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
                                
                            }
                            else if($med_qty>$last_qty)
                            {
                                $med_qty = $med_qty-$last_qty;
                                $symbol = '-';
                            }
                            else if($med_qty==$last_qty)
                            {
                                $med_qty = 0;
                            }
                            
                        }
                    }
                    
                   
                    
                    $this->db->select('hms_medicine_batch_stock.id, hms_medicine_entry.discount');
                    $this->db->join('hms_medicine_entry', 'hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id', 'left');
                    $this->db->where('hms_medicine_batch_stock.medicine_id',$medicine_list);
                    $this->db->where('hms_medicine_batch_stock.batch_no',$post['batch_no'][$i]);
                    $query_batch_stock = $this->db->get('hms_medicine_batch_stock'); 
                    $row_batch_stock = $query_batch_stock->result();
					 
					 if(!empty($row_batch_stock))
					 {
					      $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` ".$symbol." '".$med_qty."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no'][$i]."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$medicine_list."' ");
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
                           'sgst'=>$post['sgst'][$i],
                           'cgst'=>$post['cgst'][$i],
                           'igst'=>$post['igst'][$i],
                           'bar_code'=>$post['bar_code'][$i],
                           'conversion'=>$post['conversion'][$i],
                           'total_amount'=>$post['row_total_amount'][$i],
                           'expiry_date'=>date('Y-m-d',strtotime($post['expiry_date'][$i])),
                           'manuf_date'=>date('Y-m-d',strtotime($post['manuf_date'][$i])),
                           'per_pic_rate'=>($post['mrp'][$i]/$post['conversion'][$i]),
                           'created_date'=>date('Y-m-d',strtotime($post['purchase_date']))
					     ); 
					    $this->db->insert("hms_medicine_batch_stock", $batch_stock_data);
					//echo $this->db->last_query();
				}
					 
					 
                
                $i++;     //echo $this->db->last_query();
				}
				//die;

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
								'section_id'=>'3',
								'vendor_id'=>$post['vendor_id'],
								'patient_id'=>0,
								'total_amount'=>str_replace(',', '', $post['total_amount']),
								'discount_amount'=>$post['discount_amount'],
								'net_amount'=>str_replace(',', '', $post['net_amount']),
								'credit'=>str_replace(',', '', $post['net_amount']),
								'debit'=>$post['pay_amount'],
								'pay_mode'=>$post['payment_mode'],
								
								'balance'=>$blance,
								'paid_amount'=>$post['pay_amount'],
								
								'created_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
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
			//$sess_medicine_list= $this->session->userdata('medicine_sel_id'); 
			$sess_medicine_list = $post['medicine_sel_id'];
			//echo count($sess_medicine_list);die;
			$vendor_data= $this->get_vendor_by_id($post['vendor_id']);
			$vendor_code=generate_unique_id(11);
		    $purchase_no = generate_unique_id(13);
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
    			$this->db->set('vendor_id',$vendor_code);
    			$this->db->insert('hms_medicine_vendors',$data_vendor); 
    			$vendor_id= $this->db->insert_id();
		     }
			
			
            //$blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount']); //-$post['discount_amount']
            
            if(!empty($post['discount_percent']))
            {
                $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount'])-$post['discount_amount'];
            }
            else
            {
                $blance= (str_replace(',', '', $post['net_amount'])-$post['pay_amount']); 
            }
            
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
			$this->db->insert('hms_medicine_purchase',$data);
            
			$purchase_id= $this->db->insert_id();
			
			 $purchaseids = $purchase_id;
			 
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
					
                    /*$qtys=0;
                    $qty = ($conversion*$unit1)+$unit2;
					$free_qty = ($conversion*$freeunit1)+$freeunit2;
					$qtys = $qty+$free_qty; */
					
					$qtys=0;
					$qty = ($post['conversion'][$i]*$post['unit1'][$i])+$post['unit2'][$i];
					$free_qty = ($post['conversion'][$i]*$post['freeunit1'][$i])+$post['freeunit2'][$i];
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
					    $manuf_date ='';
					}
					
			$data_purchase_topurchase = array(
					"purchase_id"=>$purchase_id,
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
					//echo "<pre>"; print_r($data_purchase_topurchase); exit;
					$this->db->insert('hms_medicine_purchase_to_purchase',$data_purchase_topurchase);
					
					//echo $this->db->last_query(); die;
					
				    $data_new_stock=array("branch_id"=>$user_data['parent_id'],
				    	"type"=>1,
				    	"parent_id"=>$purchase_id,
				    	"m_id"=>$medicine_list,
				    	"credit"=>0,
				    	"debit"=>$qtys,
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
				    	"created_date"=>date('Y-m-d',strtotime($post['purchase_date'])),
				    	);
					 $this->db->insert('hms_medicine_stock',$data_new_stock);
					 
					 
                    $this->db->select('hms_medicine_batch_stock.id, hms_medicine_entry.discount');
                    $this->db->join('hms_medicine_entry', 'hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id', 'left');
                    $this->db->where('hms_medicine_batch_stock.medicine_id',$medicine_list);
                    $this->db->where('hms_medicine_batch_stock.batch_no',$post['batch_no'][$i]);
                    $query_batch_stock = $this->db->get('hms_medicine_batch_stock'); 
                    $row_batch_stock = $query_batch_stock->result();
					 
					 if(!empty($row_batch_stock))
					 {
					     $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` + '".$qtys."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no'][$i]."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$medicine_list."' ");
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
					   //echo $this->db->last_query();
			    	}
            
              $i++;
                //die;
            } 
           
          
            $data_expenses= array(
					'branch_id'=>$user_data['parent_id'],
					'type'=>2,
					'vouchar_no'=>generate_unique_id(19),
					'parent_id'=>$purchase_id,
                    'expenses_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
                    'paid_to_id'=>$vendor_id,
                    'paid_amount'=>$post['pay_amount'],
                    'payment_mode'=>$post['payment_mode'],
                    'vendor_id'=>$vendor_id,
                    
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
										'parent_id'=>$purchase_id,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
										);
					$this->db->set('created_by',$user_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
				/*add sales banlk detail*/	


				$payment_data = array(
								'parent_id'=>$purchase_id,
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'3',
								'vendor_id'=>$vendor_id,
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
            								'parent_id'=>$purchase_id,
            								'ip_address'=>$_SERVER['REMOTE_ADDR']
            								);
            			$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

            }
			/*add sales banlk detail*/
			           
		} 
	//	$this->session->unset_userdata('medicine_id');
	
	
		
	}
	return  $purchaseids;	
	}

    public function delete($id="")
    {
        $user_data = $this->session->userdata('auth_users');
    	if(!empty($id) && $id>0)
    	{
    	    $this->db->select('hms_medicine_purchase_to_purchase.medicine_id,hms_medicine_purchase_to_purchase.batch_no,hms_medicine_purchase_to_purchase.qty');  
    	    $this->db->where('hms_medicine_purchase_to_purchase.purchase_id',$id);
			//$this->db->where('hms_medicine_purchase_to_purchase.branch_id',$user_data['parent_id']);
            //$this->db->where('type','3');
            $query_purch_pay = $this->db->get('hms_medicine_purchase_to_purchase');

            $row_purchase_med = $query_purch_pay->result();
            $del='';
            if(!empty($row_purchase_med))
            {
                foreach($row_purchase_med as $med_batch)
                {
                    //select 
                    
                    $sql = "SELECT (sum(debit)-sum(credit)) as qty_avalable from hms_medicine_stock stock_to where stock_to.batch_no = '".$med_batch->batch_no."' AND is_deleted=0 AND stock_to.m_id =".$med_batch->medicine_id;
                    
                    $sql_new =  $this->db->query($sql); 
                    //echo $this->db->last_query();die; 

                    $resul_namw = $sql_new->result_array();
                   // qty_avalable
                   $avail = $resul_namw[0]['qty_avalable'];
                    //echo "<pre>"; print_r($resul_namw[0]->qty_avalable); exit;
                    
                    //end of select
                    
                    /*$this->db->where('hms_medicine_stock.m_id',$med_batch->medicine_id);
                    $this->db->where('hms_medicine_stock.batch_no',$med_batch->batch_no);
        			$this->db->where('hms_medicine_stock.branch_id',$user_data['parent_id']);
                    $this->db->where('hms_medicine_stock.type','3');
                    $query_check = $this->db->get('hms_medicine_stock');
        
                    $row_sale_data = $query_check->result();*/
                    //echo $med_batch->qty.'>'.$avail;
                    //echo $this->db->last_query(); exit;
                    if($med_batch->qty > $avail)
                    {
                        $del=2;
                    }
                    
                    
                }
            }
            if(!empty($del))
            {
                return 2;
            }
            
    	    
			
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_medicine_purchase');
			
			//update status on stock
			$this->db->where('parent_id',$id);
			$this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('type','1');
            $query_d_pay = $this->db->get('hms_medicine_stock');

            $row_d_pay = $query_d_pay->result();
            
            // Update batch stock
            $this->db->select('hms_medicine_purchase_to_purchase.medicine_id, hms_medicine_purchase_to_purchase.batch_no, hms_medicine_purchase_to_purchase.qty'); 
            $this->db->where('hms_medicine_purchase_to_purchase.purchase_id',$id); 
            $query = $this->db->get('hms_medicine_purchase_to_purchase');
            $sale_to_medicine_data =  $query->result_array();
            if(!empty($sale_to_medicine_data))
            {
                foreach($sale_to_medicine_data as $sale_to_medicine)
                {
                     $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity`-'".$sale_to_medicine['qty']."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$sale_to_medicine['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$sale_to_medicine['medicine_id']."' "); 
                }
            }
          // End update batch stock

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
                    $this->db->update('hms_medicine_stock',$stock_data);
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
			$this->db->update('hms_medicine_purchase');
			
			//update status on stock
			$this->db->where('parent_id IN('.$branch_ids.')');
			$this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('type','1');
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
                  	 $this->db->where('type',1);
                    $this->db->update('hms_medicine_stock',$stock_data);
                    //echo $this->db->last_query(); 
                }
            }

    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_purchase');
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
		//	$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.medicine_id = hms_medicine_entry.id','left');
		$this->db->where('hms_medicine_entry.is_deleted',0);  
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
	
		$this->db->group_by('hms_medicine_entry.id');  
		$query = $this->db->get('hms_medicine_entry');
		//echo $this->db->last_query();die;
		$result = $query->result(); 
		
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
		$this->db->select("hms_medicine_purchase.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_medicine_vendors.name as name,hms_medicine_vendors.email as v_email,hms_medicine_vendors.mobile as mobile,hms_medicine_vendors.address as v_address,hms_medicine_vendors.address2 as v_address2,hms_medicine_vendors.address3 as v_address3,hms_users.*,hms_medicine_vendors.vendor_gst"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 
		$this->db->join('hms_users','hms_users.id = hms_medicine_purchase.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		 
		$this->db->where('hms_medicine_purchase.is_deleted','0'); 
		$this->db->where('hms_medicine_purchase.branch_id = "'.$branch_id.'"'); 
		$this->db->where('hms_medicine_purchase.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['purchase_list']= $this->db->get()->result();

		//echo $this->db->last_query();die;
		
		$this->db->select('hms_medicine_purchase_to_purchase.*,hms_medicine_purchase_to_purchase.purchase_rate as p_r,hms_medicine_entry.*,hms_medicine_entry.*,hms_medicine_purchase_to_purchase.sgst as m_sgst,hms_medicine_purchase_to_purchase.igst as m_igst,hms_medicine_purchase_to_purchase.cgst as m_cgst,hms_medicine_purchase_to_purchase.discount as m_disc');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id'); 
		$this->db->where('hms_medicine_purchase_to_purchase.purchase_id = "'.$ids.'"');
		//$this->db->where('hms_medicine_purchase_to_purchase.branch_id = "'.$branch_id.'"'); 
		$this->db->from('hms_medicine_purchase_to_purchase');
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
	
	/* Estimate data */


	// }


	public function estimate_medicine($vals)
	{
		$response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
          		$this->db->select('hms_estimate_purchase.*,hms_medicine_vendors.name,hms_medicine_vendors.mobile,hms_medicine_vendors.vendor_gst,hms_medicine_vendors.address,hms_medicine_vendors.email');
          		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_estimate_purchase.vendor_id');
		$this->db->from('hms_estimate_purchase'); 

		 $this->db->where('hms_estimate_purchase.purchase_id LIKE "'.$vals.'%"');
		//$this->db->where('hms_estimate_purchase.is_deleted','0');
		$query = $this->db->get(); 

	$result= $query->row_array();
   //        $this->db->select("hms_estimate_purchase.*,hms_estimate_purchase_to_purchase.medicine_id,hms_estimate_purchase_to_purchase.purchase_rate,hms_estimate_purchase_to_purchase.freeunit1,hms_estimate_purchase_to_purchase.freeunit2,hms_estimate_purchase_to_purchase.unit1,hms_estimate_purchase_to_purchase.unit2,hms_estimate_purchase_to_purchase.bar_code,hms_estimate_purchase_to_purchase.hsn_no,hms_estimate_purchase_to_purchase.mrp,hms_estimate_purchase_to_purchase.manuf_date,hms_estimate_purchase_to_purchase.expiry_date,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code,hms_medicine_entry.hsn_no,hms_medicine_entry.packing,hms_medicine_entry.conversion"); 
   //       $this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id=hms_estimate_purchase.id','left');
   //        $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_estimate_purchase_to_purchase.medicine_id','left'); 
   //        //$this->db->where('hms_estimate_purchase.id',$estimate_id);
		 // //$this->db->where('hms_medicine_entry.id',$medicine_id);
   //        //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
   //        $this->db->where('hms_estimate_purchase.purchase_id LIKE "'.$vals.'%"');
   //        $this->db->from('hms_estimate_purchase'); 
   //       // $this->db->group_by('hms_estimate_purchase.id');
   //        $query = $this->db->get(); 
   //        $result = $query->result(); 
   //        echo $this->db->last_query(); exit;
         // echo "<pre>"; print_r($result); exit;
          if(!empty($result))
          { 
            $data = array($result['purchase_id'].'|'.$result['medicine_code'].'|'.$result['name'].'|'.$result['vendor_gst'].'|'.$result['email'].'|'.$result['mobile'].'|'.$result['address']);
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


	public function get_estimate_medicine_by_id($purchase_id)
	{
		$response = '';
      if(!empty($purchase_id))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_estimate_purchase.id,hms_estimate_purchase.total_amount as grand_total,hms_estimate_purchase.discount as total_discount,hms_estimate_purchase.purchase_id,hms_estimate_purchase_to_purchase.medicine_id,hms_estimate_purchase_to_purchase.qty,hms_estimate_purchase_to_purchase.purchase_rate,hms_estimate_purchase_to_purchase.discount ,hms_estimate_purchase_to_purchase.freeunit1,hms_estimate_purchase_to_purchase.freeunit2,hms_estimate_purchase_to_purchase.unit1,hms_estimate_purchase_to_purchase.unit2,hms_estimate_purchase_to_purchase.bar_code,hms_estimate_purchase_to_purchase.hsn_no,hms_estimate_purchase_to_purchase.mrp,hms_estimate_purchase_to_purchase.total_amount ,hms_estimate_purchase_to_purchase.per_pic_rate,hms_estimate_purchase_to_purchase.manuf_date,hms_estimate_purchase_to_purchase.expiry_date,hms_estimate_purchase_to_purchase.sgst sgst,hms_estimate_purchase_to_purchase.cgst ,hms_estimate_purchase_to_purchase.igst ,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code,hms_medicine_entry.hsn_no,hms_medicine_entry.packing,hms_medicine_entry.conversion"); 
         $this->db->join('hms_estimate_purchase_to_purchase','hms_estimate_purchase_to_purchase.purchase_id=hms_estimate_purchase.id','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_estimate_purchase_to_purchase.medicine_id','left'); 
          //$this->db->where('hms_estimate_purchase.id',$estimate_id);
		 //$this->db->where('hms_medicine_entry.id',$medicine_id);
          //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
          $this->db->where('hms_estimate_purchase.purchase_id ',$purchase_id);
          $this->db->from('hms_estimate_purchase'); 
         // $this->db->group_by('hms_estimate_purchase.id');
          $query = $this->db->get(); 
          // echo $this->db->last_query(); exit;
          $result = $query->result_array();
        
          //   $data = array();
         $post_mid_arr = array();
      //  echo "<pre>"; print_r($result);die;
            foreach($result as $vals)
           {

        
               // $post_mid_arr[$vals['medicine_id'].'.'.'0'] = array('mid'=>$vals['medicine_id'], 'batch_no'=>'', 'qty'=>$vals['qty'], 'exp_date'=>$vals['expiry_date'],'manuf_date'=>$vals['manuf_date'],'discount'=>$vals['discount'],'bar_code'=>$vals['bar_code'],'conversion'=>$vals['conversion'],'hsn_no'=>$vals['hsn_no'],'cgst'=>$vals['cgst'],'sgst'=>$vals['sgst'],'igst'=>$vals['igst'], 'per_pic_amount'=>$vals['per_pic_rate'],'sale_amount'=>$vals['mrp'],'total_amount'=>$vals['total_amount'],'total_pricewith_medicine'=>$vals['total_amount']); 

           	$post_mid_arr[$vals['medicine_id']] = array('mid'=>$vals['medicine_id'],'id'=>$vals['medicine_id'],'unit1'=>$vals['unit1'],'unit2'=>$vals['unit2'],'conversion'=>$vals['conversion'],'perpic_rate'=>$vals['per_pic_rate'],'manuf_date'=>$vals['manuf_date'],'batch_no'=>'','freeunit1'=>$vals['freeunit1'],'freeunit2'=>$vals['freeunit2'],'hsn_no'=>$vals['hsn_no'],'qty'=>$vals['qty'],'freeqty'=>'1', 'exp_date'=>$vals['expiry_date'],'discount'=>$vals['discount'],'mrp'=>$vals['mrp'],'cgst'=>$vals['cgst'],'igst'=>$vals['igst'],'sgst'=>$vals['sgst'],'purchase_amount'=>$vals['purchase_rate'], 'total_amount'=>$vals['total_amount'],'bar_code'=>$vals['bar_code'],'medicine_name'=>$vals['medicine_name']); 
            }
            return $post_mid_arr;
          
      } 
	}
	
	/* Estimate data */
	
	public function get_medicine_estimate_by_purchase_id($id="")
	{
		$this->db->select('hms_estimate_purchase_to_purchase.*, hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, hms_medicine_entry.conversion, hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code, hms_medicine_entry.min_alrt, hms_medicine_entry.packing, hms_medicine_entry.rack_no, hms_medicine_entry.salt');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_estimate_purchase_to_purchase.medicine_id','left');
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		$this->db->from('hms_estimate_purchase_to_purchase'); 
		if(!empty($id))
		{
          $this->db->where('hms_estimate_purchase_to_purchase.purchase_id',$id);
		} 
		$query = $this->db->get()->result(); 
		//echo "<pre>";print_r($query);die;
		$result = [];
		if(!empty($query))
		{
		  $i=1;  
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

                //echo $medicine->manuf_date;
                if($medicine->expiry_date!='1970-01-01')
                {
                   $exp_date=date('d-m-Y',strtotime($medicine->expiry_date)); 
                }
                else
                {
                    $exp_date='';
                }
                if($medicine->manuf_date!='0000-00-00')
                {
                   $manuf_date=date('d-m-Y',strtotime($medicine->manuf_date)); 
                }
                else
                {
                    $manuf_date='';
                }
                //echo $manuf_date;
                $timestamps = time()-$i;
               $result[$medicine->medicine_id.'_'.$timestamps] = array('mid'=>$medicine->medicine_id,'medicine_name'=>$medicine->medicine_name,'medicine_code'=>$medicine->medicine_code,'conversion'=>$medicine->conversion,'freeunit1'=>$medicine->freeunit1,'freeunit2'=>$medicine->freeunit2,'unit1'=>$medicine->unit1,'unit2'=>$medicine->unit2,'hsn_no'=>$medicine->hsn_no,'perpic_rate'=>$perpic_rate,'batch_no'=>$medicine->batch_no,'manuf_date'=>$manuf_date,'mrp'=>$medicine->mrp,'qty'=>$medicine->qty,'freeqty'=>$medicine->freeqty,'exp_date'=>$exp_date,'discount'=>$medicine->discount,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'purchase_amount'=>$medicine->purchase_rate, 'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code);  
               $i++;
		  } 
		} 
		//echo "<pre>";print_r($result);die;
		return $result;
	}
	
	public function vendor_list($vendor_id="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_medicine_vendors.id,hms_medicine_vendors.name');  
		$this->db->where('hms_medicine_vendors.branch_id = "'.$users_data['parent_id'].'"');
		if(!empty($vendor_id))
		{
		 $this->db->where('hms_medicine_vendors.id',$vendor_id);	
		}
		$this->db->where('hms_medicine_vendors.status',1);	
		$this->db->where('hms_medicine_vendors.vendor_type',1);
		$query = $this->db->get('hms_medicine_vendors');

		$result = $query->result(); 
		return $result; 
	} 

} 
?>