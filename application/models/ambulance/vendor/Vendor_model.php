<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vendor_model extends CI_Model 
{
	var $table = 'hms_medicine_vendors';
	var $column = array('hms_medicine_vendors.id','hms_medicine_vendors.vendor_id', 'hms_medicine_vendors.name', 'hms_medicine_vendors.address','hms_medicine_vendors.mobile','hms_medicine_vendors.status', 'hms_medicine_vendors.created_date', 'hms_medicine_vendors.modified_date', 'hms_medicine_vendors.vendor_dl_1', 'hms_medicine_vendors.vendor_dl_2');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_vendors.*"); 
		$this->db->where('is_deleted','0'); 
		$this->db->where('vendor_type','5'); 
		$this->db->where('branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->from($this->table); 
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

	public function get_by_id($id)
	{
		$this->db->select("hms_medicine_vendors.*"); 
		$this->db->from('hms_medicine_vendors'); 
		$this->db->where('hms_medicine_vendors.id',$id);
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


	public function save()
	{
		$reg_no = generate_unique_id(11); 
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
				$data = array(
				'branch_id'=>$user_data['parent_id'],
				"vendor_id"=>$reg_no,
				"name"=>$post['name'],
				"vendor_gst"=>$post['vendor_gst'],
				"vendor_dl_1"=>$post['vendor_dl_1'],
				"vendor_dl_2"=>$post['vendor_dl_2'],
				'email'=>$post['vendor_email'],
				"mobile"=>$post['mobile'],
				"address"=>$post['address'],
				"address2"=>$post['address2'],
				"address3"=>$post['address3'],
				"vendor_type"=>$post['vendor_type'],
				"status"=>$post['status']
			); 
				//print_r($data);die;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_medicine_vendors',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_medicine_vendors',$data);  
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
			$this->db->update('hms_medicine_vendors');
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
			$this->db->update('hms_medicine_vendors');
    	} 
    }

    public function vendor_list($type="")
    {
    	$user_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('branch_id = "'.$user_data['parent_id'].'"');
        $this->db->where('vendor_type = "'.$type.'"');
        $this->db->where('status',1);
        $this->db->group_by('id');
        $query = $this->db->get('hms_medicine_vendors');

        $result = $query->result(); 
        return $result; 
    } 
    
    function search_vendor_data()
    {
        $user_data = $this->session->userdata('auth_users');
    	$this->db->select("hms_medicine_vendors.*"); 
		$this->db->where('is_deleted','0'); 
		$this->db->where('vendor_type','5'); 
		$this->db->where('branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->from($this->table); 
	    $query = $this->db->get(); 

		$data= $query->result();
		//echo $this->db->last_query();
		return $data;
	}



} 
?>