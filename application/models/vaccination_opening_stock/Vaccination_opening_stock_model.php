<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vaccination_opening_stock_model extends CI_Model 
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
		$this->db->select('hms_vaccination_stock.*, hms_vaccination_racks.rack_no as rack_nu, hms_vaccination_entry.*,hms_vaccination_entry.id as m_eid,hms_vaccination_entry.created_date as create,hms_vaccination_company.company_name');
		$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_stock.v_id');   
		
		//$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.medicine_id = hms_vaccination_stock.v_id','left'); 

		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)>0');
		$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		//$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
		//$this->db->join('hms_vaccination_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_vaccination_entry.unit_second_id','left');
		$this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->where('hms_vaccination_stock.type','6');
		$this->db->from($this->table); 
		$this->db->group_by('hms_vaccination_stock.batch_no');
		$this->db->group_by('hms_vaccination_stock.v_id');
        //$this->db->group_by('hms_vaccination_stock.id');

		$i = 0;


		if(isset($search) && !empty($search))
		{
		if(!empty($search['start_date']))
		{
		$start_date = date('Y-m-d h:i:s',strtotime($search['start_date']));
		$this->db->where('hms_vaccination_stock.created_date >= "'.$start_date.'"');
		}

		if(!empty($search['end_date']))
		{
		$end_date = date('Y-m-d h:i:s',strtotime($search['end_date']));
		$this->db->where('hms_vaccination_stock.created_date <= "'.$end_date.'"');
		}

		if(!empty($search['vaccination_name']))
		{
			$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['vaccination_name'].'%"');
		}

		if(!empty($search['vaccination_company']))
		{
			 
			$this->db->where('hms_vaccination_company.company_name LIKE"'.$search['vaccination_company'].'%"');
		}

		if(!empty($search['vaccination_code']))
		{
		
			$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$search['vaccination_code'].'%"');
		}
		if(!empty($search['expiry_to']))
		{
		    $expiry_to = date('Y-m-d',strtotime($search['expiry_to']));
			$this->db->where('hms_vaccination_stock.expiry_date >="'.$expiry_to.'"');
		}
		if(!empty($search['expiry_from']))
		{
		    $expiry_from = date('Y-m-d',strtotime($search['expiry_from']));
			$this->db->where('hms_vaccination_stock.expiry_date <="'.$expiry_from.'"');
		}


		if(!empty($search['batch_no']))
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

		if($search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)>='.$search['qty_to']);
		
		}
		if($search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)<='.$search['qty_from']);
		}


		if($search['price_to_mrp']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp >="'.$search['price_to_mrp'].'"');
		}

		if($search['price_from_mrp']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp <="'.$search['price_to_mrp'].'"');
		}

		if($search['price_to_purchase']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate >= "'.$search['price_to_purchase'].'"');
		}

		if($search['price_from_purchase']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate <="'.$search['price_to_purchase'].'"');
		}

		if($search['rack_no']!="")
		{
			//$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
			$this->db->where('hms_vaccination_racks.rack_no',$search['rack_no']);
			
		}
		if($search['min_alert']!="")
		{
			$this->db->where('hms_vaccination_entry.min_alrt',$search['min_alert']);
		}
             

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
		$this->db->select('hms_vaccination_stock.*, hms_vaccination_racks.rack_no as rack_nu, hms_vaccination_entry.*,hms_medicine_purchase_to_purchase.expiry_date,hms_vaccination_entry.id as m_eid,hms_vaccination_entry.created_date as create,hms_vaccination_company.company_name');
		$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_stock.v_id');   
		$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.medicine_id = hms_vaccination_stock.v_id','left');   
		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)>0');
		$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		//$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
		//$this->db->join('hms_vaccination_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_vaccination_entry.unit_second_id','left');
		$this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->from($this->table); 
		$this->db->group_by('hms_vaccination_stock.batch_no');
		$this->db->group_by('hms_vaccination_stock.v_id');
		if(isset($search) && !empty($search))
		{
		if(!empty($search['start_date']))
		{
		$start_date = date('Y-m-d h:i:s',strtotime($search['start_date']));
		$this->db->where('hms_vaccination_stock.created_date >= "'.$start_date.'"');
		}

		if(!empty($search['end_date']))
		{
		$end_date = date('Y-m-d h:i:s',strtotime($search['end_date']));
		$this->db->where('hms_vaccination_stock.created_date <= "'.$end_date.'"');
		}

		if(!empty($search['vaccination_name']))
		{
			$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['vaccination_name'].'%"');
		}

		if(!empty($search['vaccination_company']))
		{
			 
			$this->db->where('hms_vaccination_company.company_name LIKE"'.$search['vaccination_company'].'%"');
		}

		if(!empty($search['vaccination_code']))
		{
		
			$this->db->where('hms_vaccination_entry.vaccination_code LIKE "'.$search['vaccination_code'].'%"');
		}
		if(!empty($search['expiry_to']))
		{
		    $expiry_to = date('Y-m-d',strtotime($search['expiry_to']));
			$this->db->where('hms_medicine_purchase_to_purchase.expiry_date >="'.$expiry_to.'"');
		}
		if(!empty($search['expiry_from']))
		{
		    $expiry_from = date('Y-m-d',strtotime($search['expiry_from']));
			$this->db->where('hms_medicine_purchase_to_purchase.expiry_date <="'.$expiry_from.'"');
		}


		if(!empty($search['batch_no']))
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

		if($search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)>='.$search['qty_to']);
		
		}
		if($search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where v_id = hms_vaccination_entry.id)<='.$search['qty_from']);
		}


		if($search['price_to_mrp']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp >="'.$search['price_to_mrp'].'"');
		}

		if($search['price_from_mrp']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp <="'.$search['price_to_mrp'].'"');
		}

		if($search['price_to_purchase']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate >= "'.$search['price_to_purchase'].'"');
		}

		if($search['price_from_purchase']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate <="'.$search['price_to_purchase'].'"');
		}

		if($search['rack_no']!="")
		{
			//$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
			$this->db->where('hms_vaccination_racks.rack_no',$search['rack_no']);
			
		}
		if($search['min_alert']!="")
		{
			$this->db->where('hms_vaccination_entry.min_alrt',$search['min_alert']);
		}
             

		}
		 $query = $this->db->get(); 

		$data= $query->result();
		
		return $data;
        //$this->db->group_by('hms_vaccination_stock.id');
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		// echo $this->db->last_query();die;
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

	public function get_batch_med_qty($mid="",$batch_no="")
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('(sum(debit)-sum(credit)) as total_qty');
		$this->db->where('branch_id',$user_data['parent_id']); 
		$this->db->where('v_id',$mid);
		$this->db->where('batch_no',$batch_no);
		$query = $this->db->get('hms_vaccination_stock');
		return $query->row_array();
	}
	

	public function get_by_id($id="",$batch_no="")
	{

		$this->db->select("hms_vaccination_entry.*, hms_vaccination_company.company_name, hms_vaccination_unit.vaccination_unit,hms_vaccination_stock.v_id,hms_vaccination_stock.expiry_date,hms_vaccination_stock.batch_no,hms_vaccination_stock.bar_code"); 
		$this->db->from('hms_vaccination_entry'); 
		$this->db->where('hms_vaccination_entry.id',$id);
		$this->db->where('hms_vaccination_stock.type',6);
		if(empty($batch_no))
		{
		$this->db->where('hms_vaccination_stock.batch_no','');	
		}
		else
		{
		 $this->db->where('hms_vaccination_stock.batch_no',$batch_no);	
		}
		$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id=hms_vaccination_entry.manuf_company','left');
		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id=hms_vaccination_entry.rack_no','left');
		$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id=hms_vaccination_entry.unit_id','left');
		$this->db->join('hms_vaccination_stock','hms_vaccination_stock.v_id=hms_vaccination_entry.id','left');
		$this->db->group_by('hms_vaccination_stock.v_id');
		$query = $this->db->get(); 
	   //echo $this->db->last_query();die;
		return $query->row_array();
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
    	//echo $unit_id;
       $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
         if(!empty($unit_id)){
         	
        	$this->db->where('id',$unit_id);
        }
        $this->db->order_by('vaccination_unit','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('hms_vaccination_unit.branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_vaccination_unit');
        $result = $query->result(); 
       //print '<pre>'; print_r($result);
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
        $this->db->where('hms_vaccination_racks.branch_id',$users_data['parent_id']);
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
        $this->db->where('hms_vaccination_company.branch_id', $users_data['parent_id']);
        $query = $this->db->get('hms_vaccination_company');
        $result = $query->result(); 
        return $result; 
    }

	public function save()
	{
		
		$users_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$expiry_date='';
		if(isset($post['expiry_date']) && !empty($post['expiry_date']))
		{
				$expiry_date=date('Y-m-d',strtotime($post['expiry_date']));
		}
		else
		{
				$expiry_date=date('Y-m-d');
		} 
				$data = array(
				"vaccination_code"=>$post['vaccination_code'],
				"vaccination_name"=>$post['vaccination_name'],
				'branch_id'=>$users_data['parent_id'],
				// "unit_id"=>$post['unit_id'],
				// "unit_second_id"=>$post['unit_second_id'],
				
				// "min_alrt"=>$post['min_alrt'],
				// "packing"=>$post['packing'],
				// "rack_no"=>$post['rack_no'],
				// "salt"=>$post['salt'],
				"manuf_company"=>$post['manuf_company'],
				// "mrp"=>$post['mrp'],
				// "purchase_rate"=>$post['purchase_rate'],
				// "discount"=>$post['discount'],
				// "vat"=>$post['vat'],
				// "status"=>$post['status']
			); 
				if(!empty($post['conversion'])){
					$data['conversion'] = $post['conversion'];
				}else{
					$data['conversion'] ='1';
				}
		if(!empty($post['data_id']) && $post['data_id']>0)
		{  
		

            //update into medicine_entry
			// if(!empty($medicine_details)){
            $this->db->set('modified_by',$users_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_vaccination_entry',$data); 
			//ends
             $debit = ($post['unit1_quantity']*$post['conversion'])+$post['unit2_quantity'];
             $per_pic_rate = $post['mrp']/$post['conversion'];
			//add into medicine_stock
			 $update_stock_data = array( 
			        'branch_id'=>$users_data['parent_id'],
                    'type'=>6,
                    'v_id'=>$post['data_id'],
                    'credit'=>'0',
                    'mrp'=>$post['mrp'],
                     'per_pic_rate'=>$per_pic_rate,
			        'batch_no'=>$post['batch_no'],
			        'bar_code'=>$post['bar_code'],
					//'unit1'=>$post['unit1_quantity'],
					//'unit2'=>$post['unit2_quantity'],
			        'ip_address'=>$_SERVER['REMOTE_ADDR'],
			        'created_date'=>date('Y-m-d H:i:s'),
			        'modified_date'=>date('Y-m-d H:i:s'),
			        'created_by'=>$users_data['parent_id'],
			       
			        );
			 if(!empty($post['conversion'])){
					$update_stock_data['conversion'] = $post['conversion'];
				}else{
					$update_stock_data['conversion'] ='1';
				}
            $update_stock_data['debit']=$debit;
			 
			$this->db->insert('hms_vaccination_stock',$update_stock_data);

		}
		else{ 
			///////// Check Company ////////////
            $m_company_id='';
            $company_detail = get_medicine_company($post['manuf_company'],$users_data['parent_id']);
         
          
            if(!empty($company_detail[0]['company_name']))
            {
		        $this->db->select("hms_vaccination_company.*");
			    $this->db->from('hms_vaccination_company'); 
		        $this->db->where('LOWER(hms_vaccination_company.company_name)',strtolower($company_detail[0]['company_name'])); 
			    $this->db->where('hms_vaccination_company.branch_id='.$users_data['parent_id']);  
				$query = $this->db->get(); 
			    // echo $this->db->last_query();die;
			    $medicine_company_data = $query->result_array();

                if(!empty($medicine_company_data))
				{
				    $m_company_id = $medicine_company_data[0]['id'];
				}
			    else
			    {
					$medicine_company_data = array(
					   'branch_id'=>$users_data['parent_id'],
						'company_name'=>$opening_stock['company_name'],
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'created_by'=>$users_data['parent_id'],
						'created_date'=>date('Y-m-d H:i:s'),
						'status'=>'1'
					);
					$this->db->insert('hms_vaccination_company',$medicine_company_data);
							$m_company_id = $this->db->insert_id();
				}
			}
		    ////////////////////////////////////
            
            //////////Check Medicine Name////////////////

        	$this->db->select('*');
        	$this->db->where('branch_id',$users_data['parent_id']);
        	$this->db->where('LOWER(vaccination_name)',strtolower($post['vaccination_name']));
        	$query = $this->db->get('hms_vaccination_entry');
        	$medicine_entry_data = $query->result_array();
        
        	if(!empty($medicine_entry_data))
        	{
                 $med_id = $medicine_entry_data[0]['id'];
        	}
        	else
        	{
        		$reg_no = generate_unique_id(10);
                $new_add_medicine = array(
                	'vaccination_code'=>$reg_no,
			        'branch_id'=>$users_data['parent_id'],
			        'vaccination_name'=>$post['vaccination_name'],
			        
			        'manuf_company'=>$m_company_id,
			        'mrp'=>$post['mrp'],
			        'ip_address'=>$_SERVER['REMOTE_ADDR'],
			        'created_by'=>$users_data['id'],  
			        'created_date'=>date('Y-m-d H:i:s'),
			        'status'=>'1'
                );
                if(!empty($post['conversion'])){
			        $new_add_medicine['conversion'] = $post['conversion'];
		        }else{
			        $new_add_medicine['conversion'] ='1';
		        }
			    $this->db->insert('hms_vaccination_entry',$new_add_medicine);
			    $med_id = $this->db->insert_id();
        	}
      
        	if(empty($post['conversion'])){
				$conversion = 1;
			}else
			{
				$conversion = $post['conversion'];
			}
            //add into medicine_stock
			$debit = ($post['unit1_quantity']*$post['conversion'])+$post['unit2_quantity'];
			
			$per_pic_rate = $post['mrp']/$conversion;
			 $update_stock_data = array( 
			        'branch_id'=>$users_data['parent_id'],
                    'type'=>6,
                    'v_id'=>$med_id,
                    'credit'=>'0',
                    'mrp'=>$post['mrp'],
			        'per_pic_rate'=>$per_pic_rate,
			        'batch_no'=>$post['batch_no'],
			        'bar_code'=>$post['bar_code'],
			        //'unit1'=>$post['unit1_quantity'],
					//'unit2'=>$post['unit2_quantity'],
			        'ip_address'=>$_SERVER['REMOTE_ADDR'],
			        'created_date'=>date('Y-m-d H:i:s'),
			        'modified_date'=>date('Y-m-d H:i:s'),
			        'created_by'=>$users_data['parent_id'],
			        'manuf_date'=>date('Y-m-d'),
			        'expiry_date'=>$expiry_date,
			        );
			 if(!empty($post['conversion'])){
					$update_stock_data['conversion'] = $post['conversion'];
				}else{
					$update_stock_data['conversion'] ='1';
				}
			 $update_stock_data['debit']=$debit;


			
			$this->db->insert('hms_vaccination_stock',$update_stock_data);
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

   

    
    

    

     

    public function get_medicine_list()
    {
    	$users_data = $this->session->userdata('auth_users');
    	$medicine_ids = $this->session->userdata('alloted_medicine_ids');
        $result = array();
    	if(!empty($medicine_ids))
    	{
    		$id_list = [];
    		foreach($medicine_ids as $id)
    		{
    		    if(!empty($id) && $id>0)
    			{
                    $id_list[]  = $id;
    			}
    		} 
    		
    		$medicine_ids = implode(',', $id_list);
    		
    	   $this->db->select("hms_vaccination_entry.*,hms_vaccination_stock.batch_no,hms_vaccination_stock.v_id");
	    	$this->db->from('hms_vaccination_entry');
	
            $this->db->join('hms_vaccination_stock','hms_vaccination_entry.id=hms_vaccination_stock.v_id');
          
            $this->db->where('hms_vaccination_entry.id IN ('.$medicine_ids.')');
            $this->db->where('hms_vaccination_stock.v_id IN ('.$medicine_ids.')');
           
	    	$this->db->where('(hms_vaccination_entry.is_deleted=0 and hms_vaccination_stock.is_deleted=0)');
	    	$this->db->where('(hms_vaccination_entry.branch_id='.$users_data['parent_id'].' and hms_vaccination_stock.branch_id='.$users_data['parent_id'].')');
	    	$this->db->group_by('hms_vaccination_stock.v_id');
	    	$query = $this->db->get();
	    	// echo $this->db->last_query();die;
	    	$result = $query->result_array();
    	}
    	return $result;
    }

   
	public function save_all_opening_stock($opening_stock_medicine = array())
	{
	

		$users_data = $this->session->userdata('auth_users');
        if(!empty($opening_stock_medicine))
        {
            foreach($opening_stock_medicine as $opening_stock)
            {
            	if(!empty($opening_stock['vaccination_name']) && !empty($opening_stock['mrp']) && !empty($opening_stock['unit2_qty']))
            	{
            	
                
	  //           	///////// Check Company ////////////
            		$m_company_id='';
            		if(!empty($opening_stock['company_name']))
            		{
		            	$this->db->select("hms_vaccination_company.*");
					    $this->db->from('hms_vaccination_company'); 
		                $this->db->where('LOWER(hms_vaccination_company.company_name)',strtolower($opening_stock['company_name'])); 
					    $this->db->where('hms_vaccination_company.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $medicine_company_data = $query->result_array();

					    if(!empty($medicine_company_data))
					    {
						    $m_company_id = $medicine_company_data[0]['id'];
					    }
					    else
					    {
							$medicine_company_data = array(
							'branch_id'=>$users_data['parent_id'],
							'company_name'=>$opening_stock['company_name'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_vaccination_company',$medicine_company_data);
							$m_company_id = $this->db->insert_id();
					    }
					}
				    ////////////////////////////////////

	            	//////////Check Medicine Name////////////////

	            	$this->db->select('*');
	            	$this->db->where('branch_id',$users_data['parent_id']);
	            	$this->db->where('LOWER(vaccination_name)',strtolower($opening_stock['vaccination_name']));
	            	$query = $this->db->get('hms_vaccination_entry');
	            	$medicine_entry_data = $query->result_array();
	            	if(!empty($medicine_entry_data))
	            	{
	                     $med_id = $medicine_entry_data[0]['id'];
	            	}
	            	else
	            	{
	            		$reg_no = generate_unique_id(10);
	                    $new_add_medicine = array(
	                    	'vaccination_code'=>$reg_no,
					        'branch_id'=>$users_data['parent_id'],
					        'vaccination_name'=>$opening_stock['vaccination_name'],
					        
					        'manuf_company'=>$m_company_id,
					        'mrp'=>$opening_stock['mrp'],
					        'ip_address'=>$_SERVER['REMOTE_ADDR'],
					        'created_by'=>$users_data['id'],  
					        'created_date'=>date('Y-m-d H:i:s'),
					        'status'=>'1'
	                    );
	                    if(!empty($opening_stock['conversion'])){
					        $new_add_medicine['conversion'] = $opening_stock['conversion'];
				        }else{
					        $new_add_medicine['conversion'] ='1';
				        }
					    $this->db->insert('hms_vaccination_entry',$new_add_medicine);
					    $med_id = $this->db->insert_id();
	            	}
	            	////////////////////////////////////

	            	///////////////// Stock Entry //////////
			    
			        /////////medicine allocate by branch //////////
			        if(empty($post['conversion'])){
				         $conversion = 1;
			        }else
			        {
				        $conversion = $post['conversion'];
			         }
			        $debit = ($opening_stock['unit1_qty']*$conversion)+$opening_stock['unit2_qty'];
			        // $per_pic_rate = $opening_stock['mrp']/$opening_stock['conversion'];
			        $per_pic_rate = $opening_stock['mrp'];
				    $opening_stock_data = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'type'=>6,
	                    'v_id'=>$med_id,
	                    'credit'=>'0',
	                    'per_pic_rate'=>$per_pic_rate,
	                    'mrp'=>$opening_stock['mrp'],
				        'debit'=>$debit,
				        'batch_no'=>$opening_stock['batch_no'],
				        'bar_code'=>$opening_stock['bar_code'],
				        'ip_address'=>$_SERVER['REMOTE_ADDR'],
				        'created_date'=>date('Y-m-d H:i:s'),
				        'manuf_date'=>date('Y-m-d'),
				        'modified_date'=>date('Y-m-d H:i:s'),
				        'created_by'=>$users_data['parent_id']
				        
				    );
				   //print '<pre>'; print_r($opening_stock);die;
				    if(!empty($opening_stock['conversion'])){
					        $opening_stock_data['conversion'] = $opening_stock['conversion'];
				        }else{
					        $opening_stock_data['conversion'] ='1';
				        }
				    if(!empty($opening_stock['expiry_date'])){
				    	$opening_stock_data['expiry_date'] = date('Y-m-d', strtotime($opening_stock['expiry_date']));
				    }else{
				    	$opening_stock_data['expiry_date']='0000-00-00';
				    }
	                $this->db->insert('hms_vaccination_stock',$opening_stock_data);
	            }
            }   	
        }
	}
     public function get_vaccination_name($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('vaccination_name','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('vaccination_name LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_vaccination_entry');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	        		//$response[] = $vals->vaccination_name;
					$name = $vals->vaccination_name.'|'.$vals->conversion.'|'.$vals->mrp.'|'.$vals->manuf_company.'|'.$vals->id;
					array_push($data, $name);
	        	}

	        	echo json_encode($data);
	        }
	        //return $response; 
    	} 
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
				$result= $this->db->get('hms_vaccination_stock')->result();
				foreach($result as $res)
				{
					$res_new_medicine_array[]= $res->v_id;
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
	
} 
?>