<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_booking extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
    $this->load->model('pain_score/Pain_score_model','pain_score');
        $this->load->model('nutritional_screening/Nutritional_screening_model','nutritional_screening');
        $this->load->model('plan_of_care/Plan_of_care_model','plan_of_care');
        $this->load->model('advice_master/Advice_master_model','advice_master');
        $this->load->model('initial_assessment_performed_by_doctor/Initial_assessment_performed_by_doctor_model','initial_assessment_performed_by_doctor');
		$this->load->library('form_validation');
  }

  public function fill_medication_chart($booking_id="")
  {
    
    $data['prescription_tab_setting'] = get_ipd_prescription_tab_setting();
    $data['prescription_medicine_tab_setting'] = get_ipd_prescription_medicine_tab_setting();
    $this->load->model('opd/opd_model','opd');
    $this->load->model('general/general_model'); 
    
    $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
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
    
    if($booking_id>0)
    {
     $this->load->model('ipd_booking/ipd_booking_model');
     $this->load->model('patient/patient_model');
     $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
         //echo "<pre>";print_r($opd_booking_data); exit;
     if(!empty($ipd_booking_data))
     {
       
             //present age of patient
       
      if($ipd_booking_data['dob']=='1970-01-01' || $ipd_booking_data['dob']=="0000-00-00")
      {
        $present_age = get_patient_present_age('',$ipd_booking_data);
              //echo "<pre>"; print_r($present_age);
      }
      else
      {
        $dob=date('d-m-Y',strtotime($ipd_booking_data['dob']));
        
        $present_age = get_patient_present_age($dob,$ipd_booking_data);
      }
      
      $age_y = $ipd_booking_data['age_y'];
      $age_m = $ipd_booking_data['age_m'];
      $age_d = $ipd_booking_data['age_d'];
            //$age_h = $present_age['age_h'];
            //present age of patient
      $booking_id = $ipd_booking_data['id'];
      $mlc_no = $ipd_booking_data['mlc'];
      $referral_doctor = $ipd_booking_data['referral_doctor'];
      $ipd_no = $ipd_booking_data['ipd_no'];
      $attended_doctor = $ipd_booking_data['attend_doctor_id'];
            $patient_id = $ipd_booking_data['patient_id'];//
            $simulation_id = $ipd_booking_data['simulation_id'];
            $patient_code = $ipd_booking_data['patient_code'];
            $patient_name = $ipd_booking_data['patient_name'];
            $mobile_no = $ipd_booking_data['mobile_no'];
            $gender = $ipd_booking_data['gender'];
            
            $address = $ipd_booking_data['address'];
            $city_id = $ipd_booking_data['city_id'];
            $state_id = $ipd_booking_data['state_id'];
            $country_id = $ipd_booking_data['country_id']; 
            $appointment_date = '';
            
            $relation_name = $ipd_booking_data['relation_name'];
            $relation_type = $ipd_booking_data['relation_type'];
            $relation_simulation_id = $ipd_booking_data['relation_simulation_id'];
            
            $aadhaar_no ='';
            if(!empty($ipd_booking_data['adhar_no']))
            {
              $aadhaar_no = $ipd_booking_data['adhar_no'];
            }

            $patient_bp = '';
            $patient_temp = '';
            $patient_weight = '';
            $patient_height = '';
            $patient_spo2 = '';
            $patient_rbs = '';
          }


        }
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['employee_list'] = $this->opd->employee_list();
        $data['profile_list'] = $this->opd->profile_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['chief_complaints'] = $this->general_model->chief_complaint_list(); 
        $data['examination_list'] = $this->opd->examinations_list();
        $data['diagnosis_list'] = $this->opd->diagnosis_list();  
        $data['suggetion_list'] = $this->opd->suggetion_list();  
        $data['prv_history'] = $this->opd->prv_history_list();  
        $data['personal_history'] = $this->opd->personal_history_list();
        $data['template_list'] = $this->opd->template_list();    
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['page_title'] = "Fill Medication Chart";
        
        $post = $this->input->post();
        
        $data['form_error'] = []; 
        $data['form_data'] = array(
          'data_id'=>"", 
          'patient_id'=>$patient_id,
          'booking_id'=>$booking_id,
          'attended_doctor'=>$attended_doctor,
          'appointment_date'=>$appointment_date,
          'patient_code'=>$patient_code,
          'ipd_no'=>$ipd_no,
          'mlc_no'=>$mlc_no,
          'simulation_id'=>$simulation_id,
          'patient_name'=>$patient_name,
          "aadhaar_no"=>$aadhaar_no,
          'mobile_no'=>$mobile_no,
          'gender'=>$gender,
          'age_y'=>$age_y,
          'age_m'=>$age_m,
          'age_d'=>$age_d,
          
          'address'=>$address,
          'city_id'=>$city_id,
          'state_id'=>$state_id,
          'country_id'=>$country_id,
          
          'prv_history'=>"",
          'personal_history'=>"",
          'chief_complaints'=>'',
          'examination'=>'',
          'diagnosis'=>'',
          'suggestion'=>'',
          'remark'=>'',
          'next_appointment_date'=>"",
          "relation_name"=>$relation_name,
          "relation_type"=>$relation_type,
          "relation_simulation_id"=>$relation_simulation_id,
        );
        if(isset($post) && !empty($post))
        {   
          
          $this->ipd_booking->save_medication_chart();
          // $this->session->set_userdata('ipd_prescription_id',$prescription_id);
          $this->session->set_flashdata('success','Medication chart successfully added.');
          // redirect(base_url('ipd_booking/?status=print'));
          redirect(base_url('ipd_booking'));


        }   
        // $data['medication_chart_list'] = $this->db->where(['booking_id'=>$booking_id,'patient_id'=>$patient_id])->get('hms_ipd_medication_chart')->result_array();

        $this->load->view('ipd_booking/fill_medication_chart',$data);
  }

  public function print_medication_chart($booking_id)
  {
    $data = [];
    $this->load->model('ipd_booking/ipd_booking_model');
     $this->load->model('patient/patient_model');
    $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
    $patient_id = $ipd_booking_data['patient_id'];
    $data['medication_chart_list'] = $this->db->where(['booking_id'=>$booking_id,'patient_id'=>$patient_id])->get('hms_ipd_medication_chart')->result_array();
    $data['data'] = $ipd_booking_data; //attend_doctor_id
    $data['doctor'] = get_doctor_signature($ipd_booking_data['attend_doctor_id']);
    $this->load->model('general/general_model');  
    $data['panel_company_name'] = $this->general_model->panel_company_details($data['data']['panel_name']);
    dd($data['panel_company_name']);
    $this->load->view('ipd_booking/print_medication_chart',$data);
  }

  
  public function index()
  {
    unauthorise_permission(121,733);
        //$this->session->unset_userdata('ipd_booking_id'); 
    $this->session->unset_userdata('net_values_all');
    $this->session->unset_userdata('ipd_particular_charge_billing');
    $this->session->unset_userdata('ipd_particular_payment');
    $this->session->unset_userdata('ipd_advance_payment');
        //$this->session->userdata('ipd_particular_billing') 
    $data['page_title'] = 'IPD Booking List';
        // Default  Search Setting
    $this->load->model('default_search_setting/default_search_setting_model'); 
    $default_search_data = $this->default_search_setting_model->get_default_setting();
    if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
    {
      $start_date = '';
      $end_date = '';
    }
    else
    {
      $start_date = date('d-m-Y');
      $end_date = date('d-m-Y');
    }
        // End Defaul Search
    
    $data['form_data'] = array('patient_name'=>'','patient_type'=>'','mobile_no'=>'','ipd_no'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'running'=>0,'mlc_no'=>''); 
    $this->session->set_userdata('ipd_booking_search', $data['form_data']);
    $this->load->view('ipd_booking/list',$data);
  }

  public function ajax_list()
  {  
    unauthorise_permission(121,733);
    $list = $this->ipd_booking->get_datatables();  
    
    $data = array();
    $no = $_POST['start'];
    $i = 1;
    $total_num = count($list);
        //$row='';
    foreach ($list as $ipd_booking) 
    { 

      $no++;
      $row = array();
            ////////// Check  List /////////////////
      $check_script = "";
      if($i==$total_num)
      {

        
      }          
      $row[] = '<input type="checkbox" name="ipd_booking[]" class="checklist" value="'.$ipd_booking->id.'">';  
      $row[] = $ipd_booking->ipd_no;
      $row[] = $ipd_booking->patient_code;
      $row[] = $ipd_booking->patient_name;
            //$row[] = $purchase->total_amount;
      $row[] = $ipd_booking->mobile_no;
      $time = "";
      if(date('h:i:s',strtotime($ipd_booking->admission_date. $ipd_booking->admission_time))!='12:00:00')
      {
        $time = date('h:i A',strtotime($ipd_booking->admission_date. $ipd_booking->admission_time));
      }
            $row[] = date('d-m-Y',strtotime($ipd_booking->admission_date. $ipd_booking->admission_time)).' '.$time; //$ipd_booking->admission_date;
            $row[] = "Dr. ".$ipd_booking->doctor_name;
            $row[] = $ipd_booking->room_no;
            $row[] = $ipd_booking->bad_name;
            $row[] = $ipd_booking->address;
            $row[] = $ipd_booking->remarks;
            

            $row[] = $ipd_booking->father_husband_simulation." ".$ipd_booking->father_husband;
            
            $age_y = $ipd_booking->age_y;
            $age_m = $ipd_booking->age_m;
            $age_d = $ipd_booking->age_d;
            $age_h = $ipd_booking->age_h;
            $age = "";
            if($age_y>0)
            {
              $year = 'Years';
              if($age_y==1)
              {
                $year = 'Year';
              }
              $age .= $age_y." ".$year;
            }
            if($age_m>0)
            {
              $month = 'Months';
              if($age_m==1)
              {
                $month = 'Month';
              }
              $age .= ", ".$age_m." ".$month;
            }
            if($age_d>0)
            {
              $day = 'Days';
              if($age_d==1)
              {
                $day = 'Day';
              }
              $age .= ", ".$age_d." ".$day;
            }
            if($age_h>0)
            {
              $hours = 'Hours';
              
              $age .= " ".$age_h." ".$hours;
            } 
            ///////////////////////////////////////
            
            
            
            $row[] = $ipd_booking->gender.'/'.$age;
            $row[] = $ipd_booking->patient_email;
            $row[] = $ipd_booking->insurance_type;
            $row[] = $ipd_booking->insurance_company;
            
            $row[] = $ipd_booking->mlc;
            $row[] = $ipd_booking->package_name;
            $row[] = $ipd_booking->room_type;
            $row[] = $ipd_booking->doctor_hospital_name;
            $row[] = $ipd_booking->advance_payment;
            $row[] = $ipd_booking->reg_charge;
            $row[] = $ipd_booking->panel_polocy_no;
            $row[] = str_replace(';','; ',$ipd_booking->diagnosis);
            
            if($ipd_booking->discharge_date =='0000-00-00 00:00:00' || empty($ipd_booking->discharge_date))
            {
            	$row[] ='';	
            }
            else
            {
            	$row[] = date('d-M-Y h:i A',strtotime($ipd_booking->discharge_date));
            }
            
            
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
            $btnprint='';
            $btn_admission_print='';
            if(in_array('735',$users_data['permission']['action']))
            {
              $btnedit = ' <a onClick="return edit_ipd_booking('.$ipd_booking->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if(in_array('736',$users_data['permission']['action']))
            {
              $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_booking('.$ipd_booking->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }  
            
            $print_pdf_url = "'".base_url('ipd_booking/print_ipd_booking_recipt/'.$ipd_booking->id)."'";
            $btnprint = '<li><a  onclick="print_window_page('.$print_pdf_url.');" target="_blank"><i class="fa fa-print"></i> Print</a></li>';
            
            
            $print_barcode_url = "'".base_url('ipd_booking/print_barcode/'.$ipd_booking->id)."'";
            $btn_barcode = '<li><a   href="javascript:void(0)" onClick="return print_window_page('.$print_barcode_url.')"  title="Print Barcode" ><i class="fa fa-barcode"></i> Print Barcode </a></li>';

            $print_nurses_notes = "'".base_url('ipd_booking/print_nurses_notes/'.$ipd_booking->id)."'";
            $btn_medication_chart_fill = '<li><a   href="ipd_booking/fill_medication_chart/'.$ipd_booking->id.'"  title="Fill Medication Chart" ><i class="fa fa-bar-chart"></i> Add Medication Chart </a></li>';


            $btn_medication_chart_fill .= '<li><a   href="'.base_url("ipd_booking/nurses_notes/").$ipd_booking->id.'"  title="Nurses Notes" ><i class="fa fa-info"></i> Nurse\'s Notes </a></li>';
            $btn_medication_chart_fill .= '<li><a   href="javascript:void(0)" onClick="return print_window_page('.$print_nurses_notes.')"  title="Print Nurses Notes" ><i class="fa fa-bar-chart"></i> Print Nurse\'s Notes </a></li>';
            $btn_medication_chart_fill .= '<li><a   href="'.base_url("nurse_handover/add/").$ipd_booking->id.'"  title="Nurse Handover" ><i class="fa fa-hand-lizard-o"></i> Nurse Handover</a></li>';
            $btn_medication_chart_fill .= '<li><a   href="'.base_url("doctor_handover/add/").$ipd_booking->id.'"  title="Doctor Handover" ><i class="fa fa-hand-lizard-o"></i> Doctor Handover</a></li>';
            
            $print_admission_pdf_url = "'".base_url('ipd_booking/print_ipd_adminssion_card/'.$ipd_booking->id)."'";
            
            $btn_admission_print = '<li><a  onclick="print_window_page('.$print_admission_pdf_url.');" target="_blank"><i class="fa fa-print"></i> Print Admission</a></li>';
            $btnadavancepayment="";
            $btnroom_transfer="";
            $btncharge_entry="";
            $btndischarge_bill="";
            $btngipsadischarge_bill="";
            
            if($users_data['parent_id']=='113')
            {
              $btn_admission_consent_print = '<li><a  onclick="print_add_consent('.$ipd_booking->id.')"><i class="fa fa-print"></i> Print Admission Consent</a></li>';
            }
            else
            {
              $btn_admission_consent_print = '';
            }
            
            $btnprintmlc = ''; 
            if(!empty($ipd_booking->mlc))
            {
              $btnprintmlc = '<li><a  onclick="print_mlc_print('.$ipd_booking->id.')"><i class="fa fa-print"></i> Print MLC Form </a></li>';
              
            }
            
            if(in_array('774',$users_data['permission']['action']))
            {
              $btnadavancepayment = '<li><a   href='.base_url('advance_payment/add/'.$ipd_booking->id.'/'.$ipd_booking->patient_id).' title="Advance Payment" data-url="512"> <i class="fa fa-inr" aria-hidden="true"></i> Advance Payment</a></li>';
            }
            if(in_array('740',$users_data['permission']['action']))
            {
              $btnroom_transfer = '<li><a   href='.base_url('ipd_room_transfer/add/'.$ipd_booking->id.'/'.$ipd_booking->patient_id).' title="Room Transfer" data-url="512"> <i class="fa fa-hospital-o" aria-hidden="true"></i> Room Transfer</a></li> ';
            }
            if(in_array('777',$users_data['permission']['action']))
            {
              $btncharge_entry = ' <li><a   href='.base_url('ipd_charge_entry/add/'.$ipd_booking->id.'/'.$ipd_booking->patient_id).' title="Charge Entry" data-url="512"> <i class="fa fa-keyboard-o" aria-hidden="true"></i> Charge Entry</a></li>';
            }
            $btndischarge_summary='';
            if(in_array('750',$users_data['permission']['action']))
            { 

              $btndischarge_summary = '<li><a  href='.base_url('ipd_patient_discharge_summary/add/'.$ipd_booking->id.'/'.$ipd_booking->patient_id).' title="Discharge Summary" data-url="512"> <i class="fa fa-bed" aria-hidden="true"></i> Discharge Summary</a></li>';
              
              
            }
            
            $btnadvdischarge_summary='';
            if($users_data['parent_id']=='113')
            { 

              $btnadvdischarge_summary = '<li><a  href='.base_url('ipd_patient_advance_discharge_summary/add/'.$ipd_booking->id.'/'.$ipd_booking->patient_id).' title="Discharge Summary" data-url="512"> <i class="fa fa-bed" aria-hidden="true"></i> Adv. Discharge Summary</a></li>';
              
              
            }
            else if(in_array('2521',$users_data['permission']['action']))
            {
                $btnadvdischarge_summary = '<li><a  href='.base_url('ipd_patient_advance_discharge_summary/add/'.$ipd_booking->id.'/'.$ipd_booking->patient_id).' title="Discharge Summary" data-url="512"> <i class="fa fa-bed" aria-hidden="true"></i> Adv. Discharge Summary</a></li>';
            }
            $btndisprogress_report=''; 
            if(in_array('743',$users_data['permission']['action']))
            {
              //$btndisprogress_report = '<li><a  href='.base_url('ipd_progress_report/add/'.$ipd_booking->patient_id.'/'.$ipd_booking->id).' title="Add Progress Report" data-url="512"> <i class="fa fa-file-text-o" aria-hidden="true"></i> Add Daily Notes Report</a></li>';
              
              /*if($users_data['parent_id']=='110')
              {
                $btndisprogress_report = '<li><a  href='.base_url('ipd_booking/prescription/'.$ipd_booking->id).' title="Add Progress Report" data-url="512"> <i class="fa fa-file-text-o" aria-hidden="true"></i> Add Progress Report</a></li>';
              }
              elseif($users_data['parent_id']>115)
              {*/
                $btndisprogress_report .= '<li><a  href='.base_url('ipd_booking/prescription/'.$ipd_booking->id).' title="Add Daily Notes Report" data-url="512"> <i class="fa fa-file-text-o" aria-hidden="true"></i> Daily Notes Report</a></li>'; 
              //}
              /*else
              {
                $btndisprogress_report = '<li><a  href='.base_url('ipd_progress_report/add/'.$ipd_booking->patient_id.'/'.$ipd_booking->id).' title="Add Progress Report" data-url="512"> <i class="fa fa-file-text-o" aria-hidden="true"></i> Add Progress Report</a></li>';
              }*/
              
              $btndisprogress_report .= '<li><a  href='.base_url('ipd_booking/admission_prescription/'.$ipd_booking->id).' title="Add Initial Assessment" data-url="512"> <i class="fa fa-file-text-o" aria-hidden="true"></i> Initial Assessment</a></li>';
              
              $btndisprogress_report .= '<li><a  href='.base_url('ipd_booking/nursing_prescription/'.$ipd_booking->id).' title="Add Nursing Care Plan" data-url="512"> <i class="fa fa-file-text-o" aria-hidden="true"></i> Nursing Care Plan</a></li>';
              
            }
            
            $btngipsadischarge_summary='';
            if(in_array('750',$users_data['permission']['action']))
            { 

              $btngipsadischarge_summary = '<li><a  href='.base_url('gipsa_ipd_patient_discharge_summary/add/'.$ipd_booking->id.'/'.$ipd_booking->patient_id).' title="Discharge Summary" data-url="512"> <i class="fa fa-bed" aria-hidden="true"></i> Gipsa Discharge Summary</a></li>';
              
              
            }
            
            $btn_print_progress='';
       
            $print_discharge_url = "'".base_url('ipd_discharge_bill/print_discharge_bill/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";
            $btn_print_discharge = ' <li><a  onClick="return print_window_page('.$print_discharge_url.')" href="javascript:void(0)" title="Print Discharge Bill"  data-url="512"> <i class="fa fa-print" aria-hidden="true"></i>  Print Discharge Bill</a></li>';

            $print_final_receipt_url = "'".base_url('ipd_discharge_bill/print_discharge_bill_paidamount/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";

            $btn_final_receipt = ' <li><a  onClick="return print_window_page('.$print_final_receipt_url.')" href="javascript:void(0)" title="Print Discharge Bill"  data-url="512"> <i class="fa fa-print" aria-hidden="true"></i>  Print Discharge Receipt</a></li>';
            
            
            $print_letterhead_discharge_url = "'".base_url('ipd_discharge_bill/print_letterhead_discharge_bill/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";
            
            $btn_print_letterhead_discharge = ' <li><a  onClick="return print_window_page('.$print_letterhead_discharge_url.')" href="javascript:void(0)" title="Print Letterhead Discharge Bill"  data-url="512"> <i class="fa fa-print" aria-hidden="true"></i>  Print Discharge Bill Page</a></li>';

            $btn_sales_medicine = '';
            if(in_array('400',$users_data['permission']['action'])) 
            {
              $btn_sales_medicine = '<li><a href="'.base_url('sales_medicine/add/'.$ipd_booking->patient_id).'/'.$ipd_booking->id.'" style="'.$ipd_booking->id.'" title="Sale Medicine"><i class="fa fa-plus"></i> Sale Medicine</a></li>';
            }
            $btn_ot_booking = '';

            if(in_array('808',$users_data['permission']['action']))
            {
              $btn_ot_booking = '<li><a href="'.base_url('ot_booking/add/'.$ipd_booking->patient_id).'/'.$ipd_booking->id.'" style="'.$ipd_booking->id.'" title="OT Booking"><i class="fa fa-plus"></i> OT Booking</a></li>';
            }

            $btn_sales_vaccination = '';
            if(in_array('1075',$users_data['permission']['action']))
            {
              $btn_sales_vaccination = '<li><a  href="'.base_url('sales_vaccination/add/'.$ipd_booking->patient_id).'/'.$ipd_booking->id.'" style="'.$ipd_booking->id.'" title="Sale Vaccine"><i class="fa fa-plus"></i> Sale Vaccine</a></li>';
            }

            $btn_pathology_booking='';
            if(in_array('872',$users_data['permission']['action']))
      { //(145,872);
        $btn_pathology_booking = '<li><a href="'.base_url('test/booking/'.$ipd_booking->patient_id).'/'.$ipd_booking->id.'" style="'.$ipd_booking->id.'" title="Test booking"><i class="fa fa-plus"></i> Test Booking</a></li>';
      }

      $btn_dialysis_booking = '';
      if(in_array('1193',$users_data['permission']['action']))
      {
       $btn_dialysis_booking = '<li><a href="'.base_url('dialysis_booking/add/'.$ipd_booking->patient_id).'/'.$ipd_booking->id.'" style="'.$ipd_booking->id.'" title="Dialysis Booking"><i class="fa fa-plus"></i> Dialysis Booking</a></li>';

     }
     $btn_sales_vaccination = '';
     if(in_array('1089',$users_data['permission']['action']))
     {
      $btn_sales_vaccination = ' <li><a href="'.base_url('sales_vaccination/add/'.$ipd_booking->patient_id).'/'.$ipd_booking->id.'" style="'.$ipd_booking->id.'" title="Sale Vaccine"><i class="fa fa-plus"></i> Sale Vaccine</a></li>';
    }
    
    $print_summarized_url = "'".base_url('ipd_discharge_bill/print_summarized_bill/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";
    
    $print_discharge_bill_according_group_url = "'".base_url('ipd_discharge_bill/print_discharge_bill_according_group/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";

    $print_consolidated_bill = "'".base_url('ipd_discharge_bill/print_consolidated_bill/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";

    $ipd_consolidated_bill= '<li><a onClick="return print_window_page('.$print_consolidated_bill.')" style="'.$ipd_booking->id.'" title="Test booking"><i class="fa fa-print"></i> Print Consolidated Bill</a></li>';

    $ipd_summarized_bill = '<li><a onClick="return print_window_page('. $print_summarized_url.')" style="'.$ipd_booking->id.'" title="Test booking"><i class="fa fa-print"></i> Print Summarize Bill</a></li>';

    $ipd_discharge_bill_according_group = '<li><a onClick="return print_window_page('.$print_discharge_bill_according_group_url.')" style="'.$ipd_booking->id.'" title="Group wise Bill"><i class="fa fa-print" aria-hidden="true"></i> Print Detail Bill </a></li>';
              //56/136 
    
    
    $print_gipsa_summarized_url = "'".base_url('gipsa_ipd_discharge_bill/print_summarized_bill/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";
    $print_gipsa_discharge_bill_according_group_url = "'".base_url('gipsa_ipd_discharge_bill/print_discharge_bill_according_group/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";
    $ipd_gipsa_summarized_bill = '<li><a onClick="return print_window_page('. $print_gipsa_summarized_url.')" style="'.$ipd_booking->id.'" title="Test booking"><i class="fa fa-print"></i> Print Gipsa Summarize Bill</a></li>';
    $ipd_gipsa_discharge_bill_according_group = '<li><a onClick="return print_window_page('.$print_gipsa_discharge_bill_according_group_url.')" style="'.$ipd_booking->id.'" title="Group wise Bill"><i class="fa fa-print" aria-hidden="true"></i> Print Gipsa Detail Bill </a></li>';

    $btn_recipient_booking ='';
    if($ipd_booking->discharge_status==1)
    {
      $btndischarge_bill='';
      if(in_array('785',$users_data['permission']['action']))
      {
        
        $btndischarge_bill =  '<a href="javascript:void(0)" onclick="generate_discharge_bill('.$ipd_booking->id.','.$ipd_booking->patient_id.',2)" title="Modify Bill"><i class="fa fa-database" aria-hidden="true"></i> Modify Bill</a>';
      }
      
      $btngipsadischarge_bill='';
      if(in_array('785',$users_data['permission']['action']))
      {
        
        $btngipsadischarge_bill =  '<a  href="javascript:void(0)" onclick="generate_gipsa_discharge_bill('.$ipd_booking->id.','.$ipd_booking->patient_id.',2)" title="Modify Bill"><i class="fa fa-database" aria-hidden="true"></i> Modify Gipsa Bill</a>';
      }
      
      
      
      $btn_readmit='';
      if(in_array('785',$users_data['permission']['action']))
      {
        $btn_readmit = '<li><a   onclick="confirmation_readmit('.$ipd_booking->id.','.$ipd_booking->patient_id.');"  title="Re-admit" data-url="512"> <i class="fa fa-database" aria-hidden="true"></i> Re-admit</a></li>';

      }
      
     $btn_recipient_booking='';
     
     $btn_print_label = ' <a onClick="return print_label('.$ipd_booking->id.');"  href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Print"><i class="fa fa-print"></i> Print Label</a>';
     
     $btn_print_label_16 = ' <a onClick="return print_label_sixteen('.$ipd_booking->id.');"  href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Print"><i class="fa fa-print"></i> Print Label 16</a>';
     
     $btn_print_label_24 = ' <a onClick="return print_label_twentyfour('.$ipd_booking->id.');"  href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Print"><i class="fa fa-print"></i> Print Label 24</a>';
     
     $btn_print_label_36 = ' <a onClick="return print_label_thirtysix('.$ipd_booking->id.');"  href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Print"><i class="fa fa-print"></i> Print Label 36</a>';
     
    //  if($ipd_booking->gender=='Female')
    //  {
      $btn_new_born =  '<a  href="javascript:void(0);" onclick="new_born_baby('.$ipd_booking->id.')" title="New Born"><i class="fa fa-child"></i> New Born</a>';
      
  //   }
  //   else
  //   {
  //    $btn_new_born =  '';
  //  }
   
   /*$btn_b = '<div class="slidedown">
   <button  class="btn-custom">More <span class="caret"></span></button>
   <ul class="slidedown-content">
   '.$btnprint.$btn_admission_print.$btn_barcode.$btn_admission_consent_print.$btnadvdischarge_summary.$btndischarge_summary.$btndischarge_bill.$btn_print_discharge.$btn_final_receipt.$ipd_summarized_bill.$btn_print_letterhead_discharge.$ipd_discharge_bill_according_group.$ipd_consolidated_bill.$btndisprogress_report.$btn_print_progress.$btn_readmit.$btngipsadischarge_bill.$ipd_gipsa_summarized_bill.$ipd_gipsa_discharge_bill_according_group.$btngipsadischarge_summary.$btn_new_born.$btn_sales_vaccination.$btn_recipient_booking.$btnprintmlc.$btn_print_label.$btn_print_label_16.'
   </ul>
   </div> ';*/
   
   $btn_nabh = '';
   if(in_array('2074',$users_data['permission']['action']))
   {
    $btn_nabh = ' <a  onClick="return nabh_print_ipd_booking('.$ipd_booking->id.')" href="javascript:void(0)" title="Print NABH" data-url="512"><i class="fa fa-print"></i> Print NABH</a> ';
  }
  
   
   $btn_b = '<div class="slidedown">
   <button  class="btn-custom">More <span class="caret"></span></button>
   <ul class="slidedown-content">
   '.$btnprint.$btn_admission_print.$btn_barcode.$btn_admission_consent_print.$btn_final_receipt.$btndischarge_bill.$btngipsadischarge_bill.$btn_readmit.$btnadvdischarge_summary.$btndischarge_summary.$btn_print_discharge.$btn_print_letterhead_discharge.$ipd_discharge_bill_according_group.$ipd_gipsa_discharge_bill_according_group.$ipd_summarized_bill.$ipd_gipsa_summarized_bill.$ipd_consolidated_bill.$btn_new_born.$btnprintmlc.$btn_print_label.$btn_print_label_16.$btn_print_label_24.$btn_print_label_36.$btn_nabh.'
   </ul>
   </div> ';
   
   
   

  $row[] = $btnedit.$btnview.$btndelete.$btn_b;
}
else
{
 if(in_array('785',$users_data['permission']['action']))
 {
               //$btndischarge_bill = '<li><a  href="javascript:void(0)" onclick="confirmation_box('.$ipd_booking->id.','.$ipd_booking->patient_id.');"  title="Discharge Bill" data-url="512"> <i class="fa fa-database" aria-hidden="true"></i> Discharge Bill</a></li>';
  
  $btndischarge_bill =  '<a href="javascript:void(0)" onclick="generate_discharge_bill('.$ipd_booking->id.','.$ipd_booking->patient_id.',1)" title="Discharge Bill"><i class="fa fa-database" aria-hidden="true"></i> Discharge Bill</a>';

}

if(in_array('785',$users_data['permission']['action']))
{
               //$btndischarge_bill = '<li><a   onclick="confirmation_box('.$ipd_booking->id.','.$ipd_booking->patient_id.');"  title="Discharge Bill" data-url="512"> <i class="fa fa-database" aria-hidden="true"></i> Discharge Bill</a></li>';
  
  $btngipsadischarge_bill =  '<a  onclick="generate_gipsa_discharge_bill('.$ipd_booking->id.','.$ipd_booking->patient_id.',1)" title="Discharge Bill"><i class="fa fa-database" aria-hidden="true"></i> Gipsa Discharge Bill</a>';

}

$btn_recipient_booking='';
if(in_array('1507',$users_data['permission']['action'])) 
{

 $btn_recipient_booking = '<li><a href="'.base_url('blood_bank/recipient/add/ipd_'.$ipd_booking->id).'" style="'.$ipd_booking->id.'" title="Blood Recipient"><i class="fa fa-plus"></i> Blood Recipient</a></li>';
}

// if($ipd_booking->gender=='Female')
// {
  $btn_new_born =  '<a  href="javascript:void(0);" onclick="new_born_baby('.$ipd_booking->id.')" title="New Born"><i class="fa fa-child"></i> New Born</a>';
  
// }
// else
// {
//  $btn_new_born =  '';
// }


             /*$print_label_url = "'".base_url('ipd_booking/print_template/').$ipd_booking->id;
             $btn_print_label = ' <li><a href="javascript:void(0)" onclick = "return print_label('.$print_label_url.');" title="Print Label" ><i class="fa fa-print"></i> Print Label </a></li>';*/
             
             $btn_print_label = ' <a onClick="return print_label('.$ipd_booking->id.');"  href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Print"><i class="fa fa-print"></i> Print Label</a>';
             $btn_print_label_16 = ' <a onClick="return print_label_sixteen('.$ipd_booking->id.');"  href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Print"><i class="fa fa-print"></i> Print Label 16</a>';
             
             $btn_print_label_24 = ' <a onClick="return print_label_twentyfour('.$ipd_booking->id.');"  href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Print"><i class="fa fa-print"></i> Print Label 24</a>';
     
     $btn_print_label_36 = ' <a onClick="return print_label_thirtysix('.$ipd_booking->id.');"  href="javascript:void(0)" style="'.$ipd_booking->id.'" title="Print"><i class="fa fa-print"></i> Print Label 36</a>';
             
             
              $btn_nabh = '';
             if(in_array('2074',$users_data['permission']['action']))
             {
              $btn_nabh = ' <a  onClick="return nabh_print_ipd_booking('.$ipd_booking->id.')" href="javascript:void(0)" title="Print NABH" data-url="512"><i class="fa fa-print"></i> Print NABH</a> ';
            }
            // added By Nitin Sharma 02/02/2024
            $btn_view_test = '<li><a onclick="return view_test_report('.$ipd_booking->patient_id.')" href="javascript:void(0)" title="View Test Report"><i class="fa fa-list"></i> View Test Report</a></li>';
             $btn_a = '<div class="slidedown">
             <button disabled class="btn-custom">More <span class="caret"></span></button>
             <ul class="slidedown-content">
             '.$btnprint.$btn_admission_print.$btn_barcode.$btn_medication_chart_fill.$btn_admission_consent_print.$btnadavancepayment.$btnroom_transfer.$btncharge_entry.$btnadvdischarge_summary.$btndisprogress_report.$btndischarge_summary.$btngipsadischarge_summary.$btndischarge_bill.$btn_print_progress.$btngipsadischarge_bill.$btn_sales_medicine.$btn_pathology_booking.$btn_ot_booking.$btn_dialysis_booking.$btn_sales_vaccination.$btn_recipient_booking.$btnprintmlc.$btn_new_born.$btn_print_label.$btn_print_label_16.$btn_print_label_24.$btn_print_label_36.$btn_nabh.
             $btn_view_test.'
             </ul>
             </div> ';
             // added By Nitin Sharma 02/02/2024
             
            
            
            $row[] = $btnedit.$btnview.$btndelete.$btn_a;
    }
          
          $data[] = $row;
          $i++;
        }
        
        $recordsTotal = $this->ipd_booking->count_all();
        $recordsFiltered = $recordsTotal;
        $output = array(
          "draw" => $_POST['draw'],
          "recordsTotal" => $this->ipd_booking->count_all(),
                        "recordsFiltered" => $recordsFiltered, //$this->ipd_booking->count_filtered(),
                        "data" => $data,
                      );
        //print_r($output);
        //output to json format
        echo json_encode($output);
      }
      function save_new_born()
      {
        $post= $this->input->post();
        
        if(!empty($post))
        {
          $result = $this->ipd_booking->save_new_born();
          if(!empty($result))
          {
            $data=array('success'=>1,'msg'=>'Birth deatail saved successfully!');
            echo json_encode($data);
            return false;
          }
          else
          {
            $data=array('success'=>0);
            echo json_encode($data);
            return false;
          }
        }

      }
      function new_born_baby($ipd_id)
      {

    //echo $test_id; die;
    
        $user_data = $this->session->userdata('auth_users');
        $data['title'] = 'New Born';
        $data['ipd_id'] = $ipd_id;
        $data['patient_details'] =$this->ipd_booking->get_by_id($ipd_id);
        $data['age_of_mother'] = "{$data['patient_details']['age_y']} Years, {$data['patient_details']['age_m']} Months, {$data['patient_details']['age_d']} Days";

        $this->db->select('hms_born_summery.*');
        $this->db->from('hms_born_summery'); 
        $this->db->where('hms_born_summery.branch_id',$user_data['parent_id']);
        $this->db->where('hms_born_summery.ipd_id',$ipd_id);
        

        $query = $this->db->get();
        $death_result = $query->row_array();
        if(!empty($death_result))
        {
         $data['born_id'] = $death_result['id'];
         $data['born_date'] = date('d-m-Y',strtotime($death_result['born_date']));
         $data['born_time'] = $death_result['born_time'];
         $data['weight'] = $death_result['weight'];
         $data['gender'] = $death_result['gender'];
         $data['type_of_delivery'] = $death_result['type_of_delivery'];
         $data['caste'] = $death_result['caste'];
         $data['religion'] = $death_result['religion'];
         $data['para'] = $death_result['para'];
         $data['remarks'] = $death_result['remarks'];

       }
       else
       {

         $data['born_id'] = '';
         $data['born_date'] = date('d-m-Y');
         $data['born_time'] = date('H:i:s');	
         $data['weight'] ='';
         $data['gender'] = '';
         $data['type_of_delivery'] = '';
         $data['caste'] = '';
         $data['religion'] = '';
         $data['para'] = '';
         $data['remarks'] = '';
       }
      
       $this->load->model('caste_master/caste_master_model'); 
       $data['caste_list'] = $this->caste_master_model->list();
        $data['religion_list'] = $this->db->where('branch_id',$user_data['parent_id'])->where('is_deleted',0)->where('status','1')->order_by('religion','ASC')->get('hms_religion')->result();
       
    //print_r($data['patient_details']); exit;
       $this->load->view('ipd_booking/new_born', $data);
       
     }

     public function reset_search()
     {
      $this->session->unset_userdata('ipd_booking_search');
    }



    public function ipd_booking_excel()
    {
      
      $this->load->library('excel');
      $this->excel->IO_factory();
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
      $objPHPExcel->setActiveSheetIndex(0);
      
      $fields = array('IPD No.','Patient Reg. No.','Patient Name','Gender/Age','Mobile No.','Insurance Type','Insurance Company','Admission Date','Doctor Name','Room Type','Room No.','Bed No.','Address','Remarks', 'Discharge Date', 'Diagnosis');
      $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $col = 0;
      $row_heading =1;
      foreach ($fields as $field)
      {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $row_heading++;
        $col++;
      }
      $list = $this->ipd_booking->search_report_data();
      
      $rowData = array();
      $data= array();
      if(!empty($list))
      {

       $i=0;
       foreach($list as $reports)
       {
        
        $age_y = $reports->age_y;
        $age_m = $reports->age_m;
        $age_d = $reports->age_d;
                //$age_h = $ipd_booking->age_h;
        $age = "";
        if($age_y>0)
        {
          $year = 'Years';
          if($age_y==1)
          {
            $year = 'Year';
          }
          $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
          $month = 'Months';
          if($age_m==1)
          {
            $month = 'Month';
          }
          $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
          $day = 'Days';
          if($age_d==1)
          {
            $day = 'Day';
          }
          $age .= ", ".$age_d." ".$day;
        }
                /*if($age_h>0)
                {
                $hours = 'Hours';
                
                $age .= " ".$age_h." ".$hours;
              } */
            ///////////////////////////////////////
              
              
              $genders = array('0'=>'Female','1'=>'Male','2'=>'Others');
              
              $age_gender = $genders[$reports->gender].'/'.$age;
              
              if($reports->discharge_date =='0000-00-00 00:00:00')
              {
               $createdate ='';	
             }
             else
             {
               $createdate = date('d-M-Y h:i A',strtotime($reports->discharge_date));
             }
             
             array_push($rowData,$reports->ipd_no,$reports->patient_code,$reports->patient_name,$age_gender,$reports->mobile_no,$reports->insurance_type,$reports->insurance_company,date('d-M-Y',strtotime($reports->admission_date)),$reports->doctor_name,$reports->room_category,$reports->room_no,$reports->bad_name,$reports->address,$reports->remarks,$createdat, $reports->diagnosis);
             $count = count($rowData);
             for($j=0;$j<$count;$j++)
             {
               
               $data[$i][$fields[$j]] = $rowData[$j];
             }
             unset($rowData);
             $rowData = array();
             $i++;  
           }
           
         }

        // Fetching the table data
         $row = 2;
         if(!empty($data))
         {
           foreach($data as $boking_data)
           {
            $col = 0;
            $row_val=1;
            foreach ($fields as $field)
            { 
             $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);

             $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
             $col++;
             $row_val++;
           }
           $row++;
         }
         $objPHPExcel->setActiveSheetIndex(0);
         $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
       }
       
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
       header('Content-Type: application/vnd.ms-excel charset=UTF-8');
       header("Content-Disposition: attachment; filename=ipd_booking_list_".time().".xls");
       header("Pragma: no-cache"); 
       header("Expires: 0");
       if(!empty($data))
       {
        ob_end_clean();
        $objWriter->save('php://output');
      }
    }

    public function ipd_booking_csv()
    {
      
      $this->load->library('excel');
      $this->excel->IO_factory();
      $objPHPExcel = new PHPExcel();
      $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
      $objPHPExcel->setActiveSheetIndex(0);
      
      $fields = array('IPD No.','Patient Reg. No.','Patient Name','Age/Gender', 'Mobile No.','Insurance Type','Insurance Company','Admission Date','Doctor Name','Room Type','Room No.','Bed No.','Address','Remarks','Discharge Date', 'Diagnosis');
      $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $col = 0;
      $row_heading =1;
      foreach ($fields as $field)
      {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $row_heading++;
        $col++;
      }
      $list = $this->ipd_booking->search_report_data();
      
      $rowData = array();
      $data= array();
      if(!empty($list))
      {

       $i=0;
       foreach($list as $reports)
       {
        $age_y = $reports->age_y;
        $age_m = $reports->age_m;
        $age_d = $reports->age_d;
                //$age_h = $ipd_booking->age_h;
        $age = "";
        if($age_y>0)
        {
          $year = 'Years';
          if($age_y==1)
          {
            $year = 'Year';
          }
          $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
          $month = 'Months';
          if($age_m==1)
          {
            $month = 'Month';
          }
          $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
          $day = 'Days';
          if($age_d==1)
          {
            $day = 'Day';
          }
          $age .= ", ".$age_d." ".$day;
        }
                /*if($age_h>0)
                {
                $hours = 'Hours';
                
                $age .= " ".$age_h." ".$hours;
              } */
            ///////////////////////////////////////
              
              
              $genders = array('0'=>'Female','1'=>'Male','2'=>'Others');
              
              $age_gender = $genders[$reports->gender].'/'.$age;
              if($reports->discharge_date =='0000-00-00 00:00:00')
              {
               $createdate ='';	
             }
             else
             {
               $createdate = date('d-M-Y h:i A',strtotime($reports->discharge_date));
             }
             
             array_push($rowData,$reports->ipd_no,$reports->patient_code,$reports->patient_name,$age_gender,$reports->mobile_no,$reports->insurance_type,$reports->insurance_company,date('d-M-Y',strtotime($reports->admission_date)),$reports->doctor_name,$reports->room_category,$reports->room_no,$reports->bad_name,$reports->address,$reports->remarks,$createdate, $reports->diagnosis);
             $count = count($rowData);
             for($j=0;$j<$count;$j++)
             {
               
               $data[$i][$fields[$j]] = $rowData[$j];
             }
             unset($rowData);
             $rowData = array();
             $i++;  
           }
           
         }

        // Fetching the table data
         $row = 2;
         if(!empty($data))
         {
           foreach($data as $boking_data)
           {
            $col = 0;
            $row_val=1;
            foreach ($fields as $field)
            { 
             $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);

             $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
             $col++;
             $row_val++;
           }
           $row++;
         }
         $objPHPExcel->setActiveSheetIndex(0);
         $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
       }
       
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
       header('Content-Type: application/vnd.ms-excel charset=UTF-8');
       header("Content-Disposition: attachment; filename=ipd_booking_list_".time().".csv");
       header("Pragma: no-cache"); 
       header("Expires: 0");
       if(!empty($data))
       {
        ob_end_clean();
        $objWriter->save('php://output');
      }
    }


    public function pdf_ipd_booking()
    {    
      $data['print_status']="";
      $data['data_list'] = $this->ipd_booking->search_report_data();
      $this->load->view('ipd_booking/ipd_booking_html',$data);
      $html = $this->output->get_output();
      $this->load->library('pdf');
      $this->pdf->load_html($html);
      $this->pdf->render();
      $this->pdf->stream("ipd_booking_list_".time().".pdf");
    }

    public function print_ipd_booking()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->ipd_booking->search_report_data();
      $this->load->view('ipd_booking/ipd_booking_html',$data); 
    }
    public function advance_search()
    {

      $this->load->model('general/general_model'); 
      $data['page_title'] = "Advance Search";
      $post = $this->input->post();
      $data['simulation_list'] = $this->general_model->simulation_list();
      $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
      $data['insurance_type_list'] = $this->general_model->insurance_type_list();
      $data['insurance_company_list'] = $this->general_model->insurance_company_list(); 
      $data['form_data'] = array(
       "start_date"=>"",
       "end_date"=>"",
       "insurance_type"=>'',
       "insurance_type_id"=>'',
       "ins_company_id"=>'',
       "patient_name"=>"",
       "ipd_no"=>"",
       "room_no"=>'',
       "mobile_no"=>'',
       "adhar_no"=>'',
       'attended_doctor'=>'',
       'running'=>0,
       'mlc'=>''
       
     );
      if(isset($post) && !empty($post))
      {
        $marge_post = array_merge($data['form_data'],$post);
        $this->session->set_userdata('ipd_booking_search', $marge_post);
      }
      $ipd_booking_search = $this->session->userdata('ipd_booking_search');
      if(isset($ipd_booking_search) && !empty($ipd_booking_search))
      {
        $data['form_data'] = $ipd_booking_search;
      }
      $this->load->view('ipd_booking/advance_search',$data);
    }

    public function add()
    {
      unauthorise_permission(121,734);
      $users_data = $this->session->userdata('auth_users');
      $pid='';

      if(isset($_GET['ipd']))
      {
        $pid= $_GET['ipd'];
      }
      
      $ipd_no = generate_unique_id(22);
      $this->load->model('general/general_model'); 
      $data['page_title'] = "IPD Booking";
      $data['form_error'] = [];
      $data['button_value'] = "Save";
      $post = $this->input->post();
      $vendor_id='';
      $age_m="";
      $age_y="";
      $age_d="";
      $address="";
      $address_second="";
      $address_third="";
      $gender=0;
      $vendor_code = "";
      $name = "";
      $patient_name = "";
      $mobile_no = "";
      $email = "";
      $address = "";
      $simulation_id="";
      $referral_doctor="";
      $relation_type="";
      $relation_name="";
      $relation_simulation_id="";
      $adhar_no="";
      $patient_category="";
      $authorize_person="";
      if($pid>0)
      {
       $patient = $this->ipd_booking->get_patient_by_id($pid);
           //print_r($purchase);
       
       if($patient['dob']=='1970-01-01' || $patient['dob']=="0000-00-00")
       {
        $dob='';
        $present_age = get_patient_present_age('',$patient);
      }
      else
      {
        $dob=date('d-m-Y',strtotime($patient['dob']));
        $present_age = get_patient_present_age($dob); 
              //$age_h = $present_age['age_h'];
      }
            /*$age_y = $present_age['age_y'];
            $age_m = $present_age['age_m'];
            $age_d = $present_age['age_d'];*/
            if(!empty($patient))
            {
              $patient_id = $patient['id'];
              $simulation_id = $patient['simulation_id'];
              $patient_reg_code = $patient['patient_code'];
              $name = $patient['patient_name'];
              $age_m=$patient['age_m'];
              $age_d=$patient['age_d'];
              $age_y=$patient['age_y'];
              $address = $patient['address'];
              $address_second = $patient['address2'];
              $address_third = $patient['address3'];
              $mobile_no = $patient['mobile_no'];
              $email = $patient['patient_email'];
              $relation_type=$patient['relation_type'];
              $relation_name=$patient['relation_name'];
              $relation_simulation_id=$patient['relation_simulation_id'];
              $adhar_no = $patient['adhar_no'];
              $patient_category = $patient['patient_category'];
            }
          }
          else if(isset($_GET['lid']) && !empty($_GET['lid']) && $_GET['lid']>0)
          {
          //$this->load->model('booking/booking_model');
            $lead_data = $this->ipd_booking->crm_get_by_id($_GET['lid']);
          //echo '<pre>'; print_r($lead_data['gender']);die;
            $gender=$lead_data['gender'];
            $name = $lead_data['name'];
            $email = $lead_data['email'];
            $mobile_no = $lead_data['phone'];
            $mobile_no = $lead_data['phone'];
            $age_m=$lead_data['age_m'];
            $age_d=$lead_data['age_d'];
            $age_y=$lead_data['age_y']; 
            $address = $lead_data['address'];
            $address_second = $lead_data['address2'];
            $address_third = $lead_data['address3'];  
            $patient_reg_code=generate_unique_id(4);
          }
          else
          {
           $patient_id='';
           $patient_reg_code=generate_unique_id(4);
         }
         $data['simulation_array']= $this->general_model->simulation_list();
         $data['payment_mode']=$this->general_model->payment_mode();
         $data['simulation_list']= $this->general_model->simulation_list();
         $data['doctors_list']= $this->general_model->doctors_list();
         $this->load->model('opd/opd_model','opd');
         $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
         $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
         $data['assigned_d_by_id']='';
         $data['assigned_doctor'] = $this->ipd_booking->assigned_doctor_list();
         $data['panel_type_list'] = $this->general->panel_type_list();
         $data['panel_company_list'] = $this->general->panel_company_list();
         $data['package_list']=$this->general_model->ipd_package_list();
         $data['room_type_list']=$this->general_model->room_type_list();
         $data['referal_hospital_list'] = $this->general_model->referal_hospital_list(); 
         $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
         
         $data['room_no'] = $this->ipd_booking->get_room_no();
         $data['bed_no'] = $this->ipd_booking->get_bed_no();
         
         $data['form_data'] = array(
          "patient_id"=>$patient_id,
          "data_id"=>"",
          "patient_reg_code"=>$patient_reg_code,
          "name"=>$name,
          'ipd_no'=>$ipd_no,
          'simulation_id'=>$simulation_id,
          "mobile"=>$mobile_no,
          "age_m"=>$age_m,
          "age_d"=>$age_d,
          "gender"=>$gender,
          "age_y"=>$age_y,
          'adhar_no'=>$adhar_no,
          "address"=>$address,
          "address_second"=>$address_second,
          "address_third"=>$address_third,
          "relation_type"=>$relation_type,
          "relation_name"=>$relation_name,
          "relation_simulation_id"=>$relation_simulation_id,
          "attended_doctor"=>'',
          "assigned_docotor_list"=>'',
          "authorization_amount"=>"0.00",
          "id_number"=>"",
          "room_id"=>"",
          "room_no_id"=>"",
          "time_unit"=>"",
          "bed_no_id"=>"",
          "patient_type"=>1,
          "package_id"=>"",
          "package"=>1,
          "remarks"=>"",
          "advance_deposite"=>"",
          "panel_type"=>"",
          "policy_number"=>"",
          "company_name"=>"",
          "admission_time"=>date('H:i:s'),
          "admission_date"=>date('d-m-Y'),
          'total_amount'=>"0.00",
          'discount_amount'=>"0.00",
          'payment_mode'=>"",
          'net_amount'=>"",
          'pay_amount'=>"",
          "field_name"=>'',
          "country_code"=>"+91",
          'mlc'=>'',
          'mlc_status'=>'0',
          'referral_doctor'=>'',
          'referred_by'=>'',
          'referral_hospital'=>'',
          'discharge_date'=>'',
          'patient_category'=>$patient_category,
          'authorize_person'=>$authorize_person,
          'diagnosis' => ''
        );
         if(isset($post) && !empty($post))
         {   
          $data['form_data'] = $this->_validate();
          if($this->form_validation->run() == TRUE)
          {
            $salesid=  $this->ipd_booking->save();

            
             //send sms
            if(!empty($salesid))
            {
              $get_by_id_data = $this->ipd_booking->get_by_id($salesid);
              $patient_name = $get_by_id_data['patient_name'];
              $booking_code = $get_by_id_data['ipd_no'];
              $mlc_code = $get_by_id_data['mlc'];
              $paid_amount = $get_by_id_data['advance_deposite'];
              $mobile_no = $get_by_id_data['mobile_no'];
              $room_no = $get_by_id_data['room_no'];
              $patient_email = $get_by_id_data['patient_email'];
                //check permission
              if(in_array('640',$users_data['permission']['action']))
              {
                if(!empty($mobile_no))
                {
                  send_sms('ipd_booking',15,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{IPDNo}'=>$booking_code,'{RoomNo}'=>$room_no));  
                  
                  $patient_data = $this->ipd_booking->get_patient_by_id($pid);
                  if(!empty($pid) && !empty($patient_data) && $patient_data['mobile_no']!=$post['mobile_no'])
                  {
                    $parameter = array('{patient_name}'=>$post['patient_name'], '{mobile_no}'=>$post['mobile_no']);
                    
                    send_sms('update_patient_mobile',28,'',$post['mobile_no'],$parameter);  
                  }
                }
                
              }

              if(in_array('641',$users_data['permission']['action']))
              {
                if(!empty($patient_email))
                {
                  
                  $this->load->library('general_functions');
                  $this->general_functions->email($patient_email,'','','','','1','ipd_booking','15',array('{Name}'=>$patient_name,'{IPDNo}'=>$booking_code,'{RoomNo}'=>$room_no));
                  
                }
              } 
            }


            $this->session->set_userdata('ipd_booking_id',$salesid);
            $this->session->set_flashdata('success','IPD booking has been successfully added.');
                //redirect(base_url('ipd_booking/?status=print&mlc_status='.$_POST['mlc'].''));
             // redirect(base_url('ipd_booking/?status=print&admission_form=print_admission&mlc_status='.$_POST['mlc_status'].''));
            redirect(base_url('ipd_booking/add?status=print&admission_form=print_admission&mlc_status='.$_POST['mlc_status'].''));
          }
          else
          {
            $data['form_error'] = validation_errors(); 
                 //print_r($data['form_error']);die;
          }    

        }
        $data['btn_name'] = 'Save';
        $data['patient_category'] = $this->general_model->patient_category_list();
         $data['authrize_person_list'] = $this->general_model->authrize_person_list();
        $this->load->view('ipd_booking/add',$data);
      }

      public function edit($id="")
      {
       unauthorise_permission(121,735);
       $users_data = $this->session->userdata('auth_users');
       if(isset($id) && !empty($id) && is_numeric($id))
       {     
        $this->load->model('general/general_model');  
        $post = $this->input->post();
        $result = $this->ipd_booking->get_by_id($id); 
        $admission_time='';

        $result_patient = $this->ipd_booking->get_patient_by_id($result['patient_id']);
        $data['simulation_array']= $this->general_model->simulation_list();
        $data['simulation_list']= $this->general_model->simulation_list();
        $data['doctors_list']= $this->general_model->doctors_list();
        $this->load->model('opd/opd_model','opd');
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['assigned_doctor'] = $this->ipd_booking->assigned_doctor_list();
        $data['assigned_d_by_id']=$this->ipd_booking->aasigned_doctor_by_id($result['id']);
        $data['panel_type_list'] = $this->general_model->panel_type_list();
        $data['panel_company_list'] = $this->general_model->panel_company_list();
        $data['package_list']=$this->general_model->ipd_package_list();
        $data['room_type_list']=$this->general_model->room_type_list($id);
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['patient_category'] = $this->general_model->patient_category_list();
         $data['authrize_person_list'] = $this->general_model->authrize_person_list();
        $data['room_no'] = $this->ipd_booking->get_room_no();
        $data['bed_no'] = $this->ipd_booking->get_bed_no();
        
        $this->load->model('general/general_model');
        $data['payment_mode']=$this->general_model->payment_mode();
        $get_payment_detail= $this->ipd_booking->payment_mode_detail_according_to_field($result['payment_mode'],$id);
        $total_values=array();
        for($i=0;$i<count($get_payment_detail);$i++) {
          $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
          //$data['manuf_company_list'] = $this->purchase->manuf_company_list();
        $discharge_date = '';
        if(!empty($result['discharge_date']) && $result['discharge_date']!='1970-01-01 00:00:00' && $result['discharge_date']!='0000-00-00 00:00:00') 
        {
          $discharge_date = date('d-m-Y',strtotime($result['discharge_date']));
        }
        $adhar_no ='';
        if(!empty($result_patient['adhar_no']))
        {
         $adhar_no = $result_patient['adhar_no'];
       }
       if($result['admission_time']=='00:00:00')
       {
        $admission_time='';
      }
      else
      {
        $admission_time=$result['admission_time'];
      }

      $data['page_title'] = "IPD Booking";  
      $data['button_value'] = "Update";
      $data['form_error'] = ''; 
      
      if($result_patient['dob']=='1970-01-01' || $result_patient['dob']=="0000-00-00")
      {
        $present_age = get_patient_present_age('',$result_patient);
      }
      else
      {
        $dob=date('d-m-Y',strtotime($result_patient['dob']));
        $present_age = get_patient_present_age($dob,$result_patient);
      }
      
      $age_y = $present_age['age_y'];
      $age_m = $present_age['age_m'];
      $age_d = $present_age['age_d'];
      
      $data['form_data'] = array( 
        "patient_id"=>$result['patient_id'],
        "data_id"=>$result['id'],
        "patient_reg_code"=>$result_patient['patient_code'],
        "name"=>$result_patient['patient_name'],
        'ipd_no'=>$result['ipd_no'],
        
        'referral_doctor'=>$result['referral_doctor'],
        'simulation_id'=>$result_patient['simulation_id'],
        "relation_type"=>$result_patient['relation_type'],
        "relation_name"=>$result_patient['relation_name'],
        "relation_simulation_id"=>$result_patient['relation_simulation_id'],
        "mobile"=>$result_patient['mobile_no'],
        "age_m"=>$age_m,
        "age_d"=>$age_d,
        "gender"=>$result_patient['gender'],
        "age_y"=>$age_y,
        "adhar_no"=>$adhar_no,
        "address"=>$result_patient['address'],
        "address_second"=>$result_patient['address2'],
        "address_third"=>$result_patient['address3'],
        "attended_doctor"=>$result['attend_doctor_id'],
                                    //"assigned_docotor_list"=>$result['age_m'],
        "authorization_amount"=>$result['authrization_amount'],
        "id_number"=>$result['panel_id_no'],
        "room_id"=>$result['room_type_id'],
        "room_no_id"=>$result['room_id'],
                                    //"time_unit"=>$result_patient['age_m'],
        "bed_no_id"=>$result['bad_id'],
        "patient_type"=>$result['patient_type'],
        "package_id"=>$result['package_id'],
        "package"=>$result['package_type'],
        "remarks"=>$result['remarks'],
        "advance_deposite"=>$result['advance_payment'],
        "panel_type"=>$result['panel_type'],
        "policy_number"=>$result['panel_polocy_no'],
        "company_name"=>$result['panel_name'],
        "admission_time"=>$admission_time,
        "admission_date"=>date('d-m-Y',strtotime($result['admission_date'])),
        'payment_mode'=>$result['payment_mode'],
        'field_name'=>$total_values,
        "country_code"=>"+91",
        'mlc'=>$result['mlc'],
        'mlc_status'=>$result['mlc_status'],
        'referred_by'=>$result['referred_by'],
        'referral_hospital'=>$result['referral_hospital'],
        'discharge_date'=>$discharge_date,
        'mlc'=>$result['mlc'],
        'mlc_status'=>$result['mlc_status'],
        'reg_charge'=>$result['reg_charge'],
        'patient_category'=>$result['patient_category'],
          'authorize_person'=>$result['authorize_person'],
          'diagnosis' => $result['diagnosis']
        
      );  
      
      if(isset($post) && !empty($post))
      {   
        $data['form_data'] = $this->_validate();
        if($this->form_validation->run() == TRUE)
        {
          if(in_array('640',$users_data['permission']['action']))
          {     
            if(!empty($post['mobile_no']) && $result_patient['mobile_no']!=$post['mobile_no'])
            {
              $parameter = array('{patient_name}'=>$result_patient['patient_name'], '{mobile_no}'=>$post['mobile_no']);
              
              send_sms('update_patient_mobile',28,'',$post['mobile_no'],$parameter);  
            }
          }
          
          $ipd_booking_id =  $this->ipd_booking->save();

          $this->session->set_userdata('ipd_booking_id',$ipd_booking_id);
          $this->session->set_flashdata('success','IPD booking has been successfully updated.');
          redirect(base_url('ipd_booking/?status=print'));
        }
        else
        {
          $data['form_error'] = validation_errors();
                //print_r($data['form_error']); exit;
        }     
      }
      $data['btn_name'] = 'Save';
      $this->load->view('ipd_booking/add',$data);  

    }
  }

  public function print_ipd_booking_recipt($id="")
  {
   
    $data['page_title'] = "Print Bookings";
    $ipd_booking_id= $this->session->userdata('ipd_booking_id');
    
    if(!empty($id))
    {
      $ipd_booking_id = $id;
    }
    elseif(isset($ipd_booking_id) && !empty($ipd_booking_id))
    {
      $ipd_booking_id =$ipd_booking_id;
    }
    else
    {
      $ipd_booking_id = '';
    } 
    
    $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
    
    $branch_id='';
    $users_data = $this->session->userdata('auth_users'); 
    if($users_data['users_role']==3)
    {
      $doctor_id = $users_data['parent_id'];
      $this->load->model('branch/branch_model');
      $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
      $branch_id = $doctor_data[0]->branch_id; 
    } 

    $template_format = $this->ipd_booking->template_format(array('section_id'=>5,'types'=>1),$branch_id);
      //print_r($template_format); exit;
      //Package
    
    $package_id = $get_by_id_data['ipd_list'][0]->package_id;
      /*$this->load->model('packages/packages_model','package');
      $selected_medicine_result = $this->package->selected_medicine($package_id);*/
      $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
      //$data['medicine_ids']=$medicine_arr;
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
       $this->load->model('time_print_setting/time_print_setting_model','time_setting');
      $data['time_setting'] = $this->time_setting->get_master_unique();
      
      $this->load->view('ipd_booking/print_ipd_reciept_template',$data);
    }

    public function print_ipd_adminssion_card($id="")
    {
      $this->load->model('ipd_admission_print_setting/ipd_admission_print_setting_model','ipd_admission_print');
      $data['page_title'] = "Print Bookings";
      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
      $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
      //echo "<pre>";print_r($get_by_id_data); exit;
      $branch_id='';
      $users_data = $this->session->userdata('auth_users'); 
      if($users_data['users_role']==3)
      {
        $doctor_id = $users_data['parent_id'];
        $this->load->model('branch/branch_model');
        $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
        $branch_id = $doctor_data[0]->branch_id; 
      } 

      $template_format = $this->ipd_admission_print->template_format(array('setting_name'=>'IPD_PRINT_SETTING','unique_id'=>1,'type'=>0),$branch_id);

      
      $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
      $data['template_data']=$template_format->setting_value;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
      $this->load->model('address_print_setting/address_print_setting_model','address_setting');
      $data['address_setting_list'] = $this->address_setting->get_master_unique();
      $this->load->model('time_print_setting/time_print_setting_model','time_setting');
      $data['time_setting'] = $this->time_setting->get_master_unique();
      
      $this->load->view('ipd_booking/print_ipd_admission_template',$data);
    }

    


    private function _validate()
    {
      $users_data = $this->session->userdata('auth_users'); 
      $post = $this->input->post();
      $field_list = mandatory_section_field_list(6);   
        //print_r($post);die; 
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

      if(!empty($field_list)){ 
        if($field_list[0]['mandatory_field_id']=='30' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){  
          $this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
        }
        
        if($field_list[1]['mandatory_field_id']=='31' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                //$this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
          if(($post['age_y']=='0' && $post['age_m']=='0' && $post['age_d']=='0') || ((empty($post['age_y']) && empty($post['age_m']) && empty($post['age_d']))) )
          {
           
            $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|is_natural_no_zero|max_length[3]');
          }
        }

        if($field_list[2]['mandatory_field_id']=='32' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id'])
        { 
          if(in_array('38',$users_data['permission']['section']) && $post['referred_by']=='0')
          {
            $this->form_validation->set_rules('referral_doctor', 'referred by doctor', 'trim|required');
            
          }
          else if(in_array('174',$users_data['permission']['section']) && $post['referred_by']=='1')
          {
            $this->form_validation->set_rules('referral_hospital', 'referred by hospital', 'trim|required');
          }
        }
      }

      $this->form_validation->set_rules('name', 'patient name', 'trim|required');
      $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required');
        //$this->form_validation->set_rules('mobile', 'mobile', 'trim|required');
      $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        //$this->form_validation->set_rules('age_y', 'year', 'trim|required');
      if(isset($post['package_id'])){
        $this->form_validation->set_rules('package_id', 'package name', 'trim|required'); 
      }
      $this->form_validation->set_rules('room_id', 'room type', 'trim|required');
      $this->form_validation->set_rules('room_no_id', 'room no.', 'trim|required');
      $this->form_validation->set_rules('bed_no_id', 'bed no.', 'trim|required');
         //if($post['referred_by']=='0')
         //{
      
      
      $this->form_validation->set_rules('attended_doctor', 'attended doctor', 'trim|required');
      $this->form_validation->set_rules('assigned_doctor_list[]', 'assigned docotor list', 'trim|required');
        //$this->form_validation->set_rules('advance_deposite', 'advance deposite', 'trim|required');
      $this->form_validation->set_rules('admission_date', 'admission date', 'trim|required');
      $this->form_validation->set_rules('email', 'Email', 'valid_email');
      if(isset($post['field_name']))
      {
        $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
      }

      
      $total_values=array();
      if(isset($post['field_name']))
      {
        $count_field_names= count($post['field_name']);  
        $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
          $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        } 
      }
      
      
        /*if(isset($_POST['payment_mode']) && $_POST['payment_mode']==2) {
         $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
         $this->form_validation->set_rules('card_no', 'Card no', 'trim|required');
         }
         if(isset($_POST['payment_mode']) && $_POST['payment_mode']==3) {
          $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
          $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');
        
         }
       if(isset($_POST['payment_mode']) && $_POST['payment_mode']==4) {
        $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
         $this->form_validation->set_rules('transaction_no', 'Transaction no', 'trim|required');
       }*/
       
       // $this->form_validation->set_rules('mobile', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
       
       if ($this->form_validation->run() == FALSE) 
       {  
        $patient_code = generate_unique_id(4); 

        

        if(isset($post['panel_type'])){
          $panel_type=$post['panel_type'];

        }else{
          $panel_type='';
        }
        if(isset($post['policy_number'])){
          $policy_number=$post['policy_number'];
        }else{
          $policy_number='';
        }
        if(isset($post['company_name'])){
          $company_name=$post['company_name'];
        }else{
         $company_name='';
       }
       if(isset($post['authorization_amount'])){
        $authorization_amount=$post['authorization_amount'];
      }else{
        $authorization_amount='';
      }
      if(isset($post['package_id'])){
        $package_id=$post['package_id'];
      }else{
        $package_id='';
      }
      if(isset($post['id_number'])){
        $id_number=$post['id_number'];
      }else{
        $id_number='';
      }  
      $data['form_data'] = array(
       "patient_id"=>$post['patient_id'],
       "data_id"=>$post['data_id'],
       "patient_reg_code"=>$patient_code,
       "name"=>$post['name'],
       'ipd_no'=>$post['ipd_no'],
       'simulation_id'=>$post['simulation_id'],
       "mobile"=>$post['mobile'],
       "age_m"=>$post['age_m'],
       "age_d"=>$post['age_d'],
       "gender"=>$post['gender'],
       "age_y"=>$post['age_y'],
       "adhar_no"=>$post['adhar_no'],
       "address"=>$post['address'],
       "address_second"=>$post['address_second'],
       "address_third"=>$post['address_third'],
       "attended_doctor"=>$post['attended_doctor'],
       'referral_doctor'=>$post['referral_doctor'],
       "authorization_amount"=>$authorization_amount,
       "id_number"=>$id_number,
       "room_id"=>$post['room_id'],
       "room_no_id"=>$post['room_no_id'],
       'remarks'=>$post['remarks'],
                                    //"time_unit"=>$post['time_unit'],
       "bed_no_id"=>$post['bed_no_id'],
       "patient_type"=>$post['patient_type'],
       "package_id"=>$package_id,
       "package"=>$post['package'],
       "advance_deposite"=>$post['advance_deposite'],
       "panel_type"=>$panel_type,
       "policy_number"=>$policy_number,
       "company_name"=>$company_name,
       "admission_time"=>$post['admission_time'],
       "admission_date"=>date('d-m-Y'),
       'payment_mode'=>$post['payment_mode'],
       'field_name'=>$total_values,
       "country_code"=>"+91",
       "mlc"=>$post['mlc'],
       "mlc_status"=>$post['mlc_status'],
       "relation_type"=>$post['relation_type'],
       "relation_name"=>$post['relation_name'],
       "relation_simulation_id"=>$post['relation_simulation_id'],
       'referred_by'=>$post['referred_by'],
       'referral_hospital'=>$post['referral_hospital'],
       'discharge_date'=>$post['discharge_date'],
       "mlc"=>$post['mlc'],
       "mlc_status"=>$post['mlc_status'],
       'patient_category'=>$post['patient_category'],
          'authorize_person'=>$post['authorize_person'],
     );  
      return $data['form_data'];
    }   
  }
  
  public function delete($id="")
  {
    unauthorise_permission(121,736);
    if(!empty($id) && $id>0)
    {
     $result = $this->ipd_booking->delete($id);
     $response = "IPD Booking successfully deleted.";
     echo $response;
   }
 }

 function deleteall()
 {
   unauthorise_permission(121,736);
   $post = $this->input->post();  
   if(!empty($post))
   {
    $result = $this->ipd_booking->deleteall($post['row_id']);
    $response = "IPD Booking successfully deleted.";
    echo $response;
  }
}

public function view($id="")
{  
 unauthorise_permission(121,733);
 if(isset($id) && !empty($id) && is_numeric($id))
 {      
  $data['form_data'] = $this->ipd_booking->get_by_id($id);  
  $data['page_title'] = $data['form_data']['medicine_name']." detail";
  $this->load->view('ipd_booking/view',$data);     
}
}  


    ///// employee Archive Start  ///////////////
public function archive()
{
 unauthorise_permission(121,737);
 $data['page_title'] = 'IPD Booking archive list';
 $this->load->helper('url');
 $this->load->view('ipd_booking/archive',$data);
}

public function archive_ajax_list()
{
 unauthorise_permission(121,737);
 $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive'); 

 $list = $this->ipd_booking_archive->get_datatables();  
 $data = array();
 $no = $_POST['start'];
 $i = 1;
 $total_num = count($list);
 foreach ($list as $ipd_booking) { 
  $no++;
  $row = array();
  
  
            ////////// Check  List /////////////////
  $check_script = "";
  if($i==$total_num)
  {
            /* $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                                })</script>"; */ 
                              }          
                              
                              $row[] = '<input type="checkbox" name="ipd_booking[]" class="checklist" value="'.$ipd_booking->id.'">';  
                              $row[] = $ipd_booking->ipd_no;
                              $row[] = $ipd_booking->patient_code;
                              $row[] = $ipd_booking->patient_name;
            //$row[] = $purchase->total_amount;
                              $row[] = $ipd_booking->mobile_no;
            $row[] = date('d-m-Y',strtotime($ipd_booking->admission_date)); //$ipd_booking->admission_date;
            $row[] = $ipd_booking->doctor_name;
            $row[] = $ipd_booking->room_no;
            $row[] = $ipd_booking->bad_name;
            $row[] = $ipd_booking->address;
            $row[] = $ipd_booking->remarks;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_booking->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
            if(in_array('739',$users_data['permission']['action'])){
              $btnrestore = ' <a onClick="return restore_booking('.$ipd_booking->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            }
            if(in_array('738',$users_data['permission']['action'])){
              $btndelete = ' <a onClick="return trash('.$ipd_booking->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            }
            $row[] = $btnrestore.$btndelete; 
            
            
            $data[] = $row;
            $i++;
          }

          $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ipd_booking_archive->count_all(),
            "recordsFiltered" => $this->ipd_booking_archive->count_filtered(),
            "data" => $data,
          );
        //output to json format
          echo json_encode($output);
        }

        public function restore($id="")
        {
          unauthorise_permission(121,739);
          $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive');
          if(!empty($id) && $id>0)
          {
           $result = $this->ipd_booking_archive->restore($id);
           $response = "IPD Booking successfully restore in IPD list.";
           echo $response;
         }
       }

       function restoreall()
       { 
        unauthorise_permission(121,739);
        $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
          $result = $this->ipd_booking_archive->restoreall($post['row_id']);
          $response = "IPD Booking successfully restore in IPD list.";
          echo $response;
        }
      }

      public function trash($id="")
      {
        unauthorise_permission(121,738);
        $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive');
        if(!empty($id) && $id>0)
        {
         $result = $this->ipd_booking_archive->trash($id);
         $response = "IPD Booking successfully deleted parmanently.";
         echo $response;
       }
     }

     function trashall()
     {
      unauthorise_permission(121,738);
      $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive');
      $post = $this->input->post();  
      if(!empty($post))
      {
        $result = $this->ipd_booking_archive->trashall($post['row_id']);
        $response = "IPD Booking successfully deleted parmanently.";
        echo $response;
      }
    }
    
    public function select_room_number()
    {
     $this->load->model('general/general_model');
     $room_id= $this->input->post('room_id');
     $room_no_id= $this->input->post('room_no_id');
     if(!empty($room_id)){
       $data['number_rooms']= $this->general_model->room_no_list($room_id);
     }
     $dropdown = '<option value="">-Select-</option>'; 
     if(!empty($data['number_rooms']))
     {
       $selected='';
       foreach($data['number_rooms'] as $number_rooms)
       {
        if($room_no_id==$number_rooms->id){
          $selected='selected=selected';

        }else{
          $selected='';
        }
        $dropdown .= '<option value="'.$number_rooms->id.'"  '.$selected.'>'.$number_rooms->room_no.'</option>';
      }
    } 
    echo $dropdown; 
    
  }
  public function select_bed_no_number()
  {
    $this->load->model('general/general_model');
    $room_id= $this->input->post('room_id');
    $room_no_id= $this->input->post('room_no_id');
    $bed_id= $this->input->post('bed_id');
    $ipd_id= $this->input->post('ipd_id');
    if(!empty($room_id) && !empty($room_no_id))
    {
      $data['number_bed']= $this->general_model->number_bed_list($room_id,$room_no_id,$ipd_id);
    }
          //print_r($data['number_bed']); exit;
    $selected='';
          // print_r($data['number_bed']);die;
    $dropdown = '<option value="">-Select-</option>'; 
    if(!empty($data['number_bed']))
    {
      foreach($data['number_bed'] as $number_bed)
      {
              //print_r($number_bed);
              //echo $number_bed->ipd_is_deleted; 
        
        if($bed_id == $number_bed->id)
        {
          $selected="selected=selected";
        }
        else
        {
          $selected='';
        }
        if($bed_id == $number_bed->id && $number_bed->status==1)
        {

          $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_name.'</option>';
        }
        else
        {
          if($number_bed->ipd_is_deleted=='2' ||  ($number_bed->ipd_is_deleted=='' || ($number_bed->ipd_is_deleted!=1 && $number_bed->ipd_is_deleted!=0) ))
          { 
            if($number_bed->status==1 && $number_bed->ipd_is_deleted==2)
            {
              $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_name.'</option>';  
            }
            else
            {
              $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_name.'</option>'; 
            }  
            
          }
        }
        
      }
    } 
    echo $dropdown; 
  }

  public function select_bed_no_number20171205()
  {
    $this->load->model('general/general_model');
    $room_id= $this->input->post('room_id');
    $room_no_id= $this->input->post('room_no_id');
    $bed_id= $this->input->post('bed_id');
    $ipd_id= $this->input->post('ipd_id');
    if(!empty($room_id) && !empty($room_no_id))
    {
      $data['number_bed']= $this->general_model->number_bed_list($room_id,$room_no_id,$ipd_id);
    }
    $selected='';
          // print_r($data['number_bed']);die;
    $dropdown = '<option value="">-Select-</option>'; 
    if(!empty($data['number_bed']))
    {
      foreach($data['number_bed'] as $number_bed)
      {
        if($bed_id == $number_bed->id)
        {
          $selected="selected=selected";
        }
        else
        {
          $selected='';
        }
        $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_no.'</option>';
      }
    } 
    echo $dropdown; 
  }

  public function update_discharge_data_backup($ipd_id="",$patient_id)
  {
    $result= $this->ipd_booking->update_discharge_data($ipd_id,$patient_id);  
    if(!empty($result)){
      $this->session->set_flashdata('success','Patient has been discharge successfully.');
      redirect(base_url('ipd_discharge_bill/discharge_bill_info/'.$ipd_id.'/'.$patient_id));
    }
  }
  
  public function update_discharge_data()
  {
   $post = $this->input->post();
   $ipd_id = $post['ipd_id'];
   $patient_id = $post['patient_id'];
   $discharge_date = $post['discharge_date'];
   $discharge_date_time = $post['discharge_date_time'];
   $type = $post['type'];
   $date_time = $discharge_date.' '.$discharge_date_time;
   if($type=='1')
   {
    $result= $this->ipd_booking->update_discharge_data($ipd_id,$patient_id,$date_time);  
  }
  else
  {
    $result=1;
  }
  
  if(!empty($result)){
          //$this->session->set_flashdata('success','Patient has been discharge successfully.');
   $data=array('success'=>1,'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'discharge_date'=>strtotime($date_time));
   echo json_encode($data);
   return false;
 }
}

public function readmit($ipd_id="",$patient_id)
{
  $result= $this->ipd_booking->re_admit_patient($ipd_id,$patient_id);  
  if(!empty($result))
  {
    $this->load->model('general/general_model');
    $data['page_title']="Re-admit";
    $data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
    $data['room_category']= $this->general_model->check_room_type();
    $post= $this->input->post();
    $data['form_data'] = array(
      'data_id'=>"", 
      'ipd_id'=>$ipd_id,
      "patient_id"=>$patient_id,
      "room_id"=>'',
      "room_no_id"=>'',
      'bed_no_id'=>'',
                                  //'card_no'=>'',
      'transfer_date'=>date('d-m-Y'),
      "transfer_time"=>date('H:i:s')
    );    

    if(isset($post) && !empty($post))
    { 
      $data['form_data'] = $this->_validate_readmit();
      if($this->form_validation->run() == TRUE)
      {
        $this->ipd_booking->save_readmit();
                //$this->session->set_userdata('ipd_room_transfer_id',$ipd_room_transfer_id);
        $this->session->set_flashdata('success','Patient re-admited successfully.');
        
                redirect(base_url('ipd_booking/')); // /?status=print
                
              }
              else
              {
                $data['form_error'] = validation_errors();  
              }  
            }
       //print_r( $data['form_error']);die;
            $this->load->view('ipd_booking/re_admit',$data); 
        //$this->session->set_flashdata('success','Patient has been re-admited successfully.');
        //redirect(base_url('ipd_booking/'));
          }
        }


        private function _validate_readmit()
        {

          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('room_id', 'room type', 'trim|required'); 
          $this->form_validation->set_rules('room_no_id', 'room no', 'trim|required'); 
          $this->form_validation->set_rules('bed_no_id', 'bed no', 'trim|required'); 
          $this->form_validation->set_rules('transfer_date', 're-admit date', 'trim|required'); 
          $this->form_validation->set_rules('transfer_time', 're-admit time', 'trim|required'); 
          
          if ($this->form_validation->run() == FALSE) 
          {  
            $data['form_data'] = array(
              'data_id'=>"", 
              'ipd_id'=>$post['ipd_id'],
              "patient_id"=>$post['patient_id'],
              "room_id"=>$post['room_id'],
              "room_no_id"=>$post['room_no_id'],
              'bed_no_id'=>$post['bed_no_id'],
                                      //'card_no'=>'',
              'transfer_date'=>date('d-m-Y',strtotime($post['transfer_date'])),
              "transfer_time"=>date('H:i:s',strtotime($post['transfer_time']))
            ); 
            return $data['form_data'];
          }   
        }

        public function get_payment_mode_data()
        {
          $this->load->model('general/general_model'); 
          $payment_mode_id= $this->input->post('payment_mode_id');
          $error_field= $this->input->post('error_field');

          $get_payment_detail= $this->general_model->payment_mode_detail($payment_mode_id); 
          $html='';
          $var_form_error='';
          foreach($get_payment_detail as $payment_detail)
          {

            if(!empty($error_field))
            {

              $var_form_error= $error_field; 
            }

            $html.='<div class="row m-b-5"><div class="col-md-5"><label>'.$payment_detail->field_name.'<label class="star">*</span></div> <div class="col-md-7"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>';
          }
          echo $html;exit;

        } 

        function mlc_print()
        {
         
      /*$data['page_title'] = "Medic-legal Certification";
      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
      $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
      
      $users_data = $this->session->userdata('auth_users'); 
      


      
      $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
      $data['template_data']=$template_format->setting_value;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';*/

      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
      $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
      //print_r($get_by_id_data);die;

      $data['get_by_id_data']=$get_by_id_data;
      //print '<pre>';print_r($data['get_by_id_data']['ipd_list'][0]);die;
      $this->load->view('ipd_booking/mlc_form_ipd',$data);
    }
    
    
    public function prescription($booking_id="")
    {
      
      $data['prescription_tab_setting'] = get_ipd_prescription_tab_setting();
      $data['prescription_medicine_tab_setting'] = get_ipd_prescription_medicine_tab_setting();
      $this->load->model('opd/opd_model','opd');
      $this->load->model('general/general_model'); 
      
      $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
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
      
      if($booking_id>0)
      {
       $this->load->model('ipd_booking/ipd_booking_model');
       $this->load->model('patient/patient_model');
       $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
           //echo "<pre>";print_r($opd_booking_data); exit;
       if(!empty($ipd_booking_data))
       {
         
               //present age of patient
         
        if($ipd_booking_data['dob']=='1970-01-01' || $ipd_booking_data['dob']=="0000-00-00")
        {
          $present_age = get_patient_present_age('',$ipd_booking_data);
                //echo "<pre>"; print_r($present_age);
        }
        else
        {
          $dob=date('d-m-Y',strtotime($ipd_booking_data['dob']));
          
          $present_age = get_patient_present_age($dob,$ipd_booking_data);
        }
        
        $age_y = $ipd_booking_data['age_y'];
        $age_m = $ipd_booking_data['age_m'];
        $age_d = $ipd_booking_data['age_d'];
              //$age_h = $present_age['age_h'];
              //present age of patient
        $booking_id = $ipd_booking_data['id'];
        $mlc_no = $ipd_booking_data['mlc'];
        $referral_doctor = $ipd_booking_data['referral_doctor'];
        $ipd_no = $ipd_booking_data['ipd_no'];
        $attended_doctor = $ipd_booking_data['attend_doctor_id'];
              $patient_id = $ipd_booking_data['patient_id'];//
              $simulation_id = $ipd_booking_data['simulation_id'];
              $patient_code = $ipd_booking_data['patient_code'];
              $patient_name = $ipd_booking_data['patient_name'];
              $mobile_no = $ipd_booking_data['mobile_no'];
              $gender = $ipd_booking_data['gender'];
              
              $address = $ipd_booking_data['address'];
              $city_id = $ipd_booking_data['city_id'];
              $state_id = $ipd_booking_data['state_id'];
              $country_id = $ipd_booking_data['country_id']; 
              $appointment_date = '';
              
              $relation_name = $ipd_booking_data['relation_name'];
              $relation_type = $ipd_booking_data['relation_type'];
              $relation_simulation_id = $ipd_booking_data['relation_simulation_id'];
              
              $aadhaar_no ='';
              if(!empty($ipd_booking_data['adhar_no']))
              {
                $aadhaar_no = $ipd_booking_data['adhar_no'];
              }

              $patient_bp = '';
              $patient_temp = '';
              $patient_weight = '';
              $patient_height = '';
              $patient_spo2 = '';
              $patient_rbs = '';
            }


          }
          $data['country_list'] = $this->general_model->country_list();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
          $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
          $data['employee_list'] = $this->opd->employee_list();
          $data['profile_list'] = $this->opd->profile_list();
          $data['dept_list'] = $this->general_model->department_list(); 
          $data['chief_complaints'] = $this->general_model->chief_complaint_list(); 
          $data['examination_list'] = $this->opd->examinations_list();
          $data['diagnosis_list'] = $this->opd->diagnosis_list();  
          $data['suggetion_list'] = $this->opd->suggetion_list();  
          $data['prv_history'] = $this->opd->prv_history_list();  
          $data['personal_history'] = $this->opd->personal_history_list();
          $data['template_list'] = $this->opd->template_list();    
          $data['vitals_list']=$this->general_model->vitals_list();
          $data['page_title'] = "Add Progress Report";
          
          $post = $this->input->post();
          
          $data['form_error'] = []; 
          $data['form_data'] = array(
            'data_id'=>"", 
            'patient_id'=>$patient_id,
            'booking_id'=>$booking_id,
            'attended_doctor'=>$attended_doctor,
            'appointment_date'=>$appointment_date,
            'patient_code'=>$patient_code,
            'ipd_no'=>$ipd_no,
            'mlc_no'=>$mlc_no,
            'simulation_id'=>$simulation_id,
            'patient_name'=>$patient_name,
            "aadhaar_no"=>$aadhaar_no,
            'mobile_no'=>$mobile_no,
            'gender'=>$gender,
            'age_y'=>$age_y,
            'age_m'=>$age_m,
            'age_d'=>$age_d,
            
            'address'=>$address,
            'city_id'=>$city_id,
            'state_id'=>$state_id,
            'country_id'=>$country_id,
            
            'prv_history'=>"",
            'personal_history'=>"",
            'chief_complaints'=>'',
            'examination'=>'',
            'diagnosis'=>'',
            'suggestion'=>'',
            'remark'=>'',
            'next_appointment_date'=>"",
            "relation_name"=>$relation_name,
            "relation_type"=>$relation_type,
            "relation_simulation_id"=>$relation_simulation_id,
          );
          if(isset($post) && !empty($post))
          {   
            
            $prescription_id = $this->ipd_booking->save_prescription();
            $this->session->set_userdata('ipd_prescription_id',$prescription_id);
            $this->session->set_flashdata('success','Prescription successfully added.');
            redirect(base_url('ipd_prescription/?status=print'));


          }   

          $this->load->view('ipd_booking/prescription',$data);
        }
        
        
        function select_discharge_date($booking_id,$patient_id,$type)
        {
          
          if($type==1)
          {
           $data['title'] = 'Discharge Bill';
         }
         else
         {
           $data['title'] = 'Modify Discharge Bill';
         }
         
         $data['ipd_id'] = $booking_id;
         $data['patient_id'] = $patient_id;
         $get_by_id_data = $this->ipd_booking->get_by_id($booking_id);
         $old_discharge_date = $get_by_id_data['discharge_date'];
         if(!empty($old_discharge_date) && $old_discharge_date!='0000-00-00 00:00:00')
         {
           $data['discharge_date'] = date('d-m-Y',strtotime($old_discharge_date));
           $data['discharge_date_time'] =  date('h:i A',strtotime($old_discharge_date));
         }
         else
         {
           $data['discharge_date'] = date('d-m-Y');
           $data['discharge_date_time'] = date('H:i:s');

         }
         $data['type'] = $type;
         $this->load->view('ipd_booking/discharge_bill_date', $data);
         
       }
       
       public function print_ipd_adminssion_consent($langid="",$id='')
       {
        $this->load->model('ipd_admission_print_setting/ipd_admission_print_setting_model','ipd_admission_print');
        $data['page_title'] = "Print Bookings";
        $ipd_booking_id= $this->session->userdata('ipd_booking_id');
        
        if(!empty($langid))
        {
          $lang_id = $langid;
        }
        else 
        {
          $lang_id =0;
        }
        

        if(!empty($id))
        {
          $ipd_booking_id = $id;
        }
        else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
        {
          $ipd_booking_id =$ipd_booking_id;
        }
        else
        {
          $ipd_booking_id = '';
        } 
        $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
      //echo "<pre>";print_r($get_by_id_data); exit;
        $branch_id='';
        $users_data = $this->session->userdata('auth_users'); 
        if($users_data['users_role']==3)
        {
          $doctor_id = $users_data['parent_id'];
          $this->load->model('branch/branch_model');
          $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
          $branch_id = $doctor_data[0]->branch_id; 
        } 

        $template_format = $this->ipd_admission_print->template_format_consent(array('setting_name'=>'IPD_PRINT_CONSENT','unique_id'=>1,'type'=>$lang_id),$branch_id);

        
        $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $get_by_id_data;
        $data['page_type'] = 'Booking';
        $this->load->view('ipd_booking/print_ipd_consent_form_template',$data);
      }
      
      public function print_ipd_adminssion_consent_new()
      {
         $f_type = $_GET['forid'];
         $ipd_booking_id = $_GET['ipd_id'];
        $this->load->model('ipd_admission_print_setting/ipd_admission_print_setting_model','ipd_admission_print');
        $data['page_title'] = "Print Bookings";
        
       
        $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
      //echo "<pre>";print_r($get_by_id_data); exit;
        $branch_id='';
        $users_data = $this->session->userdata('auth_users'); 
        if($users_data['users_role']==3)
        {
          $doctor_id = $users_data['parent_id'];
          $this->load->model('branch/branch_model');
          $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
          $branch_id = $doctor_data[0]->branch_id; 
        } 
        $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
        $data['all_detail']= $get_by_id_data;
        $data['page_type'] = 'Booking';
        $form_type = explode(",",$f_type);
        //print_r($form_type); die;
        //echo "<pre>"; print_r($data);
        $htm ='';
        if(!empty($form_type))
        {
            foreach($form_type as $type)
            {
                
                $template_format = $this->ipd_admission_print->template_format_consent_new(array('unique_id'=>1,'form_type'=>$type),$branch_id);
                $data['template_data']=$template_format->setting_value;
                $htm .= $this->load->view('ipd_booking/print_ipd_consent_form_template',$data,true);  
                $htm .= '<br> <br><br>';
            }
        }
        
        echo $htm;
      }
      public function print_ipd_adminssion_consent_new20220223($id='', $f_type)
      {
        $this->load->model('ipd_admission_print_setting/ipd_admission_print_setting_model','ipd_admission_print');
        $data['page_title'] = "Print Bookings";
        $ipd_booking_id= $this->session->userdata('ipd_booking_id');
        
        
        if(!empty($f_type))
        {
          $form_type = $f_type;
        }
        else 
        {
          $form_type =0;
        }

        if(!empty($id))
        {
          $ipd_booking_id = $id;
        }
        else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
        {
          $ipd_booking_id =$ipd_booking_id;
        }
        else
        {
          $ipd_booking_id = '';
        } 
        $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
      //echo "<pre>";print_r($get_by_id_data); exit;
        $branch_id='';
        $users_data = $this->session->userdata('auth_users'); 
        if($users_data['users_role']==3)
        {
          $doctor_id = $users_data['parent_id'];
          $this->load->model('branch/branch_model');
          $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
          $branch_id = $doctor_data[0]->branch_id; 
        } 

        $template_format = $this->ipd_admission_print->template_format_consent_new(array('unique_id'=>1,'form_type'=>$form_type),$branch_id);

        $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $get_by_id_data;
        $data['page_type'] = 'Booking';
        $this->load->view('ipd_booking/print_ipd_consent_form_template',$data);
      }
      
      
      function gipsa_select_discharge_date($booking_id,$patient_id,$type)
      {
        
        if($type==1)
        {
          $data['title'] = 'Discharge Bill';
        }
        else
        {
          $data['title'] = 'Modify Discharge Bill';
        }
        
        $data['ipd_id'] = $booking_id;
        $data['patient_id'] = $patient_id;
        $get_by_id_data = $this->ipd_booking->get_by_id($booking_id);
        $old_discharge_date = $get_by_id_data['discharge_date'];
        if(!empty($old_discharge_date) && $old_discharge_date!='0000-00-00 00:00:00')
        {
          $data['discharge_date'] = date('d-m-Y',strtotime($old_discharge_date));
          $data['discharge_date_time'] =  date('h:i A',strtotime($old_discharge_date));
        }
        else
        {
          $data['discharge_date'] = date('d-m-Y');
          $data['discharge_date_time'] = date('H:i:s');

        }
        $data['type'] = $type;
        $this->load->view('ipd_booking/gipsa_discharge_bill_date', $data);
        
      }
      
      
      public function print_label($ipd_id,$total_no)
      {
        
        if(!empty($ipd_id))
        {
          $get_by_id_data =$this->ipd_booking->get_by_id($ipd_id);
        //print_r($get_by_id_data); die;

          $gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');
          $age_y = $get_by_id_data['age_y'];
          $age_m = $get_by_id_data['age_m'];
          $age_d = $get_by_id_data['age_d'];
          $age_h = $get_by_id_data['age_h'];
          $age = "";
          if($age_y>0)
          {
            $year = 'Years';
            if($age_y==1)
            {
              $year = 'Year';
            }
            $age .= $age_y." ".$year;
          }
          if($age_m>0)
          {
            $month = 'Months';
            if($age_m==1)
            {
              $month = 'Month';
            }
            $age .= ", ".$age_m." ".$month;
          }
          if($age_d>0)
          {
            $day = 'Days';
            if($age_d==1)
            {
              $day = 'Day';
            }
            $age .= ", ".$age_d." ".$day;
          }
          if($age_h>0)
          {
            $hours = 'Hours';
            
            $age .= " ".$age_h." ".$hours;
          } 

          $gender =  $gender[$get_by_id_data['gender']];

          $admission_date =  date('d-m-Y',strtotime($get_by_id_data['admission_date'])).' '.date('h:i',strtotime($get_by_id_data['admission_time']));

          $data['patient_name'] = $get_by_id_data['patient_name'];
          $data['barcode_text'] = $get_by_id_data['ipd_no'];
          $data['gender_age'] = $gender.'/'.$age;
          $data['patient_code'] = $get_by_id_data['patient_code'];
          $data['admission_date'] = $admission_date;
          $data['mobile_no'] = $get_by_id_data['mobile_no'];
          $data['total_no'] = $total_no;
          
          $data['gender'] = $gender;
          $data['age'] = $age;
          
          $this->load->view('ipd_booking/print_label_template',$data);
        }
      }
      
      public function print_label_sixteen($ipd_id,$total_no)
      {
        
        if(!empty($ipd_id))
        {
          $get_by_id_data =$this->ipd_booking->get_by_id($ipd_id);
        //print_r($get_by_id_data); die;

          $gender = array('0'=>'F', '1'=>'M','2'=>'O');
          $age_y = $get_by_id_data['age_y'];
          $age_m = $get_by_id_data['age_m'];
          $age_d = $get_by_id_data['age_d'];
          $age_h = $get_by_id_data['age_h'];
          $age = "";
          if($age_y>0)
          {
            $year = 'Y';
            if($age_y==1)
            {
              $year = 'Year';
            }
            $age .= $age_y.$year;
          }
          if($age_m>0)
          {
            $month = 'M';
            if($age_m==1)
            {
              $month = 'M';
            }
            $age .= " ".$age_m.$month;
          }
         

          $gender =  $gender[$get_by_id_data['gender']];

          $admission_date =  date('d-m-Y',strtotime($get_by_id_data['admission_date'])).' '.date('h:i A',strtotime($get_by_id_data['admission_time']));
          
          $admission_dates =  date('d-m-Y',strtotime($get_by_id_data['admission_date']));
          
          $admission_times =  date('h:i A',strtotime($get_by_id_data['admission_time']));

          $data['patient_name'] = $get_by_id_data['patient_name'];
          $data['barcode_text'] = $get_by_id_data['ipd_no'];
          $data['gender_age'] = $gender.' / '.$age;
          $data['patient_code'] = $get_by_id_data['patient_code'];
          $data['admission_date'] = $admission_date;
          $data['admission_dates'] = $admission_dates;
          $data['admission_times'] = $admission_times;
          $data['mobile_no'] = $get_by_id_data['mobile_no'];
          $data['attend_doctor_id']= $get_by_id_data['attend_doctor_id'];
          $data['total_no'] = $total_no;
          
          $data['gender'] = $gender;
          $data['age'] = $age;
          
          $this->load->view('ipd_booking/print_label_sixteen_template',$data);
        }
      }
      
      
      public function print_template($id)
      {
        $data['page_title'] = 'Select No of Label';
        //$post = $this->input->post();  
        $data['id'] = $id;
        $this->load->view('ipd_booking/template',$data);
        
      }
      
      public function print_label_sixteen_template($id)
      {
        $data['page_title'] = 'Select No of Label';
        //$post = $this->input->post();  
        $data['id'] = $id;
        $this->load->view('ipd_booking/sixteen_template',$data);
        
      }
      
      public function print_label_twentyfour_template($id)
      {
        $data['page_title'] = 'Select No of Label';
        //$post = $this->input->post();  
        $data['id'] = $id;
        $this->load->view('ipd_booking/twentyfour_template',$data);
        
      }
      
      public function print_label_thirtysix_template($id)
      {
        $data['page_title'] = 'Select No of Label';
        //$post = $this->input->post();  
        $data['id'] = $id;
        $this->load->view('ipd_booking/thirtysix_template',$data);
        
      }
      
      
      public function print_barcode($id)
      {
        $patient_data = $this->ipd_booking->get_by_id($id); 
        $data['barcode_id'] = $patient_data['ipd_no'];
        if(!empty($data['barcode_id']))
        {
          $this->load->view('patient/barcode',$data);
        }
      }
      
      public function print_label_twentyfour($ipd_id,$total_no)
      {
        
        if(!empty($ipd_id))
        {
          $get_by_id_data =$this->ipd_booking->get_by_id($ipd_id);
        //print_r($get_by_id_data); die;

          $gender = array('0'=>'F', '1'=>'M','2'=>'O');
          $age_y = $get_by_id_data['age_y'];
          $age_m = $get_by_id_data['age_m'];
          $age_d = $get_by_id_data['age_d'];
          $age_h = $get_by_id_data['age_h'];
          $age = "";
          if($age_y>0)
          {
            $year = 'Y';
            if($age_y==1)
            {
              $year = 'Year';
            }
            $age .= $age_y.$year;
          }
          if($age_m>0)
          {
            $month = 'M';
            if($age_m==1)
            {
              $month = 'M';
            }
            $age .= " ".$age_m.$month;
          }
         

          $gender =  $gender[$get_by_id_data['gender']];

          $admission_date =  date('d-m-Y',strtotime($get_by_id_data['admission_date'])).' '.date('h:i A',strtotime($get_by_id_data['admission_time']));
          
          $admission_dates =  date('d-m-Y',strtotime($get_by_id_data['admission_date']));
          
          $admission_times =  date('h:i',strtotime($get_by_id_data['admission_time']));

          $data['patient_name'] = $get_by_id_data['patient_name'];
          $data['barcode_text'] = $get_by_id_data['ipd_no'];
          $data['gender_age'] = $gender.' / '.$age;
          $data['patient_code'] = $get_by_id_data['patient_code'];
          $data['admission_date'] = $admission_date;
          $data['admission_dates'] = $admission_dates;
          $data['admission_times'] = $admission_times;
          $data['mobile_no'] = $get_by_id_data['mobile_no'];
          $data['attend_doctor_id']= $get_by_id_data['attend_doctor_id'];
          $data['total_no'] = $total_no;
          
          $data['gender'] = $gender;
          $data['age'] = $age;
          
          $this->load->view('ipd_booking/print_label_twentyfour_template',$data);
        }
      }
      
      
      public function print_label_thirtysix($ipd_id,$total_no)
      {
        
        if(!empty($ipd_id))
        {
          $get_by_id_data =$this->ipd_booking->get_by_id($ipd_id);
        //print_r($get_by_id_data); die;

          $gender = array('0'=>'F', '1'=>'M','2'=>'O');
          $age_y = $get_by_id_data['age_y'];
          $age_m = $get_by_id_data['age_m'];
          $age_d = $get_by_id_data['age_d'];
          $age_h = $get_by_id_data['age_h'];
          $age = "";
          if($age_y>0)
          {
            $year = 'Y';
            if($age_y==1)
            {
              $year = 'Year';
            }
            $age .= $age_y.$year;
          }
          if($age_m>0)
          {
            $month = 'M';
            if($age_m==1)
            {
              $month = 'M';
            }
            $age .= " ".$age_m.$month;
          }
         

          $gender =  $gender[$get_by_id_data['gender']];

          $admission_date =  date('d-m-Y',strtotime($get_by_id_data['admission_date'])).' '.date('h:i A',strtotime($get_by_id_data['admission_time']));
          
          $admission_dates =  date('d-m-Y',strtotime($get_by_id_data['admission_date']));
          
          $admission_times =  date('h:i',strtotime($get_by_id_data['admission_time']));

          $data['patient_name'] = $get_by_id_data['patient_name'];
          $data['barcode_text'] = $get_by_id_data['ipd_no'];
          $data['gender_age'] = $gender.' / '.$age;
          $data['patient_code'] = $get_by_id_data['patient_code'];
          $data['admission_date'] = $admission_date;
          $data['admission_dates'] = $admission_dates;
          $data['admission_times'] = $admission_times;
          $data['mobile_no'] = $get_by_id_data['mobile_no'];
          $data['attend_doctor_id']= $get_by_id_data['attend_doctor_id'];
          $data['total_no'] = $total_no;
          
          $data['gender'] = $gender;
          $data['age'] = $age;
          
          $this->load->view('ipd_booking/print_label_thirtysix_template',$data);
        }
      }
      
      
    public function admission_prescription($booking_id="")
    {
      
      $data['prescription_tab_setting'] = get_ipd_admission_prescription_tab_setting();
      $data['prescription_medicine_tab_setting'] = get_ipd_admission_prescription_medicine_tab_setting();
      $this->load->model('opd/opd_model','opd');
      $this->load->model('general/general_model'); 
      
      $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
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
      
      if($booking_id>0)
      {
       $this->load->model('ipd_booking/ipd_booking_model');
       $this->load->model('patient/patient_model');
       $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
           //echo "<pre>";print_r($opd_booking_data); exit;
       if(!empty($ipd_booking_data))
       {
         
               //present age of patient
         
        /*if($ipd_booking_data['dob']=='1970-01-01' || $ipd_booking_data['dob']=="0000-00-00")
        {
          $present_age = get_patient_present_age('',$ipd_booking_data);
                //echo "<pre>"; print_r($present_age);
        }
        else
        {
          $dob=date('d-m-Y',strtotime($ipd_booking_data['dob']));
          
          $present_age = get_patient_present_age($dob,$ipd_booking_data);
        }*/
        
        $age_y = $ipd_booking_data['age_y'];
        $age_m = $ipd_booking_data['age_m'];
        $age_d = $ipd_booking_data['age_d'];
              //$age_h = $present_age['age_h'];
              //present age of patient
        $booking_id = $ipd_booking_data['id'];
        $mlc_no = $ipd_booking_data['mlc'];
        $referral_doctor = $ipd_booking_data['referral_doctor'];
        $ipd_no = $ipd_booking_data['ipd_no'];
        $attended_doctor = $ipd_booking_data['attend_doctor_id'];
              $patient_id = $ipd_booking_data['patient_id'];//
              $simulation_id = $ipd_booking_data['simulation_id'];
              $patient_code = $ipd_booking_data['patient_code'];
              $patient_name = $ipd_booking_data['patient_name'];
              $mobile_no = $ipd_booking_data['mobile_no'];
              $gender = $ipd_booking_data['gender'];
              
              $address = $ipd_booking_data['address'];
              $city_id = $ipd_booking_data['city_id'];
              $state_id = $ipd_booking_data['state_id'];
              $country_id = $ipd_booking_data['country_id']; 
              $appointment_date = '';
              
              $relation_name = $ipd_booking_data['relation_name'];
              $relation_type = $ipd_booking_data['relation_type'];
              $relation_simulation_id = $ipd_booking_data['relation_simulation_id'];
              
              $aadhaar_no ='';
              if(!empty($ipd_booking_data['adhar_no']))
              {
                $aadhaar_no = $ipd_booking_data['adhar_no'];
              }

              $patient_bp = '';
              $patient_temp = '';
              $patient_weight = '';
              $patient_height = '';
              $patient_spo2 = '';
              $patient_rbs = '';
            }


          }
          $data['country_list'] = $this->general_model->country_list();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
          $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
          //$data['employee_list'] = $this->opd->employee_list();
          //$data['profile_list'] = $this->opd->profile_list();
          //$data['dept_list'] = $this->general_model->department_list(); 
          $data['chief_complaints'] = $this->general_model->admission_chief_complaint_list(); 
          $data['examination_list'] = $this->opd->admission_examinations_list();
          $data['diagnosis_list'] = $this->opd->admission_diagnosis_list();  
          $data['suggetion_list'] = $this->opd->admission_suggetion_list();  
          $data['history_presenting_illness_list'] = $this->opd->history_presenting_illness_list();  //Added by Nitin Sharma 28th Jan 2024
          $data['obstetrics_menstrual_history_list'] = $this->opd->obstetrics_menstrual_history_list();  //Added by Nitin Sharma 28th Jan 2024
          $data['family_history_disease_list'] = $this->opd->family_history_disease_list();  //Added by Nitin Sharma 28th Jan 2024
          $data['prv_history'] = $this->opd->admission_prv_history_list();  
          $data['personal_history'] = $this->opd->admission_personal_history_list();
          $data['template_list'] = $this->opd->admission_template_list();    
          $data['vitals_list']=$this->general_model->vitals_list();
          $data['page_title'] = "Add Admission Progress Report";
          
          $post = $this->input->post();
          
          $data['form_error'] = []; 
          $data['form_data'] = array(
            'data_id'=>"", 
            'patient_id'=>$patient_id,
            'booking_id'=>$booking_id,
            'attended_doctor'=>$attended_doctor,
            'appointment_date'=>$appointment_date,
            'patient_code'=>$patient_code,
            'ipd_no'=>$ipd_no,
            'mlc_no'=>$mlc_no,
            'simulation_id'=>$simulation_id,
            'patient_name'=>$patient_name,
            "aadhaar_no"=>$aadhaar_no,
            'mobile_no'=>$mobile_no,
            'gender'=>$gender,
            'age_y'=>$age_y,
            'age_m'=>$age_m,
            'age_d'=>$age_d,
            
            'address'=>$address,
            'city_id'=>$city_id,
            'state_id'=>$state_id,
            'country_id'=>$country_id,
            
            'prv_history'=>"",
            'personal_history'=>"",
            'chief_complaints'=>'',
            'examination'=>'',
            'diagnosis'=>'',
            'suggestion'=>'',
            'remark'=>'',
            'history_presenting_illness'=>"", // Added by Nitin Sharma 28th Jan 2024
            'obstetrics_menstrual_history'=>"", // Added by Nitin Sharma 28th Jan 2024
            'family_history_disease'=>"", // Added by Nitin Sharma 28th Jan 2024
            'remark1'=>"", // Added by Nitin Sharma 28th Jan 2024
            'remark2'=>"", // Added by Nitin Sharma 28th Jan 2024
            'remark3'=>"", // Added by Nitin Sharma 28th Jan 2024
            'remark4'=>"", // Added by Nitin Sharma 28th Jan 2024
            'remark5'=>"", // Added by Nitin Sharma 28th Jan 2024
            'examination_type'=>"", // Added by Nitin Sharma 28th Jan 2024
            'cvs'=>"", // Added by Nitin Sharma 28th Jan 2024
            'cns'=>"", // Added by Nitin Sharma 28th Jan 2024
            'respiratory_system'=>"", // Added by Nitin Sharma 28th Jan 2024
            'per_abdomen'=>"", // Added by Nitin Sharma 28th Jan 2024
            'per_vaginal'=>"", // Added by Nitin Sharma 28th Jan 2024
            'local_examination'=>"", // Added by Nitin Sharma 28th Jan 2024
            'next_appointment_date'=>"",
            "relation_name"=>$relation_name,
            "relation_type"=>$relation_type,
            "relation_simulation_id"=>$relation_simulation_id,
          );
          if(isset($post) && !empty($post))
          {   
            $prescription_id = $this->ipd_booking->save_admission_prescription();
            $this->session->set_userdata('ipd_admission_prescription_id',$prescription_id);
            $this->session->set_flashdata('success','Admission Prescription successfully added.');
            redirect(base_url('ipd_admissionnotes?status=print')); // changes by Nitin Sharma 03/02/2024
          }   
          $data['pain_score'] = $this->pain_score->list();
          $data['nutritional_screening'] = $this->nutritional_screening->list();
          $data['plan_of_care'] = $this->plan_of_care->list();
          $data['advice_master'] = $this->advice_master->list();
          $data['initial_assessment_performed_by_doctor'] = $this->initial_assessment_performed_by_doctor->list();

          $this->load->view('ipd_booking/admission_prescription',$data);
    }
        
    public function nursing_prescription($booking_id="")
    {
      
      $data['prescription_tab_setting'] = get_ipd_nursing_prescription_tab_setting();
      $data['prescription_medicine_tab_setting'] = get_ipd_nursing_prescription_medicine_tab_setting();
      $this->load->model('opd/opd_model','opd');
      $this->load->model('general/general_model'); 
      
      $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
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
      
      if($booking_id>0)
      {
       $this->load->model('ipd_booking/ipd_booking_model');
       $this->load->model('patient/patient_model');
       $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
           //echo "<pre>";print_r($opd_booking_data); exit;
       if(!empty($ipd_booking_data))
       {
         
               //present age of patient
         
        /*if($ipd_booking_data['dob']=='1970-01-01' || $ipd_booking_data['dob']=="0000-00-00")
        {
          $present_age = get_patient_present_age('',$ipd_booking_data);
                //echo "<pre>"; print_r($present_age);
        }
        else
        {
          $dob=date('d-m-Y',strtotime($ipd_booking_data['dob']));
          
          $present_age = get_patient_present_age($dob,$ipd_booking_data);
        }*/
        
            $age_y = $ipd_booking_data['age_y'];
            $age_m = $ipd_booking_data['age_m'];
            $age_d = $ipd_booking_data['age_d'];
                  //$age_h = $present_age['age_h'];
                  //present age of patient
            $booking_id = $ipd_booking_data['id'];
            $mlc_no = $ipd_booking_data['mlc'];
            $referral_doctor = $ipd_booking_data['referral_doctor'];
            $ipd_no = $ipd_booking_data['ipd_no'];
            $attended_doctor = $ipd_booking_data['attend_doctor_id'];
              $patient_id = $ipd_booking_data['patient_id'];//
              $simulation_id = $ipd_booking_data['simulation_id'];
              $patient_code = $ipd_booking_data['patient_code'];
              $patient_name = $ipd_booking_data['patient_name'];
              $mobile_no = $ipd_booking_data['mobile_no'];
              $gender = $ipd_booking_data['gender'];
              
              $address = $ipd_booking_data['address'];
              $city_id = $ipd_booking_data['city_id'];
              $state_id = $ipd_booking_data['state_id'];
              $country_id = $ipd_booking_data['country_id']; 
              $appointment_date = '';
              
              $relation_name = $ipd_booking_data['relation_name'];
              $relation_type = $ipd_booking_data['relation_type'];
              $relation_simulation_id = $ipd_booking_data['relation_simulation_id'];
              
              $aadhaar_no ='';
              if(!empty($ipd_booking_data['adhar_no']))
              {
                $aadhaar_no = $ipd_booking_data['adhar_no'];
              }

              $patient_bp = '';
              $patient_temp = '';
              $patient_weight = '';
              $patient_height = '';
              $patient_spo2 = '';
              $patient_rbs = '';
            }


          }
          $data['country_list'] = $this->general_model->country_list();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
          $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
          /*$data['employee_list'] = $this->opd->employee_list();
          $data['profile_list'] = $this->opd->profile_list();
          $data['dept_list'] = $this->general_model->department_list();*/ 
          $data['chief_complaints'] = $this->general_model->nursing_chief_complaint_list(); 
          $data['examination_list'] = $this->opd->nursing_examinations_list();
          $data['diagnosis_list'] = $this->opd->nursing_diagnosis_list();  
          $data['suggetion_list'] = $this->opd->nursing_suggetion_list();  
          $data['prv_history'] = $this->opd->nursing_prv_history_list();  
          $data['personal_history'] = $this->opd->nursing_personal_history_list();
          $data['template_list'] = $this->opd->nursing_template_list();    
          $data['vitals_list']=$this->general_model->vitals_list();
          $data['page_title'] = "Add Nursing Progress Report";
          
          $post = $this->input->post();
          
          $data['form_error'] = []; 
          $data['form_data'] = array(
            'data_id'=>"", 
            'patient_id'=>$patient_id,
            'booking_id'=>$booking_id,
            'attended_doctor'=>$attended_doctor,
            'appointment_date'=>$appointment_date,
            'patient_code'=>$patient_code,
            'ipd_no'=>$ipd_no,
            'mlc_no'=>$mlc_no,
            'simulation_id'=>$simulation_id,
            'patient_name'=>$patient_name,
            "aadhaar_no"=>$aadhaar_no,
            'mobile_no'=>$mobile_no,
            'gender'=>$gender,
            'age_y'=>$age_y,
            'age_m'=>$age_m,
            'age_d'=>$age_d,
            
            'address'=>$address,
            'city_id'=>$city_id,
            'state_id'=>$state_id,
            'country_id'=>$country_id,
            
            'prv_history'=>"",
            'personal_history'=>"",
            'chief_complaints'=>'',
            'examination'=>'',
            'diagnosis'=>'',
            'suggestion'=>'',
            'remark'=>'',
            'next_appointment_date'=>"",
            "relation_name"=>$relation_name,
            "relation_type"=>$relation_type,
            "relation_simulation_id"=>$relation_simulation_id,
            "shift" => ""
          );
          if(isset($post) && !empty($post))
          {   
            
            $prescription_id = $this->ipd_booking->save_nursing_prescription();
            $this->session->set_userdata('ipd_nursing_prescription_id',$prescription_id);
            $this->session->set_flashdata('success','Nursing Prescription successfully added.');
            redirect(base_url('ipd_admissionnotes/?status=print'));


          }   

          $this->load->view('ipd_booking/nursing_prescription',$data);
        }
    //   Added By Nitin Sharma 02/02/2024
     function view_test_report($patient_id){
        $this->load->model('patient/Patient_model','patient');
        $get_by_id_data = $this->patient->get_by_id($patient_id);
        $data['page_title'] = 'Test List - ' .$get_by_id_data['patient_name'].' - '.$get_by_id_data['patient_code'];  
        $data['booking_id'] = $patient_id;
        $this->load->view('test/complete_list',$data);
    }

    public function nurses_notes($booking_id)
    {
      $this->load->model('opd/opd_model','opd');
      $this->load->model('general/general_model'); 
      
      $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
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
      
      if($booking_id>0)
      {
       $this->load->model('ipd_booking/ipd_booking_model');
       $this->load->model('patient/patient_model');
       $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
      
          //  echo "<pre>";print_r($opd_booking_data); exit;
       if(!empty($ipd_booking_data))
       {
         
               //present age of patient
         
        /*if($ipd_booking_data['dob']=='1970-01-01' || $ipd_booking_data['dob']=="0000-00-00")
        {
          $present_age = get_patient_present_age('',$ipd_booking_data);
                //echo "<pre>"; print_r($present_age);
        }
        else
        {
          $dob=date('d-m-Y',strtotime($ipd_booking_data['dob']));
          
          $present_age = get_patient_present_age($dob,$ipd_booking_data);
        }*/
        
            $age_y = $ipd_booking_data['age_y'];
            $age_m = $ipd_booking_data['age_m'];
            $age_d = $ipd_booking_data['age_d'];
                  //$age_h = $present_age['age_h'];
                  //present age of patient
            $booking_id = $ipd_booking_data['id'];
            $mlc_no = $ipd_booking_data['mlc'];
            $referral_doctor = $ipd_booking_data['referral_doctor'];
            $ipd_no = $ipd_booking_data['ipd_no'];
            $attended_doctor = $ipd_booking_data['attend_doctor_id'];
              $patient_id = $ipd_booking_data['patient_id'];//
              $simulation_id = $ipd_booking_data['simulation_id'];
              $patient_code = $ipd_booking_data['patient_code'];
              $patient_name = $ipd_booking_data['patient_name'];
              $mobile_no = $ipd_booking_data['mobile_no'];
              $gender = $ipd_booking_data['gender'];
              
              $address = $ipd_booking_data['address'];
              $city_id = $ipd_booking_data['city_id'];
              $state_id = $ipd_booking_data['state_id'];
              $country_id = $ipd_booking_data['country_id']; 
              $appointment_date = '';
              
              $relation_name = $ipd_booking_data['relation_name'];
              $relation_type = $ipd_booking_data['relation_type'];
              $relation_simulation_id = $ipd_booking_data['relation_simulation_id'];
              
              $aadhaar_no ='';
              if(!empty($ipd_booking_data['adhar_no']))
              {
                $aadhaar_no = $ipd_booking_data['adhar_no'];
              }

              $patient_bp = '';
              $patient_temp = '';
              $patient_weight = '';
              $patient_height = '';
              $patient_spo2 = '';
              $patient_rbs = '';
            }


          }
          $data['country_list'] = $this->general_model->country_list();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
          $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
          /*$data['employee_list'] = $this->opd->employee_list();
          $data['profile_list'] = $this->opd->profile_list();
          $data['dept_list'] = $this->general_model->department_list();*/ 
        
          $data['template_list'] = $this->opd->nursing_template_list();    
          
          $data['page_title'] = "Add Nurses Notes";
          
          $post = $this->input->post();
          
          $data['form_error'] = []; 
          $data['form_data'] = array(
            'data_id'=>"", 
            'patient_id'=>$patient_id,
            'booking_id'=>$booking_id,
            'attended_doctor'=>$attended_doctor,
            'appointment_date'=>$appointment_date,
            'patient_code'=>$patient_code,
            'ipd_no'=>$ipd_no,
            'mlc_no'=>$mlc_no,
            'simulation_id'=>$simulation_id,
            'patient_name'=>$patient_name,
            "aadhaar_no"=>$aadhaar_no,
            'mobile_no'=>$mobile_no,
            'gender'=>$gender,
            'age_y'=>$age_y,
            'age_m'=>$age_m,
            'age_d'=>$age_d,
            
            'address'=>$address,
            'city_id'=>$city_id,
            'state_id'=>$state_id,
            'country_id'=>$country_id,
            
            'prv_history'=>"",
            'personal_history'=>"",
            'chief_complaints'=>'',
            'examination'=>'',
            'diagnosis'=>'',
            'suggestion'=>'',
            'remark'=>'',
            'next_appointment_date'=>"",
            "relation_name"=>$relation_name,
            "relation_type"=>$relation_type,
            "relation_simulation_id"=>$relation_simulation_id,
          );
          if(isset($post) && !empty($post))
          {   
            
            // dd($post);
            $this->load->database();
            $this->db->where('ipd_id',$booking_id);
            $this->db->delete('hms_nurses_notes');
            
           
              foreach($post['date'] as $key => $value) {
                $insert_data = array(
                  'date' => date('Y-m-d',strtotime($post['date'][$key])),
                  'shift' => $post['shift'][$key],
                  'note' => $post['note'][$key],
                  'remark' => $post['remark'][$key],
                  'ipd_id' => $post['booking_id'],
                  'patient_id' => $post['patient_id'],
                  'attended_doctor' => $post['attended_doctor'],
                  'ipd_no' => $post['ipd_no'],
                  'patient_code' => $post['patient_code'],
                );
                $this->db->insert('hms_nurses_notes',$insert_data);
              }
          

            // $prescription_id = $this->ipd_booking->save_nursing_prescription();
            $this->session->set_userdata('nurses_print_ipd_id',$booking_id);
            $this->session->set_flashdata('success','Nurses Notes successfully added.');
            redirect(base_url('ipd_booking?status=print_nurses_note'));


          }   

          $data['nurses_note_list'] = $this->db->where('ipd_id',$booking_id)->get('hms_nurses_notes')->result_array();
          $this->load->model('nurses_note_template/Nurses_note_template_model','nurses_note_template');
          $data['template_list'] = $this->nurses_note_template->nurses_note_list();
         
          $this->load->view('ipd_booking/nurses_notes',$data);
    }

    public function print_nurses_notes($booking_id="")
    {
      $data = [];
      if(empty($booking_id)) {
          $booking_id = $this->session->userdata('nurses_print_ipd_id');
      }
      $this->load->model('ipd_booking/ipd_booking_model');
      $this->load->model('patient/patient_model');
      $ipd_booking_data = $this->ipd_booking_model->get_by_id($booking_id);
      $patient_id = $ipd_booking_data['patient_id'];
      $data['medication_chart_list'] = $this->db->where(['booking_id'=>$booking_id,'patient_id'=>$patient_id])->get('hms_ipd_medication_chart')->result_array();
      $data['data'] = $ipd_booking_data; //attend_doctor_id
      $data['doctor'] = get_doctor_signature($ipd_booking_data['attend_doctor_id']);
      $this->load->model('general/general_model');  
      $data['panel_company_name'] = $this->general_model->panel_company_details($data['data']['panel_name']);
      $data['nurses_note_list'] = $this->db->where('ipd_id',$booking_id)->get('hms_nurses_notes')->result_array();
      $this->load->view('ipd_booking/print_nurses_notes',$data);
    }

    public function get_nurses_note_template_details() {
      if($post = $this->input->post()) {
        $this->load->model('nurses_note_template/Nurses_note_template_model','nurses_note_template');
        $data = $this->nurses_note_template->get_by_id($post['id']);
        echo $data['content'];
      }
    }
    public function diagnosis_listText() {
      $term = $this->input->post('term');
      $data = [];
      
      foreach ($term as $key => $value) {
          $this->db->select('id, diagnosis');
          $this->db->like('diagnosis', $value);
          $data1 = $this->db->get('hms_opd_diagnosis')->row_array();
          // dd($this->db->last_query());
          // if(count($data) > 0) {
              $data[$key] = $data1;
          // }
      }
      echo json_encode($data);
  }
    //   Ended By Nitin Sharma 02/02/2024

    }
