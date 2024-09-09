<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Investigation_model extends CI_Model {

	var $table = 'hms_dental_investigation';
	var $column = array('hms_dental_investigation.id','hms_dental_investigation.investigation_cat', 
		'hms_dental_investigation.investigation_sub','hms_dental_investigation.status','hms_dental_investigation.created_date','hms_dental_investigation.modified_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_dental_investigation.*,(CASE WHEN hms_dental_investigation.	investigation_cat > 0 THEN investigation_sub.investigation_sub ELSE hms_dental_investigation.investigation_sub END) as second_unit, (CASE WHEN hms_dental_investigation.investigation_cat=0 THEN 'N/A' ELSE hms_dental_investigation.investigation_sub END) as first_unit");
		$this->db->join('hms_dental_investigation as investigation_sub','investigation_sub.id=hms_dental_investigation.investigation_cat','left'); 
		$this->db->from($this->table); 
        $this->db->where('hms_dental_investigation.is_deleted','0');
        $this->db->where('hms_dental_investigation.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function teeth_number_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	//$this->db->order_by('chief_complaints','ASC'); 
    	$query = $this->db->get('hms_dental_investigation');
    	//echo $this->db->last_query();
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_dental_investigation.*');
		$this->db->from('hms_dental_investigation'); 
		$this->db->where('hms_dental_investigation.id',$id);
		$this->db->where('hms_dental_investigation.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();
		return $query->row_array();
	}


	public function get_investigation_cat_list()
	{
	    $user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dental_investigation.*');
		$this->db->from('hms_dental_investigation'); 
		$this->db->where('hms_dental_investigation.investigation_cat','0');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('hms_dental_investigation.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();
		return $query->result();
	}

	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//print_r($post);

		if(!empty($post))
		{
			if(!empty($post['investigation_cat']))
			{

               $investigation_cat=$post['investigation_cat'];
			}
			else
			{
			  $investigation_cat=0;	
			}
		}  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'investigation_cat'=>$investigation_cat,
					'investigation_sub'=>$post['investigation_sub'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_dental_investigation',$data);  
		}
		else
		{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dental_investigation',$data);               
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
			$this->db->update('hms_dental_investigation');
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
			$this->db->update('hms_dental_investigation');
			//echo $this->db->last_query();die;
    	} 
    }

}
?>