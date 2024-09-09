<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends CI_Model {

	var $table = 'hms_banner';
	var $column = array('hms_banner.id','hms_banner.branch_id','hms_banner.title','hms_banner.url', 'hms_banner.status','hms_banner.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
   
	private function _get_datatables_query()
	{
	    $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_banner.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_banner.branch_id',$users_data['parent_id']);
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
	public function get_by_id($id)
	{
	    $users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_banner.*');
		$this->db->from('hms_banner'); 
		$this->db->where('hms_banner.id',$id);
		$this->db->where('hms_banner.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		return $query->row_array();
	}
  
  
    public function save()
	{
		$users_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
	//	print_r($_FILES);die;
		if(!empty($_FILES['banner_image']['name']))
		{

			$pic = uniqid().'_'.$_FILES['banner_image']['name'];
			$pic_loc = $_FILES['banner_image']['tmp_name'];
			$folder=DIR_UPLOAD_PATH."banner/";
			move_uploaded_file($pic_loc,$folder.$pic);
			//print_r($folder);die;
        }
        
		else{
    	$pic=$this->input->post('hide_banner_image');
    	}

    

		$data_spc=array(
		    'branch_id'=>$users_data['parent_id'],
				'title'=>$post['title'],
				'banner_image'=>$pic,
				'url'=>$post['url'],
				'ip_address'=>$_SERVER['REMOTE_ADDR'],
				'status'=>$post['status'],
				);
		//print_r($data_spc);die;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
	        		
					
					$this->db->where('id',$post['data_id']);
					$this->db->update('hms_banner',$data_spc);
		}
		else
		{    
				 
				   $this->db->set('created_date',date('Y-m-d H:i:s'));
				   $this->db->insert('hms_banner',$data_spc);
				   $last_id=$this->db->insert_id();
	   
		} 
		
	}
   

	public function delete($id="")
    {
        $users_data = $this->session->userdata('auth_users');
    	if(!empty($id) && $id>0)
    	{
			$this->db->where('id',$id);
			$this->db->where('branch_id',$users_data['parent_id']);
			$this->db->delete('hms_banner');
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
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->where('branch_id',$users_data['parent_id']);
			$this->db->delete('hms_banner');
			//echo $this->db->last_query();die;
    	} 
    }



  

}
?>