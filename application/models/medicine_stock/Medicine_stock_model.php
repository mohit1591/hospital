<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Medicine_stock_model extends CI_Model 
{
	var $table = 'hms_medicine_stock';
	var $column = array('hms_medicine_entry.id','hms_medicine_entry.medicine_code', 'hms_medicine_entry.medicine_name', 'hms_medicine_entry.unit_id','hms_medicine_entry.unit_second_id','hms_medicine_entry.conversion','hms_medicine_entry.min_alrt','hms_medicine_entry.packing','hms_medicine_entry.rack_no','hms_medicine_entry.salt','hms_medicine_entry.manuf_company','hms_medicine_entry.mrp','hms_medicine_entry.purchase_rate','hms_medicine_entry.discount','hms_medicine_entry.vat','hms_medicine_entry.status', 'hms_medicine_entry.created_date', 'hms_medicine_entry.modified_date');  
	var $order = array('hms_medicine_entry.id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search=$this->session->userdata('stock_search');
		//print '<pre>';print_r($search);die;
		if($search['type']==2 && isset($search['type']))
		{
			$this->db->select('hms_medicine_stock.*, hms_medicine_stock.bar_code as barcode, sum(hms_medicine_stock.debit)- sum(hms_medicine_stock.credit) as stock_qty,hms_medicine_stock.created_date as stock_created_date, hms_medicine_racks.rack_no as rack_nu, hms_medicine_entry.*,hms_medicine_entry.id as m_eid,hms_medicine_entry.created_date as create,hms_medicine_company.company_name');
		}
		else
		{
			$this->db->select('hms_medicine_stock.*,hms_medicine_stock.bar_code as barcode, sum(hms_medicine_stock.debit)- sum(hms_medicine_stock.credit) as stock_qty, hms_medicine_stock.created_date as stock_created_date, hms_medicine_racks.rack_no as rack_nu, hms_medicine_entry.*,hms_medicine_entry.id as m_eid,hms_medicine_entry.created_date as create,hms_medicine_company.company_name');
		}
		$this->db->join('hms_medicine_stock','hms_medicine_stock.m_id = hms_medicine_entry.id  AND hms_medicine_stock.is_deleted=0','left');   
		//m_eid
		//$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.medicine_id = hms_medicine_stock.m_id','left'); 

		$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
		$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)>0');
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		//$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		//$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		//$this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_medicine_entry.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		$this->db->from('hms_medicine_entry');  
		if($search['type']==2 && isset($search['type']))
		{
			$this->db->group_by('hms_medicine_stock.m_id'); 
			
		}
		else
		{
			$this->db->group_by('hms_medicine_stock.batch_no');
			$this->db->group_by('hms_medicine_stock.m_id');
		}
		$this->db->order_by('hms_medicine_stock.id','DESC');
	
		$i = 0;


		if(isset($search) && !empty($search))
		{
		/*if(isset($search['start_date']) && !empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
		$this->db->where('hms_medicine_stock.created_date >= "'.$start_date.'"');
		}

		if(isset($search['end_date']) && !empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
		$this->db->where('hms_medicine_stock.created_date <= "'.$end_date.'"');
		}*/

		if(isset($search['medicine_name']) && !empty($search['medicine_name']))
		{
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
		}

		if(isset($search['medicine_company']) && !empty($search['medicine_company']))
		{
			 
			//$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			$this->db->where('hms_medicine_company.id',$search['medicine_company']);
		}

		if(isset($search['medicine_code']) && !empty($search['medicine_code']))
		{
		
			$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
		}
		if (!empty($search['expiry_to'])) {
			$expiry_to = date('Y-m-d', strtotime($search['expiry_to']));
			$this->db->where('hms_medicine_stock.expiry_date >=', $expiry_to);
		}
		
		if (!empty($search['expiry_from'])) {
			$expiry_from = date('Y-m-d', strtotime($search['expiry_from']));
			$this->db->where('hms_medicine_stock.expiry_date <=', $expiry_from);
		}


		if(isset($search['batch_no']) && !empty($search['batch_no']))
		{
			$this->db->where('hms_medicine_stock.batch_no = "'.$search['batch_no'].'"');
		}

		if(isset($search['unit1']) && $search['unit1']!="")
		{
			$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
		}

		if(isset($search['unit2']) && $search['unit2']!="")
		{
			$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
		}

		if(isset($search['packing']) && $search['packing']!="")
		{
		
		//$this->db->where('packing',$search['packing']);
			$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
		}

	/*	if(isset($search['qty_to']) &&  $search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)>='.$search['qty_to']);
		
		}
		if(isset($search['qty_from']) && $search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)<='.$search['qty_from']);
		}*/
		
		if(isset($search['qty_from']) && $search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock stock where stock.batch_no = hms_medicine_stock.batch_no AND stock.m_id = hms_medicine_entry.id)<='.$search['qty_from']);
		}

		if(isset($search['qty_to']) &&  $search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock stock_to where stock_to.batch_no = hms_medicine_stock.batch_no AND  stock_to.m_id = hms_medicine_entry.id)>='.$search['qty_to']);
			
		}


		if(isset($search['price_to_mrp']) && $search['price_to_mrp']!="")
		{
			$this->db->where('hms_medicine_entry.mrp >="'.$search['price_to_mrp'].'"');
		}

		if(isset($search['price_from_mrp']) && $search['price_from_mrp']!="")
		{
			$this->db->where('hms_medicine_entry.mrp <="'.$search['price_to_mrp'].'"');
		}

		if(isset($search['price_to_purchase']) && $search['price_to_purchase']!="")
		{
			$this->db->where('hms_medicine_entry.purchase_rate >= "'.$search['price_to_purchase'].'"');
		}


		if(isset($search['price_from_purchase']) && $search['price_from_purchase']!="")
		{
			$this->db->where('hms_medicine_entry.purchase_rate <="'.$search['price_to_purchase'].'"');
		}

		if(isset($search['rack_no']) && $search['rack_no']!="")
		{
			//$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
			$this->db->where('hms_medicine_racks.rack_no',$search['rack_no']);
			
		}
		if(isset($search['min_alert']) && $search['min_alert']!="")
		{
			$this->db->where('hms_medicine_entry.min_alrt',$search['min_alert']);
		}
             

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
			$this->db->where('hms_medicine_entry.created_by IN ('.$emp_ids.')');
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

	function search_report_data()
	{
		$user_data = $this->session->userdata('auth_users');
		$search=$this->session->userdata('stock_search');
        if($search['type']==2 && isset($search['type']))
		{
			$this->db->select('hms_medicine_stock.*, hms_medicine_stock.bar_code as barcode, sum(hms_medicine_stock.debit)- sum(hms_medicine_stock.credit) as stock_qty,hms_medicine_stock.created_date as stock_created_date, hms_medicine_racks.rack_no as rack_nu, hms_medicine_entry.*,hms_medicine_entry.id as m_eid,hms_medicine_entry.created_date as create,hms_medicine_company.company_name');
		}
		else
		{
			$this->db->select('hms_medicine_stock.*,hms_medicine_stock.bar_code as barcode, sum(hms_medicine_stock.debit)- sum(hms_medicine_stock.credit) as stock_qty, hms_medicine_stock.created_date as stock_created_date, hms_medicine_racks.rack_no as rack_nu, hms_medicine_entry.*,hms_medicine_entry.id as m_eid,hms_medicine_entry.created_date as create,hms_medicine_company.company_name');
		}
		$this->db->join('hms_medicine_stock','hms_medicine_stock.m_id = hms_medicine_entry.id  AND hms_medicine_stock.is_deleted=0','left');   
		//m_eid
		//$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.medicine_id = hms_medicine_stock.m_id','left'); 

		$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
		$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)>0');
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		//$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		//$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		//$this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_medicine_entry.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		$this->db->from('hms_medicine_entry');  
		if($search['type']==2 && isset($search['type']))
		{
			$this->db->group_by('hms_medicine_stock.m_id'); 
			
		}
		else
		{
			$this->db->group_by('hms_medicine_stock.batch_no');
			$this->db->group_by('hms_medicine_stock.m_id');
		}
		$this->db->order_by('hms_medicine_stock.id','DESC');
	
		$i = 0;


		if(isset($search) && !empty($search))
		{
		/*if(isset($search['start_date']) && !empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
		$this->db->where('hms_medicine_stock.created_date >= "'.$start_date.'"');
		}

		if(isset($search['end_date']) && !empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
		$this->db->where('hms_medicine_stock.created_date <= "'.$end_date.'"');
		}*/

		if(isset($search['medicine_name']) && !empty($search['medicine_name']))
		{
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
		}

		if(isset($search['medicine_company']) && !empty($search['medicine_company']))
		{
			 
			//$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
			$this->db->where('hms_medicine_company.id',$search['medicine_company']);
		}

		if(isset($search['medicine_code']) && !empty($search['medicine_code']))
		{
		
			$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
		}
		if(isset($search['expiry_to']) && !empty($search['expiry_to']))
		{
		    $expiry_to = date('Y-m-d',strtotime($search['expiry_to']));
			$this->db->where('hms_medicine_stock.expiry_date >="'.$expiry_to.'"');
		}
		if(isset($search['expiry_from']) && !empty($search['expiry_from']))
		{
		    $expiry_from = date('Y-m-d',strtotime($search['expiry_from']));
			$this->db->where('hms_medicine_stock.expiry_date <="'.$expiry_from.'"');
		}


		if(isset($search['batch_no']) && !empty($search['batch_no']))
		{
			$this->db->where('hms_medicine_stock.batch_no = "'.$search['batch_no'].'"');
		}

		if(isset($search['unit1']) && $search['unit1']!="")
		{
			$this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
		}

		if(isset($search['unit2']) && $search['unit2']!="")
		{
			$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
		}

		if(isset($search['packing']) && $search['packing']!="")
		{
		
		//$this->db->where('packing',$search['packing']);
			$this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
		}

	/*	if(isset($search['qty_to']) &&  $search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)>='.$search['qty_to']);
		
		}
		if(isset($search['qty_from']) && $search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)<='.$search['qty_from']);
		}*/
		
		if(isset($search['qty_from']) && $search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock stock where stock.batch_no = hms_medicine_stock.batch_no AND stock.m_id = hms_medicine_entry.id)<='.$search['qty_from']);
		}

		if(isset($search['qty_to']) &&  $search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock stock_to where stock_to.batch_no = hms_medicine_stock.batch_no AND  stock_to.m_id = hms_medicine_entry.id)>='.$search['qty_to']);
			
		}


		if(isset($search['price_to_mrp']) && $search['price_to_mrp']!="")
		{
			$this->db->where('hms_medicine_entry.mrp >="'.$search['price_to_mrp'].'"');
		}

		if(isset($search['price_from_mrp']) && $search['price_from_mrp']!="")
		{
			$this->db->where('hms_medicine_entry.mrp <="'.$search['price_to_mrp'].'"');
		}

		if(isset($search['price_to_purchase']) && $search['price_to_purchase']!="")
		{
			$this->db->where('hms_medicine_entry.purchase_rate >= "'.$search['price_to_purchase'].'"');
		}


		if(isset($search['price_from_purchase']) && $search['price_from_purchase']!="")
		{
			$this->db->where('hms_medicine_entry.purchase_rate <="'.$search['price_to_purchase'].'"');
		}

		if(isset($search['rack_no']) && $search['rack_no']!="")
		{
			//$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
			$this->db->where('hms_medicine_racks.rack_no',$search['rack_no']);
			
		}
		if(isset($search['min_alert']) && $search['min_alert']!="")
		{
			$this->db->where('hms_medicine_entry.min_alrt',$search['min_alert']);
		}
             

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
			$this->db->where('hms_medicine_entry.created_by IN ('.$emp_ids.')');
		}
		
		 $query = $this->db->get(); 

		$data= $query->result();
		
		return $data;
        //$this->db->group_by('hms_medicine_stock.id');
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

	/*public function get_batch_med_qty($mid="",$batch_no="")
	{
		$user_data = $this->session->userdata('auth_users');
		$search=$this->session->userdata('stock_search');
		$this->db->select('(sum(debit)-sum(credit)) as total_qty');
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('branch_id',$user_data['parent_id']);
		}
		//$this->db->where('branch_id',$user_data['parent_id']); 
		$this->db->where('m_id',$mid);
		$this->db->where('batch_no',$batch_no);
		$query = $this->db->get('hms_medicine_stock');
		//echo $this->db->last_query();
		return $query->row_array();

	}*/

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
		$deleted_purchase_medicine= $this->is_deleted_purchase_medicine();
		$deleted_purchase_return_medicine= $this->is_deleted_purchase_return_medicine();
		$deleted_sale_medicine= $this->is_deleted_sale_medicine();
		$deleted_sale_return_medicine= $this->is_deleted_sale_return_medicine();
		$this->db->select("(sum(debit)-sum(credit)) as total_qty");
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('branch_id',$user_data['parent_id']);
		}
		if(!empty($deleted_purchase_medicine))
		{
		 foreach($deleted_purchase_medicine as $purchase_ids)
		 {
		 	$p_ids[]=$purchase_ids['id'];
		 }
		 $new_p_ids=implode(',',$p_ids);
		 $this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_p_ids.")");	
		}
		 
		
		if(!empty($deleted_purchase_return_medicine))
		{
			foreach($deleted_purchase_return_medicine as $purchase_r_ids)
			{
				$p_r_ids[]=$purchase_r_ids['id'];
			}
				$new_p_r_ids=implode(',',$p_r_ids);
				$this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_p_r_ids.")");
		}
		
	
		if(!empty($deleted_sale_medicine))
		{
			foreach($deleted_sale_medicine as $sale_ids)
			{
				$s_ids[]=$sale_ids['id'];
			}
			$new_s_ids=implode(',',$s_ids);
			$this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_s_ids.")");
		}
		
	
		if(!empty($deleted_sale_return_medicine))
		{
			foreach($deleted_sale_return_medicine as $sale_r_ids)
			{
				$s_r_ids[]=$sale_r_ids['id'];
			}
				$new_s_r_ids=implode(',',$s_r_ids);
				$this->db->where("hms_medicine_stock.parent_id NOT IN(".$new_s_r_ids.")");
		}
		/*  code for deleted record */
		
		$this->db->where('m_id',$mid);
		$search=$this->session->userdata('stock_search');
		
		if(!isset($search['type'])) //for reset of all the value
		{
		   if(!empty($batch_no))
    		{
    		    $this->db->where('batch_no',$batch_no);
    		}
    		else
    		{
    		    $this->db->where('batch_no','0');
    		    //$this->db->or_where('batch_no','0');
    		}     
		}
		
		if($search['type']==1 && isset($search['type']))
		{
		    /*if(!empty($batch_no))
    		{
    		    $this->db->where('batch_no',$batch_no);
    		} */
    		
    		if(!empty($batch_no))
    		{
    		    $this->db->where('batch_no',$batch_no);
    		}
    		elseif($batch_no=='0')
    		{
    		    $this->db->where('batch_no','0');
    		    //$this->db->or_where('batch_no','0');
    		}
    		else
    		{
    		   $this->db->where('batch_no',''); 
    		}
		}
		$this->db->group_by('hms_medicine_stock.batch_no,hms_medicine_stock.m_id');
		$query = $this->db->get('hms_medicine_stock');
		/*$user_data = $this->session->userdata('auth_users');
		if($user_data['parent_id']=='110')
		{
		    echo $this->db->last_query();die;
		}*/
		//echo $this->db->last_query();die;
		return $query->row_array();

	}

	public function get_by_id($id)
	{
		$this->db->select("hms_medicine_entry.*, hms_medicine_company.company_name, hms_medicine_unit.medicine_unit"); 
		$this->db->from('hms_medicine_entry'); 
		$this->db->where('hms_medicine_entry.id',$id);
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		$this->db->join('hms_medicine_company','hms_medicine_company.id=hms_medicine_entry.manuf_company','left');
		$this->db->join('hms_medicine_racks','hms_medicine_racks.id=hms_medicine_entry.rack_no','left');
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


	public function save()
	{
		$reg_no = generate_unique_id(10); 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
				$data = array(
				"medicine_code"=>$reg_no,
				"medicine_name"=>$post['medicine_name'],
				'branch_id'=>$user_data['parent_id'],
				"unit_id"=>$post['unit_id'],
				"unit_second_id"=>$post['unit_second_id'],
				"conversion"=>$post['conversion'],
				"min_alrt"=>$post['min_alrt'],
				"packing"=>$post['packing'],
				"rack_no"=>$post['rack_no'],
				"salt"=>$post['salt'],
				"manuf_company"=>$post['manuf_company'],
				"mrp"=>$post['mrp'],
				"purchase_rate"=>$post['purchase_rate'],
				"discount"=>$post['discount'],
				"vat"=>$post['vat'],
				"status"=>$post['status']
			); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_medicine_entry',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_medicine_entry',$data);               
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
			$this->db->update('hms_medicine_entry');
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
			$this->db->update('hms_medicine_entry');
    	} 
    }

    public function medicine_entry_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_entry');
        $result = $query->result(); 
        return $result; 
    } 

     public function unit_second_list($unit_second_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
         if(!empty($unit_second_id)){
        	$this->db->where('id',$unit_second_id);
        }
        $this->db->order_by('medicine_unit','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_medicine_unit');
        $result = $query->result(); 
        return $result; 
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
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_medicine_racks');
        $result = $query->result(); 
        return $result; 
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
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_medicine_company');
        $result = $query->result(); 
        return $result; 
    }


    public function get_medicine_list()
    {
    	$users_data = $this->session->userdata('auth_users');
    	$medicine_data = $this->session->userdata('alloted_medicine_ids');
        //echo "<pre>"; print_r($medicine_data); exit;
        $result = array();
    	if(!empty($medicine_data))
    	{
    		$id_list = [];
    		$batch_list = [];
    		// $l=0;
    		foreach($medicine_data as $medicine)
    		{
    			
    			
				if(!empty($medicine['medicine_id']) && $medicine['medicine_id']>0)
			    {
                    $id_list[]  = $medicine['medicine_id'];
			    }
			   
			
			
				if(!empty($medicine['batch_no']) )
			    {
                    $batch_list[]  = $medicine['batch_no'];
			    }
    			   
    		
    			 // $l++;
    		} 
    		//echo "<pre>"; print_r($batch_list); exit;
    		
    		$medicine_ids = implode(',', $id_list);
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
    		
          /* $i=1; $or='';
           $where1='';
           foreach($batch_nos_array as $value)
           {
            if($i>1)
            {
              $or=' OR ';
            }
            $where1.=$or. "hms_medicine_stock.batch_no='$value'" ;
        
            $i++;
          }
         echo $where1;*/
          //$this->db->where('('.$where1.')');
    		
    	 // echo "<pre>"; print_r($batch_nos); exit;
	    	   $this->db->select("hms_medicine_entry.*,hms_medicine_stock.batch_no,hms_medicine_stock.m_id");
		    	$this->db->from('hms_medicine_entry');
		
	            $this->db->join('hms_medicine_stock','hms_medicine_entry.id=hms_medicine_stock.m_id');
	          
	            $this->db->where('hms_medicine_entry.id IN ('.$medicine_ids.')');
	            $this->db->where('hms_medicine_stock.m_id IN ('.$medicine_ids.')');
	            if($batch_nos!='0'){
	                $this->db->where('hms_medicine_stock.batch_no IN ('.$where1.')');
	               //$this->db->where('('.$where1.')');
	            }else{
	            	$this->db->where('hms_medicine_stock.batch_no',0);
	            }
	            $this->db->where('(hms_medicine_entry.is_deleted=0 and hms_medicine_stock.is_deleted=0)');
		    	$this->db->where('(hms_medicine_entry.branch_id='.$users_data['parent_id'].' and hms_medicine_stock.branch_id='.$users_data['parent_id'].')');
		    	$this->db->group_by('hms_medicine_stock.m_id');
		    	$query = $this->db->get();
		        //echo $this->db->last_query();die;
		    	$result = $query->result_array();
		   
    	}
    	return $result;
    }

    public function allot_medicine_to_branch()
    {
    	$post = $this->input->post();
    	$users_data = $this->session->userdata('auth_users'); 
		if(isset($post) && !empty($post))
    	{

    		foreach($post['medicine'] as $mid=>$medicine)
    		{
    			//sub_branch_id
				$this->db->select("hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_stock.*,hms_medicine_unit.*");
				$this->db->from('hms_medicine_entry');
				$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left'); 
				$this->db->join('hms_medicine_stock','hms_medicine_entry.id=hms_medicine_stock.m_id','left');
				$this->db->join('hms_medicine_unit','hms_medicine_entry.unit_id=hms_medicine_unit.id','left');
				$this->db->where('hms_medicine_stock.batch_no',$post['medicine'][$mid]['batch_no']);
				$this->db->where('hms_medicine_entry.id',$mid); 
				$this->db->where('hms_medicine_entry.branch_id='.$users_data['parent_id']);  
				$this->db->group_by('hms_medicine_stock.m_id');
				$query = $this->db->get(); 
				$medicine_data = $query->result_array();
            
                
                ///////// Check Unit  //////////////
                $this->db->select("hms_medicine_unit.*");
				$this->db->from('hms_medicine_unit'); 
				$this->db->where('hms_medicine_unit.id',$medicine_data[0]['unit_id']); 
				$this->db->where('hms_medicine_unit.branch_id='.$post['sub_branch_id']);  
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
						'medicine_unit'=>$medicine_data[0]['medicine_unit'],
						'status'=>$medicine_data[0]['status'],
						'is_deleted'=>$medicine_data[0]['is_deleted'],
						'deleted_by'=>$medicine_data[0]['deleted_by'],
						'deleted_date'=>$medicine_data[0]['deleted_date'],
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'created_by'=>$users_data['parent_id'],
						'created_date'=>date('Y-m-d H:i:s')
						);
					$this->db->insert('hms_medicine_unit',$medicine_unit_data);
					$m_unit_id = $this->db->insert_id();
				}

				///////// check Unit Second ////////
                $this->db->select("hms_medicine_unit.*");
				$this->db->from('hms_medicine_unit'); 
				$this->db->where('hms_medicine_unit.id',$medicine_data[0]['unit_second_id']); 
				$this->db->where('hms_medicine_unit.branch_id='.$post['sub_branch_id']);  
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
						'medicine_unit'=>$medicine_data[0]['medicine_unit'],
						'status'=>$medicine_data[0]['status'],
						'is_deleted'=>$medicine_data[0]['is_deleted'],
						'deleted_by'=>$medicine_data[0]['deleted_by'],
						'deleted_date'=>$medicine_data[0]['deleted_date'],
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'created_by'=>$users_data['parent_id'],
						 'created_date'=>date('Y-m-d H:i:s')
						);
					$this->db->insert('hms_medicine_unit',$medicine_unit_data);
					$m_sec_unit_id = $this->db->insert_id();
				}



				///////// Check Company ////////////
				$m_company_id =0;
                if(!empty($medicine_data[0]['company_name']))
                {
                	$this->db->select("hms_medicine_company.*");
					$this->db->from('hms_medicine_company'); 
					$this->db->where('hms_medicine_company.company_name',$medicine_data[0]['company_name']); 
					$this->db->where('hms_medicine_company.branch_id='.$post['sub_branch_id']);  
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
							'is_deleted'=>$medicine_data[0]['is_deleted'],
							'deleted_by'=>$medicine_data[0]['deleted_by'],
							'deleted_date'=>$medicine_data[0]['deleted_date'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s')
							);
							$this->db->insert('hms_medicine_company',$medicine_company_data);
							$m_company_id = $this->db->insert_id();
					}
                }
				////////////////////////////////////
    		    ///////// Check Medicine //////////
                $this->db->select("hms_medicine_entry.*");
				$this->db->from('hms_medicine_entry'); 
				$this->db->where('hms_medicine_entry.medicine_name',$medicine_data[0]['medicine_name']);
				$this->db->where('hms_medicine_entry.manuf_company',$m_company_id); 
				$this->db->where('hms_medicine_entry.branch_id='.$post['sub_branch_id']);  
				$query = $this->db->get(); 
				$checked_medicine_company_data = $query->result_array();

				if(!empty($checked_medicine_company_data))
				{
					$med_id = $checked_medicine_company_data[0]['id'];
				}
				else
				{
					$new_add_medicine = array(
					'branch_id'=>$post['sub_branch_id'],
					'medicine_code'=>$medicine_data[0]['medicine_code'],
					'medicine_name'=>$medicine_data[0]['medicine_name'],
					'unit_id'=>$m_unit_id,
					'unit_second_id'=>$m_sec_unit_id,
					'conversion'=>$medicine_data[0]['conversion'],
					'min_alrt'=>$medicine_data[0]['min_alrt'],
					'packing'=>$medicine_data[0]['packing'], 
					'salt'=>$medicine_data[0]['salt'],
					'manuf_company'=>$m_company_id,
					'mrp'=>$medicine_data[0]['mrp'],
					'bar_code'=>$medicine_data[0]['bar_code'],
					// 'purchase_rate'=>$medicine_data[0]['purchase_rate'],
					// 'vat'=>$medicine_data[0]['vat'],
					'discount'=>$medicine_data[0]['discount'],
					'status'=>1,
					'is_deleted'=>0,
					'deleted_date'=>date('Y-m-d H:i:s'),
					'deleted_by'=>0,
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					'created_by'=>$users_data['id'],  
					'created_date'=>date('Y-m-d H:i:s'),

					);
					if(!empty($medicine_data[0]['purchase_rate'])){
					    $new_add_medicine['purchase_rate'] = $medicine_data[0]['purchase_rate'];
				    }else{
					    $new_add_medicine['purchase_rate'] ='00.00';
				    }
					$this->db->insert('hms_medicine_entry',$new_add_medicine);
					//echo $this->db->last_query(); exit;
					$med_id = $this->db->insert_id();
				} 
    		    ///////////////////////////////////
    		    
    		    /////////medicine allocate by branch //////////
				$data_allot_by_branch = array( 
				'branch_id'=>$users_data['parent_id'],
                'type'=>5,
                'parent_id'=>$post['sub_branch_id'],
                'm_id'=>$mid,
                'credit'=>$medicine['qty'],
				'debit'=>'0',
				'batch_no'=>$medicine['batch_no'],
				'bar_code'=>$medicine_data[0]['bar_code'],
				'conversion'=>$medicine_data[0]['conversion'],
				'is_deleted'=>$medicine_data[0]['is_deleted'],
				'deleted_date'=>$medicine_data[0]['deleted_date'],
				'deleted_by'=>$medicine_data[0]['deleted_by'],
				'ip_address'=>$_SERVER['REMOTE_ADDR'],
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
				'created_by'=>$users_data['parent_id'],
				'mrp'=>$medicine_data[0]['mrp'],
				'discount'=>$medicine_data[0]['discount'],
				'vat'=>$medicine_data[0]['vat'],
				'total_amount'=>$medicine_data[0]['total_amount'],
				'expiry_date'=>$medicine_data[0]['expiry_date'],
				'manuf_date'=>$medicine_data[0]['manuf_date'],
				'per_pic_rate'=>$medicine_data[0]['per_pic_rate'],
				);
                if(!empty($medicine_data[0]['purchase_rate'])){
					$data_allot_by_branch['purchase_rate'] = $medicine_data[0]['purchase_rate'];
				}else{
					$data_allot_by_branch['purchase_rate'] ='00.00';
				}
                $this->db->insert('hms_medicine_stock',$data_allot_by_branch);
                //echo $this->db->last_query(); exit;
                 /////////medicine allocate to branch //////////
				$data_allot_to_branch = array( 
				'branch_id'=>$post['sub_branch_id'],
                'type'=>5,
                'parent_id'=>$users_data['parent_id'],
                'm_id'=>$med_id,  //'m_id'=>$med_id, it was wrong
                'credit'=>'0',
				'debit'=>$medicine['qty'],
				'batch_no'=>$medicine['batch_no'],
				'bar_code'=>$medicine_data[0]['bar_code'],
				'conversion'=>$medicine_data[0]['conversion'],
				'is_deleted'=>$medicine_data[0]['is_deleted'],
				'deleted_date'=>$medicine_data[0]['deleted_date'],
				'deleted_by'=>$medicine_data[0]['deleted_by'],
				'ip_address'=>$_SERVER['REMOTE_ADDR'],
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
				'created_by'=>$users_data['parent_id'],
				'mrp'=>$medicine_data[0]['mrp'],
				'discount'=>$medicine_data[0]['discount'],
				'vat'=>$medicine_data[0]['vat'],
				'total_amount'=>$medicine_data[0]['total_amount'],
				'expiry_date'=>$medicine_data[0]['expiry_date'],
				'manuf_date'=>$medicine_data[0]['manuf_date'],
				'per_pic_rate'=>$medicine_data[0]['per_pic_rate'],
				);
				if(!empty($medicine_data[0]['purchase_rate'])){
					$data_allot_to_branch['purchase_rate'] = $medicine_data[0]['purchase_rate'];
				}else{
					$data_allot_to_branch['purchase_rate'] ='00.00';
				}
				
				$this->db->insert('hms_medicine_stock',$data_allot_to_branch);
    		    ///////////////////////////////////////

    		    ////////// Batch Stock start ////////////////
                $this->db->select('hms_medicine_batch_stock.id, hms_medicine_entry.discount,hms_medicine_batch_stock.quantity');
				$this->db->join('hms_medicine_entry', 'hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id', 'left');
				$this->db->where('hms_medicine_batch_stock.medicine_id',$mid);
				$this->db->where('hms_medicine_batch_stock.branch_id',$users_data['parent_id']);
				$this->db->where('hms_medicine_batch_stock.batch_no',$medicine['batch_no']);
				$query_batch_stock_master = $this->db->get('hms_medicine_batch_stock'); 
				$master_batch_stock = $query_batch_stock_master->row_array();
				//echo "<pre>"; print_r($master_batch_stock); die;
                if(!empty($master_batch_stock))
                {
                    
                   $mster_qty = $master_batch_stock['quantity']-$medicine['qty'];
                   $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = '".$mster_qty."' WHERE `hms_medicine_batch_stock`.`id` = '".$master_batch_stock['id']."' ");
                   //echo $this->db->last_query(); exit;
                }
                else
                {
                	$this->db->select('sum(hms_medicine_stock.debit)- sum(hms_medicine_stock.credit) as stock_qty');
                	$this->db->where('hms_medicine_stock.m_id',$mid);
					$this->db->where('hms_medicine_stock.branch_id',$users_data['parent_id']);
					$this->db->where('hms_medicine_stock.batch_no',$medicine['batch_no']);
					$query_batch_stock_master2 = $this->db->get('hms_medicine_stock'); 
				    $master_batch_stock2 = $query_batch_stock_master2->row_array();
				    //echo $this->db->last_query();die;
				    //echo '<pre>'; print_r($master_batch_stock2);die;
				    $total_qty = ($master_batch_stock2['stock_qty']+$medicine['qty'])-$medicine['qty'];
                    $batch_stock_data = array(
                           'branch_id'=>$users_data['parent_id'],
                           'medicine_id'=>$mid,
                           'batch_no'=>$medicine['batch_no'],
                           'hsn_no'=>'',
                           'quantity'=>$total_qty,
                           'purchase_rate'=>$medicine_data[0]['purchase_rate'],
                           'mrp'=>$medicine_data[0]['mrp'],
                           'discount'=>$medicine_data[0]['discount'],
                           'vat'=>0,
                           'sgst'=>$medicine_data[0]['sgst'],
                           'cgst'=>$medicine_data[0]['cgst'],
                           'igst'=>$medicine_data[0]['igst'],
                           'bar_code'=>$medicine_data[0]['bar_code'],
                           'conversion'=>$medicine_data[0]['conversion'],
                           'total_amount'=>$medicine_data[0]['total_amount'],
                           'expiry_date'=>$medicine_data[0]['expiry_date'],
                           'manuf_date'=>$medicine_data[0]['manuf_date'],
                           'per_pic_rate'=>$medicine_data[0]['conversion'],
                           'created_date'=>date('Y-m-d')
					     ); 
					    $this->db->insert("hms_medicine_batch_stock", $batch_stock_data);
                }


    		    $qt = $row_batch_stock[0]->quantity+$medicine['qty'];
			    
    		    ///////////////// Stock Entry //////////
				$this->db->select('hms_medicine_batch_stock.id, hms_medicine_entry.discount');
				$this->db->join('hms_medicine_entry', 'hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id', 'left');
				$this->db->where('hms_medicine_batch_stock.medicine_id',$med_id);
				$this->db->where('hms_medicine_batch_stock.batch_no',$medicine['batch_no']); 
				$this->db->where('hms_medicine_batch_stock.branch_id',$post['sub_branch_id']);
				$query_batch_stock = $this->db->get('hms_medicine_batch_stock'); 
				$row_batch_stock = $query_batch_stock->row_array();
				if(!empty($row_batch_stock))
					 {
					 	  $qt = $row_batch_stock['quantity']+$medicine['qty'];
					      $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = '".$qt."' WHERE `hms_medicine_batch_stock`.`id` =  '".$row_batch_stock['id']."' ");
					 }
					 else
					 {
	                     $batch_stock_data = array(
                           'branch_id'=>$post['sub_branch_id'],
                           'medicine_id'=>$med_id,
                           'batch_no'=>$medicine['batch_no'],
                           'hsn_no'=>'',
                           'quantity'=>$medicine['qty'],
                           'purchase_rate'=>$medicine_data[0]['purchase_rate'],
                           'mrp'=>$medicine_data[0]['mrp'],
                           'discount'=>$medicine_data[0]['discount'],
                           'vat'=>0,
                           'sgst'=>$medicine_data[0]['sgst'],
                           'cgst'=>$medicine_data[0]['cgst'],
                           'igst'=>$medicine_data[0]['igst'],
                           'bar_code'=>$medicine_data[0]['bar_code'],
                           'conversion'=>$medicine_data[0]['conversion'],
                           'total_amount'=>$medicine_data[0]['total_amount'],
                           'expiry_date'=>$medicine_data[0]['expiry_date'],
                           'manuf_date'=>$medicine_data[0]['manuf_date'],
                           'per_pic_rate'=>$medicine_data[0]['conversion'],
                           'created_date'=>date('Y-m-d')
					     ); 
					    $this->db->insert("hms_medicine_batch_stock", $batch_stock_data);
					//echo $this->db->last_query();
				}
    		    ///////// End batch stock ////////////////////
    		}
    		
             $this->session->unset_userdata('alloted_medicine_ids');
		
    		
            
    	}
    }
	function get_medicine_allot_history_datatables($medicine_id="",$batch_no="",$type="",$branch_id='')
    {
    	
	    $this->_get_medicine_allot_history_datatables_query($medicine_id,$batch_no,$type,$branch_id);
	    if($_POST['length'] != -1)
	    $this->db->limit($_POST['length'], $_POST['start']);
	    /*if(!empty($medicine_id) && !empty($batch_no))
	    {*/
	    $query = $this->db->get(); 
	    //echo $this->db->last_query();die;
	    return $query->result();
	    /*}
	    else
	    {
	    	$result = array();
	    	return $result;
	    }*/
    }
    private function _get_medicine_allot_history_datatables_query($medicine_id="0",$batch_no="0",$type="1",$branch_id='')
    {
		$users_data = $this->session->userdata('auth_users');
		if(!empty($type) && $type==1) //purchase
		{
			$this->db->select("hms_medicine_purchase_to_purchase.*,hms_branch.branch_name,hms_medicine_purchase.vendor_id,hms_medicine_purchase.purchase_id as purchase_order_id,hms_medicine_purchase.created_date as purchase_date,hms_medicine_vendors.name as vendor_name,hms_medicine_company.company_name,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code"); 
			$this->db->from('hms_medicine_purchase_to_purchase');   
			
			$this->db->join('hms_medicine_purchase','hms_medicine_purchase.id = hms_medicine_purchase_to_purchase.purchase_id','left'); 
			$this->db->join('hms_branch','hms_medicine_purchase.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_medicine_vendors','hms_medicine_purchase.vendor_id=hms_medicine_vendors.id','left'); 
			
			$this->db->join('hms_medicine_entry','hms_medicine_purchase_to_purchase.medicine_id=hms_medicine_entry.id','left');
			
			$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->where('hms_medicine_purchase.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_purchase_to_purchase.medicine_id',$medicine_id);
			$this->db->where('hms_medicine_purchase_to_purchase.batch_no',$batch_no);
			
			$this->db->where('hms_medicine_purchase.is_deleted',0);
			$this->db->where('hms_medicine_purchase.is_deleted',0);
			$this->db->order_by('hms_medicine_purchase.id','DESC');
			//echo $this->db->last_query(); 
			
		}
		if(!empty($type) && $type==2) //purchase return
		{
			$this->db->select("hms_medicine_return_to_return.*,hms_branch.branch_name,hms_medicine_return.vendor_id,hms_medicine_return.purchase_id as purchase_order_id,hms_medicine_return.created_date as purchase_date,hms_medicine_vendors.name as vendor_name,hms_medicine_company.company_name,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code"); 
			$this->db->from('hms_medicine_return_to_return');   
			
			$this->db->join('hms_medicine_return','hms_medicine_return.id = hms_medicine_return_to_return.purchase_return_id','left'); 
			$this->db->join('hms_branch','hms_medicine_return.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_medicine_vendors','hms_medicine_return.vendor_id=hms_medicine_vendors.id','left'); 
			
			$this->db->join('hms_medicine_entry','hms_medicine_return_to_return.medicine_id=hms_medicine_entry.id','left');
			
			$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->where('hms_medicine_return.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_return_to_return.medicine_id',$medicine_id);
			$this->db->where('hms_medicine_return_to_return.batch_no',$batch_no);
			$this->db->where('hms_medicine_return.is_deleted',0);
			$this->db->where('hms_medicine_return.is_deleted',0);
			$this->db->order_by('hms_medicine_return.id','DESC');
			//echo $this->db->last_query();
		}
		if(!empty($type) && $type==3) //Sale purchase_id  patient_id 	
		{
			$this->db->select("hms_medicine_sale_to_medicine.*,hms_branch.branch_name,hms_medicine_sale.patient_id,hms_medicine_sale.created_date as purchase_date,hms_patient.patient_name as vendor_name,(select company_name from hms_medicine_company as cmpny where cmpny.id = hms_medicine_entry.manuf_company) as company_name,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code"); 
			$this->db->from('hms_medicine_sale_to_medicine');   
			
			$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id','left'); 
			$this->db->join('hms_branch','hms_medicine_sale.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_medicine_sale.patient_id=hms_patient.id','left'); 
			
			$this->db->join('hms_medicine_entry','hms_medicine_sale_to_medicine.medicine_id=hms_medicine_entry.id','left');
			
			//$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_sale_to_medicine.medicine_id',$medicine_id);
			$this->db->where('hms_medicine_sale_to_medicine.batch_no',$batch_no);
			$this->db->where('hms_medicine_sale.is_deleted',0);
			$this->db->order_by('hms_medicine_sale.id','DESC');
			//echo $this->db->last_query();
		}

		if(!empty($type) && $type==4) //Sale return
		{
			$this->db->select("hms_medicine_sale_return_medicine.*,hms_branch.branch_name,hms_medicine_sale_return.patient_id,hms_medicine_sale_return.patient_id as purchase_order_id,hms_medicine_sale_return.created_date as purchase_date,hms_patient.patient_name as vendor_name,(select company_name from hms_medicine_company as cmpny where cmpny.id = hms_medicine_entry.manuf_company) as company_name,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code"); 
			$this->db->from('hms_medicine_sale_return_medicine');   
			
			$this->db->join('hms_medicine_sale_return','hms_medicine_sale_return.id = hms_medicine_sale_return_medicine.sales_return_id','left'); 
			$this->db->join('hms_branch','hms_medicine_sale_return.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_medicine_sale_return.patient_id=hms_patient.id','left'); 
			
			$this->db->join('hms_medicine_entry','hms_medicine_sale_return_medicine.medicine_id=hms_medicine_entry.id','left');
			
			//$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->where('hms_medicine_sale_return.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_sale_return_medicine.medicine_id',$medicine_id);
			$this->db->where('hms_medicine_sale_return_medicine.batch_no',$batch_no);
			$this->db->where('hms_medicine_sale_return.is_deleted',0);
			$this->db->order_by('hms_medicine_sale_return.id','DESC');
		}
		if(!empty($type) && $type==5) //Branch allot
		{
			$this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_stock.credit as quantity,hms_medicine_stock.created_date,hms_medicine_stock.parent_id,hms_branch.branch_name');
			$this->db->from('hms_medicine_entry');
			$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->join('hms_medicine_stock','hms_medicine_stock.m_id=hms_medicine_entry.id');

			$this->db->join('hms_branch','hms_medicine_stock.parent_id=hms_branch.id','left');
			$this->db->where('hms_medicine_stock.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_stock.type',$type);
			$this->db->where('hms_medicine_stock.credit > 0');  
			$this->db->where('hms_medicine_stock.m_id',$medicine_id);
			$this->db->where('hms_medicine_stock.batch_no',$batch_no);
			//$this->db->where('hms_medicine_stock.branch_id',$branch_id);
			$this->db->order_by('hms_medicine_stock.id','DESC');

			
		}
		if(!empty($type) && $type==6) //Branch allot
		{
		    	$this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_stock.debit as quantity,hms_medicine_stock.created_date,hms_medicine_stock.parent_id,hms_branch.branch_name,hms_medicine_stock.batch_no');
			$this->db->from('hms_medicine_entry');
			$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->join('hms_medicine_stock','hms_medicine_stock.m_id=hms_medicine_entry.id');

			$this->db->join('hms_branch','hms_medicine_stock.parent_id=hms_branch.id','left');
			$this->db->where('hms_medicine_stock.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_stock.type',$type);
			$this->db->where('hms_medicine_stock.debit > 0');  
			$this->db->where('hms_medicine_stock.m_id',$medicine_id);
			$this->db->where('hms_medicine_stock.batch_no',$batch_no);
			//$this->db->where('hms_medicine_stock.branch_id',$branch_id);
			$this->db->order_by('hms_medicine_stock.id','DESC');
			
			/*$this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_stock.credit as quantity,hms_medicine_stock.created_date,hms_medicine_stock.parent_id,hms_branch.branch_name');
			$this->db->from('hms_medicine_entry');
			$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->join('hms_medicine_stock','hms_medicine_stock.m_id=hms_medicine_entry.id');

			$this->db->join('hms_branch','hms_medicine_stock.parent_id=hms_branch.id','left');
			$this->db->where('hms_medicine_stock.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_stock.type',$type);
			$this->db->where('hms_medicine_stock.credit > 0');  
			$this->db->where('hms_medicine_stock.m_id',$medicine_id);
			$this->db->where('hms_medicine_stock.batch_no',$batch_no);
			//$this->db->where('hms_medicine_stock.branch_id',$branch_id);
			$this->db->order_by('hms_medicine_stock.id','DESC');*/

			
		}
    }


    function get_medicine_allot_history_count_filtered($medicine_id="",$batch_no='',$type='',$branch_id='')
	{
		$this->_get_medicine_allot_history_datatables_query($medicine_id,$batch_no,$type,$branch_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_medicine_allot_history_count_all($medicine_id="",$batch_no='',$type='',$branch_id='')
	{
		$this->_get_medicine_allot_history_datatables_query($medicine_id,$batch_no,$type,$branch_id);
		$query = $this->db->get();
		return $query->num_rows();

	}

	function is_deleted_purchase_medicine()
    {
        $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_medicine_purchase.id');
    	$this->db->where('hms_medicine_purchase.is_deleted=2');
    	$this->db->where('hms_medicine_purchase.branch_id',$user_data['parent_id']);
    	$query= $this->db->get('hms_medicine_purchase')->result_array();
    	return $query;
    }
    function is_deleted_purchase_return_medicine()
    {
        $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_medicine_return.id');
    	$this->db->where('hms_medicine_return.is_deleted=2');
    	$this->db->where('hms_medicine_return.branch_id',$user_data['parent_id']);
    	$query=$this->db->get('hms_medicine_return')->result_array();
    	//print_r($query);die;
    	return $query;
    }
    function is_deleted_sale_medicine()
    {
        $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_medicine_sale.id');
    	$this->db->where('hms_medicine_sale.is_deleted=2');
    	$this->db->where('hms_medicine_sale.branch_id',$user_data['parent_id']);
    	$query=$this->db->get('hms_medicine_sale')->result_array();
    	return $query;
    }
    function is_deleted_sale_return_medicine()
    {
        $user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_medicine_sale_return.id');
    	$this->db->where('hms_medicine_sale_return.is_deleted=2');
    	$this->db->where('hms_medicine_sale_return.branch_id',$user_data['parent_id']);
    	$query=$this->db->get('hms_medicine_sale_return')->result_array();
    	return $query;
    }
    
    public function update_quantity()
	{

   		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		//echo "<pre>";print_r($post); die();

		$this->db->select("hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_stock.*,hms_medicine_unit.*");
		$this->db->from('hms_medicine_entry');
		$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left'); 
		$this->db->join('hms_medicine_stock','hms_medicine_entry.id=hms_medicine_stock.m_id','left');
		$this->db->join('hms_medicine_unit','hms_medicine_entry.unit_id=hms_medicine_unit.id','left');
		$this->db->where('hms_medicine_stock.batch_no',$post['batch_no']);
		$this->db->where('hms_medicine_entry.id',$post['id']); 
		$this->db->where('hms_medicine_entry.branch_id='.$user_data['parent_id']);  
		$this->db->group_by('hms_medicine_stock.m_id');
		$query = $this->db->get(); 
		$medicine_data = $query->result_array();

		if(!empty($post['batch_no']))
		{
			$batch_no = $post['batch_no'];
		}
		elseif(!empty($medicine_data[0]['batch_no']))
		{
			$batch_no = $medicine_data[0]['batch_no'];
		}
		else
		{
			$batch_no ='0';
		}

		if(!empty($medicine_data[0]['bar_code']))
		{
			$bar_code = $medicine_data[0]['bar_code'];
		}
		else
		{
			$bar_code ='';
		}

		if(!empty($medicine_data[0]['conversion']))
		{
			$conversion = $medicine_data[0]['conversion'];
		}
		else
		{
			$conversion ='';
		}

		if(!empty($medicine_data[0]['deleted_date']))
		{
			$deleted_date = $medicine_data[0]['deleted_date'];
		}
		else
		{
			$deleted_date ='';
		}

		if(!empty($medicine_data[0]['deleted_by']))
		{
			$deleted_by = $medicine_data[0]['deleted_by'];
		}
		else
		{
			$deleted_by ='';
		}
		if(!empty($medicine_data[0]['total_amount']))
		{
			$total_amount = $medicine_data[0]['total_amount'];
		}
		else
		{
			$total_amount ='';
		}

		if(!empty($medicine_data[0]['expiry_date']))
		{
			$expiry_date = $medicine_data[0]['expiry_date'];
		}
		else
		{
			$expiry_date ='';
		}

		if(!empty($medicine_data[0]['manuf_date']))
		{
			$manuf_date = $medicine_data[0]['manuf_date'];
		}
		else
		{
			$manuf_date ='';
		}

		if(!empty($medicine_data[0]['per_pic_rate']))
		{
			$per_pic_rate = $medicine_data[0]['per_pic_rate'];
		}
		else
		{
			$per_pic_rate ='';
		}
		
		

		$data_allot_to_branch = array( 
				'branch_id'=>$user_data['parent_id'],
                'type'=>7,
                'parent_id'=>'0',
                'm_id'=>$post['id'],  //'m_id'=>$med_id, it was wrong
                'credit'=>'0',
				'debit'=>$post['quantity'],
				'batch_no'=>$batch_no,
				'bar_code'=>$bar_code,
				'conversion'=>$conversion,
				'is_deleted'=>0,
				'deleted_date'=>$deleted_date,
				'deleted_by'=>$deleted_by,
				'ip_address'=>$_SERVER['REMOTE_ADDR'],
				'created_date'=>date('Y-m-d H:i:s'),
				'modified_date'=>date('Y-m-d H:i:s'),
				'created_by'=>$user_data['parent_id'],
				
				'discount'=>'',
				'vat'=>'',
				'total_amount'=>$total_amount,
				'expiry_date'=>$expiry_date,
				'manuf_date'=>$manuf_date,
				'per_pic_rate'=>$per_pic_rate,
				);
				if(!empty($medicine_data[0]['purchase_rate'])){
					$data_allot_to_branch['purchase_rate'] = $medicine_data[0]['purchase_rate'];
				}else{
					$data_allot_to_branch['purchase_rate'] ='0.00';
				}
				
				$this->db->insert('hms_medicine_stock',$data_allot_to_branch);

				//echo $this->db->last_query(); exit;
	}
	
	
	public function reset_medicine_stock()
	{
	    $user_data = $this->session->userdata('auth_users');
	    $post = $this->input->post(); 
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);  
		$this->db->where('medicine_id',$post['mid']);
		$this->db->where('batch_no',$post['batch_no']);
		$query = $this->db->get('hms_medicine_batch_stock');
		//echo $this->db->last_query();
		$result =  $query->row_array(); 
		if(!empty($result))
		{
		    $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = '".$post['qty']."'  WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$post['mid']."' "); 
		}
		else
		{
		      $user_data = $this->session->userdata('auth_users');
              $this->db->select('*');
              $this->db->where('branch_id',$user_data['parent_id']); 
              $this->db->where('m_id',$post['mid']);
              $this->db->where('batch_no',$post['batch_no']);
              $query = $this->db->get('hms_medicine_stock');
              $medicine_list = $query->row_array();
              
		    $batch_stock_data = array(
                           'branch_id'=>$user_data['parent_id'],
                           'medicine_id'=>$medicine_list['m_id'],
                           'batch_no'=>$medicine_list['batch_no'],
                           'hsn_no'=>$medicine_list['hsn_no'],
                           'quantity'=>$post['qty'],
                           'purchase_rate'=>$medicine_list['purchase_amount'],
                           'mrp'=>$medicine_list['mrp'],
                           'discount'=>$medicine_list['discount'],
                           'vat'=>0,
                           'sgst'=>$medicine_list['sgst'],
                           'cgst'=>$medicine_list['cgst'],
                           'igst'=>$medicine_list['igst'],
                           'bar_code'=>$medicine_list['bar_code'],
                           'conversion'=>$medicine_list['conversion'],
                           'total_amount'=>$medicine_list['total_amount'],
                           'expiry_date'=>date('Y-m-d',strtotime($medicine_list['expiry_date'])),
                           'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
                           'per_pic_rate'=>($medicine_list['mrp']/$medicine_list['conversion']),
                           'created_date'=>date('Y-m-d',strtotime($post['created_date']))
					     ); 
		    $this->db->insert("hms_medicine_batch_stock", $batch_stock_data);
		}
		
	}

    
    public function get_medicine_allot_list()
    {
    	$users_data = $this->session->userdata('auth_users');
    	$medicine_data = $this->session->userdata('alloted_medicine_ids');
        //echo "<pre>"; print_r($medicine_data); exit;
        $result = array();
    	if(!empty($medicine_data))
    	{
    		$id_list = [];
    		$batch_list = [];
    		// $l=0;
    		foreach($medicine_data as $medicine)
    		{
    			
    			
				if(!empty($medicine['medicine_id']) && $medicine['medicine_id']>0)
			    {
                    $id_list[]  = $medicine['medicine_id'];
			    }
			   
			
			
				if(!empty($medicine['batch_no']) )
			    {
                    $batch_list[]  = $medicine['batch_no'];
			    }
    			   
    		
    			 // $l++;
    		} 
    		//echo "<pre>"; print_r($batch_list); exit;
    		
    		$medicine_ids = implode(',', $id_list);
    		$batch_nos = implode(',', $batch_list);
    		
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
    		
          /* $i=1; $or='';
           $where1='';
           foreach($batch_nos_array as $value)
           {
            if($i>1)
            {
              $or=' OR ';
            }
            $where1.=$or. "hms_medicine_stock.batch_no='$value'" ;
        
            $i++;
          }
         echo $where1;*/
          //$this->db->where('('.$where1.')');
    		
    	 // echo "<pre>"; print_r($batch_nos); exit;
	    	   $this->db->select("hms_medicine_entry.*,hms_medicine_stock.batch_no,hms_medicine_stock.m_id");
		    	$this->db->from('hms_medicine_entry');
		
	            $this->db->join('hms_medicine_stock','hms_medicine_entry.id=hms_medicine_stock.m_id');
	          
	            $this->db->where('hms_medicine_entry.id IN ('.$medicine_ids.')');
	            $this->db->where('hms_medicine_stock.m_id IN ('.$medicine_ids.')');
	            if(!empty($where1)){
	                $this->db->where('hms_medicine_stock.batch_no IN ('.$where1.')');
	              
	            }else{
	            	$this->db->where('hms_medicine_stock.batch_no',0);
	            }
	            $this->db->where('(hms_medicine_entry.is_deleted=0 and hms_medicine_stock.is_deleted=0)');
		    	$this->db->where('(hms_medicine_entry.branch_id='.$users_data['parent_id'].' and hms_medicine_stock.branch_id='.$users_data['parent_id'].')');
		    	$this->db->group_by('hms_medicine_stock.m_id');
		    	$query = $this->db->get();
		        //echo $this->db->last_query();die;
		    	$result = $query->result_array();
		   
    	}
    	return $result;
    }



	
} 
?>