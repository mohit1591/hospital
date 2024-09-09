<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_clearance extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('canteen/balance_clearance/balance_clearance_model','balance_clearance');
		$this->load->library('form_validation');
    }

    
  public function index()
  {
      unauthorise_permission(39,231);
      $data['page_title']='Balance Clearance';
      $post = $this->input->post();
      $this->session->unset_userdata('balance_search_data');
      $default_section = array('0'=>2);
      $section_id =array('section_id'=>$default_section);
      $this->session->set_userdata('balance_search_data',$section_id);
      $data['branch_list'] = $this->session->userdata('sub_branches_data');
      //print_r($data['branch_list']);die;
      $this->session->unset_userdata('type'); 
      $this->load->view('canteen/balance_clearance/list',$data);     
  }

  public function balance_list()
  {
     
    unauthorise_permission(39,231);
    $users_data = $this->session->userdata('auth_users');
    $post = $this->input->post();
    $type=1;
    if(isset($post['type']))
    {
      $type = $post['type'];
    }
    $record_list = $this->balance_clearance->patient_to_balclearlist($type);
    //echo "<pre>"; print_r($record_list);
    $row_data = ''; 
    if(!empty($record_list))
    {
       //echo "<pre>";print_r($record_list); exit;
       $i=1;
       foreach($record_list as $details)
       {  
         if(!isset($details['test_deleted']))
         {
           $details['test_deleted'] = 0;
         }

         if(!isset($details['opd_deleted']))
         {
           $details['opd_deleted'] = 0;
         }

         if(!isset($details['medicine_deleted']))
         {
           $details['medicine_deleted'] = 0;
         }

         if(!isset($details['ipd_deleted']))
         {
           $details['ipd_deleted'] = 0;
         }
          if(!isset($details['ot_deleted']))
         {
           $details['ot_deleted'] = 0;
         }
         
          if(!isset($details['blood_bank_deleted']))
         {
           $details['blood_bank_deleted'] = 0;
         }
         
         if($details['test_deleted']!=2 && $details['opd_deleted']!=2 && $details['medicine_deleted']!=2 && $details['ipd_deleted']!=2 && $details['ot_deleted']!=2 && $details['blood_bank_deleted']!=2)
         {
           $balance = "'".$details['balance']."'";
           $row_data.='<tr>
                        <td>'.$i.'</td>
                        <td>'.$details['patient_name'].'</td>
                        <td>'.$details['mobile_no'].'</td>
                        <td>'.date('d-m-Y',strtotime($details['date'])).'</td>
                        <td>'.$details['patient_code'].'</td>
                        <td>'.$details['department'].'</td>
                        
                        <td>'.$details['balance'].'</td>';
          if(in_array('232',$users_data['permission']['action'])){
               $row_data.= '<td><button type="button" class="btn-custom" name="pay_now" 
                              id="pay_now" onclick="pay_now_to_branch('.$details['id'].', '.$details['parent_id'].', '.$details['section_id'].','.$details['balance'].', '.$details['branch_id'].');">Pay Now</button>
                           </td>.';
          };
          $row_data.='</tr>';
                    $i++;  
                    /*<td>'.$details['discount'].'</td>*/     
         }          
       }
    }
    else
    {
      $row_data = '<tr><td colspan="9" class="text-center">No Record Found</td></tr>';
    } 
    echo $row_data;
  
  }

  public function pay_now($pid="", $parent_id="",$section_id="",$branch_id="")
  {
          
          unauthorise_permission(39,232);
          $this->load->model('general/general_model'); 
          $users_data = $this->session->userdata('auth_users');
          $this->load->model('opd/opd_model','opd');
          $this->load->model('ambulance/booking_model','ambulance_booking');
          $this->load->model('opd_billing/opd_billing_model','opdbilling');
          $this->load->model('sales_medicine/sales_medicine_model','sales_medicine');
           $this->load->model('sales_vaccination/sales_vaccination_model','sales_vaccination');
          $this->load->model('ipd_booking/ipd_booking_model','ipdbooking');
          $this->load->model('ot_booking/ot_booking_model','otbooking');
          
          $this->load->model('blood_bank/recipient/Recipient_model','recipient');
          $data['page_title'] = 'Balance Clearance';  
          $post = $this->input->post(); 

          $balance = '';
          if(isset($post['bal']) && !empty($post['bal']))
          {
            $balance = $post['bal'];
            $this->session->set_userdata('balance',$post['bal']);
          }
          $data['payment_mode']=$this->general_model->payment_mode();
          $data['form_error'] = [];
          $data['form_data'] = array(
                    'pid'=>$pid,
                    'parent_id'=>$parent_id,
                    'section_id'=>$section_id,
                    'branch_id'=>$branch_id,
                    'field_name'=>'',
                    'paid_date'=>date('d-m-Y'),
                   // 'paid_time'=>date('H:i:s'),
                    'payment_mode'=>1,
                    //'bank_name'=>'',
                    //'transection_no'=>'',
                    //'cheque_no'=>'',
                    //'cheque_date'=>'',
                   // 'card_no'=>'',
                    'balance'=>$balance
                 ); 
       
          if(isset($post) && !empty($post) && !isset($post['bal']))
          {
              //print_r($post); exit;
              $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                //echo 'dasdsa';die;
                $pay_id = $this->balance_clearance->payment_to_branch();
                if(!empty($pay_id))
                {
                  
                    if($post['section_id']=='1') //Pathology
                    {
                        
                        $payment_data = $this->balance_clearance->patient_balance_receipt_data($pay_id,1);
                        $patient_name = $payment_data['booking_list'][0]->patient_name;
                        $patient_email = $payment_data['booking_list'][0]->patient_email;
                        $amount = $payment_data['booking_list'][0]->debit;
                        $balance = $payment_data['booking_list'][0]->balance;
                        $booking_code = $payment_data['booking_list'][0]->lab_reg_no;
                        $mobile_no = $payment_data['booking_list'][0]->mobile_no;
                    }
                    elseif($post['section_id']=='2') //OPD
                    {
                        $get_by_id_data = $this->opd->get_by_id($post['parent_id']);
                        $patient_name = $get_by_id_data['patient_name'];
                        $patient_email = $get_by_id_data['patient_email'];
                        $balance = $post['balance'];
                        $booking_code = $get_by_id_data['booking_code'];
                        $mobile_no = $get_by_id_data['mobile_no'];
                    }
                    elseif($post['section_id']=='3') //Medicine
                    {
                        $get_by_id_data = $this->sales_medicine->get_by_id($post['parent_id']);
                        $get_patient_by_id = $this->sales_medicine->get_patient_by_id($get_by_id_data['patient_id']);
                        $patient_name = $get_patient_by_id['patient_name']; //vendor_name
                        $mobile_no = $get_patient_by_id['mobile_no'];
                        $patient_email = $get_patient_by_id['patient_email'];
                        $booking_code = $get_by_id_data['sale_no'];
                        $balance = $post['balance'];
                    }
                    elseif ($post['section_id']=='4') //Billing
                    {
                        $get_by_id_data = $this->opdbilling->get_by_id($post['parent_id']);
                        $patient_name = $get_by_id_data['patient_name'];
                        $balance = $post['balance'];
                        $mobile_no = $get_by_id_data['mobile_no'];
                        $patient_email = $get_by_id_data['patient_email'];
                        $booking_code = $get_by_id_data['reciept_code'];
                    }

                    elseif ($post['section_id']=='5') //IPD
                    {
                        $get_by_id_data = $this->ipdbooking->get_all_detail_print($post['parent_id']);
                        $patient_name = $get_by_id_data['ipd_list'][0]->patient_name;
                        $balance = $post['balance'];
                        $mobile_no = $get_by_id_data['ipd_list'][0]->mobile_no;
                        $patient_email = $get_by_id_data['ipd_list'][0]->patient_email;
                        $booking_code = $get_by_id_data['ipd_list'][0]->ipd_no;
                    }
                      elseif($post['section_id']=='7') //Medicine
                    {
                        $get_by_id_data = $this->sales_vaccination->get_by_id($post['parent_id']);
                        $get_patient_by_id = $this->sales_vaccination->get_patient_by_id($get_by_id_data['patient_id']);
                        $patient_name = $get_patient_by_id['patient_name']; //vendor_name
                        $mobile_no = $get_patient_by_id['mobile_no'];
                        $patient_email = $get_patient_by_id['patient_email'];
                        $booking_code = $get_by_id_data['sale_no'];
                        $balance = $post['balance'];
                    }
                    
                     elseif($post['section_id']=='8') //OT
                    {
                        $get_by_id_data = $this->otbooking->get_by_id($post['parent_id']);
                        $get_patient_by_id = $this->otbooking->get_patient_by_id($get_by_id_data['patient_id']);
                        $patient_name = $get_patient_by_id['patient_name']; //vendor_name
                        $mobile_no = $get_patient_by_id['mobile_no'];
                        $patient_email = $get_patient_by_id['patient_email'];
                        $booking_code = $get_by_id_data['booking_code'];
                        $balance = $post['balance'];
                    }
                    elseif($post['section_id']=='10') //Blood bank
                    {
                        $get_by_id_data = $this->recipient->get_by_id($post['parent_id']);
                        $get_patient_by_id = $this->recipient->get_patient_by_id($get_by_id_data['patient_id']);
                        $patient_name = $get_patient_by_id['patient_name']; //vendor_name
                        $mobile_no = $get_patient_by_id['mobile_no'];
                        $patient_email = $get_patient_by_id['patient_email'];
                        $booking_code = $get_by_id_data['booking_code'];
                        $balance = $post['balance'];
                    }
                     elseif($post['section_id']=='13') //OPD
                    {
                        $get_by_id_data = $this->ambulance_booking->get_by_id($post['parent_id']);
                        $patient_name = $get_by_id_data['patient_name'];
                        $patient_email = $get_by_id_data['patient_email'];
                        $balance = $post['balance'];
                        $booking_code = $get_by_id_data['booking_no'];
                        $mobile_no = $get_by_id_data['mobile_no'];
                    }

                  //check sms permission  
                  if(in_array('640',$users_data['permission']['action']))
                  {
                    
                    if(!empty($mobile_no))
                    {
                      send_sms('canteen/balance_clearance',10,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{Amt}'=>$post['balance'],'{Billno}'=>$booking_code)); 
                    }
                  }
                  //check email permission
                  if(in_array('641',$users_data['permission']['action']))
                  {
                  if(!empty($patient_email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($patient_email,'','','','','1','balance_clearance','10',array('{Name}'=>$patient_name,'{Amt}'=>$post['balance'],'{Billno}'=>$booking_code));
                     
                  }
                  }


                }
                echo $pay_id;
                return false;                
              }
              else
              {
                  $data['form_error'] = validation_errors();
                  //print_r($data['form_error']);  exit; 
              }  
          } 
          $this->load->view('canteen/balance_clearance/pay_patient_to_branch',$data);

    }

       private function _validate()
    {
        $post = $this->input->post(); 
        $total_values='';    
        if(isset($post['field_name']))
        {
          $count_field_names= count($post['field_name']);  

          $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
         $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        }
        }  
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('balance', 'balance', 'trim|required|is_natural_no_zero'); 
         $this->form_validation->set_rules('paid_date', 'paid date', 'trim|required'); 
        $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required'); 
         if(isset($post['field_name']))
        {
           $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
       
        /*if($post['payment_mode']==2)
        {
           $this->form_validation->set_rules('card_no', 'card no.', 'trim|required|numeric');
        }
        if($post['payment_mode']==4)
        {
          $this->form_validation->set_rules('transection_no', 'transection no.', 'trim|required'); 
        }
        if($post['payment_mode']==3)
        {
          $this->form_validation->set_rules('bank_name', 'bank name', 'trim|required'); 
          $this->form_validation->set_rules('cheque_no', 'cheque no.', 'trim|required|numeric');
          $this->form_validation->set_rules('cheque_date', 'cheque date', 'trim|required'); 
        } 
        */
         
        
        if ($this->form_validation->run() == FALSE) 
        {  
            
            $data['form_data'] = array(
                                        'pid'=>$post['patient_id'],
                                        'section_id'=>$post['section_id'],
                                        'parent_id'=>$post['parent_id'],
                                        'branch_id'=>$post['branch_id'],
                                        'balance'=>$post['balance'],
                                        'paid_date'=>$post['paid_date'],
                                        'field_name'=> $total_values,
                                       // 'paid_time'=>$post['paid_time'],
                                        'payment_mode'=>$post['payment_mode']
                                        //'bank_name'=>$post['bank_name'],
                                        //'transection_no'=>$post['transection_no'],
                                        //'cheque_no'=>$post['cheque_no'],
                                       // 'cheque_date'=>$post['cheque_date'],
                                       // 'card_no'=>$post['card_no'],
                                       ); 
            return $data['form_data'];
        }   
    }

   public function get_drop_down_value()
   {
      //$this->session->unset_userdata('type',$type);
      $type= $this->input->post('type'); 
      $data['type']= $type;
      //$this->session->set_userdata('type',$type);
      echo $this->load->view('canteen/balance_clearance/drop_down_data',$data,true);
   }
    public function get_allsub_branch_list()
    {
      //print_r($this->session->userdata());die;
      $sub_branch_details = $this->session->userdata('sub_branches_data');

      $parent_branch_details = $this->session->userdata('parent_branches_data');
       $users_data = $this->session->userdata('auth_users');
      // if($users_data['users_role']==2){
              $dropdown = '';
             if(!empty($sub_branch_details)){
                 $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name ="sub_branch_id" onchange="" ><option value="">Select Sub Branch</option>';
               
                   $i=0;
                   foreach($sub_branch_details as $key=>$value){
                       $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                       $i = $i+1;
                  }
                  $dropdown.='</select>';
             }
             
             echo $dropdown; 
      // }
       
     
    }
    public function print_patient_balance_receipt($id="",$patient_id="",$payment_date='',$section_id="")
    {

        $user_detail= $this->session->userdata('auth_users');
        $data['page_title'] = "Print Balance Clearance";
        $test_booking_id= $id;
        
        $get_by_id_data = $this->balance_clearance->patient_balance_receipt_data($id,$section_id);
       /* if($user_detail['parent_id']=='15')
        {
            echo "<pre>";print_r($get_by_id_data);die;
        }*/
        //echo "<pre>";print_r($get_by_id_data);die;
        $get_balance_previous= $this->balance_clearance->get_balance_previous($id,$patient_id,$payment_date,$section_id);
       if($section_id==1)
        { 
              $template_format = $this->balance_clearance->path_template_format(array('section_id'=>3,'types'=>2));//,'sub_section'=>2
              //print_r($template_format); exit;

              $data['type']=$section_id;
              $data['template_data']=$template_format;
              $data['all_detail']= $get_by_id_data;
              $data['user_detail']=$user_detail;
              $data['new_balance']=$get_balance_previous;
              // $data['balance']=$this->session->userdata('balance');
              $this->load->view('canteen/balance_clearance/print_test_balance_clearance_report',$data);

        } 
       else if($section_id==3)
        {
              $template_format = $this->balance_clearance->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>2));//,'sub_section'=>2
              //print_r($template_format); exit;

              $data['type']=$section_id;
              $data['template_data']=$template_format;
              $data['all_detail']= $get_by_id_data;
              $data['user_detail']=$user_detail;
              $data['new_balance']=$get_balance_previous;
              // $data['balance']=$this->session->userdata('balance');
              $this->load->view('canteen/balance_clearance/print_medicine_balance_clearance_report',$data);

        }
        elseif($section_id==2)
        {
            $template_format = $this->balance_clearance->template_format(array('section_id'=>1,'types'=>1));  
            //$template_format = $this->balance_clearance->template_format(array('section_id'=>1,'types'=>1,'sub_section'=>0));//,'sub_section'=>2
            //print_r($template_format); exit;

            $data['type']=$section_id;
            $data['template_data']=$template_format;
            $data['all_detail']= $get_by_id_data;
            //$data['balance']=$this->session->userdata('balance');
            $data['user_detail']=$user_detail;
            $data['new_balance']=$get_balance_previous;
            //echo "<pre>"; print_r($data);die;
            $this->load->view('canteen/balance_clearance/print_opd_balance_clearance_report',$data);

      }
      elseif($section_id==4)
      {
            $template_format = $this->balance_clearance->template_format(array('section_id'=>1,'types'=>1));  
            //$template_format = $this->balance_clearance->template_format(array('section_id'=>1,'types'=>1,'sub_section'=>0));//,'sub_section'=>2
            //print_r($template_format); exit;

            $data['type']=$section_id;
            $data['template_data']=$template_format;
            $data['all_detail']= $get_by_id_data;
            //$data['balance']=$this->session->userdata('balance');
            $data['user_detail']=$user_detail;
            $data['new_balance']=$get_balance_previous;
            $this->load->view('canteen/balance_clearance/print_billing_balance_clearance_report',$data);

      }

      elseif($section_id==5)
      {
          
              $template_format = $this->balance_clearance->template_format(array('section_id'=>5,'types'=>1));  
              $data['type']=$section_id;
              $data['template_data']=$template_format;
              $data['all_detail']= $get_by_id_data;

              //print_r($get_by_id_data); exit;
              //$data['balance']=$this->session->userdata('balance');
              $data['user_detail']=$user_detail;
              $data['new_balance']=$get_balance_previous;
              $this->load->view('canteen/balance_clearance/print_ipd_balance_clearance_report',$data);

      }
      else if($section_id==7)
        {
              $template_format = $this->balance_clearance->template_format(array('section_id'=>7,'types'=>1,'sub_section'=>2));//,'sub_section'=>2
              //print_r($template_format); exit;

              $data['type']=$section_id;
              $data['template_data']=$template_format;
              $data['all_detail']= $get_by_id_data;
              $data['user_detail']=$user_detail;
              $data['new_balance']=$get_balance_previous;
              // $data['balance']=$this->session->userdata('balance');
              //print_r($data);die;
              $this->load->view('canteen/balance_clearance/print_vaccine_balance_clearance_report',$data);

        }
        
        else if($section_id==8)  //OT
        {
            
              $template_format = $this->balance_clearance->template_format(array('section_id'=>6,'types'=>1));//,'sub_section'=>2
              //print_r($template_format); exit;
              $data['type']=$section_id;
              $data['template_data']=$template_format;
              $data['all_detail']= $get_by_id_data;
              $data['user_detail']=$user_detail;
              $data['new_balance']=$get_balance_previous;
              $this->load->view('canteen/balance_clearance/print_ot_balance_clearance_report',$data);

        }
        /* blood bank receipt */
        else if($section_id==10)
        {

        $template_format = $this->balance_clearance->template_format(array('section_id'=>10,'types'=>1));//,'sub_section'=>2
        //echo $this->db->last_query();
        //print_r($template_format); exit;
        $data['type']=$section_id;
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        $data['user_detail']=$user_detail;
        $data['new_balance']=$get_balance_previous;
       // print '<pre>';print_r($data['all_detail']);
        $this->load->view('canteen/balance_clearance/print_blood_bank_balance_clearance_report',$data);

        }
        /* blood bank receipt */
        elseif($section_id==13)
        {
            $template_format = $this->balance_clearance->template_format(array('section_id'=>12,'types'=>1));  
            //$template_format = $this->balance_clearance->template_format(array('section_id'=>1,'types'=>1,'sub_section'=>0));//,'sub_section'=>2
            //print_r($template_format); exit;

            $data['type']=$section_id;
            $data['template_data']=$template_format;
            $data['all_detail']= $get_by_id_data;
            //$data['balance']=$this->session->userdata('balance');
            $data['user_detail']=$user_detail;
            $data['new_balance']=$get_balance_previous;
            //echo "<pre>"; print_r($data);die;
            $this->load->view('canteen/balance_clearance/print_ambulance_balance_clearance_report',$data);

      }



    }

    public function ajax_list()
    {  
        unauthorise_permission(56,367);
        $list = $this->medicine_entry->get_datatables();  
        //print_r( $list);
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $medicine_entry) { 
            $no++;
            $row = array();
            if($medicine_entry->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($medicine_entry->state))
            {
                $state = " ( ".ucfirst(strtolower($medicine_entry->state))." )";
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
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="medicine_entry[]" class="checklist" value="'.$medicine_entry->id.'">'.$check_script;
            $rack_name= rack_list($medicine_entry->rack_no);
            $row[] = $medicine_entry->medicine_name;
            $row[] = $medicine_entry->packing;
            $row[] = $medicine_entry->rack_no;
            $row[] = $medicine_entry->mrp;
            $row[] = $medicine_entry->purchase_rate;
            $row[] = $medicine_entry->discount;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($medicine_entry->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('369',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_medicine_entry('.$medicine_entry->id.');" class="btn-custom" href="javascript:void(0)" style="'.$medicine_entry->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if(in_array('374',$users_data['permission']['action'])){
                $btnview=' <a class="btn-custom" onclick="return view_medicine_entry('.$medicine_entry->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
           }
            if(in_array('370',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_medicine_entry('.$medicine_entry->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }       
            $row[] = $btnedit.$btnview.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_entry->count_all(),
                        "recordsFiltered" => $this->medicine_entry->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

     public function balance_clearance()
    {     $data['page_title']='Balance Clearance';
          $post = $this->input->post();
          
          if(isset($post) && !empty($post))
          {
             $data['patient_to_balclearlist'] = $this->billing->patient_to_balclearlist();
          }
          $this->load->view('canteen/balance_clearance/balance_clearance',$data);
    }

	public function add()
	{
         unauthorise_permission(56,368);
		$this->load->model('general/general_model'); 
		$data['page_title'] = "Add medicine";
		$data['form_error'] = [];
		$data['unit_list'] = $this->medicine_entry->unit_list();
		$data['rack_list'] = $this->medicine_entry->rack_list();
		$data['manuf_company_list'] = $this->medicine_entry->manuf_company_list(); 
		$reg_no = generate_unique_id(10);
        //echo $reg_no;die;
		$post = $this->input->post();
		$data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "medicine_code"=>$reg_no,
                                    "medicine_name"=>"",
                                    "unit_id"=>"",
                                    "unit_second_id"=>"",
                                    "conversion"=>"",
                                    "min_alrt"=>"",
                                    "packing"=>"",
                                    "rack_no"=>"",
                                    "salt"=>"",
                                    "manuf_company"=>"",
                                    "mrp"=>"",
                                    "purchase_rate"=>"",
                                    "discount"=>"",
                                    "vat"=>"",
                                    "status"=>"1", 
			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_entry->save();
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        $this->session->unset_userdata('comission_data');
		$this->load->view('medicine_entry/add',$data);
	}

	public function edit($id="")
    {
         unauthorise_permission(56,369);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->medicine_entry->get_by_id($id); 
        //print_r($result);die;
        $reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
    	$data['unit_list'] = $this->medicine_entry->unit_list();
		$data['rack_list'] = $this->medicine_entry->rack_list();
		$data['manuf_company_list'] = $this->medicine_entry->manuf_company_list();
        $data['page_title'] = "Update medicine entry";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "medicine_code"=>$reg_no,
                                    "medicine_name"=>$result['medicine_name'],
                                    "unit_id"=>$result['unit_id'],
                                    "unit_second_id"=>$result['unit_second_id'],
                                    "conversion"=>$result['conversion'],
                                    "min_alrt"=>$result['min_alrt'],
                                    "packing"=>$result['packing'],
                                    "rack_no"=>$result['rack_no'],
                                    "salt"=>$result['salt'],
                                    "manuf_company"=>$result['manuf_company'],
                                    "mrp"=>$result['mrp'],
                                    "purchase_rate"=>$result['purchase_rate'],
                                    "vat"=>$result['vat'],
                                    "discount"=>$result['discount'],
                                    "status"=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_entry->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->session->unset_userdata('comission_data'); 
       $this->load->view('medicine_entry/add',$data);       
      }
    }
     
   
    public function delete($id="")
    {
        unauthorise_permission(56,370);
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_entry->delete($id);
           $response = "Medicine entry successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(56,370);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_entry->deleteall($post['row_id']);
            $response = "Medicine entry successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        unauthorise_permission(56,374);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_name']." detail";
        $this->load->view('medicine_entry/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(56,371);
        $data['page_title'] = 'Medicine entry archive list';
        $this->load->helper('url');
        $this->load->view('medicine_entry/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(56,371);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive'); 

        $list = $this->medicine_entry_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine_entry) { 
            $no++;
            $row = array();
            if($medicine_entry->status==1)
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

            $row[] = '<input type="checkbox" name="medicine_entry[]" class="checklist" value="'.$medicine_entry->id.'">'.$check_script;
            $rack_name= rack_list($medicine_entry->rack_no);
            $row[] = $medicine_entry->medicine_name;
            $row[] = $medicine_entry->packing;
            $row[] = $medicine_entry->rack_no;
            $row[] = $medicine_entry->mrp;
            $row[] = $medicine_entry->purchase_rate;
            $row[] = $medicine_entry->discount;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($medicine_entry->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           if(in_array('373',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_medicine_entry('.$medicine_entry->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('372',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$medicine_entry->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_entry_archive->count_all(),
                        "recordsFiltered" => $this->medicine_entry_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(56,373);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_entry_archive->restore($id);
           $response = "Medicine entry successfully restore in medicine entry list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(56,373);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_entry_archive->restoreall($post['row_id']);
            $response = "Medicine entry successfully restore in medicine entry list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(56,372);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_entry_archive->trash($id);
           $response = "Medicine entry successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(56,372);
        $this->load->model('medicine_entry/medicine_entry_archive_model','medicine_entry_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_entry->trashall($post['row_id']);
            $response = "Medicine entry successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function medicine_entry_dropdown()
  {
      $medicine_entry_list = $this->medicine_entry->employee_type_list();
      $dropdown = '<option value="">Select Medicine Entry</option>'; 
      if(!empty($medicine_entry_list))
      {
        foreach($medicine_entry_list as $medicine_entry)
        {
           $dropdown .= '<option value="'.$medicine_entry->id.'">'.$medicine_entry->medicine_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  public function search_data()
    {
       $post = $this->input->post(); 
       if(!empty($post['section_id']))
       { 
         $this->session->set_userdata('balance_search_data',$post);
       }
       else
       { 
          $default_section = array('0'=>15);
          $section_id =array('section_id'=>$default_section);
          $this->session->set_userdata('balance_search_data',$section_id);
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

    $html.='<div class="row m-b-5"><div class="col-md-5"><label>'.$payment_detail->field_name.'<label class="star">*</span></div> <div class="col-md-7"> <input type="text" name="field_name[]" value="" /><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>';
    }
    echo $html;exit;

  }


}
