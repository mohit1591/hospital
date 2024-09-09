<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_procedures extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('dialysis_procedures/dialysis_procedures_model','prescription');
        $this->load->library('form_validation');
    }

    
  public function index($dialysis_id='',$patient_id='')
  {
        //unauthorise_permission(86,539);
        $data['page_title'] = 'Dialysis Procedure Bill List';
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
      $data['form_data'] = array('patient_name'=>'','mobile_no'=>'','patient_code'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'dialysis_no'=>'','dialysis_id'=>$dialysis_id,'patient_id'=>$patient_id);
      $this->load->model('general/general_model');
      $data['specialization_list'] = $this->general_model->specialization_list();
      $this->load->view('dialysis_procedures/list',$data);
  }

    public function ajax_list()
    {   

        //unauthorise_permission(86,539);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->prescription->get_datatables();  
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
            $row[] = '<input type="checkbox" name="prescription[]" class="checklist" value="'.$prescription->id.'">'.$check_script;
            $row[] = $prescription->booking_code;
            $row[] = $prescription->patient_code;    
            $row[] = $prescription->patient_name;
            $row[] = date('d-m-Y',strtotime($prescription->dialysis_booking_date));
            $row[] = date('d-m-Y',strtotime($prescription->dialysis_date));
            $row[] = $prescription->mobile_no;
            
            //$row[] = $status; 
           

            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_delete = "";
            $btn_view_pre ="";
            $btn_print_pre="";
            $btn_upload_pre="";
            $btn_view_upload_pre="";
            $btn_print_pr_letter='';
            
            if($users_data['parent_id']==$prescription->branch_id)
            {
               
                $print_url = "'".base_url('dialysis_booking/print_dialysis_booking_report/'.$prescription->booking_id)."'";
                $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';         
            }

               
       /*   $btn_view_upload_pre='';
        if($users_data['parent_id']==$prescription->branch_id)
        {

            
            if(in_array('542',$users_data['permission']['action'])) 
            {
                $btn_upload_pre = '<a class="btn-custom" onclick="return upload_prescription('.$prescription->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> Upload Prescription </a>';
                
                $btn_upload_pre = '<a class="btn-custom" onclick="return upload_prescription('.$prescription->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> Upload Prescription </a>';
                
                $btn_view_upload_pre = '<a class="btn-custom" href="'.base_url('/dialysis_prescription/view_files/'.$prescription->id).'" title="View Prescription" data-url="512"><i class="fa fa-circle"></i> View Prescription Files</a>';
            }
            
               
            
              
        }*/
        
          
            // End Action Button //

            $row[] =   $btnprint;                
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription->count_all(),
                        "recordsFiltered" => $this->prescription->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
       
        $data['form_data'] = array(
                                    "start_date"=>'',//date('d-m-Y')
                                    "end_date"=>'',//date('d-m-Y')
                                    "patient_code"=>"", 
                                    "patient_name"=>"",
                                    "mobile_no"=>"",
                                    'branch_id'=>'',
                                    'dialysis_no'=>''
                                    );
        if(isset($post) && !empty($post))
        {
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('prescription_search', $marge_post);

        }
        $prescription_search = $this->session->userdata('prescription_search');
        if(isset($prescription_search) && !empty($prescription_search))
        {
            $data['form_data'] = $prescription_search;
        }
        $this->load->view('dialysis_procedures/advance_search',$data);
    }
    
    public function reset_search()
    {
        $this->session->unset_userdata('prescription_search');
    }

   

	
     
    
 
    public function delete($id="")
    {
       //unauthorise_permission(86,535);
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription->delete($id);
           $response = "Prescription successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        ///unauthorise_permission(86,535);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription->deleteall($post['row_id']);
            $response = "Prescription successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     // unauthorise_permission(86,117);   
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->prescription->get_by_id($id);  
        $data['page_title'] = $data['form_data']['patient_name']." detail";
        $this->load->view('dialysis_prescription/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission(86,536);
        $data['page_title'] = 'Prescription Archive List';
        $this->load->helper('url');
        $this->load->view('dialysis_prescription/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(86,536);
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('dialysis_prescription/prescription_archive_model','prescription_archive'); 

        $list = $this->prescription_archive->get_datatables();   
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
            ///// State name ////////
            $state = "";
            if(!empty($prescription->state))
            {
                $state = " ( ".ucfirst(strtolower($prescription->state))." )";
            }
            //////////////////////// 
            
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
            $row[] = '<input type="checkbox" name="prescription[]" class="checklist" value="'.$prescription->id.'">'.$check_script;
            $row[] = $prescription->booking_code;
            $row[] = $prescription->patient_code;    
            $row[] = $prescription->patient_name;
            $row[] = $prescription->mobile_no;
            
            $row[] = $status; 
            $row[] = date('d-M-Y',strtotime($prescription->created_date));  
            
            //Action button /////
            $btn_restore = ""; 
            $btn_view = "";
            $btn_delete = "";

            //if(in_array('537',$users_data['permission']['action'])) 
            //{
               $btn_restore = ' <a onClick="return restore_prescription('.$prescription->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            //} 
            
            
           /* if(in_array('540',$users_data['permission']['action'])) 
            {
                $btn_view = ' <a class="btn-custom" onclick="return view_prescription('.$prescription->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
            } 
            */
            
            //if(in_array('538',$users_data['permission']['action'])) 
            //{
                $btn_delete = ' <a onClick="return trash('.$prescription->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            //} 
            // End Action Button //
 
           $row[] = $btn_restore.$btn_view.$btn_delete; 

            $row[] = '  
            '; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription_archive->count_all(),
                        "recordsFiltered" => $this->prescription_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        // unauthorise_permission(86,537);
        $this->load->model('dialysis_prescription/prescription_archive_model','prescription_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_archive->restore($id);
           $response = "Prescription successfully restore in prescription type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        // unauthorise_permission(86,537);
        $this->load->model('dialysis_prescription/prescription_archive_model','prescription_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_archive->restoreall($post['row_id']);
            $response = "Prescription successfully restore in prescription list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         // unauthorise_permission(86,538);
        $this->load->model('dialysis_prescription/prescription_archive_model','prescription_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_archive->trash($id);
           $response = "Prescription successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
          //unauthorise_permission(86,538);
        $this->load->model('dialysis_prescription/prescription_archive_model','prescription_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_archive->trashall($post['row_id']);
            $response = "Prescription successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function prescription_dropdown()
  {
      $patient_list = $this->prescription->employee_type_list();
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


    public function print_prescription($id)
    { 
        
        $data['form_data'] = $this->prescription->get_by_ids($id);
        $data['prescription_data'] = $this->prescription->get_opd_prescription($id); 
        $data['test_data'] = $this->prescription->get_opd_test($id); 
        $data['page_title'] = $data['form_data']['patient_name']." Prescription";
        $this->load->view('dialysis_prescription/print_template1',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("dialysis_prescription.pdf");

    }


    public function print_prescriptions($prescription_id="",$branch_id='')
    {
         
        $prescriptions_id= $this->session->userdata('dialysis_prescription_id');
        $data['vital_sets'] = get_setting_value('VITAL_PRINT_SETTING');
        if(!empty($prescriptions_id))
        {
            $prescription_id = $prescriptions_id;
        }
        else
        {
            $prescription_id =$prescription_id;
        }

        $data['type'] = 2;
        $data['prescription_id'] = $prescription_id;
        $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->prescription->get_detail_by_prescription_id($prescription_id);

        $template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
         $data['vital_show']=$template_format_bottom->vital_show;
        $data['template_data']=$template_format->setting_value;
        
        $data['header_content']=$template_format->header_content;
        $data['all_detail']= $opd_prescription_info;
        
        $data['prescription_tab_setting'] = get_dialysis_prescription_print_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_print_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['all_detail']['prescription_list'][0]); exit;
        $signature_image = get_attended_doctor_signature($data['all_detail']['prescription_list'][0]->attended_doctor);
        
        $signature_image_new = get_doctor_signature($data['all_detail']['prescription_list'][0]->attended_doctor);
        
       // echo "<pre>"; print_r($signature_image_new); exit;
        
   $signature_reprt_data ='';
   if(!empty($signature_image))
   {
   //echo "<pre>";print_r($signature_image); exit;
     $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;">';
      
      
      
       if(!empty($signature_image->signature) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->signature))
      {
      
      $signature_reprt_data .='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->signature.'"></div></td>
      </tr>';
      
       }
       
       
      
      $signature_reprt_data .='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="text-align:center;"><b>Signature</b></td>
      </tr>';
      
      $signature_reprt_data.='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;">Dr.'.$signature_image->doctor_name.'</div></td>
      </tr>';
      
       $signature_reprt_data.='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;">'.$signature_image_new->signature.'</div></td>
      </tr>';
      
     
       
      $signature_reprt_data .='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
      </tr>
      
    </table>';

   }
   elseif(!empty($signature_image_new))
   {
        
        
   //echo "<pre>";print_r($signature_image); exit;
     $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
      <tr>
      <td width="70%"></td>
        <td valign="top" align="" style="text-align:center;"><b>Signature</b></td>
      </tr>';
      
      $signature_reprt_data.='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;">Dr.'.$signature_image->doctor_name.'</div></td>
      </tr>';
      
      if(!empty($signature_image_new->signature) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image_new->sign_img))
      {
      
      $signature_reprt_data .='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image_new->sign_img.'"></div></td>
      </tr>';
      
       }
       
       
      $signature_reprt_data .='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image_new->sign_img).'</b></td>
      </tr>
      
    </table>';

       
   }
   


        $data['signature'] = $signature_reprt_data;
        //$data['file_name'] = $file_name;
        $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();

        $data['form_data'] = $this->prescription->get_by_ids($prescription_id);
           $this->load->view('dialysis_prescription/print_prescription_template',$data);  
        //}
        
    }


    public function print_blank_prescriptions($booking_id="",$branch_id='')
    {
        $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->prescription->get_detail_by_booking_id($booking_id,$branch_id);
        $template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>1),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>1),$branch_id);
        $template_format_right = $this->prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>1),$branch_id);
        $template_format_top = $this->prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>1));
        $template_format_bottom = $this->prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>1),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['header_content']=$template_format->header_content;
        
        $data['template_data']=$template_format->setting_value;
        $data['header_content']=$template_format->header_content;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_prescription_print_tab_setting();
        //print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $data['prescription_id'] = $booking_id;
        $data['type'] = 1;

        $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['vital_sets'] = get_setting_value('VITAL_PRINT_SETTING');
        $this->load->view('dialysis_prescription/print_prescription_template',$data);
    }




    public function print_prescription_pdf($id)
    {
            $opd_prescription_info = $this->prescription->get_by_ids($id);
            $prescription_opd_prescription_info = $this->prescription->get_opd_prescription($id); 
            $prescription_opd_test_info = $this->prescription->get_opd_test($id);
            
            $booking_id = $opd_prescription_info['booking_id'];

            $this->load->model('opd/opd_model','opd');
            $opd_details = $this->opd->get_by_id($booking_id);
            $referral_doctor = $opd_details['referral_doctor'];
            
            $patient_code = $opd_prescription_info['patient_code'];
            $attended_doctor = $opd_prescription_info['attended_doctor'];
            $specialization_id = $opd_details['specialization_id'];
            $booking_code = $opd_prescription_info['booking_code'];
            $patient_name = $opd_prescription_info['patient_name'];
            $mobile_no = $opd_prescription_info['mobile_no'];
            $gender = $opd_prescription_info['gender'];
            $age_y = $opd_prescription_info['age_y'];
            $age_m = $opd_prescription_info['age_m'];
            $age_d = $opd_prescription_info['age_d'];
            $patient_bp = $opd_prescription_info['patient_bp'];
            $patient_temp = $opd_prescription_info['patient_temp'];
            $patient_weight = $opd_prescription_info['patient_weight'];
            $patient_height = $opd_prescription_info['patient_height'];
            $patient_rbs = $opd_prescription_info['patient_rbs'];
            $patient_spo2 = $opd_prescription_info['patient_spo2'];
            $next_appointment_date = $opd_prescription_info['next_appointment_date'];
            $prv_history = $opd_prescription_info['prv_history'];
            $personal_history = $opd_prescription_info['personal_history'];
            $chief_complaints = $opd_prescription_info['chief_complaints'];
            $examination = $opd_prescription_info['examination'];
            $diagnosis = $opd_prescription_info['diagnosis'];
            $suggestion = $opd_prescription_info['suggestion'];
            $remark = $opd_prescription_info['remark'];
            if($opd_prescription_info['appointment_date']!='0000-00-00')
            {
                $appointment_date =  date('d-M-Y H:i A',strtotime($opd_prescription_info['appointment_date']));
            }
            else
            {
                $appointment_date ="";
            }
            

            $genders = array('0'=>'Female','1'=>'Male');
            $gender = $genders[$gender];

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
            $doctor_name = get_doctor_name($attended_doctor);
            $referral_doctor = get_doctor_name($referral_doctor);
            
          $prescription_tab_setting = get_prescription_tab_setting(); 
            $presc_prescriptions = '';
          foreach ($prescription_tab_setting as $value) 
          {
            $test_content = '';
            if(!empty($prescription_opd_test_info)) 
            {
               if(strcmp(strtolower($value->setting_name),'test_result')=='0'){
               $test_content .= '<div style="width:100%;font-weight:bold;text-align: center;margin-top:5%;text-decoration:underline;">'.$value->setting_value.'</div>';
             
                
             $test_content .= '<table border="1" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
                <tr><td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;font-weight:bold;">Test Name</div></td></tr>';
            
                      
                    foreach ($prescription_opd_test_info as $testdata) {  
                       $test_content .= '<tr><td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;">'.$testdata->test_name.'</div></td></tr>';
                         
                      }  
                    
                   
               $test_content .='</table>';

            }

            }
        if(!empty($prescription_opd_prescription_info)) {   
           if(strcmp(strtolower($value->setting_name),'prescription')=='0'){
           
           $presc_prescriptions .= '<table border="1" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
            <tr>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Name</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Type</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Dose</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Frequency</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Duration</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Advice</u></th>
          </tr>';
        
          
                foreach ($prescription_opd_prescription_info as $prescriptiondata) { 
            $presc_prescriptions .='<tr>
                          <td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;">'.$prescriptiondata->medicine_name.'</div></td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_type.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_dose.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_frequency.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_duration.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$prescriptiondata->medicine_advice.'</td>
                      </tr>';
                }  
                   
                

        $presc_prescriptions .='</table>';  
        }


        } 


        if(!empty($suggestion))
        {
        if(strcmp(strtolower($value->setting_name),'suggestions')=='0'){
        $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
          </tr>

          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$suggestion.'</div></td>
          </tr>
        </table>';
        }
        }
        if(!empty($prv_history))
        {
            if(strcmp(strtolower($value->setting_name),'prv_history')=='0'){
                $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
                  <tr>
                    <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
                  </tr>

                  <tr>
                    <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$prv_history.'</div></td>
                  </tr>
                </table>';
            }
        }
        if(!empty($personal_history))
        {
        if(strcmp(strtolower($value->setting_name),'personal_history')=='0'){

        $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
          </tr>

          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$personal_history.'</div></td>
          </tr>
        </table>';
        }
        }
        if(!empty($chief_complaints))
        {
          if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0')
          {
            $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
              <tr>
                <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
              </tr>

              <tr>
                <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$chief_complaints.'</div></td>
              </tr>
            </table>';
        }
        }
        if(!empty($examination))
        {
        if(strcmp(strtolower($value->setting_name),'examination')=='0')
        {
        $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
          </tr>

          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$examination.'</div></td>
          </tr>
        </table>';
        }
        }
        if(!empty($diagnosis))
        {
        if(strcmp(strtolower($value->setting_name),'diagnosis')=='0'){ 
        $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
          </tr>

          <tr>
            <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$diagnosis.'</div></td>
          </tr>
        </table>';
        }
        }
        if(!empty($appointment_date) && $appointment_date!=='0000-00-00')
        {
         if(strcmp(strtolower($value->setting_name),'appointment')=='0')
         { 
            $presc_prescriptions .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
              <tr>
                <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><b>'.$value->setting_value.'</b></td>
              </tr>

              <tr>
                <td valign="top" align="left" style="text-align:left;padding-left: 4px;"><div style="float:left;">'.$appointment_date.'</div></td>
              </tr>
            </table>';

        }
    }

    }


         
         $pdf_data['patient_code'] = $patient_code;
         $pdf_data['patient_name'] = $patient_name;
         $pdf_data['presc_prescriptions'] = $presc_prescriptions;
         $pdf_data['test_content'] = $test_content;
         $pdf_data['gender'] = $gender;
         $pdf_data['patient_age'] = $patient_age;
         $pdf_data['referral_doctor'] = $referral_doctor;
         $pdf_data['doctor_name'] = $doctor_name;
         $pdf_data['booking_code'] = $booking_code;
         $pdf_data['mobile_no'] = $mobile_no;
         $pdf_data['specialization_id'] = get_specilization_name($specialization_id);
         //$pdf_data['suggestions'] = $suggestion;
         $pdf_data['prv_history'] = $prv_history;
         $pdf_data['chief_complaints'] = $chief_complaints;
         $pdf_data['examination'] = $examination;
         //$pdf_data['personal_history'] = $personal_history;
         $pdf_data['patient_bp']=$patient_bp;
         $pdf_data['patient_temp']=$patient_temp;
         $pdf_data['patient_weight']=$patient_weight;
         $pdf_data['patient_height']=$patient_height;
         $pdf_data['patient_spo2']=$patient_spo2;
         $pdf_data['patient_rbs']=$patient_rbs;
         $pdf_data['appointment_date'] = $appointment_date;
         $pdf_data['diagnosis'] = $diagnosis;
         $pdf_template = $this->load->view('dialysis_prescription/pdf_template',$pdf_data,true);
         $result['success'] = true;
         $result['msg'] = 'Print prescription success';
         $result['pdf_template'] = $pdf_template;
         echo @json_encode($result);
    }

    


public function print_blank_prescription_pdf($id)
{
        $opd_prescription_info = $this->prescription->get_by_ids($id);
        //echo "<pre>";print_r($opd_prescription_info); exit;
        $prescription_opd_prescription_info = $this->prescription->get_opd_prescription($id); 
        $prescription_opd_test_info = $this->prescription->get_opd_test($id);
        
        $booking_id = $opd_prescription_info['booking_id'];

        $this->load->model('opd/opd_model','opd');
        
        $opd_details = $this->opd->get_by_id($id);

        $this->load->model('patient/patient_model','patient');
        $patient_id = $opd_details['patient_id'];
        $patient_details = $this->patient->get_by_id($patient_id);
        //print_r($patient_details);
        $referral_doctor = $opd_details['referral_doctor'];
        $attended_doctor = $opd_details['attended_doctor'];
        
        $specialization_id = $opd_details['specialization_id'];
        $patient_code = $patient_details['patient_code'];
        //$attended_doctor = $opd_prescription_info['attended_doctor'];
        
        $booking_code = $opd_prescription_info['booking_code'];
        $simulation_id = get_simulation_name($patient_details['simulation_id']);
        $patient_name = $simulation_id .' '.$patient_details['patient_name'];
        $mobile_no = $patient_details['mobile_no'];
        $gender = $patient_details['gender'];
        $age_y = $patient_details['age_y'];
        $age_m = $patient_details['age_m'];
        $age_d = $patient_details['age_d'];
        
        $patient_bp = $opd_prescription_info['patient_bp'];
        $patient_temp = $opd_prescription_info['patient_temp'];
        $patient_weight = $opd_prescription_info['patient_weight'];
        $patient_height = $opd_prescription_info['patient_height'];
        $patient_rbs = $opd_prescription_info['patient_rbs'];
        $patient_spo2 = $opd_prescription_info['patient_spo2'];
        $next_appointment_date = $opd_prescription_info['next_appointment_date'];
        $prv_history = $opd_prescription_info['prv_history'];
        $personal_history = $opd_prescription_info['personal_history'];
        $chief_complaints = $opd_prescription_info['chief_complaints'];
        $examination = $opd_prescription_info['examination'];
        $diagnosis = $opd_prescription_info['diagnosis'];
        $suggestion = $opd_prescription_info['suggestion'];
        $remark = $opd_prescription_info['remark'];
        if($opd_prescription_info['appointment_date']!='0000-00-00')
        {
            $appointment_date =  $opd_prescription_info['appointment_date'];
        }
        else
        {
            $appointment_date ="";
        }
        

        $genders = array('0'=>'Female','1'=>'Male');
        $gender = $genders[$gender];

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
        $doctor_name = get_doctor_name($attended_doctor);
        $referral_doctor = get_doctor_name($referral_doctor);
        $specialization_id = get_specilization_name($specialization_id);
        $prescription_tab_setting = get_prescription_tab_setting(); 
        $presc_prescriptions = '';
        $test_content ="";
      


     $pdf_data['doctor_name'] = $doctor_name;
     $pdf_data['specialization_id'] = $specialization_id;    
     $pdf_data['patient_code'] = $patient_code;
     $pdf_data['patient_name'] = $patient_name;
     $pdf_data['presc_prescriptions'] = $presc_prescriptions;
     $pdf_data['test_content'] = $test_content;
     $pdf_data['gender'] = $gender;
     $pdf_data['patient_age'] = $patient_age;
     $pdf_data['referral_doctor'] = $referral_doctor;
     $pdf_data['doctor_name'] = $doctor_name;
     $pdf_data['booking_code'] = $booking_code;
     $pdf_data['mobile_no'] = $mobile_no;
     //$pdf_data['suggestions'] = $suggestion;
     $pdf_data['prv_history'] = $prv_history;
     $pdf_data['chief_complaints'] = $chief_complaints;
     $pdf_data['examination'] = $examination;
     //$pdf_data['personal_history'] = $personal_history;
     $pdf_data['patient_bp']='';
     $pdf_data['patient_temp']='';
     $pdf_data['patient_weight']='';
     $pdf_data['patient_height']='';
     $pdf_data['patient_spo2']='';
     $pdf_data['patient_rbs']='';
     $pdf_data['appointment_date'] = '';
     $pdf_data['diagnosis'] = '';
     
     $pdf_template = $this->load->view('dialysis_prescription/pdf_template',$pdf_data,true);
     $result['success'] = true;
     $result['msg'] = 'Print Dialysis prescription success';
     $result['pdf_template'] = $pdf_template;
     
    
    
    echo @json_encode($result);
}

public function view_prescription($id,$branch_id='')
{ 
    $data['form_data'] = $this->prescription->get_by_ids($id);
    $data['prescription_data'] = $this->prescription->get_opd_prescription($id); 
    $data['test_data'] = $this->prescription->get_opd_test($id); 
    $data['page_title'] = $data['form_data']['patient_name']." Prescription";
    $data['prescription_tab_setting'] = get_dialysis_prescription_tab_setting('',$branch_id);
    $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_tab_setting('',$branch_id);
    $this->load->model('general/general_model');
    $data['vitals_list']=$this->general_model->vitals_list(); 
    $data['id'] = $id;
    $this->load->view('dialysis_prescription/print_template1',$data);
    $html = $this->output->get_output();
}

public function upload_prescription($prescription_id='',$booking_id='')
{ 
  /*if($prescription_id=='0' && !empty($booking_id))
    {
      $this->load->model('opd/opd_model','opd');
      $prescription_id=$this->opd->save_prescription_before_file_upload($booking_id);
    }*/
    $data['page_title'] = 'Upload Prescription';   
    $data['form_error'] = [];
    $data['prescription_files_error'] = [];
    $post = $this->input->post();

    $data['form_data'] = array(
                                 'data_id'=>'',
                                 'prescription_id'=>$prescription_id,
                                 'old_prescription_files'=>''
                              );

    if(isset($post) && !empty($post))
    {   

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        $this->form_validation->set_rules('prescription_id', 'prescription', 'trim|required'); 
        if(!isset($_FILES['prescription_files']) || empty($_FILES['prescription_files']['name']))
        {
        	$this->form_validation->set_rules('prescription_files', 'prescription file', 'trim|required');  
        }
        
        
        if($this->form_validation->run() == TRUE)
        {
             $config['upload_path']   = DIR_UPLOAD_PATH.'dialysis/prescription/';  
             $config['allowed_types'] = 'pdf|jpeg|jpg|png|doc|docx'; 
             $config['max_size']      = 3072; 
             $config['encrypt_name'] = TRUE; 
             $this->load->library('upload', $config);
             
             if ($this->upload->do_upload('prescription_files')) 
             {
                $file_data = $this->upload->data(); 
                $this->prescription->save_file($file_data['file_name']);
                echo 1;
                return false;
              } 
             else
              { 
                $data['prescription_files_error'] = $this->upload->display_errors();
                $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'prescription_id'=>$post['prescription_id'],
                                       'old_prescription_files'=>$post['old_prescription_files']
                                       );
              }   
        }
        else
        {
        	
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'prescription_id'=>$post['prescription_id'], 
                                        'old_prescription_files'=>$post['old_prescription_files']
                                       );
            $data['form_error'] = validation_errors();
        	  

        }   

    }

  
    $this->load->view('dialysis_prescription/add_file',$data);
}


public function view_files($prescription_id)
{ 
	//$data['form_data'] = $this->prescription->get_prescription_files($prescription_id);
    
	$data['page_title'] = "Dialysis Prescription Files";
	$data['prescription_id'] = $prescription_id;
	$this->load->view('dialysis_prescription/view_prescription_files',$data);
}


public function ajax_file_list($prescription_id='')
{ 
        
        //unauthorise_permission(86,113);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->prescription_file->get_datatables($prescription_id);  
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
            $row[] = '<input type="checkbox" name="prescription[]" class="checklist" value="'.$prescription->id.'">'.$check_script;

            $sign_img = "";
            if(!empty($prescription->prescription_files) && file_exists(DIR_UPLOAD_PATH.'dialysis/prescription/'.$prescription->prescription_files))
            {
                $sign_img = ROOT_UPLOADS_PATH.'dialysis/prescription/'.$prescription->prescription_files;
                //$sign_img = '<img src="'.$sign_img.'" width="100px" />';
                $sign_img = '<a href="'.$sign_img.'" download><img src="'.$sign_img.'" width="100px"/></a>';
            }

            $row[] = $prescription->doc_name;
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
            
            $row[] = '<a class="btn-custom" onClick="return delete_prescription_file('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';                 
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription_file->count_all($prescription_id),
                        "recordsFiltered" => $this->prescription_file->count_filtered($prescription_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    

    public function delete_prescription_file($id="")
    {
       //unauthorise_permission(86,116);
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_file->delete($id);
           $response = "Prescription file successfully deleted.";
           echo $response;
       }
    }

    function deleteall_prescription_file()
    {
       // unauthorise_permission(86,116);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_file->deleteall($post['row_id']);
            $response = "Prescription file successfully deleted.";
            echo $response;
        }
    }


    public function edit($prescription_id="")
    { 
       //unauthorise_permission(85,524);
      if(isset($prescription_id) && !empty($prescription_id) && is_numeric($prescription_id))
      {      
        $this->load->model('general/general_model');
        $this->load->model('opd/opd_model','opd'); 
        $this->load->model('dialysis_booking/dialysis_booking_model','dialysisbooking'); 
        $post = $this->input->post();
        $get_by_id_data = $this->prescription->get_by_prescription_id($prescription_id); 
        //echo "<pre>";print_r($get_by_id_data); exit;
        $prescription_data = $get_by_id_data['prescription_list'][0];
        $prescription_test_list = $get_by_id_data['prescription_list']['prescription_test_list'];
        $prescription_presc_list = $get_by_id_data['prescription_list']['prescription_presc_list'];

        //echo "<pre>";print_r($prescription_data); exit;
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        /*$data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['employee_list'] = $this->opd->employee_list();
        $data['profile_list'] = $this->opd->profile_list();
        $data['dept_list'] = $this->general_model->department_list(); */
        $data['chief_complaints'] = $this->general_model->chief_complaint_list(); 
        $data['examination_list'] = $this->opd->examinations_list();
        $data['diagnosis_list'] = $this->opd->diagnosis_list();  
        $data['suggetion_list'] = $this->opd->suggetion_list();  
        $data['prv_history'] = $this->opd->prv_history_list();  
        $data['personal_history'] = $this->opd->personal_history_list();
        $data['template_list'] = $this->dialysisbooking->template_list();    
        $data['prescription_tab_setting'] = get_dialysis_prescription_tab_setting();
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['page_title'] = "Update Prescription";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        
        if(!empty($prescription_data->next_appointment_date) && $prescription_data->next_appointment_date!='0000-00-00 00:00:00' && date('d-m-Y',strtotime($prescription_data->next_appointment_date))!='01-01-1970')
        {
            $next_appointmentdate = date('d-m-Y',strtotime($prescription_data->next_appointment_date));
            
            $next_appointmenttime = date('H:i A',strtotime($prescription_data->next_appointment_date));
        }
        else
        {
            $next_appointmentdate = ''; 
            $next_appointmenttime='';
        }
        
        //present age of patient
          $modified_array =[];
            $modified_array['age_y'] = $prescription_data->age_y;
            $modified_array['age_m'] = $prescription_data->age_m;
            $modified_array['age_d'] = $prescription_data->age_d;
            $modified_array['patient_modified_date'] = $prescription_data->patient_modified_date;

          if($prescription_data->dob=='1970-01-01' || $prescription_data->dob=="0000-00-00")
          {
            $present_age = get_patient_present_age('',$modified_array);
            //echo "<pre>"; print_r($present_age);
          }
          else
          {
            $dob=date('d-m-Y',strtotime($prescription_data->dob));
            
            $present_age = get_patient_present_age($dob,$modified_array);
          }
          
          $age_y = $present_age['age_y'];
          $age_m = $present_age['age_m'];
          $age_d = $present_age['age_d'];
          //$age_h = $present_age['age_h'];
          //present age of patient
        $data['form_data'] = array(
                              'data_id'=>$prescription_id, 
                              'patient_id'=>$prescription_data->patient_id,
                              'booking_id'=>$prescription_data->booking_id,
                              'attended_doctor'=>$prescription_data->attended_doctor,
                              'appointment_date'=>$prescription_data->appointment_date,
                              'patient_code'=>$prescription_data->patient_code,
                              'booking_code'=>$prescription_data->booking_code,
                              'simulation_id'=>$prescription_data->simulation_id,
                              'patient_name'=>$prescription_data->patient_name,
                              'mobile_no'=>$prescription_data->mobile_no,
                              'gender'=>$prescription_data->gender,
                              'age_y'=>$age_y,
                              'age_m'=>$age_m,
                              'age_d'=>$age_d,
                              'address'=>$prescription_data->address,
                              'city_id'=>$prescription_data->city_id,
                              'state_id'=>$prescription_data->state_id,
                              'country_id'=>$prescription_data->country_id,
                              
                              'prv_history'=>$prescription_data->prv_history,
                              'personal_history'=>$prescription_data->personal_history,
                              'chief_complaints'=>$prescription_data->chief_complaints,
                              'examination'=>$prescription_data->examination,
                              'diagnosis'=>$prescription_data->diagnosis,
                              'suggestion'=>$prescription_data->suggestion,
                              'remark'=>$prescription_data->remark,
                              'next_appointment_date'=>$next_appointmentdate,
                              'next_appointment_time'=>$next_appointmenttime,
                              "relation_name"=>$prescription_data->relation_name,
                              "relation_type"=>$prescription_data->relation_type,
                              "relation_simulation_id"=>$prescription_data->relation_simulation_id,
                              );

        $data['prescription_test_list'] = $prescription_test_list;
        $data['prescription_presc_list'] = $prescription_presc_list;                      
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $prescription_id = $this->prescription->save_prescription();
                $this->session->set_userdata('dialysis_prescription_id',$prescription_id);
                $this->session->set_flashdata('success','Prescription updated successfully.');
                redirect(base_url('dialysis_prescription/?status=print'));

                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            } 

            

        }
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
       $data['prescription_medicine_tab_setting'] = get_dialysis_prescription_medicine_tab_setting(); 
       $this->load->view('dialysis_prescription/prescription',$data);       
      }
    }



    private function _validate()
    {
        $field_list = mandatory_section_field_list(4);
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
       	
       
       
        if ($this->form_validation->run() == FALSE) 
        {  
           
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'booking_code'=>$post['booking_code'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        'gender'=>$post['gender'],
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        'address'=>$post['address'],
                                        /*'patient_bp'=>$post['patient_bp'],
                                        'patient_temp'=>$post['patient_temp'],
                                        'patient_weight'=>$post['patient_weight'],
                                        'patient_height'=>$post['patient_height'],
                                        'patient_sop'=>$post['patient_sop'],
                                        'patient_rbs'=>$post['patient_rbs'],*/
                                        'diseases'=>$post['diseases'],
                                        
                                        
                                       ); 
            return $data['form_data'];
        }   
    }


    function get_test_vals($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->prescription->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
    
public function share_video_link($prescription_id='',$booking_id='')
{ 
    if($prescription_id=='0' && !empty($booking_id))
    {
      $this->load->model('opd/opd_model','opd');
      $prescription_id=$this->opd->save_prescription_before_file_upload($booking_id);
    }
    
    $data['page_title'] = 'Share Video Consultation Link';   
    $data['form_error'] = [];
    $post = $this->input->post();
    
    $this->load->model('opd/opd_model','opd');
    $get_by_id_data = $this->prescription->get_by_prescription_id($prescription_id); 
    
    $prescription_data = $get_by_id_data['prescription_list'][0];
    $booking_id = $prescription_data->booking_id;
    $opd_details = $this->opd->get_by_id($booking_id);
    /*'attended_doctor'=,
      'appointment_date'=>$prescription_data->appointment_date,
      'patient_code'=>$prescription_data->patient_code,
      'booking_code'=>$prescription_data->booking_code,
      'simulation_id'=>$prescription_data->simulation_id,
      'patient_name'=>$prescription_data->patient_name,
      'mobile_no'=>$prescription_data->mobile_no,*/
      
    
    //echo "<pre>"; print_r($opd_details); exit;
    $this->load->model('patient/patient_model','patient');
    $this->load->model('doctors/doctors_model','doctors');
    $patient_id = $opd_details['patient_id'];
    $patient_details = $this->patient->get_by_id($patient_id);
    $attended_doctor = $opd_details['attended_doctor'];
    $patient_code = $patient_details['patient_code'];
    $booking_code = $opd_prescription_info['booking_code'];
    $patient_name = $simulation_id .' '.$patient_details['patient_name'];
    $mobile_no = $patient_details['mobile_no'];
    $doctor_name = get_doctor_name($attended_doctor);
    
    $doctor_details = $this->doctors->get_by_id($attended_doctor);
    $doctor_name = $doctor_details['doctor_name'];
    $doctor_mobile_no = $doctor_details['mobile_no'];
    //$milliseconds = round(microtime(true) * 1000);
    //echo "<pre>"; print_r($opd_details); exit;
    $data['form_data'] = array(
                                 'data_id'=>'',
                                 'prescription_id'=>$prescription_id,
                                 'booking_id'=>$booking_id,
                                 'patient_name'=>$patient_name,
                                 //'patient_code'=>$patient_code,
                                 'patient_email'=>$patient_details['patient_email'],
                                 'doctor_mobile_no'=>$doctor_mobile_no,
                                 'patient_code'=>$patient_code,
                                 'patient_phone'=>$mobile_no,
                                 'doctor_name'=>$doctor_name,
                                 'appointment_utc_time'=>'',
                                 'appointment_expireby_time'=>'',
                              );

    if(isset($post) && !empty($post))
    {   

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        //$this->form_validation->set_rules('prescription_id', 'prescription', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'Patient Name', 'trim|required');  
        $this->form_validation->set_rules('patient_phone', 'Patient Phone', 'trim|required');  
        
        
        
        if($this->form_validation->run() == TRUE)
        {
             
                $this->prescription->save_video($post);
                echo 1;
                return false;
              
        }
        else
        {
        	
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'prescription_id'=>$post['prescription_id'], 
                                        'booking_id'=>$post['booking_id'],
                                         'patient_name'=>$post['patient_name'],
                                         //'patient_code'=>$post['patient_code'],
                                         'patient_email'=>$post['patient_email'],
                                         'doctor_mobile_no'=>$post['doctor_mobile_no'],
                                         'patient_code'=>$post['patient_code'],
                                         'patient_phone'=>$post['mobile_no'],
                                         'doctor_name'=>$post['doctor_name'],
                                         'appointment_utc_time'=>$post['appointment_utc_time'],
                                         'appointment_expireby_time'=>$post['appointment_expireby_time'],
                                       );
            $data['form_error'] = validation_errors();
        	  

        }   

    }
    $this->load->view('dialysis_prescription/add_video',$data);
}


public function view_video($prescription_id)
{ 
	$data['page_title'] = "Prescription Video Link";
	$data['prescription_id'] = $prescription_id;
	$this->load->view('dialysis_prescription/view_video',$data);
}


public function ajax_video_list($prescription_id='')
{ 
        $users_data = $this->session->userdata('auth_users');
        $list = $this->prescription_video->get_datatables($prescription_id);  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescription) { 
            $no++;
            $row = array();
            $check_script = "";
            $row[] = '<input type="checkbox" name="prescription[]" class="checklist" value="'.$prescription->id.'">'.$check_script;
            $row[] = $prescription->patient_name;
            $row[] = $prescription->doctor_name;
            $row[] = $prescription->serialId;
            $row[] = $prescription->conversation_id;
            
            $row[] = '<a target="_blank" href="'.$prescription->patient_url.'" title="Patient Video Link" data-url="512">'.$prescription->patient_url.'</a>';
            $row[] = '<a  href="'.$prescription->doctor_url.'" title="Doctor Video Link" data-url="512">'.$prescription->doctor_url.'</a>';
            $row[] = $prescription->start_valid;
            $row[] = $prescription->finish_valid;
            $row[] = $prescription->passcode;
            
            $row[] = date('d-M-Y H:i A',strtotime($prescription->created_date));  

            $row[] = '<a class="btn-custom" onClick="return delete_video('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';                 
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription_video->count_all($prescription_id),
                        "recordsFiltered" => $this->prescription_video->count_filtered($prescription_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function download_prescription($prescription_id="",$branch_id='')
    {   
        
        $booking_id = $prescription_id;
        //echo $branch_id; die;
        if(!empty($booking_id) && !empty($branch_id))
        { 
        
        $opd_prescription_info = $this->prescription->get_detail_by_prescription_letterhead($booking_id,$branch_id);
        
        //echo "<pre>"; print_r($opd_prescription_info); die;
       
        $template_format =$this->prescription->template_format_letterhead(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>3),$branch_id);

            $page_header=$template_format->page_header;            
            $header_replace_part=$template_format->page_details;
            $page_middle=$template_format->page_middle;
            $page_footer=$template_format->page_footer;

      
        $all_detail= $opd_prescription_info;
        
        //echo "<pre>"; print_r($all_detail); exit;

        $prescription_tab_setting = get_dialysis_prescription_print_tab_setting('',$branch_id);

        $prescription_medicine_tab_setting = get_dialysis_prescription_medicine_print_tab_setting($branch_id);
      //echo $data['all_detail']['prescription_list'][0]->attended_doctor; die;
        
        $attended_doctor = $all_detail['prescription_list'][0]->doctor_id;
        
        if(!empty($attended_doctor))
        {
          //$signature_image = $this->prescription->get_doctor_signature($attended_doctor); 
          
          $signature_image = get_attended_doctor_signature($attended_doctor);
          
          $signature_image_new = get_doctor_signature($attended_doctor);
          
        }
        

        $signature_reprt_data ='';
        if(!empty($signature_image))
        {
            
            
            


        $signature_reprt_data .='<table border="0" cellpadding="3px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:11px;margin-top: 5%;"> 
        ';
        
          if(!empty($signature_image_new->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image_new->sign_img))
        {

        $signature_reprt_data .='<tr>
        <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;"><img width="150px"  src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image_new->sign_img.'"></div></td>
        </tr>';

        }
         $signature_reprt_data.='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;">Dr.'.$signature_image->doctor_name.'</div></td>
      </tr>';
      
       $signature_reprt_data.='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;">'.$signature_image_new->signature.'</div></td>
      </tr>';
        
        

        
        $signature_reprt_data .='<tr>
        <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
        </tr>';
        if(!empty($signature_image->doc_reg_no))
        {
           $signature_reprt_data .='<tr>
        <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->doc_reg_no).'</b></td>
        </tr>'; 
        }
        
        
        

        $signature_reprt_data .='</table>';

        }

        

        $signature = $signature_reprt_data;
        //$data['file_name'] = $file_name;
        
       // $vitals_list=$this->opd_api_model->vitals_list();
        
        $this->load->model('general/general_model');
        $vitals_list=$this->general_model->vitals_list();
        
         $simulation =  $all_detail['prescription_list']['simulation_name'];
         $header_replace_part = str_replace("{patient_name}",$simulation.'  '.$all_detail['prescription_list'][0]->patient_name,$header_replace_part);
    
    $address = $all_detail['prescription_list'][0]->address;
    $pincode = $all_detail['prescription_list'][0]->pincode;
    $booking_date_time='';
    if(!empty($all_detail['prescription_list'][0]->dialysis_date) && $all_detail['prescription_list'][0]->dialysis_date!='0000-00-00')
{
    $booking_date_time = date('d-m-Y',strtotime($all_detail['prescription_list'][0]->dialysis_date)); 
}
       //.' '.$all_detail['prescription_list'][0]->booking_time   
    
    $patient_address = $address.' - '.$pincode;

    $header_replace_part = str_replace("{patient_address}",$patient_address,$header_replace_part);

    $header_replace_part = str_replace("{patient_reg_no}",$all_detail['prescription_list'][0]->patient_code,$header_replace_part);

    $header_replace_part = str_replace("{mobile_no}",$all_detail['prescription_list'][0]->mobile_no,$header_replace_part);
    
    $header_replace_part = str_replace("{booking_code}",$all_detail['prescription_list'][0]->booking_code,$header_replace_part);
    $header_replace_part = str_replace("{booking_date}",$booking_date_time,$header_replace_part);
    
     if(!empty($all_detail['prescription_list'][0]->mlc_no))
    {
        $header_replace_part = str_replace("{mlc_no}",$mlc_no,$header_replace_part);   
    }
    else{
        $header_replace_part = str_replace("{mlc_no}",'',$header_replace_part);  
    }

    
    $booking_time ='';
    if(!empty($all_detail['prescription_list'][0]->dialysis_time) && $all_detail['prescription_list'][0]->dialysis_time!='00:00:00' && strtotime($all_detail['prescription_list'][0]->dialysis_time)>0)
    {
        $booking_time = date('h:i A', strtotime($all_detail['prescription_list'][0]->dialysis_time));    
    }
    
    $header_replace_part = str_replace("{booking_time}",$booking_time,$header_replace_part);
    

    $header_replace_part = str_replace("{doctor_name}",'Dr. '.$all_detail['prescription_list']['attended_doctor'],$header_replace_part);

    $header_replace_part = str_replace("{ref_doctor_name}",$all_detail['prescription_list']['referral_doctor'],$header_replace_part);

    $header_replace_part = str_replace("{specialization}",$all_detail['prescription_list']['specialization_name'],$header_replace_part);

    if(!empty($all_detail['prescription_list'][0]->relation))
        {
        $rel_simulation = $all_detail['prescription_list']['simulation_name'];
        $header_replace_part = str_replace("{parent_relation_type}",$all_detail['prescription_list'][0]->relation,$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_type}",'',$header_replace_part);
        }

    if(!empty($all_detail['prescription_list'][0]->relation_name))
        {
        $rel_simulation = $all_detail['prescription_list']['simulation_name'];
        $header_replace_part = str_replace("{parent_relation_name}",$rel_simulation.'  '.$all_detail['prescription_list'][0]->relation_name,$header_replace_part);
        }
        else
        {
         $header_replace_part = str_replace("{parent_relation_name}",'',$header_replace_part);
        }
    ////////

//echo $header_replace_part; die;

    /*if(!empty($vitals_list))
    {
        foreach ($vitals_list as $vitals) 
        {
            $vitals_val = get_vitals_value($vitals->id,$prescription_id,$type);
            $page_middle = str_replace("{".$vitals->short_code."}",$vitals_val,$page_middle);
        }
    } */   
    //echo $page_middle;
    
    $opd_id = $all_detail['prescription_list'][0]->id;
        
    $patient_vital = '';

  

        if(!empty($vitals_list))
        {
              $patient_vital .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
    <tbody>
        <tr>';
          $i=0;
          foreach ($vitals_list as $vitals) 
          {
            $vital_val = get_vitals_value($vitals->id,$opd_id,2);
            //echo $this->db->last_query(); exit;

            $patient_vital .= '<td align="left" valign="top" width="20%">
            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                <tbody>
                    <tr>
                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals->vitals_name.'</b></th>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                        <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.$vital_val.'</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </td>';
            $i++;
            
          }
          
            $patient_vital .= '</tr>
    </tbody>
</table>';

        }

            
     
 $page_middle = str_replace("{vitals}",$patient_vital,$page_middle);
  //  echo $page_middle; die;

        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['prescription_list'][0]->gender];
        $age_y = $all_detail['prescription_list'][0]->age_y; 
        $age_m = $all_detail['prescription_list'][0]->age_m;
        $age_d = $all_detail['prescription_list'][0]->age_d;

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
        $patient_age =  $age;
        $gender_age = $gender.'/'.$patient_age;

    $header_replace_part = str_replace("{patient_age}",$gender_age,$header_replace_part);
    $pos_start = strpos($page_middle, '{start_loop}');
    $pos_end = strpos($page_middle, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($page_middle,$pos_start+12,$row_last_length-12);

    $patient_test_name="";
    $patient_pres_datas = '';
    $prv_history ='';
    $personal_history ='';
    $chief_complaints ='';
    $examination ='';
    $diagnosis ='';
    $suggestion ='';
    $remark ='';
    $next_app = "";
    //
 foreach ($prescription_tab_setting as $value) 
 {   
    if(strcmp(strtolower($value->setting_name),'test_result')=='0')
    {  
        if(!empty($all_detail['prescription_list']['prescription_test_list']))
        {
            if(!empty($value->setting_value)) { $Test_names =  $value->setting_value; } else { $Test_names =  $value->var_title; }
            $patient_test_name .= '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;text-decoration:underline;">'.$Test_names.':</div>';
                
              $patient_test_name .= '<table border="1" width="100%" style="border-collapse:collapse;font:13px Arial;margin-top:1%;float:left;">';
            $patient_test_name .= '<thead><tr>
                <th>Sr. No.</th><th>Test Name</th></tr><thead><tbody>';

            $j=1;
            
            foreach($all_detail['prescription_list']['prescription_test_list'] as $prescription_testname)
            { 
                 
            $patient_test_name .= '<tr><td style="text-align:center">'.$j.'</td>
        <td style="text-align:center">'.$prescription_testname->test_name.'</td>
    </tr>';
             $j++;              
            }
            
            $patient_test_name .= '</tbody></table>'; 
            
            }
    
    }

    if(strcmp(strtolower($value->setting_name),'prescription')=='0')
    {

            if(!empty($all_detail['prescription_list']['prescription_presc_list']))
            {
              
              if(!empty($value->setting_value)) { $pre_names =  $value->setting_value; } else { $pre_names =  $value->var_title; }
            $patient_pres_datas .= '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;text-decoration:underline;float:left;">'.$pre_names.':</div>';  

            $i=1;
            
            $patient_pres_datas .= '<table border="1" width="100%" style="border-collapse:collapse;margin-top:5px;float:left;">';
            $patient_pres_datas .= '<thead><tr><th>Sr No</th>';
                //print_r($prescription_medicine_tab_setting);die();
                foreach ($prescription_medicine_tab_setting as $tab_value) 
                    { 

                    if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                    { 
                    if(!empty($tab_value->setting_value))
                     { 
                        $medicine_name =  $tab_value->setting_value; } else { $medicine_name =  $tab_value->var_title;
                         }   
                       $patient_pres_datas .= '<th>'.$medicine_name.'</th>';
                    }

                    if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                    { 
                    if(!empty($tab_value->setting_value)) { $medicine_salt =  $tab_value->setting_value; } else { $medicine_salt =  $tab_value->var_title; }   
                    $patient_pres_datas .= '<th>'.$medicine_salt.'</th>';

                    }

                    if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                    { 
                    if(!empty($tab_value->setting_value)) { $medicine_brand =  $tab_value->setting_value; } else { $medicine_brand =  $tab_value->var_title; }   
                    $patient_pres_datas .= '<th>'.$medicine_brand.'</th>';

                    }

                    if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                    { 
                    if(!empty($tab_value->setting_value)) { $types =  $tab_value->setting_value; } else { $types =  $tab_value->var_title; }   
                    $patient_pres_datas .= '<th>'.$types.'</th>';

                    }

                    

                    if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                    { 
                    if(!empty($tab_value->setting_value)) { $dose =  $tab_value->setting_value; } else { $dose = $tab_value->var_title; }   
                    $patient_pres_datas .= '<th>'.$dose.'</th>';

                    }

                    if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                    { 
                    if(!empty($tab_value->setting_value)) { $duration =  $tab_value->setting_value; } else { $duration =  $tab_value->var_title; }   
                    $patient_pres_datas .= '<th>'.$duration.'</th>';

                    }

                    if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                    { 
                    if(!empty($tab_value->setting_value)) { $frequency =  $tab_value->setting_value; } else { $frequency =  $tab_value->var_title; }   
                    $patient_pres_datas .= '<th>'.$frequency.'</th>';

                    }

                    if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                    { 
                    if(!empty($tab_value->setting_value)) { $advice =  $tab_value->setting_value; } else { $advice =  $tab_value->var_title; }   
                    $patient_pres_datas .= '<th>'.$advice.'</th>';

                    }

            }    
             $patient_pres_datas .= '</tr></thead><tbody>';
              
          
                
            foreach($all_detail['prescription_list']['prescription_presc_list'] as $prescription_presc)
            { 
                          
                  $patient_pres_datas .= '<tr>
                <td>'.$i.'</td>';




                foreach ($prescription_medicine_tab_setting as $tab_value) 
                { 
                if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                { 
                   
                $patient_pres_datas .= '<td>'.$prescription_presc->medicine_name.'</td>';

                }

                if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                { 
                   
                $patient_pres_datas .= '<td>'.$prescription_presc->medicine_salt.'</td>';

                }

                if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                { 
                   
                $patient_pres_datas .= '<td>'.$prescription_presc->medicine_brand.'</td>';

                }

                if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                { 
                  
                $patient_pres_datas .= '<td>'.$prescription_presc->medicine_type.'</td>';

                }

                

                if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                { 
                   
                $patient_pres_datas .= '<td>'.$prescription_presc->medicine_dose.'</td>';

                }

                if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                { 
                 
                $patient_pres_datas .= '<td>'.$prescription_presc->medicine_duration.'</td>';

                }

                if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                { 
                 
                $patient_pres_datas .= '<td>'.$prescription_presc->medicine_frequency.'</td>';

                }

                if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                { 
                   
                $patient_pres_datas .= '<td>'.$prescription_presc->medicine_advice.'</td>';

                }

            }

                $patient_pres_datas .= '</tr>';
                 
                 $i++;
                            
            }  

             
             
           $patient_pres_datas .= '</tbody></table>';

            }
      $otherdetals=$patient_pres_datas;
    } 
  


    if(strcmp(strtolower($value->setting_name),'previous_history')=='0')
    {

        if(!empty($value->setting_value)) { $Prv_history =  $value->setting_value; } else { $Prv_history =  $value->var_title; }

            if(!empty($all_detail['prescription_list'][0]->prv_history)){ 
            $prv_history = '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:12px;"><b>'.$Prv_history.' :</b>
               '.nl2br($all_detail['prescription_list'][0]->prv_history).'
              </div>
            </div>';
            }
    }

    


    if(strcmp(strtolower($value->setting_name),'personal_history')=='0')
    {
        if(!empty($value->setting_value)) { $Personal_history =  $value->setting_value; } else { $Personal_history =  $value->var_title; }
        if(!empty($all_detail['prescription_list'][0]->personal_history)){ 
        $personal_history = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:12px;">
            <b>'.$Personal_history.' :</b>
           '.nl2br($all_detail['prescription_list'][0]->personal_history).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0')
    {   
        if(!empty($value->setting_value)) { $Chief_complaints =  $value->setting_value; } else { $Chief_complaints =  $value->var_title; }
        if(!empty($all_detail['prescription_list'][0]->chief_complaints)){ 
        $chief_complaints = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:12px;"><b>'.$Chief_complaints.':</b>
           '.nl2br($all_detail['prescription_list'][0]->chief_complaints).'
          </div>
        </div>';
        }
    }

    

    if(strcmp(strtolower($value->setting_name),'examination')=='0')
    {
        if(!empty($value->setting_value)) { $Examination =  $value->setting_value; } else { $Examination =  $value->var_title; }
        if(!empty($all_detail['prescription_list'][0]->examination)){ 
        $examination = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:12px;">
            <b>'.$Examination.' :</b>
           '.nl2br($all_detail['prescription_list'][0]->examination).'
          </div>
        </div>';
        }
    }
        
    


    if(strcmp(strtolower($value->setting_name),'diagnosis')=='0')
    { 
        if(!empty($value->setting_value)) { $Diagnosis =  $value->setting_value; } else { $Diagnosis =  $value->var_title; }
        if(!empty($all_detail['prescription_list'][0]->diagnosis)){ 
        $diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:12px;">
            <b>'.$Diagnosis.' :</b>
           '.nl2br($all_detail['prescription_list'][0]->diagnosis).'
          </div>
        </div>';
        }
    }
        



    if(strcmp(strtolower($value->setting_name),'suggestions')=='0')
    {
        if(!empty($value->setting_value)) { $Suggestion =  $value->setting_value; } else { $Suggestion =  $value->var_title; }
        if(!empty($all_detail['prescription_list'][0]->suggestion)){ 
        $suggestion = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:12px;">
            <b> '.$Suggestion.' :</b>'.nl2br($all_detail['prescription_list'][0]->suggestion).'
          
          </div>
        </div>';
        }
    }    
    


    if(strcmp(strtolower($value->setting_name),'remarks')=='0')
    { 
        if(!empty($value->setting_value)) { $Remark =  $value->setting_value; } else { $Remark =  $value->var_title; }
        if(!empty($all_detail['prescription_list'][0]->remark)){ 
        $remark = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:12px;">
            <b>'.$Remark.' :</b>
           '.nl2br($all_detail['prescription_list'][0]->remark).'
          </div>
        </div>';
        }
    }
    
    
     if(strcmp(strtolower($value->setting_name),'appointment')=='0' && !empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970' )
{

if(!empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970'){ 
  
  
            
            if(!empty($appointment_date->setting_value)) 
       { 
            $Appointment_date =  $value->setting_value; } else { $Appointment_date =  $value->var_title;
        }
    $next_app .= $Appointment_date.' '.date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date));
  
     }   }
    
  
    }

    $page_middle = str_replace("{patient_test_name}",$patient_test_name,$page_middle);
  
    $page_middle = str_replace("{patient_pres}",$otherdetals,$page_middle);
    $page_middle = str_replace("{prv_history}",$prv_history,$page_middle);
    $page_middle = str_replace("{personal_history}",$personal_history,$page_middle);
    $page_middle = str_replace("{chief_complaints}",$chief_complaints,$page_middle);
    $page_middle = str_replace("{examination}",$examination,$page_middle);
    $page_middle = str_replace("{diagnosis}",$diagnosis,$page_middle);
    $page_middle = str_replace("{suggestion}",$suggestion,$page_middle);
    $page_middle = str_replace("{remark}",$remark,$page_middle);
    $page_middle = str_replace("{appointment_date}",$next_app,$page_middle);
    $page_middle = str_replace("{signature}",$signature,$page_middle);
//echo $header_replace_part; die;
            $this->load->library('m_pdf');
            $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
            $this->m_pdf->pdf->SetHeader($page_header);
             $this->m_pdf->pdf->WriteHTML($header_replace_part.'<hr>'.$page_middle,2);
            $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
            $this->m_pdf->pdf->SetFooter($page_footer); 
            $file_name = $all_detail['prescription_list'][0]->patient_name.'prescription.pdf';

            $url = ROOT_UPLOADS_PATH.'temp/'.$file_name; 

            $pdfFilePath = DIR_UPLOAD_PATH.'/temp/'.$file_name; 
            $this->m_pdf->pdf->Output($pdfFilePath, "I");
           
        



        }
        
    }


    


}
?>