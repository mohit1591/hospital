<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd_billing extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('opd_billing/opd_billing_model','opd_billing');
		$this->load->library('form_validation');
    }

    
	public function index()
  {
        unauthorise_permission(151,909);
        $this->session->unset_userdata('opd_billing_search');
        $this->session->unset_userdata('opd_particular_billing');
        $this->session->unset_userdata('opd_particular_payment');
        $this->session->unset_userdata('dental_opd_particular_billing');
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
        $data['form_data'] = array('patient_name'=>'','reciept_code'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date); 
        $data['page_title'] = 'OPD Billing List'; 
        $this->load->view('opd_billing/list',$data);
    }

    public function ajax_list()
    {   
        unauthorise_permission(151,909);
        //$this->session->unset_userdata('referral_doctor_id');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->opd_billing->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test) {
          //print_r($test);die;
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
            
            //$attended_doctor_name ="";
            //$attended_doctor_name = get_doctor_name($test->attended_doctor);

            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$test->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $test->reciept_code;
            $row[] = $test->patient_name;
            
            $row[] = date('d-m-Y',strtotime($test->booking_date));
            $row[] = $test->patient_reg_no;
            $row[] = $test->mobile_no;
            $row[] = $test->gender;
            $row[] = $test->address;
            //$row[] = $test->father_husband_simulation." ".$test->father_husband;
            if(!empty($test->relation_name))
            {
              $row[] = $test->relation.' '.$test->relation_simulation.' '.$test->relation_name;  
            }
            else
            {
              $row[] ='';
            }
            
            $row[] = $test->patient_email;
            $row[] = $test->patient_source;
            $row[] = $test->disease;
            $row[] = $test->doctor_hospital_name;
            $row[] = $test->specialization;


            $row[] = "Dr. ".$test->doctor_name;
            $row[] = $test->title;
            //$row[] = $test->policy_no;
            if(strtotime($test->next_app_date)>0) 
                $row[] = date('d-m-Y', strtotime($test->next_app_date)); 
            else 
              $row[]="";
            $row[] = $test->payment_mode;
            $row[] = number_format($test->kit_amount,2);
            $row[] = number_format($test->particulars_charges,2);
            $row[] = number_format($test->total_amount,2);
            $row[] = number_format($test->net_amount,2);
            $row[] = number_format($test->paid_amount,2);
            $row[] = number_format($test->discount,2);
            $row[] = "Dr. ".$test->doctor_name;
            //// old /////
            //Action button /////
            $btn_confirm="";
            $btn_edit = ""; 
            $btn_delete = ""; 
            $billing_consolidated_bill = '';
            
              
            if($users_data['parent_id']==$test->branch_id)
            {
              if(in_array('914',$users_data['permission']['action'])){
        		    $btn_edit = ' <a class="btn-custom" href="'.base_url("opd_billing/edit/".$test->id).'" title="Edit Billing"><i class="fa fa-pencil"></i> Edit</a>';
              }
            
              
              if(in_array('913',$users_data['permission']['action'])){
                    $btn_delete = ' <a class="btn-custom" onClick="return delete_opd_booking('.$test->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
               }
            }
           
            $print_url = "'".base_url('opd_billing/print_billing_report/'.$test->id.'/'.$test->branch_id)."'";
            
            
            
           $btn_print = ' <a class="btn-custom" href="javascript:void(0)" onclick = "return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print  </a>'; 
           

           $print_consolidated_url = "'".base_url('opd_billing/print_consolidate_billing_report/'.$test->id.'/'.$test->branch_id)."'";
           $billing_consolidated_bill= '  <a    class="btn-custom" onClick="return print_window_page('.$print_consolidated_url.')" style="'.$test->id.'" title="Print Consolidated Bill"><i class="fa fa-print"></i> Print Consolidated Bill</a>';  
           
           $print_barcode_url = "'".base_url('opd_billing/print_barcode/'.$test->id)."'";
            $btn_barcode = '<a  class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_barcode_url.')"  title="Print Barcode" ><i class="fa fa-barcode"></i> Print Barcode </a>';
            // End Action Button //
            
            $row[] = $btn_confirm.$btn_edit.$btn_delete.$btn_print.$billing_consolidated_bill.$btn_barcode;   
            $data[] = $row;
            $i++;
        }
        
        $recordsTotal = $this->opd_billing->count_all();
        $recordsFiltered = $recordsTotal;
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $recordsTotal,
                        "recordsFiltered" => $recordsFiltered,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function add($pid="")
    {

       $billing_particular = $this->session->userdata('opd_particular_billing'); 
       unauthorise_permission(151,915);
        //$this->session->unset_userdata('billing_data_array');
        $this->load->model('general/general_model');  
        $data['specialization_list'] = $this->general_model->specialization_list(); 
         $data['teeth_number_list'] = $this->general_model->teeth_number_list(); 

        $token_type=$this->opd_billing->get_token_setting();
        $token_type=$token_type['type'];

       //print_r($data['teeth_number_list']);
        $post = $this->input->post();
       //print_r($post);
       //print_r($data['specialization_list']);
       //die;
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
        $patient_email="";
        $quantity="1";
        $amount ="";
        $remarks='';
        $charges = "";
        $balance_amount = '0.00';
        $particulars ="";
        $city_id='';
        $state_id="";
        $country_id="99";
        $attended_doctor="";
        $diseases="";
        $type=3;
        $ref_by_other ="";
        $referral_doctor="";
        $referral_hospital='';
        $referred_by='';
        $relation_name="";
        $relation_type="";
        $relation_simulation_id='';
        $adhar_no='';
        $specialization_id='';
        $booking_time ="";
        $insurance_type='';
        $insurance_type_id='';
        $ins_company_id='';
        $polocy_no='';
        $tpa_id='';
        $ins_amount='';
        $pannel_type="0";
        $ins_authorization_no='';
        $dob='';
        if($pid>0)
        {
           
           $this->load->model('patient/patient_model');
           $patient_data = $this->patient_model->get_by_id($pid);
           
           /* present age*/
           /*if($patient_data['dob']=='1970-01-01' || $patient_data['dob']=="0000-00-00")
            {
              $dob='';
              $present_age = get_patient_present_age('',$patient_data);
              $age_y = $present_age['age_y'];
              $age_m = $present_age['age_m'];
              $age_d = $present_age['age_d'];
              
            }
            else
            {
              $dob=date('d-m-Y',strtotime($patient_data['dob']));
              $present_age = get_patient_present_age($dob); 
              $age_y = $present_age['age_y'];
              $age_m = $present_age['age_m'];
              $age_d = $present_age['age_d'];
              
            }*/
            /* present age*/

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

                /*$age_y = $present_age['age_y'];
                  $age_m = $present_age['age_m'];
                  $age_d = $present_age['age_d'];*/
                if($patient_data['dob']=='1970-01-01' || $patient_data['dob']=="0000-00-00")
                {
                  $dob='';
                }
                else
                {
                  $dob=date('d-m-Y',strtotime($patient_data['dob']));
                }
                
                
              $address = $patient_data['address'];
              $address2 = $patient_data['address2'];
              $address3 = $patient_data['address3'];
              $city_id = $patient_data['city_id'];
              $state_id = $patient_data['state_id'];
              $country_id = $patient_data['country_id'];
              $relation_type = $patient_data['relation_type']; 
              $relation_name = $patient_data['relation_name'];
              $relation_simulation_id = $patient_data['relation_simulation_id'];
              $patient_email = $patient_data['patient_email'];
              $adhar_no = $patient_data['adhar_no'];
              $insurance_type=$patient_data['insurance_type'];
              $insurance_type_id=$patient_data['insurance_type_id'];
              $ins_company_id=$patient_data['ins_company_id'];
              $polocy_no=$patient_data['polocy_no'];
              $tpa_id=$patient_data['tpa_id'];
              $ins_amount=$patient_data['ins_amount'];
              $ins_authorization_no=$patient_data['ins_authorization_no'];

              /*$charges = $patient_data['charges'];
              $amount = $form_data['amount'];
              $quantity  = $form_data['quantity'];*/
           }
        }
        $data['simulation_array']= $this->general_model->simulation_list();
        $data['diseases_list'] = $this->opd_billing->diseases_list();
        $data['package_list'] = $this->general_model->package_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['particulars_list'] = $this->general_model->particulars_list();
        $data['country_list'] = $this->general_model->country_list();
        $data['referal_doctor_list'] = $this->opd_billing->referal_doctor_list();
        $data['source_list'] = $this->opd_billing->source_list();
        $data['referal_hospital_list'] = $this->opd_billing->referal_hospital_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['page_title'] = "OPD Billings";  
        $post = $this->input->post();
        if(empty($pid))
        {
          $patient_code = generate_unique_id(4);
        }
        $data['payment_mode']=$this->general_model->payment_mode();
        $reciept_code = generate_unique_id(21);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'patient_id'=>$patient_id,
                                  'patient_code'=>$patient_code,
                                  'reciept_code'=>$reciept_code,
                                  'simulation_id'=>$simulation_id,
                                  'patient_name'=>$patient_name,
                                  'mobile_no'=>$mobile_no,
                                  'gender'=>$gender,
                                  'relation_type'=>$relation_type,
                                  'relation_name'=>$relation_name,
                                  'relation_simulation_id'=>$relation_simulation_id,
                                  'age_y'=>$age_y,
                                  'age_m'=>$age_m,
                                  'age_d'=>$age_d,
                                  'dob'=>$dob,
                                  'dept_id'=>"",
                                  'attended_doctor'=>$attended_doctor,
                                  'address'=>$address,
                                  'address_second'=>$address_second,
                                  'address_third'=>$address_third,
                                  'adhar_no'=>$adhar_no,
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
                                  'field_name'=>'',
                                  'type'=>$type,
                                  'diseases'=>$diseases,
                                  "ref_by_other"=>$ref_by_other,
                                  'referral_doctor'=>$referral_doctor,
                                  'next_app_date'=>'',
                                  'kit_amount'=>'0.00',
                                  'particulars_charges'=>'0.00',
                                  'payment_mode'=>'cash',
                                  'source_from'=>'',
                                  'remarks'=>'',
                                  'referred_by'=>$referred_by,
                                  'referral_hospital'=>"",
                                  'specialization_id'=>'',
                                  'tooth_num'=>'',
                                  'tooth_num_val'=>'',
                                  "insurance_type"=>$insurance_type,
                                  "insurance_type_id"=>$insurance_type_id,
                                  "ins_company_id"=>$ins_company_id,
                                  "polocy_no"=>$polocy_no,
                                  "tpa_id"=>$tpa_id,
                                  'pannel_type'=>$pannel_type,
                                  "ins_amount"=>$ins_amount,
                                  "ins_authorization_no"=>$ins_authorization_no,
                                   'booking_time'=>date('H:i:s'),
                                   'token_type'=>$token_type,
                                   'token_no'=>'',
                                  );    

        if(isset($post) && !empty($post))
        {   
           //print"<pre>";print_r($data['form_data']); 
            $data['form_data'] = $this->_validatebilling();
            if($this->form_validation->run() == TRUE)
            {
                $booking_id = $this->opd_billing->save_booking();
                //print_r($booking_id);
                //die;
                //$this->session->set_flashdata('success','OPD particular successfully saved.');
                //redirect(base_url('opd'));
                if(!empty($booking_id))
                {
                  
                  $get_by_id_data = $this->opd_billing->get_by_id($booking_id);
                  $patient_name = $get_by_id_data['patient_name'];
                  $reciept_code = $get_by_id_data['reciept_code'];
                  $paid_amount = $get_by_id_data['paid_amount'];
                  $mobile_no = $get_by_id_data['mobile_no'];
                  $patient_email = $get_by_id_data['patient_email'];
                  //check permission
                  if(in_array('640',$users_data['permission']['action']))
                  {

                        if(!empty($mobile_no))
                        {
                          send_sms('opd_billing',3,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{Amount}'=>$paid_amount,'{Receiptno.}'=>$reciept_code)); 
                          
                          $patient_data = $this->patient_model->get_by_id($pid); 
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
                      $this->general_functions->email($patient_email,'','','','','1','opd_billing','3',array('{Name}'=>$patient_name,'{Amount}'=>$paid_amount,'{Receiptno.}'=>$reciept_code));
                       
                    }
                  } 
                }
                $this->session->set_userdata('opd_booking_id',$booking_id);
                $this->session->set_flashdata('success','OPD Billing successfully saved.');
                redirect(base_url('opd_billing/add?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors(); 
                //print_r($data['form_error']); die; 
            }     
        }
        $data['insurance_type_list'] = $this->general_model->insurance_type_list(); 
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();
        $this->load->view('opd_billing/add',$data); 
    }

	
    public function delete_booking($id="")
    {
        unauthorise_permission(151,913);
       if(!empty($id) && $id>0)
       {
           $result = $this->opd_billing->delete_booking($id);
           $response = "OPD successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(151,913);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->opd_billing->deleteall($post['row_id']);
            $response = "Billing successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(85,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->opd_billing->get_by_id($id);  
        $data['page_title'] = $data['form_data']['doctor_name']." detail";
        $this->load->view('opd/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(151,912);
        $data['page_title'] = 'OPD Billing Archive List';
        $this->load->helper('url');
        $this->load->view('opd_billing/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(151,912);
        $this->load->model('opd_billing/opd_billing_archive_model','opd_billing_archive'); 
		    $users_data = $this->session->userdata('auth_users');
        $list = $this->opd_billing_archive->get_datatables();  
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

            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            } 

            
            $attended_doctor_name ="";
            $attended_doctor_name = get_doctor_name($opd->attended_doctor);

            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$opd->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$opd->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $opd->reciept_code;
            $row[] = $opd->patient_name;
            $row[] = $attended_doctor_name;
            $row[] = date('d M Y',strtotime($opd->created_date));
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
            if(in_array('910',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_opd('.$opd->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            }
            if(in_array('911',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$opd->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->opd_billing_archive->count_all(),
                        "recordsFiltered" => $this->opd_billing_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore_opd($id="")
    {
        unauthorise_permission(151,910);
        $this->load->model('opd_billing/opd_billing_archive_model','opd_billing_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->opd_billing_archive->restore($id);
           $response = "OPD Billing successfully restore in OPD Billing list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(151,910);
        $this->load->model('opd_billing/opd_billing_archive_model','opd_billing_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->opd_billing_archive->restoreall($post['row_id']);
            $response = "Billing successfully restore in list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(151,911);
        $this->load->model('opd_billing/opd_billing_archive_model','opd_billing_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->opd_billing_archive->trash($id);
           $response = "Billing successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(151,911);
        $this->load->model('opd_billing/opd_billing_archive_model','opd_billing_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->opd_billing_archive->trashall($post['row_id']);
            $response = "Billing successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function opd_dropdown()
  {
      $opd_list = $this->opd_billing->employee_type_list();
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

 public function get_allsub_branch_list(){
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
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
      unauthorise_permission(151,914);
      $users_data = $this->session->userdata('auth_users');
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
      	//$data['specialization_list'] = $this->general_model->specialization_list();
        $token_type=$this->opd_billing->get_token_setting();
          $token_type=$token_type['type'];
        $this->load->model('general/general_model'); 
        $post = $this->input->post();
        $result = $this->opd_billing->get_by_id($id); 
        //echo "<pre>";print_r($result); exit;
        $data['simulation_array']= $this->general_model->simulation_list();
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->opd_billing->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd_billing->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();  
        $data['particulars_list'] = $this->general_model->particulars_list();
        $data['diseases_list'] = $this->opd_billing->diseases_list();
        $data['package_list'] = $this->general_model->package_list();
        $data['source_list'] = $this->opd_billing->source_list();
        $data['referal_hospital_list'] = $this->opd_billing->referal_hospital_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $billed_list = $this->opd_billing->get_billed_particular_list($id); 
        
        $data['dental_billed_list'] = $this->opd_billing->get_billed_dental_particular_list($id); 
        $booking_list = $this->session->userdata('opd_particular_billing');
        $data['dental_booking_list'] = $this->session->userdata('dental_opd_particular_billing');
        $data['teeth_number_list'] = $this->general_model->teeth_number_list(); 
        //print_r($dental_booking_list);
        //print_r($dental_billed_list);
        // && empty($result['specialization_id'])
        if(!isset($booking_list))
        {  
            $this->session->set_userdata('opd_particular_billing',$billed_list);
        }
        if(!isset($data['dental_booking_list']) && !empty($result['specialization_id']) && $result['specialization_id']=='675')
        {  
            $this->session->set_userdata('dental_opd_particular_billing',$data['dental_billed_list']);
        }
          
          
        $checkdate = date('d-m-Y',strtotime($result['cheque_date']));
        if(!empty($checkdate) && $checkdate!='00-00-0000' && $checkdate!='01-01-1970')
        {
          $chequedates= $checkdate;
        }
        else
        {
          $chequedates = ""; 
        }

        $data['page_title'] = "Update OPD Billing";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['particular_list_data'] = '';
         
         $next_app_date = date('d-m-Y',strtotime($result['next_app_date']));
        if(!empty($next_app_date) && $next_app_date!='00-00-0000' && $next_app_date!='01-01-1970' && $next_app_date!='30-11--0001')
        {
          $next_app_date  = $next_app_date;
        }
        else
        {
          $next_app_date="";
        }
        $this->load->model('general/general_model');
        $data['payment_mode']=$this->general_model->payment_mode();
        $get_payment_detail= $this->opd_billing->payment_mode_detail_according_to_field($result['payment_mode'],$id);
        $total_values=array();
        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }

        //present age of patient
          if($result['dob']=='1970-01-01' || $result['dob']=="0000-00-00")
          {
            $present_age = get_patient_present_age('',$result);
            //echo "<pre>"; print_r($present_age);
          }
          else
          {
            $dob=date('d-m-Y',strtotime($result['dob']));
            $present_age = get_patient_present_age($dob,$result);
          }
          
          $age_y = $present_age['age_y'];
          $age_m = $present_age['age_m'];
          $age_d = $present_age['age_d'];
          //$age_h = $present_age['age_h'];
          //present age of patient
          
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
          

        $data['form_data'] = array(
                                    'data_id'=>$result['id'],
                                    'patient_id'=>$result['patient_id'], 
                                    'patient_code'=>$result['patient_code'],
                                    'adhar_no'=>$result['adhar_no'],
                                    'reciept_code'=>$result['reciept_code'],
                                    'simulation_id'=>$result['simulation_id'],
                                    'patient_name'=>$result['patient_name'],
                                    'pannel_type'=>$result['pannel_type'],
                                    'mobile_no'=>$result['mobile_no'],
                                    'gender'=>$result['gender'],
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
                                    'referral_doctor'=>$result['referral_doctor'],
                                    'specialization_id'=>$result['specialization_id'],
                                    'package_id'=>$result['package_id'],
                                    'booking_date'=>date('d-m-Y',strtotime($result['booking_date'])),
                                    'next_app_date'=>$next_app_date,
                                    'total_amount'=>$result['total_amount'],
                                    'net_amount'=>$result['net_amount'],
                                    'paid_amount'=>$result['paid_amount'],
                                    'discount'=>$result['discount'],
                                    'balance'=>$result['balance'],
                                    'payment_mode'=>$result['payment_mode'],
                                    'field_name'=>$total_values,
                                    'relation_type'=>$result['relation_type'],
                                    'relation_name'=>$result['relation_name'],
                                    'relation_simulation_id'=>$result['relation_simulation_id'],
                                   // 'branch_name'=>$result['branch_name'],
                                    //'transaction_no'=>$result['transaction_no'],
                                   // 'cheque_no'=>$result['cheque_no'],
                                    //'cheque_date'=>$chequedates,
                                    'type'=>$result['type'],
                                    'patient_email'=>$result['patient_email'],
                                    'attended_doctor'=>$result['attended_doctor'],
                                    'diseases'=>$result['diseases'],
                                    'referral_doctor'=>$result['referral_doctor'],
                                    'ref_by_other'=>$result['ref_by_other'],
                                    'kit_amount'=>$result['kit_amount'],
                                    'particulars_charges'=>$result['particulars_charges'],
                                    'source_from'=>$result['source_from'],
                                    'remarks'=>$result['remarks'],
                                    'referred_by'=>$result['referred_by'],
                                    'referral_hospital'=>$result['referral_hospital'],
                                    'booking_time'=>$result['booking_time'],
                                    "insurance_type"=>$result['insurance_type'],
                                    "insurance_type_id"=>$result['insurance_type_id'],
                                    "ins_company_id"=>$result['ins_company_id'],
                                    "polocy_no"=>$result['polocy_no'],
                                    "tpa_id"=>$result['tpa_id'],
                                    "ins_amount"=>$result['ins_amount'],
                                    "ins_authorization_no"=>$result['ins_authorization_no'],
                                     'tooth_num'=>'',
                                    'token_type'=>$token_type,
                                    'token_no'=>$result['token_no'],
                                  );


                                  //print_r($data['form_data']);
                                  //exit;
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();

            if($this->form_validation->run() == TRUE)
            {   
                if(in_array('640',$users_data['permission']['action']))
                {     
                if(!empty($post['mobile_no']) && $result['mobile_no']!=$post['mobile_no'])
                  {
                    $parameter = array('{patient_name}'=>$post['patient_name'], '{mobile_no}'=>$post['mobile_no']);
                     
                    send_sms('update_patient_mobile',28,'',$post['mobile_no'],$parameter);  
                  }
                }
                
                $booking_id = $this->opd_billing->save_booking();
                $this->session->set_userdata('opd_booking_id',$booking_id);
                $this->session->set_flashdata('success','Billing updated successfully.');
                redirect(base_url('opd_billing/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors(); 
                //echo "<pre>"; print_r($data['form_error']); exit;
            }     
        }
          $data['insurance_type_list'] = $this->general_model->insurance_type_list(); 
          $data['insurance_company_list'] = $this->general_model->insurance_company_list();
          $this->load->view('opd_billing/add',$data);       
      }
    }

    
     
    private function _validateform()
    {
        $post = $this->input->post(); 
        $field_list = mandatory_section_field_list(3);
        $users_data = $this->session->userdata('auth_users');  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'patient name', 'trim|required'); 
        $this->form_validation->set_rules('attended_doctor', 'consultant', 'trim|required'); 
        $this->form_validation->set_rules('specialization', 'specialization', 'trim|required'); 
         $this->form_validation->set_rules('adhar_no', 'aadhaar no', 'min_length[12]|max_length[16]'); 
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
        if(!empty($field_list)){
            
            if($field_list[0]['mandatory_field_id']=='25' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]');
            }
           
            if($field_list[1]['mandatory_field_id']=='26' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                $this->form_validation->set_rules('age_y', 'age', 'trim|required');
            }
        }
         if($post['payment_mode']=='cheque'){
                  $this->form_validation->set_rules('branch_name', 'branch name', 'trim|required');
                $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');
                $this->form_validation->set_rules('cheque_date', 'cheque date', 'trim|required');

            }else if($post['payment_mode']=='card' || $post['payment_mode']=='neft'){
                  $this->form_validation->set_rules('transaction_no', 'transaction no', 'trim|required');
            }
         
          
        
        if ($this->form_validation->run() == FALSE) 
        {  
           
            if(!empty($post['pay_now'])){
            	$pay_now = 1;
            }
            else
            {
            	$pay_now = 0;
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
                                        'dob'=>$post['dob'],
                                        'address'=>$post['address'],
                                        'address_second'=>$post['address2'],
                                        'address_third'=>$post['address3'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'appointment_time'=>$post['appointment_time'],
                                        'booking_date'=>$post['booking_date'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$post['balance'],
                                        'patient_bp'=>$post['patient_bp'],
                                        'patient_temp'=>$post['patient_temp'],
                                        'patient_weight'=>$post['patient_weight'],
                                        'patient_height'=>$post['patient_height'],
                                        'patient_sop'=>$post['patient_sop'],
                                        'patient_rbs'=>$post['patient_rbs'],
                                        'type'=>$post['type'],
                                        'patient_email'=>$post['patient_email'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'appointment_time'=>$post['appointment_time'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                         'discount'=>$post['discount'],
                                        'balance'=>$post['balance'],
                                       'branch_name'=>$post['branch_name'],
                                       'transaction_no'=>$post['transaction_no'],
                                       'cheque_no'=>$post['cheque_no'],
                                        'pay_now'=>$pay_now,
                                        'payment_mode'=>$post['payment_mode'],
                                        'specialization_id'=>$post['specialization'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'remarks'=>$post['remarks'],
                                        'relation_type'=>$post['relation_type'],
                                        'relation_name'=>$post['relation_name'],
                                        'relation_simulation_id'=>$post['relation_simulation_id'],
                                        'referral_hospital'=>$post['referral_hospital'],
                                          'referred_by'=>$post['referred_by'],
                                          'adhar_no'=>$post['adhar_no'],
                                          "insurance_type"=>$insurance_type,
                                          "insurance_type_id"=>$insurance_type_id,
                                          "ins_company_id"=>$ins_company_id,
                                          "polocy_no"=>$post['polocy_no'],
                                          "tpa_id"=>$post['tpa_id'],
                                          "ins_amount"=>$post['ins_amount'],
                                          "ins_authorization_no"=>$post['ins_authorization_no'],
                                          'pannel_type'=>$post['pannel_type'],

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
         $doctor_rate="";
         $total_amount = 0;
         $doctor_id = $post['doctor_id'];
         $this->load->model('general/general_model');
         $doctor_data = $this->general_model->doctors_list($doctor_id);
         $consultant_charge = $doctor_data[0]->consultant_charge; 
        // $pay_arr = array('total_amount'=>$consultant_charge); //number_format
         $netamount = $consultant_charge-$post['discount'];
         $discount = $post['discount'];
         //$balance = number_format($consultant_charge-$post['discount'],2,'.', ''); 
         

         $pay_arr = array('total_amount'=>number_format($consultant_charge,2,'.', ''),'discount'=>number_format($discount,2,'.', ''),'net_amount'=>number_format($netamount,2,'.', ''),'paid_amount'=>number_format($consultant_charge-$post['discount'],2,'.', ''));	 	

         $json = json_encode($pay_arr,true);
         echo $json;
         
       }
    }

        


    public function calculate_payment()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
         
           $total_amount = $post['total_amount'];
           $discount = $post['discount'];
           $net_amount = $total_amount-$discount;
           $balance = $post['balance'];
           $paid_amount = $post['paid_amount'];
           $paid_amount = $net_amount;
           $pay_arr = array('total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''), 'balance'=>number_format($balance,2,'.', ''), 'paid_amount'=>number_format($paid_amount,2,'.', ''));
           $json = json_encode($pay_arr,true);
           echo $json;
         
       }
    }

    private function _validate()
    {
        $field_list = mandatory_section_field_list(4);
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
        if(isset($post['field_name'])) 
        {
        $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
       	//$this->form_validation->set_rules('attended_doctor','consultant','trim|required');
         if(!empty($field_list)){
            
            if($field_list[0]['mandatory_field_id']=='27' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                 $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }
           
            if($field_list[1]['mandatory_field_id']=='28' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                
              if(($post['age_y']=='0' && $post['age_m']=='0' && $post['age_d']=='0') || ((empty($post['age_y']) && empty($post['age_m']) && empty($post['age_d']))) )
                    {
                         
                        $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|is_natural_no_zero|max_length[3]');
                    }
                //$this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
            }
        }
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
       
        $opd_particular_billing_list = $this->session->userdata('opd_particular_billing');
        if(!isset($opd_particular_billing_list) && empty($opd_particular_billing_list) && empty($post['data_id']))
        {
          $this->form_validation->set_rules('particular_id', 'particular_id', 'trim|callback_check_particular_id');
        }

      
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $balance_amount = $this->opd_billing->check_patient_balance($post['patient_id']);  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'type'=>$post['type'],
                                        'reciept_code'=>$post['reciept_code'],
                                        'pay_now'=>$post['pay_now'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        'patient_email'=>$post['patient_email'],
                                        'mobile_no'=>$post['mobile_no'],
                                        'gender'=>$post['gender'],
                                        'age_y'=>$post['age_y'],
                                        'age_m'=>$post['age_m'],
                                        'age_d'=>$post['age_d'],
                                        'address'=>$post['address'],
                                        'address_second'=>$post['address_second'],
                                        'address_third'=>$post['address_third'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'relation_type'=>$post['relation_type'],
                                        'relation_name'=>$post['relation_name'],
                                        'referral_doctor'=>$post['referral_doctor'],
                                        'ref_by_other'=>$post['ref_by_other'],
                                        'attended_doctor'=>$post['attended_doctor'],
                                        'diseases'=>$post['diseases'],
                                        'amount'=>$post['amount'],
                                        'payment_mode'=>$post['payment_mode'],
                                        'field_name'=>$total_values,
                                        //'transaction_no'=>$post['transaction_no'],
                                        //'cheque_no'=>$post['cheque_no'],
                                        //'branch_name'=>$post['branch_name'],
                                        'particulars'=>$post['particulars'],
                                        'quantity'=>$post['quantity'],
                                        'booking_date'=>$post['booking_date'],
                                        'total_amount'=>$post['total_amount'],
                                        'net_amount'=>$post['net_amount'],
                                        'paid_amount'=>$post['paid_amount'],
                                        'discount'=>$post['discount'],
                                        'balance'=>$balance_amount,
                                        //'cheque_date'=>$post['cheque_date'],
                                        'next_app_date'=>$post['next_app_date'],
                                       'kit_amount'=>$post['kit_amount'],
                                       'particulars_charges'=>$post['particulars_charges'],
                                       'package_id'=>$post['package_id'],
                                       'source_from'=>$post['source_from'],
                                       'remarks'=>$post['remarks'],

                                       'referral_hospital'=>$post['referral_hospital'],
                                       'referred_by'=>$post['referred_by'],
                                       'adhar_no'=>$post['adhar_no'],
                                       'booking_time'=>$post['booking_time'],
                                       );

                                         
            return $data['form_data'];
        }   
    }


    private function _validatebilling()
    {
        $field_list = mandatory_section_field_list(4);
         $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();
        //echo "<pre>"; print_r($post); exit;
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
        $this->form_validation->set_rules('simulation_id', 'Simulation', 'trim|required'); 
        $this->form_validation->set_rules('patient_name', 'Patient Name', 'trim|required');
          if(isset($post['field_name'])) 
          {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
          }
       	
         if(!empty($field_list)){ 
            if($field_list[0]['mandatory_field_id']=='27' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }
           
            if($field_list[1]['mandatory_field_id']=='28' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                
                if(($post['age_y']=='0' && $post['age_m']=='0' && $post['age_d']=='0') || ((empty($post['age_y']) && empty($post['age_m']) && empty($post['age_d']))) )
                    {
                         
                        $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|is_natural_no_zero|max_length[3]');
                    }
                //$this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
            }
        }
        /*if($post['payment_mode']=='cheque'){
                  $this->form_validation->set_rules('branch_name', 'bank name', 'trim|required');
                $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');
                 $this->form_validation->set_rules('cheque_date', 'cheque date', 'trim|required');

            }else if($post['payment_mode']=='card' || $post['payment_mode']=='neft'){
                  $this->form_validation->set_rules('transaction_no', 'transaction no', 'trim|required');
            }*/
        $this->form_validation->set_rules('gender', 'gender', 'trim|required'); 
       
        $opd_particular_billing_list = $this->session->userdata('opd_particular_billing');
        /*if(!isset($opd_particular_billing_list) && empty($opd_particular_billing_list))
        {
          $this->form_validation->set_rules('particular_id', 'particular_id', 'trim|callback_check_particular_id');
        } */  
        
        if ($this->form_validation->run() == FALSE) 
        {  
            
            $balance_amount ="";
            if(!empty($post['patient_id']))
            {
              $balance_amount = $this->opd_billing->check_patient_balance($post['patient_id']);    
            } 

            $opd_particular_payment_array = array('total_amount'=>$post['total_amount'], 'net_amount'=>$post['net_amount'], 'discount'=>$post['discount'],'kit_amount'=>'0.00','particulars_charges'=>number_format($post['total_amount'],2,'.',''));


          $this->session->set_userdata('opd_particular_payment', $opd_particular_payment_array);
            $pannel_type ='0';
            if(!empty($post['pannel_type']))
            {
              $pannel_type = $post['pannel_type'];
            }
            $polocy_no ='';
            if(!empty($post['polocy_no']))
            {
              $polocy_no = $post['polocy_no'];
            }
            $tpa_id ='';
            if(!empty($post['tpa_id']))
            {
              $tpa_id = $post['tpa_id'];
            }
            $ins_amount ='';
            if(!empty($post['ins_amount']))
            {
              $ins_amount = $post['ins_amount'];
            }

            $ins_authorization_no ='';
            if(!empty($post['ins_authorization_no']))
            {
              $ins_authorization_no = $post['ins_authorization_no'];
            }
            
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'patient_id'=>$post['patient_id'], 
                                        'patient_code'=>$post['patient_code'],
                                        'reciept_code'=>$post['reciept_code'],
                                        'simulation_id'=>$post['simulation_id'],
                                        'patient_name'=>$post['patient_name'],
                                        'mobile_no'=>$post['mobile_no'],
                                        'relation_type'=>$post['relation_type'],
                                        'relation_name'=>$post['relation_name'],
                                        'relation_simulation_id'=>$post['relation_simulation_id'],

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
                                        //'branch_name'=>$post['branch_name'],
                                       //'transaction_no'=>$post['transaction_no'],
                                       //'cheque_no'=>$post['cheque_no'], pannel_type
                                       'field_name'=>$total_values,
                                       'payment_mode'=>$post['payment_mode'],
                                       'diseases'=>$post['diseases'],
                                       'particulars'=>$post['particulars'],
                                       //'cheque_date'=>$post['cheque_date'],
                                       'next_app_date'=>$post['next_app_date'],
                                       'kit_amount'=>$post['kit_amount'],
                                       'particulars_charges'=>$post['particulars_charges'],
                                       'package_id'=>$post['package_id'],
                                       'source_from'=>$post['source_from'],
                                       'remarks'=>$post['remarks'],
                                       'referral_hospital'=>$post['referral_hospital'],
                                       'referred_by'=>$post['referred_by'],
                                       'adhar_no'=>$post['adhar_no'],
                                       'address_second'=>$post['address_second'],
                                       'address_third'=>$post['address_third'],
                                       'booking_time'=>$post['booking_time'],
                                       'pannel_type'=>$pannel_type,
                                       'polocy_no'=>$polocy_no,
                                       'tpa_id'=>$tpa_id,
                                       'ins_amount'=>$ins_amount,
                                       'ins_authorization_no'=>$ins_authorization_no,
                                       'token_type'=>$post['token_type'],



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
    public function particular_billing_list()
    {
        unauthorise_permission(85,121);
        $data['page_title'] = 'OPD Particular Billing list'; 
        $this->load->view('opd/particular_billing_list',$data);
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
      $billing_particular = $this->session->userdata('opd_particular_billing'); 

      
      $post = $this->input->post();
      $ins_company_id=$post['ins_company_id'];
      $panel_type=$post['panel_type'];
      $branch_id='';
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
        if($panel_type==0)
          {
  //print_r($billing_particular[$ses_billing_data['particular']]);die;
          foreach($billing_particular as $ses_billing_data)
            {
            	if(!empty($ses_billing_data['kit_amount']))
                {
                $kitamount = number_format($ses_billing_data['kit_amount'],2,'.','');
                }
              else
                {
                $kitamount ='0.00';
                }

                if(!empty($ses_billing_data['particulars_charges']))
                {
                $particulars_charges = number_format($ses_billing_data['particulars_charges'],2,'.','');
                }
              else
                {
                $particulars_charges ='0.00';
                }

            $panel_rate= $this->opd_billing->get_panel_price($branch_id,$ses_billing_data['particular'],$ins_company_id);
            $charge= $ses_billing_data['normal_amount'];
            //$charge_only='';
            $normal_amount=$ses_billing_data['normal_amount'];
            /* new code by mamta */
            if(!empty($ses_billing_data['particular'])) 
              {
              $discount=$post['discount'];
              $billing_particular[$ses_billing_data['particular']] = array('particular'=>$ses_billing_data['particular'], 'quantity'=>$ses_billing_data['quantity'], 'amount'=>$ses_billing_data['normal_amount'],'particulars'=>$ses_billing_data['particulars'],'kit_amount'=>$kitamount,'particulars_charges'=>$particulars_charges,'panel_charge'=>$charge_only,'panel_type'=>$panel_type,'normal_amount'=>$ses_billing_data['normal_amount']);
             // print_r($billing_particular[$ses_billing_data['particular']]);die;
              $amount_arr = array_column($billing_particular, 'amount'); 
              $total_amount = array_sum($amount_arr);
              $this->session->set_userdata('opd_particular_billing', $billing_particular);
              $html_data = $this->opd_perticuller_list();
              $total = $total_amount+$kitamount;
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
              if(!empty($ses_billing_data['kit_amount']))
                {
                  $kitamount = number_format($ses_billing_data['kit_amount'],2,'.','');
                }
              else
                {
                  $kitamount ='0.00';
                }
              }
            }
          }
        else
          {
          $billing_particular = $this->session->userdata('opd_particular_billing');
          foreach($billing_particular as $ses_billing_data)
          {
            $panel_rate= $this->opd_billing->get_panel_price($branch_id,$ses_billing_data['particular'],$ins_company_id);
            if(isset($panel_rate[0]->charge) && $panel_rate[0]->charge!='' && !empty($panel_rate) )
            {
              $charge= $panel_rate[0]->charge*$ses_billing_data['quantity'];
              $charge_only= $panel_rate[0]->charge;
              $normal_amount=$ses_billing_data['normal_amount'];
            }
            else
            {
              $charge= $ses_billing_data['amount'];
              $charge_only='';
              $normal_amount=$ses_billing_data['normal_amount'];
            }
            if(!empty($ses_billing_data['particular'])) 
            {
            	if(!empty($ses_billing_data['kit_amount']))
                {
                $kitamount = number_format($ses_billing_data['kit_amount'],2,'.','');
                }
              else
                {
                $kitamount ='0.00';
                }

                if(!empty($ses_billing_data['particulars_charges']))
                {
                $particulars_charges = number_format($ses_billing_data['particulars_charges'],2,'.','');
                }
              else
                {
                $particulars_charges ='0.00';
                }
              $discount=$post['discount'];
              if(in_array($ses_billing_data['particular'],$p_ids_arr))
              {
                 
              }
              else
              {
              $billing_particular[$ses_billing_data['particular']] = array('particular'=>$ses_billing_data['particular'], 'quantity'=>$ses_billing_data['quantity'], 'amount'=>$charge,'particulars'=>$ses_billing_data['particulars'],'kit_amount'=>$kitamount,'particulars_charges'=>$particulars_charges,'panel_charge'=>$charge_only,'panel_type'=>$panel_type,'normal_amount'=>$ses_billing_data['normal_amount']);
             $this->session->set_userdata('opd_particular_billing', $billing_particular);
         }


              $amount_arr = array_column($billing_particular, 'amount'); 
              $total_amount = array_sum($amount_arr);
              


              $html_data = $this->opd_perticuller_list();
              $total = $total_amount+$kitamount;
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
              if(!empty($ses_billing_data['kit_amount']))
              {
                $kitamount = number_format($ses_billing_data['kit_amount'],2,'.','');
              }
              else
              {
                $kitamount ='0.00';
              }

            }
          }
          }
        /* session for panel */
         
        }
      else
        {
          $billing_particular = [];
        }
      /* for panel rate */
    

      if(!empty($post['particular'])) /* new code by mamta */
        {
         if(!empty($panel_type))
        {
          $panel_rate= $this->opd_billing->get_panel_price($branch_id,$post['particular'],$ins_company_id);

        if($post['panel_type']==1)
          {
          if(!empty($panel_rate) && $panel_rate[0]->charge!='')
            {
              $charge= $panel_rate[0]->charge*$post['quantity'];
              $charge_only= $panel_rate[0]->charge;
             
            }
          else
            {

              $charge= $post['amount'];
             
              $charge_only='';
            }
          }
        else
          {
            $charge= $post['amount'];
           
            $charge_only='';
          }
        }  /* for panel rate */
      else
        {
          $charge= $post['amount'];
         
        }

        if(!empty($post['kit_amount']))
                {
                $kitamount = number_format($post['kit_amount'],2,'.','');
                }
              else
                {
                $kitamount ='0.00';
                }
                if(!empty($post['particulars_charges']))
                {
                $particulars_charges = number_format($post['particulars_charges'],2,'.','');
                }
              else
                {
                $particulars_charges ='0.00';
                }

          $billing_particular[$post['particular']] = array('particular'=>$post['particular'], 'quantity'=>$post['quantity'], 'amount'=>$charge,'particulars'=>$post['particulars'],'kit_amount'=>$kitamount,'particulars_charges'=>$particulars_charges,'panel_charge'=>$charge_only,'panel_type'=>$panel_type,'normal_amount'=>$post['amount']);
        $amount_arr = array_column($billing_particular, 'amount'); 
        $total_amount = array_sum($amount_arr);
        $this->session->set_userdata('opd_particular_billing', $billing_particular);

        $html_data = $this->opd_perticuller_list();
        $total = $total_amount+$post['kit_amount'];
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
        if(!empty($post['kit_amount']))
          {
            $kitamount = number_format($post['kit_amount'],2,'.','');
          }
        else
          {
            $kitamount ='0.00';
          }
        }
        $response_data = array('html_data'=>$html_data, 'total_amount'=>$totamount, 'net_amount'=>$netamount, 'discount'=>$discamount,'kit_amount'=>$kitamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
        $opd_particular_payment_array = array('total_amount'=>$totamount, 'net_amount'=>$netamount, 'discount'=>$discamount,'kit_amount'=>$kitamount,'particulars_charges'=>number_format($total_amount,2,'.',''));
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
           $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''), 'discount'=>number_format($discount,2,'.',''), 'balance'=>number_format($balance,2,'.',''), 'paid_amount'=>number_format($paid_amount,2,'.',''));
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
           //foreach($post['particular_id'] as $post_perticuler)
           foreach($opd_particular_billing as $key=>$perticuller_ids)
           { 
           	  if(in_array($perticuller_ids['particular'],$post['particular_id']))
           	  {  
                 unset($opd_particular_billing[$key]);
           	  }
           }  
      $kit_amount = $post['kit_amount'];
      $discount = $post['discount'];
      $paid_amount = $post['paid_amount'];
			$amount_arr = array_column($opd_particular_billing, 'amount'); 
			$total_amount = array_sum($amount_arr);

      //echo "<pre>";print_r($opd_particular_billing); exit;

			$this->session->set_userdata('opd_particular_billing',$opd_particular_billing);
			$html_data = $this->opd_perticuller_list();
			
      $particulars_charges = $total_amount;
      $bill_total = $total_amount+$kit_amount;
      $net_amount = $bill_total-$discount;

      $response_data = array('html_data'=>$html_data,'particulars_charges'=>$particulars_charges,'kit_amount'=>$kit_amount, 'total_amount'=>$bill_total, 'net_amount'=>$net_amount, 'discount'=>$discount);

       /*$opd_particular_payment_array = array('total_amount'=>$bill_total, 'net_amount'=>$bill_total-$post['discount'], 'discount'=>$post['discount'],'kit_amount'=>$post['kit_amount'],'particulars_charges'=>$total_amount);


          $this->session->set_userdata('opd_particular_payment', $opd_particular_payment_array);
*/
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


    public function print_billing_report($id="",$branch_id='')
    {
      
      $data['page_title'] = "Print Bookings";
      $booking_id= $this->session->userdata('opd_booking_id');
      
      //die;
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
      $get_by_id_data = $this->opd_billing->get_all_detail_print($booking_id,$branch_id);
      //print"<pre>";print_r($get_by_id_data);
      //die;
      $template_format = $this->opd_billing->template_format(array('section_id'=>1,'types'=>1),$branch_id);

      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      /*$this->load->model('packages/packages_model','package');
      $selected_medicine_result = $this->package->selected_medicine($package_id);*/
		  //$medicine_arr = array();
      /*if(!empty($selected_medicine_result))
      {             
      	 foreach($selected_medicine_result as $medicine_data)
      	 {  
      	      $medicine_arr[] = $medicine_data->id; 
      	 }
      }
      $data['medicine_ids']=$medicine_arr;*/
      $data['template_data']=$template_format;
      $data['all_detail']= $get_by_id_data;
      $data['page_type']='';
      
     $this->load->model('general/general_model'); 
     $transaction_id=$this->general_model->get_transaction_id($booking_id,4,8);   //4 section id and 8 type
     $data['transaction_id']=$transaction_id[0]->field_value;
      
      $this->load->view('opd_billing/print_template_opd',$data);
    }


    public function print_consolidate_billing_report($id="",$branch_id='')
    {
      
      $data['page_title'] = "Print Booking";
      $booking_id= $this->session->userdata('opd_booking_id');
      
      //die;
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
      $get_by_id_data = $this->opd_billing->get_all_detail_print($booking_id,$branch_id);

      $payment_list = $this->opd_billing->payment_list($booking_id,$branch_id);
      
      if(!empty($payment_list) && count($payment_list)>1)
      {
        $table = '<table width="100%" cellpadding="0" cellspacing="0" style="float: left; z-index: 9999; position: absolute; left: 50px; width: 390px; margin-top:20px; " border="1">
                  <thead>
                      <tr>
                        <th colspan="5" style="text-align:center;">Payment Details</th>
                      </tr>
                      <tr>
                        <th>S.No.</th>
                        <th>Date</th>
                        <th>Reciept No.</th>
                        <th>Payment Mode</th>
                        <th>Amount</th>
                      </tr>  
                  </thead>';
          $i=1;        
          foreach($payment_list as $payment)
          {
             if(!empty($payment['debit']) && $payment['debit']>0)
             {
             $table .= '<tbody>
                          <tr>  
                              <td>'.$i.'</td>
                              <td>'.date('d-m-Y', strtotime($payment['created_date'])).'</td>
                              <td>'.$payment['reciept_prefix'].$payment['reciept_suffix'].'</td>
                              <td>'.$payment['payment_mode'].'</td>
                              <td>'.$payment['debit'].'</td>
                          </tr>    
                        </tbody>';  
            $i++;          
            }  
          }    
             $table .= '</table>'; 
             $get_by_id_data['opd_list'][0]->payment_mode = $get_by_id_data['opd_list'][0]->payment_mode.'<br/>'.$table;   
             $data['total_payment'] = $this->opd_billing->total_payment($booking_id,$branch_id,4);
            $get_by_id_data['opd_list'][0]->paid_amount = $data['total_payment']['total_debit'];
            $get_by_id_data['opd_list'][0]->balance = $data['total_payment']['total_balance'];
      }
 
      
      //print"<pre>";print_r($get_by_id_data);
      //die;
      $template_format = $this->opd_billing->template_format(array('section_id'=>1,'types'=>1),$branch_id);

      //Package
      $package_id = $get_by_id_data['opd_list'][0]->package_id;
      /*$this->load->model('packages/packages_model','package');
      $selected_medicine_result = $this->package->selected_medicine($package_id);*/
      //$medicine_arr = array();
      /*if(!empty($selected_medicine_result))
      {             
         foreach($selected_medicine_result as $medicine_data)
         {  
              $medicine_arr[] = $medicine_data->id; 
         }
      }
      $data['medicine_ids']=$medicine_arr;*/
      $data['template_data']=$template_format;
      $data['all_detail']= $get_by_id_data;
      $data['page_type']='';
      
     $this->load->model('general/general_model'); 
     $transaction_id=$this->general_model->get_transaction_id($booking_id,4,8);   //4 section id and 8 type
     $data['transaction_id']=$transaction_id[0]->field_value;
      
      $this->load->view('opd_billing/print_template_opd',$data);
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
      
      $data['all_detail']= $get_by_id_data;
      //print '<pre>';print_r($get_by_id_data); exit;
      //print '<pre>';print_r($data['all_detail']); exit;
      $this->load->view('opd_billing/print_blank_template_opd',$data);
    }

    


    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['referal_doctor_list'] = $this->opd_billing->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd_billing->attended_doctor_list();
        $data['specialization_list'] = $this->general_model->specialization_list();  
        $data['source_list'] = $this->opd_billing->source_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list(); 
        $data['form_data'] = array(
                                    "start_date"=>'',//date('d-m-Y')
                                    "end_date"=>'',//date('d-m-Y')
                                    "booking_from_date"=>'',
                                    "booking_to_date"=>'',
                                    "appointment_from_date"=>'',
                                    "appointment_to_date"=>'',
                                    "reciept_code"=>"", 
                                    'patient_code'=>'',
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
                                    "specialization"=>"",
                                    "attended_doctor"=>"",
                                    "patient_email"=>"",
                                    "start_time"=>"",
                                    "end_time"=>"",
                                    "booking_status"=>"",
                                    "status"=>"", 
                                    "remark"=>"",
                                    "branch_id"=>'',
                                    "disease"=>'',
                                    "disease_code"=>'',
                                    "source_from"=>'',
                                    "insurance_type"=>"0",
                                    "insurance_type_id"=>"",
                                    "ins_company_id"=>"",
                                    "adhar_no"=>'',
                                    
                                  );
        if(isset($post) && !empty($post))
        {
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('opd_billing_search', $marge_post);
        }
        $opd_billing_search = $this->session->userdata('opd_billing_search');
        if(isset($opd_search) && !empty($opd_billing_search))
        {
            $data['form_data'] = $opd_billing_search;
        }
        $this->load->view('opd_billing/advance_search',$data);
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
        $this->session->unset_userdata('opd_billing_search');
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


    public function opd_billing_excel()
    {
        // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Receipt No','Patient Name','Mobile No.','Doctor Name','Billing Date');
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
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
          $list = $this->opd_billing->search_opd_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $opds)
               {
                    $attended_doctor_name ="";
                    $attended_doctor_name = get_doctor_name($opds->attended_doctor);
                    $specialization_id = get_specilization_name($opds->specialization_id);
                    $booking_code = $opds->booking_code;
                    $patient_name = $opds->patient_name;
                    $booking_date = date('d-m-Y',strtotime($opds->booking_date));
                    $genders = array('0'=>'Female','1'=>'Male','2'=>'Others');
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
                    array_push($rowData,$opds->reciept_code,$opds->patient_name,$opds->mobile_no,$attended_doctor_name,$booking_date);
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
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=billing_list_".time().".xls");  
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
          }
      

      
    }

    public function opd_billing_csv()
    {
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Receipt No','Patient Name','Mobile No.','Doctor Name','Billing Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->opd_billing->search_opd_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $opds)
               {
                    $attended_doctor_name ="";
                    $attended_doctor_name = get_doctor_name($opds->attended_doctor);
                    $specialization_id = get_specilization_name($opds->specialization_id);
                    $booking_code = $opds->booking_code;
                    $patient_name = $opds->patient_name;
                    $booking_date = date('d-m-Y',strtotime($opds->booking_date));
                    $genders = array('0'=>'Female','1'=>'Male','2'=>'Others');
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
                    array_push($rowData,$opds->reciept_code,$opds->patient_name,$opds->mobile_no,$attended_doctor_name,$booking_date);
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
         header("Content-Disposition: attachment; filename=OPD_billing_list_".time().".csv");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
                ob_end_clean();
               $objWriter->save('php://output');
          }
      
    }

    public function opd_billing_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->opd_billing->search_opd_data();
        $this->load->view('opd_billing/opd_billing_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("opd_billing_list_".time().".pdf");
    }
    
    public function opd_billing_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->opd_billing->search_opd_data();
      $this->load->view('opd_billing/opd_billing_html',$data); 
    }


    public function package_rate()
    {
       $post = $this->input->post();
       //print_r($post); exit;
       if(isset($post) && !empty($post) && !empty($post['package_id']))
       {
         
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
         $particulars_charges = $post['particulars_charges'];

         $balance = number_format($particulars_charges+$kit_amount-$post['discount']-$post['paid_amount'],2,'.', ''); 
         $amount_arr = array('kit_amount'=>number_format($kit_amount,2,'.',''),'particulars_charges'=>number_format($particulars_charges,2,'.',''),'total_amount'=>number_format($particulars_charges+$kit_amount,2,'.', ''),'discount'=>number_format($discount,2,'.', ''),'net_amount'=>number_format($kit_amount+$particulars_charges-$post['discount'],2,'.', ''),'paid_amount'=>number_format($kit_amount+$particulars_charges-$post['discount'],2,'.', ''),'balance'=>$balance);

         $json = json_encode($amount_arr,true);
         echo $json;
         
       }
    }

    public function update_amount()
    {
       $post = $this->input->post();
       if(isset($post) && !empty($post))
       {
          $particulars_charges = $post['particulars_charges'];
          $kit_amount = $post['kit_amount'];
          $discount = $post['discount'];
          $total_amount=$particulars_charges+$kit_amount;
          $net_amount = $total_amount-$discount;
          $paid_amount = $net_amount;
          $pay_arr = array('particulars_charges'=>number_format($particulars_charges,2,'.', ''),'kit_amount'=>number_format($kit_amount,2,'.', ''),'total_amount'=>number_format($total_amount,2,'.', ''), 'net_amount'=>number_format($net_amount,2,'.', ''), 'discount'=>number_format($discount,2,'.', ''));
          $json = json_encode($pay_arr,true);
          echo $json;
         
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
    if(!empty($get_payment_detail)){
      foreach($get_payment_detail as $payment_detail)
      {

      if(!empty($error_field))
      {

      $var_form_error= $error_field; 
      }


      $html.='<div class="row m-b-5" id="branch"> <div class="col-xs-4">
      <strong>'.$payment_detail->field_name.'<span class="star">*</span></strong></div><div class="col-xs-8"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>';
      }

       echo $html;exit;
    }
   

    }  

    public function particulars_list_pannel($particulars_id="",$ins_company_id="")
    {


      //echo $particulars_id;
      //echo '<br>';
      //echo $ins_company_id;

        if(!empty($particulars_id))
        {
          $branch_id='';
            $particulars_list =$this->opd_billing->get_panel_price($branch_id,$particulars_id,$ins_company_id);
            //print '<pre>'; print_r($particulars_list);die;
            if(!empty($particulars_list))
            {
                $charge = '';
                foreach($particulars_list as $particulars)
                {
                   $charge = $particulars->charge;
                }
            }
        }
            $pay_arr = array('charges'=>$charge, 'amount'=>$charge,'quantity'=>1);
            $json = json_encode($pay_arr,true);
            echo $json;
    }


    public function dental_particular_payment_calculation()
    {
    //print_r($this->session->all_userdata()); 
        $post = $this->input->post();
        //print_r($post)
        $ins_company_id=$post['ins_company_id'];
        $panel_type=$post['panel_type'];
        $branch_id='';
        $charge='';
        $charge_only='';
       
    if(isset($post) && !empty($post))
      {   
       $billing_dental_particular = $this->session->userdata('dental_opd_particular_billing');
      	 $p_ids_arr=array();
         if(isset($billing_dental_particular))
         {
            foreach($billing_dental_particular as $p_ids)
            {
              $p_ids_arr[]= $p_ids['particular'];
            }
         }
       
      if(isset($billing_dental_particular) && !empty($billing_dental_particular))
        {
        /* session for panel */
        if($panel_type==0)
          {
            
             //$this->session->unset_userdata('dental_opd_particular_billing');


           foreach($billing_dental_particular as $ses_billing_data)
            {
            	// echo"hii1";echo"<pre>";
            	
            	if(!empty($ses_billing_data['kit_amount']))
                {
                $kitamount = number_format($ses_billing_data['kit_amount'],2,'.','');
                }
              else
                {
                $kitamount ='0.00';
                }

                if(!empty($ses_billing_data['particulars_charges']))
                {
                $particulars_charges = number_format($ses_billing_data['particulars_charges'],2,'.','');
                }
              else
                {
                $particulars_charges ='0.00';
                }
	            $charge= $ses_billing_data['normal_amount'];
	            $charge_only='';
	            $normal_amount=$ses_billing_data['normal_amount'];
            /* new code by mamta */
            if(!empty($ses_billing_data['particular'])) 
              {
              $discount=$post['discount'];
             
              if(in_array($ses_billing_data['particular'],$p_ids_arr))
              {
                 
              }
              else
              {
              	
               $billing_dental_particular[$ses_billing_data['particular']] = array('particular'=>$ses_billing_data['particular'], 'quantity'=>$ses_billing_data['quantity'], 'amount'=>$charge,'particulars'=>$ses_billing_data['particulars'],'kit_amount'=>$kitamount,'particulars_charges'=>$particulars_charges,'panel_charge'=>$charge_only,'panel_type'=>$panel_type,'tooth_num'=>$ses_billing_data['tooth_num'],'tooth_num_val'=>$ses_billing_data['tooth_num_val'],'normal_amount'=>$ses_billing_data['normal_amount']);
                $this->session->set_userdata('dental_opd_particular_billing', $billing_dental_particular);
              }
              


              $amount_arr = array_column($billing_dental_particular, 'amount'); 
              $total_amount = array_sum($amount_arr);

              


              $html_data = $this->opd_perticuller_list();
              $total = $total_amount+$kitamount;
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
              if(!empty($ses_billing_data['kit_amount']))
                {
                $kitamount = number_format($ses_billing_data['kit_amount'],2,'.','');
                }
              else
                {
                $kitamount ='0.00';
                }
              }
            
    		}

          }


        else
          {
          foreach($billing_dental_particular as $ses_billing_data)
            {
            	
            $panel_rate= $this->opd_billing->get_panel_price($branch_id,$ses_billing_data['particular'],$ins_company_id);
            if($ses_billing_data['panel_type']==1)
              {
              if($panel_rate[0]->charge!='' && !empty($panel_rate) )
                {
                  $charge= $panel_rate[0]->charge*$ses_billing_data['quantity'];
                  $charge_only= $panel_rate[0]->charge;
                  $normal_amount=$ses_billing_data['normal_amount'];
                }
              else
                {
                  $charge= $ses_billing_data['amount'];
                  $charge_only='';
                  $normal_amount=$ses_billing_data['normal_amount'];
                }
              }
            else
              {
                $charge= $ses_billing_data['amount'];
                $charge_only='';
                $normal_amount=$ses_billing_data['normal_amount'];
              }
            /* new code by mamta */
          if(!empty($ses_billing_data['particular'])) 
            {
            	// echo"hii13";echo"<pre>";
            	// print_r($ses_billing_data);
              if(!empty($ses_billing_data['kit_amount']))
                {
                $kitamount = number_format($ses_billing_data['kit_amount'],2,'.','');
                }
              else
                {
                $kitamount ='0.00';
                }

                if(!empty($ses_billing_data['particulars_charges']))
                {
                $particulars_charges = number_format($ses_billing_data['particulars_charges'],2,'.','');
                }
              else
                {
                $particulars_charges ='0.00';
                }

              $discount=$post['discount'];
              $billing_dental_particular[$ses_billing_data['particular']] = array('particular'=>$ses_billing_data['particular'], 'quantity'=>$ses_billing_data['quantity'], 'amount'=>$charge,'particulars'=>$ses_billing_data['particulars'],'kit_amount'=>$kitamount,'tooth_num'=>$ses_billing_data['tooth_num'],'tooth_num_val'=>$ses_billing_data['tooth_num_val'],'particulars_charges'=>$particulars_charges,'panel_charge'=>$charge_only,'panel_type'=>$panel_type,'normal_amount'=>$ses_billing_data['normal_amount']);
              $amount_arr = array_column($billing_dental_particular, 'amount'); 
              $total_amount = array_sum($amount_arr);
              $this->session->set_userdata('dental_opd_particular_billing', $billing_dental_particular);
              $html_data = $this->opd_perticuller_list();
              $total = $total_amount+$kitamount;
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
              if(!empty($ses_billing_data['kit_amount']))
                {
                  $kitamount = number_format($ses_billing_data['kit_amount'],2,'.','');
                }
              else
                {
                  $kitamount ='0.00';
                }
              }
            }
          }
        /* session for panel */
        }
      else
        {
          $billing_dental_particular = [];
        }
      /* for panel rate */
     
        if(!empty($post['particular']))
        {

         if(!empty($panel_type))
        {
        $panel_rate= $this->opd_billing->get_panel_price($branch_id,$post['particular'],$ins_company_id);
        if($post['panel_type']==1)
        {
        if(!empty($panel_rate) && $panel_rate[0]->charge!='')
          {
            $charge= $panel_rate[0]->charge*$post['quantity'];
            $charge_only= $panel_rate[0]->charge;
            $normal_amount='';
          }
        else
          {
            $charge= $post['amount'];
            $normal_amount=$post['amount'];
            $charge_only='';
          }
        }
        else
        {
          $charge= $post['amount'];
          $normal_amount=$post['amount'];
          $charge_only='';
        }
        }  /* for panel rate */
        else
        {
        $charge= $post['amount'];
        $normal_amount=$post['amount'];
        }

         if(!empty($post['kit_amount']))
                {
                $kitamount = number_format($post['kit_amount'],2,'.','');
                }
              else
                {
                $kitamount ='0.00';
                }
                if(!empty($post['particulars_charges']))
                {
                $particulars_charges = number_format($post['particulars_charges'],2,'.','');
                }
              else
                {
                $particulars_charges ='0.00';
                }


            $billing_dental_particular[$post['particular']] = array('particular'=>$post['particular'], 'quantity'=>$post['quantity'], 'amount'=>$charge,'particulars'=>$post['particulars'],'kit_amount'=>$kitamount,'particulars_charges'=>$particulars_charges,'tooth_num'=>$post['tooth_num'],'tooth_num_val'=>$post['tooth_num_val'],'panel_charge'=>$charge_only,'panel_type'=>$panel_type,'normal_amount'=>$normal_amount);
        $amount_arr = array_column($billing_dental_particular, 'amount'); 
        $total_amount = array_sum($amount_arr);

        $this->session->set_userdata('dental_opd_particular_billing', $billing_dental_particular);


        $html_data = $this->dental_opd_perticuller_list();
        $total = $total_amount+$post['kit_amount'];
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
        if(!empty($post['kit_amount']))
          {
            $kitamount = number_format($post['kit_amount'],2,'.','');
          }
        else
          {
            $kitamount ='0.00';
          }
        }
      
      $response_data = array('html_data'=>$html_data, 'total_amount'=>$totamount, 'net_amount'=>$netamount, 'discount'=>$discamount,'kit_amount'=>$kitamount,'particulars_charges'=>number_format($total_amount,2,'.',''));

      $opd_particular_payment_array = array('total_amount'=>$totamount, 'net_amount'=>$netamount, 'discount'=>$discamount,'kit_amount'=>$kitamount,'particulars_charges'=>number_format($total_amount,2,'.',''));


      $this->session->set_userdata('dental_opd_particular_payment', $opd_particular_payment_array);
      $json = json_encode($response_data,true);
      echo $json;
      }
    }

    private function dental_opd_perticuller_list()
    {
    	
      $particular_data_dental = $this->session->userdata('dental_opd_particular_billing');

     //die;
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
                     <input name="selectall_dental" class="" id="selectall_dental" onClick="toggle_dental(this);" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Particular</th>
                    <th>Charges</th>
                     <th>Tooth No.</th>
                  </tr></thead>';  
           if(isset($particular_data_dental) && !empty($particular_data_dental))
           {
              $i = 1;
              foreach($particular_data_dental as $particular_dental)
              {
                //print_r($particular_dental);
                //;
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="particular_id[]" class="part_checkbox_dental booked_checkbox_dental" value="'.$particular_dental['particular'].'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$particular_dental['particulars'].'</td>
                            <td>'.$particular_dental['amount'].'</td>
                            <td>'.$particular_dental['tooth_num_val'].'</td>
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
    

     public function remove_opd_particular_dental()
    {
       $post =  $this->input->post();
       //print_r($post);

       if(isset($post['particular_id']) && !empty($post['particular_id']))
       {
           $dental_opd_particular_billing = $this->session->userdata('dental_opd_particular_billing'); 
           //print_r($dental_opd_particular_billing);
           //die;
           if((!empty($dental_opd_particular_billing)) &&(isset($dental_opd_particular_billing)))
				{
              $dental_opd_particular_billing=$dental_opd_particular_billing;

				}
				else
				{
					$dental_opd_particular_billing='';
				}
           $particular_id_list = array_column($dental_opd_particular_billing, 'particular'); 
           //foreach($post['particular_id'] as $post_perticuler)
           foreach($dental_opd_particular_billing as $key=>$perticuller_ids)
           { 
              if(in_array($perticuller_ids['particular'],$post['particular_id']))
              {  
                 unset($dental_opd_particular_billing[$key]);
              }
           }  
      $kit_amount = $post['kit_amount'];
      $discount = $post['discount'];
      $paid_amount = $post['paid_amount'];
      $amount_arr = array_column($dental_opd_particular_billing, 'amount'); 
      $total_amount = array_sum($amount_arr);

      //echo "<pre>";print_r($opd_particular_billing); exit;

      $this->session->set_userdata('dental_opd_particular_billing',$dental_opd_particular_billing);
      $html_data = $this->dental_opd_perticuller_list();
      
      $particulars_charges = $total_amount;
      $bill_total = $total_amount+$kit_amount;
      $net_amount = $bill_total-$discount;

      $response_data = array('html_data'=>$html_data,'particulars_charges'=>$particulars_charges,'kit_amount'=>$kit_amount, 'total_amount'=>$bill_total, 'net_amount'=>$net_amount, 'discount'=>$discount);

      
      $json = json_encode($response_data,true);
      echo $json;
       }
    }
  
  
  function get_patient_detail_no_mobile($mobile)
   {
    $this->load->model('test/test_model','test');
     $patient_data = $this->test->get_by_mobile_no($mobile);
     //echo "<pre>"; print_r($patient_data); die;
     $html="";
     if(!empty($patient_data))
     { 
      //$html .='<select name="patient_id" id="patient_id" class="form-control" onchange="get_patient_id()">';
       foreach($patient_data as $patient_list)
       {
          $html.='<input type="radio" value="'.$patient_list->id.'" class="term" name="patient_id"> '.$patient_list->patient_name.'<br>';
       }
           $data=array('st'=>1,'patient_list'=>$html);
            echo json_encode($data);
            return false;
            
     }
    else{
            $data=array('st'=>0);
            echo json_encode($data);
            return false;
          }
   
 }

 function get_patient_detail_byid($patient_id)
   {
    $this->load->model('test/test_model','test');
     $patient_data = $this->test->get_patient_byid($patient_id);
     //print_r($patient_data);die;
     if(!empty($patient_data))
          {
            $data=array('st'=>1,'patient_detail'=>$patient_data);
            echo json_encode($data);
            return false;
          }
      else
          {
            $data=array('st'=>0);
            echo json_encode($data);
            return false;
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
         $token_type=$this->opd_billing->get_token_setting();
         //print_r($token_type);
         $token_type=$token_type['type'];
         //echo $type;die;
          if($token_type=='0') //hospital wise token no
          { 
            
           $patient_token_details_for_hospital=$this->opd_billing->get_patient_token_details_for_type_hospital($booking_date,$token_type);
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
            $patient_token_details_for_doctor=$this->opd_billing->get_patient_token_details_for_type_doctor($doctor_id,$booking_date,$token_type);
                //print_r($patient_token_details_for_doctor);die;

          if($patient_token_details_for_doctor['token_no']>'0')
          {
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
            $patient_token_details_for_specialization=$this->opd_billing->get_patient_token_details_for_type_specialization($specilization_id,$booking_date,$token_type);
              //  print_r($patient_token_details_for_doctor);die;

          if($patient_token_details_for_specialization['token_no']>'0')
          {
            $token_no=$patient_token_details_for_specialization['token_no']+1;
          }
          else
          {
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
    
    
     public function generate_token_date()
    {

       $post = $this->input->post();
       $token_no='';
       if(isset($post) && !empty($post))
       {
         $branch_id = $post['branch_id'];
         $doctor_id = $post['doctor_id'];
         $booking_date = date("Y-m-d", strtotime($post['booking_date']) ); 
         $token_type=$this->opd_billing->get_token_setting();
         //print_r($token_type);
         $token_type=$token_type['type'];
         //echo $type;die;
          if($token_type=='0') //hospital wise token no
          { 
            
           $patient_token_details_for_hospital=$this->opd_billing->get_patient_token_details_for_type_hospital($booking_date,$token_type);
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
            $patient_token_details_for_doctor=$this->opd_billing->get_patient_token_details_for_type_doctor($doctor_id,$booking_date,$token_type);
                //print_r($patient_token_details_for_doctor);die;

          if($patient_token_details_for_doctor['token_no']>'0')
          {
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
            $patient_token_details_for_specialization=$this->opd_billing->get_patient_token_details_for_type_specialization($specilization_id,$booking_date,$token_type);
              //  print_r($patient_token_details_for_doctor);die;

          if($patient_token_details_for_specialization['token_no']>'0')
          {
            $token_no=$patient_token_details_for_specialization['token_no']+1;
          }
          else
          {
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
    
    
    public function print_barcode($id)
    {
        $patient_data = $this->opd_billing->get_by_id($id); 
        $data['barcode_id'] = $patient_data['reciept_code'];
        if(!empty($data['barcode_id']))
        {
            $this->load->view('patient/barcode',$data);
        }
    }
    
    
    
}
?>