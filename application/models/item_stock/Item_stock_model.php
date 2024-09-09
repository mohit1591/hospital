<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_stock_model extends CI_Model {

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
        $search= $this->session->userdata('stock_items_serach');
		$this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit,(SELECT sum(p_stock.debit)-sum(p_stock.credit) from path_stock_item as p_stock WHERE p_stock.item_id=path_item.id AND p_stock.branch_id=".$users_data['parent_id'].") as stock_qty,hms_inventory_racks.rack_no,path_stock_item.id as stockids"); 
		$this->db->from($this->table); 
       
        
        $this->db->join('path_stock_item','path_stock_item.item_id=path_item.id','left');
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');
        $this->db->where('path_item.branch_id',$users_data['parent_id']);
         $this->db->where('path_item.is_deleted','0');
		$this->db->where('path_stock_item.is_deleted','0');
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
		$users_data = $this->session->userdata('auth_users');
        $search= $this->session->userdata('stock_items_serach');
        $this->db->where('path_item.is_deleted','0');
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        //$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
      //  $this->db->join('path_stock_item','path_stock_item.item_id=path_item.id','left');
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
        
       /* $emp_ids='';
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
		}*/
       // $this->db->group_by('path_item.id');
		$this->db->from($this->table);
		//echo $this->db->last_query();die;
		return $this->db->count_all_results();
	}


	public function get_item_quantity($item_id="")
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('(sum(debit)-sum(credit)) as stock_qty');
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('path_stock_item.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('path_stock_item.branch_id',$user_data['parent_id']);
		}
		$this->db->where('path_stock_item.item_id',$item_id);
		//$this->db->join('path_purchase_item','path_purchase_item.id = path_stock_item.parent_id','left');
		//$this->db->where('path_purchase_item.is_deleted','0');
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
	


	public function search_report_data()
	{
		$users_data = $this->session->userdata('auth_users');
        $search= $this->session->userdata('stock_items_serach');
		$this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit,(SELECT sum(p_stock.debit)-sum(p_stock.credit) from path_stock_item as p_stock WHERE p_stock.item_id=path_item.id AND p_stock.branch_id=".$users_data['parent_id'].") as stock_qty,
			hms_inventory_racks.rack_no"); 
		$this->db->from($this->table); 
        $this->db->where('path_item.is_deleted','0');
		$this->db->where('path_stock_item.is_deleted','0');
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
        $this->db->join('path_stock_item','path_stock_item.item_id=path_item.id','left');
        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');
		
		$this->db->join('path_purchase_item','path_purchase_item.id = path_stock_item.parent_id','left');

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
		//$this->db->select('path_item.*');

		$this->db->select('path_item.*,hms_stock_item_unit.unit,path_stock_item.unit_id as stock_item_unit,path_stock_item.second_unit');

		$this->db->from('path_item'); 
		$this->db->where('path_item.id',$id);
		$this->db->where('path_item.is_deleted','0');
	
		$this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
		$this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');

		$query = $this->db->get(); 
		return $query->row_array();
//echo $this->db->last_query();die;	
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
					//'min_alert'=>$post['min_alert'],

	                'mrp'=>$post['mrp'],
                    //'sgst'=>$post['sgst'],
                    //'cgst'=>$post['cgst'],
                    //'igst'=>$post['igst'],
                    //'discount'=>$post['discount'],
                    'conversion'=>$post['conversion'],             
                    'packing'=>$post['packing'],
                    'rack_no'=>$post['rack_no'],


					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );



	//print_r($data);die;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{   

//print_r($post);die;

            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('path_item',$data);

			$debit = ($post['stock_item_unit']*$post['conversion'])+$post['second_unit'];
			
			$data = array( 
					
					'branch_id'=>$user_data['parent_id'],
					//'parent_id'=>$insert_id,
					'item_name'=>$post['item'],
					'parent_id'=>$post['data_id'],
					'type'=>5,
					'qty'=>'',
					//'debit'=>'',
					'credit'=>'',
					'item_code'=>$post['item_code'],
					'manuf_company'=>$post['manuf_company'],
					//'item_id'=>$insert_id,
					'item_id'=>$post['data_id'],
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
		         $data['debit']=$debit;  
               
				$this->db->set('modified_by',$user_data['id']);
			    $this->db->set('modified_date',date('Y-m-d H:i:s'));
			    $this->db->where('branch_id',$user_data['parent_id']);
			    $this->db->where('type',5);
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
			//echo $this->db->last_query();die;
		
            $debit = ($post['stock_item_unit']*$post['conversion'])+$post['second_unit'];



			$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'parent_id'=>$insert_id,
					'type'=>5,
					'qty'=>'',
					//'debit'=>'',
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
                $data['debit']=$debit;  


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
					          'type'=>5,
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
    
    public function get_item_list()
    {
    	$users_data = $this->session->userdata('auth_users');
    	$inventory_data = $this->session->userdata('alloted_inventory_ids');
        //echo "<pre>"; print_r($inventory_data); exit;
        $result = array();
    	if(!empty($inventory_data))
    	{
    		$id_list = [];
    		$batch_list = [];
    		// $l=0;
    		foreach($inventory_data as $inv)
    		{
    			
    			
				if(!empty($inv['inventory_id']) && $inv['inventory_id']>0)
			    {
                    $id_list[]  = $inv['inventory_id'];
			    }
			   
			
    		} 
    		//echo "<pre>"; print_r($batch_list); exit;
    		
    		$inventory_ids = implode(',', $id_list);
    		//$batch_nos = implode(',', $batch_list);
    		
    		    $i=1; $or='';
        		$where1='';
        		$tota = count($batch_list);
        		foreach($batch_list as $value)
        		{
        				if($i==$tota)
        				{
        					$or="";
        				}
        				else
        				{
        					$or=",";	
        				}
        				$where1.="'$value'".$or;
        				$i++;
        		}
    		    $this->db->select("path_item.*,path_stock_item.item_id");
		    	$this->db->from('path_item');
		
	            $this->db->join('path_stock_item','path_item.id=path_stock_item.item_id');
	          
	            $this->db->where('path_item.id IN ('.$inventory_ids.')');
	            $this->db->where('path_stock_item.item_id IN ('.$inventory_ids.')');
	            $this->db->where('(path_item.is_deleted=0 and path_stock_item.is_deleted=0)');
		    	$this->db->where('(path_item.branch_id='.$users_data['parent_id'].' and path_stock_item.branch_id='.$users_data['parent_id'].')');
		    	$this->db->group_by('path_stock_item.item_id');
		    	$query = $this->db->get();
		        //echo $this->db->last_query();die;
		    	$result = $query->result_array();
		   
    	}
    	return $result;
    }
    
    public function get_batch_med_qty($mid="",$batch_no="")
	{
		$user_data = $this->session->userdata('auth_users');
		$search=$this->session->userdata('stock_search');

		/*  code for deleted record */
		$p_ids=array();
		$p_r_ids=array();
		$s_ids=array();
		$s_r_ids=array();
		$new_s_r_ids='';
		$new_s_ids='';
		$new_p_r_ids='';
		$new_p_ids='';
		$where_1='';
		$where_2='';
		$where_3='';
		$where_4='';
		//$deleted_purchase_item= $this->is_deleted_purchase_item();
		//$deleted_purchase_return_item= $this->is_deleted_purchase_return_item();
		//$deleted_sale_item= $this->is_deleted_sale_item();
		//$deleted_sale_return_item= $this->is_deleted_sale_return_item();
		$this->db->select("(sum(debit)-sum(credit)) as total_qty");
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('branch_id',$user_data['parent_id']);
		}
		/*if(!empty($deleted_purchase_item))
		{
		 foreach($deleted_purchase_item as $purchase_ids)
		 {
		 	$p_ids[]=$purchase_ids['id'];
		 }
		 $new_p_ids=implode(',',$p_ids);
		 $this->db->where("path_stock_item.parent_id NOT IN(".$new_p_ids.")");	
		}*/
		 
		
		/*if(!empty($deleted_purchase_return_item))
		{
			foreach($deleted_purchase_return_item as $purchase_r_ids)
			{
				$p_r_ids[]=$purchase_r_ids['id'];
			}
				$new_p_r_ids=implode(',',$p_r_ids);
				$this->db->where("path_stock_item.parent_id NOT IN(".$new_p_r_ids.")");
		}*/
		
	
		/*if(!empty($deleted_sale_item))
		{
			foreach($deleted_sale_item as $sale_ids)
			{
				$s_ids[]=$sale_ids['id'];
			}
			$new_s_ids=implode(',',$s_ids);
			$this->db->where("path_stock_item.parent_id NOT IN(".$new_s_ids.")");
		}*/
		
	
		/*if(!empty($deleted_sale_return_item))
		{
			foreach($deleted_sale_return_item as $sale_r_ids)
			{
				$s_r_ids[]=$sale_r_ids['id'];
			}
				$new_s_r_ids=implode(',',$s_r_ids);
				$this->db->where("path_stock_item.parent_id NOT IN(".$new_s_r_ids.")");
		}*/
		/*  code for deleted record */
		
		$this->db->where('item_id',$mid);
		$search=$this->session->userdata('stock_search');
		
	
		
	
		$this->db->group_by('path_stock_item.item_id');
		$query = $this->db->get('path_stock_item');
		//echo $this->db->last_query(); exit;
		return $query->row_array();

	}
	
	
	
	public function allot_item_to_branch()
    {
    	$post = $this->input->post();
    	//echo "<pre>"; print_r($post); exit;
    	$users_data = $this->session->userdata('auth_users'); 
		if(isset($post) && !empty($post))
    	{
            $h=0;
    		foreach($post['medicine'] as $mid=>$medicine)
    		{
    			//sub_branch_id
				$this->db->select("path_item.*,hms_inventory_company.company_name,path_stock_item.*,hms_stock_item_unit.*");
				$this->db->from('path_item');
				$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left'); 
				$this->db->join('path_stock_item','path_item.id=path_stock_item.item_id','left');
				$this->db->join('hms_stock_item_unit','path_item.unit_id=hms_stock_item_unit.id','left');
			
				$this->db->where('path_item.id',$mid); 
				$this->db->where('path_item.branch_id='.$users_data['parent_id']);  
				$this->db->group_by('path_stock_item.item_id');
				$query = $this->db->get(); 
				//echo $this->db->last_query(); exit;
				$medicine_data = $query->result_array();
            
                
                ///////// Check Unit  //////////////
                $this->db->select("hms_stock_item_unit.*");
				$this->db->from('hms_stock_item_unit'); 
				$this->db->where('hms_stock_item_unit.id',$medicine_data[0]['unit_id']); 
				$this->db->where('hms_stock_item_unit.branch_id='.$post['sub_branch_id']);  
				$query = $this->db->get(); 
				$medicine_unit_data = $query->result_array();
				
				if(!empty($medicine_unit_data))
				{
					$m_unit_id = $medicine_unit_data[0]['id'];
				}
				else
				{
					$medicine_unit_data = array(
						'branch_id'=>$post['sub_branch_id'],
						'unit'=>$medicine_data[0]['unit'],
						'status'=>$medicine_data[0]['status'],
						'is_deleted'=>0,
						'deleted_by'=>$medicine_data[0]['deleted_by'],
						'deleted_date'=>$medicine_data[0]['deleted_date'],
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'created_by'=>$users_data['parent_id'],
						'created_date'=>date('Y-m-d H:i:s')
						);
					$this->db->insert('hms_stock_item_unit',$medicine_unit_data);
					$m_unit_id = $this->db->insert_id();
				}

				///////// check Unit Second ////////
                $this->db->select("hms_stock_item_unit.*");
				$this->db->from('hms_stock_item_unit'); 
				$this->db->where('hms_stock_item_unit.id',$medicine_data[0]['second_unit']); 
				$this->db->where('hms_stock_item_unit.branch_id='.$post['sub_branch_id']);  
				$query = $this->db->get(); 
				$medicine_unit_data = $query->result_array();
				if(!empty($medicine_unit_data))
				{
					$m_sec_unit_id = $medicine_unit_data[0]['id'];
				}
				else
				{
					$medicine_unit_data = array(
						'branch_id'=>$post['sub_branch_id'],
						'unit'=>$medicine_data[0]['unit'],
						'status'=>$medicine_data[0]['status'],
						'is_deleted'=>0,
						'deleted_by'=>$medicine_data[0]['deleted_by'],
						'deleted_date'=>$medicine_data[0]['deleted_date'],
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'created_by'=>$users_data['parent_id'],
						 'created_date'=>date('Y-m-d H:i:s')
						);
					$this->db->insert('hms_stock_item_unit',$medicine_unit_data);
					$m_sec_unit_id = $this->db->insert_id();
				}



				///////// Check Company ////////////
				$m_company_id =0;
                if(!empty($medicine_data[0]['company_name']))
                {
                	$this->db->select("hms_inventory_company.*");
					$this->db->from('hms_inventory_company'); 
					$this->db->where('hms_inventory_company.company_name',$medicine_data[0]['company_name']); 
					$this->db->where('hms_inventory_company.branch_id='.$post['sub_branch_id']);  
					$query = $this->db->get(); 
					$medicine_company_data = $query->result_array();
					if(!empty($medicine_company_data))
					{
						$m_company_id = $medicine_company_data[0]['id'];
					}
					else
					{
							$medicine_company_data = array(
							'branch_id'=>$post['sub_branch_id'],
							'company_name'=>$medicine_data[0]['company_name'],
							'status'=>$medicine_data[0]['status'],
							'is_deleted'=>0,
							'deleted_by'=>$medicine_data[0]['deleted_by'],
							'deleted_date'=>$medicine_data[0]['deleted_date'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s')
							);
							$this->db->insert('hms_inventory_company',$medicine_company_data);
							$m_company_id = $this->db->insert_id();
					}
                }
				////////////////////////////////////
    		    ///////// Check Medicine //////////
                $this->db->select("path_item.*");
				$this->db->from('path_item'); 
				$this->db->where('path_item.item',$medicine_data[0]['item']);
				//$this->db->where('hms_inventory_company.id',$m_company_id); 
				$this->db->where('path_item.branch_id='.$post['sub_branch_id']);  
				$query = $this->db->get(); 
				//echo $this->db->last_query(); exit;
				$checked_medicine_company_data = $query->result_array();

				if(!empty($checked_medicine_company_data))
				{
					$item_id = $checked_medicine_company_data[0]['id'];
				}
				else
				{
					$new_add_medicine = array(
					'branch_id'=>$post['sub_branch_id'],
					'item_code'=>$medicine_data[0]['item_code'],
					'item'=>$medicine_data[0]['item'],
					'unit_id'=>$m_unit_id,
					'second_unit'=>$m_sec_unit_id,
					'conversion'=>$medicine_data[0]['conversion'],
					'min_alert'=>$medicine_data[0]['min_alert'],
					'packing'=>$medicine_data[0]['packing'], 
					'qty'=>0,
					'manuf_company'=>$m_company_id,
					'mrp'=>$medicine_data[0]['mrp'],
					
					'discount'=>$medicine_data[0]['discount'],
					'status'=>1,
					'is_deleted'=>0,
					'deleted_date'=>date('Y-m-d H:i:s'),
					'deleted_by'=>0,
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					'created_by'=>$users_data['id'],  
					'created_date'=>date('Y-m-d H:i:s'),

					);
					
					$this->db->insert('path_item',$new_add_medicine);
					//echo $this->db->last_query(); exit;
					$item_id = $this->db->insert_id();
				} 
    		    ///////////////////////////////////
    		    
    		    /////////medicine allocate by branch //////////
				$data_allot_by_branch = array( 
				'branch_id'=>$users_data['parent_id'],
                'type'=>5,
                'parent_id'=>$post['sub_branch_id'],
                'item_id'=>$mid,
                'credit'=>$medicine['qty'],
				'debit'=>'0',
				
				//'bar_code'=>$medicine_data[0]['bar_code'],
				//'conversion'=>$medicine_data[0]['conversion'],
				'is_deleted'=>0,
				//'deleted_date'=>$medicine_data[0]['deleted_date'],
				'deleted_by'=>$medicine_data[0]['deleted_by'],
				'ip_address'=>$_SERVER['REMOTE_ADDR'],
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
				'created_by'=>$users_data['parent_id'],
				'price'=>$medicine_data[0]['price'],
				//'discount'=>$medicine_data[0]['discount'],
				//'vat'=>$medicine_data[0]['vat'],
				'total_amount'=>$medicine_data[0]['total_amount'],
				//'expiry_date'=>$medicine_data[0]['expiry_date'],
				//'manuf_date'=>$medicine_data[0]['manuf_date'],
				'per_pic_price'=>$medicine_data[0]['per_pic_price'],
				);
               
                $this->db->insert('path_stock_item',$data_allot_by_branch);
                //echo $this->db->last_query(); exit;
                 /////////medicine allocate to branch //////////
				$data_allot_to_branch = array( 
				'branch_id'=>$post['sub_branch_id'],
                'type'=>5,
                'parent_id'=>$users_data['parent_id'],
                'item_id'=>$item_id,
                'credit'=>'0',
				'debit'=>$medicine['qty'],
				//'batch_no'=>$medicine['batch_no'],
				//'bar_code'=>$medicine_data[0]['bar_code'],
				//'conversion'=>$medicine_data[0]['conversion'],
				'is_deleted'=>0,
				//'deleted_date'=>$medicine_data[0]['deleted_date'],
				'deleted_by'=>$medicine_data[0]['deleted_by'],
				'ip_address'=>$_SERVER['REMOTE_ADDR'],
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
				'created_by'=>$users_data['parent_id'],
				'price'=>$medicine_data[0]['price'],
				//'discount'=>$medicine_data[0]['discount'],
				//'vat'=>$medicine_data[0]['vat'],
				'total_amount'=>$medicine_data[0]['total_amount'],
				//'expiry_date'=>$medicine_data[0]['expiry_date'],
				//'manuf_date'=>$medicine_data[0]['manuf_date'],
				'per_pic_price'=>$medicine_data[0]['per_pic_price'],
				);
				
				$this->db->insert('path_stock_item',$data_allot_to_branch);
				$insert_stock_id = $this->db->insert_id();
				
				if(isset($post['serial_no_array'][$h]) && !empty($post['serial_no_array'][$h]) )
                    {
                        
                        $issued_ser_id_no_array = $post['issued_ser_id_no_array'][$h];
                       
                       $decoded = json_decode($post['serial_no_array'][$h]);
                       //print '<pre>'; print_r($decoded); die;
                       $ser_array=explode(',',$decoded);
                       //echo "<pre>"; print_r($ser_array); exit;
                       if(!empty($issued_ser_id_no_array))
                       {
                           $decoded_ids = json_decode($post['issued_ser_id_no_array'][$h]);
                          $se_issue_array=explode(',',$decoded_ids); 
                       }
                       $s=0;
                      foreach ($ser_array as $value) 
                      {
                          
                            $this->db->set('issue_status',1);
                			$this->db->where('id',$se_issue_array[$s]);
                			$this->db->update('inv_stock_serial_no');
            
                          $attr=array(
                                     'stock_id'=>$insert_stock_id,
                                     'sp_id'=>$item_id,
                                     'module_id'=>7,
                                     'item_id'=>$item_id,
                                     'status'=>1,
                                     'branch_id'=>$post['sub_branch_id'],
                                     'serial_no'=>$value,
                                    );
                          $this->db->insert('inv_stock_serial_no',$attr);
            
                          //echo $this->db->last_query();die;
                          $s++;
                      }
                    }
                    $h++;
    		    //echo $this->db->last_query(); exit;
    		    ///////// End batch stock ////////////////////
    		}
    		
             $this->session->unset_userdata('alloted_inventory_ids');
		
    		
            
    	}
    }
    
    
    
    
    ///////////
    function get_item_allot_history_datatables($item_id="",$type="",$branch_id='')
    {
    	
	    $this->_get_item_allot_history_datatables_query($item_id,$type,$branch_id);
	    if($_POST['length'] != -1)
	    $this->db->limit($_POST['length'], $_POST['start']);
	    $query = $this->db->get(); 
	   // echo $this->db->last_query();die;
	    return $query->result();
	    
    }
    private function _get_item_allot_history_datatables_query($item_id="0",$type="1",$branch_id='')
    {
		$users_data = $this->session->userdata('auth_users');
		if(!empty($type) && $type==1) //purchase
		{
			$this->db->select("path_purchase_item_to_purchase.*,hms_branch.branch_name,path_purchase_item.vendor_id,path_purchase_item.purchase_no as purchase_order_id,path_purchase_item.created_date as purchase_date,hms_medicine_vendors.name as vendor_name,hms_inventory_company.company_name,path_item.item,path_item.item_code"); 
			$this->db->from('path_purchase_item_to_purchase');   
			
			$this->db->join('path_purchase_item','path_purchase_item.id = path_purchase_item_to_purchase.purchase_id','left'); 
			$this->db->join('hms_branch','path_purchase_item.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_medicine_vendors','path_purchase_item.vendor_id=hms_medicine_vendors.id','left'); 
			
			$this->db->join('path_item','path_purchase_item_to_purchase.item_id=path_item.id','left');
			
			$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left');

			$this->db->where('path_purchase_item.branch_id',$users_data['parent_id']);
			$this->db->where('path_purchase_item_to_purchase.item_id',$item_id);
			
			$this->db->where('path_purchase_item.is_deleted',0);
			$this->db->where('path_purchase_item.is_deleted',0);
			$this->db->order_by('path_purchase_item.id','DESC');
			//echo $this->db->last_query(); 
			
		}
		if(!empty($type) && $type==2) //purchase return
		{
			$this->db->select("path_purchase_return_item_purchase_return.*,hms_branch.branch_name,path_purchase_return_item.vendor_id,path_purchase_return_item.return_no as purchase_order_id,path_purchase_return_item.created_date as purchase_date,hms_medicine_vendors.name as vendor_name,hms_inventory_company.company_name,path_item.item,path_item.item_code"); 
			$this->db->from('path_purchase_return_item_purchase_return');   
			
			$this->db->join('path_purchase_return_item','path_purchase_return_item.id = path_purchase_return_item_purchase_return.return_id','left'); 
			$this->db->join('hms_branch','path_purchase_return_item.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_medicine_vendors','path_purchase_return_item.vendor_id=hms_medicine_vendors.id','left'); 
			
			$this->db->join('path_item','path_purchase_return_item_purchase_return.item_id=path_item.id','left');
			
			$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left');

			$this->db->where('path_purchase_return_item.branch_id',$users_data['parent_id']);
			$this->db->where('path_purchase_return_item_purchase_return.item_id',$item_id);
			$this->db->where('path_purchase_return_item.is_deleted',0);
			$this->db->where('path_purchase_return_item.is_deleted',0);
			$this->db->order_by('path_purchase_return_item.id','DESC');
			//echo $this->db->last_query();
		}
		if(!empty($type) && $type==3) //issue/allot	
		{
		    //path_purchase_return_item_purchase_return.
			$this->db->select("hms_stock_issue_allotment_to_issue_allotment.*,hms_branch.branch_name,hms_stock_issue_allotment.user_type_id,hms_stock_issue_allotment.created_date as purchase_date,hms_patient.patient_name as vendor_name,(select company_name from hms_inventory_company as cmpny where cmpny.id = path_item.manuf_company) as company_name,path_item.item,path_item.item_code"); 
			$this->db->from('hms_stock_issue_allotment_to_issue_allotment');   
			
			$this->db->join('hms_stock_issue_allotment','hms_stock_issue_allotment.id = hms_stock_issue_allotment_to_issue_allotment.issue_return_id','left'); 
			$this->db->join('hms_branch','hms_stock_issue_allotment.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_stock_issue_allotment.user_type_id=hms_patient.id','left'); 
			
			$this->db->join('path_item','hms_stock_issue_allotment_to_issue_allotment.item_id=path_item.id','left');
			
		

			$this->db->where('hms_stock_issue_allotment.branch_id',$users_data['parent_id']);
			$this->db->where('hms_stock_issue_allotment_to_issue_allotment.item_id',$item_id);
			$this->db->where('hms_stock_issue_allotment.is_deleted',0);
			$this->db->order_by('hms_stock_issue_allotment.id','DESC');
			//echo $this->db->last_query();
		}

		if(!empty($type) && $type==4) //allot return
		{
			$this->db->select("hms_stock_issue_allotment_return_to_issue_allotment_return.*,hms_branch.branch_name,hms_stock_issue_allotment_return_item.user_type_id,hms_stock_issue_allotment_return_item.user_type_id as purchase_order_id,hms_stock_issue_allotment_return_item.created_date as purchase_date,hms_patient.patient_name as vendor_name,(select company_name from hms_inventory_company as cmpny where cmpny.id = path_item.manuf_company) as company_name,path_item.item,path_item.item_code"); 
			$this->db->from('hms_stock_issue_allotment_return_to_issue_allotment_return');   
			
			$this->db->join('hms_stock_issue_allotment_return_item','hms_stock_issue_allotment_return_item.id = hms_stock_issue_allotment_return_to_issue_allotment_return.issue_return_id','left'); 
			$this->db->join('hms_branch','hms_stock_issue_allotment_return_item.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_stock_issue_allotment_return_item.user_type_id=hms_patient.id','left'); 
			
			$this->db->join('path_item','hms_stock_issue_allotment_return_to_issue_allotment_return.item_id=path_item.id','left');
			
			
			$this->db->where('hms_stock_issue_allotment_return_item.branch_id',$users_data['parent_id']);
			$this->db->where('hms_stock_issue_allotment_return_to_issue_allotment_return.item_id',$item_id);
			$this->db->where('hms_stock_issue_allotment_return_item.is_deleted',0);
			$this->db->order_by('hms_stock_issue_allotment_return_item.id','DESC');
		}
		if(!empty($type) && $type==5) //Branch allot
		{
			$this->db->select('path_item.*,hms_inventory_company.company_name,path_stock_item.credit as quantity,path_stock_item.created_date,path_stock_item.parent_id,hms_branch.branch_name');
			$this->db->from('path_item');
			$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left');

			$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');

			$this->db->join('hms_branch','path_stock_item.parent_id=hms_branch.id','left');
			$this->db->where('path_stock_item.branch_id',$users_data['parent_id']);
			$this->db->where('path_stock_item.type',$type);
			$this->db->where('path_stock_item.credit > 0');  
			$this->db->where('path_stock_item.item_id',$item_id);
			$this->db->order_by('path_stock_item.id','DESC');

			
		}
    }


    function get_item_allot_history_count_filtered($item_id="",$type='',$branch_id='')
	{
		$this->_get_item_allot_history_datatables_query($item_id,$type,$branch_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_item_allot_history_count_all($item_id="",$type='',$branch_id='')
	{
		$this->_get_item_allot_history_datatables_query($item_id,$type,$branch_id);
		$query = $this->db->get();
		return $query->num_rows();

	}
	
	function purchase_item_serial_by_id($item_id="",$stockids="")
  {
      $user_data=$this->session->userdata('auth_users');
      $this->db->select('inv_stock_serial_no.*');
      $this->db->where('inv_stock_serial_no.item_id',$item_id);
      $this->db->where('inv_stock_serial_no.is_deleted',0);
      $this->db->where('inv_stock_serial_no.branch_id',$user_data['parent_id']);
      $this->db->where('inv_stock_serial_no.issue_status',0);
      /*if(!empty($stockids))
      {
        $this->db->where('inv_stock_serial_no.stock_id',$stockids);
      }*/
      
      $this->db->where('(inv_stock_serial_no.module_id=1 OR inv_stock_serial_no.module_id=6 OR inv_stock_serial_no.module_id=7)'); //module 2 for purchase
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
          $this->db->where('(inv_stock_serial_no.module_id=1 OR inv_stock_serial_no.module_id=6)');
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