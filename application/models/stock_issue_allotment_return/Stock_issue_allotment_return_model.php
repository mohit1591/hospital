<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock_issue_allotment_return_model extends CI_Model 
{
	var $table = 'hms_stock_issue_allotment_return_item';
	 

	var $column = array('hms_stock_issue_allotment_return_item.id','hms_stock_issue_allotment_return_item.return_no','	hms_stock_issue_allotment_return_item.user_type_id','hms_stock_issue_allotment_return_item.total_amount','hms_stock_issue_allotment_return_item.net_amount','hms_stock_issue_allotment_return_item.created_date', 'hms_employees.name', 'hms_patient.patient_name', 'hms_doctors.doctor_name');  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('stock_issue_allotment_return_search');

		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_stock_issue_allotment_return_item.*,hms_employees.name, hms_patient.patient_name, hms_doctors.doctor_name,(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as member_name,(SELECT GROUP_CONCAT(path_item.item) FROM `hms_stock_issue_allotment_return_to_issue_allotment_return` join path_item on path_item.id=hms_stock_issue_allotment_return_to_issue_allotment_return.item_id WHERE hms_stock_issue_allotment_return_to_issue_allotment_return.issue_return_id=hms_stock_issue_allotment_return_item.id) as items"); 
		$this->db->where('hms_stock_issue_allotment_return_item.is_deleted','0'); 
		
		$this->db->join('hms_employees','hms_employees.id = hms_stock_issue_allotment_return_item.user_type_id','left'); 
		$this->db->join('hms_patient','hms_patient.id = hms_stock_issue_allotment_return_item.user_type_id','left'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_stock_issue_allotment_return_item.user_type_id','left'); 
		
		$this->db->where('hms_stock_issue_allotment_return_item.branch_id',$users_data['parent_id']); 
	
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('hms_stock_issue_allotment_return_item.issue_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('hms_stock_issue_allotment_return_item.issue_date <= "'.$end_date.'"');
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
			$this->db->where('hms_stock_issue_allotment_return_item.created_by IN ('.$emp_ids.')');
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
	    $users_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('stock_issue_allotment_return_search');
		$this->db->select("hms_stock_issue_allotment_return_item.*,hms_employees.name, hms_patient.patient_name, hms_doctors.doctor_name,(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as member_name,(SELECT GROUP_CONCAT(path_item.item) FROM `hms_stock_issue_allotment_return_to_issue_allotment_return` join path_item on path_item.id=hms_stock_issue_allotment_return_to_issue_allotment_return.item_id WHERE hms_stock_issue_allotment_return_to_issue_allotment_return.issue_return_id=hms_stock_issue_allotment_return_item.id) as items"); 
		$this->db->where('hms_stock_issue_allotment_return_item.is_deleted','0'); 
		
		$this->db->join('hms_employees','hms_employees.id = hms_stock_issue_allotment_return_item.user_type_id','left'); 
		$this->db->join('hms_patient','hms_patient.id = hms_stock_issue_allotment_return_item.user_type_id','left'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_stock_issue_allotment_return_item.user_type_id','left'); 
		
		$this->db->where('hms_stock_issue_allotment_return_item.branch_id',$users_data['parent_id']); 
	
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('hms_stock_issue_allotment_return_item.issue_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('hms_stock_issue_allotment_return_item.issue_date <= "'.$end_date.'"');
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
			$this->db->where('hms_stock_issue_allotment_return_item.created_by IN ('.$emp_ids.')');
		}
		 $result= $this->db->get()->result();
		 //echo $this->db->last_query();exit;
		 return $result;
	}

	public function get_by_id($id)
	{
		$this->db->select("hms_stock_issue_allotment_return_item.*,(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as member_name,(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.address from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.address from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.address from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as address");
		$this->db->from('hms_stock_issue_allotment_return_item'); 
		$this->db->where('hms_stock_issue_allotment_return_item.id',$id);
		
		$this->db->where('hms_stock_issue_allotment_return_item.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	function get_purchase_to_purchase_by_id($id)
	{
		 $users_data = $this->session->userdata('auth_users');

		 $this->db->select('hms_stock_issue_allotment_return_to_issue_allotment_return.*, hms_stock_issue_allotment_return_to_issue_allotment_return.issue_return_id,hms_stock_issue_allotment_return_to_issue_allotment_return.category_id,hms_stock_issue_allotment_return_to_issue_allotment_return.total_amount as amount,
		 	path_item.item as item_name,path_item.item_code,
		 	hms_stock_issue_allotment_return_to_issue_allotment_return.qty,
		 	hms_stock_item_unit.unit,
		 	hms_stock_issue_allotment_return_to_issue_allotment_return.per_pic_price as perpic_rate,
		 	hms_stock_issue_allotment_return_to_issue_allotment_return.expiry_date as exp_date,
		 	path_item.id as item_id,path_stock_category.category');

		

		$this->db->from('hms_stock_issue_allotment_return_to_issue_allotment_return'); 
		$this->db->where('hms_stock_issue_allotment_return_to_issue_allotment_return.issue_return_id',$id);
		$this->db->where('hms_stock_issue_allotment_return_to_issue_allotment_return.branch_id',$users_data['parent_id']);
		$this->db->join('path_item','path_item.id=hms_stock_issue_allotment_return_to_issue_allotment_return.item_id','left');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=hms_stock_issue_allotment_return_to_issue_allotment_return.unit_id','left');
		$this->db->join('path_stock_category`','path_stock_category.id=hms_stock_issue_allotment_return_to_issue_allotment_return.category_id','left');
	
		$query = $this->db->get()->result_array(); 
		//echo $this->db->last_query();exit;
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
            
            $result[$item_list['item_id']] =  array('item_id'=>$item_list['item_id'],'category_id'=>$item_list['category_id'],'item_code'=>$item_list['item_code'],'item_name'=>$item_list['item_name'],'amount'=>$item_list['perpic_rate'],'perpic_rate'=>$item_list['perpic_rate'],'qty'=>$item_list['qty'],'exp_date'=>date('d-m-Y',strtotime($item_list['exp_date'])),'manuf_date'=>date('d-m-Y',strtotime($item_list['manuf_date'])),'mrp'=>$item_list['mrp'],'discount'=>$item_list['discount'],'cgst'=>$item_list['cgst'],'sgst'=>$item_list['sgst'],'igst'=>$item_list['igst'],'total_amount'=>$item_list['total_amount'],'payment_mode'=>$item_list['payment_mode'],'conversion'=>$item_list['conversion'],'serial_no_array'=>$serial_data,'issue_return_id'=>$item_list['issue_return_id']
            ); 
			 } 
		} 
		
		return $result;
		//return $query->result_array();
	}
    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
	{
	$users_data = $this->session->userdata('auth_users'); 
	$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
	$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
	$this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
	$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
	$this->db->where('hms_payment_mode_field_value_acc_section.type',3);
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

		//echo "<pre>"; print_r($post); die; 

		$emp_type='';
		if(isset($post['employee_type']) && !empty($post['employee_type']))
		{
		   $emp_type= $post['employee_type'];
		}
		$data_purchase= array(
			'branch_id'=>$users_data['parent_id'],
			//'return_no'=>$post['return_code'],
			'user_type'=>$post['user_type'],
			'issue_date'=>date('Y-m-d',strtotime($post['issue_date'])),
			'user_type_id'=>$post['user_type_id'],
			'employee_type'=>$emp_type,

            'net_amount'=>$post['net_amount'],
			'total_amount'=>$post['total_amount'],
			'paid_amount'=>$post['pay_amount'],
			'balance'=>$post['balance_due'],
			'discount'=>$post['discount_amount'],
			'payment_mode'=>$post['payment_mode'],
			'discount_percent'=>$post['discount_percent'],
            'issue_no'=>$post['issue_no'],
			'item_discount'=>$post['item_discount'],
            "sgst"=>$post['sgst_amount'],
            "igst"=>$post['igst_amount'],
            "cgst"=>$post['cgst_amount'],
			);

		if(!empty($post['data_id']) && $post['data_id']>0)
		{
			
			//$blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
			$this->db->set('modified_by',$users_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['data_id']);
			$this->db->update('hms_stock_issue_allotment_return_item',$data_purchase);
 

            $stock_issue_allotment_return_item_list=$this->session->userdata('stock_issue_allotment_return_item_list');
			//print '<pre>'; print_r($stock_issue_allotment_return_item_list);die;
			if(!empty($stock_issue_allotment_return_item_list))
			{
				$where_purchase_stock=array('issue_return_id'=>$post['data_id']);
				$this->db->where($where_purchase_stock);
				$this->db->delete('hms_stock_issue_allotment_return_to_issue_allotment_return');
				
				$where_path_stock=array('parent_id'=>$post['data_id'],'type'=>7);
				$this->db->where($where_path_stock);
				$this->db->delete('path_stock_item');
				
				$this->db->where('inv_stock_serial_no.stock_id',$post['data_id']);
                $this->db->where('inv_stock_serial_no.branch_id',$users_data['parent_id']);
                $this->db->where('inv_stock_serial_no.module_id',4);
                $this->db->delete('inv_stock_serial_no');
                $m=0;
				foreach($stock_issue_allotment_return_item_list as $stock_item_list)
				{
				
					 $stock_purchase_item = array(
						'branch_id'=>$users_data['parent_id'],
						'issue_return_id'=>$post['data_id'],
						'item_id'=>$stock_item_list['item_id'],
						'per_pic_price'=>$stock_item_list['perpic_rate'],
						'unit_id'=>$stock_item_list['unit'],
						'category_id'=>$stock_item_list['category_id'],
						'total_amount'=>$stock_item_list['total_amount'],
						'qty'=>$stock_item_list['qty'],

						'mrp'=>$stock_item_list['mrp'],
						'discount'=>$stock_item_list['discount'],				
						'expiry_date'=>date('Y-m-d',strtotime($stock_item_list['exp_date'])),
						'manuf_date'=>date('Y-m-d',strtotime($stock_item_list['manuf_date'])),
						'conversion'=>$stock_item_list['conversion'],
						'sgst'=>$stock_item_list['sgst'],
						'cgst'=>$stock_item_list['cgst'],
						'igst'=>$stock_item_list['igst'],
					   );

					$this->db->insert('hms_stock_issue_allotment_return_to_issue_allotment_return',$stock_purchase_item);
                    //echo $this->db->last_query();  exit;

					$data_new_stock=array("branch_id"=>$users_data['parent_id'],
				    	"type"=>7,
				    	"parent_id"=>$post['data_id'],
				    	"item_id"=>$stock_item_list['item_id'],
				    	"credit"=>0,
				    	"debit"=>$stock_item_list['qty'],
				    	"qty"=>$stock_item_list['qty'],
				    	"price"=>$stock_item_list['mrp'],
				     	'cat_type_id'=>$stock_item_list['category_id'],
				    	"item_name"=>$stock_item_list['item_name'],
				    	//"vat"=>$medicine_list['vat'],
				    	'unit_id'=>$stock_item_list['unit'],
				    	'item_code'=>$stock_item_list['item_code'],
						"total_amount"=>$stock_item_list['total_amount'],
				    	'per_pic_price'=>$stock_item_list['perpic_rate'],
				    	"created_by"=>$users_data['id'],
				    	"created_date"=>date('Y-m-d H:i:s'),
				    	);
					 $this->db->insert('path_stock_item',$data_new_stock);
                     $purchase_id= $this->db->insert_id();
	                // echo $this->db->last_query(); die;
	                
	                if(isset($post['serial_no_array'][$m]) && !empty($post['serial_no_array'][$m]) )
                    {
                         $issued_ser_id_no_array = $post['issued_ser_id_no_array'][$m];
                         
                       $decoded = json_decode($post['serial_no_array'][$m]);
                       //print '<pre>'; print_r($decoded); die;
                       $ser_array=explode(',',$decoded);
                       if(!empty($issued_ser_id_no_array))
                       {
                           $decoded_ids = json_decode($post['issued_ser_id_no_array'][$m]);
                          $se_issue_array=explode(',',$decoded_ids); 
                       }
                       $s=0;
                      foreach ($ser_array as $value) 
                      {
                         $this->db->set('issue_status',0);
                         $this->db->set('module_id',2);
                			$this->db->where('id',$se_issue_array[$s]);
                			$this->db->update('inv_stock_serial_no');    
            
                          $attr=array(
                                     'stock_id'=>$post['data_id'],
                                     'sp_id'=>$stock_item_list['item_id'],
                                     'item_id'=>$stock_item_list['item_id'],
                                     'module_id'=>4,
                                     'status'=>1,
                                     'branch_id'=>$users_data['parent_id'],
                                     'serial_no'=>$value,
                                    );
                          $this->db->insert('inv_stock_serial_no',$attr);
                         // echo $this->db->last_query();die;
                         $s++;
                      }
                    }
                    
                     $m++;
				}
            
			}
               

			

			/*add sales banlk detail*/


			$this->session->unset_userdata('stock_issue_allotment_return_item_list');
        	$this->session->unset_userdata('stock_item_payment_payment_array');
        	$purchase_id= $post['data_id'];
		}
		else
		{

            //add
			$return_code = generate_unique_id(31);
			$this->db->set('created_by',$users_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('return_no',$return_code);

			$this->db->insert('hms_stock_issue_allotment_return_item',$data_purchase);

			$purchase_id=$this->db->insert_id();
			
			/*add sales banlk detail*/
			$stock_issue_allotment_return_item_list = $this->session->userdata('stock_issue_allotment_return_item_list');
		
	        //print '<pre>'; print_r($stock_issue_allotment_return_item_list);
			if(!empty($stock_issue_allotment_return_item_list))
			{
			    $h=0;
				foreach($stock_issue_allotment_return_item_list as $stock_item_list)
				{ 
                    $stock_purchase_item = array(
						'branch_id'=>$users_data['parent_id'],
						'issue_return_id'=>$purchase_id,
						'item_id'=>$stock_item_list['item_id'],
						'per_pic_price'=>$stock_item_list['perpic_rate'],
						'unit_id'=>$stock_item_list['unit'],
						'category_id'=>$stock_item_list['category_id'],
						'total_amount'=>$stock_item_list['total_amount'],
						'qty'=>$stock_item_list['qty'],

						'mrp'=>$stock_item_list['mrp'],
						'discount'=>$stock_item_list['discount'],				
						'expiry_date'=>date('Y-m-d',strtotime($stock_item_list['exp_date'])),
						'manuf_date'=>date('Y-m-d',strtotime($stock_item_list['manuf_date'])),
						'conversion'=>$stock_item_list['conversion'],
						'sgst'=>$stock_item_list['sgst'],
						'cgst'=>$stock_item_list['cgst'],
						'igst'=>$stock_item_list['igst'],
					   );


					$this->db->insert('hms_stock_issue_allotment_return_to_issue_allotment_return',$stock_purchase_item);
                    //echo $this->db->last_query();
                    $data_new_stock=array("branch_id"=>$users_data['parent_id'],
				    	"type"=>7,
				    	"parent_id"=>$purchase_id,
				    	"item_id"=>$stock_item_list['item_id'],
				    	"credit"=>0,
				    	"debit"=>$stock_item_list['qty'],
				    	"qty"=>$stock_item_list['qty'],
				    	"price"=>$stock_item_list['mrp'],
				        'cat_type_id'=>$stock_item_list['category_id'],
				    	"item_name"=>$stock_item_list['item_name'],
				    	//"vat"=>$medicine_list['vat'],
				    	'unit_id'=>$stock_item_list['unit'],
				    	'item_code'=>$return_code,
						"total_amount"=>$stock_item_list['total_amount'],
				    	'per_pic_price'=>$stock_item_list['perpic_rate'],
				    	"created_by"=>$users_data['id'],
				    	"created_date"=>date('Y-m-d H:i:s'),
				    	);
					 $this->db->insert('path_stock_item',$data_new_stock);
                    //echo $this->db->last_query();die;	
                     if(isset($post['serial_no_array'][$h]) && !empty($post['serial_no_array'][$h]) )
                    {
                         $issued_ser_id_no_array = $post['issued_ser_id_no_array'][$h];
                       //$ser_array=explode(',',$post['serial_no_array'][$h]);
                       
                       $decoded = json_decode($post['serial_no_array'][$h]);
                       
                      $ser_array=explode(',',$decoded);
                       if(!empty($issued_ser_id_no_array))
                       {
                           $decoded_ids = json_decode($post['issued_ser_id_no_array'][$h]);
                          $se_issue_array=explode(',',$decoded_ids); 
                       }
                       $s=0;
                      foreach ($ser_array as $value) 
                      {
                         $this->db->set('issue_status',0);
                         $this->db->set('module_id',2);
                        
                			$this->db->where('id',$se_issue_array[$s]);
                			$this->db->update('inv_stock_serial_no');
                            // echo $this->db->last_query();die;
            
                          $attr=array(
                                     'stock_id'=>$purchase_id,
                                     'sp_id'=>$stock_item_list['item_id'],
                                     'item_id'=>$stock_item_list['item_id'],
                                     'module_id'=>4,
                                     'status'=>1,
                                     'branch_id'=>$users_data['parent_id'],
                                     'serial_no'=>$value,
                                    );
                          $this->db->insert('inv_stock_serial_no',$attr);
            
                          //echo $this->db->last_query();die;
                      }
                    }
                    $h++;
				}	
				//exit;
				
			}

			

		

			/* insert data in payment table  */
			$this->session->unset_userdata('stock_issue_allotment_return_item_list');
			$this->session->unset_userdata('stock_issue_allotment_return_payment_array');
        	
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
			$this->db->update('hms_stock_issue_allotment_return_item');
    	} 
		
		if(!empty($id) && $id>0)
    	{
			$users_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$users_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('parent_id',$id);
			$this->db->update('path_stock_item');
          //echo $this->db->last_query();die;		
			
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
			$this->db->update('hms_stock_issue_allotment_return_item');
    	}

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
			$this->db->where('parent_id IN ('.$branch_ids.')');
			$this->db->update('path_stock_item');
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

	

	public function vendor_list($vendor_id="",$user_type="")
	{
		$users_data = $this->session->userdata('auth_users');
		
		if(!empty($user_type))
		{
			if($user_type==2)
			{
				if(!empty($vendor_id))
				{
				$this->db->where('id',$vendor_id);	
				}
				$this->db->select('hms_patient.id,hms_patient.patient_name as name,hms_patient.address');  
				$this->db->where('branch_id = "'.$users_data['parent_id'].'"');
				$result = $this->db->get('hms_patient')->result();
				//echo $this->db->last_query();
			}
			if($user_type==1)
			{
				if(!empty($vendor_id))
				{
				$this->db->where('id',$vendor_id);	
				}
				$this->db->select('hms_employees.id,hms_employees.name,hms_employees.address');  
				$this->db->where('branch_id = "'.$users_data['parent_id'].'"');
				$result = $this->db->get('hms_employees')->result();
			}
			if($user_type==3)
			{
				if(!empty($vendor_id))
				{
				$this->db->where('id',$vendor_id);	
				}
				$this->db->select('hms_doctors.id,hms_doctors.doctor_name as name,hms_doctors.address');  
				$this->db->where('branch_id = "'.$users_data['parent_id'].'"');
				$result = $this->db->get('hms_doctors')->result();
			}
			return $result; 
		}
		
	} 

	public function get_item_values($vals="") 
	{
    	$response = '';
    	if(!empty($vals))
    	{
				$users_data = $this->session->userdata('auth_users'); 
				$this->db->select('hms_stock_issue_allotment_to_issue_allotment.*,hms_stock_issue_allotment_to_issue_allotment.per_pic_price as price,hms_stock_item_unit.unit,path_stock_category.category,path_item.item,path_item.item_code,path_item.category_id as cat_id');  
				$this->db->join('path_item','path_item.id = hms_stock_issue_allotment_to_issue_allotment.item_id');
				$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
				$this->db->join('path_stock_category`','path_stock_category.id=path_item.category_id','left');
				
				$this->db->where('path_item.item LIKE "'.$vals.'%"');
				$this->db->where('path_item.is_deleted','0');  
				$this->db->where('path_item.branch_id  IN ('.$users_data['parent_id'].')'); 
				$this->db->group_by('hms_stock_issue_allotment_to_issue_allotment.item_id');
				$this->db->from('hms_stock_issue_allotment_to_issue_allotment');
				$query = $this->db->get(); 
	            $result = $query->result(); 
	    //echo $this->db->last_query();die;
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	        		//$response[] = $vals->medicine_name;
					$name = $vals->item.'-'.$vals->category.'|'.$vals->item_code.'|'.$vals->unit.'|'.$vals->price.'|'.$vals->item_id.'|'.$vals->cat_id;
					array_push($data, $name);
	        	}
              //print_r($data);die;
	        	echo json_encode($data);
	        }
	        //return $response; 
    	} 
    }


    function get_employee($employee_type=""){
    	$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		$this->db->where('branch_id = "'.$users_data['parent_id'].'"');
		if(!empty($employee_type))
		{
		 $this->db->where('emp_type_id',$employee_type);	
		}
		$query = $this->db->get('hms_employees');

		$result = $query->result(); 
		return $result; 
    }

    function get_data_according_user($user_type="")
    {
    	$users_data = $this->session->userdata('auth_users');
		if($user_type==2)
		{
		// $this->db->select("hms_ipd_booking.*, hms_ipd_booking.patient_id as ids,hms_patient.patient_name,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.patient_code,hms_patient.status, hms_patient.age_y,hms_patient.gender,hms_patient.mobile_no,hms_patient.address"); 
		// $this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id');
		// $this->db->where('hms_patient.is_deleted','0'); 
		// $this->db->where('hms_patient.branch_id',$users_data['parent_id']);
		// $result= $this->db->get('hms_ipd_booking')->result();

		$this->db->select("hms_patient.id as ids,hms_patient.patient_name,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.patient_code,hms_patient.status, hms_patient.age_y,hms_patient.gender,hms_patient.mobile_no,hms_patient.address"); 
		//$this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id');
		$this->db->where('hms_patient.is_deleted','0'); 
		$this->db->where('hms_patient.branch_id',$users_data['parent_id']);
		$result= $this->db->get('hms_patient')->result();
		
	      }
		if($user_type==3)
		{
		$this->db->select("hms_doctors.*, hms_doctors.id as ids,hms_cities.city, hms_state.state"); 
		$this->db->join('hms_cities','hms_cities.id=hms_doctors.city_id','left');
		$this->db->join('hms_state','hms_state.id=hms_doctors.state_id','left');
		$this->db->where('hms_doctors.is_deleted','0'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		$result= $this->db->get('hms_doctors')->result();
		}
		if($user_type==1)
		{
		$this->db->select("hms_emp_type.*,hms_emp_type.id as ids,"); 
		$this->db->from('hms_emp_type'); 
		$this->db->where('hms_emp_type.is_deleted','0');
		$this->db->where('hms_emp_type.branch_id',$users_data['parent_id']);
		$result= $this->db->get()->result();
		}
		return $result; 
    }


     function get_all_detail_print($ids="",$branch_ids="")
	{
		$users_data = $this->session->userdata('auth_users');
		$result_sales=array();
    	$this->db->select("hms_stock_issue_allotment_return_item.*,(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as member_name,


    		(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.address from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.address from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.address from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as address,

    		(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.contact_no from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.mobile_no from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.mobile_no from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as mobile,

    		(CASE WHEN hms_stock_issue_allotment_return_item.user_type=1 THEN (select hms_employees.email from hms_employees where id=hms_stock_issue_allotment_return_item.user_type_id)  WHEN hms_stock_issue_allotment_return_item.user_type=2 THEN (select hms_patient.patient_email from hms_patient where id=hms_stock_issue_allotment_return_item.user_type_id) WHEN hms_stock_issue_allotment_return_item.user_type=3 THEN (select hms_doctors.email from hms_doctors where id=hms_stock_issue_allotment_return_item.user_type_id) ELSE 'N/A' END) as email,hms_users.username
    		");
		$this->db->from('hms_stock_issue_allotment_return_item'); 
		$this->db->join('hms_users','hms_users.id = hms_stock_issue_allotment_return_item.created_by');
		$this->db->where('hms_stock_issue_allotment_return_item.id',$ids);
		//$this->db->where('hms_stock_issue_allotment_return_item.id = "'.$ids.'"');
		//$this->db->where('hms_stock_issue_allotment_return_item.branch_id',$users_data['parent_id']);
		$this->db->where('hms_stock_issue_allotment_return_item.branch_id = "'.$branch_ids.'"');
		
		$this->db->where('hms_stock_issue_allotment_return_item.is_deleted','0');
		
//echo $this->db->last_query(); exit;
		$result_sales['sales_list']= $this->db->get()->result();

		/// hms_stock_issue_allotment_return_to_issue_allotment_return
		 $this->db->select('hms_stock_issue_allotment_return_to_issue_allotment_return.*, hms_stock_issue_allotment_return_to_issue_allotment_return.issue_return_id, hms_stock_issue_allotment_return_to_issue_allotment_return.category_id,hms_stock_issue_allotment_return_to_issue_allotment_return.total_amount as amount,path_item.item as item_name,path_item.item_code,hms_stock_issue_allotment_return_to_issue_allotment_return.qty as quantity,hms_stock_item_unit.unit,hms_stock_issue_allotment_return_to_issue_allotment_return.per_pic_price as item_price,path_item.id as item_id,path_stock_category.category,hms_stock_issue_allotment_return_to_issue_allotment_return.total_amount,path_item.mrp');
		$this->db->from('hms_stock_issue_allotment_return_to_issue_allotment_return'); 
		$this->db->where('hms_stock_issue_allotment_return_to_issue_allotment_return.issue_return_id',$ids);
		$this->db->where('hms_stock_issue_allotment_return_to_issue_allotment_return.branch_id',$users_data['parent_id']);
		$this->db->join('path_item','path_item.id=hms_stock_issue_allotment_return_to_issue_allotment_return.item_id','left');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=hms_stock_issue_allotment_return_to_issue_allotment_return.unit_id','left');
		$this->db->join('path_stock_category`','path_stock_category.id=hms_stock_issue_allotment_return_to_issue_allotment_return.category_id','left');
	
		//$result_sales['item_list']=$this->db->get()->result();
		$result_sales['sales_list']['item_list']=$this->db->get()->result();
//echo $this->db->last_query(); exit;
		return $result_sales;
		
    }
// add bottom 17 march 20 

     public function item_list($ids="")
    {

    	$users_data = $this->session->userdata('auth_users'); 
		$this->db->select("path_item.*,path_stock_category.category, hms_stock_item_unit.unit as unit1, hms_stock_item_unit_2.unit as unit2, hms_inventory_racks.rack_no, hms_inventory_company.company_name, path_item.price as purchase_rate, path_item.item as item_name, (path_item.price)*(path_item.conversion) as perpic_rate");

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


   //    public function check_stock_avability($id=""){
    
   //    $user_data = $this->session->userdata('auth_users');
   //    $this->db->select("path_item.*,(sum(path_stock_item.debit)-sum(path_stock_item.credit)) as qty");
   //    $this->db->where('path_item.is_deleted','0'); 
   //    $this->db->join('path_stock_item','path_stock_item.parent_id = path_item.id');
   //    $this->db->where('path_item.branch_id',$users_data['parent_id']);    
	  // $this->db->where('path_item.is_deleted','0'); 
	  // $this->db->group_by('path_item.id');
	  // $this->db->from('path_item');
   //    $this->db->where('path_item.id',$id);
   //    $query = $this->db->get('path_item');
   //      $result = $query->row(); 
   //    //echo $this->db->last_query();die;
   //       return $result->avl_qty;
    
   //  }


    public function item_list_search()
	{
		$users_data = $this->session->userdata('auth_users'); 
    	$post = $this->input->post(); 
    	
		$this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit,
		   (sum(path_stock_item.debit)-sum(path_stock_item.credit)) as qty,
			hms_inventory_racks.rack_no,hms_inventory_company.company_name"); 
        $this->db->where('path_item.is_deleted','0');
		$this->db->where('path_stock_item.is_deleted','0');
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
        $this->db->join('path_stock_item','path_stock_item.item_id=path_item.id','left');
        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');
        $this->db->join('hms_inventory_company','hms_inventory_company.id=path_item.manuf_company','left');
        $this->db->where('path_item.branch_id',$users_data['parent_id']);

      if(!empty($post['item']))
		{
		$this->db->where('path_item.item LIKE "'.$post['item'].'%"');
	
		}

		if(!empty($post['item_code']))
		{ 
		$this->db->where('path_item.item_code LIKE "'.$post['item_code'].'%"');	
		}
	
         if(!empty($post['qty']))
        {  
          $this->db->where('(SELECT (sum(debit)-sum(credit)) from path_stock_item where parent_id = path_item.id)<='.$post['qty'].' as qty');
      
        } 

		if(!empty($post['mrp']))
		{  
		$this->db->where('path_item.mrp = "'.$post['mrp'].'"');	
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
// add bottom 17 march feb 20 

  public function search_sales($vals="")
	{
	$response = '';
      if(!empty($vals))
      {	
        $user_data = $this->session->userdata('auth_users');  
		$this->db->select("hms_stock_issue_allotment.*,(CASE WHEN hms_stock_issue_allotment.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment.user_type_id)  WHEN hms_stock_issue_allotment.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment.user_type_id) WHEN hms_stock_issue_allotment.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment.user_type_id) ELSE 'N/A' END) as member_name,(CASE WHEN hms_stock_issue_allotment.user_type=1 THEN (select hms_employees.address from hms_employees where id=hms_stock_issue_allotment.user_type_id)  WHEN hms_stock_issue_allotment.user_type=2 THEN (select hms_patient.address from hms_patient where id=hms_stock_issue_allotment.user_type_id) WHEN hms_stock_issue_allotment.user_type=3 THEN (select hms_doctors.address from hms_doctors where id=hms_stock_issue_allotment.user_type_id) ELSE 'N/A' END) as address");

        $this->db->where('hms_stock_issue_allotment.is_deleted','0'); 
		$this->db->where('hms_stock_issue_allotment.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->from('hms_stock_issue_allotment'); 
		$this->db->where('hms_stock_issue_allotment.issue_no LIKE "'.$vals.'%"');
		$query = $this->db->get(); 
		$result = $query->result();

// print '<pre>'; print_r($result);die;
//echo $this->db->last_query();die;
		$saledate = explode(' ', $result[0]->issue_date);
        $days=get_setting_value('Set_Return_Medicine_Minimum_Days');

        $expire=date('Y-m-d', strtotime($saledate[0]. ' + '.$days.' days'));

        $curDate = date('Y-m-d');
		$curDate=date('Y-m-d', strtotime($curDate));
		$saleDates = date('Y-m-d', strtotime($saledate[0]));
		$saleEnd = date('Y-m-d', strtotime($expire));

		//print_r($saleEnd); die;

      if(!empty($result) && ($curDate >= $saleDates) && ($curDate <= $saleEnd))
      { 
        $data = array();
        foreach($result as $vals)
        {
         $name = $vals->issue_no.'|'.$vals->id.'|'.$vals->total_amount.'|'.$vals->discount_percent.'|'.$vals->discount.'|'.$vals->item_discount.'|'.$vals->sgst.'|'.$vals->cgst.'|'.$vals->igst.'|'.$vals->net_amount.'|'.$vals->paid_amount.'|'.$vals->balance.'|'.$vals->payment_mode.'|'.$vals->issue_date.'|'.$vals->employee_type.'|'.$vals->user_type_id.'|'.$vals->user_type.'|'.$vals->member_name.'|'.$vals->address;

      //print '<pre>'; print_r($name); 
           array_push($data, $name);
        }

        echo json_encode($data);

        }
         else{
      	   echo json_encode(array('status'=>0, 'message'=>'Item not return'));
        }

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
      $this->db->where('inv_stock_serial_no.module_id',4); //module 2 for issue
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
          $this->db->where('inv_stock_serial_no.module_id',2); //for sale only
          $this->db->where('inv_stock_serial_no.issue_status',0);
          $this->db->where('inv_stock_serial_no.serial_no LIKE "'.$vals.'%"');
          $this->db->from('inv_stock_serial_no'); 
          $query = $this->db->get(); 
          $result = $query->result(); 
         // echo $this->db->last_query(); exit;
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