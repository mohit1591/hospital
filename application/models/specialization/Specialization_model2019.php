<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Specialization_model extends CI_Model {

	var $table = 'hms_specialization';
	var $column = array('hms_specialization.id','hms_specialization.specialization', 'hms_specialization.status','hms_specialization.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
          $parent_branch_details = $this->session->userdata('parent_branches_data');
         $sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_specialization.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_specialization.is_deleted','0');
     
            $this->db->where('hms_specialization.branch_id',$users_data['parent_id']);
		if(in_array('224',$users_data['permission']['section']) || in_array('225',$users_data['permission']['section']) || in_array('226',$users_data['permission']['section']) ||
        	in_array('227',$users_data['permission']['section']) || in_array('228',$users_data['permission']['section']) || in_array('229',$users_data['permission']['section']) || in_array('230',$users_data['permission']['section']) || in_array('231',$users_data['permission']['section']) || in_array('232',$users_data['permission']['section']) || in_array('233',$users_data['permission']['section']) || in_array('234',$users_data['permission']['section']) || in_array('235',$users_data['permission']['section']) || in_array('236',$users_data['permission']['section']) || in_array('237',$users_data['permission']['section']) || in_array('238',$users_data['permission']['section']) || in_array('239',$users_data['permission']['section']) || in_array('240',$users_data['permission']['section']) || in_array('241',$users_data['permission']['section']) || in_array('242',$users_data['permission']['section']) || in_array('243',$users_data['permission']['section']) || in_array('244',$users_data['permission']['section']) || in_array('245',$users_data['permission']['section']) || in_array('246',$users_data['permission']['section']) || in_array('247',$users_data['permission']['section']))
        {
        	$this->db->or_Where('hms_specialization.branch_id in (0) and default_value=1');
		}
		/* pedic specialization */

		 if(in_array('271',$users_data['permission']['section'])|| in_array('272',$users_data['permission']['section'])|| in_array('273',$users_data['permission']['section']) || in_array('274',$users_data['permission']['section']) || in_array('275',$users_data['permission']['section']) || in_array('276',$users_data['permission']['section']))
        {
        	$this->db->or_Where('hms_specialization.branch_id in (0) and default_value=2');
		}
		/* pedic specialization */
		/* dental specialization */

         if(in_array('277',$users_data['permission']['section'])|| in_array('278',$users_data['permission']['section'])|| in_array('279',$users_data['permission']['section']) || in_array('280',$users_data['permission']['section']) || in_array('281',$users_data['permission']['section']) || in_array('282',$users_data['permission']['section'])||in_array('277',$users_data['permission']['section'])|| in_array('283',$users_data['permission']['section'])|| in_array('284',$users_data['permission']['section']) || in_array('285',$users_data['permission']['section']) || in_array('286',$users_data['permission']['section']) || in_array('287',$users_data['permission']['section']) || in_array('288',$users_data['permission']['section'])|| in_array('289',$users_data['permission']['section'])|| in_array('290',$users_data['permission']['section'])|| in_array('291',$users_data['permission']['section'])|| in_array('292',$users_data['permission']['section'])|| in_array('293',$users_data['permission']['section'])|| in_array('294',$users_data['permission']['section'])|| in_array('295',$users_data['permission']['section']))
        {
            $this->db->or_Where('hms_specialization.branch_id in (0) and default_value=3');
        }
        /* dental specialization */
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
    
    public function specialization_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('specialization','ASC'); 
    	$query = $this->db->get('hms_specialization');
		$result = $query->result(); 
		return $result;
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_specialization.*');
		$this->db->from('hms_specialization'); 
		$this->db->where('hms_specialization.id',$id);
		$this->db->where('hms_specialization.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'specialization'=>$post['specialization'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_specialization',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_specialization',$data);               
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
			$this->db->update('hms_specialization');
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
			$this->db->update('hms_specialization');
			//echo $this->db->last_query();die;
    	} 
    }
   

}
?>