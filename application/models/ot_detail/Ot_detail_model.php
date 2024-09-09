<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_detail_model extends CI_Model {

	var $table = 'hms_ot_management';
	var $column = array('hms_ot_management.id','hms_ot_management.name','hms_ot_management.type','hms_ot_management.days','hms_ot_management.amount','hms_ot_management.remarks');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ot_management.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_ot_management.is_deleted','0');
        $this->db->where('hms_ot_management.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function ot_pacakge_list()
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

		$this->db->select("hms_ot_management.*,hms_operation_type.operation_type");
		$this->db->from('hms_ot_management'); 
		$this->db->where('hms_ot_management.id',$id);
		$this->db->join('hms_operation_type','hms_operation_type.id=hms_ot_management.type','left');
		
		$this->db->where('hms_ot_management.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	public function get_by_id_ot_details($id,$pack_id)
	{
		//echo 'rew';die;
		$this->db->select("hms_ot_booking_ot_details.*,hms_ot_booking_ot_details.particular_name as particular");
		$this->db->from('hms_ot_booking_ot_details'); 
		$this->db->where('hms_ot_management.id',$pack_id);
		$this->db->where('hms_operation_booking.id',$id);
		$this->db->join('hms_ot_management','hms_ot_management.id=hms_ot_booking_ot_details.ot_mgt_id','left');
		//$this->db->join('hms_doctors','hms_doctors.id=hms_ot_booking_pacakge_details.doctor_id','left');
		//$this->db->join('hms_ipd_perticular','hms_ipd_perticular.id=hms_ot_booking_pacakge_details.particular_id','left');
		$this->db->join('hms_operation_booking','hms_operation_booking.id=hms_ot_booking_ot_details.ot_booking_id','left');

		$this->db->where('hms_ot_management.is_deleted','0');
		$this->db->where('hms_operation_booking.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}
	

	public function save()
	{
		//echo "hi";die;
			$user_data = $this->session->userdata('auth_users');
			$post = $this->input->post();  
			//print_r($post);die;
			$this->db->where(array('ot_booking_id'=>$post['data_id']));
			$this->db->delete('hms_ot_booking_ot_details');
			$doctor_wise=$post['doctor_wise'];
			$particular_id='';
			$doctor_id='';
              if(!empty($doctor_wise))
				{
                      $i=0;
						foreach($doctor_wise as $key=>$val)
						{
							 

						   if(isset($val['doctor_name'][0]) && !empty($val['doctor_name'][0]) && $val['doctor_name'][0]!='')
						   {
						   
						   	  $doctor_id=$val['doctor_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									//'doctor_id'=>$doctor_id,
									'doctor_name'=>$val['doctor_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code'][0],
									'ot_mgt_id'=>$post['ot_mgt_id'],
									'ot_booking_id'=>$post['data_id'],
									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	 
						   	 $this->db->insert('hms_ot_booking_ot_details',$data); 

						   }


						   if(isset($val['particular_name'][0]) && !empty($val['particular_name'][0]) && $val['particular_name'][0]!='')
						   {
						   	  $particular_id=$val['particular_id'][0];
						   	  $data = array( 
									'branch_id'=>$user_data['parent_id'],
									//'particular_id'=>$particular_id,
									'particular_name'=>$val['particular_name'][0],
									'master_type'=>$val['master_type'][0],
									'master_rate'=>$val['master_rate'][0],
									'ot_type'=>$val['type_ot'][0],
									'code'=>$val['code_p'][0],
									'ot_mgt_id'=>$post['ot_mgt_id'],
									'ot_booking_id'=>$post['data_id'],
									'created_date'=>date('Y-m-d H:i:s'),
									'created_by'=>$user_data['id']
									);
						   	  $this->db->insert('hms_ot_booking_ot_details',$data); 
						   }
						
						} 
						

				}
   
             
			
	}

	

}
?>