<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_manage_model extends CI_Model {

	var $table = 'path_item';
	var $column = array('path_item.item_code','path_item.item','path_item.price','path_stock_category.category','path_item.qty','hms_stock_item_unit.unit','path_item.status','path_item.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $search= $this->session->userdata('item_manage_serach');
		
		$this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit,sum(path_stock_item.debit-path_stock_item.credit)as stock_qty,hms_inventory_racks.rack_no"); 
		$this->db->from($this->table); 
        $this->db->where('path_item.is_deleted','0');
        $this->db->where('path_stock_item.type',3);
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
        $this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');

        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');
        
        $this->db->where('path_item.branch_id',$users_data['parent_id']);
        

        if(!empty($search))
        {

        	if(isset($search['item_code']) && !empty($search['item_code']))
        	{
               $this->db->where('path_item.item_code = "'.$search['item_code'].'"');
        	}
        	if(isset($search['item_name']) && !empty($search['item_name']))
        	{
        		$this->db->where('path_item.item LIKE "%'.$search['item_name'].'%"');
        	}
        	if(isset($search['category']) && !empty($search['category']))
        	{
        		$this->db->where('path_stock_category.category LIKE "%'.$search['category'].'%"');
        	}
			if(isset($search['rack_no']) && !empty($search['rack_no']))
			{
			$this->db->where('hms_inventory_racks.rack_no LIKE "%'.$search['rack_no'].'%"');
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
			$this->db->where('path_item.created_by IN ('.$emp_ids.')');
		}
        $this->db->group_by('path_item.id');
		
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

	public function get_item_quantity($item_id="",$category_id="")
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('(sum(debit)-sum(credit)) as total_qty');
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('branch_id',$user_data['parent_id']);
		}
		//$this->db->where('branch_id',$user_data['parent_id']);
		
		$this->db->where('item_id',$item_id);	
		//$this->db->where('cat_type_id',$category_id);	
		$query = $this->db->get('path_stock_item');
		//echo $this->db->last_query();die;
		return $query->row_array();

	}
	public function get_stock_item_list()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_stock_item_unit.*');
		$this->db->from('hms_stock_item_unit'); 
		$this->db->where('hms_stock_item_unit.branch_id',$user_data['parent_id']);
		$this->db->where('hms_stock_item_unit.parent_id',0);
		$this->db->where('hms_stock_item_unit.is_deleted','0');
		$query = $this->db->get()->result(); 
		return $query;
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
		$this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit,sum(path_stock_item.debit-path_stock_item.credit)as stock_qty, hms_inventory_racks.rack_no"); 
		$this->db->from($this->table); 
        $this->db->where('path_item.is_deleted','0');
        $this->db->where('path_stock_item.type',3);
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
        $this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');

        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');

        $this->db->where('path_item.branch_id',$users_data['parent_id']);
		
		if(!empty($search))
		{
		if(isset($search['item_code']) && !empty($search['item_code']))
		{
		$this->db->where('path_item.item_code = "'.$search['item_code'].'"');
		}
		if(isset($search['item_name']) && !empty($search['item_name']))
		{
		$this->db->where('path_item.item LIKE "%'.$search['item_name'].'%"');
		}
		if(isset($search['category']) && !empty($search['category']))
		{
		$this->db->where('path_stock_category.category LIKE "%'.$search['category'].'%"');
		}

		if(isset($search['rack_no']) && !empty($search['rack_no']))
		{
		$this->db->where('hms_inventory_racks.rack_no LIKE "%'.$search['rack_no'].'%"');
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
			$this->db->where('path_item.created_by IN ('.$emp_ids.')');
		}
        $this->db->group_by('path_item.id');
		$result=$this->db->get()->result();
		
			return $result;
	}
    
    public function category_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('item','ASC'); 
    	$query = $this->db->get('path_item');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('path_item.*');
		//$this->db->select('path_item.*,hms_stock_item_unit.unit,hms_second_unit.unit as second_unit,hms_second_unit.id as second_id');

		$this->db->from('path_item'); 
		$this->db->where('path_item.id',$id);
		$this->db->where('path_item.is_deleted','0');
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
		$this->db->join('hms_stock_item_unit as hms_second_unit','hms_second_unit.id=path_item.second_unit','left');

		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{

		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'item'=>$post['item'],
					//'item_code'=>$post['item_code'],
					'manuf_company'=>$post['manuf_company'],
					'price'=>$post['item_price'],
					'category_id'=>$post['category_id'],
					'second_unit'=>$post['second_unit'],
					//'qty_with_second_unit'=>$qty_with_second_unit,
					'unit_id'=>$post['stock_item_unit'],
					'qty'=>'',
					'min_alert'=>$post['min_alert'],

	                'mrp'=>$post['mrp'],
                    'sgst'=>$post['sgst'],
                    'cgst'=>$post['cgst'],
                    'igst'=>$post['igst'],
                    'discount'=>$post['discount'],
                    'conversion'=>$post['conversion'],
                    'packing'=>$post['packing'],
                    'rack_no'=>$post['rack_no'],


					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		//print_r($data);die;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('path_item',$data);
			
			$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'parent_id'=>$post['data_id'],
					'type'=>3,
					'qty'=>'',
					'debit'=>'',
					'credit'=>'',
					'item_id'=>$post['data_id'],
					'manuf_company'=>$post['manuf_company'],
					'unit_id'=>$post['stock_item_unit'],
					'per_pic_price'=>$post['item_price'],
					'second_unit'=>$post['second_unit'],
					//'qty_with_second_unit'=>$qty_with_second_unit,
					'price'=>$post['item_price'],
					'total_amount'=>$post['item_price'],
					'cat_type_id'=>$post['category_id'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );   
               
				$this->db->set('modified_by',$user_data['id']);
			    $this->db->set('modified_date',date('Y-m-d H:i:s'));
			    $this->db->where('branch_id',$user_data['parent_id']);
			    $this->db->where('type',3);
				$this->db->where('parent_id',$post['data_id']);
				$this->db->update('path_stock_item',$data);  
//echo $this->db->last_query();die;


		}
		else
		{   
			$item_code = generate_unique_id(33); 
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('item_code',$item_code);
			$this->db->insert('path_item',$data);   
			$insert_id= $this->db->insert_id();
		
// echo $this->db->last_query();die;

			$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'parent_id'=>$insert_id,
					'type'=>3,
					'qty'=>'',
					'debit'=>'',
					'credit'=>'',
					'item_code'=>$item_code,
					'manuf_company'=>$post['manuf_company'],
					'item_id'=>$insert_id,
					'unit_id'=>$post['stock_item_unit'],
					'per_pic_price'=>$post['item_price'],
					'second_unit'=>$post['second_unit'],
					//'qty_with_second_unit'=>$qty_with_second_unit,
					'price'=>$post['item_price'],
					'total_amount'=>$post['item_price'],
					'cat_type_id'=>$post['category_id'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );   

				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('path_stock_item',$data); 

 //echo $this->db->last_query();die;


		} 	
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
			$this->db->update('path_item');
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
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('path_item');
			//echo $this->db->last_query();die;
    	} 
    }

	public function stock_item_unit_list()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('status',1); 
		$this->db->where('is_deleted',0); 
		$this->db->where('hms_stock_item_unit.parent_id','0'); 
		$this->db->order_by('unit','ASC'); 
		$query = $this->db->get('hms_stock_item_unit');
		return $query->result();
	}

	// public function get_sub_unit($unit_id='')
	// {
	// 	$user_data = $this->session->userdata('auth_users');
	// 	$this->db->select('hms_stock_item_unit.*,hms_stock_item_unit.unit as first_unit,hms_stock_second_unit.unit as second_unit,hms_stock_second_unit.id as second_id');
	// 	$this->db->where('hms_stock_item_unit.branch_id',$user_data['parent_id']);
	// 	$this->db->where('hms_stock_item_unit.status',1); 
	// 	$this->db->where('hms_stock_item_unit.is_deleted',0); 
	// 	$this->db->where('hms_stock_item_unit.id',$unit_id);
	// 	$this->db->join('hms_stock_item_unit as hms_stock_second_unit','hms_stock_second_unit.id=hms_stock_item_unit.parent_id');
	// 	$this->db->order_by('unit','ASC'); 
	// 	$query = $this->db->get('hms_stock_item_unit');

	//     return $query->result();
	//  //echo $this->db->last_query();die;

	// }
	public function get_sub_unit($unit_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_stock_item_unit.*,(CASE WHEN hms_stock_item_unit.parent_id>0 THEN unit.unit ELSE hms_stock_item_unit.unit END) as second_unit, (CASE WHEN hms_stock_item_unit.parent_id=0 THEN 'N/A' ELSE hms_stock_item_unit.unit END) as first_unit");
		$this->db->join('hms_stock_item_unit as unit','unit.id=hms_stock_item_unit.parent_id','left');
		$this->db->where('hms_stock_item_unit.branch_id',$user_data['parent_id']);
		$this->db->where('hms_stock_item_unit.status',1); 
		$this->db->where('hms_stock_item_unit.is_deleted',0); 
		$this->db->where('hms_stock_item_unit.parent_id',$unit_id);
		//$this->db->join('hms_stock_item_unit as hms_stock_second_unit','hms_stock_second_unit.id=hms_stock_item_unit.parent_id');
		$this->db->order_by('unit','ASC'); 
		$query = $this->db->get('hms_stock_item_unit');
		 return $query->result();
	 //echo $this->db->last_query();die;

	}


	public function save_all_item($item_list = array())
	{
		
		$users_data = $this->session->userdata('auth_users');
        if(!empty($item_list))
        {
            foreach($item_list as $opening_item_list)
            {
            	    ///////////////Unit /////////////////////////
					$unit_id='';
            		if(!empty($opening_item_list['unit_id']))
            		{
		            	$this->db->select('hms_stock_item_unit.*'); 
				        $this->db->where('hms_stock_item_unit.branch_id',$users_data['parent_id']);
				        $this->db->where('LOWER(hms_stock_item_unit.unit)',strtolower($opening_item_list['unit_id']));
				        $this->db->where('hms_stock_item_unit.parent_id','0');
				        $this->db->where('hms_stock_item_unit.is_deleted','0'); 
				        $query = $this->db->get('hms_stock_item_unit');
				        $item_unit_data = $query->result_array();

					    if(!empty($item_unit_data))
					    {
						    $unit_id = $item_unit_data[0]['id'];
					    }
					    else
					    {
							$item_unit_data = array(
								'branch_id'=>$users_data['parent_id'],
								'parent_id'=>0,
								'unit'=>$opening_item_list['unit_id'],
								'ip_address'=>$_SERVER['REMOTE_ADDR'],
								'created_by'=>$users_data['parent_id'],
								'status'=>1,
								'created_date'=>date('Y-m-d H:i:s')
								);
							$this->db->insert('hms_stock_item_unit',$item_unit_data);
							$unit_id = $this->db->insert_id();
					    }
					}

					///////////////Unit  End /////////////////////
					//unit 2 start
					$second_unit='';
            		if(!empty($opening_item_list['second_unit']) && !empty($unit_id))
            		{
		            	$this->db->select('hms_stock_item_unit.*'); 
				        $this->db->where('hms_stock_item_unit.parent_id',$unit_id);
				        $this->db->where('hms_stock_item_unit.branch_id',$users_data['parent_id']);
				        $this->db->where('LOWER(hms_stock_item_unit.unit)',strtolower($opening_item_list['second_unit']));
				        $this->db->where('hms_stock_item_unit.parent_id','0');
				        $this->db->where('hms_stock_item_unit.is_deleted','0'); 
				        $query = $this->db->get('hms_stock_item_unit');
				        $second_item_unit_data = $query->result_array();

					    if(!empty($second_item_unit_data))
					    {
						    $second_unit = $second_item_unit_data[0]['id'];
					    }
					    else
					    {
							$second_item_unit_data = array(
								'branch_id'=>$users_data['parent_id'],
								'parent_id'=>$unit_id,
								'unit'=>$opening_item_list['second_unit'],
								'ip_address'=>$_SERVER['REMOTE_ADDR'],
								'created_by'=>$users_data['parent_id'],
								'status'=>1,
								'created_date'=>date('Y-m-d H:i:s')
								);
							$this->db->insert('hms_stock_item_unit',$second_item_unit_data);
							$second_unit = $this->db->insert_id();
					    }
					}

					///////////////Unit 2 End ///////////////

					/////////// Check category ////////////
            		$category_id='';
            		if(!empty($opening_item_list['category_id']))
            		{
		            	$this->db->select('path_stock_category.*'); 
		            	$this->db->from('path_stock_category');
				        $this->db->where('path_stock_category.branch_id',$users_data['parent_id']);
				        $this->db->where('LOWER(path_stock_category.category)',strtolower($opening_item_list['category_id']));
				        $this->db->where('path_stock_category.is_deleted','0'); 
				        $query = $this->db->get();
				        //echo $this->db->last_query(); exit;
				        $category_data = $query->result_array();

					    if(!empty($category_data))
					    {
						    $category_id = $category_data[0]['id'];
					    }
					    else
					    {
							$category_data = array(
							'branch_id'=>$users_data['parent_id'],
							'category'=>$opening_item_list['category_id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'status'=>1,
							'created_date'=>date('Y-m-d H:i:s')
							);
							$this->db->insert('path_stock_category',$category_data);
							$category_id = $this->db->insert_id();
					    }
					}
					//echo $category_id; exit;
				    ///////////////// category ///////////////////


				    //////////Check item Name////////////////
					if(!empty($category_id))
					{
						$this->db->select('path_item.*');
						$this->db->from('path_item');
		            	$this->db->where('path_item.branch_id',$users_data['parent_id']);
		            	$this->db->where('LOWER(path_item.item)',strtolower($opening_item_list['item']));
		            	$this->db->where('path_item.category_id',$category_id);
		            	$query = $this->db->get();
						$item_entry_data = $query->result_array();

						//if item and category found
	                    if(!empty($item_entry_data))
	                    {	
	                    	$item_entry_id = $item_entry_data[0]['id'];
	                    	$item_code = $item_entry_data[0]['item_code'];

	                    	if(isset($second_unit))
					        {
					          $second_id=$second_unit;
					        }
					        else
					        {
					          $second_id='';
					        }
					        if(isset($opening_item_list['qty_with_second_unit']))
					        {
					          $qty_with_second_unit=$opening_item_list['qty_with_second_unit'];
					        }
					        else
					        {
					          $qty_with_second_unit='';
					        }	
	                    }
	                    else
	                    {
	                    	//if item not found on that category start
							$item_code = generate_unique_id(33);
		                    if(isset($second_unit))
					        {
					          $second_id=$second_unit;
					        }
					        else
					        {
					          $second_id='';
					        }
					        if(isset($opening_item_list['qty_with_second_unit']))
					        {
					          $qty_with_second_unit=$opening_item_list['qty_with_second_unit'];
					        }
					        else
					        {
					          $qty_with_second_unit='';
					        }
					        $data_item = array( 
					              'branch_id'=>$users_data['parent_id'],
					              'item'=>$opening_item_list['item'],
					              'item_code'=>$item_code,
					              'price'=>$opening_item_list['price'],
					              'category_id'=>$category_id,
					              'second_unit'=>$second_id,
					              //'qty_with_second_unit'=>$qty_with_second_unit,
					              'unit_id'=>$unit_id,
					              'qty'=>$opening_item_list['qty'],
					              'min_alert'=>$opening_item_list['min_alert'],
					              'status'=>1,
					              'ip_address'=>$_SERVER['REMOTE_ADDR']
					             ); 


					      $this->db->set('created_by',$users_data['id']);
					      $this->db->set('created_date',date('Y-m-d H:i:s'));
					      $this->db->insert('path_item',$data_item);   
					      $item_entry_id= $this->db->insert_id();

					      //if item not found on that category start
						}
					}
					else
					{
						//if category id not found then check item and 
						$this->db->select('path_item.*');
						$this->db->from('path_item');
		            	$this->db->where('path_item.branch_id',$users_data['parent_id']);
		            	$this->db->where('LOWER(path_item.item)',strtolower($opening_item_list['item']));
		            	$this->db->where('path_item.category_id=""');
		            	$query = $this->db->get();
						$item_entry_data = $query->result_array();
						//item already added 
						if(!empty($item_entry_data))
						{
							$item_entry_id = $item_entry_data[0]['id'];
	                    	$item_code = $item_entry_data[0]['item_code'];

	                    	if(isset($second_unit))
					        {
					          $second_id=$second_unit;
					        }
					        else
					        {
					          $second_id='';
					        }
					        if(isset($opening_item_list['qty_with_second_unit']))
					        {
					          $qty_with_second_unit=$opening_item_list['qty_with_second_unit'];
					        }
					        else
					        {
					          $qty_with_second_unit='';
					        }
						}
						else
						{
								//insert a new item in database
								$item_code = generate_unique_id(33);
	                    
			                    if(isset($second_unit))
						        {
						          $second_id=$second_unit;
						        }
						        else
						        {
						          $second_id='';
						        }
						        if(isset($opening_item_list['qty_with_second_unit']))
						        {
						          $qty_with_second_unit=$opening_item_list['qty_with_second_unit'];
						        }
						        else
						        {
						          $qty_with_second_unit='';
						        }
						        $data_item = array( 
						              'branch_id'=>$users_data['parent_id'],
						              'item'=>$opening_item_list['item'],
						              'item_code'=>$item_code,
						              'price'=>$opening_item_list['price'],
						              'category_id'=>$category_id,
						              'second_unit'=>$second_id,
						              'qty_with_second_unit'=>$qty_with_second_unit,
						              'unit_id'=>$unit_id,
						              'qty'=>$opening_item_list['qty'],
						              'min_alert'=>$opening_item_list['min_alert'],
						              'status'=>1,
						              'ip_address'=>$_SERVER['REMOTE_ADDR']
						             ); 


						      $this->db->set('created_by',$users_data['id']);
						      $this->db->set('created_date',date('Y-m-d H:i:s'));
						      $this->db->insert('path_item',$data_item);   
						      $item_entry_id= $this->db->insert_id();


						}
						
					}


				      $stock_item_data = array( 
					          'branch_id'=>$users_data['parent_id'],
					          'parent_id'=>$item_entry_id,
					          'type'=>3,
					          'qty'=>$opening_item_list['qty'],
					          'debit'=>$opening_item_list['qty'],
					          'credit'=>'',
					          'item_code'=>$item_code,
					          'item_id'=>$item_entry_id,
					          'unit_id'=>$unit_id,
					          'per_pic_price'=>$opening_item_list['price'],
					          'second_unit'=>$second_id,
					          'qty_with_second_unit'=>$qty_with_second_unit,
					          'price'=>$opening_item_list['price'],
					          'total_amount'=>$opening_item_list['price']*$opening_item_list['qty'],
					          'cat_type_id'=>$category_id,
					          'status'=>1,
					          'ip_address'=>$_SERVER['REMOTE_ADDR']
					          );   

					        $this->db->set('created_by',$users_data['id']);
					        $this->db->set('created_date',date('Y-m-d H:i:s'));
					        $this->db->insert('path_stock_item',$stock_item_data);
	          	
			        }
				}
			  }


    public function manuf_company_list($company_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        if(!empty($company_id)){
        	$this->db->where('id',$company_id);
        }
        $this->db->where('status','1'); 
        $this->db->order_by('company_name','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('hms_inventory_company.branch_id', $users_data['parent_id']);
        $query = $this->db->get('hms_inventory_company');
        $result = $query->result(); 
       // echo $this->db->last_query();die;
        return $result; 
    }

    public function rack_list($rack_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
         if(!empty($rack_id)){
        	$this->db->where('id',$rack_id);
        }
        $this->db->where('status','1'); 
        $this->db->order_by('rack_no','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('hms_inventory_racks.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_inventory_racks');
        $result = $query->result(); 
        return $result; 
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
 
}
?>