<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gipsa_ipd_discharge_bill extends CI_Controller {
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
    $this->load->model('gipsa_ipd_discharge_bill/ipd_discharge_bill_model','ipd_discharge_bill');
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
        $this->load->view('gipsa_ipd_discharge_bill/list',$data);
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
     // print_r($discharge_date);die;
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


          $data['patient_id'] = $patient_id;
          $list = $this->ipd_discharge_bill->get_discharge_bill_info_datatables($data['ipd_id'],$data['patient_id']);
          //echo "<pre>";print_r($ipd_details); exit;
          $this->load->model('general/general_model');
          $data['payment_mode']=$this->general_model->payment_mode();
//echo $ipd_details['discharge_payment_mode'];
          $get_payment_detail= $this->general_model->payment_ipd_mode_detail_according_to_field($ipd_details['discharge_payment_mode'],$ipd_id,9,9);
          $total_values=array();
          for($i=0;$i<count($get_payment_detail);$i++) {
          $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

          }
          //echo "<pre>"; print_r($total_values); exit;
          $payamount=$this->ipd_discharge_bill->get_paid_amount($data['ipd_id'],$data['patient_id']);
        //  print_r($payamount);die;
          if($payamount[0]->advance_payment_dis_bill>$payamount[0]->total_amount_dis_bill){
            $data['get_paid_amount']=$payamount[0]->refund_amount_dis_bill;
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
            //echo "hello";die;
              $dischargedate = date('d-m-Y h:i A',strtotime($payamount[0]->discharge_date));
          }   
          else
          {
          //  echo "hey";die;
              //$dischargedate = date('d-m-Y h:i A'); 
              $dischargedate = date('d-m-Y h:i A',$discharge_date); 
             // print_r($dischargedate);die;
          }

          //$data['ipd_bill_info']=$this->ipd_discharge_bill->ipd_bill_info();
          $data['form_data']= array('discharge_date'=>$dischargedate,//date('d-m-Y H:i:s'),
                                    'particular_date'=>date('d-m-Y'),
                                    'discharge_bill_no'=>$bill_no,
                                    "payment_mode"=>$ipd_details['discharge_payment_mode'],
                                    "amount"=>'',
                                    'field_name'=>$total_values,
                                    //'bank_name'=>'',
                                    //'card_no'=>'',
                                    'cheque_date'=>date('d-m-Y'),
                                    //'cheque_no'=>'',
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
                    redirect(base_url('gipsa_ipd_discharge_bill/discharge_bill_info/'.$ipd_id.'/'.$patient_id.'/?status=print&billstatus=printbill')); // /?status=print

                }
                else
                {

                    $data['form_error'] = validation_errors();

                }  
            }
         
          $this->load->view('gipsa_ipd_discharge_bill/discharge_bill_info.php',$data);
          // $this->load->view('medicine_stock/medicine_allot_history',$data);
     }

     private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
       
        /*if(isset($_POST['payment_mode']) && $_POST['payment_mode']==2) {
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
        }*/
          $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required');
          $this->form_validation->set_rules('group', 'group', 'trim|required');
        if(isset($post['field_name']))
        {
           $this->form_validation->set_rules('field_name[]', 'field', 'trim|required');

        }
       
          /*if(isset($post['bank_name'])){

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
          */
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
                                        'particular_date'=>$_POST['date'],
                                        'discharge_bill_no'=>$_POST['discharge_bill_no'],
                                        "payment_mode"=>$post['payment_mode'],
                                        'field_name'=>$total_values
                                        //'bank_name'=>$bank_name,
                                        //'card_no'=>$post['transaction_no'],
                                       // 'cheque_date'=>$cheque_date,
                                       // 'cheque_no'=>$cheque_no,
                                       // 'transaction_no'=>$transaction_no,
                                       // "payment_date"=>$payment_date
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
       $this->load->view('gipsa_ipd_discharge_bill/end_now',$data);
    }

 
    function get_particular_data($vals="",$group="")
    {
        if(!empty($vals))
        {
            $result = $this->ipd_discharge_bill->get_particular_data($vals,$group);  
        if(!empty($result))
        {
            echo json_encode($result,true);
        } 
        } 
    }

    function add_perticulars($ipd_id="",$patient_id=""){
     $post= $this->input->post();
     if(!empty($post['particular']))
     {
        $this->ipd_discharge_bill->save_particulars($ipd_id,$patient_id);
        $this->session->set_flashdata('success','Particular has been successfully added.');
         echo '1';exit;
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
            }
       }
        
        $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''),'total_amount_bill'=>number_format($total_amount,2,'.',''),'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'balance_due'=>number_format($blance_dues,2,'.',''),'discount'=>$post['discount'],'total_advance_amount'=>number_format($post['total_advance_amount'],2,'.',''),'refund_amount'=>number_format($refund_amount,2,'.',''),'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       
        
    } 
    public function print_discharge_bill($ipd_id="",$patient_id="")
    {

        $this->load->model('general/general_model');
        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $data['get_ipd_patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id,9,9);
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
        $this->load->view('gipsa_ipd_discharge_bill/print_template_discharge_bill',$data);
    }

    public function update_discharge_date()
    {
       $post= $this->input->post();
       $result= $this->ipd_discharge_bill->update_charge_data();
       $this->session->set_flashdata('success','Particular has been successfully updated');
       echo '1';
       exit;
    }

    
    public function delete_charges($id="",$ipd_id="",$patient_id="")
    {
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_discharge_bill->delete_charges($id,$ipd_id,$patient_id);
           $this->session->set_flashdata('success','Particular has been successfully deleted');
        
           echo '1';
           exit;
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

      $this->load->view('gipsa_ipd_discharge_bill/upadte_advance_payment',$data);
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
       $this->load->view('gipsa_ipd_discharge_bill/upadte_advance_payment',$data);
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
        $this->load->view('gipsa_ipd_discharge_bill/print_template_consolidated',$data);
    }

    public function print_summarized_bill($ipd_id="",$patient_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('general/general_model');
        $discharge_bill_id= $this->session->userdata('discharge_bill_id');
        $data['get_ipd_patient_details'] = $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
        
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->ipd_discharge_bill->get_discharge_bill_info_datatables($ipd_id,$patient_id);
        $get_by_id=$this->ipd_discharge_bill->get_discharge_bill_info_according_to_group_datatables($ipd_id,$patient_id);

        /*  For Registration and Basic Charges */

          $get_other_charges=$this->ipd_discharge_bill->get_other_charges($ipd_id,$patient_id);



        /*  For Registration and Basic Charges */

        $template_format= $this->ipd_discharge_bill->template_format(array('branch_id'=>$users_data['parent_id']));
        //$template_format= $this->ipd_discharge_bill->template_format(array());
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        $data['all_detail_data']= $get_by_id;
        $data['other_charges']=$get_other_charges;
        //print_r($data['all_detail']['CHARGES']);die;
         $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $data['address_setting_list'] = $this->address_setting->get_master_unique();
        $this->load->view('gipsa_ipd_discharge_bill/print_template_summarized',$data);
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
        //print_r($template_format); //print_summarized_bill
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        //print_r($data['all_detail']['CHARGES']);die;
         $this->load->model('address_print_setting/address_print_setting_model','address_setting');
        $data['address_setting_list'] = $this->address_setting->get_master_unique();
        $this->load->view('gipsa_ipd_discharge_bill/print_template_discharge_bill_group_wise',$data);
    }


    /* group wise discharge bill */
    
    //  print discharge paid amount
     public function print_discharge_bill_paidamount($ipd_id="",$patient_id="")
    {

        $this->load->model('general/general_model');
        $data['get_ipd_patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
        $data['page_title'] = "Print Bookings";
        $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
        $ipd_booking_id = $ipd_id;
        $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
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
        $data['all_charge_detail']= $get_charge_by_id_data;
        $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        $data['page_type'] = 'Booking';
        $this->load->view('gipsa_ipd_discharge_bill/print_ipd_final_reciept_template',$data);
       
    }


    
}
