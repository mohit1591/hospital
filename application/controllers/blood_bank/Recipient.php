<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Recipient extends CI_Controller 
{
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
        $this->load->model('blood_bank/recipient/Recipient_model','recipient'); 
        $this->load->library('form_validation');
    }

  public function index()
  {
    unauthorise_permission('262','1506');
    $this->session->unset_userdata('recipient_search');
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $data['blood_groups']=$this->blood_general_model->get_blood_group_list(); 
    $data['component_list']=$this->blood_general_model->get_component_list();
    $this->load->model('default_search_setting/default_search_setting_model'); 
    $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
            //$requirement_date = date('d-m-Y');
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
            //$requirement_date = date('d-m-Y');
        }
        // End Defaul Search
         $data['form_data'] = array('recipient_id'=>'','mobile'=>'','blood_group'=>'','component_id'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'requirement_date'=>''); 
    $data['page_title'] = 'Recipient List'; 
    $this->load->view('blood_bank/recipient/list',$data);
  }

  // Function to show list of blood Groups starts here
  public function ajax_list()
  {
     unauthorise_permission('262','1506');
    $users_data = $this->session->userdata('auth_users');
    $list = $this->recipient->get_datatables();  
    $data = array();
    $no = $_POST['start'];
    $i = 1;
    $total_num = count($list);
    foreach ($list as $recipient) 
    {
      $no++;
        $row = array();
        if($recipient->status==1)
        {
          $status = '<font color="green">Active</font>';
        }   
        else
        {
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
        ////////// Check list end ///////////// 
        $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$recipient->id.'">'.$check_script; 
        $row[] = $recipient->patient_code;   
        $row[] = $recipient->patient_name;    
        $row[] = $recipient->mobile_no;
        $row[] = $recipient->gender;
        $row[] = $recipient->blood_group; 
       // $row[] = $recipient->clinical_diagnosis; 
        $row[] = ( strtotime($recipient->requirement_date) > 0 ? date('d-M-Y', strtotime($recipient->requirement_date)) : ''); 
       // $row[] = $recipient->bag_type;
        $row[] = $status;
        $row[] = date('d-M-Y H:i A',strtotime($recipient->created_date)); 
       
      $btnedit='';
      $btndelete='';
      $actions='';
      if(in_array('1508',$users_data['permission']['action']))
      {
           $btnedit = ' <a onClick="return edit_recipient_details('.$recipient->id.');"  href="javascript:void(0)" style="'.$recipient->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
      }
      if(in_array('1509',$users_data['permission']['action']))
      {
           $btndelete = ' <a  onClick="return delete_recipient_type('.$recipient->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
      }

      $transfusion_url = "'".base_url('blood_bank/recipient/print_transfusion/'.$recipient->patient_id.'/'.$recipient->id)."'";
        $btn_transfusion = '<li><a class="" href="javascript:void(0)" onClick="return print_window_page('.$transfusion_url.')"  title="Print" ><i class="fa fa-print"></i> Print Transfusion </a></li>';
        
        
        $compatilbilty_url = "'".base_url('blood_bank/recipient/print_compatilbilty/'.$recipient->patient_id.'/'.$recipient->id)."'";
        $btn_compatilbilty = '<li><a class="" href="javascript:void(0)" onClick="return print_window_page('.$compatilbilty_url.')"  title="Print" ><i class="fa fa-print"></i> Print Compatilbilty </a></li>';


      /* cross matching */
      $crossmatching='';

      $cross_match_url=base_url('blood_bank/recipient/cross_match_page/'.$recipient->patient_id.'/'.$recipient->id);
       if(in_array('1749',$users_data['permission']['action']))
      {
      $crossmatching = '<div class="btn-ipd" style="margin-top: 0px;margin-right: 4px;"><a class="btn-custom" href="'.$cross_match_url.'" style="5525" title="Cross Matching"><i class="fa fa-plus"></i>Cross Matching</a></div>';
    }
      /* cross matching */
      $issue_comp='';
       if(in_array('1514',$users_data['permission']['action']))
      {
        $issue_comp = ' <a onClick="return issue_recipient_details('.$recipient->id.');" class="btn-custom" href="javascript:void(0)" style="'.$recipient->id.'" title="Edit"><i class="fa fa-eye" aria-hidden="true"></i> Issue Component</a>';
      }
      
      /* if(in_array('1514',$users_data['permission']['action']))
      {
        $print_pdf_url = "'".base_url('blood_bank/recipient/print_issue_recipt/'.$recipient->id)."'";
        $btn_print = '<a  href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print Reciept </a>';
        
      }
      */
      $print_advance_print = ' <a onClick="return print_advance_report('.$recipient->id.');"  href="javascript:void(0)" style="'.$recipient->id.'" title="Print"><i class="fa fa-print"></i> Print Report</a>';
      
      
       $issue_data=$this->recipient->get_recipient_issued_components($recipient->id);
         
         //echo "<pre>"; print_r($issue_data); exit;
      
      if($issue_data!='empty')
      {
            $send_email ='<a  href="javascript:void(0)" class="" onClick="return send_email('.$recipient->id.')">
                <i class="fa fa-envelope"></i> Email
           </a>';
           
          if(in_array('1514',$users_data['permission']['action']))
          {
            $print_pdf_url = "'".base_url('blood_bank/recipient/print_issue_recipt/'.$recipient->id)."'";
            $btn_print = '<li><a class="" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print Reciept </a></li>';
            
            
            $print_invoice_url = "'".base_url('blood_bank/recipient/print_invoice/'.$recipient->id)."'";
            $btn_invoice_print = '<li><a class="" href="javascript:void(0)" onClick="return print_window_page('.$print_invoice_url.')"  title="Print" ><i class="fa fa-print"></i> Print Invoice </a></li>';
            
          }
          
      }
      else
      {
           $send_email ='';
           $btn_print = '';
      }
      
      
      
     

      $btn_a = '<div class="slidedown">
        <button disabled class="btn-custom">More <span class="caret"></span></button>
        <ul class="slidedown-content">
          '.$btnedit.$btndelete.$btn_compatilbilty.$btn_transfusion.$btn_print.$btn_invoice_print.$print_advance_print.$send_email.'
        </ul>
      </div> ';
       $row[] = $crossmatching.$issue_comp.$btn_a;
      //$row[] = $btnedit.$btndelete.$crossmatching.$issue_comp.$btn_print.$print_advance_print.$btn_compatilbilty.$btn_transfusion;
      $data[] = $row;
      $i++;
    }
    $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->recipient->count_all(),
                    "recordsFiltered" => $this->recipient->count_filtered(),
                    "data" => $data,
            );
    echo json_encode($output);
  }
  // Function to show list of blood Groups Ends here 
    
    
    public function print_advance_report($id)
    {
        $data['page_title'] = 'Select a component';
        $data['id'] = $id;
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']= $this->blood_general_model->get_component_list();
        $this->load->view('blood_bank/recipient/template',$data);
   
    }
    
    public function print_report($id="",$branch_id='',$sub_section='')
    {
       
        $recipient_id=$id;
        $get_detail_by_id= $this->recipient->get_by_id_recipient($recipient_id);
        $get_detail_by_detail= $this->recipient->get_by_id_print_recipient($recipient_id,$get_detail_by_id['branch_id']);
        
        $get_by_id_data=$this->recipient->get_recipient_issued_components($recipient_id);
        
       
         
        //echo "<pre>";print_r($get_by_id_data); exit;
        
        //$get_payment_detail=$this->recipient->get_recipient_payment_details_print($recipient_id);
        //$this->load->model('general/general_model');
        //$get_payment_detail_by_id= $this->general_model->payment_mode_by_id('',$get_payment_detail['pay_mode']);
        
        $template_format= $this->recipient->report_template_format(array('section_id'=>10,'types'=>1,'sub_section'=>$sub_section,'branch_id'=>$get_detail_by_id['branch_id']));
        
        //print_r($get_payment_detail_by_id);die;
        
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['patient_detail']= $get_detail_by_id;
        $data['all_detail']= $get_detail_by_detail;
        //$data['payment_mode_by_id']= $get_payment_detail_by_id;
        $data['component_detail']= $get_by_id_data;
        $data['recipient_id'] = $recipient_id;
       // print_r($data['component_detail']); exit;
        //$data['payment_mode']= $get_payment_detail;
        $this->load->view('blood_bank/bloodbank_print_setting/print_template_bloodbank_report',$data);
      
        
    }
    
  // Function to open add form starts here
  public function add($patient_id="",$opd="",$ot="")
  {
  	unauthorise_permission('262','1507');
  	$get_data=array();
  	//$get_id='';
  	$seg=$this->uri->segment(4);
    $data['reg_no']=generate_unique_id(4);
  	if(!empty($seg))
  	{
  	$get_data=explode('_',$seg);
  	$get_type=$get_data[0];
  	$get_id=$get_data[1];
  	if($get_type=="pat")
		   {
		   	$recipient_source="4";
		   	$reference_id="0";
		   	$get_type_data="4";		      
		   }
		   else if($get_type=="ot")
		   {
		   	$data['get_id_by_ot']=$this->recipient->get_by_id_ot($get_id);
		   	$get_pat_ot_id=$data['get_id_by_ot']['patient_id'];
		   	$reference_id=$get_id;
		   	$get_id=$get_pat_ot_id;
		    $recipient_source="3";
		    $get_type_data="3";
		   }

		   else if($get_type=="ipd")
		   {
		   	$data['get_id_by_ipd']=$this->recipient->get_by_id_ipd($get_id);
		   	$get_pat_id=$data['get_id_by_ipd']['patient_id'];
		   	$reference_id=$get_id;
		   	$get_id=$get_pat_id;
		   	$get_type_data="2";
		   	$recipient_source="2";
		   }
		   else
		   {
		   	$recipient_source="1";
		   	$reference_id="0";
		   	$get_type_data="1";
		   }
     
  	}
  	else
  	{
  		$get_id=$patient_id;
 		   $get_type_data="";

  	}
   
    if($get_id!="")
    {
      $data['pat_data']   = $this->recipient->get_by_id($get_id);
      $data['component_detail']   = $this->recipient->get_component_detail_by_id($get_id);
      $data['patient_code']   = $data['pat_data']['patient_code'];
      $data['patient_id']     = $data['pat_data']['id'];
      $data['recipient_id']     = $data['pat_data']['recipient_id'];
      $data['source']     = $recipient_source;
      $data['reference_id']=$reference_id;
      $data['get_type_data']=$get_type_data;
      //print_r($data['pat_data']);
      //die;
      
    }
    else if(isset($_GET['lid']) && !empty($_GET['lid']) && $_GET['lid']>0)
        {
          //$this->load->model('opd/opd_model');
          $data['pat_data'] = $this->recipient->crm_get_by_id($_GET['lid']);
          //echo '<pre>'; print_r($lead_data);die;
          /*$name = $lead_data['name'];
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
          $address_third = $lead_data['address3'];*/
        }
    else
    {
      $data['pat_data']   = "empty";
      $data['component_detail']   = "empty";
      $data['patient_code']   = $data['reg_no'];
      $data['patient_id']     = "0";
      $data['recipient_id']     = "0";
      $data['reference_id']="0";
      $data['get_type_data']="";
    }
   
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    

    $this->load->model('general/general_model','general_model');
    $this->load->model('opd/opd_model','opd');
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $data['page_title']="Recipient Details";
    $data['simulation_list'] = $this->general_model->simulation_list($branch_id);
    $data['component_list']= $this->blood_general_model->get_component_list();
    $data['country_list'] = $this->general_model->country_list();
    $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
    $data['reminder_service']=$this->blood_general_model->get_reminder_services_list();
    $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
    $data['bag']=$this->blood_general_model->get_bag_list();
    $data['modes_of_donation']=$this->blood_general_model->get_mode_of_donation_list();
    $data['camp_list']=$this->blood_general_model->get_blood_camp_list();
    $data['reg_no']=generate_unique_id(4);
    if(!empty($branch_id))
        {
          $data['referal_doctor_list'] = $this->opd->referal_doctor_list($branch_id);
        }
        else
        {
          $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        }

        if(!empty($branch_id))
        {
          $data['referal_hospital_list'] = $this->opd->referal_hospital_list($branch_id);
         // print_r($data['referal_hospital_list']);
          //die;

        }
        else
        {
          $data['referal_hospital_list'] = $this->opd->referal_hospital_list();
        }

    $this->load->view('blood_bank/recipient/add',$data);
  }


   public function edit($patient_id="",$opd="",$ot="")
  {
  	unauthorise_permission('262','1508');
  
		    if($patient_id!="")
		    {
		      $data['pat_data']   = $this->recipient->get_by_id_edit($patient_id);
            $data['component_detail']   = $this->recipient->get_component_detail_by_id($patient_id);
          $data['patient_code']   = $data['pat_data']['patient_code'];
		      $data['patient_id']     = $data['pat_data']['id'];
		      $data['recipient_id']     = $data['pat_data']['recipient_id'];
		      $data['reference_id']=$data['pat_data']['reference_id'];
		      $data['get_type_data']=$data['pat_data']['recipient_source'];
		      
		    }  
		    else
		    {
          $data['pat_data']   = "empty";
         $data['component_detail']   = "empty";
          $data['patient_id']     = "0";
          $data['recipient_id']     = "0";
          $data['reference_id']="0";
          $data['get_type_data']="";
		    }
		   
		    $users_data = $this->session->userdata('auth_users');
		    $branch_id=$users_data['parent_id'];
		    

		    $this->load->model('general/general_model','general_model');
		    $this->load->model('opd/opd_model','opd');
		    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
		    $data['page_title']="Recipient Details";
		    $data['simulation_list'] = $this->general_model->simulation_list($branch_id); 
		    $data['country_list'] = $this->general_model->country_list();
		    $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
		    $data['reminder_service']=$this->blood_general_model->get_reminder_services_list();
		    $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
		    $data['doctors']=$this->blood_general_model->get_doctors_list();
		    $data['hospital']=$this->blood_general_model->get_hospital_list();
		    $data['bag']=$this->blood_general_model->get_bag_list();
		    $data['modes_of_donation']=$this->blood_general_model->get_mode_of_donation_list();
        $data['component_list']= $this->blood_general_model->get_component_list();
		    $data['camp_list']=$this->blood_general_model->get_blood_camp_list();
		    $data['reg_no']=generate_unique_id(4);
		    if(!empty($branch_id))
		        {
		          $data['referal_doctor_list'] = $this->opd->referal_doctor_list($branch_id);
		        }
		        else
		        {
		          $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
		        }

		        if(!empty($branch_id))
		        {
		          $data['referal_hospital_list'] = $this->opd->referal_hospital_list($branch_id);
		        }
		        else
		        {
		          $data['referal_hospital_list'] = $this->opd->referal_hospital_list();
		        }

		    $this->load->view('blood_bank/recipient/add',$data);
  }


  //details of issue component

   public function issue_component($recipient_id="")
  {
        unauthorise_permission('262','1514');
        /* component detail  */
        $b_id='';
        $data['cross_match_detail']=$this->recipient->cross_match_detail($recipient_id);
        //print_r($data['cross_match_detail']);
        if(count($data['cross_match_detail'])>0)
        {
            $data['componenet_detail']=$this->recipient->component_detail_by_cross_match_id($data['cross_match_detail']->id,$recipient_id);
        }
        $data['recipient_component_detail']=$this->recipient->get_recipient_component_detail($recipient_id);
        $this->load->model('blood_bank/donor/donor_examinations_model','donor_examination'); 
        $data['doctor_data'] =$this->donor_examination->get_doctor_id();
        $employee_type='3';
        $data['technician_data'] =$this->donor_examination->get_by_examiner_id($employee_type);
        
        
    /* component detail  */
    if($recipient_id!="")
    {
      
      //print_r($data['reg_no']);
      
       $data['recipient_selected_component_detail']=$this->recipient->get_recipient_component_selected_detail($recipient_id);

      $data['pat_data']=$this->recipient->get_by_id_recipient($recipient_id);

      $this->load->model('general/general_model','general');           
      $data['recipient_id']     = $data['pat_data']['recipient_id'];
      $data['patient_id']     = $data['pat_data']['patient_id'];
      $data['bag_id']=$data['pat_data']['bag_id'];
      $b_id=$data['pat_data']['b_id'];

      $data['blood_issued']=$data['pat_data']['blood_issued'];

      if($data['blood_issued']==0)
      {
          //$data['reg_no']=generate_unique_id(3);
          $data['reg_no']=generate_unique_id(43);
      // print_r($data['reg_no']);
          $data['issue_data']="empty";
          $data['payment_data']="empty";
          $data['payment_fields']="empty";
          $data['action']="new";
      }
      else
      {
        $data['reg_no']=$data['pat_data']['patient_code'];
        $issue_data=$this->recipient->get_recipient_issued_components($data['recipient_id']);

          $payment_data=$this->recipient->get_recipient_payment_details($data['recipient_id']);
        if($payment_data!="empty")
          $get_payment_detail= $this->recipient->payment_mode_detail_according_to_field($payment_data['pay_mode'],$data['recipient_id'],'19', '19');
        else
          $get_payment_detail="empty";
        $data['issue_data']=$issue_data;
        $data['payment_data']=$payment_data;
        $data['payment_fields']=$get_payment_detail;
        $data['action']="update";
      }

      $data['payment_mode']=$this->general->payment_mode();
    }  
    else
    {
      redirect(base_url().'/recipient');
    }
    $data['page_title']="Issue Component";
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    //print_r($b_id);
    //die;
    $data['donor_list']=$this->recipient->donar_list($b_id);
    $data['particular_data']=$this->recipient->get_recipient_particular($recipient_id);

      //echo "<pre>"; print_r($data['particular_data']); exit; //payment_data
    //print '<pre>'; print_r($data['donor_list']);die;
    $data['particular_date'] = date('d-m-Y');
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $data['component_list']= $this->blood_general_model->get_component_list(); 
    
    
    $this->load->view('blood_bank/recipient/add_issue',$data);
  }
  public function issue_component_old($recipient_id="")
  {
        unauthorise_permission('262','1514');
        /* component detail  */
        $b_id='';
        $data['cross_match_detail']=$this->recipient->cross_match_detail($recipient_id);
        //print_r($data['cross_match_detail']);
        if(count($data['cross_match_detail'])>0)
        {
            $data['componenet_detail']=$this->recipient->component_detail_by_cross_match_id($data['cross_match_detail']->id,$recipient_id);
        }
        $data['recipient_component_detail']=$this->recipient->get_recipient_component_detail($recipient_id);
    /* component detail  */
    if($recipient_id!="")
    {
      
      //print_r($data['reg_no']);
      $data['recipient_selected_component_detail']=$this->recipient->get_recipient_component_selected_detail($recipient_id);
      $data['pat_data']=$this->recipient->get_by_id_recipient($recipient_id);

      $this->load->model('general/general_model','general');           
      $data['recipient_id']     = $data['pat_data']['recipient_id'];
      $data['patient_id']     = $data['pat_data']['patient_id'];
      $data['bag_id']=$data['pat_data']['bag_id'];
      $b_id=$data['pat_data']['b_id'];

      $data['blood_issued']=$data['pat_data']['blood_issued'];

      if($data['blood_issued']==0)
      {
          $data['reg_no']=generate_unique_id(3);
      // print_r($data['reg_no']);
          $data['issue_data']="empty";
          $data['payment_data']="empty";
          $data['payment_fields']="empty";
          $data['action']="new";
      }
      else
      {
        $data['reg_no']=$data['pat_data']['patient_code'];
        $issue_data=$this->recipient->get_recipient_issued_components($data['recipient_id']);
          $payment_data=$this->recipient->get_recipient_payment_details($data['recipient_id']);
        if($payment_data!="empty")
          $get_payment_detail= $this->recipient->payment_mode_detail_according_to_field($payment_data['pay_mode'],$data['recipient_id'],'19', '19');
        else
          $get_payment_detail="empty";
        $data['issue_data']=$issue_data;
        $data['payment_data']=$payment_data;
        $data['payment_fields']=$get_payment_detail;
        $data['action']="update";
      }

      $data['payment_mode']=$this->general->payment_mode();
    }  
    else
    {
      redirect(base_url().'/recipient');
    }
    $data['page_title']="Issue Component";
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    //print_r($b_id);
    //die;
    $data['donor_list']=$this->recipient->donar_list($b_id);
    //print '<pre>'; print_r($data['donor_list']);die;
    $this->load->view('blood_bank/recipient/add_issue',$data);
  }

  public function save_recipient()
  {
    //hms_blood_patient_to_recipient
   
    $response = $this->_validate();
    if($response!=200)
    {
      echo $response;
    }
    else
    {
      $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
      $users_data = $this->session->userdata('auth_users');
      $branch_id=$users_data['parent_id'];
      //$reg_no = generate_unique_id(4);
      $reg_no = generate_unique_id(43); //
      $post=$this->input->post();
      $pat_id=$this->input->post('patient_id');
      
      $patient_id_res=$this->input->post('patient_id_res');
      $source=$this->input->post('recipient_source');
      $reference_id=$this->input->post('reference_id');
      $host_id=$this->input->post('hospital_id');
      $referred_by=$this->input->post('referred_by');
      $doctor_id=$this->input->post('doctor_id');
      $hospital_id='';
        
    	$hospital_id=$this->input->post('hospital_id');	
        $doctor_id=$this->input->post('doctor_id');
        
        $requirement_date=$this->input->post('requirement_date');
        $issue_by_mode=$this->input->post('issue_by_mode');
        $require_time=$this->input->post('require_time');
        if(!empty($requirement_date))
        {
        	$requirement_date_new=$this->input->post('requirement_date');
        }
        else
        {
        	$requirement_date_new='';
        }
          if(!empty($issue_by_mode))
        {
          $issue_by_mode_new=$this->input->post('issue_by_mode');
        }
        else
        {
          $issue_by_mode_new='';
        }

        if(!empty($require_time))
        {
          $require_time_new=date('H:i:s',strtotime($require_time));
        }
        else
        {
          $require_time_new='';
        }
        //print_r($require_time_new);
        //die;

        
        $patient_data_array=array(
                           'simulation_id'=>$this->input->post('simulation_id'),
                           'branch_id'=>$branch_id,
                           'patient_name'=>$this->input->post('patient_name'),                           
                           'relation_type'=>$this->input->post('relation_type'),
                           'relation_simulation_id'=>$this->input->post('relation_simulation_id'),
                           'relation_name'=>$this->input->post('relation_name'),
                           'mobile_no'=>$this->input->post('mobile_no'),
                           'pincode'=>$this->input->post('pincode'),
                           'age_y'=>$this->input->post('age_y'),
                           'age_m'=>$this->input->post('age_m'),
                           'age_d'=>$this->input->post('age_d'),
                           'patient_email'=>$this->input->post('patient_email'),
                           'address'=>$this->input->post('address'),
                           'address2'=>$this->input->post('address_second'),
                           'address3'=>$this->input->post('address_third'),
                           'country_id'=>$this->input->post('country_id'),
                           'state_id'=>$this->input->post('state_id'),
                           'city_id'=>$this->input->post('citys_id'),
                           'gender'=>$this->input->post('gender'),
                           'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );
      //print_r($patient_data_array);
      //die;

      if(!empty($_FILES['requisitation_form']['name']))
      { 
        
        $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/recipent_document/'; 
        $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
        $config['max_size']      = 1000; 
        $config['encrypt_name'] = TRUE; 
        $this->load->library('upload', $config);
      //print_r($config);die;
        if($this->upload->do_upload('requisitation_form')) 
        {

          $file_data = $this->upload->data(); 
          $file_name1= $file_data['file_name'];
        }

        }
        else
        {
          $file_name1=''; 
        }



      if($pat_id > 0)
      {
        $patient_condition=" id=".$pat_id." and branch_id=".$branch_id." ";
        $patient_condition_res=" id=".$patient_id_res." and branch_id=".$branch_id." ";
        $patient_data_array["modified_by"]=$users_data['id'];
        $patient_data_array["modified_date"]=date('Y-m-d H:i:s');
        $patient_rec_id=$this->blood_general_model->common_update('hms_patient',$patient_data_array, $patient_condition);

       
          $patient_data_array_recipient=array(
                           'patient_id'=>$pat_id,
                           'branch_id'=>$branch_id,
                           'reference_id'=>$reference_id,                           
                           'referred_by'=>$this->input->post('referred_by'),
                           'hospital_id'=>$hospital_id,
                           'doctor_id'=>$doctor_id,
                           'document_name'=>$file_name1,
                           'ward_bed_no'=>$this->input->post('ward_bed_no'),
                           'blood_group_id'=>$this->input->post('blood_group_id'),
                           'clinical_diagnosis'=>$this->input->post('clinical_diagnosis'),
                           'volume'=>$this->input->post('volume'),
                           'specimen_recived_by '=>$this->input->post('specimen_recived_by'),
                           'bag_id'=>$this->input->post('bag_id'),
                           'recipient_source'=>$source,
                            'issue_by_mode'=>$issue_by_mode_new,
                           'requirement_date'=>date('Y-m-d',strtotime($requirement_date)),
                            'require_time'=>$require_time_new,
                            'created_date'=>date('Y-m-d H:i:s'),
                            'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );
         

        //print_r($patient_data_array_recipient);
        //die;


           if(($patient_id_res > 0) && ($pat_id > 0))
          {


           $insert_data=$this->blood_general_model->common_update('hms_blood_patient_to_recipient',$patient_data_array_recipient, $patient_condition_res);
             $this->recipient->delete_record_component($patient_id_res,$branch_id);
           /* entry in component table */
           //print_r($this->input->post('comp_wise'));die;
            if(!empty($this->input->post('comp_wise')))
            {
              $count_compnent_data= count($this->input->post('comp_wise'));
              for($i=0;$i<$count_compnent_data;$i++)
              {
                if(isset($this->input->post('comp_wise')[$i]['check_type'][0]) && !empty($this->input->post('comp_wise')[$i]['check_type'][0]))
                {
                  $check_type=1;
                }
                else
                {
                  $check_type=0;
                }
                if(isset($this->input->post('comp_wise')[$i]['comp_qty'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_qty'][0]))
                {
                  $comp_qty=$this->input->post('comp_wise')[$i]['comp_qty'][0];
                }
                else
                {
                  $comp_qty=='';
                }
                if(isset($this->input->post('comp_wise')[$i]['comp_name'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_name'][0]))
                {
                  $comp_name=$this->input->post('comp_wise')[$i]['comp_name'][0];
                }
                else
                {
                  $comp_name=='';
                }
                
                if(isset($this->input->post('comp_wise')[$i]['comp_unite'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_unite'][0]))
                {
                  $comp_unite=$this->input->post('comp_wise')[$i]['comp_unite'][0];
                }
                else
                {
                  $comp_unite=='';
                }
                
                
                 $insert_component_array=array(
                                            'branch_id'=>$branch_id,
                                            'receipent_id'=>$patient_id_res,
                                            'qty'=>$comp_qty,
                                            'lc_check_status'=>$check_type,
                                            'component_id'=>$comp_name,
                                            'comp_unite'=>$comp_unite,
                                            'created_date'=>date('Y-m-d H:i:s'),
                                            'status'=>'1',
                                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                            );

                 $this->blood_general_model->common_insert('hms_blood_patient_to_recipient_components',$insert_component_array);
              }
            }
             /* entry in component table */

           }
           else
           {

            $insert_data = $this->blood_general_model->common_insert('hms_blood_patient_to_recipient',$patient_data_array_recipient);
            /* entry in component table */
            if(!empty($this->input->post('comp_wise')))
            {
              $count_compnent_data= count($this->input->post('comp_wise'));
              for($i=0;$i<$count_compnent_data;$i++)
              {
                 if(isset($this->input->post('comp_wise')[$i]['check_type'][0]) && !empty($this->input->post('comp_wise')[$i]['check_type'][0]))
                {
                  $check_type=1;
                }
                else
                {
                  $check_type=0;
                }
                if(isset($this->input->post('comp_wise')[$i]['comp_qty'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_qty'][0]))
                {
                  $comp_qty=$this->input->post('comp_wise')[$i]['comp_qty'][0];
                }
                else
                {
                  $comp_qty=='';
                }
                if(isset($this->input->post('comp_wise')[$i]['comp_name'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_name'][0]))
                {
                  $comp_name=$this->input->post('comp_wise')[$i]['comp_name'][0];
                }
                else
                {
                  $comp_name=='';
                }
                
                if(isset($this->input->post('comp_wise')[$i]['comp_unite'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_unite'][0]))
                {
                  $comp_unite=$this->input->post('comp_wise')[$i]['comp_unite'][0];
                }
                else
                {
                  $comp_unite=='';
                }
                 $insert_component_array=array(
                                            'branch_id'=>$branch_id,
                                            'receipent_id'=>$insert_data,
                                            'qty'=>$comp_qty,
                                            'lc_check_status'=>$check_type,
                                            'component_id'=>$comp_name,
                                            'comp_unite'=>$comp_unite,
                                            'created_date'=>date('Y-m-d H:i:s'),
                                            'status'=>'1',
                                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                            );


                 $this->blood_general_model->common_insert('hms_blood_patient_to_recipient_components',$insert_component_array);

              }
            }
             /* entry in component table */
           }
            
        echo json_encode(array('st'=>1, 'msg'=>'Recipient successfully updated'));
      }
      else
      {
        $patient_code = generate_unique_id(4);
        $patient_data_array["patient_code"]=$patient_code;
        $patient_data_array["branch_id"]=$branch_id;
        $patient_data_array["created_by"]=$users_data['id'];
        $patient_data_array["created_date"]=date('Y-m-d H:i:s');
        $patient_rec_id = $this->blood_general_model->common_insert('hms_patient',$patient_data_array);
       
        $patient_data_array_recipient=array(
                           'patient_id'=>$patient_rec_id,
                           'branch_id'=>$branch_id,
                            'reference_id'=>$reference_id, 
                           'referred_by'=>$this->input->post('referred_by'),
                           'hospital_id'=>$hospital_id,
                           'document_name'=>$file_name1,
                           'doctor_id'=>$doctor_id,
                           'ward_bed_no'=>$this->input->post('ward_bed_no'),
                           'blood_group_id'=>$this->input->post('blood_group_id'),
                           'clinical_diagnosis'=>$this->input->post('clinical_diagnosis'),
                           'volume'=>$this->input->post('volume'),
                           'specimen_recived_by '=>$this->input->post('specimen_recived_by'),
                           'bag_id'=>$this->input->post('bag_id'),
                           'recipient_source'=>$source,
                            'issue_by_mode'=>$issue_by_mode_new,
                           'requirement_date'=>date('Y-m-d',strtotime($requirement_date)),
                           'require_time'=>$require_time_new,
                            'created_date'=>date('Y-m-d H:i:s'),
                           'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );
              $insert_data = $this->blood_general_model->common_insert('hms_blood_patient_to_recipient',$patient_data_array_recipient);
              //echo $this->db->last_query();die;
            /* entry in component table */
            if(!empty($this->input->post('comp_wise')))
            {
              $count_compnent_data= count($this->input->post('comp_wise'));
              for($i=0;$i<$count_compnent_data;$i++)
              {
                //print_r($this->input->post('comp_wise'));die;
                if(isset($this->input->post('comp_wise')[$i]['check_type'][0]) && !empty($this->input->post('comp_wise')[$i]['check_type'][0]))
                {
                  $check_type=1;
                }
                else
                {
                  $check_type=0;
                }
                if(isset($this->input->post('comp_wise')[$i]['comp_qty'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_qty'][0]))
                {
                  $comp_qty=$this->input->post('comp_wise')[$i]['comp_qty'][0];
                }
                else
                {
                  $comp_qty=='';
                }
                if(isset($this->input->post('comp_wise')[$i]['comp_name'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_name'][0]))
                {
                  $comp_name=$this->input->post('comp_wise')[$i]['comp_name'][0];
                }
                else
                {
                  $comp_name=='';
                }
                
                if(isset($this->input->post('comp_wise')[$i]['comp_unite'][0]) && !empty($this->input->post('comp_wise')[$i]['comp_unite'][0]))
                {
                  $comp_unite=$this->input->post('comp_wise')[$i]['comp_unite'][0];
                }
                else
                {
                  $comp_unite=='';
                }
                
                
                 $insert_component_array=array(
                                            'branch_id'=>$branch_id,
                                            'receipent_id'=>$insert_data,
                                            'qty'=>$comp_qty,
                                            'lc_check_status'=>$check_type,
                                            'component_id'=>$comp_name,
                                            'comp_unite'=>$comp_unite,
                                            'created_date'=>date('Y-m-d H:i:s'),
                                            'status'=>'1',
                                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                            );

                 //echo '<pre>';print_r($insert_component_array);die;
                 $this->blood_general_model->common_insert('hms_blood_patient_to_recipient_components',$insert_component_array);
                
                 
              }
            }
             /* entry in component table */
              echo json_encode(array('st'=>1, 'msg'=>'Recipient successfully created'));
            }
    }
  }


  // Function to validate common settings
  public function common_setting_validation()
  {
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $value=$this->input->post('data');
    $flag=$this->input->post('flag');
    
      $status=1;
      if($flag==1)   // for minimum and maximum age
      {
        $common_settings=$this->blood_general_model->get_common_settings('DONAR_AGE_CRITERIA'); 
        $min_age=$common_settings[0]->setting_value1;
        $max_age=$common_settings[0]->setting_value2;
        if($min_age!=0)
        {
           if($value < $min_age)
             $status=0;
        }
        if($max_age!=0)
        {
            if($value > $max_age)
              $status=0;
        }
        if($status==0)
          echo json_encode(array('st'=>0,'msg'=>'<font style="color:red;">Age is not valid</font>')); 
        else
          echo json_encode(array('st'=>1,'msg'=>'')); 
      }  
      else if($flag==2)   // for minimum weight
      {
        $common_settings=$this->blood_general_model->get_common_settings('DONAR_MIN_W_CR');
        $minimum_weight=$common_settings[0]->setting_value1;
        if($value < $minimum_weight)
          echo json_encode(array('st'=>0,'msg'=>'<font style="color:red;">Minimum weight is not valid</font>')); 
        else
          echo json_encode(array('st'=>1,'msg'=>''));   

      }
      else if($flag==3)  // previous donation date
      {
        $common_settings=$this->blood_general_model->get_common_settings('MINIMUM_GAP_BETWEEN_DONATION');
        $minimum_gap=$common_settings[0]->setting_value1;
        $minimum_gap_days= 30 * $minimum_gap;
        $datetime1 =date_create(date('Y-m-d',strtotime($value)));
        $datetime2 =date_create(date('Y-m-d'));
        $interval = date_diff($datetime1, $datetime2);

        if($interval->days < $minimum_gap_days )
          echo json_encode(array('st'=>0,'msg'=>'<font style="color:red;">Not eligible as per minimum gap criteria</font>')); 
        else
          echo json_encode(array('st'=>1,'msg'=>'')); 
      }
  }
  // Function to validte common settings



  // Function validate
  public function _validate()
  {
  	$users_data = $this->session->userdata('auth_users');
    $field_list = mandatory_section_field_list(2);
    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
    if(!empty($field_list))
        {
            if($field_list[0]['mandatory_field_id']=='5' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id'])
              {
                 $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]');
              }
               
            if($field_list[1]['mandatory_field_id']=='7' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id'])
              { 
                $this->form_validation->set_rules('age_y', 'age', 'trim|required');
              }
        }
         $this->form_validation->set_rules('patient_email', 'recipient email', 'trim|valid_email');

         $this->form_validation->set_rules('patient_name', 'recipient name', 'trim|required');

         $this->form_validation->set_rules('blood_group_id', 'blood group', 'trim|required');
          $this->form_validation->set_rules('pincode', 'pincode', 'trim|min_length[6]|max_length[6]|numeric');
         //$this->form_validation->set_rules('bag_id', 'bag type', 'trim|required');
         $this->form_validation->set_rules('requirement_date', 'requirement date', 'trim|required');
          if ($this->form_validation->run() == FALSE) 
          { 
            echo json_encode(array('st'=>0, 'patient_email'=>form_error('patient_email'),'blood_group_id'=>form_error('blood_group_id'), 'bag_id'=>form_error('bag_id'),'requirement_date'=>form_error('requirement_date'),'mobile_no'=>form_error('mobile_no'), 'age'=>form_error('age_y'), 'patient_name'=>form_error('patient_name'),'pincode'=>form_error('pincode')  ));
          }
          else
          {
              return "200";
          }
  }
  // Function validate


 public function archive()
    {
        unauthorise_permission('262','1510');

 
        $data['page_title'] = 'Recipient archive list';
        $this->load->helper('url');
        $this->load->view('blood_bank/recipient/archive',$data);
    }

 public function archive_ajax_list()
    {
        unauthorise_permission('262','1510');
        $this->load->model('blood_bank/recipient/recipient_archive_model','recipient_archive'); 
    $users_data = $this->session->userdata('auth_users');
    $list = $this->recipient_archive->get_datatables();  
    $data = array();
    $no = $_POST['start'];
    $i = 1;
    $total_num = count($list);
    foreach ($list as $recipient_archive) 
    {
      $no++;
        $row = array();
        if($recipient_archive->status==1)
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
        ////////// Check list end ///////////// 
        $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$recipient_archive->id.'">'.$check_script; 
        $row[] = $recipient_archive->patient_code;   
        $row[] = $recipient_archive->patient_name;    
        $row[] = $recipient_archive->mobile_no;
        $row[] = $recipient_archive->gender;
        $row[] = $recipient_archive->blood_group; 
        //$row[] = $recipient_archive->clinical_diagnosis; 
        $row[] = ( strtotime($recipient_archive->requirement_date) > 0 ? date('d-M-Y', strtotime($recipient_archive->requirement_date)) : ''); 
        //$row[] = $recipient_archive->bag_type;
        $row[] = $status;
        $row[] = date('d-M-Y H:i A',strtotime($recipient_archive->created_date)); 
       
      $btnrestore='';
      $btndelete='';
      $actions='';
       if(in_array('1513',$users_data['permission']['action'])){
          $btnrestore = ' <a onClick="return restore_recipient('.$recipient_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1511',$users_data['permission']['action'])){
          $btndelete = ' <a onClick="return trash('.$recipient_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;

     
      
      
      $data[] = $row;
      $i++;
    }

    $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->recipient_archive->count_all(),
                    "recordsFiltered" => $this->recipient_archive->count_filtered(),
                    "data" => $data,
            );
    echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('262','1513');
         $this->load->model('blood_bank/recipient/recipient_archive_model','recipient_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->recipient_archive->restore($id);
           $response = "Recipient successfully restore in recipient list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('262','1513');
        $this->load->model('blood_bank/recipient/recipient_archive_model','recipient_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->recipient_archive->restoreall($post['row_id']);
            $response = "Recipient successfully restore in recipient list.";
            echo $response;
        }
    }

     public function trash($id="")
    {
        unauthorise_permission('262','1511');
         $this->load->model('blood_bank/recipient/recipient_archive_model','recipient_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->recipient_archive->trash($id);
           $response = "Recipient successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('262','1511');
       $this->load->model('blood_bank/recipient/recipient_archive_model','recipient_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->recipient_archive->trashall($post['row_id']);
            $response = "Recipient successfully deleted parmanently.";
            echo $response;
        }
    }
    
    function get_blood_group_name($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->recipient->get_blood_group_name($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

  public function cross_match_page($patient_id="",$recipient_id="")
  {
    //echo $recipient_id;die;
    $data['page_title']="Cross Matching";
    $this->load->model('opd/opd_model','opd');
    $post = $this->input->post();
    //print_r($post);die;
    $b_id='';
    if(!empty($recipient_id))
    {
      $data['pat_data']=$this->recipient->get_by_id_recipient($recipient_id);
      $data['bag_id']=$data['pat_data']['bag_id'];
      $b_id=$data['pat_data']['b_id'];
    }
    $users_data = $this->session->userdata('auth_users');
      if(!isset($post) || empty($post))
      {
        $this->session->unset_userdata('donor_list');  
      }
        
    //$this->session->unset_userdata('donor_list');
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
    $data['cross_match_detail']=$this->recipient->cross_match_detail($recipient_id);
    
    $data['recipient_component_detail']=$this->recipient->get_recipient_component_detail($recipient_id);

    $data['vitals_list']=$this->recipient->vitals_list();
    $branch_id=$users_data['parent_id'];
    $data['donor_list']=$this->recipient->donar_list($b_id);

    if(!empty($branch_id))
    {
      $data['referal_doctor_list'] = $this->opd->referal_doctor_list($branch_id);
    }
    else
    {
      $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
    }
    if(!empty($branch_id))
    {
    $data['referal_hospital_list'] = $this->opd->referal_hospital_list($branch_id);
    }
    else
    {
    $data['referal_hospital_list'] = $this->opd->referal_hospital_list();
    }
    $data['recepeint_detail']= $this->recipient->get_by_id($patient_id);
  //print_r($data['recepeint_detail']);
    if(!empty($_FILES['cross_match_form']['name']))
    { 

        $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/recipent_document/'; 
        $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
        $config['max_size']      = 1000; 
        $config['encrypt_name'] = TRUE; 
        $this->load->library('upload', $config);
    //print_r($config);die;
    if($this->upload->do_upload('cross_match_form')) 
    {

      $file_data = $this->upload->data(); 
      $file_name1= $file_data['file_name'];
    }

    }
    else
    {
     $file_name1=''; 
    }
     if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate_cross_match();
            if($this->form_validation->run() == TRUE)
            {
              //print_r($ipd_particular_billing_list);die;
                $charge_id = $this->recipient->save_cross_match_data($recipient_id,$file_name1);
                $this->session->set_flashdata('success','Cross Match added successfully.');
               
                if($post['issue_component']=="issue_component")
                {
                redirect(base_url('blood_bank/recipient/issue_component/'.$recipient_id));
                }
                else
                {
                  
                  redirect(base_url('blood_bank/recipient'));
                }
                
            }
            else
            {
                $data['form_error'] = validation_errors(); 
                //echo "<pre>"; print_r($data['form_error']); exit;
            }
             
        }

        if(!empty($data['cross_match_detail']) && count($data['cross_match_detail'])>0)
        {
        $data['componenet_detail']=$this->recipient->component_detail_by_cross_match_id($data['cross_match_detail']->id,$recipient_id);

//echo "<pre>"; print_r($data['componenet_detail']); die;
        /* component detail  */
          if((!isset($post) || empty($post)) && count($data['componenet_detail'])>=1)
         {
            
              $donor_list = $this->session->userdata('donor_list');
              
              if(isset($donor_list) && !empty($donor_list))
              {
                $donor_list = $donor_list; 
              }
              else
              {
                $donor_list = [];
              }
              $i=1;
              foreach($data['componenet_detail'] as $comp_detail)
              {
                //$particulars_data['charge_id']
                  $donor_list[] = array('charge_id'=>$i,'donor_actual_id'=>$comp_detail['donor_actual_id'],'donor_id'=>$comp_detail['donor_id'],'blood_group'=>$comp_detail['blood_group'],'camp_id'=>$comp_detail['camp_id'],'blood_group_id'=>$comp_detail['blood_group_id'],'quantity'=>$comp_detail['qty'], 'option'=>$comp_detail['option'], 'leuco_depleted'=>$comp_detail['leuco_deplecated'],'bar_code'=>$comp_detail['barcode'],'component_price'=>$comp_detail['component_price'],'expiry_date'=>date('d-m-Y H:i:s',strtotime($comp_detail['expiry_date'])));
                  $this->session->set_userdata('donor_list', $donor_list);
                   $i++;
              }
         }
        /* component detail  */

        /* vitals detail */
        $data['vitals_list']=$this->recipient->vitals_detail_by_cross_match_id($data['cross_match_detail']->id,$recipient_id);


        /* vitals detail */


         $data['form_data']=array(
                                'data_id'=>$data['cross_match_detail']->id,
                                'transfustion_date'=>date('d-m-Y',strtotime($data['cross_match_detail']->starttime_transfusion_date)),
                                'transfustion_time'=>date('H:i:s',strtotime($data['cross_match_detail']->starttime_transfusion_time)),
                                'file_name'=>$data['cross_match_detail']->attachement); 
        }
        else
        {
           $data['form_data']=array(
                                    'data_id'=>'',
                                    'transfustion_date'=>'',
                                    'transfustion_time'=>'',
                                    'file_name'=>''); 
        }
        
    $data['component_list']= $this->blood_general_model->get_component_list();      
     $this->load->view('blood_bank/recipient/cross_matching',$data);
        
  }

  private function _validate_cross_match()
    {
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $donor_list = $this->session->userdata('donor_list');
        //echo "<pre>"; print_r($donor_list); exit;
        if(!isset($donor_list) && empty($donor_list))
        {
          $this->form_validation->set_rules('donor_li', 'donor li', 'trim|callback_check_donor_li');
        }
        //$this->form_validation->set_rules('transfustion_time', 'transfustion time', 'trim|required');
        //$this->form_validation->set_rules('transfustion_date', 'transfustion date', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                        'transfustion_time'=>$post['transfustion_time'],
                                        'transfustion_date'=>$post['transfustion_date']
                                       );

                                         
            return $data['form_data'];
        }   
    }

    public function check_donor_li()
    {
       $donor_list = $this->session->userdata('donor_list');
       if(isset($donor_list) && !empty($donor_list))
       {
          return true;
       }
       else
       {
          $this->form_validation->set_message('donor_li', 'Please select a donor.');
          return false;
       }
    }
  public function check_email($str)
  {
    if(!empty($str))
    {
      $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
      $this->load->model('general/general_model','general');
      $post = $this->input->post();
      if(!empty($post['donor_id']) && $post['donor_id']>0)
      {
          return true;
      }
      else
      {
        $userdata = $this->blood_general_model->check_email($str); 
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
      unauthorise_permission('262','1509');
       if(!empty($id) && $id>0)
       {
           $result = $this->recipient->delete($id);
           $response = "Recipient successfully deleted.";
           echo $response;
       }
    }

    public function deleteall()
    {
        unauthorise_permission('262','1509');
        $post = $this->input->post(); 
        //print_r($post);
        //die; 
        if(!empty($post))
        {
            $result = $this->recipient->deleteall($post['row_id']);
            $response = "Recipient successfully deleted.";
            echo $response;
        }
    }


     // function to get stock listing  by component and bag type

      public function get_donor_list_old()
      {
        $component_name=$this->input->post('component_name');
        $bar_code=$this->input->post('bar_code');
        $blood_group_id='';
         //print_r($blood_group_id);
        if(!empty($blood_group_id))
        {
          $blood_group_id=$this->input->post('blood_group_id');
        }
        $get_donor_list= $this->recipient->get_donor_list_component($component_name,$blood_group_id,$bar_code);
        $check_script='';
        $check_script.='';
        $option='';
        $option='';
             $option.=$check_script.'<select name="donor_id" id="donor_id" class="" onchange="get_stock_available();" data-live-search=""><option value="">Select Donor</option>';
              if(!empty($get_donor_list))
              {
                foreach($get_donor_list as $donars)
                {
                    $option.='<option value="'.$donars->donor_id.'">'.$donars->donor_code.'</option>';
                }
              }
        
        $option.='</select>';
        echo json_encode(array('st'=>1,'option'=>$option));
      }

      public function get_donor_list()
      {
        $component_name=$this->input->post('component_name');
        $bar_code=$this->input->post('bar_code');
        $blood_group_id='';
         //print_r($blood_group_id);
        if(!empty($blood_group_id))
        {
          $blood_group_id=$this->input->post('blood_group_id');
        }
        $get_donor_list= $this->recipient->get_donor_list_component($component_name,$blood_group_id,$bar_code);
        $check_script='';
        $check_script.='';
        $option='';
        $option='';
             $option.=$check_script.'<select name="donor_id" id="donor_id" class="" onchange="get_stock_available();" data-live-search=""><option value="">Select Donor</option>';
              if(!empty($get_donor_list))
              {
                foreach($get_donor_list as $donars)
                {
                    $option.='<option value="'.$donars->donor_id.'">'.$donars->donor_code.'</option>';
                }
              }
        
        $option.='</select>';
        echo json_encode(array('st'=>1,'option'=>$option));
      }

      public function get_stocks_available()
      {
        $component_name=$this->input->post('component_name');
        $bar_code=$this->input->post('bar_code');
        $get_donor_list= $this->recipient->get_donor_list_component($component_name,$bar_code);
        //echo $this->db->last_query();
        //print_r($get_donor_list);
        //die;
        $check_script='';
        $check_script.='';
        $expiry_date='';
        $component_qty='';
        $exist_id=$this->input->post('vals');
        $bag_type_id=$this->input->post('bag_type_id');
        $donor_id=$this->input->post('donor_id');
        $bar_code=$this->input->post('bar_code');
        $blood_group_id=$this->input->post('blood_group_id');
        if((!empty($vals)) && ($vals!=''))
          $ids=implode(',', $vals);
        else
          $ids='';
          if((!empty($component_name))||($bar_code!=''))
          {
            $stock_data=$this->recipient->get_stock_available($bag_type_id,$component_name,$exist_id,$bar_code,$donor_id,$blood_group_id);
            $table="";

            //print '<pre>'; print_r($stock_data);die;

            if($stock_data!="empty")
            {
              foreach ($stock_data as $stock) 
              {
                //print_r($stock); exit;

                  if(strtotime($stock->expiry_date) >86400)
                  {
                    $expiry_date= date('d-m-Y',strtotime($stock->expiry_date));
                  }
                  else
                  {
                    $expiry_date='';
                  }

                  if(strtotime($stock->created_date) >86400)
                  {
                    $created_date= date('d-m-Y',strtotime($stock->created_date));
                  }
                  else
                  {
                    $created_date='';
                  }
                  if($stock->donation_status==1)
                  {
                      $status = '<font color="green">In-Stock</font>';
                  }   
                  else if($stock->donation_status==2){
                    $status = '<font color="red">Awaiting Results</font>';
                } 
                else if($stock->donation_status==3){
                  $status = '<font color="red">Unfit to Donate</font>';
                }
                else if($stock->donation_status==4)
                {
                  $status = '<font color="red">Discarded (Test Failed)</font>';
                  
                }
                else if($stock->donation_status==5)
                {
                  $status = '<font color="red">QC Failed</font>';
                  
                }
                  else
                  {
                    $status = '';
                  }
                  $component_qty='';
                  //$get_stock_quantity= $this->recipient->get_stock_quantity($bag_type_id,$stock->component_id,$exist_id,$stock->donor_id,$stock->bar_code,$blood_group_id);

                  $get_stock_quantity=$this->recipient->get_stock_quantity($stock->bag_type_id,$stock->component_id,$exist_ids,$stock->donors_id,'',$stock->blood_grp_id);
                  // echo "<pre>"; print_r($get_stock_quantity); exit;
                  $component_total_qty = $get_stock_quantity['total_qty'];
                  if($get_stock_quantity['total_qty']> 0)
                  {
                    $component_qty=$component_total_qty;
                 
               $check_script = "";
                /*$table.="<tr id='stock_tr_".$stock->id."'>";
                $table.="<td><input type='checkbox' class='stock_checkbox_box'  name='stock_check[]' value='".$stock->id."'>".$check_script."<input type='hidden' name='cross_match' id='cross_match' value=''/></td>";
                
                $table.="<td>".$stock->donor_code."</td>"; 
                $table.="<td>".$stock->bar_code."</td>"; 
                

                $table.="<td>".$stock->blood_group."</td>"; 
                
                $table.="<td>".$stock->component_name."</td>"; 
                $table.="<td>".$stock->volumn."</td>";
                $table.="<td>".$created_date."</td>"; 
                $table.="<td>".$expiry_date."</td>"; 
                $table.="<td>".$status."</td>";
                
                //$table.="<td>".$stock->component_price."</td>";
                $table.="<td>".$component_qty."</td>"; 
                
                $table.="</tr>";*/

                 $table.="<tr id='stock_tr_".$stock->id."'>";
                $table.="<td><input type='checkbox' class='stock_checkbox_box'  name='stock_check[]' value='".$stock->id."'>".$check_script."<input type='hidden' name='cross_match' id='cross_match' value=''/></td>";
                
                $table.="<td>".$stock->donor_code."</td>"; 
                $table.="<td>".$stock->bar_code."</td>"; 
                

                $table.="<td>".$stock->blood_group."</td>"; 
                $table.="<td>".$stock->component_name."</td>"; 
                $table.="<td>".$stock->volumn."</td>";
                $table.="<td>".$created_date."</td>"; 
                $table.="<td>".$expiry_date."</td>"; 
                $table.="<td>".$status."</td>";
                
                //$table.="<td>".$stock->component_price."</td>";
                $table.="<td>".$component_qty."</td>"; 
                $table.="</tr>";
               //echo $table;exit;
               }


              }
              
              echo json_encode(array('st'=>1,'data'=>$table));
            }
            else
            {
                echo json_encode(array('st'=>0, 'msg'=>'<tr><td colspan=11 style="text-align:center;" > No record Found</td></tr>' ));
            }
          }
          else
          {
            echo json_encode(array('st'=>0, 'msg'=>'<tr><td colspan=11 style="text-align:center;" > No record Found</td></tr>' ));
          }
       
      }

      public function get_stocks_available_old()
      {
        $component_name=$this->input->post('component_name');
        $bar_code=$this->input->post('bar_code');
        $get_donor_list= $this->recipient->get_donor_list_component($component_name,$bar_code);
        //echo $this->db->last_query();
        //print_r($get_donor_list);
        //die;
        $check_script='';
        $check_script.='';
        $expiry_date='';
        $component_qty='';
        $exist_id=$this->input->post('vals');
        $bag_type_id=$this->input->post('bag_type_id');
        $donor_id=$this->input->post('donor_id');
        $bar_code=$this->input->post('bar_code');
        $blood_group_id=$this->input->post('blood_group_id');
        if((!empty($vals)) && ($vals!=''))
          $ids=implode(',', $vals);
        else
          $ids='';
       if((!empty($component_name))||($bar_code!=''))
          {
            $stock_data=$this->recipient->get_stock_available($bag_type_id,$component_name,$exist_id,$bar_code,$donor_id,$blood_group_id);
            $table="";

            //print '<pre>'; print_r($stock_data);die;

            if($stock_data!="empty")
            {
              foreach ($stock_data as $stock) 
              {
               // print_r($stock);

                if(strtotime($stock->expiry_date) >86400){
                  $expiry_date= date('d-m-Y H:i:s',strtotime($stock->expiry_date));
                  }
                  else
                  {
                    $expiry_date='';
                  }
                  $component_qty='';
                  $get_stock_quantity= $this->recipient->get_stock_quantity($bag_type_id,$stock->component_id,$exist_id,$stock->donor_id,$stock->bar_code,$blood_group_id);
                  $component_total_qty = $get_stock_quantity['total_qty'];
                  if($get_stock_quantity['total_qty']> 0){
                    $component_qty=$component_total_qty;
                  
                  // if($stock->qty>=0){
                  //   $component_qty=$stock->qty;
                  // }
               $check_script = "";
                $table.="<tr id='stock_tr_".$stock->id."'>";
                $table.="<td><input type='checkbox' class='stock_checkbox_box'  name='stock_check[]' value='".$stock->id."'>".$check_script."<input type='hidden' name='cross_match' id='cross_match' value=''/></td>";
                $table.="<td>".$stock->component_name."</td>"; 
                $table.="<td>".$component_qty."</td>"; 
                $table.="<td>".$expiry_date."</td>"; //$stock->bag_type
                $table.="<td>".$stock->donor_code."</td>"; //$stock->blood_group
                $table.="<td>".$stock->bar_code."</td>"; 
                
                $table.="<td>".$stock->component_price."</td>";
                $table.="</tr>";
               }
              }
              echo json_encode(array('st'=>1,'data'=>$table));
            }
            else
            {
                echo json_encode(array('st'=>0, 'msg'=>'<tr><td colspan=3 style="text-align:center;" > No record Found</td></tr>' ));
            }
          }
          else
          {
            echo json_encode(array('st'=>0, 'msg'=>'<tr><td colspan=3 style="text-align:center;" > No record Found</td></tr>' ));
          }
       // } /* remove condition of bage type */
        // else
        // {
        //     echo json_encode(array('st'=>0, 'msg'=>'<tr><td colspan=3 style="text-align:center;" > No record Found</td></tr>' ));
        // }
      }
     // function to get stock listing  by component and bag type

      // Function to add to cart 
      public function add_to_cart()
      {
        $ids=implode(',', $this->input->post('vals'));
        
        /*echo "<pre>"; print_r($ids); exit;*/
        if($this->input->post('cross_match_id')!=0)
        {
          $cross_match_id=implode(',', $this->input->post('cross_match_id'));
        }
        else
        {
          $cross_match_id='';
        }
        
        $users_data = $this->session->userdata('auth_users');       
        $branch_id=$users_data['parent_id'];
        $expiry_date='';
        if(!empty($ids) && $ids!="")
        {
          if(!empty($ids))
          {
            if(!empty($cross_match_id))
            {
            $issue_data=$this->recipient->get_cross_match_data($cross_match_id);
            //print '<pre>'; print_r($issue_data);die;
            }
            else
            {
              $issue_data=$this->recipient->get_by_id_issue_bag($ids);
            }
            //echo "<pre>"; print_r($issue_data); exit;
            
            $data = array();
            $i = 1;
            $table="";
            if(!empty($this->session->userdata('total_price_issue_bag')) && $this->session->userdata('total_price_issue_bag')!="" )
            {
                $total_price=$this->session->userdata('total_price_issue_bag');
               // /echo $total_price;die;
            }
            else
            {
              $total_price=0;
            }
            
           // echo "<pre>"; print_r($issue_data); exit;
            if($issue_data!="empty")
            { 
              foreach ($issue_data as $issue) 
              {
                if(strtotime($issue->expiry_date)>86400)
                {
                  $expiry_date=date('d-m-Y H:i:s',strtotime($issue->expiry_date));
                }
                else
                {
                 $expiry_date=date('d-m-Y'); 
                }
                if(isset($issue->issue_date) && strtotime($issue->issue_date)>86400)
                {
                  $issue_date=date('d-m-Y',strtotime($issue->issue_date));
                }
                else
                {
                 $issue_date=date('d-m-Y'); 
                }
                $check_script='';
                

              $check_script_issue= "<script>
                              var today = new Date();
                              $('#issue_datepicker_".$issue->id."').datepicker({
                              format: 'dd-mm-yyyy',
                              autoclose: true,
                              startDate: '".$expiry_date."'
                              });</script>";

                $total_price=$total_price+$issue->component_price;
                $table.="<tr id='data_tr_".$issue->id."'>";
                $table.="<input type='hidden' class='stock_checkbox' name='issue_check[]' value='".$issue->id."'><input type='hidden' name='donor_id[".$issue->id."]' value='".$issue->donor_id."'>";
                $table.="<td>".$issue->donor_code.' '.$issue->bar_code.'<br>'.$issue->component_name."<input type='hidden' name='comp_name[".$issue->id."]' id='comp_name_'".$issue->id." value='".$issue->component_name."'><input type='hidden' name='comp_id[".$issue->id."]' id='comp_id_'".$issue->id." value='".$issue->component_id."'></td>";

                  $table.="<input type='hidden' name='leuco_depleted[".$issue->id."]' id='leuco_depleted_'".$issue->id."' value='1'>";

                  $table.="<td>".$issue->qty."<input type='hidden' name='quantity[".$issue->id."]' id='quantity_'".$issue->id."' value='".$issue->qty."'></td>";
                  $table.="<input type='hidden' name='expiry_date[".$issue->id."]'  value='".$expiry_date."' id='expiry_datepicker_".$issue->id."'>".$check_script.""; 
                  
                  
                   
                   if($issue->cross_match=='1')
                   {
                    $cross_m = 'Yes';
                   }
                   else
                   {
                    $cross_m = 'No';
                   }
                   
                   $table.="<td>".$cross_m."</td>";
                    $table.="<td></td>";

                  
                   $table.="<input type='hidden' name='bar_code[".$issue->id."]' id='bar_code_'".$issue->id." value='".$issue->bar_code."'>"; 
                
                 
                
                  $table.="<td><input type='text' class='iss_cart_price' name='component_price[".$issue->id."]' id='comp_price_".$issue->id."' value='".$issue->component_price."' onkeyup='return change_price(".$issue->component_price.",this.value)'></td>"; 
                   $table.="<td><input type='text' name='issue_date[".$issue->id."]' value='".$issue_date."' id='issue_datepicker_".$issue->id."'>".$check_script_issue."</td>";   
                  $table.="<td><input class='btn-update' type='button' name='rev' value='Remove' onclick='remove_component_from_bucket(".$issue->id."); return false;'> </td>"; 
                  $table.="</tr>";
                $i++;
              }
              if((!empty($total_price)) && ($total_price!=''))
              {
                $this->session->set_userdata('total_price_issue_bag',$total_price);
              }
                 echo json_encode(array('st'=>1, 'data'=>$table, 'total_price'=>$this->session->userdata('total_price_issue_bag') , 'net_amount'=>$this->session->userdata('total_price_issue_bag'), 'paid_amount'=>$this->session->userdata('total_price_issue_bag'), 'balance'=>0 ));
            }
            else
            {
                echo "<tr><td colspan=4 style='text-align:center;' > No record Found</td></tr>";
            }
          }
          else
          {
           echo "<tr><td colspan=4 style='text-align:center;' > No record Found</td></tr>";
          }
        }
        else
        {
          echo "<tr><td colspan=4 style='text-align:center;' > No record Found</td></tr>";
        }
      }

      public function add_to_cartold()
      {
        $ids=implode(',', $this->input->post('vals'));
        if($this->input->post('cross_match_id')!=0)
        {
          $cross_match_id=implode(',', $this->input->post('cross_match_id'));
        }
        else
        {
          $cross_match_id='';
        }
        
        $users_data = $this->session->userdata('auth_users');       
        $branch_id=$users_data['parent_id'];
        $expiry_date='';
        if(!empty($ids) && $ids!="")
        {
          if(!empty($ids))
          {
            if(!empty($cross_match_id))
            {
            $issue_data=$this->recipient->get_cross_match_data($cross_match_id);
            //print '<pre>'; print_r($issue_data);die;
            }
            else
            {
              $issue_data=$this->recipient->get_by_id_issue_bag($ids);
            }
            
            
            $data = array();
            $i = 1;
            $table="";
            if(!empty($this->session->userdata('total_price_issue_bag')) && $this->session->userdata('total_price_issue_bag')!="" )
            {
                $total_price=$this->session->userdata('total_price_issue_bag');
               // /echo $total_price;die;
            }
            else
            {
              $total_price=0;
            }
            if($issue_data!="empty")
            { 
              foreach ($issue_data as $issue) 
              {
                if(strtotime($issue->expiry_date)>86400)
                {
                  $expiry_date=date('d-m-Y H:i:s',strtotime($issue->expiry_date));
                }
                else
                {
                 $expiry_date=date('d-m-Y'); 
                }
                if(isset($issue->issue_date) && strtotime($issue->issue_date)>86400)
                {
                  $issue_date=date('d-m-Y',strtotime($issue->issue_date));
                }
                else
                {
                 $issue_date=date('d-m-Y'); 
                }
                $check_script='';
                // $check_script= "<script>
                //           var today = new Date();
                //           $('#expiry_datepicker_".$issue->id."').datepicker({
                //           format: 'dd-mm-yyyy',
                //           autoclose: true,
                //           startDate: '".$expiry_date."'
                //           });</script>";

              $check_script_issue= "<script>
                              var today = new Date();
                              $('#issue_datepicker_".$issue->id."').datepicker({
                              format: 'dd-mm-yyyy',
                              autoclose: true,
                              startDate: '".$expiry_date."'
                              });</script>";

                $total_price=$total_price+$issue->component_price;
                $table.="<tr id='data_tr_".$issue->id."'>";
                $table.="<input type='hidden' class='stock_checkbox' name='issue_check[]' value='".$issue->id."'><input type='hidden' name='donor_id[".$issue->id."]' value='".$issue->donor_id."'>";
                $table.="<td>".$issue->component_name."<input type='hidden' name='comp_name[".$issue->id."]' id='comp_name_'".$issue->id." value='".$issue->component_name."'><input type='hidden' name='comp_id[".$issue->id."]' id='comp_id_'".$issue->id." value='".$issue->component_id."'></td>";

                  

                  $table.="<td>".$issue->qty."<input type='hidden' name='quantity[".$issue->id."]' id='quantity_'".$issue->id."' value='".$issue->qty."'></td>";
                  //$table.="<td><input type='text' name='expiry_date[".$issue->id."]'  value='".$expiry_date."' id='expiry_datepicker_".$issue->id."'>".$check_script."</td>"; 

                   $table.="<td>".$issue->donor_code."</td>";
                   //$table.="<td><input type='text' name='bar_code[".$issue->id."]' id='bar_code_'".$issue->id." value='".$issue->bar_code."'></td>"; 
                
                 
                
                  $table.="<td><input type='text' class='iss_cart_price' name='component_price[".$issue->id."]' id='comp_price_".$issue->id."' value='".$issue->component_price."' onkeyup='return change_price(".$issue->component_price.",this.value)'></td>"; 
                   $table.="<td><input type='text' name='issue_date[".$issue->id."]' value='".$issue_date."' id='issue_datepicker_".$issue->id."'>".$check_script_issue."</td>";   
                  $table.="<td><input class='btn-update' type='button' name='rev' value='Remove' onclick='remove_component_from_bucket(".$issue->id."); return false;'> </td>"; 
                  $table.="</tr>";
                $i++;
              }
              if((!empty($total_price)) && ($total_price!=''))
              {
                $this->session->set_userdata('total_price_issue_bag',$total_price);
              }
                 echo json_encode(array('st'=>1, 'data'=>$table, 'total_price'=>$this->session->userdata('total_price_issue_bag') , 'net_amount'=>$this->session->userdata('total_price_issue_bag'), 'paid_amount'=>$this->session->userdata('total_price_issue_bag'), 'balance'=>0 ));
            }
            else
            {
                echo "<tr><td colspan=4 style='text-align:center;' > No record Found</td></tr>";
            }
          }
          else
          {
           echo "<tr><td colspan=4 style='text-align:center;' > No record Found</td></tr>";
          }
        }
        else
        {
          echo "<tr><td colspan=4 style='text-align:center;' > No record Found</td></tr>";
        }
      }


      public function save_issue_bag()
      {
          
          //hms_blood_patient_to_recipient
        $reg_no=generate_unique_id(3);
        
        $reg_no_r=generate_unique_id(43); //for isssue code//
        //echo $reg_no_r; die;
        //$reg_no=generate_unique_id(43); for isssue code//
        $users_data = $this->session->userdata('auth_users');       
        $branch_id=$users_data['parent_id'];
        $component_name=$this->input->post('comp_name');
        
        $post= $this->input->post(); 
         // echo "<pre>"; print_r($post); die; exit;
        $issue_check = $this->input->post('issue_check'); 
        $component_price=$this->input->post('component_price');
        $bar_code=$this->input->post('bar_code');
        $donor_id=$this->input->post('donor_id');

        $verify_doctor_id=$this->input->post('verify_doctor_id');
        $technician_id=$this->input->post('technician_id');
        $billing_for=$this->input->post('billing_for');
        $patient_relation=$this->input->post('patient_relation');

        $component_id=$this->input->post('comp_id');
        $expiry_date=$this->input->post('expiry_date');
        $issue_date=$this->input->post('issue_date');
        $quantity=$this->input->post('quantity');
        $leuco_depleted=$this->input->post('leuco_depleted');
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $patient_id=$this->input->post('patient_id');
        $recipient_id=$this->input->post('recipient_id');
        $session_res_id=$this->session->set_userdata('recipient_id_session',$recipient_id);
        $issue_by_mode=$this->input->post('issue_by_mode');
          if(!empty($issue_by_mode))
        {
          $issue_by_mode_new=$this->input->post('issue_by_mode');
        }
        else
        {
          $issue_by_mode_new='';
        }
        
        //
        
        /*$this->blood_general_model->common_update('hms_blood_patient_to_recipient',$recipient_data_array, $where_recipient);*/ 
        
        $update_stock_gr_status=array('issue_code'=>$reg_no_r);
        $this->db->where('patient_id',$patient_id);
        $this->db->update('hms_blood_patient_to_recipient',$update_stock_gr_status);
  
        
       // print '<pre>'; print_r($component_name);die;
       $d=0;
        foreach($component_name as $key=>$val)
        {
          $get_blood_group= $this->recipient->get_blood_grp_detail($donor_id[$key]);
          $stock_tab_array_insert=array(
                                'branch_id'=>$branch_id,
                                'donor_id'=>$donor_id[$key],
                                'recipient_id'=>$this->input->post('recipient_id'),
                                'bag_type_id'=>$this->input->post('bag_id'),
                                'component_id'=>$component_id[$key],
                                'component_name'=>$val,
                                'component_price'=>$component_price[$key],
                                'bar_code'=>$bar_code[$key],
                                'credit'=>$quantity[$key],  /* new value replace by 1*/
                                'qty'=>$quantity[$key],  /* new value replace by 1*/
                                
                                'is_issued'=>2,
                                'blood_group_id'=>$get_blood_group[0]->blood_group_id,
                                'issue_date'=>date('Y-m-d',strtotime($issue_date[$key])),
                                'expiry_date'=>date('Y-m-d H:i:s',strtotime($expiry_date[$key])),
                                'leuco_depleted'=>$leuco_depleted[$key],
                                'created_date'=>date('Y-m-d H:i:s'),
                                'created_by'=>$users_data['parent_id'],
                                'status'=>1,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                'flag'=>2,
                              );

          
          $issue_comp_array=array(
                        'branch_id'=>$users_data['parent_id'],
                        'recipient_id'=>$recipient_id,
                        'component_id'=>$component_id[$key],
                        'component_name'=>$val, 
                        'component_price'=>$component_price[$key], 
                        'issue_date'=>date('Y-m-d',strtotime($issue_date[$key])),
                        'expiry_date'=>date('Y-m-d H:i:s',strtotime($expiry_date[$key])),
                        'leuco_depleted'=>$leuco_depleted[$key],
                        'created_date'=>date('Y-m-d H:i:s'),
                        'created_by'=>$users_data['parent_id'],
                        'status'=>1,
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                     );

          
          
          $stock_tab_array_update=array('is_issued'=>1,'modified_date'=>date('Y-m-d H:i:s'), 'modified_by'=>$users_data['id']);
          $where=" branch_id=".$branch_id." AND id=".$issue_check[$d]." ";
          $this->blood_general_model->common_update('hms_blood_stock',$stock_tab_array_update,$where);  
          
          //echo $this->db->last_query(); exit;
          
          
          
          $this->blood_general_model->common_insert('hms_blood_stock',$stock_tab_array_insert);
          $this->blood_general_model->common_insert('hms_blood_recipient_issued_components',$issue_comp_array);
        
        $d++;
        }
      // For Stock table
        $recipient_data_array=array(
                                      'total_amount'=>$this->input->post('total_amount'),
                                      'shipment_amount'=>$this->input->post('shipment_charge'),
                                      //'issue_code'=>$reg_no_r,
                                      'discount_percent'=>$this->input->post('discount_percent'),
                                      'discount_amount'=>$this->input->post('discount'),
                                      'net_amount'=>$this->input->post('net_amount'),
                                      'paid_amount'=>$this->input->post('paid_amount'),
                                      'verify_doctor_id'=>$verify_doctor_id,
                                      'technician_id'=>$technician_id,
                                      'billing_for'=>$billing_for,
                                      'patient_relation'=>$patient_relation,
                                     

                                      'balance'=>$this->input->post('balance'),
                                      'issue_by_mode'=>$issue_by_mode_new,
                                      'modified_by'=>$users_data['id'],
                                      'modified_date'=>date('Y-m-d H:i:s'),
                                      'blood_issued'=>1,
                                   );
        $where_recipient=" branch_id=".$branch_id." and patient_id=".$patient_id." and id=".$recipient_id."  ";
        $this->blood_general_model->common_update('hms_blood_patient_to_recipient',$recipient_data_array, $where_recipient); 
        //print_r($this->db->last_query()); die; exit;
        // Code to insert in payment table
        if($this->input->post('balance')=="" ||  $this->input->post('balance')=="0" || $this->input->post('balance')=="0.00")
          {
          $bal="1.00";
        }
        else
        {
          $bal=$this->input->post('balance');
        }
        $payment_data = array(
                          'parent_id'=>$recipient_id,
                          'branch_id'=>$users_data['parent_id'],
                          'section_id'=>'10',
                          'doctor_id'=>0,
                          'hospital_id'=>0,
                          'patient_id'=>$patient_id,
                          'total_amount'=>str_replace(',', '', $this->input->post('total_amount')),
                          'discount_amount'=>$this->input->post('discount'),
                          'net_amount'=>str_replace(',', '', $this->input->post('net_amount')),
                          
                          'credit'=>str_replace(',', '', $this->input->post('net_amount')),
                          'debit'=>$this->input->post('paid_amount'),
                          'pay_mode'=>$this->input->post('payment_mode'),
                          'balance'=>$bal,
                          'paid_amount'=>$this->input->post('paid_amount'),
                          
                          'created_date'=>date('Y-m-d H:i:s'),
                          'created_by'=>$users_data['id']
                        );
        $payment_id= $this->blood_general_model->common_insert('hms_payment',$payment_data);

        $field_name=$this->input->post('field_name');  
        $field_id=$this->input->post('field_id');  
          if(!empty($field_name))
          {
            $post_field_value_name= $field_name;
            $counter_name= count($post_field_value_name); 
            for($i=0;$i<$counter_name;$i++) 
            {
              $data_field_value= array(
              'field_value'=>$field_name[$i],
              'field_id'=>$field_id[$i],
              'type'=>19,
              'section_id'=>19,
              'p_mode_id'=>$this->input->post('payment_mode'),
              'branch_id'=>$users_data['parent_id'],
              'parent_id'=>$recipient_id,
              'ip_address'=>$_SERVER['REMOTE_ADDR']
              );
                $this->db->set('created_by',$users_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
            }
          }


          /* genereate receipt number */
        if(in_array('218',$users_data['permission']['section']))
        {
           if($this->input->post('paid_amount')>0)
           {
             $hospital_receipt_no= check_hospital_receipt_no();
             $data_receipt_data= array(
                                'branch_id'=>$users_data['parent_id'],
                                'section_id'=>17,
                                'parent_id'=>$recipient_id,
                                'payment_id'=>$payment_id,
                                'reciept_prefix'=>$hospital_receipt_no['prefix'],
                                'reciept_suffix'=>$hospital_receipt_no['suffix'],
                                'created_by'=>$users_data['id'],
                                'created_date'=>date('Y-m-d H:i:s')
                                );
           $this->db->insert('hms_branch_hospital_no',$data_receipt_data);
           }
        }

      
       /* genereate receipt number */
      // Code to insert in payment table
          echo "Recipient successfully created";

      }

      public function save_issue_bag20200504()
      {
        $reg_no=generate_unique_id(3);
        //$reg_no=generate_unique_id(43); for isssue code//
        $users_data = $this->session->userdata('auth_users');       
        $branch_id=$users_data['parent_id'];
        $component_name=$this->input->post('comp_name');
        $component_price=$this->input->post('component_price');
        $bar_code=$this->input->post('bar_code');
        $donor_id=$this->input->post('donor_id');
        $component_id=$this->input->post('comp_id');
        $expiry_date=$this->input->post('expiry_date');
        $issue_date=$this->input->post('issue_date');
        $quantity=$this->input->post('quantity');
        $leuco_depleted=$this->input->post('leuco_depleted');
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $patient_id=$this->input->post('patient_id');
        $recipient_id=$this->input->post('recipient_id');
        $session_res_id=$this->session->set_userdata('recipient_id_session',$recipient_id);
        $issue_by_mode=$this->input->post('issue_by_mode');
          if(!empty($issue_by_mode))
        {
          $issue_by_mode_new=$this->input->post('issue_by_mode');
        }
        else
        {
          $issue_by_mode_new='';
        }

       // print '<pre>'; print_r($component_name);die;
        foreach($component_name as $key=>$val)
        {
          $get_blood_group= $this->recipient->get_blood_grp_detail($donor_id[$key]);
          $stock_tab_array_insert=array(
                                'branch_id'=>$branch_id,
                                'donor_id'=>$donor_id[$key],
                                'recipient_id'=>$this->input->post('recipient_id'),
                                'bag_type_id'=>$this->input->post('bag_id'),
                                'component_id'=>$component_id[$key],
                                'component_name'=>$val,
                                'component_price'=>$component_price[$key],
                                'bar_code'=>$bar_code[$key],
                                'credit'=>$quantity[$key],  /* new value replace by 1*/
                                'is_issued'=>2,
                                'blood_group_id'=>$get_blood_group[0]->blood_group_id,
                                'issue_date'=>date('Y-m-d',strtotime($issue_date[$key])),
                                'expiry_date'=>date('Y-m-d H:i:s',strtotime($expiry_date[$key])),
                                'leuco_depleted'=>$leuco_depleted[$key],
                                'created_date'=>date('Y-m-d H:i:s'),
                                'created_by'=>$users_data['parent_id'],
                                'status'=>1,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                'flag'=>2,
                              );

          
          $issue_comp_array=array(
                        'branch_id'=>$users_data['parent_id'],
                        'recipient_id'=>$recipient_id,
                        'component_id'=>$component_id[$key],
                        'component_name'=>$val, 
                        'component_price'=>$component_price[$key], 
                        'issue_date'=>date('Y-m-d',strtotime($issue_date[$key])),
                        'expiry_date'=>date('Y-m-d H:i:s',strtotime($expiry_date[$key])),
                        'leuco_depleted'=>$leuco_depleted[$key],
                        'created_date'=>date('Y-m-d H:i:s'),
                        'created_by'=>$users_data['parent_id'],
                        'status'=>1,
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                     );

          $stock_tab_array_update=array('is_issued'=>1,'modified_date'=>date('Y-m-d H:i:s'), 'modified_by'=>$users_data['id']);
          $where=" branch_id=".$branch_id." and donor_id=".$donor_id[$key]." and flag=1 and component_id=".$component_id[$key]." ";
          $this->blood_general_model->common_update('hms_blood_stock',$stock_tab_array_update,$where);  
          $this->blood_general_model->common_insert('hms_blood_stock',$stock_tab_array_insert);
          $this->blood_general_model->common_insert('hms_blood_recipient_issued_components',$issue_comp_array);
        }
      // For Stock table
        $recipient_data_array=array(
                                      'total_amount'=>$this->input->post('total_amount'),
                                      'shipment_amount'=>$this->input->post('shipment_charge'),
                                      'issue_code'=>$reg_no,
                                      'discount_percent'=>$this->input->post('discount_percent'),
                                      'discount_amount'=>$this->input->post('discount'),
                                      'net_amount'=>$this->input->post('net_amount'),
                                      'paid_amount'=>$this->input->post('paid_amount'),
                                      'balance'=>$this->input->post('balance'),
                                      'issue_by_mode'=>$issue_by_mode_new,
                                      'modified_by'=>$users_data['id'],
                                      'modified_date'=>date('Y-m-d H:i:s'),
                                      'blood_issued'=>1,
                                   );
        $where_recipient=" branch_id=".$branch_id." and patient_id=".$patient_id." and id=".$recipient_id."  ";
        $this->blood_general_model->common_update('hms_blood_patient_to_recipient',$recipient_data_array, $where_recipient);  
        // Code to insert in payment table
        if($this->input->post('balance')=="" ||  $this->input->post('balance')=="0" || $this->input->post('balance')=="0.00")
          {
          $bal="1.00";
        }
        else
        {
          $bal=$this->input->post('balance');
        }
        $payment_data = array(
                          'parent_id'=>$recipient_id,
                          'branch_id'=>$users_data['parent_id'],
                          'section_id'=>'10',
                          'doctor_id'=>0,
                          'hospital_id'=>0,
                          'patient_id'=>$patient_id,
                          'total_amount'=>str_replace(',', '', $this->input->post('total_amount')),
                          'discount_amount'=>$this->input->post('discount'),
                          'net_amount'=>str_replace(',', '', $this->input->post('net_amount')),
                          'credit'=>str_replace(',', '', $this->input->post('net_amount')),
                          'debit'=>$this->input->post('paid_amount'),
                          'pay_mode'=>$this->input->post('payment_mode'),
                          'balance'=>$bal,
                          'paid_amount'=>$this->input->post('paid_amount'),
                          'created_date'=>date('Y-m-d H:i:s'),
                          'created_by'=>$users_data['id']
                        );
        $payment_id= $this->blood_general_model->common_insert('hms_payment',$payment_data);
        $field_name=$this->input->post('field_name');  
        $field_id=$this->input->post('field_id');  
          if(!empty($field_name))
          {
            $post_field_value_name= $field_name;
            $counter_name= count($post_field_value_name); 
            for($i=0;$i<$counter_name;$i++) 
            {
              $data_field_value= array(
              'field_value'=>$field_name[$i],
              'field_id'=>$field_id[$i],
              'type'=>19,
              'section_id'=>19,
              'p_mode_id'=>$this->input->post('payment_mode'),
              'branch_id'=>$users_data['parent_id'],
              'parent_id'=>$recipient_id,
              'ip_address'=>$_SERVER['REMOTE_ADDR']
              );
                $this->db->set('created_by',$users_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
            }
          }


          /* genereate receipt number */
        if(in_array('218',$users_data['permission']['section']))
        {
           if($this->input->post('paid_amount')>0)
           {
             $hospital_receipt_no= check_hospital_receipt_no();
             $data_receipt_data= array(
                                'branch_id'=>$users_data['parent_id'],
                                'section_id'=>17,
                                'parent_id'=>$recipient_id,
                                'payment_id'=>$payment_id,
                                'reciept_prefix'=>$hospital_receipt_no['prefix'],
                                'reciept_suffix'=>$hospital_receipt_no['suffix'],
                                'created_by'=>$users_data['id'],
                                'created_date'=>date('Y-m-d H:i:s')
                                );
           $this->db->insert('hms_branch_hospital_no',$data_receipt_data);
           }
        }

      
       /* genereate receipt number */
      // Code to insert in payment table
          echo "Recipient successfully created";

      }



public function print_issue_recipt($ids="")
  {
      //print_r($ids);die;

      $user_detail= $this->session->userdata('auth_users');
      $this->load->model('general/general_model');
      if(!empty($ids))
      {
        $recipient_id= $ids;
      }
      else
      {
        $recipient_id= $this->session->userdata('recipient_id_session');
      }

      $data['page_title'] = "Add Blood Bank Issue";
      $get_detail_by_id= $this->recipient->get_by_id_recipient($recipient_id);
      $get_detail_by_detail= $this->recipient->get_by_id_print_recipient($recipient_id,$get_detail_by_id['branch_id']);
   
      $get_by_id_data=$this->recipient->get_recipient_issued_components($recipient_id);


        $get_payment_detail=$this->recipient->get_recipient_payment_details_print($recipient_id);

        $get_payment_detail_by_id= $this->general_model->payment_mode_by_id('',$get_payment_detail['pay_mode']);
        
      $template_format= $this->recipient->template_format(array('section_id'=>10,'types'=>1,'sub_section'=>0,'branch_id'=>$get_detail_by_id['branch_id']));

      //print_r($get_payment_detail_by_id);die;

      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['patient_detail']= $get_detail_by_id;
      $data['all_detail']= $get_detail_by_detail;
      $data['payment_mode_by_id']= $get_payment_detail_by_id;
      $data['component_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;

      
      $data['particular_data']=$this->recipient->get_recipient_particular($recipient_id);
      //print"<pre>"; print_r($data['particular_data']);
      $this->load->view('blood_bank/bloodbank_print_setting/print_template_bloodbank',$data);

  }
  
  
  public function print_invoice($ids="")
  {
      //print_r($ids);die;

      $user_detail= $this->session->userdata('auth_users');
      $this->load->model('general/general_model');
      if(!empty($ids))
      {
        $recipient_id= $ids;
      }
      else
      {
        $recipient_id= $this->session->userdata('recipient_id_session');
      }

      $data['page_title'] = "Add Blood Bank Issue";
      $get_detail_by_id= $this->recipient->get_by_id_recipient($recipient_id);
      $get_detail_by_detail= $this->recipient->get_by_id_print_recipient($recipient_id,$get_detail_by_id['branch_id']);
   
      $get_by_id_data=$this->recipient->get_recipient_issued_components($recipient_id);


        $get_payment_detail=$this->recipient->get_recipient_payment_details_print($recipient_id);
        $get_payment_detail_by_id= $this->general_model->payment_mode_by_id('',$get_payment_detail['pay_mode']);
        
      $template_format= $this->recipient->template_format(array('section_id'=>10,'types'=>1,'sub_section'=>0,'branch_id'=>$get_detail_by_id['branch_id']));

      //print_r($get_payment_detail_by_id);die;

      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['patient_detail']= $get_detail_by_id;
      $data['all_detail']= $get_detail_by_detail;
      $data['payment_mode_by_id']= $get_payment_detail_by_id;
      $data['component_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;

      
      $data['particular_data']=$this->recipient->get_recipient_particular($recipient_id);
      //print"<pre>"; print_r($data['particular_data']);
      $this->load->view('blood_bank/bloodbank_print_setting/print_template_invoice_bloodbank',$data);

  }

  public function print_issue_recipt2021($ids="")
  {
      //print_r($ids);die;

      $user_detail= $this->session->userdata('auth_users');
      $this->load->model('general/general_model');
      if(!empty($ids))
      {
        $recipient_id= $ids;
      }
      else
      {
        $recipient_id= $this->session->userdata('recipient_id_session');
      }

      $data['page_title'] = "Add Blood Bank Issue";
      $get_detail_by_id= $this->recipient->get_by_id_recipient($recipient_id);
      $get_detail_by_detail= $this->recipient->get_by_id_print_recipient($recipient_id,$get_detail_by_id['branch_id']);
   
      $get_by_id_data=$this->recipient->get_recipient_issued_components($recipient_id);


        $get_payment_detail=$this->recipient->get_recipient_payment_details_print($recipient_id);
        $get_payment_detail_by_id= $this->general_model->payment_mode_by_id('',$get_payment_detail['pay_mode']);
        
      $template_format= $this->recipient->template_format(array('section_id'=>10,'types'=>1,'sub_section'=>0,'branch_id'=>$get_detail_by_id['branch_id']));

      //print_r($get_payment_detail_by_id);die;

      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['patient_detail']= $get_detail_by_id;
      $data['all_detail']= $get_detail_by_detail;
      $data['payment_mode_by_id']= $get_payment_detail_by_id;
      $data['component_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;

      //print_r($data['user_detail']);
       //print"<pre>"; print_r($data['patient_detail']);
      //print"<pre>";print_r($data['all_detail']);
      //print"<pre>"; print_r($data['payment_mode_by_id']);
     //print"<pre>"; print_r($data['component_detail']);
     //print"<pre>"; print_r($data['payment_mode']);
      //die;
      $this->load->view('blood_bank/bloodbank_print_setting/print_template_bloodbank',$data);

  }


public function update_issue_bag()
{
  //print_r($_POST);die;
  $users_data = $this->session->userdata('auth_users');       
  $branch_id=$users_data['parent_id'];
  $component_name=$this->input->post('comp_name');
  $component_price=$this->input->post('component_price');
  $bar_code=$this->input->post('bar_code');
  $donor_id=$this->input->post('donor_id');
  $component_id=$this->input->post('comp_id');

  $verify_doctor_id=$this->input->post('verify_doctor_id');
  $technician_id=$this->input->post('technician_id');
  $billing_for=$this->input->post('billing_for');
  $patient_relation=$this->input->post('patient_relation');

  $issue_date=$this->input->post('issue_date');
  $expiry_date=$this->input->post('expiry_date');
  $leuco_depleted=$this->input->post('leuco_depleted');
  $quantity=$this->input->post('quantity');
  $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
  $patient_id=$this->input->post('patient_id');
  $recipient_id=$this->input->post('recipient_id');
   $issue_by_mode=$this->input->post('issue_by_mode');
    //print_r($this->input->post());
       // die;
     $stock_ids=$this->input->post('rec_ids');

    if(!empty($issue_by_mode))
        {
          $issue_by_mode_new=$this->input->post('issue_by_mode');
        }
        else
        {
          $issue_by_mode_new='';
        }
  $stock_exist_rec_ids=array_keys($stock_ids);

  $update_stock_status=array("status"=>0);
  $this->db->where('recipient_id',$recipient_id);
  $this->db->update('hms_blood_stock',$update_stock_status);
  // For Stock Table
  foreach($component_name as $key=>$val)
  {

    // Stock Updation starts here
    $check_record_already_exist_or_not=$this->recipient->check_data_avail_in_stock($key,$branch_id);//$recipient_id,$donor_id[$key],$component_id[$key]
    

    
    if($check_record_already_exist_or_not=="empty")
    {
       
      $get_blood_group= $this->recipient->get_blood_grp_detail($donor_id[$key]);
      $stock_tab_array_insert=array(
                                'branch_id'=>$branch_id,
                                'donor_id'=>$donor_id[$key],
                                'recipient_id'=>$this->input->post('recipient_id'),
                                'bag_type_id'=>$this->input->post('bag_id'),
                                'component_id'=>$component_id[$key],
                                'component_name'=>$val,
                                'component_price'=>$component_price[$key],
                                'bar_code'=>$bar_code[$key],
                                'credit'=>$quantity[$key],
                                'blood_group_id'=>$get_blood_group[0]->blood_group_id,
                                'is_issued'=>2,
                                'issue_date'=>date('Y-m-d',strtotime($issue_date[$key])),
                                'expiry_date'=>date('Y-m-d H:i:s',strtotime($expiry_date[$key])),
                                'leuco_depleted'=>$leuco_depleted[$key],
                                'created_date'=>date('Y-m-d H:i:s'),
                                'created_by'=>$users_data['parent_id'],
                                'status'=>1,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                'flag'=>2,
                              );

      $stock_tab_array_update=array('is_issued'=>1,'modified_date'=>date('Y-m-d H:i:s'), 'modified_by'=>$users_data['id']);
          $where=" branch_id=".$branch_id." and donor_id=".$donor_id[$key]." and flag=1 and component_id=".$component_id[$key]." ";
     $this->blood_general_model->common_update('hms_blood_stock',$stock_tab_array_update,$where); 

    $this->blood_general_model->common_insert('hms_blood_stock',$stock_tab_array_insert);
    //echo $this->db->last_query();die;

      
    }
    else
    {
    
      $get_blood_group= $this->recipient->get_blood_grp_detail($donor_id[$key]);
      $id=$check_record_already_exist_or_not['id'];
      $stock_tab_array_update=array(
                                'component_id'=>$component_id[$key],
                                'component_name'=>$val,
                                'component_price'=>$component_price[$key],
                                'bar_code'=>$bar_code[$key],
                                'issue_date'=>date('Y-m-d',strtotime($issue_date[$key])),
                                'expiry_date'=>date('Y-m-d H:i:s',strtotime($expiry_date[$key])),
                                'leuco_depleted'=>$leuco_depleted[$key],
                                'blood_group_id'=>$get_blood_group[0]->blood_group_id,
                                'credit'=>$quantity[$key],
                                'status'=>1,
                                'modified_date'=>date('Y-m-d H:i:s'),
                                'modified_by'=>$users_data['parent_id'],
                                );
      $where=" branch_id=".$branch_id." and donor_id=".$donor_id[$key]." and flag=2  and component_id=".$component_id[$key]." and recipient_id=".$recipient_id."  and id=".$id." ";
      
      $this->blood_general_model->common_update('hms_blood_stock',$stock_tab_array_update,$where); 
      //echo $this->db->last_query();
    }
    // delete records frm stock 

        //$this->recipient->remove_inactive_entries_stock($recipient_id,$donor_id,$branch_id);
     
    // delete records from stock
    // Stock Updation process ends here

  
    $this->db->where('recipient_id',$recipient_id);
    $this->db->delete('hms_blood_recipient_issued_components');
    // patient components update start here
      $issue_comp_array=array(
                                'branch_id'=>$users_data['parent_id'],
                                'recipient_id'=>$recipient_id,
                                'component_id'=>$component_id[$key],
                                'component_name'=>$val, 
                                'component_price'=>$component_price[$key], 
                                'created_date'=>date('Y-m-d H:i:s'),
                                'created_by'=>$users_data['parent_id'],
                                'status'=>1,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                 );
      $this->blood_general_model->common_insert('hms_blood_recipient_issued_components',$issue_comp_array);

    // patient component update ends here
  }  // for loop ends here
  




    // patient_to_recipient_update
      $recipient_data_array=array(
                                      'total_amount'=>$this->input->post('total_amount'),
                                      'shipment_amount'=>$this->input->post('shipment_charge'),
                                      'discount_percent'=>$this->input->post('discount_percent'),
                                      'discount_amount'=>$this->input->post('discount'),
                                      'net_amount'=>$this->input->post('net_amount'),
                                      'paid_amount'=>$this->input->post('paid_amount'),
                                      'balance'=>$this->input->post('balance'),
                                      'verify_doctor_id'=>$verify_doctor_id,
                                      'technician_id'=>$technician_id,
                                      'billing_for'=>$billing_for,
                                      'patient_relation'=>$patient_relation,
                                      'issue_by_mode'=>$issue_by_mode_new,
                                      'modified_by'=>$users_data['id'],
                                      'modified_date'=>date('Y-m-d H:i:s'),
                                      'blood_issued'=>1,
                                   );
        $where_recipient=" branch_id=".$branch_id." and patient_id=".$patient_id." and id=".$recipient_id." and id=".$recipient_id."  ";
        $this->blood_general_model->common_update('hms_blood_patient_to_recipient',$recipient_data_array, $where_recipient);  
    // patient_to_recipient update

    // update payment details
        //echo $this->input->post('hidden_pay_id'); die;
        if($this->input->post('hidden_pay_id') > 0)
        { //echo $this->input->post('hidden_pay_id'); die;
          $payment_id=$this->input->post('hidden_pay_id');
            if($this->input->post('balance')=="" ||  $this->input->post('balance')=="0" || $this->input->post('balance')=="0.00")
          {
            $bal="1.00";
          }
          else
          {
            $bal=$this->input->post('balance');
          }
          $payment_data = array(
                           'total_amount'=>str_replace(',', '', $this->input->post('total_amount')),
                            'discount_amount'=>$this->input->post('discount'),
                            'net_amount'=>str_replace(',', '', $this->input->post('net_amount')),
                            'credit'=>str_replace(',', '', $this->input->post('net_amount')),
                            
                            'debit'=>$this->input->post('paid_amount'),
                            'pay_mode'=>$this->input->post('payment_mode'),
                            'balance'=>$bal,
                            'paid_amount'=>$this->input->post('paid_amount'),
                            );
          $payment_where=" id=".$this->input->post('hidden_pay_id')." and section_id=10 and parent_id=".$recipient_id." ";
          $this->blood_general_model->common_update('hms_payment',$payment_data,$payment_where);

          $this->db->where(array('branch_id'=>$branch_id,'parent_id'=>$recipient_id,'type'=>19,'section_id'=>19));
          $this->db->delete('hms_payment_mode_field_value_acc_section');
          $field_name=$this->input->post('field_name');  
          $field_id=$this->input->post('field_id');  
            if(!empty($field_name))
            {
              $post_field_value_name= $field_name;
              $counter_name= count($post_field_value_name); 
              for($i=0;$i<$counter_name;$i++) 
              {
                $data_field_value= array(
                'field_value'=>$field_name[$i],
                'field_id'=>$field_id[$i],
                'type'=>19,
                'section_id'=>19,
                'p_mode_id'=>$this->input->post('payment_mode'),
                'branch_id'=>$users_data['parent_id'],
                'parent_id'=>$recipient_id,
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                  $this->db->set('created_by',$users_data['id']);
                  $this->db->set('created_date',date('Y-m-d H:i:s'));
                  $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
              }
            }
        }
        else
        {
          ///insert payment 

          if($this->input->post('balance')=="" ||  $this->input->post('balance')=="0" || $this->input->post('balance')=="0.00")
          {
          $bal="1.00";
        }
        else
        {
          $bal=$this->input->post('balance');
        }
        $payment_data = array(
                          'parent_id'=>$recipient_id,
                          'branch_id'=>$users_data['parent_id'],
                          'section_id'=>'10',
                          'doctor_id'=>0,
                          'hospital_id'=>0,
                          'patient_id'=>$patient_id,
                          'total_amount'=>str_replace(',', '', $this->input->post('total_amount')),
                          'discount_amount'=>$this->input->post('discount'),
                          'net_amount'=>str_replace(',', '', $this->input->post('net_amount')),
                          
                          'credit'=>str_replace(',', '', $this->input->post('net_amount')),
                          'debit'=>$this->input->post('paid_amount'),
                          'pay_mode'=>$this->input->post('payment_mode'),
                          'balance'=>$bal,
                          'paid_amount'=>$this->input->post('paid_amount'),
                          
                          'created_date'=>date('Y-m-d H:i:s'),
                          'created_by'=>$users_data['id']
                        );
        $payment_id= $this->blood_general_model->common_insert('hms_payment',$payment_data);

        $field_name=$this->input->post('field_name');  
        $field_id=$this->input->post('field_id');  
          if(!empty($field_name))
          {
            $post_field_value_name= $field_name;
            $counter_name= count($post_field_value_name); 
            for($i=0;$i<$counter_name;$i++) 
            {
              $data_field_value= array(
              'field_value'=>$field_name[$i],
              'field_id'=>$field_id[$i],
              'type'=>19,
              'section_id'=>19,
              'p_mode_id'=>$this->input->post('payment_mode'),
              'branch_id'=>$users_data['parent_id'],
              'parent_id'=>$recipient_id,
              'ip_address'=>$_SERVER['REMOTE_ADDR']
              );
                $this->db->set('created_by',$users_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
            }
          }

        }

    // Update payment details
       echo "Recipient successfully updated";

}
public function update_issue_bag2021()
{
  //print_r($_POST);die;
  $users_data = $this->session->userdata('auth_users');       
  $branch_id=$users_data['parent_id'];
  $component_name=$this->input->post('comp_name');
  $component_price=$this->input->post('component_price');
  $bar_code=$this->input->post('bar_code');
  $donor_id=$this->input->post('donor_id');
  $component_id=$this->input->post('comp_id');

  $issue_date=$this->input->post('issue_date');
  $expiry_date=$this->input->post('expiry_date');
  $leuco_depleted=$this->input->post('leuco_depleted');
  $quantity=$this->input->post('quantity');
  $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
  $patient_id=$this->input->post('patient_id');
  $recipient_id=$this->input->post('recipient_id');
   $issue_by_mode=$this->input->post('issue_by_mode');
    //print_r($this->input->post());
       // die;
     $stock_ids=$this->input->post('rec_ids');

    if(!empty($issue_by_mode))
        {
          $issue_by_mode_new=$this->input->post('issue_by_mode');
        }
        else
        {
          $issue_by_mode_new='';
        }
  $stock_exist_rec_ids=array_keys($stock_ids);

  $update_stock_status=array("status"=>0);
  $this->db->where('recipient_id',$recipient_id);
  $this->db->update('hms_blood_stock',$update_stock_status);
  // For Stock Table
  foreach($component_name as $key=>$val)
  {

    // Stock Updation starts here
    $check_record_already_exist_or_not=$this->recipient->check_data_avail_in_stock($key,$branch_id);//$recipient_id,$donor_id[$key],$component_id[$key]
    

    
    if($check_record_already_exist_or_not=="empty")
    {
       
      $get_blood_group= $this->recipient->get_blood_grp_detail($donor_id[$key]);
      $stock_tab_array_insert=array(
                                'branch_id'=>$branch_id,
                                'donor_id'=>$donor_id[$key],
                                'recipient_id'=>$this->input->post('recipient_id'),
                                'bag_type_id'=>$this->input->post('bag_id'),
                                'component_id'=>$component_id[$key],
                                'component_name'=>$val,
                                'component_price'=>$component_price[$key],
                                'bar_code'=>$bar_code[$key],
                                'credit'=>$quantity[$key],
                                'blood_group_id'=>$get_blood_group[0]->blood_group_id,
                                'is_issued'=>2,
                                'issue_date'=>date('Y-m-d',strtotime($issue_date[$key])),
                                'expiry_date'=>date('Y-m-d H:i:s',strtotime($expiry_date[$key])),
                                'leuco_depleted'=>$leuco_depleted[$key],
                                'created_date'=>date('Y-m-d H:i:s'),
                                'created_by'=>$users_data['parent_id'],
                                'status'=>1,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                'flag'=>2,
                              );

      $stock_tab_array_update=array('is_issued'=>1,'modified_date'=>date('Y-m-d H:i:s'), 'modified_by'=>$users_data['id']);
          $where=" branch_id=".$branch_id." and donor_id=".$donor_id[$key]." and flag=1 and component_id=".$component_id[$key]." ";
     $this->blood_general_model->common_update('hms_blood_stock',$stock_tab_array_update,$where); 

    $this->blood_general_model->common_insert('hms_blood_stock',$stock_tab_array_insert);
    //echo $this->db->last_query();die;

      
    }
    else
    {
    
      $get_blood_group= $this->recipient->get_blood_grp_detail($donor_id[$key]);
      $id=$check_record_already_exist_or_not['id'];
      $stock_tab_array_update=array(
                                'component_id'=>$component_id[$key],
                                'component_name'=>$val,
                                'component_price'=>$component_price[$key],
                                'bar_code'=>$bar_code[$key],
                                'issue_date'=>date('Y-m-d',strtotime($issue_date[$key])),
                                'expiry_date'=>date('Y-m-d H:i:s',strtotime($expiry_date[$key])),
                                'leuco_depleted'=>$leuco_depleted[$key],
                                'blood_group_id'=>$get_blood_group[0]->blood_group_id,
                                'credit'=>$quantity[$key],
                                'status'=>1,
                                'modified_date'=>date('Y-m-d H:i:s'),
                                'modified_by'=>$users_data['parent_id'],
                                );
      $where=" branch_id=".$branch_id." and donor_id=".$donor_id[$key]." and flag=2  and component_id=".$component_id[$key]." and recipient_id=".$recipient_id."  and id=".$id." ";
      
      $this->blood_general_model->common_update('hms_blood_stock',$stock_tab_array_update,$where); 
      //echo $this->db->last_query();
    }
    // delete records frm stock 

        //$this->recipient->remove_inactive_entries_stock($recipient_id,$donor_id,$branch_id);
     
    // delete records from stock
    // Stock Updation process ends here

  
    $this->db->where('recipient_id',$recipient_id);
    $this->db->delete('hms_blood_recipient_issued_components');
    // patient components update start here
      $issue_comp_array=array(
                                'branch_id'=>$users_data['parent_id'],
                                'recipient_id'=>$recipient_id,
                                'component_id'=>$component_id[$key],
                                'component_name'=>$val, 
                                'component_price'=>$component_price[$key], 
                                'created_date'=>date('Y-m-d H:i:s'),
                                'created_by'=>$users_data['parent_id'],
                                'status'=>1,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                 );
      $this->blood_general_model->common_insert('hms_blood_recipient_issued_components',$issue_comp_array);

    // patient component update ends here
  }  // for loop ends here
  




    // patient_to_recipient_update
      $recipient_data_array=array(
                                      'total_amount'=>$this->input->post('total_amount'),
                                      'shipment_amount'=>$this->input->post('shipment_charge'),
                                      'discount_percent'=>$this->input->post('discount_percent'),
                                      'discount_amount'=>$this->input->post('discount'),
                                      'net_amount'=>$this->input->post('net_amount'),
                                      'paid_amount'=>$this->input->post('paid_amount'),
                                      'balance'=>$this->input->post('balance'),
                                      'issue_by_mode'=>$issue_by_mode_new,
                                      'modified_by'=>$users_data['id'],
                                      'modified_date'=>date('Y-m-d H:i:s'),
                                      'blood_issued'=>1,
                                   );
        $where_recipient=" branch_id=".$branch_id." and patient_id=".$patient_id." and id=".$recipient_id." and id=".$recipient_id."  ";
        $this->blood_general_model->common_update('hms_blood_patient_to_recipient',$recipient_data_array, $where_recipient);  
    // patient_to_recipient update

    // update payment details
        if($this->input->post('hidden_pay_id') > 0)
        {
          $payment_id=$this->input->post('hidden_pay_id');
            if($this->input->post('balance')=="" ||  $this->input->post('balance')=="0" || $this->input->post('balance')=="0.00")
          {
            $bal="1.00";
          }
          else
          {
            $bal=$this->input->post('balance');
          }
          $payment_data = array(
                           'total_amount'=>str_replace(',', '', $this->input->post('total_amount')),
                            'discount_amount'=>$this->input->post('discount'),
                            'net_amount'=>str_replace(',', '', $this->input->post('net_amount')),
                            'credit'=>str_replace(',', '', $this->input->post('net_amount')),
                            'debit'=>$this->input->post('paid_amount'),
                            'pay_mode'=>$this->input->post('payment_mode'),
                            'balance'=>$bal,
                            'paid_amount'=>$this->input->post('paid_amount'),
                            );
          $payment_where=" id=".$this->input->post('hidden_pay_id')." and section_id=10 and parent_id=".$recipient_id." ";
          $this->blood_general_model->common_update('hms_payment',$payment_data,$payment_where);

          $this->db->where(array('branch_id'=>$branch_id,'parent_id'=>$recipient_id,'type'=>19,'section_id'=>19));
          $this->db->delete('hms_payment_mode_field_value_acc_section');
          $field_name=$this->input->post('field_name');  
          $field_id=$this->input->post('field_id');  
            if(!empty($field_name))
            {
              $post_field_value_name= $field_name;
              $counter_name= count($post_field_value_name); 
              for($i=0;$i<$counter_name;$i++) 
              {
                $data_field_value= array(
                'field_value'=>$field_name[$i],
                'field_id'=>$field_id[$i],
                'type'=>19,
                'section_id'=>19,
                'p_mode_id'=>$this->input->post('payment_mode'),
                'branch_id'=>$users_data['parent_id'],
                'parent_id'=>$recipient_id,
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                  $this->db->set('created_by',$users_data['id']);
                  $this->db->set('created_date',date('Y-m-d H:i:s'));
                  $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
              }
            }
        }

    // Update payment details
       echo "Recipient successfully updated";

}

  public function donor_list()
  {
      $post = $this->input->post();
      if(isset($post) && !empty($post))
      {   
        $donor_list = $this->session->userdata('donor_list');

        if(isset($donor_list) && !empty($donor_list))
        {
          $donor_list = $donor_list; 
        }
        else
        {
          $donor_list = [];
        }

          $p = count($donor_list)+1; 
          $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
          $group_data= $this->blood_general_model->get_blood_group_by_id($post['blood_group_id']);

          $donor_list[] = array('charge_id'=>$p,'donor_actual_id'=>$post['donor_actual_id'],'donor_id'=>$post['donor_id'],'blood_group'=>$group_data[0]->blood_group,'camp_id'=>$post['camp_id'],'blood_group_id'=>$post['blood_group_id'],'quantity'=>$post['quantity'], 'option'=>$post['option'], 'leuco_depleted'=>$post['leuco_depleted'],'bar_code'=>$post['bar_code'],'expiry_date'=>$post['expiry_date'],'component_price'=>$post['component_price']);
          $this->session->set_userdata('donor_list', $donor_list);
          $html_data = $this->donor_comp_list();
          $response_data = array('html_data'=>$html_data);
          $json = json_encode($response_data,true);
          echo $json;

      }
  }

    private function donor_comp_list()
    {
         $donor_list_data = $this->session->userdata('donor_list');
         $check_script="<script>$('#selectAll').on('click', function () { 
                            if ($(this).hasClass('allChecked')) {
                            $('.booked_checkbox').prop('checked', false);
                            } else {
                             
                            $('.booked_checkbox').prop('checked', true);
                            }
                            $(this).toggleClass('allChecked');
                            })</script>"; 
        $rows = '<thead class="bg-theme"><tr>           
                    <th width="60" align="center">
                     <input name="selectall" class="" id="selectAll" value="" type="checkbox">'. $check_script.'
                    </th>
                    <th>S.No.</th>
                    <th>Donor Id</th>
                     <th>Blood Group</th>
                    <th>Component Name</th>
                    <th>Leuco Deplecated</th>
                    <th>Quantity</th>
                    <th>Bar Code</th>
                    <th>Expiry Date</th>
                  </tr></thead>';  
           if(isset($donor_list_data) && !empty($donor_list_data))
           {
              
              $i = 1;
              $lc_status='';
              foreach($donor_list_data as $donor_list_data)
              {
                //$particulardata['particular'] for delete ids
                if($donor_list_data['leuco_depleted']==1)
                {
                  $lc_status="Yes";
                }
                else
                {
                  $lc_status="No";
                }
                 $rows .= '<tr>
                            <td width="60" align="center"><input type="checkbox" name="donor_li[]" class="part_checkbox booked_checkbox" value="'.$i.'" ></td>
                            <td>'.$i.'</td>
                            <td>'.$donor_list_data['donor_id'].'<input type="hidden" name="donar_detail['.$i.'][donar_actual_id]" type="text" id="'.$donor_list_data['donor_actual_id'].'" value="'.$donor_list_data['donor_actual_id'].'"/></td>
                            <td>'.$donor_list_data['blood_group'].'<input type="hidden" type="text" id="'.$donor_list_data['blood_group_id'].'"  name="donar_detail['.$i.'][blood_group_id]" value="'.$donor_list_data['blood_group_id'].'"/></td>
                            <td>'.$donor_list_data['option'].'<input type="hidden" type="text" id="'.$donor_list_data['camp_id'].'" name="donar_detail['.$i.'][camp_id]" value="'.$donor_list_data['camp_id'].'"/></td>
                            <td>'.$lc_status.'<input type="hidden" type="text" id="'.$donor_list_data['leuco_depleted'].'" name="donar_detail['.$i.'][lc_status]" value="'.$donor_list_data['leuco_depleted'].'"/></td>
                            <td>'.$donor_list_data['quantity'].'<input type="hidden" id="'.$donor_list_data['quantity'].'" name="donar_detail['.$i.'][quantity]" value="'.$donor_list_data['quantity'].'"/> </td>
                            <td>'.$donor_list_data['bar_code'].'<input type="hidden" id="'.$donor_list_data['bar_code'].'" name="donar_detail['.$i.'][bar_code]" value="'.$donor_list_data['bar_code'].'"/></td>
                            <td>'.$donor_list_data['expiry_date'].'<input type="hidden" id="'.$donor_list_data['expiry_date'].'" name="donar_detail['.$i.'][expiry_date]" value="'.$donor_list_data['expiry_date'].'"/><input type="hidden" id="'.$donor_list_data['component_price'].'" name="donar_detail['.$i.'][component_price]" value="'.$donor_list_data['component_price'].'"/></td>
                            
                        </tr>';
                 $i++;               
              } 
           }
           else
           {
               $rows .= '<tr>  
                          <td colspan="5" align="center" class=" text-danger "><div class="text-center">Donor data not available.</div></td>
                        </tr>';
           }

           
           return $rows;
    }

    public function remove_donor_list()
    {
       $post =  $this->input->post();
       
       if(isset($post['donor_li']) && !empty($post['donor_li']))
       {
           $donor_list = $this->session->userdata('donor_list');
           
           //print_r($donor_list);
           
           foreach($donor_list as $key=>$donor_ids)
           { 
            
             if(in_array($donor_ids['charge_id'],$post['donor_li']))
              {  
                unset($donor_list[$key]);
               }
           }  
     
        
        $this->session->set_userdata('donor_list',$donor_list);
        $html_data = $this->donor_comp_list();
        
        $response_data = array('html_data'=>$html_data);
        $json = json_encode($response_data,true);
        echo $json;
       }
    }

     public function advance_search()
    {
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
      
        $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
        $data['component_list']=$this->blood_general_model->get_component_list(); 

        $data['form_data'] = array(
                                    "start_date"=>"",
                                    "end_date"=>"",
                                    'component_id'=>'',
                                    'recipient_id'=>'',
                                    'blood_group'=>'',
                                    'mobile'=>'',
                                    'requirement_date'=>''                                  
                          
                                     );
        //print_r($data['form_data']);
        //die;
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('recipient_search', $merge);
        }
        $recipient_search = $this->session->userdata('recipient_search');
        if(isset($recipient_search) && !empty($recipient_search))
        {
            $data['form_data'] = $recipient_search;
        }
        $this->load->view('blood_bank/recipient/advance_search',$data);
    }


   public function reset_search()
    {
        $this->session->unset_userdata('recipient_search');
    }

    function get_particular_data($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->recipient->get_particular_data($vals);  
        if(!empty($result))
        {
            echo json_encode($result,true);
        } 
        } 
    }

    function add_perticulars($recipient_id="")
    {
       $post= $this->input->post();

       //echo "<pre>"; print_r($post); exit;
       if(!empty($post['particular']))
       {
                     $this->recipient->save_particulars($recipient_id);
          
                           if(strtotime($post['date'])>86400)
                            {
                              $payment_date=date('d-m-Y',strtotime($post['date']));
                            }
                            else
                            {
                              $payment_date=date('d-m-Y'); 
                            }

                            $table.="<tr id='data_tr_".$i."'>";
                            $table.="<input type='hidden' name='particular_id[".$post['particular_id']."]' value='".$post['particular_id']."'>";
                            $table.="<td><input type='hidden' name='comp_name[".$post['particular_id']."]' id='comp_name_'".$post['particular_id']." value='".$post['particular']."'><input type='hidden' name='comp_id[".$post['particular_id']."]' id='comp_id_'".$post['particular_id']." value='".$post['particular_id']."'></td>";
                            
                            $table.="<td>".$post['qty']."<input type='hidden' name='quantity[".$post['particular_id']."]' id='quantity_'".$post['particular_id']."' value='".$post['qty']."'>";
                            $table.="<td>&nbsp;</td><td>".$post['particular']."</td>"; 

                            
                            
                            $table.="<td><input type='text' class='iss_cart_price' name='component_price[".$post['particular_id']."]' id='comp_price_".$post['particular_id']."' value='".$post['amount']."' onkeyup='return change_price(".$post['amount'].",this.value)'></td>"; 
                            $table.="<td><input type='text' name='expiry_date[".$post['particular_id']."]'  value='".$payment_date."' id='expiry_datepicker_".$post['particular_id']."' readonly></td>"; 
                            $table.="<td><input class='btn-update' type='button' name='rev' value='Remove' onclick='remove_particular_charge(".$post['particular_id'].",".$recipient_id."); return false;'> </td>"; 
                            $table.="</tr>";


          //$this->session->set_flashdata('success','Particular has been successfully added.');
          $data=array('success'=>1,'data'=>$table,'recipient_id'=>$recipient_id);
          echo json_encode($data);
          return false;
           //echo '1';exit;
       }
    }

    public function delete_charges($id="",$recipient_id="")
    {
       if(!empty($id) && $id>0)
       {
              $result = $this->recipient->delete_charges($id,$recipient_id);
              $this->session->set_flashdata('success','Particular has been successfully deleted');
              $data=array('success'=>1,'recipient_id'=>$recipient_id);
              //print_r($data); exit;
              echo json_encode($data);
              return false;
      }
    }
    
     public function print_transfusion($patient_id="",$recipient_id='')
 {
     $users_data = $this->session->userdata('auth_users');
     $b_id='';
    if(!empty($recipient_id))
    {
      $data['pat_data']=$this->recipient->get_by_id_recipient($recipient_id);
      $data['bag_id']=$data['pat_data']['bag_id'];
      $b_id=$data['pat_data']['b_id'];
      
    }
    
    
    $data['doctors_name'] = $data['pat_data']['doctors_name'];
    //$data['issue_dates'] = date('d-m-Y H:i',strtotime($data['pat_data']['requirement_date']));
    
     $data['issue_dates'] = date('d-m-Y',strtotime($data['pat_data']['requirement_date'])).' '.date('H:i',strtotime($data['pat_data']['require_time']));
     
    //echo "<pre>"; print_r($data['pat_data']); exit;
    $data['cross_match_detail']=$this->recipient->cross_match_detail($recipient_id);
    //echo "<pre>"; print_r($data['cross_match_detail']); exit;
    $data['recipient_component_detail']=$this->recipient->get_recipient_component_detail($recipient_id);

    $data['vitals_list']=$this->recipient->vitals_list();
    
    if(count($data['cross_match_detail'])>0)
    {
        $data['componenet_detail']=$this->recipient->component_detail_by_cross_match_id($data['cross_match_detail']->id,$recipient_id);


       
          if((!isset($post) || empty($post)) && count($data['componenet_detail'])>=1)
         {
            
              $donor_list = $this->session->userdata('donor_list');
              
              if(isset($donor_list) && !empty($donor_list))
              {
                $donor_list = $donor_list; 
              }
              else
              {
                $donor_list = [];
              }
              $i=1;
              foreach($data['componenet_detail'] as $comp_detail)
              {
                //$particulars_data['charge_id']
                  $donor_list[] = array('charge_id'=>$i,'donor_actual_id'=>$comp_detail['donor_actual_id'],'donor_id'=>$comp_detail['donor_id'],'blood_group'=>$comp_detail['blood_group'],'camp_id'=>$comp_detail['camp_id'],'blood_group_id'=>$comp_detail['blood_group_id'],'quantity'=>$comp_detail['qty'], 'option'=>$comp_detail['option'], 'leuco_depleted'=>$comp_detail['leuco_deplecated'],'bar_code'=>$comp_detail['barcode'],'component_price'=>$comp_detail['component_price'],'expiry_date'=>date('d-m-Y H:i:s',strtotime($comp_detail['expiry_date'])));
                 
                   $i++;
              }
         }
         
        $data['donor_list'] = $donor_list;
        $data['vitals_list']=$this->recipient->vitals_detail_by_cross_match_id($data['cross_match_detail']->id,$recipient_id);

     }
     
        
      $template_format= $this->recipient->transfusion_template_format(array('section_id'=>10,'types'=>1,'sub_section'=>0,'branch_id'=>$users_data['parent_id']));

      $data['template_data']=$template_format;
    // echo "<pre>"; print_r($data); exit;
    $data['recepeint_detail']= $this->recipient->get_by_id($patient_id);
    //echo "<pre>"; print_r($data['recepeint_detail']); exit;
    $get_detail_by_id= $this->recipient->get_by_id_recipient($recipient_id);
      
      $data['patient_detail']= $get_detail_by_id;
      
       $get_detail_by_detail= $this->recipient->get_by_id_print_recipient($recipient_id,$get_detail_by_id['branch_id']);
        $data['all_detail']= $get_detail_by_detail;
        
         $data['form_data']=array(
                                'data_id'=>$data['cross_match_detail']->id,
                                'transfustion_date'=>date('d-m-Y',strtotime($data['pat_data']['requirement_date'])),
                                'transfustion_time'=>date('H:i:s',strtotime($data['cross_match_detail']->starttime_transfusion_time)),
                                'file_name'=>$data['cross_match_detail']->attachement); 
                                
         $issue_data=$this->recipient->get_recipient_issued_components($recipient_id);
      
      $data['issue_data']=$issue_data;     
      $data['pat_data_edit']   = $this->recipient->get_by_id_patient_details($patient_id);
     $this->load->view('blood_bank/bloodbank_print_setting/print_template_transfusion',$data);
    
    
     
 }
    
    public function print_compatilbilty($patient_id="",$recipient_id='')
 {
    //issue_dates pat_data_edit
     $users_data = $this->session->userdata('auth_users');
     $b_id='';
    if(!empty($recipient_id))
    {
      $data['pat_data']=$this->recipient->get_by_id_recipient($recipient_id);
      //echo "<pre>"; print_r($data['pat_data']); exit;
      $data['bag_id']=$data['pat_data']['bag_id'];
      $b_id=$data['pat_data']['b_id'];
    }
     $data['issue_dates'] = date('d-m-Y',strtotime($data['pat_data']['requirement_date'])).' '.date('H:i',strtotime($data['pat_data']['require_time']));
     
     
     
      $data['doctors_name'] = $data['pat_data']['doctors_name'];
      
      
    $data['cross_match_detail']=$this->recipient->cross_match_detail($recipient_id);
    
    $data['recipient_component_detail']=$this->recipient->get_recipient_component_detail($recipient_id);

    $data['vitals_list']=$this->recipient->vitals_list();
    
    if(count($data['cross_match_detail'])>0)
    {
        $data['componenet_detail']=$this->recipient->component_detail_by_cross_match_id($data['cross_match_detail']->id,$recipient_id);


       
          if((!isset($post) || empty($post)) && count($data['componenet_detail'])>=1)
         {
            
              $donor_list = $this->session->userdata('donor_list');
              
              if(isset($donor_list) && !empty($donor_list))
              {
                $donor_list = $donor_list; 
              }
              else
              {
                $donor_list = [];
              }
              $i=1;
              foreach($data['componenet_detail'] as $comp_detail)
              {
                //$particulars_data['charge_id']
                  $donor_list[] = array('charge_id'=>$i,'donor_actual_id'=>$comp_detail['donor_actual_id'],'donor_id'=>$comp_detail['donor_id'],'blood_group'=>$comp_detail['blood_group'],'camp_id'=>$comp_detail['camp_id'],'blood_group_id'=>$comp_detail['blood_group_id'],'quantity'=>$comp_detail['qty'], 'option'=>$comp_detail['option'], 'leuco_depleted'=>$comp_detail['leuco_deplecated'],'bar_code'=>$comp_detail['barcode'],'component_price'=>$comp_detail['component_price'],'expiry_date'=>date('d-m-Y H:i:s',strtotime($comp_detail['expiry_date'])));
                 
                   $i++;
              }
         }
        $data['donor_list'] = $donor_list;
        $data['vitals_list']=$this->recipient->vitals_detail_by_cross_match_id($data['cross_match_detail']->id,$recipient_id);

     }
     
        
      $template_format= $this->recipient->compatilbilty_template_format(array('section_id'=>10,'types'=>1,'sub_section'=>0,'branch_id'=>$users_data['parent_id']));

      $data['template_data']=$template_format;
    // echo "<pre>"; print_r($data); exit;
    $data['recepeint_detail']= $this->recipient->get_by_id($patient_id);
    
    $data['pat_data_edit']   = $this->recipient->get_by_id_patient_details($patient_id);
    
  //print_r($data['pat_data_edit']); exit;
    
    $get_detail_by_id= $this->recipient->get_by_id_recipient($recipient_id);
      
      $data['patient_detail']= $get_detail_by_id;
      
       $get_detail_by_detail= $this->recipient->get_by_id_print_recipient($recipient_id,$get_detail_by_id['branch_id']);
        $data['all_detail']= $get_detail_by_detail;
        
         $data['form_data']=array(
                                'data_id'=>$data['cross_match_detail']->id,
                                'transfustion_date'=>date('d-m-Y',strtotime($data['cross_match_detail']->starttime_transfusion_date)),
                                'transfustion_time'=>date('H:i:s',strtotime($data['cross_match_detail']->starttime_transfusion_time)),
                                'file_name'=>$data['cross_match_detail']->attachement); 
                                
      $issue_data=$this->recipient->get_recipient_issued_components($recipient_id);


      
      $data['issue_data']=$issue_data;
     $this->load->view('blood_bank/bloodbank_print_setting/print_template_compatilbilty',$data);
    
    
     
   
 }
 
 
 public function send_email($ids="")
  { 
        
        if(!empty($ids) && $ids>0)
        {
          $this->load->library('m_pdf');
            $user_detail= $this->session->userdata('auth_users');
            $users_data= $this->session->userdata('auth_users');
            $this->load->model('general/general_model');
            if(!empty($ids))
            {
              $recipient_id= $ids;
            }
            else
            {
              $recipient_id= $this->session->userdata('recipient_id_session');
            }

            
            $get_detail_by_id= $this->recipient->get_by_id_recipient($recipient_id);
            $get_detail_by_detail= $this->recipient->get_by_id_print_recipient($recipient_id,$get_detail_by_id['branch_id']);
            //echo "<pre>"; print_r($get_detail_by_detail); exit;
            $get_by_id_data=$this->recipient->get_recipient_issued_components($recipient_id);


              $get_payment_detail=$this->recipient->get_recipient_payment_details_print($recipient_id);
              $get_payment_detail_by_id= $this->general_model->payment_mode_by_id('',$get_payment_detail['pay_mode']);
              
            $template_format= $this->recipient->template_format(array('section_id'=>10,'types'=>1,'sub_section'=>0,'branch_id'=>$get_detail_by_id['branch_id']));

            //print_r($get_payment_detail_by_id);die;

            $template_data=$template_format;
            $user_detail=$user_detail;
            $patient_detail= $get_detail_by_id;
            $all_detail= $get_detail_by_detail;
            $payment_mode_by_id= $get_payment_detail_by_id;
            $component_detail= $get_by_id_data;
            $payment_mode= $get_payment_detail;

            
            $particular_data=$this->recipient->get_recipient_particular($recipient_id);


            $report_templ_part = $this->recipient->report_html_template('',$user_detail['parent_id']);
            $page_header = $report_templ_part->page_header;
            $header_replace_part = $report_templ_part->page_details;

            if(!empty($all_detail['blood_print_list'][0]->relation))
            {
            $rel_simulation = get_simulation_name($all_detail['blood_print_list'][0]->relation_simulation_id);
            $header_replace_part = str_replace("{parent_relation_type}",$all_detail['blood_print_list'][0]->relation,$header_replace_part);
            }
            else
            {
            $header_replace_part = str_replace("{parent_relation_type}",'',$header_replace_part);
            }
            if(!empty($all_detail['blood_print_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['blood_print_list'][0]->relation_simulation_id);
            $header_replace_part = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['blood_print_list'][0]->relation_name,$header_replace_part);
            }
            else
            {
            $header_replace_part = str_replace("{parent_relation_name}",'',$header_replace_part);
            }

          $header_replace_part = str_replace("{patient_name}",$all_detail['blood_print_list'][0]->simulation.' '.$all_detail['blood_print_list'][0]->patient_name,$header_replace_part);
          $header_replace_part = str_replace("{pateint_reg_no}",$all_detail['blood_print_list'][0]->patient_code,$header_replace_part);

          $header_replace_part = str_replace("{hospital_name}",$all_detail['blood_print_list'][0]->doctor_hospital_name,$header_replace_part);
        $date='';
        $date=date('d-m-Y');
          $header_replace_part = str_replace("{created_date}",$date,$header_replace_part);
          

          $header_replace_part = str_replace("{blood_group}",$all_detail['blood_print_list'][0]->blood_group,$header_replace_part);

          
             
             $patient_address=$all_detail['blood_print_list'][0]->address;
             
        $header_replace_part = str_replace("{pateint_address}",$patient_address,$header_replace_part);

             $genders = array('0'=>'F','1'=>'M','2'=>'O');
                $gender = $genders[$all_detail['blood_print_list'][0]->gender];
                $age_y = $all_detail['blood_print_list'][0]->age_y; 
                $age_m = $all_detail['blood_print_list'][0]->age_m;
                $age_d = $all_detail['blood_print_list'][0]->age_d;

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
                if($patient_age!=''){
                  $patient1_age = '/'.$patient_age;
                }
                if($patient_age==''){
                  $patient1_age=$patient_age;
                }
                $gender_age = $gender.$patient1_age ;

        $header_replace_part = str_replace("{address}",$patient_address,$header_replace_part);
         $header_replace_part = str_replace("{issue_code}",$all_detail['blood_print_list'][0]->issue_code,$header_replace_part);
         $header_replace_part = str_replace("{gender_age}",$gender_age,$header_replace_part);
         
          $header_replace_part = str_replace("{gender}",$gender,$header_replace_part);
         
         $header_replace_part = str_replace("{age}",$patient1_age,$header_replace_part);

          
        $header_replace_part = str_replace("{requirement_date}",date('d-m-Y',strtotime($all_detail['blood_print_list'][0]->requirement_date)),$header_replace_part);




            $middle_replace_part = $report_templ_part->page_middle;

            $pos_start = strpos($middle_replace_part, '{start_loop}');
            $pos_end = strpos($middle_replace_part, '{end_loop}');
            $row_last_length = $pos_end-$pos_start;
            $row_loop = substr($middle_replace_part,$pos_start+12,$row_last_length-12);

            // Replace looping row//
            $rplc_row = trim(substr($middle_replace_part,$pos_start,$row_last_length+12));

            $middle_replace_part = str_replace($rplc_row,"{row_data}",$middle_replace_part);

            //echo "<pre>"; print_r($component_detail); exit;
            //////////////////////// 
            $i=1;
            $tr_html = "";
            foreach($component_detail as $component_list)
            { 

                //print_r($medicine_list);
                $tr = $row_loop;
                $tr = str_replace("{s_no}",$i,$tr);
                $tr = str_replace("{mrp}",$component_list->component_price,$tr);
                $tr = str_replace("{component_name}",$component_list->component_name,$tr);
                $tr = str_replace("{qty}",$component_list->credit,$tr);
                $tr = str_replace("{total_amount}",$component_list->component_price,$tr);
                $tr = str_replace("{donor_id}",$component_list->donor_code,$tr);
                $tr_html .= $tr;
                $i++;

            }

            if(!empty($particular_data))
            {
              $j=$i;
              foreach($particular_data as $particular)
              {
                  $tr = $row_loop;
                  $tr = str_replace("{s_no}",$j,$tr);
                  $tr = str_replace("{mrp}",$particular->price,$tr);
                  $tr = str_replace("{component_name}",$particular->particular,$tr);
                  $tr = str_replace("{qty}",$particular->quantity,$tr);
                  $tr = str_replace("{total_amount}",$particular->net_price,$tr);
                  $tr = str_replace("{donor_id}",'',$tr);
                   $tr_html .= $tr;
                  $j++;
              }
                
            }

           $middle_replace_part = str_replace("{row_data}",$tr_html,$middle_replace_part);

             $footer_data_part = $report_templ_part->page_footer;
             


            if(!empty($payment_mode['balance']))
            {
              $balance='';
              if($payment_mode['balance']=='1')
              {
                $balance='0.00';
              }
              else
              {
                $balance=$payment_mode['balance'];
              }
            }

            $patient_name =$all_detail['blood_print_list'][0]->patient_name;

            $middle_replace_part = str_replace("{patient_name}",$all_detail['blood_print_list'][0]->patient_name,$middle_replace_part);

            $middle_replace_part = str_replace("{paid_amount}",$patient_detail['paid_amount'],$middle_replace_part);
            $middle_replace_part = str_replace("{balance}",$balance,$middle_replace_part);
            $middle_replace_part = str_replace("{total_amount_full}",$payment_mode['total_amount'],$middle_replace_part);
            if($all_detail['blood_print_list'][0]->shipment_amount > 0)
            {
                $middle_replace_part = str_replace("{shipping_charge}",$all_detail['blood_print_list'][0]->shipment_amount,$middle_replace_part);

            }
            else
            {
                 $middle_replace_part = str_replace("Shipping Charge:",' ',$middle_replace_part);
                $middle_replace_part = str_replace("{shipping_charge}",' ',$middle_replace_part);
            }



             if(in_array('218',$users_data['permission']['section']))
                  {
                  if($all_detail['blood_print_list'][0]->paid_amount>0 && (!empty($all_detail['blood_print_list'][0]->reciept_prefix) || !empty($all_detail['blood_print_list'][0]->reciept_suffix)) )
                  {
                      $middle_replace_part = str_replace("{hospital_receipt_no}",$all_detail['blood_print_list'][0]->reciept_prefix.$all_detail['blood_print_list'][0]->reciept_suffix,$middle_replace_part);
                  }
                  }
                  else
                  {
                      $middle_replace_part = str_replace("{hospital_receipt_no}",'',$middle_replace_part);
                  }
                  $div_head='';
                  $div_val='';
                  $div_head='<div style="float:left;font-weight:bold;">Discount</div>';
                  $div_val='<div style="float:right;font-weight:bold;">{total_discount}</div>';

                  //echo $div_val;
                  //echo $div_head;
            if($payment_mode['discount_amount']=='0.00')
            {
             $middle_replace_part = str_replace("{dis_heading}",' ',$middle_replace_part); 
             $middle_replace_part = str_replace("{total_discount}",' ',$middle_replace_part); 
            }
            else
            {
             $middle_replace_part = str_replace("{dis_heading}",$div_head,$middle_replace_part); 
             $middle_replace_part = str_replace("{total_discount}",$payment_mode['discount_amount'],$middle_replace_part);  

            }


            //$middle_replace_part = str_replace("{total_discount}",$payment_mode['discount_amount'],$middle_replace_part);
            $middle_replace_part = str_replace("{net_amount}",$payment_mode['net_amount'],$middle_replace_part);


            $get_amount = AmountInWords($payment_mode['net_amount']);

            $middle_replace_part = str_replace("{net_amount_word}",$get_amount,$middle_replace_part);

            $middle_replace_part = str_replace("{payment_mode}",$payment_mode_by_id[0]->payment_mode,$middle_replace_part);
            $middle_replace_part = str_replace("{signature}",ucfirst($user_detail['user_name']),$middle_replace_part);

            if(!empty($all_detail['blood_print_list'][0]->remarks))
            {
               $middle_replace_part = str_replace("{remarks}",$all_detail['blood_print_list'][0]->remarks,$middle_replace_part);
            }
            else
            {
               $middle_replace_part = str_replace("{remarks}",' ',$middle_replace_part);
               $middle_replace_part = str_replace("Remarks :",' ',$middle_replace_part);
               $middle_replace_part = str_replace("Remarks",' ',$middle_replace_part);

               
            }
            $middle_replace_part = str_replace("{signature}",ucfirst($user_detail['user_name']),$middle_replace_part);

            $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
            $this->m_pdf->pdf->WriteHTML($stylesheet,1); 
            $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
            $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);
            $this->m_pdf->pdf->autoPageBreak = false;
            $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
            $this->m_pdf->pdf->SetFooter($footer_data_part);
            $patient_name = str_replace(' ', '-', $patient_name);
            $pdfFilePath = $patient_name.'_report.pdf'; 
            $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
            // $this->load->library('m_pdf');
            $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
            //download it.
            $this->m_pdf->pdf->Output($pdfFilePath, "F");

            //echo $attachment = DIR_UPLOAD_PATH.'temp/reciept_report.pdf';  die;


            $data['page_title'] = 'Send Email';   
            $this->load->model('general/general_model'); 
           


            $data['form_error'] = [];
            $data['sign_error'] = [];
            $post = $this->input->post();
            $data['form_data'] = array(
                                         'booking_id'=>$ids,
                                         'type'=>$type,
                                         'subject'=>'',
                                         'email'=>'',
                                         'message'=>'',
                                         'email'=>''
                                      );
            if(isset($post) && !empty($post))
            {   
                

                $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('message', 'Message', 'trim|required'); 
                $this->form_validation->set_rules('subject', 'Subject', 'trim|required'); 

               if($this->form_validation->run() == TRUE)
               {
                    //print_r($post); exit;
                    $booking_id = $post['booking_id'];
                   
                    
                    $email = $post['email'];
                    $subject = $post['subject'];
                    $message = $post['message'];
                    $attachment = DIR_UPLOAD_PATH.'temp/'.$patient_name.'_report.pdf'; 
                    $this->load->library('general_library');

                    $response = $this->general_library->email($email,$subject,$message,'',$attachment,'','','','',$booking_data->branch_id);

                  if(!empty($attachment) && file_exists($attachment)) 
                  {
                  unlink($attachment);
                  } 

                  echo 1;
                  return false;
                

               }
               else
               {
                  $data['form_data'] = array(
                                            'booking_id'=>$post['booking_id'],
                                            'type'=>$post['type'],
                                            'email'=>$post['email'],
                                          'subject'=>$post['subject'],
                                          'message'=>$post['message'],
                                         );

                  $data['form_error'] = validation_errors();
               }  

                  
                     

            }
            $this->load->view('blood_bank/recipient/send_email',$data);
        }
        
    }
    
    
    public function recipient_excel()
    {
       // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('S NO.','Patient Code','Recipient Name','Mobile No.','Requirement Date','Blood Group','Created Date');
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
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              
             
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          
          //$list = $this->stock_model->get_datatables();  
          $list = $this->recipient->get_datatables();  
          
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $k=1;
               $exist_ids='';
                foreach ($list as $recipient) 
                {
                 
                 
                array_push($rowData,$k,$recipient->patient_code,$recipient->patient_name,$recipient->mobile_no,( strtotime($recipient->requirement_date) > 0 ? date('d-M-Y', strtotime($recipient->requirement_date)) : ''),$recipient->blood_group,date('d-M-Y', strtotime($recipient->requirement_date)));
                    $k++;
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
          header("Content-Disposition: attachment; filename=recipient_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }
    
    
    public function recipient_pdf()
    {    
        $data['print_status']="";
        //$data['data_list'] = $this->stock_model->get_datatables();  
        $data['data_list'] =  $this->recipient->get_datatables();  
        $this->load->view('blood_bank/recipient/recipient_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("recipient_list_".time().".pdf");
    }
    public function recipient_print()
    {    
      $data['print_status']="1";
     
      $data['data_list'] =  $this->recipient->get_datatables();
      $this->load->view('blood_bank/recipient/recipient_html',$data); 
    }
    
    
    public function import_recipient_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('form_validation');
        $this->load->library('excel');
        $data['page_title'] = 'Import Recipient excel';
        $arr_data = array();
        $header = array();
        $path='';

       // print_r($_FILES); die;
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

                    //echo "<pre> dfd";print_r(DIR_UPLOAD_PATH.'patient/'); exit;
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
                    
                   $array_keys = array('simulation_id','patient_name','mobile_no','pincode','age_y','age_m','age_d','patient_email','address','address_second','address_third','gender','BLOODGROUP');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M');
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
                    //echo "<pre>"; print_r($patient_all_data); exit;
                    $this->recipient->save_all_blood_recipient($patient_all_data);
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
                //echo "<pre>"; print_r($data['form_error']); exit;
                

            }
        }

        $this->load->view('blood_bank/recipient/import_recipient_excel',$data);
    }

    public function sample_recipient_import_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            
            $array_keys = array('simulation_id','patient_name','mobile_no','pincode','age_y','age_m','age_d','patient_email','address','address_second','address_third','gender','BLOODGROUP');
            
            
            $fields = array('Simulation(Mr,Mrs,..)','Recipient Name','Mobile No.','Pin Code','Age Year','Age Month','Age Day','Recipient Email','Address','Address2','Address3','Gender(Male=>1,Female=>0)','BLOOD GROUP(A+ve,B+ve,..)');
      
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
            header("Content-Disposition: attachment; filename=receipient_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }



// Please write code above this
}
?>
