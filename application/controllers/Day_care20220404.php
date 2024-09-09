<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Day_care extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();  
        auth_users();  
        $this->load->model('day_care/day_care_model','daycare');
        $this->load->library('form_validation');
    }

  public function index()
  {

        unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        //print_r($users_data);
        $this->session->unset_userdata('day_care_search');
        $this->session->unset_userdata('opd_particular_billing');
        $this->session->unset_userdata('opd_particular_payment');
        
        $data['page_title'] = 'Day Care Booking List';
        // Default Search Setting
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
        $data['form_data'] = array('patient_name'=>'','mobile_no'=>'','booking_code'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date); 
        $this->load->view('day_care/list',$data);
  }

    public function ajax_list()
    {   
        unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');

        $list = $this->daycare->get_datatables();  

//print_r($list); die;

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test) 
        {
            $no++;
            $row = array(); 
            $prescription_count = $this->daycare->get_prescription_count($test->id);
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            } 
            if($test->booking_status==0)
            {
                $booking_status = '<font color="red">Pending</font>';
            }   
            elseif($test->booking_status==1){
                $booking_status = '<font color="green">Confirm</font>';
            }                 
            elseif($test->booking_status==2){
                $booking_status = '<font color="blue">Attended</font>';
            } 
            
            //$attended_doctor_name =;
           // $attended_doctor_name = get_doctor_name($test->attended_doctor);

            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$test->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test->id.'">'; 
             }else{
               $row[]='';
             }
             $row[] = $test->patient_reg_no;
            $row[] = $test->booking_code;
            
            $row[] = $test->patient_name;
            
            if($test->appointment_date =='0000-00-00')
            {
              $row[] =''; 
            }
            else
            {
              $row[] = date('d-m-Y',strtotime($test->appointment_date));  
            }
            if(!empty($test->doctor_hospital_name))
            $row[] = $test->doctor_hospital_name;
            else 
            $row[]=""; 
            $row[] = date('d-m-Y',strtotime($test->booking_date));
            //$row[] = $test->total_amount;
            $row[] = $booking_status;
            $row[] = $test->mobile_no;
            $row[] = $test->gender;
            $row[] = $test->address;
            $row[] = $test->father_husband_simulation." ".$test->father_husband;
            $row[] = $test->patient_email;
            $row[] = $test->insurance_type;
            $row[] = $test->insurance_company;

            $row[] = $test->patient_source;
            $row[] = $test->disease;
            $row[] = $test->doctor_hospital_name;
            $row[] = $test->specialization;

            if(!empty($test->doctor_name))
            $row[] = "Dr. ".$test->doctor_name;
            else 
            $row[]="";  

            $row[] = date('h:i A', strtotime($test->booking_time)); 

             if(strtotime($test->validity_date)>0) 
                $row[] = date('d-m-Y', strtotime($test->validity_date)); 
            else 
              $row[]="";
            //$row[] = $test->validity_date;
            $row[] = $test->title;

            if(strtotime($test->next_app_date)>0) 
                $row[] = date('d-m-Y', strtotime($test->next_app_date)); 
            else 
              $row[]="";

            $row[] = number_format($test->total_amount,2);
            $row[] = number_format($test->net_amount,2);
            $row[] = number_format($test->paid_amount + $test->daycare_paid_amount,2);
            $row[] = number_format($test->discount,2);
            $row[] = $test->policy_no;

             //Action button /////
            $btn_confirm="";
            $btn_edit = ""; 
            $btn_delete = ""; 
            $btn_prescription = ""; 
            $blank_btn_print =''; 
              
             if($users_data['parent_id'] == $test->branch_id)
             {
              if($test->booking_status!=1)
              {
               if(in_array('531',$users_data['permission']['action'])){
                    $btn_confirm = ' <a class="btn-custom" onclick="return confirm_booking('.$test->id.');" title="Confirm Booking"><i class="fa fa-pencil"></i> Confirm </a>';
               }
              }
             
             if(in_array('524',$users_data['permission']['action'])){
                $btn_edit = ' <a class="btn-custom" href="'.base_url("day_care/edit_booking/".$test->id).'" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>';
              }
              
              
              if(in_array('525',$users_data['permission']['action'])){
                    $btn_delete = ' <a class="btn-custom" onClick="return delete_opd_booking('.$test->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
               }
        }




       //  $btn_prescription='';
       //  if($test->booking_type==1)  // 0 for normal and 1 for Eye
       //  {
       //      if(in_array('1418',$users_data['permission']['action']))
       //      {
       //          $print_url_eye = "'".base_url('prescription/print_blank_prescriptions/'.$test->id.'/'.$test->branch_id)."'";  
       //          //$btn_prescription .= '<div class="btn-ipd">';
       //          $btn_prescription .= '<li><a  href="'.base_url("eye/add_prescription/".$test->id).'" title="Add Prescription"><i class="fa fa-eye"></i> Add Eye Prescription</a></li>';         
       //      }
            
       //      if(in_array('1418',$users_data['permission']['action']))
       //      {
       //          $btn_prescription .= '<li><a  href="'.base_url("eye/add_eye_prescription/test/".$test->id).'" title="Add Prescription"><i class="fa fa-eye"></i> Add New Eye Prescription</a></li>';
       //          if($test->dilate_status==0)
       //          {
       //            $btn_prescription .= '<li><a href="javascript:void(0)"  onclick="dilated('.$test->id.');" style="color:orange!important;"><i class="fa fa-hand-o-right"></i> Dilate</a></li>';
       //          }
       //          else if($test->dilate_status==1){
       //             $btn_prescription .= '<li><a href="javascript:void(0)" style="color:red!important;"><i class="fa fa-hand-o-right"></i> D</a></li>';
       //          }
       //          else if($test->dilate_status==2){
       //             $btn_prescription .= '<li><a href="javascript:void(0)" style="color:green!important;"><i class="fa fa-hand-o-right"></i>Dilated</a></li>';
       //          }              
       //      }


       //      if(in_array('1419',$users_data['permission']['action']))
       //      {
       //          $print_url_eye = "'".base_url('eye/add_prescription/print_blank_prescriptions/'.$test->id.'/'.$test->branch_id)."'";  
       //         // $btn_prescription .= '<div class="btn-ipd">';
       //          $btn_prescription .= '<li><a  href="javascript:void(0)" onClick="return print_window_page('.$print_url_eye.')" title="Print" ><i class="fa fa-eye"></i
       //          > Blank Eye Prescription  </a></li>';
       //          //$btn_prescription .='</div>';                
       //      }
             
       //      if(in_array('1441',$users_data['permission']['action']))
       //      {
       //        //$btn_prescription .= '<div class="btn-ipd">';
       //        $btn_prescription .= '<li><a  href="'.base_url("eye/biometric_details/add/".$test->id).'" title="Biometric Details"><i class="fa fa-eye"></i> Biometric Test Form</a></li>';
       //        //$btn_prescription .='</div>';                
       //      }
       //  }
       // elseif($test->booking_type==2) /* for pedic*/
       //  {
            
            
            
       //      $prescription_count_pedic = $this->daycare->get_prescription_count_for_pedic($test->id);
       //      if($prescription_count_pedic<1)
       //      {
       //      if(in_array('2052',$users_data['permission']['action']))
       //      {
          
       //      $btn_prescription .= '<li><a  href="'.base_url("pediatrician/add_prescription/prescription/".$test->id).'" title="Add Prescription"><i class="fa fa-pencil"></i> Add Pediatrician Prescription</a></li>';
                          
       //      }
       //     }
       //     else
       //     {
       //        $btn_prescription.='';
       //     }

       //          if(in_array('2053',$users_data['permission']['action']))
       //      {
       //          $print_url_pedic = "'".base_url('pediatrician/add_prescription/print_blank_prescriptions/'.$test->id.'/'.$test->branch_id)."'";  
             
       //          $btn_prescription .= '<li><a  href="javascript:void(0)" onClick="return print_window_page('.$print_url_pedic.')" title="Print" ><i class="fa fa-pencil"></i
       //          > Blank Pediatrician Prescription  </a></li>';
                         
       //      }


       //    if(in_array('1629',$users_data['permission']['action']))
       //      {
                 
              
       //          $btn_prescription .= '<li><a  href="'.base_url("pediatrician/pediatrician_prescription/add_growth_prescription/".$test->id).'" title="Add Growth Measurement"><i class="fa fa-eye"></i> Growth Measurement</a></li>';
                           
       //      }
            
       //      if(in_array('1630',$users_data['permission']['action']))
       //      {

       //          $btn_prescription .= '<li><a href="'.base_url("pediatrician/pediatrician_prescription/add_vaccination_prescription/".$test->id.'/'.$test->dob.'/'.$test->patient_id).'" title="Add Vaccination Prescription"><i class="fa fa-eye"></i> Vaccination</a></li>';
                            
       //      }
          
          

       //    } 
       //  elseif($test->booking_type==3) 
       //  {
       //     $prescription_count_dental = $this->daycare->get_prescription_count_for_dental($test->id);
       //    if($prescription_count_dental<1)
       //    {
       //      if(in_array('1918',$users_data['permission']['action']))
       //      {
       //         $btn_prescription .= '<li><a  href="'.base_url("dental/dental_prescription/add_dental_prescription/".$test->id).'" title="Add Dental Prescription"><i class="fa fa-eye"></i> Add  Dental Prescription</a></li>';
                            
       //      }
       //    }
       //    else
       //    {
       //      $btn_prescription='';
       //    }
       //    }
          
       //    elseif($test->booking_type==4) /* for Gynecology*/
       //    {
       //      $prescription_count_dental = $this->daycare->get_prescription_count_for_dental($test->id);
       //      if($prescription_count_dental<1)
       //      {
       //        if(in_array('1889',$users_data['permission']['action']))
       //        {
       //            $btn_prescription .= '<li><a  href="'.base_url("gynecology/gynecology_prescription/add_gynecology_prescription/".$test->id).'" title="Add Gynecology Prescription"><i class="fa fa-eye"></i> Add  Gynecology Prescription</a></li>';
       //        }
       //          $print_url_gynec = "'".base_url('gynecology/gynecology_prescription/print_blank_prescriptions/'.$test->id.'/'.$test->branch_id)."'";
       //          $btn_prescription .= '<li><a  href="javascript:void(0)" onClick="return print_window_page('.$print_url_gynec.')" title="Print Blank Prescription" ><i class="fa fa-eye"></i
       //          > Blank Gynecology Prescription  </a></li>';
       //      }
       //    else
       //    {
       //      $btn_prescription='';
       //    }

       //        if(in_array('2075',$users_data['permission']['action']))
       //        {
       //            $btn_prescription .= '<li><a  href="'.base_url("day_care/partograph_pres/".$test->id).'" title="Add Prescription"><i class="fa fa-pencil"></i> Add Partograph</a></li>';
       //        }
              

       //  }
          
 //        else
 //        { 
 //          $print_url = "'".base_url('prescription/print_blank_prescriptions/'.$test->id.'/'.$test->branch_id)."'";     
        
 //          if($prescription_count<1)
 //          {
 //            if(in_array('532',$users_data['permission']['action']))
 //            {
 //              $btn_prescription .= '<li><a  href="'.base_url("day_care/prescription/".$test->id).'" title="Add Prescription"><i class="fa fa-pencil"></i> Add Prescription</a></li>';
                
 //                $ids_n='doctor_status_'.$test->id;
 //                $checking_status="<div id=".$ids_n.">";
               
 //                if($test->doctor_checked_status==0)
 //                {
 //                  $checking_status.='<li><a  href="javascript:void(0)"  onclick="return doctor_checked_status('.$test->id.','.$test->doctor_checked_status.')"><i class="fa fa-hourglass" aria-hidden="true"></i>
 // Pending</a></li>';

 //                }
 //                else
 //                {
 //                  $checking_status.='<li><a  href="javascript:void(0)" onclick="return doctor_checked_status('.$test->id.','.$test->doctor_checked_status.')"><i class="fa fa-tick" aria-hidden="true"></i> Checked</a></li>';
 //                }
 //                $checking_status.="<div>";
 //            }
 //          }  
 //          if(in_array('584',$users_data['permission']['action']))
 //          {
 //            $ids='doctor_status_'.$test->id;
 //            $checking_status="<div id=".$ids.">";
 //            if($test->doctor_checked_status==0)
 //            {
 //              $checking_status.='<li><a  href="javascript:void(0)"  onclick="return doctor_checked_status('.$test->id.','.$test->doctor_checked_status.')"><i class="fa fa-hourglass" aria-hidden="true"></i> Pending</a></li>';

 //            }
 //            else
 //            {
 //              $checking_status.='<li><a  href="javascript:void(0)"  onclick="return doctor_checked_status('.$test->id.','.$test->doctor_checked_status.')"><i class="fa fa-tick" aria-hidden="true"></i> Checked</a></li>';
 //            }
           
 //            $btn_prescription .= '<li><a  href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Blank Prescription  </a> </li>';
 //          }
          
 //        }

  

        // $print_mlc='';
        // if(!empty($test->mlc))
        // {
        //   $print_mlcs = "'".base_url('day_care/mlc_print/'.$test->id.'/'.$test->branch_id)."'";
        //   $print_mlc = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_mlcs.')"  title="MLC Print" ><i class="fa fa-print"></i> MLC Print  </a>';
        // }


        // $btn_download_prescription = '<li><a  href="'.base_url('/day_care/print_booking_report/'.$test->id.'/'.$test->branch_id.'/1').'" title="Download Prescription"><i class="fa fa-download"></i> Download Pdf</a></li>';

        // $btn_download_image = '<li><a  href="'.base_url('/day_care/download_image/'.$test->id.'/'.$test->branch_id).'" title="Download Image" target="_blank"><i class="fa fa-download"></i> Download Image</a></li>';

      //   $btn_sales_medicine = '';
      // if(in_array('400',$users_data['permission']['action'])) 
      // {
      // $btn_sales_medicine = '<li><a href="'.base_url('sales_medicine/add/'.$test->patient_id).'/0'.'/'.$test->id.'" style="'.$test->id.'" title="Sale Medicine"><i class="fa fa-plus"></i> Sale Medicine</a></li>';
      // }

      
      // $btn_ot_booking = '<li><a href="'.base_url('ot_booking/add/'.$test->patient_id).'/0'.'/'.$test->id.'" style="'.$test->id.'" title="OT Booking"><i class="fa fa-plus"></i> OT Booking</a></li>';

      // $btn_pathology_booking='';
      // if(in_array('872',$users_data['permission']['action']))
      // { 
      //     $btn_pathology_booking = '<li><a href="'.base_url('test/booking/'.$test->patient_id).'/0'.'/'.$test->id.'" style="'.$test->id.'" title="Test booking"><i class="fa fa-plus"></i> Test Booking</a></li>';
      // }

       //$print_pdf_url = "'".base_url('day_care/print_booking_report/'.$test->id.'/'.$test->branch_id)."'";
       // $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';
		
	if($test->discharge_status==1){
     $print_pdf_url = "'".base_url('day_care/print_discharge_bill/'.$test->id.'/'.$test->patient_id)."'";
        $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';
      }else{
		  
	  $print_pdf_url = "'".base_url('day_care/print_booking_report/'.$test->id.'/'.$test->branch_id)."'";
      $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print </a>';
      }
		
		

       $btncharge_entry = '<li><a href="'.base_url('opd_charge_entry/add/'.$test->id).'/'.$test->patient_id.'" style="'.$test->id.'" title="Charge Entry"><i class="fa fa-keyboard-o"></i> Charge Entry</a></li>';

   

  if($test->discharge_status==1){

     $running_bill = ' <a class="" href="'.base_url('day_care/running_bill_info/'.$test->id.'/'.$test->patient_id.'').'" title="Modify Running Bill" data-url="512"><i class="fa fa-plus"></i> Modify Running Bill</a>'; 
      }else{
      $running_bill = ' <a class="" href="'.base_url('day_care/running_bill_info/'.$test->id.'/'.$test->patient_id.'').'" title="Running Bill" data-url="512"><i class="fa fa-plus"></i> Running Bill</a>';
      }
      // $discharge_bill = ' <a class="" href="'.base_url('day_care/discharge_bill_info?opd_id='.$test->id.'&patient_id='.$test->patient_id.'').'" title="Running Bill" data-url="512"><i class="fa fa-database"></i> Discharge Bill</a>';
     
        $btn_a = '<div class="slidedown">
        <button disabled class="btn-custom">More <span class="caret"></span></button>
        <ul class="slidedown-content">
            '.$btncharge_entry.$running_bill.'
        </ul>
      </div> ';
            // End Action Button //
            $row[] = $btn_confirm.$btn_edit.$btn_delete.$btn_print.$btn_a;    
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->daycare->count_all(),
                        "recordsFiltered" => $this->daycare->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function download_image($id="",$branch_id='')
    {
      $data['type'] = 5;
      $data['page_title'] = "Print Bookings";
      $data['page_title'] = "Print Bookings";
      $data['download_type'] = 2;
      $this->load->model('general/general_model');
      $get_date_time_setting = $this->general_model->get_date_time_setting('opd_booking',$branch_id);
      if(!empty($get_date_time_setting->date_time_status))
      {
        $data['date_time_status'] = $get_date_time_setting->date_time_status;
      }
      else
      {
        $data['date_time_status'] = '';  
      }
      
      //$data[''] = $get_date_time_setting;

      $get_by_id_data = $this->daycare->get_all_detail_print($id,$branch_id);
      // print_r($get_by_id_data);die;
      $template_format = $this->daycare->template_format(array('section_id'=>14,'types'=>1),$branch_id);
      //print_r($get_by_id_data); exit;
      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
       //print_r($data['all_detail']);die; 
      $get_refund = $this->daycare->get_payment_refund($id,$branch_id);
      $refund_payment = 0;
      if(!empty($get_refund[0]->refund_payment))
      {
        $refund_payment = $get_refund[0]->refund_payment;
      }
      $data['refund_payment'] = $refund_payment;
      
        $this->load->view('day_care/print_template_day_care',$data);
      
    }
    public function save_image()
    {
        $post = $this->input->post();
        //print_r($post); exit;
        $data = $post['data'];
        $patient_name = $post['patient_name'];
        $patient_code = $post['patient_code'];
        $data = substr($data,strpos($data,",")+1);
        $data = base64_decode($data);
        $file_name = strtolower($patient_name).'_'.strtolower($patient_code).'.png';
        $download  = REPORT_IMAGE_FS_OPD_PATH.$file_name;
        $file = DIR_UPLOAD_REPORT_OPD_IMAGE_PATH.$file_name;
        file_put_contents($file, $data);

        echo  $download;

    }

    
    public function delete_booking($id="")
    {
        unauthorise_permission(85,525);
       if(!empty($id) && $id>0)
       {
           $result = $this->daycare->delete_booking($id);
           $response = "OPD successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(85,525);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->daycare->deleteall($post['row_id']);
            $response = "Day Care Booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(85,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->daycare->get_by_id($id);  
        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('day_care/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(85,526);
        $data['page_title'] = 'Day Care Booking Archive List';
        $this->load->helper('url');
        $this->load->view('day_care/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(85,526);
        //$this->session->unset_userdata('referral_doctor_id');
        $this->load->model('day_care/day_care_archive_model','day_care_archive'); 
        $users_data = $this->session->userdata('auth_users');
        $list = $this->day_care_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $daycare) { 
            $no++;
            $row = array();
            if($daycare->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($daycare->state))
            {
                $state = " ( ".ucfirst(strtolower($daycare->state))." )";
            }
            //////////////////////// 
            
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

            if($daycare->booking_status==0)
            {
                $booking_status = '<font color="red">Pending</font>';
            }   
            elseif($daycare->booking_status==1){
                $booking_status = '<font color="green">Confirm</font>';
            }                 
            elseif($daycare->booking_status==2){
                $booking_status = '<font color="blue">Attended</font>';
            } 
            if($daycare->type==0)
            {
                $type = 'Booking';
            }   
            elseif($daycare->type==1){
                $type = 'Billing';
            }

            $attended_doctor_name ="";
            $attended_doctor_name = get_doctor_name($daycare->attended_doctor);

            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$daycare->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$daycare->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $daycare->booking_code;
            // $row[] = $type;
            $row[] = $daycare->patient_name;
            $row[] = $attended_doctor_name;
            
            // if($daycare->confirm_date =='0000-00-00')
            // {
            //     $row[] =''; 
            // }
            // else
            // {
            //     $row[] = date('d M Y',strtotime($daycare->confirm_date));   
            // }
            
            $row[] = date('d M Y',strtotime($daycare->booking_date));
            //$row[] = $test->total_amount;
            $row[] = $booking_status;
            //$row[] = date('d-M-Y H:i A',strtotime($daycare->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
            if(in_array('527',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_opd('.$daycare->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            }
            if(in_array('528',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$daycare->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->day_care_archive->count_all(),
                        "recordsFiltered" => $this->day_care_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore_opd($id="")
    {
        unauthorise_permission(85,527);
        $this->load->model('day_care/day_care_archive_model','day_care_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->day_care_archive->restore($id);
           $response = "Day Care Booking successfully restore in OPD Booking list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(85,527);
        $this->load->model('day_care/day_care_archive_model','day_care_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->day_care_archive->restoreall($post['row_id']);
            $response = "Day Care successfully restore in list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(85,528);
        $this->load->model('day_care/day_care_archive_model','day_care_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->day_care_archive->trash($id);
           $response = "Day Care successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(85,528);
        $this->load->model('day_care/day_care_archive_model','day_care_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->day_care_archive->trashall($post['row_id']);
            $response = "Day Care successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function opd_dropdown()
  {
      $opd_list = $this->daycare->employee_type_list();
      $dropdown = '<option value="">Select OPD</option>'; 
      if(!empty($opd_list))
      {
        foreach($opd_list as $daycare)
        {
           $dropdown .= '<option value="'.$daycare->id.'">'.$daycare->doctor_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  function get_comission($id="")
  {
    $json_data = [];
     if(!empty($id) && $id>0)
     {
        if($id==1)
        {
            $arr = array('lable'=>'Share Details', 'inputs'=>'<a href="javascript:void(0)" class="btn-commission"  onclick="comission();"><i class="fa fa-cog"></i> Commission</a>');
            $json_data = json_encode($arr);
        }
        else if($id==2)
        {
            $this->load->model('general/general_model');
            $rate_list = $this->general_model->get_rate_list();
            $drop = '<select name="rate_plan_id" id="rate_plan_id">
                     <option value="">Select Rate Plan</option>';
            if(!empty($rate_list))
            {
                foreach($rate_list as $rate)
                {
                    $drop .= '<option value="'.$rate->id.'">'.$rate->title.'</option>';
                }
            }
            $drop .= '</select>';
            $arr = array('lable'=>'Rate list', 'inputs'=>$drop);
            $json_data = json_encode($arr);
        }
        echo $json_data; 
     }
  }

  public function add_comission()
  {
     $data['page_title'] = "Pathology Share Details";
     $this->load->model('general/general_model');
     $post = $this->input->post();
     if(isset($post['id']) && !empty($post['id']))
     {
        $comission_data = $this->doctors->doc_comission_data($post['id']);
        $this->session->set_userdata('comission_data',$comission_data);
     } 
     $data['dept_list'] = $this->general_model->department_list();         
     if(isset($post['data']) && !empty($post['data']))
     { 
        $this->session->set_userdata('comission_data',$post);
        echo '1'; return false;
     }
     $this->load->view('doctors/add_comission',$data);
  }

  public function view_comission()
  {
     $data['page_title'] = "Pathology Share Details";
     $this->load->model('general/general_model');
     $post = $this->input->post();
     $data['dept_list'] = $this->general_model->department_list();
     if(isset($post['id']) && !empty($post['id']))
     {
        $data['comission'] = $this->doctors->doc_comission_data($post['id']); 
     }  
     $this->load->view('doctors/view_comission',$data);
  }




  public function booking($pid="")
  {  

    $post = $this->input->post();

//echo "<pre>";print_r( $post );die();

      $users_data = $this->session->userdata('auth_users');
      unauthorise_permission(85,523);
      $this->load->model('general/general_model'); 
      $post = $this->input->post();
      $patient_id = "";
      $patient_code = "";
      $simulation_id = "";
      $patient_name = "";
      $mobile_no = "";
      $gender = "1";
      $age_y = "";
      $age_m = "";
      $age_d = "";
      $address = "";
      $address_second = "";
      $address_third = "";
      $city_id = "";
      $state_id = "";
      $country_id = "99"; 
      $patient_email ="";
      $patient_bp ="";
      $patient_temp ="";
      $patient_weight ="";
      $patient_height ="";
      $patient_sop ="";
      $patient_rbs ="";
      $booking_time ="";
      $opd_type="0";
      $pannel_type="0";
      $remarks='';
      $ref_by_other ="";
      $payment_mode="";
      $diseases="";
      $branch_id =$users_data['parent_id'];
      $type=0;
      $package_id="";
      $specialization_id="";
      $kit_amount='';
      $consultants_charge="";
      $next_app_date ="";
      $referred_by='';
      $insurance_type='';
      $insurance_type_id='';
      $ins_company_id='';
      $polocy_no='';
      $tpa_id='';
      $ins_amount='';
      $ins_authorization_no='';
      $relation_name='';
      $relation_type='';
      $relation_simulation_id='';
      $adhar_no='';
      $dob='';
        if($pid>0)
        {
           $this->load->model('patient/patient_model');
           $patient_data = $this->patient_model->get_by_id($pid);
          // echo "<pre>";print_r( $patient_data );die();
           if(!empty($patient_data))
           {
                $patient_id = $patient_data['id'];
                $patient_code = $patient_data['patient_code'];
                $simulation_id = $patient_data['simulation_id'];
                $patient_name = $patient_data['patient_name'];
                $mobile_no = $patient_data['mobile_no'];
                $gender = $patient_data['gender'];
                $age_y = $patient_data['age_y'];
                $age_m = $patient_data['age_m'];
                $age_d = $patient_data['age_d'];

                if($patient_data['dob']=='1970-01-01' || $patient_data['dob']=="0000-00-00")
                {
                  $dob='';
                  $present_age = get_patient_present_age('',$patient_data);
                }
                else
                {
                  $dobs=date('d-m-Y',strtotime($patient_data['dob']));
                  $present_age = get_patient_present_age($dobs); 
                }

                $age_y = $present_age['age_y'];
                  $age_m = $present_age['age_m'];
                  $age_d = $present_age['age_d'];
                if($patient_data['dob']=='1970-01-01' || $patient_data['dob']=="0000-00-00")
                {
                  $dob='';
                }
                else
                {
                  $dob=date('d-m-Y',strtotime($patient_data['dob']));
                }
                
                $address = $patient_data['address'];
                $address_second = $patient_data['address2'];
                $address_third = $patient_data['address3'];
                $city_id = $patient_data['city_id'];
                $state_id = $patient_data['state_id'];
                $country_id = $patient_data['country_id'];
                $patient_email = $patient_data['patient_email'];
                $insurance_type=$patient_data['insurance_type'];
                $insurance_type_id=$patient_data['insurance_type_id'];
                $ins_company_id=$patient_data['ins_company_id'];
                $polocy_no=$patient_data['polocy_no'];
                $tpa_id=$patient_data['tpa_id'];
                $ins_amount=$patient_data['ins_amount'];
                $ins_authorization_no=$patient_data['ins_authorization_no'];
                $adhar_no=$patient_data['adhar_no']; 
                $relation_name=$patient_data['relation_name'];
                $relation_type=$patient_data['relation_type'];
                $relation_simulation_id=$patient_data['relation_simulation_id'];

           }
        }
        $data['country_list'] = $this->general_model->country_list();

        if(!empty($post['branch_id']))
        {
          $data['insurance_type_list'] = $this->general_model->insurance_type_list($post['branch_id']);
        }
        else
        {
          $data['insurance_type_list'] = $this->general_model->insurance_type_list();  
        }

        if(!empty($post['branch_id']))
        {
          $data['insurance_company_list'] = $this->general_model->insurance_company_list($post['branch_id']);
        }
        else
        {
          $data['insurance_company_list'] = $this->general_model->insurance_company_list(); 
        }
        if(!empty($post['branch_id']))
        {
          $data['simulation_list'] = $this->general_model->simulation_list($post['branch_id']);
        }
        else
        {
          $data['simulation_list'] = $this->general_model->simulation_list();  
        }

        if(!empty($post['branch_id']))
        {
          $data['specialization_list'] = $this->general_model->specialization_list($post['branch_id']);
        }
        else
        {
          $data['specialization_list'] = $this->general_model->specialization_list();
            
        }

        if(!empty($post['branch_id']))
        {
          $data['package_list'] = $this->general_model->package_list('',$post['branch_id']);
        }
        else
        {
          $data['package_list'] = $this->general_model->package_list();
        }


        if(!empty($post['branch_id']))
        {
          $data['referal_doctor_list'] = $this->daycare->referal_doctor_list($post['branch_id']);
        }
        else
        {
          $data['referal_doctor_list'] = $this->daycare->referal_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['referal_hospital_list'] = $this->daycare->referal_hospital_list($post['branch_id']);
        }
        else
        {
          $data['referal_hospital_list'] = $this->daycare->referal_hospital_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['attended_doctor_list'] = $this->daycare->attended_doctor_list($post['branch_id'],$post['specialization']);
        }
        else
        {
          $data['attended_doctor_list'] = $this->daycare->attended_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['attended_doctor_list'] = $this->daycare->attended_doctor_list($post['branch_id']);
        }
        else
        {
          $data['attended_doctor_list'] = $this->daycare->attended_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['employee_list'] = $this->daycare->employee_list($post['branch_id']);
        }
        else
        {
          $data['employee_list'] = $this->daycare->employee_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['profile_list'] = $this->daycare->profile_list($post['branch_id']);
        }
        else
        {
          $data['profile_list'] = $this->daycare->profile_list();
        }
        if(!empty($post['branch_id']))
        {
          $data['source_list'] = $this->daycare->source_list($post['branch_id']);

        }
        else
        {
          $data['source_list'] = $this->daycare->source_list();

        }

        if(!empty($post['branch_id']))
        {
           $data['diseases_list'] = $this->daycare->diseases_list($post['branch_id']);
        }
        else
        {
           $data['diseases_list'] = $this->daycare->diseases_list();
        }

        $attended_doctor ='';
        if(!empty($post['attended_doctor']))
        {
          $attended_doctor =$post['attended_doctor'];
        }
        $available_time ='';
        if(!empty($post['available_time']))
        {
          $available_time =$post['available_time'];
        }
        $doctor_slot ='';
        if(!empty($post['doctor_slot']))
        {
          $doctor_slot =$post['doctor_slot'];
        }
        $appointment_date='';
        if(!empty($post['appointment_date']))
        {
          $appointment_date =$post['appointment_date'];
        }

        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['doctor_available_time'] = $this->general_model->doctor_time($attended_doctor);
        $data['doctor_available_slot'] = $this->get_doctor_slot($attended_doctor,$available_time,$doctor_slot,$appointment_date);
        $token_type=$this->daycare->get_token_setting();

        $doct_set=$this->daycare->default_doctor_setting();
        $def_specl_id='';
        $def_doct_id='';
        if(!empty($doct_set))
        {
          $def_specl_id=$doct_set['specialize_id'];
          $def_doct_id=$doct_set['doctor_id'];
        }

        $token_type=$token_type['type'];
        $validity=0;
        $validity_days=$this->daycare->get_opd_validity_days();

        if(!empty($validity_days['days']))
        {
          $validity=$validity_days['days']-1;
        }
        
        $validate_date = date('Y-m-d', strtotime(' + '.$validity.' days')); 
        $validatedate  = date('d-m-Y', strtotime($validate_date));
        $data['page_title'] = "Day Care Booking";  

        if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }

        $booking_code = generate_unique_id(55);

        //print_r($booking_code); die;

        $data['form_error'] = []; 
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['vitals_list']=$this->general_model->vitals_list();
        //pannel_type

        if($adhar_no=='0')
        {
          $adhar_no_new='';  
        }
        else
        {
            $adhar_no_new = $adhar_no;
        }
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'patient_code'=>$patient_code,
                                  'booking_code'=>$booking_code,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'dob'=>$dob,
                                  'patient_email'=>$patient_email,
                                  'adhar_no'=>$adhar_no,
                                  'dept_id'=>"",
                                  'address'=>$address,
                                  'address_second'=>$address_second,
                                  'address_third'=>$address_third,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'specialization'=>'',
                                  'referral_doctor'=>"",
                                  'attended_doctor'=>$def_doct_id,
                                  'sample_collected_by'=>"",
                                  'staff_refrenace_id'=>"",
                                  'booking_date'=>date('d-m-Y'),
                                  'validity_date'=>$validatedate,  
                                  'total_amount'=>"0.00",
                                  'net_amount'=>"0.00",
                                  'paid_amount'=>"0.00",
                                  'discount'=>"0.00",
                                  'balance'=>"0.00",
                                  'pay_now'=>'',
                                  'type'=>$type,
                                  'patient_bp' =>"",
                                  'patient_temp' =>"",
                                  'patient_weight' =>"",
                                  'patient_height' =>"",
                                  'patient_sop' =>"",
                                  'patient_rbs' =>"",
                                  'booking_time'=>date('H:i:s'),
                                  'opd_type'=>$opd_type,
                                  'pannel_type'=>$pannel_type,
                                  'remarks'=>'',
                                  'ref_by_other'=>$ref_by_other,
                                  'payment_mode'=>"cash",
                                  'branch_id'=>$branch_id,
                                  'package_id'=>$package_id,
                                  'diseases'=>$diseases,
                                  "country_code"=>"+91",
                                  'specialization_id'=>$def_specl_id,
                                  'kit_amount'=>'0.00',
                                  'consultants_charge'=>'0.00',
                                  'next_app_date'=>'',
                                  "field_name"=>'',
                                  'source_from'=>'',
                                  'referred_by'=>$referred_by,
                                  'referral_hospital'=>"",
                                  'token_type'=>$token_type,
                                  'time_value'=>'',
                                  'token_no'=>'',
                                  "insurance_type"=>$insurance_type,
                                  "insurance_type_id"=>$insurance_type_id,
                                  "ins_company_id"=>$ins_company_id,
                                  "polocy_no"=>$polocy_no,
                                  "tpa_id"=>$tpa_id,
                                  "relation_name"=>$relation_name,
                                  "relation_type"=>$relation_type,
                                  "relation_simulation_id"=>$relation_simulation_id,
                                  "ins_amount"=>$ins_amount,
                                  "ins_authorization_no"=>$ins_authorization_no,
                                  );    

        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validateform();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->daycare->save_booking();
                $this->session->set_userdata('day_booking_id',$booking_id);
              if(!empty($booking_id))
              {
                  $get_by_id_data = $this->daycare->get_by_id($booking_id);
                  $patient_name = $get_by_id_data['patient_name'];
                  $booking_code = $get_by_id_data['booking_code'];
                  $paid_amount = $get_by_id_data['paid_amount'];
                  $mobile_no = $get_by_id_data['mobile_no'];
                  $patient_email = $get_by_id_data['patient_email'];
                  $patient_id = $get_by_id_data['patient_id'];
                //check permission
                if(in_array('640',$users_data['permission']['action']))
                {
                    if(!empty($mobile_no))
                    {
                      send_sms('opd_booking',2,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{OPDNo}'=>$booking_code,'{Amount}'=>$paid_amount));  
                    }
                    
                }

                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($patient_email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($patient_email,'','','','','1','opd_booking','2',array('{Name}'=>$patient_name,'{OPDNo}'=>$booking_code,'{Amount}'=>$paid_amount));
                     
                  }
                } 
              

              }

                //exit;
                $this->session->set_flashdata('success','Day Care booking successfully done.');
                redirect(base_url('day_care/booking?status=print'));
                
               
      }
            else
            {
               

                $data['form_error'] = validation_errors();  
                
            }     
        }

        
    $data['simulation_array']= $this->general_model->simulation_list();
       $this->load->view('day_care/booking',$data);       
    }

    function get_doctor_slot($doctor_id='',$time_id='',$selected='',$appointment_date='',$appointment_id='')
    {
        $post = $this->input->post(); 
        $this->load->model('general/general_model');
        $option =''; 
        if(!empty($doctor_id) && !empty($time_id))
        {
            $booked_slot_list = $this->general_model->get_booked_slot($doctor_id,$time_id,$appointment_date,$appointment_id);
            $booked_slot = array();
            foreach ($booked_slot_list as $booked_list) 
            { 
              $booked_slot[] = $booked_list->doctor_slot;
            }   

           // print_r($booked_slot); exit();

            $time_list = $this->general_model->doctor_slot($doctor_id,$time_id);
            //print_r($time_list); exit;
            if(!empty($time_list))
            {
            $per_patient_time = $this->general_model->per_patient_time($doctor_id);
            $time1 = strtotime($time_list[0]->time1);
            $time2 = strtotime($time_list[0]->time2);
            $total_time = $time2-$time1;
            $time_in_minute = $total_time/60;  
            $total_slot = $time_in_minute/$per_patient_time[0]->per_patient_timing;
            $slot_data = '';  
            $option .= "<option value=''>Select Time</option>";
            $start_slot = $time1;
            $end_slot = $start_slot+($per_patient_time[0]->per_patient_timing*60);
            for($i=0;$i<$total_slot;$i++)
            { 
                $slot_data = date('H:i A', $start_slot).' To '.date('H:i A', $end_slot);
                $start_slot = $end_slot+60;
                $end_slot = ($end_slot+($per_patient_time[0]->per_patient_timing*60));
                $chek='';
                if($selected==$slot_data)
                {
                  $chek = 'selected="selected"';
                }
                if(!in_array($slot_data, $booked_slot))
                {
                  $option .= "<option ".$chek." value='".$slot_data."'>".$slot_data."</option>";
                }
            }
            } 
        }
        return $option;
    }

    


    public function get_allsub_branch_list(){
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $dropdown="";
        if(!empty($parent_branch_details)){
        $branch_name = get_branch_name($parent_branch_details[0]);

        
        
            $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id"><option value="">Select Branch</option></option><option value="'.$parent_branch_details[0].'">'.$branch_name.'</option><option  selected="selected" value='.$users_data['parent_id'].'>Self</option>';
             if(!empty($sub_branch_details)){
                 $i=0;
                foreach($sub_branch_details as $key=>$value){
                     $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                     $i = $i+1;
                 }
               
             }
            $dropdown.='</select>';
       }
            echo $dropdown; 
        
         
       
    }

    public function get_doctor($id="")
    {
        $option="";
        $doctor_list = getDoctor($id);  
       
        if(!empty($doctor_list))
         { 
        $option ='<select name="state" class="form-control"><option value="">Select Doctor</option>';
        if(!empty($doctor_list))
         {
          foreach($doctor_list as $doctorlist)
           {
           $option .= '<option value="'.$doctorlist->id.'">'.$doctorlist->doctor_name.'</option>';
           }
            $option .= '</select>';
         }
        }
        echo $option;
    }
    
    public function edit_booking($id="")
    { 
       unauthorise_permission(85,524);
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model'); 
        $post = $this->input->post();
        $result = $this->daycare->get_by_id($id); 
  // echo '<pre>' ;print_r($result);die;
        $data['simulation_array']= $this->general_model->simulation_list();
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->daycare->referal_doctor_list();
        $data['attended_doctor_list'] = $this->daycare->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();  
        $data['package_list'] = $this->general_model->package_list();
        $data['diseases_list'] = $this->daycare->diseases_list();
        $data['source_list'] = $this->daycare->source_list();
        $data['referal_hospital_list'] = $this->daycare->referal_hospital_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list(); 
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['page_title'] = "Update Day Care Booking";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $token_type=$this->daycare->get_token_setting();
          $token_type=$token_type['type'];
        $checkdate = date('d-m-Y',strtotime($result['cheque_date']));
        if(!empty($checkdate) && $checkdate!='00-00-0000' && $checkdate!='01-01-1970')
        {
          $checkdates= $checkdate;
        }
        else
        {
          $checkdates = ""; 
        }
        $next_app_date = date('d-m-Y',strtotime($result['next_app_date']));
        if(!empty($next_app_date) && $next_app_date!='00-00-0000' && $next_app_date!='01-01-1970' && $next_app_date!='30-11--0001')
        {
          $next_app_date  = $next_app_date;
        }
        else
        {
          $next_app_date="";
        } 

        $validity_dates = date('d-m-Y',strtotime($result['validity_date']));
        if(!empty($validity_dates) && $validity_dates!='00-00-0000' && $validity_dates!='01-01-1970' && $validity_dates!='30-11--0001')
        {
          $validity_date  = $validity_dates;
        }
        else
        {
          $validity_date="";
        } 

        $this->load->model('general/general_model');
        $data['payment_mode']=$this->general_model->payment_mode();
        $get_payment_detail= $this->daycare->payment_mode_detail_according_to_field($result['payment_mode'],$id);
        $total_values='';

        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
       
       //else
       //{
      $adhar_no='';
       if($result['adhar_no']!=0)
       {
          $adhar_no=$result['adhar_no'];
       }
       //}


      $data['vitals_list']=$this->general_model->vitals_list();
      $data['doctor_available_time'] = $this->general_model->doctor_time($result['attended_doctor']);
      $data['doctor_available_slot'] = $this->get_doctor_slot($result['attended_doctor'],$result['available_time'],$result['doctor_slot'],$result['booking_date'],$result['id']);
      
      if(($result['dob']=='1970-01-01') || ($result['dob']=="0000-00-00"))
      {
        //echo $result['dob'];
        $present_age = get_patient_present_age('',$result);
      }
      else
      {
        $dobs = date('d-m-Y',strtotime($result['dob']));
        $present_age = get_patient_present_age($dobs);
      }
    
    if(($result['dob']=='1970-01-01') || ($result['dob']=="0000-00-00"))
    {
       $date_birth =''; 
    }
    else
    {
        $date_birth = date('d-m-Y',strtotime($result['dob']));
    }
      
      $age_y = $present_age['age_y'];
      
      $age_m = $present_age['age_m'];
     
      $age_d = $present_age['age_d'];
      
//echo "<pre>";print_r($result); die;
              $data['form_data'] = array(
                                    'data_id'=>$result['id'],
                                    'branch_id'=>$result['branch_id'], 
                                    'mlc_status'=>$result['mlc_status'], 
                                    'mlc'=>$result['mlc'], 
                                    'patient_id'=>$result['patient_id'], 
                                    'patient_code'=>$result['patient_code'],
                                    'booking_code'=>$result['booking_code'],
                                    'simulation_id'=>$result['simulation_id'],
                                    'patient_name'=>$result['patient_name'],
                                    'mobile_no'=>$result['mobile_no'],
                                    "relation_name"=>$result['relation_name'],
                                    "relation_type"=>$result['relation_type'],
                                    "relation_simulation_id"=>$result['relation_simulation_id'],
                                    'gender'=>$result['gender'],
                                    'adhar_no'=>$adhar_no,
                                    'age_y'=>$age_y,
                                    'age_m'=>$age_m,
                                    'age_d'=>$age_d,
                                    'dob'=>$date_birth,
                                    'pay_now'=>$result['pay_now'],
                                    'address'=>$result['address'],
                                    'address_second'=>$result['address2'],
                                    'address_third'=>$result['address3'],
                                    'city_id'=>$result['city_id'],
                                    'state_id'=>$result['state_id'],
                                    'country_id'=>$result['country_id'],
                                    'package_id'=>$result['package_id'],
                                    'referral_doctor'=>$result['referral_doctor'],
                                    'specialization_id'=>$result['specialization_id'],
                                    'attended_doctor'=>$result['attended_doctor'],
                                    'sample_collected_by'=>$result['sample_collected_by'],
                                    'staff_refrenace_id'=>$result['staff_refrenace_id'],
                                    'booking_date'=>date('d-m-Y',strtotime($result['booking_date'])),
                                    'validity_date'=>$validity_date,
                                    'total_amount'=>$result['total_amount'],
                                    'net_amount'=>$result['net_amount'],
                                    'paid_amount'=>$result['paid_amount'],
                                    'discount'=>$result['discount'],
                                    'balance'=>$result['balance'],
                                    'payment_mode'=>$result['payment_mode'],
                                    "field_name"=>$total_values,
                                    'type'=>$result['type'],
                                    'patient_sop'=>$result['patient_spo2'],
                                    'patient_email'=>$result['patient_email'],
                                    'patient_bp' =>$result['patient_bp'],
                                    'patient_temp' =>$result['patient_temp'],
                                    'patient_weight' =>$result['patient_temp'],
                                    'patient_height' =>$result['patient_height'],
                                    'patient_rbs' =>$result['patient_rbs'],
                                    'booking_time'=>$result['booking_time'],
                                    'opd_type'=>$result['opd_type'],
                                    'pannel_type'=>$result['pannel_type'],
                                    'ref_by_other'=>$result['ref_by_other'],
                                    'diseases'=>$result['diseases'],
                                    "country_code"=>"+91",
                                    'kit_amount'=>$result['kit_amount'],
                                    'consultants_charge'=>$result['consultants_charge'],
                                    'next_app_date'=>$next_app_date,
                                    'source_from'=>$result['source_from'],
                                    'remarks'=>$result['remarks'],
                                    'referred_by'=>$result['referred_by'],
                                    'referral_hospital'=>$result['referral_hospital'],
                                    'token_type'=>$token_type,
                                    'available_time'=>$result['available_time'],
                                    'doctor_slot'=>$result['doctor_slot'],
                                    'time_value'=>$result['time_value'],
                                    'token_no'=>$result['token_no'],
                                    "insurance_type"=>$result['insurance_type'],
                                    "insurance_type_id"=>$result['insurance_type_id'],
                                    "ins_company_id"=>$result['ins_company_id'],
                                    "polocy_no"=>$result['polocy_no'],
                                    "tpa_id"=>$result['tpa_id'],
                                    "ins_amount"=>$result['ins_amount'],
                                    "ins_authorization_no"=>$result['ins_authorization_no'],
                                    );  
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validateform();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->daycare->save_booking();
                $this->session->set_userdata('day_booking_id',$booking_id);
                $this->session->set_flashdata('success','Day Care booking successfully booked.');
                redirect(base_url('day_care/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('day_care/booking',$data);       
      }
    }

    private function _validateform()
    {
        $post = $this->input->post(); 
        $field_list = mandatory_section_field_list(3);
        $users_data = $this->session->userdata('auth_users'); 
         $total_values=array();
        if(isset($post['field_name'])) 
        {
        $count_field_names= count($post['field_name']);  
       
        $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
        $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        } 
        }
    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
        $this->form_validation->set_rules('adhar_no', 'adhar no', 'min_length[12]|max_length[16]'); 
      
        if(isset($post['field_name'])) 
        {
        $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
        
        if(!empty($field_list)){ 
            if($field_list[0]['mandatory_field_id']=='25' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }
           
            if($field_list[1]['mandatory_field_id']=='26' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                
                
                 if(($post['age_y']=='0' && $post['age_m']=='0' && $post['age_d']=='0') || ((empty($post['age_y']) && empty($post['age_m']) && empty($post['age_d']))) )
                    {
                         
                        $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|is_natural_no_zero|max_length[3]');
                    }
            }
            if($field_list[2]['mandatory_field_id']=='41' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('specialization', 'Specialization', 'trim|required'); 
            }
            if($field_list[3]['mandatory_field_id']=='42' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('attended_doctor', 'Consultant', 'trim|required'); 
            }
            
            if(isset($post['pannel_type']) && $post['pannel_type']==1)
            {
              $this->form_validation->set_rules('ins_company_id', 'company name', 'trim|required');
              $this->form_validation->set_rules('insurance_type_id', 'insurance type', 'trim|required');
              $this->form_validation->set_rules('polocy_no', 'policy no', 'trim|required');
              $this->form_validation->set_rules('tpa_id', 'tpa id', 'trim|required');
              $this->form_validation->set_rules('ins_amount', 'insurance amount', 'trim|required');
              $this->form_validation->set_rules('ins_authorization_no', 'insurance authorization no.', 'trim|required');
            }
        }
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            if(!empty($post['pay_now'])){
              $pay_now = 1;
            }
            else
            {
              $pay_now = 0;
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
            if($post['adhar_no']==0)
            {
            $adhar_no=$post['adhar_no'];
            }
            else
            {
            $adhar_no='';
            }
            $data['form_data'] = array(
                                        'branch_id'=>$post['branch_id'],
                                        'data_id'=>$post['data_id'],
                                        'adhar_no'=>$post['adhar_no'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'booking_code'=>$post['booking_code'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        'gender'=>$post['gender'],
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        'dob'=>$post['dob'],
                                        'address'=>$post['address'],
                                        'address_second'=>$post['address_second'],
                                        'address_third'=>$post['address_third'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'booking_time'=>$post['booking_time'],
                                        'booking_date'=>$post['booking_date'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$post['balance'],
                                        'type'=>$post['type'],
                                        'patient_email'=>$post['patient_email'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'booking_time'=>$post['booking_time'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$post['balance'],
                                        "field_name"=>$total_values,
                                        'pay_now'=>$pay_now,
                                        'payment_mode'=>$post['payment_mode'],
                                        'specialization_id'=>$post['specialization'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        "country_code"=>"+91",
                                        'diseases'=>$post['diseases'],
                                        'next_app_date'=>$post['next_app_date'],
                                        'kit_amount'=>$post['kit_amount'],
                                        'consultants_charge'=>$post['consultants_charge'],
                                        'package_id'=>$post['package_id'],
                                        'source_from'=>$post['source_from'],
                                        'remarks'=>$post['remarks'],
                                        'pannel_type'=>$post['pannel_type'],
                                        'opd_type'=>$post['opd_type'],
                                        'referral_hospital'=>$post['referral_hospital'],
                                        'referred_by'=>$post['referred_by'],
                                        'validity_date'=>$post['validity_date'],
                                        'token_type'=>$post['token_type'],
                                        "insurance_type"=>$insurance_type,
                                        "insurance_type_id"=>$insurance_type_id,
                                        "ins_company_id"=>$ins_company_id,
                                        "polocy_no"=>$post['polocy_no'],
                                        "tpa_id"=>$post['tpa_id'],
                                        "ins_amount"=>$post['ins_amount'],
                                        "ins_authorization_no"=>$post['ins_authorization_no'],
                                        "relation_name"=>$post['relation_name'],
                                        "relation_type"=>$post['relation_type'],
                                        "relation_simulation_id"=>$post['relation_simulation_id'],
                                        'token_no'=>$post['token_no'],
                                       ); 
            return $data['form_data'];
        }   
    }

    

    public function doctor_rate()
    {
       $post = $this->input->post();
       //print_r($post); exit;
       if(isset($post) && !empty($post) && !empty($post['doctor_id']))
       {
         $branch_id = $post['branch_id'];
         $ins_company_id ='';
         if(isset($post['ins_company_id']))
         {
          $ins_company_id = $post['ins_company_id']; 
         }
         
         $panel_type = $post['panel_type'];
         $opd_type = $post['opd_type'];
         $doctor_id = $post['doctor_id'];
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

         
        // $pay_arr = array('total_amount'=>$consultant_charge); //number_format
         //$netamount = $consultant_charge-$post['discount'];
         
         if(!empty($post['discount']))
         {
          $discount = $post['discount'];
         }
         else
         {
            $discount='0.00';
         }
         if(!empty($post['kit_amount']))
         {
          $kit_amount = $post['kit_amount'];
         }
         else
         {
            $kit_amount='0.00';
         }
         $paid_amount = $post['paid_amount'];
         //$kit_amount = $post['kit_amount'];
         if($discount!='0.00')
         {
          $discount_total =number_format($discount,2,'.', '');
         }
         else
         {
          $discount_total ='0.00';
         }
         
         $balance = number_format($consultants_charge+$kit_amount-$post['discount']-$post['paid_amount'],2,'.', ''); 
         $pay_arr = array('kit_amount'=>number_format($kit_amount,2,'.',''),'consultants_charge'=>number_format($consultants_charge,2,'.',''),'total_amount'=>number_format($consultants_charge+$kit_amount,2,'.', ''),'discount'=>$discount_total,'net_amount'=>number_format($kit_amount+$consultants_charge-$post['discount'],2,'.', ''),'paid_amount'=>number_format($kit_amount+$consultants_charge-$post['discount'],2,'.', ''),'balance'=>$balance);

         //$pay_arr = array('total_amount'=>number_format($consultant_charge,2,'.', ''),'discount'=>number_format($discount,2,'.', ''),'net_amount'=>number_format($netamount,2,'.', ''),'paid_amount'=>number_format($consultant_charge-$post['discount'],2,'.', ''));   

         $json = json_encode($pay_arr,true);
         echo $json;
         
       }
    }


    public function doctor_panel_rate()
    {
       $post = $this->input->post();
       //print_r($post); exit;
       if(isset($post) && !empty($post) && !empty($post['doctor_id']) && !empty($post['ins_company_id']))
       {
         $ins_company_id = $post['ins_company_id'];
         $branch_id = $post['branch_id'];
         $panel_type = $post['panel_type'];
         $opd_type = $post['opd_type'];
         
         $doctor_rate="";
         $total_amount = 0;
         $doctor_id = $post['doctor_id'];
         $this->load->model('general/general_model');
         $consultants_charge = $this->general_model->doctors_panel_charge($doctor_id,$ins_company_id,$branch_id,$opd_type);
         
         if(!empty($post['discount']))
         {
          $discount = $post['discount'];
         }
         else
         {
            $discount='0.00';
         }
         if(!empty($post['kit_amount']))
         {
          $kit_amount = $post['kit_amount'];
         }
         else
         {
            $kit_amount='0.00';
         }
         $paid_amount = $post['paid_amount'];
         //$kit_amount = $post['kit_amount'];
         if($discount!='0.00')
         {
          $discount_total =number_format($discount,2,'.', '');
         }
         else
         {
          $discount_total ='0.00';
         }
         
         $balance = number_format($consultants_charge+$kit_amount-$post['discount']-$post['paid_amount'],2,'.', ''); 
         $pay_arr = array('kit_amount'=>number_format($kit_amount,2,'.',''),'consultants_charge'=>number_format($consultants_charge,2,'.',''),'total_amount'=>number_format($consultants_charge+$kit_amount,2,'.', ''),'discount'=>$discount_total,'net_amount'=>number_format($kit_amount+$consultants_charge-$post['discount'],2,'.', ''),'paid_amount'=>number_format($kit_amount+$consultants_charge-$post['discount'],2,'.', ''),'balance'=>$balance);

        $json = json_encode($pay_arr,true);
         echo $json;
         
       }
    }

    public function generate_token()
    {

       $post = $this->input->post();
       $token_no='';
       if(isset($post) && !empty($post) && !empty($post['doctor_id']))
       {
         $branch_id = $post['branch_id'];
         $doctor_id = $post['doctor_id'];
         $specilization_id=$post['specilization_id'];
         $booking_date = date("Y-m-d", strtotime($post['booking_date']) ); 
         $token_type=$this->daycare->get_token_setting();
         $token_type=$token_type['type'];
         //echo $type;die;
          if($token_type=='0') //hospital wise token no
          { 
            
           $patient_token_details_for_hospital=$this->daycare->get_patient_token_details_for_type_hospital($booking_date,$token_type);
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
            $patient_token_details_for_doctor=$this->daycare->get_patient_token_details_for_type_doctor($doctor_id,$booking_date,$token_type);
              //  print_r($patient_token_details_for_doctor);die;

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
            $patient_token_details_for_specialization=$this->daycare->get_patient_token_details_for_type_specialization($specilization_id,$booking_date,$token_type);
              //  print_r($patient_token_details_for_doctor);die;

                if($patient_token_details_for_specialization['token_no']>'0')
          {
            //echo "hi";die;
            $token_no=$patient_token_details_for_specialization['token_no']+1;
          }
          else
          {
            //echo "hi";die;
            $token_no='1';

          }

         }
         else
         {

         }
         $pay_arr = array('token_no'=>$token_no);
         $json = json_encode($pay_arr,true);
         echo $json;
         
       }
    }

    

    public function doctor_emergency_rate()
    {
       $post = $this->input->post();
      // print_r($post); exit;
       if(isset($post) && !empty($post) && !empty($post['doctor_id']))
       {
         $branch_id = $post['branch_id'];
         $doctor_id = $post['doctor_id'];
         $this->load->model('general/general_model');
         $ins_company_id ='';
         if(isset($post['ins_company_id']))
         {
          $ins_company_id = $post['ins_company_id']; 
         }
         $panel_type = $post['panel_type'];
         $opd_type = $post['opd_type'];
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
            

         }
          if(!empty($post['discount']))
         {
          $discount = $post['discount'];
         }
         else
         {
            $discount='0.00';
         }
         if(!empty($post['kit_amount']))
         {
          $kit_amount = $post['kit_amount'];
         }
         else
         {
            $kit_amount='0.00';
         }
         $paid_amount = $post['paid_amount'];
         //$kit_amount = $post['kit_amount'];
         if($discount!='0.00')
         {
          $discount_total =number_format($discount,2,'.', '');
         }
         else
         {
          $discount_total ='0.00';
         }
         
         $balance = number_format($consultants_charge+$kit_amount-$post['discount']-$post['paid_amount'],2,'.', ''); 
         $pay_arr = array('kit_amount'=>number_format($kit_amount,2,'.',''),'consultants_charge'=>number_format($consultants_charge,2,'.',''),'total_amount'=>number_format($consultants_charge+$kit_amount,2,'.', ''),'discount'=>$discount_total,'net_amount'=>number_format($kit_amount+$consultants_charge-$post['discount'],2,'.', ''),'paid_amount'=>number_format($kit_amount+$consultants_charge-$post['discount'],2,'.', ''),'balance'=>$balance);

        $json = json_encode($pay_arr,true);
         echo $json;
         
       }
    }

    public function package_rate()
    {
       $post = $this->input->post();
      
       if(isset($post) && !empty($post) && !empty($post['package_id']))
       {
         $package_rate="";
         $total_amount = 0;
         $package_id = $post['package_id'];
         $this->load->model('general/general_model');
         $package_data = $this->general_model->package_list($package_id);
         $kit_amount = $package_data[0]->amount;
         
         if(!empty($post['discount']))
         {
          $discount = $post['discount'];
         }
         else
         {
            $discount='0.00';
         }
         $paid_amount = $post['paid_amount'];
         $consultants_charge = $post['consultants_charge'];

         $balance = number_format($consultants_charge+$kit_amount-$discount-$post['paid_amount'],2,'.', ''); 
         $amount_arr = array('kit_amount'=>number_format($kit_amount,2,'.',''),'consultants_charge'=>number_format($consultants_charge,2,'.',''),'total_amount'=>number_format($consultants_charge+$kit_amount,2,'.', ''),'discount'=>number_format($discount,2,'.', ''),'net_amount'=>number_format($kit_amount+$consultants_charge-$discount,2,'.', ''),'paid_amount'=>number_format($kit_amount+$consultants_charge-$discount,2,'.', ''),'balance'=>$balance);

         $json = json_encode($amount_arr,true);
         echo $json;
         
       }
    }

    


    public function calculate_payment()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
         
           $total_amount = $post['total_amount'];
           $consultants_charge = $post['consultants_charge'];
           $kit_amount = $post['kit_amount'];

           $discount = $post['discount'];
           $net_amount = $total_amount-$discount;
           $balance = $post['balance'];
           $paid_amount = $post['paid_amount'];
           $paid_amount = $net_amount;
           $pay_arr = array('consultants_charge'=>number_format($consultants_charge,2,'.', ''),'kit_amount'=>number_format($kit_amount,2,'.', ''),'total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''), 'balance'=>number_format($balance,2,'.', ''), 'paid_amount'=>number_format($paid_amount,2,'.', ''));
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
    }

    public function update_amount()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
           $consultants_charge = $post['consultants_charge'];
           $kit_amount = $post['kit_amount'];
           $discount = $post['discount'];
           $total_amount=$consultants_charge+$kit_amount;
           $net_amount = $total_amount-$discount;
           $paid_amount = $net_amount;
           $pay_arr = array('consultants_charge'=>number_format($consultants_charge,2,'.', ''),'kit_amount'=>number_format($kit_amount,2,'.', ''),'total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''));
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
    }

    

    public function confirm_booking($id)
    {
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->daycare->get_by_id($id);
        $patient_id = $result['patient_id'];
        $total_amount = $result['total_amount'];
        $net_amount = $result['net_amount'];
        $paid_amount = $result['paid_amount'];
        $discount = $result['discount'];
        $balance = $result['balance'];
        $payment_mode = $result['payment_mode'];
        $branch_name = $result['branch_name'];
        
        $cheque_date = $result['cheque_date'];
        if($cheque_date=='0000-00-00 00:00:00')
        {
            $cheque_date='';
        }
        $transaction_no = $result['transaction_no'];
        
        $rate_data = $this->daycare->opd_doctor_rate($result['attended_doctor']);
        if(!empty($total_amount) && $total_amount!='0.00'){ $total_amount = $total_amount; }else { $total_amount = $rate_data; }
        if(!empty($net_amount) && $total_amount!='0.00'){ $net_amount = $net_amount; }else{ $net_amount = $rate_data; }
        if(!empty($paid_amount) && $total_amount!='0.00' && $paid_amount!='0.00'){ $paid_amount = $paid_amount;}else{ $paid_amount = $rate_data;}
        if(!empty($discount) ){ $discount = $discount; }else{ $discount = '0.00'; }
        $balance = $net_amount-$paid_amount;
        
        $data['page_title'] = "Confirm Booking";  
        $post = $this->input->post();

        $data['form_error'] = '';

        //date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time'])) 
        //'booking_time'=>date('H:i:s'),
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'booking_date'=>date('d-m-Y',strtotime($result['booking_date'])),
                                  'total_amount'=>$total_amount, 
                                  'discount'=>$discount,
                                  'net_amount'=>$net_amount,
                                  'paid_amount'=>$paid_amount,
                                  'balance'=>$balance,
                                  'booking_status'=>1,
                                  'branch_name'=>$branch_name,
                                  'cheque_date'=>$cheque_date,
                                  'transaction_no'=>$transaction_no,
                                  'payment_mode'=>$payment_mode,
                                  'patient_id'=>$patient_id
                                  );  
        
        if(!empty($post))
        {   
            
            $data['form_data'] = $this->_validate_booking_confirm();
            if($this->form_validation->run() == TRUE)
            {
                $this->daycare->confirm_booking();
                echo 1;
                return false;
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('day_care/confirm',$data);       
      }
    }


    private function _validate()
    {
        $field_list = mandatory_section_field_list(3);
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();  
        $total_values=array();
        if(isset($post['field_name'])) 
        {
        $count_field_names= count($post['field_name']);  
       
        $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
        $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        } 
        }  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
        $this->form_validation->set_rules('attended_doctor','consultant','trim|required');
        if(!empty($field_list))
        {

          if($field_list[0]['mandatory_field_id']=='27' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
          $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
          }

          if($field_list[1]['mandatory_field_id']=='28' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
          $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
          }
        }
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
        $this->form_validation->set_rules('adhar_no', 'adhar no', 'min_length[12]|max_length[16]'); 
        if($post['payment_mode']=='cheque')
        {
        $this->form_validation->set_rules('branch_name', 'bank name', 'trim|required');
        $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');
        $this->form_validation->set_rules('cheque_date', 'cheque date', 'trim|required');

        }
        else if($post['payment_mode']=='card' || $post['payment_mode']=='neft')
        {
        $this->form_validation->set_rules('transaction_no', 'transaction no', 'trim|required');
        }
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $balance_amount = $this->daycare->check_patient_balance($post['patient_id']); 
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

            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'booking_code'=>$post['booking_code'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        'gender'=>$post['gender'],
                                        'token_type'=>$post['token_type'],
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        'address'=>$post['address'],
                                        'city_id'=>$post['city_id'],
                                        'adhar_no'=>$post['adhar_no'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'booking_date'=>$post['booking_date'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$balance_amount,
                                        'country_code'=>'+91',
                                         "field_name"=>$total_values,
                                        'patient_email'=>$post['patient_email'],
                                        'booking_time'=>$post['booking_time'],
                                        'pay_now'=>$post['pay_now'],
                                        'patient_bp'=>$post['patient_bp'],
                                        'patient_temp'=>$post['patient_temp'],
                                        'patient_weight'=>$post['patient_weight'],
                                        'patient_height'=>$post['patient_height'],
                                        'patient_sop'=>$post['patient_sop'],
                                        'patient_rbs'=>$post['patient_rbs'],
                                        'specialization_id'=>$post['specialization'],
                                        'diseases'=>$post['diseases'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'payment_mode'=>$post['payment_mode'],
                                        //'transaction_no'=>$post['transaction_no'],
                                       // 'cheque_no'=>$post['cheque_no'],
                                       // 'cheque_date'=>$post['cheque_date'],
                                       // 'branch_name'=>$post['branch_name'],
                                       // 'transaction_no'=>$post['transaction_no'],
                                        'next_app_date'=>$post['next_app_date'],
                                        'kit_amount'=>$post['kit_amount'],
                                        'consultants_charge'=>$post['consultants_charge'],
                                        'package_id'=>$post['package_id'],
                                        'source_from'=>$post['source_from'],
                                        'remarks'=>$post['remarks'],
                                        'pannel_type'=>$post['pannel_type'],
                                        'opd_type'=>$post['opd_type'],
                                        "insurance_type"=>$insurance_type,
                                        "insurance_type_id"=>$insurance_type_id,
                                        "ins_company_id"=>$ins_company_id,
                                        "polocy_no"=>$post['polocy_no'],
                                        "tpa_id"=>$post['tpa_id'],
                                        "ins_amount"=>$post['ins_amount'],
                                        "ins_authorization_no"=>$post['ins_authorization_no'],
                                        ); 
            return $data['form_data'];
        }   
    }


    private function _validatebilling()
    {
        $field_list = mandatory_section_field_list(4);
         $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 

        
         if(!empty($field_list)){
            
            if($field_list[0]['mandatory_field_id']=='27' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                 $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }
           
            if($field_list[1]['mandatory_field_id']=='28' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
            }
        }
        if($post['payment_mode']=='cheque'){
                  $this->form_validation->set_rules('branch_name', 'bank name', 'trim|required');
                $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');

            }else if($post['payment_mode']=='card' || $post['payment_mode']=='neft'){
                  $this->form_validation->set_rules('transaction_no', 'transaction no', 'trim|required');
            }
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
       
        $opd_particular_billing_list = $this->session->userdata('opd_particular_billing');
        if(!isset($opd_particular_billing_list) && empty($opd_particular_billing_list))
        {
          $this->form_validation->set_rules('particular_id', 'particular_id', 'trim|callback_check_particular_id');
        }   
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2);
            $balance_amount ="";
            if(!empty($post['patient_id']))
            {
              $balance_amount = $this->daycare->check_patient_balance($post['patient_id']);    
            } 
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'booking_code'=>$post['booking_code'],
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
                                        'patient_email'=>$post['patient_email'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'booking_date'=>$post['booking_date'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$balance_amount,
                                        'type'=>$post['type'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'branch_name'=>$post['branch_name'],
                                       'transaction_no'=>$post['transaction_no'],
                                       'cheque_no'=>$post['cheque_no'],
                                       'cheque_date'=>$post['cheque_date'],
                                       'payment_mode'=>$post['payment_mode'],
                                          "country_code"=>"+91"
                                       ); 
            return $data['form_data'];
        }   
    }


    

    ///////
    public function check_particular_id()
    {
       $opd_particular_billing_list = $this->session->userdata('opd_particular_billing');
       if(isset($opd_particular_billing_list) && !empty($opd_particular_billing_list))
       {
          return true;
       }
       else
       {
          $this->form_validation->set_message('check_particular_id', 'Please select a particular.');
          return false;
       }
    }
    

    private function _validate_booking_confirm()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('total_amount', 'total amount', 'trim|required|callback_float_validation'); 
        $this->form_validation->set_rules('net_amount', 'net amount', 'trim|required'); 
        $this->form_validation->set_rules('paid_amount', 'paid amount', 'trim|required');
        $this->form_validation->set_rules('paid_amount', 'paid amount', 'trim|required');  
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'total_amount'=>$post['total_amount'], 
                                        'net_amount'=>$post['net_amount'],
                                        'discount'=>$post['discount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'balance'=>$post['balance']
                                        
                                       ); 
            return $data['form_data'];
        }   
    }

  public function float_validation($str)
    {
         if($str!='0.00'  || $str!='0' )
         {
            return true;
         }
         else
         {
            $this->form_validation->set_message('float_validation', 'Please add doctor rate first.');
            return false;
            
        }   
    }

    //opd Prescription 

    public function prescription($booking_id="")
    {
        $data['prescription_tab_setting'] = get_prescription_tab_setting();
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_tab_setting();

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
           $this->load->model('day_care/opd_model');
           $this->load->model('patient/patient_model');
           $opd_booking_data = $this->opd_model->get_by_id($booking_id);
           //echo "<pre>";print_r($opd_booking_data); exit;
           if(!empty($opd_booking_data))
           {
               
               //present age of patient
               
              if($opd_booking_data['dob']=='1970-01-01' || $opd_booking_data['dob']=="0000-00-00")
              {
                $present_age = get_patient_present_age('',$opd_booking_data);
                //echo "<pre>"; print_r($present_age);
              }
              else
              {
                $dob=date('d-m-Y',strtotime($opd_booking_data['dob']));
                
                $present_age = get_patient_present_age($dob,$opd_booking_data);
              }
              
              $age_y = $present_age['age_y'];
              $age_m = $present_age['age_m'];
              $age_d = $present_age['age_d'];
              //$age_h = $present_age['age_h'];
              //present age of patient
              $booking_id = $opd_booking_data['id'];
              $referral_doctor = $opd_booking_data['referral_doctor'];
              $booking_code = $opd_booking_data['booking_code'];
              $attended_doctor = $opd_booking_data['attended_doctor'];
              $patient_id = $opd_booking_data['patient_id'];//
              $simulation_id = $opd_booking_data['simulation_id'];
              $patient_code = $opd_booking_data['patient_code'];
              $patient_name = $opd_booking_data['patient_name'];
              $mobile_no = $opd_booking_data['mobile_no'];
              $gender = $opd_booking_data['gender'];
              /*$age_y = $opd_booking_data['age_y'];
              $age_m = $opd_booking_data['age_m'];
              $age_d = $opd_booking_data['age_d'];*/
              $address = $opd_booking_data['address'];
              $city_id = $opd_booking_data['city_id'];
              $state_id = $opd_booking_data['state_id'];
              $country_id = $opd_booking_data['country_id']; 
              $appointment_date = $opd_booking_data['appointment_date'];
              
              $relation_name = $opd_booking_data['relation_name'];
              $relation_type = $opd_booking_data['relation_type'];
              $relation_simulation_id = $opd_booking_data['relation_simulation_id'];
              
              $aadhaar_no ='';
              if(!empty($opd_booking_data['adhar_no']))
              {
                $aadhaar_no = $opd_booking_data['adhar_no'];
              }

              $patient_bp = $opd_booking_data['patient_bp'];
              $patient_temp = $opd_booking_data['patient_temp'];
              $patient_weight = $opd_booking_data['patient_weight'];
              $patient_height = $opd_booking_data['patient_height'];
              $patient_spo2 = $opd_booking_data['patient_spo2'];
              $patient_rbs = $opd_booking_data['patient_rbs'];
          }


        }
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->daycare->referal_doctor_list();
        $data['attended_doctor_list'] = $this->daycare->attended_doctor_list();
        $data['employee_list'] = $this->daycare->employee_list();
        $data['profile_list'] = $this->daycare->profile_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['chief_complaints'] = $this->general_model->chief_complaint_list(); 
        $data['examination_list'] = $this->daycare->examinations_list();
        $data['diagnosis_list'] = $this->daycare->diagnosis_list();  
        $data['suggetion_list'] = $this->daycare->suggetion_list();  
        $data['prv_history'] = $this->daycare->prv_history_list();  
        $data['personal_history'] = $this->daycare->personal_history_list();
        $data['template_list'] = $this->daycare->template_list();    
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['page_title'] = "Prescription";
                
        $post = $this->input->post();
       
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'booking_id'=>$booking_id,
                                  'attended_doctor'=>$attended_doctor,
                                  'appointment_date'=>$appointment_date,
                                  'patient_code'=>$patient_code,
                                  'booking_code'=>$booking_code,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  "aadhaar_no"=>$aadhaar_no,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'dept_id'=>"",
                                  'address'=>$address,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'patient_bp'=>$patient_bp,
                                  'patient_temp'=>$patient_temp,
                                  'patient_weight'=>$patient_weight,
                                  'patient_spo2'=>$patient_spo2,
                                  'patient_height'=>$patient_height,
                                  'patient_rbs'=>$patient_rbs,
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
          //echo "<pre>"; print_r($post); exit;
          /*$this->opd->save_prescription();
          $this->session->set_flashdata('success','Prescription successfully added.');
          redirect(base_url('prescription'));*/

          $prescription_id = $this->daycare->save_prescription();
          $this->session->set_userdata('opd_prescription_id',$prescription_id);

          //send notification to device
          $this->load->model('general/general_model');  
          //push notification to users id 
          $usersID = $this->general_model->get_users_details($patient_id);
          $receiver_device_details= $this->general_model->get_device_detail($usersID);
          //echo "<pre>";print_r($receiver_device_details); exit;
          if(!empty($receiver_device_details))
          {
            require APPPATH . '/libraries/PushNotification.php';
            $sms_msg=rawurlencode('Prescription successfully added.');
            foreach($receiver_device_details as $receiver_device_detail)
            {
              //echo $receiver_device_detail->device_token; exit;
              $token=array($receiver_device_detail->device_token);
              $sms_msg=urldecode($sms_msg);
              $order_id='';
              $serverObject = new PushNotification(); 
              if($receiver_device_detail->device_type=='ios'){
                $jsonString = $serverObject->sendPushNotificationToFCMSever( $token, $sms_msg, $order_id );  
                
              }
              elseif($receiver_device_detail->device_type=='android'){
                $jsonString = $serverObject->sendPushNotificationToAndroidSever( $token, $sms_msg, $order_id );  
                //echo "<pre>";print_r($jsonString); 
              }

              

            } 

          }

          $this->session->set_flashdata('success','Prescription successfully added.');
          redirect(base_url('prescription/?status=print'));


        }   

         $this->load->view('day_care/prescription',$data);
    }

    
    function get_template_data($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->daycare->template_data($template_id);
        echo $templatedata;
      }
    }



    function get_template_test_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->daycare->get_template_test_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_template_prescription_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->daycare->get_template_prescription_data($template_id);
        
        echo $templatetestdata;
      }
    }
    

   function complaints_name($complaints_id="")
   {
      if($complaints_id>0)
      {
         $complaintsname = $this->daycare->compalaints_list($complaints_id);
         echo $complaintsname;
      }
    }

   

    function examination_name($examination_id="")
    {
      if($examination_id>0)
      {
         $examination_name = $this->daycare->examination_list($examination_id);
         echo $examination_name;
      }
    }

    function diagnosis_name($diagnosis_id="")
    {
      if($diagnosis_id>0)
      {
         $diagnosis_name = $this->daycare->diagnosis_name($diagnosis_id);
         echo $diagnosis_name;
      }
    }
    //hms_opd_suggetion
    function suggetion_name($suggetion_id="")
    {
      if($suggetion_id>0)
      {
         $suggetion_name = $this->daycare->suggetion_name($suggetion_id);
         echo $suggetion_name;
      }
    }

    function personal_history_name($personal_his_id="")
    {
      if($personal_his_id>0)
      {
         $personal_his_name = $this->daycare->personal_history_name($personal_his_id);
         echo $personal_his_name;
      }
    }

    function prv_history_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->daycare->prv_history_name($pre_id);
         echo $pre_name;
      }
    }


    public function particular_billing_list()
    {
        unauthorise_permission(85,121);
        $data['page_title'] = 'Day Care Particular Billing list'; 
        $this->load->view('day_care/particular_billing_list',$data);
    }

    

    public function billing($pid="")
    {
        unauthorise_permission(85,530);
        //$this->session->unset_userdata('billing_data_array');
        $this->load->model('general/general_model');  
        $post = $this->input->post();
        $patient_id = "";
        $patient_code = "";
        $simulation_id = "";
        $patient_name = "";
        $mobile_no = "";
        $gender = "1";
        $age_y = "";
        $age_m = "";
        $age_d = "";
        $address = "";
        $patient_email="";
        $quantity="1";
        $amount ="";
        $charges = "";
        $balance_amount = '0.00';
        $particulars ="";
        $city_id='';
        $state_id="";
        $country_id="99";
        $attended_doctor="";
        $diseases="";
        $type=1;
        $ref_by_other ="";
        $referral_doctor="";
        if($pid>0)
        {
           $this->load->model('patient/patient_model');
           $patient_data = $this->patient_model->get_by_id($pid);
           if(!empty($patient_data))
           {
              $patient_id = $patient_data['id'];
              $patient_email = $patient_data['patient_email'];
              $patient_code = $patient_data['patient_code'];
              $simulation_id = $patient_data['simulation_id'];
              $patient_name = $patient_data['patient_name'];
              $mobile_no = $patient_data['mobile_no'];
              $gender = $patient_data['gender'];
              $age_y = $patient_data['age_y'];
              $age_m = $patient_data['age_m'];
              $age_d = $patient_data['age_d'];
              $address = $patient_data['address'];
              $city_id = $patient_data['city_id'];
              $state_id = $patient_data['state_id'];
              $country_id = $patient_data['country_id'];
              $patient_email = $patient_data['patient_email'];

              /*$charges = $patient_data['charges'];
              $amount = $form_data['amount'];
              $quantity  = $form_data['quantity'];*/
           }
        }
        $data['diseases_list'] = $this->daycare->diseases_list();

            $data['simulation_list'] = $this->general_model->simulation_list();
            $data['particulars_list'] = $this->general_model->particulars_list();
            $data['country_list'] = $this->general_model->country_list();
        $data['referal_doctor_list'] = $this->daycare->referal_doctor_list();
            $data['page_title'] = "Day Care Billings";  
        $post = $this->input->post();
        if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }
        
        $booking_code = generate_unique_id(45);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'patient_code'=>$patient_code,
                                  'booking_code'=>$booking_code,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'dept_id'=>"",
                                  'attended_doctor'=>$attended_doctor,
                                  'address'=>$address,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'patient_email'=>$patient_email,
                                  'booking_date'=>date('d-m-Y'),
                                  'charges'=>$charges,  
                                  'total_amount'=>"0.00",
                                  'net_amount'=>"0.00",
                                  'paid_amount'=>"0.00",
                                  'discount'=>"0.00",
                                  'particulars'=>'',
                                  'quantity'=>$quantity,
                                  'amount'=>$amount,
                                  'balance'=>$balance_amount,
                                  'branch_name'=>'',
                                  'transaction_no'=>'',
                                  'cheque_no'=>'',
                                  'cheque_date'=>'',
                                  'type'=>$type,
                                  'diseases'=>$diseases,
                                  "ref_by_other"=>$ref_by_other,
                                  'referral_doctor'=>$referral_doctor,
                                     "country_code"=>"+91"
                                  );    

        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validatebilling();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->daycare->save_booking();
                $this->session->set_userdata('day_booking_id',$booking_id);
                $this->session->set_flashdata('success','Day Care Particular successfully saved.');
                redirect(base_url('day_care/?status=print&type=1'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('day_care/billing',$data); 
    }


    public function particular_calculation()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
           $charges = $post['charges'];
           $quantity = $post['quantity'];
           $amount = ($charges*$quantity);
           //$amount = number_format($net_amount,2);
           $pay_arr = array('charges'=>$charges, 'amount'=>$amount,'quantity'=>$quantity);
           $json = json_encode($pay_arr,true);
           echo $json;
       }
    }


    public function particular_payment_calculation()
    {
       //print_r($this->session->all_userdata()); exit;
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {   
            $billing_particular = $this->session->userdata('opd_particular_billing'); 
            if(isset($billing_particular) && !empty($billing_particular))
            {
                $billing_particular = $billing_particular; 
            }
            else
            {
                $billing_particular = [];
            }

            $billing_particular[$post['particular']] = array('particular'=>$post['particular'], 'quantity'=>$post['quantity'], 'amount'=>$post['amount'],'particulars'=>$post['particulars']);
            $amount_arr = array_column($billing_particular, 'amount'); 
            $total_amount = array_sum($amount_arr);
            
            $this->session->set_userdata('opd_particular_billing', $billing_particular);
            $html_data = $this->opd_perticuller_list();
            
            $response_data = array('html_data'=>$html_data, 'total_amount'=>$total_amount, 'net_amount'=>$total_amount-$post['discount'], 'discount'=>$post['discount']);
            
            $opd_particular_payment_array = array('total_amount'=>$total_amount, 'net_amount'=>$total_amount-$post['discount'], 'discount'=>$post['discount']);
            

            $this->session->set_userdata('opd_particular_payment', $opd_particular_payment_array);
            $json = json_encode($response_data,true);
            echo $json;
            
       }
    }

     public function particular_payment_disc()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
           $discount = $post['discount'];
           $total_amount = $post['total_amount'];
           $net_amount = $total_amount-$discount;
           $balance = $post['balance'];
           $paid_amount = $post['paid_amount'];
           $paid_amount = $net_amount;
           $pay_arr = array('total_amount'=>$total_amount, 'net_amount'=>$net_amount, 'discount'=>$discount, 'balance'=>$balance, 'paid_amount'=>$paid_amount);
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
    }

    public function remove_opd_particular()
    {
       $post =  $this->input->post();
       
       if(isset($post['particular_id']) && !empty($post['particular_id']))
       {
           $opd_particular_billing = $this->session->userdata('opd_particular_billing'); 
           $particular_id_list = array_column($opd_particular_billing, 'particular');
           foreach($post['particular_id'] as $post_perticuler)
           {
              if(in_array($post_perticuler,$particular_id_list))
              { 
                 unset($opd_particular_billing[$post_perticuler]);
              }
           }  

            $amount_arr = array_column($opd_particular_billing, 'amount'); 
            $total_amount = array_sum($amount_arr);
            $this->session->set_userdata('opd_particular_billing',$opd_particular_billing);
            $html_data = $this->opd_perticuller_list();
            $response_data = array('html_data'=>$html_data, 'total_amount'=>$total_amount, 'net_amount'=>$total_amount, 'discount'=>0);
            $json = json_encode($response_data,true);
            echo $json;
       }
    }

    private function opd_perticuller_list()
    {
        $particular_data = $this->session->userdata('opd_particular_billing');
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
                    <th>Particular</th>
                    <th>Charges</th>
                     <th>Quantity</th>
                  </tr></thead>';  
           if(isset($particular_data) && !empty($particular_data))
           {
              $i = 1;
              foreach($particular_data as $particulardata)
              {
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="particular_id[]" class="part_checkbox booked_checkbox" value="'.$particulardata['particular'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$particulardata['particulars'].'</td>
                            <td>'.$particulardata['amount'].'</td>
                            <td>'.$particulardata['quantity'].'</td>
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Particular data not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }


    public function print_booking_report($id="",$branch_id='')
    {
      
      $data['page_title'] = "Day Care Print Booking";
      $booking_id= $this->session->userdata('day_booking_id');
      if(!empty($id))
      {
        $booking_id = $id;
      }
      else if(isset($booking_id) && !empty($booking_id))
      {
        $booking_id =$booking_id;
      }
      else
      {
        $booking_id = '';
      } 

     
      $get_by_id_data = $this->daycare->get_all_detail_print($booking_id,$branch_id);
      //print_r($get_by_id_data);die;
      $template_format = $this->daycare->template_format(array('section_id'=>14,'types'=>1),$branch_id);
     //print_r($template_format); exit;
      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Day Care Booking';
     // print_r($data);die;
      $this->load->view('day_care/print_template_day_care',$data);
    }

    public function print_blank_booking_report($id="",$type='')
    {
      
      $booking_id= $this->session->userdata('day_care_search');
      if(!empty($id))
      {
        $booking_id = $id;
      }
      else if(isset($booking_id) && !empty($booking_id))
      {
        $booking_id =$booking_id;
      }
      else
      {
        $booking_id = '';
      }
      if($type=='1')
      {
        $data['page_type'] = 'Billing';  
      }
      else
      {
        $data['page_type'] = 'Booking';
      } 
      $get_by_id_data = $this->daycare->get_all_detail_print($booking_id);
      $template_format = $this->daycare->template_format(array('section_id'=>14,'types'=>1));
      $data['template_data']=$template_format;
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_by_id_data['payment_mode']);
      $data['all_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;
      //print '<pre>';print_r($get_by_id_data); exit;
      //print '<pre>';print_r($data['all_detail']); exit;
      $this->load->view('day_care/print_blank_template_opd',$data);
    }

    


    public function advance_search()
    {
        
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->daycare->referal_doctor_list();
        $data['attended_doctor_list'] = $this->daycare->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['source_list'] = $this->daycare->source_list();  
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list(); 
        $data['form_data'] = array(
                                    "start_date"=>'',//date('d-m-Y')
                                    "end_date"=>'',//date('d-m-Y')
                                    "booking_from_date"=>'',
                                    "booking_to_date"=>'',
                                    "appointment_from_date"=>'',
                                    "appointment_to_date"=>'',
                                    "patient_code"=>"", 
                                    "patient_name"=>"",
                                    "simulation_id"=>"",
                                    "mobile_no"=>"",
                                    "gender"=>"",
                                    "age_y"=>"",
                                    "age_m"=>"",
                                    "age_d"=>"",
                                    "dob"=>"",
                                    "address"=>"",
                                    "city_id"=>"",
                                    "state_id"=>"",
                                    "country_id"=>"99",
                                    "pincode"=>"",
                                    "amount_from"=>"",
                                    "amount_to"=>"",
                                    "paid_amount_from"=>"",
                                    "paid_amount_to"=>"",
                                    "booking_code"=>"",
                                    "referral_doctor"=>"",
                                    "specialization_id"=>"",
                                    "attended_doctor"=>"",
                                    "patient_email"=>"",
                                    "start_time"=>"",
                                    "end_time"=>"",
                                    "booking_status"=>"",
                                    "insurance_type"=>"",
                                    "insurance_type_id"=>"",
                                    "ins_company_id"=>"",
                                    "status"=>"", 
                                    "remark"=>"",
                                    "branch_id"=>'',
                                    'source_from'=>'',
                                    "disease"=>"",
                                    "disease_code"=>"",
                                  );
        if(isset($post) && !empty($post))
        {
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('day_care_search', $marge_post);

        }
        $day_care_search = $this->session->userdata('day_care_search');
        if(isset($day_care_search) && !empty($day_care_search))
        {
            $data['form_data'] = $day_care_search;
        }
        $this->load->view('day_care/advance_search',$data);
    }

    public function get_branch_data($branch_id)
    {
        if($branch_id>0)
        {
          $branchdata = $this->daycare->branch_data($branch_id);
          
          echo $branchdata;
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('day_care_search');
    }

    function get_simulation_data($branch_id="")
    {
      if($branch_id>0)
      {
        $simulationdata = $this->daycare->get_simulation_data($branch_id);
        echo $simulationdata;
      }
    }

    function get_referral_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $doctor_data = $this->daycare->get_referral_doctor_data($branch_id);
        echo $doctor_data;
      }
    }

    function get_specialization_data($branch_id="")
    {
      if($branch_id>0)
      {
        $specialization_data = $this->daycare->get_specialization_data($branch_id);
        echo $specialization_data;
      }
    }

    function get_attended_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $attended_data = $this->daycare->get_attended_doctor_data($branch_id);
        echo $attended_data;
      }
    }





    public function day_care_excel()
    {
       // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $data_patient_reg = get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array('Day Care No.','Patient Name',$data_patient_reg,'Booking Date','Age','Gender','Mobile','Doctor Name','Specialization');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          foreach ($fields as $field)
          {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
              $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
             
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          $list = $this->daycare->search_opd_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $opds)
               {
                    if($opds->booking_status==0)
                    {
                         $booking_status = '<font color="red">Pending</font>';
                    }   
                    elseif($opds->booking_status==1){
                         $booking_status = '<font color="green">Confirm</font>';
                    }                 
                    elseif($opds->booking_status==2){
                         $booking_status = '<font color="blue">Attended</font>';
                    } 
                    $attended_doctor_name ="";
                    $attended_doctor_name = get_doctor_name($opds->attended_doctor);
                    $specialization_id = get_specilization_name($opds->specialization_id);
                    $booking_code = $opds->booking_code;
                    $patient_name = $opds->patient_name;
                    $booking_date = date('d M Y',strtotime($opds->booking_date));
                    if($opds->appointment_date!='0000-00-00')
                    {
                         $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
                    }
                    else
                    {
                         $appointment_date = ""; 
                    }
                    $genders = array('0'=>'Female','1'=>'Male','2'=>'Other');
                    $gender = $genders[$opds->gender];
                    $age_y = $opds->age_y;
                    $age_m = $opds->age_m;
                    $age_d = $opds->age_d;
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
                    $patient_age =  $age;
                    array_push($rowData,$opds->booking_code,$opds->patient_name,$opds->patient_code,$booking_date,$patient_age,$opds->gender,$opds->mobile_no,$attended_doctor_name,$specialization_id);
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
          header("Content-Disposition: attachment; filename=day_care_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function day_care_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $data_patient_reg = get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array('OPD NO.','Patient Name',$data_patient_reg,'Booking Date','Age','Gender','Mobile','Doctor Name','Specialization');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->daycare->search_opd_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $opds)
               {
                    if($opds->booking_status==0)
                    {
                         $booking_status = '<font color="red">Pending</font>';
                    }   
                    elseif($opds->booking_status==1){
                         $booking_status = '<font color="green">Confirm</font>';
                    }                 
                    elseif($opds->booking_status==2){
                         $booking_status = '<font color="blue">Attended</font>';
                    } 
                    $attended_doctor_name ="";
                    $attended_doctor_name = get_doctor_name($opds->attended_doctor);
                    $specialization_id = get_specilization_name($opds->specialization_id);
                    $booking_code = $opds->booking_code;
                    $patient_name = $opds->patient_name;
                    $booking_date = date('d M Y',strtotime($opds->booking_date));
                    if($opds->appointment_date!='0000-00-00')
                    {
                         $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
                    }
                    else
                    {
                         $appointment_date = ""; 
                    }
                    $genders = array('0'=>'Female','1'=>'Male','2'=>'Other');
                    $gender = $genders[$opds->gender];
                    $age_y = $opds->age_y;
                    $age_m = $opds->age_m;
                    $age_d = $opds->age_d;
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
                    $patient_age =  $age;
                    array_push($rowData,$opds->booking_code,$opds->patient_name,$opds->patient_code,$booking_date,$patient_age,$opds->gender,$opds->mobile_no,$attended_doctor_name,$specialization_id);
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
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
         header("Content-Disposition: attachment; filename=day_care_list_".time().".csv");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }
         
        
    }

    public function day_care_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->daycare->search_opd_data();
        $this->load->view('day_care/day_care_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("day_care_list_".time().".pdf");
    }
    public function day_care_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->daycare->get_datatables();
      $this->load->view('day_care/day_care_html',$data); 
    }

    /*public function saved_reffered_doctor_id(){
          $post = $this->input->post();
          if(isset($post) && !empty($post)){
               $this->session->set_userdata('referral_doctor_id',$post['referal_doctor_id']);
          }
          $referral_doctor_id = $this->session->userdata('referral_doctor_id');
          echo $referral_doctor_id;
     }*/


    function get_test_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->daycare->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_medicine_auto_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->daycare->get_medicine_auto_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_dosage_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->daycare->get_dosage_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_type_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->daycare->get_type_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    

    function get_duration_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->daycare->get_duration_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_frequency_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->daycare->get_frequency_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }  


    function get_advice_vals($vals="")
    {
        
        if(!empty($vals))
        {
            $result = $this->daycare->get_advice_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    function get_diseases_data($branch_id="")
    {
      if($branch_id>0)
      {
        $diseases_data = $this->daycare->get_diseases_data($branch_id);
        echo $diseases_data;
      }
    }

    function get_package_data($branch_id="")
    {
      if($branch_id>0)
      {
        $this->load->model('general/general_model'); 
        $package_data = $this->general_model->get_package_data($branch_id);
        echo $package_data;
      }
    }

    

    function get_source_from_data($branch_id="")
    {
      if($branch_id>0)
      {
        $source_from_data = $this->daycare->get_source_from_data($branch_id);
        echo $source_from_data;
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

        $html.='<div class="row m-b-5"><div class="col-md-12"><div class="row"><div class="col-md-5"><b>'.$payment_detail->field_name.'<span class="star">*</span></b></div> <div class="col-md-7"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div></div></div>';
          }
        echo $html;exit;

    }  
    
  public function checkbox_list_save()
  {
   // print_r($this->input->post());
    $users_data = $this->session->userdata('auth_users');
    $col_ids_array=$this->input->post('rec_id');
    $module_id=$this->input->post('module_id');
    $branch_id=$users_data['parent_id'];
    $this->daycare->delete_existing_branch_list_cols($module_id,$branch_id);
    $this->daycare->insert_new_cols_branch_list_cols($branch_id, $module_id, $col_ids_array);
    echo "Record Inserted Successfully";
  }

  public function reset_coloumn_record()
  {
    $module_id=$this->input->post('module_id');
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    $this->daycare->delete_existing_branch_list_cols($module_id,$branch_id);
    echo json_encode(array("status"=>200));
  }

  public function update_doctor_status($opd_id="",$status_type="")
  {
    $update_status= $this->daycare->update_doctor_status($opd_id,$status_type);
    echo $update_status;exit;
  }

  public function get_validity_date_in_between()
  {
      $doctor_id= $this->input->post('doctor_id');
      $booking_date= $this->input->post('booking_date');
      $patient_id= $this->input->post('patient_id');
      $result= $this->daycare->get_validity_date_in_between($doctor_id,$booking_date,$patient_id);
      echo $result;die;
  }
  public function get_validate_date()
  {
      $doctor_id= $this->input->post('doctor_id');
      $booking_date= $this->input->post('booking_date');
      $patient_id= $this->input->post('patient_id');
      $result= $this->daycare->get_validate_date($doctor_id,$booking_date,$patient_id);
      echo json_encode(array('date'=>$result));exit;
  }
  
  
  /* sample collected data */
      public function sample_import_opd_excel()
      {
              //unauthorise_permission(97,627);
              // Starting the PHPExcel library
              $this->load->library('excel');
              $this->excel->IO_factory();
              $objPHPExcel = new PHPExcel();
              $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
              $objPHPExcel->setActiveSheetIndex(0);
              $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(50);
              // Field names in the first row

              $fields = array('Day Care No.','Patient Name','UHID No.','Booking Date','Age','Gender','Mobile','Doctor Name','Specialization');

              $col = 0;
              foreach ($fields as $field)
              {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
   
              $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);

              $col++;

              }
              $rowData = array();
              $data= array();
              // Fetching the table data
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel charset=UTF-8');
              header("Content-Disposition: attachment;filename=day_care_import_sample_".time().".xls");  
              header("Pragma: no-cache"); 
              header("Expires: 0");
              ob_end_clean();
              $objWriter->save('php://output');
             
      }

/* sample collected data */
      public function import_opd_excel()
      {


        //unauthorise_permission(97,628);
        $this->load->library('form_validation');
        $this->load->library('excel');
        $data['page_title'] = 'Import Day Care excel';
        $arr_data = array();
        $header = array();
        $path='';

       
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['opd_list']) || $_FILES['opd_list']['error']>0)
            {
               
               $this->form_validation->set_rules('opd_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'opd/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('opd_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 

                    echo "<pre> dfd";print_r(DIR_UPLOAD_PATH.'opd/'); exit;
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];

                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }

              
                if(!empty($arr_data))
                {
                    //echo '<pre>'; print_r($arr_data); exit;
                    $arrs_data = array_values($arr_data);
                    $total_patient = count($arrs_data);
                    $array_keys = array('booking_code','patient_code','simulation_id','patient_name','mobile_no','address','gender','age_y','age_m','age_d','age_h','remarks','doctor_name','specialization_id','payment_mode','total_amount','discount','paid_amount','created_date');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S');
                    $patient_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $opd_all_data= array();
                    for($i=0;$i<$total_patient;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $opd_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $opd_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                   // echo 'dsd';die;
                   $this->daycare->save_all_opd_data($opd_all_data);
                }
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {

                $data['form_error'] = validation_errors();
                

            }
        }

        $this->load->view('day_care/import_opd_excel',$data);
    } 
// 30/07/2019 mlc print
    function mlc_print($id="",$branch_id='')
    {
      if(!empty($id))
      {
        $day_booking_id = $id;
      }
      else if(isset($day_booking_id) && !empty($day_booking_id))
      {
        $day_booking_id =$day_booking_id;
      }
      else
      {
        $day_booking_id = '';
      } 
       $get_by_id_data = $this->daycare->get_all_detail_print($day_booking_id,$branch_id);
      // echo "<pre>"; print_r($get_by_id_data);die;
      $data['get_by_id_data']=$get_by_id_data;
      $data['booking_id']=$day_booking_id;
      $this->load->view('day_care/mlc_form_opd',$data);
    }
    



    public function partograph_pres($booking_id="")
    {
     //echo "<pre>"; print_r($_POST);die();
        $data['partograph_tab_setting'] = get_partograph_tab_setting();
       
        $this->load->model('general/general_model'); 
        $this->load->model('partograph/partograph_model','partograph'); 

        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $post = $this->input->post();
        $patient_id = "";
        $patient_code = "";
        $booking_code='';
        $simulation_id = "";
        $patient_name = "";
        $mobile_no = "";
        $gender = "";
        $age_y = "";
        $gravida = "";
        $para = "";
       
      
        if($booking_id>0)
        {
           $this->load->model('patient/patient_model');
           $opd_booking_data = $this->daycare->get_by_id($booking_id);
         
           if(!empty($opd_booking_data))
           {
               
              if($opd_booking_data['dob']=='1970-01-01' || $opd_booking_data['dob']=="0000-00-00")
              {
                $present_age = get_patient_present_age('',$opd_booking_data);
              }
              else
              {
                $dob=date('d-m-Y',strtotime($opd_booking_data['dob']));
                
                $present_age = get_patient_present_age($dob,$opd_booking_data);
              }
              
              $age_y = $present_age['age_y'];
              $booking_id = $opd_booking_data['id'];
              $booking_code = $opd_booking_data['booking_code'];
              $booking_date = $opd_booking_data['booking_date'];
              $booking_time = $opd_booking_data['booking_time'];
              $attended_doctor = $opd_booking_data['attended_doctor'];
              $patient_id = $opd_booking_data['patient_id'];//
              $simulation_id = $opd_booking_data['simulation_id'];
              $patient_code = $opd_booking_data['patient_code'];
              $patient_name = $opd_booking_data['patient_name'];
              $mobile_no = $opd_booking_data['mobile_no'];
              $gender = $opd_booking_data['gender'];
              $gravida = $opd_booking_data['gravida'];
              $para = $opd_booking_data['para'];
              
              $appointment_date = $opd_booking_data['appointment_date'];
              
              $relation_name = $opd_booking_data['relation_name'];
              $relation_type = $opd_booking_data['relation_type'];
              $relation_simulation_id = $opd_booking_data['relation_simulation_id'];
              
              $aadhaar_no ='';
              if(!empty($opd_booking_data['adhar_no']))
              {
                $aadhaar_no = $opd_booking_data['adhar_no'];
              }
          }


        }
        // $data['simulation_list'] = $this->general_model->simulation_list();
        // $data['foetal_history'] = $this->partograph->foetal_history();
        // $data['temp_history'] = $this->partograph->temp_history();
        // $data['cervic_history'] = $this->partograph->cervic_history(); 
        // $data['contraction_history'] = $this->partograph->contraction_history();
        // $data['drugs_lv_history'] = $this->partograph->drugs_lv_history();  
        // $data['pulse_bp_history'] = $this->partograph->pulse_bp_history();  
        // $data['aminoitic_history'] = $this->partograph->aminoitic_history();    
        $data['page_title'] = "Partograph";
                
        $post = $this->input->post();
       
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'booking_id'=>$booking_id,
                                  'attended_doctor'=>$attended_doctor,
                                  'appointment_date'=>$appointment_date,
                                  'patient_code'=>$patient_code,
                                  'booking_code'=>$booking_code,
                                  'booking_date'=>$booking_date,
                                  'booking_time'=>$booking_time,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  "aadhaar_no"=>$aadhaar_no,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'age_y'=>$age_y,
                                  'gravida'=>$gravida,
                                  'para'=>$para,
                                  'foetal_history'=>"",
                                  'temp_history'=>"",
                                  'cervic_history'=>'',
                                  'contraction_history'=>'',
                                  'drugs_lv_history'=>'',
                                  'pulse_bp_history'=>'',
                                  'aminoitic_history'=>'',
                                  'next_appointment_date'=>"",
                                  "relation_name"=>$relation_name,
                                  "relation_type"=>$relation_type,
                                  "relation_simulation_id"=>$relation_simulation_id,
                                  );
        if(isset($post) && !empty($post))
        {   
          $parto_id = $this->partograph->save_partograph();
          $this->session->set_userdata('opd_parto_id',$parto_id);
          $this->session->set_flashdata('success','Partograph successfully added.');
          redirect(base_url('partograph'));


        }   

         $this->load->view('partograph/prescription',$data);
    }

    public function dilated_start()
    {
          $result = $this->daycare->dilated_start($_POST['booked_id']);
          $this->session->set_flashdata('success','Dilate started.');
          redirect(base_url('day_care'));
    }

    public function dilated_stop()
    {
          $result = $this->daycare->dilated_stop($_POST['booked_id']);
          $this->session->set_flashdata('success','Dilate stop.');
          return 1;
    }


/* Running bill info 10-1-20*/ 


    public function running_bill_info($opd_id="",$patient_id="")
     {
          $data['opd_id'] = $opd_id;  //total_amount
          $data['patient_id'] = $patient_id;
          $list = $this->daycare->get_running_bill_info_datatables($data['opd_id'],$data['patient_id']);  
          // echo "<pre>"; print_r($list);die;
          $data['running_bill_data']=$list;

          $this->load->model('general/general_model');
          $data['payment_mode']=$this->general_model->payment_mode();
          
          $payamount=$this->daycare->get_paid_amount($data['opd_id'],$data['patient_id']);
          //echo "<pre>";print_r($payamount); exit;
          $data['get_paid_amount']=$payamount[0]->daycare_paid_amount;
          if($payamount[0]->daycare_discount_amount>0)
          {
              $data['total_discount'] = $payamount[0]->daycare_discount_amount;
          }
          else
          {
              $data['total_discount'] = '0.00';
          }
          

          if(!empty($payamount[0]->daycare_discharge_date) &&  $payamount[0]->daycare_discharge_date!='0000-00-00 00:00:00' && date('Y-m-d',strtotime($payamount[0]->daycare_discharge_date))!='1970-01-01')
          {
              $dischargedate = date('d-m-Y h:i A',strtotime($payamount[0]->daycare_discharge_date));
          }   
          else
          {
            $dischargedate = date('d-m-Y h:i A'); 
          }

          if(!empty($payamount[0]->discharge_bill_no))
          {
             $bill_no = $payamount[0]->discharge_bill_no;
          }
          else
          {
            $bill_no = generate_unique_id(60);
          }

          $data['form_data']= array('discharge_date'=>$dischargedate,
                                   'discharge_bill_no'=>$bill_no,
                                    );


          $post = $this->input->post();
          if(isset($post) && !empty($post))
            { 

//echo "<pre>";print_r($payamount); exit;

                $data['form_data'] = $this->_validate_runing_bill();
                if($this->form_validation->run() == TRUE)
                {
                  //echo "dsdsd"; exit;
                    $discharge_bill_id= $this->daycare->save_discharge();
                    $this->session->set_userdata('discharge_bill_id',$discharge_bill_id);
                    $this->session->set_flashdata('success','Discharge Bill has been successfully updated.');
                    redirect(base_url('day_care/running_bill_info/'.$opd_id.'/'.$patient_id.'/?status=print&billstatus=printbill')); // /?status=print

                }
                else
                {

                    $data['form_error'] = validation_errors();

                }  
            }

          $data['page_title'] = 'Day Care Running Bill List';
          $this->load->view('day_care/running_bill_info',$data);
         
     }

     private function _validate_runing_bill()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required');
        if(isset($post['field_name']))
        {
           $this->form_validation->set_rules('field_name[]', 'field', 'trim|required');

        }
       
          $total_values=array(); 

          if(isset($post['field_name']))
          {
            $count_field_names= count($post['field_name']);  

            $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

            for($i=0;$i<$count_field_names;$i++) 
            {
            $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

            }
          }   
        if($this->form_validation->run() == FALSE) 
        {  

              $data['form_data'] = array(
                                        'discharge_date'=>$_POST['discharge_date'],
                                        'discharge_bill_no'=>$_POST['discharge_bill_no'],
                                        "payment_mode"=>$post['payment_mode'],
                                        'field_name'=>$total_values
                                        
                                       ); 
            return $data['form_data'];
        }   
    }

    public function discharge_bill_info()
     {    
          $this->load->model('general/general_model');
          $data['payment_mode']=$this->general_model->payment_mode();
          $data['opd_id'] = $this->input->get('opd_id');
          $data['patient_id'] = $this->input->get('patient_id');
          $list = $this->daycare->get_running_bill_info_datatables($data['opd_id'],$data['patient_id']);  
          // echo "<pre>"; print_r($list);die;
          $data['running_bill_data']=$list;
          $data['page_title'] = 'Day Care Discharge Bill Info';
          $this->load->view('day_care/discharge_bill_info',$data);
         
     }

     function generate_discharge_bill(){
      
     }



  public function delete($id="")
    {  
       if(!empty($id) && $id>0)
       {
           $result = $this->daycare->delete($id);
           $response = "Day Care Running Bill successfully deleted.";
            $this->session->set_flashdata('success','Particulars has been successfully deleted.');
           echo $response;
       }
    }


    public function print_running_bill($opd_id="",$patient_id="")
    {
        $this->load->model('general/general_model');
        $data['get_opd_patient_details']= $this->general_model->get_patient_according_to_day_care($opd_id,$patient_id);
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->daycare->get_running_bill_info_datatables($opd_id,$patient_id);
        $template_format= $this->daycare->template_format_running_bill(array());
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $this->load->view('day_care/print_template_running_bill',$data);
       
    }

    public function print_discharge_bill($opd_id="",$patient_id="")
    {

        $this->load->model('general/general_model');
        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $data['get_opd_patient_details']= $this->general_model->get_patient_according_to_day_care($opd_id,$patient_id);
     //echo "<pre>";print_r($data['get_ipd_patient_details']);die;
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->daycare->get_discharge_bill_info_datatables($opd_id,$patient_id);
        $get_updated_data= $this->daycare->opd_detail_data($opd_id,$patient_id);
        
        $template_format= $this->daycare->template_format_running_bill(array());
        // $template_format= $this->ipd_discharge_bill->template_format(array());
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        
        $this->load->view('day_care/print_template_discharge_bill',$data);
    } 

  public function print_letterhead_running_bill($opd_id="",$patient_id="")
    {
        $this->load->library('m_pdf');
        $this->load->model('general/general_model');
        $data['get_opd_patient_details']= $this->general_model->get_patient_according_to_day_care($opd_id,$patient_id);
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->daycare->get_running_bill_info_datatables($opd_id,$patient_id);
        $template_format= $this->daycare->template_format_running_bill(array());
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $this->load->view('day_care/print_template_running_bill_letterhead',$data);
  
  }


   public function payment_calc_all()
   { 
       
        $post = $this->input->post();
        //echo "<pre>";print_r($post); exit;
        $total_amount = 0;
        $total_vat = 0;
        $total_discount =0;
        $net_amount =0;  
        $purchase_amount=0;
        $total_new_amount=0;
        $tot_discount_amount=0;
        $blance_dues=0;
        $payamount=0;
        $refund_amount=0;
        $new_amount=0;
        $received_amount=0;
        $total_bill_amount=0;
        $i=0;
        
        
        if($post['discount']!='' && $post['discount']!=0)
        {
           $total_discount =$post['discount'];
        }
        else
        { 
           $total_discount=0;
        }
        $total_amount = $post['total_amount'];
        $total_net_amount = $post['total_amount']-$total_discount;
        $net_amount = $total_net_amount;
        $payamount= $post['pay_amount'];//$net_amount;
        $blance_dues=$post['total_amount']-$payamount-$total_discount;
            
     
        
        $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''),'total_amount_bill'=>number_format($total_amount,2,'.',''),'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'balance_due'=>number_format($blance_dues,2,'.',''),'discount'=>$post['discount'],'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       
        
    }

    

     public function print_discharge_bill_reciept($ipd_id="",$patient_id="")
    {

        $this->load->model('general/general_model');
        $data['get_ipd_patient_details']= $this->general_model->get_patient_according_to_opd($ipd_id,$patient_id);
        //print_r($data['get_ipd_patient_details']); exit;

        $data['get_ipd_patient_disc_rec']= $this->general_model->get_patient_ipd_disc_rec($ipd_id,$patient_id);
       $data['discharge_rec_no'] = $data['get_ipd_patient_disc_rec']['bill_reciept_prefix'].$data['get_ipd_patient_disc_rec']['bill_reciept_suffix'];
        $data['page_title'] = "Print Bookings";
        $this->load->model('day_care/day_care_model','daycare_m');
        $ipd_booking_id = $ipd_id;
        $get_by_id_data = $this->daycare_m->get_all_detail_print($ipd_booking_id);
        $branch_id='';
        $users_data = $this->session->userdata('auth_users'); 
        if($users_data['users_role']==3)
        {
          $doctor_id = $users_data['parent_id'];
          $this->load->model('branch/branch_model');
          $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
          $branch_id = $doctor_data[0]->branch_id; 
        } 
        //$template_format = $this->daycare_m->template_format(array('section_id'=>5,'types'=>1),$branch_id);
        $template_format = $this->daycare->template_format(array('section_id'=>14,'types'=>1),$branch_id);
        $get_charge_by_id_data=$this->daycare->get_discharge_bill_info_datatables($ipd_id,$patient_id);
        //echo "<pre>"; print_r($get_charge_by_id_data); exit;
        $get_updated_data= $this->daycare->opd_detail_data($ipd_id,$patient_id);
        //echo "<pre>"; print_r($get_updated_data); exit;
        $data['all_charge_detail']= $get_charge_by_id_data;
        $data['payment_mode'] = $get_by_id_data['opd_list'][0]->payment_mode;
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        $data['page_type'] = 'Booking';
        $this->load->view('day_care/print_ipd_final_reciept_template',$data);
       
    }

// please write code above    
}
?>