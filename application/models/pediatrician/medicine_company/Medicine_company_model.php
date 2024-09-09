<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_company_model extends CI_Model {

	var $table = 'hms_medicine_company';
	var $column = array('hms_medicine_company.id','hms_medicine_company.company_name', 'hms_medicine_company.status','hms_medicine_company.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_company.*"); 
		$this->db->from($this->table); 
		$this->db->where('hms_medicine_company.is_deleted','0');
		//$this->db->where('hms_medicine_company.type','1');
		$this->db->where('hms_medicine_company.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function medicine_comapny_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('medicine_company','ASC'); 
    	$query = $this->db->get('hms_medicine_company');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_medicine_company.*');
		$this->db->from('hms_medicine_company'); 
		$this->db->where('hms_medicine_company.id',$id);
		$this->db->where('hms_medicine_company.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		//print '<pre>'; print_r($post);die;
		
		// $data = array( 
		// 			'branch_id'=>$user_data['parent_id'],
		// 			'medicine_unit'=>$post['medicine_type'],
		// 			'type'=>'1',
		// 			'status'=>$post['status'],
		// 			'ip_address'=>$_SERVER['REMOTE_ADDR']
		//          );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{ 

            if(isset($post['medicine_company']) && !empty($post['medicine_company']))
				{

					$medicine_company= $this->check_medicine_company($post['medicine_company']);

					//if(!empty($medicine_company))
					//{
						//$medicine_company= $medicine_company[0]->id;
					//}
					//else
					//{
					$data_company = array( 
							'branch_id'=>$user_data['parent_id'],
							'company_name'=>$post['medicine_company'],
							'status'=>$post['status'],
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('modified_by',$user_data['id']);
							$this->db->set('modified_date',date('Y-m-d H:i:s'));
							$this->db->where('id',$post['data_id']);
							$this->db->update('hms_medicine_company',$data_company);
							//echo $this->db->last_query();die;
					//}
				}  
		}
		else
		{    
			    $result_medicine_company= $post['medicine_company'];
			 	if(isset($result_medicine_company) && !empty($result_medicine_company))
			 	{
			 		$medicine_company=$result_medicine_company;
				    if(isset($medicine_company) && !empty($medicine_company))
				    {
                        $company= $this->check_medicine_company($medicine_company);


                        if(!empty($company))
                        {
                        	$company= $company[0]->id;
                        }
                        else
                        {
							$data_company = array( 
							'branch_id'=>$user_data['parent_id'],
							'company_name'=>$post['medicine_company'],
							'status'=>$post['status'],
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_medicine_company',$data_company);
							//echo $this->db->last_query();die;
							$unit_id= $this->db->insert_id();
                        }
				    }

			 	}
			 	else
			 	{
			 		$company='0';
			 	}            
		} 	
	}
	public function check_medicine_company($medicine_company="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		if(!empty($medicine_company))
		{
		$this->db->where('company_name',$medicine_company);
		}
		$this->db->where('branch_id',$users_data['parent_id']); 
		$this->db->where('is_deleted!=',2); 
		$query = $this->db->get('hms_medicine_company');
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
			$this->db->update('hms_medicine_company');
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
			$this->db->update('hms_medicine_company');
			//echo $this->db->last_query();die;
    	} 
    }

}
?>