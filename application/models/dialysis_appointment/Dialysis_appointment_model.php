<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_appointment_model extends CI_Model {

	var $table = 'hms_dialysis_appointment';
	
	var $column = array(
		'hms_dialysis_appointment.id',
		'hms_patient.patient_name',
		'hms_patient.mobile_no',
		'hms_dialysis_appointment.booking_code',
		'hms_patient.patient_code',
		'hms_patient.gender',
		'hms_patient.address',
		'hms_patient.father_husband',
		'hms_dialysis_appointment.dialysis_room_no',
		'hms_dialysis_appointment.dialysis_name',
		'hms_dialysis_appointment.dialysis_date',
		'hms_dialysis_appointment.dialysis_time',
		'hms_dialysis_appointment.referred_by',
		
		
		'hms_dialysis_appointment.created_date',
		'hms_dialysis_appointment.modified_date',
		'hms_dialysis_appointment.created_by'
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
		
		$this->db->select("hms_dialysis_appointment.*,hms_doctors.doctor_name, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code,hms_patient.mobile_no,(CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband,hms_ot_room.room_no as ot_room,sim.simulation as father_husband_simulation,hms_dialysis_pacakge.amount as pack_amount,(CASE WHEN hms_dialysis_appointment.dialysis_type=1 THEN hms_dialysis_management.name ELSE hms_dialysis_pacakge.name END) as ot_pack_name,

			(CASE WHEN hms_dialysis_appointment.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_dialysis_appointment.   referral_doctor=0 THEN concat('Other ',hms_dialysis_appointment.ref_by_other) ELSE concat('Dr. ',docs.doctor_name) END) END) as doctor_hospital_name"); 
		//hms_specialization.specialization,,hms_payment_mode.payment_mode
		$this->db->from($this->table); 
     
		$this->db->join('hms_dialysis_pacakge','hms_dialysis_pacakge.id=hms_dialysis_appointment.package_id','left');
		$this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_appointment.dialysis_name','left');
		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_appointment.patient_id','left');
        $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_appointment.doctor_id','left');
       	$this->db->join('hms_ot_room','hms_ot_room.id=hms_dialysis_appointment.dialysis_room_no','left');

		//$this->db->join('hms_specialization','hms_specialization.id=hms_dialysis_appointment.specialization_id', 'left');
		$this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');

		$this->db->join('hms_hospital','hms_hospital.id = hms_dialysis_appointment.referral_hospital','left');
        $this->db->join('hms_doctors as docs','docs.id = hms_dialysis_appointment.referral_doctor','left');

        //$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_dialysis_appointment.mode_of_payment', 'left');


        $this->db->where('hms_dialysis_appointment.is_deleted','0');
        if($users_data['users_role']==4)
		{
		$this->db->where('hms_dialysis_appointment.patient_id = "'.$users_data['parent_id'].'"');
		}
		elseif($users_data['users_role']==3)
		{
			$this->db->where('hms_dialysis_appointment.referral_doctor = "'.$users_data['parent_id'].'"');
		}
		else
		{
		$this->db->where('hms_dialysis_appointment.branch_id = "'.$users_data['parent_id'].'"');	
		}

		$i = 0;

		if(isset($search) && !empty($search))
		{

		if(isset($search['start_date']) && !empty($search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($search['start_date']));
		$this->db->where('hms_dialysis_appointment.dialysis_date >= "'.$start_date.'"');
		}

		if(isset($search['end_date']) &&  !empty($search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($search['end_date']));
		$this->db->where('hms_dialysis_appointment.dialysis_date <= "'.$end_date.'"');
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
			
			if(isset($search['appointment_no']) &&  !empty($search['appointment_no']))
			{
			$this->db->where('hms_dialysis_appointment.booking_code = "'.$search['appointment_no'].'"');
			}
			
			

			if(isset($search['dialysis_time']) &&  !empty($search['dialysis_time']))
			{
			$this->db->where('hms_dialysis_appointment.dialysis_time = "'.$search['dialysis_time'].'"');
			}
			if(isset($search['dialysis_date']) &&  !empty($search['dialysis_date']))
			{
			$this->db->where('hms_dialysis_appointment.dialysis_date = "'.date('Y-m-d',strtotime($search['dialysis_date'])).'"');
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
			  $this->db->where('hms_dialysis_appointment.created_by IN ('.$emp_ids.')');
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
		
		$this->db->select("hms_dialysis_appointment.*, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code,hms_patient.mobile_no,(CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband,hms_ot_room.room_no as ot_room,sim.simulation as father_husband_simulation,hms_dialysis_pacakge.amount as pack_amount,(CASE WHEN hms_dialysis_appointment.dialysis_type=1 THEN hms_dialysis_management.name ELSE hms_dialysis_pacakge.name END) as ot_pack_name,

			(CASE WHEN hms_dialysis_appointment.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_dialysis_appointment.   referral_doctor=0 THEN concat('Other ',hms_dialysis_appointment.ref_by_other) ELSE concat('Dr. ',docs.doctor_name) END) END) as doctor_hospital_name");
		//hms_specialization.specialization,hms_payment_mode.payment_mode
		$this->db->from($this->table); 
       

		$this->db->join('hms_dialysis_pacakge','hms_dialysis_pacakge.id=hms_dialysis_appointment.package_id','left');
		$this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_appointment.dialysis_name','left');
		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_appointment.patient_id','left');
        $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_appointment.doctor_id','left');
        
		$this->db->join('hms_ot_room','hms_ot_room.id=hms_dialysis_appointment.dialysis_room_no','left');

		//$this->db->join('hms_specialization','hms_specialization.id=hms_dialysis_appointment.specialization_id', 'left');
		$this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');

		$this->db->join('hms_hospital','hms_hospital.id = hms_dialysis_appointment.referral_hospital','left');
        $this->db->join('hms_doctors as docs','docs.id = hms_dialysis_appointment.referral_doctor','left');

        //$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_dialysis_appointment.mode_of_payment', 'left');

        $this->db->where('hms_dialysis_appointment.is_deleted','0');
        if($users_data['users_role']==4)
		{
		$this->db->where('hms_dialysis_appointment.patient_id = "'.$users_data['parent_id'].'"');
		}
		elseif($users_data['users_role']==3)
		{
			$this->db->where('hms_dialysis_appointment.referral_doctor = "'.$users_data['parent_id'].'"');
		}
		else
		{
		$this->db->where('hms_dialysis_appointment.branch_id = "'.$users_data['parent_id'].'"');	
		}
		$i = 0;

		if(isset($search) && !empty($search))
		{

				if(isset($search['start_date']) && !empty($search['start_date']))
				{
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_dialysis_appointment.dialysis_date >= "'.$start_date.'"');
				}

				if(isset($search['end_date']) &&  !empty($search['end_date']))
				{
				$end_date = date('Y-m-d',strtotime($search['end_date']));
				$this->db->where('hms_dialysis_appointment.dialysis_date <= "'.$end_date.'"');
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
		          if(isset($search['appointment_no']) &&  !empty($search['appointment_no']))
    			{
    			$this->db->where('hms_dialysis_appointment.booking_code = "'.$search['appointment_no'].'"');
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
			  $this->db->where('hms_dialysis_appointment.created_by IN ('.$emp_ids.')');
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
		$this->db->from($this->table);
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
    	$query = $this->db->get('hms_dialysis_appointment');
		return $query->result();
    }*/

	public function get_by_id($id)
	{
		$this->db->select('hms_dialysis_appointment.*,hms_dialysis_pacakge.name as package_name,,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.adhar_no,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id');
		$this->db->from('hms_dialysis_appointment'); 
		$this->db->where('hms_dialysis_appointment.id',$id);
		
		
		$this->db->join('hms_dialysis_pacakge','hms_dialysis_pacakge.id=hms_dialysis_appointment.package_id','left');
		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_appointment.patient_id','left');
		$this->db->where('hms_dialysis_appointment.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query(); exit;
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
								'city_id'=>$post['city_id'],
                                'state_id'=>$post['state_id'],
                                'country_id'=>$post['country_id'],
                                'mobile_no'=>$post['mobile_no'],
                                "insurance_type"=>$insurance_type,
                                "insurance_type_id"=>$insurance_type_id,
                                "ins_company_id"=>$ins_company_id,
                                "polocy_no"=>$post['polocy_no'],
                                "tpa_id"=>$post['tpa_id'],
                                "ins_amount"=>$post['ins_amount'],
                                "ins_authorization_no"=>$post['ins_authorization_no'],
							
								);
		if(!empty($post['referral_hospital']))
		{
		    $referral_hospital = $post['referral_hospital'];
		}
		else
		{
		    $referral_hospital=0;
		}
		
		$available_time_value='';
        if(!empty($post['available_time']))
        {
         	$available_time_value = $this->get_available_time_value($post['available_time']);
        
        }
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			
			$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$post['patient_id'],
					'schedule_id'=>$post['schedule_id'],
					'dialysis_end_time'=>$time,
					'dialysis_name'=>$post['dialysis_name'],
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'doctor_slot'=>$post['doctor_slot'],
					'available_time'=>$post['available_time'],
					'time_value'=>$available_time_value,
					'dialysis_booking_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['doctor_slot'])),
					'remarks'=>$post['remarks'],
					'referred_by'=>$post['referred_by'],
					'referral_doctor'=>$post['referral_doctor'],
					'referral_hospital'=>$referral_hospital,
					'ref_by_other'=>$post['ref_by_other'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		     //print_r($data);die; dialysis_name  dialysis_room_no

			

			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id', $post['patient_id']);
			$this->db->update('hms_patient',$data_patient);


			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_dialysis_appointment',$data);  
			//echo $this->db->last_query();die;

			$dialysis_book_id=$post['data_id'];

		}
		else
		{    
			   $dialysis_booking_no = generate_unique_id(74);
			   $patient_reg_code=generate_unique_id(4); 
			   $patient_data= $this->get_patient_by_id($post['patient_id']);

			if(count($patient_data)>0)
			{
				$this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
				$this->db->where('id',$post['patient_id']);
				$this->db->update('hms_patient',$data_patient);
				$patient_id= $post['patient_id'];
				$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$patient_id,
					'booking_code'=>$dialysis_booking_no,
					'dialysis_name'=>$post['dialysis_name'],
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_end_time'=>$time,
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time'])),
					'dialysis_booking_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'remarks'=>$post['remarks'],
					'referred_by'=>$post['referred_by'],
					'referral_doctor'=>$post['referral_doctor'],
					'referral_hospital'=>$referral_hospital,
					'ref_by_other'=>$post['ref_by_other'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		
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
			$refere_by_pther='';
			if(!empty($post['ref_by_other']))
			{
			   $refere_by_pther = $post['ref_by_other'];
			}
				$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$patient_id,
					'schedule_id'=>$post['schedule_id'],
					'booking_code'=>$dialysis_booking_no,
					'dialysis_name'=>$post['dialysis_name'],
					'dialysis_room_no'=>$post['dialysis_room'],
					'dialysis_end_time'=>$time,
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'doctor_slot'=>$post['doctor_slot'],
					'available_time'=>$post['available_time'],
							'time_value'=>$available_time_value,
					'dialysis_booking_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['doctor_slot'])), //$post['dialysis_time']
					'remarks'=>$post['remarks'], 
					'referred_by'=>$post['referred_by'],
					'referral_doctor'=>$post['referral_doctor'],
					'referral_hospital'=>$referral_hospital,
					'ref_by_other'=>$refere_by_pther,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		
			}

			
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dialysis_appointment',$data); 
			$last_id= $this->db->insert_id(); //die;
			//echo $this->db->last_query();die;
			
			$dialysis_book_id=$last_id;       
		} 
		//echo $dialysis_book_id;die;
		return $dialysis_book_id;	
	}
	
	public function get_available_time_value($time_id='')
	{
		$value="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($time_id))
        {
            $this->db->select('hms_dialysis_schedule_time.*');  
            $this->db->where('hms_dialysis_schedule_time.id',$time_id); 
            $query = $this->db->get('hms_dialysis_schedule_time');
            $result = $query->row(); 
           
             $value =  date("g:i A", strtotime($result->from_time)).' - '.date("g:i A", strtotime($result->to_time)); 
            //echo $this->db->last_query();die;
        }    
            return $value; 
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
			$this->db->update('hms_dialysis_appointment');
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
			$this->db->update('hms_dialysis_appointment');
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
	        $query = $this->db->get('hms_dialysis_appointment');
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

		$this->db->select('hms_patient.*,hms_patient.id as p_id');
		$this->db->from('hms_patient'); 
		$this->db->where('hms_patient.id',$id);
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

      function get_all_detail_print($ids="",$branch_ids=""){
      	$result_operation=array();
    	$this->db->select('hms_dialysis_appointment.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_dialysis_pacakge.remarks as pacakge_remarks,hms_dialysis_pacakge.type as pacakge_type,hms_dialysis_pacakge.amount as package_amount,hms_dialysis_pacakge.days,hms_dialysis_pacakge.name as package_name,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_simulation.simulation,hms_gardian_relation.relation,hms_patient.relation_simulation_id,hms_patient.relation_type,hms_patient.relation_name,hms_dialysis_type.dialysis_type
    		,dia_type.dialysis_type as dia_type,hms_dialysis_management.amount as dia_amount,hms_dialysis_management.hours,hms_dialysis_management.name as dia_name
    		');
    	//(CASE WHEN hms_dialysis_appointment.dialysis_type =1 THEN   ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name
		$this->db->from('hms_dialysis_appointment'); 
		$this->db->where('hms_dialysis_appointment.id',$ids);
		$this->db->where('hms_dialysis_appointment.branch_id',$branch_ids);
		$this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_appointment.dialysis_name','left');

		$this->db->join('hms_dialysis_type as dia_type','dia_type.id=hms_dialysis_management.type','left');
		

		
		
		$this->db->join('hms_dialysis_pacakge','hms_dialysis_pacakge.id=hms_dialysis_appointment.package_id','left');
		$this->db->join('hms_dialysis_type','hms_dialysis_type.id=hms_dialysis_pacakge.type','left');

		$this->db->join('hms_patient','hms_patient.id=hms_dialysis_appointment.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
		$this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
		$this->db->join('hms_users','hms_users.id = hms_dialysis_appointment.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		$this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		
		$this->db->where('hms_dialysis_appointment.is_deleted','0');
		$query = $this->db->get(); 
		$result_operation['dialysis_list']= $query->result();
          //print '<pre>'; print_r($result_operation);die;
		$this->db->select('hms_dialysis_to_doctors.*'); 
		$this->db->where('hms_dialysis_to_doctors.dialysis_id = "'.$ids.'"');
		$this->db->from('hms_dialysis_to_doctors');
		$result_operation['dialysis_list']['doctor_list']=$this->db->get()->result();
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
		$this->db->select('hms_dialysis_appointment.*,CONCAT(hms_dialysis_appointment.dialysis_date," ",hms_dialysis_appointment.dialysis_time) as ot_start_datetime, CONCAT(hms_dialysis_appointment.dialysis_date," ",hms_dialysis_appointment.dialysis_end_time) as ot_end_datetime ');
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
		//$this->db->where('ot_room_no',$dialysis_room);
		//$this->db->where('ot_start_datetime',$dialysis_date);
		//$this->db->where('dialysis_time',$dialysis_date);
$this->db->where('hms_dialysis_appointment.is_deleted',0);
		$this->db->from('hms_dialysis_appointment');
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
		$this->db->select('hms_dialysis_appointment.*,CONCAT(hms_dialysis_appointment.dialysis_date," ",hms_dialysis_appointment.dialysis_time) as ot_start_datetime, CONCAT(hms_dialysis_appointment.dialysis_date," ",hms_dialysis_appointment.dialysis_end_time) as ot_end_datetime ');
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
		$this->db->where('hms_dialysis_appointment.is_deleted',0);
		$this->db->from('hms_dialysis_appointment');
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
    
     public function get_schedule_available_days($schedule_id='',$booking_date='')
    {
    	
    	$users_data = $this->session->userdata('auth_users'); 
		
		$date = date('Y-m-d',strtotime($booking_date));
        $day_name = date("l", strtotime($date));
		 
		$this->db->select('hms_dialysis_schedule_time.*,hms_days.day_name');
        $this->db->join('hms_dialysis_schedule','hms_dialysis_schedule.id = hms_dialysis_schedule_time.schedule_id');
		$this->db->join('hms_days','hms_days.id = hms_dialysis_schedule_time.available_day');
		$this->db->where('hms_dialysis_schedule_time.schedule_id = "'.$schedule_id.'"'); 
		//$this->db->where('hms_days.day_name = "'.$day_name.'"'); 
        $this->db->group_by('hms_dialysis_schedule_time.available_day');
        $query = $this->db->get('hms_dialysis_schedule_time');
         // echo $this->db->last_query(); exit;
        $result = $query->result();
        //echo $this->db->last_query();
		
		$num = $query->num_rows();
		//exit;
		if($num > 0)
		{
			//check day
			$this->db->select('hms_dialysis_schedule_time.*,hms_days.day_name');
	        $this->db->join('hms_dialysis_schedule','hms_dialysis_schedule.id = hms_dialysis_schedule_time.schedule_id');
			$this->db->join('hms_days','hms_days.id = hms_dialysis_schedule_time.available_day');
			$this->db->where('hms_dialysis_schedule_time.schedule_id = "'.$schedule_id.'"'); 
			$this->db->where('hms_days.day_name = "'.$day_name.'"'); 
	        //$this->db->group_by('hms_doctors_schedule.available_day');
	        $query = $this->db->get('hms_dialysis_schedule_time');
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
    
    public function get_doctors_details($bookind_date,$time,$doctor_id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dialysis_appointment.*,hms_patient.id as patID,hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.adhar_no,hms_patient.patient_email,hms_doctors.doctor_name,hms_cities.city,hms_state.state,hms_countries.country,hms_patient.pincode,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob');
		$this->db->from('hms_dialysis_appointment'); 
		$this->db->join('hms_patient','hms_patient.id = hms_dialysis_appointment.patient_id');
		$this->db->join('hms_doctors','hms_doctors.id = hms_dialysis_appointment. attended_doctor','left');
		$this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left');
		$this->db->join('hms_state','hms_state.id = hms_patient.state_id','left');
		$this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); 
		
		$this->db->where('hms_dialysis_appointment.branch_id',$user_data['parent_id']); 

		if(!empty($doctor_id))
		{
			$this->db->where('hms_dialysis_appointment.attended_doctor',$doctor_id); 
		}		

		//$this->db->where('hms_opd_booking.appointment_date',$bookind_date);
		$end_date = date('Y-m-d',strtotime($bookind_date));
				$this->db->where('hms_dialysis_appointment.dialysis_date = "'.$end_date.'"');
		
		$start_time = date('H:i:s',strtotime($time));
		$this->db->where('hms_dialysis_appointment.dialysis_time = "'.$start_time.'"');
		//$this->db->where('hms_opd_booking.appointment_time',$time);
		$this->db->where('hms_dialysis_appointment.is_deleted','0');
		$query = $this->db->get();
		//echo $this->db->last_query(); //exit;
		return $query->row_array();
	}
	
	
   public function confirm_booking()
   {
   		
   		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
        //echo "<pre>"; print_r($post); exit;
		$attended_doctor=$post['attended_doctor'];
		$booking_date = date("Y-m-d", strtotime($post['booking_date']) );
		$dialysis_booking_no = generate_unique_id(34);
		$dialysis_time = date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time']));
		$data = array( 
				
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$post['patient_id'],
					'booking_code'=>$dialysis_booking_no,
					//$post['dialysis_booking_code'],
					'dialysis_room_no'=>0,
					'dialysis_type'=>0,
					'ipd_id'=>0,
					'dialysis_end_time'=>$time,
					'room_type_id'=>$post['room_id'],
                    'room_no_id'=>$post['room_no_id'],
                    'room_id'=>$post['room_no_id'],
                    'bad_id'=>$post['bed_no_id'],
                    'bed_no_id'=>$post['bed_no_id'],
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_booking_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time'])),
					'remarks'=>'',
					'referred_by'=>0,
					'referral_doctor'=>0,
					'referral_hospital'=>0,
					'ref_by_other'=>0,
					'paid_amount_dis_bill'=>0,
					'discount_amount_dis_bill'=>0,
					'total_amount_dis_bill'=>0,
					'procedure_remarks'=>0,
					'net_amount_dis_bill'=>0,
					'refund_amount_dis_bill'=>0,
					'balance_amount_dis_bill'=>0,
					'procedure_created_by'=>0,
					
					
					'no_of_visit'=>1,
					'no_of_visit_done'=>1,
					'advance_payment'=>$post['advance_amount'],
					'advance_payment_dis_bill'=>$post['advance_amount'],
					'payment_mode'=>$post['payment_mode'],
					'dialysis_name'=>$post['dialysis_name'],
                    'no_pf_visit_duration'=>0,
                    'no_pf_visit_unit'=>0,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
					
					);

		
    
            $created_date = date('Y-m-d',strtotime($post['dialysis_date']));
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dialysis_booking',$data); 
			//echo $this->db->last_query(); exit;
			$last_id= $this->db->insert_id(); //die;
         
         	//advance amount paid
            if(!empty($last_id) && !empty($post['advance_amount']) && $post['advance_amount']!='0.00') 
            {
                $advance_patient_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'dialysis_id'=>$last_id,
                    'patient_id'=>$post['patient_id'],
                    'type'=>2,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['dialysis_date'])).' '.date('H:i:s',strtotime($post['dialysis_time'])),
                    'payment_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
                    'particular'=>'Advance Payment',
                    'price'=>$post['advance_amount'],
                    'panel_price'=>$post['advance_amount'],
                    'net_price'=>$post['advance_amount'],
                    'status'=>1,
                    'created_date'=>$created_date
                );
                $this->db->insert('hms_dialysis_patient_to_charge',$advance_patient_charge);
                $adva_id =$this->db->insert_id();
                //echo $this->db->last_query(); exit;
                //referral_doctor
                $comission_arr = get_doc_hos_comission($post['referral_doctor'],$post['referral_hospital'],$post['advance_amount'],148);
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
                                    'total_amount'=>$post['advance_amount'],
                                    'net_amount'=>$post['advance_amount'],
                                    'paid_amount'=>$post['advance_amount'],
                                	'doctor_id'=>$referral_doctor,
				                    'hospital_id'=>$referral_hospital,
                                    'patient_id'=>$post['patient_id'],
                                    'credit'=>'0',//$post['advance_deposite'],
                                    'debit'=>$post['advance_amount'],//'',
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
    								'created_date'=>$created_date,
    								'radhey'=>$created_date,
                                    'created_by'=>$user_data['id']
                                 );
                $this->db->insert('hms_payment',$payment_data);
                $payment_id= $this->db->insert_id();
                //echo $this->db->last_query(); exit;


                /* genereate receipt number */
           
                if($post['advance_amount']>0)
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
            
            if(!empty($post['data_id']))
            {
                $this->db->set('type',1);
    			$this->db->set('confirm_date',date('Y-m-d H:i:s'));
    			$this->db->where('id', $post['data_id']);
    			$this->db->update('hms_dialysis_appointment');
                
            }

            return $last_id;
			
   }
   
   function get_schedule($branch_id)
	{
    	if(!empty($branch_id) && $branch_id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->where('branch_id',$branch_id);
			$this->db->where('is_deleted',0); 
			$result= $this->db->get('hms_dialysis_schedule')->result();
			return $result;
    	} 
    }
    
    public function get_schedule_details($bookind_date,$time,$slot_id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dialysis_appointment.*,hms_patient.id as patID,hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.adhar_no,hms_patient.patient_email,hms_doctors.doctor_name,hms_cities.city,hms_state.state,hms_countries.country,hms_patient.pincode,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob');
		$this->db->from('hms_dialysis_appointment'); 
		$this->db->join('hms_patient','hms_patient.id = hms_dialysis_appointment.patient_id');
		$this->db->join('hms_doctors','hms_doctors.id = hms_dialysis_appointment. attended_doctor','left');
		$this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left');
		$this->db->join('hms_state','hms_state.id = hms_patient.state_id','left');
		$this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); 
		
		$this->db->where('hms_dialysis_appointment.branch_id',$user_data['parent_id']); 

		if(!empty($slot_id))
		{
			$this->db->where('hms_dialysis_appointment.schedule_id',$slot_id); 
		}		

		$end_date = date('Y-m-d',strtotime($bookind_date));
				$this->db->where('hms_dialysis_appointment.dialysis_date = "'.$end_date.'"');
		
		$start_time = date('H:i:s',strtotime($time));
		$this->db->where('hms_dialysis_appointment.dialysis_time = "'.$start_time.'"');
		$this->db->where('hms_dialysis_appointment.is_deleted','0');
		$query = $this->db->get();
		//echo $this->db->last_query(); //exit;
		return $query->row_array();
	}
    
    
    public function get_dialysis_schedule_available_time($schedule_id='',$booking_date)
    {
        //echo $schedule_id; die;
        if(isset($schedule_id) && !empty($schedule_id))
        {
            
            $data = '<option value="">Select Time</option>';
            if(!empty($schedule_id))
            {
                $time_list = $this->schedule_time($schedule_id,$booking_date);
                //print_r($time_list); exit; 
                if(!empty($time_list))
                {
                    foreach($time_list as $time)
                    {
                        
                        $data .= '<option value="'.$time->id.'">'.date("g:i A", strtotime($time->from_time)).' - '.date("g:i A", strtotime($time->to_time)).'</option>';
                        
                    }
                }
            }
        }
        return $data;
    }
    
    public function schedule_time($schedule_id="",$booking_date='')
    {
        $users_data = $this->session->userdata('auth_users');
        
        $this->db->select('hms_dialysis_schedule_time.*,hms_days.day_name');
        $this->db->join('hms_dialysis_schedule','hms_dialysis_schedule.id = hms_dialysis_schedule_time.schedule_id');
        $this->db->join('hms_days','hms_days.id = hms_dialysis_schedule_time.available_day');
        if(!empty($schedule_id))
        {
            $this->db->where('hms_dialysis_schedule_time.schedule_id = "'.$schedule_id.'"');
            
        }
        if(!empty($booking_date))
        {
            $date = date('Y-m-d',strtotime($booking_date));
            $day_name = date("l", strtotime($date));
            $this->db->where('hms_days.day_name = "'.$day_name.'"'); 
        }
         
        $query = $this->db->get('hms_dialysis_schedule_time');
        //echo $this->db->last_query(); exit;
        $result = $query->result();    
        //echo $this->db->last_query(); 
        return $result;
    }
    
    public function cancel_appointment($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('dialysis_name','');
			$this->db->set('dialysis_time','');
			$this->db->set('doctor_slot','');
			$this->db->set('available_time','');
			$this->db->set('time_value','');
			$this->db->set('type','2');
			$this->db->where('id',$id);
			$this->db->update('hms_dialysis_appointment');
			//echo $this->db->last_query();die;
    	} 
    }
    
    
    public function reschedule_booking()
   {
   		
   		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
        //echo "<pre>"; print_r($post); exit;
		$booking_date = date("Y-m-d", strtotime($post['booking_date']) );
		$dialysis_time = date('H:i:s', strtotime(date('d-m-Y').' '.$post['dialysis_time']));
		
		$available_time_value='';
        if(!empty($post['available_time']))
        {
         	$available_time_value = $this->get_available_time_value($post['available_time']);
        
        }
        
		    $data = array( 
				
					'dialysis_end_time'=>$time,
					'schedule_id'=>$post['schedule_id'],
					//'room_type_id'=>$post['room_id'],
                    //'room_no_id'=>$post['room_no_id'],
                    //'room_id'=>$post['room_no_id'],
                    //'bad_id'=>$post['bed_no_id'],
                    //'bed_no_id'=>$post['bed_no_id'],
					'dialysis_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_booking_date'=>date('Y-m-d',strtotime($post['dialysis_date'])),
					'dialysis_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['doctor_slot'])),
					'doctor_slot'=>$post['doctor_slot'],
					'available_time'=>$post['available_time'],
							'time_value'=>$available_time_value,
					);
				
    			$this->db->where('id', $post['data_id']);
    			$this->db->update('hms_dialysis_appointment',$data); 
                //echo $this->db->last_query();die;
            

            return $post['data_id'];
			
   }
    

}
?>