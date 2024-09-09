<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd_panel_particular_charge_model extends CI_Model {

	var $table = 'hms_ipd_panel_company';
	var $column = array('hms_ipd_panel_company.id','hms_ipd_panel_company.panel_company', 'hms_ipd_panel_company.status','hms_ipd_panel_company.created_date');  
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
		$this->db->select("hms_ipd_panel_company.*"); 
		$this->db->from($this->table); 
		$this->db->where('hms_ipd_panel_company.is_deleted','0');
		$this->db->where('hms_ipd_panel_company.branch_id',$users_data['parent_id']);

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
    
    public function get_panel_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('insurance_company','ASC'); 
    	$query = $this->db->get('hms_insurance_company');
		return $query->result();
    }

	public function get_particular_list($panel_id="")
	{	
		$user_data = $this->session->userdata('auth_users');
		//(CASE WHEN hms_patient.insurance_type=1 THEN  'Yes' ELSE 'No' END ) as panel_type

		$this->db->select('hms_opd_particular.*,hms_opd_particular_charge.id as charge_id,(CASE WHEN hms_opd_particular_charge.charge >0 THEN hms_opd_particular_charge.charge ELSE hms_opd_particular.charge END) as particular_charge');
		$this->db->from('hms_opd_particular');

		$this->db->join('hms_opd_particular_charge','hms_opd_particular_charge.particular_id=hms_opd_particular.id AND hms_opd_particular_charge.panel_company_id='.$panel_id,'left'); 
		
		//$this->db->where('hms_opd_particular_charge.panel_company_id',$panel_id);
		$this->db->where('hms_opd_particular.is_deleted','0');
		$this->db->where('hms_opd_particular.status',1);
		$this->db->where('hms_opd_particular.branch_id',$user_data['parent_id']); 
		$this->db->order_by('hms_opd_particular.particular','ASC');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$result = $query->result_array();
		return $result;
	}
	
	public function save_panel_particular_charge($panel_id="",$particular_id="",$vals="0",$charge_id='')
	{
		$user_data = $this->session->userdata('auth_users'); 
        //echo "<pre>";print_r($get_particular); exit;
       	if(!empty($panel_id) && !empty($particular_id) && !empty($charge_id) && $charge_id!=0)
        {
        	//echo 'ee';die;
	       	$perticular_data = array('charge'=>$vals,'type'=>1,'branch_id'=>$user_data['parent_id']);
			$this->db->where('id',$charge_id);
        	$this->db->where('particular_id',$particular_id);
        	$this->db->where('panel_company_id',$panel_id);
			$this->db->update('hms_opd_particular_charge',$perticular_data);
	        
		}
		else
		{
			$perticular_data = array('charge'=>$vals,'particular_id'=>$particular_id,'panel_company_id'=>$panel_id,'branch_id'=>$user_data['parent_id'],'type'=>1);
			$this->db->insert('hms_opd_particular_charge',$perticular_data);
		}
		return 1;
	}

	public function increase_panel_price($panel_id="",$type="",$vals="0")
	{
		$user_data = $this->session->userdata('auth_users'); 
		if(!empty($panel_id))
        {
	        $this->db->select('hms_opd_particular_charge.*');
			$this->db->from('hms_opd_particular_charge'); 
			$this->db->where('hms_opd_particular_charge.panel_company_id',$panel_id);
			$query = $this->db->get();
			$result = $query->result();
		}
		
		if(!empty($result))
		{
			foreach($result as $particular_charges)
			{

			if($type==1)
			{
				 $panelcharge =$particular_charges->charge+$vals;
			}
			elseif($type==2)
			{
				$panelcharge =$particular_charges->charge + ($vals/100)*$particular_charges->charge;	
				
			}
			else
			{
				$panelcharge = $particular_charges->charge;
			}	
					$ipd_room_charge = array('charge'=>$panelcharge);
					$this->db->where('id',$particular_charges->id);
					$this->db->where('panel_company_id',$panel_id);
					$this->db->update('hms_opd_particular_charge',$ipd_room_charge);
					//echo $this->db->last_query(); exit;
			}
		}
			 
        	return 1;
	}
   

}
?>