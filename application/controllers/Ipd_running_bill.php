<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_running_bill extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('ipd_running_bill/ipd_running_bill_model','ipd_running_bill');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
        //unauthorise_permission(129,778);
        $this->session->unset_userdata('running_bill_serach');
        //$this->session->unset_userdata('ipd_particular_billing');
        //$this->session->unset_userdata('ipd_particular_payment');
        $data['page_title'] = 'IPD Running Bill'; 
        $data['form_data'] = array('patient_name'=>'','mobile_no'=>'');
        $this->load->view('ipd_running_bill/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission(129,778);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->ipd_running_bill->get_datatables();
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

    public function running_bill_info()
     {
          $data['ipd_id'] = $this->input->get('ipd_id');
          $data['patient_id'] = $this->input->get('patient_id');
           $list = $this->ipd_running_bill->get_running_bill_info_datatables($data['ipd_id'],$data['patient_id']);  
           //print_r($list);die;
          $data['running_bill_data']=$list;
          $data['page_title'] = 'Running Bill List';
          $this->load->view('ipd_running_bill/running_bill_info.php',$data);
          // $this->load->view('medicine_stock/medicine_allot_history',$data);
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

    function end_now($data_id="",$start_date="",$ipd_id="",$patient_id="")
    {
         
        $this->load->model('general/general_model'); 
        $data['page_title'] = "End Date";
        $data['form_error'] = [];
        $data['ipd_id'] = $ipd_id;
        $data['patient_id'] = $patient_id;
        $reg_no = generate_unique_id(11);
        $post = $this->input->post();
        $data['form_data'] = array(
                                    "data_id"=>$data_id,
                                    'start_date'=>$start_date,
                                    'ipd_id'=>$ipd_id,
                                    'patient_id'=>$patient_id,
                                    "end_date"=>$start_date
                                    
                                  );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
               
                $this->ipd_running_bill->save_running_data();
              $this->session->set_flashdata('success','End date has been successfully added.');
                 echo 1;
                return false; 
            }
            else
            {

                $data['form_error'] = validation_errors();  
               //print_r($data['form_error']);die;
            }       
        }
       $this->load->view('ipd_running_bill/end_now',$data);
    }

    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('end_date', 'End Date', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(11); 
            $data['form_data'] = array(
                                    "data_id"=>$post['data_id'],
                                    'start_date'=>$post['end_date'],
                                    "end_date"=>$post['end_date']
                                  );  
            return $data['form_data'];
        }   
    }

    public function delete($id="")
    {
       // unauthorise_permission(56,370);
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_running_bill->delete($id);
           $response = "IPD Running Bill successfully deleted.";
            $this->session->set_flashdata('success','Particulars has been successfully deleted.');
           echo $response;
       }
    }
    
    public function delete_test($id="")
    {
       // unauthorise_permission(56,370);
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_running_bill->delete_test($id);
           $response = "IPD Test successfully deleted.";
            $this->session->set_flashdata('success','Test has been successfully deleted.');
           echo $response;
       }
    }
    
    
    
    public function print_running_bill($ipd_id="",$patient_id="")
    {

        $this->load->model('general/general_model');
        $data['get_ipd_patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->ipd_running_bill->get_running_bill_info_datatables($ipd_id,$patient_id);



        $template_format= $this->ipd_running_bill->template_format(array());
        //print_r($template_format);
        $data['template_data']=$template_format;
        $data['user_detail']=$user_detail;
        $data['all_detail']= $get_by_id_data;
        //print_r($data['all_detail']['CHARGES']);die;
        $this->load->view('ipd_running_bill/print_template_running_bill',$data);
    }
    
    /* 23-09-2019 */
    public function print_letterhead_running_bill($ipd_id="",$patient_id="")
    {
        $this->load->library('m_pdf');
        $this->load->model('general/general_model');
        $get_ipd_patient_details= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
       // echo "<pre>";print_r();die;
        $user_detail= $this->session->userdata('auth_users');
        $get_by_id_data=$this->ipd_running_bill->get_running_bill_info_datatables($ipd_id,$patient_id);



        $template_format= $this->ipd_running_bill->letterhead_template_format(array());
       // print_r($template_format);die;
        $template_data=$template_format;
        $user_detail=$user_detail;
        $all_detail= $get_by_id_data;

$header_replace_part=$template_data->page_details;
$middle_replace_part=$template_data->page_middle;


$payment_mode='';

    $simulation = get_simulation_name($get_ipd_patient_details['simulation_id']);
    $header_replace_part = str_replace("{patient_name}",$simulation.''.$get_ipd_patient_details['patient_name'],$header_replace_part);
    $header_replace_part = str_replace("{patient_reg_no}",$get_ipd_patient_details['patient_code'],$header_replace_part);
    $address = $get_ipd_patient_details['address'];
    $pincode = $get_ipd_patient_details['pincode'];         
    
    $patient_address = $address.' - '.$pincode;

    $header_replace_part = str_replace("{patient_address}",$patient_address,$header_replace_part);
    $header_replace_part = str_replace("{bill_no}",'',$header_replace_part);
     $header_replace_part = str_replace("{room_type}",$get_ipd_patient_details['room_category'],$header_replace_part);
     $header_replace_part = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])),$header_replace_part);
   
         $template_data->setting_value = str_replace("{specialization_level}",'',$template_data->setting_value);
         $template_data->setting_value = str_replace("{specialization}",'',$template_data->setting_value);
    //}

    if(!empty($get_ipd_patient_details['doctor_name']))
    {
        $consultant_new = '<table width="100%" cellpadding="4" style="font:13px arial;">
        <tr>
            <th>Assigned Doctor :</th>

            <th>'.'Dr. '. $get_ipd_patient_details['doctor_name'].'</th>
            </tr>';
        $template_data->setting_value = str_replace("{Consultant}",$consultant_new,$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{Consultant}",'',$template_data->setting_value);
    }
    
   $header_replace_part = str_replace("{mobile_no}",$get_ipd_patient_details['mobile_no'],$header_replace_part);


    if(!empty($get_ipd_patient_details['ipd_no']))
    {
        // $receipt_code = '<tr>
        //                 <th>IPD No.:</th>

        //     <th>'.$get_ipd_patient_details['ipd_no'].'</th>
        //     </tr>';
           $receipt_code= $get_ipd_patient_details['ipd_no'];
        $header_replace_part = str_replace("{ipd_no}",$receipt_code,$header_replace_part);
    }

    if(!empty($get_ipd_patient_details['admission_date']))
    {
        $booking_date = '<tr>
                        <th>IPD Reg. Date:</th>

            <th>'.date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).'</th>
            </tr>';
        $header_replace_part= str_replace("{booking_date}",$booking_date,$header_replace_part);
    }

    if(!empty($get_ipd_patient_details['created_date']))
    {
        $receipt_date = '<tr>
                        <th>Receipt Date:</th>

            <th>'.date('d-m-Y h:i A',strtotime($get_ipd_patient_details['created_date'])).'</th>
            </tr>';
        $header_replace_part= str_replace("{receipt_date}",$receipt_date,$header_replace_part);
    }

    if(!empty($get_ipd_patient_details['room_no']))
    {
        
        $header_replace_part = str_replace("{room_no}",$get_ipd_patient_details['room_no'],$header_replace_part);
    }
    else
    {
        $header_replace_part = str_replace("{room_no}",'',$header_replace_part);
         $header_replace_part = str_replace("Room No.:",'',$header_replace_part);
        $header_replace_part = str_replace("Room No",'',$header_replace_part);
         
    }

    if(!empty($get_ipd_patient_details['bad_no']))
    {
       /* $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['bad_no'].'</div>
            </div>';*/
        $header_replace_part = str_replace("{bed_no}",$get_ipd_patient_details['bad_no'],$header_replace_part);
    }
    else
    {
         $header_replace_part = str_replace("{bed_no}",'',$header_replace_part);
         $header_replace_part = str_replace("Bed No.:",'',$header_replace_part);
         $header_replace_part = str_replace("Bed No.",'',$header_replace_part);
    }

    if(!empty($get_ipd_patient_details['mlc']) && $get_ipd_patient_details['mlc']==1)
    {
        $mlc = '<tr>
                        <th>MLC:</th>

            <th>Yes</th>
            </tr>';
        $header_replace_part = str_replace("{mlc}",$mlc,$header_replace_part);
    }
    else
    {
         
        $header_replace_part = str_replace("{mlc}",'',$header_replace_part);
        $header_replace_part = str_replace("MLC:",' ',$header_replace_part);
        $header_replace_part = str_replace("MLC :",' ',$header_replace_part);
        $header_replace_part = str_replace("MLC",' ',$header_replace_part);
    }
    
            $table_data='<tr>
                                <th width="80px">Sr. No.</th>
                                <th>Particulars</th>
                                <th align="right">Date</th>
                                <th align="right">Qty</th>
                                <th align="right">Rate</th>
                                <th align="right">Amount</th>
                        </tr>';


                        $i=1;
                        $heading_of_particular='';
                        $actual_payment_data=array();
                        $v=1;
                        $perticuler_charge = [];
                        $i=1;
                        $array_data=array();
                        $total_amount='';
                        $v=1;
                        $type_one = 0;
                        $type_two = 0;
                        $perticuler_charge = [];
                        $new_arr=array();
                        $uni_arr = [];
                        $new_array=array();
                        foreach($all_detail['CHARGES'] as $charges)
                        { 
                           
                           $perticuler_charge[] = array('particular'=>$charges['particular'],'start_date'=>$charges['start_date'],'price'=>$charges['price'],'quantity'=>$charges['quantity'],'net_price'=>$charges['net_price'],'type'=>$charges['type']);
                        } 
 
                        $unique_arr = array_unique(array_column($perticuler_charge,'particular'));
                       
                        foreach($unique_arr as $unique)
                        {
                            $uni_arr[str_replace(" ","",$unique)] = array('particular'=>$unique,'start_date'=>'','price'=>0,'quantity'=>0,'net_price'=>0,'type'=>0);
                        }
                        
                        $array_final_perticuler = [];

                        $column_all = array_column($perticuler_charge,'particular');
                        $unique_perticuller = array_unique($column_all);
                        $summurise_arr = [];
                        $i=1;
                        //print '<pre>'; print_r($unique_perticuller);die; 
                        foreach($perticuler_charge as $final_charge)
                        {  
                           //print '<pre>'; print_r($final_charge);die; 
                           if(!empty($unique_perticuller))
                            { 
                                foreach($unique_perticuller as $uni_per)
                                { 
                                    if(trim($final_charge['particular'])==trim($uni_per))
                                    {  
                                          if(isset($summurise_arr[$final_charge['particular']]))
                                          {
                                              $summurise_arr[$final_charge['particular']] = array('particular'=>$final_charge['particular'],'type'=>$final_charge['type'], 'quantity'=>$summurise_arr[$uni_per]['quantity']+$final_charge['quantity'], 'price'=>$summurise_arr[$uni_per]['price']+$final_charge['price'], 'net_price'=>$summurise_arr[$uni_per]['net_price']+$final_charge['net_price'],'start_date'=>$final_charge['start_date'],'type'=>$final_charge['type']);
                                          } 
                                          else
                                          {
                                              $summurise_arr[$final_charge['particular']] = array('particular'=>$final_charge['particular'],'type'=>$final_charge['type'], 'quantity'=>$final_charge['quantity'], 'price'=>$final_charge['price'], 'net_price'=>$final_charge['net_price'],'start_date'=>$final_charge['start_date'],'type'=>$final_charge['type']);
                                          } 
                                       
                                    }  
                                }
                                
 
                            }  
                            $i++;
                            
                          
                        } 
                     
                         array_sort_by_column($summurise_arr, 'start_date');
                        $i=1;
                        foreach($summurise_arr as $details_data)
                        {  
                            
                            $table_data.='';
                            if($details_data['type']==1 && $type_one==0 && $details_data['type']!=5)
                            {
                                $table_data.='<tr>
                                    <th colspan="6" align="left">Registration Charge</th>
                                </tr>';  
                                $type_one = 1;
                            }
                           else if(($details_data['type']==3 || $details_data['type']==5) && $type_two==0)
                            {
                                $i=1;
                               $heading="Particulars charge";
                                $table_data.='<tr>
                                    <th colspan="6" align="left">'.$heading.'</th>
                                </tr>';
                                $type_two = 1;

                            }
                      
                        
                        $table_data.='<tr>
                                            <td width="80px">'.$i.'</td>
                                            <td>'.$details_data['particular'].'</td>
                                            <td align="right">'.date('d-m-Y',strtotime($details_data['start_date'])).'</td>
                                            <td align="right">'.$details_data['quantity'].'</td>
                                            <td align="right">'.$details_data['price'].'</td>
                                            <td align="right">'.$details_data['net_price'].'</td>
                                 </tr>';
                                $i ++; 
                                
                                
                        //$j++;
                                $total_amount=$total_amount+$details_data['net_price'];
                        } $s=1;

                       /* medicine data */
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
                                    $table_data.='<tr><th>'.$heading.'</th> </tr>';
                                    $medi_type = 1;
                                }
                                    $table_data.='<tr>
                                                    <td width="80px">'.$k.'</td>
                                                    <td>'.$payment->particular.'</td>
                                                    <td align="right">'.date('d-m-Y',strtotime($payment->start_date)).'</td>
                                                    <td align="right"></td>
                                                    <td align="right"></td>
                                                    <td align="right">'.$payment->net_price.'</td>
                                                </tr>';
                                    $k ++; 
                                    $net_medicine_payment_data[]= $payment->net_price;
                            }
                        }

                       /* medicine data */

                       /* pathalogy charges */
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
                                        $table_data.='<tr><th>'.$heading.'</th> </tr>';
                                        $pathology_type = 1;
                                    }
                                    $table_data.='<tr>
                                                    <td width="80px">'.$k.'</td>
                                                    <td>'.$payment->particular.'</td>
                                                    <td align="right">'.date('d-m-Y',strtotime($payment->start_date)).'</td>
                                                    <td align="right""></td>
                                                    <td align="right""></td>
                                                    <td align="right"">'.$payment->net_price.'</td>
                                                    </tr>';
                                    $k ++; 
                                    $net_pathology_payment_data[]= $payment->net_price;
                                }
                            }
                       /* pathalogy charges */

                        $net_advance_data=array();
                        foreach($all_detail['advance_payment'] as $payment )
                        {
                        $table_data.='<tr><th>Advance Payment</th> </tr>'; 
                        $table_data.='<tr>
                                        <td width="80px">'.$s.'</td>
                                        <td>'.$payment->particular.'</td>
                                        <td align="right">'.date('d-m-Y',strtotime($payment->start_date)).'</td>
                                        <td align="right"></td>
                                        <td align="right"></td>
                                        <td align="right">'.$payment->net_price.'</td>
                        </tr></table>';
                        $s ++; 
                        $net_advance_data[]= $payment->net_price;
                        }
                        if(isset($total_amount) && isset($net_advance_data[0]))
                        {
                                 $balance= $total_amount-$net_advance_data[0];
                        }
                        else
                        {
                                 $balance=$total_amount;
                        }

                        if(isset($net_advance_data[0]))
                        {
                            $net_advance = $net_advance_data[0];
                        }
                        else
                        {
                            $net_advance='0.00';
                        }
                 //print_r(array_unique($array_data));
                    
    $middle_replace_part  = str_replace("{table_data}",$table_data,$middle_replace_part);
    $middle_replace_part = str_replace("{total_amount}",$total_amount,$middle_replace_part);
    $middle_replace_part  = str_replace("{received_amount}",$net_advance,$middle_replace_part);
    $middle_replace_part  = str_replace("{balance}",$balance,$middle_replace_part);
    $middle_replace_part  = str_replace("{signature}",$user_detail['user_name'],$middle_replace_part);
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

    $header_replace_part  = str_replace("{patient_age}",$gender_age,$header_replace_part);

    $header_replace_part  = str_replace("{Quantity_level}",'',$header_replace_part);


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
        //print_r($data['all_detail']['CHARGES']);die;
       // $this->load->view('ipd_running_bill/print_template_running_bill',$data);
    }

}
    /* 23-09-2019 */
    
}
