<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
        $this->load->model('general/general_model');
        $this->load->model('ambulance/booking_model','booking');
		$this->load->library('form_validation');
    }

  public function index()
  {
        unauthorise_permission(349,2086);
        $users_data = $this->session->userdata('auth_users');
        //print_r($users_data);
        $this->session->unset_userdata('ambulance_search');
        $this->session->unset_userdata('amb_particular_billing');
        $data['vehicle_list'] = $this->booking->vehicle_list();
        $data['page_title'] = 'Ambulance Booking List';
        // Default Search Setting
        $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        $this->load->model('general/general_model','general_model');
        $data['location_list']=$this->general_model->location_list();
        $data['payment_mode']=$this->general_model->payment_mode();
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
        $data['form_data'] = array('patient_name'=>'','mobile_no'=>'','booking_code'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'going_date'=>''); 
          $this->load->view('ambulance/booking/list',$data);
  }

    public function ajax_list()
    {   
        unauthorise_permission(349,2086);
        $users_data = $this->session->userdata('auth_users');

        $list = $this->booking->get_datatables();  
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test) 
        {
            $no++;
            $row = array(); 
          //  $prescription_count = $this->booking->get_prescription_count($test->id);
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            
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
            if($users_data['parent_id']==$test->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test->id.'">'; 
             }else{
               $row[]='';
             }
             $row[] = $test->patient_reg_no;
            $row[] = $test->booking_no;
            
            $row[] = $test->patient_name;
            $row[]=$test->vehicle_no;
            $row[]=$test->driver_name;
            $row[] = date('d-m-Y',strtotime($test->booking_date));
            $row[] = date('h:i A',strtotime($test->booking_time));
            $row[] = $test->net_amount;
            $row[] = $test->paid_amount1;//paid_amount; //paid_amount1
            $bal = $test->balance1+$test->refund_amount;
            $row[] =number_format($bal,2,'.', '') ;
            $row[] = $test->refund_amount;
             //Action button /////
            $btn_confirm="";
            $btn_edit = ""; 
            $btn_delete = ""; 
            $btn_prescription = ""; 
            $blank_btn_print =''; 
             $btn_balance_clear = ""; 
            $btn_refund = "";
              
             if($users_data['parent_id'] == $test->branch_id)
             {
              $print_pdf_url = "'".base_url('ambulance/booking/print_booking_report/'.$test->id.'/'.$test->branch_id)."'";
              $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';
             
             if(in_array('2088',$users_data['permission']['action'])){
                $btn_edit = ' <a class="btn-custom" href="'.base_url("ambulance/booking/edit/".$test->id).'" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>';
                
                $btn_balance_clear = ' <a class="btn-custom" href="'.base_url("balance_clearance").'" title="Balance Clearance"><i class="fa fa-pencil"></i> Balance Clearance</a>'; 
            $btn_refund = ' <a class="btn-custom" href="'.base_url("refund_payment").'" title="Refund Payment"><i class="fa fa-pencil"></i> Refund Payment</a>';
              }
              
              
              if(in_array('2089',$users_data['permission']['action'])){
                    $btn_delete = ' <a class="btn-custom" onClick="return delete_ambulance_booking('.$test->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
               }
              }
        
       
            // End Action Button //
            $row[] = $btn_confirm.$btn_edit.$btn_delete.$btn_print.$print_mlc.$btn_a.$btn_balance_clear.$btn_refund;    
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->booking->count_all(),
                        "recordsFiltered" => $this->booking->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
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

      $get_by_id_data = $this->opd->get_all_detail_print($id,$branch_id);
      // print_r($get_by_id_data);die;
      $template_format = $this->opd->template_format(array('section_id'=>1,'types'=>1),$branch_id);
      //print_r($get_by_id_data); exit;
      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
       //print_r($data['all_detail']);die; 
      $get_refund = $this->opd->get_payment_refund($id,$branch_id);
      $refund_payment = 0;
      if(!empty($get_refund[0]->refund_payment))
      {
        $refund_payment = $get_refund[0]->refund_payment;
      }
      $data['refund_payment'] = $refund_payment;
      
        $this->load->view('opd/print_template_opd',$data);
      
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
        unauthorise_permission(349,2089);
       if(!empty($id) && $id>0)
       {
           $result = $this->booking->delete_booking($id);
           $response = "Ambulance Booking successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(349,2089);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->booking->deleteall($post['row_id']);
            $response = "Ambulance Booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(85,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->opd->get_by_id($id);  
        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('opd/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(85,526);
        $data['page_title'] = 'OPD Booking Archive List';
        $this->load->helper('url');
        $this->load->view('opd/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(85,526);
        //$this->session->unset_userdata('referral_doctor_id');
        $this->load->model('opd/opd_archive_model','opd_archive'); 
            $users_data = $this->session->userdata('auth_users');
        $list = $this->opd_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $opd) { 
            $no++;
            $row = array();
            if($opd->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($opd->state))
            {
                $state = " ( ".ucfirst(strtolower($opd->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

           
            } 

            if($opd->booking_status==0)
            {
                $booking_status = '<font color="red">Pending</font>';
            }   
            elseif($opd->booking_status==1){
                $booking_status = '<font color="green">Confirm</font>';
            }                 
            elseif($opd->booking_status==2){
                $booking_status = '<font color="blue">Attended</font>';
            } 
            if($opd->type==0)
            {
                $type = 'Booking';
            }   
            elseif($opd->type==1){
                $type = 'Billing';
            }

            $attended_doctor_name ="";
            $attended_doctor_name = get_doctor_name($opd->attended_doctor);

            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$opd->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$opd->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $opd->booking_code;
            // $row[] = $type;
            $row[] = $opd->patient_name;
            $row[] = $attended_doctor_name;
            if($opd->confirm_date =='0000-00-00')
            {
                $row[] =''; 
            }
            else
            {
                $row[] = date('d M Y',strtotime($opd->confirm_date));   
            }
            
            $row[] = date('d M Y',strtotime($opd->booking_date));
            //$row[] = $test->total_amount;
            $row[] = $booking_status;
            //$row[] = date('d-M-Y H:i A',strtotime($opd->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
            if(in_array('527',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_opd('.$opd->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            }
            if(in_array('528',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$opd->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->opd_archive->count_all(),
                        "recordsFiltered" => $this->opd_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore_opd($id="")
    {
        unauthorise_permission(85,527);
        $this->load->model('opd/opd_archive_model','opd_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->opd_archive->restore($id);
           $response = "OPD Booking successfully restore in OPD Booking list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(85,527);
        $this->load->model('opd/opd_archive_model','opd_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->opd_archive->restoreall($post['row_id']);
            $response = "OPD successfully restore in opd list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(85,528);
        $this->load->model('opd/opd_archive_model','opd_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->opd_archive->trash($id);
           $response = "OPD successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(85,528);
        $this->load->model('opd/opd_archive_model','opd_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->opd_archive->trashall($post['row_id']);
            $response = "OPD successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function opd_dropdown()
  {
      $opd_list = $this->opd->employee_type_list();
      $dropdown = '<option value="">Select OPD</option>'; 
      if(!empty($opd_list))
      {
        foreach($opd_list as $opd)
        {
           $dropdown .= '<option value="'.$opd->id.'">'.$opd->doctor_name.'</option>';
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




  public function add($pid="")
  {  
      $users_data = $this->session->userdata('auth_users');
    //  print_r($users_data);die;
      unauthorise_permission(349,2087);
      $this->load->model('general/general_model'); 
      $post = $this->input->post();
     // print_r($post);die;
      $data['owner_list']=$this->general_model->amb_vendor_list();
       $data['field_list'] = mandatory_section_field_list(9);
        $data['location_list'] = $this->general_model->location_list();
        $data['vehicle_types'] =  $this->booking->vehicle_type_list();
      // print_r($data['field_list']);
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
 
      $city_id = "";
      $state_id = "";
      $country_id = "99"; 
      $patient_email ="";
     
      $booking_time ="";
   
      $remarks='';
      $ref_by_other ="";
      $payment_mode="";
      
      $branch_id =$users_data['parent_id'];
      $type=0;
      $consultants_charge="";
      $referred_by='';
      $relation_name='';
      $relation_type='';
      $relation_simulation_id='';
      $adhar_no='';
      $dob='';
        if($pid>0)
        {
           $this->load->model('patient/patient_model');
           $this->load->model('opd/opd_model','opd');
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
                $city_id = $patient_data['city_id'];
                $state_id = $patient_data['state_id'];
                $country_id = $patient_data['country_id'];
                $patient_email = $patient_data['patient_email'];    
                $relation_name=$patient_data['relation_name'];
                $relation_type=$patient_data['relation_type'];
                $relation_simulation_id=$patient_data['relation_simulation_id'];
                
                $address = $patient_data['address'];
                $address_second = $patient_data['address2'];
                $address_third = $patient_data['address3'];
                $city_id = $patient_data['city_id'];
                $state_id = $patient_data['state_id'];
                $country_id = $patient_data['country_id'];
                $patient_email = $patient_data['patient_email'];
                 $dob=date('d-m-Y',strtotime($patient_data['dob']));
                 $adhar_no=$patient_data['adhar_no']; 
                

           }
        }
        else if(isset($_GET['lid']) && !empty($_GET['lid']) && $_GET['lid']>0)
        {
          $this->load->model('opd/opd_model');
          $lead_data = $this->opd_model->crm_get_by_id($_GET['lid']);
          //echo '<pre>'; print_r($lead_data);die;
          $patient_name = $lead_data['name'];
          $email = $lead_data['email'];
          $mobile_no = $lead_data['phone']; 
          $operation_name =  $lead_data['ot_id']; 
          $data['lead_ot_id'] = $lead_data['ot_id']; 
          $gender=$lead_data['gender'];
          $age_m = $lead_data['age_m'];
          $age_y = $lead_data['age_y'];
          $age_d = $lead_data['age_d'];
          $address = $lead_data['address'];
          $address_second = $lead_data['address2'];
          $address_third = $lead_data['address3'];
        }
        $data['country_list'] = $this->general_model->country_list();
        $data['particulars_list'] = $this->general_model->amb_particulars_list();	
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
          $data['referal_doctor_list'] = $this->booking->referal_doctor_list($post['branch_id']);
        }
        else
        {
          $data['referal_doctor_list'] = $this->booking->referal_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['referal_hospital_list'] = $this->booking->referal_hospital_list($post['branch_id']);
        }
        else
        {
          $data['referal_hospital_list'] = $this->booking->referal_hospital_list();
        }


        if(!empty($post['branch_id']))
        {
          $data['employee_list'] = $this->booking->employee_list($post['branch_id']);
        }
        else
        {
          $data['employee_list'] = $this->booking->employee_list();
        }

           if(!empty($post['branch_id']))
        {
          $data['vehicle_list'] = $this->booking->vehicle_list($post['branch_id']);

        }
        else
        {
          $data['vehicle_list'] = $this->booking->vehicle_list();

        }
           if(!empty($post['branch_id']))
        {
          $data['driver_list'] = $this->booking->driver_list($post['branch_id']);

        }
        else
        {
          $data['driver_list'] = $this->booking->driver_list();

        }
        
         if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }

        $booking_code = generate_unique_id(53);
        $booking_codess = $booking_code;

        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
      /*  $data['doctor_available_time'] = $this->general_model->doctor_time($attended_doctor);
        $data['doctor_available_slot'] = $this->get_doctor_slot($attended_doctor,$available_time,$doctor_slot,$appointment_date);*/
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['page_title'] = "Ambulance Booking"; 
        
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
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,   
                                  'referral_doctor'=>"",
                                  'staff_refrenace_id'=>"",
                                  'booking_date'=>date('d-m-Y'),
                                  'going_date'=>date('d-m-Y'),
                                  'total_amount'=>"0.00",
                                  'net_amount'=>"0.00",
                                  'paid_amount'=>"0.00",
                                  'total_paid_amount'=>"0:00",
                                  'total_amount'=>"0.00",
                                  'discount'=>"0.00",
                                  'balance'=>"0.00",
                                  'pay_now'=>'',
                                  'type'=>$type,
                                  'booking_time'=>date('H:i:s'),
                                  'going_time'=>date('H:i:s'),
                                  'remark'=>'',
                                  'ref_by_other'=>$ref_by_other,
                                  'payment_mode'=>"cash",
                                  'branch_id'=>$branch_id,
                                  "country_code"=>"+91",
                                  'kit_amount'=>'0.00',
                                  'consultants_charge'=>'0.00',
                                  'next_app_date'=>'',
                                  "field_name"=>'',
                                  'source_from'=>'',
                                  'distance_type'=>'',
                                  'distance'=>'',
                                  'vendor_charge'=>'',
                                  'refund_amount'=>'',
                                  'ven_charge_type'=>'',
                                  'location'=>"",
                                  'referred_by'=>'',
                                  'referral_hospital'=>"",
                                  'destination'=>"",
                                  'vendor_id'=>"",
                                  'owner_type'=>'',
                                  "relation_name"=>$relation_name,
                                  "relation_type"=>$relation_type,
                                  "relation_simulation_id"=>$relation_simulation_id,
                                  'hospital_id'=>"",
                                  'refund_amount_data'=>'',
                                  'address'=>$address,
                                  'address_second'=>$address_second,
                                  'address_third'=>$address_third,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'dob'=>$dob,
                                  'adhar_no'=>$adhar_no_new,
                                  );    

        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validateform();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->booking->save_booking();
                $this->session->set_userdata('ambulance_booking_id',$booking_id);
              if(!empty($booking_id))
              {
                  $get_by_id_data = $this->booking->get_by_id($booking_id);
                 // print_r($get_by_id_data);die;
                  if(!empty($get_by_id_data['doctor_name']))
                  {
                  $doctor_name=$get_by_id_data['doctor_name'];
                  }
                  $patient_name = $get_by_id_data['patient_name'];
                  $booking_code = $get_by_id_data['booking_code'];
                  $paid_amount = $get_by_id_data['paid_amount'];
                  $mobile_no = $get_by_id_data['mobile_no'];
                  $doc_mob_no = $get_by_id_data['doc_mob_no'];
                  $patient_email = $get_by_id_data['patient_email'];
                  $patient_id = $get_by_id_data['patient_id'];
                  
                  //$paid_amount = $get_by_id_data['paid_amount'];
                  $balance = $get_by_id_data['balance'];
                
                    
                      $vehicletype = $get_by_id_data['vehicletype'];
                   $going_time = date('H:i:s',strtotime($get_by_id_data['going_time']));
                   $going_date = date('d-m-Y',strtotime($get_by_id_data['going_date']));
                   
                 $dri_result = $this->booking->get_driver_vehicle_by_id($booking_id);
                //check permission
                if(in_array('640',$users_data['permission']['action']))
                {
                    if(!empty($mobile_no))
                    {
                      send_sms('ambulance_driver',32,$dri_result['driver_name'],$dri_result['mobile_no'],array('{driver_name}'=>$dri_result['driver_name'],'{patient_name}'=>$patient_name,'{mobile_no}'=>$mobile_no,'{booking_no}'=>$booking_codess,'{pickup}'=>$get_by_id_data['source'],'{drop}'=>$get_by_id_data['destination'],'{balance}'=>$get_by_id_data['balance'],'{paid_amount}'=>$get_by_id_data['paid_amount'],'{going_date}'=>$going_date,'{going_time}'=>$going_time));   
                     
                      send_sms('ambulance_patient',33,$patient_name,$mobile_no,array('{driver_name}'=>$dri_result['driver_name'],'{mobile_no}'=>$dri_result['mobile_no'],'{patient_name}'=>$patient_name,'{booking_no}'=>$booking_codess,'{vehicle_no}'=>$dri_result['vehicle_no'],'{balance}'=>$get_by_id_data['balance'],'{paid_amount}'=>$get_by_id_data['paid_amount'],'{going_date}'=>$going_date,'{going_time}'=>$going_time,'{vehicle_type}'=>$vehicletype)); 
                   
                      send_sms('ambulance_doctor',34,$doctor_name,$doc_mob_no,array('{doctor_name}'=>$doctor_name,'{patient_name}'=>$patient_name,'{mobile_no}'=>$mobile_no,'{booking_no}'=>$booking_codess,'{pickup}'=>$get_by_id_data['source'],'{drop}'=>$get_by_id_data['destination'],'{balance}'=>$get_by_id_data['balance'],'{paid_amount}'=>$get_by_id_data['paid_amount'],'{going_date}'=>$going_date,'{going_time}'=>$going_time)); 
                     
                    }
                    
                }

                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($patient_email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($dri_result['email'],'','','','','1','ambulance_driver','32',array('{driver_name}'=>$dri_result['driver_name'],'{patient_name}'=>$patient_name, '{mobile_no}'=>$mobile_no,'{booking_no}'=>$booking_codess,'{pickup}'=>$get_by_id_data['source'],
                      '{drop}'=>$get_by_id_data['destination']));
                     $this->general_functions->email($patient_email,'','','','','1','ambulance_patient','33',array('{driver_name}'=>$dri_result['driver_name'],'{mobile_no}'=>$dri_result['mobile_no'],'{patient_name}'=>$patient_name,'{booking_no}'=>$booking_codess,'{vehicle_no}'=>$dri_result['vehicle_no']));
                     
                  }
                } 
              $this->load->model('general/general_model');  
              //push notification to users id 
              $usersID = $this->general_model->get_users_details($patient_id);
              //die;
              
              //$usersID = $user_details->id;
              $receiver_device_details= $this->general_model->get_device_detail($usersID);
              //echo "<pre>";print_r($receiver_device_details); exit;
              if(!empty($receiver_device_details))
              {
                require APPPATH . '/libraries/PushNotification.php';
                $sms_msg=rawurlencode('Ambulance booking successfully done.');
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

              }

                //exit;
                $this->session->set_flashdata('success','Ambulance booking successfully done.');
                $this->session->unset_userdata('amb_particular_billing');
                redirect(base_url('ambulance/booking/add?status=print'));
                
                //redirect(base_url('opd/?status=print'));
      }
            else
            {
                $data['form_error'] = validation_errors(); 
                //print_r($data['form_error']);die;
            }     
        }

    //$this->session->unset_userdata('amb_particular_billing');        
    $data['simulation_array']= $this->general_model->simulation_list();
         $this->load->view('ambulance/booking/add',$data);     
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
    
    public function edit($id="")
    { 
        //refund_amount
       unauthorise_permission(349,2088);
      
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model'); 
        $post = $this->input->post();
        $result = $this->booking->get_by_id($id);
       // echo "<pre>"; print_r($result); exit;
         $data['field_list'] = mandatory_section_field_list(9);
          $data['location_list'] = $this->general_model->location_list();
          $data['vehicle_types'] =  $this->booking->vehicle_type_list();
        $data['simulation_array']= $this->general_model->simulation_list();
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->booking->referal_doctor_list();
        //print_r($data['referal_doctor_list']);die;
        $data['attended_doctor_list'] = $this->booking->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();  
        $data['package_list'] = $this->general_model->package_list();
        $data['diseases_list'] = $this->booking->diseases_list();
        $data['source_list'] = $this->booking->source_list();
        $data['referal_hospital_list'] = $this->booking->referal_hospital_list();
        //$data['insurance_type_list'] = $this->general_model->insurance_type_list(); 
        //$data['insurance_company_list'] = $this->general_model->insurance_company_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        
        $total_paid_amount_s = $this->booking->get_all_payment($id);
        $total_paid_amount = $total_paid_amount_s['payment_amount'];
        $data['page_title'] = "Update Ambulance Booking";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        
        $data['owner_list']=$this->general_model->amb_vendor_list();
        	
        $data['particulars_list'] = $this->general_model->amb_particulars_list();	
        
        
        $checkdate = date('d-m-Y',strtotime($result['cheque_date']));
        if(!empty($checkdate) && $checkdate!='00-00-0000' && $checkdate!='01-01-1970')
        {
          $checkdates= $checkdate;
        }
        else
        {
          $checkdates = ""; 
        }
        
        $this->load->model('general/general_model');
        $data['payment_mode']=$this->general_model->payment_mode();
        $get_payment_detail= $this->booking->payment_mode_detail_according_to_field($result['payment_mode'],$id);
        $total_values=array();

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


      if(($result['dob']=='1970-01-01') || ($result['dob']=="0000-00-00"))
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


         if(!empty($post['branch_id']))
        {
          $data['vehicle_list'] = $this->booking->vehicle_list($post['branch_id']);

        }
        else
        {
          $data['vehicle_list'] = $this->booking->vehicle_list();

        }
           if(!empty($post['branch_id']))
        {
          $data['driver_list'] = $this->booking->driver_list($post['branch_id']);

        }
        else
        {
          $data['driver_list'] = $this->booking->driver_list();

        }

         if(!empty($post['branch_id']))
        {
          $data['employee_list'] = $this->booking->employee_list($post['branch_id']);
        }
        else
        {
          $data['employee_list'] = $this->booking->employee_list();
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
          $data['referal_doctor_list'] = $this->booking->referal_doctor_list($post['branch_id']);
        }
        else
        {
          $data['referal_doctor_list'] = $this->booking->referal_doctor_list();
        }

        if(!empty($post['branch_id']))
        {
          $data['referal_hospital_list'] = $this->booking->referal_hospital_list($post['branch_id']);
        }
        else
        {
          $data['referal_hospital_list'] = $this->booking->referal_hospital_list();
        }
        
        if($total_paid_amount>$result['net_amount'])
        {
              $totbalance = ($total_paid_amount-$result['net_amount'])-$result['refund_amount'];
        }
        else
        {
            $totbalance = ($result['net_amount']-$total_paid_amount)+$result['refund_amount'];
        }
        
      
                $address = $result['address'];
                $address_second = $result['address2'];
                $address_third = $result['address3'];
                $city_id = $result['city_id'];
                $state_id = $result['state_id'];
                $country_id = $result['country_id'];
                $patient_email = $result['patient_email'];
                $dob = date('d-m-Y',strtotime($result['dob']));
                $adhar_nos = $result['adhar_no'];
//echo "<pre>";print_r($result); die; 

                if($adhar_nos=='0')
                {
                  $adhar_no_ed='';  
                }
                else
                {
                    $adhar_no_ed = $adhar_nos;
                }
                
              $data['form_data'] = array(
                                    'data_id'=>$result['id'], 
                                  'patient_id'=>$result['patient_id'],
                                  'patient_code'=>$result['patient_code'],
                                  'booking_code'=>$result['booking_no'],
                                  'simulation_id'=>$result['simulation_id'],
                                  'patient_name'=>$result['patient_name'],
                                   "relation_name"=>$result['relation_name'],
                                    "relation_type"=>$result['relation_type'],
                                    "relation_simulation_id"=>$result['relation_simulation_id'],
                                  'mobile_no'=>$result['mobile_no'],
                                  'gender'=>$result['gender'],
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'dob'=>$dob,
                                  'patient_email'=>$patient_email,
                                  'guardian_name'=>$result['guardian_name'],
                                  'guardian_phone'=>$result['guardian_phone'],
                                  'relation_type'=>$result['relation_id'],
                                  //'address'=>$result['address'], 
                                  'city_id'=>$result['city_id'],
                                  'state_id'=>$result['state_id'],
                                  'country_id'=>$result['country_id'],   
                                  'referral_doctor'=>$result['reffered'],
                                  'referral_hospital'=>$result['referral_hospital'],
                                  'hospital_staff'=>$result['staff_id'],
                                  'vehicle_no'=>$result['vehicle_no'],
                                  'vehicle_type'=>$result['vehicle_type'],
                                  'driver'=>$result['driver_id'],
                                  'booking_date'=>date('d-m-Y',strtotime($result['booking_date'])),
                                  'going_date'=>date('d-m-Y',strtotime($result['going_date'])),
                                  'total_amount'=>$result['net_amount'],
                                  'net_amount'=>$result['net_amount'],
                                  'paid_amount'=>$result['paid_amount'],
                                  'total_paid_amount'=>$total_paid_amount, //$result['first_paid_amount'],
                                  'total_amount'=>$result['total_amount'],
                                  'discount'=>$result['discount'],
                                  'balance'=>$totbalance,//$result['balance'],
                                  'pay_now'=>'',
                                  'type'=>$type,
                                  'booking_time'=>date('H:i:s',strtotime($result['booking_time'])),
                                  'going_time'=>date('H:i:s',strtotime($result['going_time'])),
                                  'remark'=>$result['remark'],
                                 // 'ref_by_other'=>$ref_by_other,
                                  'payment_mode'=>$result['payment_mode'],
                                  'branch_id'=>$branch_id,
                                  "country_code"=>"+91",
                                  'consultants_charge'=>$result['charge'],
                                  "field_name"=>$result['field_name'],
                                  'source_from'=>$result['source'],
                                   'distance_type'=>$result['distance_type'],
                                  'distance'=>$result['distance'],
                                  'location'=>$result['location'],
                                  'referred_by'=>$result['reffered_by'],
                                  'vendor_id'=>$result['vendor_id'],
                                  'owner_type'=>$result['owner_type'],
                                  'guardian_relation_type'=>$result['guardian_relation_type'],
                                  'destination'=>$result['destination'],
                                  'vendor_charge'=>$result['vendor_charge'],
                                  'refund_amount'=>$result['refund_amount'],
                                  'refund_amount_data'=>$result['refund_amount'],
                                  'ven_charge_type'=>$result['ven_charge_type'],
                                   'hospital_id'=>$result['hospital_id'],
                                    'address'=>$address,
                                  'address_second'=>$address_second,
                                  'address_third'=>$address_third,
                                  'city_id'=>$city_id,
                                  'state_id'=>$state_id,
                                  'country_id'=>$country_id,
                                  'dob'=>$dob,
                                  'adhar_no'=>$adhar_no_ed,
                                 
                                    );  
                                    
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validateform();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->booking->save_booking();
                $this->session->set_userdata('ambulance_booking_id',$booking_id);
                $this->session->set_flashdata('success','Ambulance booking successfully booked.');
                redirect(base_url('ambulance/booking/add?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        
        
        $billed_list = $this->booking->get_booked_particular_list($id);  	
       // $this->session->unset_userdata('amb_particular_billing');
       //print_r($billed_list);exit;
       	
		$particular_list = array();
		if(!empty($billed_list))
		{ 
			$i=0;
			foreach($billed_list as $particular)
			{
               $particular_list[$i]['particular'] = $particular['particular'];
               $particular_list[$i]['particulars'] = $particular['particulars'];
               $particular_list[$i]['amount'] = $particular['amount'];
                $particular_list[$i]['normal_amount'] = $particular['amount'];
               $particular_list[$i]['quantity'] = $particular['quantity'];
              
			$i++;
			}
		}
	
	    if(!empty($particular_list))	
        {  	
            $this->session->set_userdata('amb_particular_billing',$particular_list);	
        }
       $this->load->view('ambulance/booking/edit',$data);       
      }
    }

    private function _validateform()
    {
        $post = $this->input->post(); 
        $field_list = mandatory_section_field_list(9);
       // print_r($field_list);die;
     // echo "<pre>"; print_r($post);die;
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
        /*5-06-2020*/
          $this->form_validation->set_rules('booking_date', 'booking date', 'trim|required'); 
          $this->form_validation->set_rules('booking_time', 'booking time', 'trim|required'); 
          $this->form_validation->set_rules('going_date', 'going date', 'trim|required'); 
          $this->form_validation->set_rules('going_time', 'going time', 'trim|required'); 
          //$this->form_validation->set_rules('hospital_id', 'hospital id', 'trim|required'); 
        /*5-06-2020*/
      
       if(isset($post['field_name'])) 
        {
        $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
        
        if(!empty($field_list)){ 
            
             if($field_list[4]['mandatory_field_id']=='86' && $field_list[4]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('remark', 'Remark', 'trim|required'); 
            }
            
            if($field_list[3]['mandatory_field_id']=='50' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'trim|required'); 
            }
               if($field_list[5]['mandatory_field_id']=='87' && $field_list[5]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('relation_name', 'Relation Name.', 'trim|required'); 
            }
           
           
            if($field_list[1]['mandatory_field_id']=='44' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                
                
                 if(($post['age_y']=='0' && $post['age_m']=='0' && $post['age_d']=='0') || ((empty($post['age_y']) && empty($post['age_m']) && empty($post['age_d']))) )
                    {
                         
                        $this->form_validation->set_rules('age_y', 'age', 'trim|required');
                    }
            }
        }
 
              //  $this->form_validation->set_rules('address', 'Address', 'trim|required'); 
             //   $this->form_validation->set_rules('source_from', 'Source', 'trim|required'); 
           

                $this->form_validation->set_rules('vehicle_no', 'Vehicle no', 'trim|required');
                $this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'trim|required');

                $this->form_validation->set_rules('driver_name', 'Driver name', 'trim|required');
                $this->form_validation->set_rules('destination', 'Destination', 'trim|required');
                 $this->form_validation->set_rules('distance', 'Distance', 'trim|required');
                 $this->form_validation->set_rules('location', 'Location', 'trim|required');
      
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            if(!empty($post['pay_now']))
            {
              $pay_now = 1;
            }
            else
            {
              $pay_now = 0;
            }
          
            $data['form_data'] = array(
                                        'branch_id'=>$post['branch_id'],
                                        'data_id'=>$post['data_id'],
                                        'adhar_no'=>'',
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
                                        'dob'=>'',
                                        'address'=>$post['address'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'attended_doctor'=>'',
                                        'ref_by_other'=>'',
                                        'booking_time'=>$post['booking_time'],
                                        'booking_date'=>$post['booking_date'],
                                        'going_time'=>$post['going_time'],
                                        'going_date'=>$post['going_date'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$post['balance'],
                                        'type'=>$post['type'],
                                        'patient_email'=>'',
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'ref_by_other'=>'',
                                        'booking_time'=>$post['booking_time'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$post['balance'],
                                        "field_name"=>$total_values,
                                        'pay_now'=>$pay_now,
                                        'payment_mode'=>$post['payment_mode'],
                                        'vehicle_no'=>$post['vehicle_no'],
                                        'vehicle_type'=>$post['vehicle_type'],
                                        'driver'=>$post['driver_name'],
                                        'location'=>$post['location'],
                                        'hospital_staff'=>$post['hospital_staff'],
                                        'destination'=>$post['destination'],
                                        'distance'=>$post['distance'],
                                        "country_code"=>"+91",
                                        'next_app_date'=>'', 
                                        'consultants_charge'=>$post['consultants_charge'],
                                        'source_from'=>$post['source_from'],
                                        'remark'=>$post['remark'],
                                        'referral_hospital'=>$post['referral_hospital'],
                                        'referred_by'=>$post['referred_by'],
                                        'validity_date'=>'',
                                        "relation_name"=>$post['relation_name'],
                                        "relation_type"=>$post['relation_type'],
                                        "relation_simulation_id"=>$post['relation_simulation_id'],
                                        'owner_type'=>$post['ven_tp'],
                                        'vendor_id'=>$post['vendor_id'],
                                        'vendor_charge'=>$post['vendor_charge'],
                                        'refund_amount'=>$post['refund_amount'],
                                        'ven_charge_type'=>$post['ven_charge_type'],
                                          'hospital_id'=>$post['hospital_id'],
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
         $token_type=$this->booking->get_token_setting();
         $token_type=$token_type['type'];
         //echo $type;die;
          if($token_type=='0') //hospital wise token no
          { 
            
           $patient_token_details_for_hospital=$this->booking->get_patient_token_details_for_type_hospital($booking_date,$token_type);
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
            $patient_token_details_for_doctor=$this->booking->get_patient_token_details_for_type_doctor($doctor_id,$booking_date,$token_type);
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
            $patient_token_details_for_specialization=$this->booking->get_patient_token_details_for_type_specialization($specilization_id,$booking_date,$token_type);
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

    
    
    //This is for update 
    
    public function calculate_payment_on_edit()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
         
           $total_amount = $post['total_amount'];
           $total_paid_amount = $post['total_paid_amount'];
           $consultants_charge = $post['consultants_charge'];
           //$kit_amount = $post['kit_amount'];

           $discount = $post['discount'];
           $refund_amount = $post['refund_amount'];
           $net_amount = $total_amount-$discount;
           
           $initial_paid_amount = $post['initial_paid_amount'];
           /*
           if($post['balance']>$discount)
           {
            $balance = $post['balance']-$discount;
           }
           
           if($post['balance']<$discount)
           {
            $refund_amount = $discount-$post['balance'];
           }*/
           
           $paid_amount = $post['paid_amount']-$discount;
           //$total_paid_amount = $post['total_paid_amount']-$discount;
           
           //15july 2020
           //$paid_amount = $net_amount;
           
            /*if($net_amount>$total_paid_amount)
            {
                $paid_amount = $net_amount-$total_paid_amount; //
            }
            if($net_amount<$total_paid_amount)
            {
                
                $paid_amount = $total_paid_amount-$net_amount; //;
                
            }*/
                
           
           
           
           /*
           //comment on 15 july 2020
           if(!empty($total_paid_amount) && $total_paid_amount!='undefined')
           {
           
            $balance = '0.00';
            if($total_paid_amount<$net_amount)
            {
                $balance = $net_amount-$total_paid_amount;
                if(!empty($discount) && $discount<$balance)
                {
                    $balance = ($balance)+$refund_amount; 
                }
                
            }
           }
           else
           {
             $balance = '0.00';  
            
           }*/
           $balance = $net_amount-$total_paid_amount;
           $balance = ($balance)+$refund_amount;
           
           
           
           
           $pay_arr = array('consultants_charge'=>number_format($consultants_charge,2,'.', ''),'kit_amount'=>number_format($kit_amount,2,'.', ''),'total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''), 'balance'=>number_format($balance,2,'.', ''), 'paid_amount'=>number_format($paid_amount,2,'.', ''),'refund_amount'=>number_format($refund_amount,2,'.',''));
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
    }
    
    //this is for addd
    public function calculate_payment()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
         
           $total_amount = $post['total_amount'];
           $total_paid_amount = $post['total_paid_amount'];
           $consultants_charge = $post['consultants_charge'];
           //$kit_amount = $post['kit_amount'];

           $discount = $post['discount'];
           $refund_amount = $post['refund_amount'];
           $net_amount = $total_amount-$discount;
           
           $initial_paid_amount = $post['initial_paid_amount'];
           /*
           if($post['balance']>$discount)
           {
            $balance = $post['balance']-$discount;
           }
           
           if($post['balance']<$discount)
           {
            $refund_amount = $discount-$post['balance'];
           }*/
           
           $paid_amount = $post['paid_amount']-$discount;
           //$total_paid_amount = $post['total_paid_amount']-$discount;
           
           //15july 2020
           //$paid_amount = $net_amount;
           
            /*if($net_amount>$total_paid_amount)
            {
                $paid_amount = $net_amount-$total_paid_amount; //
            }
            if($net_amount<$total_paid_amount)
            {
                
                $paid_amount = $total_paid_amount-$net_amount; //;
                
            }*/
                
           
           
           
           if(!empty($total_paid_amount) && $total_paid_amount!='undefined')
           {
           /*if($total_paid_amount>$net_amount)
            {
                $refund_amount = $total_paid_amount-$net_amount;
            }*/
            
            $balance = '0.00';
            if($total_paid_amount<$net_amount)
            {
                $balance = $net_amount-$total_paid_amount;
                if(!empty($discount) && $discount<$balance)
                {
                    $balance = ($balance)+$refund_amount; //$discount
                }
                /*else if(!empty($discount) && $discount>$balance)
                {
                   $refund_amount =  $refund_amount+$discount;
                }*/
            }
           }
           else
           {
             $balance = '0.00';  
             //$refund_amount='0.00';
           }
           
           
           
           
           $pay_arr = array('consultants_charge'=>number_format($consultants_charge,2,'.', ''),'kit_amount'=>number_format($kit_amount,2,'.', ''),'total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''), 'balance'=>number_format($balance,2,'.', ''), 'paid_amount'=>number_format($paid_amount,2,'.', ''),'refund_amount'=>number_format($refund_amount,2,'.',''));
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
           $particulars_charges = $post['particulars_charges'];	
           $discount = $post['discount'];	
           $total_amount=$consultants_charge+$particulars_charges;	
           $net_amount = $total_amount-$discount;	
           $paid_amount = $net_amount;	
          // $balance=$consultants_charge-$net_amount;	
           $pay_arr = array('consultants_charge'=>number_format($consultants_charge,2,'.', ''),'particulars_charges'=>number_format($particulars_charges,2,'.', ''),'total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''));	
           $json = json_encode($pay_arr,true);	
           echo $json;	
         	
       }	
    }

    public function get_charge()
    {
       $post = $this->input->post();
      // print_r($post);die;
       if(isset($post) && !empty($post))
       {
             $vehicle_id = $post['vehicle_id'];

           $charge=$this->booking->get_vehicle_charge($vehicle_id);
           $booking_amount=$this->booking->get_booking_amount($post['id']);
           $consultants_charge = $charge->local_min_amount;
           $discount = $post['discount'];
           $total_amount=$consultants_charge;
           $total_amount=$consultants_charge+$post['total_amount'];	
           $net_amount = $total_amount-$discount;	
           $paid_amount = $net_amount;	
           $ref_amt=$booking_amount->first_paid_amount;
        //  print_r($paid_amount);die;
           /*if($ref_amt > $net_amount)
           {
                 $refund_amount=$ref_amt-$net_amount;
           }
          if($ref_amt==$net_amount){
               $refund_amount=0.00;
           }*/
           if(!empty($total_paid_amount))
           {
           if($total_paid_amount>$total_amount)
            {
                $refund_amount = $total_paid_amount-$total_amount;
                 $balance = '0.00';
            }
            
           
            if($total_paid_amount<$total_amount)
            {
                $balance = $total_amount-$total_paid_amount;
                if(!empty($discount) && $discount<$balance)
                {
                    $balance = $balance-$discount;
                    $refund_amount = '0.00';
                }
                
            }
           }
           else
           {
               $refund_amount = '0.00';
               $balance = '0.00';
           }
         
          // $balance=$consultants_charge-$net_amount;	
           $pay_arr = array('consultants_charge'=>number_format($consultants_charge,2,'.', ''),'kit_amount'=>number_format($kit_amount,2,'.', ''),
           'total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''),'refund_amount'=>number_format($refund_amount,2,'.',''),'balance'=>number_format($balance,2,'.',''));
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
    }

    public function get_vendor_charge()
    {
       $post = $this->input->post();
       
       if(isset($post) && !empty($post))
       {
        $vehicle_id = $post['vehicle_id'];

        $charge=$this->booking->get_vendor_charge($vehicle_id);
           $pay_arr = array('vendor_id'=>$charge->vendor_id, 'ven_charge_type'=>$charge->charge_type, 'vendor_charge'=>$charge->charge);
           $json = json_encode($pay_arr,true);
           echo $json;
       }
    }
    

    

    public function confirm_booking($id)
    {
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->booking->get_by_id($id);
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
        
        $rate_data = $this->booking->opd_doctor_rate($result['attended_doctor']);
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
                $this->booking->confirm_booking();
                echo 1;
                return false;
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('opd/confirm',$data);       
      }
    }


    private function _validate()
    {
        $field_list = mandatory_section_field_list(9);
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

          if($field_list[0]['mandatory_field_id']=='43' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
          $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
          }

          if($field_list[1]['mandatory_field_id']=='44' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
          $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
          }
        }
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
        $this->form_validation->set_rules('adhar_no', 'adhar no', 'min_length[12]|max_length[16]'); 
        if($post['payment_mode']=='cheque')
        {
        $this->form_validation->set_rules('branch_name', 'bank name', 'trim|required');
        

        }
        else if($post['payment_mode']=='card' || $post['payment_mode']=='neft')
        {
        $this->form_validation->set_rules('transaction_no', 'transaction no', 'trim|required');
        }
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $balance_amount = $this->booking->check_patient_balance($post['patient_id']); 
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
                                         'destination'=>$post['destination'],
                                        /* Divya */
                                        'distance'=>$post['distance'],
                                        'distance_type'=>$post['distance_type'],
                                        'vehicle_no'=>$post['vehicle_no'],
                                        'vehicle_type'=>$post['vehicle_type'],
                                        'location'=>$post['location'],
                                        /* Divya */
                                        'remark'=>$post['remark'],
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
       
        $opd_particular_billing_list = $this->session->userdata('amb_particular_billing');
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
              $balance_amount = $this->booking->check_patient_balance($post['patient_id']);    
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
       $opd_particular_billing_list = $this->session->userdata('amb_particular_billing');	
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
           $this->load->model('opd/opd_model');
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
        $data['referal_doctor_list'] = $this->booking->referal_doctor_list();
        $data['attended_doctor_list'] = $this->booking->attended_doctor_list();
        $data['employee_list'] = $this->booking->employee_list();
        $data['profile_list'] = $this->booking->profile_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['chief_complaints'] = $this->general_model->chief_complaint_list(); 
        $data['examination_list'] = $this->booking->examinations_list();
        $data['diagnosis_list'] = $this->booking->diagnosis_list();  
        $data['suggetion_list'] = $this->booking->suggetion_list();  
        $data['prv_history'] = $this->booking->prv_history_list();  
        $data['personal_history'] = $this->booking->personal_history_list();
        $data['template_list'] = $this->booking->template_list();    
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

          $prescription_id = $this->booking->save_prescription();
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

         $this->load->view('opd/prescription',$data);
    }

    
    function get_template_data($template_id="")
    {
      if($template_id>0)
      {
        $templatedata = $this->opd->template_data($template_id);
        echo $templatedata;
      }
    }



    function get_template_test_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->opd->get_template_test_data($template_id);
        echo $templatetestdata;
      }
    }

    function get_template_prescription_data($template_id="")
    {
      if($template_id>0)
      {
        $templatetestdata = $this->opd->get_template_prescription_data($template_id);
        
        echo $templatetestdata;
      }
    }
    

   function complaints_name($complaints_id="")
   {
      if($complaints_id>0)
      {
         $complaintsname = $this->opd->compalaints_list($complaints_id);
         echo $complaintsname;
      }
    }

   

    function examination_name($examination_id="")
    {
      if($examination_id>0)
      {
         $examination_name = $this->opd->examination_list($examination_id);
         echo $examination_name;
      }
    }

    function diagnosis_name($diagnosis_id="")
    {
      if($diagnosis_id>0)
      {
         $diagnosis_name = $this->opd->diagnosis_name($diagnosis_id);
         echo $diagnosis_name;
      }
    }
    //hms_opd_suggetion
    function suggetion_name($suggetion_id="")
    {
      if($suggetion_id>0)
      {
         $suggetion_name = $this->opd->suggetion_name($suggetion_id);
         echo $suggetion_name;
      }
    }

    function personal_history_name($personal_his_id="")
    {
      if($personal_his_id>0)
      {
         $personal_his_name = $this->opd->personal_history_name($personal_his_id);
         echo $personal_his_name;
      }
    }

    function prv_history_name($pre_id="")
    {
      if($pre_id>0)
      {
         $pre_name = $this->opd->prv_history_name($pre_id);
         echo $pre_name;
      }
    }


    public function particular_billing_list()
    {
        unauthorise_permission(85,121);
        $data['page_title'] = 'OPD Particular Billing list'; 
        $this->load->view('opd/particular_billing_list',$data);
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
        $data['diseases_list'] = $this->opd->diseases_list();

            $data['simulation_list'] = $this->general_model->simulation_list();
            $data['particulars_list'] = $this->general_model->particulars_list();
            $data['country_list'] = $this->general_model->country_list();
        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
            $data['page_title'] = "OPD Billings";  
        $post = $this->input->post();
        if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }
        
        $booking_code = generate_unique_id(53);
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
                $booking_id = $this->opd->save_booking();
                //$this->session->set_flashdata('success','OPD particular successfully saved.');
                //redirect(base_url('opd'));
                $this->session->set_userdata('opd_booking_id',$booking_id);
                $this->session->set_flashdata('success','OPD Particular successfully saved.');
                redirect(base_url('opd/?status=print&type=1'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('opd/billing',$data); 
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
    
    
    public function particular_payment_calculationnew()
      {
      $billing_particular = $this->session->userdata('opd_particular_billing'); 

      
      $post = $this->input->post();
      $charge='';
      $charge_only='';
      if(isset($post) && !empty($post))
      {   
      $billing_particular = $this->session->userdata('opd_particular_billing'); 
      
         $p_ids_arr=array();
          if(isset($billing_particular))
         {
        foreach($billing_particular as $p_ids)
        {
        	$p_ids_arr[]= $p_ids['particular'];
        }
      }

      if(isset($billing_particular) && !empty($billing_particular))
        {
        
  //print_r($billing_particular[$ses_billing_data['particular']]);die;
          foreach($billing_particular as $ses_billing_data)
            {
            	

                if(!empty($ses_billing_data['particulars_charges']))
                {
                $particulars_charges = number_format($ses_billing_data['particulars_charges'],2,'.','');
                }
              else
                {
                $particulars_charges ='0.00';
                }

            $charge= $ses_billing_data['normal_amount'];
            $normal_amount=$ses_billing_data['normal_amount'];
              if(!empty($ses_billing_data['particular'])) 
              {
                $discount=$post['discount'];
                $billing_particular[$ses_billing_data['particular']] = array('particular'=>$ses_billing_data['particular'], 'quantity'=>$ses_billing_data['quantity'], 'amount'=>$ses_billing_data['normal_amount'],'particulars'=>$ses_billing_data['particulars'],'particulars_charges'=>$particulars_charges,'normal_amount'=>$ses_billing_data['normal_amount']);
             
              $amount_arr = array_column($billing_particular, 'amount'); 
              $total_amount = array_sum($amount_arr);
              $this->session->set_userdata('opd_particular_billing', $billing_particular);
              $html_data = $this->opd_perticuller_list();
              $total = $total_amount;
              if($total-$discount==0)
                {
                  $netamount = 0.00;
                }
              else
                {
                  $netamount = number_format($total-$discount,2,'.','');
                }
              if($discount==0)
                {
                  $discamount ='0.00';
                }
                else
                {
                  $discamount = number_format($discount,2,'.','');
                }
                if($total==0)
                {
                  $totamount = '0.00';
                }
                else
                {
                    $totamount = number_format($total,2,'.','');
                }
              
              }
            }
          
        }
      else
        {
          $billing_particular = [];
        }
      /* for panel rate */
    

      if(!empty($post['particular'])) 
        {
         $charge= $post['amount'];
         
                if(!empty($post['particulars_charges']))
                {
                $particulars_charges = number_format($post['particulars_charges'],2,'.','');
                }
               else
                {
                $particulars_charges ='0.00';
                }

          $billing_particular[$post['particular']] = array('particular'=>$post['particular'], 'quantity'=>$post['quantity'], 'amount'=>$charge,'particulars'=>$post['particulars'],'particulars_charges'=>$particulars_charges,'normal_amount'=>$post['amount']);
        $amount_arr = array_column($billing_particular, 'amount'); 
        $total_amount = array_sum($amount_arr);
        $this->session->set_userdata('opd_particular_billing', $billing_particular);

        $html_data = $this->opd_perticuller_list();
        $total = $total_amount;
        if($total-$post['discount']==0)
          {
            $netamount = 0.00;
          }
        else
          {
            $netamount = number_format($total-$post['discount'],2,'.','');
          }
        if($post['discount']==0)
          {
            $discamount ='0.00';
          }
        else
          {
            $discamount = number_format($post['discount'],2,'.','');
          }
        if($total==0)
          {
            $totamount = '0.00';
          }
        else
          {
            $totamount = number_format($total,2,'.','');
          }
        
        }
        
        
            $paid_amount = $post['paid_amount'];
            
            $refund_ini = $post['refund_amount'];
            $total_paid_amount = $post['total_paid_amount'];
             $refund_amount = $refund_ini;
            //die;
            /*
            15-07-2020
            echo $refund_amount = $refund_ini;
            die;
            */
            if(!empty($total_paid_amount))
            {
            
            if($total_paid_amount>$total_amount)
            {
                $refund_amount = ($total_paid_amount-$total_amount)+$refund_ini;
            }
            
            
            $balance = '0.00';
            if($total_paid_amount<$total_amount)
            {
                $balance = ($total_amount-$total_paid_amount)+$refund_ini;
                if(!empty($discamount) && $discamount<$balance)
                {
                    $balance = $balance-$discamount;
                }
                else if(!empty($discamount) && $discamount>$balance)
                {
                   $refund_amount =  $refund_amount+$discamount;
                }
            }
            }
            else
            {
               $balance = $refund_ini; 
                $refund_amount = $refund_ini;
            }
        $response_data = array('html_data'=>$html_data, 'total_amount'=>$totamount, 'net_amount'=>$netamount, 'discount'=>$discamount,'particulars_charges'=>number_format($total_amount,2,'.',''),'balance'=>number_format($balance,2,'.',''),'refund_amount'=>number_format($refund_amount,2,'.',''));
        $opd_particular_payment_array = array('total_amount'=>$totamount, 'net_amount'=>$netamount, 'discount'=>$discamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
        $this->session->set_userdata('opd_particular_payment', $opd_particular_payment_array);
        $json = json_encode($response_data,true);
        echo $json; 
      }
      }


    public function particular_payment_calculation()
    {	
       $post = $this->input->post();	

       if(isset($post) && !empty($post))	
       {   	
            $billing_particular = $this->session->userdata('amb_particular_billing'); 	
            if(isset($billing_particular) && !empty($billing_particular))	
            {	
                $billing_particular = $billing_particular; 	
            }	
            else	
            {	
                $billing_particular = [];	
            }	
             $particulars_charges=0;	
            if(!empty($post['amount']))	
            {	
            $particulars_charges = number_format($post['particulars_charges']+$post['amount'],2,'.',''); 	
            }	
            $billing_particular[$post['particular']] = array('particular'=>$post['particular'], 'quantity'=>$post['quantity'], 'amount'=>$post['amount'],'particulars'=>$post['particulars']);	
            $amount_arr = array_column($billing_particular, 'amount'); 	
            $total_amount = array_sum($amount_arr);	
            	
            $total_amount = $post['amount']+$post['total_amount'];	
            $net_amt = $total_amount-$post['discount'];
            $net_amount = $net_amt;
            //$net_amount=$post['total_amount']-$deleted_total_amount-$post['discount'];	
            $this->session->set_userdata('amb_particular_billing', $billing_particular);
            $this->session->userdata('amb_particular_billing');
            
            $refund_init = $post['refund_amount'];
            $refund_amount = $refund_init;
           // $total_paid_amount = $post['total_paid_amount'];
            
            //echo $total_paid_amount = $post['total_paid_amount']; die;
            /*if(!empty($total_paid_amount) && $total_paid_amount!='undefined')
            {
                if($total_paid_amount>$total_amount)
                {
                    $refund_amount = $total_paid_amount-$total_amount;
                }
                
                $balance = '0.00';
                if($total_paid_amount<$total_amount)
                {
                    $balance = $total_amount-$total_paid_amount;
                    if(!empty($discamount) && $discamount<$balance)
                    {
                        $balance = $balance-$discamount;
                    }
                    else if(!empty($discamount) && $discamount>$balance)
                    {
                       $refund_amount =  $refund_amount+$discamount;
                    }
                }
            }
            else
            {
               $balance = '0.00'; 
                $refund_amount = '0.00';
            }*/
            //echo $total_paid_amount;
           //echo $net_amount; die; 
           $total_paid_amount = $post['total_paid_amount']; 
        if(!empty($total_paid_amount) && $total_paid_amount!='undefined')
        {
            if($total_paid_amount>$total_amount)
            {
                $refund_amount = ($total_paid_amount-$total_amount)+$refund_init;
            }
            
            /*$balance = '0.00';
            if($total_paid_amount<$total_amount)
            {
                $balance = $total_amount-$total_paid_amount;
            }*/
            
            //$balance = '0.00';
            
            if($total_paid_amount<$net_amount)
            {
                 $balance = ($net_amount-$total_paid_amount)+$refund_init; //die;
                //if(!empty($discamount) && $discamount<$balance)
                //{
                    if(!empty($discamount))
                    {
                    $balance = $balance-$discamount;
                    }
                //}
                //else if(!empty($discamount) && $discamount>$balance)
                //{
                   $refund_amount =  $refund_init;//'0.00';//$refund_amount+$discamount;
                //}
            }
            else
            {
                $refund_amount = ($total_paid_amount-$net_amount)+$refund_init;
               $balance  =  '0.00'+$refund_init;
            }
       }
       else
       {
           $balance = '0.00'+$refund_init;
            $refund_amount = '0.00'+$refund_init;
            
       }
            /*$paid_amount = $post['paid_amount'];
            if($total_paid_amount>$total_amount)
            {
                $refund_amount = $total_paid_amount-$total_amount;
            }
            
            $balance = '0.00';
            if($total_paid_amount<$total_amount)
            {
                $balance = $total_amount-$total_paid_amount;
            }*/
            $html_data = $this->amb_particular_billing();	
            	
            $response_data = array('html_data'=>$html_data, 'consultants_charge'=>$post['consultants_charge'], 'particulars_charges'=>$particulars_charges, 'total_amount'=>$total_amount, 'net_amount'=>$net_amt, 'discount'=>$post['discount'],'balance'=>number_format($balance,2,'.',''),'refund_amount'=>number_format($refund_amount,2,'.',''));	
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
       //echo "<pre>"; print_r($post); exit;
       if(isset($post['particular_id']) && !empty($post['particular_id']))	
       {	
           $opd_particular_billing = $this->session->userdata('amb_particular_billing'); 	
           //echo "<pre>"; print_r($opd_particular_billing); exit;
           $before_amount_arr = array_column($opd_particular_billing, 'amount');	
           $particular_id_list = array_column($opd_particular_billing, 'particular');	
           /*foreach($post['particular_id'] as $post_perticuler)	
           {	
              if(in_array($post_perticuler,$particular_id_list))	
              { 	
                 unset($opd_particular_billing[$post_perticuler]);	
              }	
           }*/
           
           foreach($opd_particular_billing as $key=>$perticuller_ids)
           { 
           	  if(in_array($perticuller_ids['particular'],$post['particular_id']))
           	  {  
                 unset($opd_particular_billing[$key]);
           	  }
           } 
           
           
            $amount_arr = array_column($opd_particular_billing, 'amount'); 	
            $after_total_amount = array_sum($amount_arr);	
            $before_part_total_amount = array_sum($before_amount_arr);	
            $deleted_total_amount = $before_part_total_amount-$after_total_amount;	
            $total_amount=$post['total_amount']-$deleted_total_amount;	
            $net_amount=$post['total_amount']-$deleted_total_amount-$post['discount'];	
            $this->session->set_userdata('amb_particular_billing',$opd_particular_billing);	
            //$opd_particular_billing = $this->session->userdata('amb_particular_billing'); 
            //echo "<pre>"; print_r($opd_particular_billing); exit;
            
            $html_data = $this->amb_particular_billing();	
            
            $paid_amount = $post['paid_amount'];
            $refund_amount = '0.00';
            
            $total_paid_amount = $post['total_paid_amount'];
            
            if(!empty($total_paid_amount) && $total_paid_amount!='undefined')
            {
            if($total_paid_amount>$total_amount)
            {
                $refund_amount = $total_paid_amount-$total_amount;
            }
            
            if(empty($post['refund_amount']))
            {
            $balance = '0.00';
            if($total_paid_amount<$net_amount)
            {
                $balance = $net_amount-$total_paid_amount;
                
                    if(!empty($discamount))
                    {
                    $balance = $balance-$discamount;
                    }
                
                   $refund_amount =  '0.00';
                
            }
            else
            {
                $refund_amount = $total_paid_amount-$net_amount;
               $balance  =  '0.00';
            }
            }
            else
            {
                
                $balance = '0.00';
                if($total_paid_amount<$net_amount)
                {
                    $balance = ($net_amount-$total_paid_amount)+$post['refund_amount'];
                    
                        if(!empty($discamount))
                        {
                        $balance = $balance-$discamount;
                        }
                }
                else
                {
                   $balance  = $post['refund_amount'];
                }
                $refund_amount = $post['refund_amount'];
            }
       }
       else
       {
           $balance = '0.00';
            $refund_amount = '0.00';
            
       }
            
            $response_data = array('html_data'=>$html_data, 'particulars_charges'=>$after_total_amount, 'total_amount'=>$total_amount, 'net_amount'=>$net_amount, 'discount'=>$post['discount'],'refund_amount'=>number_format($refund_amount,2,'.', ''),'balance'=>number_format($balance,2,'.', ''));
            ////////////
            
          /*$pay_arr = array('consultants_charge'=>number_format($consultants_charge,2,'.', ''),'kit_amount'=>number_format($kit_amount,2,'.', ''),'total_amount'=>number_format($total_amount,2,'.', ''),
           'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''), 'vendor_charge'=>number_format($vendor_charge,2,'.', ''), 'refund_amount'=>number_format($refund_amount,2,'.', ''));*/
           //////////////////
            
            
            $json = json_encode($response_data,true);	
            echo $json;	
       }	
    }	
    private function amb_particular_billing()	
    {	
        $particular_data = $this->session->userdata('amb_particular_billing');	
         $check_script = "<script>$('#selectall').on('click', function () { 	
                                  if ($('#selectall').prop('checked') == true) {	
                                      $('.booked_checkbox').prop('checked', true);	
                                  } else {	
                                      $('.booked_checkbox').prop('checked', false);	
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
      $booking_id= $this->session->userdata('ambulance_booking_id');
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
      $get_by_id_data = $this->booking->get_all_detail_print($booking_id,$branch_id);
      $data['particulars'] = $this->booking->get_booked_particular($booking_id,$branch_id);
       $data['payment_modes'] = $this->booking->get_booked_payment_mode($booking_id,$branch_id);
      // print_r($data['payment_modes']);die;
      //print_r($get_by_id_data);die;
      $template_format = $this->booking->template_format(array('section_id'=>13,'types'=>1),$branch_id);
      $data['template_data']=$template_format;
      $data['all_detail']= $get_by_id_data;
      $data['refund_data']=$this->booking->get_refund_booking_amount($booking_id);
      $data['page_type'] = 'Booking';
     // print_r($data);die;
      $this->load->view('ambulance/booking/print_template_ambulance',$data);
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
      $get_by_id_data = $this->opd->get_all_detail_print($booking_id);
      $template_format = $this->opd->template_format(array('section_id'=>1,'types'=>1));
      $data['template_data']=$template_format;
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_by_id_data['payment_mode']);
      $data['all_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;
      //print '<pre>';print_r($get_by_id_data); exit;
      //print '<pre>';print_r($data['all_detail']); exit;
      $this->load->view('opd/print_blank_template_opd',$data);
    }

    


    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
      //  print_r($post);die;
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->booking->referal_doctor_list();
        $data['referal_hospital_list'] = $this->booking->referal_hospital_list();
        $data['attended_doctor_list'] = $this->booking->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['source_list'] = $this->booking->source_list();  
        $data['location_list']=$this->general_model->location_list();
        $data['particulars_list'] = $this->general_model->amb_particulars_list();
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['owner_list']=$this->general_model->amb_vendor_list();
        
        $data['vehicle_list']=$this->booking->vehicle_list('');
        
        $data['form_data'] = array(
                                    "start_date"=>'',//date('d-m-Y')
                                    "end_date"=>'',//date('d-m-Y')
                                    "booking_from_date"=>'',
                                    "booking_to_date"=>'',
                                    "appointment_from_date"=>'',
                                    "appointment_to_date"=>'',
                                    "patient_code"=>"", 
                                    "patient_name"=>"",
                                    "patient_email"=>"",
                                    "simulation_id"=>"",
                                    "mobile_no"=>"",
                                    "vehicle_no"=>"",
                                    "vehicle_type"=>"",
                                    "driver_name"=>"",
                                    "address"=>"",
                                   "vehicle_type"=>"",
                                    "distance_type"=>"",
                                    "amount_from"=>"",
                                    "amount_to"=>"",
                                    "paid_amount_from"=>"",
                                    "paid_amount_to"=>"",
                                    "booking_no"=>"",
                                    "referral_doctor"=>"",
                                    "referral_hospital"=>"",
                                    "location"=>"",
                                    "start_time"=>"",
                                    "end_time"=>"",
                                    "booking_status"=>"",
                                    "status"=>"", 
                                    "payment_mode"=>"",
                                    "branch_id"=>'',
                                    'source_from'=>'',
                                    'destination'=>'',
                                    'going_date'=>'',
                                
                                  );
        if(isset($post) && !empty($post))
        {
           // print_r($post);die;
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('ambulance_search', $marge_post);

        }
        $ambulance_search = $this->session->userdata('ambulance_search');
        if(isset($ambulance_search) && !empty($ambulance_search))
        {
            $data['form_data'] = $ambulance_search;
        }
        $this->load->view('ambulance/booking/advance_search',$data);
    }

    public function get_branch_data($branch_id)
    {
        if($branch_id>0)
        {
          $branchdata = $this->opd->branch_data($branch_id);
          
          echo $branchdata;
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('ambulance_search');
    }

    function get_simulation_data($branch_id="")
    {
      if($branch_id>0)
      {
        $simulationdata = $this->opd->get_simulation_data($branch_id);
        echo $simulationdata;
      }
    }

    function get_referral_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $doctor_data = $this->opd->get_referral_doctor_data($branch_id);
        echo $doctor_data;
      }
    }

    function get_specialization_data($branch_id="")
    {
      if($branch_id>0)
      {
        $specialization_data = $this->opd->get_specialization_data($branch_id);
        echo $specialization_data;
      }
    }

    function get_attended_doctor_data($branch_id="")
    {
      if($branch_id>0)
      {
        $attended_data = $this->opd->get_attended_doctor_data($branch_id);
        echo $attended_data;
      }
    }


    public function opd_excel_old()
    {
        $list = $this->opd->search_opd_data();
       // echo "<pre>";print_r($list); exit;
        $columnHeader = '';  
        $columnHeader = " OPD No.". "\t"  . "Patient Name" . "\t" . "Patient Reg. No.". "\t" . "Doctor Name". "\t" . "Specialization" . "\t". "Appointment Date" ."\t". "Age" . "\t" . "Gender" . "\t". "Mobile" ;
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


    public function ambulance_excel()
    {
       // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $data_patient_reg = get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array($data_patient_reg,'Booking NO.','Patient Name','Vehicle No.','Driver Name','Booking Date','Booking Time','Net Amount','Paid Amount','Balance','Refund Amount');
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
              
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          $list = $this->booking->search_ambulance_data();
       //   print_r($list);die;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $ambulance)
               {
                    
                   
                    $booking_code = $ambulance->booking_no;
                    $patient_name = $ambulance->patient_name;
                   
                    if($ambulance->balance1>0)
                    {
                        $row[] = $ambulance->balance1;
                    }else
                    {
                          
                          $bal = $ambulance->balance1+$ambulance->refund_amount;
                          if($bal>0)
                          {
                              $balk= $bal;
                          }
                          else
                          {
                              $balk='0.00';
                          }
                          
                    }
                    
                    array_push($rowData,$ambulance->patient_code,$ambulance->booking_no,$ambulance->patient_name,$ambulance->vehicle_no,$ambulance->driver_name,date('d-m-Y',strtotime($ambulance->booking_date)),date('h:i A',strtotime($ambulance->booking_time)),$ambulance->net_amount,$ambulance->paid_amount1,$balk,$ambulance->refund_amount);
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
          header("Content-Disposition: attachment; filename=ambulance_booking_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
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
          $fields = array('OPD NO.','Patient Name',$data_patient_reg,'Appointment Date','Booking Date','Age','Gender','Mobile','Doctor Name','Specialization');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->opd->search_opd_data();
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
                    array_push($rowData,$opds->booking_code,$opds->patient_name,$opds->patient_code,$appointment_date,$booking_date,$patient_age,$gender,$opds->mobile_no,$attended_doctor_name,$specialization_id);
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
         header("Content-Disposition: attachment; filename=opd_list_".time().".csv");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }
         
        
    }

    public function ambulance_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->booking->search_ambulance_data();
        $this->load->view('ambulance/booking/ambulance_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("ambulance_booking_list_".time().".pdf");
    }
    public function opd_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->opd->search_opd_data();
      $this->load->view('opd/opd_html',$data); 
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
            $result = $this->opd->get_test_vals($vals);  
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
            $result = $this->opd->get_medicine_auto_vals($vals);  
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
            $result = $this->opd->get_dosage_vals($vals);  
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
            $result = $this->opd->get_type_vals($vals);  
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
            $result = $this->opd->get_duration_vals($vals);  
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
            $result = $this->opd->get_frequency_vals($vals);  
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
            $result = $this->opd->get_advice_vals($vals);  
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
        $diseases_data = $this->opd->get_diseases_data($branch_id);
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
        $source_from_data = $this->opd->get_source_from_data($branch_id);
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

        $html.='<div class="row m-b-5"><div class="col-md-12"><div class="row"><div class="col-md-4"><b>'.$payment_detail->field_name.'<span class="star">*</span></b></div> <div class="col-md-8"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div></div></div>';
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
    $this->opd->delete_existing_branch_list_cols($module_id,$branch_id);
    $this->opd->insert_new_cols_branch_list_cols($branch_id, $module_id, $col_ids_array);
    echo "Record Inserted Successfully";
  }

  public function reset_coloumn_record()
  {
    $module_id=$this->input->post('module_id');
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    $this->opd->delete_existing_branch_list_cols($module_id,$branch_id);
    echo json_encode(array("status"=>200));
  }

  public function update_doctor_status($opd_id="",$status_type="")
  {
    $update_status= $this->opd->update_doctor_status($opd_id,$status_type);
    echo $update_status;exit;
  }

  public function get_validity_date_in_between()
  {
      $doctor_id= $this->input->post('doctor_id');
      $booking_date= $this->input->post('booking_date');
      $patient_id= $this->input->post('patient_id');
      $result= $this->opd->get_validity_date_in_between($doctor_id,$booking_date,$patient_id);
      echo $result;die;
  }
  public function get_validate_date()
  {
      $doctor_id= $this->input->post('doctor_id');
      $booking_date= $this->input->post('booking_date');
      $patient_id= $this->input->post('patient_id');
      $result= $this->opd->get_validate_date($doctor_id,$booking_date,$patient_id);
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
              $fields = array('OPD No.','Patient Reg.No.(*)','Simulation','Patient Name(*)','Mobile No.(*)','Address','Gender (Male=1,Female=0,Others=2)','Age Year(yyyy)(*)','Age Month(mm)(*)','Age Day(dd)(*)','Age Hours','Remarks','Doctor Name(*)','Specilization(*)','Payment mode','Amount(*)','Discount(*)','Paid Amount','Created Date');
              $col = 0;
              foreach ($fields as $field)
              {
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 2, '');
                  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 2, '');
                  
                  //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 2, '1988');
                  //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 2, '10');
                  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 2, '');
                  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 2, '');
                  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 2, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 2, '');
                  
                  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 3, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 4, '');
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 5, '');
                   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 6, '');
                  $col++;
              }
              $rowData = array();
              $data= array();
              // Fetching the table data
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
              // Sending headers to force the user to download the file
              header('Content-Type: application/vnd.ms-excel charset=UTF-8');
              header("Content-Disposition: attachment;filename=ambulance_booking_sample_".time().".xls");  
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
        $data['page_title'] = 'Import OPD excel';
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
                    $array_keys = array('booking_code','patient_code','simulation_id','patient_name','mobile_no','address','gender','age_y','age_m','age_d','age_h','remark','doctor_name','specialization_id','payment_mode','total_amount','discount','paid_amount','created_date');

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
                   $this->opd->save_all_opd_data($opd_all_data);
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

        $this->load->view('opd/import_opd_excel',$data);
    } 
// 30/07/2019 mlc print
    function mlc_print($id="",$branch_id='')
    {
      if(!empty($id))
      {
        $opd_booking_id = $id;
      }
      else if(isset($opd_booking_id) && !empty($opd_booking_id))
      {
        $opd_booking_id =$opd_booking_id;
      }
      else
      {
        $opd_booking_id = '';
      } 
       $get_by_id_data = $this->opd->get_all_detail_print($opd_booking_id,$branch_id);
      // echo "<pre>"; print_r($get_by_id_data);die;
      $data['get_by_id_data']=$get_by_id_data;
      $data['booking_id']=$opd_booking_id;
      $this->load->view('opd/mlc_form_opd',$data);
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
           $opd_booking_data = $this->opd->get_by_id($booking_id);
         
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
    /*Distance Charge */
    public function get_distance_charge()
    {
       $post = $this->input->post();
       
       if(isset($post) && !empty($post))
       {
        $vehicle_id = $post['vehicle_id'];
         $vehicle_type = $post['vehicle_type'];
         $vencharge=$this->booking->get_vendor_charge($vehicle_id);
        $distance_type=$post['distance_type'];
      
        $distance=$post['distance'];
        $total=$post['particulars_charges'];
      
        $charge=$this->booking->get_distance_charge($vehicle_type);
         $booking_amount=$this->booking->get_booking_amount($post['id']);
       
        if($distance_type==0)
      {
         
        if($distance >=$charge->local_min_distance)
        {

          $consultants_charge=$charge->local_min_amount+(($distance-$charge->local_min_distance)*$charge->local_per_km_charge);
        }
        elseif($distance <=$charge->local_min_distance)
        {
           $consultants_charge=$charge->local_min_amount;
        }
      }
        elseif($distance_type==1)
      {
        
        if($distance >=$charge->outstation_min_distance)
        {
           $consultants_charge=$charge->outstation_min_amount+(($distance-$charge->outstation_min_distance)*$charge->outstation_per_km_charge);
          
        }
        elseif($distance <=$charge->outstation_min_distance)
        {
          $consultants_charge=$charge->outstation_min_amount;
        }
      }
      $vendor_charge=0;
      if($vencharge->charge_type==1)
      {
          $vendor_charge = $vencharge->charge;
      }
      else if($vencharge->charge_type==2)
      {
          $vendor_charge = $distance*$vencharge->charge;
      }
           $discount = $post['discount'];
           $total_amount=$total+$consultants_charge;
           $net_amount = $total_amount-$discount;
           $paid_amount = $net_amount;
            //$ref_amt=$booking_amount->first_paid_amount;
            
            $total_paid_amount=$booking_amount->first_paid_amount;
     
          /* if($ref_amt > $net_amount)
           {
                 $refund_amount=$ref_amt-$net_amount;
           }
           if($ref_amt==$net_amount){
               $refund_amount=0.00;
           }*/
           if(!empty($total_paid_amount))
           {
            if($total_paid_amount>$total_amount)
            {
                $refund_amount = $total_paid_amount-$total_amount;
                 $balance = '0.00';
            }
            
           
            if($total_paid_amount<$total_amount)
            {
                $balance = $total_amount-$total_paid_amount;
                if(!empty($discount) && $discount<$balance)
                {
                    $balance = $balance-$discount;
                    $refund_amount = '0.00';
                }
                
            }
            
           }
           else
           {
               $refund_amount = '0.00';
               $balance = '0.00';
           }
           
           
           
          // $balance=$consultants_charge-$net_amount;
           $pay_arr = array('consultants_charge'=>number_format($consultants_charge,2,'.', ''),'kit_amount'=>number_format($kit_amount,2,'.', ''),'total_amount'=>number_format($total_amount,2,'.', ''),
           'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''),'balance'=>number_format($balance,2,'.', ''), 'vendor_charge'=>number_format($vendor_charge,2,'.', ''), 'refund_amount'=>number_format($refund_amount,2,'.', ''));
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
       
    }
    /*Distance Charge*/
    public function vendor_list($type=''){
        $result=$this->general_model->amb_vendor_list($type);
        $html='<option value="">Select Vendor</option>';
        foreach($result as $vendor){
            $html.='<option value="'.$vendor->id.'">'.$vendor->name.'</option>';
        }
        echo $html;
    }
    
     public function vendor_vehicle_list($id=''){
        $result = $this->booking->vehicle_list('',$id);
        $html='<option value="">Select Vehicle</option>';
        foreach($result as $vehicle){
            $html.='<option value="'.$vehicle->id.'">'.$vehicle->vehicle_no.'</option>';
        }
        echo $html;
    }
      public function lease_vehicle_list(){
        $result = $this->booking->lease_vehicle_list('');
        $html='<option value="">Select Vehicle</option>';
        foreach($result as $vehicle){
            $html.='<option value="'.$vehicle->id.'">'.$vehicle->vehicle_no.'</option>';
        }
        echo $html;
    }
    
      public function get_vendor_commission($id=''){
        $result = $this->booking->vendor_commission('',$id);
       
       // echo $html;
    }
}
