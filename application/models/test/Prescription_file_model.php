<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription_file_model extends CI_Model {

	var $table = 'hms_test_prescription_files';
	var $column = array('hms_test_prescription_files.id','hms_test_prescription_files.prescription_files','hms_test_prescription_files.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_test_prescription_files.*, hms_test_prescription_files.prescription_files"); 
		$this->db->from($this->table);  
        $this->db->where('hms_test_prescription_files.branch_id = "'.$users_data['parent_id'].'"');
       
		$i = 0;
	
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); 
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

	function get_datatables($prescription_id='')
	{
		$this->_get_datatables_query();
		$this->db->where('prescription_id',$prescription_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	function count_filtered($prescription_id='')
	{
		$this->_get_datatables_query();
		$this->db->where('prescription_id',$prescription_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($prescription_id='')
	{
		$this->db->from($this->table);
		$this->db->where('prescription_id',$prescription_id);
		return $this->db->count_all_results();
	}
 


    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{ 
    		$this->db->select('*');  
			$this->db->where('id',$id);
			$query = $this->db->get('hms_test_prescription_files');
			$result = $query->result();
			if(!empty($result))
			{
			  if(!empty($result[0]->prescription_files))
			  {
			  	unlink(DIR_UPLOAD_PATH.'test_prescription/'.$result[0]->prescription_files);
			  }	 
			}  

			$this->db->where('id',$id);
			$this->db->delete('hms_test_prescription_files'); 
    	} 
    }

    public function deleteall($ids=array())
    {
    	if(!empty($ids))
    	{ 

    		$id_list = [];
    		foreach($ids as $id)
    		{
    			$this->db->select('*');  
				$this->db->where('id',$id);
				$query = $this->db->get('hms_test_prescription_files');
				$result = $query->result();
				if(!empty($result))
				{
				  if(!empty($result[0]->prescription_files))
				  {
				  	unlink(DIR_UPLOAD_PATH.'test_prescription/'.$result[0]->prescription_files);
				  }	 
				}

    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$banch_ids = implode(',', $id_list); 
			$this->db->where('id IN ('.$banch_ids.')');
			$this->db->delete('hms_test_prescription_files'); 
    	} 
    }

    public function save_file($filename="")
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  

		$data = array( 
					'prescription_id'=>$post['prescription_id'],
					'branch_id' => $user_data['parent_id'],
					'status' =>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(!empty($filename))
			{
			   $this->db->set('prescription_files',$filename);
			}
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_test_prescription_files',$data);  
		}
		else{    
			if(!empty($filename))
			{
			   $this->db->set('prescription_files',$filename);
			}
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_test_prescription_files',$data);               
		} 
	}

}
?>