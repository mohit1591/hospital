<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_booking_model extends CI_Model {

	var $table = 'hms_dialysis_booking';
	var $column = array(
		'hms_dialysis_booking.id',
		'hms_dialysis_booking.ipd_id',
		'hms_patient.patient_name',
		'hms_patient.mobile_no',
		'hms_patient.patient_code',
		'hms_patient.gender',
		'hms_patient.address',
		'hms_patient.father_husband',
		'hms_dialysis_booking.dialysis_room_no',
		'hms_dialysis_booking.dialysis_name',
		'hms_dialysis_booking.dialysis_date',
		'hms_dialysis_booking.dialysis_time',
		'hms_dialysis_booking.referred_by',
		'hms_dialysis_room_category.room_category',
		'hms_dialysis_rooms.room_no',
		'hms_dialysis_room_to_bad.bad_no',
		
		'hms_dialysis_booking.created_date',
		'hms_dialysis_booking.modified_date',
		'hms_dialysis_booking.created_by'
		);
	
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('dialysis_booking_serach');
        $users_data = $this->session->userdata('auth_users');
		

		$this->db->select("hms_dialysis_booking.*,hms_doctors.doctor_name,hms_dialysis_rooms.room_no,hms_dialysis_room_to_bad.bad_no,hms_dialysis_room_category.room_category as room_type, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code,hms_patient.mobile_no,(CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband,hms_ot_room.room_no as ot_room,sim.simulation as father_husband_simulation,hms_dialysis_pacakge.amount as pack_amount,hms_dialysis_management.name as dialysiss_name,

			(CASE WHEN hms_dialysis_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_dialysis_booking.   referral_doctor=0 THEN concat('Other ',hms_dialysis_booking.ref_by_other) ELSE concat('Dr. ',docs.doctor_name) END) END) as doctor_hospital_name"); 
		$this->db->from($this->table); 
        $this->db->join('hms_dialysis_pacakge','hms_dialysis_pacakge.id=hms_dialysis_booking.package_id','left');
		$this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_booking.dialysis_name','left');
		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_booking.patient_id','left');
        $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.doctor_id','left');
        $this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id=hms_dialysis_booking.room_no_id','left');
		$this->db->join('hms_dialysis_room_to_bad','hms_dialysis_room_to_bad.id=hms_dialysis_booking.bad_id','left');
		$this->db->join('hms_dialysis_room_category','hms_dialysis_room_category.id=hms_dialysis_booking.room_type_id','left');
		$this->db->join('hms_ot_room','hms_ot_room.id=hms_dialysis_booking.dialysis_room_no','left');
        $this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');

		$this->db->join('hms_hospital','hms_hospital.id = hms_dialysis_booking.referral_hospital','left');
        $this->db->join('hms_doctors as docs','docs.id = hms_dialysis_booking.referral_doctor','left');
        $this->db->where('hms_dialysis_booking.is_deleted','0');
        
		$this->db->where('hms_dialysis_booking.branch_id = "'.$users_data['parent_id'].'"');	
		

		$i = 0;

		if(isset($search) && !empty($search))
		{

		if(isset($search['start_date']) && !empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date']));
		$this->db->where('hms_dialysis_booking.dialysis_date >= "'.$start_date.'"');
		}

		if(isset($search['end_date']) &&  !empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date']));
		$this->db->where('hms_dialysis_booking.dialysis_date <= "'.$end_date.'"');
		}
         
         
			if(isset($search['dialysis_no']) &&  !empty($search['dialysis_no']))
			{
			$this->db->where('hms_dialysis_booking.booking_code = "'.$search['dialysis_no'].'"');
			}
			if(isset($search['patient_code']) &&  !empty($search['patient_code']))
			{
			$this->db->where('hms_patient.patient_code = "'.$search['patient_code'].'"');
			}
			if(!empty($search['insurance_type']))
	          {
	            $this->db->where('hms_patient.insurance_type',$search['insurance_type']);
	          }

	          if(!empty($search['insurance_type_id']))
	          {
	            $this->db->where('hms_patient.insurance_type_id',$search['insurance_type_id']);
	          }

	          if(!empty($search['ins_company_id']))
	          {
	            $this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
	          }
			if(isset($search['patient_name']) &&  !empty($search['patient_name']))
			{
			$this->db->where('hms_patient.patient_name = "'.$search['patient_name'].'"');
			}
			if(isset($search['adhar_no']) &&  !empty($search['adhar_no']))
			{
			$this->db->where('hms_patient.adhar_no = "'.$search['adhar_no'].'"');
			}
			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
			$this->db->where('hms_patient.mobile_no = "'.$search['mobile_no'].'"');
			}

			if(isset($search['dialysis_time']) &&  !empty($search['dialysis_time']))
			{
			$this->db->where('hms_dialysis_booking.dialysis_time = "'.$search['dialysis_time'].'"');
			}
			if(isset($search['dialysis_date']) &&  !empty($search['dialysis_date']))
			{
			$this->db->where('hms_dialysis_booking.dialysis_date = "'.date('Y-m-d',strtotime($search['dialysis_date'])).'"');
			}	
        
		

		}
		
		$emp_ids='';
			if($users_data['emp_id']>0)
			{
				if($users_data['record_access']=='1')
				{
					$emp_ids= $users_data['id'];
				}
			}
			elseif(!empty($get["employee"]) && is_numeric($get['employee']))
			{
				$emp_ids=  $get["employee"];
			}


			if(isset($emp_ids) && !empty($emp_ids))
			{ 
			  $this->db->where('hms_dialysis_booking.created_by IN ('.$emp_ids.')');
			}
	
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

	public function search_report_data()
	{
		$search = $this->session->userdata('dialysis_booking_serach');
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_dialysis_booking.*,hms_doctors.doctor_name,hms_dialysis_rooms.room_no,hms_dialysis_room_to_bad.bad_no,hms_dialysis_room_category.room_category as room_type, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code,hms_patient.mobile_no,(CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband,hms_ot_room.room_no as ot_room,sim.simulation as father_husband_simulation,hms_dialysis_pacakge.amount as pack_amount,hms_dialysis_management.name as dialysiss_name,

			(CASE WHEN hms_dialysis_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_dialysis_booking.   referral_doctor=0 THEN concat('Other ',hms_dialysis_booking.ref_by_other) ELSE concat('Dr. ',docs.doctor_name) END) END) as doctor_hospital_name"); 
		$this->db->from($this->table); 
        $this->db->join('hms_dialysis_pacakge','hms_dialysis_pacakge.id=hms_dialysis_booking.package_id','left');
		$this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_booking.dialysis_name','left');
		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_booking.patient_id','left');
        $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.doctor_id','left');
        
        $this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id=hms_dialysis_booking.room_no_id','left');
		$this->db->join('hms_dialysis_room_to_bad','hms_dialysis_room_to_bad.id=hms_dialysis_booking.bad_id','left');
		$this->db->join('hms_dialysis_room_category','hms_dialysis_room_category.id=hms_dialysis_booking.room_type_id','left');
		$this->db->join('hms_ot_room','hms_ot_room.id=hms_dialysis_booking.dialysis_room_no','left');
        $this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');

		$this->db->join('hms_hospital','hms_hospital.id = hms_dialysis_booking.referral_hospital','left');
        $this->db->join('hms_doctors as docs','docs.id = hms_dialysis_booking.referral_doctor','left');
        $this->db->where('hms_dialysis_booking.is_deleted','0');
        $this->db->where('hms_dialysis_booking.branch_id = "'.$users_data['parent_id'].'"');	
		
		$i = 0;

		if(isset($search) && !empty($search))
		{
		    
			if(isset($search['dialysis_no']) &&  !empty($search['dialysis_no']))
			{
			$this->db->where('hms_dialysis_booking.booking_code = "'.$search['dialysis_no'].'"');
			}

				if(isset($search['start_date']) && !empty($search['start_date']))
				{
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_dialysis_booking.dialysis_date >= "'.$start_date.'"');
				}

				if(isset($search['end_date']) &&  !empty($search['end_date']))
				{
				$end_date = date('Y-m-d',strtotime($search['end_date']));
				$this->db->where('hms_dialysis_booking.dialysis_date <= "'.$end_date.'"');
				}

				if(!empty($search['insurance_type']))
		          {
		            $this->db->where('hms_patient.insurance_type',$search['insurance_type']);
		          }

		          if(!empty($search['insurance_type_id']))
		          {
		            $this->db->where('hms_patient.insurance_type_id',$search['insurance_type_id']);
		          }

		          if(!empty($search['ins_company_id']))
		          {
		            $this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
		          }
		}
		
		$emp_ids='';
			if($users_data['emp_id']>0)
			{
				if($users_data['record_access']=='1')
				{
					$emp_ids= $users_data['id'];
				}
			}
			elseif(!empty($get["employee"]) && is_numeric($get['employee']))
			{
				$emp_ids=  $get["employee"];
			}


			if(isset($emp_ids) && !empty($emp_ids))
			{ 
			  $this->db->where('hms_dialysis_booking.created_by IN ('.$emp_ids.')');
			}

			$result=$this->db->get()->result();
			return $result;
	
		
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
	    $search = $this->session->userdata('dialysis_booking_serach');
        $users_data = $this->session->userdata('auth_users');
		$this->db->from($this->table);
		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_booking.patient_id','left');
		if(isset($search) && !empty($search))
		{

		if(isset($search['start_date']) && !empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date']));
		$this->db->where('hms_dialysis_booking.dialysis_date >= "'.$start_date.'"');
		}

		if(isset($search['end_date']) &&  !empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date']));
		$this->db->where('hms_dialysis_booking.dialysis_date <= "'.$end_date.'"');
		}
         
         
			if(isset($search['dialysis_no']) &&  !empty($search['dialysis_no']))
			{
			$this->db->where('hms_dialysis_booking.booking_code = "'.$search['dialysis_no'].'"');
			}
			if(isset($search['patient_code']) &&  !empty($search['patient_code']))
			{
			$this->db->where('hms_patient.patient_code = "'.$search['patient_code'].'"');
			}
			if(!empty($search['insurance_type']))
	          {
	            $this->db->where('hms_patient.insurance_type',$search['insurance_type']);
	          }

	          if(!empty($search['insurance_type_id']))
	          {
	            $this->db->where('hms_patient.insurance_type_id',$search['insurance_type_id']);
	          }

	          if(!empty($search['ins_company_id']))
	          {
	            $this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
	          }
			if(isset($search['patient_name']) &&  !empty($search['patient_name']))
			{
			$this->db->where('hms_patient.patient_name = "'.$search['patient_name'].'"');
			}
			if(isset($search['adhar_no']) &&  !empty($search['adhar_no']))
			{
			$this->db->where('hms_patient.adhar_no = "'.$search['adhar_no'].'"');
			}
			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
			$this->db->where('hms_patient.mobile_no = "'.$search['mobile_no'].'"');
			}

			if(isset($search['dialysis_time']) &&  !empty($search['dialysis_time']))
			{
			$this->db->where('hms_dialysis_booking.dialysis_time = "'.$search['dialysis_time'].'"');
			}
			if(isset($search['dialysis_date']) &&  !empty($search['dialysis_date']))
			{
			$this->db->where('hms_dialysis_booking.dialysis_date = "'.date('Y-m-d',strtotime($search['dialysis_date'])).'"');
			}	
        
		

		}
		
		$emp_ids='';
			if($users_data['emp_id']>0)
			{
				if($users_data['record_access']=='1')
				{
					$emp_ids= $users_data['id'];
				}
			}
			elseif(!empty($get["employee"]) && is_numeric($get['employee']))
			{
				$emp_ids=  $get["employee"];
			}


			if(isset($emp_ids) && !empty($emp_ids))
			{ 
			  $this->db->where('hms_dialysis_booking.created_by IN ('.$emp_ids.')');
			}
		return $this->db->count_all_results();
	}
    
    /*public function ot_pacakge_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('name','ASC'); 
    	$query = $this->db->get('hms_dialysis_booking');
		return $query->result();
    }*/

	public function get_by_id($id)
	{
		$this->db->select('hms_dialysis_booking.*,hms_dialysis_pacakge.name as package_name,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.adhar_no,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_dialysis_rooms.room_no,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.country_id,hms_patient.state_id,hms_patient.city_id,hms_patient.patient_email,hms_patient.dob');
		$this->db->from('hms_dialysis_booking'); 
		$this->db->where('hms_dialysis_booking.id',$id);
		
		$this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id=hms_dialysis_booking.room_id','left');
		$this->db->join('hms_dialysis_pacakge','hms_dialysis_pacakge.id=hms_dialysis_booking.package_id','left');
		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_booking.patient_id','left');
		$this->db->where('hms_dialysis_booking.is_deleted','0');
		$query = $this->db->get(); 
		$result= $query->row_array();
	     return $result;
	}
	public function get_patient_by_id($id)
	{

		$this->db->select('hms_patient.*');
		$this->db->from('hms_patient'); 
		$this->db->where('hms_patient.id',$id);
		//$this->db->where('hms_medicine_vendors.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//echo "<pre>"; print_r($post); exit;
		$data_patient = array(
								//"patient_code"=>$post['patient_reg_code'],
								"patient_name"=>$post['name'],
								'simulation_id'=>$post['simulation_id'],
								'branch_id'=>$user_data['parent_id'],
								'relation_type'=>$post['relation_type'],
								'relation_name'=>$post['relation_name'],
								'relation_simulation_id'=>$post['relation_simulation_id'],
								'gender'=>$post['gender'],
								"age_y"=>$post['age_y'],
								"age_m"=>$post['age_m'],
								"age_d"=>$post['age_d'],
								"adhar_no"=>$post['adhar_no'],
								"address"=>$post['address'],
								"address2"=>$post['address_second'],
								"address3"=>$post['address_third'],
								'mobile_no'=>$post['mobile_no'],
								'country_id'=>$post['country_id'],
								'state_id'=>$post['state_id'],
								'city_id'=>$post['city_id'],
								'dob'=>date('Y-m-d',strtotime($post['dob']))
								);
		
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(isset($post['pacakage_name'])  && $post['dialysis_type']==2)
			{
				
			    $p_hours= $this->get_package_hours($post['pacakage_name']);
				//$time_n=date('H:i:s',strtotime($post['dialysis_time']));
				$time_n= date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time']));
				$time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
				
				
			}
			 if(isset($post['dialysis_name'])  && $post['dialysis_type']==1)
			{
				
				$p_hours= $this->get_dialysis_hours($post['dialysis_name']);	
				$time_n= date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time']));//date('H:i:s',strtotime($post['dialysis_time']));
				$time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
			}
			
            if(empty($post['referral_hospital']))
            {
               $referral_hospital = 0;
            }
            else
            {
                $referral_hospital = $post['referral_hospital'];
            }

			$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$post['patient_id'],
					'ipd_id'=>$post['ipd_id'],
					'dialysis_name'=>$post['dialysis_name'],
					'dialysis_type'=>$post['dialysis_type'],
					'dialysis_end_time'=>$time,
					'dialysis_room_no'=>$post['dialysis_room'],
					//'package_id'=>$post['pacakage_name'],
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_booking_date'=>date('Y-m-d',strtotime($post['dialysis_booking_date'])),
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time'])),
					'remarks'=>$post['remarks'],
					'referred_by'=>$post['referred_by'],
					'referral_doctor'=>$post['referral_doctor'],
					'referral_hospital'=>$referral_hospital,
					'ref_by_other'=>$post['ref_by_other'],
					
					'no_of_visit'=>$post['no_of_visit'],
                    'no_pf_visit_duration'=>$post['no_pf_visit_duration'],
                    'no_pf_visit_unit'=>$post['no_pf_visit_unit'],
					
					'room_id'=>$post['room_id'],
					'room_no_id'=>$post['room_no_id'],
					'bed_no_id'=>$post['bed_no_id'],
					'advance_payment'=>$post['advance_deposite'],
					'payment_mode'=>$post['payment_mode'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		     //print_r($data);die; dialysis_name  dialysis_room_no

			

			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['patient_id']);
			$this->db->update('hms_patient',$data_patient);


			$this->db->where(array('dialysis_id'=>$post['data_id']));
			$this->db->delete('hms_dialysis_to_doctors');

			/*if($post['dialysis_type']==1)
			{
				$this->db->set('package_id','');
				$this->db->set('dialysis_name',$post['dialysis_name']);
	
			}
			elseif($post['dialysis_type']==2)
			{
				
				$this->db->set('package_id',$post['pacakage_name']);
				$this->db->set('dialysis_name','');
			}*/

            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_dialysis_booking',$data);  
			//echo $this->db->last_query();die;

			//pacakage_name as package id
			if(!empty($post['data_id']) && !empty($post['pacakage_name']) && $post['pacakage_name']!='0.00') 
			{ /*
				
					$this->db->where('ot_id',$post['data_id']);
		            $this->db->where('ipd_id',$post['ipd_id']);
		            $this->db->where('patient_id',$post['patient_id']);
		            $this->db->delete('hms_dialysis_patient_to_charge');
					
					$package_charge = get_ot_package_charge($post['pacakage_name']);
					$amount = $package_charge[0]['amount'];
					//echo "<pre>";print_r($package_charge); exit;
					$ot_charge = array(
						"branch_id"=>$user_data['parent_id'],
						'ipd_id'=>$post['ipd_id'],
						'patient_id'=>$post['patient_id'],
						'dialysis_id'=>$post['data_id'],
						'dialysis_package_id'=>$post['pacakage_name'],
						'type'=>11,
						'quantity'=>1,
						'start_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
						'end_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
						'particular'=>$post['dialysis_name'].' (OT)',
						'price'=>$amount,
						'panel_price'=>$amount,
						'net_price'=>$amount,
						'status'=>1,
						'created_date'=>date('Y-m-d H:i:s')
				);
			$this->db->insert('hms_dialysis_patient_to_charge',$ot_charge);
			*/ }
			 $created_date = date('Y-m-d',strtotime($post['dialysis_date']));
			
			if(!empty($post['data_id'])) 
			{
			    
			    	$this->db->where('dialysis_id',$post['data_id']);
		            $this->db->where('patient_id',$post['patient_id']);
		             $this->db->where('type',3);
		            $this->db->delete('hms_dialysis_patient_to_charge');
			    
			    
                    //panel charges
                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->dialysis_room_type_list(); 
                    $room_charge_type_list = $this->general_model->dialysis_room_charge_type_list();
                    
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_dialysis_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],'',$post['patient_type']);
                        $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];
                       
                      $registration_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'dialysis_id'=>$post['data_id'],
                                'patient_id'=>$post['patient_id'],
                                'start_date'=>date('Y-m-d',strtotime($post['dialysis_date'])).' '.date('H:i:s',strtotime($post['dialysis_time'])),
                                'type'=>3,
                                'room_id'=>$post['room_id'],
                                'group_name'=>'',
                                'group_code'=>'',
                                'particular_code'=>'',
                                'quantity'=>1,
                                'transfer_status'=>1,
                                'particular'=>$charge_type,
                                'price'=>$charges,
                                'panel_price'=>$charges,
                                'net_price'=>$charges,
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_dialysis_patient_to_charge',$registration_patient_charge);
                        
                    } 
                   }
            
			}
			//echo "<pre>"; print_r($post['package_names']); exit;
			if(!empty($post['package_names'])) 
			{
			    
			    $this->db->where('dialysis_id',$post['data_id']);
		            $this->db->where('patient_id',$post['patient_id']);
		             $this->db->where('type',1);
		            $this->db->delete('hms_dialysis_patient_to_charge');
			    
			    foreach($post['package_names'] as $key=>$value)
			    {
			       $p_charge_result =  $this->get_package_charge($key);
				   $p_charge =  $p_charge_result[0]->amount;
				    $doctor_array=array(
				        "branch_id"=>$user_data['parent_id'],
				        'patient_id'=>$post['patient_id'],
				        'dialysis_id'=>$post['data_id'],
					    'package_id'=>$key,
					    'parent_id'=>$post['data_id'],
					    'type'=>1,
					    'particular_id'=>0,
					    'quantity'=>1,
						'start_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
						'end_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
						'particular'=>$value[0],
					    'price'=>$p_charge,
					    'panel_price'=>$p_charge,
					    'net_price'=>$p_charge,
					    'status'=>1,
						'created_date'=>date('Y-m-d H:i:s')
					);
				
				    $this->db->insert('hms_dialysis_patient_to_charge',$doctor_array);
				   // echo $this->db->last_query(); exit;
				
			    }	
					
			}


			$post_doctor= count($post['doctor_names']);
			foreach($post['doctor_names'] as $key=>$value)
			{
				$doctor_array=array('dialysis_id'=>$post['data_id'],'doctor_id'=>$key,'doctor_name'=>$value[0]);
				$this->db->insert('hms_dialysis_to_doctors',$doctor_array);
				
			} 
			$dialysis_book_id=$post['data_id'];

		}
		else
		{    
			   $dialysis_booking_no = generate_unique_id(34);
			   $patient_reg_code=generate_unique_id(4); 
			   $patient_data= $this->get_patient_by_id($post['patient_id']);

			   if(isset($post['pacakage_name'])  && $post['dialysis_type']==2)
				{
					$p_hours= $this->get_package_hours($post['pacakage_name']);
					//print_r($p_hours);die;
				    //$time_n=date('H:i:s',strtotime($post['dialysis_time']));
				    $time_n= date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time']));
				    $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
					
				}
				 if(isset($post['dialysis_name'])  && $post['dialysis_type']==1)
				{
					$p_hours= $this->get_dialysis_hours($post['dialysis_name']);	
					//$time_n=date('H:i:s',strtotime($post['dialysis_time']));
					$time_n= date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time']));
					$time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
				}
				
			if(count($patient_data)>0)
			{
				$this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
				$this->db->where('id',$post['patient_id']);
				$this->db->update('hms_patient',$data_patient);
				$patient_id= $post['patient_id'];
				/*$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$patient_id,
					'booking_code'=>$dialysis_booking_no,
					'dialysis_type'=>$post['dialysis_type'],
					'ipd_id'=>$post['ipd_id'],
					'dialysis_name'=>$post['dialysis_name'],
					//'package_id'=>$post['pacakage_name'],
					//'dialysis_room_no'=>$post['dialysis_room'],
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_end_time'=>$time,
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time'])),
					'dialysis_booking_date'=>date('Y-m-d',strtotime($post['dialysis_booking_date'])),
					'remarks'=>$post['remarks'],
					'referred_by'=>$post['referred_by'],
					'referral_doctor'=>$post['referral_doctor'],
					'referral_hospital'=>$post['referral_hospital'],
					'ref_by_other'=>$post['ref_by_other'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );*/
		
			}
			else
			{

				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->set('patient_code',$patient_reg_code);
				$this->db->insert('hms_patient',$data_patient); 
				$patient_id= $this->db->insert_id();
				//echo $this->db->last_query();die;
				
				$data = array(     
							"users_role"=>4,
							"parent_id"=>$patient_id,
							"username"=>'PAT000'.$patient_id,
							"password"=>md5('PASS'.$patient_id),
							//"email"=>$post['patient_email'], 
							"status"=>'1',
							"ip_address"=>$_SERVER['REMOTE_ADDR'],
							"created_by"=>$user_data['id'],
							"created_date" =>date('Y-m-d H:i:s')
                 ); 
              $this->db->insert('hms_users',$data); 
              $users_id = $this->db->insert_id();    
           

                ////////// Send SMS /////////////////////
                if(in_array('640',$user_data['permission']['action']))
                {
                  if(!empty($post['mobile_no']))
                  {
                  	send_sms('patient_registration',18,$post['name'],$post['mobile_no'],array('{patient_name}'=>$post['name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id)); 
                  }
                }
        
			}
			$refere_by_pther='';
			if(!empty($post['ref_by_other']))
			{
			   $refere_by_pther = $post['ref_by_other'];
			}
			if(!empty($post['ipd_id']))
			{
			    $ipdid = $post['ipd_id'];
			}
			else
			{
			    $ipdid = '0';
			}
			if(!empty($post['referral_hospital']))
			{
			    $referral_hospital = $post['referral_hospital'];
			}
			else
			{
			    $referral_hospital = '0';
			}
			if(!empty($post['referral_doctor']))
			{
			    $referral_doctor = $post['referral_doctor'];
			}
			else
			{
			    $referral_doctor = '0';
			}
				$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$patient_id,
					'booking_code'=>$dialysis_booking_no,
					//$post['dialysis_booking_code'],
					'dialysis_room_no'=>$post['dialysis_room'],
					'dialysis_type'=>$post['dialysis_type'],
					'ipd_id'=>$ipdid,
					'dialysis_end_time'=>$time,
					'dialysis_name'=>$post['dialysis_name'],
					'room_type_id'=>$post['room_id'],
                    'room_no_id'=>$post['room_no_id'],
                    'room_id'=>$post['room_no_id'],
                    'bad_id'=>$post['bed_no_id'],
                    'bed_no_id'=>$post['bed_no_id'],
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_booking_date'=>date('Y-m-d',strtotime($post['dialysis_booking_date'])),
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time'])),
					'remarks'=>$post['remarks'],
					'referred_by'=>$post['referred_by'],
					'referral_doctor'=>$referral_doctor,
					'referral_hospital'=>$referral_hospital,
					'ref_by_other'=>$refere_by_pther,
					'no_of_visit'=>$post['no_of_visit'],
					'no_of_visit_done'=>1,
					'advance_payment'=>$post['advance_deposite'],
					'payment_mode'=>$post['payment_mode'],
                    'no_pf_visit_duration'=>$post['no_pf_visit_duration'],
                    'no_pf_visit_unit'=>$post['no_pf_visit_unit'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		
			
			
            $created_date = date('Y-m-d',strtotime($post['dialysis_date']));
            
            $payment_created_date = date('Y-m-d',strtotime($post['dialysis_booking_date']));
            
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dialysis_booking',$data); 
			 $last_id= $this->db->insert_id(); //die;
			//echo $this->db->last_query();die;
			
			//patient to room  dialysis_no
			
			$dialysis_room = array( 
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$patient_id,
					'dialysis_id'=>$last_id,
					'dialysis_room_no'=>$post['dialysis_room'],
					'room_type_id'=>$post['room_id'],
                    'room_id'=>$post['room_no_id'],
                    'bad_id'=>$post['bed_no_id'],
                    'room_no_id'=>$post['room_no_id'],
                    'bed_no_id'=>$post['bed_no_id'],
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time'])),
					'remarks'=>$post['remarks'],
					'dialysis_room_type'=>1,
					'dialysis_no'=>1,
					'dialysis_status'=>0,
					'is_deleted'=>0,
					);
					
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dialysis_patient_room',$dialysis_room);
			
			
			
			//end of patient to room
			
			//advance amount paid
            if(!empty($last_id) && !empty($post['advance_deposite']) && $post['advance_deposite']!='0.00') 
            {
                $advance_patient_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'dialysis_id'=>$last_id,
                    'patient_id'=>$patient_id,
                    'type'=>2,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['dialysis_date'])).' '.date('H:i:s',strtotime($post['dialysis_time'])),
                    'payment_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
                    'particular'=>'Advance Payment',
                    'price'=>$post['advance_deposite'],
                    'panel_price'=>$post['advance_deposite'],
                    'net_price'=>$post['advance_deposite'],
                    'status'=>1,
                    'created_date'=>$created_date
                );
                $this->db->insert('hms_dialysis_patient_to_charge',$advance_patient_charge);
                $adva_id =$this->db->insert_id();
                //echo $this->db->last_query(); exit;
                //referral_doctor
                $comission_arr = get_doc_hos_comission($post['referral_doctor'],$post['referral_hospital'],$post['advance_deposite'],148);
                //print_r($comission_arr);die;
                $doctor_comission = 0;
                $hospital_comission=0;
                $comission_type=0;
                $total_comission=0;
                if(!empty($comission_arr))
                {
                    $doctor_comission = $comission_arr['doctor_comission'];
                    $hospital_comission= $comission_arr['hospital_comission'];
                    $comission_type= $comission_arr['comission_type'];
                    $total_comission= $comission_arr['total_comission'];
                }
                //Payment Details
                $payment_data = array(
                                    'parent_id'=>$last_id,
                                    'branch_id'=>$user_data['parent_id'],
                                    'section_id'=>'15',  
                                    'type'=>'4',  
                                    'total_amount'=>$post['advance_deposite'],
                                    'net_amount'=>$post['advance_deposite'],
                                    'paid_amount'=>$post['advance_deposite'],
                                	'doctor_id'=>$referral_doctor,
				                    'hospital_id'=>$referral_hospital,
                                    'patient_id'=>$patient_id,
                                    'credit'=>'0',//$post['advance_deposite'],
                                    'debit'=>$post['advance_deposite'],//'',
                                    'pay_mode'=>$post['payment_mode'],
                                    'advance_payment_id'=>$adva_id,
                                    'doctor_comission'=>$doctor_comission,
    								'hospital_comission'=>$hospital_comission,
    								'comission_type'=>$comission_type,
    								'total_comission'=>$total_comission,
    								
    								'balance'=>0,
    								'test_master_rate'=>0,
    								'test_base_rate'=>0,
    								'test_comission_doc'=>0,
    								'test_comission_data'=>0,
    								'created_date'=>$payment_created_date,
    								'radhey'=>$payment_created_date,
                                    'created_by'=>$user_data['id']
                                 );
                $this->db->insert('hms_payment',$payment_data);
                $payment_id= $this->db->insert_id();
                //echo $this->db->last_query(); exit;


                /* genereate receipt number */
           
                if($post['advance_deposite']>0)
                {
                $hospital_receipt_no= check_hospital_receipt_no();
                $data_receipt_data= array('branch_id'=>$user_data['parent_id'],
                                    'section_id'=>25,
                                    'payment_id'=>$payment_id,
                                    'parent_id'=>$last_id,
                                    'reciept_prefix'=>$hospital_receipt_no['prefix'],
                                    'reciept_suffix'=>$hospital_receipt_no['suffix'],
                                    'created_by'=>$user_data['id'],
                                    'created_date'=>$created_date
                                    );
                $this->db->insert('hms_branch_hospital_no',$data_receipt_data); 
                }
            

            }
            
            //end of advance
			
			if(!empty($last_id)) 
			{
                    //panel charges
                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->dialysis_room_type_list(); 
                    $room_charge_type_list = $this->general_model->dialysis_room_charge_type_list();
                    
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_dialysis_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],'',$post['patient_type']);
                        $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];
                       
                      $registration_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'dialysis_id'=>$last_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['dialysis_date'])).' '.date('H:i:s',strtotime($post['dialysis_time'])),
                                'type'=>3,
                                'room_id'=>$post['room_id'],
                                'group_name'=>'',
                                'group_code'=>'',
                                'particular_code'=>'',
                                'quantity'=>1,
                                'transfer_status'=>1,
                                'particular'=>$charge_type,
                                'price'=>$charges,
                                'panel_price'=>$charges,
                                'net_price'=>$charges,
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_dialysis_patient_to_charge',$registration_patient_charge);
                        
                    } 
                   }
            
			}
			
			if(!empty($post['package_names'])) 
			{
			    
			    foreach($post['package_names'] as $key=>$value)
			    {
			        $p_charge_result =  $this->get_package_charge($key);
				   $p_charge =  $p_charge_result[0]->amount;
				    
				    $doctor_array=array(
				        "branch_id"=>$user_data['parent_id'],
				        'patient_id'=>$patient_id,
				        'dialysis_id'=>$last_id,
					    'package_id'=>$key,
					    'parent_id'=>$last_id,
					    'type'=>1,
					    'quantity'=>1,
					    'particular_id'=>0,
						'start_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
						'end_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
						'particular'=>$value[0],
					    'price'=>$p_charge,
					    'panel_price'=>$p_charge,
					    'net_price'=>$p_charge,
					    'status'=>1,
						'created_date'=>date('Y-m-d H:i:s')
					);
				
				    $this->db->insert('hms_dialysis_patient_to_charge',$doctor_array);
				    //echo $this->db->last_query(); exit;
				
			    }	
					
			}

			foreach($post['doctor_names'] as $key=>$value)
			{
				$doctor_array=array('dialysis_id'=>$last_id,
					'doctor_id'=>$key,
					'doctor_name'=>$value[0]
					);
				
				$this->db->insert('hms_dialysis_to_doctors',$doctor_array);
				
			} 
			
			
			if(!empty($post['appointment_id']))
            {
                $this->db->set('type',1);
    			$this->db->set('confirm_date',date('Y-m-d H:i:s'));
    			$this->db->where('id', $post['appointment_id']);
    			$this->db->update('hms_dialysis_appointment');
    			//echo $this->db->last_query(); exit;
                
            }

			$dialysis_book_id=$last_id;       
		} 
		//echo $dialysis_book_id;die;
		return $dialysis_book_id;	
	}

	public function get_package_hours($package_name='')
	{   

	    $user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('id',$package_name); 
		$query = $this->db->get('hms_dialysis_pacakge')->result();
		//print_r($query);die;
		return $query;
	}
	public function get_package_charge($package_id='')
	{   
	    $user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dialysis_pacakge_management.amount');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('id',$package_id); 
		$query = $this->db->get('hms_dialysis_pacakge_management')->result();
		//print_r($query);die;
		return $query;
	}
	
	

	public function dialysis_room_list()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('hms_dialysis_room')->result();
		//print_r($query);die;
		return $query;
	}

	public function get_dialysis_hours($op_name='')
	{   

	    $user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('id',$op_name); 
		$query = $this->db->get('hms_dialysis_management')->result();
		//print_r($query);die;
		return $query;
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
			$this->db->update('hms_dialysis_booking');
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
			$this->db->update('hms_dialysis_booking');
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
	       // $this->db->where('medicine_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_dialysis_booking');
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
    public function get_patient_by_id_with_ipd_detail($id)
	{

		$this->db->select('hms_patient.*,hms_patient.id as p_id,hms_dialysis_rooms.room_no');
		$this->db->from('hms_patient'); 
		$this->db->where('hms_dialysis_booking.patient_id',$id);
		$this->db->join('hms_dialysis_booking','hms_dialysis_booking.patient_id=hms_patient.id','left');
		$this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id=hms_dialysis_booking.room_id','left');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}

	public function pacakage_list(){
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('status',1); 
		$this->db->where('is_deleted',0); 
		$this->db->order_by('name','ASC'); 
		$query = $this->db->get('hms_dialysis_pacakge');
		return $query->result();
    }
    public function remarks_list(){
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('status',1); 
		$this->db->where('is_deleted',0); 
		$this->db->order_by('remarks','ASC'); 
		$query = $this->db->get('hms_dialysis_remarks')->result();
		//print_r($query);die;
		return $query;
    }

    
	public function get_doctor_name($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
			$users_data = $this->session->userdata('auth_users'); 
			$this->db->select('*');   
			$this->db->order_by('hms_doctors.doctor_name','ASC');
			$this->db->where('hms_doctors.is_deleted',0); 
			$this->db->where('hms_doctors.doctor_type IN (0,2)'); 
			$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  


			$query = $this->db->get('hms_doctors');
			$result = $query->result(); 

	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	        		//$response[] = $vals->medicine_name;
					$name = $vals->doctor_name.'|'.$vals->id;
					array_push($data, $name);
	        	}

	        	echo json_encode($data);
	        }
	        //return $response; 
    	} 
    }
    function doctor_list_by_otids($id){
    	$this->db->select('hms_dialysis_to_doctors.*');
		$this->db->from('hms_dialysis_to_doctors'); 
		$this->db->where('hms_dialysis_to_doctors.dialysis_id',$id);
		
		$query = $this->db->get()->result();
		$data=array(); 
		foreach($query as $res){
			$data[$res->doctor_id][]=$res->doctor_name;
		}
		return $data;
	
    }
    
     function package_list_by_otids($id)
     {
    	$this->db->select('hms_dialysis_patient_to_charge.*');
		$this->db->from('hms_dialysis_patient_to_charge'); 
		$this->db->where('hms_dialysis_patient_to_charge.dialysis_id',$id);
		$this->db->where('hms_dialysis_patient_to_charge.type',1);
		$query = $this->db->get()->result();
		$data=array(); 
		foreach($query as $res){
			$data[$res->package_id][]=$res->particular;
		}
		return $data;
	
    }

      function get_all_detail_print($ids="",$branch_ids="")
      {
          
          $user_data = $this->session->userdata('auth_users'); 
      	$result_operation=array();
    	$this->db->select('hms_dialysis_booking.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_dialysis_pacakge.remarks as pacakge_remarks,hms_dialysis_pacakge.type as pacakge_type,hms_dialysis_pacakge.amount as package_amount,hms_dialysis_pacakge.days,hms_dialysis_pacakge.name as package_name,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.patient_email,hms_dialysis_rooms.room_no,hms_simulation.simulation,hms_dialysis_room_to_bad.bad_no as bed_no,hms_gardian_relation.relation,hms_patient.relation_simulation_id,hms_patient.relation_type,hms_patient.relation_name,hms_dialysis_type.dialysis_type
    		,dia_type.dialysis_type as dia_type,hms_dialysis_management.amount as dia_amount,hms_dialysis_management.hours,hms_dialysis_management.name as dia_name,(CASE WHEN hms_patient.insurance_type=1 THEN "TPA" ELSE "Normal" END ) as insurance_type_name, hms_insurance_type.insurance_type as insurance_name, hms_insurance_company.insurance_company,hms_patient.polocy_no as policy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_dialysis_management.name as dialysiss_name
    		');
    	
		$this->db->from('hms_dialysis_booking'); 
		$this->db->where('hms_dialysis_booking.id',$ids);
		$this->db->where('hms_dialysis_booking.branch_id',$user_data['parent_id']);
		
		$this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_booking.dialysis_name','left');

		$this->db->join('hms_dialysis_type as dia_type','dia_type.id=hms_dialysis_management.type','left');
		

		$this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id=hms_dialysis_booking.room_id','left');
		$this->db->join('hms_dialysis_room_to_bad','hms_dialysis_room_to_bad.id=hms_dialysis_booking.bad_id','left');
		
		$this->db->join('hms_dialysis_pacakge','hms_dialysis_pacakge.id=hms_dialysis_booking.package_id','left');
		$this->db->join('hms_dialysis_type','hms_dialysis_type.id=hms_dialysis_pacakge.type','left');

		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_booking.patient_id','left');
		
		$this->db->join('hms_insurance_type',' hms_insurance_type.id = hms_patient.insurance_type_id','left'); // insurance type
		$this->db->join('hms_insurance_company',' hms_insurance_company.id = hms_patient.ins_company_id','left'); // insurance type
		
		
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
		$this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
		$this->db->join('hms_users','hms_users.id = hms_dialysis_booking.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		$this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		
		$this->db->where('hms_dialysis_booking.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$result_operation['dialysis_list']= $query->result();
          //print '<pre>'; print_r($result_operation);die;
		$this->db->select('hms_dialysis_to_doctors.*'); 
		$this->db->where('hms_dialysis_to_doctors.dialysis_id = "'.$ids.'"');
		$this->db->from('hms_dialysis_to_doctors');
		$result_operation['dialysis_list']['doctor_list']=$this->db->get()->result();
		
		$this->db->select('hms_dialysis_patient_to_charge.*');
		$this->db->where('hms_dialysis_patient_to_charge.dialysis_id = "'.$ids.'"');
        $this->db->where('hms_dialysis_patient_to_charge.branch_id = "'.$user_data['parent_id'].'"'); 
        $this->db->where('hms_dialysis_patient_to_charge.type =2'); 
		$this->db->from('hms_dialysis_patient_to_charge');
		$result_operation['dialysis_list']['advance_payment_list']=$this->db->get()->result();
		
		$this->db->select('hms_dialysis_patient_to_charge.*');
		$this->db->where('hms_dialysis_patient_to_charge.dialysis_id = "'.$ids.'"');
        $this->db->where('hms_dialysis_patient_to_charge.branch_id = "'.$user_data['parent_id'].'"'); 
        $this->db->where('hms_dialysis_patient_to_charge.type =1'); 
		$this->db->from('hms_dialysis_patient_to_charge');
		$result_operation['dialysis_list']['dialysis_package_list']=$this->db->get()->result();
		
		$this->db->select('hms_dialysis_patient_to_charge.*');
		$this->db->where('hms_dialysis_patient_to_charge.dialysis_id = "'.$ids.'"');
        $this->db->where('hms_dialysis_patient_to_charge.branch_id = "'.$user_data['parent_id'].'"'); 
        $this->db->where('hms_dialysis_patient_to_charge.type=3'); 
		$this->db->from('hms_dialysis_patient_to_charge');
		$result_operation['dialysis_list']['dialysis_room_list']=$this->db->get()->result();
		
		$this->db->select('hms_payment.*,hms_payment_mode.payment_mode');
		$this->db->where('hms_payment.parent_id = "'.$ids.'"');
        $this->db->where('hms_payment.branch_id = "'.$user_data['parent_id'].'"'); 
        $this->db->where('hms_payment.section_id=15'); 
        $this->db->where('hms_payment.type=4'); 
		$this->db->from('hms_payment');
		$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');

		$result_operation['dialysis_list']['advance_payment_details_list']=$this->db->get()->result();
		
		
		
		return $result_operation;
		
    }
     function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	//print_r($query);exit;
    	return $query;

    }
    public function dialysis_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_dialysis_management.*');
		$this->db->where('status',1);
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].')');
		$this->db->where('is_deleted',0);
		$this->db->from('hms_dialysis_management');
		$query=$this->db->get()->result();
		//print_r($query);exit;
		return $query;	
    }

    public function dialysis_scheduled_time($dialysis_date="",$start_datetime="",$end_datetime="",$dialysis_room="")
    {
    	

    	// // echo '<br/>';
    	 //echo  $start_datetime;
    	// echo '<br>';
    	// //exit;
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_dialysis_booking.*,CONCAT(hms_dialysis_booking.dialysis_date," ",hms_dialysis_booking.dialysis_time) as ot_start_datetime, CONCAT(hms_dialysis_booking.dialysis_date," ",hms_dialysis_booking.dialysis_end_time) as ot_end_datetime ');
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
		//$this->db->where('ot_room_no',$dialysis_room);
		//$this->db->where('ot_start_datetime',$dialysis_date);
		//$this->db->where('dialysis_time',$dialysis_date);
$this->db->where('hms_dialysis_booking.is_deleted',0);
		$this->db->from('hms_dialysis_booking');
		$query=$this->db->get()->result();
		$msg=array();
       if(!empty($query)){
		foreach($query as $res)
		{
          if($res->dialysis_date == $dialysis_date  && $res->dialysis_room_no == $dialysis_room && ($start_datetime >=$res->ot_start_datetime && $start_datetime <= $res->ot_end_datetime))
          {
          	  $msg['error']=1;
          }
          else
          {
          	$msg['error']=2;
          }
		}
	   }

	  
		return $msg;
		
         
    }


    public function dialysis_scheduled_doctor($dialysis_date="",$start_datetime="",$end_datetime="",$doctors="")
    {
    	$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_dialysis_booking.*,CONCAT(hms_dialysis_booking.dialysis_date," ",hms_dialysis_booking.dialysis_time) as ot_start_datetime, CONCAT(hms_dialysis_booking.dialysis_date," ",hms_dialysis_booking.dialysis_end_time) as ot_end_datetime ');
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
		//$this->db->where('ot_room_no',$dialysis_room);
		//$this->db->where('ot_start_datetime',$dialysis_date);
		//$this->db->where('dialysis_time',$dialysis_date);
$this->db->where('hms_dialysis_booking.is_deleted',0);
		$this->db->from('hms_dialysis_booking');
		$query=$this->db->get()->result();
		$msg=array();
		$new_val=array();
		$doctor=array();
		if(!empty($query))
		{

       //print '<pre>'; print_r($query);die;
		foreach($query as $res)
		{

		 if($res->dialysis_date == $dialysis_date && ($start_datetime >=$res->ot_start_datetime && $start_datetime <= $res->ot_end_datetime))
          {
           

           	foreach($doctors as $key=>$value)
          	{
          		$new_val[]=$key;
           			
          	}

        	  $this->db->select('hms_dialysis_to_doctors.*');
              $this->db->where("hms_dialysis_to_doctors.doctor_id IN (".implode(',',$new_val).")");
              $this->db->where('hms_dialysis_to_doctors.dialysis_id',$res->id);
              $result_doctor= $this->db->get('hms_dialysis_to_doctors')->result();
              
              if(!empty($result_doctor))
              {
              	$doctor['error']=4;
              }
              else
              {
              	$doctor['error']=5;
              }
           
          }
          else
          {
          	$msg['error']=2;
             
          }
		}
			return array('doctor_error'=>$doctor['error'],'error'=>$msg['error']);
	   }
         
	
    }
    
    public function get_dialysis_room_no()
    {
        $this->db->select('*'); 
        $users_data = $this->session->userdata('auth_users');
        //$this->db->where('status','1'); 
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->order_by('id','ASC'); 
        $query = $this->db->get('hms_dialysis_rooms');
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result;
    }
    public function get_dialysis_bed_no()
    {
        $this->db->select('hms_dialysis_room_to_bad.*,hms_dialysis_booking.is_deleted as dialysis_is_deleted'); 
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id = hms_dialysis_room_to_bad.dialysis_id','left');
        $this->db->order_by('hms_dialysis_room_to_bad.id','ASC'); 
        $query = $this->db->get('hms_dialysis_room_to_bad');
        // echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result;
    }
    
    public function get_package_name($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
			$users_data = $this->session->userdata('auth_users'); 
			$this->db->select('*');   
			$this->db->order_by('hms_dialysis_pacakge_management.name','ASC');
			$this->db->where('hms_dialysis_pacakge_management.is_deleted',0); 
			$this->db->where('hms_dialysis_pacakge_management.branch_id',$users_data['parent_id']);  
            $query = $this->db->get('hms_dialysis_pacakge_management');
			$result = $query->result(); 

	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	        		$name = $vals->name.'|'.$vals->id.'|'.$vals->amount;
					array_push($data, $name);
	        	}

	        	echo json_encode($data);
	        }
	        //return $response; 
    	} 
    }
    
    public function save_charges($dialysis_id="",$patient_id="")
	{
	    $users_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//echo "<pre>"; print_r($post); exit;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
			//edit
		}
		else
		{
			//add
			$dialysis_particular_billing_list = $this->session->userdata('dialysis_particular_charge_billing');
			//print '<pre>'; print_r($dialysis_particular_billing_list);die;
			if(!empty($dialysis_particular_billing_list))
			{
				$data_charge = array("is_deleted"=>1);
				
				$this->db->set('modified_by',$users_data['id']);
                $this->db->set('modified_date',date('Y-m-d H:i:s'));
                $this->db->where(array('dialysis_id'=>$dialysis_id,'patient_id'=>$patient_id,'type'=>5));
                $this->db->update('hms_dialysis_patient_to_charge',$data_charge);
				 

				foreach($dialysis_particular_billing_list as $particular_billing)
				{
					
						$total_price = $particular_billing['amount'];
						$panel_price = $particular_billing['amount'];
					
					$particular_data = array(
						'branch_id'=>$users_data['parent_id'],
						'dialysis_id'=>$post['dialysis_id'],
						'patient_id'=>$post['patient_id'],
						'type'=>5,
						'doctor'=>$particular_billing['doctor'],
						'doctor_id'=>$particular_billing['doctor_id'],
						'particular_id'=>$particular_billing['particular'],
						'start_date'=>date('Y-m-d H:i:s',strtotime($particular_billing['s_date'])),//date('Y-m-d H:i:s'),
						'particular'=>$particular_billing['particulars'],
						'quantity'=>$particular_billing['quantity'],
						//'price'=>$particular_billing['amount'],
                                                'price'=>$particular_billing['charges'],
						'panel_price'=>$panel_price,
						'net_price'=>$total_price,
						'status'=>1,
					);
					$this->db->insert('hms_dialysis_patient_to_charge',$particular_data);
					//echo $this->db->last_query(); exit;
				}	

			}

			$this->session->unset_userdata('dialysis_particular_charge_billing');
        	$this->session->unset_userdata('dialysis_particular_payment');
        	
		}
      return true;	
	}
    
    
    public function get_prescription_count($booking_id='')
    {
    	$this->db->select('*');  
        $this->db->where('hms_dialysis_prescription.booking_id',$booking_id);
        $query = $this->db->get('hms_dialysis_prescription');
        //$result = $query->result(); 
        return $query->num_rows();

    }
    
    public function get_dialysis_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dialysis_booking.*, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.adhar_no, hms_patient.patient_email,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.dob,hms_patient.modified_date as patient_modified_date');

	

		$this->db->from('hms_dialysis_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_dialysis_booking.patient_id'); 
		$this->db->where('hms_dialysis_booking.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_dialysis_booking.id',$id);
		$this->db->where('hms_dialysis_booking.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
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
		$relation_sim_id='';
		if(!empty($post['relation_simulation_id']))
		{
		    $relation_sim_id=$post['relation_simulation_id'];
		}
		$relation_name='';
		if(!empty($post['relation_name']))
		{
		    $relation_name=$post['relation_name'];
		}
		$relation_type='';
		if(!empty($post['relation_type']))
		{
		    $relation_type=$post['relation_type'];
		}

		$data_patient = array(
								"patient_name"=>$post['patient_name'],
								"mobile_no"=>$post['mobile_no'],
								"gender"=>$post['gender'], 
								"age_y"=>$post['age_y'],
								"age_m"=>$post['age_m'],
								"age_d"=>$post['age_d'],
								'adhar_no'=>$post['aadhaar_no'],
								'relation_type'=>$relation_type,
								'relation_name'=>$relation_name,
								'relation_simulation_id'=>$relation_sim_id,
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
					"status"=>1,
					'prescription_date'=>date('Y-m-d H:i',strtotime($post['prescription_date'])),
					); 
		    
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dialysis_prescription',$data); 
			$data_id = $this->db->insert_id(); 
			
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
						$this->db->insert('hms_dialysis_prescription_patient_test',$test_data); 
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
									$this->db->where('hms_medicine_company.company_name',$post['brand'][$i]);  
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
					$this->db->insert('hms_dialysis_prescription_patient_pres',$prescription_data);
					//echo $this->db->last_query(); exit;
					$test_data_id = $this->db->insert_id(); 
				
			}
			


		 	return $data_id;
	
    }
    
   public function template_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_dialysis_prescription_template.*');  
		$this->db->where('hms_dialysis_prescription_template.id',$template_id);  
		$this->db->where('hms_dialysis_prescription_template.is_deleted',0); 
		$this->db->where('hms_dialysis_prescription_template.branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_dialysis_prescription_template');
		$result = $query->row(); 
		return json_encode($result);
		 
    }
    
    public function get_template_prescription_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_dialysis_prescription_patient_pres_template.*');  
		$this->db->where('hms_dialysis_prescription_patient_pres_template.template_id',$template_id);  
		$query = $this->db->get('hms_dialysis_prescription_patient_pres_template');
		$result = $query->result(); 
		return json_encode($result);
		 
    }
    
    public function get_template_test_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_dialysis_prescription_patient_test_template.*');  
		$this->db->where('hms_dialysis_prescription_patient_test_template.template_id',$template_id);  
		$query = $this->db->get('hms_dialysis_prescription_patient_test_template');
		$result = $query->result(); 
		//echo $this->db->last_query(); exit;
		return json_encode($result);
		 
    }
    
    public function template_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_dialysis_prescription_template');
        $result = $query->result(); 
        return $result; 
    }
    
    
    public  function get_discharge_bill_info_datatables($book_id="0",$patient_id="0")
    {
    
			$users_data = $this->session->userdata('auth_users');
			$this->db->select("hms_dialysis_patient_to_charge.*,hms_dialysis_room_category.room_category,hms_doctors.doctor_name");
			$this->db->from('hms_dialysis_patient_to_charge');
			$this->db->join('hms_dialysis_room_category','hms_dialysis_room_category.id=hms_dialysis_patient_to_charge.room_id','left');
			
			$this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_patient_to_charge.doctor_id','left');
			
			$this->db->where('hms_dialysis_patient_to_charge.type NOT IN (2,7,8)');
			$this->db->where('hms_dialysis_patient_to_charge.dialysis_id',$book_id);
			$this->db->where('hms_dialysis_patient_to_charge.is_deleted',0);
			$this->db->where('hms_dialysis_patient_to_charge.patient_id',$patient_id);
			 $res= $this->db->get()->result();
			 //echo $this->db->last_query(); exit;
			 $data['CHARGES']=array();
			 $data['advance_payment']='';
			 $res = json_decode(json_encode($res), true);
			 foreach($res as $data_new)
			 {
			 	
			 	$data['CHARGES'][]=$data_new;
			}
			
			$this->db->select("hms_dialysis_patient_to_charge.branch_id, hms_dialysis_patient_to_charge.dialysis_id, hms_dialysis_patient_to_charge.id, hms_dialysis_patient_to_charge.type, hms_dialysis_patient_to_charge.particular, hms_dialysis_patient_to_charge.start_date, hms_dialysis_patient_to_charge.end_date, (CASE WHEN hms_dialysis_patient_to_charge.quantity >= 0 THEN 0 END) as quantity, (CASE WHEN hms_dialysis_patient_to_charge.price >= 0 THEN '0.00' END) as price, sum(hms_dialysis_patient_to_charge.net_price) as net_price"); 
			$this->db->from('hms_dialysis_patient_to_charge');
			$this->db->where('hms_dialysis_patient_to_charge.branch_id',$users_data['parent_id']);
			$this->db->where('hms_dialysis_patient_to_charge.dialysis_id',$book_id);
			$this->db->where('hms_dialysis_patient_to_charge.patient_id',$patient_id);
			$this->db->where('hms_dialysis_patient_to_charge.type',2); 
			$this->db->group_by('hms_dialysis_patient_to_charge.type');
			$data['advance_payment']= $this->db->get()->result();
			
			
			$this->db->from('hms_dialysis_patient_to_charge');
			
			$this->db->join('hms_payment','hms_payment.advance_payment_id=hms_dialysis_patient_to_charge.id','left');
			
			$this->db->where('hms_dialysis_patient_to_charge.branch_id',$users_data['parent_id']);
			$this->db->where('hms_dialysis_patient_to_charge.dialysis_id',$book_id);
			$this->db->where('hms_dialysis_patient_to_charge.patient_id',$patient_id);
			$this->db->where('hms_dialysis_patient_to_charge.type',2); 
			//$this->db->group_by('hms_dialysis_patient_to_charge.type');
			//5 aug 2020 narayana
			//echo $this->db->last_query();
			$data['row_wise_advance_payment']= $this->db->get()->result();
			
			

	       //print '<pre>'; print_r($data);die;
			return $data;
		

	
			
		
    }
    
     public function get_paid_amount($dialysis_id="",$patient_id=""){
  	  $user_data = $this->session->userdata('auth_users');
  	  $this->db->select('*');
  	  $this->db->where(array('id'=>$dialysis_id,'patient_id'=>$patient_id,'branch_id'=>$user_data['parent_id']));
  	  $query=$this->db->get('hms_dialysis_booking')->result();
  	  return $query;
  }
  
  public function save_procedure_bill()
  {

		$user_data = $this->session->userdata('auth_users');
		$users_data = $this->session->userdata('auth_users');

		$post = $this->input->post();
		//echo "<pre>"; print_r($post); exit;
		
 		$blance ='';
		$referral_id = get_ipd_referral_doctor($post['dialysis_id']);
		if(!empty($referral_id))
		{
			$referral_id =$referral_id;
		}
		
		if(!empty($post['dialysis_id']) && !empty($post['patient_id']))
		{
				$this->db->where(array('patient_id'=>$post['patient_id'],'section_id'=>15,'branch_id'=>$users_data['parent_id'],'parent_id'=>$post['patient_id']));
                $this->db->delete('hms_refund_payment');
                
                $this->db->where(array('branch_id'=>$user_data['parent_id'],'refund_id'=>$post['dialysis_id'],'paid_to_id'=>$post['patient_id'],'type'=>18));
				$this->db->delete('hms_expenses');
         }
		

		if($post['total_advance_discount']>$post['total_amount'])
		{
			//if already exist delete the data and insert it
			$insert_array=array(
              'patient_id'=>$post['patient_id'],
              'section_id'=>15,
              'parent_id'=>$post['patient_id'],
              'branch_id'=>$users_data['parent_id'],
              'doctor_id'=>"",
              'refund_amount'=>$post['total_refund_amount'],
              'refund_date'=>date('Y-m-d H:i:s'),//date('Y-m-d',strtotime($post['discharge_date'])),
              'created_date'=>date('Y-m-d H:i:s'),
              'status'=>"",
              'type'=>15,
              'created_by'=>$users_data['id']);

			$this->db->insert('hms_refund_payment',$insert_array);
			//echo $this->db->last_query();
         	$refundid= $this->db->insert_id();

			$data_expenses= array(
                    'branch_id'=>$users_data['parent_id'],
                    'type'=>18,
                    'vouchar_no'=>generate_unique_id(19),
                    'parent_id'=>$refundid,
                    'refund_id'=>$post['dialysis_id'],
                    'expenses_date'=>date('Y-m-d H:i:s'),
                    'paid_to_id'=>$post['patient_id'],
                    'paid_amount'=>$post['total_refund_amount'],
                    'payment_mode'=>$post['refund_payment_mode'],
                    'created_date'=>date('Y-m-d H:i:s'),
                    'modified_date'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$users_data['id']
                    );
				//print_r($data_expenses);die;
                $this->db->insert('hms_expenses',$data_expenses);
				//echo $this->db->last_query(); die();
				//echo $insert_id;die;
				
				
                
                
                $this->db->where('parent_id',$post['dialysis_id']);
	            $this->db->where('section_id','15');
	            $this->db->where('type','12');
	            //$this->db->where('balance>0');
	            $this->db->where('patient_id',$post['patient_id']);
	            $query_d_pay = $this->db->get('hms_payment');
	            $row_payment_pay = $query_d_pay->result();
	            
	            
	       if(!empty($post['dialysis_id']) && !empty($post['patient_id']) && !empty($row_payment_pay) )
    		{
    			//echo "aa"; die;

    			
	            	foreach($row_payment_pay as $row_d)
	            	{
	            		
                       if(!empty($post['total_discount']))
    					{
    						$total_credit = $post['total_amount']-$post['total_discount'];
    					}
    					else
    					{
    						$total_credit = $post['total_net_amount'];	
    					}
    		        
    		        $doctor_comission = 0;
                    $hospital_comission=0;
                    $comission_type='';
                    $total_comission=0;
                    
    		        $comission_arr = get_doc_hos_comission($referral_id,0,$post['total_paid_amount'],148);
                    //print_r($comission_arr);die; 
                    if(!empty($comission_arr))
                    {
                        $doctor_comission = $comission_arr['doctor_comission'];
                        $hospital_comission= $comission_arr['hospital_comission'];
                        $comission_type= $comission_arr['comission_type'];
                        $total_comission= $comission_arr['total_comission'];
                    }			
					$payment_data = array(
						'parent_id'=>$post['dialysis_id'],
						'branch_id'=>$user_data['parent_id'],
						'section_id'=>'15',
						'doctor_id'=>$referral_id,
						'type'=>12,
						'patient_id'=>$post['patient_id'],
						'total_amount'=>str_replace(',', '', $post['total_amount']),
						'discount_amount'=>$post['total_discount'],
						'net_amount'=>str_replace(',', '', $post['total_net_amount']),
						'credit'=>str_replace(',', '', $total_credit),
						'debit'=>str_replace(',', '', $post['total_paid_amount']),
						'pay_mode'=>$post['payment_mode'],
						'balance'=>$post['total_balance'],
						'paid_amount'=>$post['total_paid_amount'],
						'doctor_comission'=>$doctor_comission,
                        'hospital_comission'=>$hospital_comission,
                        'comission_type'=>$comission_type,
                        'total_comission'=>$total_comission,
						'created_date'=>date('Y-m-d H:i:s'),//date('Y-m-d H:i:s',strtotime($post['discharge_date'])),
                        'created_by'=>$user_data['id']
    	             );
					$this->db->where('id',$row_d->id); 
    				$this->db->update('hms_payment',$payment_data);
    				//echo $this->db->last_query(); exit; 
    		
	            }
	            

    		}
    		else
    		{
	            
	            
	            
                
                
                // Set refund comission 
				$this->db->where('parent_id',$post['dialysis_id']);
    			//$this->db->where('parent_id',$post['data_id']);
                $this->db->where('section_id','15');
                //$this->db->where('balance>0');
                $this->db->where('patient_id',$post['patient_id']);
                $this->db->where('(doctor_id>0 OR hospital_id>0)');
                $query_d_pay = $this->db->get('hms_payment');
                $row_d_pay = $query_d_pay->row_array();
                
                $comission_arr = get_doc_hos_comission($row_d_pay['doctor_id'],$row_d_pay['hospital_id'],$post['total_refund_amount'],148);
                //print_r($comission_arr);die;
                $doctor_comission = 0;
                $hospital_comission=0;
                $comission_type='';
                $total_comission=0;
                if(!empty($comission_arr))
                {
                    $doctor_comission = $comission_arr['doctor_comission'];
                    $hospital_comission= $comission_arr['hospital_comission'];
                    $comission_type= $comission_arr['comission_type'];
                    $total_comission= '-'.$comission_arr['total_comission'];
                    
                    $payment_data = array(
								'parent_id'=>$post['dialysis_id'],
								'branch_id'=>$users_data['parent_id'],
								'section_id'=>'15',  //'section_id'=>'3',
								'patient_id'=>$post['patient_id'],
								'hospital_id'=>$row_d_pay['hospital_id'],
								'doctor_id'=>$row_d_pay['doctor_id'],
								'credit'=>'',
								'debit'=>0,
								'type'=>12,
								'pay_mode'=>$post['refund_payment_mode'],
								'advance_payment_id'=>$advance_payment_id,
								'doctor_comission'=>$doctor_comission,
								'hospital_comission'=>$hospital_comission,
								'comission_type'=>$comission_type,
								'total_comission'=>$total_comission,
								
								'created_date'=>date('Y-m-d H:i:s',strtotime($post['discharge_date'])),//date('Y-m-d H:i:s'),
								'created_by'=>$user_data['id']
            	             );

			        $this->db->insert('hms_payment',$payment_data);
                }
				// End refund comission
				
		}

				/*add sales banlk detail*/
            if(!empty($post['field_name']))
            {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                {
	                $data_field_value= array(
	                'field_value'=>$post['field_name'][$i],
	                'field_id'=>$post['field_id'][$i],
	                'type'=>17,
	                'section_id'=>17,
	                'p_mode_id'=>$post['refund_payment_mode'],
	                'branch_id'=>$user_data['parent_id'],
	                'parent_id'=>$post['dialysis_id'],
	                'ip_address'=>$_SERVER['REMOTE_ADDR']
	                );
	                $this->db->set('created_by',$user_data['id']);
	                $this->db->set('created_date',date('Y-m-d H:i:s'));
	                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
             }

			

            /*add sales banlk detail*/
		}
		else
		{

	  	   		
				if(!empty($post['dialysis_id']) && !empty($post['patient_id']))
				{

					$this->db->where('parent_id',$post['dialysis_id']);
		            $this->db->where('section_id','15');
		            $this->db->where('type','12');
		            //$this->db->where('balance>0');
		            $this->db->where('patient_id',$post['patient_id']);
		            $query_d_pay = $this->db->get('hms_payment');
		            $row_d_pay = $query_d_pay->result();
		            //echo "<pre>";print_r($row_d_pay); die;
				
					
				}
	            

    		if(!empty($post['dialysis_id']) && !empty($post['patient_id']) && !empty($row_d_pay) )
    		{
    			//echo "aa"; die;

    			
	            	foreach($row_d_pay as $row_d)
	            	{
	            		
                       if(!empty($post['total_discount']))
    					{
    						$total_credit = $post['total_amount']-$post['total_discount'];
    					}
    					else
    					{
    						$total_credit = $post['total_net_amount'];	
    					}
    		        
    		        $doctor_comission = 0;
                    $hospital_comission=0;
                    $comission_type='';
                    $total_comission=0;
                    
    		        $comission_arr = get_doc_hos_comission($referral_id,0,$post['total_paid_amount'],148);
                    //print_r($comission_arr);die; 
                    if(!empty($comission_arr))
                    {
                        $doctor_comission = $comission_arr['doctor_comission'];
                        $hospital_comission= $comission_arr['hospital_comission'];
                        $comission_type= $comission_arr['comission_type'];
                        $total_comission= $comission_arr['total_comission'];
                    }			
					$payment_data = array(
						'parent_id'=>$post['dialysis_id'],
						'branch_id'=>$user_data['parent_id'],
						'section_id'=>'15',
						'doctor_id'=>$referral_id,
						'type'=>12,
						'patient_id'=>$post['patient_id'],
						'total_amount'=>str_replace(',', '', $post['total_amount']),
						'discount_amount'=>$post['total_discount'],
						'net_amount'=>str_replace(',', '', $post['total_net_amount']),
						'credit'=>str_replace(',', '', $total_credit),
						'debit'=>str_replace(',', '', $post['total_paid_amount']),
						'pay_mode'=>$post['payment_mode'],
						'balance'=>$post['total_balance'],
						'paid_amount'=>$post['total_paid_amount'],
						'doctor_comission'=>$doctor_comission,
                        'hospital_comission'=>$hospital_comission,
                        'comission_type'=>$comission_type,
                        'total_comission'=>$total_comission,
						'created_date'=>date('Y-m-d H:i:s'),//date('Y-m-d H:i:s',strtotime($post['discharge_date'])),
                        'created_by'=>$user_data['id']
    	             );
					$this->db->where('id',$row_d->id); 
    				$this->db->update('hms_payment',$payment_data);
    				//echo $this->db->last_query(); exit; 
    		
	            }
	            

    		}
    		else
    		{
    			//echo "<pre>"; print_r($post); exit;
    			if(!empty($post['total_discount']))
				{
					$total_credit = $post['total_amount']-$post['total_discount'];
				}
				else
				{
					$total_credit = $post['total_net_amount'];	
				}
				
				// Set ipd comission
                $doctor_comission = 0;
                $hospital_comission=0;
                $comission_type='';
                $total_comission=0; 
                
                if(!empty($referral_id))
                { 
                    $comission_arr = get_doc_hos_comission($referral_id,0,$post['total_paid_amount'],148);
                    //print_r($comission_arr);die; 
                    if(!empty($comission_arr))
                    {
                        $doctor_comission = $comission_arr['doctor_comission'];
                        $hospital_comission= $comission_arr['hospital_comission'];
                        $comission_type= $comission_arr['comission_type'];
                        $total_comission= $comission_arr['total_comission'];
                    }
                }
                
				// set ipd comission
    			$payment_data = array(
						'parent_id'=>$post['dialysis_id'],
						'branch_id'=>$user_data['parent_id'],
						'section_id'=>'15',
						'doctor_id'=>$referral_id, 
						'type'=>12,
						'patient_id'=>$post['patient_id'],
						'total_amount'=>str_replace(',', '', $post['total_amount']),
						'discount_amount'=>$post['total_discount'],
						'net_amount'=>str_replace(',', '', $post['total_net_amount']),
						'credit'=>str_replace(',', '', $total_credit),
						'debit'=>str_replace(',', '', $post['total_paid_amount']),
						'pay_mode'=>$post['payment_mode'],
                        'doctor_comission'=>$doctor_comission,
                        'hospital_comission'=>$hospital_comission,
                        'comission_type'=>$comission_type,
                        'total_comission'=>$total_comission,
						//'bank_name'=>$bank_name,
						//'cheque_no'=>$cheque_no,
						//'cheque_date'=>$cheque_date,
						'balance'=>$post['total_balance'],
						'paid_amount'=>$post['total_paid_amount'],
						//'transection_no'=>$transaction_no,
                        'created_date'=>date('Y-m-d H:i:s'),//date('Y-m-d H:i:s',strtotime($post['discharge_date'])),//
                        'created_by'=>$user_data['id']
    	             );
    		$this->db->insert('hms_payment',$payment_data);
    		$payment_id= $this->db->insert_id();

    			if($post['total_paid_amount']>0)
	    		{
	    			
	    			$hospital_receipt_no= check_hospital_receipt_no();
					$data_receipt_data= array(
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>25,
								'payment_id'=>$payment_id,
								'parent_id'=>$post['dialysis_id'],
								'reciept_prefix'=>$hospital_receipt_no['prefix'],
								'reciept_suffix'=>$hospital_receipt_no['suffix'],
								'created_by'=>$user_data['id'],
								'created_date'=>date('Y-m-d H:i:s')
								);
					$this->db->insert('hms_branch_hospital_no',$data_receipt_data);	
					//echo $this->db->last_query(); die;
	    		}	
    		}
    		/*add sales banlk detail*/
    		//genereate reciept no

    		//echo "yy"; die;


			if(!empty($post['field_name']))
                {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                {
	                $data_field_value= array(
	                'field_value'=>$post['field_name'][$i],
	                'field_id'=>$post['field_id'][$i],
	                'type'=>5,
	                'section_id'=>9,
	                'p_mode_id'=>$post['payment_mode'],
	                'branch_id'=>$user_data['parent_id'],
	                'parent_id'=>$post['dialysis_id'],
	                'ip_address'=>$_SERVER['REMOTE_ADDR']
	                );
	                $this->db->set('created_by',$user_data['id']);
	                $this->db->set('created_date',date('Y-m-d H:i:s'));
	                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

            /*add sales banlk detail*/
        }

		/* update booking table  */
		$this->db->select('hms_dialysis_booking.procedure_bill_no');
        $this->db->from('hms_dialysis_booking'); 
        $this->db->where('hms_dialysis_booking.id',$post['dialysis_id']);
        $this->db->where('hms_dialysis_booking.is_deleted','0');
        $query = $this->db->get(); 
        $ipd_record =  $query->row_array();
        if(!empty($ipd_record['procedure_bill_no']))
        {
           $bill_no = $ipd_record['procedure_bill_no'];
           $ipd_discharge_created_date = date('Y-m-d H:i:s'); // $ipd_record['ipd_discharge_created_date'];
        }
		else
		{
			$bill_no = generate_unique_id(75);
			$ipd_discharge_created_date = date('Y-m-d H:i:s');
		}
		//date('Y-m-d H:i:s',strtotime($post['discharge_date']))
		//'discharge_date'=>date('Y-m-d H:i:s'),
		$update_data=array('total_amount_dis_bill'=>$post['total_amount'],'discount_amount_dis_bill'=>$post['total_discount'],'advance_payment_dis_bill'=>$post['total_advance_discount'],'net_amount_dis_bill'=>str_replace(',', '', $post['total_net_amount']),'paid_amount_dis_bill'=>$post['total_paid_amount'],'refund_amount_dis_bill'=>str_replace(',', '', $post['total_refund_amount']),'balance_amount_dis_bill'=>$post['total_balance'],'procedure_bill_no'=>$bill_no,'procedure_payment_mode'=>$post['payment_mode'],'procedure_created_date'=>$ipd_discharge_created_date,'procedure_created_by'=>$user_data['id'],'procedure_remarks'=>$post['dialysis_remarks']);
		$this->db->where(array('id'=>$post['dialysis_id'],'patient_id'=>$post['patient_id']));
		$this->db->update('hms_dialysis_booking',$update_data);
		//echo $this->db->last_query(); exit;
		/* update booking table  */


		 /*add sales banlk detail*/
		 $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['dialysis_id'],'type'=>9,'section_id'=>9));
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
	                'type'=>9,
	                'section_id'=>9,
	                'p_mode_id'=>$post['payment_mode'],
	                'branch_id'=>$user_data['parent_id'],
	                'parent_id'=>$post['dialysis_id'],
	                'ip_address'=>$_SERVER['REMOTE_ADDR']
	                );
	                $this->db->set('created_by',$user_data['id']);
	                $this->db->set('created_date',date('Y-m-d H:i:s'));
	                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

            /*add sales banlk detail*/

        /* update charge entry table  */
			$this->db->select('*');
			$this->db->from('hms_dialysis_patient_to_charge');
			$this->db->where(array('dialysis_id'=>$post['dialysis_id'],'patient_id'=>$post['patient_id']));
			$result_room= $this->db->get()->result();

			//echo "<pre>"; print_r($result_room); exit;
			foreach($result_room as $rooms_transfer)
			{

			$registration_patient_charge_update = array(
			"branch_id"=>$user_data['parent_id'],
			'dialysis_id'=>$post['dialysis_id'],
			'patient_id'=>$post['patient_id'],
			'end_date'=>date('Y-m-d h:i:s'), //date('Y-m-d',strtotime($post['discharge_date'])),
			'particular'=>$rooms_transfer->particular,
			'particular_code'=>$rooms_transfer->particular_code,
			'price'=>$rooms_transfer->price,
			'panel_price'=>$rooms_transfer->panel_price,
			'net_price'=>$rooms_transfer->net_price,
			'status'=>1,
			'created_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where('hms_dialysis_patient_to_charge.end_date','0000-00-00 00:00:00');
			$this->db->where(array('id'=>$rooms_transfer->id));
			$this->db->update('hms_dialysis_patient_to_charge',$registration_patient_charge_update);

			}
            

            
  }
    
    
    
    public function dialysis_detail_data($dialysis_id="",$patient_id="")
    {
       $this->db->select('*');
       $this->db->where(array('id'=>$dialysis_id,'patient_id'=>$patient_id));
       $result= $this->db->get('hms_dialysis_booking')->result();
       return $result;
    }
    
    function get_patient_according_to_dialysis($dialysis_id="",$patient_id='',$section_id='',$type='') 
    {   
        $users_data = $this->session->userdata('auth_users'); 
       $this->db->select("hms_patient.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,(CASE WHEN ds.emp_id>0 THEN emp.name ELSE hms_branch.branch_name END) as user_name_disch,concat_ws('',hms_patient.address, hms_patient.address2, hms_patient.address3) as address,hms_patient.id as p_id,hms_dialysis_booking.id as dialysis_id,hms_dialysis_booking.booking_code,hms_dialysis_booking.total_amount_dis_bill,hms_dialysis_booking.discount_amount_dis_bill,hms_dialysis_booking. advance_payment_dis_bill,hms_dialysis_booking.net_amount_dis_bill,hms_dialysis_booking.refund_amount_dis_bill,hms_dialysis_booking.procedure_created_date,hms_dialysis_booking.payment_mode,hms_dialysis_rooms.room_no,hms_dialysis_room_to_bad.bad_no,hms_dialysis_room_to_bad.bad_name,hms_dialysis_room_category.room_category,hms_dialysis_booking.procedure_bill_no,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.dialysis_time,hms_doctors.doctor_name,hms_payment_mode.payment_mode,(select sum(credit)-sum(debit) from hms_payment where patient_id = hms_dialysis_booking.patient_id AND parent_id = hms_dialysis_booking.id AND section_id = '15' AND branch_id = ".$users_data['parent_id'].") as balance_amount_dis_bill, (select sum(debit) from hms_payment where patient_id = hms_dialysis_booking.patient_id AND parent_id = hms_dialysis_booking.id AND section_id = '15' AND branch_id = ".$users_data['parent_id']." AND type!=4 AND type!=0) as paid_amount_dis_bill,hms_specialization.specialization,discharge_payment_mode.payment_mode as disc_payment_mode,hms_payment_mode_field_value_acc_section.field_value as card_no,hms_gardian_relation.relation,hms_dialysis_booking.remarks,(CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' ELSE 'Normal' END ) as insurance_type,hms_patient.polocy_no as insurance_policy_no,hms_patient.tpa_id, hms_patient.ins_amount as insurance_amount, hms_patient.ins_authorization_no as auth_no,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.address as address1,hms_dialysis_booking.procedure_remarks,hms_dialysis_management.name as dialysiss_name,hms_dialysis_booking.dialysis_booking_date");
        $this->db->from('hms_patient');
        if(!empty($patient_id))
        {
            $this->db->where('hms_dialysis_booking.patient_id',$patient_id);    
        } 
        if(!empty($dialysis_id))
        {
            $this->db->where('hms_dialysis_booking.id',$dialysis_id);    
        }
        
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.patient_id=hms_patient.id','left');
        
        	$this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_booking.dialysis_name','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id = hms_patient.relation_type','left'); 
        
         $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
        
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_dialysis_booking.payment_mode','left');
        
        $this->db->join('hms_payment_mode as discharge_payment_mode','discharge_payment_mode.id=hms_dialysis_booking.procedure_payment_mode','left');
        
        
        if(!empty($section_id) && !empty($type))
        {
            $this->db->join('hms_payment_mode_field_value_acc_section','hms_payment_mode_field_value_acc_section.parent_id = hms_dialysis_booking.id AND hms_payment_mode_field_value_acc_section.section_id='.$section_id.' AND hms_payment_mode_field_value_acc_section.type='.$type,'left');
        
        }
        else
        {
            $this->db->join('hms_payment_mode_field_value_acc_section','hms_payment_mode_field_value_acc_section.parent_id = hms_dialysis_booking.id AND hms_payment_mode_field_value_acc_section.section_id=3 AND hms_payment_mode_field_value_acc_section.type=9','left');
            
        }
        
        
        $this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id=hms_dialysis_booking.room_id','left');
        $this->db->join('hms_dialysis_room_to_bad','hms_dialysis_room_to_bad.id=hms_dialysis_booking.bad_id','left');
        $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.doctor_id','left');
        $this->db->join('hms_specialization','hms_specialization.id=hms_doctors.specilization_id','left');
        $this->db->join('hms_dialysis_room_category','hms_dialysis_room_category.id=hms_dialysis_booking.room_type_id','left');
        $this->db->join('hms_users','hms_users.id = hms_dialysis_booking.created_by','left');
        
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->join('hms_users as ds','ds.id = hms_dialysis_booking.procedure_created_by','left');
        
         $this->db->join('hms_employees as emp','emp.id=ds.emp_id','left');
        
         
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left'); 
		 
	
        
        $query = $this->db->get(); 
        //echo $this->db->last_query(); //exit;
        return $query->row_array();
    }
    
    public function get_particular_data($vals="")
	{
			$response = '';
			if(!empty($vals))
			{
			    $users_data = $this->session->userdata('auth_users'); 
			    $this->db->select('*');  
			    $this->db->where('status','1'); 
			    $this->db->order_by('particular','ASC');
			    $this->db->where('is_deleted',0);
			    $this->db->where('particular LIKE "'.$vals.'%"');
			    $this->db->where('branch_id',$users_data['parent_id']);  
			    $query = $this->db->get('hms_ipd_perticular');
			    $result = $query->result(); 
			    //echo $this->db->last_query();
			    if(!empty($result))
			    { 
			    	$data = array();
			    	foreach($result as $vals)
			    	{
			    		//$response[] = $vals->medicine_name;
						$name = $vals->particular.'|'.$vals->charge.'|'.$vals->id;
						array_push($data, $name);
			    	}

			    	echo json_encode($data);
			    }
			    //return $response; 
			} 
	}
	
  public function save_particulars($dialysis_id,$patient_id)
  {
   $post= $this->input->post();
   $this->db->select('*');
   $users_data = $this->session->userdata('auth_users');
 
	$particular_data = array(
							'branch_id'=>$users_data['parent_id'],
							'dialysis_id'=>$dialysis_id,
							'patient_id'=>$patient_id,
							'type'=>5,
							'particular_id'=>$post['particular_id'],
							'start_date'=>date('Y-m-d',strtotime($post['date'])),
							'doctor'=>0,
						    'doctor_id'=>0,
							'particular'=>$post['particular'],
							'quantity'=>$post['qty'],
							'price'=>$post['charge'],
							'panel_price'=>$post['charge'],
							'net_price'=>$post['qty']*$post['charge'],
							'status'=>1,
							);
	$this->db->insert('hms_dialysis_patient_to_charge',$particular_data);
   
  }
  
  public function update_procedure_date()
  {
    	$post= $this->input->post();
    	if(isset($post['net_price']))
    	{
    		$net_price= $post['net_price'];
    	}
    	else
    	{
    		$net_price=$post['qty_edit']*$post['price_edit'];

    	}
    	if(isset($post['qty_edit'])){
    		$quantity=$post['qty_edit'];
    	}
    	else
    	{
    		$quantity=1;
    	}
    	if(isset($post['price_edit']))
    	{
          $price= $post['price_edit'];
    	}
    	else
    	{
    	 $price	='';
    	}
    	 $update_row= array('price'=>$price,'net_price'=>$net_price,'quantity'=>$quantity);
    	 //print '<pre>'; print_r($update_row);die;
    	 $this->db->where('id',$post['data_id']);
    	 return $this->db->update('hms_dialysis_patient_to_charge',$update_row);
    }
    
    public function delete_charges($id="",$dialysis_id="",$patient_id="")
	{
		if(!empty($id) && $id>0)
		{
		$user_data = $this->session->userdata('auth_users');
		$this->db->set('is_deleted',1);
		$this->db->set('deleted_by',$user_data['id']);
		$this->db->set('deleted_date',date('Y-m-d H:i:s'));
		$this->db->where(array('id'=>$id));
		return $this->db->update('hms_dialysis_patient_to_charge');
		//echo $this->db->last_query();die;
		} 
	}
	
	public function deleteAllprocedure_charges($ids=array(),$dialysis_id="",$patient_id="")
	{
		if(!empty($ids))
		{
			$id=implode(',', $ids);
		$user_data = $this->session->userdata('auth_users');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('dialysis_id',$dialysis_id);
		$this->db->where('patient_id',$patient_id);
		$this->db->where('id IN ('.$id.')');
		return $this->db->delete('hms_dialysis_patient_to_charge');
		} 
	}
	
	function procedure_template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_dialysis_branch_procedure_bill_print_setting.*');
    	$this->db->where($data);
    	//$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
    	$this->db->from('hms_dialysis_branch_procedure_bill_print_setting');
    	$query=$this->db->get()->row();
    	//print_r($query);exit;
    	return $query;

    }
    
    public function get_all_payment($ipd_id,$patient_id)
	{
		$users_data = $this->session->userdata('auth_users'); 
			 $this->db->select('(select sum(debit) as paid_amount from hms_payment as  payment where payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id AND payment.type!=4  AND payment.branch_id = "'.$users_data['parent_id'].'") as paid_amount');
			$this->db->from('hms_patient');
			$this->db->join('hms_payment','hms_payment.patient_id=hms_patient.id AND hms_patient.id='.$patient_id); 
			
			$this->db->where('hms_payment.parent_id',$ipd_id);
			$this->db->where('hms_payment.branch_id',$users_data['parent_id']);
			$this->db->where('hms_payment.section_id',15);
			$this->db->where('hms_payment.type!=',4);
			$this->db->group_by('hms_payment.parent_id, hms_payment.section_id');
			$query = $this->db->get();
			//echo $this->db->last_query(); exit;
			return $query->row();
	}
	
	public function getdialysisbyid($id)
    {
        $this->db->select('hms_dialysis_booking.*,hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.adhar_no,hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_dialysis_rooms.room_no,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.mobile_no,hms_payment.debit as net_amount');
        $this->db->from('hms_dialysis_booking'); 
        $this->db->join('hms_patient','hms_patient.id = hms_dialysis_booking.patient_id'); 
        $this->db->join('hms_payment','hms_payment.parent_id = hms_dialysis_booking.id AND hms_payment.type=4 AND hms_payment.section_id=15','left');
        $this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id = hms_dialysis_booking.room_id','left');
        $this->db->where('hms_dialysis_booking.id',$id);
        $this->db->where('hms_dialysis_booking.is_deleted','0');
        $query = $this->db->get(); 
        //echo $this->db->last_query(); 
        return $query->row_array();
    }
    
    public function get_dialysis_assigned_doctors_name($ipd_bkng_id)
    {
        $this->db->select('group_concat(doctor_id) as assigned_doc_ids');
        $this->db->from('hms_dialysis_to_doctors');
        $this->db->where('dialysis_id', $ipd_bkng_id);
        $res=$this->db->get();
        if($res->num_rows() > 0)
        {
            $result = $res->result();
            $assigned_doc_ids=$result[0]->assigned_doc_ids;
            $this->db->select('group_concat(doctor_name) as assigned_doctor');
            $this->db->from('hms_doctors');
            $this->db->where('id in ('.$assigned_doc_ids.')');
            $res=$this->db->get();
            //if($res->num_rows() > 0)
            if(count($res) > 0)
            {
                return $res->result();
            }
            else
            {
                return "empty";
            }
        }
        else
        {
            return "empty";
        }
    }
    
    public function get_dialysis_package_name($ipd_bkng_id)
    {
        $this->db->select('group_concat(particular) as assigned_particular');
        $this->db->from('hms_dialysis_patient_to_charge');
        $this->db->where('dialysis_id', $ipd_bkng_id);
        $this->db->where('type', 1);
        $res=$this->db->get();
        if(count($res) > 0)
        {
            return $res->result();
        }
        else
        {
            return "empty";
        }
        
    }
    
    
    public function save_next_dialysis()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();  
        $this->load->model('general/general_model'); 
        $room_type_list = $this->general_model->dialysis_room_type_list(); 
        $room_charge_type_list = $this->general_model->dialysis_room_charge_type_list();
        if(!empty($room_charge_type_list))
        
        /*$update_booked_data= array('room_type_id'=>$post['room_id'],'room_id'=>$post['room_no_id'],'bad_id'=>$post['bed_no_id']);
        $this->db->where(array('id'=>$post['dialysis_id'],'patient_id'=>$post['patient_id']));
        $this->db->update('hms_dialysis_booking',$update_booked_data);*/
        
        $this->db->select('hms_dialysis_booking.no_of_visit_done');
        $this->db->from('hms_dialysis_booking');
        $this->db->where('hms_dialysis_booking.id',$post['dialysis_id']);
        $this->db->where('hms_dialysis_booking.patient_id',$post['patient_id']);
        $query_dia = $this->db->get(); 
        //echo $this->db->last_query(); exit;
        $result_dia = $query_dia->row_array();
        
        $no_of_visit_done = $result_dia['no_of_visit_done'];
        
        
        $update_booked_data= array('no_of_visit_done'=>$no_of_visit_done+1);
        $this->db->where(array('id'=>$post['dialysis_id'],'patient_id'=>$post['patient_id']));
        $this->db->update('hms_dialysis_booking',$update_booked_data);
        //echo $this->db->last_query(); exit;
        
        $this->db->select('*');
        $this->db->from('hms_dialysis_room_to_bad');
        $this->db->where('room_type_id',$post['room_id']);
        $this->db->where('room_id',$post['room_no_id']);
        $this->db->where('bad_no',$post['bed_no_id']);
        
        $query = $this->db->get(); 
        //echo $this->db->last_query(); exit;
        $result = $query->row_array();

        $bed_id = $result['id'];
        $this->db->set('status','1');
        $this->db->set('dialysis_id',$post['dialysis_id']);
        $this->db->where('id',$result['id']);
        $this->db->update('hms_dialysis_room_to_bad');
        
        $dialysis_room = array( 
          'branch_id'=>$user_data['parent_id'],
          'patient_id'=>$post['patient_id'],
          'dialysis_id'=>$post['dialysis_id'],
          'dialysis_room_no'=>$post['room_no_id'],
          'room_type_id'=>$post['room_id'],
          'room_id'=>$post['room_no_id'],
          'bad_id'=>$post['bed_no_id'],
          'room_no_id'=>$post['room_no_id'],
          'bed_no_id'=>$post['bed_no_id'],
          'dialysis_date'=>date('Y-m-d',strtotime($post['transfer_date'])),
          'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['transfer_time'])),
          'remarks'=>$post['remarks'],
          'dialysis_room_type'=>0,  //for next dialysis
          'dialysis_no'=>1,
          'dialysis_status'=>0,
          'is_deleted'=>0,
          );
          
      $this->db->set('created_by',$user_data['id']);
      $this->db->set('created_date',date('Y-m-d H:i:s'));
      $this->db->insert('hms_dialysis_patient_room',$dialysis_room);
     // echo $this->db->last_query(); exit;

        

    }
    
    public function get_dialysis_history_data($dialysis_id='',$patient_id='')
    {
        $user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dialysis_patient_room.*,hms_patient.patient_name,hms_patient.patient_code,hms_dialysis_booking.booking_code,hms_dialysis_rooms.room_no,hms_dialysis_room_to_bad.bad_no,hms_dialysis_room_category.room_category as room_type,hms_dialysis_management.name as dialysiss_name');
		$this->db->join('hms_patient','hms_patient.id = hms_dialysis_patient_room.patient_id','left');
		$this->db->join('hms_dialysis_booking','hms_dialysis_booking.id = hms_dialysis_patient_room.dialysis_id','left');
		$this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_booking.dialysis_name','left');
		
		$this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id=hms_dialysis_patient_room.room_no_id','left');
		$this->db->join('hms_dialysis_room_to_bad','hms_dialysis_room_to_bad.id=hms_dialysis_patient_room.bad_id','left');
		$this->db->join('hms_dialysis_room_category','hms_dialysis_room_category.id=hms_dialysis_patient_room.room_type_id','left');
		
		
		$this->db->where('hms_dialysis_patient_room.branch_id',$user_data['parent_id']);
		$this->db->where('hms_dialysis_patient_room.dialysis_id',$dialysis_id); 
		$this->db->where('hms_dialysis_patient_room.patient_id',$patient_id); 
		$query = $this->db->get('hms_dialysis_patient_room')->result();
		//print_r($query);die;
		return $query;
    }
    
    public function item_list($test_id="",$booking_id="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('path_test_to_inventory_item.*, inventory_item.booking_id as booking_status,inventory_item.qty as booking_qty, inventory_item.unit_id as booking_unit,path_test_to_inventory_item.qty as inventory_qty,hms_stock_item_unit.unit as first_unit,path_stock_category.category,path_item.item,path_item.qty');  
      $this->db->join('path_test_to_inventory_item as inventory_item', 'inventory_item.item_id=path_test_to_inventory_item.item_id AND inventory_item.booking_id= "'.$booking_id.'"', 'left');
      $this->db->where('path_test_to_inventory_item.test_id',$test_id);
      $this->db->where('path_test_to_inventory_item.booking_id',0);
      $this->db->where('path_test_to_inventory_item.branch_id',$users_data['parent_id']); 
      $this->db->join('path_item`','path_item.id=path_test_to_inventory_item.item_id','left'); 
      $this->db->join('path_stock_category`','path_stock_category.id=path_item.category_id','left');
      $this->db->join('hms_stock_item_unit`','hms_stock_item_unit.id=path_test_to_inventory_item.unit_id','left'); 
      $this->db->group_by('path_test_to_inventory_item.item_id');
      $query = $this->db->get('path_test_to_inventory_item');
      $result = $query->result();
      //print_r($result);die;
      return $result;
    }
    /* save inventory data in test to inventory as well as stock item */
    public function insert_invntory()
    {
        $post= $this->input->post();
        //echo "<pre>"; print_r($post); die;
        $user_data = $this->session->userdata('auth_users'); 

        if(!empty($post['item_name']))
        {
          $array_item = array_values($post['item_name']);
        }
        if(!empty($post['item_id']))
        {
          $array_item_id = array_values($post['item_id']);
        }
        if(!empty($post['unit_value']))
        {
          $array_unit_value = array_values($post['unit_value']);
        }
        if(!empty($post['quantity']))
        {
           $array_quantity = array_values($post['quantity']);
        }
            
        
        $data_items= array('branch_id'=>$user_data['parent_id'],
                        'type'=>1,
                        'patient_id'=>$post['patient_id'],
                        'booking_id'=>$post['booking_id'],
                        'booking_date'=>date('Y-m-d')
                       );
                        $this->db->set('created_by',$user_data['id']);
                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                        $this->db->insert('hms_dialysis_to_inventory',$data_items);
                        $inv_book_id= $this->db->insert_id();
        //echo $this->db->last_query(); die;
      if(!empty($array_item))
      {
         $i=0;
         $qty='';
        foreach($array_item as $item_name)
        {
                        $data_insert_items= 
                        array('branch_id'=>$user_data['parent_id'],
                        'type'=>1,
                        'patient_id'=>$post['patient_id'],
                        'booking_id'=>$post['booking_id'],
                        'inventory_book_id'=>$inv_book_id,
                        'item_id'=>$array_item_id[$i],
                        'unit_id'=>$array_unit_value[$i],
                        'qty'=>$array_quantity[$i], 
                        );
                        $this->db->set('created_by',$user_data['id']);
                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                        $this->db->insert('hms_dialysis_to_inventory_item',$data_insert_items);
                        //echo $this->db->last_query();
                        
                        /* enter data in stock table */

                         //$check_parent_unit= get_checkparent_unit($array_unit_value[$i]);

                     
                         /*if(isset($check_parent_unit) && count($check_parent_unit)>0)
                         {
                           $qty=$array_quantity[$i]/$check_parent_unit[0]->qty_with_second_unit;
                            
                         }
                         else
                         {
                           $qty=$array_quantity[$i];
                         }*/
                        
                          $data_new_stock=array("branch_id"=>$user_data['parent_id'],
                                            "type"=>5,
                                            "parent_id"=>$post['booking_id'],
                                            'test_id'=>$post['test_id'],
                                            'item_id'=>$array_item_id[$i],
                                            "credit"=>$array_quantity[$i],
                                            "debit"=>'',
                                            "qty"=>$array_quantity[$i],
                                            "price"=>'',
                                            'cat_type_id'=>'',
                                            "item_name"=>'',
                                            //"vat"=>$medicine_list['vat'],
                                            'unit_id'=>$array_unit_value[$i],
                                            'item_code'=>'',
                                            "total_amount"=>'',
                                            'per_pic_price'=>'',
                                            "created_by"=>$user_data['id'],
                                            "created_date"=>date('Y-m-d H:i:s'),);
            $this->db->insert('path_stock_item',$data_new_stock);
            //echo $this->db->last_query();die;
           /* enter data in stock table */

             //echo $this->db->last_query();
            $i++;


        } 
        //die;
      }
    }
    
    public function get_dialysis_inventory_data($dialysis_id='',$patient_id='')
    {
        $user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dialysis_to_inventory.*,hms_patient.patient_name,hms_patient.patient_code,hms_dialysis_booking.booking_code');
		$this->db->join('hms_patient','hms_patient.id = hms_dialysis_to_inventory.patient_id','left');
		$this->db->join('hms_dialysis_booking','hms_dialysis_booking.id = hms_dialysis_to_inventory.booking_id','left');
		$this->db->where('hms_dialysis_to_inventory.branch_id',$user_data['parent_id']);
		$this->db->where('hms_dialysis_to_inventory.booking_id',$dialysis_id); 
		$this->db->where('hms_dialysis_to_inventory.patient_id',$patient_id); 
		
		$result_pre= $this->db->get('hms_dialysis_to_inventory')->result();
		//echo $this->db->last_query(); exit;
		
		/*$this->db->select('hms_dialysis_to_inventory_item.*');
		$this->db->join('hms_dialysis_to_inventory','hms_dialysis_to_inventory.id = hms_dialysis_to_inventory_item.inventory_book_id'); 
		$this->db->where('hms_dialysis_to_inventory_item.booking_id = "'.$dialysis_id.'"');
		
		$this->db->where('hms_dialysis_to_inventory_item.patient_id = "'.$patient_id.'"');
		
		$this->db->where('hms_dialysis_to_inventory_item.branch_id= "'.$user_data['parent_id'].'"');
		$this->db->from('hms_dialysis_to_inventory_item');
		$result_pre['inventory_list']['item_list']=$this->db->get()->result();*/
        return $result_pre;
		
    }
    
    public function get_dialysis_inventory_data_by_id($id='')
    {
        $this->db->select('hms_dialysis_to_inventory_item.*,path_item.item as item_name, path_item.item_code');
		$this->db->join('hms_dialysis_to_inventory','hms_dialysis_to_inventory.id = hms_dialysis_to_inventory_item.inventory_book_id'); 
		
		$this->db->join('path_item','path_item.id=hms_dialysis_to_inventory_item.item_id','left');
		
		$this->db->where('hms_dialysis_to_inventory_item.inventory_book_id = "'.$id.'"');
		$this->db->from('hms_dialysis_to_inventory_item');
		$result_pre=$this->db->get()->result();
        return $result_pre;  
    }
    
      
    
    public function get_advance_by_id($id="")
	{
		$this->db->select('hms_dialysis_patient_to_charge.*'); 
		$this->db->from('hms_dialysis_patient_to_charge');
		$this->db->where('hms_dialysis_patient_to_charge.id',$id);
		$query = $this->db->get(); 
		$result= $query->row_array();
	     return $result;
	}
	
	function get_payment_reciept_no($ids='')
    {
        $r_num='';
        if(!empty($ids))
        {
            
        
    	$sql = "SELECT  hms_payment.reciept_prefix,hms_payment.reciept_suffix FROM `hms_dialysis_patient_to_charge` as p_charge join hms_payment on hms_payment.advance_payment_id = p_charge.id AND hms_payment.section_id=15 AND hms_payment.type=4  WHERE p_charge.id=".$ids;

    	 $query = $this->db->query($sql);
		 $data = $query->result();
		 
		 if(!empty($data))
		 {
		   //  echo "<pre>"; print_r($data); exit;
		 	$reciept_prefix = $data[0]->reciept_prefix; 
		 	$reciept_suffix = $data[0]->reciept_suffix; 
		 	$r_num = $reciept_prefix.$reciept_suffix;
		 }
        }
		 
		  return $r_num;

    }


}
?>