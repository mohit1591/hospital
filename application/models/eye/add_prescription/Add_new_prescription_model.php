<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_new_prescription_model extends CI_Model 
{
	public function get_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_opd_booking.*,hms_opd_booking.policy_no as polocy_no,hms_opd_booking.insurance_type_id as insurance_type, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.adhar_no, hms_patient.patient_email,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id');
		
			$this->db->from('hms_opd_booking'); 
			$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
			$this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 
			$this->db->where('hms_opd_booking.id',$id);
			$this->db->where('hms_opd_booking.is_deleted','0');
			$query = $this->db->get(); 
			return $query->row_array();
	}

	public function template_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_eye_prescription_template.*');  
		$this->db->where('hms_eye_prescription_template.id',$template_id);  
		$this->db->where('hms_eye_prescription_template.is_deleted',0); 
		$this->db->where('hms_eye_prescription_template.branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_eye_prescription_template');
		$result = $query->row(); 
		return json_encode($result);
		 
    }

   public function get_cheif_complain_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_eye_cheif_complain_template_vals.*');  
		$this->db->where('hms_eye_cheif_complain_template_vals.eye_prescription_template_id',$template_id);  
		$query = $this->db->get('hms_eye_cheif_complain_template_vals');
		$result = $query->result(); 
		//echo $this->db->last_query(); exit;
		return json_encode($result);
		 
    }

   public function get_systemic_illness_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_eye_systemic_illness_template_vals.*');  
		$this->db->where('hms_eye_systemic_illness_template_vals.eye_prescription_template_id',$template_id);  
		$query = $this->db->get('hms_eye_systemic_illness_template_vals');
		$result = $query->result(); 
		//echo $this->db->last_query(); exit;
		return json_encode($result);
		 
    }

   public function get_diagnosis_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('	hms_eye_diagnosis_template_vals.*');  
		$this->db->where('	hms_eye_diagnosis_template_vals.eye_prescription_template_id',$template_id);  
		$query = $this->db->get('hms_eye_diagnosis_template_vals');
		$result = $query->result(); 
		//echo $this->db->last_query(); exit;
		return json_encode($result);
		 
    }

	public function get_template_test_data($template_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_eye_prescription_patient_test_template.*');  
		$this->db->where('hms_eye_prescription_patient_test_template.template_id',$template_id);  
		$query = $this->db->get('hms_eye_prescription_patient_test_template');
		$result = $query->result(); 
		//echo $this->db->last_query(); exit;
		return json_encode($result);
	 
	}
   public function get_template_prescription_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_eye_prescription_patient_pres_template.*');  
		$this->db->where('hms_eye_prescription_patient_pres_template.template_id',$template_id);  
		$query = $this->db->get('hms_eye_prescription_patient_pres_template');
		$result = $query->result(); 
		return json_encode($result);
		 
    }
    
    public function drawing_master()
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_eye_drawing.*');  
		$this->db->where('hms_eye_drawing.branch_id', $users_data['parent_id']);
		$query = $this->db->get('hms_eye_drawing');
		return $query->result_array();  
		 
    }
    
    public function get_drawing($booking_id="",$pres_id="")
     {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_eye_prescription_drawing.*');  
		$this->db->where('hms_eye_prescription_drawing.booking_id', $booking_id);
		$this->db->where('hms_eye_prescription_drawing.pres_id', $pres_id);
		$query = $this->db->get('hms_eye_prescription_drawing');
		return $query->result_array(); 
		 
     }

    public function save_prescription($filename1="",$filename2="")
    {
    	//echo "<pre>";print_r($_POST); exit;users_data
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$reg_no = generate_unique_id(10); 
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
		
 		if(!empty($post['data_id']) && $post['data_id']>0)
		{
			
		if($post['next_appointment_date']!='00-00-0000 00:00:00' && $post['next_appointment_date']!='01-01-1970')
		{
			$next_appointment_date = date('Y-m-d H:i:s',strtotime($post['next_appointment_date']));
		}	
		else
		{
			$next_appointment_date = ''; 
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
					"right_eye_image"=>$filename1,
					"left_eye_image"=>$filename2,
					"right_eye_discussion"=>$post['right_eye_dicussion'],
					"left_eye_discussion"=>$post['left_eye_dicussion'],

					/*"patient_bp"=>$post['patient_bp'],
					"patient_temp"=>$post['patient_temp'],
					"patient_weight"=>$post['patient_weight'],
					"patient_height"=>$post['patient_height'],
					"patient_spo2"=>$post['patient_spo2'],
					"patient_rbs"=>$post['patient_rbs'],*/
					"prv_history"=>$post['prv_history'],
					"personal_history"=>$post['personal_history'],
					//"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					//"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>$next_appointment_date,
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_eye_prescription',$data);

			$data_opd = array( 
					"branch_id"=> $user_data['parent_id'],      
					"booking_code"=>$post['booking_code'],
					'flag'=>1,
					'flag_id'=>$post['data_id'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					/*"patient_bp"=>$post['patient_bp'],
					"patient_temp"=>$post['patient_temp'],
					"patient_weight"=>$post['patient_weight'],
					"patient_height"=>$post['patient_height'],
					"patient_spo2"=>$post['patient_spo2'],
					"patient_rbs"=>$post['patient_rbs'],*/
					"prv_history"=>$post['prv_history'],
					"personal_history"=>$post['personal_history'],
					//"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					//"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>$next_appointment_date,
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('flag_id',$post['data_id']);
			$this->db->update('hms_opd_prescription',$data_opd);



				/* cheif complain */
 		

 			if(!empty($post['cheif_complains']))
				{
				$this->db->where('eye_prescription_id',$post['data_id']);
				$this->db->delete('hms_eye_cheif_complain_vals'); 
				$new_array= array_values($post['cheif_complains']);
				$total_cheif_complain_name = count($new_array); 

				for($i=0;$i<=$total_cheif_complain_name-1;$i++)
				{
					if(isset($new_array[$i]['cheif_complain_name']))
					{
						$cheif_complain_name=$new_array[$i]['cheif_complain_name'];
					}
					else
					{
						$cheif_complain_name='';
					}
					if(isset($new_array[$i]['cheif_c_right']))
					{
						$cheif_c_right=$new_array[$i]['cheif_c_right'];
					}
					else
					{
						$cheif_c_right='';
					}
					if(isset($new_array[$i]['cheif_c_left']))
					{
						$cheif_c_left=$new_array[$i]['cheif_c_left'];
					}
					else
					{
					$cheif_c_left='';	
					}
					if(isset($new_array[$i]['cheif_c_days']))
					{
						$cheif_c_days=$new_array[$i]['cheif_c_days'];
					}
					else
					{
						$cheif_c_days='';
					}
					if(isset($new_array[$i]['cheif_c_time']))
					{
						$cheif_c_time=$new_array[$i]['cheif_c_time'];
					}
					else
					{
						$cheif_c_time='';
					}
					$prescription_data_cheif = array(
												"eye_prescription_id"=>$post['data_id'],
												"cheif_complains"=>$cheif_complain_name,
												"right_eye"=>$cheif_c_right,
												"left_eye"=>$cheif_c_left,
												"days"=>$cheif_c_days,
												"time"=>$cheif_c_time);
					
					$this->db->insert('hms_eye_cheif_complain_vals',$prescription_data_cheif); 
					//$test_data_id = $this->db->insert_id(); 
				}

				}
				/* cheif compalin   */

				/* systemic illness  */
				if(!empty($post['systemic_illness']))
				{
					$this->db->where('eye_prescription_id',$post['data_id']);
					$this->db->delete('hms_eye_systemic_illness_vals'); 
					$total_systemic_illness = count($post['systemic_illness']); 
					for($i=0;$i<=$total_systemic_illness-1;$i++)
					{

						if(isset($post['systemic_illness'][$i]))
						{
						$systemic_illness[$i]=$post['systemic_illness'][$i];
						}
						else
						{
							$systemic_illness[$i]='';
						}

						if(isset($post['systemic_illness_days'][$i]))
						{
						$systemic_illness_days[$i]=$post['systemic_illness_days'][$i];
						}
						else
						{
							$systemic_illness_days[$i]='';
						}


						if(isset($post['systemic_illness_time'][$i]))
						{
						$systemic_illness_time[$i]=$post['systemic_illness_time'][$i];
						}
						else
						{
							$systemic_illness_time[$i]='';
						}


						$prescription_data_systemic_illness = array(
														'branch_id'=>$user_data['parent_id'],
														"eye_prescription_id"=>$post['data_id'],
														"systemic_illness"=>$systemic_illness[$i],
														"days"=>$systemic_illness_days[$i],
														"time"=>$systemic_illness_time[$i]);


						$this->db->insert('hms_eye_systemic_illness_vals',$prescription_data_systemic_illness); 
				//$test_data_id = $this->db->insert_id(); 
				}
			}
		/* systemic illness  */

		/* diagnosis data */
		
		if(!empty($post['diagnosis']))
			{
				$this->db->where('eye_prescription_id',$post['data_id']);
				$this->db->delete('hms_eye_diagnosis_vals'); 
				$new_array_diagnosis= array_values($post['diagnosis']);
				$total_diagnosis = count($new_array_diagnosis); 
				for($i=0;$i<=$total_diagnosis-1;$i++)
				{

					if(isset($new_array_diagnosis[$i]['diagnosis_name']))
					{
					$diagnosis=$new_array_diagnosis[$i]['diagnosis_name'];
					}
					else
					{
					$diagnosis='';
					}


					if(isset($new_array_diagnosis[$i]['diagnosis_right']))
					{
					$diagnosis_right=$new_array_diagnosis[$i]['diagnosis_right'];
					}
					else
					{
					$diagnosis_right='';
					}


					if(isset($new_array_diagnosis[$i]['diagnosis_left']))
					{
					$diagnosis_left=$new_array_diagnosis[$i]['diagnosis_left'];
					}
					else
					{
					$diagnosis_left='';
					}


					if(isset($new_array_diagnosis[$i]['diagnosis_days']))
					{
					$diagnosis_days=$new_array_diagnosis[$i]['diagnosis_days'];
					}
					else
					{
					$diagnosis_days='';
					}


					if(isset($new_array_diagnosis[$i]['diagnosis_time']))
					{
					$diagnosis_time=$new_array_diagnosis[$i]['diagnosis_time'];
					}
					else
					{
						$diagnosis_time='';
					}


					$prescription_data_diagnosis = array(
													'branch_id'=>$user_data['parent_id'],
													"eye_prescription_id"=>$post['data_id'],
													"diagnosis"=>$diagnosis,
													"right_eye"=>$diagnosis_right,
													"left_eye"=>$diagnosis_left,
													"days"=>$diagnosis_days,
													"time"=>$diagnosis_time);
					$this->db->insert('hms_eye_diagnosis_vals',$prescription_data_diagnosis);
				}
			}
			
		   /* diagnosis data */
			$this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_eye_prescription_patient_test'); 
			$total_test = count(array_filter($post['test_name'])); 
			for($i=0;$i<$total_test;$i++)
			{
					if(!empty($post['test_name'][$i]))
					{	
						$this->db->select('path_test.*');  
						$this->db->where('path_test.test_name',$post['test_name'][$i]);  
						$this->db->where('path_test.is_deleted!=2'); 
						$this->db->where('path_test.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('path_test');
						$num = $query->num_rows();
						if($num>0)
						{

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
						}

					}
						$test_data = array(
						"prescription_id"=>$post['data_id'],
						"test_name"=>$post['test_name'][$i]);
						$this->db->insert('hms_eye_prescription_patient_test',$test_data); 
						$test_data_id = $this->db->insert_id(); 
			}

			$this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_eye_prescription_patient_pres'); 
			if(!empty($post['prescription']))
			{
				$new_pres_array= array_values($post['prescription']);
			    $total_prescription = count(array_filter($new_pres_array));	

			for($i=0;$i<$total_prescription;$i++)
			{	

			 if(isset($new_pres_array[$i]['brand']) && !empty($new_pres_array[$i]['brand']))
				{
					$company= $this->check_comapny_name($new_pres_array[$i]['brand']);

					if(!empty($company))
					{
						$company_name= $company[0]->id;  
					}
					else
					{
						$data_company = array( 
						'branch_id'=>$user_data['parent_id'],
						'company_name'=>$new_pres_array[$i]['brand'],
						'status'=>1,
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
						$this->db->set('created_by',$user_data['id']);
						$this->db->set('created_date',date('Y-m-d H:i:s'));
						$this->db->insert('hms_medicine_company',$data_company);
						$company_name= $this->db->insert_id();
					}
				}
				else
				{
					$company_name='';
				}
				

			
				if(isset($new_pres_array[$i]['medicine_type']) && !empty($new_pres_array[$i]['medicine_type']))
				{
                  
					$unit= $this->check_unit($new_pres_array[$i]['medicine_type']);

					if(!empty($unit))
					{
					$unit_id= $unit[0]->id;
					}
					else
					{
					$data_unit = array( 
							'branch_id'=>$user_data['parent_id'],
							'medicine_unit'=>$new_pres_array[$i]['medicine_type'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_medicine_unit',$data_unit);
							$unit_id= $this->db->insert_id();	
					}
				}
				else
				{
					$unit_id='';
				}

			if(isset($new_pres_array[$i]['medicine_salt']))
			{
				$medicine_salt=$new_pres_array[$i]['medicine_salt'];
			}
			else
			{
				$medicine_salt='';
			}
			/* insert into medicine table  */
			$data_medicine_entry = array(
					"medicine_code"=>$reg_no,
					"medicine_name"=>$new_pres_array[$i]['medicine_name'],
					'branch_id'=>$user_data['parent_id'],
					'type'=>1,
					"unit_id"=>$unit_id,
					"salt"=>$medicine_salt,
					"manuf_company"=>$company_name,
					"status"=>1
					); 
			//print_r($data_medicine_entry);die;
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$post['data_id']);
			$this->db->update('hms_medicine_entry',$data_medicine_entry); 
				
					//medicine dose
					if(!empty($new_pres_array[$i]['medicine_dose']) &&  isset($new_pres_array[$i]['medicine_dose']))
					{
						$this->db->select('hms_eye_medicine_dosage.*');
						$this->db->where('hms_eye_medicine_dosage.dosage',$new_pres_array[$i]['medicine_dose']); 
						$this->db->where('hms_eye_medicine_dosage.is_deleted!=2'); 
						$this->db->where('hms_eye_medicine_dosage.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_medicine_dosage');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'dosage'=>$new_pres_array[$i]['medicine_dose'],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_eye_medicine_dosage',$data);
						}
					}

					//medicine duration
					if(!empty($new_pres_array[$i]['medicine_duration']) && isset($new_pres_array[$i]['medicine_duration']))
					{
						$this->db->select('hms_eye_medicine_dosage_duration.*'); 
						//$this->db->from('hms_opd_medicine_dosage_duration');
						$this->db->where('hms_eye_medicine_dosage_duration.medicine_dosage_duration',$new_pres_array[$i]['medicine_duration']); 
						$this->db->where('hms_eye_medicine_dosage_duration.is_deleted!=2'); 
						$this->db->where('hms_eye_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_medicine_dosage_duration');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'medicine_dosage_duration'=>$new_pres_array[$i]['medicine_duration'],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_eye_medicine_dosage_duration',$data);
						}
					}

					//medicine frequency
					if(!empty($new_pres_array[$i]['medicine_frequency']) && isset($new_pres_array[$i]['medicine_frequency']))
					{
						$this->db->select('hms_eye_medicine_dosage_frequency.*');
						//$this->db->from('hms_opd_medicine_dosage_frequency');
						$this->db->where('hms_eye_medicine_dosage_frequency.medicine_dosage_frequency',$new_pres_array[$i]['medicine_frequency']); 
						$this->db->where('hms_eye_medicine_dosage_frequency.is_deleted!=2'); 
						$this->db->where('hms_eye_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_medicine_dosage_frequency');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_dosage_frequency'=>$new_pres_array[$i]['medicine_frequency'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_eye_medicine_dosage_frequency',$data);
						}
					}

					//medicine advice
					if(!empty($new_pres_array[$i]['medicine_advice']) && isset($new_pres_array[$i]['medicine_advice']))
					{
						//echo 'ff';
						$this->db->select('*');
						//$this->db->from('hms_opd_advice');
						$this->db->where('hms_eye_advice.medicine_advice',$new_pres_array[$i]['medicine_advice']); 
						$this->db->where('hms_eye_advice.is_deleted!=2'); 
						$this->db->where('hms_eye_advice.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_advice');
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
							$this->db->insert('hms_eye_advice',$data);
						}
					}
					if(isset($new_pres_array[$i]['medicine_left_eye']))
							{
							$medicine_left_eye=$new_pres_array[$i]['medicine_left_eye'];
							}
							else
							{
							$medicine_left_eye='';
							}
							if(isset($new_pres_array[$i]['medicine_right_eye']))
							{
							$medicine_right_eye=$new_pres_array[$i]['medicine_right_eye'];
							}
							else
							{
							$medicine_right_eye='';
							}
							if(isset($new_pres_array[$i]['brand']))
							{
							$medicine_brand=$new_pres_array[$i]['brand'];
							}
							else
							{
							$medicine_brand='';
							}

							 if(isset($new_pres_array[$i]['medicine_salt']))
						   {
						   	$medicine_salt_n=$new_pres_array[$i]['medicine_salt'];
						   }
						   else
						   {
						   	$medicine_salt_n='';
						   }
						   if(isset($new_pres_array[$i]['medicine_type']))
						   {
						   	$medicine_type_n=$new_pres_array[$i]['medicine_type'];
						   }
						   else
						   {
						   	$medicine_type_n='';
						   }
						   if(isset($new_pres_array[$i]['medicine_dose']))
						   {
						   	$medicine_dose_n=$new_pres_array[$i]['medicine_dose'];
						   }
						   else
						   {
						   	$medicine_dose_n='';
						   }
						    if(isset($new_pres_array[$i]['medicine_duration']))
						   {
						   	$medicine_duration_n=$new_pres_array[$i]['medicine_duration'];
						   }
						   else
						   {
						   	$medicine_duration_n='';
						   }
						    if(isset($new_pres_array[$i]['medicine_frequency']))
						   {
						   	$medicine_frequency_n=$new_pres_array[$i]['medicine_frequency'];
						   }
						   else
						   {
						   	$medicine_frequency_n='';
						   }

						   if(isset($new_pres_array[$i]['medicine_date']))
						   {
						   	$medicine_date_n=$new_pres_array[$i]['medicine_date'];
						   }
						   else
						   {
						   	$medicine_date_n='';
						   }
						    if(isset($new_pres_array[$i]['medicine_advice']))
						   {
						   	$medicine_advice_n=$new_pres_array[$i]['medicine_advice'];
						   }
						   else
						   {
						   	$medicine_advice_n='';
						   }
							$prescription_data = array(
								"prescription_id"=>$post['data_id'],
								"medicine_name"=>$new_pres_array[$i]['medicine_name'],
								"medicine_brand"=>$medicine_brand,
								"medicine_salt"=>$medicine_salt_n,
								"medicine_type"=>$medicine_type_n,
								"medicine_dose"=>$medicine_dose_n,
								"date"=>date('Y-m-d',strtotime(	$medicine_date_n)),
								"left_eye"=>$medicine_left_eye,
								"right_eye"=>$medicine_right_eye,
								"medicine_duration"=>$medicine_duration_n,
								"medicine_frequency"=>$medicine_frequency_n,
								"medicine_advice"=>$medicine_advice_n
								);
							$this->db->insert('hms_eye_prescription_patient_pres',$prescription_data);
							$test_data_id = $this->db->insert_id(); 
					    //echo $this->db->last_query(); 
				
					}
			}//die;
			  

			
			

			if(!empty($post['next_appointment_date']) && $post['next_appointment_date']!='0000-00-00' && $post['next_appointment_date']!='30-11-0')
		    {
		       $booking_id = $post['booking_id'];
		       $this->load->model('opd/opd_model');
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



	           	$appointment_code = generate_unique_id(20);
				$timestamp = $post['next_appointment_date'];
				$splitTimeStamp = explode(" ",$timestamp);
				$booking_date = $splitTimeStamp[0];
				$booking_time = $splitTimeStamp[1];

	           $data_test = array(    
						'branch_id'=> $user_data['parent_id'],
						'parent_id'=>0,
						'type'=>1,
						'booking_type'=>1,
						'patient_id'=>$patient_id,
						'referral_doctor'=>$referral_doctor,
						'attended_doctor'=>$attended_doctor,
						'booking_status'=>'0', 
						'appointment_code'=>$appointment_code, 
						'appointment_date'=>date('Y-m-d',strtotime($booking_date)),
						'appointment_time'=>date('H:i:s',strtotime($booking_time)), 
						'created_by'=>$user_data['id'],
						'created_date'=>date('Y-m-d H:i:s')
				);

	          


	           //'sample_collected_by'=>$sample_collected_by,
			   //'staff_refrenace_id'=>$staff_refrenace_id,
	           //'booking_date'=>date('Y-m-d',strtotime($post['next_appointment_date']))
		    	/*$this->db->set('patient_id',$patient_id);
		    	$this->db->set('type',1);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));*/
				$this->db->insert('hms_opd_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
	            
	            //echo $this->db->last_query(); exit;
		 	}

		 	$data_id= $post['data_id'];

		} /*add */
		else
		{
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
					"right_eye_image"=>$filename1,
					"left_eye_image"=>$filename2,
					"right_eye_discussion"=>$post['right_eye_dicussion'],
					"left_eye_discussion"=>$post['left_eye_dicussion'],

					"prv_history"=>$prv_history,
					"personal_history"=>$personal_history,
					//"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					//"diagnosis"=>$post['diagnosis'],
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
			$this->db->insert('hms_eye_prescription',$data); 
			// /echo $this->db->last_query();die;
			$data_id = $this->db->insert_id(); 

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
					'flag'=>1,
					'flag_id'=>$data_id,
					
					"prv_history"=>$prv_history,
					"personal_history"=>$personal_history,
					//"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					//"diagnosis"=>$post['diagnosis'],
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
           // echo $this->db->last_query();die;
				/* cheif complain */
 		

 			if(!empty($post['cheif_complains']))
				{
				$new_array= array_values($post['cheif_complains']);
				$total_cheif_complain_name = count($new_array); 

				for($i=0;$i<=$total_cheif_complain_name-1;$i++)
				{
					if(isset($new_array[$i]['cheif_complain_name']))
					{
						$cheif_complain_name=$new_array[$i]['cheif_complain_name'];
					}
					else
					{
						$cheif_complain_name='';
					}
					if(isset($new_array[$i]['cheif_c_right']))
					{
						$cheif_c_right=$new_array[$i]['cheif_c_right'];
					}
					else
					{
						$cheif_c_right='';
					}
					if(isset($new_array[$i]['cheif_c_left']))
					{
						$cheif_c_left=$new_array[$i]['cheif_c_left'];
					}
					else
					{
					$cheif_c_left='';	
					}
					if(isset($new_array[$i]['cheif_c_days']))
					{
						$cheif_c_days=$new_array[$i]['cheif_c_days'];
					}
					else
					{
						$cheif_c_days='';
					}
					if(isset($new_array[$i]['cheif_c_time']))
					{
						$cheif_c_time=$new_array[$i]['cheif_c_time'];
					}
					else
					{
						$cheif_c_time='';
					}
					$prescription_data_cheif = array(
												"eye_prescription_id"=>$data_id,
												"cheif_complains"=>$cheif_complain_name,
												"right_eye"=>$cheif_c_right,
												"left_eye"=>$cheif_c_left,
												"days"=>$cheif_c_days,
												"time"=>$cheif_c_time);
					
					$this->db->insert('hms_eye_cheif_complain_vals',$prescription_data_cheif); 
					//$test_data_id = $this->db->insert_id(); 
				}

				}
				/* cheif compalin  */

				/* systemic illness  */
				if(!empty($post['systemic_illness']))
				{
					$total_systemic_illness = count($post['systemic_illness']); 
					for($i=0;$i<=$total_systemic_illness-1;$i++)
					{

						if(isset($post['systemic_illness'][$i]))
						{
						$systemic_illness[$i]=$post['systemic_illness'][$i];
						}
						else
						{
							$systemic_illness[$i]='';
						}

						if(isset($post['systemic_illness_days'][$i]))
						{
						$systemic_illness_days[$i]=$post['systemic_illness_days'][$i];
						}
						else
						{
							$systemic_illness_days[$i]='';
						}


						if(isset($post['systemic_illness_time'][$i]))
						{
						$systemic_illness_time[$i]=$post['systemic_illness_time'][$i];
						}
						else
						{
							$systemic_illness_time[$i]='';
						}


						$prescription_data_systemic_illness = array(
														'branch_id'=>$user_data['parent_id'],
														"eye_prescription_id"=>$data_id,
														"systemic_illness"=>$systemic_illness[$i],
														"days"=>$systemic_illness_days[$i],
														"time"=>$systemic_illness_time[$i]);


						$this->db->insert('hms_eye_systemic_illness_vals',$prescription_data_systemic_illness); 
				//$test_data_id = $this->db->insert_id(); 
				}
			}
		/* systemic illness  */

		/* diagnosis data */
		
		if(!empty($post['diagnosis']))
			{
				$new_array_diagnosis= array_values($post['diagnosis']);
				$total_diagnosis = count($new_array_diagnosis); 
				for($i=0;$i<=$total_diagnosis-1;$i++)
				{

					if(isset($new_array_diagnosis[$i]['diagnosis_name']))
					{
					$diagnosis=$new_array_diagnosis[$i]['diagnosis_name'];
					}
					else
					{
					$diagnosis='';
					}


					if(isset($new_array_diagnosis[$i]['diagnosis_right']))
					{
					$diagnosis_right=$new_array_diagnosis[$i]['diagnosis_right'];
					}
					else
					{
					$diagnosis_right='';
					}


					if(isset($new_array_diagnosis[$i]['diagnosis_left']))
					{
					$diagnosis_left=$new_array_diagnosis[$i]['diagnosis_left'];
					}
					else
					{
					$diagnosis_left='';
					}


					if(isset($new_array_diagnosis[$i]['diagnosis_days']))
					{
					$diagnosis_days=$new_array_diagnosis[$i]['diagnosis_days'];
					}
					else
					{
					$diagnosis_days='';
					}


					if(isset($new_array_diagnosis[$i]['diagnosis_time']))
					{
					$diagnosis_time=$new_array_diagnosis[$i]['diagnosis_time'];
					}
					else
					{
						$diagnosis_time='';
					}


					$prescription_data_diagnosis = array(
													'branch_id'=>$user_data['parent_id'],
													"eye_prescription_id"=>$data_id,
													"diagnosis"=>$diagnosis,
													"right_eye"=>$diagnosis_right,
													"left_eye"=>$diagnosis_left,
													"days"=>$diagnosis_days,
													"time"=>$diagnosis_time);
					$this->db->insert('hms_eye_diagnosis_vals',$prescription_data_diagnosis); 

					//echo $this->db->last_query();die;
			//$test_data_id = $this->db->insert_id(); 
			}
			}
			
		/* diagnosis data */

 			



			$total_test = count(array_filter($post['test_name']));
			for($i=0;$i<$total_test;$i++)
			{
				
					//check and add masters of test 
					if(!empty($post['test_name'][$i]))
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
								//echo $this->db->last_query(); exit;
						}

					}

				$test_data = array(
				"prescription_id"=>$data_id,
				"test_name"=>$post['test_name'][$i]);
				$this->db->insert('hms_eye_prescription_patient_test',$test_data); 
				$test_data_id = $this->db->insert_id(); 
			}
			
             if(!empty($post['prescription']))
             {
             	$new_pres_array= array_values($post['prescription']);
				$total_prescription = count(array_filter($new_pres_array)); 


			for($i=0;$i<$total_prescription-1;$i++)
			{	
					//echo $post['medicine_name'][$i];
					//check and add masters 

			

				/* medicine type */
				//print '<pre>'; print_r($new_pres_array);die;
				$result_new_unit= $new_pres_array[$i]['medicine_type'];
			 	if(isset($result_new_unit) && !empty($result_new_unit))
			 	{
			 		$medicine_unit=$result_new_unit;
				    if(isset($medicine_unit) && !empty($medicine_unit))
				    {
                        $unit= $this->check_unit($medicine_unit); 
                        if(!empty($unit))
                        {
                        	$unit_id= $unit[0]->id;
                        }
                        else
                        {
							$data_unit = array( 
							'branch_id'=>$user_data['parent_id'],
							'medicine_unit'=>$new_pres_array[$i]['medicine_type'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_medicine_unit',$data_unit);
							$unit_id= $this->db->insert_id();
                        }

                        
                       
				    }

			 	}
			 	else
			 	{
			 		$unit_id='0';
			 	}

				/* medicine type */

				/* medicine brand */
				$result_new_company=  $new_pres_array[$i]['brand'];
			 	if(isset($result_new_company) && !empty($result_new_company))
			 	{
			 		$medicine_comapny=$result_new_company;
				    if(isset($medicine_comapny) && !empty($medicine_comapny))
				    {
                        $company= $this->check_comapny_name($medicine_comapny);

                        if(!empty($company))
                        {
                            $company_id= $company[0]->id;  
                        }
                        else
                        {
							$data_company = array( 
							'branch_id'=>$user_data['parent_id'],
							'company_name'=>$new_pres_array[$i]['brand'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR']
							);
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_medicine_company',$data_company);
							$company_id= $this->db->insert_id();
                        }
                       
				    }

			 	}
			 	else
			 	{
			 		$company_id='';
			 	}

				/* medicine brand */


                  if(isset($new_pres_array[$i]['medicine_salt']))
                  {
                  	$medicine_salt=$new_pres_array[$i]['medicine_salt'];
                  }
                  else
                  {
                  	$medicine_salt='';
                  }
				/* medicine entry */
				   $data_medicine_entry = array(
							"medicine_code"=>$reg_no,
							"medicine_name"=>$new_pres_array[$i]['medicine_name'],
							'branch_id'=>$user_data['parent_id'],
							'type'=>1,
							"unit_id"=>$unit_id,
							"salt"=>$medicine_salt,
							"manuf_company"=>$company_id
							); 
			 	
					$this->db->set('created_by',$user_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_medicine_entry',$data_medicine_entry);      
			 	
				/* medicine entry */
					
					//medicine dose
					if(!empty($new_pres_array[$i]['medicine_dose']))
					{
						$this->db->select('hms_eye_medicine_dosage.*');
						//$this->db->from('hms_opd_medicine_dosage');
						$this->db->where('hms_eye_medicine_dosage.dosage',$new_pres_array[$i]['medicine_dose']); 
						$this->db->where('hms_eye_medicine_dosage.is_deleted!=2'); 
						$this->db->where('hms_eye_medicine_dosage.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_medicine_dosage');
						//echo $this->db->last_query();die;
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'dosage'=>$new_pres_array[$i]['medicine_dose'],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_eye_medicine_dosage',$data);
						}
					}

					//medicine duration
					if(!empty($post['prescription'][$i]['medicine_duration']))
					{
						$this->db->select('hms_eye_medicine_dosage_duration.*'); 
						//$this->db->from('hms_opd_medicine_dosage_duration');
						$this->db->where('hms_eye_medicine_dosage_duration.medicine_dosage_duration',$new_pres_array[$i]['medicine_duration']); 
						$this->db->where('hms_eye_medicine_dosage_duration.is_deleted!=2'); 
						$this->db->where('hms_eye_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_medicine_dosage_duration');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'medicine_dosage_duration'=>$new_pres_array[$i]['medicine_duration'],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_eye_medicine_dosage_duration',$data);
						}
					}

					//medicine frequency
					if(!empty($post['prescription'][$i]['medicine_frequency']))
					{
						$this->db->select('hms_eye_medicine_dosage_frequency.*');
						//$this->db->from('hms_opd_medicine_dosage_frequency');
						$this->db->where('hms_eye_medicine_dosage_frequency.medicine_dosage_frequency',$new_pres_array[$i]['medicine_frequency']); 
						$this->db->where('hms_eye_medicine_dosage_frequency.is_deleted!=2'); 
						$this->db->where('hms_eye_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_medicine_dosage_frequency');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_dosage_frequency'=>$new_pres_array[$i]['medicine_frequency'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_eye_medicine_dosage_frequency',$data);
						}
					}

					//medicine advice
					if(!empty($new_pres_array[$i]['medicine_advice']))
					{
						$this->db->select('*');
						//$this->db->from('hms_opd_advice');
						$this->db->where('hms_eye_advice.medicine_advice',$new_pres_array[$i]['medicine_advice']); 
						$this->db->where('hms_eye_advice.is_deleted!=2'); 
						$this->db->where('hms_eye_advice.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_advice');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_advice'=>$new_pres_array[$i]['medicine_advice'],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_eye_advice',$data);
						}
					}
				 }
             
						
				 		//print '<pre>'; print_r($new_pres_array);die;
					if(isset($new_pres_array) && !empty($new_pres_array))
					{

						$total_prescription = count($new_pres_array);

						for($i=0;$i<=$total_prescription-1;$i++)
						{
							if(isset($new_pres_array[$i]['medicine_left_eye']))
						   {
						   	$medicine_left_eye=$new_pres_array[$i]['medicine_left_eye'];
						   }
						   else
						   {
						   	$medicine_left_eye='';
						   }
						   if(isset($new_pres_array[$i]['medicine_right_eye']))
						   {
						   	$medicine_right_eye=$new_pres_array[$i]['medicine_right_eye'];
						   }
						   else
						   {
						   	$medicine_right_eye='';
						   }

						   if(isset($new_pres_array[$i]['medicine_right_eye']))
						   {
						   	$medicine_right_eye=$new_pres_array[$i]['medicine_right_eye'];
						   }
						   else
						   {
						   	$medicine_right_eye='';
						   }
						   if(isset($new_pres_array[$i]['medicine_salt']))
						   {
						   	$medicine_salt_n=$new_pres_array[$i]['medicine_salt'];
						   }
						   else
						   {
						   	$medicine_salt_n='';
						   }
						   if(isset($new_pres_array[$i]['medicine_type']))
						   {
						   	$medicine_type_n=$new_pres_array[$i]['medicine_type'];
						   }
						   else
						   {
						   	$medicine_type_n='';
						   }
						   if(isset($new_pres_array[$i]['medicine_dose']))
						   {
						   	$medicine_dose_n=$new_pres_array[$i]['medicine_dose'];
						   }
						   else
						   {
						   	$medicine_dose_n='';
						   }
						    if(isset($new_pres_array[$i]['medicine_duration']))
						   {
						   	$medicine_duration_n=$new_pres_array[$i]['medicine_duration'];
						   }
						   else
						   {
						   	$medicine_duration_n='';
						   }
						    if(isset($new_pres_array[$i]['medicine_frequency']))
						   {
						   	$medicine_frequency_n=$new_pres_array[$i]['medicine_frequency'];
						   }
						   else
						   {
						   	$medicine_frequency_n='';
						   }

						   if(isset($new_pres_array[$i]['medicine_date']))
						   {
						   	$medicine_date_n=$new_pres_array[$i]['medicine_date'];
						   }
						   else
						   {
						   	$medicine_date_n='';
						   }
						   if(isset($new_pres_array[$i]['medicine_advice']))
						   {
						   	$medicine_advice_n=$new_pres_array[$i]['medicine_advice'];
						   }
						   else
						   {
						   	$medicine_advice_n='';
						   }
						   if(isset($new_pres_array[$i]['brand']))
						   {
						   	$brand_n=$new_pres_array[$i]['brand'];
						   }
						   else
						   {
						   	$brand_n='';
						   }
						  
						   $prescription_data = array(
							"prescription_id"=>$data_id,
							"medicine_name"=>$new_pres_array[$i]['medicine_name'],
							"medicine_brand"=>$brand_n,
							"medicine_salt"=>$medicine_salt_n,
							"medicine_type"=>$medicine_type_n,
							"medicine_dose"=>$medicine_dose_n,
							"medicine_duration"=>$medicine_duration_n,
							"medicine_frequency"=>$medicine_frequency_n,
							"date"=>date('Y-m-d',strtotime($medicine_date_n)),
							"left_eye"=>$medicine_left_eye,
							"right_eye"=>$medicine_right_eye,
							
							"medicine_advice"=>$medicine_advice_n
							);

						
						$this->db->insert('hms_eye_prescription_patient_pres',$prescription_data); 
						//echo $this->db->last_query();
						$test_data_id = $this->db->insert_id(); 
						}
					//die;

					}
				}
				
			
			

			 /* prescription */




			if(!empty($post['next_appointment_date']))
		    {
		       $booking_id = $post['booking_id'];
		       $this->load->model('opd/opd_model'); 
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
						'booking_type'=>1,
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
				$this->db->insert('hms_opd_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
	            //echo $this->db->last_query(); exit;
		 	} 


		 // 	if(!empty($post['data']))
			// {    
			// 	$current_date = date('Y-m-d H:i:s');
	  //           foreach($post['data'] as $key=>$val)
	  //           {
	            	
 	 //            	$data = array(
	  //                              "branch_id"=>$user_data['parent_id'],
	  //                              "type"=>2,
	  //                              "booking_id"=>$data_id,
	  //                              "vitals_id"=>$key,
	  //                              "vitals_value"=>$val['name'],
	                               
	  //                             );
	              
	  //             $this->db->insert('hms_branch_vitals',$data);
	  //     		  $id = $this->db->insert_id();
	  //           } 
			// }

		 // 
		}
				return $data_id;
			
	
    }
    public function get_by_prescription_id($prescription_id)
	{
		$this->db->select("hms_eye_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.adhar_no"); 
		$this->db->from('hms_eye_prescription'); 
		$this->db->where('hms_eye_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_eye_prescription.patient_id','left');
		//$this->db->join('hms_opd_prescription_patient_test','hms_opd_prescription_patient_test.prescription_id=hms_opd_prescription.id','left');
		//$this->db->join('hms_opd_prescription_patient_pres','hms_opd_prescription_patient_pres.prescription_id=hms_opd_prescription.id','left');
		
		//$query = $this->db->get(); 
		$result_pre['prescription_list']= $this->db->get()->result();
		//echo $this->db->last_query();die;
		
		$this->db->select('hms_eye_prescription_patient_test.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = hms_eye_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_eye_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_prescription_patient_test');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_eye_prescription_patient_pres.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = hms_eye_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_eye_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_prescription_patient_pres');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();

		$this->db->select('hms_eye_cheif_complain_vals.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = hms_eye_cheif_complain_vals.eye_prescription_id'); 
		$this->db->where('hms_eye_cheif_complain_vals.eye_prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_cheif_complain_vals');
		$result_pre['prescription_list']['cheif_compalin']=$this->db->get()->result();

		$this->db->select('hms_eye_systemic_illness_vals.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = 	hms_eye_systemic_illness_vals.eye_prescription_id'); 
		$this->db->where('hms_eye_systemic_illness_vals.eye_prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_systemic_illness_vals');
		$result_pre['prescription_list']['systemic_illness']=$this->db->get()->result();


		$this->db->select('hms_eye_diagnosis_vals.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = 	hms_eye_diagnosis_vals.eye_prescription_id'); 
		$this->db->where('hms_eye_diagnosis_vals.eye_prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_diagnosis_vals');
		$result_pre['prescription_list']['diagnosis_list']=$this->db->get()->result();

		return $result_pre;

	}
	public function check_unit($medicine_unit="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		 if(!empty($medicine_unit)){
			$this->db->where('medicine_unit',$medicine_unit);
		}
		 $this->db->where('branch_id',$users_data['parent_id']); 
		
		$query = $this->db->get('hms_medicine_unit');
		$result = $query->result(); 

		return $result; 
	}
	public function check_comapny_name($medicine_comapny_name="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		 if(!empty($medicine_comapny_name)){
			$this->db->where('company_name',$medicine_comapny_name);
		}
		 $this->db->where('branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_medicine_company');
		$result = $query->result(); 

		return $result; 
	}

	public function get_detail_by_prescription_id($prescription_id)
	{
		$this->db->select("hms_eye_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_eye_prescription.patient_bp as patientbp,hms_eye_prescription.patient_temp as patienttemp,hms_eye_prescription.patient_weight as patientweight,hms_eye_prescription.patient_height as patientpr,hms_eye_prescription.patient_spo2 as patientspo,hms_eye_prescription.patient_rbs as patientrbs"); 



		$this->db->from('hms_eye_prescription'); 
		$this->db->where('hms_eye_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_eye_prescription.patient_id','left');
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_eye_prescription.booking_id','left'); 
		$result_pre['prescription_list']= $this->db->get()->result();
		//echo $this->db->last_query();
		
		$this->db->select('hms_eye_prescription_patient_test.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = hms_eye_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_eye_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_prescription_patient_test');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_eye_prescription_patient_pres.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = hms_eye_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_eye_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_prescription_patient_pres');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();


		$this->db->select('hms_eye_cheif_complain_vals.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = hms_eye_cheif_complain_vals.eye_prescription_id'); 
		$this->db->where('hms_eye_cheif_complain_vals.eye_prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_cheif_complain_vals');
		$result_pre['prescription_list']['cheif_compalin']=$this->db->get()->result();
		

		$this->db->select('hms_eye_systemic_illness_vals.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = 	hms_eye_systemic_illness_vals.eye_prescription_id'); 
		$this->db->where('hms_eye_systemic_illness_vals.eye_prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_systemic_illness_vals');
		$result_pre['prescription_list']['systemic_illness']=$this->db->get()->result();


		$this->db->select('hms_eye_diagnosis_vals.*');
		$this->db->join('hms_eye_prescription','hms_eye_prescription.id = 	hms_eye_diagnosis_vals.eye_prescription_id'); 
		$this->db->where('hms_eye_diagnosis_vals.eye_prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_eye_diagnosis_vals');
		$result_pre['prescription_list']['diagnosis_list']=$this->db->get()->result();

		return $result_pre;

	}
	function template_format($data="",$branch_id='')
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_eye_branch_prescription_setting.*');
    	$this->db->where($data);
    	if(!empty($branch_id))
    	{
    		$this->db->where('hms_eye_branch_prescription_setting.branch_id = "'.$branch_id.'"');
    	}
    	else
    	{
    		$this->db->where('hms_eye_branch_prescription_setting.branch_id = "'.$users_data['parent_id'].'"');
    	}
    	 
    	$this->db->from('hms_eye_branch_prescription_setting');
    	$result=$this->db->get()->row();
    	return $result;

    }
    function get_eye_prescription($prescription_id='')
	{
		$this->db->select("hms_eye_prescription_patient_pres.*"); 
		$this->db->from('hms_eye_prescription_patient_pres'); 
		$this->db->where('hms_eye_prescription_patient_pres.prescription_id',$prescription_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}

	function get_eye_test($prescription_id='')
	{
		$this->db->select("hms_eye_prescription_patient_test.*"); 
		$this->db->from('hms_eye_prescription_patient_test'); 
		$this->db->where('hms_eye_prescription_patient_test.prescription_id',$prescription_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}
	public function get_by_ids($id)
	{
		$this->db->select("hms_eye_prescription.*,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id");  //,hms_opd_prescription_patient_test.*,hms_opd_prescription_patient_pres.*
		$this->db->from('hms_eye_prescription'); 
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_eye_prescription.booking_id','left');
		$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left');
		$this->db->where('hms_eye_prescription.id',$id);
		$this->db->where('hms_eye_prescription.is_deleted','0'); 
		$query = $this->db->get(); 
		echo $this->db->last_query();die();
		return $query->row_array();
		
	}

	public function save_file($filename="")
	{
		//print_r($filename);die;
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'prescription_id'=>$post['prescription_id'],
					'branch_id' => $user_data['parent_id'],
					'status' =>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(!empty($filename))
			{
			   $this->db->set('prescription_files',$filename);
			}
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_eye_prescription_files',$data);  
		}
		else{    
			if(!empty($filename))
			{
			   $this->db->set('prescription_files',$filename);
			}
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_eye_prescription_files',$data);               
		} 	
	}

	public function get_prescription_files($prescription_id='')
	{
		if(!empty($prescription_id) && $prescription_id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->select('*');
			$this->db->where('is_deleted',0);
			$this->db->where('status',1);
			$this->db->where('prescription_id',$prescription_id);
			$query = $this->db->get('hms_eye_prescription_files'); 
			$result = $query->result(); 
			//echo $this->db->last_query(); exit;
			return $result;
			
    	} 
	}

	public function delete_eye($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('flag_id',$id);
			$this->db->update('hms_opd_prescription');

			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_eye_prescription');

			
    	} 
    }

    public function get_detail_by_booking_id($id,$branch_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_opd_booking.*,hms_opd_booking.next_app_date as next_appointment_date,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_opd_booking.patient_bp as patientbp,hms_opd_booking.patient_temp as patienttemp,hms_opd_booking.patient_weight as patientweight,hms_opd_booking.patient_height as patientpr,hms_opd_booking.patient_spo2 as patientspo,hms_opd_booking.patient_rbs as patientrbs');
		$this->db->from('hms_opd_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
		if(!empty($branch_id))
		{
			$this->db->where('hms_opd_booking.branch_id',$branch_id); 
		}
		else
		{
			$this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 	
		}
		
		$this->db->where('hms_opd_booking.id',$id);
		$this->db->where('hms_opd_booking.is_deleted','0');
		 
		$result_pre['prescription_list']= $this->db->get()->result();
		//echo "<pre>";print_r($result_pre); exit;
		return $result_pre;
	}

 public function save()
 {
 	$user_data = $this->session->userdata('auth_users');
	$post=$this->input->post();
	//echo "<pre>"; print_r($post); die;
		$history_flag=isset($post['print_history_flag']) ? '1': '0';
		$contactlens_flag=isset($post['print_contactlens_flag']) ? '1': '0';
		$glassesprescriptions_flag=isset($post['print_glassesprescriptions_flag']) ? '1': '0';
		$intermediate_glasses_prescriptions_flag=isset($post['print_intermediate_glasses_prescriptions_flag']) ? '1': '0';
		$examination_flag=isset($post['print_examination_flag']) ? '1': '0';
		$drawing_flag=isset($post['print_drawing_flag']) ? '1': '0';
		$diagnosis_flag=isset($post['print_diagnosis_flag']) ? '1': '0';
		$investigations_flag=isset($post['print_investigations_flag']) ? '1': '0';
		$advice_flag=isset($post['print_advice_flag']) ? '1': '0';
		$biometry_flag=isset($post['print_biometry_flag']) ? '1': '0';
	   	$pres_data = array('branch_id'=>$post['branch_id'], 'booking_code'=>$post['booking_code'], 'patient_id'=>$post['patient_id'], 'booking_id'=>$post['booking_id'],'history_flag'=>$history_flag, 'contactlens_flag'=>$contactlens_flag, 'glassesprescriptions_flag'=>$glassesprescriptions_flag,'intermediate_glasses_prescriptions_flag'=>$intermediate_glasses_prescriptions_flag,'examination_flag'=>$examination_flag, 'drawing_flag'=>$drawing_flag,'diagnosis_flag'=>$diagnosis_flag,'investigations_flag'=>$investigations_flag,'advice_flag'=>$advice_flag,'biometry_flag'=>$biometry_flag, 'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$post['branch_id']);

		   if(!empty($post['prescrption_id']) || $post['prescrption_id'] !='')
			{
				if(!empty($post['sale_id']) || $post['sale_id'] !=0)
				{		
				  $sale_id=$post['sale_id'];			
				 $this->db->where('sales_id',$post['sale_id']);
       			 $this->db->delete('hms_medicine_sale_to_medicine');
			     $this->db->where(array('branch_id'=>$post['branch_id'],'parent_id'=>$post['sale_id'],'type'=>8));
			           $this->db->delete('hms_medicine_stock');
				}

				$prescriptionid=$post['prescrption_id'];
			   $this->db->where('id',$post['prescrption_id']);
			   $this->db->where('booking_id',$post['booking_id']);
			   $this->db->where('branch_id',$post['branch_id']);
			   $this->db->update('hms_std_eye_prescription',$pres_data);
			}
			else
			{
				 if(!empty($post['advs']['medication']))
				 {
				  $sale_no = generate_unique_id(16);
				  $msale = array(
				                'branch_id'=>$post['branch_id'],
				                'patient_id'=>$post['patient_id'],
				                "ipd_id"=>'0',
				                'opd_id'=>'0',
				                'sale_no'=>$sale_no,
				                'sale_date'=>date('Y-m-d'),
				                'total_amount'=>'0.00',
				                'net_amount'=>'0.00',
				                'discount'=>'0.00',
				                'refered_id'=>$post['refered_id'],
				                'simulation_id'=>$post['simulation_id'],
				                "sgst"=>'0.00',
				                "igst"=>'0.00',
				                "cgst"=>'0.00',
				                "discount_percent"=>'0',
				                'payment_mode'=>'1',
				                'balance'=>'0.00',
				                'created_by'=>$user_data['id'],
				                'created_date'=>date('Y-m-d H:i:s')
				              ); 
				        $this->db->insert('hms_medicine_sale',$msale);
				         $sale_id=$this->db->insert_id();
				         $this->db->set('sale_id',$sale_id);
				         $this->db->set('created_date',date('Y-m-d H:i:s'));
				         $this->db->insert('hms_std_eye_prescription',$pres_data);
				         //echo $this->db->last_query(); exit;
			 			 $prescriptionid=$this->db->insert_id();
				     }
		    }
		    $medi_total_amount=0;
		    $total_amt_igst=0;
	        $total_amt_cgst=0;
	        $total_amt_sgst=0;
	        $total_new_without_amount=0;
	        $tot_discount_amount=0;

		    foreach ($post['advs']['medication'] as $key => $medicine) 
			{
				$data_expi= $this->get_manu_expiry_date($medicine['med_id'], $post['branch_id']);
				$this->update_medicine_freqdata($medicine['med_id'], $post['branch_id'], $post['patient_id'],$post['booking_id'], $prescriptionid);

				 $per_pic_amount= number_format($data_expi->mrp/$data_expi->conversion,2,'.','');
                 $tot_qty_with_rate= $per_pic_amount*$medicine['mqty']; 
                 $total_new_other_amount = $tot_qty_with_rate-($tot_qty_with_rate/100)*$data_expi->discount;
                 $medi_total_amount +=$total_new_other_amount;
                 $total_amt_igst += ($total_new_other_amount/100)*$data_expi->igst;
				 $total_amt_cgst += ($total_new_other_amount/100)*$data_expi->cgst;
				 $total_amt_sgst += ($total_new_other_amount/100)*$data_expi->sgst; 
				 $total_new_without_amount += $total_new_other_amount-($total_amt_igst+$total_amt_cgst+$total_amt_sgst);
				 $tot_discount_amount += ($tot_qty_with_rate)/100*$data_expi->discount; 
			          $data_purchase_topurchase = array(
			          "sales_id"=>$sale_id,
			          'medicine_id'=>$medicine['med_id'],
			          'qty'=>$medicine['mqty'],
			          'discount'=>$data_expi->discount,
			          'sgst'=>$data_expi->sgst,
			          'igst'=>$data_expi->igst,
			          'batch_no'=>$data_expi->batch_no,
			          'conversion'=>$data_expi->conversion,
			          'manuf_date'=>$data_expi->manuf_date,
			          'expiry_date'=>$data_expi->expiry_date,
			          'cgst'=>$data_expi->cgst,
			          'hsn_no'=>$data_expi->hsn_no,
			          'sgst'=>$data_expi->sgst,
			          'igst'=>$data_expi->igst,
			          'cgst'=>$data_expi->cgst,
			          'bar_code'=>$data_expi->bar_code,
			          'total_amount'=>$total_new_other_amount,
			          'per_pic_price'=>$per_pic_amount,
			          'created_date'=>date('Y-m-d H:i:s')
			          );
			          $this->db->insert('hms_medicine_sale_to_medicine',$data_purchase_topurchase);


			          $data_new_stock=array(
			          	"branch_id"=>$user_data['parent_id'],
			            "type"=>8,
			            "parent_id"=>$sale_id,
			            "m_id"=>$medicine['med_id'],
			            "credit"=>$medicine['mqty'],
			            "debit"=>'0',
			            "mrp"=>$data_expi->mrp,
			            'batch_no'=>$data_expi->batch_no,
			          	'conversion'=>$data_expi->conversion,
			          	'manuf_date'=>$data_expi->manuf_date,
			          	'expiry_date'=>$data_expi->expiry_date,
			            "discount"=>$data_expi->discount,
			            'sgst'=>$data_expi->sgst,
			            'igst'=>$data_expi->igst,
			            'cgst'=>$data_expi->cgst,
			            'hsn_no'=>$data_expi->hsn_no,
			            'bar_code'=>$data_expi->bar_code,
			            "created_by"=>$user_data['id'],   
			            'per_pic_price'=>$per_pic_amount,         
			            "created_date"=>date('Y-m-d H:i:s')
			            );					
					$this->db->insert('hms_medicine_stock',$data_new_stock);					
			}
			if(!empty($sale_id) || $sale_id !=0)
				{
					  $msale = array(
				                'total_amount'=>$total_new_without_amount,
				                'net_amount'=>$medi_total_amount,
				                'discount'=>$tot_discount_amount,
				                "sgst"=>$total_amt_sgst,
				                "igst"=>$total_amt_igst,
				                "cgst"=>$total_amt_cgst,
				                "discount_percent"=>'0',
				                'payment_mode'=>'1',
				              ); 
					    $this->db->where('id',$sale_id);
				        $this->db->update('hms_medicine_sale',$msale);
				}
     
     		$advice_medication=$post['advs']['medication'];
			$advice_procedure=$post['advs']['procedures'];
			$advice_referral=$post['advs']['referral'];	
			$advice_advice=$post['advs']['advice'];	 
			$this->update_appointment_type($post['booking_id'],$post['advs']['advice']['app_type']);

		
			$advice_comm=$post['advs']['comments'];

			if($post['advs']['advice']['make_app']=='1' && $post['advs']['advice']['days'] >=1)
			{
				$this->add_next_appointment($post['branch_id'], $post['patient_id'], $post['booking_id'],$post['advs']['advice']['date'], $post['advs']['advice']['time']);
			}
			
			$data_advice=array('branch_id'=>$post['branch_id'], 'booking_code'=>$post['booking_code'], 'pres_id'=>$prescriptionid, 'patient_id'=>$post['patient_id'], 'booking_id'=>$post['booking_id'] , 'medication_tab'=>json_encode($advice_medication), 'proceduces_tab'=>json_encode($advice_procedure), 'referral_tab'=>json_encode($advice_referral),'advice_tab'=>json_encode($advice_advice), 'comments'=>json_encode($advice_comm), 'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$user_data['id'], 'created_date'=>date('Y-m-d H:i:s'));
			
	// investigation  
	$opthal_set=$post['investig_name']['ophthal'];
	$laboratory_set=$post['investig_name']['lab'];
	$radiology_set=$post['investig_name']['radio'];
	$invest_comments=array('comment_ophthal'=>$post['comment_ophthal'],'comment_lab'=>$post['comment_lab'],'comment_radiology'=>$post['comment_radiology']);

	$data_investigation=array('branch_id'=>$post['branch_id'], 'booking_code'=>$post['booking_code'], 'pres_id'=>$prescriptionid, 'patient_id'=>$post['patient_id'], 'booking_id'=>$post['booking_id'] , 'ophthal_set'=>json_encode($opthal_set), 'laboratory_set'=>json_encode($laboratory_set), 'radiology_set'=>json_encode($radiology_set),'invest_comm'=>json_encode($invest_comments), 'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$user_data['id'], 'created_date'=>date('Y-m-d H:i:s'));
	// examination

	$examnsn_lod_normal= isset($post['examnsn_lod_normal']) ? $post['examnsn_lod_normal'] :'0';
	$examnsn_rod_normal= isset($post['examnsn_rod_normal']) ? $post['examnsn_rod_normal'] :'0';
	$examnsn_apndgs_l_eyelids=isset($post['examnsn_apndgs_l_eyelids']) ? $post['examnsn_apndgs_l_eyelids'] : '0';
	$examnsn_apndgs_l_eyelshs=isset($post['examnsn_apndgs_l_eyelshs']) ? $post['examnsn_apndgs_l_eyelshs'] : '0';
	$examnsn_apndgs_l_lcmlc=isset($post['examnsn_apndgs_l_lcmlc']) ? $post['examnsn_apndgs_l_lcmlc'] : '0';
	$examnsn_apndgs_l_syrn=isset($post['examnsn_apndgs_l_syrn']) ? $post['examnsn_apndgs_l_syrn'] : '0';
	$examnsn_conjtv_l_consn_sub=isset($post['examnsn_conjtv_l_consn_sub']) ? $post['examnsn_conjtv_l_consn_sub'] : '0';
	$examnsn_cornea_l_srfs_nrml=isset($post['examnsn_cornea_l_srfs_nrml']) ? $post['examnsn_cornea_l_srfs_nrml'] : '0';
	$examnsn_cornea_l_srfs_epcdft=isset($post['examnsn_cornea_l_srfs_epcdft']) ? $post['examnsn_cornea_l_srfs_epcdft'] : '0';
	$examnsn_cornea_l_srfs_think=isset($post['examnsn_cornea_l_srfs_think']) ? $post['examnsn_cornea_l_srfs_think'] : '0';
	$examnsn_cornea_l_srfs_scar=isset($post['examnsn_cornea_l_srfs_scar']) ? $post['examnsn_cornea_l_srfs_scar'] : '0';
	$examnsn_cornea_l_srfs_vascu=isset($post['examnsn_cornea_l_srfs_vascu']) ? $post['examnsn_cornea_l_srfs_vascu'] : '0';
	$examnsn_cornea_l_srfs_dgensn=isset($post['examnsn_cornea_l_srfs_dgensn']) ? $post['examnsn_cornea_l_srfs_dgensn'] : '0';
	$examnsn_cornea_l_srfs_dstph=isset($post['examnsn_cornea_l_srfs_dstph']) ? $post['examnsn_cornea_l_srfs_dstph'] : '0';
	$examnsn_cornea_l_srfs_fbd=isset($post['examnsn_cornea_l_srfs_fbd']) ? $post['examnsn_cornea_l_srfs_fbd'] : '0';
	$examnsn_cornea_l_srfs_tear=isset($post['examnsn_cornea_l_srfs_tear']) ? $post['examnsn_cornea_l_srfs_tear'] : '0';
	$examnsn_cornea_l_srfs_kp=isset($post['examnsn_cornea_l_srfs_kp']) ? $post['examnsn_cornea_l_srfs_kp'] : '0';
	$examnsn_cornea_l_srfs_opct=isset($post['examnsn_cornea_l_srfs_opct']) ? $post['examnsn_cornea_l_srfs_opct'] : '0';
	$examnsn_cornea_l_srfs_ulcr=isset($post['examnsn_cornea_l_srfs_ulcr']) ? $post['examnsn_cornea_l_srfs_ulcr'] : '0';

	$examnsn_pupl_l_shp= isset($post['examnsn_pupl_l_shp']) ? $post['examnsn_pupl_l_shp'] : '0';
	$examnsn_ems_l_sqnt= isset($post['examnsn_ems_l_sqnt']) ? $post['examnsn_ems_l_sqnt'] : '0';
	$examnsn_ems_l_sqnt_trpa= isset($post['examnsn_ems_l_sqnt_trpa']) ? $post['examnsn_ems_l_sqnt_trpa'] : '0';
	$examnsn_ems_l_para_mr= isset($post['examnsn_ems_l_para_mr']) ? $post['examnsn_ems_l_para_mr'] : '0';
	$examnsn_ems_l_para_lr= isset($post['examnsn_ems_l_para_lr']) ? $post['examnsn_ems_l_para_lr'] : '0';
	$examnsn_ems_l_para_so= isset($post['examnsn_ems_l_para_so']) ? $post['examnsn_ems_l_para_so'] : '0';
	$examnsn_ems_l_para_sr= isset($post['examnsn_ems_l_para_sr']) ? $post['examnsn_ems_l_para_sr'] : '0';
	$examnsn_ems_l_para_ir= isset($post['examnsn_ems_l_para_ir']) ? $post['examnsn_ems_l_para_ir'] : '0';
	$examnsn_ems_l_para_io= isset($post['examnsn_ems_l_para_io']) ? $post['examnsn_ems_l_para_io'] : '0';
	$examnsn_ems_l_sqnt_trpa_v= isset($post['examnsn_ems_l_sqnt_trpa_v']) ? $post['examnsn_ems_l_sqnt_trpa_v'] : '0';
	$examnsn_ems_l_sqnt_trpa_h= isset($post['examnsn_ems_l_sqnt_trpa_h']) ? $post['examnsn_ems_l_sqnt_trpa_h'] : '0';
	$examnsn_ems_l_sqnt_trpa_h_xt= isset($post['examnsn_ems_l_sqnt_trpa_h_xt']) ? $post['examnsn_ems_l_sqnt_trpa_h_xt'] : '0';
	$examnsn_ems_l_sqnt_trpa_h_et= isset($post['examnsn_ems_l_sqnt_trpa_h_et']) ? $post['examnsn_ems_l_sqnt_trpa_h_et'] : '0';

	$examnsn_fundus_l_dilate=isset($post['examnsn_fundus_l_dilate']) ? $post['examnsn_fundus_l_dilate'] : '';




	$examnsn_apndgs_r_eyelids=isset($post['examnsn_apndgs_r_eyelids']) ? $post['examnsn_apndgs_r_eyelids'] : '0';
	$examnsn_apndgs_r_eyelshs=isset($post['examnsn_apndgs_r_eyelshs']) ? $post['examnsn_apndgs_r_eyelshs'] : '0';
	$examnsn_apndgs_r_lcmlc=isset($post['examnsn_apndgs_r_lcmlc']) ? $post['examnsn_apndgs_r_lcmlc'] : '0';
	$examnsn_apndgs_r_syrn=isset($post['examnsn_apndgs_r_syrn']) ? $post['examnsn_apndgs_r_syrn'] : '0';
	$examnsn_conjtv_r_consn_sub=isset($post['examnsn_conjtv_r_consn_sub']) ? $post['examnsn_conjtv_r_consn_sub'] : '0';
	$examnsn_cornea_r_srfs_nrml=isset($post['examnsn_cornea_r_srfs_nrml']) ? $post['examnsn_cornea_r_srfs_nrml'] : '0';
	$examnsn_cornea_r_srfs_epcdft=isset($post['examnsn_cornea_r_srfs_epcdft']) ? $post['examnsn_cornea_r_srfs_epcdft'] : '0';
	$examnsn_cornea_r_srfs_think=isset($post['examnsn_cornea_r_srfs_think']) ? $post['examnsn_cornea_r_srfs_think'] : '0';
	$examnsn_cornea_r_srfs_scar=isset($post['examnsn_cornea_r_srfs_scar']) ? $post['examnsn_cornea_r_srfs_scar'] : '0';
	$examnsn_cornea_r_srfs_vascu=isset($post['examnsn_cornea_r_srfs_vascu']) ? $post['examnsn_cornea_r_srfs_vascu'] : '0';
	$examnsn_cornea_r_srfs_dgensn=isset($post['examnsn_cornea_r_srfs_dgensn']) ? $post['examnsn_cornea_r_srfs_dgensn'] : '0';
	$examnsn_cornea_r_srfs_dstph=isset($post['examnsn_cornea_r_srfs_dstph']) ? $post['examnsn_cornea_r_srfs_dstph'] : '0';
	$examnsn_cornea_r_srfs_fbd=isset($post['examnsn_cornea_r_srfs_fbd']) ? $post['examnsn_cornea_r_srfs_fbd'] : '0';
	$examnsn_cornea_r_srfs_tear=isset($post['examnsn_cornea_r_srfs_tear']) ? $post['examnsn_cornea_r_srfs_tear'] : '0';
	$examnsn_cornea_r_srfs_kp=isset($post['examnsn_cornea_r_srfs_kp']) ? $post['examnsn_cornea_r_srfs_kp'] : '0';
	$examnsn_cornea_r_srfs_opct=isset($post['examnsn_cornea_r_srfs_opct']) ? $post['examnsn_cornea_r_srfs_opct'] : '0';
	$examnsn_cornea_r_srfs_ulcr=isset($post['examnsn_cornea_r_srfs_ulcr']) ? $post['examnsn_cornea_r_srfs_ulcr'] : '0';
	$examnsn_pupl_r_shp= isset($post['examnsn_pupl_r_shp']) ? $post['examnsn_pupl_r_shp'] : '0';
	$examnsn_ems_r_sqnt= isset($post['examnsn_ems_r_sqnt']) ? $post['examnsn_ems_r_sqnt'] : '0';
	$examnsn_ems_r_sqnt_trpa= isset($post['examnsn_ems_r_sqnt_trpa']) ? $post['examnsn_ems_r_sqnt_trpa'] : '0';
	$examnsn_ems_r_para_mr= isset($post['examnsn_ems_r_para_mr']) ? $post['examnsn_ems_r_para_mr'] : '0';
	$examnsn_ems_r_para_lr= isset($post['examnsn_ems_r_para_lr']) ? $post['examnsn_ems_r_para_lr'] : '0';
	$examnsn_ems_r_para_so= isset($post['examnsn_ems_r_para_so']) ? $post['examnsn_ems_r_para_so'] : '0';
	$examnsn_ems_r_para_sr= isset($post['examnsn_ems_r_para_sr']) ? $post['examnsn_ems_r_para_sr'] : '0';
	$examnsn_ems_r_para_ir= isset($post['examnsn_ems_r_para_ir']) ? $post['examnsn_ems_r_para_ir'] : '0';
	$examnsn_ems_r_para_io= isset($post['examnsn_ems_r_para_io']) ? $post['examnsn_ems_r_para_io'] : '0';
	$examnsn_ems_r_sqnt_trpa_v= isset($post['examnsn_ems_r_sqnt_trpa_v']) ? $post['examnsn_ems_r_sqnt_trpa_v'] : '0';
	$examnsn_ems_r_sqnt_trpa_h= isset($post['examnsn_ems_r_sqnt_trpa_h']) ? $post['examnsn_ems_r_sqnt_trpa_h'] : '0';
	$examnsn_ems_r_sqnt_trpa_h_xt= isset($post['examnsn_ems_r_sqnt_trpa_h_xt']) ? $post['examnsn_ems_r_sqnt_trpa_h_xt'] : '0';
	$examnsn_ems_r_sqnt_trpa_h_et= isset($post['examnsn_ems_r_sqnt_trpa_h_et']) ? $post['examnsn_ems_r_sqnt_trpa_h_et'] : '0';
	$toggle_fundus_nrml= isset($post['toggle_fundus_nrml']) ? $post['toggle_fundus_nrml'] : '0';
	$toggle_fundus_ri8_nrml= isset($post['toggle_fundus_ri8_nrml']) ? $post['toggle_fundus_ri8_nrml'] : '0';
	$examnsn_fundus_r_dialet=isset($post['examnsn_fundus_r_dialet']) ? $post['examnsn_fundus_r_dialet'] : '';



		 $general_examina= array('examnsn_gen_examina'=>$post['examnsn_gen_examina'], 'examnsn_gen_examina_eye'=>$post['examnsn_gen_examina_eye'], 'general_exam_abnormal'=>$post['general_exam_abnormal'], 'examnsn_lod_normal'=>$examnsn_lod_normal,'examnsn_rod_normal'=>$examnsn_rod_normal);
		 $appearance=array('examnsn_aprnc_l_phthisis'=>$post['examnsn_aprnc_l_phthisis'], 'examnsn_aprnc_l_anthms'=>$post['examnsn_aprnc_l_anthms'], 'examnsn_aprnc_l_mcrthms'=>$post['examnsn_aprnc_l_mcrthms'], 'examnsn_aprnc_l_artfsl'=>$post['examnsn_aprnc_l_artfsl'], 'examnsn_aprnc_l_prptsis'=>$post['examnsn_aprnc_l_prptsis'], 'examnsn_aprnc_l_dsptpa'=>$post['examnsn_aprnc_l_dsptpa'], 'examnsn_aprnc_l_injrd'=>$post['examnsn_aprnc_l_injrd'], 'examnsn_aprnc_l_swln'=>$post['examnsn_aprnc_l_swln'],'examnsn_aprnc_l_comm'=>$post['examnsn_aprnc_l_comm'], 'examnsn_aprnc_r_phthisis'=>$post['examnsn_aprnc_r_phthisis'], 'examnsn_aprnc_r_anthms'=>$post['examnsn_aprnc_r_anthms'], 'examnsn_aprnc_r_mcrthms'=>$post['examnsn_aprnc_r_mcrthms'], 'examnsn_aprnc_r_artfsl'=>$post['examnsn_aprnc_r_artfsl'], 'examnsn_aprnc_r_prptsis'=>$post['examnsn_aprnc_r_prptsis'], 'examnsn_aprnc_r_dsptpa'=>$post['examnsn_aprnc_r_dsptpa'], 'examnsn_aprnc_r_injrd'=>$post['examnsn_aprnc_r_injrd'], 'examnsn_aprnc_r_swln'=>$post['examnsn_aprnc_r_swln'], 'examnsn_aprnc_r_comm'=>$post['examnsn_aprnc_r_comm'],'examnsn_aprnc_l_update'=>$post['examnsn_aprnc_l_update'],'examnsn_aprnc_r_update'=>$post['examnsn_aprnc_r_update']);
		 
		 $appendages =array('examnsn_apndgs_l_eyelids'=>$examnsn_apndgs_l_eyelids, 'examnsn_apndgs_l_eyelids_chzn'=>$post['examnsn_apndgs_l_eyelids_chzn'], 'examnsn_apndgs_l_eyelids_ptss'=>$post['examnsn_apndgs_l_eyelids_ptss'], 'examnsn_apndgs_l_eyelids_swln'=>$post['examnsn_apndgs_l_eyelids_swln'], 'examnsn_apndgs_l_eyelids_intrpn'=>$post['examnsn_apndgs_l_eyelids_intrpn'], 'examnsn_apndgs_l_eyelids_ectrpn'=>$post['examnsn_apndgs_l_eyelids_ectrpn'], 'examnsn_apndgs_l_eyelids_mass'=>$post['examnsn_apndgs_l_eyelids_mass'], 'examnsn_apndgs_l_eyelids_mbts'=>$post['examnsn_apndgs_l_eyelids_mbts'],
		 	'examnsn_apndgs_l_eyelshs'=>$examnsn_apndgs_l_eyelshs,'examnsn_apndgs_l_eyelshs_tchs'=>$post['examnsn_apndgs_l_eyelshs_tchs'],'examnsn_apndgs_l_eyelshs_dtchs'=>$post['examnsn_apndgs_l_eyelshs_dtchs'], 'examnsn_apndgs_l_lcmlc'=>$examnsn_apndgs_l_lcmlc,'examnsn_apndgs_l_lcmlc_swln'=>$post['examnsn_apndgs_l_lcmlc_swln'],'examnsn_apndgs_l_lcmlc_regusn'=>$post['examnsn_apndgs_l_lcmlc_regusn'],'examnsn_apndgs_l_syrn'=>$examnsn_apndgs_l_syrn,'examnsn_apndgs_l_syrn_syrn'=>$post['examnsn_apndgs_l_syrn_syrn'], 'examnsn_apndgs_l_comm'=>$post['examnsn_apndgs_l_comm'],
		 	'examnsn_apndgs_r_eyelids'=>$examnsn_apndgs_r_eyelids, 'examnsn_apndgs_r_eyelids_chzn'=>$post['examnsn_apndgs_r_eyelids_chzn'], 'examnsn_apndgs_r_eyelids_ptss'=>$post['examnsn_apndgs_r_eyelids_ptss'], 'examnsn_apndgs_r_eyelids_swln'=>$post['examnsn_apndgs_r_eyelids_swln'], 'examnsn_apndgs_r_eyelids_intrpn'=>$post['examnsn_apndgs_r_eyelids_intrpn'], 'examnsn_apndgs_r_eyelids_ectrpn'=>$post['examnsn_apndgs_r_eyelids_ectrpn'], 'examnsn_apndgs_r_eyelids_mass'=>$post['examnsn_apndgs_r_eyelids_mass'], 'examnsn_apndgs_r_eyelids_mbts'=>$post['examnsn_apndgs_r_eyelids_mbts'],
		 	'examnsn_apndgs_r_eyelshs'=>$examnsn_apndgs_r_eyelshs,'examnsn_apndgs_r_eyelshs_tchs'=>$post['examnsn_apndgs_r_eyelshs_tchs'],'examnsn_apndgs_r_eyelshs_dtchs'=>$post['examnsn_apndgs_r_eyelshs_dtchs'], 'examnsn_apndgs_r_lcmlc'=>$examnsn_apndgs_r_lcmlc,'examnsn_apndgs_r_lcmlc_swln'=>$post['examnsn_apndgs_r_lcmlc_swln'],'examnsn_apndgs_r_lcmlc_regusn'=>$post['examnsn_apndgs_r_lcmlc_regusn'],'examnsn_apndgs_r_syrn'=>$examnsn_apndgs_r_syrn,'examnsn_apndgs_r_syrn_syrn'=>$post['examnsn_apndgs_r_syrn_syrn'], 'examnsn_apndgs_r_comm'=>$post['examnsn_apndgs_r_comm'],'examnsn_apndgs_l_update'=>$post['examnsn_apndgs_l_update'], 'examnsn_apndgs_r_update'=>$post['examnsn_apndgs_r_update']);
		
		 $conjuctiva=array('examnsn_conjtv_l_consn'=>$post['examnsn_conjtv_l_consn'], 'examnsn_conjtv_l_consn_sub'=>$examnsn_conjtv_l_consn_sub, 'examnsn_conjtv_l_tear'=>$post['examnsn_conjtv_l_tear'], 'examnsn_conjtv_l_cbleb'=>$post['examnsn_conjtv_l_cbleb'], 'examnsn_conjtv_l_sbhrg'=>$post['examnsn_conjtv_l_sbhrg'], 'examnsn_conjtv_l_fbd'=>$post['examnsn_conjtv_l_fbd'], 'examnsn_conjtv_l_flcls'=>$post['examnsn_conjtv_l_flcls'], 'examnsn_conjtv_l_paple'=>$post['examnsn_conjtv_l_paple'], 'examnsn_conjtv_l_pngcla'=>$post['examnsn_conjtv_l_pngcla'], 'examnsn_conjtv_l_ptrgm'=>$post['examnsn_conjtv_l_ptrgm'], 'examnsn_conjtv_l_phctn'=>$post['examnsn_conjtv_l_phctn'],'examnsn_conjtv_l_dseg'=>$post['examnsn_conjtv_l_dseg'],'examnsn_conjtv_l_comm'=>$post['examnsn_conjtv_l_comm'], 'examnsn_conjtv_r_consn'=>$post['examnsn_conjtv_r_consn'], 'examnsn_conjtv_r_consn_sub'=>$examnsn_conjtv_r_consn_sub, 'examnsn_conjtv_r_tear'=>$post['examnsn_conjtv_r_tear'], 'examnsn_conjtv_r_cbleb'=>$post['examnsn_conjtv_r_cbleb'], 'examnsn_conjtv_r_sbhrg'=>$post['examnsn_conjtv_r_sbhrg'], 'examnsn_conjtv_r_fbd'=>$post['examnsn_conjtv_r_fbd'], 'examnsn_conjtv_r_flcls'=>$post['examnsn_conjtv_r_flcls'], 'examnsn_conjtv_r_paple'=>$post['examnsn_conjtv_r_paple'], 'examnsn_conjtv_r_pngcla'=>$post['examnsn_conjtv_r_pngcla'], 'examnsn_conjtv_r_ptrgm'=>$post['examnsn_conjtv_r_ptrgm'], 'examnsn_conjtv_r_phctn'=>$post['examnsn_conjtv_r_phctn'],'examnsn_conjtv_r_dseg'=>$post['examnsn_conjtv_r_dseg'],'examnsn_conjtv_r_comm'=>$post['examnsn_conjtv_r_comm'],'examnsn_conjtv_l_update'=>$post['examnsn_conjtv_l_update'], 'examnsn_conjtv_r_update'=>$post['examnsn_conjtv_r_update']);
		
		 $cornea=array('examnsn_cornea_l_sz'=>$post['examnsn_cornea_l_sz'], 'examnsn_cornea_l_shp'=>$post['examnsn_cornea_l_shp'], 'examnsn_cornea_l_srfs_nrml'=>$post['examnsn_cornea_l_srfs_nrml'],'examnsn_cornea_l_srfs_epcdft'=>$post['examnsn_cornea_l_srfs_epcdft'], 'examnsn_cornea_l_srfs_think'=>$post['examnsn_cornea_l_srfs_think'], 'examnsn_cornea_l_srfs_scar'=>$post['examnsn_cornea_l_srfs_scar'],'examnsn_cornea_l_srfs_vascu'=>$post['examnsn_cornea_l_srfs_vascu'], 'examnsn_cornea_l_srfs_dgensn'=>$post['examnsn_cornea_l_srfs_dgensn'], 'examnsn_cornea_l_srfs_dstph'=>$post['examnsn_cornea_l_srfs_dstph'], 'examnsn_cornea_l_srfs_fbd'=>$post['examnsn_cornea_l_srfs_fbd'], 'examnsn_cornea_l_srfs_tear'=>$post['examnsn_cornea_l_srfs_tear'], 'examnsn_cornea_l_srfs_kp'=>$post['examnsn_cornea_l_srfs_kp'], 'examnsn_cornea_l_srfs_opct'=>$post['examnsn_cornea_l_srfs_opct'], 'examnsn_cornea_l_srfs_ulcr'=>$post['examnsn_cornea_l_srfs_ulcr'], 'examnsn_cornea_l_scht1_mm'=>$post['examnsn_cornea_l_scht1_mm'], 'examnsn_cornea_l_scht1_min'=>$post['examnsn_cornea_l_scht1_min'], 'examnsn_cornea_l_scht2_mm'=>$post['examnsn_cornea_l_scht2_mm'], 'examnsn_cornea_l_scht2_min'=>$post['examnsn_cornea_l_scht2_min'], 'examnsn_cornea_l_comm'=>$post['examnsn_cornea_l_comm'], 'examnsn_cornea_r_sz'=>$post['examnsn_cornea_r_sz'], 'examnsn_cornea_r_shp'=>$post['examnsn_cornea_r_shp'], 'examnsn_cornea_r_srfs_nrml'=>$post['examnsn_cornea_r_srfs_nrml'],'examnsn_cornea_r_srfs_epcdft'=>$post['examnsn_cornea_r_srfs_epcdft'], 'examnsn_cornea_r_srfs_think'=>$post['examnsn_cornea_r_srfs_think'], 'examnsn_cornea_r_srfs_scar'=>$post['examnsn_cornea_r_srfs_scar'],'examnsn_cornea_r_srfs_vascu'=>$post['examnsn_cornea_r_srfs_vascu'], 'examnsn_cornea_r_srfs_dgensn'=>$post['examnsn_cornea_r_srfs_dgensn'], 'examnsn_cornea_r_srfs_dstph'=>$post['examnsn_cornea_r_srfs_dstph'], 'examnsn_cornea_r_srfs_fbd'=>$post['examnsn_cornea_r_srfs_fbd'], 'examnsn_cornea_r_srfs_tear'=>$post['examnsn_cornea_r_srfs_tear'], 'examnsn_cornea_r_srfs_kp'=>$post['examnsn_cornea_r_srfs_kp'], 'examnsn_cornea_r_srfs_opct'=>$post['examnsn_cornea_r_srfs_opct'], 'examnsn_cornea_r_srfs_ulcr'=>$post['examnsn_cornea_r_srfs_ulcr'], 'examnsn_cornea_r_scht1_mm'=>$post['examnsn_cornea_r_scht1_mm'], 'examnsn_cornea_r_scht1_min'=>$post['examnsn_cornea_r_scht1_min'], 'examnsn_cornea_r_scht2_mm'=>$post['examnsn_cornea_r_scht2_mm'], 'examnsn_cornea_r_scht2_min'=>$post['examnsn_cornea_r_scht2_min'], 'examnsn_cornea_r_comm'=>$post['examnsn_cornea_r_comm'],'examnsn_cornea_l_update'=>$post['examnsn_cornea_l_update'],'examnsn_cornea_r_update'=>$post['examnsn_cornea_r_update']);
		 $aterior_ch=array('examnsn_ac_l_defth'=>$post['examnsn_ac_l_defth'], 'examnsn_ac_l_defth_txt1'=>$post['examnsn_ac_l_defth_txt1'], 'examnsn_ac_l_defth_txt2'=>$post['examnsn_ac_l_defth_txt2'], 'examnsn_ac_l_cells'=>$post['examnsn_ac_l_cells'], 'examnsn_ac_l_cells_txt'=>$post['examnsn_ac_l_cells_txt'], 'examnsn_ac_l_flar'=>$post['examnsn_ac_l_flar'], 'examnsn_ac_l_flar_txt'=>$post['examnsn_ac_l_flar_txt'], 'examnsn_ac_l_hyfma'=>$post['examnsn_ac_l_hyfma'], 'examnsn_ac_l_hyfma_txt'=>$post['examnsn_ac_l_hyfma_txt'], 'examnsn_ac_l_hypn'=>$post['examnsn_ac_l_hypn'], 'examnsn_ac_l_hypn_txt'=>$post['examnsn_ac_l_hypn_txt'], 'examnsn_ac_l_fbd'=>$post['examnsn_ac_l_fbd'], 'examnsn_ac_l_fbd_txt'=>$post['examnsn_ac_l_fbd_txt'], 'examnsn_ac_l_comm'=>$post['examnsn_ac_l_comm'],'examnsn_ac_r_defth'=>$post['examnsn_ac_r_defth'], 'examnsn_ac_r_defth_txt1'=>$post['examnsn_ac_r_defth_txt1'], 'examnsn_ac_r_defth_txt2'=>$post['examnsn_ac_r_defth_txt2'], 'examnsn_ac_r_cells'=>$post['examnsn_ac_r_cells'], 'examnsn_ac_r_cells_txt'=>$post['examnsn_ac_r_cells_txt'], 'examnsn_ac_r_flar'=>$post['examnsn_ac_r_flar'], 'examnsn_ac_r_flar_txt'=>$post['examnsn_ac_r_flar_txt'], 'examnsn_ac_r_hyfma'=>$post['examnsn_ac_r_hyfma'], 'examnsn_ac_r_hyfma_txt'=>$post['examnsn_ac_r_hyfma_txt'], 'examnsn_ac_r_hypn'=>$post['examnsn_ac_r_hypn'], 'examnsn_ac_r_hypn_txt'=>$post['examnsn_ac_r_hypn_txt'], 'examnsn_ac_r_fbd'=>$post['examnsn_ac_r_fbd'], 'examnsn_ac_r_fbd_txt'=>$post['examnsn_ac_r_fbd_txt'], 'examnsn_ac_r_comm'=>$post['examnsn_ac_r_comm'],'examnsn_ac_l_update'=>$post['examnsn_ac_l_update'], 'examnsn_ac_r_update'=>$post['examnsn_ac_r_update']);
		
		 $pupil=array('examnsn_pupl_l_shp'=>$post['examnsn_pupl_l_shp'], 'examnsn_pupl_l_rld'=>$post['examnsn_pupl_l_rld'], 'examnsn_pupl_l_rlc'=>$post['examnsn_pupl_l_rlc'], 'examnsn_pupl_l_apd'=>$post['examnsn_pupl_l_apd'], 'examnsn_pupl_l_comm'=>$post['examnsn_pupl_l_comm'], 'examnsn_pupl_r_shp'=>$post['examnsn_pupl_r_shp'], 'examnsn_pupl_r_rld'=>$post['examnsn_pupl_r_rld'], 'examnsn_pupl_r_rlc'=>$post['examnsn_pupl_r_rlc'], 'examnsn_pupl_r_apd'=>$post['examnsn_pupl_r_apd'], 'examnsn_pupl_r_comm'=>$post['examnsn_pupl_r_comm'],'examnsn_pupl_l_update'=>$post['examnsn_pupl_l_update'], 'examnsn_pupl_r_update'=>$post['examnsn_pupl_r_update']);

		 $iris=array('examnsn_iris_l_shp'=>$post['examnsn_iris_l_shp'], 'examnsn_iris_l_nvi'=>$post['examnsn_iris_l_nvi'], 'examnsn_iris_l_synch'=>$post['examnsn_iris_l_synch'], 'examnsn_iris_l_comm'=>$post['examnsn_iris_l_comm'], 'examnsn_iris_r_shp'=>$post['examnsn_iris_r_shp'], 'examnsn_iris_r_nvi'=>$post['examnsn_iris_r_nvi'], 'examnsn_iris_r_synch'=>$post['examnsn_iris_r_synch'], 'examnsn_iris_r_comm'=>$post['examnsn_iris_r_comm'],'examnsn_iris_l_update'=>$post['examnsn_iris_l_update'], 'examnsn_iris_r_update'=>$post['examnsn_iris_r_update']);
		
		 $lens=array('examnsn_lens_l_ntr'=>$post['examnsn_lens_l_ntr'], 'examnsn_lens_l_psn'=>$post['examnsn_lens_l_psn'], 'examnsn_lens_l_sz'=>$post['examnsn_lens_l_sz'], 'examnsn_lens_l_locsg_ns'=>$post['examnsn_lens_l_locsg_ns'], 'examnsn_lens_l_locsg_c'=>$post['examnsn_lens_l_locsg_c'], 'examnsn_lens_l_locsg_p'=>$post['examnsn_lens_l_locsg_p'], 'examnsn_lens_l_comm'=>$post['examnsn_lens_l_comm'], 'examnsn_lens_r_ntr'=>$post['examnsn_lens_r_ntr'], 'examnsn_lens_r_psn'=>$post['examnsn_lens_r_psn'], 'examnsn_lens_r_sz'=>$post['examnsn_lens_r_sz'], 'examnsn_lens_r_locsg_ns'=>$post['examnsn_lens_r_locsg_ns'], 'examnsn_lens_r_locsg_c'=>$post['examnsn_lens_r_locsg_c'], 'examnsn_lens_r_locsg_p'=>$post['examnsn_lens_r_locsg_p'], 'examnsn_lens_r_comm'=>$post['examnsn_lens_r_comm'],'examnsn_lens_l_update'=>$post['examnsn_lens_l_update'], 'examnsn_lens_r_update'=>$post['examnsn_lens_r_update']);

		 $extraocular_move_squint=array('examnsn_ems_l_unimv'=>$post['examnsn_ems_l_unimv'], 'examnsn_ems_l_bimv'=>$post['examnsn_ems_l_bimv'], 'examnsn_ems_l_comm'=>$post['examnsn_ems_l_comm'], 'examnsn_ems_l_prsm'=>$post['examnsn_ems_l_prsm'], 'examnsn_ems_l_sqnt'=>$post['examnsn_ems_l_sqnt'], 'examnsn_ems_l_sqnt_trpa'=>$post['examnsn_ems_l_sqnt_trpa'], 'examnsn_ems_l_sqnt_phoria'=>$post['examnsn_ems_l_sqnt_phoria'], 'examnsn_ems_l_sqnt_trpa_h'=>$post['examnsn_ems_l_sqnt_trpa_h'], 'examnsn_ems_l_sqnt_trpa_h_et'=>$post['examnsn_ems_l_sqnt_trpa_h_et'], 'examnsn_ems_l_sqnt_trpa_h_xt'=>$post['examnsn_ems_l_sqnt_trpa_h_xt'],'examnsn_ems_l_para_mr'=>$examnsn_ems_l_para_mr,'examnsn_ems_l_para_lr'=>$examnsn_ems_l_para_lr,'examnsn_ems_l_para_so'=>$examnsn_ems_l_para_so,'examnsn_ems_l_para_sr'=>$examnsn_ems_l_para_sr,'examnsn_ems_l_para_ir'=>$examnsn_ems_l_para_ir,'examnsn_ems_l_para_io'=>$examnsn_ems_l_para_io, 'examnsn_ems_l_sqnt_trpa_v'=>$examnsn_ems_l_sqnt_trpa_v, 'examnsn_ems_r_unimv'=>$post['examnsn_ems_r_unimv'], 'examnsn_ems_r_bimv'=>$post['examnsn_ems_r_bimv'], 'examnsn_ems_r_comm'=>$post['examnsn_ems_r_comm'], 'examnsn_ems_r_prsm'=>$post['examnsn_ems_r_prsm'], 'examnsn_ems_r_sqnt'=>$post['examnsn_ems_r_sqnt'], 'examnsn_ems_r_sqnt_trpa'=>$post['examnsn_ems_r_sqnt_trpa'], 'examnsn_ems_r_sqnt_phoria'=>$post['examnsn_ems_r_sqnt_phoria'], 'examnsn_ems_r_sqnt_trpa_h'=>$post['examnsn_ems_r_sqnt_trpa_h'], 'examnsn_ems_r_sqnt_trpa_h_et'=>$post['examnsn_ems_r_sqnt_trpa_h_et'], 'examnsn_ems_r_sqnt_trpa_h_xt'=>$post['examnsn_ems_r_sqnt_trpa_h_xt'],'examnsn_ems_r_para_mr'=>$examnsn_ems_r_para_mr,'examnsn_ems_r_para_lr'=>$examnsn_ems_r_para_lr,'examnsn_ems_r_para_so'=>$examnsn_ems_r_para_so,'examnsn_ems_r_para_sr'=>$examnsn_ems_r_para_sr,'examnsn_ems_r_para_ir'=>$examnsn_ems_r_para_ir,'examnsn_ems_r_para_io'=>$examnsn_ems_r_para_io, 'examnsn_ems_r_sqnt_trpa_v'=>$examnsn_ems_r_sqnt_trpa_v,'examnsn_ems_l_update'=>$post['examnsn_ems_l_update'], 'examnsn_ems_r_update'=>$post['examnsn_ems_r_update']);

		 $intraocular_pressure=array('examnsn_iop_l_value'=>$post['examnsn_iop_l_value'], 'examnsn_iop_l_time'=>$post['examnsn_iop_l_time'], 'examnsn_iop_l_method'=>$post['examnsn_iop_l_method'], 'examnsn_iop_l_comm'=>$post['examnsn_iop_l_comm'], 'examnsn_iop_r_value'=>$post['examnsn_iop_r_value'], 'examnsn_iop_r_time'=>$post['examnsn_iop_r_time'], 'examnsn_iop_r_method'=>$post['examnsn_iop_r_method'], 'examnsn_iop_r_comm'=>$post['examnsn_iop_r_comm']);
		
		 $gonioscopy=array('examnsn_gonispy_l_sup_d1'=>$post['examnsn_gonispy_l_sup_d1'], 'examnsn_gonispy_l_sup_d2'=>$post['examnsn_gonispy_l_sup_d2'], 'examnsn_gonispy_l_sup_d3'=>$post['examnsn_gonispy_l_sup_d3'], 'examnsn_gonispy_l_tmprl_d1'=>$post['examnsn_gonispy_l_tmprl_d1'], 'examnsn_gonispy_l_tmprl_d2'=>$post['examnsn_gonispy_l_tmprl_d2'], 'examnsn_gonispy_l_tmprl_d3'=>$post['examnsn_gonispy_l_tmprl_d3'], 'examnsn_gonispy_l_nsl_d1'=>$post['examnsn_gonispy_l_nsl_d1'], 'examnsn_gonispy_l_nsl_d2'=>$post['examnsn_gonispy_l_nsl_d2'], 'examnsn_gonispy_l_nsl_d3'=>$post['examnsn_gonispy_l_nsl_d3'], 'examnsn_gonispy_l_infr_d1'=>$post['examnsn_gonispy_l_infr_d1'], 'examnsn_gonispy_l_infr_d2'=>$post['examnsn_gonispy_l_infr_d2'], 'examnsn_gonispy_l_infr_d3'=>$post['examnsn_gonispy_l_infr_d3'], 'examnsn_gonispy_l_comm'=>$post['examnsn_gonispy_l_comm'], 'examnsn_gonispy_r_sup_d1'=>$post['examnsn_gonispy_r_sup_d1'], 'examnsn_gonispy_r_sup_d2'=>$post['examnsn_gonispy_r_sup_d2'], 'examnsn_gonispy_r_sup_d3'=>$post['examnsn_gonispy_r_sup_d3'], 'examnsn_gonispy_r_tmprl_d1'=>$post['examnsn_gonispy_r_tmprl_d1'], 'examnsn_gonispy_r_tmprl_d2'=>$post['examnsn_gonispy_r_tmprl_d2'], 'examnsn_gonispy_r_tmprl_d3'=>$post['examnsn_gonispy_r_tmprl_d3'], 'examnsn_gonispy_r_nsl_d1'=>$post['examnsn_gonispy_r_nsl_d1'], 'examnsn_gonispy_r_nsl_d2'=>$post['examnsn_gonispy_r_nsl_d2'], 'examnsn_gonispy_r_nsl_d3'=>$post['examnsn_gonispy_r_nsl_d3'], 'examnsn_gonispy_r_infr_d1'=>$post['examnsn_gonispy_r_infr_d1'], 'examnsn_gonispy_r_infr_d2'=>$post['examnsn_gonispy_r_infr_d2'], 'examnsn_gonispy_r_infr_d3'=>$post['examnsn_gonispy_r_infr_d3'], 'examnsn_gonispy_r_comm'=>$post['examnsn_gonispy_r_comm'],'examnsn_gonispy_l_update'=>$post['examnsn_gonispy_l_update'], 'examnsn_gonispy_r_update'=>$post['examnsn_gonispy_r_update']);

		 $fundus=array('toggle_fundus_nrml'=>$toggle_fundus_nrml, 'examnsn_fundus_l_dilate'=>$examnsn_fundus_l_dilate, 'examnsn_fundus_l_mda'=>$post['examnsn_fundus_l_mda'], 'examnsn_fundus_l_mda_comm'=>$post['examnsn_fundus_l_mda_comm'], 'examnsn_fundus_l_pvd'=>$post['examnsn_fundus_l_pvd'], 'examnsn_fundus_l_ods'=>$post['examnsn_fundus_l_ods'], 'examnsn_fundus_l_cdr'=>$post['examnsn_fundus_l_cdr'], 'examnsn_fundus_l_cdr_txt'=>$post['examnsn_fundus_l_cdr_txt'], 'examnsn_fundus_l_opdisc'=>$post['examnsn_fundus_l_opdisc'], 'examnsn_fundus_l_bldvls'=>$post['examnsn_fundus_l_bldvls'], 'examnsn_fundus_l_bldvls_txt'=>$post['examnsn_fundus_l_bldvls_txt'], 'examnsn_fundus_l_mcla'=>$post['examnsn_fundus_l_mcla'], 'examnsn_fundus_l_mcla_txt'=>$post['examnsn_fundus_l_mcla_txt'],'examnsn_fundus_l_vtrs'=>$post['examnsn_fundus_l_vtrs'], 'examnsn_fundus_l_vtrs_txt'=>$post['examnsn_fundus_l_vtrs_txt'], 'examnsn_fundus_l_rtnldcht'=>$post['examnsn_fundus_l_rtnldcht'], 'examnsn_fundus_l_rtnldcht_txt'=>$post['examnsn_fundus_l_rtnldcht_txt'], 'examnsn_fundus_l_perlsn'=>$post['examnsn_fundus_l_perlsn'], 'examnsn_fundus_l_perlsn_txt'=>$post['examnsn_fundus_l_perlsn_txt'], 'examnsn_fundus_l_fnds'=>$post['examnsn_fundus_l_fnds'],'examnsn_fundus_l_comm'=>$post['examnsn_fundus_l_comm'], 'toggle_fundus_ri8_nrml'=>$toggle_fundus_ri8_nrml, 'examnsn_fundus_r_dialet'=>$examnsn_fundus_r_dialet, 'examnsn_fundus_r_mda'=>$post['examnsn_fundus_r_mda'], 'examnsn_fundus_r_mda_comm'=>$post['examnsn_fundus_r_mda_comm'], 'examnsn_fundus_r_pvd'=>$post['examnsn_fundus_r_pvd'], 'examnsn_fundus_r_ods'=>$post['examnsn_fundus_r_ods'], 'examnsn_fundus_r_cdr'=>$post['examnsn_fundus_r_cdr'], 'examnsn_fundus_r_cdr_txt'=>$post['examnsn_fundus_r_cdr_txt'], 'examnsn_fundus_r_opdisc'=>$post['examnsn_fundus_r_opdisc'], 'examnsn_fundus_r_bldvls'=>$post['examnsn_fundus_r_bldvls'], 'examnsn_fundus_r_bldvls_txt'=>$post['examnsn_fundus_r_bldvls_txt'], 'examnsn_fundus_r_mcla'=>$post['examnsn_fundus_r_mcla'], 'examnsn_fundus_r_mcla_txt'=>$post['examnsn_fundus_r_mcla_txt'],'examnsn_fundus_r_vtrs'=>$post['examnsn_fundus_r_vtrs'], 'examnsn_fundus_r_vtrs_txt'=>$post['examnsn_fundus_r_vtrs_txt'], 'examnsn_fundus_r_rtnldcht'=>$post['examnsn_fundus_r_rtnldcht'], 'examnsn_fundus_r_rtnldcht_txt'=>$post['examnsn_fundus_r_rtnldcht_txt'], 'examnsn_fundus_r_perlsn'=>$post['examnsn_fundus_r_perlsn'], 'examnsn_fundus_r_perlsn_txt'=>$post['examnsn_fundus_r_perlsn_txt'], 'examnsn_fundus_r_fnds'=>$post['examnsn_fundus_r_fnds'],'examnsn_fundus_r_comm'=>$post['examnsn_fundus_r_comm'],'examnsn_fundus_l_update'=>$post['examnsn_fundus_l_update'], 'examnsn_fundus_r_update'=>$post['examnsn_fundus_r_update']);

		
		$data_exam = array('branch_id'=>$post['branch_id'], 'pres_id'=>$prescriptionid, 'booking_code'=>$post['booking_code'], 'patient_id'=>$post['patient_id'], 'booking_id'=>$post['booking_id'] , 'general_examina'=>json_encode($general_examina), 'appearance'=>json_encode($appearance), 'appendages'=>json_encode($appendages), 'conjuctiva'=>json_encode($conjuctiva), 'cornea'=>json_encode($cornea), 'aterior_chamber'=>json_encode($aterior_ch), 'pupil'=>json_encode($pupil), 'iris'=>json_encode($iris), 'lens'=>json_encode($lens), 'extraocular_move_squint'=>json_encode($extraocular_move_squint), 'intraocular_pressure'=>json_encode($intraocular_pressure), 'gonioscopy'=>json_encode($gonioscopy), 'fundus'=>json_encode($fundus), 'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$user_data['id'], 'created_date'=>date('Y-m-d H:i:s'));
	

	//biometry

		$data_biometry = array('branch_id'=>$post['branch_id'], 'pres_id'=>$prescriptionid, 'booking_code'=>$post['booking_code'], 'patient_id'=>$post['patient_id'], 'booking_id'=>$post['booking_id'],'biometry_ob_k1_one'=>$post['biometry_ob_k1_one'],'biometry_ob_k1_two'=>$post['biometry_ob_k1_two']
			,'biometry_ob_k1_three'=>$post['biometry_ob_k1_three']
			,'biometry_ob_k2_one'=>$post['biometry_ob_k2_one']
			,'biometry_ob_k2_two'=>$post['biometry_ob_k2_two']
			,'biometry_ob_k2_three'=>$post['biometry_ob_k2_three']
			,'biometry_ob_al_one'=>$post['biometry_ob_al_one']
			,'biometry_ob_al_two'=>$post['biometry_ob_al_two']
			,'biometry_ob_al_three'=>$post['biometry_ob_al_three']
			,'biometry_ascan_one'=>$post['biometry_ascan_one']
			,'biometry_ascan_two'=>$post['biometry_ascan_two']
			,'biometry_ascan_three'=>$post['biometry_ascan_three']

			,'biometry_ascan_sec_one'=>$post['biometry_ascan_sec_one']
			,'biometry_ascan_sec_two'=>$post['biometry_ascan_sec_two']
			,'biometry_ascan_sec_three'=>$post['biometry_ascan_sec_three']
			,'biometry_ascan_thr_one'=>$post['biometry_ascan_thr_one']
			,'biometry_ascan_thr_two'=>$post['biometry_ascan_thr_two']
			,'biometry_ascan_thr_three'=>$post['biometry_ascan_thr_three']
			,'biometry_iol_one'=>$post['biometry_iol_one']
			,'biometry_srk_one'=>$post['biometry_srk_one']
			,'biometry_error_one'=>$post['biometry_error_one']
			,'biometry_barett_one'=>$post['biometry_barett_one']


			,'biometry_error_one_two'=>$post['biometry_error_one_two']
			,'biometry_iol_two'=>$post['biometry_iol_two']
			,'biometry_ascan_sec_sec'=>$post['biometry_ascan_sec_sec']
			,'biometry_error_sec'=>$post['biometry_error_sec']
			,'biometry_barett_sec'=>$post['biometry_barett_sec']
			,'biometry_error_one_sec'=>$post['biometry_error_one_sec']
			,'biometry_iol_thr'=>$post['biometry_iol_thr']
			,'biometry_ascan_sec_thr'=>$post['biometry_ascan_sec_thr']
			,'biometry_error_thr'=>$post['biometry_error_thr']

			,'biometry_barett_thr'=>$post['biometry_barett_thr']
			,'biometry_error_one_thr'=>$post['biometry_error_one_thr']
			,'biometry_iol_four'=>$post['biometry_iol_four']
			,'biometry_ascan_sec_four'=>$post['biometry_ascan_sec_four']
			,'biometry_error_four'=>$post['biometry_error_four']
			,'biometry_barett_four'=>$post['biometry_barett_four']
			,'biometry_error_one_four'=>$post['biometry_error_one_four']
			,'biometry_iol_five'=>$post['biometry_iol_five']
			,'biometry_ascan_sec_five'=>$post['biometry_ascan_sec_five']

			,'biometry_error_five'=>$post['biometry_error_five']
			,'biometry_barett_five'=>$post['biometry_barett_five']
			,'biometry_error_one_five'=>$post['biometry_error_one_five']
			,'biometry_remarks'=>$post['biometry_remarks'],'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$user_data['id'], 'created_date'=>date('Y-m-d H:i:s'));

	// refraction

	$refraction_va_ua_l= isset($post['refraction_va_ua_l']) ? $post['refraction_va_ua_l'] :'';
    $refraction_va_ua_l_p= isset($post['refraction_va_ua_l_p']) ? $post['refraction_va_ua_l_p'] :'';
    $refraction_va_ua_r= isset($post['refraction_va_ua_r']) ? $post['refraction_va_ua_r'] :'0';
    $refraction_va_ua_r_p= isset($post['refraction_va_ua_r_p']) ? $post['refraction_va_ua_r_p'] :'';
    $refraction_va_ua_l_2= isset($post['refraction_va_ua_l_2']) ? $post['refraction_va_ua_l_2'] :'';
    $refraction_va_ua_l_p_2= isset($post['refraction_va_ua_l_p_2']) ? $post['refraction_va_ua_l_p_2'] : '';
    $refraction_va_ua_r_2= isset($post['refraction_va_ua_r_2']) ? $post['refraction_va_ua_r_2'] : '';
    $refraction_va_ua_r_p_2= isset($post['refraction_va_ua_r_p_2']) ? $post['refraction_va_ua_r_p_2'] : '';
    $refraction_va_ph_l= isset($post['refraction_va_ph_l']) ? $post['refraction_va_ph_l'] : '';
    $refraction_va_ph_l_p= isset($post['refraction_va_ph_l_p']) ? $post['refraction_va_ph_l_p'] : '';
    $refraction_va_ph_l_ni= isset($post['refraction_va_ph_l_ni']) ? $post['refraction_va_ph_l_ni'] : '';
    $refraction_va_ph_r= isset($post['refraction_va_ph_r']) ? $post['refraction_va_ph_r'] : '';
    $refraction_va_ph_r_p= isset($post['refraction_va_ph_r_p']) ? $post['refraction_va_ph_r_p'] : '';
    $refraction_va_ph_r_ni= isset($post['refraction_va_ph_r_ni']) ? $post['refraction_va_ph_r_ni'] : '';
    $refraction_va_gls_l= isset($post['refraction_va_gls_l']) ? $post['refraction_va_gls_l'] : '';
    $refraction_va_gls_l_p= isset($post['refraction_va_gls_l_p']) ? $post['refraction_va_gls_l_p'] : '';
    $refraction_va_gls_r= isset($post['refraction_va_gls_r']) ? $post['refraction_va_gls_r'] : '';
    $refraction_va_gls_r_p= isset($post['refraction_va_gls_r_p']) ? $post['refraction_va_gls_r_p'] : '';
    $refraction_va_gls_l_2= isset($post['refraction_va_gls_l_2']) ? $post['refraction_va_gls_l_2'] : '';
    $refraction_va_gls_l_p_2= isset($post['refraction_va_gls_l_p_2']) ? $post['refraction_va_gls_l_p_2'] : '';
    $refraction_va_gls_r_2= isset($post['refraction_va_gls_r_2']) ? $post['refraction_va_gls_r_2'] : '';
    $refraction_va_gls_r_p_2= isset($post['refraction_va_gls_r_p_2']) ? $post['refraction_va_gls_r_p_2'] : '';
    $refraction_va_cl_l= isset($post['refraction_va_cl_l']) ? $post['refraction_va_cl_l'] : '';
    $refraction_va_cl_l_p= isset($post['refraction_va_cl_l_p']) ? $post['refraction_va_cl_l_p'] : '';
    $refraction_va_cl_r= isset($post['refraction_va_cl_r']) ? $post['refraction_va_cl_r'] : '';

    $refraction_va_cl_r_p= isset($post['refraction_va_cl_r_p']) ? $post['refraction_va_cl_r_p'] : '';
   	$refraction_contra_sens_l= isset($post['refraction_contra_sens_l']) ? $post['refraction_contra_sens_l'] : '';
    $refraction_contra_sens_r= isset($post['refraction_contra_sens_r']) ? $post['refraction_contra_sens_r'] : '';
    $refraction_gps_ad_check = isset($post['refraction_gps_ad_check']) ? '1' : '';
    $refraction_gps_ad_show_print = isset($post['refraction_gps_ad_show_print']) ? '1' : '';
    $refraction_itgp_ad_check = isset($post['refraction_itgp_ad_check']) ? '1' : '0';
    $refraction_itgp_ad_show_print = isset($post['refraction_itgp_ad_show_print']) ? '1' : '';
			$visal_acuity=array(
				'refraction_va_ua_l'=>$refraction_va_ua_l,
				'refraction_va_ua_l_txt'=>$post['refraction_va_ua_l_txt'],
				'refraction_va_ua_l_p'=>$refraction_va_ua_l_p,
				'refraction_va_ua_r'=>$refraction_va_ua_r,
				'refraction_va_ua_r_txt'=>$post['refraction_va_ua_r_txt'],
				'refraction_va_ua_r_p'=>$refraction_va_ua_r_p,
				'refraction_va_ua_l_2'=>$refraction_va_ua_l_2,
				'refraction_va_ua_l_2_txt'=>$post['refraction_va_ua_l_2_txt'],
				'refraction_va_ua_l_p_2'=>$refraction_va_ua_l_p_2,
				'refraction_va_ua_r_2'=>$refraction_va_ua_r_2,
				'refraction_va_ua_r_2_txt'=>$post['refraction_va_ua_r_2_txt'],
				'refraction_va_ua_r_p_2'=>$refraction_va_ua_r_p_2,

				'refraction_va_ua_l_pr_s'=>$post['refraction_va_ua_l_pr_s'],
				'refraction_va_ua_l_pr_i'=>$post['refraction_va_ua_l_pr_i'],
				'refraction_va_ua_l_pr_n'=>$post['refraction_va_ua_l_pr_n'],
				'refraction_va_ua_l_pr_t'=>$post['refraction_va_ua_l_pr_t'],
				'refraction_va_ua_r_pr_s'=>$post['refraction_va_ua_r_pr_s'],
				'refraction_va_ua_r_pr_i'=>$post['refraction_va_ua_r_pr_i'],
				'refraction_va_ua_r_pr_n'=>$post['refraction_va_ua_r_pr_n'],
				'refraction_va_ua_r_pr_t'=>$post['refraction_va_ua_r_pr_t'],

				'refraction_va_ph_l'=>$refraction_va_ph_l,
				'refraction_va_ph_l_txt'=>$post['refraction_va_ph_l_txt'],
				'refraction_va_ph_l_p'=>$refraction_va_ph_l_p,
				'refraction_va_ph_l_ni'=>$refraction_va_ph_l_ni,
				'refraction_va_ph_r'=>$refraction_va_ph_r,
				'refraction_va_ph_r_txt'=>$post['refraction_va_ph_r_txt'],
				'refraction_va_ph_r_p'=>$refraction_va_ph_r_p,
				'refraction_va_ph_r_ni'=>$refraction_va_ph_r_ni,

				'refraction_va_gls_l'=>$refraction_va_gls_l,
				'refraction_va_gls_l_txt'=>$post['refraction_va_gls_l_txt'],
				'refraction_va_gls_l_p'=>$refraction_va_gls_l_p,
				'refraction_va_gls_r'=>$refraction_va_gls_r,
				'refraction_va_gls_r_txt'=>$post['refraction_va_gls_r_txt'],
				'refraction_va_gls_r_p'=>$refraction_va_gls_r_p,
				'refraction_va_gls_l_2'=>$refraction_va_gls_l_2,
				'refraction_va_gls_l_2_txt'=>$post['refraction_va_gls_l_2_txt'],
				'refraction_va_gls_l_p_2'=>$refraction_va_gls_l_p_2,
				'refraction_va_gls_r_2'=>$refraction_va_gls_r_2,
				'refraction_va_gls_r_2_txt'=>$post['refraction_va_gls_r_2_txt'],
				'refraction_va_gls_r_p_2'=>$refraction_va_gls_r_p_2,

				'refraction_va_cl_l'=>$refraction_va_cl_l,
				'refraction_va_cl_l_txt'=>$post['refraction_va_cl_l_txt'],
				'refraction_va_cl_l_p'=>$refraction_va_cl_l_p,
				'refraction_va_cl_r'=>$refraction_va_cl_r,
				'refraction_va_cl_r_txt'=>$post['refraction_va_cl_r_txt'],
				'refraction_va_cl_r_p'=>$refraction_va_cl_r_p,
				'refraction_va_l_comm'=>$post['refraction_va_l_comm'],
				'refraction_va_r_comm'=>$post['refraction_va_r_comm']
			);

			$keratometry=array('refraction_km_l_kh'=>$post['refraction_km_l_kh'],
				'refraction_km_l_kh_a'=>$post['refraction_km_l_kh_a'],'refraction_km_r_kh'=>$post['refraction_km_r_kh'],'refraction_km_r_kh_a'=>$post['refraction_km_r_kh_a'],'refraction_km_l_kv'=>$post['refraction_km_l_kv'],
				'refraction_km_l_kv_a'=>$post['refraction_km_l_kv_a'],'refraction_km_r_kv'=>$post['refraction_km_r_kv'],'refraction_km_r_kv_a'=>$post['refraction_km_r_kv_a']);

			$pgp=array('refraction_pgp_l_dt_sph'=>$post['refraction_pgp_l_dt_sph'],'refraction_pgp_l_dt_cyl'=>$post['refraction_pgp_l_dt_cyl'],'refraction_pgp_l_dt_axis'=>$post['refraction_pgp_l_dt_axis'],'refraction_pgp_l_dt_vision'=>$post['refraction_pgp_l_dt_vision'],
			'refraction_pgp_l_ad_sph'=>$post['refraction_pgp_l_ad_sph'],'refraction_pgp_l_ad_cyl'=>$post['refraction_pgp_l_ad_cyl'],'refraction_pgp_l_ad_axis'=>$post['refraction_pgp_l_ad_axis'],'refraction_pgp_l_ad_vision'=>$post['refraction_pgp_l_ad_vision'],
			'refraction_pgp_l_nr_sph'=>$post['refraction_pgp_l_nr_sph'],'refraction_pgp_l_nr_cyl'=>$post['refraction_pgp_l_nr_cyl'],'refraction_pgp_l_nr_axis'=>$post['refraction_pgp_l_nr_axis'],'refraction_pgp_l_nr_vision'=>$post['refraction_pgp_l_nr_vision'],'refraction_pgp_l_lens'=>$post['refraction_pgp_l_lens'],

			'refraction_pgp_r_dt_sph'=>$post['refraction_pgp_r_dt_sph'],'refraction_pgp_r_dt_cyl'=>$post['refraction_pgp_r_dt_cyl'],'refraction_pgp_r_dt_axis'=>$post['refraction_pgp_r_dt_axis'],'refraction_pgp_r_dt_vision'=>$post['refraction_pgp_r_dt_vision'],
			'refraction_pgp_r_ad_sph'=>$post['refraction_pgp_r_ad_sph'],'refraction_pgp_r_ad_cyl'=>$post['refraction_pgp_r_ad_cyl'],'refraction_pgp_r_ad_axis'=>$post['refraction_pgp_r_ad_axis'],'refraction_pgp_r_ad_vision'=>$post['refraction_pgp_r_ad_vision'],
			'refraction_pgp_r_nr_sph'=>$post['refraction_pgp_r_nr_sph'],'refraction_pgp_r_nr_cyl'=>$post['refraction_pgp_r_nr_cyl'],'refraction_pgp_r_nr_axis'=>$post['refraction_pgp_r_nr_axis'],'refraction_pgp_r_nr_vision'=>$post['refraction_pgp_r_nr_vision'],'refraction_pgp_r_lens'=>$post['refraction_pgp_r_lens']);

			$auto_ref=array('refraction_ar_l_dry_sph'=>$post['refraction_ar_l_dry_sph'],'refraction_ar_l_dry_cyl'=>$post['refraction_ar_l_dry_cyl'],'refraction_ar_l_dry_axis'=>$post['refraction_ar_l_dry_axis'],
			'refraction_ar_l_dd_sph'=>$post['refraction_ar_l_dd_sph'],'refraction_ar_l_dd_cyl'=>$post['refraction_ar_l_dd_cyl'],'refraction_ar_l_dd_axis'=>$post['refraction_ar_l_dd_axis'],
			'refraction_ar_l_b1_sph'=>$post['refraction_ar_l_b1_sph'],'refraction_ar_l_b1_cyl'=>$post['refraction_ar_l_b1_cyl'],'refraction_ar_l_b1_axis'=>$post['refraction_ar_l_b1_axis'],
			'refraction_ar_l_b2_sph'=>$post['refraction_ar_l_b2_sph'],'refraction_ar_l_b2_cyl'=>$post['refraction_ar_l_b2_cyl'],'refraction_ar_l_b2_axis'=>$post['refraction_ar_l_b2_axis'],
			'refraction_ar_r_dry_sph'=>$post['refraction_ar_r_dry_sph'],'refraction_ar_r_dry_cyl'=>$post['refraction_ar_r_dry_cyl'],'refraction_ar_r_dry_axis'=>$post['refraction_ar_r_dry_axis'],
			'refraction_ar_r_dd_sph'=>$post['refraction_ar_r_dd_sph'],'refraction_ar_r_dd_cyl'=>$post['refraction_ar_r_dd_cyl'],'refraction_ar_r_dd_axis'=>$post['refraction_ar_r_dd_axis'],
			'refraction_ar_r_b1_sph'=>$post['refraction_ar_r_b1_sph'],'refraction_ar_r_b1_cyl'=>$post['refraction_ar_r_b1_cyl'],'refraction_ar_r_b1_axis'=>$post['refraction_ar_r_b1_axis'],
			'refraction_ar_r_b2_sph'=>$post['refraction_ar_r_b2_sph'],'refraction_ar_r_b2_cyl'=>$post['refraction_ar_r_b2_cyl'],'refraction_ar_r_b2_axis'=>$post['refraction_ar_r_b2_axis']);

			$dry_ref=array('refraction_dry_ref_l_dt_sph'=>$post['refraction_dry_ref_l_dt_sph'],'refraction_dry_ref_l_dt_cyl'=>$post['refraction_dry_ref_l_dt_cyl'],'refraction_dry_ref_l_dt_axis'=>$post['refraction_dry_ref_l_dt_axis'],'refraction_dry_ref_l_dt_vision'=>$post['refraction_dry_ref_l_dt_vision'],
			'refraction_dry_ref_l_ad_sph'=>$post['refraction_dry_ref_l_ad_sph'],'refraction_dry_ref_l_ad_cyl'=>$post['refraction_dry_ref_l_ad_cyl'],'refraction_dry_ref_l_ad_axis'=>$post['refraction_dry_ref_l_ad_axis'],'refraction_dry_ref_l_ad_vision'=>$post['refraction_dry_ref_l_ad_vision'],
			'refraction_dry_ref_l_nr_sph'=>$post['refraction_dry_ref_l_nr_sph'],'refraction_dry_ref_l_nr_cyl'=>$post['refraction_dry_ref_l_nr_cyl'],'refraction_dry_ref_l_nr_axis'=>$post['refraction_dry_ref_l_nr_axis'],'refraction_dry_ref_l_nr_vision'=>$post['refraction_dry_ref_l_nr_vision'],'refraction_dry_ref_l_comm'=>$post['refraction_dry_ref_l_comm'],

			'refraction_dry_ref_r_dt_sph'=>$post['refraction_dry_ref_r_dt_sph'],'refraction_dry_ref_r_dt_cyl'=>$post['refraction_dry_ref_r_dt_cyl'],'refraction_dry_ref_r_dt_axis'=>$post['refraction_dry_ref_r_dt_axis'],'refraction_dry_ref_r_dt_vision'=>$post['refraction_dry_ref_r_dt_vision'],
			'refraction_dry_ref_r_ad_sph'=>$post['refraction_dry_ref_r_ad_sph'],'refraction_dry_ref_r_ad_cyl'=>$post['refraction_dry_ref_r_ad_cyl'],'refraction_dry_ref_r_ad_axis'=>$post['refraction_dry_ref_r_ad_axis'],'refraction_dry_ref_r_ad_vision'=>$post['refraction_dry_ref_r_ad_vision'],
			'refraction_dry_ref_r_nr_sph'=>$post['refraction_dry_ref_r_nr_sph'],'refraction_dry_ref_r_nr_cyl'=>$post['refraction_dry_ref_r_nr_cyl'],'refraction_dry_ref_r_nr_axis'=>$post['refraction_dry_ref_r_nr_axis'],'refraction_dry_ref_r_nr_vision'=>$post['refraction_dry_ref_r_nr_vision'],'refraction_dry_ref_r_comm'=>$post['refraction_dry_ref_r_comm']);

			$ref_dtd=array('refraction_ref_dtd_l_dt_sph'=>$post['refraction_ref_dtd_l_dt_sph'],'refraction_ref_dtd_l_dt_cyl'=>$post['refraction_ref_dtd_l_dt_cyl'],'refraction_ref_dtd_l_dt_axis'=>$post['refraction_ref_dtd_l_dt_axis'],'refraction_ref_dtd_l_dt_vision'=>$post['refraction_ref_dtd_l_dt_vision'],
			'refraction_ref_dtd_l_ad_sph'=>$post['refraction_ref_dtd_l_ad_sph'],'refraction_ref_dtd_l_ad_cyl'=>$post['refraction_ref_dtd_l_ad_cyl'],'refraction_ref_dtd_l_ad_axis'=>$post['refraction_ref_dtd_l_ad_axis'],'refraction_ref_dtd_l_ad_vision'=>$post['refraction_ref_dtd_l_ad_vision'],
			'refraction_ref_dtd_l_nr_sph'=>$post['refraction_ref_dtd_l_nr_sph'],'refraction_ref_dtd_l_nr_cyl'=>$post['refraction_ref_dtd_l_nr_cyl'],'refraction_ref_dtd_l_nr_axis'=>$post['refraction_ref_dtd_l_nr_axis'],'refraction_ref_dtd_l_nr_vision'=>$post['refraction_ref_dtd_l_nr_vision'],'refraction_ref_dtd_l_du'=>$post['refraction_ref_dtd_l_du'],'refraction_ref_dtd_l_comm'=>$post['refraction_ref_dtd_l_comm'],

			'refraction_ref_dtd_r_dt_sph'=>$post['refraction_ref_dtd_r_dt_sph'],'refraction_ref_dtd_r_dt_cyl'=>$post['refraction_ref_dtd_r_dt_cyl'],'refraction_ref_dtd_r_dt_axis'=>$post['refraction_ref_dtd_r_dt_axis'],'refraction_ref_dtd_r_dt_vision'=>$post['refraction_ref_dtd_r_dt_vision'],
			'refraction_ref_dtd_r_ad_sph'=>$post['refraction_ref_dtd_r_ad_sph'],'refraction_ref_dtd_r_ad_cyl'=>$post['refraction_ref_dtd_r_ad_cyl'],'refraction_ref_dtd_r_ad_axis'=>$post['refraction_ref_dtd_r_ad_axis'],'refraction_ref_dtd_r_ad_vision'=>$post['refraction_ref_dtd_r_ad_vision'],
			'refraction_ref_dtd_r_nr_sph'=>$post['refraction_ref_dtd_r_nr_sph'],'refraction_ref_dtd_r_nr_cyl'=>$post['refraction_ref_dtd_r_nr_cyl'],'refraction_ref_dtd_r_nr_axis'=>$post['refraction_ref_dtd_r_nr_axis'],'refraction_ref_dtd_r_nr_vision'=>$post['refraction_ref_dtd_r_nr_vision'],'refraction_ref_dtd_r_du'=>$post['refraction_ref_dtd_r_du'],'refraction_ref_dtd_r_comm'=>$post['refraction_ref_dtd_r_comm']);

			$retinoscopy=array('refraction_rtnp_l_t'=>$post['refraction_rtnp_l_t'],'refraction_rtnp_l_l'=>$post['refraction_rtnp_l_l'],'refraction_rtnp_l_r'=>$post['refraction_rtnp_l_r'],'refraction_rtnp_l_b'=>$post['refraction_rtnp_l_b'],'refraction_rtnp_l_va'=>$post['refraction_rtnp_l_va'],'refraction_rtnp_l_ha'=>$post['refraction_rtnp_l_ha'],'refraction_rtnp_l_at_dis'=>$post['refraction_rtnp_l_at_dis'],'refraction_rtnp_l_du'=>$post['refraction_rtnp_l_du'],'refraction_rtnp_l_comm'=>$post['refraction_rtnp_l_comm'],'refraction_rtnp_r_t'=>$post['refraction_rtnp_r_t'],'refraction_rtnp_r_l'=>$post['refraction_rtnp_r_l'],'refraction_rtnp_r_r'=>$post['refraction_rtnp_r_r'],'refraction_rtnp_r_b'=>$post['refraction_rtnp_r_b'],'refraction_rtnp_r_va'=>$post['refraction_rtnp_r_va'],'refraction_rtnp_r_ha'=>$post['refraction_rtnp_r_ha'],'refraction_rtnp_r_at_dis'=>$post['refraction_rtnp_r_at_dis'],'refraction_rtnp_r_du'=>$post['refraction_rtnp_r_du'],'refraction_rtnp_r_comm'=>$post['refraction_rtnp_r_comm']);

			$pmt=array('refraction_pmt_l_dt_sph'=>$post['refraction_pmt_l_dt_sph'],'refraction_pmt_l_dt_cyl'=>$post['refraction_pmt_l_dt_cyl'],'refraction_pmt_l_dt_axis'=>$post['refraction_pmt_l_dt_axis'],'refraction_pmt_l_dt_vision'=>$post['refraction_pmt_l_dt_vision'],
			'refraction_pmt_l_ad_sph'=>$post['refraction_pmt_l_ad_sph'],'refraction_pmt_l_ad_cyl'=>$post['refraction_pmt_l_ad_cyl'],'refraction_pmt_l_ad_axis'=>$post['refraction_pmt_l_ad_axis'],'refraction_pmt_l_ad_vision'=>$post['refraction_pmt_l_ad_vision'],
			'refraction_pmt_l_nr_sph'=>$post['refraction_pmt_l_nr_sph'],'refraction_pmt_l_nr_cyl'=>$post['refraction_pmt_l_nr_cyl'],'refraction_pmt_l_nr_axis'=>$post['refraction_pmt_l_nr_axis'],'refraction_pmt_l_nr_vision'=>$post['refraction_pmt_l_nr_vision'],

			'refraction_pmt_r_dt_sph'=>$post['refraction_pmt_r_dt_sph'],'refraction_pmt_r_dt_cyl'=>$post['refraction_pmt_r_dt_cyl'],'refraction_pmt_r_dt_axis'=>$post['refraction_pmt_r_dt_axis'],'refraction_pmt_r_dt_vision'=>$post['refraction_pmt_r_dt_vision'],
			'refraction_pmt_r_ad_sph'=>$post['refraction_pmt_r_ad_sph'],'refraction_pmt_r_ad_cyl'=>$post['refraction_pmt_r_ad_cyl'],'refraction_pmt_r_ad_axis'=>$post['refraction_pmt_r_ad_axis'],'refraction_pmt_r_ad_vision'=>$post['refraction_pmt_r_ad_vision'],
			'refraction_pmt_r_nr_sph'=>$post['refraction_pmt_r_nr_sph'],'refraction_pmt_r_nr_cyl'=>$post['refraction_pmt_r_nr_cyl'],'refraction_pmt_r_nr_axis'=>$post['refraction_pmt_r_nr_axis'],'refraction_pmt_r_nr_vision'=>$post['refraction_pmt_r_nr_vision']);

			$glass_pres=array('refraction_gps_l_dt_sph'=>$post['refraction_gps_l_dt_sph'],'refraction_gps_l_dt_cyl'=>$post['refraction_gps_l_dt_cyl'],'refraction_gps_l_dt_axis'=>$post['refraction_gps_l_dt_axis'],'refraction_gps_l_dt_vision'=>$post['refraction_gps_l_dt_vision'],
			'refraction_gps_l_ad_sph'=>$post['refraction_gps_l_ad_sph'],'refraction_gps_l_ad_cyl'=>$post['refraction_gps_l_ad_cyl'],'refraction_gps_l_ad_axis'=>$post['refraction_gps_l_ad_axis'],'refraction_gps_l_ad_vision'=>$post['refraction_gps_l_ad_vision'],
			'refraction_gps_l_nr_sph'=>$post['refraction_gps_l_nr_sph'],'refraction_gps_l_nr_cyl'=>$post['refraction_gps_l_nr_cyl'],'refraction_gps_l_nr_axis'=>$post['refraction_gps_l_nr_axis'],'refraction_gps_l_nr_vision'=>$post['refraction_gps_l_nr_vision'],'refraction_gps_l_tol'=>$post['refraction_gps_l_tol'],'refraction_gps_l_ipd'=>$post['refraction_gps_l_ipd'],'refraction_gps_l_lns_mat'=>$post['refraction_gps_l_lns_mat'],'refraction_gps_l_lns_tnt'=>$post['refraction_gps_l_lns_tnt'],'refraction_gps_l_fm'=>$post['refraction_gps_l_fm'],'refraction_gps_l_advs'=>$post['refraction_gps_l_advs'],

			'refraction_gps_r_dt_sph'=>$post['refraction_gps_r_dt_sph'],'refraction_gps_r_dt_cyl'=>$post['refraction_gps_r_dt_cyl'],'refraction_gps_r_dt_axis'=>$post['refraction_gps_r_dt_axis'],'refraction_gps_r_dt_vision'=>$post['refraction_gps_r_dt_vision'],
			'refraction_gps_r_ad_sph'=>$post['refraction_gps_r_ad_sph'],'refraction_gps_r_ad_cyl'=>$post['refraction_gps_r_ad_cyl'],'refraction_gps_r_ad_axis'=>$post['refraction_gps_r_ad_axis'],'refraction_gps_r_ad_vision'=>$post['refraction_gps_r_ad_vision'],
			'refraction_gps_r_nr_sph'=>$post['refraction_gps_r_nr_sph'],'refraction_gps_r_nr_cyl'=>$post['refraction_gps_r_nr_cyl'],'refraction_gps_r_nr_axis'=>$post['refraction_gps_r_nr_axis'],'refraction_gps_r_nr_vision'=>$post['refraction_gps_r_nr_vision'],'refraction_gps_r_tol'=>$post['refraction_gps_r_tol'],'refraction_gps_r_ipd'=>$post['refraction_gps_r_ipd'],'refraction_gps_r_lns_mat'=>$post['refraction_gps_r_lns_mat'],'refraction_gps_r_lns_tnt'=>$post['refraction_gps_r_lns_tnt'],'refraction_gps_r_fm'=>$post['refraction_gps_r_fm'],'refraction_gps_r_advs'=>$post['refraction_gps_r_advs'],
			'refraction_gps_ad_check'=>$refraction_gps_ad_check,'refraction_gps_ad_show_print'=>$refraction_gps_ad_show_print);

			$inter_gls_pres=array('refraction_itgp_l_dt_sph'=>$post['refraction_itgp_l_dt_sph'],'refraction_itgp_l_dt_cyl'=>$post['refraction_itgp_l_dt_cyl'],'refraction_itgp_l_dt_axis'=>$post['refraction_itgp_l_dt_axis'],'refraction_itgp_l_dt_vision'=>$post['refraction_itgp_l_dt_vision'],
			'refraction_itgp_l_ad_sph'=>$post['refraction_itgp_l_ad_sph'],'refraction_itgp_l_ad_cyl'=>$post['refraction_itgp_l_ad_cyl'],'refraction_itgp_l_ad_axis'=>$post['refraction_itgp_l_ad_axis'],'refraction_itgp_l_ad_vision'=>$post['refraction_itgp_l_ad_vision'],
			'refraction_itgp_l_nr_sph'=>$post['refraction_itgp_l_nr_sph'],'refraction_itgp_l_nr_cyl'=>$post['refraction_itgp_l_nr_cyl'],'refraction_itgp_l_nr_axis'=>$post['refraction_itgp_l_nr_axis'],'refraction_itgp_l_nr_vision'=>$post['refraction_itgp_l_nr_vision'],'refraction_itgp_l_tol'=>$post['refraction_itgp_l_tol'],'refraction_itgp_l_ipd'=>$post['refraction_itgp_l_ipd'],'refraction_itgp_l_lns_mat'=>$post['refraction_itgp_l_lns_mat'],'refraction_itgp_l_lns_tnt'=>$post['refraction_itgp_l_lns_tnt'],'refraction_itgp_l_fm'=>$post['refraction_itgp_l_fm'],'refraction_itgp_l_advs'=>$post['refraction_itgp_l_advs'],

			'refraction_itgp_r_dt_sph'=>$post['refraction_itgp_r_dt_sph'],'refraction_itgp_r_dt_cyl'=>$post['refraction_itgp_r_dt_cyl'],'refraction_itgp_r_dt_axis'=>$post['refraction_itgp_r_dt_axis'],'refraction_itgp_r_dt_vision'=>$post['refraction_itgp_r_dt_vision'],
			'refraction_itgp_r_ad_sph'=>$post['refraction_itgp_r_ad_sph'],'refraction_itgp_r_ad_cyl'=>$post['refraction_itgp_r_ad_cyl'],'refraction_itgp_r_ad_axis'=>$post['refraction_itgp_r_ad_axis'],'refraction_itgp_r_ad_vision'=>$post['refraction_itgp_r_ad_vision'],
			'refraction_itgp_r_nr_sph'=>$post['refraction_itgp_r_nr_sph'],'refraction_itgp_r_nr_cyl'=>$post['refraction_itgp_r_nr_cyl'],'refraction_itgp_r_nr_axis'=>$post['refraction_itgp_r_nr_axis'],'refraction_itgp_r_nr_vision'=>$post['refraction_itgp_r_nr_vision'],'refraction_itgp_r_tol'=>$post['refraction_itgp_r_tol'],'refraction_itgp_r_ipd'=>$post['refraction_itgp_r_ipd'],'refraction_itgp_r_lns_mat'=>$post['refraction_itgp_r_lns_mat'],'refraction_itgp_r_lns_tnt'=>$post['refraction_itgp_r_lns_tnt'],'refraction_itgp_r_fm'=>$post['refraction_itgp_r_fm'],'refraction_itgp_r_advs'=>$post['refraction_itgp_r_advs'],
			'refraction_itgp_ad_check'=>$refraction_itgp_ad_check,'refraction_itgp_ad_show_print'=>$refraction_itgp_ad_show_print);
			
			$cont_lens_pres= array('refraction_clp_l_bc'=>$post['refraction_clp_l_bc'],'refraction_clp_l_dia'=>$post['refraction_clp_l_dia'],'refraction_clp_l_sph'=>$post['refraction_clp_l_sph'],'refraction_clp_l_cyl'=>$post['refraction_clp_l_cyl'],'refraction_clp_l_axis'=>$post['refraction_clp_l_axis'],'refraction_clp_l_add'=>$post['refraction_clp_l_add'],'refraction_clp_l_rv_date'=>$post['refraction_clp_l_rv_date'],'refraction_clp_l_clr'=>$post['refraction_clp_l_clr'],'refraction_clp_l_tp'=>$post['refraction_clp_l_tp'],'refraction_clp_l_advice'=>$post['refraction_clp_l_advice'],	'refraction_clp_r_bc'=>$post['refraction_clp_r_bc'],'refraction_clp_r_dia'=>$post['refraction_clp_r_dia'],'refraction_clp_r_sph'=>$post['refraction_clp_r_sph'],'refraction_clp_r_cyl'=>$post['refraction_clp_r_cyl'],'refraction_clp_r_axis'=>$post['refraction_clp_r_axis'],'refraction_clp_r_add'=>$post['refraction_clp_r_add'],'refraction_clp_r_rv_date'=>$post['refraction_clp_r_rv_date'],'refraction_clp_r_clr'=>$post['refraction_clp_r_clr'],'refraction_clp_r_tp'=>$post['refraction_clp_r_tp'],'refraction_clp_r_advice'=>$post['refraction_clp_r_advice']);


			$color_vision= array('refraction_col_vis_l'=>$post['refraction_col_vis_l'],
			'refraction_col_vis_r'=>$post['refraction_col_vis_r']);

			$conta_sens= array('refraction_contra_sens_l'=>$refraction_contra_sens_l,
			'refraction_contra_sens_r'=>$refraction_contra_sens_r);
			$intra_press= array('refraction_intra_press_l_mg'=>$post['refraction_intra_press_l_mg'],'refraction_intra_press_l_time'=>$post['refraction_intra_press_l_time'],'refraction_intra_press_l_comm'=>$post['refraction_intra_press_l_comm'],
			'refraction_intra_press_r_mg'=>$post['refraction_intra_press_r_mg'],'refraction_intra_press_r_time'=>$post['refraction_intra_press_r_time'],'refraction_intra_press_r_comm'=>$post['refraction_intra_press_r_comm'],'refraction_intra_l_update'=>$post['refraction_intra_press_l_update'],'refraction_intra_r_update'=>$post['refraction_intra_press_r_update']);

			$orthoptics= array('refraction_ortho_l'=>$post['refraction_ortho_l'],
			'refraction_ortho_r'=>$post['refraction_ortho_r']);

			$ref_data = array('branch_id'=>$post['branch_id'],'pres_id'=>$prescriptionid, 'booking_code'=>$post['booking_code'], 'patient_id'=>$post['patient_id'], 'booking_id'=>$post['booking_id'] , 'visual_acuity'=>json_encode($visal_acuity), 'keratometry'=>json_encode($keratometry), 'pgp'=>json_encode($pgp), 'auto_refraction'=>json_encode($auto_ref), 'dry_refraction'=>json_encode($dry_ref), 'refraction_delated'=>json_encode($ref_dtd), 'retinoscopy'=>json_encode($retinoscopy), 'pmt'=>json_encode($pmt), 'glass_prescription'=>json_encode($glass_pres), 'inter_glass_presc'=>json_encode($inter_gls_pres), 'contact_lens_presc'=>json_encode($cont_lens_pres), 'color_vision'=>json_encode($color_vision), 'contrast_sensivity'=>json_encode($conta_sens), 'intraocular_press'=>json_encode($intra_press), 'orthoptics'=>json_encode($orthoptics), 'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$user_data['id'], 'created_date'=>date('Y-m-d H:i:s'));

		// history tabs
		   	$visit_comm=$post['visit_comm'];
	   		$family_history=$post['family_history'];
	   		$medical_history=$post['medical_history'];

	   		if(isset($post['general_checkup']))
	   			$general_checkup=$post['general_checkup'];
	   		else
	   			$general_checkup=0;

	   		if(isset($post['special_status']))
	   			$special_status=$post['special_status'];
	   		else
	   			$special_status=0;

	   		if(isset($post['bdv_m']))
	   			$bdv_m=$post['bdv_m'];
	   		else
	   			$bdv_m=0;
		   		if(isset($post['history_chief_blurr_dist']))
		   			$history_chief_blurr_dist=$post['history_chief_blurr_dist'];
		   		else
		   			$history_chief_blurr_dist=0;
		   		if(isset($post['history_chief_blurr_near']))
		   			$history_chief_blurr_near=$post['history_chief_blurr_near'];
		   		else
		   			$history_chief_blurr_near=0;
		   		if(isset($post['history_chief_blurr_pain']))
		   			$history_chief_blurr_pain=$post['history_chief_blurr_pain'];
		   		else
		   			$history_chief_blurr_pain=0;
		   		if(isset($post['history_chief_blurr_ug']))
		   			$history_chief_blurr_ug=$post['history_chief_blurr_ug'];
		   		else
		   			$history_chief_blurr_ug=0;
	   		
	   		if(isset($post['pain_m']))
	   			$pain_m=$post['pain_m'];
	   		else
	   			$pain_m=0;
	   		if(isset($post['redness_m']))
	   			$redness_m=$post['redness_m'];
	   		else
	   			$redness_m=0;
	   		if(isset($post['injury_m']))
	   			$injury_m=$post['injury_m'];
	   		else
	   			$injury_m=0;
	   		if(isset($post['water_m']))
	   			$water_m=$post['water_m'];
	   		else
	   			$water_m=0;
	   		if(isset($post['discharge_m']))
	   			$discharge_m=$post['discharge_m'];
	   		else
	   			$discharge_m=0;
	   		if(isset($post['dryness_m']))
	   			$dryness_m=$post['dryness_m'];
	   		else
	   			$dryness_m=0;
	   		if(isset($post['itch_m']))
	   			$itch_m=$post['itch_m'];
	   		else
	   			$itch_m=0;

	   		if(isset($post['fbd_m']))
	   			$fbd_m=$post['fbd_m'];
	   		else
	   			$fbd_m=0;
	   		if(isset($post['devs_m']))
	   			$devs_m=$post['devs_m'];
	   		else
	   			$devs_m=0;
	   		if(isset($post['heads_m']))
	   			$heads_m=$post['heads_m'];
	   		else
	   			$heads_m=0;
	   		if(isset($post['canss_m']))
	   			$canss_m=$post['canss_m'];
	   		else
	   			$canss_m=0;
	   		if(isset($post['ovs_m']))
	   			$ovs_m=$post['ovs_m'];
	   		else
	   			$ovs_m=0;

		   		if(isset($post['history_chief_ovs_glare']))
		   			$history_chief_ovs_glare=$post['history_chief_ovs_glare'];
		   		else
		   			$history_chief_ovs_glare=0;
		   		if(isset($post['history_chief_ovs_floaters']))
		   			$history_chief_ovs_floaters=$post['history_chief_ovs_floaters'];
		   		else
		   			$history_chief_ovs_floaters=0;
		   		if(isset($post['history_chief_ovs_photophobia']))
		   			$history_chief_ovs_photophobia=$post['history_chief_ovs_photophobia'];
		   		else
		   			$history_chief_ovs_photophobia=0;
		   		if(isset($post['history_chief_ovs_color_halos']))
		   			$history_chief_ovs_color_halos=$post['history_chief_ovs_color_halos'];
		   		else
		   			$history_chief_ovs_color_halos=0;
		   		if(isset($post['history_chief_ovs_metamorphopsia']))
		   			$history_chief_ovs_metamorphopsia=$post['history_chief_ovs_metamorphopsia'];
		   		else
		   			$history_chief_ovs_metamorphopsia=0;
		   		if(isset($post['history_chief_ovs_chromatopsia']))
		   			$history_chief_ovs_chromatopsia=$post['history_chief_ovs_chromatopsia'];
		   		else
		   			$history_chief_ovs_chromatopsia=0;
		   		if(isset($post['history_chief_ovs_dnv']))
		   			$history_chief_ovs_dnv=$post['history_chief_ovs_dnv'];
		   		else
		   			$history_chief_ovs_dnv=0;
		   		if(isset($post['history_chief_ovs_ddv']))
		   			$history_chief_ovs_ddv=$post['history_chief_ovs_ddv'];
		   		else
		   			$history_chief_ovs_ddv=0;
	   		
	   		if(isset($post['sdv_m']))
	   			$sdv_m=$post['sdv_m'];
	   		else
	   			$sdv_m=0;
	   		if(isset($post['doe_m']))
	   			$doe_m=$post['doe_m'];
	   		else
	   			$doe_m=0;
	   		if(isset($post['swel_m']))
	   			$swel_m=$post['swel_m'];
	   		else
	   			$swel_m=0;
	   		if(isset($post['burns_m']))
	   			$burns_m=$post['burns_m'];
	   		else
	   			$burns_m=0;


	   		if(isset($post['gla_m']))
	   			$gla_m=$post['gla_m'];
	   		else
	   			$gla_m=0;
	   		if(isset($post['reti_m']))
	   			$reti_m=$post['reti_m'];
	   		else
	   			$reti_m=0;
	   		if(isset($post['glass_m']))
	   			$glass_m=$post['glass_m'];
	   		else
	   			$glass_m=0;
	   		if(isset($post['eyedi_m']))
	   			$eyedi_m=$post['eyedi_m'];
	   		else
	   			$eyedi_m=0;
	   		if(isset($post['eyesu_m']))
	   			$eyesu_m=$post['eyesu_m'];
	   		else
	   			$eyesu_m=0;
	   		if(isset($post['uve_m']))
	   			$uve_m=$post['uve_m'];
	   		else
	   			$uve_m=0;
	   		if(isset($post['retil_m']))
	   			$retil_m=$post['retil_m'];
	   		else
	   			$retil_m=0;
	   		
			
	   		if(isset($post['dia_m']))
	   			$dia_m=$post['dia_m'];
	   		else
	   			$dia_m=0;
	   		if(isset($post['hyper_m']))
	   			$hyper_m=$post['hyper_m'];
	   		else
	   			$hyper_m=0;
	   		if(isset($post['alcoh_m']))
	   			$alcoh_m=$post['alcoh_m'];
	   		else
	   			$alcoh_m=0;
	   		if(isset($post['smok_m']))
	   			$smok_m=$post['smok_m'];
	   		else
	   			$smok_m=0;
	   		if(isset($post['card_m']))
	   			$card_m=$post['card_m'];
	   		else
	   			$card_m=0;
	   		if(isset($post['steri_m']))
	   			$steri_m=$post['steri_m'];
	   		else
	   			$steri_m=0;
	   		if(isset($post['drug_m']))
	   			$drug_m=$post['drug_m'];
	   		else
	   			$drug_m=0;
			if(isset($post['hiva_m']))
	   			$hiva_m=$post['hiva_m'];
	   		else
	   			$hiva_m=0;
	   		if(isset($post['cant_m']))
	   			$cant_m=$post['cant_m'];
	   		else
	   			$cant_m=0;
	   		if(isset($post['tuber_m']))
	   			$tuber_m=$post['tuber_m'];
	   		else
	   			$tuber_m=0;
	   		if(isset($post['asth_m']))
	   			$asth_m=$post['asth_m'];
	   		else
	   			$asth_m=0;
	   		if(isset($post['cnsds_m']))
	   			$cnsds_m=$post['cnsds_m'];
	   		else
	   			$cnsds_m=0;
	   		if(isset($post['hypo_m']))
	   			$hypo_m=$post['hypo_m'];
	   		else
	   			$hypo_m=0;
			if(isset($post['hyperth_m']))
	   			$hyperth_m=$post['hyperth_m'];
	   		else
	   			$hyperth_m=0;
	   		if(isset($post['hepac_m']))
	   			$hepac_m=$post['hepac_m'];
	   		else
	   			$hepac_m=0;
	   		if(isset($post['renald_m']))
	   			$renald_m=$post['renald_m'];
	   		else
	   			$renald_m=0;
	   		if(isset($post['acid_m']))
	   			$acid_m=$post['acid_m'];
	   		else
	   			$acid_m=0;
	   		if(isset($post['oins_m']))
	   			$oins_m=$post['oins_m'];
	   		else
	   			$oins_m=0;
	   		if(isset($post['oasp_m']))
	   			$oasp_m=$post['oasp_m'];
	   		else
	   			$oasp_m=0;
	   		if(isset($post['acon_m']))
	   			$acon_m=$post['acon_m'];
	   		else
	   			$acon_m=0;
			if(isset($post['thd_m']))
	   			$thd_m=$post['thd_m'];
	   		else
	   			$thd_m=0;
	   		if(isset($post['chewt_m']))
	   			$chewt_m=$post['chewt_m'];
	   		else
	   			$chewt_m=0;

	   		if(isset($post['antimi_agen_m']))
	   			$antimi_agen_m=$post['antimi_agen_m'];
	   		else
	   			$antimi_agen_m=0;
	   		if(isset($post['ampic_m']))
	   			$ampic_m=$post['ampic_m'];
	   		else
	   			$ampic_m=0;
	   		if(isset($post['amox_m']))
	   			$amox_m=$post['amox_m'];
	   		else
	   			$amox_m=0;
	   		if(isset($post['ceftr_m']))
	   			$ceftr_m=$post['ceftr_m'];
	   		else
	   			$ceftr_m=0;
	   		if(isset($post['cipro_m']))
	   			$cipro_m=$post['cipro_m'];
	   		else
	   			$cipro_m=0;
	   		if(isset($post['clari_m']))
	   			$clari_m=$post['clari_m'];
	   		else
	   			$clari_m=0;
			if(isset($post['cotri_m']))
	   			$cotri_m=$post['cotri_m'];
	   		else
	   			$cotri_m=0;
	   		if(isset($post['etham_m']))
	   			$etham_m=$post['etham_m'];
	   		else
	   			$etham_m=0;
	   		if(isset($post['ison_m']))
	   			$ison_m=$post['ison_m'];
	   		else
	   			$ison_m=0;
	   		if(isset($post['metro_m']))
	   			$metro_m=$post['metro_m'];
	   		else
	   			$metro_m=0;
	   		if(isset($post['penic_m']))
	   			$penic_m=$post['penic_m'];
	   		else
	   			$penic_m=0;
			if(isset($post['rifa_m']))
	   			$rifa_m=$post['rifa_m'];
	   		else
	   			$rifa_m=0;
	   		if(isset($post['strep_m']))
	   			$strep_m=$post['strep_m'];
	   		else
	   			$strep_m=0;


	   		if(isset($post['antif_agen_m']))
	   			$antif_agen_m=$post['antif_agen_m'];
	   		else
	   			$antif_agen_m=0;
	   		if(isset($post['ketoc_m']))
	   			$ketoc_m=$post['ketoc_m'];
	   		else
	   			$ketoc_m=0;
			if(isset($post['fluco_m']))
	   			$fluco_m=$post['fluco_m'];
	   		else
	   			$fluco_m=0;
	   		if(isset($post['itrac_m']))
	   			$itrac_m=$post['itrac_m'];
	   		else
	   			$itrac_m=0;
			

	   		if(isset($post['ant_agen_m']))
	   			$ant_agen_m=$post['ant_agen_m'];
	   		else
	   			$ant_agen_m=0;
	   		if(isset($post['acyclo_m']))
	   			$acyclo_m=$post['acyclo_m'];
	   		else
	   			$acyclo_m=0;
	   		if(isset($post['efavir_m']))
	   			$efavir_m=$post['efavir_m'];
	   		else
	   			$efavir_m=0;
	   		if(isset($post['enfuv_m']))
	   			$enfuv_m=$post['enfuv_m'];
	   		else
	   			$enfuv_m=0;
	   		if(isset($post['nelfin_m']))
	   			$nelfin_m=$post['nelfin_m'];
	   		else
	   			$nelfin_m=0;
	   		if(isset($post['nevira_m']))
	   			$nevira_m=$post['nevira_m'];
	   		else
	   			$nevira_m=0;
	   		if(isset($post['zidov_m']))
	   			$zidov_m=$post['zidov_m'];
	   		else
	   			$zidov_m=0;

	   		
	   		if(isset($post['nsaids_m']))
	   			$nsaids_m=$post['nsaids_m'];
	   		else
	   			$nsaids_m=0;
	   		if(isset($post['aspirin_m']))
	   			$aspirin_m=$post['aspirin_m'];
	   		else
	   			$aspirin_m=0;
	   		if(isset($post['paracet_m']))
	   			$paracet_m=$post['paracet_m'];
	   		else
	   			$paracet_m=0;
	   		if(isset($post['ibupro_m']))
	   			$ibupro_m=$post['ibupro_m'];
	   		else
	   			$ibupro_m=0;
	   		if(isset($post['diclo_m']))
	   			$diclo_m=$post['diclo_m'];
	   		else
	   			$diclo_m=0;
	   		if(isset($post['aceclo_m']))
	   			$aceclo_m=$post['aceclo_m'];
	   		else
	   			$aceclo_m=0;
	   		if(isset($post['napro_m']))
	   			$napro_m=$post['napro_m'];
	   		else
	   			$napro_m=0;
			

	   		if(isset($post['eye_drops_m']))
	   			$eye_drops_m=$post['eye_drops_m'];
	   		else
	   			$eye_drops_m=0;
	   		if(isset($post['tropip_m']))
	   			$tropip_m=$post['tropip_m'];
	   		else
	   			$tropip_m=0;
	   		if(isset($post['tropi_m']))
	   			$tropi_m=$post['tropi_m'];
	   		else
	   			$tropi_m=0;
	   		if(isset($post['timolol_m']))
	   			$timolol_m=$post['timolol_m'];
	   		else
	   			$timolol_m=0;
	   		if(isset($post['homide_m']))
	   			$homide_m=$post['homide_m'];
	   		else
	   			$homide_m=0;
	   		if(isset($post['brimo_m']))
	   			$brimo_m=$post['brimo_m'];
	   		else
	   			$brimo_m=0;
	   		if(isset($post['latan_m']))
	   			$latan_m=$post['latan_m'];
	   		else
	   			$latan_m=0;
	   		if(isset($post['travo_m']))
	   			$travo_m=$post['travo_m'];
	   		else
	   			$travo_m=0;
	   		if(isset($post['tobra_m']))
	   			$tobra_m=$post['tobra_m'];
	   		else
	   			$tobra_m=0;
			if(isset($post['moxif_m']))
	   			$moxif_m=$post['moxif_m'];
	   		else
	   			$moxif_m=0;
	   		if(isset($post['homat_m']))
	   			$homat_m=$post['homat_m'];
	   		else
	   			$homat_m=0;
	   		if(isset($post['tobra_m']))
	   			$tobra_m=$post['tobra_m'];
	   		else
	   			$tobra_m=0;
	   		if(isset($post['piloc_m']))
	   			$piloc_m=$post['piloc_m'];
	   		else
	   			$piloc_m=0;
	   		if(isset($post['piloc_m']))
	   			$piloc_m=$post['piloc_m'];
	   		else
	   			$piloc_m=0;
	   		if(isset($post['cyclop_m']))
	   			$cyclop_m=$post['cyclop_m'];
	   		else
	   			$cyclop_m=0;
			////////////
			if(isset($post['atrop_m']))
	   			$atrop_m=$post['atrop_m'];
	   		else
	   			$atrop_m=0;
	   		if(isset($post['phenyl_m']))
	   			$phenyl_m=$post['phenyl_m'];
	   		else
	   			$phenyl_m=0;
	   		if(isset($post['tropic_m']))
	   			$tropic_m=$post['tropic_m'];
	   		else
	   			$tropic_m=0;
	   		if(isset($post['parac_m']))
	   			$parac_m=$post['parac_m'];
	   		else
	   			$parac_m=0;
	   		if(isset($post['ciplox_m']))
	   			$ciplox_m=$post['ciplox_m'];
	   		else
	   			$ciplox_m=0;


	   		if(isset($post['alco_m']))
	   			$alco_m=$post['alco_m'];
	   		else
	   			$alco_m=0;
			if(isset($post['latex_m']))
	   			$latex_m=$post['latex_m'];
	   		else
	   			$latex_m=0;
	   		if(isset($post['betad_m']))
	   			$betad_m=$post['betad_m'];
	   		else
	   			$betad_m=0;
	   		if(isset($post['adhes_m']))
	   			$adhes_m=$post['adhes_m'];
	   		else
	   			$adhes_m=0;
	   		if(isset($post['tegad_m']))
	   			$tegad_m=$post['tegad_m'];
	   		else
	   			$tegad_m=0;
	   		if(isset($post['trans_m']))
	   			$trans_m=$post['trans_m'];
	   		else
	   			$trans_m=0;

			if(isset($post['seaf_m']))
	   			$seaf_m=$post['seaf_m'];
	   		else
	   			$seaf_m=0;
	   		if(isset($post['corn_m']))
	   			$corn_m=$post['corn_m'];
	   		else
	   			$corn_m=0;
	   		if(isset($post['egg_m']))
	   			$egg_m=$post['egg_m'];
	   		else
	   			$egg_m=0;
	   		if(isset($post['milk_m']))
	   			$milk_m=$post['milk_m'];
	   		else
	   			$milk_m=0;
	   		if(isset($post['pean_m']))
	   			$pean_m=$post['pean_m'];
	   		else
	   			$pean_m=0;
	   		if(isset($post['shell_m']))
	   			$shell_m=$post['shell_m'];
	   		else
	   			$shell_m=0;
	   		if(isset($post['soy_m']))
	   			$soy_m=$post['soy_m'];
	   		else
	   			$soy_m=0;
	   		if(isset($post['lact_m']))
	   			$lact_m=$post['lact_m'];
	   		else
	   			$lact_m=0;
	   		if(isset($post['mush_m']))
	   			$mush_m=$post['mush_m'];
	   		else
	   			$mush_m=0;

			$chief_complaints=array(
				'bdv_m'=>$bdv_m, 'history_chief_blurr_side'=>$post['history_chief_blurr_side'], 'history_chief_blurr_dur'=>$post['history_chief_blurr_dur'],'history_chief_blurr_unit'=>$post['history_chief_blurr_unit'], 'history_chief_blurr_comm'=>$post['history_chief_blurr_comm'], 'history_chief_blurr_dist'=>$history_chief_blurr_dist, 'history_chief_blurr_near'=>$history_chief_blurr_near, 'history_chief_blurr_pain'=>$history_chief_blurr_pain,'history_chief_blurr_ug'=>$history_chief_blurr_ug,
				'pain_m'=>$pain_m,'history_chief_pains_side'=>$post['history_chief_pains_side'],'history_chief_pains_dur'=>$post['history_chief_pains_dur'],'history_chief_pains_unit'=>$post['history_chief_pains_unit'],'history_chief_pains_comm'=>$post['history_chief_pains_comm'],
				'redness_m'=>$redness_m,'history_chief_rednes_side'=>$post['history_chief_rednes_side'], 'history_chief_rednes_dur'=>$post['history_chief_rednes_dur'], 'history_chief_rednes_unit'=>$post['history_chief_rednes_unit'], 'history_chief_rednes_comm'=>$post['history_chief_rednes_comm'],
				'injury_m'=>$injury_m,'history_chief_injuries_side'=>$post['history_chief_injuries_side'], 'history_chief_injuries_dur'=>$post['history_chief_injuries_dur'], 'history_chief_injuries_unit'=>$post['history_chief_injuries_unit'],'history_chief_injuries_comm'=>$post['history_chief_injuries_comm'],
				'water_m'=>$water_m,'history_chief_waterings_side'=>$post['history_chief_waterings_side'], 'history_chief_waterings_dur'=>$post['history_chief_waterings_dur'], 'history_chief_waterings_unit'=>$post['history_chief_waterings_unit'],'history_chief_waterings_comm'=>$post['history_chief_waterings_comm'],
				'discharge_m'=>$discharge_m,'history_chief_discharges_side'=>$post['history_chief_discharges_side'], 'history_chief_discharges_dur'=>$post['history_chief_discharges_dur'], 'history_chief_discharges_unit'=>$post['history_chief_discharges_unit'],'history_chief_discharges_comm'=>$post['history_chief_discharges_comm'],
				'dryness_m'=>$dryness_m,'history_chief_dryness_side'=>$post['history_chief_dryness_side'], 'history_chief_dryness_dur'=>$post['history_chief_dryness_dur'], 'history_chief_dryness_unit'=>$post['history_chief_dryness_unit'],'history_chief_dryness_comm'=>$post['history_chief_dryness_comm'],
				'itch_m'=>$itch_m,'history_chief_itchings_side'=>$post['history_chief_itchings_side'], 'history_chief_itchings_dur'=>$post['history_chief_itchings_dur'], 'history_chief_itchings_unit'=>$post['history_chief_itchings_unit'],'history_chief_itchings_comm'=>$post['history_chief_itchings_comm'],
				'fbd_m'=>$fbd_m,'history_chief_fbsensation_side'=>$post['history_chief_fbsensation_side'], 'history_chief_fbsensation_dur'=>$post['history_chief_fbsensation_dur'], 'history_chief_fbsensation_unit'=>$post['history_chief_fbsensation_unit'],'history_chief_fbsensation_comm'=>$post['history_chief_fbsensation_comm'],
				'devs_m'=>$devs_m,'history_chief_dev_squint_side'=>$post['history_chief_dev_squint_side'], 'history_chief_dev_squint_dur'=>$post['history_chief_dev_squint_dur'], 'history_chief_dev_squint_unit'=>$post['history_chief_dev_squint_unit'],'history_chief_dev_squint_comm'=>$post['history_chief_dev_squint_comm'],
				    'history_chief_dev_diplopia'=>$post['history_chief_dev_diplopia'],'history_chief_dev_truma'=>$post['history_chief_dev_truma'],'history_chief_dev_ps'=>$post['history_chief_dev_ps'],
				'heads_m'=>$heads_m,'history_chief_head_strain_side'=>$post['history_chief_head_strain_side'], 'history_chief_head_strain_dur'=>$post['history_chief_head_strain_dur'], 'history_chief_head_strain_unit'=>$post['history_chief_head_strain_unit'],'history_chief_head_strain_comm'=>$post['history_chief_head_strain_comm'],
				'canss_m'=>$canss_m,'history_chief_size_shape_side'=>$post['history_chief_size_shape_side'], 'history_chief_size_shape_dur'=>$post['history_chief_size_shape_dur'], 'history_chief_size_shape_unit'=>$post['history_chief_size_shape_unit'],'history_chief_size_shape_comm'=>$post['history_chief_size_shape_comm'],
				'ovs_m'=>$ovs_m,'history_chief_ovs_side'=>$post['history_chief_ovs_side'], 'history_chief_ovs_dur'=>$post['history_chief_ovs_dur'], 'history_chief_ovs_unit'=>$post['history_chief_ovs_unit'],'history_chief_ovs_comm'=>$post['history_chief_ovs_comm'], 'history_chief_ovs_glare'=>$history_chief_ovs_glare, 'history_chief_ovs_floaters'=>$history_chief_ovs_floaters, 'history_chief_ovs_photophobia'=>$history_chief_ovs_photophobia, 'history_chief_ovs_color_halos'=>$history_chief_ovs_color_halos, 'history_chief_ovs_metamorphopsia'=>$history_chief_ovs_metamorphopsia, 'history_chief_ovs_chromatopsia'=>$history_chief_ovs_chromatopsia, 'history_chief_ovs_dnv'=>$history_chief_ovs_dnv, 'history_chief_ovs_ddv'=>$history_chief_ovs_ddv,
				'sdv_m'=>$sdv_m,'history_chief_sdiv_side'=>$post['history_chief_sdiv_side'], 'history_chief_sdiv_dur'=>$post['history_chief_sdiv_dur'], 'history_chief_sdiv_unit'=>$post['history_chief_sdiv_unit'],'history_chief_sdiv_comm'=>$post['history_chief_sdiv_comm'],
				'doe_m'=>$doe_m,'history_chief_doe_side'=>$post['history_chief_doe_side'], 'history_chief_doe_dur'=>$post['history_chief_doe_dur'], 'history_chief_doe_unit'=>$post['history_chief_doe_unit'],'history_chief_doe_comm'=>$post['history_chief_doe_comm'],
				'swel_m'=>$swel_m,'history_chief_swell_side'=>$post['history_chief_swell_side'], 'history_chief_swell_dur'=>$post['history_chief_swell_dur'], 'history_chief_swell_unit'=>$post['history_chief_swell_unit'],'history_chief_swell_comm'=>$post['history_chief_swell_comm'],
			 	'burns_m'=>$burns_m,'history_chief_sen_burn_side'=>$post['history_chief_sen_burn_side'], 'history_chief_sen_burn_dur'=>$post['history_chief_sen_burn_dur'], 'history_chief_sen_burn_unit'=>$post['history_chief_sen_burn_unit'],'history_chief_sen_burn_comm'=>$post['history_chief_sen_burn_comm'],'history_chief_comm'=>$post['history_chief_comm']);
		

			$ophthalmic=array(
				'gla_m'=>$gla_m, 'history_ophthalmic_glau_l_dur'=>$post['history_ophthalmic_glau_l_dur'], 'history_ophthalmic_glau_l_unit'=>$post['history_ophthalmic_glau_l_unit'],'history_ophthalmic_glau_r_dur'=>$post['history_ophthalmic_glau_r_dur'], 'history_ophthalmic_glau_r_unit'=>$post['history_ophthalmic_glau_r_unit'], 'history_ophthalmic_glau_comm'=>$post['history_ophthalmic_glau_comm'],
				'reti_m'=>$reti_m,'history_ophthalmic_renti_d_l_dur'=>$post['history_ophthalmic_renti_d_l_dur'], 'history_ophthalmic_renti_d_l_unit'=>$post['history_ophthalmic_renti_d_l_unit'],'history_ophthalmic_renti_d_r_dur'=>$post['history_ophthalmic_renti_d_r_dur'], 'history_ophthalmic_renti_d_r_unit'=>$post['history_ophthalmic_renti_d_r_unit'], 'history_ophthalmic_renti_d_comm'=>$post['history_ophthalmic_renti_d_comm'],
				'glass_m'=>$glass_m,'history_ophthalmic_glas_l_dur'=>$post['history_ophthalmic_glas_l_dur'], 'history_ophthalmic_glas_l_unit'=>$post['history_ophthalmic_glas_l_unit'],'history_ophthalmic_glas_r_dur'=>$post['history_ophthalmic_glas_r_dur'], 'history_ophthalmic_glas_r_unit'=>$post['history_ophthalmic_glas_r_unit'], 'history_ophthalmic_glas_comm'=>$post['history_ophthalmic_glas_comm'],
				'eyedi_m'=>$eyedi_m,'history_ophthalmic_eye_d_l_dur'=>$post['history_ophthalmic_eye_d_l_dur'], 'history_ophthalmic_eye_d_l_unit'=>$post['history_ophthalmic_eye_d_l_unit'],'history_ophthalmic_eye_d_r_dur'=>$post['history_ophthalmic_eye_d_r_dur'], 'history_ophthalmic_eye_d_r_unit'=>$post['history_ophthalmic_eye_d_r_unit'], 'history_ophthalmic_eye_d_comm'=>$post['history_ophthalmic_eye_d_comm'],
				'eyesu_m'=>$eyesu_m,'history_ophthalmic_eye_s_l_dur'=>$post['history_ophthalmic_eye_s_l_dur'], 'history_ophthalmic_eye_s_l_unit'=>$post['history_ophthalmic_eye_s_l_unit'],'history_ophthalmic_eye_s_r_dur'=>$post['history_ophthalmic_eye_s_r_dur'], 'history_ophthalmic_eye_s_r_unit'=>$post['history_ophthalmic_eye_s_r_unit'], 'history_ophthalmic_eye_s_comm'=>$post['history_ophthalmic_eye_s_comm'],
				'uve_m'=>$uve_m,'history_ophthalmic_uvei_l_dur'=>$post['history_ophthalmic_uvei_l_dur'], 'history_ophthalmic_uvei_l_unit'=>$post['history_ophthalmic_uvei_l_unit'],'history_ophthalmic_uvei_r_dur'=>$post['history_ophthalmic_uvei_r_dur'], 'history_ophthalmic_uvei_r_unit'=>$post['history_ophthalmic_uvei_r_unit'], 'history_ophthalmic_uvei_comm'=>$post['history_ophthalmic_uvei_comm'],
				'retil_m'=>$retil_m,'history_ophthalmic_renti_l_l_dur'=>$post['history_ophthalmic_renti_l_l_dur'], 'history_ophthalmic_renti_l_l_unit'=>$post['history_ophthalmic_renti_l_l_unit'],'history_ophthalmic_renti_l_r_dur'=>$post['history_ophthalmic_renti_l_r_dur'], 'history_ophthalmic_renti_l_r_unit'=>$post['history_ophthalmic_renti_l_r_unit'], 'history_ophthalmic_renti_l_comm'=>$post['history_ophthalmic_renti_l_comm'], 'history_ophthalmic_comm'=>$post['history_ophthalmic_comm'],
				);

			$systematic_history=array(
				'dia_m'=>$dia_m, 'history_systemic_diab_dur'=>$post['history_systemic_diab_dur'], 'history_systemic_diab_unit'=>$post['history_systemic_diab_unit'],'history_systemic_diab_comm'=>$post['history_systemic_diab_comm'],
				'hyper_m'=>$hyper_m,'history_systemic_hyper_dur'=>$post['history_systemic_hyper_dur'], 'history_systemic_hyper_unit'=>$post['history_systemic_hyper_unit'],'history_systemic_hyper_comm'=>$post['history_systemic_hyper_comm'],
				'alcoh_m'=>$alcoh_m,'history_systemic_alcoh_dur'=>$post['history_systemic_alcoh_dur'], 'history_systemic_alcoh_unit'=>$post['history_systemic_alcoh_unit'],'history_systemic_alcoh_comm'=>$post['history_systemic_alcoh_comm'],
				'smok_m'=>$smok_m,'history_systemic_smokt_dur'=>$post['history_systemic_smokt_dur'], 'history_systemic_smokt_unit'=>$post['history_systemic_smokt_unit'],'history_systemic_smokt_comm'=>$post['history_systemic_smokt_comm'],
				'card_m'=>$card_m,'history_systemic_cardd_dur'=>$post['history_systemic_cardd_dur'], 'history_systemic_cardd_unit'=>$post['history_systemic_cardd_unit'],'history_systemic_cardd_comm'=>$post['history_systemic_cardd_comm'],
				'steri_m'=>$steri_m,'history_systemic_steri_dur'=>$post['history_systemic_steri_dur'], 'history_systemic_steri_unit'=>$post['history_systemic_steri_unit'],'history_systemic_steri_comm'=>$post['history_systemic_steri_comm'],
				'drug_m'=>$drug_m,'history_systemic_drug_dur'=>$post['history_systemic_drug_dur'], 'history_systemic_drug_unit'=>$post['history_systemic_drug_unit'], 'history_systemic_drug_comm'=>$post['history_systemic_drug_comm'],
				'hiva_m'=>$hiva_m,'history_systemic_hiva_dur'=>$post['history_systemic_hiva_dur'], 'history_systemic_hiva_unit'=>$post['history_systemic_hiva_unit'],'history_systemic_hiva_comm'=>$post['history_systemic_hiva_comm'],
				'cant_m'=>$cant_m,'history_systemic_cantu_dur'=>$post['history_systemic_cantu_dur'], 'history_systemic_cantu_unit'=>$post['history_systemic_cantu_unit'],'history_systemic_cantu_comm'=>$post['history_systemic_cantu_comm'],
				'tuber_m'=>$tuber_m,'history_systemic_tuberc_dur'=>$post['history_systemic_tuberc_dur'], 'history_systemic_tuberc_unit'=>$post['history_systemic_tuberc_unit'],'history_systemic_tuberc_comm'=>$post['history_systemic_tuberc_comm'],
				'asth_m'=>$asth_m,'history_systemic_asthm_dur'=>$post['history_systemic_asthm_dur'], 'history_systemic_asthm_unit'=>$post['history_systemic_asthm_unit'],'history_systemic_asthm_comm'=>$post['history_systemic_asthm_comm'],
				'cnsds_m'=>$cnsds_m,'history_systemic_cncds_dur'=>$post['history_systemic_cncds_dur'], 'history_systemic_cncds_unit'=>$post['history_systemic_cncds_unit'],'history_systemic_cncds_comm'=>$post['history_systemic_cncds_comm'],
				'hypo_m'=>$hypo_m,'history_systemic_hypo_dur'=>$post['history_systemic_hypo_dur'], 'history_systemic_hypo_unit'=>$post['history_systemic_hypo_unit'],'history_systemic_hypo_comm'=>$post['history_systemic_hypo_comm'],
				'hyperth_m'=>$hyperth_m,'history_systemic_hyperth_dur'=>$post['history_systemic_hyperth_dur'], 'history_systemic_hyperth_unit'=>$post['history_systemic_hyperth_unit'],'history_systemic_hyperth_comm'=>$post['history_systemic_hyperth_comm'],
				'hepac_m'=>$hepac_m,'history_systemic_heptc_dur'=>$post['history_systemic_heptc_dur'], 'history_systemic_heptc_unit'=>$post['history_systemic_heptc_unit'],'history_systemic_heptc_comm'=>$post['history_systemic_heptc_comm'],
				'renald_m'=>$renald_m,'history_systemic_rendis_dur'=>$post['history_systemic_rendis_dur'], 'history_systemic_rendis_unit'=>$post['history_systemic_rendis_unit'],'history_systemic_rendis_comm'=>$post['history_systemic_rendis_comm'],
				'acid_m'=>$acid_m,'history_systemic_acid_dur'=>$post['history_systemic_acid_dur'], 'history_systemic_acid_unit'=>$post['history_systemic_acid_unit'],'history_systemic_acid_comm'=>$post['history_systemic_acid_comm'],
			 	'oins_m'=>$oins_m,'history_systemic_onins_dur'=>$post['history_systemic_onins_dur'], 'history_systemic_onins_unit'=>$post['history_systemic_onins_unit'],'history_systemic_onins_comm'=>$post['history_systemic_onins_comm'],
			 	'oasp_m'=>$oasp_m,'history_systemic_oasbth_dur'=>$post['history_systemic_oasbth_dur'], 'history_systemic_oasbth_unit'=>$post['history_systemic_oasbth_unit'],'history_systemic_oasbth_comm'=>$post['history_systemic_oasbth_comm'],
				'acon_m'=>$acon_m,'history_systemic_consan_dur'=>$post['history_systemic_consan_dur'], 'history_systemic_consan_unit'=>$post['history_systemic_consan_unit'],'history_systemic_consan_comm'=>$post['history_systemic_consan_comm'],
				'thd_m'=>$thd_m,'history_systemic_thyrd_dur'=>$post['history_systemic_thyrd_dur'], 'history_systemic_thyrd_unit'=>$post['history_systemic_thyrd_unit'],'history_systemic_thyrd_comm'=>$post['history_systemic_thyrd_comm'],
			 	'chewt_m'=>$chewt_m,'history_systemic_chewt_dur'=>$post['history_systemic_chewt_dur'], 'history_systemic_chewt_unit'=>$post['history_systemic_chewt_unit'],'history_systemic_chewt_comm'=>$post['history_systemic_chewt_comm'],'history_systemic_comm'=>$post['history_systemic_comm']);
			
			$drugs_antimi_agent=array(
				'antimi_agen_m'=>$antimi_agen_m,
				'ampic_m'=>$ampic_m, 'history_drug_antimicrobial_ampici_dur'=>$post['history_drug_antimicrobial_ampici_dur'], 'history_drug_antimicrobial_ampici_unit'=>$post['history_drug_antimicrobial_ampici_unit'],'history_drug_antimicrobial_ampici_comm'=>$post['history_drug_antimicrobial_ampici_comm'],
				'amox_m'=>$amox_m, 'history_drug_antimicrobial_amoxi_dur'=>$post['history_drug_antimicrobial_amoxi_dur'], 'history_drug_antimicrobial_amoxi_unit'=>$post['history_drug_antimicrobial_amoxi_unit'],'history_drug_antimicrobial_amoxi_comm'=>$post['history_drug_antimicrobial_amoxi_comm'],
				'ceftr_m'=>$ceftr_m, 'history_drug_antimicrobial_ceftr_dur'=>$post['history_drug_antimicrobial_ceftr_dur'], 'history_drug_antimicrobial_ceftr_unit'=>$post['history_drug_antimicrobial_ceftr_unit'],'history_drug_antimicrobial_ceftr_comm'=>$post['history_drug_antimicrobial_ceftr_comm'],
				'cipro_m'=>$cipro_m, 'history_drug_antimicrobial_ciprof_dur'=>$post['history_drug_antimicrobial_ciprof_dur'], 'history_drug_antimicrobial_ciprof_unit'=>$post['history_drug_antimicrobial_ciprof_unit'],'history_drug_antimicrobial_ciprof_comm'=>$post['history_drug_antimicrobial_ciprof_comm'],
				'clari_m'=>$clari_m, 'history_drug_antimicrobial_clarith_dur'=>$post['history_drug_antimicrobial_clarith_dur'], 'history_drug_antimicrobial_clarith_unit'=>$post['history_drug_antimicrobial_clarith_unit'],'history_drug_antimicrobial_clarith_comm'=>$post['history_drug_antimicrobial_clarith_comm'],
				'cotri_m'=>$cotri_m, 'history_drug_antimicrobial_cotri_dur'=>$post['history_drug_antimicrobial_cotri_dur'], 'history_drug_antimicrobial_cotri_unit'=>$post['history_drug_antimicrobial_cotri_unit'],'history_drug_antimicrobial_cotri_comm'=>$post['history_drug_antimicrobial_cotri_comm'],
				'etham_m'=>$etham_m, 'history_drug_antimicrobial_ethamb_dur'=>$post['history_drug_antimicrobial_ethamb_dur'], 'history_drug_antimicrobial_ethamb_unit'=>$post['history_drug_antimicrobial_ethamb_unit'],'history_drug_antimicrobial_ethamb_comm'=>$post['history_drug_antimicrobial_ethamb_comm'],
				'ison_m'=>$ison_m, 'history_drug_antimicrobial_isoni_dur'=>$post['history_drug_antimicrobial_isoni_dur'], 'history_drug_antimicrobial_isoni_unit'=>$post['history_drug_antimicrobial_isoni_unit'],'history_drug_antimicrobial_isoni_comm'=>$post['history_drug_antimicrobial_isoni_comm'],
				'metro_m'=>$metro_m, 'history_drug_antimicrobial_metron_dur'=>$post['history_drug_antimicrobial_metron_dur'], 'history_drug_antimicrobial_metron_unit'=>$post['history_drug_antimicrobial_metron_unit'],'history_drug_antimicrobial_metron_comm'=>$post['history_drug_antimicrobial_metron_comm'],
				'penic_m'=>$penic_m, 'history_drug_antimicrobial_penic_dur'=>$post['history_drug_antimicrobial_penic_dur'], 'history_drug_antimicrobial_penic_unit'=>$post['history_drug_antimicrobial_penic_unit'],'history_drug_antimicrobial_penic_comm'=>$post['history_drug_antimicrobial_penic_comm'],
				'rifa_m'=>$rifa_m, 'history_drug_antimicrobial_rifam_dur'=>$post['history_drug_antimicrobial_rifam_dur'], 'history_drug_antimicrobial_rifam_unit'=>$post['history_drug_antimicrobial_rifam_unit'],'history_drug_antimicrobial_rifam_comm'=>$post['history_drug_antimicrobial_rifam_comm'],
				'strep_m'=>$strep_m, 'history_drug_antimicrobial_strept_dur'=>$post['history_drug_antimicrobial_strept_dur'], 'history_drug_antimicrobial_strept_unit'=>$post['history_drug_antimicrobial_strept_unit'],'history_drug_antimicrobial_strept_comm'=>$post['history_drug_antimicrobial_strept_comm']);


			$drugs_antifungal_agent=array(
				'antif_agen_m'=>$antif_agen_m,
				'ketoc_m'=>$ketoc_m, 'history_drug_antifungal_ketoco_dur'=>$post['history_drug_antifungal_ketoco_dur'], 'history_drug_antifungal_ketoco_unit'=>$post['history_drug_antifungal_ketoco_unit'],'history_drug_antifungal_ketoco_comm'=>$post['history_drug_antifungal_ketoco_comm'],
				'fluco_m'=>$fluco_m, 'history_drug_antifungal_flucon_dur'=>$post['history_drug_antifungal_flucon_dur'], 'history_drug_antifungal_flucon_unit'=>$post['history_drug_antifungal_flucon_unit'],'history_drug_antifungal_flucon_comm'=>$post['history_drug_antifungal_flucon_comm'],
				'itrac_m'=>$itrac_m, 'history_drug_antifungal_itrac_dur'=>$post['history_drug_antifungal_itrac_dur'], 'history_drug_antifungal_itrac_unit'=>$post['history_drug_antifungal_itrac_unit'],'history_drug_antifungal_itrac_comm'=>$post['history_drug_antifungal_itrac_comm']);

			$drugs_antiviral_agent=array(
				'ant_agen_m'=>$ant_agen_m,
				'acyclo_m'=>$acyclo_m, 'history_drug_antiviral_acyclo_dur'=>$post['history_drug_antiviral_acyclo_dur'], 'history_drug_antiviral_acyclo_unit'=>$post['history_drug_antiviral_acyclo_unit'],'history_drug_antiviral_acyclo_comm'=>$post['history_drug_antiviral_acyclo_comm'],
				'efavir_m'=>$efavir_m, 'history_drug_antiviral_efavir_dur'=>$post['history_drug_antiviral_efavir_dur'], 'history_drug_antiviral_efavir_unit'=>$post['history_drug_antiviral_efavir_unit'],'history_drug_antiviral_efavir_comm'=>$post['history_drug_antiviral_efavir_comm'],
				'enfuv_m'=>$enfuv_m, 'history_drug_antiviral_enfuv_dur'=>$post['history_drug_antiviral_enfuv_dur'], 'history_drug_antiviral_enfuv_unit'=>$post['history_drug_antiviral_enfuv_unit'],'history_drug_antiviral_enfuv_comm'=>$post['history_drug_antiviral_enfuv_comm'],
				'nelfin_m'=>$nelfin_m, 'history_drug_antiviral_nelfin_dur'=>$post['history_drug_antiviral_nelfin_dur'], 'history_drug_antiviral_nelfin_unit'=>$post['history_drug_antiviral_nelfin_unit'],'history_drug_antiviral_nelfin_comm'=>$post['history_drug_antiviral_nelfin_comm'],
				'nevira_m'=>$nevira_m, 'history_drug_antiviral_nevira_dur'=>$post['history_drug_antiviral_nevira_dur'], 'history_drug_antiviral_nevira_unit'=>$post['history_drug_antiviral_nevira_unit'],'history_drug_antiviral_nevira_comm'=>$post['history_drug_antiviral_nevira_comm'],
				'zidov_m'=>$zidov_m, 'history_drug_antiviral_zidov_dur'=>$post['history_drug_antiviral_zidov_dur'], 'history_drug_antiviral_zidov_unit'=>$post['history_drug_antiviral_zidov_unit'],'history_drug_antiviral_zidov_comm'=>$post['history_drug_antiviral_zidov_comm']);


			$drugs_nsaids_agent=array(
				'nsaids_m'=>$nsaids_m,
				'aspirin_m'=>$aspirin_m, 'history_drug_nsaids_aspirin_dur'=>$post['history_drug_nsaids_aspirin_dur'], 'history_drug_nsaids_aspirin_unit'=>$post['history_drug_nsaids_aspirin_unit'],'history_drug_nsaids_aspirin_comm'=>$post['history_drug_nsaids_aspirin_comm'],
				'paracet_m'=>$paracet_m, 'history_drug_nsaids_paracet_dur'=>$post['history_drug_nsaids_paracet_dur'], 'history_drug_nsaids_paracet_unit'=>$post['history_drug_nsaids_paracet_unit'],'history_drug_nsaids_paracet_comm'=>$post['history_drug_nsaids_paracet_comm'],
				'ibupro_m'=>$ibupro_m, 'history_drug_nsaids_ibupro_dur'=>$post['history_drug_nsaids_ibupro_dur'], 'history_drug_nsaids_ibupro_unit'=>$post['history_drug_nsaids_ibupro_unit'],'history_drug_nsaids_ibupro_comm'=>$post['history_drug_nsaids_ibupro_comm'],
				'diclo_m'=>$diclo_m, 'history_drug_nsaids_diclo_dur'=>$post['history_drug_nsaids_diclo_dur'], 'history_drug_nsaids_diclo_unit'=>$post['history_drug_nsaids_diclo_unit'],'history_drug_nsaids_diclo_comm'=>$post['history_drug_nsaids_diclo_comm'],
				'aceclo_m'=>$aceclo_m, 'history_drug_nsaids_aceclo_dur'=>$post['history_drug_nsaids_aceclo_dur'], 'history_drug_nsaids_aceclo_unit'=>$post['history_drug_nsaids_aceclo_unit'],'history_drug_nsaids_aceclo_comm'=>$post['history_drug_nsaids_aceclo_comm'],
				'napro_m'=>$napro_m, 'history_drug_nsaids_napro_dur'=>$post['history_drug_nsaids_napro_dur'], 'history_drug_nsaids_napro_unit'=>$post['history_drug_nsaids_napro_unit'],'history_drug_nsaids_napro_comm'=>$post['history_drug_nsaids_napro_comm']);

			$drugs_eyealco_m_agent=array(
				'eye_drops_m'=>$eye_drops_m,
				'tropip_m'=>$tropip_m, 'history_drug_eye_tropicp_dur'=>$post['history_drug_eye_tropicp_dur'], 'history_drug_eye_tropicp_unit'=>$post['history_drug_eye_tropicp_unit'],'history_drug_eye_tropicp_comm'=>$post['history_drug_eye_tropicp_comm'],
				'tropi_m'=>$tropi_m, 'history_drug_eye_tropica_dur'=>$post['history_drug_eye_tropica_dur'], 'history_drug_eye_tropica_unit'=>$post['history_drug_eye_tropica_unit'],'history_drug_eye_tropica_comm'=>$post['history_drug_eye_tropica_comm'],
				'timolol_m'=>$timolol_m, 'history_drug_eye_timol_dur'=>$post['history_drug_eye_timol_dur'], 'history_drug_eye_timol_unit'=>$post['history_drug_eye_timol_unit'],'history_drug_eye_timol_comm'=>$post['history_drug_eye_timol_comm'],
				'homide_m'=>$homide_m, 'history_drug_eye_homide_dur'=>$post['history_drug_eye_homide_dur'], 'history_drug_eye_homide_unit'=>$post['history_drug_eye_homide_unit'],'history_drug_eye_homide_comm'=>$post['history_drug_eye_homide_comm'],
				'brimo_m'=>$brimo_m, 'history_drug_eye_brimon_dur'=>$post['history_drug_eye_brimon_dur'], 'history_drug_eye_brimon_unit'=>$post['history_drug_eye_brimon_unit'],'history_drug_eye_brimon_comm'=>$post['history_drug_eye_brimon_comm'],
				'latan_m'=>$latan_m, 'history_drug_eye_latan_dur'=>$post['history_drug_eye_latan_dur'], 'history_drug_eye_latan_unit'=>$post['history_drug_eye_latan_unit'],'history_drug_eye_latan_comm'=>$post['history_drug_eye_latan_comm'],
				'travo_m'=>$travo_m, 'history_drug_eye_travo_dur'=>$post['history_drug_eye_travo_dur'], 'history_drug_eye_travo_unit'=>$post['history_drug_eye_travo_unit'],'history_drug_eye_travo_comm'=>$post['history_drug_eye_travo_comm'],
				'tobra_m'=>$tobra_m, 'history_drug_eye_tobra_dur'=>$post['history_drug_eye_tobra_dur'], 'history_drug_eye_tobra_unit'=>$post['history_drug_eye_tobra_unit'],'history_drug_eye_tobra_comm'=>$post['history_drug_eye_tobra_comm'],
				'moxif_m'=>$moxif_m, 'history_drug_eye_moxif_dur'=>$post['history_drug_eye_moxif_dur'], 'history_drug_eye_moxif_unit'=>$post['history_drug_eye_moxif_unit'],'history_drug_eye_moxif_comm'=>$post['history_drug_eye_moxif_comm'],
				'homat_m'=>$homat_m, 'history_drug_eye_homat_dur'=>$post['history_drug_eye_homat_dur'], 'history_drug_eye_homat_unit'=>$post['history_drug_eye_homat_unit'],'history_drug_eye_homat_comm'=>$post['history_drug_eye_homat_comm'],
				'piloc_m'=>$piloc_m, 'history_drug_eye_piloca_dur'=>$post['history_drug_eye_piloca_dur'], 'history_drug_eye_piloca_unit'=>$post['history_drug_eye_piloca_unit'],'history_drug_eye_piloca_comm'=>$post['history_drug_eye_piloca_comm'],
				'cyclop_m'=>$cyclop_m, 'history_drug_eye_cyclop_dur'=>$post['history_drug_eye_cyclop_dur'], 'history_drug_eye_cyclop_unit'=>$post['history_drug_eye_cyclop_unit'],'history_drug_eye_cyclop_comm'=>$post['history_drug_eye_cyclop_comm'],
				'atrop_m'=>$atrop_m, 'history_drug_eye_atropi_dur'=>$post['history_drug_eye_atropi_dur'], 'history_drug_eye_atropi_unit'=>$post['history_drug_eye_atropi_unit'],'history_drug_eye_atropi_comm'=>$post['history_drug_eye_atropi_comm'],
				'phenyl_m'=>$phenyl_m, 'history_drug_eye_phenyl_dur'=>$post['history_drug_eye_phenyl_dur'], 'history_drug_eye_phenyl_unit'=>$post['history_drug_eye_phenyl_unit'],'history_drug_eye_phenyl_comm'=>$post['history_drug_eye_phenyl_comm'],
				'tropic_m'=>$tropic_m, 'history_drug_eye_tropicac_dur'=>$post['history_drug_eye_tropicac_dur'], 'history_drug_eye_tropicac_unit'=>$post['history_drug_eye_tropicac_unit'],'history_drug_eye_tropicac_comm'=>$post['history_drug_eye_tropicac_comm'],
				'parac_m'=>$parac_m, 'history_drug_eye_paracain_dur'=>$post['history_drug_eye_paracain_dur'], 'history_drug_eye_paracain_unit'=>$post['history_drug_eye_paracain_unit'],'history_drug_eye_paracain_comm'=>$post['history_drug_eye_paracain_comm'],
				'ciplox_m'=>$ciplox_m, 'history_drug_eye_ciplox_dur'=>$post['history_drug_eye_ciplox_dur'], 'history_drug_eye_ciplox_unit'=>$post['history_drug_eye_ciplox_unit'],'history_drug_eye_ciplox_comm'=>$post['history_drug_eye_ciplox_comm']);


		
			$contact_allergies=array(
				'alco_m'=>$alco_m, 'history_contact_alcohol_dur'=>$post['history_contact_alcohol_dur'], 'history_contact_alcohol_unit'=>$post['history_contact_alcohol_unit'],'history_contact_alcohol_comm'=>$post['history_contact_alcohol_comm'],
				'latex_m'=>$latex_m, 'history_contact_latex_dur'=>$post['history_contact_latex_dur'], 'history_contact_latex_unit'=>$post['history_contact_latex_unit'],'history_contact_latex_comm'=>$post['history_contact_latex_comm'],
				'betad_m'=>$betad_m, 'history_contact_betad_dur'=>$post['history_contact_betad_dur'], 'history_contact_betad_unit'=>$post['history_contact_betad_unit'],'history_contact_betad_comm'=>$post['history_contact_betad_comm'],
				'adhes_m'=>$adhes_m, 'history_contact_adhes_dur'=>$post['history_contact_adhes_dur'], 'history_contact_adhes_unit'=>$post['history_contact_adhes_unit'],'history_contact_adhes_comm'=>$post['history_contact_adhes_comm'],
				'tegad_m'=>$tegad_m, 'history_contact_tegad_dur'=>$post['history_contact_tegad_dur'], 'history_contact_tegad_unit'=>$post['history_contact_tegad_unit'],'history_contact_tegad_comm'=>$post['history_contact_tegad_comm'],
				'trans_m'=>$trans_m, 'history_contact_transp_dur'=>$post['history_contact_transp_dur'], 'history_contact_transp_unit'=>$post['history_contact_transp_unit'],'history_contact_transp_comm'=>$post['history_contact_transp_comm']);

		
			$food_allergies=array(
				'seaf_m'=>$seaf_m, 'history_food_seaf_dur'=>$post['history_food_seaf_dur'], 'history_food_seaf_unit'=>$post['history_food_seaf_unit'],'history_food_seaf_comm'=>$post['history_food_seaf_comm'],
				'corn_m'=>$corn_m, 'history_food_corn_dur'=>$post['history_food_corn_dur'], 'history_food_corn_unit'=>$post['history_food_corn_unit'],'history_food_corn_comm'=>$post['history_food_corn_comm'],
				'egg_m'=>$egg_m, 'history_food_egg_dur'=>$post['history_food_egg_dur'], 'history_food_egg_unit'=>$post['history_food_egg_unit'],'history_food_egg_comm'=>$post['history_food_egg_comm'],
				'milk_m'=>$milk_m, 'history_food_milk_p_dur'=>$post['history_food_milk_p_dur'], 'history_food_milk_p_unit'=>$post['history_food_milk_p_unit'],'history_food_milk_p_comm'=>$post['history_food_milk_p_comm'],
				'pean_m'=>$pean_m, 'history_food_pean_dur'=>$post['history_food_pean_dur'], 'history_food_pean_unit'=>$post['history_food_pean_unit'],'history_food_pean_comm'=>$post['history_food_pean_comm'],
				'shell_m'=>$shell_m, 'history_food_shell_dur'=>$post['history_food_shell_dur'], 'history_food_shell_unit'=>$post['history_food_shell_unit'],'history_food_shell_comm'=>$post['history_food_shell_comm'],
				'soy_m'=>$soy_m, 'history_food_soy_dur'=>$post['history_food_soy_dur'], 'history_food_soy_unit'=>$post['history_food_soy_unit'],'history_food_soy_comm'=>$post['history_food_soy_comm'],
				'lact_m'=>$lact_m, 'history_food_lact_dur'=>$post['history_food_lact_dur'], 'history_food_lact_unit'=>$post['history_food_lact_unit'],'history_food_lact_comm'=>$post['history_food_lact_comm'],
				'mush_m'=>$mush_m, 'history_food_mush_dur'=>$post['history_food_mush_dur'], 'history_food_mush_unit'=>$post['history_food_mush_unit'],'history_food_mush_comm'=>$post['history_food_mush_comm'], 'history_food_comm'=>$post['history_food_comm'],'history_vital_temp_update'=>$post['history_vital_temp_update'], 'history_vital_pulse_update'=>$post['history_vital_pulse_update'],'history_vital_bp_update'=>$post['history_vital_bp_update']);

			$history_vital_temp=$post['history_vital_temp'];
			$history_vital_pulse=$post['history_vital_pulse'];
			$history_vital_bp=$post['history_vital_bp'];
			$history_vital_rr=$post['history_vital_rr'];
			$history_anthropometry_height=$post['history_anthropometry_height'];
			$history_anthropometry_weight=$post['history_anthropometry_weight'];
			$history_anthropometry_bmi=$post['history_anthropometry_bmi'];
			$history_anthropometry_comm=$post['history_anthropometry_comm'];

			$drug = array_merge($drugs_antimi_agent, $drugs_antifungal_agent, $drugs_antiviral_agent,$drugs_nsaids_agent, $drugs_eyealco_m_agent);

			$radios= array('general_checkup' => $general_checkup, 'special_status'=>$special_status);

			$his_data = array('branch_id'=>$post['branch_id'], 'pres_id'=>$prescriptionid, 'booking_code'=>$post['booking_code'], 'patient_id'=>$post['patient_id'], 'booking_id'=>$post['booking_id'] , 'history_radios' => json_encode($radios), 'free_test'=>$post['visit_comm'], 'family'=>$family_history,'medical'=>$post['medical_history'], 'chief_complaints'=>json_encode($chief_complaints), 'ophthalmic'=>json_encode($ophthalmic), 'systemic'=>json_encode($systematic_history), 'drug_allergies'=>json_encode($drug), 'contact_allergies'=>json_encode($contact_allergies), 'food_allergies'=>json_encode($food_allergies), 'temperature'=>$history_vital_temp, 'pulse'=>$history_vital_pulse, 'blood_pressure'=>$history_vital_bp, 'rr'=>$history_vital_rr, 'height'=>$history_anthropometry_height, 'weight'=>$history_anthropometry_weight, 'bmi'=>$history_anthropometry_bmi, 'comment'=>$history_anthropometry_comm, 'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$user_data['id'], 'created_date'=>date('Y-m-d H:i:s'));

			//Diagnosis
			$diagnosis_set=$post['diagnosis']['icd_data'];

			$diagnosis_check=isset($post['diagnosis_check']) ? '1':'0';	
			$data_diagnosis=array('branch_id'=>$post['branch_id'], 'booking_code'=>$post['booking_code'], 'pres_id'=>$prescriptionid, 'patient_id'=>$post['patient_id'], 'booking_id'=>$post['booking_id'] ,'diagnosis_data'=>json_encode($diagnosis_set),'provisional_check'=>$diagnosis_check,'provisional_cmnt'=>$post['diagnosis_cmnt'] ,'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$user_data['id'], 'created_date'=>date('Y-m-d H:i:s'));

			
			//Diagnosis

			if(!empty($post['prescrption_id']) || $post['prescrption_id'] !='')
			{
			   $this->db->where('pres_id',$post['prescrption_id']);
			   $this->db->where('booking_id',$post['booking_id']);
			   $this->db->where('branch_id',$post['branch_id']);
			   $this->db->update('hms_std_eye_prescription_history',$his_data);

			   $this->db->where('pres_id',$post['prescrption_id']);
			   $this->db->where('booking_id',$post['booking_id']);
			   $this->db->where('branch_id',$post['branch_id']);
			   $this->db->update('hms_std_eye_prescription_refraction',$ref_data);

			   $this->db->where('pres_id',$post['prescrption_id']);
			   $this->db->where('booking_id',$post['booking_id']);
			   $this->db->where('branch_id',$post['branch_id']);
			   $this->db->update('hms_std_eye_prescription_examination',$data_exam);

			   $this->db->where('pres_id',$post['prescrption_id']);
			   $this->db->where('booking_id',$post['booking_id']);
			   $this->db->where('branch_id',$post['branch_id']);
			   $this->db->update('hms_std_eye_prescription_biometry',$data_biometry);

			   $this->db->where('pres_id',$post['prescrption_id']);
			   $this->db->where('booking_id',$post['booking_id']);
			   $this->db->where('branch_id',$post['branch_id']);
			   $this->db->update('hms_std_eye_prescription_investigation',$data_investigation);

			   $this->db->where('pres_id',$post['prescrption_id']);
			   $this->db->where('booking_id',$post['booking_id']);
			   $this->db->where('branch_id',$post['branch_id']);
			   $this->db->update('hms_std_eye_prescription_diagnosis_hierarchy',$data_diagnosis);

			   $this->db->where('pres_id',$post['prescrption_id']);
			   $this->db->where('booking_id',$post['booking_id']);
			   $this->db->where('branch_id',$post['branch_id']);
			   $this->db->update('hms_std_eye_prescription_advice',$data_advice);	
			}
			else
			{
			 $this->db->insert('hms_std_eye_prescription_history',$his_data);
			 $this->db->insert('hms_std_eye_prescription_refraction',$ref_data);
			 $this->db->insert('hms_std_eye_prescription_examination',$data_exam);
			 $this->db->insert('hms_std_eye_prescription_biometry',$data_biometry);
			 $this->db->insert('hms_std_eye_prescription_investigation',$data_investigation);
			 $this->db->insert('hms_std_eye_prescription_diagnosis_hierarchy',$data_diagnosis);
			 $this->db->insert('hms_std_eye_prescription_advice',$data_advice);
		    }		
		    
		    
		    // Set drawing $prescriptionid
            $this->db->where('pres_id',$prescriptionid);
			$this->db->where('booking_id',$post['booking_id']);
            $this->db->delete('hms_eye_prescription_drawing');
            
            $drawing_data = $this->session->userdata('drawing_data');
            $user_data = $this->session->userdata('auth_users');
            if(!empty($drawing_data))
            {
                foreach($drawing_data as $drawing)
                {
                    $d_arr = array(
                                    "booking_id"=>$post['booking_id'],
                                    "pres_id"=>$prescriptionid,
                                    "image"=>$drawing['image'],
                                    "remark"=>$drawing['remark'],
                                    "created_by"=>$user_data['id'],
                                    "created_date"=>date('Y-m-d H:i:s') 
                                  );
                    $this->db->insert('hms_eye_prescription_drawing', $d_arr);          
                }                          
            }
            $drawing_data = $this->session->unset_userdata('drawing_data');

             // $prescriptionid
		    
		   $eye_file_upload = $this->session->userdata('eye_file_upload'); 
		 //echo "<pre>"; print_r($eye_file_upload); exit;
    		if(!empty($eye_file_upload))
    		{ 
                $this->db->where('booking_id',$post['booking_id']);
                $this->db->where('pres_id',$prescriptionid);
                $this->db->delete('hms_eye_std_prescription_files');
    		    foreach($eye_file_upload as $eye_file)
    		    { 
    		        	$arr = array(
    		                      'booking_id'=> $post['booking_id'],
    		                      'pres_id'=> $prescriptionid,
    		                      'file_name'=> $eye_file['file_name'],
    		                      'orig_name'=> $eye_file['orig_name'],
    		                      'created_date'=>date('Y-m-d H:i:s')
    		                    );
    		       $this->db->insert('hms_eye_std_prescription_files',$arr);   
    		       //echo $this->db->last_query();//die();
    		    }
    		}
    		//die; 
    		$this->session->unset_userdata('eye_file_upload'); 

		    // End drawing
		    return 1;
	}

	public function get_new_data_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
			$this->db->select('hms_opd_booking.booking_code, hms_opd_booking.branch_id, hms_opd_booking.patient_id, hms_opd_booking.id, hms_opd_booking.booking_date, hms_opd_booking.dilate_time, hms_opd_booking.dilate_status, hms_patient.simulation_id, hms_opd_booking.referral_doctor, hms_opd_booking.attended_doctor,hms_opd_booking.dilate_start_time,hms_opd_booking.app_type,hms_opd_booking.cyclo_start_time,hms_opd_booking.cyclo_time,hms_opd_booking.cyclo_status');		
			$this->db->from('hms_opd_booking');
			$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
			$this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 
			$this->db->where('hms_opd_booking.id',$id);
			$query = $this->db->get(); 
			return $query->row_array();
	}
	public function get_prescription_by_id($booking_id='', $presc_id='')
	{
		$this->db->select('*');
		$this->db->where('id',$presc_id);
		$this->db->where('booking_id',$booking_id);
		$this->db->from('hms_std_eye_prescription');
		$result=$this->db->get()->row_array();
		return $result;
	}
	public function get_prescription_new_by_id($booking_id='', $presc_id='')
	{
		$this->db->select('*');
		$this->db->where('pres_id',$presc_id);
		$this->db->where('booking_id',$booking_id);
		$this->db->from('hms_std_eye_prescription_history');
		$result=$this->db->get()->row_array();
		return $result;

	}
	public function get_prescription_refraction_new_by_id($booking_id='', $presc_id='')
	{
		$this->db->select('*');
		$this->db->where('pres_id',$presc_id);
		$this->db->where('booking_id',$booking_id);
		$this->db->from('hms_std_eye_prescription_refraction');
		$result=$this->db->get()->row_array();
		return $result;
	}
	public function get_blank_refraction_id($presc_id)
	{
		$this->db->select('*');
		$this->db->where('pres_id',$presc_id);
		$this->db->from('hms_std_eye_prescription_refraction');
		$result=$this->db->get()->row_array();
		return $result;
	}
	public function get_blank_examination_id($presc_id)
	{
		$this->db->select('*');
		$this->db->where('pres_id',$presc_id);
		$this->db->from('hms_std_eye_prescription_examination');
		$result=$this->db->get()->row_array();
		return $result;
	}
	public function get_prescription_examination_id($booking_id='', $presc_id='')
	{
		$this->db->select('*');
		$this->db->where('pres_id',$presc_id);
		$this->db->where('booking_id',$booking_id);
		$this->db->from('hms_std_eye_prescription_examination');
		$result=$this->db->get()->row_array();
		return $result;
	}


	public function get_investigation_by_id($booking_id='',$presc_id='')
	{
		$this->db->select('*');
		$this->db->where('pres_id',$presc_id);
		$this->db->where('booking_id',$booking_id);
		$this->db->from('hms_std_eye_prescription_investigation');
		$result=$this->db->get()->row_array();
		return $result;
	}

	public function get_advice_by_id($booking_id='',$presc_id='')
	{
		$this->db->select('*');
		$this->db->where('pres_id',$presc_id);
		$this->db->where('booking_id',$booking_id);
		$this->db->from('hms_std_eye_prescription_advice');
		$result=$this->db->get()->row_array();
		return $result;
	}

	 public function get_diagnosis_data_by_id($booking_id,$presc_id)
   {
	    $this->db->select('*');
	   	$this->db->where('booking_id',$booking_id);
	   	$this->db->where('pres_id',$presc_id);
	    $this->db->from('hms_std_eye_prescription_diagnosis_hierarchy');
	    $query=$this->db->get();
	    return $query->row_array();
    }

  public function medicine_list_search()
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$post = $this->input->post();
		$this->db->select('hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, (SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id) as stock_quantit');  
		$this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.medicine_id = hms_medicine_entry.id','left');
		$this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
		$this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
		if(!empty($post['medicine_name']))
		{			
			$this->db->where('hms_medicine_entry.medicine_name LIKE "'.$post['medicine_name'].'%"');			
		}
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')');
		$this->db->where('hms_medicine_entry.is_deleted','0'); 
		$this->db->group_by('hms_medicine_entry.id');
		$this->db->from('hms_medicine_entry');
        $query = $this->db->get(); 
       // echo $this->db->last_query();die();
       return  $query->result();
    }

	public function referal_doctor_list($branch_id='', $name='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('id, doctor_name, doctor_code');   
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
		$this->db->where('hms_doctors.doctor_name LIKE "%'.$name.'%"');
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		return $result; 
    }
    
    public function attended_doctor_list($branch_id='', $name='')
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('id, doctor_name, doctor_code');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (1,2)'); 
		if(!empty($branch_id))
		{
			$this->db->where('hms_doctors.branch_id',$branch_id);
		}
		else
		{
			$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		}
		$this->db->where('hms_doctors.doctor_name LIKE "%'.$name.'%"');
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		return $result; 
    }


    public function get_doctor_list_both($branch_id="")
    {
        $this->db->select('hms_doctors.id, doctor_name');
        $this->db->from('hms_doctors');
        $this->db->where('branch_id',$branch_id);  
        $this->db->where('hms_doctors.is_deleted',0);
        $this->db->where('hms_doctors.doctor_type IN (1,2)');
        $res= $this->db->get()->result();
        return $res;
    }

   public function save_medicine_freqdata()
   {
	   	$post=$this->input->post();	
	   	if(!empty($post['tmp_pres_id']) || $post['tmp_pres_id'] !='')
   	 	{
   	 		$this->db->where('branch_id',$post['tmp_branch_id']);
   	 		$this->db->where('booking_id',$post['tmp_booking_id']);
   	 		$this->db->where('patient_id',$post['tmp_patient_id']);
   	 		$this->db->where('pres_id',$post['tmp_pres_id']);
   	 		$this->db->where('med_id',$post['tp_data'][0]['med_id']);
   	 		$this->db->delete('hms_std_eye_advs_temp');
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
	   	 	$temp_data=array('branch_id'=>$post['tmp_branch_id'], 'booking_id'=>$post['tmp_booking_id'],'patient_id'=>$post['tmp_patient_id'], 'pres_id'=>$post['tmp_pres_id'], 'med_id'=>$post['tp_data'][0]['med_id'], 'row'=>$data['sn'], 'day'=>$day,'st_date'=>$data['st_date'],'en_date'=>$data['en_date'],'st_time'=>$data['st_time'],'en_time'=>$data['en_time'],'freq'=>$data['freq'],'intvl'=>$data['intvl']);
	   	 	$this->db->insert('hms_std_eye_advs_temp',$temp_data);
	   }
  	return 1;
   }



   public function update_medicine_freqdata($id='', $branch_id='', $patient_id='',$book_id='',$prs_id='')
   {
	  	$this->db->set('pres_id',$prs_id);
        $this->db->where('patient_id',$patient_id);
        $this->db->where('branch_id',$branch_id);
        $this->db->where('booking_id',$book_id);
        $this->db->where('med_id',$id);
        $this->db->where('pres_id',0);
        $this->db->update('hms_std_eye_advs_temp');
      return 1;
   }

   public function save_advice_data()
   {
   		$users_data = $this->session->userdata('auth_users'); 
		$post=$this->input->post();
	 	$temp_data=array('branch_id'=>$users_data['parent_id'], 'set_name'=>$post['ad_set_name'], 'set_data'=>$post['ad_set_txt'], 'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$users_data['id'], 'created_date'=>date('Y-m-d H:i:s'));
	 	if(!empty($post['set_id']) && $post['set_id'] !='0')
	 	{
	 		$this->db->where('id',$post['set_id']);
	 		$this->db->update('hms_std_eye_advice_set',$temp_data);
	 	}
	 	else{
	 		$this->db->insert('hms_std_eye_advice_set',$temp_data);
	 	}
	 	
		return 1;
   }

   public function get_advice_sets($branch_id='', $id='')
   {
   		$this->db->select('id, set_name, set_data');
        $this->db->from('hms_std_eye_advice_set');
        $this->db->where('branch_id',$branch_id);
        if(!empty($id))
        {
        	$this->db->where('id',$id);
        }
        $res = $this->db->get();
        return $res->result_array();
   }

    /* Diagnosis */
   public function  get_commonly_icd($id="")
   {
   		$this->db->select('hms_icd10.icd_id, hms_icd10.descriptions');
        $this->db->from('hms_icd10');
        $this->db->where('hms_icd10.id',$id);
        $res= $this->db->get()->row_array();
        return $res;
   }
   public function  get_custom_icd($id="")
   {
   		$this->db->select('hms_custom_icds.id,hms_custom_icds.custom_type,hms_custom_icds.new_icd, hms_custom_icds.attached_icd,hms_custom_icds.eye_type');
        $this->db->from('hms_custom_icds');
        $this->db->where('hms_custom_icds.id',$id);
        $res= $this->db->get()->row_array();
        return $res;
   }
    /* diagnosis */


   /* Diagnosis */
   public function save_commonly_custom()
   {
   	$user_data = $this->session->userdata('auth_users'); 

   	$post=$this->input->post();
   	$data_diagnosis_hierarchy=array('branch_id'=>$post['diagno_branch_id'],'patient_id'=>$post['diagno_patient_id'], 'booking_id'=>$post['diagno_booking_id'] ,'icd_code'=>$post['icd_code'],'icd_name'=>$post['icd_name'], 'eye_type'=>$post['eye_side'],'eye_side_name'=>$post['eye_side_name'],'diagnosis_comment'=>$post['diagnosis_comment'],'user_comment'=>$post['user_comment'],'entered_by'=>$post['entered_by'],'data_type'=>$post['data_type'],'is_code'=>$post['is_code'], 'status'=>1, 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'created_by'=>$user_data['id'], 'created_date'=>date('Y-m-d H:i:s'));

   	if($post['data_id']!="")
   	{
   		$this->db->update('hms_std_eye_prescription_diagnosis_data',$data_diagnosis_hierarchy,array('id'=>$post['data_id']));
   		//echo $this->db->last_query();die;
   	}
	else{
		$this->db->insert('hms_std_eye_prescription_diagnosis_data',$data_diagnosis_hierarchy);
	}
	
     //echo $this->db->last_query();die;
   }



    public function get_diagnosis_list($booking_id='', $branch_id='', $patient_id='')
   {
   	$this->db->select('*');
   	$this->db->where('booking_id',$booking_id);
   	$this->db->where('branch_id',$branch_id);
    $this->db->from('hms_std_eye_prescription_diagnosis_data');
    $query=$this->db->get();
    return $query->result();

   }
   public function get_diagnosis_by_id($id)
   {
   	$this->db->select('*');
   	$this->db->where('id',$id);
    $this->db->from('hms_std_eye_prescription_diagnosis_data');
    $query=$this->db->get();
    return $query->row_array();

   }

   public function icd_diagnosis_list($keyword="")
 {
 	$min=5;
 	$max=7;
   $this->db->select('*');
   $this->db->where('chapter',7); 
  $where="CHAR_LENGTH(icd_id)< $max  AND CHAR_LENGTH(icd_id) > $min";
   $this->db->where($where);
   if(!empty($keyword))
   {
   	  $this->db->like('descriptions',$keyword);
   	  $this->db->or_like('icd_id',$keyword);
   }
   $this->db->from('hms_icd10'); 
   $query=$this->db->get(); 
   return $query->result_array(); 
 }


 public function icd_custom_diagnosis_list($keyword="")
 {
 	$user_data=$this->session->userdata('auth_users');
 	$this->db->select('*');
 	$this->db->where('branch_id',$user_data['parent_id']); 
 	if(!empty($keyword)){
   //	$where= 'JSON_EXTRACT(new_icd, "$.icd_name") = "'.$keyword.'"';
   	// $this->db->where('('.$where.')');
 		$where= 'JSON_EXTRACT(new_icd, "$.icd_name") ';
 		$where1= 'JSON_EXTRACT(attached_icd, "$.attach_icd_name") ';
 		$this->db->like($where,$keyword);
 		$this->db->or_like($where1,$keyword);
 	}
 	$this->db->from('hms_custom_icds'); 
 	$query=$this->db->get(); 
 	return $query->result_array(); 
 }
      /* diagnosis */

  // procedure Search //

      public function procedure_test_list($keyword)
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('id,test_name,test_code');  
        $this->db->where('dept_id','28'); 
        //$this->db->where('test_head_id',$test_head_id); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->like('test_name',$keyword); 
        $query = $this->db->get('path_test');
        $result = $query->result(); 
        return $result; 
    }
  // Procedure Search //  

     // procedure Search //

    public function delete_eye_prescription($pres_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('id',$pres_id); 
     	$this->db->delete('hms_std_eye_prescription'); 

       $this->db->where('pres_id',$pres_id);
	   $this->db->where('branch_id',$users_data['parent_id']);
	   $this->db->delete('hms_std_eye_prescription_history');

	   $this->db->where('pres_id',$pres_id);
	   $this->db->where('branch_id',$users_data['parent_id']);
	   $this->db->delete('hms_std_eye_prescription_refraction');

	   $this->db->where('pres_id',$pres_id);
	   $this->db->where('branch_id',$users_data['parent_id']);
	   $this->db->delete('hms_std_eye_prescription_examination');

	   $this->db->where('pres_id',$pres_id);
	   $this->db->where('branch_id',$users_data['parent_id']);
	   $this->db->delete('hms_std_eye_prescription_investigation');

	   $this->db->where('pres_id',$pres_id);
	   $this->db->where('branch_id',$users_data['parent_id']);
	   $this->db->delete('hms_std_eye_prescription_diagnosis_hierarchy');

	   $this->db->where('pres_id',$pres_id);
	   $this->db->where('branch_id',$users_data['parent_id']);
	   $this->db->delete('hms_std_eye_prescription_advice');
	   return 1;	

    }
  // Procedure Search //  


   public function get_medicine_sets($branch_id='')
   {
   	$this->db->select('id,set_name');
   	$this->db->where('branch_id',$branch_id);
    $this->db->from('hms_std_medicine_sets');
    $query=$this->db->get();
    return $query->result_array();
   }

   public function get_medicine_set_tapperdata($mid='', $branch_id='', $pres_id='')
   {
        $this->db->select('*');
        $this->db->from('hms_std_eye_advs_temp');
        $this->db->where('branch_id',$branch_id);
        $this->db->where('med_id',$mid);
        if(!empty($pres_id) && $pres_id !='')
        {
            $this->db->where('pres_id',$pres_id);
        }
        $this->db->order_by('id','ASC');
        $res= $this->db->get();
        return $res->result_array();
   }
   
   public function get_manu_expiry_date($mid='', $branch_id='')
   {
   	 $this->db->select('hms_medicine_stock.bar_code,hms_medicine_stock.hsn_no as hsn,hms_medicine_stock.mrp, hms_medicine_stock.conversion,hms_medicine_stock.batch_no,hms_medicine_stock.expiry_date,hms_medicine_stock.manuf_date,hms_medicine_stock.discount,hms_medicine_stock.sgst,hms_medicine_stock.cgst,hms_medicine_stock.igst');   
    $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id AND (hms_medicine_stock.type=1 OR hms_medicine_stock.type=6 OR hms_medicine_stock.type=5 OR hms_medicine_stock.type=8) AND hms_medicine_stock.is_deleted=0');    
     $this->db->where('hms_medicine_stock.m_id',$mid);  
    $this->db->where('hms_medicine_entry.is_deleted','0');  
    $this->db->where('hms_medicine_entry.branch_id  IN ('.$branch_id.')'); 
    $this->db->group_by('hms_medicine_entry.id, hms_medicine_stock.batch_no');  
    $query = $this->db->get('hms_medicine_stock');
    return $query->row();
   }


   public function add_next_appointment($branch_id='', $patient_id='', $booking_id='', $date='', $time='')
   {
   	       $booking_date = date("Y-m-d", strtotime($date)); 
   	       $this->load->model('opd/opd_model','opd');
   	 		 if(!empty($date))
            {
            	$this->db->set('next_app_date',$booking_date);
            	$this->db->where('id',$booking_id);
            	$this->db->update('hms_opd_booking');



            	$this->db->where('parent_id',$booking_id);
	            $this->db->where('appointment_type','1');
	            $this->db->where('patient_id',$patient_id);
	            $this->db->delete('hms_opd_booking');
	          
            	$booking_code = generate_unique_id(9);
				$opd_dtls=$this->opd->get_by_id($booking_id);


			$validity=0;
			$validity_days=$this->opd->get_opd_validity_days();
			if(!empty($validity_days['days']))
			{
			$validity=$validity_days['days']-1;
			$validate_date =  date('Y-m-d', strtotime($booking_date. ' + '.$validity.' days'));
			}
			else
			{
			$validate_date=$booking_date;
			}

				$token_no=$this->generate_token($branch_id,$opd_dtls['attended_doctor'],$opd_dtls['specialization_id'],$booking_date);
            	$appointment_data = array(	
            				'branch_id'=>$branch_id,
							'parent_id'=>$booking_id,
							'patient_id'=>$patient_id,
							'appointment_type'=>1, 
            				'referral_doctor'=>$opd_dtls['referral_doctor'], 
							'ref_by_other'=>$opd_dtls['ref_by_other'],
							'specialization_id'=>$opd_dtls['specialization_id'],
							'attended_doctor'=>$opd_dtls['attended_doctor'],
							'type'=>$opd_dtls['type'],
							'consultants_charge'=>$opd_dtls['consultants_charge'],
							'net_amount'=>$opd_dtls['net_amount'],
							'booking_date'=>$booking_date,
							'validity_date'=>$validate_date,
							'booking_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$time)),
							'token_no'=>$token_no,
							'confirm_date'=>date('Y-m-d H:i:s'),
							'booking_type'=>$opd_dtls['booking_type'],  // eye module add
							'booking_status'=>1,
							'app_type'=>1,
							'confirm_date'=>date('Y-m-d H:i:s'),
							'booking_code'=>$booking_code,
							'created_by'=>$opd_dtls['created_by'],
							'created_date'=>date('Y-m-d H:i:s')
					);
				$this->db->insert('hms_opd_booking',$appointment_data);    
	            $appointment_id = $this->db->insert_id(); 
            }
   }

    public function generate_token($branch_id='',$doctor_id='',$specilization_id='',$booking_date='')
    {
       $this->load->model('opd/opd_model','opd');
       $token_no='';
       if(!empty($booking_date) && !empty($doctor_id))
       { 
         $token_type=$this->opd->get_token_setting();
         $token_type=$token_type['type'];
          if($token_type=='0') //hospital wise token no
          {             
           $patient_token_details_for_hospital=$this->opd->get_patient_token_details_for_type_hospital($booking_date,$token_type);
          if($patient_token_details_for_hospital['token_no']>'0')
          {
            $token_no=$patient_token_details_for_hospital['token_no']+1;
          }
          else
          {
            $token_no='1';
          } 
             
         }
         elseif($token_type=='1') // doctor wise token no
         {
            $patient_token_details_for_doctor=$this->opd->get_patient_token_details_for_type_doctor($doctor_id,$booking_date,$token_type);
          if($patient_token_details_for_doctor['token_no']>'0')
          {
            $token_no=$patient_token_details_for_doctor['token_no']+1;
          }
          else
          {
            $token_no='1';
          }

         }
        elseif($token_type=='2') // specialization wise token no
         {
            $patient_token_details_for_specialization=$this->opd->get_patient_token_details_for_type_specialization($specilization_id,$booking_date,$token_type);
          if($patient_token_details_for_specialization['token_no']>'0')
          {
            $token_no=$patient_token_details_for_specialization['token_no']+1;
          }
          else
          {
            $token_no='1';
          }

         }
         return $token_no;
       }
    }

    public function prescription_html_template($part="",$branch_id="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_std_eye_report_print_setting.*');
      if(!empty($branch_id))
      {
          $this->db->where('hms_std_eye_report_print_setting.branch_id',$branch_id);
      }
      else
      {
           $this->db->where('hms_std_eye_report_print_setting.branch_id',$users_data['parent_id']);
      } 
        $this->db->from('hms_std_eye_report_print_setting'); 
      $result=$this->db->get()->row(); 
      return $result;
    }


    public function update_appointment_type($booking_id='',$status='')
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->set('app_type',$status);
		$this->db->where('id',$booking_id); 
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->update('hms_opd_booking');
    	return 1;
    }


    public function get_prescription_biometry_id($booking_id='', $presc_id='')
	{
		$this->db->select('*');
		$this->db->where('pres_id',$presc_id);
		$this->db->where('booking_id',$booking_id);
		$this->db->from('hms_std_eye_prescription_biometry');
		$result=$this->db->get()->row_array();
		return $result;
	}

	public function get_by_upload_files($booking_id, $prescription_id)
    {
        $this->db->select("hms_eye_std_prescription_files.*"); 
		$this->db->where('hms_eye_std_prescription_files.booking_id', $booking_id);  
		$this->db->where('hms_eye_std_prescription_files.pres_id', $prescription_id);  
		$this->db->from('hms_eye_std_prescription_files'); 
		$result = $this->db->get()->result_array();
		//print_r($result);die;
		return $result;
    }

// Please code above this	
//(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_sale_to_medicine.batch_no = batch_no) as stock_quantit

}
?>