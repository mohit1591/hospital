<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_mode_field_model extends CI_Model {

	var $table = 'hms_payment_mode';
	//var $column = array('hms_payment_mode.id,hms_payment_mode.payment_mode,hms_payment_mode_to_field.p_mode_id','hms_payment_mode.payment_mode','hms_payment_mode_to_field.field_name','hms_payment_mode.sort_order','hms_payment_mode.status'); 
var $column = array('hms_payment_mode.id','hms_payment_mode.payment_mode','hms_payment_mode_to_field.p_mode_id','hms_payment_mode.payment_mode','hms_payment_mode_to_field.field_name','hms_payment_mode.sort_order','hms_payment_mode.status');   
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_payment_mode.*"); 
		$this->db->from($this->table);
		$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.p_mode_id = hms_payment_mode.id','left');
        $this->db->where('hms_payment_mode.is_deleted','0');
        $this->db->where('hms_payment_mode.branch_id',$users_data['parent_id']);
	    $this->db->group_by('hms_payment_mode.id');
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
    
    public function payment_mode_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('field_name','ASC'); 
    	$query = $this->db->get('hms_payment_mode_to_field');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_payment_mode.*');
		$this->db->from('hms_payment_mode'); 
		$this->db->where('hms_payment_mode.id',$id);
		$this->db->where('hms_payment_mode.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	public function get_filed_name_by_id($id)
	{
		$this->db->select('hms_payment_mode_to_field.*');
		$this->db->from('hms_payment_mode_to_field'); 
		$this->db->where('hms_payment_mode_to_field.p_mode_id',$id);
		$this->db->where('hms_payment_mode_to_field.is_deleted','0');
		$query = $this->db->get()->result(); 
		return $query;

		
	}

	public function check_field_name($check_field)
	{
		$this->db->select('hms_payment_mode_to_field.*');
		$this->db->from('hms_payment_mode_to_field'); 
		$this->db->where('hms_payment_mode_to_field.field_name',$check_field);
		$this->db->where('hms_payment_mode_to_field.is_deleted','0');
		$query = $this->db->get()->result(); 
		return $query;

		
	}

	public function get_payment_mode()
	{
		$this->db->select('hms_payment_mode.*');
		$this->db->from('hms_payment_mode'); 
		$this->db->where('hms_payment_mode.is_deleted','0');
		$query = $this->db->get()->result();
		return $query;
	}
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		
		$this->db->where(array('p_mode_id'=>$post['data_id']));
		$this->db->delete('hms_payment_mode_to_field');
		$error = $this->db->error();
		if(!empty($error['message']))
		{
           return 2;
		}
		else
		{
			$data_payment_mode = array( 
			'branch_id'=>$user_data['parent_id'],
			'payment_mode'=>$post['payment_mode'],
			'status'=>$post['status'],
			"sort_order"=>$post['sort_order'],
			'ip_address'=>$_SERVER['REMOTE_ADDR']
			);
			if(!empty($post['data_id']) && $post['data_id']>0)
			{    
				if(!empty($post['field']))
				{
					$count_tot_ids= count($post['field']);
					for($i=0;$i<$count_tot_ids;$i++)
						{

							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'p_mode_id'=>$post['data_id'],
										'field_name'=> $post['field'][$i],
										'ip_address'=>$_SERVER['REMOTE_ADDR']
										);

							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_payment_mode_to_field',$data); 

						} 
				}
	            			$this->db->set('modified_by',$user_data['id']);
							$this->db->set('modified_date',date('Y-m-d H:i:s'));
	            			$this->db->where('id',$post['data_id']);
		       				$this->db->update('hms_payment_mode',$data_payment_mode);
			}
		else{  
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_payment_mode',$data_payment_mode);
				$last_id= $this->db->insert_id();
	          if(!empty($post['field']))
	          {
	          	$count_tot_ids= count($post['field']);
		          	for($i=0;$i<$count_tot_ids;$i++)
			       {

			       	  $data = array( 
							'branch_id'=>$user_data['parent_id'],
							'p_mode_id'=>$last_id,
							'field_name'=> $post['field'][$i],
							'ip_address'=>$_SERVER['REMOTE_ADDR']
				         );

						$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_payment_mode_to_field',$data); 

			       }	
	          }
			  
			}
			return 1;
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
			$this->db->update('hms_payment_mode');
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
			$this->db->update('hms_payment_mode');
			//echo $this->db->last_query();die;
    	} 
    }


  

}
?>