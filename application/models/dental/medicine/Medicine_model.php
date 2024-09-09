<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_model extends CI_Model {

	var $table = 'hms_medicine_entry';
	var $column = array('hms_medicine_entry.id','hms_medicine_entry.medicine_name','hms_medicine_unit.medicine_unit','hms_medicine_entry.salt','hms_medicine_entry.manuf_company', 'hms_medicine_entry.status','hms_medicine_entry.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit"); 
		$this->db->from($this->table); 
        $this->db->where('hms_medicine_entry.is_deleted','0');
        //$this->db->where('hms_medicine_entry.type','1');
        $this->db->where('hms_medicine_entry.branch_id = "'.$users_data['parent_id'].'"');
        $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company AND hms_medicine_company.is_deleted!=2','left');
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
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
    
    public function medicine_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('medicine','ASC'); 
    	$query = $this->db->get('hms_medicine_entry');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_medicine_entry.*,hms_medicine_company.id as company_id,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');
		$this->db->from('hms_medicine_entry'); 
		$this->db->where('hms_medicine_entry.id',$id);
		$this->db->where('hms_medicine_entry.is_deleted','0');
		$this->db->join('hms_medicine_company','hms_medicine_company.id=hms_medicine_entry.manuf_company','left');
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$reg_no = generate_unique_id(10); 
		$unit_id='';
		$company_id='';

		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'medicine'=>$post['medicine'],
					'type'=>$post['type'],
					'salt'=>$post['salt'],
					'brand'=>$post['brand'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			
				if(isset($post['brand']) && !empty($post['brand']))
				{
					$company= $this->check_comapny_name($post['brand']);

					if(!empty($company))
					{
						$company_name= $company[0]->id;  
					}
					else
					{
						$data_company = array( 
						'branch_id'=>$user_data['parent_id'],
						'company_name'=>$post['brand'],
						'status'=>1,
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
						$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_medicine_company',$data_company);
						$company_name= $this->db->insert_id();
					}
				}
				

			
				if(isset($post['type']) && !empty($post['type']))
				{
					$unit= $this->check_unit($post['type']);
					if(!empty($unit))
					{
					$unit_id= $unit[0]->id;
					}
					else
					{
					$data_unit = array( 
							'branch_id'=>$user_data['parent_id'],
							'medicine_unit'=>$post['type'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_medicine_unit',$data_unit);
							$unit_id= $this->db->insert_id();	
					}
				}
			
			/* insert into medicine table  */
			$data_medicine_entry = array(
					"medicine_code"=>$reg_no,
					"medicine_name"=>$post['medicine'],
					'branch_id'=>$user_data['parent_id'],
					'type'=>1,
					"unit_id"=>$unit_id,
					"salt"=>$post['salt'],
					"manuf_company"=>$company_name,
					"status"=>$post['status']
					); 
			//print_r($data_medicine_entry);die;
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$post['data_id']);
			$this->db->update('hms_medicine_entry',$data_medicine_entry);      
			/* insert into medicine table  */

		}
		else{    


			    /* insert into unit table  */
			 	$result_new_unit= $post['type'];
			 	if(isset($result_new_unit) && !empty($result_new_unit))
			 	{
			 		$medicine_unit=$result_new_unit;
				    if(isset($medicine_unit) && !empty($medicine_unit))
				    {
                        $unit= $this->check_unit($medicine_unit); 
                        if(!empty($unit))
                        {
                        	$unit_id= $unit[0]->id;
                        }
                        else
                        {
							$data_unit = array( 
							'branch_id'=>$user_data['parent_id'],
							'medicine_unit'=>$post['type'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_medicine_unit',$data_unit);
							$unit_id= $this->db->insert_id();
                        }

                        
                       
				    }

			 	}
			 	else
			 	{
			 		$unit_id='0';
			 	}
			 	/* insert into unit table  */


			 	/* insert into company table  */
				$result_new_company= $post['brand'];
			 	if(isset($result_new_company) && !empty($result_new_company))
			 	{
			 		$medicine_comapny=$result_new_company;
				    if(isset($medicine_comapny) && !empty($medicine_comapny))
				    {
                        $company= $this->check_comapny_name($medicine_comapny);

                        if(!empty($company))
                        {
                            $company_id= $company[0]->id;  
                        }
                        else
                        {
							$data_company = array( 
							'branch_id'=>$user_data['parent_id'],
							'company_name'=>$post['brand'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_medicine_company',$data_company);
							$company_id= $this->db->insert_id();
                        }
                       
				    }

			 	}
			 	else
			 	{
			 		$company_id='';
			 	}

			 	/* insert into company table  */

			 	/* insert into medicine table  */
					$data_medicine_entry = array(
							"medicine_code"=>$reg_no,
							"medicine_name"=>$post['medicine'],
							'branch_id'=>$user_data['parent_id'],
							'type'=>1,
							"unit_id"=>$unit_id,
							"salt"=>$post['salt'],
							"manuf_company"=>$company_id,
							"status"=>$post['status']
							); 
			 	
					$this->db->set('created_by',$user_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_medicine_entry',$data_medicine_entry);      
			 	/* insert into medicine table  */



			//$this->db->set('created_by',$user_data['id']);
			//$this->db->set('created_date',date('Y-m-d H:i:s'));
			//$this->db->insert('hms_opd_medicine',$data);               
		} 	
	}
	public function check_comapny_name($medicine_comapny_name="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		 if(!empty($medicine_comapny_name)){
			$this->db->where('company_name',$medicine_comapny_name);
		}
		$this->db->where('is_deleted!=',2); 
		$this->db->where('branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_medicine_company');
		$result = $query->result(); 

		return $result; 
	}
	public function check_unit($medicine_unit="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		 if(!empty($medicine_unit)){
			$this->db->where('medicine_unit',$medicine_unit);
		}
		$this->db->where('branch_id',$users_data['parent_id']); 
		$this->db->where('is_deleted!=',2); 
		$query = $this->db->get('hms_medicine_unit');
		$result = $query->result(); 

		return $result; 
	}
	public function get_medicine_data($medicine_ids="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		 if(!empty($medicine_ids))
		 {
			$this->db->where('id',$medicine_ids);
		 }
		 $this->db->where('branch_id',$users_data['parent_id']); 
		$this->db->where('is_deleted!=',2); 
		$query = $this->db->get('hms_medicine_entry');
		$result = $query->result(); 

		return $result; 
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
			$this->db->update('hms_medicine_entry');
			//echo $this->db->last_query();die;
    	} 
    }

    public function get_unit_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_unit','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_unit LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_medicine_unit');
	        $result = $query->result(); 
	      //  print_r($result);die;
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_unit;
	        	}
	        }
	        return $response; 
    	} 
    }

     public function get_company_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('company_name','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('company_name LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_medicine_company');
	        $result = $query->result(); 
	      //  print_r($result);die;
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->company_name.'|'.$vals->id;
	        	}
	        }
	        return $response; 
    	} 
    }

    public function get_salt_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
			$users_data = $this->session->userdata('auth_users'); 
			$this->db->select('hms_medicine_salt_master.salt');  
			$this->db->order_by('hms_medicine_salt_master.salt','ASC');
			$this->db->where('hms_medicine_salt_master.salt LIKE "'.$vals.'%"');
			$query = $this->db->get('hms_medicine_salt_master');
			$result = $query->result(); 
	      	if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->salt;
	        	}
	        }
	        return $response; 
    	} 
    }


}
?>