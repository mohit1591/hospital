<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Appointment_model extends CI_Model 
{
	var $table = 'hms_opd_booking';
	var $column = array('hms_opd_booking.id','hms_opd_booking.appointment_code','hms_patient.patient_name','hms_patient.mobile_no','hms_patient.patient_code','hms_opd_booking.appointment_date','hms_patient.gender','hms_patient.address', 'hms_patient.father_husband', 'hms_patient.patient_email', 'ins_type.insurance_type', 'ins_cmpy.insurance_company', 'src.source', 'ds.disease', 'hms_hospital.hospital_name', 'spcl.specialization','docs.doctor_name','hms_opd_booking.policy_no');
//,'hms_opd_booking.appointment_time'
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_opd_booking.*,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.patient_code as patient_reg_no, hms_patient.mobile_no, (CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband, sim.simulation as father_husband_simulation,hms_patient.patient_email, ins_type.insurance_type, ins_cmpy.insurance_company, src.source as patient_source, ds.disease , (CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name, spcl.specialization,docs.doctor_name");

		
        $this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left');
		$this->db->join('hms_disease','hms_disease.id= hms_opd_booking.diseases','left');
        $this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');
        $this->db->join('hms_insurance_type as ins_type','ins_type.id=hms_opd_booking.insurance_type_id', 'left');
        $this->db->join('hms_insurance_company as ins_cmpy','ins_cmpy.id=hms_opd_booking.ins_company_id', 'left');

        $this->db->join('hms_patient_source as src', 'src.id=hms_opd_booking.source_from', 'Left');
        $this->db->join('hms_disease as ds', 'ds.id=hms_opd_booking.diseases', 'left');
       
    	$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
          $this->db->join('hms_specialization as spcl','spcl.id = hms_opd_booking.specialization_id','left');

        $this->db->join('hms_doctors as docs','docs.id = hms_opd_booking.attended_doctor','left');

		$this->db->where('hms_opd_booking.is_deleted','0');


		$search = $this->session->userdata('appointment_search');
	
		if($user_data['users_role']==4)
		{
			$this->db->where('hms_opd_booking.patient_id = "'.$user_data['parent_id'].'"');
		}
		elseif($user_data['users_role']==3)
		{
			$this->db->where('hms_opd_booking.referral_doctor = "'.$user_data['parent_id'].'"');
		}
		else
		{
			if(isset($search['branch_id']) && $search['branch_id']!=''){
			$this->db->where('hms_opd_booking.branch_id IN ('.$search['branch_id'].')');
			}else{
			$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');
			}	
		}
		$this->db->where('hms_opd_booking.appointment =1');
		if($search['app_status']=='1') //pending
		{
		    $this->db->where('hms_opd_booking.type =1');
		}
		elseif($search['app_status']=='2') //confirm
		{
		   $this->db->where('hms_opd_booking.type =2'); 
		}
		elseif($search['app_status']=='3')
		{
		    $this->db->where('(hms_opd_booking.type =1 OR hms_opd_booking.type =2)'); 
		}
	    else
		{
		  $this->db->where('hms_opd_booking.type =1');  
		}	
		
		/////// Search query end //////////////
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['source_from']))
			{
				$this->db->where('hms_opd_booking.source_from',$search['source_from']);
			}
            if(!empty($search['appointment_code']))
			{
				$this->db->where('hms_opd_booking.appointment_code= "'.$search['appointment_code'].'"');
			}
			if(!empty($search['disease']))
			{
				$this->db->where('hms_disease.disease= "'.$search['disease'].'"');
			}
			if(!empty($search['disease_code']))
			{
				$this->db->where('hms_disease.disease_code= "'.$search['disease_code'].'"');
			}

            if(!empty($search['appointment_from_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['appointment_from_date'])).' 00:00:00';
				$this->db->where('hms_opd_booking.appointment_date >= "'.$start_date.'"');
			}

			if(!empty($search['appointment_end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['appointment_end_date'])).' 23:59:59';
				$this->db->where('hms_opd_booking.appointment_date <= "'.$end_date.'"');
			}

			if(!empty($search['start_time']))
			{
				$start_time = date('H:i:s',strtotime($search['start_time']));
				$this->db->where('hms_opd_booking.appointment_time >= "'.$start_time.'"');
			}

			if(!empty($search['end_time']))
			{
				$end_time = date('H:i:s',strtotime($search['end_time']));
				$this->db->where('hms_opd_booking.appointment_time <= "'.$end_time.'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}

			if(!empty($search['attended_doctor']))
			{
				$this->db->where('hms_opd_booking.attended_doctor',$search['attended_doctor']);
			}

			if(!empty($search['referral_doctor']))
			{
				$this->db->where('hms_opd_booking.referral_doctor',$search['referral_doctor']);
			}

			if(!empty($search['specialization']))
			{
				$this->db->where('hms_opd_booking.specialization',$search['specialization']);
			}


			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}
			if(isset($search['adhar_no']) && $search['adhar_no']!="")
			{
				$this->db->where('hms_patient.adhar_no',$search['adhar_no']);
			}

			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_opd_booking.status',$search['status']);
			}
			// if(isset($search['booking_status']) && $search['booking_status']!="")
			// {
			// 	$this->db->where('hms_opd_booking.booking_status',$search['booking_status']);
			// }
			    /* refered by code */
            if(isset($search['referral_hospital']) && $search['referred_by']=='1' && !empty($search['referral_hospital']))
            {
            $this->db->where('hms_opd_booking.referral_hospital' ,$search['referral_hospital']);
            }
            elseif(isset($search['refered_id']) && $search['referred_by']=='0' && !empty($search['refered_id']))
            {
                $this->db->where('hms_opd_booking.referral_doctor' ,$search['refered_id']);
            }
            /* refered by code */


		}
		$this->db->from($this->table); 
		
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
			$this->db->where('hms_opd_booking.created_by IN ('.$emp_ids.')');
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
		$this->db->from($this->table);
		
		$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left');
		$this->db->join('hms_disease','hms_disease.id= hms_opd_booking.diseases','left');
        $this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');
        $this->db->join('hms_insurance_type as ins_type','ins_type.id=hms_opd_booking.insurance_type_id', 'left');
        $this->db->join('hms_insurance_company as ins_cmpy','ins_cmpy.id=hms_opd_booking.ins_company_id', 'left');

        $this->db->join('hms_patient_source as src', 'src.id=hms_opd_booking.source_from', 'Left');
        $this->db->join('hms_disease as ds', 'ds.id=hms_opd_booking.diseases', 'left');
       
    	$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
          $this->db->join('hms_specialization as spcl','spcl.id = hms_opd_booking.specialization_id','left');

        $this->db->join('hms_doctors as docs','docs.id = hms_opd_booking.attended_doctor','left');
		$this->db->where('hms_opd_booking.is_deleted','0');


		$search = $this->session->userdata('appointment_search');
		//echo "<pre>"; print_r($search); die;
	
		if($user_data['users_role']==4)
		{
			$this->db->where('hms_opd_booking.patient_id = "'.$user_data['parent_id'].'"');
		}
		elseif($user_data['users_role']==3)
		{
			$this->db->where('hms_opd_booking.referral_doctor = "'.$user_data['parent_id'].'"');
		}
		else
		{
			if(isset($search['branch_id']) && $search['branch_id']!=''){
			$this->db->where('hms_opd_booking.branch_id IN ('.$search['branch_id'].')');
			}else{
			$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');
			}	
		}
		$this->db->where('hms_opd_booking.appointment=1');
		if(isset($search) && !empty($search) && $search['app_status']=='1') //pending
		{
		    $this->db->where('hms_opd_booking.type =1');
		}
		elseif(isset($search) && !empty($search) && $search['app_status']=='2') //confirm
		{
		   $this->db->where('hms_opd_booking.type =2'); 
		}
		elseif($search['app_status']=='3')
		{
		    $this->db->where('(hms_opd_booking.type =1 OR hms_opd_booking.type =2)'); 
		}
		else
		{
		  $this->db->where('hms_opd_booking.type =1');  
		}
		
		
		/////// Search query end //////////////
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['source_from']))
			{
				$this->db->where('hms_opd_booking.source_from',$search['source_from']);
			}
            if(!empty($search['appointment_code']))
			{
				$this->db->where('hms_opd_booking.appointment_code= "'.$search['appointment_code'].'"');
			}
			if(!empty($search['disease']))
			{
				$this->db->where('hms_disease.disease= "'.$search['disease'].'"');
			}
			if(!empty($search['disease_code']))
			{
				$this->db->where('hms_disease.disease_code= "'.$search['disease_code'].'"');
			}

            if(!empty($search['appointment_from_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['appointment_from_date'])).' 00:00:00';
				$this->db->where('hms_opd_booking.appointment_date >= "'.$start_date.'"');
			}

			if(!empty($search['appointment_end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['appointment_end_date'])).' 23:59:59';
				$this->db->where('hms_opd_booking.appointment_date <= "'.$end_date.'"');
			}

			if(!empty($search['start_time']))
			{
				$start_time = date('H:i:s',strtotime($search['start_time']));
				$this->db->where('hms_opd_booking.appointment_time >= "'.$start_time.'"');
			}

			if(!empty($search['end_time']))
			{
				$end_time = date('H:i:s',strtotime($search['end_time']));
				$this->db->where('hms_opd_booking.appointment_time <= "'.$end_time.'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}

			if(!empty($search['attended_doctor']))
			{
				$this->db->where('hms_opd_booking.attended_doctor',$search['attended_doctor']);
			}

			if(!empty($search['referral_doctor']))
			{
				$this->db->where('hms_opd_booking.referral_doctor',$search['referral_doctor']);
			}

			if(!empty($search['specialization']))
			{
				$this->db->where('hms_opd_booking.specialization',$search['specialization']);
			}


			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}
			if(isset($search['adhar_no']) && $search['adhar_no']!="")
			{
				$this->db->where('hms_patient.adhar_no',$search['adhar_no']);
			}

			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_opd_booking.status',$search['status']);
			}
			// if(isset($search['booking_status']) && $search['booking_status']!="")
			// {
			// 	$this->db->where('hms_opd_booking.booking_status',$search['booking_status']);
			// }
			    /* refered by code */
            if(isset($search['referral_hospital']) && $search['referred_by']=='1' && !empty($search['referral_hospital']))
            {
            $this->db->where('hms_opd_booking.referral_hospital' ,$search['referral_hospital']);
            }
            elseif(isset($search['refered_id']) && $search['referred_by']=='0' && !empty($search['refered_id']))
            {
                $this->db->where('hms_opd_booking.referral_doctor' ,$search['refered_id']);
            }
            /* refered by code */


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
			$this->db->where('hms_opd_booking.created_by IN ('.$emp_ids.')');
		}
		//$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $this->db->count_all_results();
	}

	
	public function get_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_opd_booking.*, hms_patient.simulation_id,hms_simulation.simulation, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.adhar_no,hms_patient.patient_email,hms_doctors.doctor_name,hms_cities.city,hms_state.state,hms_countries.country,hms_patient.pincode,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob');
		$this->db->from('hms_opd_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id');
		$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking. attended_doctor','left');
		$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
		$this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left');
		//$this->db->join('hms_gardian_relation','hms_gardian_relation.id = hms_patient.relation_type','left');
		$this->db->join('hms_state','hms_state.id = hms_patient.state_id','left');
		//$this->db->join('hms_state','hms_state.id = hms_patient.state_id','left');
		$this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); 
		$this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_opd_booking.id',$id);
		$this->db->where('hms_opd_booking.is_deleted','0');
		$query = $this->db->get();
		return $query->row_array();
	}
	
    public function save()
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
        //available time schedule
        $available_time_value='';
        if(!empty($post['available_time']))
        {
        	$available_time_value = $this->get_available_time_value($post['available_time']);
        }
        $insurance_type='';
   		if(isset($post['insurance_type']))
   		{
   			$insurance_type = $post['insurance_type'];
   		}
   		$insurance_type_id='';
   		if(isset($post['insurance_type_id']))
   		{
   			$insurance_type_id = $post['insurance_type_id'];
   		}
   		$ins_company_id='';
   		if(isset($post['ins_company_id']))
   		{
   			$ins_company_id = $post['ins_company_id'];
   		}
   		$doctor_slot='';
   		
   		if(!empty($post['doctor_slot']))
		{
			$doctor_slot = $post['doctor_slot'];
   		}
   		
   		if(!empty($post['doctor_slot']))
   		{
   		    
   		
   		    $appointment_time = $post['doctor_slot'];
   		}
   		else
   		{
   		    $appointment_time=$post['appointment_time'];
   		}
   	
        $data_patient = array(    
							'simulation_id'=>$post['simulation_id'],
							'branch_id'=>$branch_id,
							'patient_name'=>$post['patient_name'],
							'mobile_no'=>$post['mobile_no'],
							'gender'=>$post['gender'],
							'age_y'=>$post['age_y'],
							'age_m'=>$post['age_m'],
							'age_d'=>$post['age_d'],
							'relation_type'=>$post['relation_type'],
							'relation_name'=>$post['relation_name'],
							//'relation_simulation_id'=>$post['relation_simulation_id'],
							'patient_email'=>$post['patient_email'], 
							'address'=>$post['address'],
							'city_id'=>$post['city_id'],
							'state_id'=>$post['state_id'],
							'country_id'=>$post['country_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$user_data['id'],
							'adhar_no'=>$post['adhar_no'], 
							'address2'=>$post['address_second'],
							'address3'=>$post['address_third'],
							//insurance
							"insurance_type"=>$insurance_type,
							"insurance_type_id"=>$insurance_type_id,
							"ins_company_id"=>$ins_company_id,
							"polocy_no"=>$post['polocy_no'],
							"tpa_id"=>$post['tpa_id'],
							"ins_amount"=>$post['ins_amount'],
							"ins_authorization_no"=>$post['ins_authorization_no'],

					    	);
		        $appointment_data = array(
							
							'branch_id'=>$branch_id,
							'diseases'=>$post['diseases'],	    
							'appointment_date'=>date('Y-m-d',strtotime($post['appointment_date'])),
							'appointment_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$appointment_time)), 
							'type'=>1,
							'specialization_id'=>$post['specialization'],
							'referred_by'=>$post['referred_by'],
							'attended_doctor'=>$post['attended_doctor'],
							'referral_doctor'=>$post['referral_doctor'],
							'referral_hospital'=>$post['referral_hospital'],
							'ref_by_other'=>$post['ref_by_other'],
							//'booking_date'=>date('Y-m-d H:i:s'),
							'booking_date'=>date('Y-m-d',strtotime($post['appointment_date'])),
							'booking_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$appointment_time)), 
							'booking_status'=>'0',
							'source_from'=>$post['source_from'],
							'remarks'=>$post['remarks'],
							'available_time'=>$post['available_time'],
							'time_value'=>$available_time_value,
							'doctor_slot'=>$doctor_slot,
							'remarks'=>$post['remarks'],
							'opd_type'=>$post['opd_type'],
							'pannel_type'=>$post['pannel_type'],
					);
		
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			
			if(!empty($post['patient_id']) && $post['patient_id']>0)
		    {
		    	$this->db->where('id',$post['patient_id']);
				$this->db->update('hms_patient',$data_patient); 
				//echo $this->db->last_query(); exit;
		    }
			$booking_id = $post['data_id'];
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$appointment_data); 
			//echo $this->db->last_query(); exit;


		}
		else
		{   
			    if(!empty($post['patient_id']) && $post['patient_id']>0)
			    {
			    	$patient_id = $post['patient_id'];
			    } 
			    else
			    {
			    	$patient_code = generate_unique_id(4,$branch_id);
			    	$this->db->set('patient_code',$patient_code);
			    	$this->db->set('created_date',date('Y-m-d H:i:s'));
			    	$this->db->insert('hms_patient',$data_patient);  
			    	$patient_id = $this->db->insert_id(); 
			    	//users create
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
					/*$this->db->select('*');
					$this->db->where('users_role','4');
					$query = $this->db->get('hms_permission_to_role');     
					$permission_list = $query->result();
					if(!empty($permission_list))
					{
					foreach($permission_list as $permission)
					{
					$data = array(
					        'users_role' =>4,
					        'users_id' => $users_id,
					        'master_id' => $patient_id,
					        'section_id' => $permission->section_id,
					        'action_id' => $permission->action_id, 
					        'permission_status' => '1',
					        'ip_address' => $_SERVER['REMOTE_ADDR'],
					        'created_by' =>$user_data['id'],
					        'created_date' =>date('Y-m-d H:i:s'),
					     );
					$this->db->insert('hms_permission_to_users',$data);
					}
					}*/

					////////// Send SMS /////////////////////
					if(in_array('640',$user_data['permission']['action']))
					{
					if(!empty($post['mobile_no']))
					{
						send_sms('patient_registration',18,$post['patient_name'],$post['mobile_no'],array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id));

					}
					}
					//////////////////////////////////////////

					////////// SEND EMAIL ///////////////////
					if(!empty($post['patient_email']))
					{ 
					$this->load->library('general_library'); 
					$this->general_library->email($post['patient_email'],'','','','','','patient_registration','18',array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id)); 
					}
			    	//echo $this->db->last_query(); 
			    }
			    
			    $discount_result = $this->get_common_token_setting($user_data['parent_id']);
	            if($discount_result==1)
	            {
	                //generate token for appointment also
	                $token_type=$this->get_token_setting();
                    $token_type=$token_type['type'];
                    if($post['appointment_date']=='1970-01-01')
                    {
                        $booking_date = date('Y-m-d');
                    }
                    else
                    {
                        $booking_date = $post['appointment_date'];
                    }
                    $new_token = $this->generate_token_on_run_time($user_data['parent_id'],$post['attended_doctor'],$booking_date,$specialization_id);
                    
                    
                        $data_token=array(
    				           'branch_id'=>$user_data['parent_id'],
    							'patient_id'=>$patient_id,
    							'doctor_id'=>$post['attended_doctor'],
    							'booking_date'=>date("Y-m-d", strtotime($post['appointment_date']) ),
    							'token_no'=>$new_token,
    							 'type'=>$token_type,
    							'created_date'=>date('Y-m-d H:i:s'));
                    //print_r($data_token);die;
                    
                    $this->db->insert('hms_patient_to_token',$data_token);
                    
                    $this->db->set('token_no',$new_token);
	            }
			   	$appointment_code = generate_unique_id(20);
			   	$this->db->set('patient_id',$patient_id);
			   	$this->db->set('appointment_code',$appointment_code);
			   	$this->db->set('appointment',1);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$appointment_data);    
	            $booking_id = $this->db->insert_id(); 
	            //echo $this->db->last_query(); exit;
	            
	            
                    
        }

		return $booking_id; 	
	}
	public function save20220326()
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
        //available time schedule
        $available_time_value='';
        if(!empty($post['available_time']))
        {
        	$available_time_value = $this->get_available_time_value($post['available_time']);
        }
        $insurance_type='';
   		if(isset($post['insurance_type']))
   		{
   			$insurance_type = $post['insurance_type'];
   		}
   		$insurance_type_id='';
   		if(isset($post['insurance_type_id']))
   		{
   			$insurance_type_id = $post['insurance_type_id'];
   		}
   		$ins_company_id='';
   		if(isset($post['ins_company_id']))
   		{
   			$ins_company_id = $post['ins_company_id'];
   		}
   		$doctor_slot='';
   		
   		if(!empty($post['doctor_slot']))
		{
			$doctor_slot = $post['doctor_slot'];
   		}
   		
   		if(!empty($post['doctor_slot']))
   		{
   		    
   		
   		    $appointment_time = $post['doctor_slot'];
   		}
   		else
   		{
   		    $appointment_time=$post['appointment_time'];
   		}
   	
        $data_patient = array(    
							'simulation_id'=>$post['simulation_id'],
							'branch_id'=>$branch_id,
							'patient_name'=>$post['patient_name'],
							'mobile_no'=>$post['mobile_no'],
							'gender'=>$post['gender'],
							'age_y'=>$post['age_y'],
							'age_m'=>$post['age_m'],
							'age_d'=>$post['age_d'],
							'relation_type'=>$post['relation_type'],
							'relation_name'=>$post['relation_name'],
							//'relation_simulation_id'=>$post['relation_simulation_id'],
							'patient_email'=>$post['patient_email'], 
							'address'=>$post['address'],
							'city_id'=>$post['city_id'],
							'state_id'=>$post['state_id'],
							'country_id'=>$post['country_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$user_data['id'],
							'adhar_no'=>$post['adhar_no'], 
							'address2'=>$post['address_second'],
							'address3'=>$post['address_third'],
							//insurance
							"insurance_type"=>$insurance_type,
							"insurance_type_id"=>$insurance_type_id,
							"ins_company_id"=>$ins_company_id,
							"polocy_no"=>$post['polocy_no'],
							"tpa_id"=>$post['tpa_id'],
							"ins_amount"=>$post['ins_amount'],
							"ins_authorization_no"=>$post['ins_authorization_no'],

					    	);
		$appointment_data = array(
							
							'branch_id'=>$branch_id,
							'diseases'=>$post['diseases'],	    
							'appointment_date'=>date('Y-m-d',strtotime($post['appointment_date'])),
							'appointment_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$appointment_time)), 
							'type'=>1,
							'specialization_id'=>$post['specialization'],
							'referred_by'=>$post['referred_by'],
							'attended_doctor'=>$post['attended_doctor'],
							'referral_doctor'=>$post['referral_doctor'],
							'referral_hospital'=>$post['referral_hospital'],
							'ref_by_other'=>$post['ref_by_other'],
							//'booking_date'=>date('Y-m-d H:i:s'),
							'booking_date'=>date('Y-m-d',strtotime($post['appointment_date'])),
							'booking_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$appointment_time)), 
							'booking_status'=>'0',
							'source_from'=>$post['source_from'],
							'remarks'=>$post['remarks'],
							'available_time'=>$post['available_time'],
							'time_value'=>$available_time_value,
							'doctor_slot'=>$doctor_slot,
							'remarks'=>$post['remarks'],
							'opd_type'=>$post['opd_type'],
							'pannel_type'=>$post['pannel_type'],
					);
		
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			
			if(!empty($post['patient_id']) && $post['patient_id']>0)
		    {
		    	$this->db->where('id',$post['patient_id']);
				$this->db->update('hms_patient',$data_patient); 
				//echo $this->db->last_query(); exit;
		    }
			$booking_id = $post['data_id'];
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$appointment_data); 
			//echo $this->db->last_query(); exit;


		}
		else
		{   
			    if(!empty($post['patient_id']) && $post['patient_id']>0)
			    {
			    	$patient_id = $post['patient_id'];
			    } 
			    else
			    {
			    	$patient_code = generate_unique_id(4,$branch_id);
			    	$this->db->set('patient_code',$patient_code);
			    	$this->db->set('created_date',date('Y-m-d H:i:s'));
			    	$this->db->insert('hms_patient',$data_patient);  
			    	$patient_id = $this->db->insert_id(); 
			    	//users create
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
					/*$this->db->select('*');
					$this->db->where('users_role','4');
					$query = $this->db->get('hms_permission_to_role');     
					$permission_list = $query->result();
					if(!empty($permission_list))
					{
					foreach($permission_list as $permission)
					{
					$data = array(
					        'users_role' =>4,
					        'users_id' => $users_id,
					        'master_id' => $patient_id,
					        'section_id' => $permission->section_id,
					        'action_id' => $permission->action_id, 
					        'permission_status' => '1',
					        'ip_address' => $_SERVER['REMOTE_ADDR'],
					        'created_by' =>$user_data['id'],
					        'created_date' =>date('Y-m-d H:i:s'),
					     );
					$this->db->insert('hms_permission_to_users',$data);
					}
					}*/

					////////// Send SMS /////////////////////
					if(in_array('640',$user_data['permission']['action']))
					{
					if(!empty($post['mobile_no']))
					{
						send_sms('patient_registration',18,$post['patient_name'],$post['mobile_no'],array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id));

					}
					}
					//////////////////////////////////////////

					////////// SEND EMAIL ///////////////////
					if(!empty($post['patient_email']))
					{ 
					$this->load->library('general_library'); 
					$this->general_library->email($post['patient_email'],'','','','','','patient_registration','18',array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id)); 
					}
			    	//echo $this->db->last_query(); 
			    }
			   	$appointment_code = generate_unique_id(20);
			   	$this->db->set('patient_id',$patient_id);
			   	$this->db->set('appointment_code',$appointment_code);
			   	$this->db->set('appointment',1);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$appointment_data);    
	            $booking_id = $this->db->insert_id(); 
	            //echo $this->db->last_query(); exit;
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
	

    public function delete_appointment($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->where('type',1);
			$this->db->update('hms_opd_booking');
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
			$this->db->update('hms_opd_booking');
    	} 
    }

    public function patient_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_opd_booking');
        $result = $query->result(); 
        return $result; 
    }


    public function referal_doctor_list($branch_id="")
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
		$result = $query->result(); 
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
		//$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
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


	public function opd_doctor_rate($doctor_id="",$ins_company_id='',$panel_type='0',$opd_type='0')
    {
		$consultants_charge = 0;
		$user_data = $this->session->userdata('auth_users');
		$branch_id = $user_data['parent_id'];
		$this->load->model('general/general_model');
         if($panel_type==1)
         {
         	$consultants_charge = $this->general_model->doctors_panel_charge($doctor_id,$ins_company_id,$branch_id,$opd_type);
         	
         }
         else
         {
         	  $doctor_data = $this->general_model->doctors_list($doctor_id,$branch_id);
           
            if($opd_type==1)
            {
               
               $consultants_charge = $doctor_data[0]->emergency_charge; 
            }
            else
            {
              $consultants_charge = $doctor_data[0]->consultant_charge;
            }
            
            //echo "<pre>";print_r($doctor_data); exit;
         }



       /*if(isset($doctor_id) && !empty($doctor_id) && $panel_type==1 && !empty($ins_company_id))
       {
         $this->load->model('general/general_model');
         $rate_list = $this->general_model->get_consultant_charge($doctor_id);
         if(!empty($rate_list->consultant_charge))
         {
         	$total_amount = $rate_list->consultant_charge;		
         }
         
         
         
       }*/
       return $consultants_charge;
    }
   public function confirm_booking()
   {
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
       // echo "<pre>"; print_r($post); exit;
		$attended_doctor=$post['attended_doctor'];
		$specialization_id = $post['specialization_id'];
		if(!empty($post['booking_date']))
		{
		    $booking_date = date("Y-m-d", strtotime($post['booking_date']));
		}
		else
		{
		    $booking_date = date('Y-m-d');
		}
		//$booking_date = date("Y-m-d", strtotime($post['booking_date']) );
		$patient_id = $post['patient_id'];
		
		$booking_code = generate_unique_id(9);
		    /* Token Generation Start */
		$discount_result = $this->get_common_token_setting($user_data['parent_id']);    
		if($discount_result!=1)
		{
    		$token_type=$this->get_token_setting();
              $token_type=$token_type['type'];
            if($booking_date=='1970-01-01')
    		{
    		    $booking_date = date('Y-m-d');
    		}
    		$new_token = $this->generate_token_on_run_time($user_data['parent_id'],$attended_doctor,$booking_date,$specialization_id);
    		
              
    		/*   Token generation End */
		    $data = array( 
					'branch_id'=>$user_data['parent_id'],
					'booking_code'=>$booking_code,
					'consultants_charge'=>$post['total_amount'], 
					'total_amount'=>$post['total_amount'],
					'discount'=>$post['discount'],
					'net_amount'=>$post['net_amount'],
					'paid_amount'=>$post['paid_amount'],
					'balance'=>($post['net_amount']-$post['paid_amount']),
					'booking_status'=>1,
					'payment_mode'=>$post['payment_mode'],
					'confirm_date'=>date('Y-m-d H:i:s'),
					//'cheque_no'=>$post['cheque_no'],
					'type'=>2,
					'pay_now'=>1,
					'token_no'=>$new_token
		         );
		         
		}
		else
		{
		    $result_apt = $this->get_by_id($post['data_id']);
		    $apt_data = date('d-m-Y',strtotime($result_apt['appointment_date']));
		    //when date changes
		    if(strtotime($post['booking_date'])!=strtotime($apt_data))
		    {
		       $token_type=$this->get_token_setting();
               $token_type=$token_type['type'];
                if($booking_date=='1970-01-01')
        		{
        		    $booking_date = date('Y-m-d');
        		}
        		$new_token = $this->generate_token_on_run_time($user_data['parent_id'],$attended_doctor,$booking_date,$specialization_id); 
		    
		        $data = array( 
					'branch_id'=>$user_data['parent_id'],
					'booking_code'=>$booking_code,
					'consultants_charge'=>$post['total_amount'], 
					'total_amount'=>$post['total_amount'],
					'discount'=>$post['discount'],
					'net_amount'=>$post['net_amount'],
					'paid_amount'=>$post['paid_amount'],
					'balance'=>($post['net_amount']-$post['paid_amount']),
					'booking_status'=>1,
					'payment_mode'=>$post['payment_mode'],
					'confirm_date'=>date('Y-m-d H:i:s'),
					//'cheque_no'=>$post['cheque_no'],
					'type'=>2,
					'pay_now'=>1,
					'token_no'=>$new_token
					
		         );
		        
		        
		    }
		    else
		    {   //for same day
		        $data = array( 
					'branch_id'=>$user_data['parent_id'],
					'booking_code'=>$booking_code,
					'consultants_charge'=>$post['total_amount'], 
					'total_amount'=>$post['total_amount'],
					'discount'=>$post['discount'],
					'net_amount'=>$post['net_amount'],
					'paid_amount'=>$post['paid_amount'],
					'balance'=>($post['net_amount']-$post['paid_amount']),
					'booking_status'=>1,
					'payment_mode'=>$post['payment_mode'],
					'confirm_date'=>date('Y-m-d H:i:s'),
					//'cheque_no'=>$post['cheque_no'],
					'type'=>2,
					'pay_now'=>1,
					
		         );
		        
		    }
		    
		    
		    
		}

		if($post['schedule_type']==0)
		{
			$booking_time = '';
			if(!empty($post['booking_time']))
			{
				$booking_time = date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time']));
			}

			$data_schedule_type = array( 
					'booking_date'=>date('Y-m-d',strtotime($post['booking_date'])),
					'booking_time'=>$booking_time
					);
		}
		else
		{
			$data_schedule_type = array();

		}
		$data_testr = array_merge($data, $data_schedule_type);

		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('confirm_date',date('Y-m-d H:i:s'));
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$data_testr); 
            //echo $this->db->last_query(); exit;
			/*add sales banlk detail*/
			
			//for dr Kamal
			if($discount_result!=1)
		    {
    			$doctorids = $post['attended_doctor'];
    			if($user_data['parent_id']=='196' && empty($post['attended_doctor']))
    			{
    			    $doctorids = 7394;
    			}
			
			
    			$data_token=array(
    				           'branch_id'=>$user_data['parent_id'],
    							'patient_id'=>$patient_id,
    							'doctor_id'=>$doctorids,
    							'booking_date'=>date("Y-m-d", strtotime($post['booking_date']) ),
    							'token_no'=>$new_token,
    							 'type'=>$token_type,
    							'created_date'=>date('Y-m-d H:i:s')
    									);
    		    //print_r($data_token);die;
    			
    			$this->db->insert('hms_patient_to_token',$data_token); 
    			//echo $this->db->last_query(); die;
		    }

			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>11,'section_id'=>2));
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
	                'type'=>11,
	                'section_id'=>2,
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

		}

			$this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','2');
            $this->db->where('patient_id',$post['patient_id']);
            $this->db->delete('hms_payment'); 

           

            if(($post['net_amount']-$post['paid_amount'])=='0')
            {
            	$balance_c=1;
            }
            else
            {
            	$balance_c = ($post['net_amount']-$post['paid_amount']);
            }

            $payment_data = array(
                               'parent_id'=>$post['data_id'],
                               'branch_id'=>$user_data['parent_id'],
                               'doctor_id'=>$post['consultant'],
                               'section_id'=>'2',
                               'patient_id'=>$post['patient_id'],
                               'pay_mode'=>$post['payment_mode'],
                               'total_amount'=>$post['total_amount'],
                               'discount_amount'=>$post['discount'],
                               'net_amount'=>$post['net_amount'],
                               'paid_amount'=>$post['paid_amount'],
                               'credit'=>$post['net_amount'],
                               'debit'=>$post['paid_amount'],
                               'balance'=>$balance_c,
                               'created_by'=>$user_data['id'],
                               'created_date'=>date('Y-m-d H:i:s'),
            	             );
            $this->db->insert('hms_payment',$payment_data);
            //echo $this->db->last_query(); exit;

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
	                'type'=>11,
	                'section_id'=>4,
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
            return $post['data_id'];
			
   }
   public function confirm_booking20220326()
   {
   		
   		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
       // echo "<pre>"; print_r($post); exit;
		$attended_doctor=$post['attended_doctor'];
		$specialization_id = $post['specialization_id'];
		if(!empty($post['booking_date']))
		{
		    $booking_date = date("Y-m-d", strtotime($post['booking_date']));
		}
		else
		{
		    $booking_date = date('Y-m-d');
		}
		//$booking_date = date("Y-m-d", strtotime($post['booking_date']) );
		$patient_id = $post['patient_id'];
		$booking_code = generate_unique_id(9);
		    /* Token Generation Start */
		$token_type=$this->get_token_setting();
          $token_type=$token_type['type'];
        /*
		if($token_type=='0') //hospital wise token no
		{ 

			$patient_token_details_for_hospital=$this->get_patient_token_details_for_type_hospital($booking_date,$token_type);
			$token_no='1';
			if($patient_token_details_for_hospital['token_no']>'0')
			{
				$token_no=$patient_token_details_for_hospital['token_no']+1;
			}

		}
		elseif($token_type=='1') // doctor wise token no
		{
			$patient_token_details_for_doctor=$this->get_patient_token_details_for_type_doctor($attended_doctor,$booking_date,$token_type);
			$token_no='1';
			if($patient_token_details_for_doctor['token_no']>'0')
			{
				$token_no=$patient_token_details_for_doctor['token_no']+1;
			}

		}*/
		
		if($booking_date=='1970-01-01')
		{
		    $booking_date = date('Y-m-d');
		}
		$new_token = $this->generate_token_on_run_time($user_data['parent_id'],$attended_doctor,$booking_date,$specialization_id);
		
          
		/*   Token generation End */
		
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'booking_code'=>$booking_code,
					'consultants_charge'=>$post['total_amount'], 
					'total_amount'=>$post['total_amount'],
					'discount'=>$post['discount'],
					'net_amount'=>$post['net_amount'],
					'paid_amount'=>$post['paid_amount'],
					'balance'=>($post['net_amount']-$post['paid_amount']),
					'booking_status'=>1,
					'payment_mode'=>$post['payment_mode'],
					//'transaction_no'=>$post['transaction_no'],
					//'branch_name'=>$post['branch_name'],
					'confirm_date'=>date('Y-m-d H:i:s'),
					//'cheque_no'=>$post['cheque_no'],
					'type'=>2,
					'pay_now'=>1,
					'token_no'=>$new_token
		         );

		if($post['schedule_type']==0)
		{
			$booking_time = '';
			if(!empty($post['booking_time']))
			{
				$booking_time = date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time']));
			}

			$data_schedule_type = array( 
					'booking_date'=>date('Y-m-d',strtotime($post['booking_date'])),
					'booking_time'=>$booking_time
					);
		}
		else
		{
			$data_schedule_type = array();

		}
		$data_testr = array_merge($data, $data_schedule_type);

		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('confirm_date',date('Y-m-d H:i:s'));
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$data_testr); 
            //echo $this->db->last_query(); exit;
			/*add sales banlk detail*/
			
			//for dr Kamal
			$doctorids = $post['attended_doctor'];
			if($user_data['parent_id']=='196' && empty($post['attended_doctor']))
			{
			    $doctorids = 7394;
			}
			
			
			$data_token=array(
				           'branch_id'=>$user_data['parent_id'],
							'patient_id'=>$patient_id,
							'doctor_id'=>$doctorids,
							'booking_date'=>date("Y-m-d", strtotime($post['booking_date']) ),
							'token_no'=>$new_token,
							 'type'=>$token_type,
							'created_date'=>date('Y-m-d H:i:s')
									);
		//print_r($data_token);die;
			
			$this->db->insert('hms_patient_to_token',$data_token); 
			//echo $this->db->last_query(); die;

			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>11,'section_id'=>2));
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
	                'type'=>11,
	                'section_id'=>2,
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

		}

			$this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','2');
            $this->db->where('patient_id',$post['patient_id']);
            $this->db->delete('hms_payment'); 

           

            if(($post['net_amount']-$post['paid_amount'])=='0')
            {
            	$balance_c=1;
            }
            else
            {
            	$balance_c = ($post['net_amount']-$post['paid_amount']);
            }

            $payment_data = array(
                               'parent_id'=>$post['data_id'],
                               'branch_id'=>$user_data['parent_id'],
                               'doctor_id'=>$post['consultant'],
                               'section_id'=>'2',
                               'patient_id'=>$post['patient_id'],
                               'pay_mode'=>$post['payment_mode'],
                               'total_amount'=>$post['total_amount'],
                               'discount_amount'=>$post['discount'],
                               'net_amount'=>$post['net_amount'],
                               'paid_amount'=>$post['paid_amount'],
                               'credit'=>$post['net_amount'],
                               'debit'=>$post['paid_amount'],
                               'balance'=>$balance_c,
                               'created_by'=>$user_data['id'],
                               'created_date'=>date('Y-m-d H:i:s'),
            	             );
            $this->db->insert('hms_payment',$payment_data);
            //echo $this->db->last_query(); exit;

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
	                'type'=>11,
	                'section_id'=>4,
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
            return $post['data_id'];
			
   }
   
   public function generate_token_on_run_time($branch_id,$doctor_id,$booking_date,$specilization_id)
    {

       
       $token_no='';
       if(isset($branch_id) && !empty($booking_date))
       {
         
         $booking_date = date("Y-m-d", strtotime($booking_date)); 
         $token_type_data=$this->get_token_setting();
         $token_type=$token_type_data['type'];
         if($token_type=='0') //hospital wise token no
         { 
            
           $patient_token_details_for_hospital=$this->get_patient_token_details_for_type_hospital($booking_date,$token_type);
         //print_r($patient_token_details_for_hospital);die;
          if($patient_token_details_for_hospital['token_no']>'0')
          {
            $token_no=$patient_token_details_for_hospital['token_no']+1;
            //echo $token_no;die;
          }
          else
          {
            $token_no='1';
 
          } 
             
         }
         elseif($token_type=='1') // doctor wise token no
         {
           // echo "hi";die;
            $patient_token_details_for_doctor=$this->get_patient_token_details_for_type_doctor($doctor_id,$booking_date,$token_type);
                  if($patient_token_details_for_doctor['token_no']>'0')
                  {
                    //echo "hi";die;
                    $token_no=$patient_token_details_for_doctor['token_no']+1;
                  }
                  else
                  {
                    //echo "hi";die;
                    $token_no='1';
        
                  }

         }
         elseif($token_type=='2') // specialization wise token no
         {
           // echo "hi";die;
            $patient_token_details_for_specialization=$this->get_patient_token_details_for_type_specialization($specilization_id,$booking_date,$token_type);
              //  print_r($patient_token_details_for_doctor);die;

          if($patient_token_details_for_specialization['token_no']>'0')
          {
            $token_no=$patient_token_details_for_specialization['token_no']+1;
          }
          else
          {
            $token_no='1';
          }

         }
         else
         {

         }
         
         return $token_no;
         /*$pay_arr = array('token_no'=>$token_no);
         $json = json_encode($pay_arr,true);
         echo $json;*/
         
       }
    }
    
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
	   //echo $this->db->last_query();
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
   public function get_patient_token_details_for_type_doctor_old($id='',$booking_date='',$type='')
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

     public function get_patient_token_details_for_type_hospital_old($booking_date='',$type='')
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
		$result['appointment_code'] = generate_unique_id(20,$branch_id);
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

		$drop = '<select name="simulation" id="simulation" onchange="find_gender(this.value)">
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

	public function get_referral_hospital_data($branch_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_hospital.*');  
		$this->db->where('hms_hospital.branch_id',$branch_id);  
		$this->db->where('hms_hospital.is_deleted',0); 
		$query = $this->db->get('hms_hospital');
		$hospital_list = $query->result(); 
		
		$drop = '<select name="referral_hospital" id="referral_hospital">
		         <option value="">Select Hospital</option>';
		if(!empty($hospital_list))
		{
		    foreach($hospital_list as $hospital)
		    {
		        $drop .= '<option value="'.$hospital->id.'">'.$hospital->hospital_name.'</option>';
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



    function get_all_detail_print($ids=""){
    	$result_opd=array();
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_opd_booking.*,hms_patient.*,hms_users.*,hms_packages.title as package_name,hms_doctors.consultant_charge"); 
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id','left');
		$this->db->join('hms_packages','hms_packages.id = hms_opd_booking.package_id','left');
		$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking. attended_doctor','left');
		$this->db->join('hms_users','hms_users.id = hms_opd_booking.created_by');  
		$this->db->where('hms_opd_booking.is_deleted','0'); 
		$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->where('hms_opd_booking.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_opd['opd_list']= $this->db->get()->result();
		
		$this->db->select('hms_opd_booking_to_particulars.*');
		$this->db->join('hms_opd_booking','hms_opd_booking.id = hms_opd_booking_to_particulars.booking_id'); 
		$this->db->where('hms_opd_booking_to_particulars.booking_id = "'.$ids.'"');
		$this->db->from('hms_opd_booking_to_particulars');
		$result_opd['opd_list']['particular_list']=$this->db->get()->result();

		return $result_opd;
		
    }

    function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	$this->db->where('hms_print_branch_template.branch_id = "'.$users_data['parent_id'].'"'); 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	return $query;

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

    

    

    public function diseases_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		
                $this->db->where('hms_disease.status',1);
		$this->db->where('hms_disease.is_deleted',0); 
		$this->db->where('hms_disease.branch_id',$users_data['parent_id']);
                 $this->db->order_by('hms_disease.disease','ASC');  
		$query = $this->db->get('hms_disease');
                
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }
    


    function search_opd_data()
    {
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_opd_booking.*,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.gender,hms_patient.patient_code");
		$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','inner');
                $this->db->join('hms_disease','hms_disease.id=hms_opd_booking.diseases','left');
		$this->db->where('hms_opd_booking.is_deleted','0'); 
		


		$search = $this->session->userdata('appointment_search');
		if($user_data['users_role']==4)
		{
			if(isset($search) && !empty($search))
			{
			$this->db->where('hms_opd_booking.patient_id = "'.$search['branch_id'].'"');
			}
			else
			{
			$this->db->where('hms_opd_booking.patient_id = "'.$user_data['parent_id'].'"');	
			}
		}
		elseif($user_data['users_role']==3)
		{
			$this->db->where('hms_opd_booking.referral_doctor = "'.$user_data['parent_id'].'"');
		}
		else
		{
			if(isset($search) && !empty($search))
			{
			$this->db->where('hms_opd_booking.branch_id = "'.$search['branch_id'].'"');
			}
			else
			{
			$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');	
			}
		}
		
		//$this->db->where('hms_opd_booking.type=1');
		$this->db->where('hms_opd_booking.appointment =1');
		if($search['app_status']=='1') //pending
		{
		    $this->db->where('hms_opd_booking.type =1');
		}
		elseif($search['app_status']=='2') //confirm
		{
		   $this->db->where('hms_opd_booking.type =1'); 
		}
		elseif($search['app_status']=='3')
		{
		    $this->db->where('(hms_opd_booking.type =1 OR hms_opd_booking.type =2)'); 
		}
		else
		{
		  $this->db->where('hms_opd_booking.type =1');  
		}
		//$this->db->from($this->table); 
		/////// Search query end //////////////
		
		if(isset($search) && !empty($search))
		{
			
             if(!empty($search['appointment_code']))
			{
				$this->db->where('hms_opd_booking.appointment_code >= "'.$search['appointment_code'].'"');
			}

            if(!empty($search['appointment_from_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['appointment_from_date'])).' 00:00:00';
				$this->db->where('hms_opd_booking.appointment_date >= "'.$start_date.'"');
			}

			if(!empty($search['appointment_end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['appointment_end_date'])).' 23:59:59';
				$this->db->where('hms_opd_booking.appointment_date <= "'.$end_date.'"');
			}

			if(!empty($search['start_time']))
			{
				$start_time = date('H:i:s',strtotime($search['start_time']));
				$this->db->where('hms_opd_booking.appointment_time >= "'.$start_time.'"');
			}

			if(!empty($search['end_time']))
			{
				$end_time = date('H:i:s',strtotime($search['end_time']));
				$this->db->where('hms_opd_booking.appointment_time <= "'.$end_time.'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}

			if(!empty($search['attended_doctor']))
			{
				$this->db->where('hms_opd_booking.attended_doctor',$search['attended_doctor']);
			}

			if(!empty($search['referral_doctor']))
			{
				$this->db->where('hms_opd_booking.referral_doctor',$search['referral_doctor']);
			}

			if(!empty($search['specialization']))
			{
				$this->db->where('hms_opd_booking.specialization',$search['specialization']);
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
				$this->db->where('hms_opd_booking.status',$search['status']);
			}
			if(isset($search['booking_status']) && $search['booking_status']!="")
			{
				$this->db->where('hms_opd_booking.booking_status',$search['booking_status']);
			}
                        if(!empty($search['disease']))
			{
				$this->db->where('hms_disease.disease= "'.$search['disease'].'"');
			}
			if(!empty($search['disease_code']))
			{
				$this->db->where('hms_disease.disease_code= "'.$search['disease_code'].'"');
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
			$this->db->where('hms_opd_booking.created_by IN ('.$emp_ids.')');
		}
	    $query = $this->db->get('hms_opd_booking'); 
	    //echo $this->db->last_query(); exit;
		$data= $query->result();
		
		return $data;
	}


	public function source_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_patient_source.source','ASC');
                $this->db->where('hms_patient_source.status',1);
		$this->db->where('hms_patient_source.is_deleted',0); 
		$this->db->where('hms_patient_source.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_patient_source');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    public function get_doctor_available_days($doctor_id='',$booking_date='')
    {
    	
    	$users_data = $this->session->userdata('auth_users'); 
		
		$date = date('Y-m-d',strtotime($booking_date));
        $day_name = date("l", strtotime($date));
		 
		$this->db->select('hms_doctors_schedule.*,hms_days.day_name');
        $this->db->join('hms_doctors','hms_doctors.id = hms_doctors_schedule.doctor_id');
		$this->db->join('hms_days','hms_days.id = hms_doctors_schedule.available_day');
		$this->db->where('hms_doctors_schedule.doctor_id = "'.$doctor_id.'"'); 
		//$this->db->where('hms_days.day_name = "'.$day_name.'"'); 
        $this->db->group_by('hms_doctors_schedule.available_day');
        $query = $this->db->get('hms_doctors_schedule');
        $result = $query->result();
        //echo $this->db->last_query();
		
		$num = $query->num_rows();
		//exit;
		if($num > 0)
		{
			//check day
			$this->db->select('hms_doctors_schedule.*,hms_days.day_name');
	        $this->db->join('hms_doctors','hms_doctors.id = hms_doctors_schedule.doctor_id');
			$this->db->join('hms_days','hms_days.id = hms_doctors_schedule.available_day');
			$this->db->where('hms_doctors_schedule.doctor_id = "'.$doctor_id.'"'); 
			$this->db->where('hms_days.day_name = "'.$day_name.'"'); 
	        //$this->db->group_by('hms_doctors_schedule.available_day');
	        $query = $this->db->get('hms_doctors_schedule');
	        //echo $this->db->last_query(); exit;
	        $result = $query->result();
			$num_total = $query->num_rows();
			if($num_total>0)
			{
				return 1;	
			}
			else
			{
				return 0;
			}	
			
		}
		else
		{
			return 2;
		}
		
			
    }


    public function get_common_token_setting($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $status=0;
        if(!empty($branch_id))
        {
            $this->db->select('hms_common_token_setting.status');  
            $this->db->where('hms_common_token_setting.branch_id',$branch_id); 
            $query = $this->db->get('hms_common_token_setting');
            $result = $query->row(); 
            if(!empty($result->status))
            {
                $status = $result->status;    
            }
        }    
            return $status; 
      
    }


    
} 
?>