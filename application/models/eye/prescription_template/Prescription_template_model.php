<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Prescription_template_model extends CI_Model 
{
	var $table = 'hms_eye_prescription_template';
	var $column = array('hms_eye_prescription_template.id','hms_eye_prescription_template.template_name','hms_eye_prescription_template.status', 'hms_eye_prescription_template.created_date', 'hms_eye_prescription_template.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_eye_prescription_template.*");
		$this->db->where('hms_eye_prescription_template.is_deleted','0'); 
		$this->db->where('hms_eye_prescription_template.branch_id = "'.$user_data['parent_id'].'"');

		

		/////// Search query end //////////////
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d h:i:s',strtotime($search['start_date']));
				$this->db->where('created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d h:i:s',strtotime($search['end_date']));
				$this->db->where('created_date >= "'.$end_date.'"');
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('status',$search['status']);
			}


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
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	
	public function get_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_eye_prescription_template.*');
		$this->db->from('hms_eye_prescription_template'); 
		$this->db->where('hms_eye_prescription_template.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_eye_prescription_template.id',$id);
		$this->db->where('hms_eye_prescription_template.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save($filename="")
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
	
		
		
		$data = array(     
							'branch_id'=>$user_data['parent_id'],
							"template_name"=>$post['template_name'],
							
							/*"patient_bp"=>$post['patient_bp'],
							"patient_temp"=>$post['patient_temp'],
							"patient_weight"=>$post['patient_weight'],
							"patient_height"=>$post['patient_height'],
							"patient_spo2"=>$post['patient_spo2'],*/

							"prv_history"=>$post['prv_history'],
							"personal_history"=>$post['personal_history'],
							//"chief_complaints"=>$post['chief_complaints'],
							"examination"=>$post['examination'],
							//"diagnosis"=>$post['diagnosis'],
							"suggestion"=>$post['suggestion'],
							"remark"=>$post['remark'],
							"appointment_date"=>date('Y-m-d',strtotime($post['next_appointment_date'])),
							"status"=>1
				         ); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			//template

			$total_test = count($post['test_name']);
			if($total_test > 0)
			{
				$this->db->where('template_id',$post['data_id']);
    			$this->db->delete('hms_eye_prescription_patient_test_template');
			}
			foreach ($post['test_name'] as $value) 
			{
					$test_data = array(
						"template_id"=>$post['data_id'],
						"test_name"=>$value);
					$this->db->insert('hms_eye_prescription_patient_test_template',$test_data); 
					$test_data_id = $this->db->insert_id(); 
			}

			
			if(!empty($post['cheif_complains']))
				{
				$cheif_complains_values= array_values($post['cheif_complains']);
				$this->db->where(array('eye_prescription_template_id'=>$post['data_id']));
				$this->db->delete('hms_eye_cheif_complain_template_vals'); 
				$total_cheif_complain_name = count($cheif_complains_values); 

				for($i=0;$i<=$total_cheif_complain_name-1;$i++)
				{
					if(isset($cheif_complains_values[$i]['cheif_complain_name']))
					{
						$cheif_complain_name=$cheif_complains_values[$i]['cheif_complain_name'];
					}
					else
					{
						$cheif_complain_name='';
					}
					if(isset($cheif_complains_values[$i]['cheif_c_right']))
					{
						$cheif_c_right=$cheif_complains_values[$i]['cheif_c_right'];
					}
					else
					{
						$cheif_c_right='';
					}
					if(isset($cheif_complains_values[$i]['cheif_c_left']))
					{
						$cheif_c_left=$cheif_complains_values[$i]['cheif_c_left'];
					}
					else
					{
					$cheif_c_left='';	
					}
					if(isset($cheif_complains_values[$i]['cheif_c_days']))
					{
						$cheif_c_days=$cheif_complains_values[$i]['cheif_c_days'];
					}
					else
					{
						$cheif_c_days='';
					}
					if(isset($cheif_complains_values[$i]['cheif_c_time']))
					{
						$cheif_c_time=$cheif_complains_values[$i]['cheif_c_time'];
					}
					else
					{
						$cheif_c_time='';
					}
					$prescription_data_cheif = array(
												'branch_id'=>$user_data['parent_id'],
												"eye_prescription_template_id"=>$post['data_id'],
												"cheif_complains"=>$cheif_complain_name,
												"right_eye"=>$cheif_c_right,
												"left_eye"=>$cheif_c_left,
												"days"=>$cheif_c_days,
												"time"=>$cheif_c_time);
					//print '<pre>'; print_r($prescription_data_cheif);
					$this->db->insert('hms_eye_cheif_complain_template_vals',$prescription_data_cheif); 
					//$test_data_id = $this->db->insert_id(); 
				}//die;

				}
			
			/* add complaints values */
			
			$this->db->where(array('eye_prescription_template_id'=>$post['data_id']));
			$this->db->delete('hms_eye_diagnosis_template_vals'); 
		  if(!empty($post['diagnosis']))
			{
				$diagnosis_values= array_values($post['diagnosis']);
				$total_diagnosis = count($post['diagnosis']); 
				for($i=0;$i<=$total_diagnosis-1;$i++)
				{

					if(isset($diagnosis_values[$i]['diagnosis_name']))
					{
					$diagnosis=$diagnosis_values[$i]['diagnosis_name'];
					}
					else
					{
					$diagnosis='';
					}


					if(isset($diagnosis_values[$i]['diagnosis_right']))
					{
					$diagnosis_right=$diagnosis_values[$i]['diagnosis_right'];
					}
					else
					{
					$diagnosis_right='';
					}


					if(isset($diagnosis_values[$i]['diagnosis_left']))
					{
					$diagnosis_left=$diagnosis_values[$i]['diagnosis_left'];
					}
					else
					{
					$diagnosis_left='';
					}


					if(isset($diagnosis_values[$i]['diagnosis_days']))
					{
					$diagnosis_days=$diagnosis_values[$i]['diagnosis_days'];
					}
					else
					{
					$diagnosis_days='';
					}


					if(isset($diagnosis_values[$i]['diagnosis_time']))
					{
					$diagnosis_time=$diagnosis_values[$i]['diagnosis_time'];
					}
					else
					{
						$diagnosis_time='';
					}


					$prescription_data_diagnosis = array(
													'branch_id'=>$user_data['parent_id'],
													"eye_prescription_template_id"=>$post['data_id'],
													"diagnosis"=>$diagnosis,
													"right_eye"=>$diagnosis_right,
													"left_eye"=>$diagnosis_left,
													"days"=>$diagnosis_days,
													"time"=>$diagnosis_time);
					$this->db->insert('hms_eye_diagnosis_template_vals',$prescription_data_diagnosis); 

					//echo $this->db->last_query();die;
			//$test_data_id = $this->db->insert_id(); 
			}
			}
			/* add diagnosis values */



			/* add systemic illness values */
			$this->db->where(array('eye_prescription_template_id'=>$post['data_id']));
			$this->db->delete('hms_eye_systemic_illness_template_vals'); 
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
													"eye_prescription_template_id"=>$post['data_id'],
													"systemic_illness"=>$systemic_illness[$i],
													"days"=>$systemic_illness_days[$i],
													"time"=>$systemic_illness_time[$i]);


					$this->db->insert('hms_eye_systemic_illness_template_vals',$prescription_data_systemic_illness); 
			//$test_data_id = $this->db->insert_id(); 
			}
			}
		

			/* add systemic illness values */


			//print '<pre>'; print_r($post['prescription']);die;
		    
			if(isset($post['prescription']) && !empty($post['prescription']))
			{
				    $new_pres_val=array_values($post['prescription']);
					$total_prescription = count($new_pres_val);
					if($total_prescription > 0)
					{
					$this->db->where('template_id',$post['data_id']);
					$this->db->delete('hms_eye_prescription_patient_pres_template');
					}
				
				
				for($i=0;$i<=$total_prescription-1;$i++)
				{
						if(isset($new_pres_val[$i]['medicine_left_eye']))
					   {
					   	$medicine_left_eye=$new_pres_val[$i]['medicine_left_eye'];
					   }
					   else
					   {
					   	$medicine_left_eye='';
					   }
					   if(isset($new_pres_val[$i]['medicine_right_eye']))
					   {
					   	$medicine_right_eye=$new_pres_val[$i]['medicine_right_eye'];
					   }
					   else
					   {
					   	$medicine_right_eye='';
					   }
					   if(isset($new_pres_val[$i]['medicine_company']))
					   {
					   	$medicine_company=$new_pres_val[$i]['medicine_company'];
					   }
					   else
					   {
					   	$medicine_company='';
					   }
					   if(isset($new_pres_val[$i]['medicine_salt']))
					   {
					   	$medicine_salt=$new_pres_val[$i]['medicine_salt'];
					   }
					   else
					   {
					   	$medicine_salt='';
					   }
					   if(isset($new_pres_val[$i]['medicine_type']))
					   {
					   	$medicine_type=$new_pres_val[$i]['medicine_type'];
					   }
					   else
					   {
					   	$medicine_type='';
					   }
					   if(isset($new_pres_val[$i]['medicine_dose']))
					   {
					   	$medicine_dose=$new_pres_val[$i]['medicine_dose'];
					   }
					   else
					   {
					   	$medicine_dose='';
					   }
					   if(isset($new_pres_val[$i]['medicine_duration']))
					   {
					   	$medicine_duration=$new_pres_val[$i]['medicine_duration'];
					   }
					   else
					   {
					   	$medicine_duration='';
					   }
					   if(isset($new_pres_val[$i]['medicine_frequency']))
					   {
					   	$medicine_frequency=$new_pres_val[$i]['medicine_frequency'];
					   }
					   else
					   {
					   	$medicine_frequency='';
					   }
					   if(isset($new_pres_val[$i]['medicine_advice']))
					   {
					   	$medicine_advice=$new_pres_val[$i]['medicine_advice'];
					   }
					   else
					   {
					   	$medicine_advice='';
					   }
					   $prescription_data = array(
						"template_id"=>$post['data_id'],
						"medicine_name"=>$new_pres_val[$i]['medicine_name'],
						"medicine_brand"=>$medicine_company,
						"medicine_salt"=>$medicine_salt,
						"medicine_type"=>$medicine_type,
						"medicine_dose"=>$medicine_dose,
						"medicine_duration"=>$medicine_duration,
						"medicine_frequency"=>$medicine_frequency,
						"left_eye"=>$medicine_left_eye,
						"right_eye"=>$medicine_right_eye,
						"medicine_advice"=>$medicine_advice
						);

					//print '<pre>'; print_r($prescription_data);
					$this->db->insert('hms_eye_prescription_patient_pres_template',$prescription_data); 
					$test_data_id = $this->db->insert_id(); 
				}
				//die;

			}

			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
                        $this->db->where('id',$post['data_id']);
			$this->db->update('hms_eye_prescription_template',$data); 
//echo $this->db->last_query(); exit;
		}
		else
		{    
          
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_eye_prescription_template',$data);
			
			$data_id = $this->db->insert_id(); 

			/* add complaints values */
			
				if(!empty($post['cheif_complains']))
				{
				$cheif_complains_values= array_values($post['cheif_complains']);
				
				$total_cheif_complain_name = count($cheif_complains_values); 
				for($i=0;$i<=$total_cheif_complain_name-1;$i++)
				{
					if(isset($cheif_complains_values[$i]['cheif_complain_name']))
					{
						$cheif_complain_name=$cheif_complains_values[$i]['cheif_complain_name'];
					}
					else
					{
						$cheif_complain_name='';
					}
					if(isset($cheif_complains_values[$i]['cheif_c_right']))
					{
						$cheif_c_right=$cheif_complains_values[$i]['cheif_c_right'];
					}
					else
					{
						$cheif_c_right='';
					}
					if(isset($cheif_complains_values[$i]['cheif_c_left']))
					{
						$cheif_c_left=$cheif_complains_values[$i]['cheif_c_left'];
					}
					else
					{
					$cheif_c_left='';	
					}
					if(isset($cheif_complains_values[$i]['cheif_c_days']))
					{
						$cheif_c_days=$cheif_complains_values[$i]['cheif_c_days'];
					}
					else
					{
						$cheif_c_days='';
					}
					if(isset($cheif_complains_values[$i]['cheif_c_time']))
					{
						$cheif_c_time=$cheif_complains_values[$i]['cheif_c_time'];
					}
					else
					{
						$cheif_c_time='';
					}
					$prescription_data_cheif = array(
												"eye_prescription_template_id"=>$data_id,
												"cheif_complains"=>$cheif_complain_name,
												"right_eye"=>$cheif_c_right,
												"left_eye"=>$cheif_c_left,
												"days"=>$cheif_c_days,
												"time"=>$cheif_c_time);
					//print '<pre>'; print_r($prescription_data_cheif);
					$this->db->insert('hms_eye_cheif_complain_template_vals',$prescription_data_cheif); 
					//$test_data_id = $this->db->insert_id(); 
				}//die;

				}
			
			/* add complaints values */

   					
				/* add diagnosis values */
			if(!empty($post['diagnosis']))
			{
				$diagnosis_values= array_values($post['diagnosis']);	
				$total_diagnosis = count($diagnosis_values); 
				for($i=0;$i<=$total_diagnosis-1;$i++)
				{

					if(isset($diagnosis_values[$i]['diagnosis_name']))
					{
					$diagnosis=$diagnosis_values[$i]['diagnosis_name'];
					}
					else
					{
					$diagnosis='';
					}


					if(isset($diagnosis_values[$i]['diagnosis_right']))
					{
					$diagnosis_right=$diagnosis_values[$i]['diagnosis_right'];
					}
					else
					{
					$diagnosis_right='';
					}


					if(isset($diagnosis_values[$i]['diagnosis_left']))
					{
					$diagnosis_left=$diagnosis_values[$i]['diagnosis_left'];
					}
					else
					{
					$diagnosis_left='';
					}


					if(isset($diagnosis_values[$i]['diagnosis_days']))
					{
					$diagnosis_days=$diagnosis_values[$i]['diagnosis_days'];
					}
					else
					{
					$diagnosis_days='';
					}


					if(isset($diagnosis_values[$i]['diagnosis_time']))
					{
					$diagnosis_time=$diagnosis_values[$i]['diagnosis_time'];
					}
					else
					{
						$diagnosis_time='';
					}


					$prescription_data_diagnosis = array(
													"eye_prescription_template_id"=>$data_id,
													"diagnosis"=>$diagnosis,
													"right_eye"=>$diagnosis_right,
													"left_eye"=>$diagnosis_left,
													"days"=>$diagnosis_days,
													"time"=>$diagnosis_time);
					$this->db->insert('hms_eye_diagnosis_template_vals',$prescription_data_diagnosis); 

					//echo $this->db->last_query();die;
			//$test_data_id = $this->db->insert_id(); 
			}
			}
			/* add diagnosis values */

			/* add systemic illness values */

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
														"eye_prescription_template_id"=>$data_id,
														"systemic_illness"=>$systemic_illness[$i],
														"days"=>$systemic_illness_days[$i],
														"time"=>$systemic_illness_time[$i]);


						$this->db->insert('hms_eye_systemic_illness_template_vals',$prescription_data_systemic_illness); 
				//$test_data_id = $this->db->insert_id(); 
				}
			}
		

			/* add systemic illness values */


			$total_test = count($post['test_name']);
 
			foreach ($post['test_name'] as $value) 
			{
					$test_data = array(
						"template_id"=>$data_id,
						"test_name"=>$value);
					
					$this->db->insert('hms_eye_prescription_patient_test_template',$test_data); 
					$test_data_id = $this->db->insert_id(); 
			} 

		     
           //print '<pre>'; print_r($post['prescription']);die;
			if(isset($post['prescription']) && !empty($post['prescription']))
			{
				$new_array_pres=array_values($post['prescription']);
				$total_prescription = count($new_array_pres);
			
				for($i=0;$i<=$total_prescription-1;$i++)
				{
					
						if(isset($new_array_pres[$i]['medicine_left_eye']))
					   {
					   	$medicine_left_eye[$i]=$new_array_pres[$i]['medicine_left_eye'];
					   }
					   else
					   {
					   	$medicine_left_eye[$i]='';
					   }
					   if(isset($new_array_pres[$i]['medicine_right_eye']))
					   {
					   	$medicine_right_eye[$i]=$new_array_pres[$i]['medicine_right_eye'];
					   }
					   else
					   {
					   	$medicine_right_eye[$i]='';
					   }
					   if(!empty($new_array_pres[$i]['medicine_company']))
					   {
					  
					   	$medicine_company[$i]=$new_array_pres[$i]['medicine_company'];
					   }
					   else
					   {
					   	
					   	$medicine_company[$i]='';
					   }
					   if(isset($new_array_pres[$i]['medicine_salt']))
					   {
					   	$medicine_salt[$i]=$new_array_pres[$i]['medicine_salt'];
					   }
					   else
					   {
					   	$medicine_salt[$i]='';
					   }
					   if(isset($new_array_pres[$i]['medicine_type']))
					   {
					   	$medicine_type[$i]=$new_array_pres[$i]['medicine_type'];
					   }
					   else
					   {
					   	$medicine_type[$i]='';
					   }
					   if(isset($new_array_pres[$i]['medicine_dose']))
					   {
					   	$medicine_dose[$i]=$new_array_pres[$i]['medicine_dose'];
					   }
					   else
					   {
					   	$medicine_dose[$i]='';
					   }
					   if(isset($new_array_pres[$i]['medicine_duration']))
					   {
					   	$medicine_duration[$i]=$new_array_pres[$i]['medicine_duration'];
					   }
					   else
					   {
					   	$medicine_duration[$i]='';
					   }
					   if(isset($new_array_pres[$i]['medicine_frequency']))
					   {
					   	$medicine_frequency[$i]=$new_array_pres[$i]['medicine_frequency'];
					   }
					   else
					   {
					   	$medicine_frequency[$i]='';
					   }
					   if(isset($new_array_pres[$i]['medicine_advice']))
					   {
					   	$medicine_advice[$i]=$new_array_pres[$i]['medicine_advice'];
					   }
					   else
					   {
					   	$medicine_advice[$i]='';
					   }
					   $prescription_data = array(
						"template_id"=>$data_id,
						"medicine_name"=>$new_array_pres[$i]['medicine_name'],
						"medicine_brand"=>$medicine_company[$i],
						"medicine_salt"=>$medicine_salt[$i],
						"medicine_type"=>$medicine_type[$i],
						"medicine_dose"=>$medicine_dose[$i],
						"medicine_duration"=>$medicine_duration[$i],
						"medicine_frequency"=>$medicine_frequency[$i],
						"left_eye"=>$medicine_left_eye[$i],
						"right_eye"=>$medicine_right_eye[$i],
						"medicine_advice"=>$medicine_advice[$i]
						);

					//print '<pre>'; print_r($prescription_data);
					$this->db->insert('hms_eye_prescription_patient_pres_template',$prescription_data);
					//echo $this->db->last_query(); 
					$test_data_id = $this->db->insert_id(); 
				}
				

			}
			


			

		} 	
	}


	function get_eye_prescription_template($template_id='')
	{
		$this->db->select("hms_eye_prescription_patient_pres_template.*"); 
		$this->db->from('hms_eye_prescription_patient_pres_template'); 
		$this->db->where('hms_eye_prescription_patient_pres_template.template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}

	function get_eye_test_template($template_id='')
	{
		$this->db->select("hms_eye_prescription_patient_test_template.*"); 
		$this->db->from('hms_eye_prescription_patient_test_template'); 
		$this->db->where('hms_eye_prescription_patient_test_template.template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
		//print_r($result);die;
        return $result;
	}
	function get_eye_cheif_template($template_id='')
	{
		$this->db->select("hms_eye_cheif_complain_template_vals.*"); 
		$this->db->from('hms_eye_cheif_complain_template_vals'); 
		$this->db->where('hms_eye_cheif_complain_template_vals.eye_prescription_template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
		//print_r($result);die;
        return $result;
	}
	function get_eye_diagnosis_template($template_id='')
	{
		$this->db->select("hms_eye_diagnosis_template_vals.*"); 
		$this->db->from('hms_eye_diagnosis_template_vals'); 
		$this->db->where('hms_eye_diagnosis_template_vals.eye_prescription_template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
		//print_r($result);die;
		return $result;
	}

	function get_eye_systemic_illness_template($template_id='')
	{
		$this->db->select("hms_eye_systemic_illness_template_vals.*"); 
		$this->db->from('hms_eye_systemic_illness_template_vals'); 
		$this->db->where('hms_eye_systemic_illness_template_vals.eye_prescription_template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
		//print_r($result);die;
		return $result;
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
			$this->db->update('hms_eye_prescription_template');
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
			$this->db->update('hms_eye_prescription_template');
    	} 
    }

    public function delete_pres_medicine($medicine_presc_id='',$template_id='')
    {
    	

    	if(!empty($medicine_presc_id) && $medicine_presc_id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->where('id',$medicine_presc_id);
			$this->db->delete('hms_eye_prescription_patient_pres_template'); 
			
			//echo $this->db->last_query();die;
    	}

    	$this->db->select("*"); 
		$this->db->from('hms_eye_prescription_patient_pres_template'); 
		$this->db->where('template_id',$template_id);
		$query = $this->db->get(); 

		$result = $query->result(); 
		$temp =""; 
		if(!empty($result))
		{
		foreach ($result as $prescriptiontemplate) {
			$temp .= '<tr><td><input type="text" name="medicine_name[]" value="'.$prescriptiontemplate->medicine_name.'" class="medicine-name"></td><td><input type="text" name="medicine_type[]" value="'.$prescriptiontemplate->medicine_type.'" class="medicine-name"></td><td><input type="text" name="medicine_dose[]" class="medicine-name" value="'.$prescriptiontemplate->medicine_dose.'"></td><td><input type="text" name="medicine_duration[]" class="medicine-name" value="'.$prescriptiontemplate->medicine_duration.'"></td><td><input type="text" name="medicine_frequency[]" value="'.$prescriptiontemplate->medicine_frequency.'" class="medicine-name"></td><td><input type="text" name="medicine_advice[]" class="medicine-name" value"'.$prescriptiontemplate->medicine_advice.'"></td><td width="80"><a href="javascript:void(0);" onclick="delete_prescription_medicine('.$prescriptiontemplate->id.');" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';
		}
		}
		else
		{
			$temp .= '<tr><td colspan="7">Prescription Medicine Not Available.</td></tr>';
		}

		return $temp;

    }

    

    public function referal_doctor_list()
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
		return $result; 
    }

    public function attended_doctor_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (1,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
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

    public function save_booking()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
        $booking_code = generate_unique_id(9);
        $data_patient = array(    
									'simulation_id'=>$post['simulation_id'],
									'patient_name'=>$post['patient_name'],
									'mobile_no'=>$post['mobile_no'],
									'gender'=>$post['gender'],
									'age_y'=>$post['age_y'],
									'age_m'=>$post['age_m'],
									'age_d'=>$post['age_d'], 
									'address'=>$post['address'],
									'city_id'=>$post['city_id'],
									'state_id'=>$post['state_id'],
									'country_id'=>$post['country_id'],
									'status'=>1,
									'ip_address'=>$_SERVER['REMOTE_ADDR']
					            ); 
		$data_test = array(    
						'booking_code'=>$booking_code, 
						'dept_id'=>$post['dept_id'], 
						'referral_doctor'=>$post['referral_doctor'],
						'attended_doctor'=>$post['attended_doctor'],
						'sample_collected_by'=>$post['sample_collected_by'],
						'staff_refrenace_id'=>$post['staff_refrenace_id'],
						'booking_date'=>date('Y-m-d',strtotime($post['booking_date']))
						
		         );
         
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			$booking_id = $post['data_id'];
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$data_test);  

			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['patient_id']);
			$this->db->update('hms_patient',$data_patient); 
            
           

		}
		else
		{   
		    if(!empty($post['patient_id']) && $post['patient_id']>0)
		    {
		    	$patient_id = $post['patient_id'];
		    } 
		    else
		    {
		    	$patient_code = generate_unique_id(4);
		    	$this->db->set('patient_code',$patient_code);
		    	$this->db->insert('hms_patient',$data_patient);  
		    	$patient_id = $this->db->insert_id(); 
		    }
		    $this->db->set('patient_id',$patient_id);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_opd_booking',$data_test);    
            $booking_id = $this->db->insert_id(); 
			 
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
					'total_amount'=>$post['total_amount'],
					'discount'=>$post['discount'],
					'net_amount'=>$post['net_amount'],
					'paid_amount'=>$post['paid_amount'],
					'balance'=>$post['balance'],
					'booking_status'=>1
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$data);  
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
			$this->db->select('hms_eye_chief_complaints.chief_complaints');  
			$this->db->where('hms_eye_chief_complaints.id',$value);  
			$this->db->where('hms_eye_chief_complaints.is_deleted',0);  
			$this->db->where('hms_eye_chief_complaints.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_chief_complaints.id');  
			$query = $this->db->get('hms_eye_chief_complaints');
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
			$this->db->select('hms_eye_examination.examination');  
			$this->db->where('hms_eye_examination.id',$value);  
			$this->db->where('hms_eye_examination.is_deleted',0);  
			$this->db->where('hms_eye_examination.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_examination.id');  
			$query = $this->db->get('hms_eye_examination');
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
        $query = $this->db->get('hms_eye_examination');
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
			$this->db->select('hms_eye_diagnosis.diagnosis');  
			$this->db->where('hms_eye_diagnosis.id',$value);  
			$this->db->where('hms_eye_diagnosis.is_deleted',0);  
			$this->db->where('hms_eye_diagnosis.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_diagnosis.id');  
			$query = $this->db->get('hms_eye_diagnosis');
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
        $query = $this->db->get('hms_eye_diagnosis');
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
        $query = $this->db->get('hms_eye_suggetion');
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
			$this->db->select('hms_eye_suggetion.medicine_suggetion');  
			$this->db->where('hms_eye_suggetion.id',$value);  
			$this->db->where('hms_eye_suggetion.is_deleted',0);  
			$this->db->where('hms_eye_suggetion.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_suggetion.id');  
			$query = $this->db->get('hms_eye_suggetion');
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
        $query = $this->db->get('hms_eye_prv_history');
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
			$this->db->select('hms_eye_prv_history.prv_history');  
			$this->db->where('hms_eye_prv_history.id',$value);  
			$this->db->where('hms_eye_prv_history.is_deleted',0);  
			$this->db->where('hms_eye_prv_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_prv_history.id');  
			$query = $this->db->get('hms_eye_prv_history');
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
        $query = $this->db->get('hms_eye_personal_history');
        $result = $query->result(); 
        return $result; 
    }
    //hms_eye_personal_history
   public function personal_history_name($personal_history_id="")
   {
		$personal_history_ids = explode(',', $personal_history_id);
		$users_data = $this->session->userdata('auth_users'); 
		$personalname="";
		$i=1;
		$total = count($personal_history_ids);
		foreach ($personal_history_ids as $value) 
		{
			$this->db->select('hms_eye_personal_history.personal_history');  
			$this->db->where('hms_eye_personal_history.id',$value);  
			$this->db->where('hms_eye_personal_history.is_deleted',0);  
			$this->db->where('hms_eye_personal_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_personal_history.id');  
			$query = $this->db->get('hms_eye_personal_history');
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
    	//print_r($_POST); exit;
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		
 		

		$data = array(     
					"booking_code"=>$post['booking_code'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					"patient_bp"=>$post['patient_bp'],
					"patient_temp"=>$post['patient_temp'],
					"patient_weight"=>$post['patient_weight'],
					"patient_height"=>$post['patient_height'],
					"patient_spo2"=>$post['patient_spo2'],
					"prv_history"=>$post['prv_history'],
					"personal_history"=>$post['personal_history'],
					"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>$post['next_appointment_date'],
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_opd_prescription',$data); 
			$data_id = $this->db->insert_id(); 

			$total_test = count($post['test_name']);

			foreach ($post['test_name'] as $value) 
			{
					$test_data = array(
						"prescription_id"=>$data_id,
						"test_name"=>$value);
					$this->db->insert('hms_opd_prescription_patient_test',$test_data); 
					$test_data_id = $this->db->insert_id(); 
			}

			$total_prescription = count($post['medicine_name']); 
			for($i=0;$i<=$total_prescription-1;$i++)
			{
					$prescription_data = array(
						"prescription_id"=>$data_id,
						"medicine_name"=>$post['medicine_name'][$i],
						"medicine_type"=>$post['medicine_type'][$i],
						"medicine_dose"=>$post['medicine_dose'][$i],
						"medicine_duration"=>$post['medicine_duration'][$i],
						"medicine_frequency"=>$post['medicine_frequency'][$i],
						"medicine_advice"=>$post['medicine_advice'][$i]
						);
					$this->db->insert('hms_opd_prescription_patient_pres',$prescription_data); 
					$test_data_id = $this->db->insert_id(); 
			}


			 


			if(!empty($post['appointment_date']))
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



	           $booking_code = generate_unique_id(9);

	           $data_test = array(    
						'booking_code'=>$booking_code, 
						'referral_doctor'=>$referral_doctor,
						'attended_doctor'=>$attended_doctor,
						'sample_collected_by'=>$sample_collected_by,
						'staff_refrenace_id'=>$staff_refrenace_id,
						'booking_date'=>date('Y-m-d',strtotime($post['appointment_date']))
						
		         );

		    	$this->db->set('patient_id',$patient_id);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
		 	} 
	
    }

	public function get_eye_diseases_data($branch_id="")
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

	public function get_eye_advice_vals($vals="")
    {
		$vals = urldecode($vals);
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_advice','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_advice LIKE "%'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_eye_advice');
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

    public function get_eye_frequency_vals($vals="")
    {
		$vals = urldecode($vals);
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage_frequency','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage_frequency LIKE "%'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_eye_medicine_dosage_frequency');
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

   public function get_eye_duration_vals($vals="")
    {
		$vals = urldecode($vals);
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage_duration','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage_duration LIKE "%'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_eye_medicine_dosage_duration');
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

    public function get_eye_type_vals($vals="")
    {
		$vals = urldecode($vals);
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_unit LIKE "%'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_medicine_unit');
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
   public function get_eye_dosage_vals($vals="")
	{
		$vals = urldecode($vals);
		$response = '';
		if(!empty($vals))
		{
	        $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('dosage','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('dosage LIKE "%'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_eye_medicine_dosage');
	        $result = $query->result(); 
	        //echo $this->db->last_query();die;
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->dosage;
	        	}
	        }
	        return $response; 
		} 
	}
	public function get_eye_medicine_auto_vals($vals="")
    {
		$vals = urldecode($vals);
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');  
	        $this->db->where('hms_medicine_entry.status','1'); 
	        $this->db->order_by('hms_medicine_entry.medicine_name','ASC');
	        $this->db->where('hms_medicine_entry.is_deleted',0);
	        $this->db->where('hms_medicine_entry.medicine_name LIKE "%'.$vals.'%"');
	        $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);  
	        $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
	        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
	        $query = $this->db->get('hms_medicine_entry');
	        $result = $query->result(); 
	        //print '<pre>'; print_r($result);die;
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
    public function get_eye_test_vals($vals="")
    {
		$vals = urldecode($vals);
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
					$name = $vals->test_name;
					array_push($data, $name);
	            //    $response[] = $vals->test_name;
	        	}
	        }
	        echo json_encode($data);
    	} 
    }
    public function eye_personal_history_name($personal_history_id="")
	{
		$personal_history_ids = explode(',', $personal_history_id);
		$users_data = $this->session->userdata('auth_users'); 
		$personalname="";
		$i=1;
		$total = count($personal_history_ids);
		foreach ($personal_history_ids as $value) 
		{
			$this->db->select('hms_eye_personal_history.personal_history');  
			$this->db->where('hms_eye_personal_history.id',$value);  
			$this->db->where('hms_eye_personal_history.is_deleted',0);  
			$this->db->where('hms_eye_personal_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_personal_history.id');  
			$query = $this->db->get('hms_eye_personal_history');
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
    public function eye_template_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_eye_prescription_template');
        $result = $query->result(); 
        return $result; 
    }
    public function eye_personal_history_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0'); 
		if(!empty($type)) {
            $this->db->where('hms_eye_personal_history.personal_history LIKE "'.$type.'%"');
        }   
        $query = $this->db->get('hms_eye_personal_history');
        $result = $query->result(); 
        return $result; 
    }

	public function eye_prv_history_name($prv_id="")
	{
		$prv_ids = explode(',', $prv_id);
		$users_data = $this->session->userdata('auth_users'); 
		$prvname="";
		$i=1;
		$total = count($prv_ids);
		foreach ($prv_ids as $value) 
		{
			$this->db->select('hms_eye_prv_history.prv_history');  
			$this->db->where('hms_eye_prv_history.id',$value);  
			$this->db->where('hms_eye_prv_history.is_deleted',0);  
			$this->db->where('hms_eye_prv_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_prv_history.id');  
			$query = $this->db->get('hms_eye_prv_history');
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
    public function eye_prv_history_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
		if(!empty($type)) {
            $this->db->where('hms_eye_prv_history.prv_history LIKE "'.$type.'%"');
        } 
        $query = $this->db->get('hms_eye_prv_history');
        $result = $query->result(); 
        return $result; 
    }
    public function eye_suggetion_name($suggetion_id="")
	{
		$suggetion_ids = explode(',', $suggetion_id);
		$users_data = $this->session->userdata('auth_users'); 
		$suggetionname="";
		$i=1;
		$total = count($suggetion_ids);
		foreach ($suggetion_ids as $value) 
		{
			$this->db->select('hms_eye_suggetion.medicine_suggetion');  
			$this->db->where('hms_eye_suggetion.id',$value);  
			$this->db->where('hms_eye_suggetion.is_deleted',0);  
			$this->db->where('hms_eye_suggetion.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_suggetion.id');  
			$query = $this->db->get('hms_eye_suggetion');
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
   public function eye_suggetion_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
		if(!empty($type)) {
            $this->db->where('hms_eye_suggetion.medicine_suggetion LIKE "'.$type.'%"');
        } 
        $query = $this->db->get('hms_eye_suggetion');
        $result = $query->result(); 
        return $result; 
    }
   public function eye_diagnosis_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');  
		if(!empty($type)) {
            $this->db->where('hms_eye_diagnosis.diagnosis LIKE "'.$type.'%"');
        }  
        $query = $this->db->get('hms_eye_diagnosis');
        $result = $query->result(); 
        return $result; 
    }
   public function eye_diagnosis_name($diagnosis_id="",$rowcountid="")
	{
		$diagnosis_ids = explode(',', $diagnosis_id);
		$users_data = $this->session->userdata('auth_users'); 
		$diagnosisname="";
		$i=1;
		$total = count($diagnosis_ids);
		foreach ($diagnosis_ids as $value) 
		{
			$this->db->select('hms_eye_diagnosis.diagnosis');  
			$this->db->where('hms_eye_diagnosis.id',$value);  
			$this->db->where('hms_eye_diagnosis.is_deleted',0);  
			$this->db->where('hms_eye_diagnosis.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_diagnosis.id');  
			$query = $this->db->get('hms_eye_diagnosis');
			$result = $query->row(); 

			$diagnosisname .= $result->diagnosis;
			if($i!=$total)
			{
				$diagnosisname .=',';
			}
		
		$i++;
			
		}
			$ht='$(this).closest("tr").remove()';
		$html='<tr><td align="left" style="text-align:left;" height="30"><input type="text" name="diagnosis['.$rowcountid.'][diagnosis_name]" value="'.$diagnosisname.'"/></td>
							<td align="center" height="30">
							<input type="checkbox" class="w-40px" value="1" name="diagnosis['.$rowcountid.'][diagnosis_left]">
							</td>
							<td align="center" height="30"><input type="checkbox" class="w-40px" value="2" name="diagnosis['.$rowcountid.'][diagnosis_right]"></td>
							<td align="center" height="30">
								<select class="w-60px" id="" name="diagnosis['.$rowcountid.'][diagnosis_days]">
									<option value="1" >1</option>
									<option value="2" >2</option>
									<option value="3" >3</option>
									<option value="4" >4</option>
									<option value="5" >5</option>
									<option value="6" >6</option>
									<option value="7" >7</option>
									<option value="8" >8</option>
									<option value="9" >9</option>
								</select>
							</td>
							<td align="center" height="30">
								<select class="w-60px" name="diagnosis['.$rowcountid.'][diagnosis_time]">
									<option value="1" >Days</option>
									<option value="2" >Week</option>
									<option value="3" >Month</option>
									<option value="4" >Year</option>
								</select>
								
							</td>
							<td align="center" height="30"><a class="btn-custom btnDelete" onclick="remove_diagnosis_row('.$diagnosis_id.');"><i class="fa fa-trash"></i> Delete</a></td>
						</tr>';
						$i++;
		return $html;
		 
    }
   public function systemic_illness_list($type="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		if(!empty($type)) {
            $this->db->where('hms_eye_systemic_illness.systemic_illness LIKE "'.$type.'%"');
        } 
		$query = $this->db->get('hms_eye_systemic_illness');
		$result = $query->result();
		//print '<pre>'; print_r($result);die; 
		return $result; 
	}
	public function eye_examinations_list($type="")
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');  
		if(!empty($type)) {
            $this->db->where('hms_eye_examination.examination LIKE "'.$type.'%"');
        } 
		$query = $this->db->get('hms_eye_examination');
		$result = $query->result(); 
		return $result; 
	}
	public function eye_systemic_illness_list($systemic_illness_id="")
   {
		$systemic_illness = explode(',', $systemic_illness_id);
		$users_data = $this->session->userdata('auth_users'); 
		$systemicillnessname="";
		$i=1;
		$total = count($systemic_illness);
		foreach ($systemic_illness as $value) 
		{
			$this->db->select('hms_eye_systemic_illness.systemic_illness');  
			$this->db->where('hms_eye_systemic_illness.id',$value);  
			$this->db->where('hms_eye_systemic_illness.is_deleted',0);  
			$this->db->where('hms_eye_systemic_illness.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_systemic_illness.id');  
			$query = $this->db->get('hms_eye_systemic_illness');
			$result = $query->row(); 

			$systemicillnessname .= $result->systemic_illness;
			if($i!=$total)
			{
				$systemicillnessname .=',';
			}
		
		$i++;
			
		}
		$ht='$(this).closest("tr").remove()';
		$html='<tr><td align="left" style="text-align:left;" height="30"><input type="text" name="systemic_illness[]" value="'.$systemicillnessname.'"/></td>

							
							<td align="center" height="30">
								<select class="w-60px" id="" name="systemic_illness_days[]">
									<option value="1" >1</option>
									<option value="2" >2</option>
									<option value="3" >3</option>
									<option value="4" >4</option>
									<option value="5" >5</option>
									<option value="6" >6</option>
									<option value="7" >7</option>
									<option value="8" >8</option>
									<option value="9" >9</option>
								</select>
							</td>
							<td align="center" height="30">
								<select class="w-60px" name="systemic_illness_time[]">
									<option value="1" >Days</option>
									<option value="2" >Week</option>
									<option value="3" >Month</option>
									<option value="4" >Year</option>
								</select>
								
							</td>
							<td align="center" height="30"><a class="btn-custom btnDelete" onclick="remove_systemic_ill_row('.$systemic_illness_id.');"><i class="fa fa-trash"></i> Delete</a></td>
						</tr>';
						$i++;
		return $html;
    }

   public function eye_compalaints_list($compaints_id="",$rowcountid)
   {
		$chief_complaints = explode(',', $compaints_id);
		$users_data = $this->session->userdata('auth_users'); 
		$compalaintsname="";
		$i=1;
		$total = count($chief_complaints);
		foreach ($chief_complaints as $value) 
		{
			$this->db->select('hms_eye_chief_complaints.chief_complaints');  
			$this->db->where('hms_eye_chief_complaints.id',$value);  
			$this->db->where('hms_eye_chief_complaints.is_deleted',0);  
			$this->db->where('hms_eye_chief_complaints.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_chief_complaints.id');  
			$query = $this->db->get('hms_eye_chief_complaints');
			$result = $query->row(); 

			$compalaintsname .= $result->chief_complaints;
			if($i!=$total)
			{
				$compalaintsname .=',';
			}
		
		$i++;
			
		}
		$tr=0;
		if(!empty($this->input->get('tr_position')))
		{
			$tr=$this->input->get('tr_position')+1;
			
		}

		
	 
	   
	     $tr=$this->input->get('inital_tr_position')+1;
	    
		$ht='$(this).closest("tr").remove()';
		$html='<tr><td align="left" style="text-align:left;" height="30"><input type="text" name="cheif_complains['.$rowcountid.'][cheif_complain_name]" value="'.$compalaintsname.'"/></td>
							<td align="center" height="30">
							<input type="checkbox" class="w-40px" value="1" name="cheif_complains['.$rowcountid.'][cheif_c_left]">
							</td>
							<td align="center" height="30"><input type="checkbox" class="w-40px" value="2" name="cheif_complains['.$rowcountid.'][cheif_c_right]">
							</td>
							<td align="center" height="30">
								<select class="w-60px" id="" name="cheif_complains['.$rowcountid.'][cheif_c_days]">
									<option value="1" >1</option>
									<option value="2" >2</option>
									<option value="3" >3</option>
									<option value="4" >4</option>
									<option value="5" >5</option>
									<option value="6" >6</option>
									<option value="7" >7</option>
									<option value="8" >8</option>
									<option value="9" >9</option>
								</select>
							</td>
							<td align="center" height="30">
								<select class="w-60px" name="cheif_complains['.$rowcountid.'][cheif_c_time]">
									<option value="1" >Days</option>
									<option value="2" >Week</option>
									<option value="3" >Month</option>
									<option value="4" >Year</option>
								</select>
								
							</td>
							<td align="center" height="30">
							<a class="btn-custom btnDelete" onclick="remove_row('.$compaints_id.');"><i class="fa fa-trash"></i> Delete</a>
							
							</td>
						</tr>';
						$i++;
		return $html;
    }

	public function get_test_vals($vals="")
    {
		$vals = urldecode($vals);
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

   public function eye_examination_list($examination_id="")
   {
		$examination_ids = explode(',', $examination_id);
		$users_data = $this->session->userdata('auth_users'); 
		$examinationname="";
		$i=1;
		$total = count($examination_ids);
		foreach ($examination_ids as $value) 
		{
			$this->db->select('hms_eye_examination.examination');  
			$this->db->where('hms_eye_examination.id',$value);  
			$this->db->where('hms_eye_examination.is_deleted',0);  
			$this->db->where('hms_eye_examination.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_examination.id');  
			$query = $this->db->get('hms_eye_examination');
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

} 
?>