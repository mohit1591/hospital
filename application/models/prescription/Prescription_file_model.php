<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription_file_model extends CI_Model {

	var $table = 'hms_prescription_files';
	var $column = array('hms_prescription_files.id','hms_prescription_files.prescription_files','hms_prescription_files.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_prescription_files.*, hms_prescription_files.prescription_files"); 
		$this->db->from($this->table);  
        $this->db->where('hms_prescription_files.branch_id = "'.$users_data['parent_id'].'"');
       
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
    
    public function sign_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('doctor_id','ASC'); 
    	$query = $this->db->get('prescription_files');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_signature.*');
		$this->db->from('hms_signature'); 
		$this->db->where('hms_signature.id',$id); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save($filename="")
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'doctor_id'=>$post['doctor_id'],
					'dept_id'=>$post['dept_id'],
					'signature'=>$post['signature'],
					//'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(!empty($filename))
			{
			   $this->db->set('sign_img',$filename);
			}
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_signature',$data);  
		}
		else{    
			if(!empty($filename))
			{
			   $this->db->set('sign_img',$filename);
			}
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_signature',$data);               
		} 	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{ 
    		$this->db->select('*');  
			$this->db->where('id',$id);
			$query = $this->db->get('hms_prescription_files');
			$result = $query->result();
			if(!empty($result))
			{
			  if(!empty($result[0]->prescription_files))
			  {
			  	unlink(DIR_UPLOAD_PATH.'opd/prescription/'.$result[0]->prescription_files);
			  }	 
			}  

			$this->db->where('id',$id);
			$this->db->delete('hms_prescription_files'); 
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
				$query = $this->db->get('hms_prescription_files');
				$result = $query->result();
				if(!empty($result))
				{
				  if(!empty($result[0]->prescription_files))
				  {
				  	unlink(DIR_UPLOAD_PATH.'opd/prescription/'.$result[0]->prescription_files);
				  }	 
				}

    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$banch_ids = implode(',', $id_list); 
			$this->db->where('id IN ('.$banch_ids.')');
			$this->db->delete('hms_prescription_files'); 
    	} 
    }

    public function doctors_list($id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $id_set = "";
        if(!empty($id) && $id>0)
        {
          $id_set = ' WHERE id != "'.$id.'"';
        }
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('doctor_name','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('id NOT IN (select id from hms_signature '.$id_set.')');
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query();
        return $result; 
    }

}
?>