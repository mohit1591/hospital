<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partograph extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('partograph/partograph_model','partograph'); 
		$this->load->library('form_validation');
    }

    
	public function index()
  {
     // unauthorise_permission(86,539);
      $data['page_title'] = 'Partograph List';
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
      $this->load->view('partograph/list',$data);
  }

    public function ajax_list()
    {   

       // unauthorise_permission(86,539);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->partograph->get_datatables();  
       // echo "<pre>";print_r($list);die();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $partograph) { 
            $no++;
            $row = array();
            if($partograph->status==1)
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
            $row[] = '<input type="checkbox" name="partograph[]" class="checklist" value="'.$partograph->id.'">'.$check_script;
            $row[] = $partograph->booking_code;
            $row[] = $partograph->patient_code;    
            $row[] = $partograph->patient_name;
            $row[] = $partograph->mobile_no;
            
            $row[] = $status; 
            //$row[] = date('d-M-Y',strtotime($partograph->created_date));  

            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_delete = "";
            $btn_view_pre ="";
            $btn_print_pre="";
         
            
            if($users_data['parent_id']==$partograph->branch_id)
            {
               
                   // if(in_array('1410',$users_data['permission']['action'])) 
                   // {
                    $btn_edit = ' <a class="btn-custom" href="'.base_url("partograph/edit/".$partograph->id).'" title="Edit partograph"><i class="fa fa-pencil"></i> Edit Partograph</a>';
                   // }
                  //  if(in_array('1411',$users_data['permission']['action'])) 
                   // {
                    $btn_delete = ' <a class="btn-custom" onClick="return delete_partograph('.$partograph->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                   // }  

                
            }


              //   if(in_array('1448',$users_data['permission']['action'])) 
                // {
                    $btn_view_pre = ' <a class="btn-custom"  href="'.base_url('partograph/view_partograph/'.$partograph->id.'/'.$users_data['parent_id']).'" title="View Partograph" target="_blank" data-url="512"><i class="fa fa-info-circle"></i> View Partograph</a>';
               //  } 
              //   if(in_array('1449',$users_data['permission']['action'])) 
               //  {
                   $print_url = "'".base_url('partograph/print_partographs/'.$partograph->id.'/'.$users_data['parent_id'])."'";
                //  $btn_print_pre = ' <a class="btn-custom" onClick="return print_window_page('.$print_url.')" href="javascript:void(0)" title="Print Partograph"  data-url="512"><i class="fa fa-print"></i> Print Partograph</a>';
               // }


            $row[] =   $btn_view_pre.$btn_print_pre.$btn_edit.$btn_view.$btn_delete;                
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->partograph->count_all(),
                        "recordsFiltered" => $this->partograph->count_filtered(),
                        "data" => $data,
                );
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
            $this->session->set_userdata('partograph_search', $marge_post);

        }
        $partograph_search = $this->session->userdata('partograph_search');
        if(isset($partograph_search) && !empty($partograph_search))
        {
            $data['form_data'] = $partograph_search;
        }
        $this->load->view('partograph/advance_search',$data);
    }
    
    public function reset_search()
    {
        $this->session->unset_userdata('partograph_search');
    }

   

	
     
    
 
    public function delete($id="")
    {
       unauthorise_permission(86,535);
       if(!empty($id) && $id>0)
       {
           $result = $this->partograph->delete($id);
           $response = "Partograph successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(86,535);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->partograph->deleteall($post['row_id']);
            $response = "Partograph successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     // unauthorise_permission(86,117);   
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->partograph->get_by_id($id);  
        $data['page_title'] = $data['form_data']['patient_name']." detail";
        $this->load->view('partograph/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(86,536);
        $data['page_title'] = 'Partograph Archive List';
        $this->load->helper('url');
        $this->load->view('partograph/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(86,536);
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('partograph/partograph_archive_model','partograph_archive'); 

        $list = $this->partograph_archive->get_datatables();  
        //echo "<pre>";print_r($list);die(); 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $partograph) { 
            $no++;
            $row = array();
            if($partograph->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($partograph->state))
            {
                $state = " ( ".ucfirst(strtolower($partograph->state))." )";
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
            $row[] = '<input type="checkbox" name="partograph[]" class="checklist" value="'.$partograph->id.'">'.$check_script;
            $row[] = $partograph->booking_code;
            $row[] = $partograph->patient_code;    
            $row[] = $partograph->patient_name;
            $row[] = $partograph->mobile_no;
            
            $row[] = $status; 
            $row[] = date('d-M-Y',strtotime($partograph->created_date));  
            
            //Action button /////
            $btn_restore = ""; 
            $btn_view = "";
            $btn_delete = "";

            if(in_array('537',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_partograph('.$partograph->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            
            if(in_array('538',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$partograph->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
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
                        "recordsTotal" => $this->partograph_archive->count_all(),
                        "recordsFiltered" => $this->partograph_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission(86,537);
        $this->load->model('partograph/partograph_archive_model','partograph_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->partograph_archive->restore($id);
           $response = "partograph successfully restore in partograph type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
         unauthorise_permission(86,537);
        $this->load->model('partograph/partograph_archive_model','partograph_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->partograph_archive->restoreall($post['row_id']);
            $response = "partograph successfully restore in partograph list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
          unauthorise_permission(86,538);
        $this->load->model('partograph/partograph_archive_model','partograph_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->partograph_archive->trash($id);
           $response = "partograph successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
          unauthorise_permission(86,538);
        $this->load->model('partograph/partograph_archive_model','partograph_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->partograph_archive->trashall($post['row_id']);
            $response = "partograph successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function partograph_dropdown()
  {
      $patient_list = $this->partograph->employee_type_list();
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


    public function print_partograph($id)
    { 
        
        $data['form_data'] = $this->partograph->get_by_ids($id);
        $data['partograph_data'] = $this->partograph->get_opd_partograph($id); 
        $data['test_data'] = $this->partograph->get_opd_test($id); 
        $data['page_title'] = $data['form_data']['patient_name']." partograph";
        $this->load->view('partograph/print_template1',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("partograph.pdf");

    }


    public function print_partographs($id="",$branch_id='')
    {

        $partograph_id= $this->session->userdata('opd_parto_id');
        $this->load->helper('ganeral_helper');
        if(!empty($partograph_id))
        {
            $id = $partograph_id;
        }
        else
        {
            $id =$id;
        }
        $users_data = $this->session->userdata('auth_users');
        $branch_id=
        $branch_id=$users_data['parent_id'];   
   
        redirect('partograph/view_partograph/'.$id.'/'.$users_data['parent_id']);

       /* $data['type'] = 2;
        $data['parto_id'] = $parto_id;
        $data['page_title'] = "Print Partograph";
        $opd_partograph_info = $this->partograph->get_detail_by_parto_id($parto_id);

        $template_format = $this->partograph->template_format(array('setting_name'=>'PARTOGRAPH_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
       
        $template_format_left = $this->partograph->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->partograph->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->partograph->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->partograph->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);

        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_partograph_info;

        $data['partograph_tab_setting'] = get_partograph_tab_setting('',$branch_id);
        $signature_image = get_doctor_signature($data['all_detail']['partograph_list'][0]->attended_doctor);

        $this->load->view('partograph/print_partograph_template',$data);*/
      
    }


    public function print_blank_partographs($booking_id="",$branch_id='')
    {
        $data['page_title'] = "Print partograph";
        $opd_partograph_info = $this->partograph->get_detail_by_booking_id($booking_id,$branch_id);
        $template_format = $this->partograph->template_format(array('setting_name'=>'partograph_PRINT_SETTING','unique_id'=>5,'type'=>1),$branch_id);
        //$template_format = $this->partograph->template_format(array('setting_name'=>'partograph_PRINT_SETTING','unique_id'=>5));
        $template_format_left = $this->partograph->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>1),$branch_id);
        $template_format_right = $this->partograph->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>1),$branch_id);
        $template_format_top = $this->partograph->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>1));
        $template_format_bottom = $this->partograph->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>1),$branch_id);
        $data['template_data_left']=$template_format_left->setting_value;
        $data['template_format_right']=$template_format_right->setting_value;
        $data['template_format_top']=$template_format_top->setting_value;
        $data['template_format_bottom']=$template_format_bottom->setting_value;
        
        $data['template_data']=$template_format->setting_value;
        $data['all_detail']= $opd_partograph_info;

        $data['partograph_tab_setting'] = get_partograph_print_tab_setting();
        //print_r($data['partograph_tab_setting']); exit;
        $data['partograph_medicine_tab_setting'] = get_partograph_medicine_print_tab_setting();
        $data['signature'] = '';
        //print_r($data['all_detail']); exit;
        $data['parto_id'] = $booking_id;
        $data['type'] = 1;

        $this->load->model('general/general_model');
        $data['vitals_list']=$this->general_model->vitals_list();
        $this->load->view('partograph/print_partograph_template',$data);
    }




    public function print_partograph_pdf($id)
    {
            $opd_partograph_info = $this->partograph->get_by_ids($id);
            $partograph_opd_partograph_info = $this->partograph->get_opd_partograph($id); 
            $partograph_opd_test_info = $this->partograph->get_opd_test($id);
            
            $booking_id = $opd_partograph_info['booking_id'];

            $this->load->model('opd/opd_model','opd');
            $opd_details = $this->opd->get_by_id($booking_id);
            $referral_doctor = $opd_details['referral_doctor'];
            
            $patient_code = $opd_partograph_info['patient_code'];
            $attended_doctor = $opd_partograph_info['attended_doctor'];
            $specialization_id = $opd_details['specialization_id'];
            $booking_code = $opd_partograph_info['booking_code'];
            $patient_name = $opd_partograph_info['patient_name'];
            $mobile_no = $opd_partograph_info['mobile_no'];
            $gender = $opd_partograph_info['gender'];
            $age_y = $opd_partograph_info['age_y'];
            $gravida = $opd_partograph_info['gravida'];
            $para = $opd_partograph_info['para'];
            $patient_bp = $opd_partograph_info['patient_bp'];
            $patient_temp = $opd_partograph_info['patient_temp'];
            $patient_weight = $opd_partograph_info['patient_weight'];
            $patient_height = $opd_partograph_info['patient_height'];
            $patient_rbs = $opd_partograph_info['patient_rbs'];
            $patient_spo2 = $opd_partograph_info['patient_spo2'];
            $next_appointment_date = $opd_partograph_info['next_appointment_date'];
            $prv_history = $opd_partograph_info['prv_history'];
            $personal_history = $opd_partograph_info['personal_history'];
            $chief_complaints = $opd_partograph_info['chief_complaints'];
            $examination = $opd_partograph_info['examination'];
            $diagnosis = $opd_partograph_info['diagnosis'];
            $suggestion = $opd_partograph_info['suggestion'];
            $remark = $opd_partograph_info['remark'];
            if($opd_partograph_info['appointment_date']!='0000-00-00')
            {
                $appointment_date =  date('d-M-Y H:i A',strtotime($opd_partograph_info['appointment_date']));
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
            if($gravida>0)
            {
            $month = 'Months';
            if($gravida==1)
            {
              $month = 'Month';
            }
            $age .= ", ".$gravida." ".$month;
            }
            if($para>0)
            {
            $day = 'Days';
            if($para==1)
            {
              $day = 'Day';
            }
            $age .= ", ".$para." ".$day;
            }
            $patient_age =  $age; 
            $doctor_name = get_doctor_name($attended_doctor);
            $referral_doctor = get_doctor_name($referral_doctor);
            
          $partograph_tab_setting = get_partograph_tab_setting(); 
            $presc_partographs = '';
          foreach ($partograph_tab_setting as $value) 
          {
            $test_content = '';
            if(!empty($partograph_opd_test_info)) 
            {
               if(strcmp(strtolower($value->setting_name),'test_result')=='0'){
               $test_content .= '<div style="width:100%;font-weight:bold;text-align: center;margin-top:5%;text-decoration:underline;">'.$value->setting_value.'</div>';
             
                
             $test_content .= '<table border="1" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
                <tr><td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;font-weight:bold;">Test Name</div></td></tr>';
            
                      
                    foreach ($partograph_opd_test_info as $testdata) {  
                       $test_content .= '<tr><td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;">'.$testdata->test_name.'</div></td></tr>';
                         
                      }  
                    
                   
               $test_content .='</table>';

            }

            }
        if(!empty($partograph_opd_partograph_info)) {   
           if(strcmp(strtolower($value->setting_name),'partograph')=='0'){
           
           $presc_partographs .= '<table border="1" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
            <tr>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Name</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Type</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Dose</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Frequency</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Duration</u></th>
            <th valign="top" align="center" style="text-align:center;"><u>Medicine Advice</u></th>
          </tr>';
        
          
                foreach ($partograph_opd_partograph_info as $partographdata) { 
            $presc_partographs .='<tr>
                          <td valign="top" align="left" style="padding-left:4px;"><div style="float:left;text-align: left;">'.$partographdata->medicine_name.'</div></td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$partographdata->medicine_type.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$partographdata->medicine_dose.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$partographdata->medicine_frequency.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$partographdata->medicine_duration.'</td>
                          <td valign="top" align="left" style="padding-left:4px;">'.$partographdata->medicine_advice.'</td>
                      </tr>';
                }  
                   
                

        $presc_partographs .='</table>';  
        }


        } 


        if(!empty($suggestion))
        {
        if(strcmp(strtolower($value->setting_name),'suggestions')=='0'){
        $presc_partographs .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
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
                $presc_partographs .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
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

        $presc_partographs .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
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
            $presc_partographs .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
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
        $presc_partographs .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
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
        $presc_partographs .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
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
            $presc_partographs .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 1%;">
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
         $pdf_data['presc_partographs'] = $presc_partographs;
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
         $pdf_template = $this->load->view('partograph/pdf_template',$pdf_data,true);
         $result['success'] = true;
         $result['msg'] = 'Print partograph success';
         $result['pdf_template'] = $pdf_template;
         echo @json_encode($result);
    }

    


public function print_blank_partograph_pdf($id)
{
        $opd_partograph_info = $this->partograph->get_by_ids($id);
        //echo "<pre>";print_r($opd_partograph_info); exit;
        $partograph_opd_partograph_info = $this->partograph->get_opd_partograph($id); 
        $partograph_opd_test_info = $this->partograph->get_opd_test($id);
        
        $booking_id = $opd_partograph_info['booking_id'];

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
        //$attended_doctor = $opd_partograph_info['attended_doctor'];
        
        $booking_code = $opd_partograph_info['booking_code'];
        $simulation_id = get_simulation_name($patient_details['simulation_id']);
        $patient_name = $simulation_id .' '.$patient_details['patient_name'];
        $mobile_no = $patient_details['mobile_no'];
        $gender = $patient_details['gender'];
        $age_y = $patient_details['age_y'];
        $gravida = $patient_details['gravida'];
        $para = $patient_details['para'];
        
        $patient_bp = $opd_partograph_info['patient_bp'];
        $patient_temp = $opd_partograph_info['patient_temp'];
        $patient_weight = $opd_partograph_info['patient_weight'];
        $patient_height = $opd_partograph_info['patient_height'];
        $patient_rbs = $opd_partograph_info['patient_rbs'];
        $patient_spo2 = $opd_partograph_info['patient_spo2'];
        $next_appointment_date = $opd_partograph_info['next_appointment_date'];
        $prv_history = $opd_partograph_info['prv_history'];
        $personal_history = $opd_partograph_info['personal_history'];
        $chief_complaints = $opd_partograph_info['chief_complaints'];
        $examination = $opd_partograph_info['examination'];
        $diagnosis = $opd_partograph_info['diagnosis'];
        $suggestion = $opd_partograph_info['suggestion'];
        $remark = $opd_partograph_info['remark'];
        if($opd_partograph_info['appointment_date']!='0000-00-00')
        {
            $appointment_date =  $opd_partograph_info['appointment_date'];
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
        
        $patient_age =  $age; 
        $doctor_name = get_doctor_name($attended_doctor);
        $referral_doctor = get_doctor_name($referral_doctor);
        $specialization_id = get_specilization_name($specialization_id);
        $partograph_tab_setting = get_partograph_tab_setting(); 
        $presc_partographs = '';
        $test_content ="";
      


     $pdf_data['doctor_name'] = $doctor_name;
     $pdf_data['specialization_id'] = $specialization_id;    
     $pdf_data['patient_code'] = $patient_code;
     $pdf_data['patient_name'] = $patient_name;
     $pdf_data['presc_partographs'] = $presc_partographs;
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
     
     $pdf_template = $this->load->view('partograph/pdf_template',$pdf_data,true);
     $result['success'] = true;
     $result['msg'] = 'Print partograph success';
     $result['pdf_template'] = $pdf_template;
     
    
    
    echo @json_encode($result);
}

public function view_partograph($id,$branch_id='')
{ 
    $data['form_data'] = $this->partograph->get_by_ids($id);
    $data['partograph_data'] = $this->partograph->get_opd_partograph($id); 
    $data['test_data'] = $this->partograph->get_opd_test($id); 
    $data['page_title'] = $data['form_data']['patient_name']." partograph";
    $data['partograph_tab_setting'] = get_partograph_tab_setting('',$branch_id);
    $this->load->model('general/general_model'); 
    $data['id'] = $id;
    $this->load->view('partograph/print_template1',$data);
    $html = $this->output->get_output();
}





    public function edit($parto_id="")
    { 
       //unauthorise_permission(85,524);
      if(isset($parto_id) && !empty($parto_id) && is_numeric($parto_id))
      {      
        $this->load->model('general/general_model');
        $this->load->model('opd/opd_model','opd'); 
        $post = $this->input->post();
        $get_by_id_data = $this->partograph->get_by_parto_id($parto_id); 
        //echo "<pre>";print_r($get_by_id_data); exit;
        $partograph_data = $get_by_id_data['partograph_list'][0];
        $foetal_heart_list = $get_by_id_data['partograph_list']['foetal_heart_list'];
        $temp_list = $get_by_id_data['partograph_list']['temp_list'];
        $amniotic_fluid_list = $get_by_id_data['partograph_list']['amniotic_fluid_list'];
        $cervic_list = $get_by_id_data['partograph_list']['cervic_list'];
         $contraction_list = $get_by_id_data['partograph_list']['contraction_list'];
        $pulse_bp_list = $get_by_id_data['partograph_list']['pulse_bp_list'];
        $drugs_fluid_list = $get_by_id_data['partograph_list']['drugs_fluid_list'];
        
        $data['simulation_list'] = $this->general_model->simulation_list();      
        $data['partograph_tab_setting'] = get_partograph_tab_setting();
        $data['page_title'] = "Update partograph";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
 
        if(!empty($partograph_data->next_appointment_date) && $partograph_data->next_appointment_date!='0000-00-00 00:00:00' && date('d-m-Y',strtotime($partograph_data->next_appointment_date))!='01-01-1970')
        {
            $next_appointmentdate = date('d-m-Y H:i A',strtotime($partograph_data->next_appointment_date));
        }
        else
        {
            $next_appointmentdate = ''; 
        }
        
       
          $age_y = $partograph_data->age_y;
          
          //$age_h = $present_age['age_h'];
          //present age of patient
        $data['form_data'] = array(
                              'data_id'=>$parto_id, 
                              'patient_id'=>$partograph_data->patient_id,
                              'booking_id'=>$partograph_data->booking_id,
                              'booking_date'=>$partograph_data->booking_date,
                              'booking_time'=>$partograph_data->booking_time,
                              'attended_doctor'=>$partograph_data->attended_doctor,
                              'appointment_date'=>$partograph_data->appointment_date,
                              'patient_code'=>$partograph_data->patient_code,
                              'booking_code'=>$partograph_data->booking_code,
                              'simulation_id'=>$partograph_data->simulation_id,
                              'patient_name'=>$partograph_data->patient_name,
                              'mobile_no'=>$partograph_data->mobile_no,
                              'gender'=>$partograph_data->gender,
                              'age_y'=>$age_y,
                              'gravida'=>$partograph_data->gravida,
                              'para'=>$partograph_data->para,
                              
                             
                              
                              'next_appointment_date'=>$next_appointmentdate,
                              "relation_name"=>$partograph_data->relation_name,
                              "relation_type"=>$partograph_data->relation_type,
                              "relation_simulation_id"=>$partograph_data->relation_simulation_id,
                              );

       $data['foetal_heart_list'] = $foetal_heart_list;
       $data['temp_list'] = $temp_list;
       $data['amniotic_fluid_list'] = $amniotic_fluid_list;
       $data['cervic_list'] = $cervic_list;
       $data['contraction_list'] = $contraction_list;
       $data['pulse_bp_list'] = $pulse_bp_list; 
       $data['drugs_fluid_list']=$drugs_fluid_list;                    
      
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $parto_id = $this->partograph->save_partograph();
                $this->session->set_userdata('opd_parto_id',$parto_id);
                $this->session->set_flashdata('success','Partograph updated successfully.');
                redirect(base_url('partograph'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }
        }
      $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();

       $this->load->view('partograph/prescription',$data);       
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
                                        'gravida'=>$post['gravida'],
                                        'para'=>$post['para'],
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
            $result = $this->partograph->get_test_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    

    public function chart_one()
    {
      $post=$this->input->post();
      $result = $this->partograph->chart_one($post['parto_id'], $post['branch_id']);
     
      if(!empty($result))
      {
         foreach ($result as $value) {
         $result2[]=array('time'=>date('h:i A',strtotime($value->time)), 'value'=>$value->value);
        }
        echo json_encode($result2,true);
      } 
       
    }
     public function chart_two()
    {
      $post=$this->input->post();
      $result = $this->partograph->chart_two($post['parto_id'], $post['branch_id']);
      if(!empty($result))
      {
       $result2=array();
        foreach ($result as $value) {
         $result2[]=array('time'=>date('h:i A',strtotime($value->time)), 'value'=>$value->value);
        }
        echo json_encode($result2,true);
      } 
       
    }
     public function chart_three()
    {
      $post=$this->input->post();
      $result = $this->partograph->chart_three($post['parto_id'], $post['branch_id']);
    
      if(!empty($result))
      {
         foreach ($result as $value) {
         $result2[]=array('time'=>date('h:i A',strtotime($value->time)), 'value'=>$value->value,'value_x'=>$value->value_x);
        }
        echo json_encode($result2,true);
      } 
       
    }
     public function chart_four()
    {
      $post=$this->input->post();
      $result = $this->partograph->chart_four($post['parto_id'], $post['branch_id']);
     
      if(!empty($result))
      {
         foreach ($result as $value) {
         $result2[]=array('time'=>date('h:i A',strtotime($value->time)), 'value'=>$value->value);
        }
        echo json_encode($result2,true);
      } 
       
    }
     public function chart_five()
    {
      $post=$this->input->post();
      $result = $this->partograph->chart_five($post['parto_id'], $post['branch_id']);
     
      if(!empty($result))
      {
       $result2=array();
        foreach ($result as $value) {
         $result2[]=array('time'=>date('h:i A',strtotime($value->time)), 'value'=>$value->value);
        }
        echo json_encode($result2,true);
      } 
       
    }
     public function chart_six()
    {
      $post=$this->input->post();
      $result = $this->partograph->chart_six($post['parto_id'], $post['branch_id']);
     
      if(!empty($result))
      {
         foreach ($result as $value) {
          if($value->value_low_bp =='' ){
            $low_bp=$high_bp=0;
          }
          else{
            $low_bp=$value->value_low_bp;
            $high_bp=$value->value_high_bp;
          }
         $result2[]=array('time'=>date('h:i A',strtotime($value->time)), 'value'=>$value->value, 'value_x'=>$value->value_x, 'value_low_bp'=>$low_bp, 'value_high_bp'=>$high_bp);
        }
        echo json_encode($result2,true);
      } 
       
    }
     public function chart_seven()
    {
      $post=$this->input->post();
      $result = $this->partograph->chart_seven($post['parto_id'], $post['branch_id']);
     
      if(!empty($result))
      {
       $result2=array();
        foreach ($result as $value) {
         $result2[]=array('time'=>date('h:i A',strtotime($value->time)), 'value'=>$value->value);
        }
        echo json_encode($result2,true);
      } 
       
    }

}
?>