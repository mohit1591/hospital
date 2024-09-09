<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock_purchase_model extends CI_Model 
{
	var $table = 'path_purchase_item';
	 

	var $column = array('path_purchase_item.id','path_purchase_item.purchase_no','path_purchase_item.purchase_date','	hms_medicine_vendors.name','path_purchase_item.net_amount','path_purchase_item.balance','path_purchase_item.created_date');  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('stock_purchase_search');

		$users_data = $this->session->userdata('auth_users');
		$this->db->select("path_purchase_item.*,hms_medicine_vendors.name"); 
		$this->db->where('path_purchase_item.is_deleted','0'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left');
		$this->db->where('path_purchase_item.branch_id',$users_data['parent_id']); 
	
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_purchase_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_purchase_item.created_date <= "'.$end_date.'"');
			}
		}

		// if(!empty($search['medicine_name']))
		// {
		// 	$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
		// }

		// if(!empty($search['medicine_company']))
		// {
		// 	// $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
		// 	$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
		// }

		// if(!empty($search['medicine_code']))
		// {
		
		// 	$this->db->where('hms_medicine_entry.medicine_code',$search['medicine_code']);
		// }
		
		
		// if(isset($search['unit1']) && $search['unit1']!="")
		// {
		// 	$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
		// }

		// if(isset($search['unit2']) && $search['unit2']!="")
		// {
		// 	$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
		// }

		// if(isset($search['packing']) && $search['packing']!="")
		// {
		
		// //$this->db->where('packing',$search['packing']);
		// 	$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
		// }
		// if(isset($search['hsn_no']) && $search['hsn_no']!="")
		// {

		// //$this->db->where('packing',$search['packing']);
		// $this->db->where('hms_medicine_entry.hsn_no LIKE "'.$search['hsn_no'].'%"');
		// }
		// if(isset($search['cgst']) && $search['cgst']!="")
		// {

		// //$this->db->where('packing',$search['packing']);
		// $this->db->where('hms_medicine_entry.cgst LIKE "'.$search['cgst'].'%"');
		// }
		// if(isset($search['sgst']) && $search['sgst']!="")
		// {

		// //$this->db->where('packing',$search['packing']);
		// $this->db->where('hms_medicine_entry.sgst LIKE "'.$search['sgst'].'%"');
		// }
		// if(isset($search['igst']) && $search['igst']!="")
		// {

		// //$this->db->where('packing',$search['packing']);
		// $this->db->where('hms_medicine_entry.igst LIKE "'.$search['igst'].'%"');
		// }
		

		// if(isset($search['mrp_to']) && $search['mrp_to']!="")
		// {
		// 	$this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
		// }

		// if(isset($search['mrp_from']) &&$search['mrp_from']!="")
		// {
		// 	$this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
		// }

		// if(isset($search['purchase_to']) &&$search['purchase_to']!="")
		// {
		// 	$this->db->where('hms_medicine_entry.purchase_rate >= "'.$search['purchase_to'].'"');
		// }

		// if(isset($search['purchase_from']) &&$search['purchase_from']!="")
		// {
		// 	$this->db->where('hms_medicine_entry.purchase_rate <="'.$search['purchase_from'].'"');
		// }

		// if(isset($search['rack_no']) &&$search['rack_no']!="")
		// {
		// 	//$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
		// 	$this->db->where('hms_medicine_racks.rack_no',$search['rack_no']);
			
		// }
		// if(isset($search['min_alert']) &&$search['min_alert']!="")
		// {
		// 	$this->db->where('hms_medicine_entry.min_alrt',$search['min_alert']);
		// }
  //       if(isset($search['discount']) &&$search['discount']!="")
		// {
		// 	$this->db->where('hms_medicine_entry.discount',$search['discount']);
		// }     

		// }
	    $emp_ids='';
			if($users_data['emp_id']>0)
			{
				if($users_data['record_access']=='1')
				{
					$emp_ids= $users_data['id'];
				}
			}
			elseif(!empty($search["employee"]) && is_numeric($search['employee']))
			{
				$emp_ids=  $search["employee"];
			}


			if(isset($emp_ids) && !empty($emp_ids))
			{ 
				$this->db->where('path_purchase_item.created_by IN ('.$emp_ids.')');
			}
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

	public function search_report_data()
	{
		$search = $this->session->userdata('stock_purchase_search');

		$users_data = $this->session->userdata('auth_users');
		$this->db->select("path_purchase_item.*,hms_medicine_vendors.name"); 
		$this->db->where('path_purchase_item.is_deleted','0'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left');
		$this->db->where('path_purchase_item.branch_id',$users_data['parent_id']); 
	
		$this->db->from($this->table); 
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_purchase_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_purchase_item.created_date <= "'.$end_date.'"');
			}
		}
		
		$emp_ids='';
			if($users_data['emp_id']>0)
			{
				if($users_data['record_access']=='1')
				{
					$emp_ids= $users_data['id'];
				}
			}
			elseif(!empty($search["employee"]) && is_numeric($search['employee']))
			{
				$emp_ids=  $search["employee"];
			}


			if(isset($emp_ids) && !empty($emp_ids))
			{ 
				$this->db->where('path_purchase_item.created_by IN ('.$emp_ids.')');
			}
		 $result= $this->db->get()->result();
		 return $result;
	}

	public function get_by_id($id)
	{
		$this->db->select('path_purchase_item.*,hms_medicine_vendors.name,hms_medicine_vendors.id as v_id,hms_medicine_vendors.vendor_id,hms_medicine_vendors.address');
		$this->db->from('path_purchase_item'); 
		$this->db->where('path_purchase_item.id',$id);
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=path_purchase_item.vendor_id');
		$this->db->where('hms_medicine_vendors.vendor_type',3);
		$this->db->where('path_purchase_item.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	function get_purchase_to_purchase_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		 $this->db->select('path_purchase_item_to_purchase.purchase_id,path_purchase_item_to_purchase.category_id,path_purchase_item_to_purchase.total_amount as amount,path_purchase_item_to_purchase.total_amount,path_item.item as item_name,path_item.item_code,path_purchase_item_to_purchase.qty as quantity,hms_stock_item_unit.unit,path_purchase_item_to_purchase.per_pic_price as item_price,path_item.id as item_id,path_stock_category.category');
		$this->db->from('path_purchase_item_to_purchase'); 
		$this->db->where('path_purchase_item_to_purchase.purchase_id',$id);
		$this->db->where('path_purchase_item_to_purchase.branch_id',$users_data['parent_id']);
		$this->db->join('path_item','path_item.id=path_purchase_item_to_purchase.item_id','left');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_purchase_item_to_purchase.unit_id','left');
		$this->db->join('path_stock_category`','path_stock_category.id=path_purchase_item_to_purchase.category_id','left');
	
		$query = $this->db->get()->result_array(); 
		
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $item_list)	
		  {
            $result[$item_list['item_id']] =  array('item_id'=>$item_list['item_id'],'category_id'=>$item_list['category_id'],'item_code'=>$item_list['item_code'],'total_price'=>$item_list['total_amount'],'item_name'=>$item_list['item_name'].'-'.$item_list['category'],'amount'=>$item_list['item_price']*$item_list['quantity'],'unit'=>$item_list['unit'],'item_price'=>$item_list['item_price'],'quantity'=>$item_list['quantity'],'total_amount'=>''); 

			 } 
		} 
		
		return $result;
	}
    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
		$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
		$this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.type',1);
		$this->db->where('hms_payment_mode_field_value_acc_section.section_id',14);
		$this->db->where('hms_payment_mode_field_value_acc_section.branch_id = "'.$users_data['parent_id'].'"');
		$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
		//echo $this->db->last_query();die;
	     return $query;
	}

	public function save()
	{
		$users_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		$data_purchase= array(
							'branch_id'=>$users_data['parent_id'],
							//'purchase_no'=>$post['purchase_code'],
							'purchase_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
							'net_amount'=>$post['net_amount'],
							'vendor_id'=>$post['vendor_id'],
							'total_amount'=>$post['total_amount'],
							'paid_amount'=>$post['pay_amount'],
							'balance'=>$post['balance_due'],
							'discount'=>$post['discount_amount'],
							'payment_mode'=>$post['payment_mode'],
							'discount_percent'=>$post['discount_percent'],
		);
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
			
			$blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$this->db->set('modified_by',$users_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['data_id']);
			$this->db->update('path_purchase_item',$data_purchase);
                
			/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$users_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>1,'section_id'=>14));
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
						'section_id'=>14,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$users_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					$this->db->set('created_by',$users_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
                    // echo $this->db->last_query();
					}
			}
			//die;

			/*add sales banlk detail*/


			
			

            $stock_purchase_item_list=$this->session->userdata('stock_purchase_item_list');
			if(!empty($stock_purchase_item_list))
			{
				$where_purchase_stock=array('purchase_id'=>$post['data_id']);
				$this->db->where($where_purchase_stock);
				$this->db->delete('path_purchase_item_to_purchase');
				
				$where_path_stock=array('parent_id'=>$post['data_id'],'type'=>1);
				$this->db->where($where_path_stock);
				$this->db->delete('path_stock_item');

				foreach($stock_purchase_item_list as $stock_item_list)
				{
					 $stock_purchase_item = array(
						'branch_id'=>$users_data['parent_id'],
						'purchase_id'=>$post['data_id'],
						'item_id'=>$stock_item_list['item_id'],
						'per_pic_price'=>$stock_item_list['item_price'],
						'unit_id'=>$stock_item_list['unit'],
						'category_id'=>$stock_item_list['category_id'],
						'total_amount'=>$stock_item_list['quantity']*$stock_item_list['item_price'],
						 'qty'=>$stock_item_list['quantity']
					);
					//echo "<pre>"; print_r($stock_purchase_item);
					$this->db->insert('path_purchase_item_to_purchase',$stock_purchase_item);
					//echo $this->db->last_query();

					$data_new_stock=array("branch_id"=>$users_data['parent_id'],
				    	"type"=>1,
				    	"parent_id"=>$post['data_id'],
				    	"item_id"=>$stock_item_list['item_id'],
				    	"credit"=>0,
				    	"debit"=>$stock_item_list['quantity'],
				    	"qty"=>$stock_item_list['quantity'],
				    	"price"=>$stock_item_list['item_price'],
				        'cat_type_id'=>$stock_item_list['category_id'],
				    	"item_name"=>$stock_item_list['item_name'],
				    	//"vat"=>$medicine_list['vat'],
				    	'unit_id'=>$stock_item_list['unit'],
				    	'item_code'=>$stock_item_list['item_code'],
						"total_amount"=>$stock_item_list['total_price'],
				    	'per_pic_price'=>$stock_item_list['item_price'],
				    	"created_by"=>$users_data['id'],
				    	"created_date"=>date('Y-m-d H:i:s'),
				    	);
					//echo "<pre>"; print_r($data_new_stock);
					 $this->db->insert('path_stock_item',$data_new_stock);
					//echo $this->db->last_query(); 
				}
				//die();
                      
			}
                /* insert data into expense table  */


				$data_expenses= array(
						'branch_id'=>$users_data['parent_id'],
						'type'=>4,
						'vouchar_no'=>generate_unique_id(19),
						'parent_id'=>$post['data_id'],
						'expenses_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
						'paid_to_id'=>$post['vendor_id'],
						'paid_amount'=>$post['pay_amount'],
						'payment_mode'=>$post['payment_mode'],
						// 'cheque_no'=>$cheque_no,
						//'branch_name'=>$bank_name,
						//'cheque_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
						//'transaction_no'=>$transaction_no,
						'modified_date'=>date('Y-m-d H:i:s'),
						'modified_by'=>$users_data['id'],
						);
				   $this->db->insert('hms_expenses',$data_expenses);

				/* insert data into expense table  */

				/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$users_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>6,'section_id'=>14));
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
						'section_id'=>14,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$users_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					$this->db->set('created_by',$users_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

					}
			}

			/*add sales banlk detail*/




				/* insert data in payment table  */

				$payment_data = array(
								'parent_id'=>$post['data_id'],
								'branch_id'=>$users_data['parent_id'],
								'section_id'=>'6',
								'vendor_id'=>$post['vendor_id'],
								'total_amount'=>str_replace(',', '', $post['total_amount']),
								'discount_amount'=>$post['discount_amount'],
								'net_amount'=>str_replace(',', '', $post['net_amount']),
								'credit'=>str_replace(',', '', $post['net_amount']),
								'debit'=>$post['pay_amount'],
								'pay_mode'=>$post['payment_mode'],
								// 'bank_name'=>$bank_name,
								// 'card_no'=>$card_no,
								// 'cheque_no'=>$cheque_no,
								// 'cheque_date'=>$payment_date,
								'balance'=>$blance,
								'paid_amount'=>$post['pay_amount'],
								'created_date'=>date('Y-m-d H:i:s'),
								'created_by'=>$users_data['id']
            	             );
             $this->db->insert('hms_payment',$payment_data);


				/* insert data in payment table  */

						/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$users_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>5,'section_id'=>14));
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
						'type'=>5,
						'section_id'=>14,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$users_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					$this->db->set('created_by',$users_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

					}
			}

			/*add sales banlk detail*/


			$this->session->unset_userdata('stock_purchase_item_list');
        	$this->session->unset_userdata('stock_item_payment_payment_array');
        	$purchase_id= $post['data_id'];
		}
		else
		{
			//add
			$purchase_code = generate_unique_id(27);
			$this->db->set('created_by',$users_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('purchase_no',$purchase_code);
			$this->db->insert('path_purchase_item',$data_purchase);
			//echo $this->db->last_query();die;
			$purchase_id=$this->db->insert_id();
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
						'type'=>1,
						'section_id'=>14,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$users_data['parent_id'],
						'parent_id'=>$purchase_id,
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					$this->db->set('created_by',$users_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
                     //echo $this->db->last_query();
					}
			   }
            //die;
			/*add sales banlk detail*/
			$stock_purchase_item_list = $this->session->userdata('stock_purchase_item_list');
		
			//print '<pre>'; print_r($ipd_particular_billing_list);die;
			if(!empty($stock_purchase_item_list))
			{
				foreach($stock_purchase_item_list as $stock_item_list)
				{
				
					$stock_purchase_item = array(
						'branch_id'=>$users_data['parent_id'],
						'purchase_id'=>$purchase_id,
						'item_id'=>$stock_item_list['item_id'],
						'category_id'=>$stock_item_list['category_id'],
						'per_pic_price'=>$stock_item_list['item_price'],
						'total_amount'=>$stock_item_list['total_price'],
						'qty'=>$stock_item_list['quantity']
					);
					$this->db->insert('path_purchase_item_to_purchase',$stock_purchase_item);
                    
                    $data_new_stock=array("branch_id"=>$users_data['parent_id'],
				    	"type"=>1,
				    	"parent_id"=>$purchase_id,
				    	"item_id"=>$stock_item_list['item_id'],
				    	"credit"=>0,
				    	"debit"=>$stock_item_list['quantity'],
				    	"qty"=>$stock_item_list['quantity'],
				    	"price"=>$stock_item_list['item_price'],
				         'cat_type_id'=>$stock_item_list['category_id'],
				    	"item_name"=>$stock_item_list['item_name'],
				    	//"vat"=>$medicine_list['vat'],
				    	'unit_id'=>$stock_item_list['unit'],
				    	'item_code'=>$purchase_code,
						"total_amount"=>$stock_item_list['total_price'],
				    	'per_pic_price'=>$stock_item_list['item_price'],
				    	"created_by"=>$users_data['id'],
				    	"created_date"=>date('Y-m-d H:i:s'),
				    	);
					 $this->db->insert('path_stock_item',$data_new_stock);
					//echo $this->db->last_query(); exit;
				}	
				

			}

			$data_expenses= array(
						'branch_id'=>$users_data['parent_id'],
						'type'=>4,
						'vouchar_no'=>generate_unique_id(19),
						'parent_id'=>$purchase_id,
						'expenses_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
						'paid_to_id'=>$post['vendor_id'],
						'paid_amount'=>$post['pay_amount'],
						'payment_mode'=>$post['payment_mode'],
						// 'cheque_no'=>$cheque_no,
						//'branch_name'=>$bank_name,
						//'cheque_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
						//'transaction_no'=>$transaction_no,
						'modified_date'=>date('Y-m-d H:i:s'),
						'modified_by'=>$users_data['id'],
						);
				   $this->db->insert('hms_expenses',$data_expenses);
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
						'type'=>6,
						'section_id'=>14,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$users_data['parent_id'],
						'parent_id'=>$purchase_id,
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					$this->db->set('created_by',$users_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

					}
			   }

			/*add sales banlk detail*/

			/* insert data in payment table  */

				$payment_data = array(
								'parent_id'=>$purchase_id,
								'branch_id'=>$users_data['parent_id'],
								'section_id'=>'6',
								'vendor_id'=>$post['vendor_id'],
								'total_amount'=>str_replace(',', '', $post['total_amount']),
								'discount_amount'=>$post['discount_amount'],
								'net_amount'=>str_replace(',', '', $post['net_amount']),
								'credit'=>str_replace(',', '', $post['net_amount']),
								'debit'=>$post['pay_amount'],
								'pay_mode'=>$post['payment_mode'],
								// 'bank_name'=>$bank_name,
								// 'card_no'=>$card_no,
								// 'cheque_no'=>$cheque_no,
								// 'cheque_date'=>$payment_date,
								'balance'=>$blance,
								'paid_amount'=>$post['pay_amount'],
								'created_date'=>date('Y-m-d H:i:s'),
								'created_by'=>$users_data['id']
            	             );
             $this->db->insert('hms_payment',$payment_data);
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
						'type'=>5,
						'section_id'=>14,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$users_data['parent_id'],
						'parent_id'=>$purchase_id,
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					$this->db->set('created_by',$users_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

					}
			}

			/*add sales banlk detail*/

			/* insert data in payment table  */
			$this->session->unset_userdata('stock_purchase_item_list');
			$this->session->unset_userdata('stock_item_payment_payment_array');
        	
		}
      return $purchase_id;	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$users_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$users_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_purchase_item');
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
			$users_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$users_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('path_purchase_item');
    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_purchase');
        $result = $query->result(); 
        return $result; 
    } 

   
    function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	//print_r($query);exit;
    	return $query;

    }

    public function get_patient_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_patient.*');
		$this->db->from('hms_patient'); 
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].')');
		$this->db->where('hms_patient.id',$id);
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function attended_doctor_list()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (1,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		// print '<pre>'; print_r($result);
		return $result; 
	}

	public function assigned_doctor_list()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (0,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		// print '<pre>'; print_r($result);
		return $result; 
	}

	public function aasigned_doctor_by_id($id=''){
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		if(!empty($id)){
		$this->db->where('ipd_booking_id',$id);
		}
		$this->db->order_by('id','ASC');
		$this->db->group_by('doctor_id');
		$this->db->where('branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_ipd_assign_doctor');
		$result = $query->result(); 
		// echo $this->db->last_query();die;
		return $result; 
	}

	public function vendor_list($vendor_id="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		$this->db->where('branch_id = "'.$users_data['parent_id'].'"');
		if(!empty($vendor_id))
		{
		 $this->db->where('id',$vendor_id);	
		}
		$this->db->where('status',1);	
		$this->db->where('vendor_type',3);
		$query = $this->db->get('hms_medicine_vendors');

		$result = $query->result(); 
		return $result; 
	} 

	public function get_item_values($vals="")
	{
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
            //$this->db->select('(sum(debit)-sum(credit)) as total_qty');
	        $this->db->select('path_stock_item.*,(sum(path_stock_item.debit)-sum(path_stock_item.credit)) as qty,path_stock_item.item_id,hms_stock_item_unit.unit as first_unit,hms_second_unit.unit as second_unit_name,hms_second_unit.id as s_id,path_stock_category.category,path_item.item,path_item.price,path_item.item_code,path_item.category_id');  
	        
	         
	         $this->db->join('path_item','path_item.id=path_stock_item.item_id','left');
	          $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
	         $this->db->join('hms_stock_item_unit as hms_second_unit','hms_second_unit.id=path_item.second_unit','left');
	       
	        $this->db->where('path_item.status','1'); 
	        $this->db->order_by('path_item.item','ASC');
	        $this->db->where('path_item.is_deleted',0);
	        $this->db->group_by('path_stock_item.item_id');
	        $this->db->where('path_item.item LIKE "'.$vals.'%"');
	        $this->db->where('path_item.branch_id',$users_data['parent_id']);  
	         $this->db->join('path_stock_category`','path_stock_category.id=path_item.category_id','left');
	        $query = $this->db->get('path_stock_item');

	        $result = $query->result();
             //print_r($result);die;
	       //echo $this->db->last_query();die;
	        
	         $data = array();
	       
	         $unit_first=array();	
	         $dropdown=[];
	         $new_option='';
	         $unit_second=array();
	         $unit_second_id=[];
	         $unit_first_id=[];
					if(!empty($result))
					{ 

						if(!empty($result[0]->first_unit))
						{
						$unit_first['first_name']= $result[0]->first_unit;
						$unit_first['id']= $result[0]->unit_id;
						$unit_first_id[]=$unit_first;
						}

						if(!empty($result[0]->second_unit_name))
						{
						$unit_second['first_name']=$result[0]->second_unit_name;
						$unit_second['id']=$result[0]->s_id;
						$unit_second_id[]=$unit_second;
						}

						// print_r($unit_first);
						 //echo '<br>';
						 //print_r($unit_second);die;
						$dropdown[]=array_merge($unit_first_id,$unit_second_id);
						$dropdown_value= array_unique($dropdown);

						$new_option.='<option>Select Unit</option>';
						if(!empty($dropdown_value[0]) && count($dropdown_value[0])>0)
						{
                        
						foreach($dropdown_value[0] as $options)
						{
							
						if(!empty($options))
						{
						$new_option.= '<option value="'.$options['id'].'">'.$options['first_name'].'</option>';

						}


						}
						}


					}
				//	print_r($result);die;
	        
            if(!empty($result))
            {
            	foreach($result as $vals)
	        	{

                   $name = $result[0]->item.'-'.$result[0]->category.'|'.$result[0]->item_code.'|'.$result[0]->first_unit.'|'.$result[0]->price.'|'.$result[0]->item_id.'|'.$result[0]->category_id.'|'.$result[0]->qty.'|'.$new_option.'|'.$result[0]->item;
					array_push($data, $name);
	        	}
                 //print_r($data);die;
	        	echo json_encode($data);
           }
	        
	        //return $response; 
    	} 
    }



    function get_all_detail_print($ids="",$branch_id="")
	{
		$users_data = $this->session->userdata('auth_users');
		$result_sales=array();
    	$this->db->select("path_purchase_item.*,hms_medicine_vendors.name,hms_medicine_vendors.id as v_id,hms_medicine_vendors.vendor_id,hms_medicine_vendors.address,hms_users.*,hms_medicine_vendors.email as v_email,hms_medicine_vendors.address as v_address,hms_medicine_vendors.mobile as v_mobile"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left'); 
		$this->db->join('hms_users','hms_users.id = path_purchase_item.created_by'); 
		$this->db->where('path_purchase_item.is_deleted','0'); 
		$this->db->where('path_purchase_item.branch_id = "'.$branch_id.'"'); 
		$this->db->where('path_purchase_item.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_sales['purchase_list']= $this->db->get()->result();

		$this->db->select('path_purchase_item_to_purchase.purchase_id,path_purchase_item_to_purchase.category_id,path_purchase_item_to_purchase.total_amount as amount,path_purchase_item_to_purchase.total_amount,path_item.item as item_name,path_item.item_code,path_purchase_item_to_purchase.qty as quantity,hms_stock_item_unit.unit,path_purchase_item_to_purchase.per_pic_price as item_price,path_item.id as item_id,path_stock_category.category');
		$this->db->from('path_purchase_item_to_purchase'); 
		$this->db->where('path_purchase_item_to_purchase.purchase_id',$ids);
		$this->db->where('path_purchase_item_to_purchase.branch_id',$users_data['parent_id']);
		$this->db->join('path_item','path_item.id=path_purchase_item_to_purchase.item_id','left');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_purchase_item_to_purchase.unit_id','left');
		$this->db->join('path_stock_category`','path_stock_category.id=path_purchase_item_to_purchase.category_id','left');
	
		$result_sales['purchase_list']['item_list']=$this->db->get()->result();

		return $result_sales;
		
    }



} 
?>