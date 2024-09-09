<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();  
        auth_users();  
        $this->load->model('patient/patient_model','patient');
        $this->load->model('patient/document_file_model','document_file');
        $this->load->library('form_validation');
        //$this->load->library('form_validation');
    }

    
    public function index()
    {  
        unauthorise_permission(19,113);
        $this->session->unset_userdata('patient_search');
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

        $data['page_title'] = 'Patient List'; 
        $data['form_data'] = array('patient_code'=>'','patient_name'=>'','adhar_no'=>'','mobile_no'=>'','address'=>'','start_date'=>$start_date, 'end_date'=>$end_date);
        $this->load->view('patient/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(19,113);
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->patient->get_datatables();
        $recordsTotal = $this->patient->count_all();
        $recordsFiltered = $recordsTotal;  //$this->patient->count_filtered();
        //echo "<pre>"; print_r($list);die;   
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $patient) { 
            $no++;
            $row = array();
            if($patient->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($patient->state))
            {
                $state = " ( ".ucfirst(strtolower($patient->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            // $check_script = "<script>$('#selectAll').on('click', function () { 
                                 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                   
            $row[] = '<input type="checkbox" name="patient[]" class="checklist" value="'.$patient->id.'">'.$check_script;
            $relation_name='';
            if(!empty($patient->relation_name))
            {
                $relation_name = $patient->patient_relation." ".$patient->relation_name;  
            }
            $gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');
            $row[] = $patient->patient_code; 
            $row[] = $patient->patient_name;
            $row[] = $relation_name;
            $row[] = $patient->mobile_no;
            $row[] = $gender[$patient->gender];
            ///////////// Age calculation //////////
                $age_y = $patient->age_y;
                $age_m = $patient->age_m;
                $age_d = $patient->age_d;
                $age_h = $patient->age_h;
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
            $row[] = $age; 
            if(!empty($patient->address) && $patient->address!=', , ')
            {
                $row[] = rtrim($patient->address,' ,');
            }
            else
            {
                $row[]="";
            }   
            $row[] = $patient->adhar_no;
            $row[] = $patient->marital_status;
            $row[] = (strtotime($patient->anniversary) > 0 ) ? date('d-m-Y',strtotime($patient->anniversary)) : '';
            $row[] = $patient->religion;
            $row[] = (strtotime($patient->dob) > 0 ) ? date('d-m-Y',strtotime($patient->dob)) : '';
            $row[] = $patient->mother;
            $row[] = $patient->guardian_name;
            $row[] = $patient->guardian_email;
            $row[] = $patient->guardian_phone;
            $row[] = $patient->relation;

            $row[] = $patient->patient_email;
            $row[] = $patient->monthly_income;
            $row[] = $patient->occupation;
            $row[] = $patient->ins_type;

            
            $row[] = $patient->insurance_type;
            $row[] = $patient->insurance_company;
            $row[] = $patient->polocy_no;
            $row[] = $patient->tpa_id;

            $row[] = $patient->ins_amount;
            $row[] = $patient->ins_authorization_no;


            $row[] = date('d-m-Y h:i A', strtotime($patient->created_date));
            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_barcode = ""; 
            $btn_delete = "";
            $btn_booking='';
            $btn_billing ="";
            $btn_appointment="";
            $btn_sales_medicine='';
            $btn_sales_return_medicine='';
            $btn_history='';
            $btn_ipd_booking='';
            $btn_consolidate_payment='';
            $btn_ot_booking = '';
            $btn_ambulance_booking='';
            $btn_day_care='';
            $btn_sales_canteen= '';
            $btn_camp_booking = '';
            $divstr = '<div class="btn-ipd">';
            $enddivstr='</div>';
                if($users_data['parent_id']==$patient->branch_id){
                    if(in_array('115',$users_data['permission']['action'])) 
                    {
                       $btn_edit = ' <a  href="'.base_url('patient/edit/'.$patient->id).'" style="'.$patient->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a> ';
                    }
                }
                if(in_array('358',$users_data['permission']['section'])) 
                {
                    $btn_sales_canteen = '<li><a href="'.base_url('canteen/sale/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="Sale Canteen"><i class="fa fa-plus"></i> Sale Canteen</a></li>';
                } 
                   if(in_array('117',$users_data['permission']['action'])) 
                    {
                       $btn_view = ' <a class="btn-custom" onclick="return view_patient('.$patient->id.','.$patient->branch_id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
                    }
                     
                $barcode_url = "'".base_url('patient/print_barcode/'.$patient->id)."'";
                $btn_barcode = '<li><a onClick="return print_barcode_page('.$barcode_url.')" href="javascript:void(0)" title="Print Barcode" data-url=""><i class="fa fa-barcode"></i>Print Barcode</a></li>';
                
                if($users_data['parent_id']==$patient->branch_id)
                {
                    if(in_array('116',$users_data['permission']['action'])) 
                    {
                       $btn_delete = '<li><a onClick="return delete_patient('.$patient->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a></li>';
                    }
                }
                    if(in_array('588',$users_data['permission']['action'])) 
                    { 
                        $btn_appointment = '<li><a href="'.base_url('appointment/add/'.$patient->id).'" style="'.$patient->id.'" title="Opd Booking"><i class="fa fa-plus"></i> Appointment</a></li>';
                    }
                    
                    if(in_array('523',$users_data['permission']['action'])) 
                    {
                        $btn_booking = '<li><a href="'.base_url('opd/booking/'.$patient->id).'" style="'.$patient->id.'" title="Opd Booking"><i class="fa fa-plus"></i> Opd Booking</a></li>';
                    }
                    if(in_array('319',$users_data['permission']['section'])) 
                    {
                        $btn_camp_booking = '<li><a href="'.base_url('camp/booking/'.$patient->id).'" style="'.$patient->id.'" title="Camp Booking"><i class="fa fa-plus"></i> Camp Booking</a></li>';
                    }
                    if(in_array('387',$users_data['permission']['section'])) 
                    {
                        $btn_day_care = '<li><a href="'.base_url('day_care/booking/'.$patient->id).'" style="'.$patient->id.'" title="Day Care Booking"><i class="fa fa-plus"></i> Day Care Booking</a></li>';
                    }
                    if(in_array('915',$users_data['permission']['action'])) 
                    {
                        $btn_billing = '<li> <a  href="'.base_url('opd_billing/add/'.$patient->id).'" style="'.$patient->id.'" title="Opd Billing"><i class="fa fa-plus"></i> Opd Billing</a></li>';
                    }
                    if(in_array('400',$users_data['permission']['action'])) 
                    {
                        $btn_sales_medicine = ' <li><a  href="'.base_url('sales_medicine/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="Sale Medicine"><i class="fa fa-plus"></i> Sale Medicine</a></li>';
                        
                        $btn_sales_medicine .= ' <li><a  href="'.base_url('sales_estimate/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="Sale Medicine Estimate"><i class="fa fa-plus"></i>Sale Medicine Estimate</a></li>';
                        
                    }
                    $btn_sales_vaccine = '';
                    if(in_array('1089',$users_data['permission']['action'])) 
                    {
                     $btn_sales_vaccine = ' <li><a  href="'.base_url('sales_vaccination/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="Sale Vaccine"><i class="fa fa-plus"></i> Sale Vaccine</a></li>';
                   }
                   $btn_sales_return_medicine = '';
                    if(in_array('408',$users_data['permission']['action'])) 
                    {
                        $btn_sales_return_medicine = '<li><a  href="'.base_url('sales_return_medicine/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="Sale Return Medicine"><i class="fa fa-plus"></i> Sale Return</a></li>';
                    }
                    $btn_pathology_booking='';
                    if(in_array('872',$users_data['permission']['action']))
                    {
                        $btn_pathology_booking = '<li> <a  href="'.base_url('test/booking/'.$patient->id).'" style="'.$patient->id.'" title="Test booking"><i class="fa fa-plus"></i> Test Booking</a> </li>';
                    }
                    $btn_proforma_booking='';
                    if(in_array('2473',$users_data['permission']['action']))
                    {
                        $btn_proforma_booking = '<li> <a  href="'.base_url('pathology_invoice/booking/'.$patient->id).'" style="'.$patient->id.'" title="Proforma Invoice"><i class="fa fa-plus"></i> Proforma Invoice</a> </li>';
                    }
                    
                    
                    $btn_sales_return_vaccine = '';
                    if(in_array('1096',$users_data['permission']['action'])) 
                    {
                    $btn_sales_return_vaccine = '<li><a href="'.base_url('sales_return_vaccination/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="Sale Return Vaccine"><i class="fa fa-plus"></i> Sale Return Vaccine</a></li>';
                    }

                    if(in_array('734',$users_data['permission']['action'])) 
                    {
                        if(!empty($patient->running))
                        {
                           $btn_ipd_booking = '<li><span style="color:green;">Running Patient</span></li>'; 
                        }
                        else
                        {
                            $btn_ipd_booking = ' <li><a  href="'.base_url('ipd_booking/add/?ipd='.$patient->id).'" style="'.$patient->id.'" title="IPD Booking"><i class="fa fa-plus"></i> IPD Booking</a></li>';
                        }
                        
                    }


                    if(in_array('808',$users_data['permission']['action'])) 
                    {
                    $btn_ot_booking = ' <li><a href="'.base_url('ot_booking/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="OT Booking"><i class="fa fa-plus"></i> OT Booking</a></li>';
                    }
                 
                   
                /* blood receipient */
                $btn_recipient_booking ='';
                if(in_array('1507',$users_data['permission']['action'])) 
                {
                $btn_recipient_booking = ' <li><a href="'.base_url('blood_bank/recipient/add/pat_'.$patient->id).'" style="'.$patient->id.'" title="Blood Recipient"><i class="fa fa-plus"></i> Blood Recipient</a></li>';
                }
                
                /*blood receipient */    
               $btn_dialysis_booking='';
               $btn_dialysis_appointment='';
               if(in_array('207',$users_data['permission']['section'])) 
               {
                $btn_dialysis_booking = ' <li><a  href="'.base_url('dialysis_booking/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="Dialysis Booking"><i class="fa fa-plus"></i> Dialysis Booking</a></li>';
                
                $btn_dialysis_appointment = ' <li><a  href="'.base_url('dialysis_appointment/add/'.$patient->id).'" style="'.$patient->id.'" title="Dialysis Appointment"><i class="fa fa-plus"></i> Dialysis Appointment</a></li>';
                
                }
                
                $btn_upload_document='';
               if(in_array('114',$users_data['permission']['action'])) 
               {
                    $btn_history = ' <li><a  href="'.base_url('patient/patient_history?id='.$patient->id.'&branch_id='.$patient->branch_id.'&type=1').'" style="'.$patient->id.'" title="History"><i class="fa fa-history"></i> History</a></li>';
                    

                $print_pdf_url = "'".base_url('patient/print_consolidate_history/?patient_id='.$patient->id.'&branch_id='.$patient->branch_id)."'";

                $btn_consolidate_payment = ' <li><a  onClick="return print_window_page('.$print_pdf_url.')" style="'.$patient->id.'" title="History"><i class="fa fa-money"></i> Consolidated Payment</a></li>';

             

                $btncertificate = ' <li><a  href="'.base_url('patient/doctor_certificate/'.$patient->id.'/'.$patient->branch_id).'" style="'.$patient->id.'" title="Certificate"><i class="fa fa-history"></i> Certificate</a></li>';
              
                $btn_upload_document = '<a href="'.base_url('patient/view_document/'.$patient->id).'" title="View Document" data-url="512"><i class="fa fa-file"></i> View Document</a>';
              }
              
                if(in_array('350',$users_data['permission']['section'])) 
               {
                       $btn_ambulance_booking = ' <a  href="'.base_url('ambulance/booking/add/'.$patient->id).'" style="'.$patient->id.'" title="Edit"><i class="fa fa-plus"></i> Ambulance Booking</a> ';
                }
                
                $btn_amb_enquiry='';
                    if(in_array('350',$users_data['permission']['section'])) 
                    {
                    $btn_amb_enquiry = ' <li><a href="'.base_url('ambulance/enquiry/add/?reg='.$patient->id).'" style="'.$patient->id.'" title="Ambulance Enquiry"><i class="fa fa-plus"></i> Ambulance Enquiry</a></li>';
                    }
                    
            
            $reg_card_url = "'".base_url('patient/print_reg_card/?patient_id='.$patient->id.'&branch_id='.$patient->branch_id)."'";

                $btn_reg_card_url = ' <li><a  onClick="return print_window_page('.$reg_card_url.')" style="'.$patient->id.'" title="History"><i class="fa fa-print"></i> Print Reg. Card</a></li>';
                
                $btn_reg_card = ' <a  href="'.base_url('reg_card_setting/').'" style="'.$patient->id.'" title="Edit"><i class="fa fa-print"></i> Reg. Card Prt Setting</a> ';
                
                $btn_report_print='';    
            if(!empty($patient->mobile_no))
            {
              $wlink = base_url('whatsapp_report/'.$patient->id.'-'.md5(time()));
              $whatsapp_msg = 'Hello '.$patient->patient_name;
              $whatsapp_msg = urldecode($whatsapp_msg);
              $whatsapp_url = "https://api.whatsapp.com/send/?phone=91".$patient->mobile_no."&text=".$whatsapp_msg."&app_absent=0";
              $btn_report_print = '<a target="_blanl" href="'.$whatsapp_url.'" title="Send Message" ><img src="'.base_url().'assets/images/whatsapp-logo.png" width="20px" /></a>';
            }
                
            // End Action Button //
         
            $btn_a = '<div class="slidedown">
            <button disabled  class="btn-custom">More <span class="caret"></span></button>
            <ul class="slidedown-content">
            '.$btn_edit.$btn_barcode.$btn_delete.$btn_appointment.$btn_booking.$btn_camp_booking.$btn_day_care.$btn_recipient_booking.$btn_dialysis_booking.$btn_dialysis_appointment.$btn_billing.$btn_sales_medicine.$btn_sales_return_medicine.$btn_ipd_booking.$btn_ot_booking.$btn_pathology_booking.$btn_proforma_booking.$btn_sales_vaccine.$btn_sales_return_vaccine.$btn_ambulance_booking.$btn_amb_enquiry.$btn_sales_canteen.$btn_history.$btn_consolidate_payment.$btncertificate.$btn_upload_document.$btn_reg_card_url.$btn_reg_card.'
            </ul>
            </div> ';
              
                $row[] = $btn_report_print.$btn_view.$btn_a;  
            $data[] = $row;
            $i++;
        } 
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $recordsTotal,
                        "recordsFiltered" =>$recordsFiltered,
                        "data" => $data,
                );
        //output to json format
        //echo "<pre>"; print_r($output); die; 
        echo json_encode($output);
    }
 /* patient history ajax by mamta */
    public function patient_history($id='',$branch_id='')
     {
        $data['id'] = $this->input->get('id');
        $data['type'] = $this->input->get('type');
        $data['branch_id'] = $this->input->get('branch_id');
        $data['page_title'] = 'Patient History List';
        $this->load->view('patient/patient_history',$data);
          // $this->load->view('medicine_stock/medicine_allot_history',$data);
     }
     public function patient_new_history($id='',$branch_id='')
     {
        $data['id'] = $this->input->get('id');
        $data['type'] = $this->input->get('type');
        $data['page_title'] = 'Patient History List';
        $this->load->view('patient/patient_history',$data);
          // $this->load->view('medicine_stock/medicine_allot_history',$data);
     }
     
     public function print_consolidate_history()
     {
            unauthorise_permission('42','245');
            $get = $this->input->get();
            //print_r($this->session->userdata('company_data'));
            $users_data = $this->session->userdata('auth_users');
            $branch_list= $this->session->userdata('sub_branches_data');
            $parent_id= $users_data['parent_id'];
            $branch_ids = array_column($branch_list, 'id'); 
            $data['branch_collection_list'] = [];
            //$data['doctor_collection_list'] = $this->reports->doctor_collection_list($get);
            $data['branch_detail']=$users_data;
            $data['company_data']=$this->session->userdata('company_data');
            //print_r($data['company_data']);
            $data['self_opd_collection_list'] = $this->patient->self_opd_consolidate_history_list($get);
            
            //print_r($data['self_opd_collection_list']);
            
            $data['self_billing_collection_list'] = $this->patient->self_billing_consolidate_history_list($get);
            $data['self_medicine_collection_list'] = $this->patient->self_medicine_consolidate_history_list($get);
            $data['self_ipd_collection_list'] = $this->patient->self_ipd_consolidate_history_list($get);
            $data['self_pathology_collection_list'] = $this->patient->self_pathology_consolidate_history_list($get);
            // consolidate collection op

            $data['self_ambulance_collection_list'] = $this->patient->self_ambulance_consolidate_history_list($get);
            $data['self_ot_collection_list'] = $this->patient->self_ot_consolidate_history_list($get);
            $data['self_dialysis_collection_list'] = $this->patient->self_dialysis_consolidate_history_list($get);
            $data['self_inventory_collection_list'] = $this->patient->self_inventory_consolidate_history_list($get);
            $data['self_vacci_collection_list'] = $this->patient->self_vacci_consolidate_history_list($get);

             $data['self_eye_collection_list'] = $this->patient->self_eye_consolidate_history_list($get);
            $data['self_pedit_collection_list'] = $this->patient->self_pedit_consolidate_history_list($get);
            $data['self_dental_collection_list'] = $this->patient->self_dental_consolidate_history_list($get);
            $data['self_gyni_collection_list'] = $this->patient->self_gyni_consolidate_history_list($get);
            
            $data['self_day_care_collection_list'] = $this->patient->self_day_care_consolidate_history_list($get);
           
            $data['get'] = $get;
            //print_r($data);
            $this->load->view('patient/list_consolidate_history_data',$data);  
     }

     public function print_doctor_certificate()
     {
       
           $id=$this->input->post('certificate_id');
           $doctor_id=$this->input->post('doctor_id');
          // echo $id.$doctor_id;die;
           unauthorise_permission('42','245');
            $get = $this->input->get();
             //  print_r($get);die;
            //print_r($this->session->userdata('company_data'));
            $users_data = $this->session->userdata('auth_users');
            $branch_list= $this->session->userdata('sub_branches_data');
            $parent_id= $users_data['parent_id'];
            $branch_ids = array_column($branch_list, 'id'); 
            $data['branch_collection_list'] = [];
            //$data['doctor_collection_list'] = $this->reports->doctor_collection_list($get);
            $data['branch_detail']=$users_data;
            $data['company_data']=$this->session->userdata('company_data');
            //print_r($data['company_data']);
             $id=$this->uri->segment(3);
             $branch_id=$this->uri->segment(4);
              $template_id=$_POST['certificate_id'];
            //  echo $template_id;die;
             $data['template_data'] = $this->patient->get_template_data_by_branch_id($branch_id,$template_id);
           // print_r($data['template_data']);die;
             $data['form_data'] = $this->patient->get_by_id($id);
           //  print_r($data['form_data']);die;
            $data['doctor_data'] = $this->patient->get_doctor_data($doctor_id);
           // print_r($data['doctor_data']);die;
            $data['signature_data'] = $this->patient->get_doctor_signature_data($doctor_id,$branch_id);
        
            $this->load->view('patient/print_certificate_data',$data);
            }

    
    public function patient_history_ajax()
    {  

        $post = $this->input->post();
       
        $this->load->model('opd_billing/opd_billing_model','opd_billing'); 
        //unauthorise_permission(63,415);
        $users_data = $this->session->userdata('auth_users');

        $list = $this->patient->get_patient_history_datatables($post['patient_id'],$post['type'],$post['branch_id']);  
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';

        foreach ($list as $patient_history) 
        { 
            $no++;
            $row = array();
            $state = "";
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
            if($post['type']==1 || $post['type']==2)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                if($post['type']==1){
                $row[] = $patient_history->doctor_name;
                }
                  
                
                if($post['type']==2){
                    $get_particulars= $this->opd_billing->get_billed_particular_list($patient_history->id);
                    $paticulars_new=array();
                    foreach($get_particulars as $particulars){
                        $paticulars_new[]=$particulars['particulars'];

                    }
                     $row[]= implode(',', $paticulars_new);
                }
                
                $row[] = $patient_history->amount;
            }
            elseif($post['type']==4)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
            elseif($post['type']==5)
            {
                $row[] = $patient_history->number;
                //$row[] = $patient_history->lab_reg_no;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==6)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==7)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->remarks;
            }
             elseif($post['type']==8)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = '';
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==9)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==10)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==11)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==12)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==13)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==14)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
             elseif($post['type']==15)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
            else
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                $row[] = $patient_history->amount;
            }
            if($post['type']==1){
            $print_pdf_url = "'".base_url('opd/print_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = ' <a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';
            }
            if($post['type']==2){
             $print_url = "'".base_url('opd_billing/print_billing_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
             $btnprint = ' <a class="btn-custom" href="javascript:void(0)" onclick = "return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print  </a>';  
            }
            if($post['type']==3){
            $print_url = "'".base_url('sales_medicine/print_sales_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            if($post['type']==4){
            $print_url = "'".base_url('ipd_booking/print_ipd_booking_recipt/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            if($post['type']==5){
            $print_url = "'".base_url('test/print_test_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
             if($post['type']==6){
            $print_url = "'".base_url('ambulance/booking/print_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }

            if($post['type']==7){
            $print_url = "'".base_url('dialysis_booking/print_dialysis_booking_report/'.$patient_history->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
              if($post['type']==8){
            $print_url = "'".base_url('dialysis_booking/print_dialysis_booking_report/'.$patient_history->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
              if($post['type']==9){
            $print_url = "'".base_url('sales_vaccination/print_sales_report/'.$patient_history->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
              if($post['type']==10){
            $print_url = "'".base_url('dialysis_booking/print_dialysis_booking_report/'.$patient_history->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
              if($post['type']==11){
            $print_url = "'".base_url('opd/print_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
              if($post['type']==12){
            $print_url = "'".base_url('opd/print_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
              if($post['type']==13){
            $print_url = "'".base_url('opd/print_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
              if($post['type']==14){
            $print_url = "'".base_url('opd/print_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            
             if($post['type']==15){
            $print_url = "'".base_url('day_care/print_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            

            $row[]=$btnprint;
            $data[] = $row;
            $i++;
        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->patient->get_patient_history_count_all($post['patient_id'],$post['type']),
                        "recordsFiltered" => $this->patient->get_patient_history_count_filtered($post['patient_id'],$post['type']),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }
    public function patient_history_ajax20200124()
    {  

        $post = $this->input->post();
       
        $this->load->model('opd_billing/opd_billing_model','opd_billing'); 
        //unauthorise_permission(63,415);
        $users_data = $this->session->userdata('auth_users');

        $list = $this->patient->get_patient_history_datatables($post['patient_id'],$post['type'],$post['branch_id']);  
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
       // print_r($list);
        foreach ($list as $patient_history) 
        { 
            $no++;
            $row = array();
            $state = "";
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
            if($post['type']==1 || $post['type']==2)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));
                if($post['type']==1){
                $row[] = $patient_history->doctor_name;
                }
                  
                
                if($post['type']==2){
                    $get_particulars= $this->opd_billing->get_billed_particular_list($patient_history->id);
                   //print_r($get_particulars);
                    $paticulars_new=array();
                    foreach($get_particulars as $particulars){
                        $paticulars_new[]=$particulars['particulars'];

                    }
                     $row[]= implode(',', $paticulars_new);
                }
                
                $row[] = $patient_history->amount;
            }
            elseif($post['type']==4)
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));;
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
            elseif($post['type']==5)
            {
                $row[] = $patient_history->number;
                //$row[] = $patient_history->lab_reg_no;
                $row[] = date('d-M-Y',strtotime($patient_history->date));;
                $row[] = $patient_history->doctor_name;
                $row[] = $patient_history->amount;
            }
            else
            {
                $row[] = $patient_history->number;
                $row[] = date('d-M-Y',strtotime($patient_history->date));;
                $row[] = $patient_history->amount;
            }
            if($post['type']==1){
            $print_pdf_url = "'".base_url('opd/print_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = ' <a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';
            }
            if($post['type']==2){
             $print_url = "'".base_url('opd_billing/print_billing_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
             $btnprint = ' <a class="btn-custom" href="javascript:void(0)" onclick = "return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print  </a>';  
            }
            if($post['type']==3){
            $print_url = "'".base_url('sales_medicine/print_sales_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            if($post['type']==4){
            $print_url = "'".base_url('ipd_booking/print_ipd_booking_recipt/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            if($post['type']==5){
            $print_url = "'".base_url('test/print_test_booking_report/'.$patient_history->id.'/'.$patient_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            $row[]=$btnprint;
            $data[] = $row;
            $i++;
        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->patient->get_patient_history_count_all($post['patient_id'],$post['type']),
                        "recordsFiltered" => $this->patient->get_patient_history_count_filtered($post['patient_id'],$post['type']),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }
    /* patient history ajax */
    
    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        $data['data']=get_setting_value('PATIENT_REG_NO');
        //print_r($data['data']);die;
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['religion_list'] = $this->general_model->religion_list();
        $data['relation_list'] = $this->general_model->relation_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();    
        $data['form_data'] = array(
                                    "start_date"=>"",
                                    "end_date"=>"",
                                    "patient_code"=>"", 
                                    "patient_name"=>"",
                                    "simulation_id"=>"",
                                    "mobile_no"=>"",
                                    "gender"=>"",
                                    "start_age_y"=>"",
                                    "end_age_y"=>"",
                                    "age_d"=>"",
                                    "adhar_no"=>"",
                                    "dob"=>"",
                                    "address"=>"",
                                    "city_id"=>"",
                                    "state_id"=>"",
                                    "country_id"=>"",
                                    "pincode"=>"",
                                    "marital_status"=>"",
                                    "religion_id"=>"",
                                    "father_husband"=>"",
                                    "mother"=>"",
                                    "guardian_name"=>"",
                                    "guardian_email"=>"",
                                    "guardian_phone"=>"",
                                    "relation_id"=>"",
                                    "patient_email"=>"",
                                    "monthly_income"=>"",
                                    "occupation"=>"",
                                    "photo"=>"",
                                    "old_img"=>"",
                                    "relation_type"=>"",
                                    "relation_name"=>"",
                                    "insurance_type"=>"",
                                    "insurance_type_id"=>"",
                                    "ins_company_id"=>"",
                                    "polocy_no"=>"",
                                    "tpa_id"=>"",
                                    "ins_amount"=>"",
                                    "ins_authorization_no"=>"", 
                                    "status"=>"", 
                                    "remark"=>"",
                                    "branch_id"=>"",
                                     );
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('patient_search', $merge);
        }
        $patient_search = $this->session->userdata('patient_search');
        if(isset($patient_search) && !empty($patient_search))
        {
            $data['form_data'] = $patient_search;
        }
        $this->load->view('patient/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('patient_search');
    }

    public function add()
    {
        unauthorise_permission(19,114);
        $this->load->library('form_validation');
        $this->load->model('general/general_model'); 
        $users_data = $this->session->userdata('auth_users');
        $data['page_title'] = "Add Patient";
        $data['form_error'] = [];
        $data['country_list'] = $this->general_model->country_list();

        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['religion_list'] = $this->general_model->religion_list();
        $data['relation_list'] = $this->general_model->relation_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        // print_r($data['gardian_relation_list']);die;
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();   
        $reg_no = generate_unique_id(4);
        $post = $this->input->post();
        $data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "patient_code"=>$reg_no, 
                                    "patient_name"=>"",
                                    "simulation_id"=>"",
                                    "mobile_no"=>"",
                                    "gender"=>"1",
                                    "age_y"=>"",
                                    "age_m"=>"",
                                    "age_d"=>"",
                                    "age_h"=>"",
                                    // "dob"=>"",
                                    "address"=>"",
                                    "address_second"=>"",
                                    "address_third"=>"",
                                    "adhar_no"=>"",
                                    "city_id"=>"",
                                    "state_id"=>"",
                                    "country_id"=>"99",
                                    "pincode"=>"",
                                    "marital_status"=>"0",
                                    "religion_id"=>"",
                                    "father_husband"=>"",
                                    "mother"=>"",
                                    "guardian_name"=>"",
                                    "guardian_email"=>"",
                                    "guardian_phone"=>"",
                                    "relation_id"=>"",
                                    "patient_email"=>"",
                                    "monthly_income"=>"",
                                    "occupation"=>"",
                                    "photo"=>"",
                                    "old_img"=>"",
                                    "insurance_type"=>"0",
                                    "insurance_type_id"=>"",
                                    "ins_company_id"=>"",
                                    "polocy_no"=>"",
                                    "tpa_id"=>"",
                                    "ins_amount"=>"",
                                    "relation_simulation_id"=>"",
                                    "relation_type"=>"",
                                    "relation_name"=>"",
                                    "ins_authorization_no"=>"", 
                                    "status"=>"1", 
                                    "remark"=>"",
                                    "country_code"=>"+91",
                                    "opt_type"=>"Add",
                                    "opticon"=>'<i class="fa fa-plus"></i>',
                                    'dob'=>'',
                                    'anniversary'=>'',
                                    'f_h_simulation'=>'',
                                    'capture_finger'=>'',  // Added By Nitin Sharma 04/02/2024
                                    'fingerprint_photo'=>'', // Added By Nitin Sharma 04/02/2024
                                    'created_date'=>date('m/d/Y H:i:s')
                                  );
        if(isset($post) && !empty($post))
        { 
            $valid_response = $this->_validate();
            $data['form_data'] = $valid_response['form_data']; 
            if($this->form_validation->run() == TRUE)
            {
                if($post['capture_img']!="")
                {
                    $valid_response['photo_name'] = $post['capture_img'];
                } 
                $patient_id =  $this->patient->save($valid_response['photo_name']);
                /////// Send SMS /////////
                //print_r($users_data['permission']['action']);die;
                if(in_array('640',$users_data['permission']['action']))
                {
                  if(!empty($post['mobile_no']))
                  {
                    $parameter = array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id);
                    send_sms('patient_registration',18,'',$post['mobile_no'],$parameter);  
                  }
                }
                /////////////////////////
                
                ////////// SEND EMAIL ///////////////////
                //if(in_array('640',$users_data['permission']['action']))
                //{ 
                  if(!empty($post['patient_email']))
                  { 
                    $this->load->library('general_functions'); 
                    $this->general_functions->email($post['patient_email'],'','','','','1','patient_registration','18',array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id)); 
                  }
               // } 
                 
                ////////////////////////////////////////


                $this->session->set_flashdata('success','Patient successfully added.');
                redirect(base_url('patient'));
            }
            else
            {
               
                $data['form_error'] = validation_errors();  
            }       
        }
        $this->session->unset_userdata('comission_data');
        $this->load->view('patient/add',$data);
    }

    public function edit($id="")
    {
     $users_data = $this->session->userdata('auth_users');  
     unauthorise_permission(19,115);
    
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $this->load->library('form_validation');
        $result = $this->patient->get_by_id($id); 
        //print_r($result);die;
       // $reg_no = generate_unique_id(3);
        $this->load->model('general/general_model');  
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['religion_list'] = $this->general_model->religion_list();
        $data['relation_list'] = $this->general_model->relation_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();   
        $data['page_title'] = "Update Patient";  
        $post = $this->input->post();
        $data['form_error'] = '';  
        $dob ='';
        if($result['dob']!='0000-00-00' && $result['dob']!='1970-01-01')
        {
            $dob = date('d-m-Y',strtotime($result['dob']));
        }
        $anniversary='';
        if($result['anniversary']!='0000-00-00' && $result['anniversary']!='1970-01-01' )
        {
            $anniversary = date('d-m-Y',strtotime($result['anniversary']));
        }
        $adhar_no='';
        if(!empty($result['adhar_no']))
        {
            $adhar_no = $result['adhar_no'];
        }
        
        /* present age*/
        $present_age = get_patient_present_age($dob,$result);
        $age_y = $present_age['age_y'];
        $age_m = $present_age['age_m'];
        $age_d = $present_age['age_d'];
        if(!empty($present_age['age_h']))
        {
            $age_h = $present_age['age_h'];
        }
        else
        {
            $age_h ='0';
        }
        /* present age*/
        
        $data['form_data'] = array(
                                    "data_id"=>$result['id'],
                                    "branch_id"=>$result['branch_id'],
                                    "patient_code"=>$result['patient_code'],
                                    "patient_name"=>$result['patient_name'],
                                    "simulation_id"=>$result['simulation_id'],
                                    "mobile_no"=>$result['mobile_no'],
                                    "gender"=>$result['gender'],
                                    "age_y"=>$age_y,
                                    "age_m"=>$age_m,
                                    "age_d"=>$age_d,
                                    "age_h"=>$age_h,
                                    "relation_simulation_id"=>$result['relation_simulation_id'],
                                    "relation_type"=>$result['relation_type'],
                                    "relation_name"=>$result['relation_name'],
                                    "adhar_no"=>$adhar_no,
                                    // "dob"=>$result['dob'],
                                    "address"=>$result['address'],
                                    "address_second"=>$result['address2'],
                                    "address_third"=>$result['address3'],
                                    "city_id"=>$result['city_id'],
                                    "state_id"=>$result['state_id'],
                                    "country_id"=>$result['country_id'],
                                    "pincode"=>$result['pincode'],
                                    "marital_status"=>$result['marital_status'],
                                    "religion_id"=>$result['religion_id'],
                                    "father_husband"=>$result['father_husband'],
                                    "mother"=>$result['mother'],
                                    "guardian_name"=>$result['guardian_name'],
                                    "guardian_email"=>$result['guardian_email'],
                                    "guardian_phone"=>$result['guardian_phone'],
                                    "relation_id"=>$result['relation_id'],
                                    "patient_email"=>$result['patient_email'],
                                    "monthly_income"=>$result['monthly_income'],
                                    "occupation"=>$result['occupation'],
                                    "old_img"=>$result['photo'],
                                    "photo"=>$result['photo'],
                                    "insurance_type"=>$result['insurance_type'],
                                    "insurance_type_id"=>$result['insurance_type_id'],
                                    "ins_company_id"=>$result['ins_company_id'],
                                    "polocy_no"=>$result['polocy_no'],
                                    "tpa_id"=>$result['tpa_id'],
                                    "ins_amount"=>$result['ins_amount'],
                                    "ins_authorization_no"=>$result['ins_authorization_no'], 
                                    "status"=>$result['status'],
                                    "remark"=>$result['remark'],
                                    "username"=>$result['username'],
                                     "country_code"=>"+91",
                                     "opt_type"=>"Update",
                                     "opticon"=>'<i class="fa fa-refresh"></i>',
                                     'dob'=>$dob,
                                     'anniversary'=>$anniversary,
                                     'f_h_simulation'=>$result['f_h_simulation'],
                                     'created_date'=>date('m/d/Y h:i A', strtotime($result['created_date']))
                                   );  
        if(isset($post) && !empty($post))
        {   

            $valid_response = $this->_validate();
            $data['form_data'] = $valid_response['form_data'];
            $data['photo_error'] = $valid_response['photo_error']; 
            if($this->form_validation->run() == TRUE)
            {  
                 /////// Send SMS /////////
                //echo '<pre>'; print_r($users_data['permission']['action']);die;
                if(in_array('640',$users_data['permission']['action']))
                { 
                  if(!empty($post['mobile_no']) && $result['mobile_no']!=$post['mobile_no'])
                  {
                    $parameter = array('{patient_name}'=>$post['patient_name'], '{mobile_no}'=>$post['mobile_no']);
                     
                    send_sms('update_patient_mobile',28,'',$post['mobile_no'],$parameter);  
                  }
                }
                /////////////////////////
                
                if(!empty($post['old_img']) && !empty($valid_response['photo_name']) && file_exists(DIR_UPLOAD_PATH.'patients/'.$post['old_img']) && $post['old_img']!==$valid_response['photo_name'])
                {
                   
                 
                   unlink(DIR_UPLOAD_PATH.'patients/'.$post['old_img']);
                }
             
                if($post['capture_img']!="")
                {
                    $valid_response['photo_name'] = $post['capture_img'];
                } 
                 
                $this->patient->save($valid_response['photo_name']);
                $this->session->set_flashdata('success','Patient successfully updated.');
                redirect(base_url('patient'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->session->unset_userdata('comission_data'); 
       $this->load->view('patient/add',$data);       
      }
    }
     
    private function _validate()
    {
        $this->load->library('form_validation');
        $post = $this->input->post();
          $field_list = mandatory_section_field_list(2);
         $users_data = $this->session->userdata('auth_users');  
        $data['form_data']= [];  
        $data['photo_error']= [];
        $data['photo_name'] = $post['old_img'];
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required');
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
        $this->form_validation->set_rules('adhar_no', 'aadhaar no.', 'min_length[12]|max_length[16]'); 
        
          
          if(!empty($field_list))
          {
            
            if($field_list[0]['mandatory_field_id']=='5' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id'])
            {
                $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]');
            }
           
            if($field_list[1]['mandatory_field_id']=='7' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id'])
            { 
                //$this->form_validation->set_rules('age_y', 'age', 'trim|required');
                if(($post['age_y']=='0' && $post['age_m']=='0' && $post['age_d']=='0' && $post['age_h']=='0') || ((empty($post['age_y']) && empty($post['age_m']) && empty($post['age_d']) && empty($post['age_h']))) )
                    {   
                        $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|is_natural_no_zero|max_length[3]');
                    }
            }
          }

        if(!empty($post['data_id']) && !empty($post['password']))
        {
          $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[15]');
        } 

        $this->form_validation->set_rules('guardian_phone', 'guardian mobile no.', 'trim|numeric|min_length[10]|max_length[10]'); 
       
        $this->form_validation->set_rules('pincode', 'pincode', 'trim|min_length[6]|max_length[6]|numeric');
        $this->form_validation->set_rules('patient_email', 'patient email', 'trim|valid_email|callback_check_email');
        $this->form_validation->set_rules('guardian_email', 'guardian email', 'trim|valid_email'); 
        $this->form_validation->set_rules('monthly_income', 'monthly', 'trim|numeric'); 
      
        if(isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name']))
        {  
             $config['upload_path']   = DIR_UPLOAD_PATH.'patients/'; 
             $config['allowed_types'] = 'jpeg|jpg|png'; 
             $config['max_size']      = 0;  //1000 image not upload after 5 mb
             $config['encrypt_name'] = TRUE; 
             $this->load->library('upload', $config);
            if ($this->upload->do_upload('photo')) 
            {

               $file_data = $this->upload->data();
               $data['photo_name']  =  $file_data['file_name']; 
            } 
            else
            {
                
               
              
                $this->form_validation->set_rules('photo', 'photo', 'trim|required'); 
                $data['photo_error'] = $this->upload->display_errors();
                //print_r($data['photo_error']);die;
                
            }
        }

   
        if ($this->form_validation->run() == FALSE) 
        { 
            $reg_no = generate_unique_id(4); 
            if(!isset($post['insurance_type_id']))
            {
                $insurance_type_id = "";
            }
            else
            {
               $insurance_type_id = $post['insurance_type_id'];
            }

            if(!isset($post['ins_company_id']))
            {
                $ins_company_id = "";
            }
            else
            {
                $ins_company_id = $post['ins_company_id'];
            }

            $username = '';
            if(isset($post['username']))
            {
                $username = $post['username'];
            }

            $data['form_data'] = array(
                                "data_id"=>$post['data_id'], 
                                "patient_code"=>$reg_no, 
                                "patient_name"=>$post['patient_name'],
                                "simulation_id"=>$post['simulation_id'],
                                "mobile_no"=>$post['mobile_no'],
                                "gender"=>$post['gender'],
                                "age_y"=>$post['age_y'],
                                "age_m"=>$post['age_m'],
                                "age_d"=>$post['age_d'],
                                "age_h"=>$post['age_h'], 
                                "address"=>$post['address'],
                                "address_second"=>$post['address_second'],
                                "address_third"=>$post['address_third'],
                                "adhar_no"=>$post['adhar_no'],
                                "city_id"=>$post['city_id'],
                                "state_id"=>$post['state_id'],
                                "country_id"=>$post['country_id'],
                                "pincode"=>$post['pincode'],
                                "marital_status"=>$post['marital_status'],
                                "religion_id"=>$post['religion_id'],
                                //"father_husband"=>$post['father_husband'],
                                "mother"=>$post['mother'],
                                "guardian_name"=>$post['guardian_name'],
                                "guardian_email"=>$post['guardian_email'],
                                "guardian_phone"=>$post['guardian_phone'],
                                "relation_id"=>$post['relation_id'],
                                "patient_email"=>$post['patient_email'],
                                "monthly_income"=>$post['monthly_income'],
                                "occupation"=>$post['occupation'],
                                "insurance_type"=>$post['insurance_type'],
                                "insurance_type_id"=>$insurance_type_id,
                                "old_img"=>$post['old_img'],
                                "ins_company_id"=>$ins_company_id,
                                "polocy_no"=>$post['polocy_no'],
                                "tpa_id"=>$post['tpa_id'],
                                "ins_amount"=>$post['ins_amount'],
                                "ins_authorization_no"=>$post['ins_authorization_no'], 
                                "status"=>$post['status'] ,
                                "relation_type"=>$post['relation_type'],
                                "relation_name"=>$post['relation_name'],
                                "relation_simulation_id"=>$post['relation_simulation_id'],
                                 "remark"=>$post['remark'],
                                "country_code"=>"+91",
                                "username"=>$username,
                                'f_h_simulation'=>$post['f_h_simulation'],
                                'anniversary'=>$post['anniversary'],
                                'dob'=>$post['dob'],
                                'created_date'=>$post['created_date']
                               ); 
            if(!empty($post['data_id']))
            {
                $data['form_data']['opt_type'] = "Update";
                $data['form_data']['opticon'] = '<i class="fa fa-plus"></i>';
            }
            else
            {
                $data['form_data']['opt_type'] = "Add";
                $data['form_data']['opticon'] = '<i class="fa fa-plus"></i>';
            }
            
        }   
        return $data;
    }

    public function check_email($str)
    {
        if(!empty($str))
        {
            $this->load->model('general/general_model','general');
            $post = $this->input->post();
            if(!empty($post['data_id']) && $post['data_id']>0)
            {
                return true;
            }
            else
            {
                $userdata = $this->general->check_email($str); 
                if(empty($userdata))
                {
                   return true;
                }
                else
                { 
                    $this->form_validation->set_message('check_email', 'Email already exists.');
                    return false;
                }
            }
        } 
    }
 
    public function delete($id="")
    {
       unauthorise_permission(19,116);
       if(!empty($id) && $id>0)
       {
           $result = $this->patient->delete($id);
           $response = "Patient successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(19,116);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->patient->deleteall($post['row_id']);
            $response = "Patient successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     unauthorise_permission(19,117);   
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->patient->get_by_id($id);  
        //print '<pre>';print_r($data['form_data']);die;
         $data['page_title'] = $data['form_data']['patient_name']." detail";
        $this->load->view('patient/view',$data);     
      }
    }  

     public function doctor_certificate($id="",$branch_id='')
    {  
       // echo $branch_id;die;
        
        $data['form_data'] = $this->patient->get_by_id($id); 
      //  echo $branch_id;die;
    //   echo '<pre>';print_r($data['form_data']);die;
        $data['doctor_name'] = $this->patient->get_doctor_name($branch_id);
      // print_r($data['doctor_name']);die;
        $data['template_data'] = $this->patient->get_template_data(); 
      //  print_r($data['template_data']);die;
      // echo '<pre>';print_r($data['template_data']);die;
        $data['page_title'] = $data['form_data']['patient_name']." detail";
        $this->load->view('patient/view_doctor_certificate',$data);
    //$users_data = $this->session->userdata('auth_users'); 
     //$parent_id= $users_data['parent_id'];

   //  echo $parent_id;die;
      // $data['patient']=$users_data; 
    //  unauthorise_permission(19,117);   
 /*    if(isset($id) && !empty($id) && is_numeric($id))
      {   
     // echo "xyz";die;    
        $data['form_data'] = $this->patient->get_by_id($id); 
    //     echo '<pre>';print_r($data['form_data']);die;
        $data['template_data'] = $this->patient->get_template_data()->result(); 
        //echo '<pre>';print_r($data['template_data']);die;
        $data['page_title'] = $data['form_data']['patient_name']." detail";
        $this->load->view('patient/view_doctor_certificate',$data);     
      }*/
    }  

public function ipd_printtemplate_dropdown()
  {
      $value= $this->input->post('value');
      $data_array= array('id'=>$value);
      $opd_list = $this->patient->template_list($data_array);
      $data['template']= $opd_list['template'];
     // $data['short_code']= $opd_list['short_code'];
      echo json_encode($data);
  }
    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(19,118);
        $data['page_title'] = 'Patient Archive List';
        $this->load->helper('url');
        $this->load->view('patient/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(19,118);
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('patient/patient_archive_model','patient_archive'); 

        $list = $this->patient_archive->get_datatables();   
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $patient) { 
            $no++;
            $row = array();
            if($patient->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($patient->state))
            {
                $state = " ( ".ucfirst(strtolower($patient->state))." )";
            }
            //////////////////////// 
            
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
            $row[] = '<input type="checkbox" name="patient[]" class="checklist" value="'.$patient->id.'">'.$check_script;
            $gender = array('0'=>'Female', '1'=>'Male', '2'=>'Others');
            $row[] = $patient->patient_code; 
            $row[] = $patient->patient_name;
            $row[] = $patient->mobile_no;
            $row[] = $gender[$patient->gender];
            ///////////// Age calculation //////////
                $age_y = $patient->age_y;
                $age_m = $patient->age_m;
                $age_d = $patient->age_d;
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
            ///////////////////////////////////////
            $row[] = $age; 
            if(!empty($patient->address))
            {
                $address = substr($patient->address,0,50);
            }
            else
            {
                $address = $patient->address;
            }
            $row[] = $address;
            $row[] = date('d-m-Y h:i A', strtotime($patient->created_date));  
            
            //Action button /////
            $btn_restore = ""; 
            $btn_view = "";
            $btn_delete = "";
            $btn_history = "";

            if(in_array('120',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_patient('.$patient->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            
            if(in_array('117',$users_data['permission']['action'])) 
            {
                $btn_view = ' <a class="btn-custom" onclick="return view_patient('.$patient->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
            } 
            
            if(in_array('119',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$patient->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //
 
           $row[] = $btn_restore.$btn_view.$btn_delete; 

            $row[] = '  
            '; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->patient_archive->count_all(),
                        "recordsFiltered" => $this->patient_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(19,120);
        $this->load->model('patient/patient_archive_model','patient_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->patient_archive->restore($id);
           $response = "Patient successfully restore in Patient list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(19,120);
        $this->load->model('patient/patient_archive_model','patient_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->patient_archive->restoreall($post['row_id']);
            $response = "Patient successfully restore in Patient list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(19,119);
        $this->load->model('patient/patient_archive_model','patient_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->patient_archive->trash($id);
           $response = "Patient successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(19,119);
        $this->load->model('patient/patient_archive_model','patient_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->patient_archive->trashall($post['row_id']);
            $response = "Patient successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function patient_dropdown()
  {

      $patient_list = $this->patient->employee_type_list();
      $dropdown = '<option value="">Select Doctor</option>'; 
      if(!empty($patient_list))
      {
        foreach($patient_list as $patient)
        {
           $dropdown .= '<option value="'.$patient->id.'">'.$patient->doctor_name.'</option>';
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
        $comission_data = $this->patient->doc_comission_data($post['id']);
        $this->session->set_userdata('comission_data',$comission_data);
     } 
     $data['dept_list'] = $this->general_model->department_list();         
     if(isset($post['data']) && !empty($post['data']))
     { 
        $this->session->set_userdata('comission_data',$post);
        echo '1'; return false;
     }
     $this->load->view('patient/add_comission',$data);
  }

  public function view_comission()
  {
     $data['page_title'] = "Pathology Share Details";
     $this->load->model('general/general_model');
     $post = $this->input->post();
     $data['dept_list'] = $this->general_model->department_list();
     if(isset($post['id']) && !empty($post['id']))
     {
        $data['comission'] = $this->patient->doc_comission_data($post['id']); 
     }  
     $this->load->view('patient/view_comission',$data);
  }



    public function patient_excel()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $data= get_setting_value('PATIENT_REG_NO');
        $fields = array($data,'Patient Name','Patient Relation','Mobile No.','Gender','Age','Address','Created Date');
         $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
        foreach ($fields as $field)
        {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
        }
        $list = $this->patient->search_patient_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $patients)
            {
                $genders = array('0'=>'Female', '1'=>'Male', '2'=>'Other');
                $gender = $genders[$patients->gender];
                $age_y = $patients->age_y;
                $age_m = $patients->age_m;
                $age_d = $patients->age_d;
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
                $age .= "/ ".$age_m." ".$month;
                }
                if($age_d>0)
                {
                $day = 'Days';
                if($age_d==1)
                {
                  $day = 'Day';
                }
                $age .= "/ ".$age_d." ".$day;
                }
                $patient_age =  $age;
                $created_date = date('d-m-Y h:i A', strtotime($patients->created_date));
                
                $relation_name='';
            if(!empty($patients->relation_name))
            {
                $relation_name = $patients->patient_relation." ".$patients->relation_name;  
            }
            
                
                array_push($rowData,$patients->patient_code,$patients->patient_name,$relation_name,$patients->mobile_no,$gender,$patient_age,$patients->address,$created_date);
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
            foreach($data as $patients_data)
            {
                $col = 0;
                $row_val=1;
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $patients_data[$field]);
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
        header('Content-Type: application/vnd.ms-excel charset=UTF-8');
        header("Content-Disposition: attachment; filename=patient_list_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
    }

    public function patient_csv()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $data= get_setting_value('PATIENT_REG_NO');
                  
        $fields = array($data,'Patient Name','Mobile No.','Gender','Age','Address','Created Date');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->patient->search_patient_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $patients)
            {
                $genders = array('0'=>'Female', '1'=>'Male', '2'=>'Other');
                $gender = $genders[$patients->gender];
                $age_y = $patients->age_y;
                $age_m = $patients->age_m;
                $age_d = $patients->age_d;
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
                $age .= "/ ".$age_m." ".$month;
                }
                if($age_d>0)
                {
                $day = 'Days';
                if($age_d==1)
                {
                  $day = 'Day';
                }
                $age .= "/ ".$age_d." ".$day;
                }
                $patient_age =  $age;
                $created_date = date('d-m-Y h:i A', strtotime($patients->created_date));
                array_push($rowData,$patients->patient_code,$patients->patient_name,$patients->mobile_no,$gender,$patient_age,$patients->address,$created_date);
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
            foreach($data as $patients_data)
            {
                $col = 0;
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $patients_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=patient_list_".time().".csv");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }

      
    }

    public function patient_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->patient->search_patient_data();
        $this->load->view('patient/patient_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("patient_list_".time().".pdf");
    }
    public function patient_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->patient->search_patient_data();
      $this->load->view('patient/patient_html',$data); 
    }

    public function start_cam()
    {     
        $data['page_title'] = 'Web Camera';
        $this->load->view('patient/webcam',$data);  
    }

    public function webcam_save()
    {
        $filename =  time() . '.jpg';
        $filepath = DIR_UPLOAD_PATH.'patients/'; 
        move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath.$filename); 
        echo $filename;
    }
    
    public function import_patient_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('form_validation');
        $this->load->library('excel');
        $data['page_title'] = 'Import Patient excel';
        $arr_data = array();
        $header = array();
        $path='';

        //print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['patient_list']) || $_FILES['patient_list']['error']>0)
            {
               
               $this->form_validation->set_rules('patient_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('patient_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 

                    echo "<pre> dfd";print_r(DIR_UPLOAD_PATH.'patient/'); exit;
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

                //echo "<pre>";print_r($arr_data); exit;
                if(!empty($arr_data))
                {
                    //echo '<pre>'; print_r($arr_data); exit;
                    $arrs_data = array_values($arr_data);
                    $total_patient = count($arrs_data);
                    
                   $array_keys = array('simulation_id','patient_name','relation_type','relation_simulation_id','relation_name','mobile_no','gender','age_y','age_m','age_d','age_h','dob','address','address2','address3','adhar_no','country','state','city','pincode','marital_status','anniversary','religion_id','mother','guardian_name','guardian_email','guardian_phone','relation_id','patient_email','monthly_income','occupation','insurance_type','insurance_type_id','ins_company_id','polocy_no','tpa_id','ins_amount','ins_authorization_no');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM');
                    $patient_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $patient_all_data= array();
                    for($i=0;$i<$total_patient;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $patient_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $patient_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                    
                    $this->patient->save_all_patient($patient_all_data);
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

        $this->load->view('patient/import_patient_excel',$data);
    } 


    public function sample_import_patient_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Simulation','Patient Name(*)','Relation Type','Relation Simulation','Relation Name','Mobile No.','Gender (Male=1,Female=0,Others=2)','Age Year(yyyy)','Age Month(mm)','Age Day(dd)','Age Hours','Dob(dd-mm-yyyy)','Address1','Address2','Address3','Aadhaar No.','Country','State','City','Pin Code','Marital Status(Married=1,Unmarried=0)','Marriage Anniversary(dd-mm-yyyy)','Religion','Mother Name','Guardian Name','Guardian Email','Guardian Mobile','Relation','Patient Email','Monthly Income','Occupation','Insurance Type(Normal=0,TPA=1)','Insurance Type','Insurance Company','Policy No.','TPA ID','Insurance Amount','Authorization No.');

            
      
              $col = 0;
            foreach ($fields as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $col++;
            }
            $rowData = array();
            $data= array();
          
            // Fetching the table data
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            // Sending headers to force the user to download the file
            header('Content-Type: application/vnd.ms-excel charset=UTF-8');
            header("Content-Disposition: attachment; filename=patient_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }
    
    
     public function view_document($patient_id)
    { 
        $data['page_title'] = "Patient Document";
        $data['patient_id'] = $patient_id;
        $this->load->view('patient/patient_document',$data);
    }

    public function ajax_document_list($patient_id='')
    { 
        
        $users_data = $this->session->userdata('auth_users');
        $list = $this->document_file->get_datatables($patient_id);  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescription) { 
            $no++;
            $row = array();
            if($prescription->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
                        
            ////////// Check  List /////////////////
            $check_script = "";
                               
            $row[] = '<input type="checkbox" name="prescription[]" class="checklist" value="'.$prescription->id.'">'.$check_script;
            $row[] = $prescription->document_name;
            $sign_img = "";
            if(!empty($prescription->document_files) && file_exists(DIR_UPLOAD_PATH.'patients/document/'.$prescription->document_files))
            {
                $sign_img = ROOT_UPLOADS_PATH.'patients/document/'.$prescription->document_files;
                $sign_img = '<a href="'.$sign_img.'" target="_blank"><img src="'.$sign_img.'" width="100px" /></a>';
            }

            $row[] = $sign_img;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($prescription->created_date));  

            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_delete = "";
            $btn_view_pre ="";
            $btn_print_pre="";
            $btn_upload_pre="";
            $btn_view_upload_pre="";
            
            $row[] = '<a class="btn-custom" onClick="return delete_document_file('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';                 
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->document_file->count_all($patient_id),
                        "recordsFiltered" => $this->document_file->count_filtered($patient_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


public function upload_document($patient_id='')
{ 
    $data['page_title'] = 'Upload Document';   
    $data['form_error'] = [];
    $data['document_files_error'] = [];
    $post = $this->input->post();

    $data['form_data'] = array(
                                 'data_id'=>'',
                                 'patient_id'=>$patient_id,
                                 'old_document_files'=>'',
                                 'document_name'=>'',
                              );

    if(isset($post) && !empty($post))
    {   

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        $this->form_validation->set_rules('patient_id', 'patient', 'trim|required'); 
        $this->form_validation->set_rules('document_name', 'Document Name', 'trim|required'); 

        if(!isset($_FILES['document_files']) || empty($_FILES['document_files']['name']))
        {
            $this->form_validation->set_rules('document_files', 'Document file', 'trim|required');  
        }
        
        //echo DIR_UPLOAD_PATH.'patients/document/'; exit;
        if($this->form_validation->run() == TRUE)
        {
             $config['upload_path']   = DIR_UPLOAD_PATH.'patients/document/';  
             $config['allowed_types'] = 'pdf|jpeg|jpg|png'; 
             $config['max_size']      = 10240; 
             $config['encrypt_name'] = TRUE; 
             $this->load->library('upload', $config);
             
             if ($this->upload->do_upload('document_files')) 
             {
                $file_data = $this->upload->data(); 
                $this->document_file->save_file($file_data['file_name']);
                echo 1;
                return false;
              } 
             else
              { 
                $data['document_files_error'] = $this->upload->display_errors();
                $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'],
                                       'old_document_files'=>$post['old_document_files'],
                                       'document_name'=>$post['document_name']
                                       );
              }   
        }
        else
        {
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'old_document_files'=>$post['old_document_files'],
                                        'document_name'=>$post['document_name']
                                       );
            $data['form_error'] = validation_errors();
              

        }   

    }

    $this->load->view('patient/add_document',$data);
}


    

    public function delete_document_file($id="")
    {
       if(!empty($id) && $id>0)
       {
           $result = $this->document_file->delete($id);
           $response = "Document file successfully deleted.";
           echo $response;
       }
    }

    function deleteall_document_file()
    {
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->document_file->deleteall($post['row_id']);
            $response = "Document file successfully deleted.";
            echo $response;
        }
    }
    
    function getAge()
    {
        $post=$this->input->post();
        //echo "<pre>";print_r($post); exit;
        $age_year = $post['year'];
        $age_years = date("Y",strtotime("-".$age_year." year"));
        
        
        $age_month = $post['month'];
        $age_day = $post['day'];
        if(!empty($age_day))
        {
           
           if($age_day>9)
            {
              $dayl = $age_day;
            }
            else
            {
              $dayl = '0'.$age_day; 
            }
           
        }
        else
        {
            $dayl =date('d');
        }
        
        if(!empty($age_month) && $age_month!='-1')
        {
            if($monthl>9)
            {
              $monthl = $age_month;  
            }
            else
            {
              $monthl = '0'.$age_month;  
            }
           
        }
        else
        {
            $monthl =date('m');
        }
       echo  $dob = $dayl.'-'.$monthl.'-'.$age_years;
        
    }
    
    public function print_reg_card()
     {
        $users_data = $this->session->userdata('auth_users');
        $id = $this->input->get('patient_id');
        $data['patient_data'] = $this->patient->get_patient_by_id($id);
        //echo "<pre>"; print_r($data['patient_data']); exit;
        $template_format = $this->patient->template_format($users_data['parent_id']);
        $data['template_data']=$template_format->setting_value;
        $this->load->view('patient/print_reg_card',$data);

     }
     
     
    public function feedback()
    { 
        //unauthorise_permission('70','439');
        $users_data = $this->session->userdata('auth_users'); 
        $data['page_title'] = 'Patient Feedback List'; 
        $this->load->view('patient/feedback',$data);
    }

    public function feedback_ajax_list()
    { 
        //unauthorise_permission('70','439');
        $this->load->model('patient/patient_feedback_model','patient_feedback');
        $users_data = $this->session->userdata('auth_users');  
        $list = $this->patient_feedback->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $feedback) {
         // print_r($examination);die;
            
            $no++;
            $row = array();   
            $row[] = $feedback->patient_code;
            $row[] = $feedback->patient_name;
            $row[] = $feedback->feedback;
            $row[] = $feedback->rating; 
            $row[] = date('d-M-Y h:i A',strtotime($feedback->created_date));  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->patient_feedback->count_all(),
                        "recordsFiltered" => $this->patient_feedback->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    public function print_barcode($id)
    {
        $patient_data = $this->patient->get_by_id($id);
        $data['barcode_id'] = $patient_data['patient_code'];
        $data['total_no']=4;
        if(!empty($data['barcode_id']))
        {
            $this->load->view('patient/barcode',$data);
        }
    }


}
