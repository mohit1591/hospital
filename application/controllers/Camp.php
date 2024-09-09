<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Camp extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('camp/camp_model','camp');
		$this->load->library('form_validation');
    }

  public function index()
  {

        unauthorise_permission(319,1910);
        $users_data = $this->session->userdata('auth_users');
        //print_r($users_data);
        $this->session->unset_userdata('opd_search');
        $this->session->unset_userdata('opd_particular_billing');
        $this->session->unset_userdata('opd_particular_payment');
        $this->load->model('eye/general/eye_general_model','eye_general_model'); 
        $data['camp_list'] = $this->eye_general_model->camp_list();
        $data['page_title'] = 'Camp Booking List';
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
        $this->load->view('camp/list',$data);
  }


    public function ajax_list()
    {   
        unauthorise_permission(319,1910);
        $users_data = $this->session->userdata('auth_users');

        $list = $this->camp->get_datatables();  
       // print_r($list);die;
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $checking_status="";
        $ids_n='';
        $ids='';
        foreach ($list as $test) 
        {
         	$no++;
            $row = array(); 
            $prescription_count = $this->camp->get_prescription_count($test->id);
            ////////// Check  List /////////////////
            if($test->booking_status==0)
            {
                $booking_status = '<font color="red">Pending</font>';
            }   
            elseif($test->booking_status==1)
            {
                $booking_status = '<font color="green">Confirm</font>';
            }                 
            elseif($test->booking_status==2)
            {
                $booking_status = '<font color="blue">Attended</font>';
            } 
            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$test->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test->id.'">'; 
            }
            else
            {
               $row[]='';
            }
            $row[] = $test->patient_reg_no;
            $row[] = $test->booking_code;
            $row[] = $test->patient_name;
            if(strtotime($test->validity_date)>0) 
                $row[] = date('d-m-Y', strtotime($test->validity_date)); 
            else 
              $row[]="";
            $row[] = date('d-m-Y',strtotime($test->booking_date));
            $row[] = $booking_status;
            $row[] = "Dr. ".$test->doctor_name;
      			$row[] = $test->mobile_no;
      			$row[] = $test->gender;
            $row[] = $test->address;
            if(!empty($test->relation_name))
            {
              $row[] = $test->relation.' '.$test->relation_simulation.' '.$test->relation_name;  
            }
            else
            {
              $row[] ='';
            }
            $row[] = $test->patient_email;
            $row[] = $test->insurance_type;
            $row[] = $test->insurance_company;
            $row[] = $test->patient_source;
            $row[] = $test->disease;
            $row[] = $test->doctor_hospital_name;
            $row[] = $test->specialization;
            $row[] = "Dr. ".$test->doctor_name;
            $row[] = date('h:i A', strtotime($test->booking_time));

            
            $row[] = $test->policy_no;
            $row[] = $test->camp_name;

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
	               //if(in_array('531',$users_data['permission']['action']))
	               {
	                    $btn_confirm = ' <a class="btn-custom" onclick="return confirm_booking('.$test->id.');" title="Confirm Booking"><i class="fa fa-pencil"></i> Confirm </a>';
	               }
              }
             
             if(in_array('1915',$users_data['permission']['action']))
             {
                $btn_edit = ' <a class="btn-custom" href="'.base_url("camp/edit_booking/".$test->id).'" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>';
              }
              
              
              if(in_array('1914',$users_data['permission']['action']))
              {
                    $btn_delete = ' <a class="btn-custom" onClick="return delete_opd_booking('.$test->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
              }
        }
        
        $btn_prescription='';
            
             if(in_array('2413',$users_data['permission']['action']))
            {
                $btn_prescription .= '<li><a  href="'.base_url("eye/add_eye_prescription/test/".$test->id).'" title="Add Prescription"><i class="fa fa-eye"></i> Add Adv. Eye Prescription</a></li>';
            }
            
            if(in_array('1418',$users_data['permission']['action']))
            {
                $print_url_eye = "'".base_url('prescription/print_blank_prescriptions/'.$test->id.'/'.$test->branch_id)."'";
                $btn_prescription .= '<li><a  href="'.base_url("eye/add_prescription/".$test->id).'" title="Add Prescription"><i class="fa fa-eye"></i> Add Eye Prescription</a></li>';
            }
            
            
            if(in_array('1419',$users_data['permission']['action']))
            {
                $print_url_eye = "'".base_url('eye/add_prescription/print_blank_prescriptions/'.$test->id.'/'.$test->branch_id)."'";
                $btn_prescription .= '<li><a  href="javascript:void(0)" onClick="return print_window_page('.$print_url_eye.')" title="Print" ><i class="fa fa-eye"></i
                > Blank Eye Prescription  </a></li>';
                
            }

            if(in_array('1441',$users_data['permission']['action']))
            {
              $btn_prescription .= '<li><a  href="'.base_url("eye/biometric_details/add/".$test->id).'" title="Biometric Details"><i class="fa fa-eye"></i> Biometric Test Form</a></li>';
            }
             
        $print_pdf_url = "'".base_url('camp/print_booking_report/'.$test->id.'/'.$test->branch_id)."'";
        $btn_print = '';

        $btn_download_prescription = '<li><a  href="'.base_url('/camp/print_booking_report/'.$test->id.'/'.$test->branch_id.'/1').'" title="Download Prescription"><i class="fa fa-download"></i> Download Pdf</a></li>';

        $btn_download_image = '<li><a  href="'.base_url('/camp/download_image/'.$test->id.'/'.$test->branch_id).'" title="Download Image" target="_blank"><i class="fa fa-download"></i> Download Image</a></li>';
        $btn_print_barcode = '';
        $print_barcode_url = "'".base_url('camp/print_barcode/').$test->id."'";
        $btn_print_barcode = ' <li><a  href="javascript:void(0)" onclick = "return print_window_page('.$print_barcode_url.');" title="Print Barcode" ><i class="fa fa-print"></i> Print Barcode </a></li>';

        
        $btn_a = '<div class="slidedown">
        <button  class="btn-custom">More <span class="caret"></span></button>
        <ul class="slidedown-content">
          '.$btn_print_barcode.$btn_prescription.$btn_download_prescription.$checking_status.$btn_download_image.'
        </ul>
      </div> ';
            // End Action Button //
            $row[] = $btn_confirm.$btn_edit.$btn_delete.$btn_print.$btn_a;   
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->camp->count_all(),
                        "recordsFiltered" => $this->camp->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function print_barcode($booking_id='')
    {
      
      $users_data = $this->session->userdata('auth_users'); 
      if(!empty($booking_id))
      {
        $booking_data = $this->camp->get_by_id($booking_id);
        $barcode_setting = barcode_setting($users_data['parent_id']);
        $data['total_receipt'] = $barcode_setting->total_receipt;
        
        $data['barcode_image'] = $booking_data['barcode_image'];
        $data['barcode_type'] = $booking_data['barcode_type'];
        $data['year_name'] = $booking_data['year_name'];
        $data['month_name'] = $booking_data['month_name'];
        $data['branch_id'] = $users_data['parent_id'];
        //echo "<pre>";print_r($barcode_setting); exit;

        $this->load->view('test/print_barcode_template',$data);
      }
    }


    public function download_image($id="",$branch_id='')
    {
      $data['type'] = 2;
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

      $get_by_id_data = $this->camp->get_all_detail_print($id,$branch_id);
      // print_r($get_by_id_data);die;
      $template_format = $this->camp->template_format(array('section_id'=>1,'types'=>1),$branch_id);
      //print_r($get_by_id_data); exit;
      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
       //print_r($data['all_detail']);die; 
      $get_refund = $this->camp->get_payment_refund($id,$branch_id);
      $refund_payment = 0;
      if(!empty($get_refund[0]->refund_payment))
      {
        $refund_payment = $get_refund[0]->refund_payment;
      }
      $data['refund_payment'] = $refund_payment;
      
        $this->load->view('camp/print_template_opd',$data);
      
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
        unauthorise_permission(319,1914);
       if(!empty($id) && $id>0)
       {
           $result = $this->camp->delete_booking($id);
           $response = "Camp successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(319,1914);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->camp->deleteall($post['row_id']);
            $response = "Camp Booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(319,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->camp->get_by_id($id);  
        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('camp/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(319,1913);
        $data['page_title'] = 'Camp Booking Archive List';
        $this->load->helper('url');
        $this->load->view('camp/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(319,1913);
        //$this->session->unset_userdata('referral_doctor_id');
        $this->load->model('camp/camp_archive_model','camp_archive'); 
		    $users_data = $this->session->userdata('auth_users');
        $list = $this->camp_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $camp) { 
            $no++;
            $row = array();
            if($camp->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($camp->state))
            {
                $state = " ( ".ucfirst(strtolower($camp->state))." )";
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

            if($camp->booking_status==0)
            {
                $booking_status = '<font color="red">Pending</font>';
            }   
            elseif($camp->booking_status==1){
                $booking_status = '<font color="green">Confirm</font>';
            }                 
            elseif($camp->booking_status==2){
                $booking_status = '<font color="blue">Attended</font>';
            } 
            if($camp->type==0)
            {
                $type = 'Booking';
            }   
            elseif($camp->type==1){
                $type = 'Billing';
            }

            $attended_doctor_name ="";
            $attended_doctor_name = get_doctor_name($camp->attended_doctor);

            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$camp->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$camp->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $camp->booking_code;
            // $row[] = $type;
            $row[] = $camp->patient_name;
            $row[] = $attended_doctor_name;
           
            
            $row[] = date('d-m-Y',strtotime($camp->booking_date));
            
           
            //$row[] = $test->total_amount;
            $row[] = $booking_status;
            //$row[] = date('d-M-Y H:i A',strtotime($camp->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
            if(in_array('1912',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_camp('.$camp->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            }
            if(in_array('1911',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$camp->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->camp_archive->count_all(),
                        "recordsFiltered" => $this->camp_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore_camp($id="")
    {
        unauthorise_permission(319,1912);
        $this->load->model('camp/camp_archive_model','camp_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->camp_archive->restore($id);
           $response = "Camp Booking successfully restore in Camp Booking list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(319,1912);
        $this->load->model('camp/camp_archive_model','camp_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->camp_archive->restoreall($post['row_id']);
            $response = "Camp successfully restore in Camp list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(319,1911);
        $this->load->model('camp/camp_archive_model','camp_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->camp_archive->trash($id);
           $response = "Camp successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(319,1911);
        $this->load->model('camp/camp_archive_model','camp_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->camp_archive->trashall($post['row_id']);
            $response = "Camp successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function opd_dropdown()
  {
      $opd_list = $this->camp->employee_type_list();
      $dropdown = '<option value="">Select OPD</option>'; 
      if(!empty($opd_list))
      {
        foreach($opd_list as $opd)
        {
           $dropdown .= '<option value="'.$camp->id.'">'.$camp->doctor_name.'</option>';
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
      $users_data = $this->session->userdata('auth_users');
      unauthorise_permission(319,1916);
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
      $camp_id="";
      $diseases="";
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
           //print_r($patient_data);
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
           //print_r($patient_data);
           //die;
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
          $data['referal_doctor_list'] = $this->camp->referal_doctor_list($post['branch_id']);
        }
        else
        {
          $data['referal_doctor_list'] = $this->camp->referal_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['referal_hospital_list'] = $this->camp->referal_hospital_list($post['branch_id']);
        }
        else
        {
          $data['referal_hospital_list'] = $this->camp->referal_hospital_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['attended_doctor_list'] = $this->camp->attended_doctor_list($post['branch_id'],$post['specialization']);
        }
        else
        {
          $data['attended_doctor_list'] = $this->camp->attended_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['attended_doctor_list'] = $this->camp->attended_doctor_list($post['branch_id']);
        }
        else
        {
          $data['attended_doctor_list'] = $this->camp->attended_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['employee_list'] = $this->camp->employee_list($post['branch_id']);
        }
        else
        {
          $data['employee_list'] = $this->camp->employee_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['profile_list'] = $this->camp->profile_list($post['branch_id']);
        }
        else
        {
          $data['profile_list'] = $this->camp->profile_list();
        }
        if(!empty($post['branch_id']))
        {
          $data['source_list'] = $this->camp->source_list($post['branch_id']);

        }
        else
        {
          $data['source_list'] = $this->camp->source_list();

        }

        if(!empty($post['branch_id']))
        {
           $data['diseases_list'] = $this->camp->diseases_list($post['branch_id']);
        }
        else
        {
           $data['diseases_list'] = $this->camp->diseases_list();
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
        $token_type=$this->camp->get_token_setting();
        $token_type=$token_type['type'];
        $validity=0;
        $validity_days=$this->camp->get_opd_validity_days();

        if(!empty($validity_days['days']))
        {
          $validity=$validity_days['days'];
        }
        
        $validate_date = date('Y-m-d', strtotime(' + '.$validity.' days')); 
        $validatedate  = date('d-m-Y', strtotime($validate_date));
        $data['page_title'] = "Camp Booking";  

        if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }

        $booking_code = generate_unique_id(45);
        $data['form_error'] = []; 
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['vitals_list']=$this->general_model->vitals_list();
        $data['simulation_array']= $this->general_model->simulation_list();

        $this->load->model('eye/general/eye_general_model','eye_general_model'); 
        $data['camp_list'] = $this->eye_general_model->camp_list();

        //pannel_type
        $data['validity_days']=$validity;
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
                                  'attended_doctor'=>"",
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
                                  'camp_id'=>$camp_id,
                                  'diseases'=>$diseases,
                                  "country_code"=>"+91",
                                  'specialization_id'=>'',
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
                $booking_id = $this->camp->save_booking();

              if(!empty($booking_id))
              {
                  $get_by_id_data = $this->camp->get_by_id($booking_id);
                  $patient_name = $get_by_id_data['patient_name'];
                  $booking_code = $get_by_id_data['booking_code'];
                  $paid_amount = $get_by_id_data['paid_amount'];
                  $mobile_no = $get_by_id_data['mobile_no'];
                  $patient_email = $get_by_id_data['patient_email'];
                  $paid_amount = 0;
                //check permission
                if(in_array('640',$users_data['permission']['action']))
                {
                    if(!empty($mobile_no))
                    {
                      send_sms('opd_booking',28,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{camp_booking_no}'=>$booking_code,'{Amount}'=>$paid_amount)); 

                      //echo "dsds"; die; 
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

                $this->session->set_userdata('opd_booking_id',$booking_id);
                $this->session->set_flashdata('success','Camp booking successfully booked.');
                redirect(base_url('camp/booking?status=print'));
      }
            else
            {
               

                $data['form_error'] = validation_errors();  
                
            }     
        }

       $this->load->view('camp/booking',$data);       
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
       unauthorise_permission(319,1915);
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model'); 
        $post = $this->input->post();
        $result = $this->camp->get_by_id($id); 
        //print '<pre>' ;print_r($result);die;
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->camp->referal_doctor_list();
        $data['attended_doctor_list'] = $this->camp->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();  
        $data['package_list'] = $this->general_model->package_list();
        $data['diseases_list'] = $this->camp->diseases_list();
        $data['source_list'] = $this->camp->source_list();
        $data['referal_hospital_list'] = $this->camp->referal_hospital_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list(); 
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['page_title'] = "Update Camp Booking";  
        $data['simulation_array']= $this->general_model->simulation_list();
        $this->load->model('eye/general/eye_general_model','eye_general_model'); 
        $data['camp_list'] = $this->eye_general_model->camp_list();
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $token_type=$this->camp->get_token_setting();
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
        $get_payment_detail= $this->camp->payment_mode_detail_according_to_field($result['payment_mode'],$id);
        $total_values='';

        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
       $adhar_no='';
       if($result['adhar_no']!=0)
       {
          $adhar_no=$result['adhar_no'];
       }
       //}


      $data['vitals_list']=$this->general_model->vitals_list();
      $data['doctor_available_time'] = $this->general_model->doctor_time($result['attended_doctor']);
      $data['doctor_available_slot'] = $this->get_doctor_slot($result['attended_doctor'],$result['available_time'],$result['doctor_slot'],$result['booking_date'],$result['id']);
      //echo "<pre>";print_r($result); die;
      if($result['dob']=='1970-01-01' || $result['dob']=="0000-00-00")
      {
        
        $present_age = get_patient_present_age('',$result);
      }
      else
      {
        $dobs = date('d-m-Y',strtotime($result['dob']));
        $present_age = get_patient_present_age($dobs);
      }

      
      $age_y = $present_age['age_y'];
      
      $age_m = $present_age['age_m'];
     
      $age_d = $present_age['age_d'];
      $dob ='';
      if(!empty($result['dob']) && $result['dob']!='0000-00-00' && $result['dob']!='1971-01-01')
      {
        $dob = date('d-m-Y',strtotime($result['dob']));  
      }
      
       
              $data['form_data'] = array(
                                    'data_id'=>$result['id'],
                                    'branch_id'=>$result['branch_id'], 
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
                                    'dob'=>$dob,
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
                                    'camp_id'=>$result['camp_id'],
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
                $booking_id = $this->camp->save_booking();
                $this->session->set_userdata('opd_booking_id',$booking_id);
                $this->session->set_flashdata('success','Camp booking successfully updated.');
                redirect(base_url('camp/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('camp/booking',$data);       
      }
    }

    private function _validateform()
    {
        $post = $this->input->post(); 

        $field_list = mandatory_section_field_list(3);
        //echo "<pre>"; print_r($field_list); exit;
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
        $this->form_validation->set_rules('camp_id', 'Camp', 'trim|required');
        //$this->form_validation->set_rules('adhar_no', 'adhar no', 'min_length[12]|max_length[16]'); 
        $this->form_validation->set_rules('specialization', 'specialization', 'trim|required'); 
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
                //$this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
              if(($post['age_y']=='0' && $post['age_m']=='0' && $post['age_d']=='0') || ((empty($post['age_y']) && empty($post['age_m']) && empty($post['age_d']))) )
                    {
                         
                        $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|is_natural_no_zero|max_length[3]');
                    }
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
                                        'total_amount'=>0,
                                        'net_amount'=>0,
                                        'paid_amount'=>0,
                                        'discount'=>0,
                                        'balance'=>0,
                                        'type'=>$post['type'],
                                        'patient_email'=>$post['patient_email'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'booking_time'=>$post['booking_time'],
                                        'total_amount'=>0,
                                        'net_amount'=>0,
                                        'paid_amount'=>0,
                                        'discount'=>0,
                                        'balance'=>0,
                                        "field_name"=>$total_values,
                                        'pay_now'=>$pay_now,
                                        'payment_mode'=>0,
                                        'specialization_id'=>$post['specialization'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        "country_code"=>"+91",
                                        'diseases'=>$post['diseases'],
                                        'next_app_date'=>"00-00-0000",
                                        'kit_amount'=>$post['kit_amount'],
                                        'consultants_charge'=>0,
                                        'package_id'=>$post['package_id'],
                                        'source_from'=>$post['source_from'],
                                        'remarks'=>$post['remarks'],
                                        'pannel_type'=>$post['pannel_type'],
                                        'opd_type'=>0,
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
                                        'camp_id'=>$post['camp_id'],
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
         $booking_date = date("Y-m-d", strtotime($post['booking_date']) ); 
         $token_type=$this->camp->get_token_setting();
         $token_type=$token_type['type'];
         //echo $type;die;
          if($token_type=='0') //hospital wise token no
          { 
            
           $patient_token_details_for_hospital=$this->camp->get_patient_token_details_for_type_hospital($booking_date,$token_type);
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
            $patient_token_details_for_doctor=$this->camp->get_patient_token_details_for_type_doctor($doctor_id,$booking_date,$token_type);
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
        $result = $this->camp->get_by_id($id);
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
        
        $rate_data = $this->camp->opd_doctor_rate($result['attended_doctor']);
        if(!empty($total_amount) && $total_amount!='0.00'){ $total_amount = $total_amount; }else { $total_amount = $rate_data; }
        if(!empty($net_amount) && $total_amount!='0.00'){ $net_amount = $net_amount; }else{ $net_amount = $rate_data; }
        if(!empty($paid_amount) && $total_amount!='0.00' && $paid_amount!='0.00'){ $paid_amount = $paid_amount;}else{ $paid_amount = $rate_data;}
        if(!empty($discount) ){	$discount = $discount; }else{ $discount = '0.00'; }
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
                $this->camp->confirm_booking();
                echo 1;
                return false;
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('camp/confirm',$data);       
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

        if(!empty($post['discount']) && $post['discount']>0)
        {

        }
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $balance_amount = $this->camp->check_patient_balance($post['patient_id']); 
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
              $balance_amount = $this->camp->check_patient_balance($post['patient_id']);    
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

	//Camp Prescription 

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
           $this->load->model('camp/camp_model');
           $this->load->model('patient/patient_model');
           $opd_booking_data = $this->camp_model->get_by_id($booking_id);
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

              $relation_name = $opd_booking_data['relation_name'];
              $relation_type = $opd_booking_data['relation_type'];
              $relation_simulation_id = $opd_booking_data['relation_simulation_id'];
              
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
        $data['referal_doctor_list'] = $this->camp->referal_doctor_list();
        $data['attended_doctor_list'] = $this->camp->attended_doctor_list();
        $data['employee_list'] = $this->camp->employee_list();
        $data['profile_list'] = $this->camp->profile_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['chief_complaints'] = $this->general_model->chief_complaint_list(); 
        $data['examination_list'] = $this->camp->examinations_list();
        $data['diagnosis_list'] = $this->camp->diagnosis_list();  
        $data['suggetion_list'] = $this->camp->suggetion_list();  
        $data['prv_history'] = $this->camp->prv_history_list();  
        $data['personal_history'] = $this->camp->personal_history_list();
        $data['template_list'] = $this->camp->template_list();    
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
          /*$this->camp->save_prescription();
          $this->session->set_flashdata('success','Prescription successfully added.');
          redirect(base_url('prescription'));*/

          $prescription_id = $this->camp->save_prescription();
          $this->session->set_userdata('opd_prescription_id',$prescription_id);
          $this->session->set_flashdata('success','Prescription successfully added.');
          redirect(base_url('prescription/?status=print'));


        } 	

         $this->load->view('camp/prescription',$data);
    }

    
    function get_template_data($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->camp->template_data($template_id);
        echo $templatedata;
      }
    }



    function get_template_test_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->camp->get_template_test_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_template_prescription_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->camp->get_template_prescription_data($template_id);
        
        echo $templatetestdata;
      }
    }
    

   function complaints_name($complaints_id="")
   {
      if($complaints_id>0)
      {
         $complaintsname = $this->camp->compalaints_list($complaints_id);
         echo $complaintsname;
      }
    }


   

    function examination_name($examination_id="")
    {
      if($examination_id>0)
      {
         $examination_name = $this->camp->examination_list($examination_id);
         echo $examination_name;
      }
    }
   

    function diagnosis_name($diagnosis_id="")
    {
      if($diagnosis_id>0)
      {
         $diagnosis_name = $this->camp->diagnosis_name($diagnosis_id);
         echo $diagnosis_name;
      }
    }
  
    //hms_opd_suggetion
    function suggetion_name($suggetion_id="")
    {
      if($suggetion_id>0)
      {
         $suggetion_name = $this->camp->suggetion_name($suggetion_id);
         echo $suggetion_name;
      }
    }
   

    function personal_history_name($personal_his_id="")
    {
      if($personal_his_id>0)
      {
         $personal_his_name = $this->camp->personal_history_name($personal_his_id);
         echo $personal_his_name;
      }
    }
  

    function prv_history_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->camp->prv_history_name($pre_id);
         echo $pre_name;
      }
    }
  


    public function particular_billing_list()
    {
        unauthorise_permission(319,121);
        $data['page_title'] = 'Camp Particular Billing list'; 
        $this->load->view('camp/particular_billing_list',$data);
    }

    

    public function billing($pid="")
    {
        unauthorise_permission(319,530);
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
        $data['diseases_list'] = $this->camp->diseases_list();

    		$data['simulation_list'] = $this->general_model->simulation_list();
    		$data['particulars_list'] = $this->general_model->particulars_list();
    		$data['country_list'] = $this->general_model->country_list();
        $data['referal_doctor_list'] = $this->camp->referal_doctor_list();
    		$data['page_title'] = "Camp Billings";  
        $post = $this->input->post();
        if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }
        
        $booking_code = generate_unique_id(9);
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
                $booking_id = $this->camp->save_booking();
                //$this->session->set_flashdata('success','Camp particular successfully saved.');
                //redirect(base_url('opd'));
                $this->session->set_userdata('opd_booking_id',$booking_id);
                $this->session->set_flashdata('success','Camp Particular successfully saved.');
                redirect(base_url('camp/?status=print&type=1'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('camp/billing',$data); 
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
      
      $data['page_title'] = "Print Bookings";
      $booking_id= $this->session->userdata('opd_booking_id');
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
      $get_by_id_data = $this->camp->get_all_detail_print($booking_id,$branch_id);
      //echo "<pre>";print_r($get_by_id_data);die;
      $template_format = $this->camp->template_format(array('section_id'=>1,'types'=>1),$branch_id);
      //print_r($get_by_id_data); exit;
      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
     // print_r($data);die;
      $this->load->view('camp/print_template_opd',$data);
    }

    public function print_blank_booking_report($id="",$type='')
    {
      
      $booking_id= $this->session->userdata('opd_booking_id');
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
      $get_by_id_data = $this->camp->get_all_detail_print($booking_id);
      $template_format = $this->camp->template_format(array('section_id'=>1,'types'=>1));
      $data['template_data']=$template_format;
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_by_id_data['payment_mode']);
      $data['all_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;
      //print '<pre>';print_r($get_by_id_data); exit;
      //print '<pre>';print_r($data['all_detail']); exit;
      $this->load->view('camp/print_blank_template_opd',$data);
    }

    


    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->camp->referal_doctor_list();
        $data['attended_doctor_list'] = $this->camp->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['source_list'] = $this->camp->source_list();  
        $this->load->model('eye/general/eye_general_model','eye_general_model'); 
        $data['camp_list'] = $this->eye_general_model->camp_list();
        
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
                                    "status"=>"", 
                                    "remark"=>"",
                                    "branch_id"=>'',
                                    'source_from'=>'',
                                    "disease"=>"",
                                    "disease_code"=>"",
                                    "camp_id"=>'',
                                    
                                  );
        if(isset($post) && !empty($post))
        {
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('opd_search', $marge_post);

        }
        $opd_search = $this->session->userdata('opd_search');
        if(isset($opd_search) && !empty($opd_search))
        {
            $data['form_data'] = $opd_search;
        }
        $this->load->view('camp/advance_search',$data);
    }

    public function get_branch_data($branch_id)
    {
        if($branch_id>0)
        {
          $branchdata = $this->camp->branch_data($branch_id);
          
          echo $branchdata;
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('opd_search');
    }

    function get_simulation_data($branch_id="")
    {
      if($branch_id>0)
      {
        $simulationdata = $this->camp->get_simulation_data($branch_id);
        echo $simulationdata;
      }
    }

    function get_referral_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $doctor_data = $this->camp->get_referral_doctor_data($branch_id);
        echo $doctor_data;
      }
    }

    function get_specialization_data($branch_id="")
    {
      if($branch_id>0)
      {
        $specialization_data = $this->camp->get_specialization_data($branch_id);
        echo $specialization_data;
      }
    }

    function get_attended_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $attended_data = $this->camp->get_attended_doctor_data($branch_id);
        echo $attended_data;
      }
    }


    public function opd_excel_old()
    {
        $list = $this->camp->search_opd_data();
       // echo "<pre>";print_r($list); exit;
        $columnHeader = '';  
        $columnHeader = " Camp Booking No.". "\t"  . "Patient Name" . "\t" . "Patient Reg. No.". "\t" . "Doctor Name". "\t" . "Specialization" . "\t". "Appointment Date" ."\t". "Age" . "\t" . "Gender" . "\t". "Mobile" ;
        $setData = '';
        if(!empty($list))
        {
            $rowData = "";
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
            if($opds->appointment_date!='0000-00-00')
            {
              $appointment_date = date('d M Y',strtotime($opds->appointment_date)); 
            }
            else
            {
                $appointment_date = ""; 
            }
            
            $booking_date = date('d M Y',strtotime($opds->booking_date));

                $genders = array('0'=>'Female', '1'=>'Male', '2'=>'Other');
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
                $bookingcode = " ";
                if(!empty($opds->booking_code))
                {
                  $bookingcode = $opds->booking_code;
                }
                $rowData = $bookingcode ."\t". $opds->patient_name . "\t" . $opds->patient_code. "\t" . $attended_doctor_name. "\t". $specialization_id. "\t" .  $appointment_date . "\t" . $patient_age . "\t" . $gender . "\t" . $opds->mobile_no; 
                $setData .= trim($rowData) . "\n";    
           
            }
        }

      

        echo '<pre>'.$setData;die;
        header("Content-type: application/octet-stream");  
        header("Content-Disposition: attachment; filename=opd_list_".time().".xls");  
        header("Pragma: no-cache");  
        header("Expires: 0");  

        echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
    }


    public function opd_excel()
    {
       // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $data_patient_reg = get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array('Camp Booking No.','Patient Name',$data_patient_reg,'Booking Date','Age','Gender','Mobile','Doctor Name','Specialization','Camp');
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
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          $list = $this->camp->search_opd_data();
          //print_r($list);die;
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
                    array_push($rowData,$opds->booking_code,$opds->patient_name,$opds->patient_code,$booking_date,$patient_age,$gender,$opds->mobile_no,$attended_doctor_name,$specialization_id,$opds->camp_name);
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
          header("Content-Disposition: attachment; filename=camp_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          //ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function opd_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array('Camp Booking No.','Patient Name',$data_patient_reg,'Booking Date','Age','Gender','Mobile','Doctor Name','Specialization', 'Camp');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->camp->search_opd_data();
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
                    array_push($rowData,$opds->booking_code,$opds->patient_name,$opds->patient_code,$booking_date,$patient_age,$gender,$opds->mobile_no,$attended_doctor_name,$specialization_id, $opds->camp_name);
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
         header("Content-Disposition: attachment; filename=camp_list_".time().".csv");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               //ob_end_clean();
               $objWriter->save('php://output');
         }
         
        
    }

    public function opd_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->camp->search_opd_data();
        $this->load->view('camp/opd_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("camp_list_".time().".pdf");
    }
    public function opd_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->camp->search_opd_data();
      $this->load->view('camp/opd_html',$data); 
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
            $result = $this->camp->get_test_vals($vals);  
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
            $result = $this->camp->get_medicine_auto_vals($vals);  
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
            $result = $this->camp->get_dosage_vals($vals);  
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
            $result = $this->camp->get_type_vals($vals);  
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
            $result = $this->camp->get_duration_vals($vals);  
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
            $result = $this->camp->get_frequency_vals($vals);  
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
            $result = $this->camp->get_advice_vals($vals);  
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
        $diseases_data = $this->camp->get_diseases_data($branch_id);
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
        $source_from_data = $this->camp->get_source_from_data($branch_id);
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
    //echo "<pre>";print_r($this->input->post()); die;
    $users_data = $this->session->userdata('auth_users');
    $col_ids_array=$this->input->post('rec_id');
    $module_id=$this->input->post('module_id');
    $branch_id=$users_data['parent_id'];
    $this->camp->delete_existing_branch_list_cols($module_id,$branch_id);
    $this->camp->insert_new_cols_branch_list_cols($branch_id, $module_id, $col_ids_array);
    echo "Record Inserted Successfully";
  }

  public function reset_coloumn_record()
  {
    $module_id=$this->input->post('module_id');
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    $this->camp->delete_existing_branch_list_cols($module_id,$branch_id);
    echo json_encode(array("status"=>200));
  }

  public function update_doctor_status($opd_id="",$status_type="")
  {
    $update_status= $this->camp->update_doctor_status($opd_id,$status_type);
    echo $update_status;exit;
  }

  public function get_validity_date_in_between(){
     $doctor_id= $this->input->post('doctor_id');
     $booking_date= $this->input->post('booking_date');
     $patient_id= $this->input->post('patient_id');
     $result= $this->camp->get_validity_date_in_between($doctor_id,$booking_date,$patient_id);
     echo $result;die;
  }
public function get_validate_date()
{
    $doctor_id= $this->input->post('doctor_id');
    $booking_date= $this->input->post('booking_date');
    $patient_id= $this->input->post('patient_id');
     $result= $this->camp->get_validate_date($doctor_id,$booking_date,$patient_id);
     echo json_encode(array('date'=>$result));exit;
}
// please write code above    
}
?>