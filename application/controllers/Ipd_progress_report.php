<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_progress_report extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('ipd_progress_report/ipd_progress_report_model','ipd_progress_report');
		$this->load->library('form_validation');
    }

    
	public function index()
	{
	  unauthorise_permission(123,742);
	  $data['page_title'] = 'Progress Note';
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
	  $data['form_data'] = array('patient_name'=>'','mobile_no'=>'','patient_code'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date);  
	  $this->load->view('ipd_progress_report/list',$data);
	}

    public function ajax_list()
    {   

        unauthorise_permission(123,742);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->ipd_progress_report->get_datatables();  
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
            $row[] = $prescription->ipd_no;
            $row[] = $prescription->patient_code;    
            $row[] = $prescription->patient_name;
            $row[] = $prescription->mobile_no;
            
            $row[] = $status; 
            $row[] = date('d-M-Y',strtotime($prescription->report_date));  
            $row[] = date('d-M-Y',strtotime($prescription->created_date));  

            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_delete = "";
            $btn_view_pre ="";
            $btn_print_pre="";
            $btn_upload_pre="";
            $btn_view_upload_pre="";
            
            if($users_data['parent_id']==$prescription->branch_id)
            {
	            if(in_array('744',$users_data['permission']['action'])) 
	            {
	                $btn_edit = ' <a class="btn-custom" href="'.base_url("ipd_progress_report/edit/".$prescription->id).'" title="Edit Progress Note"><i class="fa fa-pencil"></i> Edit</a>';
	            }

	            if(in_array('745',$users_data['permission']['action'])) 
	            {
	               $btn_delete = ' <a class="btn-custom" onClick="return delete_progress_report('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
	            } 
	        }
            if(in_array('742',$users_data['permission']['action'])) 
            {
               $btn_view_pre = ' <a class="btn-custom"  href="'.base_url('/ipd_progress_report/view_progress_report/'.$prescription->id.'/'.$prescription->branch_id).'" title="View Progress Note" target="_blank" data-url="512"><i class="fa fa-info-circle"></i> View</a>';
            }

             $print_url = "'".base_url('ipd_progress_report/print_ipd_progress_report/'.$prescription->id)."'";
              $btn_print_pre = ' <a class="btn-custom" onClick="return print_window_page('.$print_url.')" href="javascript:void(0)" title="Print Prescription"  data-url="512"><i class="fa fa-print"></i> Print</a>'; 
            
          
          
            // End Action Button //

            $row[] =  $btn_view_pre.$btn_edit.$btn_delete.$btn_print_pre;                
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report->count_filtered(),
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
                                    );
        if(isset($post) && !empty($post))
        {
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('ipd_progress_report_search', $marge_post);

        }
        $prescription_search = $this->session->userdata('ipd_progress_report_search');
        if(isset($prescription_search) && !empty($prescription_search))
        {
            $data['form_data'] = $prescription_search;
        }
        $this->load->view('ipd_progress_report/advance_search',$data);
    }
    
    public function reset_search()
    {
        $this->session->unset_userdata('ipd_progress_report_search');
    }

   	public function delete($id="")
    {
       unauthorise_permission(123,745);
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report->delete($id);
           $response = "Ipd progress note successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(123,745);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report->deleteall($post['row_id']);
            $response = "Ipd progress note successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     unauthorise_permission(123,742);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_progress_report->get_by_id($id);  
        $data['page_title'] = $data['form_data']['patient_name']." detail";
        $this->load->view('ipd_progress_report/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(123,746);
        $data['page_title'] = 'Ipd progress note Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_progress_report/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(123,746);
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('ipd_progress_report/ipd_progress_report_archive_model','ipd_progress_report_archive'); 

        $list = $this->ipd_progress_report_archive->get_datatables();   
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
            $row[] = $prescription->ipd_no;
            $row[] = $prescription->patient_code;    
            $row[] = $prescription->patient_name;
            $row[] = $prescription->mobile_no;
            
            $row[] = $status; 
            $row[] = date('d-M-Y',strtotime($prescription->created_date)); 
            
            //Action button /////
            $btn_restore = ""; 
            $btn_view = "";
            $btn_delete = "";

            if(in_array('748',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_ipd_progress_report('.$prescription->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            
            if(in_array('540',$users_data['permission']['action'])) 
            {
                //$btn_view = ' <a class="btn-custom" onclick="return view_ipd_progress_report('.$prescription->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
            } 
            
            if(in_array('747',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$prescription->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
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
                        "recordsTotal" => $this->ipd_progress_report_archive->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission(123,748);
        $this->load->model('ipd_progress_report/ipd_progress_report_archive_model','ipd_progress_report_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_archive->restore($id);
           $response = "IPD progress report successfully restore in type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
         unauthorise_permission(123,748);
        $this->load->model('ipd_progress_report/ipd_progress_report_archive_model','ipd_progress_report_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_archive->restoreall($post['row_id']);
            $response = "IPD progress report successfully restore in list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         unauthorise_permission(123,747);
        $this->load->model('ipd_progress_report/ipd_progress_report_archive_model','ipd_progress_report_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_archive->trash($id);
           $response = "IPD progress report successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(123,747);
        $this->load->model('ipd_progress_report/ipd_progress_report_archive_model','ipd_progress_report_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_archive->trashall($post['row_id']);
            $response = "IPD progress report successfully deleted parmanently.";
            echo $response;
        }
    }
 	public function print_ipd_progress_report($progress_id="",$branch_id='')
    {
        
        $progress_report_id= $this->session->userdata('progress_report_id');
        if(!empty($progress_report_id))
        {
            $progress_report_id = $progress_report_id;
        }
        else
        {
            $progress_report_id =$progress_id;
        }


        $data['page_title'] = "Print progress report";
        $opd_prescription_info = $this->ipd_progress_report->get_detail_by_progress_id($progress_report_id);
        //echo "<pre>";print_r($opd_prescription_info); exit;

        $users_data = $this->session->userdata('auth_users'); 
        if($users_data['users_role']==3)
        {
            $doctor_id = $users_data['parent_id'];
            $this->load->model('branch/branch_model');
            $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
            $branch_id = $doctor_data[0]->branch_id; 
        }

        $template_format = $this->ipd_progress_report->template_format(array('setting_name'=>'PROGRESS_REPORT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
        $template_format_left = $this->ipd_progress_report->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->ipd_progress_report->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->ipd_progress_report->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->ipd_progress_report->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;
        //echo "<pre>"; print_r($opd_prescription_info);
        $data['progress_report_tab_setting'] = get_progress_report_tab_setting('',$branch_id);
        $signature_image = get_doctor_signature($data['all_detail'][0]->attend_doctor_id);
        
		   $signature_reprt_data ='';
		   if(!empty($signature_image))
		   {
		   
		     $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
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
		      
		    </table>';

		   }
   


        $data['signature'] = $signature_reprt_data;
        $this->load->view('ipd_progress_report/progress_report_template',$data);
    }


    public function print_blank_prescriptions($booking_id="",$branch_id='')
    {
        $data['page_title'] = "Print Prescription";
        $opd_prescription_info = $this->ipd_progress_report->get_detail_by_booking_id($booking_id,$branch_id);
        $template_format = $this->ipd_progress_report->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>1),$branch_id);
        //$template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->ipd_progress_report->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>1),$branch_id);
        $template_format_right = $this->ipd_progress_report->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>1),$branch_id);
        $template_format_top = $this->ipd_progress_report->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>1));
        $template_format_bottom = $this->ipd_progress_report->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>1),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_prescription_print_tab_setting();
        //print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_prescription_medicine_print_tab_setting();
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $this->load->view('ipd_progress_report/print_progress_report_template',$data);
    }




    public function print_progress_report_pdf($id)
    {
            $opd_prescription_info = $this->ipd_progress_report->get_by_ids($id);
            $prescription_opd_prescription_info = $this->ipd_progress_report->get_ipd_progress_report($id); 
            $prescription_opd_test_info = $this->ipd_progress_report->get_opd_test($id);
            
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
         $pdf_template = $this->load->view('prescription/pdf_template',$pdf_data,true);
         $result['success'] = true;
         $result['msg'] = 'Print prescription success';
         $result['pdf_template'] = $pdf_template;
         echo @json_encode($result);
    }

    


public function print_blank_progress_report_pdf($id)
{
        $opd_prescription_info = $this->ipd_progress_report->get_by_ids($id);
        //echo "<pre>";print_r($opd_prescription_info); exit;
        $prescription_opd_prescription_info = $this->ipd_progress_report->get_opd_prescription($id); 
        $prescription_opd_test_info = $this->ipd_progress_report->get_opd_test($id);
        
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
     
     $pdf_template = $this->load->view('prescription/pdf_template',$pdf_data,true);
     $result['success'] = true;
     $result['msg'] = 'Print prescription success';
     $result['pdf_template'] = $pdf_template;
     
    
    
    echo @json_encode($result);
}

public function view_progress_report($id,$branch_id='')
{ 
    //echo $id;
    //$data['form_data'] = $this->ipd_progress_report->get_by_ids($id);
    $opd_prescription_info = $this->ipd_progress_report->get_detail_by_progress_id($id);
    //echo "<pre>"; print_r($opd_prescription_info); exit;
    $data['all_detail']= $opd_prescription_info;
    $data['progress_report_tab_setting'] = get_progress_report_tab_setting('',$branch_id);
    $signature_image = get_doctor_signature($data['all_detail'][0]->attend_doctor_id);
    
       $signature_reprt_data ='';
       if(!empty($signature_image))
       {
       
         $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
          <tr>
          <td width="80%"></td>
            <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">Signature</b></td>
          </tr>';
          
          if(file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->sign_img))
          {
          
          $signature_reprt_data .='<tr>
          <td width="80%"></td>
            <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->sign_img.'"></div></td>
          </tr>';
          
           }
           
          $signature_reprt_data .='<tr>
          <td width="80%"></td>
            <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.$signature_image->signature.'</b></td>
          </tr>
          
        </table>';

       }



    $data['signature'] = $signature_reprt_data;


    //$data['page_title'] = $data['form_data']['patient_name']." Progress Report";
    $data['page_title'] = $data['all_detail'][0]->patient_name." Progress Report";
    
    //$data['progress_report_tab_setting'] = get_progress_report_tab_setting('',$branch_id);
    $this->load->view('ipd_progress_report/print_template',$data);
    $html = $this->output->get_output();
    /*$data['form_data'] = $this->ipd_progress_report->get_by_ids($id);
    $data['page_title'] = $data['form_data']['patient_name']." Progress Report";
    $data['progress_report_tab_setting'] = get_progress_report_tab_setting('',$branch_id);
    $this->load->view('ipd_progress_report/print_template',$data);
    $html = $this->output->get_output();*/
}




	public function add($patient_id="",$ipd_id='')
	{
		unauthorise_permission(123,743);
        $data['prescription_tab_setting'] = get_progress_report_tab_setting();
        $this->load->model('general/general_model'); 
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
        if($ipd_id>0)
        {
           $this->load->model('ipd_booking/ipd_booking_model');
           $this->load->model('patient/patient_model');
           $ipd_booking_data = $this->ipd_booking_model->get_all_detail_print($ipd_id);
           $ipd_booking = $ipd_booking_data['ipd_list'];
		  	//echo "<pre>";print_r($ipd_booking);
		   if(!empty($ipd_booking_data))
           {
              $ipd_id = $ipd_booking[0]->id;
              $ipd_no = $ipd_booking[0]->ipd_no;
              $doctor_name = $ipd_booking[0]->doctor_name;
              $patient_id = $ipd_booking[0]->patient_id;
              $simulation_id = $ipd_booking[0]->simulation_id;
              $patient_code = $ipd_booking[0]->patient_code;
              $patient_name = $ipd_booking[0]->patient_name;
              $mobile_no = $ipd_booking[0]->mobile_no;
              $gender = $ipd_booking[0]->gender;
              $attend_doctor_id = $ipd_booking[0]->attend_doctor_id;
              $age_y = $ipd_booking[0]->age_y;
              $age_m = $ipd_booking[0]->age_m;
              $age_d = $ipd_booking[0]->age_d;
              $address = $ipd_booking[0]->address;
              $city_id = $ipd_booking[0]->city_id;
              $state_id = $ipd_booking[0]->state_id;
              $country_id = $ipd_booking[0]->country_id;
              $room_category = $ipd_booking[0]->room_category;
              $bad_no = $ipd_booking[0]->bad_no;
              $room_no = $ipd_booking[0]->room_no;
              $room_type_id = $ipd_booking[0]->room_type_id;
              $room_id = $ipd_booking[0]->room_id;
              $bad_id = $ipd_booking[0]->bad_id; 
              
             
          }


        }
        
        $data['suggetion_list'] = $this->ipd_progress_report->suggetion_list();  
        $data['prescription_list'] = $this->ipd_progress_report->prescription_list();  
        $data['dressing_list'] = $this->ipd_progress_report->dressing_list();
        $data['remarks_list'] = $this->ipd_progress_report->remarks_list();  
        
        $data['template_list'] = $this->ipd_progress_report->template_list();    
        
        $data['page_title'] = "Add Progress Note";
                
        $post = $this->input->post();
       
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'ipd_id'=>$ipd_id,
                                  'bad_id'=>$bad_id,
                                  'doctor_name'=>$doctor_name,
                                  'patient_code'=>$patient_code,
                                  'ipd_no'=>$ipd_no,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'address'=>$address,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'attend_doctor_id'=>$attend_doctor_id,
                                  'room_category'=>$room_category,
                                  'bed_no'=>$bad_no,
                                  'room_no'=>$room_no,
                                  'room_type_id'=>$room_type_id,
                                  'room_id'=>$room_id,
                                  'bad_id'=>$bad_id,
                                  'prescription'=>"",
                                  'suggestion'=>"",
                                  'dressing'=>'',
                                  'remarks'=>'',
                                  'patient_bp'=>'',
                                  'patient_temp'=>'',
                                  'patient_weight'=>'',
                                  'patient_spo2'=>'',
                                  'patient_height'=>'',
                                  'patient_rbs'=>'',
                                  'report_date'=>date('d-m-Y'),
                                  'report_time'=>date('H:i'),
                                  
                                  );
        if(isset($post) && !empty($post))
        {   
          $progress_report_id = $this->ipd_progress_report->save();
          $this->session->set_userdata('progress_report_id',$progress_report_id);
          $this->session->set_flashdata('success','IPD progress note successfully added.');
          redirect(base_url('ipd_progress_report/?status=print'));
		} 	

         $this->load->view('ipd_progress_report/add',$data);
    }
   
    public function edit($progress_report_id="")
    { 
       unauthorise_permission(123,744);
      if(isset($progress_report_id) && !empty($progress_report_id) && is_numeric($progress_report_id))
      {      
        $this->load->model('general/general_model');
        $this->load->model('opd/opd_model','opd'); 
        $post = $this->input->post();
        $get_by_id_data = $this->ipd_progress_report->get_by_progress_report_id($progress_report_id); 
        
        $prescription_data = $get_by_id_data['progress_report_list'][0];
        //echo "<pre>";print_r($prescription_data); exit;
        //echo $prescription_data->bad_id;
        $data['suggetion_list'] = $this->ipd_progress_report->suggetion_list();  
        $data['prescription_list'] = $this->ipd_progress_report->prescription_list();  
        $data['dressing_list'] = $this->ipd_progress_report->dressing_list();
        $data['remarks_list'] = $this->ipd_progress_report->remarks_list();  
        
        $data['template_list'] = $this->ipd_progress_report->template_list();
        $data['page_title'] = "Update Progress Note";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
      
        
       $data['prescription_tab_setting'] = get_progress_report_tab_setting();
        $data['form_data'] = array(
                              'data_id'=>$progress_report_id, 
                              'patient_id'=>$prescription_data->patient_id,
                              'ipd_id'=>$prescription_data->ipd_id,
                              'attend_doctor_id'=>$prescription_data->attend_doctor_id,
                              'report_date'=>date('d-m-Y',strtotime($prescription_data->report_date)),
                              'report_time'=>$prescription_data->report_time,
                              'patient_code'=>$prescription_data->patient_code,
                              'ipd_no'=>$prescription_data->ipd_no,
                              'simulation_id'=>$prescription_data->simulation_id,
                              'patient_name'=>$prescription_data->patient_name,
                              'mobile_no'=>$prescription_data->mobile_no,
                              'gender'=>$prescription_data->gender,
                              'age_y'=>$prescription_data->age_y,
                              'age_m'=>$prescription_data->age_m,
                              'age_d'=>$prescription_data->age_d,
                              'address'=>$prescription_data->address,
                              'city_id'=>$prescription_data->city_id,
                              'state_id'=>$prescription_data->state_id,
                              'country_id'=>$prescription_data->country_id,
                              'patient_bp'=>$prescription_data->patient_bp,
                              'patient_temp'=>$prescription_data->patient_temp,
                              'patient_weight'=>$prescription_data->patient_weight,
                              'patient_spo2'=>$prescription_data->patient_spo2,
                              'patient_height'=>$prescription_data->patient_height,
                              'patient_rbs'=>$prescription_data->patient_rbs,
                              'prescription'=>$prescription_data->prescription,
                              'dressing'=>$prescription_data->dressing,
                              'suggestion'=>$prescription_data->suggestion,
                              'remarks'=>$prescription_data->remarks,
                              'room_category'=>$prescription_data->room_category,
                              'room_type_id'=>$prescription_data->room_type_id,
                              'bed_no'=>$prescription_data->bad_no,
                              'bad_id'=>$prescription_data->bed_id,
                              'room_no'=>$prescription_data->room_no,
                              'room_id'=>$prescription_data->room_id,
                              'bad_no'=>$prescription_data->bad_no,
                              //'bed_id'=>$prescription_data->bed_id,


                              );

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $progress_report_id = $this->ipd_progress_report->save();
                $this->session->set_userdata('progress_report_id',$progress_report_id);
                $this->session->set_flashdata('success','Progress note updated successfully.');
                redirect(base_url('ipd_progress_report'));
			}
            else
            {
                $data['form_error'] = validation_errors();  
            } 

            

        }

       $this->load->view('ipd_progress_report/add',$data);       
      }
    }

    function prescription_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->ipd_progress_report->prescription_name($pre_id);
         echo $pre_name;
      }
    }

    function dressing_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->ipd_progress_report->dressing_name($pre_id);
         echo $pre_name;
      }
    }
    function suggetion_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->ipd_progress_report->suggetion_name($pre_id);
         echo $pre_name;
      }
    }

    function remarks_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->ipd_progress_report->remarks_name($pre_id);
         echo $pre_name;
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
                                        'patient_bp'=>$post['patient_bp'],
                                        'patient_temp'=>$post['patient_temp'],
                                        'patient_weight'=>$post['patient_weight'],
                                        'patient_height'=>$post['patient_height'],
                                        'patient_sop'=>$post['patient_sop'],
                                        'patient_rbs'=>$post['patient_rbs'],
                                        'diseases'=>$post['diseases'],
                                        
                                        
                                       ); 
            return $data['form_data'];
        }   
    }


    function get_test_vals($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->ipd_progress_report->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    


}
?>