<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Procedure_note_summary_model extends CI_Model {

	var $table = 'hms_procedure_note_summery';
	var $column = array('hms_procedure_note_summery.id','hms_procedure_note_summery.name','hms_procedure_note_summery.diagnosis','hms_procedure_note_summery.operation','hms_procedure_note_summery.op_findings','hms_procedure_note_summery.procedures','hms_procedure_note_summery.pos_op_orders', 'hms_procedure_note_summery.status','hms_procedure_note_summery.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_procedure_note_summery.*,hms_ot_management.name as operation_name"); 
		$this->db->from($this->table); 
        $this->db->where('hms_procedure_note_summery.is_deleted','0');
        $this->db->where('hms_procedure_note_summery.branch_id = "'.$users_data['parent_id'].'"');
         $this->db->join('hms_ot_management','hms_ot_management.id=hms_procedure_note_summery.operation','left');
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
    
    public function ot_summary_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
		
    	//$this->db->order_by('medicine','ASC'); 
    	$query = $this->db->get('hms_procedure_note_summery');
		return $query->result();
    }

    public function get_operation_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('name','ASC'); 
    	$query = $this->db->get('hms_ot_management');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_procedure_note_summery.*');
		$this->db->from('hms_procedure_note_summery'); 
		$this->db->where('hms_procedure_note_summery.id',$id);
		$this->db->where('hms_procedure_note_summery.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$users_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$medicine_data=$this->input->post('medicine');
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'diagnosis'=>$post['diagnosis'],
					'operation'=>$post['operation'],
					'op_findings'=>$post['op_findings'],
					'procedures'=>$post['procedures'],
					'pos_op_orders'=>$post['pos_op_orders'],
					'blood_transfusion'=>$post['blood_transfusion'],
                      'blood_loss'=>$post['blood_loss'],
                      'drain'=>$post['drain'],
                      'histopathological'=>$post['histopathological'],
                      'microbiological'=>$post['microbiological'],
                      'remark'=>$post['remark'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_procedure_note_summery',$data); 
			$temp_id =$post['data_id'];
			//echo $this->db->last_query(); exit;
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_procedure_note_summery',$data);  
			$temp_id =  $this->db->insert_id();
		} 
		
		
		if($medicine_data!="" && !empty($medicine_data))
          {
            if($temp_id > 0)
            {
              $this->delete_operation_summary_medicines($temp_id);
            }
            
            $user_data = $this->session->userdata('auth_users');
            $users_data = $this->session->userdata('auth_users');
            foreach($medicine_data as $data)
            {
                
                if($data['medicine_name']!="" && !empty($data['medicine_name']) )
                {
                  $medicine_array=array('summary_id'=>$temp_id,
                                      'branch_id'=>$users_data['parent_id'],
                                      'is_eye_drop'=>$data['is_eyedrop'],
                                      'medicine_name'=>$data['medicine_name'],
                                      'medicine_dose'=>$data['medicine_dosage'],
                                      'medicine_duration'=>$data['medicine_duration'],
                                      'medicine_frequency'=>$data['medicine_frequency'],
                                      'medicine_advice'=>$data['medicine_advice'],
                                      'medicine_date'=>date('Y-m-d',strtotime($data["medicine_date"])),
                                      'left_eye'=>$data['left_eye'],
                                      'right_eye'=>$data['right_eye'],
                                      'created_date'=>date('Y-m-d H:i:s'),
                                      'created_by'=>$users_data['id'],
                                      'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                      'status'=>1,
                                      );
                  //print_r($medicine_array);die;
                  
                  $this->db->insert('hms_procedure_note_summery_to_medicine',$medicine_array);  
			
                  //echo $this->db->last_query(); exit;
                }
            }
           return $temp_id;
          }
		
	}
	
	public function delete_operation_summary_medicines($ot_summary_id)
    {
        $user_data = $this->session->userdata('auth_users');
    	$this->db->where('summary_id',$ot_summary_id);
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->delete('hms_procedure_note_summery_to_medicine');
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
			$this->db->update('hms_procedure_note_summery');
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
			$this->db->update('hms_procedure_note_summery');
			//echo $this->db->last_query();die;
    	} 
    }

    public function get_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_procedure_note_summery_type');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_type;
	        	}
	        }
	        return $response; 
    	} 
    }
    
    public function get_summary_medicine_data($summary_data_id)
    {
        $users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_procedure_note_summery_to_medicine.*');
    	$this->db->from('hms_procedure_note_summery_to_medicine');
    	$this->db->where('hms_procedure_note_summery_to_medicine.branch_id',$users_data['parent_id']);
    	$this->db->where('hms_procedure_note_summery_to_medicine.summary_id',$summary_data_id);
    	$res=$this->db->get();
    	if($res->num_rows() > 0)
    		return $res->result();
    	else
    		return "empty";
    }

}
?>