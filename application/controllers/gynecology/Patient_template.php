<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_template extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();  
      auth_users();  
      $this->load->model('gynecology/patient_template/patient_template_model','patienttemplate');
      $this->load->library('form_validation');
  }

    public function index()
    {
        unauthorise_permission('314','1874');
        $this->session->unset_userdata('patient_history_data');
        $this->session->unset_userdata('patient_family_history_data');
        $this->session->unset_userdata('patient_personal_history_data');
        $this->session->unset_userdata('patient_menstrual_history_data');
        $this->session->unset_userdata('patient_medical_history_data');
        $this->session->unset_userdata('patient_obestetric_history_data');
        $this->session->unset_userdata('patient_disease_data');
        $this->session->unset_userdata('patient_complaint_data');
        $this->session->unset_userdata('patient_allergy_data');
        $this->session->unset_userdata('patient_general_examination_data');
        $this->session->unset_userdata('patient_clinical_examination_data');  
        $this->session->unset_userdata('patient_investigation_data');  
        $this->session->unset_userdata('patient_advice_data');

        $data['page_title'] = 'Prescription Template List'; 
        $this->load->view('gynecology/patient_template/list',$data);
    }

    public function ajax_list()
    {   
        unauthorise_permission('314','1874');
        $this->session->unset_userdata('book_test');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->patienttemplate->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescriptions) {
         // print_r($test);die;
            $no++;
            $row = array(); 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }

            if($prescriptions->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }  
           
            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$prescriptions->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$prescriptions->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $prescriptions->template_name;
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($prescriptions->created_date)); 
      
            //Action button /////
            $btn_confirm="";
            $btn_edit = ""; 
            $btn_delete = ""; 
              
            if(in_array('1876',$users_data['permission']['action'])) 
            {
                $btn_edit = ' <a class="btn-custom" href="'.base_url("gynecology/patient_template/edit/".$prescriptions->id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
           }
            if(in_array('1877',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a class="btn-custom" onClick="return delete_template('.$prescriptions->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>'; 
            }
              
            // End Action Button //
            
            $row[] = $btn_edit.$btn_delete;   
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->patienttemplate->count_all(),
                        "recordsFiltered" => $this->patienttemplate->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

  
    public function delete($id="")
    {
        unauthorise_permission('314','1877');
        if(!empty($id) && $id>0)
        {
        $result = $this->patienttemplate->delete($id);
        $response = "Gynecology Prescription Template successfully deleted.";
        echo $response;
        }
    }

    function deleteall()
    {
         unauthorise_permission('314','1877');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->patienttemplate->deleteall($post['row_id']);
            $response = "Gynecology Prescription Template Booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        //unauthorise_permission(247,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->patienttemplate->get_by_id($id);  
        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('eye/patient_template/view',$data);     
      }
    }  


   
  public function add()
  {
       unauthorise_permission('314','1875');
      $post = $this->input->post();
      if(empty($post))
      {
          $this->session->unset_userdata('patient_history_data');
          $this->session->unset_userdata('patient_family_history_data');
          $this->session->unset_userdata('patient_personal_history_data');
          $this->session->unset_userdata('patient_menstrual_history_data');
          $this->session->unset_userdata('patient_medical_history_data');
          $this->session->unset_userdata('patient_obestetric_history_data');
          $this->session->unset_userdata('patient_disease_data');
          $this->session->unset_userdata('patient_complaint_data');
          $this->session->unset_userdata('patient_allergy_data');
          $this->session->unset_userdata('patient_general_examination_data');
          $this->session->unset_userdata('patient_clinical_examination_data');  
          $this->session->unset_userdata('patient_investigation_data');  
          $this->session->unset_userdata('patient_advice_data');
          $this->session->unset_userdata('patient_gpla_data');
      }
      
      $this->load->model('gynecology/general/Gynecology_general_model','gynecology_general'); 
      $this->load->helper('gynecology'); 
      $post = $this->input->post();
      $patient_id = "";
      $patient_code = "";
      $simulation_id = "";
      $patient_name = "";
      $mobile_no = "";
      $gender = "";
      $age_y = "";
      $age_m = "";
      $age_d = "";
      $address = "";
      $city_id = "";
      $state_id = "";
      $country_id = ""; 
      
      $this->load->model('opd/opd_model','opd');
      $data['relation_list'] = $this->patienttemplate->gynecology_realtion_list();
      $data['disease_list'] = $this->patienttemplate->gynecology_disease_list();
      $data['complaint_list'] = $this->patienttemplate->gynecology_complaint_list();
      $data['allergy_list'] = $this->patienttemplate->gynecology_allergy_list();
      $data['general_examination_list'] = $this->patienttemplate->gynecology_general_examination_list();
      $data['clinical_examination_list'] = $this->patienttemplate->gynecology_clinical_examination_list();
      $data['investigation_list'] = $this->patienttemplate->gynecology_investigation_list();
      $data['advice_list'] = $this->patienttemplate->gynecology_advice_list();
      $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting();
      $data['prescription_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
      //print_r( $data['prescription_medicine_tab_setting']);die;
      $data['page_title'] = "Add Gynecology Patient Template";
      $post = $this->input->post();
      $data['form_error'] = []; 
      $data['form_data'] = array(
                                'data_id'=>"", 
                                "template_name"=>'',
                                'patient_bp'=>"",
                                'patient_temp'=>"",
                                'patient_weight'=>"",
                                'patient_spo2'=>"",
                                'patient_height'=>'',
                                'prv_history'=>"",
                                'personal_history'=>"",
                                'examination'=>'',
                                'diagnosis'=>'',
                                'suggestion'=>'',
                                'biometric_details'=>'',
                                'systemic_illness'=>'',
                                'remark'=>'',
                                'next_appointment_date'=>"",
                                'appointment_time'=>"",
                                'appointment_date'=>"",
                                'blood_group'=>"",
                                'disease_id'=>"",
                                'disease_details'=>"",
                                'operation'=>"",
                                'operation_date'=>"",
                                'chief_complaint_id'=>"",
                                'reason'=>"",
                                'teeth_name'=>"",
                                'number'=>"",
                                'time'=>"",
                                'allergy_id'=>"",
                                'habit_id'=>"",
                                'advice_id'=>"",
                                'diagnosis_id'=>"",
                                'treatment_id'=>"",
                                'investigation_id'=>"",
                                'check_appointment'=>"",
                                'next_appointment_date'=>""

                                );
      if(isset($post) && !empty($post))
      {   
          $data['form_data'] = $this->_validate();
          if($this->form_validation->run() == TRUE)
          {
            $this->patienttemplate->save();
            $this->session->set_flashdata('success','Gynecology Prescription template successfully added.');
            redirect(base_url('gynecology/patient_template'));
          }
          else
          {
              $data['form_error'] = validation_errors();  
          }    
      }   

      $this->load->view('gynecology/patient_template/add_template',$data);
  }

  public function patient_gpla_list()
    {
      $post = $this->input->post();

      $patient_gpla_data = $this->session->userdata('patient_gpla_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_gpla_data = $this->session->userdata('patient_gpla_data'); 
        if(isset($patient_gpla_data) && !empty($patient_gpla_data))
        {
          $patient_gpla = $patient_gpla_data;
        }
        else
        {
          $patient_gpla = [];
        }
  
        
        $patient_gpla[$post['unique_id_patient_gpla']] = array('patient_gpla_id'=>$post['patient_gpla_id'], 'dog_value'=>$post['dog_value'], 'mode_value'=>$post['mode_value'], 'monthyear_value'=>$post['monthyear_value'], 'unique_id_patient_gpla'=>$post['unique_id_patient_gpla']);
        $this->session->set_userdata('patient_gpla_data', $patient_gpla);
        $html_data = $this->patient_gpla_template_list();
       // print_r($html_data);die();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_gpla_template_list()
    {
      $patient_gpla_data = $this->session->userdata('patient_gpla_data');
      
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>DOG</th>
                    <th>Mode</th>
                    <th>Month Year</th>
                  </tr></thead><tbody>';  
           if(isset($patient_gpla_data) && !empty($patient_gpla_data))
           {
              $i = 1;

              foreach($patient_gpla_data as $key=>$patient_gpla_data_rec)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_gpla_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_gpla" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_gpla_data_rec['dog_value'].'</td>
                             <td>'.$patient_gpla_data_rec['mode_value'].'</td>
                             <td>'.$patient_gpla_data_rec['monthyear_value'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">GPLA are not available.</div></td>
                        </tr>';
           }
           $rows .= '</tbody></table>';

          
          return $rows;
    }

    public function edit($id="")
    {
        unauthorise_permission('314','1876');
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->patienttemplate->get_by_id($id); 
        $data['medicine_template_data'] = $this->patienttemplate->get_gynecology_medicine_prescription_template($id);
        $data['medicine_patient_template_data'] = $this->patienttemplate->get_gynecology_medicine_history_template($id);
        $data['relation_list'] = $this->patienttemplate->gynecology_realtion_list();
        $data['disease_list'] = $this->patienttemplate->gynecology_disease_list();
        $data['complaint_list'] = $this->patienttemplate->gynecology_complaint_list();
        $data['allergy_list'] = $this->patienttemplate->gynecology_allergy_list();
        $data['general_examination_list'] = $this->patienttemplate->gynecology_general_examination_list();
        $data['clinical_examination_list'] = $this->patienttemplate->gynecology_clinical_examination_list();
        $data['investigation_list'] = $this->patienttemplate->gynecology_investigation_list();
        $data['advice_list'] = $this->patienttemplate->gynecology_advice_list();
        $data['patient_gpla_data'] = $this->db->where('template_id',$id)->get('hms_gynecology_gpla_template')->result_array();

        $patient_history_db_data = $this->patienttemplate->get_patient_history_list($id);
        $patient_history_data = $this->session->userdata('patient_history_data');
        if(!isset($patient_history_data))
        {
          if(!empty($patient_history_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_history_db_data as $patient_history)
              {

               $phd_row[$i_row] = array('marriage_status'=>$patient_history['marriage_status'], 'married_life_unit'=>$patient_history['married_life_unit'],'married_life_type'=>$patient_history['married_life_type'],'marriage_no'=>$patient_history['marriage_no'],'marriage_details'=>$patient_history['marriage_details'],'previous_delivery'=>$patient_history['previous_delivery'],'delivery_type'=>$patient_history['delivery_type'],'delivery_details'=>$patient_history['delivery_details'],'unique_id'=>$i_row);
               $i_row++;
              } 
              $this->session->set_userdata('patient_history_data',$phd_row);
          }
        }

        $patient_family_history_db_data = $this->patienttemplate->get_patient_family_history_list($id);
        $patient_family_history_data = $this->session->userdata('patient_family_history_data');
        if(!isset($patient_family_history_data))
        {
          if(!empty($patient_family_history_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_family_history_db_data as $patient_family_history)
              {

                $phd_row[$i_row] = array('relation'=>$patient_family_history['relation'], 'disease'=>$patient_family_history['disease'],'family_description'=>$patient_family_history['family_description'],'family_duration_unit'=>$patient_family_history['family_duration_unit'],'family_duration_type'=>$patient_family_history['family_duration_type'],'relation_id'=>$patient_family_history['relation_id'],'disease_id'=>$patient_family_history['disease_id'],'unique_id_family_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_family_history_data',$phd_row);
          }
        }

        $family_personal_list = $this->patienttemplate->get_gynecology_personal_history_list($id);

        $patient_personal_history_data = $this->session->userdata('patient_personal_history_data');
        if(!isset($patient_personal_history_data))
        {
          if(!empty($family_personal_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($family_personal_list as $patient_personal_history)
              {

                $phd_row[$i_row] = array('br_discharge'=>$patient_personal_history['br_discharge'], 'side'=>$patient_personal_history['side'],'hirsutism'=>$patient_personal_history['hirsutism'],'white_discharge'=>$patient_personal_history['white_discharge'],'type'=>$patient_personal_history['type'],'frequency_personal'=>$patient_personal_history['frequency_personal'],'dyspareunia'=>$patient_personal_history['dyspareunia'],'personal_details'=>$patient_personal_history['personal_details'], 'unique_id_personal_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_personal_history_data',$phd_row);
          }
        }
        
        $menstrual_personal_list = $this->patienttemplate->get_gynecology_menstrual_history_list($id);

        $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
        if(!isset($patient_menstrual_history_data))
        {
          if(!empty($menstrual_personal_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($menstrual_personal_list as $patient_menstrual_history)
              {
                if(($patient_menstrual_history['lmp_date']=="01-01-1970")||($patient_menstrual_history['lmp_date']==""))
                {
                  $lmp_date = "";
                }
                else
                {
                  $lmp_date = date("d-m-Y",strtotime($patient_menstrual_history['lmp_date']));
                }

                $phd_row[$i_row] = array('previous_cycle'=>$patient_menstrual_history['previous_cycle'], 'prev_cycle_type'=>$patient_menstrual_history['prev_cycle_type'],'present_cycle'=>$patient_menstrual_history['present_cycle'],'present_cycle_type'=>$patient_menstrual_history['present_cycle_type'],'lmp_date'=>$lmp_date,'dysmenorrhea'=>$patient_menstrual_history['dysmenorrhea'],'dysmenorrhea_type'=>$patient_menstrual_history['dysmenorrhea_type'],'cycle_details'=>$patient_menstrual_history['cycle_details'], 'unique_id_menstrual_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_menstrual_history_data',$phd_row);
          }
        }

        $medical_list = $this->patienttemplate->get_gynecology_medical_history_list($id);

        $patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
        if(!isset($patient_medical_history_data))
        {
          if(!empty($medical_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($medical_list as $patient_medical_history)
              {

                $phd_row[$i_row] = array('tb'=>$patient_medical_history['tb'], 'tb_rx'=>$patient_medical_history['tb_rx'],'dm'=>$patient_medical_history['dm'],'dm_years'=>$patient_medical_history['dm_years'],'dm_rx'=>$patient_medical_history['dm_rx'],'ht'=>$patient_medical_history['ht'],'medical_details'=>$patient_medical_history['medical_details'],'medical_others'=>$patient_medical_history['medical_others'],'unique_id_medical_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_medical_history_data',$phd_row);
          }
        }

        $obestetric_list = $this->patienttemplate->get_gynecology_obestetric_history_list($id);

        $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
        if(!isset($patient_obestetric_history_data))
        {
          if(!empty($obestetric_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($obestetric_list as $patient_obestetric_history)
              {

                $phd_row[$i_row] = array('obestetric_g'=>$patient_obestetric_history['obestetric_g'], 'obestetric_p'=>$patient_obestetric_history['obestetric_p'],'obestetric_l'=>$patient_obestetric_history['obestetric_l'],'obestetric_mtp'=>$patient_obestetric_history['obestetric_mtp'],'unique_id_obestetric_history'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_obestetric_history_data',$phd_row);
          }
        }

        $disease_list = $this->patienttemplate->get_gynecology_disease_list($id);

        $patient_disease_data = $this->session->userdata('patient_disease_data');
        if(!isset($patient_disease_data))
        {
          if(!empty($disease_list))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($disease_list as $patient_disease_data)
              {

                $phd_row[$i_row] = array('patient_disease_id'=>$patient_disease_data['patient_disease_id'], 'disease_value'=>$patient_disease_data['disease_value'], 'patient_disease_unit'=>$patient_disease_data['patient_disease_unit'],'patient_disease_type'=>$patient_disease_data['patient_disease_type'],'disease_description'=>$patient_disease_data['disease_description'],'unique_id_patient_disease'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_disease_data',$phd_row);
          }
        }

        $patient_advice_db_data = $this->patienttemplate->get_patient_advice_list($id);

        $patient_advice_data = $this->session->userdata('patient_advice_data');
        if(!isset($patient_advice_data))
        {
          if(!empty($patient_advice_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_advice_db_data as $patient_advice_data)
              {

                $phd_row[$i_row] = array('patient_advice_id'=>$patient_advice_data['patient_advice_id'], 'advice_value'=>$patient_advice_data['advice_value'], 'unique_id_patient_advice'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_advice_data',$phd_row);
          }
        }
        
        $patient_investigation_db_data = $this->patienttemplate->get_patient_investigation_list($id);

        $patient_investigation_data = $this->session->userdata('patient_investigation_data');
        if(!isset($patient_investigation_data))
        {
            if(!empty($patient_investigation_db_data))
            { 
                $i_row = 1;
                $phd_row = []; 
                foreach($patient_investigation_db_data as $patient_investigation_data)
                {
                  if(!empty($patient_investigation_data['std_value']))
                  {
                    $patient_investigation_data['std_value'] = $patient_investigation_data['std_value'];
                  }
                  else
                  {
                    $patient_investigation_data['std_value'] = "";
                  }
                  if(!empty($patient_investigation_data['observed_value']))
                  {
                    $patient_investigation_data['observed_value'] = $patient_investigation_data['observed_value'];
                  }
                  else
                  {
                    $patient_investigation_data['observed_value'] = "";
                  }

                  $phd_row[$i_row] = array('patient_investigation_id'=>$patient_investigation_data['patient_investigation_id'], 'investigation_value'=>$patient_investigation_data['investigation_value'], 'std_value'=>$patient_investigation_data['std_value'], 'observed_value'=>$patient_investigation_data['observed_value'], 'unique_id_patient_investigation'=>$i_row);

                 $i_row++;
                } 
                $this->session->set_userdata('patient_investigation_data',$phd_row);
            }
        }
        
        $patient_clinical_examination_db_data = $this->patienttemplate->get_patient_clinical_examination_list($id);

        $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');

        if(!isset($patient_clinical_examination_data))
        {
          if(!empty($patient_clinical_examination_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_clinical_examination_db_data as $patient_clinical_examination_data)
              {

                $phd_row[$i_row] = array('patient_clinical_examination_id'=>$patient_clinical_examination_data['patient_clinical_examination_id'], 'clinical_examination_value'=>$patient_clinical_examination_data['clinical_examination_value'],'clinical_examination_description'=>$patient_clinical_examination_data['clinical_examination_description'],'unique_id_patient_clinical_examination'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_clinical_examination_data',$phd_row);
          }
        }
  
        $patient_general_examination_db_data = $this->patienttemplate->get_patient_general_examination_list($id);
        $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');

        if(!isset($patient_general_examination_data))
        {
          if(!empty($patient_general_examination_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_general_examination_db_data as $patient_general_examination_data)
              {

                $phd_row[$i_row] = array('patient_general_examination_id'=>$patient_general_examination_data['patient_general_examination_id'], 'general_examination_value'=>$patient_general_examination_data['general_examination_value'],'general_examination_description'=>$patient_general_examination_data['general_examination_description'],'unique_id_patient_general_examination'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_general_examination_data',$phd_row);
          }
        }

        $patient_allergy_db_data = $this->patienttemplate->get_patient_allergy_list($id);
        $patient_allergy_data = $this->session->userdata('patient_allergy_data');

        if(!isset($patient_allergy_data))
        {
          if(!empty($patient_allergy_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_allergy_db_data as $patient_allergy_data)
              {

                $phd_row[$i_row] = array('patient_allergy_id'=>$patient_allergy_data['patient_allergy_id'], 'allergy_value'=>$patient_allergy_data['allergy_value'], 'patient_allergy_unit'=>$patient_allergy_data['patient_allergy_unit'],'patient_allergy_type'=>$patient_allergy_data['patient_allergy_type'],'allergy_description'=>$patient_allergy_data['allergy_description'],'unique_id_patient_allergy'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_allergy_data',$phd_row);
          }
        }

        $patient_complaint_db_data = $this->patienttemplate->get_patient_complaint_list($id);
        $patient_complaint_data = $this->session->userdata('patient_complaint_data');

        if(!isset($patient_complaint_data))
        {
          if(!empty($patient_complaint_db_data))
          { 
              $i_row = 1;
              $phd_row = []; 
              foreach($patient_complaint_db_data as $patient_complaint_data)
              {

                $phd_row[$i_row] = array('patient_complaint_id'=>$patient_complaint_data['patient_complaint_id'], 'complaint_value'=>$patient_complaint_data['complaint_value'], 'patient_complaint_unit'=>$patient_complaint_data['patient_complaint_unit'],'patient_complaint_type'=>$patient_complaint_data['patient_complaint_type'],'complaint_description'=>$patient_complaint_data['complaint_description'],'unique_id_patient_complaint'=>$i_row);

               $i_row++;
              } 
              $this->session->set_userdata('patient_complaint_data',$phd_row);
          }
        }
        
        
        $data['page_title'] = "Gynecology Update Prescription Template";  
        $post = $this->input->post();
        $data['form_error'] = ''; 

        $next_appointmentdate='';
          if(!empty($result['appointment_date']) && ($result['appointment_date']!='0000-00-00 00:00:00') && (date('d-m-Y',strtotime($result['appointment_date']))!='01-01-1970'))
        {
            $next_appointmentdate = date('d-m-Y H:i A',strtotime($result['appointment_date']));
        }   
        else
        {
            $next_appointmentdate = ''; 
        }


        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'template_name'=>$result['template_name'], 
                                  'chief_complaint_id'=>"",
                                  'disease_id'=>"",
                                  'disease_details'=>"",
                                  'operation'=>"",
                                  'operation_date'=>"",
                                  'allergy_id'=>"",
                                  'habit_id'=>"",
                                  'reason'=>"",
                                  'treatment_id'=>"",
                                  'advice_id'=>"",
                                  'next_appointment_date'=>$next_appointmentdate,
                                  'check_appointment'=>$result['check_appointment'],
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->patienttemplate->save();

                $this->session->set_flashdata('success','Gynecology Prescription template updated successfully.');
                redirect(base_url('gynecology/patient_template'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

        $data['prescription_tab_setting'] = get_gynecology_patient_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_gynecology_prescription_medicine_tab_setting();
        $this->load->view('gynecology/patient_template/add_template',$data);       
      }
    }



   public function delete_pres_medicine($medicine_presc_id="",$template_id='')
   {
      if($medicine_presc_id>0)
      {
         $pres_temp_list = $this->patienttemplate->delete_pres_medicine($medicine_presc_id,$template_id);
         echo $pres_temp_list;
      }
    }

    private function _validate()
    {
        $post = $this->input->post();
        //print_r($post); exit;    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('template_name', 'Template Name', 'trim|required');
        $remark=''; 
        if(!empty($post['remark']))
        {
          $remark=$post['remark'];
        }
        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'template_name'=>$post['template_name'],
                                          'blood_group'=>'',
                                           'chief_complaint_id'=>'',
                                           'disease_details'=>'',
                                           'disease_id'=>'',
                                           'operation_date'=>'',
                                           'operation'=>'',
                                           'allergy_id'=>'',
                                           'reason'=>'',
                                           'habit_id'=>'',
                                           'diagnosis_id'=>'',
                                           'treatment_id'=>'',
                                           'advice_id'=>'',
                                           'check_appointment'=>'',
                                           'next_appointment_date'=>'',
                                        'remark'=>$remark,

                                       ); 
            return $data['form_data'];
        }   
    }


    
    
    public function archive()
    {
        unauthorise_permission('314','1878');
        $data['page_title'] = 'Gynecology Template Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/patient_template/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('314','1878');
        $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive'); 

        $list = $this->prescription_template_archive->get_datatables(); 
        //print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescriptiont) { 
            $no++;
            $row = array();
            if($prescriptiont->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$prescriptiont->id.'">'.$check_script; 
            $row[] = $prescriptiont->template_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($prescriptiont->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1880',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_prescription_template('.$prescriptiont->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1879',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$prescriptiont->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription_template_archive->count_all(),
                        "recordsFiltered" => $this->prescription_template_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function restore($id="")
    {
        unauthorise_permission('314','1880');
       $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_template_archive->restore($id);
           $response = "Gynecology Prescription Template successfully restore in list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('314','1880');
        $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_template_archive->restoreall($post['row_id']);
            $response = "Gynecology Prescription Template successfully restore in list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('314','1879');
       $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_template_archive->trash($id);
           $response = "Gynecology Prescription Template successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('314','1879');
        $this->load->model('gynecology/patient_template/patient_template_archive_model','prescription_template_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_template_archive->trashall($post['row_id']);
            $response = "Gynecology Prescription Template successfully deleted parmanently.";
            echo $response;
        }
    }



   
   function get_gynecology_medicine_auto_vals($vals="")
    {
         //echo "hi";die;
        if(!empty($vals))
        {
            $result = $this->patienttemplate->get_gynecology_medicine_auto_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

   function get_gynecology_dosage_vals($vals="")
    {
        //echo 'rwe';die;
        if(!empty($vals))
        {
            $result = $this->patienttemplate->get_gynecology_dosage_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

   function get_gynecology_duration_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->patienttemplate->get_gynecology_duration_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    function get_gynecology_frequency_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->patienttemplate->get_gynecology_frequency_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    } 
    function get_gynecology_advice_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->patienttemplate->get_gynecology_advice_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    function get_eye_diseases_data($branch_id="")
    {
      if($branch_id>0)
      {
        $diseases_data = $this->patienttemplate->get_eye_diseases_data($branch_id);
        echo $diseases_data;
      }
    } 

    function get_test_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->patienttemplate->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    } 
    public function patient_history_list()
    {
      //$this->session->unset_userdata('patient_history_data'); die;
      $post = $this->input->post();
      $patient_history_data = $this->session->userdata('patient_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_history_data = $this->session->userdata('patient_history_data'); 
        if(isset($patient_history_data) && !empty($patient_history_data))
        {
          $patient_history = $patient_history_data; 
        }
        else
        {
          $patient_history = [];
        }
        $patient_history[$post['unique_id']] = array('marriage_status'=>$post['marriage_status'], 'married_life_unit'=>$post['married_life_unit'],'married_life_type'=>$post['married_life_type'],'marriage_no'=>$post['marriage_no'],'marriage_details'=>$post['marriage_details'],'previous_delivery'=>$post['previous_delivery'],'delivery_type'=>$post['delivery_type'],'delivery_details'=>$post['delivery_details'],'rec_count'=>$post['rec_count'],'unique_id'=>$post['unique_id']);
        $this->session->set_userdata('patient_history_data', $patient_history);
        $html_data = $this->patient_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_history_template_list()
    {
      $patient_history_data = $this->session->userdata('patient_history_data');
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                      <th>Married</th>
                      <th>Married Life</th>
                      <th>Marriage No.</th>
                      <th>Married Details</th>
                      <th>Previous Delivery</th>
                      <th>Delivery Type</th>
                      <th>Delivery Details </th>
                  </tr></thead>';  
           if(isset($patient_history_data) && !empty($patient_history_data))
           {
              $i = 1;
              foreach($patient_history_data as $key=>$patienthistorydata)
              { 
                if (strpos($patienthistorydata['married_life_type'], 'Select') !== false) 
                {
                    $patienthistorydata['married_life_type'] = "";
                }
                else
                {
                  $patienthistorydata['married_life_type'] = $patienthistorydata['married_life_type'];
                }
                if (strpos($patienthistorydata['previous_delivery'], 'Select') !== false) 
                {
                    $patienthistorydata['previous_delivery'] = "";
                }
                else
                {
                  $patienthistorydata['previous_delivery'] = $patienthistorydata['previous_delivery'];
                }
                if (strpos($patienthistorydata['delivery_type'], 'Select') !== false) 
                {
                    $patienthistorydata['delivery_type'] = "";
                }
                else
                {
                  $patienthistorydata['delivery_type'] = $patienthistorydata['delivery_type'];
                }
                 $rows .= '<tr name="patient_history_row" id="'.$key.'">
                            <td width="60" align="center"><input type="checkbox" name="patient_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patienthistorydata['marriage_status'].'</td>
                            <td>'.$patienthistorydata['married_life_unit'].' '.$patienthistorydata['married_life_type'].'</td>
                            <td>'.$patienthistorydata['marriage_no'].'</td>
                            <td>'.$patienthistorydata['marriage_details'].'</td>
                             <td>'.$patienthistorydata['previous_delivery'].'  </td>
                             <td>'.$patienthistorydata['delivery_type'].'  </td>
                             <td>'.$patienthistorydata['delivery_details'].'  </td>
                            
                        </tr>';
                 $i++;                
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_family_history_list()
    {
      $post = $this->input->post();
      $patient_family_history_data = $this->session->userdata('patient_family_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_family_history_data = $this->session->userdata('patient_family_history_data'); 
        if(isset($patient_family_history_data) && !empty($patient_family_history_data))
        {
          $patient_history = $patient_family_history_data; 
        }
        else
        {
          $patient_history = [];
        }
        $patient_history[$post['unique_id_family_history']] = array('relation'=>$post['relation'], 'disease'=>$post['disease'],'family_description'=>$post['family_description'],'family_duration_unit'=>$post['family_duration_unit'],'family_duration_type'=>$post['family_duration_type'],'relation_id'=>$post['relation_id'],'disease_id'=>$post['disease_id'],'unique_id_family_history'=>$post['unique_id_family_history']);
        $this->session->set_userdata('patient_family_history_data', $patient_history);
        $html_data = $this->patient_family_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_family_history_template_list()
    {
      $patient_family_history_data = $this->session->userdata('patient_family_history_data');
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                      <th>Relation</th>
                      <th>Disease</th>
                      <th>Description</th>
                      <th>Duration</th>
                  </tr></thead>';  
           if(isset($patient_family_history_data) && !empty($patient_family_history_data))
           {
              $i = 1;
              foreach($patient_family_history_data as $key=>$patient_family_history_data)
              {
                if (strpos($patient_family_history_data['relation'], 'Select') !== false) 
                {
                    $patient_family_history_data['relation'] = "";
                }
                else
                {
                  $patient_family_history_data['relation'] = $patient_family_history_data['relation'];
                }
                if (strpos($patient_family_history_data['disease'], 'Select') !== false) 
                {
                    $patient_family_history_data['disease'] = "";
                }
                else
                {
                  $patient_family_history_data['disease'] = $patient_family_history_data['disease'];
                }
                if (strpos($patient_family_history_data['family_duration_type'], 'Select') !== false) 
                {
                    $patient_family_history_data['family_duration_type'] = "";
                }
                else
                {
                  $patient_family_history_data['family_duration_type'] = $patient_family_history_data['family_duration_type'];
                }
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_family_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_family_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_family_history_data['relation'].'</td>
                            <td>'.$patient_family_history_data['disease'].'</td>
                            <td>'.$patient_family_history_data['family_description'].'</td>
                            <td>'.$patient_family_history_data['family_duration_unit'].' '.$patient_family_history_data['family_duration_type'].'</td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_personal_history_list()
    {
      $post = $this->input->post();
      $patient_personal_history_data = $this->session->userdata('patient_personal_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_personal_history_data = $this->session->userdata('patient_personal_history_data'); 
        if(isset($patient_personal_history_data) && !empty($patient_personal_history_data))
        {
          $patient_history = $patient_personal_history_data; 
        }
        else
        {
          $patient_history = [];
        }
        $patient_history[$post['unique_id_personal_history']] = array('br_discharge'=>$post['br_discharge'], 'side'=>$post['side'],'hirsutism'=>$post['hirsutism'],'white_discharge'=>$post['white_discharge'],'type'=>$post['type'],'frequency_personal'=>$post['frequency_personal'],'dyspareunia'=>$post['dyspareunia'],'personal_details'=>$post['personal_details'], 'unique_id_personal_history'=>$post['unique_id_personal_history']);
        $this->session->set_userdata('patient_personal_history_data', $patient_history);
        $html_data = $this->patient_personal_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_personal_history_template_list()
    {
      $patient_personal_history_data = $this->session->userdata('patient_personal_history_data');
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                      <th>Breast Discharge</th>
                      <th>Side</th>
                      <th>Hirsutism</th>
                      <th>White Discharge</th>
                      <th>Type</th>
                      <th>Frequency</th>
                      <th>Dyspareunia</th>
                      <th>Details</th>
                  </tr></thead>';  
           if(isset($patient_personal_history_data) && !empty($patient_personal_history_data))
           {
              $i = 1;
              foreach($patient_personal_history_data as $key=>$patient_personal_history_data)
              {
                if (strpos($patient_personal_history_data['br_discharge'], 'Select') !== false) 
                {
                  $patient_personal_history_data['br_discharge'] = "";
                }
                else
                {
                  $patient_personal_history_data['br_discharge'] = $patient_personal_history_data['br_discharge'];
                }
                if (strpos($patient_personal_history_data['side'], 'Select') !== false) 
                {
                    $patient_personal_history_data['side'] = "";
                }
                else
                {
                  $patient_personal_history_data['side'] = $patient_personal_history_data['side'];
                }
                if (strpos($patient_personal_history_data['hirsutism'], 'Select') !== false) 
                {
                    $patient_personal_history_data['hirsutism'] = "";
                }
                else
                {
                  $patient_personal_history_data['hirsutism'] = $patient_personal_history_data['hirsutism'];
                }
                if (strpos($patient_personal_history_data['white_discharge'], 'Select') !== false) 
                {
                    $patient_personal_history_data['white_discharge'] = "";
                }
                else
                {
                  $patient_personal_history_data['white_discharge'] = $patient_personal_history_data['white_discharge'];
                }
                if (strpos($patient_personal_history_data['type'], 'Select') !== false) 
                {
                    $patient_personal_history_data['type'] = "";
                }
                else
                {
                  $patient_personal_history_data['type'] = $patient_personal_history_data['type'];
                }
                if (strpos($patient_personal_history_data['dyspareunia'], 'Select') !== false) 
                {
                    $patient_personal_history_data['dyspareunia'] = "";
                }
                else
                {
                  $patient_personal_history_data['dyspareunia'] = $patient_personal_history_data['dyspareunia'];
                }
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_personal_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_personal_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_personal_history_data['br_discharge'].'</td>
                            <td>'.$patient_personal_history_data['side'].'</td>
                            <td>'.$patient_personal_history_data['hirsutism'].'</td>
                            <td>'.$patient_personal_history_data['white_discharge'].'</td>
                            <td>'.$patient_personal_history_data['type'].'</td>
                            <td>'.$patient_personal_history_data['frequency_personal'].'</td>
                            <td>'.$patient_personal_history_data['dyspareunia'].'</td>
                            <td>'.$patient_personal_history_data['personal_details'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function patient_menstrual_history_list()
    {
      $post = $this->input->post();
      //echo $post['lmp_date'];die;
      $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data'); 
        if(isset($patient_menstrual_history_data) && !empty($patient_menstrual_history_data))
        {
          $patient_menstrual_history = $patient_menstrual_history_data; 
        }
        else
        {
          $patient_menstrual_history = [];
        }
        if(($post['lmp_date']=="01-01-1970")||($post['lmp_date']==""))
        {
          $lmp_date = "";

        }
        else
        {
          $lmp_date = $post['lmp_date'];
        }


       
        $patient_menstrual_history[$post['unique_id_menstrual_history']] = array('previous_cycle'=>$post['previous_cycle'], 'prev_cycle_type'=>$post['prev_cycle_type'],'present_cycle'=>$post['present_cycle'],'present_cycle_type'=>$post['present_cycle_type'],'lmp_date'=>$post['lmp_date'],'dysmenorrhea'=>$post['dysmenorrhea'],'dysmenorrhea_type'=>$post['dysmenorrhea_type'],'cycle_details'=>$post['cycle_details'],'unique_id_menstrual_history'=>$post['unique_id_menstrual_history']);
        $this->session->set_userdata('patient_menstrual_history_data', $patient_menstrual_history);
        $html_data = $this->patient_menstrual_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }

    private function patient_menstrual_history_template_list()
    {
      $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                      <th>Previous Cycle</th>
                      <th>Cycle Type</th>
                      <th>Present Cycle</th>
                      <th>Cycle Type</th>
                      <th>Details</th>
                      <th>LMP Date</th>
                      <th>Dysmenorrhea</th>
                      <th>Dysmenorrhea Type</th>
                  </tr></thead>';  
           if(isset($patient_menstrual_history_data) && !empty($patient_menstrual_history_data))
           {
              $i = 1;
              foreach($patient_menstrual_history_data as $key=>$patient_menstrual_history_data)
              {
                if (strpos($patient_menstrual_history_data['previous_cycle'], 'Select') !== false) 
                {
                  $patient_menstrual_history_data['previous_cycle'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['previous_cycle'] = $patient_menstrual_history_data['previous_cycle'];
                }
                if (strpos($patient_menstrual_history_data['prev_cycle_type'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['prev_cycle_type'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['prev_cycle_type'] = $patient_menstrual_history_data['prev_cycle_type'];
                }
                if (strpos($patient_menstrual_history_data['present_cycle'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['present_cycle'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['present_cycle'] = $patient_menstrual_history_data['present_cycle'];
                }
                if (strpos($patient_menstrual_history_data['present_cycle_type'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['present_cycle_type'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['present_cycle_type'] = $patient_menstrual_history_data['present_cycle_type'];
                }
                if (strpos($patient_menstrual_history_data['dysmenorrhea'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['dysmenorrhea'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['dysmenorrhea'] = $patient_menstrual_history_data['dysmenorrhea'];
                }
                if (strpos($patient_menstrual_history_data['dysmenorrhea_type'], 'Select') !== false) 
                {
                    $patient_menstrual_history_data['dysmenorrhea_type'] = "";
                }
                else
                {
                  $patient_menstrual_history_data['dysmenorrhea_type'] = $patient_menstrual_history_data['dysmenorrhea_type'];
                }
                if(($patient_menstrual_history_data['lmp_date']=="01-01-1970")||($patient_menstrual_history_data['lmp_date']==''))
                  $lmp_date = "";
                else
                $lmp_date = date("d-m-Y",strtotime($patient_menstrual_history_data['lmp_date'])); 
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_menstrual_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_menstrual_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_menstrual_history_data['previous_cycle'].'</td>
                            <td>'.$patient_menstrual_history_data['prev_cycle_type'].'</td>
                            <td>'.$patient_menstrual_history_data['present_cycle'].'</td>
                            <td>'.$patient_menstrual_history_data['present_cycle_type'].'</td>
                            <td>'.$patient_menstrual_history_data['cycle_details'].'</td>
                            <td>'.$lmp_date.'</td>
                            <td>'.$patient_menstrual_history_data['dysmenorrhea'].'</td>
                            <td>'.$patient_menstrual_history_data['dysmenorrhea_type'].'</td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function patient_medical_history_list()
    {

      //$this->session->unset_userdata('patient_personal_history_data');die;
      $post = $this->input->post();

      $patient_medical_history_data = $this->session->userdata('patient_medical_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_medical_history_data = $this->session->userdata('patient_medical_history_data'); 
        if(isset($patient_medical_history_data) && !empty($patient_medical_history_data))
        {
          $patient_medical_history = $patient_medical_history_data; 
        }
        else
        {
          $patient_medical_history = [];
        }
        $patient_medical_history[$post['unique_id_medical_history']] = array('tb'=>$post['tb'], 'tb_rx'=>$post['tb_rx'],'dm'=>$post['dm'],'dm_years'=>$post['dm_years'],'dm_rx'=>$post['dm_rx'],'ht'=>$post['ht'],'medical_details'=>$post['medical_details'],'medical_others'=>$post['medical_others'],'unique_id_medical_history'=>$post['unique_id_medical_history']);
        //print_r( $patient_medical_history);die;
        $this->session->set_userdata('patient_medical_history_data', $patient_medical_history);
        $html_data = $this->patient_medical_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_medical_history_template_list()
    {
      $patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                      <th>T.B</th>
                      <th>Rx</th>
                      <th>D.M</th>
                      <th>Years</th>
                      <th>Rx</th>
                      <th>H.T</th>
                      <th>Details</th>
                      <th>Others</th>
                  </tr></thead>';  
           if(isset($patient_medical_history_data) && !empty($patient_medical_history_data))
           {
              $i = 1;
              foreach($patient_medical_history_data as $key=>$patient_medical_history_data)
              {
                if (strpos($patient_medical_history_data['tb'], 'Select') !== false) 
                {
                  $patient_medical_history_data['tb'] = "";
                }
                else
                {
                  $patient_medical_history_data['tb'] = $patient_medical_history_data['tb'];
                }
                if (strpos($patient_medical_history_data['dm'], 'Select') !== false) 
                {
                    $patient_medical_history_data['dm'] = "";
                }
                else
                {
                  $patient_medical_history_data['dm'] = $patient_medical_history_data['dm'];
                }
                if (strpos($patient_medical_history_data['ht'], 'Select') !== false) 
                {
                    $patient_medical_history_data['ht'] = "";
                }
                else
                {
                  $patient_medical_history_data['ht'] = $patient_medical_history_data['ht'];
                }
                
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_medical_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_medical_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_medical_history_data['tb'].'</td>
                            <td>'.$patient_medical_history_data['tb_rx'].'</td>
                            <td>'.$patient_medical_history_data['dm'].'</td>
                            <td>'.$patient_medical_history_data['dm_years'].'</td>
                            <td>'.$patient_medical_history_data['dm_rx'].'</td>
                            <td>'.$patient_medical_history_data['ht'].'</td>
                            <td>'.$patient_medical_history_data['medical_details'].'</td>
                            <td>'.$patient_medical_history_data['medical_others'].'</td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_obestetric_history_list()
    {

      //$this->session->unset_userdata('patient_personal_history_data');die;
      $post = $this->input->post();

      $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data'); 
        if(isset($patient_obestetric_history_data) && !empty($patient_obestetric_history_data))
        {
          $patient_obestetric_history = $patient_obestetric_history_data; 
        }
        else
        {
          $patient_obestetric_history = [];
        }
        $patient_obestetric_history[$post['unique_id_obestetric_history']] = array('obestetric_g'=>$post['obestetric_g'], 'obestetric_p'=>$post['obestetric_p'],'obestetric_l'=>$post['obestetric_l'],'obestetric_mtp'=>$post['obestetric_mtp'],'unique_id_obestetric_history'=>$post['unique_id_obestetric_history']);
        //print_r( $patient_obestetric_history);die;
        $this->session->set_userdata('patient_obestetric_history_data', $patient_obestetric_history);
        $html_data = $this->patient_obestetric_history_template_list();
        //print_r($html_data);die;
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
        
     }
    }
    private function patient_obestetric_history_template_list()
    {
      $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                      <th>G</th>
                      <th>P</th>
                      <th>L</th>
                      <th>MTP</th>
                  </tr></thead>';  
           if(isset($patient_obestetric_history_data) && !empty($patient_obestetric_history_data))
           {
              $i = 1;
              foreach($patient_obestetric_history_data as $key=>$patient_obestetric_history_data)
              {
                
                
                 $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_obestetric_history[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_obestetric_history" value="'.$key.'">
                            <td>'.$i.'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_g'].'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_p'].'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_l'].'</td>
                            <td>'.$patient_obestetric_history_data['obestetric_mtp'].'</td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="7" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_disease_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_disease_data = $this->session->userdata('patient_disease_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_disease_data = $this->session->userdata('patient_disease_data'); 
        if(isset($patient_disease_data) && !empty($patient_disease_data))
        {
          $patient_disease = $patient_disease_data;
        }
        else
        {
          $patient_disease = [];
        }
       
        $patient_disease[$post['unique_id_patient_disease']] = array('patient_disease_id'=>$post['patient_disease_id'], 'disease_value'=>$post['disease_value'], 'patient_disease_unit'=>$post['patient_disease_unit'],'patient_disease_type'=>$post['patient_disease_type'],'disease_description'=>$post['disease_description'],'unique_id_patient_disease'=>$post['unique_id_patient_disease']);
        $this->session->set_userdata('patient_disease_data', $patient_disease);
        $html_data = $this->patient_disease_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
    }
  }

   private function patient_disease_template_list()
    {
      $patient_disease_data = $this->session->userdata('patient_disease_data');
      //print_r($patient_disease_data);die;
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Disease Name</th>
                    <th>Duration</th>
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_disease_data) && !empty($patient_disease_data))
           {
              $i = 1;
              foreach($patient_disease_data as $key=>$patient_disease_data_rec)
              {
                if (strpos($patient_disease_data_rec['patient_disease_type'], 'Select') !== false) 
                {
                    $patient_disease_data_rec['patient_disease_type'] = "";
                }
                else
                {
                  $patient_disease_data_rec['patient_disease_type'] = $patient_disease_data_rec['patient_disease_type'];
                }
                  $rows .= '<tr id="">
                            <td width="60" align="center"><input type="checkbox" name="patient_disease_name[]" class="part_checkbox booked_checkbox" value="'.$key.'"  ></td>
                            <input type="hidden" name="unique_id_patient_disease" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_disease_data_rec['disease_value'].'</td>
                            <td>'.$patient_disease_data_rec['patient_disease_unit'].' '.$patient_disease_data_rec['patient_disease_type'].'</td>
                            <td>'.$patient_disease_data_rec['disease_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_complaint_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_complaint_data = $this->session->userdata('patient_complaint_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_complaint_data = $this->session->userdata('patient_complaint_data'); 
        if(isset($patient_complaint_data) && !empty($patient_complaint_data))
        {
          $patient_complaint = $patient_complaint_data;
        }
        else
        {
          $patient_complaint = [];
        }
       
        $patient_complaint[$post['unique_id_patient_complaint']] = array('patient_complaint_id'=>$post['patient_complaint_id'], 'complaint_value'=>$post['complaint_value'], 'patient_complaint_unit'=>$post['patient_complaint_unit'],'patient_complaint_type'=>$post['patient_complaint_type'],'complaint_description'=>$post['complaint_description'],'unique_id_patient_complaint'=>$post['unique_id_patient_complaint']);
        $this->session->set_userdata('patient_complaint_data', $patient_complaint);
        $html_data = $this->patient_complaint_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_complaint_template_list()
    {
      $patient_complaint_data = $this->session->userdata('patient_complaint_data');
      //print_r($patient_complaint_data);die;
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Complaint Name</th>
                    <th>Duration</th>
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_complaint_data) && !empty($patient_complaint_data))
           {
              $i = 1;
              foreach($patient_complaint_data as $key=>$patient_complaint_data_rec)
              {
                if (strpos($patient_complaint_data_rec['patient_complaint_type'], 'Select') !== false) 
                {
                    $patient_complaint_data_rec['patient_complaint_type'] = "";
                }
                else
                {
                  $patient_complaint_data_rec['patient_complaint_type'] = $patient_complaint_data_rec['patient_complaint_type'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_complaint_name[]" class="part_checkbox booked_checkbox" value="'.$key.'"  ></td>
                            <input type="hidden" name="unique_id_patient_complaint" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_complaint_data_rec['complaint_value'].'</td>
                            <td>'.$patient_complaint_data_rec['patient_complaint_unit'].' '.$patient_complaint_data_rec['patient_complaint_type'].'</td>
                            <td>'.$patient_complaint_data_rec['complaint_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_allergy_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_allergy_data = $this->session->userdata('patient_allergy_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_allergy_data = $this->session->userdata('patient_allergy_data'); 
        if(isset($patient_allergy_data) && !empty($patient_allergy_data))
        {
          $patient_allergy = $patient_allergy_data;
        }
        else
        {
          $patient_allergy = [];
        }
       
        $patient_allergy[$post['unique_id_patient_allergy']] = array('patient_allergy_id'=>$post['patient_allergy_id'], 'allergy_value'=>$post['allergy_value'], 'patient_allergy_unit'=>$post['patient_allergy_unit'],'patient_allergy_type'=>$post['patient_allergy_type'],'allergy_description'=>$post['allergy_description'],'unique_id_patient_allergy'=>$post['unique_id_patient_allergy']);
        $this->session->set_userdata('patient_allergy_data', $patient_allergy);
        $html_data = $this->patient_allergy_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_allergy_template_list()
    {
      $patient_allergy_data = $this->session->userdata('patient_allergy_data');
      //print_r($patient_allergy_data);die;
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Allergy Name</th>
                    <th>Duration</th>
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_allergy_data) && !empty($patient_allergy_data))
           {
              $i = 1;
              foreach($patient_allergy_data as $key=>$patient_allergy_data_rec)
              {
                if (strpos($patient_allergy_data_rec['patient_allergy_type'], 'Select') !== false) 
                {
                    $patient_allergy_data_rec['patient_allergy_type'] = "";
                }
                else
                {
                  $patient_allergy_data_rec['patient_allergy_type'] = $patient_allergy_data_rec['patient_allergy_type'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_allergy_name[]" class="part_checkbox booked_checkbox" value="'.$key.'"  ></td>
                            <input type="hidden" name="unique_id_patient_allergy" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_allergy_data_rec['allergy_value'].'</td>
                            <td>'.$patient_allergy_data_rec['patient_allergy_unit'].' '.$patient_allergy_data_rec['patient_allergy_type'].'</td>
                            <td>'.$patient_allergy_data_rec['allergy_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_general_examination_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_general_examination_data = $this->session->userdata('patient_general_examination_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_general_examination_data = $this->session->userdata('patient_general_examination_data'); 
        if(isset($patient_general_examination_data) && !empty($patient_general_examination_data))
        {
          $patient_general_examination = $patient_general_examination_data;
        }
        else
        {
          $patient_general_examination = [];
        }
       
        $patient_general_examination[$post['unique_id_patient_general_examination']] = array('patient_general_examination_id'=>$post['patient_general_examination_id'], 'general_examination_value'=>$post['general_examination_value'],'general_examination_description'=>$post['general_examination_description'],'unique_id_patient_general_examination'=>$post['unique_id_patient_general_examination']);
        $this->session->set_userdata('patient_general_examination_data', $patient_general_examination);
        $html_data = $this->patient_general_examination_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_general_examination_template_list()
    {
      $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
      //print_r($patient_general_examination_data);die;
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Exam Name</th>
                  
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_general_examination_data) && !empty($patient_general_examination_data))
           {
              $i = 1;
              foreach($patient_general_examination_data as $key=>$patient_general_examination_data_rec)
              {
                
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_general_examination_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_general_examination" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_general_examination_data_rec['general_examination_value'].'</td>
                          
                            <td>'.$patient_general_examination_data_rec['general_examination_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_clinical_examination_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data'); 
        if(isset($patient_clinical_examination_data) && !empty($patient_clinical_examination_data))
        {
          $patient_clinical_examination = $patient_clinical_examination_data;
        }
        else
        {
          $patient_clinical_examination = [];
        }
       
        $patient_clinical_examination[$post['unique_id_patient_clinical_examination']] = array('patient_clinical_examination_id'=>$post['patient_clinical_examination_id'], 'clinical_examination_value'=>$post['clinical_examination_value'], 'clinical_examination_description'=>$post['clinical_examination_description'],'unique_id_patient_clinical_examination'=>$post['unique_id_patient_clinical_examination']);
        $this->session->set_userdata('patient_clinical_examination_data', $patient_clinical_examination);
        $html_data = $this->patient_clinical_examination_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_clinical_examination_template_list()
    {
      $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
      //print_r($patient_clinical_examination_data);die;
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Exam Name</th>
                    
                    <th>Description</th>
                  </tr></thead>';  
           if(isset($patient_clinical_examination_data) && !empty($patient_clinical_examination_data))
           {
              $i = 1;
              foreach($patient_clinical_examination_data as $key=>$patient_clinical_examination_data_rec)
              {
               
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_clinical_examination_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_clinical_examination" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_clinical_examination_data_rec['clinical_examination_value'].'</td>
                           
                            <td>'.$patient_clinical_examination_data_rec['clinical_examination_description'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_investigation_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_investigation_data = $this->session->userdata('patient_investigation_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_investigation_data = $this->session->userdata('patient_investigation_data'); 
        if(isset($patient_investigation_data) && !empty($patient_investigation_data))
        {
          $patient_investigation = $patient_investigation_data;
        }
        else
        {
          $patient_investigation = [];
        }
       
        $patient_investigation[$post['unique_id_patient_investigation']] = array('patient_investigation_id'=>$post['patient_investigation_id'], 'investigation_value'=>$post['investigation_value'], 'std_value'=>$post['std_value'], 'observed_value'=>$post['observed_value'], 'unique_id_patient_investigation'=>$post['unique_id_patient_investigation']);
        $this->session->set_userdata('patient_investigation_data', $patient_investigation);
        $html_data = $this->patient_investigation_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    public function get_std_value()
    {
      $investigation_id = $this->input->post('value');
      $std_value = $this->patienttemplate->get_std_value($investigation_id);
      echo $std_value[0]->std_value;
    }

    private function patient_investigation_template_list()
    {
      $patient_investigation_data = $this->session->userdata('patient_investigation_data');
      //print_r($patient_investigation_data);die;
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Investigation Name</th>
                    <th>Std. Value</th>
                    <th>Observed Value</th>
                  </tr></thead>';  
           if(isset($patient_investigation_data) && !empty($patient_investigation_data))
           {
              $i = 1;
              foreach($patient_investigation_data as $key=>$patient_investigation_data_rec)
              {
                if (strpos($patient_investigation_data_rec['investigation_value'], 'Select') !== false) 
                {
                    $patient_investigation_data_rec['investigation_value'] = "";
                }
                else
                {
                  $patient_investigation_data_rec['investigation_value'] = $patient_investigation_data_rec['investigation_value'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_investigation_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_investigation" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td>'.$patient_investigation_data_rec['investigation_value'].'</td>
                             <td>'.$patient_investigation_data_rec['std_value'].'</td>
                             <td>'.$patient_investigation_data_rec['observed_value'].'</td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function patient_advice_list()
    {
      //$this->session->unset_userdata('patient_disease_data');die;
      $post = $this->input->post();
      $patient_advice_data = $this->session->userdata('patient_advice_data'); 
      if(isset($post) && !empty($post))
      {   
        $patient_advice_data = $this->session->userdata('patient_advice_data'); 
        if(isset($patient_advice_data) && !empty($patient_advice_data))
        {
          $patient_advice = $patient_advice_data;
        }
        else
        {
          $patient_advice = [];
        }
        $this->db->where('id',$post['patient_advice_id']);
        $adv = $this->db->get('hms_gynecology_advice')->row_array()['advice'];
        
        $patient_advice[$post['unique_id_patient_advice']] = array('patient_advice_id'=>$post['patient_advice_id'], 'advice_value'=>$adv, 'unique_id_patient_advice'=>$post['unique_id_patient_advice']);
        $this->session->set_userdata('patient_advice_data', $patient_advice);
        $html_data = $this->patient_advice_template_list();
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
      }
    }

    private function patient_advice_template_list()
    {
      $patient_advice_data = $this->session->userdata('patient_advice_data');
      //print_r($patient_advice_data);die;
       $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              "; 
      $rows = '<thead class="bg-theme"><tr>       
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectall" onClick="toggle(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Advice Name</th>
                  </tr></thead>';  
           if(isset($patient_advice_data) && !empty($patient_advice_data))
           {
              $i = 1;
              foreach($patient_advice_data as $key=>$patient_advice_data_rec)
              {
                if (strpos($patient_advice_data_rec['advice_value'], 'Select') !== false) 
                {
                    $patient_advice_data_rec['advice_value'] = "";
                }
                else
                {
                  $patient_advice_data_rec['advice_value'] = $patient_advice_data_rec['advice_value'];
                }
                  $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="patient_advice_name[]" class="part_checkbox booked_checkbox" value="'.$key.'" ></td>
                            <input type="hidden" name="unique_id_patient_advice" value="'.$key.'">
                            <td>'.$i.'</td>
                             <td><div>'.$patient_advice_data_rec['advice_value'].'</div></td>
                          </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease are not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    
    // functions to delete table record from session   
    public function remove_gynecology_patient_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_history_vals'=>explode(',', $post['patient_history_vals']));
        if(isset($data['patient_history_vals']) && !empty($data['patient_history_vals']))
        {
          $patient_history_data = $this->session->userdata('patient_history_data');
          $patient_history_id_list = array_column($patient_history_data, 'unique_id');
          foreach($patient_history_data as $key=>$patient_history_ids)
          {
            if(in_array($patient_history_ids['unique_id'],$data['patient_history_vals']))
            {
               unset($patient_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_history_data',$patient_history_data);
          $html_data = $this->patient_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_family_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_family_history_vals'=>explode(',', $post['patient_family_history_vals']));
        if(isset($data['patient_family_history_vals']) && !empty($data['patient_family_history_vals']))
        {
          $patient_family_history_data = $this->session->userdata('patient_family_history_data');
          $patient_family_history_id_list = array_column($patient_family_history_data, 'unique_id_family_history');
          foreach($patient_family_history_data as $key=>$patient_family_history_ids)
          {
            if(in_array($patient_family_history_ids['unique_id_family_history'],$data['patient_family_history_vals']))
            {
               unset($patient_family_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_family_history_data',$patient_family_history_data);
          $html_data = $this->patient_family_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_personal_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_personal_history_vals'=>explode(',', $post['patient_personal_history_vals']));
        if(isset($data['patient_personal_history_vals']) && !empty($data['patient_personal_history_vals']))
        {
          $patient_personal_history_data = $this->session->userdata('patient_personal_history_data');
          $patient_personal_history_id_list = array_column($patient_personal_history_data, 'unique_id_personal_history');
          foreach($patient_personal_history_data as $key=>$patient_personal_history_ids)
          {
            if(in_array($patient_personal_history_ids['unique_id_personal_history'],$data['patient_personal_history_vals']))
            {
               unset($patient_personal_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_personal_history_data',$patient_personal_history_data);
          $html_data = $this->patient_personal_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_menstrual_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_menstrual_history_vals'=>explode(',', $post['patient_menstrual_history_vals']));
        if(isset($data['patient_menstrual_history_vals']) && !empty($data['patient_menstrual_history_vals']))
        {
          $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
          $patient_menstrual_history_id_list = array_column($patient_menstrual_history_data, 'unique_id_menstrual_history');
          foreach($patient_menstrual_history_data as $key=>$patient_menstrual_history_ids)
          {
            if(in_array($patient_menstrual_history_ids['unique_id_menstrual_history'],$data['patient_menstrual_history_vals']))
            {
               unset($patient_menstrual_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_menstrual_history_data',$patient_menstrual_history_data);
          $html_data = $this->patient_menstrual_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_medical_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_medical_history_vals'=>explode(',', $post['patient_medical_history_vals']));
        if(isset($data['patient_medical_history_vals']) && !empty($data['patient_medical_history_vals']))
        {
          $patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
          $patient_medical_history_id_list = array_column($patient_medical_history_data, 'unique_id_medical_history');
          foreach($patient_medical_history_data as $key=>$patient_medical_history_ids)
          {
            if(in_array($patient_medical_history_ids['unique_id_medical_history'],$data['patient_medical_history_vals']))
            {
               unset($patient_medical_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_medical_history_data',$patient_medical_history_data);
          $html_data = $this->patient_medical_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_obestetric_history()
    {    
        $post =  $this->input->post();
        $data = array('patient_obestetric_history_vals'=>explode(',', $post['patient_obestetric_history_vals']));
        if(isset($data['patient_obestetric_history_vals']) && !empty($data['patient_obestetric_history_vals']))
        {
          $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
          $patient_obestetric_history_id_list = array_column($patient_obestetric_history_data, 'unique_id_obestetric_history');
          foreach($patient_obestetric_history_data as $key=>$patient_obestetric_history_ids)
          {
            if(in_array($patient_obestetric_history_ids['unique_id_obestetric_history'],$data['patient_obestetric_history_vals']))
            {
               unset($patient_obestetric_history_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_obestetric_history_data',$patient_obestetric_history_data);
          $html_data = $this->patient_obestetric_history_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_disease()
    {    
        $post =  $this->input->post();
        $data = array('patient_disease_vals'=>explode(',', $post['patient_disease_vals']));
        if(isset($data['patient_disease_vals']) && !empty($data['patient_disease_vals']))
        {
          $patient_disease_data = $this->session->userdata('patient_disease_data');
          $patient_disease_id_list = array_column($patient_disease_data, 'unique_id_patient_disease');
          foreach($patient_disease_data as $key=>$patient_disease_ids)
          {
            if(in_array($patient_disease_ids['unique_id_patient_disease'],$data['patient_disease_vals']))
            {
               unset($patient_disease_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_disease_data',$patient_disease_data);
          $html_data = $this->patient_disease_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_complaint()
    {    
        $post =  $this->input->post();
        $data = array('patient_complaint_vals'=>explode(',', $post['patient_complaint_vals']));
        if(isset($data['patient_complaint_vals']) && !empty($data['patient_complaint_vals']))
        {
          $patient_complaint_data = $this->session->userdata('patient_complaint_data');
          $patient_complaint_id_list = array_column($patient_complaint_data, 'unique_id_patient_complaint');
          foreach($patient_complaint_data as $key=>$patient_complaint_ids)
          {
            if(in_array($patient_complaint_ids['unique_id_patient_complaint'],$data['patient_complaint_vals']))
            {
               unset($patient_complaint_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_complaint_data',$patient_complaint_data);
          $html_data = $this->patient_complaint_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_allergy()
    {    
        $post =  $this->input->post();
        $data = array('patient_allergy_vals'=>explode(',', $post['patient_allergy_vals']));
        if(isset($data['patient_allergy_vals']) && !empty($data['patient_allergy_vals']))
        {
          $patient_allergy_data = $this->session->userdata('patient_allergy_data');
          $patient_allergy_id_list = array_column($patient_allergy_data, 'unique_id_patient_allergy');
          foreach($patient_allergy_data as $key=>$patient_allergy_ids)
          {
            if(in_array($patient_allergy_ids['unique_id_patient_allergy'],$data['patient_allergy_vals']))
            {
               unset($patient_allergy_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_allergy_data',$patient_allergy_data);
          $html_data = $this->patient_allergy_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_general_examination()
    {    
        $post =  $this->input->post();
        $data = array('patient_general_examination_vals'=>explode(',', $post['patient_general_examination_vals']));
        if(isset($data['patient_general_examination_vals']) && !empty($data['patient_general_examination_vals']))
        {
          $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
          $patient_general_examination_id_list = array_column($patient_general_examination_data, 'unique_id_patient_general_examination');
          foreach($patient_general_examination_data as $key=>$patient_general_examination_ids)
          {
            if(in_array($patient_general_examination_ids['unique_id_patient_general_examination'],$data['patient_general_examination_vals']))
            {
               unset($patient_general_examination_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_general_examination_data',$patient_general_examination_data);
          $html_data = $this->patient_general_examination_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_clinical_examination()
    {    
        $post =  $this->input->post();
        $data = array('patient_clinical_examination_vals'=>explode(',', $post['patient_clinical_examination_vals']));
        if(isset($data['patient_clinical_examination_vals']) && !empty($data['patient_clinical_examination_vals']))
        {
          $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
          $patient_clinical_examination_id_list = array_column($patient_clinical_examination_data, 'unique_id_patient_clinical_examination');
          foreach($patient_clinical_examination_data as $key=>$patient_clinical_examination_ids)
          {
            if(in_array($patient_clinical_examination_ids['unique_id_patient_clinical_examination'],$data['patient_clinical_examination_vals']))
            {
               unset($patient_clinical_examination_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_clinical_examination_data',$patient_clinical_examination_data);
          $html_data = $this->patient_clinical_examination_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_investigation()
    {    
        $post =  $this->input->post();
        $data = array('patient_investigation_vals'=>explode(',', $post['patient_investigation_vals']));
        if(isset($data['patient_investigation_vals']) && !empty($data['patient_investigation_vals']))
        {
          $patient_investigation_data = $this->session->userdata('patient_investigation_data');
          $patient_investigation_id_list = array_column($patient_investigation_data, 'unique_id_patient_investigation');
          foreach($patient_investigation_data as $key=>$patient_investigation_ids)
          {
            if(in_array($patient_investigation_ids['unique_id_patient_investigation'],$data['patient_investigation_vals']))
            {
               unset($patient_investigation_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_investigation_data',$patient_investigation_data);
          $html_data = $this->patient_investigation_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }

    public function remove_gynecology_patient_advice()
    {    
        $post =  $this->input->post();
        $data = array('patient_advice_vals'=>explode(',', $post['patient_advice_vals']));
        if(isset($data['patient_advice_vals']) && !empty($data['patient_advice_vals']))
        {
          $patient_advice_data = $this->session->userdata('patient_advice_data');
          $patient_advice_id_list = array_column($patient_advice_data, 'unique_id_patient_advice');
          foreach($patient_advice_data as $key=>$patient_advice_ids)
          {
            if(in_array($patient_advice_ids['unique_id_patient_advice'],$data['patient_advice_vals']))
            {
               unset($patient_advice_data[$key]);
            }
          }  
          $this->session->set_userdata('patient_advice_data',$patient_advice_data);
          $html_data = $this->patient_advice_template_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;
       
        }
    }
 
   
}
