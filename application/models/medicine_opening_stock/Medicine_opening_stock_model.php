<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Medicine_opening_stock_model extends CI_Model 
{
	var $table = 'hms_medicine_stock';
	var $column = array('hms_medicine_entry.id','hms_medicine_entry.medicine_code', 'hms_medicine_entry.medicine_name', 'hms_medicine_entry.unit_id','hms_medicine_entry.unit_second_id','hms_medicine_entry.conversion','hms_medicine_entry.min_alrt','hms_medicine_entry.packing','hms_medicine_entry.rack_no','hms_medicine_entry.salt','hms_medicine_entry.manuf_company','hms_medicine_entry.mrp','hms_medicine_entry.purchase_rate','hms_medicine_entry.discount','hms_medicine_entry.vat','hms_medicine_entry.status', 'hms_medicine_entry.created_date', 'hms_medicine_entry.modified_date');  
	var $order = array('hms_medicine_stock.id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search=$this->session->userdata('stock_search');
		$this->db->select('hms_medicine_stock.*,hms_medicine_stock.id as stock_id,hms_medicine_stock.mrp as new_mrp, hms_medicine_racks.rack_no as rack_nu, hms_medicine_entry.*,hms_medicine_entry.id as m_eid,hms_medicine_entry.created_date as create,hms_medicine_company.company_name,hms_medicine_stock.purchase_rate as med_purchase');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id');   
		$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
		$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		$this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->where('hms_medicine_stock.type','6');
		$this->db->from($this->table); 
		$this->db->group_by('hms_medicine_stock.batch_no');
		$this->db->group_by('hms_medicine_stock.m_id');
        //$this->db->group_by('hms_medicine_stock.id');

		$i = 0;


		if(isset($search) && !empty($search))
		{
		if(!empty($search['start_date']))
		{
		$start_date = date('Y-m-d h:i:s',strtotime($search['start_date']));
		$this->db->where('hms_medicine_stock.created_date >= "'.$start_date.'"');
		}

		if(!empty($search['end_date']))
		{
		$end_date = date('Y-m-d h:i:s',strtotime($search['end_date']));
		$this->db->where('hms_medicine_stock.created_date <= "'.$end_date.'"');
		}

		if(!empty($search['medicine_name']))
		{
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
		}

		if(!empty($search['medicine_company']))
		{
			 
			$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
		}

		if(!empty($search['medicine_code']))
		{
		
			$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
		}
		if(!empty($search['expiry_to']))
		{
		    $expiry_to = date('Y-m-d',strtotime($search['expiry_to']));
			$this->db->where('hms_medicine_stock.expiry_date >="'.$expiry_to.'"');
		}
		if(!empty($search['expiry_from']))
		{
		    $expiry_from = date('Y-m-d',strtotime($search['expiry_from']));
			$this->db->where('hms_medicine_stock.expiry_date <="'.$expiry_from.'"');
		}


		if(!empty($search['batch_no']))
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

		if($search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)>='.$search['qty_to']);
		
		}
		if($search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)<='.$search['qty_from']);
		}


		if($search['price_to_mrp']!="")
		{
			$this->db->where('hms_medicine_entry.mrp >="'.$search['price_to_mrp'].'"');
		}

		if($search['price_from_mrp']!="")
		{
			$this->db->where('hms_medicine_entry.mrp <="'.$search['price_to_mrp'].'"');
		}

		if($search['price_to_purchase']!="")
		{
			$this->db->where('hms_medicine_entry.purchase_rate >= "'.$search['price_to_purchase'].'"');
		}

		if($search['price_from_purchase']!="")
		{
			$this->db->where('hms_medicine_entry.purchase_rate <="'.$search['price_to_purchase'].'"');
		}

		if($search['rack_no']!="")
		{
			//$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
			$this->db->where('hms_medicine_racks.rack_no',$search['rack_no']);
			
		}
		if($search['min_alert']!="")
		{
			$this->db->where('hms_medicine_entry.min_alrt',$search['min_alert']);
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
		$this->db->select('hms_medicine_stock.*, hms_medicine_racks.rack_no as rack_nu, hms_medicine_entry.*,hms_medicine_purchase_to_purchase.expiry_date,hms_medicine_entry.id as m_eid,hms_medicine_entry.created_date as create,hms_medicine_company.company_name,hms_medicine_stock.purchase_rate as med_purchase');
		$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id');   
		$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.medicine_id = hms_medicine_stock.m_id','left');   
		$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
		$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
		$this->db->where('hms_medicine_company.is_deleted',0);
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		$this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->from($this->table); 
		$this->db->group_by('hms_medicine_stock.batch_no');
		$this->db->group_by('hms_medicine_stock.m_id');
		if(isset($search) && !empty($search))
		{
		if(!empty($search['start_date']))
		{
		$start_date = date('Y-m-d h:i:s',strtotime($search['start_date']));
		$this->db->where('hms_medicine_stock.created_date >= "'.$start_date.'"');
		}

		if(!empty($search['end_date']))
		{
		$end_date = date('Y-m-d h:i:s',strtotime($search['end_date']));
		$this->db->where('hms_medicine_stock.created_date <= "'.$end_date.'"');
		}

		if(!empty($search['medicine_name']))
		{
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
		}

		if(!empty($search['medicine_company']))
		{
			 
			$this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
		}

		if(!empty($search['medicine_code']))
		{
		
			$this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
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

		if($search['qty_to']!="")
		{

			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)>='.$search['qty_to']);
		
		}
		if($search['qty_from']!="")
		{
			$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)<='.$search['qty_from']);
		}


		if($search['price_to_mrp']!="")
		{
			$this->db->where('hms_medicine_entry.mrp >="'.$search['price_to_mrp'].'"');
		}

		if($search['price_from_mrp']!="")
		{
			$this->db->where('hms_medicine_entry.mrp <="'.$search['price_to_mrp'].'"');
		}

		if($search['price_to_purchase']!="")
		{
			$this->db->where('hms_medicine_entry.purchase_rate >= "'.$search['price_to_purchase'].'"');
		}

		if($search['price_from_purchase']!="")
		{
			$this->db->where('hms_medicine_entry.purchase_rate <="'.$search['price_to_purchase'].'"');
		}

		if($search['rack_no']!="")
		{
			$this->db->where('hms_medicine_racks.rack_no',$search['rack_no']);
			
		}
		if($search['min_alert']!="")
		{
			$this->db->where('hms_medicine_entry.min_alrt',$search['min_alert']);
		}
             

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
		$this->db->where('m_id',$mid);
		$this->db->where('batch_no',$batch_no);
		$query = $this->db->get('hms_medicine_stock');
		return $query->row_array();
	}
	
	public function get_batch_med_validate_qty($mid="",$batch_no="")
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('(sum(debit)-sum(credit)) as total_qty');
		$this->db->where('branch_id',$user_data['parent_id']); 
		//$this->db->where('type!=6'); 
		$this->db->where('m_id',$mid);
		$this->db->where('is_deleted',0); //added on 27 dec 21
		$this->db->where('batch_no',$batch_no);
		$query = $this->db->get('hms_medicine_stock');
		//echo $this->db->last_query(); exit;
		return $query->row_array();
	}
	

    // changes by Nitin Sharma 1st Feb 2024
	public function get_by_id($id="",$batch_no="0")
	{
		$this->db->select("hms_medicine_entry.*, hms_medicine_company.company_name, hms_medicine_unit.medicine_unit,hms_medicine_stock.m_id,hms_medicine_stock.expiry_date,hms_medicine_stock.batch_no,hms_medicine_stock.bar_code,hms_medicine_stock.id as stock_id,hms_medicine_stock.unit1,hms_medicine_stock.unit2,hms_medicine_stock.mrp as new_mrp,hms_medicine_stock.purchase_rate as med_purchase,hms_medicine_batch_stock.quantity as total_quantity"); 
		$this->db->from('hms_medicine_entry'); 
		$this->db->where('hms_medicine_entry.id',$id);
        $this->db->where('hms_medicine_stock.type',6);
		$this->db->where('hms_medicine_stock.batch_no',$batch_no);	
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		$this->db->join('hms_medicine_company','hms_medicine_company.id=hms_medicine_entry.manuf_company','left');
		$this->db->join('hms_medicine_racks','hms_medicine_racks.id=hms_medicine_entry.rack_no','left');
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_stock','hms_medicine_stock.m_id=hms_medicine_entry.id','left');
		$this->db->join('hms_medicine_batch_stock','hms_medicine_batch_stock.medicine_id=hms_medicine_entry.id','left');
		$this->db->group_by('hms_medicine_stock.m_id');
		//$this->db->where('hms_medicine_company.is_deleted',0);
		$query = $this->db->get(); 
        // echo $this->db->last_query();die;
		return $query->row_array();
	}
	// changes Ended by Nitin Sharma 1st Feb 2024
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
    	//echo $unit_id;
       $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
         if(!empty($unit_id)){
         	
        	$this->db->where('id',$unit_id);
        }
        $this->db->order_by('medicine_unit','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('hms_medicine_unit.branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_medicine_unit');
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
        $this->db->where('hms_medicine_racks.branch_id',$users_data['parent_id']);
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
        $this->db->where('hms_medicine_company.branch_id', $users_data['parent_id']);
        $query = $this->db->get('hms_medicine_company');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

	public function save()
	{
		
		$users_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$manuf_comapny='';
		if(isset($post['manuf_company']) && !empty($post['manuf_company']))
		{
			$manuf_comapny=$post['manuf_company'];
		}
		else
		{
			$manuf_comapny='';

		}
		$expiry_date='';
		if(isset($post['expiry_date']) && !empty($post['expiry_date']))
		{
				$expiry_date=date('Y-m-d',strtotime($post['expiry_date']));
		}
		else
		{
				$expiry_date='';//date('Y-m-d');
		} 
		$data = array("medicine_code"=>$post['medicine_code'],
				      "medicine_name"=>$post['medicine_name'],
				      'branch_id'=>$users_data['parent_id'],
				      "manuf_company"=>$manuf_comapny); 
				
				if(!empty($post['conversion'])){
					$data['conversion'] = $post['conversion'];
				}else{
					$data['conversion'] ='1';
				}
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
		    $this->db->set('modified_by',$users_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_medicine_entry',$data); 
			//ends
             $debit = ($post['unit1_quantity']*$post['conversion'])+$post['unit2_quantity'];
             $per_pic_rate = $post['mrp']/$post['conversion'];
             
             if(!empty($post['batch_no']))
             {
                 $batchno = $post['batch_no'];
             }
             else
             {
                 $batchno = 0;
             }
             
             //get quantity on initial add opening stock item 
             
             
                $this->db->select("hms_medicine_stock.debit");
                $this->db->from('hms_medicine_stock'); 
                $where_opening= array('type'=>6,'id'=>$post['stock_id']);
                $this->db->where($where_opening);
                $this->db->where('hms_medicine_stock.branch_id='.$users_data['parent_id']);  
                $query = $this->db->get(); 
                // echo $this->db->last_query();die;
                $medicine_open_stk_data = $query->result_array();
                // print_r($medicine_company_data);die;
               $debit_on_add = $medicine_open_stk_data[0]['debit']; //qty on opening stock add
                
             ///echo $debit_on_add; die;
             
            //check stock quantity before update 
            
            $qty_data = $this->get_batch_med_validate_qty($post['data_id'],$batchno);
            //echo $qty_data['total_qty']; die;
            if($qty_data['total_qty']>$debit_on_add)
            {
               $new_qty = ($qty_data['total_qty']-$debit_on_add)+$debit; 
            }
            else //if($qty_data['total_qty'] < $debit_on_add)
            {
                $new_qty = $debit;
                
                //($debit_on_add-$qty_data['total_qty'])+$debit; 
            }
            /*else
            {
                $new_qty = $qty_data['total_qty']+$debit;
            }*/
            //echo $new_qty; die;
            
            /*$this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` + '".$debit."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$post['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$post['data_id']."' ");*/
            
            $this->db->query("UPDATE `hms_medicine_batch_stock` SET hms_medicine_batch_stock.expiry_date ='".$expiry_date."',`hms_medicine_batch_stock`.`quantity` = '".$new_qty."', 	 `hms_medicine_batch_stock`.`purchase_rate` =  '".$post['purchase_rate']."',`hms_medicine_batch_stock`.`mrp` =  '".$post['mrp']."'  WHERE `hms_medicine_batch_stock`.`batch_no` = '".$batchno."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$post['data_id']."' ");
             //echo $this->db->last_query(); exit;
             
             
            /* $where_delete= array('type'=>6,'id'=>$post['stock_id']);
             $this->db->where($where_delete);
             $this->db->delete('hms_medicine_stock');*/
             
             //get medicine 
             
             $this->db->select('*');
        	$this->db->where('branch_id',$users_data['parent_id']);
        	if(!empty($manuf_comapny))
        	{
        	  $this->db->where('manuf_company',$manuf_comapny);  
        	}
        	
        	$this->db->where('is_deleted=0');
            $this->db->where('id',$post['data_id']);
        	$query = $this->db->get('hms_medicine_entry');
        	$medicine_entry_data = $query->result_array();
             
			//add into medicine_stock
			 $update_stock_data = array( 
									'branch_id'=>$users_data['parent_id'],
									'type'=>6,
									'm_id'=>$post['data_id'],
									'credit'=>'0',
									'mrp'=>$post['mrp'],
									'per_pic_rate'=>$per_pic_rate,
									'batch_no'=>$batchno,
									'sgst'=>$medicine_entry_data[0]['sgst'],
                			        'cgst'=>$medicine_entry_data[0]['cgst'],
                			        'igst'=>$medicine_entry_data[0]['igst'],
                			        'purchase_rate'=>$post['purchase_rate'],
                			        'discount'=>$medicine_entry_data[0]['discount'],
									'bar_code'=>$post['bar_code'],
									'unit1'=>$post['unit1_quantity'],
									'unit2'=>$post['unit2_quantity'],
									 'expiry_date'=>$expiry_date,
						);
						
			    if(!empty($post['conversion'])){
					$update_stock_data['conversion'] = $post['conversion'];
				}else{
					$update_stock_data['conversion'] ='1';
				}
                $update_stock_data['debit']=$debit;
			
			$this->db->set('modified_by',$users_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['stock_id']);
			$this->db->update('hms_medicine_stock',$update_stock_data); 
			//echo $this->db->last_query(); exit;
			//$this->db->insert('hms_medicine_stock',$update_stock_data);
			//echo $this->db->last_query(); exit;
			
		

		}
		else
		{
		    
		    
		   if(!empty($post['batch_no']))
             {
                 $batchno = $post['batch_no'];
             }
             else
             {
                 $batchno = 0;
             }
             //check if opening stock already added for same medicine and same bach number
             $batch_stock = $this->check_batch_stock($post['medicine_idss'],$batchno,$post['medicine_name']);
             if(!empty($batch_stock))
             {
                 return 2;
             }
             //echo "<pre>"; print_r($batch_stock); exit;
		    
			///////// Check Company ////////////
            $m_company_id='';
            $company_detail = get_medicine_company($manuf_comapny,$users_data['parent_id']);
            if(!empty($company_detail[0]['company_name']))
            {
		        $this->db->select("hms_medicine_company.*");
			    $this->db->from('hms_medicine_company'); 
		        $this->db->where('LOWER(hms_medicine_company.company_name)',strtolower($company_detail[0]['company_name'])); 
			    $this->db->where('hms_medicine_company.branch_id='.$users_data['parent_id']);  
				$query = $this->db->get(); 
			    // echo $this->db->last_query();die;
			    $medicine_company_data = $query->result_array();
			   // print_r($medicine_company_data);die;

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
					$this->db->insert('hms_medicine_company',$medicine_company_data);
							$m_company_id = $this->db->insert_id();
				}
			}
		    ////////////////////////////////////
            
            //////////Check Medicine Name////////////////
            //purchase_rate	mrp	discount	sgst	cgst	igst of medicine
            
            $this->db->select('*');
        	$this->db->where('branch_id',$users_data['parent_id']);
        	if(!empty($m_company_id))
        	{
        	   $this->db->where('manuf_company',$m_company_id); 
        	}
        	
        	$this->db->where('is_deleted !=2');
            //$this->db->where('LOWER(medicine_name)',strtolower($post['medicine_name']));
            $this->db->where('id',$post['medicine_idss']);
        	$query = $this->db->get('hms_medicine_entry');
        	$medicine_entry_data = $query->result_array();
           if(!empty($post['medicine_idss']))
        	{
                 $med_id = $post['medicine_idss']; //$medicine_entry_data[0]['id'];
        	}
        	else
        	{

        		$reg_no = generate_unique_id(10);
                $new_add_medicine = array(
                	'medicine_code'=>$reg_no,
			        'branch_id'=>$users_data['parent_id'],
			        'medicine_name'=>$post['medicine_name'],
			        
			        'manuf_company'=>$m_company_id,
			        'mrp'=>$post['mrp'],
			        'purchase_rate'=>$post['purchase_rate'],
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
			    $this->db->insert('hms_medicine_entry',$new_add_medicine);
			   
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
			if(!empty($post['purchase_rate']))
			{
			    $purchase_rate = $post['purchase_rate'];
			}
			else
			{
			    $purchase_rate = $medicine_entry_data[0]['purchase_rate'];
			}
			if(!empty($medicine_entry_data[0]['sgst']))
			{
			    $sgst_val= $medicine_entry_data[0]['sgst'];
			}
			else
			{
			    $sgst_val='0.00';
			}
			if(!empty($medicine_entry_data[0]['cgst']))
			{
			    $cgst_val= $medicine_entry_data[0]['cgst'];
			}
			else
			{
			    $cgst_val='0.00';
			}
			if(!empty($medicine_entry_data[0]['igst']))
			{
			    $igst_val= $medicine_entry_data[0]['igst'];
			}
			else
			{
			    $igst_val='0.00';
			}
			if(!empty($medicine_entry_data[0]['discount']))
			{
			    $discount_vals= $medicine_entry_data[0]['discount'];
			}
			else
			{
			    $discount_vals='0.00';
			}
			 $update_stock_data = array( 
			        'branch_id'=>$users_data['parent_id'],
                    'type'=>6,
                    'm_id'=>$med_id,
                    'credit'=>'0',
                    'mrp'=>$per_pic_rate,
			        'per_pic_rate'=>$per_pic_rate,
			        'batch_no'=>$batchno,
			        'sgst'=>$sgst_val,
			        'cgst'=>$cgst_val,
			        'igst'=>$igst_val,
			        'purchase_rate'=>$purchase_rate,
			        'discount'=>$discount_vals,
			        'bar_code'=>$post['bar_code'],
					'unit1'=>$post['unit1_quantity'],
					'unit2'=>$post['unit2_quantity'],
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
			
			$this->db->insert('hms_medicine_stock',$update_stock_data);
			//echo $this->db->last_query(); die;
			
			
            $this->db->select('hms_medicine_batch_stock.id, hms_medicine_entry.discount,hms_medicine_entry.sgst,hms_medicine_entry.cgst,hms_medicine_entry.igst');
            $this->db->join('hms_medicine_entry', 'hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id', 'left');
            $this->db->where('hms_medicine_batch_stock.medicine_id',$med_id);
            $this->db->where('hms_medicine_batch_stock.batch_no',$batchno);
            $query_batch_stock = $this->db->get('hms_medicine_batch_stock'); 
            $row_batch_stock = $query_batch_stock->result();
		    //echo $this->db->last_query();die;
            
            if(!empty($row_batch_stock))
            {
               $this->db->query("UPDATE `hms_medicine_batch_stock` SET hms_medicine_batch_stock.expiry_date ='".$expiry_date."', `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` + '".$debit."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$batchno."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$med_id."' "); 
            }
            else
            {
                
                $this->db->select('hms_medicine_batch_stock.id, hms_medicine_entry.discount,hms_medicine_entry.sgst,hms_medicine_entry.cgst,hms_medicine_entry.igst');
                $this->db->join('hms_medicine_batch_stock', 'hms_medicine_batch_stock.medicine_id = hms_medicine_entry.id', 'left');
                $this->db->where('hms_medicine_entry.id',$med_id);
                //$this->db->where('hms_medicine_batch_stock.batch_no',$batchno);
                $query_batch_stock = $this->db->get('hms_medicine_entry'); 
                $row_batch_stock = $query_batch_stock->result();
                
                if(!empty($row_batch_stock[0]->discount))
                {
                    $batc_discount = $row_batch_stock[0]->discount;
                }
                else
                {
                    $batc_discount ='0.00';
                }
                $batch_stock_data = array(
                           'branch_id'=>$users_data['parent_id'],
                           'medicine_id'=>$med_id,
                           'batch_no'=>$batchno,
                           'hsn_no'=>'',
                           'quantity'=>$debit,
                           'purchase_rate'=>$post['purchase_rate'],
                           'mrp'=>$post['mrp'],
                           'discount'=>$batc_discount,
                           'vat'=>0,
                           'sgst'=>$row_batch_stock[0]->sgst,
                           'cgst'=>$row_batch_stock[0]->cgst,
                           'igst'=>$row_batch_stock[0]->igst,
                           'bar_code'=>$post['bar_code'],
                           'conversion'=>$post['conversion'],
                           'total_amount'=>$post['mrp'],
                           'expiry_date'=>$expiry_date,
                           'manuf_date'=>date('Y-m-d'),
                           'per_pic_rate'=>$per_pic_rate,
                           'created_date'=>date('Y-m-d')
					     ); 
					    $this->db->insert("hms_medicine_batch_stock", $batch_stock_data);
					    //echo $this->db->last_query();die; 
                
            }
            
          
          
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
    		
    	   $this->db->select("hms_medicine_entry.*,hms_medicine_stock.batch_no,hms_medicine_stock.m_id");
	    	$this->db->from('hms_medicine_entry');
	
            $this->db->join('hms_medicine_stock','hms_medicine_entry.id=hms_medicine_stock.m_id');
          
            $this->db->where('hms_medicine_entry.id IN ('.$medicine_ids.')');
            $this->db->where('hms_medicine_stock.m_id IN ('.$medicine_ids.')');
           
	    	$this->db->where('(hms_medicine_entry.is_deleted=0 and hms_medicine_stock.is_deleted=0)');
	    	$this->db->where('(hms_medicine_entry.branch_id='.$users_data['parent_id'].' and hms_medicine_stock.branch_id='.$users_data['parent_id'].')');
	    	$this->db->group_by('hms_medicine_stock.m_id');
	    	$query = $this->db->get();
	    	// echo $this->db->last_query();die;
	    	$result = $query->result_array();
    	}
    	return $result;
    }

   
	public function save_all_opening_stock($opening_stock_medicine = array())
	{
	 //echo "<pre>"; print_r($opening_stock_medicine); exit;	
		$users_data = $this->session->userdata('auth_users');
        if(!empty($opening_stock_medicine))
        {
            foreach($opening_stock_medicine as $opening_stock)
            {
            	if(!empty($opening_stock['medicine_name']) && !empty($opening_stock['mrp']))
            	{
            	
                
	  //           	///////// Check Company ////////////
            		$m_company_id='';
            		if(!empty($opening_stock['company_name']))
            		{
		            	$this->db->select("hms_medicine_company.*");
					    $this->db->from('hms_medicine_company'); 
		                $this->db->where('LOWER(hms_medicine_company.company_name)',strtolower($opening_stock['company_name'])); 
					    $this->db->where('hms_medicine_company.branch_id='.$users_data['parent_id']);  
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
							$this->db->insert('hms_medicine_company',$medicine_company_data);
							$m_company_id = $this->db->insert_id();
					    }
					}
				    ////////////////////////////////////

	            	//////////Check Medicine Name////////////////

	            	$this->db->select('*');
	            	$this->db->where('branch_id',$users_data['parent_id']);
	            	$this->db->where('LOWER(medicine_name)',strtolower($opening_stock['medicine_name']));
	            	$query = $this->db->get('hms_medicine_entry');
	            	$medicine_entry_data = $query->result_array();
	            	if(!empty($medicine_entry_data))
	            	{
	                     $med_id = $medicine_entry_data[0]['id'];
	            	}
	            	else
	            	{
	            		$reg_no = generate_unique_id(10);
	                    $new_add_medicine = array(
	                    	'medicine_code'=>$reg_no,
					        'branch_id'=>$users_data['parent_id'],
					        'medicine_name'=>$opening_stock['medicine_name'],
					        
					        'manuf_company'=>0,
					        'purchase_rate'=>$opening_stock['purchase_rate'],
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
					    $this->db->insert('hms_medicine_entry',$new_add_medicine);
					    $med_id = $this->db->insert_id();
	            	}
	            	////////////////////////////////////

	            	///////////////// Stock Entry //////////
			    
			        /////////medicine allocate by branch //////////
			        if(empty($opening_stock['conversion']) && $opening_stock['conversion']==''){
				         $conversion = 1;
			        }else
			        {
				        $conversion = $opening_stock['conversion'];
			         }
			        $debit = ($opening_stock['unit1_qty']*$conversion)+$opening_stock['unit2_qty'];
			        // $per_pic_rate = $opening_stock['mrp']/$opening_stock['conversion'];
			        $per_pic_rate = $opening_stock['mrp'];
				    $opening_stock_data = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'type'=>6,
	                    'm_id'=>$med_id,
	                    'credit'=>'0',
	                    'per_pic_rate'=>$per_pic_rate,
	                    'mrp'=>$opening_stock['mrp'],
	                    'purchase_rate'=>$opening_stock['purchase_rate'],
				        'debit'=>$debit,
				        'batch_no'=>$opening_stock['batch_no'],
				        'bar_code'=>$opening_stock['bar_code'],
				        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                        'manuf_date'=>date('Y-m-d'),
				        'created_date'=>date('Y-m-d H:i:s'),
				        'modified_date'=>date('Y-m-d H:i:s'),
				        'created_by'=>$users_data['parent_id']
				        
				    );
				    if(!empty($opening_stock['conversion'])){
					        $opening_stock_data['conversion'] = $opening_stock['conversion'];
					        $openin_conversion = $opening_stock['conversion'];
				        }else{
					        $opening_stock_data['conversion'] ='1';
					        $openin_conversion =1;
				        }
				    if(!empty($opening_stock['expiry_date'])){
				        
				        
				        $excelDate = $opening_stock['expiry_date'];
                        $timestamp = $excelDate * 60 * 60 * 24;
                        $mysqlDate = date('Y-m-d', $timestamp);
                        
                        $date = strtotime($mysqlDate.' -70 year');
                        $mysqlDatenew = date('Y-m-d', $date);
				        
				    	$opening_stock_data['expiry_date'] =$mysqlDatenew; //date('Y-m-d', strtotime($opening_stock['expiry_date']));
				    }else{
				    	$opening_stock_data['expiry_date']='0000-00-00';
				    }
	                $this->db->insert('hms_medicine_stock',$opening_stock_data);
	                //echo $this->db->last_query(); die;
	                
	                //update insert batch stock 
	                $this->db->select('hms_medicine_batch_stock.id, hms_medicine_entry.discount,hms_medicine_entry.sgst,hms_medicine_entry.cgst,hms_medicine_entry.igst');
            $this->db->join('hms_medicine_entry', 'hms_medicine_entry.id = hms_medicine_batch_stock.medicine_id', 'left');
            $this->db->where('hms_medicine_batch_stock.medicine_id',$med_id);
            $this->db->where('hms_medicine_batch_stock.batch_no',$opening_stock['batch_no']);
            $query_batch_stock = $this->db->get('hms_medicine_batch_stock'); 
            $row_batch_stock = $query_batch_stock->result();
		    //echo $this->db->last_query();die;
            
            if(!empty($row_batch_stock))
            {
               $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity` + '".$debit."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$opening_stock['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$med_id."' "); 
            }
            else
            {
                
                $this->db->select('hms_medicine_batch_stock.id, hms_medicine_entry.discount,hms_medicine_entry.sgst,hms_medicine_entry.cgst,hms_medicine_entry.igst');
                $this->db->join('hms_medicine_batch_stock', 'hms_medicine_batch_stock.medicine_id = hms_medicine_entry.id', 'left');
                $this->db->where('hms_medicine_entry.id',$med_id);
                //$this->db->where('hms_medicine_batch_stock.batch_no',$batchno);
                $query_batch_stock = $this->db->get('hms_medicine_entry'); 
                $row_batch_stock = $query_batch_stock->result();
                
                
                if(!empty($opening_stock['expiry_date'])){
				        
				        
				        $excelDate = $opening_stock['expiry_date'];
                        $timestamp = $excelDate * 60 * 60 * 24;
                        $mysqlDate = date('Y-m-d', $timestamp);
                        
                        $date = strtotime($mysqlDate.' -70 year');
                        $mysqlDatenew = date('Y-m-d', $date);
				        
				    	$expiry_date =$mysqlDatenew; //date('Y-m-d', strtotime($opening_stock['expiry_date']));
				    }else{
				            $expiry_date ='0000-00-00';
				    }
                
               
                $batch_stock_data = array(
                           'branch_id'=>$users_data['parent_id'],
                           'medicine_id'=>$med_id,
                           'batch_no'=>$opening_stock['batch_no'],
                           'hsn_no'=>'',
                           'quantity'=>$debit,
                           'purchase_rate'=>$opening_stock['purchase_rate'],
                           'mrp'=>$opening_stock['mrp'],
                           'discount'=>$row_batch_stock[0]->discount,
                           'vat'=>0,
                           'sgst'=>$row_batch_stock[0]->sgst,
                           'cgst'=>$row_batch_stock[0]->cgst,
                           'igst'=>$row_batch_stock[0]->igst,
                           'bar_code'=>$opening_stock['bar_code'],
                           'conversion'=>$openin_conversion,
                           'total_amount'=>$opening_stock['mrp'],
                           'expiry_date'=>$expiry_date,
                           'manuf_date'=>date('Y-m-d'),
                           'per_pic_rate'=>$per_pic_rate,
                           'created_date'=>date('Y-m-d')
					     ); 
					    $this->db->insert("hms_medicine_batch_stock", $batch_stock_data);
					    //echo $this->db->last_query();die; 
					    
            }
	                
	                
	                
	                
	            }
            }   	
        }
	}
     public function get_medicine_name($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_name','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_name LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_medicine_entry');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	        		//$response[] = $vals->medicine_name;
					$name = $vals->medicine_name.'|'.$vals->conversion.'|'.$vals->mrp.'|'.$vals->manuf_company.'|'.$vals->id.'|'.$vals->purchase_rate;
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
				$result= $this->db->get('hms_medicine_stock')->result();
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
	
	public function get_medicine_conversion($id="",$batch_no="")
	{

		$this->db->select("hms_medicine_entry.*, hms_medicine_company.company_name, hms_medicine_unit.medicine_unit,hms_medicine_stock.m_id,hms_medicine_stock.expiry_date,hms_medicine_stock.batch_no,hms_medicine_stock.bar_code,hms_medicine_stock.id as stock_id,hms_medicine_stock.unit1,hms_medicine_stock.unit2,hms_medicine_stock.mrp as new_mrp"); 
		$this->db->from('hms_medicine_entry'); 
		$this->db->where('hms_medicine_entry.id',$id);

		//$this->db->where('hms_medicine_stock.type',6);
	    if(!empty($batch_no))
	    {
	     $this->db->where('hms_medicine_stock.batch_no',$batch_no);	
		   
	    }
		 
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		$this->db->join('hms_medicine_company','hms_medicine_company.id=hms_medicine_entry.manuf_company','left');
		$this->db->join('hms_medicine_racks','hms_medicine_racks.id=hms_medicine_entry.rack_no','left');
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_stock','hms_medicine_stock.m_id=hms_medicine_entry.id','left');
		$this->db->group_by('hms_medicine_stock.m_id');
		//$this->db->where('hms_medicine_company.is_deleted',0);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}
	
	public function check_batch_stock($medicine_id="",$batch_no="0",$medicine_name='')
	{
	    $users_data = $this->session->userdata('auth_users'); 
	    if(!empty($medicine_name))
	    {
	        
	        $this->db->select('hms_medicine_entry.id');
        	$this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);
        	$this->db->where('LOWER(hms_medicine_entry.medicine_name)',strtolower($medicine_name));
        	$query = $this->db->get('hms_medicine_entry');
        	//echo $this->db->last_query(); exit;
        	$medicine_entry_open_data = $query->result_array();
        	if(!empty($medicine_entry_open_data))
        	{
                 $med_id = $medicine_entry_open_data[0]['id'];
        	}
	    }
	    if(!empty($medicine_id))
	    {
	        $check_med_id = $medicine_id;
	    }
	    else
	    {
	        $check_med_id = $med_id;
	    }
	    //echo $check_med_id; die;
	    if(!empty($check_med_id))
	    {
            
    		$this->db->select("hms_medicine_stock.id"); 
    		$this->db->from('hms_medicine_stock'); 
    		if(!empty($check_med_id))
    		{
    		    $this->db->where('hms_medicine_stock.m_id',$check_med_id);
    		}
            $this->db->where('hms_medicine_stock.type',6);
    	    $this->db->where('hms_medicine_stock.branch_id',$users_data['parent_id']);	
    	    $this->db->where('hms_medicine_stock.batch_no',$batch_no);	
    		$query = $this->db->get(); 
    		//echo $this->db->last_query();die;
    		return $query->row_array();
	    }
	    else
	    {
	        return 0;
	    }
	}
	
} 
?>