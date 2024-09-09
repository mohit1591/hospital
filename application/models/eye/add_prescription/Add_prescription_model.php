<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Add_prescription_model extends CI_Model 
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
					"date_time_new" =>$post['date_time_new'],
					"next_reasons"=>$post['next_reasons'],
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
				/* cheif compalin  */

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

					//echo $this->db->last_query();die;
			//$test_data_id = $this->db->insert_id(); 
			}
			}
			
		   /* diagnosis data */


			//echo $this->db->last_query(); exit;
			$this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_eye_prescription_patient_test'); 

			//$total_test = count($post['test_name']);
			$total_test = count(array_filter($post['test_name'])); 
			//foreach ($post['test_name'] as $value) 
			//{
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
						"prescription_id"=>$post['data_id'],
						"test_name"=>$post['test_name'][$i]);
						$this->db->insert('hms_eye_prescription_patient_test',$test_data); 
						$test_data_id = $this->db->insert_id(); 
			}

			$this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_eye_prescription_patient_pres'); 

			//$total_prescription = count($post['medicine_name']);
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
						//$this->db->from('hms_eye_medicine_dosage');
						$this->db->where('hms_eye_medicine_dosage.dosage',$new_pres_array[$i]['medicine_dose']); 
						$this->db->where('hms_eye_medicine_dosage.is_deleted!=2'); 
						$this->db->where('hms_eye_medicine_dosage.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_eye_medicine_dosage');
						//echo  $this->db->last_query();
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



		 // 	if(!empty($post['data']))
			// {  

   //                      $this->db->where('booking_id',$prescriptionid);
			// $this->db->where('branch_id',$user_data['parent_id']);
   //            	        $this->db->delete('hms_branch_vitals');
  
			// 	$current_date = date('Y-m-d H:i:s');
	  //           foreach($post['data'] as $key=>$val)
	  //           {
	            	
 	 //            	$data = array(
	  //                              "branch_id"=>$user_data['parent_id'],
	  //                              "type"=>2,
	  //                              "booking_id"=>$prescriptionid,
	  //                              "vitals_id"=>$key,
	  //                              "vitals_value"=>$val['name'],
	                               
	  //                             );
	              
	  //             $this->db->insert('hms_branch_vitals',$data);
	  //     		  $id = $this->db->insert_id();
	  //           } 
			// }


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
		$this->db->select("hms_opd_booking.opd_type,hms_eye_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_eye_prescription.patient_bp as patientbp,hms_eye_prescription.patient_temp as patienttemp,hms_eye_prescription.patient_weight as patientweight,hms_eye_prescription.patient_height as patientpr,hms_eye_prescription.patient_spo2 as patientspo,hms_eye_prescription.patient_rbs as patientrbs"); 



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
		$this->db->select("hms_eye_prescription.*,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time");  //,hms_opd_prescription_patient_test.*,hms_opd_prescription_patient_pres.*
		$this->db->from('hms_eye_prescription'); 
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_eye_prescription.booking_id','left');
		$this->db->where('hms_eye_prescription.id',$id);
		$this->db->where('hms_eye_prescription.is_deleted','0'); 
		$query = $this->db->get(); 
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


// Please code above this	
}
?>