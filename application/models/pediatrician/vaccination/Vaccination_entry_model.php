<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vaccination_entry_model extends CI_Model 
{
	var $table = 'hms_vaccination_entry';
	var $column = array('hms_vaccination_entry.id', 'hms_vaccination_entry.vaccination_name', 'hms_vaccination_entry.unit_id','hms_vaccination_entry.unit_second_id','hms_vaccination_entry.conversion','hms_vaccination_entry.min_alrt','hms_vaccination_entry.packing','hms_vaccination_entry.rack_no','hms_vaccination_entry.salt','hms_vaccination_entry.manuf_company','hms_vaccination_entry.mrp','hms_vaccination_entry.purchase_rate','hms_vaccination_entry.discount','hms_vaccination_entry.vat','hms_vaccination_entry.status', 'hms_vaccination_entry.created_date', 'hms_vaccination_entry.modified_date');  


	var $order = array('hms_vaccination_entry.id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		 $search = $this->session->userdata('vaccination_entry_search');
		// print_r($search);die;
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_vaccination_entry.*,hms_vaccination_racks.rack_no,hms_vaccination_racks.id as rack_id,hms_vaccination_company.company_name"); 
		$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
		$this->db->where('hms_vaccination_entry.branch_id',$user_data['parent_id']); 
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');

		$this->db->join('hms_vaccination_unit', 'hms_vaccination_unit.id=hms_vaccination_entry.unit_id', 'left');
		$this->db->join('hms_vaccination_unit as vu', 'vu.id=hms_vaccination_entry.unit_second_id', 'left');
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
		if(!empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
		$this->db->where('hms_vaccination_entry.created_date >= "'.$start_date.'"');
		}

		if(!empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
		$this->db->where('hms_vaccination_entry.created_date <= "'.$end_date.'"');
		}

		if(!empty($search['vaccination_name']))
		{
			$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['vaccination_name'].'%"');
		}

		if(!empty($search['vaccination_company']))
		{
			// $this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
			$this->db->where('hms_vaccination_company.company_name LIKE"'.$search['vaccination_company'].'%"');
		}

		if(!empty($search['vaccination_code']))
		{
		
			$this->db->where('hms_vaccination_entry.vaccination_code',$search['vaccination_code']);
		}
		
		
		if(isset($search['unit1']) && $search['unit1']!="")
		{
			$this->db->where('hms_vaccination_unit.id = "'.$search['unit1'].'"');
		}

		if(isset($search['unit2']) && $search['unit2']!="")
		{
			$this->db->where('vu.id ="'.$search['unit2'].'"');
		}

		if(isset($search['packing']) && $search['packing']!="")
		{
		
		//$this->db->where('packing',$search['packing']);
			$this->db->where('hms_vaccination_entry.packing LIKE "'.$search['packing'].'%"');
		}
		if(isset($search['hsn_no']) && $search['hsn_no']!="")
		{

		//$this->db->where('packing',$search['packing']);
		$this->db->where('hms_vaccination_entry.hsn_no LIKE "'.$search['hsn_no'].'%"');
		}
		if(isset($search['cgst']) && $search['cgst']!="")
		{

		//$this->db->where('packing',$search['packing']);
		$this->db->where('hms_vaccination_entry.cgst LIKE "'.$search['cgst'].'%"');
		}
		if(isset($search['sgst']) && $search['sgst']!="")
		{

		//$this->db->where('packing',$search['packing']);
		$this->db->where('hms_vaccination_entry.sgst LIKE "'.$search['sgst'].'%"');
		}
		if(isset($search['igst']) && $search['igst']!="")
		{

		//$this->db->where('packing',$search['packing']);
		$this->db->where('hms_vaccination_entry.igst LIKE "'.$search['igst'].'%"');
		}
		

		if(isset($search['mrp_to']) && $search['mrp_to']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp >="'.$search['mrp_to'].'"');
		}

		if(isset($search['mrp_from']) &&$search['mrp_from']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp <="'.$search['mrp_from'].'"');
		}

		if(isset($search['purchase_to']) &&$search['purchase_to']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate >= "'.$search['purchase_to'].'"');
		}

		if(isset($search['purchase_from']) &&$search['purchase_from']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate <="'.$search['purchase_from'].'"');
		}

		if(isset($search['rack_no']) &&$search['rack_no']!="")
		{
			//$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
			$this->db->where('hms_vaccination_racks.rack_no',$search['rack_no']);
			
		}
		if(isset($search['min_alert']) &&$search['min_alert']!="")
		{
			$this->db->where('hms_vaccination_entry.min_alrt',$search['min_alert']);
		}
        if(isset($search['discount']) &&$search['discount']!="")
		{
			$this->db->where('hms_vaccination_entry.discount',$search['discount']);
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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}
	function search_report_data(){

		 $search = $this->session->userdata('vaccination_entry_search');
		// print_r($search);die;
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_vaccination_entry.*,hms_vaccination_racks.rack_no,hms_vaccination_racks.id as rack_id,hms_vaccination_company.company_name"); 
		$this->db->where('hms_vaccination_entry.is_deleted','0'); 
		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');


$this->db->join('hms_vaccination_unit', 'hms_vaccination_unit.id=hms_vaccination_entry.unit_id', 'left');
	$this->db->join('hms_vaccination_unit as vu', 'vu.id=hms_vaccination_entry.unit_second_id', 'left');


		$this->db->where('hms_vaccination_entry.branch_id',$user_data['parent_id']); 
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
		if(!empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
		$this->db->where('hms_vaccination_entry.created_date >= "'.$start_date.'"');
		}

		if(!empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
		$this->db->where('hms_vaccination_entry.created_date <= "'.$end_date.'"');
		}

		if(!empty($search['vaccination_name']))
		{
			$this->db->where('hms_vaccination_entry.vaccination_name LIKE "'.$search['vaccination_name'].'%"');
		}

		if(!empty($search['vaccination_company']))
		{
			 $this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
			$this->db->where('hms_vaccination_company.company_name LIKE"'.$search['vaccination_company'].'%"');
		}

		if(!empty($search['vaccination_code']))
		{
		
			$this->db->where('hms_vaccination_entry.vaccination_code',$search['vaccination_code']);
		}
		
		
		if(isset($search['unit1']) && $search['unit1']!="")
		{
			$this->db->where('hms_vaccination_unit.id = "'.$search['unit1'].'"');
		}

		if(isset($search['unit2']) && $search['unit2']!="")
		{
			$this->db->where('vu.id ="'.$search['unit2'].'"');
		}

		if(isset($search['packing']) && $search['packing']!="")
		{
		
		//$this->db->where('packing',$search['packing']);
			$this->db->where('hms_vaccination_entry.packing LIKE "'.$search['packing'].'%"');
		}

		if(isset($search['hsn_no']) && $search['hsn_no']!="")
		{

		//$this->db->where('packing',$search['packing']);
		$this->db->where('hms_vaccination_entry.hsn_no LIKE "'.$search['hsn_no'].'%"');
		}

		if(isset($search['cgst']) && $search['cgst']!="")
		{

		//$this->db->where('packing',$search['packing']);
		$this->db->where('hms_vaccination_entry.cgst LIKE "'.$search['cgst'].'%"');
		}
		if(isset($search['sgst']) && $search['sgst']!="")
		{

		//$this->db->where('packing',$search['packing']);
		$this->db->where('hms_vaccination_entry.sgst LIKE "'.$search['sgst'].'%"');
		}
		if(isset($search['igst']) && $search['igst']!="")
		{

		//$this->db->where('packing',$search['packing']);
		$this->db->where('hms_vaccination_entry.igst LIKE "'.$search['igst'].'%"');
		}

		if(isset($search['mrp_to']) &&$search['mrp_to']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp >="'.$search['mrp_to'].'"');
		}

		if(isset($search['mrp_from']) &&$search['mrp_from']!="")
		{
			$this->db->where('hms_vaccination_entry.mrp <="'.$search['mrp_from'].'"');
		}

		if(isset($search['purchase_to']) &&$search['purchase_to']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate >= "'.$search['purchase_to'].'"');
		}

		if(isset($search['purchase_from']) &&$search['purchase_from']!="")
		{
			$this->db->where('hms_vaccination_entry.purchase_rate <="'.$search['purchase_from'].'"');
		}

		if(isset($search['rack_no']) &&$search['rack_no']!="")
		{
			//$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
			$this->db->where('hms_vaccination_racks.rack_no',$search['rack_no']);
			
		}
		if(isset($search['min_alert']) &&$search['min_alert']!="")
		{
			$this->db->where('hms_vaccination_entry.min_alrt',$search['min_alert']);
		}
        if(isset($search['discount']) &&$search['discount']!="")
		{
			$this->db->where('hms_vaccination_entry.discount',$search['discount']);
		} 
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
		$this->db->from($this->table);
		return $this->db->count_all_results();
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
		
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
				$data = array(
					'branch_id'=>$user_data['parent_id'],
					"vaccination_name"=>$post['vaccination_name'],
					"salt"=>$post['salt'],
					"manuf_company"=>$post['manuf_company'],
					"status"=>1
			); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_vaccination_entry',$data);  
		}
		else{    
			$reg_no = generate_unique_id(35); 
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->set('vaccination_code',$reg_no);
			$this->db->insert('hms_vaccination_entry',$data);   
			//echo $this->db->last_query(); exit;            
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

    public function vaccination_entry_list()
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
       // echo $this->db->last_query();die;
        return $result; 
    }
    
    
     public function save_all_vaccination($vaccination_all_data = array())
	{
		

		//echo "hello";echo "<pre>";print_r($doctors_all_data); //$patient_data['relation_type']
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($vaccination_all_data))
        {
            foreach($vaccination_all_data as $vaccination_data)
            {
            	//print_r($doctor_data);
            	if(!empty($vaccination_data['vaccination_name']))
            	{
            		
					  //unit_id start
					$unit_id='';
					if(!empty($vaccination_data['unit_id']))
            		{
            			//echo "hello"; print_r($doctor_data['specialization']);
		            	$this->db->select("hms_vaccination_unit.*");
					    $this->db->from('hms_vaccination_unit'); 
		                $this->db->where('LOWER(hms_vaccination_unit.vaccination_unit)',strtolower($vaccination_data['unit_id'])); 
		                $this->db->where('hms_vaccination_unit.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $unit_data = $query->result_array();

					    if(!empty($unit_data))
					    {
						    $unit_id = $unit_data[0]['id'];
					    }
					    else
					    {
						 	$vaccinationunit_insert_data = array(
							'vaccination_unit'=>$vaccination_data['unit_id'],
							'status'=>1,
							'branch_id'=>$users_data['parent_id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_vaccination_unit',$vaccinationunit_insert_data);
							$unit_id = $this->db->insert_id();
					    }
					}
                       //unit_id end
					 //unit_second_id start
					$unit_second_id='';
					if(!empty($vaccination_data['unit_second_id']))
            		{
            			//echo "hello"; print_r($doctor_data['specialization']);
		            	$this->db->select("hms_vaccination_unit.*");
					    $this->db->from('hms_vaccination_unit'); 
		                $this->db->where('LOWER(hms_vaccination_unit.vaccination_unit)',strtolower($vaccination_data['unit_second_id'])); 
		                $this->db->where('hms_vaccination_unit.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $unit_data = $query->result_array();

					    if(!empty($unit_data))
					    {
						    $unit_second_id = $unit_data[0]['id'];
					    }
					    else
					    {
						 	$vaccinationunit_insert_data = array(
							'vaccination_unit'=>$vaccination_data['unit_second_id'],
							'status'=>1,
							'branch_id'=>$users_data['parent_id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_vaccination_unit',$vaccinationunit_insert_data);
							$unit_second_id = $this->db->insert_id();
					    }
					}
                    //unit_second_id end
                    //rack_no start
					$rack_no='';
					if(!empty($vaccination_data['rack_no']))
            		{
            			//echo "hello"; print_r($doctor_data['specialization']);
		            	$this->db->select("hms_vaccination_racks.*");
					    $this->db->from('hms_vaccination_racks'); 
		                $this->db->where('LOWER(hms_vaccination_racks.rack_no)',strtolower($vaccination_data['rack_no'])); 
		                $this->db->where('hms_vaccination_racks.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $rack_no_data = $query->result_array();

					    if(!empty($rack_no_data))
					    {
						    $rack_no = $rack_no_data[0]['id'];
					    }
					    else
					    {
						 	$rack_no_insert_data = array(
							'rack_no'=>$vaccination_data['rack_no'],
							'status'=>1,
							'branch_id'=>$users_data['parent_id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_vaccination_racks',$rack_no_insert_data);
							$rack_no = $this->db->insert_id();
					    }
					}

                         //rack_no end
					  //manuf_company start
						$manuf_company='';
					if(!empty($vaccination_data['manuf_company']))
            		{
            			//echo "hello"; print_r($doctor_data['specialization']);
		            	$this->db->select("hms_vaccination_company.*");
					    $this->db->from('hms_vaccination_company'); 
		                $this->db->where('LOWER(hms_vaccination_company.company_name)',strtolower($vaccination_data['manuf_company'])); 
		                $this->db->where('hms_vaccination_company.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $manuf_company_data = $query->result_array();

					    if(!empty($manuf_company_data))
					    {
						    $manuf_company = $manuf_company_data[0]['id'];
					    }
					    else
					    {
						 	$manuf_company_insert_data = array(
							'company_name'=>$vaccination_data['manuf_company'],
							'branch_id'=>$users_data['parent_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_vaccination_company',$manuf_company_insert_data);
							$manuf_company = $this->db->insert_id();
					    }
					}
					 //manuf_company end
				
$vaccination_code = generate_unique_id(35);
//print_r($doctor_code);
				$vaccination_data_array = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'vaccination_code'=>$vaccination_code,
	                    'vaccination_name'=>$vaccination_data['vaccination_name'],
	                    'unit_id'=>$unit_id,
	                    'unit_second_id'=>$unit_second_id,
	                    "conversion"=>$vaccination_data['conversion'],
						"min_alrt"=>$vaccination_data['min_alrt'],
						"packing"=>$vaccination_data['packing'],
						"rack_no"=>$rack_no,
						'salt'=>$vaccination_data['salt'],
						'manuf_company'=>$manuf_company,
						'mrp'=>$vaccination_data['mrp'],
						'purchase_rate'=>$vaccination_data['purchase_rate'],
						'hsn_no'=>$vaccination_data['hsn_no'],
						'sgst'=>$vaccination_data['sgst'],
						'cgst'=>$vaccination_data['cgst'],
						'igst'=>$vaccination_data['igst'],
						'discount'=>$vaccination_data['discount'],
						'status'=>1,					
	                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_date'=>date('Y-m-d H:i:s'),
				        'modified_date'=>date('Y-m-d H:i:s'),
				        'created_by'=>$users_data['parent_id']
				        
				    );

                //print_r($vaccination_data_array);
				    $this->db->insert('hms_vaccination_entry',$vaccination_data_array);
	                //echo $this->db->last_query(); exit;
	            }
            }   	
        }
	}

   

} 
?>