<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operation_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('operation_summary/operation_summary_model','operation_summary');
        $this->load->library('form_validation');
    }

   
  // operation summary functions starts 
    public function add($ot_booking_id)
    {
        //rec_id
        unauthorise_permission('133','801');
        $users_data = $this->session->userdata('auth_users');
        $data['page_title'] = "Add Operation Summary";  
        $this->load->model('ot_booking/ot_booking_model','otbooking');      
        $this->load->model('general/general_model');
        $result = $this->otbooking->get_by_id($ot_booking_id);  
        //echo "<pre>"; print_r($result); exit;
        $data['ot_procedures'] = get_ot_procedure_list();
        $data['post_observations'] = get_post_operative_observations();
        $data['simulation_list'] = $this->general_model->simulation_list(); 
        $data['data']=$result;
        $summary_data=$this->operation_summary->get_data_by_id($ot_booking_id,$result['patient_id'],$users_data['parent_id']);
        if(!empty($summary_data) && $summary_data!="" )
        {
			$data['medicine_data']=$this->operation_summary->get_summary_medicine_data($ot_booking_id,$result['patient_id'],$users_data['parent_id'],$summary_data['id']);
			$data['summary_data']=$summary_data;
			$data['rec_id']=$summary_data['id'];
			$data['diagnosis']=$summary_data['diagnosis'];
			$data['op_findings']=$summary_data['op_findings'];
			$data['procedures']=$summary_data['procedures'];
			$data['pos_op_orders']=$summary_data['pos_op_orders'];
			
			$data['indication_of_surgery']=$summary_data['indication_of_surgery'];
			$data['type_of_anaesthesia']=$summary_data['type_of_anaesthesia'];
			$data['name_of_anaesthetist']=$summary_data['name_of_anaesthetist'];
			$data['operation_start_time']=$summary_data['operation_start_time'];
			$data['operation_finish_time']=$summary_data['operation_finish_time'];
			$data['post_operative_period'] = $summary_data['post_operative_period'];
			$data['doctor_list'] =$this->operation_summary->doctor_list_by_otids($ot_booking_id);
        }
        else
        {
          $data['medicine_data']="empty";
          $data['summary_data']="empty";
          $data['rec_id']=0;
          $data['doctor_list'] =$this->otbooking->doctor_list_by_otids($ot_booking_id);
        }
        
        $this->load->model('ot_summary/ot_summary_model','otsummary');
        $data['ot_summary_template'] = $this->otsummary->ot_summary_list();
         
        $this->load->view('operation_summary/add',$data);
     
    }

    public function add_procedure_note($ot_booking_id)
    {
        //rec_id
        unauthorise_permission('133','801');
        $users_data = $this->session->userdata('auth_users');
        $data['page_title'] = "Add Operation Summary";  
        $this->load->model('ot_booking/ot_booking_model','otbooking');      
        $this->load->model('general/general_model');
        $result = $this->otbooking->get_by_id($ot_booking_id);  
        //echo "<pre>"; print_r($result); exit;
        $data['ot_procedures'] = get_ot_procedure_list();
        $data['post_observations'] = get_post_operative_observations();
        $data['simulation_list'] = $this->general_model->simulation_list(); 
        $data['data']=$result;
        $summary_data=$this->operation_summary->get_procedure_data_by_id($ot_booking_id,$result['patient_id'],$users_data['parent_id']);
        if(!empty($summary_data) && $summary_data!="" )
        {
			$data['medicine_data']=$this->operation_summary->get_procedure_summary_medicine_data($ot_booking_id,$result['patient_id'],$users_data['parent_id'],$summary_data['id']);
			$data['summary_data']=$summary_data;
			$data['rec_id']=$summary_data['id'];
			$data['diagnosis']=$summary_data['diagnosis'];
			$data['op_findings']=$summary_data['op_findings'];
			$data['procedures']=$summary_data['procedures'];
			$data['pos_op_orders']=$summary_data['pos_op_orders'];
			
			$data['indication_of_surgery']=$summary_data['indication_of_surgery'];
			$data['type_of_anaesthesia']=$summary_data['type_of_anaesthesia'];
			$data['name_of_anaesthetist']=$summary_data['name_of_anaesthetist'];
			$data['operation_start_time']=$summary_data['operation_start_time'];
			$data['operation_finish_time']=$summary_data['operation_finish_time'];
			$data['post_operative_period'] = $summary_data['post_operative_period'];
			$data['doctor_list'] =$this->operation_summary->procedure_doctor_list_by_otids($ot_booking_id);
        }
        else
        {
          $data['medicine_data']="empty";
          $data['summary_data']="empty";
          $data['rec_id']=0;
          $data['doctor_list'] =$this->otbooking->doctor_list_by_otids($ot_booking_id);
        }
        
        $this->load->model('procedure_note_summary/procedure_note_summary_model','prosummary');
        $data['ot_summary_template'] = $this->prosummary->ot_summary_list();
        
        $this->load->model('procedure_data/procedure_data_model','pd_list');
        $data['procedure_data'] = $this->pd_list->procedure_data_list();
        $this->load->model('procedure_note_tab_setting/procedure_note_tab_setting_model','procedure_note');
        $data['procedure_note_tab_setting_list'] = $this->procedure_note->get_setting();
         
        $this->load->view('operation_summary/add_procedure_note',$data);
     
    }
  // Function to store ot summary data
  public function save_procedure_data()
  {
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
      $this->form_validation->set_rules('ot_procedure', 'OT Procedure', 'trim'); 
      $this->form_validation->set_rules('post_observations', 'Post Observations', 'trim');   
        if ($this->form_validation->run() == FALSE) 
        {  
          echo json_encode(array('st'=>0,'ot_procedure'=>form_error('ot_procedure'), 'post_observations'=>form_error('post_observations') ));    
        }   
        else
        {
          //echo "<pre>";print_r($this->input->post());die;
          $ot_summary_id=$this->input->post('ot_summary_id');
            

          $users_data = $this->session->userdata('auth_users');
          $patient_id=$this->input->post('patient_id');
          $ot_booking_id=$this->input->post('ot_booking_id');
          $ot_procedure=$this->input->post('ot_procedure');
          $post_observations=$this->input->post('post_observations');
          $medicine_data=$this->input->post('medicine');
          $remark=$this->input->post('remark');
          $diagnosis = $this->input->post('diagnosis');
          $op_findings = $this->input->post('op_findings');
          $procedures = $this->input->post('procedures');
          $pos_op_orders = $this->input->post('pos_op_orders');

          $surgeon_name = $this->input->post('surgeon_name');
          $blood_transfusion = $this->input->post('blood_transfusion');
          $recovery_time = $this->input->post('recovery_time');
         
		$indication_of_surgery = $this->input->post('indication_of_surgery');
		$type_of_anaesthesia = $this->input->post('type_of_anaesthesia');
		$name_of_anaesthetist = $this->input->post('name_of_anaesthetist');
		$operation_start_time = $this->input->post('operation_start_time');
		$operation_finish_time = $this->input->post('operation_finish_time');
		$post_operative_period = $this->input->post('post_operative_period');
		

        if($recovery_time!='00-00-0000 00:00:00' && $recovery_time!='01-01-1970')
		{
			$recovery_time_new = date('Y-m-d H:i:s',strtotime($recovery_time));
		}	
		else
		{
			$recovery_time_new = ''; 
		}
		if($operation_start_time!='00-00-0000 00:00:00' && $operation_start_time!='01-01-1970')
		{
			$operation_start_time_new = date('Y-m-d H:i:s',strtotime($operation_start_time));
		}	
		else
		{
			$operation_start_time_new = ''; 
		}
		
		if($operation_finish_time!='00-00-0000 00:00:00' && $operation_finish_time!='01-01-1970')
		{
			$operation_finish_time_new = date('Y-m-d H:i:s',strtotime($operation_finish_time));
		}	
		else
		{
			$operation_finish_time_new = ''; 
		}

          $blood_loss = $this->input->post('blood_loss');
          $drain = $this->input->post('drain');
          $histopathological = $this->input->post('histopathological');
          $microbiological = $this->input->post('microbiological');

        /*$post = $this->input->post();
          echo "<pre>"; print_r($post); exit;*/

          if($ot_summary_id==0) // Main table Insertion code
          {
            $data_array=array('branch_id'=>$users_data['parent_id'],  
                            'patient_id'=>$patient_id,
                            'ot_booking_id'=>$ot_booking_id,
                            'ot_procedure_id'=>$ot_procedure,
                            'post_observation_id'=>$post_observations,
                            'remark'=>$remark,
                            'diagnosis'=>$diagnosis,
                            'op_findings'=>$op_findings,
                            'procedures'=>$procedures,
                            'pos_op_orders'=>$pos_op_orders,
                             'indication_of_surgery'=>$indication_of_surgery,
                             'type_of_anaesthesia'=>$type_of_anaesthesia,
                             'name_of_anaesthetist'=>$name_of_anaesthetist,
                             'operation_start_time'=>$operation_start_time_new,
                             'operation_finish_time'=>$operation_finish_time_new,
                             'surgeon_name'=>$surgeon_name,
                             'post_operative_period'=>$post_operative_period,
                            'blood_transfusion'=>$blood_transfusion,
                            'recovery_time'=>$recovery_time_new,
                            'blood_loss'=>$blood_loss,
                            'drain'=>$drain,
                            'histopathological'=>$histopathological,
                            'microbiological'=>$microbiological,

                            'created_date'=>date('Y-m-d'),
                            'created_by'=>$users_data['id'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'status'=>1,
                            'procedure_data' =>$this->input->post('procedure_data')
                            );
                      
            $inserted_id=$this->operation_summary->common_insert('hms_operation_procedure_note_summary',$data_array);


            $post_doctor= count($this->input->post('doctor_names'));
			foreach($this->input->post('doctor_names') as $key=>$value)
			{
				$doctor_array=array('summary_id'=>$ot_booking_id,'doctor_id'=>$key,'doctor_name'=>$value[0]);
				$this->db->insert('hms_operation_summary_procedure_note_to_doctors',$doctor_array);
				
			} 


          }
          else if($ot_summary_id > 0) // Main table updation code
          {
            $data_array=array(
                            'ot_procedure_id'=>$ot_procedure,
                            'post_observation_id'=>$post_observations,
                            'remark'=>$remark,
                            'diagnosis'=>$diagnosis,
                            'op_findings'=>$op_findings,
                            'procedures'=>$procedures,
                            'pos_op_orders'=>$pos_op_orders,
                                
                            'surgeon_name'=>$surgeon_name,
                            'blood_transfusion'=>$blood_transfusion,
                            'recovery_time'=>$recovery_time_new,
                            'blood_loss'=>$blood_loss,
                            'drain'=>$drain,
                            'histopathological'=>$histopathological,
                            'microbiological'=>$microbiological,
                            'indication_of_surgery'=>$indication_of_surgery,
                             'type_of_anaesthesia'=>$type_of_anaesthesia,
                             'name_of_anaesthetist'=>$name_of_anaesthetist,
                             'operation_start_time'=>$operation_start_time_new,
                             'operation_finish_time'=>$operation_finish_time_new,
                             'post_operative_period'=>$post_operative_period,
                            'modified_date'=>date('Y-m-d'),
                            'modified_by'=>$users_data['id'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'procedure_data' =>$this->input->post('procedure_data'),
                            );
            $result=$this->operation_summary->common_update('hms_operation_procedure_note_summary',$data_array,$ot_summary_id,$users_data['parent_id']);
            //echo $this->db->last_query(); exit;
            $inserted_id=$ot_summary_id;

            $this->db->where(array('summary_id'=>$ot_booking_id));
			$this->db->delete('hms_operation_summary_procedure_note_to_doctors');

            foreach($this->input->post('doctor_names') as $key=>$value)
			{
				$doctors_array=array('summary_id'=>$ot_booking_id,
					'doctor_id'=>$key,
					'doctor_name'=>$value[0]
					);
				
				$this->db->insert('hms_operation_summary_procedure_note_to_doctors',$doctors_array);
				//echo $this->db->last_query(); 
			}
			//exit;

          }
          if($medicine_data!="" && !empty($medicine_data))
          {
            if($ot_summary_id > 0)
            {
              $this->operation_summary->delete_operation_summary_medicines($ot_summary_id,$users_data['parent_id']);
            }
            foreach($medicine_data as $data)
            {
                if($data['medicine_name']!="" && !empty($data['medicine_name']) )
                {
                  $medicine_array=array('summary_id'=>$inserted_id,
                                      'branch_id'=>$users_data['parent_id'],
                                      'is_eye_drop'=>$data['is_eyedrop'],
                                      'medicine_name'=>$data['medicine_name'],
                                      'medicine_unit'=>$data['medicine_unit'],
                                      'medicine_company'=>$data['medicine_company'],
                                      'medicine_salt'=>$data['medicine_salt'],
                                      'medicine_dose'=>$data['medicine_dosage'],
                                      'medicine_duration'=>$data['medicine_duration'],
                                      'medicine_frequency'=>$data['medicine_frequency'],
                                      'medicine_advice'=>$data['medicine_advice'],
                                      'medicine_date'=>date('Y-m-d',strtotime($data["medicine_date"])),
                                      'left_eye'=>$data['left_eye'],
                                      'right_eye'=>$data['right_eye'],
                                      'created_date'=>date('Y-m-d H:i:s'),
                                      'created_by'=>$users_data['id'],
                                      'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                      'status'=>1,
                                      );
                  //print_r($medicine_array);die;
                  $result=$this->operation_summary->common_insert('hms_operation_procedure_note_summary_to_medicine',$medicine_array);
                  //echo $this->db->last_query(); //exit;
                }
            }
            
            //die;
            echo json_encode(array('st'=>1, 'message'=>"Record Inserted Successfully" ));
          }
        }
    
  }
  
  
  public function save_data()
  {
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
      $this->form_validation->set_rules('ot_procedure', 'OT Procedure', 'trim'); 
      $this->form_validation->set_rules('post_observations', 'Post Observations', 'trim');   
        if ($this->form_validation->run() == FALSE) 
        {  
          echo json_encode(array('st'=>0,'ot_procedure'=>form_error('ot_procedure'), 'post_observations'=>form_error('post_observations') ));    
        }   
        else
        {
          //echo "<pre>";print_r($this->input->post());die;
          $ot_summary_id=$this->input->post('ot_summary_id');
            

          $users_data = $this->session->userdata('auth_users');
          $patient_id=$this->input->post('patient_id');
          $ot_booking_id=$this->input->post('ot_booking_id');
          $ot_procedure=$this->input->post('ot_procedure');
          $post_observations=$this->input->post('post_observations');
          $medicine_data=$this->input->post('medicine');
          $remark=$this->input->post('remark');
          $diagnosis = $this->input->post('diagnosis');
          $op_findings = $this->input->post('op_findings');
          $procedures = $this->input->post('procedures');
          $pos_op_orders = $this->input->post('pos_op_orders');

          $surgeon_name = $this->input->post('surgeon_name');
          $blood_transfusion = $this->input->post('blood_transfusion');
          $recovery_time = $this->input->post('recovery_time');
         
		$indication_of_surgery = $this->input->post('indication_of_surgery');
		$type_of_anaesthesia = $this->input->post('type_of_anaesthesia');
		$name_of_anaesthetist = $this->input->post('name_of_anaesthetist');
		$operation_start_time = $this->input->post('operation_start_time');
		$operation_finish_time = $this->input->post('operation_finish_time');
		$post_operative_period = $this->input->post('post_operative_period');
		

        if($recovery_time!='00-00-0000 00:00:00' && $recovery_time!='01-01-1970')
		{
			$recovery_time_new = date('Y-m-d H:i:s',strtotime($recovery_time));
		}	
		else
		{
			$recovery_time_new = ''; 
		}
		if($operation_start_time!='00-00-0000 00:00:00' && $operation_start_time!='01-01-1970')
		{
			$operation_start_time_new = date('Y-m-d H:i:s',strtotime($operation_start_time));
		}	
		else
		{
			$operation_start_time_new = ''; 
		}
		
		if($operation_finish_time!='00-00-0000 00:00:00' && $operation_finish_time!='01-01-1970')
		{
			$operation_finish_time_new = date('Y-m-d H:i:s',strtotime($operation_finish_time));
		}	
		else
		{
			$operation_finish_time_new = ''; 
		}

          $blood_loss = $this->input->post('blood_loss');
          $drain = $this->input->post('drain');
          $histopathological = $this->input->post('histopathological');
          $microbiological = $this->input->post('microbiological');

        /*$post = $this->input->post();
          echo "<pre>"; print_r($post); exit;*/

          if($ot_summary_id==0) // Main table Insertion code
          {
            $data_array=array('branch_id'=>$users_data['parent_id'],  
                            'patient_id'=>$patient_id,
                            'ot_booking_id'=>$ot_booking_id,
                            'ot_procedure_id'=>$ot_procedure,
                            'post_observation_id'=>$post_observations,
                            'remark'=>$remark,
                            'diagnosis'=>$diagnosis,
                            'op_findings'=>$op_findings,
                            'procedures'=>$procedures,
                            'pos_op_orders'=>$pos_op_orders,
                             'indication_of_surgery'=>$indication_of_surgery,
                             'type_of_anaesthesia'=>$type_of_anaesthesia,
                             'name_of_anaesthetist'=>$name_of_anaesthetist,
                             'operation_start_time'=>$operation_start_time_new,
                             'operation_finish_time'=>$operation_finish_time_new,
                             'surgeon_name'=>$surgeon_name,
                             'post_operative_period'=>$post_operative_period,
                            'blood_transfusion'=>$blood_transfusion,
                            'recovery_time'=>$recovery_time_new,
                            'blood_loss'=>$blood_loss,
                            'drain'=>$drain,
                            'histopathological'=>$histopathological,
                            'microbiological'=>$microbiological,

                            'created_date'=>date('Y-m-d'),
                            'created_by'=>$users_data['id'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'status'=>1,
                            );
            $inserted_id=$this->operation_summary->common_insert('hms_operation_summary',$data_array);


            $post_doctor= count($this->input->post('doctor_names'));
			foreach($this->input->post('doctor_names') as $key=>$value)
			{
				$doctor_array=array('summary_id'=>$ot_booking_id,'doctor_id'=>$key,'doctor_name'=>$value[0]);
				$this->db->insert('hms_operation_summary_to_doctors',$doctor_array);
				
			} 


          }
          else if($ot_summary_id > 0) // Main table updation code
          {
            $data_array=array(
                            'ot_procedure_id'=>$ot_procedure,
                            'post_observation_id'=>$post_observations,
                            'remark'=>$remark,
                            'diagnosis'=>$diagnosis,
                            'op_findings'=>$op_findings,
                            'procedures'=>$procedures,
                            'pos_op_orders'=>$pos_op_orders,
                                
                            'surgeon_name'=>$surgeon_name,
                            'blood_transfusion'=>$blood_transfusion,
                            'recovery_time'=>$recovery_time_new,
                            'blood_loss'=>$blood_loss,
                            'drain'=>$drain,
                            'histopathological'=>$histopathological,
                            'microbiological'=>$microbiological,
                            'indication_of_surgery'=>$indication_of_surgery,
                             'type_of_anaesthesia'=>$type_of_anaesthesia,
                             'name_of_anaesthetist'=>$name_of_anaesthetist,
                             'operation_start_time'=>$operation_start_time_new,
                             'operation_finish_time'=>$operation_finish_time_new,
                             'post_operative_period'=>$post_operative_period,
                            'modified_date'=>date('Y-m-d'),
                            'modified_by'=>$users_data['id'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            );
            $result=$this->operation_summary->common_update('hms_operation_summary',$data_array,$ot_summary_id,$users_data['parent_id']);
            //echo $this->db->last_query(); exit;
            $inserted_id=$ot_summary_id;

            $this->db->where(array('summary_id'=>$ot_booking_id));
			$this->db->delete('hms_operation_summary_to_doctors');

            foreach($this->input->post('doctor_names') as $key=>$value)
			{
				$doctors_array=array('summary_id'=>$ot_booking_id,
					'doctor_id'=>$key,
					'doctor_name'=>$value[0]
					);
				
				$this->db->insert('hms_operation_summary_to_doctors',$doctors_array);
				//echo $this->db->last_query(); 
			}
			//exit;

          }
          if($medicine_data!="" && !empty($medicine_data))
          {
            if($ot_summary_id > 0)
            {
              $this->operation_summary->delete_operation_summary_medicines($ot_summary_id,$users_data['parent_id']);
            }
            foreach($medicine_data as $data)
            {
                if($data['medicine_name']!="" && !empty($data['medicine_name']) )
                {
                  $medicine_array=array('summary_id'=>$inserted_id,
                                      'branch_id'=>$users_data['parent_id'],
                                      'is_eye_drop'=>$data['is_eyedrop'],
                                      'medicine_name'=>$data['medicine_name'],
                                      'medicine_unit'=>$data['medicine_unit'],
                                      'medicine_company'=>$data['medicine_company'],
                                      'medicine_salt'=>$data['medicine_salt'],
                                      'medicine_dose'=>$data['medicine_dosage'],
                                      'medicine_duration'=>$data['medicine_duration'],
                                      'medicine_frequency'=>$data['medicine_frequency'],
                                      'medicine_advice'=>$data['medicine_advice'],
                                      'medicine_date'=>date('Y-m-d',strtotime($data["medicine_date"])),
                                      'left_eye'=>$data['left_eye'],
                                      'right_eye'=>$data['right_eye'],
                                      'created_date'=>date('Y-m-d H:i:s'),
                                      'created_by'=>$users_data['id'],
                                      'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                      'status'=>1,
                                      );
                  //print_r($medicine_array);die;
                  $result=$this->operation_summary->common_insert('hms_operation_summary_to_medicine',$medicine_array);
                  //echo $this->db->last_query(); //exit;
                }
            }
            
            //die;
            echo json_encode(array('st'=>1, 'message'=>"Record Inserted Successfully" ));
          }
        }
    
  } 

  function append_doctor_list(){
     $name=  $this->input->post('name');
     $row_count=$this->input->post('rowCount');
     $doctor_id=$this->input->post('doctor_id');
     $simulat = $this->input->post('simulat');
     $i=1;
     if(!empty($name)){
     $table=' <tr> <td><input type="checkbox" value="'.$simulat." ".$name.'" name="doctor_names['.$doctor_id.'][]" class="child_checkbox" onclick="add_check();" checked/></td>
                 <td>'.$row_count.'</td>
                <td>'.$simulat." ".ucfirst($name).'</td>
                        </tr>';
                        $i++;
                        echo $table;exit;
                      }

    }

   function doctor_list_by_otids($id){
    	$this->db->select('hms_operation_summary_to_doctors.*');
		$this->db->from('hms_operation_summary_to_doctors'); 
		$this->db->where('hms_operation_summary_to_doctors.summary_id',$id);
		
		$query = $this->db->get()->result();
		$data=array(); 
		foreach($query as $res){
			$data[$res->doctor_id][]=$res->doctor_name;
		}
		return $data;
	
    } 
  // Function to store ot summary data  


  // Function to view ot summary print template
    public function ot_summary_print_setting()
    {
      unauthorise_permission('133','802');
      $users_data = $this->session->userdata('auth_users');
      $branch_id  = $users_data['parent_id'];
      $data['print_template']=$this->operation_summary->get_ot_summary_template($branch_id);
      $data['page_title']="Operation Summary Print Settings";
      $this->load->view('operation_summary/ot_summary_print_setting',$data);
    }

    public function procedure_note_summary_print_setting()
    {
      unauthorise_permission('133','802');
      $users_data = $this->session->userdata('auth_users');
      $branch_id  = $users_data['parent_id'];
      $data['print_template']=$this->operation_summary->get_procedure_note_summary_template($branch_id);
      $data['page_title']="Procedure Note Summary Print Settings";
      $this->load->view('operation_summary/procedure_note_summary_print_setting',$data);
    }
  // Function to view ot summary print template

  // Function to set ot summary settings  
    public function update_ot_summary_settings()
    {
      $template=$this->input->post('print_setting');
      $rec_id=$this->input->post('rec_id');
      $users_data = $this->session->userdata('auth_users');
      $branch_id  = $users_data['parent_id'];
      $data_array=array('setting_value'=>$template,'modified_date'=>date('Y-m-d H:i:s'), 'modified_by'=>$users_data['id']);
      $result=$this->operation_summary->common_update('hms_ot_summary_print_setting',$data_array,$rec_id,$users_data['parent_id']);
      echo "Template Updated Succesfully";

    }

    public function update_procedure_note_summary_settings()
    {
      $template=$this->input->post('print_setting');
      $rec_id=$this->input->post('rec_id');
      $users_data = $this->session->userdata('auth_users');
      $branch_id  = $users_data['parent_id'];
      $data_array=array('setting_value'=>$template,'modified_date'=>date('Y-m-d H:i:s'), 'modified_by'=>$users_data['id']);
      $result=$this->operation_summary->common_update('hms_procedure_note_summary_print_setting',$data_array,$rec_id,$users_data['parent_id']);
      echo "Template Updated Succesfully";

    }
  // Function to set ot summary settings  

  // Function to print Ot Summary
    public function print_procedure_note_summary($ot_booking_id,$id="")
    {
        //echo "dsdd"; exit;
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $data['page_title'] = "Add Operation Summary";  
        $this->load->model('ot_booking/ot_booking_model','otbooking');      
        $this->load->model('general/general_model');
        $result = $this->otbooking->get_by_id($ot_booking_id);  
        $data['ot_procedures'] = get_ot_procedure_list();
        $data['post_observations'] = get_post_operative_observations();
        $data['data']=$result;
        $summary_data=$this->operation_summary->get_procedure_data_by_id($ot_booking_id,$id,$result['patient_id'],$users_data['parent_id']);

        //echo "<pre>"; print_r($summary_data); exit;
        if(!empty($summary_data) && $summary_data!="" )
        {
          $data['medicine_data']=$this->operation_summary->get_procedure_summary_medicine_data($ot_booking_id,$result['patient_id'],$users_data['parent_id'],$summary_data['id']);
          
           $data['signature_data']=$this->operation_summary->get_doctor_digital_sign($summary_data['surgeon_name']);
           //echo "<pre>"; print_r($data['signature_data']); exit;
          $data['summary_data']=$summary_data;
          $data['rec_id']=$summary_data['id'];
        }
        else
        {
          $data['medicine_data']="empty";
          $data['summary_data']="empty";
          $data['rec_id']=0;
        }
        //print "<pre>";print_r($users_data);
        //print "<pre>";print_r($data['medicine_data']);die;
        $this->load->model('procedure_history/procedure_history_model','procedure_history');
        $data['template']=$this->operation_summary->get_procedure_note_summary_template($branch_id);
        $doctor_list =$this->procedure_history->procedure_doctor_list_by_otids($id);
      
        $get_detail_by_id= $this->otbooking->get_by_id($ot_booking_id);
        $data['patient_ot_detail']= $get_detail_by_id;
        //print_r($doctor_list);
        $doctor_name=array();
	    foreach($doctor_list as $key=>$value){
	    $doctor_name[]= $value[0];

	    }
	    $doctor_names= implode(',',$doctor_name);
	    $data['doctor_name'] = $doctor_names; //die;
      $this->load->model('procedure_data/procedure_data_model');
	    $procedureData = $this->procedure_data_model->get_by_id($summary_data['procedure_data']);
      $data['procedure_data'] = $procedureData['name'];
        $this->load->view('operation_summary/procedure_note_summary_print',$data);
    }

    public function print_summary($ot_booking_id)
    {
        //echo "dsdd"; exit;
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $data['page_title'] = "Add Operation Summary";  
        $this->load->model('ot_booking/ot_booking_model','otbooking');      
        $this->load->model('general/general_model');
        $result = $this->otbooking->get_by_id($ot_booking_id);  
        $data['ot_procedures'] = get_ot_procedure_list();
        $data['post_observations'] = get_post_operative_observations();
        $data['data']=$result;
        $summary_data=$this->operation_summary->get_data_by_id($ot_booking_id,$result['patient_id'],$users_data['parent_id']);

        //echo "<pre>"; print_r($summary_data); exit;
        if(!empty($summary_data) && $summary_data!="" )
        {
          $data['medicine_data']=$this->operation_summary->get_summary_medicine_data($ot_booking_id,$result['patient_id'],$users_data['parent_id'],$summary_data['id']);
          
           $data['signature_data']=$this->operation_summary->get_doctor_digital_sign($summary_data['surgeon_name']);
          //  echo "<pre>"; print_r($data['signature_data']); exit;
          $data['summary_data']=$summary_data;
          $data['rec_id']=$summary_data['id'];
        }
        else
        {
          $data['medicine_data']="empty";
          $data['summary_data']="empty";
          $data['rec_id']=0;
        }
        //print "<pre>";print_r($users_data);
        //print "<pre>";print_r($data['medicine_data']);die;
        $data['template']=$this->operation_summary->get_ot_summary_template($branch_id);
        $doctor_list =$this->operation_summary->doctor_list_by_otids($ot_booking_id);
        $get_detail_by_id= $this->otbooking->get_by_id($ot_booking_id);
        $data['patient_ot_detail']= $get_detail_by_id;
        //print_r($doctor_list);
        $doctor_name=array();
	    foreach($doctor_list as $key=>$value){
	    $doctor_name[]=$value[0];

	    }
	    $doctor_names= implode(',',$doctor_name);
	    $data['doctor_name'] = $doctor_names; //die;
        $this->load->view('operation_summary/ot_summary_print',$data);
    }
  // Funciton to print OT summary

    function get_template($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->operation_summary->get_template($template_id);
        echo $templatedata;
      }
    }

    function get_procedure_template($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->operation_summary->get_procedure_template($template_id);
        echo $templatedata;
      }
    }
    
    function get_template_medicine($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->operation_summary->get_template_medicine($template_id);
        
        echo $templatetestdata;
      }
    }


// Please write code above this
}
?>