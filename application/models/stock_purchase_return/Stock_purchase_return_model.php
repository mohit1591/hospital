<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock_purchase_return_model extends CI_Model 
{
	var $table = 'path_purchase_return_item';
	 

	var $column = array('path_purchase_return_item.id','path_purchase_return_item.purchase_no','path_purchase_return_item.return_no','path_purchase_return_item.purchase_date','hms_medicine_vendors.name','path_purchase_return_item.net_amount','path_purchase_return_item.paid_amount','path_purchase_return_item.balance','path_purchase_return_item.created_date');  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('stock_purchase_return_search');

		$users_data = $this->session->userdata('auth_users');
		$this->db->select("path_purchase_return_item.*,hms_medicine_vendors.name,(SELECT GROUP_CONCAT(path_item.item) FROM `path_purchase_return_item_purchase_return` join path_item on path_item.id=path_purchase_return_item_purchase_return.item_id WHERE path_purchase_return_item_purchase_return.return_id=path_purchase_return_item.id) as items"); 
		$this->db->where('path_purchase_return_item.is_deleted','0'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_return_item.vendor_id','left');
		$this->db->where('path_purchase_return_item.branch_id',$users_data['parent_id']); 
	
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_purchase_return_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_purchase_return_item.created_date <= "'.$end_date.'"');
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
			$this->db->where('path_purchase_return_item.created_by IN ('.$emp_ids.')');
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
		$search = $this->session->userdata('stock_purchase_return_search');

		$users_data = $this->session->userdata('auth_users');
		$this->db->where('path_purchase_return_item.is_deleted','0'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_return_item.vendor_id','left');
		$this->db->where('path_purchase_return_item.branch_id',$users_data['parent_id']); 
	
		
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_purchase_return_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_purchase_return_item.created_date <= "'.$end_date.'"');
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
			$this->db->where('path_purchase_return_item.created_by IN ('.$emp_ids.')');
		}
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function search_report_data()
	{
		$search = $this->session->userdata('stock_purchase_return_search');

		$users_data = $this->session->userdata('auth_users');
		$this->db->select("path_purchase_return_item.*,hms_medicine_vendors.name,(SELECT GROUP_CONCAT(path_item.item) FROM `path_purchase_return_item_purchase_return` join path_item on path_item.id=path_purchase_return_item_purchase_return.item_id WHERE path_purchase_return_item_purchase_return.return_id=path_purchase_return_item.id) as items"); 
		$this->db->where('path_purchase_return_item.is_deleted','0'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_return_item.vendor_id','left');
		$this->db->where('path_purchase_return_item.branch_id',$users_data['parent_id']); 
	
		$this->db->from($this->table); 
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_purchase_return_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_purchase_return_item.created_date <= "'.$end_date.'"');
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
			$this->db->where('path_purchase_return_item.created_by IN ('.$emp_ids.')');
		}
		 $result= $this->db->get()->result();
		 return $result;
	}

	public function get_by_id($id)
	{
		$this->db->select('path_purchase_return_item.*,hms_medicine_vendors.name,hms_medicine_vendors.id as v_id,hms_medicine_vendors.vendor_id,hms_medicine_vendors.address');
		$this->db->from('path_purchase_return_item'); 
		$this->db->where('path_purchase_return_item.id',$id);
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=path_purchase_return_item.vendor_id');
		$this->db->where('hms_medicine_vendors.vendor_type',3);
		$this->db->where('path_purchase_return_item.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	function get_purchase_to_purchase_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');

		 $this->db->select('path_purchase_return_item_purchase_return.*,path_purchase_return_item_purchase_return.total_amount as amount, path_item.item as item_name, path_item.item_code,path_purchase_return_item_purchase_return.qty as quantity, hms_stock_item_unit.unit, path_purchase_return_item_purchase_return.per_pic_price as item_price, path_item.id as item_id, path_item.price as purchase_amount,path_stock_category.category, path_purchase_return_item_purchase_return.expiry_date as exp_date');
		$this->db->from('path_purchase_return_item_purchase_return'); 
		$this->db->where('path_purchase_return_item_purchase_return.purchase_id',$id);
		$this->db->where('path_purchase_return_item_purchase_return.branch_id',$users_data['parent_id']);
		$this->db->join('path_item','path_item.id=path_purchase_return_item_purchase_return.item_id','left');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_purchase_return_item_purchase_return.unit_id','left');
		$this->db->join('path_stock_category`','path_stock_category.id=path_purchase_return_item_purchase_return.category_id','left');
		
		
		
		$query = $this->db->get()->result_array(); 
		$result = [];
		if(!empty($query))
		{
		  foreach($query as $item_list)	
		  {
		      
		      $serial_no=array();
             $purchase_item_serial =$this->purchase_item_serial_by_id($id,$item_list['item_id']);
            foreach ($purchase_item_serial as  $serial) 
            {
                array_push($serial_no, $serial->serial_no);
            } 
            $serial_data=implode(",", $serial_no);    
            
			$result[$item_list['item_id']] =  array('item_id'=>$item_list['item_id'],'category_id'=>$item_list['category_id'],'item_code'=>$item_list['item_code'],'total_price'=>$item_list['total_amount'],'item_name'=>$item_list['item_name'].'-'.$item_list['category'],'amount'=>$item_list['item_price'],'item_price'=>$item_list['item_price'],'perpic_rate'=>$item_list['item_price'],'quantity'=>$item_list['quantity'],'exp_date'=>date('d-m-Y',strtotime($item_list['exp_date'])),'manuf_date'=>date('d-m-Y',strtotime($item_list['manuf_date'])),'unit1'=>$item_list['unit1'],'unit2'=>$item_list['unit2'],'serial_no_array'=>$serial_data,'mrp'=>$item_list['mrp'],'purchase_amount'=>$item_list['purchase_amount'],'purchase_rate'=>$item_list['purchase_rate'],'discount'=>$item_list['discount'],'cgst'=>$item_list['cgst'],'sgst'=>$item_list['sgst'],'igst'=>$item_list['igst'],'freeunit1'=>$item_list['freeunit1'],'freeunit2'=>$item_list['freeunit2'],'total_amount'=>$item_list['total_amount'],'payment_mode'=>$item_list['payment_mode'],'return_id'=>$item_list['return_id']); 

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
	$this->db->where('hms_payment_mode_field_value_acc_section.type',2);
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
			'purchase_no'=>$post['purchase_code'],
			//'return_no'=>$post['return_code'],
			'purchase_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
			'net_amount'=>$post['net_amount'],
			'vendor_id'=>$post['vendor_id'],
			'total_amount'=>$post['total_amount'],
			'paid_amount'=>$post['pay_amount'],
			'balance'=>$post['balance_due'],
			'discount'=>$post['discount_amount'],
			'payment_mode'=>$post['payment_mode'],
			'discount_percent'=>$post['discount_percent'],
			
			'item_discount'=>$post['item_discount'],
			"sgst"=>$post['sgst_amount'],
			"igst"=>$post['igst_amount'],
			"cgst"=>$post['cgst_amount'],
			);
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
			
			$blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$this->db->set('modified_by',$users_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['data_id']);
			$this->db->update('path_purchase_return_item',$data_purchase);
   //$this->db->last_query();              
			/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$users_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>2,'section_id'=>14));
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

           $stock_purchase_return_item_list=$this->session->userdata('stock_purchase_return_item_list');
          
			//print_r($stock_purchase_return_item_list);die;
			if(!empty($stock_purchase_return_item_list))
			{
				$where_purchase_stock=array('return_id'=>$post['data_id']);
				$this->db->where($where_purchase_stock);
				$this->db->delete('path_purchase_return_item_purchase_return');
				
				$where_path_stock=array('parent_id'=>$post['data_id'],'type'=>2);
				$this->db->where($where_path_stock);
				$this->db->delete('path_stock_item');
				
				 $this->db->where('inv_stock_serial_no.stock_id',$post['data_id']);
                $this->db->where('inv_stock_serial_no.branch_id',$users_data['parent_id']);
                $this->db->where('inv_stock_serial_no.module_id',3);
                $this->db->delete('inv_stock_serial_no');
                $m=0;
				foreach($stock_purchase_return_item_list as $stock_item_list)
				{
				 
				    $qty = ($stock_item_list['conversion']*$stock_item_list['unit1'])+$stock_item_list['unit2'];
					$free_qty = ($stock_item_list['conversion']*$stock_item_list['freeunit1'])+$stock_item_list['freeunit2'];
					$qtys = $qty+$free_qty; 
				
					 $stock_purchase_item = array(
						'branch_id'=>$users_data['parent_id'],
						'return_id'=>$post['data_id'],
						'purchase_id'=>$post['data_id'],
						'item_id'=>$stock_item_list['item_id'],
						'per_pic_price'=>$stock_item_list['perpic_rate'],
						//'unit_id'=>$stock_item_list['unit'],
						//'category_id'=>$stock_item_list['category_id'],
						'total_amount'=>$stock_item_list['total_amount'],
						'qty'=>$qtys,
						'unit1'=>$stock_item_list['unit1'],
						'unit2'=>$stock_item_list['unit2'],
						'mrp'=>$stock_item_list['mrp'],
						'purchase_rate'=>$stock_item_list['purchase_rate'],
						'discount'=>$stock_item_list['discount'],				
						'expiry_date'=>date('Y-m-d',strtotime($stock_item_list['exp_date'])),
						'manuf_date'=>date('Y-m-d',strtotime($stock_item_list['manuf_date'])),
						'freeunit1'=>$stock_item_list['freeunit1'],
						'freeunit2'=>$stock_item_list['freeunit2'],
						'conversion'=>$stock_item_list['conversion'],
						'sgst'=>$stock_item_list['sgst'],
						'cgst'=>$stock_item_list['cgst'],
						'igst'=>$stock_item_list['igst'],
					);
					$this->db->insert('path_purchase_return_item_purchase_return',$stock_purchase_item);
           //echo $this->db->last_query();				
					$data_new_stock=array(
					    "branch_id"=>$users_data['parent_id'],
				    	"type"=>2,
				    	"parent_id"=>$post['data_id'],
				    	"item_id"=>$stock_item_list['item_id'],
				    	"credit"=>$qtys,
				    	"debit"=>0,
				    	"qty"=>$qtys,
						"parent_id"=>$post['data_id'],
				    	"item_id"=>$stock_item_list['item_id'],
				    	"price"=>$stock_item_list['purchase_amount'],
				        //'cat_type_id'=>$stock_item_list['category_id'],
				    	"item_name"=>$stock_item_list['item'],
				    	//"vat"=>$medicine_list['vat'],
				    	//'unit_id'=>$stock_item_list['unit'],
				    	'item_code'=>$stock_item_list['item_code'],
						"total_amount"=>$stock_item_list['total_amount'],
				    	'per_pic_price'=>$stock_item_list['perpic_rate'],
				    	"created_by"=>$users_data['id'],
				    	"created_date"=>date('Y-m-d H:i:s'),
						
				    	);
					 $this->db->insert('path_stock_item',$data_new_stock);
					 $stock_p_id=$this->db->insert_id();
                    //echo $this->db->last_query(); exit;
                    
                     $issued_ser_id_no_array = $post['issued_ser_id_no_array'][$m];
                    //print '<pre>'; print_r($issued_ser_id_no_array); die;
                    if(isset($post['serial_no_array'][$m]) && !empty($post['serial_no_array'][$m]) )
                    {
                       //$ser_array=explode(',',$post['serial_no_array'][$m]);
                       $decoded = json_decode($post['serial_no_array'][$h]);
                       //print '<pre>'; print_r($decoded); die;
                       $ser_array=explode(',',$decoded);
                       
                       if(!empty($issued_ser_id_no_array))
                       {
                           $decoded_ids = json_decode($post['issued_ser_id_no_array'][$m]);
                          $se_issue_array=explode(',',$decoded_ids); 
                       }
                       //print '<pre>'; print_r($se_issue_array); die;
                       $s=0;
                      foreach ($ser_array as $value) 
                      {
                            $this->db->set('issue_status',1);
                			$this->db->where('id',$se_issue_array[$s]);
                			$this->db->update('inv_stock_serial_no');
            
                          $attr=array(
                                     'stock_id'=>$post['data_id'],
                                     'sp_id'=>$stock_item_list['item_id'],
                                     'module_id'=>3,
                                     'item_id'=>$stock_item_list['item_id'],
                                     'status'=>1,
                                     'branch_id'=>$users_data['parent_id'],
                                     'serial_no'=>$value,
                                     'issued_ser_id'=>$se_issue_array[$s]
                                    );
                          $this->db->insert('inv_stock_serial_no',$attr);
            
                         // echo $this->db->last_query();die;
                          $s++;
                      }
                    }
                    
                    $m++;



				}


                      
			}

				/* insert data in payment table  */

				$payment_data = array(
								'parent_id'=>$post['data_id'],
								'branch_id'=>$users_data['parent_id'],
								'section_id'=>'6',
								'type'=>'3',
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


			$this->session->unset_userdata('stock_purchase_return_item_list');
        	$this->session->unset_userdata('stock_item_payment_payment_array');
        	$purchase_id= $post['data_id'];
		}
		else
		{

			//add
			$return_code = generate_unique_id(28);
			$this->db->set('created_by',$users_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('return_no',$return_code);
			
			$this->db->insert('path_purchase_return_item',$data_purchase);
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
						'type'=>2,
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
			
		    $stock_purchase_return_item_list=$this->session->userdata('stock_purchase_return_item_list');
			//print '<pre>'; print_r($ipd_particular_billing_list);die;
			if(!empty($stock_purchase_return_item_list))
			{
			     $h=0;
				foreach($stock_purchase_return_item_list as $stock_item_list)
				{   
				    $qty = ($stock_item_list['conversion']*$stock_item_list['unit1'])+$stock_item_list['unit2'];
					$free_qty = ($stock_item_list['conversion']*$stock_item_list['freeunit1'])+$stock_item_list['freeunit2'];
					$qtys = $qty+$free_qty;
				
					$stock_purchase_item = array(
						
						'branch_id'=>$users_data['parent_id'],
						'return_id'=>$purchase_id,
						'purchase_id'=>$purchase_id,
						'item_id'=>$stock_item_list['item_id'],
						'per_pic_price'=>$stock_item_list['perpic_rate'],
						//'unit_id'=>$stock_item_list['unit'],
						//'category_id'=>$stock_item_list['category_id'],
						'total_amount'=>$stock_item_list['total_amount'],
						'qty'=>$qtys,
						'unit1'=>$stock_item_list['unit1'],
						'unit2'=>$stock_item_list['unit2'],
						'mrp'=>$stock_item_list['mrp'],
						'purchase_rate'=>$stock_item_list['purchase_rate'],
						'discount'=>$stock_item_list['discount'],				
						'expiry_date'=>date('Y-m-d',strtotime($stock_item_list['exp_date'])),
						'manuf_date'=>date('Y-m-d',strtotime($stock_item_list['manuf_date'])),
						'freeunit1'=>$stock_item_list['freeunit1'],
						'freeunit2'=>$stock_item_list['freeunit2'],
						'conversion'=>$stock_item_list['conversion'],
						'sgst'=>$stock_item_list['sgst'],
						'cgst'=>$stock_item_list['cgst'],
						'igst'=>$stock_item_list['igst'],
                        );
						
					$this->db->insert('path_purchase_return_item_purchase_return',$stock_purchase_item);
//echo $this->db->last_query(); die;              
                    $data_new_stock=array(
					
					    "branch_id"=>$users_data['parent_id'],
				    	"type"=>2,
				    	"parent_id"=>$purchase_id,
				    	"item_id"=>$stock_item_list['item_id'],
				    	"credit"=>$qtys,
				    	"debit"=>0,
				    	"qty"=>$qtys,
						"parent_id"=>$post['data_id'],
				    	"item_id"=>$stock_item_list['item_id'],
				    	"price"=>$stock_item_list['purchase_amount'],
				        //'cat_type_id'=>$stock_item_list['category_id'],
				    	"item_name"=>$stock_item_list['item'],
				    	//"vat"=>$medicine_list['vat'],
				    	//'unit_id'=>$stock_item_list['unit'],
				    	'item_code'=>$stock_item_list['item_code'],
						"total_amount"=>$stock_item_list['total_amount'],
				    	'per_pic_price'=>$stock_item_list['perpic_rate'],
				    	"created_by"=>$users_data['id'],
				    	"created_date"=>date('Y-m-d H:i:s'),
				    	);
					 $this->db->insert('path_stock_item',$data_new_stock);
                    //echo $this->db->last_query(); exit;
                    $stock_p_id=$this->db->insert_id();
                    
                    
                    $issued_ser_id_no_array = $post['issued_ser_id_no_array'][$h];
                    // print '<pre>'; print_r($issued_ser_id_no_array); die;
                    if(isset($post['serial_no_array'][$h]) && !empty($post['serial_no_array'][$h]) )
                    {
                       $decoded = json_decode($post['serial_no_array'][$h]);
                       $ser_array=explode(',',$decoded);
                       
                       if(!empty($issued_ser_id_no_array))
                       {
                           $decoded_ids = json_decode($post['issued_ser_id_no_array'][$h]);
                           $se_issue_array=explode(',',$decoded_ids); 
                       }
                       $s=0;
                       
                       //echo "<pre>"; print_r($ser_array); exit;
                      foreach ($ser_array as $value) 
                      {
                            $this->db->set('issue_status',1);
                			$this->db->where('id',$se_issue_array[$s]);
                			$this->db->update('inv_stock_serial_no');
            
                          $attr=array(
                                     'stock_id'=>$purchase_id,
                                     'sp_id'=>$stock_item_list['item_id'],
                                     'module_id'=>3,
                                     'item_id'=>$stock_item_list['item_id'],
                                     'status'=>1,
                                     'branch_id'=>$users_data['parent_id'],
                                     'serial_no'=>$value,
                                     'issued_ser_id'=>$se_issue_array[$s]
                                    );
                          $this->db->insert('inv_stock_serial_no',$attr);
            
                          //echo $this->db->last_query();die;
                          $s++;
                      }
                    }
                    $h++;
				}	
				

			}

			

			/* insert data in payment table  */

				$payment_data = array(
								'parent_id'=>$purchase_id,
								'branch_id'=>$users_data['parent_id'],
								'section_id'=>'6',
								'type'=>'3',
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
			$this->session->unset_userdata('stock_purchase_return_item_list');
			$this->session->unset_userdata('stock_item_payment_payment_array');
        	
		}
      return $purchase_id;	
	}
     public function unit_list($unit_id="")
    {
    	//echo $unit_id;
       $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
         if(!empty($unit_id)){
         	
        	$this->db->where('id',$unit_id);
        }
        $this->db->where('is_deleted',0);
        $this->db->where('hms_stock_item_unit.branch_id',$users_data['parent_id']);
        $this->db->order_by('unit','ASC'); 
        $query = $this->db->get('hms_stock_item_unit');
        $result = $query->result(); 
       //print '<pre>'; print_r($result);
        return $result; 
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
			$this->db->update('path_purchase_return_item');
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
			$this->db->update('path_purchase_return_item');
    	} 
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

	

	public function vendor_list($vendor_id="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		$this->db->where('branch_id = "'.$users_data['parent_id'].'"');
		if(!empty($vendor_id))
		{
		 $this->db->where('id',$vendor_id);	
		}
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
	        $this->db->select('path_item.*,hms_stock_item_unit.unit,,path_stock_category.category');  
	         $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
	       
	        $this->db->where('path_item.status','1'); 
	        $this->db->order_by('path_item.item','ASC');
	        $this->db->where('path_item.is_deleted',0);
	        $this->db->where('path_item.item LIKE "'.$vals.'%"');
	        $this->db->where('path_item.branch_id',$users_data['parent_id']);
	         $this->db->join('path_stock_category`','path_stock_category.id=path_item.category_id','left');
	        $query = $this->db->get('path_item');
	        $result = $query->result(); 
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	        		//$response[] = $vals->medicine_name;
					$name = $vals->item.'-'.$vals->category.'|'.$vals->item_code.'|'.$vals->unit.'|'.$vals->price.'|'.$vals->id.'|'.$vals->category_id;
					array_push($data, $name);
	        	}

	        	echo json_encode($data);
	        }
	        //return $response; 
    	} 
    }


 function get_all_detail_print($ids="",$branch_id="")
	{
		$users_data = $this->session->userdata('auth_users');
		$result_pur=array();
    	$this->db->select('path_purchase_return_item.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.id as v_id,hms_medicine_vendors.vendor_id,hms_medicine_vendors.address as vendor_address,hms_medicine_vendors.email as vendor_email, hms_medicine_vendors.mobile as vendor_mobile,hms_medicine_vendors.vendor_gst');
		$this->db->from('path_purchase_return_item'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=path_purchase_return_item.vendor_id');
		$this->db->where('hms_medicine_vendors.vendor_type',3);
		$this->db->where('path_purchase_return_item.id',$ids);
		$this->db->where('path_purchase_return_item.is_deleted','0');
		$result_pur['purchases_list']= $this->db->get()->result();
//echo $this->db->last_query();die;	
		$this->db->select('path_purchase_return_item_purchase_return.*,path_purchase_return_item_purchase_return.total_amount as amount, path_item.item as item_name, path_item.item_code,path_purchase_return_item_purchase_return.qty as quantity, hms_stock_item_unit.unit, path_purchase_return_item_purchase_return.per_pic_price as item_price, path_item.id as item_id, path_item.price as purchase_amount,path_stock_category.category, path_purchase_return_item_purchase_return.expiry_date as exp_date,path_item.mrp');
		$this->db->from('path_purchase_return_item_purchase_return'); 
		$this->db->where('path_purchase_return_item_purchase_return.return_id',$ids);
		
		$this->db->where('path_purchase_return_item_purchase_return.branch_id',$users_data['parent_id']);
		$this->db->join('path_item','path_item.id=path_purchase_return_item_purchase_return.item_id','left');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_purchase_return_item_purchase_return.unit_id','left');
		$this->db->join('path_stock_category`','path_stock_category.id=path_purchase_return_item_purchase_return.category_id','left');
		$result_pur['purchases_list']['item_list']=$this->db->get()->result();
//echo $this->db->last_query();die;
		return $result_pur;
		
    }

// add bottom 4 feb 20 

     public function item_list($ids="")
    {

    	$users_data = $this->session->userdata('auth_users'); 
		$this->db->select("path_item.*,path_stock_category.category, hms_stock_item_unit.unit as unit1, hms_stock_item_unit_2.unit as unit2, hms_inventory_racks.rack_no, hms_inventory_company.company_name, path_item.price as purchase_rate");

		if(!empty($ids))
		{
			$this->db->where('path_item.id IN ('.$ids.')'); 
		}
		$this->db->where('path_item.is_deleted','0');
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');

        $this->db->join('hms_inventory_company','hms_inventory_company.id=path_item.manuf_company','left');
        $this->db->join('hms_stock_item_unit as hms_stock_item_unit_2','hms_stock_item_unit_2.id = path_item.second_unit','left');

        $this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');

        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');

        $this->db->where('path_item.branch_id',$users_data['parent_id']);

        $this->db->where('path_item.is_deleted','0'); 
		$this->db->group_by('path_item.id');
	
		$query = $this->db->get('path_item');
		$result = $query->result(); 
	//echo $this->db->last_query();die;
		return $result; 
    }


    	public function item_list_search()
	{
		$users_data = $this->session->userdata('auth_users'); 
    	$post = $this->input->post();  
		$this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit as unit1, hms_stock_item_unit_2.unit as unit2, hms_inventory_racks.rack_no, hms_inventory_company.company_name, path_stock_item.type"); 

		
        $this->db->where('path_item.is_deleted','0');
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');

        $this->db->join('hms_inventory_company','hms_inventory_company.id=path_item.manuf_company','left');
        $this->db->join('hms_stock_item_unit as hms_stock_item_unit_2','hms_stock_item_unit_2.id = path_item.second_unit','left');

        $this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');

        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');

        $this->db->where('path_item.branch_id',$users_data['parent_id']);




      if(!empty($post['item']))
		{
		$this->db->where('path_item.item LIKE "'.$post['item'].'%"');
	
		}

		if(!empty($post['item_code']))
		{ 
		$this->db->where('path_item.item_code LIKE "'.$post['item_code'].'%"');	
		}
	
		if(!empty($post['unit1']))
		{  
		$this->db->where('hms_stock_item_unit.id = "'.$post['unit1'].'"');
		}

		if(!empty($post['unit2']))
		{  
		$this->db->where('hms_stock_item_unit_2.id ="'.$post['unit2'].'"');	
		}

		if(!empty($post['mrp']))
		{  
		$this->db->where('path_item.mrp = "'.$post['mrp'].'"');	
		}

		if(!empty($post['p_rate']))
		{  
		$this->db->where('path_item.price = "'.$post['p_rate'].'"');	
		}

		if(!empty($post['discount']))
		{  
		$this->db->where('path_item.discount = "'.$post['discount'].'"');
		}
	
		if(!empty($post['sgst']))
		{  
		 $this->db->where('path_item.sgst = "'.$post['sgst'].'"');	
		}
		if(!empty($post['cgst']))
		{  
		$this->db->where('path_item.cgst = "'.$post['cgst'].'"');	
		}

		if(!empty($post['igst']))
		{  
		$this->db->where('path_item.igst = "'.$post['igst'].'"');	
		}

		if(!empty($post['conversion']))
		{  
		$this->db->where('path_item.conversion = "'.$post['conversion'].'"');	
		}

		if(!empty($post['manuf_company']))
		{  
		$this->db->where('hms_inventory_company.company_name = "'.$post['manuf_company'].'"');	
		}

		if(!empty($post['packing']))
		{  
		$this->db->where('path_item.packing LIKE "'.$post['packing'].'%"');	
		}


		$this->db->where('path_item.branch_id IN ('.$users_data['parent_id'].')');
		$this->db->where('path_item.is_deleted','0'); 
		$this->db->group_by('path_item.id');
		$this->db->from('path_item');
        $query = $this->db->get(); 
        $data= $query->result();
       //print_r($data);die;
   //echo $this->db->last_query();die;
       return  $query->result();
	
	}



     public function check_unique_value($branch_id, $invoice_id, $id='')
    {
    	$this->db->select('hms_medicine_purchase.*');
		$this->db->from('hms_medicine_purchase'); 
		$this->db->where('hms_medicine_purchase.branch_id',$branch_id);
		$this->db->where('hms_medicine_purchase.id',$invoice_id);
		if(!empty($id))
		$this->db->where('hms_medicine_purchase.id !=',$id);
		$this->db->where('hms_medicine_purchase.is_deleted','0');
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
// add bottom 4 feb 20 


public function search_purchase($vals="")
    {
      $response = '';
      if(!empty($vals))
      {
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("path_purchase_item.*,hms_medicine_vendors.name, hms_medicine_vendors.mobile, hms_medicine_vendors.address"); 
		$this->db->where('path_purchase_item.is_deleted','0'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left');
		 
		$this->db->where('path_purchase_item.branch_id',$users_data['parent_id']); 
		$this->db->from('path_purchase_item'); 
		$this->db->where('path_purchase_item.purchase_no LIKE "'.$vals.'%"');
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
           $name = $vals->purchase_no.'|'.$vals->id.'|'.$vals->total_amount.'|'.$vals->discount_percent.'|'.$vals->discount.'|'.$vals->item_discount.'|'.$vals->sgst.'|'.$vals->cgst.'|'.$vals->igst.'|'.$vals->net_amount.'|'.$vals->paid_amount.'|'.$vals->balance.'|'.$vals->payment_mode.'|'.$vals->purchase_date.'|'.$vals->vendor_id.'|'.$vals->vendor_code.'|'.$vals->vendor_name.'|'.$vals->address.'|'.$vals->mobile;
            array_push($data, $name);

			
        }
		


        echo json_encode($data);
      }
      else{
      	echo json_encode(array('status'=>0, 'message'=>'Item not return'));
      }
      //return $response; 
  } 
}


function purchase_item_serial_by_id($request_id="", $sp_id='')
  {
     $user_data=$this->session->userdata('auth_users');

      $this->db->select('inv_stock_serial_no.*');
      $this->db->where('inv_stock_serial_no.stock_id',$request_id);
      $this->db->where('inv_stock_serial_no.is_deleted',0);
      $this->db->where('inv_stock_serial_no.branch_id',$user_data['parent_id']);
      $this->db->where('inv_stock_serial_no.sp_id',$sp_id);
      $this->db->where('inv_stock_serial_no.module_id',3);
      //$this->db->join('inv_stock_product','inv_stock_serial_no.sp_id=inv_stock_product.id','left');
  

      $query=$this->db->get('inv_stock_serial_no');
      //echo $this->db->last_query();die;
      return $query->result();
  }
  
  public function search_serial_no($vals="",$issue_allot_id='',$item_id='')
    {
      $response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("inv_stock_serial_no.id as issued_id,inv_stock_serial_no.serial_no"); 
          $this->db->where('inv_stock_serial_no.branch_id = "'.$user_data['parent_id'].'"');
          $this->db->where('inv_stock_serial_no.item_id',$item_id);
          $this->db->where('inv_stock_serial_no.module_id=1');
          $this->db->where('inv_stock_serial_no.issue_status',0);
          $this->db->where('inv_stock_serial_no.serial_no LIKE "'.$vals.'%"');
          $this->db->from('inv_stock_serial_no'); 
          $query = $this->db->get(); 
          $result = $query->result(); 
          //echo $this->db->last_query(); exit;
          //echo "<pre>"; print_r($result); exit;
          if(!empty($result))
          { 
            $data = array();
            foreach($result as $vals)
            {
                $name = $vals->serial_no.'|'.$vals->issued_id;
                array_push($data, $name);
            }

            echo json_encode($data);
          }
          //return $response; 
      } 
    }
	

} 
?>