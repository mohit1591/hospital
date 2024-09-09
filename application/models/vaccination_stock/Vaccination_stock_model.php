<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vaccination_stock_model extends CI_Model 
{
	var $table = 'hms_vaccination_stock';
	var $column = array('hms_vaccination_entry.id','hms_vaccination_entry.vaccination_code', 'hms_vaccination_entry.vaccination_name', 'hms_vaccination_entry.unit_id','hms_vaccination_entry.unit_second_id','hms_vaccination_entry.conversion','hms_vaccination_entry.min_alrt','hms_vaccination_entry.packing','hms_vaccination_entry.rack_no','hms_vaccination_entry.salt','hms_vaccination_entry.manuf_company','hms_vaccination_entry.mrp','hms_vaccination_entry.purchase_rate','hms_vaccination_entry.discount','hms_vaccination_entry.vat','hms_vaccination_entry.status', 'hms_vaccination_entry.created_date', 'hms_vaccination_entry.modified_date');  
	var $order = array('hms_vaccination_entry.id' => 'desc'); 

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
			$this->db->select('hms_vaccination_stock.*, sum(hms_vaccination_stock.debit)- sum(hms_vaccination_stock.credit) as stock_qty,hms_vaccination_stock.created_date as stock_created_date, hms_vaccination_racks.rack_no as rack_nu, hms_vaccination_entry.*,hms_vaccination_entry.id as m_eid,hms_vaccination_entry.created_date as create,hms_vaccination_company.company_name');
		}
		else
		{
			$this->db->select('hms_vaccination_stock.*, sum(hms_vaccination_stock.debit)- sum(hms_vaccination_stock.credit) as stock_qty, hms_vaccination_stock.created_date as stock_created_date, hms_vaccination_racks.rack_no as rack_nu, hms_vaccination_entry.*,hms_vaccination_entry.id as m_eid,hms_vaccination_entry.created_date as create,hms_vaccination_company.company_name');
		}
		$this->db->join('hms_vaccination_stock','hms_vaccination_stock.v_id = hms_vaccination_entry.id','left');   
		//m_eid
		//$this->db->join('hms_vaccination_purchase_to_purchase','hms_vaccination_purchase_to_purchase.vaccine_id = hms_vaccination_stock.v_id','left'); 

		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)>0');
		$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		//$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
		//$this->db->join('hms_vaccination_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_vaccination_entry.unit_second_id','left');
		//$this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_vaccination_entry.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		$this->db->from('hms_vaccination_entry');  
		if($search['type']==2 && isset($search['type']))
		{
			$this->db->group_by('hms_vaccination_stock.v_id');
			
		}
		else
		{

			$this->db->group_by('hms_vaccination_entry.id');
			$this->db->group_by('hms_vaccination_stock.batch_no');
			
		}
		$this->db->order_by('hms_vaccination_stock.id','DESC');
		//if(isset($search['type']) && !empty($search['start_date']))
		
        //$this->db->group_by('hms_vaccination_stock.id');

		$i = 0;


		if(isset($search) && !empty($search))
		{
		if(isset($search['start_date']) && !empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
		$this->db->where('hms_vaccination_stock.created_date >= "'.$start_date.'"');
		}

		if(isset($search['end_date']) && !empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
		$this->db->where('hms_vaccination_stock.created_date <= "'.$end_date.'"');
		}

		if(isset($search['vaccination_name']) && !empty($search['vaccination_name']))
		{
			$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['vaccination_name'].'%"');
		}

		if(isset($search['vaccination_company']) && !empty($search['vaccination_company']))
		{
			 
			$this->db->where('hms_vaccination_company.company_name LIKE"'.$search['vaccination_company'].'%"');
		}

		if(isset($search['vaccination_code']) && !empty($search['vaccination_code']))
		{
		
			$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$search['vaccination_code'].'%"');
		}
		if(isset($search['expiry_to']) && !empty($search['expiry_to']))
		{
		    $expiry_to = date('Y-m-d',strtotime($search['expiry_to']));
			$this->db->where('hms_vaccination_stock.expiry_date >="'.$expiry_to.'"');
		}
		if(isset($search['expiry_from']) && !empty($search['expiry_from']))
		{
		    $expiry_from = date('Y-m-d',strtotime($search['expiry_from']));
			$this->db->where('hms_vaccination_stock.expiry_date <="'.$expiry_from.'"');
		}


		if(isset($search['batch_no']) && !empty($search['batch_no']))
		{
			$this->db->where('hms_vaccination_stock.batch_no = "'.$search['batch_no'].'"');
		}

		if(isset($search['unit1']) && $search['unit1']!="")
		{
			$this->db->where('hms_vaccination_unit.id = "'.$search['unit1'].'"');
		}

		if(isset($search['unit2']) && $search['unit2']!="")
		{
			$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
		}

		if(isset($search['packing']) && $search['packing']!="")
		{
		
		//$this->db->where('packing',$search['packing']);
			$this->db->where('hms_vaccination_entry.packing LIKE "'.$search['packing'].'%"');
		}

		if(isset($search['qty_to']) &&  $search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)>='.$search['qty_to']);
		
		}
		if(isset($search['qty_from']) && $search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)<='.$search['qty_from']);
		}


		if(isset($search['price_to_mrp']) && $search['price_to_mrp']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp >="'.$search['price_to_mrp'].'"');
		}

		if(isset($search['price_from_mrp']) && $search['price_from_mrp']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp <="'.$search['price_to_mrp'].'"');
		}

		if(isset($search['price_to_purchase']) && $search['price_to_purchase']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate >= "'.$search['price_to_purchase'].'"');
		}


		if(isset($search['price_from_purchase']) && $search['price_from_purchase']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate <="'.$search['price_to_purchase'].'"');
		}

		if(isset($search['rack_no']) && $search['rack_no']!="")
		{
			//$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
			$this->db->where('hms_vaccination_racks.rack_no',$search['rack_no']);
			
		}
		if(isset($search['min_alert']) && $search['min_alert']!="")
		{
			$this->db->where('hms_vaccination_entry.min_alrt',$search['min_alert']);
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
			$this->db->where('hms_vaccination_entry.created_by IN ('.$emp_ids.')');
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

	function search_report_data(){
		$user_data = $this->session->userdata('auth_users');
		$search=$this->session->userdata('stock_search');
		//print '<pre>';print_r($search);die;
		if($search['type']==2 && isset($search['type']))
		{
			$this->db->select('hms_vaccination_stock.*, sum(hms_vaccination_stock.debit)- sum(hms_vaccination_stock.credit) as stock_qty,hms_vaccination_stock.created_date as stock_created_date, hms_vaccination_racks.rack_no as rack_nu, hms_vaccination_entry.*,hms_vaccination_entry.id as m_eid,hms_vaccination_entry.created_date as create,hms_vaccination_company.company_name');
		}
		else
		{
			$this->db->select('hms_vaccination_stock.*, sum(hms_vaccination_stock.debit)- sum(hms_vaccination_stock.credit) as stock_qty, hms_vaccination_stock.created_date as stock_created_date, hms_vaccination_racks.rack_no as rack_nu, hms_vaccination_entry.*,hms_vaccination_entry.id as m_eid,hms_vaccination_entry.created_date as create,hms_vaccination_company.company_name');
		}
		$this->db->join('hms_vaccination_stock','hms_vaccination_stock.v_id = hms_vaccination_entry.id','left');   
		//m_eid
		//$this->db->join('hms_vaccination_purchase_to_purchase','hms_vaccination_purchase_to_purchase.vaccine_id = hms_vaccination_stock.v_id','left'); 

		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)>0');
		$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		//$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
		//$this->db->join('hms_vaccination_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_vaccination_entry.unit_second_id','left');
		//$this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_vaccination_entry.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
		}
		
		$this->db->from('hms_vaccination_entry');  
		if($search['type']==2 && isset($search['type']))
		{
			$this->db->group_by('hms_vaccination_stock.v_id');
			
		}
		else
		{

			$this->db->group_by('hms_vaccination_entry.id');
			$this->db->group_by('hms_vaccination_stock.batch_no');
			
		}
		$this->db->order_by('hms_vaccination_stock.id','DESC');
		//if(isset($search['type']) && !empty($search['start_date']))
		
        //$this->db->group_by('hms_vaccination_stock.id');

		$i = 0;
		if(isset($search) && !empty($search))
		{
		if(isset($search['start_date']) && !empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
		$this->db->where('hms_vaccination_stock.created_date >= "'.$start_date.'"');
		}

		if(isset($search['end_date']) && !empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
		$this->db->where('hms_vaccination_stock.created_date <= "'.$end_date.'"');
		}

		if(isset($search['vaccination_name']) && !empty($search['vaccination_name']))
		{
			$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['vaccination_name'].'%"');
		}

		if(isset($search['vaccination_company']) && !empty($search['vaccination_company']))
		{
			 
			$this->db->where('hms_vaccination_company.company_name LIKE"'.$search['medicine_company'].'%"');
		}

		if(isset($search['vaccination_code']) && !empty($search['vaccination_code']))
		{
		
			$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$search['vaccination_code'].'%"');
		}
		if(isset($search['expiry_to']) && !empty($search['expiry_to']))
		{
		    $expiry_to = date('Y-m-d',strtotime($search['expiry_to']));
			$this->db->where('hms_vaccination_stock.expiry_date >="'.$expiry_to.'"');
		}
		if(isset($search['expiry_from']) && !empty($search['expiry_from']))
		{
		    $expiry_from = date('Y-m-d',strtotime($search['expiry_from']));
			$this->db->where('hms_vaccination_stock.expiry_date <="'.$expiry_from.'"');
		}


		if(isset($search['batch_no']) && !empty($search['batch_no']))
		{
			$this->db->where('hms_vaccination_stock.batch_no = "'.$search['batch_no'].'"');
		}

		if(isset($search['unit1']) && $search['unit1']!="")
		{
			$this->db->where('hms_vaccination_unit.id = "'.$search['unit1'].'"');
		}

		if(isset($search['unit2']) && $search['unit2']!="")
		{
			$this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
		}

		if(isset($search['packing']) && $search['packing']!="")
		{
		
		//$this->db->where('packing',$search['packing']);
			$this->db->where('hms_vaccination_entry.packing LIKE "'.$search['packing'].'%"');
		}

		if(isset($search['qty_to']) &&  $search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)>='.$search['qty_to']);
		
		}
		if(isset($search['qty_from']) && $search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)<='.$search['qty_from']);
		}


		if(isset($search['price_to_mrp']) && $search['price_to_mrp']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp >="'.$search['price_to_mrp'].'"');
		}

		if(isset($search['price_from_mrp']) && $search['price_from_mrp']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp <="'.$search['price_to_mrp'].'"');
		}

		if(isset($search['price_to_purchase']) && $search['price_to_purchase']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate >= "'.$search['price_to_purchase'].'"');
		}

		if(isset($search['price_from_purchase']) && $search['price_from_purchase']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate <="'.$search['price_to_purchase'].'"');
		}

		if(isset($search['rack_no']) && $search['rack_no']!="")
		{
			//$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
			$this->db->where('hms_vaccination_racks.rack_no',$search['rack_no']);
			
		}
		if(isset($search['min_alert']) && $search['min_alert']!="")
		{
			$this->db->where('hms_vaccination_entry.min_alrt',$search['min_alert']);
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
			$this->db->where('hms_vaccination_entry.created_by IN ('.$emp_ids.')');
		}
		 $query = $this->db->get(); 

		$data= $query->result();
		//print '<pre>'; print_r($data);die;
		return $data;
        //$this->db->group_by('hms_vaccination_stock.id');
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

	public function get_batch_med_qty($vid="",$batch_no="")
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
		$this->db->where('v_id',$vid);
		$this->db->where('batch_no',$batch_no);
		$query = $this->db->get('hms_vaccination_stock');
		//echo $this->db->last_query();
		return $query->row_array();

	}

	public function get_by_id($id)
	{
		$this->db->select("hms_vaccination_entry.*, hms_vaccination_company.company_name, hms_vaccination_unit.vaccination_unit"); 
		$this->db->from('hms_vaccination_entry'); 
		$this->db->where('hms_vaccination_entry.id',$id);
		$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id=hms_vaccination_entry.manuf_company','left');
		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id=hms_vaccination_entry.rack_no','left');
		$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id=hms_vaccination_entry.unit_id','left');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


	public function save()
	{
		$reg_no = generate_unique_id(10); 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
				$data = array(
				"vaccination_code"=>$reg_no,
				"vaccination_name"=>$post['vaccination_name'],
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
			$this->db->update('hms_vaccination_entry',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_vaccination_entry',$data);               
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
			$this->db->update('hms_vaccination_entry');
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
			$this->db->update('hms_vaccination_entry');
    	} 
    }

    public function medicine_entry_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_vaccination_entry');
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
        $this->db->order_by('vaccination_unit','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_vaccination_unit');
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
        $this->db->order_by('vaccination_unit','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_vaccination_unit');
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
        $query = $this->db->get('hms_vaccination_racks');
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
        $query = $this->db->get('hms_vaccination_company');
        $result = $query->result(); 
        return $result; 
    }


    public function get_medicine_list()
    {
    	$users_data = $this->session->userdata('auth_users');
    	$medicine_data = $this->session->userdata('alloted_vaccine_ids');

        $result = array();
    	if(!empty($medicine_data))
    	{
    		$id_list = [];
    		$batch_list = [];
    		// $l=0;
    		foreach($medicine_data as $medicine)
    		{
    			
    			
				if(!empty($medicine['vaccine_id']) && $medicine['vaccine_id']>0)
			    {
                    $id_list[]  = $medicine['vaccine_id'];
			    }
			   
			
			
				if(!empty($medicine['batch_no']) && $medicine['batch_no']>0)
			    {
                    $batch_list[]  = $medicine['batch_no'];
			    }
    			   
    		
    			 // $l++;
    		} 
    		
    		
    		$medicine_ids = implode(',', $id_list);
    		$batch_nos = implode(',', $batch_list);
    		
    	  
	    	   $this->db->select("hms_vaccination_entry.*,hms_vaccination_stock.batch_no,hms_vaccination_stock.v_id");
		    	$this->db->from('hms_vaccination_entry');
		
	            $this->db->join('hms_vaccination_stock','hms_vaccination_entry.id=hms_vaccination_stock.v_id');
	          
	            $this->db->where('hms_vaccination_entry.id IN ('.$medicine_ids.')');
	            $this->db->where('hms_vaccination_stock.v_id IN ('.$medicine_ids.')');
	            if($batch_nos!=0){
	                $this->db->where('hms_vaccination_stock.batch_no IN ('.$batch_nos.')');
	            }else{
	            	$this->db->where('hms_vaccination_stock.batch_no',0);
	            }
	            $this->db->where('(hms_vaccination_entry.is_deleted=0 and hms_vaccination_stock.is_deleted=0)');
		    	$this->db->where('(hms_vaccination_entry.branch_id='.$users_data['parent_id'].' and hms_vaccination_stock.branch_id='.$users_data['parent_id'].')');
		    	$this->db->group_by('hms_vaccination_stock.v_id');
		    	$query = $this->db->get();
		    	// echo $this->db->last_query();die;
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
           //print_r($post['vaccine']);die;  
    		foreach($post['vaccine'] as $vid=>$medicine)
    		{
    			//sub_branch_id
				$this->db->select("hms_vaccination_entry.*,hms_vaccination_company.company_name,hms_vaccination_stock.*,hms_vaccination_unit.*");
				$this->db->from('hms_vaccination_entry');
				$this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left'); 
				$this->db->join('hms_vaccination_stock','hms_vaccination_entry.id=hms_vaccination_stock.v_id','left');
				$this->db->join('hms_vaccination_unit','hms_vaccination_entry.unit_id=hms_vaccination_unit.id','left');
				$this->db->where('hms_vaccination_stock.batch_no',$post['vaccine'][$vid]['batch_no']);
				$this->db->where('hms_vaccination_entry.id',$vid); 
				$this->db->where('hms_vaccination_entry.branch_id='.$users_data['parent_id']);  
				$this->db->group_by('hms_vaccination_stock.v_id');
				$query = $this->db->get(); 
				$medicine_data = $query->result_array();
                //echo "<pre>";print_r($medicine_data);die;
                
                ///////// Check Unit  //////////////
                $this->db->select("hms_vaccination_unit.*");
				$this->db->from('hms_vaccination_unit'); 
				$this->db->where('hms_vaccination_unit.id',$medicine_data[0]['unit_id']); 
				$this->db->where('hms_vaccination_unit.branch_id='.$post['sub_branch_id']);  
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
						'vaccination_unit'=>$medicine_data[0]['vaccination_unit'],
						'status'=>$medicine_data[0]['status'],
						'is_deleted'=>$medicine_data[0]['is_deleted'],
						'deleted_by'=>$medicine_data[0]['deleted_by'],
						'deleted_date'=>$medicine_data[0]['deleted_date'],
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'created_by'=>$users_data['parent_id'],
						'created_date'=>date('Y-m-d H:i:s')
						);
					$this->db->insert('hms_vaccination_unit',$medicine_unit_data);
					$m_unit_id = $this->db->insert_id();
				}

				///////// check Unit Second ////////
                $this->db->select("hms_vaccination_unit.*");
				$this->db->from('hms_vaccination_unit'); 
				$this->db->where('hms_vaccination_unit.id',$medicine_data[0]['unit_second_id']); 
				$this->db->where('hms_vaccination_unit.branch_id='.$post['sub_branch_id']);  
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
						'vaccination_unit'=>$medicine_data[0]['vaccination_unit'],
						'status'=>$medicine_data[0]['status'],
						'is_deleted'=>$medicine_data[0]['is_deleted'],
						'deleted_by'=>$medicine_data[0]['deleted_by'],
						'deleted_date'=>$medicine_data[0]['deleted_date'],
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'created_by'=>$users_data['parent_id'],
						 'created_date'=>date('Y-m-d H:i:s')
						);
					$this->db->insert('hms_vaccination_unit',$medicine_unit_data);
					$m_sec_unit_id = $this->db->insert_id();
				}



				///////// Check Company ////////////
				$m_company_id =0;
                if(!empty($medicine_data[0]['company_name']))
                {
                	$this->db->select("hms_vaccination_company.*");
					$this->db->from('hms_vaccination_company'); 
					$this->db->where('hms_vaccination_company.company_name',$medicine_data[0]['company_name']); 
					$this->db->where('hms_vaccination_company.branch_id='.$post['sub_branch_id']);  
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
							$this->db->insert('hms_vaccination_company',$medicine_company_data);
							$m_company_id = $this->db->insert_id();
					}
                }
				////////////////////////////////////
    		    ///////// Check Medicine //////////
                $this->db->select("hms_vaccination_entry.*");
				$this->db->from('hms_vaccination_entry'); 
				$this->db->where('hms_vaccination_entry.vaccination_name',$medicine_data[0]['vaccination_name']);
				$this->db->where('hms_vaccination_entry.manuf_company',$m_company_id); 
				$this->db->where('hms_vaccination_entry.branch_id='.$post['sub_branch_id']);  
				$query = $this->db->get(); 
				//echo $this->db->last_query();
				$checked_medicine_company_data = $query->result_array();
				//echo $this->db->last_query();die;
				if(!empty($checked_medicine_company_data))
				{
					$med_id = $checked_medicine_company_data[0]['id'];
					
				}
				else
				{

					$new_add_medicine = array(
					'branch_id'=>$post['sub_branch_id'],
					'vaccination_code'=>$medicine_data[0]['vaccination_code'],
					'vaccination_name'=>trim($medicine_data[0]['vaccination_name']),
					'unit_id'=>$m_unit_id,
					'unit_second_id'=>$m_sec_unit_id,
					'conversion'=>$medicine_data[0]['conversion'],
					'min_alrt'=>$medicine_data[0]['min_alrt'],
					'packing'=>$medicine_data[0]['packing'], 
					'salt'=>$medicine_data[0]['salt'],
					'manuf_company'=>$m_company_id,
					'mrp'=>$medicine_data[0]['mrp'],
					// 'purchase_rate'=>$medicine_data[0]['purchase_rate'],
					// 'vat'=>$medicine_data[0]['vat'],
					'discount'=>$medicine_data[0]['discount'],
					'status'=>$medicine_data[0]['status'],
					'is_deleted'=>$medicine_data[0]['is_deleted'],
					'deleted_date'=>$medicine_data[0]['deleted_date'],
					'deleted_by'=>$medicine_data[0]['deleted_by'],
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					'created_by'=>$users_data['id'],  
					'created_date'=>date('Y-m-d H:i:s'),

					);
					if(!empty($medicine_data[0]['purchase_rate'])){
					    $new_add_medicine['purchase_rate'] = $medicine_data[0]['purchase_rate'];
				    }else{
					    $new_add_medicine['purchase_rate'] ='00.00';
				    }
					$this->db->insert('hms_vaccination_entry',$new_add_medicine);
					//echo $this->db->last_query();die;
					$med_id = $this->db->insert_id();

				}

    		    ///////////////////////////////////
    		    
    		    ///////////////// Stock Entry //////////
    		    
    		    /////////medicine allocate by branch //////////
				$data_allot_by_branch = array( 
				'branch_id'=>$users_data['parent_id'],
                'type'=>5,
                'parent_id'=>$post['sub_branch_id'],
                'v_id'=>$vid,
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
                $this->db->insert('hms_vaccination_stock',$data_allot_by_branch);
                 //echo $this->db->last_query(); exit;
                 /////////medicine allocate to branch //////////
				$data_allot_to_branch = array( 
				'branch_id'=>$post['sub_branch_id'],
                'type'=>5,
                'parent_id'=>$users_data['parent_id'],
                'v_id'=>$med_id,  //'v_id'=>$med_id, it was wrong
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
				
				$this->db->insert('hms_vaccination_stock',$data_allot_to_branch);
				//echo $this->db->last_query();die;
    		    ///////////////////////////////////////
    		}
    		
             $this->session->unset_userdata('alloted_vaccine_ids');
		
    		
            
    	}
    }
	function get_medicine_allot_history_datatables($vaccine_id="",$batch_no="",$type="",$branch_id='')
    {
    	
	    $this->_get_medicine_allot_history_datatables_query($vaccine_id,$batch_no,$type,$branch_id);
	    if($_POST['length'] != -1)
	    $this->db->limit($_POST['length'], $_POST['start']);
	    /*if(!empty($vaccine_id) && !empty($batch_no))
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
    private function _get_medicine_allot_history_datatables_query($vaccine_id="0",$batch_no="0",$type="1",$branch_id='')
    {
		$users_data = $this->session->userdata('auth_users');
		if(!empty($type) && $type==1) //purchase
		{
			$this->db->select("hms_vaccination_purchase_to_purchase.*,hms_branch.branch_name,hms_vaccination_purchase.vendor_id,hms_vaccination_purchase.purchase_id as purchase_order_id,hms_vaccination_purchase.created_date as purchase_date,hms_medicine_vendors.name as vendor_name,hms_vaccination_company.company_name,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.vaccination_code"); 
			$this->db->from('hms_vaccination_purchase_to_purchase');   
			
			$this->db->join('hms_vaccination_purchase','hms_vaccination_purchase.id = hms_vaccination_purchase_to_purchase.purchase_id','left'); 
			$this->db->join('hms_branch','hms_vaccination_purchase.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_medicine_vendors','hms_vaccination_purchase.vendor_id=hms_medicine_vendors.id','left'); 
			
			$this->db->join('hms_vaccination_entry','hms_vaccination_purchase_to_purchase.vaccine_id=hms_vaccination_entry.id','left');
			
			$this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');

			$this->db->where('hms_vaccination_purchase.branch_id',$users_data['parent_id']);
			$this->db->where('hms_vaccination_purchase_to_purchase.vaccine_id',$vaccine_id);
			
			$this->db->where('hms_vaccination_purchase.is_deleted',0);
			$this->db->where('hms_vaccination_purchase.is_deleted',0);
			$this->db->order_by('hms_vaccination_purchase.id','DESC');
			//echo $this->db->last_query(); 
			
		}
		if(!empty($type) && $type==2) //purchase return
		{
			$this->db->select("hms_vaccination_purchase_return_to_return.*,hms_branch.branch_name,hms_vaccination_purchase_return.vendor_id,hms_vaccination_purchase_return.purchase_id as purchase_order_id,hms_vaccination_purchase_return.created_date as purchase_date,hms_medicine_vendors.name as vendor_name,hms_vaccination_company.company_name,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.vaccination_code"); 
			$this->db->from('hms_vaccination_purchase_return_to_return');   
			
			$this->db->join('hms_vaccination_purchase_return','hms_vaccination_purchase_return.id = hms_vaccination_purchase_return_to_return.purchase_return_id','left'); 
			$this->db->join('hms_branch','hms_vaccination_purchase_return.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_medicine_vendors','hms_vaccination_purchase_return.vendor_id=hms_medicine_vendors.id','left'); 
			
			$this->db->join('hms_vaccination_entry','hms_vaccination_purchase_return_to_return.vaccine_id=hms_vaccination_entry.id','left');
			
			$this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');

			$this->db->where('hms_vaccination_purchase_return.branch_id',$users_data['parent_id']);
			$this->db->where('hms_vaccination_purchase_return_to_return.vaccine_id',$vaccine_id);
			
			$this->db->where('hms_vaccination_purchase_return.is_deleted',0);
			$this->db->where('hms_vaccination_purchase_return.is_deleted',0);
			$this->db->order_by('hms_vaccination_purchase_return.id','DESC');
			//echo $this->db->last_query();
		}
		if(!empty($type) && $type==3) //Sale purchase_id  patient_id 	
		{
			$this->db->select("hms_vaccination_sale_to_vaccination.*,hms_branch.branch_name,hms_vaccination_sale.patient_id,hms_vaccination_sale.created_date as purchase_date,hms_patient.patient_name as vendor_name,(select company_name from hms_vaccination_company as cmpny where cmpny.id = hms_vaccination_entry.manuf_company) as company_name,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.vaccination_code"); 
			$this->db->from('hms_vaccination_sale_to_vaccination');   
			
			$this->db->join('hms_vaccination_sale','hms_vaccination_sale.id = hms_vaccination_sale_to_vaccination.sales_id','left'); 
			$this->db->join('hms_branch','hms_vaccination_sale.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_vaccination_sale.patient_id=hms_patient.id','left'); 
			
			$this->db->join('hms_vaccination_entry','hms_vaccination_sale_to_vaccination.vaccine_id=hms_vaccination_entry.id','left');
			
			//$this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');

			$this->db->where('hms_vaccination_sale.branch_id',$users_data['parent_id']);
			$this->db->where('hms_vaccination_sale_to_vaccination.vaccine_id',$vaccine_id);
			
			$this->db->where('hms_vaccination_sale.is_deleted',0);
			$this->db->order_by('hms_vaccination_sale.id','DESC');
			//echo $this->db->last_query();
		}

		if(!empty($type) && $type==4) //Sale return
		{
			$this->db->select("hms_vaccination_sale_return_vaccination.*,hms_branch.branch_name,hms_vaccination_sale_return.patient_id,hms_vaccination_sale_return.patient_id as purchase_order_id,hms_vaccination_sale_return.created_date as purchase_date,hms_patient.patient_name as vendor_name,(select company_name from hms_vaccination_company as cmpny where cmpny.id = hms_vaccination_entry.manuf_company) as company_name,hms_vaccination_entry.vaccination_name,hms_vaccination_entry.vaccination_code"); 
			$this->db->from('hms_vaccination_sale_return_vaccination');   
			
			$this->db->join('hms_vaccination_sale_return','hms_vaccination_sale_return.id = hms_vaccination_sale_return_vaccination.sales_return_id','left'); 
			$this->db->join('hms_branch','hms_vaccination_sale_return.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_vaccination_sale_return.patient_id=hms_patient.id','left'); 
			
			$this->db->join('hms_vaccination_entry','hms_vaccination_sale_return_vaccination.vaccine_id=hms_vaccination_entry.id','left');
			
			//$this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');

			$this->db->where('hms_vaccination_sale_return.branch_id',$users_data['parent_id']);
			$this->db->where('hms_vaccination_sale_return_vaccination.vaccine_id',$vaccine_id);
			
			$this->db->where('hms_vaccination_sale_return.is_deleted',0);
			$this->db->order_by('hms_vaccination_sale_return.id','DESC');
		}
		if(!empty($type) && $type==5) //Branch allot
		{
			$this->db->select('hms_vaccination_entry.*,hms_vaccination_company.company_name,hms_vaccination_stock.credit as quantity,hms_vaccination_stock.created_date,hms_vaccination_stock.parent_id,hms_branch.branch_name');
			$this->db->from('hms_vaccination_entry');
			$this->db->join('hms_vaccination_company','hms_vaccination_entry.manuf_company=hms_vaccination_company.id','left');

			$this->db->join('hms_vaccination_stock','hms_vaccination_stock.v_id=hms_vaccination_entry.id');

			$this->db->join('hms_branch','hms_vaccination_stock.parent_id=hms_branch.id','left');
			$this->db->where('hms_vaccination_stock.branch_id',$users_data['parent_id']);
			$this->db->where('hms_vaccination_stock.type',$type);
			$this->db->where('hms_vaccination_stock.credit > 0');  
			$this->db->where('hms_vaccination_stock.v_id',$vaccine_id);
			//$this->db->where('hms_vaccination_stock.branch_id',$branch_id);
			$this->db->order_by('hms_vaccination_stock.id','DESC');

			
		}
    }


    function get_medicine_allot_history_count_filtered($vaccine_id="",$batch_no='',$type='',$branch_id='')
	{
		$this->_get_medicine_allot_history_datatables_query($vaccine_id,$batch_no,$type,$branch_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_medicine_allot_history_count_all($vaccine_id="",$batch_no='',$type='',$branch_id='')
	{
		$this->_get_medicine_allot_history_datatables_query($vaccine_id,$batch_no,$type,$branch_id);
		$query = $this->db->get();
		return $query->num_rows();

	}



	
} 
?>