<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_patient_advance_discharge_summary extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_patient_discharge_summary/ipd_patient_advance_discharge_summary_model','ipd_discharge_summary');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('124','749');
        $data['page_title'] = 'IPD Patient Discharge Summary List'; 
        $this->load->view('ipd_patient_discharge_summary/list_advance',$data);
    }

   

    public function ajax_list()
    { 
        unauthorise_permission('124','749');
        $users_data = $this->session->userdata('auth_users'); 
    
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->ipd_discharge_summary->get_datatables();
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_discharge_summary) {
         // print_r($ipd_discharge_summary);die;;
            $no++;
            $row = array();
            if($ipd_discharge_summary->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List d /////////////////
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
            $checkboxs = "";
            if($users_data['parent_id']==$ipd_discharge_summary->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_discharge_summary->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $ipd_discharge_summary->ipd_no;
            $row[] = $ipd_discharge_summary->patient_code;
            $row[] = $ipd_discharge_summary->patient_name;
            $row[] = $ipd_discharge_summary->mobile_no;

             
            $row[] = $status;
            $row[] = date('d-M-Y h:i A',strtotime($ipd_discharge_summary->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_discharge_summary->branch_id)
            {
              if(in_array('751',$users_data['permission']['action'])){
               $btnedit =' <a  href="'.base_url("ipd_patient_advance_discharge_summary/edit/".$ipd_discharge_summary->id).'" class="btn-custom" style="'.$ipd_discharge_summary->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('752',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_discharge_summary('.$ipd_discharge_summary->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
            $btnmedic ='';
            if(in_array('756',$users_data['permission']['action']))
            {
            $btnmedic =' <a  href="'.base_url("ipd_patient_advance_discharge_summary/add_medicine/".$ipd_discharge_summary->id).'" class="btn-custom" style="'.$ipd_discharge_summary->id.'" title="Add Medicine"><i class="fa fa-pencil" aria-hidden="true"></i> Add Medicine</a>';
            }
            $btnmedicine_view ='';
            if(in_array('757',$users_data['permission']['action']))
            {
            $btnmedicine_view =' <a  href="'.base_url("ipd_patient_advance_discharge_summary/view_medicine/".$ipd_discharge_summary->id).'" class="btn-custom" style="'.$ipd_discharge_summary->id.'" title="Add Medicine"><i class="fa fa-pencil" aria-hidden="true"></i> View Medicine</a>';
            }

             $print_pdf_url = "'".base_url('ipd_patient_advance_discharge_summary/print_discharge_summary/'.$ipd_discharge_summary->id)."'";
             // <a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>
               //$btn_print = '<a id="ipd_summary_print" class="btn-custom" href="javascript:void(0);"> Print </a>';
            $btn_print='';
            if($users_data['parent_id']!='113')
             { 
               $btn_print = ' <a onClick="return print_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_discharge_summary->id.'" title="Print"><i class="fa fa-print"></i> Print</a>';
             }
    
      $btn_letter_print = ' <a onClick="return print_letter_head_summary('.$ipd_discharge_summary->id.','.$ipd_discharge_summary->branch_id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_discharge_summary->id.'" title="Print letter head"><i class="fa fa-print"></i> Print letter head</a>';
      
            $btn_medicine_print='';
            if(in_array('758',$users_data['permission']['action']))
            {  
              $print_medicine_url = "'".base_url('ipd_patient_advance_discharge_summary/print_discharge_summary_medicine/'.$ipd_discharge_summary->id)."'";
             $btn_medicine_print = '<a href="javascript:void(0)" class="btn-custom" onClick="return print_window_page('.$print_medicine_url.');"> <i class="fa fa-print"></i> Print Medicine</a>';
            }
            
            $btn_advance='';
           if($users_data['parent_id']=='113')
           {
              $btneditadvance =' <a  href="'.base_url("ipd_patient_advance_discharge_summary/edit/".$ipd_discharge_summary->id).'" class="btn-custom" style="'.$ipd_discharge_summary->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';

             $print_advance_print = ' <a onClick="return print_advance_summary('.$ipd_discharge_summary->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_discharge_summary->id.'" title="Print"><i class="fa fa-print"></i> Print</a>';


              $btn_advance = '<div class="slidedown">
              <button disabled class="btn-custom">More <span class="caret"></span></button>
                <ul class="slidedown-content">
                '.$btneditadvance.$print_advance_print.'
              </ul>
            </div> ';
           }
           
           
             $row[] = $btnedit.$btndelete.$btn_print.$btn_letter_print.$btnmedic.$btnmedicine_view.$btn_medicine_print.$btn_advance;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_discharge_summary->count_all(),
                        "recordsFiltered" => $this->ipd_discharge_summary->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add($ipd_id="",$patient_id="")
    {
        unauthorise_permission('124','750');
        $data['page_title'] = "Add IPD Discharge Summary";  
        $post = $this->input->post();
        $data['adv_template_list'] = $this->ipd_discharge_summary->advance_template_list();
        
        //echo '<pre>'; print_r($advance_template_list);die; //patient_details
        //
        $get = $this->input->get();
        
        $data['form_error'] = []; 
        if(!empty($get['tid']))
        {
          $advance_template_list = $this->ipd_discharge_summary->advance_template_list($get['tid']);
          if(!empty($advance_template_list))
          {
            $advance_template_list = $advance_template_list[0];
            $data['form_data'] = array(
                    'data_id'=>'', 
                    'ipd_id'=>$ipd_id, 
                    'patient_id'=>$patient_id, 
                    'diagnosis'=>$advance_template_list['diagnosis'],
                    'past_history'=>$advance_template_list['past_history'],
                    'family_history'=>$advance_template_list['family_history'],
                    'birth_history'=>$advance_template_list['birth_history'],
                    'general_history'=>$advance_template_list['general_history'],
                    'menstrual_history'=>$advance_template_list['menstrual_history'],
                    'obstetric_history'=>$advance_template_list['obstetric_history'],
                    'systemic_examination'=>$advance_template_list['systemic_examination'],
                    'local_examination'=>$advance_template_list['local_examination'],
                    'specific_findings'=>$advance_template_list['specific_findings'],
                    'surgery_preferred'=>$advance_template_list['surgery_preferred'],
                    'operation_notes'=>$advance_template_list['operation_notes'],
                    'course_in_hospital'=>$advance_template_list['course_in_hospital'],
                    'treatment_given'=>$advance_template_list['treatment_given'], 

                    'summery_type'=>$advance_template_list['summery_type'],
                    'chief_complaints'=>$advance_template_list['chief_complaints'],
                    'h_o_presenting'=>$advance_template_list['h_o_presenting'],
                    'on_examination'=>$advance_template_list['on_examination'],
                    'vitals_pulse'=>$advance_template_list['vitals_pulse'],
                    'vitals_chest'=>$advance_template_list['vitals_chest'],
                    'vitals_bp'=>$advance_template_list['vitals_bp'],
                    'vitals_cvs'=>$advance_template_list['vitals_cvs'],
                    'vitals_temp'=>$advance_template_list['vitals_temp'],
                    'vitals_p_a'=>$advance_template_list['vitals_p_a'],
                    'vitals_cns'=>$advance_template_list['vitals_cns'],

                    'vitals_r_r'=>$advance_template_list['vitals_r_r'],
                    'vitals_saturation'=>$advance_template_list['vitals_saturation'],
                    'vitals_pupils'=>$advance_template_list['vitals_pupils'],
                    'vitals_pallor'=>$advance_template_list['vitals_pallor'],
                    'vitals_icterus'=>$advance_template_list['vitals_icterus'],
                    'vitals_cyanosis'=>$advance_template_list['vitals_cyanosis'],
                    'vitals_clubbing'=>$advance_template_list['vitals_clubbing'],
                    'vitals_edema'=>$advance_template_list['vitals_edema'],
                    'vitals_lymphadenopathy'=>$advance_template_list['vitals_lymphadenopathy'],

                    'provisional_diagnosis'=>$advance_template_list['provisional_diagnosis'],
                    'final_diagnosis'=>$advance_template_list['final_diagnosis'],
                    'course_in_hospital'=>$advance_template_list['course_in_hospital'],
                    'investigations'=>$advance_template_list['investigations'],
                    'discharge_time_condition'=>$advance_template_list['discharge_time_condition'],
                    'discharge_advice'=>$advance_template_list['discharge_advice'],
                    'review_time_date'=>date('d-m-Y'),
                    'review_time'=>date('H:i:s'),
                    'death_date'=>date('d-m-Y'),
                    'death_time'=>date('H:i:s'),
                    'dischargedate'=>date('d-m-Y'),
                    'dischargetime'=>date('H:i:s'),
                    
                     'status'=>"1",
                     'remarks'=>$advance_template_list['remarks'],
                              ); 
            $data['prescription_presc_med_list']= $this->ipd_discharge_summary->get_advance_discharge_medicine($get['tid']);
            //echo '<pre>'; print_r($data['prescription_presc_list']);die;
          }
        }else
        {
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'ipd_id'=>$ipd_id, 
                                  'patient_id'=>$patient_id, 
                                  'summery_type'=>"",
                                  'chief_complaints'=>"",
                                  'h_o_presenting'=>"",
                                  'on_examination'=>"",
                                  'vitals_pulse'=>"",
                                  'vitals_chest'=>"",
                                  'vitals_bp'=>"",
                                  'vitals_cvs'=>"",
                                  'vitals_temp'=>"",
                                  'vitals_p_a'=>"",
                                  'vitals_cns'=>"",

                                  'vitals_r_r'=>'',
                                  'vitals_saturation'=>'',
                                  'vitals_pupils'=>'',
                                  'vitals_pallor'=>'',
                                  'vitals_icterus'=>'',
                                  'vitals_cyanosis'=>'',
                                  'vitals_clubbing'=>'',
                                  'vitals_edema'=>'',
                                  'vitals_lymphadenopathy'=>'',

                                  'provisional_diagnosis'=>"",
                                  'final_diagnosis'=>"",
                                  'course_in_hospital'=>"",
                                  'investigations'=>"",
                                  'discharge_time_condition'=>"",
                                  'discharge_advice'=>"",
                                  'review_time_date'=>date('d-m-Y'),
                                  'review_time'=>date('H:i:s'),
                                  'death_date'=>date('d-m-Y'),
                                  'death_time'=>date('H:i:s'),
                                   'status'=>"1",
                                   'remarks'=>get_setting_value("IPD_DISCHARGE_REMARKS"),
                                   'dischargedate'=>date('d-m-Y'),
                    'dischargetime'=>date('H:i:s'),
                              );    
         }
         
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save();
                
                $this->session->set_userdata('discharge_summary_id',$discharge_summary_id);
                $this->session->set_flashdata('success','Discharge summary_id updated successfully.');
                redirect(base_url('ipd_patient_advance_discharge_summary/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                
            }     
        }
      $this->load->model('general/general_model');
      $data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
      $data['simulation_list'] = $this->general_model->simulation_list();   
      $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      //echo "<pre>"; print_r($discharge_labels_setting_list); exit;
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
      $sortArray = array();
      foreach($arr as $value)
      { 
          foreach($value as $key=>$value)
          {
              if(!isset($sortArray[$key])){
                  $sortArray[$key] = array();
              }
              $sortArray[$key][] = $value;
          }
      }
     
      $orderby = "order_by";
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      //echo "<pre>"; print_r($data['discharge_labels_setting_list']); exit;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);
      $data['discharge_field_master_list'] = $this->ipd_discharge_summary->discharge_field_master_list(1,1);
      //print_r($data['discharge_field_master_list']); exit;

      $get_payment_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field();
      //print_r($get_payment_detail); exit; 
      $total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;


 
      $data['template_list'] = $this->ipd_discharge_summary->template_list();

      $ipd_test_data = $this->ipd_discharge_summary->get_ipd_pathology_test($ipd_id);

      $booking_id = $ipd_test_data['id'];
      
      $data['path_booking_id'] =$booking_id;

      ///

if(!empty($booking_id))
{
      $this->load->model('test/test_model','test');
      $get_by_id_data = $this->test->patient_booking_list($ipd_id); 
		//echo "<pre>"; print_r($get_by_id_data); die;

    $common_profile =array();
    if(!empty($get_by_id_data))
    {
      
      $common_profile_date_pro =array();
      $common_profile_bookIDS=array();
      $pi = 1;
      foreach($get_by_id_data as $value) 
      {
        $booked_profile_data = $this->ipd_discharge_summary->get_booking_profile_print_name($value->id); 
        //echo $this->db->last_query(); exit;
        //echo "<pre>"; print_r($booked_profile_data); exit;
          if(!empty($booked_profile_data))
          { 
              
                
                foreach($booked_profile_data as $booked_profl)
                {
                  if(!in_array($booked_profl->profile_name,$common_profile['profile_name']))
                  {
                      $common_profile['profile_name'][] = $booked_profl->profile_name; 
                      $common_profile['profile_id'][] = $booked_profl->profile_id;
                      $common_profile['commons_profile'][] = "Yes";
                      
                      $common_profile_date_pro[$pi]['profile_name'] = $booked_profl->profile_name; 
                      $common_profile_date_pro[$pi]['profile_id'] = $booked_profl->profile_id;
                      $common_profile_date_pro[$pi]['booking_id'] =$value->id;
                      $common_profile_bookIDS['booking_id'][] =$value->id;
                      
                     $pi++;  
                      
                  }
                  else
                  { 
                
                      $common_profile['profile_name'][] = $booked_profl->profile_name; 
                      $common_profile['profile_id'][] = $booked_profl->profile_id;
                      $common_profile['commons_profile'][] = "";
                      
                      $common_profile_date_pro[$pi]['profile_name'] = $booked_profl->profile_name; 
                      $common_profile_date_pro[$pi]['profile_id'] = $booked_profl->profile_id;
                      $common_profile_date_pro[$pi]['booking_id'] =$value->id;
                      $common_profile_bookIDS['booking_id'][] =$value->id;
                      
                  }
                  
                }
          }
        }
      }

//echo "<pre>"; print_r($common_profile); exit;
   /* $common_profile_name_list = array_unique($common_profile['profile_name']);
    $common_profile_id_list = array_unique($common_profile['profile_id']);*/
     
     $common_profile_name_list = $common_profile['profile_name'];
    $common_profile_id_list = $common_profile['profile_id'];
    
    $commons_profile_check = $common_profile['commons_profile'];
    
    
    // echo "<pre>"; print_r($common_profile_name_list); exit;
     
      if(!empty($get_by_id_data))
      {
        $common_profile_date =array();
        foreach($get_by_id_data as $value) 
        {
          if(!empty($value->booking_date) && $value->booking_date!='1970-01-01')
          {
            $date_key = strtotime($value->booking_date); 
              //if(!isset($common_profile_date[$date_key])){ 
                  $common_profile_date[$value->id] = $value->booking_date;
              //}
          }
              
          
        }
      }
     
      //echo "<pre>"; print_r($common_profile_bookIDS['booking_id']); exit;
      
      $common_booking_ids=array();
      if(!empty($common_profile_bookIDS['booking_id']))
      {
        $n=0;
        foreach ($common_profile_bookIDS['booking_id'] as $key => $value) 
        {
          if(!in_array($value, $common_booking_ids))
          {
            $common_booking_ids[$n] = $value; 
            $n++;  
          }
          
        }
       }
    //echo "<pre>"; print_r($common_booking_ids); exit;
      
      //echo "<pre>"; print_r($common_profile); exit;
      //profile test listing start
      $test_profiles='';
      if(!empty($common_profile))
      {
          //commons_profile
          $row=2;
        $test_report_data .='<table class="table table-bordered table-striped" id="test_name_table" style="margin-left:-150px;"><thead><tr><th colspan="2">Investigation</th>
                  <th align="left" style="padding:4px;" colspan="'.count($common_profile_date).'">Report</th></tr><tr>
                  <th align="left" style="padding:4px;width:100px;">S. No.</th>
                  <th align="left" style="padding:4px;">Investigations</th>';
                  foreach ($common_profile_date as $cmvalue) 
                  {
                      $test_report_data .='<th>'.date('d-m-Y',strtotime($cmvalue)).'</th>';  
                  }
                  $ty=0;
                  foreach ($common_profile_name_list as $key =>$value) 
                  {
                      
                      $progilr_id = $common_profile_id_list[$ty];
                      ///common condition
                      if(!empty($commons_profile_check[$ty]))
                      {
                      
                        $test_report_data .='<thead><tr><th colspan="2">'.$value.'</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  

                      if(!empty($common_profile_date_pro))
                      {
                        $k=0;
                        
                         //echo "<pre>";print_r($common_profile_date_pro); exit;
                         foreach ($common_profile_date_pro as $pro_value) 
                         {
                             
                            if($value==$pro_value['profile_name'])
                            {
                             
                                $test_BookingID = $common_profile_bookIDS['booking_id'][$ty];
                               
                                $test_profiles =  $this->ipd_discharge_summary->report_test_list($test_BookingID,'','',$progilr_id);
                                
                               
                               if(!empty($test_profiles))
                               {
                                $n=1;
                                foreach ($test_profiles as $value_p) 
                                {
                                      $test_report_data .='<tr>
                                        <td>'.$n.'</td>
                                        <td>'.$value_p->test_name.'</td>';
                                        $s=0;
                                        foreach ($common_profile_date as $b_key=>$valued) 
                                        {
                                          //$test_BookingID = $common_booking_ids[$s];
                                          
                                          $test_BookingID = $b_key;
                                          $get_result_on_date = $this->test->get_test_result_by_date($value_p->test_id,$value_p->profile_id,$valued,$test_BookingID);
                                          //echo "<pre>";print_r($get_result_on_date); 
                                          $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                          $s++;

                                        }
                                        
                                        $test_report_data .='</tr>';
                                        $n++;
                                }
                               }
                            }
                            $k++;       
                         }
                      }     
                      }
                      //end of profile name
                  
                      $ty++;
                  }
      }

    
    //Profile test listing end 
    
     //Individual test report listing 
    if(!empty($get_by_id_data))
	{
	    
	    
	  
	    
            $my_test=array();
            $ko=1;
			foreach($get_by_id_data as $value) 
			{
			    $datatest_list = $this->ipd_discharge_summary->report_test_list($value->id,'','0');
                //echo "<pre>"; print_r($datatest_list);
                if(!empty($datatest_list))
                {
        
                  //echo "<pre>";  print_r($get_by_id_data);
                  //print_r($booking_id)
        
                  foreach($datatest_list as $mytest_list) 
          		  {
          		     
          		      if(!empty($my_testp) && array_search($mytest_list->test_id, array_column($my_testp, 'test_id')) !== False) {
          		          //echo "INN";
          		          $my_testp[$ko]['test_name'] = $mytest_list->test_name;
                        $my_testp[$ko]['test_id'] = $mytest_list->test_id;
          		          $my_testp[$ko]['booking_id'] = $mytest_list->booking_id;
          		          $my_testp[$ko]['common_test'] = "Yes";
          		         
          		      }
          		      else
          		      {
                        $my_testp[$ko]['test_name'] = $mytest_list->test_name;
                        $my_testp[$ko]['test_id'] = $mytest_list->test_id;
                        $my_testp[$ko]['booking_id'] = $mytest_list->booking_id;
                        $my_testp[$ko]['common_test'] = "";
                        
                        
          		      }
          		      $ko++;
                        
          		  }
                }
			}
	}
	
	
	 //echo "<pre>"; print_r($common_booking_ids);  die; 
	 
	 if($my_testp)
	 {
	      $test_report_data .='<thead><tr><th colspan="2">Test</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>'; 
	     $po=1;
	     foreach($my_testp as $row)
	     {
	        //print_r($row); 
	            if(empty($row['common_test']))
	            {
	                    $test_report_data .='<tr>';
                            $test_report_data .='<td style="padding:4px;">'.$po.'</td>';
                            $test_report_data .='<td style="padding:4px;">'.$row['test_name'].'</td>';
                            
                            
                            $s=0;
                            
                            foreach ($common_profile_date as $key=>$valued) 
                            {
                               
                             
                              $get_result_on_date = $this->test->get_test_result_by_date($row['test_id'],'',$valued,$key);
                              $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                              $s++;
                
                            }
                            
                            
                            
                             $test_report_data .='</tr>';
                            $po++;
	            }
	     }
	 }
	 //End of individual test report listing    

    
    /*if(!empty($get_by_id_data))
		{
			$result_test_data = array();
		    $x=1;

            $test_report_data .='<thead><tr><th colspan="2">Test</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  
            
			foreach($get_by_id_data as $value) 
			{
				$datatest_list = $this->ipd_discharge_summary->report_test_list($value->id,'','0');
                echo "<pre>"; print_r($datatest_list); //exit;
				if(!empty($datatest_list))
                {
        
                  //echo "<pre>";  print_r($get_by_id_data);
                  //print_r($booking_id)
        
                  foreach($datatest_list as $mytest_list) 
          		  {
          				    $test_report_data .='<tr>';
                            $test_report_data .='<td style="padding:4px;">'.$x.'</td>';
                            $test_report_data .='<td style="padding:4px;">'.$mytest_list->test_name.'</td>';
                
                            $s=0;
                            foreach ($common_profile_date as $valued) 
                            {
                              $test_BookingID = $booked_test_list_data[$s];
                              $get_result_on_date = $this->test->get_test_result_by_date($mytest_list->test_id,'',$valued,$mytest_list->booking_id);
                              $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                              $s++;
                
                            }
                            $test_report_data .='</tr>';
                            $x++;		
          			}
				}	
		  }
    }*/
       $test_report_data .='</tbody></table>'; 
      
}

    
      $data['test_report_data'] = $test_report_data; //exit;

      ////
 $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();

      $this->load->view('ipd_patient_discharge_summary/add_advance',$data);       
    }

    function get_template_data($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->ipd_discharge_summary->template_data($template_id);
        echo $templatedata;
      }
    }


     // -> function to find gender according to selected ipd_discharge_summary
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_discharge_summary_id = $this->input->post('ipd_discharge_summary_id');
         $data='';
          if(!empty($ipd_discharge_summary_id)){
               $result = $this->general_model->find_gender($ipd_discharge_summary_id);
               if(!empty($result))
               {
                  $male = "";
                  $female = ""; 
                  $others=""; 
                  if($result['gender']==1)
                  {
                     $male = 'checked="checked"';
                  } 
                  else if($result['gender']==0)
                  {
                     $female = 'checked="checked"';
                  } 
                  else if($result['gender']==2){
                    $others = 'checked="checked"';
                  }
                  $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
                          <input type="radio" name="gender" '.$female.' value="0" /> Female &nbsp;
                          <input type="radio" name="gender" '.$others.' value="2" /> Others ';
               }
 
          }
          echo $data;
    }
    // -> end:
    public function edit($id="")
    {
        // prescription_presc_list
     unauthorise_permission('124','751');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_discharge_summary->get_by_id($id);  
        //print_r($result);die;
        $data['page_title'] = "Update IPD Discharge Summary";  
        $post = $this->input->post();
        $data['form_error'] = '';  
        $data['adv_template_list'] = $this->ipd_discharge_summary->advance_template_list();
        $get = $this->input->get();
        if(!empty($get['tid']))
        {
          $advance_template_list = $this->ipd_discharge_summary->advance_template_list($get['tid']);
          if(!empty($advance_template_list))
          {
            $advance_template_list = $advance_template_list[0];
            $data['form_data'] = array(
                    //'data_id'=>'', 
                    //'ipd_id'=>$ipd_id, 
                    //'patient_id'=>$patient_id, 
                    'data_id'=>$result['id'],
                      'patient_id'=>$result['patient_id'],
                      'ipd_id'=>$result['ipd_id'],
                    'diagnosis'=>$advance_template_list['diagnosis'],
                    'past_history'=>$advance_template_list['past_history'],
                    'family_history'=>$advance_template_list['family_history'],
                    'birth_history'=>$advance_template_list['birth_history'],
                    'general_history'=>$advance_template_list['general_history'],
                    'menstrual_history'=>$advance_template_list['menstrual_history'],
                    'obstetric_history'=>$advance_template_list['obstetric_history'],
                    'systemic_examination'=>$advance_template_list['systemic_examination'],
                    'local_examination'=>$advance_template_list['local_examination'],
                    'specific_findings'=>$advance_template_list['specific_findings'],
                    'surgery_preferred'=>$advance_template_list['surgery_preferred'],
                    'operation_notes'=>$advance_template_list['operation_notes'],
                    'course_in_hospital'=>$advance_template_list['course_in_hospital'],
                    'treatment_given'=>$advance_template_list['treatment_given'], 

                    'summery_type'=>$advance_template_list['summery_type'],
                    'chief_complaints'=>$advance_template_list['chief_complaints'],
                    'h_o_presenting'=>$advance_template_list['h_o_presenting'],
                    'on_examination'=>$advance_template_list['on_examination'],
                    'vitals_pulse'=>$advance_template_list['vitals_pulse'],
                    'vitals_chest'=>$advance_template_list['vitals_chest'],
                    'vitals_bp'=>$advance_template_list['vitals_bp'],
                    'vitals_cvs'=>$advance_template_list['vitals_cvs'],
                    'vitals_temp'=>$advance_template_list['vitals_temp'],
                    'vitals_p_a'=>$advance_template_list['vitals_p_a'],
                    'vitals_cns'=>$advance_template_list['vitals_cns'],

                    'vitals_r_r'=>$advance_template_list['vitals_r_r'],
                    'vitals_saturation'=>$advance_template_list['vitals_saturation'],
                    'vitals_pupils'=>$advance_template_list['vitals_pupils'],
                    'vitals_pallor'=>$advance_template_list['vitals_pallor'],
                    'vitals_icterus'=>$advance_template_list['vitals_icterus'],
                    'vitals_cyanosis'=>$advance_template_list['vitals_cyanosis'],
                    'vitals_clubbing'=>$advance_template_list['vitals_clubbing'],
                    'vitals_edema'=>$advance_template_list['vitals_edema'],
                    'vitals_lymphadenopathy'=>$advance_template_list['vitals_lymphadenopathy'],

                    'provisional_diagnosis'=>$advance_template_list['provisional_diagnosis'],
                    'final_diagnosis'=>$advance_template_list['final_diagnosis'],
                    'course_in_hospital'=>$advance_template_list['course_in_hospital'],
                    'investigations'=>$advance_template_list['investigations'],
                    'discharge_time_condition'=>$advance_template_list['discharge_time_condition'],
                    'discharge_advice'=>$advance_template_list['discharge_advice'],
                    'review_time_date'=>date('d-m-Y'),
                    'review_time'=>date('H:i:s'),
                    
                    'dischargedate'=>date('d-m-Y'),
                    'dischargetime'=>date('H:i:s'),
                     'status'=>"1",
                     'remarks'=>$advance_template_list['remarks'],
                              ); 
            $data['prescription_presc_med_list']= $this->ipd_discharge_summary->get_advance_discharge_medicine($get['tid']);
            //echo '<pre>'; print_r($data['prescription_presc_med_list']);die;
          }
        }else
        {
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'patient_id'=>$result['patient_id'],
                                  'ipd_id'=>$result['ipd_id'],
                                  'summery_type'=>$result['summery_type'],
                                  'chief_complaints'=>$result['chief_complaints'],
                                  'h_o_presenting'=>$result['h_o_presenting'],
                                  'on_examination'=>$result['on_examination'],
                                  'vitals_pulse'=>$result['vitals_pulse'],
                                  'vitals_chest'=>$result['vitals_chest'],
                                  'vitals_bp'=>$result['vitals_bp'],
                                  'vitals_cvs'=>$result['vitals_cvs'],
                                  'vitals_temp'=>$result['vitals_temp'],
                                  'vitals_p_a'=>$result['vitals_p_a'],
                                  'vitals_cns'=>$result['vitals_cns'],
                                  'provisional_diagnosis'=>$result['provisional_diagnosis'],
                                  'final_diagnosis'=>$result['final_diagnosis'],
                                  'course_in_hospital'=>$result['course_in_hospital'],
                                  'investigations'=>$result['investigations'],
                                  'discharge_time_condition'=>$result['discharge_time_condition'],
                                  'discharge_advice'=>$result['discharge_advice'],
                                  
                                  'treatment_given'=>$result['treatment_given'],
                                  'operation_notes'=>$result['operation_notes'],
                                  'surgery_preferred'=>$result['surgery_preferred'],
                                  'past_history'=>$result['past_history'],
                                  'menstrual_history'=>$result['menstrual_history'],
                                  'obstetric_history'=>$result['obstetric_history'],

                                  'diagnosis'=>$result['diagnosis'],
                                  'vitals_r_r'=>$result['vitals_r_r'],
                                  'vitals_saturation'=>$result['vitals_saturation'],
                                  'vitals_pupils'=>$result['vitals_pupils'],
                                  'vitals_pallor'=>$result['vitals_pallor'],
                                  'vitals_icterus'=>$result['vitals_icterus'],
                                  'vitals_cyanosis'=>$result['vitals_cyanosis'],
                                  'vitals_clubbing'=>$result['vitals_clubbing'],
                                  'vitals_edema'=>$result['vitals_edema'],
                                  'vitals_lymphadenopathy'=>$result['vitals_lymphadenopathy'],
                                  'family_history'=>$result['family_history'],
                                  'birth_history'=>$result['birth_history'],
                                  'general_history'=>$result['general_history'],
                                  'systemic_examination'=>$result['systemic_examination'],
                                  'local_examination'=>$result['local_examination'],
                                  'specific_findings'=>$result['specific_findings'],

                                  'review_time_date'=>date('d-m-Y',strtotime($result['review_time_date'])), 
                                  'review_time'=>$result['review_time'], 
                                  
                                   'dischargedate'=>date('d-m-Y',strtotime($result['dischargedate'])), 
                                  'dischargetime'=>$result['dischargetime'], 
                                  

                                  
                                  'death_date'=>date('d-m-Y',strtotime($result['death_date'])),
                                  'death_time'=>$result['death_time'],
                                  
                                  'status'=>$result['status'],
                                  'remarks'=>$result['remarks'],
                                  
                                  );  
        $data['prescription_presc_med_list']= $this->ipd_discharge_summary->get_discharge_medicine($id,$result['ipd_id']);
        }
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save();
                //echo $discharge_summary_id; exit;
                $this->session->set_userdata('discharge_summary_id',$discharge_summary_id);
                $this->session->set_flashdata('success','Discharge summary updated successfully.');
                redirect(base_url('ipd_patient_advance_discharge_summary/?status=print'));
                //echo 1;
                //return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                //print_r(validation_errors()); die;
            }     
        }
        //print_r($result); die;
        $this->load->model('general/general_model');
        $data['patient_details']= $this->general_model->get_patient_according_to_ipd($result['ipd_id'],$result['patient_id']);
        //echo '<pre>'; print_r($data['patient_details']);die;
        $data['simulation_list'] = $this->general_model->simulation_list();   
        $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
       
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
      $sortArray = array();
      foreach($arr as $value){ //print_r($value);
          foreach($value as $key=>$value)
          {
              if(!isset($sortArray[$key])){
                  $sortArray[$key] = array();
              }
              $sortArray[$key][] = $value;
          }
      }
      $orderby = "order_by";
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);        
      $data['template_list'] = $this->ipd_discharge_summary->template_list();

      $data['discharge_field_master_list'] = $this->ipd_discharge_summary->discharge_field_master_list(1,1);
      /////
      $get_payment_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field($id);
      //print_r($get_payment_detail); exit;
      $total_values=array();
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
          $total_values[]= $get_payment_detail[$i]->field_value.'__'.$get_payment_detail[$i]->discharge_lable.'__'.$get_payment_detail[$i]->field_id.'__'.$get_payment_detail[$i]->type;
      }
      $data['field_name'] = $total_values;
      
      $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
       
       /********** Medicine of discharge summary ************/
       $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
       $data['prescription_test_list']=$result['id'];
       
       /********** Medicine of discharge summary ************/

       /********** Investigation  of discharge summary ************/
       $data['test_list']=$result['id'];
       $data['discharge_test_list']= $this->ipd_discharge_summary->get_discharge_test($id);
       
       
       /********** Medicine of discharge summary ************/
       
      //print_r($total_values); exit;
      /////

        //exit;
      $test_report_data ='';
      $ipd_test_data = $this->ipd_discharge_summary->get_ipd_pathology_test($result['ipd_id']);

       $booking_id = $ipd_test_data['id'];
      
      $data['path_booking_id'] =$booking_id;
      //print_r($ipd_test_data);die;
      //test booking list
      if(!empty($booking_id))
      {
          
          $this->load->model('test/test_model','test');
              $get_by_id_data = $this->test->patient_booking_list($result['ipd_id']); 
        		//echo "<pre>"; print_r($get_by_id_data); die;
        
            $common_profile =array();
            if(!empty($get_by_id_data))
            {
              
              $common_profile_date_pro =array();
              $common_profile_bookIDS=array();
              $pi = 1;
              foreach($get_by_id_data as $value) 
              {
                $booked_profile_data = $this->ipd_discharge_summary->get_booking_profile_print_name($value->id); 
                //echo $this->db->last_query(); exit;
                //echo "<pre>"; print_r($booked_profile_data); exit;
                  if(!empty($booked_profile_data))
                  { 
                      
                        
                        foreach($booked_profile_data as $booked_profl)
                        {
                          if(!in_array($booked_profl->profile_name,$common_profile['profile_name']))
                          {
                              $common_profile['profile_name'][] = $booked_profl->profile_name; 
                              $common_profile['profile_id'][] = $booked_profl->profile_id;
                              $common_profile['commons_profile'][] = "Yes";
                              
                              $common_profile_date_pro[$pi]['profile_name'] = $booked_profl->profile_name; 
                              $common_profile_date_pro[$pi]['profile_id'] = $booked_profl->profile_id;
                              $common_profile_date_pro[$pi]['booking_id'] =$value->id;
                              $common_profile_bookIDS['booking_id'][] =$value->id;
                              
                             $pi++;  
                              
                          }
                          else
                          { 
                        
                              $common_profile['profile_name'][] = $booked_profl->profile_name; 
                              $common_profile['profile_id'][] = $booked_profl->profile_id;
                              $common_profile['commons_profile'][] = "";
                              
                              $common_profile_date_pro[$pi]['profile_name'] = $booked_profl->profile_name; 
                              $common_profile_date_pro[$pi]['profile_id'] = $booked_profl->profile_id;
                              $common_profile_date_pro[$pi]['booking_id'] =$value->id;
                              $common_profile_bookIDS['booking_id'][] =$value->id;
                              
                          }
                          
                        }
                  }
                }
              }
        
        //echo "<pre>"; print_r($common_profile); exit;
           /* $common_profile_name_list = array_unique($common_profile['profile_name']);
            $common_profile_id_list = array_unique($common_profile['profile_id']);*/
             
             $common_profile_name_list = $common_profile['profile_name'];
            $common_profile_id_list = $common_profile['profile_id'];
            
            $commons_profile_check = $common_profile['commons_profile'];
            
            
            // echo "<pre>"; print_r($common_profile_name_list); exit;
             
              if(!empty($get_by_id_data))
              {
                $common_profile_date =array();
                foreach($get_by_id_data as $value) 
                {
                  if(!empty($value->booking_date) && $value->booking_date!='1970-01-01')
                  {
                    $date_key = strtotime($value->booking_date); 
                      //if(!isset($common_profile_date[$date_key])){ 
                          $common_profile_date[$value->id] = $value->booking_date;
                      //}
                  }
                      
                  
                }
              }
             
              //echo "<pre>"; print_r($common_profile_bookIDS['booking_id']); exit;
              
              $common_booking_ids=array();
              if(!empty($common_profile_bookIDS['booking_id']))
              {
                $n=0;
                foreach ($common_profile_bookIDS['booking_id'] as $key => $value) 
                {
                  if(!in_array($value, $common_booking_ids))
                  {
                    $common_booking_ids[$n] = $value; 
                    $n++;  
                  }
                  
                }
               }
            //echo "<pre>"; print_r($common_booking_ids); exit;
              
              //echo "<pre>"; print_r($common_profile); exit;
              //profile test listing start
              $test_profiles='';
              if(!empty($common_profile))
              {
                  //commons_profile
                  $row=2;
                $test_report_data .='<table class="table table-bordered table-striped" id="test_name_table" style="margin-left:-150px;"><thead><tr><th colspan="2">Investigation</th>
                          <th align="left" style="padding:4px;" colspan="'.count($common_profile_date).'">Report</th></tr><tr>
                          <th align="left" style="padding:4px;width:100px;">S. No.</th>
                          <th align="left" style="padding:4px;">Investigations</th>';
                          foreach ($common_profile_date as $cmvalue) 
                          {
                              $test_report_data .='<th>'.date('d-m-Y',strtotime($cmvalue)).'</th>';  
                          }
                          $ty=0;
                          foreach ($common_profile_name_list as $key =>$value) 
                          {
                              
                              $progilr_id = $common_profile_id_list[$ty];
                              ///common condition
                              if(!empty($commons_profile_check[$ty]))
                              {
                              
                                $test_report_data .='<thead><tr><th colspan="2">'.$value.'</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  
        
                              if(!empty($common_profile_date_pro))
                              {
                                $k=0;
                                
                                 //echo "<pre>";print_r($common_profile_date_pro); exit;
                                 foreach ($common_profile_date_pro as $pro_value) 
                                 {
                                     
                                    if($value==$pro_value['profile_name'])
                                    {
                                     
                                        $test_BookingID = $common_profile_bookIDS['booking_id'][$ty];
                                       
                                        $test_profiles =  $this->ipd_discharge_summary->report_test_list($test_BookingID,'','',$progilr_id);
                                        
                                       
                                       if(!empty($test_profiles))
                                       {
                                        $n=1;
                                        foreach ($test_profiles as $value_p) 
                                        {
                                              $test_report_data .='<tr>
                                                <td>'.$n.'</td>
                                                <td>'.$value_p->test_name.'</td>';
                                                $s=0;
                                                foreach ($common_profile_date as $b_key=>$valued) 
                                                {
                                                  //$test_BookingID = $common_booking_ids[$s];
                                                  
                                                  $test_BookingID = $b_key;
                                                  $get_result_on_date = $this->test->get_test_result_by_date($value_p->test_id,$value_p->profile_id,$valued,$test_BookingID);
                                                  //echo "<pre>";print_r($get_result_on_date); 
                                                  $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                                  $s++;
        
                                                }
                                                
                                                $test_report_data .='</tr>';
                                                $n++;
                                        }
                                       }
                                    }
                                    $k++;       
                                 }
                              }     
                              }
                              //end of profile name
                          
                              $ty++;
                          }
              }
        
            
            //Profile test listing end 
            
             //Individual test report listing 
            if(!empty($get_by_id_data))
        	{
        	    
        	    
        	  
        	    
                    $my_test=array();
                    $ko=1;
        			foreach($get_by_id_data as $value) 
        			{
        			    $datatest_list = $this->ipd_discharge_summary->report_test_list($value->id,'','0');
                        //echo "<pre>"; print_r($datatest_list);
                        if(!empty($datatest_list))
                        {
                
                          //echo "<pre>";  print_r($get_by_id_data);
                          //print_r($booking_id)
                
                          foreach($datatest_list as $mytest_list) 
                  		  {
                  		     
                  		      if(!empty($my_testp) && array_search($mytest_list->test_id, array_column($my_testp, 'test_id')) !== False) {
                  		          //echo "INN";
                  		          $my_testp[$ko]['test_name'] = $mytest_list->test_name;
                                $my_testp[$ko]['test_id'] = $mytest_list->test_id;
                  		          $my_testp[$ko]['booking_id'] = $mytest_list->booking_id;
                  		          $my_testp[$ko]['common_test'] = "Yes";
                  		         
                  		      }
                  		      else
                  		      {
                                $my_testp[$ko]['test_name'] = $mytest_list->test_name;
                                $my_testp[$ko]['test_id'] = $mytest_list->test_id;
                                $my_testp[$ko]['booking_id'] = $mytest_list->booking_id;
                                $my_testp[$ko]['common_test'] = "";
                                
                                
                  		      }
                  		      $ko++;
                                
                  		  }
                        }
        			}
        	}
        	
        	
        	 //echo "<pre>"; print_r($common_booking_ids);  die; 
        	 
        	 if($my_testp)
        	 {
        	      $test_report_data .='<thead><tr><th colspan="2">Test</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>'; 
        	     $po=1;
        	     foreach($my_testp as $row)
        	     {
        	        //print_r($row); 
        	            if(empty($row['common_test']))
        	            {
        	                    $test_report_data .='<tr>';
                                    $test_report_data .='<td style="padding:4px;">'.$po.'</td>';
                                    $test_report_data .='<td style="padding:4px;">'.$row['test_name'].'</td>';
                                    
                                    
                                    $s=0;
                                    
                                    foreach ($common_profile_date as $key=>$valued) 
                                    {
                                       
                                     
                                      $get_result_on_date = $this->test->get_test_result_by_date($row['test_id'],'',$valued,$key);
                                      $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                      $s++;
                        
                                    }
                                    
                                    
                                    
                                     $test_report_data .='</tr>';
                                    $po++;
        	            }
        	     }
        	 }
        	 //End of individual test report listing    
        
               $test_report_data .='</tbody></table>'; 
              
        
              
              /*
              $this->load->model('test/test_model','test');
              $get_by_id_data = $this->test->patient_booking_list($result['ipd_id']); 
            //echo "<pre>"; print_r($get_by_id_data); die;
        
            $common_profile =array();
            if(!empty($get_by_id_data))
            {
              
              $common_profile_date_pro =array();
              $common_profile_bookIDS=array();
              foreach($get_by_id_data as $value) 
              {
                $booked_profile_data = $this->ipd_discharge_summary->get_booking_profile_print_name($value->id); 
                
                //echo $this->db->last_query(); exit;
                //echo "<pre>"; print_r($booked_profile_data); exit;
                  if(!empty($booked_profile_data))
                  { 
                      
                        $pi = 1;
                        foreach($booked_profile_data as $booked_profl)
                        {
                          if(!in_array($booked_profl->profile_name,$common_profile))
                          {
                              $common_profile['profile_name'][] = $booked_profl->profile_name; 
                              $common_profile['profile_id'][] = $booked_profl->profile_id;
                              
                              $common_profile_date_pro[$pi]['profile_name'] = $booked_profl->profile_name; 
                              $common_profile_date_pro[$pi]['profile_id'] = $booked_profl->profile_id;
                              $common_profile_date_pro[$pi]['booking_id'] =$value->id;
                              $common_profile_bookIDS['booking_id'][] =$value->id;
                              
                          }
                          $pi++; 
                        }
                  }
                }
              }
        
        //echo "<pre>"; print_r($common_profile); exit;
            $common_profile_name_list = array_unique($common_profile['profile_name']);
            $common_profile_id_list = array_unique($common_profile['profile_id']);
             //$common_profile_name_list = $this->super_unique($common_profile,'profile_name'); 
             //echo "<pre>"; print_r($common_profile_id_list); exit;
              if(!empty($get_by_id_data))
              {
                $common_profile_date =array();
                foreach($get_by_id_data as $value) 
                {
                  if(!empty($value->booking_date) && $value->booking_date!='1970-01-01')
                  {
                    $date_key = strtotime($value->booking_date); 
                      if(!isset($common_profile_date[$date_key])){ 
                          $common_profile_date[$date_key] = $value->booking_date;
                      }
                  }
                      
                  
                }
              }
              //echo "<pre>"; print_r($common_profile_data); exit;
            //6 feb 2021
            
              //echo "<pre>"; print_r($common_profile_bookIDS['booking_id']); exit;
              
              $common_booking_ids=array();
              if(!empty($common_profile_bookIDS['booking_id']))
              {
                $n=0;
                foreach ($common_profile_bookIDS['booking_id'] as $key => $value) 
                {
                  if(!in_array($value, $common_booking_ids))
                  {
                    $common_booking_ids[$n] = $value; 
                    $n++;  
                  }
                  
                }
               }
            //echo "<pre>"; print_r($common_booking_ids); exit;
              
              //echo "<pre>"; print_r($common_profile_bookIDS); exit;
              
              $test_profiles='';
              if(!empty($common_profile))
              {
                  $row=2;
                $test_report_data .='<table class="table table-bordered table-striped" id="test_name_table" style="margin-left:-150px;"><thead><tr><th colspan="2">Investigation</th>
                          <th align="left" style="padding:4px;" colspan="'.count($common_profile_date).'">Report</th></tr><tr>
                          <th align="left" style="padding:4px;width:100px;">S. No.</th>
                          <th align="left" style="padding:4px;">Investigations</th>';
                          foreach ($common_profile_date as $value) 
                          {
                              $test_report_data .='<th>'.date('d-m-Y',strtotime($value)).'</th>';  
                          }
                          
                          foreach ($common_profile_name_list as $key =>$value) 
                          {
                            $progilr_id = $common_profile_id_list[$key];
                            $test_report_data .='<thead><tr><th colspan="2">'.$value.'</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  
        
                            
                              //
                              //echo $this->db->last_query(); die;
                              //$common_profile_date_pro = [];
                              if(!empty($common_profile_date_pro))
                              {
                                $k=0;
                                
                                 //echo "<pre>";print_r($common_profile_date_pro); exit;
                                 foreach ($common_profile_date_pro as $pro_value) 
                                 {
                                    if($value==$pro_value['profile_name'])
                                    {
                                        $test_BookingID = $common_profile_bookIDS['booking_id'][$k];
                                        $test_profiles =  $this->ipd_discharge_summary->report_test_list($test_BookingID,'','',$progilr_id);
                                        
                                       if(!empty($test_profiles))
                                       {
                                        $n=1;
                                        foreach ($test_profiles as $value_p) 
                                        {
                                              $test_report_data .='<tr>
                                                <td>'.$n.'</td>
                                                <td>'.$value_p->test_name.'</td>';
                                                $s=0;
                                                foreach ($common_profile_date as $valued) 
                                                {
                                                  $test_BookingID = $common_booking_ids[$s];
                                                  $get_result_on_date = $this->test->get_test_result_by_date($value_p->test_id,$value_p->profile_id,$valued,$test_BookingID);
                                                  //echo "<pre>";print_r($get_result_on_date); 
                                                  $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                                  $s++;
        
                                                }
                                                
                                                $test_report_data .='</tr>';
                                                $n++;
                                        }
                                       }
                                    }
                                    $k++;       
                                 }
                              }     
                          }
              }
        
            
            //test
            if(!empty($get_by_id_data))
            {
              $result_test_data = array();
                $x=1;
        
                    $test_report_data .='<thead><tr><th colspan="2">Test</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  
        
              foreach($get_by_id_data as $value) 
              {
                $datatest_list = $this->ipd_discharge_summary->report_test_list($value->id,'','0');
                
                if(!empty($datatest_list))
                        {
                
                         
                
                          foreach($datatest_list as $mytest_list) 
                        {
                              $test_report_data .='<tr>';
                                    $test_report_data .='<td style="padding:4px;">'.$x.'</td>';
                                    $test_report_data .='<td style="padding:4px;">'.$mytest_list->test_name.'</td>';
                        
                                    $s=0;
                                    foreach ($common_profile_date as $valued) 
                                    {
                                      $test_BookingID = $booked_test_list_data[$s];
                                      $get_result_on_date = $this->test->get_test_result_by_date($mytest_list->test_id,'',$valued,$mytest_list->booking_id);
                                      $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                      $s++;
                        
                                    }
                                    $test_report_data .='</tr>';
                                    $x++;   
                        }
                } 
              }
            }
               $test_report_data .='</tbody></table>'; 
              
        */ 
          
          
          
          
      }
       $data['test_report_data'] = $test_report_data; 
      //end test booking list
      $this->load->view('ipd_patient_discharge_summary/add_advance',$data);       
      }
    }
    private function _validatemedicine()
    {
      $post = $this->input->post();
       $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('patient_id', 'patient_id', 'trim|required');

        $data['form_data'] = array(
                                        
                                       ); 
            return $data['form_data'];
    }
    private function _validate()
    {
        $post = $this->input->post();

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('patient_name', 'patient_name', 'trim|required');
        

        $total_values=array();
        if(isset($post['field_name'])) 
        {
          $count_field_names= count($post['field_name']);  
          $get_payment_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field();
          //print_r($get_payment_detail); exit;
          $total_values='';
          for($i=0;$i<count($get_payment_detail);$i++) 
          {
            $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->discharge_lable.'_'.$get_payment_detail[$i]->field_id.'_'.$get_payment_detail[$i]->type;
          }
          

        }
        /*if(isset($post['field_name'])) 
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }*/


        if ($this->form_validation->run() == FALSE) 
        {  
            $summery_type='';
            if(!empty($post['summery_type']))
            {
                $summery_type = $post['summery_type'];
            }
            $chief_complaints='';
            if(!empty($post['chief_complaints']))
            {
                $chief_complaints = $post['chief_complaints'];
            }
            $h_o_presenting='';
            if(!empty($post['h_o_presenting']))
            {
                $h_o_presenting = $post['h_o_presenting'];
            }
            $on_examination='';
            if(!empty($post['on_examination']))
            {
                $on_examination = $post['on_examination'];
            }
            $vitals_pulse='';
            if(!empty($post['vitals_pulse']))
            {
                $vitals_pulse = $post['vitals_pulse'];
            }
            $vitals_chest='';
            if(!empty($post['vitals_chest']))
            {
                $vitals_chest = $post['vitals_chest'];
            }
            $vitals_bp='';
            if(!empty($post['vitals_bp']))
            {
                $vitals_bp = $post['vitals_bp'];
            }
            $vitals_cvs='';
            if(!empty($post['vitals_cvs']))
            {
                $vitals_cvs = $post['vitals_cvs'];
            }
            $vitals_temp='';
            if(!empty($post['vitals_temp']))
            {
                $vitals_temp = $post['vitals_temp'];
            }

            $vitals_p_a='';
            if(!empty($post['vitals_p_a']))
            {
                $vitals_p_a = $post['vitals_p_a'];
            }

            $vitals_cns='';
            if(!empty($post['vitals_cns']))
            {
                $vitals_cns = $post['vitals_cns'];
            }

            $provisional_diagnosis='';
            if(!empty($post['provisional_diagnosis']))
            {
                $provisional_diagnosis = $post['provisional_diagnosis'];
            }

            $final_diagnosis='';
            if(!empty($post['final_diagnosis']))
            {
                $final_diagnosis = $post['final_diagnosis'];
            }

            $course_in_hospital='';
            if(!empty($post['course_in_hospital']))
            {
                $course_in_hospital = $post['course_in_hospital'];
            }

            $investigations='';
            if(!empty($post['investigations']))
            {
                $investigations = $post['investigations'];
            }
            $discharge_time_condition='';
            if(!empty($post['discharge_time_condition']))
            {
                $discharge_time_condition = $post['discharge_time_condition'];
            }

            $discharge_advice='';
            if(!empty($post['discharge_advice']))
            {
                $discharge_advice = $post['discharge_advice'];
            }

            $review_time_date='';
            if(!empty($post['review_time_date']))
            {
                $review_time_date = $post['review_time_date'];
            }
            
            $death_date='';
            if(!empty($post['death_date']))
            {
                $death_date = $post['death_date'];
            }

            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'],
                                        'ipd_id'=>$post['ipd_id'],
                                        'summery_type'=>$summery_type,
                                        'chief_complaints'=>$chief_complaints,
                                        'h_o_presenting'=>$h_o_presenting,
                                        'on_examination'=>$on_examination,
                                        'vitals_pulse'=>$vitals_pulse,
                                        'vitals_chest'=>$vitals_chest,
                                        'vitals_bp'=>$vitals_bp,
                                        'vitals_cvs'=>$vitals_cvs,
                                        'vitals_temp'=>$vitals_temp,
                                        'vitals_p_a'=>$vitals_p_a,
                                        'vitals_cns'=>$vitals_cns,
                                        'provisional_diagnosis'=>$provisional_diagnosis,
                                        'final_diagnosis'=>$final_diagnosis,
                                        'course_in_hospital'=>$course_in_hospital,
                                        'investigations'=>$investigations,
                                        'discharge_time_condition'=>$discharge_time_condition,
                                        'discharge_advice'=>$discharge_advice,
                                        'review_time_date'=>$review_time_date,
                                        'review_time'=>$post['review_time'], 
                                        
                                        'death_date'=>$death_date,
                                        'death_time'=>$post['death_time'], 
                                        
                                        'status'=>$post['status'],
                                        "field_name"=>$total_values,
                                        'remarks'=>$post['remarks'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       // unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_discharge_summary->delete($id);
           $response = "IPD Discharge Summary successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('124','752');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary->deleteall($post['row_id']);
            $response = "IPD Discharge Summarys successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_discharge_summary->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_discharge_summary']." detail";
        $this->load->view('ipd_patient_discharge_summary/view_advance',$data);     
      }
    }  


   

    public function print_discharge_summary($summary_id="",$branch_id='',$type='')
    {
         
       //IPD_DISCHARGE_INVESTIGATION_TEST
       $discharge_summary_id= $this->session->userdata('discharge_summary_id');
        if(!empty($discharge_summary_id))
        {
            $discharge_summary_id = $discharge_summary_id;
        }
        else
        {
            $discharge_summary_id =$summary_id;
        }
        
        $data['page_title'] = "Print Discharge Summary";
        $summary_info = $this->ipd_discharge_summary->get_detail_by_summary_id($discharge_summary_id);
        //echo "<pre>";print_r($summary_info); exit;
        $ipd_id = $summary_info['discharge_list'][0]->ipd_id;
        $template_format = $this->ipd_discharge_summary->template_format(array('setting_name'=>'DISCHARGE_SUMMARY_PRINT_SETTING'),$branch_id,$type);
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $summary_info;

      $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
      $sortArray = array();
      foreach($arr as $value){ //print_r($value);
          foreach($value as $key=>$value)
          {
              if(!isset($sortArray[$key])){
                  $sortArray[$key] = array();
              }
              $sortArray[$key][] = $value;
          }
      }
      $orderby = "order_by"; //discharge_labels_setting_list
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);

        $signature_image = get_doctor_signature($data['all_detail']['discharge_list'][0]->attend_doctor_id);
        
         $signature_reprt_data ='';
         if(!empty($signature_image))
         {
         
           $signature_reprt_data .='<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
          <table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
            <tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">Signature</b></td>
            </tr>';
            
            if(!empty($signature_image->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->sign_img))
            {
            
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->sign_img.'"></div></td>
            </tr>';
            
             }
             
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
            </tr>
            
          </table></div></div>';

         }
        $data['signature'] = $signature_reprt_data;
        $discharge_master_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field_print($discharge_summary_id);
        //print_r($get_payment_detail); exit;
        $total_values=array();
        for($i=0;$i<count($discharge_master_detail);$i++) 
        {
            $total_values[]= $discharge_master_detail[$i]->field_value.'__'.$discharge_master_detail[$i]->discharge_lable.'__'.$discharge_master_detail[$i]->field_id.'__'.$discharge_master_detail[$i]->type.'__'.$discharge_master_detail[$i]->discharge_short_code;
        }
        $data['field_name'] = $total_values;
        
        
        
        
        
        
        
        /************ Medicine Administered*************/


        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
       //echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
        if(!empty($summary_info['medicine_administered_detail'][0]->medicine_name) && $summary_info['medicine_administered_detail'][0]->medicine_name!=""){
          $medicine_admib_data='';
            $medicine_admib_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="10" style="padding:4px;" valign="top">MEDICATION ADMINISTERED:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_admib_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_admib_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_admib_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_administered_detail'] as $prescription_presc)
                     { 
                         $medicine_admib_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_admib_data .='</tr></tbody>
                  </table>';

                  $data['medicine_administered_list']=$medicine_admib_data;
        }
        else{
          $data['medicine_administered_list']='';
        }
 // print_r($data['medicine_administered_list']);die;
      
        /************ Medicine Administered *************/



        /************ Medicine *************/


        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
       //echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
       if(!empty($summary_info['medicine_list'])){
        if(!empty($prescription_medicine_tab_setting)){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="15" style="padding:4px;" valign="top">Medicine Prescribed:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_list'] as $prescription_presc)
                     { 
                         $medicine_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_data .='</tr></tbody>
                  </table>';

                  $data['medicine_list']=$medicine_data;
        }
       }
       
        else{
          $data['medicine_list']='';
        }
  
      
          /************ Medicine  patient_id *************/
          /************ Investigation *************/
         $discharge_setting = get_setting_value('IPD_DISCHARGE_INVESTIGATION_TEST'); //die;
          
          if($discharge_setting=='1')
          {

            $ipd_test_data = $this->ipd_discharge_summary->get_ipd_pathology_test($ipd_id);
            $booking_id = $ipd_test_data['id'];

              ///

              if(!empty($booking_id))
              {
                  
          
      
      $this->load->model('test/test_model','test');
      $get_by_id_data = $this->test->patient_booking_list($ipd_id); 
		//echo "<pre>"; print_r($get_by_id_data); die;

    
    if(!empty($get_by_id_data))
    {
      $common_profile =array();
      
      foreach($get_by_id_data as $value) 
      {
        $booked_profile_data = $this->ipd_discharge_summary->get_booking_profile_print_name($value->id); 
        //echo "<pre>"; print_r($booked_profile_data); exit;
          if(!empty($booked_profile_data))
          { 
              
                foreach($booked_profile_data as $booked_profl)
                {
                  if(!in_array($booked_profl->profile_name,$common_profile))
                  {
                      $common_profile['profile_name'][] = $booked_profl->profile_name; 
                      $common_profile['profile_id'][] = $booked_profl->profile_id;
                  }
                   
                }
          }
        }
      }

//echo "<pre>"; print_r($common_profile); exit;
    $common_profile_name_list = array_unique($common_profile['profile_name']);
    $common_profile_id_list = array_unique($common_profile['profile_id']);
     //$common_profile_name_list = $this->super_unique($common_profile,'profile_name'); 
     //echo "<pre>"; print_r($common_profile_id_list); exit;
      if(!empty($get_by_id_data))
      {
        $common_profile_date =array();
        foreach($get_by_id_data as $value) 
        {
          if(!empty($value->booking_date) && $value->booking_date!='1970-01-01')
          {
            $date_key = strtotime($value->booking_date); 
              if(!isset($common_profile_date[$date_key])){ 
                  $common_profile_date[$date_key] = $value->booking_date;
              }
          }
              
          
        }
      }
      //echo "<pre>"; print_r($common_profile_data); exit;

    if(!empty($get_by_id_data))
    {
      $common_profile_date_pro =array();
      $common_profile_bookIDS=array();
      foreach($get_by_id_data as $value) 
      {
        $booked_profile_data = $this->ipd_discharge_summary->get_booking_profile_print_name($value->id); 
          if(!empty($booked_profile_data))
          { 
              
                foreach($booked_profile_data as $booked_profl)
                {
                  $common_profile_date_pro[]['profile_name'] = $booked_profl->profile_name; 
                  $common_profile_date_pro[]['profile_id'] = $booked_profl->profile_id;
                  $common_profile_date_pro[]['booking_id'] =$value->id;
                  $common_profile_bookIDS['booking_id'][] =$value->id;
        
                }
          }
        }
      }
      //echo "<pre>"; print_r($common_profile_bookIDS['booking_id']); exit;
      $common_booking_ids=array();
      if(!empty($common_profile_bookIDS['booking_id']))
      {
        $n=0;
        foreach ($common_profile_bookIDS['booking_id'] as $key => $value) 
        {
          if(!in_array($value, $common_booking_ids))
          {
            $common_booking_ids[$n] = $value; 
            $n++;  
          }
          
        }
       }
    //echo "<pre>"; print_r($common_booking_ids); exit;
      
      //echo "<pre>"; print_r($common_profile_bookIDS); exit;
      if(!empty($common_profile))
      { $row=2;
        $test_report_data .='<table style="width:100%;border-collapse:collapse;font:13px arial;">
  <tr>
    <td width="5%"></td>
    <td><table style="width:100%;border-collapse:collapse;font:13px arial;" id="test_name_table"><thead><tr><th colspan="2" align="left" style="font-size:15px;">Investigation</th>
                  <th align="left" style="padding:4px;" colspan="'.count($common_profile_date).'">Report</th></tr><tr>
                  <th align="left" style="padding:4px;width:100px;">S. No.</th>
                  <th align="left" style="padding:4px;">Investigations</th>';
                  foreach ($common_profile_date as $value) 
                  {
                      $test_report_data .='<th  align="left" >'.date('d-m-Y',strtotime($value)).'</th>';  
                  }
                  
                  foreach ($common_profile_name_list as $key =>$value) 
                  {
                    $progilr_id = $common_profile_id_list[$key];
                    $test_report_data .='<thead><tr><th colspan="2" align="left"><u>'.$value.'</u></th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  

                    
                      //echo "<pre>";print_r($common_profile_bookIDS); exit;
                      if(!empty($common_profile_date_pro))
                      {
                        $k=0;
                        
                        //echo "<pre>";print_r($common_profile_date_pro); exit;
                         foreach ($common_profile_date_pro as $pro_value) 
                         {
                            if($value==$pro_value['profile_name'])
                            {
                                $test_BookingID = $common_profile_bookIDS['booking_id'][$k];
                                $test_profiles =  $this->ipd_discharge_summary->report_test_list($test_BookingID,'','',$progilr_id);
                               if(!empty($test_profiles))
                               {
                                $n=1;
                                foreach ($test_profiles as $value) 
                                {
                                      $test_report_data .='<tr>
                                        <td>'.$n.'</td>
                                        <td>'.$value->test_name.'</td>';
                                        $s=0;
                                        foreach ($common_profile_date as $valued) 
                                        {
                                          $test_BookingID = $common_booking_ids[$s];
                                          $get_result_on_date = $this->test->get_test_result_by_date($value->test_id,$value->profile_id,$valued,$test_BookingID);
                                          //echo "<pre>";print_r($get_result_on_date); 
                                          $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                          $s++;

                                        }
                                        
                                        $test_report_data .='</tr>';
                                        $n++;
                                }
                               }
                            }
                            $k++;       
                         }
                      }     
                  }
      }


    //test
    if(!empty($get_by_id_data))
		{
			$result_test_data = array();
		  $x=1;

      $test_report_data .='<thead><tr><th colspan="3">Test</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  

			foreach($get_by_id_data as $value) 
			{
				$datatest_list = $this->ipd_discharge_summary->report_test_list($value->id,'','0');
        
				if(!empty($datatest_list))
        {

          //echo "<pre>";  print_r($get_by_id_data);
          //print_r($booking_id)

          foreach($datatest_list as $mytest_list) 
  				{
  				  $test_report_data .='<tr>';
            $test_report_data .='<td style="padding:4px;">'.$x.'</td>';
            $test_report_data .='<td style="padding:4px;">'.$mytest_list->test_name.'</td>';

            $s=0;
            foreach ($common_profile_date as $valued) 
            {
              $test_BookingID = $booked_test_list_data[$s];
              $get_result_on_date = $this->test->get_test_result_by_date($mytest_list->test_id,'',$valued,$mytest_list->booking_id);
              $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
              $s++;

            }
            $test_report_data .='</tr>';
            $x++;		
  				}
				}	
		  }
    }
       $test_report_data .='</tbody></table></td>
  </tr>
</table>'; 
      
              }


              //$data['test_report_data'] = $test_report_data; //exit;

              ////


            
           $data['test_list']=$test_report_data;

           //print_r($data['test_list']); exit;

          }
          else
          {


          $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($discharge_summary_id);

        
            $test_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="6" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left"  style="padding:4px;" valign="top">Test name</th>
                      <th  align="left"  style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" style="padding:4px;" valign="top">Result</th>
                      ';

              
                       
                       $i=0;
                       
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                    
                         $test_data .='<tr>';

                  $test_data .='<tr style="border-bottom:1px dashed #ccc;">
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->test_name.'</td>
                        <td align="left" style="padding:4px;" valign="top">'.$discharge_test_list->test_date.'</td>
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->result.'</td>
                      </tr>';

                      $i++; } 

             $test_data .='</tr></tbody>
                  </table>';

                  $data['test_list']=$test_data;

          }

                  
       // }
  
      
        /************ Investigation ************* medicine_list */

        $this->load->view('ipd_patient_discharge_summary/print_discharge_advance_summary_template',$data);
    
    }

    public function add_medicine($summary_id='')
    {
        unauthorise_permission('124','756');
        $data['page_title'] = "Add IPD Discharge Summary";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array();    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validatemedicine();
            if($this->form_validation->run() == TRUE)
            {
                $discharge_summary_id = $this->ipd_discharge_summary->save_medicine();
                
                $this->session->set_userdata('medicine_discharge_summary_id',$summary_id);
                $this->session->set_flashdata('success','Discharge summary medicine saved successfully.');
                redirect(base_url('ipd_patient_advance_discharge_summary/?status=print_medicine'));
                
            }
            else
            { 
                $data['form_error'] = validation_errors();  
                
            }     
        }
      $this->load->model('general/general_model');
      $data['summary_data'] = $this->ipd_discharge_summary->get_by_id($summary_id);
      //print_r($data['summary_data']);
      /*$data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
      $data['simulation_list'] = $this->general_model->simulation_list(); */  
      $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
      $data['discharge_labels_setting_list'] = $this->discharge_labels_setting->get_active_master_unique();
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_active_vital_master_unique();
      $data['template_list'] = $this->ipd_discharge_summary->template_list();

      $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
      $this->load->view('ipd_patient_discharge_summary/add_advance_medicine',$data); 
    }

    public function view_medicine($summary_id='')
    {
        unauthorise_permission('124','757');
        $data['page_title'] = "IPD Discharge Summary Medicine";  
        
        $data['summary_id'] = $summary_id;    
       
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
        $this->load->view('ipd_patient_discharge_summary/view_medicine',$data); 
    }

    
   
    public function print_discharge_summary_medicine($medicine_summary_ids="",$branch_id='')
    {
        unauthorise_permission('124','758'); 
        $medicine_discharge_summary_id= $this->session->userdata('medicine_discharge_summary_id');
      
        
        if(!empty($medicine_summary_ids))
        {
          $medicine_summary_id =$medicine_summary_ids;
        }else
        {
            $medicine_summary_id = $medicine_discharge_summary_id;
        }
        
        
        
        $data['page_title'] = "Print Medicine Discharge Summary";
        $medicine_summary_info = $this->ipd_discharge_summary->get_medicine_detail_by_summary_id($medicine_summary_id);
        if(empty($medicine_summary_info['discharge_list']))
        {
          $medicine_summary_info = $this->ipd_discharge_summary->get_detail_by_summary_id($medicine_summary_id);
        }
       

        $template_format = $this->ipd_discharge_summary->medicine_template_format(array('setting_name'=>'DISCHARGE_MEDICINE_PRINT_SETTING'),$branch_id);
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $medicine_summary_info;
        //print_r($data['all_detail']); exit;
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
        if(!empty($data['all_detail']['discharge_list'][0]->attend_doctor_id))
        {
          $signature_image = get_doctor_signature($data['all_detail']['discharge_list'][0]->attend_doctor_id);  
        }
        
        
        $data['summary_medicine_data'] = $this->ipd_discharge_summary->get_medicine_by_id($medicine_summary_id);
        //print_r($data['summary_medicine_data']);
        $discharge_medicine_list='';
        //if(!empty($data['summary_medicine_data'])) 
        //{ 
           $discharge_medicine_list .='<table  style="width: 100%;text-align:left;" >';
            $discharge_medicine_list .='<thead>';
            foreach ($data['prescription_medicine_tab_setting'] as $med_value) 
            { 
               if(!empty($med_value->setting_value)) { $laevel_name =  $med_value->setting_value; } else { $laevel_name =  $med_value->var_title; }
               $discharge_medicine_list .='<th>'.$laevel_name.'</th>';
            }
            $discharge_medicine_list .='</thead>';
            if(!empty($data['summary_medicine_data']))
            { 
                        
                        $discharge_medicine_list .='<tbody>';
                         $l=1;
                        foreach ($data['summary_medicine_data'] as $prescription_presc) 
                        {
                          
                        
                        $discharge_medicine_list .='<tr>';
                      
                        
                        foreach ($data['prescription_medicine_tab_setting'] as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $discharge_medicine_list .='<td>'. $prescription_presc['medicine_name'].'</td>';
                        
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_brand'].'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_salt'].'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_type'].'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_dose'].'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_duration'].'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $discharge_medicine_list .='<td>'.$prescription_presc['medicine_frequency'].'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $discharge_medicine_list .='<td>'.$prescription_presc['medicine_advice'].'</td>';
                       } 
                       }
                        
                      $discharge_medicine_list .='</tr>';
                   } 
                   $discharge_medicine_list .='</tbody>';
              }
              else
              {
                //$discharge_medicine_list .='<tbody><tr><td colspan="6" align="center">No matching records found</td></tr></tbody>';
              
              }  
          
        $discharge_medicine_list .='</table>';  
     //}




         $signature_reprt_data ='';
         if(!empty($signature_image))
         {
         
           $signature_reprt_data .='<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
          <table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
            <tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">Signature</b></td>
            </tr>';
            
            if(!empty($signature_image->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->sign_img))
            {
            
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: right;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->sign_img.'"></div></td>
            </tr>';
            
             }
             
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
            </tr>
            
          </table></div></div>';

         }

        $data['signature'] = $signature_reprt_data;
        $data['discharge_medicine_list_row'] =$discharge_medicine_list;
        $this->load->view('ipd_patient_discharge_summary/print_discharge_summary_medicine_template',$data);
    }


    public function medicine_ajax_list($summary_id)
    {   
      unauthorise_permission('124','757');
       
        $this->load->model('ipd_patient_discharge_summary/ipd_medicine_discharge_summary_model','medicine_discharge_summary');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
       //$list = $this->ipd_discharge_summary->get_datatables();
        $list = $this->medicine_discharge_summary->get_medicine_by_id($summary_id); 
        //echo "<pre>";        print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_discharge_summary) {
          
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
           
            ////////// Check list end ///////////// 
           
            $checkboxs = "";
           
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_discharge_summary['id'].'">'.$check_script;
            foreach ($prescription_medicine_tab_setting as $tab_value) 
            { 
            if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_name'];
            }


            if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
            {  
            $row[] = $ipd_discharge_summary['medicine_type'];
            }

            if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
            {
                $row[] = $ipd_discharge_summary['medicine_salt'];
            }
            if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
            {
                $row[] = $ipd_discharge_summary['medicine_brand'];
            }

            if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_dose'];
            }
            if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_duration'];
            }
            if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_frequency'];
            }
            if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
            {
            $row[] = $ipd_discharge_summary['medicine_advice'];
            }
}
            $btnedit='';
            $btndelete='';
          
            
            $data[] = $row;
            $i++;
        }
//echo "<pre>";print_r($data); exit;
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_discharge_summary->count_all(),
                        "recordsFiltered" => $this->medicine_discharge_summary->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    function deleteall_medicine()
    {
        unauthorise_permission('124','759');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_discharge_summary->deleteall_medicine($post['row_id']);
            $response = "Medicne successfully deleted.";
            echo $response;
        }
    }

    
    public function print_discharge_summary_letter_head($summary_id="",$branch_id='',$type='')
    {
        $test_heading_interpretation = '';
        $booking_data_discharge =$this->ipd_discharge_summary->get_detail_by_summary_id($summary_id);
        //echo "<pre>"; print_r($booking_data);die;
        $this->load->library('m_pdf');
        $report_templ_part = $this->ipd_discharge_summary->report_html_template('',$branch_id);
        $template_format = $this->ipd_discharge_summary->test_report_template_format(array('setting_name'=>'DISCHARGE_REPORT_PRINT_SETTING'));

        $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
      $sortArray = array();
      foreach($arr as $value)
      { 
          foreach($value as $key=>$value)
          {
              if(!isset($sortArray[$key])){
                  $sortArray[$key] = array();
              }
              $sortArray[$key][] = $value;
          }
      }
      $orderby = "order_by";
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $discharge_labels_setting_list= $arr;
      $discharge_vital_setting_list= $this->discharge_labels_setting->get_vital_master_unique_array(1,1);
      $discharge_field_master_list= $this->ipd_discharge_summary->discharge_field_master_list(1,1);

      $booking_data = $booking_data_discharge['discharge_list'][0];
    //  print_r($booking_data);die;
        $patient_name=$booking_data->patient_name;
        $patient_code=$booking_data->patient_code;
        $attended_doctor=$booking_data->attend_doctor_id;
        $mobile_no = $booking_data->mobile_no;
        $tube_no = $booking_data->tube_no;
        $gender = $booking_data->gender;
        $age_y = $booking_data->age_y;
        $age_m = $booking_data->age_m;
        $age_d = $booking_data->age_d;
        $age_h = $booking_data->age_h;
        $booking_date = $booking_data->booking_date;
        $booking_time = $booking_data->booking_time;
        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$gender];
       
        $age = "";
        if($age_y>0)
        {
        $year = 'Y';
        if($age_y==1)
        {
          $year = 'Y';
        }
        $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
        $month = 'M';
        if($age_m==1)
        {
          $month = 'M';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'D';
        if($age_d==1)
        {
          $day = 'D';
        }
        $age .= ", ".$age_d." ".$day;
        }
        if($age_h>0)
        {
        $hours = 'H';
        if($age_h==1)
        {
          $hours = 'H';
        }
        $age .= $age_h." ".$hours;
        }
        $patient_age =  $age; 
        $doctor_name = get_doctor_name($attended_doctor);
        $qualification= get_doctor_qualifications($attended_doctor);
      /*******Format***********/
      
      /**********Format********/
      

   

      //$pdf_template = $this->load->view('test/test_pdf_template',$pdf_data,true);
       ///////////////
      $header_replace_part = $report_templ_part->page_details;
      
      $header_replace_part = str_replace("{patient_name}",$patient_name,$header_replace_part); 
/* for doctor qualification */
      $header_replace_part = str_replace("{patient_address}",$booking_data->address,$header_replace_part);

      $header_replace_part = str_replace("{patient_reg_no}",$patient_code,$header_replace_part);

      $header_replace_part = str_replace("{mobile_no}",$mobile_no,$header_replace_part);

     
      $header_replace_part = str_replace("{ipd_no}",$booking_data->ipd_no,$header_replace_part);
      $header_replace_part = str_replace("{admission_date}",$booking_date_time,$header_replace_part);

      $header_replace_part = str_replace("{doctor_name}",$doctor_name,$header_replace_part);

      $header_replace_part = str_replace("{specialization}",$qualification,$header_replace_part);

      $header_replace_part = str_replace("{booking_time}", $test_booking_time, $header_replace_part);
      //booking time//
      $header_replace_part = str_replace("{admission_date}",$booking_data->admission_date,$header_replace_part);
      $header_replace_part = str_replace("{discharge_date}",$booking_data->discharge_date,$header_replace_part);
      
     


      $gender_age = $gender.'/'.$patient_age;

      $header_replace_part = str_replace("{patient_age}",$gender_age,$header_replace_part);

   

      $middle_replace_part = $report_templ_part->page_middle;
      $pos_start = strpos($middle_replace_part, '{start_loop}');
      $pos_end = strpos($middle_replace_part, '{end_loop}');
      $row_last_length = $pos_end-$pos_start;
      $row_loop = substr($middle_replace_part,$pos_start+12,$row_last_length-12);


      foreach ($discharge_labels_setting_list as $value) 
    {   
    //echo "<pre>".$value->setting_name;     
    if(strcmp(strtolower($value['setting_name']),'cheif_complaints')=='0')
    {

        if(!empty($value['setting_value'])) { $cheif_complaints_name =  $value['setting_value']; } else { $cheif_complaints_name =  $value['var_title']; }

            if(!empty($booking_data_discharge['discharge_list'][0]->chief_complaints)){ 
            $chief_complaints.= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>'.$cheif_complaints_name.' :</b>
               '.nl2br($booking_data_discharge['discharge_list'][0]->chief_complaints).'
              </div>
            </div>';
            }
    }

   



    if(strcmp(strtolower($value['setting_name']),'ho_presenting_illness')=='0')
    {
       
         if(!empty($value['setting_value'])) { $Personal_history =  $value['setting_value']; } else { $Personal_history =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->h_o_presenting)){ 
        $h_o_presenting_illness = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Personal_history.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->h_o_presenting).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value['setting_name']),'on_examination')=='0')
    {   
        if(!empty($value['setting_value'])) { $on_examination_name =  $value['setting_value']; } else { $on_examination_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->on_examination)){ 
        $on_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$on_examination_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->on_examination).'
          </div>
        </div>';
        }
    }

              if(strcmp(strtolower($value['setting_name']),'mlc_fir_no')=='0')
              {
                if(!empty($value['setting_value'])) { $mlc_fir_no_name =  $value['setting_value']; } else { $mlc_fir_no_name =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->mlc_fir_no)){ 
                $mlc_fir_no = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$mlc_fir_no_name.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->mlc_fir_no).'
                </div>
                </div>';
                 } 

              }
                  if(strcmp(strtolower($value['setting_name']),'lama_dama_reasons')=='0')
              {
                if(!empty($value['setting_value'])) { $lama_dama_reason =  $value['setting_value']; } else { $lama_dama_reason =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->lama_dama_reasons)){ 
                $lama_dama_reasons = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$lama_dama_reason.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->lama_dama_reasons).'
                </div>
                </div>';
                 }

              }
                  if(strcmp(strtolower($value['setting_name']),'refered_data')=='0')
              {
                if(!empty($value['setting_value'])) { $refered_data_name =  $value['setting_value']; } else { $refered_data_name =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->refered_data)){ 
                $refered_data = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$refered_data_name.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->refered_data).'
                </div>
                </div>';
                 }

              }

    

    if(strcmp(strtolower($value['setting_name']),'vitals')=='0')
    {
        if(!empty($value['setting_value'])) { $vitals_name =  $value['setting_value']; } else { $vitals_name =  $value['var_title']; }
       
        $vitals = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$vitals_name.' :</b>';
        $vitals .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
            <tbody>
                <tr>';
            foreach($discharge_vital_setting_list as $discharge_vital_labels)
            {
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'pulse')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $pulse_name = $discharge_vital_labels['setting_value']; } else { $pulse_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_pulse))
                    {
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$pulse_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_pulse).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>'; 
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'chest')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_chest_name = $discharge_vital_labels['setting_value']; } else { $vitals_chest_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_chest))
                    {
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_chest_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_chest).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>'; 
                    }
                }


                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'bp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $bp_name = $discharge_vital_labels['setting_value']; } else { $bp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_bp))
                    {
                    
                    $vitals .='
                            <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$bp_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                        <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_bp).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cvs')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_cvs_name = $discharge_vital_labels['setting_value']; } else { $vitals_cvs_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cvs))
                    {
                    
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_cvs_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cvs).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'temp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_temp_name = $discharge_vital_labels['setting_value']; } else { $vitals_temp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_temp))
                    {
                    
                    $vitals .='

                    <td align="left" valign="top" width="20%">
                    <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                        <tbody>
                            <tr>
                                <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_temp_name.'</b></th>
                            </tr>
                            <tr>
                                <td align="left" valign="top">
                                <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_temp).'</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cns')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $cns_name = $discharge_vital_labels['setting_value']; } else { $cns_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cns))
                    {
                    
                    $vitals .='
                    <td align="left" valign="top" width="20%">
                        <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                            <tbody>
                                <tr>
                                    <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$cns_name.'</b></th>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">
                                    <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cns).'</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'p_a')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $p_a_name = $discharge_vital_labels['setting_value']; } else { $p_a_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_p_a))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$p_a_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                        <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_p_a).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }     
            
           

        }


         $vitals .='</tr>
                    </tbody>
                </table></div>
                        </div>';
      
    }
        
    






    if(strcmp(strtolower($value['setting_name']),'provisional_diagnosis')=='0')
    { 
        if(!empty($value['setting_value'])) { $Diagnosis_name =  $value['setting_value']; } else { $Diagnosis_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->provisional_diagnosis)){ 
        $provisional_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Diagnosis_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->provisional_diagnosis).'
          </div>
        </div>';
        }
    }
        



    if(strcmp(strtolower($value['setting_name']),'final_diagnosis')=='0')
    {
        if(!empty($value['setting_value'])) { $final_diagnosis_name =  $value['setting_value']; } else { $final_diagnosis_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->final_diagnosis)){ 
        $final_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b> '.$final_diagnosis_name.' :</b>'.nl2br($booking_data_discharge['discharge_list'][0]->final_diagnosis).'
          
          </div>
        </div>';
        }
    }    
    


    if(strcmp(strtolower($value['setting_name']),'course_in_hospital')=='0')
    { 
        if(!empty($value['setting_value'])) { $course_in_hospital_name =  $value['setting_value']; } else { $course_in_hospital_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->course_in_hospital)){ 
        $course_in_hospital = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$course_in_hospital_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->course_in_hospital).'
          </div>
        </div>';
        }
    }


    if(strcmp(strtolower($value['setting_name']),'investigation')=='0')
    { 
        if(!empty($value['setting_value'])) { $investigations_name =  $value['setting_value']; } else { $investigations_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->investigations)){ 
        $investigation = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$investigations_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->investigations).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value['setting_name']),'condition_at_discharge_time')=='0')
    { 
        if(!empty($value['setting_value'])) { $discharge_time_condition_name =  $value['setting_value']; } else { $discharge_time_condition_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->discharge_time_condition)){ 
        $condition_at_discharge_time = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$discharge_time_condition_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->discharge_time_condition).'
          </div>
        </div>';
        }
    }

    if(strcmp(strtolower($value['setting_name']),'advise_on_discharge')=='0')
    { 
        if(!empty($value['setting_value'])) { $discharge_time_condition_name =  $value['setting_value']; } else { $discharge_time_condition_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->discharge_advice)){ 
        $advise_on_aischarge = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$discharge_time_condition_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->discharge_advice).'
          </div>
        </div>';
        }
    }
    
   
   if(strcmp(strtolower($value['setting_name']),'review_time_and_date')=='0')
   {
       if(!empty($value['setting_value'])) 
       { 
            $review_time_date_name =  $value['setting_value']; } else { $review_time_date_name =  $value['var_title']; 
       }
       $time='';
       if(!empty($booking_data_discharge['discharge_list'][0]->review_time_date) && $booking_data_discharge['discharge_list'][0]->review_time_date!='00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='00:00:00 00:00:00')
       {
            $time = $booking_data_discharge['discharge_list'][0]->review_time;
       }
       if(!empty($booking_data_discharge['discharge_list'][0]->review_time) && $booking_data_discharge['discharge_list'][0]->review_time!='0000-00-00 00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='1970:01:01 00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='1970-01-01')
       {
        $review_time_and_date .= '<div style="float:left;width:100%;margin:1em 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$review_time_date_name.' :</b>
            '.date('d-m-Y',strtotime($booking_data_discharge['discharge_list'][0]->review_time_date)).' '.$time.'
          </div>
        </div>';
       }
    }

    //seprated vitals
               
               //echo "<pre>"; print_r($value); 
                if(strcmp(strtolower($value['setting_name']),'pulse')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_pulse)){ 
                    $vitals_pulse.= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$pulse_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_pulse).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'chest')=='0' && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $vitals_chest_name =  $value['setting_value']; } else { $vitals_chest_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_chest)){ 
                    $vitals_chest .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_chest_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_chest).'
                      </div>
                    </div>';
                    }
                }
               
                if(strcmp(strtolower($value['setting_name']),'bp')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $bp_name =  $value['setting_value']; } else { $bp_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_bp)){ 
                    $vitals_bp .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$bp_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_bp).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'cvs')=='0'  && $value['type'] =='vitals')
                { //echo $booking_data_discharge['discharge_list'][0]->vitals_cvs.''. strtolower($value['setting_name']);

                    if(!empty($value['setting_value'])) { $vitals_cvs_name =  $value['setting_value']; } else { $vitals_cvs_name =  $value['var_title']; }

                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cvs)){  


                    $vitals_cvs .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_cvs_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cvs).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'temp')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $vitals_temp_name =  $value['setting_value']; } else { $vitals_temp_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_temp)){ 
                    $vitals_temp .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_temp_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_temp).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'cns')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $cns_name =  $value['setting_value']; } else { $cns_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cns)){ 
                    $vitals_cns .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$cns_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cns).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'p_a')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $p_a_name =  $value['setting_value']; } else { $p_a_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_p_a)){ 
                    $vitals_p_a .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$p_a_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_p_a).'
                      </div>
                    </div>';
                    }
                }

    }

    $middle_replace_part = str_replace("{chief_complaints}",$chief_complaints,$middle_replace_part);
    $middle_replace_part = str_replace("{h_o_presenting_illness}",$h_o_presenting_illness,$middle_replace_part);
    $middle_replace_part = str_replace("{on_examination}",$on_examination,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals}",$vitals,$middle_replace_part);
    $middle_replace_part = str_replace("{provisional_diagnosis}",$provisional_diagnosis,$middle_replace_part);
    $middle_replace_part = str_replace("{final_diagnosis}",$final_diagnosis,$middle_replace_part);
    $middle_replace_part = str_replace("{course_in_hospital}",$course_in_hospital,$middle_replace_part);
    $middle_replace_part = str_replace("{investigation}",$investigation,$middle_replace_part);
    $middle_replace_part = str_replace("{condition_at_discharge_time}",$condition_at_discharge_time,$middle_replace_part);
    $middle_replace_part = str_replace("{advise_on_discharge}",$advise_on_aischarge,$middle_replace_part);
    $middle_replace_part = str_replace("{review_time_and_date}",$review_time_and_date,$middle_replace_part);

    //
    $middle_replace_part = str_replace("{h_o_past}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{mlc_fir_no}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{ho_any_allergy}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{medicine_prescribed}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{lama_dama_reasons}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{refered_data}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{icd_code}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{admission_reason}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{family_history}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{alcohol_history}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{procedure_performedand}",'',$middle_replace_part);
     $middle_replace_part = str_replace("{nutritional_advice}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{consultants_name}",'',$middle_replace_part);

    //

    $middle_replace_part = str_replace("{vitals_pulse}",$vitals_pulse,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_chest}",$vitals_chest,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_bp}",$vitals_bp,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_cvs}",$vitals_cvs,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_temp}",$vitals_temp,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_cns}",$vitals_cns,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_p_a}",$vitals_p_a,$middle_replace_part);

        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
      // echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
        if(!empty($summary_info['medicine_list'][0]->medicine_name) && $summary_info['medicine_list'][0]->medicine_name!=""){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="10" style="padding:4px;" valign="top">MEDICINE DETAILS:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_list'] as $prescription_presc)
                     { 
                         $medicine_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_data .='</tr></tbody>
                  </table>';

                
        }
        else{
          $medicine_data='';
        }
  
      
        /************ Medicine *************/

          /************ Investigation *************/
          $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($discharge_summary_id);

        if(!empty($summary_info['investigation_test_list'][0]->test_name) && $summary_info['investigation_test_list'][0]->test_name!=""){
            $test_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="6" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Test name</th>
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Result</th>
                      ';

              
                       
                       $i=0;
                       
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                    
                         $test_data .='<tr>';

                  $test_data .='<tr style="border-bottom:1px dashed #ccc;">
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$discharge_test_list->test_name.'</td>
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$discharge_test_list->test_date.'</td>
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$discharge_test_list->result.'</td>
                      </tr>';

                      $i++; } 

             $test_data .='</tr></tbody>
                  </table>';

                 
        }
  
      
        /************ Investigation *************/


    $middle_replace_part = str_replace("{medicine_prescribed}",$medicine_data,$middle_replace_part);
      $middle_replace_part = str_replace("{investigation}",$test_data,$middle_replace_part);


       $middle_replace_part = str_replace("{past_history}", '', $middle_replace_part);
        $middle_replace_part = str_replace("{menstrual_history}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{obstetric_history}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{surgery_preferred}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{operation_notes}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{treatment_given}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{medicine_administered_detail}", '', $middle_replace_part);
         $middle_replace_part = str_replace("{medicine_detail}", '', $middle_replace_part); 
         $middle_replace_part = str_replace("{signature}", '', $middle_replace_part); 

      
        
        //echo "<pre>"; print_r($report_templ_part->page_footer);die;
        $footer_data_part = $report_templ_part->page_footer;

   // $footer_data_part=str_replace("{doctor_name}",$doctor_name,$footer_data_part);
   // $footer_data_part=str_replace("{signature}",$signature,$footer_data_part);
 // echo $middle_replace_part;die;

        ////////////////////

      $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 

    
   
     if($type=='Download')
    {
        if($template_format->header_pdf==1)
        {
           $page_header = $report_templ_part->page_header;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$report_templ_part->page_header.'</div>';
        }

        if($template_format->details_pdf==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_format->middle_pdf==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_format->footer_pdf==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
        }
       $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part);
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf'; 
        $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
        // $this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "F");
        
    }
    else 
    { 

    // echo $middle_replace_part;die;
        
        if($template_format->header_print==1)
        {
           $page_header = $report_templ_part->page_header;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$report_templ_part->page_header.'</div><br></br>';
        }

        if($template_format->details_print==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_format->middle_print==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_format->footer_print==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
           $footer_data_part = '<p style="height:'.$template_format->pixel_value.'px;"></p>'; 
            $this->m_pdf->pdf->defaultfooterline = 0;
        }

        

        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part); 
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
    }  
  
  
      
    }
    
    public function print_template($id)
    {
        $data['page_title'] = 'Select Template';
        //$post = $this->input->post();  
        $data['id'] = $id;
        $this->load->view('ipd_patient_discharge_summary/advance_template',$data);
   
    }
    
    
    public function print_discharge_summary_page($summary_id="",$branch_id='',$type='')
    {
         
       //IPD_DISCHARGE_INVESTIGATION_TEST
       $discharge_summary_id= $this->session->userdata('discharge_summary_id');
        if(!empty($discharge_summary_id))
        {
            $discharge_summary_id = $discharge_summary_id;
        }
        else
        {
            $discharge_summary_id =$summary_id;
        }
        
        $data['page_title'] = "Print Discharge Summary";
        $summary_info = $this->ipd_discharge_summary->get_detail_by_summary_id($discharge_summary_id);
        //echo "<pre>";print_r($summary_info); exit;
        $ipd_id = $summary_info['discharge_list'][0]->ipd_id;
        $template_format = $this->ipd_discharge_summary->template_format(array('setting_name'=>'DISCHARGE_SUMMARY_PRINT_SETTING'),$branch_id,$type);
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $summary_info;

      $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
      $sortArray = array();
      foreach($arr as $value){ //print_r($value);
          foreach($value as $key=>$value)
          {
              if(!isset($sortArray[$key])){
                  $sortArray[$key] = array();
              }
              $sortArray[$key][] = $value;
          }
      }
      $orderby = "order_by"; //discharge_labels_setting_list
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $data['discharge_labels_setting_list'] = $arr;
      $data['discharge_vital_setting_list'] = $this->discharge_labels_setting->get_vital_master_unique_array(1,1);

        $signature_image = get_doctor_signature($data['all_detail']['discharge_list'][0]->attend_doctor_id);
        
         $signature_reprt_data ='';
         if(!empty($signature_image))
         {
         
           $signature_reprt_data .='<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
          <table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
            <tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">Signature</b></td>
            </tr>';
            
            if(!empty($signature_image->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->sign_img))
            {
            
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->sign_img.'"></div></td>
            </tr>';
            
             }
             
            $signature_reprt_data .='<tr>
            <td width="80%"></td>
              <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
            </tr>
            
          </table></div></div>';

         }
        $data['signature'] = $signature_reprt_data;
        $discharge_master_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field_print($discharge_summary_id);
        //print_r($get_payment_detail); exit;
        $total_values=array();
        for($i=0;$i<count($discharge_master_detail);$i++) 
        {
            $total_values[]= $discharge_master_detail[$i]->field_value.'__'.$discharge_master_detail[$i]->discharge_lable.'__'.$discharge_master_detail[$i]->field_id.'__'.$discharge_master_detail[$i]->type.'__'.$discharge_master_detail[$i]->discharge_short_code;
        }
        $data['field_name'] = $total_values;
        
        
        
        
        
        
        
        /************ Medicine Administered*************/


        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
       //echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
        if(!empty($summary_info['medicine_administered_detail'][0]->medicine_name) && $summary_info['medicine_administered_detail'][0]->medicine_name!=""){
          $medicine_admib_data='';
            $medicine_admib_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="10" style="padding:4px;" valign="top">MEDICATION ADMINISTERED:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_admib_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_admib_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_admib_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_administered_detail'] as $prescription_presc)
                     { 
                         $medicine_admib_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_admib_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_admib_data .='</tr></tbody>
                  </table>';

                  $data['medicine_administered_list']=$medicine_admib_data;
        }
        else{
          $data['medicine_administered_list']='';
        }
 // print_r($data['medicine_administered_list']);die;
      
        /************ Medicine Administered *************/



        /************ Medicine *************/


        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
       //echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
       if(!empty($summary_info['medicine_list'])){
        if(!empty($prescription_medicine_tab_setting)){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="15" style="padding:4px;" valign="top">Medicine Prescribed:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_list'] as $prescription_presc)
                     { 
                         $medicine_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_data .='</tr></tbody>
                  </table>';

                  $data['medicine_list']=$medicine_data;
        }
       }
       
        else{
          $data['medicine_list']='';
        }
  
      
          /************ Medicine  patient_id *************/
          /************ Investigation *************/
         $discharge_setting = get_setting_value('IPD_DISCHARGE_INVESTIGATION_TEST'); //die;
          
          if($discharge_setting=='1')
          {

            $ipd_test_data = $this->ipd_discharge_summary->get_ipd_pathology_test($ipd_id);
            $booking_id = $ipd_test_data['id'];

              ///

              if(!empty($booking_id))
              {
                  
          
      
      $this->load->model('test/test_model','test');
      $get_by_id_data = $this->test->patient_booking_list($ipd_id); 
		//echo "<pre>"; print_r($get_by_id_data); die;

    
    if(!empty($get_by_id_data))
    {
      $common_profile =array();
      
      foreach($get_by_id_data as $value) 
      {
        $booked_profile_data = $this->ipd_discharge_summary->get_booking_profile_print_name($value->id); 
        //echo "<pre>"; print_r($booked_profile_data); exit;
          if(!empty($booked_profile_data))
          { 
              
                foreach($booked_profile_data as $booked_profl)
                {
                  if(!in_array($booked_profl->profile_name,$common_profile))
                  {
                      $common_profile['profile_name'][] = $booked_profl->profile_name; 
                      $common_profile['profile_id'][] = $booked_profl->profile_id;
                  }
                   
                }
          }
        }
      }

//echo "<pre>"; print_r($common_profile); exit;
    $common_profile_name_list = array_unique($common_profile['profile_name']);
    $common_profile_id_list = array_unique($common_profile['profile_id']);
     //$common_profile_name_list = $this->super_unique($common_profile,'profile_name'); 
     //echo "<pre>"; print_r($common_profile_id_list); exit;
      if(!empty($get_by_id_data))
      {
        $common_profile_date =array();
        foreach($get_by_id_data as $value) 
        {
          if(!empty($value->booking_date) && $value->booking_date!='1970-01-01')
          {
            $date_key = strtotime($value->booking_date); 
              if(!isset($common_profile_date[$date_key])){ 
                  $common_profile_date[$date_key] = $value->booking_date;
              }
          }
              
          
        }
      }
      //echo "<pre>"; print_r($common_profile_data); exit;

    if(!empty($get_by_id_data))
    {
      $common_profile_date_pro =array();
      $common_profile_bookIDS=array();
      foreach($get_by_id_data as $value) 
      {
        $booked_profile_data = $this->ipd_discharge_summary->get_booking_profile_print_name($value->id); 
          if(!empty($booked_profile_data))
          { 
              
                foreach($booked_profile_data as $booked_profl)
                {
                  $common_profile_date_pro[]['profile_name'] = $booked_profl->profile_name; 
                  $common_profile_date_pro[]['profile_id'] = $booked_profl->profile_id;
                  $common_profile_date_pro[]['booking_id'] =$value->id;
                  $common_profile_bookIDS['booking_id'][] =$value->id;
        
                }
          }
        }
      }
      //echo "<pre>"; print_r($common_profile_bookIDS['booking_id']); exit;
      $common_booking_ids=array();
      if(!empty($common_profile_bookIDS['booking_id']))
      {
        $n=0;
        foreach ($common_profile_bookIDS['booking_id'] as $key => $value) 
        {
          if(!in_array($value, $common_booking_ids))
          {
            $common_booking_ids[$n] = $value; 
            $n++;  
          }
          
        }
       }
    //echo "<pre>"; print_r($common_booking_ids); exit;
      
      //echo "<pre>"; print_r($common_profile_bookIDS); exit;
      if(!empty($common_profile))
      { $row=2;
        $test_report_data .='<table style="width:100%;border-collapse:collapse;font:13px arial;">
  <tr>
    <td width="5%"></td>
    <td><table style="width:100%;border-collapse:collapse;font:13px arial;" id="test_name_table"><thead><tr><th colspan="2" align="left" style="font-size:15px;">Investigation</th>
                  <th align="left" style="padding:4px;" colspan="'.count($common_profile_date).'">Report</th></tr><tr>
                  <th align="left" style="padding:4px;width:100px;">S. No.</th>
                  <th align="left" style="padding:4px;">Investigations</th>';
                  foreach ($common_profile_date as $value) 
                  {
                      $test_report_data .='<th  align="left" >'.date('d-m-Y',strtotime($value)).'</th>';  
                  }
                  
                  foreach ($common_profile_name_list as $key =>$value) 
                  {
                    $progilr_id = $common_profile_id_list[$key];
                    $test_report_data .='<thead><tr><th colspan="2" align="left"><u>'.$value.'</u></th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  

                    
                      //echo "<pre>";print_r($common_profile_bookIDS); exit;
                      if(!empty($common_profile_date_pro))
                      {
                        $k=0;
                        
                        //echo "<pre>";print_r($common_profile_date_pro); exit;
                         foreach ($common_profile_date_pro as $pro_value) 
                         {
                            if($value==$pro_value['profile_name'])
                            {
                                $test_BookingID = $common_profile_bookIDS['booking_id'][$k];
                                $test_profiles =  $this->ipd_discharge_summary->report_test_list($test_BookingID,'','',$progilr_id);
                               if(!empty($test_profiles))
                               {
                                $n=1;
                                foreach ($test_profiles as $value) 
                                {
                                      $test_report_data .='<tr>
                                        <td>'.$n.'</td>
                                        <td>'.$value->test_name.'</td>';
                                        $s=0;
                                        foreach ($common_profile_date as $valued) 
                                        {
                                          $test_BookingID = $common_booking_ids[$s];
                                          $get_result_on_date = $this->test->get_test_result_by_date($value->test_id,$value->profile_id,$valued,$test_BookingID);
                                          //echo "<pre>";print_r($get_result_on_date); 
                                          $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                          $s++;

                                        }
                                        
                                        $test_report_data .='</tr>';
                                        $n++;
                                }
                               }
                            }
                            $k++;       
                         }
                      }     
                  }
      }


    //test
    if(!empty($get_by_id_data))
		{
			$result_test_data = array();
		  $x=1;

      $test_report_data .='<thead><tr><th colspan="3">Test</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  

			foreach($get_by_id_data as $value) 
			{
				$datatest_list = $this->ipd_discharge_summary->report_test_list($value->id,'','0');
        
				if(!empty($datatest_list))
        {

          //echo "<pre>";  print_r($get_by_id_data);
          //print_r($booking_id)

          foreach($datatest_list as $mytest_list) 
  				{
  				  $test_report_data .='<tr>';
            $test_report_data .='<td style="padding:4px;">'.$x.'</td>';
            $test_report_data .='<td style="padding:4px;">'.$mytest_list->test_name.'</td>';

            $s=0;
            foreach ($common_profile_date as $valued) 
            {
              $test_BookingID = $booked_test_list_data[$s];
              $get_result_on_date = $this->test->get_test_result_by_date($mytest_list->test_id,'',$valued,$mytest_list->booking_id);
              $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
              $s++;

            }
            $test_report_data .='</tr>';
            $x++;		
  				}
				}	
		  }
    }
       $test_report_data .='</tbody></table></td>
  </tr>
</table>'; 
      
              }


              //$data['test_report_data'] = $test_report_data; //exit;

              ////


            
           $data['test_list']=$test_report_data;

           //print_r($data['test_list']); exit;

          }
          else
          {


          $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($discharge_summary_id);

        
            $test_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="6" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left"  style="padding:4px;" valign="top">Test name</th>
                      <th  align="left"  style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" style="padding:4px;" valign="top">Result</th>
                      ';

              
                       
                       $i=0;
                       
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                    
                         $test_data .='<tr>';

                  $test_data .='<tr style="border-bottom:1px dashed #ccc;">
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->test_name.'</td>
                        <td align="left" style="padding:4px;" valign="top">'.$discharge_test_list->test_date.'</td>
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->result.'</td>
                      </tr>';

                      $i++; } 

             $test_data .='</tr></tbody>
                  </table>';

                  $data['test_list']=$test_data;

          }

                  
       // }
  
      
        /************ Investigation ************* medicine_list */

        $html = $this->load->view('ipd_patient_discharge_summary/print_discharge_advance_summary_template',$data,true);
        
        $this->load->library('m_pdf');
        
        $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
        $this->m_pdf->pdf->WriteHTML($stylesheet,1);
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($html,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
        
    
    }
    
    
    
    public function print_advance_discharge_summary_letter_head($summary_id="",$branch_id='',$type='')
    {
   
        //$booked_total_department = $this->test->booked_total_department($booking_id);; //summary_info
        $test_heading_interpretation = '';
        $booking_data_discharge =$this->ipd_discharge_summary->get_detail_by_summary_id($summary_id);
        //echo "<pre>"; print_r($booking_data_discharge);die;
        $summary_info = $this->ipd_discharge_summary->get_detail_by_summary_id($summary_id);
         $ipd_id = $summary_info['discharge_list'][0]->ipd_id;
         
         $ipd_assigned_doctors=get_ipd_assigned_doctors_name($ipd_id);
        $ipd_assigned_doctors=$ipd_assigned_doctors[0]->assigned_doctor;
        $ipd_assigned_doctors=explode(',', $ipd_assigned_doctors);
        $ipd_assigned_doctors=implode(', Dr. ', $ipd_assigned_doctors);
       
        
        
        //echo "<pre>"; print_r($booking_data);die;
        $this->load->library('m_pdf');
        $report_templ_part = $this->ipd_discharge_summary->report_html_template('',$branch_id);
        
        //echo "<pre>"; print_r($report_templ_part);die;
        $template_format = $this->ipd_discharge_summary->test_report_template_format_adv(array('setting_name'=>'DISCHARGE_REPORT_PRINT_SETTING'));
        
        //echo "<pre>"; print_r($template_format);die;

        $this->load->model('discharge_labels_setting/discharge_advance_labels_setting_model','discharge_labels_setting');
      $discharge_labels_setting_list = $this->discharge_labels_setting->get_master_unique(1,1);
      //echo "<pre>"; print_r($discharge_labels_setting_list); exit;
      $discharge_seprate_vital_list = $this->discharge_labels_setting->get_seprate_vital_master_unique(1,1);
      
      //echo "<pre>";print_r($discharge_seprate_vital_list); exit;
      $arr = array_merge($discharge_labels_setting_list,$discharge_seprate_vital_list);
      $sortArray = array();
      foreach($arr as $value)
      { 
          foreach($value as $key=>$value)
          {
              if(!isset($sortArray[$key])){
                  $sortArray[$key] = array();
              }
              $sortArray[$key][] = $value;
          }
      }
      $orderby = "order_by";
      array_multisort($sortArray[$orderby],SORT_ASC,$arr);
      $discharge_labels_setting_list= $arr;
      //echo "<pre>";print_r($discharge_labels_setting_list); exit;
      $discharge_vital_setting_list= $this->discharge_labels_setting->get_vital_master_unique_array(1,0);
      //echo "<pre>";print_r($discharge_vital_setting_list); exit;
      $discharge_field_master_list= $this->ipd_discharge_summary->discharge_field_master_list(1,1);

      $booking_data = $booking_data_discharge['discharge_list'][0];
    //  print_r($booking_data);die;
        $patient_name=$booking_data->patient_name;
        $simulation = $booking_data->simulation;
        $patient_code=$booking_data->patient_code;
        $attended_doctor=$booking_data->attend_doctor_id;
        $mobile_no = $booking_data->mobile_no;
        $tube_no = $booking_data->tube_no;
        $gender = $booking_data->gender;
        $age_y = $booking_data->age_y;
        $age_m = $booking_data->age_m;
        $age_d = $booking_data->age_d;
        $age_h = $booking_data->age_h;
        $booking_date = $booking_data->booking_date;
        $booking_time = $booking_data->booking_time;
        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$gender];
       $k=0;
        $age = "";
        if($age_y>0)
        {
        $year = 'Y';
        if($age_y==1)
        {
          $year = 'Y';
        }
        $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
        $month = 'M';
        if($age_m==1)
        {
          $month = 'M';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'D';
        if($age_d==1)
        {
          $day = 'D';
        }
        $age .= ", ".$age_d." ".$day;
        }
        if($age_h>0)
        {
        $hours = 'H';
        if($age_h==1)
        {
          $hours = 'H';
        }
        $age .= $age_h." ".$hours;
        }
        $patient_age =  $age; 
        $doctor_name = get_doctor_name($attended_doctor);
        $qualification= get_doctor_qualifications($attended_doctor);
      /*******Format***********/
      
      /**********Format********/
      

   
   $admission_date_time='';
    if(!empty($booking_data->admission_date) && $booking_data->admission_date!='0000-00-00')
    {
                 $time = '';
                if(date('h:i:s', strtotime($booking_data->admission_time))!='12:00:00')
                {
                    $time = date('h:i A', strtotime($booking_data->admission_time));
                }
               $admission_date_time = date('d-m-Y',strtotime($booking_data->admission_date)).' '.$time;
           
             
    }
  
    $booking_date_time = $admission_date_time;

      //$pdf_template = $this->load->view('test/test_pdf_template',$pdf_data,true);
       ///////////////
      $header_replace_part = $report_templ_part->page_details;
      
      $header_replace_part = str_replace("{patient_name}",$simulation.' '.$patient_name,$header_replace_part); 
/* for doctor qualification */
      
      
      //address setting
    $this->load->model('address_print_setting/address_print_setting_model','address_setting');
      $address_setting_list = $this->address_setting->get_master_unique();
    if(empty($address_setting_list))
    {
         $header_replace_part = str_replace("{patient_address}",$booking_data->address,$header_replace_part);
    }
    else
    {
        $address='';
        if($address_setting_list[0]->address1)
        {
           $address .= $booking_data->address.' '; 
        }
        if($address_setting_list[0]->address2)
        {
           $address .= $booking_data->address2.' '; 
        }
       
        if($address_setting_list[0]->address3)
        {
           $address .=  $booking_data->address3.' '; 
        }
      
        if($address_setting_list[0]->city)
        {
           $address .=  $booking_data->city_name.' '; 
        }
       
        if($address_setting_list[0]->state)
        {
           $address .= $booking_data->state_name.' '; 
           //echo $address;die;
        }
       
        if($address_setting_list[0]->country)
        {
           $address .=  $booking_data->country_name.' '; 
        }
        
        if($address_setting_list[0]->pincode)
        {
           $address .= $booking_data->pincode; 
        }
        
        
       
        
        $header_replace_part = str_replace("{patient_address}",$address,$header_replace_part);
    }
      
     // $header_replace_part = str_replace("{patient_address}",$booking_data->address,$header_replace_part);

      $header_replace_part = str_replace("{patient_reg_no}",$patient_code,$header_replace_part);

      $header_replace_part = str_replace("{mobile_no}",$mobile_no,$header_replace_part);

     
      $header_replace_part = str_replace("{ipd_no}",$booking_data->ipd_no,$header_replace_part);
      $header_replace_part = str_replace("{admission_date}",$booking_date_time,$header_replace_part);

      $header_replace_part = str_replace("{doctor_name}",$doctor_name,$header_replace_part);

      $header_replace_part = str_replace("{specialization}",$qualification,$header_replace_part);

      $header_replace_part = str_replace("{booking_time}", $test_booking_time, $header_replace_part);
      
      
       $header_replace_part = str_replace("{assigned_doctor}", "Dr. ".$ipd_assigned_doctors , $header_replace_part);
       
       
      
      //booking time//
      $header_replace_part = str_replace("{admission_date}",$booking_data->admission_date,$header_replace_part);
      
      
      if(!empty($booking_data->dischargedate) && $booking_data->dischargedate!='0000-00-00')
      {
          $time = '';
                if(date('h:i:s', strtotime($booking_data->dischargetime))!='12:00:00')
                {
                    $time = date('h:i A', strtotime($booking_data->dischargetime));
                }
               $discharge_datenew = date('d-m-Y',strtotime($booking_data->dischargedate)).' '.$time;
      }
      elseif(!empty($booking_data->discharge_date) && $booking_data->discharge_date!='0000-00-00 00:00:00')
      {
            $discharge_datenew = date('d-m-Y h:i A',strtotime($booking_data->discharge_date));     
        
        
      }
      
      $header_replace_part = str_replace("{discharge_date}",$discharge_datenew,$header_replace_part);
      
      
      
      if(!empty($booking_data->relation))
        {
        
        $header_replace_part = str_replace("{parent_relation_type}",$booking_data->relation,$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_type}",'',$header_replace_part);
        }

    if(!empty($booking_data->relation_name))
        {
        $rel_simulation = get_simulation_name($booking_data->relation_simulation_id);
        $header_replace_part = str_replace("{parent_relation_name}",$rel_simulation.' '.$booking_data->relation_name,$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_name}",'',$header_replace_part);
        }
        
        
      
      
      
      
      
      if(!empty($all_detail['discharge_list'][0]->relation))
        {
        
        $template_data = str_replace("{parent_relation_type}",$all_detail['discharge_list'][0]->relation,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_type}",'',$template_data);
        }

    if(!empty($all_detail['discharge_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['discharge_list'][0]->relation_simulation_id);
        $template_data = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['discharge_list'][0]->relation_name,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_name}",'',$template_data);
        }
      //print_r($summary_info['discharge_list'][0]->summery_type);
       if($summary_info['discharge_list'][0]->summery_type)
        {
        
        if($summary_info['discharge_list'][0]->summery_type=='1')
        { 
            $sumtype = 'Referral';
        }
         elseif($summary_info['discharge_list'][0]->summery_type=='2')
        { 
            $sumtype = 'Discharge';
        }
         elseif($summary_info['discharge_list'][0]->summery_type=='3')
        { 
            $sumtype = 'D.O.P.R';
        }
         elseif($summary_info['discharge_list'][0]->summery_type=='4')
        { 
            $sumtype = 'Normal';
        }
         elseif($summary_info['discharge_list'][0]->summery_type=='5')
        { 
            $sumtype = 'Death';
        }
        }
        else
        {
          
            $sumtype = 'Lama';
        
        }
    // echo $sumtype;
    
      $summary_types= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>Summary Type :</b>
               '.$sumtype.'
              </div>
            </div>';

      $gender_age = $gender.'/'.$patient_age;

      $header_replace_part = str_replace("{patient_age}",$gender_age,$header_replace_part);
      
     

   

      $middle_replace_part = $report_templ_part->page_middle;
      $pos_start = strpos($middle_replace_part, '{start_loop}');
      $pos_end = strpos($middle_replace_part, '{end_loop}');
      $row_last_length = $pos_end-$pos_start;
      $row_loop = substr($middle_replace_part,$pos_start+12,$row_last_length-12);

$middle_replace_part = str_replace("{summary_type}",$summary_types,$middle_replace_part);
      foreach ($discharge_labels_setting_list as $value) 
    {   
    //echo "<pre>".$value->setting_name;     
    if(strcmp(strtolower($value['setting_name']),'cheif_complaints')=='0')
    {

        if(!empty($value['setting_value'])) { $cheif_complaints_name =  $value['setting_value']; } else { $cheif_complaints_name =  $value['var_title']; }

            if(!empty($booking_data_discharge['discharge_list'][0]->chief_complaints)){ 
            $chief_complaints.= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>'.$cheif_complaints_name.' :</b>
               '.nl2br($booking_data_discharge['discharge_list'][0]->chief_complaints).'
              </div>
            </div>';
            }
    }

   



    if(strcmp(strtolower($value['setting_name']),'ho_presenting_illness')=='0')
    {
       
         if(!empty($value['setting_value'])) { $Personal_history =  $value['setting_value']; } else { $Personal_history =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->h_o_presenting)){ 
        $h_o_presenting_illness = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Personal_history.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->h_o_presenting).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value['setting_name']),'on_examination')=='0')
    {   
        if(!empty($value['setting_value'])) { $on_examination_name =  $value['setting_value']; } else { $on_examination_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->on_examination)){ 
        $on_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$on_examination_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->on_examination).'
          </div>
        </div>';
        }
    }
    
    
    
    if(strcmp(strtolower($value['setting_name']),'past_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $past_history_name =  $value['setting_value']; } else { $past_history_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->past_history)){ 
                $past_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$past_history_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->past_history).'
                  </div>
                </div>';
                }
            }
            
            
    if(strcmp(strtolower($value['setting_name']),'personal_history')=='0')
    {   
        if(!empty($value['setting_value'])) { $personal_history_name =  $value['setting_value']; } else { $personal_history_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->personal_history)){ 
        $personal_history = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$personal_history_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->personal_history).'
          </div>
        </div>';
        }
    }
    
    if(strcmp(strtolower($value['setting_name']),'family_history')=='0')
    {   
        if(!empty($value['setting_value'])) { $family_history_name =  $value['setting_value']; } else { $family_history_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->family_history)){ 
        $family_history = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$family_history_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->family_history).'
          </div>
        </div>';
        }
    }


    if(strcmp(strtolower($value['setting_name']),'birth_history')=='0')
    {   
        if(!empty($value['setting_value'])) { $birth_history_name =  $value['setting_value']; } else { $birth_history_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->birth_history)){ 
        $birth_history = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$birth_history_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->birth_history).'
          </div>
        </div>';
        }
    }

    if(strcmp(strtolower($value['setting_name']),'general_history')=='0')
    {   
        if(!empty($value['setting_value'])) { $general_history_name =  $value['setting_value']; } else { $general_history_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->general_history)){ 
        $general_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$general_history_name.':</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->general_history).'
          </div>
        </div>'.$general_examination_vital;
        }
    }

     if(strcmp(strtolower($value['setting_name']),'systemic_examination')=='0')
            {   
                if(!empty($value['setting_value'])) { $systemic_examination_name =  $value['setting_value']; } else { $systemic_examination_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->systemic_examination)){ 
                $systemic_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$systemic_examination_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->systemic_examination).'
                  </div>
                </div>'.$systemic_examination_vital;
                }
            }

            if(strcmp(strtolower($value['setting_name']),'local_examination')=='0')
            {   
                if(!empty($value['setting_value'])) { $local_examination_name =  $value['setting_value']; } else { $local_examination_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->local_examination)){ 
                $local_examination = '<div style="float:left;width:100%;margin:1em 0 4em;">
                  <div style="font-size:small;line-height:18px;"><b>'.$local_examination_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->local_examination).'
                  </div>
                </div>';
                }
            }
            
            
            if(strcmp(strtolower($value['setting_name']),'specific_findings')=='0')
            {   
                if(!empty($value['setting_value'])) { $specific_findings_name =  $value['setting_value']; } else { $specific_findings_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->specific_findings)){ 
                $specific_findings = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$specific_findings_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->specific_findings).'
                  </div>
                </div>';
                }
            }
            
         if(strcmp(strtolower($value['setting_name']),'diagnosis')=='0')
        {
    
            if(!empty($value['setting_value'])) { $diagnosis_name =  $value['setting_value']; } else { $diagnosis_name =  $value['var_title']; }
    
                if(!empty($booking_data_discharge['discharge_list'][0]->diagnosis)){ 
                $diagnosis.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$diagnosis_name.' :</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->diagnosis).'
                  </div>
                </div>';
                }
        }

            if(strcmp(strtolower($value['setting_name']),'menstrual_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $menstrual_history_name =  $value['setting_value']; } else { $menstrual_history_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->menstrual_history)){ 
                $menstrual_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$menstrual_history_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->menstrual_history).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'obstetric_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $obstetric_history_name =  $value['setting_value']; } else { $obstetric_history_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->obstetric_history)){ 
                $obstetric_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$obstetric_history_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->obstetric_history).'
                  </div>
                </div>';
                }
            }
//surgery_preferred
             if(strcmp(strtolower($value['setting_name']),'surgery_preferred')=='0')
            {   
                if(!empty($value['setting_value'])) { $surgery_preferred_name =  $value['setting_value']; } else { $surgery_preferred_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->surgery_preferred)){ 
                $surgery_preferred = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$surgery_preferred_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->surgery_preferred).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'operation_notes')=='0')
            {   
                if(!empty($value['setting_value'])) { $operation_notes_name =  $value['setting_value']; } else { $operation_notes_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->operation_notes)){ 
                 $operation_notes = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$operation_notes_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->operation_notes).'
                  </div>
                </div>'; 
                }
            }

            if(strcmp(strtolower($value['setting_name']),'treatment_given')=='0')
            {   
                if(!empty($value['setting_value'])) { $treatment_given_name =  $value['setting_value']; } else { $treatment_given_name =  $value['var_title']; }
                if(!empty($booking_data_discharge['discharge_list'][0]->treatment_given)){ 
                $treatment_given = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$treatment_given_name.':</b>
                   '.nl2br($booking_data_discharge['discharge_list'][0]->treatment_given).'
                  </div>
                </div>';
                }
            }

              if(strcmp(strtolower($value['setting_name']),'mlc_fir_no')=='0')
              {
                if(!empty($value['setting_value'])) { $mlc_fir_no_name =  $value['setting_value']; } else { $mlc_fir_no_name =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->mlc_fir_no)){ 
                $mlc_fir_no = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$mlc_fir_no_name.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->mlc_fir_no).'
                </div>
                </div>';
                 } 

              }
                  if(strcmp(strtolower($value['setting_name']),'lama_dama_reasons')=='0')
              {
                if(!empty($value['setting_value'])) { $lama_dama_reason =  $value['setting_value']; } else { $lama_dama_reason =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->lama_dama_reasons)){ 
                $lama_dama_reasons = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$lama_dama_reason.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->lama_dama_reasons).'
                </div>
                </div>';
                 }

              }
                  if(strcmp(strtolower($value['setting_name']),'refered_data')=='0')
              {
                if(!empty($value['setting_value'])) { $refered_data_name =  $value['setting_value']; } else { $refered_data_name =  $value['var_title']; }
               if(!empty($booking_data_discharge['discharge_list'][0]->refered_data)){ 
                $refered_data = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$refered_data_name.':</b><br>
                '.nl2br($booking_data_discharge['discharge_list'][0]->refered_data).'
                </div>
                </div>';
                 }

              }

    
    $b=1;
    if(strcmp(strtolower($value['setting_name']),'vitals')=='0')
    {
        if(!empty($value['setting_value'])) { $vitals_name =  $value['setting_value']; } else { $vitals_name =  $value['var_title']; }
       
        $vitals .= '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$vitals_name.' :</b>';
        $vitals .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
            <tbody>
                <tr>';
            
            foreach($discharge_vital_setting_list as $discharge_vital_labels)
            {
                
                
               
            
           
            
            
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'pulse')=='0' && $discharge_vital_labels['type'] =='vitals')
                { 
                     //echo "inn"; die;
                    
                    if(!empty($discharge_vital_labels['setting_value'])) { $pulse_name = $discharge_vital_labels['setting_value']; } else { $pulse_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_pulse))
                    {
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$pulse_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">
                                            <div style="min-height:20px;text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_pulse).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>'; 
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'chest')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_chest_name = $discharge_vital_labels['setting_value']; } else { $vitals_chest_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_chest))
                    {
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_chest_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">
                                            <div style="min-height:20px;text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_chest).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>'; 
                    }
                }

            
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'bp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $bp_name = $discharge_vital_labels['setting_value']; } else { $bp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_bp))
                    {
                    
                    $vitals .='
                            <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$bp_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_bp).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cvs')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_cvs_name = $discharge_vital_labels['setting_value']; } else { $vitals_cvs_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cvs))
                    {
                    
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_cvs_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">
                                            <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cvs).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'temp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_temp_name = $discharge_vital_labels['setting_value']; } else { $vitals_temp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_temp))
                    {
                    
                    $vitals .='

                    <td align="left" valign="top" width="20%">
                    <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                        <tbody>
                            <tr>
                                <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_temp_name.'</b></th>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_temp).'</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cns')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $cns_name = $discharge_vital_labels['setting_value']; } else { $cns_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cns))
                    {
                    
                    $vitals .='
                    <td align="left" valign="top" width="20%">
                        <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                            <tbody>
                                <tr>
                                    <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$cns_name.'</b></th>
                                </tr>
                                <tr>
                                    <td align="center" valign="top">
                                    <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cns).'</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'p_a')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $p_a_name = $discharge_vital_labels['setting_value']; } else { $p_a_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_p_a))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$p_a_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_p_a).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'r_r')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $r_r_name = $discharge_vital_labels['setting_value']; } else { $r_r_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_r_r))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$r_r_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_r_r).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'saturation')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $saturation_name = $discharge_vital_labels['setting_value']; } else { $saturation_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_saturation))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$saturation_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_saturation).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'pupils')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $pupils_name = $discharge_vital_labels['setting_value']; } else { $pupils_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_pupils))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$pupils_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_pupils).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'pallor')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $pallor_name = $discharge_vital_labels['setting_value']; } else { $pallor_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_pallor))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$pallor_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_pallor).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                 if(strcmp(strtolower($discharge_vital_labels['setting_name']),'icterus')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $icterus_name = $discharge_vital_labels['setting_value']; } else { $icterus_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_icterus))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$icterus_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_icterus).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cyanosis')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $cyanosis_name = $discharge_vital_labels['setting_value']; } else { $cyanosis_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cyanosis))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$cyanosis_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cyanosis).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'clubbing')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $clubbing_name = $discharge_vital_labels['setting_value']; } else { $clubbing_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_clubbing))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$clubbing_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_clubbing).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'edema')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $edema_name = $discharge_vital_labels['setting_value']; } else { $edema_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_edema))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$edema_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_edema).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'lymphadenopathy')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $lymphadenopathy_name = $discharge_vital_labels['setting_value']; } else { $lymphadenopathy_name = $discharge_vital_labels['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_lymphadenopathy))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$lymphadenopathy_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="text-align: center;padding-left: 4px;">'.nl2br($booking_data_discharge['discharge_list'][0]->vitals_lymphadenopathy).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }
                
               
          $b++; 
        if($b=='7')
               {
                   $b=1;
                    $vitals .='</tr>
                    </tbody>
                </table></div>
                        </div>';
                        
                
                 $vitals .= '<div style="float:left;width:100%;margin:1em 0 0;">
                                  <div style="font-size:small;line-height:18px;">';
                                $vitals .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
                                    <tbody>
                                        <tr>';
                
               }
               
               
            
              
        }


         $vitals .='</tr>
                    </tbody>
                </table></div>
                        </div>';
      
    }
      
     
    


    if(strcmp(strtolower($value['setting_name']),'provisional_diagnosis')=='0')
    { 
        if(!empty($value['setting_value'])) { $Diagnosis_name =  $value['setting_value']; } else { $Diagnosis_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->provisional_diagnosis)){ 
        $provisional_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Diagnosis_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->provisional_diagnosis).'
          </div>
        </div>';
        }
    }
        



    if(strcmp(strtolower($value['setting_name']),'final_diagnosis')=='0')
    {
        if(!empty($value['setting_value'])) { $final_diagnosis_name =  $value['setting_value']; } else { $final_diagnosis_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->final_diagnosis)){ 
        $final_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b> '.$final_diagnosis_name.' :</b>'.nl2br($booking_data_discharge['discharge_list'][0]->final_diagnosis).'
          
          </div>
        </div>';
        }
    }    
    


    if(strcmp(strtolower($value['setting_name']),'course_in_hospital')=='0')
    { 
        if(!empty($value['setting_value'])) { $course_in_hospital_name =  $value['setting_value']; } else { $course_in_hospital_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->course_in_hospital)){ 
        $course_in_hospital = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$course_in_hospital_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->course_in_hospital).'
          </div>
        </div>';
        }
    }


    if(strcmp(strtolower($value['setting_name']),'investigation')=='0')
    { 
        if(!empty($value['setting_value'])) { $investigations_name =  $value['setting_value']; } else { $investigations_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->investigations)){ 
        $investigation = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$investigations_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->investigations).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value['setting_name']),'condition_at_discharge_time')=='0')
    { 
        if(!empty($value['setting_value'])) { $discharge_time_condition_name =  $value['setting_value']; } else { $discharge_time_condition_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->discharge_time_condition)){ 
        $condition_at_discharge_time = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$discharge_time_condition_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->discharge_time_condition).'
          </div>
        </div>';
        }
    }

    if(strcmp(strtolower($value['setting_name']),'advise_on_discharge')=='0')
    { 
        if(!empty($value['setting_value'])) { $discharge_time_condition_name =  $value['setting_value']; } else { $discharge_time_condition_name =  $value['var_title']; }
        if(!empty($booking_data_discharge['discharge_list'][0]->discharge_advice)){ 
        $advise_on_aischarge = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$discharge_time_condition_name.' :</b>
           '.nl2br($booking_data_discharge['discharge_list'][0]->discharge_advice).'
          </div>
        </div>';
        }
    }
    
   
   if(strcmp(strtolower($value['setting_name']),'review_time_and_date')=='0')
   {
       if(!empty($value['setting_value'])) 
       { 
            $review_time_date_name =  $value['setting_value']; } else { $review_time_date_name =  $value['var_title']; 
       }
       $time='';
       if(!empty($booking_data_discharge['discharge_list'][0]->review_time_date) && $booking_data_discharge['discharge_list'][0]->review_time_date!='00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='00:00:00 00:00:00')
       {
            $time = $booking_data_discharge['discharge_list'][0]->review_time;
       }
       if(!empty($booking_data_discharge['discharge_list'][0]->review_time) && $booking_data_discharge['discharge_list'][0]->review_time!='0000-00-00 00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='1970:01:01 00:00:00' && $booking_data_discharge['discharge_list'][0]->review_time_date!='1970-01-01')
       {
        $review_time_and_date .= '<div style="float:left;width:100%;margin:1em 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$review_time_date_name.' :</b>
            '.date('d-m-Y',strtotime($booking_data_discharge['discharge_list'][0]->review_time_date)).' '.$time.'
          </div>
        </div>';
       }
    }

    //seprated vitals
               
               //echo "<pre>"; print_r($value); 
                if(strcmp(strtolower($value['setting_name']),'pulse')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_pulse)){ 
                    $vitals_pulse.= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$pulse_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_pulse).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'chest')=='0' && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $vitals_chest_name =  $value['setting_value']; } else { $vitals_chest_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_chest)){ 
                    $vitals_chest .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_chest_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_chest).'
                      </div>
                    </div>';
                    }
                }
               
                if(strcmp(strtolower($value['setting_name']),'bp')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $bp_name =  $value['setting_value']; } else { $bp_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_bp)){ 
                    $vitals_bp .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$bp_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_bp).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'cvs')=='0'  && $value['type'] =='vitals')
                { //echo $booking_data_discharge['discharge_list'][0]->vitals_cvs.''. strtolower($value['setting_name']);

                    if(!empty($value['setting_value'])) { $vitals_cvs_name =  $value['setting_value']; } else { $vitals_cvs_name =  $value['var_title']; }

                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cvs)){  


                    $vitals_cvs .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_cvs_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cvs).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'temp')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $vitals_temp_name =  $value['setting_value']; } else { $vitals_temp_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_temp)){ 
                    $vitals_temp .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_temp_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_temp).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'cns')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $cns_name =  $value['setting_value']; } else { $cns_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_cns)){ 
                    $vitals_cns .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$cns_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_cns).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'p_a')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $p_a_name =  $value['setting_value']; } else { $p_a_name =  $value['var_title']; }
                    if(!empty($booking_data_discharge['discharge_list'][0]->vitals_p_a)){ 
                    $vitals_p_a .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$p_a_name.' :</b>
                       '.nl2br($booking_data_discharge['discharge_list'][0]->vitals_p_a).'
                      </div>
                    </div>';
                    }
                }

    }
    
    
    $remarks='';
        if(!empty($booking_data_discharge['discharge_list'][0]->remarks)){ 
        $remarks = '<div style="float:left;width:100%;margin:1em 0 4em;">
          <div style="font-size:small;line-height:18px;">
           '.nl2br($booking_data_discharge['discharge_list'][0]->remarks).'
          </div>
        </div>';
        }
    
    
    //Death Date Time :
    if($summary_info['discharge_list'][0]->summery_type=='5')
    {
     $death_time_and_date='';
               $deathtime='';
               if(!empty($booking_data_discharge['discharge_list'][0]->death_date) && $booking_data_discharge['discharge_list'][0]->death_date!='00:00:00' && $booking_data_discharge['discharge_list'][0]->death_date!='00:00:00 00:00:00')
               {
                    $deathtime = $booking_data_discharge['discharge_list'][0]->death_time;
               }
               if(!empty($booking_data_discharge['discharge_list'][0]->death_time) && $booking_data_discharge['discharge_list'][0]->death_time!='0000-00-00 00:00:00' && $booking_data_discharge['discharge_list'][0]->death_date!='1970:01:01 00:00:00' && $booking_data_discharge['discharge_list'][0]->death_date!='1970-01-01')
               {
                $death_time_and_date .= '<div style="float:left;width:100%;margin:1em 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>Death Date Time :</b>
                    '.date('d-m-Y',strtotime($booking_data_discharge['discharge_list'][0]->death_date)).' '.$time.'
                  </div>
                </div>';
               }
     
     $middle_replace_part = str_replace("{death_date_time}",$death_time_and_date,$middle_replace_part);       
    }
    else
    {
        $middle_replace_part = str_replace("{death_date_time}",'',$middle_replace_part);
        $middle_replace_part = str_replace("Death Date Time :",'',$middle_replace_part);
    }

    
    
    
    $middle_replace_part = str_replace("{chief_complaints}",$chief_complaints,$middle_replace_part);
    $middle_replace_part = str_replace("{h_o_presenting_illness}",$h_o_presenting_illness,$middle_replace_part);
    $middle_replace_part = str_replace("{on_examination}",$on_examination,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals}",$vitals,$middle_replace_part);
    
    
    $middle_replace_part = str_replace("{provisional_diagnosis}",$provisional_diagnosis,$middle_replace_part);
    $middle_replace_part = str_replace("{final_diagnosis}",$final_diagnosis,$middle_replace_part);
    $middle_replace_part = str_replace("{course_in_hospital}",$course_in_hospital,$middle_replace_part);
    $middle_replace_part = str_replace("{investigation}",$investigation,$middle_replace_part);
    $middle_replace_part = str_replace("{condition_at_discharge_time}",$condition_at_discharge_time,$middle_replace_part);
    $middle_replace_part = str_replace("{advise_on_discharge}",$advise_on_aischarge,$middle_replace_part);
    $middle_replace_part = str_replace("{review_time_and_date}",$review_time_and_date,$middle_replace_part);

    //
    $middle_replace_part = str_replace("{h_o_past}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{mlc_fir_no}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{ho_any_allergy}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{medicine_prescribed}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{lama_dama_reasons}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{refered_data}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{icd_code}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{admission_reason}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{family_history}",$family_history,$middle_replace_part);
    $middle_replace_part = str_replace("{alcohol_history}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{procedure_performedand}",'',$middle_replace_part);
     $middle_replace_part = str_replace("{nutritional_advice}",'',$middle_replace_part);
    $middle_replace_part = str_replace("{consultants_name}",'',$middle_replace_part);

    //

    $middle_replace_part = str_replace("{vitals_pulse}",$vitals_pulse,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_chest}",$vitals_chest,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_bp}",$vitals_bp,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_cvs}",$vitals_cvs,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_temp}",$vitals_temp,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_cns}",$vitals_cns,$middle_replace_part);
    $middle_replace_part = str_replace("{vitals_p_a}",$vitals_p_a,$middle_replace_part);
    
    
    
    //investigation_list
    
    
     $discharge_master_detail= $this->ipd_discharge_summary->discharge_master_detail_by_field_print($summary_id);
       // print_r($discharge_master_detail); exit;
        $total_values=array();
        for($i=0;$i<count($discharge_master_detail);$i++) 
        {
            $total_values[]= $discharge_master_detail[$i]->field_value.'__'.$discharge_master_detail[$i]->discharge_lable.'__'.$discharge_master_detail[$i]->field_id.'__'.$discharge_master_detail[$i]->type.'__'.$discharge_master_detail[$i]->discharge_short_code;
        }
      
       // $field_name = $total_values;
        
        if(!empty($total_values))
        { 
            $data_discharge='';
            foreach ($total_values as $field_names) 
            {
               //echo $tot_values[4];
                $tot_values= explode('__',$field_names);
                if(!empty($tot_values[0]))
                {
                     $data_discharge = '<div style="float:left;width:100%;margin:1em 0 0;">
                          <div style="font-size:small;line-height:18px;">
                            <b>'.ucfirst($tot_values[1]).' :</b>
                           '.nl2br($tot_values[0]).'
                          </div>
                        </div>';
                    $middle_replace_part = str_replace($tot_values[4],$data_discharge,$middle_replace_part);
                }
                else
                {
                    $middle_replace_part = str_replace($tot_values[4],'',$middle_replace_part); 
                }
               
                        

            } 
        }
    

        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
      // echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
        if(!empty($summary_info['medicine_list'][0]->medicine_name) && $summary_info['medicine_list'][0]->medicine_name!=""){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:11px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="10" style="padding:4px;" valign="top">MEDICINE DETAILS:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_list'] as $prescription_presc)
                     { 
                         $medicine_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_data .='</tr></tbody>
                  </table>';

                
        }
        else{
          $medicine_data='';
        }
  
      
        /************ Medicine *************/

          /************ Investigation *************/
          $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($discharge_summary_id);

        if(!empty($summary_info['investigation_test_list'][0]->test_name) && $summary_info['investigation_test_list'][0]->test_name!=""){
            $test_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:50%;border-color:#ccc;border-collapse:collapse;font:11px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="3" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Test name</th>
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" colspan="2" style="padding:4px;" valign="top">Result</th>
                      ';

              
                       
                       $i=0;
                       
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                         $inv_date='';
                        if($discharge_test_list->test_date!='1970-01-01')
                        {
                            $inv_date = $discharge_test_list->test_date;
                        }
                    
                         $test_data .='<tr>';

                  $test_data .='<tr style="border-bottom:1px dashed #ccc;">
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$discharge_test_list->test_name.'</td>
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$inv_date.'</td>
                        <td align="left" colspan="2" style="padding:4px;" valign="top">'.$discharge_test_list->result.'</td>
                      </tr>';

                      $i++; } 

             $test_data .='</tr></tbody>
                  </table>';

                 
        }
        
        
        
        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting();
      // echo "<pre>"; print_r( $prescription_medicine_tab_setting);die();
      if(!empty($summary_info['medicine_list']))
      {
        if(!empty($prescription_medicine_tab_setting)){
          $medicine_data='';
            $medicine_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:11px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="<?php echo count($prescription_medicine_tab_setting);  ?>" style="padding:4px;" valign="top">Medicine Prescribed:</th>
                      </tr>

                      <tr style="border-bottom:1px dashed #ccc;">';

                     
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                      if(!empty($med_value->setting_value)) { 

                        $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->setting_value.' </th>';

                         } else { 
                          $medicine_data .='<th  align="left" colspan="2" style="padding:4px;" valign="top">  '.$med_value->var_title.' </th>';

                        }
                        
                      }

                
                     $medicine_data .='</tr>';
                       
                       $i=0;
                     foreach($summary_info['medicine_list'] as $prescription_presc)
                     { 
                         $medicine_data .='<tr>';

                    foreach($prescription_medicine_tab_setting as $tab_value)
                    { 

                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'. $prescription_presc->medicine_name.'</td>';
                        
                        }
                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                       
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_brand.'</td>';
                        
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_salt.'</td>';
                       
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_type.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_dose.'</td>';
                        
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_duration.'</td>';
                        
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        
                        $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_frequency.'</td>';
                      
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                       
                       $medicine_data .='<td align="left" colspan="2" style="padding:4px;" valign="top">'.$prescription_presc->medicine_advice.'</td>';
                       } 

           
                     } $i++; } 

             $medicine_data .='</tr></tbody>
                  </table>';

                  $medicine_list=$medicine_data;
        }
        else{
          $medicine_list='';
        }
    }
    else{
          $medicine_list='';
        }
  
    
       $middle_replace_part = str_replace("{medicine_detail}",$medicine_list,$middle_replace_part);
        /************ Investigation *************/
        
        
         $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($summary_id);

        //if(!empty($summary_info['investigation_test_list'][0]->test_name) && $summary_info['investigation_test_list'][0]->test_name!=""){
            $investigation_list_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:11px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="3" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left"  style="padding:4px;" valign="top">Test name</th>
                      <th  align="left"  style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" style="padding:4px;" valign="top">Result</th>
                      ';

              
                       
                       $i=0;
                       
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                    
                         $investigation_list_data .='<tr>';

                  $investigation_list_data .='<tr style="border-bottom:1px dashed #ccc;">
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->test_name.'</td>
                        <td align="left" style="padding:4px;" valign="top">'.$discharge_test_list->test_date.'</td>
                        <td align="left"  style="padding:4px;" valign="top">'.$discharge_test_list->result.'</td>
                      </tr>';

                      $i++; } 

             $investigation_list_data .='</tr></tbody>
                  </table>';

                 // $data['test_list']=$test_data;


    $middle_replace_part = str_replace("{medicine_prescribed}",$medicine_data,$middle_replace_part);
      $middle_replace_part = str_replace("{investigation}",$test_data,$middle_replace_part);
      
      
      
      
      ////////investigation 
      
      
      /*$discharge_setting = get_setting_value('IPD_DISCHARGE_INVESTIGATION_TEST'); 
          
          if($discharge_setting=='1')
          {*/

            $ipd_test_data = $this->ipd_discharge_summary->get_ipd_pathology_test($ipd_id);
            $booking_id = $ipd_test_data['id'];

              ///

              if(!empty($booking_id))
              {
                  
                  
                            $this->load->model('test/test_model','test');
                      $get_by_id_data = $this->test->patient_booking_list($ipd_id); 
                		//echo "<pre>"; print_r($get_by_id_data); die;
                
                    $common_profile =array();
                    if(!empty($get_by_id_data))
                    {
                      
                      $common_profile_date_pro =array();
                      $common_profile_bookIDS=array();
                      $pi = 1;
                      foreach($get_by_id_data as $value) 
                      {
                        $booked_profile_data = $this->ipd_discharge_summary->get_booking_profile_print_name($value->id); 
                        //echo $this->db->last_query(); exit;
                        //echo "<pre>"; print_r($booked_profile_data); exit;
                          if(!empty($booked_profile_data))
                          { 
                              
                                
                                foreach($booked_profile_data as $booked_profl)
                                {
                                  if(!in_array($booked_profl->profile_name,$common_profile['profile_name']))
                                  {
                                      $common_profile['profile_name'][] = $booked_profl->profile_name; 
                                      $common_profile['profile_id'][] = $booked_profl->profile_id;
                                      $common_profile['commons_profile'][] = "Yes";
                                      
                                      $common_profile_date_pro[$pi]['profile_name'] = $booked_profl->profile_name; 
                                      $common_profile_date_pro[$pi]['profile_id'] = $booked_profl->profile_id;
                                      $common_profile_date_pro[$pi]['booking_id'] =$value->id;
                                      $common_profile_bookIDS['booking_id'][] =$value->id;
                                      
                                     $pi++;  
                                      
                                  }
                                  else
                                  { 
                                
                                      $common_profile['profile_name'][] = $booked_profl->profile_name; 
                                      $common_profile['profile_id'][] = $booked_profl->profile_id;
                                      $common_profile['commons_profile'][] = "";
                                      
                                      $common_profile_date_pro[$pi]['profile_name'] = $booked_profl->profile_name; 
                                      $common_profile_date_pro[$pi]['profile_id'] = $booked_profl->profile_id;
                                      $common_profile_date_pro[$pi]['booking_id'] =$value->id;
                                      $common_profile_bookIDS['booking_id'][] =$value->id;
                                      
                                  }
                                  
                                }
                          }
                        }
                      }
                
                //echo "<pre>"; print_r($common_profile); exit;
                   /* $common_profile_name_list = array_unique($common_profile['profile_name']);
                    $common_profile_id_list = array_unique($common_profile['profile_id']);*/
                     
                     $common_profile_name_list = $common_profile['profile_name'];
                    $common_profile_id_list = $common_profile['profile_id'];
                    
                    $commons_profile_check = $common_profile['commons_profile'];
                    
                    
                    // echo "<pre>"; print_r($common_profile_name_list); exit;
                     
                      if(!empty($get_by_id_data))
                      {
                        $common_profile_date =array();
                        foreach($get_by_id_data as $value) 
                        {
                          if(!empty($value->booking_date) && $value->booking_date!='1970-01-01')
                          {
                            $date_key = strtotime($value->booking_date); 
                              //if(!isset($common_profile_date[$date_key])){ 
                                  $common_profile_date[$value->id] = $value->booking_date;
                              //}
                          }
                              
                          
                        }
                      }
                     
                      //echo "<pre>"; print_r($common_profile_bookIDS['booking_id']); exit;
                      
                      $common_booking_ids=array();
                      if(!empty($common_profile_bookIDS['booking_id']))
                      {
                        $n=0;
                        foreach ($common_profile_bookIDS['booking_id'] as $key => $value) 
                        {
                          if(!in_array($value, $common_booking_ids))
                          {
                            $common_booking_ids[$n] = $value; 
                            $n++;  
                          }
                          
                        }
                       }
                    //echo "<pre>"; print_r($common_booking_ids); exit;
                      
                      //echo "<pre>"; print_r($common_profile); exit;
                      //profile test listing start
                      $test_profiles='';
                      if(!empty($common_profile))
                      {
                          //commons_profile
                          $row=2;
                       
                
                           $test_report_data .='
                    <table class="table table-bordered table-striped" id="test_name_table" style="margin-left:0px;font-size:11px;" border="1">
                     		<tr>
                     			<th colspan="2">Investigation</th>
                                  <th align="left" style="padding:4px;" colspan="'.count($common_profile_date).'">Report</th>
                      		</tr>
                      			<tr>
                                  <th align="left" style="padding:4px;width:100px;">S. No.</th>
                                  <th align="left" style="padding:4px;">Investigations</th>';       
                                  foreach ($common_profile_date as $cmvalue) 
                                  {
                                      $test_report_data .='<th>'.date('d-m-Y',strtotime($cmvalue)).'</th>';  
                                  }
                                  $ty=0;
                                  foreach ($common_profile_name_list as $key =>$value) 
                                  {
                                      
                                      $progilr_id = $common_profile_id_list[$ty];
                                      ///common condition
                                      if(!empty($commons_profile_check[$ty]))
                                      {
                                      
                                        $test_report_data .='<thead><tr><th colspan="2">'.$value.'</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  
                
                                      if(!empty($common_profile_date_pro))
                                      {
                                        $k=0;
                                        
                                         //echo "<pre>";print_r($common_profile_date_pro); exit;
                                         foreach ($common_profile_date_pro as $pro_value) 
                                         {
                                             
                                            if($value==$pro_value['profile_name'])
                                            {
                                             
                                                $test_BookingID = $common_profile_bookIDS['booking_id'][$ty];
                                               
                                                $test_profiles =  $this->ipd_discharge_summary->report_test_list($test_BookingID,'','',$progilr_id);
                                                
                                               
                                               if(!empty($test_profiles))
                                               {
                                                $n=1;
                                                foreach ($test_profiles as $value_p) 
                                                {
                                                      $test_report_data .='<tr>
                                                        <td>'.$n.'</td>
                                                        <td>'.$value_p->test_name.'</td>';
                                                        $s=0;
                                                        foreach ($common_profile_date as $b_key=>$valued) 
                                                        {
                                                          //$test_BookingID = $common_booking_ids[$s];
                                                          
                                                          $test_BookingID = $b_key;
                                                          $get_result_on_date = $this->test->get_test_result_by_date($value_p->test_id,$value_p->profile_id,$valued,$test_BookingID);
                                                          //echo "<pre>";print_r($get_result_on_date); 
                                                          $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                                          $s++;
                
                                                        }
                                                        
                                                        $test_report_data .='</tr>';
                                                        $n++;
                                                }
                                               }
                                            }
                                            $k++;       
                                         }
                                      }     
                                      }
                                      //end of profile name
                                  
                                      $ty++;
                                  }
                      }
                
                    
                    //Profile test listing end 
                    
                     //Individual test report listing 
                    if(!empty($get_by_id_data))
                	{
                	    
                	    
                	  
                	    
                            $my_test=array();
                            $ko=1;
                			foreach($get_by_id_data as $value) 
                			{
                			    $datatest_list = $this->ipd_discharge_summary->report_test_list($value->id,'','0');
                                //echo "<pre>"; print_r($datatest_list);
                                if(!empty($datatest_list))
                                {
                        
                                  //echo "<pre>";  print_r($get_by_id_data);
                                  //print_r($booking_id)
                        
                                  foreach($datatest_list as $mytest_list) 
                          		  {
                          		     
                          		      if(!empty($my_testp) && array_search($mytest_list->test_id, array_column($my_testp, 'test_id')) !== False) {
                          		          //echo "INN";
                          		          $my_testp[$ko]['test_name'] = $mytest_list->test_name;
                                        $my_testp[$ko]['test_id'] = $mytest_list->test_id;
                          		          $my_testp[$ko]['booking_id'] = $mytest_list->booking_id;
                          		          $my_testp[$ko]['common_test'] = "Yes";
                          		         
                          		      }
                          		      else
                          		      {
                                        $my_testp[$ko]['test_name'] = $mytest_list->test_name;
                                        $my_testp[$ko]['test_id'] = $mytest_list->test_id;
                                        $my_testp[$ko]['booking_id'] = $mytest_list->booking_id;
                                        $my_testp[$ko]['common_test'] = "";
                                        
                                        
                          		      }
                          		      $ko++;
                                        
                          		  }
                                }
                			}
                	}
                	
                	
                	 //echo "<pre>"; print_r($common_booking_ids);  die; 
                	 
                	 if($my_testp)
                	 {
                	      $test_report_data .='<thead><tr><th colspan="3">Test</th><th colspan="'.count($common_profile_date).'"></th></tr></thead>';  
                	     $po=1;
                	     foreach($my_testp as $row)
                	     {
                	        //print_r($row); 
                	            if(empty($row['common_test']))
                	            {
                	                    $test_report_data .='<tr>';
                                            $test_report_data .='<td style="padding:4px;">'.$po.'</td>';
                                            $test_report_data .='<td style="padding:4px;">'.$row['test_name'].'</td>';
                                            
                                            
                                            $s=0;
                                            
                                            foreach ($common_profile_date as $key=>$valued) 
                                            {
                                               
                                             
                                              $get_result_on_date = $this->test->get_test_result_by_date($row['test_id'],'',$valued,$key);
                                              $test_report_data .='<td>'.$get_result_on_date['result'].'</td>';  
                                              $s++;
                                
                                            }
                                            
                                            
                                            
                                             $test_report_data .='</tr>';
                                            $po++;
                	            }
                	     }
                	 }
                	 //End of individual test report listing 
                	 
                	  $test_report_data .='</tbody></table>'; 
              }


//$test_report_data .= $investigation_list_data;

              
      


          $discharge_summary_test_list= $this->ipd_discharge_summary->get_discharge_test($summary_id);
          //echo "<pre>"; print_r($discharge_summary_test_list); die;
        if(!empty($discharge_summary_test_list))
        {
        
                $test_report_data .='<table border="1" cellpadding="0" cellspacing="0" style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;">
                    <tbody>
                      <tr>
                        <th align="left" colspan="4" style="padding:4px;" valign="top">INVESTIGATION DETAILS:</th>
                      </tr>
                      <tr style="border-bottom:1px dashed #ccc;">
                      <th  align="left"  style="padding:4px;" valign="top">Test name</th>
                      <th  align="left"  style="padding:4px;" valign="top">Test Date</th>
                      <th  align="left" style="padding:4px;" valign="top">Result</th>';

              
                       
                       $i=1;
                     
                     foreach($discharge_summary_test_list as $discharge_test_list)
                     { 
                    
                         $test_report_data .='<tr>';

                            $test_report_data .='<td style="padding:4px;">'.$discharge_test_list->test_name.'</td>
                            <td style="padding:4px;">'.$discharge_test_list->test_date.'</td>
                            <td style="padding:4px;">'.$discharge_test_list->result.'</td>
                          </tr>';
    
                          $i++; 
                         
                     } 

            
                  
                 $test_report_data .='</tbody></table>';
        }

                 // $test_list=$test_data;
//$test_list .=$test_data;

//$test_report_data .= $investigation_list_data;
//$test_report_data .= $test_data;
  $test_list =$test_report_data;        
      //end of investigation 
      
       //$middle_replace_part = str_replace("{investigation_list}",$investigation_list_data,$middle_replace_part);
        
        
        if($test_list!=""){ 
            $middle_replace_part = str_replace("{investigation_list}",$test_list,$middle_replace_part);
        }
        else{
             $middle_replace_part = str_replace("{investigation_list}",'',$middle_replace_part);
        }


       /*$middle_replace_part = str_replace("{past_history}", '', $middle_replace_part);
        $middle_replace_part = str_replace("{menstrual_history}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{obstetric_history}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{surgery_preferred}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{operation_notes}", '', $middle_replace_part); 
        $middle_replace_part = str_replace("{treatment_given}", '', $middle_replace_part);*/ 
        
        //echo $diagnosis; die;
        $middle_replace_part = str_replace("{personal_history}",$personal_history,$middle_replace_part);
        $middle_replace_part = str_replace("{birth_history}",$birth_history,$middle_replace_part);
        $middle_replace_part = str_replace("{general_examination}",$general_examination,$middle_replace_part);
        $middle_replace_part = str_replace("{systemic_examination}",$systemic_examination,$middle_replace_part);
        $middle_replace_part = str_replace("{local_examination}",$local_examination,$middle_replace_part);
        $middle_replace_part = str_replace("{diagnosis}",$diagnosis,$middle_replace_part);
        $middle_replace_part = str_replace("{specific_findings}",$specific_findings,$middle_replace_part);
        $middle_replace_part = str_replace("{remarks}",$remarks,$middle_replace_part);
        $middle_replace_part = str_replace("{summary_type}",ucfirst($sumtype),$middle_replace_part);
        
        $middle_replace_part = str_replace("{past_history}",$past_history,$middle_replace_part);
        $middle_replace_part = str_replace("{obstetric_history}",$obstetric_history,$middle_replace_part);
        $middle_replace_part = str_replace("{menstrual_history}",$menstrual_history,$middle_replace_part);
        //echo $operation_notes; exit;
        $middle_replace_part = str_replace("{surgery_preferred}",$surgery_preferred,$middle_replace_part);
        $middle_replace_part = str_replace("{operation_notes}",$operation_notes,$middle_replace_part);
        $middle_replace_part = str_replace("{treatment_given}",$treatment_given,$middle_replace_part);
        
        //operation_notes
        
        $middle_replace_part = str_replace("{medicine_administered_detail}", '', $middle_replace_part);
         $middle_replace_part = str_replace("{medicine_detail}", '', $middle_replace_part); 
         
          // $middle_replace_part = str_replace("{created_fields}",$data_discharge,$middle_replace_part); 
           
         $middle_replace_part = str_replace("{signature}", $doctor_name,$middle_replace_part); 

      
        //echo $middle_replace_part; die;
        //echo "<pre>"; print_r($report_templ_part->page_footer);die;
        $footer_data_part = $report_templ_part->page_footer;

   // $footer_data_part=str_replace("{doctor_name}",$doctor_name,$footer_data_part);
   // $footer_data_part=str_replace("{signature}",$signature,$footer_data_part);
 // echo $middle_replace_part;die;

        ////////////////////

      $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 

    
   
     if($type=='Download')
    {
        if($template_format->header_pdf==1)
        {
           $page_header = $report_templ_part->page_header;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$report_templ_part->page_header.'</div>';
        }
        
         $page_header = str_replace("{summary_type}",$sumtype,$page_header);

        if($template_format->details_pdf==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_format->middle_pdf==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_format->footer_pdf==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
        }
       $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part);
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf'; 
        $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
        // $this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "F");
        
    }
    else 
    { 

    // echo $middle_replace_part;die;
        
        if($template_format->header_print==1)
        {
           $page_header = $report_templ_part->page_header;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$report_templ_part->page_header.'</div><br></br>';
        }
        
         $page_header = str_replace("{summary_type}",$sumtype,$page_header);

        if($template_format->details_print==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_format->middle_print==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_format->footer_print==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
           $footer_data_part = '<p style="height:'.$template_format->pixel_value.'px;"></p>'; 
            $this->m_pdf->pdf->defaultfooterline = 0;
        }

        

        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part); 
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
    }  
  
  
      
    }

}
?>