<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_clearance_history_model extends CI_Model {

	var $table = 'hms_payment';
	var $column = array('hms_payment.id','hms_patient.patient_code', 'hms_patient.patient_name','hms_patient.mobile_no', 'hms_payment.created_date', 'hms_payment_mode.payment_mode', 'hms_payment.debit', 'hms_payment.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users'); 
       $balance_search = $this->session->userdata('balance_search');
       //echo "<pre>"; print_r($balance_search); exit;
		$this->db->select("hms_payment.*, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_payment_mode.payment_mode as pay_mode"); 
		$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
		$this->db->from($this->table);  
		$this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id'); 
        $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
        $this->db->where('hms_payment.balance<1');
        if(!empty($balance_search))
        {
        	if(!empty($balance_search['department']))
        	{
        		$this->db->where('hms_payment.section_id', $balance_search['department']);
        	}

        	if(!empty($balance_search['start_date']))
        	{
        		$this->db->where('hms_payment.created_date >= "'.date('Y-m-d', strtotime($balance_search['start_date'])).' 00:00:00"');
        	}

        	if(!empty($balance_search['end_date']))
        	{
        		$this->db->where('hms_payment.created_date <= "'.date('Y-m-d', strtotime($balance_search['end_date'])).' 23:59:59"');
        	}
        }
	
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
		$this->db->select('hms_payment.*, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no');
		$this->db->from('hms_payment'); 
		$this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id', 'left'); 
		$this->db->where('hms_payment.id',$id); 
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}

	public function payment_mode_list()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_payment_mode.*');
		$this->db->from('hms_payment_mode');  
		$this->db->where('hms_payment_mode.branch_id',$users_data['parent_id']);
		$this->db->where('hms_payment_mode.status','1'); 
		$this->db->where('hms_payment_mode.is_deleted','0');
		$query = $this->db->get(); 
		return $query->result_array(); 
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array(  
					'pay_mode'=>$post['pay_mode'],
					'debit'=>$post['amount'], 
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{     
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_payment',$data);  
		} 	
	}
 
  

}
?>