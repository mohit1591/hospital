<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate_type_model extends CI_Model {

	var $table = 'hms_doctor_certificate';
	var $column = array('hms_doctor_certificate.id','hms_doctor_certificate.title', 'hms_doctor_certificate.template','hms_doctor_certificate.created_date','hms_doctor_certificate.modified_date');  
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
		$this->db->select("hms_doctor_certificate.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_doctor_certificate.is_deleted','0');
       
            $this->db->where('hms_doctor_certificate.branch_id',$users_data['parent_id']);
	
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
    function get_ipd_patient()
    {
    	
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient.*,hms_ipd_booking.attend_doctor_id as doctors_id"); 
	   $this->db->from('hms_patient');
	   $this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id=hms_patient.id');
	   $this->db->where('hms_patient.is_deleted','0');
	   $this->db->where('hms_patient.branch_id',$user_data['parent_id']);
	   $this->db->order_by('hms_patient.id','DESC'); 
	   $query = $this->db->get(); 
	   return $query->result_array();
    }
    function get_ot_patient()
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient.*,hms_operation_booking.doctor_id as doctors_id"); 
	   $this->db->from('hms_patient');
	   $this->db->join('hms_operation_booking','hms_operation_booking.patient_id=hms_patient.id');
	   $this->db->where('hms_patient.is_deleted','0');
	   $this->db->where('hms_patient.branch_id',$user_data['parent_id']);
	   $this->db->order_by('hms_patient.id','DESC'); 
	   $query = $this->db->get(); 
	   return $query->result_array();
   }

   function get_opd_patient()
   {
       $user_data = $this->session->userdata('auth_users');
      $this->db->select("hms_patient.*,hms_opd_booking.attended_doctor as doctors_id"); 
	   $this->db->from('hms_patient');
	   $this->db->join('hms_opd_booking','hms_opd_booking.patient_id=hms_patient.id');
	   $this->db->where('hms_patient.is_deleted','0');
	   $this->db->where('hms_patient.branch_id',$user_data['parent_id']);
	   $this->db->where('hms_opd_booking.type','2');
	   $this->db->order_by('hms_patient.id','DESC'); 
	   $query = $this->db->get(); 
	   //echo $this->db->last_query();
	   return $query->result_array();

   }

    function get_pathology_patient()
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient.*,path_test_booking.attended_doctor as doctors_id"); 
	   $this->db->from('hms_patient');
	   $this->db->join('path_test_booking','path_test_booking.patient_id=hms_patient.id');
	   $this->db->where('hms_patient.is_deleted','0');
	   $this->db->where('hms_patient.branch_id',$user_data['parent_id']);
	   $this->db->order_by('hms_patient.id','DESC'); 
	   $query = $this->db->get(); 
	   return $query->result_array();
    }
    function get_template_data()
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_doctor_certificate.*"); 
	   $this->db->from('hms_doctor_certificate');
	  // $this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id=hms_patient.id');
	   $this->db->where('hms_doctor_certificate.branch_id',$user_data['parent_id']);
           $this->db->where('hms_doctor_certificate.is_deleted!=2');
	   $query = $this->db->get(); 
	   return $query->result_array();
    }

    function save_certificate()
    {
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		
		$data = array(    
				'patient_id'=>$post['patient_id'],
				'certificate_id' => $post['certificate_id'],
				'branch_id'=>$user_data['parent_id'],
				'template'=>$post['template']
				
			);	
		$this->db->set('created_by',$user_data['id']);
		$this->db->set('created_date',date('Y-m-d H:i:s'));
		$this->db->insert('hms_branch_patient_certificate',$data);
		$certificateid = $this->db->insert_id();
		return $certificateid; 
		//echo $this->db->last_query(); exit;
    }

    function get_print_data($id='')
    {
    	$this->db->select('hms_branch_patient_certificate.*');
		$this->db->where('hms_branch_patient_certificate.id = "'.$id.'"');
		$this->db->from('hms_branch_patient_certificate');
		$result=$this->db->get()->result();

		return $result;
    }

    function get_template_data_by_patient_id($get="")
    {
    	 $user_data = $this->session->userdata('auth_users');
    	// echo $user_data['parent_id'];die;
     	 $this->db->select("*");
		 $this->db->from('hms_doctor_certificate'); 
		 $this->db->where('hms_doctor_certificate.branch_id',$user_data['parent_id']);
		  $this->db->where('hms_doctor_certificate.id',$get['certificate_id']);
		 $query = $this->db->get(); 
		 return $query->row_array();
    }
    public function get_patient_data($get="")
	{ 
		//print_r($get);die;
		//echo $get['patient_id'];die;
		$this->db->select("hms_patient.*, hms_cities.city, hms_state.state, hms_countries.country, hms_simulation.simulation, hms_religion.religion, hms_relation.relation, hms_insurance_type.insurance_type as insurance_types, hms_insurance_company.insurance_company, hms_users.username,hms_gardian_relation.relation as g_relation"); 
		$this->db->from('hms_patient'); 
		$this->db->where('hms_patient.id',$get['patient_id']);
		// $this->db->where('hms_patient.is_deleted','0'); 
		$this->db->join('hms_users','hms_users.parent_id=hms_patient.id AND hms_users.users_role=4','left');
		$this->db->join('hms_religion','hms_religion.id=hms_patient.religion_id','left');
		$this->db->join('hms_relation','hms_relation.id=hms_patient.relation_id','left');
		
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		
		$this->db->join('hms_countries','hms_countries.id=hms_patient.country_id','left');
		$this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_simulation','hms_simulation.id=hms_patient.simulation_id','left');
        $this->db->join('hms_insurance_type','hms_insurance_type.id=hms_patient.insurance_type_id','left');
        $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id','left');
		$query = $this->db->get(); 
		return $query->row_array();
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
    
    public function simulation_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('simulation','ASC'); 
    	$query = $this->db->get('hms_ipd_room_category');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_doctor_certificate.*');
		$this->db->from('hms_doctor_certificate'); 
		$this->db->where('hms_doctor_certificate.id',$id);
		$this->db->where('hms_doctor_certificate.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'title'=>$post['title'],
					'template'=>$post['template'],
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_doctor_certificate',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));

			$this->db->insert('hms_doctor_certificate',$data);               
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
			$this->db->update('hms_doctor_certificate');
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
			$this->db->update('hms_doctor_certificate');
			//echo $this->db->last_query();die;
    	} 
    }

    public function get_simulation_name($simulation_id)
    {
        $this->db->select('simulation'); 
        $this->db->where('id',$simulation_id);                   
        $query = $this->db->get('hms_ipd_room_category');
        $test_list = $query->result(); 
            foreach($test_list as $simulations)
            {
               $simulation = $simulations->simulation;
            } 
        
        return $simulation; 
    }
    public function get_room_type_charges()
    {
    	$users_data = $this->session->userdata('auth_users');
    	$post = $this->input->post();
    	$result = array();
    	if(isset($post['room_category_id']) && !empty($post['room_category_id']))
    	{
    		$this->db->select('*');
    		$this->db->where('room_cat_type',$post['room_category_id']);
    		$this->db->where('branch_id',$users_data['parent_id']);
    		$query = $this->db->get('hms_rooms');
    		$result = $query->result_array();
    	}
    	return $result;

    }
    

  

}
?>