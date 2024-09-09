<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();  
        auth_users();  
        $this->load->model('canteen/customer/customer_model','customer');
        //$this->load->model('canteen/customer/document_file_model','document_file');
        $this->load->library('form_validation');
        //$this->load->library('form_validation');
    }

    
    public function index()
    {  
       // unauthorise_permission(19,113);
        $this->session->unset_userdata('customer_search');
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

        $data['page_title'] = 'Customer List'; 
        $data['form_data'] = array('customer_code'=>'','customer_name'=>'','adhar_no'=>'','mobile_no'=>'','address'=>'','start_date'=>$start_date, 'end_date'=>$end_date);
        $this->load->view('canteen/customer/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission(19,113);
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->customer->get_datatables();
   //print_r($list);die;       
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $customer) { 
            $no++;
            $row = array();
            if($customer->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($customer->state))
            {
                $state = " ( ".ucfirst(strtolower($customer->state))." )";
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
            $row[] = '<input type="checkbox" name="patient[]" class="checklist" value="'.$customer->id.'">'.$check_script;
            $relation_name='';
            if(!empty($customer->relation_name))
            {
                $relation_name = $customer->customer_relation." ".$customer->relation_name;  
            }
            $gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');
            $row[] = $customer->customer_code; 
            $row[] = $customer->customer_name;
            //$row[] = $relation_name;
            $row[] = $customer->mobile_no;
            $row[] = $gender[$customer->gender];
            ///////////// Age calculation //////////
                $age_y = $customer->age_y;
                $age_m = $customer->age_m;
                $age_d = $customer->age_d;
                $age_h = $customer->age_h;
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
            //$row[] = $age; 
           // if(!empty($customer->address) && $customer->address!=', , ')
           // {
           //     $row[] = rtrim($customer->address,' ,'); //substr($customer->address,0,50);
           // }
           // else
           // {
          //      $row[]="";
          //  }   
            //$row[] = $customer->adhar_no;
            //$row[] = $customer->marital_status;
            //$row[] = (strtotime($customer->anniversary) > 0 ) ? date('d-m-Y',strtotime($customer->anniversary)) : '';
            //$row[] = $customer->religion;
            //$row[] = (strtotime($customer->dob) > 0 ) ? date('d-m-Y',strtotime($customer->dob)) : '';
            //$row[] = $customer->mother;
            //$row[] = $customer->guardian_name;
            //$row[] = $customer->guardian_email;
            //$row[] = $customer->guardian_phone;
            //$row[] = $customer->relation;

            //$row[] = $customer->customer_email;
           // $row[] = $customer->monthly_income;
           // $row[] = $customer->occupation;
           // $row[] = $customer->ins_type;
        
            //$row[] = $customer->insurance_type;
            //$row[] = $customer->insurance_company;
           // $row[] = $customer->polocy_no;
           // $row[] = $customer->tpa_id;
           // $row[] = $customer->ins_amount;
           // $row[] = $customer->ins_authorization_no;
            $row[] = date('d-m-Y h:i A', strtotime($customer->created_date));

            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_delete = "";
                if($users_data['parent_id']==$customer->branch_id){
                    //if(in_array('115',$users_data['permission']['action'])) {
                       $btn_edit = ' <a class="btn-custom" href="'.base_url('canteen/customer/edit/'.$customer->id).'" style="'.$customer->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a> ';
                   // }

                   // if(in_array('117',$users_data['permission']['action'])) {
                       $btn_view = ' <a class="btn-custom" onclick="return view_customer('.$customer->id.','.$customer->branch_id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
                   // }
               
                   // if(in_array('116',$users_data['permission']['action'])) {
                       $btn_delete = '<a class="btn-custom" onClick="return delete_customer('.$customer->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                   // }
				   
				    $btn_sale = ' <div class="btn-ipd"><a class="btn-custom" href="'.base_url('canteen/sale/add/'.$customer->id).'" style="width:120px;'.$vendor->id.'" title="Sale Canteen"><i class="fa fa-plus"></i>Sale Canteen</a></div>';
                } 
            // End Action Button //
              
                $row[] = $btn_edit.$btn_view.$btn_delete.$btn_sale;  
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->customer->count_all(),
                        "recordsFiltered" => $this->customer->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 /* patient history ajax by mamta */
    public function customer_history($id='',$branch_id='')
     {
        $data['id'] = $this->input->get('id');
        $data['type'] = $this->input->get('type');
        $data['branch_id'] = $this->input->get('branch_id');
        $data['page_title'] = 'Customer History List';
        $this->load->view('canteen/customer/customer_history',$data);
          // $this->load->view('medicine_stock/medicine_allot_history',$data);
     }
     public function customer_new_history($id='',$branch_id='')
     {
        $data['id'] = $this->input->get('id');
        $data['type'] = $this->input->get('type');
        $data['page_title'] = 'Customer History List';
        $this->load->view('canteen/customer/customer_history',$data);
          // $this->load->view('medicine_stock/medicine_allot_history',$data);
     }

     public function print_consolidate_history()
     {
            //unauthorise_permission('42','245');
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
            $data['self_opd_collection_list'] = $this->customer->self_opd_consolidate_history_list($get);
            $data['self_billing_collection_list'] = $this->customer->self_billing_consolidate_history_list($get);
            $data['self_medicine_collection_list'] = $this->customer->self_medicine_consolidate_history_list($get);
            $data['self_ipd_collection_list'] = $this->customer->self_ipd_consolidate_history_list($get);
            $data['self_pathology_collection_list'] = $this->customer->self_pathology_consolidate_history_list($get);
            //$data['self_medicine_return_collection_list'] = $this->customer->self_medicine_return_collection_list($get);
            $data['get'] = $get;
            //print_r($data);
            $this->load->view('canteen/customer/list_consolidate_history_data',$data);  
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
             $data['template_data'] = $this->customer->get_template_data_by_branch_id($branch_id,$template_id);
           // print_r($data['template_data']);die;
             $data['form_data'] = $this->customer->get_by_id($id);
           //  print_r($data['form_data']);die;
            $data['doctor_data'] = $this->customer->get_doctor_data($doctor_id);
           // print_r($data['doctor_data']);die;
            $data['signature_data'] = $this->customer->get_doctor_signature_data($doctor_id,$branch_id);
        
            $this->load->view('canteen/customer/print_certificate_data',$data);
            }


    public function customer_history_ajax()
    {  

        $post = $this->input->post();
       
        $this->load->model('opd_billing/opd_billing_model','opd_billing'); 
        //unauthorise_permission(63,415);
        $users_data = $this->session->userdata('auth_users');

        $list = $this->customer->get_customer_history_datatables($post['customer_id'],$post['type'],$post['branch_id']);  
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
       // print_r($list);
        foreach ($list as $customer_history) 
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
                $row[] = $customer_history->number;
                $row[] = date('d-M-Y',strtotime($customer_history->date));
                if($post['type']==1){
                $row[] = $customer_history->doctor_name;
                }
                  
                
                if($post['type']==2){
                    $get_particulars= $this->opd_billing->get_billed_particular_list($customer_history->id);
                   //print_r($get_particulars);
                    $paticulars_new=array();
                    foreach($get_particulars as $particulars){
                        $paticulars_new[]=$particulars['particulars'];

                    }
                     $row[]= implode(',', $paticulars_new);
                }
                
                $row[] = $customer_history->amount;
            }
            elseif($post['type']==4)
            {
                $row[] = $customer_history->number;
                $row[] = date('d-M-Y',strtotime($customer_history->date));;
                $row[] = $customer_history->doctor_name;
                $row[] = $customer_history->amount;
            }
            elseif($post['type']==5)
            {
                $row[] = $customer_history->number;
                //$row[] = $customer_history->lab_reg_no;
                $row[] = date('d-M-Y',strtotime($customer_history->date));;
                $row[] = $customer_history->doctor_name;
                $row[] = $customer_history->amount;
            }
            else
            {
                $row[] = $customer_history->number;
                $row[] = date('d-M-Y',strtotime($customer_history->date));;
                $row[] = $customer_history->amount;
            }
            if($post['type']==1){
            $print_pdf_url = "'".base_url('opd/print_booking_report/'.$customer_history->id.'/'.$customer_history->branch_id)."'";
            $btnprint = ' <a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';
            }
            if($post['type']==2){
             $print_url = "'".base_url('opd_billing/print_billing_report/'.$customer_history->id.'/'.$customer_history->branch_id)."'";
             $btnprint = ' <a class="btn-custom" href="javascript:void(0)" onclick = "return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print  </a>';  
            }
            if($post['type']==3){
            $print_url = "'".base_url('sales_medicine/print_sales_report/'.$customer_history->id.'/'.$customer_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            if($post['type']==4){
            $print_url = "'".base_url('ipd_booking/print_ipd_booking_recipt/'.$customer_history->id.'/'.$customer_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            if($post['type']==5){
            $print_url = "'".base_url('test/print_test_booking_report/'.$customer_history->id.'/'.$customer_history->branch_id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            }
            $row[]=$btnprint;
            $data[] = $row;
            $i++;
        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->customer->get_customer_history_count_all($post['customer_id'],$post['type']),
                        "recordsFiltered" => $this->customer->get_customer_history_count_filtered($post['customer_id'],$post['type']),
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
        $data['data']=get_setting_value('CUSTOMER_REG_NO');
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
                                    "customer_code"=>"", 
                                    "customer_name"=>"",
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
                                    "customer_email"=>"",
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
            $this->session->set_userdata('customer_search', $merge);
        }
        $customer_search = $this->session->userdata('customer_search');
        if(isset($customer_search) && !empty($customer_search))
        {
            $data['form_data'] = $customer_search;
        }
        $this->load->view('canteen/customer/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('customer_search');
    }

    public function add()
    {
       //unauthorise_permission(19,114);
        $this->load->library('form_validation');
        $this->load->model('general/general_model'); 
        $users_data = $this->session->userdata('auth_users');
        $data['page_title'] = "Add Customer";
        $data['form_error'] = [];
        $data['country_list'] = $this->general_model->country_list();

        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['religion_list'] = $this->general_model->religion_list();
        $data['relation_list'] = $this->general_model->relation_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        // print_r($data['gardian_relation_list']);die;
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();   
        $reg_no = generate_unique_id(61);

      // print_r($$reg_no);die;
        
        
        $post = $this->input->post();
	//print_r($post);die;	
		
        $data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "customer_code"=>$reg_no, 
                                    "customer_name"=>"",
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
                                    "customer_email"=>"",
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
                $customer_id =  $this->customer->save($valid_response['photo_name']);
                /////// Send SMS /////////
                //print_r($users_data['permission']['action']);die;
                if(in_array('640',$users_data['permission']['action']))
                {
                  if(!empty($post['mobile_no']))
                  {
                    $parameter = array('{customer_name}'=>$post['customer_name'],'{username}'=>'PAT000'.$customer_id,'{password}'=>'PASS'.$customer_id);
                    send_sms('customer_registration',18,'',$post['mobile_no'],$parameter); 
                  }
                }
                /////////////////////////
                
                ////////// SEND EMAIL ///////////////////
                if(in_array('640',$users_data['permission']['action']))
                { 
                  if(!empty($post['customer_email']))
                  { 
                    $this->load->library('general_functions'); 
                    $this->general_functions->email($post['customer_email'],'','','','','1','customer_registration','18',array('{customer_name}'=>$post['customer_name'],'{username}'=>'PAT000'.$customer_id,'{password}'=>'PASS'.$customer_id)); 
                  }
                } 
                 
                ////////////////////////////////////////


                $this->session->set_flashdata('success','Customer successfully added.');
                redirect(base_url('canteen/customer'));
            }
            else
            {
               
                $data['form_error'] = validation_errors();  
            }       
        }
        $this->session->unset_userdata('customer_comission_data');
        $this->load->view('canteen/customer/add',$data);
    }

    public function edit($id="")
    {
       
     //unauthorise_permission(19,115);
    
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $this->load->library('form_validation');
        $result = $this->customer->get_by_id($id); 
        //print_r($result);die;
       // $reg_no = generate_unique_id(61);
        $this->load->model('general/general_model');  
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['religion_list'] = $this->general_model->religion_list();
        $data['relation_list'] = $this->general_model->relation_list();
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();   
        $data['page_title'] = "Update Customer";  
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
                                    "customer_code"=>$result['customer_code'],
                                    "customer_name"=>$result['customer_name'],
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
                                    "customer_email"=>$result['customer_email'],
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

                if(!empty($post['old_img']) && !empty($valid_response['photo_name']) && file_exists(DIR_UPLOAD_PATH.'customer/'.$post['old_img']) && $post['old_img']!==$valid_response['photo_name'])
                {
                   
                 
                   unlink(DIR_UPLOAD_PATH.'customer/'.$post['old_img']);
                }
             
                if($post['capture_img']!="")
                {
                    $valid_response['photo_name'] = $post['capture_img'];
                } 
                 
                $this->customer->save($valid_response['photo_name']);
                $this->session->set_flashdata('success','customer successfully updated.');
                redirect(base_url('canteen/customer'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->session->unset_userdata('customer_comission_data'); 
       $this->load->view('canteen/customer/add',$data);       
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
        $this->form_validation->set_rules('customer_name', 'Customer name', 'trim|required');
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
        $this->form_validation->set_rules('customer_email', 'Customer email', 'trim|valid_email|callback_check_email');
        $this->form_validation->set_rules('guardian_email', 'guardian email', 'trim|valid_email'); 
        $this->form_validation->set_rules('monthly_income', 'monthly', 'trim|numeric'); 
      
        if(isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name']))
        {  
             $config['upload_path']   = DIR_UPLOAD_PATH.'customer/'; 
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
            $reg_no = generate_unique_id(61); 
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
                                "customer_code"=>$reg_no, 
                                "customer_name"=>$post['customer_name'],
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
                                "customer_email"=>$post['customer_email'],
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
      // unauthorise_permission(19,116);
       if(!empty($id) && $id>0)
       {
           $result = $this->customer->delete($id);
           $response = "Customer successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        //unauthorise_permission(19,116);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->customer->deleteall($post['row_id']);
            $response = "Customer successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     //unauthorise_permission(19,117);   
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->customer->get_by_id($id);  
        //print '<pre>';print_r($data['form_data']);die;
         $data['page_title'] = $data['form_data']['customer_name']." detail";
        $this->load->view('canteen/customer/view',$data);     
      }
    }  

     public function doctor_certificate($id="",$branch_id='')
    {  
       // echo $branch_id;die;
        
        $data['form_data'] = $this->customer->get_by_id($id); 
      //  echo $branch_id;die;
    //   echo '<pre>';print_r($data['form_data']);die;
        $data['doctor_name'] = $this->customer->get_doctor_name($branch_id);
      // print_r($data['doctor_name']);die;
        $data['template_data'] = $this->customer->get_template_data(); 
      //  print_r($data['template_data']);die;
      // echo '<pre>';print_r($data['template_data']);die;
        $data['page_title'] = $data['form_data']['customer_name']." detail";
        $this->load->view('canteen/customer/view_doctor_certificate',$data);
    //$users_data = $this->session->userdata('auth_users'); 
     //$parent_id= $users_data['parent_id'];

   //  echo $parent_id;die;
      // $data['patient']=$users_data; 
    //  unauthorise_permission(19,117);   
 /*    if(isset($id) && !empty($id) && is_numeric($id))
      {   
     // echo "xyz";die;    
        $data['form_data'] = $this->customer->get_by_id($id); 
    //     echo '<pre>';print_r($data['form_data']);die;
        $data['template_data'] = $this->customer->get_template_data()->result(); 
        //echo '<pre>';print_r($data['template_data']);die;
        $data['page_title'] = $data['form_data']['customer_name']." detail";
        $this->load->view('canteen/customer/view_doctor_certificate',$data);     
      }*/
    }  

public function ipd_printtemplate_dropdown()
  {
      $value= $this->input->post('value');
      $data_array= array('id'=>$value);
      $opd_list = $this->customer->template_list($data_array);
      $data['template']= $opd_list['template'];
     // $data['short_code']= $opd_list['short_code'];
      echo json_encode($data);
  }
    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission(19,118);
        $data['page_title'] = 'customer Archive List';
        $this->load->helper('url');
        $this->load->view('canteen/customer/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission(19,118);
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('canteen/customer/customer_archive_model','customer_archive'); 

        $list = $this->customer_archive->get_datatables();   
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $customer) { 
            $no++;
            $row = array();
            if($customer->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($customer->state))
            {
                $state = " ( ".ucfirst(strtolower($customer->state))." )";
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
            $row[] = '<input type="checkbox" name="patient[]" class="checklist" value="'.$customer->id.'">'.$check_script;
            $gender = array('0'=>'Female', '1'=>'Male', '2'=>'Others');
            $row[] = $customer->customer_code; 
            $row[] = $customer->customer_name;
            $row[] = $customer->mobile_no;
            $row[] = $gender[$customer->gender];
            ///////////// Age calculation //////////
                $age_y = $customer->age_y;
                $age_m = $customer->age_m;
                $age_d = $customer->age_d;
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
            if(!empty($customer->address))
            {
                $address = substr($customer->address,0,50);
            }
            else
            {
                $address = $customer->address;
            }
            $row[] = $address;
            $row[] = date('d-m-Y h:i A', strtotime($customer->created_date));  
            
            //Action button /////
            $btn_restore = ""; 
            $btn_view = "";
            $btn_delete = "";
            $btn_history = "";

            if(in_array('120',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_patient('.$customer->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            
            if(in_array('117',$users_data['permission']['action'])) 
            {
                $btn_view = ' <a class="btn-custom" onclick="return view_patient('.$customer->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
            } 
            
            if(in_array('119',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$customer->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
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
                        "recordsTotal" => $this->customer_archive->count_all(),
                        "recordsFiltered" => $this->customer_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission(19,120);
        $this->load->model('canteen/customer/customer_archive_model','customer_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->customer_archive->restore($id);
           $response = "Customer successfully restore in Customer list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission(19,120);
        $this->load->model('canteen/customer/customer_archive_model','customer_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->customer_archive->restoreall($post['row_id']);
            $response = "customer successfully restore in customer list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission(19,119);
        $this->load->model('canteen/customer/customer_archive_model','customer_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->customer_archive->trash($id);
           $response = "Customer successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       // unauthorise_permission(19,119);
        $this->load->model('canteen/customer/customer_archive_model','customer_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->customer_archive->trashall($post['row_id']);
            $response = "Customer successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function customer_dropdown()
  {

      $customer_list = $this->customer->employee_type_list();
      $dropdown = '<option value="">Select Doctor</option>'; 
      if(!empty($customer_list))
      {
        foreach($customer_list as $customer)
        {
           $dropdown .= '<option value="'.$customer->id.'">'.$customer->doctor_name.'</option>';
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
        $customer_comission_data = $this->customer->doc_customer_comission_data($post['id']);
        $this->session->set_userdata('customer_comission_data',$customer_comission_data);
     } 
     $data['dept_list'] = $this->general_model->department_list();         
     if(isset($post['data']) && !empty($post['data']))
     { 
        $this->session->set_userdata('customer_comission_data',$post);
        echo '1'; return false;
     }
     $this->load->view('canteen/customer/add_comission',$data);
  }

  public function view_comission()
  {
     $data['page_title'] = "Pathology Share Details";
     $this->load->model('general/general_model');
     $post = $this->input->post();
     $data['dept_list'] = $this->general_model->department_list();
     if(isset($post['id']) && !empty($post['id']))
     {
        $data['comission'] = $this->customer->doc_customer_comission_data($post['id']); 
     }  
     $this->load->view('canteen/customer/view_comission',$data);
  }



    public function customer_excel()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $data= get_setting_value('CUSTOMER_REG_NO');
        $fields = array($data,'Customer Name','Mobile No.','Gender','Age','Address','Created Date');
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
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
        }
        $list = $this->customer->search_customer_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $customers)
            {
                $genders = array('0'=>'Female', '1'=>'Male', '2'=>'Other');
                $gender = $genders[$customers->gender];
                $age_y = $customers->age_y;
                $age_m = $customers->age_m;
                $age_d = $customers->age_d;
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
                $customer_age =  $age;
                $created_date = date('d-m-Y h:i A', strtotime($customers->created_date));
                array_push($rowData,$customers->customer_code,$customers->customer_name,$customers->mobile_no,$gender,$customer_age,$customers->address,$created_date);
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
            foreach($data as $customers_data)
            {
                $col = 0;
                $row_val=1;
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $customers_data[$field]);
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
        header("Content-Disposition: attachment; filename=customer_list_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
    }

    public function customer_csv()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $data= get_setting_value('CUSTOMER_REG_NO');
                  
        $fields = array($data,'Customer Name','Mobile No.','Gender','Age','Address','Created Date');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->customer->search_customer_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $customers)
            {
                $genders = array('0'=>'Female', '1'=>'Male', '2'=>'Other');
                $gender = $genders[$customers->gender];
                $age_y = $customers->age_y;
                $age_m = $customers->age_m;
                $age_d = $customers->age_d;
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
                $customer_age =  $age;
                $created_date = date('d-m-Y h:i A', strtotime($customers->created_date));
                array_push($rowData,$customers->customer_code,$customers->customer_name,$customers->mobile_no,$gender,$customer_age,$customers->address,$created_date);
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
            foreach($data as $customers_data)
            {
                $col = 0;
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $customers_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=customer_list_".time().".csv");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }

      
    }

    public function customer_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->customer->search_customer_data();
        $this->load->view('canteen/customer/customer_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("customer_list_".time().".pdf");
    }
    public function customer_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->customer->search_customer_data();
      $this->load->view('canteen/customer/customer_html',$data); 
    }

    public function start_cam()
    {     
        $data['page_title'] = 'Web Camera';
        $this->load->view('canteen/customer/webcam',$data);  
    }

    public function webcam_save()
    {
        $filename =  time() . '.jpg';
        $filepath = DIR_UPLOAD_PATH.'customer/'; 
        move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath.$filename); 
        echo $filename;
    }
    
    public function import_customer_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('form_validation');
        $this->load->library('excel');
        $data['page_title'] = 'Import Customer excel';
        $arr_data = array();
        $header = array();
        $path='';

        //print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['customer_list']) || $_FILES['customer_list']['error']>0)
            {
               
               $this->form_validation->set_rules('customer_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('customer_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 

                    echo "<pre> dfd";print_r(DIR_UPLOAD_PATH.'customer/'); exit;
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
                    $total_customer = count($arrs_data);
                    
                   $array_keys = array('simulation_id','customer_name','relation_type','relation_simulation_id','relation_name','mobile_no','gender','age_y','age_m','age_d','age_h','dob','address','address2','address3','adhar_no','country','state','city','pincode','marital_status','anniversary','religion_id','mother','guardian_name','guardian_email','guardian_phone','relation_id','customer_email','monthly_income','occupation','insurance_type','insurance_type_id','ins_company_id','polocy_no','tpa_id','ins_amount','ins_authorization_no');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM');
                    $customer_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $customer_all_data= array();
                    for($i=0;$i<$total_customer;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $customer_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $customer_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                    
                    $this->customer->save_all_customer($customer_all_data);
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

        $this->load->view('canteen/customer/import_customer_excel',$data);
    } 


    public function sample_import_customer_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Simulation','Customer Name(*)','Relation Type','Relation Simulation','Relation Name','Mobile No.','Gender (Male=1,Female=0,Others=2)','Age Year(yyyy)','Age Month(mm)','Age Day(dd)','Age Hours','Dob(dd-mm-yyyy)','Address1','Address2','Address3','Aadhaar No.','Country','State','City','Pin Code','Marital Status(Married=1,Unmarried=0)','Marriage Anniversary(dd-mm-yyyy)','Religion','Mother Name','Guardian Name','Guardian Email','Guardian Mobile','Relation','Customer Email','Monthly Income','Occupation','Insurance Type(Normal=0,TPA=1)','Insurance Type','Insurance Company','Policy No.','TPA ID','Insurance Amount','Authorization No.');

            
      
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
            header("Content-Disposition: attachment; filename=customer_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }
    
    
     public function view_document($customer_id)
    { 
        $data['page_title'] = "Customer Document";
        $data['customer_id'] = $customer_id;
        $this->load->view('canteen/customer/customer_document',$data);
    }

    public function ajax_document_list($customer_id='')
    { 
        
        $users_data = $this->session->userdata('auth_users');
        $list = $this->document_file->get_datatables($customer_id);  
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
            if(!empty($prescription->document_files) && file_exists(DIR_UPLOAD_PATH.'customer/document/'.$prescription->document_files))
            {
                $sign_img = ROOT_UPLOADS_PATH.'customer/document/'.$prescription->document_files;
                $sign_img = '<a href="'.$sign_img.'" download><img src="'.$sign_img.'" width="100px" /></a>';
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
                        "recordsTotal" => $this->document_file->count_all($customer_id),
                        "recordsFiltered" => $this->document_file->count_filtered($customer_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


public function upload_document($customer_id='')
{ 
    $data['page_title'] = 'Upload Document';   
    $data['form_error'] = [];
    $data['document_files_error'] = [];
    $post = $this->input->post();

    $data['form_data'] = array(
                                 'data_id'=>'',
                                 'customer_id'=>$customer_id,
                                 'old_document_files'=>'',
                                 'document_name'=>'',
                              );

    if(isset($post) && !empty($post))
    {   

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        $this->form_validation->set_rules('customer_id', 'patient', 'trim|required'); 
        $this->form_validation->set_rules('document_name', 'Document Name', 'trim|required'); 

        if(!isset($_FILES['document_files']) || empty($_FILES['document_files']['name']))
        {
            $this->form_validation->set_rules('document_files', 'Document file', 'trim|required');  
        }
        
        //echo DIR_UPLOAD_PATH.'customer/document/'; exit;
        if($this->form_validation->run() == TRUE)
        {
             $config['upload_path']   = DIR_UPLOAD_PATH.'customer/document/';  
             $config['allowed_types'] = 'pdf|jpeg|jpg|png'; 
             $config['max_size']      = 1000; 
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
                                        'customer_id'=>$post['customer_id'],
                                       'old_document_files'=>$post['old_document_files'],
                                       'document_name'=>$post['document_name']
                                       );
              }   
        }
        else
        {
            
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'customer_id'=>$post['customer_id'], 
                                        'old_document_files'=>$post['old_document_files'],
                                        'document_name'=>$post['document_name']
                                       );
            $data['form_error'] = validation_errors();
              

        }   

    }

    $this->load->view('canteen/customer/add_document',$data);
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


}
