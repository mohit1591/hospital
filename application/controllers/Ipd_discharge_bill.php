<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_discharge_bill extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('ipd_discharge_bill/ipd_discharge_bill_model','ipd_discharge_bill');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
        unauthorise_permission(130,782);
        $this->session->unset_userdata('discharge_bill_serach');
        //$this->session->unset_userdata('ipd_particular_billing');
        //$this->session->unset_userdata('ipd_particular_payment');
        $data['page_title'] = 'IPD Discharge Bill'; 
        $data['form_data'] = array('patient_name'=>'','mobile_no'=>'');
        $this->load->view('ipd_discharge_bill/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(130,782);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->ipd_discharge_bill->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $patient) { 
            $no++;
            $row = array();
         
            ///// State name ////////
            $state = "";
            if(!empty($patient->state))
            {
                $state = " ( ".ucfirst(strtolower($patient->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            }                   
            $row[] = '<input type="checkbox" name="patient[]" class="checklist" value="'.$patient->id.'">'.$check_script;
            $row[] = $patient->patient_code; 
            $row[] = date('Y').'/'.$patient->ipd_no;
            $row[] = $patient->patient_name;
            $row[] = date('d-m-Y',strtotime($patient->admission_date)); 
            $row[] = date('H:i:s',strtotime($patient->admission_time)); 
            $row[] = $patient->doctor_name; 
            $row[] = $patient->room_no; 
            $row[] = $patient->bad_no; 
            $row[] = $patient->specialization; 
            
            //Action button /////
            $ot_booking = "";
            $ipd_charge_entry='';
           // $patient_id=  ;
          $running_bill = ' <a class="btn-custom" href="'.base_url('ipd_running_bill/running_bill_info?ipd_id='.$patient->ipd_book_id.'&patient_id='.$patient->ipd_patient_id.'').'" title="Running Bill" data-url="512"><i class="fa fa-plus"></i> Running Bill</a>';
                         
            $row[]=$running_bill;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_running_bill->count_all(),
                        "recordsFiltered" => $this->ipd_running_bill->count_filtered(),
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
                                        "patient_name"=>"",
                                        "mobile_no"=>"",
                                        "search_criteria"=>""
                                      );
            if(isset($post) && !empty($post))
            {
            
                $marge_post = array_merge($data['form_data'],$post);
                $this->session->set_userdata('running_bill_serach', $marge_post);

            }
                $running_bill_serach = $this->session->userdata('running_bill_serach');
                if(isset($running_bill_serach) && !empty($running_bill_serach))
                  {
                  $data['form_data'] = $running_bill_serach;
                   }
           // $this->load->view('ipd_running_bill/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('ot_booking_serach');
    }  

    public function discharge_bill_info($ipd_id="",$patient_id="",$discharge_date='')
     {
          unauthorise_permission(130,785);
          $post= $this->input->post();
          $data['page_title'] = 'Discharge Bill List';
          $data['ipd_id'] = $ipd_id;
          //$bill_no = generate_unique_id(24);

          $this->load->model('ipd_booking/ipd_booking_model','ipd_booking_model');

          $ipd_details = $this->ipd_booking_model->get_by_id($data['ipd_id']);
          if(!empty($ipd_details['discharge_bill_no']))
          {
             $bill_no = $ipd_details['discharge_bill_no'];
          }
          else
          {
            $bill_no = generate_unique_id(24);
          }
          
          if(!empty($ipd_details['discharge_remarks']))
          {
             $discharge_remarks = $ipd_details['discharge_remarks'];
          }
          else
          {
            $discharge_remarks = '';
          }


          $data['patient_id'] = $patient_id;
          $list = $this->ipd_discharge_bill->get_discharge_bill_info_datatables($data['ipd_id'],$data['patient_id']);
          $this->load->model('general/general_model');
           $data['payment_mode']=$this->general_model->payment_mode();
           
           
            $get_payment_detail= $this->general_model->payment_ipd_mode_detail_according_to_field($ipd_details['discharge_payment_mode'],$ipd_id,9,9);
          $total_values=array();
          for($i=0;$i<count($get_payment_detail);$i++) {
          $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

          }
          
          
          $payamount=$this->ipd_discharge_bill->get_paid_amount($data['ipd_id'],$data['patient_id']);
          if($payamount[0]->advance_payment_dis_bill>$payamount[0]->total_amount_dis_bill){
            //$data['get_paid_amount']=$payamount[0]->refund_amount_dis_bill;
            $data['get_paid_amount']='0.00';
          }else{
            $data['get_paid_amount']=$payamount[0]->paid_amount_dis_bill;
          }
          if($payamount[0]->discount_amount_dis_bill>0)
          {
              $data['total_discount'] = $payamount[0]->discount_amount_dis_bill;
          }
          else
          {
              $data['total_discount'] = '0.00';
          }
          $data['running_bill_data']=$list;

          if(!empty($payamount[0]->discharge_date) &&  $payamount[0]->discharge_date!='0000-00-00 00:00:00' && date('Y-m-d',strtotime($payamount[0]->discharge_date))!='1970-01-01')
          {
              $dischargedate = date('d-m-Y h:i A',strtotime($payamount[0]->discharge_date));
          }   
          else
          {
              //$dischargedate = date('d-m-Y h:i A'); 
              $dischargedate = date('d-m-Y h:i A',$discharge_date); 
          }
          
          if(!empty($ipd_details['refund_payment_mode']))
          {
            $payment_re = $ipd_details['refund_payment_mode'];
          }
          else
          {
            $payment_re ='';
          }
          
          //$data['ipd_bill_info']=$this->ipd_discharge_bill->ipd_bill_info();
          $data['form_data']= array('discharge_date'=>$dischargedate,//date('d-m-Y H:i:s'),
                                    'particular_date'=>date('d-m-Y'),
                                    'next_app_date'=>'',
                                    'discharge_bill_no'=>$bill_no,
                                    "payment_mode"=>$ipd_details['discharge_payment_mode'],
                                    "amount"=>'',
                                    'field_name'=>$total_values,
                                    //'bank_name'=>'',
                                    //'card_no'=>'',
                                    'cheque_date'=>date('d-m-Y'),
                                    "refund_payment_mode"=>$payment_re,
                                    'discharge_remarks'=>$discharge_remarks,
                                    //'transaction_no'=>'',
                                    );

            if(isset($post) && !empty($post))
            { 
                $data['form_data'] = $this->_validate();
                if($this->form_validation->run() == TRUE)
                {

                    $discharge_bill_id= $this->ipd_discharge_bill->save();
                   
                  
              if(!empty($discharge_bill_id))
              {
                  $get_ipd_patient_details= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
                  $get_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info_datatables($ipd_id,$patient_id);
                  $get_updated_data= $this->ipd_discharge_bill->ipd_ipd_detail_data($ipd_id,$patient_id);
                 
                  $patient_name = $get_ipd_patient_details['patient_name'];
                  $ipd_no = $get_updated_data[0]->ipd_no;
                  $paid_amount = $get_by_id_data['advance_deposite'];
                  $mobile_no = $get_ipd_patient_details['mobile_no'];
                  
                  $patient_email = $get_ipd_patient_details['patient_email'];
                  $discharge_bill_no = $get_ipd_patient_details['discharge_bill_no'];
                  $amount = $get_updated_data[0]->total_amount_dis_bill;
                //check permission 
                  //exit;
                if(in_array('640',$users_data['permission']['action']))
                {
                    if(!empty($mobile_no))
                    {
                      send_sms('discharge_bill',17,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{BillNo}'=>$discharge_bill_no,'{IPDNo}'=>$ipd_no,'{Amt}'=>$amount));  
                    }
                    
                }

                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($patient_email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($patient_email,'','','','','1','discharge_bill','17',array('{Name}'=>$patient_name,'{BillNo}'=>$discharge_bill_no,'{IPDNo}'=>$ipd_no,'{Amt}'=>$amount));
                     
                  }
                } 
              }

                    $this->session->set_userdata('discharge_bill_id',$discharge_bill_id);
                    $this->session->set_flashdata('success','Discharge Billing has been successfully updated.');
                    //redirect(base_url('ipd_discharge_bill/discharge_bill_info/'.$ipd_id.'/'.$patient_id.'/?status=print')); // /?status=print
                    redirect(base_url('ipd_discharge_bill/discharge_bill_info/'.$ipd_id.'/'.$patient_id.'/?status=print&billstatus=printbill')); // /?status=print

                }
                else
                {

                    $data['form_error'] = validation_errors();

                }  
            }
         $data['doctor_list'] = $this->general_model->attended_doctor_list();
         $users_data = $this->session->userdata('auth_users');
         /*if($users_data['parent_id']=='110')
         {*/
            $data['pathology_balance'] = $this->ipd_discharge_bill->balance_remaining($patient_id,'1');
            $data['medicine_balance'] = $this->ipd_discharge_bill->balance_remaining($patient_id,'3'); 
         //}
         $data['ipd_details']=$ipd_details;
          $this->load->view('ipd_discharge_bill/discharge_bill_info',$data);
          
     }

     private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
       
        
          $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required');
        if(isset($post['field_name']))
        {
           $this->form_validation->set_rules('field_name[]', 'field', 'trim|required');

        }
       
          
          $total_values=array(); 

          if(isset($post['field_name']))
          {
            $count_field_names= count($post['field_name']);  

            $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

            for($i=0;$i<$count_field_names;$i++) 
            {
            $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

            }
          }   
        if ($this->form_validation->run() == FALSE) 
        {  

              $data['form_data'] = array(
                                        'discharge_date'=>$_POST['discharge_date'],
                                        'next_app_date'=>$_POST['next_app_date'],
                                        'particular_date'=>$_POST['date'],
                                        'discharge_bill_no'=>$_POST['discharge_bill_no'],
                                        "payment_mode"=>$post['payment_mode'],
                                        'field_name'=>$total_values,
                                        'refund_payment_mode'=>$_POST['refund_payment_mode'],
                                       ); 
            return $data['form_data'];
        }   
    }

    /* public function running_bill_info()
    {  
        $post = $this->input->post();
        
        unauthorise_permission(63,415);
        $users_data = $this->session->userdata('auth_users');

       
      //print '<pre>';print_r( $list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $ipd_running_bill) 
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
            
             $row[] = $i;
                if($ipd_running_bill->type==2)
                {
                    $row[] = $ipd_running_bill->particular;
                    $row[] = date('d-m-Y',strtotime($ipd_running_bill->start_date));
                    $row[]='';
                    $row[]='';
                }
                else
                {
                    $row[] = $ipd_running_bill->particular; 
                    $row[] = date('d-m-Y',strtotime($ipd_running_bill->start_date));
                    $row[] = $ipd_running_bill->price;
                    $row[] = $ipd_running_bill->quantity;
                }
                
                
                
                if(!empty($ipd_running_bill->total_advance_price) && $ipd_running_bill->total_advance_price>0)
                {
                   $row[] = $ipd_running_bill->total_advance_price;  
                }
                else
                {
                    $row[] = $ipd_running_bill->net_price;
                }
                
                $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_running_bill->get_running_bill_info_count_all($post['ipd_id'],$post['patient_id']),
                        "recordsFiltered" => $this->ipd_running_bill->get_running_bill_info_count_filtered($post['ipd_id'],$post['patient_id']),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }*/

    function end_now($data_id="",$start_date="")
    {
         
        $this->load->model('general/general_model'); 
        $data['page_title'] = "End Date";
        $data['form_error'] = [];
        $reg_no = generate_unique_id(11);
        $post = $this->input->post();
        $data['form_data'] = array(
                                    "data_id"=>$data_id,
                                    'start_date'=>$start_date,
                                    "end_date"=>$start_date
                                    
                                  );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
               
                $this->ipd_discharge_bill->save_discharge_data();
               //$this->session->set_flashdata('success','Sales medicine has been successfully added.');
                 echo 1;
                return false; 
            }
            else
            {

                $data['form_error'] = validation_errors();  
               //print_r($data['form_error']);die;
            }       
        }
       $this->load->view('ipd_discharge_bill/end_now',$data);
    }

 
    function get_particular_data($vals="",$patient_type="",$panel_name="")
    {
        if(!empty($vals))
        {
            $result = $this->ipd_discharge_bill->get_particular_data($vals, $patient_type, $panel_name);  
        if(!empty($result))
        {
            echo json_encode($result,true);
        } 
        } 
    }

    function add_perticulars($ipd_id="",$patient_id="",$discharge_date='')
    {
     $post= $this->input->post();
     if(!empty($post['particular']))
     {
        $this->ipd_discharge_bill->save_particulars($ipd_id,$patient_id);
        $this->session->set_flashdata('success','Particular has been successfully added.');
        $data=array('success'=>1,'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'discharge_date'=>$discharge_date);
        echo json_encode($data);
        return false;
         //echo '1';exit;
     }
    }

     public function payment_calc_all()
    { 
       
        $post = $this->input->post();

        $total_amount = 0;
        $total_vat = 0;
        $total_discount =0;
        $net_amount =0;  
        $purchase_amount=0;
        $total_new_amount=0;
        $tot_discount_amount=0;
        $total_advance_amount=0;
        $blance_dues=0;
        $payamount=0;
        $refund_amount=0;
        $new_amount=0;
        $received_amount=0;
        $total_bill_amount=0;
        $i=0;
        if($post['update_row']==1)
        {
              $list = $this->ipd_discharge_bill->get_discharge_bill_info_datatables($post['ipd_id'],$post['patient_id']);
              //print '<pre>'; print_r($list);die;
              foreach($list['CHARGES'] as $patient_ipd_list)
              {
                  $received_amount= $received_amount+$patient_ipd_list['net_price'];
                  //$total_amount+=($patient_ipd_list['net_price']*$patient_ipd_list['quantity']);
                  $total_amount+=($patient_ipd_list['price']*$patient_ipd_list['quantity']);

              }
              
              if(!empty($list['pathology_payment']))
                    {
                            
                            foreach($list['pathology_payment'] as $payment )
                            {
                                $total_amount = $total_amount+$payment->net_price;
                                 $received_amount= $received_amount+$payment->net_price;
                            }
                    }
              
              $total_discount=0;
              $net_amount = ($total_amount-$total_discount);
              
              foreach($list['advance_payment'] as $patient_ipd_list)
              {
                  $net_advance_data[] = $patient_ipd_list->net_price;
              }

              $advance_payment=$net_advance_data[0];
              if($net_amount+$payamount>$advance_payment)
              {
               $blance_dues=($net_amount+$payamount)-$advance_payment;   
              }
              else
              {
                $blance_dues='0.00';  
              }
              
              if($net_amount+$payamount<$advance_payment)
              {
                 $refund_amount= $advance_payment-($net_amount+$payamount); 
              }
              else
              {
                 $refund_amount= '0.00'; 
              }
              


         
        }
        else
        {
        
            if($post['discount']!='' && $post['discount']!=0)
            {
               $total_discount =$post['discount'];
            }
            else
            { 
               $total_discount=0;
            }
           
    
    
            $total_amount = $post['total_amount'];
            
            
            $total_net_amount = $post['total_amount']-$total_discount;
           /* if($total_net_amount > $post['total_advance_amount'])
            {
                $net_amount = $total_net_amount-$post['total_advance_amount'];  
            }
            else
            {*/
                $net_amount = $total_net_amount;
            //}
            //$net_amount = $total_net_amount-$post['total_advance_amount'];
                  
            if($post['total_advance_amount'] >$total_amount)
            {
               
              $payamount=$post['pay_amount'];
              //$refund_amount= $post['total_refund_amount'];
              $new_amount= $net_amount+$post['total_refund_amount'];
              $advance_payment = floatval(preg_replace('/[^\d.]/', '', number_format($post['total_advance_amount'])));
              //$blance_dues=($new_amount+$payamount)-$advance_payment;
                /*if(($new_amount+$payamount)>$advance_payment)
                {
                  $blance_dues_amount=($new_amount+$payamount)-$advance_payment;
                  $refund_amount='0.00';
                  if($blance_dues_amount>0)
                  {
                      $blance_dues=$blance_dues_amount;
                  }
                  else
                  {
                     $blance_dues='0.00'; 
                  }
                  
                }
                else
                {
                  $balance_due ='0.00';
                  $refund_amount_total= $advance_payment-($new_amount+$payamount);
                  if($refund_amount_total>0)
                  {
                       $refund_amount=$refund_amount_total;
                  }
                  else
                  {
                     $refund_amount='0.00'; 
                  }
                }*/
              
              
              if(($net_amount+$payamount)>$advance_payment)
                {
                  $blance_dues_amount=($net_amount+$payamount)-$advance_payment;
                  $refund_amount='0.00';
                  if($blance_dues_amount>0)
                  {
                      $blance_dues=$blance_dues_amount;
                  }
                  else
                  {
                     $blance_dues='0.00'; 
                  }
                  
                }
                else
                {
                  $balance_due ='0.00';
                  $refund_amount_total= $advance_payment-($net_amount+$payamount);
                  if($refund_amount_total>0)
                  {
                       $refund_amount=$refund_amount_total;
                  }
                  else
                  {
                     $refund_amount='0.00'; 
                  }
                }
                
    
            }
            else
            {
    
              $refund_amount=0;
              $payamount= $post['pay_amount'];//$net_amount;
              //$blance_dues=$net_amount-$post['total_advance_amount']-$payamount;//$net_amount -$payamount;
              $blance_dues=$post['total_amount']-$post['total_advance_amount']-$payamount-$total_discount;
              
              if($blance_dues<0)
              {
                    $refund_amount = abs($blance_dues);  
                    $blance_dues = 0;
              }
              
            }
       }
        
        $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''),'total_amount_bill'=>number_format($total_amount,2,'.',''),'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'balance_due'=>number_format($blance_dues,2,'.',''),'discount'=>$post['discount'],'total_advance_amount'=>number_format($post['total_advance_amount'],2,'.',''),'refund_amount'=>number_format($refund_amount,2,'.',''),'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       
        
    } 
    public function print_discharge_bill($ipd_id="",$patient_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('general/general_model');
        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $data['get_ipd_patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id,9,9);
        //echo "<pre>";print_r($data['get_ipd_patient_details']);die;
        $user_detail= $this->session->userdata('auth_users');
        if($users_data['parent_id']=='192')
        {
           $get_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info($ipd_id,$patient_id); 
        }
        else
        {
            $get_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info_datatables($ipd_id,$patient_id);
        }
        
        
        
        $get_updated_data= $this->ipd_discharge_bill->ipd_ipd_detail_data($ipd_id,$patient_id);
        //print '<pre>'; print_r($data['get_ipd_patient_details']);die;
        //print '<pre>'; print_r($data['get_ipd_patient_details']);die;
       
        $template_format= $this->ipd_discharge_bill->template_format(array('branch_id'=>$users_data['parent_id']));
    // $template_format= $this->ipd_discharge_bill->template_format(array());
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        //print_r($data['all_detail']['CHARGES']);die;
        
        $paid_amt = $this->ipd_discharge_bill->get_all_payment($ipd_id,$patient_id);
        $data['total_paid_amount'] ='0';
        if(!empty($paid_amt))
        {
          $data['total_paid_amount'] = $paid_amt->paid_amount;
        }
        
        $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $data['address_setting_list'] = $this->address_setting->get_master_unique();
        $this->load->model('time_print_setting/time_print_setting_model','time_setting');
        $data['time_setting'] = $this->time_setting->get_master_unique();
        $this->load->view('ipd_discharge_bill/print_template_discharge_bill',$data);
    }

    public function update_discharge_date()
    {
       $post= $this->input->post();
       $result= $this->ipd_discharge_bill->update_charge_data();
       $this->session->set_flashdata('success','Particular has been successfully updated');
       echo '1';
       exit;
    }

    
    public function delete_charges($id="",$ipd_id="",$patient_id="",$discharge_date='')
    {
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_bill->delete_charges($id,$ipd_id,$patient_id);
           $this->session->set_flashdata('success','Particular has been successfully deleted');
             /*$data=array('success'=>1,'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'discharge_date'=>$discharge_date);
           echo json_encode($data);
        return false;*/
        
        $data=array('success'=>1,'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'discharge_date'=>$discharge_date);
        //print_r($data); exit;
        echo json_encode($data);
        return false;
        
           //echo '1';
           //exit;
            //redirect(base_url('ipd_discharge_bill/discharge_bill_info/'.$ipd_id.'/'.$patient_id)); // /?status=print
       }
    }


    function update_advance_payment($ipd_id="",$patient_id="")
    {
      $data['page_title']="Update Advance Payment";
      $data['ipd_id']=$ipd_id;
      $this->load->model('general/general_model'); 
      $data['patient_id']=$patient_id;
      $data['form_data'] = array(  'ipd_id'=>$ipd_id,
                                        'patient_id'=>$patient_id
                                        );
      $list = $this->ipd_discharge_bill->get_advance_payment_details($ipd_id,$patient_id); 
      //echo "<pre>";print_r($list);die;
      $data['advance_list']=$list;
      //print '<pre>'; print_r($data['advance_list']);die;
      $data['payment_mode']=$this->general_model->payment_mode();
      //print_r($data['payment_mode']);die;

      $this->load->view('ipd_discharge_bill/upadte_advance_payment',$data);
    }

    public function advance_detail($ipd_id="",$patient_id="")
    {
        $post= $this->input->post();
       // echo "<pre>";print_r($post);die;
        $this->load->model('general/general_model'); 
        $data['page_title'] = "End Date";
        $data['form_error'] = [];
        $data['ipd_id']=$ipd_id;
        $data['patient_id']=$patient_id;
        $post = $this->input->post();
        $list = $this->ipd_discharge_bill->get_advance_payment_details($ipd_id,$patient_id); 
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['advance_list']=$list;
        $data['form_data'] = array(  'ipd_id'=>$ipd_id,
                                        'bank_name'=>'',
                                        'field_name'=>'',
                                        'payment_mode'=>'',
                                        //'card_no'=>$post['transaction_no'],
                                        //'cheque_date'=>'',
                                        //'cheque_no'=>'',
                                        //'transaction_no'=>'',
                                        //"payment_date"=>'',
                                        'patient_id'=>$patient_id
                                        );
        if(isset($post) && !empty($post))
        {   
            //$data['form_data'] = $this->_validate_bank_details($ipd_id,$patient_id);
            //if($this->form_validation->run() == TRUE)
            //{
                
                $this->ipd_discharge_bill->save_advance_data();
                $this->session->set_flashdata('success','Payemnt has been successfully added.');
                 echo 1;
                return false; 
            //}
            //else
            //{

                //$data['form_error'] = validation_errors();  
             
            //}       
        }
       $this->load->view('ipd_discharge_bill/upadte_advance_payment',$data);
    }

    private function _validate_bank_details()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        if(isset($_POST['payment_mode']) && $_POST['payment_mode']==2) {
        $this->form_validation->set_rules('transaction_no', 'transaction no', 'trim|required');
        }
        if(isset($_POST['payment_mode']) && $_POST['payment_mode']==3) {
        $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
        $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');
        $this->form_validation->set_rules('cheque_date', 'cheque date', 'trim|required');

        }
        if(isset($_POST['payment_mode']) && $_POST['payment_mode']==4) {
        $this->form_validation->set_rules('transaction_no', 'Transaction no', 'trim|required');
        }
         if(isset($_POST['payment_mode']) && $_POST['payment_mode']==1) {
        $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required');
        }

          if(isset($post['bank_name'])){

          $bank_name=$_POST['bank_name'];
          }else{
          $bank_name='';
          }
          if(isset($_POST['card_no'])){

          $card_no=$_POST['card_no'];
          }else{
          $card_no='';
          }
          if(isset($_POST['cheque_no'])){

          $cheque_no=$_POST['cheque_no'];
          }else{
          $cheque_no='';
          }
          if(isset($_POST['payment_date']) && !empty($post['payment_date'])){

          $payment_date=date('d-m-Y',strtotime($_POST['payment_date']));

          }else{
          $payment_date='';
          }
          if(isset($_POST['cheque_date']) && !empty($post['cheque_date'])){

          $cheque_date=date('d-m-Y',strtotime($_POST['cheque_date']));

          }else{
          $cheque_date='';
          }
          if(isset($_POST['transaction_no'])){

          $transaction_no=$_POST['transaction_no'];
          }else{
          $transaction_no='';
          }
        if ($this->form_validation->run() == FALSE) 
        {  

              $data['form_data'] = array(
                                        "payment_mode"=>$post['payment_mode'],
                                        'ipd_id'=>$post['ipd_id'],
                                        'patient_id'=>$post['patient_id'],
                                        'bank_name'=>$bank_name,
                                        //'card_no'=>$post['transaction_no'],
                                        'cheque_date'=>$cheque_date,
                                        'cheque_no'=>$cheque_no,
                                        'transaction_no'=>$transaction_no,
                                        "payment_date"=>$payment_date
                                       ); 
            return $data['form_data'];
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

    public function get_payment_mode_data_advance()
    {
        $this->load->model('general/general_model'); 
        $payment_mode_id= $this->input->post('payment_mode_id');
        $advance_id= $this->input->post('advance_id');

        $get_payment_detail= $this->general_model->payment_mode_detail($payment_mode_id);

       
        $html='';
        $var_form_error='';
        foreach($get_payment_detail as $payment_detail)
        {
        
        $html.='<input type="text" name="advance_payment_date['.$advance_id.'][field_name][]" value="" placeholder="Enter '.$payment_detail->field_name.'" id="field_value_new'.$advance_id.$payment_detail->id.'" required/><input type="hidden" name="advance_payment_date['.$advance_id.'][field_id][]" value="'.$payment_detail->id.'" placeholder="Enter '.$payment_detail->field_name.'" /><span style="margin: 0px 0px 0px 40px;" class="text-danger" id="field_error_'.$advance_id.$payment_detail->id.'"></span>';

      }
        echo $html;exit;

    }

    public function print_consolidated_bill($ipd_id="",$patient_id="")
    {

        $this->load->model('general/general_model');
        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $data['get_ipd_patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);

        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info_datatables($ipd_id,$patient_id);
        //$template_format= $this->ipd_discharge_bill->template_format(array());
$users_data = $this->session->userdata('auth_users'); 
        $template_format= $this->ipd_discharge_bill->template_format(array('branch_id'=>$users_data['parent_id']));
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        //print_r($data['all_detail']['CHARGES']);die;
        $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $data['address_setting_list'] = $this->address_setting->get_master_unique();
        $this->load->view('ipd_discharge_bill/print_template_consolidated',$data);
    }

    public function print_summarized_bill($ipd_id="",$patient_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('general/general_model');
        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $data['get_ipd_patient_details'] = $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
        
        $user_detail= $this->session->userdata('auth_users');
        //$get_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info_datatables($ipd_id,$patient_id);
        
        $get_by_id_data=$this->ipd_discharge_bill->get_summarized_bill_info_datatables($ipd_id,$patient_id);
        
        $template_format= $this->ipd_discharge_bill->template_format(array('branch_id'=>$users_data['parent_id']));
        //$template_format= $this->ipd_discharge_bill->template_format(array());
        //print_r($template_format);
        $data['template_data']=$template_format;
        //echo "<pre>"; print_r($template_format); exit;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        
        $paid_amt = $this->ipd_discharge_bill->get_all_payment($ipd_id,$patient_id);
        $data['total_paid_amount'] ='0';
        if(!empty($paid_amt))
        {
          $data['total_paid_amount'] = $paid_amt->paid_amount;
        }
        
        //print_r($data['all_detail']['CHARGES']);die;
        $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $data['address_setting_list'] = $this->address_setting->get_master_unique();
        $this->load->view('ipd_discharge_bill/print_template_summarized',$data);
    }
    
    
     /* group wise discharge bill */

    public function print_discharge_bill_according_group($ipd_id="",$patient_id="")
    {

        $this->load->model('general/general_model');
        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $data['get_ipd_patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
        //echo "<pre>";print_r($data['get_ipd_patient_details']);die;
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info_datatables($ipd_id,$patient_id);
        $get_updated_data= $this->ipd_discharge_bill->ipd_ipd_detail_data($ipd_id,$patient_id);
        //print '<pre>'; print_r($data['get_ipd_patient_details']);die;
        //print '<pre>'; print_r($data['get_ipd_patient_details']);die;
       $users_data = $this->session->userdata('auth_users'); 
        $template_format= $this->ipd_discharge_bill->template_format(array('branch_id'=>$users_data['parent_id']));
     // $template_format= $this->ipd_discharge_bill->template_format(array());
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        //print_r($data['all_detail']['CHARGES']);die;
        $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $data['address_setting_list'] = $this->address_setting->get_master_unique();
        $this->load->view('ipd_discharge_bill/print_template_discharge_bill_group_wise',$data);
    }


    /* group wise discharge bill */
    
    //  print discharge paid amount
     public function print_discharge_bill_paidamount($ipd_id="",$patient_id="")
    {
        //get_ipd_patient_details
        $this->load->model('general/general_model');
        $data['get_ipd_patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
        //echo "<pre>"; print_r($data['get_ipd_patient_details']); exit;
        
        $data['get_ipd_patient_disc_rec']= $this->general_model->get_patient_ipd_disc_rec($ipd_id,$patient_id);
       $data['discharge_rec_no'] = $data['get_ipd_patient_disc_rec']['bill_reciept_prefix'].$data['get_ipd_patient_disc_rec']['bill_reciept_suffix'];
       
       
        $data['page_title'] = "Print Bookings";
        $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
        $ipd_booking_id = $ipd_id;
        $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
        //echo "<pre>"; print_r($get_by_id_data); exit;
        $branch_id='';
        $users_data = $this->session->userdata('auth_users'); 
        if($users_data['users_role']==3)
        {
          $doctor_id = $users_data['parent_id'];
          $this->load->model('branch/branch_model');
          $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
          $branch_id = $doctor_data[0]->branch_id; 
        } 
        $template_format = $this->ipd_booking->template_format(array('section_id'=>5,'types'=>1),$branch_id);
        $get_charge_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info_datatables($ipd_id,$patient_id);
        $get_updated_data= $this->ipd_discharge_bill->ipd_ipd_detail_data($ipd_id,$patient_id);
        //echo "<pre>"; print_r($get_charge_by_id_data); exit;
        $data['all_charge_detail']= $get_charge_by_id_data;
        $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        $data['page_type'] = 'Booking';
        $this->load->view('ipd_discharge_bill/print_ipd_final_reciept_template',$data);
       
    }
    
    
    public function print_letterhead_discharge_bill($ipd_id="",$patient_id="")
    {
       
        $this->load->library('m_pdf');
        $this->load->model('general/general_model');
        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $get_ipd_patient_details= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id,9,9);
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info_datatables($ipd_id,$patient_id);
        $get_updated_data= $this->ipd_discharge_bill->ipd_ipd_detail_data($ipd_id,$patient_id);
        $users_data = $this->session->userdata('auth_users'); 
        $template_format= $this->ipd_discharge_bill->letterhead_template_format($users_data['parent_id']);
        $template_data=$template_format;
        $user_detail=$user_detail;
        $all_detail= $get_by_id_data;
        $header_replace_part=$template_data->page_details;


        $card_no='';
        if(!empty($get_ipd_patient_details['card_no']))
        {
          $card_no = ' ('. $get_ipd_patient_details['card_no'].')';
        }

         $payment_mode="";

        $disc_payment_mode = $get_ipd_patient_details['disc_payment_mode']; 

        if(!empty($disc_payment_mode))
        {
            $payment_mode = $disc_payment_mode.$card_no; 
        }
        else
        {
            $payment_mode=$get_ipd_patient_details['payment_mode'].$card_no;
           
        }
        
        
        //insurance_type
        $insurance_type= ucwords($get_ipd_patient_details['insurance_type']);
        $header_replace_part = str_replace("{insurance_type}", $insurance_type, $header_replace_part);  
        //insurance_type

        //insurance_type_name
        $insurance_type_name= ucwords($get_ipd_patient_details['insurance_type_name']);
        $header_replace_part = str_replace("{insurance_type_name}", $insurance_type_name, $header_replace_part);  
        //insurance_type_name

        //insurance_company_name
        $insurance_company_name= ucwords($get_ipd_patient_details['insurance_company_name']);
        $header_replace_part = str_replace("{insurance_company_name}", $insurance_company_name,$header_replace_part);  
        //insurance_company_name

        //insurance_policy_no
        $insurance_policy_no= ucwords($get_ipd_patient_details['insurance_policy_no']);
        $header_replace_part = str_replace("{insurance_policy_no}", $insurance_policy_no, $header_replace_part); 
        //
  
  if($get_ipd_patient_details['discharge_date']=="0000-00-00" || $get_ipd_patient_details['discharge_date']=='0000-00-00 00:00:00')
  {
     $header_replace_part = str_replace("{discharge_date}",'', $header_replace_part);
  }
  else
  {
      
      if($template_data->date_time_formate==1)
    {
        

        $header_replace_part = str_replace("{discharge_date}",date('d-m-Y h:i A',strtotime($get_ipd_patient_details['discharge_date'])), $header_replace_part);

    }
    else
    {
         $header_replace_part = str_replace("{discharge_date}",date('d-m-Y',strtotime($get_ipd_patient_details['discharge_date'])), $header_replace_part); 
    }
   
  }

    $simulation = get_simulation_name($get_ipd_patient_details['simulation_id'])
    ;
    $header_replace_part = str_replace("{patient_name}",$simulation.''.$get_ipd_patient_details['patient_name'], $header_replace_part);
    $header_replace_part = str_replace("{patient_reg_no}",$get_ipd_patient_details['patient_code'], $header_replace_part);
    $address = $get_ipd_patient_details['address'];
    $pincode = $get_ipd_patient_details['pincode'];         
    
    $patient_address = $address.' - '.$pincode;

     $header_replace_part = str_replace("{patient_address}",$patient_address, $header_replace_part);
    $template_data->setting_value = str_replace("{room_type}",$get_ipd_patient_details['room_category'],$template_data->setting_value);
    /* $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])),$template_data->setting_value);*/
     
     
     if($template_data->date_time_formate==1)
      {
        $admission_time='';
        if(!empty($get_ipd_patient_details['admission_time']) && $get_ipd_patient_details['admission_time']!='00:00:00' && strtotime($get_ipd_patient_details['admission_time'])>0)
        {
            $admission_time = date('h:i A', strtotime($get_ipd_patient_details['admission_time'])); 
        }

        
        $header_replace_part = str_replace("{admission_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).' '.$admission_time, $header_replace_part);

      }
      else
      {
         $header_replace_part = str_replace("{admission_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])), $header_replace_part);
      }
    

    $tot_amount='<div style="width:100%;border-top:1px solid #111;">
                        <div style="float:left;font-weight:bold;">Total Amount:</div>
                        <div style="float:right;font-weight:bold;">'.$get_ipd_patient_details['total_amount_dis_bill'].'</div>
                    </div>';
   $middle_replace_part=$template_data->page_middle;
     $middle_replace_part = str_replace("{total_amount}",$tot_amount,$middle_replace_part);

   $middle_replace_part = str_replace("{advance_amount}",$get_ipd_patient_details['advance_payment_dis_bill'], $middle_replace_part);
      $middle_replace_part = str_replace("{discount}",$get_ipd_patient_details['discount_amount_dis_bill'], $middle_replace_part);

if(!empty($get_ipd_patient_details['paid_amount_dis_bill']))
{
$paid_amount_dis_bill = $get_ipd_patient_details['paid_amount_dis_bill'];
}
else
{
$paid_amount_dis_bill = '0.00';
}


      $middle_replace_part = str_replace("{received}",$paid_amount_dis_bill, $middle_replace_part); //total_amount_dis_bill 02-12-2017
     $middle_replace_part = str_replace("{refund}",$get_ipd_patient_details['refund_amount_dis_bill'], $middle_replace_part);
     if($get_ipd_patient_details['balance_amount_dis_bill'] < 0)
     {
        $paid_balance = '0.00';
     }
     else
     {
        $paid_balance = $get_ipd_patient_details['balance_amount_dis_bill'];
     }
    
         $template_data->setting_value = str_replace("{specialization_level}",'',$template_data->setting_value);
         $header_replace_part = str_replace("{specialization}",$get_ipd_patient_details['specialization'], $header_replace_part);
    
    if(!empty($get_ipd_patient_details['doctor_name']))
    {
        $header_replace_part = str_replace("{doctor_name}",'Dr. '.$get_ipd_patient_details['doctor_name'], $header_replace_part);
    }
    else
    {
         $header_replace_part = str_replace("{doctor_name}",'',$template_data->setting_value);
          $header_replace_part = str_replace("doctor_name:",'',$template_data->setting_value);
    }
    
    $header_replace_part = str_replace("{bill_no}",$get_ipd_patient_details['discharge_bill_no'], $header_replace_part);
    $header_replace_part = str_replace("{mobile_no}",$get_ipd_patient_details['mobile_no'], $header_replace_part);


    if(!empty($get_ipd_patient_details['ipd_no']))
    {
        
        $header_replace_part= str_replace("{ipd_no}",$get_ipd_patient_details['ipd_no'],$header_replace_part);
    }

    if(!empty($get_ipd_patient_details['admission_date']))
    {
        $booking_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">IPD Reg. Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).'</div>
            </div>';
         $header_replace_part = str_replace("{booking_date}",$booking_date, $header_replace_part);
    }

    if(!empty($get_ipd_patient_details['created_date']))
    {
        $receipt_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Receipt Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y h:i A',strtotime($get_ipd_patient_details['created_date'])).'</div>
            </div>';
        $template_data->setting_value = str_replace("{receipt_date}",$receipt_date,$template_data->setting_value);
    }

    if(!empty($get_ipd_patient_details['room_no']))
    {
        $room_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Room No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['room_no'].'</div>
            </div>';
        $middle_replace_part = str_replace("{room_no}",$room_no,$middle_replace_part);
    }
    else
    {
         $middle_replace_part = str_replace("{room_no}",'',$middle_replace_part);
    }

    if(!empty($get_ipd_patient_details['bad_no']))
    {
        $bed_no = '<div style="float:left;width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['bad_no'].'</div>
            </div>';
        $middle_replace_part = str_replace("{bed_no}",$bed_no,$middle_replace_part);
    }
    else
    {
         $middle_replace_part = str_replace("{bed_no}",'',$middle_replace_part);
    }

    if(!empty($get_ipd_patient_details['mlc']) && $get_ipd_patient_details['mlc']==1)
    {
        $mlc = '<div style="float:left;width:100%;">
            <div style="float:left;width:40%;line-height:19px;font-weight:600;">MLC:</div>

            <div style="float:left;width:60%;line-height:19px;">Yes</div>
            </div>';
        $template_data->setting_value = str_replace("{mlc}",$mlc,$template_data->setting_value);
    }
    else
    {
         
        $middle_replace_part = str_replace("{mlc}",'',$middle_replace_part);
        $middle_replace_part = str_replace("MLC:",' ',$middle_replace_part);
        $template_data->setting_value = str_replace("MLC :",' ',$template_data->setting_value);
        $template_data->setting_value = str_replace("MLC",' ',$template_data->setting_value);
    }
    $middle_replace_part=str_replace("{payment_mode}",'',$middle_replace_part);

     $middle_replace_part=str_replace("{payment_table}",'',$middle_replace_part);
     $middle_replace_part=str_replace("{tds}",'',$middle_replace_part);

            $table_data='<table width="100%" border="0" cellpadding="4" cellspacing="0" style="border-collapse:collpase;font:13px arial;">
                          <tr>
                            <th align="left" style="width:80px;">Sr. No.</th>
                            <th align="left">Particulars</th>
                            <th align="right">Date</th>
                            <th align="right">Qty</th>
                            <th align="right">Rate</th>
                            <th align="right">Amount</th>
                          </tr><tbody>
                                ';
                                //echo "<pre>"; print_r($all_detail['CHARGES']);

                        $i=1;
                        $heading_of_particular='';
                        $array_data=array();
                        $total_amount='';
                        $v=1;
                        $type_one = 0;
                        $type_two = 0;
                        $perticuler_charge = [];
                        
                        
                        
                        if(!empty($all_detail['CHARGES'])){
                        foreach($all_detail['CHARGES'] as  $details_data)
                        {  
                            
                            $table_data.='';
                            if($details_data['type']==1 && $type_one==0 && $details_data['type']!=5)
                            {
                                $table_data.='
                                              <tr>
                                                <th colspan="6" align="left">Registration charges</th>
                                              </tr>';  
                                $type_one = 1;
                            }
                            else if(($details_data['type']==3 || $details_data['type']==5) && $type_two==0)
                            {
                                $i=1;
                               $heading="Particulars charge";
                                $table_data.='<tbody>
                                              <tr>
                                                <th colspan="6" align="left">'.$heading.'</th>
                                              </tr>';
                                $type_two = 1;

                            }
                      
                        
                        $table_data.='
                                      <tr>
                                        <td>'.$i.'</td>
                                        <td>'.$details_data['particular'].'</td>
                                        <td align="right">'.date('d-m-Y',strtotime($details_data['start_date'])).'</td>
                                        <td align="right">1.00</td>
                                        <td align="right">'.$details_data['quantity'].'</td>
                                        <td align="right">'.$details_data['net_price'].'</td>
                                      </tr>
                                          ';
                                $i ++; 
                                
                                
                        //$j++;
                                $total_amount=$total_amount+$details_data['net_price'];
                        }
                        } 

                    
                    $k=1;
                    $medi_type=0;
                    if(!empty($all_detail['medicine_payment']))
                    {
                            $net_medicine_payment_data=array();
                            foreach($all_detail['medicine_payment'] as $payment )
                            {
                            if($medi_type ==0)
                            {
                                $heading="Medicine Charge";
                                $table_data.='<tr>
                                                <th colspan="6" align="left">'.$heading.'</th>
                                              </tr>';
                                $medi_type = 1;
                            }
                            $table_data.='<tr>
                                            <td>'.$k.'</td>
                                            <td>'.$payment->particular.'</td>
                                            <td>'.date('d-m-Y',strtotime($payment->start_date)).'</td>
                                            <td></td>
                                            <td></td>
                                            <td align="right">'.$payment->net_price.'</td></tr>
                            ';
                            $k ++; 
                            $net_medicine_payment_data[]= $payment->net_price;
                            }
                    }


                    $k=1;
                    $pathology_type=0;
                    if(!empty($all_detail['pathology_payment']))
                    {
                            $net_pathology_payment_data=array();
                            foreach($all_detail['pathology_payment'] as $payment )
                            {
                            if($pathology_type ==0)
                            {
                                $heading="Pathology Test";
                                $table_data.='<tr>
                                                <th colspan="6" align="left">'.$heading.'</th>
                                              </tr>';
                                $pathology_type = 1;
                            }
                            $table_data.='<tr><td>'.$k.'</td>
                                            <td>'.$payment->particular.'</td>
                                            <td>'.date('d-m-Y',strtotime($payment->start_date)).'</td>
                                            <td></td>
                                            <td></td>
                                            <td>'.$payment->net_price.'</td></tr>';
                            $k ++; 
                            $net_pathology_payment_data[]= $payment->net_price;
                            }
                    }

                        if(isset($total_amount) && isset($net_medicine_payment_data[0]))
                        {
                                 $balance= $total_amount-$net_medicine_payment_data[0];
                        }
                        else
                        {
                                 $balance='';
                        }
                        
                    $s=1;
                    if(!empty($all_detail['advance_payment']))
                    {
                            $net_advance_data=array();
                            foreach($all_detail['advance_payment'] as $payment )
                            {
                            $table_data.='<tr>
                                                <th colspan="6" align="left" style="width:100%;">'.$heading.'</th>
                                              </tr>'; 
                            $table_data.='<tr><td>'.$k.'</td>
                                            <td>'.$payment->particular.'</td>
                                            <td>'.date('d-m-Y',strtotime($payment->start_date)).'</td>
                                            <td></td>
                                            <td></td>
                                            <td>'.$payment->net_price.'</td></tr>';
                            $s ++; 
                            $net_advance_data[]= $payment->net_price;
                            }
                    }

                  $table_data.='</tbody></table>';

                    /*if(isset($total_amount) && isset($net_advance_data[0]))
                    {
                             $balance= $total_amount-$net_advance_data[0];
                    }
                    else
                    {
                             $balance='';
                    }*/
                    
    if(isset($total_amount) && (isset($net_advance_data[0]) || isset($paid_amount_dis_bill) ) )
    {
             $balance= $total_amount-$net_advance_data[0]-$paid_amount_dis_bill;
             if(!empty($get_ipd_patient_details['discount_amount_dis_bill']) && isset($get_ipd_patient_details['discount_amount_dis_bill']))
             {
               $balance= $balance-$get_ipd_patient_details['discount_amount_dis_bill'];
             }
             if($balance>0)
             {
                $balance = number_format($balance,2);    
             }
             else
             {
                 $balance = '0.00';
             }
             
    }
    else
    {
             $balance='0.00';
    }

                    
                 //print_r(array_unique($array_data));
                    
    $middle_replace_part = str_replace("{table_data}",$table_data,$middle_replace_part);
   
    $middle_replace_part  = str_replace("{received_amount}",$total_amount,$middle_replace_part);
   $middle_replace_part  = str_replace("{balance}",$balance,$middle_replace_part);
    $middle_replace_part  = str_replace("{signature}",ucfirst($get_ipd_patient_details['user_name']),$middle_replace_part);
  //  echo $middle_replace_part->$template_data->page_middle;die;
    $genders = array('0'=>'F','1'=>'M');
    $gender = $genders[$get_ipd_patient_details['gender']];
    $age_y = $get_ipd_patient_details['age_y']; 
    $age_m = $get_ipd_patient_details['age_m'];
    $age_d = $get_ipd_patient_details['age_d'];

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

     $header_replace_part = str_replace("{patient_age}",$gender_age, $header_replace_part);

    $template_data->setting_value = str_replace("{Quantity_level}",'',$template_data->setting_value);

   
      $footer_data_part = $template_data->page_footer;
      $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 

    
   
     if($type=='Download')
    {
        if($template_data->header_pdf==1)
        {
           $page_header = $template_data->page_header;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$template_data->page_header.'</div>';
        }

        if($template_data->details_pdf==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_data->middle_pdf==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_data->footer_pdf==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
        }
       $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part);
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf'; 
        $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
        // $this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "F");
        
    }
    else 
    { 

    // echo $middle_replace_part;die;
        
        if($template_data->header_print==1)
        {
           $page_header = $template_data->page_header;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$template_data->page_header.'</div><br></br>';
        }

        if($template_data->details_print==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_data->middle_print==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_data->footer_print==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
           $footer_data_part = '<p style="height:'.$template_data->pixel_value.'px;"></p>'; 
            $this->m_pdf->pdf->defaultfooterline = 0;
        }

        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part); 
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
    }  
  
  
      
    }
    
    public function deleteAll_charges()
    {
      $post=$this->input->post();
       if(!empty($post))
       {
           $result = $this->ipd_discharge_bill->deleteAll_charges($post['row_id'],$post['ipd_id'],$post['patient_id']);
           $this->session->set_flashdata('success','Charges has been successfully deleted');
           echo '1';
           exit;
       }
    }
    
    


    
}
