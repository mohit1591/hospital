<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Booking_model extends CI_Model 
{
	var $table = 'hms_ambulance_booking';
	var $column = array('hms_ambulance_booking.id','hms_ambulance_booking.booking_no','hms_patient.patient_name', 'hms_ambulance_booking.created_date', 'hms_ambulance_booking.booking_date', 'hms_ambulance_booking.status','hms_patient.patient_code', 'hms_patient.mobile_no',
	'hms_ambulance_booking.booking_time','hms_ambulance_booking.net_amount', 'hms_ambulance_booking.paid_amount', 'hms_ambulance_booking.discount','hms_ambulance_driver.driver_name','hms_ambulance_vehicle.vehicle_no'); 

	var $order = array('hms_ambulance_booking.id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
	    //refund_amount
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ambulance_booking.*,hms_patient.patient_name,
			hms_patient.patient_code as patient_reg_no, hms_patient.address, hms_patient.father_husband,src.source as patient_source,hms_ambulance_vehicle.vehicle_no,hms_ambulance_driver.driver_name,hms_ambulance_location.location_name,(select sum(payment.debit) as paid_amount1 from hms_payment as payment where payment.parent_id = hms_ambulance_booking.id AND payment.section_id =13 AND payment.type!=12) as paid_amount1,(select sum(payment.credit)-sum(payment.debit) as balance1 from hms_payment as payment where payment.parent_id = hms_ambulance_booking.id AND payment.section_id =13 AND payment.type!=12) as balance1");

		$this->db->join('hms_patient','hms_patient.id=hms_ambulance_booking.patient_id','Left');
		

        $this->db->join('hms_patient_source as src', 'src.id=hms_ambulance_booking.source', 'Left');
    
    	$this->db->join('hms_doctors','hms_doctors.id = hms_ambulance_booking.reffered','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
          $this->db->join('hms_ambulance_driver','hms_ambulance_driver.id = hms_ambulance_booking.driver_id','left');
          $this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id = hms_ambulance_booking.vehicle_no','left');
           $this->db->join('hms_ambulance_location','hms_ambulance_location.id = hms_ambulance_booking.location','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_ambulance_booking.payment_mode','left');
      
		$search = $this->session->userdata('ambulance_search');
		
		if($user_data['users_role']==4)
		{
			$this->db->where('hms_ambulance_booking.patient_id = "'.$user_data['parent_id'].'"');
			
		}
		elseif($user_data['users_role']==3)
		{
			$this->db->where('hms_ambulance_booking.reffered = "'.$user_data['parent_id'].'"');
		}
		else
		{
			if(isset($search['branch_id']) && $search['branch_id']!=''){
			$this->db->where('hms_ambulance_booking.branch_id IN ('.$search['branch_id'].')');
			}else{
			$this->db->where('hms_ambulance_booking.branch_id = "'.$user_data['parent_id'].'"');
			}
		}
	
		$this->db->where('hms_ambulance_booking.status',1);
		/////// Search query end //////////////
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['source_from']))
			{
				$this->db->where('hms_ambulance_booking.source',$search['source_from']);
			}
			 if(!empty($search['destination']))
			{
				$this->db->where('hms_ambulance_booking.destination',$search['destination']);
			}
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_ambulance_booking.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:00';
				$this->db->where('hms_ambulance_booking.created_date <= "'.$end_date.'"');
			}

			 if(isset($search['vehicle_type']) && !empty($search['vehicle_type']))
                {
                    $this->db->where('hms_ambulance_vehicle.vehicle_type = "'.$search['vehicle_type'].'"');
                }
               
                if(!empty($search['owner_name']))
			{
				$this->db->where('hms_ambulance_booking.vendor_id',$search['owner_name']);
			}
			
			      if(!empty($search['']))
			{
				$this->db->where('hms_ambulance_booking.location',$search['location']);
			}
              
			if(!empty($search['amount_from']))
			{
				$this->db->where('hms_ambulance_booking.total_amount >= "'.$search['amount_from'].'"');
			}

			if(!empty($search['amount_to']))
			{
				$this->db->where('hms_ambulance_booking.total_amount <= "'.$search['amount_to'].'"');
			}

			if(!empty($search['paid_amount_from']))
			{
				$this->db->where('hms_ambulance_booking.paid_amount >= "'.$search['paid_amount_from'].'"');
			}

			if(!empty($search['paid_amount_to']))
			{
				$this->db->where('hms_ambulance_booking.paid_amount <= "'.$search['paid_amount_to'].'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}
			if(!empty($search['vehicle_no']))
			{
				$this->db->where('hms_ambulance_vehicle.id',$search['vehicle_no']);
			}
		
			if(!empty($search['driver_name']))
			{
				// $this->db->where('hms_ambulance_driver.driver_name',$search['driver_name']);
				$this->db->where('hms_ambulance_driver.driver_name LIKE "'.$search['driver_name'].'%"');
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}
			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(!empty($search['booking_no']))
			{
				$this->db->where('hms_ambulance_booking.booking_no',$search['booking_no']);
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}
			if(!empty($search['address']))
			{
				$this->db->where('hms_patient.address',$search['address']);
			}
			 if(!empty($search['going_date']))
			{
				$going_date = date('Y-m-d',strtotime($search['going_date'])).' 00:00:00';
				$this->db->where('hms_ambulance_booking.going_date = "'.$going_date.'"');
			}
			

		

			if(!empty($search['referral_doctor']))
			{
				$this->db->where('hms_ambulance_booking.reffered',$search['referral_doctor']);
			}
			
			if(!empty($search['referral_hospital']))
			{
				$this->db->where('hms_ambulance_booking.referral_hospital',$search['referral_hospital']);
			}


			
			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}
			if(!empty($search['patient_email']))
			{
				$this->db->where('hms_patient.patient_email LIKE "'.$search['patient_email'].'%"');
			}

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_ambulance_booking.status',$search['status']);
			}
			if(isset($search['booking_status']) && $search['booking_status']!="")
			{
				$this->db->where('hms_ambulance_booking.booking_status',$search['booking_status']);
			}
				if(isset($search['payment_mode']) && $search['payment_mode']!="")
			{
				$this->db->where('hms_ambulance_booking.payment_mode',$search['payment_mode']);
			}
			
			if(isset($search['distance_type']) && $search['distance_type']!="")
			{
				$this->db->where('hms_ambulance_booking.distance_type',$search['distance_type']);
			}
			
			if(!empty($search['owner_name']))
			{
				$this->db->where('hms_ambulance_vehicle.vendor_id',$search['owner_name']);
			}
			


		}

		$emp_ids='';
		if($user_data['emp_id']>0)
		{
			if($user_data['record_access']=='1')
			{
				$emp_ids= $user_data['id'];
			}
		}
		elseif(!empty($get["employee"]) && is_numeric($get['employee']))
        {
            $emp_ids=  $get["employee"];
        }
		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('hms_ambulance_booking.created_by IN ('.$emp_ids.')');
		}
		$this->db->from($this->table); 
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
		$user_data = $this->session->userdata('auth_users');
		
		$this->db->join('hms_patient','hms_patient.id=hms_ambulance_booking.patient_id');
		

        $this->db->join('hms_patient_source as src', 'src.id=hms_ambulance_booking.source', 'Left');
    
    	$this->db->join('hms_doctors','hms_doctors.id = hms_ambulance_booking.reffered','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
          $this->db->join('hms_ambulance_driver','hms_ambulance_driver.id = hms_ambulance_booking.driver_id','left');
          $this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id = hms_ambulance_booking.vehicle_no','left');
      
		$search = $this->session->userdata('ambulance_search');
		
		if($user_data['users_role']==4)
		{
			$this->db->where('hms_ambulance_booking.patient_id = "'.$user_data['parent_id'].'"');
			
		}
		elseif($user_data['users_role']==3)
		{
			$this->db->where('hms_ambulance_booking.reffered = "'.$user_data['parent_id'].'"');
		}
		else
		{
			if(isset($search['branch_id']) && $search['branch_id']!=''){
			$this->db->where('hms_ambulance_booking.branch_id IN ('.$search['branch_id'].')');
			}else{
			$this->db->where('hms_ambulance_booking.branch_id = "'.$user_data['parent_id'].'"');
			}
		}
	
		
		/////// Search query end //////////////
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['source_from']))
			{
				$this->db->where('hms_ambulance_booking.source',$search['source_from']);
			}
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_ambulance_booking.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date']));
				$this->db->where('hms_ambulance_booking.created_date <= "'.$end_date.'"');
			}

			 if(isset($search['vehicle_type']) && !empty($search['vehicle_type']))
                {
                    $this->db->where('hms_ambulance_vehicle.vehicle_type = "'.$search['vehicle_type'].'"');
                }
               

			

			/* Booking */


			

			if(!empty($search['amount_from']))
			{
				$this->db->where('hms_ambulance_booking.total_amount >= "'.$search['amount_from'].'"');
			}

			if(!empty($search['amount_to']))
			{
				$this->db->where('hms_ambulance_booking.total_amount <= "'.$search['amount_to'].'"');
			}

			if(!empty($search['paid_amount_from']))
			{
				$this->db->where('hms_ambulance_booking.paid_amount >= "'.$search['paid_amount_from'].'"');
			}

			if(!empty($search['paid_amount_to']))
			{
				$this->db->where('hms_ambulance_booking.paid_amount <= "'.$search['paid_amount_to'].'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}
			if(!empty($search['vehicle_no']))
			{
				$this->db->where('hms_ambulance_vehicle.vehicle_no LIKE "'.$search['vehicle_no'].'%"');
			}
		
			if(!empty($search['driver_name']))
			{
				// $this->db->where('hms_ambulance_driver.driver_name',$search['driver_name']);
				$this->db->where('hms_ambulance_driver.driver_name LIKE "'.$search['driver_name'].'%"');
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}
			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(!empty($search['booking_no']))
			{
				$this->db->where('hms_ambulance_booking.booking_no',$search['booking_no']);
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}
			if(!empty($search['address']))
			{
				$this->db->where('hms_patient.address',$search['address']);
			}
			

		

			if(!empty($search['referral_doctor']))
			{
				$this->db->where('hms_ambulance_booking.reffered',$search['referral_doctor']);
			}
				if(!empty($search['referral_hospital']))
			{
				$this->db->where('hms_ambulance_booking.referral_hospital',$search['referral_hospital']);
			}

			
			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_ambulance_booking.status',$search['status']);
			}
			if(isset($search['booking_status']) && $search['booking_status']!="")
			{
				$this->db->where('hms_ambulance_booking.booking_status',$search['booking_status']);
			}
			


		}
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function get_all_payment($id='')
	{
	   $user_data = $this->session->userdata('auth_users');
		$this->db->select('SUM(hms_payment.debit) as payment_amount');
		$this->db->from('hms_payment'); 
		$this->db->where('hms_payment.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_payment.parent_id',$id);
		$this->db->where('hms_payment.type!=12');
	    $query = $this->db->get(); 
		return $query->row_array(); 
	}

	
	public function get_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ambulance_booking.*, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.dob,hms_patient.modified_date as patient_modified_date,hms_patient.guardian_name,hms_patient.guardian_phone,hms_patient.relation_id,hms_doctors.doctor_name,hms_doctors.mobile_no as doc_mob_no,hms_ambulance_vehicle_types.type as vehicletype,hms_patient.adhar_no');

		//,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no driver

		$this->db->from('hms_ambulance_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_ambulance_booking.patient_id'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_ambulance_booking.reffered','left'); 
		
			$this->db->join('hms_ambulance_vehicle_types','hms_ambulance_vehicle_types.id = hms_ambulance_booking.vehicle_type','left'); 
		//$this->db->join('hms_ambulance_driver','hms_ambulance_driver.id = hms_ambulance_booking.driver_id','left');
          //$this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id = hms_ambulance_booking.vehicle_no','left');
		$this->db->where('hms_ambulance_booking.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_ambulance_booking.id',$id);
		
		$query = $this->db->get(); 
	//echo $this->db->last_query(); exit;
		return $query->row_array();
	}
	
    	public function refund_amount($id,$payment_id='')
    	{
    	  $user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_payment.debit as payment_amount');
		$this->db->from('hms_payment'); 
		$this->db->where('hms_payment.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_payment.id',$payment_id);
	//	$this->db->where('hms_payment.id',$payment_id);
		$query = $this->db->get(); 
		return $query->row_array();
    	}
	public function get_by_ids($id,$payment_id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ambulance_booking.*,hms_payment.total_amount as payment_amount, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.dob,hms_patient.modified_date as patient_modified_date,hms_patient.guardian_name,hms_patient.guardian_phone,hms_patient.relation_id,hms_doctors.doctor_name');
		$this->db->from('hms_ambulance_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_ambulance_booking.patient_id'); 
		$this->db->join('hms_payment','hms_payment.parent_id = hms_ambulance_booking.id','left'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_ambulance_booking.reffered','left'); 
		$this->db->where('hms_ambulance_booking.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_ambulance_booking.id',$id);
		$this->db->where('hms_payment.id',$payment_id);
		$query = $this->db->get(); 
//	echo $this->db->last_query();die;
		return $query->result_array();
	}
	
	

    public function delete_booking($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			// $this->db->set('is_deleted',1);
			// $this->db->set('deleted_by',$user_data['id']);
			// $this->db->set('deleted_date',date('Y-m-d H:i:s'));
		//	$this->db->where('id',$id);
			$this->db->delete('hms_ambulance_booking',array('id'=>$id));
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
			// $this->db->set('is_deleted',1);
			// $this->db->set('deleted_by',$user_data['id']);
			// $this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->delete('hms_ambulance_booking');
    	} 
    }

    public function patient_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_ambulance_booking');
        $result = $query->result(); 
        return $result; 
    }

    public function get_prescription_count($booking_id='')
    {
    	$this->db->select('*');  
        $this->db->where('hms_opd_prescription.booking_id',$booking_id);
        $query = $this->db->get('hms_opd_prescription');
        //$result = $query->result(); 
        return $query->num_rows();

    }
    public function get_token_setting()
    {
    	//echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_token_setting.*"); 
	   $this->db->from('hms_token_setting');
	   $this->db->where('hms_token_setting.branch_id',$user_data['parent_id']);
	   $query = $this->db->get(); 
	   return $query->row_array();
    }

    public function get_opd_validity_days()
    {
    	//echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_opd_validity_setting.days"); 
	   $this->db->from('hms_opd_validity_setting');
	   $this->db->where('hms_opd_validity_setting.branch_id',$user_data['parent_id']);
	   $query = $this->db->get(); 
	   return $query->row_array();
    }

    

    public function get_patient_token_details($id='')
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient_to_token.*"); 
	   $this->db->from('hms_patient_to_token');
	   $this->db->where('hms_patient_to_token.doctor_id',$id);
	   $this->db->where('hms_patient_to_token.branch_id',$user_data['parent_id']);
	   $query = $this->db->get(); 
	   return $query->row_array();

    }
     public function get_patient_token_details_for_type_doctor($id='',$booking_date='',$type='')
    {
    	//echo $type;die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient_to_token.*"); 
	   $this->db->from('hms_patient_to_token');
	   $this->db->where('hms_patient_to_token.doctor_id',$id);
	   $this->db->where('hms_patient_to_token.booking_date',$booking_date);
	   $this->db->where('hms_patient_to_token.type',$type);
	   $this->db->where('hms_patient_to_token.branch_id',$user_data['parent_id']);
	   $this->db->order_by("id", "DESC");
       $this->db->limit(1, 0);
	   $query = $this->db->get(); 
	   return $query->row_array();

    }
    public function get_patient_token_details_for_type_hospital($booking_date='',$type='')
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient_to_token.*"); 
	   $this->db->from('hms_patient_to_token');
	   $this->db->where('hms_patient_to_token.booking_date',$booking_date);
	   $this->db->where('hms_patient_to_token.type',$type);
	   $this->db->where('hms_patient_to_token.branch_id',$user_data['parent_id']);
	   $this->db->order_by("id", "DESC");
       $this->db->limit(1, 0);
	   $query = $this->db->get(); 
	  // $count=$query->num_rows();
	   return $query->row_array();

    }


    public function referal_doctor_list($branch_id='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (0,2)'); 
		if(!empty($branch_id))
		{
			$this->db->where('hms_doctors.branch_id',$branch_id);
		}
		else
		{
			$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		}
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    public function referal_hospital_list($branch_id="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_hospital.hospital_name','ASC');
		$this->db->where('hms_hospital.is_deleted',0); 
		if(!empty($branch_id))
		{
			$this->db->where('hms_hospital.branch_id',$branch_id);
		}
		else
		{
			$this->db->where('hms_hospital.branch_id',$users_data['parent_id']);
		}
		  
		$query = $this->db->get('hms_hospital');
		$result = $query->result_array(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    public function attended_doctor_list($branch_id='',$specialization='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (1,2)'); 
		if(!empty($specialization))
        {
            $this->db->where('specilization_id',$specialization); 
        }
		if(!empty($branch_id))
		{
			$this->db->where('hms_doctors.branch_id',$branch_id);
		}
		else
		{
			$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		}  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    } 

    public function employee_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_employees.name','ASC');
		$this->db->where('is_deleted',0);
		$this->db->where('type',1);
		$this->db->where('branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_employees');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    public function profile_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_profile.profile_name','ASC');
		$this->db->where('is_deleted',0);  
		$this->db->where('branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_profile');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    public function save_booking()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//echo "<pre>"; print_r($post); exit;
		if(!empty($post['branch_id']))
        {
        	$branch_id = $post['branch_id'];
        }
        else
        {
        	$branch_id = $user_data['parent_id'];
        }

		$available_time_value='';
		if(!empty($post['available_time']))
		{
			$available_time_value = $this->get_available_time_value($post['available_time']);
		}
   		
   		
       		if($post['specialization']==EYE_SPECIALIZATION_ID)
    		{
    			$booking_type="1";    // Eye
    		}
    		elseif($post['specialization']==PEDIATRICIAN_SPECIALIZATION_ID)
            {
              $booking_type="2";    // PEDIATRICIAN 
            }
            elseif($post['specialization']==DENTAL_SPECIALIZATION_ID)
    		{
    			$booking_type="3";    // DENTAL	
    		}
    		elseif($post['specialization']==GYNECOLOGY_SPECIALIZATION_ID)
    		{
    			$booking_type="4";    // DENTAL	
    		}
    		else
    		{
    			$booking_type="0";    // Normal	
    		}
    		$dob = '0000-00-00';
    		if(!empty($post['dob']))
    		{
    		 $dob_new=date('Y-m-d',strtotime($post['dob']));		
    	    if($dob_new!='1970-01-01' || $dob_new!="0000-00-00")
            {
                $dob=$dob_new;
               
    		}  
		}
		
		
			
		
        $data_patient = array(
							'simulation_id'=>$post['simulation_id'],
							'branch_id'=>$branch_id,
							'patient_name'=>$post['patient_name'],
							'dob'=>$dob,
							'mobile_no'=>$post['mobile_no'],
							'gender'=>$post['gender'],
							'age_y'=>$post['age_y'],
							'age_m'=>$post['age_m'],	
							'age_d'=>$post['age_d'],	
							'address'=>$post['address'],
							'address2'=>$post['address_second'],
							'address3'=>$post['address_third'],
							'city_id'=>$post['city_id'],
							'state_id'=>$post['state_id'],
							'country_id'=>$post['country_id'],
							'guardian_name'=>$post['guardian_name'],
							'guardian_phone'=>$post['guardian_phone'],
							'patient_email'=>$post['patient_email'],
							'adhar_no'=>$post['adhar_no'],
							'relation_type'=>$post['relation_type'],
							'relation_name'=>$post['relation_name'],
							'relation_simulation_id'=>$post['relation_simulation_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'], 
					    	);
		
		
			if($post['pay_now']==1){ $booking_status =1; }else{ $booking_status =0;}  
			
			
			if(!empty($post['referral_doctor'])){ $reffered =$post['referral_doctor']; }else{ $reffered =0;}  
			
			if(!empty($post['vendor_id'])){ $vendor_id =$post['vendor_id']; }else{ $vendor_id =0;}  
			
			if(!empty($post['referral_hospital'])){ $referral_hospital =$post['referral_hospital']; }else{ $referral_hospital =0;}  
			

			$data_test=array(
                                 'vehicle_no'=>$post['vehicle_no'],
                                 'vehicle_type'=>$post['vehicle_type'],
                                 'driver_id'=>$post['driver_name'],
                                  'reffered'=>$reffered,
                                  'staff_id'=>$post['hospital_staff'],
                                  'booking_date'=>date('Y-m-d',strtotime($post['booking_date'])),
                                  'booking_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time'])),
                                  'going_date'=>date('Y-m-d',strtotime($post['going_date'])),
                                  'going_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['going_time'])),
                                  'total_amount'=>$post['total_amount'],
                                  'paid_amount'=>$post['paid_amount'],
                                  'discount'=>$post['discount'],
								  'net_amount'=>$post['net_amount'],
								  'balance'=>$post['balance'],
                                  'charge'=>$post['consultants_charge'],
                                  'distance'=>$post['distance'],
                                  'distance_type'=>$post['distance_type'],
                                  'location'=>$post['location'],
                                  'destination'=>$post['destination'],
                                  'remark'=>$post['remark'],
                                  'vendor_id'=>$vendor_id,
                                  'payment_mode'=>$post['payment_mode'],
                                  'branch_id'=>$branch_id,                            
                                  'source'=>$post['source_from'],              
                                  'reffered_by'=>$post['referred_by'],
                                  'referral_hospital'=>$referral_hospital,
                                  'status'=>1,
                                  'owner_type'=>$post['ven_tp'],
                                  'ven_charge_type'=>$post['ven_charge_type'],
                                  'vendor_charge'=>$post['vendor_charge'],
                                 'refund_amount'=>$post['refund_amount_data'],
                                 'hospital_id'=>$post['hospital_id'],
                                  'ip_address'=>$_SERVER['REMOTE_ADDR']
                                
                                 );

		
		//for payment
		if($post['pay_now']==1)
		{
			$data_pay_test = array( 
						'pay_now'=>$post['pay_now'],
						'paid_amount'=>$post['paid_amount'],
						);
		}
		else
		{
			$data_pay_test = array();

		}
		
    	$data_testr = array_merge($data_test, $data_pay_test);
        //update booking
        if(!empty($post['data_id']) && $post['data_id']>0)
		{ 
			//echo "dsj";die;
		    $created_by= $this->get_by_id($post['data_id']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$post['patient_id']);
	    	$this->db->update('hms_patient',$data_patient);  
	    	//echo $this->db->last_query();die;
	    	
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->set('enquiry_status',1);
            $this->db->where('booking_no',$post['booking_code']);
			$this->db->update('hms_ambulance_enquiry'); 
		    //echo $this->db->last_query();die;

			$booking_id = $post['data_id'];
            $this->db->set('refund_amount',$post['refund_amount_data']);
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ambulance_booking',$data_testr);  
			//echo $this->db->last_query(); exit;
		    
            $this->db->where('parent_id',$booking_id);
            $this->db->where('section_id','13');
            $this->db->where('type','10');
            
            
            $this->db->where('patient_id',$post['patient_id']);
            $query_d_pay = $this->db->get('hms_payment');
            $row_d_pay = $query_d_pay->result();


            if(!empty($row_d_pay))
            {
            	foreach($row_d_pay as $row_d)
            	{
            		$payment_data = array(
							'parent_id'=>$booking_id,
							'branch_id'=>$branch_id,
							'vendor_id'=>$post['vendor_id'],
							'section_id'=>'13',
							'hospital_id'=>$post['referral_hospital'],
							'doctor_id'=>$post['referral_doctor'],
							'patient_id'=>$post['patient_id'],
							'total_amount'=>$post['total_amount'],
							'discount_amount'=>$post['discount'],
							'net_amount'=>$post['net_amount'],
							'credit'=>$post['net_amount'],
							'debit'=>$post['paid_amount'],
							'balance'=>($post['net_amount']-$post['paid_amount'])+1,
							'pay_mode'=>$post['payment_mode'],
							'paid_amount'=>$post['paid_amount'],
							'created_by'=>$row_d->created_by,//$user_data['id'],
							'created_date'=>$row_d->created_date,//date('Y-m-d H:i:s',strtotime($post['booking_date'])),
            	        );
					$this->db->where('id',$row_d->id);
            		$this->db->update('hms_payment',$payment_data); 
            		
            	
            

            	}
            	/*	if($post['total_paid_amount'] > $post['paid_amount'])
            		{
            		    	$payment_data1 = array(
							'parent_id'=>$booking_id,
							'branch_id'=>$branch_id,
							'vendor_id'=>$post['vendor_id'],
							'section_id'=>'13',
							'hospital_id'=>$post['referral_hospital'],
							'doctor_id'=>$post['referral_doctor'],
							'patient_id'=>$post['patient_id'],
							'total_amount'=>$post['refund_amount'],
							'discount_amount'=>$post['discount'],
							'net_amount'=>$post['refund_amount'],
							'credit'=>$post['refund_amount'],
							'debit'=>$post['refund_amount'],
						//	'balance'=>($post['net_amount']-$post['paid_amount'])+1,
							'pay_mode'=>$post['payment_mode'],
							'paid_amount'=>$post['refund_amount'],
							'created_by'=>$row_d->created_by,//$user_data['id'],
							'created_date'=>$row_d->created_date,//date('Y-m-d H:i:s',strtotime($post['booking_date'])),
            	        );
				//	$this->db->where('id',$row_d->id);
            		$this->db->insert('hms_payment',$payment_data1); 
            		}*/
            }
            else{
                
            $payment_data = array(
                               'parent_id'=>$post['data_id'],
                               'branch_id'=>$branch_id,
                               'section_id'=>'13',
                               'vendor_id'=>$post['vendor_id'],
                               'type'=>10,
                               'hospital_id'=>$post['referral_hospital'],
                               'doctor_id'=>$post['referral_doctor'],
                               'patient_id'=>$post['patient_id'],
                               'total_amount'=>$post['consultants_charge'],
                               'discount_amount'=>$post['discount'],
                               'net_amount'=>$post['net_amount'],
                               'credit'=>$post['net_amount'],
                               'debit'=>$post['paid_amount'],
                               'balance'=>($post['net_amount']-$post['paid_amount'])+1,
                               'pay_mode'=>$post['payment_mode'],
								'paid_amount'=>$post['paid_amount'],
                               'created_by'=>$user_data['id'],
                               'created_date'=>date('Y-m-d H:i:s',strtotime($post['booking_date'].' '.$post['booking_time'])),
            	             );

            
            $this->db->insert('hms_payment',$payment_data);
            $payment_id = $this->db->insert_id();
          //  echo $this->db->last_query();die;
            }
             /*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>7,'section_id'=>4));
			$this->db->delete('hms_payment_mode_field_value_acc_section');
			if(!empty($post['field_name']))
			{
				$post_field_value_name= $post['field_name'];
				$counter_name= count($post_field_value_name); 
				for($i=0;$i<$counter_name;$i++) 
					{
						$data_field_value= array(
						'field_value'=>$post['field_name'][$i],
						'field_id'=>$post['field_id'][$i],
						'type'=>10,
						'section_id'=>13,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$post['data_id'],
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
			}

			/*add sales banlk detail*/
            /* save particular */	
		    $this->db->where('branch_id',$user_data['parent_id']);	
            $this->db->where('booking_id',$post['data_id']);	
            $this->db->delete('hms_ambulance_booking_to_particulars'); 	
            $amd_particular_billing_list = $this->session->userdata('amb_particular_billing');
            if(!empty($amd_particular_billing_list) && isset($amd_particular_billing_list) )	
            {	
            	foreach($amd_particular_billing_list as $particular_billing)	
            	{
            	    if(!empty($particular_billing['particular']))
            	    {
            		$particular_data = array(	
            			'booking_id'=>$post['data_id'],	
            			'branch_id'=>$user_data['parent_id'],	
            			'particular'=>$particular_billing['particular'],	
            			'quantity'=>$particular_billing['quantity'],	
            			'amount'=>$particular_billing['amount'],	
            			'particulars'=>$particular_billing['particulars'],	
            			'status'=>1, 	
            			'created_by'=>$user_data['id'],	
            			'created_date'=>date('Y-m-d H:i:s'),	
            			'ip_address'=>$_SERVER['REMOTE_ADDR'], 	
            			);	
            		$this->db->insert('hms_ambulance_booking_to_particulars',$particular_data);	
            	    }
            	}
            	$this->session->unset_userdata('amb_particular_billing');
            }


		}
		else
		{   
			if(!empty($post['patient_id']) && $post['patient_id']>0)
		    {
		    	$patient_id = $post['patient_id'];
		    	$this->db->where('id',$post['patient_id']);
	    	    $this->db->update('hms_patient',$data_patient);
		    } 
		    else
		    {
		    	//echo "dsd";die;
		    	$patient_code = generate_unique_id(4);
		    	$this->db->set('patient_code',$patient_code);
		    	$this->db->set('created_by',$user_data['id']);
			    $this->db->set('created_date',date('Y-m-d H:i:s'));
		    	$this->db->insert('hms_patient',$data_patient);  
		    	$patient_id = $this->db->insert_id();

		    		if(in_array('388',$user_data['permission']['section']))
					{
		    	
		    	//echo $this->db->last_query(); exit;
		    	
		    	//user create
			    $data = array(     
								"users_role"=>4,
								"parent_id"=>$patient_id,
								"username"=>'PAT000'.$patient_id,
								"password"=>md5('PASS'.$patient_id),
								"email"=>$post['patient_email'], 
								"status"=>'1',
								"ip_address"=>$_SERVER['REMOTE_ADDR'],
								"created_by"=>$user_data['id'],
								"created_date" =>date('Y-m-d H:i:s')
	                 ); 
					$this->db->insert('hms_users',$data); 
					$users_id = $this->db->insert_id();    
					
				}

				
		    }

		   	
		   	$bookingcode = generate_unique_id(53);
		   	//$this->db->set('created_date',date('Y-m-d H:i:s'));
		   	
		    $this->db->set('booking_no',$bookingcode);
		    $this->db->set('first_paid_amount',$post['paid_amount']);
		    $this->db->set('patient_id',$patient_id);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ambulance_booking',$data_testr);    
            $booking_id = $this->db->insert_id(); 
            //echo $this->db->last_query();die;

           /*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>10,'section_id'=>12));
			$this->db->delete('hms_payment_mode_field_value_acc_section');
			if(!empty($post['field_name']))
			{
				$post_field_value_name= $post['field_name'];
				$counter_name= count($post_field_value_name); 
				for($i=0;$i<$counter_name;$i++) 
					{
						$data_field_value= array(
						'field_value'=>$post['field_name'][$i],
						'field_id'=>$post['field_id'][$i],
						'type'=>10,
						'section_id'=>13,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$booking_id,
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
			}
		 /*add sales banlk detail*/ 
    
            
            $payment_data = array(
                               'parent_id'=>$booking_id,
                               'branch_id'=>$branch_id,
                               'section_id'=>'13',
                               'vendor_id'=>$post['vendor_id'],
                               'type'=>10,
                               'hospital_id'=>$post['referral_hospital'],
                               'doctor_id'=>$post['referral_doctor'],
                               'patient_id'=>$patient_id,
                               'total_amount'=>$post['consultants_charge'],
                               'discount_amount'=>$post['discount'],
                               'net_amount'=>$post['net_amount'],
                               'credit'=>$post['net_amount'],
                               'debit'=>$post['paid_amount'],
                               'balance'=>($post['net_amount']-$post['paid_amount'])+1,
                               'pay_mode'=>$post['payment_mode'],
                               //'transection_no'=>$transaction_no,
								//'bank_name'=>$bank_name,
								//'cheque_no'=>$post['cheque_no'],
								//'cheque_date'=>date('Y-m-d',strtotime($post['cheque_date'])),
								'paid_amount'=>$post['paid_amount'],
                               'created_by'=>$user_data['id'],
                               'created_date'=>date('Y-m-d H:i:s',strtotime($post['booking_date'].' '.$post['booking_time'])),
            	             );

            
            $this->db->insert('hms_payment',$payment_data);
          //  echo $this->db->last_query();die;
            $payment_id = $this->db->insert_id();
            
             // ambulance particular add	
            $amd_particular_billing_list = $this->session->userdata('amb_particular_billing'); 	
            if(!empty($amd_particular_billing_list) && isset($amd_particular_billing_list) )	
            {	
            	foreach($amd_particular_billing_list as $particular_billing)	
            	{
            	    if(!empty($particular_billing['particular']))
            	    {
            		$particular_data = array(	
            			'booking_id'=>$booking_id,	
            			'branch_id'=>$user_data['parent_id'],	
            			'particular'=>$particular_billing['particular'],	
            			'quantity'=>$particular_billing['quantity'],	
            			'amount'=>$particular_billing['amount'],	
            			'particulars'=>$particular_billing['particulars'],	
            			'status'=>1, 	
            			'created_by'=>$user_data['id'],	
            			'created_date'=>date('Y-m-d H:i:s'),	
            			'ip_address'=>$_SERVER['REMOTE_ADDR'], 	
            			);	
            		$this->db->insert('hms_ambulance_booking_to_particulars',$particular_data);	
            	    }
            	}
            	$this->session->unset_userdata('amb_particular_billing');
            }
        
		}

		return $booking_id; 	
	}


	public function get_available_time_value($time_id='')
	{
		$value="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($time_id))
        {
            $this->db->select('hms_doctors_schedule.*');  
            $this->db->where('hms_doctors_schedule.id',$time_id); 
            $query = $this->db->get('hms_doctors_schedule');
            $result = $query->row(); 
           
             $value =  date("g:i A", strtotime($result->from_time)).' - '.date("g:i A", strtotime($result->to_time)); 
            //echo $this->db->last_query();die;
        }    
            return $value; 
	}


	public function check_patient_balance($patient_id="")
    {
    	if(!empty($patient_id))
    	{
    		$this->db->select('(sum(hms_payment.debit)-sum(hms_payment.credit)) as balance');
    		$this->db->where('hms_payment.patient_id',$patient_id);
    		$this->db->where('hms_payment.status',1);
    		$this->db->from('hms_payment');
    		$query = $this->db->get();
    		$result = $query->result();
    	    return $result[0]->balance;
    	}
    }


	public function opd_doctor_rate($doctor_id="")
    {
       $total_amount = 0;
       if(isset($doctor_id) && !empty($doctor_id))
       {
        
         $this->load->model('general/general_model');
         $doctor_data = $this->general_model->doctors_list($doctor_id);
         $doctor_pay_type = $doctor_data[0]->doctor_pay_type; 
         $rate_plan_id    = $doctor_data[0]->rate_plan_id; 
         //1 commision and 2 transaction
         $rate_list = $this->general_model->get_doctor_rate($doctor_pay_type,$rate_plan_id,$doctor_id);
         if(!empty($rate_list->doc_rate))
         {
         	$total_amount = $rate_list->doc_rate;		
         }
         
         
         
       }
       return $total_amount;
    }

   public function confirm_booking()
   {
   		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
			'branch_id'=>$user_data['parent_id'],
			'booking_date'=>date('Y-m-d',strtotime($post['booking_date'])),
			'booking_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time'])), 
			'total_amount'=>$post['total_amount'],
			'discount'=>$post['discount'],
			'net_amount'=>$post['net_amount'],
			'paid_amount'=>$post['paid_amount'],
			'balance'=>($post['net_amount']-$post['paid_amount']),
			'booking_status'=>1,
			'payment_mode'=>$post['payment_mode'],
			'transaction_no'=>$post['transaction_no'],
			'branch_name'=>$post['branch_name'],
			'confirm_date'=>date('Y-m-d H:i:s'),
			'pay_now'=>1
		   );
		

			if(!empty($post['data_id']) && $post['data_id']>0)
			{    
	            $this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
	            $this->db->where('id',$post['data_id']);
				$this->db->update('hms_ambulance_booking',$data);  
			}

			$this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','2');
            $this->db->where('patient_id',$post['patient_id']);
            $this->db->delete('hms_payment'); 
            $payment_data = array(
                       'parent_id'=>$post['data_id'],
                       'branch_id'=>$user_data['parent_id'],
                       'section_id'=>'2',
                       'patient_id'=>$post['patient_id'],
                       'total_amount'=>$post['total_amount'],
                       'discount_amount'=>$post['discount'],
                       'net_amount'=>$post['net_amount'],
                       'balance'=>($post['net_amount']-$post['paid_amount']),
                       'credit'=>$post['net_amount'],
                       'debit'=>$post['paid_amount'],
                       'created_by'=>$user_data['id'],
                       'created_date'=>date('Y-m-d H:i:s'),
    	             );
            $this->db->insert('hms_payment',$payment_data);
            $payment_id = $this->db->insert_id();
            if($post['paid_amount']>0)
			{
				$hospital_receipt_no= check_hospital_receipt_no();
				$data_receipt_data= array('branch_id'=>$user_data['parent_id'],
				'section_id'=>1,
				'parent_id'=>$post['data_id'],
				'payment_id'=>$payment_id,
				'reciept_prefix'=>$hospital_receipt_no['prefix'],
				'reciept_suffix'=>$hospital_receipt_no['suffix'],
				'created_by'=>$user_data['id'],
				'created_date'=>date('Y-m-d H:i:s')
				);
				$this->db->insert('hms_branch_hospital_no',$data_receipt_data);
			}
	}

   public function compalaints_list($compaints_id="")
   {
		$chief_complaints = explode(',', $compaints_id);
		$users_data = $this->session->userdata('auth_users'); 
		$compalaintsname="";
		$i=1;
		$total = count($chief_complaints);
		foreach ($chief_complaints as $value) 
		{
			$this->db->select('hms_opd_chief_complaints.chief_complaints');  
			$this->db->where('hms_opd_chief_complaints.id',$value);  
			$this->db->where('hms_opd_chief_complaints.is_deleted',0);  
			$this->db->where('hms_opd_chief_complaints.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_chief_complaints.id');  
			$query = $this->db->get('hms_opd_chief_complaints');
			$result = $query->row(); 

			$compalaintsname .= $result->chief_complaints;
			if($i!=$total)
			{
				$compalaintsname .=',';
			}
		
		$i++;
			
		}
		return $compalaintsname;
		 
    }

   public function examination_list($examination_id="")
   {
		$examination_ids = explode(',', $examination_id);
		$users_data = $this->session->userdata('auth_users'); 
		$examinationname="";
		$i=1;
		$total = count($examination_ids);
		foreach ($examination_ids as $value) 
		{
			$this->db->select('hms_opd_examination.examination');  
			$this->db->where('hms_opd_examination.id',$value);  
			$this->db->where('hms_opd_examination.is_deleted',0);  
			$this->db->where('hms_opd_examination.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_examination.id');  
			$query = $this->db->get('hms_opd_examination');
			$result = $query->row(); 

			$examinationname .= $result->examination;
			if($i!=$total)
			{
				$examinationname .=',';
			}
		
		$i++;
			
		}
		return $examinationname;
		 
    }


    public function examinations_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_examination');
        $result = $query->result(); 
        return $result; 
    }

    public function diagnosis_name($diagnosis_id="")
   {
		$diagnosis_ids = explode(',', $diagnosis_id);
		$users_data = $this->session->userdata('auth_users'); 
		$diagnosisname="";
		$i=1;
		$total = count($diagnosis_ids);
		foreach ($diagnosis_ids as $value) 
		{
			$this->db->select('hms_opd_diagnosis.diagnosis');  
			$this->db->where('hms_opd_diagnosis.id',$value);  
			$this->db->where('hms_opd_diagnosis.is_deleted',0);  
			$this->db->where('hms_opd_diagnosis.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_diagnosis.id');  
			$query = $this->db->get('hms_opd_diagnosis');
			$result = $query->row(); 

			$diagnosisname .= $result->diagnosis;
			if($i!=$total)
			{
				$diagnosisname .=',';
			}
		
		$i++;
			
		}
		return $diagnosisname;
		 
    }


    public function diagnosis_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_diagnosis');
        $result = $query->result(); 
        return $result; 
    }

    public function suggetion_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_suggetion');
        $result = $query->result(); 
        return $result; 
    }

   public function suggetion_name($suggetion_id="")
   {
		$suggetion_ids = explode(',', $suggetion_id);
		$users_data = $this->session->userdata('auth_users'); 
		$suggetionname="";
		$i=1;
		$total = count($suggetion_ids);
		foreach ($suggetion_ids as $value) 
		{
			$this->db->select('hms_opd_suggetion.medicine_suggetion');  
			$this->db->where('hms_opd_suggetion.id',$value);  
			$this->db->where('hms_opd_suggetion.is_deleted',0);  
			$this->db->where('hms_opd_suggetion.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_suggetion.id');  
			$query = $this->db->get('hms_opd_suggetion');
			$result = $query->row(); 

			$suggetionname .= $result->medicine_suggetion;
			if($i!=$total)
			{
				$suggetionname .=',';
			}
		
		$i++;
			
		}
		return $suggetionname;
		 
    }


    public function prv_history_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_prv_history');
        $result = $query->result(); 
        return $result; 
    }

   public function prv_history_name($prv_id="")
   {
		$prv_ids = explode(',', $prv_id);
		$users_data = $this->session->userdata('auth_users'); 
		$prvname="";
		$i=1;
		$total = count($prv_ids);
		foreach ($prv_ids as $value) 
		{
			$this->db->select('hms_opd_prv_history.prv_history');  
			$this->db->where('hms_opd_prv_history.id',$value);  
			$this->db->where('hms_opd_prv_history.is_deleted',0);  
			$this->db->where('hms_opd_prv_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_prv_history.id');  
			$query = $this->db->get('hms_opd_prv_history');
			$result = $query->row(); 

			$prvname .= $result->prv_history;
			if($i!=$total)
			{
				$prvname .=',';
			}
		
		$i++;
			
		}
		return $prvname;
		 
    }

    public function personal_history_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_personal_history');
        $result = $query->result(); 
        return $result; 
    }
    
    public function template_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_prescription_template');
        $result = $query->result(); 
        return $result; 
    }
    

   public function personal_history_name($personal_history_id="")
   {
		$personal_history_ids = explode(',', $personal_history_id);
		$users_data = $this->session->userdata('auth_users'); 
		$personalname="";
		$i=1;
		$total = count($personal_history_ids);
		foreach ($personal_history_ids as $value) 
		{
			$this->db->select('hms_opd_personal_history.personal_history');  
			$this->db->where('hms_opd_personal_history.id',$value);  
			$this->db->where('hms_opd_personal_history.is_deleted',0);  
			$this->db->where('hms_opd_personal_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_personal_history.id');  
			$query = $this->db->get('hms_opd_personal_history');
			$result = $query->row(); 

			$personalname .= $result->personal_history;
			if($i!=$total)
			{
				$personalname .=',';
			}
		
		$i++;
			
		}
		return $personalname;
		 
    }
    
    
    public function save_prescription()
    {
    	//echo "<pre>";print_r($_POST); exit;
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$prv_history="";
		if(!empty($post['prv_history']))
		{
			$prv_history = $post['prv_history'];
		}
		$personal_history="";
		if(!empty($post['personal_history']))
		{
			$personal_history = $post['personal_history'];
		}

		$data_patient = array(
								"patient_name"=>$post['patient_name'],
								"mobile_no"=>$post['mobile_no'],
								"gender"=>$post['gender'], 
								"age_y"=>$post['age_y'],
								"age_m"=>$post['age_m'],
								"age_d"=>$post['age_d'],
								'adhar_no'=>$post['aadhaar_no'],
								'relation_type'=>$post['relation_type'],
								'relation_name'=>$post['relation_name'],
								'relation_simulation_id'=>$post['relation_simulation_id'],
					    	);

		if(!empty($post['patient_id']) && $post['patient_id']>0)
	    {
	    	$patient_id = $post['patient_id'];
	    	$this->db->where('id',$post['patient_id']);
    	    $this->db->update('hms_patient',$data_patient);
	    } 
	    else
	    {
	    	$patient_code = generate_unique_id(4);
	    	$this->db->set('patient_code',$patient_code);
	    	$this->db->insert('hms_patient',$data_patient);  
	    	$patient_id = $this->db->insert_id();
	    	//echo $this->db->last_query(); exit; 
	    }
		
 		$data = array( 
					"branch_id"=> $user_data['parent_id'],      
					"booking_code"=>$post['booking_code'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					"prv_history"=>$prv_history,
					"personal_history"=>$personal_history,
					"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>date('Y-m-d H:i',strtotime($post['next_appointment_date'])),
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_opd_prescription',$data); 
			$data_id = $this->db->insert_id(); 
			/* update doctor status in opd */
			$data_update= array('doctor_checked_status'=>1);
			$this->db->where('id',$post['booking_id']);
			$this->db->update('hms_ambulance_booking',$data_update);
			/* update doctor status in opd */


			
			$total_test = count(array_filter($post['test_name']));
			for($i=0;$i<$total_test;$i++)
			{
				
					//check and add masters of test 
					if(!empty($post['test_name'][$i]) && empty($post['test_id'][$i]))
					{	
						$this->db->select('path_test.*');  
						$this->db->where('path_test.test_name',$post['test_name'][$i]);  
						$this->db->where('path_test.is_deleted!=2'); 
						$this->db->where('path_test.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('path_test');
						$num = $query->num_rows();
						//echo $this->db->last_query();
						//echo $num; exit;
						if($num>0)
						{
							$path_test_data = $query->result_array();
							if(!empty($path_test_data))
						    {
							    $test_id = $path_test_data[0]['id'];
						    }
						}
						else
						{
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'test_name'=>$post['test_name'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('path_test',$data);
								$test_id = $this->db->insert_id(); 
								//echo $this->db->last_query(); exit;
						}

					}
					else
					{
						$test_id = $post['test_id'][$i];
					}

					

				$test_data = array(
							"prescription_id"=>$data_id,
							"test_name"=>$post['test_name'][$i],
							"test_id"=>$test_id);
						$this->db->insert('hms_opd_prescription_patient_test',$test_data); 
						$test_data_id = $this->db->insert_id(); 
			}

			$total_prescription = count(array_filter($post['medicine_name'])); 
			for($i=0;$i<$total_prescription;$i++)
			{	
					//echo $post['medicine_name'][$i];
					//check and add masters 
					if(!empty($post['medicine_name'][$i]))
					{	
						$this->db->select('hms_medicine_entry.*');  
						//$this->db->from('hms_opd_medicine');
						$this->db->where('hms_medicine_entry.medicine_name',$post['medicine_name'][$i]);  
						$this->db->where('hms_medicine_entry.is_deleted=0'); 
						$this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_medicine_entry');
						$num = $query->num_rows();
						//echo $this->db->last_query();
						//echo $num; exit;
						if($num>0)
						{
							$company_data = $query->result_array();
							if(!empty($company_data))
						    {
							    $medicine_id = $company_data[0]['id'];
						    }
						}
						else
						{		
									$unit_id ='';
								//check medicine type
								if(!empty($post['medicine_type'][$i]))
								{
									 
									$this->db->select('hms_medicine_unit.*');  
									$this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]);  
									$this->db->where('hms_medicine_unit.is_deleted=0'); 
									$this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
									$query = $this->db->get('hms_medicine_unit');
									$num = $query->num_rows();
									//echo $this->db->last_query();
									//echo $num; exit;
									if($num>0)
									{
										$unit_data = $query->result_array();
										if(!empty($unit_data))
									    {
										    $unit_id = $unit_data[0]['id'];
									    }	
									}
									else
									{
										$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'test_name'=>$post['medicine_type'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

										$this->db->set('created_by',$user_data['id']);
										$this->db->set('created_date',date('Y-m-d H:i:s'));
										$this->db->insert('hms_medicine_unit',$data);
										$unit_id = $this->db->insert_id(); 
									}
								}

								////check medicine type end

								//check medicine company

								if(!empty($post['brand'][$i]))
								{
									 
									$this->db->select('hms_medicine_company.*');  
									$this->db->where('hms_medicine_company.medicine_unit',$post['brand'][$i]);  
									$this->db->where('hms_medicine_company.is_deleted=0'); 
									$this->db->where('hms_medicine_company.branch_id',$user_data['parent_id']); 
									$query = $this->db->get('hms_medicine_company');
									$num = $query->num_rows();
									//echo $this->db->last_query();
									//echo $num; exit;
									if($num>0)
									{
										$company_data = $query->result_array();
										if(!empty($company_data))
									    {
										    $company_id = $company_data[0]['id'];
									    }
									}
									else
									{
										$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'company_name'=>$post['brand'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

										$this->db->set('created_by',$user_data['id']);
										$this->db->set('created_date',date('Y-m-d H:i:s'));
										$this->db->insert('hms_medicine_company',$data);
										$company_id = $this->db->insert_id(); 
									}
								}


								//medicine company end
								
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_name'=>$post['medicine_name'][$i],
												'type'=>$unit_id,
												'salt'=>$post['salt'][$i],
												'manuf_company'=>$company_id,
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_medicine_entry',$data);
								$medicine_id = $this->db->insert_id(); 
								//echo $this->db->last_query(); exit;
						}

					}
					//medicne type
					if(!empty($post['medicine_type'][$i]))
					{
						$this->db->select('hms_opd_medicine_type.*');
						//$this->db->from('hms_opd_medicine_type');
						$this->db->where('hms_opd_medicine_type.medicine_type',$post['medicine_type'][$i]); 
						$this->db->where('hms_opd_medicine_type.is_deleted!=2'); 
						$this->db->where('hms_opd_medicine_type.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_medicine_type');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_type'=>$post['medicine_type'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_opd_medicine_type',$data);
						}
					}
					//medicine dose
					if(!empty($post['medicine_dose'][$i]))
					{
						$this->db->select('hms_opd_medicine_dosage.*');
						//$this->db->from('hms_opd_medicine_dosage');
						$this->db->where('hms_opd_medicine_dosage.medicine_dosage',$post['medicine_dose'][$i]); 
						$this->db->where('hms_opd_medicine_dosage.is_deleted!=2'); 
						$this->db->where('hms_opd_medicine_dosage.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_medicine_dosage');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'medicine_dosage'=>$post['medicine_dosage'][$i],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_opd_medicine_dosage',$data);
						}
					}

					//medicine duration
					if(!empty($post['medicine_duration'][$i]))
					{
						$this->db->select('hms_opd_medicine_dosage_duration.*'); 
						//$this->db->from('hms_opd_medicine_dosage_duration');
						$this->db->where('hms_opd_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
						$this->db->where('hms_opd_medicine_dosage_duration.is_deleted!=2'); 
						$this->db->where('hms_opd_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_medicine_dosage_duration');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'medicine_dosage_duration'=>$post['medicine_dosage'][$i],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_opd_medicine_dosage_duration',$data);
						}
					}

					//medicine frequency
					if(!empty($post['medicine_frequency'][$i]))
					{
						$this->db->select('hms_opd_medicine_dosage_frequency.*');
						//$this->db->from('hms_opd_medicine_dosage_frequency');
						$this->db->where('hms_opd_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
						$this->db->where('hms_opd_medicine_dosage_frequency.is_deleted!=2'); 
						$this->db->where('hms_opd_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_medicine_dosage_frequency');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_dosage_frequency'=>$post['medicine_frequency'][$i],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_opd_medicine_dosage_frequency',$data);
						}
					}

					//medicine advice
					if(!empty($post['medicine_advice'][$i]))
					{
						$this->db->select('*');
						//$this->db->from('hms_opd_advice');
						$this->db->where('hms_opd_advice.medicine_advice',$post['medicine_advice'][$i]); 
						$this->db->where('hms_opd_advice.is_deleted!=2'); 
						$this->db->where('hms_opd_advice.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_advice');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_advice'=>$post['medicine_advice'][$i],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_opd_advice',$data);
						}
					}
					//medicne id 
					//echo $data_id; exit;
					if(!empty($post['medicine_id'][$i]))
					{
						$medicine_id = $post['medicine_id'][$i];
					}
					else
					{
						$medicine_id = $medicine_id;
					}
					$prescription_data = array(
						"prescription_id"=>$data_id,
						'medicine_id'=>$medicine_id,
						"medicine_name"=>$post['medicine_name'][$i],
						"medicine_salt"=>$post['salt'][$i],
						"medicine_brand"=>$post['brand'][$i],
						"medicine_type"=>$post['medicine_type'][$i],
						"medicine_dose"=>$post['medicine_dose'][$i],
						"medicine_duration"=>$post['medicine_duration'][$i],
						"medicine_frequency"=>$post['medicine_frequency'][$i],
						"medicine_advice"=>$post['medicine_advice'][$i]
						);
					$this->db->insert('hms_opd_prescription_patient_pres',$prescription_data);
					//echo $this->db->last_query(); exit;
					$test_data_id = $this->db->insert_id(); 
				
			}
			

			 


			if(!empty($post['next_appointment_date']))
		    {
		       $booking_id = $post['booking_id'];
		       $opd_booking_data = $this->opd_model->get_by_id($booking_id);
		      	
	           if(!empty($opd_booking_data))
	           {
	              $booking_id = $opd_booking_data['id'];
	              $referral_doctor = $opd_booking_data['referral_doctor'];
	              $attended_doctor = $opd_booking_data['attended_doctor'];
	              $patient_id = $opd_booking_data['patient_id'];
	              $simulation_id = $opd_booking_data['simulation_id'];
	              $patient_code = $opd_booking_data['patient_code'];
	              $patient_name = $opd_booking_data['patient_name'];
	              $mobile_no = $opd_booking_data['mobile_no'];
	              $gender = $opd_booking_data['gender'];
	              $age_y = $opd_booking_data['age_y'];
	              $age_m = $opd_booking_data['age_m'];
	              $age_d = $opd_booking_data['age_d'];
	              $address = $opd_booking_data['address'];
	              $city_id = $opd_booking_data['city_id'];
	              $state_id = $opd_booking_data['state_id'];
	              $country_id = $opd_booking_data['country_id']; 
	           }

	            $timestamp = $post['next_appointment_date'];
				$splitTimeStamp = explode(" ",$timestamp);
				$booking_date = $splitTimeStamp[0];
				$booking_time = $splitTimeStamp[1];

	           $appointment_code = generate_unique_id(20);

	           $data_test = array(    
						'type'=>1,
						'appointment_date'=>date('Y-m-d',strtotime($booking_date)),
						'appointment_time'=>date('H:i:s',strtotime($booking_time)), 
						'appointment_code'=>$appointment_code,
						'booking_status'=>'0', 
						'status'=>1,
						'confirm_date'=>'0000-00-00',
						'next_app_date'=>'0000-00-00',
						'source_from'=>0,
						'modified_by'=>0,
						'referral_doctor'=>$referral_doctor,
						'attended_doctor'=>$attended_doctor,
						"branch_id"=> $user_data['parent_id'],
						'booking_date'=>date('Y-m-d H:i:s'),
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'is_deleted'=>0,
						'deleted_by'=>0,
						'deleted_date'=>'0000-00-00',
				  );

		    	$this->db->set('patient_id',$patient_id);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_ambulance_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
	            //echo $this->db->last_query(); exit;
		 	} 


		 	if(!empty($post['data']))
			{    
				$current_date = date('Y-m-d H:i:s');
	            foreach($post['data'] as $key=>$val)
	            {
	            	
 	            	$data = array(
	                               "branch_id"=>$user_data['parent_id'],
	                               "type"=>2,
	                               "booking_id"=>$data_id,
	                               "vitals_id"=>$key,
	                               "vitals_value"=>$val['name'],
	                               
	                              );
	              
	              $this->db->insert('hms_branch_vitals',$data);
	      		  $id = $this->db->insert_id();
	            } 
			}

		 	return $data_id;
	
    }
    

   public function template_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_prescription_template.*');  
		$this->db->where('hms_opd_prescription_template.id',$template_id);  
		$this->db->where('hms_opd_prescription_template.is_deleted',0); 
		$this->db->where('hms_opd_prescription_template.branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_opd_prescription_template');
		$result = $query->row(); 
		return json_encode($result);
		 
    }
  //Other Branch Data for Booking
   public function branch_data($branch_id="")
   {
		$result = array();
		$result['booking_code'] = generate_unique_id(53,$branch_id);
		$result['patient_code'] = generate_unique_id(4,$branch_id);
		return json_encode($result);
	}

	public function get_simulation_data($branch_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_simulation.*');  
		$this->db->where('hms_simulation.branch_id',$branch_id);  
		$this->db->where('hms_simulation.is_deleted',0); 
		$query = $this->db->get('hms_simulation');
		$simulation_list = $query->result();

		$drop = '<select name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
		         <option value="">Select</option>';
		if(!empty($simulation_list))
		{
		    foreach($simulation_list as $simulation)
		    {
		        $drop .= '<option value="'.$simulation->id.'">'.$simulation->simulation.'</option>';
		    }
		}
		$drop .= '</select>';
		
		return $json_data = $drop;
	}

	public function get_referral_doctor_data($branch_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_doctors.*');  
		$this->db->where('hms_doctors.doctor_type IN (0,2)');
		$this->db->where('hms_doctors.branch_id',$branch_id);  
		$this->db->where('hms_doctors.is_deleted',0); 
		$query = $this->db->get('hms_doctors');
		$doctor_list = $query->result(); 
		
		$drop = '<select name="simulation_id" id="simulation_id">
		         <option value="">Select</option>';
		if(!empty($doctor_list))
		{
		    foreach($doctor_list as $doctor)
		    {
		        $drop .= '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
		    }
		}
		$drop .= '</select>';
		
		return $json_data = $drop;
	}
	public function get_specialization_data($branch_id="")
	{
		
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_specialization.*');  
		$this->db->where('hms_specialization.branch_id',$branch_id);  
		$this->db->where('hms_specialization.is_deleted',0); 
		$query = $this->db->get('hms_specialization');
		$specialization_list = $query->result(); 
		$drop = '<select name="specialization" class="w-150px" id="specilization_id"  onchange="return get_doctor_specilization(this.value,'.$branch_id.');">
		         <option value="">Select</option>';
		if(!empty($specialization_list))
		{
		    foreach($specialization_list as $specialization)
		    {
		        $drop .= '<option value="'.$specialization->id.'">'.$specialization->specialization.'</option>';
		    }
		}
		$drop .= '</select>';

		if(in_array('44',$users_data['permission']['action'])) {
                      
          $drop .= '<a href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new">New</a>';
           }
		
		return $json_data = $drop;
	}

	public function get_attended_doctor_data($branch_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_doctors.*');  
		$this->db->where('hms_doctors.branch_id',$branch_id);
		$this->db->where('hms_doctors.doctor_type IN (1,2)');   
		$this->db->where('hms_doctors.is_deleted',0); 
		$query = $this->db->get('hms_doctors');
		$doctor_list = $query->result(); 
		
		$drop = '<select name="simulation_id" id="simulation_id">
		         <option value="">Select</option>';
		if(!empty($specialization_list))
		{
		    foreach($doctor_list as $doctor)
		    {
		        $drop .= '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
		    }
		}
		$drop .= '</select>';
		
		return $json_data = $drop;
	}
//Other Branch Data for Booking
	
   public function get_template_test_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_prescription_patient_test_template.*');  
		$this->db->where('hms_opd_prescription_patient_test_template.template_id',$template_id);  
		$query = $this->db->get('hms_opd_prescription_patient_test_template');
		$result = $query->result(); 
		//echo $this->db->last_query(); exit;
		return json_encode($result);
		 
    }

   public function get_template_prescription_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_prescription_patient_pres_template.*');  
		$this->db->where('hms_opd_prescription_patient_pres_template.template_id',$template_id);  
		$query = $this->db->get('hms_opd_prescription_patient_pres_template');
		$result = $query->result(); 
		return json_encode($result);
		 
    }

	public function get_billed_particular_list($booking_id="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('particular,particulars,amount,quantity');   
		if(!empty($booking_id))
		{
          $this->db->where('booking_id',$booking_id); 
		}    
		  
		$query = $this->db->get('hms_opd_booking_to_particulars');
		$result = $query->result(); 
		
		$particular_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $particular)
			{
               $particular_list[$i]['particular'] = $particular->particular;
               $particular_list[$i]['particulars'] = $particular->particulars;
               $particular_list[$i]['amount'] = $particular->amount;
               $particular_list[$i]['quantity'] = $particular->quantity;
			$i++;
			}
		}
		return $particular_list; 
    }

    public function particular_list($ids="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_particular.*'); 
		if(!empty($ids))
		{
			$this->db->where('hms_opd_particular.id  IN ('.$ids.')'); 
		}
		$this->db->where('hms_opd_particular.is_deleted',0);  
		$this->db->where('hms_opd_particular.branch_id  IN ('.$users_data['parent_id'].',0)'); 
		$this->db->group_by('hms_opd_particular.id');  
		$query = $this->db->get('hms_opd_particular');
		$result = $query->result(); 
		return $result; 
    }

    
    function get_all_detail_print($ids="",$branch_id='')
    {
    	$result_opd=array(); 
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ambulance_booking.*,hms_ambulance_booking.remark as remarks,hms_ambulance_booking.created_date as mod_date,hms_patient.*,hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_ambulance_booking.charge as consultant_charge,hms_payment_mode.payment_mode,hms_branch_hospital_no.	reciept_prefix,hms_branch_hospital_no.reciept_suffix,(CASE WHEN hms_ambulance_booking.reffered_by=1 THEN ref_hospital.hospital_name ELSE ref_doctor.doctor_name END) 
		as referral_doctor_name, hms_patient_source.source as source_name, hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, (CASE WHEN hms_patient.marital_status=1 THEN 'Married' ELSE 'Single' END ) as marital_status , hms_religion.religion as religion_name, hms_simulation.simulation as f_simulation,
		hms_relation.relation, (CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' ELSE 'Normal' END ) as insurance_type_name,hms_gardian_relation.relation,hms_payment_mode_field_value_acc_section.field_value as card_no,hms_ambulance_location.location_name,hms_ambulance_driver.driver_name,hms_ambulance_vehicle.vehicle_no,SUM(hms_refund_payment.refund_amount) as total_refund,ref_hospital.hospital_code"); 

		$this->db->join('hms_patient','hms_patient.id = hms_ambulance_booking.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		 $this->db->join('hms_ambulance_location','hms_ambulance_location.id = hms_ambulance_booking.location','left');
	     $this->db->join('hms_ambulance_driver','hms_ambulance_driver.id = hms_ambulance_booking. driver_id','left');
	     $this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id = hms_ambulance_booking. vehicle_no','left');
		$this->db->join('hms_doctors as ref_doctor','ref_doctor.id = hms_ambulance_booking. reffered','left');
		$this->db->join('hms_hospital as ref_hospital','ref_hospital.id = hms_ambulance_booking. referral_hospital','left');
		$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_ambulance_booking.payment_mode','left');
			$this->db->join('hms_refund_payment','hms_refund_payment.parent_id = hms_ambulance_booking.id','left');
	

		$this->db->join('hms_payment_mode_field_value_acc_section','hms_payment_mode_field_value_acc_section.parent_id = hms_ambulance_booking.id AND hms_payment_mode_field_value_acc_section.section_id=13 AND hms_payment_mode_field_value_acc_section.type=10','left');


	// added on 06-feb-2018
		$this->db->join('hms_patient_source','hms_patient_source.id = hms_ambulance_booking. source','left');  // source from
		$this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
		$this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
		$this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
		//$this->db->join('hms_disease','hms_disease.id = hms_ambulance_booking.diseases','left'); // Disease name
		$this->db->join('hms_religion','hms_religion.id = hms_patient.religion_id','left'); // Religion name
		$this->db->join('hms_simulation','hms_simulation.id = hms_patient.f_h_simulation','left'); // Simulation name
		$this->db->join('hms_relation',' hms_relation.id = hms_patient.relation_id','left'); // Relation name
		//$this->db->join('hms_insurance_type',' hms_insurance_type.id = hms_ambulance_booking.insurance_type_id','left'); // insurance type
		//$this->db->join('hms_insurance_company',' hms_insurance_company.id = hms_ambulance_booking.ins_company_id','left'); // insurance type
	 //added on 06-feb-2018


		$this->db->join('hms_users','hms_users.id = hms_ambulance_booking.created_by','left');
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		$this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
        $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_ambulance_booking.id AND hms_branch_hospital_no.section_id=1','left');  
		$this->db->where('hms_ambulance_booking.is_deleted','0'); 
		
		if(!empty($branch_id))
		{
			$this->db->where('hms_ambulance_booking.branch_id = "'.$branch_id.'"'); 	
		}
		else
		{
			$this->db->where('hms_ambulance_booking.branch_id = "'.$user_data['parent_id'].'"'); 
		}
		
		$this->db->where('hms_ambulance_booking.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_ambulance['ambulance_list']= $this->db->get()->result();
//echo $this->db->last_query();die;



		return $result_ambulance;
		
    }

    function get_all_detail_print20210528($ids="",$branch_id='')
    {
    	$result_opd=array(); 
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ambulance_booking.*,hms_ambulance_booking.remark as remarks,hms_ambulance_booking.created_date as mod_date,hms_patient.*,hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_ambulance_booking.charge as consultant_charge,hms_payment_mode.payment_mode,hms_branch_hospital_no.	reciept_prefix,hms_branch_hospital_no.reciept_suffix,(CASE WHEN hms_ambulance_booking.reffered_by=1 THEN ref_hospital.hospital_name ELSE ref_doctor.doctor_name END) 
		as referral_doctor_name, hms_patient_source.source as source_name, hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, (CASE WHEN hms_patient.marital_status=1 THEN 'Married' ELSE 'Single' END ) as marital_status , hms_religion.religion as religion_name, hms_simulation.simulation as f_simulation,
		hms_relation.relation, (CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' ELSE 'Normal' END ) as insurance_type_name,hms_gardian_relation.relation,hms_payment_mode_field_value_acc_section.field_value as card_no,hms_ambulance_location.location_name,hms_ambulance_driver.driver_name,hms_ambulance_vehicle.vehicle_no,SUM(hms_refund_payment.refund_amount) as total_refund,ref_hospital.hospital_code"); 

		$this->db->join('hms_patient','hms_patient.id = hms_ambulance_booking.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		 $this->db->join('hms_ambulance_location','hms_ambulance_location.id = hms_ambulance_booking.location','left');
	     $this->db->join('hms_ambulance_driver','hms_ambulance_driver.id = hms_ambulance_booking. driver_id','left');
	     $this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id = hms_ambulance_booking. vehicle_no','left');
		$this->db->join('hms_doctors as ref_doctor','ref_doctor.id = hms_ambulance_booking. reffered','left');
		$this->db->join('hms_hospital as ref_hospital','ref_hospital.id = hms_ambulance_booking. referral_hospital','left');
		$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_ambulance_booking.payment_mode','left');
			$this->db->join('hms_refund_payment','hms_refund_payment.parent_id = hms_ambulance_booking.id','left');
	

		$this->db->join('hms_payment_mode_field_value_acc_section','hms_payment_mode_field_value_acc_section.parent_id = hms_ambulance_booking.id AND hms_payment_mode_field_value_acc_section.section_id=13 AND hms_payment_mode_field_value_acc_section.type=10','left');


	// added on 06-feb-2018
		$this->db->join('hms_patient_source','hms_patient_source.id = hms_ambulance_booking. source','left');  // source from
		$this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
		$this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
		$this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
		//$this->db->join('hms_disease','hms_disease.id = hms_ambulance_booking.diseases','left'); // Disease name
		$this->db->join('hms_religion','hms_religion.id = hms_patient.religion_id','left'); // Religion name
		$this->db->join('hms_simulation','hms_simulation.id = hms_patient.f_h_simulation','left'); // Simulation name
		$this->db->join('hms_relation',' hms_relation.id = hms_patient.relation_id','left'); // Relation name
		

		$this->db->join('hms_users','hms_users.id = hms_ambulance_booking.created_by','left');
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		$this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
        $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_ambulance_booking.id AND hms_branch_hospital_no.section_id=1','left');  
		$this->db->where('hms_ambulance_booking.is_deleted','0'); 
		
		if(!empty($branch_id))
		{
			$this->db->where('hms_ambulance_booking.branch_id = "'.$branch_id.'"'); 	
		}
		else
		{
			$this->db->where('hms_ambulance_booking.branch_id = "'.$user_data['parent_id'].'"'); 
		}
		
		$this->db->where('hms_ambulance_booking.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_ambulance['ambulance_list']= $this->db->get()->result();
//echo $this->db->last_query();die;



		return $result_ambulance;
		
    }

    function template_format($data="",$branch_id=''){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	if(!empty($branch_id))
    	{
    	$this->db->where('hms_print_branch_template.branch_id = "'.$branch_id.'"');
    	}
    	else
    	{
    	$this->db->where('hms_print_branch_template.branch_id = "'.$users_data['parent_id'].'"');
    	}
    	 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	return $query;

    }

    public function diseases_list($branch_id="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_disease.disease','ASC');
		$this->db->where('hms_disease.status',1);
		$this->db->where('hms_disease.is_deleted',0);
		if(!empty($branch_id))
		{
			$this->db->where('hms_disease.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_disease.branch_id',$users_data['parent_id']);	
		}
		  
		$query = $this->db->get('hms_disease');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    public function source_list($branch_id='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_patient_source.source','ASC');
		$this->db->where('hms_patient_source.status',1);
		$this->db->where('hms_patient_source.is_deleted',0);
		if(!empty($branch_id))
		{
			$this->db->where('hms_patient_source.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_patient_source.branch_id',$users_data['parent_id']);
		} 
		  
		$query = $this->db->get('hms_patient_source');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }
    


    function search_ambulance_data()
    {
    	$user_data = $this->session->userdata('auth_users');
		//$this->db->select("hms_ambulance_booking.*,hms_ambulance_vehicle.vehicle_no as vehicle,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.patient_code");
		$this->db->select("hms_ambulance_booking.*,hms_patient.patient_code,hms_patient.patient_name,
			hms_patient.patient_code as patient_reg_no, hms_patient.address, hms_patient.father_husband,src.source as patient_source,hms_ambulance_vehicle.vehicle_no,hms_ambulance_driver.driver_name,hms_ambulance_location.location_name,(select sum(payment.debit) as paid_amount1 from hms_payment as payment where payment.parent_id = hms_ambulance_booking.id AND payment.section_id =13 AND payment.type!=12) as paid_amount1,(select sum(payment.credit)-sum(payment.debit) as balance1 from hms_payment as payment where payment.parent_id = hms_ambulance_booking.id AND payment.section_id =13 AND payment.type!=12) as balance1");
		  $this->db->join('hms_hospital','hms_hospital.id = hms_ambulance_booking.referral_hospital','left');
		  	$this->db->join('hms_doctors','hms_doctors.id = hms_ambulance_booking.reffered','left');
		$this->db->join('hms_patient','hms_patient.id=hms_ambulance_booking.patient_id','inner');
		
		$this->db->join('hms_patient_source as src', 'src.id=hms_ambulance_booking.source', 'Left');
		
		 $this->db->join('hms_ambulance_driver','hms_ambulance_driver.id = hms_ambulance_booking.driver_id','left');
          //$this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id = hms_ambulance_booking.vehicle_no','left');
          // $this->db->join('hms_ambulance_location','hms_ambulance_location.id = hms_ambulance_booking.location','left');
             $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_ambulance_booking.payment_mode','left');
             
		
		$this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id=hms_ambulance_booking.vehicle_no','left');
		$this->db->join('hms_ambulance_location','hms_ambulance_location.id=hms_ambulance_booking.location','left');
		$this->db->where('hms_ambulance_booking.is_deleted','0');
		//$this->db->where('hms_ambulance_booking.type','2'); 
		
//hms_ambulance_vehicle

		$search = $this->session->userdata('ambulance_search');
	//	print_r($search); exit;
		if($user_data['users_role']==4)
		{
			if(isset($search) && !empty($search))
			{
			$this->db->where('hms_ambulance_booking.patient_id = "'.$search['branch_id'].'"');
			}
			else
			{
			$this->db->where('hms_ambulance_booking.patient_id = "'.$user_data['parent_id'].'"');	
			}
		}
		elseif($user_data['users_role']==3)
		{
			$this->db->where('hms_ambulance_booking.reffered = "'.$user_data['parent_id'].'"');
		}
		else
		{
			if(isset($search['branch_id']) && !empty($search['branch_id']))
			{
			$this->db->where('hms_ambulance_booking.branch_id = "'.$search['branch_id'].'"');
			}
			else
			{
			$this->db->where('hms_ambulance_booking.branch_id = "'.$user_data['parent_id'].'"');	
			}
		}

		$this->db->from($this->table); 
		/////// Search query end //////////////
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['source_from']))
			{
				$this->db->where('hms_ambulance_booking.source',$search['source_from']);
			}
			 if(!empty($search['destination']))
			{
				$this->db->where('hms_ambulance_booking.destination',$search['destination']);
			}
            if(!empty($search['start_date']))
			{
				//$start_date = date('Y-m-d',strtotime($search['start_date']));
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_ambulance_booking.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date']));
				
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				
				$this->db->where('hms_ambulance_booking.created_date <= "'.$end_date.'"');
			}

			 if(isset($search['vehicle_type']) && !empty($search['vehicle_type']))
                {
                    $this->db->where('hms_ambulance_vehicle.vehicle_type = "'.$search['vehicle_type'].'"');
                }
               

                if(!empty($search['location']))
			{
				$this->db->where('hms_ambulance_booking.location',$search['location']);
			}
              
			if(!empty($search['amount_from']))
			{
				$this->db->where('hms_ambulance_booking.total_amount >= "'.$search['amount_from'].'"');
			}

			if(!empty($search['amount_to']))
			{
				$this->db->where('hms_ambulance_booking.total_amount <= "'.$search['amount_to'].'"');
			}

			if(!empty($search['paid_amount_from']))
			{
				$this->db->where('hms_ambulance_booking.paid_amount >= "'.$search['paid_amount_from'].'"');
			}

			if(!empty($search['paid_amount_to']))
			{
				$this->db->where('hms_ambulance_booking.paid_amount <= "'.$search['paid_amount_to'].'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}
			if(!empty($search['vehicle_no']))
			{
				$this->db->where('hms_ambulance_vehicle.vehicle_no LIKE "'.$search['vehicle_no'].'%"');
			}
		
			if(!empty($search['driver_name']))
			{
				// $this->db->where('hms_ambulance_driver.driver_name',$search['driver_name']);
				$this->db->where('hms_ambulance_driver.driver_name LIKE "'.$search['driver_name'].'%"');
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}
			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(!empty($search['booking_no']))
			{
				$this->db->where('hms_ambulance_booking.booking_no',$search['booking_no']);
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}
			if(!empty($search['address']))
			{
				$this->db->where('hms_patient.address',$search['address']);
			}
			

		

			if(!empty($search['referral_doctor']))
			{
				$this->db->where('hms_ambulance_booking.reffered',$search['referral_doctor']);
			}
			
				if(!empty($search['referral_hospital']))
			{
				$this->db->where('hms_ambulance_booking.referral_hospital',$search['referral_hospital']);
			}


			
			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}
			if(!empty($search['patient_email']))
			{
				$this->db->where('hms_patient.patient_email LIKE "'.$search['patient_email'].'%"');
			}

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_ambulance_booking.status',$search['status']);
			}
			if(isset($search['booking_status']) && $search['booking_status']!="")
			{
				$this->db->where('hms_ambulance_booking.booking_status',$search['booking_status']);
			}
				if(isset($search['payment_mode']) && $search['payment_mode']!="")
			{
				$this->db->where('hms_ambulance_booking.payment_mode',$search['payment_mode']);
			}
			
			if(isset($search['distance_type']) && $search['distance_type']!="")
			{
				$this->db->where('hms_ambulance_booking.distance_type',$search['distance_type']);
			}
				 if(!empty($search['going_date']))
			{
				$going_date = date('Y-m-d',strtotime($search['going_date'])).' 00:00:00';
				$this->db->where('hms_ambulance_booking.going_date = "'.$going_date.'"');
			}
			


		}
		$emp_ids='';
		if($user_data['emp_id']>0)
		{
			if($user_data['record_access']=='1')
			{
				$emp_ids= $user_data['id'];
			}
		}
		elseif(!empty($get["employee"]) && is_numeric($get['employee']))
        {
            $emp_ids=  $get["employee"];
        }
		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('hms_ambulance_booking.created_by IN ('.$emp_ids.')');
		}
		$this->db->order_by('hms_ambulance_booking.id','desc');
	    $query = $this->db->get(); 
    	//echo $this->db->last_query();
		$data= $query->result();
	
		return $data;
	}



	 public function get_test_vals_old($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('test_name','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('test_name LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('path_test');
	        //$query = $this->db->get('hms_opd_test_name');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->test_name;
	        	}
	        }
	        return $response; 
    	} 
    }

    public function get_medicine_auto_vals_old($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');  
	        $this->db->where('hms_medicine_entry.status','1'); 
	        $this->db->order_by('hms_medicine_entry.medicine_name','ASC');
	        $this->db->where('hms_medicine_entry.is_deleted',0);
	        $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$vals.'%"');
	        $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);  
	        $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
	        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
	        $query = $this->db->get('hms_medicine_entry');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	               $name = $vals->medicine_name.'|'.$vals->medicine_unit.'|'.$vals->salt.'|'.$vals->company_name;
					array_push($data, $name);
	               //$response[] = $vals->medicine;
	        	}

	        	echo json_encode($data);
	        }
	        
	        //return $response; 
    	} 
    }
    
     public function get_test_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('test_name','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('test_name LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('path_test');
	        //$query = $this->db->get('hms_opd_test_name');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	               $name = $vals->test_name.'|'.$vals->id;
					array_push($data, $name);
	               //$response[] = $vals->medicine;
	        	}

	        	echo json_encode($data);
	        }

	        /*if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response['test_id'] = $vals->id;
	               $response['test_name'] = $vals->test_name;
	        	}
	        }
	        return $response; */
    	} 
    }


    public function get_medicine_auto_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');  
	        $this->db->where('hms_medicine_entry.status','1'); 
	        $this->db->order_by('hms_medicine_entry.medicine_name','ASC');
	        $this->db->where('hms_medicine_entry.is_deleted',0);
	        $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$vals.'%"');
	        $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);  
	        $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
	        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
	        $query = $this->db->get('hms_medicine_entry');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	               $name = $vals->medicine_name.'|'.$vals->medicine_unit.'|'.$vals->salt.'|'.$vals->company_name.'|'.$vals->id;
					array_push($data, $name);
	               //$response[] = $vals->medicine;
	        	}

	        	echo json_encode($data);
	        }
	        
	        //return $response; 
    	} 
    }

    public function get_dosage_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_opd_medicine_dosage');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_dosage;
	        	}
	        }
	        return $response; 
    	} 
    }

    public function get_type_vals($vals="")
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
	        $query = $this->db->get('hms_opd_medicine_type');
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

    

    public function get_duration_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage_duration','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage_duration LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_opd_medicine_dosage_duration');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_dosage_duration;
	        	}
	        }
	        return $response; 
    	} 
    }


    public function get_frequency_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage_frequency','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage_frequency LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_opd_medicine_dosage_frequency');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_dosage_frequency;
	        	}
	        }
	        return $response; 
    	} 
    }

    public function get_advice_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_advice','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_advice LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_opd_advice');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_advice;
	        	}
	        }
	        return $response; 
    	} 
    }

    
    public function get_diseases_data($branch_id="")
	{
		
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_disease.*');  
		$this->db->where('hms_disease.branch_id',$branch_id);  
		$this->db->where('hms_disease.is_deleted',0); 
		$query = $this->db->get('hms_disease');
		$disease_list = $query->result(); 
		$drop = '<select name="diseases" class="w-150px" id="disease_id">
		         <option value="">Select</option>';
		if(!empty($disease_list))
		{
		    foreach($disease_list as $diseases)
		    {
		        $drop .= '<option value="'.$diseases->id.'">'.$diseases->disease.'</option>';
		    }
		}
		$drop .= '</select>';

		if(in_array('44',$users_data['permission']['action'])) {
                      
          $drop .= '<a href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new">New</a>';
           }
		
		return $json_data = $drop;
	}

	public function get_package_data($branch_id="")
	{
		
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_disease.*');  
		$this->db->where('hms_disease.branch_id',$branch_id);  
		$this->db->where('hms_disease.is_deleted',0); 
		$query = $this->db->get('hms_disease');
		$disease_list = $query->result(); 
		$drop = '<select name="diseases" class="w-150px" id="disease_id">
		         <option value="">Select</option>';
		if(!empty($disease_list))
		{
		    foreach($disease_list as $diseases)
		    {
		        $drop .= '<option value="'.$diseases->id.'">'.$diseases->disease.'</option>';
		    }
		}
		$drop .= '</select>';

		if(in_array('44',$users_data['permission']['action'])) {
                      
          $drop .= '<a href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new">New</a>';
           }
		
		return $json_data = $drop;
	}

	

	public function get_source_from_data($branch_id="")
	{
		
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_patient_source.*');  
		$this->db->where('hms_patient_source.branch_id',$branch_id);  
		$this->db->where('hms_patient_source.is_deleted',0); 
		$query = $this->db->get('hms_patient_source');
		$source_list = $query->result(); 
		$drop = '<select name="source_from" class="w-150px" id="patient_source_id">
		         <option value="">Select</option>';
		if(!empty($source_list))
		{
		    foreach($source_list as $sources)
		    {
		        $drop .= '<option value="'.$sources->id.'">'.$sources->source.'</option>';
		    }
		}
		$drop .= '</select>';

		if(in_array('44',$users_data['permission']['action'])) {
                      
          $drop .= '<a href="javascript:void(0)" onclick=" return patient_source_add_modal();"  class="btn-new">New</a>';
           }
		
		return $json_data = $drop;
	}

	function sms_template_format($data="")
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_sms_branch_template.*');
    	$this->db->where($data);
    	$this->db->where('hms_sms_branch_template.branch_id = "'.$users_data['parent_id'].'"'); 
    	$this->db->from('hms_sms_branch_template');
    	$query=$this->db->get()->row();
    	//echo $this->db->last_query();
    	return $query;

    }

    function sms_url()
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_branch_sms_config.*');
    	//$this->db->where($data);
    	$this->db->where('hms_branch_sms_config.branch_id = "'.$users_data['parent_id'].'"'); 
    	$this->db->from('hms_branch_sms_config');
    	$query=$this->db->get()->row();
    	//echo $this->db->last_query();
    	return $query;

    }

	function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
	{
    $users_data = $this->session->userdata('auth_users'); 
	$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
	$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

	$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
	$this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
	$this->db->where('hms_payment_mode_field_value_acc_section.type',7);
	$this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
	$this->db->where('hms_payment_mode_field_value_acc_section.section_id',2);
	$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();

	return $query;
	}


	public function get_payment_refund($booking_id='',$branch_id='')
	{
		$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('SUM(hms_refund_payment.refund_amount) as refund_payment');
    	$this->db->where('hms_refund_payment. section_id =2');
    	$this->db->where('hms_refund_payment.branch_id = "'.$branch_id.'"');
    	$this->db->where('hms_refund_payment.parent_id = "'.$booking_id.'"'); 
    	$this->db->from('hms_refund_payment');
    	$query=$this->db->get()->result();
    	return $query;	
	}

	public function get_checkbox_coloumns_old($module_id)
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('clm.id, clm.coloum_id, clm.coloum_name, clm.default_coloum, clm.module, (CASE WHEN br.branch_id > 0 THEN 1 WHEN clm.default_coloum>0 THEN 1 ELSE 0 END) as checked_status');
		$this->db->from('hms_list_table_column as clm');
		$this->db->join('hms_list_table_column_to_branch as br', 'clm.coloum_id = br.coloum_id and br.branch_id = '.$users_data['parent_id'].' and br.module='.$module_id.' ', 'Left');
		//$this->db->where('br.branch_id',$users_data['parent_id']);
		$this->db->where('clm.module',$module_id);
		//$this->db->where('br.brn',$module_id);
		$res=$this->db->get();
		if($res->num_rows()  > 0)
		{
			return $res->result();
		}
		else
		{
			$this->db->select('id, coloum_id, coloum_name, default_coloum, module');
			$this->db->from('hms_list_table_column');
			$this->db->where('module',$module_id);
			$res=$this->db->get();

			if($res->num_rows() > 0)
			{
				return $res->result();
			}
			else
			{
				return "empty";
			}
		}

		
	}
	
	public function get_checkbox_coloumns($module_id)
	{
		$users_data = $this->session->userdata('auth_users');
		//echo "<pre>"; print_r($users_data); exit;
		//[parent_id] => 13
		$this->db->select('hms_list_table_column.*, 
		(CASE WHEN (select count(id)  from hms_list_table_column_to_branch as branch_coloum where branch_coloum.module = '.$module_id.' AND branch_coloum.branch_id = '.$users_data['parent_id'].') > 0 THEN hms_list_table_column_to_branch.id ELSE hms_list_table_column.default_coloum END) as selected_status');
		$this->db->from('hms_list_table_column');
		$this->db->join('hms_list_table_column_to_branch','hms_list_table_column_to_branch.coloum_id=hms_list_table_column.coloum_id AND hms_list_table_column_to_branch.branch_id = '.$users_data['parent_id'].' AND hms_list_table_column_to_branch.module='.$module_id.' ','left');

		$this->db->where('hms_list_table_column.module',$module_id);
		$res=$this->db->get();
		//echo $this->db->last_query();
		if($res->num_rows()  > 0)
		{
			return $res->result();
		}
		else
		{
			$this->db->select('id, coloum_id, coloum_name, default_coloum, module');
			$this->db->from('hms_list_table_column');
			$this->db->where('module',$module_id);
			$res=$this->db->get();

			if($res->num_rows() > 0)
			{
				return $res->result();
			}
			else
			{
				return "empty";
			}
		}

		
	}
    

	// added on 11-feb-2018
	public function delete_existing_branch_list_cols($module_id,$branch_id)
	{

		$this->db->where('module',$module_id);
		$this->db->where('branch_id',$branch_id);		
		$this->db->delete('hms_list_table_column_to_branch');
		
		
	}

	public function insert_new_cols_branch_list_cols($branch_id, $module_id, $col_ids_array)
	{
		foreach ($col_ids_array as $coloumn_id) 
		{
            $data_array['module'] = $module_id;
            $data_array['branch_id'] = $branch_id;
            $data_array['coloum_id'] = $coloumn_id;
            $this->db->insert('hms_list_table_column_to_branch',$data_array);
        }
           //echo $this->db->last_query();
	}

	// added on 11-feb-2018	

	public function update_doctor_status($opd_id="",$status_doc="")
	{
		$doctor_status='';
		 if(isset($status_doc) && $status_doc==0)
		 {
		 	$doctor_status='1';
		 }
		 else
		 {
		 	$doctor_status='0';
		 }
		$this->db->where('hms_ambulance_booking.id',$opd_id);
		$data_update= array('doctor_checked_status'=>$doctor_status);
		$result= $this->db->update('hms_ambulance_booking',$data_update);
		//echo $this->db->last_query();die;
		if(isset($result))
		{
			return $doctor_status;
		}
		//echo $this->db->last_query();die;
		
		
	}

	public function get_validity_date_in_between($doctor_id="",$booking_date="",$patient_id="")
	{
			$users_data = $this->session->userdata('auth_users');
			$validity=0;
			$validity_days=$this->get_opd_validity_days();

			if(!empty($validity_days['days']))
			{
			$validity=$validity_days['days']-1;
			$validate_date =  date('Y-m-d', strtotime($booking_date. ' + '.$validity.' days'));
			}
			else
			{
				$validate_date=$booking_date;
			}
         
			$validatedate  = date('d-m-Y', strtotime($validate_date));
			$this->db->select('hms_ambulance_booking.patient_id,hms_ambulance_booking.attended_doctor,hms_ambulance_booking.booking_date,hms_ambulance_booking.validity_date');
			$this->db->from('hms_ambulance_booking');
			$this->db->where('hms_ambulance_booking.attended_doctor',$doctor_id);
			$this->db->where('hms_ambulance_booking.patient_id',$patient_id);
			$this->db->where('hms_ambulance_booking.type',2);
			$this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);
			$this->db->where('hms_ambulance_booking.booking_date<=',date('Y-m-d',strtotime($booking_date)));
			$this->db->limit('1');
			$this->db->order_by('hms_ambulance_booking.id','DESC');
//echo $this->db->last_query();
			$res= $this->db->get()->result();
		//	echo $this->db->last_query(); die;
			//print_r($res);die;
			if(!empty($res) && count($res)>0)
			{
				
			/*	if(strtotime(date('d-m-Y',strtotime($res[0]->booking_date)))<=strtotime(date('d-m-Y',strtotime($res[0]->validity_date))) && strtotime($booking_date)<=strtotime(date('d-m-Y',strtotime($res[0]->validity_date))) )
				{*/
				if(strtotime(date('Y-m-d',strtotime($res[0]->booking_date)))<=strtotime(date('Y-m-d',strtotime($res[0]->validity_date))) && strtotime(date('Y-m-d',strtotime($booking_date)))<=strtotime(date('Y-m-d',strtotime($res[0]->validity_date))) )
				{
					echo '1';exit;
				}
				else
				{
					
					echo '2';exit;
				}
			}
			else
				{
					
					echo '2';exit;
				}
			
	}

	function get_validate_date($doctor_id="",$booking_date="",$patient_id="")
	{
			$users_data = $this->session->userdata('auth_users');
			$validity=0;
			$validity_days=$this->get_opd_validity_days();

			if(!empty($validity_days['days']))
			{
			$validity=$validity_days['days']-1;
			$validate_date =  date('Y-m-d', strtotime($booking_date. ' + '.$validity.' days'));
			}
			else
			{
			$validate_date=$booking_date;
			}

			$validatedate  = date('d-m-Y', strtotime($validate_date));
			$this->db->select('hms_ambulance_booking.patient_id,hms_ambulance_booking.attended_doctor,hms_ambulance_booking.booking_date,hms_ambulance_booking.validity_date');
			$this->db->from('hms_ambulance_booking');
			$this->db->where('hms_ambulance_booking.attended_doctor',$doctor_id);
			$this->db->where('hms_ambulance_booking.patient_id',$patient_id);
			$this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);
			$this->db->where('hms_ambulance_booking.booking_date<=',date('Y-m-d',strtotime($booking_date)));
			$this->db->where('hms_ambulance_booking.type',2);
			$this->db->limit('1');
			$this->db->order_by('hms_ambulance_booking.id','DESC');

			$res= $this->db->get()->result();
		//	echo $this->db->last_query();die;
			//print_r($res);die;
			if(!empty($res) && count($res)>0)
			{
			 if(strtotime(date('d-m-Y',strtotime($res[0]->booking_date)))<=strtotime(date('d-m-Y',strtotime($res[0]->validity_date))) && strtotime($booking_date)<=strtotime(date('d-m-Y',strtotime($res[0]->validity_date))) )
				{ 

					return date('d-m-Y',strtotime($res[0]->validity_date));
				}
				else
				{

					return date('d-m-Y',strtotime($validate_date));
				}
			}
			else
			{
				return date('d-m-Y',strtotime($validate_date));
			}
			
	}
	
	public function get_prescription_count_for_dental($booking_id='')
    {
    	$users_data = $this->session->userdata('auth_users');
    	$this->db->select('*');  
        $this->db->where('hms_opd_prescription.booking_id',$booking_id);
        $this->db->where('hms_opd_prescription.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_opd_prescription');
        //$result = $query->result(); 
        return $query->num_rows();

    }
    
     public function get_prescription_count_for_pedic($booking_id='')
    {
    	$users_data = $this->session->userdata('auth_users');
    	$this->db->select('*');  
        $this->db->where('hms_pediatrician_prescription.booking_id',$booking_id);
        $this->db->where('hms_pediatrician_prescription.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_prescription');
        //$result = $query->result(); 
        return $query->num_rows();

    }
    
    
    /* opd all data */
	public function save_all_opd_data($patient_all_data = array())
	{

		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$branch_id = $user_data['parent_id'];
		$consult_charge='';
		$net_amount='';
		$payment_mode_id='';
		$simulation_id='';
		$booking_type='';
		//print_r($patient_all_data);die;
		if(!empty($patient_all_data))
		{
			foreach($patient_all_data as $patient_data)
			{

				$gender='';
				if(strtolower($patient_data['gender'])=='male')
				{
					$gender = 1;
				}
				elseif(strtolower($patient_data['gender'])=='female')
				{
					$gender = 0;
				}
				elseif(strtolower($patient_data['gender'])=='others')
				{
					$gender =2;
				}
				else
				{
					$gender = $patient_data['gender'];
				}

				/* check simulation */
				//echo 'dsd';die;
				$get_simulation= $this->get_simulation($patient_data['simulation_id']);
				//print_r($get_simulation);die;
				if(!empty($get_simulation))
				{
					$simulation_id= $get_simulation[0]->id;
				}
				else
				{
					$data_simulation = array( 
								'branch_id'=>$user_data['parent_id'],
								'gender'=>$gender,
								'simulation'=>$patient_data['simulation_id'],
								'status'=>1,
								'gender'=>$patient_data['gender'],
								'ip_address'=>$_SERVER['REMOTE_ADDR']
								);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_simulation',$data_simulation);  
							$simulation_id=$this->db->insert_id();
				}


				/* check simulation */
					

				$data_patient = array(
								'simulation_id'=>$simulation_id,
								'branch_id'=>$branch_id,
								'patient_name'=>$patient_data['patient_name'],
								'mobile_no'=>$patient_data['mobile_no'],
								'gender'=>$gender,
								'age_y'=>$patient_data['age_y'],
								'age_m'=>$patient_data['age_m'],
								'adhar_no'=>$patient_data['adhar_no'],
								'age_d'=>$patient_data['age_d'],
								'address'=>$patient_data['address'],
								'status'=>1,
								'ip_address'=>$_SERVER['REMOTE_ADDR'], 
						    	);
		
				//print_r($data_patient);die;

				/* check doctor exits in specialiation or not */

				
				
				$speci_branch= $this->get_specialization_branch($patient_data['specialization_id']);
				
				if(!empty($speci_branch))
				{
					$specialization_id=$speci_branch[0]->id;
				}
				else
				{
					$speci_admin= $this->get_specialization($patient_data['specialization_id']);
					$specialization_id=$speci_admin[0]->id;
				}
				
				$specialization_id=$speci[0]->id;

				if($specialization_id==497)
				{
					$branch_id=0;
					$speci_new= $this->get_specialization_new($patient_data['specialization_id'],$branch_id);
					$specialization_id=$speci_new[0]->id;
				}
				elseif($specialization_id==640)
				{
					$branch_id=0;
					$speci_new= $this->get_specialization_new($patient_data['specialization_id'],$branch_id);
					$specialization_id=$speci_new[0]->id;
				}
				elseif($specialization_id==675)
				{
					$branch_id=0;
					$speci_new= $this->get_specialization_new($patient_data['specialization_id'],$branch_id);
					$specialization_id=$speci_new[0]->id;
				}
				elseif($specialization_id==981)
				{
					$branch_id=0;
					$speci_new= $this->get_specialization_new($patient_data['specialization_id'],$branch_id);
					$specialization_id=$speci_new[0]->id;
				}
				else
				{
					$branch_id=$user_data['parent_id'];
					$speci_new= $this->get_specialization_new($patient_data['specialization_id'],$branch_id);
					$specialization_id=$speci_new[0]->id;
				}

				if(!empty($speci_new))
				{


					//echo '<pre>fff'; print_r($patient_data);die;
					$speci_doc= $this->get_specialization_according_toname($patient_data['doctor_name'],$specialization_id);
					if(!empty($speci_doc))
					{


						//echo '<pre>aa'; print_r($patient_data);die;
						$specialization_id=$speci_doc[0]->specilization_id;
						$doctor_id=$speci_doc[0]->id;


					}
					else
					{

						//echo '<pre>fff'; print_r($patient_data);die;
						$data = array( 
							"doctor_name"=>$patient_data['doctor_name'],
							"consultant_charge"=>$patient_data['total_amount'],
							"specilization_id"=>$specialization_id,
							"doctor_pay_type"=>0,
							"status"=>1,
							"doctor_type"=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'], 
						 ); 
						$reg_no = generate_unique_id(3);
						$this->db->set('doctor_code',$reg_no);
						$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
						$this->db->set('created_by',$user_data['id']);
						$this->db->set('branch_id',$user_data['parent_id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_doctors',$data); 
						$doctor_id=$this->db->insert_id();
						$specialization_id=$specialization_id;
						$consult_charge=$patient_data['total_amount'];
					}
				}
				else
				{
					//echo '<pre>'; print_r($patient_data);die;
					$data_spec = array( 
									'branch_id'=>$branch_id,
									'specialization'=>$patient_data['specialization_id'],
									'status'=>1,
									'ip_address'=>$_SERVER['REMOTE_ADDR']
								);
						$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_specialization',$data_spec);  
						$specialization_id=$this->db->insert_id();
						$data_doc = array( 
							"doctor_name"=>$patient_data['doctor_name'],
							"specilization_id"=>$specialization_id,
							"consultant_charge"=>$patient_data['total_amount'],
							"doctor_pay_type"=>0,
							"status"=>1,
							"doctor_type"=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'], 
						 ); 
						$reg_no = generate_unique_id(3);
						$this->db->set('doctor_code',$reg_no);
						$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
						$this->db->set('created_by',$user_data['id']);
						$this->db->set('branch_id',$user_data['parent_id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_doctors',$data_doc); 
						$doctor_id=$this->db->insert_id();

						$consult_charge=$patient_data['total_amount'];
					/* insert specialization after that doctor added;*/

				}
				/* check doctor exits in specialiation or not */

				/* INSERT PAYMENT MODE */

				$get_payment_mode= $this->get_payment_mode($patient_data['payment_mode']);
				if(!empty($get_payment_mode))
				{
					$payment_mode_id= $get_payment_mode[0]->id;
				}
				else
				{
					$data = array( 
							'branch_id'=>$user_data['parent_id'],
							'payment_mode'=>$patient_data['payment_mode'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_payment_mode',$data); 
					        $payment_mode_id= $this->db->insert_id();		
				}

				/* INSERT PAYMENT MODE */
			/* checking for specified specialization */
			if($specialization_id==497)
			{
				$booking_type=1;
			}
			elseif($specialization_id==640)
			{
				$booking_type=2;
			}
			elseif($specialization_id==675)
			{
				$booking_type=3;
			}
			elseif($specialization_id==981)
			{
				$booking_type=4;
			}
			else
			{
				$booking_type=0;
			}

			//echo $specialization_id;die;
			/* checking for specified specialization */
		   $net_amount= $patient_data['total_amount']-$patient_data['discount'];
			$data_test = array(    
				'branch_id'=>$branch_id,
				'specialization_id'=>$specialization_id,
				'type'=>2,
				'total_amount'=>$patient_data['total_amount'],
				'attended_doctor'=>$doctor_id,
				'charge'=>$consult_charge,
				'discount'=>$patient_data['discount'],
				'net_amount'=>$net_amount,
				'booking_date'=>date('Y-m-d',strtotime($patient_data['created_date'])),
				'booking_status'=>1,
				'remarks'=>$patient_data['remarks'],
				'payment_mode'=>$payment_mode_id,
				'booking_type'=>$booking_type,  // eye module add
			);
			$data_pay_test = array( 
						'pay_now'=>0,
						'paid_amount'=>$patient_data['paid_amount'],
						);
			$data_testr = array_merge($data_test, $data_pay_test);
			

			/* check patient reg no already exits */

				
			$patient_reg_no= $this->check_patient_reg_no($patient_data['patient_code']);
			if(!empty($patient_reg_no) && $patient_reg_no[0]->id>0)
			{
                                 
					/* blank */
					$patient_id= $patient_reg_no[0]->id;
			}
			else
			{


			$patient_code = generate_unique_id(4);
		    	$this->db->set('patient_code',$patient_data['patient_code']);
		    	$this->db->set('created_by',$user_data['id']);
			    $this->db->set('created_date',date('Y-m-d',strtotime($patient_data['created_date'])));
		    	$this->db->insert('hms_patient',$data_patient); 
			//echo $this->db->last_query();die; 
		    	$patient_id = $this->db->insert_id();
			}
			/* check patient reg no already exits */
           
			//echo '<pre>'; print_r($patient_data);die;

			/* check when opd no already register */
			$booking_code= $this->check_booking_code_exits($patient_data['booking_code']);
			if(!empty($booking_code))
			{
				$booking_id = $booking_code[0]->id; 
			}
			else
			{
				$bookingcode = $patient_data['booking_code'];
				$this->db->set('confirm_date',date('Y-m-d H:i:s'));
				$this->db->set('booking_no',$bookingcode);
				$this->db->set('patient_id',$patient_id);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d',strtotime($patient_data['created_date'])));
				$this->db->insert('hms_ambulance_booking',$data_testr);  
				$booking_id = $this->db->insert_id(); 
			}
			/* check when opd no already register */
		    
			//echo $this->db->last_query();die;
			
            

            $payment_data = array(
                               'parent_id'=>$booking_id,
                               'branch_id'=>$branch_id,
                               'section_id'=>'2',
                               'hospital_id'=>'',
                               'doctor_id'=>'',
                               'patient_id'=>$patient_id,
                               'total_amount'=>$patient_data['total_amount'],
                               'discount_amount'=>$patient_data['discount'],
                               'net_amount'=>$net_amount,
                               'credit'=>$net_amount,
                               'debit'=>$patient_data['paid_amount'],
                               'balance'=>($net_amount-$patient_data['paid_amount'])+1,
                               'pay_mode'=>$payment_mode_id,
                               //'transection_no'=>$transaction_no,
								//'bank_name'=>$bank_name,
								//'cheque_no'=>$post['cheque_no'],
								//'cheque_date'=>date('Y-m-d',strtotime($post['cheque_date'])),
								'paid_amount'=>$patient_data['paid_amount'],
                               	'created_by'=>$user_data['id'],
                               'created_date'=>date('Y-m-d H:i:s',strtotime($patient_data['created_date'])),
            	             );

            
            $this->db->insert('hms_payment',$payment_data);
           // echo $this->db->last_query();die;
            $payment_id = $this->db->insert_id();

            /* genereate receipt number */
				if(in_array('218',$user_data['permission']['section']))
				{

						if($patient_data['paid_amount']>0)
						{
							$hospital_receipt_no= check_hospital_receipt_no();
							$data_receipt_data= array('branch_id'=>$user_data['parent_id'],
							'section_id'=>1,
							'parent_id'=>$booking_id,
							'payment_id'=>$payment_id,
							'reciept_prefix'=>$hospital_receipt_no['prefix'],
							'reciept_suffix'=>$hospital_receipt_no['suffix'],
							'created_by'=>$user_data['id'],
							'created_date'=>date('Y-m-d',strtotime($patient_data['created_date']))
							);
							$this->db->insert('hms_branch_hospital_no',$data_receipt_data);
						}
				}
	       
            //echo $this->db->last_query(); exit;

         /*add sales banlk detail*/
			// $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>7,'section_id'=>4));
			// $this->db->delete('hms_payment_mode_field_value_acc_section');
			// if(!empty($post['field_name']))
			// {
			// 	$post_field_value_name= $post['field_name'];
			// 	$counter_name= count($post_field_value_name); 
			// 	for($i=0;$i<$counter_name;$i++) 
			// 		{
			// 			$data_field_value= array(
			// 			'field_value'=>$post['field_name'][$i],
			// 			'field_id'=>$post['field_id'][$i],
			// 			'type'=>7,
			// 			'section_id'=>4,
			// 			'p_mode_id'=>$post['payment_mode'],
			// 			'branch_id'=>$user_data['parent_id'],
			// 			'parent_id'=>$booking_id,
			// 			'ip_address'=>$_SERVER['REMOTE_ADDR']
			// 			);
			// 	$this->db->set('created_by',$user_data['id']);
			// 	$this->db->set('created_date',date('Y-m-d H:i:s'));
			// 	$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

			// 	}
			// }
		 /*add sales banlk detail*/ 

            //medicine kit
    //         if(!empty($post['package_id']))
    //         {
				// $medicine_kit_data = array(
    //                            'parent_id'=>$booking_id,
    //                            'branch_id'=>$branch_id,
    //                            'section_id'=>'1',
    //                            'kit_id'=>$post['package_id'],
    //                            'patient_id'=>$patient_id,
    //                            'credit'=>1,
    //                            'debit'=>0,
    //                            'created_by'=>$user_data['id'],
    //                            'created_date'=>date('Y-m-d H:i:s'),
    //         	             );
    //         	$this->db->insert('hms_medicine_kit_stock',$medicine_kit_data); 
    //         }


   //       	if(!empty($post['data']))
			// {    
			// 	$current_date = date('Y-m-d H:i:s');
	  //           foreach($post['data'] as $key=>$val)
	  //           {
	            	
 	 //            	$data = array(
	  //                              "branch_id"=>$user_data['parent_id'],
	  //                              "type"=>1,
	  //                              "booking_id"=>$booking_id,
	  //                              "vitals_id"=>$key,
	  //                              "vitals_value"=>$val['name'],
	                               
	  //                             );
	              
	  //             $this->db->insert('hms_branch_vitals',$data);
	  //     		  $id = $this->db->insert_id();
	  //           } 
			// }
             
		


			}
		}

			
    }

    /* check patient code already exits or not */

    public function check_patient_reg_no($patient_code="")
    {

    	$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_patient.id');  
		$this->db->where('hms_patient.branch_id',$users_data['parent_id']);  
		$this->db->where('hms_patient.patient_code',$patient_code); 
		$this->db->where('hms_patient.is_deleted',0); 
		$query = $this->db->get('hms_patient')->result();
		return $query;
    }

    /* check patient code already exits or not */

    public function get_payment_mode($pay_text="")
    {

    	$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_payment_mode.id');  
		$this->db->where('hms_payment_mode.branch_id',$users_data['parent_id']);  
		$this->db->where('LOWER(hms_payment_mode.payment_mode)',strtolower($pay_text)); 
		$this->db->where('hms_payment_mode.is_deleted',0); 
		$query = $this->db->get('hms_payment_mode')->result();
		//echo $this->db->last_query();die;
		return $query;
    }
    public function get_specialization_according_toname($doctor_name="",$speci_name="")
	{
		
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_doctors.*');  
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$this->db->where('hms_doctors.doctor_name',$doctor_name); 
		$this->db->where('hms_doctors.specilization_id',$speci_name);  
		$this->db->where('hms_doctors.is_deleted',0); 
		$query = $this->db->get('hms_doctors')->result();
		//echo $this->db->last_query();die;
		return $query;
	}

	public function get_specialization($specilization="")
	{

		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_specialization.*');  

		$this->db->where('hms_specialization.branch_id IN(0)');  
		$this->db->where('LOWER(hms_specialization.specialization)',strtolower($specilization)); 
		$this->db->where('hms_specialization.is_deleted',0); 
		$query = $this->db->get('hms_specialization')->result();
		//echo $this->db->last_query();die;
		return $query;
	}

	public function get_specialization_branch($specilization="")
	{

		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_specialization.*');  

		$this->db->where('hms_specialization.branch_id',$users_data['parent_id']);  
		$this->db->where('LOWER(hms_specialization.specialization)',strtolower($specilization)); 
		$this->db->where('hms_specialization.is_deleted',0); 
		$query = $this->db->get('hms_specialization')->result();
		//echo $this->db->last_query();die;
		return $query;
	}
	public function get_specialization_new($specilization="",$branch_id="")
	{

		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_specialization.*');  
		$this->db->where('hms_specialization.branch_id',$branch_id);  
		$this->db->where('LOWER(hms_specialization.specialization)',strtolower($specilization)); 
		$this->db->where('hms_specialization.is_deleted',0); 
		$query = $this->db->get('hms_specialization')->result();
		//echo $this->db->last_query();die;
		return $query;
	}

	public function get_simulation($simulation="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_simulation.*');  
		$this->db->where('hms_simulation.branch_id',$users_data['parent_id']); 
		$this->db->where('LOWER(hms_simulation.simulation)',strtolower($simulation)); 
		$this->db->where('hms_simulation.is_deleted',0); 
		$query = $this->db->get('hms_simulation')->result();
		return $query;
	}

	/* check booking code exits */
	public function check_booking_code_exits($booking_code="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_ambulance_booking.*');  
		$this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']); 
		$this->db->where('hms_ambulance_booking.booking_code',$booking_code); 
		$this->db->where('hms_ambulance_booking.is_deleted',0); 
		$query = $this->db->get('hms_ambulance_booking')->result();
		return $query;
	}
    
   // op 23/07/2019 

	 public function get_patient_token_details_for_type_specialization($specialization_id='',$booking_date='',$type='')
    {
    	//echo $type;die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient_to_token.*"); 
	   $this->db->from('hms_patient_to_token');
	   $this->db->where('hms_patient_to_token.specialization_id',$specialization_id);
	   $this->db->where('hms_patient_to_token.booking_date',$booking_date);
	   $this->db->where('hms_patient_to_token.type',$type);
	   $this->db->where('hms_patient_to_token.branch_id',$user_data['parent_id']);
	   $this->db->order_by("id", "DESC");
       $this->db->limit(1, 0);
	   $query = $this->db->get(); 
	   return $query->row_array();

    }

    public function default_doctor_setting()
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_default_doct_setting.specialize_id, hms_default_doct_setting.doctor_id"); 
	   $this->db->from('hms_default_doct_setting');
	   $this->db->where('hms_default_doct_setting.branch_id',$user_data['parent_id']);
	   $query = $this->db->get(); 
	   return $query->row_array();
    }


    /* 4-11-2019 */
     public function vehicle_list($branch_id='',$ven_id='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		//$this->db->order_by('hms_ambulance_vehicle.source','ASC');
		$this->db->where('hms_ambulance_vehicle.status',1);
		
		if(!empty($branch_id))
		{
			$this->db->where('hms_ambulance_vehicle.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_ambulance_vehicle.branch_id',$users_data['parent_id']);
		} 
	    if(!empty($ven_id))
		{
			$this->db->where('hms_ambulance_vehicle.vendor_id',$ven_id);
		} 
		
		$this->db->where('hms_ambulance_vehicle.is_deleted',0);
		  
		$query = $this->db->get('hms_ambulance_vehicle');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }
     public function driver_list($branch_id='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		//$this->db->order_by('hms_ambulance_vehicle.source','ASC');
		$this->db->where('hms_ambulance_driver.status',1);
	
		if(!empty($branch_id))
		{
			$this->db->where('hms_ambulance_driver.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_ambulance_driver.branch_id',$users_data['parent_id']);
		} 
		  
		$query = $this->db->get('hms_ambulance_driver');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    function get_vehicle_charge($vehicle_type)
    {
    	$this->db->select('local_min_amount');
    	$this->db->where('id',$vehicle_type);
    	$this->db->from('hms_ambulance_vehicle_types');
    	$query=$this->db->get();
    	$result=$query->row();
    	return $result;
    }
    
    
    function get_vendor_charge($vehicle_id)
    {
    	$this->db->select('vendor_id,charge_type,charge');
    	$this->db->where('id',$vehicle_id);
    	$this->db->from('hms_ambulance_vehicle');
    	$query=$this->db->get();
    //	echo $this->db->last_query();die;
    	$result=$query->row();
    	return $result;
    }
    /* 4-11-2019 */
// Please write code above    

function get_distance_charge($vehicle_id)
    {
    	
    	  $column="local_min_distance,local_min_amount,local_per_km_charge,outstation_min_distance,outstation_min_amount,outstation_per_km_charge";
    	
    	$this->db->select($column);

    	$this->db->where('id',$vehicle_id);
    	$this->db->from('hms_ambulance_vehicle_types');
    	$query=$this->db->get();
    	$result=$query->row();
    	//echo $this->db->last_query();die;
    	return $result;
    }    
    
     public function get_booked_particular($booking_id,$branch_id){
    	$this->db->select('hms_ambulance_booking_to_particulars.*');
		$this->db->from('hms_ambulance_booking_to_particulars');
		$this->db->where('hms_ambulance_booking_to_particulars.booking_id',$booking_id);
		$this->db->where('hms_ambulance_booking_to_particulars.branch_id',$branch_id);
		$query=$this->db->get();
    	$result=$query->result();
    	return $result;
    }
    
     public function get_booked_particular_list($booking_id){
    	$this->db->select('hms_ambulance_booking_to_particulars.*');
		$this->db->from('hms_ambulance_booking_to_particulars');
		$this->db->where('hms_ambulance_booking_to_particulars.booking_id',$booking_id);
		$query=$this->db->get();
    	$result=$query->result_array();
    	return $result;
    }
    
    public function get_driver_vehicle_by_id($booking_id='')
    {
        $this->db->select('hms_ambulance_driver.driver_name,hms_ambulance_driver.email, hms_ambulance_driver.mobile_no, hms_ambulance_vehicle.vehicle_no');
		$this->db->from('hms_ambulance_booking');
		$this->db->join('hms_ambulance_driver','hms_ambulance_driver.id = hms_ambulance_booking.driver_id','left');
		$this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.id = hms_ambulance_booking.vehicle_no','left');
		$this->db->where('hms_ambulance_booking.id',$booking_id);
		$query=$this->db->get();
    	$result=$query->row_array();
    	return $result;
    }

     public function vehicle_type_list($branch_id='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->where('hms_ambulance_vehicle_types.status',1);		
		if(!empty($branch_id))
		{
			$this->db->where('hms_ambulance_vehicle_types.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_ambulance_vehicle_types.branch_id',$users_data['parent_id']);
		} 
		
		$this->db->where('hms_ambulance_vehicle_types.is_deleted',0);
		  
		$query = $this->db->get('hms_ambulance_vehicle_types');
		$result = $query->result(); 
		return $result; 
    }
    
      public function lease_vehicle_list($branch_id='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');
		$this->db->where('hms_ambulance_vehicle.status',1);
		
		if(!empty($branch_id))
		{
			$this->db->where('hms_ambulance_vehicle.branch_id',$branch_id);
		} 
		else
		{
			$this->db->where('hms_ambulance_vehicle.branch_id',$users_data['parent_id']);
		} 
	    $this->db->where('hms_ambulance_vehicle.vehicle_type',1);
		$this->db->where('hms_ambulance_vehicle.is_deleted',0);
		  
		$query = $this->db->get('hms_ambulance_vehicle');
		$result = $query->result(); 
		
		return $result; 
    }
    
    /*27-05-2020*/
    public function get_booking_amount($id)
    {
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');
		$this->db->where('hms_ambulance_booking.id',$id);
		$this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ambulance_booking.is_deleted',0);
		$query = $this->db->get('hms_ambulance_booking');
		$result = $query->row(); 
		return $result; 
    }
     public function get_refund_booking_amount($id)
    {
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_refund_payment.created_date as refund_date,hms_refund_payment.refund_amount,hms_payment_mode.payment_mode as pay_mode');
		
		$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_refund_payment.payment_mode','left');
		
		$this->db->where('hms_refund_payment.parent_id',$id);
		$this->db->where('hms_refund_payment.branch_id',$users_data['parent_id']);
			$this->db->order_by("hms_refund_payment.id", "DESC");
		$query = $this->db->get('hms_refund_payment');
		
		//echo 	$this->db->last_query();die;
		$result = $query->result_array(); 
		return $result; 
    }
    /*27-05-2020*/
     public function get_booked_payment_mode($booking_id,$branch_id){
    	$this->db->select('hms_payment.debit as paid_amount,hms_payment.created_date,hms_payment_mode.payment_mode as pay_mode'); //,SUM(hms_payment.paid_amount) as  receive_amount
	$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
		$this->db->where('hms_payment.parent_id',$booking_id);
		$this->db->where('hms_payment.section_id',13);
		$this->db->where('hms_payment.type!=12'); //10
		$this->db->where('hms_payment.branch_id',$branch_id);
			$this->db->from('hms_payment');
			$this->db->order_by("hms_payment.id", "DESC");
		$query=$this->db->get();
	//echo 	$this->db->last_query();die;
    	$result=$query->result_array();
    	
    	return $result;
    }
    
} 
?>