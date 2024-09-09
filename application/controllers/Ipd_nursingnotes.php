<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_nursingnotes extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('ipd_nursingnotes/prescription_model','prescription');
        $this->load->model('ipd_nursingnotes/prescription_file_model','prescription_file');
		$this->load->library('form_validation');
  }

    
  public function index()
  {
      unauthorise_permission(121,733);
      $data['page_title'] = 'Nursing Care Plan Report List';
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
      $this->load->view('ipd_nursingnotes/list',$data);
  }

    public function ajax_list()
    {   

        unauthorise_permission(121,733);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->prescription->get_datatables();  
        //echo "<pre>"; print_r($list); exit;
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
                   
                $btn_edit = ' <a class="btn-custom" href="'.base_url("ipd_nursingnotes/edit/".$prescription->id).'" title="Edit Nursing Care Plan Report"><i class="fa fa-pencil"></i> Edit Nursing Care Plan Report</a>';
                    
                $btn_delete = ' <a class="btn-custom" onClick="return delete_prescription('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
            }

            
               $btn_view_pre='';
                /*$btn_view_pre = ' <a class="btn-custom"  href="'.base_url('/ipd_nursingnotes/view_prescription/'.$prescription->id.'/'.$prescription->branch_id).'" title="View Progress Report" target="_blank" data-url="512"><i class="fa fa-info-circle"></i> View Progress Report</a>';*/
                
                $print_url = "'".base_url('ipd_nursingnotes/print_prescriptions/'.$prescription->id.'/'.$prescription->branch_id)."'";
                $btn_print_pre = ' <a class="btn-custom" onClick="return print_window_page('.$print_url.')" href="javascript:void(0)" title="Print Nursing Care Plan Report"  data-url="512"><i class="fa fa-print"></i> Print Nursing Care Plan Report</a>';
                 
            
          
        if($users_data['parent_id']==$prescription->branch_id)
        {

            $btn_upload_pre = '<a class="btn-custom" onclick="return upload_prescription('.$prescription->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> Upload Nursing Care Plan Report </a>';
               
            $btn_view_upload_pre = '<a class="btn-custom" href="'.base_url('/ipd_nursingnotes/view_files/'.$prescription->id.'/'.$prescription->branch_id).'" title="View Nursing Care Plan Report" data-url="512"><i class="fa fa-circle"></i> View Nursing Care Plan Report Files</a>';
               
              
        }
          
            // End Action Button //

            $row[] =   $btn_view_pre.$btn_print_pre.$btn_upload_pre.$btn_view_upload_pre.$btn_edit.$btn_view.$btn_delete;                
        
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
        $this->load->view('ipd_nursingnotes/advance_search',$data);
    }
    
    public function reset_search()
    {
        $this->session->unset_userdata('prescription_search');
    }

   

	
     
    
 
    public function delete($id="")
    {
      // unauthorise_permission(86,535);
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription->delete($id);
           $response = "Progress Report successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        //unauthorise_permission(86,535);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription->deleteall($post['row_id']);
            $response = "Progress Report successfully deleted.";
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
        $this->load->view('ipd_nursingnotes/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission(86,536);
        $data['page_title'] = 'Progress Report Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_nursingnotes/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission(86,536);
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('ipd_nursingnotes/prescription_archive_model','prescription_archive'); 

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

            
            $btn_restore = ' <a onClick="return restore_prescription('.$prescription->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            
            
            $btn_delete = ' <a onClick="return trash('.$prescription->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            
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
        
    $this->load->model('ipd_nursingnotes/prescription_archive_model','prescription_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_archive->restore($id);
           $response = "Progress Report successfully restore in Progress list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        // unauthorise_permission(86,537);
        $this->load->model('ipd_nursingnotes/prescription_archive_model','prescription_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_archive->restoreall($post['row_id']);
            $response = "Progress Report successfully restore in Progress Report list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
         // unauthorise_permission(86,538);
        $this->load->model('ipd_nursingnotes/prescription_archive_model','prescription_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_archive->trash($id);
           $response = "Progress Report successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
         // unauthorise_permission(86,538);
        $this->load->model('ipd_nursingnotes/prescription_archive_model','prescription_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_archive->trashall($post['row_id']);
            $response = "Progress Report successfully deleted parmanently.";
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
        $data['prescription_data'] = $this->prescription->get_ipd_prescription($id); 
        $data['test_data'] = $this->prescription->get_ipd_test($id); 
        $data['page_title'] = $data['form_data']['patient_name']." Prescription";
        $this->load->view('ipd_nursingnotes/print_template1',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("prescription.pdf");

    }


    public function print_prescriptions($prescription_id="",$branch_id='')
    {
         
        $prescriptions_id= $this->session->userdata('ipd_nursing_prescription_id');
        if(!empty($prescriptions_id))
        {
            $prescription_id = $prescriptions_id;
        }
        else
        {
            $prescription_id =$prescription_id;
        }

        $data['type'] = 6;
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
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_ipd_nursing_prescription_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_ipd_nursing_prescription_medicine_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['all_detail']['prescription_list'][0]); exit;
        $signature_image = get_doctor_signature($data['all_detail']['prescription_list'][0]->attended_doctor);
        $signature_reprt_data ='';
        if(!empty($signature_image))
        {
        
         $signature_reprt_data .= '<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse; font-size:13px; margin-top: 5%;"> 
    <tr>
        <td width="70%"></td>
        <td valign="top" align="center"><b>Consultant Doctor</b></td>
    </tr>';

if (!empty($signature_image->signature) && file_exists(DIR_UPLOAD_PATH . 'doctor_signature/' . $signature_image->signature)) {
    $signature_reprt_data .= '<tr>
        <td width="70%"></td>
        <td valign="top" align="center"><img width="90px" src="' . ROOT_UPLOADS_PATH . 'doctor_signature/' . $signature_image->signature . '"></td>
    </tr>';
}

$signature_reprt_data .= '<tr>
        <td width="70%"></td>
        <td valign="top" align="center"><small><i>Dr. ' . nl2br($signature_image->doctor_name) . '</i></small></td>
    </tr>
    <tr>
        <td width="70%"></td>
        <td valign="top" align="center"><small><i>' . nl2br($signature_image->qualification) . '</i></small></td>
    </tr>
    <tr>
        <td width="70%"></td>
        <td valign="top" align="center"><small><i>' . nl2br($signature_image->doc_reg_no) . '</i></small></td>
    </tr>
</table>';

     
        }
   


        $data['signature'] = $signature_reprt_data;
        //$data['file_name'] = $file_name;
        $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();

        $this->load->view('ipd_nursingnotes/print_prescription_template',$data);
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
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_prescription_info;

        $data['prescription_tab_setting'] = get_ipd_prescription_print_tab_setting();
        //print_r($data['prescription_tab_setting']); exit;
        $data['prescription_medicine_tab_setting'] = get_ipd_prescription_medicine_print_tab_setting();
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $data['prescription_id'] = $booking_id;
        $data['type'] = 6;

        $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();
        $this->load->view('ipd_nursingnotes/print_prescription_template',$data);
    }




    public function print_prescription_pdf($id)
    {
            $opd_prescription_info = $this->prescription->get_by_ids($id);
            $prescription_opd_prescription_info = $this->prescription->get_ipd_prescription($id); 
            $prescription_opd_test_info = $this->prescription->get_ipd_test($id);
            
            $booking_id = $opd_prescription_info['booking_id'];

            $this->load->model('opd/opd_model','opd');
            $opd_details = $this->opd->get_by_id($booking_id);
            $referral_doctor = $opd_details['referral_doctor'];
            
            $patient_code = $opd_prescription_info['patient_code'];
            $attended_doctor = $opd_prescription_info['attended_doctor'];
            $specialization_id = $opd_details['specialization_id'];
            $ipd_no = $opd_prescription_info['ipd_no'];
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
            
          $prescription_tab_setting = get_ipd_prescription_tab_setting(); 
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
         $pdf_data['ipd_no'] = $ipd_no;
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
         $pdf_template = $this->load->view('ipd_nursingnotes/pdf_template',$pdf_data,true);
         $result['success'] = true;
         $result['msg'] = 'Print Progress Report  success';
         $result['pdf_template'] = $pdf_template;
         echo @json_encode($result);
    }

    


public function print_blank_prescription_pdf($id)
{
        $opd_prescription_info = $this->prescription->get_by_ids($id);
        //echo "<pre>";print_r($opd_prescription_info); exit;
        $prescription_opd_prescription_info = $this->prescription->get_ipd_prescription($id); 
        $prescription_opd_test_info = $this->prescription->get_ipd_test($id);
        
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
        
        $ipd_no = $opd_prescription_info['ipd_no'];
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
        $prescription_tab_setting = get_ipd_prescription_tab_setting(); 
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
     $pdf_data['ipd_no'] = $ipd_no;
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
     
     $pdf_template = $this->load->view('ipd_nursingnotes/pdf_template',$pdf_data,true);
     $result['success'] = true;
     $result['msg'] = 'Print Progress Report success';
     $result['pdf_template'] = $pdf_template;
     
    
    
    echo @json_encode($result);
}

public function view_prescription($id,$branch_id='')
{ 
    $data['form_data'] = $this->prescription->get_by_ids($id);
    $data['prescription_data'] = $this->prescription->get_ipd_prescription($id); 
    $data['test_data'] = $this->prescription->get_ipd_test($id); 
    $data['page_title'] = $data['form_data']['patient_name']." Prescription";
    $data['prescription_tab_setting'] = get_ipd_prescription_tab_setting('',$branch_id);
    $data['prescription_medicine_tab_setting'] = get_ipd_prescription_medicine_tab_setting('',$branch_id);
    $this->load->model('general/general_model');
    $data['vitals_list']=$this->general_model->vitals_list(); 
    $data['id'] = $id;
    $this->load->view('ipd_nursingnotes/print_template1',$data);
    $html = $this->output->get_output();
}

public function upload_prescription($prescription_id='')
{ 
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
        //echo DIR_UPLOAD_PATH.'ipd_nursingnotes/'; exit;
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        $this->form_validation->set_rules('prescription_id', 'prescription', 'trim|required'); 
        if(!isset($_FILES['prescription_files']) || empty($_FILES['prescription_files']['name']))
        {
        	$this->form_validation->set_rules('prescription_files', 'Progress Report file', 'trim|required');  
        }
        
        
        if($this->form_validation->run() == TRUE)
        {

             $config['upload_path']   = DIR_UPLOAD_PATH.'ipd_nursingnotes/';  
             $config['allowed_types'] = 'pdf|jpeg|jpg|png'; 
             $config['max_size']      = 1000; 
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

    //$data['uploaded_files'] = $this->prescription->get_uploaded_files($prescription_id);


    $this->load->view('ipd_nursingnotes/add_file',$data);
}


public function view_files($prescription_id)
{ 
	//$data['form_data'] = $this->prescription->get_prescription_files($prescription_id);
    
	$data['page_title'] = "Progress Report Files";
	$data['prescription_id'] = $prescription_id;
	$this->load->view('ipd_nursingnotes/view_prescription_files',$data);
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
            if(!empty($prescription->prescription_files) && file_exists(DIR_UPLOAD_PATH.'ipd_nursingnotes/'.$prescription->prescription_files))
            {
                $sign_img = ROOT_UPLOADS_PATH.'ipd_nursingnotes/'.$prescription->prescription_files;
                //$sign_img = '<img src="'.$sign_img.'" width="100px" />';
                $sign_img = '<a href="'.$sign_img.'"><img src="'.$sign_img.'" width="100px" /></a>';
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
      // unauthorise_permission(86,116);
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_file->delete($id);
           $response = "Progress Report file successfully deleted.";
           echo $response;
       }
    }

    function deleteall_prescription_file()
    {
        //unauthorise_permission(86,116);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->prescription_file->deleteall($post['row_id']);
            $response = "Progress Report file successfully deleted.";
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
        $post = $this->input->post();
        $get_by_id_data = $this->prescription->get_by_prescription_id($prescription_id); 
        //echo "<pre>";print_r($get_by_id_data); exit;
        $prescription_data = $get_by_id_data['prescription_list'][0];
        $prescription_test_list = $get_by_id_data['prescription_list']['prescription_test_list'];
        $prescription_presc_list = $get_by_id_data['prescription_list']['prescription_presc_list'];

        //echo "<pre>";print_r($prescription_data); exit;
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        //$data['employee_list'] = $this->opd->employee_list();
        //$data['profile_list'] = $this->opd->profile_list();
        //$data['dept_list'] = $this->general_model->department_list(); 
        $data['chief_complaints'] = $this->general_model->nursing_chief_complaint_list(); 
        $data['examination_list'] = $this->opd->nursing_examinations_list();
        $data['diagnosis_list'] = $this->opd->diagnosis_list();  
        $data['suggetion_list'] = $this->opd->nursing_suggetion_list();  
        $data['prv_history'] = $this->opd->nursing_prv_history_list();  
        $data['personal_history'] = $this->opd->nursing_personal_history_list();
        $data['template_list'] = $this->opd->nursing_template_list();    
        $data['prescription_tab_setting'] = get_ipd_nursing_prescription_tab_setting();
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['page_title'] = "Update Progress Report";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        
       
        
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
          
          /*$age_y = $present_age['age_y'];
          $age_m = $present_age['age_m'];
          $age_d = $present_age['age_d'];*/
          
          $age_y = $prescription_data->age_y;
          $age_m = $prescription_data->age_m;
          $age_d = $prescription_data->age_d;
          
          //$age_h = $present_age['age_h'];
          //present age of patient
        $data['form_data'] = array(
                              'data_id'=>$prescription_id, 
                              'patient_id'=>$prescription_data->patient_id,
                              'booking_id'=>$prescription_data->booking_id,
                              'attended_doctor'=>$prescription_data->attended_doctor,
                              'patient_code'=>$prescription_data->patient_code,
                              'ipd_no'=>$prescription_data->ipd_no,
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
                              
                              "relation_name"=>$prescription_data->relation_name,
                              "relation_type"=>$prescription_data->relation_type,
                              "relation_simulation_id"=>$prescription_data->relation_simulation_id,
                              "date_time_new" =>$prescription_data->date_time_new,
                              "shift" =>$prescription_data->shift
                              );

        $data['prescription_test_list'] = $prescription_test_list;
        $data['prescription_presc_list'] = $prescription_presc_list;                      
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
            
                $prescription_id = $this->prescription->save_prescription();
                $this->session->set_userdata('ipd_nursing_prescription_id',$prescription_id);
                $this->session->set_flashdata('success','Progress Report updated successfully.');
                redirect(base_url('ipd_nursingnotes/?status=print'));

                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            } 

            

        }
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['prescription_medicine_tab_setting'] = get_ipd_nursing_prescription_medicine_tab_setting(); 
        $this->load->view('ipd_nursingnotes/prescription',$data);       
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
                                        'ipd_no'=>$post['ipd_no'],
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
    
    public function check_patient_ids($prescription_ids='')
	{
        $result = $this->prescription->check_consolidate_prescription($prescription_ids);
        $pres_list =  array('patient_status'=>$result);
        $json = json_encode($pres_list,true);
        echo $json;
	}
	
	 public function print_consolidate_prescription()
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id = $users_data['parent_id'];
         $get = $this->input->get();
         //echo "<pre>"; print_r($get); die;
         $data['page_title'] = 'Admission Note List';
         if(isset($get) && !empty($get))
         {
           $all_prescription_ids = $get['pres_ids'];  
           $data['prescription_list'] = $this->prescription->get_consolidate_prescription($all_prescription_ids);
         } 
        $this->load->view('ipd_nursingnotes/consolidate_prescription',$data);
    }
    
    public function search_box_data()
    {
      $this->load->model('opd/opd_model','opd'); 
        $this->load->model('general/general_model');
        $post = $this->input->post();
        $type = $post['type'];
        $output = [];
        if($post['class'] == 'chief_complaints_data'){
          $getData = $this->general_model->nursing_chief_complaint_list("",$type); 
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->chief_complaints
            );
          }
        } else if ($post['class'] == 'prv_history_data') {
          $getData = $this->opd->nursing_prv_history_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->prv_history
            );
          }
        } else if($post['class'] == 'personal_history_data') {
          $getData = $this->opd->nursing_personal_history_list($type);
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->personal_history
            );
          }
        } else if($post['class'] == 'examination_data') {
          $getData = $this->opd->nursing_examinations_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->examination
            );
          }
        } else if($post['class'] == 'diagnosis_data') {
          $getData = $this->opd->diagnosis_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->diagnosis
            );
          }
        } else if($post['class'] == 'suggestion_data') {
          $getData = $this->opd->nursing_suggetion_list($type);  
          foreach($getData as $row)
          {
            $output[] = array(
              'id'  => $row->id,
              'name'  => $row->medicine_suggetion
            );
          }
        }
        echo json_encode($output);
    }


}
?>