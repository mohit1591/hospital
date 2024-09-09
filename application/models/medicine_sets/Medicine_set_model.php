<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Medicine_set_model extends CI_Model 
{
	var $table = 'hms_std_medicine_sets';
	var $column = array('hms_std_medicine_sets.id','hms_std_medicine_sets.created_date'); 

	var $order = array('id' => 'desc'); 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('hms_std_medicine_sets.branch_id',$user_data['parent_id']);
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
		 $this->db->where('is_deleted',0); 
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
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_std_medicine_sets.*');
		$this->db->from('hms_std_medicine_sets'); 
		$this->db->join('hms_patient','hms_patient.id = hms_std_medicine_sets.patient_id'); 
		$this->db->where('hms_std_medicine_sets.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_std_medicine_sets.id',$id);
		$this->db->where('hms_std_medicine_sets.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	

    public function delete_medicine_set($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_std_medicine_sets');
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
			$this->db->update('hms_std_medicine_sets');
    	} 
    }

   public function save_medicine_freqdata()
   {
	   	$post=$this->input->post();	
	   	$set_id=0;
	   	if(!empty($post['set_id']) || $post['set_id'] !='')
   	 	{
   	 		$set_id=$post['set_id'];
   	 		$this->db->where('branch_id',$post['branch_id']);
   	 		$this->db->where('set_id',$post['set_id']);
   	 		$this->db->where('med_id',$post['tp_data'][0]['med_id']);
   	 		$this->db->delete('hms_std_eye_advice_set_tapper_data');
   	 	}
	   foreach ($post['tp_data'] as $key => $data) {
	   		$day=0;
	   		if(!empty($data['wdays']))
	   		{
	   			$day=$data['wdays'];
	   		}
	   		else{
	   			$day=$data['days'];
	   		}
	   	 	$temp_data=array('branch_id'=>$post['branch_id'], 'set_id'=>$set_id, 'med_id'=>$post['tp_data'][0]['med_id'], 'row'=>$data['sn'], 'day'=>$day,'st_date'=>$data['st_date'],'en_date'=>$data['en_date'],'st_time'=>$data['st_time'],'en_time'=>$data['en_time'],'freq'=>$data['freq'],'intvl'=>$data['intvl'], 'created_date'=>date('Y-m-d H:i:s'));
	   	 	$this->db->insert('hms_std_eye_advice_set_tapper_data',$temp_data);
	   }
  	return 1;
   }


	public function save_medicine_set()
	{
	 $post = $this->input->post(); 
	 $setdata=array('branch_id'=>$post['branch_id'], 'set_name'=>$post['set_name'], 'status'=>'1', 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$post['branch_id'], 'created_date'=>date('Y-m-d H:i:s'));
		if(!empty($post['set_id']) || $post['set_id'] !='')
   	 	{
   	 		$set_id=$post['set_id'];
   	 		$this->db->where('id',$post['set_id']);
   	 		$this->db->update('hms_std_medicine_sets',$setdata);
   	 		$this->db->where('branch_id',$post['branch_id']);
   	 		$this->db->where('set_id',$post['set_id']);
   	 		$this->db->delete('hms_std_eye_advice_set_medicines');
   	 	}
   	 	else{
   	 		$this->db->insert('hms_std_medicine_sets',$setdata);
   	 		$set_id=$this->db->insert_id();
   	 	}
	   foreach ($post['advs']['medication'] as $key => $data) 
	   {
	   	 	$temp_data=array('branch_id'=>$post['branch_id'], 'set_id'=>$set_id, 'medicine_name'=>$data['mname'], 'medicine_id'=>$data['med_id'], 'medicine_type'=>$data['mtype'],'quantity'=>$data['mqty'],'frequency'=>$data['mfrq'],'duration'=>$data['mdur'],'duration_unit'=>$data['mdurd'],'eyes'=>$data['eside'],'instrucion'=>$data['minst'], 'created_date'=>date('Y-m-d H:i:s'));
	   	 	$this->db->insert('hms_std_eye_advice_set_medicines',$temp_data);

	   	 	$this->update_medicine_tapper_set($post['branch_id'],$set_id,$data['med_id']);
	   }
  	   return 1;		  
	}

    public function get_medicine_set_data($branch_id='',$id='')
    {
    	$this->db->select('hms_std_eye_advice_set_medicines.*,hms_std_medicine_sets.set_name');
    	$this->db->from('hms_std_eye_advice_set_medicines');
    	$this->db->join('hms_std_medicine_sets', 'hms_std_eye_advice_set_medicines.set_id=hms_std_medicine_sets.id');
    	$this->db->where('hms_std_eye_advice_set_medicines.branch_id', $branch_id);
    	$this->db->where('hms_std_eye_advice_set_medicines.set_id', $id);
    	$result=$this->db->get();
    	return $result->result();

    }
    public function update_medicine_tapper_set($branch_id='',$set_id='',$medid='')
    {
    	$this->db->set('set_id',$set_id);
    	$this->db->where('branch_id', $branch_id);
    	$this->db->where('set_id', 0);
    	$this->db->where('med_id', $medid);
    	$this->db->update('hms_std_eye_advice_set_tapper_data');
    	return 1;
    }

   public function check_unique_value($branch_id, $set, $id='')
   {
		$this->db->select('hms_std_medicine_sets.*');
		$this->db->from('hms_std_medicine_sets'); 
		$this->db->where('hms_std_medicine_sets.branch_id',$branch_id);
		$this->db->where('hms_std_medicine_sets.set_name',$set);
		if(!empty($id))
		$this->db->where('hms_std_medicine_sets.id !=',$id);
		$this->db->where('hms_std_medicine_sets.is_deleted','0');
		$query = $this->db->get(); 
		$result=$query->row_array();
		if(!empty($result))
		{
		return 1;
		}
		else{
		return 0;
		}
   }

// Please write code above         
} 
?>